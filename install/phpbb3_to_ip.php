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

@set_time_limit(0);
//@ignore_user_abort(true);
@ini_set('memory_limit', '64M');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$meta_content['page_title'] = 'phpBB 3 Importing Process';

$mode_array = array('main', 'forums', 'users', 'posts');
$mode = request_var('mode', '');
$mode = !in_array($mode, $mode_array) ? $mode_array[0] : $mode;

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

define('SCRIPT_NAME', 'phpbb3_to_ip.' . PHP_EXT);
define('SOURCE_FORUMS', 'phpbb_forums');
define('SOURCE_TOPICS', 'phpbb_topics');
define('SOURCE_POSTS', 'phpbb_posts');
define('SOURCE_USERS', 'phpbb_users');

define('SECONDS_PER_STEP', '3');
define('USERS_PER_STEP', '250');
define('POSTS_PER_STEP', '1000');


if ($mode == 'main')
{
	$redirect_url = append_sid(SCRIPT_NAME . '?mode=forums');
	$message_info = '<br /><br /><span style="color:#228822;"><b>Welcome to phpBB 3 to Icy Phoenix import process</b></span><br /><br />';
	$message_info .= '<br /><br /><span style="color:#224488;">This procedure has been design to import phpBB 3 data into an existing Icy Phoenix installation. Even if the whole process has been tested on a standard phpBB 3 installation, you should be aware that the process cannot be undone, so please make sure you have a backup of your DB before going on.</span><br /><br />';
	$message_info .= '<br /><br /><span style="color:#224488;">Before going on, please make sure you edited some of the constants in this file to refer to phpBB 3 tables.</span><br /><br />';
	$message_info .= '<br /><br /><span style="color:#dd2222;"><b>All data will be erased before trying to restore... if you are aware of that, please click this link to proceed:</b></span> <a href="' . $redirect_url . '">click here to begin</a><br /><br />';
}

if ($mode == 'forums')
{
	$sql = "TRUNCATE " . FORUMS_TABLE;
	$result = $db->sql_query($sql);

	$sql = "TRUNCATE " . TOPICS_TABLE;
	$result = $db->sql_query($sql);

	$sql = "TRUNCATE " . POSTS_TABLE;
	$result = $db->sql_query($sql);

	$sql_i = "INSERT INTO " . FORUMS_TABLE . "
	SELECT f.forum_id, f.forum_type, f.parent_id, f2.forum_type, f.left_id, f.right_id, f.forum_parents,
	f.forum_name, f.forum_id, f.forum_desc, f.forum_status, 0, f.forum_posts, f.forum_topics, 0,
	f.forum_last_post_id, f.forum_last_poster_id, f.forum_last_post_subject, f.forum_last_post_time, f.forum_last_poster_name, '',
	1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
	0, f.forum_rules, 0, '', 0, 0, 0,
	NULL, 0, 0, 0, '', NULL, 0,
	0, 0, 0, 0, 0, 1,
	3, 3, 3, 3, 3,
	3, 3, 3, 1, 3, 3, 1, 1
	FROM " . SOURCE_FORUMS . " f, " . SOURCE_FORUMS . " f2
	WHERE f2.forum_id = f.parent_id";
	$result_i = $db->sql_query($sql_i);

	$sql_i = "UPDATE " . FORUMS_TABLE . " SET f.main_type = 'f' WHERE f.main_type = 1";
	$result_i = $db->sql_query($sql_i);

	$sql_i = "UPDATE " . FORUMS_TABLE . " SET f.main_type = 'c' WHERE f.main_type = 0";
	$result_i = $db->sql_query($sql_i);

	$sql_i = "SELECT * FROM " . FORUMS_TABLE . " ORDER BY left_id";
	$result_i = $db->sql_query($sql_i);
	$all_forums = $db->sql_fetchrowset($result_i);
	$db->sql_freeresult($result_i);
	$forum_order = 0;
	foreach ($all_forums as $forum)
	{
		$forum_order += 10;
		$sql_i = "UPDATE " . FORUMS_TABLE . " SET f.forum_order = " . $forum_order . " WHERE f.forum_id = " . $forum['forum_id'];
		$result_i = $db->sql_query($sql_i);
	}

	$sql_i = "INSERT INTO " . TOPICS_TABLE . " (topic_id, forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_type, topic_first_post_id, topic_last_post_id)
	SELECT t.topic_id, t.forum_id, t.topic_title, t.topic_poster, t.topic_time, t.topic_views, t.topic_replies, t.topic_status, t.topic_type, t.topic_first_post_id, t.topic_last_post_id
	FROM " . SOURCE_TOPICS . " t";
	$result_i = $db->sql_query($sql_i);

	$redirect_url = append_sid(SCRIPT_NAME . '?mode=users&amp;start=0');
	meta_refresh(SECONDS_PER_STEP, $redirect_url);
	$message_info = '<br /><br /><span style="color:#228822;"><b>Forums and topics imported!</b></span><br /><br />';
	$message_info .= '<br /><br /><span style="color:#224488;"><b>Proceeding to next step... importing users!</b></span><br /><br />';
	$message_info .= '<br /><br /><span style="color:#dd2222;"><b>The script will proceed automatically, do not click anything!</b></span><br /><br />';
}

