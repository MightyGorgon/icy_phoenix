<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

// Start user modifiable variables

// Define the Number of statistics output
$return_limit = 8;
define('SERVER_TIME_ZONE', $board_config['board_timezone']);

// End user modifiable variables

$percentage = 0;
$bar_percent = 0;

// Functions

// Do the math ;)
function do_math($firstval, $value, $total)
{
	global $percentage, $bar_percent;

	$cst = ($firstval > 0) ? 90 / $firstval : 90;

	if ($value != 0 )
	{
		$percentage = ($total) ? round(min(100, ($value / $total) * 100) * 100) / 100 : 0;
	}
	else
	{
		$percentage = 0;
	}

	$bar_percent = round($value * $cst);
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '15';
$cms_page_name = 'site_hist';
$auth_level_req = $board_config['auth_view_site_hist'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_site_hist'] == 1) ? true : false;

$page_title = $lang['Site_history'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

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

$sql = "SELECT *
	FROM " . THEMES_TABLE . "
	WHERE themes_id = " . $style;

if (!($result = $db->sql_query($sql,false,true)))
{
	message_die(CRITICAL_ERROR, "Couldn't query database for theme info.");
}

if(!$row = $db->sql_fetchrow($result))
{
	message_die(CRITICAL_ERROR, "Couldn't get theme data for themes_id=$style.");
}

$current_template_path = 'templates/' . $row['template_name'] . '/';

$template->set_filenames(array('body' => 'site_hist.tpl'));

$db->sql_freeresult($result);

$current_time = time();
$minutes = date('is', $current_time);
// $minutes= sprintf('%04d',date('is',$current_time));
$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
$dato = date('H', $current_time);
$time_today = $hour_now - (3600 * $dato);
$year = create_date('Y', $current_time, 0);
$month [0] = mktime (0, 0, 0, 1, 1, $year) - (SERVER_TIME_ZONE * 3600) + 3600;
$month [1] = $month [0] + 2678400;
$month [2] = mktime (0, 0, 0, 3, 1, $year) - (SERVER_TIME_ZONE * 3600) + 3600;
$month [3] = $month [2] + 2678400;
$month [4] = $month [3] + 2592000;
$month [5] = $month [4] + 2678400;
$month [6] = $month [5] + 2592000;
$month [7] = $month [6] + 2678400;
$month [8] = $month [7] + 2678400;
$month [9] = $month [8] + 2592000;
$month [10] = $month [9] + 2678400;
$month [11] = $month [10] + 2592000;
$month [12] = $month [11] + 2678400;
$time_thismonth = $month [date('n') - 1];
$time_thisweek = $time_today - ((date('w', $time_today) - 1) * 86400);
if ((time() - $time_thisweek) < 0)
{
	$time_thisweek_poster = $time_thisweek - (60 * 60 * 24 * 7);
	$time_today_poster = $time_today - (60 * 60 * 24 * 6);
}
else
{
	$time_thisweek_poster = $time_thisweek;
	$time_today_poster = $time_today;
}
$this_month = create_date('n', $time_thismonth, SERVER_TIME_ZONE);
$l_this_month = create_date('F', $time_thismonth, SERVER_TIME_ZONE);
$l_this_day = create_date('D', $time_today, SERVER_TIME_ZONE);

// This week top postes
$sql = "SELECT u.user_id, u.username, count(u.user_id) as user_posts
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
	WHERE u.user_id = p.poster_id
		AND p.post_time > '" . $time_thisweek_poster . "'
	GROUP BY user_id
	ORDER BY user_posts DESC
	LIMIT " . $return_limit;

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve topposters data", "", __LINE__, __FILE__, $sql);
}

$user_count = $db->sql_numrows($result);
$user_data = $db->sql_fetchrowset($result);
$firstcount = $user_data[0]['user_posts'];

for ($i = 0; $i < $user_count; $i++)
{
	$total_posts_thisweek += $user_data[$i]['user_posts'];
}

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
	do_math($firstcount, $user_data[$i]['user_posts'], $total_posts_thisweek);
	$template->assign_block_vars('top_posters_week', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => ($user_data[$i]['user_id'] == -1) ? $lang['Guest'] : colorize_username($user_data[$i]['user_id']),
		'PERCENTAGE' => $percentage,
		'BAR' => $bar_percent,
		'POSTS' => $user_data[$i]['user_posts']
		)
	);
}
$db->sql_freeresult($result);

// This months top postes
$sql = "SELECT u.user_id, u.username, count(u.user_id) as user_posts
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
	WHERE u.user_id = p.poster_id
		AND p.post_time > '" . $time_thismonth . "'
	GROUP BY user_id
	ORDER BY user_posts DESC
	LIMIT " . $return_limit;

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve topposters data", "", __LINE__, __FILE__, $sql);
}

