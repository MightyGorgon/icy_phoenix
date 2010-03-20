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
	'id' => 'server_settings',
	'name' => '05_Server_Settings',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'server_name' => array(
		'lang_key' => 'Server_name',
		'explain' => 'Server_name_explain',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'server_port' => array(
		'lang_key' => 'Server_port',
		'explain' => 'Server_port_explain',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'script_path' => array(
		'lang_key' => 'Script_path',
		'explain' => 'Script_path_explain',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'sitename' => array(
		'lang_key' => 'Site_name',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'site_desc' => array(
		'lang_key' => 'Site_desc',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'board_disable' => array(
		'lang_key' => 'Board_disable',
		'explain' => 'Board_disable_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'board_disable_mess_st' => array(
		'lang_key' => 'board_disable_message',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'board_disable_message' => array(
		'lang_key' => 'board_disable_message_texte',
		'type' => 'HTMLVARCHAR',
		'default' => '',
		),

	'gzip_compress' => array(
		'lang_key' => 'Enable_gzip',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'disable_registration_ip_check' => array(
		'lang_key' => 'Disable_Registration_IP_Check',
		'explain' => 'Disable_Registration_IP_Check_Explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'registration_status' => array(
		'lang_key' => 'registration_status',
		'explain' => 'registration_status_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'registration_closed' => array(
		'lang_key' => 'registration_closed',
		'explain' => 'registration_closed_explain',
		'type' => 'HTMLVARCHAR',
		'default' => '',
		),

	'require_activation' => array(
		'lang_key' => 'Acct_activation',
		'type' => 'LIST_RADIO',
		'default' => 'Acc_User',
		'values' => array(
			'Acc_None' => USER_ACTIVATION_NONE,
			'Acc_User' => USER_ACTIVATION_SELF,
			'Acc_Admin' => USER_ACTIVATION_ADMIN,
			),
		),

	'enable_confirm' => array(
		'lang_key' => 'Visual_confirm',
		'explain' => 'Visual_confirm_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'use_captcha' => array(
		'lang_key' => 'Use_Captcha',
		'explain' => 'Use_Captcha_Explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'allow_autologin' => array(
		'lang_key' => 'Allow_autologin',
		'explain' => 'Allow_autologin_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'max_autologin_time' => array(
		'lang_key' => 'Autologin_time',
		'explain' => 'Autologin_time_explain',
		'type' => 'SMALLINT',
		'default' => 0,
		),

	'max_login_attempts' => array(
		'lang_key' => 'Max_login_attempts',
		'explain' => 'Max_login_attempts_explain',
		'type' => 'SMALLINT',
		'default' => 5,
		),

	'login_reset_time' => array(
		'lang_key' => 'Login_reset_time',
		'explain' => 'Login_reset_time_explain',
		'type' => 'SMALLINT',
		'default' => 30,
		),

	'cookie_domain' => array(
		'lang_key' => 'Cookie_domain',
		'separator' => 'Cookie_settings',
		'separator_explain' => 'Cookie_settings_explain',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'cookie_name' => array(
		'lang_key' => 'Cookie_name',
		'type' => 'TINYTEXT',
		'default' => '',
		),

	'cookie_path' => array(
		'lang_key' => 'Cookie_path',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'cookie_secure' => array(
		'lang_key' => 'Cookie_secure',
		'explain' => 'Cookie_secure_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Disabled',
		'values' => array(
			'Disabled' => 0,
			'Enabled' => 1,
			),
		),

	'session_length' => array(
		'lang_key' => 'Session_length',
		'type' => 'SMALLINT',
		'default' => 3600,
		),

	'coppa_fax' => array(
		'lang_key' => 'COPPA_fax',
		'separator' => 'COPPA_settings',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'coppa_mail' => array(
		'lang_key' => 'COPPA_mail',
		'explain' => 'COPPA_mail_explain',
		'type' => 'TEXT',
		'default' => '',
		),

	'board_email_form' => array(
		'lang_key' => 'Board_email_form',
		'explain' => 'Board_email_form_explain',
		'separator' => 'Email_settings',
		'type' => 'LIST_RADIO',
		'default' => 'Enabled',
		'values' => array(
			'Enabled' => 1,
			'Disabled' => 0,
			),
		),

	'board_email' => array(
		'lang_key' => 'Admin_email',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'board_email_sig' => array(
		'lang_key' => 'Email_sig',
		'explain' => 'Email_sig_explain',
		'type' => 'HTMLTEXT',
		'default' => '',
		),

	'smtp_delivery' => array(
		'lang_key' => 'Use_SMTP',
		'explain' => 'Use_SMTP_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'smtp_host' => array(
		'lang_key' => 'SMTP_server',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'smtp_username' => array(
		'lang_key' => 'SMTP_username',
		'explain' => 'SMTP_username_explain',
		'type' => 'VARCHAR',
		'default' => '',
		),

	'smtp_password' => array(
		'lang_key' => 'SMTP_password',
		'explain' => 'SMTP_password_explain',
		'type' => 'PASSWORD',
		'default' => '',
		),

);

$this->init_config($settings_details, $settings_data);

?>