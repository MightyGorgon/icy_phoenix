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
* Get all users in a group
*/
function get_users_in_group($group_id)
{
	global $db;

	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_email, g.group_id, g.group_name
		FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
		WHERE ug.group_id = " . $group_id . "
			AND g.group_single_user = 0
			AND ug.user_pending = 0
			AND u.user_id = ug.user_id
			AND u.user_active = 1
			AND g.group_id = ug.group_id";
	$result = $db->sql_query($sql);
	$users_array = array();
	$users_array = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	return $users_array;
}

/**
* Count all users in a group
*/
function count_users_in_group($group_id)
{
	global $db;

	$sql = "SELECT SUM(user_pending = 0) as members, SUM(user_pending = 1) as pending
		FROM " . USER_GROUP_TABLE . "
		WHERE group_id = '" . $group_id . "'";
	$result = $db->sql_query($sql);
	$counting_list = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return $counting_list;
}

/**
* Count all active users
*/
function count_active_users()
{
	global $db;

	$sql = "SELECT SUM(user_active = 1) as active_members
		FROM " . USERS_TABLE;
	$result = $db->sql_query($sql);
	$counting_list = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return $counting_list;
}

/**
* Update all users colors and ranks.
*
* @param => group_id
* @return => true on success
*/
function update_all_users_colors_ranks($group_id)
{
	global $db, $config;
	$group_color = get_group_color($group_id);
	$group_rank = get_group_rank($group_id);
	$sql = "SELECT user_id
		FROM " . USER_GROUP_TABLE . "
		WHERE group_id = '" . $group_id . "'
			AND user_pending = 0";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$sql_set = '';
		if ($group_color)
		{
			$sql_set .= ', user_color = \'' . $group_color . '\'';
		}
		if ($group_rank > 0)
		{
			$sql_set .= ', user_rank = \'' . $group_rank . '\'';
		}
		$sql_users = "UPDATE " . USERS_TABLE . "
			SET group_id = '" . $group_id . "'" . $sql_set . "
			WHERE user_id = '" . $row['user_id'] . "'";
		$db->sql_query($sql_users);

		clear_user_color_cache($row['user_id']);
	}
	$db->sql_freeresult($result);

	return true;
}

/**
* Update user color and group.
*
* @param => user_id
* @param => user_color
* @param => group_id
* @return => true on success
*/
function update_user_color($user_id, $user_color, $group_id = false, $force_color = false, $force_group_color = false, $simple_mode = false)
{
	global $db, $config;

	$sql = "SELECT u.user_color, u.group_id
					FROM " . USERS_TABLE . " as u
					WHERE u.user_id = " . $user_id . "
					LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$current_user_color = $row['user_color'];
	$current_group_id = $row['group_id'];
	$db->sql_freeresult($result);

	$user_color_sql = '';
	$user_color = check_valid_color($user_color);
	$user_color = ($user_color != false) ? $user_color : $config['active_users_color'];

	if ($force_color || empty($current_user_color) || empty($current_group_id) || ($current_user_color == $config['active_users_color']))
	{
		$user_color_sql .= (empty($user_color_sql) ? '' : ', ') . ("user_color = '" . $user_color . "'");
		if ($simple_mode && !empty($group_id) && intval($group_id))
		{
			$user_color_sql .= (empty($user_color_sql) ? '' : ', ') . ("group_id = " . intval($group_id));
		}
	}

	if (!$simple_mode)
	{
		// 0 is different from false...
		if ($group_id === 0)
		{
			$user_color_sql .= (($user_color_sql == '') ? '' : ', ') . ("group_id = '0'");
		}
		elseif ($force_group_color || (($current_group_id == '0') && ($group_id !== false)))
		{
			$new_group_color = get_group_color($group_id);
			$user_color = ($new_group_color != false) ? $new_group_color : $user_color;
			$user_color_sql .= ($new_group_color != false) ? ((($user_color_sql == '') ? '' : ', ') . ("group_id = '" . $group_id . "'")) : '';
		}
	}

	if (!empty($user_color_sql))
	{
		$sql_users = "UPDATE " . USERS_TABLE . "
			SET " . $user_color_sql . "
			WHERE user_id = " . $user_id;
		$db->sql_query($sql_users);

		clear_user_color_cache($user_id);
	}
	return true;
}

