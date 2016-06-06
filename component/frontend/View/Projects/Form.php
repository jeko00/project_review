<?php
/**
 * @package		hshrndresearch
 * @copyright	2016 jk 
 * @license		GNU GPL version 3 or later
 */

namespace HSH\HSHRnDReview\Site\View\Projects;

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
		$pattern = $compiler->createMatcher('date'); // Defines an @date directive with one argument

			$code = '
		$1<?php
		$dateMatcherArgs = array$2;
		if (!isset($dateMatcherArgs[1]) || empty($dateMatcherArgs[1]))
		{
			$dateMatcherArgs[1] = \'DATE_FORMAT_LC3\';
		}
		$dateMatcherJDate = new \JDate($dateMatcherArgs[0]);
		$class = ($dateMatcherJDate->toUNIX() < time()) ? \'label label-important\' : \'label \';
		echo "<span class=\"$class\">" . $dateMatcherJDate->format(\JText::_($dateMatcherArgs[1])) . "</span>";
		?>
		';
		
		return preg_replace($pattern, $code, $template);
		});


		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('regdate'); // Defines an @regdate directive with one argument

			$code = '
		$1<?php
		$dateMatcherArgs = array$2;
		if (!isset($dateMatcherArgs[1]) || empty($dateMatcherArgs[1]))
		{
			$dateMatcherArgs[1] = \'DATE_FORMAT_LC3\';
		}
		$dateMatcherJDate = new \JDate($dateMatcherArgs[0]);
		$class = \'label \';
		echo "<span class=\"$class\">" . $dateMatcherJDate->format(\JText::_($dateMatcherArgs[1])) . "</span>";
		?>
		';
		
		return preg_replace($pattern, $code, $template);
		});


		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('colorStrBlue'); // colors a string to blue if second argument  > 0
		
		$code = '
		$1<?php 
		
		$stringChgArgs = array$2; 
		$tcolor = ($stringChgArgs[1] > 0) ? \'color:blue\' : \'\';
		echo  "<p style=" . $tcolor . ">" . nl2br($stringChgArgs[0]) . "</p>"; ?>';
	
		return preg_replace($pattern, $code, $template);
		});



		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('colorLabelBlue'); // colors a string to blue if second argument  > 0
		
		$code = '
		$1<?php 
		
		$stringChgArgs = array$2; 
		
		$class = ($stringChgArgs[1] > 0) ? \'label label-info\' : \'label \';
		echo  "<span class=\"$class\">" . $stringChgArgs[0] . "</span>"; ?>';
		
		return preg_replace($pattern, $code, $template);
		});

		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('colorLabelRed'); // colors a string to blue if second argument  > 0
		
		$code = '
		$1<?php 
		
		$stringChgArgs = array$2; 
		
		$class = ($stringChgArgs[1] > 0) ? \'label label-important\' : \'label \';
		echo  "<span class=\"$class\">" . $stringChgArgs[0] . "</span>"; ?>';
		
		return preg_replace($pattern, $code, $template);
		});


		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('colorLabelGreenOrange'); // colors a string green if second argument  < 0 / orange if second argument > 0 
		
		$code = '
		$1<?php 
		
		$stringChgArgs = array$2; 
		
		$class = \'label \';
		if ( $stringChgArgs[1] < 0 ){
			$class = \'label label-success\'; 			
		}
		else if( $stringChgArgs[1] > 0 ){
			$class = \'label label-warning\'; 
		}
		echo  "<span class=\"$class\">" . $stringChgArgs[0] . "</span>"; ?>';

		return preg_replace($pattern, $code, $template);
		});


		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('dateIntervalWColorLabelGreenOrange'); // colors a string green if second argument  < 0 / orange if second argument > 0 
		
		$code = '
		$1<?php 
		
		$stringChgArgs = array$2; 
		
		$class = \'label \';
		if ( $stringChgArgs[1] < 0 ){
			$class = \'label label-success\'; 			
		}
		else if( $stringChgArgs[1] > 0 ){
			$class = \'label label-warning\'; 
		}
		
		
		$interval = new DateInterval($stringChgArgs[0]);
		$durationString = $interval->format(\'%y years, %m months, %d days\');
		$durationString = str_replace(array(\'0 years,\', \' 0 months,\', \' 0 days,\'), \'\', $durationString);
		$durationString = str_replace(array(\'1 years, \', \' 1 months, \', \' 1 days, \'), array(\'1 year, \', \'1 month, \', \'1 day, \'), $durationString);
		echo  "<span class=\"$class\">" . $durationString . "</span>"; ?>';

		return preg_replace($pattern, $code, $template);
		});





		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('colorDateGreenOrangeRed'); 
		// colors a string green if second argument  < 0 / orange if second argument > 0 / red if date has passed but third argument is still 0
		
		$code = '
		$1<?php 
		
		$dateArgs = array$2; 

		$dateMatcherJDate = new \JDate($dateArgs[0]);

		$classIco = \'icon icon-checkbox-unchecked\';
		if ( $dateArgs[2] > 0 ){
			$classIco = \'icon icon-checkbox\';			
		}

		$class = \'label \';
		if ( $dateArgs[2] == 0 &&  $dateMatcherJDate->toUNIX() < time() ) {
			$class = \'label label-important\'; 						
		}
		else{
			if ( $dateArgs[1] < 0 ){
				$class = \'label label-success\'; 			
			}
			else if( $dateArgs[1] > 0 ){
				$class = \'label label-warning\'; 
			}
		}

		$dateFormat = \'DATE_FORMAT_LC3\';
		
		echo  "<span style=\"white-space:nowrap;\"/><i style=\"vertical-align:-2px;\" class=\"$classIco\"/></i><span class=\"$class\">" . $dateMatcherJDate->format(\JText::_($dateFormat)) . "</span></span>"; ?>';
		return preg_replace($pattern, $code, $template);
		});
		
		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('colorDateGreenOrangeRedWoCheckBox'); 
		// colors a string green if second argument  < 0 / orange if second argument > 0 / red if date has passed 
		
		$code = '
		$1<?php 
		
		$dateArgs = array$2; 

		$dateMatcherJDate = new \JDate($dateArgs[0]);

		$class = \'label \';
		if ( $dateArgs[2] == 0 &&  $dateMatcherJDate->toUNIX() < time() ) {
			$class = \'label label-important\'; 						
		}
		else{
			if ( $dateArgs[1] < 0 ){
				$class = \'label label-success\'; 			
			}
			else if( $dateArgs[1] > 0 ){
				$class = \'label label-warning\'; 
			}
		}

		$dateFormat = \'DATE_FORMAT_LC3\';
		
		echo  "<span style=\"white-space:nowrap;\"/></i><span class=\"$class\">" . $dateMatcherJDate->format(\JText::_($dateFormat)) . "</span></span>"; ?>';
		return preg_replace($pattern, $code, $template);
		});
		
		

		$container->blade->extend(function($template, Blade $compiler)
		{
		$pattern = $compiler->createMatcher('checkMark'); 
		// shows a checkmark icon if first argument  > 0 
		
		$code = '
		$1<?php 
		
		$chkArgs = array$2; 
		
		$class = \'label \';
		if ( $chkArgs[0] == 0 ) {
			$class = \'icon icon-checkbox-unchecked\'; 						
		}
		else{
			$class = \'icon icon-checkbox\'; 
		}
		echo  "<span class=\"$class\">" . "</span>"; ?>';
		return preg_replace($pattern, $code, $template);
		});

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