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
define('IN_ICYPHOENIX', true);
define('MG_CTRACK_FLAG', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if(!empty($_GET['mode']) || !empty($_POST['mode']))
{
	$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = false;
}

// Give guest a notice so they know they aren't allowed to use the shoutbox.
if(($board_config['shout_allow_guest'] == 0) && !$userdata['session_logged_in'])
{
	message_die(GENERAL_ERROR, $lang['Shoutbox_no_auth']);
}

// Show shoutbox with header and footer if the user didn't request anything else
if (($mode == false) || ($mode != 'archive'))
{
	/*
	$cms_page_id = '0';
	$cms_page_name = 'ajax_chat';
	*/
	$auth_level_req = $board_config['auth_view_ajax_chat'];
	if ($auth_level_req > AUTH_ALL)
	{
		if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if ($userdata['user_level'] != ADMIN)
		{
			if ($auth_level_req == AUTH_ADMIN)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
			if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
		}
	}
	$cms_global_blocks = ($board_config['wide_blocks_ajax_chat'] == 1) ? true : false;
	// AJAX Chat currently doesn't have its own wide blocks
	$cms_global_blocks = ($board_config['wide_blocks_shoutbox'] == 1) ? true : false;

	$ajax_archive_link = true;
	/*
	$cms_page_id = '0';
	$cms_page_name = 'ajax_chat';
	*/
	$auth_level_req = $board_config['auth_view_ajax_chat_archive'];
	if ($auth_level_req > AUTH_ALL)
	{
		if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
		{
			$ajax_archive_link = false;
		}
		if ($userdata['user_level'] != ADMIN)
		{
			if ($auth_level_req == AUTH_ADMIN)
			{
				$ajax_archive_link = false;
			}
			if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
			{
				$ajax_archive_link = false;
			}
		}
	}

	if ($ajax_archive_link == true)
	{
		$template->assign_block_vars('archive_link', array());
	}

	$page_title = $lang['Ajax_Chat'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'ajax_chat_body.tpl'));

	$template->assign_vars(array(
		'L_WIO' => $lang['Who_is_Chatting'],
		'L_GUESTS' =>  $lang['Online_guests'],
		'L_TOTAL' => $lang['Online_total'],
		'L_USERS' => $lang['Online_registered'],
		'L_SHOUTBOX_ONLINE_EXPLAIN' => $lang['Shoutbox_online_explain']
		)
	);

	$shoutbox_template_parse = false;
	include(IP_ROOT_PATH . 'includes/ajax_shoutbox_inc.' . PHP_EXT);
}
else
{
	/*
	$cms_page_id = '0';
	$cms_page_name = 'ajax_chat';
	*/
	$auth_level_req = $board_config['auth_view_ajax_chat_archive'];
	if ($auth_level_req > AUTH_ALL)
	{
		if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if ($userdata['user_level'] != ADMIN)
		{
			if ($auth_level_req == AUTH_ADMIN)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
			if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
		}
	}
	$cms_global_blocks = ($board_config['wide_blocks_ajax_chat_archive'] == 1) ? true : false;

	$page_title = $lang['Ajax_Chat'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	include_once(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
	// Include Post functions and BBCodes
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	$template->set_filenames(array('body' => 'ajax_chat_archive.tpl'));

	$template->assign_block_vars('view_shoutbox', array(
		'REFRESH_TIME' => $board_config['shoutbox_refreshtime'],
		'U_ACTION' => append_sid(IP_ROOT_PATH . 'ajax_shoutbox.' . PHP_EXT)
		)
	);

	// Define censored word matches
	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);

	$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	// Make Pagination and collect some extra data
	$sql = 'SELECT COUNT(*) as stored_shouts, MAX(shout_id) as total_shouts
			FROM ' . AJAX_SHOUTBOX_TABLE;
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read shouts information', '', __LINE__, __FILE__);
	}

	$num_items = $db->sql_fetchrow($result);

	$pagination = generate_pagination('ajax_chat.' . PHP_EXT . '?mode=archive', $num_items['stored_shouts'], $board_config['posts_per_page'], $start);

	if($pagination != '')
	{
		$template->assign_block_vars('pag', array(
			'PAGINATION' => $pagination
			)
		);
	}

	// Get my shouts
	$sql = "SELECT COUNT(*) as count
			FROM " . AJAX_SHOUTBOX_TABLE . "
			WHERE user_id = " . $userdata['user_id'];
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read shouts information', '', __LINE__, __FILE__);
	}
	$myshouts = $db->sql_fetchrow($result);

	// Get the shouts count for the last 24 hours
	$yesterday = time() - (24 * 60 * 60);
	$sql = "SELECT COUNT(*) as count
			FROM " . AJAX_SHOUTBOX_TABLE . "
			WHERE shout_time >= " . $yesterday;
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read shouts information', '', __LINE__, __FILE__);
	}
	$today = $db->sql_fetchrow($result);

	$template->assign_vars(array(
		'U_ACTION' => append_sid('ajax_shoutbox.' . PHP_EXT . '?act=del'),
		'L_AUTHOR' => $lang['Author'],
		'L_SHOUTS' => $lang['Shouts'],
		'L_STATS' =>$lang['Statistics'],
		'L_ARCHIVE' => $lang['Ajax_Archive'],
		'L_CONFIRM' => $lang['Confirm_delete_pm'],
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
		'L_SHOUTBOX_ONLINE_EXPLAIN' => $lang['Shoutbox_online_explain']
		)
	);

	// Get Who is Online in the shoutbox
	$time_ago = time() - 30;

	// Set all counters to 0
	$reg_online_counter = $guest_online_counter = $online_counter = 0;

	$sql = "SELECT u.user_id, u.username
		FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " s, " . USERS_TABLE . " u
		WHERE s.session_time >= " . $time_ago . "
			AND s.session_user_id = u.user_id";

	$result = $db->sql_query($sql);
	while($online = $db->sql_fetchrow($result))
	{
		$user_id = $online['user_id'];
		$username = $online['username'];

		if($user_id != ANONYMOUS)
		{
			$username = colorize_username($user_id);
			$template->assign_block_vars('online_list', array(
				'USERNAME' => $username
				)
			);
			$reg_online_counter++;
		}
		else
		{
			$guest_online_counter++;
		}
		$online_counter++;
	}

	$template->assign_vars(array(
		'TOTAL_COUNTER' => $online_counter,
		'REGISTERED_COUNTER' => $reg_online_counter,
		'GUEST_COUNTER' => $guest_online_counter
		)
	);

	// Get the top ten shouters
	$sql = "SELECT COUNT(*) AS user_shouts, s.user_id, u.username
			FROM " . AJAX_SHOUTBOX_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.user_id != " . ANONYMOUS . "
			AND u.user_id = s.user_id
			GROUP BY u.user_id
			ORDER BY user_shouts DESC
			LIMIT 10";

	$results = $db->sql_query($sql);
	while($top_shouters = $db->sql_fetchrow($results))
	{
		$template->assign_block_vars('top_shouters', array(
			'USERNAME' => colorize_username($top_shouters['user_id']),
			//'USER_LINK' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $top_shouters['user_id']),
			'USER_SHOUTS' => $top_shouters['user_shouts']
			)
		);
	}

	// Gets the shouts for display
	if (!empty($_GET['full']))
	{
		$sql = "SELECT sb.*, u.username
				FROM " . AJAX_SHOUTBOX_TABLE . " sb, " . USERS_TABLE . " u
				WHERE sb.user_id = u.user_id
				ORDER BY sb.shout_id ASC";
	}
	else
	{
		$sql = "SELECT sb.*, u.username
				FROM " . AJAX_SHOUTBOX_TABLE . " sb, " . USERS_TABLE . " u
				WHERE sb.user_id = u.user_id
				ORDER BY sb.shout_id DESC
				LIMIT  " . $start . ", " . $board_config['posts_per_page'];
	}

	$results = $db->sql_query($sql);
	$row = $db->sql_fetchrowset($results);

	if(empty($row))
	{
		// This is just to know that there are no shouts in the database.
		$msg = $lang['Shoutbox_empty'];
		message_die(GENERAL_MESSAGE, $msg);
	}

	for($x = 0; $x < count($row); $x++)
	{
		$id = $row[$x]['shout_id'];
		//$time = utf8_encode(create_date($board_config['default_dateformat'], $row[$x]['shout_time'], $board_config['board_timezone']));
		$time = utf8_encode(create_date('Y/m/d - H.i.s', $row[$x]['shout_time'], $board_config['board_timezone']));
		//$time = utf8_encode(date('Y/m/d - H.i.s', $row[$x]['shout_time']));

		if ($row[$x]['user_id'] == ANONYMOUS)
		{
			$shouter = utf8dec($row[$x]['username']);
			$shouter_link = false;
			$shouter_color = '';
		}
		else
		{
			$shouter = utf8dec($row[$x]['username']);
			$shouter_link = append_sid(PROFILE_MG . '?mode=viewprofile&amp;u=' . $row[$x]['user_id']);
			$shouter_color = ' ' . colorize_username($row[$x]['user_id'], false, true);
		}

		//$message = stripslashes($row[$x]['shout_text']);
		$message = utf8dec($row[$x]['shout_text']);
		$bbcode_uid = $row[$x]['shout_uid'];

		// BBCodes parsing not needed in this case!
		/*
		// Word Censor.
		$message = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $message) : $message;

		$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
		$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
		$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
		$message = $bbcode->parse($message, $bbcode_uid);

		//$message = preg_replace(array('<', '>'), array('mg_tag_open', 'mg_tag_close'), $message);
		*/

		if($userdata['session_logged_in'] && ($userdata['user_level'] == ADMIN))
		{
			$temp_url = 'javascript: deleteShout(' . $id . ')';
			$delpost_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
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
}

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>