/**
* Update users posts colors
*
* @param => user_id_ary
* @param => color
* @return => true on success
*/
function update_users_posts_details($user_id_ary, $color)
{
	global $db;

	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_first_poster_color = '" . $db->sql_escape($color) . "' WHERE " . $db->sql_in_set('topic_first_poster_id', $user_id_ary);
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_last_poster_color = '" . $db->sql_escape($color) . "' WHERE " . $db->sql_in_set('topic_last_poster_id', $user_id_ary);
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . FORUMS_TABLE . " SET forum_last_poster_color = '" . $db->sql_escape($color) . "' WHERE " . $db->sql_in_set('forum_last_poster_id', $user_id_ary);
	$result = $db->sql_query($sql);

	return true;
}

/**
* Update user posts details
*
* @param => user_id
* @return => true on success
*/
function update_user_posts_details($user_id, $color = '', $username = '', $update_color = false, $update_username = false)
{
	global $db;

	if ($update_color || $update_username)
	{
		$sql = user_color_sql($user_id);
		$result = $db->sql_query($sql);
		$sql_row = array();
		$row = array();
		while ($sql_row = $db->sql_fetchrow($result))
		{
			$row = $sql_row;
		}
		$db->sql_freeresult($result);
		$username = ($update_username || empty($username)) ? $row['username'] : $username;
		$color = $update_color ? $row['user_color'] : $color;

		$sql_topic_first_poster_set = "topic_first_poster_name = '" . $db->sql_escape($username) . "', ";
		$sql_topic_last_poster_set = "topic_last_poster_name = '" . $db->sql_escape($username) . "', ";
		$sql_forum_last_poster_set = "forum_last_poster_name = '" . $db->sql_escape($username) . "', ";
	}

	$sql = "UPDATE " . TOPICS_TABLE . " SET " . $sql_topic_first_poster_set . "topic_first_poster_color = '" . $db->sql_escape($color) . "' WHERE topic_first_poster_id = " . $user_id;
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . TOPICS_TABLE . " SET " . $sql_topic_last_poster_set . "topic_last_poster_color = '" . $db->sql_escape($color) . "' WHERE topic_last_poster_id = " . $user_id;
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . FORUMS_TABLE . " SET " . $sql_forum_last_poster_set . "forum_last_poster_color = '" . $db->sql_escape($color) . "' WHERE forum_last_poster_id = " . $user_id;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Update user rank simple
*
* @param => user_id
* @param => user_rank
* @param => force_rank
* @return => true on success
*/
function update_user_rank_simple($user_id, $user_rank, $force_rank = false)
{
	global $db, $config;

	$sql = "SELECT u.user_rank
					FROM " . USERS_TABLE . " as u
					WHERE u.user_id = " . $user_id . "
					LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$current_user_rank = $row['user_rank'];
	$db->sql_freeresult($result);

	$user_rank_sql = '';

	if ($force_rank || !empty($user_rank) || empty($current_user_rank))
	{
		$user_rank_sql .= (empty($user_rank_sql) ? '' : ', ') . ("user_rank = " . $user_rank);
	}

	if (!empty($user_rank_sql))
	{
		$sql_users = "UPDATE " . USERS_TABLE . "
			SET " . $user_rank_sql . "
			WHERE user_id = " . $user_id;
		$db->sql_query($sql_users);
		return true;
	}

	return false;
}

/**
* Creates a list with groups name and a link to the group page.
*
* @param => none
* @return => array
*/
function build_groups_list_array()
{
	global $db, $cache, $config;

	$groups_data = get_groups_data(false, true, array());
	$groups_list = array();
	foreach ($groups_data as $group_data)
	{
		if (!empty($group_data['group_legend']))
		{
			$tmp_group_color = check_valid_color($group_data['group_color']);
			$groups_list[] = array(
				'group_id' => $group_data['group_id'],
				'group_url' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_data['group_id']),
				'group_color' => $tmp_group_color,
				'group_color_style' => ($tmp_group_color ? ' style="color:' . $group_data['group_color'] . ';font-weight:bold;"' : ' style="font-weight:bold;"')
			);
		}
	}

	return $groups_list;
}

