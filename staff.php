<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$is_auth_ary = array();
$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $user->data, $forum_data);

$sql_forums = "SELECT ug.user_id, f.forum_id, f.forum_name
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . FORUMS_TABLE . " f
		WHERE aa.auth_mod = " . TRUE . "
			AND ug.group_id = aa.group_id
			AND f.forum_id = aa.forum_id";
$result_forums = $db->sql_query($sql_forums, 0, 'staff_');

while($row = $db->sql_fetchrow($result_forums))
{
	$display_forums = ($is_auth_ary[$row['forum_id']]['auth_view']) ? true : false;
	if($display_forums)
	{
		$forum_id = $row['forum_id'];
		$staff2[$row['user_id']][$row['forum_id']] = '<a href="'. append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '" class="genmed">'. $row['forum_name'] .'</a><br />';
	}
}

$ranks_array = $cache->obtain_ranks(false);

$level_cat = $lang['Staff_level'];
for($i = 0; $i < sizeof($level_cat); $i++)
{
	$user_level = $level_cat[$i];
	$template->assign_block_vars('user_level', array(
		'USER_LEVEL' => $user_level,
		)
	);

	if($level_cat['0'])
	{
		$where = 'user_level IN (' . JUNIOR_ADMIN . ', ' . ADMIN . ')';
	}
	elseif($level_cat['1'])
	{
		$where = 'user_level = '. MOD;
	}
	$level_cat[$i] = '';

	$sql_user = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastlogon, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang
		FROM " . USERS_TABLE . " u
		WHERE $where";
	$result_user = $db->sql_query($sql_user);

	while($staff = $db->sql_fetchrow($result_user))
	{
		$k = 0;
		$row_class = (!($k % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$user_id = $staff['user_id'];

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_ranks = generate_ranks($staff, $ranks_array);
		if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html']  == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
		{
			$user_ranks['rank_01_html'] = '&nbsp;';
		}
		// Mighty Gorgon - Multiple Ranks - END

		$rank = $user_ranks['rank_01_html'];
		$rank_image = $user_ranks['rank_01_img_html'];

		$avatar = user_get_avatar($staff['user_id'], $staff['user_level'], $staff['user_avatar'], $staff['user_avatar_type'], $staff['user_allowavatar']);

		$forums = '';
		if(!empty($staff2[$staff['user_id']]))
		{
			asort($staff2[$staff['user_id']]);
			$forums = implode(' ', $staff2[$staff['user_id']]);
		}

		/*
		// Mighty Gorgon: OLD SQL REMOVED - BEGIN
		$sql_posts = "SELECT DISTINCT p.post_time, p.post_id, count(DISTINCT t.topic_id) AS user_topics
				FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
				WHERE p.poster_id = '$user_id' AND t.topic_poster = '$user_id'
				GROUP BY p.post_time
				ORDER BY p.post_time DESC LIMIT 1";
		// Mighty Gorgon: OLD SQL REMOVED - END
		*/
		$sql_posts = "SELECT count(DISTINCT t.topic_id) AS user_topics
				FROM " . TOPICS_TABLE . " t
				WHERE t.topic_poster = '$user_id'
				LIMIT 1";
		$results_posts = $db->sql_query($sql_posts);
		$row = $db->sql_fetchrow($results_posts);
		//$last_post = (isset($row['post_time'])) ? '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?'. POST_POST_URL . '=' . $row[post_id] . '#p' . $row[post_id]) . '"  style="text-decoration:none">' . create_date_ip($config['default_dateformat'], $row['post_time'], $config['board_timezone']) .'</a>' : $lang['None'];
		$user_topics = $row['user_topics'];

		$memberdays = max(1, round((time() - $staff['user_regdate']) / 86400));
		$posts_per_day = $staff['user_posts'] / $memberdays;
		$topics_per_day = $user_topics / $memberdays;
		if($staff['user_posts'] != '0')
		{
			$total_topics = $config['max_topics'];
			$total_posts = $config['max_posts'];
			$post_percent = ($total_posts) ? min(100, ($staff['user_posts'] / $total_posts) * 100) : 0;
			$topic_percent = ($total_topics) ? min(100, ($user_topics / $total_topics) * 100) : 0;
		}
		else
		{
			$post_percent = 0;
			$topic_percent = 0;
		}

		$pmto = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $staff[user_id]);
		$pm = '<a href="' . $pmto . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
		$mailto = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL .'=' . $staff['user_id']) : 'mailto:' . $staff['user_email'];
		$mail = ($staff['user_email']) ? '<a href="' . $mailto . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>' : '';

		$user_info = array();
		$user_info = generate_user_info($staff);
		foreach ($user_info as $k => $v)
		{
			$$k = $v;
		}

		$template->assign_block_vars('user_level.staff', array(
			'ROW_CLASS' => $row_class,
			'USERNAME' => colorize_username($staff['user_id'], $staff['username'], $staff['user_color'], $staff['user_active']),
			'RANK' => $rank,
			'RANK_IMAGE' => $rank_image,
			'AVATAR' => $avatar,
			'FORUMS' => $forums,
			'POSTS' => $staff['user_posts'],
			'POST_PERCENT' => sprintf($lang['User_post_pct_stats'], $post_percent),
			'POSTS_PER_DAY' => sprintf($lang['User_post_day_stats'], $posts_per_day),
			'TOPICS' => $user_topics,
			'TOPIC_PERCENT' => sprintf($lang['User_post_pct_stats'], $topic_percent),
			'TOPICS_PER_DAY' => sprintf($lang['Staff_user_topic_day_stats'], $topics_per_day),
			'LAST_POST' => $last_post,
			'JOINED' => create_date($lang['JOINED_DATE_FORMAT'], $staff['user_regdate'], $config['board_timezone']),
			'PERIOD' => sprintf($lang['Staff_period'], $memberdays),

			'PROFILE_IMG' => $profile_img,
			'PROFILE' => $profile,
			'SEARCH_IMG' => $search_img,
			'SEARCH' => $search,
			'PM_IMG' => $pm_img,
			'PM' => $pm,
			'EMAIL_IMG' => (!$user->data['session_logged_in']) ? '' : $email_img,
			'EMAIL' => $email,
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
			'POSTER_GENDER' => $gender_image,
			'ONLINE_STATUS_IMG' => $online_status_img,

			'U_PROFILE' => $profile_url,
			'U_PM' => $pm_url,
			'U_EMAIL' => $email_url,
			'U_WWW' => $www_url,
			'U_AIM' => $aim_url,
			'U_ICQ' => $icq_url,
			'U_MSN' => $msn_url,
			'U_SKYPE' => $skype_url,
			'U_YIM' => $yahoo_url,
			'L_POSTER_ONLINE_STATUS' => $online_status_lang,
			'POSTER_ONLINE_STATUS_CLASS' => $online_status_class,
			'U_POSTER_ONLINE_STATUS' => $online_status_url,
			)
		);
		$k++;
	}
}

$template->assign_vars(array(
	'L_USERNAME' => $lang['Username'],
	'L_FORUMS' => $lang['Staff_forums'],
	'L_STATS' => $lang['Staff_stats'],
	'L_POSTS' => $lang['Posts'],
	'L_TOPICS' => $lang['Topics'],
	'L_LAST_POST' => $lang['Last_Post'],
	'L_JOINED' => $lang['Joined'],
	'L_CONTACT' => $lang['Staff_contact'],
	'L_MESSENGER' => $lang['Staff_messenger'],
	'L_WWW' => $lang['Website'],
	)
);

full_page_generation('staff_body.tpl', $lang['Staff'], '', '');

?>