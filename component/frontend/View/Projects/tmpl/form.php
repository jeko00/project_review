<?php
//-------------------------------------------------------------------------------------------------------------------------------------------
//
//form.php: is the project creation form. Used to gather data for a intial project submission done by the user 04/2106/jk
//
//-------------------------------------------------------------------------------------------------------------------------------------------

$this->getContainer()->template->addCSS('media://com_hshrndreview/css/frontend.css', $this->getContainer()->mediaVersion);
JHtml::_('jquery.framework');
// $doc = JFactory::getDocument();
// $tooltip = 'jQuery(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });';
// $doc->addScriptDeclaration($tooltip);


JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');

$db = \JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('name');
$query->from($db->quoteName('#__hshrndreview_collaborators'));
$db->setQuery($query);
$allExistingCollaborators = $db->loadObjectList();

$toolTipProjectName="Project Name::Name of the project. It CANNOT be changed after the project has been created!";

$toolTipProjectStartDate="Project Start Date::The starting date CANNOT be changed after the project has been created. You have to change the project status instead (e.g. OnHold).";

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
?>

<form action="index.php" method="post" accept-charset="utf-8">
	
	<div id="header">
		<h1>Submit New Development Project</h1>
	</div>
	
	<input type="hidden" name="option" value="com_hshrndreview"/>
   <input type="hidden" name="view" value="Projects"/>
   <input type="hidden" name="task" value="addProjectAndStatus"/>   
   	  
   <table style="width:70%;">
	<col width="25%">
	<col width="30%">
	<col width="10%">
	<col width="5%">
      
	  <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectName; ?>'><b>Project Name</b></td>
         <td class="hsh_td_tables_input" colspan="3"><input type="text" class="hsh_text_input" id="name_id" name="name" value="" placeholder="Name of the project" autofocus="autofocus" required/></td>
	  </tr>
	  
      <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectStartDate; ?>'><b>Start Date</b></td>
         <td class="hsh_td_tables_input" colspan="3">
		    <?php echo JHTML::_('calendar', "", "startDate" , "startDate_id", '%Y-%m-%d', array('class'=>'hsh_td_tables_input', 'required'=>'1')  );?>
		 </td>
      </tr>
      
	  <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectObjective; ?>'><b>Project Objective</b></td>
         <td class="hsh_td_tables_input" colspan="3"><textarea name="objective" class="hsh_multiline_text_input" value="" placeholder="Provide a little but meaningful description of the new project..." required></textarea></td>
	  </tr>
      
	  <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectResponsible; ?>'><b>Project Responsible</b></td>
         <td colspan="3" class="hsh_td_tables_input"><input type="text" class="hsh_text_input" name="responsible" value="<?php $n = \JFactory::getUser()->get('name'); echo $n; ?>" required/></td>
      </tr>

      <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectCosts; ?>'><b>Estimated Project Costs</b></td>
         <td class="hsh_td_tables_input" colspan="3"><input type="number" class="hsh_text_input" name="estimatedProjectCosts" value="" required/></td>
      </tr>

      <tr>
         <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipProjectStatus; ?>'><b>Project Status</b></td>
         <td class="hsh_td_tables_input" colspan="3">
			<select required name="projectStatus">
			  <option value="">-Select-</option>
			  <option value="Active">Active</option>
			  <option value="Finished">Finished</option>
			  <option value="OnHold">OnHold</option>
			  <option value="Terminated">Terminated</option>
			</select>
		</td>
      </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipURS; ?>' colspan="4">User Requirement Specification </th>
     </tr>
	 
     <tr>
		<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipURS; ?>'>Release Date </td>
        <td class="hsh_td_tables_input">
	    	<?php echo JHTML::_('calendar', "", "msURSReleaseDate" , "msURSReleaseDate", '%Y-%m-%d', array('class'=>'hsh_td_tables_input', 'required'=>'1')  );?>
		</td>
		<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipURSMilestone; ?>'>MS reached </td>
		<td class="hsh_td_tables_input"><input type="checkbox" name="msURSReleasedReached" value="1"</td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipFS; ?>' colspan="4">Functional Specification </th>
     </tr>
	 
     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipFS; ?>'>Release Date </td>
         <td class="hsh_td_tables_input">
    		 <?php echo JHTML::_('calendar', "", "msFSReleaseDate" , "msFSReleaseDate", '%Y-%m-%d', array('class'=>'hsh_td_tables_input', 'required'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipFSMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msFSReleasedReached" value="1"</td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipExperimentalModel; ?>' colspan="4">Experimental Model Available</th>
     </tr>

     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExperimentalModel; ?>'>Release Date </td>
         <td class="hsh_td_tables_input">
    		 <?php echo JHTML::_('calendar', "", "msExpModelReleaseDate" , "msExpModelReleaseDate", '%Y-%m-%d', array('class'=>'hsh_td_tables_input', 'required'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExperimentalModelMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msExpModelReleasedReached" value="1"</td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipPrototype; ?>' colspan="4">Prototypes Available</th>
     </tr>

     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipPrototype; ?>'>Release Date </td>
         <td class="hsh_td_tables_input">
	 		<?php echo JHTML::_('calendar', "", "msPrototypeReleaseDate" , "msPrototypeReleaseDate", '%Y-%m-%d', array('class'=>'hsh_td_tables_input', 'required'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipPrototypeMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msPrototypeReleasedReached" value="1"</td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipVolumeProduction; ?>' colspan="4">Volume Production Started</th>
     </tr>
     <tr>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipVolumeProduction; ?>'>Release Date </td>
         <td class="hsh_td_tables_input">
 			 <?php echo JHTML::_('calendar', "", "msVolumeProductionDate" , "msVolumeProductionDate", '%Y-%m-%d', array('class'=>'hsh_td_tables_input', 'required'=>'1')  );?>
		 </td>
		 <td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipVolumeProductionMilestone; ?>'>MS reached </td>
		 <td class="hsh_td_tables_input"><input type="checkbox" name="msVolumeProductionReached" value="1"</td>
     </tr>

     <tr>
         <th align="left" class="span hasTip" title='<?php echo $toolTipExternalPartner; ?>' colspan="4">External Project Partner</th>
     </tr>

     <tr>
	 	<td class="hsh_td_tables_input hasTip" title='<?php echo $toolTipExternalPartner; ?>'>Partner Known w/i the Group:</td>
	  	<td  class="hsh_td_tables_input" colspan="3">
	  		<select class="hsh_multiline_select_list" name="collaboratorList" id="collaboratorList_id" multiple onchange="extPartnerSelectionChanged()">
	       	 	<?php foreach($allExistingCollaborators as $collaborator){ ?>
	       			<option value="<?php echo $collaborator->name; ?>"><?php echo $collaborator->name; ?></option>
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
		  <textarea name="externalPartner"   id="externalPartner" class="hsh_multiline_project_partner_text_input" value="" placeholder="" readonly=1></textarea>
	 	</td>
     </tr>

	 	
      <tr>
	 <td align="center" colspan="4"><input type="submit" value="Submit" style="height:30px; width:100px" />      <input type="reset" value="Reset" style="height:30px; width:100px" /> </td>
     </tr>
   </table>


</form>
<script>
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
				collaborators += '\r';
			}   
			collaborators += collaboratorOpts[i].value;
			isFirst=false;
		}
	}
//	alert(collaborators);
	var currentCollaborators = document.getElementById('externalPartner');
	currentCollaborators.value = collaborators;
}
window.addEvent('domready', function(){ 
       var JTooltips = new Tips($$('.hasTip'), 
       { maxTitleChars: 50, fixed: false}); 
    });
</script>

