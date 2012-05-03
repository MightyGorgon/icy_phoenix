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

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Decide whether XML or JSON is to be used - JSON preferred
$response_type = (function_exists('json_decode') && is_array(json_decode('{"a":1}', true))) ? 'json' : 'xml';

$mode_types = array('archive');
$mode = request_var('mode', '');
$mode = (!in_array($mode, $mode_types) ? '' : $mode);

// Give guest a notice so they know they aren't allowed to use the shoutbox.
if (($config['shout_allow_guest'] == 0) && !$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_AJAX_CHAT, true));
}

$private_chat = false;
$chat_room = request_var('chat_room', '');
$chat_room_users = array_map('intval', explode('|', $chat_room));
$chat_room_users_count = sizeof($chat_room_users);
$chat_room_sql = " s.shout_room = '' ";
if ($chat_room !== '')
{
	// validate chat room
	if (count($chat_room_users) < 2)
	{
		// Less than 2 users in chat room
		message_die(GENERAL_ERROR, $lang['INVALID']);
	}
	sort($chat_room_users);
	$chat_last_user = 0;
	foreach ($chat_room_users as $chat_user)
	{
		if ($chat_user <= $chat_last_user)
		{
			// Same user cannot be twice in a room or invalid user id
			message_die(GENERAL_ERROR, $lang['INVALID']);
		}
		$chat_last_user = $chat_user;
	}
	$chat_room = implode('|', $chat_room_users);
	if (($user->data['user_level'] != ADMIN) && !in_array($user->data['user_id'], $chat_room_users))
	{
		// Current user is not in that chat room
		message_die(GENERAL_ERROR, $lang['Not_Auth_View']);
	}
	$private_chat = true;
	$chat_room_sql = " s.shout_room = '|" . $chat_room . "|' ";
	define('AJAX_CHAT_ROOM', true);
}

