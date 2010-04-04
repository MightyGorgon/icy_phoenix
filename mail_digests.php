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
* Mark D. Hamill (mhamill@computer.org)
*
*/

// ----------------------------------------- WARNING ---------------------------------------------- //
// THIS PROGRAM SHOULD BE INVOKED TO RUN AUTOMATICALLY EVERY HOUR BY THE OPERATING SYSTEM USING AN
// OPERATING SYSTEM FEATURE LIKE CRONTAB. SEE BATCH_SCHEDULING.TXT!!!
// ------------------------------------------ EDIT ------------------------------------------------ //
// IN ACP THERE IS AN OPTION FOR TRYING TO EMULATE THE CRONTAB VIA PHP. NOT FULLY WORKING YET WITH
// SOME CONFIGURATIONS.
// ----------------------------------------- WARNING ---------------------------------------------- //

// Warning: this was only tested with MySQL. I don't have access to other databases. Consequently,
// the SQL may need tweaking for other relational databases.

if (!defined('PHP_DIGESTS_CRON'))
{
	// Comment this line to allow execution of mail_digests.php from address bar.
	// Decomment it to block the execution
	die('Hacking attempt');

	define('IN_ICYPHOENIX', true);
	if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
	if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
	include(IP_ROOT_PATH . 'common.' . PHP_EXT);

	// Start session management
	$userdata = session_pagestart($user_ip);
	init_userprefs($userdata);
	// End session management

	if (empty($config['enable_digests']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (empty($userdata))
{
	// Start session management
	$userdata = session_pagestart($user_ip);
	init_userprefs($userdata);
	// End session management
}

// Comment this if you run it outside Icy Phoenix
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include_once(IP_ROOT_PATH . 'includes/constants.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/digest_constants.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/auth.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cron.' . PHP_EXT);

if (($config['url_rw'] || $config['url_rw_guests']) && !function_exists('make_url_friendly'))
{
	include(IP_ROOT_PATH . 'includes/functions_rewrite.' . PHP_EXT);
}

if (empty($bbcode) || !class_exists('bbcode'))
{
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
}

setup_extra_lang(array('lang_digests'));

@set_time_limit(0);
$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);
ignore_user_abort();

$link_tag = '';
$link_tag_unset = true;
//$break_type = (DIGEST_SHOW_SUMMARY_TYPE == 'html') ? "<br />\r\n" : "\r\n";
$break_type = (DIGEST_SHOW_SUMMARY_TYPE == 'html') ? "<br />\n" : "\n";
$line_break = "\n";
$digest_log_entry = '';

// Is today the day to run the weekly digest?
$today = @getdate();
$wday = $today['wday'];
$current_hour = $today['hours'];
$weekly_digest_text = ($wday == DIGEST_WEEKLY_DIGEST_DAY) ? " or (digest_type = 'WEEK' and send_hour = " . $current_hour . ")" : "";

// Send a user a weekly digest only if it is the correct day and hour of the week for a weekly
// digest, and any daily digest if the current hour of the day is the same as the hour wanted for the digest.

/*
$sql = "SELECT s.user_id, u.username, u.user_email, u.user_lastvisit, u.user_lang, s.digest_type, s.format, s.show_text, s.show_mine, s.new_only, s.send_on_no_messages, s.send_hour, s.text_length
	FROM " . DIGEST_SUBSCRIPTIONS_TABLE . ' s, ' . USERS_TABLE . " u
	WHERE s.user_id = u.user_id
		AND s.user_id = 2";
*/
$sql = "SELECT s.user_id, u.username, u.user_email, u.user_lastvisit, u.user_lang, s.digest_type, s.format, s.show_text, s.show_mine, s.new_only, s.send_on_no_messages, s.send_hour, s.text_length
	FROM " . DIGEST_SUBSCRIPTIONS_TABLE . " s, " . USERS_TABLE . " u
	WHERE s.user_id = u.user_id AND ((digest_type = 'DAY' AND send_hour = " . $current_hour . ')' . $weekly_digest_text . ')';
$result = $db->sql_query($sql);

// Retrieve a list of forum_ids that all registered users can access. Since digests go only to registered
// users it's important to include those forums not accessible to the general public but accessible to users.
$sql2 = 'SELECT forum_id FROM ' . FORUMS_TABLE . ' WHERE auth_read IN (' . AUTH_ALL . ', ' . AUTH_REG . ') AND forum_type = ' . FORUM_POST;
$result2 = $db->sql_query($sql2);
$i = 0;
while ($row2 = $db->sql_fetchrow($result2))
{
	$valid_forums [$i] = $row2['forum_id'];
	$i++;
}

$db->sql_freeresult($result2);

// With each pass through the loop one user will receive a customized digest.

$digests_sent = 0;
while ($row = $db->sql_fetchrow($result))
{
	// This logic ensures the hour the user wanted to receive the digest is reported correctly in the digest.
	$user_timezone = (float) $row['user_timezone'];
	$offset = $board_timezone - $user_timezone;
	$send_hour = (float) $row['send_hour'] - $offset;
	if ($send_hour < 0)
	{
		$send_hour = $send_hour + 24;
	}
	elseif ($send_hour >= 24)
	{
		$send_hour = $send_hour - 24;
	}

	if ($row['new_only'] == 'TRUE')
	{
		// To filter out any possible messages a user may have seen we need to examine a number of
		// possibilities, including last user message date/time, date/time of last session, if it exists, and
		// of course, the last access date/time in the USERS table. Of these 3 possibilities, whichever is
		// the greatest value is the actual last accessed date, and we may need to filter out messages
		// prior to this date and time. My experience is phpBB doesn't always get it right.

		$sql3 = "SELECT max(post_time) AS last_post_date
					FROM " . POSTS_TABLE . "
					WHERE poster_id = " . $row['user_id'];
		$result3 = $db->sql_query($sql3);
		$row3 = $db->sql_fetchrow($result3);
		$last_post_date = ($row3['last_post_date'] <> '') ? $row3['last_post_date'] : 0;
		$db->sql_freeresult($result3);

		// When did the user's last session accessed?
		$sql3 = "SELECT max(session_time) AS last_session_date
					FROM " . SESSIONS_TABLE . "
					WHERE session_user_id = " . $row['user_id'];
		$result3 = $db->sql_query($sql3);
		$row3 = $db->sql_fetchrow($result3);
		$last_session_date = ($row3['last_session_date'] <> '') ? $row3['last_session_date'] : 0;
		$db->sql_freeresult($result3);

		$last_visited_date = $row['user_lastvisit'];
		if ($last_visited_date == '')
		{
			$last_visited_date = 0;
		}

		// The true last visit date is the greatest of: last_visited_date, last message posted, and last session date
		$last_visited_date = max($last_post_date, $last_session_date, $last_visited_date);

	}

	// Get a list of forums that can only be read if user has been granted explicit permission
	$i = 0;
	$elected_forums = array();
	$sql3 = "SELECT distinct a.forum_id
		FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug
		WHERE ug.user_id = " . $row['user_id'] . "
		AND ug.user_pending = 0
		AND a.group_id = ug.group_id";
	$result3 = $db->sql_query($sql3);
	while ($row3 = $db->sql_fetchrow($result3))
	{
		$elected_forums [$i] = $row3['forum_id'];
		$i++;
	}
	$db->sql_freeresult($result3);

	// Get the union of the valid_forums array and the elected_formus array. The resulting elements are
	// the forums that could be queried. This is necessary because MySQL doesn't support SQL Unions (yet).
	$queried_forums = array_merge($valid_forums, $elected_forums);
	$queried_forums = array_unique($queried_forums);

	// Further filter the number of messages sent by including only forums from which the user
	// specifically wants to get emails. If there are none, assume all.
	$i = 0;
	$subscribed_forums = '';
	$sql3 = "SELECT forum_id
		FROM " . DIGEST_SUBSCRIBED_FORUMS_TABLE . "
		WHERE user_id = " . $row['user_id'];
	$result3 = $db->sql_query($sql3);

	while ($row3 = $db->sql_fetchrow($result3))
	{
		$subscribed_forums [$i] = $row3['forum_id'];
		$i++;
	}

	$db->sql_freeresult($result3);

	// If there are subscribed forums, we only want to see messages for these forums.
	if ($i <> 0)
	{
		$queried_forums = array_intersect($queried_forums, $subscribed_forums);
	}

	// Create a list of forums to be queried from the database. This is a comma delimited list of all forums
	// the user is allowed to read that can be used with the SQL IN operation.
	$forums_list = implode(',', $queried_forums);
	if (empty($forums_list))
	{
		continue;
	}

	// Format sender's email address (SMTP seems to have a problem with adding username)
	$to = ($config['smtp_delivery']) ? $row['user_email'] : $row['username'] . ' <' . $row['user_email'] . '>';

	// Show the text of the message?
	$show_text = ($row['show_text'] == 'YES') ? true: false;

	// Show messages written by this user?
	$show_mine = ($row['show_mine'] == 'YES') ? true: false;

	// Prepare to get digest type
	if($row['digest_type'] == 'DAY')
	{
		$msg_period = $lang['digest_period_24_hrs'];
		$period = time() - (24 * 60 * 60);
	}
	else
	{
		$msg_period = $lang['digest_period_1_week'];
		$period = time() - (7 * 24 * 60 * 60);
	}

	// Format differently if HTML requests
	if($row['format'] == 'HTML')
	{
		$html = true;
		$parastart = '<p>';
		$paraend = '</p>' . $line_break;
	}
	else
	{
		$html = false;
		$parastart = '';
		$paraend = $line_break . $line_break;
	}

	// Set part of SQL needed to retrieve new only, or messages through the selected period
	if ($row['new_only'] == 'TRUE')
	{
		$code = max($period, $last_visited_date);
	}
	else
	{
		$code = $period;
	}

	// Filter out user's own postings, if they so elected
	if ($show_mine == false)
	{
		$code .= ' and p.poster_id <> ' . $row['user_id'];
	}

	// The emailer class does not have the equivalent of the assign_block_vars operation, so the
	// entire digest must be placed inside a variable.
	$msg = '';

	// Create a list of messages for this user that presumably have not been seen.
	// Filter out unauthorized forums.
	$sql2 = "SELECT f.forum_name, t.topic_title, u.username AS 'Posted by', p.post_time, p.post_text, p.post_id, t.topic_id, f.forum_id
		FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . USERS_TABLE . " u
		WHERE p.topic_id = t.topic_id
			AND t.forum_id = f.forum_id
			AND p.poster_id = u.user_id
			AND f.forum_type = " . FORUM_POST . "
			AND p.post_time > " . $code . "
			AND f.forum_id IN (" . $forums_list . ")
		ORDER BY f.forum_order, t.topic_title, p.post_time";

	// Uncomment next line to see SQL used
	// $msg .= "**DEBUG**\r\n' . $sql2 . '\r\n**DEBUG**\r\n";

	$result2 = $db->sql_query($sql2);

	// Format all the mail for this user

	$last_forum = '';
	$last_topic = '';
	$msg_count = 0;

	while ($row2 = $db->sql_fetchrow($result2))
	{

		// Calculate Display Time
		$display_time = date(DIGEST_DATE_FORMAT, $row2['post_time']);

		// Format Post Text
		$post_text = (strlen($row2['post_text']) <= $row['text_length']) ? $row2['post_text'] : substr($row2['post_text'], 0, $row['text_length']) . '...';

		// Close table if topic is changed
		if (($row2['topic_title'] <> $last_topic) && $html && ($last_topic <> ''))
		{
			$msg .= '</table><br /><br />' . $line_break;
		}

		// Show name of forum only if it changes
		if ($row2['forum_name'] <> $last_forum)
		{
			if ($config['url_rw'] == '1')
			{
				$forum_url = DIGEST_SITE_URL . str_replace ('--', '-', make_url_friendly($row2['forum_name']) . '-vf' . $row2['forum_id'] . '.html');
			}
			else
			{
				$forum_url = DIGEST_SITE_URL . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $row2['forum_id'];
			}
			if ($html)
			{
				if ($last_forum <> '')
				{
					//$msg .= '</table><br /><hr /><br />' . $line_break;
					$msg .= '<br /><hr /><br />' . $line_break;
				}
				$msg .= '<h2>' . $lang['digest_forum'] . '<a href="' . $forum_url . '">' . $row2['forum_name'] . '</a></h2>' . $line_break;
			}
			else
			{
				$msg .= $line_break . "<<<< " . $lang['digest_forum'] . ' ' . $row2['forum_name'] . ', ' . $forum_url . " >>>>" . $line_break;
			}
		}

		// Show name of topic only if it changes
		if ($row2['topic_title'] <> $last_topic)
		{
			if ($config['url_rw'] == '1')
			{
				$topic_url = DIGEST_SITE_URL . str_replace ('--', '-', make_url_friendly($row2['topic_title']) . '-vt' . $row2['topic_id'] . '.html');
			}
			else
			{
				$topic_url = DIGEST_SITE_URL . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $row2['topic_id'];
			}
			if ($html)
			{
				$msg .= '<h3>' . $lang['digest_topic'] . '<a href="' . $topic_url . '">' . $row2['topic_title'] . '</a></h3>' . $line_break;
				$msg .= '<table class="forumline" width="98%"cellspacing="0" cellpadding="2">' . $line_break;
			}
			else
			{
				$msg .= $line_break . "<< " . $lang['digest_topic'] . ' ' . $row2['topic_title'] . ', ' . $topic_url . " >>" . $line_break . $line_break;
			}
		}

		// Show message information
		if ($config['url_rw'] == '1')
		{
			$post_url = DIGEST_SITE_URL . str_replace ('--', '-', make_url_friendly($row2['topic_title']) . '-vp' . $row2['post_id'] . '.html#p' . $row2['post_id']);
		}
		else
		{
			$post_url = DIGEST_SITE_URL . CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $row2['topic_id'] . '#p' . $row2['post_id'];
		}
		if ($html)
		{
			$msg .= '<tr>' . $line_break;
			$msg .= '<th style="text-align: left;">' . $lang['digest_link'] . ': <a href="' . $post_url . '">' . $row2['post_id'] . '</a> - ' . $lang['digest_post_time'] . ': ' . $display_time . ' ' . gmdate('T', $row2['post_time']) . ' - ' . $lang['digest_author'] . ': ' . $row2['Posted by'] . '</th>' . $line_break;
			$msg .= '</tr>' . $line_break;
			if ($show_text)
			{
				/*
				// Remove BBCode and replace \n with <br />, makes for nicer presentation
				$this_msg = '<td>' . preg_replace('/\[\S+\]/', '', $post_text) . '</td>';
				$this_msg = preg_replace('/\\n/', '<br />', $this_msg);
				$msg .= $this_msg . $line_break;
				*/
				if (empty($bbcode) && class_exists('bbcode'))
				{
					unset($bbcode);
					$bbcode = new bbcode();
				}
				$bbcode->allow_html = (isset($config['allow_html']) ? $config['allow_html'] : false);
				$bbcode->allow_bbcode = (isset($config['allow_bbcode']) ? $config['allow_bbcode'] : true);
				$bbcode->allow_smilies = (isset($config['allow_smilies']) ? $config['allow_smilies'] : true);
				$post_text = $bbcode->parse($post_text);
				$this_msg = '<td class="row1"><div class="post-text">' . $post_text . '</div></td>';
				if (empty($bbcode->allow_bbcode))
				{
					$this_msg = str_replace("\n", "<br />", preg_replace("/\r\n/", "\n", $this_msg));
				}
				$msg .= '<tr>' . $line_break;
				$msg .= $this_msg . $line_break;
				$msg .= '</tr>' . $line_break;
				$msg .= '<tr>' . $line_break;
				$msg .= '<td class="row2"><div class="post-text">&nbsp;</div></td>' . $line_break;
				$msg .= '</tr>' . $line_break;
			}
		}
		else
		{
			$msg .= $lang['digest_posted_by'] . $row2['Posted by'] . $lang['digest_posted_at'] . $display_time . ' ' . gmdate('T', $row2['post_time']) .
				', ' . $post_url . '' . $line_break;

			// If requested to show the message text
			if ($show_text)
			{
				if (strlen($post_text) < ($row['text_length'] + 3))
				{
					$msg .= $post_text . $line_break;
				}
				else
				{
					// Remove BBCode, makes for nicer presentation
					$msg .= $lang['digest_message_excerpt'] . ': ' . preg_replace('/\[\S+\]/', '', $post_text) . $line_break;
				}
				$msg .= $line_break . '------------------------------' . $line_break;
			}
		}

		// If the forum has changed, note the change
		if ($row2['forum_name'] <> $last_forum)
		{
			$last_forum = $row2['forum_name'];
		}
		if ($row2['topic_title'] <> $last_topic)
		{
			$last_topic = $row2['topic_title'];
		}

		$msg_count++;
	}

	$db->sql_freeresult($result2);

	if ($html && ($last_topic <> ''))
	{
		$msg .= '</table>' . $line_break;
	}

	if ($msg_count == 0)
	{
		$msg .= $parastart . $lang['digest_no_new_messages'] . $paraend;
	}

	// Send the email if there are messages or if user selected to send email anyhow
	if (($msg_count > 0) || ($row['send_on_no_messages'] == 'YES'))
	{

		if (!(is_object($emailer)))
		{
			$emailer = new emailer();
		}

		$emailer->use_template('mail_digests', $row['user_lang']);
		if ($html)
		{
			// Apply a style sheet if requested for HTML digest. If no style sheet is wanted then the
			// link tag pointing to the style sheet is not displayed. A custom style sheet gets first priority.
			/*
			if ($link_tag_unset)
			{
				$stylesheet = '';
				if (DIGEST_USE_CUSTOM_STYLESHEET)
				{
					$stylesheet = DIGEST_CUSTOM_STYLESHEET_PATH;
				}
				elseif (DIGEST_USE_DEFAULT_STYLESHEET)
				{
					// Get the default style sheet to apply to the HTML email
					$sql2 = 'SELECT style_name, head_stylesheet
						FROM ' . THEMES_TABLE . '
						WHERE themes_id = ' . $config['default_style'];
					$result2 = $db->sql_query($sql2);
					$row2 = $db->sql_fetchrow($result2);
					$stylesheet = 'templates/' . $row2['style_name'] . '/' . $row2['head_stylesheet'];
					$db->sql_freeresult($result2);
				}
				if ($stylesheet <> '')
				{
					$link_tag = '<link rel="stylesheet" type="text/css" href="' . DIGEST_SITE_URL . $stylesheet . '" />';
				}
				$link_tag_unset = false;
			}
			*/
		}

		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';
		$config['html_email'] = $html;
		$emailer->from = $lang['digest_from_text_name'] . ' <' . $lang['digest_from_email_address'] . '>';
		$emailer->to($to);
		$emailer->set_subject($lang['digest_subject_line']);

		$emailer->assign_vars(array(
			'BOARD_URL' => DIGEST_SITE_URL,
			'LINK' => $link_tag,
			'L_SITENAME' => $config['sitename'],
			'L_SALUTATION' => $lang['digest_salutation'],
			'SALUTATION' => $row['username'],
			'L_DIGEST_OPTIONS' => $lang['digest_your_digest_options'],
			'L_INTRODUCTION' => $lang['digest_introduction'],
			'L_FORMAT' => $lang['digest_format_short'],
			'FORMAT' => $row['format'],
			'L_MESSAGE_TEXT' => $lang['digest_show_message_text'],
			'MESSAGE_TEXT' => $row['show_text'],
			'L_MY_MESSAGES' => $lang['digest_show_my_messages'],
			'MY_MESSAGES' => $row['show_mine'],
			'L_FREQUENCY' => $lang['digest_frequency'],
			'FREQUENCY' => $row['digest_type'],
			'L_NEW_MESSAGES' => $lang['digest_show_only_new_messages'],
			'NEW_MESSAGES' => $row['new_only'],
			'L_SEND_DIGEST' => $lang['digest_send_if_no_new_messages'],
			'SEND_DIGEST' => $row['send_on_no_messages'],
			'L_SEND_TIME' => $lang['digest_hour_to_send_short'],
			'SEND_TIME' => gmdate('g A', gmmktime($send_hour)),
			'DIGEST_CONTENT' => $msg,
			'DISCLAIMER' => ($html) ? $lang['digest_disclaimer_html'] : $lang['digest_disclaimer_text'],
			'L_TEXT_LENGTH' => $lang['digest_message_size'],
			'TEXT_LENGTH' => $row['text_length'],
			'L_VERSION' => $lang['digest_version_text'],
			'VERSION' => DIGEST_VERSION
			)
		);
		$emailer->send($html);
		$emailer->reset();

		$digests_sent++;

	}

	// Normally this is run as a batch job, but it can be useful to get summary information of what was sent and to whom.
	if (DIGEST_SHOW_SUMMARY)
	{
		$digest_log_entry .= $lang['digest_a_digest_containing'] . ' ' . $msg_count . ' ' . $lang['digest_posts_was_sent_to'] . ' ' . $row['user_email'] . $break_type;
	}
}

// Summary information normally not seen, but can be captured via command line to a file
if (DIGEST_SHOW_SUMMARY)
{
	$summary_content = '';
	if (DIGEST_SHOW_SUMMARY_TYPE == 'html')
	{
		$summary_content .= '<html>' . $line_break;
		$summary_content .= '<head>' . $line_break;
		$summary_content .= '<title>' . $lang['digest_summary'] . '</title>' . $line_break;
		$summary_content .= '</head>' . $line_break;
		$summary_content .= '<body>' . $line_break;
		$summary_content .= '<h1>' . $lang['digest_summary'] . '</h1>' . $line_break;
	}
	$summary_content .= $digest_log_entry;
	if (DIGEST_SHOW_SUMMARY_TYPE == 'html')
	{
		$summary_content .= '<hr />' . $line_break;
	}
	$summary_content .= $lang['digest_a_total_of'] . ' ' . $digests_sent . ' ' . $lang['digest_were_emailed'] . $break_type;
	$summary_content .= $lang['digest_server_date'] . ' ' . gmdate(DIGEST_SERVER_DATE_DISPLAY) . $break_type;
	$summary_content .= $lang['digest_server_hour'] . ' ' . gmdate('H') . $break_type;
	$summary_content .= $lang['digest_server_time_zone'] . ' ' . gmdate('Z') / 3600 . ' ' . $lang['digest_or'] . ' ' . gmdate('T') .  $break_type;
	if (DIGEST_SHOW_SUMMARY_TYPE == 'html')
	{
		$summary_content .= '</body>' . $line_break;
		$summary_content .= '</html>' . $line_break;
	}

	if (!defined('PHP_DIGESTS_CRON'))
	{
		echo($summary_content);
	}
	else
	{
		// MG Digests LOG - BEGIN
		if ($config['write_digests_log'] == true)
		{
			//echo($summary_content);
			$datecode = gmdate('Ymd');
			$logs_path = !empty($config['logs_path']) ? $config['logs_path'] : 'logs';
			$logdigests = $logs_path . '/digests_' . $datecode . '.txt';
			$date = gmdate('Y/m/d - H:i:s');
			$log_message = '[' . $date . ']' . $line_break . $line_break;
			$log_message .= $summary_content;
			$log_message .= $line_break . $line_break;
			$log_message .= '________________________________________________';
			$log_message .= $line_break . $line_break;
			$fp = fopen($logdigests, "a+");
			fwrite($fp, $log_message);
			fclose($fp);
		}
		// MG Digests LOG - END
	}
}

$db->sql_freeresult($result);

set_config('digests_last_send_time', time());
set_config('digests_php_cron_lock', 0);

?>