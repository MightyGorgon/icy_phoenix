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

define('IN_TOPIC', true);
// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_VIEWTOPIC', true);
// MG Cash MOD For IP - END
define('CT_SECLEVEL', 'MEDIUM');
$ct_ignoregvar = array('');
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_delete.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_rate.' . PHP_EXT);
// Event Registration - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_events_reg.' . PHP_EXT);
// Event Registration - END

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

setup_extra_lang(array('lang_rate'));

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

$sort_days_array = array(0, 1, 7, 14, 30, 90, 180, 365);
$sort_days_lang_array = array(0 => $lang['ALL_POSTS'], 1 => $lang['1_DAY'], 7 => $lang['7_DAYS'], 14 => $lang['2_WEEKS'], 30 => $lang['1_MONTH'], 90 => $lang['3_MONTHS'], 180 => $lang['6_MONTHS'], 365 => $lang['1_YEAR']);
$sort_key_array = array('t', 's', 'a');
$sort_key_lang_array = array('t' => $lang['POST_TIME'], 's' => $lang['SUBJECT'], 'a' => $lang['AUTHOR']);
// In Icy Phoenix we still prefer sorting by time instead by ID... it could lead to collateral problems I know...
//$sort_key_sql_array = array('t' => 'p.post_id', 's' => 'p.post_subject', 'a' => 'u.username_clean');
$sort_key_sql_array = array('t' => 'p.post_time', 's' => 'p.post_subject', 'a' => 'u.username_clean');
$sort_dir_array = array('a', 'd');
$sort_dir_lang_array = array('a' => $lang['ASCENDING'], 'd' => $lang['DESCENDING']);
$sort_dir_sql_array = array('a' => 'ASC', 'd' => 'DESC');

$default_sort_days = (!empty($user->data['user_post_show_days'])) ? $user->data['user_post_show_days'] : $sort_days_array[0];
$default_sort_key = (!empty($user->data['user_post_sortby_type'])) ? $user->data['user_post_sortby_type'] : $sort_key_array[0];
$default_sort_dir = (!empty($user->data['user_post_sortby_dir'])) ? $user->data['user_post_sortby_dir'] : $sort_dir_array[0];

$sort_days = request_var('st', $default_sort_days);
$sort_days = check_var_value($sort_days, $sort_days_array);
$sort_key = request_var('sk', $default_sort_key);
$sort_key = check_var_value($sort_key, $sort_key_array);
$sort_key_sql = $sort_key_sql_array[$sort_key];
$sort_dir = strtolower(request_var('sd', $default_sort_dir));
$sort_dir = check_var_value($sort_dir, $sort_dir_array);
$sort_dir_sql = $sort_dir_sql_array[$sort_dir];

// Backward compatibility
if (check_http_var_exists('postorder', true))
{
	$sort_dir_array_old = array('asc', 'desc');
	$sort_dir = strtolower(request_var('postorder', $sort_dir_array_old[0]));
	$sort_dir = check_var_value($sort_dir, $sort_dir_array_old);
	$sort_dir = ($sort_dir == 'asc') ? 'a' : 'd';
	$sort_dir_sql = $sort_dir_sql_array[$sort_dir];
}

if (check_http_var_exists('postdays', true))
{
	$sort_days = request_var('postdays', $default_sort_days);
	$sort_days = check_var_value($sort_days, $sort_days_array);
}

$vt_sort_append_array = array();
if ($sort_days != $sort_days_array[0])
{
	$vt_sort_append_array['st'] = $sort_days;
}
if ($sort_key != $sort_key_array[0])
{
	$vt_sort_append_array['sk'] = $sort_key;
}
if ($sort_dir != $sort_dir_array[0])
{
	$vt_sort_append_array['sd'] = $sort_dir;
}

$vt_sort_append = '';
$vt_sort_append_red = '';
if (!empty($vt_sort_append_array))
{
	foreach ($vt_sort_append_array as $k => $v)
	{
		$vt_sort_append = '&amp;' . $k . '=' . $v;
		$vt_sort_append_red = '&' . $k . '=' . $v;
	}
}

$select_post_array = array('st' => 'sort_days', 'sk' => 'sort_key', 'sd' => 'sort_dir');
$select_post_array_output = array();
foreach ($select_post_array as $s_key => $s_name)
{
	$select_post_array_output[$s_key] = '<select name="' . $s_key . '">';
	foreach (${$s_name . '_lang_array'} as $k => $v)
	{
		$selected = (${$s_name} == $k) ? ' selected="selected"' : '';
		$select_post_array_output[$s_key] .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
	}
	$select_post_array_output[$s_key] .= '</select>';
	${'select_' . $s_name} = $select_post_array_output[$s_key];
}

$sid = request_var('sid', '');

// Activity - BEGIN
if (!empty($config['plugins']['activity']['enabled']))
{
	include_once(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);
	$q = "SELECT * FROM " . INA_HOF;
	$r = $db->sql_query($q);
	$hof_data = $db->sql_fetchrowset($r);
	$db->sql_freeresult($r);
}
// Activity - END

// Start initial var setup
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

$download = request_get_var('download', '');

if (empty($topic_id) && empty($post_id))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

// Find topic id if user requested a newer or older topic
$view = request_get_var('view', '');
if (!empty($view) &&  empty($post_id))
{
	if ($view == 'newest')
	{
		if (isset($_COOKIE[$config['cookie_name'] . '_sid']) || !empty($sid))
		{
			$session_id = isset($_COOKIE[$config['cookie_name'] . '_sid']) ? $_COOKIE[$config['cookie_name'] . '_sid'] : $sid;
			if (!preg_match('/^[A-Za-z0-9]*$/', $session_id))
			{
				$session_id = '';
			}

			if ($session_id)
			{
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p, " . SESSIONS_TABLE . " s,  " . USERS_TABLE . " u
					WHERE s.session_id = '" . $db->sql_escape($session_id) . "'
						AND u.user_id = s.session_user_id
						AND p.topic_id = '" . $topic_id . "'
						AND p.post_time >= u.user_lastvisit
					ORDER BY p.post_time ASC
					LIMIT 1";
				$result = $db->sql_query($sql);

/* UPI2DB REPLACE
				if (!($row = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
				}
*/
//<!-- BEGIN Unread Post Information to Database Mod -->
				if (!($row = $db->sql_fetchrow($result)))
				{
					if ($topic_id > 0)
					{
						redirect(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red);
					}
					else
					{
						message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
					}
				}
//<!-- END Unread Post Information to Database Mod -->

				$post_id = $row['post_id'];
				$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
				$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');

				$session_id_append = !empty($sid) ? ('sid=' . $session_id . '&') : '';
				redirect(append_sid(CMS_PAGE_VIEWTOPIC . '?' . $session_id_append . $kb_mode_append_red . $forum_id_append . '&' . $topic_id_append . '&' . $post_id_append . $post_id_append_url));
			}
		}

		redirect(append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red, true));
	}
	elseif (($view == 'next') || ($view == 'previous'))
	{
		$sql_condition = ($view == 'next') ? '>' : '<';
		$sql_ordering = ($view == 'next') ? 'ASC' : 'DESC';

		$sql = "SELECT t.topic_id, t.forum_id
			FROM " . TOPICS_TABLE . " t, " . TOPICS_TABLE . " t2
			WHERE
				t2.topic_id = '" . $topic_id . "'
				AND t.forum_id = t2.forum_id
				AND t.topic_moved_id = 0
				AND t.deleted = 0
				AND t.topic_last_post_id $sql_condition t2.topic_last_post_id
			ORDER BY t.topic_last_post_id $sql_ordering
			LIMIT 1";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$forum_id = intval($row['forum_id']);
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id = intval($row['topic_id']);
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			redirect(append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red));
		}
		else
		{
			$message = ($view == 'next') ? 'No_newer_topics' : 'No_older_topics';
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

// This rather complex gaggle of code handles querying for topics but also allows for direct linking to a post (and the calculation of which page the post is on and the correct display of viewtopic)
$join_sql_table = (!$post_id ? '' : (", " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2 "));
$join_sql = (!$post_id ? ("t.topic_id = " . $topic_id) : ("p.post_id = " . $post_id . " AND t.topic_id = p.topic_id AND p2.topic_id = p.topic_id AND p2.post_id <= " . $post_id));
$count_sql = (!$post_id ? '' : (", COUNT(p2.post_id) AS prev_posts"));

$order_sql = (!$post_id ? '' : ("GROUP BY p.post_id, t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.poll_start, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.auth_ban, f.auth_greencard, f.auth_bluecard ORDER BY p.post_id ASC"));

// Let's try to query all fields for topics and forums... it should not require too much resources as we are querying only one row
//$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.poll_start, t.topic_last_post_id, t.title_compl_infos, t.topic_first_post_id, t.topic_calendar_time, t.topic_calendar_duration, t.topic_reg, t.topic_similar_topics, f.forum_name, f.forum_status, f.forum_id, f.forum_thanks, f.forum_similar_topics, f.forum_topic_views, f.forum_kb_mode, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.forum_rules, f.auth_ban, f.auth_greencard, f.auth_bluecard, fr.*" . $count_sql . "
$sql = "SELECT t.*, f.*, fr.*" . $count_sql . "
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . FORUMS_RULES_TABLE . " fr" . $join_sql_table . "
	WHERE $join_sql
		AND f.forum_id = t.forum_id
		AND fr.forum_id = t.forum_id
		$order_sql";
attach_setup_viewtopic_auth($order_sql, $sql);
$result = $db->sql_query($sql);

if (!($forum_topic_data = $db->sql_fetchrow($result)))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}
$db->sql_freeresult($result);

$forum_id = intval($forum_topic_data['forum_id']);
$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
$topic_id = intval($forum_topic_data['topic_id']);
$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');

$forum_name = get_object_lang(POST_FORUM_URL . $forum_id, 'name');
$topic_title = $forum_topic_data['topic_title'];
$topic_title_prefix = (empty($forum_topic_data['title_compl_infos'])) ? '' : $forum_topic_data['title_compl_infos'] . ' ';
$topic_time = $forum_topic_data['topic_time'];
$topic_first_post_id = intval($forum_topic_data['topic_first_post_id']);
$topic_calendar_time = intval($forum_topic_data['topic_calendar_time']);
$topic_calendar_duration = intval($forum_topic_data['topic_calendar_duration']);

$meta_content = array();
$meta_content = $class_topics->meta_content_init($forum_topic_data, 'topic');
$meta_content['post_id'] = (!empty($post_id) && (intval($post_id) > 0)) ? intval($post_id) : 0;

$this_forum_auth_read = intval($forum_topic_data['auth_read']);

if ($forum_topic_data['forum_kb_mode'])
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

// Thanks Mod - BEGIN
$show_thanks = false;
if (empty($config['disable_thanks_topics']) && !empty($forum_topic_data['forum_thanks']) && !$user->data['is_bot'])
{
	$show_thanks = true;
	$show_thanks_button = false;
	if ($user->data['session_logged_in'])
	{
		$sql_thanked = "SELECT topic_id
				FROM " . THANKS_TABLE . "
				WHERE topic_id = " . $topic_id . "
					AND user_id = " . $user->data['user_id'] . "
				LIMIT 1";
		$result_thanked = $db->sql_query($sql_thanked);
		if (!$has_thanked = $db->sql_fetchrow($result_thanked))
		{
			$show_thanks_button = true;
		}
		$db->sql_freeresult($result_thanked);
	}
}
// Thanks Mod - END

// Set or remove bookmark - BEGIN
$setbm = request_var('setbm', '');
$removebm = request_var('removebm', '');
if ((!empty($setbm) || !empty($removebm)) && !$user->data['is_bot'])
{
	$redirect = CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red . '&start=' . $start . $vt_sort_append_red . '&highlight=' . urlencode($_GET['highlight']);
	if ($user->data['session_logged_in'])
	{
		if (!empty($setbm))
		{
			set_bookmark($topic_id);
		}
		elseif (!empty($removebm))
		{
			remove_bookmark($topic_id);
		}
	}
	else
	{
		if (!empty($setbm))
		{
			$redirect .= '&setbm=true';
		}
		elseif (!empty($removebm))
		{
			$redirect .= '&removebm=true';
		}
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . $redirect, true));
	}
	redirect(append_sid($redirect, true));
}
// Set or remove bookmark - END

$cms_page['page_id'] = 'viewtopic';
// Comment out page_nav because viewtopic has its own breadcrumbs...
//$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

if ($download)
{
	@include(IP_ROOT_PATH . 'includes/topic_download.' . PHP_EXT);
	exit;
}

//Begin Lo-Fi Mod
if (!empty($lofi))
{
	$lang['Reply_with_quote'] = $lang['quote_lofi'] ;
	$lang['Edit_delete_post'] = $lang['edit_lofi'];
	$lang['View_IP'] = $lang['ip_lofi'];
	$lang['Delete_post'] = $lang['del_lofi'];
	$lang['Read_profile'] = $lang['profile_lofi'];
	$lang['Send_private_message'] = $lang['pm_lofi'];
	$lang['Send_email'] = $lang['email_lofi'];
	$lang['Visit_website'] = $lang['website_lofi'];
	$lang['ICQ'] = $lang['icq_lofi'];
	$lang['AIM'] = $lang['aim_lofi'];
	$lang['YIM'] = $lang['yim_lofi'];
	$lang['MSNM'] = $lang['msnm_lofi'];
}
//End Lo-Fi Mod

