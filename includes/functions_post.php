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

$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

// This function will prepare a posted message for entry into the database.
function prepare_message($message, $html_on, $bbcode_on, $smile_on)
{
	global $board_config, $html_entities_match, $html_entities_replace;

	// Clean up the message
	$message = trim($message);

	if ($html_on)
	{
		// If HTML is on, we try to make it safe
		// This approach is quite agressive and anything that does not look like a valid tag
		// is going to get converted to HTML entities
		$message = stripslashes($message);
		$html_match = '#<[^\w<]*(\w+)((?:"[^"]*"|\'[^\']*\'|[^<>\'"])+)?>#';
		$matches = array();

		$message_split = preg_split($html_match, $message);
		preg_match_all($html_match, $message, $matches);

		$message = '';

		foreach ($message_split as $part)
		{
			$tag = array(array_shift($matches[0]), array_shift($matches[1]), array_shift($matches[2]));
			$message .= preg_replace($html_entities_match, $html_entities_replace, $part) . clean_html($tag);
			//$message .= preg_replace($html_entities_match, $html_entities_replace, $part) . $tag;
		}

		$message = addslashes($message);
		$message = str_replace('&quot;', '\&quot;', $message);
	}
	else
	{
		$message = preg_replace($html_entities_match, $html_entities_replace, $message);
	}

	return $message;
}

function unprepare_message($message)
{
	global $unhtml_specialchars_match, $unhtml_specialchars_replace;

	return preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, $message);
}

// Prepare a message for posting
function prepare_post(&$mode, &$post_data, &$bbcode_on, &$html_on, &$smilies_on, &$error_msg, &$username, &$subject, &$message, &$poll_title, &$poll_options, &$poll_length, &$reg_active, &$reg_reset, &$reg_max_option1, &$reg_max_option2, &$reg_max_option3, &$reg_length, &$topic_desc, $topic_calendar_time = 0, $topic_calendar_duration = 0)
{
	global $board_config, $userdata, $lang;
	global $topic_id;
	global $db;

	// Check username
	if (!empty($username))
	{
		$username = phpbb_clean_username($username);

		if (!$userdata['session_logged_in'] || ($userdata['session_logged_in'] && ($username != $userdata['username'])))
		{
			include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

			$result = validate_username($username);
			if ($result['error'])
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];
			}
		}
		else
		{
			$username = '';
		}
	}

	// Check subject
	if (!empty($subject))
	{
		$subject = htmlspecialchars(trim($subject));
	}
	elseif ($mode == 'newtopic' || ($mode == 'editpost' && $post_data['first_post']))
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_subject'] : $lang['Empty_subject'];
	}
	// Check Topic Desciption
	if ( !empty($topic_desc) )
	{
		$topic_desc = htmlspecialchars(trim($topic_desc));
	}

	// Check message
	if (!empty($message))
	{
		$message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on);
		// Mighty Gorgon - TO BE VERIFIED
		//$message = addslashes($message);
		// Mighty Gorgon - TO BE VERIFIED
	}
	elseif (($mode != 'delete') && ($mode != 'poll_delete'))
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_message'] : $lang['Empty_message'];
	}
	// check calendar date
	if ((!empty($topic_calendar_time)) && (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post'])))
	{
		$year = intval(date('Y', $topic_calendar_time));
		$month = intval(date('m', $topic_calendar_time));
		$day = intval(date('d', $topic_calendar_time));
		if (!checkdate($month, $day, $year))
		{
			$error_msg .= (!empty($error_msg) ? '<br />' : '') . sprintf($lang['Date_error'], $day, $month, $year);
		}
	}

	// Check to see if there's a new post while the user is posting
	$new_post_while_posting = false;
	if(!empty($_POST['post_time']) && (($mode == 'reply') || ($mode == 'quote')) && $board_config['show_new_reply_posting'])
	{
		$last_post_time = intval($_POST['post_time']);

		if(isset($topic_id) && $last_post_time)
		{
			$sql = "SELECT post_time FROM " . POSTS_TABLE . " WHERE topic_id = '" . $topic_id . "' ORDER BY post_time DESC LIMIT 0, 1";
			if(($result = $db->sql_query($sql)) !== false)
			{
				if($row = $db->sql_fetchrow($result))
				{
					$last_post_time2 = $row['post_time'];
					if($last_post_time2 > $last_post_time)
					{
						$new_post_while_posting = true;
						$error_msg .= (empty($error_msg) ? '' : '<br />') . $lang['Warn_new_post'];
					}
				}
				$db->sql_freeresult($result);
			}
		}
	}

	// Check to see if the user is last poster and is bumping
	//if(($mode == 'reply' || $mode == 'quote') && ($board_config['no_bump'] == true) && ($new_post_while_posting == false))
	$no_bump = ((($board_config['no_bump'] == 1) && ($userdata['user_level'] != ADMIN)) || (($board_config['no_bump'] == 2) && ($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))) ? true : false;
	if((($mode == 'reply') || ($mode == 'quote')) && ($no_bump == true) && ($new_post_while_posting == false))
	{
		if(isset($topic_id))
		{
			$sql = "SELECT poster_id FROM " . POSTS_TABLE . "
							WHERE topic_id = '" . $topic_id . "'
							AND post_time > " . (time() - 86400) . "
							ORDER BY post_time DESC
							LIMIT 0, 1";
			if(($result = $db->sql_query($sql)) !== false)
			{
				if($row = $db->sql_fetchrow($result))
				{
					if($row['poster_id'] == $userdata['user_id'])
					{
						$error_msg .= (empty($error_msg) ? '' : '<br />') . $lang['WARN_NO_BUMP'];
					}
				}
				$db->sql_freeresult($result);
			}
		}
	}

	// Handle poll stuff
	if (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post']))
	{
		$poll_length = (isset($poll_length)) ? max(0, intval($poll_length)) : 0;

		if (!empty($poll_title))
		{
			$poll_title = htmlspecialchars(trim($poll_title));
		}

		if(!empty($poll_options))
		{
			$temp_option_text = array();
			while(list($option_id, $option_text) = @each($poll_options))
			{
				$option_text = trim($option_text);
				if (!empty($option_text))
				{
					$temp_option_text[intval($option_id)] = htmlspecialchars($option_text);
				}
			}
			$option_text = $temp_option_text;

			if (count($poll_options) < 2)
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['To_few_poll_options'] : $lang['To_few_poll_options'];
			}
			elseif (count($poll_options) > $board_config['max_poll_options'])
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['To_many_poll_options'] : $lang['To_many_poll_options'];
			}
			elseif ($poll_title == '')
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_poll_title'] : $lang['Empty_poll_title'];
			}
		}

		// Event Registration - BEGIN
		$reg_active = (isset($reg_active)) ? max(0, intval($reg_active)) : 0;
		$reg_max_option1 = (isset($reg_max_option1)) ? max(0, intval($reg_max_option1)) : 0;
		$reg_max_option2 = (isset($reg_max_option2)) ? max(0, intval($reg_max_option2)) : 0;
		$reg_max_option3 = (isset($reg_max_option3)) ? max(0, intval($reg_max_option3)) : 0;
		$reg_length = (isset($reg_length)) ? max(0, intval($reg_length)) : 0;
		// Event Registration - END
	}
	return;
}

