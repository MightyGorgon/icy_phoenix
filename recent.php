<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

// ############         Edit below         ########################################
$topic_length = '60'; // length of topic title
$topic_limit = $config['topics_per_page'];
$special_forums = '0'; // specify forums ('0' = no; '1' = yes)
$forum_ids = ''; // IDs of forums; separate them with a comma
$set_mode = 'last24'; // set default mode ('today', 'yesterday', 'last24', 'lastweek', 'lastXdays')
$set_days = '7'; // set default days (used for lastXdays mode)
// ############         Edit above         ########################################

// UPI2DB - BEGIN
if($user->data['upi2db_access'])
{
	if (!defined('UPI2DB_UNREAD'))
	{
		$user->data['upi2db_unread'] = upi2db_unread();
	}
	$count_new_posts = sizeof($user->data['upi2db_unread']['new_posts']);
	$count_edit_posts = sizeof($user->data['upi2db_unread']['edit_posts']);
	$count_always_read = sizeof($user->data['upi2db_unread']['always_read']['topics']);
	$count_mark_unread = sizeof($user->data['upi2db_unread']['mark_posts']);
}
// UPI2DB - END

$cms_page['page_id'] = 'recent';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$mode_types = array('today', 'yesterday', 'last24', 'lastweek', 'lastXdays', 'utopics', 'uposts');
if ($user->data['user_level'] == ADMIN)
{
	$mode_types = array_merge($mode_types, array('utview'));
}

$mode = request_var('mode', $set_mode);
$mode = check_var_value($mode, $mode_types, $set_mode);

$amount_days = request_var('amount_days', 0);
$amount_days = ($amount_days <= 0) ? $set_days : $amount_days;

$user_id = request_var(POST_USERS_URL, 0);
if(!empty($user_id))
{
	$user_id = ($user_id < 2) ? false : $user_id;

	if (!empty($user_id))
	{
		$target_userdata = get_userdata($user_id);
		if (empty($target_userdata))
		{
			$mode = $set_mode;
		}
		else
		{
			$username = htmlspecialchars($target_userdata['username']);
		}
	}
	else
	{
		$mode = $set_mode;
	}
}

$psort_types = array('time', 'cat');
$psort = request_var('psort', $psort_types[0]);
$psort = check_var_value($psort, $psort_types);

$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('recent.' . PHP_EXT) . '" class="nav-current">' . $lang['Recent_topics'] . '</a>';

$except_forums = build_exclusion_forums_list();

$where_forums = ($special_forums == '0') ? 't.forum_id NOT IN (' . $except_forums . ')' : 't.forum_id NOT IN (' . $except_forums . ') AND t.forum_id IN (' . $forum_ids . ')';
$sql_sort = ' ORDER BY t.topic_last_post_id DESC ';
if ($psort == 'cat')
{
	$sql_sort = ' ORDER BY f.forum_id ASC, t.topic_last_post_id DESC ';
}

$extra_tables = '';
$extra_fields = '';
if ($mode == 'utview')
{
	$extra_fields = ", tv.view_time, tv.view_count";
	$extra_tables = ", " . TOPIC_VIEW_TABLE . " tv";
}

$sql_start = "SELECT DISTINCT(t.topic_id), t.*, p.poster_id, p.post_username AS last_poster_name, p.post_id, p.post_time, f.forum_name, f.forum_id, u.username AS last_poster, u.user_id AS last_poster_id, u.user_active AS last_poster_active, u.user_mask AS last_poster_mask, u.user_color AS last_poster_color, u2.username AS first_poster, u2.user_id AS first_poster_id, u2.user_active AS first_poster_active, u2.user_mask AS first_poster_mask, u2.user_color AS first_poster_color, p2.post_username AS first_poster_name" . $extra_fields . "
		FROM (" . TOPICS_TABLE . " t, " . POSTS_TABLE . " p" . $extra_tables . ")
			LEFT OUTER JOIN " . POSTS_TABLE . " p2 ON (p2.post_id = t.topic_first_post_id)
			LEFT OUTER JOIN " . FORUMS_TABLE . " f ON (f.forum_id = p.forum_id)
			LEFT OUTER JOIN " . USERS_TABLE . " u ON (u.user_id = p.poster_id)
			LEFT OUTER JOIN " . USERS_TABLE . " u2 ON (u2.user_id = t.topic_poster)
		WHERE ";
$sql_where = $where_forums . " AND p.post_id = t.topic_last_post_id AND t.topic_status <> " . TOPIC_MOVED;
$sql_end = "LIMIT $start, $topic_limit";

