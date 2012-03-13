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

if (!defined('CTRACKER_DISABLE_OUTPUT'))
{
	define('CTRACKER_DISABLE_OUTPUT', true);
}

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Decide whether XML or JSON is to be used - JSON preferred
$response_type = (function_exists('json_decode') && is_array(json_decode('{"a":1}', true))) ? 'json' : 'xml';

// Lets see what we do, if nothing define show the shoutbox
$action = request_var('act', '');

$private_chat = false;
if (!defined('AJAX_CHAT_ROOM'))
{
	$chat_room = request_var('chat_room', '');
	$chat_room = preg_replace('/[^0-9|]+/', '', trim($chat_room));
	$chat_room_users = array();
	$chat_room_users = explode('|', $chat_room);
	$chat_room_users_count = sizeof($chat_room_users);
	$chat_room_sql = " s.shout_room = '" . $chat_room . "' ";
	if (($user->data['user_level'] != ADMIN) && !empty($chat_room) && !in_array($user->data['user_id'], $chat_room_users))
	{
		message_die(GENERAL_ERROR, $lang['Not_Auth_View']);
	}
	define('AJAX_CHAT_ROOM', true);
	$private_chat = true;
}

if (!empty($action))
{
	define('AJAX_HEADERS', true);
	// Headers are sent to prevent browsers from caching... IE is still resistent sometimes
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');

	if ($response_type == 'xml') // can be 'xml' or 'json'
	{
		header('Content-type: text/xml; charset=UTF-8');
		$template->set_filenames(array('xhr' => 'ajax_shoutbox_xml.tpl'));
	}
	else
	{
		header('Content-type: application/json; charset=UTF-8');
		$template->set_filenames(array('xhr' => 'ajax_shoutbox_json.tpl'));
	}

	$error = AJAX_SHOUTBOX_NO_ERROR;
	$error_msg = '';

	// Delete alert for poster if present
	if ($private_chat && !empty($user->data['user_private_chat_alert']))
	{
		$sql = "UPDATE " . USERS_TABLE . " SET user_private_chat_alert = '0' WHERE user_id = " . $user->data['user_id'];
		$db->sql_query($sql);
	}

	// Code for sending data
	if ($action == 'add')
	{
		$shouter = request_var('nm', '', true);
		$shouter = urldecode($shouter); // reverse the Javascript encodeURIComponent()
		$shouter = htmlspecialchars_decode($shouter, ENT_COMPAT);
		$message = request_var('co', '', true);
		$message = urldecode($message); // reverse the Javascript encodeURIComponent()
		$message = htmlspecialchars_decode($message, ENT_COMPAT);
		$shout_time = time();

		// Flood Control
		$sql = "SELECT MAX(s.shout_time) AS last_shout
				FROM " . AJAX_SHOUTBOX_TABLE . " s
				WHERE s.shouter_ip = '" . $db->sql_escape($user_ip) . "'";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			if ($row = $db->sql_fetchrow($result))
			{
				if (($shout_time - intval($row['last_shout'])) < $config['shoutbox_floodinterval'])
				{
					// Display error
					$error = AJAX_SHOUTBOX_ERROR;
					pseudo_die(SHOUTBOX_ERROR, $lang['Shoutbox_flooderror']);
				}
			}
		}

		// Alert other users that somebody is willing to chat with them
		if ($private_chat)
		{
			// It omits users that have been active for the last 5 minutes (300 seconds)
			$sql = "SELECT session_user_id
					FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . "
					WHERE " . $db->sql_in_set('session_user_id', $chat_room_users) . "
						AND session_time < " . (time() - 300) . "
					ORDER BY session_user_id ASC";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$alert_users_array = array();
			foreach ($chat_room_users as $chat_room_user)
			{
				if (($chat_room_user != $user->data['user_id']) && !in_array($chat_room_user, $row))
				{
					$alert_users_array[] = $chat_room_user;
				}
			}

			if (sizeof($alert_users_array) > 0)
			{
				$sql = "UPDATE " . USERS_TABLE . " SET user_private_chat_alert = '" . $chat_room . "' WHERE " . $db->sql_in_set('user_id', $alert_users_array);
				$db->sql_query($sql);
			}
		}

		// Some weird conversion of the data inputed
		if ($user->data['session_logged_in'])
		{
			$shouter = '';
		}
		else
		{
			// Stop guest shouts if they are not allowed
			if ($config['shout_allow_guest'] != 1)
			{
				pseudo_die(SHOUTBOX_ERROR, $lang['Shoutbox_no_auth']);
			}

			if ($shouter == '')
			{
				$shouter = $lang['Guest'];
			}
			else
			{
				$shouter = strip_tags($shouter);

				// The name is shortened to 30 letters
				$shouter = substr($shouter, 0, 30);

				// Check the username
				include_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
				$check_name = validate_username($shouter);
				// Username is invalid so tell the user and die
				if ($check_name['error'])
				{
					$error = AJAX_SHOUTBOX_ERROR;
					pseudo_die(SHOUTBOX_ERROR, $check_name['error_msg']);
				}
			}
		}
		
		// Only if a name and a message have been provides the information is added to the db
		if ($message != '')
		{
			// Add new data
			$sql = "INSERT INTO " . AJAX_SHOUTBOX_TABLE . " (user_id, shouter_name, shout_text, shouter_ip, shout_time, shout_room) VALUES (" . $user->data['user_id'] . ", '" . $db->sql_escape($shouter) . "', '" . $db->sql_escape($message) . "', '" . $db->sql_escape($user_ip) . "', " . $shout_time . ", '" . $chat_room . "')";

			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				pseudo_die(SHOUTBOX_ERROR, $lang['Shoutbox_unable']);
			}

			// Only do this if there is a limit.
			if ($config['stored_shouts'] > 1)
			{
				$limit = $config['stored_shouts'] - 1;
				// Keep the database with the selected number of entrys.
				$sql = "SELECT s.shout_id
						FROM " . AJAX_SHOUTBOX_TABLE . " s
						WHERE s.shout_id > 0
							AND " . $chat_room_sql . "
						ORDER BY s.shout_id DESC
						LIMIT " . $limit . ", 1";
				$results = $db->sql_query($sql);
				$row = $db->sql_fetchrowset($results);
				$id = $row[0]['shout_id'];

				if ($id)
				{
					// Delete all message prior to a certain id
					$sql = "DELETE FROM " . AJAX_SHOUTBOX_TABLE . " WHERE shout_id < " . $id;
					$results = $db->sql_query($sql);
				}
			}
		}
		else
		{
			$error = AJAX_SHOUTBOX_ERROR;
			$error_msg = $lang['Empty_message'];
		}
	}
	// Code for getting data - also after a successful add
	if (($action == 'read') || ($action == 'add' && $error == AJAX_SHOUTBOX_NO_ERROR))
	{
		// Stop guest from reading the shoutbox if they aren't allowed
		if (($config['shout_allow_guest'] == 0) && !$user->data['session_logged_in'])
		{
			pseudo_die(AJAX_SHOUTBOX_NO_ERROR, $lang['Shoutbox_no_auth']);
		}

		// Update session data and online list
		if (isset($_POST['su']))
		{
			update_session($error_msg);
			if ($error_msg != '')
			{
				pseudo_die(AJAX_SHOUTBOX_ERROR, $error_msg);
			}

			// Only get session data if the user was online SESSION_REFRESH seconds ago
			$time_ago = time() - SESSION_REFRESH;

			// Read session data for update
			$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_level
			FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.session_time >= " . $time_ago . "
				AND s.session_user_id = u.user_id
			ORDER BY case u.user_level when 0 then 10 else u.user_level end";
			$result = $db->sql_query($sql);

			// Set all counters to 0
			$reg_online_counter = $guest_online_counter = $online_counter = 0;
			$online_list = array();
			while ($online = $db->sql_fetchrow($result))
			{
				if($online['user_id'] != ANONYMOUS)
				{
					$online_list[$online['user_id']] = $online;
					$reg_online_counter++;
				}
				else
				{
					$guest_online_counter++;
				}
				$online_counter++;
			}

			// Check if anything has changed
			$online_keys = array_keys($online_list);
			sort($online_keys, SORT_STRING);
			$signature = md5(implode(',', $online_keys));
			$sig = request_var('sig', '');

			if ($signature != $sig)
			{
				foreach ($online_list as $name => $online)
				{
					$style_color = colorize_username($online['user_id'], $online['username'], $online['user_color'], $online['user_active'], false, true);
					if ($response_type == 'xml')
					{
						$template->assign_block_vars('online_list', array(
							'USER' => $online['username'],
							'USER_ID' => $online['user_id'],
							'LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $online['user_id']),
							'LINK_STYLE' => $style_color,
							)
						);
					}
					else
					{
						$json_user = array(
							'user_id' => $online['user_id'],
							'username' => $online['username'],
							'user_link' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $online['user_id']),
							'link_style' => $style_color,
						);
						$template->assign_block_vars('online_list', array(
							'user' => json_encode($json_user)
							)
						);
					}
				}
				$template->assign_block_vars('online_stats', array(
					'TOTAL' => $online_counter,
					'GUESTS' => $guest_online_counter,
					'REG' => $reg_online_counter,
					'SIG' => $signature
					)
				);
			}
		}

		// If the request does not provide the id of the last know message the id is set to 0
		$lastID = request_var('lastID', 0);

		$limit_sql = '';
		// Check if there is a limit else, show all shouts
		if ($config['display_shouts'] > 0)
		{
			// Gets a limited number of entries
			$limit_sql = " LIMIT " . $config['display_shouts'];
		}

		$sql = "SELECT s.*, u.username, u.user_active, u.user_color
				FROM " . AJAX_SHOUTBOX_TABLE . " s, " . USERS_TABLE . " u
				WHERE s.shout_id > " . $lastID . "
					AND s.user_id = u.user_id
					AND " . $chat_room_sql . "
				ORDER BY s.shout_id DESC" . $limit_sql;
		$results = $db->sql_query($sql);
		$row = $db->sql_fetchrowset($results);

		if (!(empty($row)))
		{
			$row = array_reverse($row);
		}
		else
		{
			// This is just to know that there are no shouts in the database but it's not an error
			pseudo_die(AJAX_SHOUTBOX_NO_ERROR, $lang['Shoutbox_empty']);
		}

		for ($x = 0; $x < sizeof($row); $x++)
		{
			$id = $row[$x]['shout_id'];
			$time = utf8_encode(create_date('Y/m/d - H.i.s', $row[$x]['shout_time'], $config['board_timezone']));

			if ($row[$x]['user_id'] == ANONYMOUS)
			{
				$shouter = utf8_encode($row[$x]['shouter_name']);
				$shouter_link = -1;
			}
			else
			{
				$shouter = utf8_encode($row[$x]['username']);
				$shouter_link = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;u=' . $row[$x]['user_id']);
			}

			$shouter = urlencode($shouter); // for Javascript
			$shouter_color = colorize_username($row[$x]['user_id'], $row[$x]['username'], $row[$x]['user_color'], $row[$x]['user_active'], false, true);

			$message = $row[$x]['shout_text'];
			$message = strip_tags($message);
			$message = censor_text($message);

			// Forced HTML to false to avoid problems
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
			$message = $bbcode->parse($message);

			$message = urlencode($message); // for Javascript

			if ($response_type == 'xml')
			{
				$template->assign_block_vars('shouts', array(
					'ID' => $id,
					'SHOUTER' => $shouter,
					'SHOUTER_ID' => $row[$x]['user_id'],
					'SHOUTER_COLOR' => $shouter_color,
					'SHOUTER_LINK' => $shouter_link,
					'MESSAGE' => $message,
					'DATE' => $time
					)
				);
			}
			else
			{
				$json_shout = array(
					'id' => $id,
					'shouter' => $shouter,
					'shouter_id' => $row[$x]['user_id'],
					'shouter_color' => $shouter_color,
					'shouter_link' => $shouter_link,
					'msg' => $message,
					'date' => $time
				);
				$template->assign_block_vars('shouts', array(
					'shout' => json_encode($json_shout)
					)
				);
			}
		}
	}
	// Code for Deleting Data
	elseif ($action == 'del')
	{
		if (($user->data['user_level'] == ADMIN) && ($user->data['session_logged_in']))
		{
			$shout_id = request_var('sh', 0);
			$sql = 'DELETE FROM ' . AJAX_SHOUTBOX_TABLE . ' WHERE shout_id =' . $shout_id;
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				$error = AJAX_SHOUTBOX_ERROR;
				$error_msg = $lang['Shoutbox_unable'];
			}
		}
	}
	// Code to leave the chat room
	elseif ($action == 'leave')
	{
		remove_session($error_msg);
		if ($error_msg != '')
		{
			pseudo_die(AJAX_SHOUTBOX_ERROR, $error_msg);
		}
	}
	else {
			pseudo_die(AJAX_SHOUTBOX_ERROR, "unknown action");
	}

	// Send back the XHR response
	pseudo_die($error, $error_msg);
}

