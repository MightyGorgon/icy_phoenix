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
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_rate.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_rate.' . PHP_EXT);

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$page_number = (isset($_GET['page_number']) ? intval($_GET['page_number']) : (isset($_POST['page_number']) ? intval($_POST['page_number']) : false));
$page_number = ($page_number < 1) ? false : $page_number;

$start = (!$page_number) ? $start : (($page_number * $board_config['posts_per_page']) - $board_config['posts_per_page']);

// Activity - BEGIN
//if (defined('ACTIVITY_MOD'))
if (defined('ACTIVITY_MOD') && (ACTIVITY_MOD == true))
{
	include_once(IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'includes/functions_amod_plus.' . PHP_EXT);
	$q = "SELECT * FROM " . INA_HOF;
	$r = $db->sql_query($q);
	$hof_data = $db->sql_fetchrowset($r);
	$db->sql_freeresult($r);
}
// Activity - END

// Start initial var setup
$forum_id = 0;
$topic_id = 0;
$post_id = 0;

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

if (isset($_GET[POST_TOPIC_URL]))
{
	$topic_id = intval($_GET[POST_TOPIC_URL]);
}
elseif (isset($_GET['topic']))
{
	$topic_id = intval($_GET['topic']);
}

if (isset($_GET[POST_POST_URL]))
{
	$post_id = intval($_GET[POST_POST_URL]);
}

$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');

/*
$forum_id = !empty($_GET[POST_FORUM_URL]) ? intval($_GET[POST_FORUM_URL]) : (!empty($_POST[POST_FORUM_URL]) ? intval($_POST[POST_FORUM_URL]) : '0');
$topic_id = !empty($_GET[POST_TOPIC_URL]) ? intval($_GET[POST_TOPIC_URL]) : (!empty($_POST[POST_TOPIC_URL]) ? intval($_POST[POST_TOPIC_URL]) : '0');
$post_id = !empty($_GET[POST_POST_URL]) ? intval($_GET[POST_POST_URL]) : (!empty($_POST[POST_POST_URL]) ? intval($_POST[POST_POST_URL]) : '0');

 . POST_FORUM_URL . '=' . $forum_id . '&amp;'
 . POST_TOPIC_URL . '=' . $topic_id . '&amp;'
$forum_id_append . '&' . $topic_id_append . '&' . $post_id_append . $post_id_append_url
$forum_id_append . '&amp;' . $topic_id_append . '&amp;' . $post_id_append . $post_id_append_url
*/

$kb_mode = false;
$kb_mode_append = '';
$kb_mode_append_red = '';
$kb_mode_var = request_var('kb', '');
if (($kb_mode_var == 'on') && ($userdata['bot_id'] == false))
{
	$kb_mode = true;
	$kb_mode_append = '&amp;kb=on';
	$kb_mode_append_red = '&kb=on';
}

$download = (isset($_GET['download'])) ? $_GET['download'] : '';

if (!$topic_id && !$post_id)
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

// Find topic id if user requested a newer or older topic
if (isset($_GET['view']) && empty($_GET[POST_POST_URL]))
{
	if ($_GET['view'] == 'newest')
	{
		if (isset($_COOKIE[$board_config['cookie_name'] . '_sid']) || isset($_GET['sid']))
		{
			$session_id = isset($_COOKIE[$board_config['cookie_name'] . '_sid']) ? $_COOKIE[$board_config['cookie_name'] . '_sid'] : $_GET['sid'];
			if (!preg_match('/^[A-Za-z0-9]*$/', $session_id))
			{
				$session_id = '';
			}

			if ($session_id)
			{
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p, " . SESSIONS_TABLE . " s,  " . USERS_TABLE . " u
					WHERE s.session_id = '$session_id'
						AND u.user_id = s.session_user_id
						AND p.topic_id = '" . $topic_id . "'
						AND p.post_time >= u.user_lastvisit
					ORDER BY p.post_time ASC
					LIMIT 1";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain newer/older topic information', '', __LINE__, __FILE__, $sql);
				}

/* UPI2DB REPLACE
				if (!($row = $db->sql_fetchrow($result)))
				{
					message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
				}
*/
//<!-- BEGIN Unread Post Information to Database Mod -->
				if (!($row = $db->sql_fetchrow($result)))
				{
					if ($topic_id != 0)
					{
						redirect(VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red);
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

				if (isset($_GET['sid']))
				{
					$session_id_append = 'sid=' . $session_id . '&';
				}
				else
				{
					$session_id_append = '';
				}
				redirect(append_sid(VIEWTOPIC_MG . '?' . $session_id_append . $kb_mode_append_red . $forum_id_append . '&' . $topic_id_append . '&' . $post_id_append . $post_id_append_url));
			}
		}

		redirect(append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red, true));
	}
	elseif (($_GET['view'] == 'next') || ($_GET['view'] == 'previous'))
	{
		$sql_condition = ($_GET['view'] == 'next') ? '>' : '<';
		$sql_ordering = ($_GET['view'] == 'next') ? 'ASC' : 'DESC';

		$sql = "SELECT t.topic_id, t.forum_id
			FROM " . TOPICS_TABLE . " t, " . TOPICS_TABLE . " t2
			WHERE
				t2.topic_id = '" . $topic_id . "'
				AND t.forum_id = t2.forum_id
				AND t.topic_moved_id = 0
				AND t.topic_last_post_id $sql_condition t2.topic_last_post_id
			ORDER BY t.topic_last_post_id $sql_ordering
			LIMIT 1";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not obtain newer/older topic information", '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			$forum_id = intval($row['forum_id']);
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id = intval($row['topic_id']);
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			redirect(append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red));
		}
		else
		{
			$message = ($_GET['view'] == 'next') ? 'No_newer_topics' : 'No_older_topics';
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

//
// This rather complex gaggle of code handles querying for topics but
// also allows for direct linking to a post (and the calculation of which
// page the post is on and the correct display of viewtopic)
//
$join_sql_table = (!$post_id) ? '' : ", " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2 ";
$join_sql = (!$post_id) ? "t.topic_id = '" . $topic_id . "'" : "p.post_id = '" . $post_id . "' AND t.topic_id = p.topic_id AND p2.topic_id = p.topic_id AND p2.post_id <= '" . $post_id . "'";
$count_sql = (!$post_id) ? '' : ", COUNT(p2.post_id) AS prev_posts";

$order_sql = (!$post_id) ? '' : "GROUP BY p.post_id, t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.auth_ban, f.auth_greencard, f.auth_bluecard ORDER BY p.post_id ASC";

$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, t.title_compl_infos, t.topic_first_post_id, t.topic_calendar_time, t.topic_calendar_duration, f.forum_name, f.forum_status, f.forum_id, f.forum_similar_topics, f.forum_topic_views, f.forum_kb_mode, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.forum_rules, f.auth_ban, f.auth_greencard, f.auth_bluecard, fr.*" . $count_sql . "
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . FORUMS_RULES_TABLE . " fr" . $join_sql_table . "
	WHERE $join_sql
		AND f.forum_id = t.forum_id
		AND fr.forum_id = t.forum_id
		$order_sql";

attach_setup_viewtopic_auth($order_sql, $sql);

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if (!($forum_topic_data = $db->sql_fetchrow($result)))
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
$db->sql_freeresult($result);

$forum_id = intval($forum_topic_data['forum_id']);
$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
$topic_id = intval($forum_topic_data['topic_id']);
$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
$this_forum_auth_read = intval($forum_topic_data['auth_read']);

if ($forum_topic_data['forum_kb_mode'] == 1)
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
if ($board_config['disable_thanks_topics'] == false)
{
	// Check if the Thanks feature is active for this forum
	$sql = "SELECT forum_thanks
			FROM " . FORUMS_TABLE . "
			WHERE forum_id = '" . $forum_id . "'
			LIMIT 1";
	if (!($result = $db->sql_query($sql, false, 'forums_thanks_', FORUMS_CACHE_FOLDER)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain forum information', '', __LINE__, __FILE__, $sql);
	}
	$show_thanks = 0;
	$show_thanks_button = 0;
	while ($forum_thank_result = $db->sql_fetchrow($result))
	{
		$show_thanks = ($forum_thank_result['forum_thanks'] == 1) ? 1 : 0;
		$show_thanks_button = 0;
		if ($show_thanks && $userdata['session_logged_in'])
		{
			$sql_thanked = "SELECT topic_id
					FROM " . THANKS_TABLE . "
					WHERE topic_id = '" . $topic_id . "'
						AND user_id = '" . $userdata['user_id'] . "'
					LIMIT 1";
			if (!($result_thanked = $db->sql_query($sql_thanked)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain thanks information', '', __LINE__, __FILE__, $sql);
			}
			if ($has_thanked = $db->sql_fetchrow($result_thanked))
			{
				$show_thanks_button = 0;
			}
			else
			{
				$show_thanks_button = 1;
			}
			$db->sql_freeresult($result_thanked);
		}
	}
	$db->sql_freeresult($result);
}
else
{
	$show_thanks = 0;
}
// Thanks Mod - END

//
// Set or remove bookmark
//
if (isset($_GET['setbm']) || isset($_GET['removebm']))
{
	$redirect = VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red . '&start=' . $start . '&postdays=' . $post_days . '&postorder=' . $post_order . '&highlight=' . $_GET['highlight'];
	if ($userdata['session_logged_in'])
	{
		if (isset($_GET['setbm']) && $_GET['setbm'])
		{
			set_bookmark($topic_id);
		}
		elseif (isset($_GET['removebm']) && $_GET['removebm'])
		{
			remove_bookmark($topic_id);
		}
	}
	else
	{
		if (isset($_GET['setbm']) && $_GET['setbm'])
		{
			$redirect .= '&setbm=true';
		}
		elseif (isset($_GET['removebm']) && $_GET['removebm'])
		{
			$redirect .= '&removebm=true';
		}
		redirect(append_sid(LOGIN_MG . '?redirect=' . $redirect, true));
	}
	redirect(append_sid($redirect, true));
}

$cms_page_id = '3';
$cms_page_name = 'viewt';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

if ($download)
{
	$sql_download = ($download != -1) ? " AND p.post_id = " . intval($download) . " " : '';

	if (!$userdata['user_allowswearywords'])
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}

	$sql = "SELECT u.*, p.*
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.topic_id = $topic_id
			$sql_download
			AND u.user_id = p.poster_id
			ORDER BY p.post_time ASC, p.post_id ASC";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Could not create download stream for post.", '', __LINE__, __FILE__, $sql);
	}

	$download_file = '';

	$is_auth_read = array();
	$break = "\r\n";
	$line = '-----------------------------------';

	while ($row = $db->sql_fetchrow($result))
	{
		$is_auth_read = auth(AUTH_ALL, $row['forum_id'], $userdata);

		$poster_id = $row['user_id'];
		$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : $row['username'];

		$post_date = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);

		$post_subject = ($row['post_subject'] != '') ? $row['post_subject'] : '';

		$message = $row['post_text'];
		$message = strip_tags($message);
		$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
		$message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
		if($userdata['session_logged_in'])
		{
			$sql = "SELECT p.poster_id, p.topic_id
				FROM " . POSTS_TABLE . " p
				WHERE p.topic_id = '" . $topic_id . "'
				AND p.poster_id = '" . $userdata['user_id'] . "'";
			$resultat = $db->sql_query($sql);
			$show = $db->sql_numrows($resultat) ? true : false;
			if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
			{
				$show = true;
			}
		}

		if(!$show && preg_match('/\[hide/i', $message))
		{
			$search = array("/\[hide\](.*?)\[\/hide\]/");
			$replace = array($lang['xs_bbc_hide_message']. ':' . $break . $lang['xs_bbc_hide_message_explain'] . $break);
			$message =  preg_replace($search, $replace, $message);
		}
		$message = unprepare_message($message);
		$search = array('/&#40;/', '/&#41;/', '/&#58;/', '/&#91;/', '/&#93;/', '/&#123;/', '/&#125;/');
		$replace = array('(', ')', ':', '[', ']', '{', '}',);
		$message = preg_replace($search, $replace, $message);

		if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
		{
			$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
			$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
		}

		$download_file .= $line . $break . $poster . $break . $post_date . $break . $break . $post_subject . $break . $line . $break . $message . $break . $break . $break;
	}
	$db->sql_freeresult($result);

	$disp_folder = ($download == -1) ? 'Topic_' . $topic_id : 'Post_' . $download;
	$this_download_src = create_server_url() . (VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . (($download > 0) ? ('&' . POST_POST_URL . '=' . $download . '#p' . $download) : ''));

	$download_file = $this_download_src . $break . $download_file;

	if (!$is_auth_read['auth_read'])
	{
		$download_file = sprintf($lang['Sorry_auth_read'], $is_auth_read['auth_read_type']);
		$disp_folder = 'Download';
	}

	$filename = $board_config['sitename'] . '_' . (ereg_replace("[^A-Za-z0-9]", "_", $post_subject)) . '_' . $disp_folder . '_' . date('Ymd', time()) . '.txt';
	header('Content-Type: text/x-delimtext; name="' . $filename . '"');
	header('Content-Disposition: attachment;filename="' . $filename . '"');
	header('Content-Transfer-Encoding: plain/text');
	header('Content-Length: ' . strlen($download_file));
	print $download_file;

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
$active = 0;
$install_time = time();
$bypass = true;

