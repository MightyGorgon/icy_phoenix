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
* Javier B (kinfule@lycos.es)
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/*
* From php.net comments
* by ivanmaz(remove) at mech dot math dot msu dot su
* UTF8 to Cyrillic Win-1251 Convertor
*/
function utf8dec($s)
{
	$out = "";
	for ($i = 0; $i < strlen($s); $i++)
	{
		$c1 = substr ($s, $i, 1);
		$byte1 = ord ($c1);
		if ($byte1>>5 == 6) // 110x xxxx, 110 prefix for 2 bytes unicode
		{
			$i++;
			$c2 = substr ($s, $i, 1);
			$byte2 = ord ($c2);
			$byte1 &= 31; // remove the 3 bit two bytes prefix
			$byte2 &= 63; // remove the 2 bit trailing byte prefix
			$byte2 |= (($byte1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
			$byte1 >>= 2; // c1 shifts 2 to the right

			$word = ($byte1<<8) + $byte2;
			if ($word == 1025)
			{
				$out .= chr(168);
			}
			elseif ($word == 1105)
			{
				$out .= chr(184);
			}
			elseif ($word >= 0x0410 && $word <= 0x044F)
			{
				$out .= chr($word - 848);
			}
			else
			{
				$a = dechex($byte1);
				$a = str_pad($a, 2, "0", STR_PAD_LEFT);
				$b = dechex($byte2);
				$b = str_pad($b, 2, "0", STR_PAD_LEFT);
				$out .= "&#x" . $a . $b . ";";
			}
		}
		else
		{
			$out .= $c1;
		}
	}
	return $out;
}

// A fast way to stop running the script and displaying the xml response
function pseudo_die($error, $error_msg)
{
	global $template;

	$template->assign_vars(array(
		'ERROR_STATUS' => $error,
		'ERROR_MSG' => utf8_encode($error_msg)
		)
	);

	$template->pparse('xhr');
	die();
}

// Update and return Shoutbox sessions data
function update_session(&$error_msg, $refresh = true)
{
	global $db, $cache, $config, $user, $lang;

	$guest_sql = '';
	$online_counter = 0;
	$reg_online_counter = 0;
	$guest_online_counter = 0;

	// First clean old data... so we should have a light table...
	// Just double chat session refresh time to make sure we are not removing sessions for users still active...
	$clean_time = time() - ((int) $config['ajax_chat_session_refresh'] * 2);
	$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " WHERE session_time < " . $clean_time;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error_msg = 'Could not update Shoutbox session data';
	}

	if ($refresh)
	{
		// Guest are reconized by their IP
		if (!$user->data['session_logged_in'])
		{
			$guest_sql = " AND session_ip = '" . $db->sql_escape($user->ip) . "'";
		}

		// Only get session data if the user was online $config['ajax_chat_session_refresh'] seconds ago
		$time_ago = time() - (int) $config['ajax_chat_session_refresh'];
		$sql = 'SELECT session_id
				FROM ' . AJAX_SHOUTBOX_SESSIONS_TABLE . '
				WHERE session_user_id = ' . $user->data['user_id'] . '
					AND session_time >= ' . $time_ago . '
					' . $guest_sql . '
				LIMIT 1';
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$error_msg = 'Can\'t read shoutbox session data';
		}

		// We need to decide if we create an entry or update a previous one
		if ($row = $db->sql_fetchrow($result))
		{
			$current_session_id = $row['session_id'];
			$sql = "UPDATE " . AJAX_SHOUTBOX_SESSIONS_TABLE . "
					SET session_ip = '" . $db->sql_escape($user->ip) . "',
					session_time = " . time() . "
					WHERE session_id = " . $row['session_id'];
		}
		else
		{
			$current_session_id = get_ajax_chat_max_session_id() + 1;
			$sql = "INSERT INTO " . AJAX_SHOUTBOX_SESSIONS_TABLE . " (session_id, session_user_id, session_username, session_ip, session_start, session_time)
				VALUES (" . $current_session_id . ", " . $user->data['user_id'] . ", '" . ($user->data['session_logged_in'] ? $user->data['username'] : '') . "', '" . $db->sql_escape($user->ip) . "', " . time() . ", " . time() . ")";
		}
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$error_msg = 'Could not update Shoutbox session data';
		}

		$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . "
				WHERE session_user_id = " . $user->data['user_id'] . "
					AND session_id <> " . $current_session_id;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$error_msg = 'Could not update Shoutbox session data';
		}
	}

	if (!empty($user->data['user_private_chat_alert']))
	{
		$sql = "UPDATE " . USERS_TABLE . " SET user_private_chat_alert = '' WHERE user_id = " . $user->data['user_id'];
		$db->sql_return_on_error(true);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);
	}

}

