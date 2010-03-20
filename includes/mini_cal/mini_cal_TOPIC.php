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
	$mini_cal_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

	$mini_cal_auth = array();
	$mini_cal_auth['view'] = '';
	$mini_cal_auth['post'] = '';

	while ( list($mini_cal_forum_id, $mini_cal_auth_level) = each($mini_cal_auth_ary) )
	{
		if ( $mini_cal_auth_level[MINI_CAL_EVENT_AUTH_LEVEL] )
		{
			$mini_cal_auth['view'] .= ($mini_cal_auth['view'] == '') ? $mini_cal_forum_id : ', ' . $mini_cal_forum_id;
		}

		if ( ($mini_cal_auth_level['auth_post']) && $mini_cal_auth_level['auth_cal'] )
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
		// start and end date
		$start_date = gmmktime(0, 0, 0, $mini_cal_this_month, 01, $mini_cal_this_year);
		$w_month = $mini_cal_this_month + 1;
		$w_year = $mini_cal_this_year;
		if ($w_month > 12)
		{
			$w_month = 01;
			$w_year++;
		}
		$end_date = gmmktime(0, 0, 0, $w_month, 01, $w_year);

		// we consider the duration
		$sql = "SELECT DISTINCT topic_calendar_time, topic_calendar_duration
				FROM " . TOPICS_TABLE . "
				WHERE forum_id IN ($auth_view_forums)
					AND (topic_calendar_time + topic_calendar_duration) >= $start_date
					AND topic_calendar_time < $end_date
					AND topic_calendar_time IS NOT NULL
					AND topic_calendar_time <> 0";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			$mini_cal_event_days_ww = array();
			while( $row = $db->sql_fetchrow($result) )
			{
				$start_day = intval(gmdate('d', $row['topic_calendar_time']));
				for ($i = 0; ( ($i <= intval($row['topic_calendar_duration'] / 86400)) && ( ($start_day + $i) <= 31) ); $i++)
				{
					$mini_cal_event_days_ww[ ($start_day + $i) ] = true;
				}
			}
			while (list($mini_cal_event_day, $mini_cal_event_present) = each($mini_cal_event_days_ww) )
			{
				$mini_cal_event_days[] = $mini_cal_event_day;
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
	global $template, $db, $lang, $mini_cal_today, $mini_cal_this_month, $mini_cal_this_year, $mini_cal_this_day;

	// start and end date
	$start_date = gmmktime(0, 0, 0, intval(substr($mini_cal_today, 4, 2)), $mini_cal_this_day, $mini_cal_this_year);

	$w_month = $mini_cal_this_month;
	$days_ahead_sql = '';
	if (MINI_CAL_DAYS_AHEAD > 0)
	{
		$w_year = $mini_cal_this_year;
		if ($w_month > 12)
		{
			$w_month = 01;
			$w_year++;
		}
		$end_date = gmmktime(0, 0, 0, $w_month, $mini_cal_this_day + MINI_CAL_DAYS_AHEAD, $w_year);
		$days_ahead_sql = " AND topic_calendar_time < $end_date ";
	}

	// initialise some sql bits
	$mini_cal_auth_sql = ($mini_cal_auth['view'] != '') ? ' AND t.forum_id in (' . $mini_cal_auth['view'] . ') ' : '';

	// get events
	$sql = "SELECT t.topic_id, t.topic_calendar_time, t.topic_title, t.forum_id, t.topic_calendar_duration
		FROM " . TOPICS_TABLE . " t
		WHERE topic_calendar_time >= $start_date
			$days_ahead_sql
			AND topic_calendar_time IS NOT NULL
			AND topic_calendar_time <> 0
			$mini_cal_auth_sql
		ORDER BY
			t.topic_calendar_time ASC
		LIMIT
			0," . MINI_CAL_LIMIT;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		$template->assign_block_vars('switch_mini_cal_events', array());
		if ( $db->sql_numrows($result) > 0 )
		{
			// we've got some events

			// now let's output our events in the given date format for the current language
			while ($row = $db->sql_fetchrow($result))
			{
				$cal_time = $row['topic_calendar_time'];
				$day_span = (gmdate('Ymd', $cal_time) < gmdate('Ymd', $cal_time+$row['topic_calendar_duration']));
				$include_time = gmdate('His', $cal_time) > 0;
				$cal_date = getFormattedDate(
								gmdate('w', $cal_time),
								gmdate('n', $cal_time),
								gmdate('d', $cal_time),
								gmdate('Y', $cal_time),
								gmdate('H', $cal_time),
								gmdate('i', $cal_time),
								gmdate('s', $cal_time),
								$lang['Mini_Cal_date_format'].((!$day_span && $include_time)?' '.$lang['Mini_Cal_date_format_Time']:'')
							);

				if ($day_span || $row['topic_calendar_duration'] > 0)
				{
					$cal_time = $cal_time + $row['topic_calendar_duration'];
					$cal_date .= ' - ' . getFormattedDate(
											gmdate('w', $cal_time),
											gmdate('n', $cal_time),
											gmdate('d', $cal_time),
											gmdate('Y', $cal_time),
											gmdate('H', $cal_time),
											gmdate('i', $cal_time),
											gmdate('s', $cal_time),
											((!$day_span)?$lang['Mini_Cal_date_format_Time']:$lang['Mini_Cal_date_format'])
										);
				}

				$template->assign_block_vars('mini_cal_events', array(
						'MINI_CAL_EVENT_DATE' => $cal_date,
						'S_MINI_CAL_EVENT' => $row['topic_title'],
						//'S_MINI_CAL_EVENT' => $mini_cal_auth_sql,
						'U_MINI_CAL_EVENT' => append_sid( IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC ."?" . POST_TOPIC_URL . '=' . $row['topic_id'] )
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
function getMiniCalSearchSql($search_date)
{
	$s_yy = intval(substr($search_date, 0, 4));
	$s_mm = intval(substr($search_date, 4, 2));
	$s_dd = intval(substr($search_date, 6, 2));
	$search_date = gmmktime(0, 0 ,0, $s_mm, $s_dd, $s_yy);
	$nix_tomorrow = gmmktime (0, 0, 0, $s_mm, $s_dd + 1, $s_yy);

	$sql = "SELECT topic_first_post_id as post_id
		FROM " . TOPICS_TABLE . "
		WHERE (topic_calendar_time + topic_calendar_duration) >= $search_date AND topic_calendar_time < $nix_tomorrow";

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
	$s_yy = intval(substr($search_date, 0, 4));
	$s_mm = intval(substr($search_date, 4, 2));
	$s_dd = intval(substr($search_date, 6, 2));
	$search_date = mktime(0,0,0, $s_mm, $s_dd, $s_yy);
	//$url = append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?search_id=mini_cal_events&amp;d=' . $search_date);
	$url = append_sid(IP_ROOT_PATH . 'calendar_scheduler.' . PHP_EXT . '?d=' . $search_date);
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
	getPostForumsList($mini_cal_post_auth);
}


$template->assign_vars(array(
	'U_MINI_CAL_CALENDAR' => append_sid(IP_ROOT_PATH . 'calendar.' . PHP_EXT),
	'U_MINI_CAL_ADD_EVENT' => append_sid(IP_ROOT_PATH . 'posting.' . PHP_EXT . '?mode=newtopic&f=' . MINI_CAL_EVENTS_FORUM )
	)
);


?>