if (!$board_config['disable_ftr'])
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

	if ($active && (strlen($ins_date) != 10))
	{
		$q = "UPDATE " . FORCE_READ_TABLE . " SET install_date = '" . $install_time . "'";
		$r = $db -> sql_query($q);
	}

	if (isset($ins_date) && (strlen($ins_date) != 10))
	{
		$ins_date = $install_time;
	}

	if (($viewed_mode == 'reading') || ($check_viewed != 'false'))
	{
		$bypass = true;
	}

	if ($active && ($check_viewed == 'false') && !$bypass)
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
}
// Force Topic Read - END

$similar_topics_enabled = false;
if (($board_config['similar_topics'] == 1) && ($forum_topic_data['forum_similar_topics'] == 1))
{
	$similar_topics_enabled = true;
}

if ($bypass)
{

	if ($similar_topics_enabled)
	{
		$similar_forums_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
		$similar_is_auth = $similar_forums_auth[$forum_id];
	}

	// Start auth check
	$is_auth = array();
	$is_auth = $tree['auth'][POST_FORUM_URL . $forum_id];

	if (!$is_auth['auth_read'])
	{
		if (!$userdata['session_logged_in'])
		{
			$redirect = $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red;
			$redirect .= ($post_id) ? '&' . $post_id_append : '';
			$redirect .= ($start) ? '&start=' . $start : '';
			redirect(append_sid(LOGIN_MG . '?redirect=' . VIEWTOPIC_MG . '&' . $redirect, true));
		}
		$message = sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);
		message_die(GENERAL_MESSAGE, $message);
	}
	// End auth check

	// Who viewed a topic - BEGIN
	if (($board_config['disable_topic_view'] == 0) && ($forum_topic_data['forum_topic_views'] == 1))
	{
		$user_id = $userdata['user_id'];
		$sql = 'UPDATE ' . TOPIC_VIEW_TABLE . ' SET topic_id = "' . $topic_id . '", view_time = "' . time() . '", view_count = view_count + 1 WHERE topic_id=' . $topic_id . ' AND user_id = ' . $user_id;
		if (!$db->sql_query($sql) || !$db->sql_affectedrows())
		{
			$sql = 'INSERT IGNORE INTO ' . TOPIC_VIEW_TABLE . ' (topic_id, user_id, view_time, view_count)
				VALUES (' . $topic_id . ', "' . $user_id . '", "' . time() . '", "1")';
			if (!($db->sql_query($sql)))
			{
				message_die(CRITICAL_ERROR, 'Error create user view topic information ', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	// Who viewed a topic - END

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

	if ($post_id)
	{
		$start = floor(($forum_topic_data['prev_posts'] - 1) / intval($board_config['posts_per_page'])) * intval($board_config['posts_per_page']);
	}

	// Is user watching this thread?
	if($userdata['session_logged_in'])
	{
		$can_watch_topic = true;

		$sql = "SELECT notify_status
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = '" . $topic_id . "'
				AND user_id = '" . $userdata['user_id'] . "'
			LIMIT 1";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not obtain topic watch information", '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			if (isset($_GET['unwatch']))
			{
				if ($_GET['unwatch'] == 'topic')
				{
					$is_watching_topic = 0;
					$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
						WHERE topic_id = $topic_id
							AND user_id = " . $userdata['user_id'];
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, "Could not delete topic watch information", '', __LINE__, __FILE__, $sql);
					}
				}

				$redirect_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $start . $kb_mode_append);
				meta_refresh(3, $redirect_url);

				$message = $lang['No_longer_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $start . $kb_mode_append) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$is_watching_topic = true;

				if ($row['notify_status'])
				{
					$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
						SET notify_status = 0
						WHERE topic_id = $topic_id
							AND user_id = " . $userdata['user_id'];
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, "Could not update topic watch information", '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}
		else
		{
			if (isset($_GET['watch']))
			{
				if ($_GET['watch'] == 'topic')
				{
					$is_watching_topic = true;
					$sql = "INSERT INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
						VALUES (" . $userdata['user_id'] . ", $topic_id, 0)";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, "Could not insert topic watch information", '', __LINE__, __FILE__, $sql);
					}
				}

				$redirect_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;' . 'start=' . $start);
				meta_refresh(3, $redirect_url);

				$message = $lang['You_are_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;' . '&amp;start=' . $start) . '">', '</a>');
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
		if (isset($_GET['unwatch']))
		{
			if ($_GET['unwatch'] == 'topic')
			{
				redirect(append_sid(LOGIN_MG . '?redirect=' . VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red . '&unwatch=topic', true));
			}
		}
		else
		{
			$can_watch_topic = 0;
			$is_watching_topic = 0;
		}
	}

	//
	// Generate a 'Show posts in previous x days' select box. If the postdays var is POSTed
	// then get it's value, find the number of topics with dates newer than it (to properly
	// handle pagination) and alter the main query
	//
	$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
	$previous_days_text = array($lang['All_Posts'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

	if(!empty($_POST['postdays']) || !empty($_GET['postdays']))
	{
		$post_days = (!empty($_POST['postdays'])) ? intval($_POST['postdays']) : intval($_GET['postdays']);
		$min_post_time = time() - (intval($post_days) * 86400);

		$sql = "SELECT COUNT(p.post_id) AS num_posts
			FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
			WHERE t.topic_id = $topic_id
				AND p.topic_id = t.topic_id
				AND p.post_time >= $min_post_time";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not obtain limited topics count information", '', __LINE__, __FILE__, $sql);
		}

		$total_replies = ($row = $db->sql_fetchrow($result)) ? intval($row['num_posts']) : 0;

		$limit_posts_time = "AND p.post_time >= $min_post_time ";

		if (!empty($_POST['postdays']))
		{
			$start = 0;
		}
	}
	else
	{
		$total_replies = intval($forum_topic_data['topic_replies']) + 1;

		$limit_posts_time = '';
		$post_days = 0;
	}

	$select_post_days = '<select name="postdays">';
	for($i = 0; $i < count($previous_days); $i++)
	{
		$selected = ($post_days == $previous_days[$i]) ? ' selected="selected"' : '';
		$select_post_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
	}
	$select_post_days .= '</select>';

	// Decide how to order the post display
	if (!empty($_POST['postorder']) || !empty($_GET['postorder']))
	{
		$post_order = (!empty($_POST['postorder'])) ? htmlspecialchars($_POST['postorder']) : htmlspecialchars($_GET['postorder']);
		$post_time_order = ($post_order == 'asc') ? 'ASC' : 'DESC';
	}
	else
	{
		$post_order = 'asc';
		$post_time_order = 'ASC';
	}

	$select_post_order = '<select name="postorder">';
	if ($post_time_order == 'ASC')
	{
		$select_post_order .= '<option value="asc" selected="selected">' . $lang['Oldest_First'] . '</option><option value="desc">' . $lang['Newest_First'] . '</option>';
	}
	else
	{
		$select_post_order .= '<option value="asc">' . $lang['Oldest_First'] . '</option><option value="desc" selected="selected">' . $lang['Newest_First'] . '</option>';
	}
	$select_post_order .= '</select>';

	$user_ids = array();
	$user_ids2 = array();
	if($userdata['session_logged_in'])
	{
		$user_ids[$userdata['user_id']] = $userdata['username'];
	}
	// Custom Profile Fields MOD
	$profile_data = get_fields('WHERE view_in_topic = ' . VIEW_IN_TOPIC . ' AND users_can_view = ' . ALLOW_VIEW);
	$profile_data_sql = get_udata_txt($profile_data, 'u.');
	// END Custom Profile Fields MOD

	// Similar Topics - BEGIN
	if ($similar_topics_enabled)
	{

		if ($board_config['similar_ignore_forums_ids'])
		{
			$ignore_forums_ids = array_map('intval', explode("\n", trim($board_config['similar_ignore_forums_ids'])));
		}
		else
		{
			$ignore_forums_ids = array();
		}

		// Get forum auth information to insure privacy of hidden topics
		$forums_auth_sql = '';
		//foreach ($similar_forums_auth as $k=>$v)
		//$similar_forums_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
		foreach ($similar_forums_auth as $k => $v)
		{
			if (count($ignore_forums_ids) && in_array($k, $ignore_forums_ids))
			{
				continue;
			}
			if ($v['auth_view'] && $v['auth_read'])
			{
				$forums_auth_sql .= (($forums_auth_sql == '') ? '': ', ') . $k;
			}
		}
		if ($forums_auth_sql != '')
		{
			$forums_auth_sql = ' AND t.forum_id IN (' . $forums_auth_sql . ') ';
		}

		if ($board_config['similar_stopwords'])
		{
			// encoding match for workaround
			$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

			// check against stopwords start
			@include_once(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
			$stopword_array = @file(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt');
			$synonym_array = array();
			// check against stopwords end

			$title_search = '';
			$title_search_array = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('post', stripslashes($topic_title), $stopword_array, $synonym_array), 'search') : split(' ', $topic_title);

			for ($i = 0; $i < count($title_search_array); $i++)
			{
				$title_search .= (($title_search == '') ? '': ' ') . $title_search_array[$i];
			}
		}
		else
		{
			$title_search = $topic_title;
		}

		/*
		if (!empty($forum_topic_data['topic_desc']) && $board_config['similar_topicdesc'])
		{
			if ($board_config['similar_stopwords'])
			{
				$topicdesc = '';
				$topic_desc_array = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('post', stripslashes($forum_topic_data['topic_desc']), $stopword_array, $synonym_array), 'search') : split(' ', $forum_topic_data['topic_desc']);
				for ($i = 0; $i < count($topic_desc_array); $i++)
				{
					$topicdesc .= (($topicdesc == '') ? '': ' ') . $topic_desc_array[$i];
				}
			}
			else
			{
				$topicdesc = $forum_topic_data['topic_desc'];
			}
			$sql_topic_desc = "+MATCH(t.topic_desc) AGAINST('" . addslashes($topicdesc) . "')";
		}

		$sql_match = "MATCH(t.topic_title) AGAINST('" . addslashes($title_search) . "')" . $sql_topic_desc;
		*/
		$sql_match = "MATCH(t.topic_title) AGAINST('" . addslashes($title_search) . "')";

		if ($board_config['similar_sort_type'] == 'time')
		{
			$sql_sort = 'p.post_time';
		}
		else
		{
			$sql_sort = 'relevance';
		}

		//ORDER BY t.topic_type DESC, ' . $sql_sort . ' DESC LIMIT 0,' . intval($board_config['similar_max_topics']);
		$sql = "SELECT t.*, u.user_id, u.username, u.user_active, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_color as user_color2, f.forum_id, f.forum_name, p.post_time, p.post_username, $sql_match as relevance
					FROM ". TOPICS_TABLE ." t, ". USERS_TABLE ." u, ". FORUMS_TABLE ." f, ". POSTS_TABLE ." p, " . USERS_TABLE . " u2
					WHERE t.topic_id <> $topic_id $forums_auth_sql
					AND $sql_match
					AND t.forum_id = f.forum_id
					AND p.poster_id = u2.user_id
					AND p.post_id = t.topic_last_post_id
					AND t.topic_poster = u.user_id
					AND t.topic_status <> " . TOPIC_MOVED . '
					GROUP BY t.topic_id
					ORDER BY ' . $sql_sort . ' DESC LIMIT 0,' . intval($board_config['similar_max_topics']);
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not get main information for similar topics", '', __LINE__, __FILE__, $sql);
		}
		$similar_topics = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$count_similar = count($similar_topics);

		// Switch again to false because we will show the box only if we have similar topics!
		$similar_topics_enabled = false;
		if ($count_similar > 0)
		{
			$similar_topics_enabled = true;
		}
	}
	// Similar Topics - END

	//if ($board_config['switch_poster_info_topic'] == true)
	// Use the above code if you want even guests to be shown the extra info
	if (($board_config['switch_poster_info_topic'] == true) && $userdata['session_logged_in'])
	{
		$parse_extra_user_info = true;
		// Query Styles
		$sql = "SELECT themes_id, style_name
			FROM " . THEMES_TABLE . "
			ORDER BY template_name, themes_id";
		if (!($result = $db->sql_query($sql, false, 'themes_')))
		{
			message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
		}
		$styles_list_id = array();
		$styles_list_name = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$styles_list_id[] = $row['themes_id'];
			$styles_list_name[] = $row['style_name'];
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$parse_extra_user_info = false;
	}

	// Activity - BEGIN
	//if (defined('ACTIVITY_MOD') && (ACTIVITY_MOD == true))
	if (defined('ACTIVITY_MOD'))
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
	$self_sql = (intval($is_auth['auth_read']) == AUTH_SELF) ? " AND t.topic_poster = u2.user_id AND (u2.user_id = '" . $userdata['user_id'] . "' OR t.topic_type = '" . POST_GLOBAL_ANNOUNCE . "' OR t.topic_type = '" . POST_ANNOUNCE . "' OR t.topic_type = '" . POST_STICKY . "')" : '';
	// Self AUTH - END

	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_posts, u.user_from, u.user_from_flag, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_skype, u.user_regdate, u.user_msnm, u.user_viewemail, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, u.user_allow_viewonline, u.user_session_time, u.user_warnings, u.user_level, u.user_birthday, u.user_next_birthday_greeting, u.user_gender, u.user_personal_pics_count, u.user_style, u.user_lang" . $activity_sql . $profile_data_sql . ", u.ct_miserable_user, p.*, t.topic_poster, t.title_compl_infos
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . TOPICS_TABLE . " t" . $self_sql_tables . "
		WHERE p.topic_id = $topic_id
			AND t.topic_id = p.topic_id
			AND u.user_id = p.poster_id
			" . $limit_posts_time . "
			" . $self_sql . "
		ORDER BY p.post_time $post_time_order
		LIMIT " . $start . ", " . $board_config['posts_per_page'];

	// MG Cash MOD For IP - BEGIN
	if (defined('CASH_MOD'))
	{
		$cm_viewtopic->generate_columns($template, $forum_id, $sql);
	}
	// MG Cash MOD For IP - END

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
	}

	$postrow = array();
	if ($row = $db->sql_fetchrow($result))
	{
		do
		{
			if($row['user_id'] > 0)
			{
				$user_ids[$row['user_id']] = $row['username'];
			}
			/*
			if(defined('LOCAL_DEBUG'))
			{
				$row['post_text_compiled'] = '';
			}
			*/
			$postrow[] = $row;
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
		$total_posts = count($postrow);
	}
	else
	{
		include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
		sync('topic', $topic_id);
		message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
	}

	$resync = false;
	if (($forum_topic_data['topic_replies'] + 1) < ($start + count($postrow)))
	{
		$resync = true;
	}
	elseif (($start + $board_config['posts_per_page']) > $forum_topic_data['topic_replies'])
	{
		$row_id = intval($forum_topic_data['topic_replies']) % intval($board_config['posts_per_page']);
		if ($postrow[$row_id]['post_id'] != $forum_topic_data['topic_last_post_id'] || $start + count($postrow) < $forum_topic_data['topic_replies'])
		{
			$resync = true;
		}
	}
	elseif (count($postrow) < $board_config['posts_per_page'])
	{
		$resync = true;
	}

	if ($resync)
	{
		include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
		sync('topic', $topic_id);

		$result = $db->sql_query('SELECT COUNT(post_id) AS total FROM ' . POSTS_TABLE . ' WHERE topic_id = ' . $topic_id);
		$row = $db->sql_fetchrow($result);
		$total_replies = $row['total'];
	}

	// Mighty Gorgon - Multiple Ranks - BEGIN
	require_once(IP_ROOT_PATH . 'includes/functions_mg_ranks.' . PHP_EXT);
	$ranks_sql = query_ranks();
	// Mighty Gorgon - Multiple Ranks - END

	// Define censored word matches
	if (!$userdata['user_allowswearywords'])
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}
	// Start Autolinks For phpBB Mod
	$orig_autolink = array();
	$replacement_autolink = array();
	obtain_autolink_list($orig_autolink, $replacement_autolink, $forum_id);
	// End Autolinks For phpBB Mod

	// Censor topic title
	if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
	{
		$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
	}

	// Was a highlight request part of the URI?
	$highlight_match = $highlight = '';

	if (isset($_GET['highlight']))
	{
		$_GET['highlight'] = addslashes(preg_replace('#[][\\/%():><{}`]#', ' ', $_GET['highlight']));

		// Split words and phrases
		$words = explode(' ', trim(htmlspecialchars($_GET['highlight'])));

		for($i = 0; $i < count($words); $i++)
		{
			if (trim($words[$i]) != '')
			{
				$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
			}
		}
		unset($words);

		$highlight = urlencode($_GET['highlight']);
		$highlight_match = phpbb_rtrim($highlight_match, "\\");
	}

	// Post, reply and other URL generation for templating vars
	$new_topic_url = append_sid('posting.' . PHP_EXT . '?mode=newtopic&amp;' . $forum_id_append);
	$reply_topic_url = append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . $forum_id_append . '&amp;' . $topic_id_append);
	$view_forum_url = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . $kb_mode_append);
	$view_prev_topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;view=previous');
	$view_next_topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;view=next');

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
	/*
	$nav_links['up'] = array(
		'url' => append_sid(make_url_friendly($forum_name) . '-vf' . $forum_id . '.html'),
		'title' => $forum_name
	);
	*/
	//SEO TOOLKIT END

	$is_this_locked = ($forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED) ? true : false;
	$reply_img = $is_this_locked ? $images['reply_locked'] : $images['reply_new'];
	$reply_alt = $is_this_locked ? $lang['Topic_locked'] : $lang['Reply_to_topic'];
	$post_img = ($forum_topic_data['forum_status'] == FORUM_LOCKED) ? $images['post_locked'] : $images['post_new'];
	$post_alt = ($forum_topic_data['forum_status'] == FORUM_LOCKED) ? $lang['Forum_locked'] : $lang['Post_new_topic'];

	if(!$userdata['session_logged_in'] || !$is_auth['auth_reply'] || ($is_this_locked && !$is_auth['auth_mod']))
	{
		$can_reply = false;
	}
	else
	{
		$can_reply = true;
		$template->assign_block_vars('switch_can_reply', array());
	}

	// Begin Thanks Mod
	$thank_img = $images['thanks'];
	$thank_alt = $lang['thanks_alt'];
	if ($show_thanks_button && ($postrow[0]['topic_poster'] != $userdata['user_id']))
	{
		$template->assign_var('S_THANKS', true);
	}
	// End Thanks Mod

	// Set a cookie for this topic
	if ($userdata['session_logged_in'])
	{
		$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_t']) : array();
		$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_f']) : array();

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
			$topic_last_read = $userdata['user_lastvisit'];
		}

		if ((count($tracking_topics) >= 150) && empty($tracking_topics[$topic_id]))
		{
			asort($tracking_topics);
			unset($tracking_topics[key($tracking_topics)]);
		}

		$tracking_topics[$topic_id] = time();

		setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
	}

