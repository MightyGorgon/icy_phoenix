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
	global $config, $html_entities_match, $html_entities_replace;

	// Clean up the message
	$message = trim($message);

	if ($html_on)
	{
		// If HTML is on, we try to make it safe
		// This approach is quite agressive and anything that does not look like a valid tag is going to get converted to HTML entities
		$message = $message;
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

		$message = $message;
		// Mighty Gorgon: This should not be needed any more...
		//$message = str_replace('&quot;', '\&quot;', $message);
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
function prepare_post(&$mode, &$post_data, &$bbcode_on, &$html_on, &$smilies_on, &$error_msg, &$username, &$subject, &$message, &$poll_title, &$poll_options, &$poll_data, &$reg_active, &$reg_reset, &$reg_max_option1, &$reg_max_option2, &$reg_max_option3, &$reg_length, &$topic_desc, $topic_calendar_time = 0, $topic_calendar_duration = 0)
{
	global $config, $user, $lang;
	global $topic_id;
	global $db;

	// Check username
	if (!empty($username))
	{
		$username = phpbb_clean_username($username);

		if (!$user->data['session_logged_in'] || ($user->data['session_logged_in'] && ($username != $user->data['username'])))
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
		$subject = trim($subject);
	}
	elseif ($mode == 'newtopic' || ($mode == 'editpost' && $post_data['first_post']))
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_subject'] : $lang['Empty_subject'];
	}
	// Check Topic Desciption
	if (!empty($topic_desc))
	{
		$topic_desc = trim($topic_desc);
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
		$year = intval(gmdate('Y', $topic_calendar_time));
		$month = intval(gmdate('m', $topic_calendar_time));
		$day = intval(gmdate('d', $topic_calendar_time));
		if (!checkdate($month, $day, $year))
		{
			$error_msg .= (!empty($error_msg) ? '<br />' : '') . sprintf($lang['Date_error'], $day, $month, $year);
		}
	}

	// Check to see if there's a new post while the user is posting
	$new_post_while_posting = false;
	if(!empty($_POST['post_time']) && (($mode == 'reply') || ($mode == 'quote')) && $config['show_new_reply_posting'])
	{
		$last_post_time = intval($_POST['post_time']);

		if(!empty($topic_id) && $last_post_time)
		{
			$sql = "SELECT post_time FROM " . POSTS_TABLE . " WHERE topic_id = '" . $topic_id . "' ORDER BY post_time DESC LIMIT 0, 1";
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if ($result)
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
	//if(($mode == 'reply' || $mode == 'quote') && ($config['no_bump'] == true) && ($new_post_while_posting == false))
	$no_bump = ((($config['no_bump'] == 1) && ($user->data['user_level'] != ADMIN)) || (($config['no_bump'] == 2) && ($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))) ? true : false;
	if((($mode == 'reply') || ($mode == 'quote')) && ($no_bump == true) && ($new_post_while_posting == false))
	{
		if(!empty($topic_id))
		{
			$sql = "SELECT poster_id FROM " . POSTS_TABLE . "
							WHERE topic_id = '" . $topic_id . "'
							AND post_time > " . (time() - 86400) . "
							ORDER BY post_time DESC
							LIMIT 0, 1";
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if ($result)
			{
				if($row = $db->sql_fetchrow($result))
				{
					if($row['poster_id'] == $user->data['user_id'])
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
		$poll_title = (!empty($poll_title) ? trim($poll_title) : (isset($poll_data['title']) ? trim($poll_data['title']) : ''));
		$poll_start = (isset($poll_data['start'])) ? $poll_data['start'] : time();
		$poll_length = (isset($poll_data['length'])) ? max(0, intval($poll_data['length'])) : 0;
		$poll_max_options = (isset($poll_data['max_options'])) ? max(1, intval($poll_data['max_options'])) : 1;
		$poll_change = (isset($poll_data['change'])) ? $poll_data['change'] : 0;
		$poll_data = array(
			'title' => $poll_title,
			'start' => $poll_start,
			'length' => $poll_length,
			'max_options' => $poll_max_options,
			'change' => $poll_change
		);

		if(!empty($poll_options))
		{
			$temp_option_text = array();
			while(list($option_id, $option_text) = @each($poll_options))
			{
				$option_text = trim($option_text);
				if (!empty($option_text))
				{
					$temp_option_text[intval($option_id)] = $option_text;
				}
			}
			$option_text = $temp_option_text;

			if (sizeof($poll_options) < 2)
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['To_few_poll_options'] : $lang['To_few_poll_options'];
			}
			elseif (sizeof($poll_options) > $config['max_poll_options'])
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
function submit_post($mode, &$post_data, &$message, &$meta, &$forum_id, &$topic_id, &$post_id, &$topic_type, &$bbcode_on, &$html_on, &$acro_auto_on, &$smilies_on, &$attach_sig, $post_username, $post_subject, $topic_title_clean, $topic_tags, $post_message, $poll_title, &$poll_options, &$poll_data, &$reg_active, &$reg_reset, &$reg_max_option1, &$reg_max_option2, &$reg_max_option3, &$reg_length, &$news_category, &$topic_show_portal, &$mark_edit, &$topic_desc, $topic_calendar_time = 0, $topic_calendar_duration = 0, $extra_vars = array())
{
	global $db, $cache, $config, $user, $lang, $class_plugins;

	// CrackerTracker v5.x
	if ((($mode == 'newtopic') || ($mode == 'reply')) && (($config['ctracker_spammer_blockmode'] > 0) || ($config['ctracker_spam_attack_boost'] == 1)) && ($user->data['user_level'] != ANONYMOUS))
	{
		include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
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

	if (($user->data['user_level'] != ADMIN) && (!empty($config['force_large_caps_mods']) || ($user->data['user_level'] != MOD)))
	{
		//$post_subject = strtolower($post_subject);
		$post_subject = ucwords($post_subject);
	}

	// Flood control
	if (($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))
	{
		if (!function_exists('check_flood_posting'))
		{
			include_once(IP_ROOT_PATH . 'includes/functions_flood.' . PHP_EXT);
		}
		check_flood_posting(false);
	}

	if ($mode == 'editpost')
	{
		remove_search_post($post_id);
	}

	if (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post']))
	{
		$topic_vote = (!empty($poll_title) && sizeof($poll_options) >= 2) ? 1 : 0;
		$topic_show_portal = ($topic_show_portal == true) ? 1 : 0;
		$topic_calendar_duration = ($topic_calendar_duration == '') ? 0 : $topic_calendar_duration;

		// Event Registration - BEGIN
		$topic_reg = 0;
		if ($reg_active == 1)
		{
			$topic_reg = 1;
		}
		// Event Registration - END

		$query = array(
			'topic_title' => $post_subject,
			'topic_desc' => $topic_desc,
			'news_id' => $news_id,
			'topic_tags' => $topic_tags,
			'topic_type' => $topic_type,
			'topic_calendar_time' => $topic_calendar_time,
			'topic_calendar_duration' => $topic_calendar_duration,
			'topic_reg' => $topic_reg,
			'topic_show_portal' => $topic_show_portal,
		);
		if ($mode != 'editpost')
		{
			$query = array_merge($query, array(
				'topic_poster' => $user->data['user_id'],
				'topic_time' => $current_time,
				'forum_id' => $forum_id,
				'topic_status' => TOPIC_UNLOCKED,
				)
			);
		}
		
		/**
		 * @event submit_post_query_insert_or_update_first.
		 * @description Add/remove parts from the SQL query to create a new topic, or update the first post of an already existing topic.
		 * @since 3.0
		 * @var array query The SQL insert/update query parts.
		 * @var mixed {...signature} All the variables from submit_post's signature are passed through.
		 */
		$vars = array('query', 'mode', 'post_data', 'message', 'meta', 'forum_id', 'topic_id', 'post_id', 'topic_type', 'bbcode_on', 'html_on', 'acro_auto_on', 'smilies_on', 'attach_sig', 'post_username', 'post_subject', 'topic_title_clean', 'topic_tags', 'post_message', 'poll_title', 'poll_options', 'poll_data', 'reg_active', 'reg_reset', 'reg_max_option1', 'reg_max_option2', 'reg_max_option3', 'reg_length', 'news_category', 'topic_show_portal', 'mark_edit', 'topic_desc', 'topic_calendar_time', 'topic_calendar_duration', 'extra_vars');
		extract($class_plugins->trigger('submit_post_query_insert_or_update_first', compact($vars)));
		
		$sql = $db->sql_build_insert_update($query, $mode != 'editpost');
		if ($mode == 'newtopic')
		{
			$sql = "INSERT INTO " . TOPICS_TABLE . " " . $sql;
		}
		else
		{
			$sql = "UPDATE " . TOPICS_TABLE . " SET $sql WHERE topic_id = $topic_id";
		}

		$db->sql_query($sql);

		if ($mode == 'newtopic')
		{
			$topic_id = $db->sql_nextid();
		}
		else
		{
			// Event Registration - BEGIN
			if ($reg_reset)
			{
				$sql = "DELETE FROM " . REGISTRATION_TABLE . " WHERE topic_id = " . $topic_id;
				$db->sql_query($sql);
			}
			// Event Registration - END
		}

		if (!function_exists('create_clean_topic_title'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
		}
		create_clean_topic_title($topic_id, $forum_id, $topic_title_clean, '');

		@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
		$class_topics_tags = new class_topics_tags();
		$topic_tags_array = $class_topics_tags->create_tags_array($topic_tags);
		$update_tags = ($mode == 'editpost') ? true : false;
		$class_topics_tags->submit_tags($topic_id, $forum_id, $topic_tags_array, $update_tags);
		unset($class_topics_tags);

		// Empty the similar id cache for guests every time we create a new topic or edit the first post in a topic
		if ($config['similar_topics'])
		{
			$clear_result = clear_similar_topics();
		}
	}

	// Poll management - BEGIN
	if ((($mode == 'newtopic') || (($mode == 'editpost') && $post_data['edit_poll'])) && !empty($poll_title) && (sizeof($poll_options) >= 2))
	{
		$poll_title = (!empty($poll_title) ? trim($poll_title) : (isset($poll_data['title']) ? trim($poll_data['title']) : ''));
		$poll_start = !empty($poll_data['start']) ? $poll_data['start'] : $current_time;
		$poll_length = isset($poll_data['length']) ? max(0, intval($poll_data['length'])) : 0;
		$poll_max_options = (isset($poll_data['max_options'])) ? max(1, intval($poll_data['max_options'])) : 1;
		$poll_last_vote = !empty($post_data['poll_last_vote']) ? $post_data['poll_last_vote'] : 0;
		$poll_change = !empty($poll_data['change']) ? 1 : 0;

		$sql_ary = array(
			'poll_title' => $poll_title,
			'poll_start' => $poll_start,
			'poll_length' => $poll_length,
			'poll_max_options' => $poll_max_options,
			'poll_last_vote' => $poll_last_vote,
			'poll_vote_change' => $poll_change
		);

		$sql_poll_update = $db->sql_build_insert_update($sql_ary, false);

		$sql = "UPDATE " . TOPICS_TABLE . " SET " . $sql_poll_update . " WHERE topic_id = " . $topic_id;
		$db->sql_query($sql);

		$delete_option_sql = '';
		$old_poll_result = array();
		if (($mode == 'editpost') && $post_data['has_poll'])
		{
			$sql = "SELECT *
				FROM " . POLL_OPTIONS_TABLE . "
				WHERE topic_id = $topic_id
				ORDER BY poll_option_id ASC";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$old_poll_result[$row['poll_option_id']] = $row['poll_option_total'];

				if (!isset($poll_options[$row['poll_option_id']]))
				{
					$delete_option_sql .= (($delete_option_sql != '') ? ', ' : '') . $row['poll_option_id'];
				}
			}
		}

		$poll_option_id = 1;
		@reset($poll_options);
		while (list($option_id, $option_text) = each($poll_options))
		{
			if (!empty($option_text))
			{
				$option_insert = (($mode != 'editpost') || !isset($old_poll_result[$option_id])) ? true : false;
				$poll_result = $option_insert ? 0 : $old_poll_result[$option_id];
				$poll_option_id = $option_insert ? $poll_option_id : $option_id;

				$sql_tmp_option_ary = array(
					'poll_option_id' => $poll_option_id,
					'topic_id' => $topic_id,
					'poll_option_text' => $option_text,
					'poll_option_total' => $poll_result
				);

				$sql_tmp_option = $db->sql_build_insert_update($sql_tmp_option_ary, $option_insert);

				if ($option_insert)
				{
					$sql = "INSERT INTO " . POLL_OPTIONS_TABLE . " " . $sql_tmp_option;
				}
				else
				{
					$sql = "UPDATE " . POLL_OPTIONS_TABLE . " SET " . $sql_tmp_option . " WHERE poll_option_id = $option_id AND topic_id = $topic_id";
				}
				$db->sql_query($sql);

				$poll_option_id++;
			}
		}

		if ($delete_option_sql != '')
		{
			$sql = "DELETE FROM " . POLL_OPTIONS_TABLE . "
				WHERE poll_option_id IN ($delete_option_sql)
					AND topic_id = $topic_id";
			$db->sql_query($sql);
		}
	}
	// Poll management - END

	// Event Registration - BEGIN
	if ((($mode == 'newtopic') || ($mode == 'editpost')) && ($topic_reg == 1))
	{
		if ($mode == 'editpost')
		{
			$sql = "SELECT count(1) chk_reg FROM " . REGISTRATION_DESC_TABLE . " WHERE topic_id = $topic_id";
			$result = $db->sql_query($sql);
			$chk_reg = ($db->sql_fetchfield('chk_reg', 0, $result) != 0) ? true : false;
		}

		$sql = (($mode != 'editpost') || (($mode == 'editpost') && ($chk_reg == false))) ? "INSERT INTO " . REGISTRATION_DESC_TABLE . " (topic_id, reg_active, reg_max_option1, reg_max_option2, reg_max_option3, reg_start, reg_length) VALUES ($topic_id, $reg_active, $reg_max_option1, $reg_max_option2, $reg_max_option3, $current_time, " . ($reg_length * 86400) . ")" : "UPDATE " . REGISTRATION_DESC_TABLE . " SET reg_active = $reg_active, reg_max_option1 = $reg_max_option1, reg_max_option2 = $reg_max_option2, reg_max_option3 = $reg_max_option3, reg_length = " . ($reg_length * 86400) . " WHERE topic_id = $topic_id";
		$db->sql_query($sql);
	}
	// Event Registration - END

	// To show also admins modifications decomment this line!!!
	//if( ($user->data['user_level'] == ADMIN) && !$config['always_show_edit_by'] )
	if($user->data['user_level'] == ADMIN)
	{
		$edited_sql = '';
	}
	else
	{
		// Original phpBB "Edit By"
		//$edited_sql = ($mode == 'editpost' && !$post_data['last_post'] && $post_data['poster_post']) ? ", post_edit_time = $current_time, post_edit_count = post_edit_count + 1 " : "";

		$edited_sql = ", post_edit_time = '" . $current_time . "', post_edit_count = (post_edit_count + 1), post_edit_id = '" . $user->data['user_id'] . "' ";
		if ($config['always_show_edit_by'] == true)
		{
			$edited_sql = ($mode == 'editpost') ? $edited_sql : '';
		}
		else
		{
			$edited_sql = (($mode == 'editpost') && !$post_data['last_post']) ? $edited_sql : '';
		}
	}

	$lock_post = request_boolean_var('post_locked', false);

	$sql = ($mode != 'editpost') ? "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_subject, post_text, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_autolinks_acronyms, enable_sig, post_locked, post_images) VALUES (" . $topic_id . ", " . $forum_id . ", " . $user->data['user_id'] . ", '" . $db->sql_escape($post_username) . "', '" . $db->sql_escape($post_subject) . "', '" . $db->sql_escape($post_message) . "', " . $current_time . ", '" . $db->sql_escape($user->ip) . "', " . $bbcode_on . ", " . $html_on . ", " . $smilies_on . ", " . $acro_auto_on . ", " . $attach_sig . ", " . (!empty($lock_post) ? '1' : '0') . ", '" . $db->sql_escape($post_data['post_images']) . "')" : "UPDATE " . POSTS_TABLE . " SET post_username = '" . $db->sql_escape($post_username) . "', post_text = '" . $db->sql_escape($post_message) . "', post_text_compiled = '', post_subject = '" . $db->sql_escape($post_subject) . "', enable_bbcode = " . $bbcode_on . ", enable_html = " . $html_on . ", enable_smilies = " . $smilies_on . ", enable_autolinks_acronyms = " . $acro_auto_on . ", enable_sig = " . $attach_sig . ", post_locked = " . (!empty($lock_post) ? '1' : '0') . ", post_images = '" . $db->sql_escape($post_data['post_images']) . "' " . $edited_sql . " WHERE post_id = " . $post_id;
	//die($sql);
	$db->sql_transaction('begin');
	$db->sql_query($sql);

	if ($mode != 'editpost')
	{
		$post_id = $db->sql_nextid();
	}

// UPI2DB - BEGIN
	if($config['upi2db_on'])
	{
		$mark_edit = (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD)) ? $mark_edit : true;

		if(($mode != 'editpost') || (($mode == 'editpost') && $post_data['last_post'] && $config['upi2db_last_edit_as_new'] && $mark_edit) || (($mode == 'editpost') && !$post_data['last_post'] && $config['upi2db_edit_as_new'] && $mark_edit) || ($mode == 'reply'))
		{
			$sql = "SELECT post_id FROM " . UPI2DB_LAST_POSTS_TABLE . "
				WHERE post_id = " . $post_id;
			$result = $db->sql_query($sql);
			$id_vorhanden = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			if ($id_vorhanden == 0)
			{
				$pt_or_pet = ($mode != 'editpost') ? "post_time" : "post_edit_time";
				$sql = "INSERT INTO " . UPI2DB_LAST_POSTS_TABLE . " (post_id, topic_id, forum_id, poster_id, " . $pt_or_pet . ", topic_type, post_edit_by) VALUES ('$post_id', '$topic_id', '$forum_id', '" . $user->data['user_id'] . "', '$current_time', '$topic_type', '" . $user->data['user_id'] . "')";
			}
			else
			{
				$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . " SET post_edit_time = '" . $current_time . "', topic_type = '" . $topic_type . "', post_edit_by = '" . $user->data['user_id'] . "' WHERE post_id = " . $post_id;
			}
			$db->sql_query($sql);
		}
		// Edited By Mighty Gorgon - BEGIN
		if (($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))
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
// UPI2DB - END

	add_search_words('single', $post_id, $post_message, $post_subject);

	// DOWNLOADS - BEGIN
	if (!empty($config['plugins']['downloads']['enabled']))
	{
		setup_extra_lang(array('lang_downloads'), IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['downloads']['dir'] . 'language/');
		include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['downloads']['dir'] . 'classes/class_dlmod.' . PHP_EXT);
		$dl_mod = new dlmod();
		$dl_config = $dl_mod->get_config();

		if ($dl_config['enable_post_dl_traffic'])
		{
			if (!$dl_config['delay_post_traffic'] || ((time() - $user->data['user_regdate']) / 84600) > $dl_config['delay_post_traffic'])
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
						WHERE user_id = " . $user->data['user_id'];
					$db->sql_query($sql);
				}
			}
		}
	}
	// DOWNLOADS - END

	// ReSync last topic title if needed
	if (($mode == 'editpost') && $post_data['first_post'])
	{
		$sql = "UPDATE " . FORUMS_TABLE . " f
			SET f.forum_last_post_subject = '" . $db->sql_escape($post_subject) . "'
			WHERE f.forum_last_topic_id = " . $topic_id;
		$result = $db->sql_query($sql);
	}

	$db->sql_transaction('commit');

	empty_cache_folders(POSTS_CACHE_FOLDER);
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	board_stats();
	cache_tree(true);

	$cash_string = '';
	// MG Cash MOD For IP - BEGIN
	if (!empty($config['plugins']['cash']['enabled']))
	{
		$cash_message = $GLOBALS['cm_posting']->update_post($mode, $post_data, $forum_id, $topic_id, $post_id, $topic_type, $post_username, $post_message);
		$cash_string = '<br />' . $cash_message;
	}
	// MG Cash MOD For IP - END
	$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '">';
	$message = $lang['Stored'] . $cash_string . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

	return false;
}

// Save Draft
function save_draft($draft_id, $user_id, $forum_id, $topic_id, $subject, $message)
{
	global $lang, $db;

	if ($draft_id == 0)
	{
		$sql = "INSERT INTO " . DRAFTS_TABLE . " (user_id, forum_id, topic_id, save_time, draft_subject, draft_message) VALUES ('$user_id', '$forum_id', '$topic_id', '" . time() . "', '" . $db->sql_escape($subject) . "', '" . $db->sql_escape($message) . "')";
	}
	else
	{
		$sql = "UPDATE " . DRAFTS_TABLE . " SET user_id = '$user_id', forum_id = '$forum_id', topic_id = '$topic_id', save_time =  '" . time() . "', draft_subject = '" . $db->sql_escape($subject) . "', draft_message = '" . $db->sql_escape($message) . "' WHERE draft_id = '" . $draft_id . "'";
	}
	$db->sql_query($sql);

	return true;
}

/*
* Get user notifications
*/
function get_userdata_notifications($target_user, $force_str = false)
{
	global $db;

	$target_user = (!is_numeric($target_user) || $force_str) ? phpbb_clean_username($target_user) : intval($target_user);
	$sql = "SELECT *
			FROM " . USERS_TABLE . "
			WHERE ";
	$sql .= ((is_integer($target_user)) ? ("user_id = " . $target_user) : "username = '" . $db->sql_escape($target_user) . "'") . " AND user_id <> " . ANONYMOUS;
	$result = $db->sql_query($sql);
	$return_value = ($row = $db->sql_fetchrow($result)) ? $row : false;
	$db->sql_freeresult($result);
	return $return_value;
}

/*
* Fill smiley templates (or just the variables) with smileys
* Either in a window or inline
*/
function generate_smilies($mode)
{
	global $db, $cache, $config, $auth, $user, $lang, $template, $images, $theme;
	global $starttime, $gen_simple_header;

	$inline_columns = $config['smilie_columns'];
	$inline_rows = $config['smilie_rows'];
	$window_columns = $config['smilie_window_columns'];
	$window_rows = $config['smilie_window_rows'];
	$smilies_per_page = $window_columns * $window_rows;
	$start = request_var('start', 0);
	$start = ($start < 0) ? 0 : $start;
	$smilies_per_page = request_var('smilies_per_page', $smilies_per_page);

	if ($mode == 'window')
	{
		// Start session management
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();
		// End session management

		$gen_simple_header = true;

		$meta_content['page_title'] = $lang['Emoticons'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		page_header($meta_content['page_title'], true);

		$template->set_filenames(array('smiliesbody' => 'posting_smilies.tpl'));
	}

	// Smilies Order Replace
	// ORDER BY smilies_id";
	$sql = "SELECT emoticon, code, smile_url FROM " . SMILIES_TABLE . " ORDER BY smilies_order";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql, 0, 'smileys_');
	$db->sql_return_on_error(false);
	if ($result !== false)
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
			$smilies_split_row = ($mode == 'inline') ? ($inline_columns - 1) : ($window_columns - 1);

			$s_colspan = 0;
			$row = 0;
			$col = 0;

			$server_protocol = !empty($config['cookie_secure']) ? 'https://' : 'http://';
			$host = extract_current_hostname();
			$url = $server_protocol . $host;
			if (!empty($config['server_port']) && $config['server_port'] != 80)
			{
				$url .= ':' . $config['server_port'];
			}
			$url .= $config['script_path'];

			for($i = $smiley_start; $i < $smiley_stop; $i++)
			{
				if (!$col)
				{
					$template->assign_block_vars('smilies_row', array());
				}
				$template->assign_block_vars('smilies_row.smilies_col', array(
					'SMILEY_CODE' => $rowset[$i]['code'],
					'SMILEY_IMG' => $url . $config['smilies_path'] . '/' . $rowset[$i]['smile_url'],
					'SMILEY_DESC' => $rowset[$i]['emoticon']
					)
				);

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
				$template->assign_vars(array(
					'L_MORE_SMILIES' => $lang['More_emoticons'],
					'U_MORE_SMILIES' => append_sid('posting.' . PHP_EXT . '?mode=smilies')
					)
				);
				$template->assign_block_vars('switch_smilies_extra', array());
			}

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

			$template->assign_vars(array(
				'L_EMOTICONS' => $lang['Emoticons'],
				'L_CLOSE_WINDOW' => $lang['Close_window'],
				'L_SMILEYS_PER_PAGE' => $lang['Smileys_Per_Page'],

				'REQUEST_URI' => append_sid('posting.' . PHP_EXT . '?mode=smilies'),
				'U_SMILEYS_GALLERY' => append_sid('smileys.' . PHP_EXT),

				'DEFAULT_SMILEYS_PER_PAGE' => $window_columns * $window_rows,
				'SELECT_SMILEYS_PP' => $select_smileys_pp,
				'PAGINATION' => generate_pagination('posting.' . PHP_EXT . '?mode=smilies&amp;smilies_per_page=' . $smilies_per_page, $num_smilies, $per_page, $start, false),
				'S_SMILIES_COLSPAN' => $s_colspan
				)
			);
		}
	}

	$template->assign_vars(array(
		'DISPLAY_MODE' => ($mode == 'window') ? 'window' : 'inline',
		)
	);

	if ($mode == 'window')
	{
		$template->pparse('smiliesbody');
		page_footer(true, '', true);
	}
}

/**
* Called from within prepare_message to clean included HTML tags if HTML is turned on for that post
* @param array $tag Matching text from the message to parse
*/
function clean_html($tag)
{
	global $config;

	if (empty($tag[0]))
	{
		return '';
	}

	$allowed_html_tags = preg_split('/, */', strtolower($config['allow_html_tags']));
	$disallowed_attributes = '/^(?:style|on)/i';

	// Check if this is an end tag
	preg_match('/<[^\w\/]*\/[\W]*(\w+)/', $tag[0], $matches);
	if (sizeof($matches))
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
			for ($i = 0; $i < sizeof($test[0]); $i++)
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

?>