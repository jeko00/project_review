<?php
/**
 * @package		com_hshrndprojects
 * @copyright	Copyright (c)2016 Jens H. Kowal / me.com
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Site\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

class Collaborators extends DataModel
{
	public function __construct(Container $container, array $config = array())
	{
		
		$config['behaviours'] = array('Filters');
		
	    parent::__construct($container, $config);
		//function hasMany(string $name, string $foreignModelClass = null, string $localKey = null, string $foreignKey = null)
		$this->hasMany("allPartner" , "Partnerships", "hshrndreview_collaborator_id", "hshrndreview_fk_collaborator_id" );	
	}

}