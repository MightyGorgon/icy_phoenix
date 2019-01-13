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

$settings_details = array();
$settings_details = array(
	'id' => 'sql_charge',
	'name' => '20_SQL_Charge',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	/*
	'fast_n_furious' => array(
		'lang_key' => 'IP_fast_n_furious',
		'explain' => 'IP_fast_n_furious_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),
	*/

	/*
	'db_cron' => array(
		'lang_key' => 'IP_db_cron',
		'explain' => 'IP_db_cron_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),
	*/

	'site_history' => array(
		'lang_key' => 'IP_site_history',
		'explain' => 'IP_site_history_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'active_sessions' => array(
		'lang_key' => 'IP_active_sessions',
		'explain' => 'IP_active_sessions_explain',
		'type' => 'INT',
		'default' => 0,
	),

	'global_disable_upi2db' => array(
		'lang_key' => 'IP_global_disable_upi2db',
		'explain' => 'IP_global_disable_upi2db_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'enable_own_icons' => array(
		'lang_key' => 'IP_enable_own_icons',
		'explain' => 'IP_enable_own_icons_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'show_forums_online_users' => array(
		'lang_key' => 'IP_show_forums_online_users',
		'explain' => 'IP_show_forums_online_users_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'gsearch_guests' => array(
		'lang_key' => 'IP_gsearch_guests',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'visit_counter_switch' => array(
		'lang_key' => 'IP_visit_counter_switch',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'enable_new_messages_number' => array(
		'lang_key' => 'IP_enable_new_messages_number',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'disable_likes_posts' => array(
		'lang_key' => 'IP_disable_likes_posts',
		'explain' => 'IP_disable_likes_posts_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'show_thanks_profile' => array(
		'lang_key' => 'IP_show_thanks_profile',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

/*
	'show_thanks_viewtopic' => array(
		'lang_key' => 'IP_show_thanks_viewtopic',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),
*/

	'disable_topic_view' => array(
		'lang_key' => 'IP_disable_topic_view',
		'explain' => 'IP_disable_topic_view_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'disable_referers' => array(
		'lang_key' => 'IP_disable_referers',
		'explain' => 'IP_disable_referers_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'disable_logins' => array(
		'lang_key' => 'IP_disable_logins',
		'explain' => 'IP_disable_logins_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'last_logins_n' => array(
		'lang_key' => 'IP_last_logins_n',
		'type' => 'INT',
		'default' => 10,
	),

	'index_top_posters' => array(
		'lang_key' => 'IP_index_top_posters',
		'explain' => 'IP_index_top_posters_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'index_last_msgs' => array(
		'lang_key' => 'IP_index_last_msgs',
		'explain' => 'IP_index_last_msgs_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'online_last_msgs' => array(
		'lang_key' => 'IP_online_last_msgs',
		'explain' => 'IP_online_last_msgs_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'last_msgs_n' => array(
		'lang_key' => 'IP_last_msgs_n',
		'type' => 'INT',
		'default' => 10,
	),

	'last_msgs_x' => array(
		'lang_key' => 'IP_last_msgs_x',
		'explain' => 'IP_last_msgs_x_explain',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'auto_refresh_topic_interval' => array(
		'lang_key' => 'IP_auto_refresh_viewtopic_interval',
		'explain' => 'IP_auto_refresh_viewtopic_interval_explain',
		'type' => 'INT',
		'default' => 5000,
		'values' => $this->list_yes_no,
	),

	'show_chat_online' => array(
		'lang_key' => 'IP_show_chat_online',
		'explain' => 'IP_show_chat_online_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'index_shoutbox' => array(
		'lang_key' => 'IP_index_shoutbox',
		'explain' => 'IP_index_shoutbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'online_shoutbox' => array(
		'lang_key' => 'IP_online_shoutbox',
		'explain' => 'IP_online_shoutbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'img_shoutbox' => array(
		'lang_key' => 'IP_img_shoutbox',
		'explain' => 'IP_img_shoutbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'index_birthday' => array(
		'lang_key' => 'IP_index_birthday',
		'explain' => 'IP_index_birthday_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'show_random_quote' => array(
		'lang_key' => 'IP_show_random_quote',
		'explain' => 'IP_show_random_quote_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

);

$this->init_config($settings_details, $settings_data);

?>