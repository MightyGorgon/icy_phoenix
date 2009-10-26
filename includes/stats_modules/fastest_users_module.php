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

// Top Posting Users
$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TIME' => $lang['time_on_forum'],
	'L_POST_PER_DAY' => $lang['posts_day'],
	'L_USERNAME' => $lang['Username'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_fastest_users']
	)
);

$percentage = 0;
$bar_percent = 0;

$currect_time = time();

$sql ="SELECT user_id, username, user_active, user_color, user_posts, user_regdate,
	(user_posts / (($currect_time - user_regdate) / 86400)) rate,
	ROUND(($currect_time - user_regdate) / 86400) time_on_forum
	FROM " . USERS_TABLE . "
	WHERE (user_id <> " .ANONYMOUS . ")
		AND (user_posts > 0)
	ORDER BY rate DESC
	LIMIT " . $return_limit;
$result = $stat_db->sql_query($sql);
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);

$firstrate = $user_data[0]['rate'];
$total = $firstrate;

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$value = $user_data[$i]['rate'];

	$cst = ($firstrate > 0) ? 90 / $firstrate : 90;

	$bar_percent = round($value * $cst);

	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => colorize_username($user_data[$i]['user_id'], $user_data[$i]['username'], $user_data[$i]['user_color'], $user_data[$i]['user_active']),
		'BAR' => $bar_percent,
		'RATE' => round($user_data[$i]['rate'], 2),
		'TIME' => $user_data[$i]['time_on_forum']
		)
	);
}

?>