// Post a new topic/reply/poll or edit existing post/poll
function submit_post($mode, &$post_data, &$message, &$meta, &$forum_id, &$topic_id, &$post_id, &$poll_id, &$topic_type, &$bbcode_on, &$html_on, &$acro_auto_on, &$smilies_on, &$attach_sig, $post_username, $post_subject, $post_message, $poll_title, &$poll_options, &$poll_length, &$reg_active, &$reg_reset, &$reg_max_option1, &$reg_max_option2, &$reg_max_option3, &$reg_length, &$news_category, &$topic_show_portal, &$mark_edit, &$topic_desc, $topic_calendar_time = 0, $topic_calendar_duration = 0)
{
	global $board_config, $lang, $db;
	global $userdata, $user_ip, $post_data;
	// CrackerTracker v5.x
	global $ctracker_config;

	if ((($mode == 'newtopic') || ($mode == 'reply')) && (($ctracker_config->settings['spammer_blockmode'] > 0) || ($ctracker_config->settings['spam_attack_boost'] == 1)) && ($userdata['user_level'] != ANONYMOUS))
	{
		include_once(IP_ROOT_PATH . 'ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
		$login_functions = new ct_userfunctions();
		$login_functions->handle_postings();
		unset($login_functions);
	}
	// CrackerTracker v5.x
	// BEGIN cmx_slash_news_mod
	if(isset($news_category) && is_numeric($news_category))
	{
		$news_id = intval($news_category);
		//$topic_type = POST_NEWS;
	}
	else
	{
		$news_id = 0;
	}
	// END cmx_slash_news_mod

	include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

	$current_time = time();

	if (($userdata['user_level'] != ADMIN) && (($board_config['force_large_caps_mods'] == true) || ($userdata['user_level'] != MOD)))
	{
		//$post_subject = strtolower($post_subject);
		$post_subject = ucwords($post_subject);
	}

	if (($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))
	{
		// Flood control
		$where_sql = ($userdata['user_id'] == ANONYMOUS) ? "poster_ip = '$user_ip'" : 'poster_id = ' . $userdata['user_id'];
		$sql = "SELECT MAX(post_time) AS last_post_time
			FROM " . POSTS_TABLE . "
			WHERE $where_sql";
		if ($result = $db->sql_query($sql))
		{
			if ($row = $db->sql_fetchrow($result))
			{
				if (intval($row['last_post_time']) > 0 && ($current_time - intval($row['last_post_time'])) < intval($board_config['flood_interval']))
				{
					message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
				}
			}
		}
	}

	if ($mode == 'editpost')
	{
		remove_search_post($post_id);
	}

	if (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post']))
	{
		$topic_vote = (!empty($poll_title) && count($poll_options) >= 2) ? 1 : 0;
		$topic_show_portal = ( $topic_show_portal == true ) ? 1 : 0;
		$topic_calendar_duration = ( $topic_calendar_duration == '') ? 0 : $topic_calendar_duration;

		// Event Registration - BEGIN
		$topic_reg = 0;
		if ($reg_active == 1)
		{
			$topic_reg = 1;
		}
		// Event Registration - END

		$sql = ($mode != 'editpost') ? "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_desc, topic_poster, topic_time, forum_id, news_id, topic_status, topic_type, topic_calendar_time, topic_calendar_duration, topic_reg, topic_vote, topic_show_portal) VALUES ('$post_subject', '$topic_desc', " . $userdata['user_id'] . ", $current_time, $forum_id, $news_id, " . TOPIC_UNLOCKED . ", $topic_type, $topic_calendar_time, $topic_calendar_duration, $topic_reg, $topic_vote, $topic_show_portal)" : "UPDATE " . TOPICS_TABLE . " SET topic_title = '$post_subject', news_id = $news_id, topic_desc = '$topic_desc', topic_type = $topic_type, topic_calendar_time = $topic_calendar_time, topic_calendar_duration = $topic_calendar_duration, topic_reg = $topic_reg" . (($post_data['edit_vote'] || !empty($poll_title)) ? ", topic_vote = " . $topic_vote : "") . ", topic_show_portal = $topic_show_portal
		WHERE topic_id = $topic_id";

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}

		if ($mode == 'newtopic')
		{
			$topic_id = $db->sql_nextid();
		}
		else
		{
			// Event Registration - BEGIN
			if ($reg_reset)
			{
				$sql = "DELETE FROM " . REGISTRATION_TABLE . " WHERE topic_id = $topic_id";

				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete registration data from table', '', __LINE__, __FILE__, $sql);
				}
			}
			// Event Registration - END
		}
	}

	// Event Registration - BEGIN
	if ((($mode == 'newtopic') || ($mode == 'editpost')) && ($topic_reg == 1))
	{
		if ($mode == 'editpost')
		{
			$sql = "SELECT count(1) chk_reg FROM " . REGISTRATION_DESC_TABLE . " WHERE topic_id = $topic_id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not check registration data for this topic', '', __LINE__, __FILE__, $sql);
			}
			$chk_reg = ($db->sql_fetchfield('chk_reg', 0, $result) != 0) ? true : false;
		}

		$sql = ($mode != 'editpost' || ($mode == 'editpost' && $chk_reg == false)) ? "INSERT INTO " . REGISTRATION_DESC_TABLE . " (topic_id, reg_active, reg_max_option1, reg_max_option2, reg_max_option3, reg_start, reg_length) VALUES ($topic_id, $reg_active, $reg_max_option1, $reg_max_option2, $reg_max_option3, $current_time, " . ($reg_length * 86400) . ")" : "UPDATE " . REGISTRATION_DESC_TABLE . " SET reg_active = $reg_active, reg_max_option1 = $reg_max_option1, reg_max_option2 = $reg_max_option2, reg_max_option3 = $reg_max_option3, reg_length = " . ($reg_length * 86400) . " WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not store registration data', '', __LINE__, __FILE__, $sql);
		}
	}
	// Event Registration - END

	// To show also admins modifications decomment this line!!!
	//if( ($userdata['user_level'] == ADMIN) && !$board_config['always_show_edit_by'] )
	if($userdata['user_level'] == ADMIN)
	{
		$edited_sql = '';
	}
	else
	{
		// Original phpBB "Edit By"
		//$edited_sql = ($mode == 'editpost' && !$post_data['last_post'] && $post_data['poster_post']) ? ", post_edit_time = $current_time, post_edit_count = post_edit_count + 1 " : "";

		$edited_sql = ", post_edit_time = '" . $current_time . "', post_edit_count = (post_edit_count + 1), post_edit_id = '" . $userdata['user_id'] . "' ";
		if ($board_config['always_show_edit_by'] == true)
		{
			$edited_sql = ($mode == 'editpost') ? $edited_sql : '';
		}
		else
		{
			$edited_sql = (($mode == 'editpost') && !$post_data['last_post']) ? $edited_sql : '';
		}
	}

	// Mighty Gorgon - This must be tested! - BEGIN
	/*
	if (get_magic_quotes_gpc() == false)
	{
		$post_message = addslashes($post_message);
	}
	*/
	// Mighty Gorgon - This must be tested! - END

	$sql = ($mode != 'editpost') ? "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_subject, post_text, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_sig, enable_autolinks_acronyms) VALUES ($topic_id, $forum_id, " . $userdata['user_id'] . ", '$post_username', '$post_subject', '$post_message', $current_time, '$user_ip', $bbcode_on, $html_on, $smilies_on, $attach_sig, $acro_auto_on)" : "UPDATE " . POSTS_TABLE . " SET post_username = '$post_username',  post_text = '$post_message', post_text_compiled = '', post_subject = '$post_subject', enable_bbcode = $bbcode_on, enable_html = $html_on, enable_smilies = $smilies_on, enable_sig = $attach_sig" . $edited_sql . ", enable_autolinks_acronyms = " . $acro_auto_on . " WHERE post_id = $post_id";
	if (!$db->sql_query($sql, BEGIN_TRANSACTION))
	{
		empty_cache_folders(POSTS_CACHE_FOLDER);
		message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
	}

	if ($mode != 'editpost')
	{
		$post_id = $db->sql_nextid();
	}

//<!-- BEGIN Unread Post Information to Database Mod -->
	if($board_config['upi2db_on'])
	{
		$mark_edit = ($userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD) ? $mark_edit : true;

		if(($mode != 'editpost') || ($mode == 'editpost' &&  $post_data['last_post'] && $board_config['upi2db_last_edit_as_new'] && $mark_edit) || ($mode == 'editpost' && !$post_data['last_post'] && $board_config['upi2db_edit_as_new'] && $mark_edit) || ($mode == 'reply'))
		{
			$sql = "SELECT post_id FROM " . UPI2DB_LAST_POSTS_TABLE . "
				WHERE post_id = " . $post_id;
			if ($result = $db->sql_query($sql))
			{
				$id_vorhanden = $db->sql_numrows($result);
			}
			$db->sql_freeresult($result);

			if ($id_vorhanden == 0)
			{
				$pt_or_pet = ($mode != 'editpost') ? "post_time" : "post_edit_time";
				$sql = "INSERT INTO " . UPI2DB_LAST_POSTS_TABLE . " (post_id, topic_id, forum_id, poster_id, " . $pt_or_pet . ", topic_type, post_edit_by) VALUES ('$post_id', '$topic_id', '$forum_id', '" . $userdata['user_id'] . "', '$current_time', '$topic_type', '" . $userdata['user_id'] . "')";
			}
			else
			{
				$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . " SET post_edit_time = '" . $current_time . "', topic_type = '" . $topic_type . "', post_edit_by = '" . $userdata['user_id'] . "' WHERE post_id = " . $post_id;
			}

			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error in posting (UPI2DB)', '', __LINE__, __FILE__, $sql);
			}
		}
		// Edited By Mighty Gorgon - BEGIN
		if (($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))
		{
			if(($topic_type == POST_STICKY) || ($topic_type == POST_ANNOUNCE) || ($topic_type == POST_GLOBAL_ANNOUNCE))
			{
				$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
					WHERE forum_id =  " . $forum_id;
				$db->sql_query($sql);
			}
		}
		// Edited By Mighty Gorgon - END
	}