//<!-- BEGIN Unread Post Information to Database Mod -->
	if($userdata['upi2db_access'])
	{
		$unread_new_posts = 0;
		$unread_edit_posts = 0;
		for($i = 0; $i < $total_posts; $i++)
		{
			if (count($unread[$topic_id]['new_posts']) && in_array($postrow[$i]['post_id'], $unread[$topic_id]['new_posts']))
			{
				++$unread_new_posts;
			}
			if (count($unread[$topic_id]['edit_posts']) && in_array($postrow[$i]['post_id'], $unread[$topic_id]['edit_posts']))
			{
				++$unread_edit_posts;
			}
		}
	}
//<!-- END Unread Post Information to Database Mod -->

	// Load templates
	if ($kb_mode == true)
	{
		$template->set_filenames(array('body' => 'viewtopic_kb_body.tpl'));
	}
	else
	{
		$template->set_filenames(array('body' => 'viewtopic_body.tpl'));
	}

	make_jumpbox(VIEWFORUM_MG, $forum_id);

	// Output page header
	if ($board_config['display_viewonline'])
	{
		define('SHOW_ONLINE', true);
	}

	$topic_title = $topic_title_prefix . $topic_title;
	$page_title = $topic_title;
	$meta_description = '';
	$meta_keywords = '';
	$template->assign_var('S_VIEW_TOPIC', true);
	if ($board_config['show_icons'] == true)
	{
		$template->assign_var('S_SHOW_ICONS', true);
	}
	else
	{
		$template->assign_var('S_SHOW_LINKS', true);
	}
	$cms_page_nav = false;
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	if ($similar_topics_enabled == true)
	{
		include(IP_ROOT_PATH . 'includes/similar_topics.' . PHP_EXT);
	}

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

	$topic_mod = '';

	if ($is_auth['auth_mod'])
	{
		$s_auth_can .= sprintf($lang['Rules_moderate'], '<a href="modcp.' . PHP_EXT . '?' . $forum_id_append . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		// Full string to append as a reference for FORUM TOPIC POST (FTP)
		$full_ftp_append = (($forum_id_append == '') ? '' : ($forum_id_append . '&amp;')) . (($topic_id_append == '') ? '' : ($topic_id_append . '&amp;')) . (($post_id_append == '') ? '' : ($post_id_append . '&amp;'));

		if ($lofi)
		{
			$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=delete&amp;sid=' . $userdata['session_id'] . '" title="' . $lang['Delete_topic'] . '">' . $lang['Delete_topic'] . '</a>&nbsp;::&nbsp;';

			$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=move&amp;sid=' . $userdata['session_id'] . '" title="' . $lang['Move_topic'] . '">' . $lang['Move_topic'] . '</a>&nbsp;<br />';

			$topic_mod .= ($forum_topic_data['topic_status'] == TOPIC_UNLOCKED) ? '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=lock&amp;sid=' . $userdata['session_id'] . '" title="' . $lang['Lock_topic'] . '">' . $lang['Lock_topic'] . '</a>&nbsp;::&nbsp;' : '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=unlock&amp;sid=' . $userdata['session_id'] . '" title="' . $lang['Unlock_topic'] . '">' . $lang['Unlock_topic'] . '</a>&nbsp;::&nbsp;';

			$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=split&amp;sid=' . $userdata['session_id'] . '" title="' . $lang['Split_topic'] . '">' . $lang['Split_topic'] . '</a>&nbsp;';

			$topic_mod .= '<a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=merge&amp;sid=' . $userdata['session_id'] . '" title="' . $lang['Merge_topic'] . '">' . $lang['Merge_topic'] . '</a>&nbsp;<br />';
			if ($board_config['bin_forum'] != false)
			{
				$topic_mod .= '<a href="bin.' . PHP_EXT . '?' . $full_ftp_append . 'sid=' . $userdata['session_id'] . '" title="' . $lang['Move_bin'] . '">' . $lang['Move_bin'] . '</a>&nbsp;';
			}
		}
		else
		{
			if ($board_config['bin_forum'] != false)
			{
				$topic_mod .= '<span class="img-btn"><a href="bin.' . PHP_EXT . '?' . $full_ftp_append . 'sid=' . $userdata['session_id'] . '"><img src="' . $images['topic_mod_bin'] . '" alt="' . $lang['Move_bin'] . '" title="' . $lang['Move_bin'] . '" /></a></span>&nbsp;';
			}
			$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=delete&amp;sid=' . $userdata['session_id'] . '" ><img src="' . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_topic'] . '" title="' . $lang['Delete_topic'] . '" /></a></span>&nbsp;';

			$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=move&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move_topic'] . '" title="' . $lang['Move_topic'] . '" /></a></span>&nbsp;';

			$topic_mod .= ($forum_topic_data['topic_status'] == TOPIC_UNLOCKED) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=lock&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['topic_mod_lock'] . '" alt="' . $lang['Lock_topic'] . '" title="' . $lang['Lock_topic'] . '" /></a></span>&nbsp;' : '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=unlock&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['topic_mod_unlock'] . '" alt="' . $lang['Unlock_topic'] . '" title="' . $lang['Unlock_topic'] . '" /></a></span>&nbsp;';

			$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=split&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['topic_mod_split'] . '" alt="' . $lang['Split_topic'] . '" title="' . $lang['Split_topic'] . '" /></a></span>&nbsp;';

			$topic_mod .= '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=merge&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['topic_mod_merge'] . '" alt="' . $lang['Merge_topic'] . '" title="' . $lang['Merge_topic'] . '" /></a></span>&nbsp;<br /><br />';

			$normal_button = '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=normalize&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['normal_post'] . '" alt="' . $lang['Mod_CP_normal'] . '" title="' . $lang['Mod_CP_normal2'] . '" /></a></span>&nbsp;';

			$sticky_button = ($is_auth['auth_sticky']) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=sticky&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['sticky_post'] . '" alt="' . $lang['Mod_CP_sticky'] . '" title="' . $lang['Mod_CP_sticky2'] . '" /></a></span>&nbsp;' : '';

			$announce_button = ($is_auth['auth_announce']) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=announce&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['announce_post'] . '" alt="' . $lang['Mod_CP_announce'] . '" title="' . $lang['Mod_CP_announce2'] . '" /></a></span>&nbsp;' : '';

			$global_button = ($is_auth['auth_globalannounce']) ? '<span class="img-btn"><a href="modcp.' . PHP_EXT . '?' . $full_ftp_append . 'mode=super_announce&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images['gannounce_post'] . '" alt="' . $lang['Mod_CP_global'] . '" title="' . $lang['Mod_CP_global2'] . '" /></a></span>&nbsp;' : '';

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
	//if (!(($userdata['user_level'] == 0) && ($userdata['user_id'] != $row['topic_poster'])))
	if ($is_auth['auth_edit'] || ($userdata['user_id'] == $row['topic_poster']))
	{
		$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . " ORDER BY title_info ASC";
		if (!($result = $db->sql_query($sql, false, 'topics_prefixes_', TOPICS_CACHE_FOLDER)))
		{
			message_die(GENERAL_MESSAGE, 'Unable to query Quick Title Addon informations.');
		}
		$select_title = '<form action="modcp.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '" method="post"><br /><br /><select name="qtnum"><option value="-1">---</option>';
		while ($row = $db->sql_fetchrow($result))
		{
			$addon = str_replace('%mod%', addslashes($userdata['username']), $row['title_info']);
			$dateqt = ($row['date_format'] == '') ? create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']) : create_date($row['date_format'], time(), $board_config['board_timezone']);
			$addon = str_replace('%date%', $dateqt, $addon);
			$select_title .= '<option value="' . $row['id'] . '">' . htmlspecialchars($addon) . '</option>';
		}
		$db->sql_freeresult($result);
		$select_title .= '</select>&nbsp;<input type="submit" name="quick_title_edit" class="liteoption" value="' . $lang['Edit_title'] . '"/><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '"/><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '"/></form>';
		$topic_mod .= $select_title;
	}

	if ($kb_mode == true)
	{
		$s_kb_mode_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;kb=off' . '&amp;start=' . $start);
		$s_kb_mode = '<a href="' . $s_kb_mode_url . '">' . $lang['KB_MODE_OFF'] . '</a>';
		$s_kb_mode_img = (isset($images['topic_kb_off'])) ? '<a href="' . $s_kb_mode_url . '"><img src="' . $images['topic_kb_off'] . '" alt="' . $lang['KB_MODE_OFF'] . '" title="' . $lang['KB_MODE_OFF'] . '" /></a>' : '';
	}
	else
	{
		$s_kb_mode_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;kb=on' . '&amp;start=' . $start);
		$s_kb_mode = '<a href="' . $s_kb_mode_url . '">' . $lang['KB_MODE_ON'] . '</a>';
		$s_kb_mode_img = (isset($images['topic_kb_on'])) ? '<a href="' . $s_kb_mode_url . '"><img src="' . $images['topic_kb_on'] . '" alt="' . $lang['KB_MODE_ON'] . '" title="' . $lang['KB_MODE_ON'] . '" /></a>' : '';
	}

	// Topic watch information
	$s_watching_topic = '';
	if ($can_watch_topic)
	{
		if ($is_watching_topic)
		{
			$s_watching_topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;unwatch=topic&amp;start=' . $start);
			$s_watching_topic = '<a href="' . $s_watching_topic_url . '">' . $lang['Stop_watching_topic'] . '</a>';
			$s_watching_topic_img = (isset($images['topic_un_watch'])) ? '<a href="' . $s_watching_topic_url . '"><img src="' . $images['topic_un_watch'] . '" alt="' . $lang['Stop_watching_topic'] . '" title="' . $lang['Stop_watching_topic'] . '" /></a>' : '';
		}
		else
		{
			$s_watching_topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;watch=topic&amp;start=' . $start);
			$s_watching_topic = '<a href="' . $s_watching_topic_url . '">' . $lang['Start_watching_topic'] . '</a>';
			$s_watching_topic_img = (isset($images['topic_watch'])) ? '<a href="' . $s_watching_topic_url . '"><img src="' . $images['topic_watch'] . '" alt="' . $lang['Start_watching_topic'] . '" title="' . $lang['Start_watching_topic'] . '" /></a>' : '';
		}
	}

	// Bookmark information
	if ($userdata['session_logged_in'])
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
			'U_BOOKMARK_ACTION' => append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;start=' . $start . '&amp;postdays=' . $post_days . '&amp;postorder=' . $post_order . '&amp;highlight=' . $_GET['highlight'] . $bm_action)
			)
		);
	}
