<?php
/**
 * @package		com_hshrndprojects
 * @copyright	Copyright (c)2016 Jens H. Kowal / me.com
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

class Projects extends DataModel
{
	public function buildQuery($overrideLimits = false)
    {	
	    $query = parent::buildQuery($overrideLimits);
		return $query;	
	}
}