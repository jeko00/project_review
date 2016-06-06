<?php
/**
 * @package		hshrndreview
 * @copyright	Copyright (c)2016 jk
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Admin\Controller;

use HSH\HSHRnDReview\Admin\Model\Projects;
use FOF30\Controller\DataController;

defined('_JEXEC') or die();

class Project extends DataController
{

	
	/**
	 * Redirects the user to the Thank You page after successfully receiving the message
	 *
	 * @return  bool  True to continue processing
	 */
	protected function onAfterSave()
	{
		/** @var Items $model */
		$model = $this->getModel();

		//if ($model->saveSuccessful)
		//{
			$this->setRedirect(\JRoute::_('index.php?option=com_hshrndreview&view=ThankYou'));
		//}

		return true;
	}
	protected function onBeforeSave()
	{
		/** @var Items $model */
		$model = $this->getModel();

		dump($query, '__hilfe__ ADMIN CONTROLLER PROJECTSSSSSSS....');


		//if ($model->saveSuccessful)
		//{
			$this->setRedirect(\JRoute::_('index.php?option=com_hshrndreview&view=ThankYou'));
		//}

		return true;
	}
}