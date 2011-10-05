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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_online.' . PHP_EXT);
// Mighty Gorgon - HTTP AGENTS - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_mg_http.' . PHP_EXT);
// Mighty Gorgon - HTTP AGENTS - END

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Viewonline pagination... to be coded...
/*
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$show_all = isset($_GET['show_all']) ? true : false;
*/

$cms_page['page_id'] = 'viewonline';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

make_jumpbox(CMS_PAGE_VIEWFORUM);

$template->assign_vars(array(
	// Start add - Fully integrated shoutbox MOD
	'U_SHOUTBOX' => append_sid('shoutbox.' . PHP_EXT),
	'L_SHOUTBOX' => $lang['Shoutbox'],
	'U_SHOUTBOX_MAX' => append_sid('shoutbox_max.' . PHP_EXT),
	// End add - Fully integrated shoutbox MOD

	'L_WHOSONLINE' => $lang['Who_is_Online'],
	// Start Advanced IP Tools Pack MOD
	'L_WHOIS' => $lang['Whois'],
	'L_IP' => $lang['IP'],
	'L_BROWSER' => $lang['Browser'],
	// End Advanced IP Tools Pack MOD
	'L_USERNAME' => $lang['Username'],
	'L_FORUM_LOCATION' => $lang['Forum_Location'],
	'L_LAST_UPDATE' => $lang['Last_updated']
	)
);

// Forum info
$forum_types = array(FORUM_CAT, FORUM_POST, FORUM_LINK);
$forums_array = get_forums_ids($forum_types, true, false);
foreach ($forums_array as $forum)
{
	$forum_data[$forum['forum_id']] = $forum['forum_name'];
}

// Get auth data
$is_auth_ary = array();
$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $user->data);

// Viewonline pagination... to be coded...
/*
if ($show_all)
{
	$sql_limit = '';
}
else
{
	$sql_limit = 'LIMIT ' . $start . ', ' . $config['topics_per_page'];
}
*/

// Get user list
$online_users = get_online_users(false, true, '');

$guest_users = 0;
$registered_users = 0;
$hidden_users = 0;

