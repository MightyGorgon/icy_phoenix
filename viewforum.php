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

// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_VIEWFORUM', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
// Event Registration - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_events_reg.' . PHP_EXT);
// Event Registration - END

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

// Start initial var setup
$selected_id = request_var('selected_id', '');
if (!empty($selected_id))
{
	$type = substr($selected_id, 0, 1);
	$id = intval(substr($selected_id, 1));
	if ($type == POST_FORUM_URL)
	{
		$forum_id = $id;
		$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
	}
	elseif (($type == POST_CAT_URL) || ($selected_id == 'Root'))
	{
		$parm = ($id != 0) ? '?' . POST_CAT_URL . '=' . $id : '';
		redirect(append_sid(CMS_PAGE_FORUM . $parm));
		exit;
	}
}

$mark_read = request_var('mark', '');
// End initial var setup

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$kb_mode = false;
$kb_mode_append = '';
$kb_mode_append_red = '';
$kb_mode_var = request_var('kb', '');
if (($kb_mode_var == 'on') && ($user->data['bot_id'] == false))
{
	$kb_mode = true;
	$kb_mode_append = '&amp;kb=on';
	$kb_mode_append_red = '&kb=on';
}

if ($tree['data'][$tree['keys'][POST_FORUM_URL . $forum_id]]['forum_kb_mode'] == 1)
{
	if ($kb_mode_var == 'off')
	{
		$kb_mode = false;
		$kb_mode_append = '&amp;kb=off';
		$kb_mode_append_red = '&kb=off';
	}
	else
	{
		$kb_mode = true;
		$kb_mode_append = '&amp;kb=on';
		$kb_mode_append_red = '&kb=on';
	}
}

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

// UPI2DB - BEGIN
if ($user->data['upi2db_access'])
{
	$params = array(
		'always_read' => 'always_read',
		POST_FORUM_URL => 'f',
		POST_TOPIC_URL => 't',
		POST_POST_URL => 'p',
		'do' => 'do',
		'tt' => 'tt'
	);
	while(list($var, $param) = @each($params))
	{
		$$var = request_var($param, '');
	}
	$forum_id_append = ((!empty($f) && empty($forum_id_append)) ? (POST_FORUM_URL . '=' . $f) : $forum_id_append);
	$topic_id_append = (!empty($t) ? (POST_TOPIC_URL . '=' . $t) : '');
	$post_id_append = (!empty($p) ? (POST_POST_URL . '=' . $p) : '');

	if (!defined('UPI2DB_UNREAD'))
	{
		$user->data['upi2db_unread'] = upi2db_unread();
	}

	$except_time = except_time();

	if($do || $always_read)
	{
		if($do)
		{
			$mark_read_text = set_unread($t, $f, $p, $user->data['upi2db_unread'], $do, $tt);
		}

		if($always_read)
		{
			$mark_read_text = always_read($t, $always_read, $user->data['upi2db_unread']);
		}

		$redirect_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append);
		meta_refresh(3, $redirect_url);

		$message = $mark_read_text . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?'. $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
// UPI2DB - END

$cms_page['page_id'] = 'viewforum';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

// Force Topic Read - BEGIN
$ftr_disabled = $config['ftr_disable'] ? true : false;
if (!$ftr_disabled)
{
	@include(IP_ROOT_PATH . 'includes/topic_ftr.' . PHP_EXT);
}
// Force Topic Read - END

// Topics Sorting - BEGIN
$letters_array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$start_letter = request_var('start_letter', '');
$start_letter = (in_array($start_letter, $letters_array) ? $start_letter : '');

$sort_order_array = array('newest', 'oldest', 'AZ', 'ZA', 'views', 'replies', 'time', 'author');
$sort_order = request_var('sort_order', 'newest');
$sort_order = (in_array($sort_order, $sort_order_array) ? $sort_order : $sort_order_array[0]);
$sort_dir = request_var('sort_dir', 'DESC');
$sort_dir = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';

switch ($sort_order)
{
	case 'AZ':
		$sort_dir = 'ASC';
		$sort_order_sql = "t.topic_title " . $sort_dir;
		break;
	case 'ZA':
		$sort_dir = 'DESC';
		$sort_order_sql = "t.topic_title " . $sort_dir;
		break;
	case 'views':
		$sort_order_sql = "t.topic_views " . $sort_dir;
		break;
	case 'replies':
		$sort_order_sql = "t.topic_replies " . $sort_dir;
		break;
	case 'time':
		$sort_order_sql = "t.topic_time " . $sort_dir;
		break;
	case 'author':
		$sort_order_sql = "t.topic_poster " . $sort_dir;
		break;
	case 'oldest':
		$sort_dir = 'ASC';
		$sort_order_sql = "t.topic_last_post_id " . $sort_dir;
		break;
	case 'newest':
	default:
		$sort_order = 'newest';
		$sort_dir = 'DESC';
		$sort_order_sql = "t.topic_last_post_id " . $sort_dir;
		break;
}

if (!in_array($start_letter, $letters_array))
{
	$start_letter = '';
	$start_letter_sql = '';
}
else // we have a single letter, so let's sort alphabetically...
{
	$sort_dir = 'ASC';
	$sort_order_sql = "t.topic_title " . $sort_dir;
	//$start_letter_sql = "AND t.topic_title LIKE '" . $db->sql_escape($start_letter) . "%'";
	$start_letter_sql = "AND UPPER(SUBSTR(t.topic_title, 1, 1)) = '" . $db->sql_escape($start_letter) . "'";
}
// Topics Sorting - END

// get the forum row
//
// Check if the user has actually sent a forum ID with his/her request
// If not give them a nice error page.
//

$forum_row = $tree['data'][$tree['keys'][POST_FORUM_URL . $forum_id]];
if (empty($forum_row))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_FORUM');
}

$meta_content = array();
$meta_content = $class_topics->meta_content_init($forum_row, 'forum');
$meta_content['forum_id'] = $forum_id;

