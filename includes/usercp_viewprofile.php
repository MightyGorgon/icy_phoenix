<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

$show_extra_stats = request_get_var('stats', 0);
$target_user_id = request_get_var(POST_USERS_URL, ANONYMOUS);

if (empty($target_user_id) || ($target_user_id == ANONYMOUS))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_USER');
}

$profiledata = get_userdata($target_user_id);
if (empty($profiledata) || empty($profiledata['user_id']))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_USER');
}

// We force the user to be active to show its profile... or we require the viewer to be admin!
if (empty($profiledata['user_active']) && ($user->data['user_level'] != ADMIN))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_USER');
}

// GROUPS - BEGIN
$add_rem_result = false;

$ug_action_id = request_var(POST_GROUPS_URL, 0);

if (($user->data['user_level'] == ADMIN) && isset($_POST['ug_add']) && !empty($ug_action_id))
{
	if (!function_exists('group_user_add'))
	{
		include(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
	}
	$ug_add_result = group_user_add($ug_action_id, $profiledata['user_id'], false);

	if ($ug_add_result === 1)
	{
		$message = $lang['GROUP_ADDED_USER'];
		$add_rem_result = true;
	}
}

if (($user->data['user_level'] == ADMIN) && !empty($ug_action_id) && (isset($_GET['ug_rem']) || isset($_POST['ug_rem'])))
{
	if (isset($_POST['confirm']))
	{
		if (!function_exists('group_user_rem'))
		{
			include(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
		}
		$ug_rem_result = group_user_rem($ug_action_id, $profiledata['user_id'], false);

		if ($ug_rem_result === 1)
		{
			$message = $lang['GROUP_REMOVED_USER'];
			$add_rem_result = true;
		}
	}
	else
	{
		$s_hidden_fields = build_hidden_fields(array(
			'sid' => $user->data['session_id'],
			'ug_rem' => 1,
			POST_GROUPS_URL => (int) $ug_action_id,
			)
		);

		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_GROUP_CP) . '" class="nav-current">' . $lang['Group_Control_Panel'] . '</a>';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['CONFIRM_UNSUB_USER'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'S_CONFIRM_ACTION' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
		full_page_generation('confirm_body.tpl', $lang['Group_Control_Panel'], '', '');
	}
}

if (!empty($add_rem_result))
{
		empty_cache_folders(USERS_CACHE_FOLDER);

		$redirect_url = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']);
		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $message);
}
// GROUPS - END

include_once(IP_ROOT_PATH . 'includes/functions_zebra.' . PHP_EXT);

if (!empty($user->data['session_logged_in']) && ($profiledata['user_id'] != $user->data['user_id']))
{
	$zmode = request_var('zmode', '');
	if (!empty($zmode))
	{
		// Allow only friends...
		//$zmode_types = array('friend', 'foe');
		$zmode_types = array('friend');
		$zmode = (!in_array($zmode, $zmode_types) ? '' : $zmode);
	}

	if (!empty($zmode))
	{
		$zaction = request_var('zaction', '');
		$zaction_types = array('add', 'remove');
		$zaction = (!in_array($zaction, $zaction_types) ? '' : $zaction);

		if (!empty($zaction) && ($zaction == 'add'))
		{
			user_friend_foe_add(array($profiledata['user_id']), true);
		}
		elseif (!empty($zaction) && ($zaction == 'remove'))
		{
			user_friend_foe_remove(array($profiledata['user_id']), true);
		}
	}
}

// Update the profile view list
$target_user = $profiledata['user_id'];
$viewer = $user->data['username'];
$viewer_id = $user->data['user_id'];
$current_time = time();
if ($target_user != $viewer_id)
{
	$sql = "UPDATE " . USERS_TABLE . "
			SET user_profile_view = '1'
			WHERE user_id = " . $target_user;
	$db->sql_query($sql);

	$sql = "SELECT * FROM " . PROFILE_VIEW_TABLE . "
		WHERE user_id = " . $target_user . "
		AND viewer_id = " . $viewer_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		if (!$row = $db->sql_fetchrow($result))
		$sql = "INSERT INTO " . PROFILE_VIEW_TABLE . "
			(user_id, viewername, viewer_id, view_stamp, counter)
			VALUES ('" . $db->sql_escape($target_user) . "', '" . $db->sql_escape($viewer) . "', '" . $db->sql_escape($viewer_id) . "', '" . $db->sql_escape($current_time) . "', '1')";
		$db->sql_query($sql);
		$count = $row['counter'] + 1;

		$sql = "UPDATE " . PROFILE_VIEW_TABLE . "
				SET view_stamp = '$current_time', counter = '$count'
				WHERE user_id = " . $target_user. "
				AND viewer_id = " . $viewer_id;
		$db->sql_query($sql);
	}
}

// Mighty Gorgon - Multiple Ranks - BEGIN
@include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
$ranks_array = $cache->obtain_ranks(false);
// Mighty Gorgon - Multiple Ranks - END

make_jumpbox(CMS_PAGE_VIEWFORUM);

//
// Calculate the number of days this user has been a member ($memberdays)
// Then calculate their posts per day
//
$regdate = $profiledata['user_regdate'];
$memberdays = max(1, round((time() - $regdate) / 86400));
$posts_per_day = $profiledata['user_posts'] / $memberdays;

// Get the users percentage of total posts
if ($profiledata['user_posts'] != 0)
{
	$total_posts = $config['max_posts'];
	$percentage = ($total_posts) ? min(100, ($profiledata['user_posts'] / $total_posts) * 100) : 0;
}
else
{
	$percentage = 0;
}

// Mighty Gorgon - Thanks Received - BEGIN
$total_thanks_received = 0;
if ($config['show_thanks_profile'] && empty($config['disable_likes_posts']))
{
	$total_thanks_received = user_get_thanks_received($profiledata['user_id']);
	$template->assign_block_vars('show_thanks_profile', array());
}
// Mighty Gorgon - Thanks Received - END

// Mighty Gorgon - HTTP AGENTS - BEGIN
include(IP_ROOT_PATH . 'includes/functions_mg_http.' . PHP_EXT);
$user_os = get_user_os($profiledata['user_browser']);
$user_browser = get_user_browser($profiledata['user_browser']);
// Mighty Gorgon - HTTP AGENTS - END

