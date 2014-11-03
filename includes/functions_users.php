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
	global $config, $lang, $images, $user;

	$date_format = ($date_format == false) ? $lang['JOINED_DATE_FORMAT'] : $date_format;

	$info_array = array('avatar', 'first_name', 'last_name', 'from', 'posts', 'joined', 'gender', 'flag', 'style', 'age', 'birthday', 'avatar', 'profile_url', 'profile_img', 'profile', 'pm_url', 'pm_img', 'pm', 'search_url', 'search_img', 'search', 'ip_url', 'ip_img', 'ip', 'email_url', 'email_img', 'email', 'www_url', 'www_img', 'www', 'online_status_url', 'online_status_class', 'online_status_img', 'online_status');

	$user_sn_im_array = get_user_sn_im_array();
	foreach ($user_sn_im_array as $k => $v)
	{
		$info_array[] = $k;
		$info_array[] = $k . '_img';
		$info_array[] = $k . '_url';
	}

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

	if (empty($user->data['user_id']) || ($user->data['user_id'] == ANONYMOUS))
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
	elseif (!empty($row['user_allow_viewemail']) || $is_moderator || $user->data['user_level'] == ADMIN)
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

	if (isset($row['ct_last_used_ip']) && ($user->data['user_level'] == ADMIN))
	{
		$user_info['ip_url'] = 'http://www.nic.com/cgi-bin/whois.cgi?query=' . htmlspecialchars(urlencode($row['ct_last_used_ip']));
		$user_info['ip_img'] = '<a href="' . $user_info['ip_url'] . '" target="_blank"><img src="' . $images['icon_ip2'] . '" alt="' . $lang['View_IP'] . ' (' . htmlspecialchars($row['ct_last_used_ip']) . ')" title="' . $lang['View_IP'] . ' (' . htmlspecialchars($row['ct_last_used_ip']) . ')" /></a>';
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

	$user_sn_im_array = get_user_sn_im_array();
	$im_links_array = array();
	foreach ($user_sn_im_array as $k => $v)
	{
		$im_links_array[$k] = $v['alt_name'];
	}
	$im_links_array['chat'] = 'id';

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
	// Start as offline...
	$user_info['online_status_img'] = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
	$user_info['online_status_lang'] = $lang['Offline'];
	$user_info['online_status_class'] = 'offline';
	if ($row['user_session_time'] >= (time() - $config['online_time']))
	{
		if (!empty($row['user_allow_viewonline']))
		{
			$user_info['online_status_img'] = '<a href="' . $user_info['online_status_url'] . '"><img src="' . $images['icon_online2'] . '" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" /></a>';
			$user_info['online_status_lang'] = $lang['Online'];
			$user_info['online_status_class'] = 'online';
		}
		elseif (isset($row['user_allow_viewonline']) && empty($row['user_allow_viewonline']) && (($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $user_id)))
		{
			$user_info['online_status_img'] = '<a href="' . $user_info['online_status_url'] . '"><img src="' . $images['icon_hidden2'] . '" alt="' . $lang['Hidden'] . '" title="' . $lang['Hidden'] . '" /></a>';
			$user_info['online_status_lang'] = $lang['Hidden'];
			$user_info['online_status_class'] = 'hidden';
		}
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
		'rank_01',
		'rank_02',
		'rank_03',
		'rank_04',
		'rank_05',
	);

	$user_ranks = array();

	$is_banned = false;
	$is_guest = false;
	$rank_sw = false;

	for($j = 0; $j < sizeof($user_ranks_array); $j++)
	{
		$user_ranks[$user_ranks_array[$j]] = '';
		$user_ranks[$user_ranks_array[$j] . '_img'] = '';
		$user_ranks[$user_ranks_array[$j] . '_html'] = '';
		$user_ranks[$user_ranks_array[$j] . '_img_html'] = '';
	}

	if ($user_row['user_id'] == ANONYMOUS)
	{
		$is_guest = true;
	}

	if (!$is_guest && !empty($ranks_array['bannedrow']))
	{
		$is_banned = (isset($ranks_array['bannedrow'][$user_row['user_id']])) ? true : false;
	}

	foreach ($ranks_array['ranksrow'] as $rank_key => $rank_data)
	{
		$rank_tmp = $rank_data['rank_title'];
		$rank_img_tmp = ($rank_data['rank_image']) ? '<img src="' . $rank_data['rank_image'] . '" alt="' . $rank_tmp . '" title="' . $rank_tmp . '" />' : '';
		$rank_tmp = (empty($rank_data['rank_show_title']) && !empty($rank_img_tmp)) ? '' : $rank_tmp;
		if (!empty($is_guest))
		{
			if ($rank_data['rank_special'] == '2')
			{
				$user_ranks['rank_01'] = $rank_tmp;
				$user_ranks['rank_01_img'] = $rank_img_tmp;
				$user_ranks['rank_01_html'] = !empty($rank_tmp) ? ($rank_tmp . '<br />') : '';
				$user_ranks['rank_01_img_html'] = !empty($rank_img_tmp) ? ($rank_img_tmp . '<br />') : '';
				break;
			}
		}
		elseif (!empty($is_banned))
		{
			if ($rank_data['rank_special'] == '3')
			{
				$user_ranks['rank_01'] = $rank_tmp;
				$user_ranks['rank_01_img'] = $rank_img_tmp;
				$user_ranks['rank_01_html'] = !empty($rank_tmp) ? ($rank_tmp . '<br />') : '';
				$user_ranks['rank_01_img_html'] = !empty($rank_img_tmp) ? ($rank_img_tmp . '<br />') : '';
				break;
			}
		}
		else
		{
			$day_diff = intval((time() - $user_row['user_regdate']) / 86400);

			for($k = 0; $k < sizeof($user_fields_array); $k++)
			{
				switch ($rank_data['rank_special'])
				{
					case '1':
						if ($user_row[$user_fields_array[$k]] == $rank_data['rank_id'])
						{
							$rank_sw = true;
						}
						break;
					case '0':
						if (($user_row[$user_fields_array[$k]] == '0') && ($user_row['user_posts'] >= $rank_data['rank_min']))
						{
							$rank_sw = true;
						}
						break;
					case '-1':
						if (($user_row[$user_fields_array[$k]] == '-1') && ($day_diff >= $rank_data['rank_min']))
						{
							$rank_sw = true;
						}
						break;
					default:
						break;
				}

				if (!empty($rank_sw))
				{
					$user_ranks[$user_ranks_array[$k]] = $rank_tmp;
					$user_ranks[$user_ranks_array[$k] . '_img'] = $rank_img_tmp;
					$user_ranks[$user_ranks_array[$k] . '_html'] = !empty($rank_tmp) ? ($rank_tmp . '<br />') : '';
					$user_ranks[$user_ranks_array[$k] . '_img_html'] = !empty($rank_img_tmp) ? ($rank_img_tmp . '<br />') : '';
					$rank_sw = false;
				}
			}

		}
	}

	return $user_ranks;
}