// handle forum link type
$selected_id = POST_FORUM_URL . $forum_id;
$CH_this = isset($tree['keys'][$selected_id]) ? $tree['keys'][$selected_id] : -1;
if (($CH_this > -1) && !empty($tree['data'][$CH_this]['forum_link']))
{
	// add 1 to hit if count ativated
	if ($tree['data'][$CH_this]['forum_link_hit_count'])
	{
		$sql = "UPDATE " . FORUMS_TABLE . "
					SET forum_link_hit = forum_link_hit + 1
					WHERE forum_id = $forum_id";
		$result = $db->sql_query($sql);
		cache_tree(true);
	}

	// prepare url
	$url = $tree['data'][$CH_this]['forum_link'];
	if ($tree['data'][$CH_this]['forum_link_internal'])
	{
		$part = explode('?', $url);
		$url .= ((sizeof($part) > 1) ? '&' : '?') . 'sid=' . $user->data['session_id'];
		$url = append_sid($url);

		// redirect to url
		redirect($url);
	}

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $url);
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta http-equiv="refresh" content="0; url=' . $url . '"><title>' . $lang['Redirect'] . '</title></head><body><div align="center">' . sprintf($lang['Redirect_to'], '<a href="' . $url . '">', '</a>') . '</div></body></html>';
		exit;
	}

	// Behave as per HTTP/1.1 spec for others
	header('Location: ' . $url);
	exit;
}

// Start auth check
$is_auth = array();
$is_auth = $tree['auth'][POST_FORUM_URL . $forum_id];

if (!$is_auth['auth_read'] || !$is_auth['auth_view'])
{
	if (!$user->data['session_logged_in'])
	{
		$redirect = $forum_id_append . $kb_mode_append_red . ((isset($start)) ? '&start=' . $start : '');
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_VIEWFORUM . '&' . $redirect, true));
	}

	// The user is not authed to read this forum ...
	$message = (!$is_auth['auth_view']) ? $lang['NO_FORUM'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}
// End of auth check

// Handle marking posts
if ($mark_read == 'topics')
{
	if ($user->data['session_logged_in'] && !$user->data['is_bot'])
	{
		// Force last visit to max 60 days limit to avoid having too much unread topics
		if ($user->data['user_lastvisit'] < (time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 24 * 60 * 60)))
		{
			$user->data['user_lastvisit'] = time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 24 * 60 * 60);
		}

		// UPI2DB - BEGIN
		if(!$user->data['upi2db_access'])
		{
		// UPI2DB - END

			$sql = "SELECT MAX(post_time) AS last_post
				FROM " . POSTS_TABLE . "
				WHERE forum_id = " . $forum_id;
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
				$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();

				if (((sizeof($tracking_forums) + sizeof($tracking_topics)) >= 150) && empty($tracking_forums[$forum_id]))
				{
					asort($tracking_forums);
					unset($tracking_forums[key($tracking_forums)]);
				}

				if ($row['last_post'] > $user->data['user_lastvisit'])
				{
					$tracking_forums[$forum_id] = time();
					$user->set_cookie('f', serialize($tracking_forums), $user->cookie_expire);
				}
			}
		// UPI2DB - BEGIN
		}
		else
		{
			marking_posts($forum_id);
		}
		// UPI2DB - END

		$redirect_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append);
		meta_refresh(3, $redirect_url);
	}

	$message = $lang['Topics_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append) . '">', '</a> ');
	message_die(GENERAL_MESSAGE, $message);
}
// End handle marking posts

$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : '';
$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : '';

// Do the forum Prune
if ($is_auth['auth_mod'] && $config['prune_enable'])
{
	if ($forum_row['prune_next'] < time() && $forum_row['prune_enable'])
	{
		include(IP_ROOT_PATH . 'includes/prune.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
		auto_prune($forum_id);
	}
}
// End of forum prune

//
// Obtain list of moderators of each forum
// First users, then groups ... broken into two queries
//
// moderators list
$moderators = array();
$idx = $tree['keys'][POST_FORUM_URL . $forum_id];
for ($i = 0; $i < sizeof($tree['mods'][$idx]['user_id']); $i++)
{
	$moderators[] = colorize_username($tree['mods'][$idx]['user_id'][$i], $tree['mods'][$idx]['username'][$i], $tree['mods'][$idx]['user_color'][$i], $tree['mods'][$idx]['user_active'][$i]);
}
for ($i = 0; $i < sizeof($tree['mods'][$idx]['group_id']); $i++)
{
	$group_color_style = ' style="font-weight: bold; text-decoration: none;' . (($tree['mods'][$idx]['group_color'][$i] != '') ? 'color: ' . $tree['mods'][$idx]['group_color'][$i] . ';"' : '"');
	$moderators[] = '<a href="' . append_sid( CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $tree['mods'][$idx]['group_id'][$i]) . '"' . $group_color_style . '>' . $tree['mods'][$idx]['group_name'][$i] . '</a>';
}

$l_moderators = (sizeof($moderators) == 1) ? $lang['Moderator'] : $lang['Moderators'];
$forum_moderators = (sizeof($moderators)) ? implode(', ', $moderators) : $lang['None'];
unset($moderators);

// Forum notification MOD - BEGIN
// Is user watching this forum?
$watch = request_var('watch', '');
$unwatch = request_var('unwatch', '');
if($user->data['session_logged_in'] && !$user->data['is_bot'])
{
	($forum_row['forum_notify'] == '1') ? ($can_watch_forum = true) : ($can_watch_forum = false);

	$sql = "SELECT notify_status
	FROM " . FORUMS_WATCH_TABLE . "
	WHERE forum_id = $forum_id
		AND user_id = " . $user->data['user_id'] . "
	LIMIT 1";
	$result = $db->sql_query($sql);

	if($row = $db->sql_fetchrow($result))
	{
		if(!empty($unwatch))
		{
			if($unwatch == 'forum')
			{
				$is_watching_forum = 0;

				$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
					WHERE forum_id = " . $forum_id . "
						AND user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);
			}

			$redirect_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start);
			meta_refresh(3, $redirect_url);

			$message = $lang['No_longer_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_forum = true;

			if($row['notify_status'])
			{
				$sql = "UPDATE " . FORUMS_WATCH_TABLE . "
					SET notify_status = 0
					WHERE forum_id = " . $forum_id . "
						AND user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);
			}
		}
	}
	else
	{
		if(!empty($watch))
		{
			if($watch == 'forum')
			{
				$is_watching_forum = true;

				$sql = "INSERT INTO " . FORUMS_WATCH_TABLE . " (user_id, forum_id, notify_status)
					VALUES (" . $user->data['user_id'] . ", " . $forum_id . ", 0)";
				$result = $db->sql_query($sql);
			}

			$redirect_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start);
			meta_refresh(3, $redirect_url);

			$message = $lang['You_are_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_forum = 0;
		}
	}
}
else
{
	if(!empty($unwatch))
	{
		if($unwatch == 'forum')
		{
			header('Location: ' . append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_VIEWFORUM . '&' . $forum_id_append . $kb_mode_append_red . '&unwatch=forum', true));
		}
	}
	else
	{
		$can_watch_forum = 0;
		$is_watching_forum = 0;
	}
}
// Forum notification MOD - END

// Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent then get its value,
// find the number of topics with dates newer than it (to properly handle pagination) and alter the main query
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['ALL_TOPICS'], $lang['1_DAY'], $lang['7_DAYS'], $lang['2_WEEKS'], $lang['1_MONTH'], $lang['3_MONTHS'], $lang['6_MONTHS'], $lang['1_YEAR']);

$topic_days = request_var('topicdays', 0);
if (!empty($topic_days))
{
	$min_topic_time = time() - ($topic_days * 86400);

	$sql = "SELECT COUNT(t.topic_id) AS forum_topics
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.forum_id = " . $forum_id . "
			AND p.post_id = t.topic_last_post_id
			AND p.post_time >= $min_topic_time";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$topics_count = ($row['forum_topics']) ? $row['forum_topics'] : 1;
	$limit_topics_time = "AND p.post_time >= " . $min_topic_time;

	if (!empty($topic_days))
	{
		$start = 0;
	}
}
else
{
	// Topics Sorting - BEGIN
	if (!empty($start_letter))
	{
		$sql = "SELECT COUNT(topic_id) AS forum_topics
			FROM " . TOPICS_TABLE . " t
			WHERE t.forum_id = '" . $forum_id . "'
				" . $start_letter_sql . "
			ORDER BY " . $sort_order_sql;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$topics_count = ($row['forum_topics']) ? $row['forum_topics'] : 1;
		$db->sql_freeresult($result);
	}
	else
	{
	// Topics Sorting - END
		$topics_count = ($forum_row['forum_topics']) ? $forum_row['forum_topics'] : 1;
	// Topics Sorting - BEGIN
	}
	// Topics Sorting - END
	$limit_topics_time = '';
	$topic_days = 0;
}

$select_topic_days = '<select name="topicdays">';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_topic_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$select_topic_days .= '</select>';

// UPI2DB - BEGIN
if(!$user->data['upi2db_access'])
{
// UPI2DB - END

	// All GLOBAL announcement data, this keeps GLOBAL announcements on each viewforum page...
	$sql = "SELECT t.*, u.username, u.user_id, u.user_active, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_color as user_color2, p.post_time, p.post_username
					FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2
					WHERE t.topic_poster = u.user_id
						AND p.post_id = t.topic_last_post_id
						AND p.poster_id = u2.user_id
						AND t.topic_type = " . POST_GLOBAL_ANNOUNCE . "
						" . $start_letter_sql . "
					ORDER BY " . $sort_order_sql;
	$result = $db->sql_query($sql);

	$topic_rowset = array();
	$total_announcements = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$topic_rowset[] = $row;
		$total_announcements++;
	}

	$db->sql_freeresult($result);
// End add - Global announcement MOD
// UPI2DB - BEGIN
//}
//if(!$user->data['upi2db_access'])
//{
// UPI2DB - END
	// All announcement data, this keeps announcements on each viewforum page...
	$sql = "SELECT t.*, u.username, u.user_id, u.user_active, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_color as user_color2, p.post_time, p.post_username
					FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2
					WHERE t.forum_id = $forum_id
						AND t.topic_poster = u.user_id
						AND p.post_id = t.topic_last_post_id
						AND p.poster_id = u2.user_id
						AND t.topic_type = " . POST_ANNOUNCE . "
						" . $start_letter_sql . "
					ORDER BY " . $sort_order_sql;
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$topic_rowset[] = $row;
		$total_announcements++;
	}

	$db->sql_freeresult($result);

// UPI2DB - BEGIN
	$upi2db_post_announce = "AND t.topic_type <> " . POST_ANNOUNCE . " AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE;
	$upi2db_post_global_announce = "t.forum_id = $forum_id";
}
else
{
	$topic_rowset = array();
	$total_announcements = 0;
	$upi2db_post_announce = '';
	$upi2db_post_global_announce = "(t.forum_id = $forum_id OR t.topic_type = " . POST_GLOBAL_ANNOUNCE . ")";
}
// UPI2DB - END

// UPI2DB - EDIT - BEGIN
//add , p2.post_edit_time
//change *t.forum_id = $forum_id* to *$upi2db_post_global_announce*
//change *AND t.topic_type <> " . POST_ANNOUNCE . " AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE . "* to *$upi2db_post_announce*
// UPI2DB - EDIT - END

// Grab all the basic data (all topics except announcements) for this forum
// Self AUTH - BEGIN
//$self_sql = (intval($is_auth['auth_read']) == AUTH_SELF) ? " AND t.topic_poster = '" . $user->data['user_id'] . "'" : '';
$self_sql = (intval($is_auth['auth_read']) == AUTH_SELF) ? " AND (t.topic_poster = '" . $user->data['user_id'] . "' OR t.topic_type = '" . POST_GLOBAL_ANNOUNCE . "' OR t.topic_type = '" . POST_ANNOUNCE . "' OR t.topic_type = '" . POST_STICKY . "')" : '';
// Self AUTH - END
$sql = "SELECT t.*, u.username, u.user_id, u.user_active, u.user_mask, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_mask as user_mask2, u2.user_color as user_color2, p.post_username, p2.post_username AS post_username2, p2.post_time, p2.post_edit_time, p.enable_bbcode, p.enable_html, p.enable_smilies
				FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
				WHERE $upi2db_post_global_announce
					AND t.topic_poster = u.user_id
					AND p.post_id = t.topic_first_post_id
					AND p2.post_id = t.topic_last_post_id
					AND u2.user_id = p2.poster_id
					$self_sql
					$upi2db_post_announce
					$start_letter_sql
				ORDER BY t.topic_type DESC, " . $sort_order_sql . "
				LIMIT " . $start . ", " . $config['topics_per_page'];
// UPI2DB DELETE
//#AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE . "
// UPI2DB - END
$result = $db->sql_query($sql);
$total_topics = 0;
// UPI2DB - BEGIN
// REPLACE
/*
while($row = $db->sql_fetchrow($result))
{
	$topic_rowset[] = $row;
	$total_topics++;
}
*/

$topic_rowset_gae = array();
$topic_rowset_gan = array();
$topic_rowset_ae = array();
$topic_rowset_an = array();
$topic_rowset_a = array();
$topic_rowset_se = array();
$topic_rowset_sn = array();
$topic_rowset_ne = array();
$topic_rowset_nn = array();
$topic_rowset_ar = array();
$topic_rowset_n = array();

