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

$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TOPICS' => $lang['Topics'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_USERNAME' => $lang['Username'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_most_active_topicstarter']
	)
);

$percentage = 0;
$bar_percent = 0;

$total_topics = $config['max_topics'];

$sql = 'SELECT u.user_id, u.username, u.user_active, u.user_color, COUNT(t.topic_poster) num_topics
	FROM ' . USERS_TABLE . ' u, ' . TOPICS_TABLE .' t
	WHERE (t.topic_poster <> ' . ANONYMOUS . ') AND (u.user_posts > 0) AND (u.user_id = t.topic_poster)
	GROUP BY t.topic_poster ORDER BY num_topics DESC
	LIMIT ' . $return_limit;
$result = $stat_db->sql_query($sql);
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);

$firstcount = $user_data[0]['num_topics'];

$template->_tpldata['stats_row.'] = array();

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$percentage = 0;
	$bar_percent = 0;
	$cst = ($firstcount > 0) ? (90 / $firstcount) : 90;
	if ($user_data[$i]['num_topics'] != 0)
	{
		$percentage = ($total_topics) ? round(min(100, ($user_data[$i]['num_topics'] / $total_topics) * 100)) : 0;
		$bar_percent = round($user_data[$i]['num_topics'] * $cst);
	}

	// top_posters_do_math($firstcount, $user_data[$i]['num_topics'], $total_topics);

	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => colorize_username($user_data[$i]['user_id'], $user_data[$i]['username'], $user_data[$i]['user_color'], $user_data[$i]['user_active']),
		'PERCENTAGE' => $percentage,
		'BAR' => $bar_percent,
		'TOPICS' => $user_data[$i]['num_topics']
		)
	);
}

?>