// Force Topic Read - BEGIN
$ftr_disabled = $config['ftr_disable'] ? true : false;
if (!$ftr_disabled)
{
	@include(IP_ROOT_PATH . 'includes/topic_ftr.' . PHP_EXT);
}
// Force Topic Read - END

$similar_topics_enabled = false;
if ($config['similar_topics'] && $forum_topic_data['forum_similar_topics'])
{
	$similar_topics_enabled = true;
}

if ($similar_topics_enabled)
{
	$similar_forums_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);
	$similar_is_auth = $similar_forums_auth[$forum_id];
}

// Start auth check
$is_auth = array();
$is_auth = $tree['auth'][POST_FORUM_URL . $forum_id];


if (!($is_auth['auth_mod']) && ($forum_topic_data['deleted']))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

if (!$is_auth['auth_read'])
{
	if (!$user->data['session_logged_in'])
	{
		$redirect = $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red;
		$redirect .= ($post_id) ? '&' . $post_id_append : '';
		$redirect .= ($start) ? '&start=' . $start : '';
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_VIEWTOPIC . '&' . $redirect, true));
	}
	$message = sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);
	message_die(GENERAL_MESSAGE, $message);
}
// End auth check

// Who viewed a topic - BEGIN
if (!$config['disable_topic_view'] && $forum_topic_data['forum_topic_views'])
{
	$user_id = $user->data['user_id'];
	$sql = 'UPDATE ' . TOPIC_VIEW_TABLE . ' SET topic_id = "' . $topic_id . '", view_time = "' . time() . '", view_count = view_count + 1 WHERE topic_id=' . $topic_id . ' AND user_id = ' . $user_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result || !$db->sql_affectedrows())
	{
		$sql = 'INSERT IGNORE INTO ' . TOPIC_VIEW_TABLE . ' (topic_id, user_id, view_time, view_count)
			VALUES (' . $topic_id . ', "' . $user_id . '", "' . time() . '", "1")';
		$db->sql_query($sql);
	}
}
// Who viewed a topic - END

if (!empty($post_id))
{
	$start = floor(($forum_topic_data['prev_posts'] - 1) / intval($config['posts_per_page'])) * intval($config['posts_per_page']);
}

// Is user watching this thread?
$watch = request_var('watch', '');
$unwatch = request_var('unwatch', '');
if($user->data['session_logged_in'] && !$user->data['is_bot'])
{
	$can_watch_topic = true;

	$sql = "SELECT notify_status
		FROM " . TOPICS_WATCH_TABLE . "
		WHERE topic_id = " . $topic_id . "
			AND user_id = " . $user->data['user_id'] . "
		LIMIT 1";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		if (!empty($unwatch))
		{
			if ($unwatch == 'topic')
			{
				$is_watching_topic = 0;
				$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
					WHERE topic_id = " . $topic_id . "
						AND user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);
			}

			$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $start . $kb_mode_append);
			meta_refresh(3, $redirect_url);

			$message = $lang['No_longer_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $start . $kb_mode_append) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = true;

			if ($row['notify_status'])
			{
				$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
					SET notify_status = 0
					WHERE topic_id = " . $topic_id . "
						AND user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);
			}
		}
	}
	else
	{
		if (!empty($watch))
		{
			if ($watch == 'topic')
			{
				$is_watching_topic = true;
				$sql = "INSERT INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, forum_id, notify_status)
					VALUES (" . $user->data['user_id'] . ", " . $topic_id . ", " . $forum_id . ", 0)";
				$result = $db->sql_query($sql);
			}

			$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;' . 'start=' . $start);
			meta_refresh(3, $redirect_url);

			$message = $lang['You_are_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;' . '&amp;start=' . $start) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = 0;
		}
	}
}
else
{
	if ($unwatch == 'topic')
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red . '&unwatch=topic', true));
	}
	else
	{
		$can_watch_topic = 0;
		$is_watching_topic = 0;
	}
}

// Generate a 'Show posts in previous x days' select box. If the postdays var is POSTed then get it's value, find the number of topics with dates newer than it (to properly handle pagination) and alter the main query
if(!empty($sort_days))
{
	$start = 0;
	$min_post_time = time() - (intval($sort_days) * 86400);

	$deleted_where = $is_auth['auth_mod'] ? 'AND p.deleted != 2' : ' AND p.deleted = 0';
	$sql = "SELECT COUNT(p.post_id) AS num_posts
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.topic_id = " . $topic_id . "
			AND p.topic_id = t.topic_id
			$deleted_where
			AND p.post_time >= " . $min_post_time;
	$result = $db->sql_query($sql);
	$total_replies = ($row = $db->sql_fetchrow($result)) ? intval($row['num_posts']) : 0;
	$limit_posts_time = "AND p.post_time >= " . $min_post_time . " ";
}
else
{
	$sort_days = 0;
	$total_replies = intval($forum_topic_data['topic_replies']) + 1;
	$limit_posts_time = '';
}

$user_ids = array();
$user_ids2 = array();
if($user->data['session_logged_in'])
{
	$user_ids[$user->data['user_id']] = $user->data['username'];
}
// Custom Profile Fields MOD
$profile_data = get_fields('WHERE view_in_topic = ' . VIEW_IN_TOPIC . ' AND users_can_view = ' . ALLOW_VIEW);
$profile_data_sql = get_udata_txt($profile_data, 'u.');
// END Custom Profile Fields MOD

// Similar Topics - BEGIN
if ($similar_topics_enabled)
{
	$similar_topics = get_similar_topics($similar_forums_auth, $topic_id, $topic_title, $forum_topic_data['topic_similar_topics'], $forum_topic_data['topic_desc']);
	$count_similar = sizeof($similar_topics);

	// Switch again to false because we will show the box only if we have similar topics!
	$similar_topics_enabled = false;
	if ($count_similar > 0)
	{
		$similar_topics_enabled = true;
	}
}
// Similar Topics - END

//if ($config['switch_poster_info_topic'] == true)
// Use the above code if you want even guests to be shown the extra info
if ($config['switch_poster_info_topic'] && $user->data['session_logged_in'] && !$user->data['is_bot'])
{
	$parse_extra_user_info = true;
	// Query Styles
	$styles = $cache->obtain_styles(true);
	foreach ($styles as $k => $v)
	{
		$styles_list_id[] = $k;
		$styles_list_name[] = $v;
	}
}
else
{
	$parse_extra_user_info = false;
}

// Activity - BEGIN
if (!empty($config['plugins']['activity']['enabled']) && !$user->data['is_bot'])
{
	$activity_sql = ', u.user_trophies, u.ina_char_name';
}
else
{
	$activity_sql = '';
}
// Activity - END

// Go ahead and pull all data for this topic
// Self AUTH - BEGIN
$self_sql_tables = (intval($is_auth['auth_read']) == AUTH_SELF) ? ', ' . USERS_TABLE . ' u2' : '';
$self_sql = (intval($is_auth['auth_read']) == AUTH_SELF) ? " AND t.topic_poster = u2.user_id AND (u2.user_id = '" . $user->data['user_id'] . "' OR t.topic_type = '" . POST_GLOBAL_ANNOUNCE . "' OR t.topic_type = '" . POST_ANNOUNCE . "' OR t.topic_type = '" . POST_STICKY . "')" : '';
// Self AUTH - END

$sql = "SELECT u.username, u.user_id, u.user_active, u.user_mask, u.user_color, u.user_posts, u.user_from, u.user_from_flag, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_skype, u.user_regdate, u.user_msnm, u.user_viewemail, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, u.user_allow_viewonline, u.user_session_time, u.user_warnings, u.user_level, u.user_birthday, u.user_next_birthday_greeting, u.user_gender, u.user_personal_pics_count, u.user_style, u.user_lang" . $activity_sql . $profile_data_sql . ", u.ct_miserable_user, p.*, t.topic_poster, t.title_compl_infos, p.deleted
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . TOPICS_TABLE . " t" . $self_sql_tables . "
	WHERE p.topic_id = $topic_id
		AND t.topic_id = p.topic_id
		AND u.user_id = p.poster_id
		AND p.deleted != 2
		" . $limit_posts_time . "
		" . $self_sql . "
	ORDER BY " . $sort_key_sql . " " . $sort_dir_sql . "
	LIMIT " . $start . ", " . $config['posts_per_page'];

// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	$cm_viewtopic->generate_columns($template, $forum_id, $sql);
}
// MG Cash MOD For IP - END

$result = $db->sql_query($sql);

$postrow = array();
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		if($row['user_id'] > 0 && ($is_auth['auth_mod'] || $row['deleted'] == '0'))
		{
			$user_ids[$row['user_id']] = $row['username'];
		}
		$postrow[] = $row;
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);
	if (!($total_posts = sizeof($postrow)))
	{ //it looks like ... But it's not ! (yeah, it's evil.)
		message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
	}
}
else
{
	include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
	sync('topic', $topic_id);
	message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
}

if (($total_posts == 1) && !empty($config['robots_index_topics_no_replies']))
{
	define('ROBOTS_NOINDEX', true);
}

$resync = false;
if (($forum_topic_data['topic_replies'] + 1) < ($start + sizeof($postrow)))
{
	$resync = true;
}
elseif (($start + $config['posts_per_page']) > $forum_topic_data['topic_replies'])
{
	$row_id = intval($forum_topic_data['topic_replies']) % intval($config['posts_per_page']);
	if ($postrow[$row_id]['post_id'] != $forum_topic_data['topic_last_post_id'] || $start + sizeof($postrow) < $forum_topic_data['topic_replies'])
	{
		$resync = true;
	}
}
elseif (sizeof($postrow) < $config['posts_per_page'])
{
	$resync = true;
}

if ($resync)
{
	include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
	sync('topic', $topic_id);

	$sql = 'SELECT COUNT(post_id) AS total FROM ' . POSTS_TABLE . ' WHERE topic_id = ' . $topic_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$total_replies = $row['total'];
}

// Mighty Gorgon - Multiple Ranks - BEGIN
$ranks_array = $cache->obtain_ranks(false);
// Mighty Gorgon - Multiple Ranks - END

$topic_title = censor_text($topic_title);

// Was a highlight request part of the URI?
$highlight_match = '';
$highlight = '';

$highlight_words = request_var('highlight', '');
$highlight_words = htmlspecialchars_decode($highlight_words, ENT_COMPAT);
if (!empty($highlight_words))
{
	$highlight_words = addslashes(preg_replace('#[][\\/%():><{}`]#', ' ', $highlight_words));

	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars($highlight_words)));

	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
		}
	}
	unset($words);

	$highlight = urlencode($highlight_words);
	$highlight_match = rtrim($highlight_match, "\\");
}

// Post, reply and other URL generation for templating vars
$new_topic_url = append_sid('posting.' . PHP_EXT . '?mode=newtopic&amp;' . $forum_id_append);
$reply_topic_url = append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . $forum_id_append . '&amp;' . $topic_id_append);
$view_forum_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . $kb_mode_append);
$view_prev_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;view=previous');
$view_next_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;view=next');

// Begin Thanks Mod
$thank_topic_url = append_sid('posting.' . PHP_EXT . '?mode=thank&amp;' . $forum_id_append . '&amp;' . $topic_id_append);
// End Thanks Mod

// Mozilla navigation bar
//SEO TOOLKIT BEGIN
$nav_links['prev'] = array(
	'url' => $view_prev_topic_url,
	'title' => $lang['View_previous_topic']
);
$nav_links['next'] = array(
	'url' => $view_next_topic_url,
	'title' => $lang['View_next_topic']
);
$nav_links['up'] = array(
	'url' => $view_forum_url,
	'title' => $forum_name
);
//SEO TOOLKIT END

$is_this_locked = (($forum_topic_data['forum_status'] == FORUM_LOCKED) || ($forum_topic_data['topic_status'] == TOPIC_LOCKED)) ? true : false;
$reply_img = $is_this_locked ? $images['reply_locked'] : $images['reply_new'];
$reply_alt = $is_this_locked ? $lang['Topic_locked'] : $lang['Reply_to_topic'];
$post_img = ($forum_topic_data['forum_status'] == FORUM_LOCKED) ? $images['post_locked'] : $images['post_new'];
$post_alt = ($forum_topic_data['forum_status'] == FORUM_LOCKED) ? $lang['Forum_locked'] : $lang['Post_new_topic'];

if(!$user->data['session_logged_in'] || !$is_auth['auth_reply'] || ($is_this_locked && !$is_auth['auth_mod']) || $user->data['is_bot'])
{
	$can_reply = false;
}
else
{
	$can_reply = true;
	$template->assign_var('S_CAN_REPLY', true);
}

// Begin Thanks Mod
$thank_img = $images['thanks'];
$thank_alt = $lang['thanks_alt'];
if ($show_thanks_button && ($postrow[0]['topic_poster'] != $user->data['user_id']))
{
	$template->assign_var('S_THANKS', true);
}
// End Thanks Mod