if ($mode == 'users')
{
	if (empty($start))
	{
		$sql = "TRUNCATE " . GROUPS_TABLE;
		$result = $db->sql_query($sql);

		$sql = "TRUNCATE " . USER_GROUP_TABLE;
		$result = $db->sql_query($sql);

		$sql = "TRUNCATE " . USERS_TABLE;
		$result = $db->sql_query($sql);

		// Add Anonymous
		$user_data = array(
			'user_id' => -1,
			'username' => 'Anonymous',
			'username_clean' => 'anonymous',
			'user_password' => '',
			'user_regdate' => time(),
			'user_email' => '',
			'user_timezone' => $config['board_timezone'],
			'user_dateformat' => $config['default_dateformat'],
			'user_lang' => $config['default_lang'],
			'user_style' => $config['default_style'],
			'user_level' => 0,
			'user_rank' => 0,
			'user_active' => 0,
			'user_actkey' => 'user_actkey',
		);
		$user_added = add_user($user_data, true, false);

		// Import Admins...
		$sql = "SELECT * FROM " . SOURCE_USERS . "
				WHERE group_id = 5
				ORDER BY user_id ASC
				LIMIT " . $start . ", " . USERS_PER_STEP;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			die('<br /><br /><b>Users table not found!</b><br /><br />');
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$user_data = array(
				'user_id' => $row['user_id'],
				'username' => $row['username'],
				'username_clean' => utf8_clean_string($row['username']),
				'user_password' => $row['user_password'],
				'user_regdate' => $row['user_regdate'],
				'user_email' => $row['user_email'],
				'user_email_hash' => $row['user_email_hash'],
				'user_timezone' => $row['user_timezone'],
				'user_dateformat' => $row['user_dateformat'],
				'user_lang' => $config['default_lang'],
				'user_style' => $config['default_style'],
				'user_level' => 1,
				'user_rank' => 0,
				'user_active' => 1,
				'user_actkey' => 'user_actkey',
			);
			$user_added = add_user($user_data, true, true);
		}
	}

	$sql = "SELECT * FROM " . SOURCE_USERS . "
			WHERE group_id NOT IN (1, 5, 6)
			ORDER BY user_id ASC
			LIMIT " . $start . ", " . USERS_PER_STEP;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		die('<br /><br /><b>Users table not found!</b><br /><br />');
	}

	$output_msg = '<div class="post-text">' . "\n" . '<ul type="circle">' . "\n";
	$users_counter = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$users_counter++;
		$user_data = array(
			'user_id' => $row['user_id'],
			'username' => $row['username'],
			'username_clean' => utf8_clean_string($row['username']),
			'user_password' => $row['user_password'],
			'user_regdate' => $row['user_regdate'],
			'user_email' => $row['user_email'],
			'user_email_hash' => $row['user_email_hash'],
			'user_timezone' => $row['user_timezone'],
			'user_dateformat' => $row['user_dateformat'],
			'user_lang' => $config['default_lang'],
			'user_style' => $config['default_style'],
			'user_level' => 1,
			'user_rank' => 0,
			'user_active' => 1,
			'user_actkey' => 'user_actkey',
		);
		$user_added = add_user($user_data, true, false);
		$output_msg .= '<li><span style="color:#228822;"><b>' . $user_data['username'] . '</b></span></li>' . "\n";
	}
	$output_msg .= '</ul>' . "\n" . '</div>' . "\n";
	$db->sql_freeresult($result);

	if (($users_counter <= USERS_PER_STEP) && ($users_counter != 0))
	{
		$redirect_url = append_sid(SCRIPT_NAME . '?mode=users&amp:start=' . ($start + USERS_PER_STEP));
		meta_refresh(SECONDS_PER_STEP, $redirect_url);
		$message_info = '<br /><br /><span style="color:#228822;"><b>Importing users...</b></span><br /><br />';
		$message_info .= '<br /><br /><span style="color:#224488;"><b>Proceeding to next step...</b></span><br /><br />';
		$message_info .= '<br /><br /><span style="color:#dd2222;"><b>The script will proceed automatically, do not click anything!</b></span><br /><br />';
	}
	else
	{
		$redirect_url = append_sid(SCRIPT_NAME . '?mode=posts&amp;start=0');
		meta_refresh(SECONDS_PER_STEP, $redirect_url);
		$message_info = '<br /><br /><span style="color:#228822;"><b>All users imported!</b></span><br /><br />';
		$message_info .= '<br /><br /><span style="color:#224488;"><b>Proceeding to next step... importing posts!</b></span><br /><br />';
		$message_info .= '<br /><br /><span style="color:#dd2222;"><b>The script will proceed automatically, do not click anything!</b></span><br /><br />';
	}
}

