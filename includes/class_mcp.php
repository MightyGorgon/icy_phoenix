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
* MOD CP class
*/
class class_mcp
{


	/**
	* Check if the selected forum has postcount enabled
	*/
	function forum_check_postcount($forum_id)
	{
		global $db, $cache, $lang;

		$forum_postcount = true;
		$sql = "SELECT forum_postcount FROM " . FORUMS_TABLE . " WHERE forum_id = " . $forum_id . " AND forum_postcount = 0";
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$forum_postcount = false;
		}
		$db->sql_freeresult($result);

		return $forum_postcount;
	}

	/**
	* Decrease user post count (and avoid values less than zero)
	*/
	function user_decrease_postscounter($user_id, $posts_number)
	{
		global $db, $cache, $lang;

		$sql = "SELECT user_posts FROM " . USERS_TABLE . " WHERE user_id = " . (int) $user_id;
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			if ($row['user_posts'] >= (int) $posts_number)
			{
				$posts_sql = "UPDATE " . USERS_TABLE . " SET user_posts = user_posts - " . $posts_number . " WHERE user_id = " . $user_id;
			}
			else
			{
				$posts_sql = "UPDATE " . USERS_TABLE . " SET user_posts = 0 WHERE user_id = " . $user_id;
			}
			$result = $db->sql_query($posts_sql);
		}
		$db->sql_freeresult($result);

		return true;
	}

	/**
	* Delete topic(s)
	*/
	function topic_delete($topics, $forum_id)
	{
		global $db, $cache, $lang;

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

		$forum_postcount = $this->forum_check_postcount($forum_id);
		if (!empty($forum_postcount))
		{
			$sql = "SELECT poster_id, COUNT(post_id) AS posts FROM " . POSTS_TABLE . "
				WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
				GROUP BY poster_id";
			$result = $db->sql_query($sql);

			$count_sql = array();
			while($row = $db->sql_fetchrow($result))
			{
				$this->user_decrease_postscounter($row['poster_id'], $row['posts']);
			}
			$db->sql_freeresult($result);
		}

		$sql = "SELECT post_id FROM " . POSTS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

		$post_id_sql = '';
		while($row = $db->sql_fetchrow($result))
		{
			$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row['post_id']);
		}
		$db->sql_freeresult($result);

		$sql = "DELETE FROM " . TOPICS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
			OR " . $db->sql_in_set('topic_moved_id', $topics_ids);
		$db->sql_transaction('begin');
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

		$sql = "DELETE FROM " . RATINGS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . TOPIC_VIEW_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . DRAFTS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . POSTS_LIKES_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . POSTS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$db->sql_query($sql);

		if($post_id_sql != '')
		{
			if (!function_exists('remove_search_post'))
			{
				include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
			}
			remove_search_post($post_id_sql);
		}

		$this->topic_poll_delete($topics);
		$db->sql_transaction('commit');

		if (!empty($topics_ids))
		{
			if (!function_exists('attachment_sync_topic'))
			{
				include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_delete.' . PHP_EXT);
			}
			foreach ($topics_ids as $topic_id)
			{
				attachment_sync_topic($topic_id);
			}
		}

		$this->sync_cache(array($forum_id), 0);
		$this->sync('all_forums');

		return true;
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

				// UPI2DB - BEGIN
				$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);

				$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
					SET forum_id = " . $new_forum_id . "
					WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);
				// UPI2DB - END

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

			$this->sync_cache(array($new_forum_id, $old_forum_id), 0);
			$this->sync_topic_details(0, 0, true, false);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Move and rename all topics in a forum
	*/
	function topic_move_ren_all($old_forum_id, $new_forum_id, $title_label = '')
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
			$db->sql_transaction('begin');

			$sql = "UPDATE " . TOPICS_TABLE . "
				SET forum_id = " . $new_forum_id . "
				" . (!empty($title_label) ? ", topic_title = CONCAT(\"" . $db->sql_escape($title_label) . " \", topic_title)" : "") . "
				WHERE forum_id = " . $old_forum_id;
			$db->sql_query($sql);

			$sql = "UPDATE " . POSTS_TABLE . "
				SET forum_id = " . $new_forum_id . "
				WHERE forum_id = " . $old_forum_id;
			$db->sql_query($sql);

			// UPI2DB - BEGIN
			$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
				SET forum_id = " . $new_forum_id . "
				WHERE forum_id = " . $old_forum_id;
			$db->sql_query($sql);

			$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
				SET forum_id = " . $new_forum_id . "
				WHERE forum_id = " . $old_forum_id;
			$db->sql_query($sql);
			// UPI2DB - END

			$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
				SET forum_id = " . $new_forum_id . "
				WHERE forum_id = " . $old_forum_id;
			$db->sql_query($sql);

			// TAGS - BEGIN
			$sql = "UPDATE " . TOPICS_TAGS_MATCH_TABLE . "
				SET forum_id = " . $new_forum_id . "
				WHERE forum_id = " . $old_forum_id;
			$db->sql_query($sql);
			// TAGS - END

			$db->sql_transaction('commit');

			$this->sync_cache(array($new_forum_id, $old_forum_id), 0);
			$this->sync_topic_details(0, 0, true, false);

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

		return true;
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

		return true;
	}

	/**
	* Merge topic(s)
	*/
	function topic_merge($topics, $new_topic_id, $forum_id)
	{
		global $db, $cache, $config, $user, $lang;

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

		// UPI2DB - BEGIN
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
		// UPI2DB - END

		$sql = "DELETE FROM " . TOPICS_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM  " . TOPICS_WATCH_TABLE . "
			WHERE " . $db->sql_in_set('topic_id', $topics_ids);
		$result = $db->sql_query($sql);

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

		// LIKES - BEGIN
		@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		$class_topics = new class_topics();
		$class_topics->topics_posts_likes_resync();
		// LIKES - END

		$db->sql_transaction('commit');

		$this->topic_poll_delete($topics);
		$this->sync_cache(array($forum_id), array($new_topic_id));

		return true;
	}

	/**
	* Split topic(s)
	*/
	function topic_split($posts, $forum_id, $new_forum_id, $topic_id, $split_beyond, $topic_title)
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
			VALUES ('" . $db->sql_escape($topic_title) . "', " . $first_poster . ", " . $topic_time . ", " . $new_forum_id . ", " . TOPIC_UNLOCKED . ", " . POST_NORMAL . ")";
		$db->sql_query($sql);

		$new_topic_id = $db->sql_nextid();

		// We should not update Topic Watch table for a split...
		/*
		$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
			SET topic_id = " . $new_topic_id . "
			WHERE topic_id = " . $topic_id . "
			AND user_id IN (" . $user_id_sql . ")";
		$db->sql_query($sql);
		*/

		$sql = "UPDATE " . POSTS_LIKES_TABLE . "
			SET topic_id = " . $new_topic_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_TABLE . "
			SET topic_id = " . $new_topic_id . ", forum_id = " . $new_forum_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);

		// UPI2DB - BEGIN
		$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
			SET topic_id = " . $new_topic_id . ", forum_id = " . $new_forum_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);

		$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
			SET topic_id = " . $new_topic_id . ", forum_id = " . $new_forum_id . "
			WHERE post_id IN (" . $post_id_sql . ")";
		$db->sql_query($sql);
		// UPI2DB - END

		// LIKES - BEGIN
		@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		$class_topics = new class_topics();
		$class_topics->topics_posts_likes_resync();
		// LIKES - END

		$db->sql_transaction('commit');

		$this->sync_cache(array($new_forum_id, $forum_id), array($new_topic_id, $topic_id));
		$this->sync_topic_details(0, 0, true, false);

		return $new_topic_id;
	}

	/**
	* Recycle topic(s)
	*/
	function topic_recycle($topics_ids, $old_forum_id)
	{
		global $db, $cache, $config, $lang;

		$bin_forum_id = intval($config['bin_forum']);
		if (!empty($bin_forum_id) && ($bin_forum_id != $old_forum_id))
		{
			$this->topic_move($topics_ids, $old_forum_id, $bin_forum_id, false);

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
	* Edit topic(s) labels
	*/
	function topic_label_edit($topics_ids, $label_data)
	{
		global $db, $cache, $config, $bbcode, $user, $lang;

		$topic_label_id = empty($label_data['id']) ? 0 : $label_data['id'];

		if (empty($topic_label_id))
		{
			$topic_label_compiled = '';
		}
		else
		{
			if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
			if (empty($bbcode)) $bbcode = new bbcode();

			if (!class_exists('class_topics')) include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
			if (empty($class_topics)) $class_topics = new class_topics();

			$label_compiled = $class_topics->gen_label_compiled($label_data);
			$topic_label_compiled = $label_compiled;
		}

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_label_id = " . $db->sql_escape($topic_label_id) . ", topic_label_compiled = '" . $db->sql_escape(trim($topic_label_compiled)) . "'
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
		global $db, $cache, $config, $user, $lang;

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

		$sql = "DELETE FROM " . POLL_OPTIONS_TABLE . " WHERE " . $db->sql_in_set('topic_id', $topics);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . POLL_VOTES_TABLE . " WHERE " . $db->sql_in_set('topic_id', $topics);
		$db->sql_query($sql);

		empty_cache_folders(POSTS_CACHE_FOLDER);
	}

	/*
	* Delete a post/poll
	*/
	function post_delete($mode, &$post_data, &$message, &$meta, &$forum_id, &$topic_id, &$post_id)
	{
		global $db, $cache, $config, $lang, $user;

		$poll_deleted = false;

		$bin_mode = false;
		$bin_forum_id = intval($config['bin_forum']);
		if (($mode == 'delete') && !empty($bin_forum_id) && ($bin_forum_id != $forum_id))
		{
			$bin_mode = true;
		}

		if ($mode != 'poll_delete')
		{
			// MG Cash MOD For IP - BEGIN
			if (!empty($config['plugins']['cash']['enabled']))
			{
				$GLOBALS['cm_posting']->update_delete($mode, $post_data, $forum_id, $topic_id, $post_id);
			}
			// MG Cash MOD For IP - END

			if ($post_data['first_post'] && $post_data['last_post'])
			{
				if (!empty($bin_mode))
				{
					$this->topic_recycle(array($topic_id), $forum_id);
				}
				else
				{
					$this->topic_delete($topic_id, $forum_id);
					$poll_deleted = true;
				}
			}
			else
			{
				if (!empty($bin_mode))
				{
					$new_topic_id = $this->post_recycle($post_id, $forum_id, $topic_id, $post_data['topic_title'], false);
				}
				else
				{
					$sql = "DELETE FROM " . POSTS_TABLE . " WHERE post_id = $post_id";
					$db->sql_query($sql);

					// Event Registration - BEGIN
					if ($post_data['first_post'])
					{
						$sql = "DELETE FROM " . REGISTRATION_TABLE . " WHERE topic_id = $topic_id";
						$db->sql_query($sql);

						$sql = "DELETE FROM " . REGISTRATION_DESC_TABLE . " WHERE topic_id = $topic_id";
						$db->sql_query($sql);
					}
					// Event Registration - END

					// UPI2DB - BEGIN
					$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . " WHERE post_id = $post_id";
					$db->sql_query($sql);

					$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . " WHERE post_id = $post_id";
					$db->sql_query($sql);
					// UPI2DB - END

					$sql = "DELETE FROM " . POSTS_LIKES_TABLE . " WHERE post_id = $post_id";
					$db->sql_query($sql);

					if (!function_exists('remove_search_post'))
					{
						include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
					}
					remove_search_post($post_id);
				}
			}
		}

		if ($post_data['has_poll'] && $post_data['edit_poll'] && (($mode == 'poll_delete') || (($mode == 'delete') && $post_data['first_post'] && $post_data['last_post'])))
		{
			if (empty($bin_mode) && empty($poll_deleted))
			{
				$this->topic_poll_delete($topic_id);
			}
		}

		if (($mode == 'delete') && $post_data['first_post'] && $post_data['last_post'])
		{
			$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '">';
			$message = $lang['Deleted'];
		}
		else
		{
			$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">';
			$message = (($mode == 'poll_delete') ? $lang['Poll_delete'] : $lang['Deleted']) . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>');
		}

		$message .=  '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

		if (!empty($forum_id))
		{
			$this->sync('forum', $forum_id);
		}

		// LIKES - BEGIN
		@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		$class_topics = new class_topics();
		$class_topics->topics_posts_likes_resync();
		// LIKES - END

		$this->sync_cache(0, 0);
		board_stats();
		cache_tree(true);

		return true;
	}

	/**
	* Recycle post(s)
	*/
	function post_recycle($posts_ids, $old_forum_id, $old_topic_id, $old_topic_title, $split_beyond)
	{
		global $db, $cache, $config, $lang;

		$bin_forum_id = intval($config['bin_forum']);
		if (!empty($bin_forum_id) && ($bin_forum_id != $old_forum_id))
		{
			if (empty($old_topic_title))
			{
				$topic_data = $this->get_topic_data($old_topic_id);
				$old_topic_title = !empty($topic_data['topic_title']) ? $topic_data['topic_title'] : '';
			}
			$topic_title = trim(substr($lang['POST_AUTO_SPLIT'] . ' (' . $old_topic_id . ') ' . $old_topic_title, 0, 254));
			$new_topic_id = $this->topic_split($posts_ids, $old_forum_id, $bin_forum_id, $old_topic_id, $split_beyond, $topic_title);
			return $new_topic_id;
		}
		else
		{
			return false;
		}
	}

	/*
	* Change post time
	*/
	function post_change_time($post_id, $post_time)
	{
		global $db, $user;

		/*
		$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
		if ($user->data['user_id'] != $founder_id)
		{
			return false;
		}
		*/

		$sql = "SELECT post_edit_time FROM " . POSTS_TABLE . "
			WHERE post_id = '" . $post_id . "'
			LIMIT 1";
		$result = $db->sql_query($sql);

		while($row = $db->sql_fetchrow($result))
		{
			$post_edit_time = $row['post_edit_time'];
		}
		$db->sql_freeresult($result);

		if ($post_edit_time < $post_time)
		{
			$post_edit_time = $post_time;
		}

		$sql = "UPDATE " . POSTS_TABLE . "
			SET post_time = '" . $post_time . "', post_edit_time = '" . $post_edit_time . "'
			WHERE post_id = '" . $post_id . "'";
		$result = $db->sql_query($sql);

		$is_first_post = false;
		$sql = "SELECT topic_id
			FROM " . TOPICS_TABLE . "
			WHERE topic_first_post_id = '" . $post_id . "'
			LIMIT 1";
		$result = $db->sql_query($sql);

		if($row = $db->sql_fetchrow($result))
		{
			$is_first_post = true;
			$topic_id = $row['topic_id'];
		}
		$db->sql_freeresult($result);

		if ($is_first_post)
		{
			$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_time = '" . $post_time . "'
				WHERE topic_id = '" . $topic_id . "'";
			$result = $db->sql_query($sql);
		}

		return true;
	}

	/*
	* Change poster
	*/
	function post_change_poster($post_id, $poster_name)
	{
		global $db, $user;

		/*
		$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
		if ($user->data['user_id'] != $founder_id)
		{
			return false;
		}
		*/

		$sql = get_users_sql($poster_name, false, false, true, false);
		$result = $db->sql_query($sql);

		if(!($row = $db->sql_fetchrow($result)))
		{
			$db->sql_freeresult($result);
			return false;
		}
		$poster_id = $row['user_id'];
		$db->sql_freeresult($result);

		$is_first_post = false;
		$sql = "SELECT topic_id
			FROM " . TOPICS_TABLE . "
			WHERE topic_first_post_id = '" . $post_id . "'
			LIMIT 1";
		$result = $db->sql_query($sql);

		if($row = $db->sql_fetchrow($result))
		{
			$is_first_post = true;
			$topic_id = $row['topic_id'];
		}
		$db->sql_freeresult($result);

		$is_post_count = false;
		$sql = "SELECT p.forum_id, p.poster_id, p.post_username, f.forum_postcount
			FROM " . POSTS_TABLE . " p, " . FORUMS_TABLE . " f
			WHERE p.post_id = '" . $post_id . "'
				AND f.forum_id = p.forum_id
			LIMIT 1";
		$result = $db->sql_query($sql);
		if($row = $db->sql_fetchrow($result))
		{
			$old_poster_id = $row['poster_id'];
			$old_poster_username = $row['post_username'];
			$is_post_count = ($row['forum_postcount'] ? true : false);
		}
		$db->sql_freeresult($result);

		$sql = "UPDATE " . POSTS_TABLE . " SET poster_id = '" . $poster_id . "', post_username = '' WHERE post_id = '" . $post_id . "'";
		$result = $db->sql_query($sql);

		if ($is_first_post)
		{
			$sql = "UPDATE " . TOPICS_TABLE . " SET topic_poster = '" . $poster_id . "' WHERE topic_id = '" . $topic_id . "'";
			$result = $db->sql_query($sql);
		}

		if ($is_post_count)
		{
			if ($poster_id != ANONYMOUS)
			{
				$sql = "UPDATE " . USERS_TABLE . " SET user_posts = (user_posts + 1) WHERE user_id = '" . $poster_id . "'";
				$result = $db->sql_query($sql);
				$this->autogroup($poster_id);
			}

			if ($old_poster_id != ANONYMOUS)
			{
				$this->user_decrease_postscounter($old_poster_id, 1);
				$this->autogroup($old_poster_id);
			}
		}

		return true;
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

		$sql = "SELECT forum_name FROM " . FORUMS_TABLE . " WHERE forum_id = " . (int) $id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['forum_name'];
	}

	/**
	* Get topic data
	*/
	function get_topic_data($topic_id)
	{
		global $db, $cache;

		$topic_data = array();
		$sql = "SELECT * FROM " . TOPICS_TABLE . " WHERE topic_id = " . (int) $topic_id . " LIMIT 1";
		$result = $db->sql_query($sql);
		$topic_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $topic_data;
	}

	/*
	* Get first and last post id for a topic
	*/
	function get_first_last_post_id($topic_id)
	{
		global $db, $config;

		$topic_data = array();

		$sql = "SELECT MAX(post_id) AS last_post_id, MIN(post_id) AS first_post_id, COUNT(post_id) - 1 AS replies
			FROM " . POSTS_TABLE . "
			WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$topic_data = $row;
		}

		return $topic_data;
	}

	/*
	* Get forum last post id
	*/
	function get_forum_last_post_id($forum_id)
	{
		global $db, $config;

		$last_post_id = 0;

		$sql = "SELECT MAX(post_id) AS last_post_id
			FROM " . POSTS_TABLE . "
			WHERE forum_id = " . $forum_id;
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$last_post_id = $row['last_post_id'];
		}

		return $last_post_id;
	}

	// Synchronise functions for forums/topics
	function sync($type, $id = false)
	{
		global $db, $cache, $config;

		switch($type)
		{
			case 'all_forums':
				$sql = "SELECT forum_id
					FROM " . FORUMS_TABLE;
				$result = $db->sql_query($sql);
				while($row = $db->sql_fetchrow($result))
				{
					$this->sync('forum', $row['forum_id']);
				}
				$db->sql_freeresult($result);

				$sql = "UPDATE " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
					SET f.forum_last_topic_id = p.topic_id, f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
					WHERE f.forum_last_post_id = p.post_id
						AND t.topic_id = p.topic_id
						AND p.poster_id = u.user_id";
				$result = $db->sql_query($sql);

				break;

			case 'all_topics':
				$sql = "SELECT topic_id
					FROM " . TOPICS_TABLE;
				$result = $db->sql_query($sql);
				while($row = $db->sql_fetchrow($result))
				{
					$this->sync('topic', $row['topic_id']);
				}
				$db->sql_freeresult($result);

				$sql = "UPDATE " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
					SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
					WHERE t.topic_first_post_id = p.post_id
						AND p.poster_id = u.user_id
						AND t.topic_last_post_id = p2.post_id
						AND p2.poster_id = u2.user_id";
				$db->sql_query($sql);

				break;

			case 'forum':
				$sql = "SELECT MAX(post_id) AS last_post, COUNT(post_id) AS total
					FROM " . POSTS_TABLE . "
					WHERE forum_id = " . (int) $id;
				$result = $db->sql_query($sql);

				if ($row = $db->sql_fetchrow($result))
				{
					$last_post = ($row['last_post']) ? $row['last_post'] : 0;
					$total_posts = ($row['total']) ? $row['total'] : 0;
				}
				else
				{
					$last_post = 0;
					$total_posts = 0;
				}

				$sql = "SELECT COUNT(topic_id) AS total
					FROM " . TOPICS_TABLE . "
					WHERE forum_id = " . (int) $id;
				$result = $db->sql_query($sql);
				$total_topics = ($row = $db->sql_fetchrow($result)) ? (($row['total']) ? $row['total'] : 0) : 0;

				$sql = "UPDATE " . FORUMS_TABLE . "
					SET forum_last_post_id = $last_post, forum_posts = $total_posts, forum_topics = $total_topics
					WHERE forum_id = " . (int) $id;
				$db->sql_query($sql);

				break;

			case 'topic':
				$sql = "SELECT MAX(post_id) AS last_post, MIN(post_id) AS first_post, COUNT(post_id) AS total_posts
					FROM " . POSTS_TABLE . "
					WHERE topic_id = " . (int) $id;
				$result = $db->sql_query($sql);

				if ($row = $db->sql_fetchrow($result))
				{
					if ($row['total_posts'])
					{
						// Correct the details of this topic
						$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET topic_replies = ' . ($row['total_posts'] - 1) . ', topic_first_post_id = ' . $row['first_post'] . ', topic_last_post_id = ' . $row['last_post'] . "
							WHERE topic_id = $id";
						$db->sql_query($sql);
					}
					else
					{
						// There are no replies to this topic
						// Check if it is a move stub
						$sql_move = 'SELECT topic_moved_id
							FROM ' . TOPICS_TABLE . "
							WHERE topic_id = " . (int) $id;
						$result_move = $db->sql_query($sql_move);

						if ($row = $db->sql_fetchrow($result_move))
						{
							if (!$row['topic_moved_id'])
							{
								$sql = 'DELETE FROM ' . TOPICS_TABLE . " WHERE topic_id = " . (int) $id;
								$db->sql_query($sql);
							}
						}

						$db->sql_freeresult($result_move);
					}
				}
				$db->sql_freeresult($result);
				if (!function_exists('attachment_sync_topic'))
				{
					include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_delete.' . PHP_EXT);
				}
				attachment_sync_topic($id);

				break;
		}

		board_stats();
		return true;
	}

	/**
	* Resync cache after topics moderation
	*/
	function sync_cache($forums_ids, $topics_ids)
	{
		global $db, $cache, $config, $lang;

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
					$this->sync('forum', $forums_ids[$i]);
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
					$this->sync('topic', $topics_ids[$i]);
				}
			}
		}

		return true;
	}

	/*
	* Synchronize topic details
	*/
	function sync_topic_details($topic_id, $forum_id, $all_data_only = true, $skip_all_data = false)
	{
		global $db, $cache, $config, $lang;

		if (empty($all_data_only))
		{
			$last_post_id = $this->get_forum_last_post_id($forum_id);
			$topic_data = $this->get_first_last_post_id($topic_id);

			if (empty($last_post_id) || empty($topic_data['first_post_id']) || empty($topic_data['last_post_id']))
			{
				return false;
			}

			$sql = "UPDATE " . TOPICS_TABLE . " t
				SET t.topic_first_post_id = " . $topic_data['first_post_id'] . ", t.topic_last_post_id = " . $topic_data['last_post_id'] . ", t.topic_replies = " . $topic_data['replies'] . "
				WHERE t.topic_id = " . $topic_id;
			$db->sql_query($sql);

			$sql = "UPDATE " . FORUMS_TABLE . " f
				SET f.forum_last_post_id = " . $last_post_id . "
				WHERE f.forum_id = " . $forum_id;
			$db->sql_query($sql);
		}

		if (empty($skip_all_data))
		{
			$sql = "UPDATE " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
				SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
				WHERE t.topic_first_post_id = p.post_id
					AND p.poster_id = u.user_id
					AND t.topic_last_post_id = p2.post_id
					AND p2.poster_id = u2.user_id";
			$db->sql_query($sql);

			$sql = "UPDATE " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
				SET f.forum_last_topic_id = p.topic_id, f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
				WHERE f.forum_last_post_id = p.post_id
					AND t.topic_id = p.topic_id
					AND p.poster_id = u.user_id";
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
				SET p.post_subject = t.topic_title
				WHERE p.post_id = t.topic_first_post_id";
			$result = $db->sql_query($sql);
		}

		return;
	}

	/*
	* Sync post stats and details
	*/
	function sync_post_stats(&$mode, &$post_data, &$forum_id, &$topic_id, &$post_id, &$user_id)
	{
		global $db, $cache, $config, $lang;

		if (!function_exists('update_user_color') || !function_exists('update_user_posts_details'))
		{
			include(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
		}

		$decrease_counter = ($mode == 'delete') ? true : false;
		$sign = ($mode == 'delete') ? '- 1' : '+ 1';
		$forum_update_sql = "forum_posts = forum_posts $sign";
		$topic_update_sql = '';

		if ($mode == 'delete')
		{
			if ($post_data['last_post'])
			{
				if ($post_data['first_post'])
				{
					$forum_update_sql .= ', forum_topics = forum_topics - 1';
				}
				else
				{

					$topic_update_sql .= 'topic_replies = topic_replies - 1';
					$topic_data = $this->get_first_last_post_id($topic_id);
					if (!empty($topic_data['last_post_id']))
					{
						$topic_update_sql .= ', topic_last_post_id = ' . $topic_data['last_post_id'];
					}
				}

				if ($post_data['last_topic'])
				{
					$last_post_id = $this->get_forum_last_post_id($forum_id);
					if (!empty($last_post_id))
					{
						$forum_update_sql .= ($row['last_post_id']) ? ', forum_last_post_id = ' . $last_post_id : ', forum_last_post_id = 0';
					}
				}
			}
			elseif ($post_data['first_post'])
			{
				$topic_data = $this->get_first_last_post_id($topic_id);
				if (!empty($topic_data['first_post_id']))
				{
					$topic_update_sql .= 'topic_replies = topic_replies - 1, topic_first_post_id = ' . $topic_data['first_post_id'];
				}
			}
			else
			{
				$topic_update_sql .= 'topic_replies = topic_replies - 1';
			}
		}
		elseif ($mode != 'poll_delete')
		{
			$forum_update_sql .= ", forum_last_post_id = $post_id" . (($mode == 'newtopic') ? ", forum_topics = forum_topics $sign" : "");
			$topic_update_sql = "topic_last_post_id = $post_id" . (($mode == 'reply') ? ", topic_replies = topic_replies $sign" : ", topic_first_post_id = $post_id");
		}
		else
		{
			// Shall we update poll fields for this topic?
			//$topic_update_sql .= 'topic_vote = 0';
		}

		$db->sql_transaction('begin');

		if ($mode != 'poll_delete')
		{
			$sql = "UPDATE " . FORUMS_TABLE . "
				SET $forum_update_sql
				WHERE forum_id = $forum_id";
			$db->sql_query($sql);
		}

		if ($topic_update_sql != '')
		{
			$sql = "UPDATE " . TOPICS_TABLE . "
				SET $topic_update_sql
				WHERE topic_id = $topic_id";
			$db->sql_query($sql);
		}

		if ($mode != 'poll_delete')
		{
			$forum_postcount = $this->forum_check_postcount($forum_id);

			$this->sync_topic_details($topic_id, $forum_id, false, false);

			if (!empty($forum_postcount))
			{
				if (!empty($decrease_counter))
				{
					$this->user_decrease_postscounter($user_id, 1);
				}
				else
				{
					$sql = "UPDATE " . USERS_TABLE . " SET user_posts = user_posts " . $sign  . " WHERE user_id = " . $user_id;
					$db->sql_query($sql);
				}

				$db->sql_transaction('commit');

				if ($config['site_history'])
				{
					$current_time = time();
					$minutes = gmdate('is', $current_time);
					$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
					$sql='UPDATE ' . SITE_HISTORY_TABLE . ' SET '. (($mode == 'newtopic' || $post_data['first_post']) ? 'new_topics=new_topics' : 'new_posts=new_posts') . $sign . ' WHERE date=' . $hour_now;
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result || !$db->sql_affectedrows())
					{
						$sql = 'INSERT IGNORE INTO ' . SITE_HISTORY_TABLE . ' (date, ' . (($mode == 'newtopic' || $post_data['first_post']) ? 'new_topics' : 'new_posts') . ')
							VALUES (' . $hour_now . ', "1")';
						$db->sql_query($sql);
					}
				}

				if ($user_id != ANONYMOUS)
				{
					$this->autogroup($user_id);
				}
			}

			$this->sync_cache(0, 0);
			board_stats();
			cache_tree(true);
		}

		return;
	}

	/*
	* Autogroup check
	*/
	function autogroup($user_id)
	{
		global $db, $cache, $config, $lang;

		if ($user_id != ANONYMOUS)
		{
			if (!function_exists('update_user_color') || !function_exists('update_user_posts_details'))
			{
				include(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
			}

			$sql = "SELECT ug.user_id, g.group_id as g_id, u.user_posts, u.group_id, u.user_color, g.group_count, g.group_color, g.group_count_max FROM (" . GROUPS_TABLE . " g, " . USERS_TABLE . " u)
					LEFT JOIN ". USER_GROUP_TABLE." ug ON g.group_id = ug.group_id AND ug.user_id = '" . $user_id . "'
					WHERE u.user_id = '" . $user_id . "'
					AND g.group_single_user = '0'
					AND g.group_count_enable = '1'
					AND g.group_moderator <> '" . $user_id . "'";
			$result = $db->sql_query($sql);

			$user_cache_refresh = false;
			while ($group_data = $db->sql_fetchrow($result))
			{
				$user_already_added = empty($group_data['user_id']) ? false : true;
				$user_add = (($group_data['user_posts'] >= $group_data['group_count']) && ($group_data['user_posts'] < $group_data['group_count_max'])) ? true : false;
				$user_remove = (($group_data['user_posts'] < $group_data['group_count']) || ($group_data['user_posts'] >= $group_data['group_count_max'])) ? true : false;
				if ($user_add && !$user_already_added)
				{
					update_user_color($user_id, $group_data['group_color'], $group_data['g_id'], false, false);
					update_user_posts_details($user_id, $group_data['group_color'], '', false, false);
					$user_cache_refresh = true;
					//user join a autogroup
					$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
						VALUES (" . $group_data['g_id'] . ", $user_id, '0')";
					$db->sql_query($sql);
				}
				elseif ($user_already_added && $user_remove)
				{
					update_user_color($user_id, $config['active_users_color'], 0);
					update_user_posts_details($user_id, '', '', false, false);
					$user_cache_refresh = true;
					//remove user from auto group
					$sql = "DELETE FROM " . USER_GROUP_TABLE . "
						WHERE group_id = '" . $group_data['g_id'] . "'
						AND user_id = '" . $user_id . "'";
					$db->sql_query($sql);
				}
			}
			$db->sql_freeresult($result);

			empty_cache_folders(SQL_CACHE_FOLDER);
			if (!empty($user_cache_refresh))
			{
				empty_cache_folders(USERS_CACHE_FOLDER);
			}
		}

		return;
	}

}

?>