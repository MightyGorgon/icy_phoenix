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
include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

// Obtain initial var settings
if (isset($_GET[POST_FORUM_URL]) || isset($_POST[POST_FORUM_URL]))
{
	$forum_id = (isset($_POST[POST_FORUM_URL])) ? intval($_POST[POST_FORUM_URL]) : intval($_GET[POST_FORUM_URL]);
}
else
{
	$forum_id = '';
}

if (isset($_GET[POST_POST_URL]) || isset($_POST[POST_POST_URL]))
{
	$post_id = (isset($_POST[POST_POST_URL])) ? intval($_POST[POST_POST_URL]) : intval($_GET[POST_POST_URL]);
}
else
{
	$post_id = '';
}

if (isset($_GET[POST_TOPIC_URL]) || isset($_POST[POST_TOPIC_URL]))
{
	$topic_id = (isset($_POST[POST_TOPIC_URL])) ? intval($_POST[POST_TOPIC_URL]) : intval($_GET[POST_TOPIC_URL]);
}
else
{
	$topic_id = '';
}

$confirm = true;

// Continue var definitions
$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

// session id check
if (!empty($_POST['sid']) || !empty($_GET['sid']))
{
	$sid = (!empty($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}

//
// Obtain relevant data
//
if (!empty($topic_id))
{
	$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics
		FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_id = " . $topic_id . "
			AND f.forum_id = t.forum_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);

	$forum_topics = ($topic_row['forum_topics'] == 0) ? 1 : $topic_row['forum_topics'];
	$forum_id = $topic_row['forum_id'];
	$forum_name = $topic_row['forum_name'];
}
else if (!empty($forum_id))
{
	$sql = "SELECT forum_name, forum_topics
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = " . $forum_id;
	if (!($result = $db->sql_query($sql)))
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
if ($sid == '' || $sid != $userdata['session_id'])
{
	message_die(GENERAL_ERROR, 'Invalid_session');
}

// Start auth check
$is_auth = auth(AUTH_ALL, $forum_id, $userdata);

if (!$is_auth['auth_mod'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Moderator'], $lang['Not_Authorised']);
}
// End Auth Check

$page_title = $lang['Mod_CP'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

if ($confirm)
{
	if (($board_config['bin_forum'] == 0) || (empty($_POST['topic_id_list']) && empty($topic_id)))
	{
		$redirect_url = VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
		$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $lang['Bin_disabled'] . '<br /><br />' . $message);
	}
	else
	{
		// Define bin forum
		$new_forum_id = intval($board_config['bin_forum']);
		$old_forum_id = $forum_id;

		if ($new_forum_id != $old_forum_id)
		{
			$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);

			$topic_list = '';
			for($i = 0; $i < count($topics); $i++)
			{
				$topic_list .= (($topic_list != '') ? ', ' : '') . intval($topics[$i]);
			}

			$sql = "SELECT *
				FROM " . TOPICS_TABLE . "
				WHERE topic_id IN ($topic_list)
					AND forum_id = $old_forum_id
					AND topic_status <> " . TOPIC_MOVED;
			if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
			{
				message_die(GENERAL_ERROR, 'Could not select from topic table', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			for($i = 0; $i < count($row); $i++)
			{
				$topic_id = $row[$i]['topic_id'];

				if (isset($_POST['move_leave_shadow']))
				{
					// Insert topic in the old forum that indicates that the forum has moved.
					$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
						VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id)";
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not insert shadow topic', '', __LINE__, __FILE__, $sql);
					}
				}

				$sql = "UPDATE " . TOPICS_TABLE . "
					SET forum_id = '" . $new_forum_id . "'
					WHERE topic_id = '" . $topic_id . "'";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
				}

//<!-- BEGIN Unread Post Information to Database Mod -->
				$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
					SET forum_id = '" . $new_forum_id . "'
					WHERE topic_id = '" . $topic_id . "'";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
				}

				$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
					SET forum_id = '" . $new_forum_id . "'
					WHERE topic_id = '" . $topic_id . "'";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
				}
//<!-- BEGIN Unread Post Information to Database Mod -->

				$sql = "UPDATE " . POSTS_TABLE . "
					SET forum_id = '" . $new_forum_id . "'
					WHERE topic_id = '" . $topic_id . "'";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update post topic ids', '', __LINE__, __FILE__, $sql);
				}
			}

			// Sync the forum indexes
			$db->clear_cache('posts_');
			$db->clear_cache('forums_');
			sync('forum', $new_forum_id);
			sync('forum', $old_forum_id);

			$message = $lang['Topics_Moved_bin'];
		}
		else
		{
			$message = $lang['No_Topics_Moved'];
		}

		$redirect_url = VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
		$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $old_forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $message);
	}
}

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>