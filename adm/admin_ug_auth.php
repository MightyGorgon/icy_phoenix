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

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['113_Permissions_Users'] = $filename . '?mode=user';
	$module['1620_Groups']['130_Permissions_Group'] = $filename . '?mode=group';
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('./pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// FUNCTIONS - BEGIN
if (!function_exists('check_auth'))
{
	function check_auth($type, $key, $u_access, $is_admin)
	{
		$auth_user = 0;

		if(count($u_access))
		{
			for($j = 0; $j < count($u_access); $j++)
			{
				$result = 0;
				switch($type)
				{
					case AUTH_ACL:
						$result = $u_access[$j][$key];

					case AUTH_MOD:
						$result = $result || $u_access[$j]['auth_mod'];

					case AUTH_ADMIN:
						$result = $result || $is_admin;
						break;
				}

				$auth_user = $auth_user || $result;
			}
		}
		else
		{
			$auth_user = $is_admin;
		}

		return $auth_user;
	}
}
// FUNCTIONS - END

$params = array('mode' => 'mode', 'user_id' => POST_USERS_URL, 'group_id' => POST_GROUPS_URL, 'adv' => 'adv');

while(list($var, $param) = @each($params))
{
	if (!empty($_POST[$param]) || !empty($_GET[$param]))
	{
		$$var = (!empty($_POST[$param])) ? $_POST[$param] : $_GET[$param];
	}
	else
	{
		$$var = '';
	}
}

$user_id = intval($user_id);
$group_id = intval($group_id);
// Disallow other admins to delete or edit the first admin - BEGIN
$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
if (($user_id == $founder_id) && ($userdata['user_id'] != $founder_id))
{
	$edituser = $userdata['username'];
	$editok = $userdata['user_id'];
	$sql = "INSERT INTO " . ADMINEDIT_TABLE . " (edituser, editok) VALUES ('" . str_replace("\'", "''", $edituser) . "','" . $editok . "')";
	if(!($result = $db->sql_query($sql)))
	{
	message_die(GENERAL_ERROR, 'Could not obtain adminedit information for this user', '', __LINE__, __FILE__, $sql);
	}
		message_die(GENERAL_MESSAGE, $lang['L_ADMINEDITMSG']);
}
// Disallow other admins to delete or edit the first admin - END

$adv = intval($adv);
$mode = htmlspecialchars($mode);

//Start Quick Administrator User Options and Information MOD
if(isset($_POST['redirect']) || isset($_GET['redirect']))
{
	$redirect = (isset($_POST['redirect'])) ? $_POST['redirect'] : $_GET['redirect'];
	$redirect = htmlspecialchars($redirect);
}
else
{
	$redirect = '';
}
//End Quick Administrator User Options and Information MOD

//
// Start program - define vars
//
include(IP_ROOT_PATH . './includes/def_auth.' . PHP_EXT);

// build an indexed array on field names
@reset($field_names);
$forum_auth_fields = array();
while (list($auth_key, $auth_name) = @each($field_names))
{
	$forum_auth_fields[] = $auth_key;
}

if (isset($_POST['submit']) && ((($mode == 'user') && $user_id) || (($mode == 'group') && $group_id)))
{
	$user_level = '';
	if ($mode == 'user')
	{
		// Get group_id for this user_id
		$sql = "SELECT g.group_id, u.user_level
			FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
			WHERE u.user_id = $user_id
				AND ug.user_id = u.user_id
				AND g.group_id = ug.group_id
				AND g.group_single_user = " . TRUE;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not select info from user/user_group table', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		$group_id = $row['group_id'];
		$user_level = $row['user_level'];

		$db->sql_freeresult($result);
	}

	$l_auth_return = ($mode == 'user') ? $lang['Click_return_userauth'] : $lang['Click_return_groupsadmin'];
	$l_auth_url = ($mode == 'user') ? 'admin_ug_auth.' : 'admin_groups.';

	// Carry out requests
	// We are making an admin / jadmin
	if (($mode == 'user') && ((($_POST['userlevel'] == 'admin') && ($user_level != ADMIN)) || (($_POST['userlevel'] == 'jadmin') && ($user_level != JUNIOR_ADMIN))))
	{
		// Make user an admin (if already user)
		// The user already had or it has been set an admin level...
		$current_level = $user_level;
		$new_level = (($_POST['userlevel'] == 'admin') ? ADMIN : JUNIOR_ADMIN);
		$level_changed = false;
		if ($userdata['user_id'] != $user_id)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_level = '" . $new_level . "'
				WHERE user_id = " . $user_id;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
			}

			$auth_reset = reset_auth_for_admins($user_id);
		}

		$db->clear_cache();
		cache_tree(true);

		$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_sid($l_auth_url . PHP_EXT . '?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		// We are changing a user level from admin / jadmin to normal user
		if (($mode == 'user') && ($_POST['userlevel'] == 'user') && (($user_level == ADMIN) || ($user_level == JUNIOR_ADMIN)))
		{
			// Make admin a user (if already admin) ... ignore if you're trying to change yourself from an admin to user!
			if ($userdata['user_id'] != $user_id)
			{
				$auth_reset = reset_all_auth($user_id);

				// Update users level, reset to USER
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_level = " . USER . "
					WHERE user_id = " . $user_id;
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_sid($l_auth_url . PHP_EXT . '?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		}
		else
		{
			// We are not changing user level, so we may process user permissions...
			$change_mod_list = (isset($_POST['moderator'])) ? $_POST['moderator'] : false;

			if (empty($adv))
			{
				// OLD SQL
				/*
				$sql = "SELECT f.*
					FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
					WHERE f.cat_id = c.cat_id
					ORDER BY c.cat_order, f.forum_order ASC";
				*/
				$sql = "SELECT f.*
					FROM " . FORUMS_TABLE . " f, " . FORUMS_TABLE . " f2, " . CATEGORIES_TABLE . " c
					WHERE (f.cat_id = c.cat_id AND f.main_type = 'c')
						OR (f.cat_id = 0 AND f.main_type = 'c')
						OR (f.cat_id = f2.forum_id AND f.main_type = 'f')
					GROUP BY f.forum_id
					ORDER BY c.cat_order, f.forum_order ASC";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't obtain forum information", "", __LINE__, __FILE__, $sql);
				}

				$forum_access = $forum_auth_level_fields = array();
				while($row = $db->sql_fetchrow($result))
				{
					$forum_access[] = $row;
				}
				$db->sql_freeresult($result);

				for($i = 0; $i < count($forum_access); $i++)
				{
					$forum_id = $forum_access[$i]['forum_id'];
					for($j = 0; $j < count($forum_auth_fields); $j++)
					{
						$forum_auth_level_fields[$forum_id][$forum_auth_fields[$j]] = $forum_access[$i][$forum_auth_fields[$j]] == AUTH_ACL;
					}
				}

				while(list($forum_id, $value) = @each($_POST['private']))
				{
					while(list($auth_field, $exists) = @each($forum_auth_level_fields[$forum_id]))
					{
						if ($exists)
						{
							$change_acl_list[$forum_id][$auth_field] = $value;
						}
					}
				}
			}
			else
			{
				$change_acl_list = array();
				$used_forums = array();
				for($j = 0; $j < count($forum_auth_fields); $j++)
				{
					$auth_field = $forum_auth_fields[$j];
					while(list($forum_id, $value) = @each($_POST['private_' . $auth_field]))
					{
						$change_acl_list[$forum_id][$auth_field] = $value;
					}

					if (!isset($used_forums[$forum_id]))
					{
						$sql = ($mode == 'user') ? ("SELECT aa.*, g.group_single_user FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND aa.forum_id = $forum_id AND g.group_single_user = 1") : ("SELECT * FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = $group_id AND forum_id = $forum_id");
						if (!($result = $db->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
						}
						if ($row = $db->sql_fetchrow($result))
						{
							for ($k = 0; $k < count($forum_auth_fields); $k++)
							{
								$change_acl_list[$forum_id][$forum_auth_fields[$k]] = $row[$forum_auth_fields[$k]];
							}
						}
						$used_forums[$forum_id] = 1;
					}
				}
			}

			// get all sorted by level
			$keys = array();
			$keys = get_auth_keys('Root', true);
			$forum_access = array();

			// extract forums
			$forum_access = array();
			for ($i = 0; $i < count($keys['id']); $i++)
			{
				if ($tree['type'][ $keys['idx'][$i] ] == POST_FORUM_URL)
				{
					$forum_access[] = $tree['data'][ $keys['idx'][$i] ];
				}
			}

			$sql = ($mode == 'user') ? "SELECT aa.* FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = " . TRUE : "SELECT * FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = $group_id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
			}

			$auth_access = array();
			while($row = $db->sql_fetchrow($result))
			{
				$auth_access[$row['forum_id']] = $row;
			}
			$db->sql_freeresult($result);

			$forum_auth_action = array();
			$update_acl_status = array();
			$update_mod_status = array();

			for($i = 0; $i < count($forum_access); $i++)
			{
				$forum_id = $forum_access[$i]['forum_id'];

				if (isset($change_mod_list[$forum_id]) && ((isset($auth_access[$forum_id]['auth_mod']) && ($change_mod_list[$forum_id] != $auth_access[$forum_id]['auth_mod'])) || (!isset($auth_access[$forum_id]['auth_mod']) && !empty($change_mod_list[$forum_id])))
				)
				{
					$update_mod_status[$forum_id] = $change_mod_list[$forum_id];

					if (!$update_mod_status[$forum_id])
					{
						$forum_auth_action[$forum_id] = 'delete';
					}
					else if (!isset($auth_access[$forum_id]['auth_mod']))
					{
						$forum_auth_action[$forum_id] = 'insert';
					}
					else
					{
						$forum_auth_action[$forum_id] = 'update';
					}
				}

				for($j = 0; $j < count($forum_auth_fields); $j++)
				{
					$auth_field = $forum_auth_fields[$j];

					if($forum_access[$i][$auth_field] == AUTH_ACL && isset($change_acl_list[$forum_id][$auth_field]))
					{
						if ((empty($auth_access[$forum_id]['auth_mod']) &&
							(isset($auth_access[$forum_id][$auth_field]) && $change_acl_list[$forum_id][$auth_field] != $auth_access[$forum_id][$auth_field]) ||
							(!isset($auth_access[$forum_id][$auth_field]) && !empty($change_acl_list[$forum_id][$auth_field]))) ||
							!empty($update_mod_status[$forum_id])
						)
						{
							$update_acl_status[$forum_id][$auth_field] = (!empty($update_mod_status[$forum_id])) ? 0 :  $change_acl_list[$forum_id][$auth_field];

							if (isset($auth_access[$forum_id][$auth_field]) && empty($update_acl_status[$forum_id][$auth_field]) && $forum_auth_action[$forum_id] != 'insert' && $forum_auth_action[$forum_id] != 'update')
							{
								$forum_auth_action[$forum_id] = 'delete';
							}
							elseif (!isset($auth_access[$forum_id][$auth_field]) && !($forum_auth_action[$forum_id] == 'delete' && empty($update_acl_status[$forum_id][$auth_field])))
							{
								$forum_auth_action[$forum_id] = 'insert';
							}
							elseif (isset($auth_access[$forum_id][$auth_field]) && !empty($update_acl_status[$forum_id][$auth_field]))
							{
								$forum_auth_action[$forum_id] = 'update';
							}
						}
						elseif ((empty($auth_access[$forum_id]['auth_mod']) &&
							(isset($auth_access[$forum_id][$auth_field]) && $change_acl_list[$forum_id][$auth_field] == $auth_access[$forum_id][$auth_field])) && $forum_auth_action[$forum_id] == 'delete')
						{
							$forum_auth_action[$forum_id] = 'update';
						}
					}
				}
			}

			// Checks complete, make updates to DB
			$delete_sql = '';
			while(list($forum_id, $action) = @each($forum_auth_action))
			{
				if ($action == 'delete')
				{
					$delete_sql .= (($delete_sql != '') ? ', ' : '') . $forum_id;
				}
				else
				{
					if ($action == 'insert')
					{
						$sql_field = '';
						$sql_value = '';
						while (list($auth_type, $value) = @each($update_acl_status[$forum_id]))
						{
							$sql_field .= (($sql_field != '') ? ', ' : '') . $auth_type;
							$sql_value .= (($sql_value != '') ? ', ' : '') . $value;
						}
						$sql_field .= (($sql_field != '') ? ', ' : '') . 'auth_mod';
						$sql_value .= (($sql_value != '') ? ', ' : '') . ((!isset($update_mod_status[$forum_id])) ? 0 : $update_mod_status[$forum_id]);

						$sql = "INSERT INTO " . AUTH_ACCESS_TABLE . " (forum_id, group_id, $sql_field)
							VALUES ($forum_id, $group_id, $sql_value)";
					}
					else
					{
						$sql_values = '';
						while (list($auth_type, $value) = @each($update_acl_status[$forum_id]))
						{
							$sql_values .= (($sql_values != '') ? ', ' : '') . $auth_type . ' = ' . $value;
						}
						$sql_values .= (($sql_values != '') ? ', ' : '') . 'auth_mod = ' . ((!isset($update_mod_status[$forum_id])) ? 0 : $update_mod_status[$forum_id]);

						$sql = "UPDATE " . AUTH_ACCESS_TABLE . "
							SET $sql_values
							WHERE group_id = $group_id
								AND forum_id = $forum_id";
					}
					if(!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, "Couldn't update private forum permissions", "", __LINE__, __FILE__, $sql);
					}
				}
			}

			if ($delete_sql != '')
			{
				$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
					WHERE group_id = $group_id
						AND forum_id IN ($delete_sql)";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't delete permission entries", "", __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_sid($l_auth_url . PHP_EXT . '?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		}

		// Update user level to mod for appropriate users
		$sql = "SELECT u.user_id
			FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
			WHERE ug.group_id = aa.group_id
				AND u.user_id = ug.user_id
				AND ug.user_pending = 0
				AND u.user_level NOT IN (" . MOD . ", " . JUNIOR_ADMIN . ", " . ADMIN . ")
			GROUP BY u.user_id
			HAVING SUM(aa.auth_mod) > 0";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
		}

		$set_mod = '';
		while($row = $db->sql_fetchrow($result))
		{
			$set_mod .= (($set_mod != '') ? ', ' : '') . $row['user_id'];
		}
		$db->sql_freeresult($result);

		// Update user level to user for appropriate users
		$sql = "SELECT u.user_id
			FROM ((" . USERS_TABLE . " u
			LEFT JOIN " . USER_GROUP_TABLE . " ug ON ug.user_id = u.user_id)
			LEFT JOIN " . AUTH_ACCESS_TABLE . " aa ON aa.group_id = ug.group_id)
			WHERE u.user_level NOT IN (" . USER . ", " . JUNIOR_ADMIN . ", " . ADMIN . ")
			GROUP BY u.user_id
			HAVING SUM(aa.auth_mod) = 0";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
		}

		$unset_mod = '';
		while($row = $db->sql_fetchrow($result))
		{
			$unset_mod .= (($unset_mod != '') ? ', ' : '') . $row['user_id'];
		}
		$db->sql_freeresult($result);

		if ($set_mod != '')
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_level = " . MOD . "
				WHERE user_id IN ($set_mod)";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql);
			}
		}

		$sql = "SELECT user_id FROM " . USER_GROUP_TABLE . " WHERE group_id = $group_id";
		$result = $db->sql_query($sql);

		$group_user = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$group_user[$row['user_id']] = $row['user_id'];
		}
		$db->sql_freeresult($result);

		$sql = "SELECT ug.user_id, COUNT(auth_mod) AS is_auth_mod
					FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
					WHERE ug.user_id IN (" . implode(', ', $group_user) . ")
						AND aa.group_id = ug.group_id
						AND aa.auth_mod = 1
					GROUP BY ug.user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain moderator status', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['is_auth_mod'])
			{
				unset($group_user[$row['user_id']]);
			}
		}
		$db->sql_freeresult($result);

		if (count($group_user))
		{
			$sql = "UPDATE " . USERS_TABLE . "
							SET user_level = " . USER . "
								WHERE user_id IN (" . implode(', ', $group_user) . ") AND user_level = " . MOD;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
			}
		}

		cache_tree(true);

		if ($unset_mod != '')
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_level = " . USER . "
				WHERE user_id IN ($unset_mod)";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql);
			}
		}
		$sql = "SELECT user_id FROM " . USER_GROUP_TABLE . " WHERE group_id = $group_id";
		$result = $db->sql_query($sql);

		$group_user = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$group_user[$row['user_id']] = $row['user_id'];
		}
		$db->sql_freeresult($result);

		$sql = "SELECT ug.user_id, COUNT(auth_mod) AS is_auth_mod
			FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id IN (" . implode(', ', $group_user) . ")
				AND aa.group_id = ug.group_id
				AND aa.auth_mod = 1
			GROUP BY ug.user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain moderator status', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['is_auth_mod'])
			{
				unset($group_user[$row['user_id']]);
			}
		}
		$db->sql_freeresult($result);

		if (count($group_user))
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_level = " . USER . "
				WHERE user_id IN (" . implode(', ', $group_user) . ") AND user_level = " . MOD;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
			}
		}

		$db->clear_cache();
		message_die(GENERAL_MESSAGE, $message);
	}
}

