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

// setup
$days = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');

$traffic = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);

// start template
$template->assign_vars(array(
	'L_TITLE' => $lang['module_name_registrations_by_day_of_week'],
	'L_REG' => $lang['New_users'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_DOW' => $lang['Dow'],
	'L_GRAPH' => $lang['Graph']
	)
);

// define graph bars
$statistics->init_bars();

// get total regs
$sql = "SELECT COUNT(user_id) as total_reg FROM " . USERS_TABLE;

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Unable to retrieve users data', '', __LINE__, __FILE__, $sql);
}

$row = $stat_db->sql_fetchrow($result);
$total_reg = $row['total_reg'];

// return statistics
$sql = 'SELECT DAYOFWEEK(FROM_UNIXTIME(user_regdate)) as dow, COUNT(*) AS ct
	FROM ' . USERS_TABLE . '
	WHERE user_id <> -1
	GROUP BY DAYOFWEEK(FROM_UNIXTIME(user_regdate))
	ORDER BY DAYOFWEEK(FROM_UNIXTIME(user_regdate)) ASC';

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Unable to retrieve users data', '', __LINE__, __FILE__, $sql);
}

$reg_data = $stat_db->sql_fetchrowset($result);

// get highest new users' count
$max_reg_ct = '0';

for ($i = 0; $i < 7; $i++)
{
	$max_reg_ct = max($max_reg_ct, $reg_data[$i]['ct']);
}

$template->_tpldata['traffic.'] = array();
//reset($template->_tpldata['traffic.']);

// build rows
for ($i = 1; $i < 8; $i++)
{
	$class = (!($i+1 % 2)) ? $theme['td_class2'] : $theme['td_class1'];

	if ($reg_data[$i - 1]['ct'])
	{
		$traffic[$i] = $reg_data[$i - 1]['ct'];
	}

	$statistics->do_math($max_reg_ct, $reg_data[$i - 1]['ct'], $total_reg);

	$template->assign_block_vars('traffic', array(
		'CLASS' => $class,
		'DOW' => $lang['datetime'][$days[$i]],
		'REG' => $traffic[$i],
		'PERCENTAGE' => $statistics->percentage,
		'BAR' => $statistics->bar_percent)
	);
}

?>