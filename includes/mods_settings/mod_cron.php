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

$list_cron_intervals = array(
	'Disabled' => 0,
	'15M' => 900,
	'30M' => 1800,
	'1H' => 3600,
	'2H' => 7200,
	'3H' => 10800,
	'6H' => 21600,
	'12H' => 43200,
	'1D' => 86400,
	'3D' => 259200,
	'7D' => 604800,
	'14D' => 1209600,
	'30D' => 2592000,
);

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
		'values' => $list_cron_intervals,
		),

	'cron_database_interval' => array(
		'lang_key' => 'IP_cron_database_interval',
		'explain' => 'IP_cron_database_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_cron_intervals,
		),

	'cron_cache_interval' => array(
		'lang_key' => 'IP_cron_cache_interval',
		'explain' => 'IP_cron_cache_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_cron_intervals,
		),

	'cron_sql_interval' => array(
		'lang_key' => 'IP_cron_sql_interval',
		'explain' => 'IP_cron_sql_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_cron_intervals,
		),

	'cron_users_interval' => array(
		'lang_key' => 'IP_cron_users_interval',
		'explain' => 'IP_cron_users_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_cron_intervals,
		),

	'cron_topics_interval' => array(
		'lang_key' => 'IP_cron_topics_interval',
		'explain' => 'IP_cron_topics_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_cron_intervals,
		),

/*
	'cron_sessions_interval' => array(
		'lang_key' => 'IP_cron_sessions_interval',
		'explain' => 'IP_cron_sessions_interval_explain',
		'type' => 'LIST_DROP',
		'default' => 'Disabled',
		'values' => $list_cron_intervals,
		),
*/

);

init_board_config($mod_name, $config_fields);

?>