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
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ($board_config['disable_topic_view'] == true)
{
	message_die(GENERAL_MESSAGE, $lang['Feature_Disabled']);
}

if (isset($_GET[POST_TOPIC_URL]))
{
	$topic_id = intval($_GET[POST_TOPIC_URL]);
}
elseif (isset($_POST[POST_TOPIC_URL]))
{
	$topic_id = intval($_POST[POST_TOPIC_URL]);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['Topic_post_not_exist']);
}


if (!$userdata['session_logged_in'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=topic_view_users.' . PHP_EXT . '&' . POST_TOPIC_URL . '=' . $topic_id, true));
}

// find the forum, in witch the topic are located
$sql = "SELECT f.forum_id
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
	WHERE f.forum_id = t.forum_id AND t.topic_id = '" . $topic_id . "'";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

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
/*
if (($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
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
for($i = 0; $i < count($mode_types_text); $i++)
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

// Generate page
$page_title = $lang['who_viewed'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'whoviewed_body.tpl'));
make_jumpbox(VIEWFORUM_MG);

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['who_viewed'],
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_ONLINE_STATUS' => $lang['Online_status'],
	'L_FROM' => $lang['Joined'],
	'L_LOGON' => $lang['Location'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_AIM' => $lang['AIM'],
	'L_YIM' => $lang['YIM'],
	'L_MSNM' => $lang['MSNM'],
	'L_ICQ' => $lang['ICQ'],
	'L_JOINED' => $lang['Topic_time'],
	'L_POSTS' => $lang['Topic_count'],
	'L_PM' => $lang['Private_Message'],
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('topic_view_users.' . PHP_EXT)
	)
);

switch($mode)
{
	case 'joined':
		$order_by = "u.user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'username':
		$order_by = "u.username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'topic_count':
		$order_by = "tv.view_count $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'topic_time':
		$order_by = "tv.view_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'email':
		$order_by = "u.user_email $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'website':
		$order_by = "u.user_website $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'topten':
		$order_by = "u.user_posts $sort_order LIMIT 10";
		break;
	default:
		$order_by = "u.user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
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

$sql = "SELECT u.username, u.user_id, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_avatar, u.user_avatar_type, u.user_allowavatar, user_allow_viewonline, user_session_time, tv.view_time, tv.view_count
	FROM " . USERS_TABLE . " u, " . TOPIC_VIEW_TABLE . " tv
	WHERE u.user_id = tv.user_id
		AND tv.topic_id = '" . $topic_id . "'
		" . $sql_hidden . "
	GROUP BY tv.user_id
	ORDER BY $order_by";

if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	$username = $row['username'];
	$user_id = $row['user_id'];

	$from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
	$joined = create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']);
	$topic_time = ($row['view_time']) ? create_date($board_config['default_dateformat'],$row['view_time'], $board_config['board_timezone']) : $lang['Never_last_logon'];
	$view_count = ($row['view_count']) ? $row['view_count'] : '&nbsp;';

	$poster_avatar = user_get_avatar($row['user_id'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

	if (!empty($row['user_viewemail']) || $userdata['user_level'] == ADMIN)
	{
		$email_uri = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $user_id) : 'mailto:' . $row['user_email'];

		$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
		$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
	}
	else
	{
		$email_img = '&nbsp;';
		$email = '&nbsp;';
	}

	$temp_url = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);
	$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
	$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

	$temp_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $user_id);
	$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
	$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

	$www_img = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '&nbsp;';
	$www = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Visit_website'] . '</a>' : '';

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

	$temp_url = append_sid(SEARCH_MG . '?search_author=' . urlencode($username) . '&amp;showresults=posts');
	$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . $lang['Search_user_posts'] . '" title="' . $lang['Search_user_posts'] . '" /></a>';
	$search = '<a href="' . $temp_url . '">' . $lang['Search_user_posts'] . '</a>';
	if ($row['user_session_time'] >= (time() - $board_config['online_time']))
	{
		if ($row['user_allow_viewonline'])
		{
			$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_online2'] . '" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" /></a>';
		}
		elseif ($userdata['user_level'] == ADMIN || $userdata['user_id'] == $user_id)
		{
			$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_hidden2'] . '" alt="' . $lang['Hidden'] . '" title="' . $lang['Hidden'] . '" /></a>';
		}
		else
		{
			$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
		}
	}
	else
	{
		$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
	}

	$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('memberrow', array(
		'ROW_NUMBER' => $i + (intval($_GET['start']) + 1),
		'ROW_COLOR' => '#' . $row_color,
		'ROW_CLASS' => $row_class,
		'USERNAME' => (($user_id != ANONYMOUS) ? colorize_username($user_id) : $lang['Guest']),

		'FROM' => $joined,
		'LAST_LOGON' => $from,
		'JOINED' => $topic_time,
		'POSTS' => $view_count,

		'AVATAR_IMG' => $poster_avatar,
		'PROFILE_IMG' => $profile_img,
		'PROFILE' => $profile,
		'SEARCH_IMG' => $search_img,
		'SEARCH' => $search,
		'PM_IMG' => $pm_img,
		'PM' => $pm,
		'EMAIL_IMG' => $email_img,
		'EMAIL' => $email,
		'WWW_IMG' => $www_img,
		'WWW' => $www,
		'ICQ_STATUS_IMG' => $icq_status_img,
		'ICQ_IMG' => $icq_img,
		'ICQ' => $icq,
		'AIM_IMG' => $aim_img,
		'AIM' => $aim,
		'MSN_IMG' => $msn_img,
		'MSN' => $msn,
		'YIM_IMG' => $yim_img,
		'YIM' => $yim,
		'SKYPE_IMG' => $skype_img,
		'SKYPE' => $skype,
		'ONLINE_STATUS_IMG' => $online_status_img,
		)
	);

	$i++;
}

if ($mode != 'topten' || $board_config['topics_per_page'] < 10)
{

	$sql = "SELECT count(*) AS total
		FROM " . TOPIC_VIEW_TABLE . "
		WHERE topic_id = " . $topic_id;

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
	}

	if ($total = $db->sql_fetchrow($result))
	{
		$total_members = $total['total'];
		$pagination = generate_pagination('topic_view_users.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;mode=' . $mode . '&amp;order=' . $sort_order, $total_members, $board_config['topics_per_page'], $start) . '&nbsp;';
	}
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['topics_per_page']) + 1), ceil($total_members / $board_config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>