// remove a Shoutbox session
function remove_session(&$error_msg)
{
	global $db, $user, $user_ip;
	$guest_sql = '';

	// Guest are reconized by their IP
	if (!$user->data['session_logged_in'])
	{
		$guest_sql = " AND session_ip = '" . $db->sql_escape($user->ip) . "'";
	}

	// Only get session data if the user was online $config['ajax_chat_session_refresh'] seconds ago
	$time_ago = time() - (int) $config['ajax_chat_session_refresh'];
	$sql = 'SELECT session_id
			FROM ' . AJAX_SHOUTBOX_SESSIONS_TABLE . '
			WHERE session_user_id = ' . $user->data['user_id'] . '
				AND session_time >= ' . $time_ago . '
				' . $guest_sql . '
			LIMIT 1';
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error_msg = 'Can\'t read shoutbox session data';
	}

	// We need to delete a previous existing entry only
	if ($row = $db->sql_fetchrow($result))
	{
		$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . "
				WHERE session_id = " . $row['session_id'];
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$error_msg = 'Could not delete Shoutbox session data';
		}
	}
}

// Checks if a user is in the chat session
function user_in_chat_session($id)
{
	global $db, $cache, $config;

	// Only get session data if the user was online $config['ajax_chat_session_refresh'] seconds ago
	$time_ago = time() - (int) $config['ajax_chat_session_refresh'];
	$sql = 'SELECT session_id
			FROM ' . AJAX_SHOUTBOX_SESSIONS_TABLE . '
			WHERE session_user_id = ' . $id . '
				AND session_time >= ' . $time_ago . '
			LIMIT 1';
	$result = $db->sql_query($sql);
	if (!$result)
	{
		return false;
	}
	if ($row = $db->sql_fetchrow($result))
	{
		return true;
	}
	return false;
}

// Get max session_id
function get_ajax_chat_max_session_id()
{
	global $db, $cache;

	$sql = 'SELECT MAX(session_id) AS max_session_id
			FROM ' . AJAX_SHOUTBOX_SESSIONS_TABLE;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error_msg = 'Can\'t read shoutbox session data';
	}

	if($row = $db->sql_fetchrow($result))
	{
		return (int) $row['max_session_id'];
	}
	else
	{
		return 0;
	}
}

// Given a list of rooms, produce a list of users in those rooms
//
// $rooms the list of rooms
// $chat_room the current chat room
// $chat_link the chat room link
function get_chat_room_users($rooms, $chat_room, $chat_link)
{
	global $db, $cache, $user, $lang;

	$chatroom_title = $lang['Public_room'];
	$chatroom_userlist = '';
	$result = array();
	$result['rooms'] = array();
	$room_class = '';
	$chat_room_all = request_var('all_rooms', 0);
	$chat_room_all = !empty($chat_room_all) ? true : false;
	if (($chat_room == '') && empty($chat_room_all))
	{
		$room_class = ' class="active"';
	}
	$result['rooms'][] = array(
		'NAME' => $lang['Public_room'],
		'LIST' => '',
		'STYLED_LIST' => '',
		'CLASS' => $room_class,
		'LINK' => append_sid($chat_link)
	);
	$room_list_ids = array();
	$room_styled_list_ids = array();
	if (!empty($rooms))
	{
		$room_users_list = '';
		foreach ($rooms as $room)
		{
			$room_users_list .= $room['shout_room'];
		}
		$room_users_sql = array_unique(array_filter(array_map('intval', explode('|', $room_users_list))));
		$sql = "SELECT DISTINCT user_id, username, user_color, user_active
				FROM " . USERS_TABLE . "
				WHERE " . $db->sql_in_set('user_id', $room_users_sql);
		$results = $db->sql_query($sql);
		$users = $db->sql_fetchrowset($results);

		foreach ($users as $chat_user)
		{
			if($user->data['session_logged_in'] && ($chat_user['user_id'] == $user->data['user_id']))
			{
				$room_list_ids[$chat_user['user_id']] = $lang['My_id'];
				$room_styled_list_ids[$chat_user['user_id']] = colorize_username($chat_user['user_id'], $lang['My_id'], $chat_user['user_color'], $chat_user['user_active'], false, true);
			}
			else
			{
				$room_list_ids[$chat_user['user_id']] = $chat_user['username'];
				$room_styled_list_ids[$chat_user['user_id']] = colorize_username($chat_user['user_id'], $chat_user['username'], $chat_user['user_color'], $chat_user['user_active'], false, true);
			}
		}

		foreach ($rooms as $room)
		{
			$comma = '';
			$list = '';
			$styled_list = '';
			$room_class = '';

			$current_room = $room['shout_room'];
			$room_users = array_unique(array_filter(array_map('intval', explode('|', $room['shout_room']))));
			foreach ($room_users as $room_user)
			{
				$list .= $comma . $room_list_ids[$room_user];
				$styled_list .= $comma . '<span ' . $room_styled_list_ids[$room_user] . '>' . $room_list_ids[$room_user] . '</span>';
				$comma = ', ';
			}
			if ($current_room == ('|' . $chat_room . '|'))
			{
				$room_class = ' class="active"';
				$chatroom_title = $lang['Private_room'];
				$chatroom_userlist = $styled_list;
			}
			$result['rooms'][] = array(
				'NAME' => $lang['Private_room'],
				'LIST' => $list,
				'STYLED_LIST' => $styled_list,
				'CLASS' => $room_class,
				'LINK' => append_sid($chat_link . '&amp;chat_room=' . implode('|', $room_users))
			);
		}
	}
	$result['room_list_ids'] = $room_list_ids;
	$result['styled_list_ids'] = $room_styled_list_ids;
	$result['title'] = $chatroom_title;
	$result['userlist'] = $chatroom_userlist;
	return $result;
}

?>