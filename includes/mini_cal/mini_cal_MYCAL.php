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

if (!defined('IN_MINI_CAL'))
{
	die('Hacking attempt');
}


/***************************************************************************
	getMiniCalForumsAuth

	version:		1.0.0
	parameters:	 $userdata - an initialised $userdata array.
	returns:		a two part array
						$mini_cal_auth['view'] - a comma seperated list of forums which the user has VIEW permissions for
						$mini_cal_auth['post'] - a comma seperated list of forums which the user has POST permissions for
 ***************************************************************************/
function getMiniCalForumsAuth($userdata)
{
	global $db;

	// initialise our forums auth list
	$mini_cal_auth_ary = array();
	$mini_cal_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);

	$mini_cal_auth = array();
	$mini_cal_auth['view'] = '';
	$mini_cal_auth['post'] = '';

	while (list($mini_cal_forum_id, $mini_cal_auth_level) = each($mini_cal_auth_ary))
	{
		if ($mini_cal_auth_level[MINI_CAL_EVENT_AUTH_LEVEL])
		{
			$mini_cal_auth['view'] .= ($mini_cal_auth['view'] == '') ? $mini_cal_forum_id : ', ' . $mini_cal_forum_id;
		}

		if (($mini_cal_auth_level['auth_post']))
		{
			$mini_cal_auth['post'] .= ($mini_cal_auth['post'] == '') ? $mini_cal_forum_id : ', ' . $mini_cal_forum_id;
		}
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

	if ($auth_view_forums != '')
	{
		$sql = "SELECT DISTINCT DAYOFMONTH(c.cal_date) as event_day
			FROM " . MYCALENDAR_TABLE . " c, " . FORUMS_TABLE . " f
			WHERE f.forum_id = c.forum_id
				AND f.forum_type = " . FORUM_POST . "
				AND f.forum_id IN ($auth_view_forums)
				AND YEAR(cal_date) = $mini_cal_this_year
				AND MONTH(cal_date) = $mini_cal_this_month";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while($row = $db->sql_fetchrow($result))
			{
				$mini_cal_event_days[] = $row['event_day'];
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
	$mini_cal_auth_sql = ($mini_cal_auth['view'] != '') ? ' AND t.forum_id in (' . $mini_cal_auth['view'] . ') ' : '';
	$days_ahead_sql = (MINI_CAL_DAYS_AHEAD > 0) ? " AND (c.cal_date <= DATE_ADD(CURDATE(), INTERVAL " . MINI_CAL_DAYS_AHEAD . " DAY)) " : '';

	// get the events
	$sql = "SELECT
				c.topic_id, c.cal_date, c.forum_id,
				MONTH(c.cal_date) as cal_month,
				DAYOFWEEK(DATE_SUB(c.cal_date, INTERVAL " . MINI_CAL_FDOW . " DAY)) as cal_weekday,
				DAYOFMONTH(c.cal_date) as cal_monthday,
				YEAR(c.cal_date) as cal_year,
				HOUR(c.cal_date) as cal_hour,
				MINUTE(c.cal_date) as cal_min,
				SECOND(c.cal_date) as cal_sec,
				t.topic_title
			FROM
				" . MYCALENDAR_TABLE . " as c,
				" . TOPICS_TABLE . " as t
			WHERE
				c.topic_id = t.topic_id
				AND (c.cal_date >= CURDATE())
				$days_ahead_sql
				$mini_cal_auth_sql
			ORDER BY
				c.cal_date ASC
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


				$template->assign_block_vars('mini_cal_events', array(
						'MINI_CAL_EVENT_DATE' => $cal_date,
						'S_MINI_CAL_EVENT' => $row['topic_title'],
						'U_MINI_CAL_EVENT' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC ."?" . POST_TOPIC_URL . '=' . $row['topic_id'])
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


/***************************************************************************
	getMiniCalSearchSql

	version:		1.0.0
	parameters:	 $search_id	- the type of search we're looking for
					$search_date - the date passed to the search

	returns:		an sql string
 ***************************************************************************/
function getMiniCalSearchSql($search_id, $search_date)
{
	$sql = "SELECT t.topic_first_post_id as post_id
		FROM " . MYCALENDAR_TABLE . " c, " . TOPICS_TABLE . " t
		WHERE t.topic_id = c.topic_id
			AND DATE_FORMAT(c.cal_date, '%Y%m%d') = $search_date";

	return $sql;
}


/***************************************************************************
	getMiniCalSearchURL

	version:		1.0.0
	parameters:	 $search_date - the date passed to the search

	returns:		an url string
 ***************************************************************************/
function getMiniCalSearchURL($search_date)
{
	$url = append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?search_id=mini_cal_events&amp;d=' . $search_date);
	return $url;
}


/***************************************************************************
	getMiniCalPostForumsList

	version:		1.0.0
	parameters:	 $mini_cal_post_auth  - a comma seperated list of forms with post rights

	returns:		adds a forums select list to the template output
***************************************************************************/
function getMiniCalPostForumsList($mini_cal_post_auth)
{
	$and_post_auth_sql = 'AND f.events_forum = 1';

	getPostForumsList($mini_cal_post_auth, $and_post_auth_sql);
}

$template->assign_vars(array(
	'U_MINI_CAL_CALENDAR' => append_sid(IP_ROOT_PATH . 'mycalendar.' . PHP_EXT),
	'U_MINI_CAL_ADD_EVENT' => append_sid(IP_ROOT_PATH . 'posting.' . PHP_EXT . '?mode=newtopic&f=' . MINI_CAL_EVENTS_FORUM)
	)
);

?>