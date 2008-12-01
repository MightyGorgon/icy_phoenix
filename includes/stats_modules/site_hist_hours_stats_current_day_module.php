<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$current_time = time();
$minutes = date('is', $current_time);
$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
$dato = date('H', $current_time);
$time_today = $hour_now - (3600 * $dato);
$year = create_date('Y', $current_time, $board_config['board_timezone']);
$time_thismonth = $month [date('n') - 1];
$time_thisweek = $time_today - ((date('w', $time_today) - 1) * 86400);
if ((time() - $time_thisweek) < 0)
{
	$time_thisweek_poster = $time_thisweek - (60 * 60 * 24 * 7);
	$time_today_poster = $time_today - (60 * 60 * 24 * 6);
}
else
{
	$time_thisweek_poster = $time_thisweek;
	$time_today_poster = $time_today;
}
$this_month = create_date('n', $time_thismonth, $board_config['board_timezone']);
$l_this_month = create_date('F', $time_thismonth, $board_config['board_timezone']);
$l_this_day = create_date('D', $time_today, $board_config['board_timezone']);

$template->assign_vars(array(
	'L_MODULE_NAME' => $lang['module_name_site_hist_hours_stats_current_day'],
	'24_BACK' => sprintf($lang['Last_24'], $return_limit),
	'L_TIME' => $lang['Time'],
	'L_USERS_TOTAL' => $lang['Total_users'],
	'L_REG_USERS' => $lang['Reg_users'],
	'L_HIDDEN_USERS' => $lang['Hidden_users'],
	'L_GUESTS_USERS' => $lang['Guests_users'],
	'L_NEW_USERS' => $lang['New_users'],
	'L_NEW_TOPICS' => $lang['New_topics'],
	'L_NEW_POSTS' => $lang['New_posts_reply'],
	)
);

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

// site_history last X hours
$hour_now -=(($return_limit - 1) * 3600);
for ($i = 0; $i < $return_limit; $i++)
{

	if ($i == $return_limit - 1)
	{
		$l_currrent_time = '<strong>' . create_date('H:i', $hour_now, $board_config['board_timezone']) . '</strong>';
	}
	else
	{
		$l_currrent_time = create_date('H:i', $hour_now, $board_config['board_timezone']);
	}

	$sql = "SELECT COUNT(user_regdate) as new_users
		FROM " . USERS_TABLE . "
		WHERE user_regdate >= " . $hour_now . "
			AND user_regdate < " . ($hour_now + 3599);

	if (!($result = $stat_db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	$new_users = $stat_db->sql_fetchrow($result);
	$new_users = $new_users['new_users'];

	$sql = "SELECT MAX(reg + hidden + guests) as total, date, MAX(reg) as reg , MAX(hidden) as hidden, MAX(guests) as guests, SUM(new_topics) as topics, SUM(new_posts) as posts
		FROM " . SITE_HISTORY_TABLE . "
		WHERE date >= " . $hour_now . "
			AND date < " . ($hour_now + 3599) . "
		GROUP BY (date >= " . $hour_now . "
			AND date < " . ($hour_now + 3599) . ")";

	if (!($result = $stat_db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	if ($stat_db->sql_numrows($result))
	{
		$site_today = $stat_db->sql_fetchrow($result);
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('stats_row', array(
			'CLASS' => $class,
			'TIME' => $l_currrent_time,
			'TOTAL' => $site_today['total'],
			'REG' => ($site_today['reg']) ? $site_today['reg'] : $lang['None'],
			'HIDDEN' => ($site_today['hidden']) ? $site_today['hidden'] : $lang['None'],
			'GUESTS' => ($site_today['guests']) ? $site_today['guests'] : $lang['None'],
			'TOPICS' => ($site_today['topics']) ? $site_today['topics'] : $lang['None'],
			'POSTS' => ($site_today['posts']) ? $site_today['posts'] : $lang['None'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	else
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('stats_row', array(
			'CLASS' => $class,
			'TIME' => $l_currrent_time,
			'TOTAL' => $lang['Not_availble'],
			'REG' => $lang['Not_availble'],
			'HIDDEN' => $lang['Not_availble'],
			'GUESTS' => $lang['Not_availble'],
			'TOPICS' => $lang['Not_availble'],
			'POSTS' => $lang['Not_availble'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	$hour_now += 3600;
}

?>