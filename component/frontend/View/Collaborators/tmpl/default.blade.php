{{--
@package	hshrndreview
@copyright	2016 jk
@license	GNU GPL version 3 or later
--}}
<?php
/** @var \FOF30\View\DataView\Html $this */


//----------------------------------------------------------------------------------------------------------------------------
//default.blade.php: is the collaborators oveview screen showing ALL partenships sorted by collaborators    
//----------------------------------------------------------------------------------------------------------------------------


$routeToSelf = \JRoute::_('index.php/partnerships');
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

$currentFilter=($this->getModel()->getState('filter_order'));
$currentOrder=($this->getModel()->getState('filter_order_Dir') == "asc" ?  "<span class=\"icon icon-arrow-up\"></span>" : "<span class=\"icon icon-arrow-down\"></span>");
$oppositeOrder=($this->getModel()->getState('filter_order_Dir') == "asc" ? "desc" : "asc");
?>

@section('header')
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="<?php echo $routeToSelf ?>">
				R&D Project Partner
			</a>
			<form class="navbar-form pull-left" name="comHSHRNDReviewHeaderInlineForm{{$uniqueId}}"
				  action="index.php" method="post" >
			</form>			
		</div>
	</div>
@show

@section('items')
	<form action="index.php" id="adminForm" name="adminForm" method="post">
	<table class="hsh_rnd_projects_table">
		<col width="30%">
		<col width="70%">
	<thead>
		<tr>
			<th class="hsh_rnd_projects_table_th">External Partner</th>
			<th class="hsh_rnd_projects_table_th">Subsidiaries</th>
		</tr>
	</thead>
	<tbody> 
	@foreach ($this->items as $item)
		@if ($item->name != "none" && $item->name != "None")
		<tr class="hsh_rnd_projects_partnership_table_tr">
			<td class="hsh_rnd_projects_table_td4">
				{{{$item->name}}}
			</td>
			<td class="hsh_rnd_projects_table_td4">
				@foreach ($item->allPartner as $partner)
					@ownerTitle($partner->owner_id),  
				@endforeach
			</td>
		</tr>
		@endif	
	@endforeach
	</tbody>
	</table>
	
	<input type="hidden" name="option" value="com_hshrndreview">
	<input type="hidden" name="view" value="Collaborators">
	<input type="hidden" name="task" value="browse">
	
	
	{{$this->getPagination()->getPaginationLinks()}}
	</form>
@show
