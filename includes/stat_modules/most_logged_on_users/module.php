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

$sql = "SELECT SUM(user_totaltime) as total_time FROM " . USERS_TABLE . "
WHERE user_id <> " . ANONYMOUS;

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$row = $stat_db->sql_fetchrow($result);
$total_time = $row['total_time'];

$sql = 'SELECT user_id, username, user_totaltime
FROM ' . USERS_TABLE . '
WHERE (user_id <> ' . ANONYMOUS . ') AND (user_totaltime > 0)
ORDER BY user_totaltime DESC
LIMIT ' . $return_limit;

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);

$firstcount = $user_data[0]['user_totaltime'];

$template->_tpldata['users.'] = array();
//reset($template->_tpldata['users.']);

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$statistics->do_math($firstcount, $user_data[$i]['user_totaltime'], $total_time);

	$template->assign_block_vars('users', array(
		'RANK' => $i+1,
		'CLASS' => $class,
		'USERNAME' => $user_data[$i]['username'],
		'PERCENTAGE' => $statistics->percentage,
		'BAR' => $statistics->bar_percent,
		'URL' => append_sid(IP_ROOT_PATH . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_data[$i]['user_id']),
		'TIME' => make_hours($user_data[$i]['user_totaltime'])
		)
	);
}

?>