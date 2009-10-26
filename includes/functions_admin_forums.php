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

// admin_forums_extend.php - BEGIN

function admin_add_error($msg)
{
	global $error, $error_msg, $lang;

	$error = true;
	$error_msg .= (empty($error_msg) ? '<br />' : '<br /><br />') . (isset($lang[$msg]) ? $lang[$msg] : $msg);
}

function admin_get_nav_cat_desc($cur = '')
{
	global $nav_separator, $lang;

	$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

	$nav_cat_desc = make_cat_nav_tree($cur, 'admin_forums_extend');
	if (!empty($nav_cat_desc))
	{
		$nav_cat_desc = $nav_separator . $nav_cat_desc;
	}
	return $nav_cat_desc;
}

function delete_item($old, $new = '', $topic_dest = '')
{
	global $db;

	// no changes
	if ($old == $new) return;

	// old type and id
	$old_type = substr($old, 0, 1);
	$old_id = intval(substr($old, 1));

	// new type and id
	$new_type = substr($new, 0, 1);
	$new_id = intval(substr($new, 1));
	if (($new_id == 0) || !in_array($new_type, array(POST_FORUM_URL, POST_CAT_URL)))
	{
		$new_type = POST_CAT_URL;
		$new_id = 0;
	}

	// topic dest
	$dst_type = substr($topic_dest, 0, 1);
	$dst_id = intval(substr($topic_dest, 1));
	if (($dst_id == 0) || ($dst_type != POST_FORUM_URL))
	{
		$topic_dest = '';
	}

	// re-attach all the content to the new id
	if (!empty($new))
	{
		$sql = "UPDATE " . FORUMS_TABLE . "
					SET main_type = '$new_type', parent_id = $new_id
					WHERE main_type = '$old_type' AND parent_id = $old_id";
		$db->sql_query($sql);
	}

	// topics move
	if (!empty($topic_dest) && ($dst_type == POST_FORUM_URL))
	{
		if (($dst_type == POST_FORUM_URL) && ($old_type == POST_FORUM_URL))
		{
			// topics
			$sql = "UPDATE " . TOPICS_TABLE . " SET forum_id = $dst_id WHERE forum_id = $old_id";
			$db->sql_query($sql);

			// posts
			$sql = "UPDATE " . POSTS_TABLE . " SET forum_id = $dst_id WHERE forum_id = $old_id";
			$db->sql_query($sql);

			sync('forum', $dst_id);
		}
	}

	// all what is attached to a forum
	if ($old_type == POST_FORUM_URL)
	{
		// read current moderators for the old forum
		$sql = "SELECT ug.user_id FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug
					WHERE a.forum_id = $old_id
						AND a.auth_mod = 1
						AND ug.group_id = a.group_id";
		$result = $db->sql_query($sql);

		$user_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$user_ids[] = $row['user_id'];
		}
		$db->sql_freeresult($result);

		// remove moderator status for those ones
		if (!empty($user_ids))
		{
			$old_moderators = implode(', ', $user_ids);

			// check which ones remain moderators
			$sql = "SELECT ug.user_id FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug
						WHERE a.forum_id <> $old_id
							AND a.auth_mod = 1
							AND ug.group_id = a.group_id
							AND ug.user_id IN ($old_moderators)";
			$result = $db->sql_query($sql);

			$user_ids = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$user_ids[] = $row['user_id'];
			}
			$new_moderators = empty($user_ids) ? '' : implode(', ', $user_ids);

			// update users status
			$sql = "UPDATE " . USERS_TABLE . "
						SET user_level = " . USER . "
						WHERE user_id IN ($old_moderators)
							AND user_level NOT IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
			$db->sql_query($sql);

			if (!empty($new_moderators))
			{
				$sql = "UPDATE " . USERS_TABLE . "
							SET user_level = " . MOD . "
							WHERE user_id IN ($new_moderators)
								AND user_level NOT IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
				$db->sql_query($sql);
			}
		}

		// remove auth for the old forum
		$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " WHERE forum_id = $old_id";
		$db->sql_query($sql);

		// prune table
		$sql = "DELETE FROM " . PRUNE_TABLE . " WHERE forum_id = $old_id";
		$db->sql_query($sql);

		// polls
		$sql = "SELECT v.vote_id FROM " . VOTE_DESC_TABLE . " v, " . TOPICS_TABLE . " t
					WHERE t.forum_id = $old_id
						AND v.topic_id = t.topic_id";
		$result = $db->sql_query($sql);

		$vote_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$vote_ids[] = $row['vote_id'];
		}
		$s_vote_ids = empty($vote_ids) ? '' : implode(', ', $vote_ids);
		if (!empty($s_vote_ids))
		{
			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " WHERE vote_id IN ($s_vote_ids)";
			$result = $db->sql_query($sql);

			$sql = "DELETE FROM " . VOTE_USERS_TABLE . " WHERE vote_id IN ($s_vote_ids)";
			$result = $db->sql_query($sql);

			$sql = "DELETE FROM " . VOTE_DESC_TABLE . " WHERE vote_id IN ($s_vote_ids)";
			$result = $db->sql_query($sql);
		}

		// topics
		prune($old_id, 0, true); // Delete everything from forum
	}

	// Delete rules only for forums?
	//if ($old_type == POST_FORUM_URL)
	if (true)
	{
		$sql = "DELETE FROM " . FORUMS_RULES_TABLE . " WHERE forum_id = $old_id";
		$db->sql_query($sql);
	}

	// delete the old one
	$sql = "DELETE FROM " . FORUMS_TABLE . " WHERE forum_id = $old_id";
	$db->sql_query($sql);
}

