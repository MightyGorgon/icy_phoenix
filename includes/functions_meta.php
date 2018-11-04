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

/*
* Create $meta_content array with some core informations about page and its content
*/
function create_meta_content()
{
	global $db, $cache, $config, $lang;
	global $meta_content;

	if ((!empty($meta_content['post_id']) && ($meta_content['post_id'] > 0)) || (!empty($meta_content['topic_id']) && ($meta_content['topic_id'] > 0)))
	{
		if (!empty($meta_content['post_id']) && ($meta_content['post_id'] > 0))
		{
			$sql = "SELECT f.forum_name, f.forum_name_clean, t.topic_title, t.topic_title_clean, t.topic_tags, t.topic_label_compiled, p.topic_id, p.forum_id
							FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
							WHERE p.post_id = '" . $meta_content['post_id'] . "'
								AND t.topic_id = p.topic_id
								AND f.forum_id = p.forum_id
							LIMIT 1";
			// Mighty Gorgon: shall we cache this as well? Maybe too many files... better avoid...
			//$result = $db->sql_query($sql, 0, 'posts_meta_', TOPICS_CACHE_FOLDER);
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
		}
		else
		{
			$sql = "SELECT f.forum_name, f.forum_name_clean, t.forum_id, t.topic_id, t.topic_title, t.topic_title_clean, t.topic_tags, t.topic_label_compiled
							FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
							WHERE t.topic_id = '" . $meta_content['topic_id'] . "'
								AND f.forum_id = t.forum_id
							LIMIT 1";
			$db->sql_return_on_error(true);
			$result = CACHE_TOPICS_META ? $db->sql_query($sql, 0, 'topics_meta_', TOPICS_CACHE_FOLDER) : $db->sql_query($sql);
			$db->sql_return_on_error(false);
		}
		if($result)
		{
			while ($meta_row = $db->sql_fetchrow($result))
			{
				$meta_content['forum_id'] = $meta_row['forum_id'];
				$meta_content['forum_name'] = strip_tags(stripslashes($meta_row['forum_name']));
				$meta_content['forum_name_clean'] = $meta_row['forum_name_clean'];

				$meta_content['topic_id'] = $meta_row['topic_id'];
				$meta_content['topic_title'] = strip_tags(stripslashes($meta_row['topic_title']));
				$meta_content['topic_title_clean'] = $meta_row['topic_title_clean'];
				$meta_content['topic_tags'] = $meta_row['topic_tags'];
				$meta_content['topic_label_compiled'] = $meta_row['topic_label_compiled'];

				$meta_content['keywords'] = $meta_content['topic_tags'];
				$meta_content['keywords'] = empty($meta_content['keywords']) ? str_replace(array(' ', ',, '), array(', ', ', '), ip_clean_string($meta_content['topic_title'], $lang['ENCODING'], true)) : $meta_content['keywords'];
				$meta_content['description'] = $meta_content['forum_name'] . ' - ' . $meta_content['topic_title'];
				//$meta_content['page_title'] = $meta_content['forum_name'] . ' :: ' . $meta_content['page_title'];
				$meta_content['page_title'] = $meta_content['page_title'];
			}
			$db->sql_freeresult($result);
		}
	}
	elseif (!empty($meta_content['forum_id']) && ($meta_content['forum_id'] > 0))
	{
		$sql = "SELECT f.forum_name, f.forum_name_clean, f.forum_desc
						FROM " . FORUMS_TABLE . " f
						WHERE f.forum_id = '" . $meta_content['forum_id'] . "'
							AND f.forum_type = " . FORUM_POST . "
						LIMIT 1";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while ($meta_row = $db->sql_fetchrow($result))
			{
				$meta_content['forum_name'] = strip_tags(stripslashes($meta_row['forum_name']));
				$meta_content['forum_name_clean'] = $meta_row['forum_name_clean'];

				$meta_content['description'] = $meta_content['forum_name'] . (empty($meta_row['forum_desc']) ? '' : (' - ' . strip_tags(stripslashes($meta_row['forum_desc']))));
				$meta_content['keywords'] = $meta_content['forum_name'] . ', ';
			}
			$db->sql_freeresult($result);
		}
	}
	elseif (!empty($meta_content['cat_id']) && ($meta_content['cat_id'] > 0))
	{
		$sql = "SELECT c.forum_name AS cat_name, c.forum_name_clean AS cat_name_clean, c.forum_desc
						FROM " . FORUMS_TABLE . " c
						WHERE c.forum_id = " . $meta_content['cat_id'] . "
							AND f.forum_type = " . FORUM_CAT . "
						LIMIT 1";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while ($meta_row = $db->sql_fetchrow($result))
			{
				$meta_content['cat_name'] = strip_tags(stripslashes($meta_row['cat_name']));
				$meta_content['cat_name_clean'] = $meta_row['cat_name_clean'];

				$meta_content['description'] = $meta_content['cat_name'] . (empty($meta_row['cat_desc']) ? '' : (' - ' . strip_tags(stripslashes($meta_row['cat_desc']))));
				$meta_content['keywords'] = $meta_content['cat_name'] . ', ';
			}
			$db->sql_freeresult($result);
		}
	}
	else
	{
		/*
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		*/
	}

	if (!empty($meta_content['cat_id']) && !empty($meta_content['cat_title']) && empty($meta_content['cat_title_clean']))
	{
		$meta_content['cat_title_clean'] = ip_clean_string($meta_row['cat_title'], $lang['ENCODING']);
		if (!function_exists('update_clean_cat_title'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
		}
		update_clean_cat_title($meta_content['cat_id'], $meta_content['cat_title_clean']);
	}

	if (!empty($meta_content['forum_id']) && !empty($meta_content['forum_name']) && empty($meta_content['forum_name_clean']))
	{
		$meta_content['forum_name_clean'] = ip_clean_string($meta_row['forum_name'], $lang['ENCODING']);
		if (!function_exists('update_clean_forum_name'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
		}
		update_clean_forum_name($meta_content['forum_id'], $meta_content['forum_name_clean']);
	}

	if (!empty($meta_content['topic_id']) && !empty($meta_content['topic_title']) && empty($meta_content['topic_title_clean']))
	{
		$meta_content['topic_title_clean'] = ip_clean_string($meta_row['topic_title'], $lang['ENCODING']);
		if (!function_exists('update_clean_topic_title'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
		}
		update_clean_topic_title($meta_content['topic_id'], $meta_content['topic_title_clean']);
	}

	// Mighty Gorgon: shall we UTF8 decode also page_title and meta?
	/*
	$meta_content['page_title'] = ip_utf8_decode($meta_content['page_title']);
	$meta_content['description'] = ip_utf8_decode($meta_content['description']);
	$meta_content['keywords'] = ip_utf8_decode($meta_content['keywords']);
	*/

	return true;
}

?>