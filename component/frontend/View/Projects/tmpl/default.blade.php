{{--
@package	hshrndreview
@copyright	2016 jk
@license	GNU GPL version 3 or later
--}}
<?php
/** @var \FOF30\View\DataView\Html $this */


//----------------------------------------------------------------------------------------------------------------------------------------
//
//default.blade.php: is the project oveview screen showing ALL projects from ALL companies it considers the rights of the user 04/2106/jk   
//
//----------------------------------------------------------------------------------------------------------------------------------------


//======================================
//$uri = \JUri::getInstance($uri);
//$this->requestURL = $uri->toString();
//======================================

JHtml::_('jquery.framework');
JHtml::_('behavior.framework', true);
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.tooltip');

$routeToSelf = \JRoute::_('index.php?option=com_hshrndreview&view=Projects');
$uniqueId = md5(microtime() . rand(0, 1000000));

$this->getContainer()->template->addCSS('media://com_hshrndreview/css/frontend.css');

$user = \JFactory::getUser(); 	//current user

$grp = \JAccess::getGroupsByUser($this->container->platform->getUser()->id, false); 
//this is a very hands on approach to get the hsh group (some how the 'false' in getGroupsByUser does not work) 03/2016/jk
$ownergroup = max($grp);

$db = \JFactory::getDbo();
//this is just to know whether we are in the user is in the reviewer group or not
$uReviewerGroup='hsgrp_HSH';
$uHSHEditorGroup='hsgrp_HSHEditor';
$query = $db->getQuery(true);
$query->select('title');
$query->from($db->quoteName('#__usergroups'));
$query->where($db->quoteName('id')." = ". $ownergroup);
$db->setQuery($query)->loadObject();
$uGroupTitle = $db->loadResult();

$accessViewLevelsCurrentUser = \JFactory::getUser()->getAuthorisedViewLevels();
$accessViewLevelsCurrentUserAsString = implode(',',$accessViewLevelsCurrentUser);


//this is for the sorting 
$currentFilter=($this->getModel()->getState('filter_order'));
$currentOrder=($this->getModel()->getState('filter_order_Dir') == "asc" ?  "<span class=\"icon icon-arrow-up\"></span>" : "<span class=\"icon icon-arrow-down\"></span>");
$oppositeOrder=($this->getModel()->getState('filter_order_Dir') == "asc" ? "desc" : "asc");


//retrieve states to show currently valid search strings 
//for project name
$currentSearchStringProjectName=$this->getModel()->getState('name');
//for project status
$currentProjectStatusSearchSel=$this->getModel()->getState('currentProjectStatusSearchSel');
//for companies
$currentCompanySearchSel=$this->getModel()->getState('currentCompanySearchSel');
//turn it into an array
$currentCompanySearchSel=str_replace("[","",$currentCompanySearchSel);
$currentCompanySearchSel=str_replace("]","",$currentCompanySearchSel);
$currentCompanySearchSel=str_replace(" ","",$currentCompanySearchSel);
$currentCompanySearchSelAsArray=explode(",",$currentCompanySearchSel);


//prepare the lists for the search by company functionality 
//search for all user groups that represent companies
$queryCompanies = $db->getQuery(true);
$queryCompanies->select(array('id','title'));
$queryCompanies->from($db->quoteName('#__usergroups'));
$queryCompanies->where($db->quoteName('title') . ' LIKE '. '\'hsgrp_%\'');
$db->setQuery($queryCompanies);
$allCompanies = $db->loadObjectList();

$queryACLRules = $db->getQuery(true);
$queryACLRules->select('rules');
$queryACLRules->from($db->quoteName('#__viewlevels'));
$queryACLRules->where($db->quoteName('id') . ' IN '. '('. $accessViewLevelsCurrentUserAsString . ')' );
$db->setQuery($queryACLRules);
$allACLRules = $db->loadObjectList();


$allReadableCompanyIdx = array();
$allReadableCompanyNames = array();
$companyIsSelected = array();
foreach ($allCompanies as $company) {

	if($company->title != "hsgrp_HSH" && $company->title != "hsgrp_HSHEditor"   && $company->title != "hsgrp_HS_Foundation"){

		foreach ($allACLRules as $rule) {
			$r=str_replace("[","",$rule->rules);
			$r=str_replace("]","",$r);
			$rulesAsArray=explode(",",$r);
			if(in_array($company->id, $rulesAsArray)){
		
				//form real company name
				$c_name=$r=str_replace("hsgrp_","",$company->title);
				if(!in_array($c_name,$allReadableCompanyNames)){
					array_push($allReadableCompanyIdx, $company->id);	
					array_push($allReadableCompanyNames, $c_name);	
					$companyIdString = "'" . $company->id . "'";
					array_push($companyIsSelected, (in_array($companyIdString, $currentCompanySearchSelAsArray) ? 1 : 0));
					continue;
				}
			}
		}
	}	
}







