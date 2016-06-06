<?php
/**
 * @package		hshrndresearch
 * @copyright	2016 jk 
 * @license		GNU GPL version 3 or later
 */

namespace HSH\HSHRnDReview\Site\View\Collaborators;

use FOF30\Container\Container;
use FOF30\View\Compiler\Blade;
use FOF30\View\DataView\Form as DataViewForm;

class Form extends DataViewForm
{
	public function __construct(Container $container, array $config = array())
	{
		// Create a custom Blade behaviour
		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('ownerTitle'); 
		// shows a the group title if first argument  > 0 
		
		$code = '
		$1<?php 		
		$chkArgs = array$2; 
		
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(\'title\');
		$query->from($db->quoteName(\'#__usergroups\'));
		$query->where($db->quoteName(\'id\')." = ". $chkArgs[0]);
		$db->setQuery($query)->loadObject();
		$title = $db->loadResult();
		$title = ltrim($title, "hsgrp_");
		
		echo $title; ?>';
		return preg_replace($pattern, $code, $template);
		});

		parent::__construct($container, $config);
	}
}