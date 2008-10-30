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

// CTracker_Ignore: File checked by human
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

define('IN_VIEWFORUM', true);
// Start initial var setup
if (isset($_GET[POST_FORUM_URL]) || isset($_POST[POST_FORUM_URL]))
{
	$forum_id = (isset($_GET[POST_FORUM_URL])) ? intval($_GET[POST_FORUM_URL]) : intval($_POST[POST_FORUM_URL]);
}
elseif (isset($_GET['forum']))
{
	$forum_id = intval($_GET['forum']);
}
else
{
	$forum_id = '';
}

$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');

if (isset($_GET['selected_id']) || isset($_POST['selected_id']))
{
	$selected_id = isset($_POST['selected_id']) ? $_POST['selected_id'] : $_GET['selected_id'];
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
		redirect(append_sid(IP_ROOT_PATH . FORUM_MG . $parm));
		exit;
	}
}

if (isset($_GET['mark']) || isset($_POST['mark']))
{
	$mark_read = (isset($_POST['mark'])) ? $_POST['mark'] : $_GET['mark'];
}
else
{
	$mark_read = '';
}
// End initial var setup

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$kb_mode = false;
$kb_mode_append = '';
$kb_mode_append_red = '';
if ((!empty($_GET['kb']) || !empty($_POST['kb'])) && ($userdata['bot_id'] == false))
{
	$kb_mode = true;
	$kb_mode_append = '&amp;kb=true';
	$kb_mode_append_red = '&kb=true';
}

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$page_number = (isset($_GET['page_number']) ? intval($_GET['page_number']) : (isset($_POST['page_number']) ? intval($_POST['page_number']) : false));
$page_number = ($page_number < 1) ? false : $page_number;

$start = (!$page_number) ? $start : (($page_number * $board_config['topics_per_page']) - $board_config['topics_per_page']);

//<!-- BEGIN Unread Post Information to Database Mod -->
if ($userdata['upi2db_access'])
{
	$params = array('always_read' => 'always_read', POST_FORUM_URL => 'f', POST_TOPIC_URL => 't', POST_POST_URL => 'p', 'do' => 'do', 'tt' => 'tt');
	while(list($var, $param) = @each($params))
	{
		if (!empty($_POST[$param]) || !empty($_GET[$param]))
		{
			$$var = (!empty($_POST[$param])) ? $_POST[$param] : $_GET[$param];
		}
		else
		{
			$$var = '';
		}
	}
	$forum_id_append = ((!empty($f) && empty($forum_id_append)) ? (POST_FORUM_URL . '=' . $f) : $forum_id_append);
	$topic_id_append = (!empty($t) ? (POST_TOPIC_URL . '=' . $t) : '');
	$post_id_append = (!empty($p) ? (POST_POST_URL . '=' . $p) : '');

	$unread = unread();
	$except_time = except_time();

	if($do || $always_read)
	{
		if($do)
		{
			$mark_read_text = set_unread($t, $f, $p, $unread, $do, $tt);
		}

		if($always_read)
		{
			$mark_read_text = always_read($t, $always_read, $unread);
		}

		$redirect_url = append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append);
		meta_refresh(3, $redirect_url);

		$message = $mark_read_text . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?'. $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
//<!-- END Unread Post Information to Database Mod -->

$cms_page_id = '2';
$cms_page_name = 'viewf';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

// Force Topic Read - BEGIN
$active ='';

if ($board_config['disable_ftr'] == 0)
{
	$viewed_mode = $_GET['mode'];
	$check_viewed = GetUsersView($userdata['user_id']);
	$install_time = time();
	$bypass = '';
	$q = "SELECT active, effected, install_date FROM " . FORCE_READ_TABLE;
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);
	$db->sql_freeresult($r);
	$active = $row['active'];
	$effected = $row['effected'];
	$ins_date = $row['install_date'];
}
if (($active) && (strlen($ins_date) != 10))
{
	$q = "UPDATE " . FORCE_READ_TABLE . " SET install_date = '" . $install_time . "'";
	$r = $db -> sql_query($q);
}

if (strlen($ins_date) != 10)
{
	$ins_date = $install_time;
}

if (($viewed_mode == 'reading') || ($check_viewed != 'false'))
{
	$bypass = true;
}

