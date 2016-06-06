<?php
/**
 * @package		com_hshrndprojects
 * @copyright	Copyright (c)2016 Jens H. Kowal / me.com
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

class Statuses extends DataModel
{
	public function __construct(Container $container, array $config = array())
	{
	        parent::__construct($container, $config);

			//function hasOne(string $name, string $foreignModelClass = null, string $localKey = null, string $foreignKey = null)
//	        $this->hasOne("prjName", "Projects", "hshrndreview_project_id", "hshrndreview_project_id");
			
	}

	public function buildQuery($overrideLimits = false)
	{
		$query = parent::buildQuery($overrideLimits);

		// Manipulate your $query here

		return $query;
	}
}