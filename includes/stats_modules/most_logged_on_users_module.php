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

include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);

// Top Posting Users
$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TIME2'=> $lang['Time2'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_USERNAME' => $lang['Username'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_most_logged_on_users']
	)
);

$sql = "SELECT SUM(user_totaltime) as total_time
	FROM " . USERS_TABLE . "
	WHERE user_id <> " . ANONYMOUS;
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrow($result);
$total_time = $row['total_time'];

$sql = 'SELECT user_id, username, user_active, user_color, user_totaltime
	FROM ' . USERS_TABLE . '
	WHERE (user_id <> ' . ANONYMOUS . ') AND (user_totaltime > 0)
	ORDER BY user_totaltime DESC
	LIMIT ' . $return_limit;
$result = $stat_db->sql_query($sql);
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);

$firstcount = $user_data[0]['user_totaltime'];

$template->_tpldata['stats_row.'] = array();

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$statistics->do_math($firstcount, $user_data[$i]['user_totaltime'], $total_time);

	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => colorize_username($user_data[$i]['user_id'], $user_data[$i]['username'], $user_data[$i]['user_color'], $user_data[$i]['user_active']),
		'PERCENTAGE' => $statistics->percentage,
		'BAR' => $statistics->bar_percent,
		'TIME' => make_hours($user_data[$i]['user_totaltime'])
		)
	);
}

?>