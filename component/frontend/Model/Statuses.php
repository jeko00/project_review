<?php
/**
 * @package		com_hshrndprojects
 * @copyright	Copyright (c)2016 Jens H. Kowal / me.com
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Site\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

class Statuses extends DataModel
{
	
	public $saveSuccessful = false;

	public $isNewStatusUpdate = false;
	
	
    /**
    * This method is only called after a record is saved. We will hook on it
    * to send an email to the address specified in the category.
    *
    * @return  bool
    */
    protected function onAfterSave()
    {
    	$this->saveSuccessful = true;
		
		if( $this->isNewStatusUpdate ){ 
        	$this->_sendNewStatusUpdateEmailToAdministrators();
        	$this->_sendNewStatusUpdateEmailToUser();
		}
    }
	
    /**
     * Sends an email to all contact category administrators.
     */
    private function _sendNewStatusUpdateEmailToAdministrators()
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
            $subject = \JFactory::getConfig()->get('sitename') . ": New Status Update ...";
			$mailer->setSubject($subject);
			$projectId = $this->recordData['hshrndreview_fk_project_id'];
			
			$db = \JFactory::getDbo();
			//this is just to know whether we are in the user is in the reviewer group or not
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('name', 'owner')));
			$query->from($db->quoteName('#__hshrndreview_projects'));
			$query->where($db->quoteName('hshrndreview_project_id')." = ". $projectId);
			$db->setQuery($query);
			$project = $db->loadRow();
			
			$owner = $project['1'];
			//this is just to know whether we are in the user is in the reviewer group or not
			$query2 = $db->getQuery(true);
			$query2->select('title');
			$query2->from($db->quoteName('#__usergroups'));
			$query2->where($db->quoteName('id')." = ". $owner);
			$db->setQuery($query2)->loadObject();
			$ownerName = $db->loadResult();
			$ownerName = ltrim($ownerName, "hsgrp_");		
			
			$submitter = \JFactory::getUser();
			$submitterName = $submitter->name;
			
			//$ownerName = ltrim($ownerName, "hsgrp_");
			
			$body   = "A new status update for project named: \"" . $project['0'] . "\" has been submitted by " . $submitterName . "@" . $ownerName . " to the HSH project database!\n\n Have a nice day - your friendly HSH RnD Review automailer...";
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
    private function _sendNewStatusUpdateEmailToUser()
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
            $subject = \JFactory::getConfig()->get('sitename') . ": New Status Update...";
			$mailer->setSubject($subject);
			$projectId = $this->recordData['hshrndreview_fk_project_id'];
			
			$db = \JFactory::getDbo();
			//this is just to know whether we are in the user is in the reviewer group or not
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('name', 'owner')));
			$query->from($db->quoteName('#__hshrndreview_projects'));
			$query->where($db->quoteName('hshrndreview_project_id')." = ". $projectId);
			$db->setQuery($query);
			$project = $db->loadRow();
			
			$body   = "Thank you for submitting a new status update for project named: \"" . $project['0'] . "\". It has been added to the HSH project database!\n\n Have a nice day - your friendly HSH RnD Review automailer...";
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
	    parent::__construct($container, $config);

	}


	public function onBeforeBuildQuery(&$query)
	{
		$this->setState('filter_order', 'created_on');
//		$this->setState('filter_order', 'hshrndreview_status_id');
		$this->setState('filter_order_Dir', 'DESC');
	}	

	public function onAfterBuildQuery(&$query)
	{
		//dump($query->order);
	}

}