/**
* Creates a list with groups name and a link to the group page.
*
* @param => none
* @return => template var
*/
function build_groups_list_template()
{
	global $db, $cache, $config, $template, $lang;

	$groups_data = get_groups_data(false, false, array());
	$groups_list = '';
	foreach ($groups_data as $group_data)
	{
		if (!empty($group_data['group_legend']))
		{
			$tmp_group_color = check_valid_color($group_data['group_color']);
			$groups_list .= '&nbsp;<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_data['group_id']) . '" style="font-weight: bold; text-decoration: none;' . ($tmp_group_color ? ('color: ' . $tmp_group_color . ';') : '') . '">' . $group_data['group_name'] . '</a>,';
		}
	}

	if (!empty($config['active_users_legend']))
	{
		$tmp_group_color = check_valid_color($config['active_users_color']);
		$groups_list .= '&nbsp;<a href="' . append_sid(CMS_PAGE_MEMBERLIST) . '" style="font-weight: bold; text-decoration: none;' . ($tmp_group_color ? ('color: ' . $tmp_group_color . ';') : '') . '">' . $lang['Active_Users_Group'] . '</a>,';
	}

	if (!empty($config['bots_legend']))
	{
		$tmp_group_color = check_valid_color($config['bots_color']);
		$groups_list .= '&nbsp;<span style="font-weight: bold; text-decoration: none;' . ($tmp_group_color ? ('color: ' . $tmp_group_color . ';') : '') . '">' . $lang['Bots_Group'] . '</span>,';
	}

	if (!empty($groups_list))
	{
		$groups_list = substr($groups_list, 0, strlen($groups_list) - 1);
	}

	$template->assign_var('GROUPS_LIST_LEGEND', $groups_list);
}

/**
* Creates a list with all the groups a member subscribed.
*
* @param => user_id
* @return => array
*/
function build_groups_user($user_id, $show_hidden = true)
{
	global $db, $cache, $config, $lang;

	$groups_data_user = get_groups_data_user($user_id, true, true, array());
	$groups_list = array();
	foreach ($groups_data_user as $group_data)
	{
		if (empty($group_data['user_pending']))
		{
			$show_this = true;
			if (empty($show_hidden) && ($group_data['group_type'] == GROUP_HIDDEN))
			{
				$show_this = false;
			}

			if (!empty($show_this))
			{
				$tmp_group_color = check_valid_color($group_data['group_color']);
				$groups_list[] = array(
					'group_id' => $group_data['group_id'],
					'group_name' => $group_data['group_name'],
					'group_url' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_data['group_id']),
					'group_color' => $tmp_group_color,
					'group_color_style' => ($tmp_group_color ? ' style="color:' . $group_data['group_color'] . ';font-weight:bold;"' : ' style="font-weight:bold;"')
				);
			}
		}
	}

	return $groups_list;
}

/**
* Get group leaders
*
* @param => group_id
* @return => array
*/
function get_group_leaders($group_id)
{
	global $db, $cache, $config;

	$sql = "SELECT u.*
					FROM " . USER_GROUP_TABLE . " as ug, " . USERS_TABLE . " as u
					WHERE ug.group_id = " . (int) $group_id . "
						AND ug.group_leader = 1
						AND u.user_id = ug.user_id
					ORDER BY u.user_id ASC";
	$result = $db->sql_query($sql);
	$group_leaders_sql = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	// Better refactor the array to make simplify parsing...
	$group_leaders = array();
	if (!empty($group_leaders_sql))
	{
		foreach ($group_leaders_sql as $group_leader)
		{
			$group_leaders[$group_leader['user_id']] = $group_leader;
		}
	}

	return $group_leaders;
}

