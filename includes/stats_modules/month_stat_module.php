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

// Month Statistics (New: posts, topics and users)
$template->assign_vars(array(
	'L_DATE' => $lang['Date'],
	'L_USERS' => $lang['New_users'],
	'L_TOPIC' => $lang['New_topics'],
	'L_REPLIE' => $lang['New_replies'],
	'MODULE_NAME' => $lang['module_name_month_stat']
	)
);

// Number of years to display.
$nyear = 1;
$nyear *= 12;

// New posts by Months.
$sql = 'SELECT MONTH(FROM_UNIXTIME(post_time)) as mon, YEAR(FROM_UNIXTIME(post_time)) as year,COUNT(*) AS ant
	FROM ' . POSTS_TABLE . '
	GROUP BY year,mon
	ORDER BY year DESC, mon DESC
	LIMIT 0,'. $nyear;
$result = $stat_db->sql_query($sql);
$posts_count = $stat_db->sql_numrows($result);
$posts_data = $stat_db->sql_fetchrowset($result);

// New topics by Months.
$sql = 'SELECT MONTH(FROM_UNIXTIME(topic_time)) as mon,  YEAR(FROM_UNIXTIME(topic_time)) as year,COUNT(*) AS ant
	FROM ' . TOPICS_TABLE . '
	GROUP BY year,mon
	ORDER BY year DESC, mon DESC
	LIMIT 0,'. $nyear;
$result = $stat_db->sql_query($sql);
$topics_count = $stat_db->sql_numrows($result);
$topics_data = $stat_db->sql_fetchrowset($result);

// New users by days.
$sql = 'SELECT MONTH(FROM_UNIXTIME(user_regdate)) as mon,  YEAR(FROM_UNIXTIME(user_regdate)) as year,COUNT(*) AS ant
	FROM ' . USERS_TABLE . '
	GROUP BY year,mon
	ORDER BY year DESC, mon DESC
	LIMIT 0,' . $nyear;
$result = $stat_db->sql_query($sql);
$users_count = $stat_db->sql_numrows($result);
$users_data = $stat_db->sql_fetchrowset($result);

$user_con = 0;
$user_sum = 0;
$post_con = 0;
$post_sum = 0;
$topic_con = 0;
$topic_sum = 0;

$template->_tpldata['stats_row.'] = array();

for ($i = 0; $i < $nyear; $i= $i + 1)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
	$date = (mktime (0, 0, 0, gmdate('m') - $i, gmdate('d'), gmdate('Y')));
	$datem = gmdate('m', $date);
	$datey = gmdate('Y', $date);

	$f = 0;

	// Posts.
	for ($j = 0; $j < $nyear; $j=$j+1)
	{
		if (($posts_data[$j]['mon'] == $datem) && ($posts_data[$j]['year'] == $datey))
		{
			$post = $posts_data[$j]['ant'];
			$post_sum += $posts_data[$j]['ant'];
			$post_con++;
			$f = 1;
		}
	}
	if ($f)
	{
		$f = 0;
	}
	else
	{
		$post = '-';
	}

	// Topics.
	for ($j = 0; $j < $nyear; $j= $j + 1)
	{
		if ($topics_data[$j]['mon'] == $datem && $topics_data[$j]['year'] == $datey)
		{
			$topic = $topics_data[$j]['ant'];
			$topic_sum += $topics_data[$j]['ant'];
			$topic_con++;
			$f = 1;
		}
	}
	if ($f)
	{
		$f = 0;
	}
	else
	{
		$topic = '-';
	}

	// Users.
	for ($j = 0; $j < $nyear; $j=$j+1)
	{
		if ($users_data[$j]['mon'] == $datem && $users_data[$j]['year'] == $datey)
		{
			$user = $users_data[$j]['ant'];
			$user_sum += $users_data[$j]['ant'];
			$user_con++;
			$f = 1;
		}
	}
	if ($f)
	{
		$f = 0;
	}
	else
	{
		$user = '-';
	}

	if ($user == '-' && $topic == '-' && $post == '-')
	{
		break;
	}

	$date = create_date('F Y', (mktime (0, 0, 0, gmdate('m') - $i, gmdate('d'), gmdate('Y'))), $tz);
	$template->assign_block_vars('stats_row', array(
		'CLASS' => $class,
		'DATE' => $date,
		'POST' => $post,
		'TOPIC' => $topic,
		'USER' => $user
		)
	);
}

$template->assign_block_vars('stats_row', array(
	'CLASS' => 'row2',
	'DATE' => $lang['Sum_Table'],
	'USER' => $user_sum,
	'TOPIC' => $topic_sum,
	'POST' => $post_sum
	)
);

$template->assign_block_vars('stats_row', array(
	'CLASS' => 'row2',
	'DATE' => $lang['Avg_Table'],
	'USER' => ($user_con > 0) ? round($user_sum / $user_con, 2) : 0,
	'TOPIC' => ($topic_sum > 0) ? round($topic_sum / $topic_con, 2) : 0,
	'POST' => ($post_sum > 0) ? round($post_sum / $post_con, 2) : 0,
	)
);

?>