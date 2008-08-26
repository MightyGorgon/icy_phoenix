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

if(!function_exists(imp_top_posters_func))
{
	function imp_top_posters_func()
	{
		global $lang, $template, $cms_config_vars, $block_id, $board_config, $db, $phpEx;

		$show_admin = true;
		$show_mod = true;
		if (($show_admin == true) && ($show_mod == true))
		{
			$sql_level = "";
		}
		elseif ($show_admin == true)
		{
			$sql_level = "AND u.user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
		}
		elseif ($show_mod == true)
		{
			$sql_level = "AND u.user_level IN (" . USER . ", " . MOD . ")";
		}
		else
		{
			$sql_level = "AND u.user_level = " . USER;
		}
		$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_level
			FROM " . USERS_TABLE . " u
			WHERE (u.user_id <> " . ANONYMOUS . ")
			" . $sql_level . "
			ORDER BY u.user_posts DESC
			LIMIT " . $cms_config_vars['md_total_poster'][$block_id];
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				$username2 = $row['username'];
				$row['username'] = colorize_username($row['user_id']);
				$username = $row['username'];
				$user_id = $row['user_id'];
				$posts = ($row['user_posts']) ? $row['user_posts'] : 0;
				$poster_avatar = user_get_avatar($row['user_id'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

				$template->assign_block_vars('topposter', array(
					'USERNAME' => $username,
					'POSTS' => $posts,
					'AVATAR_IMG' => $poster_avatar,
					'U_VIEWPOSTER' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
					'U_VIEWPOSTS' => append_sid(SEARCH_MG . '?search_author=' . urlencode(utf8_decode($username2)) . '&amp;showresults=posts')
					//'U_VIEWPOSTS' => append_sid(SEARCH_MG . '?search_author=' . htmlspecialchars($username2) . '&amp;showresults=posts')
					)
				);
				$i++;
			}
			while ($row = $db->sql_fetchrow($result));
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'L_POSTS' => $lang['Posts']
			)
		);
	}
}

imp_top_posters_func();
?>