//Start Quick Administrator User Options and Information MOD
if($redirect != '')
{
	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userprofile'], '<a href="' . append_sid('../' . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT) . '">', '</a>');
}
//End Quick Administrator User Options and Information MOD
elseif (($mode == 'user' && (isset($_POST['username']) || $user_id)) || (($mode == 'group') && $group_id))
{
	if (isset($_POST['username']))
	{
		$this_userdata = get_userdata($_POST['username'], true);
		if (!is_array($this_userdata))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_user']);
		}
		$user_id = $this_userdata['user_id'];
	}

	// get all sorted by level
	$keys = array();
	$keys = get_auth_keys('Root', true);

	// get the maximum level
	$max_level = 0;
	for ($i = 0; $i < count($keys['id']); $i++)
	{
		if ($keys['real_level'][$i] > $max_level) $max_level = $keys['real_level'][$i];
	}

	// extract forums
	$forum_access = array();
	for ($i=0; $i < count($keys['id']); $i++)
	{
		if ($tree['type'][ $keys['idx'][$i] ] == POST_FORUM_URL)
		{
			$forum_access[] = $tree['data'][ $keys['idx'][$i] ];
		}
	}

	if(empty($adv))
	{
		for($i = 0; $i < count($forum_access); $i++)
		{
			$forum_id = $forum_access[$i]['forum_id'];

			$forum_auth_level[$forum_id] = AUTH_ALL;

			for($j = 0; $j < count($forum_auth_fields); $j++)
			{
				$forum_access[$i][$forum_auth_fields[$j]] . ' :: ';
				if ($forum_access[$i][$forum_auth_fields[$j]] == AUTH_ACL)
				{
					$forum_auth_level[$forum_id] = AUTH_ACL;
					$forum_auth_level_fields[$forum_id][] = $forum_auth_fields[$j];
				}
			}
		}
	}
	$sql = "SELECT count(*) AS total FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug WHERE ";
	$sql .= ($mode == 'user') ? "u.user_id = $user_id AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = $group_id AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't count user/group information", "", __LINE__, __FILE__, $sql);
	}
	$count_ug_info = $db->sql_fetchrow($result);
	$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	$pagination_url = 'admin_ug_auth.' . PHP_EXT . '?' . (($mode == 'user') ? POST_USERS_URL . '=' . $user_id : POST_GROUPS_URL . '=' . $group_id) . '&amp;mode=' . $mode . '&amp;adv=' . $adv;
	$pagination = generate_pagination($pagination_url, $count_ug_info['total'], $board_config['posts_per_page'], $start);

	$sql = "SELECT u.user_id, u.username, u.user_level, g.group_id, g.group_name, g.group_single_user, g.group_color, ug.user_pending FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug WHERE ";
	$sql .= ($mode == 'user') ? "u.user_id = $user_id AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = $group_id AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
	$sql .= " ORDER BY u.username, g.group_name LIMIT $start, " . $board_config['posts_per_page'];

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user/group information", "", __LINE__, __FILE__, $sql);
	}
	$ug_info = array();
	while($row = $db->sql_fetchrow($result))
	{
		$ug_info[] = $row;
	}
	$db->sql_freeresult($result);

	$sql = ($mode == 'user') ? "SELECT aa.*, g.group_single_user FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = 1" : "SELECT * FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = $group_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
	}

	$auth_access = array();
	$auth_access_count = array();
	while($row = $db->sql_fetchrow($result))
	{
		$auth_access[$row['forum_id']][] = $row;
		$auth_access_count[$row['forum_id']]++;
	}
	$db->sql_freeresult($result);

	$is_admin = ($mode == 'user') ? ((($ug_info[0]['user_level'] == ADMIN) && ($ug_info[0]['user_id'] != ANONYMOUS)) ? 1 : 0) : 0;
	$is_jadmin = ($mode == 'user') ? ((($ug_info[0]['user_level'] == JUNIOR_ADMIN) && ($ug_info[0]['user_id'] != ANONYMOUS)) ? 1 : 0) : 0;
	$is_admin_select = ((($ug_info[0]['user_level'] == JUNIOR_ADMIN) && ($ug_info[0]['user_id'] != ANONYMOUS)) ? JUNIOR_ADMIN : $is_admin);

	for($i = 0; $i < count($forum_access); $i++)
	{
		$forum_id = $forum_access[$i]['forum_id'];

		unset($prev_acl_setting);
		for($j = 0; $j < count($forum_auth_fields); $j++)
		{
			$key = $forum_auth_fields[$j];
			$value = $forum_access[$i][$key];

			switch($value)
			{
				case AUTH_ALL:
				case AUTH_REG:
					$auth_ug[$forum_id][$key] = 1;
					break;

				case AUTH_ACL:
					$auth_ug[$forum_id][$key] = (!empty($auth_access_count[$forum_id])) ? check_auth(AUTH_ACL, $key, $auth_access[$forum_id], $is_admin) : 0;
					$auth_field_acl[$forum_id][$key] = $auth_ug[$forum_id][$key];

					if (isset($prev_acl_setting))
					{
						if ($prev_acl_setting != $auth_ug[$forum_id][$key] && empty($adv))
						{
							$adv = 1;
						}
					}

					$prev_acl_setting = $auth_ug[$forum_id][$key];

					break;

				case AUTH_MOD:
					$auth_ug[$forum_id][$key] = (!empty($auth_access_count[$forum_id])) ? check_auth(AUTH_MOD, $key, $auth_access[$forum_id], $is_admin) : 0;
					break;

				case AUTH_ADMIN:
					$auth_ug[$forum_id][$key] = $is_admin;
					break;

				default:
					$auth_ug[$forum_id][$key] = 0;
					break;
			}
		}

		// Is user a moderator?
		$auth_ug[$forum_id]['auth_mod'] = (!empty($auth_access_count[$forum_id])) ? check_auth(AUTH_MOD, 'auth_mod', $auth_access[$forum_id], 0) : 0;
	}

	$s_column_span = 2 + $max_level; // Two columns always present
	if($adv)
	{
		$s_column_span = $s_column_span + count($forum_auth_fields) - 1;
	}

	// read the objects without the index forum (i=0)
	for ($i = 1; $i < count($keys['id']); $i++)
	{
		$CH_this = $keys['idx'][$i];
		$level = $keys['real_level'][$i];
		if ($tree['type'][$CH_this] == POST_CAT_URL)
		{
			$class_cat = 'cat';
			$template->assign_block_vars('row', array());
			$template->assign_block_vars('row.cathead', array(
				'CLASS_CAT' => $class_cat,
				'CAT_TITLE' => get_object_lang($tree['type'][$CH_this] . $tree['id'][$CH_this], 'name'),
				'INC_SPAN' => $max_level - $level+1,
				)
			);
			for ($k = 1; $k <= $level; $k++) $template->assign_block_vars('row.cathead.inc', array());
			if ($adv)
			{
				for ($j = 0; $j < count($forum_auth_fields); $j++)
				{
					$template->assign_block_vars('row.cathead.aclvalues', array());
				}
			}
			else
			{
				$template->assign_block_vars('row.cathead.aclvalues', array());
			}
		}

		if ($tree['type'][$CH_this] == POST_FORUM_URL)
		{
			$forum_id = $tree['data'][ $keys['idx'][$i] ]['forum_id'];
			$user_ary = $auth_ug[$forum_id];

			if (empty($adv))
			{
				if ($forum_auth_level[$forum_id] == AUTH_ACL)
				{
					$allowed = 1;

					for($j = 0; $j < count($forum_auth_level_fields[$forum_id]); $j++)
					{
						if (!$auth_ug[$forum_id][$forum_auth_level_fields[$forum_id][$j]])
						{
							$allowed = 0;
						}
					}

					$optionlist_acl = '<select id="private_id_' . $forum_id . '" name="private[' . $forum_id . ']">';

					if ($is_admin || $is_jadmin || $user_ary['auth_mod'])
					{
						$optionlist_acl .= '<option value="1">' . $lang['Allowed_Access'] . '</option>';
					}
					else if ($allowed)
					{
						$optionlist_acl .= '<option value="1" selected="selected">' . $lang['Allowed_Access'] . '</option><option value="0">'. $lang['Disallowed_Access'] . '</option>';
					}
					else
					{
						$optionlist_acl .= '<option value="1">' . $lang['Allowed_Access'] . '</option><option value="0" selected="selected">' . $lang['Disallowed_Access'] . '</option>';
					}

					$optionlist_acl .= '</select>';
				}
				else
				{
					$optionlist_acl = '&nbsp;';
				}
			}
			else
			{
				for($j = 0; $j < count($forum_access); $j++)
				{
					if ($forum_access[$j]['forum_id'] == $forum_id)
					{
						for($k = 0; $k < count($forum_auth_fields); $k++)
						{
							$field_name = $forum_auth_fields[$k];

							if($forum_access[$j][$field_name] == AUTH_ACL)
							{
								$optionlist_acl_adv[$forum_id][$k] = '<select id="private_id_' . $field_name . '_' . $forum_id . '" name="private_' . $field_name . '[' . $forum_id . ']">';

								if(isset($auth_field_acl[$forum_id][$field_name]) && !($is_admin || $user_ary['auth_mod']))
								{
									if(!$auth_field_acl[$forum_id][$field_name])
									{
										$optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
									}
									else
									{
										$optionlist_acl_adv[$forum_id][$k] .= '<option value="1" selected="selected">' . $lang['ON'] . '</option><option value="0">' . $lang['OFF'] . '</option>';
									}
								}
								else
								{
									if($is_admin || $user_ary['auth_mod'])
									{
										$optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option>';
									}
									else
									{
										$optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
									}
								}

								$optionlist_acl_adv[$forum_id][$k] .= '</select>';

							}
						}
					}
				}
			}

			$optionlist_mod = '<select id="moderator_id_' . $forum_id . '" name="moderator[' . $forum_id . ']">';
			$optionlist_mod .= ($user_ary['auth_mod']) ? '<option value="1" selected="selected">' . $lang['Is_Moderator'] . '</option><option value="0">' . $lang['Not_Moderator'] . '</option>' : '<option value="1">' . $lang['Is_Moderator'] . '</option><option value="0" selected="selected">' . $lang['Not_Moderator'] . '</option>';
			$optionlist_mod .= '</select>';

			$row_class = (!($i % 2)) ? 'row2' : 'row1';

			$template->assign_block_vars('row', array());
			$template->assign_block_vars('row.forums', array(
				'INC_SPAN' => $max_level - $level+1,
				'ROW_CLASS' => $row_class,
				'FORUM_NAME' => get_object_lang(POST_FORUM_URL . $tree['data'][ $keys['idx'][$i] ]['forum_id'], 'name'),
				'U_FORUM_AUTH' => append_sid('admin_forumauth.' . PHP_EXT . '?f=' . $tree['data'][ $keys['idx'][$i] ]['forum_id']),
				'S_MOD_SELECT' => $optionlist_mod
				)
			);

			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('row.forums.inc', array());
			}
			if(!$adv)
			{
				$template->assign_block_vars('row.forums.aclvalues', array(
					'S_ACL_SELECT' => $optionlist_acl
					)
				);
			}
			else
			{
				for($j = 0; $j < count($forum_auth_fields); $j++)
				{
					$template->assign_block_vars('row.forums.aclvalues', array(
						'S_ACL_SELECT' => $optionlist_acl_adv[$forum_id][$j]
						)
					);
				}
			}
		}
	}
	//	@reset($auth_user);

	if ($mode == 'user')
	{
		$t_username = $ug_info[0]['username'];
		//$s_user_type = ($is_admin) ? '<select name="userlevel"><option value="admin" selected="selected">' . $lang['Auth_Admin'] . '</option><option value="user">' . $lang['Auth_User'] . '</option></select>' : '<select name="userlevel"><option value="admin">' . $lang['Auth_Admin'] . '</option><option value="user" selected="selected">' . $lang['Auth_User'] . '</option></select>';
		$s_user_type = '<select name="userlevel">';
		$s_user_type .= '<option value="admin"' . (($is_admin_select == ADMIN) ? ' selected="selected"' : '') . '>' . $lang['Auth_Admin'] . '</option>';
		$s_user_type .= '<option value="jadmin"' . (($is_admin_select == JUNIOR_ADMIN) ? ' selected="selected"' : '') . '>' . $lang['Auth_Junior_Admin'] . '</option>';
		$s_user_type .= '<option value="user"' . (($is_admin_select == 0) ? ' selected="selected"' : '') . '>' . $lang['Auth_User'] . '</option>';
		$s_user_type .= '</select>';
	}
	else
	{
		$t_groupname = $ug_info[0]['group_name'];
	}

	$name = array();
	$id = array();
	$color = array();
	for($i = 0; $i < count($ug_info); $i++)
	{
		if((($mode == 'user') && !$ug_info[$i]['group_single_user']) || ($mode == 'group'))
		{
			$name[] = ($mode == 'user') ? $ug_info[$i]['group_name'] : $ug_info[$i]['username'];
			$id[] = ($mode == 'user') ? intval($ug_info[$i]['group_id']) : intval($ug_info[$i]['user_id']);
			$color[] = ($mode == 'user') ? (!empty($ug_info[$i]['group_color']) ? (' style="font-weight: bold; text-decoration: none; color: ' . $ug_info[$i]['group_color'] . ';"') : '') : (' ' . colorize_username(intval($ug_info[$i]['user_id']), '', '', '', false, true));
		}
	}

	$t_usergroup_list = '';
	$t_pending_list = '';
	if(count($name))
	{
		for($i = 0; $i < count($name); $i++)
		{
			$ug = ($mode == 'user') ? 'group&amp;' . POST_GROUPS_URL : 'user&amp;' . POST_USERS_URL;
			if (!$ug_info[$i]['user_pending'])
			{
				$t_usergroup_list .= (($t_usergroup_list != '') ? ', ' : '') . '<a href="' . append_sid('admin_ug_auth.' . PHP_EXT . '?mode=' . $ug . '=' . $id[$i]) . '"' . $color[$i] . '>' . $name[$i] . '</a>';
			}
			else
			{
				$t_pending_list .= (($t_pending_list != '') ? ', ' : '') . '<a href="' . append_sid('admin_ug_auth.' . PHP_EXT . '?mode=' . $ug . '=' . $id[$i]) . '"' . $color[$i] . '>' . $name[$i] . '</a>';
			}
		}
	}
	$t_usergroup_list = ($t_usergroup_list == '') ? $lang['None'] : $t_usergroup_list;
	$t_pending_list = ($t_pending_list == '') ? $lang['None'] : $t_pending_list;

	$s_column_span = 2; // Two columns always present
	if(!$adv)
	{
		$template->assign_block_vars('acltype', array(
			'L_UG_ACL_TYPE' => $lang['Simple_Permission']
			)
		);
		$s_column_span++;
	}
	else
	{
		for($i = 0; $i < count($forum_auth_fields); $i++)
		{
			$cell_title = $field_names[$forum_auth_fields[$i]];

			$template->assign_block_vars('acltype', array(
				'L_UG_ACL_TYPE' => $cell_title
				)
			);
			$s_column_span++;
		}
	}

	// Dump in the page header ...
	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ADM_TPL . 'auth_ug_body.tpl'));

	$adv_switch = (empty($adv)) ? 1 : 0;
	$u_ug_switch = ($mode == 'user') ? POST_USERS_URL . '=' . $user_id : POST_GROUPS_URL . '=' . $group_id;
	$switch_mode = append_sid('admin_ug_auth.' . PHP_EXT . '?mode=' . $mode . '&amp;' . $u_ug_switch . '&amp;adv=' . $adv_switch);
	$switch_mode_text = (empty($adv)) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
	$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="adv" value="' . $adv . '" />';
	$s_hidden_fields .= ($mode == 'user') ? '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />' : '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	//Start Quick Administrator User Options and Information MOD
	$s_hidden_fields .= '<input type="hidden" name="redirect" value="' . $redirect .'" />';
	//End Quick Administrator User Options and Information MOD

	if ($mode == 'user')
	{
		$template->assign_block_vars('switch_user_auth', array());

		$template->assign_vars(array(
			'USERNAME' => $t_username,
			'USER_LEVEL' => $lang['User_Level'] . ' : ' . $s_user_type,
			'USER_GROUP_MEMBERSHIPS' => sprintf($lang['Group_memberships'], ($count_ug_info['total'] - 1)) . ': ' . $t_usergroup_list)
		);
	}
	else
	{
		$template->assign_block_vars('switch_group_auth', array());

		$template->assign_vars(array(
			'USERNAME' => $t_groupname,
			'GROUP_MEMBERSHIP' => sprintf($lang['Usergroup_members'], $count_ug_info['total'])  . ': ' . $t_usergroup_list . '<br />' . $lang['Pending_members'] . ' : ' . $t_pending_list)
		);
	}

	$template->assign_vars(array(
		'L_USER_OR_GROUPNAME' => ($mode == 'user') ? $lang['Username'] : $lang['Group_name'],
		'PAGINATION' => $pagination,
		'L_AUTH_TITLE' => ($mode == 'user') ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_AUTH_EXPLAIN' => ($mode == 'user') ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_MODERATOR_STATUS' => $lang['Moderator_status'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_FORUM' => $lang['Forum'],

		'U_USER_OR_GROUP' => append_sid('admin_ug_auth.' . PHP_EXT),
		'U_SWITCH_MODE' => $u_switch_mode,
		'SPACER' => './../' . $images['spacer'],
		'INC_SPAN' => $max_level + 1,
		'S_COLUMN_SPAN' => $s_column_span + $max_level+2,
		'S_AUTH_ACTION' => append_sid('admin_ug_auth.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
else
{
	// Select a user/group
	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ($mode == 'user') ? ADM_TPL . 'user_select_body.tpl' : ADM_TPL . 'auth_select_body.tpl'));

	if ($mode == 'user')
	{
		$template->assign_vars(array(
			'L_FIND_USERNAME' => $lang['Find_username'],
			'U_SEARCH_USER' => append_sid('../' . SEARCH_MG . '?mode=searchuser')
			)
		);
	}
	else
	{
		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE . "
			ORDER BY group_name";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't get group list", "", __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			$select_list = '<select name="' . POST_GROUPS_URL . '">';
			do
			{
				$select_list .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
			}
			while ($row = $db->sql_fetchrow($result));
			$select_list .= '</select>';
		}

		$template->assign_vars(array(
			'S_AUTH_SELECT' => $select_list
			)
		);
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';

	$l_type = ($mode == 'user') ? 'USER' : 'AUTH';

	$template->assign_vars(array(
		'L_' . $l_type . '_TITLE' => ($mode == 'user') ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_' . $l_type . '_EXPLAIN' => ($mode == 'user') ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_' . $l_type . '_SELECT' => ($mode == 'user') ? $lang['Select_a_User'] : $lang['Select_a_Group'],
		'L_LOOK_UP' => ($mode == 'user') ? $lang['Look_up_User'] : $lang['Look_up_Group'],

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_' . $l_type . '_ACTION' => append_sid('admin_ug_auth.' . PHP_EXT))
	);

}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>