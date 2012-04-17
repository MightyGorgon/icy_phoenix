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

if(!function_exists('cms_block_news_posters'))
{
	function cms_block_news_posters()
	{
		global $db, $cache, $config, $template, $images, $lang, $block_id, $cms_config_vars;

		include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);

		$template->_tpldata['news_poster.'] = array();

		$page_link = htmlspecialchars($cms_config_vars['md_news_posters_page_link'][$block_id]);
		//0 = alphabetical || 1 = News
		$list_sort = request_var('list_sort', '');
		$list_sort = (($list_sort == POST_TOPIC_URL) ? 1 : (($list_sort == POST_USERS_URL) ? 0 : false));
		$list_sort = ($list_sort === false) ? (($cms_config_vars['md_news_posters_sort'][$block_id] == 1) ? 1 : 0) : $list_sort;
		$show_avatars = ($cms_config_vars['md_news_posters_avatar'][$block_id] == 1) ? 1 : 0;

		$start = request_var('start', 0);
		$start = ($start < 0) ? 0 : $start;
		$per_page = request_var('per_page', $config['topics_per_page']);
		$per_page = ($per_page < 0) ? $config['topics_per_page'] : $per_page;

		$quick_list = request_var('quick_list', '');

		$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');

		$portal_page_id = request_var('page', 0);
		$portal_page_id = !empty($portal_page_id) ? ('&amp;page=' . $portal_page_id) : '';

		$base_url = htmlspecialchars(urldecode($index_file));
		$base_url .= '?list_sort=' . (($list_sort == 1) ? POST_TOPIC_URL : POST_USERS_URL);
		$base_url .= '&amp;per_page=' . $per_page;
		$base_url .= $portal_page_id;
		$sort_sql = "ORDER BY " . (($list_sort == 1) ? "num_topics DESC" : "u.username ASC");

		$template->assign_vars(array(
			'S_QUICK_LIST' => ($quick_list == 'true') ? true : false,
			'U_QUICK_LIST' => $base_url . '&amp;quick_list=true',
			'U_NORMAL_LIST' => $base_url,

			'L_NEWS_POSTERS' => $lang['Title_news_posters'],
			'L_USER_PROFILE' => $lang['Profile'],
			'L_PM' => $lang['Private_Message'],
			'L_USER_WWW' => $lang['Website'],
			)
		);

		$tpl_block_var_name = 'news_poster' . (($show_avatars == 1) ? '_av' : '');

		if ($quick_list == 'true')
		{
			$sql = "SELECT t.topic_poster, COUNT(t.topic_poster) num_topics,
								u.user_id, u.username, u.user_active, u.user_color
							FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
							WHERE t.news_id > 0
								AND t.topic_status <> '" . TOPIC_MOVED . "'
								AND u.user_id = t.topic_poster
							GROUP BY t.topic_poster
							" . $sort_sql;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$username_clean = $row['username'];
				$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
				$user_id = $row['user_id'];
				$posts = ($row['user_posts']) ? $row['user_posts'] : 0;

				$template->assign_block_vars('news_poster', array(
					'USERNAME' => $username . $gender_image,
					'NEWS' => $row['num_topics'],
					'U_VIEWNEWS' => append_sid($page_link . ((strpos($page_link, '?') === false) ? '?' : '&amp;') . 'ubid=' . $user_id),
					)
				);
			}
			$db->sql_freeresult($result);
		}
		else
		{

			$sql = "SELECT t.topic_poster, COUNT(t.topic_poster) num_topics,
								u.user_id, u.username, u.user_active, u.user_color, u.user_level, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_posts,
								u.user_from, u.user_from_flag, u.user_regdate, u.user_gender,
								u.user_website, u.user_icq, u.user_aim, u.user_msnm, u.user_yim, u.user_skype
							FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
							WHERE t.news_id > 0
								AND t.topic_status <> '" . TOPIC_MOVED . "'
								AND u.user_id = t.topic_poster
							GROUP BY t.topic_poster
							" . $sort_sql . "
							LIMIT " . $start . ", " . $per_page;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$username_clean = $row['username'];
				$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
				$user_id = $row['user_id'];
				$posts = ($row['user_posts']) ? $row['user_posts'] : 0;
				$poster_avatar = user_get_avatar($row['user_id'], $row['user_level'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

				$poster_from = ($row['user_from']) ? ($lang['Location'] . ': ' . $row['user_from']) : ($lang['Location'] . ': ???');
				$poster_from_flag = ($row['user_from_flag']) ? ('&nbsp;<img src="images/flags/' . $row['user_from_flag'] . '" alt="' . $row['user_from_flag'] . '" title="' . $row['user_from'] . '" />') : '';
				$poster_joined = $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $config['board_timezone']);

				$temp_url = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $poster_id);
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

				$user_info = array();
				$user_info = generate_user_info($row);
				foreach ($user_info as $k => $v)
				{
					$$k = $v;
				}

				$template->assign_block_vars($tpl_block_var_name, array(
					'USERNAME' => $username . $gender_image,
					'POSTS' => $posts,
					'NEWS' => $row['num_topics'],
					'AVATAR_IMG' => $poster_avatar,
					'POSTER_FROM' => $poster_from . $poster_from_flag,
					'POSTER_JOINED' => $poster_joined,
					'CONTACTS' => $pm_img . $www_img . $icq_img . $aim_img . $msn_img . $yahoo_img . $skype_img,

					'PROFILE_IMG' => $profile_img,
					'PROFILE' => $profile,
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'WWW_IMG' => $www_img,
					'WWW' => $www,
					'AIM_IMG' => $aim_img,
					'AIM' => $aim,
					'ICQ_STATUS_IMG' => $icq_status_img,
					'ICQ_IMG' => $icq_img,
					'ICQ' => $icq,
					'MSN_IMG' => $msn_img,
					'MSN' => $msn,
					'SKYPE_IMG' => $skype_img,
					'SKYPE' => $skype,
					'YIM_IMG' => $yahoo_img,
					'YIM' => $yahoo,

					'U_PROFILE' => $profile_url,
					'U_PM' => $pm_url,
					'U_WWW' => $www_url,
					'U_AIM' => $aim_url,
					'U_ICQ' => $icq_url,
					'U_MSN' => $msn_url,
					'U_SKYPE' => $skype_url,
					'U_YIM' => $yahoo_url,

					'U_VIEWPOSTER' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
					'U_VIEWNEWS' => append_sid($page_link . ((strpos($page_link, '?') === false) ? '?' : '&amp;') . 'ubid=' . $user_id),
					'U_VIEWTOPICS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode(ip_utf8_decode($username_clean)) . '&amp;search_topic_starter=1&amp;show_results=topics'),
					'U_VIEWPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode(ip_utf8_decode($username_clean)) . '&amp;showresults=posts')
					)
				);
			}
			$db->sql_freeresult($result);

			$sql = "SELECT COUNT(DISTINCT t.topic_poster) as news_posters
							FROM " . TOPICS_TABLE . " t
							WHERE t.news_id > 0
								AND t.topic_status <> '" . TOPIC_MOVED . "'";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$total_news_posters = $row['news_posters'];
			$db->sql_freeresult($result);

			$number_of_page = (ceil($total_news_posters / $per_page) == 0) ? 1 : ceil($total_news_posters / $per_page);
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination($base_url, $total_news_posters, $per_page, $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $per_page) + 1), $number_of_page),
				'L_GOTO_PAGE' => $lang['Goto_page']
				)
			);
		}
	}
}

cms_block_news_posters();

?>
