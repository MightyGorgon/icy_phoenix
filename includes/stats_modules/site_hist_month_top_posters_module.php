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

// Top Posting Users This Month (Site History)
$statistics->init_bars();

$current_time = time();
$minutes = date('is', $current_time);
$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
$dato = date('H', $current_time);
$time_today = $hour_now - (3600 * $dato);
$year = create_date('Y', $current_time, $board_config['board_timezone']);
$month = array();
$month [0] = mktime (0, 0, 0, 1, 1, $year) - ($board_config['board_timezone'] * 3600) + 3600;
$month [1] = $month [0] + 2678400;
$month [2] = mktime (0, 0, 0, 3, 1, $year) - ($board_config['board_timezone'] * 3600) + 3600;
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
$time_thismonth = $month[date('n') - 1];
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
	'L_MODULE_NAME' => $lang['module_name_site_hist_month_top_posters'],
	'MONTH' => sprintf($lang['Month_Var'], ($l_this_month . ' ' . create_date('Y', $time_thismonth, $board_config['board_timezone']))),
	'L_RANK' => $lang['Rank'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_GRAPH' => $lang['Graph'],
	'L_USERNAME' => $lang['Username'],
	'L_POSTS' => $lang['Posts']
	)
);

// This months top postes
$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, count(u.user_id) as user_posts
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
	WHERE (u.user_id = p.poster_id)
		AND (p.post_time > '" . $time_thismonth . "')
		AND (u.user_id <> " . ANONYMOUS . ")
	GROUP BY u.user_id
	ORDER BY user_posts DESC
	LIMIT " . $return_limit;

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve topposters data', '', __LINE__, __FILE__, $sql);
}

$total_posts_thismonth = 0;
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);
$firstcount = $user_data[0]['user_posts'];

for ($i = 0; $i < $user_count; $i++)
{
	$total_posts_thismonth += $user_data[$i]['user_posts'];
}

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
	$statistics->do_math($firstcount, $user_data[$i]['user_posts'], $total_posts_thismonth);
	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => colorize_username($user_data[$i]['user_id'], $user_data[$i]['username'], $user_data[$i]['user_color'], $user_data[$i]['user_active']),
		'PERCENTAGE' => $statistics->percentage,
		'BAR' => $statistics->bar_percent,
		'POSTS' => $user_data[$i]['user_posts']
		)
	);
}

?>