//<!-- END Unread Post Information to Database Mod -->

	add_search_words('single', $post_id, stripslashes($post_message), stripslashes($post_subject));

	// DOWNLOADS - BEGIN
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_downloads.' . PHP_EXT);
	include(IP_ROOT_PATH . DL_ROOT_PATH . 'classes/class_dlmod.' . PHP_EXT);
	$dl_mod = new dlmod();
	$dl_config = $dl_mod->get_config();

	if ($dl_config['enable_post_dl_traffic'])
	{
		if (!$dl_config['delay_post_traffic'] || ((time() - $userdata['user_regdate']) / 84600) > $dl_config['delay_post_traffic'])
		{
			$dl_traffic = 0;
			if ($mode == 'newtopic')
			{
				$dl_traffic = $dl_config['newtopic_traffic'];
			}
			elseif (($mode == 'reply') || ($mode == 'quote'))
			{
				$dl_traffic = $dl_config['reply_traffic'];
			}

			if ($dl_traffic > 0)
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_traffic = user_traffic + $dl_traffic
					WHERE user_id = " . $userdata['user_id'];
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not add download traffic to user account', '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	// DOWNLOADS - END

	// Add poll
	if ((($mode == 'newtopic') || (($mode == 'editpost') && $post_data['edit_poll'])) && !empty($poll_title) && (count($poll_options) >= 2))
	{
		$sql = (!$post_data['has_poll']) ? "INSERT INTO " . VOTE_DESC_TABLE . " (topic_id, vote_text, vote_start, vote_length) VALUES ($topic_id, '$poll_title', $current_time, " . ($poll_length * 86400) . ")" : "UPDATE " . VOTE_DESC_TABLE . " SET vote_text = '$poll_title', vote_length = " . ($poll_length * 86400) . " WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}

		$delete_option_sql = '';
		$old_poll_result = array();
		if (($mode == 'editpost') && $post_data['has_poll'])
		{
			$sql = "SELECT vote_option_id, vote_result
				FROM " . VOTE_RESULTS_TABLE . "
				WHERE vote_id = $poll_id
				ORDER BY vote_option_id ASC";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain vote data results for this topic', '', __LINE__, __FILE__, $sql);
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$old_poll_result[$row['vote_option_id']] = $row['vote_result'];

				if (!isset($poll_options[$row['vote_option_id']]))
				{
					$delete_option_sql .= ($delete_option_sql != '') ? ', ' . $row['vote_option_id'] : $row['vote_option_id'];
				}
			}
		}
		else
		{
			$poll_id = $db->sql_nextid();
		}

		@reset($poll_options);

		$poll_option_id = 1;
		while (list($option_id, $option_text) = each($poll_options))
		{
			if (!empty($option_text))
			{
				$option_text = str_replace("\'", "''", htmlspecialchars($option_text));
				$poll_result = (($mode == 'editpost') && isset($old_poll_result[$option_id])) ? $old_poll_result[$option_id] : 0;

				$sql = (($mode != 'editpost') || !isset($old_poll_result[$option_id])) ? "INSERT INTO " . VOTE_RESULTS_TABLE . " (vote_id, vote_option_id, vote_option_text, vote_result) VALUES ($poll_id, $poll_option_id, '$option_text', $poll_result)" : "UPDATE " . VOTE_RESULTS_TABLE . " SET vote_option_text = '$option_text', vote_result = $poll_result WHERE vote_option_id = $option_id AND vote_id = $poll_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
				}
				$poll_option_id++;
			}
		}

		if ($delete_option_sql != '')
		{
			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . "
				WHERE vote_option_id IN ($delete_option_sql)
					AND vote_id = $poll_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error deleting pruned poll options', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	empty_cache_folders(POSTS_CACHE_FOLDER);
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	board_stats();
	cache_tree(true);

	$cash_string = '';
	// MG Cash MOD For IP - BEGIN
	if (defined('CASH_MOD'))
	{
		$cash_message = $GLOBALS['cm_posting']->update_post($mode, $post_data, $forum_id, $topic_id, $post_id, $topic_type, $post_username, $post_message);
		$cash_string = '<br />' . $cash_message;
	}
	// MG Cash MOD For IP - END
	$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '">';
	$message = $lang['Stored'] . $cash_string . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

	return false;
}

// Save Draft
function save_draft($draft_id, $user_id, $forum_id, $topic_id, $subject, $message)
{
	global $lang, $db;

	if ($draft_id == 0)
	{
		$sql = "INSERT INTO " . DRAFTS_TABLE . " (user_id, forum_id, topic_id, save_time, draft_subject, draft_message) VALUES ('$user_id', '$forum_id', '$topic_id', '" . time() . "', '$subject', '$message')";
	}
	else
	{
		$sql = "UPDATE " . DRAFTS_TABLE . " SET user_id = '$user_id', forum_id = '$forum_id', topic_id = '$topic_id', save_time =  '" . time() . "', draft_subject = '$subject', draft_message = '$message' WHERE draft_id = '" . $draft_id . "'";
	}

	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
	}
	return true;
}

