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

if (!defined('MG_CTRACK_FLAG'))
{
	define('MG_CTRACK_FLAG', true);
}

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

$action = false;
// Lets see what we do, if nothing define show the shoutbox
if (!empty($_POST['act']) || !empty($_GET['act']))
{
	$action = (!empty($_POST['act'])) ? htmlspecialchars($_POST['act']) : htmlspecialchars($_GET['act']);
}

if($action)
{
	// Headers are sent to prevent browsers from caching... IE is still resistent sometimes
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');
	header('Content-type: text/xml; charset=utf-8');

	// Define the XML Template
	$template->set_filenames(array('xml' => 'ajax_shoutbox_xml.tpl'));

	$error = AJAX_SHOUTBOX_NO_ERROR;
	$error_msg = '';

	// Code for getting data
	if($action == 'read')
	{
		// Stop guest from reading the shoutbox if they aren't allowed
		if (($board_config['shout_allow_guest'] == 0) && !$userdata['session_logged_in'])
		{
			pseudo_die(SHOUTBOX_NO_ERROR, $lang['Shoutbox_no_auth']);
		}

		// Update session data and online list
		if(isset($_POST['su']))
		{
			update_session($error_msg);
			// Read session data for update
			$sql = "SELECT u.user_id, u.username, u.user_level
			FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.session_time >= " . (time() - 20) . "
			AND s.session_user_id = u.user_id
			ORDER BY case user_level when 0 then 10 else user_level end";
			$result = $db->sql_query($sql);

			// Set all counters to 0
			$reg_online_counter = $guest_online_counter = $online_counter = 0;
			while($online = $db->sql_fetchrow($result))
			{
				if($online['user_id'] != ANONYMOUS)
				{
					$style_color = colorize_username($online['user_id'], false, true);

					$template->assign_block_vars('online_list', array(
						'USER' => $online['username'],
						'USER_ID' => $online['user_id'],
						'LINK' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $online['user_id']),
						'LINK_STYLE' => $style_color,
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

			$template->assign_block_vars('online_stats', array(
				'TOTAL' => $online_counter,
				'GUESTS' => $guest_online_counter++,
				'REG' => $reg_online_counter
				)
			);
		}

		// Define censored word matches
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		// If the request does not provide the id of the last know message the id is set to 0
		$lastID = ($_GET['lastID']) ? intval($_GET['lastID']) : 0;

		// Check if there is a limit else, show all shouts
		if($board_config['display_shouts'] > 0)
		{
			// Gets a limited number of entries
			$sql = "SELECT sb.*, u.username
					FROM " . AJAX_SHOUTBOX_TABLE . " sb, " . USERS_TABLE . " u
					WHERE sb.shout_id > " . $lastID . "
					AND sb.user_id = u.user_id
					ORDER BY sb.shout_id DESC
					LIMIT " . $board_config['display_shouts'];
		}
		else
		{
			// Get all shouts
			$sql = "SELECT sb.*, u.username
					FROM " . AJAX_SHOUTBOX_TABLE . " sb, " . USERS_TABLE . " u
					WHERE sb.shout_id > " . $lastID . "
					AND sb.user_id = u.user_id
					ORDER BY sb.shout_id DESC";
		}
		$results = $db->sql_query($sql);
		$row = $db->sql_fetchrowset($results);

		if(!(empty($row)))
		{
			$row = array_reverse($row);
		}
		else
		{
			// This is just to know that there are no shouts in the database but it's not an error
			pseudo_die(SHOUTBOX_NO_ERROR, $lang['Shoutbox_empty']);
		}

		for($x = 0; $x < count($row); $x++)
		{
			$id = $row[$x]['shout_id'];
			//$time = utf8_encode(create_date($board_config['default_dateformat'], $row[$x]['shout_time'], $board_config['board_timezone']));
			$time = utf8_encode(create_date('Y/m/d - H.i.s', $row[$x]['shout_time'], $board_config['board_timezone']));
			//$time = utf8_encode(date('Y/m/d - H.i.s', $row[$x]['shout_time']));

			if ($row[$x]['user_id'] == ANONYMOUS)
			{
				$shouter = utf8_encode($row[$x]['shouter_name']);
				$shouter_link = -1;
			}
			else
			{
				$shouter = utf8_encode($row[$x]['username']);
				$shouter_link = append_sid(PROFILE_MG . '?mode=viewprofile&amp;u=' . $row[$x]['user_id']);
			}

			$shouter_color = colorize_username($row[$x]['user_id'], false, true);
			/*
			$shouter = colorize_username($row[$x]['user_id']);
			$shouter = preg_replace(array('<', '>'), array('mg_tag_open', 'mg_tag_close'), $shouter);
			$shouter_link = '-1';
			*/

			//$message = stripslashes($row[$x]['shout_text']);
			//$message = utf8_encode($row[$x]['shout_text']);
			$message = $row[$x]['shout_text'];

			// Word Censor.
			$message = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $message) : $message;

			//$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
			// Forced HTML to false to avoid problems
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
			/*
			$bbcode->allow_html = true;
			$bbcode->allow_bbcode = true;
			$bbcode->allow_smilies = true;
			*/
			$message = $bbcode->parse($message);

			//$message = preg_replace(array('<', '>'), array('mg_tag_open', 'mg_tag_close'), $message);

			$template->assign_block_vars('shouts', array(
				'ID' => $id,
				'SHOUTER' => $shouter,
				'SHOUTER_COLOR' => $shouter_color,
				'SHOUTER_LINK' => $shouter_link,
				'MESSAGE' => $message,
				'DATE' => $time
				)
			);
		}
	}
	// Code for sending data
	elseif ($action == 'add')
	{
		$shouter = trim($_POST['nm']); //name from the form in index.html
		$message = trim($_POST['co']); //comment from the form in index.html
		$shout_time = time();

		// Flood Control
		$sql = "SELECT MAX(shout_time) AS last_shout
				FROM " . AJAX_SHOUTBOX_TABLE . "
				WHERE shouter_ip = '" . $user_ip . "'";

		if ($result = $db->sql_query($sql))
		{
			if ($row = $db->sql_fetchrow($result))
			{
				if (($shout_time - intval($row['last_shout'])) < $board_config['shoutbox_floodinterval'])
				{
					// Display error
					$error = AJAX_SHOUTBOX_ERROR;
					pseudo_die(SHOUTBOX_ERROR, $lang['Shoutbox_flooderror']);
				}
			}
		}

		// Some weird conversion of the data inputed
		if($userdata['session_logged_in'])
		{
			$shouter = '';
			//$shouter = colorize_username($userdata['user_id']);
		}
		else
		{
			// Stop guest shouts if they are not allowed
			if ($board_config['shout_allow_guest'] != 1)
			{
				pseudo_die(SHOUTBOX_ERROR, $lang['Shoutbox_no_auth']);
			}

			if ($shouter == '')
			{
				$shouter = $lang['Guest'];
			}
			else
			{
				$shouter = strip_tags(stripslashes($shouter));
				$shouter = str_replace("'", "''", $shouter);

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

		$message = strip_tags(stripslashes($message));
		$message = str_replace("'", "''", $message);

		// we don't want users shouting images so we take them out before parsing the bbcodes
		//$message = ereg_replace("\\[img\\]([^\[]*)\\[/img\\]", '', $message);

		/*
		// The message is cut of after 500 letters
		if (strlen($message) > 500)
		{
			$message = substr($message, 0, 500);
		}
		*/

		//$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
		// Forced HTML to false to avoid problems
		$bbcode->allow_html = false;
		$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
		$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
		//$message = addslashes($bbcode->parse($message));
		$message = $bbcode->parse($message);
		$message = str_replace('http://', 'http:_/_/', $message);
		$message = str_replace('www.', 'http:_/_/www.', $message);
		$message = str_replace('http:_/_/http:_/_/', 'http:_/_/', $message);

		// Only if a name and a message have been provides the information is added to the db
		if ($message != '')
		{
			// Add new data
			$sql = "INSERT INTO " . AJAX_SHOUTBOX_TABLE . " (user_id, shouter_name, shout_text, shouter_ip, shout_time) VALUES (" . $userdata['user_id'] . ", '" . $shouter . "', '" . $message . "', '" . $user_ip . "', " . $shout_time . ")";

			if(!($results = $db->sql_query($sql)))
			{
				/*
				$error = AJAX_SHOUTBOX_ERROR;
				$error_msg = $lang['Shoutbox_unable'];
				$template->pparse('xml');
				*/
				pseudo_die(SHOUTBOX_ERROR, $lang['Shoutbox_unable']);
			}

			// Only do this if there is a limit.
			if($board_config['stored_shouts'] > 1)
			{
				$limit = $board_config['stored_shouts'] - 1;
				// Keep the database with the selected number of entrys.
				$sql = "SELECT shout_id
						FROM " . AJAX_SHOUTBOX_TABLE . "
						ORDER BY shout_id DESC
						LIMIT " . $limit . ", 1";

				$results = $db->sql_query($sql);
				$row = $db->sql_fetchrowset($results);
				$id = $row[0]['shout_id'];

				if ($id)
				{
					// Delete all message prior to a certain id
					$sql = "DELETE FROM " . AJAX_SHOUTBOX_TABLE . "
							WHERE shout_id < " . $id;
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
	// Code for Deleting Data
	elseif ($action == 'del')
	{
		if(($userdata['user_level'] == ADMIN) && ($userdata['session_logged_in']))
		{
			$shout_id = intval($_POST['sh']);

			$sql = 'DELETE FROM ' . AJAX_SHOUTBOX_TABLE . '
					WHERE shout_id =' . $shout_id;

			if(!($result = $db->sql_query($sql)))
			{
				$error = AJAX_SHOUTBOX_ERROR;
				$error_msg = $lang['Shoutbox_unable'];
			}
		}
	}
	pseudo_die($error, $error_msg);
}
// We're Out of PhpBB so call the Simple header and parser
if ($shoutbox_template_parse == true)
{
	$page_title = $lang['Ajax_Chat'];
	$meta_description = '';
	$meta_keywords = '';
	$gen_simple_header = true;
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
}

// Load templates
$template->set_filenames(array('shoutbox' => 'ajax_shoutbox_body.tpl'));

// Use special dimensions to the else use default.
if(($_GET['width'] > 0) && ($_GET['height'] > 0))
{
	$shoutbox_width = intval($_GET['width']);
	$shoutbox_height = intval($_GET['height']);
}
else
{
	$shoutbox_width = (!$shoutbox_width) ? 710 : intval($shoutbox_width);
	$shoutbox_height = (!$shoutbox_height) ? 350 : intval($shoutbox_height);
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
	'L_WIO' => $lang['Who_is_Chatting'],
	'L_GUESTS' =>  $lang['Online_guests'],
	'L_TOTAL' => $lang['Online_total'],
	'L_USERS' => $lang['Online_registered'],
	'L_TOP_SHOUTERS' => $lang['Top_Ten_Shouters'],
	'L_SHOUTBOX_ONLINE_EXPLAIN' => $lang['Shoutbox_online_explain'],
	'U_ARCHIVE' => append_sid('ajax_chat.' . PHP_EXT . '?mode=archive')
	)
);

if($board_config['shout_allow_guest'] > 0)
{
	// Guest and Users may see the shoutbox
	$template->assign_block_vars('view_shoutbox', array(
		'BOX_WIDTH' => $shoutbox_width,
		'BOX_HEIGHT' => $shoutbox_height,
		'DIV_WIDTH' => $shoutbox_div_width,
		'DIV_HEIGHT' => $shoutbox_div_height,
		'TABLE_WIDTH' => $shoutbox_table_width,
		'TABLE_HEIGHT' => $shoutbox_table_height,
		'REFRESH_TIME' => $board_config['shoutbox_refreshtime'],
		'U_ACTION' => append_sid(IP_ROOT_PATH . 'ajax_shoutbox.' . PHP_EXT)
		)
	);
	if($board_config['shout_allow_guest'] == 1)
	{
		// Guest and users may shout.
		$template->assign_block_vars('view_shoutbox.shout_allowed', array());
		if(!($userdata['session_logged_in']))
		{
			// Only guests need to enter a username
			$template->assign_block_vars('view_shoutbox.shout_allowed.guest_shouter', array());
		}
	}
	else
	{
		// Only registered users may shout.
		if($userdata['session_logged_in'])
		{
			$template->assign_block_vars('view_shoutbox.shout_allowed', array());
		}
	}
}
else
{
	// Only registered users may see/shout
	if($userdata['session_logged_in'])
	{
		$template->assign_block_vars('view_shoutbox', array(
			'BOX_WIDTH' => $shoutbox_width,
			'BOX_HEIGHT' => $shoutbox_height,
			'DIV_WIDTH' => $shoutbox_div_width,
			'DIV_HEIGHT' => $shoutbox_div_height,
			'TABLE_WIDTH' => $shoutbox_table_width,
			'TABLE_HEIGHT' => $shoutbox_table_height,
			'REFRESH_TIME' => $board_config['shoutbox_refreshtime'],
			'U_ACTION' => append_sid(IP_ROOT_PATH . 'ajax_shoutbox.' . PHP_EXT)
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

if($userdata['user_level'] == ADMIN)
{
	$template->assign_block_vars('view_shoutbox.user_is_admin', array());
}

// BBCBMG - BEGIN
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcb_mg_small.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG_SMALL', 'bbcb_mg_small');
// BBCBMG - END

if($shoutbox_template_parse)
{
	$template->pparse('shoutbox');
	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}
else
{
	$template->assign_var_from_handle('SHOUTBOX_BODY', 'shoutbox');
}

?>