if ($mode == 'posts')
{
	// Step by step...
	$sql = "SELECT * FROM " . SOURCE_POSTS . "
			ORDER BY post_id ASC
			LIMIT " . $start . ", " . POSTS_PER_STEP;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		die('<br /><br /><b>Posts table not found!</b><br /><br />');
	}

	$output_msg = '<div class="post-text">' . "\n" . '<ul type="circle">' . "\n";
	$posts_counter = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$posts_counter++;
		$sql_i = "INSERT INTO " . POSTS_TABLE . " (`post_id`, `topic_id`, `forum_id`, `poster_id`, `post_time`, `poster_ip`, `post_username`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_autolinks_acronyms`, `enable_sig`, `post_edit_time`, `post_edit_count`, `post_attachment`, `post_bluecard`, `post_subject`, `post_text`, `post_text_compiled`, `edit_notes`) VALUES ('" . $row['post_id'] . "', '" . $row['topic_id'] . "', '" . $row['forum_id'] . "', '" . $row['poster_id'] . "', '" . $row['post_time'] . "', '" . $row['poster_ip'] . "', '" . $row['post_username'] . "', '" . $row['enable_bbcode'] . "', 0, '" . $row['enable_smilies'] . "', 1, '" . $row['enable_sig'] . "', '" . $row['post_time'] . "', 0, 0, NULL, '" . $row['post_subject'] . "', '" . bbcode_bb3_adjust($row['post_text'], $row['bbcode_uid']) . "', '', NULL)";
		$result_i = $db->sql_query($sql_i);

		$output_msg .= '<li><span style="color:#228822;"><b>Post ' . $row['post_id'] . '</b></span></li>' . "\n";
	}
	$output_msg .= '</ul>' . "\n" . '</div>' . "\n";
	$db->sql_freeresult($result);

	if (($posts_counter <= POSTS_PER_STEP) && ($posts_counter != 0))
	{
		$redirect_url = append_sid(SCRIPT_NAME . '?mode=posts&amp:start=' . ($start + POSTS_PER_STEP));
		meta_refresh(SECONDS_PER_STEP, $redirect_url);
		$message_info = '<br /><br /><span style="color:#228822;"><b>Importing users...</b></span><br /><br />';
		$message_info .= '<br /><br /><span style="color:#224488;"><b>Proceeding to next step...</b></span><br /><br />';
		$message_info .= '<br /><br /><span style="color:#dd2222;"><b>The script will proceed automatically, do not click anything!</b></span><br /><br />';
	}
	else
	{
		$message_info = '<br /><br /><span style="color:#228822;"><b>Import complete, enjoy your Icy Phoenix!</b></span><br /><br />';
	}

	// Full one step, but missing bbcodes...
	/*
	$sql_i = "INSERT INTO " . POSTS_TABLE . " (post_id, topic_id, forum_id, poster_id, post_time, poster_ip, post_username, post_subject, post_text)
	SELECT p.post_id, p.topic_id, p.forum_id, p.poster_id, p.post_time, '127.0.0.1', p.post_username, p.post_subject, p.post_text
	FROM " . SOURCE_POSTS . " p";
	$result_i = $db->sql_query($sql_i);
	*/
}