// Show shoutbox with header and footer if the user didn't request anything else
if (empty($mode))
{
	if (!isset($cms_page['page_id']))
	{
		$cms_page['page_id'] = 'ajax_chat';
	}
	// Set as tmp value to not overwrite page id if included as a block...
	// Check before the archive link, so we can then use $cms_page_id_tmp for deciding what template to use
	$cms_page_id_tmp = 'ajax_chat_archive';
	$cms_auth_level_tmp = (isset($cms_config_layouts[$cms_page_id_tmp]['view']) ? $cms_config_layouts[$cms_page_id_tmp]['view'] : AUTH_ALL);
	$ajax_archive_link = check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp, true);

	// Import settings from other vars if set... or force global blocks to off since this may be run as stand alone
	$cms_page_id_tmp = 'ajax_chat';
	$cms_page['page_nav'] = isset($cms_page['page_nav']) ? $cms_page['page_nav'] : true;
	$cms_page['global_blocks'] = isset($cms_page['global_blocks']) ? $cms_page['global_blocks'] : false;
	$cms_auth_level_tmp = (isset($cms_config_layouts[$cms_page_id_tmp]['view']) ? $cms_config_layouts[$cms_page_id_tmp]['view'] : AUTH_ALL);
	check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp);

	$breadcrumbs['bottom_right_links'] = '<a href="' . append_sid('ajax_chat.' . PHP_EXT) . '">' . $lang['Ajax_Chat'] . '</a>' . (($ajax_archive_link == true) ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;' . '<a href="' . append_sid('ajax_chat.' . PHP_EXT . '?mode=archive') . '">' . $lang['Ajax_Archive'] . '</a>') : '');

	$template_to_parse = 'ajax_chat_body.tpl';
	$template->assign_vars(array(
		'L_PAGE_TITLE' => $lang['Ajax_Chat'],
		'L_WIO' => $lang['Who_is_Chatting'],
		'L_GUESTS' => $lang['Online_guests'],
		'L_TOTAL' => $lang['Online_total'],
		'L_USERS' => $lang['Online_registered'],
		'L_SHOUTBOX_ONLINE_EXPLAIN' => $lang['Shoutbox_online_explain'],
		'DELETE_IMG' => '<img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" />',
		'L_SHOUT_PREFIX' => 'shout_',
		'L_USER_PREFIX' => 'user_',
		'L_ROOM_PREFIX' => 'room_',
		'S_TARGET' => 'target=\"_blank\"',
		)
	);

	$shoutbox_template_parse = false;
	include(IP_ROOT_PATH . 'includes/ajax_shoutbox_inc.' . PHP_EXT);
}
else
{
	if (!isset($cms_page['page_id']))
	{
		$cms_page['page_id'] = 'ajax_chat_archive';
	}
	// Set as tmp value to not overwrite page id if included as a block...
	// Check before the chat link, so we can then use $cms_page_id_tmp for deciding what template to use
	$cms_page_id_tmp = 'ajax_chat';
	$cms_auth_level_tmp = (isset($cms_config_layouts[$cms_page_id_tmp]['view']) ? $cms_config_layouts[$cms_page_id_tmp]['view'] : AUTH_ALL);
	$ajax_chat_link = check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp, true);

	// Import settings from other vars if set... or force global blocks to off since this may be run as stand alone
	$cms_page_id_tmp = 'ajax_chat_archive';
	$cms_page['page_nav'] = isset($cms_page['page_nav']) ? $cms_page['page_nav'] : true;
	$cms_page['global_blocks'] = isset($cms_page['global_blocks']) ? $cms_page['global_blocks'] : false;
	$cms_auth_level_tmp = (isset($cms_config_layouts[$cms_page_id_tmp]['view']) ? $cms_config_layouts[$cms_page_id_tmp]['view'] : AUTH_ALL);
	check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp);

	$breadcrumbs['bottom_right_links'] = (($ajax_chat_link == true) ? '<a href="' . append_sid('ajax_chat.' . PHP_EXT) . '">' . $lang['Ajax_Chat'] . '</a>&nbsp;' . MENU_SEP_CHAR . '&nbsp;' : '') . '<a href="' . append_sid('ajax_chat.' . PHP_EXT . '?mode=archive') . '">' . $lang['Ajax_Archive'] . '</a>';
	$template_to_parse = 'ajax_chat_archive.tpl';

	include_once(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);
	// Include Post functions and BBCodes
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	// Make Pagination and collect some extra data
	$sql = 'SELECT COUNT(s.shout_id) as stored_shouts, MAX(s.shout_id) as total_shouts
					FROM ' . AJAX_SHOUTBOX_TABLE . ' s
					WHERE ' . $chat_room_sql;
	$result = $db->sql_query($sql);

	$num_items = $db->sql_fetchrow($result);

	$start = request_get_var('start', 0);
	$start = ($start < 0) ? 0 : $start;

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination('ajax_chat.' . PHP_EXT . '?mode=archive&amp;chat_room=' . $chat_room, $num_items['stored_shouts'], $config['posts_per_page'], $start),
		)
	);

	// Get my shouts
	$sql = "SELECT COUNT(s.shout_id) as count
			FROM " . AJAX_SHOUTBOX_TABLE . " s
			WHERE s.user_id = " . $user->data['user_id'] . "
				AND " . $chat_room_sql;
	$result = $db->sql_query($sql);
	$myshouts = $db->sql_fetchrow($result);

	// Get the shouts count for the last 24 hours
	$yesterday = time() - (24 * 60 * 60);
	$sql = "SELECT COUNT(s.shout_id) as count
			FROM " . AJAX_SHOUTBOX_TABLE . " s
			WHERE s.shout_time >= " . $yesterday . "
				AND " . $chat_room_sql;
	$result = $db->sql_query($sql);
	$today = $db->sql_fetchrow($result);

	$template->assign_vars(array(
		'L_PAGE_TITLE' => $lang['Ajax_Archive'],
		'L_AUTHOR' => $lang['Author'],
		'L_SHOUTS' => $lang['Shouts'],
		'L_STATS' =>$lang['Statistics'],
		'L_ARCHIVE' => $lang['Ajax_Archive'],
		'L_CONFIRM' => $lang['Confirm_delete_pm'],
		'L_UNABLE' => $lang['Shoutbox_unable'],
		'L_TIMEOUT' => $lang['Shoutbox_timeout'],
		'TOTAL_SHOUTS' => $num_items['total_shouts'],
		'L_TOTAL_SHOUTS' => $lang['Total_shouts'],
		'STORED_SHOUTS' => $num_items['stored_shouts'],
		'L_STORED_SHOUTS' => $lang['Stored_shouts'],
		'MY_SHOUTS' => $myshouts['count'],
		'L_MY_SHOUTS' => $lang['My_shouts'],
		'TODAY_SHOUTS' => $today['count'],
		'L_TODAY_SHOUTS' => $lang['Today_shouts'],
		'L_POSTED' => $lang['Posted'],
		'L_WIO' => $lang['Who_is_Chatting'],
		'L_GUESTS' =>  $lang['Online_guests'],
		'L_TOTAL' => $lang['Online_total'],
		'L_USERS' => $lang['Online_registered'],
		'L_TOP_SHOUTERS' => $lang['Top_Ten_Shouters'],
		'L_SHOUTBOX_ONLINE_EXPLAIN' => $lang['Shoutbox_online_explain'],
		'L_SHOUT_PREFIX' => 'shout_',
		'L_USER_PREFIX' => 'user_',
		'L_ROOM_PREFIX' => 'room_',
		'PRIVATE_USERS' => '{ }' // Javascript object syntax
		)
	);

	$template->assign_block_vars('view_shoutbox', array(
		'REFRESH_TIME' => (int) $config['ajax_chat_msgs_refresh'] * 1000,
		'RESPONSE_TYPE' => $response_type,
		'CHAT_ROOM' => $chat_room,
		'UPDATE_MODE' => 'archive',
		'U_ACTION' => append_sid(IP_ROOT_PATH . CMS_PAGE_AJAX_SHOUTBOX)
		)
	);

	$admin_mode = false;
	if ($user->data['user_level'] == ADMIN)
	{
		$admin_mode = request_var('admin', 0);
		$admin_mode = empty($admin_mode) ? false : true;
		$template->assign_block_vars('view_shoutbox.user_is_admin', array());
	}

	// Guest are reconized by their IP
	$guest_sql = '';
	$is_guest = false;
	if (!$user->data['session_logged_in'])
	{
		$is_guest = true;
		$guest_sql = " AND session_ip = '" . $db->sql_escape($user->ip) . "'";
	}

	// Update session data and online list - only get session data if the user was online $config['ajax_chat_session_refresh'] seconds ago
	$time_ago = time() - (int) $config['ajax_chat_session_refresh'];

	// Read session data for update
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_level
	FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " s, " . USERS_TABLE . " u
	WHERE s.session_time >= " . $time_ago . "
		AND s.session_user_id = u.user_id" . $guest_sql . "
	ORDER BY case u.user_level when 0 then 10 else u.user_level end";
	$result = $db->sql_query($sql);

	// Set all counters to 0
	$reg_online_counter = $guest_online_counter = $online_counter = 0;
	$online_list = array();

	// Default online user
	$online_user = array();
	while($online = $db->sql_fetchrow($result))
	{
		if($online['user_id'] != ANONYMOUS)
		{
			$style_color = colorize_username($online['user_id'], $online['username'], $online['user_color'], $online['user_active'], false, true);
			$online['user_style_color'] = $style_color;

			if ($online['user_id'] != $user->data['user_id'])
			{
				$online_list[$online['username']] = $online;
			}
			else
			{
				$online['username'] = $lang['My_id'];
				$online_user = $online;
			}
			$reg_online_counter++;
		}
		else
		{
			$guest_online_counter++;
		}
		$online_counter++;
	}

	// Check if anything has changed
	ksort($online_list);
	$online_keys = array_keys($online_list);

	// Start with the user
	if (!empty($online_user))
	{
		if ($response_type == 'xml')
		{
			$template->assign_block_vars('online_list', array(
				'USER_ID' => $online_user['user_id'],
				'USERNAME' => $online_user['username'],
				'USER_STYLE' => $online_user['user_style_color'],
				'CHAT_LINK' => ''
				)
			);
		}
		else
		{
			$json_user = array(
				'user_id' => $online_user['user_id'],
				'username' => $online_user['username'],
				'user_style' => $online_user['user_style_color'],
				'chat_link' => ''
			);
			$template->assign_block_vars('online_list', array(
				'user' => @json_encode($json_user)
				)
			);
		}
	}

	foreach ($online_list as $online)
	{
		$chat_link = '';
		if ($response_type == 'xml')
		{
			$template->assign_block_vars('online_list', array(
				'USER_ID' => $online['user_id'],
				'USERNAME' => $online['username'],
				'USER_STYLE' => $online['user_style_color'],
				'CHAT_LINK' => $chat_link,
				)
			);
		}
		else
		{
			$json_user = array(
				'user_id' => $online['user_id'],
				'username' => $online['username'],
				'user_style' => $online['user_style_color'],
				'chat_link' => $chat_link,
			);
			$template->assign_block_vars('online_list', array(
				'user' => @json_encode($json_user)
				)
			);
		}
	}

	$template->assign_vars(array(
		'TOTAL_COUNTER' => $online_counter,
		'REGISTERED_COUNTER' => $reg_online_counter,
		'GUEST_COUNTER' => $guest_online_counter
		)
	);

	// Get the top ten shouters
	$sql = "SELECT COUNT(*) AS user_shouts, s.user_id, u.username, u.user_color
			FROM " . AJAX_SHOUTBOX_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.user_id != " . ANONYMOUS . "
				AND " . $chat_room_sql . "
				AND u.user_id = s.user_id
			GROUP BY u.user_id
			ORDER BY user_shouts DESC
			LIMIT 10";

	$result = $db->sql_query($sql);
	while($top_shouters = $db->sql_fetchrow($result))
	{
		if ($top_shouters['user_id'] == ANONYMOUS)
		{
			$shouter = $top_shouters['username'];
			$shouter_link = '';
		}
		else
		{
			$shouter = ($user->data['session_logged_in'] && $top_shouters['user_id'] == $user->data['user_id']) ? $lang['My_id'] : $top_shouters['username'];
			$shouter_link = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;u=' . $top_shouters['user_id']);
		}

		$template->assign_block_vars('top_shouters', array(
			'USERNAME' => colorize_username($top_shouters['user_id'], $shouter, $top_shouters['user_color']),
			'USER_LINK' => $shouter_link,
			'USER_SHOUTS' => $top_shouters['user_shouts']
			)
		);
	}

	// Gets the shouts for display
	$chatroom_title = $lang['Public_room'];
	$chatroom_userlist = '';
	$sql = "SELECT s.*, u.username, u.user_color
			FROM " . AJAX_SHOUTBOX_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.user_id = u.user_id
				AND " . $chat_room_sql . "
			ORDER BY s.shout_id DESC
			LIMIT " . $start . ", " . $config['posts_per_page'];
	$results = $db->sql_query($sql);
	$row = $db->sql_fetchrowset($results);

	if(empty($row))
	{
		$template->assign_block_vars('no_shouts', array());
	}
	else
	{
		for($x = 0; $x < sizeof($row); $x++)
		{
			$id = $row[$x]['shout_id'];
			$time = utf8_encode(create_date('d M Y - H:i:s', $row[$x]['shout_time'], $config['board_timezone']));

			if ($row[$x]['user_id'] == ANONYMOUS)
			{
				$shouter = $row[$x]['username'];
				$shouter_link = false;
				$shouter_color = '';
			}
			else
			{
				$shouter = ($user->data['session_logged_in'] && $row[$x]['user_id'] == $user->data['user_id']) ? $lang['My_id'] : $row[$x]['username'];
				$shouter_link = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;u=' . $row[$x]['user_id']);
				$shouter_color = colorize_username($row[$x]['user_id'], $shouter, $row[$x]['user_color'], true, false, true);
			}

			$message = $row[$x]['shout_text'];
			$message = strip_tags($message);
			$message = censor_text($message);

			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
			$message = $bbcode->parse($message);

			if ($user->data['session_logged_in'] && ($user->data['user_level'] == ADMIN))
			{
				$temp_url = 'javascript:removeShout(' . $id . ');';
				$delpost_img = '<a href="#" onclick="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
			}
			else
			{
				$temp_url = '';
				$delpost_img = '';
			}

			if($shouter_link != false)
			{
				$shouter_html = '<a href="' . $shouter_link . '" class="postlink"' . $shouter_color . '>' . $shouter . '</a>';
			}
			else
			{
				$shouter_html = $shouter;
			}

			$template->assign_block_vars('shouts', array(
				'ID' => $id,
				'SHOUTER' => $shouter_html,
				'MESSAGE' => $message,
				'DELETE_IMG' => $delpost_img,
				'DATE' => $time
				)
			);
		}

		// Gets the chat_rooms for display
		$archive_link = '?mode=archive';
		if ($user->data['user_level'] == ADMIN)
		{
			$template->assign_block_vars('rooms', array(
				'NAME' => $lang['Admin_rooms'],
				'LIST' => '',
				'STYLED_LIST' => '',
				'LINK' => append_sid('ajax_chat.' . PHP_EXT . $archive_link . '&amp;admin=1')
				)
			);

			$admin_mode = request_var('admin', '');
			if (!empty($admin_mode))
			{
				$admin_mode = true;
				$archive_link .= '&amp;admin=1';
			}
			else
			{
				$admin_mode = false;
			}
		}
		$room_filter = ($admin_mode == true) ? "shout_room != ''" : "shout_room like '%|" . $user->data['user_id'] . "|%'";
		$sql = "SELECT DISTINCT shout_room
				FROM " . AJAX_SHOUTBOX_TABLE . "
				WHERE " . $room_filter . "
				ORDER BY shout_id DESC";
		$results = $db->sql_query($sql);
		$rooms = $db->sql_fetchrowset($results);
		$room_users = get_chat_room_users($rooms, $chat_room, $archive_link);
		$chatroom_title = $room_users['title'];
		$chatroom_userlist = $room_users['userlist'];
		$rooms = $room_users['rooms'];
		foreach ($rooms as $room)
		{
			$template->assign_block_vars('rooms', $room);
		}
	}
	$template->assign_vars(array(
		'L_SHOUTBOX_EMPTY' => $lang['Shoutbox_empty'],
		'L_SHOUT_ROOMS' => $lang['Shout_rooms'],
		'L_SHOUT_ROOM_TITLE' => $chatroom_title,
		'L_SHOUT_ROOM_LIST' => $chatroom_userlist
		)
	);
}

full_page_generation($template_to_parse, ($template_to_parse == 'ajax_chat_body.tpl') ? $lang['Ajax_Chat'] : $lang['Ajax_Archive'], '', '');

?>
