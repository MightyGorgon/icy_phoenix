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
	'SEPARATOR_CLASS' => $theme['td_class1'],
	'L_RANK' => $lang['Rank'],
	'L_RATE' => $lang['Rate'],
	'L_TOPIC' => $lang['Topic'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_interesting_topics']
	)
);

//All your code
$auth_data_sql = '';
$sql = 'SELECT forum_id FROM ' . FORUMS_TABLE;
if (!$result = $stat_db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve forum_id data", '', __LINE__, __FILE__, $sql);
}

while ($row = $stat_db->sql_fetchrow($result))
{
	$is_auth = auth('auth_view', $row['forum_id'], $userdata);
	if ($is_auth['auth_view'])
	{
		$auth_data_sql .= ($auth_data_sql != '') ? ', ' . $row['forum_id'] : $row['forum_id'];
	}
}

$sql = 'SELECT topic_id, topic_title, topic_replies, topic_views, topic_views / (topic_replies + 1) AS k
	FROM ' . TOPICS_TABLE ."
	WHERE forum_id IN ($auth_data_sql)
	AND topic_status <>". TOPIC_MOVED . '
	ORDER BY k DESC
	LIMIT ' . $return_limit;
if (!$result = $stat_db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve topic data", '', __LINE__, __FILE__, $sql);
}

$topic_data = $stat_db->sql_fetchrowset($result);

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < count($topic_data); $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
	$rate = round(($topic_data[$i]['k']), 0);
	$rate = ($rate > 100) ? 100 : $rate;
	$bar = ($rate > 95) ? 95 : $rate;
	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'TITLE' => $topic_data[$i]['topic_title'],
		'RATE' => $rate,
		'PERCENTAGE' => $rate,
		'BAR' => $bar,
		'URL' => append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_data[$i]['topic_id'])
		)
	);
}

/*
$sql = 'SELECT topic_id, topic_title, topic_replies, topic_views, topic_views/(topic_replies + 1) AS k
	FROM ' . TOPICS_TABLE . "
	WHERE forum_id IN ($auth_data_sql)
	AND topic_status <> ". TOPIC_MOVED . '
	ORDER BY k DESC
	LIMIT '. ($board_config['max_topics'] - ($return_limit) - 1) .', ' . ($return_limit);
if (!$result = $stat_db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve topic data", '', __LINE__, __FILE__, $sql);
}

$least_first_place = $board_config['max_topics'] - count($topic_data) + 1;

$topic_data = $stat_db->sql_fetchrowset($result);

$template->_tpldata['leasttopics.'] = array();
//reset($template->_tpldata['leasttopics.']);

for ($i = 0; $i < count($topic_data); $i++)
{
	$least_first_place++;
	$rate = round(($topic_data[$i]['k']), 0);
	$rate = ($rate > 100) ? 100 : $rate;
	$bar = ($rate > 95) ? 95 : $rate;
	$template->assign_block_vars('leasttopics', array(
		'RANK' => $least_first_place,
		'CLASS' => (!(($i + 1) % 2)) ? $theme['td_class2'] : $theme['td_class1'],
		'TITLE' => $topic_data[$i]['topic_title'],
		'RATE' => $rate,
		'PERCENTAGE' => $rate,
		'BAR' => $bar,
		'URL' => append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_data[$i]['topic_id'])
		)
	);
}
*/

?>