// Set a cookie for this topic
if ($user->data['session_logged_in'] && !$user->data['is_bot'])
{
	$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();
	$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();

	if (!empty($tracking_topics[$topic_id]) && !empty($tracking_forums[$forum_id]))
	{
		$topic_last_read = ($tracking_topics[$topic_id] > $tracking_forums[$forum_id]) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	elseif (!empty($tracking_topics[$topic_id]) || !empty($tracking_forums[$forum_id]))
	{
		$topic_last_read = (!empty($tracking_topics[$topic_id])) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	else
	{
		$topic_last_read = $user->data['user_lastvisit'];
	}

	if ((sizeof($tracking_topics) >= 150) && empty($tracking_topics[$topic_id]))
	{
		asort($tracking_topics);
		unset($tracking_topics[key($tracking_topics)]);
	}

	$tracking_topics[$topic_id] = time();

	setcookie($config['cookie_name'] . '_t', serialize($tracking_topics), 0, $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure']);
}

//<!-- BEGIN Unread Post Information to Database Mod -->
if($user->data['upi2db_access'])
{
	$unread_new_posts = 0;
	$unread_edit_posts = 0;
	for($i = 0; $i < $total_posts; $i++)
	{
		if (sizeof($unread[$topic_id]['new_posts']) && in_array($postrow[$i]['post_id'], $unread[$topic_id]['new_posts']))
		{
			++$unread_new_posts;
		}
		if (sizeof($unread[$topic_id]['edit_posts']) && in_array($postrow[$i]['post_id'], $unread[$topic_id]['edit_posts']))
		{
			++$unread_edit_posts;
		}
	}
}
//<!-- END Unread Post Information to Database Mod -->

$template_to_parse = ($kb_mode) ? 'viewtopic_kb_body.tpl' : 'viewtopic_body.tpl';
// Needed for attachments... do not remove!
$template->set_filenames(array('body' => $template_to_parse));

make_jumpbox(CMS_PAGE_VIEWFORUM, $forum_id);

// Output page header
if ($config['display_viewonline'])
{
	define('SHOW_ONLINE', true);
}

$topic_title = $topic_title_prefix . $topic_title;
$meta_content['page_title'] = $meta_content['forum_name'] . ' :: ' . $topic_title;
$meta_content['page_title_clean'] = $topic_title;
$template->assign_var('S_VIEW_TOPIC', true);
if ($config['show_icons'] == true)
{
	$template->assign_var('S_SHOW_ICONS', true);
}
else
{
	$template->assign_var('S_SHOW_LINKS', true);
}

if ($similar_topics_enabled)
{
	include(IP_ROOT_PATH . 'includes/similar_topics.' . PHP_EXT);
}

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

$topic_mod = '';

if ($is_auth['auth_mod'])
{
	$s_auth_can .= sprintf($lang['Rules_moderate'], '<a href="modcp.' . PHP_EXT . '?' . $forum_id_append . '&amp;sid=' . $user->data['session_id'] . '">', '</a>');

	// Full string to append as a reference for FORUM TOPIC POST (FTP)
	$full_ftp_append = (($forum_id_append == '') ? '' : ($forum_id_append . '&amp;')) . (($topic_id_append == '') ? '' : ($topic_id_append . '&amp;')) . (($post_id_append == '') ? '' : ($post_id_append . '&amp;'));

	if ($lofi)
	{
		$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=delete&amp;sid=' . $user->data['session_id'] . '" title="' . $lang['Delete_topic'] . '">' . $lang['Delete_topic'] . '</a>&nbsp;&bull;&nbsp;';

		$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=move&amp;sid=' . $user->data['session_id'] . '" title="' . $lang['Move_topic'] . '">' . $lang['Move_topic'] . '</a>&nbsp;<br />';

		$topic_mod .= ($forum_topic_data['topic_status'] == TOPIC_UNLOCKED) ? '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=lock&amp;sid=' . $user->data['session_id'] . '" title="' . $lang['Lock_topic'] . '">' . $lang['Lock_topic'] . '</a>&nbsp;&bull;&nbsp;' : '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=unlock&amp;sid=' . $user->data['session_id'] . '" title="' . $lang['Unlock_topic'] . '">' . $lang['Unlock_topic'] . '</a>&nbsp;::&nbsp;';

		$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=split&amp;sid=' . $user->data['session_id'] . '" title="' . $lang['Split_topic'] . '">' . $lang['Split_topic'] . '</a>&nbsp;';

		$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=merge&amp;sid=' . $user->data['session_id'] . '" title="' . $lang['Merge_topic'] . '">' . $lang['Merge_topic'] . '</a>&nbsp;<br />';
		if ($config['bin_forum'] != false)
		{
			$topic_mod .= '<a href="bin.' . PHP_EXT . '?' . $full_ftp_append . 'sid=' . $user->data['session_id'] . '" title="' . $lang['Move_bin'] . '">' . $lang['Move_bin'] . '</a>&nbsp;';
		}
	}
	else
	{
		if ($config['bin_forum'] != false)
		{
			$topic_mod .= '<span class="img-btn"><a href="bin.' . PHP_EXT . '?' . $full_ftp_append . 'sid=' . $user->data['session_id'] . '"><img src="' . $images['topic_mod_bin'] . '" alt="' . $lang['Move_bin'] . '" title="' . $lang['Move_bin'] . '" /></a></span>&nbsp;';
		}
		$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=delete&amp;sid=' . $user->data['session_id'] . '" ><img src="' . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_topic'] . '" title="' . $lang['Delete_topic'] . '" /></a></span>&nbsp;';

		$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=move&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move_topic'] . '" title="' . $lang['Move_topic'] . '" /></a></span>&nbsp;';

		$topic_mod .= ($forum_topic_data['topic_status'] == TOPIC_UNLOCKED) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=lock&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['topic_mod_lock'] . '" alt="' . $lang['Lock_topic'] . '" title="' . $lang['Lock_topic'] . '" /></a></span>&nbsp;' : '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=unlock&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['topic_mod_unlock'] . '" alt="' . $lang['Unlock_topic'] . '" title="' . $lang['Unlock_topic'] . '" /></a></span>&nbsp;';

		$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=split&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['topic_mod_split'] . '" alt="' . $lang['Split_topic'] . '" title="' . $lang['Split_topic'] . '" /></a></span>&nbsp;';

		$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=merge&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['topic_mod_merge'] . '" alt="' . $lang['Merge_topic'] . '" title="' . $lang['Merge_topic'] . '" /></a></span>&nbsp;<br /><br />';

		$normal_button = '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=normalize&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['normal_post'] . '" alt="' . $lang['Mod_CP_normal'] . '" title="' . $lang['Mod_CP_normal2'] . '" /></a></span>&nbsp;';

		$sticky_button = ($is_auth['auth_sticky']) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=sticky&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['sticky_post'] . '" alt="' . $lang['Mod_CP_sticky'] . '" title="' . $lang['Mod_CP_sticky2'] . '" /></a></span>&nbsp;' : '';

		$announce_button = ($is_auth['auth_announce']) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=announce&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['announce_post'] . '" alt="' . $lang['Mod_CP_announce'] . '" title="' . $lang['Mod_CP_announce2'] . '" /></a></span>&nbsp;' : '';

		$global_button = ($is_auth['auth_globalannounce']) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=super_announce&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images['gannounce_post'] . '" alt="' . $lang['Mod_CP_global'] . '" title="' . $lang['Mod_CP_global2'] . '" /></a></span>&nbsp;' : '';

		switch($forum_topic_data['topic_type'])
		{
			case POST_NORMAL:
				$topic_mod .= $global_button . $announce_button . $sticky_button;
				break;
			case POST_STICKY:
				$topic_mod .= $global_button . $announce_button . $normal_button;
				break;
			case POST_ANNOUNCE:
				$topic_mod .= $global_button . $sticky_button . $normal_button;
				break;
			case POST_GLOBAL_ANNOUNCE:
				$topic_mod .= $announce_button . $sticky_button . $normal_button;
				break;
		}
	}
}

// Topic prefixes
//if (!(($user->data['user_level'] == 0) && ($user->data['user_id'] != $row['topic_poster'])))
if ($is_auth['auth_edit'] || ($user->data['user_id'] == $row['topic_poster']))
{
	$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . " ORDER BY title_info ASC";
	$result = $db->sql_query($sql, 0, 'topics_prefixes_', TOPICS_CACHE_FOLDER);

	$select_title = '<form action="modcp.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '" method="post"><br /><br /><select name="qtnum"><option value="-1">---</option>';
	while ($row = $db->sql_fetchrow($result))
	{
		$addon = str_replace('%mod%', addslashes($user->data['username']), $row['title_info']);
		$dateqt = ($row['date_format'] == '') ? create_date($config['default_dateformat'], time(), $config['board_timezone']) : create_date($row['date_format'], time(), $config['board_timezone']);
		$addon = str_replace('%date%', $dateqt, $addon);
		$select_title .= '<option value="' . $row['id'] . '">' . htmlspecialchars($addon) . '</option>';
	}
	$db->sql_freeresult($result);
	$select_title .= '</select>&nbsp;<input type="submit" name="quick_title_edit" class="liteoption" value="' . $lang['Edit_title'] . '"/><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '"/><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '"/></form>';
	$topic_mod .= $select_title;
}

if ($kb_mode == true)
{
	$s_kb_mode_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;kb=off' . '&amp;start=' . $start);
	$s_kb_mode = '<a href="' . $s_kb_mode_url . '">' . $lang['KB_MODE_OFF'] . '</a>';
	$s_kb_mode_img = (isset($images['topic_kb_off'])) ? '<a href="' . $s_kb_mode_url . '"><img src="' . $images['topic_kb_off'] . '" alt="' . $lang['KB_MODE_OFF'] . '" title="' . $lang['KB_MODE_OFF'] . '" /></a>' : '';
}
else
{
	$s_kb_mode_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;kb=on' . '&amp;start=' . $start);
	$s_kb_mode = '<a href="' . $s_kb_mode_url . '">' . $lang['KB_MODE_ON'] . '</a>';
	$s_kb_mode_img = (isset($images['topic_kb_on'])) ? '<a href="' . $s_kb_mode_url . '"><img src="' . $images['topic_kb_on'] . '" alt="' . $lang['KB_MODE_ON'] . '" title="' . $lang['KB_MODE_ON'] . '" /></a>' : '';
}

// Topic watch information
$s_watching_topic = '';
if ($can_watch_topic)
{
	if ($is_watching_topic)
	{
		$s_watching_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;unwatch=topic&amp;start=' . $start);
		$s_watching_topic = '<a href="' . $s_watching_topic_url . '">' . $lang['Stop_watching_topic'] . '</a>';
		$s_watching_topic_img = (isset($images['topic_un_watch'])) ? '<a href="' . $s_watching_topic_url . '"><img src="' . $images['topic_un_watch'] . '" alt="' . $lang['Stop_watching_topic'] . '" title="' . $lang['Stop_watching_topic'] . '" /></a>' : '';
	}
	else
	{
		$s_watching_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;watch=topic&amp;start=' . $start);
		$s_watching_topic = '<a href="' . $s_watching_topic_url . '">' . $lang['Start_watching_topic'] . '</a>';
		$s_watching_topic_img = (isset($images['topic_watch'])) ? '<a href="' . $s_watching_topic_url . '"><img src="' . $images['topic_watch'] . '" alt="' . $lang['Start_watching_topic'] . '" title="' . $lang['Start_watching_topic'] . '" /></a>' : '';
	}
}

// Bookmark information
if ($user->data['session_logged_in'] && !$user->data['is_bot'])
{
	$template->assign_block_vars('bookmark_state', array());
	// Send vars to template
	if (is_bookmark_set($topic_id))
	{
		$bookmark_img = $images['bookmark_remove'];
		$bm_action = '&amp;removebm=true';
		$set_rem_bookmark = $lang['Remove_Bookmark'];
	}
	else
	{
		$bookmark_img = $images['bookmark_add'];
		$bm_action = '&amp;setbm=true';
		$set_rem_bookmark = $lang['Set_Bookmark'];
	}
	$template->assign_vars(array(
		'L_BOOKMARK_ACTION' => $set_rem_bookmark,
		'IMG_BOOKMARK' => $bookmark_img,
		'U_BOOKMARK_ACTION' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;start=' . $start . $vt_sort_append . '&amp;highlight=' . urlencode($_GET['highlight']) . $bm_action)
		)
	);
}

//<!-- BEGIN Unread Post Information to Database Mod -->
if($user->data['upi2db_access'])
{
	//$mark_always_read = mark_always_read($forum_topic_data['topic_type'], $topic_id, $forum_id, 'viewforum', 'txt', $unread);
	$s_mark_ar = mark_always_read_vt_ip($forum_topic_data['topic_type'], $topic_id, $forum_id, 'txt', $unread);
	$s_mark_ar_img = mark_always_read_vt_ip($forum_topic_data['topic_type'], $topic_id, $forum_id, 'img', $unread);
}
else
{
	$mark_always_read = '';
	$s_mark_ar = '';
	$s_mark_ar_img = '';
}
//<!-- END Unread Post Information to Database Mod -->

if ($total_replies > (10 * $config['posts_per_page']))
{
	$template->assign_var('S_EXTENDED_PAGINATION', true);
}

// If we've got a hightlight set pass it on to pagination,
// I get annoyed when I lose my highlight after the first page.
$pagination = ($highlight != '') ? generate_pagination(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . $vt_sort_append . '&amp;highlight=' . $highlight, $total_replies, $config['posts_per_page'], $start) : generate_pagination(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . $vt_sort_append, $total_replies, $config['posts_per_page'], $start);
$current_page = get_page($total_replies, $config['posts_per_page'], $start);
$watch_topic_url = 'topic_view_users.' . PHP_EXT . '?' . $forum_id_append . '&amp;' . $topic_id_append;

$rules_bbcode = '';
if ($forum_topic_data['rules_in_viewtopic'])
{
	//BBcode Parsing for Olympus rules Start
	$rules_bbcode = $forum_topic_data['rules'];
	$bbcode->allow_html = true;
	$bbcode->allow_bbcode = true;
	$bbcode->allow_smilies = true;
	$rules_bbcode = $bbcode->parse($rules_bbcode);
	//BBcode Parsing for Olympus rules Start

	$template->assign_vars(array(
		'S_FORUM_RULES' => true,
		'S_FORUM_RULES_TITLE' => ($forum_topic_data['rules_display_title']) ? true : false
		)
	);
}

$topic_viewed_link = '';
if (empty($config['disable_topic_view']) && ($forum_topic_data['forum_topic_views'] == 1) && ($user->data['user_level'] == ADMIN))
{
	$topic_viewed_link = append_sid('topic_view_users.' . PHP_EXT . '?' . $forum_id_append . '&amp;' . $topic_id_append);
}

if ($config['show_social_bookmarks'])
{
	$template->assign_block_vars('social_bookmarks', array());
}

if ($config['display_tags_box'])
{
	@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
	$class_topics_tags = new class_topics_tags();
	$topic_tags_links = $class_topics_tags->build_tags_list(array($topic_id));
	$template->assign_vars(array(
		'S_TOPIC_TAGS' => true,
		'TOPIC_TAGS' => $topic_tags_links,
		)
	);
}

$topic_title_enc = urlencode(ip_utf8_decode($topic_title));
// URL Rewrite - BEGIN
// Rewrite Social Bookmars URLs if any of URL Rewrite rules has been enabled
// Forum ID and KB Mode removed from topic_url_enc to avoid compatibility problems with redirects in tell a friend
if (($config['url_rw'] == true) || ($config['url_rw_guests'] == true))
{
	$topic_url_ltt = htmlspecialchars((create_server_url() . make_url_friendly($topic_title) . '-vt' . $topic_id . '.html') . ($kb_mode ? ('?' . $kb_mode_append) : ''));
	$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . make_url_friendly($topic_title) . '-vt' . $topic_id . '.html'));
}
else
{
	$topic_url_ltt = htmlspecialchars(ip_utf8_decode(create_server_url() . CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red));
	$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . CMS_PAGE_VIEWTOPIC . '?' . $topic_id_append));
}
// URL Rewrite - END
// Convert and clean special chars!
$topic_title = htmlspecialchars_clean($topic_title);
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_ID_FULL' => POST_FORUM_URL . $forum_id,
	'FORUM_NAME' => $forum_name,
	'FORUM_RULES' => $rules_bbcode,
	'TOPIC_ID' => $topic_id,
	'TOPIC_ID_FULL' => POST_TOPIC_URL . $topic_id,
	'TOPIC_TITLE' => $topic_title,
	'TOPIC_TITLE_SHORT' => ((strlen($topic_title) > 80) ? substr($topic_title, 0, 80) . '...' : $topic_title),
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / intval($config['posts_per_page'])) + 1), ceil($total_replies / intval($config['posts_per_page']))),

	'POST_IMG' => $post_img,
	'REPLY_IMG' => $reply_img,
	'THANKS_IMG' => $thank_img,
	'IS_LOCKED' => $is_this_locked,

	'TOPIC_TITLE_ENC' => $topic_title_enc,
	'TOPIC_URL_ENC' => $topic_url_enc,
	'TOPIC_URL_LTT' => $topic_url_ltt,
	'L_DOWNLOAD_POST' => $lang['Download_post'],
	'L_DOWNLOAD_TOPIC' => $lang['Download_topic'],
	'DOWNLOAD_TOPIC' => append_sid(CMS_PAGE_VIEWTOPIC . '?download=-1&amp;' . $forum_id_append . '&amp;' . $topic_id_append),
	'U_TELL' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . $topic_title_enc . '&amp;topic_id=' . $topic_id),
	'L_PRINT' => $lang['Print_View'],
	'U_PRINT' => append_sid('printview.' . PHP_EXT . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $start),

	'L_SHARE_TOPIC' => $lang['ShareThisTopic'],
	'L_REPLY_NEWS' => $lang['News_Reply'],
	'L_PRINT_NEWS' => $lang['News_Print'],
	'L_EMAIL_NEWS' => $lang['News_Email'],
	'MINIPOST_IMG' => $images['icon_minipost'],
	'IMG_FLOPPY' => $images['floppy2'],
	'IMG_REPLY' => $images['news_reply'],
	'IMG_RECENT_TOPICS' => $images['recent_topics'],
	'IMG_PRINT' => $images['printer_topic'],
	'IMG_VIEWED' => $images['topic_viewed'],
	'IMG_EMAIL' => $images['email_topic'],
	'IMG_LEFT' => $images['icon_previous'],
	'IMG_RIGHT' => $images['icon_next'],

	'IMG_ARU' => $images['arrow_rounded_up'],
	'IMG_ARR' => $images['arrow_rounded_right'],
	'IMG_ARD' => $images['arrow_rounded_down'],
	'IMG_ARL' => $images['arrow_rounded_left'],

	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_ARTICLE' => $lang['Article'],
	'L_COMMENTS' => $lang['Comments'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_VIEW_NEXT_TOPIC' => $lang['View_next_topic'],
	'L_VIEW_PREVIOUS_TOPIC' => $lang['View_previous_topic'],
	'L_GO_TO_PAGE_NUMBER' => $lang['Go_To_Page_Number'],
	'L_THANKS' => $thank_alt,
	'L_THANKS_ADD_RATE' => $lang['thanks_add_rate'],
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_POST_REPLY_TOPIC' => $reply_alt,
	'L_POST_QUOTE' => $lang['Reply_with_quote'],
	'L_POST_EDIT' => $lang['Edit_delete_post'],
	'L_POST_DELETE' => $lang['Delete_post'],
	'L_TOPIC_VIEWED' => $lang['Topic_view_users'],
	'L_USER_IP' => $lang['View_IP'],
	'L_QUICK_REPLY' => $lang['Quick_Reply'],
	'L_QUICK_QUOTE' => $lang['QuickQuote'],
	'L_OFFTOPIC' => $lang['OffTopic'],
	'L_BACK_TO_TOP' => $lang['Back_to_top'],
	'L_DISPLAY_POSTS' => $lang['Display_posts'],
	'L_LOCK_TOPIC' => $lang['Lock_topic'],
	'L_UNLOCK_TOPIC' => $lang['Unlock_topic'],
	'L_MOVE_TOPIC' => $lang['Move_topic'],
	'L_SPLIT_TOPIC' => $lang['Split_topic'],
	'L_DELETE_TOPIC' => $lang['Delete_topic'],
	'L_GOTO_PAGE' => $lang['Goto_page'],
	'L_FORUM_RULES' => (empty($forum_topic_data['rules_custom_title'])) ? $lang['Forum_Rules'] : $forum_topic_data['rules_custom_title'],
	'L_PERMISSIONS_LIST' => $lang['Permissions_List'],
	'L_TELL' => $lang['TELL_FRIEND'],
	'L_TOPIC_RATING' => $lang['TopicUseful'],
	'L_USER_ALBUM' => $lang['Show_Personal_Gallery'],
	'L_USER_WWW' => $lang['Website'],
	'L_USER_EMAIL' => $lang['Send_Email'],
	'L_USER_PROFILE' => $lang['Profile'],
	'L_PM' => $lang['Private_Message'],

	'L_SMILEYS' => $lang['Emoticons'],
	'L_SMILEYS_MORE' => $lang['More_emoticons'],
	'U_SMILEYS_MORE' => append_sid('posting.' . PHP_EXT . '?mode=smilies'),

	'IMG_QUICK_QUOTE' => $images['icon_quick_quote'],
	'IMG_OFFTOPIC' => $images['icon_offtopic'],

	'S_TOPIC_LINK' => POST_TOPIC_URL,
	'S_SELECT_SORT_DAYS' => $select_sort_days,
	'S_SELECT_SORT_KEY' => $select_sort_key,
	'S_SELECT_SORT_DIR' => $select_sort_dir,
	'S_POST_DAYS_ACTION' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;start=' . $start),
	'S_AUTH_LIST' => $s_auth_can,
	'S_TOPIC_ADMIN' => $topic_mod,
	'IS_KB_MODE' => ($kb_mode == true) ? true : false,
	'S_KB_MODE' => !empty($s_kb_mode) ? $s_kb_mode : '',
	'S_KB_MODE_IMG' => !empty($s_kb_mode_img) ? $s_kb_mode_img : '',
	'S_WATCH_TOPIC' => !empty($s_watching_topic) ? $s_watching_topic : '',
	'S_WATCH_TOPIC_IMG' => !empty($s_watching_topic_img) ? $s_watching_topic_img : '',

	'U_VIEW_TOPIC' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . (!empty($start) ? ('&amp;start=' . $start) : '') . $vt_sort_append . (!empty($highlight) ? ('&amp;highlight=' . $highlight) : '') . (($kb_mode == true) ? '&amp;kb=on' : '')),
	'U_VIEW_TOPIC_BASE' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append),
