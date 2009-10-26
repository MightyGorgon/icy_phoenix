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

if(!function_exists('cms_block_online_users'))
{
	function cms_block_online_users()
	{
		global $db, $cache, $config, $template, $theme, $images, $userdata, $lang, $table_prefix;

		include(IP_ROOT_PATH . 'includes/users_online_block.' . PHP_EXT);

		$template->assign_vars(array(
			'B_L_VIEW' => $lang['View_complete_list'],
			'B_TOTAL_USERS_ONLINE' => $l_online_users,
			'B_LOGGED_IN_USER_LIST' => $online_userlist,
			'B_U_VIEWONLINE' => append_sid('viewonline.' . PHP_EXT),
			'B_RECORD_USERS' => sprintf($lang['Record_online_users'], $config['record_online_users'], create_date($config['default_dateformat'], $config['record_online_date'], $config['board_timezone']))
			)
		);
	}
}

cms_block_online_users();

?>