//tons of tooltips
$toolTipSearchProjectName="Search for projects by name.";
$toolTipSearchProjectStatus="Search for projects by status.";
$toolTipSearchCompany="Search for projects by company.";

$toolTipNewProject="Click here to submit a new development project";

$toolTipNameHeader="Name of the development project. Sort by project name by clicking here \"asc\"/\"desc\".";
$toolTipOwnerHeader="Owner of the development project. Sort by project owner by clicking here \"asc\"/\"desc\".";
$toolTipCostsHeader="Total costs of the project based on the last status update. Sort by project costs by clicking here \"asc\"/\"desc\".";
$toolTipStatusHeader="Project Status based on the last status update. Sort by project status by clicking here \"asc\"/\"desc\".";
$toolTipCurrentObjectiveHeader="Objective of the project based on the last status update.";
$toolTipStartDateHeader="Starting date of the development project.";
$toolTipFinishDateHeader="Finish date of the project based on the last status update. (In this simplified process model the development project ends when the volume production starts.)";
$toolTipDurationHeader="Duration of the project based on the last status update. (Time duration between Start Date and Volume Production Start.)";
$toolTipMilestonesHeader="Estimated dates for the milestones within of the project based on the last status update. (Click on the respective button to see the details!)";
$toolTipAssessmentHeader="Assessment of the project based on the last status update.";

$toolTipProjectDetails="Click here to show a detailed history of the project development.";
$toolTipNewStatusUpdate="Click here to submit a new status update for this project.";
$toolTipNewStatusUpdateInvalid="Submitting project status update is only possible for projects with status \"Active\" or \"OnHold\".";
$toolTipMilestoneDetails="Click here to fold/unfold details about the milestones in this project.";

$toolTipVolumeProduction="Volume Production Started::Planned starting date for the volume production (\"When can it sold to customers?\").";
$toolTipPrototype="Prototypes Available::Planned release date for the prototypes (\"When can it be exhibit at trade fairs?\").";
$toolTipExpModel="Experimental Model Available::Planned release date for the experimental model.";
$toolTipFSRel="Functional Specification::Planned release date for the functional specification. Release date can be altered during every status update.";
$toolTipURSRel="User Requirement Specification::Planned release date for the user requirement specification.";

$toolTipColorLegend="Color coding is always with respect to the previous status update. This means if e.g. the costs have increased between status update \"1\" and \"2\" color of the costs label will change in status update \"2\" only (it will turn orange). From status update \"3\" on (if the costs dont change again) the color turns back to gray.";
?>

@section('page-script')
<script type="text/javascript">
function myFunction(elem) {
		
	var row = elem.parentNode.parentNode.rowIndex;	
	
	var tableElm = document.getElementById("projectTable");	
	var tableChilds = tableElm.getElementsByTagName("tr");

	var isShort=true;
	for (i = row+1; i < row+6; ++i) {
	 	var tableCells = tableChilds[i].getElementsByClassName("toggable");
		
	 	for (j = 0; j < tableCells.length; ++j) {
			if(tableCells[j].style.display != "none"){
		 	 	tableCells[j].style.display = "none";
			}
			else{
		 	 	tableCells[j].style.display = "";				
				isShort=false;
			}

		}
	}
	
	var untoggableCells = tableChilds[row].getElementsByClassName("untoggable");
}

function searchCriteriaProjectStatusChanged(){

	var projectStatusOpts = document.getElementById('projectStatusSearchSel').options;
	var len = projectStatusOpts.length;
	var options = "";
	
	var isFirst=true;
	for(i=0;i<len;i++){
		if(projectStatusOpts[i].selected==true){
			if(!isFirst){
				options += ", " ;
			}   
			options += "'" + projectStatusOpts[i].value + "'";
			isFirst=false;
		}
	}

	var p = document.getElementById('projectStatusSearchSelHidden');
	p.value = options;
}
         
function searchCriteriaCompanyChanged(){

	var companyOpts = document.getElementById('companySearchSel').options;
	var len = companyOpts.length;
	var options = "";
	
	var isFirst=true;
	for(i=0;i<len;i++){
		if(companyOpts[i].selected==true){
			if(!isFirst){
				options += ", " ;
			}   
			options += "'" + companyOpts[i].value + "'";
			isFirst=false;
		}
	}

	var p = document.getElementById('companySearchSelHidden');
	p.value = options;
}



