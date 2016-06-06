<?php
/**
 * @package   HSH RnD Review
 * @copyright Copyright (c)2016 Jens Kowal / me.com
 * @license   GNU General Public License version 3 or later
 */
defined('_JEXEC') or die();
// Load FOF
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('FOF 3.0 is not installed', 500);
}
$container = FOF30\Container\Container::getInstance('com_hshrndreview')->dispatcher->dispatch();