//<!-- BEGIN Unread Post Information to Database Mod -->
	if($userdata['upi2db_access'])
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

	if ($total_replies > (10 * $board_config['posts_per_page']))
	{
		$template->assign_var('S_EXTENDED_PAGINATION', true);
	}

	// If we've got a hightlight set pass it on to pagination,
	// I get annoyed when I lose my highlight after the first page.
	$pagination = ($highlight != '') ? generate_pagination(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;postdays=' . $post_days . '&amp;postorder=' . $post_order . '&amp;highlight=' . $highlight, $total_replies, $board_config['posts_per_page'], $start) : generate_pagination(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;postdays=' . $post_days . '&amp;postorder=' . $post_order, $total_replies, $board_config['posts_per_page'], $start);
	$current_page = get_page($total_replies, $board_config['posts_per_page'], $start);
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
	if (($board_config['disable_topic_view'] == 0) && ($forum_topic_data['forum_topic_views'] == 1) && ($userdata['user_level'] == ADMIN))
	{
		$topic_viewed_link = append_sid('topic_view_users.' . PHP_EXT . '?' . $forum_id_append . '&amp;' . $topic_id_append);
	}

	if ($board_config['show_social_bookmarks'] == true)
	{
		$template->assign_block_vars('social_bookmarks', array());
	}
	$topic_title_enc = urlencode(ip_utf8_decode($topic_title));
	// URL Rewrite - BEGIN
	// Rewrite Social Bookmars URLs if any of URL Rewrite rules has been enabled
	// Forum ID and KB Mode removed from topic_url_enc to avoid compatibility problems with redirects in tell a friend
	if (($board_config['url_rw'] == true) || ($board_config['url_rw_guests'] == true))
	{
		$topic_url_ltt = htmlspecialchars((create_server_url() . make_url_friendly($topic_title) . '-vt' . $topic_id . '.html') . ($kb_mode ? ('?' . $kb_mode_append) : ''));
		$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . make_url_friendly($topic_title) . '-vt' . $topic_id . '.html'));
	}
	else
	{
		$topic_url_ltt = htmlspecialchars(ip_utf8_decode(create_server_url() . VIEWTOPIC_MG . '?' . $forum_id_append . '&' . $topic_id_append . $kb_mode_append_red));
		$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . VIEWTOPIC_MG . '?' . $topic_id_append));
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
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / intval($board_config['posts_per_page'])) + 1), ceil($total_replies / intval($board_config['posts_per_page']))),

		'POST_IMG' => $post_img,
		'REPLY_IMG' => $reply_img,
		'THANKS_IMG' => $thank_img,

		'TOPIC_TITLE_ENC' => $topic_title_enc,
		'TOPIC_URL_ENC' => $topic_url_enc,
		'TOPIC_URL_LTT' => $topic_url_ltt,
		'L_DOWNLOAD_POST' => $lang['Download_post'],
		'L_DOWNLOAD_TOPIC' => $lang['Download_topic'],
		'DOWNLOAD_TOPIC' => append_sid(VIEWTOPIC_MG . '?download=-1&amp;' . $forum_id_append . '&amp;' . $topic_id_append),
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
		'S_SELECT_POST_DAYS' => $select_post_days,
		'S_SELECT_POST_ORDER' => $select_post_order,
		'S_POST_DAYS_ACTION' => append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;start=' . $start),
		'S_AUTH_LIST' => $s_auth_can,
		'S_TOPIC_ADMIN' => $topic_mod,
		'IS_KB_MODE' => ($kb_mode == true) ? true : false,
		'S_KB_MODE' => !empty($s_kb_mode) ? $s_kb_mode : '',
		'S_KB_MODE_IMG' => !empty($s_kb_mode_img) ? $s_kb_mode_img : '',
		'S_WATCH_TOPIC' => !empty($s_watching_topic) ? $s_watching_topic : '',
		'S_WATCH_TOPIC_IMG' => !empty($s_watching_topic_img) ? $s_watching_topic_img : '',

		'U_VIEW_TOPIC' => append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . (!empty($start) ? ('&amp;start=' . $start) : '') . (!empty($post_days) ? ('&amp;postdays=' . $post_days) : '') . (!empty($post_order) ? ('&amp;postorder=' . $post_order) : '') . (!empty($highlight) ? ('&amp;highlight=' . $highlight) : '') . (($kb_mode == true) ? '&amp;kb=on' : '')),
		'U_VIEW_TOPIC_BASE' => append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append),
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
	if (!empty($forum_topic_data['topic_vote']))
	{
		$s_hidden_fields = '';

		$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
			FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
			WHERE vd.topic_id = $topic_id
				AND vr.vote_id = vd.vote_id
			ORDER BY vr.vote_option_id ASC";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not obtain vote data for this topic", '', __LINE__, __FILE__, $sql);
		}

		if ($vote_info = $db->sql_fetchrowset($result))
		{
			$db->sql_freeresult($result);
			$vote_options = count($vote_info);

			$vote_id = $vote_info[0]['vote_id'];
			$vote_title = $vote_info[0]['vote_text'];

			$sql = "SELECT vote_id
				FROM " . VOTE_USERS_TABLE . "
				WHERE vote_id = $vote_id
					AND vote_user_id = " . intval($userdata['user_id']);
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Could not obtain user vote data for this topic", '', __LINE__, __FILE__, $sql);
			}

			$user_voted = ($row = $db->sql_fetchrow($result)) ? true : 0;
			$db->sql_freeresult($result);

			if (isset($_GET['vote']) || isset($_POST['vote']))
			{
				$view_result = (((isset($_GET['vote'])) ? $_GET['vote'] : $_POST['vote']) == 'viewresult') ? true : 0;
			}
			else
			{
				$view_result = 0;
			}

			$poll_expired = ($vote_info[0]['vote_length']) ? (($vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time()) ? true : 0) : 0;

			if ($user_voted || $view_result || $poll_expired || !$is_auth['auth_vote'] || $forum_topic_data['topic_status'] == TOPIC_LOCKED)
			{
				$template->set_filenames(array('pollbox' => 'viewtopic_poll_result.tpl'));

				$vote_results_sum = 0;

				for($i = 0; $i < $vote_options; $i++)
				{
					$vote_results_sum += $vote_info[$i]['vote_result'];
				}

				$vote_graphic = 0;
				$vote_graphic_max = count($images['voting_graphic']);

				for($i = 0; $i < $vote_options; $i++)
				{
					$vote_percent = ($vote_results_sum > 0) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
					// [Begin] XS Poll Color - Edited by MG
						if ($vote_percent <= 0.3)
						{
							$vote_color = 'red';
						}
						elseif (($vote_percent > 0.3) && ($vote_percent <= 0.6))
						{
							$vote_color = 'blue';
						}
						elseif ($vote_percent > 0.6)
						{
							$vote_color = 'green';
						}
					// [End] XS Poll Color - Edited by MG
					$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);

					$voting_bar = 'voting_graphic_' . $vote_color;
					$voting_bar_body = 'voting_graphic_' . $vote_color . '_body';
					$voting_bar_left = 'voting_graphic_' . $vote_color . '_left';
					$voting_bar_right = 'voting_graphic_' . $vote_color . '_right';

					$voting_bar_img = $images[$voting_bar];
					$voting_bar_body_img = $images[$voting_bar_body];
					$voting_bar_left_img = $images[$voting_bar_left];
					$voting_bar_right_img = $images[$voting_bar_right];

					$vote_graphic_img = $images['voting_graphic'][$vote_graphic];
					$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;

					if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
					{
						$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
					}

					$template->assign_block_vars('poll_option', array(
						'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
						'POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
						'POLL_OPTION_PCT' => $vote_percent * 100,
						'POLL_OPTION_PERCENT' => sprintf('%.1d%%', ($vote_percent * 100)),
						'POLL_GRAPHIC' => $voting_bar_img,
						'POLL_GRAPHIC_BODY' => $voting_bar_body_img,
						'POLL_GRAPHIC_LEFT' => $voting_bar_left_img,
						'POLL_GRAPHIC_RIGHT' => $voting_bar_right_img,
						'POLL_OPTION_COLOR' => $vote_color,
						'POLL_OPTION_IMG' => $vote_graphic_img,
						'POLL_OPTION_IMG_WIDTH' => $vote_graphic_length
						)
					);
				}

				$template->assign_vars(array(
					'L_TOTAL_VOTES' => $lang['Total_votes'],
					'TOTAL_VOTES' => $vote_results_sum
					)
				);

			}
			else
			{
				$template->set_filenames(array('pollbox' => 'viewtopic_poll_ballot.tpl'));

				for($i = 0; $i < $vote_options; $i++)
				{
					if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
					{
						$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
					}

					$template->assign_block_vars('poll_option', array(
						'POLL_OPTION_ID' => $vote_info[$i]['vote_option_id'],
						'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
					);
				}

				$template->assign_vars(array(
					'L_SUBMIT_VOTE' => $lang['Submit_vote'],
					'L_VIEW_RESULTS' => $lang['View_results'],
					'U_VIEW_RESULTS' => append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;postdays=' . $post_days . '&amp;postorder=' . $post_order . '&amp;vote=viewresult')
					)
				);

				$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
			}

			if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
			{
				$vote_title = preg_replace($orig_word, $replacement_word, $vote_title);
			}

			$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

			$template->assign_vars(array(
				'POLL_QUESTION' => $vote_title,
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_POLL_ACTION' => append_sid('posting.' . PHP_EXT . '?mode=vote&amp;' . $forum_id_append . '&amp;' . $topic_id_append))
			);

			$template->assign_var_from_handle('POLL_DISPLAY', 'pollbox');
		}
	}

	init_display_post_attachments($forum_topic_data['topic_attachment']);

	// Don't update the topic view counter if viewer is poster
	if (!($postrow[0]['user_id'] == $userdata['user_id']))
	{
		// Update the topic view counter
		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_views = topic_views + 1
			WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not update topic views.", '', __LINE__, __FILE__, $sql);
		}
	}

	// Begin Thanks Mod
	// Get topic thanks
	if ($show_thanks == FORUM_THANKABLE)
	{
		// Select Format for the date
		$timeformat = "d F";
		$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, t.thanks_time
				FROM " . THANKS_TABLE . " t, " . USERS_TABLE . " u
				WHERE topic_id = $topic_id
				AND t.user_id = u.user_id";
		if (!($result = $db->sql_query($sql)))
		{
		message_die(GENERAL_ERROR, "Could not obtain thanks information", '', __LINE__, __FILE__, $sql);
		}
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
			$thanks_date[$i] = create_date_simple($timeformat, $thanksrow[$i]['thanks_time'], $board_config['board_timezone']);
			// Make thanker profile link
			$thanks .= '<span class="gensmall">' . (($thanks != '') ? ', ' : '') . colorize_username($thanksrow[$i]['user_id'], $thanksrow[$i]['username'], $thanksrow[$i]['user_color'], $thanksrow[$i]['user_active']) . ' (' . $thanks_date[$i] . ')</span>';
		}

		$sql = "SELECT t.topic_poster, u.user_id, u.username, u.user_active, u.user_color
				FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
				WHERE t.topic_id = $topic_id
					AND t.topic_poster = u.user_id
				LIMIT 1";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not obtain user information", '', __LINE__, __FILE__, $sql);
		}
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

	if ($board_config['enable_quick_quote'] == 1)
	{
		$template->assign_block_vars('switch_quick_quote', array());
	}

	// Okay, let's do the loop, yeah come on baby let's do the loop and it goes like this ...
	$sig_cache = array();
	$delnote = isset($_GET['delnote']) ? explode('.', $_GET['delnote']) : array();
	$this_year = create_date('Y', time(), $board_config['board_timezone']);
	$this_date = create_date('md', time(), $board_config['board_timezone']);

	// Mighty Gorgon - Feedbacks - BEGIN
	if (defined('MG_FEEDBACKS'))
	{
		$mg_root_path = IP_ROOT_PATH . 'mg/';
		include_once($mg_root_path . 'includes/mg_functions_feedbacks.' . PHP_EXT);
		include_once($mg_root_path . 'mg_common.' . PHP_EXT);
		include_once($mg_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_mg.' . PHP_EXT);
		$feedbacks_allowed_forums = explode(',', MG_FEEDBACKS_FORUMS);
		$feedback_disabled = false;
		if (!in_array($forum_id, $feedbacks_allowed_forums))
		{
			$feedback_disabled = true;
		}
	}
	// Mighty Gorgon - Feedbacks - END

	for($i = 0; $i < $total_posts; $i++)
	{
		$poster_id = $postrow[$i]['user_id'];
		$post_id = $postrow[$i]['post_id'];
		$user_pic_count = $postrow[$i]['user_personal_pics_count'];
		$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : colorize_username($postrow[$i]['user_id'], $postrow[$i]['username'], $postrow[$i]['user_color'], $postrow[$i]['user_active']);
		$poster_qq = ($poster_id == ANONYMOUS) ? $lang['Guest'] : $postrow[$i]['username'];
		// Start add - Birthday MOD
		$poster_age = '';
		if ($board_config['birthday_viewtopic'] == 1)
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
			if ($this_date == $poster_birthdate)
			{
				$gebbild = '<img src="images/birthdaycake.gif" alt="Happy Birthday" title="Happy Birthday" />';
			}
			else
			{
				$gebbild = ' ';
			}
		}
		// End add - Birthday MOD

		$post_date = create_date2($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

		$poster_posts = ($postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $postrow[$i]['user_posts'] : '';

		$poster_from = ($postrow[$i]['user_from'] && $postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Location'] . ': ' . $postrow[$i]['user_from'] : '';

		$poster_from_flag = ($postrow[$i]['user_from_flag'] && $postrow[$i]['user_id'] != ANONYMOUS) ? '<img src="images/flags/' . $postrow[$i]['user_from_flag'] . '" alt="' . $postrow[$i]['user_from_flag'] . '" title="' . $postrow[$i]['user_from'] . '" />' : '';

		$poster_joined = ($postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $postrow[$i]['user_regdate'], $board_config['board_timezone']) : '';

		$poster_avatar = user_get_avatar($poster_id, $postrow[$i]['user_level'], $postrow[$i]['user_avatar'], $postrow[$i]['user_avatar_type'], $postrow[$i]['user_allowavatar']);

		// Define the little post icon
//<!-- BEGIN Unread Post Information to Database Mod -->
		if(!$userdata['upi2db_access'])
		{
//<!-- END Unread Post Information to Database Mod -->
			if ($userdata['session_logged_in'] && ($postrow[$i]['post_time'] > $userdata['user_lastvisit']) && ($postrow[$i]['post_time'] > $topic_last_read))
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

		if (($board_config['url_rw'] == '1') || (($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS)))
		{
			$mini_post_url = str_replace ('--', '-', make_url_friendly($postrow[$i]['post_subject']) . '-vp' . $postrow[$i]['post_id'] . '.html#p' . $postrow[$i]['post_id']);
		}
		else
		{
			// Mighty Gorgon: this is the full URL in case we would like to use it instead of the short form permalink... maybe for SEO purpose it is better using the short form
			//$mini_post_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#p' . $postrow[$i]['post_id'];
			$mini_post_url = append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#p' . $postrow[$i]['post_id'];
		}

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_ranks = generate_ranks($postrow[$i], $ranks_sql);

		$user_rank_01 = ($user_ranks['rank_01'] == '') ? '' : ($user_ranks['rank_01'] . '<br />');
		$user_rank_01_img = ($user_ranks['rank_01_img'] == '') ? '' : ($user_ranks['rank_01_img'] . '<br />');
		$user_rank_02 = ($user_ranks['rank_02'] == '') ? '' : ($user_ranks['rank_02'] . '<br />');
		$user_rank_02_img = ($user_ranks['rank_02_img'] == '') ? '' : ($user_ranks['rank_02_img'] . '<br />');
		$user_rank_03 = ($user_ranks['rank_03'] == '') ? '' : ($user_ranks['rank_03'] . '<br />');
		$user_rank_03_img = ($user_ranks['rank_03_img'] == '') ? '' : ($user_ranks['rank_03_img'] . '<br />');
		$user_rank_04 = ($user_ranks['rank_04'] == '') ? '' : ($user_ranks['rank_04'] . '<br />');
		$user_rank_04_img = ($user_ranks['rank_04_img'] == '') ? '' : ($user_ranks['rank_04_img'] . '<br />');
		$user_rank_05 = ($user_ranks['rank_05'] == '') ? '' : ($user_ranks['rank_05'] . '<br />');
		$user_rank_05_img = ($user_ranks['rank_05_img'] == '') ? '' : ($user_ranks['rank_05_img'] . '<br />');
		if (($user_rank_01 == '') && ($user_rank_01_img  == '') && ($user_rank_02 == '') && ($user_rank_02_img == '') && ($user_rank_03 == '') && ($user_rank_03_img == '') && ($user_rank_04 == '') && ($user_rank_04_img == '') && ($user_rank_05 == '') && ($user_rank_05_img == ''))
		{
			$user_rank_01 = '&nbsp;';
		}
		// Mighty Gorgon - Multiple Ranks - END

		$poster_thanks_received = '';
		if (($poster_id != ANONYMOUS) && ($userdata['user_id'] != ANONYMOUS) && $board_config['show_thanks_viewtopic'] && !$board_config['disable_thanks_topics'] && !$lofi)
		{
			$total_thanks_received = user_get_thanks_received($poster_id);
			$poster_thanks_received = ($total_thanks_received > 0) ? ($lang['THANKS_RECEIVED'] . ': ' . '<a href="' . append_sid(SEARCH_MG . '?search_thanks=' . $poster_id) . '">' . $total_thanks_received . '</a>' . '<br />') : '';
		}

		// Handle anon users posting with usernames
		if (($poster_id == ANONYMOUS) && ($postrow[$i]['post_username'] != ''))
		{
			$poster = $postrow[$i]['post_username'];
			$poster_qq = $postrow[$i]['post_username'];
			$user_rank_01 = $lang['Guest'] . '<br />';
		}

		if ($poster_id != ANONYMOUS)
		{
			$profile_url = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $poster_id);
			$profile_img = '<a href="' . $profile_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
			$profile = '<a href="' . $profile_url . '">' . $lang['Profile'] . '</a>';

			$pm_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $poster_id);
			$pm_img = '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
			$pm = '<a href="' . $pm_url . '">' . $lang['PM'] . '</a>';

			$email_url = '';
			if (empty($userdata['user_id']) || ($userdata['user_id'] == ANONYMOUS))
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
				$email_url = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $poster_id) : 'mailto:' . $postrow[$i]['user_email'];
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

			$aim_img = (!empty($postrow[$i]['user_aim'])) ? build_im_link('aim', $postrow[$i]['user_aim'], $lang['AIM'], $images['icon_aim2']) : '';
			$aim = (!empty($postrow[$i]['user_aim'])) ? build_im_link('aim', $postrow[$i]['user_aim'], $lang['AIM'], false) : '';
			$aim_url = (!empty($postrow[$i]['user_aim'])) ? build_im_link('aim', $postrow[$i]['user_aim'], $lang['AIM'], false, true) : '';

			$icq_status_img = (!empty($postrow[$i]['user_icq'])) ? '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $postrow[$i]['user_icq'] . '&img=5" width="18" height="18" /></a>' : '';
			$icq_img = (!empty($postrow[$i]['user_icq'])) ? build_im_link('icq', $postrow[$i]['user_icq'], $lang['ICQ'], $images['icon_icq2']) : '';
			$icq = (!empty($postrow[$i]['user_icq'])) ? build_im_link('icq', $postrow[$i]['user_icq'], $lang['ICQ'], false) : '';
			$icq_url = (!empty($postrow[$i]['user_icq'])) ? build_im_link('icq', $postrow[$i]['user_icq'], $lang['ICQ'], false, true) : '';

			$msn_img = (!empty($postrow[$i]['user_msnm'])) ? build_im_link('msn', $postrow[$i]['user_msnm'], $lang['MSNM'], $images['icon_msnm2']) : '';
			$msn = (!empty($postrow[$i]['user_msnm'])) ? build_im_link('msn', $postrow[$i]['user_msnm'], $lang['MSNM'], false) : '';
			$msn_url = (!empty($postrow[$i]['user_msnm'])) ? build_im_link('msn', $postrow[$i]['user_msnm'], $lang['MSNM'], false, true) : '';

			$skype_img = (!empty($postrow[$i]['user_skype'])) ? build_im_link('skype', $postrow[$i]['user_skype'], $lang['SKYPE'], $images['icon_skype2']) : '';
			$skype = (!empty($postrow[$i]['user_skype'])) ? build_im_link('skype', $postrow[$i]['user_skype'], $lang['SKYPE'], false) : '';
			$skype_url = (!empty($postrow[$i]['user_skype'])) ? build_im_link('skype', $postrow[$i]['user_skype'], $lang['SKYPE'], false, true) : '';

			$yim_img = (!empty($postrow[$i]['user_yim'])) ? build_im_link('yahoo', $postrow[$i]['user_yim'], $lang['YIM'], $images['icon_yim2']) : '';
			$yim = (!empty($postrow[$i]['user_yim'])) ? build_im_link('yahoo', $postrow[$i]['user_yim'], $lang['YIM'], false) : '';
			$yim_url = (!empty($postrow[$i]['user_yim'])) ? build_im_link('yahoo', $postrow[$i]['user_yim'], $lang['YIM'], false, true) : '';

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
			$online_status_url = append_sid('viewonline.' . PHP_EXT);
			$online_status_lang = $lang['Offline'];
			$online_status_class = 'offline';
			if (($userdata['user_level'] == ADMIN) || ($userdata['user_id'] == $poster_id) || $postrow[$i]['user_allow_viewonline'])
			{
				if ($postrow[$i]['user_session_time'] >= (time() - $board_config['online_time']))
				{
					$online_status_img = '<a href="' . $online_status_url . '"><img src="' . $images['icon_online2'] . '" alt="' . $lang['Online'] .'" title="' . $lang['Online'] .'" /></a>';
					$online_status_lang = $lang['Online'];
					$online_status_class = 'online';
				}
				else
				{
					$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] .'" title="' . $lang['Offline'] .'" />';
					$online_status_lang = $lang['Offline'];
					$online_status_class = 'offline';
				}
			}
			else
			{
				$online_status_img = '<a href="' . $online_status_url . '"><img src="' . $images['icon_hidden2'] . '" alt="' . $lang['Hidden'] .'" title="' . $lang['Hidden'] .'" /></a>';
				$online_status_lang = $lang['Hidden'];
				$online_status_class = 'hidden';
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
			$yim_url = '';
			$yim_img = '';
			$yim = '';
			$album_url = '';
			$album_img = '';
			$album = '';
			$online_status_url = '';
			$online_status_img = '';
		}
		$quote_url = append_sid('posting.' . PHP_EXT . '?mode=quote&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id']);
		$quote_img = '<a href="' . $quote_url . '"><img src="' . $images['icon_quote'] . '" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '" /></a>';
		$quote = '<a href="' . $quote_url . '">' . $lang['Reply_with_quote'] . '</a>';

		$search_url = append_sid(SEARCH_MG . '?search_author=' . urlencode($postrow[$i]['username']) . '&amp;showresults=posts');
		$search_img = '<a href="' . $search_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" title="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" /></a>';
		$search = '<a href="' . $search_url . '">' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '</a>';

		$edit_url = '';
		if ((($userdata['user_id'] == $poster_id) && $is_auth['auth_edit']) || $is_auth['auth_mod'])
		{
			if (($board_config['allow_mods_edit_admin_posts'] == false) && ($postrow[$i]['user_level'] == ADMIN) && ($userdata['user_level'] != ADMIN))
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
		else
		{
			$edit_img = '';
			$edit = '';
		}

		$delpost_url = '';
		$ip_url = '';
		if (($userdata['user_level'] == ADMIN) || $is_auth['auth_mod'])
		{
			$ip_url = 'modcp.' . PHP_EXT . '?mode=ip&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id'] . '&amp;sid=' . $userdata['session_id'];
			// Start Advanced IP Tools Pack MOD
			$ip_img = '<a href="' . $ip_url . '"><img src="' . $images['icon_ip2'] . '" alt="' . $lang['View_IP'] . ' (' . decode_ip($postrow[$i]['poster_ip']) . ')" title="' . $lang['View_IP'] . ' (' . decode_ip($postrow[$i]['poster_ip']) . ')" /></a>';
			// End Advanced IP Tools Pack MOD
			$ip = '<a href="' . $ip_url . '">' . $lang['View_IP'] . '</a>';

			$delpost_url = 'posting.' . PHP_EXT . '?mode=delete&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id'] . '&amp;sid=' . $userdata['session_id'];
			$delpost_img = '<a href="' . $delpost_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
			$delpost = '<a href="' . $delpost_url . '">' . $lang['Delete_post'] . '</a>';
		}
		else
		{
			$ip_img = '';
			$ip = '';

			if ($userdata['user_id'] == $poster_id && $is_auth['auth_delete'] && $forum_topic_data['topic_last_post_id'] == $postrow[$i]['post_id'])
			{
				$delpost_url = 'posting.' . PHP_EXT . '?mode=delete&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $postrow[$i]['post_id'] . '&amp;sid=' . $userdata['session_id'];
				$delpost_img = '<a href="' . $delpost_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
				$delpost = '<a href="' . $delpost_url . '">' . $lang['Delete_post'] . '</a>';
			}
			else
			{
				$delpost_img = '';
				$delpost = '';
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
				$grn_card_action = 'return confirm(\''.sprintf($lang['Green_card_warning'],$current_user).'\')';
				$temp_url = 'card.' . PHP_EXT . '?mode=unban&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $userdata['user_id'] . '&amp;sid=' . $userdata['session_id'];
				$g_card_img = '<a href="' . $temp_url . '" title="'. $lang['Give_G_card'] . '" onclick="' . $grn_card_action . '">' . $grn_card_img . '</a>';
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
				for($n = 0; $n < count($ranks_sql['bannedrow']); $n++)
				{
					if ($ranks_sql['bannedrow'][$n]['ban_userid'] == $poster_id)
					{
						$is_banned = true;
						break;
					}
				}
				if (($user_warnings > $board_config['max_user_bancard']) || ($is_banned == true))
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

			if (($user_warnings <= $board_config['max_user_bancard']) && $is_auth['auth_ban'])
			{
				$yel_card_img = '<img src="' . $images['icon_y_card'] . '" alt="' . sprintf($lang['Give_Y_card'], $user_warnings + 1) . '" />';
				$yel_card_action = 'return confirm(\'' . sprintf($lang['Yellow_card_warning'], $current_user) . '\')';
				$temp_url = 'card.' . PHP_EXT . '?mode=warn&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $userdata['user_id'] . '&amp;sid=' . $userdata['session_id'];
				$y_card_img = '<a href="' . $temp_url . '" title="' .sprintf($lang['Give_Y_card'], $user_warnings + 1). '" onclick="' . $yel_card_action . '">' . $yel_card_img . '</a>';

				$red_card_img = '<img src="'. $images['icon_r_card'] . '" alt="'. $lang['Give_R_card'] . '" />';
				$red_card_action = 'return confirm(\''.sprintf($lang['Red_card_warning'], $current_user).'\')';
				$temp_url = 'card.' . PHP_EXT . '?mode=ban&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $userdata['user_id'] . '&amp;sid=' . $userdata['session_id'];
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
				$blue_card_img = (($postrow[$i]['post_bluecard'])) ? '<img src="' . $images['icon_bhot_card'] . '" alt="' . sprintf($lang['Clear_b_card'], $postrow[$i]['post_bluecard']) . '" />' : '<img src="' . $images['icon_b_card'] . '" alt="' . $lang['Give_b_card'] . '" />';
				$blue_card_action = ($postrow[$i]['post_bluecard']) ? 'return confirm(\'' . $lang['Clear_blue_card_warning'] . '\')' : 'return confirm(\'' . $lang['Blue_card_warning'] . '\')';
				$temp_url = 'card.' . PHP_EXT . '?mode=' . (($postrow[$i]['post_bluecard']) ? 'report_reset' : 'report') . '&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $userdata['user_id'] . '&amp;sid=' . $userdata['session_id'];
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
				$temp_url = 'card.' . PHP_EXT . '?mode=report&amp;post_id=' . $postrow[$i]['post_id'] . '&amp;user_id=' . $userdata['user_id'] . '&amp;sid=' . $userdata['session_id'];
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
		if ($board_config['enable_quick_quote'] == true)
		{
			$look_up_array = array(
				"\"",
				"<",
				">",
				"\n",
				chr(13),
			);

			$replacement_array = array(
				"\\\"",
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
			if (!empty($orig_word))
			{
				$plain_message = (!empty($plain_message)) ? preg_replace($orig_word, $replacement_word, $plain_message) : '';
			}
			$plain_message = str_replace($look_up_array, $replacement_array, $plain_message);
		}
		// Mighty Gorgon - Quick Quote - END

		// Mighty Gorgon - New BBCode Functions - BEGIN
		// Please, do not change anything here, if you're not confident with what you're doing!!!
		//$message = $postrow[$i]['post_text'];
		$message_compiled = empty($postrow[$i]['post_text_compiled']) ? false : $postrow[$i]['post_text_compiled'];
		// CrackerTracker v5.x
		$is_miserable = false;
		if (($postrow[$i]['ct_miserable_user'] == 1) && ($postrow[$i]['user_id'] != $userdata['user_id']) && ($userdata['user_level'] == USER))
		{
			//$message = $lang['ctracker_message_dialog_title'] . '<br /><br />' . $lang['ctracker_ipb_deleted'];
			$message = $lang['ctracker_message_dialog_title'] . "\n\n" . $lang['ctracker_ipb_deleted'];
			$is_miserable = true;
		}
		else
		{
			$message = $postrow[$i]['post_text'];
			//if (($postrow[$i]['ct_miserable_user'] == 1) && ($userdata['user_level'] == ADMIN))
			if ($postrow[$i]['ct_miserable_user'] == 1)
			{
				//$message .= '<br /><br />' . $lang['ctracker_mu_success_bbc'];
				$message .= "\n\n" . $lang['ctracker_mu_success_bbc'];
				$is_miserable = true;
			}
		}
		// CrackerTracker v5.x
		if ($is_miserable == true)
		{
			$message_compiled = false;
		}

		// BEGIN CMX News Mod
		// Strip out the <!--break--> delimiter.
		$delim = htmlspecialchars('<!--break-->');
		$pos = strpos($message, $delim);
		if(($pos !== false) && ($pos < strlen($message)))
		{
			$message = substr_replace($message, html_entity_decode($delim), $pos, strlen($delim));
		}
		// END CMX News Mod

		$user_sig = ($postrow[$i]['enable_sig'] && (trim($postrow[$i]['user_sig']) != '') && $board_config['allow_sig']) ? $postrow[$i]['user_sig'] : '';

		// Replace Naughty Words - BEGIN
		if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
		{
			$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
			//$poster = (!empty($poster)) ? preg_replace($orig_word, $replacement_word, $poster) : '';
			//$poster_qq = (!empty($poster_qq)) ? preg_replace($orig_word, $replacement_word, $poster_qq) : '';
			//$user_sig = (!empty($user_sig)) ? preg_replace($orig_word, $replacement_word, $user_sig) : '';
			//$message = (!empty($message)) ? preg_replace($orig_word, $replacement_word, $message) : '';

			if ($user_sig != '')
			{
				$user_sig = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
			}

			$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
		}
		// Replace Naughty Words - END

		// BBCode Parsing

		if($user_sig && empty($sig_cache[$postrow[$i]['user_id']]))
		{
			$bbcode->allow_bbcode = $board_config['allow_bbcode'] && $userdata['user_allowbbcode'];
			//$bbcode->allow_smilies = true;
			//$bbcode->allow_html = true;
			$bbcode->allow_smilies = $board_config['allow_smilies'] && empty($lofi);
			$bbcode->allow_html = $board_config['allow_html'] && $userdata['user_allowhtml'];
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

		$bbcode->allow_html = $board_config['allow_html'] && $userdata['user_allowhtml'] && $postrow[$i]['enable_html'];
		$bbcode->allow_bbcode = $board_config['allow_bbcode'] && $userdata['user_allowbbcode'] && $postrow[$i]['enable_bbcode'];
		$bbcode->allow_smilies = $board_config['allow_smilies'] && empty($lofi) && $postrow[$i]['enable_smilies'];

		if(preg_match('/\[code/i', $message))
		{
			$bbcode->allow_html = false;
		}

		if(preg_match('/\[hide/i', $message) || preg_match('/\[xs/i', $message) || preg_match('/\[upgrade/i', $message))
		{
			$message_compiled = false;
		}

		if (!empty($lofi))
		{
			$message = $bbcode->parse($message);
		}
		elseif($board_config['posts_precompiled'] == '0')
		{
			if($message_compiled == false)
			{
				// $bbcode->allow_smilies = $board_config['allow_smilies'] && $postrow[$i]['user_allowsmile'] ? true : false;
				$GLOBALS['code_post_id'] = $postrow[$i]['post_id'];
				$message = $bbcode->parse($message);
				if ($bbcode->allow_bbcode == false)
				{
					$message = str_replace("\n", "<br />", preg_replace("/\r\n/", "\n", $message));
				}
				$GLOBALS['code_post_id'] = 0;
				// update database
				$sql = "UPDATE " . POSTS_TABLE . " SET post_text_compiled='" . addslashes($message) . "' WHERE post_id='" . $postrow[$i]['post_id'] . "'";
				$db->sql_query($sql);
			}
			else
			{
				$message = $message_compiled;
			}
		}
		else
		{
			$GLOBALS['code_post_id'] = $postrow[$i]['post_id'];
			$message = $bbcode->parse($message);
			if ($bbcode->allow_bbcode == false)
			{
				$message = str_replace("\n", "<br />", preg_replace("/\r\n/", "\n", $message));
			}
			$GLOBALS['code_post_id'] = 0;
		}
		// Mighty Gorgon - New BBCode Functions - END

		//Acronyms, AutoLinks, Wrap - BEGIN
		if ($postrow[$i]['enable_autolinks_acronyms'] == true)
		{
			$message = $bbcode->acronym_pass($message);
			if(count($orig_autolink))
			{
				$message = autolink_transform($message, $orig_autolink, $replacement_autolink);
			}
			//$message = kb_word_wrap_pass($message);
			if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
			{
				$message = preg_replace($orig_word, $replacement_word, $message);
			}
		}
		//Acronyms, AutoLinks, Wrap -END

		// Highlight active words (primarily for search)
		if ($highlight_match)
		{
			// This has been back-ported from 3.0 CVS
			$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<span class="highlight-w"><b>\1</b></span>', $message);
		}
		// Replace newlines (we use this rather than nl2br because till recently it wasn't XHTML compliant)
		if ($user_sig != '')
		{
			$user_sig = '<br />' . $board_config['sig_line'] . '<br />' . $user_sig;
		}

		// Mighty Gorgon - ???
		// $message = str_replace("\n", "\n<br />\n", $message);
		// Mighty Gorgon - ???

		// Editing information
		if ($board_config['edit_notes'] == 1)
		{
			$notes_list = strlen($postrow[$i]['edit_notes']) ? unserialize($postrow[$i]['edit_notes']) : array();
			if($is_auth['auth_mod'] && (count($delnote) == 2) && ($delnote[0] == $postrow[$i]['post_id']))
			{
				$new_list = array();
				$num = intval($delnote[1]);
				for($n = 0; $n < count($notes_list); $n++)
				{
					if($n !== $num)
					{
						$new_list[] = $notes_list[$n];
					}
				}
				$notes_list = $new_list;
				$postrow[$i]['edit_notes'] = count($notes_list) ? serialize($notes_list) : '';
				$sql = "UPDATE " . POSTS_TABLE . " SET edit_notes='" . addslashes($postrow[$i]['edit_notes']) . "' WHERE post_id='" . $postrow[$i]['post_id'] . "'";
				$db->sql_query($sql);
			}
		}
		else
		{
			$notes_list = '';
		}

		$show_edit_by = (($board_config['always_show_edit_by'] || !$notes_list) ? true : false);
		if ($postrow[$i]['post_edit_count'] && $show_edit_by)
		{
			$l_edit_time_total = ($postrow[$i]['post_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
			$l_edit_id = (intval($postrow[$i]['post_edit_id']) > 1) ? colorize_username($postrow[$i]['post_edit_id']) : $poster;
			$l_edited_by = sprintf($l_edit_time_total, $l_edit_id, create_date2($board_config['default_dateformat'], $postrow[$i]['post_edit_time'], $board_config['board_timezone']), $postrow[$i]['post_edit_count']);
		}
		else
		{
			$l_edited_by = '';
		}

		// Convert and clean special chars!
		$post_subject = htmlspecialchars_clean($post_subject);
		// SMILEYS IN TITLE - BEGIN
		if (($board_config['smilies_topic_title'] == true) && !$lofi)
		{
			$bbcode->allow_smilies = ($board_config['allow_smilies'] && $postrow[$i]['enable_smilies'] ? true : false);
			$post_subject = $bbcode->parse_only_smilies($post_subject);
		}
		// SMILEYS IN TITLE - END

		if (!empty($topic_calendar_time) && ($postrow[$i]['post_id'] == $topic_first_post_id))
		{
			$post_subject .= get_calendar_title($topic_calendar_time, $topic_calendar_duration);
		}

//<!-- BEGIN Unread Post Information to Database Mod -->
		if($userdata['upi2db_access'])
		{
			$post_edit_max = ($postrow[$i]['post_time'] >= $postrow[$i]['post_edit_time']) ? $postrow[$i]['post_time'] : $postrow[$i]['post_edit_time'];
			$post_time_max = (empty($board_config['upi2db_edit_as_new'])) ? $postrow[$i]['post_time'] : $post_edit_max;
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
		$post_edit_string = (!$userdata['user_id'] == ADMIN) ? '' : '<a href="javascript:post_time_edit(\'' . $post_edit_link . '\')" style="text-decoration:none;">' . $lang['Edit_post_time_xs'] . '</a>';
		//$post_edit_string = (!$userdata['user_level'] == MOD || !$userdata['user_id'] == ADMIN) ? '' : '<a href="javascript:post_time_edit(' . $topic_id . ', ' . $post_id . ')" style="text-decoration:none;">' . $lang['Edit_post_time_xs']. '</a>';
		$single_post = '<a href="#_Single_Post_View" onclick="javascript:open_postreview(\'show_post.' . PHP_EXT . '?' . POST_POST_URL . '=' . intval($post_id) . '\');" style="text-decoration:none;">#' . ($i + 1 + $start) . '</a>';

		// Mighty Gorgon - Feedbacks - BEGIN
		$feedbacks_received = '';
		$feedback_add = '';
		if (defined('MG_FEEDBACKS') && !$feedback_disabled)
		{
			$feedbacks_details = get_user_feedbacks_received($postrow[$i]['user_id']);
			if ($feedbacks_details['feedbacks_count'] > 0)
			{
				$feedbacks_average = (($feedbacks_details['feedbacks_count'] > 0) ? (round($feedbacks_details['feedbacks_sum'] / $feedbacks_details['feedbacks_count'], 1)) : 0);
				$feedbacks_average_img = IP_ROOT_PATH . 'images/feedbacks/' . build_feedback_rating_image($feedbacks_average);
				$feedbacks_received = (($feedbacks_details['feedbacks_count'] > 0) ? ($lang['FEEDBACKS_RECEIVED'] . ': [ <a href="' . append_sid('mg_feedbacks.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $postrow[$i]['user_id']) . '">' . $feedbacks_details['feedbacks_count'] . '</a> ]<br /><img src="' . $feedbacks_average_img . '" alt="' . $feedbacks_average . '" title="' . $feedbacks_average . '" /><br />') : '');
			}
			if (can_user_give_feedbacks_topic($userdata['user_id'], $topic_id) && can_user_give_feedbacks_global($userdata['user_id'], $topic_id) && ($userdata['user_id'] != $postrow[$i]['user_id']))
			{
				$feedback_add = '&nbsp;&nbsp;<a href="' . append_sid('mg_feedbacks.' . PHP_EXT . '?mode=input&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_USERS_URL . '=' . $postrow[$i]['user_id']) . '">' . $lang['FEEDBACK_ADD'] . '</a><br />';
			}
		}
		// Mighty Gorgon - Feedbacks - END

		// Again this will be handled by the templating code at some point
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('postrow', array(
			// Mighty Gorgon - Feedbacks - BEGIN
			'FEEDBACKS' => $feedbacks_received . $feedback_add,
			// Mighty Gorgon - Feedbacks - END
			'ROW_CLASS' => $row_class,
			'POSTER_NAME' => $poster,
			'POSTER_NAME_QQ' => $poster_qq,
			'POSTER_NAME_QR' => str_replace(array(' ', '?', '&'), array('%20', '%3F', '%26'), $poster_qq),
			//'POSTER_NAME_QR' => htmlspecialchars($poster_qq),
			'POSTER_AGE' => $poster_age,
			'GEB_BILD' => !empty($gebbild) ? $gebbild : '',
			'USER_RANK_01' => $user_rank_01,
			'USER_RANK_01_IMG' => $user_rank_01_img,
			'USER_RANK_02' => $user_rank_02,
			'USER_RANK_02_IMG' => $user_rank_02_img,
			'USER_RANK_03' => $user_rank_03,
			'USER_RANK_03_IMG' => $user_rank_03_img,
			'USER_RANK_04' => $user_rank_04,
			'USER_RANK_04_IMG' => $user_rank_04_img,
			'USER_RANK_05' => $user_rank_05,
			'USER_RANK_05_IMG' => $user_rank_05_img,
			'POSTER_GENDER' => $gender_image,
			'POSTER_JOINED' => $poster_joined,
			'POSTER_POSTS' => $poster_posts,
			'POSTER_THANKS_RECEIVED' => $poster_thanks_received,
			'POSTER_FROM' => $poster_from,
			'POSTER_FROM_FLAG' => $poster_from_flag,
			'POSTER_AVATAR' => $poster_avatar,
			'POST_DATE' => $post_date,
			'POST_EDIT_STRING' => $post_edit_string,
			//'POST_EDIT_LINK' => $post_edit_link,
			'POST_SUBJECT' => $post_subject,
			'MESSAGE' => $message,
			'PLAIN_MESSAGE' => $plain_message,
			'SIGNATURE' => $user_sig,
			'EDITED_MESSAGE' => $l_edited_by,

			'POSTER_STYLE' => $poster_style,
			'POSTER_LANG' => $poster_lang,

			// Activity - BEGIN
			'POSTER_TROPHY' => (defined('ACTIVITY_MOD') && (ACTIVITY_MOD == true)) ? Amod_Build_Topics($hof_data, $postrow[$i]['user_id'], $postrow[$i]['user_trophies'], $postrow[$i]['username'], $postrow[$i]['ina_char_name']) : '',
			// Activity - END

			'MINI_POST_IMG' => $mini_post_img,
			'PROFILE_IMG' => $profile_img,
			'PROFILE' => $profile,
			'SEARCH_IMG' => $search_img,
			'SEARCH' => $search,
			'PM_IMG' => $pm_img,
			'PM' => $pm,
			'EMAIL_IMG' => (!$userdata['session_logged_in']) ? '' : $email_img,
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
			'YIM_IMG' => $yim_img,
			'YIM' => $yim,
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
			'IP_IMG' => $ip_img,
			'IP' => $ip,

			'U_PROFILE' => $profile_url,
			'U_PM' => $pm_url,
			'U_EMAIL' => $email_url,
			'U_WWW' => $www_url,
			'U_AIM' => $aim_url,
			'U_ICQ' => $icq_url,
			'U_MSN' => $msn_url,
			'U_SKYPE' => $skype_url,
			'U_YIM' => $yim_url,
			'U_ALBUM' => $album_url,
			'L_POSTER_ONLINE_STATUS' => $online_status_lang,
			'POSTER_ONLINE_STATUS_CLASS' => $online_status_class,
			'U_POSTER_ONLINE_STATUS' => $online_status_url,
			'U_IP' => $ip_url,
			'U_QUOTE' => $quote_url,
			'U_EDIT' => $edit_url,
			'U_DELETE' => $delpost_url,

			'L_MINI_POST_ALT' => $mini_post_alt,
			'NOTES_COUNT' => count($notes_list),
			'NOTES_DATA' => $postrow[$i]['edit_notes'],
			'DOWNLOAD_POST' => append_sid(VIEWTOPIC_MG . '?download=' . $postrow[$i]['post_id'] . '&amp;' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append),
			'SINGLE_POST' => $single_post,
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

			'U_POST_ID' => $postrow[$i]['post_id']
			)
		);

		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$cm_viewtopic->post_vars($postrow[$i], $userdata, $forum_id);
		}
		// MG Cash MOD For IP - END

		// Custom Profile Fields MOD - BEGIN
		if (($poster_id != ANONYMOUS) && ($profile_data_sql != ''))
		{
			$language = $board_config['default_lang'];
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

		if ($board_config['switch_poster_info_topic'] == 1)
		{
			$template->assign_block_vars('postrow.switch_poster_info', array());
		}
		if ($userdata['user_showavatars'])
		{
			$template->assign_block_vars('postrow.switch_showavatars', array());
		}
		if ($userdata['user_showsignatures'])
		{
			$template->assign_block_vars('postrow.switch_showsignatures', array());
		}

		display_post_attachments($postrow[$i]['post_id'], $postrow[$i]['post_attachment']);

		if(($show_thanks == FORUM_THANKABLE) && ($i == 0) && ($current_page == 1) && ($thanks <> ''))
		{
			$template->assign_block_vars('postrow.thanks', array(
				'THANKS' => $thanks,
				'THANKFUL' => $lang['thankful'],
				'THANKS2' => $lang ['thanks2'],
				'THANKS3' => $thanks2
				)
			);
		}

		//if ((!$forum_topic_data['forum_status'] == FORUM_LOCKED) && (!$forum_topic_data['topic_status'] == TOPIC_LOCKED) && ($is_auth['auth_reply']) && ($userdata['session_logged_in']))
		if ((!$forum_topic_data['forum_status'] == FORUM_LOCKED) && (!$forum_topic_data['topic_status'] == TOPIC_LOCKED) && ($is_auth['auth_reply']) && ($board_config['enable_quick_quote'] == 1))
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

		if ($board_config['edit_notes'])
		{
			$template->assign_vars(array(
				'S_EDIT_NOTES' => true,
				)
			);

			for($n = 0; $n < count($notes_list); $n++)
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
				for($j = 0; $j < count($list); $j++)
				{
					$item['notes.'][] = array(
						'L_EDITED_BY' => $lang['Edited_by'],
						'POSTER_NAME' => colorize_username($list[$j]['poster']),
						'POSTER_PROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $list[$j]['poster']),
						'TEXT' => htmlspecialchars($list[$j]['text']),
						'TIME' => create_date2($board_config['default_dateformat'], $list[$j]['time'], $board_config['board_timezone']),
						'L_DELETE_NOTE' => $lang['Delete_note'],
						'U_DELETE' => $is_auth['auth_mod'] ? ($template->vars['U_VIEW_TOPIC'] . '&amp;delnote=' . $item['U_POST_ID'] . '.' . $j) : '',
					);
				}
				unset($item);
			}
		}

	}

	$rating_auth_data = rate_auth($userdata['user_id'], $forum_id, $topic_id);
	$rating_box = ((($rating_auth_data == RATE_AUTH_NONE) || ($rating_auth_data == RATE_AUTH_DENY)) ? false : true);
	$sb_box = $board_config['show_social_bookmarks'] ? true : false;
	$ltt_box = $board_config['link_this_topic'] ? true : false;
	$topic_useful_box = ($rating_box || $sb_box || $ltt_box) ? true : false;

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

//<!-- BEGIN Unread Post Information to Database Mod -->
	if($userdata['upi2db_access'])
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

	generate_smilies_row();

	$template->pparse('body');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}

?>