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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('IN_MINI_CAL'))
{
	die('Hacking attempt');
}

include_once(IP_ROOT_PATH . 'cal_settings.' . PHP_EXT);

/***************************************************************************
	getMiniCalForumsAuth

	version:		1.0.0
	parameters:	 $user_data - an initialised $user_data array.
	returns:		a two part array
						$mini_cal_auth['view'] - a comma seperated list of forums which the user has VIEW permissions for
						$mini_cal_auth['post'] - a comma seperated list of forums which the user has POST permissions for
 ***************************************************************************/
function getMiniCalForumsAuth($user_data)
{
	global $db;

	// initialise our forums auth list
	$mini_cal_auth_ary = array();
	$mini_cal_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $user_data);

	$mini_cal_auth = array();
	$mini_cal_auth['view'] = '';
	$mini_cal_auth['post'] = '';

	$sql = "SELECT * FROM " . CAL_CONFIG;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		while($row = $db->sql_fetchrow($result))
		{
			$cal_config[$row['config_name']] = $row['config_value'];
		}

		$mini_cal_auth['view'] = (($user_data['user_level'] == ADMIN) || $cal_config['allow_anon']);

		$caluser = calendarperm($user_data['user_id']);
		if(($cal_config['allow_user_default'] > $caluser) && ($user_data['user_id'] != ANONYMOUS))
		{
			$caluser = $cal_config['allow_user_default'];
		}
		$mini_cal_auth['post'] = $caluser;
	}
	return $mini_cal_auth;
}


/***************************************************************************
	getMiniCalEventDays

	version:		1.0.0
	parameters:	 $auth_view_forums - a comma seperated list of forums which the user has VIEW permissions for
	returns:		an array containing a list of day containing event the user has permission to view
 ***************************************************************************/
function getMiniCalEventDays($auth_view_forums)
{
	global $db, $mini_cal_this_year, $mini_cal_this_month;

	$mini_cal_event_days = array();

	if ($auth_view_forums)
	{
		$sql = "SELECT DISTINCT DAYOFMONTH(c.stamp) as event_day, DAYOFMONTH(c.eventspan) as event_day_end
			FROM " . CAL_TABLE . " c
			WHERE YEAR(c.stamp) = $mini_cal_this_year
				AND MONTH(c.stamp) = $mini_cal_this_month";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while($row = $db->sql_fetchrow($result))
			{
				for ($i=$row['event_day']; $i<=$row['event_day_end']; $i++)
				{
					$mini_cal_event_days[] = $i;
				}
			}
		}
	}

	return $mini_cal_event_days;
}


/***************************************************************************
	getMiniCalEvents

	version:		1.0.0
	parameters:	 $mini_cal_auth - a two part array
						$mini_cal_auth['view'] - a comma seperated list of forums which the user has VIEW permissions for
						$mini_cal_auth['post'] - a comma seperated list of forums which the user has POST permissions for

	returns:		nothing - it assigns variable to the template
 ***************************************************************************/