window.addEvent('domready', function(){ 
       var JTooltips = new Tips($$('.hasTip'), 
       { maxTitleChars: 50, fixed: false}); 
    });
</script>
@show




@section('header')
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="<?php echo $routeToSelf ?>">
				@lang('COM_HSHRNDREVIEW_PROJECTS_FRONTEND_HEADING')
			</a>
			<form class="navbar-form pull-left" name="comHSHRNDReviewHeaderInlineForm{{$uniqueId}}" id="comHSHRNDReviewHeaderInlineForm{{$uniqueId}}"  action="index.php" method="post" >
				
				<input type="hidden" name="option" value="com_hshrndreview"/>
 			    <input type="hidden" name="view" value="Projects"/>

			</form>
			
			@if ($uGroupTitle != $uReviewerGroup && $uGroupTitle != $uHSHEditorGroup)
			<div class="pull-right hasTip" title='<?php echo $toolTipNewProject; ?>'>
				<a href="@route('index.php?option=com_hshrndreview&view=Projects&task=add')" class="btn btn-success">
					<span class="icon icon-new"></span>
					@lang('COM_HSHRNDREVIEW_NEW_PROJECT_') @ownerTitle($ownergroup)
				</a>
			</div>
			@endif
		</div>
	</div>
@show



@section('items')
	<form action="index.php" id="adminForm" name="adminForm" method="post">
		<div id="j-main-container" class="span12 j-toggle-main">
			<div id="filter-bar" class="btn-toolbar">				
				<!-- search for projects by name-->
				<div class="btn-wrapper input-append">
					<input type="text" name="name" id="name" style="width:400px;" class="hasTip"  title='<?php echo $toolTipSearchProjectName; ?>' value='<?php echo $currentSearchStringProjectName; ?>'   placeholder="Search by Project Name..." onchange="document.adminForm.submit();">
					<button type="submit" class="btn hasTooltip" title="" data-original-title="Search"><span class="icon-search"></span>
					</button>
					<button class="btn btn-primary hasTooltip" onclick="document.adminForm.name.value=''; document.adminForm.projectStatusSel.select=''; document.adminForm.projectStatusSearchSelHidden.select=''; this.form.submit();" title="Reset" data-original-title="Reset">
						<i class="icon-remove"></i>
					</button>
				</div>
				
				<!-- search for projects by status-->
				<div class="btn-wrapper hasTip" title='<?php echo $toolTipSearchProjectStatus; ?>'>        
					<select class="chosen" name="projectStatusSearchSel" id="projectStatusSearchSel" data-placeholder="Search by Project Status..." multiple="true" style="width:498px;" onchange="searchCriteriaProjectStatusChanged(), this.form.submit();">
						<option value="Active" <?php echo (strpos($currentProjectStatusSearchSel,"Active")==false ? '' : 'selected');?> >Active</option>
						<option value="Finished" <?php echo (strpos($currentProjectStatusSearchSel,"Finished")==false ? '' : 'selected');?> >Finished</option>
						<option value="OnHold" <?php echo (strpos($currentProjectStatusSearchSel,"OnHold")==false ? '' : 'selected');?> >OnHold</option>
						<option value="Terminated" <?php echo (strpos($currentProjectStatusSearchSel,"Terminated")==false ? '' : 'selected');?> >Terminated</option>
					</select>                                          
				</div>                         
				
				<div class="btn-wrapper">    
					<p>
				</div>                         
				
				<!-- search for projects by company-->
				<div class="btn-wrapper hasTip" title='<?php echo $toolTipSearchCompany; ?>'>        
					<select class="chosen"  name="companySearchSel" id="companySearchSel" data-placeholder="Search by Company..." multiple="true" style="width:498px;" onchange="searchCriteriaCompanyChanged(), this.form.submit();">
			       	 	<?php $max = sizeof($allReadableCompanyIdx); for( $i = 0; $i<$max; $i++){ ?>
			       			<option value="<?php echo $allReadableCompanyIdx[$i]; ?>"  <?php if ($companyIsSelected[$i] == 1 ) echo ' selected="selected"';?> ><?php echo $allReadableCompanyNames[$i]; ?> </option>
			       		<?php } ?>
					</select>                                          
				</div>                         
			</div>
		</div>



	<table class="hsh_rnd_projects_table" id="projectTable">
	<thead>
		<tr class="hsh_rnd_projects_table_tr">
			<th class="hsh_rnd_projects_table_th  hasTip"  title='<?php echo $toolTipNameHeader; ?>' width="10%">
				<span style="white-space:nowrap;"/><a href="@route('index.php?option=com_hshrndreview&view=Projects&filter_order=name&filter_order_Dir=' . $oppositeOrder )"><?php if($this->getModel()->getState('filter_order', '', 'string') == 'name'){ echo $currentOrder ; } ?>Name</a></span>
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipOwnerHeader; ?>' width="7%">
				<span style="white-space:nowrap;"/><a href="@route('index.php?option=com_hshrndreview&view=Projects&filter_order=owner&filter_order_Dir=' . $oppositeOrder )"><?php if($this->getModel()->getState('filter_order', '', 'string') == 'owner'){echo $currentOrder;} ?>Owner</a></span>
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipCostsHeader; ?>' width="7%">
				<span style="white-space:nowrap;"/><a href="@route('index.php?option=com_hshrndreview&view=Projects&filter_order=estimatedProjectCosts&filter_order_Dir=' . $oppositeOrder )"><?php if($this->getModel()->getState('filter_order', '', 'string') == 'estimatedProjectCosts'){ echo $currentOrder ; } ?>Est.Costs</a></span>
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipStatusHeader; ?>' width="7%">
				<span style="white-space:nowrap;"/><a href="@route('index.php?option=com_hshrndreview&view=Projects&filter_order=projectStatus&filter_order_Dir=' . $oppositeOrder )"><?php if($this->getModel()->getState('filter_order', '', 'string') == 'projectStatus'){ echo $currentOrder ; } ?>Status</a></span>
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipCurrentObjectiveHeader; ?>' width="20%">
				Current Objective
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipStartDateHeader; ?>' width="7%">
				Start Date
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipFinishDateHeader; ?>' width="10%">
				Finish Date
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipDurationHeader; ?>' width="7%">
				Duration
			</th>
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipMilestonesHeader; ?>' width="10%">
				Milestones
			</th>
			@if (($uGroupTitle == $uReviewerGroup) || ($uGroupTitle == $uHSHEditorGroup))
			<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipAssessmentHeader; ?>' width="20%">
				Assessment
			</th>			
			@endif
		</tr>
	</thead>
	<tbody> 
	@foreach ($this->items as $item)
	
		@if (   (in_array($item->owner, $user->getAuthorisedGroups()) ) || (in_array($item->ownerAL, $accessViewLevelsCurrentUser)) )
	
		<tr class="hsh_rnd_projects_table_tr">
			<td class="hsh_rnd_projects_table_td4 hasTip" title='<?php echo $toolTipProjectDetails; ?>' rowspan="6">
				<a href="@route('index.php?option=com_hshrndreview&view=Projects&task=read&id=' . $item->hshrndreview_project_id)">
				{{{$item->name}}}
				</a>
				<br>
				{{-- only owner should be allowed to submit new status updates --}}
				@if (in_array($item->owner, $user->getAuthorisedGroups()))
					@if ($item->projectStatus == "Active" || $item->projectStatus == "OnHold")
						<div class="hasTip" title='<?php echo $toolTipNewStatusUpdate; ?>'>
							<a class="btn btn-small" href="@route('index.php?option=com_hshrndreview&view=Statuses&task=edit&hshrndreview_status_id=' . $item->hshrndreview_fk_status_id . '&projectName=' . $item->name . '&projectStartDate=' . $item->startDate . '&isAssessment=0')" >
								<span class="icon icon-edit"></span>
							</a>
						</div>
					@else
						<div class="hasTip" title='<?php echo $toolTipNewStatusUpdateInvalid; ?>'>
							<a class="btn btn-small" disabled>
								<span class="icon icon-edit"></span>
							</a>
						</div>
					@endif
							
				
				@endif
				
			</td>

			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				@ownerTitle($item->owner) @colorStrBlue('('.$item->responsible.')' , $item->responsibleChanged) 
			</td>
			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				@colorLabelGreenOrange($item->estimatedProjectCosts , $item->estimatedProjectCostsChanged )
			</td>
			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				@colorLabelBlue($item->projectStatus , $item->projectStatusChanged )
			</td>  
			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				<div class="do_scroll">
				@colorStrBlue($item->objective , $item->objectiveChanged ) 
				</div>
			</td>  
			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				@regdate($item->startDate)
			</td>

			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				@colorDateGreenOrangeRedWoCheckBox($item->msVolumeProductionDate , $item->msVolumeProductionDateChanged, $item->msVolumeProductionReached) 
			</td>  
			
			<td class="hsh_rnd_projects_table_td2 untoggable">
				@dateIntervalWColorLabelGreenOrange($item->projectDuration , $item->projectDurationChanged )
			</td> 

			<td class="hsh_rnd_projects_table_td2 untoggable hasTip"  title='<?php echo $toolTipMilestoneDetails; ?>'>
				<button type="button" onclick="myFunction(this)" >Milestones</button>
			</td>
			
			@if (($uGroupTitle == $uReviewerGroup) || ($uGroupTitle == $uHSHEditorGroup))
			<td class="hsh_rnd_projects_table_td4" rowspan="6">
				<div class="do_scroll">
				{{{$item->projectAssessment}}}
				</div>
			</td>
			@endif
			
		</tr>	

		<tr class="hsh_rnd_projects_table_tr">
			<td class="toggable hasTip" title='<?php echo $toolTipURSRel; ?>' style="display: none;">URS Rel.:</td><td  class="toggable" style="display: none;">@colorDateGreenOrangeRed($item->msURSReleaseDate , $item->msURSReleaseDateChanged , $item->msURSReleasedReached)</td>  
		</tr>	
		
		<tr class="hsh_rnd_projects_table_tr">
			<td class="toggable hasTip" title='<?php echo $toolTipFSRel; ?>' style="display: none;">FS Rel.:</td><td class="toggable" style="display: none;">@colorDateGreenOrangeRed($item->msFSReleaseDate , $item->msFSReleaseDateChanged , $item->msFSReleasedReached)</td>
		</tr>
		
		<tr class="hsh_rnd_projects_table_tr">
			<td class="toggable hasTip" title='<?php echo $toolTipExpModel; ?>' style="display: none;">ExpModel Rel.:</td><td class="toggable" style="display: none;">@colorDateGreenOrangeRed($item->msExpModelReleaseDate , $item->msExpModelReleaseDateChanged , $item->msExpModelReleasedReached)</td>
		</tr>	

		<tr class="hsh_rnd_projects_table_tr">
			<td class="toggable hasTip" title='<?php echo $toolTipPrototype; ?>' style="display: none;">Proto. Rel.:</td><td class="toggable" style="display: none;">@colorDateGreenOrangeRed($item->msPrototypeReleaseDate , $item->msPrototypeReleaseDateChanged , $item->msPrototypeReleasedReached)</td>  
		</tr>	

		<tr class="hsh_rnd_projects_table_tr">
			<td  class="hsh_rnd_projects_table_td3 toggable hasTip" title='<?php echo $toolTipVolumeProduction; ?>' style="display: none;">Vol.Prod Start:</td><td class="hsh_rnd_projects_table_td3 toggable" style="display: none;">@colorDateGreenOrangeRed($item->msVolumeProductionDate , $item->msVolumeProductionDateChanged , $item->msVolumeProductionReached)</td> 
		</tr>


		@endif
	@endforeach
	</tbody>
	</table>


		<input type="hidden" name="option" value="com_hshrndreview">
		<input type="hidden" name="view" value="Projects">
		<input type="hidden" name="task" value="browse">
		<input type="hidden" name="projectStatusSearchSelHidden" id="projectStatusSearchSelHidden" value="<?php echo $currentProjectStatusSearchSel; ?>"/>
		<input type="hidden" name="companySearchSelHidden" id="companySearchSelHidden" value="<?php echo $currentCompanySearchSel; ?>"/>


	{{$this->getPagination()->getPaginationLinks()}}


	</form>



