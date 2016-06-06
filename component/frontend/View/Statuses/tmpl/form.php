<?php
//-------------------------------------------------------------------------------------------------------------------------------------------
//
//form.php: is the project status update form. Used to gather data for a status update done by the user 04/2106/jk
//
//-------------------------------------------------------------------------------------------------------------------------------------------

$this->getContainer()->template->addCSS('media://com_hshrndreview/css/frontend.css', $this->getContainer()->mediaVersion);

JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');
//$document = &JFactory::getDocument();
//$document->addScript("includes/js/joomla.javascript.js");

$db = \JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('name');
$query->from($db->quoteName('#__hshrndreview_collaborators'));
$db->setQuery($query);
$allExistingCollaboratorsAsObjects = $db->loadObjectList();
$allExistingCollaborators = array();
$collaboratorArePartners = array();
$allExistingPartners = "";

// search who is already a partner

//all partner currently registered
$externalPartnerArray = preg_split("/\r\n|\n|\r/", $this->item->externalPartner);

$isFirstPartner=true;
//iterate over all collaborators within the group 
foreach($allExistingCollaboratorsAsObjects as $collaborator){
	$isAlreadyAPartner=false;
	//for each partner within the group retrieve whether they are also registerd partner for this project
	foreach ($externalPartnerArray as $partner) {
		if($collaborator->name==$partner){
			$isAlreadyAPartner=true;
		}
	}
	//if they are also partner in this project make sure that they will be selected in the list
	if($isAlreadyAPartner){
		array_push($collaboratorArePartners, 1);

		//build up the string for the readonly "external partner field" default value
		if(!$isFirstPartner){
			$allExistingPartners .= "\n";
		} 
		$allExistingPartners .= $collaborator->name;
	    $isFirstPartner=false;
	}
	else{
		array_push($collaboratorArePartners, 0);		
	}
	array_push($allExistingCollaborators, $collaborator->name);
}




$toolTipProjectObjective="Project Objective::Describe roughly the objectives of the project. Project objectives can be altered during every status update.";

$toolTipProjectResponsible="Project Responsible::Responsible project leader @your company. Default is the logged-in user. Responsible can be altered during every status update";

$toolTipProjectCosts="Project Costs::COSTS LISTED HERE SHOULD COVER THE WHOLE DEVELOPMENT PROCESS (e.g. incl. costs for certification, clinical studies etc.) Costs can be altered during every status update.";

$toolTipProjectStatus="Project Status::\"Active:\" Project is under progress; \"Finished:\" Project has been finished as planned. \"Terminated:\" Project has been finished unexpectedly. \"OnHold:\" Project is TEMPORARILY on hold (e.g. because of missing resources etc.) Can be altered during every status update.";

$toolTipURS="User Requirement Specification::Planned release date for the user requirement specification.";
$toolTipURSMilestone="Milestone: \"User Requirement Specification\"::If the user requirement specification has been released already check this box. Can be altered during every status update.";

$toolTipFS="Functional Specification::Planned release date for the functional specification. Release date can be altered during every status update.";
$toolTipFSMilestone="Milestone: \"Functional Specification\"::If the functional specification has been released already check this box. Can be altered during every status update.";

$toolTipExperimentalModel="Experimental Model Available::Planned release date for the experimental model. Release date can be altered during every status update.";
$toolTipExperimentalModelMilestone="Milestone: \"Experimental Model Available\"::If the experimental models are already available check this box. Can be altered during every status update.";

$toolTipPrototype="Prototypes Available::Planned release date for the prototypes (\"When can it be exhibit at trade fairs?\"). Release date can be altered during every status update.";
$toolTipPrototypeMilestone="Milestone: \"Prototypes Available\"::If the prototypes are already available check this box. Can be altered during every status update.";


$toolTipVolumeProduction="Volume Production Started::Planned starting date for the volume production (\"When can it sold to customers?\"). Volume production start date can be altered during every status update.";
$toolTipVolumeProductionMilestone="Milestone: \"Volume Production Started\"::If the volume production has started already check this box. Can be altered during every status update.";

