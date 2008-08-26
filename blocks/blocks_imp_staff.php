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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_staff_func))
{
	function imp_staff_func()
	{
		global $template, $lang, $db, $theme, $phpEx, $lang, $board_config, $userdata, $phpbb_root_path, $table_prefix, $var_cache, $images;

		include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

		$sql = "SELECT * FROM " . USERS_TABLE."
			WHERE user_level <> 0
			ORDER BY user_level";
		if (!($results = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get staff information', '', __LINE__, __FILE__, $sql);
		}
		while($db_select = $db->sql_fetchrow($results))
		{
			if (($db_select['user_level'] == JUNIOR_ADMIN) || ($db_select['user_level'] == ADMIN))
			{
				$user_level = $lang['Memberlist_Administrator'];
			}
			else if ($db_select['user_level'] == MOD)
			{
				$user_level = $lang['Memberlist_Moderator'];
			}

			$u_name = colorize_username($db_select[user_id]);
			$l_name = $db_select[username];

			$template->assign_block_vars('staff', array(
				'USER_LEVEL' => $user_level,
				'L_NAME' => $l_name,
				'U_NAME' => $u_name
				)
			);
		}

		$template->assign_vars(array(
			'STAFF_NAME' => $lang['Staff'],
			'STAFF_ADMIN' => $lang['Memberlist_Administrator'],
			'STAFF_MOD' => $lang['Memberlist_Moderator'],
			)
		);
	}
}

imp_staff_func();

?>