//<!-- BEGIN Unread Post Information to Database Mod -->
	'U_MARK_ALWAYS_READ' => $mark_always_read,
	'S_MARK_AR' => $s_mark_ar,
	'S_MARK_AR_IMG' => $s_mark_ar_img,
//<!-- END Unread Post Information to Database Mod -->
	'U_TOPIC_VIEWED' => $topic_viewed_link,
	'U_VIEW_FORUM' => $view_forum_url,
	'U_VIEW_OLDER_TOPIC' => $view_prev_topic_url,
	'U_VIEW_NEWER_TOPIC' => $view_next_topic_url,
	'U_THANKS' => $thank_topic_url,
	'U_POST_NEW_TOPIC' => $new_topic_url,
	'U_POST_REPLY_TOPIC' => $reply_topic_url
	)
);

// Does this topic contain a poll?
if (!empty($forum_topic_data['poll_start']))
{
	$class_topics->display_poll($forum_topic_data, false);
}

// Event Registration - BEGIN
include(IP_ROOT_PATH . 'includes/viewtopic_events_reg.' . PHP_EXT);
// Event Registration - END

init_display_post_attachments($forum_topic_data['topic_attachment']);

// Begin Thanks Mod
// Get topic thanks
if ($show_thanks)
{
	// Select Format for the date
	$timeformat = "d F";
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, t.thanks_time
			FROM " . THANKS_TABLE . " t, " . USERS_TABLE . " u
			WHERE t.topic_id = " . $topic_id . "
			AND t.user_id = u.user_id";
	$result = $db->sql_query($sql);
	$total_thank = $db->sql_numrows($result);
	$thanksrow = array();
	if ($fil = $db->sql_fetchrow($result))
	{
		do
		{
			$thanksrow[] = $fil;
		}
		while ($fil = $db->sql_fetchrow($result));
	}
	$db->sql_freeresult($result);
	$thanks = '';
	for($i = 0; $i < $total_thank; $i++)
	{
		// Get thanks date
		$thanks_date[$i] = create_date_ip($timeformat, $thanksrow[$i]['thanks_time'], $config['board_timezone'], true);
		// Make thanker profile link
		$thanks .= (($thanks != '') ? ', ' : '') . colorize_username($thanksrow[$i]['user_id'], $thanksrow[$i]['username'], $thanksrow[$i]['user_color'], $thanksrow[$i]['user_active']) . ' (' . $thanks_date[$i] . ')';
	}

	$sql = "SELECT t.topic_poster, u.user_id, u.username, u.user_active, u.user_color
			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
			WHERE t.topic_id = " . $topic_id . "
				AND t.topic_poster = u.user_id
			LIMIT 1";
	$result = $db->sql_query($sql);

	$author = array();
	if ($fil = $db->sql_fetchrow($result))
	{
		$author[] = $fil;
	}
	$db->sql_freeresult($result);

	$author_name = colorize_username($author[0]['user_id'], $author[0]['username'], $author[0]['user_color'], $author[0]['user_active']);

	$thanks2 = $lang['thanks_to'] . ' ' . $author_name . $lang['thanks_end'];
}
// End Thanks Mod

if ($config['enable_quick_quote'])
{
	$template->assign_block_vars('switch_quick_quote', array());
}

$sig_cache = array();
$delnote = isset($_GET['delnote']) ? explode('.', $_GET['delnote']) : array();
$this_year = create_date('Y', time(), $config['board_timezone']);
$this_date = create_date('md', time(), $config['board_timezone']);

// Mighty Gorgon - POSTS LIKES - BEGIN
$posts_like_enabled = false;
if (empty($config['disable_likes_posts']) && $forum_topic_data['forum_likes'] && !$user->data['is_bot'])
{
	$posts_like_enabled = true;
	$posts_list = array();
	for($i = 0; $i < $total_posts; $i++)
	{
		$posts_list[] = $postrow[$i]['post_id'];
	}
	$topic_posts_likes = $class_topics->topic_posts_likes_get(array('topic_id' => $topic_id), $posts_list);
}
// Mighty Gorgon - POSTS LIKES - END

// Mighty Gorgon - Feedback - BEGIN
$feedback_disabled = true;
if (!empty($config['plugins']['feedback']['enabled']) && !empty($config['plugins']['feedback']['dir']))
{
	include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['feedback']['dir'] . 'common.' . PHP_EXT);
	$feedback_allowed_forums = explode(',', PLUGINS_FEEDBACK_FORUMS);
	$feedback_disabled = false;
	if (!in_array($forum_id, $feedback_allowed_forums))
	{
		$feedback_disabled = true;
	}
}
// Mighty Gorgon - Feedback - END