if (!$user->data['session_logged_in'])
{
	$user->data['user_time_mode'] = $config['default_time_mode'];
	$user->data['user_timezone'] = $config['board_timezone'];
	$user->data['user_dst_time_lag'] = $config['default_dst_time_lag'];
}

$dst_sec = get_dst(time(), $user->data['user_timezone']);
$adj_time = (3600 * $user->data['user_timezone']) + $dst_sec;
$int_day_sec = intval((time() + $adj_time) / 86400) * 86400;

$mode_pagination = '&amp;amount_days=' . $amount_days;
$total_topics = 0;

switch($mode)
{
	case 'today':
		$sql_tmp = " AND (p.post_time + " . $adj_time . ") > " . $int_day_sec;
		$sql = $sql_start . $sql_where . $sql_tmp . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_today']));
		$where_count = $where_forums . $sql_tmp;
		$l_mode = $lang['Recent_title_today'];
		break;

	case 'yesterday':
		$sql_tmp = " AND (p.post_time + 86400 + " . $adj_time . ") > " . $int_day_sec . " AND (p.post_time + " . $adj_time . ") < " . $int_day_sec;
		$sql = $sql_start . $sql_where . $sql_tmp . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_yesterday']));
		$where_count = $where_forums . $sql_tmp;
		$l_mode = $lang['Recent_title_yesterday'];
		break;

	case 'last24':
		$sql = $sql_start . $sql_where . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400" . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_last24']));
		$where_count = $where_forums . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400";
		$l_mode = $lang['Recent_title_last24'];
		break;

	case 'lastweek':
		$sql = $sql_start . $sql_where . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 691200" . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_lastweek']));
		$where_count = $where_forums . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 691200";
		$l_mode = $lang['Recent_title_lastweek'];
		break;

	case 'lastXdays':
		$sql = $sql_start . $sql_where . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400 * " . $amount_days . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['Recent_lastXdays'], $amount_days)));
		$where_count = $where_forums . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400 * $amount_days";
		$l_mode = sprintf($lang['Recent_title_lastXdays'], $amount_days);
		break;

	case 'utopics':
		$sql = $sql_start . $sql_where . " AND t.topic_poster = " . $user_id . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['RECENT_USER_STARTED_NAV'], $username)));
		$where_count = $where_forums . " AND t.topic_poster = '" . $user_id . "'";
		$l_mode = sprintf($lang['RECENT_USER_STARTED_TITLE'], $username);
		$mode_pagination = '&amp;' . POST_USERS_URL . '=' . $user_id;
		break;

	case 'uposts':
		$sql = "SELECT topic_id, MAX(post_time) as ptime
			FROM " . POSTS_TABLE . "
			WHERE poster_id = '" . $user_id . "'
			GROUP BY topic_id
			ORDER BY ptime DESC";
		$result = $db->sql_query($sql);

		$search_ids = array();
		while($row = $db->sql_fetchrow($result))
		{
			$search_ids[] = $row['topic_id'];
		}
		$db->sql_freeresult($result);
		$sql_add = '';
		$total_topics = sizeof($search_ids);
		if ($total_topics > 0)
		{
			$sql_where = " t.topic_id IN (" . implode(',', $search_ids) . ") AND " . $sql_where;
		}
		$sql = $sql_start . $sql_where . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['RECENT_USER_POSTS_NAV'], $username)));
		$where_count = $where_forums . " AND p.poster_id = '" . $user_id . "'";
		$l_mode = sprintf($lang['RECENT_USER_POSTS_TITLE'], $username);
		$mode_pagination = '&amp;' . POST_USERS_URL . '=' . $user_id;
		break;

	case 'utview':
		$sql_sort = ' ORDER BY tv.view_time DESC ';
		if ($psort == 'cat')
		{
			$sql_sort = ' ORDER BY f.forum_id ASC, tv.view_time DESC ';
		}
		$sql_where = $sql_where . " AND tv.topic_id = t.topic_id AND tv.user_id = '" . $user_id . "' ";
		$sql = $sql_start . $sql_where . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['RECENT_USER_VIEWS_NAV'], $username)));
		$where_count = $where_forums . " AND tv.topic_id = t.topic_id AND tv.user_id = '" . $user_id . "'";
		$l_mode = sprintf($lang['RECENT_USER_VIEWS_TITLE'], $username);
		$mode_pagination = '&amp;' . POST_USERS_URL . '=' . $user_id;
		break;

	default:
		$message = $lang['Recent_wrong_mode'] . '<br /><br />' . sprintf($lang['Recent_click_return'], '<a href="' . append_sid('recent.' . PHP_EXT) . '">', '</a>') . '<br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
		break;
}