//
// Update post stats and details
//
function update_post_stats(&$mode, &$post_data, &$forum_id, &$topic_id, &$post_id, &$user_id)
{
	global $db, $board_config;

	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$sign = ($mode == 'delete') ? '- 1' : '+ 1';
	$forum_update_sql = "forum_posts = forum_posts $sign";
	$topic_update_sql = '';

	if ($mode == 'delete')
	{
		if ($post_data['last_post'])
		{
			if ($post_data['first_post'])
			{
				$forum_update_sql .= ', forum_topics = forum_topics - 1';
			}
			else
			{

				$topic_update_sql .= 'topic_replies = topic_replies - 1';

				$sql = "SELECT MAX(post_id) AS last_post_id
					FROM " . POSTS_TABLE . "
					WHERE topic_id = $topic_id";
				if (!($result = $db->sql_query($sql)))
				{
					empty_cache_folders(POSTS_CACHE_FOLDER);
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$topic_update_sql .= ', topic_last_post_id = ' . $row['last_post_id'];
				}
			}

			if ($post_data['last_topic'])
			{
				$sql = "SELECT MAX(post_id) AS last_post_id
					FROM " . POSTS_TABLE . "
					WHERE forum_id = $forum_id";
				if (!($result = $db->sql_query($sql)))
				{
					empty_cache_folders(POSTS_CACHE_FOLDER);
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$forum_update_sql .= ($row['last_post_id']) ? ', forum_last_post_id = ' . $row['last_post_id'] : ', forum_last_post_id = 0';
				}
			}
		}
		elseif ($post_data['first_post'])
		{
			$sql = "SELECT MIN(post_id) AS first_post_id
				FROM " . POSTS_TABLE . "
				WHERE topic_id = $topic_id";
			if (!($result = $db->sql_query($sql)))
			{
				empty_cache_folders(POSTS_CACHE_FOLDER);
				message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$topic_update_sql .= 'topic_replies = topic_replies - 1, topic_first_post_id = ' . $row['first_post_id'];
			}
		}
		else
		{
			$topic_update_sql .= 'topic_replies = topic_replies - 1';
		}
	}
	elseif ($mode != 'poll_delete')
	{
		$forum_update_sql .= ", forum_last_post_id = $post_id" . (($mode == 'newtopic') ? ", forum_topics = forum_topics $sign" : "");
		$topic_update_sql = "topic_last_post_id = $post_id" . (($mode == 'reply') ? ", topic_replies = topic_replies $sign" : ", topic_first_post_id = $post_id");
	}
	else
	{
		$topic_update_sql .= 'topic_vote = 0';
	}

	if ($mode != 'poll_delete')
	{
		$sql = "UPDATE " . FORUMS_TABLE . "
			SET $forum_update_sql
			WHERE forum_id = $forum_id";
		if (!$db->sql_query($sql))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($topic_update_sql != '')
	{
		$sql = "UPDATE " . TOPICS_TABLE . "
			SET $topic_update_sql
			WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($mode != 'poll_delete')
	{
		// Disable Post count - BEGIN
		$sql = "SELECT forum_postcount
			FROM " . FORUMS_TABLE . "
			WHERE forum_id = $forum_id AND forum_postcount = 0";
		if (!($result = $db->sql_query($sql)))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
		}
		if ($row = $db->sql_fetchrow($result))
		{
			return;
		}
		// Disable Post count - END

		$sql = "UPDATE " . USERS_TABLE . "
			SET user_posts = user_posts $sign
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql, END_TRANSACTION))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
	}
	if ($board_config['site_history'] == '1')
	{
		$current_time=time();
		$minutes = date('is', $current_time);
		$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
		$sql='UPDATE ' . SITE_HISTORY_TABLE . ' SET '. (($mode == 'newtopic' || $post_data['first_post']) ? 'new_topics=new_topics' : 'new_posts=new_posts') . $sign . ' WHERE date=' . $hour_now;
		if ( !$db->sql_query($sql) || !$db->sql_affectedrows() )
		{
			$sql = 'INSERT IGNORE INTO ' . SITE_HISTORY_TABLE . ' (date, '.(($mode == 'newtopic' || $post_data['first_post']) ? 'new_topics' : 'new_posts').')
				VALUES (' . $hour_now . ', "1")';
			if ( !($db->sql_query($sql)) )
			{
				message_die(CRITICAL_ERROR, 'Error createing new site_hitory'.$sql, '', __LINE__, __FILE__, $sql);
			}
		}
	}

	empty_cache_folders(POSTS_CACHE_FOLDER);
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	board_stats();
	cache_tree(true);

	$sql = "SELECT ug.user_id, g.group_id as g_id, u.user_posts, u.user_color_group, u.user_color, g.group_count, g.group_color, g.group_count_max FROM (" . GROUPS_TABLE . " g, " . USERS_TABLE . " u)
			LEFT JOIN ". USER_GROUP_TABLE." ug ON g.group_id = ug.group_id AND ug.user_id = '" . $user_id . "'
			WHERE u.user_id = '" . $user_id . "'
			AND g.group_single_user = '0'
			AND g.group_count_enable = '1'
			AND g.group_moderator <> '" . $user_id . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting users post stat', '', __LINE__, __FILE__, $sql);
	}
	while ($group_data = $db->sql_fetchrow($result))
	{
		$user_already_added = (empty($group_data['user_id'])) ? false : true;
		$user_add = (($group_data['group_count'] == $group_data['user_posts']) && ($user_id != ANONYMOUS)) ? true : false;
		$user_remove = ($group_data['group_count'] > $group_data['user_posts'] || $group_data['group_count_max'] < $group_data['user_posts']) ? true : false;
		if ($user_add && !$user_already_added)
		{
			update_user_color($user_id, $group_data['group_color'], $group_data['g_id'], false, false);
			empty_cache_folders(USERS_CACHE_FOLDER);
			//user join a autogroup
			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
				VALUES (" . $group_data['g_id'] . ", $user_id, '0')";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error insert users, group count', '', __LINE__, __FILE__, $sql);
			}
		}
		elseif ($user_already_added && $user_remove)
		{
			update_user_color($user_id, $board_config['active_users_color'], 0);
			empty_cache_folders(USERS_CACHE_FOLDER);
			//remove user from auto group
			$sql = "DELETE FROM " . USER_GROUP_TABLE . "
				WHERE group_id = '" . $group_data['g_id'] . "'
				AND user_id = '" . $user_id . "'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not remove users, group count', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	empty_cache_folders(POSTS_CACHE_FOLDER);
	return;
}

//
// Delete a post/poll
//
function delete_post($mode, &$post_data, &$message, &$meta, &$forum_id, &$topic_id, &$post_id, &$poll_id)
{
	global $board_config, $lang, $db;
	global $userdata, $user_ip;

	if ($mode != 'poll_delete')
	{
		// MG Cash MOD For IP - BEGIN
		if ( defined('CASH_MOD') )
		{
			$GLOBALS['cm_posting']->update_delete($mode, $post_data, $forum_id, $topic_id, $post_id);
		}
		// MG Cash MOD For IP - END
		include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

		$sql = "DELETE FROM " . POSTS_TABLE . "
			WHERE post_id = $post_id";
		if (!$db->sql_query($sql))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
		}

		// Event Registration - BEGIN
		if ($post_data['first_post'])
		{
			$sql = "DELETE FROM " . REGISTRATION_TABLE . " WHERE topic_id = $topic_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete registration entries from table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . REGISTRATION_DESC_TABLE . " WHERE topic_id = $topic_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete registration data from table', '', __LINE__, __FILE__, $sql);
			}
		}
		// Event Registration - END

//<!-- BEGIN Unread Post Information to Database Mod -->
		$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
			WHERE post_id = $post_id";
		if (!$db->sql_query($sql))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in deleting post (UPI2DB)', '', __LINE__, __FILE__, $sql);
		}
		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE post_id = $post_id";
		if (!$db->sql_query($sql))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			message_die(GENERAL_ERROR, 'Error in deleting post (UPI2DB)', '', __LINE__, __FILE__, $sql);
		}
