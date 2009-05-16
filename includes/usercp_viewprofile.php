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

if (empty($_GET[POST_USERS_URL]) || $_GET[POST_USERS_URL] == ANONYMOUS)
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}

$profiledata = get_userdata($_GET[POST_USERS_URL]);
if (empty($profiledata['user_id']))
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}

// Update the profile view list
$user = $profiledata['user_id'];
$viewer = addslashes($userdata['username']);
$viewer_id = $userdata['user_id'];
$current_time = time();
if ($user <> $viewer_id)
{
	$sql = "UPDATE " . USERS_TABLE . "
			SET user_profile_view = '1'
			WHERE user_id = " . $user;
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not update user data.", '', __LINE__, __FILE__, $sql);
		}

	$sql = "SELECT * FROM " . PROFILE_VIEW_TABLE . "
		WHERE user_id = " . $user . "
		AND viewer_id = " . $viewer_id;

	if ($result = $db->sql_query($sql))
	{
		if (!$row = $db->sql_fetchrow($result))
		$sql = "INSERT INTO " . PROFILE_VIEW_TABLE . "
			(user_id, viewername, viewer_id, view_stamp, counter)
			VALUES ('$user', '$viewer', '$viewer_id', '$current_time', '1')";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not insert profile views.", '', __LINE__, __FILE__, $sql);
		}
		else
		{
			$count = $row['counter'] + 1;
			$sql = "UPDATE " . PROFILE_VIEW_TABLE . "
					SET view_stamp = '$current_time', counter = '$count'
					WHERE user_id = " . $user. "
					AND viewer_id = " . $viewer_id;
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not update profile views.", '', __LINE__, __FILE__, $sql);
			}
		}
	}
}
if (!$profiledata)
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}
// Mighty Gorgon - Multiple Ranks - BEGIN
require_once(IP_ROOT_PATH . 'includes/functions_mg_ranks.' . PHP_EXT);
$ranks_sql = query_ranks();
// Mighty Gorgon - Multiple Ranks - END

//
// Output page header and profile_view template
//
$template->set_filenames(array('body' => 'profile_view_body.tpl'));
make_jumpbox(VIEWFORUM_MG);

//
// Calculate the number of days this user has been a member ($memberdays)
// Then calculate their posts per day
//
$regdate = $profiledata['user_regdate'];
$memberdays = max(1, round((time() - $regdate) / 86400));
$posts_per_day = $profiledata['user_posts'] / $memberdays;

// Get the users percentage of total posts
if ($profiledata['user_posts'] != 0 )
{
	$total_posts = $board_config['max_posts'];
	$percentage = ($total_posts) ? min(100, ($profiledata['user_posts'] / $total_posts) * 100) : 0;
}
else
{
	$percentage = 0;
}

// Mighty Gorgon - Thanks Received - BEGIN
$total_thanks_received = 0;
if (($board_config['show_thanks_profile'] == true) && ($board_config['disable_thanks_topics'] == false))
{
	$total_thanks_received = user_get_thanks_received($profiledata['user_id']);
	$template->assign_block_vars('show_thanks_profile', array());
}
// Mighty Gorgon - Thanks Received - END

// Mighty Gorgon - HTTP AGENTS - BEGIN
include(IP_ROOT_PATH . 'includes/functions_mg_http.' . PHP_EXT);
$user_os = get_user_os($profiledata['user_http_agents']);
$user_browser = get_user_browser($profiledata['user_http_agents']);
// Mighty Gorgon - HTTP AGENTS - END