if (!$shoutbox_template_parse)
{
	// Load templates
	$template->set_filenames(array('shoutbox' => 'ajax_shoutbox_body.tpl'));
}

// Use special dimensions to the else use default.
$shoutbox_width = request_var('width', 710);
$shoutbox_height = request_var('height', 350);
if(($shoutbox_width <= 0) || ($shoutbox_height <= 0))
{
	$shoutbox_width = 710;
	$shoutbox_height = 350;
}

/* Results need a fixed width a height for the overflow. */
$shoutbox_div_width = (95 / 100) * $shoutbox_width;
$shoutbox_div_height = (85 / 100) * $shoutbox_height;

$shoutbox_table_width = $shoutbox_div_width - 30;
$shoutbox_table_height = $shoutbox_div_height - 25;

$template->assign_vars(array(
	'L_SHOUTBOX' => $lang['Ajax_Shoutbox'],
	'L_USERNAME' => $lang['Username'],
	'L_MESSAGE' => $lang['Message'],
	'L_DELETE' => $lang['Delete'],
	'L_CONFIRM' => $lang['Confirm_delete_pm'],
	'L_SUMBIT' => $lang['Submit'],
	'L_ARCHIVE' => $lang['Ajax_Archive'],
	'L_UNABLE' => $lang['Shoutbox_unable'],
	'L_TIMEOUT' => $lang['Shoutbox_timeout'],
	'L_WIO' => $lang['Who_is_Chatting'],
	'L_GUESTS' =>  $lang['Online_guests'],
	'L_TOTAL' => $lang['Online_total'],
	'L_USERS' => $lang['Online_registered'],
	'L_TOP_SHOUTERS' => $lang['Top_Ten_Shouters'],
	'L_SHOUTBOX_ONLINE_EXPLAIN' => $lang['Shoutbox_online_explain'],
	'L_SHOUT_PREFIX' => 'shout-',
	'L_USER_PREFIX' => 'user-',
	'U_ARCHIVE' => append_sid(CMS_PAGE_AJAX_CHAT . '?mode=archive')
	)
);