/**
* Updates a username across all relevant tables/fields
*
* @param string $old_name the old/current username
* @param string $new_name the new username
*/
function user_update_name($old_name, $new_name)
{
	global $config, $db, $cache;

	$update_ary = array(
		FORUMS_TABLE => array('forum_last_poster_name'),
		MODERATOR_CACHE_TABLE => array('username'),
		POSTS_TABLE => array('post_username'),
		TOPICS_TABLE => array('topic_first_poster_name', 'topic_last_poster_name'),
	);

	foreach ($update_ary as $table => $field_ary)
	{
		foreach ($field_ary as $field)
		{
			$sql = "UPDATE $table
				SET $field = '" . $db->sql_escape($new_name) . "'
				WHERE $field = '" . $db->sql_escape($old_name) . "'";
			$db->sql_query($sql);
		}
	}

	if ($config['newest_username'] == $old_name)
	{
		set_config('newest_username', $new_name, true);
	}

	// Because some tables/caches use username-specific data we need to purge this here.
	$cache->destroy('sql', MODERATOR_CACHE_TABLE);
}

/*
* Fake User Profile
*/
function user_profile_mask(&$user_data)
{
	global $config, $lang;

	$user_data['user_id'] = ANONYMOUS;
	$user_data['username'] = $lang['INACTIVE_USER'];
	$user_data['user_first_name'] = '';
	$user_data['user_last_name'] = '';
	$user_data['post_username'] = $user_data['username'];
	$user_data['user_color'] = '';
	$user_data['user_level'] = USER;
	$user_data['user_regdate'] = $config['board_startdate'];
	$user_data['user_from'] = '';
	$user_data['user_from_flag'] = '';
	$user_data['user_birthday'] = 999999;
	$user_data['user_posts'] = 0;
	$user_data['user_personal_pics_count'] = 0;
	$user_data['user_avatar'] = '';
	$user_data['user_avatar_type'] = 0;
	$user_data['user_allowavatar'] = 0;
	$user_data['user_lang'] = $config['default_lang'];
	$user_data['user_style'] = $config['default_style'];
	$user_data['user_rank'] = '-2';
	$user_data['user_rank_2'] = '-2';
	$user_data['user_rank_3'] = '-2';
	$user_data['user_rank_4'] = '-2';
	$user_data['user_rank_5'] = '-2';
	$user_data['user_allow_viewemail'] = 0;
	$user_data['user_website'] = '';
	$user_data['user_gender'] = 0;
	$user_data['user_allow_viewonline'] = 0;
	$user_data['user_session_time'] = 0;
	$user_data['poster_ip'] = '';
	$user_data['user_warnings'] = 0;
	$user_data['user_sig'] = '';

	$user_sn_im_array = get_user_sn_im_array();
	foreach ($user_sn_im_array as $k => $v)
	{
		$user_data[$v['field']] = '';
	}

	return true;
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
		AND u.user_active = 1
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

/**
* Sends a birthday PM
*/
function birthday_pm_send()
{
	global $db, $cache, $config, $user, $lang;

	// Birthday - BEGIN
	// Check if the user has or have had birthday, also see if greetings are enabled
	if (($user->data['user_birthday'] != 999999) && !empty($config['birthday_greeting']) && (create_date('Ymd', time(), $config['board_timezone']) >= $user->data['user_next_birthday_greeting'] . realdate('md', $user->data['user_birthday'])))
	{
		// If a user had a birthday more than one week before we will not send the PM...
		if ((time() - gmmktime(0, 0, 0, $user->data['user_birthday_m'], $user->data['user_birthday_d'], $user->data['user_next_birthday_greeting'])) <= (86400 * 8))
		{
			// Birthday PM - BEGIN
			$pm_subject = $lang['Greeting_Messaging'];
			$pm_date = gmdate('U');

			$year = create_date('Y', time(), $config['board_timezone']);
			$date_today = create_date('Ymd', time(), $config['board_timezone']);
			$user_birthday = realdate('md', $user->data['user_birthday']);
			$user_birthday2 = (($year . $user_birthday < $date_today) ? ($year + 1) : $year) . $user_birthday;

			$user_age = create_date('Y', time(), $config['board_timezone']) - realdate('Y', $user->data['user_birthday']);
			if (create_date('md', time(), $config['board_timezone']) < realdate('md', $user->data['user_birthday']))
			{
				$user_age--;
			}

			$pm_text = ($user_birthday2 == $date_today) ? sprintf($lang['Birthday_greeting_today'], $user_age) : sprintf($lang['Birthday_greeting_prev'], $user_age, realdate(str_replace('Y', '', $lang['DATE_FORMAT_BIRTHDAY']), $user->data['user_birthday']) . ((!empty($user->data['user_next_birthday_greeting']) ? ($user->data['user_next_birthday_greeting']) : '')));

			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());

			include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);
			$privmsg_subject = sprintf($pm_subject, $config['sitename']);
			$privmsg_message = sprintf($pm_text, $config['sitename'], $config['sitename']);
			$privmsg_sender = $founder_id;
			$privmsg_recipient = $user->data['user_id'];

			$privmsg = new class_pm();
			$privmsg->delete_older_message('PM_INBOX', $privmsg_recipient);
			$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message);
			unset($privmsg);
			// Birthday PM - END
		}

		// Update next greetings year
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_next_birthday_greeting = " . (create_date('Y', time(), $config['board_timezone']) + 1) . "
			WHERE user_id = " . $user->data['user_id'];
		$status = $db->sql_query($sql);
	} //Sorry user shall not have a greeting this year
	// Birthday - END

}

