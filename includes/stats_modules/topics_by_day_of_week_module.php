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
	'L_TITLE' => $lang['module_name_topics_by_day_of_week'],
	'L_TOPICS' => $lang['Topics'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_DOW' => $lang['Dow'],
	'L_GRAPH' => $lang['Graph']
	)
);

// define graph bars
$statistics->init_bars();

// get total topics
$sql = "SELECT COUNT(topic_id) as total_topics FROM " . TOPICS_TABLE;
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrow($result);
$total_topics = $row['total_topics'];

// return statistics
$sql = 'SELECT DAYOFWEEK(FROM_UNIXTIME(topic_time)) as dow, COUNT(*) AS ct
FROM ' . TOPICS_TABLE . '
GROUP BY DAYOFWEEK(FROM_UNIXTIME(topic_time))
ORDER BY DAYOFWEEK(FROM_UNIXTIME(topic_time)) ASC';
$result = $stat_db->sql_query($sql);
$topics_data = $stat_db->sql_fetchrowset($result);

// get highest topic count
$max_topic_ct = '0';

for ($i = 0; $i < 7; $i++)
{
	$max_topic_ct = max($max_topic_ct, $topics_data[$i]['ct']);
}

$template->_tpldata['stats_row.'] = array();

// build rows
for ($i = 1; $i < 8; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	if ($topics_data[$i - 1]['ct'])
	{
		$traffic[$i] = $topics_data[$i - 1]['ct'];
	}

	$statistics->do_math($max_topic_ct, $topics_data[$i - 1]['ct'], $total_topics);

	$template->assign_block_vars('stats_row', array(
		'CLASS' => $class,
		'DOW' => $lang['datetime'][$days[$i]],
		'TOPICS' => $traffic[$i],
		'PERCENTAGE' => $statistics->percentage,
		'BAR' => $statistics->bar_percent
		)
	);
}

?>