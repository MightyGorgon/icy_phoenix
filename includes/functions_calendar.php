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
* Ptirhiik (admin@rpgnet-fr.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

include_once(IP_ROOT_PATH . './includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . './includes/bbcode.' . PHP_EXT);

// function select
function calendar_get_tree_option($cur = '')
{
	global $db, $userdata, $lang;
	global $bbcode;
	// init
	if (empty($cur) || ($cur == 'Root'))
	{
		$cur = POST_CAT_URL . 0;
	}
	$type = substr($cur, 0, 1);
	$id = intval(substr($cur, 1));
	if (($id == 0) || !in_array($type, array(POST_CAT_URL, POST_FORUM_URL)))
	{
		$type = POST_CAT_URL;
		$id = 0;
	}
	$cur = $type . $id;

	// init res
	$selected = ($cur == POST_CAT_URL . 0) ? ' selected="selected"' : '';
	$res = '<option value="Root"' . $selected . '>' . $lang['Forum_index'] . '</option>';

	// get auth read
	$is_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
	$forum_ids = array();
	while (list($forum_id, $auth) = each($is_auth))
	{
		if ($auth['auth_read'] && $auth['auth_view'])
		{
			$forum_ids[] = $forum_id;
		}
	}
	if (empty($forum_ids)) return $res;

	// list of forums authed
	$s_forum_list = implode(', ', $forum_ids);
	$sql = "SELECT f.cat_id, c.cat_title, f.forum_id, f.forum_name
				FROM " . FORUMS_TABLE . " AS f, " . CATEGORIES_TABLE . " AS c
				WHERE c.cat_id = f.cat_id
					AND f.forum_id IN ($s_forum_list)
				ORDER BY cat_order, cat_title, forum_order, forum_name";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Couldn not obtain forums/categories informations', '', __LINE__, __FILE__, $sql);
	}
	$cat_id = -1;
	$first = true;
	while ($row = $db->sql_fetchrow($result))
	{
		// category
		if (($row['cat_id'] != $cat_id) || $first)
		{
			$first = false;
			$cat_id = $row['cat_id'];
			$fid = POST_CAT_URL . $row['cat_id'];
			$selected = ($cur == $fid) ? ' selected="selected"' : '';
			$res .= sprintf('<option value="%s"%s>|--[ %s ]</option>', $fid, $selected, str_replace("''", "\'", $row['cat_title']));
		}

		// forum
		$fid = POST_FORUM_URL . $row['forum_id'];
		$selected = ($cur == $fid) ? ' selected="selected"' : '';
		$res .= sprintf('<option value="%s"%s>|&nbsp;&nbsp;&nbsp;|--- %s</option>', $fid, $selected, str_replace("''", "\'", $row['forum_name']));
	}
	$db->sql_freeresult($result);

	return $res;
}

function calendar_forum_select($selected_id = '')
{
	global $db, $userdata, $lang;
	global $bbcode;
	$forum_list = '<select name="selected_id" onchange="forms[\'f_calendar\'].submit();">' . get_tree_option($selected_id) . '</select>';
	//$forum_list = '<select name="selected_id" onchange="forms[\'f_calendar\'].submit();">' . calendar_get_tree_option($selected_id) . '</select>';

	return $forum_list;
}

// translate a date for display
function date_dsp($format, $date)
{
	global $board_config, $lang;
	static $translate;

	if (empty($translate) && $board_config['default_lang'] != 'english')
	{
		@reset($lang['datetime']);
		while (list($match, $replace) = @each($lang['datetime']))
		{
			$translate[$match] = $replace;
		}
	}
	return (!empty($translate)) ? strtr(date($format, $date), $translate) : date($format, $date);
}

function get_calendar_title_date($calendar_start, $calendar_duration)
{
	global $lang, $images, $board_config, $userdata;
	global $bbcode;
	if (empty($calendar_start)) return '';

	// get the component of the date and duration
	$year = 0;
	$month = 0;
	$day = 0;
	$hour = 0;
	$min = 0;
	$d_day = 0;
	$d_hour = 0;
	$d_min = 0;
	if (!empty($calendar_start))
	{
		$year = intval(date('Y', $calendar_start));
		$month = intval(date('m', $calendar_start));
		$day = intval(date('d', $calendar_start));
		$hour = intval(date('H', $calendar_start));
		$min = intval(date('i', $calendar_start));
		if (!empty($calendar_duration))
		{
			$d_dur = intval($calendar_duration);
			$d_day = intval($d_dur / 86400);
			$d_dur = $d_dur - 86400 * $d_day;
			$d_hour = intval($d_dur / 3600);
			$d_dur = $d_dur - 3600 * $d_hour;
			$d_min = intval($d_dur / 60);
		}
	}

	// quit if no date
	if (empty($year) || empty($month) || empty($day)) return '';

	// raz duration less than 1 day if no time for event start
	if (empty($hour) && empty($min))
	{
		$d_hour = 0;
		$d_min = 0;
	}

	// add the time to start date if present
	$fmt_start = $lang['DATE_FORMAT2'];
	if (!empty($hour))
	{
		$fmt_start = $board_config['default_dateformat'];
	}

	// add the time to end date if duration
	$fmt_end = $lang['DATE_FORMAT2'];
	if (!empty($hour) || !empty($d_hour))
	{
		$fmt_end = $board_config['default_dateformat'];
	}

	// apply it to dates
	$date_start = date_dsp($fmt_start, $calendar_start);
	$date_end = date_dsp($fmt_end, $calendar_start + $calendar_duration);

	// add period to the title
	$calendar_icon = '<a href="' . append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT . '?start=' . date('Ymd', $calendar_start)). '"><img src="' . $images['icon_calendar'] . '" hspace="3" border="0" valign="top" alt="' . $lang['Calendar_event'] . '" /></a>';
	if (empty($calendar_duration))
	{
		$res = sprintf($lang['Calendar_time'], $date_start);
	}
	else
	{
		$res = sprintf($lang['Calendar_from_to'], $date_start, $date_end);
	}

	return $res;
}

