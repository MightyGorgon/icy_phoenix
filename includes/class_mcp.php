<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (C) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* MOD CP TOPIC class
*/
class class_mcp_topic
{
	/**
	* Delete topic(s)
	*/
	function topic_delete($topics, $forum_id, $method = POST_UNAPPROVE)
	{
		global $db, $cache, $lang;

		include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

		$sql = "SELECT topic_id FROM " . TOPICS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics) . "
			AND forum_id = " . $forum_id;
		$result = $db->sql_query($sql);

		$topics_ids = array();
		while($row = $db->sql_fetchrow($result))
		{
			$topics_ids[] = $row['topic_id'];
		}
		if (empty($topics_ids))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}
		$db->sql_freeresult($result);

		$sql = "SELECT poster_id, COUNT(post_id) AS posts FROM " . POSTS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
			GROUP BY poster_id";
		$result = $db->sql_query($sql);

		$count_sql = array();
		while($row = $db->sql_fetchrow($result))
		{
			$count_sql[] = "UPDATE " . USERS_TABLE . " SET user_posts = user_posts - " . $row['posts'] . " WHERE user_id = " . $row['poster_id'];
		}
		$db->sql_freeresult($result);

		if(sizeof($count_sql))
		{
			for($i = 0; $i < sizeof($count_sql); $i++)
			{
				$db->sql_query($count_sql[$i]);
			}
		}

		$sql = "SELECT post_id FROM " . POSTS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

		$post_ids = array();
		while($row = $db->sql_fetchrow($result))
		{
			$post_ids[] = intval($row['post_id']);
		}
		$db->sql_freeresult($result);

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET post_approval = " . $method . ", deleter_user_id = '" . $user->data['user_id'] . "', deleter_username = '" . $user->data['username'] . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
			OR " . $db->sql_in_set('topic_moved_id', $topics_ids);
		$db->sql_transaction('begin');
		$db->sql_query($sql);

		if ($method == POST_DELETED)
		{
			$sql = "DELETE FROM " . THANKS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . BOOKMARK_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . REGISTRATION_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . REGISTRATION_DESC_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			// TAGS - BEGIN
			@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
			$class_topics_tags = new class_topics_tags();
			$tags = $class_topics_tags->get_topics_tags($topics);

			if (sizeof($tags) > 0)
			{
				for ($i = 0; $i < sizeof($topics); $i++)
				{
					$class_topics_tags->remove_tag_from_match($tags, $topics[$i]);
				}
				$class_topics_tags->update_tag_entry($tags);
			}
			// TAGS - END

			// UPI2DB - BEGIN
			$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);
			// UPI2DB - END

			$sql = "DELETE FROM " . DRAFTS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);
		
			$sql = "DELETE FROM " . RATINGS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . TOPIC_VIEW_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . POSTS_LIKES_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			if($post_ids)
			{
				remove_search_post($post_ids);
			}

			$this->topic_poll_delete($topics);
		}

		$sql = "UPDATE " . POSTS_TABLE . "
			SET post_approval = " . $method . ", deleter_user_id = '" . $user->data['user_id'] . "', deleter_username = '" . $db->sql_escape($user->data['username']) . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);
		$db->sql_transaction('commit');

		$this->cache_resync(array($forum_id), 0);
	}

	/**
	* Move topic(s)
	*/
	function topic_move($topics_ids, $old_forum_id, $new_forum_id, $leave_shadow)
	{
		global $db, $cache, $lang;

		$old_forum_id = $this->fix_forum_id($old_forum_id);
		$new_forum_id = $this->fix_forum_id($new_forum_id);
		if (($old_forum_id <= 0) || ($new_forum_id <= 0))
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_FORUM');
		}

		if($new_forum_id != $old_forum_id)
		{
			$topic_list = '';
			for($i = 0; $i < sizeof($topics_ids); $i++)
			{
				$topic_list .= (($topic_list != '') ? ', ' : '') . intval($topics_ids[$i]);
			}

			$sql = "SELECT * FROM " . TOPICS_TABLE . "
				WHERE topic_id IN (" . $topic_list . ")
				AND forum_id = " . $old_forum_id . "
				AND topic_status <> " . TOPIC_MOVED;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$db->sql_transaction('begin');

			for($i = 0; $i < sizeof($row); $i++)
			{
				$topic_id = $row[$i]['topic_id'];
				if($leave_shadow)
				{
					$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
						VALUES ($old_forum_id, '" . addslashes($db->sql_escape($row[$i]['topic_title'])) . "', '" . $db->sql_escape($row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", " . $topic_id . ")";
					$db->sql_query($sql);
				}

				$sql = "UPDATE " . TOPICS_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . POSTS_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);

//<!-- BEGIN Unread Post Information to Database Mod -->
				$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);
//<!-- END Unread Post Information to Database Mod -->

				$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);

				// TAGS - BEGIN
				$sql = "UPDATE " . TOPICS_TAGS_MATCH_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);
				// TAGS - END
			}

			$db->sql_transaction('commit');

			$this->cache_resync(array($new_forum_id, $old_forum_id), 0);
			if (!function_exists('sync_topic_details'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
			}
			sync_topic_details(0, 0, true, false);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Lock/Unlock topic(s)
	*/
	function topic_lock_unlock($topics, $action, $forum_id)
	{
		global $db, $cache, $lang;

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_status = " . (($action == 'lock') ? TOPIC_LOCKED : TOPIC_UNLOCKED) . "
			WHERE " . $db->sql_in_set('topic_id', $topics) . "
			AND forum_id = " . $forum_id . "
			AND topic_moved_id = 0";
		$result = $db->sql_query($sql);

		empty_cache_folders(POSTS_CACHE_FOLDER);
	}

	/**
	* Change topic(s) status (Sticky, Announce, Global Announce or Normal)
	*/
	function topic_switch_status($topics, $status)
	{
		global $db, $cache, $lang, $is_auth;

		if(($status == 'sticky') && !$is_auth['auth_sticky'])
		{
			$message = sprintf($lang['Sorry_auth_sticky'], $is_auth['auth_sticky_type']);
			message_die(GENERAL_MESSAGE, $message);
		}
		if(($status == 'announce') && !$is_auth['auth_announce'])
		{
			$message = sprintf($lang['Sorry_auth_announce'], $is_auth['auth_announce_type']);
			message_die(GENERAL_MESSAGE, $message);
		}
		if(($status == 'super_announce') && !$is_auth['auth_globalannounce'])
		{
			$message = sprintf($lang['Sorry_auth_announce'], $is_auth['auth_announce_type']);
			message_die(GENERAL_MESSAGE, $message);
		}
		if(empty($topics))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		if($status == 'sticky')
		{
			$topic_type = POST_STICKY;
		}
		elseif($status == 'announce')
		{
			$topic_type = POST_ANNOUNCE;
		}
		elseif($status == 'super_announce')
		{
			$topic_type = POST_GLOBAL_ANNOUNCE;
		}
		elseif($status == 'normalize')
		{
			$topic_type = POST_NORMAL;
		}
		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_type = " . $topic_type . "
			WHERE " . $db->sql_in_set('topic_id', $topics) . "
			AND topic_moved_id = 0";
		$result = $db->sql_query($sql);

		empty_cache_folders(POSTS_CACHE_FOLDER);
	}

	/**
	* Merge topic(s)
	*/
	function topic_merge($topics, $new_topic_id, $forum_id)
	{
		global $db, $cache, $lang;

		$topics_ids = array();
		for($i = 0; $i < sizeof($topics); $i++)
		{
			if ($topics[$i] != $new_topic_id)
			{
				$topics_ids[] = $topics[$i];
			}
		}

		$db->sql_transaction('begin');

		$sql = "UPDATE " . POSTS_TABLE . "
			SET topic_id = '" . $new_topic_id . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

		$sql = "UPDATE " . UPI2DB_ALWAYS_READ_TABLE . "
			SET topic_id = '" . $new_topic_id . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
			SET topic_id = '" . $new_topic_id . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
			SET topic_id = '" . $new_topic_id . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . TOPICS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM  " . TOPICS_WATCH_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM " . THANKS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . BOOKMARK_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . RATINGS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . TOPIC_VIEW_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "UPDATE " . DRAFTS_TABLE . "
			SET topic_id = '" . $new_topic_id . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_LIKES_TABLE . "
			SET topic_id = '" . $new_topic_id . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . REGISTRATION_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . REGISTRATION_DESC_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		// TAGS - BEGIN
		@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
		$class_topics_tags = new class_topics_tags();
		$tags = $class_topics_tags->get_topics_tags($topics);

		if (sizeof($tags) > 0)
		{
			$class_topics_tags->remove_tag_from_match($tags, $topics[$i]);
			$class_topics_tags->update_tag_entry($tags);
		}
		// TAGS - END

		$this->topic_poll_delete($topics);

		$this->cache_resync(array($forum_id), array($new_topic_id));

		$db->sql_transaction('commit');
	}

	/**
	* Split topic(s)
	*/
	function topic_split($posts, $forum_id, $new_forum_id, $topic_id, $split_beyond, $subject)
	{
		global $db, $cache, $lang;

		if (empty($posts))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$new_forum_id = $this->fix_forum_id($new_forum_id);

		if ($new_forum_id <= 0)
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_FORUM');
		}

		if ($split_beyond)
		{
			$sql = "SELECT post_time
				FROM " . POSTS_TABLE . "
				WHERE " . $db->sql_in_set('post_id', $posts) . "
				AND topic_id = " . $topic_id . "
				ORDER BY post_time ASC
				LIMIT 1";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$sql_where = " AND post_time >= " . $row['post_time'] . " AND topic_id = " . $topic_id;
		}
		else
		{
			$sql_where = " AND " . $db->sql_in_set('post_id', $posts);
		}

		$sql = "SELECT post_id, poster_id, topic_id
			FROM " . POSTS_TABLE . "
			WHERE forum_id = " . $forum_id . $sql_where;
		$result = $db->sql_query($sql);

		$row = $db->sql_fetchrowset($result);
		$user_id_sql = '';
		$post_id_sql = '';
		for ($i = 0; $i < sizeof($row); $i++)
		{
			$user_id_sql .= (($user_id_sql != '') ? ', ' : '') . intval($row[$i]['poster_id']);
			$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row[$i]['post_id']);;
		}
		$db->sql_freeresult($result);

		$first_poster = $row[0]['poster_id'];
		$topic_id = $row[0]['topic_id'];
		$topic_time = time();

		$db->sql_transaction('begin');
		$sql  = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type)
			VALUES ('" . $db->sql_escape($subject) . "', " . $first_poster . ", " . $topic_time . ", " . $new_forum_id . ", " . TOPIC_UNLOCKED . ", " . POST_NORMAL . ")";
		$db->sql_query($sql);

		$new_topic_id = $db->sql_nextid();

		$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
			SET topic_id = " . $new_topic_id . "
			WHERE topic_id = " . $topic_id . "
			AND user_id IN (" . $user_id_sql . ")";
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_LIKES_TABLE . "
			SET topic_id = " . $new_topic_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_TABLE . "
			SET topic_id = " . $new_topic_id . ", forum_id = " . $new_forum_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);

		//<!-- BEGIN Unread Post Information to Database Mod -->
		$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
			SET topic_id = " . $new_topic_id . ", forum_id = " . $new_forum_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);

		$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
			SET topic_id = " . $new_topic_id . ", forum_id = " . $new_forum_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);
		//<!-- END Unread Post Information to Database Mod -->

		$this->cache_resync(array($new_forum_id, $forum_id), array($new_topic_id, $topic_id));
		if (!function_exists('sync_topic_details'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
		}
		sync_topic_details(0, 0, true, false);

		$db->sql_transaction('commit');

		return $new_topic_id;
	}

	/**
	* Recycle topic(s)
	*/
	function topic_recycle($topics_ids, $old_forum_id)
	{
		global $db, $cache, $config, $lang;

		$new_forum_id = intval($config['bin_forum']);
		if (!empty($new_forum_id) && ($new_forum_id != $old_forum_id))
		{
			$this->topic_move($topics_ids, $old_forum_id, $new_forum_id, false);

			$sql = "DELETE FROM " . BOOKMARK_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . DRAFTS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids);
			$db->sql_query($sql);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Edit topic(s) titles
	*/
	function topic_quick_title_edit($topics_ids, $qt_row)
	{
		global $db, $cache, $config, $user, $lang;

		$addon = str_replace('%mod%', addslashes($user->data['username']), $qt_row['title_info'] . ' ');
		$dateqt = ($qt_row['date_format'] == '') ? create_date($config['default_dateformat'], time(), $config['board_timezone']) : create_date($qt_row['date_format'], time(), $config['board_timezone']);
		$addon = str_replace('%date%', $dateqt, $addon);

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET title_compl_infos = '" . addslashes($addon) . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
				AND topic_moved_id = 0";
		$result = $db->sql_query($sql);

		empty_cache_folders(POSTS_CACHE_FOLDER);
	}

	/**
	* Topic(s) news category edit
	*/
	function topic_news_category_edit($topics_ids, $news_category)
	{
		global $db, $cache, $lang;

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET news_id = '" . $news_category . "'
			WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
				AND topic_moved_id = 0";
		$result = $db->sql_query($sql);

		empty_cache_folders(POSTS_CACHE_FOLDER);
	}

	/**
	* Delete poll within topic(s)
	*/
	function topic_poll_delete($topics)
	{
		global $db, $cache, $lang;

		$sql_ary = array(
			'poll_title' => '',
			'poll_start' => 0,
			'poll_length' => 0,
			'poll_max_options' => 1,
			'poll_last_vote' => 0,
			'poll_vote_change' => 0
		);

		$sql_update = $db->sql_build_insert_update($sql_ary, false);

		$sql = "UPDATE " . TOPICS_TABLE . " SET " . $sql_update . " WHERE " . $db->sql_in_set('topic_id', $topics);
		$db->sql_query($sql);

		/*
		$sql = "DELETE FROM " . POLL_OPTIONS_TABLE . " WHERE " . $db->sql_in_set('topic_id', $topics);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . POLL_VOTES_TABLE . " WHERE " . $db->sql_in_set('topic_id', $topics);
		$db->sql_query($sql);
		*/

		empty_cache_folders(POSTS_CACHE_FOLDER);
	}

	/**
	* Fix forum ID
	*/
	function fix_forum_id($id)
	{
		if ($id == 'Root')
		{
			$id = 0;
		}
		else
		{
			$type = substr($id, 0, 1);
			$id = ($type == POST_FORUM_URL) ? intval(substr($id, 1)) : (int) $id;
		}
		$id = ((!is_int($id) || ($id <= 0)) ? 0 : $id);
		return $id;
	}

	/**
	* Find forum name
	*/
	function find_names($id)
	{
		global $db, $cache, $lang;

		$id = $this->fix_forum_id($id);

		if ($id <= 0)
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_FORUM');
		}

		$sql = "SELECT forum_name FROM " . FORUMS_TABLE . " WHERE forum_id = " . $id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row['forum_name'];
	}

	/**
	* Resync cache after topics moderation
	*/
	function cache_resync($forums_ids, $topics_ids)
	{
		global $db, $cache, $lang;

		empty_cache_folders(POSTS_CACHE_FOLDER);
		empty_cache_folders(FORUMS_CACHE_FOLDER);

		if (!empty($forums_ids) && is_array($forums_ids))
		{
			$forums_processed = array();
			for ($i = 0; $i < sizeof($forums_ids); $i++)
			{
				if (!empty($forums_ids[$i]) && !in_array($forums_ids[$i], $forums_processed))
				{
					$forums_processed[] = $forums_ids[$i];
					if (!function_exists('sync'))
					{
						include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
					}
					sync('forum', $forums_ids[$i]);
				}
			}
		}

		if (!empty($topics_ids) && is_array($topics_ids))
		{
			$topics_processed = array();
			for ($i = 0; $i < sizeof($topics_ids); $i++)
			{
				if (!empty($topics_ids[$i]) && !in_array($topics_ids[$i], $topics_processed))
				{
					$topics_processed[] = $topics_ids[$i];
					if (!function_exists('sync'))
					{
						include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
					}
					sync('topic', $topics_ids[$i]);
				}
			}
		}

		return true;
	}
}

?>