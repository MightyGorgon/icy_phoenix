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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ($config['disable_topic_view'])
{
	message_die(GENERAL_MESSAGE, $lang['Feature_Disabled']);
}

$topic_id = request_var(POST_TOPIC_URL, 0);

if (empty($topic_id))
{
	message_die(GENERAL_MESSAGE, $lang['Topic_post_not_exist']);
}


if (!$userdata['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=topic_view_users.' . PHP_EXT . '&' . POST_TOPIC_URL . '=' . $topic_id, true));
}

// find the forum, in witch the topic are located
$sql = "SELECT f.forum_id
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
	WHERE f.forum_id = t.forum_id AND t.topic_id = '" . $topic_id . "'";
$result = $db->sql_query($sql);

if (!($forum_topic_data = $db->sql_fetchrow($result)))
{
	message_die(GENERAL_MESSAGE, $lang['Topic_post_not_exist']);
}
$forum_id = $forum_topic_data['forum_id'];

$is_auth_ary = array();
$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forum_topic_data);
if ((!$is_auth_ary[$forum_topic_data['forum_id']]['auth_read']) || (!$is_auth_ary[$forum_topic_data['forum_id']]['auth_view']))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

// If you want to disallow view to normal users decomment this block
//if (($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))
if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
/*
*/

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if (isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_POST['mode'])) ? htmlspecialchars($_POST['mode']) : htmlspecialchars($_GET['mode']);
}
else
{
	$mode = 'joined';
}

if(isset($_POST['order']))
{
	$sort_order = ($_POST['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($_GET['order']))
{
	$sort_order = ($_GET['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
}

$mode_types_text = array($lang['Sort_Username'], $lang['Sort_Email'], $lang['Sort_Joined'], $lang['Topic_time'], $lang['Topic_count'], $lang['Sort_Website'], $lang['Sort_Top_Ten']);
$mode_types = array('username', 'email', 'joindate', 'topic_time', 'topic_count', 'website', 'topten');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < sizeof($mode_types_text); $i++)
{
	$selected = ($mode == $mode_types[$i]) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

$select_sort_order .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '"/>';

make_jumpbox(CMS_PAGE_VIEWFORUM);

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['who_viewed'],
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
	'L_LAST_VIEWED' => $lang['Topic_time'],
	'L_FROM' => $lang['Location'],
	'L_JOINED' => $lang['Joined'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('topic_view_users.' . PHP_EXT)
	)
);

switch($mode)
{
	case 'joined':
		$order_by = "u.user_regdate $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'username':
		$order_by = "u.username $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'topic_count':
		$order_by = "tv.view_count $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'topic_time':
		$order_by = "tv.view_time $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'email':
		$order_by = "u.user_email $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'website':
		$order_by = "u.user_website $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'topten':
		$order_by = "u.user_posts $sort_order LIMIT 10";
		break;
	default:
		$order_by = "u.user_regdate $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
}

if ($userdata['user_level'] == ADMIN)
{
	$sql_hidden = '';
}
else
{
	$sql_hidden = ' AND u.user_allow_viewonline = \'1\'';
}

$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastlogon, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang, tv.view_time, tv.view_count
	FROM " . USERS_TABLE . " u, " . TOPIC_VIEW_TABLE . " tv
	WHERE u.user_id = tv.user_id
		AND tv.topic_id = '" . $topic_id . "'
		" . $sql_hidden . "
	GROUP BY tv.user_id
	ORDER BY $order_by";
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

	$topic_time = ($row['view_time']) ? create_date($config['default_dateformat'], $row['view_time'], $config['board_timezone']) : $lang['Never_last_logon'];
	$view_count = ($row['view_count']) ? $row['view_count'] : '&nbsp;';

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
		'YIM_URL' => $user_info['yim_url'],
		'YIM_IMG' => $user_info['yim_img'],
		'YIM' => $user_info['yim'],
		'ONLINE_STATUS_URL' => $user_info['online_status_url'],
		'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
		'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
		'ONLINE_STATUS' => $user_info['online_status'],
		'L_ONLINE_STATUS' => $user_info['online_status_lang'],
		)
	);

	$i++;
}

if ($mode != 'topten' || $config['topics_per_page'] < 10)
{

	$sql = "SELECT count(*) AS total
		FROM " . TOPIC_VIEW_TABLE . "
		WHERE topic_id = " . $topic_id;
	$result = $db->sql_query($sql);

	if ($total = $db->sql_fetchrow($result))
	{
		$total_members = $total['total'];
		$pagination = generate_pagination('topic_view_users.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;mode=' . $mode . '&amp;order=' . $sort_order, $total_members, $config['topics_per_page'], $start) . '&nbsp;';
	}
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_members / $config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

full_page_generation('whoviewed_body.tpl', $lang['who_viewed'], '', '');

?>