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
$minutes = gmdate('is', $current_time);
$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
$dato = gmdate('H', $current_time);
$time_today = $hour_now - (3600 * $dato);
$year = create_date('Y', $current_time, $config['board_timezone']);
$time_thismonth = $month [gmdate('n') - 1];
$time_thisweek = $time_today - ((gmdate('w', $time_today) - 1) * 86400);
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
$this_month = create_date('n', $time_thismonth, $config['board_timezone']);
$l_this_month = create_date('F', $time_thismonth, $config['board_timezone']);
$l_this_day = create_date('D', $time_today, $config['board_timezone']);

$template->assign_vars(array(
	'L_MODULE_NAME' => $lang['module_name_site_hist_daily_stats_current_week'],
	'WEEK_BACK' => $lang['Most_online_week'],
	'L_DAY' => $lang['Week_day'],
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

// last 7 days site_history
$time_today -= 518400;
for ($i = 0; $i < 7; $i++)
{
	if ($i == 6)
	{
		$l_currrent_day = '<strong>' . create_date('D', $time_today, $config['board_timezone']) . '</strong>';
	}
	else
	{
		$l_currrent_day = create_date('D', $time_today, $config['board_timezone']);
	}

	$sql = "SELECT COUNT(user_regdate) as new_users
		FROM " . USERS_TABLE . "
		WHERE user_regdate >= " . $time_today . "
			AND user_regdate < " . ($time_today + 86400);
	$result = $stat_db->sql_query($sql);
	$new_users = $stat_db->sql_fetchrow($result);
	$new_users = $new_users['new_users'];

	$sql = "SELECT MAX(reg + hidden + guests) as total, date, MAX(reg) as reg , MAX(hidden) as hidden, MAX(guests) as guests, SUM(new_topics) as topics, SUM(new_posts) as posts
		FROM " . SITE_HISTORY_TABLE . "
		WHERE date >= " . $time_today . "
			AND date < " . ($time_today + 86400) . "
		GROUP BY (date >= " . $time_today . "
			AND date < " . ($time_today + 86400) . ")";
	$result = $stat_db->sql_query($sql);

	if ($stat_db->sql_numrows($result))
	{
		$site_week = $stat_db->sql_fetchrow($result);
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('stats_row', array(
			'CLASS' => $class,
			'DAY' => $l_currrent_day,
			'TOTAL' => $site_week['total'],
			'REG' => ($site_week['reg'])? $site_week['reg'] : $lang['None'],
			'HIDDEN' => ($site_week['hidden']) ? $site_week['hidden'] : $lang['None'],
			'GUESTS' => ($site_week['guests']) ? $site_week['guests'] : $lang['None'],
			'TOPICS' => ($site_week['topics']) ? $site_week['topics'] : $lang['None'],
			'POSTS' => ($site_week['posts']) ? $site_week['posts'] : $lang['None'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	else
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('stats_row', array(
			'CLASS' => $class,
			'DAY' => $l_currrent_day,
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
	$time_today += 86400;
}

?>