// Mighty Gorgon - Full Album Pack - BEGIN
if (!empty($config['plugins']['album']['enabled']))
{
	include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['album']['dir'] . 'common.' . PHP_EXT);
	include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['album']['dir'] . 'includes/album_functions_profile.' . PHP_EXT);
	album_profile_last_pictures($profiledata);
}
// Mighty Gorgon - Full Album Pack - END

$avatar_img = user_get_avatar($profiledata['user_id'], $profiledata['user_level'], $profiledata['user_avatar'], $profiledata['user_avatar_type'], $profiledata['user_allowavatar']);

// Mighty Gorgon - Multiple Ranks - BEGIN
$user_ranks = generate_ranks($profiledata, $ranks_array);
// Mighty Gorgon - Multiple Ranks - END

// ONLINE OFFLINE - BEGIN
$user_online_status = 'offline';
if ($profiledata['user_session_time'] >= (time() - $config['online_time']))
{
	if ($profiledata['user_allow_viewonline'])
	{
		$user_online_status = 'online';
		$online_status_img = '<a href="' . append_sid(CMS_PAGE_VIEWONLINE) . '"><img src="' . $images['icon_online'] . '" alt="' . htmlspecialchars(sprintf($lang['is_online'], $profiledata['username'])) . '" title="' . htmlspecialchars(sprintf($lang['is_online'], $profiledata['username'])) . '" /></a>';
	}
	elseif (($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $profiledata['user_id']))
	{
		$user_online_status = 'hidden';
		$online_status_img = '<a href="' . append_sid(CMS_PAGE_VIEWONLINE) . '"><img src="' . $images['icon_hidden'] . '" alt="' . htmlspecialchars(sprintf($lang['is_hidden'], $profiledata['username'])) . '" title="' . htmlspecialchars(sprintf($lang['is_hidden'], $profiledata['username'])) . '" /></a>';
	}
	else
	{
		$user_online_status = 'offline';
		$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . htmlspecialchars(sprintf($lang['is_offline'], $profiledata['username'])) . '" title="' . htmlspecialchars(sprintf($lang['is_offline'], $profiledata['username'])) . '" />';
	}
}
else
{
	$user_online_status = 'offline';
	$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . htmlspecialchars(sprintf($lang['is_offline'], $profiledata['username'])) . '" title="' . htmlspecialchars(sprintf($lang['is_offline'], $profiledata['username'])) . '" />';
}
// ONLINE OFFLINE - END

$pm_url = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']);
$pm_img = '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
$pm = '<a href="' . $pm_url . '">' . $lang['Send_private_message'] . '</a>';

$email_url = '';
if (empty($user->data['user_id']) || ($user->data['user_id'] == ANONYMOUS))
{
	if (!empty($profiledata['user_allow_viewemail']))
	{
		$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Hidden_email'] . '" title="' . $lang['Hidden_email'] . '" />';
	}
	else
	{
		$email_img = '&nbsp;';
	}
	$email = '&nbsp;';
}
elseif (!empty($profiledata['user_allow_viewemail']) || $user->data['user_level'] == ADMIN)
{
	$email_url = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL .'=' . $profiledata['user_id']) : 'mailto:' . $profiledata['user_email'];
	$email_img = '<a href="' . $email_url . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
	$email = '<a href="' . $email_url . '">' . $lang['Send_email'] . '</a>';
}
else
{
	$email_img = '&nbsp;';
	$email = '&nbsp;';
}

$www_url = ($profiledata['user_website']) ? $profiledata['user_website'] : '';
$www_img = ($profiledata['user_website']) ? '<a href="' . $profiledata['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '&nbsp;';
$www = ($profiledata['user_website']) ? '<a href="' . $profiledata['user_website'] . '" target="_blank">' . $profiledata['user_website'] . '</a>' : '&nbsp;';

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
		'plain' => '&nbsp;',
		'icon' => '',
		'img' => '&nbsp;',
		'url' => ''
	);
	if (!empty($profiledata['user_' . $im_v]))
	{
		$all_ims[$im_k] = array(
			'plain' => build_im_link($im_k, $profiledata, false, false, false, false, false),
			'icon' => build_im_link($im_k, $profiledata, 'icon', true, false, $user_online_status, false),
			'img' => build_im_link($im_k, $profiledata, 'icon_tpl', true, false, false, false),
			'url' => build_im_link($im_k, $profiledata, false, false, true, false, false)
		);
	}
}

$aim_img = $all_ims['aim']['img'];
$aim = $all_ims['aim']['plain'];
$aim_url = $all_ims['aim']['url'];

$icq_status_img = (!empty($profiledata['user_icq'])) ? '<a href="http://wwp.icq.com/' . $profiledata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $profiledata['user_icq'] . '&amp;img=5" width="18" height="18" /></a>' : '&nbsp;';
$icq_img = $all_ims['icq']['img'];
$icq = $all_ims['icq']['plain'];
$icq_url = $all_ims['icq']['url'];

$msn_img = $all_ims['msn']['img'];
$msn = $all_ims['msn']['plain'];
$msn = $msn_img;
$msn_url = $all_ims['msn']['url'];

$skype_img = $all_ims['skype']['img'];
$skype = $all_ims['skype']['plain'];
$skype_url = $all_ims['skype']['url'];

$yahoo_img = $all_ims['yahoo']['img'];
$yahoo = $all_ims['yahoo']['plain'];
$yahoo_url = $all_ims['yahoo']['url'];

$temp_url = append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode($profiledata['username']) . '&amp;showresults=posts');
$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" title="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" /></a>';
$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '</a>';
// Start Advanced IP Tools Pack MOD
$encoded_ip = ($profiledata['user_registered_ip'] == '') ? '' : $profiledata['user_registered_ip'];
$decoded_ip = ($encoded_ip == '0.0.0.0') ? $lang['Not_recorded'] : $encoded_ip;
$hostname = ($profiledata['user_registered_hostname'] == '') ? $lang['Not_recorded'] : htmlspecialchars($profiledata['user_registered_hostname']);
// End Advanced IP Tools Pack MOD

// BBCode - BEGIN
@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
$bbcode->allow_html = $config['allow_html'];
$bbcode->allow_bbcode = $config['allow_bbcode'];
if ($config['allow_smilies'] && $profiledata['user_allowsmile'] && !$lofi)
{
	$bbcode->allow_smilies = $config['allow_smilies'];
}
else
{
	$bbcode->allow_smilies = false;
}
// BBCode - END