if($user->data['upi2db_access'])
{
	if($config['upi2db_edit_topic_first'])
	{
		while($row = $db->sql_fetchrow($result))
		{
			if(isset($user->data['upi2db_unread']['edit_topics']) && isset($user->data['upi2db_unread']['always_read']['topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
			{
				$topic_rowset_gae[] = $row;
				$total_announcements++;
			}
			elseif(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
			{
				$topic_rowset_gan[] = $row;
				$total_announcements++;
			}
			elseif(isset($user->data['upi2db_unread']['edit_topics']) && isset($user->data['upi2db_unread']['always_read']['topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_ANNOUNCE)
			{
				$topic_rowset_ae[] = $row;
				$total_announcements++;
			}
			elseif(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_ANNOUNCE)
			{
				$topic_rowset_an[] = $row;
				$total_announcements++;
			}
			elseif(isset($user->data['upi2db_unread']['edit_topics']) && isset($user->data['upi2db_unread']['always_read']['topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_STICKY)
			{
				$topic_rowset_se[] = $row;
				$total_topics++;
			}
			elseif(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_STICKY)
			{
				$topic_rowset_sn[] = $row;
				$total_topics++;
			}
			elseif(isset($user->data['upi2db_unread']['edit_topics']) && isset($user->data['upi2db_unread']['always_read']['topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] != POST_STICKY && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_GLOBAL_ANNOUNCE)
			{
				$topic_rowset_ne[] = $row;
				$total_topics++;
			}
			elseif(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] != POST_STICKY && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_GLOBAL_ANNOUNCE)
			{
				$topic_rowset_nn[] = $row;
				$total_topics++;
			}
			if(in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']))
			{
				$topic_rowset_ar[] = $row;
				$total_topics++;
			}
		}
		$topic_rowset = array_merge($topic_rowset_gae, $topic_rowset_gan, $topic_rowset_ae, $topic_rowset_an, $topic_rowset_se, $topic_rowset_sn, $topic_rowset_ne, $topic_rowset_nn, $topic_rowset_ar);
	}
	else
	{
		while($row = $db->sql_fetchrow($result))
		{
			if(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
			{
				$topic_rowset_gan[] = $row;
				$total_announcements++;
			}
			elseif(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] == POST_ANNOUNCE)
			{
				$topic_rowset_an[] = $row;
				$total_announcements++;
			}
			elseif(isset($user->data['upi2db_unread']['always_read']['topics']) && !in_array($row['topic_id'], $user->data['upi2db_unread']['always_read']['topics']) && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_GLOBAL_ANNOUNCE)
			{
				$topic_rowset_nn[] = $row;
				$total_topics++;
			}
			else
			{
				$topic_rowset_ar[] = $row;
				$total_topics++;
			}
		}
		$topic_rowset = array_merge($topic_rowset_gan, $topic_rowset_an, $topic_rowset_nn, $topic_rowset_ar);
	}
}
else
{
	while($row = $db->sql_fetchrow($result))
	{
		$topic_rowset[] = $row;
		$total_topics++;
	}
}
// UPI2DB - END
$db->sql_freeresult($result);

$forum_likes_switch = !empty($tree['data'][$CH_this]['forum_likes']) ? true : false;

// Total topics ...
$total_topics += $total_announcements;
$dividers = get_dividers($topic_rowset);

// Post URL generation for templating vars
$template->assign_vars(array(
	'L_DISPLAY_TOPICS' => $lang['Display_topics'],
	'U_POST_NEW_TOPIC' => append_sid(CMS_PAGE_POSTING . '?mode=newtopic&amp;' . $forum_id_append),
	'S_SELECT_TOPIC_DAYS' => $select_topic_days,
	'S_POST_DAYS_ACTION' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start . '&amp;start_letter=' . $start_letter . '&amp;sort_order=' . $sort_order . '&amp;sort_dir=' . $sort_dir)
	)
);

// User authorization levels output
// Self AUTH - BEGIN
$lang['Rules_reply_can'] = ((intval($is_auth['auth_reply']) == AUTH_SELF) ? $lang['Rules_reply_can_own'] : $lang['Rules_reply_can']);
// Self AUTH - END
$s_auth_can = ($is_auth['auth_post'] ? $lang['Rules_post_can'] : $lang['Rules_post_cannot']) . '<br />';
$s_auth_can .= ($is_auth['auth_reply'] ? $lang['Rules_reply_can'] : $lang['Rules_reply_cannot']) . '<br />';
$s_auth_can .= ($is_auth['auth_edit'] ? $lang['Rules_edit_can'] : $lang['Rules_edit_cannot']) . '<br />';
$s_auth_can .= ($is_auth['auth_delete'] ? $lang['Rules_delete_can'] : $lang['Rules_delete_cannot']) . '<br />';
$s_auth_can .= ($is_auth['auth_vote'] ? $lang['Rules_vote_can'] : $lang['Rules_vote_cannot']) . '<br />';
if (intval($config['disable_attachments_mod']) == 0)
{
	$s_auth_can .= ($is_auth['auth_attachments'] ? $lang['Rules_attach_can'] : $lang['Rules_attach_cannot']) . '<br />';
	$s_auth_can .= ($is_auth['auth_download'] ? $lang['Rules_download_can'] : $lang['Rules_download_cannot']) . '<br />';
}
$s_auth_can .= ($is_auth['auth_cal'] ? $lang['Rules_calendar_can'] : $lang['Rules_calendar_cannot']) . '<br />';
$s_auth_can .= ($is_auth['auth_ban'] ? $lang['Rules_ban_can'] . '<br />' : '');
$s_auth_can .= ($is_auth['auth_greencard'] ? $lang['Rules_greencard_can'] . '<br />' : '');
$s_auth_can .= ($is_auth['auth_bluecard'] ? $lang['Rules_bluecard_can'] . '<br />' : '');

//attach_build_auth_levels($is_auth, $s_auth_can);

if ($is_auth['auth_mod'])
{
	$s_auth_can .= sprintf($lang['Rules_moderate'], '<a href="modcp.' . PHP_EXT . '?' . $forum_id_append . '&amp;start=' . $start . '&amp;sid=' . $user->data['session_id'] . '">', '</a>');
}

// Mozilla navigation bar
$nav_links['up'] = array(
	'url' => append_sid(CMS_PAGE_FORUM),
	'title' => sprintf($lang['Forum_Index'], $config['sitename']
	)
);

// Start add - Forum notification MOD
$s_watching_forum = '';

if($can_watch_forum)
{
	if($is_watching_forum)
	{
		$s_watching_forum = '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;unwatch=forum&amp;start=' . $start) . '">' . $lang['Stop_watching_forum'] . '</a>';
		$s_watching_forum_img = (isset($images['Forum_un_watch'])) ? '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;unwatch=forum&amp;start=' . $start) . '"><img src="' . $images['Forum_un_watch'] . '" alt="' . $lang['Stop_watching_forum'] . '" title="' . $lang['Stop_watching_forum'] . '" border="0"></a>' : '';
	}
	else
	{
		$s_watching_forum = '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;watch=forum&amp;start=' . $start) . '">' . $lang['Start_watching_forum'] . '</a>';
		$s_watching_forum_img = (isset($images['Forum_watch'])) ? '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;watch=forum&amp;start=' . $start) . '"><img src="' . $images['Forum_watch'] . '" alt="' . $lang['Stop_watching_forum'] . '" title="' . $lang['Start_watching_forum'] . '" border="0"></a>' : '';
	}
}
// End add - Forum notification MOD

// UPI2DB - BEGIN
if($user->data['upi2db_access'])
{
	if(!in_array($forum_id, $user->data['upi2db_unread']['always_read']['forums']))
	{
		$mark_as_read = '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;mark=topics') . '">' . $lang['Mark_all_topics'] . '</a>';
		$mark_always_read = '<a href="' . append_sid(CMS_PAGE_FORUM . '?forum_id=' . $forum_id . $kb_mode_append . '&amp;always_read=set') . '">' . $lang['upi2db_always_read_forum_short'] . '</a>';
		$marked_as_read = '';
	}
	else
	{
		$mark_as_read = '';
		$mark_always_read = '<a href="' . append_sid(CMS_PAGE_FORUM . '?forum_id=' . $forum_id . $kb_mode_append . '&amp;always_read=unset') . '">' . $lang['upi2db_always_read_forum_unset_short'] . '</a>';
		$marked_as_read = $lang['upi2db_forum_is_always_read'];
	}
}
else
{
	$mark_as_read = '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;mark=topics') . '">' . $lang['Mark_all_topics'] . '</a>';
	$mark_always_read = '';
	$marked_as_read = '';
}
// UPI2DB - END

// Dump out the page header and load viewforum template

$forum_row['forum_name'] = get_object_lang(POST_FORUM_URL . $forum_id, 'name');
if ($config['display_viewonline'])
{
	define('SHOW_ONLINE', true);
}
if (!$config['board_disable'] || ($config['board_disable'] && ($user->data['user_level'] == ADMIN)))
{
	$template->vars['S_TPL_FILENAME'] = 'index';
}

//$template->assign_block_vars('google_ad', array());
$meta_content['page_title'] = $forum_row['forum_name'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';
$breadcrumbs['bottom_right_links'] = '';
if ($user->data['session_logged_in'] && !$user->data['is_bot'])
{
	$breadcrumbs['bottom_left_links'] = $marked_as_read;
	$breadcrumbs['bottom_right_links'] = (($mark_as_read != '') ? ($mark_as_read . '&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . $s_watching_forum . (($mark_always_read != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;' . $mark_always_read) : '');
}
$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid('viewforumlist.' . PHP_EXT . '?' . $forum_id_append) . '">' . $lang['VF_ALL_TOPICS'] . '</a>';

$template_to_parse = ($kb_mode) ? 'viewforum_kb_body.tpl' : 'viewforum_body.tpl';

make_jumpbox(CMS_PAGE_VIEWFORUM);

$rules_bbcode = '';
if (!empty($forum_row['forum_rules_switch']))
{
	if (isset($forum_row['forum_rules_in_viewforum']) && $forum_row['forum_rules_in_viewforum'])
	{
		//BBcode Parsing for Olympus rules Start
		$rules_bbcode = $forum_row['forum_rules'];
		$bbcode->allow_html = true;
		$bbcode->allow_bbcode = true;
		$bbcode->allow_smilies = true;
		$rules_bbcode = $bbcode->parse($rules_bbcode);
		//BBcode Parsing for Olympus rules Start

		$template->assign_vars(array(
			'S_FORUM_RULES' => true,
			'S_FORUM_RULES_TITLE' => ($forum_row['forum_rules_display_title']) ? true : false
			)
		);
	}
}
display_index(POST_FORUM_URL . $forum_id);

if ($forum_row['auth_rate'] != -1)
{
	$template->assign_block_vars('rating_switch', array());
}

if ($config['forum_wordgraph'] && !empty($forum_row['forum_tags']))
{
	include(IP_ROOT_PATH . 'includes/forum_wordgraph.' . PHP_EXT);
}

$is_this_locked = ($forum_row['forum_status'] == FORUM_LOCKED) ? true : false;
$sort_lang = ($sort_dir == 'ASC') ? $lang['Sort_Ascending'] : $lang['Sort_Descending'];
$sort_img = ($sort_dir == 'ASC') ? 'images/sort_asc.png' : 'images/sort_desc.png';
$sort_img_full = '<img src="' . $sort_img . '" alt="' . $sort_lang . '" title="' . $sort_lang . '" style="padding-left: 3px;" />';
$start_letter_append = ($start_letter == '') ? '' : ('&amp;start_letter=' . $start_letter);
$sort_order_append = '&amp;sort_order=' . $sort_order;
$sort_dir_append = '&amp;sort_dir=' . $sort_dir;
$sort_dir_append_rev = '&amp;sort_dir=' . (($sort_dir == 'ASC') ? 'DESC' : 'ASC');
$topic_days_append = ($topic_days == 0) ? '' : ('&amp;topicdays=' . $topic_days);
$this_forum_address = CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . $topic_days_append . $start_letter_append;
$icon_img = empty($forum_row['icon']) ? '' : (isset($images[$forum_row['icon']]) ? $images[$forum_row['icon']] : $forum_row['icon']);

$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_ID_FULL' => POST_FORUM_URL . $forum_id,
	'FORUM_NAME' => $forum_row['forum_name'],
	'FORUM_ICON_IMG' => $icon_img,
	'FORUM_RULES' => $rules_bbcode,
	'MODERATORS' => $forum_moderators,
	'POST_IMG' => ($forum_row['forum_status'] == FORUM_LOCKED) ? $images['post_locked'] : $images['post_new'],
	'IS_LOCKED' => $is_this_locked,
	'S_FORUM_LIKES_SWITCH' => $forum_likes_switch,

	'FOLDER_IMG' => $images['topic_nor_read'],
	'FOLDER_NEW_IMG' => $images['topic_nor_unread'],
	'FOLDER_HOT_IMG' => $images['topic_hot_read'],
	'FOLDER_HOT_NEW_IMG' => $images['topic_hot_unread'],
	'FOLDER_LOCKED_IMG' => $images['topic_nor_locked_read'],
	'FOLDER_LOCKED_NEW_IMG' => $images['topic_nor_locked_unread'],
	'FOLDER_STICKY_IMG' => $images['topic_imp_read'],
	'FOLDER_STICKY_NEW_IMG' => $images['topic_imp_unread'],
	'FOLDER_ANNOUNCE_IMG' => $images['topic_ann_read'],
	'FOLDER_ANNOUNCE_NEW_IMG' => $images['topic_ann_unread'],
	// UPI2DB - BEGIN
	'FOLDER_AR' => $images['topic_ar_read'],
	'L_AR_POSTS' => $lang['always_read_icon'],
	// UPI2DB - END
	'FOLDER_GLOBAL_ANNOUNCE_IMG' => $images['topic_glo_read'],
	'FOLDER_GLOBAL_ANNOUNCE_NEW_IMG' => $images['topic_glo_unread'],

	'L_TOPICS' => $lang['Topics'],
	'L_FORUM_RULES' => (empty($forum_row['forum_rules_custom_title'])) ? $lang['Forum_Rules'] : $forum_row['forum_rules_custom_title'],
	'L_REPLIES' => $lang['Replies'],
	'L_VIEWS' => $lang['Views'],
	'L_POSTS' => $lang['Posts'],
	'L_LASTPOST' => $lang['Last_Post'],
	'L_GO_TO_PAGE_NUMBER' => $lang['Go_To_Page_Number'],
	'L_MODERATOR' => $l_moderators,
	'L_MARK_TOPICS_READ' => $lang['Mark_all_topics'],
	'L_POST_NEW_TOPIC' => ($forum_row['forum_status'] == FORUM_LOCKED) ? $lang['Forum_locked'] : $lang['Post_new_topic'],
	'L_ANNOUNCEMENT' => $lang['Post_Announcement'],
	'L_STICKY' => $lang['Post_Sticky'],
	'L_GLOBAL_ANNOUNCEMENT' => $lang['Post_global_announcement'],

	'L_NO_NEW_POSTS_GLOBAL_ANNOUNCEMENT' => $lang['No_new_posts_global_announcement'],
	'L_NEW_POSTS_GLOBAL_ANNOUNCEMENT' => $lang['New_posts_global_announcement'],
	'L_NO_NEW_POSTS_ANNOUNCEMENT' => $lang['No_new_posts_announcement'],
	'L_NEW_POSTS_ANNOUNCEMENT' => $lang['New_posts_announcement'],
	'L_NO_NEW_POSTS_STICKY' => $lang['No_new_posts_sticky'],
	'L_NEW_POSTS_STICKY' => $lang['New_posts_sticky'],
	'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'],
	'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'],
	'L_NO_NEW_POSTS_HOT' => $lang['No_new_posts_hot'],
	'L_NEW_POSTS_HOT' => $lang['New_posts_hot'],
	'L_NO_NEW_POSTS' => $lang['No_new_posts'],
	'L_NEW_POSTS' => $lang['New_posts'],

	'U_VF_TITLE_SORT' => append_sid($this_forum_address . '&amp;sort_order=' . (($sort_order == 'AZ') ? 'ZA' : 'AZ')),
	'U_VF_VIEWS_SORT' => append_sid($this_forum_address . '&amp;sort_order=views' . $sort_dir_append_rev),
	'U_VF_REPLIES_SORT' => append_sid($this_forum_address . '&amp;sort_order=replies' . $sort_dir_append_rev),
	'U_VF_TIME_SORT' => append_sid($this_forum_address . '&amp;sort_order=time' . $sort_dir_append_rev),
	'U_VF_AUTHOR_SORT' => append_sid($this_forum_address . '&amp;sort_order=author' . $sort_dir_append_rev),
	'U_VF_LAST_POST_SORT' => append_sid($this_forum_address . '&amp;sort_order=' . (($sort_order == 'newest') ? 'oldest' : 'newest')),

	'VF_TITLE_SORT' => ((($sort_order == 'AZ') || ($sort_order == 'ZA')) ? $sort_img_full : ''),
	'VF_VIEWS_SORT' => (($sort_order == 'views') ? $sort_img_full : ''),
	'VF_REPLIES_SORT' => (($sort_order == 'replies') ? $sort_img_full : ''),
	'VF_TIME_SORT' => (($sort_order == 'time') ? $sort_img_full : ''),
	'VF_AUTHOR_SORT' => (($sort_order == 'author') ? $sort_img_full : ''),
	'VF_LAST_POST_SORT' => ((($sort_order == 'oldest') || ($sort_order == 'newest')) ? $sort_img_full : ''),

	'L_CURRENT_SORT' => $sort_lang,

	'L_POSTED' => $lang['Posted'],
	'L_JOINED' => $lang['Joined'],
	'L_AUTHOR' => $lang['Author'],
	'L_DESCRIPTION' => $lang['Description'],
	'L_ICON_DESCRIPTION' => $lang['Icon_Description'],
	'L_PERMISSIONS_LIST' => $lang['Permissions_List'],
	'S_AUTH_LIST' => $s_auth_can,
	'S_WATCH_FORUM' => $s_watching_forum,
	'S_TOPIC_TAGS' => !empty($config['display_tags_box']) ? true : false,
	'U_VIEW_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append),
	// UPI2DB - BEGIN
	//'U_MARK_READ' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . '&amp;mark=topics'),
	'MARKED_READ' => $marked_as_read,
	'U_MARK_ALWAYS_READ' => $mark_always_read,
	'U_MARK_READ' => $mark_as_read
	// UPI2DB - END
	)
);
// End header

// MG User Replied - BEGIN
// check if user replied to the topic
define('USER_REPLIED_ICON', true);
$user_topics = $class_topics->user_replied_array($topic_rowset);
// MG User Replied - END

// Okay, let's dump out the page...
if($total_topics)
{
	for($i = 0; $i < $total_topics; $i++)
	{
		$forum_id = $topic_rowset[$i]['forum_id'];
		$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
		$topic_id = $topic_rowset[$i]['topic_id'];
		$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
		$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

		$topic_title_data = $class_topics->generate_topic_title($topic_id, $topic_rowset[$i], $config['last_topic_title_length']);
		$topic_title = $topic_title_data['title'];
		$topic_title_clean = $topic_title_data['title_clean'];
		$topic_title_plain = $topic_title_data['title_plain'];
		$topic_title_label = $topic_title_data['title_label'];
		$topic_title_short = $topic_title_data['title_short'];

		//$news_label = ($topic_rowset[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
		$news_label = '';

		$replies = $topic_rowset[$i]['topic_replies'];
		$topic_type = $topic_rowset[$i]['topic_type'];

		$topic_link = $class_topics->build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_reg'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['poll_start'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies);

		$topic_id = $topic_link['topic_id'];
		$topic_id_append = $topic_link['topic_id_append'];

		// Event Registration - BEGIN
		if (($topic_rowset[$i]['topic_reg']) && check_reg_active($topic_id))
		{
			$regoption_array = array();

			if ($user->data['session_logged_in'] && !$user->data['is_bot'])
			{
				$sql = "SELECT registration_status FROM " . REGISTRATION_TABLE . "
						WHERE topic_id = " . $topic_id . "
						AND registration_user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);

				$reg_user_own_reg = '<span class="text_orange">&bull;</span>';
				if ($regrow = $db->sql_fetchrow($result))
				{
					$status = $regrow['registration_status'];
					if ($status == REG_OPTION1)
					{
						$reg_user_own_reg = '<span class="text_green">&bull;</span>';
					}
					elseif ($status == REG_OPTION2)
					{
						$reg_user_own_reg = '<span class="text_blue">&bull;</span>';
					}
					elseif ($status == REG_OPTION3)
					{
						$reg_user_own_reg = '<span class="text_red">&bull;</span>';
					}
				}

				$db->sql_freeresult($result);
			}

			$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, r.registration_time, r.registration_status FROM " . REGISTRATION_TABLE . " r, " . USERS_TABLE . " u
					WHERE r.topic_id = " . $topic_id . "
					AND r.registration_user_id = u.user_id
					ORDER BY registration_status, registration_time";
			$result = $db->sql_query($sql);
			$reg_info = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$numregs = sizeof($reg_info);
			$option1_count = 0;
			$option2_count = 0;
			$option3_count = 0;

			for ($u = 0; $u < $numregs; $u++)
			{
				if ($reg_info[$u]['registration_status'] == REG_OPTION1)
				{
					$option1_count++;
				}
				elseif ($reg_info[$u]['registration_status'] == REG_OPTION2)
				{
					$option2_count++;
				}
				elseif ($reg_info[$u]['registration_status'] == REG_OPTION3)
				{
					$option3_count++;
				}
			}

			$option1_count = '<span class="text_green">' . (0 + $option1_count) . '</span>';
			array_push($regoption_array, $option1_count);

			$option2_count = '<span class="text_blue">' . (0 + $option2_count) . '</span>';
			array_push($regoption_array, $option2_count);

			$option3_count = '<span class="text_red">' . (0 + $option3_count) . '</span>';
			array_push($regoption_array, $option3_count);

			$regoptions_count = sizeof($regoption_array);

			$v = 0;
			$regoptions = '';
			while ($v < $regoptions_count - 1)
			{
				$regoptions .= $regoption_array[$v] . '-';
				$v++;
			}
			$regoptions .= array_pop($regoption_array);
		}
		// Event Registration - END

		$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

		if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
		{
			$view_topic_url = append_sid(str_replace ('--', '-', make_url_friendly($topic_title) . '-vt' . $topic_id . '.html'));
		}
		else
		{
			$view_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append);
		}

		$topic_author = ($topic_rowset[$i]['user_id'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username'] != '') ? $topic_rowset[$i]['post_username'] : $lang['Guest']) : colorize_username($topic_rowset[$i]['user_id'], $topic_rowset[$i]['username'], $topic_rowset[$i]['user_color'], $topic_rowset[$i]['user_active']);

		if (($user->data['user_level'] != ADMIN) && !empty($topic_rowset[$i]['user_mask']) && empty($topic_rowset[$i]['user_active']))
		{
			$topic_author = $lang['INACTIVE_USER'];
		}

		//$first_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['topic_time'], $config['board_timezone']);
		$first_post_time = create_date_ip($lang['DATE_FORMAT_VF'], $topic_rowset[$i]['topic_time'], $config['board_timezone'], true);

		$last_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['post_time'], $config['board_timezone']);

		$last_post_author = ($topic_rowset[$i]['id2'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username2'] != '') ? $topic_rowset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ') : colorize_username($topic_rowset[$i]['id2'], $topic_rowset[$i]['user2'], $topic_rowset[$i]['user_color2'], $topic_rowset[$i]['user_active2']);

		if (($user->data['user_level'] != ADMIN) && !empty($topic_rowset[$i]['user_mask2']) && empty($topic_rowset[$i]['user_active2']))
		{
			$last_post_author = $lang['INACTIVE_USER'] . ' ';
		}

		// Convert and clean special chars!
		$last_post_url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#p' . $topic_rowset[$i]['topic_last_post_id'] . '" title="' . $topic_title_plain . '"><img src="' . (!empty($topic_link['class_new']) ? $images['icon_newest_reply'] : $images['icon_latest_reply']) . '" alt="' . $lang['View_latest_post'] . '" title="' . $topic_title_plain . ' - ' . $lang['View_latest_post'] . '" /></a>';

//----------------------------------------------------
// UPI2DB - BEGIN
		if($user->data['upi2db_access'])
		{
			$mark_always_read = mark_always_read($topic_rowset[$i]['topic_type'], $topic_id, $forum_id, 'viewforum', 'icon', $user->data['upi2db_unread'], $start, $topic_link['image']);
		}
		else
		{
			$mark_always_read = '<img src="' . $topic_link['image'] . '" style="margin-right: 4px;" alt="' . $topic_link['image_alt'] . '" title="' . $topic_link['image_alt'] . '" />';
		}
// UPI2DB - END
//----------------------------------------------------

		$views = $topic_rowset[$i]['topic_views'];

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$calendar_title = '';
		$calendar_title = get_calendar_title($topic_rowset[$i]['topic_calendar_time'], $topic_rowset[$i]['topic_calendar_duration']);
		// Convert and clean special chars!
		// We shouldn't need this...
		//$calendar_title = htmlspecialchars_clean($calendar_title);
		if (!empty($calendar_title))
		{
			//$calendar_title = '</a></span>' . $calendar_title . '<span class="topiclink">';
			$calendar_title = '<span class="gensmall">' . $calendar_title . '</span>';
		}
		//$topic_title .= $calendar_title;

		if ($forum_row['auth_rate'] != -1)
		{
			$rating2 = sprintf("%.1f", round(($topic_rowset[$i]['topic_rating']), 0) / 2);
		}

		$template->assign_block_vars('topicrow', array(
			'ROW_CLASS' => $row_class,
			'FORUM_ID' => $forum_id,
			'TOPIC_ID' => $topic_id,
			'TOPIC_FOLDER_IMG' => $topic_link['image'],
			'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
			'TOPIC_AUTHOR' => $topic_author,
			'TOPIC_TITLE' => $topic_title,
			'TOPIC_TITLE_PLAIN' => $topic_title_plain,
			'TOPIC_TYPE' => $topic_link['type'],
			'TOPIC_TYPE_ICON' => $topic_link['icon'],
			'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
			'CLASS_NEW' => $topic_link['class_new'],
			'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
			'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
			'TOPIC_RATING' => (!empty($rating2) ? $rating2 : ''),
			'CALENDAR_TITLE' => $calendar_title,
			'GOTO_PAGE' => $topic_pagination['base'],
			'GOTO_PAGE_FULL' => $topic_pagination['full'],
			'REPLIES' => $replies,
			'VIEWS' => $views,
			'LIKES' => $topic_rowset[$i]['topic_likes'],
			'FIRST_POST_TIME' => $first_post_time,
			'LAST_POST_TIME' => $last_post_time,
			'LAST_POST_AUTHOR' => $last_post_author,
			'LAST_POST_IMG' => $last_post_url,
			'L_NEWS' => $news_label,
			// Event Registration - BEGIN
			'REG_OPTIONS' => $regoptions,
			'REG_USER_OWN_REG' => $reg_user_own_reg,
			// Event Registration - END
//--------------------------------------------------------
// UPI2DB - BEGIN
			'U_MARK_ALWAYS_READ' => $mark_always_read,
// UPI2DB - END
//--------------------------------------------------------
			'U_VIEW_TOPIC' => $view_topic_url
			)
		);

		if ($forum_row['auth_rate'] != -1)
		{
			$template->assign_block_vars('topicrow.rate_switch_msg', array());
		}

		if (array_key_exists($i, $dividers))
		{
			$template->assign_block_vars('topicrow.divider', array(
				'L_DIV_HEADERS' => $dividers[$i])
			);
		}

		// Event Registration - BEGIN
		if (($topic_rowset[$i]['topic_reg']) && check_reg_active($topic_rowset[$i]['topic_id']))
		{
			$template->assign_block_vars('topicrow.display_reg', array());
		}
		// Event Registration - END

		if (!empty($topic_rowset[$i]['topic_desc']) && $config['show_topic_description'])
		{
			$topic_desc = censor_text($topic_rowset[$i]['topic_desc']);
			// Convert and clean special chars!
			$topic_desc = htmlspecialchars_clean($topic_desc);
			// SMILEYS IN TITLE - BEGIN
			if (($config['smilies_topic_title'] == true) && !$lofi)
			{
				$bbcode->allow_smilies = ($config['allow_smilies'] && $topic_rowset[$i]['enable_smilies'] ? true : false);
				$topic_desc = $bbcode->parse_only_smilies($topic_desc);
			}
			// SMILEYS IN TITLE - END
			$template->assign_block_vars('topicrow.switch_topic_desc', array(
				'TOPIC_DESCRIPTION' => $topic_desc
				)
			);
		}

		if ($i == 0)
		{
			$viewforum_banner_text = get_ad('vfx');
			if (!empty($viewforum_banner_text))
			{
				$template->assign_vars(array(
					'VIEWFORUM_BANNER_CODE_IMG' => '<img src="' . $images['topic_hot_unread'] . '" style="margin-right: 4px;" alt="Sponsor" title="Sponsor" />',
					'VIEWFORUM_BANNER_CODE' => $viewforum_banner_text,
					)
				);
				$template->assign_block_vars('topicrow.switch_viewforum_banner', array());
			}
		}

	}

	$topics_count -= $total_announcements;
	$number_of_page = (ceil($topics_count / $config['topics_per_page']) == 0) ? 1 : ceil($topics_count / $config['topics_per_page']);

	if ($topics_count > (10 * $config['topics_per_page']))
	{
		$template->assign_var('S_EXTENDED_PAGINATION', true);
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . $kb_mode_append . '&amp;topicdays=' . $topic_days . '&amp;start_letter=' . $start_letter . '&amp;sort_order=' . $sort_order . '&amp;sort_dir=' . $sort_dir, $topics_count, $config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), $number_of_page),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);
}
else
{
	// No topics
	$no_topics_msg = ($forum_row['forum_status'] == FORUM_LOCKED) ? $lang['Forum_locked'] : $lang['No_topics_post_one'];
	$template->assign_vars(array(
		'L_NO_TOPICS' => $no_topics_msg
		)
	);

	$template->assign_block_vars('switch_no_topics', array());
}

// Should the news banner be shown?
if($config['xs_show_news'])
{
	include(IP_ROOT_PATH . 'includes/xs_news.' . PHP_EXT);
	$template->assign_block_vars('switch_show_news', array());
}

// Topics Sorting - BEGIN
if (($config['show_alpha_bar'] == 1) && ($forum_row['forum_sort_box'] == 1))
{
	// Begin Configuration Section
	// Change this to whatever you want the divider to be. Be sure to keep both apostrophies.
	$divider = ' &bull; ';
	$divider_letters = ' ';
	// End Configuration Section

	// Do not change anything below this line.
	$total_letters_count = sizeof($letters_array);
	$this_letter_number = 0;

	$template->assign_vars(array(
		'S_SHOW_ALPHA_BAR' => true,
		'DIVIDER' => $divider,
		'U_NEWEST' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=newest&amp;topicdays=' . $topic_days . $kb_mode_append),
		'U_OLDEST' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=oldest&amp;topicdays=' . $topic_days . $kb_mode_append),
		'U_AZ' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=AZ&amp;topicdays=' . $topic_days . $kb_mode_append),
		'U_ZA' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=ZA&amp;topicdays=' . $topic_days . $kb_mode_append),
		)
	);

	foreach ($letters_array as $letter)
	{
		$this_letter_number++;
		$template->assign_block_vars('alphabetical_sort', array(
			'LETTER' => $letter,
			'U_LETTER' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append . '&amp;start_letter=' . $letter . '&amp;topicdays=' . $topic_days . $kb_mode_append),
			'DIVIDER' => ($this_letter_number != $total_letters_count) ? $divider_letters : '',
			)
		);
	}
}
// Topics Sorting - END

$viewforum_banner_top = get_ad('vft');
$viewforum_banner_bottom = get_ad('vfb');
$template->assign_vars(array(
	'VIEWFORUM_BANNER_TOP' => $viewforum_banner_top,
	'VIEWFORUM_BANNER_BOTTOM' => $viewforum_banner_bottom,
	)
);

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>