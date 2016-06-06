{{--
@package	hshrndreview 
@copyright	2016 jk
@license	GNU GPL version 3 or later
--}}
<?php
//-------------------------------------------------------------------------------------------------------------------------------------------
//
//item.blade.php: is the project detail oveview screen showing project details and in particular a table with all status updates  04/2106/jk
//
//-------------------------------------------------------------------------------------------------------------------------------------------

JHTML::_('behavior.tooltip');

$routeToSelf = \JRoute::_('index.php?option=com_hshrndreview&view=Projects');
$uniqueId = md5(microtime() . rand(0, 1000000));

$this->getContainer()->template->addCSS('media://com_hshrndreview/css/frontend.css');

$user = \JFactory::getUser(); 	//current user
$accessViewLevelsCurrentUser = \JFactory::getUser()->getAuthorisedViewLevels();

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


$toolTipNewStatusUpdate="Click here to submit a new status update for this project.";
$toolTipNewStatusUpdateInvalid="Submitting project status update is only possible for projects with status \"Active\" or \"OnHold\".";


$toolTipNameHeader="Name of the development project.";
$toolTipOwnerHeader="Owner of the development project.";
$toolTipProjectTypeHeader="Type of the Project.";
$toolTipStartDateHeader="Starting date of the development project.";

$toolTipSubmDateHeader="Submision date from each status update.";
$toolTipFinishDateHeader="Finish date of the project from each status update. (In this simplified process model the development project ends when the volume production starts.)";
$toolTipDurationHeader="Duration of the project from each status update. (Time duration between Start Date and Volume Production Start.)";
$toolTipCostsHeader="Total costs of the project from each status update.";
$toolTipStatusHeader="Project Status from each status update.";
$toolTipObjectiveHeader="Objective of the project from each status update.";
$toolTipExtPartnerHeader="External project partner from each status update.";
$toolTipResponsibleHeader="Responsible project leader from each status update.";
$toolTipMilestonesHeader="Estimated dates for the milestones within the project from each status update.";
$toolTipReasonFChangeHeader="Justification for changes relative to the previous status update.";
$toolTipAssessmentHeader="Assessment of the project relative to each status update.";
	
$toolTipVolumeProduction="Volume Production Started::Planned starting date for the volume production (\"When can it sold to customers?\").";
$toolTipPrototype="Prototypes Available::Planned release date for the prototypes (\"When can it be exhibit at trade fairs?\").";
$toolTipExpModel="Experimental Model Available::Planned release date for the experimental model.";
$toolTipFSRel="Functional Specification::Planned release date for the functional specification. Release date can be altered during every status update.";
$toolTipURSRel="User Requirement Specification::Planned release date for the user requirement specification.";

$toolTipColorLegend="Color coding is always with respect to the previous status update. This means if e.g. the costs have increased between status update \"1\" and \"2\" color of the costs label will change in status update \"2\" only (it will turn orange). From status update \"3\" on (if the costs dont change again) the color turns back to gray.";



//new status updates can only be submitted for projects with a status "Active" or "OnHold"
$lastestStatusUpdateId=0;
$projectIsClosed=false;
foreach ($this->item->allStatuses as $status){
	if($status->hshrndreview_status_id >= $lastestStatusUpdateId){
		$lastestStatusUpdateId=$status->hshrndreview_status_id;
		if($status->projectStatus == "Finished"||$status->projectStatus == "Terminated"){
			$projectIsClosed=true;
		}
	}
}

?>


@section('page-script')
<script type="text/javascript">
window.addEvent('domready', function(){ 
       var JTooltips = new Tips($$('.hasTip'), 
       { maxTitleChars: 50, fixed: false}); 
    });
</script>
@show


@section('header')

@if ((in_array($this->item->owner, $user->getAuthorisedGroups())) || (in_array($this->item->ownerAL, $accessViewLevelsCurrentUser)) || ($user->authorise('core.read','com_hshrndreview')))

	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="<?php echo $routeToSelf ?>">
				@lang('COM_HSHRNDREVIEW_PROJECT_STATUS_FRONTEND_HEADING')
			</a>
			@if (in_array($this->item->owner, $user->getAuthorisedGroups()))
				@if ($projectIsClosed)
					<div class="pull-right hasTip"  title='<?php echo $toolTipNewStatusUpdate; ?>'>
						<a class="btn btn-success" disabled>
							<span class="icon icon-new"></span>
							@lang('COM_HSHRNDREVIEW_NEW_PROJECT_STATUS_UPDATE')
						</a>
					</div>
				@else
					<div class="pull-right hasTip"  title='<?php echo $toolTipNewStatusUpdateInvalid; ?>'>
						<a href="@route('index.php?option=com_hshrndreview&view=Statuses&task=edit&hshrndreview_status_id=' . $this->item->hshrndreview_fk_status_id . '&projectName=' . $this->item->name . '&projectStartDate=' . $this->item->startDate . '&isAssessment=0')" class="btn btn-success">
							<span class="icon icon-new"></span>
							@lang('COM_HSHRNDREVIEW_NEW_PROJECT_STATUS_UPDATE')
						</a>
					</div>
				@endif
			@endif
		</div>
	</div>