function get_calendar_title($calendar_start, $calendar_duration)
{
	global $bbcode;
	if (empty($calendar_start)) return '';

	$calendar_title = get_calendar_title_date($calendar_start, $calendar_duration);
	if (empty($calendar_title)) return '';

	// send back the full title
	$res = '<span class="gensmall"><br />' . $calendar_title . '</span>';
	return $res;
}

/*
* Return true if the year is a leap year
* You can also use this one... but it works only for valid UNIX TIMESTAMP... if you want to check year 3245 for example, it won't work...
* $d_isleapyear = date('L', mktime(0, 0, 0, $myMonth, 1, $myYear));    // is YYYY a leapyear?
*/
function is_leap_year($year)
{
	if(($year % 400) == 0)
	{
		return true;
	}
	elseif(($year % 100) == 0)
	{
		return false;
	}
	elseif(($year % 4) == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//------------------------------------------------------------------
// Event management : all events are stored in the array events
// ----------------
//	structure of this array :
//
//
//		event_id : letter + id : ie u2 = User, user_id=2
//
//		event_author_id :			id of the author of the event (for topic : topic poster)
//		event_author :				name of the event author
//		event_time :				date-time of the event creation
//
//		event_last_author_id :		for topics : author id of the last reply
//		event_last_author :			for topics : author name of the last reply
//		event_last_time :			for topics : date-time of creation of the last reply
//
//		event_replies :				for topics : number of replies
//		event_views :				for topics : number of views
//		event_type :				for topics : topic type
//		event_vote :				for topics : poll present
//		event_status :				for topics : topic status
//		event_moved_id :			for topics : topic moved id
//		event_last_id :				for topics : last post id
//		event_forum_id :			for topics : forum id
//
//		event_icon :				icon for the event title
//		event_title :				title of the event
//		event_short_title			short title of the event (according to the number of char allowed)
//		event_message :				full message (will be used as the overview flying window)
//		event_calendar_time :		start date-time of the event
//		event_calendar_duration :	duration of the event (in seconds)
//
//		event_link :				link to what should be called when clicking to the link
//		event_txt_class :			class of CSS used to display the title in the calendar cells
//		event_type_icon :			icon set to recognize a type of event in the calendar (full HTML <img src="...)
//------------------------------------------------------------------

//
// topics
//
function get_event_topics(&$events, &$number, $start_date, $end_date, $limit=false, $start=0, $max_limit=-1, $fid='')
{
	global $template, $lang, $images, $userdata, $board_config, $db;
	global $tree;
	global $bbcode;

	// Define censored word matches
	if (!$userdata['user_allowswearywords'])
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}

	// get some parameter
	$topic_title_length = isset($board_config['calendar_title_length']) ? intval($board_config['calendar_title_length']) : 30;
	$topic_text_length = isset($board_config['calendar_text_length']) ? intval($board_config['calendar_text_length']) : 200;
	if ($max_limit < 0)
	{
		$max_limit = $board_config['topics_per_page'];
	}

	// get the forums authorized (compliency with categories hierarchy v2 mod)
	$cat_hierarchy = function_exists(get_auth_keys);
	$s_forums_ids = '';
	if (!$cat_hierarchy)
	{
		// standard read
		$is_auth = array();
		$is_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);

		// forum or cat asked
		$is_ask = array();
		if (($fid == 'Root') || ($fid == POST_CAT_URL . 0))
		{
			$fid = '';
		}
		if (!empty($fid))
		{
			$type = substr($fid, 0, 1);
			$id = intval(substr($fid, 1));
			if ($type == POST_CAT_URL)
			{
				$sql = "SELECT forum_id FROM " . FORUMS_TABLE . " WHERE cat_id = '" . $id . "'";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not obtain forums informations', '', __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$is_ask[$row['forum_id']] = true;
				}
				$db->sql_freeresult($result);
			}
			elseif ($type == POST_FORUM_URL)
			{
				$is_ask[$id] = true;
			}
			else
			{
				return;
			}
		}

		// get the list of authorized forums
		while (list($forum_id, $forum_auth) = each($is_auth))
		{
			if ($forum_auth['auth_read'] && (empty($fid) || isset($is_ask[$forum_id])))
			{
				$s_forum_ids .= (empty($s_forum_ids) ? '' : ', ') . $forum_id;
			}
		}
	}
	else
	{
		if (empty($fid) || ($fid == POST_CAT_URL . 0))
		{
			$fid = 'Root';
		}

		// get auth key
		$keys = array();
		$keys = get_auth_keys($fid, true, -1, -1, 'auth_read');
		for ($i = 0; $i < count($keys['id']); $i++)
		{
			if (($tree['type'][$keys['idx'][$i]] == POST_FORUM_URL) && $tree['auth'][ $keys['id'][$i] ]['auth_read'])
			{
				$s_forum_ids .= (empty($s_forum_ids) ? '' : ', ') . $tree['id'][$keys['idx'][$i]];
			}
		}
	}

	// no forums authed, return
	if (empty($s_forum_ids))
	{
		return;
	}

	// select topics
	$sql_forums_field = '';
	$sql_forums_file = '';
	$sql_forums_match = '';
	if (!$cat_hierarchy)
	{
		$sql_forums_field = ', f.forum_name';
		$sql_forums_file = ', ' . FORUMS_TABLE . ' AS f';
		$sql_forums_match = ' AND f.forum_id = t.forum_id';
	}
	$sql = "SELECT
					t.*,
					p.poster_id, p.post_username, p.post_text, p.enable_bbcode, p.enable_html, p.enable_smilies,
					u.username, u.user_active, u.user_color,
					lp.poster_id AS lp_poster_id,
					lu.username AS lp_username,
					lp.post_username AS lp_post_username,
					lp.post_time AS lp_post_time
					$sql_forums_field
			FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u, " . POSTS_TABLE . " AS lp, " . USERS_TABLE . " lu $sql_forums_file
			WHERE
				t.forum_id IN ($s_forum_ids)
				AND p.post_id = t.topic_first_post_id
				AND u.user_id = p.poster_id
				AND lp.post_id = t.topic_last_post_id
				AND lu.user_id = lp.poster_id
				AND t.topic_calendar_time < $end_date
				AND (t.topic_calendar_time + t.topic_calendar_duration) >= $start_date
				AND t.topic_status <> " . TOPIC_MOVED . "
				$sql_forums_match
			ORDER BY
				t.topic_calendar_time, t.topic_calendar_duration DESC, t.topic_last_post_id DESC";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain topics information', '', __LINE__, __FILE__, $sql);
	}

	// get the number of occurences
	$number = $db->sql_numrows($result);

	// if limit per page asked, limit the number of results
	if ($limit)
	{
		$db->sql_freeresult($result);
		$sql .= " LIMIT $start, $max_limit";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain topics information', '', __LINE__, __FILE__, $sql);
		}
	}

	// read the items
	while ($row = $db->sql_fetchrow($result))
	{
		// prepare the message
		$topic_author_id = $row['poster_id'];
		$topic_author = ($row['poster_id'] == ANONYMOUS) ? $row['post_username'] : $row['username'];
		$topic_time = $row['topic_time'];

		$topic_last_author_id = $row['lp_poster_id'];
		$topic_last_author = ($row['lp_poster_id'] == ANONYMOUS) ? $row['lp_post_username'] : $row['lp_username'];
		$topic_last_time = $row['lp_post_time'];

		$topic_views = $row['topic_views'];
		$topic_replies = $row['topic_replies'];

		$topic_icon = $row['topic_icon'];
		$topic_title = $row['topic_title'];
		$message = htmlspecialchars($row['post_text']);

		$topic_calendar_time = $row['topic_calendar_time'];
		$topic_calendar_duration = $row['topic_calendar_duration'];
		$topic_link = append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $row['topic_id']);

		// censor topic_title
		if (count($orig_word))
		{
			$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
			$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
		}
		global $bbcode, $board_config;
		include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		$short_title = (strlen($topic_title) > $topic_title_length + 3) ? substr($topic_title, 0, $topic_title_length) . '...' : $topic_title;
		// Convert and clean special chars!
		$topic_title = htmlspecialchars_clean($topic_title);
		$short_title = htmlspecialchars_clean($short_title);
		// SMILEYS IN TITLE - BEGIN
		if (($board_config['smilies_topic_title'] == true) && !$lofi)
		{
			$bbcode->allow_smilies = ($board_config['allow_smilies'] ? true : false);
			$topic_title = $bbcode->parse_only_smilies($topic_title);
			$short_title = $bbcode->parse_only_smilies($short_title);
		}
		// SMILEYS IN TITLE - END

		$dsp_topic_icon = '';
		if (function_exists(get_icon_title))
		{
			$dsp_topic_icon = get_icon_title($topic_icon, 0, POST_CALENDAR);
		}

		// parse the message
		$message = substr($message, 0, $topic_text_length);

		// remove HTML if not allowed
		if (!$board_config['allow_html'] && $row['enable_html'])
		{
			$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
		}

		$html_on = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? 1 : 0 ;
		$bbcode_on = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? 1 : 0 ;
		$smilies_on = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? 1 : 0 ;

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;

		$message = $bbcode->parse($message);

		// get the date format
		$fmt = $lang['DATE_FORMAT2'];
		if (!empty($topic_calendar_duration))
		{
			$fmt = $board_config['default_dateformat'];
		}

		// replace \n with <br />
		//$message = preg_replace("/[\n\r]{1,2}/", '<br />', $message);

		// build the overview
		$sav_tpl = $template->_tpldata;
		$det_handler = '_overview_topic_' . $row['topic_id'];
		$template->set_filenames(array(
			$det_handler => 'calendar_overview_topic.tpl')
		);

		$nav_desc = '';
		if ($cat_hierarchy)
		{
			$nav_desc = make_cat_nav_tree(POST_FORUM_URL . $row['forum_id'], '', 'gensmall');
		}
		else
		{
			$nav_desc = '<a href="' . append_sid(IP_ROOT_PATH . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $row['forum_id']) . '" class="gensmall">' . $row['forum_name'] . '</a>';
		}
		$template->assign_vars(array(
			'L_CALENDAR_EVENT' => $lang['Calendar_event'],
			'L_AUTHOR' => $lang['Author'],
			'L_TOPIC_DATE' => $lang['Date'],
			'L_FORUM' => $lang['Forum'],
			'L_VIEWS' => $lang['Views'],
			'L_REPLIES' => $lang['Replies'],
			'TOPIC_TITLE' => $dsp_topic_icon . '&nbsp;' . $topic_title,
			'CALENDAR_EVENT' => get_calendar_title_date($topic_calendar_time, $topic_calendar_duration),
			'AUTHOR' => $topic_author,
			'TOPIC_DATE' => create_date($userdata['user_dateformat'], $topic_time, $board_config['board_timezone']),
			'NAV_DESC' => $nav_desc,
			'CALENDAR_MESSAGE'  => $message,
			'VIEWS' => $topic_views,
			'REPLIES' => $topic_replies,
			)
		);

		$template->assign_var_from_handle('_calendar_overview', $det_handler);
		$message = $template->_tpldata['.'][0]['_calendar_overview'];
		$template->_tpldata = $sav_tpl;

		// remove \n remaining from the template
		$message = preg_replace("/[\n\r]{1,2}/", '', $message);

		// store only the new values
		$new_row = array();
		$new_row['event_id'] = POST_TOPIC_URL . $row['topic_id'];

		$new_row['event_author_id'] = $topic_author_id;
		$new_row['event_author'] = $topic_author;
		$new_row['event_author_active'] = $row['user_active'];
		$new_row['event_author_color'] = $row['user_color'];
		$new_row['event_time'] = $topic_time;

		$new_row['event_last_author_id'] = $topic_last_author_id;
		$new_row['event_last_author'] = $topic_last_author;
		$new_row['event_last_time'] = $topic_last_time;

		$new_row['event_replies'] = $topic_replies;
		$new_row['event_views'] = $topic_views;
		$new_row['event_type'] = $row['topic_type'];
		$new_row['event_vote'] = $row['topic_vote'];
		$new_row['event_status'] = $row['topic_status'];
		$new_row['event_moved_id'] = $row['topic_moved_id'];
		$new_row['event_last_id'] = $row['topic_last_post_id'];
		$new_row['event_forum_id'] = $row['forum_id'];
		$new_row['event_forum_name'] = $row['forum_name'];

		$new_row['event_icon'] = $topic_icon;
		$new_row['event_title'] = $topic_title;
		$new_row['event_short_title'] = $short_title;
		$new_row['event_message'] = $message;
		$new_row['event_calendar_time'] = $topic_calendar_time;
		$new_row['event_calendar_duration'] = $topic_calendar_duration;
		$new_row['event_link'] = $topic_link;
		$new_row['event_birthday'] = false;
		$new_row['event_txt_class'] = 'genmed';
		$new_row['event_type_icon'] = '<img src="' . $images['icon_tiny_topic'] . '" border="0" valign="bottom" hspace="2" />';

		$events[] = $new_row;
	}
	$db->sql_freeresult($result);
}

