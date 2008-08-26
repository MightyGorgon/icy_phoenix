<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// Top Posting Users
$percentage = 0;
$bar_percent = 0;

// Getting voting bar info
if(!$board_config['override_user_style'])
{
	if(($userdata['user_id'] != ANONYMOUS) && (isset($userdata['user_style'])))
	{
		$style = $userdata['user_style'];
		if(!$theme)
		{
			$style =  $board_config['default_style'];
		}
	}
	else
	{
		$style =  $board_config['default_style'];
	}
}
else
{
	$style =  $board_config['default_style'];
}

$sql = 'SELECT *
FROM ' . THEMES_TABLE . '
WHERE themes_id = ' . $style;

if (!($result = $db->sql_query($sql)))
{
	message_die(CRITICAL_ERROR, 'Couldn\'t query database for theme info.');
}

if(!$row = $db->sql_fetchrow($result))
{
	message_die(CRITICAL_ERROR, 'Couldn\'t get theme data for themes_id=' . $style . '.');
}

$current_template_path = 'templates/' . $row['template_name'] . '/';

$currect_time = time();

$sql ="SELECT user_id, username, user_posts, user_regdate,
(user_posts/(($currect_time - user_regdate)/ 86400)) rate,
ROUND(($currect_time - user_regdate)/ 86400) time_on_forum
FROM " . USERS_TABLE .
" WHERE (user_id <> " .ANONYMOUS . ") AND (user_posts > 0) ORDER BY rate DESC LIMIT " . $return_limit;

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$user_count = $db->sql_numrows($result);
$user_data = $db->sql_fetchrowset($result);

$firstrate = $user_data[0]['rate'];

$total = $firstrate;
for ($i = 0; $i < $user_count; $i++)
{
	$class = (!($i+1 % 2)) ? $theme['td_class2'] : $theme['td_class1'];

	$value = $user_data[$i]['rate'];

	$cst = ($firstrate > 0) ? 90 / $firstrate : 90;

	$bar_percent = round($value * $cst);

	$template->assign_block_vars('users', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => $user_data[$i]['username'],
		'BAR' => $bar_percent,
		'URL' => append_sid($phpbb_root_path . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_data[$i]['user_id']),
		'RATE' => round($user_data[$i]['rate'], 2),
		'TIME' => $user_data[$i]['time_on_forum']
		)
	);
}

$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TIME' => $lang['time_on_forum'],
	'L_POST_PER_DAY' => $lang['posts_day'],
	'L_USERNAME' => $lang['Username'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_fastest_users']
	)
);


?>