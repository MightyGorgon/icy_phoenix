<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}
//define('MINI_CAL_FLAG', true);

if(!function_exists('cms_block_calendar'))
{
	function cms_block_calendar()
	{
		global $db, $cache, $config, $template, $images, $user, $lang, $bbcode;
		global $mini_cal_today, $mini_cal_this_month, $mini_cal_this_year, $mini_cal_this_day;

		$birthdays_list = array();
		@include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
		$birthdays_list = get_birthdays_list_full();

		include(IP_ROOT_PATH . 'includes/mini_cal/mini_cal.' . PHP_EXT);
	}
}

cms_block_calendar();

?>