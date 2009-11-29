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

// CTracker_Ignore: File checked by human
/*
	From php.net comments
	by ivanmaz(remove) at mech dot math dot msu dot su
	UTF8 to Cyrillic Win-1251 Convertor
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

	$template->pparse('xml');
	die();
}

// Update and return Shoutbox sessions data
function update_session(&$error_msg)
{
	global $db, $cache, $userdata, $lang, $user_ip;
	$guest_sql = '';
	$online_counter = 0;
	$reg_online_counter = 0;
	$guest_online_counter = 0;

	// First clean old data... so we should have a light table...
	$clean_time = time() - 86400;
	$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " WHERE session_time < " . $clean_time;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error_msg = 'Could not update Shoutbox session data';
	}

	// Guest are reconized by their IP
	if(!$userdata['session_logged_in'])
	{
		$guest_sql = " AND session_ip = '" . $user_ip . "'";
	}

	// Only get session data if the user was online SESSION_REFRESH seconds ago
	$time_ago = time() - SESSION_REFRESH;
	$sql = 'SELECT session_id
			FROM ' . AJAX_SHOUTBOX_SESSIONS_TABLE . '
			WHERE session_user_id = ' . $userdata['user_id'] . '
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
	if($row = $db->sql_fetchrow($result))
	{
		$current_session_id = $row['session_id'];
		$sql = "UPDATE " . AJAX_SHOUTBOX_SESSIONS_TABLE . "
				SET session_ip = '" . $user_ip . "',
				session_time = " . time() . "
				WHERE session_id = " . $row['session_id'];
	}
	else
	{
		$current_session_id = get_ajax_chat_max_session_id() + 1;
		$sql = "INSERT INTO " . AJAX_SHOUTBOX_SESSIONS_TABLE . " (session_id, session_user_id, session_username, session_ip, session_start, session_time)
			VALUES (" . $current_session_id . ", " . $userdata['user_id'] . ", '" . ($userdata['session_logged_in'] ? $userdata['username'] : '') . "', '" . $user_ip . "', " . time() . ", " . time() . ")";
	}
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error_msg = 'Could not update Shoutbox session data';
	}

	$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . "
			WHERE session_user_id = " . $userdata['user_id'] . "
				AND session_id <> " . $current_session_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error_msg = 'Could not update Shoutbox session data';
	}

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

?>