// Mighty Gorgon - Full Album Pack - BEGIN
$cms_page_id_tmp = 'album';
$cms_auth_level_tmp = (isset($cms_config_layouts[$cms_page_id_tmp]['view']) ? $cms_config_layouts[$cms_page_id_tmp]['view'] : AUTH_ALL);
$show_latest_pics = check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp, true);
if ($show_latest_pics)
{
	include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_album_main.' . PHP_EXT);

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;
	$album_rate_pic_url = $album_show_pic_url;
	$album_comment_pic_url = $album_show_pic_url;

	$sql = "SELECT * FROM " . ALBUM_CONFIG_TABLE;
	if(!$result = $db->sql_query($sql, false, 'album_config_'))
	{
		message_die(GENERAL_ERROR, "Could not query album config information", "", __LINE__, __FILE__, $sql);
	}
	while($row = $db->sql_fetchrow($result))
	{
		$album_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);

	$limit_sql = $album_config['img_cols'] * $album_config['img_rows'];
	$cols_per_page = $album_config['img_cols'];

	$sql = "SELECT p.*, c.*, u.user_id, u.username, u.user_active, u.user_color
			FROM " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . " AS c, " . USERS_TABLE . " u
			WHERE c.cat_user_id = " . $profiledata['user_id'] . "
				AND p.pic_cat_id = c.cat_id
				AND p.pic_approval = 1
				AND u.user_id = p.pic_user_id
			ORDER BY pic_time DESC";

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query recent pics information', '', __LINE__, __FILE__, $sql);
	}

	$recentrow = array();

	while($row = $db->sql_fetchrow($result))
	{
		$recentrow[] = $row;
	}

	$totalpicrow = count($recentrow);

	$db->sql_freeresult($result);

	if ($totalpicrow > 0)
	{
		$temp_url = append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id']);
		$album_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_album'] . '" alt="' . sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username']) . '" title="' . sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username']) . '" /></a>';
		$album = '<a href="' . $temp_url . '">' . sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username']) . '</a>';

		$template->assign_block_vars('recent_pics_block', array());
		for ($i = 0; $i < (($totalpicrow < $limit_sql) ? $totalpicrow : $limit_sql); $i += $cols_per_page)
		{
			$template->assign_block_vars('recent_pics_block.recent_pics', array());

			for ($j = $i; $j < ($i + $cols_per_page); $j++)
			{
				if($j >= $totalpicrow)
				{
					break;
				}

				$pic_preview = '';
				$pic_preview_hs = '';
				if ($album_config['lb_preview'])
				{
					$slideshow_cat = 'Profile';
					$slideshow = !empty($slideshow_cat) ? ', { slideshowGroup: \'' . $slideshow_cat . '\' } ' : '';
					$pic_preview_hs = ' class="highslide" onclick="return hs.expand(this' . $slideshow . ');"';

					$pic_preview = 'onmouseover="showtrail(\'' . append_sid('album_picm.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id']) . '\',\'' . addslashes($recentrow[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
				}

				$pic_sp_link = append_sid('album_showpage.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id']);
				$pic_dl_link = append_sid('album_pic.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id']);

				$template->assign_block_vars('recent_pics_block.recent_pics.recent_col', array(
					'U_PIC' => ($album_config['fullpic_popup'] ? $pic_dl_link : $pic_sp_link),
					'U_PIC_SP' => $pic_sp_link,
					'U_PIC_DL' => $pic_dl_link,

					'THUMBNAIL' => append_sid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id']),
					'PIC_PREVIEW_HS' => $pic_preview_hs,
					'PIC_PREVIEW' => $pic_preview,
					'DESC' => $recentrow[$j]['pic_desc']
					)
				);

				$recent_poster = colorize_username($recentrow[$j]['user_id'], $recentrow[$j]['username'], $recentrow[$j]['user_color'], $recentrow[$j]['user_active']);
				$template->assign_block_vars('recent_pics_block.recent_pics.recent_detail', array(
					'PIC_TITLE' => htmlspecialchars($recentrow[$j]['pic_title']),
					'TITLE' => '<a href = "' . $album_show_pic_url . '?pic_id=' . $recentrow[$j]['pic_id'] . '">' . htmlspecialchars($recentrow[$j]['pic_title']) . '</a>',
					'POSTER' => $recent_poster,
					'TIME' => create_date($board_config['default_dateformat'], $recentrow[$j]['pic_time'], $board_config['board_timezone']),

					'U_PIC' => ($album_config['fullpic_popup'] ? $pic_dl_link : $pic_sp_link),
					'U_PIC_SP' => $pic_sp_link,
					'U_PIC_DL' => $pic_dl_link,

					'VIEW' => $recentrow[$j]['pic_view_count'],
					)
				);
			}
		}
	}
	else
	{
		$album_img = '&nbsp;';
		$album = '';
	}
}
// Mighty Gorgon - Full Album Pack - END

$avatar_img = user_get_avatar($profiledata['user_id'], $profiledata['user_level'], $profiledata['user_avatar'], $profiledata['user_avatar_type'], $profiledata['user_allowavatar']);

// Mighty Gorgon - Multiple Ranks - BEGIN
$user_ranks = generate_ranks($profiledata, $ranks_sql);

$user_rank_01 = ($user_ranks['rank_01'] == '') ? '' : ($user_ranks['rank_01'] . '<br />');
$user_rank_01_img = ($user_ranks['rank_01_img'] == '') ? '' : ($user_ranks['rank_01_img'] . '<br />');
$user_rank_02 = ($user_ranks['rank_02'] == '') ? '' : ($user_ranks['rank_02'] . '<br />');
$user_rank_02_img = ($user_ranks['rank_02_img'] == '') ? '' : ($user_ranks['rank_02_img'] . '<br />');
$user_rank_03 = ($user_ranks['rank_03'] == '') ? '' : ($user_ranks['rank_03'] . '<br />');
$user_rank_03_img = ($user_ranks['rank_03_img'] == '') ? '' : ($user_ranks['rank_03_img'] . '<br />');
$user_rank_04 = ($user_ranks['rank_04'] == '') ? '' : ($user_ranks['rank_04'] . '<br />');
$user_rank_04_img = ($user_ranks['rank_04_img'] == '') ? '' : ($user_ranks['rank_04_img'] . '<br />');
$user_rank_05 = ($user_ranks['rank_05'] == '') ? '' : ($user_ranks['rank_05'] . '<br />');
$user_rank_05_img = ($user_ranks['rank_05_img'] == '') ? '' : ($user_ranks['rank_05_img'] . '<br />');
// Mighty Gorgon - Multiple Ranks - END

$pm_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']);
$pm_img = '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
$pm = '<a href="' . $pm_url . '">' . $lang['Send_private_message'] . '</a>';

$email_url = '';
if (empty($userdata['user_id']) || ($userdata['user_id'] == ANONYMOUS))
{
	if (!empty($profiledata['user_viewemail']))
	{
		$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Hidden_email'] . '" title="' . $lang['Hidden_email'] . '" />';
	}
	else
	{
		$email_img = '&nbsp;';
	}
	$email = '&nbsp;';
}
elseif (!empty($profiledata['user_viewemail']) || $userdata['user_level'] == ADMIN)
{
	$email_url = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $profiledata['user_id']) : 'mailto:' . $profiledata['user_email'];
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

$aim_img = (!empty($profiledata['user_aim'])) ? build_im_link('aim', $profiledata['user_aim'], $lang['AIM'], $images['icon_aim']) : '&nbsp;';
$aim = (!empty($profiledata['user_aim'])) ? build_im_link('aim', $profiledata['user_aim'], $lang['AIM'], false) : '&nbsp;';
$aim_url = (!empty($profiledata['user_aim'])) ? build_im_link('aim', $profiledata['user_aim'], $lang['AIM'], false, true) : '';

$icq_status_img = (!empty($profiledata['user_icq'])) ? '<a href="http://wwp.icq.com/' . $profiledata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $profiledata['user_icq'] . '&img=5" width="18" height="18" /></a>' : '&nbsp;';
$icq_img = (!empty($profiledata['user_icq'])) ? build_im_link('icq', $profiledata['user_icq'], $lang['ICQ'], $images['icon_icq']) : '&nbsp;';
$icq = (!empty($profiledata['user_icq'])) ? build_im_link('icq', $profiledata['user_icq'], $lang['ICQ'], false) : '&nbsp;';
$icq_url = (!empty($profiledata['user_icq'])) ? build_im_link('icq', $profiledata['user_icq'], $lang['ICQ'], false, true) : '';