$user_sig = '';
if ($profiledata['user_attachsig'] && $config['allow_sig'])
{
	$user_sig = $profiledata['user_sig'];
	if ($user_sig != '')
	{
		$user_sig = censor_text($user_sig);
		$bbcode->is_sig = true;
		$user_sig = $bbcode->parse($user_sig);
		$bbcode->is_sig = false;
	}
	//$template->assign_block_vars('switch_user_sig_block', array());
}

$user_sig = ($user_sig == '') ? '&nbsp;' : $user_sig;

$selfdes = $profiledata['user_selfdes'];
$selfdes = censor_text($selfdes);

if ($selfdes == '')
{
	$selfdes = $lang['UserNoInfo'];
}
else
{
	//$bbcode->is_sig = true;
	$selfdes = $bbcode->parse($selfdes);
	//$bbcode->is_sig = false;
}

if ($user_sig != '')
{
	$selfdes = $selfdes . '<br /><br /><hr />' . $user_sig;
}


if (!empty($profiledata['user_id']))
{
	$user_most_active = get_forum_most_active($profiledata['user_id']);
	$user_most_active_forum_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . urlencode($user_most_active['forum_id']));
	$user_most_active_forum_id = $user_most_active['forum_id'];
	$user_most_active_forum_name = $user_most_active['forum_name'];
	$user_most_active_posts = $user_most_active['posts'];
}

// BIRTHDAY - BEGIN
if ($profiledata['user_birthday'] != 999999)
{
	$user_birthday = realdate($lang['DATE_FORMAT_BIRTHDAY'], $profiledata['user_birthday']);
}
else
{
	$user_birthday = $lang['No_birthday_specify'];
}
// BIRTHDAY - END


// Start add - Gender MOD
if (!empty($profiledata['user_gender']))
{
	switch ($profiledata['user_gender'])
	{
		case 1: $gender = $lang['Male']; break;
		case 2: $gender = $lang['Female']; break;
		default: $gender = $lang['No_gender_specify'];
	}
}
else
{
	$gender = $lang['No_gender_specify'];
}
// End add - Gender MOD

$location = ($profiledata['user_from']) ? $profiledata['user_from'] : '&nbsp;' ;
$flag = (!empty($profiledata['user_from_flag'])) ? '<img src="images/flags/' . $profiledata['user_from_flag'] . '" alt="' . $profiledata['user_from_flag'] . '" title="' . $profiledata['user_from_flag'] . '" />' : '';
$location .= '&nbsp;' . $flag ;

// Activity - BEGIN
if (!empty($config['plugins']['activity']['enabled']) && !empty($user->data['session_logged_in']))
{
	include_once(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);
	unset($trophy_count, $trophy_holder, $trophy);
	if (($config['ina_show_view_profile']) && ($profiledata['user_trophies'] > '0') && ($profiledata['user_id'] != ANONYMOUS))
	{
		$template->assign_block_vars('trophy', array(
			'PROFILE_TROPHY' => '<a href="javascript:popup_open(\'' . IP_ROOT_PATH . 'activity_trophy_popup.' . PHP_EXT . '?user=' . $profiledata['user_id'] . '&amp;sid=' . $user->data['session_id'] . '\',\'New_Window\',\'400\',\'380\',\'yes\')" onclick="blur()">' . $lang['Trohpy'] . '</a>:&nbsp;&nbsp;' . $profiledata['user_trophies'],
			'TROPHY_TITLE' => $lang['Trohpy']
			)
		);
	}

	$template->assign_vars(array(
		'PROFILE_TIME' => DisplayPlayingTime(2, $profiledata['ina_time_playing']),
		'PROFILE_TITLE' => $lang['profile_game_time']
		)
	);

	if (($config['ina_char_show_viewprofile']) && ($profiledata['ina_char_name']) && ($profile_data['user_id'] != ANONYMOUS))
	{
		$template->assign_block_vars('profile_char', array(
			'CHAR_PROFILE' => AMP_Profile_Char($profiledata['user_id'], '')
			)
		);
	}

	$poster_rank .= Amod_Trophy_King_Image($profiledata['user_id']);
}
// Activity - END

$u_search_author = urlencode(strtr($profiledata['username'], array_flip(get_html_translation_table(HTML_ENTITIES))));

// Generate page
$link_name = htmlspecialchars($profiledata['username']);
$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
$breadcrumbs['bottom_right_links'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_author=' . $u_search_author . '&amp;search_topic_starter=1&amp;show_results=topics') . '">' . htmlspecialchars(sprintf($lang['Search_user_topics_started'], $profiledata['username'])) . '</a>&nbsp;&bull;&nbsp;<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_author=' . $u_search_author) . '">' . htmlspecialchars(sprintf($lang['Search_user_posts'], $profiledata['username'])) . '</a><br /><a href="' . append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id']) . '">' . htmlspecialchars(sprintf($lang['Personal_Gallery_Of_User_Profile'], $profiledata['username'], $totalpicrow)) . '</a>&nbsp;&bull;&nbsp;<a href="' . append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id'] . '&amp;mode=' . ALBUM_VIEW_LIST) . '">' . sprintf($lang['Picture_List_Of_User'], $profiledata['username']) . '</a>';

display_upload_attach_box_limits($profiledata['user_id']);

// Mighty Gorgon - Feedback - BEGIN
$feedback_received = '';
if (!empty($config['plugins']['feedback']['enabled']) && !empty($config['plugins']['feedback']['dir']))
{
	$plugin_name = 'feedback';
	include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['feedback']['dir'] . 'common.' . PHP_EXT);
	$feedback_details = get_user_feedback_received($profiledata['user_id']);
	if ($feedback_details['feedback_count'] > 0)
	{
		$feedback_average = (($feedback_details['feedback_count'] > 0) ? (round($feedback_details['feedback_sum'] / $feedback_details['feedback_count'], 1)) : 0);
		$feedback_average_img = IP_ROOT_PATH . 'images/feedback/' . build_feedback_rating_image($feedback_average);
		$feedback_received = (($feedback_details['feedback_count'] > 0) ? ('[ <a href="' . append_sid(PLUGINS_FEEDBACK_FILE . '?' . POST_USERS_URL . '=' . $profiledata['user_id']) . '">' . $feedback_details['feedback_count'] . '</a> ]&nbsp;&nbsp;<img src="' . $feedback_average_img . '" style="vertical-align: middle;" alt="' . $feedback_average . '" title="' . $feedback_average . '" />') : '');
	}
}
// Mighty Gorgon - Feedback - END