@endif

@show

@section('items')


@if ((in_array($this->item->owner, $user->getAuthorisedGroups())) || (in_array($this->item->ownerAL, $accessViewLevelsCurrentUser)) || ($user->authorise('core.read','com_hshrndreview')))

<table class="table table-bordered table-hover">
	<col width="20%">
	<col width="80%">
	<tr>
		<th scope="row" class="hasTip" title='<?php echo $toolTipNameHeader; ?>'>Project Name</th>
		<td>{{{$this->item->name}}}</td>
	</tr>
	<tr>
		<th scope="row" class="hasTip" title='<?php echo $toolTipOwnerHeader; ?>'>Project Owner</th>
		<td>@ownerTitle($this->item->owner)</td>
	</tr>
  	<tr>
  		<th scope="row" class="hasTip" title='<?php echo $toolTipProjectTypeHeader; ?>'>Project Type</th>
		<td>{{{$this->item->project_type}}}</td>
  	</tr>
  	<tr>
  		<th scope="row" class="hasTip" title='<?php echo $toolTipStartDateHeader; ?>'>Start Date</th>
		<td>@regdate($this->item->startDate)</td>
  	</tr>
</table>

<table class="hsh_rnd_projects_table">
<thead>
	
	<tr class="hsh_rnd_projects_table_tr">
		<th class="hsh_rnd_projects_table_th" width="7%">
			<div class="hasTip" title='<?php echo $toolTipSubmDateHeader; ?>'>Subm. Date</div><div class="hasTip" title='<?php echo $toolTipFinishDateHeader; ?>'>Finish Date</div><div class="hasTip" title='<?php echo $toolTipDurationHeader; ?>'>Duration</div>
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipCostsHeader; ?>' width="7%">
    		Est.Costs 
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipStatusHeader; ?>' width="7%">
			Status
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipObjectiveHeader; ?>' width="20%">
			Objective
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipExtPartnerHeader; ?>' width="7%">
			Ext. Partner
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipResponsibleHeader; ?>' width="7%">
			Responsible
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipMilestonesHeader; ?>' colspan="2" width="18%">
			Milestones
		</th>
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipReasonFChangeHeader; ?>' width="18%">
	 		Reason f. changes?
		</th>
		
		@if ( ($uGroupTitle == $uReviewerGroup) || ($uGroupTitle == $uHSHEditorGroup))
		<th class="hsh_rnd_projects_table_th hasTip" title='<?php echo $toolTipAssessmentHeader; ?>' width="18%">
			Assessment
		</th>			
		@endif
	</tr>

