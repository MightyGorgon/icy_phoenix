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

if(!function_exists('cms_block_staff'))
{
	function cms_block_staff()
	{
		global $db, $cache, $config, $template, $theme, $images, $user, $lang, $table_prefix;

		$sql = "SELECT * FROM " . USERS_TABLE."
			WHERE user_level <> 0
			ORDER BY user_level";
		$result = $db->sql_query($sql);

		while($db_select = $db->sql_fetchrow($result))
		{
			if (($db_select['user_level'] == JUNIOR_ADMIN) || ($db_select['user_level'] == ADMIN))
			{
				$user_level = $lang['Memberlist_Administrator'];
			}
			elseif ($db_select['user_level'] == MOD)
			{
				$user_level = $lang['Memberlist_Moderator'];
			}

			$u_name = colorize_username($db_select['user_id'], $db_select['username'], $db_select['user_color'], $db_select['user_active']);
			$l_name = $db_select[username];

			$template->assign_block_vars('staff', array(
				'USER_LEVEL' => $user_level,
				'L_NAME' => $l_name,
				'U_NAME' => $u_name
				)
			);
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'STAFF_NAME' => $lang['Staff'],
			'STAFF_ADMIN' => $lang['Memberlist_Administrator'],
			'STAFF_MOD' => $lang['Memberlist_Moderator'],
			)
		);
	}
}

cms_block_staff();

?>