function reorder_tree()
{
	global $tree, $db;

	// Make sure forums cache is empty...
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	empty_cache_folders(TOPICS_CACHE_FOLDER);
	// Read the tree
	read_tree(true);

	// Update with new order
	$order = 0;
	for ($i = 0; $i < sizeof($tree['data']); $i++)
	{
		if (!empty($tree['id'][$i]))
		{
			$order += 10;
			$sql = "UPDATE " . FORUMS_TABLE . "
						SET forum_order = " . $order . "
						WHERE forum_id = " . intval($tree['id'][$i]);
			$db->sql_query($sql);
		}
	}

	// Make sure forums cache is empty again...
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	empty_cache_folders(TOPICS_CACHE_FOLDER);
	// Re-cache the tree
	cache_tree(true);
	board_stats();
}

// admin_forums_extend.php - END

/**
* Move forum position by $steps up/down
* Ported from phpBB 3
*/
// Usage
/*
$move_forum_name = $this->move_forum_by($row, $action, 1);
add_log('admin', 'LOG_FORUM_' . strtoupper($action), $row['forum_name'], $move_forum_name);
$cache->destroy('sql', FORUMS_TABLE);
*/

function move_forum_by($forum_row, $action = 'move_up', $steps = 1)
{
	global $db;

	/**
	* Fetch all the siblings between the module's current spot
	* and where we want to move it to. If there are less than $steps
	* siblings between the current spot and the target then the
	* module will move as far as possible
	*/
	$sql = 'SELECT forum_id, forum_name, left_id, right_id
		FROM ' . FORUMS_TABLE . "
		WHERE parent_id = {$forum_row['parent_id']}
			AND " . (($action == 'move_up') ? "right_id < {$forum_row['right_id']} ORDER BY right_id DESC" : "left_id > {$forum_row['left_id']} ORDER BY left_id ASC");
	$result = $db->sql_query_limit($sql, $steps);

	$target = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$target = $row;
	}
	$db->sql_freeresult($result);

	if (!sizeof($target))
	{
		// The forum is already on top or bottom
		return false;
	}

	/**
	* $left_id and $right_id define the scope of the nodes that are affected by the move.
	* $diff_up and $diff_down are the values to substract or add to each node's left_id
	* and right_id in order to move them up or down.
	* $move_up_left and $move_up_right define the scope of the nodes that are moving
	* up. Other nodes in the scope of ($left_id, $right_id) are considered to move down.
	*/
	if ($action == 'move_up')
	{
		$left_id = $target['left_id'];
		$right_id = $forum_row['right_id'];

		$diff_up = $forum_row['left_id'] - $target['left_id'];
		$diff_down = $forum_row['right_id'] + 1 - $forum_row['left_id'];

		$move_up_left = $forum_row['left_id'];
		$move_up_right = $forum_row['right_id'];
	}
	else
	{
		$left_id = $forum_row['left_id'];
		$right_id = $target['right_id'];

		$diff_up = $forum_row['right_id'] + 1 - $forum_row['left_id'];
		$diff_down = $target['right_id'] - $forum_row['right_id'];

		$move_up_left = $forum_row['right_id'] + 1;
		$move_up_right = $target['right_id'];
	}

	// Now do the dirty job
	$sql = 'UPDATE ' . FORUMS_TABLE . "
		SET left_id = left_id + CASE
			WHEN left_id BETWEEN {$move_up_left} AND {$move_up_right} THEN -{$diff_up}
			ELSE {$diff_down}
		END,
		right_id = right_id + CASE
			WHEN right_id BETWEEN {$move_up_left} AND {$move_up_right} THEN -{$diff_up}
			ELSE {$diff_down}
		END,
		forum_parents = ''
		WHERE
			left_id BETWEEN {$left_id} AND {$right_id}
			AND right_id BETWEEN {$left_id} AND {$right_id}";
	$db->sql_query($sql);

	return $target['forum_name'];
}

