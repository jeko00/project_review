<?php
/**
 * @package		com_hshrndprojects
 * @copyright	Copyright (c)2016 Jens H. Kowal / me.com
 * @license		GNU General Public License version 2 or later
 */

namespace HSH\HSHRnDReview\Site\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

class Partnerships extends DataModel
{
	public function __construct(Container $container, array $config = array())
	{
	    parent::__construct($container, $config);
	}
}