</thead>
<tbody> 
	@foreach ($this->item->allStatuses as $status)

		<tr  class="hsh_rnd_projects_table_tr">
			{{-- submission date --}} 
			{{-- finish project date--}}			
			{{-- project duration--}}	
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				<p>@regdate($status->created_on) </p>
				<p>@colorDateGreenOrangeRedWoCheckBox($status->msVolumeProductionDate , $status->msVolumeProductionDateChanged, $status->msVolumeProductionReached ) </p>
				<p>@dateIntervalWColorLabelGreenOrange($status->projectDuration , $status->projectDurationChanged )</p>
			</td>
			{{-- estimated project costs --}}
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				@colorLabelGreenOrange($status->estimatedProjectCosts , $status->estimatedProjectCostsChanged )
			</td>

			{{-- project status --}}
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				@colorLabelBlue($status->projectStatus , $status->projectStatusChanged )
			</td>  
			
			{{-- project objective --}}
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				<div class="do_scroll">
				@colorStrBlue($status->objective , $status->objectiveChanged )
				</div>
			</td>  
			
			{{-- external project partner --}}			
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				<div class="do_scroll">
				@colorStrBlue($status->externalPartner , $status->externalPartnerChanged )
				</div>
			</td>  
			
			{{-- project reponsible --}}			
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				@colorStrBlue($status->responsible , $status->responsibleChanged )
			</td>



			{{-- user requirement specification label --}}
			<td  class="hsh_rnd_projects_table_td2 hasTip" title='<?php echo $toolTipURSRel; ?>'>
				URS Rel.:
			</td>
			
			{{-- user requirement specification date --}}
			<td  class="hsh_rnd_projects_table_td2">
				@colorDateGreenOrangeRed($status->msURSReleaseDate , $status->msURSReleaseDateChanged , $status->msURSReleasedReached )
			</td>  




			{{-- justification for a change --}}			
			<td class="hsh_rnd_projects_table_td4" rowspan="5">
				<div class="do_scroll">
				{{{$status->justificationForAChange}}}
				</div>
			</td>			
			
			
			{{-- my assessment --}}			
			@if ( ($uGroupTitle == $uReviewerGroup) || ($uGroupTitle == $uHSHEditorGroup))
			<td class="hsh_rnd_projects_table_td2" rowspan="4">
				<div class="do_scroll">
				{{{$status->projectAssessment}}}
				</div>
			</td>
			@endif
		</tr>
			
		<tr class="hsh_rnd_projects_table_tr">
			{{-- functional specification label --}}	
			<td class="hasTip" title='<?php echo $toolTipFSRel; ?>'>
				FS Rel.:
			</td>
			
			{{-- functional specification date --}}	
			<td>
				@colorDateGreenOrangeRed($status->msFSReleaseDate , $status->msFSReleaseDateChanged , $status->msFSReleasedReached )
			</td>			
		</tr>	

		
		<tr class="hsh_rnd_projects_table_tr">
			{{-- experimental model available label --}}	
			<td class="hasTip" title='<?php echo $toolTipExpModel; ?>'>
				ExpModel Rel.: 
			</td>
			
			{{-- experimental model available date --}}	
			<td>
				@colorDateGreenOrangeRed($status->msExpModelReleaseDate , $status->msExpModelReleaseDateChanged , $status->msExpModelReleasedReached )
			</td>  
		</tr>
			
		
		<tr class="hsh_rnd_projects_table_tr">
			
			{{-- prototype available label --}}	
			<td  class="hasTip" title='<?php echo $toolTipPrototype; ?>'>
				Proto. Rel.:
			</td>
			
			{{-- prototype available date --}}	
			<td>
				@colorDateGreenOrangeRed($status->msPrototypeReleaseDate , $status->msPrototypeReleaseDateChanged , $status->msPrototypeReleasedReached )
			</td> 
		</tr>	

		<tr class="hsh_rnd_projects_table_tr">

			{{-- volume production label --}}			
			<td  class="hsh_rnd_projects_table_td3 hasTip" title='<?php echo $toolTipVolumeProduction; ?>'>
				Vol.Prod Start:
			</td>
			
			{{-- volume production date --}}			
			<td  class="hsh_rnd_projects_table_td3">
				@colorDateGreenOrangeRed($status->msVolumeProductionDate , $status->msVolumeProductionDateChanged , $status->msVolumeProductionReached )
			</td>  

			
			@if ( ($uGroupTitle == $uReviewerGroup) || ($uGroupTitle == $uHSHEditorGroup))
			<td class="hsh_rnd_projects_table_td3">
				{{-- only member of the uHSHEditorGroup should be allowed to submit assessments  --}}
				@if ($uGroupTitle == $uHSHEditorGroup)
				<a class="btn btn-small" href="@route('index.php?option=com_hshrndreview&view=Statuses&task=edit&hshrndreview_status_id=' . $status->hshrndreview_status_id . '&projectName=' . $this->item->name . '&projectStartDate=' . $this->item->startDate . '&isAssessment=1')">
					<span class="icon icon-edit"></span></a>
				@endif
			</td>
			@endif
		</tr> 
		
		
		
	@endforeach


</tbody>
</table>

<hr>
<h3 class="hasTip" title='<?php echo $toolTipColorLegend; ?>'>Color Legend</h3>
<table style="border:1px solid gray">
	<col width="60px">
	<col width="450px">
	<tr>
		<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelGreenOrange(" Gray Color " , 0 )</th>
		<td style="border:1px solid grey;">{{{"\"No Change\" w respect to the last submission..."}}}</td>
	</tr>
	<tr>
		<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelBlue(" Blue Color " , 1)</th>
		<td style="border:1px solid grey;">{{{"\"Non-judgmental\" change of a field..."}}}</td>
	</tr>
	<tr>
		<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelGreenOrange(" Green Color" , -1 )</th>
		<td style="border:1px solid grey;">{{{"\"Positive Change\" (e.g. Costs decreased or milestone pushed forward...)"}}}</td>
	</tr>
	<tr>
		<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelGreenOrange("Orange Color" , 1 )</th>
		<td style="border:1px solid grey;">{{{"\"Negative Change\" (e.g. Costs increased or milestone pushed backward...)"}}}</td>
	</tr>
	<tr>
		<th style="text-align:left;border:1px solid grey;" scope="row">@colorLabelRed("  Red Color " , 1 )</th>
		<td style="border:1px solid grey;">{{{"\"Failed to meet the deadline...\""}}}</td>
	</tr>
</table>

@endif


@show
