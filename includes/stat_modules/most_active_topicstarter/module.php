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

global $theme, $template;

$percentage = 0;
$bar_percent = 0;

//
// Getting voting bar info
//
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

$total_topics = $board_config['max_topics'];

$sql = 'SELECT u.user_id, u.username, COUNT(t.topic_poster) num_topics
FROM ' . USERS_TABLE . ' u, ' . TOPICS_TABLE .' t
WHERE (t.topic_poster <> ' . ANONYMOUS . ') AND (u.user_posts > 0) AND (u.user_id = t.topic_poster)
GROUP BY t.topic_poster ORDER BY num_topics DESC
LIMIT ' . $return_limit;

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve users data', '', __LINE__, __FILE__, $sql);
}

$user_count = $db->sql_numrows($result);
$user_data = $db->sql_fetchrowset($result);

$firstcount = $user_data[0]['num_topics'];

for ($i = 0; $i < $user_count; $i++)
{
	$class = (!($i+1 % 2)) ? $theme['td_class2'] : $theme['td_class1'];

	$cst = ($firstcount > 0) ? 90 / $firstcount : 90;

	if ($user_data[$i]['num_topics'] != 0)
	{
		$percentage = ($total_topics) ? round(min(100, ($user_data[$i]['num_topics'] / $total_topics) * 100)) : 0;
	}
	else
	{
		$percentage = 0;
	}

	$bar_percent = round($user_data[$i]['num_topics'] * $cst);

//	top_posters_do_math($firstcount, $user_data[$i]['num_topics'], $total_topics);

	$template->assign_block_vars('users', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => $user_data[$i]['username'],
		'PERCENTAGE' => $percentage,
		'BAR' => $bar_percent,
		'URL' => append_sid($phpbb_root_path . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_data[$i]['user_id']),
		'TOPICS' => $user_data[$i]['num_topics']
		)
	);
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

?>