@show


	<hr>
	<h3 class="hasTip" title='<?php echo $toolTipColorLegend; ?>'>Color Legend</h3>
	<table style="border:1px solid grey">
		<col width="60px">
		<col width="450px">
		<tr>
			<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelGreenOrange("Gray Color" , 0 )</th>
			<td style="border:1px solid grey;">{{{"\"No Change\" w respect to the last submission"}}}</td>
		</tr>
		<tr>
			<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelBlue("Blue Color" , 1)</th>
			<td style="border:1px solid grey;">{{{"\"Non-judgmental\" change of a field..."}}}</td>
		</tr>
		<tr>
			<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelGreenOrange("Green Color" , -1 )</th>
			<td style="border:1px solid grey;">{{{"\"Positive Change\" (e.g. Costs decreased or milestone pushed forward...)"}}}</td>
		</tr>
		<tr>
			<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelGreenOrange("Orange Color" , 1 )</th>
			<td style="border:1px solid grey;">{{{"\"Negative Change\" (e.g. Costs increased or milestone pushed backward...)"}}}</td>
		</tr>
		<tr>
			<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelRed("Red Color" , 1 )</th>
			<td style="border:1px solid grey;">{{{"\"Failed to meet the deadline...\""}}}</td>
		</tr>
	</table>
	
	
	