$user_count = $db->sql_numrows($result);
$user_data = $db->sql_fetchrowset($result);
$firstcount = $user_data[0]['user_posts'];

for ($i = 0; $i < $user_count; $i++)
{
	$total_posts_thismonth += $user_data[$i]['user_posts'];
}
for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
	do_math($firstcount, $user_data[$i]['user_posts'], $total_posts_thismonth);
	$template->assign_block_vars('top_posters', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'USERNAME' => ($user_data[$i]['user_id'] == -1) ? $lang['Guest'] : colorize_username($user_data[$i]['user_id']),
		'PERCENTAGE' => $percentage,
		'BAR' => $bar_percent,
		'POSTS' => $user_data[$i]['user_posts'])
	);
}
$db->sql_freeresult($result);

// months site_history
for ($i = 0; $i < 12; $i++)
{
	$sql = "SELECT COUNT(user_regdate) as new_users
		FROM " . USERS_TABLE . "
		WHERE user_regdate >= " . $month[$i] . "
			AND user_regdate < " . $month[$i + 1];

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	$new_users = $db->sql_fetchrow($result);
	$new_users = $new_users['new_users'];

	$sql = "SELECT MAX(reg + hidden + guests) as total, date, MAX(reg) as reg , MAX(hidden) as hidden, MAX(guests) as guests, SUM(new_topics) as topics, SUM(new_posts) as posts
		FROM " . SITE_HISTORY_TABLE . "
		WHERE date>= " . $month[$i] . "
			AND date < " . $month[$i + 1] . "
		GROUP BY (date >= " . $month[$i] . "
			AND date < " . $month[$i+1] . ")";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result))
	{
		$site_monthly = $db->sql_fetchrow($result);
		if ($i+1 == $this_month)
		{
			$l_month = '<strong>' . $l_this_month . '</strong>';
		}
		else
		{
			$l_month = create_date('F', $month[$i], SERVER_TIME_ZONE);
		}

		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('site_month', array(
			'CLASS' => $class,
			'MONTH' => $l_month,
			'TOTAL' => $site_monthly['total'],
			'REG' => ($site_monthly['reg'])? $site_monthly['reg'] : $lang['None'],
			'HIDDEN' => ($site_monthly['hidden']) ? $site_monthly['hidden'] : $lang['None'],
			'GUESTS' => ($site_monthly['guests']) ? $site_monthly['guests'] : $lang['None'],
			'TOPICS' => ($site_monthly['topics']) ? $site_monthly['topics'] : $lang['None'],
			'POSTS' => ($site_monthly['posts']) ? $site_monthly['posts'] : $lang['None'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	else
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('site_month', array(
			'CLASS' => $class,
			'MONTH' => create_date('F', $month[$i], SERVER_TIME_ZONE),
			'TOTAL' => $lang['Not_availble'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}

	$db->sql_freeresult($result);
}

// last 7 days site_history
$time_today -= 518400;
for ($i = 0; $i < 7; $i++)
{
	if ($i == 6)
	{
		$l_currrent_day = '<strong>' . create_date('D', $time_today, SERVER_TIME_ZONE) . '</strong>';
	}
	else
	{
		$l_currrent_day = create_date('D', $time_today, SERVER_TIME_ZONE);
	}
	$sql = "SELECT COUNT(user_regdate) as new_users
		FROM " . USERS_TABLE . "
		WHERE user_regdate >= " . $time_today . "
			AND user_regdate < " . ($time_today + 86400);

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	$new_users = $db->sql_fetchrow($result);
	$new_users = $new_users['new_users'];

	$sql = "SELECT MAX(reg + hidden + guests) as total, date, MAX(reg) as reg , MAX(hidden) as hidden, MAX(guests) as guests, SUM(new_topics) as topics, SUM(new_posts) as posts
		FROM " . SITE_HISTORY_TABLE . "
		WHERE date >= " . $time_today . "
			AND date < " . ($time_today + 86400) . "
		GROUP BY (date >= " . $time_today . "
			AND date < " . ($time_today + 86400) . ")";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}
	if ($db->sql_numrows($result))
	{
		$site_week = $db->sql_fetchrow($result);
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('site_week', array(
			'CLASS' => $class,
			'DAY' => $l_currrent_day,
			'TOTAL' => $site_week['total'],
			'REG' => ($site_week['reg'])? $site_week['reg'] : $lang['None'],
			'HIDDEN' => ($site_week['hidden']) ? $site_week['hidden'] : $lang['None'],
			'GUESTS' => ($site_week['guests']) ? $site_week['guests'] : $lang['None'],
			'TOPICS' => ($site_week['topics']) ? $site_week['topics'] : $lang['None'],
			'POSTS' => ($site_week['posts']) ? $site_week['posts'] : $lang['None'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	else
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('site_week', array(
			'CLASS' => $class,
			'DAY' => $l_currrent_day,
			'TOTAL' => $lang['Not_availble'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	$time_today += 86400;
	$db->sql_freeresult($result);
}

// site_history last X hours
$hour_now -=(($return_limit - 1) * 3600);
for ($i = 0; $i < $return_limit; $i++)
{

	if ($i == $return_limit - 1)
	{
		$l_currrent_time = '<strong>' . create_date('H:i', $hour_now, SERVER_TIME_ZONE) . '</strong>';
	}
	else
	{
		$l_currrent_time = create_date('H:i', $hour_now, SERVER_TIME_ZONE);
	}

	$sql = "SELECT COUNT(user_regdate) as new_users
		FROM " . USERS_TABLE . "
		WHERE user_regdate >= " . $hour_now . "
			AND user_regdate < " . ($hour_now + 3599);

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	$new_users = $db->sql_fetchrow($result);
	$new_users = $new_users['new_users'];

	$sql = "SELECT MAX(reg + hidden + guests) as total, date, MAX(reg) as reg , MAX(hidden) as hidden, MAX(guests) as guests, SUM(new_topics) as topics, SUM(new_posts) as posts
		FROM " . SITE_HISTORY_TABLE . "
		WHERE date >= " . $hour_now . "
			AND date < " . ($hour_now + 3599) . "
		GROUP BY (date >= " . $hour_now . "
			AND date < " . ($hour_now + 3599) . ")";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result))
	{
		$site_today = $db->sql_fetchrow($result);
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('site_today', array(
			'CLASS' => $class,
			'TIME' => $l_currrent_time,
			'TOTAL' => $site_today['total'],
			'REG' => ($site_today['reg']) ? $site_today['reg'] : $lang['None'],
			'HIDDEN' => ($site_today['hidden']) ? $site_today['hidden'] : $lang['None'],
			'GUESTS' => ($site_today['guests']) ? $site_today['guests'] : $lang['None'],
			'TOPICS' => ($site_today['topics']) ? $site_today['topics'] : $lang['None'],
			'POSTS' => ($site_today['posts']) ? $site_today['posts'] : $lang['None'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	else
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];
		$template->assign_block_vars('site_today', array(
			'CLASS' => $class,
			'TIME' => $l_currrent_time,
			'TOTAL' => $lang['Not_availble'],
			'NEW_USERS' => ($new_users) ? $new_users : $lang['None']
			)
		);
	}
	$hour_now += 3600;
	$db->sql_freeresult($result);
}

$template->assign_vars(array(
	'MONTH_BACK' => sprintf($lang['Most_online'], create_date('Y', $time_thismonth, SERVER_TIME_ZONE)),
	'THIS_MONTH' => $l_this_month . ' ' . create_date('Y', $time_thismonth, SERVER_TIME_ZONE),
	'WEEK_BACK' => $lang['Most_online_week'],
	'24_BACK' => sprintf($lang['Last_24'], $return_limit),
	'L_USERS_TOTAL' => $lang['Total_users'],
	'L_REG_USERS' => $lang['Reg_users'],
	'L_HIDDEN_USERS' => $lang['Hidden_users'],
	'L_GUESTS_USERS' => $lang['Guests_users'],
	'L_NEW_USERS' => $lang['New_users'],
	'L_NEW_TOPICS' => $lang['New_topics'],
	'L_NEW_POSTS' => $lang['New_posts_reply'],
	'L_MONTH' => $lang['Month'],
	'L_DAY' => $lang['Week_day'],
	'L_TIME' => $lang['Time'],
	'L_TOP_POSTERS' => $lang['Top_Posting_Users'],
	'L_TOP_POSTERS_WEEK' => sprintf($lang['Top_Posting_Users_week'], (create_date('D', $time_thisweek_poster, SERVER_TIME_ZONE)) . ' - ' . $l_this_day),
	'L_RANK' => $lang['Rank'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_GRAPH' => $lang['Graph'],
	'L_USERNAME' => $lang['Username'],
	'L_POSTS' => $lang['Posts'],
	'L_TOP_VISITORS' => $lang['Top_Visiting_Users'],
	'L_TOTAL_TIME' => $lang['Time_spend'] = 'Time spend',
	'PAGE_NAME' => $lang['Statistics'])
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);


?>