$msn_img = (!empty($profiledata['user_msnm'])) ? build_im_link('msn', $profiledata['user_msnm'], $lang['MSNM'], $images['icon_msnm']) : '&nbsp;';
$msn = (!empty($profiledata['user_msnm'])) ? build_im_link('msn', $profiledata['user_msnm'], $lang['MSNM'], false) : '&nbsp;';
$msn = $msn_img;
$msn_url = (!empty($profiledata['user_msnm'])) ? build_im_link('msn', $profiledata['user_msnm'], $lang['MSNM'], false, true) : '';

$skype_img = (!empty($profiledata['user_skype'])) ? build_im_link('skype', $profiledata['user_skype'], $lang['SKYPE'], $images['icon_skype']) : '&nbsp;';
$skype = (!empty($profiledata['user_skype'])) ? build_im_link('skype', $profiledata['user_skype'], $lang['SKYPE'], false) : '&nbsp;';
$skype_url = (!empty($profiledata['user_skype'])) ? build_im_link('skype', $profiledata['user_skype'], $lang['SKYPE'], false, true) : '';

$yim_img = (!empty($profiledata['user_yim'])) ? build_im_link('yahoo', $profiledata['user_yim'], $lang['YIM'], $images['icon_yim']) : '&nbsp;';
$yim = (!empty($profiledata['user_yim'])) ? build_im_link('yahoo', $profiledata['user_yim'], $lang['YIM'], false) : '&nbsp;';
$yim_url = (!empty($profiledata['user_yim'])) ? build_im_link('yahoo', $profiledata['user_yim'], $lang['YIM'], false, true) : '';

$temp_url = append_sid(SEARCH_MG . '?search_author=' . urlencode($profiledata['username']) . '&amp;showresults=posts');
$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" title="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" /></a>';
$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '</a>';
// Start Advanced IP Tools Pack MOD
$encoded_ip = ($profiledata['user_registered_ip'] == '') ? '' : $profiledata['user_registered_ip'];
$decoded_ip = (decode_ip($encoded_ip) == '0.0.0.0') ? $lang['Not_recorded'] : decode_ip($encoded_ip);
$hostname = ($profiledata['user_registered_hostname'] == '') ? $lang['Not_recorded'] : htmlspecialchars($profiledata['user_registered_hostname']);
// End Advanced IP Tools Pack MOD

// BBCode - BEGIN
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
global $bbcode;
$bbcode->allow_html = $board_config['allow_html'];
$bbcode->allow_bbcode = $board_config['allow_bbcode'];
if ($board_config['allow_smilies'] && $profiledata['user_allowsmile'] && !$lofi)
{
	$bbcode->allow_smilies = $board_config['allow_smilies'];
}
else
{
	$bbcode->allow_smilies = false;
}
// BBCode - END

$user_sig = '';
if ($profiledata['user_attachsig'] && $board_config['allow_sig'])
{
	$user_sig = $profiledata['user_sig'];
	if ($user_sig != '')
	{
		$bbcode->is_sig = true;
		$user_sig = $bbcode->parse($user_sig);
		$bbcode->is_sig = false;
		if (!$userdata['user_allowswearywords'])
		{
			$orig_word = !empty($orig_word) ? $orig_word : array();
			$replacement_word = !empty($replacement_word) ? $replacement_word : array();
			obtain_word_list($orig_word, $replacement_word);
			if(!empty($orig_word))
			{
				$user_sig = preg_replace($orig_word, $replacement_word, $user_sig);
			}
		}
	}
	//$template->assign_block_vars('switch_user_sig_block', array());
}

$user_sig = ($user_sig == '') ? '&nbsp;' : $user_sig;

$selfdes = $profiledata['user_selfdes'];
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

if (!$userdata['user_allowswearywords'])
{
	$orig_word = !empty($orig_word) ? $orig_word : array();
	$replacement_word = !empty($replacement_word) ? $replacement_word : array();
	obtain_word_list($orig_word, $replacement_word);
	if(!empty($orig_word))
	{
		$selfdes = preg_replace($orig_word, $replacement_word, $selfdes);
	}
}
if ($user_sig != '')
{
	$selfdes = $selfdes . '<br /><br /><hr />' . $user_sig;
}


if ($profiledata['user_id'])
{
	$user_most_active = get_forum_most_active($profiledata['user_id']);
	$user_most_active_forum_url = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . urlencode($user_most_active['forum_id']));
	$user_most_active_forum_name = $user_most_active['forum_name'];
	$user_most_active_posts = $user_most_active['posts'];
}

// Start add - Birthday MOD
if ($profiledata['user_birthday'] != 999999)
{
	$user_birthday = realdate($lang['DATE_FORMAT_BIRTHDAY'], $profiledata['user_birthday']);
}
else
{
	$user_birthday = $lang['No_birthday_specify'];
}
// End add - Birthday MOD


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
//if (defined('ACTIVITY_MOD'))
if (defined('ACTIVITY_MOD') && (ACTIVITY_MOD == true))
{
	include_once(IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'includes/functions_amod_plus.' . PHP_EXT);
	unset($trophy_count, $trophy_holder, $trophy);
	if (($board_config['ina_show_view_profile']) && ($profiledata['user_trophies'] > '0') && ($profiledata['user_id'] != ANONYMOUS))
	{
		$template->assign_block_vars('trophy', array(
			'PROFILE_TROPHY' => '<a href="javascript:popup_open(\'' . IP_ROOT_PATH . 'activity_trophy_popup.' . PHP_EXT . '?user=' . $profiledata['user_id'] . '&sid=' . $userdata['session_id'] . '\', \'New_Window\', \'400\', \'380\', \'yes\')" onclick="blur()">' . $lang['Trohpy'] . '</a>:&nbsp;&nbsp;' . $profiledata['user_trophies'],
			'TROPHY_TITLE' => $lang['Trohpy']
			)
		);
	}

	$template->assign_vars(array(
		'PROFILE_TIME' => DisplayPlayingTime(2, $profiledata['ina_time_playing']),
		'PROFILE_TITLE' => $lang['profile_game_time']
		)
	);

	if (($board_config['ina_char_show_viewprofile']) && ($profiledata['ina_char_name']) && ($profile_data['user_id'] != ANONYMOUS))
	{
		//include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		$template->assign_block_vars('profile_char', array(
			'CHAR_PROFILE' => AMP_Profile_Char($profiledata['user_id'], '')
			)
		);
	}

	$poster_rank .= Amod_Trophy_King_Image($profiledata['user_id']);
}
// Activity - END