$result = $db->sql_query($sql);

$line = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();

// MG User Replied - BEGIN
// check if user replied to the topic
define('USER_REPLIED_ICON', true);
$user_topics = $class_topics->user_replied_array($line);
// MG User Replied - END

for($i = 0; $i < sizeof($line); $i++)
{
	$forum_id = $line[$i]['forum_id'];
	$topic_id = $line[$i]['topic_id'];
	$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
	$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
	$forum_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append);
	$topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append);
	$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

	$topic_title_data = $class_topics->generate_topic_title($topic_id, $line[$i], $topic_length);
	$topic_title = $topic_title_data['title'];
	$topic_title_clean = $topic_title_data['title_clean'];
	$topic_title_plain = $topic_title_data['title_plain'];
	$topic_title_label = $topic_title_data['title_label'];
	$topic_title_short = $topic_title_data['title_short'];
/*
print_r($topic_title_data);
die();
*/

	//$news_label = ($line[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
	$news_label = '';

	$views = $line[$i]['topic_views'];
	$replies = $line[$i]['topic_replies'];

	$topic_link = $class_topics->build_topic_icon_link($forum_id, $line[$i]['topic_id'], $line[$i]['topic_type'], $line[$i]['topic_reg'], $line[$i]['topic_replies'], $line[$i]['news_id'], $line[$i]['poll_start'], $line[$i]['topic_status'], $line[$i]['topic_moved_id'], $line[$i]['post_time'], $user_replied, $replies);

	$topic_id = $topic_link['topic_id'];
	$topic_id_append = $topic_link['topic_id_append'];

	$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

	$first_time = create_date_ip($lang['DATE_FORMAT_VF'], $line[$i]['topic_time'], $config['board_timezone'], true);
	// Old format
	//$first_time = create_date_ip($config['default_dateformat'], $line[$i]['topic_time'], $config['board_timezone']);
	$first_author = ($line[$i]['first_poster_id'] != ANONYMOUS) ? colorize_username($line[$i]['first_poster_id'], $line[$i]['first_poster'], $line[$i]['first_poster_color'], $line[$i]['first_poster_active']) : (($line[$i]['first_poster_name'] != '') ? $line[$i]['first_poster_name'] : $lang['Guest']);
	if (($user->data['user_level'] != ADMIN) && !empty($line[$i]['first_poster_mask']) && empty($line[$i]['first_poster_active']))
	{
		$first_author = $lang['INACTIVE_USER'];
	}
	$last_time = create_date_ip($config['default_dateformat'], $line[$i]['post_time'], $config['board_timezone']);
	$last_author = ($line[$i]['last_poster_id'] != ANONYMOUS) ? colorize_username($line[$i]['last_poster_id'], $line[$i]['last_poster'], $line[$i]['last_poster_color'], $line[$i]['last_poster_active']) : (($line[$i]['last_poster_name'] != '') ? $line[$i]['last_poster_name'] : $lang['Guest']);
	if (($user->data['user_level'] != ADMIN) && !empty($line[$i]['last_poster_mask']) && empty($line[$i]['last_poster_active']))
	{
		$last_author = $lang['INACTIVE_USER'];
	}
	$last_url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $line[$i]['topic_last_post_id']) . '#p' . $line[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

	// SELF AUTH - BEGIN
	// Comment the lines below if you wish to show RESERVED topics for AUTH_SELF.
	/*
	if ((($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD)) && (intval($is_auth_ary[$line[$i]['forum_id']]['auth_read']) == AUTH_SELF) && ($line[$i]['first_poster_id'] != $user->data['user_id']))
	{
		$first_author = $lang['Reserved_Author'];
		$last_author = $lang['Reserved_Author'];
		$topic_title = $lang['Reserved_Topic'];
	}
	*/
	// SELF AUTH - END

	if($mode == 'utview')
	{
		$last_time = $last_time = create_date_ip($config['default_dateformat'], $line[$i]['view_time'], $config['board_timezone']);;
		$last_author = '';
		$last_url = '';
	}

	$template->assign_block_vars('recent', array(
		'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],

		'TOPIC_ID' => $topic_id,
		'TOPIC_FOLDER_IMG' => $topic_link['image'],
		'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
		'TOPIC_TITLE' => $topic_title,
		'TOPIC_TITLE_PLAIN' => $topic_title_plain,
		'TOPIC_TYPE' => $topic_link['type'],
		'TOPIC_TYPE_ICON' => $topic_link['icon'],
		'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
		'CLASS_NEW' => $topic_link['class_new'],
		'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
		'L_NEWS' => $news_label,
		'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($line[$i]['topic_attachment']),
		'GOTO_PAGE' => $topic_pagination['base'],
		'GOTO_PAGE_FULL' => $topic_pagination['full'],
		'L_VIEWS' => $lang['Views'],
		'VIEWS' => $views,

		'L_REPLIES' => $lang['Replies'],
		'REPLIES' => $replies,
		//'FIRST_POST_TIME' => sprintf($lang['Recent_first'], $first_time),
		'FIRST_POST_TIME' => $first_time,
		'FIRST_AUTHOR' => $first_author,
		'LAST_POST_TIME' => $last_time,
		'LAST_AUTHOR' => $last_author,
		'LAST_URL' => $last_url,
		'FORUM_NAME' => $line[$i]['forum_name'],
		'U_VIEW_FORUM' => $forum_url,
		'U_VIEW_TOPIC' => $topic_url,
		)
	);
}

