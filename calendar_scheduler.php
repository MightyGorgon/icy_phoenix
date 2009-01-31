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

// CTracker_Ignore: File checked by human
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_topics_list.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// some constants
$set_of_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$set_of_days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

// get parameters
// date
$date = 0;
if(isset($_POST['date']) || isset($_GET['d']))
{
	$date = isset($_POST['date']) ? intval($_POST['date']) : intval($_GET['d']);
}

if($date <= 0)
{
	$date = cal_date(time(),$board_config['board_timezone']);
}

// date per jumpbox
$start_month = intval($_POST['start_month']);
$start_year = intval($_POST['start_year']);
if ( !empty($start_month) && !empty($start_year) )
{
	$day = 01;
	if (!empty($date))
	{
		$day = date('d', $date);
	}
	$date = mktime(0, 0, 0, $start_month, $day, $start_year);
}

// mode
$mode = '';
$mode_array = array('hour');
if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = isset($_POST['mode']) ? $_POST['mode'] : $_GET['mode'];
}

if (!in_array($mode, $mode_array))
{
	$mode = '';
}

// start
$start = isset($_GET['start']) ? intval($_GET['start']) : (isset($_POST['start']) ? intval($_POST['start']) : 0);
$start = ($start < 0) ? 0 : $start;

// get the period
$year = date('Y', $date);
$month = date('m', $date);
$day = date('d', $date);
$hour = date('H', $date);
$min = date('i', $date);
if ($mode == 'hour')
{
	$start_date = mktime($hour, 0, 0, $month, $day, $year);
	$end_date = mktime($hour + 1, 0, 0, $month, $day, $year);
}
else
{
	$start_date = mktime(0, 0, 0, $month, $day, $year);
	$end_date = mktime(0 ,0 ,0, $month, $day + 1, $year);
}

// get the forum id selected
$fid = '';
if ( isset($_POST['selected_id']) || isset($_GET['fid']) )
{
	$fid = isset($_POST['selected_id']) ? $_POST['selected_id'] : $_GET['fid'];
	if ($fid != 'Root')
	{
		$type = substr($fid, 0, 1);
		$id = intval(substr($fid, 1));
		if (($id == 0) || !in_array($type, array(POST_FORUM_URL, POST_CAT_URL)))
		{
			$type = POST_CAT_URL;
			$id = 0;
		}
		$fid = $type . $id;
		if ($fid == POST_CAT_URL . '0')
		{
			$fid = 'Root';
		}
	}
}

// Get month events
$month_start = mktime(0, 0, 0, $month, 01, $year);
$month_end = mktime(0, 0, 0, $month + 1, 01, $year);
$number = 0;
$events = array();
get_event_topics($events, $number, $month_start, $month_end, false, 0, -1, $fid);

// get the days with events
$days = array();
for($i = 0; $i < count($events); $i++)
{
	// set the event on the month viewed
	$calendar_start = $events[$i]['event_calendar_time'];
	$calendar_end = $events[$i]['event_calendar_time'] + $events[$i]['event_calendar_duration'];
	if ($calendar_start < $month_start) $calendar_start = $month_start;
	if ($calendar_end >= $month_end) $calendar_end = $month_end - 1;
	$wstart = intval(date('d', $calendar_start));
	$wend = intval(date('d', $calendar_end));
	for($j = $wstart; $j <= $wend; $j++)
	{
		$days[$j] = true;
	}
}

// Get day events
$events = array();

// topics
$topics_count = 0;
$remaining = $board_config['topics_per_page'] - $displayed;
$local_start = $start-$displayed;
get_event_topics($events, $topics_count, $start_date, $end_date, true, $local_start, $remaining, $fid);
// set the page title and include the page header
$page_title = $lang['Calendar_scheduler'];
$meta_description = '';
$meta_keywords = '';

$today_birthdays_list = '';
if (($board_config['calendar_birthday'] == true))
{
	$b_year = date('Y', $date);
	$b_month = date('n', $date);
	$b_day = date('j', $date);
	$b_limit = 0;
	$birthdays_list = array();
	$birthdays_list = get_birthdays_list($b_year, true, $b_month, $b_day, 0, $b_limit, false);

	// get the number of occurences
	$number = count($birthdays_list);

	// read users
	for ($i = 0; $i < $number; $i++)
	{
		$today_birthdays_list .= (($today_birthdays_list == '') ? '' : ', ') . colorize_username($birthdays_list[$i]['user_id'], $birthdays_list[$i]['username'], $birthdays_list[$i]['user_color'], $birthdays_list[$i]['user_active']) . ' (' . (intval($b_year) - intval($birthdays_list[$i]['user_birthday_y'])) . ')';
	}
}
$today_birthdays_list = ($today_birthdays_list == '') ? $lang['None'] : $today_birthdays_list;

