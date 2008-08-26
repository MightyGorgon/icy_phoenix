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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_user_block_block_func))
{
	function imp_user_block_block_func()
	{
		global $userdata, $template, $board_config, $lang, $db, $phpEx, $phpbb_root_path;
		include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

		/*if($userdata['session_logged_in'])
		{
				$sql = "SELECT COUNT(post_id) as total
					FROM " . POSTS_TABLE . "
					WHERE post_time >= " . $userdata['user_lastvisit'] . " AND poster_id <> " . $userdata['user_id'] . " AND post_time < " . time();
					$result = $db->sql_query($sql);
			if($result)
			{
				$row = $db->sql_fetchrow($result);
				$lang['Search_new'] = $lang['Search_new'] . "&nbsp;(" . $row['total'] . ")";
			}
		}*/

		$avatar_img = user_get_avatar($userdata['user_id'], $userdata['user_avatar'], $userdata['user_avatar_type'], $userdata['user_allowavatar']);

		// Check For Anonymous User
		if ($userdata['user_id'] != ANONYMOUS)
		{
				$username = colorize_username($userdata['user_id']);
		}
		else
		{
			$username = $lang['Guest'];
			$avatar_img = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" />';
		}
		if ($userdata['user_id'] != '-1')
		{
			$userdata['username'] = colorize_username($userdata['user_id']);
			$name_link =  $userdata['username'];
		}
		else
		{
			$name_link = $lang['Guest'];
		}

		$template->assign_vars(array(
			'AVATAR_IMG' => $avatar_img,
			'U_NAME_LINK' => $name_link,
			'L_REMEMBER_ME' => $lang['Remember_me'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],
			'U_SEND_PASSWORD' => append_sid(PROFILE_MG . '?mode=sendpassword'),
			'L_REGISTER_NEW_ACCOUNT' => sprintf($lang['Register_new_account'], '<a href="' . append_sid(PROFILE_MG . '?mode=register') . '">', '</a>'),
			'L_NEW_SEARCH' => $lang['Search_new']
			)
		);
	}
}

imp_user_block_block_func();
?>