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

/*
* generate_user_info(&$row, $date_format = false, $group_mod = false)
* This function is used to generate a full set of user details
* Example:
* $user_info = array();
* $user_info = generate_user_info($row);
* foreach ($user_info as $k => $v)
* {
* 	$$k = $v;
* }
*/
function generate_user_info(&$row, $date_format = false, $is_moderator = false)
{
	global $board_config, $lang, $images, $userdata;

	$date_format = ($date_format == false) ? $lang['JOINED_DATE_FORMAT'] : $date_format;

	$info_array = array('avatar', 'from', 'posts', 'joined', 'gender', 'flag', 'style', 'age', 'birthday', 'avatar', 'profile_url', 'profile_img', 'profile', 'pm_url', 'pm_img', 'pm', 'search_url', 'search_img', 'search', 'ip_url', 'ip_img', 'ip', 'email_url', 'email_img', 'email', 'www_url', 'www_img', 'www', 'aim_url', 'aim_img', 'aim', 'icq_url', 'icq_status_img', 'icq_img', 'icq', 'msn_url', 'msn_img', 'msn', 'skype_url', 'skype_img', 'skype', 'yim_url', 'yim_img', 'yim', 'online_status_url', 'online_status_class', 'online_status_img', 'online_status');

	// Initialize everything...
	$user_info = array();
	for ($i = 0; $i < count($info_array); $i++)
	{
		$user_info[$info_array[$i]] = '';
	}

	$user_info['from'] = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
	$user_info['joined'] = create_date($date_format, $row['user_regdate'], $board_config['board_timezone']);
	$user_info['posts'] = ($row['user_posts']) ? $row['user_posts'] : 0;
	$user_info['style'] = ($row['style_name']) ? $row['style_name'] : '';

	$user_info['avatar'] = user_get_avatar($row['user_id'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

	if (empty($userdata['user_id']) || ($userdata['user_id'] == ANONYMOUS))
	{
		if (!empty($row['user_viewemail']))
		{
			$user_info['email_img'] = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Hidden_email'] . '" title="' . $lang['Hidden_email'] . '" />';
		}
		else
		{
			$user_info['email_img'] = '&nbsp;';
		}
		$user_info['email'] = '&nbsp;';
	}
	elseif (!empty($row['user_viewemail']) || $is_moderator || $userdata['user_level'] == ADMIN)
	{
		$user_info['email_url'] = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $row['user_id']) : 'mailto:' . $row['user_email'];
		$user_info['email_img'] = '<a href="' . $user_info['email_url'] . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
		$user_info['email'] = '<a href="' . $user_info['email_url'] . '">' . $lang['Send_email'] . '</a>';
	}
	else
	{
		$user_info['email_img'] = '&nbsp;';
		$user_info['email'] = '&nbsp;';
	}

	if (isset($row['ct_last_used_ip']) && ($userdata['user_level'] == ADMIN))
	{
		$user_info['ip_url'] = 'http://www.nic.com/cgi-bin/whois.cgi?query=' . decode_ip($row['ct_last_used_ip']);
		$user_info['ip_img'] = '<a href="' . $user_info['ip_url'] . '" target="_blank"><img src="' . $images['icon_ip2'] . '" alt="' . $lang['View_IP'] . ' (' . decode_ip($row['ct_last_used_ip']) . ')" title="' . $lang['View_IP'] . ' (' . decode_ip($row['ct_last_used_ip']) . ')" /></a>';
		$user_info['ip'] = '<a href="' . $user_info['ip_url'] . '">' . $lang['View_IP'] . '</a>';
	}

	$user_info['profile_url'] = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']);
	$user_info['profile_img'] = '<a href="' . $user_info['profile_url'] . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
	$user_info['profile'] = '<a href="' . $user_info['profile_url'] . '">' . $lang['Read_profile'] . '</a>';

	$user_info['pm_url'] = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $row['user_id']);
	$user_info['pm_img'] = '<a href="' . $user_info['pm_url'] . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
	$user_info['pm'] = '<a href="' . $user_info['pm_url'] . '">' . $lang['Send_private_message'] . '</a>';

	$user_info['search_url'] = append_sid(SEARCH_MG . '?search_author=' . urlencode($username) . '&amp;showresults=posts');
	$user_info['search_img'] = '<a href="' . $search_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $username) . '" title="' . sprintf($lang['Search_user_posts'], $username) . '" /></a>';
	$user_info['search'] = '<a href="' . $search_url . '">' . sprintf($lang['Search_user_posts'], $username) . '</a>';

	$user_info['www_img'] = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
	$user_info['www'] = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Visit_website'] . '</a>' : '';
	$user_info['www_url'] = ($row['user_website'] != '') ? $row['user_website'] : '';

	$user_info['aim_img'] = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], $images['icon_aim2']) : '';
	$user_info['aim'] = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], false) : '';
	$user_info['aim_url'] = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], false, true) : '';

	$user_info['icq_status_img'] = (!empty($row['user_icq'])) ? '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&img=5" width="18" height="18" /></a>' : '';
	$user_info['icq_img'] = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], $images['icon_icq2']) : '';
	$user_info['icq'] = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], false) : '';
	$user_info['icq_url'] = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], false, true) : '';

	$user_info['msn_img'] = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], $images['icon_msnm2']) : '';
	$user_info['msn'] = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], false) : '';
	$user_info['msn_url'] = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], false, true) : '';

	$user_info['skype_img'] = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], $images['icon_skype2']) : '';
	$user_info['skype'] = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], false) : '';
	$user_info['skype_url'] = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], false, true) : '';

	$user_info['yim_img'] = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], $images['icon_yim2']) : '';
	$user_info['yim'] = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], false) : '';
	$user_info['yim_url'] = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], false, true) : '';

	// ONLINE / OFFLINE - BEGIN
	$user_info['online_status_url'] = append_sid('viewonline.' . PHP_EXT);
	$user_info['online_status_lang'] = $lang['Offline'];
	$user_info['online_status_class'] = 'offline';
	if (isset($row['user_allow_viewonline']) && ($row['user_session_time'] >= (time() - $board_config['online_time'])))
	{
		if ($row['user_allow_viewonline'])
		{
			$user_info['online_status_img'] = '<a href="' . $user_info['online_status_url'] . '"><img src="' . $images['icon_online2'] . '" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" /></a>';
			$user_info['online_status_lang'] = $lang['Online'];
			$user_info['online_status_class'] = 'online';
		}
		elseif (($userdata['user_level'] == ADMIN) || ($userdata['user_id'] == $user_id))
		{
			$user_info['online_status_img'] = '<a href="' . $user_info['online_status_url'] . '"><img src="' . $images['icon_hidden2'] . '" alt="' . $lang['Hidden'] . '" title="' . $lang['Hidden'] . '" /></a>';
			$user_info['online_status_lang'] = $lang['Hidden'];
			$user_info['online_status_class'] = 'hidden';
		}
		else
		{
			$user_info['online_status_img'] = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
			$user_info['online_status_lang'] = $lang['Offline'];
			$user_info['online_status_class'] = 'offline';
		}
	}
	else
	{
		$user_info['online_status_img'] = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
		$user_info['online_status_lang'] = $lang['Offline'];
		$user_info['online_status_class'] = 'offline';
	}
	// ONLINE / OFFLINE - END

	// GENDER - BEGIN
	$user_info['gender'] = '';
	if (isset($row['user_gender']))
	{
		switch ($row['user_gender'])
		{
			case 1:
				$user_info['gender'] = '<img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'].  ': ' . $lang['Male'] . '" title="' . $lang['Gender'] . ': ' . $lang['Male'] . '" />';
				break;
			case 2:
				$user_info['gender'] = '<img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender']. ': ' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" />';
				break;
			default:
				$user_info['gender'] = '';
				break;
		}
	}
	// GENDER - END

	if (isset($row['user_birthday_y']))
	{
		$time_now = time();
		$b_year = create_date('Y', $time_now, $board_config['board_timezone']);
		$user_info['age'] = '(' . (intval($b_year) - intval($row['user_birthday_y'])) . ')';
	}