//<!-- END Unread Post Information to Database Mod -->

		if ($post_data['last_post'])
		{
			if ($post_data['first_post'])
			{
				$forum_update_sql .= ', forum_topics = forum_topics - 1';
				$sql = "DELETE FROM " . TOPICS_TABLE . "
					WHERE topic_id = $topic_id
						OR topic_moved_id = $topic_id";
				if (!$db->sql_query($sql))
				{
					empty_cache_folders(POSTS_CACHE_FOLDER);
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . THANKS_TABLE . "
				WHERE topic_id = $topic_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in deleting Thanks post Information', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
					WHERE topic_id = $topic_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . BOOKMARK_TABLE . "
					WHERE topic_id = $topic_id";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		empty_cache_folders(POSTS_CACHE_FOLDER);
		empty_cache_folders(FORUMS_CACHE_FOLDER);
		remove_search_post($post_id);
	}

	if ($mode == 'poll_delete' || ($mode == 'delete' && $post_data['first_post'] && $post_data['last_post']) && $post_data['has_poll'] && $post_data['edit_poll'])
	{
		$sql = "DELETE FROM " . VOTE_DESC_TABLE . "
			WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting poll', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . "
			WHERE vote_id = $poll_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting poll', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . VOTE_USERS_TABLE . "
			WHERE vote_id = $poll_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting poll', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($mode == 'delete' && $post_data['first_post'] && $post_data['last_post'])
	{
		$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) . '">';
		$message = $lang['Deleted'];
	}
	else
	{
		$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">';
		$message = (($mode == 'poll_delete') ? $lang['Poll_delete'] : $lang['Deleted']) . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>');
	}

	$message .=  '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

	empty_cache_folders(POSTS_CACHE_FOLDER);
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	board_stats();
	cache_tree(true);

	return;
}

function get_userdata_notifications($user, $force_str = false)
{
	global $db;

	if (!is_numeric($user) || $force_str)
	{
		$user = phpbb_clean_username($user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = "SELECT *
			FROM " . USERS_TABLE . "
			WHERE ";
	$sql .= ((is_integer($user)) ? "user_id = $user" : "username = '" . str_replace("\'", "''", $user) . "'") . " AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
	}
	$return_value = ($row = $db->sql_fetchrow($result)) ? $row : false;
	$db->sql_freeresult($result);
	return $return_value;
}

// Start replacement - Forum notification MOD
//
// Handle user notification on new post (including forum notification)
//
function user_notification($mode, &$post_data, &$topic_title, &$forum_id, &$topic_id, &$post_id, &$notify_user)
{
	global $board_config, $lang, $db;
	global $userdata, $user_ip;
	global $bbcode;
	$current_time = time();
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

	if ($mode != 'delete')
	{
		if ($mode == 'reply')
		{
			// One row SQL for caching purpose...
			$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid <> 0 ORDER BY ban_userid ASC";
			if (!($result = $db->sql_query($sql, false, 'ban_', USERS_CACHE_FOLDER)))
			{
				message_die(GENERAL_ERROR, "Could not obtain banned users information.", '', __LINE__, __FILE__, $sql);
			}
			$user_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				if (isset($row['ban_userid']) && !empty($row['ban_userid']))
				{
					$user_id_sql .= ', ' . $row['ban_userid'];
				}
			}

			$sql = "SELECT u.user_id, u.user_email, u.user_lang, u.username, f.forum_name
				FROM " . TOPICS_WATCH_TABLE . " tw, " . USERS_TABLE . " u, " . FORUMS_TABLE . " f
				WHERE tw.topic_id = $topic_id
					AND tw.user_id NOT IN (" . $userdata['user_id'] . ", " . ANONYMOUS . $user_id_sql . ")
					AND tw.notify_status = " . TOPIC_WATCH_UN_NOTIFIED . "
					AND f.forum_id = $forum_id
					AND u.user_id = tw.user_id
					AND u.user_active = 1";
			if (!($result = $db->sql_query($sql)))
			{
				empty_cache_folders(POSTS_CACHE_FOLDER);
				message_die(GENERAL_ERROR, 'Could not obtain list of topic watchers', '', __LINE__, __FILE__, $sql);
			}

			$update_watched_sql = '';
			$bcc_list_ary = array();
			$users_ary = array();

			if ($row = $db->sql_fetchrow($result))
			{
				// Sixty second limit
				@set_time_limit(60);

				do
				{
					if ($row['user_email'] != '')
					{
						$bcc_list_ary[$row['user_lang']][] = $row['user_email'];
						$users_ary[$row['user_email']] = $row['username'];
					}
					$forum_name = $row['forum_name'];
					$update_watched_sql .= ($update_watched_sql != '') ? ', ' . $row['user_id'] : $row['user_id'];
				}
				while ($row = $db->sql_fetchrow($result));

				//
				// Let's do some checking to make sure that mass mail functions
				// are working in win32 versions of php.
				//
				if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
				{
					$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

					// We are running on windows, force delivery to use our smtp functions
					// since php's are broken by default
					$board_config['smtp_delivery'] = 1;
					$board_config['smtp_host'] = @$ini_val('SMTP');
				}

				if (count($bcc_list_ary))
				{
					include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
					$emailer = new emailer($board_config['smtp_delivery']);
					$server_url = create_server_url();

					$orig_word = array();
					$replacement_word = array();
					obtain_word_list($orig_word, $replacement_word);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$topic_title = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, unprepare_message($topic_title)) : unprepare_message($topic_title);
					$post_text = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, unprepare_message($post_data['message'])) : unprepare_message($post_data['message']);

					if ($board_config['html_email'] == true)
					{
						$bbcode->allow_bbcode = ($board_config['allow_bbcode'] ? $board_config['allow_bbcode'] : false);
						$bbcode->allow_html = ($board_config['allow_html'] ? $board_config['allow_html'] : false);
						$bbcode->allow_smilies = ($board_config['allow_smilies'] ? $board_config['allow_smilies'] : false);
						$post_text = $bbcode->parse($post_text);
						$post_text = stripslashes($post_text);
					}
					else
					{
						$post_text = $bbcode->plain_message($post_text, '');
					}

					@reset($bcc_list_ary);
					while (list($user_lang, $bcc_list) = each($bcc_list_ary))
					{
						$emailer->use_template('topic_notify', $user_lang);

						for ($i = 0; $i < count($bcc_list); $i++)
						{
							$emailer->bcc($bcc_list[$i]);
						}

						// The Topic_reply_notification lang string below will be used
						// if for some reason the mail template subject cannot be read
						// ... note it will not necessarily be in the posters own language!
						$emailer->set_subject($lang['Topic_reply_notification']);

						// This is a nasty kludge to remove the username var ... till (if?)
						// translators update their templates
						$emailer->msg = preg_replace('#[ ]?{USERNAME}#', '', $emailer->msg);

						if ($board_config['url_rw'] == '1')
						{
							$topic_url = $server_url . str_replace ('--', '-', make_url_friendly($topic_title) . '-vp' . $post_id . '.html#p' . $post_id);
						}
						else
						{
							$topic_url = $server_url . VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $post_id . '#p' . $post_id;
						}

						$emailer->assign_vars(array(
							'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
							'SITENAME' => $board_config['sitename'],
							//'USERNAME' => $users_ary[$bcc_list['0']],
							//'USERNAME' => '',
							'TOPIC_TITLE' => $topic_title,
							'POST_TEXT' => $post_text,
							'POSTERNAME' => $post_data['username'],
							'FORUM_NAME' => $forum_name,
							'ROOT' => $server_url,

							'U_TOPIC' => $topic_url,
							'U_STOP_WATCHING_TOPIC' => $server_url . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&unwatch=topic'
							)
						);

						$emailer->send();
						$emailer->reset();
					}
				}
			}

			$already_mailed = (trim($update_watched_sql) == '') ? "" : "$update_watched_sql, ";
			$db->sql_freeresult($result);

			// start of reply forum notification
			$sql = "SELECT u.user_id, u.user_email, u.user_lang, f.forum_name
				FROM " . USERS_TABLE . " u, " . FORUMS_WATCH_TABLE . " fw, " . FORUMS_TABLE . " f
				WHERE fw.forum_id = $forum_id
					AND fw.user_id NOT IN (" . $already_mailed . $userdata['user_id'] . ", " . ANONYMOUS . $user_id_sql . " )
					AND f.forum_id = $forum_id
					AND f.forum_notify = '1'
					AND u.user_id = fw.user_id
					AND u.user_active = 1";
			if (!($result = $db->sql_query($sql)))
			{
				empty_cache_folders(POSTS_CACHE_FOLDER);
				message_die(GENERAL_ERROR, 'Could not obtain list of topic watchers', '', __LINE__, __FILE__, $sql);
			}

			$bcc_list_ary = array();

			$users_ary = array();

			if ($row = $db->sql_fetchrow($result))
			{
				// Sixty second limit
				@set_time_limit(60);

				do
				{
					if ($row['user_email'] != '')
					{
						$bcc_list_ary[$row['user_lang']][] = $row['user_email'];
						$users_ary[$row['user_email']] = $row['username'];
					}
					$forum_name = $row['forum_name'];
				}
				while ($row = $db->sql_fetchrow($result));

				//
				// Let's do some checking to make sure that mass mail functions
				// are working in win32 versions of php.
				//
				if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
				{
					$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

					// We are running on windows, force delivery to use our smtp functions
					// since php's are broken by default
					$board_config['smtp_delivery'] = 1;
					$board_config['smtp_host'] = @$ini_val('SMTP');
				}

				if (count($bcc_list_ary))
				{
					include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
					$emailer = new emailer($board_config['smtp_delivery']);
					$server_url = create_server_url();

					$orig_word = array();
					$replacement_word = array();
					obtain_word_list($orig_word, $replacement_word);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$topic_title = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, unprepare_message($topic_title)) : unprepare_message($topic_title);
					$post_text = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, unprepare_message($post_data['message'])) : unprepare_message($post_data['message']);

					if ($board_config['html_email'] == true)
					{
						$bbcode->allow_bbcode = ($board_config['allow_bbcode'] ? $board_config['allow_bbcode'] : false);
						$bbcode->allow_html = ($board_config['allow_html'] ? $board_config['allow_html'] : false);
						$bbcode->allow_smilies = ($board_config['allow_smilies'] ? $board_config['allow_smilies'] : false);
						$post_text = $bbcode->parse($post_text);
						$post_text = stripslashes($post_text);
					}
					else
					{
						$post_text = $bbcode->plain_message($post_text, '');
					}

					$temp_is_auth = array();
					@reset($bcc_list_ary);
					while (list($user_lang, $bcc_list) = each($bcc_list_ary))
					{
						$temp_userdata = get_userdata_notifications($row['user_id']);
						$temp_is_auth = auth(AUTH_ALL, $forum_id, $temp_userdata, -1);

						// another security check (i.e. the forum might have become private and
						// there are still users who have notification activated)
						if( $temp_is_auth['auth_read'] && $temp_is_auth['auth_view'] )
						{
							$emailer->use_template('forum_notify', $user_lang);

							for ($i = 0; $i < count($bcc_list); $i++)
							{
								$emailer->bcc($bcc_list[$i]);
							}

							// The Topic_reply_notification lang string below will be used
							// if for some reason the mail template subject cannot be read
							// ... note it will not necessarily be in the posters own language!
							$emailer->set_subject($lang['Topic_reply_notification']);

							// This is a nasty kludge to remove the username var ... till (if?)
							// translators update their templates
							$emailer->msg = preg_replace('#[ ]?{USERNAME}#', '', $emailer->msg);

							if ($board_config['url_rw'] == '1')
							{
								$topic_url = $server_url . str_replace ('--', '-', make_url_friendly($topic_title) . '-vp' . $post_id . '.html#p' . $post_id);
							}
							else
							{
								$topic_url = $server_url . VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $post_id . '#p' . $post_id;
							}

							$emailer->assign_vars(array(
								'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
								'SITENAME' => $board_config['sitename'],
								//'USERNAME' => $users_ary[$bcc_list['0']],
								//'USERNAME' => '',
								'TOPIC_TITLE' => $topic_title,
								'POST_TEXT' => $post_text,
								'POSTERNAME' => $post_data['username'],
								'FORUM_NAME' => $forum_name,
								'ROOT' => $server_url,

								'U_TOPIC' => $topic_url,
								'U_STOP_WATCHING_FORUM' => $server_url . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&unwatch=forum'
								)
							);

							$emailer->send();
							$emailer->reset();
						}
					}
				}
			}
			// end of forum notification on reply

			$db->sql_freeresult($result);

			if ($update_watched_sql != '')
			{
				$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
					SET notify_status = " . TOPIC_WATCH_NOTIFIED . "
					WHERE topic_id = $topic_id
						AND user_id IN ($update_watched_sql)";
				$db->sql_query($sql);
			}
		}

		//
		// code for newtopic forum notification
		//
		if ($mode == 'newtopic')
		{
			// One row SQL for caching purpose...
			$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid <> 0 ORDER BY ban_userid ASC";
			if (!($result = $db->sql_query($sql, false, 'ban_', USERS_CACHE_FOLDER)))
			{
				message_die(GENERAL_ERROR, "Could not obtain banned users information.", '', __LINE__, __FILE__, $sql);
			}
			$user_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				if (isset($row['ban_userid']) && !empty($row['ban_userid']))
				{
					$user_id_sql .= ', ' . $row['ban_userid'];
				}
			}

			$sql = "SELECT u.user_id, u.username, u.user_email, u.user_lang, f.forum_name
				FROM " . FORUMS_WATCH_TABLE . " fw, " . USERS_TABLE . " u, " . FORUMS_TABLE . " f
				WHERE fw.forum_id = $forum_id
					AND fw.user_id NOT IN (" . $userdata['user_id'] . ", " . ANONYMOUS . $user_id_sql . ")
					AND f.forum_id = $forum_id
					AND f.forum_notify = '1'
					AND u.user_id = fw.user_id
					AND u.user_active = 1";
			if (!($result = $db->sql_query($sql)))
			{
				empty_cache_folders(POSTS_CACHE_FOLDER);
				message_die(GENERAL_ERROR, 'Could not obtain list of forum watchers', '', __LINE__, __FILE__, $sql);
			}

			$bcc_list_ary = array();
			$users_ary = array();

			if ($row = $db->sql_fetchrow($result))
			{
				// Sixty second limit
				@set_time_limit(60);

				unset($forum_name);
				do
				{
					if ($row['user_email'] != '')
					{
						$bcc_list_ary[$row['user_lang']][] = $row['user_email'];
						$users_ary[$row['user_email']] = $row['username'];
					}
					$forum_name = $row['forum_name'];
				}
				while ($row = $db->sql_fetchrow($result));

				//
				// Let's do some checking to make sure that mass mail functions
				// are working in win32 versions of php.
				//
				if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
				{
					$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

					// We are running on windows, force delivery to use our smtp functions
					// since php's are broken by default
					$board_config['smtp_delivery'] = 1;
					$board_config['smtp_host'] = @$ini_val('SMTP');
				}

				if (count($bcc_list_ary))
				{
					include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
					$emailer = new emailer($board_config['smtp_delivery']);
					$server_url = create_server_url();

					$orig_word = array();
					$replacement_word = array();
					obtain_word_list($orig_word, $replacement_word);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$topic_title = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, unprepare_message($topic_title)) : unprepare_message($topic_title);
					$post_text = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, unprepare_message($post_data['message'])) : unprepare_message($post_data['message']);

					if ($board_config['html_email'] == true)
					{
						$bbcode->allow_bbcode = ($board_config['allow_bbcode'] ? $board_config['allow_bbcode'] : false);
						$bbcode->allow_html = ($board_config['allow_html'] ? $board_config['allow_html'] : false);
						$bbcode->allow_smilies = ($board_config['allow_smilies'] ? $board_config['allow_smilies'] : false);
						$post_text = $bbcode->parse($post_text);
						$post_text = stripslashes($post_text);
					}
					else
					{
						$post_text = $bbcode->plain_message($post_text, '');
					}

					$temp_is_auth = array();
					@reset($bcc_list_ary);
					while (list($user_lang, $bcc_list) = each($bcc_list_ary))
					{
						$temp_userdata = get_userdata_notifications($row['user_id']);
						$temp_is_auth = auth(AUTH_ALL, $forum_id, $temp_userdata, -1);

						// another security check (i.e. the forum might have become private and
						// there are still users who have notification activated)
						if( $temp_is_auth['auth_read'] && $temp_is_auth['auth_view'] )
						{
							$emailer->use_template('newtopic_notify', $user_lang);

							for ($i = 0; $i < count($bcc_list); $i++)
							{
								$emailer->bcc($bcc_list[$i]);
							}

							// The Topic_reply_notification lang string below will be used
							// if for some reason the mail template subject cannot be read
							// ... note it will not necessarily be in the posters own language!
							$emailer->set_subject($lang['Topic_reply_notification']);

							// This is a nasty kludge to remove the username var ... till (if?)
							// translators update their templates
							$emailer->msg = preg_replace('#[ ]?{USERNAME}#', '', $emailer->msg);

							if ($board_config['url_rw'] == '1')
							{
								$topic_url = $server_url . str_replace ('--', '-', make_url_friendly($topic_title) . '-vp' . $post_id . '.html#p' . $post_id);
							}
							else
							{
								$topic_url = $server_url . VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $post_id . '#p' . $post_id;
							}

							$emailer->assign_vars(array(
								'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
								'SITENAME' => $board_config['sitename'],
								//'USERNAME' => $users_ary[$bcc_list['0']],
								//'USERNAME' => '',
								'TOPIC_TITLE' => $topic_title,
								'POST_TEXT' => $post_text,
								'POSTERNAME' => $post_data['username'],
								'FORUM_NAME' => $forum_name,
								'ROOT' => $server_url,

								'U_TOPIC' => $topic_url,
								'U_STOP_WATCHING_FORUM' => $server_url . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&unwatch=forum'
								)
							);

							$emailer->send();
							$emailer->reset();
						}
					}
				}
			}

			$db->sql_freeresult($result);
		}

		$sql = "SELECT topic_id
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $topic_id
				AND user_id = " . $userdata['user_id'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain topic watch information', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		if (!$notify_user && !empty($row['topic_id']))
		{
			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE topic_id = $topic_id
					AND user_id = " . $userdata['user_id'];
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete topic watch information', '', __LINE__, __FILE__, $sql);
			}
		}
		elseif ($notify_user && empty($row['topic_id']))
		{
			$sql = "INSERT INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
				VALUES (" . $userdata['user_id'] . ", $topic_id, 0)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert topic watch information', '', __LINE__, __FILE__, $sql);
			}
		}
	}
}
// End replacement - Forum notification MOD