if (function_exists('get_html_translation_table'))
{
	$u_search_author = urlencode(strtr($profiledata['username'], array_flip(get_html_translation_table(HTML_ENTITIES))));
}
else
{
	$u_search_author = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $profiledata['username']));
}

// Generate page
$page_title = $lang['Viewing_profile'];
$meta_description = '';
$meta_keywords = '';
$link_name = htmlspecialchars(stripslashes($profiledata['username']));
$nav_server_url = create_server_url();
$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
$breadcrumbs_links_right = '<a href="' . append_sid(SEARCH_MG . '?search_author=' . $u_search_author . '&amp;search_topic_starter=1&amp;show_results=topics') . '">' . sprintf($lang['Search_user_topics_started'], $profiledata['username']) . '</a>&nbsp;&bull;&nbsp;<a href="' . append_sid(SEARCH_MG . '?search_author=' . $u_search_author) . '">' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '</a><br /><a href="' . append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id']) . '">' . sprintf($lang['Personal_Gallery_Of_User_Profile'], $profiledata['username'], $totalpicrow) . '</a>&nbsp;&bull;&nbsp;<a href="' . append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id'] . '&amp;mode=' . ALBUM_VIEW_LIST) . '">' . sprintf($lang['Picture_List_Of_User'], $profiledata['username']) . '</a>';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

// Start add - Online/Offline/Hidden Mod
if ($profiledata['user_session_time'] >= (time() - $board_config['online_time']))
{
	if ($profiledata['user_allow_viewonline'])
	{
		$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_online'] . '" alt="' . sprintf($lang['is_online'], $profiledata['username']) . '" title="' . sprintf($lang['is_online'], $profiledata['username']) . '" /></a>';
	}
	elseif ($userdata['user_level'] == ADMIN || $userdata['user_id'] == $profiledata['user_id'])
	{
		$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_hidden'] . '" alt="' . sprintf($lang['is_hidden'], $profiledata['username']) . '" title="' . sprintf($lang['is_hidden'], $profiledata['username']) . '" /></a>';
	}
	else
	{
		$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $profiledata['username']) . '" title="' . sprintf($lang['is_offline'], $profiledata['username']) . '" />';
	}
}
else
{
	$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $profiledata['username']) . '" title="' . sprintf($lang['is_offline'], $profiledata['username']) . '" />';
}
// End add - Online/Offline/Hidden Mod
display_upload_attach_box_limits($profiledata['user_id']);