if ($config['shout_allow_guest'] > 0)
{
	// Guest and Users may see the shoutbox
	$template->assign_block_vars('view_shoutbox', array(
		'BOX_WIDTH' => $shoutbox_width,
		'BOX_HEIGHT' => $shoutbox_height,
		'DIV_WIDTH' => $shoutbox_div_width,
		'DIV_HEIGHT' => $shoutbox_div_height,
		'TABLE_WIDTH' => $shoutbox_table_width,
		'TABLE_HEIGHT' => $shoutbox_table_height,
		'REFRESH_TIME' => $config['shoutbox_refreshtime'],
		'CHAT_ROOM' => $chat_room,
		'U_ACTION' => IP_ROOT_PATH . 'ajax_shoutbox.' . PHP_EXT
		)
	);
	if ($config['shout_allow_guest'] == 1)
	{
		// Guest and users may shout.
		$template->assign_block_vars('view_shoutbox.shout_allowed', array());
		if (!($user->data['session_logged_in']))
		{
			// Only guests need to enter a username
			$template->assign_block_vars('view_shoutbox.shout_allowed.guest_shouter', array());
		}
	}
	else
	{
		// Only registered users may shout.
		if ($user->data['session_logged_in'])
		{
			$template->assign_block_vars('view_shoutbox.shout_allowed', array());
		}
	}
}
else
{
	// Only registered users may see/shout
	if ($user->data['session_logged_in'])
	{
		$template->assign_block_vars('view_shoutbox', array(
			'BOX_WIDTH' => $shoutbox_width,
			'BOX_HEIGHT' => $shoutbox_height,
			'DIV_WIDTH' => $shoutbox_div_width,
			'DIV_HEIGHT' => $shoutbox_div_height,
			'TABLE_WIDTH' => $shoutbox_table_width,
			'TABLE_HEIGHT' => $shoutbox_table_height,
			'REFRESH_TIME' => $config['shoutbox_refreshtime'],
			'RESPONSE_TYPE' => $response_type,
			'CHAT_ROOM' => $chat_room,
			'U_ACTION' => IP_ROOT_PATH . 'ajax_shoutbox.' . PHP_EXT
			)
		);
		$template->assign_block_vars('view_shoutbox.shout_allowed', array());
	}
	else
	{
		//message_die(GENERAL_MESSAGE, $lang['Shoutbox_no_auth']);
		pseudo_die(GENERAL_MESSAGE, $lang['Shoutbox_no_auth']);
	}
}

$template->assign_block_vars('view_shoutbox.onload', array());

if ($user->data['user_level'] == ADMIN)
{
	$template->assign_block_vars('view_shoutbox.user_is_admin', array());
}

// BBCBMG - BEGIN
define('BBCB_MG_SMALL', true);
include_once(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_vars(array(
	'BBCB_FORM_NAME' => 'chatForm',
	'BBCB_TEXT_NAME' => 'chatbarText',
	)
);
$template->assign_var_from_handle('BBCB_MG_SMALL', 'bbcb_mg');
// BBCBMG - END

if ($shoutbox_template_parse)
{
	// We're Out of PhpBB so call the Simple header and parser
	$gen_simple_header = true;
	$template->assign_var('S_POPUP', true);
	full_page_generation('ajax_shoutbox_body.tpl', $lang['Ajax_Chat'], '', '');
}
else
{
	$template->assign_var_from_handle('SHOUTBOX_BODY', 'shoutbox');
}

?>
