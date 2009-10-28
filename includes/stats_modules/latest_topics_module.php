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

// Latest Topics
$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TOPIC' => $lang['Topic'],
	'L_POSTTIME' => $lang['Post_time'],
	'MODULE_NAME' => $lang['module_name_latest_topics']
	)
);

// Authorization SQL - forum-based
$auth_data_sql = '';

$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

$sql = 'SELECT forum_id FROM ' . FORUMS_TABLE . ' WHERE forum_type = ' . FORUM_POST;
$result = $stat_db->sql_query($sql);

while ($row = $stat_db->sql_fetchrow($result))
{
	if ($is_auth_ary[$row['forum_id']]['auth_view'])
	{
		$auth_data_sql .= ($auth_data_sql != '') ? ', ' . $row['forum_id'] : $row['forum_id'];
	}
}

if ($auth_data_sql != '')
{
	$sql = 'SELECT topic_id, topic_title, topic_time
		FROM ' . TOPICS_TABLE . '
		WHERE forum_id IN (' . $auth_data_sql . ') AND (topic_status <> 2)
		ORDER BY topic_time DESC
		LIMIT ' . $return_limit;
	$result = $stat_db->sql_query($sql);
	$topic_count = $stat_db->sql_numrows($result);
	$topic_data = $stat_db->sql_fetchrowset($result);
}
else
{
	$topic_count = 0;
	$topic_data = array();
}

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < $topic_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$posttime = create_date('d F Y, H:i', $topic_data[$i]['topic_time'], $config['board_timezone']);

	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'POSTTIME' => $posttime,
		'TITLE' => $topic_data[$i]['topic_title'],
		'URL' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC .'?' . POST_TOPIC_URL . '=' . $topic_data[$i]['topic_id'])
		)
	);
}

?>