// Mighty Gorgon - Feedbacks - BEGIN
if (defined('MG_FEEDBACKS'))
{
	define('MG_ROOT_PATH', IP_ROOT_PATH . 'mg/');
	include_once(MG_ROOT_PATH . 'includes/functions_feedbacks.' . PHP_EXT);
	include_once(MG_ROOT_PATH . 'common.' . PHP_EXT);
	include_once(MG_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_mg.' . PHP_EXT);
	include_once(MG_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_feedbacks.' . PHP_EXT);

	$feedbacks_received = '';
	$feedbacks_details = get_user_feedbacks_received($profiledata['user_id']);
	if ($feedbacks_details['feedbacks_count'] > 0)
	{
		$feedbacks_average = (($feedbacks_details['feedbacks_count'] > 0) ? (round($feedbacks_details['feedbacks_sum'] / $feedbacks_details['feedbacks_count'], 1)) : 0);
		$feedbacks_average_img = IP_ROOT_PATH . 'images/feedbacks/' . build_feedback_rating_image($feedbacks_average);
		$feedbacks_received = (($feedbacks_details['feedbacks_count'] > 0) ? ('[ <a href="' . append_sid(MG_FEEDBACKS_FILE . '?' . POST_USERS_URL . '=' . $profiledata['user_id']) . '">' . $feedbacks_details['feedbacks_count'] . '</a> ]&nbsp;&nbsp;<img src="' . $feedbacks_average_img . '" style="vertical-align:middle;" alt="' . $feedbacks_average . '" title="' . $feedbacks_average . '" />') : '');
	}
}
// Mighty Gorgon - Feedbacks - END

$template->assign_vars(array(
	// Mighty Gorgon - Feedbacks - BEGIN
	'FEEDBACKS' => $feedbacks_received,
	// Mighty Gorgon - Feedbacks - END
	'USERNAME' => $profiledata['username'],
	'JOINED' => create_date($lang['JOINED_DATE_FORMAT'], $profiledata['user_regdate'], $board_config['board_timezone']),

	// Start add - Last visit MOD
	'L_LOGON' => $lang['Last_logon'],
	'LAST_LOGON' => ($userdata['user_level'] == ADMIN || (!$board_config['hidde_last_logon'] && $profiledata['user_allow_viewonline'])) ? (($profiledata['user_lastlogon'])? create_date($board_config['default_dateformat'], $profiledata['user_lastlogon'], $board_config['board_timezone']):$lang['Never_last_logon']):$lang['Hidde_last_logon'],
	'L_TOTAL_ONLINE_TIME' => $lang['Total_online_time'],
	'TOTAL_ONLINE_TIME' => make_hours($profiledata['user_totaltime']),
	'L_LAST_ONLINE_TIME' => $lang['Last_online_time'],
	'LAST_ONLINE_TIME' => make_hours($profiledata['user_session_time'] - $profiledata['user_lastlogon']),
	'L_NUMBER_OF_VISIT' => $lang['Number_of_visit'],
	'NUMBER_OF_VISIT' => ($profiledata['user_totallogon'] > 0) ? $profiledata['user_totallogon'] : $lang['None'],
	'L_NUMBER_OF_PAGES' => $lang['Number_of_pages'],
	'NUMBER_OF_PAGES' => ($profiledata['user_totalpages']) ? $profiledata['user_totalpages'] : $lang['None'],
	// End add - Last visit MOD

	// Mighty Gorgon - Multiple Ranks - BEGIN
	'USER_RANK_01' => $user_rank_01,
	'USER_RANK_01_IMG' => $user_rank_01_img,
	'USER_RANK_02' => $user_rank_02,
	'USER_RANK_02_IMG' => $user_rank_02_img,
	'USER_RANK_03' => $user_rank_03,
	'USER_RANK_03_IMG' => $user_rank_03_img,
	'USER_RANK_04' => $user_rank_04,
	'USER_RANK_04_IMG' => $user_rank_04_img,
	'USER_RANK_05' => $user_rank_05,
	'USER_RANK_05_IMG' => $user_rank_05_img,
	// Mighty Gorgon - Multiple Ranks - END
	'POSTS_PER_DAY' => $posts_per_day,
	'POSTS' => $profiledata['user_posts'],
	'PERCENTAGE' => $percentage . '%',
	'POST_DAY_STATS' => sprintf($lang['User_post_day_stats'], $posts_per_day),
	'POST_PERCENT_STATS' => sprintf($lang['User_post_pct_stats'], $percentage),
	'THANKS_RECEIVED' => (($total_thanks_received > 0) ? ('<a href="' . append_sid(SEARCH_MG . '?search_thanks=' . $profiledata['user_id']) . '">' . $total_thanks_received . '</a>') : $total_thanks_received),
	'INVISION_AVATAR_IMG' => $avatar_img,
	'INVISION_MOST_ACTIVE_FORUM_URL' => $user_most_active_forum_url,
	'INVISION_MOST_ACTIVE_FORUM_NAME' => $user_most_active_forum_name,
	'INVISION_POST_DAY_STATS' => sprintf($lang['Invision_User_post_day_stats'], $posts_per_day),
	'INVISION_POST_PERCENT_STATS' => sprintf($lang['Invision_User_post_pct_stats'], $percentage),
	'INVISION_USER_SIG' => $user_sig,
	'SEARCH_IMG' => $search_img,
	'SEARCH' => $search,
	'PM_IMG' => $pm_img,
	'PM' => $pm,
	'U_PM' => $pm_url,
	'EMAIL_IMG' => (!$userdata['session_logged_in'])? '' : $email_img,
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
	'YIM_IMG' => $yim_img,
	'YIM' => $yim,
	'U_YIM' => $yim_url,

	//'LOCATION' => ($profiledata['user_from']) ? $profiledata['user_from'] : '&nbsp;',
	'LOCATION' => $location,
	'OCCUPATION' => ($profiledata['user_occ']) ? $profiledata['user_occ'] : '&nbsp;',
	'INTERESTS' => ($profiledata['user_interests']) ? $profiledata['user_interests'] : '&nbsp;',

	'PHONE' => ($profiledata['user_phone']) ? $profiledata['user_phone'] : '&nbsp;',
	'SELFDES' => $selfdes,

	'U_PROFILE_VISITS' => append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;' . POST_POST_URL . '=0'),
	'U_VISITS' => '<a href="' . append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;' . POST_POST_URL . '=0') . '"><img src="' . $images['icon_view'] . '" alt="' . $lang['Views'] . '" /></a>',

	// Start add - Gender MOD
	'GENDER' => $gender,
	// End add - Gender MOD


	// Start add - Birthday MOD
	'BIRTHDAY' => $user_birthday,
	// End add - Birthday MOD

	'AVATAR_IMG' => $avatar_img,

	'L_VIEWING_PROFILE' => sprintf($lang['Viewing_user_profile'], $profiledata['username']),
	'L_ABOUT_USER' => sprintf($lang['About_user'], $profiledata['username']),
	'L_AVATAR' => $lang['Avatar'],
	'L_POSTER_RANK' => $lang['Poster_rank'],
	'L_JOINED' => $lang['Joined'],
	'L_TOTAL_POSTS' => $lang['Total_posts'],
	'L_SEARCH_USER_POSTS' => sprintf($lang['Search_user_posts'], $profiledata['username']),
	'L_SEARCH_USER_TOPICS' => sprintf($lang['Search_user_topics_started'], $profiledata['username']),
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

	'L_PHONE' => $lang['UserPhone'],
	'L_EXTRA_PROFILE_INFO' => $lang['Extra_profile_info'],
	'L_EXTRA_WINDOW'=> $lang['Extra_window']. " :: " . $profiledata['username'],
	'U_EXTRA_WINDOW' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&extra_mode=window'),
	// Mighty Gorgon - HTTP AGENTS - BEGIN
	'USER_OS_IMG' => $user_os['img'],
	'USER_BROWSER_IMG' => $user_browser['img'],
	// Mighty Gorgon - HTTP AGENTS - END
	// Mighty Gorgon - Full Album Pack - BEGIN
	'ALBUM_IMG' => $album_img,
	'ALBUM' => $album,
	'U_PERSONAL_GALLERY' => append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id']),
	'L_PERSONAL_GALLERY' => sprintf($lang['Personal_Gallery_Of_User_Profile'], $profiledata['username'], $totalpicrow),
	'U_TOGGLE_VIEW_ALL' => append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id'] . '&amp;mode=' . ALBUM_VIEW_ALL),
	'TOGGLE_VIEW_ALL_IMG' => $images['icon_tiny_search'],
	'L_TOGGLE_VIEW_ALL' => sprintf($lang['Show_All_Pic_View_Mode_Profile'], $profiledata['username']),
	'U_ALL_IMAGES_BY_USER' => append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id'] . '&amp;mode=' . ALBUM_VIEW_LIST),
	'L_ALL_IMAGES_BY_USER' => sprintf($lang['Picture_List_Of_User'], $profiledata['username']),
	'L_PERSONAL_ALBUM' => $lang['Your_Personal_Gallery'],
	'L_PIC_TITLE' => $lang['Pic_Image'],
	'L_POSTER' => $lang['Pic_Poster'],
	'L_POSTED' => $lang['Posted'],
	'L_VIEW' => $lang['View'],
	'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',
	'L_NO_PICS' => $lang['No_Pics'],
	'L_RECENT_PUBLIC_PICS' => $lang['Recent_Public_Pics'],
	'S_COLS' => $album_config['cols_per_page'],
	'S_COL_WIDTH' => ($album_config['cols_per_page'] == 0) ? '100%' : (100/$album_config['cols_per_page']) . '%',
	//'S_COL_WIDTH' => (100/$album_config['cols_per_page']) . '%',
	// Mighty Gorgon - Full Album Pack - END
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
	'L_INVISION_VIEWING_PROFILE' => sprintf($lang['Invision_View_Profile'], $profiledata['username']),
//====
//==== Author: Disturbed One [http://anthonycoy.com] =================== |
//==== End Invision View Profile ======================================= |
//====================================================================== |
	// Start add - Gender MOD
	'L_GENDER' => $lang['Gender'],
	// End add - Gender MOD

// Start add - Birthday MOD
	'L_BIRTHDAY' => $lang['Birthday'],
// End add - Birthday MOD

	'U_SEARCH_USER' => append_sid(SEARCH_MG . '?search_author=' . $u_search_author),
	'U_SEARCH_USER_TOPICS' => append_sid(SEARCH_MG . '?search_author=' . $u_search_author . '&amp;search_topic_starter=1&amp;show_results=topics'),
	// Start Advanced IP Tools Pack MOD
	'L_MODERATOR_IP_INFORMATION' => $lang['Moderator_ip_information'],
	'L_REGISTERED_IP_ADDRESS' => $lang['Registered_ip_address'],
	'L_REGISTERED_HOSTNAME' => $lang['Registered_hostname'],
	'L_OTHER_REGISTERED_IPS' => sprintf($lang['Other_registered_ips'], $decoded_ip),
	'L_OTHER_IPS' => $lang['Other_posted_ips'],
	'USER_EMAIL_ADDRESS' => $profiledata['user_email'],
	'U_USER_IP_ADDRESS' => ($decoded_ip != $lang['Not_recorded']) ? '<a href="http://whois.sc/' . $decoded_ip . '" target="_blank">' . $decoded_ip . '</a>' : $lang['Not_recorded'],
	'USER_IP_ADDRESS' => $decoded_ip,
	'USER_REGISTERED_HOSTNAME' => $hostname,
	// End Advanced IP Tools Pack MOD

	'U_USER_RECENT_TOPICS' => append_sid('recent.' . PHP_EXT . '?mode=utopics&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
	'U_USER_RECENT_POSTS' => append_sid('recent.' . PHP_EXT . '?mode=uposts&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
	'U_USER_RECENT_TOPICS_VIEW' => append_sid('recent.' . PHP_EXT . '?mode=utview&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),

	'S_PROFILE_ACTION' => append_sid(PROFILE_MG)
	)
);


// Custom Profile Fields - BEGIN
// Include Language
$language = $board_config['default_lang'];
if (!file_exists(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_profile_fields.' . PHP_EXT))
{
	$language = 'english';
}
include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_profile_fields.' . PHP_EXT);

include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
$profile_data = get_fields('WHERE view_in_profile = ' . VIEW_IN_PROFILE . ' AND users_can_view = ' . ALLOW_VIEW);
$profile_names = array();

$abouts = array();
$contacts = array();
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
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR,'Could not obtain field value', '', __LINE__, __FILE__, $sql);
	}

	$temp = $db->sql_fetchrow($result);
	$profile_names[$name] = displayable_field_data($temp[$col_name], $field['field_type']);
	$tmp_field = $profile_names[$name];
	if (isset($lang[$field_id . '_' . $tmp_field]))
	{
		$profile_names[$name] = $lang[$field_id . '_' . $tmp_field];
	}

	if($location == 1)
	{
		$contacts[] = '<td valign="top" class="' . $theme['td_class2'] . '"><b><span class="genmed">' . $field_name . '</span></b></td><td class="' . $theme['td_class1'] . ' post-buttons"><span class="genmed">' . $profile_names[$name] . '&nbsp;</span></td>';
	}
	else
	{
		$abouts[] = '<td valign="top" class="' . $theme['td_class2'] . '"><b><span class="genmed">' . $field_name . '</span></b></td><td class="' . $theme['td_class1'] . ' post-buttons"><span class="genmed">' . $profile_names[$name] . '&nbsp;</span></td>';
	}
}

foreach($abouts as $about_field)
{
	$template->assign_block_vars('custom_about',array('ABOUT' => $about_field));
}

foreach($contacts as $contact_field)
{
	$template->assign_block_vars('custom_contact',array('CONTACT' => $contact_field));
}
// Custom Profile Fields - END

//====================================================================== |
//==== Start Invision View Profile ===================================== |
//==== v1.1.3 ========================================================== |
$user_id = $userdata['user_id'];
$view_user_id = $profiledata['user_id'];
$groups = array();
$sql = '
	SELECT
		g.group_id,
		g.group_name,
		g.group_description,
		g.group_type
	FROM
		'.USER_GROUP_TABLE.' as l,
		'.GROUPS_TABLE.' as g
	WHERE
		l.user_pending = 0 AND
		g.group_single_user = 0 AND
		l.user_id ='. $view_user_id.' AND
		g.group_id = l.group_id
	ORDER BY
		g.group_name,
		g.group_id';
if (!($result = $db->sql_query($sql))) message_die(GENERAL_ERROR, 'Could not read groups', '', __LINE__, __FILE__, $sql);
while ($group = $db->sql_fetchrow($result)) $groups[] = $group;

$template->assign_vars(array(
	'L_USERGROUPS' => $lang['Usergroups'],
	)
);
if (count($groups) > 0)
{
	$template->assign_block_vars('switch_groups_on', array());
}
{
	for ($i=0; $i < count($groups); $i++)
	{
		$is_ok = false;
		// groupe invisible ?
		if (($groups[$i]['group_type'] != GROUP_HIDDEN) || ($userdata['user_level'] == ADMIN))
		{
			$is_ok = true;
		}
		else
		{
			$group_id = $groups[$i]['group_id'];
			$sql = 'SELECT * FROM '.USER_GROUP_TABLE.' WHERE group_id='.$group_id.' AND user_id='.$user_id.' AND user_pending=0';
			if (!($result = $db->sql_query($sql))) message_die(GENERAL_ERROR, 'Couldn\'t obtain viewer group list', '', __LINE__, __FILE__, $sql);
			$is_ok = ($group = $db->sql_fetchrow($result));
		}  // end if ($view_list[$i]['group_type'] == GROUP_HIDDEN)
		// groupe visible : afficher
		if ($is_ok)
		{
			$u_group_name = append_sid('groupcp.' . PHP_EXT . '?g=' . $groups[$i]['group_id']);
			$l_group_name = $groups[$i]['group_name'];
			$l_group_desc = $groups[$i]['group_description'];
			$template->assign_block_vars('groups',array(
				'U_GROUP_NAME' => $u_group_name,
				'L_GROUP_NAME' => $l_group_name,
				'L_GROUP_DESC' => $l_group_desc,
				)
			);
		}  // end if ($is_ok)
	}  // end for ($i=0; $i < count($groups); $i++)
}  // end if (count($groups) > 0)
//====
//==== Author: Disturbed One [http://anthonycoy.com] =================== |
//==== End Invision View Profile ======================================= |
//====================================================================== |

// Start Advanced IP Tools Pack MOD
// Let's see if the user viewing this page is an admin or mod, if not, we can save several database queries! :P
if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
{
	$template->assign_block_vars('switch_user_admin_or_mod',array() );
	// All users registering under this IP address section
	if ($encoded_ip != '')
	{
		$sql = 'SELECT COUNT(user_id) AS total_users FROM ' . USERS_TABLE . ' WHERE user_registered_ip = "' . $encoded_ip . '" AND user_id != "' . $profiledata['user_id'] . '"';

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error: could not determine total users registering under this IP.', '', __LINE__, __FILE__, $sql);
		}

		if (!$row = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_ERROR, 'Error: could not get the total users number.', '', __LINE__, __FILE__, $sql);
		}

		$total_users = $row['total_users'];

		if ($total_users > 0)
		{
			$u_start = (isset($_GET['u_start'])) ? intval($_GET['u_start']) : 0;

			$sql = "SELECT user_id, username, user_regdate, user_registered_ip, user_registered_hostname FROM " . USERS_TABLE . " WHERE user_registered_ip = '" . $encoded_ip . "' AND user_id != '" . $profiledata['user_id'] . "' ORDER BY user_regdate DESC LIMIT $u_start, " . $board_config['topics_per_page'];

			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error: could not look up other registered IP addresses.', '', __LINE__, __FILE__, $sql);
			}

			$template->assign_block_vars('switch_user_admin_or_mod.switch_other_user_ips', array());

				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('switch_user_admin_or_mod.switch_other_user_ips.OTHER_REGISTERED_IPS', array(
						'USER_NAME' => $row['username'],
						'U_PROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
						'USER_HOSTNAME' => htmlspecialchars($row['user_registered_hostname']),
						'TIME' => create_date($userdata['user_dateformat'], $row['user_regdate'], $userdata['user_timezone']),
						)
					);
				}

			$base_url = PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'];
			$pagination = generate_pagination($base_url, $total_users, $board_config['topics_per_page'], $u_start, TRUE, 'u_start');

			$template->assign_vars(array(
				'USERS_PAGINATION' => $pagination,
				'USERS_PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($u_start / $board_config['topics_per_page']) + 1), ceil($total_users / $board_config['topics_per_page'])),
			));
		}

		else
		{
			$template->assign_vars(array(
				'L_NO_OTHER_REGISTERED_IPS' => $lang['No_other_registered_ips'],
				)
			);
			$template->assign_block_vars('switch_user_admin_or_mod.switch_no_other_registered_ips', array());
		}
	}

	else
	{
		$template->assign_vars(array(
			'L_NO_OTHER_REGISTERED_IPS' => $lang['No_other_registered_ips'],
			)
		);
		$template->assign_block_vars('switch_user_admin_or_mod.switch_no_other_registered_ips', array());
	}

	// All IP addresses this user has posted from section
	$total_ips = 0;
	$sql = 'SELECT poster_ip, COUNT(*) AS postings FROM ' . POSTS_TABLE . ' WHERE poster_id = "' . $profiledata['user_id'] . '" GROUP BY poster_ip ORDER BY ' . ((SQL_LAYER == 'msaccess') ? 'COUNT(*)' : 'postings') . ' DESC';

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error: could not look up all IP addresses this user has posted from.', '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$total_ips = $total_ips + 1;
	}

	if ($total_ips > 0)
	{
		$i_start = (isset($_GET['i_start'])) ? intval($_GET['i_start']) : 0;

		$sql = 'SELECT poster_ip, COUNT(*) AS postings FROM ' . POSTS_TABLE . ' WHERE poster_id = "' . $profiledata['user_id'] . '" GROUP BY poster_ip ORDER BY ' . ((SQL_LAYER == 'msaccess') ? 'COUNT(*)' : 'postings') . " DESC LIMIT $i_start, " . $board_config['topics_per_page'];

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error: could not look up all IP addresses this user has posted from.', '', __LINE__, __FILE__, $sql);
		}

		$template->assign_block_vars('switch_user_admin_or_mod.switch_other_posted_ips', array());

			while ($row = $db->sql_fetchrow($result))
			{
				$poster_ip = decode_ip($row['poster_ip']);

				$template->assign_block_vars('switch_user_admin_or_mod.switch_other_posted_ips.ALL_IPS_POSTED_FROM', array(
					'U_POSTER_IP' => 'http://whois.sc/' . $poster_ip,
					'POSTER_IP' => $poster_ip,
					'POSTS' => $row['postings'] . ' ' . (($row['postings'] == 1) ? $lang['Post'] : $lang['Posts']),
					'U_POSTS_LINK' => append_sid(SEARCH_MG . '?mode=results&amp;search_author=' . urlencode($profiledata['username']) . '&amp;search_ip=' . $poster_ip),
				));
			}

		$base_url = PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'];
		$pagination = generate_pagination($base_url, $total_ips, $board_config['topics_per_page'], $i_start, TRUE, 'i_start');

		$template->assign_vars(array(
			'IPS_PAGINATION' => $pagination,
			'IPS_PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($i_start / $board_config['topics_per_page']) + 1), ceil($total_ips / $board_config['topics_per_page'])),
			)
		);
	}

	else
	{
		$template->assign_vars(array(
			'L_NO_OTHER_POSTED_IPS' => $lang['No_other_posted_ips'],
			)
		);
		$template->assign_block_vars('switch_user_admin_or_mod.switch_no_other_posted_ips', array());
	}

	if ($board_config['disable_logins'] == 0)
	{
		$template->assign_var('S_LOGINS_HISTORY', true);
		// All logins section
		// Obtain the total logins for this user
		$sql = 'SELECT COUNT(login_id) AS total_logins FROM ' . LOGINS_TABLE . ' WHERE login_userid = ' . $profiledata['user_id'];

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error: could not determine total logins.', '', __LINE__, __FILE__, $sql);
		}

		if (!$row = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_ERROR, 'Error: could not get the logins number.', '', __LINE__, __FILE__, $sql);
		}

		$total_logins = $row['total_logins'];

		if ($total_logins > 0)
		{
			$l_start = (isset($_GET['l_start'])) ? intval($_GET['l_start']) : 0;

			// Now get the results in groups based on how many topics per page parameter set in the admin panel
			$sql = 'SELECT * FROM ' . LOGINS_TABLE . ' WHERE login_userid = ' . $profiledata['user_id'] . " ORDER BY login_time DESC LIMIT $l_start, " . $board_config['topics_per_page'];

				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error: could not retrieve last login.', '', __LINE__, __FILE__, $sql);
				}

				while ($row = $db->sql_fetchrow($result))
				{
					$ip = decode_ip($row['login_ip']);

					$template->assign_block_vars('switch_user_admin_or_mod.USER_LOGINS', array(
						'U_IP' => 'http://whois.sc/' . $ip,
						'IP' => $ip,
						'USER_AGENT' => htmlspecialchars($row['login_user_agent']),
						'LOGIN_TIME' => create_date($userdata['user_dateformat'], $row['login_time'], $userdata['user_timezone']),
					));
				}

			$base_url = PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'];
			$pagination = generate_pagination($base_url, $total_logins, $board_config['topics_per_page'], $l_start, true, 'l_start');

			$template->assign_vars(array(
				'LOGINS_PAGINATION' => $pagination,
				'LOGINS_PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($l_start / $board_config['topics_per_page']) + 1), ceil($total_logins / $board_config['topics_per_page'])),
				)
			);
		}

		else
		{
			$template->assign_vars(array(
				'L_NO_LOGINS' => $lang['No_logins'],
				)
			);
			$template->assign_block_vars('switch_user_admin_or_mod.switch_no_logins', array());
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
// Mighty Gorgon - Full Album Pack - BEGIN
if ($album_config['show_all_in_personal_gallery'] == 1)
{
	$template->assign_block_vars('enable_view_toggle', array());
}
// Mighty Gorgon - Full Album Pack - END
//Start Quick Administrator User Options and Information MOD
if ($userdata['user_level'] == ADMIN)
{
	$template->assign_block_vars('switch_user_admin',array());
}

$sql = "SELECT * FROM " . BANLIST_TABLE . " WHERE ban_userid = " . $profiledata['user_id'] . " OR ban_email = '" . $profiledata['user_email'] . "'";

if (! ($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not look up banned status', '', __LINE__, __FILE__, $sql);
}

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
	'L_BANNED_EMAIL' => ($banned_email == '') ? $lang['User_email_not_banned'] : sprintf($lang['User_email_banned'], $profiledata['user_email']),

	'U_ADMIN_EDIT_PROFILE' => ADM . '/admin_users.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;mode=edit&amp;redirect=yes',
	'U_ADMIN_EDIT_PERMISSIONS' => ADM . '/admin_ug_auth.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;mode=user'
	)
);

//End Quick Administrator User Options and Information MOD
include(IP_ROOT_PATH . 'includes/bb_usage_stats.' . PHP_EXT);
// MG Cash MOD For IP - BEGIN
if (defined('CASH_MOD'))
{
	$cm_viewprofile->post_vars($template, $profiledata, $userdata);
}
// MG Cash MOD For IP - END
$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>