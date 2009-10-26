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

if(!function_exists('cms_block_top_posters'))
{
	function cms_block_top_posters()
	{
		global $db, $cache, $config, $template, $lang, $block_id, $cms_config_vars;

		include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);

		$top_posters_n = (intval($cms_config_vars['md_total_poster'][$block_id]) ? $cms_config_vars['md_total_poster'][$block_id] : 10);
		$show_admins = true;
		$show_mods = true;
		$top_posters_array = top_posters($top_posters_n, $show_admins, $show_mods, true);

		$show_avatars = ($cms_config_vars['md_show_avatars'][$block_id] == true) ? true : false;
		$template->assign_var('S_SHOW_AVATARS', $show_avatars);

		for ($i = 0; $i < sizeof($top_posters_array); $i++)
		{
			$username2 = $top_posters_array[$i]['username'];
			$username = colorize_username($top_posters_array[$i]['user_id'], $top_posters_array[$i]['username'], $top_posters_array[$i]['user_color'], $top_posters_array[$i]['user_active']);
			$user_id = $top_posters_array[$i]['user_id'];
			$posts = ($top_posters_array[$i]['user_posts']) ? $top_posters_array[$i]['user_posts'] : 0;
			$poster_avatar = user_get_avatar($top_posters_array[$i]['user_id'], $top_posters_array[$i]['user_level'], $top_posters_array[$i]['user_avatar'], $top_posters_array[$i]['user_avatar_type'], $top_posters_array[$i]['user_allowavatar']);

			$template->assign_block_vars('topposter', array(
				'USERNAME' => $username,
				'POSTS' => $posts,
				'AVATAR_IMG' => $poster_avatar,
				'U_VIEWPOSTER' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
				'U_VIEWPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode(ip_utf8_decode($username2)) . '&amp;showresults=posts')
				//'U_VIEWPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . htmlspecialchars($username2) . '&amp;showresults=posts')
				)
			);
		}
	}
}

cms_block_top_posters();

?>