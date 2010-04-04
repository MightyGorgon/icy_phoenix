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

// Top Posting Users This Week (Site History)
$current_time = time();
$minutes = gmdate('is', $current_time);
$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
$dato = gmdate('H', $current_time);
$time_today = $hour_now - (3600 * $dato);
$year = create_date('Y', $current_time, 0);
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
	'L_MODULE_NAME' => $lang['module_name_site_hist_week_top_posters'],
	'WEEK' => sprintf($lang['Week_Var'], (create_date('D', $time_thisweek, $config['board_timezone'])) . ' - ' . $l_this_day),
	'L_RANK' => $lang['Rank'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_GRAPH' => $lang['Graph'],
	'L_USERNAME' => $lang['Username'],
	'L_POSTS' => $lang['Posts']
	)
);

$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, count(u.user_id) as user_posts
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
	WHERE (u.user_id = p.poster_id)
		AND (p.post_time > '" . $time_thisweek_poster . "')
		AND (u.user_id <> " . ANONYMOUS . ")
	GROUP BY u.user_id
	ORDER BY user_posts DESC
	LIMIT " . $return_limit;
$result = $stat_db->sql_query($sql);
$total_posts_thisweek = 0;
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);
$firstcount = $user_data[0]['user_posts'];

for ($i = 0; $i < $user_count; $i++)
{
	$total_posts_thisweek += $user_data[$i]['user_posts'];
}

$template->_tpldata['stats_row.'] = array();

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$statistics->do_math($firstcount, $user_data[$i]['user_posts'], $total_posts_thisweek);

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