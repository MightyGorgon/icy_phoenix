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

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$like = request_var('like', '');
if ($user->data['is_bot'] || (empty($like) && !empty($config['disable_topic_view'])) || (!empty($like) && (!empty($config['disable_likes_posts']) || !$user->data['session_logged_in'])))
{
	message_die(GENERAL_MESSAGE, $lang['Feature_Disabled']);
}

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

if ((empty($like) && empty($topic_id)) || (!empty($like) && empty($post_id)))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

if (!$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=topic_view_users.' . PHP_EXT . '&' . POST_TOPIC_URL . '=' . $topic_id, true));
}

// Find the forum where this topic is located
if (!empty($like))
{
	$sql = "SELECT f.*, t.*, p.*
		FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE p.post_id = " . $post_id . "
			AND t.topic_id = p.topic_id
			AND f.forum_id = t.forum_id";
}
else
{
	$sql = "SELECT f.*, t.*
		FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
		WHERE t.topic_id = " . $topic_id . "
			AND f.forum_id = t.forum_id";
}
$result = $db->sql_query($sql);
$forum_topic_data = $db->sql_fetchrow($result);
$db->sql_freeresult($result);
if (empty($forum_topic_data))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}
$forum_id = $forum_topic_data['forum_id'];

$is_auth = array();
$is_auth = auth(AUTH_ALL, $forum_id, $user->data, $forum_topic_data);

if (!$is_auth['auth_read'] || !$is_auth['auth_view'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

// If you want to disallow view to normal users decomment this block
//if (empty($like) && ($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))
if (empty($like) && ($user->data['user_level'] != ADMIN))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
/*
*/

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$select_name = 'mode';
$mode_types = array('topic_time', 'username', 'email', 'joindate', 'topic_count', 'website', 'topten');
$mode_types_text = array($lang['Topic_time'], $lang['SORT_USERNAME'], $lang['SORT_EMAIL'], $lang['SORT_JOINED'], $lang['Topic_count'], $lang['SORT_WEBSITE'], $lang['SORT_TOP_TEN']);
$mode = request_var('mode', $mode_types[0]);
$mode = check_var_value($mode, $mode_types);
$default = $mode;
$select_js = '';
$select_sort_mode = $class_form->build_select_box($select_name, $default, $mode_types, $mode_types_text, $select_js);

$select_name = 'order';
$sort_order_select_array = array('ASC', 'DESC');
$sort_order_select_lang_array = array($lang['Sort_Ascending'], $lang['Sort_Descending']);
$sort_order = request_var('order', 'DESC');
$sort_order = check_var_value($sort_order, $sort_order_select_array);
$default = $sort_order;
$select_js = '';
$select_sort_order = $class_form->build_select_box($select_name, $default, $sort_order_select_array, $sort_order_select_lang_array, $select_js);

$base_url = IP_ROOT_PATH . 'topic_view_users.' . PHP_EXT . '?' . (!empty($like) ? ('like=1&amp;' . POST_POST_URL . '=' . $post_id) : (POST_TOPIC_URL . '=' . $topic_id));
$base_url_full = $base_url . '&amp;mode=' . $mode . '&amp;order=' . $sort_order;

switch($mode)
{
	case 'joined':
		$order_by = "u.user_regdate";
		break;
	case 'username':
		$order_by = "u.username";
		break;
	case 'topic_count':
		$order_by = !empty($like) ? "u.user_posts" : "tv.view_count";
		break;
	case 'topic_time':
		$order_by = !empty($like) ? "pl.like_time" : "tv.view_time";
		break;
	case 'email':
		$order_by = "u.user_email";
		break;
	case 'website':
		$order_by = "u.user_website";
		break;
	case 'topten':
		$order_by = "u.user_posts $sort_order LIMIT 10";
		break;
	default:
		$order_by = !empty($like) ? "pl.like_time" : "u.user_regdate";
		break;
}

$order_by = $order_by . (($mode != 'topten') ? (' ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page']) : '');

if ($user->data['user_level'] == ADMIN)
{
	$sql_hidden = '';
}
else
{
	$sql_hidden = ' AND u.user_allow_viewonline = \'1\'';
}

if (!empty($like))
{
	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_allow_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang, pl.like_time
		FROM " . USERS_TABLE . " u, " . POSTS_LIKES_TABLE . " pl
		WHERE u.user_id = pl.user_id
			AND pl.post_id = " . $post_id . "
			" . $sql_hidden . "
		ORDER BY $order_by";
}
else
{
	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_allow_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang, tv.view_time, tv.view_count
		FROM " . USERS_TABLE . " u, " . TOPIC_VIEW_TABLE . " tv
		WHERE u.user_id = tv.user_id
			AND tv.topic_id = " . $topic_id . "
			" . $sql_hidden . "
		GROUP BY tv.user_id
		ORDER BY $order_by";
}
$result = $db->sql_query($sql);

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	$user_id = $row['user_id'];
	$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);

	$user_info = array();
	$user_info = generate_user_info($row);
	foreach ($user_info as $k => $v)
	{
		$$k = $v;
	}

	if (!empty($like))
	{
		$topic_time = ($row['like_time']) ? create_date($config['default_dateformat'], $row['like_time'], $config['board_timezone']) : $lang['Never_last_logon'];
		$view_count = '&nbsp;';
	}
	else
	{
		$topic_time = ($row['view_time']) ? create_date($config['default_dateformat'], $row['view_time'], $config['board_timezone']) : $lang['Never_last_logon'];
		$view_count = ($row['view_count']) ? $row['view_count'] : '&nbsp;';
	}

	$poster_avatar = $user_info['avatar'];

	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('memberrow', array(
		'ROW_NUMBER' => $i + (intval($_GET['start']) + 1),
		'ROW_CLASS' => $row_class,
		'USERNAME' => ($user_id == ANONYMOUS) ? $lang['Guest'] : $username,

		'LAST_VIEWED' => $topic_time,
		'VIEWS_COUNT' => $view_count,

		'FROM' => $user_info['from'],
		'JOINED' => $user_info['joined'],
		'POSTS' => $user_info['posts'],
		'AVATAR_IMG' => $user_info['avatar'],
		'GENDER' => $user_info['gender'],
		'PROFILE_URL' => $user_info['profile_url'],
		'PROFILE_IMG' => $user_info['profile_img'],
		'PROFILE' => $user_info['profile'],
		'PM_URL' => $user_info['pm_url'],
		'PM_IMG' => $user_info['pm_img'],
		'PM' => $user_info['pm'],
		'SEARCH_URL' => $user_info['search_url'],
		'SEARCH_IMG' => $user_info['search_img'],
		'SEARCH' => $user_info['search'],
		'IP_URL' => $user_info['ip_url'],
		'IP_IMG' => $user_info['ip_img'],
		'IP' => $user_info['ip'],
		'EMAIL_URL' => $user_info['email_url'],
		'EMAIL_IMG' => $user_info['email_img'],
		'EMAIL' => $user_info['email'],
		'WWW_URL' => $user_info['www_url'],
		'WWW_IMG' => $user_info['www_img'],
		'WWW' => $user_info['www'],
		'AIM_URL' => $user_info['aim_url'],
		'AIM_IMG' => $user_info['aim_img'],
		'AIM' => $user_info['aim'],
		'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
		'ICQ_URL' => $user_info['icq_url'],
		'ICQ_IMG' => $user_info['icq_img'],
		'ICQ' => $user_info['icq'],
		'MSN_URL' => $user_info['msn_url'],
		'MSN_IMG' => $user_info['msn_img'],
		'MSN' => $user_info['msn'],
		'SKYPE_URL' => $user_info['skype_url'],
		'SKYPE_IMG' => $user_info['skype_img'],
		'SKYPE' => $user_info['skype'],
		'YIM_URL' => $user_info['yahoo_url'],
		'YIM_IMG' => $user_info['yahoo_img'],
		'YIM' => $user_info['yahoo'],
		'ONLINE_STATUS_URL' => $user_info['online_status_url'],
		'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
		'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
		'ONLINE_STATUS' => $user_info['online_status'],
		'L_ONLINE_STATUS' => $user_info['online_status_lang'],
		)
	);

	$i++;
}

