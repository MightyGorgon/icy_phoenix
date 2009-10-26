<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Topics Tags class
*/
class class_topics_tags
{

	/*
	* Create tags array
	*/
	function create_tags_array($topic_tags)
	{
		global $lang;

		$topic_tags_array = array();
		if (!empty($topic_tags))
		{
			$topic_tags_array = explode(',', $topic_tags);
			for ($i = 0; $i < sizeof($topic_tags_array); $i++)
			{
				$topic_tags_array[$i] = substr(ip_clean_string(trim($topic_tags_array[$i]), $lang['ENCODING'], true), 0, 50);
			}
		}

		return $topic_tags_array;
	}

	/*
	* Check if tag exists
	*/
	function check_tag($tag)
	{
		global $db, $lang;

		$tag_id = false;
		$sql = "SELECT tag_id FROM " . TOPICS_TAGS_LIST_TABLE . " WHERE tag_text = " . $db->sql_validate_value($tag);
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$tag_id = $row['tag_id'];
			$db->sql_freeresult($result);
		}

		return $tag_id;
	}

	/*
	* Check if tag exists for topic id
	*/
	function check_tag_match_exists($tag_id, $topic_id)
	{
		global $db, $lang;

		$return = false;
		$sql = "SELECT tag_id FROM " . TOPICS_TAGS_MATCH_TABLE . " WHERE tag_id = " . $tag_id . " AND topic_id = " . $topic_id;
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$return = true;
		}

		return $return;
	}

	/*
	* Create tag entry
	*/
	function create_tag_entry($tag)
	{
		global $db;

		$tag_id = false;
		$sql_ary = array('tag_text' => $tag, 'tag_count' => 1);
		$sql = "INSERT INTO " . TOPICS_TAGS_LIST_TABLE . " " . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
		$tag_id = $db->sql_nextid();

		return $tag_id;
	}

	/*
	* Update tag entry
	*/
	function update_tag_entry($tag_id)
	{
		global $db, $lang;

		$tag_count = 0;
		$sql = "SELECT COUNT(tag_id) as tag_count FROM " . TOPICS_TAGS_MATCH_TABLE . " WHERE tag_id = " . $tag_id;
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$tag_count = $row['tag_count'];
			$db->sql_freeresult($result);
		}

		$sql = "UPDATE " . TOPICS_TAGS_LIST_TABLE . " SET tag_count = " . $tag_count . " WHERE tag_id = " . $tag_id;
		$db->sql_query($sql);

		$this->remove_zero_tags();

		return $tag_id;
	}

	/*
	* Submit tags
	*/
	function submit_tags($topic_id, $forum_id, $tags, $update = false)
	{
		global $db, $lang;

		$old_tags = array();
		if ($update)
		{
			$topics_ids_array = array($topic_id);
			$old_tags = $this->get_topics_tags($topics_ids_array);
			$tags_to_be_removed = array();
			$tags_to_be_removed = array_diff($old_tags, $tags);
			if (!empty($tags_to_be_removed))
			{
				$this->remove_tag_text_from_match($tags_to_be_removed, $topic_id);
			}
			//$this->remove_tags($topic_id);
		}

		if (empty($tags) || !is_array($tags))
		{
			return false;
		}

		if (empty($forum_id))
		{
			$forum_id = $this->get_forum_id($topic_id);
			if (empty($forum_id))
			{
				return false;
			}
		}

		for ($i = 0; $i < sizeof($tags); $i++)
		{
			$tag_created = false;
			$tag_id = $this->check_tag($tags[$i]);
			if ($tag_id === false)
			{
				$tag_created = true;
				$tag_id = $this->create_tag_entry($tags[$i]);
			}
			if (empty($tag_id))
			{
				return false;
			}
			if (!$this->check_tag_match_exists($tag_id, $topic_id))
			{
				$sql_ary = array('tag_id' => $tag_id, 'topic_id' => $topic_id, 'forum_id' => $forum_id);
				$sql = "INSERT INTO " . TOPICS_TAGS_MATCH_TABLE . " " . $db->sql_build_array('INSERT', $sql_ary);
				$db->sql_query($sql);
				if (!$tag_created)
				{
					$tag_id = $this->update_tag_entry($tag_id);
				}
			}
		}

		return true;
	}

	/*
	* Get forum id
	*/
	function get_forum_id($topic_id)
	{
		global $db, $lang;

		$sql = "SELECT forum_id FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$forum_id = $row['forum_id'];
			$db->sql_freeresult($result);
		}
		else
		{
			return false;
		}

		return $forum_id;
	}

	/*
	* Get total tags
	*/
	function get_total_tags()
	{
		global $db, $lang;

		$sql = "SELECT count(tag_id) AS total FROM " . TOPICS_TAGS_LIST_TABLE;
		$result = $db->sql_query($sql);

		$total_items = 0;
		if ($total = $db->sql_fetchrow($result))
		{
			$total_items = $total['total'];
		}
		$db->sql_freeresult($result);

		return $total_items;
	}

	/*
	* Get tags
	*/
	function get_tags($start, $limit)
	{
		global $db, $lang;

		$tags = array();
		$sql = "SELECT l.*
						FROM " . TOPICS_TAGS_LIST_TABLE . " l
						ORDER BY l.tag_count DESC, l.tag_text
						LIMIT " . $start . ", " . $limit;
		$result = $db->sql_query($sql);
		$tags = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $tags;
	}

	/*
	* Get topics tags
	*/
	function get_topics_tags($topics_ids_array)
	{
		global $db, $lang;

		$tags = array();
		$sql = "SELECT DISTINCT m.tag_id, l.tag_text
						FROM " . TOPICS_TAGS_MATCH_TABLE . " m, " . TOPICS_TAGS_LIST_TABLE . " l
						WHERE " . $db->sql_in_set('m.topic_id', $topics_ids_array) . "
							AND l.tag_id = m.tag_id
						ORDER BY l.tag_text";
		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$tags[] = $row['tag_text'];
		}
		$db->sql_freeresult($result);

		return $tags;
	}

	/*
	* Get topics from tag
	*/
	function get_topics_with_tags($tags, $start, $limit)
	{
		global $db, $lang;

		$limit_sql = (!empty($start) ? (" LIMIT " . $start . (!empty($limit) ? (", " . $limit) : '')) : '');

		$topics = array();
		$sql = "SELECT t.*
						FROM " . TOPICS_TAGS_MATCH_TABLE . " m, " . TOPICS_TAGS_LIST_TABLE . " l, " . TOPICS_TABLE . " t
						WHERE " . $db->sql_in_set('l.tag_text', $tags) . "
							AND l.tag_id = m.tag_id
							AND t.topic_id = m.topic_id
						ORDER BY t.topic_id"
						. $limit_sql;
		$result = $db->sql_query($sql);
		$topics = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $topics;
	}

	/*
	* Delete all tags which account to zero
	*/
	function remove_zero_tags()
	{
		global $db, $lang;

		$sql = "DELETE FROM " . TOPICS_TAGS_LIST_TABLE . " WHERE tag_count = 0";
		$result = $db->sql_query($sql);

		return true;
	}

	/*
	* Delete all tags for selected topics
	*/
	function remove_tag_from_match($tags_ids_array, $topic_id)
	{
		global $db, $lang;

		$sql = "DELETE FROM " . TOPICS_TAGS_MATCH_TABLE . " WHERE topic_id = " . $topic_id . " AND " . $db->sql_in_set('tag_id', $tags_ids_array);
		$result = $db->sql_query($sql);

		return true;
	}

	/*
	* Delete all tags text for selected topics
	*/
	function remove_tag_text_from_match($tags_texts_array, $topic_id)
	{
		global $db, $lang;

		$sql = "SELECT tag_id
						FROM " . TOPICS_TAGS_LIST_TABLE . "
						WHERE " . $db->sql_in_set('tag_text', $tags_texts_array);
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while ($row = $db->sql_fetchrow($result))
			{
				$tag_id = $row['tag_id'];
				$tags_ids_array = array($tag_id);
				$this->remove_tag_from_match($tags_ids_array, $topic_id);
				$tag_id = $this->update_tag_entry($tag_id);
			}
		}

		return true;
	}

	/*
	* Delete all tags for selected topic
	*/
	function remove_tags($topic_id)
	{
		global $db;

		$sql = "SELECT tag_id
						FROM " . TOPICS_TAGS_MATCH_TABLE . "
						WHERE topic_id = " . $topic_id;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while ($row = $db->sql_fetchrow($result))
			{
				$tag_id = $row['tag_id'];
				$tags_ids_array = array($tag_id);
				$this->remove_tag_from_match($tags_ids_array, $topic_id);
				$tag_id = $this->update_tag_entry($tag_id);
			}
		}

		return true;
	}

}

?>