$user_sn_im_array = get_user_sn_im_array();
foreach ($user_sn_im_array as $k => $v)
{
	$template->assign_var('ICON_' . strtoupper($k), $all_ims[$k]['icon']);
}

$is_friend = user_check_friend_foe($profiledata['user_id'], true);
$template->assign_vars(array(
	// Mighty Gorgon - Feedback - BEGIN
	'FEEDBACK' => $feedback_received,
	// Mighty Gorgon - Feedback - END
	'USERNAME' => $profiledata['username'],
	'JOINED' => create_date($lang['JOINED_DATE_FORMAT'], $profiledata['user_regdate'], $config['board_timezone']),

	'SHOW_FRIEND_LINK' => ($profiledata['user_id'] != $user->data['user_id']) ? true : false,
	'IS_FRIEND' => !empty($is_friend) ? true : false,
	'U_FRIEND_ADD_REMOVE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;zmode=friend&amp;zaction=' . (!empty($is_friend) ? ('remove') : ('add'))),

	// Start add - Last visit MOD
	'L_LOGON' => $lang['Last_logon'],
	'LAST_LOGON' => (($user->data['user_level'] == ADMIN) || (!$config['hidde_last_logon'] && $profiledata['user_allow_viewonline'])) ? (($profiledata['user_lastvisit'])? create_date($config['default_dateformat'], $profiledata['user_lastvisit'], $config['board_timezone']):$lang['Never_last_logon']):$lang['Hidde_last_logon'],
	'L_TOTAL_ONLINE_TIME' => $lang['Total_online_time'],
	'TOTAL_ONLINE_TIME' => make_hours($profiledata['user_totaltime']),
	'L_LAST_ONLINE_TIME' => $lang['Last_online_time'],
	'LAST_ONLINE_TIME' => make_hours($profiledata['user_session_time'] - $profiledata['user_lastvisit']),
	'L_NUMBER_OF_VISIT' => $lang['Number_of_visit'],
	'NUMBER_OF_VISIT' => ($profiledata['user_totallogon'] > 0) ? $profiledata['user_totallogon'] : $lang['None'],
	'L_NUMBER_OF_PAGES' => $lang['Number_of_pages'],
	'NUMBER_OF_PAGES' => ($profiledata['user_totalpages']) ? $profiledata['user_totalpages'] : $lang['None'],
	// End add - Last visit MOD

	// Mighty Gorgon - Multiple Ranks - BEGIN
	'USER_RANK_01' => $user_ranks['rank_01_html'],
	'USER_RANK_01_IMG' => $user_ranks['rank_01_img_html'],
	'USER_RANK_02' => $user_ranks['rank_02_html'],
	'USER_RANK_02_IMG' => $user_ranks['rank_02_img_html'],
	'USER_RANK_03' => $user_ranks['rank_03_html'],
	'USER_RANK_03_IMG' => $user_ranks['rank_03_img_html'],
	'USER_RANK_04' => $user_ranks['rank_04_html'],
	'USER_RANK_04_IMG' => $user_ranks['rank_04_img_html'],
	'USER_RANK_05' => $user_ranks['rank_05_html'],
	'USER_RANK_05_IMG' => $user_ranks['rank_05_img_html'],
	// Mighty Gorgon - Multiple Ranks - END
	'POSTS_PER_DAY' => $posts_per_day,
	'POSTS' => $profiledata['user_posts'],
	'S_POSTS_SECTION' => ($profiledata['user_posts'] > 0) ? true : false,
	'PERCENTAGE' => $percentage . '%',
	'POST_DAY_STATS' => sprintf($lang['User_post_day_stats'], $posts_per_day),
	'POST_PERCENT_STATS' => sprintf($lang['User_post_pct_stats'], $percentage),
	'THANKS_RECEIVED' => (($total_thanks_received > 0) ? ('<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_thanks=' . $profiledata['user_id']) . '">' . $total_thanks_received . '</a>') : $total_thanks_received),
	'INVISION_AVATAR_IMG' => $avatar_img,
	'INVISION_MOST_ACTIVE_FORUM_URL' => $user_most_active_forum_url,
	'INVISION_MOST_ACTIVE_FORUM_ID' => $user_most_active_forum_id,
	'INVISION_MOST_ACTIVE_FORUM_NAME' => $user_most_active_forum_name,
	'INVISION_POST_DAY_STATS' => sprintf($lang['Invision_User_post_day_stats'], $posts_per_day),
	'INVISION_POST_PERCENT_STATS' => sprintf($lang['Invision_User_post_pct_stats'], $percentage),
	'INVISION_USER_SIG' => $user_sig,
	'SEARCH_IMG' => $search_img,
	'SEARCH' => $search,
	'PM_IMG' => $pm_img,
	'PM' => $pm,
	'U_PM' => $pm_url,
	'EMAIL_IMG' => (!$user->data['session_logged_in'])? '' : $email_img,
	'EMAIL' => $email,
	'U_EMAIL' => $email_url,
	'WWW_IMG' => $www_img,
	'WWW' => $www,
	'U_WWW' => $www_url,
	'AIM_IMG' => $aim_img,
	'AIM' => $aim,
	'U_AIM' => $aim_url,
	'ICQ_STATUS_IMG' => $icq_status_img,
	'ICQ_IMG' => $icq_img,
	'ICQ' => $icq,
	'U_ICQ' => $icq_url,
	'MSN_IMG' => $msn_img,
	'MSN' => $msn,
	'U_MSN' => $msn_url,
	'SKYPE_IMG' => $skype_img,
	'SKYPE' => $skype,
	'U_SKYPE' => $skype_url,
	'YIM_IMG' => $yahoo_img,
	'YIM' => $yahoo,
	'U_YIM' => $yahoo_url,

	//'LOCATION' => ($profiledata['user_from']) ? $profiledata['user_from'] : '&nbsp;',
	'LOCATION' => $location,
	'USER_FIRST_NAME' => ($profiledata['user_first_name']) ? $profiledata['user_first_name'] : '&nbsp;',
	'USER_LAST_NAME' => ($profiledata['user_last_name']) ? $profiledata['user_last_name'] : '&nbsp;',
	'OCCUPATION' => ($profiledata['user_occ']) ? $profiledata['user_occ'] : '&nbsp;',
	'INTERESTS' => ($profiledata['user_interests']) ? $profiledata['user_interests'] : '&nbsp;',

	'PHONE' => ($profiledata['user_phone']) ? $profiledata['user_phone'] : '&nbsp;',
	'SELFDES' => $selfdes,

	'U_PROFILE_VISITS' => append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;' . POST_POST_URL . '=0'),
	'U_VISITS' => '<a href="' . append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;' . POST_POST_URL . '=0') . '"><img src="' . $images['icon_view'] . '" alt="' . $lang['Views'] . '" /></a>',

	// Start add - Gender MOD
	'GENDER' => $gender,
	// End add - Gender MOD

	// BIRTHDAY - BEGIN
	'BIRTHDAY' => $user_birthday,
	// BIRTHDAY - END

	'AVATAR_IMG' => $avatar_img,

	'L_VIEWING_PROFILE' => htmlspecialchars(sprintf($lang['Viewing_user_profile'], $profiledata['username'])),
	'L_ABOUT_USER' => htmlspecialchars(sprintf($lang['About_user'], $profiledata['username'])),
	'L_AVATAR' => $lang['Avatar'],
	'L_POSTER_RANK' => $lang['Poster_rank'],
	'L_JOINED' => $lang['Joined'],
	'L_TOTAL_POSTS' => $lang['Total_posts'],
	'L_SEARCH_USER_POSTS' => htmlspecialchars(sprintf($lang['Search_user_posts'], $profiledata['username'])),
	'L_SEARCH_USER_TOPICS' => htmlspecialchars(sprintf($lang['Search_user_topics_started'], $profiledata['username'])),
	'L_NO_POSTS' => $lang['No_Posts'],
	'L_CONTACT' => $lang['Contact'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_EMAIL' => $lang['Email'],
	'L_PM' => $lang['Private_Message'],
	'L_ICQ_NUMBER' => $lang['ICQ'],
	'L_YAHOO' => $lang['YIM'],
	'L_SKYPE' => $lang['SKYPE'],
	'L_AIM' => $lang['AIM'],
	'L_MESSENGER' => $lang['MSNM'],
	'L_WEBSITE' => $lang['Website'],
	'L_LOCATION' => $lang['Location'],
	'L_OCCUPATION' => $lang['Occupation'],
	'L_INTERESTS' => $lang['Interests'],

	'U_USERGROUPS' => append_sid(CMS_PAGE_GROUP_CP),

	'L_PHONE' => $lang['UserPhone'],
	'L_EXTRA_PROFILE_INFO' => $lang['Extra_profile_info'],
	'L_EXTRA_WINDOW'=> $lang['Extra_window'] . ' :: ' . $profiledata['username'],
	'U_EXTRA_WINDOW' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;extra_mode=window'),
	// Mighty Gorgon - HTTP AGENTS - BEGIN
	'USER_OS_IMG' => $user_os['img'],
	'USER_BROWSER_IMG' => $user_browser['img'],
	// Mighty Gorgon - HTTP AGENTS - END

	// Start add - Online/Offline/Hidden Mod
	'ONLINE_STATUS_IMG' => $online_status_img,
	'L_ONLINE_STATUS' => $lang['Online_status'],
	// End add - Online/Offline/Hidden Mod
//====================================================================== |
//==== Start Invision View Profile ===================================== |
//==== v1.1.3 ========================================================== |
//====
	'L_INVISION_A_STATS' => $lang['Invision_Active_Stats'],
	'L_INVISION_COMMUNICATE' => $lang['Invision_Communicate'],
	'L_INVISION_INFO' => $lang['Invision_Info'],
	'L_INVISION_MEMBER_TITLE' => $lang['Invision_Member_Title'],
	'L_INVISION_MEMBER_GROUP' => $lang['Invision_Member_Group'],
	'L_INVISION_MOST_ACTIVE' => $lang['Invision_Most_Active'],
	'L_INVISION_MOST_ACTIVE_POSTS' => sprintf($lang['Invision_Most_Active_Posts'], $user_most_active_posts),
	'L_INVISION_P_DETAILS' => $lang['Invision_Details'],
	'L_INVISION_POSTS' => $lang['Invision_Total_Posts'],
	'L_INVISION_PPD_STATS' => $lang['Invision_PPD_Stats'],
	'L_INVISION_SIGNATURE' => $lang['Invision_Signature'],
	'L_INVISION_WEBSITE' => $lang['Invision_Website'],
	'L_INVISION_VIEWING_PROFILE' => htmlspecialchars(sprintf($lang['Invision_View_Profile'], $profiledata['username'])),
//====
//==== Author: Disturbed One [http://anthonycoy.com] =================== |
//==== End Invision View Profile ======================================= |
//====================================================================== |
	// Start add - Gender MOD
	'L_GENDER' => $lang['Gender'],
	// End add - Gender MOD

// BIRTHDAY - BEGIN
	'L_BIRTHDAY' => $lang['Birthday'],
// BIRTHDAY - END

	'U_SEARCH_USER' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . $u_search_author),
	'U_SEARCH_USER_TOPICS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . $u_search_author . '&amp;search_topic_starter=1&amp;show_results=topics'),
	// Start Advanced IP Tools Pack MOD
	'L_MODERATOR_IP_INFORMATION' => $lang['Moderator_ip_information'],
	'L_REGISTERED_IP_ADDRESS' => $lang['Registered_ip_address'],
	'L_REGISTERED_HOSTNAME' => $lang['Registered_hostname'],
	'L_OTHER_REGISTERED_IPS' => sprintf($lang['Other_registered_ips'], $decoded_ip),
	'L_OTHER_IPS' => $lang['Other_posted_ips'],
	'USER_EMAIL_ADDRESS' => $profiledata['user_email'],
	'U_USER_IP_ADDRESS' => ($decoded_ip != $lang['Not_recorded']) ? '<a href="http://whois.sc/' . htmlspecialchars(urlencode($decoded_ip)) . '" target="_blank">' . $decoded_ip . '</a>' : $lang['Not_recorded'],
	'USER_IP_ADDRESS' => $decoded_ip,
	'USER_REGISTERED_HOSTNAME' => $hostname,
	// End Advanced IP Tools Pack MOD

	'U_USER_RECENT_TOPICS' => append_sid('recent.' . PHP_EXT . '?mode=utopics&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
	'U_USER_RECENT_POSTS' => append_sid('recent.' . PHP_EXT . '?mode=uposts&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
	'U_USER_RECENT_TOPICS_VIEW' => append_sid('recent.' . PHP_EXT . '?mode=utview&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),

	'S_PROFILE_ACTION' => append_sid(CMS_PAGE_PROFILE)
	)
);

// Profiled user must be online, so must the current user, and the profiled user must not the currently logged in user
if (($user_online_status != 'offline') && $user->data['session_logged_in'] && ($user->data['user_id'] != $profiledata['user_id']))
{
	$display_chat_link = true;
	if (!empty($config['ajax_chat_check_online']))
	{
		if (!function_exists('user_in_chat_session'))
		{
			include(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);
		}
		// Check if the user is in the chat room
		$is_user_in_chat = user_in_chat_session($profiledata['user_id']);
		$display_chat_link = !empty($is_user_in_chat) ? true : false;
	}

	if ($display_chat_link)
	{
		$ajax_chat_page = !empty($config['ajax_chat_link_type']) ? CMS_PAGE_AJAX_CHAT : CMS_PAGE_AJAX_SHOUTBOX;
		$ajax_chat_room = 'chat_room=' . (min($user->data['user_id'], $profiledata['user_id']) . '|' . max($user->data['user_id'], $profiledata['user_id']));
		$ajax_chat_link = append_sid($ajax_chat_page . '?' . $ajax_chat_room);
		$ajax_chat_ref = !empty($config['ajax_chat_link_type']) ? ($ajax_chat_link . '" target="_chat') : ('#" onclick="window.open(\'' . $ajax_chat_link . '\', \'_chat\', \'width=720,height=600,resizable=yes\'); return false;');
		$template->assign_vars(array(
			'U_AJAX_SHOUTBOX_PVT_LINK' => $ajax_chat_ref,
			'ICON_CHAT' => $all_ims['chat']['icon'],
			'U_CHAT' => $all_ims['chat']['url']
			)
		);
	}
}

// Custom Profile Fields - BEGIN
// Include Language
setup_extra_lang(array('lang_profile_fields'));

include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
$profile_data = get_fields('WHERE view_in_profile = ' . VIEW_IN_PROFILE . ' AND users_can_view = ' . ALLOW_VIEW);
$profile_names = array();

foreach($profile_data as $field)
{
	$name = $field['field_name'];
	$col_name = text_to_column($field['field_name']);
	$id = $profiledata['user_id'];
	$type = $field['field_type'];
	$location = $field['profile_location'];

	$field_id = $field['field_id'];
	$field_name = $field['field_name'];
	if (isset($lang[$field_id . '_' . $field_name]))
	{
		$field_name = $lang[$field_id . '_' . $field_name];
	}

	$sql = "SELECT $col_name FROM " . USERS_TABLE . "
		WHERE user_id = $id";
	$result = $db->sql_query($sql);
	$temp = $db->sql_fetchrow($result);
	$profile_names[$name] = displayable_field_data($temp[$col_name], $field['field_type']);
	$tmp_field = $profile_names[$name];
	if (isset($lang[$field_id . '_' . $tmp_field]))
	{
		$profile_names[$name] = $lang[$field_id . '_' . $tmp_field];
	}

	if($location == 1)
	{
		$template->assign_block_vars('custom_contact', array(
			'NAME' => $field_name,
			'VALUE' => $profile_names[$name],
			)
		);
	}
	else
	{
		$template->assign_block_vars('custom_about', array(
			'NAME' => $field_name,
			'VALUE' => $profile_names[$name],
			)
		);
	}
}
// Custom Profile Fields - END

// GROUPS - BEGIN
$groups_display_block = ($user->data['user_level'] == ADMIN) ? true : false;
$viewer_groups_list = array();
if ($user->data['session_logged_in'])
{
	$viewer_groups_data = get_groups_data_user($user->data['user_id'], false, false, array());
	if (!empty($viewer_groups_data))
	{
		foreach ($viewer_groups_data as $group_data)
		{
			if (empty($group_data['user_pending']))
			{
				$viewer_groups_list[] = $group_data['group_id'];
			}
		}
	}
}

$profile_groups_data = get_groups_data_user($profiledata['user_id'], true, true, array());
if (!empty($profile_groups_data))
{
	foreach ($profile_groups_data as $group_data)
	{
		$group_display = true;
		if ($user->data['user_level'] != ADMIN)
		{
			if (($group_data['group_type'] == GROUP_HIDDEN) && empty($group_data['user_pending']))
			{
				if (!$user->data['session_logged_in'] || empty($viewer_groups_list))
				{
					$group_display = false;
				}
				else
				{
					$group_display = in_array($group_data['group_id'], $viewer_groups_list) ? true : false;
				}
			}
		}

		if (!empty($group_display))
		{
			$groups_display_block = true;
			$group_name = $group_data['group_name'];
			$group_name = ($group_data['group_type'] == GROUP_HIDDEN) ? ('<i>' . $group_name . '</i>') : $group_name;
			$group_pending = (!empty($group_data['user_pending']) ? ' <i>[' . $lang['MEMBERSHIP_PENDING'] . ']</i>' : '');
			$template->assign_block_vars('groups', array(
				'GROUP_COLOR' => (!empty($group_data['group_color']) ? (' style="color: ' . $group_data['group_color'] . ';"') : ''),
				'U_GROUP_NAME' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_data['group_id']),
				'U_GROUP_REMOVE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;' . POST_GROUPS_URL . '=' . $group_data['group_id'] . '&amp;ug_rem=1'),
				'L_GROUP_NAME' => $group_name . $group_pending,
				'L_GROUP_DESC' => $group_data['group_description']
				)
			);
		}
	}
}