if ($total_topics == 0)
{
	$sql = "SELECT count(DISTINCT(t.topic_id)) AS total_topics
					FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p" . $extra_tables . "
					WHERE $where_count
					AND p.post_id = t.topic_last_post_id";
	$result = $db->sql_query($sql);
	if($total = $db->sql_fetchrow($result))
	{
		$total_topics = $total['total_topics'];
	}
}

$base_url = 'recent.' . PHP_EXT . '?mode=' . $mode . $mode_pagination;
if ($psort != $psort_types[0])
{
	$base_url .= '&amp;psort=' . $psort;
}
$pagination = generate_pagination($base_url, $total_topics, $topic_limit, $start);

if($total_topics == '0')
{
	$template->assign_block_vars('switch_no_topics', array());
}

$is_user_recent = in_array($mode, array('utopics', 'uposts', 'utview')) ? true : false;
$template->assign_vars(array(
	'L_RECENT_TITLE' => ($total_topics == '1') ? sprintf($lang['Recent_title_one'], $total_topics, $l_mode) : sprintf($lang['Recent_title_more'], $total_topics, $l_mode),
	'L_TODAY' => $lang['Recent_today'],
	'L_YESTERDAY' => $lang['Recent_yesterday'],
	'L_LAST24' => $lang['Recent_last24'],
	'L_LASTWEEK' => $lang['Recent_lastweek'],
	'L_LAST' => $lang['Recent_last'],
	'L_DAYS' => $lang['Recent_days'],
	'L_SELECT_MODE' => $lang['Recent_select_mode'],
	'L_SHOWING_POSTS' => $lang['Recent_showing_posts'],
	'L_LASTPOST' => ($mode == 'utview') ? $lang['Topic_time'] : $lang['Last_Post'],
	'L_NO_TOPICS' => $lang['Recent_no_topics'],
	'U_SORT_CAT' => append_sid('recent.' . PHP_EXT . '?amount_days=' . $amount_days . '&amp;mode=' . $mode . '&amp;psort=cat&amp;start=' . $start . (!empty($user_id) ? ('&amp;' . POST_USERS_URL . '=' . $user_id) : '')),
	'U_SORT_TIME' => append_sid('recent.' . PHP_EXT . '?amount_days=' . $amount_days . '&amp;mode=' . $mode . '&amp;psort=time&amp;start=' . $start . (!empty($user_id) ? ('&amp;' . POST_USERS_URL . '=' . $user_id) : '')),
	'IS_USER_RECENT' => $is_user_recent,
	'AMOUNT_DAYS' => $amount_days,
	'FORM_ACTION' => append_sid('recent.' . PHP_EXT),
	'U_RECENT_TODAY' => append_sid('recent.' . PHP_EXT . '?mode=today'),
	'U_RECENT_YESTERDAY' => append_sid('recent.' . PHP_EXT . '?mode=yesterday'),
	'U_RECENT_LAST24' => append_sid('recent.' . PHP_EXT . '?mode=last24'),
	'U_RECENT_LASTWEEK' => append_sid('recent.' . PHP_EXT . '?mode=lastweek'),
	'PAGINATION' => ($total_topics != '0') ? $pagination : '&nbsp;',
	'PAGE_NUMBER' => ($total_topics != '0') ? sprintf($lang['Page_of'], (floor($start / $topic_limit) + 1), ceil($total_topics / $topic_limit)) : '&nbsp;',
	)
);

full_page_generation('recent_body.tpl', $lang['Recent_topics'], '', '');

?>