$toolTipExternalPartner="External Project Partner::If there are external partners contributing significantly to the development process (e.g. supplier of a specific laser source, academic institutions providing substantial content) add them here. To do this seek first in the selection list \"Partner Known w/i the Group\" for your partner and select them (to select multiple or deselect some partner hold Ctrl.- or Cmd.- key pressed while clicking). If your partner is not yet in the selection list \"Partner Known w/i the Group\" add them in the input text field and press the \"Add New\" button."; 

$toolTipReasonForChange="Whenever a field value has been altered during the status update (e.g. Costs have changed...), the field \"Reason for a Change\" becomes mandatory. Add a quick comment why the change was necessary. Of course changing \"MS-reached\" checkboxes are excluded. Altered input fields turn slightly yellow to remind you what has been changed in the form so far.";
?>
<form action="index.php" method="post" accept-charset="utf-8">
	
	<div id="header">
		<h1> <?php echo ($_GET['isAssessment'] == 1 ? 'Assessment for <br>' : ''); ?> Status Update Development Project: <?php echo $_GET['projectName']; ?></h1>
	</div>
	
	<input type="hidden" name="option" value="com_hshrndreview"/>
   	<input type="hidden" name="view" value="Statuses"/>
   	<input type="hidden" name="task" value="<?php echo ($_GET['isAssessment'] == 1 ? 'addAssessement' : 'addStatusUpdate'); ?>" />
                         
	<!--because "fieldset disabled" does not work on IE we have to "if then else" the code --> 	
	<!--this is the assessment part of the form (only the assessement texarea is writable) --> 
	<?php if($_GET['isAssessment'] == 1) : ?>
		<h1> Submission Date: <?php $sdate = new DateTime($this->item->created_on); echo $sdate->format('d/m/Y'); ?></h1>

		<input type="hidden" name="hshrndreview_status_id" value="<?php echo $this->item->hshrndreview_status_id; ?>" />


	   <table	style="width:70%;">
		<col width="25%">
		<col width="30%">
		<col width="10%">
		<col width="25%">
	    <tr>
			<td class="hsh_td_tables_input"><b>Assessment</b></td>
	    	<td class="hsh_td_tables_input" colspan="3"><textarea name="projectAssessment" id="projectAssessment" class="hsh_multiline_text_input" required ><?php echo $this->item->projectAssessment; ?></textarea></td>
	 	</tr>
	 <tr>
        <td class="hsh_td_tables_input"><b>Project Objective</b></td>
        <td class="hsh_td_tables_input" colspan="3"><textarea readonly class="hsh_multiline_text_input"><?php echo $this->item->objective; ?></textarea></td>
     </tr>
     <tr>
         <td class="hsh_td_tables_input"><b>Project Responsible</b></td>
         <td class="hsh_td_tables_input" colspan="3"><input type="text" class="hsh_text_input" value="<?php echo $this->item->responsible; ?>" readonly/></td>
     </tr>
     <tr>
        <td class="hsh_td_tables_input"><b>Estimated Project Costs</b></td>
        <td class="hsh_td_tables_input" colspan="3"><input type="number" class="hsh_text_input" value="<?php echo $this->item->estimatedProjectCosts; ?>" readonly/></td>
     </tr>
     <tr>
         <td class="hsh_td_tables_input"><b>Project Status</b></td>
         <td class="hsh_td_tables_input" colspan="3">
			<select readonly>
			  <option value="">-Select-</option>
			  <option value="Active" <?php echo ($this->item->projectStatus=="Active" ? 'selected' : '');?> >Active</option>
			  <option value="Finished" <?php echo ($this->item->projectStatus=="Finished" ? 'selected' : '');?> >Finished</option>
			  <option value="OnHold" <?php echo ($this->item->projectStatus=="OnHold" ? 'selected' : '');?> >OnHold</option>
			  <option value="Terminated" <?php echo ($this->item->projectStatus=="Terminated" ? 'selected' : '');?> >Terminated</option>
			</select></td>
      </tr>
     <tr>
         <th align="left" class="span" colspan="4">User Requirement Specification </th>
     </tr>
     <tr>
		<td class="hsh_td_tables_input">Release Date </td>
        <td class="hsh_td_tables_input">
			<?php $currentDate=$this->item->msURSReleaseDate; echo JHTML::_('calendar', $currentDate, "" , "", '%Y-%m-%d', array('readonly'=>'1')  );?>
		</td>
		<td class="hsh_td_tables_input">MS reached </td>
		<td class="hsh_td_tables_input"><input type="checkbox" <?php echo ($this->item->msURSReleasedReached==1 ? 'checked' : '' );?> readonly> </td>
     </tr>
     <tr>
         <th align="left" class="span" colspan="4">Functional Specification </th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input">Release Date </td>
         <td class="hsh_td_tables_input">
 			<?php $currentDate=$this->item->msFSReleaseDate; echo JHTML::_('calendar', $currentDate, "" , "", '%Y-%m-%d', array('readonly'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input">MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" <?php echo ($this->item->msFSReleasedReached==1 ? 'checked' : '');?> readonly> </td>	
     </tr>
     <tr>
         <th align="left" class="span" colspan="4">Experimental Model Available</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input">Release Date </td>
         <td class="hsh_td_tables_input">
	 		 <?php $currentDate=$this->item->msExpModelReleaseDate; echo JHTML::_('calendar', $currentDate, "" , "", '%Y-%m-%d', array('readonly'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input">MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" <?php echo ($this->item->msExpModelReleasedReached==1 ? 'checked' : '');?> readonly> </td>
     </tr>

     <tr>
         <th align="left" class="span" colspan="4">Prototypes Available</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input">Release Date </td>
         <td class="hsh_td_tables_input">
	 		 <?php $currentDate=$this->item->msPrototypeReleaseDate; echo JHTML::_('calendar', $currentDate, "" , "", '%Y-%m-%d', array('readonly'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input">MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" <?php echo ($this->item->msPrototypeReleasedReached==1 ? 'checked' : '');?> readonly> </td>
     </tr>

     <tr>
         <th align="left" class="span" colspan="4">Volume Production Started</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input">Release Date </td>
         <td class="hsh_td_tables_input">
	 		 <?php $currentDate=$this->item->msVolumeProductionDate; echo JHTML::_('calendar', $currentDate, "" , "", '%Y-%m-%d', array('readonly'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input">MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" <?php echo ($this->item->msVolumeProductionReached==1 ? 'checked' : '');?> readonly> </td>
     </tr>
     <tr>
        <td class="hsh_td_tables_input"><b>External Project Partner</b></td> 
     	<td class="hsh_td_tables_input" colspan="3"><textarea class="hsh_multiline_text_input" readonly><?php echo $this->item->externalPartner; ?></textarea></td>
 	</tr>
     <tr>
        <td class="hsh_td_tables_input"><b>Reason for a Change</b></td>
        <td class="hsh_td_tables_input" colspan="3"><textarea class="hsh_multiline_text_input" readonly><?php echo $this->item->justificationForAChange; ?></textarea></td>
     </tr>
     <tr>
         <td align="center" colspan="4"><input type="submit" value="Submit" style="height:30px; width:100px" />      <input type="reset" value="Reset" style="height:30px; width:100px" /> </td>
     </tr>
	   </table>

	<?php else : ?>

   <table	style="width:70%;">
	<col width="25%">
	<col width="30%">
	<col width="10%">
	<col width="5%">		
      <tr>
        <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectObjective; ?>'><b>Project Objective</b></td>
        <td class="hsh_td_tables_input" colspan="3"><textarea name="objective" id="objective" onchange="someValueChanged(this)" class="hsh_multiline_text_input" required ><?php echo $this->item->objective; ?></textarea></td>
      </tr>

      <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectResponsible; ?>'><b>Project Responsible</b></td>
         <td class="hsh_td_tables_input" colspan="3"><input type="text" class="hsh_text_input" name="responsible" id="responsible" value="<?php echo $this->item->responsible; ?>" onchange="someValueChanged(this)" required /></td>
      </tr>

      <tr>
        <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectCosts; ?>'><b>Estimated Project Costs</b></td>
        <td class="hsh_td_tables_input" colspan="3"><input type="number" class="hsh_text_input" name="estimatedProjectCosts" id="estimatedProjectCosts" value="<?php echo $this->item->estimatedProjectCosts; ?>"  onchange="someValueChanged(this)" required/></td>
      </tr>

     <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectStatus; ?>'><b>Project Status</b></td>
         <td class="hsh_td_tables_input" colspan="3">
			<select required name="projectStatus" id="projectStatus" onchange="someValueChanged(this)">
			  <option value="">-Select-</option>
			  <option value="Active" <?php echo ($this->item->projectStatus=="Active" ? 'selected' : '');?> >Active</option>
			  <option value="Finished" <?php echo ($this->item->projectStatus=="Finished" ? 'selected' : '');?> >Finished</option>
			  <option value="OnHold" <?php echo ($this->item->projectStatus=="OnHold" ? 'selected' : '');?> >OnHold</option>
			  <option value="Terminated" <?php echo ($this->item->projectStatus=="Terminated" ? 'selected' : '');?> >Terminated</option>
			</select></td>
      </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipURS; ?>' colspan="4">User Requirement Specification </th>
     </tr>
     <tr>
		<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipURS; ?>'>Release Date </td>
        
		<td class="hsh_td_tables_input">
			<?php $currentDate=$this->item->msURSReleaseDate; echo JHTML::_('calendar', $currentDate, "msURSReleaseDate" , "msURSReleaseDate", '%Y-%m-%d', array( 'onchange'=>'someValueChanged(this)', 'required'=>'1')  );?>
		</td>
		
		<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipURSMilestone; ?>'>MS reached </td>
		<td class="hsh_td_tables_input"><input type="checkbox" name="msURSReleasedReached" value="1" <?php echo ($this->item->msURSReleasedReached==1 ? 'checked' : '');?> > </td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipFS; ?>' colspan="4">Functional Specification </th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipFS; ?>'>Release Date </td>

         <td class="hsh_td_tables_input">
 			<?php $currentDate=$this->item->msFSReleaseDate; echo JHTML::_('calendar', $currentDate, "msFSReleaseDate" , "msFSReleaseDate", '%Y-%m-%d', array( 'onchange'=>'someValueChanged(this)', 'required'=>'1')  );?>
		 </td> 
			 
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipFSMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msFSReleasedReached" value="1" <?php echo ($this->item->msFSReleasedReached==1 ? 'checked' : '');?> > </td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipExperimentalModel; ?>' colspan="4">Experimental Model Available</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExperimentalModel; ?>'>Release Date </td>
		 
         <td class="hsh_td_tables_input">
 			<?php $currentDate=$this->item->msExpModelReleaseDate; echo JHTML::_('calendar', $currentDate, "msExpModelReleaseDate" , "msExpModelReleaseDate", '%Y-%m-%d', array( 'onchange'=>'someValueChanged(this)', 'required'=>'1')  );?>
		 </td>

		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExperimentalModelMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msExpModelReleasedReached" value="1" <?php echo ($this->item->msExpModelReleasedReached==1 ? 'checked' : '');?> > </td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipPrototype; ?>' colspan="4">Prototypes Available</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipPrototype; ?>'>Release Date </td>

         <td class="hsh_td_tables_input">
 			<?php $currentDate=$this->item->msPrototypeReleaseDate; echo JHTML::_('calendar', $currentDate, "msPrototypeReleaseDate" , "msPrototypeReleaseDate", '%Y-%m-%d', array( 'onchange'=>'someValueChanged(this)', 'required'=>'1')  );?>
		 </td>

		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipPrototypeMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msPrototypeReleasedReached" value="1" <?php echo ($this->item->msPrototypeReleasedReached==1 ? 'checked' : '');?> > </td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipVolumeProduction; ?>' colspan="4">Volume Production Started</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipVolumeProduction; ?>'>Release Date </td>
		 
         <td class="hsh_td_tables_input">
 			<?php $currentDate=$this->item->msVolumeProductionDate; echo JHTML::_('calendar', $currentDate, "msVolumeProductionDate" , "msVolumeProductionDate", '%Y-%m-%d', array( 'onchange'=>'someValueChanged(this)', 'required'=>'1')  );?>
		 </td>
		 
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipVolumeProductionMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msVolumeProductionReached" value="1" <?php echo ($this->item->msVolumeProductionReached==1 ? 'checked' : '');?> > </td>
     </tr>



     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipExternalPartner; ?>' colspan="4">External Project Partner</th>
     </tr>

     <tr>
	 	<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExternalPartner; ?>'>Partner Known w/i the Group:</td>
	  	<td  class="hsh_td_tables_input" colspan="3">
	  		<select class="hsh_multiline_select_list hasTip" title='<?php echo $toolTipExternalPartner; ?>' name="collaboratorList" id="collaboratorList_id" multiple onchange="extPartnerSelectionChanged()">
	       	 	<?php foreach( array_combine($allExistingCollaborators, $collaboratorArePartners)  as $collaborator => $partner){ ?>
	       			<option value="<?php echo $collaborator; ?>"  <?php if ($partner == 1 ) echo ' selected="selected"';?> ><?php echo $collaborator; ?> </option>
	       		<?php } ?>
	  		</select>
		</td>
     </tr>

     <tr>
 		<td></td>
		<td colspan="3"> 
		  <input type="text" class="hsh_newCollaborator_text_input" id="newCollaborator_id" name="newCollaborator" value="" placeholder="Insert here a new Collaborator AND CLICK ADD->"/><input type="button" class="btn" value="Add New" style="vertical-align:4px; height:28px;" onclick="newCollaboratorFunction();">	
	    </td>
     </tr>
     <tr>
	 	<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExternalPartner; ?>'>Partner for new Project:</td>
	  	<td  class="hsh_td_tables_input" colspan="3">
		  <textarea name="externalPartner"   id="externalPartner" class="hsh_multiline_project_partner_text_input" placeholder="" readonly=1><?php echo $allExistingPartners; ?></textarea>
	 	</td>
     </tr>

     <tr>
        <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipReasonForChange; ?>'><b>Reason for a Change</b></td>
        <td class="hsh_td_tables_input" colspan="3"><textarea name="justificationForAChange" id="justificationForAChange" class="hsh_multiline_text_input" ></textarea></td>
     </tr>

     <tr>
         <td align="center" colspan="4"><input type="submit" value="Submit" style="height:30px; width:100px" />      <input type="reset" value="Reset" style="height:30px; width:100px" /> </td>
     </tr>
   </table>

	<!--store the "old" values-->
	<input type="hidden" name="hshrndreview_fk_project_id" value="<?php echo $this->item->hshrndreview_fk_project_id; ?>"/>	
	<input type="hidden" name="objectiveOld" id="objectiveOld" value="<?php echo $this->item->objective; ?>"/>
	<input type="hidden" name="responsibleOld" id="responsibleOld" value="<?php echo $this->item->responsible; ?>"/>
	<input type="hidden" name="estimatedProjectCostsOld" id="estimatedProjectCostsOld" value="<?php echo $this->item->estimatedProjectCosts; ?>"/>
	<input type="hidden" name="projectStatusOld" id="projectStatusOld" value="<?php echo $this->item->projectStatus; ?>"/>
	<input type="hidden" name="msURSReleaseDateOld" id="msURSReleaseDateOld" value="<?php echo $this->item->msURSReleaseDate; ?>"/>
	<input type="hidden" name="msFSReleaseDateOld" id="msFSReleaseDateOld"  value="<?php echo $this->item->msFSReleaseDate; ?>"/>
	<input type="hidden" name="msExpModelReleaseDateOld" id="msExpModelReleaseDateOld" value="<?php echo $this->item->msExpModelReleaseDate; ?>"/>
	<input type="hidden" name="msPrototypeReleaseDateOld" id="msPrototypeReleaseDateOld" value="<?php echo $this->item->msPrototypeReleaseDate; ?>"/>
	<input type="hidden" name="msVolumeProductionDateOld" id="msVolumeProductionDateOld" value="<?php echo $this->item->msVolumeProductionDate; ?>"/>
	<input type="hidden" name="externalPartnerOld" id="externalPartnerOld" value="<?php echo $allExistingPartners; ?>"/>
	<input type="hidden" name="projectDurationOld" id="projectDurationOld"  value="<?php echo $this->item->projectDuration; ?>"/>	
	<input type="hidden" name="projectStartDateOld" id="projectStartDateOld" value="<?php echo $_GET['projectStartDate']; ?>"/>


	<?php endif; ?>
	

</form>
<script>

function someValueChanged(obj) {
	//change the input field background if field has changed
	
	var x = obj.id;
	var old = document.getElementById(x + 'Old');
	
	if(obj.value != old.value){
		obj.style.backgroundColor = "lightyellow";	
	}
	else{
		obj.style.backgroundColor = "white";	
	}
	
	//make the justificationForAChange field required if necessary
	var justificationElem = document.querySelector("#justificationForAChange");
	
	if( document.getElementById('objectiveOld').value != document.getElementById('objective').value ||
		document.getElementById('responsibleOld').value != document.getElementById('responsible').value ||
		document.getElementById('estimatedProjectCostsOld').value != document.getElementById('estimatedProjectCosts').value ||
	 	document.getElementById('projectStatusOld').value != document.getElementById('projectStatus').value ||
		document.getElementById('msURSReleaseDateOld').value != document.getElementById('msURSReleaseDate').value ||
		document.getElementById('msFSReleaseDateOld').value != document.getElementById('msFSReleaseDate').value ||
		document.getElementById('msExpModelReleaseDateOld').value != document.getElementById('msExpModelReleaseDate').value ||
		document.getElementById('msPrototypeReleaseDateOld').value != document.getElementById('msPrototypeReleaseDate').value ||
		document.getElementById('msVolumeProductionDateOld').value != document.getElementById('msVolumeProductionDate').value ||
		document.getElementById('externalPartnerOld').value != document.getElementById('externalPartner').value ){
		
		justificationElem.style.backgroundColor = "lightyellow";
		justificationElem.setAttribute("required", "required");		
		justificationElem.removeAttribute("disabled");
	}
	else{
		justificationElem.style.backgroundColor = "white";
		justificationElem.removeAttribute("required");		
		justificationElem.setAttribute("disabled", "disabled");
		justificationElem.value ="";
	}
}

function newCollaboratorFunction(){
	var newCollaborator = document.querySelector("#newCollaborator_id");
	if(newCollaborator.value.length > 0){
	
		//alert('newCollaborator:' + newCollaborator.value);

		var collaboratorOpts = document.getElementById('collaboratorList_id').options;
		var len = collaboratorOpts.length;
		var alreadyIn=false;
		
		//this is just to avoid duplicates in the list
		for(i=0;i<len;i++){
			if(collaboratorOpts[i].value == newCollaborator.value){
				alreadyIn=true;
			}
		}
		if(!alreadyIn){
		    var x = document.createElement("OPTION");
		    x.setAttribute("value", newCollaborator.value);
		    var t = document.createTextNode(newCollaborator.value);
		    x.appendChild(t);
			x.selected = true;
		    document.getElementById('collaboratorList_id').appendChild(x);
			extPartnerSelectionChanged();
		}
		document.querySelector("#newCollaborator_id").value = "";
	}	
}

function extPartnerSelectionChanged(){

	var collaboratorOpts = document.getElementById('collaboratorList_id').options;
	var len = collaboratorOpts.length;
	var collaborators = "";
	
	var isFirst=true;
	for(i=0;i<len;i++){
		if(collaboratorOpts[i].selected==true){
			if(!isFirst){
				collaborators += "\n";
			}   
			collaborators += collaboratorOpts[i].value;
			isFirst=false;
		}
	}
	var currentCollaborators = document.getElementById('externalPartner');
	currentCollaborators.value = collaborators;
	someValueChanged(document.getElementById('externalPartner'));
}
window.addEvent('domready', function(){ 
       var JTooltips = new Tips($$('.hasTip'), 
       { maxTitleChars: 50, fixed: false}); 
});
</script>