/*
	$template->assign_vars(array(
		'L_USER_PROFILE' => $lang['Profile'],
		'L_PM' => $lang['Private_Message'],
		'L_EMAIL' => $lang['Email'],
		'L_POSTS' => $lang['Posts'],
		'L_CONTACTS' => $lang['User_Contacts'],
		'L_WEBSITE' => $lang['Website'],
		'L_FROM' => $lang['Location'],
		'L_ONLINE_STATUS' => $lang['Online_status'],

		'FROM' => $user_info['from'],
		'JOINED' => $user_info['joined'],
		'POSTS' => $user_info['posts'],
		'AVATAR_IMG' => $user_info['avatar'],
		'AGE' => $user_info['age'],
		'GENDER' => $user_info['gender'],
		'STYLE' => $user_info['style'],
		'PROFILE_URL' => $user_info['profile_url'],
		'PROFILE_IMG' => $user_info['profile_img'],
		'PROFILE' => $user_info['profile'],
		'PM_URL' => $user_info['pm_url'],
		'PM_IMG' => $user_info['pm_img'],
		'PM' => $user_info['pm'],
		'SEARCH_URL' => $user_info['search_url'],
		'SEARCH_IMG' => $user_info['search_img'],
		'SEARCH' => $user_info['search'],
		'IP_URL' => $user_info['ip_url'],
		'IP_IMG' => $user_info['ip_img'],
		'IP' => $user_info['ip'],
		'EMAIL_URL' => $user_info['email_url'],
		'EMAIL_IMG' => $user_info['email_img'],
		'EMAIL' => $user_info['email'],
		'WWW_URL' => $user_info['www_url'],
		'WWW_IMG' => $user_info['www_img'],
		'WWW' => $user_info['www'],
		'AIM_URL' => $user_info['aim_url'],
		'AIM_IMG' => $user_info['aim_img'],
		'AIM' => $user_info['aim'],
		'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
		'ICQ_URL' => $user_info['icq_url'],
		'ICQ_IMG' => $user_info['icq_img'],
		'ICQ' => $user_info['icq'],
		'MSN_URL' => $user_info['msn_url'],
		'MSN_IMG' => $user_info['msn_img'],
		'MSN' => $user_info['msn'],
		'SKYPE_URL' => $user_info['skype_url'],
		'SKYPE_IMG' => $user_info['skype_img'],
		'SKYPE' => $user_info['skype'],
		'YIM_URL' => $user_info['yim_url'],
		'YIM_IMG' => $user_info['yim_img'],
		'YIM' => $user_info['yim'],
		'ONLINE_STATUS_URL' => $user_info['online_status_url'],
		'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
		'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
		'ONLINE_STATUS' => $user_info['online_status'],
		'L_ONLINE_STATUS' => $user_info['online_status_lang'],
		)
	);
*/

	return $user_info;
}

/*
* Top X Posters
*/
function top_posters($user_limit, $show_admin, $show_mod)
{
	global $db;
	if (($show_admin == true) && ($show_mod == true))
	{
		$sql_level = '';
	}
	elseif ($show_admin == true)
	{
		$sql_level = "AND u.user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
	}
	elseif ($show_mod == true)
	{
		$sql_level = "AND u.user_level IN (" . USER . ", " . MOD . ")";
	}
	else
	{
		$sql_level = "AND u.user_level = " . USER;
	}
	$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_level
	FROM " . USERS_TABLE . " u
	WHERE (u.user_id <> " . ANONYMOUS . ")
	" . $sql_level . "
	ORDER BY u.user_posts DESC
	LIMIT " . $user_limit;
	if (!($result = $db->sql_query($sql, false, 'top_posters_')))
	{
		message_die(GENERAL_ERROR, 'Could not query forum top poster information', '', __LINE__, __FILE__, $SQL);
	}
	$top_posters = '';
	while($row = $db->sql_fetchrow($result))
	{
		$top_posters .= ' ' . colorize_username($row['user_id']) . '(' . $row['user_posts'] . ') ';
	}
	return $top_posters;
}

?>