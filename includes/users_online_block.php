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

if (defined('SHOW_ONLINE_CHAT') && $config['show_chat_online'])
{
	//$template->assign_block_vars('switch_ac_online', array());
	include_once(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_level
		FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE . " s, " . USERS_TABLE . " u
		WHERE s.session_time >= " . (time() - SESSION_REFRESH) . "
		AND s.session_user_id = u.user_id
		ORDER BY case user_level when 0 then 10 else user_level end";
	$result = $db->sql_query($sql);

	// Set all counters to 0
	$ac_reg_online_counter = $ac_guest_online_counter = $ac_online_counter = 0;
	$ac_username_lists = '';
	while($ac_online = $db->sql_fetchrow($result))
	{
		if($ac_online['user_id'] != ANONYMOUS)
		{
			$ac_username_lists .= (($ac_username_lists == '') ? '' : ', ') . colorize_username($ac_online['user_id'], $ac_online['username'], $ac_online['user_color'], $ac_online['user_active']);
			$ac_reg_online_counter++;
		}
		else
		{
			$ac_guest_online_counter++;
		}
		$ac_online_counter++;
	}
	$ac_username_lists = ($ac_username_lists == '') ? '' : ('[ ' . $ac_username_lists . ' ]');

	$ac_t_user = ($ac_online_counter == 0) ? $lang['AC_Online_users_zero_total'] : (($ac_online_counter == 1) ? $lang['AC_Online_user_total'] : (sprintf($lang['AC_Online_users_total'], $ac_online_counter)));
	$ac_r_user = ($ac_reg_online_counter == 0) ? $lang['Reg_users_zero_total'] : (($ac_reg_online_counter == 1) ? (sprintf($lang['Reg_user_total'], $ac_reg_online_counter)) : (sprintf($lang['Reg_users_total'], $ac_reg_online_counter)));
	$ac_g_user = ($ac_guest_online_counter == 0) ? $lang['Guest_users_zero_total'] : (($ac_guest_online_counter == 0) ? (sprintf($lang['Guest_user_total'], $ac_guest_online_counter)) : (sprintf($lang['Guest_users_total'], $ac_guest_online_counter)));
	$ac_online_text = $ac_t_user . $ac_r_user . $ac_g_user;
}

$logged_visible_online = 0;
$logged_hidden_online = 0;
$guests_online = 0;
$online_userlist = '';
$l_online_users = '';

$any_char = chr(0) . '%';
$one_char = chr(0) . '_';
if (!empty($topic_id) && !defined('IN_VIEWFORUM'))
{
	$user_forum_sql = ' AND s.session_page ' . $db->sql_like_expression("{$any_char}_t_={$topic_id}x{$any_char}");
}
elseif (!empty($forum_id))
{
	$user_forum_sql = ' AND s.session_page ' . $db->sql_like_expression("{$any_char}_f_={$forum_id}x{$any_char}");
}
else
{
	$user_forum_sql = '';
}

$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip, s.session_user_agent
	FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
	WHERE u.user_id = s.session_user_id
	AND s.session_time >= " . (time() - ONLINE_REFRESH) . "
		$user_forum_sql
	ORDER BY u.username ASC, s.session_ip ASC";
$result = $db->sql_query($sql);

$userlist_ary = array();
$userlist_visible = array();
$tmp_bots_array = array();

$prev_user_id = 0;
$prev_user_ip = '';
$prev_session_ip = '';
while($row = $db->sql_fetchrow($result))
{

	// User is logged in and therefore not a guest
	if ($row['session_logged_in'])
	{
		// Skip multiple sessions for one user
		if ($row['user_id'] != $prev_user_id)
		{
			$user_online_link = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
			if ($row['user_allow_viewonline'])
			{
				$logged_visible_online++;
			}
			else
			{
				$logged_hidden_online++;
				$user_online_link = '<em>' . $user_online_link . '</em>';
			}

			if ($row['user_allow_viewonline'] || ($userdata['user_level'] == ADMIN) || ($userdata['user_id'] == $row['user_id']))
			{
				$online_userlist .= (($online_userlist != '') ? ', ' : '') . $user_online_link;
			}
		}
		$prev_user_id = $row['user_id'];
	}
	else
	{
		// Skip multiple sessions for one user
		if (($row['session_ip'] != $prev_session_ip) || ($userdata['session_ip'] != ''))
		{
			$guests_online++;
			// MG BOTS Parsing - BEGIN
			$bot_name_tmp = bots_parse($row['session_ip'], $config['bots_color'], $row['session_user_agent']);
			if ($bot_name_tmp != false)
			{
				if (!in_array($bot_name_tmp, $tmp_bots_array))
				{
					$tmp_bots_array[] = $bot_name_tmp;
					$online_botlist .= ($online_botlist != '') ? ', ' . $bot_name_tmp : $bot_name_tmp;
				}
			}
			// MG BOTS Parsing - END
		}
	}
	$prev_session_ip = $row['session_ip'];
}
$db->sql_freeresult($result);

if (empty($online_botlist))
{
	$online_botlist = '';
}
else
{
	$online_botlist = ((isset($forum_id)) ? $lang['Bots_browsing_forum'] : $lang['Search_Engines']) . ' ' . $online_botlist;
}

if (empty($online_userlist))
{
	$online_userlist = $lang['None'];
}
// user always browsing - only needed if on view-forum & user is not hidden
if (isset($forum_id) && $userdata['session_logged_in'] && $userdata['user_allow_viewonline'])
{

	$user_browsing_link = colorize_username($userdata['user_id'], $userdata['username'], $userdata['user_color'], $userdata['user_active']);

	// if userlist shows `none` replace with user_browsing_link
	if ($online_userlist == $lang['None'])
	{
		$online_userlist = $user_browsing_link;
	}
	elseif (substr_count($online_userlist, $user_browsing_link) == 0)
	{
		// add link if user is missing from list
		$online_userlist .= ', ' . $user_browsing_link;
	}
}

//$online_userlist = ((isset($forum_id)) ? $lang['Browsing_forum'] : $lang['Registered_users']) . ' ' . $online_userlist;
$online_userlist = $lang['Registered_users'] . ' ' . $online_userlist;

$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

if ($total_online_users > $config['record_online_users'])
{
	set_config('record_online_users', $total_online_users);
	set_config('record_online_date', time());
}

if ($total_online_users == 0)
{
	$l_t_user_s = ((isset($topic_id)) ? $lang['Browsing_topic'] : ((isset($forum_id)) ? $lang['Browsing_forum'] : $lang['Online_users_zero_total']));
}
else
{
	$l_t_user_s = ((isset($topic_id)) ? $lang['Browsing_topic'] : ((isset($forum_id)) ? $lang['Browsing_forum'] : $lang['Online_users_total']));
}

if ($logged_visible_online == 0)
{
	$l_r_user_s = $lang['Reg_users_zero_total'];
}
elseif ($logged_visible_online == 1)
{
	$l_r_user_s = $lang['Reg_user_total'];
}
else
{
	$l_r_user_s = $lang['Reg_users_total'];
}

if ($logged_hidden_online == 0)
{
	$l_h_user_s = $lang['Hidden_users_zero_total'];
}
elseif ($logged_hidden_online == 1)
{
	$l_h_user_s = $lang['Hidden_user_total'];
}
else
{
	$l_h_user_s = $lang['Hidden_users_total'];
}

if ($guests_online == 0)
{
	$l_g_user_s = $lang['Guest_users_zero_total'];
}
elseif ($guests_online == 1)
{
	$l_g_user_s = $lang['Guest_user_total'];
}
else
{
	$l_g_user_s = $lang['Guest_users_total'];
}

$l_online_users = sprintf($l_t_user_s, $total_online_users) . ' ';
$l_online_users .= sprintf($l_r_user_s, $logged_visible_online);
$l_online_users .= sprintf($l_h_user_s, $logged_hidden_online);
$l_online_users .= sprintf($l_g_user_s, $guests_online);

?>