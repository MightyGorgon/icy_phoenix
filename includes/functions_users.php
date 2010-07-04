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
	global $config, $lang, $images, $userdata;

	$date_format = ($date_format == false) ? $lang['JOINED_DATE_FORMAT'] : $date_format;

	$info_array = array('avatar', 'first_name', 'last_name', 'from', 'posts', 'joined', 'gender', 'flag', 'style', 'age', 'birthday', 'avatar', 'profile_url', 'profile_img', 'profile', 'pm_url', 'pm_img', 'pm', 'search_url', 'search_img', 'search', 'ip_url', 'ip_img', 'ip', 'email_url', 'email_img', 'email', 'www_url', 'www_img', 'www', 'facebook_url', 'facebook_img', 'facebook', 'twitter_url', 'twitter_img', 'twitter', 'aim_url', 'aim_img', 'aim', 'icq_url', 'icq_status_img', 'icq_img', 'icq', 'jabber_url', 'jabber_img', 'jabber', 'msn_url', 'msn_img', 'msn', 'skype_url', 'skype_img', 'skype', 'yahoo_url', 'yahoo_img', 'yahoo', 'online_status_url', 'online_status_class', 'online_status_img', 'online_status');

	// Initialize everything...
	$user_info = array();
	for ($i = 0; $i < sizeof($info_array); $i++)
	{
		$user_info[$info_array[$i]] = '';
	}

	$user_info['from'] = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
	$user_info['joined'] = create_date($date_format, $row['user_regdate'], $config['board_timezone']);
	$user_info['posts'] = ($row['user_posts']) ? $row['user_posts'] : 0;
	$user_info['style'] = ($row['style_name']) ? $row['style_name'] : '';

	$user_info['avatar'] = user_get_avatar($row['user_id'], $row['user_level'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

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
		$user_info['email_url'] = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL .'=' . $row['user_id']) : 'mailto:' . $row['user_email'];
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

	$user_info['profile_url'] = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']);
	$user_info['profile_img'] = '<a href="' . $user_info['profile_url'] . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
	$user_info['profile'] = '<a href="' . $user_info['profile_url'] . '">' . $lang['Read_profile'] . '</a>';

	$user_info['pm_url'] = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $row['user_id']);
	$user_info['pm_img'] = '<a href="' . $user_info['pm_url'] . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
	$user_info['pm'] = '<a href="' . $user_info['pm_url'] . '">' . $lang['Send_private_message'] . '</a>';

	$user_info['search_url'] = append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode($username) . '&amp;showresults=posts');
	$user_info['search_img'] = '<a href="' . $search_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $username) . '" title="' . sprintf($lang['Search_user_posts'], $username) . '" /></a>';
	$user_info['search'] = '<a href="' . $search_url . '">' . sprintf($lang['Search_user_posts'], $username) . '</a>';

	$user_info['www_img'] = !empty($row['user_website']) ? ('<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>') : '';
	$user_info['www'] = !empty($row['user_website']) ? ('<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Visit_website'] . '</a>') : '';
	$user_info['www_url'] = !empty($row['user_website']) ? $row['user_website'] : '';

	$im_links_array = array(
		'chat' => 'id',
		'aim' => 'aim',
		'facebook' => 'facebook',
		'icq' => 'icq',
		'jabber' => 'jabber',
		'msn' => 'msnm',
		'skype' => 'skype',
		'twitter' => 'twitter',
		'yahoo' => 'yim',
	);

	$all_ims = array();
	foreach ($im_links_array as $im_k => $im_v)
	{
		$all_ims[$im_k] = array(
			'plain' => '',
			'img' => '',
			'url' => ''
		);
		if (!empty($row['user_' . $im_v]))
		{
			$all_ims[$im_k] = array(
				'plain' => build_im_link($im_k, $row, false, false, false, false, false),
				'img' => build_im_link($im_k, $row, 'icon_tpl_vt', true, false, false, false),
				'url' => build_im_link($im_k, $row, false, false, true, false, false)
			);
		}
		$user_info[$im_k . '_img'] = $all_ims[$im_k]['img'];
		$user_info[$im_k] = $all_ims[$im_k]['plain'];
		$user_info[$im_k . '_url'] = $all_ims[$im_k]['url'];
	}

	$user_info['icq_status_img'] = (!empty($row['user_icq'])) ? '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&amp;img=5" width="18" height="18" /></a>' : '';

	// ONLINE / OFFLINE - BEGIN
	$user_info['online_status_url'] = append_sid(CMS_PAGE_VIEWONLINE);
	$user_info['online_status_lang'] = $lang['Offline'];
	$user_info['online_status_class'] = 'offline';
	if (isset($row['user_allow_viewonline']) && ($row['user_session_time'] >= (time() - $config['online_time'])))
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
		$b_year = create_date('Y', $time_now, $config['board_timezone']);
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
		'YIM_URL' => $user_info['yahoo_url'],
		'YIM_IMG' => $user_info['yahoo_img'],
		'YIM' => $user_info['yahoo'],
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
* Generate Ranks
*/
function generate_ranks($user_row, $ranks_array)
{
	$user_fields_array = array(
		'user_rank',
		'user_rank2',
		'user_rank3',
		'user_rank4',
		'user_rank5'
	);

	$user_ranks_array = array(
		'rank_01', 'rank_01_img',
		'rank_02', 'rank_02_img',
		'rank_03', 'rank_03_img',
		'rank_04', 'rank_04_img',
		'rank_05', 'rank_05_img',
	);

	$user_ranks = array();

	$is_banned = false;
	$is_guest = false;
	$rank_sw = false;

	for($j = 0; $j < sizeof($user_ranks_array); $j++)
	{
		$user_ranks[$user_ranks_array[$j]] = '';
	}

	if ($user_row['user_id'] == ANONYMOUS)
	{
		$is_guest = true;
	}

	if (!$is_guest && !empty($ranks_array['bannedrow']))
	{
		for($j = 0; $j < sizeof($ranks_array['bannedrow']); $j++)
		{
			if ($ranks_array['bannedrow'][$j]['ban_userid'] == $user_row['user_id'])
			{
				$is_banned = true;
				break;
			}
		}
	}

	for($j = 0; $j < sizeof($ranks_array['ranksrow']); $j++)
	{
		$rank_tmp = $ranks_array['ranksrow'][$j]['rank_title'];
		$rank_img_tmp = ($ranks_array['ranksrow'][$j]['rank_image']) ? '<img src="' . $ranks_array['ranksrow'][$j]['rank_image'] . '" alt="' . $rank_tmp . '" title="' . $rank_tmp . '" />' : '';
		if ($is_guest == true)
		{
			if ($ranks_array['ranksrow'][$j]['rank_special'] == '2')
			{
				$user_ranks['rank_01'] = $rank_tmp;
				$user_ranks['rank_01_img'] = $rank_img_tmp;
			}
		}
		elseif ($is_banned == true)
		{
			if ($ranks_array['ranksrow'][$j]['rank_special'] == '3')
			{
				$user_ranks['rank_01'] = $rank_tmp;
				$user_ranks['rank_01_img'] = $rank_img_tmp;
			}
		}
		else
		{
			$day_diff = intval((time() - $user_row['user_regdate']) / 86400);

			for($k = 0; $k < sizeof($user_fields_array); $k++)
			{
				switch ($ranks_array['ranksrow'][$j]['rank_special'])
				{
					case '1':
						if ($user_row[$user_fields_array[$k]] == $ranks_array['ranksrow'][$j]['rank_id'])
						{
							$rank_sw = true;
						}
						break;
					case '0':
						if (($user_row[$user_fields_array[$k]] == '0') && ($user_row['user_posts'] >= $ranks_array['ranksrow'][$j]['rank_min']))
						{
							$rank_sw = true;
						}
						break;
					case '-1':
						if (($user_row[$user_fields_array[$k]] == '-1') && ($day_diff >= $ranks_array['ranksrow'][$j]['rank_min']))
						{
							$rank_sw = true;
						}
						break;
					default:
						break;
				}

				if ($rank_sw == true)
				{
					$user_ranks[$user_ranks_array[(($k + 1) * 2) - 2]] = $rank_tmp;
					$user_ranks[$user_ranks_array[(($k + 1) * 2) - 1]] = $rank_img_tmp;
					$rank_sw = false;
				}
			}

		}
	}

	return $user_ranks;
}

/*
* Top X Posters
*/
function top_posters($user_limit, $show_admins = true, $show_mods = true, $only_array = false)
{
	global $db;
	$sql_level = ($show_admins && $show_mods) ? '' : ("AND u.user_level IN (" . USER . ($show_mods ? (", " . MOD) : '') . ($show_admins ? (", " . JUNIOR_ADMIN . ", " . ADMIN) : '') . ")");

	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_posts, u.user_avatar, u.user_avatar_type, u.user_allowavatar
	FROM " . USERS_TABLE . " u
	WHERE (u.user_id <> " . ANONYMOUS . ")
	" . $sql_level . "
	ORDER BY u.user_posts DESC
	LIMIT " . $user_limit;
	$result = $db->sql_query($sql, 0, 'posts_top_posters_', POSTS_CACHE_FOLDER);

	$top_posters = '';
	$top_posters_array = array();
	while($row = $db->sql_fetchrow($result))
	{
		$top_posters .= (($top_posters == '') ? '' : ', ') . colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']) . ' (' . $row['user_posts'] . ')';
		$top_posters_array[] = $row;
	}
	$db->sql_freeresult($result);

	$return_value = ($only_array == true) ? $top_posters_array : $top_posters;
	return $return_value;
}

?>