// Okay, let's do the loop, yeah come on baby let's do the loop and it goes like this ...
$ip_display_auth = ip_display_auth($user->data, true);
for($i = 0; $i < $total_posts; $i++)
{
	$this_poster_mask = false;
	if (($user->data['user_level'] != ADMIN) && !empty($postrow[$i]['user_mask']) && empty($postrow[$i]['user_active']))
	{
		$this_poster_mask = true;
		user_profile_mask($postrow[$i]);
	}
	$poster_id = $postrow[$i]['user_id'];
	$post_id = $postrow[$i]['post_id'];
	$user_pic_count = $postrow[$i]['user_personal_pics_count'];
	$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : colorize_username($postrow[$i]['user_id'], $postrow[$i]['username'], $postrow[$i]['user_color'], $postrow[$i]['user_active']);
	$poster_qq = ($poster_id == ANONYMOUS) ? $lang['Guest'] : $postrow[$i]['username'];
	// BIRTHDAY - BEGIN
	$poster_age = '';
	if ($config['birthday_viewtopic'])
	{
		if ($postrow[$i]['user_birthday'] != 999999)
		{
			$poster_birthdate = realdate('md', $postrow[$i]['user_birthday']);
			$poster_age = $this_year - realdate('Y', $postrow[$i]['user_birthday']);
			if ($this_date < $poster_birthdate)
			{
				$poster_age--;
			}
			$poster_age = $lang['Age'] . ': ' . $poster_age . '<br />';
		}
		else
		{
			$poster_age = '';
			$poster_birthdate = '';
		}
		$birtdhay_cake = ($this_date == $poster_birthdate) ? '<img src="images/birthday_cake.png" alt="Happy Birthday" title="Happy Birthday" />' : '';
	}
	// BIRTHDAY - END

	$post_date = create_date_ip($config['default_dateformat'], $postrow[$i]['post_time'], $config['board_timezone']);

	$poster_posts = ($postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $postrow[$i]['user_posts'] : '';

	$poster_from_flag = (!empty($postrow[$i]['user_from_flag']) && ($postrow[$i]['user_id'] != ANONYMOUS)) ? '<img src="images/flags/' . $postrow[$i]['user_from_flag'] . '" alt="' . $postrow[$i]['user_from_flag'] . '" title="' . $postrow[$i]['user_from'] . '" />' : '';

	$poster_from = (!empty($postrow[$i]['user_from']) && ($postrow[$i]['user_id'] != ANONYMOUS)) ? $lang['Location'] . ': ' . $postrow[$i]['user_from'] : '';

	$poster_from_full = ((!empty($poster_from_flag) || !empty($postrow[$i]['user_from'])) && ($postrow[$i]['user_id'] != ANONYMOUS)) ? $lang['Location'] . ':' . (!empty($poster_from_flag) ? (' ' . $poster_from_flag) : '') . (!empty($postrow[$i]['user_from']) ? (' ' . $postrow[$i]['user_from']) : '') : '';

	$poster_joined = ($postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $postrow[$i]['user_regdate'], $config['board_timezone']) : '';

	$poster_avatar = user_get_avatar($poster_id, $postrow[$i]['user_level'], $postrow[$i]['user_avatar'], $postrow[$i]['user_avatar_type'], $postrow[$i]['user_allowavatar']);

	// Define the little post icon
//<!-- BEGIN Unread Post Information to Database Mod -->
	if(!$user->data['upi2db_access'])
	{
//<!-- END Unread Post Information to Database Mod -->
		if ($user->data['session_logged_in'] && ($postrow[$i]['post_time'] > $user->data['user_lastvisit']) && ($postrow[$i]['post_time'] > $topic_last_read) && !$user->data['is_bot'])
		{
			$mini_post_img = $images['icon_minipost_new'];
			$mini_post_alt = $lang['New_post'];
		}
		else
		{
			$mini_post_img = $images['icon_minipost'];
			$mini_post_alt = $lang['Post'];
		}
//<!-- BEGIN Unread Post Information to Database Mod -->
	}
	else
	{
		viewtopic_calc_unread($unread, $topic_id, $postrow[$i]['post_id'], $forum_id, $mini_post_img, $mini_post_alt, $unread_color, $read_posts);
	}
//<!-- END Unread Post Information to Database Mod -->

	if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
	{
		$mini_post_url = str_replace ('--', '-', make_url_friendly($postrow[$i]['post_subject']) . '-vp' . $postrow[$i]['post_id'] . '.html#p' . $postrow[$i]['post_id']);
	}
	else
	{
		// Mighty Gorgon: this is the full URL in case we would like to use it instead of the short form permalink... maybe for SEO purpose it is better using the short form
		//$mini_post_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#p' . $postrow[$i]['post_id'];
		$mini_post_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#p' . $postrow[$i]['post_id'];
	}

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$user_ranks = generate_ranks($postrow[$i], $ranks_array);
	if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html']  == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
	{
		$user_ranks['rank_01_html'] = '&nbsp;';
	}
	// Mighty Gorgon - Multiple Ranks - END

	$poster_thanks_received = '';
	if (($poster_id != ANONYMOUS) && ($user->data['user_id'] != ANONYMOUS) && $config['show_thanks_viewtopic'] && empty($config['disable_thanks_topics']) && !$lofi)
	{
		$total_thanks_received = user_get_thanks_received($poster_id);
		$poster_thanks_received = ($total_thanks_received > 0) ? ($lang['THANKS_RECEIVED'] . ': ' . '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_thanks=' . $poster_id) . '">' . $total_thanks_received . '</a>' . '<br />') : '';
	}

	// Handle anon users posting with usernames
	if (($poster_id == ANONYMOUS) && ($postrow[$i]['post_username'] != ''))
	{
		$poster = $postrow[$i]['post_username'];
		$poster_qq = $postrow[$i]['post_username'];
		$user_ranks['rank_01_html'] = $lang['Guest'] . '<br />';
	}

	if ($poster_id != ANONYMOUS)
	{
		$profile_url = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $poster_id);
		$profile_img = '<a href="' . $profile_url . '"><img src="' . $images['icon_profile'] . '" alt="' . htmlspecialchars($postrow[$i]['username']) . ' - ' . $lang['Read_profile'] . '" title="' . htmlspecialchars($postrow[$i]['username']) . '" /></a>';
		$profile = '<a href="' . $profile_url . '">' . $lang['Profile'] . '</a>';

		$pm_url = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $poster_id);
		$pm_img = '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
		$pm = '<a href="' . $pm_url . '">' . $lang['PM'] . '</a>';

		$email_url = '';
		if (empty($user->data['user_id']) || ($user->data['user_id'] == ANONYMOUS))
		{
			if (!empty($postrow[$i]['user_viewemail']))
			{
				$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Hidden_email'] . '" title="' . $lang['Hidden_email'] . '" />';
			}
			else
			{
				$email_img = '&nbsp;';
			}
			$email = '&nbsp;';
		}
		elseif (!empty($postrow[$i]['user_viewemail']) || $is_auth['auth_mod'])
		{
			$email_url = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL .'=' . $poster_id) : 'mailto:' . $postrow[$i]['user_email'];
			$email_img = '<a href="' . $email_url . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
			$email = '<a href="' . $email_url . '">' . $lang['Email'] . '</a>';
		}
		else
		{
			$email_img = '';
			$email = '';
		}

		$www_img = ($postrow[$i]['user_website']) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
		$www = ($postrow[$i]['user_website']) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_blank">' . $lang['Website'] . '</a>' : '';
		$www_url = ($postrow[$i]['user_website']) ? $postrow[$i]['user_website'] : '';

		$im_links_array = array(
			'chat' => 'id',
			'aim' => 'aim',
			'facebook' => 'facebook',
			'icq' => 'icq',
			'jabber' => 'jabber',
			'msn' => 'msnm',
			'skype' => 'skype',
			'twitter' => 'twitter',
			'yahoo' => 'yim',
		);

		$all_ims = array();
		foreach ($im_links_array as $im_k => $im_v)
		{
			$all_ims[$im_k] = array(
				'plain' => '',
				'img' => '',
				'url' => ''
			);
			if (!empty($postrow[$i]['user_' . $im_v]))
			{
				$all_ims[$im_k] = array(
					'plain' => build_im_link($im_k, $postrow[$i], false, false, false, false, false),
					'img' => build_im_link($im_k, $postrow[$i], 'icon_tpl_vt', true, false, false, false),
					'url' => build_im_link($im_k, $postrow[$i], false, false, true, false, false)
				);
			}
		}

		$aim_img = $all_ims['aim']['img'];
		$aim = $all_ims['aim']['plain'];
		$aim_url = $all_ims['aim']['url'];

		$icq_status_img = (!empty($postrow[$i]['user_icq'])) ? '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $postrow[$i]['user_icq'] . '&amp;img=5" width="18" height="18" /></a>' : '';
		$icq_img = $all_ims['icq']['img'];
		$icq = $all_ims['icq']['plain'];
		$icq_url = $all_ims['icq']['url'];

		$msn_img = $all_ims['msn']['img'];
		$msn = $all_ims['msn']['plain'];
		$msn_url = $all_ims['msn']['url'];

		$skype_img = $all_ims['skype']['img'];
		$skype = $all_ims['skype']['plain'];
		$skype_url = $all_ims['skype']['url'];

		$yahoo_img = $all_ims['yahoo']['img'];
		$yahoo = $all_ims['yahoo']['plain'];
		$yahoo_url = $all_ims['yahoo']['url'];

		// --- Smart Album Button BEGIN ----------------
		$album_url = '';
		if ($postrow[$i]['user_personal_pics_count'] > 0)
		{
			$album_url = ($postrow[$i]['user_personal_pics_count']) ? append_sid('album.' . PHP_EXT . '?user_id=' . $postrow[$i]['user_id']) : '';
			$album_img = ($postrow[$i]['user_personal_pics_count']) ? '<a href="' . $album_url . '"><img src="' . $images['icon_album'] . '" alt="' . $lang['Show_Personal_Gallery'] . '" title="' . $lang['Show_Personal_Gallery'] . '" /></a>' : '';
			$album = ($postrow[$i]['user_personal_pics_count']) ? '<a href="' . $album_url . '">' . $lang['Show_Personal_Gallery'] . '</a>' : '';
		}
		else
		{
			$album_img = '';
			$album = '';
		}
		// --- Smart Album Button END ----------------

		// Gender - BEGIN
		switch ($postrow[$i]['user_gender'])
		{
			case 1:
				$gender_image = '<img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'].  ': ' . $lang['Male'] . '" title="' . $lang['Gender'] . ': ' . $lang['Male'] . '" />';
				break;
			case 2:
				$gender_image = '<img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender']. ': ' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" />';
				break;
			default:
				$gender_image = '';
		}
		// Gender - END

		// ONLINE / OFFLINE - BEGIN
		$online_status_url = append_sid(CMS_PAGE_VIEWONLINE);
		// Start as offline...
		$online_status_img = '<img src="' . $images['icon_im_status_offline'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
		$online_status_lang = $lang['Offline'];
		$online_status_class = 'offline';
		if ($postrow[$i]['user_session_time'] >= (time() - $config['online_time']))
		{
			if (!empty($postrow[$i]['user_allow_viewonline']))
			{
				$online_status_img = '<a href="' . $online_status_url . '"><img src="' . $images['icon_im_status_online'] . '" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" /></a>';
				$online_status_lang = $lang['Online'];
				$online_status_class = 'online';
			}
			elseif (isset($postrow[$i]['user_allow_viewonline']) && empty($postrow[$i]['user_allow_viewonline']) && (($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $poster_id)))
			{
				$online_status_img = '<a href="' . $online_status_url . '"><img src="' . $images['icon_im_status_hidden'] . '" alt="' . $lang['Hidden'] . '" title="' . $lang['Hidden'] . '" /></a>';
				$online_status_lang = $lang['Hidden'];
				$online_status_class = 'hidden';
			}
		}
		// ONLINE / OFFLINE - END
	}
	else
	{
		$gender_image = '';
		$poster_from_flag = '';
		$profile_url = '';
		$profile_img = '';
		$profile = '';
		$pm_url = '';
		$pm_img = '';
		$pm = '';
		$email_url = '';
		$email_img = '';
		$email = '';
		$www_url = '';
		$www_img = '';
		$www = '';
		$aim_url = '';
		$aim_img = '';
		$aim = '';
		$icq_url = '';
		$icq_status_img = '';
		$icq_img = '';
		$icq = '';
		$msn_url = '';
		$msn_img = '';
		$msn = '';
		$skype_url = '';
		$skype_img = '';
		$skype = '';
		$yahoo_url = '';
		$yahoo_img = '';
		$yahoo = '';
		$album_url = '';
		$album_img = '';
		$album = '';
		$online_status_url = '';
		$online_status_img = '';
	}
	$quote_url = append_sid('posting.' . PHP_EXT . '?mode=quote&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id']);
	$quote_img = '<a href="' . $quote_url . '"><img src="' . $images['icon_quote'] . '" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '" /></a>';
	$quote = '<a href="' . $quote_url . '">' . $lang['Reply_with_quote'] . '</a>';

	$search_url = append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode($postrow[$i]['username']) . '&amp;showresults=posts');
	$search_img = '<a href="' . $search_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" title="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" /></a>';
	$search = '<a href="' . $search_url . '">' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '</a>';

	$edit_url = '';
	$edit_img = '';
	$edit = '';
	if ((($user->data['user_id'] == $poster_id) && $is_auth['auth_edit']) || $is_auth['auth_mod'])
	{
		if (($config['allow_mods_edit_admin_posts'] == false) && ($postrow[$i]['user_level'] == ADMIN) && ($user->data['user_level'] != ADMIN))
		{
			$edit_img = '';
			$edit = '';
		}
		else
		{
			$edit_url = append_sid('posting.' . PHP_EXT . '?mode=editpost&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id']);
			$edit_img = '<a href="' . $edit_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" /></a>';
			$edit = '<a href="' . $edit_url . '">' . $lang['Edit_delete_post'] . '</a>';
		}
	}

	$delpost_url = '';
	$delpost_img = '';
	$delpost = '';
	$ip_url = '';
	$ip_img = '';
	$ip_img_icon = '';
	$ip = '';
	if (($user->data['user_level'] == ADMIN) || $is_auth['auth_mod'])
	{
		if (!empty($ip_display_auth))
		{
			$ip_url = 'modcp.' . PHP_EXT . '?mode=ip&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id'] . '&amp;sid=' . $user->data['session_id'];
			// Start Advanced IP Tools Pack MOD
			$ip_img = '<a href="' . $ip_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . ' (' . htmlspecialchars($postrow[$i]['poster_ip']) . ')" title="' . $lang['View_IP'] . ' (' . htmlspecialchars($postrow[$i]['poster_ip']) . ')" /></a>';
			$ip_img_icon = '<a href="' . $ip_url . '"><img src="' . $images['vt_post_ip'] . '" alt="' . $lang['View_IP'] . ' (' . htmlspecialchars($postrow[$i]['poster_ip']) . ')" title="' . $lang['View_IP'] . ' (' . htmlspecialchars($postrow[$i]['poster_ip']) . ')" /></a>';
			// End Advanced IP Tools Pack MOD
			$ip = '<a href="' . $ip_url . '">' . $lang['View_IP'] . '</a>';
		}

		if (($config['allow_mods_edit_admin_posts'] == false) && ($postrow[$i]['user_level'] == ADMIN) && ($user->data['user_level'] != ADMIN))
		{
			$delpost_url = '';
			$delpost_img = '';
			$delpost = '';
		}
		else
		{
			$delpost_url = 'posting.' . PHP_EXT . '?mode=delete&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id'] . '&amp;sid=' . $user->data['session_id'];
			$delpost_img = '<a href="' . $delpost_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
			$delpost = '<a href="' . $delpost_url . '">' . $lang['Delete_post'] . '</a>';
		}
	}
	else
	{
		if (($config['allow_mods_edit_admin_posts'] == false) && ($postrow[$i]['user_level'] == ADMIN) && ($user->data['user_level'] != ADMIN))
		{
			$delpost_url = '';
			$delpost_img = '';
			$delpost = '';
		}
		elseif (($user->data['user_id'] == $poster_id) && $is_auth['auth_delete'] && ($forum_topic_data['topic_last_post_id'] == $postrow[$i]['post_id']))
		{
			$delpost_url = 'posting.' . PHP_EXT . '?mode=delete&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id'] . '&amp;sid=' . $user->data['session_id'];
			$delpost_img = '<a href="' . $delpost_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
			$delpost = '<a href="' . $delpost_url . '">' . $lang['Delete_post'] . '</a>';
		}
	}

	// Start Yellow Card Changes for phpBB Styles
	$allowed_styles = array(
		'ca_aphrodite',
		'squared',
	);
	if(in_array($theme['template_name'], $allowed_styles))
	{
		$phpbb_styles = true;
	}
	else
	{
		$phpbb_styles = false;
	}

	if(($poster_id != ANONYMOUS) && ($postrow[$i]['user_level'] != ADMIN))
	{
		$current_user = str_replace("'", "\'", $postrow[$i]['username']);
		if ($is_auth['auth_greencard'])
		{
			$grn_card_img = '<img src="'. $images['icon_g_card'] . '" alt="' . $lang['Give_G_card'] . '" />';
			$grn_card_action = 'return confirm(\'' . sprintf($lang['Green_card_warning'], $current_user) . '\')';
			$temp_url = 'card.' . PHP_EXT . '?mode=unban&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $user->data['user_id'] . '&amp;sid=' . $user->data['session_id'];
			$g_card_img = '<a href="' . $temp_url . '" title="' . $lang['Give_G_card'] . '" onclick="' . $grn_card_action . '">' . $grn_card_img . '</a>';
			if($phpbb_styles == true)
			{
				$g_card_img = '<span class="img-green">' . $g_card_img . '</span>';
			}
		}
		else
		{
			$g_card_img = '';
		}

		$user_warnings = $postrow[$i]['user_warnings'];

		$card_img = '';
		$is_banned = false;
		if ($user_warnings == 0)
		{
			$card_img = '';
		}
		else
		{
			$is_banned = (isset($ranks_array['bannedrow'][$poster_id])) ? true : false;
			if (($user_warnings > $config['max_user_bancard']) || $is_banned)
			{
				$card_img = '<img src="' . $images['icon_r_cards'] . '" alt="' . $lang['Banned'] . '" title="' . $lang['Banned'] . '">';
			}
			else
			{
				for ($n = 0; $n < $user_warnings; $n++)
				{
					$card_img .= '<img src="' . $images['icon_y_cards'] . '" alt="' . sprintf($lang['Warnings'], $user_warnings) . '" title="' . sprintf($lang['Warnings'], $user_warnings) . '" />&nbsp;';
				}
			}
		}

		if (($user_warnings <= $config['max_user_bancard']) && $is_auth['auth_ban'])
		{
			$yel_card_img = '<img src="' . $images['icon_y_card'] . '" alt="' . sprintf($lang['Give_Y_card'], $user_warnings + 1) . '" />';
			$yel_card_action = 'return confirm(\'' . sprintf($lang['Yellow_card_warning'], $current_user) . '\')';
			$temp_url = 'card.' . PHP_EXT . '?mode=warn&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $user->data['user_id'] . '&amp;sid=' . $user->data['session_id'];
			$y_card_img = '<a href="' . $temp_url . '" title="' .sprintf($lang['Give_Y_card'], $user_warnings + 1). '" onclick="' . $yel_card_action . '">' . $yel_card_img . '</a>';

			$red_card_img = '<img src="'. $images['icon_r_card'] . '" alt="'. $lang['Give_R_card'] . '" />';
			$red_card_action = 'return confirm(\''.sprintf($lang['Red_card_warning'], $current_user).'\')';
			$temp_url = 'card.' . PHP_EXT . '?mode=ban&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $user->data['user_id'] . '&amp;sid=' . $user->data['session_id'];
			$r_card_img = '<a href="' . $temp_url . '" title="' . $lang['Give_R_card'] . '" onclick="' . $red_card_action . '">' . $red_card_img . '</a>';

			if($phpbb_styles == true)
			{
				$y_card_img = '<span class="img-warn">' . $y_card_img . '</span>';
				$r_card_img = '<span class="img-ban">' . $r_card_img . '</span>';
			}
		}
		else
		{
			$y_card_img = '';
			$r_card_img = '';
		}
	}
	else
	{
		$card_img = '';
		$g_card_img = '';
		$y_card_img = '';
		$r_card_img = '';
	}

	if ($is_auth['auth_bluecard'])
	{
		if ($is_auth['auth_mod'])
		{
			$blue_card_img = (($postrow[$i]['post_bluecard'])) ? '<img src="' . $images['icon_p_card'] . '" alt="' . sprintf($lang['Clear_b_card'], $postrow[$i]['post_bluecard']) . '" />' : '<img src="' . $images['icon_b_card'] . '" alt="' . $lang['Give_b_card'] . '" />';
			$blue_card_action = ($postrow[$i]['post_bluecard']) ? 'return confirm(\'' . $lang['Clear_blue_card_warning'] . '\')' : 'return confirm(\'' . $lang['Blue_card_warning'] . '\')';
			$temp_url = 'card.' . PHP_EXT . '?mode=' . (($postrow[$i]['post_bluecard']) ? 'report_reset' : 'report') . '&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $user->data['user_id'] . '&amp;sid=' . $user->data['session_id'];
			$b_card_img = '<a href="' . $temp_url . '" onclick="' . $blue_card_action . '" title="' . $lang['Give_b_card'] . '">' . $blue_card_img . '</a>';
			if($phpbb_styles == true)
			{
				$b_card_img = '<span class="' . (($postrow[$i]['post_bluecard']) ? 'img-clear' : 'img-report') . '">' . $b_card_img . '</span>';
			}
		}
		else
		{
			$blue_card_img = '<img src="'. $images['icon_b_card'] . '" alt="'. $lang['Give_b_card'] . '" title="'.$lang['Give_b_card'].'" />';
			$blue_card_action = 'return confirm(\''.$lang['Blue_card_warning'].'\')';
			$temp_url = 'card.' . PHP_EXT . '?mode=report&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $user->data['user_id'] . '&amp;sid=' . $user->data['session_id'];
			$b_card_img = '<a href="' . $temp_url . '" title="' . $lang['Give_b_card'] . '" onclick="' . $blue_card_action . '">' . $blue_card_img . '</a>';
			if($phpbb_styles == true)
			{
				$b_card_img = '<span class="img-report">' . $b_card_img . '</span>';
			}
		}
	}
	else
	{
		$b_card_img = '';
	}

	// parse hidden fields if cards visible
	$card_hidden = ($g_card_img || $r_card_img || $y_card_img || $b_card_img) ? '<input type="hidden" name="post_id" value="' . $postrow[$i]['post_id']. '" />' : '';
	// End Changes for Yellow Card Mod

	if ($parse_extra_user_info == true)
	{
		if(array_search($postrow[$i]['user_style'], $styles_list_id))
		{
			$poster_style = $lang['Change_Style'] . ': ' . $styles_list_name[array_search($postrow[$i]['user_style'], $styles_list_id)] . '<br />';
		}
		else
		{
			$poster_style = '';
		}
		if (!$postrow[$i]['user_lang'] == '')
		{
			$poster_lang = $lang['Change_Lang'] . ': ' . ucfirst(strtolower($postrow[$i]['user_lang'])) . '&nbsp;<img src="language/lang_' . $postrow[$i]['user_lang'] . '/flag.png" alt="" title="" /><br />';
		}
		else
		{
			$poster_lang = '';
		}
	}
	else
	{
		$poster_style = '';
		$poster_lang = '';
	}
	$post_subject = ($postrow[$i]['post_subject'] != '') ? $postrow[$i]['post_subject'] : '';

	// Mighty Gorgon - Quick Quote - BEGIN
	if ($config['enable_quick_quote'])
	{
		$look_up_array = array(
			'\"',
			'"',
			"<",
			">",
			"\n",
			chr(13),
		);

		$replacement_array = array(
			'&q_mg;',
			'\"',
			"&lt_mg;",
			"&gt_mg;",
			"\\n",
			"",
		);

		$plain_message = $postrow[$i]['post_text'];
		$plain_message = strtr($plain_message, array_flip(get_html_translation_table(HTML_ENTITIES)));
		//Hide MOD
		if(preg_match('/\[hide/i', $plain_message))
		{
			$search = array("/\[hide\](.*?)\[\/hide\]/");
			$replace = array('[hide]' . $lang['xs_bbc_hide_quote_message'] . '[/hide]');
			$plain_message =  preg_replace($search, $replace, $plain_message);
		}
		//Hide MOD
		$plain_message = censor_text($plain_message);
		$plain_message = str_replace($look_up_array, $replacement_array, $plain_message);
	}
	// Mighty Gorgon - Quick Quote - END

	// Mighty Gorgon - New BBCode Functions - BEGIN
	// Please, do not change anything here, if you're not confident with what you're doing!!!
	$message = $postrow[$i]['post_text'];
	$message_compiled = (empty($postrow[$i]['post_text_compiled']) || !empty($user->data['session_logged_in']) || !empty($config['posts_precompiled'])) ? false : $postrow[$i]['post_text_compiled'];

	// CrackerTracker v5.x
	$poster_miserable = ($postrow[$i]['ct_miserable_user'] == 1) ? true : false;
	$is_miserable = ($poster_miserable && ($postrow[$i]['user_id'] == $user->data['user_id'])) ? true : false;
	if ($poster_miserable && !$is_miserable)
	{
		// Maybe we should hide the miserable user tag for guests? ==> if ($user->data['session_logged_in'])
		if (($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))
		{
			// Normal users and guests...
			// If you want to hide the post text, just decomment the line below!
			$message = '';
		}
		$message .= "\n\n" . $lang['ctracker_mu_success_bbc'];
	}

	if ($poster_miserable)
	{
		$message_compiled = false;
	}
	// CrackerTracker v5.x

	$user_sig = ($postrow[$i]['enable_sig'] && (trim($postrow[$i]['user_sig']) != '') && $config['allow_sig']) ? $postrow[$i]['user_sig'] : '';

	// Replace Naughty Words - BEGIN
	$post_subject = censor_text($post_subject);
	$user_sig = censor_text($user_sig);
	$message = censor_text($message);
	// Replace Naughty Words - END

	// BBCode Parsing

	if($user_sig && empty($sig_cache[$postrow[$i]['user_id']]))
	{
		$bbcode->allow_bbcode = $config['allow_bbcode'] && $user->data['user_allowbbcode'];
		//$bbcode->allow_smilies = true;
		//$bbcode->allow_html = true;
		$bbcode->allow_smilies = $config['allow_smilies'] && empty($lofi);
		$bbcode->allow_html = $config['allow_html'] && $user->data['user_allowhtml'];
		$bbcode->is_sig = true;
		$bbcode->allow_hs = false;
		$user_sig = $bbcode->parse($user_sig);
		$bbcode->is_sig = false;
		$bbcode->allow_hs = true;
		$sig_cache[$postrow[$i]['user_id']] = $user_sig;
	}
	elseif($user_sig)
	{
		$user_sig = $sig_cache[$postrow[$i]['user_id']];
	}

	// Replace new lines (we use this rather than nl2br because till recently it wasn't XHTML compliant)
	if ($user_sig != '')
	{
		$user_sig = '<br />' . $config['sig_line'] . '<br />' . $user_sig;
	}

	$bbcode->allow_html = (($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) && $postrow[$i]['enable_html'];
	$bbcode->allow_bbcode = $config['allow_bbcode'] && $user->data['user_allowbbcode'] && $postrow[$i]['enable_bbcode'];
	$bbcode->allow_smilies = $config['allow_smilies'] && empty($lofi) && $postrow[$i]['enable_smilies'];

	if(preg_match('/\[code/i', $message))
	{
		$bbcode->allow_html = false;
	}

	if ($message_compiled === false)
	{
		$bbcode->code_post_id = $postrow[$i]['post_id'];
		$message = $bbcode->parse($message);
		$bbcode->code_post_id = 0;
		if ($bbcode->allow_bbcode == false)
		{
			$message = str_replace("\n", "<br />", preg_replace("/\r\n/", "\n", $message));
		}
		if (empty($user->data['session_logged_in']) && empty($config['posts_precompiled']) && empty($lofi))
		{
			// update database
			$sql = "UPDATE " . POSTS_TABLE . " SET post_text_compiled = '" . $db->sql_escape($message) . "' WHERE post_id='" . $postrow[$i]['post_id'] . "'";
			$db->sql_query($sql);
		}
	}
	else
	{
		$message = $message_compiled;
	}
	// Mighty Gorgon - New BBCode Functions - END

	//Acronyms, AutoLinks - BEGIN
	if ($postrow[$i]['enable_autolinks_acronyms'])
	{
		$message = $bbcode->acronym_pass($message);
		$message = $bbcode->autolink_text($message, $forum_id);
	}
	//Acronyms, AutoLinks - END

	// Highlight active words (primarily for search)
	if ($highlight_match)
	{
		// This has been back-ported from 3.0 CVS
		$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<span class="highlight-w"><b>\1</b></span>', $message);
	}

	// BEGIN CMX News Mod
	// Strip out the <!--break--> delimiter.
	if ($postrow[$i]['post_id'] == $topic_first_post_id)
	{
		$delim = htmlspecialchars('<!--break-->');
		$pos = strpos($message, $delim);
		if(($pos !== false) && ($pos < strlen($message)))
		{
			$message = substr_replace($message, html_entity_decode($delim), $pos, strlen($delim));
		}
	}
	// END CMX News Mod

	// Mighty Gorgon - ???
	// $message = str_replace("\n", "\n<br />\n", $message);
	// Mighty Gorgon - ???

	// Editing information
	if ($config['edit_notes'] == 1)
	{
		$notes_list = strlen($postrow[$i]['edit_notes']) ? unserialize($postrow[$i]['edit_notes']) : array();
		if($is_auth['auth_mod'] && (sizeof($delnote) == 2) && ($delnote[0] == $postrow[$i]['post_id']))
		{
			$new_list = array();
			$num = intval($delnote[1]);
			for($n = 0; $n < sizeof($notes_list); $n++)
			{
				if($n !== $num)
				{
					$new_list[] = $notes_list[$n];
				}
			}
			$notes_list = $new_list;
			$postrow[$i]['edit_notes'] = sizeof($notes_list) ? serialize($notes_list) : '';
			$sql = "UPDATE " . POSTS_TABLE . " SET edit_notes = '" . $db->sql_escape($postrow[$i]['edit_notes']) . "' WHERE post_id = '" . $postrow[$i]['post_id'] . "'";
			$db->sql_query($sql);
		}
	}
	else
	{
		$notes_list = '';
	}

	$show_edit_by = (($config['always_show_edit_by'] || !$notes_list) ? true : false);
	if ($postrow[$i]['post_edit_count'] && $show_edit_by)
	{
		$l_edit_time_total = ($postrow[$i]['post_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
		$l_edit_id = (intval($postrow[$i]['post_edit_id']) > 1) ? colorize_username($postrow[$i]['post_edit_id']) : $poster;
		$l_edited_by = sprintf($l_edit_time_total, $l_edit_id, create_date_ip($config['default_dateformat'], $postrow[$i]['post_edit_time'], $config['board_timezone']), $postrow[$i]['post_edit_count']);
	}
	else
	{
		$l_edited_by = '';
	}

	// Convert and clean special chars!
	$post_subject = htmlspecialchars_clean($post_subject);
	// SMILEYS IN TITLE - BEGIN
	if (($config['smilies_topic_title'] == true) && !$lofi)
	{
		$bbcode->allow_smilies = ($config['allow_smilies'] && $postrow[$i]['enable_smilies'] ? true : false);
		$post_subject = $bbcode->parse_only_smilies($post_subject);
	}
	// SMILEYS IN TITLE - END

	if (!empty($topic_calendar_time) && ($postrow[$i]['post_id'] == $topic_first_post_id))
	{
		$post_subject .= get_calendar_title($topic_calendar_time, $topic_calendar_duration);
	}

//<!-- BEGIN Unread Post Information to Database Mod -->
	if($user->data['upi2db_access'])
	{
		$post_edit_max = ($postrow[$i]['post_time'] >= $postrow[$i]['post_edit_time']) ? $postrow[$i]['post_time'] : $postrow[$i]['post_edit_time'];
		$post_time_max = (empty($config['upi2db_edit_as_new'])) ? $postrow[$i]['post_time'] : $post_edit_max;
		$post_id = $postrow[$i]['post_id'];
		$mark_topic_unread = mark_post_viewtopic($post_time_max, $unread, $topic_id, $forum_id, $post_id, $except_time, $forum_topic_data['topic_type']);
	}
	else
	{
		$mark_topic_unread = '';
	}
//<!-- END Unread Post Information to Database Mod -->

	$post_id = $postrow[$i]['post_id'];
	$poster_number = ($postrow[$i]['poster_id'] == ANONYMOUS) ? '' : $lang['User_Number'] . ': ' . $postrow[$i]['poster_id'];
	$post_edit_link = append_sid('edit_post_details.' . PHP_EXT . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id']);
	$post_edit_string_short = ($user->data['user_level'] == ADMIN) ? ('<a href="#" onclick="post_time_edit(\'' . $post_edit_link . '\'); return false;" style="text-decoration: none;" title="' . $lang['Edit_post_time_xs'] . '">' . $post_date . '</a>') : '';
	$post_edit_string = ($user->data['user_level'] == ADMIN) ? ('<a href="#" onclick="post_time_edit(\'' . $post_edit_link . '\'); return false;" style="text-decoration: none;" title="' . $lang['Edit_post_time_xs'] . '">' . $lang['Edit_post_time_xs'] . '</a>') : '';
	$single_post = '<a href="#_Single_Post_View" onclick="open_postreview(\'show_post.' . PHP_EXT . '?' . POST_POST_URL . '=' . intval($post_id) . '\'); return false;" style="text-decoration: none;">#' . ($i + 1 + $start) . '</a>';
	$single_post_share = '<a href="#" onclick="popup(\'share.' . PHP_EXT . '?' . POST_POST_URL . '=' . intval($post_id) . '\', 840, 420, \'_post_share\'); return false;" style="text-decoration: none;">' . $lang['SHARE'] . '</a>';
	$single_post_like_list = '<a href="#" onclick="popup(\'topic_view_users.' . PHP_EXT . '?like=1&amp;' . POST_POST_URL . '=' . intval($post_id) . '\', 840, 420, \'_post_like\'); return false;" style="text-decoration: none;" title="' . $lang['LIKE_RECAP'] . '">' . '{USERS_LIKE}' . '</a>';

	// Mighty Gorgon - POSTS LIKES - BEGIN
	$post_like_text = '';
	$post_like_text_js = '';
	if ($posts_like_enabled)
	{
		$users_like = sizeof($topic_posts_likes['posts'][$post_id]);
		$reader_likes = false;
		$post_like_text_single = false;

		if (($user->data['user_id'] != ANONYMOUS) && !empty($topic_posts_likes['users'][$user->data['user_id']]) && in_array($post_id, $topic_posts_likes['users'][$user->data['user_id']]))
		{
			$reader_likes = true;
			$users_like--;
		}

		if (!empty($users_like))
		{
			$post_like_text_single = ($users_like == 1) ? true : false;
		}

		if ($reader_likes)
		{
			if (empty($users_like))
			{
				$single_post_like_list = '';
				$post_like_text = $lang['LIKE_COUNTER_YOU'];
				$post_like_text_js = '';
			}
			elseif ($post_like_text_single)
			{
				$single_post_like_list = str_replace('{USERS_LIKE}', 1, $single_post_like_list);
				$post_like_text = $lang['LIKE_COUNTER_YOU_OTHERS_SINGLE'];
				$post_like_text_js = $lang['LIKE_COUNTER_OTHERS_SINGLE'];
			}
			else
			{
				$single_post_like_list = str_replace('{USERS_LIKE}', $users_like, $single_post_like_list);
				$post_like_text = sprintf($lang['LIKE_COUNTER_YOU_OTHERS'], $single_post_like_list);
				$post_like_text_js = sprintf($lang['LIKE_COUNTER_OTHERS'], $single_post_like_list);
			}
		}
		else
		{
			if (empty($users_like))
			{
				$single_post_like_list = '';
				$post_like_text = '';
				$post_like_text_js = $lang['LIKE_COUNTER_YOU'];
			}
			elseif ($post_like_text_single)
			{
				$single_post_like_list = str_replace('{USERS_LIKE}', 1, $single_post_like_list);
				$post_like_text = $lang['LIKE_COUNTER_OTHERS_SINGLE'];
				$post_like_text_js = $lang['LIKE_COUNTER_YOU_OTHERS_SINGLE'];
			}
			else
			{
				$single_post_like_list = str_replace('{USERS_LIKE}', $users_like, $single_post_like_list);
				$post_like_text = sprintf($lang['LIKE_COUNTER_OTHERS'], $single_post_like_list);
				$post_like_text_js = sprintf($lang['LIKE_COUNTER_YOU_OTHERS'], $single_post_like_list);
			}
		}
	}
	// Mighty Gorgon - POSTS LIKES - END

	// Mighty Gorgon - Feedback - BEGIN
	$feedback_received = '';
	$feedback_add = '';
	if (!empty($config['plugins']['feedback']['enabled']) && !$feedback_disabled)
	{
		$feedback_details = get_user_feedback_received($postrow[$i]['user_id']);
		if ($feedback_details['feedback_count'] > 0)
		{
			$feedback_average = (($feedback_details['feedback_count'] > 0) ? (round($feedback_details['feedback_sum'] / $feedback_details['feedback_count'], 1)) : 0);
			$feedback_average_img = IP_ROOT_PATH . 'images/feedback/' . build_feedback_rating_image($feedback_average);
			$feedback_received = (($feedback_details['feedback_count'] > 0) ? ($lang['FEEDBACK_RECEIVED'] . ': [ <a href="' . append_sid(PLUGINS_FEEDBACK_FILE . '?' . POST_USERS_URL . '=' . $postrow[$i]['user_id']) . '">' . $feedback_details['feedback_count'] . '</a> ]<br /><img src="' . $feedback_average_img . '" alt="' . $feedback_average . '" title="' . $feedback_average . '" /><br />') : '');
		}
		if (can_user_give_feedback_topic($user->data['user_id'], $topic_id) && can_user_give_feedback_global($user->data['user_id'], $topic_id) && ($user->data['user_id'] != $postrow[$i]['user_id']))
		{
			$feedback_add = '&nbsp;&nbsp;<a href="' . append_sid(PLUGINS_FEEDBACK_FILE . '?mode=input&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_USERS_URL . '=' . $postrow[$i]['user_id']) . '">' . $lang['FEEDBACK_ADD'] . '</a><br />';
		}
	}
	// Mighty Gorgon - Feedback - END

	// Again this will be handled by the templating code at some point
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('postrow', array(
		// Mighty Gorgon - Feedback - BEGIN
		'FEEDBACK' => $feedback_received . $feedback_add,
		// Mighty Gorgon - Feedback - END
		'ROW_CLASS' => $row_class,
		'POSTER_NAME' => $poster,
		'POSTER_NAME_QQ' => $poster_qq,
		'POSTER_NAME_QR' => str_replace(array(' ', '?', '&'), array('%20', '%3F', '%26'), $poster_qq),
		//'POSTER_NAME_QR' => htmlspecialchars($poster_qq),
		'POSTER_AGE' => $poster_age,
		'HAPPY_BIRTHDAY' => $birtdhay_cake,
		'USER_RANK_01' => $user_ranks['rank_01_html'],
		'USER_RANK_01_IMG' => $user_ranks['rank_01_img_html'],
		'USER_RANK_02' => $user_ranks['rank_02_html'],
		'USER_RANK_02_IMG' => $user_ranks['rank_02_img_html'],
		'USER_RANK_03' => $user_ranks['rank_03_html'],
		'USER_RANK_03_IMG' => $user_ranks['rank_03_img_html'],
		'USER_RANK_04' => $user_ranks['rank_04_html'],
		'USER_RANK_04_IMG' => $user_ranks['rank_04_img_html'],
		'USER_RANK_05' => $user_ranks['rank_05_html'],
		'USER_RANK_05_IMG' => $user_ranks['rank_05_img_html'],
		'POSTER_GENDER' => $gender_image,
		'POSTER_JOINED' => $poster_joined,
		'POSTER_POSTS' => $poster_posts,
		'POSTER_THANKS_RECEIVED' => $poster_thanks_received,
		'POSTER_FROM' => $poster_from,
		'POSTER_FROM_FULL' => $poster_from_full,
		'POSTER_FROM_FLAG' => $poster_from_flag,
		'POSTER_AVATAR' => $poster_avatar,
		'POST_DATE' => $post_date,
		'POST_EDIT_STRING' => $post_edit_string,
		'POST_EDIT_STRING_SHORT' => $post_edit_string_short,
		//'POST_EDIT_LINK' => $post_edit_link,
		'POST_SUBJECT' => $post_subject,
		'MESSAGE' => $message,
		'PLAIN_MESSAGE' => $plain_message,
		'SIGNATURE' => $user_sig,
		'EDITED_MESSAGE' => $l_edited_by,

		'POSTER_STYLE' => $poster_style,
		'POSTER_LANG' => $poster_lang,

		// Activity - BEGIN
		'POSTER_TROPHY' => (!empty($config['plugins']['activity']['enabled']) ? Amod_Build_Topics($hof_data, $postrow[$i]['user_id'], $postrow[$i]['user_trophies'], $postrow[$i]['username'], $postrow[$i]['ina_char_name']) : ''),
		// Activity - END

		'MINI_POST_IMG' => $mini_post_img,
		'PROFILE_IMG' => $profile_img,
		'PROFILE' => $profile,
		'SEARCH_IMG' => $search_img,
		'SEARCH' => $search,
		'PM_IMG' => $pm_img,
		'PM' => $pm,
		'EMAIL_IMG' => (!$user->data['session_logged_in'] || $user->data['is_bot']) ? '' : $email_img,
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
		'ALBUM_IMG' => $album_img,
		'ALBUM' => $album,
		'POSTER_ONLINE_STATUS_IMG' => $online_status_img,

		'EDIT_IMG' => $edit_img,
		'EDIT' => $edit,
		'DELETE_IMG' => $delpost_img,
		'DELETE' => $delpost,
		'QUOTE_IMG' => $quote_img,
		'QUOTE' => $quote,
		'DOWNLOAD_IMG' => $images['icon_download2'],
		'DOWNLOAD_IMG_ICON' => $images['vt_post_download'],
		'IP_IMG' => $ip_img,
		'IP_IMG_ICON' => $ip_img_icon,
		'IP' => $ip,

		'U_PROFILE' => $profile_url,
		'U_PM' => $pm_url,
		'U_EMAIL' => $email_url,
		'U_WWW' => $www_url,
		'U_AIM' => $aim_url,
		'U_ICQ' => $icq_url,
		'U_MSN' => $msn_url,
		'U_SKYPE' => $skype_url,
		'U_YIM' => $yahoo_url,
		'U_ALBUM' => $album_url,
		'L_POSTER_ONLINE_STATUS' => $online_status_lang,
		'POSTER_ONLINE_STATUS_CLASS' => $online_status_class,
		'U_POSTER_ONLINE_STATUS' => $online_status_url,
		'U_IP' => $ip_url,
		'U_QUOTE' => $quote_url,
		'U_EDIT' => $edit_url,
		'U_DELETE' => $delpost_url,

		'DELETED' => $postrow[$i]['deleted'],
		'POST_IS_DELETED' => sprintf($lang['POST_IS_DELETED'], '<a href="'.append_sid(CMS_PAGE_PROFILE . '?' . POST_USERS_URL . '=' . $postrow[$i]['deleter_user_id']).'">', colorize_username($postrow[$i]['deleter_user_id'], $postrow[$i]['deleter_username']), '</a>', create_date_ip('H:i:s Y/m/d', $postrow[$i]['deleted_time'], $config['board_timezone'])),
		'DELETER_USERNAME' => $postrow[$i]['deleter_username'],

		'L_MINI_POST_ALT' => $mini_post_alt,
		'NOTES_COUNT' => sizeof($notes_list),
		'NOTES_DATA' => $postrow[$i]['edit_notes'],
		'DOWNLOAD_POST' => append_sid(CMS_PAGE_VIEWTOPIC . '?download=' . $postrow[$i]['post_id'] . '&amp;' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append),
		'SINGLE_POST' => $single_post,
		'SINGLE_POST_SHARE' => $single_post_share,
		'READER_LIKES' => $reader_likes,
		'POST_LIKE_TEXT' => $post_like_text,
		'POST_LIKE_TEXT_JS' => str_replace(array('/'), array('\/'), addslashes($post_like_text)),
		'POST_LIKE_TEXT_JS_NEW' => str_replace(array('/'), array('\/'), addslashes($post_like_text_js)),
		'POSTER_NO' => $poster_number,
		//'POSTER_NO' => $postrow[$i]['poster_id'],
		'USER_WARNINGS' => !empty($user_warnings) ? $user_warnings : '',
		'CARD_IMG' => $card_img,
		'CARD_HIDDEN_FIELDS' => $card_hidden,
		'CARD_EXTRA_SPACE' => ($r_card_img || $y_card_img || $g_card_img || $b_card_img) ? ' ' : '',

		'U_MINI_POST' => $mini_post_url,
//<!-- BEGIN Unread Post Information to Database Mod -->
		'UNREAD_IMG' => $mark_topic_unread,
		'UNREAD_COLOR' => !empty($unread_color) ? $unread_color : '',
//<!-- END Unread Post Information to Database Mod -->
		'U_G_CARD' => $g_card_img,
		'U_Y_CARD' => $y_card_img,
		'U_R_CARD' => $r_card_img,
		'U_B_CARD' => $b_card_img,
		'S_CARD' => ($phpbb_styles) ? $card_action : append_sid('card.' . PHP_EXT),

		'U_TOPIC_ID' => $topic_id,
		'U_POST_ID' => $postrow[$i]['post_id']
		)
	);

	// MG Cash MOD For IP - BEGIN
	if (!empty($config['plugins']['cash']['enabled']))
	{
		$cm_viewtopic->post_vars($postrow[$i], $user->data, $forum_id);
	}
	// MG Cash MOD For IP - END

	// Custom Profile Fields MOD - BEGIN
	if (($poster_id != ANONYMOUS) && ($profile_data_sql != ''))
	{
		$language = $config['default_lang'];
		if (!file_exists(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_profile_fields.' . PHP_EXT))
		{
			$language = 'english';
		}
		include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_profile_fields.' . PHP_EXT);

		$cp_data = array();
		$cp_data = get_topic_udata($postrow[$i], $profile_data);

		if ($cp_data['aboves'])
			foreach($cp_data['aboves'] as $above_val)
			{
				$template->assign_block_vars('postrow.above_sig', array('ABOVE_VAL' => $above_val));
			}

		if ($cp_data['belows'])
		{
			foreach($cp_data['belows'] as $below_val)
			{
				$template->assign_block_vars('postrow.below_sig', array('BELOW_VAL' => $below_val));
			}
		}

		if ($cp_data['author'])
		{
			foreach($cp_data['author'] as $author_val)
			{
				$template->assign_block_vars('postrow.author_profile', array('AUTHOR_VAL' => $author_val));
			}
		}
	}
	// Custom Profile Fields MOD - END

	if ($config['switch_poster_info_topic'])
	{
		$template->assign_block_vars('postrow.switch_poster_info', array());
	}
	if ($user->data['user_showavatars'])
	{
		$template->assign_block_vars('postrow.switch_showavatars', array());
	}
	if ($user->data['user_showsignatures'])
	{
		$template->assign_block_vars('postrow.switch_showsignatures', array());
	}

	display_post_attachments($postrow[$i]['post_id'], $postrow[$i]['post_attachment']);

	if(!empty($show_thanks) && ($i == 0) && ($current_page == 1) && !empty($thanks))
	{
		$template->assign_block_vars('postrow.thanks', array(
			'THANKS' => $thanks,
			'THANKFUL' => $lang['thankful'],
			'THANKS2' => $lang ['thanks2'],
			'THANKS3' => $thanks2
			)
		);
	}

	//if ((!$forum_topic_data['forum_status'] == FORUM_LOCKED) && (!$forum_topic_data['topic_status'] == TOPIC_LOCKED) && ($is_auth['auth_reply']) && ($user->data['session_logged_in']))
	if ((!$forum_topic_data['forum_status'] == FORUM_LOCKED) && (!$forum_topic_data['topic_status'] == TOPIC_LOCKED) && ($is_auth['auth_reply']) && $config['enable_quick_quote'] && !$user->data['is_bot'])
	{
		$template->assign_block_vars('postrow.switch_quick_quote', array());
	}

	if ($i == 0)
	{

		$viewtopic_banner_text = get_ad('vtx');
		if (!empty($viewtopic_banner_text))
		{
			$template->assign_vars(array(
				'VIEWTOPIC_BANNER_CODE' => $viewtopic_banner_text,
				)
			);

			// Decomment this if you want sponsors to be shown everywhere
			$template->assign_block_vars('postrow.switch_viewtopic_banner', array());

			// Use this if you want to block sponsors not readable by guests
			/*
			if ($this_forum_auth_read > 0)
			{
				$template->assign_block_vars('postrow.switch_viewtopic_banner', array());
			}
			*/
		}

		$template->assign_block_vars('postrow.switch_first_post', array());
	}

	if ($config['edit_notes'])
	{
		$template->assign_vars(array(
			'S_EDIT_NOTES' => true,
			)
		);

		for($n = 0; $n < sizeof($notes_list); $n++)
		{
			if(!isset($user_ids[$notes_list[$n]['poster']]))
			{
				$user_ids[$notes_list[$n]['poster']] = 'n/a';
				$user_ids2[] = $notes_list[$n]['poster'];
			}
		}

		// add notes
		unset($item);

		if(!empty($template->_tpldata['postrow.'][$i]['NOTES_DATA']))
		{
			$item = &$template->_tpldata['postrow.'][$i];
			$item['notes.'] = array();
			$list = unserialize($item['NOTES_DATA']);
			for($j = 0; $j < sizeof($list); $j++)
			{
				$item['notes.'][] = array(
					'L_EDITED_BY' => $lang['Edited_by'],
					'POSTER_NAME' => colorize_username($list[$j]['poster']),
					'POSTER_PROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $list[$j]['poster']),
					'TEXT' => htmlspecialchars($list[$j]['text']),
					'TIME' => create_date_ip($config['default_dateformat'], $list[$j]['time'], $config['board_timezone']),
					'L_DELETE_NOTE' => $lang['Delete_note'],
					'U_DELETE' => $is_auth['auth_mod'] ? ($template->vars['U_VIEW_TOPIC'] . '&amp;delnote=' . $item['U_POST_ID'] . '.' . $j) : '',
				);
			}
			unset($item);
		}
	}

}

$topic_useful_box = false;
if (!$user->data['is_bot'])
{
	$rating_auth_data = rate_auth($user->data['user_id'], $forum_id, $topic_id);
	$rating_box = ((($rating_auth_data == RATE_AUTH_NONE) || ($rating_auth_data == RATE_AUTH_DENY)) ? false : true);
	$sb_box = $config['show_social_bookmarks'] ? true : false;
	$ltt_box = $config['link_this_topic'] ? true : false;
	$topic_useful_box = (($rating_box || $sb_box || $ltt_box) ? true : false);
}

if ($topic_useful_box)
{
	$template->assign_block_vars('switch_topic_useful', array());

	if ($sb_box)
	{
		$template->assign_block_vars('switch_topic_useful.social_bookmarks', array());
	}

	if ($rating_box)
	{
		ratings_view_topic();
	}

	if ($ltt_box)
	{
		$template->assign_block_vars('switch_topic_useful.link_this_topic', array());
	}
}

if ($posts_like_enabled)
{
	$template->assign_vars(array(
		'S_POSTS_LIKES' => true,
		)
	);
}

// Don't update the topic view counter if viewer is poster or a BOT
if (($postrow[0]['user_id'] != $user->data['user_id']) && !$user->data['is_bot'])
{
	// Update the topic view counter
	$sql = "UPDATE " . TOPICS_TABLE . "
		SET topic_views = topic_views + 1
		WHERE topic_id = " . $topic_id;
	$db->sql_query($sql);
}

//<!-- BEGIN Unread Post Information to Database Mod -->
if($user->data['upi2db_access'])
{
	delete_read_posts($read_posts);
}
//<!-- END Unread Post Information to Database Mod -->

$viewtopic_banner_top = get_ad('vtt');
$viewtopic_banner_bottom = get_ad('vtb');
$template->assign_vars(array(
	'VIEWTOPIC_BANNER_TOP' => $viewtopic_banner_top,
	'VIEWTOPIC_BANNER_BOTTOM' => $viewtopic_banner_bottom,
	)
);

if($can_reply)
{
	if (!function_exists('generate_smilies_row'))
	{
		include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
	}
	generate_smilies_row();
}

// Force to false page_nav because viewtopic has its own breadcrumbs...
$cms_page['page_nav'] = false;
full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>