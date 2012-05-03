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
if (empty($is_allowed))
{
	return;
}

$settings_details = array();
$settings_details = array(
	'id' => 'security_settings',
	'name' => '80_Security',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'admin_protect' => array(
		'lang_key' => 'IP_admin_protect',
		'explain' => 'IP_admin_protect_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'ip_admins_only' => array(
		'lang_key' => 'IP_ip_admins_only',
		'explain' => 'IP_ip_admins_only_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'db_log_actions' => array(
		'lang_key' => 'IP_db_log_actions',
		'explain' => 'IP_db_log_actions_explain',
		'type' => 'LIST_RADIO_BR',
		'default' => 1,
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
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'write_errors_log' => array(
		'lang_key' => 'IP_write_errors_log',
		'explain' => 'IP_write_errors_log_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'write_digests_log' => array(
		'lang_key' => 'IP_write_digests_log',
		'explain' => 'IP_write_digests_log_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'logs_path' => array(
		'lang_key' => 'IP_logs_path',
		'explain' => 'IP_logs_path_explain',
		'type' => 'VARCHAR',
		'default' => 'logs',
	),

);

$this->init_config($settings_details, $settings_data);

?>