$reg_counter = 0;
$guest_counter = 0;
$prev_user = 0;
$session_ip_array = array();
foreach ($online_users as $row)
{
	$view_online = false;
	$is_auth_view = false;
	$forum_id = false;
	$topic_id = false;
	// Mighty Gorgon - HTTP AGENTS - BEGIN
	$user_os = get_user_os($row['session_browser']);
	$user_browser = get_user_browser($row['session_browser']);
	// Mighty Gorgon - HTTP AGENTS - END

	if ($row['session_logged_in'])
	{
		$user_id = $row['user_id'];

		if ($user_id != $prev_user)
		{
			$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);

			if (!$row['user_allow_viewonline'])
			{
				$view_online = (($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $user_id)) ? true : false;
				$hidden_users++;
				$username = '<i>' . $username . '</i>';
			}
			else
			{
				$view_online = true;
				$registered_users++;
			}

			$which_counter = 'reg_counter';
			$which_row = 'reg_user_row';
			$prev_user = $user_id;
		}
	}
	else
	{
		// Skip multiple sessions for one user
		if (!empty($row['session_ip']) && !in_array($row['session_ip'], $session_ip_array))
		{
			$session_ip_array[] = $row['session_ip'];

			// MG BOTS Parsing - BEGIN
			$bot_name_tmp = bots_parse($row['session_ip'], $config['bots_color'], $row['session_browser']);
			if ($bot_name_tmp['name'] != false)
			{
				$username = $bot_name_tmp['name'];
			}
			else
			{
				$username = '<b>' . $lang['Guest'] . '</b>';
			}
			// MG BOTS Parsing - END
			$view_online = true;
			$guest_users++;
			$which_counter = 'guest_counter';
			$which_row = 'guest_user_row';
		}
	}

	if ($view_online)
	{
		if ((strpos($row['session_page'], CMS_PAGE_VIEWFORUM) !== false) || (strpos($row['session_page'], CMS_PAGE_VIEWTOPIC) !== false))
		{
			if (!empty($row['session_forum_id']))
			{
				$forum_id = $row['session_forum_id'];
				$is_auth_view = ($is_auth_ary[$forum_id]['auth_read'] != false) ? true : false;
			}

			if (!empty($row['session_topic_id']))
			{
				$topic_id = $row['session_topic_id'];
			}
		}

		if (!empty($topic_id))
		{
			// Topic info
			$sql_tt = "SELECT topic_title, forum_id FROM " . TOPICS_TABLE . " WHERE topic_id='" . $topic_id . "'";
			$result_tt = $db->sql_query($sql_tt);
			$topic_title = $db->sql_fetchrow($result_tt);
			if ($is_auth_ary[$topic_title['forum_id']]['auth_read'] != false)
			{
				$location['lang'] = $forum_data[$topic_title['forum_id']] . '&nbsp;&raquo;&nbsp;' . $topic_title['topic_title'];
				$location['url'] = CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $topic_title['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $topic_id;
			}
			else
			{
				$location['lang'] = $lang['Forum_index'];
				$location['url'] = CMS_PAGE_FORUM;
			}
		}
		else
		{
			if (!empty($forum_id) && $is_auth_view)
			//if (!empty($forum_id))
			{
				$location['lang'] = $forum_data[$forum_id];
				$location['url'] = CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id;
			}
			else
			{
				$location = get_online_page($row['session_page']);
			}
		}

		$location['url'] = append_sid(IP_ROOT_PATH . $location['url']);

		$row_class = ($$which_counter % 2) ? $theme['td_class1'] : $theme['td_class2'];

		// Start Advanced IP Tools Pack MOD
		$mode = htmlspecialchars($_GET['mode']);

		$test_ip = request_get_var('ip', '');
		if ((($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD)) && ($mode == 'lookup') && isset($_GET['ip']) && ($row['session_ip'] == $test_ip))
		{
			$ip = gethostbyaddr($row['session_ip']);
		}
		else
		{
			$ip = $row['session_ip'];
			$mode = 'ip';
		}
		// End Advanced IP Tools Pack MOD
		$template->assign_block_vars("$which_row", array(
			// Start Advanced IP Tools Pack MOD
			'IP' => htmlspecialchars($ip),
			'USER_AGENT' => htmlspecialchars($row['session_browser']) . '<br />' . htmlspecialchars($row['session_page']),
			'U_HOSTNAME_LOOKUP' => ($mode != 'lookup') ? append_sid(CMS_PAGE_VIEWONLINE . '?mode=lookup&amp;ip=' . htmlspecialchars(urlencode($row['session_ip']))) : append_sid(CMS_PAGE_VIEWONLINE . '?mode=ip&amp;ip=' . htmlspecialchars(urlencode($row['session_ip']))),
			'U_WHOIS' => 'http://whois.sc/' . htmlspecialchars(urlencode($row['session_ip'])),
			// End Advanced IP Tools Pack MOD

			'ROW_CLASS' => $row_class,
			'USERNAME' => $username,
			'LASTUPDATE' => create_date_ip($config['default_dateformat'], $row['session_time'], $config['board_timezone']),
			'FORUM_LOCATION' => $location['lang'],
			// Mighty Gorgon - HTTP AGENTS - BEGIN
			'USER_OS_IMG' => $user_os['img'],
			'USER_BROWSER_IMG' => $user_browser['img'],
			// Mighty Gorgon - HTTP AGENTS - END
			'U_USER_PROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
			'U_FORUM_LOCATION' => $location['url']
			)
		);
		// Start Advanced IP Tools Pack MOD
		$ip_display_auth = ip_display_auth($user->data, false);
		if (!empty($ip_display_auth))
		{
			$template->assign_block_vars($which_row . '.switch_display_ips', array());
		}
		// End Advanced IP Tools Pack MOD

		$$which_counter++;
	}
}

if($registered_users == 0)
{
	$l_r_user_s = $lang['Reg_users_zero_online'];
}
elseif($registered_users == 1)
{
	$l_r_user_s = $lang['Reg_user_online'];
}
else
{
	$l_r_user_s = $lang['Reg_users_online'];
}

if($hidden_users == 0)
{
	$l_h_user_s = $lang['Hidden_users_zero_online'];
}
elseif($hidden_users == 1)
{
	$l_h_user_s = $lang['Hidden_user_online'];
}
else
{
	$l_h_user_s = $lang['Hidden_users_online'];
}

if($guest_users == 0)
{
	$l_g_user_s = $lang['Guest_users_zero_online'];
}
elseif($guest_users == 1)
{
	$l_g_user_s = $lang['Guest_user_online'];
}
else
{
	$l_g_user_s = $lang['Guest_users_online'];
}

$template->assign_vars(array(
	'TOTAL_REGISTERED_USERS_ONLINE' => sprintf($l_r_user_s, $registered_users) . sprintf($l_h_user_s, $hidden_users),
	'TOTAL_GUEST_USERS_ONLINE' => sprintf($l_g_user_s, $guest_users)
	)
);

