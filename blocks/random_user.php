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
* @Extra credits for this file
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_random_user'))
{
	function cms_block_random_user()
	{
		global $db, $cache, $config, $template, $images, $user, $lang, $block_id, $cms_config_vars;

		// Mighty Gorgon - Multiple Ranks - BEGIN
		@include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
		$ranks_array = $cache->obtain_ranks(false);
		// Mighty Gorgon - Multiple Ranks - END

		$sql = "SELECT u.*
			FROM " . USERS_TABLE . " u
			WHERE (u.user_id <> " . ANONYMOUS . ")
			ORDER BY RAND()
			LIMIT 1";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$user_id = $row['user_id'];
			$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
			$username_simple = $row['username'];
			$user_pics = $row['user_personal_pics_count'];
			$posts = ($row['user_posts']) ? $row['user_posts'] : 0;
			$poster_avatar = user_get_avatar($row['user_id'], $row['user_level'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);
			$poster_posts = ($row['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $row['user_posts'] : '';

			$poster_from = ($row['user_from'] && $row['user_id'] != ANONYMOUS) ? $lang['Location'] . ': ' . $row['user_from'] : '';

			$poster_from_flag = ($row['user_from_flag'] && $row['user_id'] != ANONYMOUS) ? '<img src="images/flags/' . $row['user_from_flag'] . '" alt="' . $row['user_from_flag'] . '" title="' . $row['user_from'] . '" />' : '';

			$poster_joined = ($row['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $config['board_timezone']) : '';

			$poster_age = '';
			$poster_birthday = '';
			if ($row['user_birthday'] != 999999)
			{
				$this_year = create_date('Y', time(), $config['board_timezone']);
				$this_date = create_date('md', time(), $config['board_timezone']);
				$poster_birthday = realdate('d/m/Y', $row['user_birthday']);
				$poster_age = $this_year - realdate('Y', $row['user_birthday']);
				if ($this_date < $poster_birthday)
				{
					$poster_age--;
				}
				$poster_age = $lang['Age'] . ': ' . $poster_age . ' (' . $poster_birthday . ')<br />';
			}

			// Mighty Gorgon - Multiple Ranks - BEGIN
			$user_ranks = generate_ranks($row, $ranks_array);
			if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html']  == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
			{
				$user_ranks['rank_01_html'] = '&nbsp;';
			}
			// Mighty Gorgon - Multiple Ranks - END

			$profile_url = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);
			$profile_img = '<a href="' . $profile_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
			$profile = '<a href="' . $profile_url . '">' . $lang['Profile'] . '</a>';
			$profile_link = '<a href="' . $profile_url . '">' . $lang['SEE_MORE_DETAILS'] . '</a>';

			$pm_url = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $user_id);
			$pm_img = '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
			$pm = '<a href="' . $pm_url . '">' . $lang['PM'] . '</a>';

			// Start add - Gender MOD
			switch ($row['user_gender'])
			{
				case 1:
					$gender_image = '<img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'].  ': ' . $lang['Male'] . '" title="' . $lang['Gender'] . ': ' . $lang['Male'] . '" />';
					break;
				case 2:
					$gender_image = '<img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender']. ': ' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" />';
					break;
				default:
					$gender_image = '';
			}
			// End add - Gender MOD

			if (!empty($row['user_viewemail']) || ($user->data['user_level'] == ADMIN))
			{
				$email_uri = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL .'=' . $user_id) : 'mailto:' . $row['user_email'];

				$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
				$email = '<a href="' . $email_uri . '">' . $lang['Email'] . '</a>';
			}
			else
			{
				$email_img = '';
				$email = '';
			}

			$www_img = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
			$www = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Website'] . '</a>' : '';

			$im_links_array = array(
				'chat' => 'id',
				'aim' => 'aim',
				'facebook' => 'facebook',
				'flickr' => 'flickr',
				'googleplus' => 'googleplus',
				'icq' => 'icq',
				'jabber' => 'jabber',
				'linkedin' => 'linkedin',
				'msn' => 'msnm',
				'skype' => 'skype',
				'twitter' => 'twitter',
				'yahoo' => 'yim',
				'youtube' => 'youtube',
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
			}

			$aim_img = $all_ims['aim']['img'];
			$aim = $all_ims['aim']['plain'];
			$aim_url = $all_ims['aim']['url'];

			$icq_status_img = (!empty($row['user_icq'])) ? '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&amp;img=5" width="18" height="18" /></a>' : '';
			$icq_img = $all_ims['icq']['img'];
			$icq = $all_ims['icq']['plain'];
			$icq_url = $all_ims['icq']['url'];

			$msn_img = $all_ims['msn']['img'];
			$msn = $all_ims['msn']['plain'];
			$msn_url = $all_ims['msn']['url'];

			$skype_img = $all_ims['skype']['img'];
			$skype = $all_ims['skype']['plain'];
			$skype_url = $all_ims['skype']['url'];

			$yahoo_img = $all_ims['yahoo']['img'];
			$yahoo = $all_ims['yahoo']['plain'];
			$yahoo_url = $all_ims['yahoo']['url'];

			if ($row['user_personal_pics_count'] > 0)
			{
				$album_img = ($row['user_personal_pics_count']) ? '<a href="album.' . PHP_EXT . '?user_id=' . $row['user_id'] . '"><img src="' . $images['icon_album'] . '" alt="' . $lang['Show_Personal_Gallery'] . '" title="' . $lang['Show_Personal_Gallery'] . '" /></a>' : '';
				$album = ($row['user_personal_pics_count']) ? '<a href="album.' . PHP_EXT . '?user_id=' . $row['user_id'] . '">' . $lang['Show_Personal_Gallery'] . '</a>' : '';
			}
			else
			{
				$album_img = '';
				$album = '';
			}

			// ONLINE / OFFLINE - BEGIN
			if (($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $user_id) || $row['user_allow_viewonline'])
			{
				if ($row['user_session_time'] >= (time() - $config['online_time']))
				{
					$online_status_img = '<a href="' . append_sid(CMS_PAGE_VIEWONLINE) . '"><img src="' . $images['icon_online2'] . '" alt="' . $lang['Online'] .'" title="' . $lang['Online'] .'" /></a>';
				}
				else
				{
					$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] .'" title="' . $lang['Offline'] .'" />';
				}
			}
			else
			{
				$online_status_img = '<a href="' . append_sid(CMS_PAGE_VIEWONLINE) . '"><img src="' . $images['icon_hidden2'] . '" alt="' . $lang['Hidden'] .'" title="' . $lang['Hidden'] .'" /></a>';
			}
			// ONLINE / OFFLINE - END

			$template->assign_block_vars('random_user', array(
				'L_POSTS' => $lang['Posts'],

				'USERNAME' => $username,
				'POSTS' => $posts,
				'U_VIEWPOSTER' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
				'U_VIEWPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode(ip_utf8_decode($username_simple)) . '&amp;showresults=posts'),
				'POSTER_AGE' => $poster_age,
				'POSTER_BIRTHDAY' => $poster_birthday,
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
				'POSTER_GENDER' => $gender_image,
				'POSTER_JOINED' => $poster_joined,
				'POSTER_POSTS' => $poster_posts,
				'POSTER_FROM' => $poster_from,
				'POSTER_FROM_FLAG' => $poster_from_flag,
				'POSTER_AVATAR' => $poster_avatar,

				'PROFILE_IMG' => $profile_img,
				'PROFILE' => $profile,
				'PROFILE_LINK' => $profile_link,
				'PM_IMG' => $pm_img,
				'PM' => $pm,
				'EMAIL_IMG' => (!$user->data['session_logged_in']) ? '' : $email_img,
				'EMAIL' => $email,
				'WWW_IMG' => $www_img,
				'WWW' => $www,
				'ICQ_STATUS_IMG' => $icq_status_img,
				'ICQ_IMG' => $icq_img,
				'ICQ' => $icq,
				'AIM_IMG' => $aim_img,
				'AIM' => $aim,
				'MSN_IMG' => $msn_img,
				'MSN' => $msn,
				'YIM_IMG' => $yahoo_img,
				'YIM' => $yahoo,
				'SKYPE_IMG' => $skype_img,
				'SKYPE' => $skype,
				'POSTER_ONLINE_STATUS_IMG' => $online_status_img,
				)
			);
		}
		$db->sql_freeresult($result);
	}
}

cms_block_random_user();

?>