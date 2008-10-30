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

if(!function_exists('imp_news_posters_func'))
{
	function imp_news_posters_func()
	{
		global $lang, $images, $template, $cms_config_vars, $block_id, $board_config, $db;

		$template->_tpldata['news_poster.'] = array();

		$page_link = htmlspecialchars($cms_config_vars['md_news_posters_page_link'][$block_id]);
		//0 = alphabetical || 1 = News
		$list_sort = ($cms_config_vars['md_news_posters_sort'][$block_id] == 1) ? 1 : 0;
		$show_avatars = ($cms_config_vars['md_news_posters_avatar'][$block_id] == 1) ? 1 : 0;

		if ($list_sort == 1)
		{
			$sort_sql = "ORDER BY num_topics DESC";
		}
		else
		{
			$sort_sql = "ORDER BY u.username ASC";
		}

		$tpl_block_var_name = 'news_poster';
		if ($show_avatars == 1)
		{
			$tpl_block_var_name .= '_av';
		}

		$sql = "SELECT t.topic_poster, COUNT(t.topic_poster) num_topics,
							u.user_id, u.username, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_posts,
							u.user_from, u.user_from_flag, u.user_regdate, u.user_gender,
							u.user_website, u.user_icq, u.user_aim, u.user_msnm, u.user_yim, u.user_skype
						FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
						WHERE t.news_id > 0
							AND t.topic_status <> '" . TOPIC_MOVED . "'
							AND u.user_id = t.topic_poster
						GROUP BY t.topic_poster
						" . $sort_sql;
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query topics table', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$username_clean = $row['username'];
			$username = colorize_username($row['user_id']);
			$user_id = $row['user_id'];
			$posts = ($row['user_posts']) ? $row['user_posts'] : 0;
			$poster_avatar = user_get_avatar($row['user_id'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

			$poster_from = ($row['user_from']) ? ($lang['Location'] . ': ' . $row['user_from']) : ($lang['Location'] . ': ???');
			$poster_from_flag = ($row['user_from_flag']) ? ('&nbsp;<img src="images/flags/' . $row['user_from_flag'] . '" alt="' . $row['user_from_flag'] . '" title="' . $row['user_from'] . '" />') : '';
			$poster_joined = $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']);

			$temp_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $poster_id);
			$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
			$pm = '<a href="' . $temp_url . '">' . $lang['PM'] . '</a>';

			switch ($row['user_gender'])
			{
				case 1:
					$gender_image = '&nbsp;<img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'].  ': ' . $lang['Male'] . '" title="' . $lang['Gender'] . ': ' . $lang['Male'] . '" />';
					break;
				case 2:
					$gender_image = '&nbsp;<img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender']. ': ' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" />';
					break;
				default:
					$gender_image = '';
			}

			$www_img = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
			$www = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Website'] . '</a>' : '';

			$icq_status_img = (!empty($row['user_icq'])) ? '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&img=5" width="18" height="18" /></a>' : '';
			$icq_img = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], $images['icon_icq2']) : '';
			$icq = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], false) : '';

			$aim_img = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], $images['icon_aim2']) : '';
			$aim = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], false) : '';

			$msn_img = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], $images['icon_msnm2']) : '';
			$msn = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], false) : '';

			$yim_img = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], $images['icon_yim2']) : '';
			$yim = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], false) : '';

			$skype_img = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], $images['icon_skype2']) : '';
			$skype = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], false) : '';

			$template->assign_block_vars($tpl_block_var_name, array(
				'USERNAME' => $username . $gender_image,
				'POSTS' => $posts,
				'NEWS' => $row['num_topics'],
				'AVATAR_IMG' => $poster_avatar,
				'POSTER_FROM' => $poster_from . $poster_from_flag,
				'POSTER_JOINED' => $poster_joined,
				'CONTACTS' => $pm_img . $www_img . $icq_img . $aim_img . $msn_img . $yim_img . $skype_img,
				'U_VIEWPOSTER' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
				'U_VIEWNEWS' => append_sid($page_link . ((strpos($page_link, '?') === false) ? '?' : '&amp;') . 'ubid=' . $user_id),
				'U_VIEWTOPICS' => append_sid(SEARCH_MG . '?search_author=' . urlencode(utf8_decode($username_clean)) . '&amp;search_topic_starter=1&amp;show_results=topics'),
				'U_VIEWPOSTS' => append_sid(SEARCH_MG . '?search_author=' . urlencode(utf8_decode($username_clean)) . '&amp;showresults=posts')
				)
			);
		}
		$db->sql_freeresult($result);
	}
}

imp_news_posters_func();

?>