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
$year = create_date('Y', $current_time, 0);
$month = array();
$month [0] = mktime (0, 0, 0, 1, 1, $year) - ($config['board_timezone'] * 3600) + 3600;
$month [1] = $month [0] + 2678400;
$month [2] = mktime (0, 0, 0, 3, 1, $year) - ($config['board_timezone'] * 3600) + 3600;
$month [3] = $month [2] + 2678400;
$month [4] = $month [3] + 2592000;
$month [5] = $month [4] + 2678400;
$month [6] = $month [5] + 2592000;
$month [7] = $month [6] + 2678400;
$month [8] = $month [7] + 2678400;
$month [9] = $month [8] + 2592000;
$month [10] = $month [9] + 2678400;
$month [11] = $month [10] + 2592000;
$month [12] = $month [11] + 2678400;
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
	'L_MODULE_NAME' => $lang['module_name_site_hist_monthly_stats_current_year'],
	'MONTH_BACK' => sprintf($lang['Most_online'], create_date('Y', $time_thismonth, $config['board_timezone'])),
	'L_MONTH' => $lang['Month'],
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

// months site_history
for ($i = 0; $i < 12; $i++)
{
	$sql = "SELECT COUNT(user_regdate) as new_users
		FROM " . USERS_TABLE . "
		WHERE user_regdate >= " . $month[$i] . "
			AND user_regdate < " . $month[$i + 1];
	$result = $stat_db->sql_query($sql);
	$new_users = $stat_db->sql_fetchrow($result);
	$new_users = $new_users['new_users'];

	$sql = "SELECT MAX(reg + hidden + guests) as total, date, MAX(reg) as reg , MAX(hidden) as hidden, MAX(guests) as guests, SUM(new_topics) as topics, SUM(new_posts) as posts
		FROM " . SITE_HISTORY_TABLE . "
		WHERE date>= " . $month[$i] . "
			AND date < " . $month[$i + 1] . "
		GROUP BY (date >= " . $month[$i] . "
			AND date < " . $month[$i+1] . ")";
	$result = $stat_db->sql_query($sql);

	if ($stat_db->sql_numrows($result))
	{
		$site_monthly = $stat_db->sql_fetchrow($result);
		if ($i + 1 == $this_month)
		{
			$l_month = '<strong>' . $l_this_month . '</strong>';
		}
		else
		{
			$l_month = create_date('F', $month[$i], $config['board_timezone']);
		}

		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('stats_row', array(
			'CLASS' => $class,
			'MONTH' => $l_month,
			'TOTAL' => $site_monthly['total'],
			'REG' => ($site_monthly['reg'])? $site_monthly['reg'] : $lang['None'],
			'HIDDEN' => ($site_monthly['hidden']) ? $site_monthly['hidden'] : $lang['None'],
			'GUESTS' => ($site_monthly['guests']) ? $site_monthly['guests'] : $lang['None'],
			'TOPICS' => ($site_monthly['topics']) ? $site_monthly['topics'] : $lang['None'],
			'POSTS' => ($site_monthly['posts']) ? $site_monthly['posts'] : $lang['None'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	else
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('stats_row', array(
			'CLASS' => $class,
			'MONTH' => create_date('F', $month[$i], $config['board_timezone']),
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
}

?>