function getMiniCalEvents($mini_cal_auth)
{
	global $template, $db, $lang;

	// initialise some sql bits
	if ($mini_cal_auth['view'])
	{
		$days_ahead_sql = (MINI_CAL_DAYS_AHEAD > 0) ? " AND (c.stamp <= DATE_ADD(CURDATE(), INTERVAL " . MINI_CAL_DAYS_AHEAD . " DAY)) " : '';

		// get the events
		$sql = "SELECT
					c.id, c.stamp, c.eventspan, c.subject,
					MONTH(c.stamp) as cal_month,
					DAYOFWEEK(DATE_SUB(c.stamp, INTERVAL " . MINI_CAL_FDOW . " DAY)) as cal_weekday,
					DAYOFMONTH(c.stamp) as cal_monthday,
					YEAR(c.stamp) as cal_year,
					HOUR(c.stamp) as cal_hour,
					MINUTE(c.stamp) as cal_min,
					SECOND(c.stamp) as cal_sec,

					MONTH(c.eventspan) as cal_month_end,
					DAYOFWEEK(DATE_SUB(c.eventspan, INTERVAL " . MINI_CAL_FDOW . " DAY)) as cal_weekday_end,
					DAYOFMONTH(c.eventspan) as cal_monthday_end,
					YEAR(c.eventspan) as cal_year_end
				FROM
					" . CAL_TABLE . " as c
				WHERE ((c.stamp >= CURDATE()) OR (CURDATE() <= c.eventspan))
					$days_ahead_sql
					$mini_cal_auth_sql
				ORDER BY
					c.stamp ASC
				LIMIT
					0," . MINI_CAL_LIMIT;

		// did we get a result?
		// if not then the user does not have MyCalendar installed
		// so just die quielty don't bother to output an error message
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			// ok we've got MyCalendar
			$template->assign_block_vars('switch_mini_cal_events', array());
			if ($db->sql_numrows($result) > 0)
			{
				// we've even got some events
				// initialise out date formatting patterns
				$cal_date_pattern = unserialize(MINI_CAL_DATE_PATTERNS);

				// output our events in the given date format for the current language
					while ($row = $db->sql_fetchrow($result))
				{
					$cal_date = getFormattedDate(
									$row['cal_weekday'],
									$row['cal_month'],
									$row['cal_monthday'],
									$row['cal_year'],
									$row['cal_hour'],
									$row['cal_min'],
									$row['cal_sec'],
									$lang['Mini_Cal_date_format']
								);

					if ($row['eventspan'] > $row['stamp'])
					{
						$cal_date .= ' - ' . getFormattedDate(
										$row['cal_weekday_end'],
										$row['cal_month_end'],
										$row['cal_monthday_end'],
										$row['cal_year_end'],
										'',
										'',
										'',
										$lang['Mini_Cal_date_format']
									);
					}


					$template->assign_block_vars('mini_cal_events', array(
							'MINI_CAL_EVENT_DATE' => $cal_date,
							'S_MINI_CAL_EVENT' => $row['subject'],
							'U_MINI_CAL_EVENT' => append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT . '?id=' . $row['id'] . '&mode=display')
							)
					);
				}
			}
			else
			{
				// no events :(
				$template->assign_block_vars('mini_cal_no_events', array());
			}
			$db->sql_freeresult($result);
		}
	}
}


/***************************************************************************
	getMiniCalSearchSql

	version:		1.0.0
	parameters:	 $search_id	- the type of search we're looking for
					$search_date - the date passed to the search

	returns:		an sql string
 ***************************************************************************/
function getMiniCalSearchSql($search_id, $search_date)
{
	// not used
}


/***************************************************************************
	getMiniCalSearchURL

	version:		1.0.0
	parameters:	 $search_date - the date passed to the search

	returns:		an url string
 ***************************************************************************/
function getMiniCalSearchURL($search_date)
{
	$s_yy = substr($search_date, 0, 4);
	$s_mm = substr($search_date, 4, 2);
	$s_dd = substr($search_date, 6, 2);
	$url = append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT . '?day=' . $s_dd . '&amp;month=' . $s_mm . '&amp;year=' . $s_yy . '&amp;mode=display');
	return $url;
}


// borrow from calendar.php
function calendarperm($user_id)
{
	global $db, $cal_config;
	// Get the user permissions first.
	$sql = "SELECT user_calendar_perm FROM " . USERS_TABLE . " WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	// Get the group permissions second.
	$sql = "SELECT group_calendar_perm FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g
		WHERE ug.user_id = " . $user_id . " AND g.group_id = ug.group_id";
	$result = $db->sql_query($sql);

	$topgroup = 0;
	while($rowg = $db->sql_fetchrow($result2))
	{
		if($topgroup < $rowg['group_calendar_perm'])
		{
			$topgroup = $rowg['group_calendar_perm'];
		}
	}
	$db->sql_freeresult($result);

	// Use whichever value is highest.
	$cal_perm = ($topgroup > $row['user_calendar_perm']) ? $topgroup : $row['user_calendar_perm'];

	return $cal_perm;
}

/***************************************************************************
	getMiniCalPostForumsList

	version:		1.0.0
	parameters:	 $mini_cal_post_auth  - a comma seperated list of forms with post rights

	returns:		adds a forums select list to the template output
***************************************************************************/
function getMiniCalPostForumsList($mini_cal_post_auth)
{
	if ($mini_cal_post_auth >= 2)
	{
		global $template;

		$template->assign_block_vars('switch_mini_cal_add_events', array());
	}

}

$template->assign_vars(array(
	'U_MINI_CAL_CALENDAR' => append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT),
	'U_MINI_CAL_ADD_EVENT' => append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT . '?action=Cal_add_event')
	)
);

?>