/**
* Get groups for which a user is leader
*
* @param => user_id
* @return => array
*/
function get_user_leading_groups($user_id)
{
	global $db, $cache, $config;

	$sql = "SELECT g.*
					FROM " . USER_GROUP_TABLE . " as ug, " . GROUPS_TABLE . " as g
					WHERE ug.user_id = " . (int) $user_id . "
						AND g.group_single_user = 0
						AND ug.group_leader = 1
						AND g.group_id = ug.group_id
					ORDER BY g.group_id ASC";
	$result = $db->sql_query($sql);
	$groups_leaded_sql = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	// Better refactor the array to make simplify parsing...
	$user_leading = array();
	if (!empty($groups_leaded_sql))
	{
		foreach ($groups_leaded_sql as $single_group)
		{
			$user_leading[$single_group['group_id']] = $single_group;
		}
	}

	return $user_leading;
}

/**
* Query the group details.
*
* @param => group_id
* @return => string
*/
function get_group_details($group_id)
{
	global $db, $cache, $config;

	$sql = "SELECT g.*
					FROM " . GROUPS_TABLE . " as g
					WHERE g.group_id = '" . $group_id . "'
					LIMIT 1";
	$result = $db->sql_query($sql);
	$group_details = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return $group_details;
}

/**
* Query the group color.
*
* @param => group_id
* @return => string
*/
function get_group_color($group_id)
{
	global $db, $cache, $config;

	$sql = "SELECT g.group_color
					FROM " . GROUPS_TABLE . " as g
					WHERE g.group_id = '" . $group_id . "'
					LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$group_color = $row['group_color'];
	$db->sql_freeresult($result);

	return $group_color;
}

/**
* Query the group rank.
*
* @param => group_id
* @return => string
*/
function get_group_rank($group_id)
{
	global $db;

	$sql = "SELECT g.group_rank
					FROM " . GROUPS_TABLE . " as g
					WHERE g.group_id = '" . $group_id . "'
					LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$group_rank = $row['group_color'];
	$db->sql_freeresult($result);

	return $group_rank;
}

/**
* Query the user color.
*
* @param => user_id
* @return => string
*/
function get_user_color($user_id)
{
	global $db;

	$sql = "SELECT u.user_color
					FROM " . USERS_TABLE . " as u
					WHERE u.user_id = " . $user_id . "
					LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$user_color = $row['user_color'];
	$db->sql_freeresult($result);

	return $user_color;
}

/**
* Query the user level.
*
* @param => user_id
* @return => string
*/
function get_user_level($user_id)
{
	global $db;

	$sql = "SELECT u.user_level
					FROM " . USERS_TABLE . " as u
					WHERE u.user_id = " . $user_id . "
					LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$user_level = $row['user_level'];
	$db->sql_freeresult($result);

	return $user_level;
}