if (($mode != 'topten') || ($config['topics_per_page'] < 10))
{

	if (!empty($like))
	{
		$sql = "SELECT count(*) AS total
			FROM " . POSTS_LIKES_TABLE . "
			WHERE post_id = " . $post_id;
	}
	else
	{
		$sql = "SELECT count(*) AS total
			FROM " . TOPIC_VIEW_TABLE . "
			WHERE topic_id = " . $topic_id;
	}
	$result = $db->sql_query($sql);

	if ($total = $db->sql_fetchrow($result))
	{
		$total_members = $total['total'];
		$pagination = generate_pagination($base_url_full, $total_members, $config['topics_per_page'], $start);
	}
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

make_jumpbox(CMS_PAGE_VIEWFORUM);

$page_title = !empty($like) ? $lang['LIKE_RECAP'] : $lang['who_viewed'];
$template->assign_vars(array(
	'L_PAGE_TITLE' => $page_title,
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_ONLINE_STATUS' => $lang['Online_status'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_PM' => $lang['Private_Message'],
	'L_USER_PROFILE' => $lang['Profile'],
	'L_EMAIL' => $lang['Email'],
	'L_CONTACTS' => $lang['User_Contacts'],
	'L_ONLINE_STATUS' => $lang['Online_status'],
	'L_USER_WWW' => $lang['Website'],
	'L_USER_EMAIL' => $lang['Send_Email'],
	'L_USER_PROFILE' => $lang['Profile'],

	'L_VIEWS_COUNT' => $lang['Topic_count'],
	'L_LAST_VIEWED' => !empty($like) ? $lang['LIKE_TIME'] : $lang['Topic_time'],
	'L_FROM' => $lang['Location'],
	'L_JOINED' => $lang['Joined'],

	'S_POSTS_LIKES' => !empty($like) ? true : false,
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid($base_url),

	'CLOSE_WINDOW' => $lang['Close_window'],

	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_members / $config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

if (!empty($like))
{
	$gen_simple_header = true;
	$template->assign_var('S_POPUP', true);
}
full_page_generation('whoviewed_body.tpl', $page_title, '', '');

?>