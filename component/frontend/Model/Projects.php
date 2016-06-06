<?php
/**
 * @package		com_hshrndprojects
 * @copyright	Copyright (c)2016 Jens H. Kowal / me.com
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Site\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

class Projects extends DataModel
{
    /** @var   bool  Did we save the record successfully? Used by the controller for conditional 
redirection to the Thank You page. */
    public $saveSuccessful = false;
	
	public $isNewProject = false;

    /**
    * This method is only called after a record is saved. We will hook on it
    * to send an email to the address specified in the category.
    *
    * @return  bool
    */
    protected function onAfterSave()
    {
    	$this->saveSuccessful = true;
		
		//project item will be stored 2 times once without a status id and then the status item will be stored 
		//and then project item will be stored a second time this time with a valid status (this is nesessary 
		//because the project item has the status id as current status id and status item has to have the project id as well)
		//therefore we will send the confirmation only the second time the project item is stored
		if( $this->isNewProject ){ 
        	$this->_sendNewProjectEmailToAdministrators();
        	$this->_sendNewProjectEmailToUser();
		}
    }


    /**
     * Sends an email to all contact category administrators.
     */
    private function _sendNewProjectEmailToAdministrators()
    {
            // Get a reference to the Joomla! mailer object
            $mailer = \JFactory::getMailer();
			
			$config = \JFactory::getConfig();
			$sender = array( 
			    $config->get( 'mailfrom' ),
			    $config->get( 'fromname' ) 
			);
 
			$mailer->setSender($sender);

            // Load the category and set the recipient to this category's email address
			//            $category = $this->category;
			//            $emails = explode(',', $category->email);
			$user = \JFactory::getUser('admin');
			$mailer->addRecipient($user->email);
            
            // Set the subject
            $subject = \JFactory::getConfig()->get('sitename') . ": New Development Project Submission...";
			$mailer->setSubject($subject);
			$name = $this->recordData['name'];
			$owner = $this->recordData['owner'];
			
			$db = \JFactory::getDbo();
			//this is just to know whether we are in the user is in the reviewer group or not
			$query = $db->getQuery(true);
			$query->select('title');
			$query->from($db->quoteName('#__usergroups'));
			$query->where($db->quoteName('id')." = ". $owner);
			$db->setQuery($query)->loadObject();
			$ownerName = $db->loadResult();
			$ownerName = ltrim($ownerName, "hsgrp_");
			
			$submitter = \JFactory::getUser();
			$submitterName = $submitter->name;
			
			$body   = "A new development project named: \"" . $name . "\" has been submitted by " . $submitterName . "@" . $ownerName . " to the HSH project database!\n\n Have a nice day - your friendly HSH RnD Review automailer...";
			$mailer->setBody($body);
            // Send the email
			$send = $mailer->Send();
			if ( $send !== true ) {
			    echo 'Error sending email: ' . $send->__toString();
			} else {
			    echo 'Mail sent';
			}
    }
	
    /**
     * Sends an email to all contact category administrators.
     */
    private function _sendNewProjectEmailToUser()
    {
            // Get a reference to the Joomla! mailer object
            $mailer = \JFactory::getMailer();
			
			$config = \JFactory::getConfig();
			$sender = array( 
			    $config->get( 'mailfrom' ),
			    $config->get( 'fromname' ) 
			);
 
			$mailer->setSender($sender);

			$user = \JFactory::getUser();
			$mailer->addRecipient($user->email);
            
            // Set the subject
            $subject = \JFactory::getConfig()->get('sitename') . ": New Development Project Submission...";
			$mailer->setSubject($subject);
			$name = $this->recordData['name'];
			
			$body   = "Thank you for submitting your new development project named: \"" . $name . "\". It has been added to the HSH project database!\n\n Have a nice day - your friendly HSH RnD Review automailer...";
			$mailer->setBody($body);
            // Send the email
			$send = $mailer->Send();
			if ( $send !== true ) {
			    echo 'Error sending email: ' . $send->__toString();
			} else {
			    echo 'Mail sent';
			}
    }
	
	
	public function __construct(Container $container, array $config = array())
	{
		
		$config['behaviours'] = array('Filters');
		
	 	parent::__construct($container, $config);
		//function hasOne(string $name, string $foreignModelClass = null, string $localKey = null, string $foreignKey = null)
		//$this->hasOne("currentStatus" , "Statuses", "hshrndreview_fk_status_id", "hshrndreview_status_id" );
		//relations do not work if you have to do sorting by related columns :-( Then you have to do a real JOIN in buildQuery()
		
		//function hasMany(string $name, string $foreignModelClass = null, string $localKey = null, string $foreignKey = null)
		$this->hasMany("allStatuses" , "Statuses", "hshrndreview_project_id", "hshrndreview_fk_project_id" );	
		
		
		$this->addKnownField('objective', '', 'text');
		$this->addKnownField('objectiveChanged', '', 'integer');

		$this->addKnownField('responsible', '', 'text');
		$this->addKnownField('responsibleChanged', '', 'integer');

		$this->addKnownField('estimatedProjectCosts', '', 'int');
		$this->addKnownField('estimatedProjectCostsChanged', '', 'integer');

		$this->addKnownField('projectStatus', '', 'text');
		$this->addKnownField('projectStatusChanged', '', 'integer');

		$this->addKnownField('projectDuration', '', 'text');
		$this->addKnownField('projectDurationChanged', '', 'integer');

		$this->addKnownField('msURSReleaseDate', '', 'text');
		$this->addKnownField('msURSReleaseDateChanged', '', 'integer');
		$this->addKnownField('msURSReleasedReached', '', 'integer');

		$this->addKnownField('msFSReleaseDate', '', 'text');
		$this->addKnownField('msFSReleaseDateChanged', '', 'integer');
		$this->addKnownField('msFSReleasedReached', '', 'integer');

		$this->addKnownField('msExpModelReleaseDate', '', 'text');
		$this->addKnownField('msExpModelReleaseDateChanged', '', 'integer');
		$this->addKnownField('msExpModelReleasedReached', '', 'integer');

		$this->addKnownField('msPrototypeReleaseDate', '', 'text');
		$this->addKnownField('msPrototypeReleaseDateChanged', '', 'integer');
		$this->addKnownField('msPrototypeReleasedReached', '', 'integer');

		$this->addKnownField('msVolumeProductionDate', '', 'text');
		$this->addKnownField('msVolumeProductionDateChanged', '', 'integer');
		$this->addKnownField('msVolumeProductionReached', '', 'integer');

		$this->addKnownField('justificationForAChange', '', 'text');
		
		$this->addKnownField('externalPartner', '', 'text');
		$this->addKnownField('externalPartnerChanged', '', 'integer');
		$this->addKnownField('projectAssessment', '', 'text');


		//this is just a hack to make all project visible because pagination is somehow not working yet 05/2016 /jk
		//$this->setState('limit',5);
		
		
		$this->addBehaviour('Filters');
   	}
	
	
	public function buildQuery($overrideLimits = false)
    {	
		//get the values from the post request
		$formData=$_POST;
		
		// Get a "select all" query
		$db = $this->getDbo();
		$query = $db->getQuery(true)
        ->select('p.* , s.*')
        ->from('#__hshrndreview_projects AS p')
        ->join('INNER', ' #__hshrndreview_statuses AS s ON ( p.hshrndreview_fk_status_id = s.hshrndreview_status_id )');
		
		//here we make sure that we do the query with a constrained where clause: "load only rows I have read access to..." 2016/jk
		$accessViewLevelsCurrentUser = \JFactory::getUser()->getAuthorisedViewLevels();
		$idList = implode(',', $accessViewLevelsCurrentUser);
		$query->where($db->quoteName('ownerAL') . ' IN '. '('. $idList . ')');
		
		//constrain the query by project status if necessary  
		if ( $formData['projectStatusSearchSelHidden'] != ""){
			$query->where($db->quoteName('projectStatus') . ' IN '. '('. $formData['projectStatusSearchSelHidden'] . ')');			
			$this->setState('currentProjectStatusSearchSel', $formData['projectStatusSearchSelHidden']);
		}
		
		//constrain the query by company if necessary  
		if ( $formData['companySearchSelHidden'] != ""){
			$query->where($db->quoteName('owner') . ' IN '. '('. $formData['companySearchSelHidden'] . ')');			
			$this->setState('currentCompanySearchSel', $formData['companySearchSelHidden']);
		}
		
		
		

		// Run the "before build query" hook and behaviours
		$this->triggerEvent('onBeforeBuildQuery', array(&$query, $overrideLimits));

		// Apply custom WHERE clauses
		if (count($this->whereClauses))
		{
			foreach ($this->whereClauses as $clause)
			{
				$query->where($clause);
			}
		}

		$order = $this->getState('filter_order', null, 'cmd');

		if (!array_key_exists($order, $this->knownFields))
		{
			$order = $this->getIdFieldName();
			$this->setState('filter_order', $order);
		}

		$order = $db->qn($order);

		$dir = strtoupper($this->getState('filter_order_Dir', null, 'cmd'));

		if (!in_array($dir, array('ASC', 'DESC')))
		{
			$dir = 'ASC';
			$this->setState('filter_order_Dir', $dir);
		}

		$query->order($order . ' ' . $dir);

		// Run the "before after query" hook and behaviours
		$this->triggerEvent('onAfterBuildQuery', array(&$query, $overrideLimits));

		return $query;	
	}
	
	
	public function recordDataToDatabaseData()
	{
		$copy = array_merge($this->recordData);

		$projectsArrayElements = array('hshrndreview_project_id', 'name', 'slug', 'owner', 'ownerAL', 'project_type', 'startDate', 'hshrndreview_fk_status_id', 'enabled', 'ordering', 'created_on', 'created_by', 'modified_on', 'modified_by', 'locked_on', 'locked_by', 'hits');

		foreach ($copy as $name => $value)
		{
			if(in_array($name, $projectsArrayElements)){
				$method = $this->container->inflector->camelize('set_' . $name . '_attribute');

				if (method_exists($this, $method))
				{
					$copy[$name] = $this->{$method}($value);
				}
			}
			else{
				//remove element
				unset($copy[$name]);
			}
		}
		return $copy;
	}


}