if (!empty($groups_display_block))
{
	$template->assign_var('S_DISPLAY_UCP_GROUPS', true);
}

if ($user->data['user_level'] == ADMIN)
{
	$profile_groups_list = array();
	if (!empty($profile_groups_data))
	{
		foreach ($profile_groups_data as $group_data)
		{
			$profile_groups_list[] = $group_data['group_id'];
		}
	}

	$all_groups = get_groups_data(false, true, array());
	$non_member_groups = array();
	foreach ($all_groups as $group_data)
	{
		$process_group = true;
		if (!empty($profile_groups_list) && in_array($group_data['group_id'], $profile_groups_list))
		{
			$process_group = false;
		}
		if ($process_group)
		{
			$non_member_groups[] = $group_data;
		}
	}

	if (!empty($non_member_groups))
	{
		$template->assign_var('S_DISPLAY_NON_MEMBER_GROUPS', true);
		$non_member_groups_select = '';
		$non_member_groups_select .= '<select name="' . POST_GROUPS_URL . '">';
		foreach ($non_member_groups as $group_data)
		{
			$color_style = (!empty($group_data['group_color']) ? (' style="color: ' . $group_data['group_color'] . ';"') : '');
			$non_member_groups_select .= '<option value="' . $group_data['group_id'] . '">' . $group_data['group_name'] . '</option>';
		}
		$non_member_groups_select .= '</select>';

		$s_hidden_fields = build_hidden_fields(array(
			'mode' => 'viewprofile',
			POST_USERS_URL => (int) $profiledata['user_id'],
			)
		);

		$template->assign_vars(array(
			'S_DISPLAY_NON_MEMBER_GROUPS' => true,
			'S_GROUPCP_ACTION' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
			'S_NMG_SELECT' => $non_member_groups_select,
			'L_ADD_MEMBER' => $lang['Add_member'],
			'L_NMG_SELECT' => $lang['Non_member_groups'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}
}
// GROUPS - END

// Start Advanced IP Tools Pack MOD
// Let's see if the user viewing this page is an admin or mod, if not, we can save several database queries! :P
$ip_display_auth = ip_display_auth($user->data, false);
if (!empty($ip_display_auth))
{
	$template->assign_block_vars('switch_display_ips', array());
	// All users registering under this IP address section
	if ($encoded_ip != '')
	{
		$sql = 'SELECT COUNT(user_id) AS total_users FROM ' . USERS_TABLE . ' WHERE user_registered_ip = "' . $encoded_ip . '" AND user_id != "' . $profiledata['user_id'] . '"';
		$result = $db->sql_query($sql);

		if (!$row = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_ERROR, 'Error: could not get the total users number.', '', __LINE__, __FILE__, $sql);
		}

		$total_users = $row['total_users'];

		if ($total_users > 0)
		{
			$u_start = (isset($_GET['u_start'])) ? intval($_GET['u_start']) : 0;

			$sql = "SELECT user_id, username, user_regdate, user_registered_ip, user_registered_hostname FROM " . USERS_TABLE . " WHERE user_registered_ip = '" . $encoded_ip . "' AND user_id != '" . $profiledata['user_id'] . "' ORDER BY user_regdate DESC LIMIT $u_start, " . $config['topics_per_page'];
			$result = $db->sql_query($sql);

			$template->assign_block_vars('switch_display_ips.switch_other_user_ips', array());

			while ($row = $db->sql_fetchrow($result))
			{
				$template->assign_block_vars('switch_display_ips.switch_other_user_ips.OTHER_REGISTERED_IPS', array(
					'USER_NAME' => $row['username'],
					'U_PROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
					'USER_HOSTNAME' => htmlspecialchars($row['user_registered_hostname']),
					'TIME' => create_date($user->data['user_dateformat'], $row['user_regdate'], $user->data['user_timezone']),
					)
				);
			}

			$base_url = CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'];
			$pagination = generate_pagination($base_url, $total_users, $config['topics_per_page'], $u_start, TRUE, 'u_start');

			$template->assign_vars(array(
				'USERS_PAGINATION' => $pagination,
				'USERS_PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($u_start / $config['topics_per_page']) + 1), ceil($total_users / $config['topics_per_page'])),
			));
		}

		else
		{
			$template->assign_vars(array(
				'L_NO_OTHER_REGISTERED_IPS' => $lang['No_other_registered_ips'],
				)
			);
			$template->assign_block_vars('switch_display_ips.switch_no_other_registered_ips', array());
		}
	}

	else
	{
		$template->assign_vars(array(
			'L_NO_OTHER_REGISTERED_IPS' => $lang['No_other_registered_ips'],
			)
		);
		$template->assign_block_vars('switch_display_ips.switch_no_other_registered_ips', array());
	}

	// All IP addresses this user has posted from section
	$total_ips = 0;
	$sql = 'SELECT poster_ip, COUNT(*) AS postings FROM ' . POSTS_TABLE . ' WHERE poster_id = "' . $profiledata['user_id'] . '" GROUP BY poster_ip ORDER BY ' . ((SQL_LAYER == 'msaccess') ? 'COUNT(*)' : 'postings') . ' DESC';
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$total_ips = $total_ips + 1;
	}

	if ($total_ips > 0)
	{
		$i_start = request_var('i_start', 0);
		$i_start = ($i_start < 0) ? 0 : $i_start;

		$sql = 'SELECT poster_ip, COUNT(*) AS postings FROM ' . POSTS_TABLE . ' WHERE poster_id = "' . $profiledata['user_id'] . '" GROUP BY poster_ip ORDER BY ' . ((SQL_LAYER == 'msaccess') ? 'COUNT(*)' : 'postings') . " DESC LIMIT $i_start, " . $config['topics_per_page'];
		$result = $db->sql_query($sql);

		$template->assign_block_vars('switch_display_ips.switch_other_posted_ips', array());

		while ($row = $db->sql_fetchrow($result))
		{
			$poster_ip = $row['poster_ip'];

			$template->assign_block_vars('switch_display_ips.switch_other_posted_ips.ALL_IPS_POSTED_FROM', array(
				'U_POSTER_IP' => 'http://whois.sc/' . htmlspecialchars(urlencode($poster_ip)),
				'POSTER_IP' => $poster_ip,
				'POSTS' => $row['postings'] . ' ' . (($row['postings'] == 1) ? $lang['Post'] : $lang['Posts']),
				'U_POSTS_LINK' => append_sid(CMS_PAGE_SEARCH . '?mode=results&amp;search_author=' . htmlspecialchars(urlencode($profiledata['username'])) . '&amp;search_ip=' . $poster_ip),
				)
			);
		}

		$base_url = CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'];
		$pagination = generate_pagination($base_url, $total_ips, $config['topics_per_page'], $i_start, true, 'i_start');

		$template->assign_vars(array(
			'IPS_PAGINATION' => $pagination,
			'IPS_PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($i_start / $config['topics_per_page']) + 1), ceil($total_ips / $config['topics_per_page'])),
			)
		);
	}

	else
	{
		$template->assign_vars(array(
			'L_NO_OTHER_POSTED_IPS' => $lang['No_other_posted_ips'],
			)
		);
		$template->assign_block_vars('switch_display_ips.switch_no_other_posted_ips', array());
	}

	if (!$config['disable_logins'])
	{
		$template->assign_var('S_LOGINS_HISTORY', true);
		// All logins section
		// Obtain the total logins for this user
		$sql = 'SELECT COUNT(login_id) AS total_logins FROM ' . LOGINS_TABLE . ' WHERE login_userid = ' . $profiledata['user_id'];
		$result = $db->sql_query($sql);

		if (!$row = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_ERROR, 'Error: could not get the logins number.', '', __LINE__, __FILE__, $sql);
		}

		$total_logins = $row['total_logins'];

		if ($total_logins > 0)
		{
			$l_start = (isset($_GET['l_start'])) ? intval($_GET['l_start']) : 0;

			// Now get the results in groups based on how many topics per page parameter set in the admin panel
			$sql = 'SELECT * FROM ' . LOGINS_TABLE . ' WHERE login_userid = ' . $profiledata['user_id'] . " ORDER BY login_time DESC LIMIT $l_start, " . $config['topics_per_page'];
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$ip = $row['login_ip'];

				$template->assign_block_vars('switch_display_ips.USER_LOGINS', array(
					'U_IP' => 'http://whois.sc/' . htmlspecialchars(urlencode($ip)),
					'IP' => $ip,
					'USER_AGENT' => htmlspecialchars($row['login_user_agent']),
					'LOGIN_TIME' => create_date($user->data['user_dateformat'], $row['login_time'], $user->data['user_timezone']),
					)
				);
			}

			$base_url = CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'];
			$pagination = generate_pagination($base_url, $total_logins, $config['topics_per_page'], $l_start, true, 'l_start');

			$template->assign_vars(array(
				'LOGINS_PAGINATION' => $pagination,
				'LOGINS_PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($l_start / $config['topics_per_page']) + 1), ceil($total_logins / $config['topics_per_page'])),
				)
			);
		}

		else
		{
			$template->assign_vars(array(
				'L_NO_LOGINS' => $lang['No_logins'],
				)
			);
			$template->assign_block_vars('switch_display_ips.switch_no_logins', array());
		}

		$template->assign_vars(array(
			'L_USERNAME' => $lang['Username'],
			'L_LOGINS' => $lang['Logins'],
			'L_IP' => $lang['IP'],
			'L_BROWSER' => $lang['Browser'],
			'L_TIME' => $lang['Time'],
			)
		);
	}
}
// End Advanced IP Tools Pack MOD