/*
* Get birthdays for calendar
*/
function get_birthdays(&$events, &$number, $start_date, $end_date, $year = 0, $year_lt = false, $month = 0, $day = 0, $day_end = 0, $limit = 0, $show_inactive = false)
{
	global $lang, $images, $db;

	$birthdays_list = array();
	$birthdays_list = get_birthdays_list($year, $year_lt, $month, $day, $day_end, $limit, false);

	// get the number of occurences
	$number = count($birthdays_list);

	// read users
	for ($i = 0; $i < $number; $i++)
	{
		$user_id = $birthdays_list[$i]['user_id'];
		$username = $birthdays_list[$i]['username'];
		$user_birthday = realdate($lang['DATE_FORMAT2'], $birthdays_list[$i]['user_birthday']);

		// We cannot use colorize_username because this should be just the url... try to parse the color code instead
		$username_colorized = colorize_username($birthdays_list[$i]['user_id'], $birthdays_list[$i]['username'], $birthdays_list[$i]['user_color'], $birthdays_list[$i]['user_active'], true);
		$username_color = colorize_username($birthdays_list[$i]['user_id'], $birthdays_list[$i]['username'], $birthdays_list[$i]['user_color'], $birthdays_list[$i]['user_active'], false, true);
		// Trim last double quote...
		$username_color = (substr($username_color, -1) == '"') ? substr($username_color, 0, -1) : '';
		$username_link = append_sid(IP_ROOT_PATH . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '" ' . $username_color;

		$event_day = realdate('d', $birthdays_list[$i]['user_birthday']);
		$event_month = realdate('n', $birthdays_list[$i]['user_birthday']);
		$event_year2 = realdate('Y', $birthdays_list[$i]['user_birthday']);
		$start_month = intval(date('m', $start_date));
		$event_year = intval(date('Y', $start_date));
		if ($event_month < $start_month)
		{
			$event_year++;
		}
		$event_time = mktime(0, 0, 0, $event_month, $event_day, $event_year);


		$tmp_message = sprintf($lang['birthday'], $username_colorized);
		// It is JavaScript... we need to escape slashes
		$message = htmlspecialchars('<table class="forumline" width="100%" cellspacing="0" cellpadding="0"><tr><td class="row1" nowrap="nowrap"><b>' . $lang['birthday_header'] . '<\/b><span class="topiclink"><\/span><hr \/><span class="genmed">' . $tmp_message . '<\/span><\/td><\/tr><\/table>');
		$message = preg_replace("/[\n\r]{1,2}/", '', $message);

		$new_row = array();
		$new_row['event_id'] = POST_USERS_URL . $user_id;

		$new_row['event_author_id'] = $user_id;
		$new_row['event_author'] = $username;
		$new_row['event_time'] = $event_time;

		$new_row['event_last_author_id'] = '';
		$new_row['event_last_author'] = '';
		$new_row['event_last_time'] = '';

		$new_row['event_replies'] = '';
		$new_row['event_views'] = '';
		$new_row['event_type'] = POST_BIRTHDAY;
		$new_row['event_vote'] = '';
		$new_row['event_status'] = '';
		$new_row['event_moved_id'] = '';
		$new_row['event_last_id'] = '';
		$new_row['event_forum_id'] = '';
		$new_row['event_forum_name'] = '';

		$new_row['event_icon'] = '';
		$new_row['event_title'] = $username;
		$new_row['event_short_title'] = $username;
		$new_row['event_message'] = $message;
		$new_row['event_calendar_time'] = $event_time;
		$new_row['event_calendar_duration'] = '';
		$new_row['event_link'] = $username_link;
		$new_row['event_birthday'] = true;
		$new_row['event_txt_class'] = $txt_class;
		$new_row['event_type_icon'] = '<img src="' . $images['icon_tiny_profile'] . '" border="0" hspace="2" />';
		$events[] = $new_row;
	}
}

/*
* Get birthdays for calendar
*/
function get_birthdays_list($year = 0, $year_lt = false, $month = 0, $day = 0, $day_end = 0, $limit = 0, $show_inactive = false)
{
	global $db;

	$sql_where = '';
	if ($year_lt == false)
	{
		$sql_where .= ($year > 0) ? (' AND u.user_birthday_y = ' . $year) : '';
	}
	else
	{
		$sql_where .= ($year > 0) ? (' AND u.user_birthday_y <= ' . $year) : '';
	}

	if (($month > 0) && ($day_end > 0))
	{
		$month_start = $month;
		$month_end = $month;
		if ($day_end < $day)
		{
			$month_end = ($month_end == 12) ? 1 : $month_end;
			$sql_where .= ' AND (((u.user_birthday_m = ' . $month_start . ') AND (u.user_birthday_d >= ' . $day . ')) OR ((u.user_birthday_m = ' . $month_end . ') AND (u.user_birthday_d <= ' . $day_end . ')))';
		}
		else
		{
			$sql_where .= ' AND u.user_birthday_m = ' . $month;
			$sql_where .= ' AND u.user_birthday_d >= ' . $day;
			$sql_where .= ' AND u.user_birthday_d <= ' . $day_end;
		}
	}
	else
	{
		$sql_where .= ($month > 0) ? (' AND u.user_birthday_m = ' . $month) : '';
		$sql_where .= ($day > 0) ? (' AND u.user_birthday_d = ' . $day) : '';
	}

	// If WHERE still empty then query only users with a birthday
	$sql_where = ($sql_where == '') ? (' AND u.user_birthday <> 999999') : $sql_where;

	if ($show_inactive == false)
	{
		$sql_where .= (' AND user_active = 1');
	}

	$sql_limit = ($limit > 0) ? ('LIMIT ' . $limit) : '';

	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_birthday, u.user_birthday_y, u.user_birthday_m, u.user_birthday_d
				FROM " . USERS_TABLE . " AS u
				WHERE u.user_id <> " . ANONYMOUS . "
				" . $sql_where . "
				ORDER BY username
				" . $sql_limit;

	if (!$result = $db->sql_query($sql, false, 'birthdays_list_'))
	{
		message_die(GENERAL_ERROR, 'Could not read user table to get birthday info', '', __LINE__, __FILE__, $sql);
	}

	$birthdays_list = array();
	// read users
	while ($row = $db->sql_fetchrow($result))
	{
		$birthdays_list[] = $row;
	}
	$db->sql_freeresult($result);
	return $birthdays_list;
}

function display_calendar($main_template, $nb_days = 0, $start = 0, $fid = '')
{
	global $template, $lang, $images, $userdata, $board_config, $db, $bbcode, $tree;
	static $handler;
	if (empty($handler))
	{
		$handler = 1;
	}
	else
	{
		$handler++;
	}

	$day_of_week = array(
		$lang['datetime']['Sunday'],
		$lang['datetime']['Monday'],
		$lang['datetime']['Tuesday'],
		$lang['datetime']['Wednesday'],
		$lang['datetime']['Thursday'],
		$lang['datetime']['Friday'],
		$lang['datetime']['Saturday'],
	);
	$months = array(
		' ------------ ',
		$lang['datetime']['January'],
		$lang['datetime']['February'],
		$lang['datetime']['March'],
		$lang['datetime']['April'],
		$lang['datetime']['May'],
		$lang['datetime']['June'],
		$lang['datetime']['July'],
		$lang['datetime']['August'],
		$lang['datetime']['September'],
		$lang['datetime']['October'],
		$lang['datetime']['November'],
		$lang['datetime']['December'],
	);

	// get some parameter
	$first_day_of_week = isset($board_config['calendar_week_start']) ? intval($board_config['calendar_week_start']) : 1;
	$nb_row_per_cell = isset($board_config['calendar_nb_row']) ? intval($board_config['calendar_nb_row']) : 5;

	// get the start date - calendar doesn't go before 1971
	$cur_date = (empty($start) || (intval(date('Y', $start)) < 1971)) ? cal_date(time(), $board_config['board_timezone']) : $start;

	$cur_date = mktime(0, 0, 0, intval(date('m', $cur_date)), intval(date('d', $cur_date)), intval(date('Y', $cur_date)));

	$cur_month = 0;
	$cur_day = 0;

	// the full month is displayed
	if (empty($nb_days))
	{
		// set indicator
		$full_month = true;

		// set the start day on the start of the month
		$start_date = mktime(0, 0, 0, intval(date('m', $cur_date)), 01, intval(date('Y', $cur_date)));

		// get the day number set as start of the display
		$cfg_week_day_start = $first_day_of_week;

		// get the number of blank cells
		$start_inc = intval(date('w', $start_date)) - $cfg_week_day_start;
		if ($start_inc < 0)
		{
			$start_inc = 7 + $start_inc;
		}

		// Used to adjust birthdays SQL
		$cur_month = intval(date('n', $cur_date));

		// get the end date
		$year = intval(date('Y', $start_date));
		$month = intval(date('m', $start_date)) + 1;
		if ($month > 12)
		{
			$year++;
			$month = 1;
		}
		$end_date = mktime(0, 0, 0, $month, 01, $year);

		// set the number of cells per line
		$nb_cells = 7;

		// get the number of rows
		$nb_rows = intval(($start_inc + intval(($end_date - $start_date) / 86400)) / $nb_cells) + 1;
	}
	else
	{
		// set indicator
		$full_month = false;

		// set the start date to the day before the date selected
		$start_date = mktime(0, 0, 0, date('m', $cur_date), date('d', $cur_date) - 1, date('Y', $cur_date));

		// get the day number set as start of the week
		$cfg_week_day_start = intval(date('w', $start_date));

		// get the numbe of blank cells
		$start_inc = 0;

		// get the end date
		$end_date = mktime(0, 0, 0, date('m', $start_date), date('d', $start_date) + $nb_days, date('Y', $start_date));

		// set the number of cells per line
		$nb_cells = $nb_days;

		// set the number of rows
		$nb_rows = 1;
	}

	// Ok, let's get the various events :)
	$events = array();
	$number = 0;

	// topics
	get_event_topics($events, $number, $start_date, $end_date, false, 0, -1, $fid);

	$pages_array = array('calendar.' . PHP_EXT, FORUM_MG, VIEWFORUM_MG);
	//$current_page = $_SERVER['PHP_SELF'];
	$current_page = basename($_SERVER['PHP_SELF']);

	// No limits in calendar
	$day_end = 0;
	$birthdays_limit = 0;
	if ($current_page != 'calendar.' . PHP_EXT)
	{
		// Limit total birthdays in forum and viewforum... in large could take forever!
		$birthdays_limit = 50;
		// We are not in calendar, so we can force date to today!!!
		$cur_time = time() + (3600 * $board_config['board_timezone']);
		$cur_month = intval(date('n', $cur_time));
		$cur_day = intval(date('j', $cur_time));
		// Force one week walk forward...
		$days_walk_forward = 7;
		$day_end = intval(date('j', $cur_time + ($days_walk_forward * 86400)));
	}

	if (($board_config['calendar_birthday'] == true) && in_array(strtolower($current_page), $pages_array))
	{
		// get_birthdays(&$events, &$number, $start_date, $end_date, $year = 0, $year_lt = false, $month = 0, $day = 0, $day_end = 0, $limit = 0, $show_inactive = false)
		get_birthdays($events, $number, $start_date, $end_date, 0, false, $cur_month, $cur_day, $day_end, $birthdays_limit, false);
	}
	/*
	for ($i = 0; $i < count($pages_array); $i++)
	{
		if (strpos(strtolower($current_page), strtolower($pages_array[$i])) !== false)
		{
			get_birthdays($events, $number, $start_date, $end_date);
		}
	}
	*/

	// And now display them

	// build a list per date
	$map = array();
	for ($i = 0; $i < count($events); $i++)
	{
		$event_time = $events[$i]['event_calendar_time'];

		// adjust the event period to the start of day
		$event_time_end = $event_time + $events[$i]['event_calendar_duration'];
		$event_end = mktime(0, 0, 0, intval(date('m', $event_time_end)), intval(date('d', $event_time_end)), intval(date('Y', $event_time_end)));
		$event_start = mktime(0, 0, 0, intval(date('m', $event_time)), intval(date('d', $event_time)), intval(date('Y', $event_time)));

		if ($event_start < $start_date)
		{
			$event_start = $start_date;
		}
		if ($event_end > $end_date)
		{
			$event_end = $end_date;
		}

		// search a free day map offset in the start day
		$event_id = $events[$i]['event_id'];
		$offset_date = $event_start;
		$map_offset = count($map[$event_start]);
		$found = false;
		for ($k=0; ($k < count($map[$event_start])) && !$found; $k++)
		{
			if ($map[$event_start][$k] == -1)
			{
				$found = true;
				$map_offset = $k;
			}
		}

		// mark the offset as used for the whole event period
		$offset_date = $event_start;
		while ($offset_date <= $event_end)
		{
			for ($l = count($map[$offset_date]); $l <= $map_offset; $l++)
			{
				$map[$offset_date][$l] = -1;
			}
			$map[$offset_date][$map_offset] = $i;
			$offset_date = mktime(0, 0, 0, date('m', $offset_date), date('d', $offset_date)+1, date('Y', $offset_date));
		}
	}

	// template
	$template->set_filenames(array('_calendar_body' . $handler => 'calendar_box.tpl'));

	// buid select list for month
	$month = intval(date('m', $start_date));
	$s_month = '<select name="start_month" onchange="forms[\'f_calendar\'].submit();">';
	for ($i = 1; $i < count($months); $i++)
	{
		$selected = ($month == $i) ? ' selected="selected"' : '';
		$s_month .= '<option value="' . $i . '"' . $selected . '>' . $months[$i] . '</option>';
	}
	$s_month .= '</select>';

	// buid select list for year
	$year = intval(date('Y', $start_date));
	$s_year = '<select name="start_year" onchange="forms[\'f_calendar\'].submit();">';
	for ($i=1971; $i < 2070; $i++)
	{
		$selected = ($year == $i) ? ' selected="selected"' : '';
		$s_year .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
	}
	$s_year .= '</select>';

	// build a forum select list
	$s_forum_list = '<select name="selected_id" onchange="forms[\'f_calendar\'].submit();">' . get_tree_option($fid) . '</select>';

	// header
	$board_config['calendar_display_open'] = false;
	$template->assign_vars(array(
		'UP_ARROW' => $images['cal_up_arrow'],
		'DOWN_ARROW' => $images['cal_down_arrow'],
		'UP_ARROW2' => $images['arrow_up'],
		'DOWN_ARROW2' => $images['arrow_down'],
		'TOGGLE_ICON' => ($board_config['calendar_display_open'] == false) ? $images['cal_up_arrow'] : $images['cal_down_arrow'],
		'TOGGLE_ICON2' => ($board_config['calendar_display_open'] == false) ? $images['arrow_up'] : $images['arrow_down'],
		'TOGGLE_STATUS' => ($board_config['calendar_display_open'] == false) ? 'none' : '',
		)
	);
	$prec = (date('Ym', $start_date) > 197101) ? date('Ymd', mktime(0, 0, 0, date('m', $start_date) - 1, 01, date('Y', $start_date))) : date('Ymd', $start_date);
	$next = date('Ymd', mktime(0, 0, 0, date('m', $start_date)+1, 01, date('Y', $start_date)));
	$template->assign_block_vars('_calendar_box', array(
		'L_CALENDAR' => '<a href="' . append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT . '?start=' . date('Ymd', cal_date(time(),$board_config['board_timezone']))) . '"><img src="' . $images['icon_calendar'] . '" hspace="3" border="0" align="top" alt="' . $lang['Calendar_event'] . '" /></a>' . $lang['Calendar'],
		'L_CALENDAR_TXT' => $lang['Calendar'],
		'SPAN_ALL' => $nb_cells,
		'S_MONTH' => $s_month,
		'S_YEAR' => $s_year,
		'S_FORUM_LIST' => $s_forum_list,
		'L_GO' => $lang['Go'],
		'ACTION' => append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT),
		'U_PREC' => append_sid('calendar.' . PHP_EXT . '?start=' . $prec . '&amp;fid=' . $fid),
		'U_NEXT' => append_sid('calendar.' . PHP_EXT . '?start=' . $next . '&amp;fid=' . $fid),
		)
	);
	if ($full_month)
	{
		$template->assign_block_vars('_calendar_box.switch_full_month', array());
		$offset = $cfg_week_day_start;
		for ($j=0; $j < $nb_cells; $j++)
		{
			if ($offset >= count($day_of_week)) $offset = 0;
			$template->assign_block_vars('_calendar_box.switch_full_month._cell', array(
				'WIDTH' => floor(100 / $nb_cells),
				'L_DAY' => $day_of_week[$offset],
				)
			);
			$offset++;
		}
	}
	else
	{
		$template->assign_block_vars('_calendar_box.switch_full_month_no', array());
	}

	// display
	$offset_date = mktime(0, 0, 0, date('m', $start_date), date('d', $start_date) - $start_inc, date('Y', $start_date));
	for ($i=0; $i < $nb_rows; $i++)
	{
		$template->assign_block_vars('_calendar_box._row', array());
		for ($j=0; $j < $nb_cells; $j++)
		{
			$span = 1;

			// date less than start
			if (intval(date('Ymd', $offset_date)) < intval(date('Ymd', $start_date)))
			{
				// compute the cell to span
				$span = $start_inc;
				$j = $start_inc-1;
				$offset_date = mktime(0, 0, 0, date('m', $start_date), date('d', $start_date)-1, date('Y', $start_date));
			}

			// date greater than last
			if (intval(date('Ymd', $offset_date)) >= intval(date('Ymd', $end_date)))
			{
				// compute the cell to span
				$span = $nb_cells-$j;
				$j = $nb_cells;
			}

			$format = (intval(date('Ymd', $offset_date)) == intval(date('Ymd', cal_date(time(),$board_config['board_timezone'])))) ? '<b>%s</b>' : '%s';
			$template->assign_block_vars('_calendar_box._row._cell', array(
				'WIDTH' => floor(100 / $nb_cells),
				'SPAN' => $span,
				'DATE' => sprintf($format, date_dsp(($full_month ? '' : 'D ') . $lang['DATE_FORMAT2'], $offset_date)),
				'U_DATE' => append_sid(IP_ROOT_PATH . 'calendar_scheduler.' . PHP_EXT . '?d=' . $offset_date . '&amp;fid=' . $fid),
				)
			);
			// blank cells
			if ((intval(date('Ymd', $offset_date)) >= intval(date('Ymd', $start_date))) && (intval(date('Ymd', $offset_date)) < intval(date('Ymd', $end_date))))
			{
				$template->assign_block_vars('_calendar_box._row._cell.switch_filled', array(
					'EVENT_DATE' => $offset_date,
					'TOGGLE_STATUS' => 'none',
					'TOGGLE_ICON' => $images['down_arrow2'],
					)
				);

				// send events
				$more = false;
				$over = (count($map[$offset_date]) > $nb_row_per_cell);
				for ($k = 0; $k < count($map[$offset_date]); $k++)
				{
					// we are just over the limit
					if ($over && ($k == $nb_row_per_cell))
					{
						$more = true;
						$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event._more_header', array());
					}

					$ind = $map[$offset_date][$k];
					$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event', array(
						'U_EVENT' => $events[$ind]['event_link'],
						'EVENT_TYPE' => $events[$ind]['event_type_icon'],
						'EVENT_TITLE' => $events[$ind]['event_short_title'],
						'EVENT_CLASS' => $events[$ind]['event_txt_class'],
						'EVENT_MESSAGE' => str_replace(array('"', '\''), array('&quot;', '\\\''), $events[$ind]['event_message']),
						)
					);
					$flag = ($over && ($k == $nb_row_per_cell-1));
					if ($ind > -1)
					{
						$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event.switch_event', array());
						if ($flag)
						{
							$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event.switch_event._more', array());
						}
						else
						{
							$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event.switch_event._more_no', array());
						}
					}
					else
					{
						$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event.switch_event_no', array());
						if ($flag)
						{
							$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event.switch_event_no._more', array());
						}
						else
						{
							$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event.switch_event_no._more_no', array());
						}
					}

					if (($k == count($map[$offset_date])-1) && $more)
					{
						$template->assign_block_vars('_calendar_box._row._cell.switch_filled._event._more_footer', array());
					}
				}
			}
			else
			{
				$template->assign_block_vars('_calendar_box._row._cell.switch_filled_no', array());
			}
			$offset_date = mktime(0, 0, 0, date('m', $offset_date), date('d', $offset_date) + 1, date('Y', $offset_date));
		}
	}

	// fill the main template
	$template->assign_var_from_handle($main_template, '_calendar_body' . $handler);
}

?>