//
// Fill smiley templates (or just the variables) with smileys
// Either in a window or inline
//
function generate_smilies($mode)
{
	global $db, $board_config, $template, $lang, $images, $theme;
	global $user_ip, $session_length, $starttime;
	global $userdata;

	if (defined('IN_PA_POSTING'))
	{
		global $pafiledb_template;
	}

	$inline_columns = $board_config['smilie_columns'];
	$inline_rows = $board_config['smilie_rows'];
	$window_columns = $board_config['smilie_window_columns'];
	$window_rows = $board_config['smilie_window_rows'];
	$smilies_per_page = $window_columns * $window_rows;
	$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	$smilies_per_page = (isset($_GET['smilies_per_page'])) ? intval($_GET['smilies_per_page']) : (isset($_POST['smilies_per_page'])) ? intval($_POST['smilies_per_page']) : $smilies_per_page;

	if ($mode == 'window')
	{
		// Start session management
		$userdata = session_pagestart($user_ip);
		init_userprefs($userdata);
		// End session management

		$gen_simple_header = true;

		$page_title = $lang['Emoticons'];
		$meta_description = '';
		$meta_keywords = '';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		if (defined('IN_PA_POSTING'))
		{
			$pafiledb_template->set_filenames(array('smiliesbody' => 'posting_smilies.tpl'));
		}
		else
		{
			$template->set_filenames(array('smiliesbody' => 'posting_smilies.tpl'));
		}
	}

	// Smilies Order Replace
	// ORDER BY smilies_id";
	$sql = "SELECT emoticon, code, smile_url FROM " . SMILIES_TABLE . " ORDER BY smilies_order";
	if ($result = $db->sql_query($sql, false, 'smileys_'))
	{
		$num_smilies = 0;
		$rowset = array();
		$rowset2 = array();
		while($row = $db->sql_fetchrow($result))
		{
			if(empty($rowset2[$row['smile_url']]))
			{
				$rowset2[$row['smile_url']] = $row['smile_url'];
				$rowset[$num_smilies]['smile_url'] = $row['smile_url'];
				$rowset[$num_smilies]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row['code']));
				$rowset[$num_smilies]['emoticon'] = $row['emoticon'];
				$num_smilies++;
			}
		}
		unset($rowset2);
		$db->sql_freeresult($result);

		if ($num_smilies)
		{
			if(($mode == 'inline') || ($smilies_per_page == 0))
			{
				$per_page = $num_smilies;
				$smiley_start = 0;
				$smiley_stop = $num_smilies;
			}
			else
			{
				$per_page = ($smilies_per_page > $num_smilies) ? $num_smilies : $smilies_per_page;
				$page_num = ($start <= 0) ? 1 : ($start / $per_page) + 1;
				$smiley_start = ($per_page * $page_num) - $per_page;
				$smiley_stop = (($per_page * $page_num) > $num_smilies) ? $num_smilies : $smiley_start + $per_page;
			}
			$smilies_count = ($mode == 'inline') ? min((($inline_columns * $inline_rows) - 1), $num_smilies) : $num_smilies;
			$smilies_split_row = ($mode == 'inline') ? $inline_columns - 1 : $window_columns - 1;

			$s_colspan = 0;
			$row = 0;
			$col = 0;

			for($i = $smiley_start; $i < $smiley_stop; $i++)
			{
				if (!$col)
				{
					if (defined('IN_PA_POSTING'))
					{
						$pafiledb_template->assign_block_vars('smilies_row', array());
					}
					else
					{
						$template->assign_block_vars('smilies_row', array());
					}
				}
				$parsing_template = array(
					'SMILEY_CODE' => $rowset[$i]['code'],
					'SMILEY_IMG' => 'http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $rowset[$i]['smile_url'],
					'SMILEY_DESC' => $rowset[$i]['emoticon']
				);
				if (defined('IN_PA_POSTING'))
				{
					$pafiledb_template->assign_block_vars('smilies_row.smilies_col', $parsing_template);
				}
				else
				{
					$template->assign_block_vars('smilies_row.smilies_col', $parsing_template);
				}

				$s_colspan = max($s_colspan, $col + 1);

				if ($col == $smilies_split_row)
				{
					if((($mode == 'inline') && ($row == $inline_rows - 1)) || (empty($inline) && ($row == $per_page)))
					{
						break;
					}
					$col = 0;
					$row++;
				}
				else
				{
					$col++;
				}
			}

			if ($mode == 'inline' && $num_smilies > $inline_rows * $inline_columns)
			{
				$parsing_template = array(
					'L_MORE_SMILIES' => $lang['More_emoticons'],
					'U_MORE_SMILIES' => append_sid('posting.' . PHP_EXT . '?mode=smilies')
				);
				if (defined('IN_PA_POSTING'))
				{
					$pafiledb_template->assign_block_vars('switch_smilies_extra', array());
					$pafiledb_template->assign_vars($parsing_template);
				}
				else
				{
					$template->assign_block_vars('switch_smilies_extra', array());
					$template->assign_vars($parsing_template);
				}
			}

			$pagination = generate_pagination('posting.' . PHP_EXT . '?mode=smilies', $num_smilies, $per_page, $start, false);

			$select_smileys_pp = '<select name="smilies_per_page" onchange="SetSmileysPerPage();" class="gensmall">';
			$select_smileys_pp .= '<option value="' . ($window_columns * $window_rows) . '"' . (($smilies_per_page == ($window_columns * $window_rows)) ? ' selected="selected"' : '') . '>' . ($window_columns * $window_rows) . '</option>';
			$select_smileys_pp .= '<option value="50"' . (($smilies_per_page == 50) ? ' selected="selected"' : '') . '>50</option>';
			$select_smileys_pp .= '<option value="100"' . (($smilies_per_page == 100) ? ' selected="selected"' : '') . '>100</option>';
			$select_smileys_pp .= '<option value="150"' . (($smilies_per_page == 150) ? ' selected="selected"' : '') . '>150</option>';
			$select_smileys_pp .= '<option value="250"' . (($smilies_per_page == 250) ? ' selected="selected"' : '') . '>250</option>';
			$select_smileys_pp .= '<option value="500"' . (($smilies_per_page == 500) ? ' selected="selected"' : '') . '>500</option>';
			$select_smileys_pp .= '<option value="1000"' . (($smilies_per_page == 1000) ? ' selected="selected"' : '') . '>1000</option>';
			$select_smileys_pp .= '<option value="5000"' . (($smilies_per_page == 5000) ? ' selected="selected"' : '') . '>5000</option>';
			$select_smileys_pp .= '</select>';

			$parsing_template = array(
				'L_EMOTICONS' => $lang['Emoticons'],
				'L_CLOSE_WINDOW' => $lang['Close_window'],
				'L_SMILEYS_PER_PAGE' => $lang['Smileys_Per_Page'],

				'REQUEST_URI' => append_sid('posting.' . PHP_EXT . '?mode=smilies'),
				'U_SMILEYS_GALLERY' => append_sid('smileys.' . PHP_EXT),

				'DEFAULT_SMILEYS_PER_PAGE' => $window_columns * $window_rows,
				'SELECT_SMILEYS_PP' => $select_smileys_pp,
				'PAGINATION' => $pagination,
				'S_SMILIES_COLSPAN' => $s_colspan
			);
			if (defined('IN_PA_POSTING'))
			{
				$pafiledb_template->assign_vars($parsing_template);
			}
			else
			{
				$template->assign_vars($parsing_template);
			}
		}
	}

	if ($mode == 'window')
	{
		if (defined('IN_PA_POSTING'))
		{
			$pafiledb_template->pparse('smiliesbody');
		}
		else
		{
			$template->pparse('smiliesbody');
		}
		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	}
}

