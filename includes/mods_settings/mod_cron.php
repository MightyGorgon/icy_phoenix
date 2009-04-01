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
$mod_name = '90_Cron';

$config_fields = array(

	'cron_global_switch' => array(
		'lang_key' => 'IP_cron_global_switch',
		'explain' => 'IP_cron_global_switch_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'cron_files_interval' => array(
		'lang_key' => 'IP_cron_files_interval',
		'explain' => 'IP_cron_files_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),

	'cron_database_interval' => array(
		'lang_key' => 'IP_cron_database_interval',
		'explain' => 'IP_cron_database_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),

	'cron_cache_interval' => array(
		'lang_key' => 'IP_cron_cache_interval',
		'explain' => 'IP_cron_cache_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),

	'cron_sql_interval' => array(
		'lang_key' => 'IP_cron_sql_interval',
		'explain' => 'IP_cron_sql_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),

	'cron_users_interval' => array(
		'lang_key' => 'IP_cron_users_interval',
		'explain' => 'IP_cron_users_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),

	'cron_topics_interval' => array(
		'lang_key' => 'IP_cron_topics_interval',
		'explain' => 'IP_cron_topics_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),

/*
	'cron_sessions_interval' => array(
		'lang_key' => 'IP_cron_sessions_interval',
		'explain' => 'IP_cron_sessions_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_time_intervals,
		),
*/

);

init_board_config($mod_name, $config_fields);

?>