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
	'L_POSTS' => $lang['Posts'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_USERNAME' => $lang['Username'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_top_posters']
	)
);

$sql = "SELECT SUM(user_posts) as total_posts FROM " . USERS_TABLE . " WHERE user_id <> " . ANONYMOUS;
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrow($result);
$total_posts = $row['total_posts'];

$sql = 'SELECT user_id, username, user_active, user_color, user_posts
	FROM ' . USERS_TABLE . '
	WHERE (user_id <> ' . ANONYMOUS . ') AND (user_posts > 0)
	ORDER BY user_posts DESC
	LIMIT ' . $return_limit;
$result = $stat_db->sql_query($sql);
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);

$firstcount = $user_data[0]['user_posts'];

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$statistics->do_math($firstcount, $user_data[$i]['user_posts'], $total_posts);

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