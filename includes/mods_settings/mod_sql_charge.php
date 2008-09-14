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

include_once(IP_ROOT_PATH . 'includes/functions_mods_settings.' . PHP_EXT);
$mod_name = '20_SQL_Charge';

$config_fields = array(

	/*
	'fast_n_furious' => array(
		'lang_key' => 'IP_fast_n_furious',
		'explain' => 'IP_fast_n_furious_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),
	*/

	'db_cron' => array(
		'lang_key' => 'IP_db_cron',
		'explain' => 'IP_db_cron_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'site_history' => array(
		'lang_key' => 'IP_site_history',
		'explain' => 'IP_site_history_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'global_disable_upi2db' => array(
		'lang_key' => 'IP_global_disable_upi2db',
		'explain' => 'IP_global_disable_upi2db_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'enable_own_icons' => array(
		'lang_key' => 'IP_enable_own_icons',
		'explain' => 'IP_enable_own_icons_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'show_forums_online_users' => array(
		'lang_key' => 'IP_show_forums_online_users',
		'explain' => 'IP_show_forums_online_users_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'google_bot_detector' => array(
		'lang_key' => 'IP_google_bot_detector',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'visit_counter_switch' => array(
		'lang_key' => 'IP_visit_counter_switch',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'enable_new_messages_number' => array(
		'lang_key' => 'IP_enable_new_messages_number',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'disable_thanks_topics' => array(
		'lang_key' => 'IP_disable_thanks_topics',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'show_thanks_profile' => array(
		'lang_key' => 'IP_show_thanks_profile',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'show_thanks_viewtopic' => array(
		'lang_key' => 'IP_show_thanks_viewtopic',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'disable_topic_view' => array(
		'lang_key' => 'IP_disable_topic_view',
		'explain' => 'IP_disable_topic_view_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'disable_referrers' => array(
		'lang_key' => 'IP_disable_referrers',
		'explain' => 'IP_disable_referrers_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'disable_logins' => array(
		'lang_key' => 'IP_disable_logins',
		'explain' => 'IP_disable_logins_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'last_logins_n' => array(
		'lang_key' => 'IP_last_logins_n',
		'type' => 'VARCHAR',
		'default' => '10',
		),

	'index_top_posters' => array(
		'lang_key' => 'IP_index_top_posters',
		'explain' => 'IP_index_top_posters_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'index_last_msgs' => array(
		'lang_key' => 'IP_index_last_msgs',
		'explain' => 'IP_index_last_msgs_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'online_last_msgs' => array(
		'lang_key' => 'IP_online_last_msgs',
		'explain' => 'IP_online_last_msgs_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'last_msgs_n' => array(
		'lang_key' => 'IP_last_msgs_n',
		'type' => 'VARCHAR',
		'default' => '10',
		),

	'last_msgs_x' => array(
		'lang_key' => 'IP_last_msgs_x',
		'explain' => 'IP_last_msgs_x_explain',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'show_chat_online' => array(
		'lang_key' => 'IP_show_chat_online',
		'explain' => 'IP_show_chat_online_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'index_shoutbox' => array(
		'lang_key' => 'IP_index_shoutbox',
		'explain' => 'IP_index_shoutbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'online_shoutbox' => array(
		'lang_key' => 'IP_online_shoutbox',
		'explain' => 'IP_online_shoutbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'img_shoutbox' => array(
		'lang_key' => 'IP_img_shoutbox',
		'explain' => 'IP_img_shoutbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'index_links' => array(
		'lang_key' => 'IP_index_links',
		'explain' => 'IP_index_links_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'index_birthday' => array(
		'lang_key' => 'IP_index_birthday',
		'explain' => 'IP_index_birthday_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'show_random_quote' => array(
		'lang_key' => 'IP_show_random_quote',
		'explain' => 'IP_show_random_quote_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

);

init_board_config($mod_name, $config_fields);

?>