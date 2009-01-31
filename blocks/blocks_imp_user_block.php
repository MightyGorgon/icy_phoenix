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

if(!function_exists('imp_user_block_block_func'))
{
	function imp_user_block_block_func()
	{
		global $userdata, $template, $board_config, $lang, $db;

		/*
		if($userdata['session_logged_in'])
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

		// Check For Anonymous User
		if ($userdata['user_id'] != ANONYMOUS)
		{
			$name_link = colorize_username($userdata['user_id'], $userdata['username'], $userdata['user_color'], $userdata['user_active']);
			$avatar_img = user_get_avatar($userdata['user_id'], $userdata['user_level'], $userdata['user_avatar'], $userdata['user_avatar_type'], $userdata['user_allowavatar']);
		}
		else
		{
			$name_link = $lang['Guest'];
			$avatar_img = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" />';
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