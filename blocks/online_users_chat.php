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

if(!function_exists('cms_block_online_users_chat'))
{
	function cms_block_online_users_chat()
	{
		global $db, $cache, $config, $template, $theme, $images, $user, $lang, $table_prefix;

		$online_time = 600;
		$cache_time = 300;

		// Initialize data
		$online_users_array = array('reg' => 0, 'guests' => 0, 'tot' => 0, 'list' => '', 'text' => '', 'users' => array(), 'user_ids' => array());

		if (!function_exists('get_online_users'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_online.' . PHP_EXT);
		}

		$online_users_chat = get_online_users('chat', true, false, '', $online_time, $cache_time);

		foreach ($online_users_chat as $online_user_chat_data)
		{
			$uid = $online_user_chat_data['user_id'];
			if (!in_array($uid, $online_users_array['user_ids']))
			{
				$online_users_array['user_ids'][] = $uid;
				$online_users_array['users'][$uid] = array(
					'user_id' => $online_user_chat_data['user_id'],
					'username' => $online_user_chat_data['username'],
					'username_clean' => $online_user_chat_data['username_clean'],
					'user_color' => $online_user_chat_data['user_color'],
					'user_active' => $online_user_chat_data['user_active'],
					'user_allow_viewonline' => 1,
				);
			}
		}

		$online_users = get_online_users('site', true, false, '', $online_time, $cache_time);

		foreach ($online_users as $online_user_data)
		{
			$uid = $online_user_data['user_id'];
			if (!in_array($uid, $online_users_array['user_ids']))
			{
				if ($online_user_data['user_allow_viewonline'] || ($user->data['user_level'] == ADMIN) || ($user->data['user_id'] == $online_user_data['user_id']))
				{
					$online_users_array['user_ids'][] = $uid;
					$io = $online_user_data['user_allow_viewonline'] ? true : false;
					$online_users_array['users'][$uid] = array(
						'user_id' => $online_user_data['user_id'],
						'username' => $online_user_data['username'],
						'username_clean' => $online_user_data['username_clean'],
						'user_color' => $online_user_data['user_color'],
						'user_active' => $online_user_data['user_active'],
						'user_allow_viewonline' => $online_user_data['user_allow_viewonline'],
					);
				}
			}
		}

		$online_users_array['tot'] = sizeof($online_users_array['users']);
		$online_users_array['text'] = empty($online_users_array['tot']) ? $lang['Reg_users_zero_total'] : (($online_users_array['tot'] == 1) ? $lang['Reg_user_total'] : (sprintf($lang['Reg_users_total'], $online_users_array['tot'])));

		if (empty($online_users_array['tot']))
		{
			$online_users_text = $lang['None'];
		}
		else
		{
			$online_users_text = '';
			foreach ($online_users_array['users'] as $k => $online_user_data)
			{
				$online_users_sort[$k] = $online_user_data['username_clean'];
			}
			asort($online_users_sort);
			foreach ($online_users_sort as $k => $v)
			{
				$cu = $online_users_array['users'][$k];
				$io = $cu['user_allow_viewonline'] ? true : false;
				$online_users_text .= (empty($online_users_text) ? '' : ', ') . ($io ? '' : '<em>') . colorize_username($cu['user_id'], $cu['username'], $cu['user_color'], $cu['user_active'], true) . ($io ? '' : '</em>');
			}
		}





// We need to use $alt_link_url to specify the link to connect to chat!
//function colorize_username($user_id, $username = '', $user_color = '', $user_active = true, $no_profile = false, $get_only_color_style = false, $from_db = false, $force_cache = false, $alt_link_url = '')
//	$user_link_url = !empty($alt_link_url) ? str_replace('$USER_ID', $user_id, $alt_link_url) : ((defined('USER_LINK_URL_OVERRIDE')) ? str_replace('$USER_ID', $user_id, USER_LINK_URL_OVERRIDE) : (CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id));





		$template->assign_vars(array(
			'B_ONLINE_USERS_TEXT' => $online_users_text,
			'B_U_CHAT' => append_sid(CMS_PAGE_AJAX_CHAT),
			)
		);
	}
}

cms_block_online_users_chat();

?>