if ($registered_users + $hidden_users == 0)
{
	$template->assign_vars(array(
		'L_NO_REGISTERED_USERS_BROWSING' => $lang['No_users_browsing']
		)
	);
}

if ($guest_users == 0)
{
	$template->assign_vars(array(
		'L_NO_GUESTS_BROWSING' => $lang['No_users_browsing']
		)
	);
}
if ($config['online_shoutbox'] == 1)
{
	$template->assign_vars(array('S_SHOUTBOX' => true));
}

// Recent Topics - BEGIN
if ($config['online_last_msgs'] == 1)
{
	$template->assign_block_vars('switch_show_recent', array());

	$except_forums = build_exclusion_forums_list();

	if(!empty($config['last_msgs_x']))
	{
		$except_forums .= ',' . $config['last_msgs_x'];
	}

	$except_forums = str_replace(' ', '', $except_forums);

	$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, u.user_active, u.user_color, f.forum_name
			FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u, " . FORUMS_TABLE . " AS f
			WHERE t.forum_id NOT IN (" . $except_forums . ")
				AND t.topic_status <> 2
				AND p.post_id = t.topic_last_post_id
				AND p.poster_id = u.user_id
				AND f.forum_id = t.forum_id
			ORDER BY p.post_id DESC
			LIMIT " . intval($config['last_msgs_n']);
	$result = $db->sql_query($sql);
	$number_recent_topics = $db->sql_numrows($result);
	$recent_topic_row = array();
	while($row = $db->sql_fetchrow($result))
	{
		$recent_topic_row[] = $row;
	}
	for($i = 0; $i < $number_recent_topics; $i++)
	{
		$template->assign_block_vars('switch_show_recent.recent_topic_row', array(
			'U_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $recent_topic_row[$i]['forum_id']),
			'L_FORUM' => $recent_topic_row[$i]['forum_name'],
			'U_TITLE' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id']) . '#p' .$recent_topic_row[$i]['post_id'],
			'L_TITLE' => $recent_topic_row[$i]['topic_title'],
			'U_POSTER' => colorize_username($recent_topic_row[$i]['user_id'], $recent_topic_row[$i]['username'], $recent_topic_row[$i]['user_color'], $recent_topic_row[$i]['user_active']),
			'S_POSTER' => $recent_topic_row[$i]['username'],
			'S_POSTTIME' => create_date_ip($config['default_dateformat'], $recent_topic_row[$i]['post_time'], $config['board_timezone'])
			)
		);
	}

	// Last Seen - BEGIN
	$sql = "SELECT username, user_id, user_active, user_color, user_lastvisit, user_level, user_allow_viewonline
					FROM " . USERS_TABLE . "
					WHERE user_id <> " . ANONYMOUS . "
					ORDER BY user_lastvisit DESC
					LIMIT " . intval($config['last_msgs_n']);
	$result = $db->sql_query($sql);
	$number_last_seen = $db->sql_numrows($result);
	$last_seen_row = array();
	while($row = $db->sql_fetchrow($result)) { $last_seen_row[] = $row; }
	for($i = 0; $i < $number_last_seen; $i++)
	{
		$username = colorize_username($last_seen_row[$i]['user_id'], $last_seen_row[$i]['username'], $last_seen_row[$i]['user_color'], $last_seen_row[$i]['user_active']);
		$username_text = $last_seen_row[$i]['username'];
		if($last_seen_row[$i]['user_allow_viewonline'] != 1)
		{
			if(($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $last_seen_row[$i]['user_id']))
			{
				$username = '<i>' . $username . '</i>';
			}
			else
			{
				$username = '<i>' . $lang['Hidde_last_logon'] . '</i>';
			}
		}

		$template->assign_block_vars('switch_show_recent.last_seen_row', array(
				'U_LSEEN_LINK' => ($last_seen_row[$i]['user_allow_viewonline']) ? $username : (($user->data[user_level] == ADMIN) ? '<i>' . $username . '</i>' : $username),
				'L_LSEEN_USERNAME' => $username_text,
				'L_LSEEN_TIME' => create_date_ip($config['default_dateformat'], $last_seen_row[$i]['user_lastvisit'], $config['board_timezone']),
				//'L_LSEEN_TIME' => gmdate("d.m.Y - H:i", $last_seen_row[$i]['user_lastvisit']),
			)
		);
	}
	// Last Seen - END
}
// Recent Topics - END

full_page_generation('viewonline_body.tpl', $lang['Who_is_Online'], '', '');

?>