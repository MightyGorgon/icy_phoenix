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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_delete.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

function prune($forum_id, $prune_date, $prune_all = false)
{
	global $db, $lang;
	// Before pruning, lets try to clean up the invalid topic entries
	$sql = 'SELECT topic_id FROM ' . TOPICS_TABLE . '
		WHERE topic_last_post_id = 0';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain lists of topics to sync', '', __LINE__, __FILE__, $sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		sync('topic', $row['topic_id']);
	}

	$db->sql_freeresult($result);
	$prune_all = ($prune_all) ? '' : 'AND t.topic_vote = 0 AND t.topic_type <> ' . POST_ANNOUNCE;
	//
	// Those without polls and announcements ... unless told otherwise!
	//
	$sql = "SELECT t.topic_id
		FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
		WHERE t.forum_id = $forum_id
			$prune_all
			AND p.post_id = t.topic_last_post_id";
	if ($prune_date != '')
	{
		$sql .= " AND p.post_time < $prune_date";
	}

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain lists of topics to prune', '', __LINE__, __FILE__, $sql);
	}

	$sql_topics = '';
	while($row = $db->sql_fetchrow($result))
	{
		$sql_topics .= (($sql_topics != '') ? ', ' : '') . $row['topic_id'];
	}
	$db->sql_freeresult($result);

	if($sql_topics != '')
	{
		$sql = "SELECT post_id
			FROM " . POSTS_TABLE . "
			WHERE forum_id = $forum_id
				AND topic_id IN ($sql_topics)";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain list of posts to prune', '', __LINE__, __FILE__, $sql);
		}

		$sql_post = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$sql_post .= (($sql_post != '') ? ', ' : '') . $row['post_id'];
		}
		$db->sql_freeresult($result);

		if ($sql_post != '')
		{
			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE topic_id IN ($sql_topics)";
			if (!$db->sql_query($sql, BEGIN_TRANSACTION))
			{
				message_die(GENERAL_ERROR, 'Could not delete watched topics during prune', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . TOPICS_TABLE . "
				WHERE topic_id IN ($sql_topics)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete topics during prune', '', __LINE__, __FILE__, $sql);
			}

			$pruned_topics = $db->sql_affectedrows();
			$sql = "DELETE FROM " . BOOKMARK_TABLE . "
				WHERE topic_id IN ($sql_topics)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete bookmarks during prune', '', __LINE__, __FILE__, $sql);
			}


			$sql = "DELETE FROM " . POSTS_TABLE . "
				WHERE post_id IN ($sql_post)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete posts during prune', '', __LINE__, __FILE__, $sql);
			}

			$pruned_posts = $db->sql_affectedrows();

			remove_search_post($sql_post);
//<!-- BEGIN Unread Post Information to Database Mod -->
			prune_upi2db($sql_post);
//<!-- END Unread Post Information to Database Mod -->
			prune_attachments($sql_post);

			return array ('topics' => $pruned_topics, 'posts' => $pruned_posts);
		}
	}

	return array('topics' => 0, 'posts' => 0);
}

//
// Function auto_prune(), this function will read the configuration data from
// the auto_prune table and call the prune function with the necessary info.
//
function auto_prune($forum_id = 0)
{
	global $db, $lang;

	$sql = "SELECT *
		FROM " . PRUNE_TABLE . "
		WHERE forum_id = $forum_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read auto_prune table', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		if ($row['prune_freq'] && $row['prune_days'])
		{
			$prune_date = time() - ($row['prune_days'] * 86400);
			$next_prune = time() + ($row['prune_freq'] * 86400);

			prune($forum_id, $prune_date);
			sync('forum', $forum_id);

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET prune_next = $next_prune
				WHERE forum_id = $forum_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update forum table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	return;
}

?>