if (!$active)
{
	$bypass = true;
}
elseif ($active && ($check_viewed == 'false') && !$bypass)
{
	if ($viewed_mode == 'read_this')
	{
		$q = "SELECT topic_number, message FROM " . FORCE_READ_TABLE;
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);
		$db->sql_freeresult($r);
		$ftr_topic = $row['topic_number'];
		$msg = $row['message'];
		InsertReadTopic($userdata['user_id']);
		redirect(append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $ftr_topic . $kb_mode_append_red . '&mode=reading'), true);
	}
	else
	{
		if ((($check_viewed == 'false') && ($effected <> 1) && ($ins_date <= $userdata['user_regdate'])) || (($check_viewed == 'false') && ($effected == '1')))
		{
			include_once(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
			$q = "SELECT * FROM " . FORCE_READ_TABLE;
			$r = $db -> sql_query($q);
			$row = $db -> sql_fetchrow($r);
			$db->sql_freeresult($r);
			$ftr_topic = $row['topic_number'];
			$msg = $row['message'];
			$lng_msg = '<br /><br />' . sprintf($lang['Click_read_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $ftr_topic . $kb_mode_append . '&amp;mode=read_this') . '">', '</a>');
			message_die(GENERAL_ERROR, $msg . $lng_msg, 'Error');
			include_once(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
		}
		else
		{
			$bypass = true;
		}
	}
}
// Force Topic Read - END

// Sort Topics - BEGIN
$letters_array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$start_letter = (isset($_GET['start_letter'])) ? $_GET['start_letter'] : '';
$start_letter = (in_array($start_letter, $letters_array) ? $start_letter : '');

$asort_order = (isset($_GET['asort_order'])) ? $_GET['asort_order'] : '';

if ($asort_order == 'AZ')
{
	$sort_order = "t.topic_title ASC";
}
elseif ($asort_order == 'ZA')
{
	$sort_order = "t.topic_title DESC";
}
elseif ($asort_order == 'oldest')
{
	$sort_order = "t.topic_last_post_id ASC";
}
else
{
	$sort_order = "t.topic_last_post_id DESC";
}

if (!in_array($start_letter, $letters_array))
{
	$start_letter = '';
	$start_letter_sql = '';
}
else // we have a single letter, so lets sort alphabetically...
{
	$sort_order = "topic_title ASC";
	$start_letter_sql = "AND t.topic_title LIKE '" . $start_letter . "%'";
}
// Sort Topics - END

if ($bypass)
{
	// get the forum row
	//
	// Check if the user has actually sent a forum ID with his/her request
	// If not give them a nice error page.
	//

	$forum_row = $tree['data'][$tree['keys'][POST_FORUM_URL . $forum_id]];
	if (empty($forum_row))
	{
		message_die(GENERAL_MESSAGE, 'Forum_not_exist');
	}

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
						WHERE forum_id=$forum_id";
			if (!$db->sql_query($sql)) message_die(GENERAL_ERROR, 'Could not increment forum hits information', '', __LINE__, __FILE__, $sql);
			cache_tree(true);
		}

		// prepare url
		$url = $tree['data'][$CH_this]['forum_link'];
		if ($tree['data'][$CH_this]['forum_link_internal'])
		{
			$part = explode('?', $url);
			$url .= ((count($part) > 1) ? '&' : '?') . 'sid=' . $userdata['session_id'];
			$url = append_sid($url);

			// redirect to url
			redirect($url);
		}

		// Redirect via an HTML form for PITA webservers
		if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
		{
			header('Refresh: 0; URL=' . $url);
			echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="0; url=' . $url . '"><title>' . $lang['Redirect'] . '</title></head><body><div align="center">' . sprintf($lang['Rediect_to'], '<a href="' . $url . '">', '</a>') . '</div></body></html>';
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
		if (!$userdata['session_logged_in'])
		{
			$redirect = $forum_id_append . $kb_mode_append_red . ((isset($start)) ? '&start=' . $start : '');
			redirect(append_sid(LOGIN_MG . '?redirect=' . VIEWFORUM_MG . '&' . $redirect, true));
		}

		// The user is not authed to read this forum ...
		$message = (!$is_auth['auth_view']) ? $lang['Forum_not_exist'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);

		message_die(GENERAL_MESSAGE, $message);
	}
	// End of auth check

	// Handle marking posts
	if ($mark_read == 'topics')
	{
		if ($userdata['session_logged_in'])
		{
			//<!-- BEGIN Unread Post Information to Database Mod -->
			if(!$userdata['upi2db_access'])
			{
			//<!-- END Unread Post Information to Database Mod -->

				$sql = "SELECT MAX(post_time) AS last_post
					FROM " . POSTS_TABLE . "
					WHERE forum_id = $forum_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_f']) : array();
					$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_t']) : array();

					if ((count($tracking_forums) + count($tracking_topics)) >= 150 && empty($tracking_forums[$forum_id]))
					{
						asort($tracking_forums);
						unset($tracking_forums[key($tracking_forums)]);
					}

					if ($row['last_post'] > $userdata['user_lastvisit'])
					{
						$tracking_forums[$forum_id] = time();
						setcookie($board_config['cookie_name'] . '_f', serialize($tracking_forums), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
					}
				}
			//<!-- BEGIN Unread Post Information to Database Mod -->
			}
			else
			{
				marking_posts($forum_id);
			}
			//<!-- END Unread Post Information to Database Mod -->

			$redirect_url = append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append);
			meta_refresh(3, $redirect_url);
		}

		$message = $lang['Topics_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append) . '">', '</a> ');
		message_die(GENERAL_MESSAGE, $message);
	}
	// End handle marking posts

	$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_t']) : '';
	$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_f']) : '';

	// Do the forum Prune
	if ($is_auth['auth_mod'] && $board_config['prune_enable'])
	{
		if ($forum_row['prune_next'] < time() && $forum_row['prune_enable'])
		{
			include(IP_ROOT_PATH . 'includes/prune.' . PHP_EXT);
			require(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
			auto_prune($forum_id);
		}
	}
	// End of forum prune

	// GET GROUP COLORS - BEGIN
	$group_color = array();
	$sql_gc = "SELECT g.group_id, g.group_color
						FROM " . GROUPS_TABLE . " as g
						WHERE g.group_color <> ''";
	if (!($result_gc = $db->sql_query($sql_gc, false, 'groups_colors_')))
	{
		message_die(GENERAL_ERROR, 'Could not obtain groups color', '', __LINE__, __FILE__, $sql_gc);
	}

	while ($row_gc = $db->sql_fetchrow($result_gc))
	{
		$group_color[$row_gc['group_id']] = ($row_gc['group_color'] != '') ? ' style="font-weight:bold;text-decoration:none;color:' . $row_gc['group_color'] . ';"' : ' style="font-weight:bold;text-decoration:none;"';
	}
	$db->sql_freeresult($result_gc);
	// GET GROUP COLORS - END

	//
	// Obtain list of moderators of each forum
	// First users, then groups ... broken into two queries
	//
	// moderators list
	$moderators = array();
	$idx = $tree['keys'][ POST_FORUM_URL . $forum_id ];
	for ($i = 0; $i < count($tree['mods'][$idx]['user_id']); $i++)
	{
		$moderators[] = colorize_username($tree['mods'][$idx]['user_id'][$i]);
	}
	for ($i = 0; $i < count($tree['mods'][$idx]['group_id']); $i++)
	{
		$moderators[] = '<a href="' . append_sid('./groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $tree['mods'][$idx]['group_id'][$i]) . '"' . $group_color[$tree['mods'][$idx]['group_id'][$i]] . '>' . $tree['mods'][$idx]['group_name'][$i] . '</a>';
	}

	$l_moderators = (count($moderators) == 1) ? $lang['Moderator'] : $lang['Moderators'];
	$forum_moderators = (count($moderators)) ? implode(', ', $moderators) : $lang['None'];
	unset($moderators);

	// Forum notification MOD - BEGIN
	// Is user watching this forum?
	if($userdata['session_logged_in'])
	{
		($forum_row['forum_notify'] == '1') ? ($can_watch_forum = true) : ($can_watch_forum = false);

		$sql = "SELECT notify_status
		FROM " . FORUMS_WATCH_TABLE . "
		WHERE forum_id = $forum_id
			AND user_id = " . $userdata['user_id'] . "
		LIMIT 1";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain forum watch information", "", __LINE__, __FILE__, $sql);
		}

		if($row = $db->sql_fetchrow($result))
		{
			if(isset($_GET['unwatch']))
			{
				if($_GET['unwatch'] == 'forum')
				{
					$is_watching_forum = 0;

					$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
						WHERE forum_id = $forum_id
							AND user_id = " . $userdata['user_id'];
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Couldn\'t delete forum watch information', '', __LINE__, __FILE__, $sql);
					}
				}

				$redirect_url = append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start);
				meta_refresh(3, $redirect_url);

				$message = $lang['No_longer_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$is_watching_forum = true;

				if($row['notify_status'])
				{
					$sql = "UPDATE " . FORUMS_WATCH_TABLE . "
						SET notify_status = 0
						WHERE forum_id = $forum_id
							AND user_id = " . $userdata['user_id'];
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Couldn\'t update forum watch information', '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}
		else
		{
			if(isset($_GET['watch']))
			{
				if($_GET['watch'] == 'forum')
				{
					$is_watching_forum = true;

					$sql = "INSERT INTO " . FORUMS_WATCH_TABLE . " (user_id, forum_id, notify_status)
						VALUES (" . $userdata['user_id'] . ", $forum_id, 0)";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, "Couldn't insert forum watch information", "", __LINE__, __FILE__, $sql);
					}
				}

				$redirect_url = append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start);
				meta_refresh(3, $redirect_url);

				$message = $lang['You_are_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start) . '">', '</a>');
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
		if(isset($_GET['unwatch']))
		{
			if($_GET['unwatch'] == 'forum')
			{
				header('Location: ' . append_sid(LOGIN_MG . '?redirect=' . VIEWFORUM_MG . '&' . $forum_id_append . $kb_mode_append_red . '&unwatch=forum', true));
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
	$previous_days_text = array($lang['All_Topics'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

	if (!empty($_POST['topicdays']) || !empty($_GET['topicdays']))
	{
		$topic_days = (!empty($_POST['topicdays'])) ? intval($_POST['topicdays']) : intval($_GET['topicdays']);
		$min_topic_time = time() - ($topic_days * 86400);

		$sql = "SELECT COUNT(t.topic_id) AS forum_topics
			FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
			WHERE t.forum_id = $forum_id
				AND p.post_id = t.topic_last_post_id
				AND p.post_time >= $min_topic_time";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain limited topics count information', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		$topics_count = ($row['forum_topics']) ? $row['forum_topics'] : 1;
		$limit_topics_time = "AND p.post_time >= $min_topic_time";

		if (!empty($_POST['topicdays']))
		{
			$start = 0;
		}
	}
	else
	{
		// Sort Topics - BEGIN
		if (!empty($start_letter))
		{
			$sql = "SELECT COUNT(topic_id) AS forum_topics
				FROM " . TOPICS_TABLE . "
				WHERE forum_id = '" . $forum_id . "'
					AND topic_title LIKE '" . $start_letter . "%'
				ORDER BY " . $sort_order;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get topic counts for letter search', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			$topics_count = ($row['forum_topics']) ? $row['forum_topics'] : 1;
			$db->sql_freeresult($result);
		}
		else
		{
		// Sort Topics - END
			$topics_count = ($forum_row['forum_topics']) ? $forum_row['forum_topics'] : 1;
		// Sort Topics - BEGIN
		}
		// Sort Topics - END
		$limit_topics_time = '';
		$topic_days = 0;
	}

	$select_topic_days = '<select name="topicdays">';
	for($i = 0; $i < count($previous_days); $i++)
	{
		$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
		$select_topic_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
	}
	$select_topic_days .= '</select>';

	//<!-- BEGIN Unread Post Information to Database Mod -->
	if(!$userdata['upi2db_access'])
	{
	//<!-- END Unread Post Information to Database Mod -->

		// All GLOBAL announcement data, this keeps GLOBAL announcements on each viewforum page...
		$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username
						FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2
						WHERE t.topic_poster = u.user_id
							AND p.post_id = t.topic_last_post_id
							AND p.poster_id = u2.user_id
							AND t.topic_type = " . POST_GLOBAL_ANNOUNCE . "
							" . $start_letter_sql . "
						ORDER BY " . $sort_order;
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain topic information", "", __LINE__, __FILE__, $sql);
		}

		$topic_rowset = array();
		$total_announcements = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$topic_rowset[] = $row;
			$total_announcements++;
		}

		$db->sql_freeresult($result);
	// End add - Global announcement MOD
	//<!-- BEGIN Unread Post Information to Database Mod -->
	//}
	//if(!$userdata['upi2db_access'])
	//{
	//<!-- END Unread Post Information to Database Mod -->
		// All announcement data, this keeps announcements on each viewforum page...
		$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username
						FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2
						WHERE t.forum_id = $forum_id
							AND t.topic_poster = u.user_id
							AND p.post_id = t.topic_last_post_id
							AND p.poster_id = u2.user_id
							AND t.topic_type = " . POST_ANNOUNCE . "
							" . $start_letter_sql . "
						ORDER BY " . $sort_order;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			$topic_rowset[] = $row;
			$total_announcements++;
		}

		$db->sql_freeresult($result);

//<!-- BEGIN Unread Post Information to Database Mod -->
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
//<!-- END Unread Post Information to Database Mod -->

//<!-- BEGIN Unread Post Information to Database Mod MODIFY -->
//add , p2.post_edit_time
//change *t.forum_id = $forum_id* to *$upi2db_post_global_announce*
//change *AND t.topic_type <> " . POST_ANNOUNCE . " AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE . "* to *$upi2db_post_announce*

	// Grab all the basic data (all topics except announcements) for this forum
	// Self AUTH - BEGIN
	//$self_sql = (intval($is_auth['auth_read']) == AUTH_SELF) ? " AND t.topic_poster = '" . $userdata['user_id'] . "'" : '';
	$self_sql = (intval($is_auth['auth_read']) == AUTH_SELF) ? " AND (t.topic_poster = '" . $userdata['user_id'] . "' OR t.topic_type = '" . POST_GLOBAL_ANNOUNCE . "' OR t.topic_type = '" . POST_ANNOUNCE . "' OR t.topic_type = '" . POST_STICKY . "')" : '';
	// Self AUTH - END
	$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time, p2.post_edit_time, p.enable_bbcode, p.enable_html, p.enable_smilies
					FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
					WHERE $upi2db_post_global_announce
						AND t.topic_poster = u.user_id
						AND p.post_id = t.topic_first_post_id
						AND p2.post_id = t.topic_last_post_id
						AND u2.user_id = p2.poster_id
						$self_sql
						$upi2db_post_announce
						$start_letter_sql
					ORDER BY t.topic_type DESC, " . $sort_order . "
					LIMIT $start, " . $board_config['topics_per_page'];
// UPI2DB DELETE
//#AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE . "
//<!-- END Unread Post Information to Database Mod -->

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
	}

	$cached2 = $db->cached;
	$total_topics = 0;
//<!-- BEGIN Unread Post Information to Database Mod -->
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

	if($userdata['upi2db_access'])
	{
		if($board_config['upi2db_edit_topic_first'])
		{
			while($row = $db->sql_fetchrow($result))
			{
				if(isset($unread['edit_topics']) && isset($unread['always_read']['topics']) && in_array($row['topic_id'], $unread['edit_topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
				{
					$topic_rowset_gae[] = $row;
					$total_announcements++;
				}
				elseif(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
				{
					$topic_rowset_gan[] = $row;
					$total_announcements++;
				}
				elseif(isset($unread['edit_topics']) && isset($unread['always_read']['topics']) && in_array($row['topic_id'], $unread['edit_topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_ANNOUNCE)
				{
					$topic_rowset_ae[] = $row;
					$total_announcements++;
				}
				elseif(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_ANNOUNCE)
				{
					$topic_rowset_an[] = $row;
					$total_announcements++;
				}
				elseif(isset($unread['edit_topics']) && isset($unread['always_read']['topics']) && in_array($row['topic_id'], $unread['edit_topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_STICKY)
				{
					$topic_rowset_se[] = $row;
					$total_topics++;
				}
				elseif(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_STICKY)
				{
					$topic_rowset_sn[] = $row;
					$total_topics++;
				}
				elseif(isset($unread['edit_topics']) && isset($unread['always_read']['topics']) && in_array($row['topic_id'], $unread['edit_topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] != POST_STICKY && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_GLOBAL_ANNOUNCE)
				{
					$topic_rowset_ne[] = $row;
					$total_topics++;
				}
				elseif(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] != POST_STICKY && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_GLOBAL_ANNOUNCE)
				{
					$topic_rowset_nn[] = $row;
					$total_topics++;
				}
				if(in_array($row['topic_id'], $unread['always_read']['topics']))
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
				if(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
				{
					$topic_rowset_gan[] = $row;
					$total_announcements++;
				}
				elseif(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] == POST_ANNOUNCE)
				{
					$topic_rowset_an[] = $row;
					$total_announcements++;
				}
				elseif(isset($unread['always_read']['topics']) && !in_array($row['topic_id'], $unread['always_read']['topics']) && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_GLOBAL_ANNOUNCE)
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
//<!-- END Unread Post Information to Database Mod -->
	$db->sql_freeresult($result);

	if($cached1 || $cached2)
	{
		$update_list = array();
		for($i = 0; $i < count($topic_rowset); $i++)
		{
			$update_list[] = $topic_rowset[$i]['topic_id'];
		}
		if(count($update_list))
		{
			$sql = "SELECT topic_id, topic_views FROM " . TOPICS_TABLE . " WHERE topic_id IN (" . implode(', ', $update_list) . ")";
			$list = array();
			$result = $db->sql_query($sql);
			while($row = $db->sql_fetchrow($result))
			{
				$list[$row['topic_id']] = $row['topic_views'];
			}
			$db->sql_freeresult($result);
			for($i = 0; $i < count($topic_rowset); $i++)
			{
				if(isset($list[$topic_rowset[$i]['topic_id']]))
				{
					$topic_rowset[$i]['topic_views'] = $list[$topic_rowset[$i]['topic_id']];
				}
			}
			unset($list);
		}
	}

	// Total topics ...
	$total_topics += $total_announcements;
	$dividers = get_dividers($topic_rowset);
	// Define censored word matches
	if (!$userdata['user_allowswearywords'])
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}

	// Post URL generation for templating vars
	$template->assign_vars(array(
		'L_DISPLAY_TOPICS' => $lang['Display_topics'],
		'U_POST_NEW_TOPIC' => append_sid(POSTING_MG . '?mode=newtopic&amp;' . $forum_id_append),
		'S_SELECT_TOPIC_DAYS' => $select_topic_days,
		'S_POST_DAYS_ACTION' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;start=' . $start . '&amp;start_letter=' . $start_letter . '&amp;asort_order=' . $asort_order)
		)
	);

	// User authorisation levels output
	// Self AUTH - BEGIN
	$lang['Rules_reply_can'] = ((intval($is_auth['auth_reply']) == AUTH_SELF) ? $lang['Rules_reply_can_own'] : $lang['Rules_reply_can']);
	// Self AUTH - END
	$s_auth_can = ($is_auth['auth_post'] ? $lang['Rules_post_can'] : $lang['Rules_post_cannot']) . '<br />';
	$s_auth_can .= ($is_auth['auth_reply'] ? $lang['Rules_reply_can'] : $lang['Rules_reply_cannot']) . '<br />';
	$s_auth_can .= ($is_auth['auth_edit'] ? $lang['Rules_edit_can'] : $lang['Rules_edit_cannot']) . '<br />';
	$s_auth_can .= ($is_auth['auth_delete'] ? $lang['Rules_delete_can'] : $lang['Rules_delete_cannot']) . '<br />';
	$s_auth_can .= ($is_auth['auth_vote'] ? $lang['Rules_vote_can'] : $lang['Rules_vote_cannot']) . '<br />';
	if (intval($attach_config['disable_mod']) == 0)
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
		$s_auth_can .= sprintf($lang['Rules_moderate'], '<a href="modcp.' . PHP_EXT . '?' . $forum_id_append . '&amp;start=' . $start . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');
	}

	// Mozilla navigation bar
	$nav_links['up'] = array(
		'url' => append_sid(FORUM_MG),
		'title' => sprintf($lang['Forum_Index'], $board_config['sitename']
		)
	);


	// Start add - Forum notification MOD
	$s_watching_forum = "";

	if($can_watch_forum)
	{
		if($is_watching_forum)
		{
			$s_watching_forum = '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;unwatch=forum&amp;start=' . $start) . '">' . $lang['Stop_watching_forum'] . '</a>';
			$s_watching_forum_img = (isset($images['Forum_un_watch'])) ? '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;unwatch=forum&amp;start=' . $start) . '"><img src="' . $images['Forum_un_watch'] . '" alt="' . $lang['Stop_watching_forum'] . '" title="' . $lang['Stop_watching_forum'] . '" border="0"></a>' : '';
		}
		else
		{
			$s_watching_forum = '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;watch=forum&amp;start=' . $start) . '">' . $lang['Start_watching_forum'] . '</a>';
			$s_watching_forum_img = (isset($images['Forum_watch'])) ? '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;watch=forum&amp;start=' . $start) . '"><img src="' . $images['Forum_watch'] . '" alt="' . $lang['Stop_watching_forum'] . '" title="' . $lang['Start_watching_forum'] . '" border="0"></a>' : '';
		}
	}
	// End add - Forum notification MOD

	//<!-- BEGIN Unread Post Information to Database Mod -->
	if($userdata['upi2db_access'])
	{
		if(!in_array($forum_id, $unread['always_read']['forums']))
		{
			$mark_as_read = '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;mark=topics') . '">' . $lang['Mark_all_topics'] . '</a>';
			$marked_as_read = '&nbsp;';
			$mark_always_read = '<a href="' . append_sid(FORUM_MG . '?forum_id=' . $forum_id . $kb_mode_append . '&amp;always_read=set') . '">' . $lang['upi2db_always_read_forum_short'] . '</a>';
		}
		else
		{
			$mark_as_read = '';
			$marked_as_read = $lang['upi2db_forum_is_always_read'];
			$mark_always_read = '<a href="' . append_sid(FORUM_MG . '?forum_id=' . $forum_id . $kb_mode_append . '&amp;always_read=unset') . '">' . $lang['upi2db_always_read_forum_unset_short'] . '</a>';
		}
	}
	else
	{
		$mark_always_read = '';
		$marked_as_read = '';
		$mark_as_read = '<a href="' . append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;mark=topics') . '">' . $lang['Mark_all_topics'] . '</a>';
	}
	//<!-- END Unread Post Information to Database Mod -->

	// Dump out the page header and load viewforum template

	$forum_row['forum_name'] = get_object_lang(POST_FORUM_URL . $forum_id, 'name');
	define('SHOW_ONLINE', true);
	if (!$board_config['board_disable'] || ($board_config['board_disable'] && ($userdata['user_level'] == ADMIN)))
	{
		$template->vars['S_TPL_FILENAME'] = 'index';
	}

	//$template->assign_block_vars('google_ad', array());
	$page_title = $forum_row['forum_name'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	if ($kb_mode == true)
	{
		$template->set_filenames(array('body' => 'viewforum_kb_body.tpl'));
	}
	else
	{
		$template->set_filenames(array('body' => 'viewforum_body.tpl'));
	}

	make_jumpbox(VIEWFORUM_MG);

	if ($forum_row['rules_in_viewforum'])
	{
		$template->assign_block_vars('switch_forum_rules', array());
		// display a title on top of the box??
		if ($forum_row['rules_display_title'])
		{
			$template->assign_block_vars('switch_forum_rules.switch_display_title', array());
		}
	}
	display_index(POST_FORUM_URL . $forum_id);

	if ($forum_row['auth_rate'] != -1)
	{
		$template->assign_block_vars('rating_switch', array());
	}
	//BBcode Parsing for Olympus rules Start
	$rules_bbcode = $forum_row['forum_rules'];
	$bbcode->allow_html = true;
	$bbcode->allow_bbcode = true;
	$bbcode->allow_smilies = true;
	$rules_bbcode = $bbcode->parse($rules_bbcode);
	//BBcode Parsing for Olympus rules Start

	if ($board_config['forum_wordgraph'] == 1)
	{
		include(IP_ROOT_PATH . 'includes/forum_wordgraph.' . PHP_EXT);
	}

	$template->assign_vars(array(
		'FORUM_ID' => $forum_id,
		'FORUM_NAME' => $forum_row['forum_name'],
		'FORUM_RULES' => $rules_bbcode,
		'MODERATORS' => $forum_moderators,
		'POST_IMG' => ($forum_row['forum_status'] == FORUM_LOCKED) ? $images['post_locked'] : $images['post_new'],

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
		//<!-- BEGIN Unread Post Information to Database Mod -->
		'FOLDER_AR' => $images['topic_ar_read'],
		'L_AR_POSTS' => $lang['always_read_icon'],
		//<!-- END Unread Post Information to Database Mod -->
		'FOLDER_GLOBAL_ANNOUNCE_IMG' => $images['topic_glo_read'],
		'FOLDER_GLOBAL_ANNOUNCE_NEW_IMG' => $images['topic_glo_unread'],

		'L_TOPICS' => $lang['Topics'],
		'L_FORUM_RULES' => (empty($forum_row['rules_custom_title'])) ? $lang['Forum_Rules'] : $forum_row['rules_custom_title'],
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

		'L_POSTED' => $lang['Posted'],
		'L_JOINED' => $lang['Joined'],
		'L_AUTHOR' => $lang['Author'],
		'L_DESCRIPTION' => $lang['Description'],
		'L_ICON_DESCRIPTION' => $lang['Icon_Description'],
		'L_PERMISSIONS_LIST' => $lang['Permissions_List'],
		'S_AUTH_LIST' => $s_auth_can,
		'S_WATCH_FORUM' => $s_watching_forum,
		'U_VIEW_FORUM' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append),
		//<!-- BEGIN Unread Post Information to Database Mod -->
		//'U_MARK_READ' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . '&amp;mark=topics'),
		'MARKED_READ' => $marked_as_read,
		'U_MARK_ALWAYS_READ' => $mark_always_read,
		'U_MARK_READ' => $mark_as_read
		//<!-- END Unread Post Information to Database Mod -->
		)
	);
	// End header

	// MG User Replied - BEGIN
	// check if user replied to the topics
	define('USER_REPLIED_ICON', true);
	$user_topics = user_replied_array($topic_rowset);
	// MG User Replied - END

	// Okay, lets dump out the page...
	if($total_topics)
	{
		for($i = 0; $i < $total_topics; $i++)
		{
			$forum_id = $topic_rowset[$i]['forum_id'];
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id = $topic_rowset[$i]['topic_id'];
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

			$topic_title = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];
			$topic_title_prefix = (empty($topic_rowset[$i]['title_compl_infos'])) ? '' : $topic_rowset[$i]['title_compl_infos'] . ' ';
			$topic_title = $topic_title_prefix . $topic_title;
			$topic_title_plain = $topic_title;
			if (($board_config['smilies_topic_title'] == true) && !$lofi)
			{
				//Start BBCode Parsing for title
				$bbcode->allow_html = false;
				$bbcode->allow_bbcode = false;
				$bbcode->allow_smilies = ($board_config['allow_smilies'] && $topic_rowset[$i]['enable_smilies'] ? true : false);
				$topic_title = $bbcode->parse($topic_title, '', true);
				//End BBCode Parsing for title
			}

			//$news_label = ($topic_rowset[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
			$news_label = '';

			$replies = $topic_rowset[$i]['topic_replies'];
			$topic_type = $topic_rowset[$i]['topic_type'];

			$topic_link = build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['topic_vote'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies, $unread);

			$topic_id = $topic_link['topic_id'];
			$topic_id_append = $topic_link['topic_id_append'];

			if(($replies + 1) > $board_config['posts_per_page'])
			{
				$total_pages = ceil(($replies + 1) / $board_config['posts_per_page']);
				$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': ';

				$times = 1;
				for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
				{
					$goto_page .= '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $j) . '">' . $times . '</a>';
					if(($times == 1) && ($total_pages > 4))
					{
						$goto_page .= ' ... ';
						$times = $total_pages - 3;
						$j += ($total_pages - 4) * $board_config['posts_per_page'];
					}
					elseif ($times < $total_pages)
					{
						$goto_page .= ', ';
					}
					$times++;
				}
				$goto_page .= ' ] ';
			}
			else
			{
				$goto_page = '';
			}

			//$view_topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append);
			if ($kb_mode == true)
			{
				$view_topic_url =  append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;kb=true');
			}
			else
			{
				if (($board_config['url_rw'] == '1') || (($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS)))
				{
					$view_topic_url = append_sid(str_replace ('--', '-', make_url_friendly($topic_title) . '-vt' . $topic_id . '.html'));
				}
				else
				{
					$view_topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append);
				}
			}

			$topic_author = ($topic_rowset[$i]['user_id'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username'] != '') ? $topic_rowset[$i]['post_username'] : $lang['Guest']) : colorize_username($topic_rowset[$i]['user_id']);
			$topic_author .= ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? '' : '';

			//$first_post_time = create_date2($board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);
			$first_post_time = create_date_simple($lang['DATE_FORMAT_VF'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);

			$last_post_time = create_date2($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);

			$last_post_author = ($topic_rowset[$i]['id2'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username2'] != '') ? $topic_rowset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ') : colorize_username($topic_rowset[$i]['id2']);

			$last_post_url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#p' . $topic_rowset[$i]['topic_last_post_id'] . '" title="' . $topic_title_plain . '"><img src="' . (!empty($topic_link['class_new']) ? $images['icon_newest_reply'] : $images['icon_latest_reply']) . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

//----------------------------------------------------
//<!-- BEGIN Unread Post Information to Database Mod -->
			if($userdata['upi2db_access'])
			{
				$mark_always_read = mark_always_read($topic_rowset[$i]['topic_type'], $topic_id, $forum_id, 'viewforum', 'icon', $unread, $start, $topic_link['image']);
			}
			else
			{
				$mark_always_read = '<img src="' . $topic_link['image'] . '" style="margin-right:4px;" alt="' . $topic_link['image_alt'] . '" title="' . $topic_link['image_alt'] . '" />';
			}
//<!-- END Unread Post Information to Database Mod -->
//----------------------------------------------------

			$views = $topic_rowset[$i]['topic_views'];

			$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$calendar_title = '';
			$calendar_title = get_calendar_title($topic_rowset[$i]['topic_calendar_time'], $topic_rowset[$i]['topic_calendar_duration']);
			if (!empty($calendar_title))
			{
				//$calendar_title = '</a></span>' . $calendar_title . '<span class="topictitle">';
				$calendar_title = '<span class="gensmall">' . $calendar_title . '</span>';
			}
			//$topic_title .= $calendar_title;

			if ($forum_row['auth_rate'] != -1)
			{
				$rating2 = sprintf("%.1f", round(($topic_rowset[$i]['topic_rating']), 0) / 2);
			}

			$template->assign_block_vars('topicrow', array(
				'ROW_COLOR' => $row_color,
				'ROW_CLASS' => $row_class,
				'FORUM_ID' => $forum_id,
				'TOPIC_ID' => $topic_id,
				'TOPIC_FOLDER_IMG' => $topic_link['image'],
				'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
				'TOPIC_AUTHOR' => $topic_author,
				'TOPIC_TITLE' => $topic_title,
				'TOPIC_TYPE' => $topic_link['type'],
				'TOPIC_TYPE_ICON' => $topic_link['icon'],
				'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
				'CLASS_NEW' => $topic_link['class_new'],
				'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
				'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
				'TOPIC_RATING' => $rating2,
				'CALENDAR_TITLE' => $calendar_title,
				//'GOTO_PAGE' => $goto_page,
				'GOTO_PAGE' => (($goto_page == '') ? '' : '<span class="gotopage">' . $goto_page . '</span>'),
				'REPLIES' => $replies,
				'VIEWS' => $views,
				'FIRST_POST_TIME' => $first_post_time,
				'LAST_POST_TIME' => $last_post_time,
				'LAST_POST_AUTHOR' => $last_post_author,
				'LAST_POST_IMG' => $last_post_url,
				'L_NEWS' => $news_label,
//--------------------------------------------------------
//<!-- BEGIN Unread Post Information to Database Mod -->
				'U_MARK_ALWAYS_READ' => $mark_always_read,
//<!-- END Unread Post Information to Database Mod -->
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

			if (!empty($topic_rowset[$i]['topic_desc']) && $board_config['show_topic_description'])
			{
				$topic_desc = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_desc']) : $topic_rowset[$i]['topic_desc'];
				if ($board_config['smilies_topic_title'] == '1')
				{
					$topic_desc = $bbcode->parse($topic_desc, '', true);
				}

				$template->assign_block_vars('topicrow.switch_topic_desc', array(
					'TOPIC_DESCRIPTION' => $topic_desc
					)
				);
			}
		}

		$topics_count -= $total_announcements;
		$number_of_page = (ceil($topics_count / $board_config['topics_per_page']) == 0) ? 1 : ceil($topics_count / $board_config['topics_per_page']);

		if ($topics_count > (10 * $board_config['topics_per_page']))
		{
			$template->assign_block_vars('extended_pagination', array());
		}

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(VIEWFORUM_MG . '?' . $forum_id_append . $kb_mode_append . '&amp;topicdays=' . $topic_days . '&amp;start_letter=' . $start_letter . '&amp;asort_order=' . $asort_order, $topics_count, $board_config['topics_per_page'], $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['topics_per_page']) + 1), $number_of_page),
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
	include(IP_ROOT_PATH . 'includes/xs_news.' . PHP_EXT);
	if($xs_news_config['xs_show_news'])
	{
		$template->assign_block_vars('switch_show_news', array());
	}

	// Sort Topics - BEGIN
	if ($board_config['show_alpha_bar'])
	{
		// Begin Configuration Section
		// Change this to whatever you want the divider to be. Be sure to keep both apostrophies.
		$divider = ' &bull ';
		$divider_letters = ' ';
		// End Configuration Section

		// Do not change anything below this line.
		$total_letters_count = count($letters_array);
		$this_letter_number = 0;

		$template->assign_vars(array(
			'S_SHOW_ALPHA_BAR' => true,
			'DIVIDER' => $divider,
			'U_NEWEST' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . '&amp;start_letter=&amp;asort_order=newest&amp;topicdays=' . $topic_days . $kb_mode_append),
			'U_OLDEST' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . '&amp;start_letter=&amp;asort_order=oldest&amp;topicdays=' . $topic_days . $kb_mode_append),
			'U_AZ' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . '&amp;start_letter=&amp;asort_order=AZ&amp;topicdays=' . $topic_days . $kb_mode_append),
			'U_ZA' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . '&amp;start_letter=&amp;asort_order=ZA&amp;topicdays=' . $topic_days . $kb_mode_append),
			)
		);

		foreach ($letters_array as $letter)
		{
			$this_letter_number++;
			$template->assign_block_vars('alphabetical_sort', array(
				'LETTER' => $letter,
				'U_LETTER' => append_sid(VIEWFORUM_MG . '?' . $forum_id_append . '&amp;start_letter=' . $letter . '&amp;topicdays=' . $topic_days . $kb_mode_append),
				'DIVIDER' => ($this_letter_number != $total_letters_count) ? $divider_letters : '',
				)
			);
		}
	}
	// Sort Topics - END

	// Parse the page and print
	$template->pparse('body');

	// Page footer
	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}

?>