/**
* Sends a birthday Email
*/
function birthday_email_send()
{
	global $db, $cache, $config, $lang;

	if (!class_exists('emailer'))
	{
		@include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
	}
	$server_url = create_server_url();

	$birthdays_list = get_birthdays_list_email();
	foreach ($birthdays_list as $k => $v)
	{
		// Birthday - BEGIN
		// Check if the user has or have had birthday, also see if greetings are enabled
		if (!empty($config['birthday_greeting']))
		{
			// Birthday Email - BEGIN
			setup_extra_lang(array('lang_cron_vars'), '', $v['user_lang']);

			$year = create_date('Y', time(), $v['user_timezone']);
			$date_today = create_date('Ymd', time(), $v['user_timezone']);
			$user_birthday = realdate('md', $v['user_birthday']);
			$user_birthday2 = (($year . $user_birthday < $date_today) ? ($year + 1) : $year) . $user_birthday;

			$user_age = create_date('Y', time(), $v['user_timezone']) - realdate('Y', $v['user_birthday']);
			if (create_date('md', time(), $v['user_timezone']) < realdate('md', $v['user_birthday']))
			{
				$user_age--;
			}

			$email_subject = sprintf($lang['BIRTHDAY_GREETING_EMAIL_SUBJECT'], $config['sitename']);
			//$email_text = sprintf($lang['BIRTHDAY_GREETING_EMAIL_CONTENT_AGE'], $user_age);
			$email_text = sprintf($lang['BIRTHDAY_GREETING_EMAIL_CONTENT'], $config['sitename']);

			// Send the email!
			$emailer = new emailer();

			$emailer->use_template('birthday_greeting', $v['user_lang']);
			$emailer->to($v['user_email']);

			// If for some reason the mail template subject cannot be read... note it will not necessarily be in the posters own language!
			$emailer->set_subject($email_subject);

			$v['username'] = !empty($v['user_first_name']) ? $v['user_first_name'] : $v['username'];

			// This is a nasty kludge to remove the username var ... till (if?) translators update their templates
			$emailer->msg = preg_replace('#[ ]?{USERNAME}#', $v['username'], $emailer->msg);

			$email_sig = create_signature($config['board_email_sig']);
			$emailer->assign_vars(array(
				'USERNAME' => !empty($config['html_email']) ? htmlspecialchars($v['username']) : $v['username'],
				'USER_AGE' => $user_age,
				'EMAIL_SIG' => $email_sig,
				'SITENAME' => $config['sitename'],
				'SITE_URL' => $server_url
				)
			);

			$emailer->send();
			$emailer->reset();
			// Birthday Email - END

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_next_birthday_greeting = " . (create_date('Y', time(), $v['user_timezone']) + 1) . "
				WHERE user_id = " . $v['user_id'];
			$status = $db->sql_query($sql);
		}
		// Birthday - END
	}
	// We reset the lang again for default lang...
	setup_extra_lang(array('lang_cron_vars'));
}

