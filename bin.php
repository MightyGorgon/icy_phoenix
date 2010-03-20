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
* @Extra credits for this file
* Kooky (kooky@altern.org)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
$mcp_topic = new class_mcp_topic();

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

$confirm = true;

// Continue var definitions
$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

// session id check
$sid = request_var('sid', '');

// Obtain relevant data
if (!empty($topic_id))
{
	$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics
		FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_id = " . $topic_id . "
			AND f.forum_id = t.forum_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);

	$forum_topics = ($topic_row['forum_topics'] == 0) ? 1 : $topic_row['forum_topics'];
	$forum_id = $topic_row['forum_id'];
	$forum_name = $topic_row['forum_name'];
}
elseif (!empty($forum_id))
{
	$sql = "SELECT forum_name, forum_topics
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = " . $forum_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		message_die(GENERAL_MESSAGE, 'Forum_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);

	$forum_topics = ($topic_row['forum_topics'] == 0) ? 1 : $topic_row['forum_topics'];
	$forum_name = $topic_row['forum_name'];
}
else
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// session id check
if ($sid == '' || ($sid != $userdata['session_id']))
{
	message_die(GENERAL_ERROR, 'Invalid_session');
}

// Start auth check
$is_auth = auth(AUTH_ALL, $forum_id, $userdata);

if (!$is_auth['auth_mod'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Moderator'], $lang['Not_Authorized']);
}
// End Auth Check

if ($confirm)
{
	if (($config['bin_forum'] == 0) || (empty($_POST['topic_id_list']) && empty($topic_id)))
	{
		$redirect_url = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
		$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $lang['Bin_disabled'] . '<br /><br />' . $message);
	}
	else
	{
		$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);

		if($mcp_topic->topic_recycle($topics, $forum_id))
		{
			$message = $lang['Topics_Moved_bin'];
		}
		else
		{
			$message = $lang['No_Topics_Moved'];
		}

		$redirect_url = CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
		$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $message);
	}
}

?>