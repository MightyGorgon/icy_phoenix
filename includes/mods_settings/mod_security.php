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

if (!defined('BOARD_CONFIG'))
{
	return;
}

$is_allowed = check_acp_module_access();
if ($is_allowed == false)
{
	return;
}

@include_once(IP_ROOT_PATH . 'includes/functions_mods_settings.' . PHP_EXT);
$mod_name = '80_Security';

$config_fields = array(

	'admin_protect' => array(
		'lang_key' => 'IP_admin_protect',
		'explain' => 'IP_admin_protect_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'db_log_actions' => array(
		'lang_key' => 'IP_db_log_actions',
		'explain' => 'IP_db_log_actions_explain',
		'type' => 'LIST_RADIO_BR',
		'default' => 'Yes',
		'values' => array(
			'DB_LOG_ALL' => 2,
			'Yes' => 1,
			'No' => 0,
			),
		),

	'mg_log_actions' => array(
		'lang_key' => 'IP_mg_log_actions',
		'explain' => 'IP_mg_log_actions_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'write_errors_log' => array(
		'lang_key' => 'IP_write_errors_log',
		'explain' => 'IP_write_errors_log_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'write_digests_log' => array(
		'lang_key' => 'IP_write_digests_log',
		'explain' => 'IP_write_digests_log_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'logs_path' => array(
		'lang_key' => 'IP_logs_path',
		'explain' => 'IP_logs_path_explain',
		'type' => 'VARCHAR',
		'default' => 'logs',
		),

);

init_board_config($mod_name, $config_fields);

?>