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

		$ajax_chat_page = !empty($config['ajax_chat_link_type']) ? CMS_PAGE_AJAX_CHAT : CMS_PAGE_AJAX_SHOUTBOX;
		$ajax_chat_link = !empty($config['ajax_chat_link_type']) ? (append_sid($ajax_chat_page) . '" target="_chat') : ('#" onclick="window.open(\'' . append_sid($ajax_chat_page) . '\', \'_chat\', \'width=720,height=600,resizable=yes\'); return false;');
		$online_time = 300;
		$cache_time = 600;

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

		$online_users_text = '';
		$switch_users_online = false;
		if (empty($online_users_array['tot']))
		{
			$online_users_text = $lang['CHAT_NO_USERS'];
		}
		else
		{
			foreach ($online_users_array['users'] as $k => $online_user_data)
			{
				$online_users_sort[$k] = $online_user_data['username_clean'];
			}
			asort($online_users_sort);
			foreach ($online_users_sort as $k => $v)
			{
				$cu = $online_users_array['users'][$k];
				$io = $cu['user_allow_viewonline'] ? true : false;
				$user_link = '';
				if ($user->data['session_logged_in'] && ($user->data['user_id'] != $cu['user_id']))
				{
					$chat_room = 'chat_room=' . (min($user->data['user_id'], $cu['user_id']) . '|' . max($user->data['user_id'], $cu['user_id']));
					$chat_link = append_sid($ajax_chat_page . '?' . $ajax_chat_room);
					$user_link = !empty($config['ajax_chat_link_type']) ? ($chat_link . '" target="_chat') : ('#" onclick="window.open(\'' . $chat_link . '\', \'_chat\', \'width=720,height=600,resizable=yes\'); return false;');
				}
				$online_users_text .= (empty($online_users_text) ? '' : ', ') . ($io ? '' : '<em>') . colorize_username($cu['user_id'], $cu['username'], $cu['user_color'], $cu['user_active'], false, false, false, false, $user_link) . ($io ? '' : '</em>');
			}
			$switch_users_online = !empty($online_users_text) ? true : false;
		}

		$template->assign_vars(array(
			'S_USERS_ONLINE' => $switch_users_online,
			'B_ONLINE_USERS_TEXT' => $online_users_text,
			'B_U_CHAT' => $ajax_chat_link,
			)
		);
	}
}

cms_block_online_users_chat();

?>