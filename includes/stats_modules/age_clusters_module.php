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

// USE $db NOT $stat_db for this module... don't know why...

$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TITLE' => $lang['module_name_age_clusters'],
	'L_GRAPH' => $lang['Graph'],
	'L_HOWMANY' => $lang['How_many']
	)
);

$sql = "SELECT COUNT(u.user_birthday_y) as birthday_counter
	FROM " . USERS_TABLE . " u
	WHERE u.user_birthday_y <> ''";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$user_data = $db->sql_fetchrow($result);
$total_birthdays = $user_data['birthday_counter'];
//$stat_db->sql_freeresult($result);

$age_data = array();
$age_array = array();
$age_string_array = array();
$total_birthdays = 0;

$current_time = time();
$current_year = date('Y', $current_time);
$total_clusters = 14;
$first_cluster_end = 16;
$last_cluster_begin = 64;
$years_span = ($last_cluster_begin - $first_cluster_end) / ($total_clusters - 2);
$year_start = date('Y', mktime(0, 0, 0, 1, 1, $current_year - $last_cluster_begin));
$year_end = date('Y', mktime(0, 0, 0, 1, 1, $year_start + $years_span));
$last_cluster_year = date('Y', mktime(0, 0, 0, 1, 1, $current_year - $first_cluster_end));

// Less than $first_cluster_end
$sql = "SELECT COUNT(u.user_birthday_y) as birthday_counter
	FROM " . USERS_TABLE . " u
	WHERE (u.user_birthday_y <> '')
		AND (u.user_birthday_y >= " . $last_cluster_year . ")";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$row = array();
$row = $db->sql_fetchrow($result);
$age_data[$total_clusters - 1] = (!empty($row) ? $row['birthday_counter'] : 0);
$age_array[$total_clusters - 1] = $first_cluster_end;
$age_string_array[$total_clusters - 1] = $lang['LESS_THAN'] . '&nbsp;' . $age_array[$total_clusters - 1];

// More than $last_cluster_begin
$sql = "SELECT COUNT(u.user_birthday_y) as birthday_counter
	FROM " . USERS_TABLE . " u
	WHERE (u.user_birthday_y <> '')
		AND (u.user_birthday_y < " . $year_start . ")";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$row = array();
$row = $db->sql_fetchrow($result);
$age_data[0] = (!empty($row) ? $row['birthday_counter'] : 0);
$age_array[0] = $last_cluster_begin;
$age_string_array[0] = $lang['MORE_THAN'] . '&nbsp;' . $age_array[0];

$total_birthdays = $total_birthdays + $age_data[0] + $age_data[$total_clusters - 1];
$sql_array = array();
for ($i = 1; $i < ($total_clusters - 1); $i++)
{
	$sql = "SELECT COUNT(u.user_birthday_y) as birthday_counter
		FROM " . USERS_TABLE . " u
		WHERE (u.user_birthday_y > 0)
			AND (u.user_birthday_y >= " . $year_start . ")
			AND (u.user_birthday_y < " . $year_end . ")";
	//$sql_array[$i] = $sql . '<br />';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
	}

	$row = array();
	$row = $db->sql_fetchrow($result);
	$age_data[$i] = (!empty($row) ? $row['birthday_counter'] : 0);
	$age_array[$i] = $age_array[$i - 1] - $years_span;
	$age_string_array[$i] = ($age_array[$i - 1] - $years_span) . ' - ' . $age_array[$i - 1];
	$total_birthdays = $total_birthdays + $age_data[$i];
	$year_start = date('Y', mktime(0, 0, 0, 1, 1, $year_start + $years_span));
	$year_end = date('Y', mktime(0, 0, 0, 1, 1, $year_end + $years_span));
}

//print_r($age_data);
//print_r($sql_array);

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

$firstcount = max($age_data);

for ($i = (count($age_data) - 1); $i >= 0; $i--)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
	$statistics->do_math($firstcount, $age_data[$i], $total_birthdays);
	$template->assign_block_vars('stats_row', array(
		'RANK' => count($age_data) - $i,
		'CLASS' => $class,
		'PERCENTAGE' => ($age_data[$i] == 0) ? 0 : $statistics->percentage,
		'BAR' => ($age_data[$i] == 0) ? 0 : $statistics->bar_percent,
		'YEAR' => $age_string_array[$i],
		'HOWMANY' => $age_data[$i]
		)
	);
}

?>