//Start Quick Administrator User Options and Information MOD
if ($user->data['user_level'] == ADMIN)
{
	$template->assign_block_vars('switch_user_admin',array());
}

$sql = "SELECT * FROM " . BANLIST_TABLE . " WHERE ban_userid = " . $profiledata['user_id'] . " OR ban_email = '" . $db->sql_escape($profiledata['user_email']) . "'";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$banned_username = $row['ban_userid'];
	$banned_email = $row['ban_email'];
}

$db->sql_freeresult($result);

$template->assign_vars(array(
	'L_QUICK_ADMIN_OPTIONS' => $lang['Quick_admin_options'],
	'L_ADMIN_EDIT_PROFILE' => $lang['Admin_edit_profile'],
	'L_ADMIN_EDIT_PERMISSIONS' => $lang['Admin_edit_permissions'],
	'L_USER_ACTIVE_INACTIVE' => ($profiledata['user_active'] == 1) ? $lang['User_active'] : $lang['User_not_active'],
	'L_BANNED_USERNAME' => ($banned_username == '') ? $lang['Username_not_banned'] : $lang['Username_banned'],
	'L_BANNED_EMAIL' => ($banned_email == '') ? $lang['User_email_not_banned'] : htmlspecialchars(sprintf($lang['User_email_banned'], $profiledata['user_email'])),
	'L_USER_BAN_UNBAN' => ($banned_username == '') ? $lang['USER_BAN'] : $lang['USER_UNBAN'],

	'U_USER_BAN_UNBAN' => IP_ROOT_PATH . 'card.' . PHP_EXT . '?mode=' . (($banned_username == '') ? 'ban' : 'unban') . '&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;sid=' . $user->data['session_id'],
	'U_ADMIN_EDIT_PROFILE' => ADM . '/admin_users.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;mode=edit&amp;redirect=yes',
	'U_ADMIN_EDIT_PERMISSIONS' => ADM . '/admin_ug_auth.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;mode=user'
	)
);

//End Quick Administrator User Options and Information MOD

include(IP_ROOT_PATH . 'includes/bb_usage_stats.' . PHP_EXT);
// We need to keep this here... to make sure also $view_bb_usage_allowed is assigned
$extra_stats_auth = (!empty($view_bb_usage_allowed) || !empty($ip_display_auth)) ? true : false;
$template->assign_vars(array(
	'L_EXTRA_STATS' => (!empty($show_extra_stats) ? $lang['EXTRA_STATS_HIDE'] : $lang['EXTRA_STATS_SHOW']),
	'U_EXTRA_STATS' => append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . (!empty($show_extra_stats) ? '' : '&amp;stats=1')),
	'S_EXTRA_STATS_AUTH' => (!empty($extra_stats_auth) ? true : false),
	'S_EXTRA_STATS' => (!empty($show_extra_stats) ? true : false),
	)
);

// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	$cm_viewprofile->post_vars($template, $profiledata, $user->data);
}
// MG Cash MOD For IP - END

full_page_generation('profile_view_body.tpl', htmlspecialchars($profiledata['username']), '', '');

?>