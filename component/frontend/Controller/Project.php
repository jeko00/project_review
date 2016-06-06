<?php
/**
 * @package		hshrndreview
 * @copyright	Copyright (c)2016 jk
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Site\Controller;

use HSH\HSHRnDReview\Site\Model\Projects;
use FOF30\Controller\DataController;

defined('_JEXEC') or die();

class Project extends DataController
{
	public function addProjectAndStatus()
	{
//		dump('Project::addProjectAndStatus()....');
		
		//get the values from the post request (from the input form)
		$formData=$_POST;
		
		//determine the owner group
		$grp = \JAccess::getGroupsByUser($this->container->platform->getUser()->id, false); 
		//this is a very hands on approach to get the hsh group (some how the 'false' in getGroupsByUser does not work) 03/2016/jk
		$ownergroup = max($grp);

		// the new projects inherits the Viewing Access Level of the user creating the project - should be ok or? 06/2016 /jk 
		$levels = \JFactory::getUser()->getAuthorisedViewLevels();
		$ownerAL = max($levels);	

		//create an array with the misisng information to fill the project table completely 
		$supplementalProjectData = array("slug" => "", "owner" => $ownergroup, "ownerAL" => $ownerAL, "project_type" =>"Development");

		//merge the missing project data  
		$projectData = array_merge($formData, $supplementalProjectData);
		
		//get the Projects model
		$projectModel = $this->container->factory->model('Projects')->tmpInstance();
		//...and save it
		$projectModel->isNewProject = false; //we send confirmation email just the second time th emodel is stored see further down 
		$projectModel->bind($projectData)->save();
		
		$begin = date_create($projectData['startDate']);
		$end = date_create($projectData['msVolumeProductionDate']);
		$duration  = date_diff($begin, $end);

		//create an array with the misisng information to fill the status table completely 
		$supplementalStatusData = array("hshrndreview_fk_project_id" => $projectModel->getId(), 
		                                "objectiveChanged" => 0, 
		                                "responsibleChanged" => 0, 
		                                "estimatedProjectCostsChanged" => 0, 
		                                "projectStatusChanged" => 0, 
		                                "projectDuration" => $duration->format('P%yY%mM%dD'), 
		                                "projectDurationChanged" => 0, 
		                                "msURSReleaseDateChanged" => 0, 
		                                "msURSReleaseDateChanged" => 0, 
		                                "msFSReleaseDateChanged" => 0, 
		                                "msExpModelReleaseDateChanged" => 0, 
		                                "msPrototypeReleaseDateChanged" => 0, 
		                                "msVolumeProductionDateChanged" => 0, 
										"externalPartnerChanged" => 0,
		                                "justificationForAChange" => "");

		$statusData = array_merge($formData, $supplementalStatusData);

		$statusModel = $this->container->factory->model('Statuses')->tmpInstance();
		$statusModel->isNewStatusUpdate = false;
		$statusModel->bind($statusData)->save();
		
		//store the current status id in the project record
		$projectData['hshrndreview_fk_status_id'] = $statusModel->getId();
		$projectModel->isNewProject = true;
		$projectModel->bind($projectData)->save();
		
		
		
		//create an array from all external partner
		$externalPartnerArray = preg_split("/\r\n|\n|\r/", $statusData['externalPartner']);
		
		
		$collaboratorsModel = $this->container->factory->model('Collaborators');
		
		$projectOwner = $projectModel->getFieldValue('owner');
		
		
		foreach ($externalPartnerArray as $partner) {

			$collaboratorId=0;
			$items = $collaboratorsModel->where('name', '==', $partner)->get();

			if(count($items) > 0){
				//there is always only one - I have to find out how to access the first item in collection instead 04/2016/jk
				foreach ($items as $ii) {
					$collaboratorId=$ii->getFieldValue('hshrndreview_collaborator_id');
				}
			}
			else{
				$collaborator = array("name" => $partner);
				$collaboratorModel = $collaboratorsModel->tmpInstance();
				$collaboratorModel->bind($collaborator)->save();
				$collaboratorId=$collaboratorModel->getId();
			}
			
			$partnerShipsModel = $this->container->factory->model('Partnerships');
			$partnerShipItems = $partnerShipsModel->where('hshrndreview_fk_collaborator_id', '==', $collaboratorId)->get();
			$isListed=false;
			foreach ($partnerShipItems as $partnerShipItem) {
				if($partnerShipItem->owner_id == $projectOwner){
					$isListed=true;
				}
			}
			if(!$isListed){
				$partnerShipData = array("hshrndreview_fk_collaborator_id" => $collaboratorId, "owner_id" => $projectOwner);
				$partnerShipModel = $partnerShipsModel->tmpInstance();
				$partnerShipModel->bind($partnerShipData)->save();
			}
		}
		
		
		
		return true;
	}

	public function execute($task) {
	        switch ($task) {
	            case 'addProjectAndStatus':
	                $this->task = 'addProjectAndStatus';
	                break;
	            default:
	                $this->task = $task;
	                break;
	        }
	        parent::execute($this->task);
	 }




	
	/**
	 * Redirects the user to the Thank You page after successfully receiving the message
	 *
	 * @return  bool  True to continue processing
	 */
	protected function onAfterAddProjectAndStatus()
	{
		
		$this->setRedirect(\JRoute::_('index.php?option=com_hshrndreview&view=Projects'),"Thank you for submitting your new development project. A confirmation email has been send to you...");

		return true;
	}
}