include (IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

// template name
$template->set_filenames(array('body' => 'calendar_scheduler_body.tpl'));
make_jumpbox(VIEWFORUM_MG);

// Header
$template->assign_vars(array(
	'L_CALENDAR_SCHEDULER' => $lang['Calendar_scheduler'],
	'U_CALENDAR_SCHEDULER' => append_sid('calendar_scheduler.' . PHP_EXT . '?d=' . $date . '&amp;mode=' . $mode . '&amp;start=' . $start),
	'L_BIRTHDAYS' => $lang['birthdays'],
	'TODAY_BIRTHDAYS_LIST' => $today_birthdays_list,
	)
);

// set a bar of hours
$work_date = mktime( 0, 0, 0, $month, $day, $year );
for($i = 0; $i <= 24; $i++)
{
	if ($i == 0)
	{
		$l_hour = $lang['All_events'];
		if ($mode != 'hour')
		{
			$color = 'quote';
		}
		else
		{
			$color = 'row2';
		}
	}
	else
	{
		$l_hour = date('H', $work_date);
		if (($mode == 'hour') && ($hour == $i - 1))
		{
			$color = 'quote';
		}
		else
		{
			$color = 'row3';
		}
		$work_date = $work_date + 3600;
	}
	$template->assign_block_vars('hour', array(
		'CLASS' => $color,
		'HOUR' => $l_hour,
		'U_HOUR' => append_sid('calendar_scheduler.' . PHP_EXT . '?' . (($i == 0) ? '' : 'mode=hour&amp;') . 'd=' . mktime((($i == 0) ? 0 : $i - 1), 0, 0, $month, $day, $year)),
		)
	);
}

// send the month box
$first_day_of_week = isset($board_config['calendar_week_start']) ? intval($board_config['calendar_week_start']) : 1;

// buid select list for month
$s_month = '';
for ($i = 0; $i < count($set_of_months); $i++)
{
	$selected = ($month == $i+1) ? ' selected="selected"' : '';
	$s_month .= '<option value="' . ($i + 1) . '"' . $selected . '>' . $lang['datetime'][$set_of_months[$i]] . '</option>';
}
$s_month = sprintf('<select name="start_month" onchange="forms[\'f_calendar_scheduler\'].submit();">%s</select>', $s_month);

// buid select list for year
$year = intval(date('Y', $start_date));
$s_year = '<select name="start_year" onchange="forms[\'f_calendar_scheduler\'].submit();">';
for ($i = 1971; $i < 2070; $i++)
{
	$selected = ($year == $i) ? ' selected="selected"' : '';
	$s_year .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}
$s_year .= '</select>';

// build a forum select list
$s_forum_list = '<select name="selected_id" onchange="forms[\'f_calendar_scheduler\'].submit();">' . get_tree_option($fid) . '</select>';

// send header
$k = $first_day_of_week;
for ($i = 0; $i <= 6; $i++)
{
	$template->assign_block_vars('header_cell', array(
		'L_DAY' => $lang['datetime'][$set_of_days[$k]],
		)
	);
	$k++;
	if ($k > 6) $k = 0;
}

$prec = mktime(0, 0, 0, $month - 1, $day, $year);
$next = mktime(0, 0, 0, $month + 1, $day, $year);
$template->assign_vars(array(
	'S_MONTH'				=> $s_month,
	'S_YEAR'				=> $s_year,
	'U_PREC'				=> append_sid('calendar_scheduler.' . PHP_EXT . '?d=' . $prec . '&amp;fid=' . $fid),
	'U_NEXT'				=> append_sid('calendar_scheduler.' . PHP_EXT . '?d=' . $next . '&amp;fid=' . $fid),
	'U_CALENDAR'		=> append_sid('calendar.' . PHP_EXT . '?start=' . $year . $month . '01&amp;fid=' . $fid),
	'L_CALENDAR'		=> $lang['Calendar'],
	'IMG_CALENDAR'	=> $images['icon_calendar'],
	)
);

// get first day of the month
$offset = date('w', mktime(0, 0, 0, $month, 01, $year)) - $first_day_of_week;
if ($offset < 0) $offset = $offset + 7;
$offset = mktime(0, 0, 0, $month, 01-$offset, $year);
$nb_days = intval((mktime(0, 0, 0, $month + 1, 01, $year) - $offset) / 86400);
$nb_rows = intval($nb_days / 7);

$start_m = mktime(0, 0, 0, $month, 01, $year);
$end_m = mktime(0, 0, 0, $month + 1, 01, $year);
$today = mktime(0, 0, 0, $month, $day, $year);
if (($nb_days % 7) > 0)
{
	$nb_rows++;
}

$color = false;
for ($j = 0; $j < $nb_rows; $j++)
{
	$template->assign_block_vars('row', array());
	$color = !$color;
	for ($i = 0; $i <= 6; $i++)
	{
		$cur = intval(date('d', $offset));
		$class = ($color) ? $theme['td_class1'] : $theme['td_class2'];
		if (($offset < $start_m) || ($offset >= $end_m))
		{
			$cur = '&nbsp;';
			$class = $theme['td_class3'];
		}
		if ($offset == $today)
		{
			$class = 'quote';
		}
		// Old condition removed...
		//if ($days[$cur])
		if ($cur != '&nbsp;')
		{
			$url = append_sid('calendar_scheduler.' . PHP_EXT . '?d=' . $offset . '&amp;fid=' . $fid);
			$cur = sprintf('<a href="%s" class="gensmall"><b>%s</b></a>', $url, $cur);
		}
		$template->assign_block_vars('row.cell', array(
			'CLASS' => $class,
			'DAY' => $cur,
			)
		);
		$offset = $offset + 86400;
	}
}

// list of topics
$period = ($mode == 'hour') ? (3600 - 1) : '';
$title = get_calendar_title_date($start_date, $period);

// move events to topic_rowset format
$topic_rowset = array();
for ($i = 0; $i < count($events); $i++)
{
	$row['topic_id']									= $events[$i]['event_id'];
	$row['topic_title']								= $events[$i]['event_title'];
	$row['topic_replies']							= $events[$i]['event_replies'];
	$row['topic_type']								= $events[$i]['event_type'];
	$row['topic_vote']								= $events[$i]['event_vote'];
	$row['topic_status']							= $events[$i]['event_status'];
	$row['topic_moved_id']						= $events[$i]['event_moved_id'];
	$row['post_time']									= $events[$i]['event_last_time'];
	$row['user_id']										= $events[$i]['event_author_id'];
	$row['username']									= $events[$i]['event_author'];
	$row['post_username']							= $events[$i]['event_author'];
	$row['topic_time']								= $events[$i]['event_time'];
	$row['id2']												= $events[$i]['event_last_author_id'];
	$row['post_username2']						= $events[$i]['event_last_author'];
	$row['user2']											= $events[$i]['event_last_author'];
	$row['topic_last_post_id']				= $events[$i]['event_last_id'];
	$row['topic_views']								= $events[$i]['event_views'];
	$row['forum_id']									= $events[$i]['event_forum_id'];
	$row['forum_name']								= $events[$i]['event_forum_name'];
	$row['topic_calendar_time']				= $events[$i]['event_calendar_time'];
	$row['topic_calendar_duration']		= $events[$i]['event_calendar_duration'];
	$row['topic_icon']								= $events[$i]['event_icon'];

	$topic_rowset[] = $row;
}

$split_type = false;
$display_nav_tree = (intval($board_config['calendar_forum']) == 1);
$footer = $s_forum_list . '&nbsp;<input type="submit" value="' . $lang['Go'] . '" class="liteoption" />';
topic_list('TOPIC_LIST_SCHEDULER', 'topics_list_box', $topic_rowset, $title, $split_type, $display_nav_tree, $footer);

// system
$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
$s_hidden_fields .= '<input type="hidden" name="date" value="' . $date . '" />';
$s_hidden_fields .= '<input type="hidden" name="start" value="' . $start . '" />';

$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

$total = $topics_count;
if ($total == 0)
{
	$total++;
}

$template->assign_vars(array(
	'PAGINATION' => generate_pagination('calendar_scheduler.' . PHP_EXT . '?d=' . $date . '&amp;mode=' . $mode, $total, $board_config['topics_per_page'], $start),
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor( $start / $board_config['topics_per_page']) + 1 ), ceil($topics_count / $board_config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page'],

	'NAV_SEPARATOR' => $nav_separator,
	'S_ACTION' => append_sid('calendar_scheduler.' . PHP_EXT),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	)
);

// send to browser
$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>