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

function user_check_friend($target_id)
{
	global $userdata, $db;
	$sql = "SELECT * FROM " . ZEBRA_TABLE . "
			WHERE user_id = '" . $userdata['user_id'] . "'
				AND zebra_id = '" . $target_id . "'
				AND friend = '1'
			LIMIT 1";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		return true;
	}
	return false;
}

function user_get_zebra_list($ftype = 'friends')
{
	global $userdata, $db;
	if ($ftype == 'foes')
	{
		$sql_f_check = 'foe';
	}
	else
	{
		$sql_f_check = 'friend';
	}
	$zebra_list = array();
	$sql = "SELECT z.zebra_id, u.username, u.user_active, u.user_color
			FROM " . ZEBRA_TABLE . " z, " . USERS_TABLE . " u
			WHERE z.user_id = '" . $userdata['user_id'] . "'
				AND " . $sql_f_check . " = '1'
				AND u.user_id = z.zebra_id
			ORDER BY u.username ASC";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$zebra_list[] = $row;
	}
	$db->sql_freeresult($result);
	if (empty($zebra_list))
	{
		return false;
	}
	else
	{
		return $zebra_list;
	}
}

function user_get_friends_online_list()
{
	global $userdata, $db;
	$friends_online_list = array();
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_allow_viewonline, s.session_logged_in, s.session_time
					FROM " . ZEBRA_TABLE . " z, " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
					WHERE z.user_id = '" . $userdata['user_id'] . "'
					AND z.friend = '1'
					AND u.user_id = z.zebra_id
					AND u.user_id = s.session_user_id
					AND s.session_time >= " . (time() - ONLINE_REFRESH) . "";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$friends_online_list[$row['user_id']]['username'] = $row['username'];
		$friends_online_list[$row['user_id']]['user_active'] = $row['user_active'];
		$friends_online_list[$row['user_id']]['user_color'] = $row['user_color'];
		$friends_online_list[$row['user_id']]['user_level'] = $row['user_level'];
		$friends_online_list[$row['user_id']]['user_allow_viewonline'] = $row['user_allow_viewonline'];
	}
	$db->sql_freeresult($result);
	if (empty($friends_online_list))
	{
		return false;
	}
	else
	{
		return $friends_online_list;
	}
}

function user_check_admin_mod($target_id)
{
	global $db;
	$sql = "SELECT * FROM " . USERS_TABLE . "
			WHERE user_id = '" . $target_id . "'
				AND user_level > 0
			LIMIT 1";
	$result = $db->sql_query($sql, 0, 'user_level_');

	while ($row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		return true;
	}
	return false;
}

function user_check_pm_in_allowed($target_id)
{
	global $userdata, $db;
	$sql = "SELECT * FROM " . USERS_TABLE . "
			WHERE user_id = '" . $target_id . "'
				AND user_allow_pm_in = 1
			LIMIT 1";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		return true;
	}
	return false;
}

?>