$template->assign_vars(array(
	'MESSAGE_TITLE' => $meta_content['page_title'],
	'MESSAGE_TEXT' => $message_info,
	)
);

/*
$message = $message_info . $message;
message_die(GENERAL_MESSAGE, $message);
*/

full_page_generation('message_body.tpl', 'phpBB 3 Porting', '', '');

function add_user($user_data, $batch_process = true, $is_admin = false)
{
	global $db, $cache, $config, $user, $lang;

	$sql = "INSERT INTO " . USERS_TABLE . " " . $db->sql_build_insert_update($user_data, true);
	$db->sql_return_on_error(true);
	$db->sql_transaction('begin');
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		if ($batch_process)
		{
			return false;
		}
		message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
	}

	$group_name = empty($is_admin) ? '' : 'Admin';
	$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator) VALUES ('" . $group_name . "', 'Personal User', 1, 0)";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		if ($batch_process)
		{
			return false;
		}
		message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
	}
	$group_id = $db->sql_nextid();

	$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending) VALUES ($user_id, $group_id, 0)";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_transaction('commit');
	$db->sql_return_on_error(false);
	if (!$result)
	{
		if ($batch_process)
		{
			return false;
		}
		message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
	}
	return true;
}

function bbcode_bb3_adjust($text, $bbcode_uid = '')
{
	$text = str_replace('http&#58;//', 'http://', $text);
	$text = str_replace('&#46;', '.', $text);
	$text = str_replace('quote=&quot;', 'quote="', $text);
	if (!empty($bbcode_uid))
	{
		$text = str_replace('&quot;:' . $bbcode_uid, '"', $text);
		$text = str_replace(':' . $bbcode_uid, '', $text);
	}
	$text = ereg_replace("<!-- s", "", ereg_replace(" --><img[^>]*><!-- s[^>]* -->", "", $text));
	$text = ereg_replace("<!-- m --><a class=\"postlink\" href=\"", "", ereg_replace("\">[^>]*><!-- m -->", "", $text));
	$text = str_replace('&quot;', '"', $text);
	$text = str_replace('size=75', 'size=9', $text);
	$text = str_replace('size=150', 'size=14', $text);
	$text = str_replace('size=200', 'size=18', $text);
	$text = str_replace('&amp;lt;', '&lt;', $text);
	$text = str_replace('&amp;gt;', '&gt;', $text);
	$text = str_replace('list:u', 'list', $text);
	$text = str_replace('list:o', 'list', $text);
	return $text;
}

?>