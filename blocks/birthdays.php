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
* Zuker
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_birthdays'))
{
	function cms_block_birthdays()
	{
		global $db, $cache, $config, $template, $images, $lang;

		$birthdays_list = array();
		@include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
		$birthdays_list = get_birthdays_list_full();

		$template->assign_vars(array(
			'BIRTHDAY_IMG' => $images['birthday_image'],
			'L_WHOSBIRTHDAY_WEEK' => ($config['birthday_check_day'] > 1) ? sprintf((($birthdays_list['xdays']) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $config['birthday_check_day']) . $birthdays_list['xdays'] : '',
			'L_WHOSBIRTHDAY_TODAY' => ($config['birthday_check_day']) ? ($birthdays_list['today']) ? $lang['Birthday_today'] . $birthdays_list['today'] : $lang['Nobirthday_today'] : ''
			)
		);
	}
}

cms_block_birthdays();

?>