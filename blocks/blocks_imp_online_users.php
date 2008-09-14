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

if(!function_exists(imp_online_users_block_func))
{
	function imp_online_users_block_func()
	{
		global $template, $lang, $db, $theme, $lang, $board_config, $userdata, $table_prefix, $var_cache;

		include(IP_ROOT_PATH . 'includes/users_online_block.' . PHP_EXT);

		$template->assign_vars(array(
			'B_L_VIEW' => $lang['View_complete_list'],
			'B_TOTAL_USERS_ONLINE' => $l_online_users,
			'B_LOGGED_IN_USER_LIST' => $online_userlist,
			'B_U_VIEWONLINE' => append_sid('viewonline.' . PHP_EXT),
			'B_RECORD_USERS' => sprintf($lang['Record_online_users'], $board_config['record_online_users'], create_date($board_config['default_dateformat'], $board_config['record_online_date'], $board_config['board_timezone']))
			)
		);
	}
}

imp_online_users_block_func();

?>