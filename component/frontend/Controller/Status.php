<?php
/**
 * @package		hshrndreview
 * @copyright	Copyright (c)2016 jk
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Site\Controller;

use HSH\HSHRnDReview\Site\Model\Statuses;
use FOF30\Controller\DataController;

defined('_JEXEC') or die();

class Status extends DataController
{
	public function addStatusUpdate()
	{
		//get the values from the post request (from the input form)
		$statusData=$_POST;
		
		//get overall duration
		$begin = date_create($statusData['projectStartDateOld']);
		$end = date_create($statusData['msVolumeProductionDate']);
		$duration  = date_diff($begin, $end);
		
		
		//set estimatedProjectCostsChanged flag
		$estimatedProjectCostsChanged = 0;
		if( $statusData['estimatedProjectCostsOld']>$statusData['estimatedProjectCosts'] ){
			$estimatedProjectCostsChanged = -1;
		}
		else if( $statusData['estimatedProjectCostsOld']<$statusData['estimatedProjectCosts'] ){
			$estimatedProjectCostsChanged = 1;
		}
		
		//set msURSReleaseDateChanged flag
		$msURSReleaseDateChanged = 0;
		if( $statusData['msURSReleaseDateOld']>$statusData['msURSReleaseDate'] ){
			$msURSReleaseDateChanged = -1;
		}
		else if( $statusData['msURSReleaseDateOld']<$statusData['msURSReleaseDate'] ){
			$msURSReleaseDateChanged = 1;
		}
		
		
		//set msFSReleaseDateChanged flag
		$msFSReleaseDateChanged = 0;
		if( $statusData['msFSReleaseDateOld']>$statusData['msFSReleaseDate'] ){
			$msFSReleaseDateChanged = -1;
		}
		else if( $statusData['msFSReleaseDateOld']<$statusData['msFSReleaseDate'] ){
			$msFSReleaseDateChanged = 1;
		}
		
		//set msExpModelReleaseDateChanged flag
		$msExpModelReleaseDateChanged = 0;
		if( $statusData['msExpModelReleaseDateOld']>$statusData['msExpModelReleaseDate'] ){
			$msExpModelReleaseDateChanged = -1;
		}
		else if( $statusData['msExpModelReleaseDateOld']<$statusData['msExpModelReleaseDate'] ){
			$msExpModelReleaseDateChanged = 1;
		}
		
		//set msPrototypeReleaseDateChanged flag
		$msPrototypeReleaseDateChanged = 0;
		if( $statusData['msPrototypeReleaseDateOld']>$statusData['msPrototypeReleaseDate'] ){
			$msPrototypeReleaseDateChanged = -1;
		}
		else if( $statusData['msPrototypeReleaseDateOld']<$statusData['msPrototypeReleaseDate'] ){
			$msPrototypeReleaseDateChanged = 1;
		}

		//set msVolumeProductionDateChanged/projectDurationChanged flag (because startDate is fixed projectDurationChanged is basically the same as msVolumeProductionDateChanged)
		$projectDurationChanged = 0;
		$msVolumeProductionDateChanged = 0;
		if( $statusData['msVolumeProductionDateOld']>$statusData['msVolumeProductionDate'] ){
			$msVolumeProductionDateChanged = -1;
			$projectDurationChanged = -1;
		}
		else if( $statusData['msVolumeProductionDateOld']<$statusData['msVolumeProductionDate'] ){
			$msVolumeProductionDateChanged = 1;
			$projectDurationChanged = 1;
		}




		//create an array with the misisng information to fill the status table completely 
		$supplementalStatusData = array( 
		                                "objectiveChanged" => ($statusData['objectiveOld']!=$statusData['objective'] ? 1 : 0), 
		                                "responsibleChanged" => ($statusData['responsibleOld']!=$statusData['responsible'] ? 1 : 0), 
		                                "projectDuration" => $duration->format('P%yY%mM%dD'), 
		                                "projectDurationChanged" => $projectDurationChanged, 
		                                "estimatedProjectCostsChanged" => $estimatedProjectCostsChanged, 
		                                "projectStatusChanged" => ($statusData['projectStatusOld']!=$statusData['projectStatus'] ? 1 : 0), 
		                                "msURSReleaseDateChanged" => $msURSReleaseDateChanged, 
		                                "msFSReleaseDateChanged" => $msFSReleaseDateChanged, 
		                                "msExpModelReleaseDateChanged" => $msExpModelReleaseDateChanged, 
		                                "msPrototypeReleaseDateChanged" => $msPrototypeReleaseDateChanged, 
		                                "msVolumeProductionDateChanged" => $msVolumeProductionDateChanged,
										"externalPartnerChanged" => ($statusData['externalPartnerOld']!=$statusData['externalPartner'] ? 1 : 0));

		$completeStatusData = array_merge($statusData, $supplementalStatusData);

		$statusModel = $this->container->factory->model('Statuses')->tmpInstance();
		$statusModel->isNewStatusUpdate = true;
		$statusModel->bind($completeStatusData)->save();
		

		//get the project id
		$projectDataKey = array("hshrndreview_project_id" => $statusData['hshrndreview_fk_project_id']);
		//load the associated project record
		$projectModel = $this->container->factory->model('Projects')->findOrFail($projectDataKey);
		//update the project record to point to the latest status update
		$projectModel->setFieldValue("hshrndreview_fk_status_id", $statusModel->getId());
		$projectModel->isNewProject = false; //we send confirmation email in projects only if the project is created new 
		$projectModel->save();
		
		
		
		
		
		//create an array from all external partner
		$externalPartnerArray = preg_split("/\r\n|\n|\r/", $statusData['externalPartner']);
		
		
		$collaboratorsModel = $this->container->factory->model('Collaborators');
		$partnerShipsModel = $this->container->factory->model('Partnerships');
		
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
		
	}
	
	public function addAssessement()
	{
		//get the values from the post request (from the input form)
		$statusData=$_POST;
		$statusModel = $this->container->factory->model('Statuses')->findOrFail($statusData['hshrndreview_status_id']);
		$statusModel->setFieldValue("projectAssessment", $statusData['projectAssessment']);
		$statusModel->save();
	}

	public function execute($task) {
	        switch ($task) {
	            case 'addStatusUpdate':
	                $this->task = 'addStatusUpdate';
	                break;
		        case 'addAssessement':
		            $this->task = 'addAssessement';
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
	protected function onAfterAddStatusUpdate()
	{
		$this->setRedirect(\JRoute::_('index.php?option=com_hshrndreview&view=Projects'),"Thank you for submitting the status update. A confirmation email has been send to you...");

		return true;
	}
	
	
	protected function onAfterAddAssessement()
	{
		$this->setRedirect(\JRoute::_('index.php?option=com_hshrndreview&view=Projects'),"Thank you for submitting the assessment...");

		return true;
	}
	
}