/*
* Build icons select box
*/
//build_icons_select_box('../', 'images/forums/', 'icon', 'icon_image_sel', $icon, false, false, ' onchange="update_icon(this.options[selectedIndex].value);"')
function build_icons_select_box($icons_path_prefix, $icons_path, $input_name, $select_name, $default, $options_array, $options_langs_array, $select_js = '')
{
	global $lang;

	$select_box = '';
	$icons_array = array();
	$options_array = array();
	$options_langs_array = array();
	$options_array[] = $icons_path_prefix . 'images/spacer.gif';
	$options_langs_array[] = $lang['No_Icon_Image'];
	if (!empty($default))
	{
		$options_array[] = $default;
		$options_langs_array[] = $default;
	}
	if (is_dir($icons_path_prefix . $icons_path))
	{
		$dir = opendir($icons_path_prefix . $icons_path);
		while($file = readdir($dir))
		{
			if ((strpos($file, '.gif')) || (strpos($file, '.png')) || (strpos($file, '.jpg')))
			{
				$icons_array[] = $icons_path . $file;
			}
		}
		closedir($dir);
		sort($icons_array);
		$options_array = array_merge($options_array, $icons_array);
		$options_langs_array = array_merge($options_langs_array, $icons_array);

		$select_js = (!empty($select_js) ? $select_js : '');
		$select_box = '<select name="' . $select_name . '"' . $select_js . '>';
		for($j = 0; $j < sizeof($options_array); $j++)
		{
			$selected = ($options_array[$j] == $default) ? ' selected="selected"' : '';
			$select_box .= '<option value="' . $options_array[$j] . '"' . $selected . '>' . $options_langs_array[$j] . '</option>';
		}
		$select_box .= '</select>';

		$icon_img_sp = (!empty($default) ? ($icons_path_prefix . $default) : ($icons_path_prefix . 'images/spacer.gif'));
		$select_box .= '&nbsp;&nbsp;<img name="icon_image" src="' . $icon_img_sp . '" alt="" style="vertical-align: middle;" /><br /><br />';
	}
	$icon_img_path = (!empty($default) ? $default : '');
	$select_box .= '<input class="post" type="text" name="' . $input_name . '" size="40" maxlength="255" value="' . $icon_img_path . '" /><br />';

	return $select_box;
}

/*
* Rebuild forums and topics posters and colors
*/
function rebuild_forums_topics_posters($db_maintenance = true)
{
	global $db;

	$sql = "UPDATE " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
					SET f.forum_last_topic_id = p.topic_id, f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
					WHERE f.forum_last_post_id = p.post_id
						AND t.topic_id = p.topic_id
						AND p.poster_id = u.user_id";
	if ($db_maintenance)
	{
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't rebuild forums table!", __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$result = $db->sql_query($sql);
	}

	$sql = "UPDATE " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
					SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
					WHERE t.topic_first_post_id = p.post_id
						AND p.poster_id = u.user_id
						AND t.topic_last_post_id = p2.post_id
						AND p2.poster_id = u2.user_id";
	if ($db_maintenance)
	{
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't rebuild topics table!", __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$result = $db->sql_query($sql);
	}

	return true;
}

/**
* Update clean forum name for all forums
*/
function update_all_clean_forum_names()
{
	global $db, $lang;

	$sql = "SELECT * FROM " . FORUMS_TABLE . " ORDER BY forum_order, forum_id";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		if (empty($row['forum_name_clean']))
		{
			$row['forum_name_clean'] = substr(ip_clean_string($row['forum_name'], $lang['ENCODING']), 0, 254);
			update_clean_forum_name($row['forum_id'], $row['forum_name_clean']);
		}
	}
	$db->sql_freeresult($result);

	return true;
}

/**
* Update clean forum name
*/
function update_clean_forum_name($forum_id = 0, $forum_name = '')
{
	global $db;

	$sql = "UPDATE " . FORUMS_TABLE . " SET forum_name_clean = " . $db->sql_validate_value($forum_name) . " WHERE forum_id = " . $forum_id;
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_ftitle_clean = " . $db->sql_validate_value($forum_name) . " WHERE forum_id = " . $forum_id;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Clear clean forum name
*/
function clear_clean_forum_name($forum_id = 0)
{
	global $db;

	$sql_where = '';
	if (!empty($forum_id))
	{
		$sql_where = " WHERE forum_id = " . $forum_id;
	}
	$sql = "UPDATE " . FORUMS_TABLE . " SET forum_name_clean = ''" . $sql_where;
	$result = $db->sql_query($sql);
	return true;
}

?>