function generate_smilies_row()
{
	global $db, $board_config, $template;
	if (defined('IN_PA_POSTING'))
	{
		global $pafiledb_template;
	}

	$max_smilies = (!empty($board_config['smilie_single_row']) ? intval($board_config['smilie_single_row']) : 20);

	//$sql = "SELECT * FROM " . SMILIES_TABLE;
	$sql = "SELECT emoticon, code, smile_url FROM " . SMILIES_TABLE . " GROUP BY smile_url ORDER BY smilies_order LIMIT " . $max_smilies;
	if ($result = $db->sql_query($sql, false, 'smileys_'))
	{
		$orig = array();
		$repl = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$parsing_template = array(
				'CODE' => $row['code'],
				'URL' => 'http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $row['smile_url'],
				'DESC' => htmlspecialchars($row['emoticon'])
			);
			if (defined('IN_PA_POSTING'))
			{
				$pafiledb_template->assign_block_vars('smilies', $parsing_template);
			}
			else
			{
				$template->assign_block_vars('smilies', $parsing_template);
			}
		}
		$db->sql_freeresult($result);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Couldn\'t retrieve smilies list', '', __LINE__, __FILE__, $sql);
	}

	/*
	$sql = 'SELECT emoticon, code, smile_url
		FROM ' . SMILIES_TABLE . '
		GROUP BY smile_url
		ORDER BY smilies_id LIMIT ' . $max_smilies;
	//if (!$result = $db->sql_query($sql, false, 'smileys_'))
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve smilies list", '', __LINE__, __FILE__, $sql);
	}
	$smilies_count = $db->sql_numrows($result);
	$smilies_data = $db->sql_fetchrowset($result);
	for ($i = 0; $i < $smilies_count; $i++)
	{
		$parsing_template = array(
			'CODE' => $smilies_data[$i]['code'],
			'URL' => 'http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $smilies_data[$i]['smile_url'],
			'DESC' => $smilies_data[$i]['emoticon']
		);
		if (defined('IN_PA_POSTING'))
		{
			$pafiledb_template->assign_block_vars('smilies', $parsing_template);
		}
		else
		{
			$template->assign_block_vars('smilies', $parsing_template);
		}
	}
	*/
}