/**
* Get the birthdays list to send greetings email.
*/
function get_birthdays_list_email()
{
	global $db, $cache;

	// Since the highest timezone is +12, we start twelve hours later... we also need to keep into account that -12 and +12 have one day delay!
	$time_now = time();
	$time_now_12 = $time_now + (60 * 60 * 12);
	$b_h = gmdate('G', $time_now);
	$timezone_delta = ($b_h == 0) ? 0 : (($b_h < 12) ? -$bh : (24 - $b_h));
	$b_y = gmdate('Y', $time_now_12);
	$b_m = gmdate('n', $time_now_12);
	$b_d = gmdate('j', $time_now_12);

	$sql_where = ' ((u.user_birthday_y <= ' . $b_y . ') AND (u.user_birthday_m = ' . $b_m . ') AND (u.user_birthday_d = ' . $b_d . ')) ';

	if ((gmdate('L', $time_now_12) == 0) && ($b_m == 3) && ($b_d == 1))
	{
		$sql_where .= ' OR ((u.user_birthday_y <= ' . $b_y . ') AND (u.user_birthday_m = 2) AND (u.user_birthday_d = 29)) ';
	}

	$sql_timezone = '(user_timezone LIKE "' . $timezone_delta . '.%")';
	if ($timezone_delta == 12)
	{
		$sql_timezone = ' AND (' . $sql_timezone . ' OR (user_timezone LIKE "-' . $timezone_delta . '.%")) ';
	}
	else
	{
		$sql_timezone = ' AND ' . $sql_timezone . ' ';
	}

	$sql_where = ' AND (u.user_birthday <> 999999) AND (user_active = 1) AND (user_allow_mass_email = 1) ' . $sql_timezone . ' AND (' . $sql_where . ')';

	// Changed sorting by username_clean instead of username
	$sql = "SELECT u.user_id, u.username, u.user_first_name, u.user_active, u.user_color, u.user_email, u.user_timezone, u.user_lang, u.user_birthday, u.user_birthday_y, u.user_birthday_m, u.user_birthday_d, u.user_next_birthday_greeting
				FROM " . USERS_TABLE . " AS u
				WHERE u.user_id <> " . ANONYMOUS . "
				" . $sql_where . "
				ORDER BY username_clean";
	$result = $db->sql_query($sql);
	$birthdays_list = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	return $birthdays_list;
}

?>