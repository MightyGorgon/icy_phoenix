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
* netclectic - Adrian Cockburn - phpbb@netclectic.com
*
*/

// CTracker_Ignore: File checked by human

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

define('IN_MINI_CAL', 1);

include_once(IP_ROOT_PATH . 'includes/mini_cal/mini_cal_config.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/mini_cal/mini_cal_common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/mini_cal/calendarSuite.' . PHP_EXT);

// get the mode (if any)
if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mini_cal_mode = isset($_POST['mode']) ? $_POST['mode'] : $_GET['mode'];
}
$mini_cal_mode = ($mini_cal_mode == 'personal') ? $mini_cal_mode : 'default';

// get the user (for personal calendar)
if(isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL]))
{
	$mini_cal_user = (isset($_POST[POST_USERS_URL])) ? intval($_POST[POST_USERS_URL]) : intval($_GET[POST_USERS_URL]);
}

// get the calendar month
$mini_cal_month = request_var('month', 0);

// initialise our calendarsuite class
$mini_cal = new calendarSuite();

// initialise the mini_cal lang files
// for maximum efficiency you might want to move the mini_cal lang variables into lang_main
// and remove these lines
$use_lang = (!@file_exists(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_main_mini_cal.' . PHP_EXT)) ? 'english' : $config['default_lang'];
include(IP_ROOT_PATH . 'language/lang_' . $use_lang . '/lang_main_mini_cal.' . PHP_EXT);

// setup our mini_cal template
$template->set_filenames(array('mini_cal_body' => 'mini_cal_body.tpl'));

// initialise some variables
$mini_cal_today = create_date('Ymd', time(), $config['board_timezone']);
$s_cal_month = ($mini_cal_month != 0) ? $mini_cal_month . ' month' : $mini_cal_today;
$mini_cal->getMonth($s_cal_month);
$mini_cal_count = MINI_CAL_FDOW;
$mini_cal_this_year = $mini_cal->dateYYYY;
$mini_cal_this_month = $mini_cal->dateMM;
$mini_cal_this_day = $mini_cal->dateDD;
$mini_cal_month_days = $mini_cal->daysMonth;

if (MINI_CAL_CALENDAR_VERSION != 'NONE')
{
	// include the required events calendar support
	$mini_cal_inc = 'mini_cal_' . MINI_CAL_CALENDAR_VERSION;
	include_once(IP_ROOT_PATH . 'includes/mini_cal/' . $mini_cal_inc . '.' . PHP_EXT);

	// include the required events calendar support
	$mini_cal_auth = getMiniCalForumsAuth($userdata);
	$mini_cal_event_days = getMiniCalEventDays($mini_cal_auth['view']);
	getMiniCalEvents($mini_cal_auth);
	getMiniCalPostForumsList($mini_cal_auth['post']);
}

// output the days for the current month
// if MINI_CAL_DATE_SEARCH = POSTS then hyperlink any days which have already past
// if MINI_CAL_DATE_SEARCH = EVENTS then hyperlink any which have events
for($i = 0; $i < $mini_cal_month_days;)
{
	// is this the first day of the week?
	if($mini_cal_count == MINI_CAL_FDOW)
	{
		$template->assign_block_vars('mini_cal_row', array());
	}

	// is this a valid weekday?
	if($mini_cal_count == ($mini_cal->day[$i][7]))
	{
		$mini_cal_this_day = $mini_cal->day[$i][0];

		$d_mini_cal_today = $mini_cal_this_year . (($mini_cal_this_month <= 9) ? '0' . $mini_cal_this_month : $mini_cal_this_month) . (($mini_cal_this_day <= 9) ? '0' . $mini_cal_this_day : $mini_cal_this_day);
		$mini_cal_day = ($mini_cal_today == $d_mini_cal_today) ? '<span class="' . MINI_CAL_TODAY_CLASS . '">' . $mini_cal_this_day . '</span>' : $mini_cal_this_day;

		if ((MINI_CAL_CALENDAR_VERSION != 'NONE') && (MINI_CAL_DATE_SEARCH == 'EVENTS'))
		{
			$mini_cal_day_link = '<a href="' . getMiniCalSearchURL($d_mini_cal_today) . '" class="' . MINI_CAL_DAY_LINK_CLASS . '">' . ($mini_cal_day) . '</a>';
			$mini_cal_day = (in_array($mini_cal_this_day, $mini_cal_event_days)) ? $mini_cal_day_link : $mini_cal_day;
		}
		else
		{
			$nix_mini_cal_today = gmmktime($config['board_timezone'], 0, 0, $mini_cal_this_month, $mini_cal_this_day, $mini_cal_this_year);
			$mini_cal_day_link = '<a href="' . append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?search_id=mini_cal&amp;d=' . $nix_mini_cal_today) . '" class="' . MINI_CAL_DAY_LINK_CLASS . '">' . ($mini_cal_day) . '</a>';
			$mini_cal_day = ($mini_cal_today >= $d_mini_cal_today) ? $mini_cal_day_link : $mini_cal_day;
		}

		$template->assign_block_vars('mini_cal_row.mini_cal_days', array(
			'MINI_CAL_DAY' => $mini_cal_day
			)
		);
		$i++;
	}
	// no day
	else
	{
		$template->assign_block_vars('mini_cal_row.mini_cal_days', array(
			'MINI_CAL_DAY' => '&nbsp;')
		);
	}

	// is this the last day of the week?
	if ($mini_cal_count == 6)
	{
		// if so then reset the count
		$mini_cal_count = 0;
	}
	else
	{
		// otherwise increment the count
		$mini_cal_count++;
	}
}

// output our general calendar bits
$prev_qs = setQueryStringVal('month', (int) $mini_cal_month - 1);
$next_qs = setQueryStringVal('month', (int) $mini_cal_month + 1);
$index_file = htmlspecialchars(urldecode($_SERVER['SCRIPT_NAME']));
$prev_month = '<a href="' . append_sid($index_file . $prev_qs) . '" class="gen"><b>&laquo;</b></a>';
$next_month = '<a href="' . append_sid($index_file . $next_qs) . '" class="gen"><b>&raquo;</b></a>';
$template->assign_vars(array(
	'L_MINI_CAL_MONTH' => $lang['mini_cal']['long_month'][$mini_cal->day[0][4]] . ' ' . $mini_cal->day[0][5],
	'L_MINI_CAL_ADD_EVENT' => $lang['Mini_Cal_add_event'],
	'L_MINI_CAL_CALENDAR' => $lang['Mini_Cal_calendar'],
	'L_MINI_CAL_EVENTS' => $lang['Mini_Cal_events'],
	'L_MINI_CAL_NO_EVENTS' => $lang['Mini_Cal_no_events'],
	'L_MINI_CAL_MON' => $lang['mini_cal']['day'][1],
	'L_MINI_CAL_TUE' => $lang['mini_cal']['day'][2],
	'L_MINI_CAL_WED' => $lang['mini_cal']['day'][3],
	'L_MINI_CAL_THU' => $lang['mini_cal']['day'][4],
	'L_MINI_CAL_FRI' => $lang['mini_cal']['day'][5],
	'L_MINI_CAL_SAT' => $lang['mini_cal']['day'][6],
	'L_MINI_CAL_SUN' => $lang['mini_cal']['day'][7],
	'U_PREV_MONTH' => $prev_month,
	'U_NEXT_MONTH' => $next_month,
	'L_WHOSBIRTHDAY_WEEK' => ($config['birthday_check_day'] >= 1) ? sprintf((($birthdays_list['xdays']) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $config['birthday_check_day']).$birthdays_list['xdays'] : '',
	'L_WHOSBIRTHDAY_TODAY' => ($birthdays_list['today']) ? $lang['Birthday_today'] . $birthdays_list['today'] : $lang['Nobirthday_today'],
	)
);

$template->assign_var_from_handle('MINI_CAL_OUTPUT', 'mini_cal_body');
?>