/**
* Reset all auths but auth_mod
*
* @param => user_id
* @return => boolean
*/
function reset_all_auth($user_id)
{
	// Remember that AUTH_ACCESS_TABLE always refer to group_id field even if we are changing user permissions!
	global $db, $forum_auth_fields;

	$sql_forum_auth = '';
	for ($i = 0; $i < sizeof($forum_auth_fields); $i++)
	{
		$sql_forum_auth .= (empty($sql_forum_auth) ? '' : ', ') . $forum_auth_fields[$i] . ' = 0';
	}
	$sql = "UPDATE " . AUTH_ACCESS_TABLE . "
		SET " . $sql_forum_auth . "
		WHERE group_id = " . $user_id;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Remove all auth for group/user from auth table.
*
* @param => group_id (can be a group id or a user id)
* @not_mod => boolean (if set to true it will remove only auth settings where auth_mod = 0)
* @return => boolean
*/
function delete_all_auth($group_id, $not_mod = false)
{
	global $db;

	$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
		WHERE group_id = " . $group_id .
		($not_mod ? " AND auth_mod = 0" : "") ;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Remove all auth for not mod forums and set all other auths to 0 because the user is now admin.
* This function will not reset the 'auth_mod' field because you may want to show the user as admin in forums list
*
* @param => user_id
* @return => boolean
*/
function reset_auth_for_admins($user_id)
{
	$auth_delete = delete_all_auth($user_id, true);
	$auth_reset = reset_all_auth($user_id);
	return true;
}

/**
* Query the user color.
*
* @param => group_id
* @param => move: direction
* @return => string
*/
function change_legend_order($group_id, $move)
{
	global $db, $lang;
	$move = ($move == '1') ? '1' : '0';
	$sql = "SELECT group_id, group_name, group_legend_order
		FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> " . TRUE . "
		ORDER BY group_legend_order ASC, group_name ASC";
	$result = $db->sql_query($sql);

	$item_order = 0;
	$weight_assigned = 0;
	$last_g_id = 0;
	$to_change_g_id = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$item_order++;

		if ($row['group_id'] == $group_id)
		{
			$weight_assigned = $item_order;
			if (($move == '0') && ($item_order > 1))
			{
				$to_change_g_id = $last_g_id;
			}
		}

		if (($weight_assigned == ($item_order - 1)) && ($move == '1') && ($item_order > 1))
		{
			$to_change_g_id = $row['group_id'];
		}

		$sql_alt = "UPDATE " . GROUPS_TABLE . " SET group_legend_order = '" . $item_order . "' WHERE group_id = '" . $row['group_id'] . "'";
		$result_alt = $db->sql_query($sql_alt);
		$last_g_id = $row['group_id'];
	}
	if ($to_change_g_id != 0)
	{
		$item_order = ($move == '1') ? ($weight_assigned + 1) : ($weight_assigned - 1);
		$sql = "UPDATE " . GROUPS_TABLE . " SET group_legend_order = '" . $item_order . "' WHERE group_id = '" . $group_id . "'";
		$result = $db->sql_query($sql);

		//$item_order = ($move == '1') ? ($weight_assigned - 1) : ($weight_assigned + 1);
		$item_order = $weight_assigned;
		$sql = "UPDATE " . GROUPS_TABLE . " SET group_legend_order = '" . $item_order . "' WHERE group_id = '" . $to_change_g_id . "'";
		$result = $db->sql_query($sql);
	}
}

/**
* Adjust legend order.
*/
function adjust_legend_order()
{
	global $db, $cache, $config, $lang;

	$groups_data = get_groups_data(true, false, array());
	$item_order = 0;
	foreach ($groups_data as $group_data)
	{
		$item_order++;
		$sql_alt = "UPDATE " . GROUPS_TABLE . " SET group_legend_order = '" . $item_order . "' WHERE group_id = '" . $group_data['group_id'] . "'";
		$result_alt = $db->sql_query($sql_alt);
	}
}

/**
* Add user(s) to group
*
* @return mixed false if no errors occurred, else the user lang string for the relevant error, for example 'NO_USER'
*/
function group_user_add($group_id, $user_id, $clear_cache = false)
{
	// 2 => User already member
	// 1 => User added
	// 0 => User not added
	global $db, $lang;

	$this_userdata = get_userdata($user_id);

	$sql = "SELECT ug.user_id, g.group_type, g.group_rank, g.group_color, g.group_count, g.group_count_max
		FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE g.group_id = '" . $group_id . "'
			AND ug.group_id = g.group_id";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$group_rank = $row['group_rank'];
		$group_color = $row['group_color'];
		do
		{
			if ($user_id == $row['user_id'])
			{
				return 2;
			}
		}
		while ($row = $db->sql_fetchrow($result));
	}
	else
	{
		return 0;
	}

	$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending) VALUES (" . $group_id . ", " . $user_id . ", 0)";
	$result = $db->sql_query($sql);

	update_user_color($this_userdata['user_id'], $group_color, $group_id, false, false, true);
	update_user_rank_simple($this_userdata['user_id'], $group_rank);
	update_user_posts_details($this_userdata['user_id'], $group_color, '', false, false);

	if ($clear_cache)
	{
		$db->clear_cache();
	}
	return 1;
}

?>