function generate_smilies_row_simple()
{
	global $db, $board_config, $template;

	$max_smilies = (!empty($board_config['smilie_single_row']) ? intval($board_config['smilie_single_row']) : 20);

	$sql = "SELECT emoticon, code, smile_url FROM " . SMILIES_TABLE . " GROUP BY smile_url ORDER BY smilies_order LIMIT " . $max_smilies;
	if ($result = $db->sql_query($sql, false, 'smileys_'))
	{
		$orig = array();
		$repl = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('smilies', array(
				'CODE' => $row['code'],
				'URL' => 'http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $row['smile_url'],
				'DESC' => htmlspecialchars($row['emoticon'])
				)
			);
		}
		$db->sql_freeresult($result);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Couldn\'t retrieve smilies list', '', __LINE__, __FILE__, $sql);
	}

	/*
	$sql = 'SELECT emoticon, code, smile_url
		FROM ' . SMILIES_TABLE . '
		GROUP BY smile_url
		ORDER BY smilies_id LIMIT ' . $max_smilies;

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve smilies list", '', __LINE__, __FILE__, $sql);
	}
	$smilies_count = $db->sql_numrows($result);
	$smilies_data = $db->sql_fetchrowset($result);
	for ($i = 0; $i < $smilies_count; $i++)
	{
		$template->assign_block_vars('smilies', array(
			'CODE' => $smilies_data[$i]['code'],
			'URL' => 'http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $smilies_data[$i]['smile_url'],
			'DESC' => $smilies_data[$i]['emoticon']
			)
		);
	}
	*/
}

/**
* Called from within prepare_message to clean included HTML tags if HTML is
* turned on for that post
* @param array $tag Matching text from the message to parse
*/
function clean_html($tag)
{
	global $board_config;

	if (empty($tag[0]))
	{
		return '';
	}

	$allowed_html_tags = preg_split('/, */', strtolower($board_config['allow_html_tags']));
	$disallowed_attributes = '/^(?:style|on)/i';

	// Check if this is an end tag
	preg_match('/<[^\w\/]*\/[\W]*(\w+)/', $tag[0], $matches);
	if (count($matches))
	{
		if (in_array(strtolower($matches[1]), $allowed_html_tags))
		{
			return '</' . $matches[1] . '>';
		}
		else
		{
			return htmlspecialchars('</' . $matches[1] . '>');
		}
	}

	// Check if this is an allowed tag
	if (in_array(strtolower($tag[1]), $allowed_html_tags))
	{
		$attributes = '';
		if (!empty($tag[2]))
		{
			preg_match_all('/[\W]*?(\w+)[\W]*?=[\W]*?(["\'])((?:(?!\2).)*)\2/', $tag[2], $test);
			for ($i = 0; $i < count($test[0]); $i++)
			{
				if (preg_match($disallowed_attributes, $test[1][$i]))
				{
					continue;
				}
				$attributes .= ' ' . $test[1][$i] . '=' . $test[2][$i] . str_replace(array('[', ']'), array('&#91;', '&#93;'), htmlspecialchars($test[3][$i])) . $test[2][$i];
			}
		}
		if (in_array(strtolower($tag[1]), $allowed_html_tags))
		{
			return '<' . $tag[1] . $attributes . '>';
		}
		else
		{
			return htmlspecialchars('<' . $tag[1] . $attributes . '>');
		}
	}
	// Finally, this is not an allowed tag so strip all the attibutes and escape it
	else
	{
		return htmlspecialchars('<' . $tag[1] . '>');
	}
}

function change_post_time($post_id, $post_time)
{
	global $db, $userdata;

	/*
	$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
	if ($userdata['user_id'] != $founder_id)
	{
		return false;
	}
	*/

	$sql = "SELECT post_edit_time FROM " . POSTS_TABLE . "
		WHERE post_id = '" . $post_id . "'
		LIMIT 1";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not catch post edit time', '', __LINE__, __FILE__, $sql);
	}
	while($row = $db->sql_fetchrow($result))
	{
		$post_edit_time = $row['post_edit_time'];
	}
	$db->sql_freeresult($result);

	if ($post_edit_time < $post_time)
	{
		$post_edit_time = $post_time;
	}

	$sql = "UPDATE " . POSTS_TABLE . "
		SET post_time = '" . $post_time . "', post_edit_time = '" . $post_edit_time . "'
		WHERE post_id = '" . $post_id . "'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not save post time and post edit time', '', __LINE__, __FILE__, $sql);
	}

	$is_first_post = false;
	$sql = "SELECT topic_id
		FROM " . TOPICS_TABLE . "
		WHERE topic_first_post_id = '" . $post_id . "'
		LIMIT 1";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t query topics table', '', __LINE__, __FILE__, $sql);
	}
	if($row = $db->sql_fetchrow($result))
	{
		$is_first_post = true;
		$topic_id = $row['topic_id'];
	}
	$db->sql_freeresult($result);

	if ($is_first_post)
	{
		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_time = '" . $post_time . "'
			WHERE topic_id = '" . $topic_id . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not save topic time', '', __LINE__, __FILE__, $sql);
		}
	}

	return true;
}

function change_poster_id($post_id, $poster_name)
{
	global $db, $userdata;

	/*
	$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
	if ($userdata['user_id'] != $founder_id)
	{
		return false;
	}
	*/

	$sql = "SELECT user_id, username
		FROM " . USERS_TABLE . "
		WHERE username = '" . $poster_name . "'
		LIMIT 1";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t obtain user id', '', __LINE__, __FILE__, $sql);
	}
	if(!($row = $db->sql_fetchrow($result)))
	{
		$db->sql_freeresult($result);
		return false;
	}
	$poster_id = $row['user_id'];
	$db->sql_freeresult($result);

	$is_first_post = false;
	$sql = "SELECT topic_id
		FROM " . TOPICS_TABLE . "
		WHERE topic_first_post_id = '" . $post_id . "'
		LIMIT 1";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t query topics table', '', __LINE__, __FILE__, $sql);
	}
	if($row = $db->sql_fetchrow($result))
	{
		$is_first_post = true;
		$topic_id = $row['topic_id'];
	}
	$db->sql_freeresult($result);

	$is_post_count = false;
	$sql = "SELECT p.forum_id, p.poster_id, p.post_username, f.forum_postcount
		FROM " . POSTS_TABLE . " p, " . FORUMS_TABLE . " f
		WHERE p.post_id = '" . $post_id . "'
			AND f.forum_id = p.forum_id
		LIMIT 1";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t query forums table', '', __LINE__, __FILE__, $sql);
	}
	if($row = $db->sql_fetchrow($result))
	{
		$old_poster_id = $row['poster_id'];
		$old_poster_username = $row['post_username'];
		$is_post_count = ($row['forum_postcount'] ? true : false);
	}
	$db->sql_freeresult($result);

	$sql = "UPDATE " . POSTS_TABLE . " SET poster_id = '" . $poster_id . "', post_username = '' WHERE post_id = '" . $post_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t update posts table', '', __LINE__, __FILE__, $sql);
	}

	if ($is_first_post)
	{
		$sql = "UPDATE " . TOPICS_TABLE . " SET topic_poster = '" . $poster_id . "' WHERE topic_id = '" . $topic_id . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t update posts table', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($is_post_count)
	{
		$sql = "UPDATE " . USERS_TABLE . " SET user_posts = (user_posts + 1) WHERE user_id = '" . $poster_id . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t update users table', '', __LINE__, __FILE__, $sql);
		}

		if ($old_poster_id != ANONYMOUS)
		{
			$sql = "UPDATE " . USERS_TABLE . " SET user_posts = (user_posts - 1) WHERE user_id = '" . $old_poster_id . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t update users table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	return true;
}

?>