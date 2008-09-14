<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['100_Server_Configuration'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

// Pull all config data
$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($_POST['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

		$new[$config_name] = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $default_config[$config_name];

		if ($config_name == 'cookie_name')
		{
			$new['cookie_name'] = str_replace('.', '_', $new['cookie_name']);
		}

		// Attempt to prevent a common mistake with this value,
		// http:// is the protocol and not part of the server name
		if ($config_name == 'server_name')
		{
			$new['server_name'] = str_replace('http://', '', $new['server_name']);
		}

		if( isset($_POST['submit']) )
		{
			$sql = 'UPDATE ' . CONFIG_TABLE . ' SET config_value=\'' . $_POST['message_board_disable_text'] . '\' WHERE config_name = \'board_disable_message\'';
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($_POST['submit']) )
	{
		$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_board_server.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}


$disable_board_yes = ( $new['board_disable'] ) ? 'checked="checked"' : '';
$disable_board_no = ( !$new['board_disable'] ) ? 'checked="checked"' : '';

$message_disable_board_yes = ( $new['board_disable_mess_st'] ) ? 'checked="checked"' : '';
$message_disable_board_no = ( !$new['board_disable_mess_st'] ) ? 'checked="checked"' : '';

$cookie_secure_yes = ( $new['cookie_secure'] ) ? 'checked="checked"' : '';
$cookie_secure_no = ( !$new['cookie_secure'] ) ? 'checked="checked"' : '';

$registration_status_yes = ( $new['registration_status'] ) ? 'checked="checked"' : '';
$registration_status_no = ( !$new['registration_status'] ) ? 'checked="checked"' : '';

$disable_registration_ip_check_yes = ( $new['disable_registration_ip_check'] ) ? 'checked="checked"' : '';
$disable_registration_ip_check_no = ( !$new['disable_registration_ip_check'] ) ? 'checked="checked"' : '';

$activation_none = ( $new['require_activation'] == USER_ACTIVATION_NONE ) ? 'checked="checked"' : '';
$activation_user = ( $new['require_activation'] == USER_ACTIVATION_SELF ) ? 'checked="checked"' : '';
$activation_admin = ( $new['require_activation'] == USER_ACTIVATION_ADMIN ) ? 'checked="checked"' : '';

$confirm_yes = ($new['enable_confirm']) ? 'checked="checked"' : '';
$confirm_no = (!$new['enable_confirm']) ? 'checked="checked"' : '';
$use_captcha_yes = ($new['use_captcha']) ? 'checked="checked"' : '';
$use_captcha_no = (!$new['use_captcha']) ? 'checked="checked"' : '';

$allow_autologin_yes = ($new['allow_autologin']) ? 'checked="checked"' : '';
$allow_autologin_no = (!$new['allow_autologin']) ? 'checked="checked"' : '';

$board_email_form_yes = ( $new['board_email_form'] ) ? 'checked="checked"' : '';
$board_email_form_no = ( !$new['board_email_form'] ) ? 'checked="checked"' : '';

$gzip_yes = ( $new['gzip_compress'] ) ? 'checked="checked"' : '';
$gzip_no = ( !$new['gzip_compress'] ) ? 'checked="checked"' : '';

$prune_yes = ( $new['prune_enable'] ) ? 'checked="checked"' : '';
$prune_no = ( !$new['prune_enable'] ) ? 'checked="checked"' : '';

$smtp_yes = ( $new['smtp_delivery'] ) ? 'checked="checked"' : '';
$smtp_no = ( !$new['smtp_delivery'] ) ? 'checked="checked"' : '';

$template->set_filenames(array('body' => ADM_TPL . 'board_config_server_body.tpl'));


// Escape any quotes in the site description for proper display in the text box on the admin page
$new['site_desc'] = str_replace('"', '&quot;', $new['site_desc']);
$new['sitename'] = str_replace('"', '&quot;', strip_tags($new['sitename']));
$new['registration_closed'] = str_replace('"', '&quot;', $new['registration_closed']);
$new['sig_line'] = str_replace('"', '&quot;', $new['sig_line']);


$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board_server.' . PHP_EXT),
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_CONFIGURATION_TITLE' => $lang['General_Config'],
	'L_CONFIGURATION_EXPLAIN' => $lang['Config_explain'],
	'L_GENERAL_SETTINGS' => $lang['General_settings'],
	'L_SERVER_SETTINGS' => $lang['Server_Cookies'],
	'L_SERVER_NAME' => $lang['Server_name'],
	'L_SERVER_NAME_EXPLAIN' => $lang['Server_name_explain'],
	'L_SERVER_PORT' => $lang['Server_port'],
	'L_SERVER_PORT_EXPLAIN' => $lang['Server_port_explain'],
	'L_SCRIPT_PATH' => $lang['Script_path'],
	'L_SCRIPT_PATH_EXPLAIN' => $lang['Script_path_explain'],
	'L_SITE_NAME' => $lang['Site_name'],
	'L_SITE_DESCRIPTION' => $lang['Site_desc'],
	'L_DISABLE_BOARD' => $lang['Board_disable'],
	'L_DISABLE_BOARD_EXPLAIN' => $lang['Board_disable_explain'],
	'L_REGISTRATION_STATUS' => $lang['registration_status'],
	'L_REGISTRATION_STATUS_EXPLAIN' => $lang['registration_status_explain'],
	'L_REGISTRATION_CLOSED' => $lang['registration_closed'],
	'L_REGISTRATION_CLOSED_EXPLAIN' => $lang['registration_closed_explain'],
	'L_DISABLE_REGISTRATION_IP_CHECK' => $lang['Disable_Registration_IP_Check'],
	'L_DISABLE_REGISTRATION_IP_CHECK_EXPLAIN' => $lang['Disable_Registration_IP_Check_Explain'],
	'L_MESSAGE_DISABLE_BOARD' => $lang['board_disable_message'],
	'L_MESSAGE_DISABLE_BOARD_TEXT' => $lang['board_disable_message_texte'],
	'L_ACCT_ACTIVATION' => $lang['Acct_activation'],
	'L_NONE' => $lang['Acc_None'],
	'L_USER' => $lang['Acc_User'],
	'L_ADMIN' => $lang['Acc_Admin'],
	'L_VISUAL_CONFIRM' => $lang['Visual_confirm'],
	'L_VISUAL_CONFIRM_EXPLAIN' => $lang['Visual_confirm_explain'],
	'L_USE_CAPTCHA' => $lang['Use_Captcha'],
	'L_USE_CAPTCHA_EXPLAIN' => $lang['Use_Captcha_Explain'],
	'L_ALLOW_AUTOLOGIN' => $lang['Allow_autologin'],
	'L_ALLOW_AUTOLOGIN_EXPLAIN' => $lang['Allow_autologin_explain'],
	'L_AUTOLOGIN_TIME' => $lang['Autologin_time'],
	'L_AUTOLOGIN_TIME_EXPLAIN' => $lang['Autologin_time_explain'],
	'L_COOKIE_SETTINGS' => $lang['Cookie_settings'],
	'L_COOKIE_SETTINGS_EXPLAIN' => $lang['Cookie_settings_explain'],
	'L_COOKIE_DOMAIN' => $lang['Cookie_domain'],
	'L_COOKIE_NAME' => $lang['Cookie_name'],
	'L_COOKIE_PATH' => $lang['Cookie_path'],
	'L_COOKIE_SECURE' => $lang['Cookie_secure'],
	'L_COOKIE_SECURE_EXPLAIN' => $lang['Cookie_secure_explain'],
	'L_SESSION_LENGTH' => $lang['Session_length'],
	'L_PRIVATE_MESSAGING' => $lang['Private_Messaging'],
	'L_ENABLED' => $lang['Enabled'],
	'L_DISABLED' => $lang['Disabled'],
	'L_MAX_LOGIN_ATTEMPTS'			=> $lang['Max_login_attempts'],
	'L_MAX_LOGIN_ATTEMPTS_EXPLAIN'	=> $lang['Max_login_attempts_explain'],
	'L_LOGIN_RESET_TIME'			=> $lang['Login_reset_time'],
	'L_LOGIN_RESET_TIME_EXPLAIN'	=> $lang['Login_reset_time_explain'],
	'MAX_LOGIN_ATTEMPTS'			=> $new['max_login_attempts'],
	'LOGIN_RESET_TIME'				=> $new['login_reset_time'],
	'L_BOARD_EMAIL_FORM' => $lang['Board_email_form'],
	'L_BOARD_EMAIL_FORM_EXPLAIN' => $lang['Board_email_form_explain'],
	'L_ENABLE_GZIP' => $lang['Enable_gzip'],
	'L_COPPA_SETTINGS' => $lang['COPPA_settings'],
	'L_COPPA_FAX' => $lang['COPPA_fax'],
	'L_COPPA_MAIL' => $lang['COPPA_mail'],
	'L_COPPA_MAIL_EXPLAIN' => $lang['COPPA_mail_explain'],
	'L_EMAIL_SETTINGS' => $lang['Email_settings'],
	'L_ADMIN_EMAIL' => $lang['Admin_email'],
	'L_EMAIL_SIG' => $lang['Email_sig'],
	'L_EMAIL_SIG_EXPLAIN' => $lang['Email_sig_explain'],
	'L_USE_SMTP' => $lang['Use_SMTP'],
	'L_USE_SMTP_EXPLAIN' => $lang['Use_SMTP_explain'],
	'L_SMTP_SERVER' => $lang['SMTP_server'],
	'L_SMTP_USERNAME' => $lang['SMTP_username'],
	'L_SMTP_USERNAME_EXPLAIN' => $lang['SMTP_username_explain'],
	'L_SMTP_PASSWORD' => $lang['SMTP_password'],
	'L_SMTP_PASSWORD_EXPLAIN' => $lang['SMTP_password_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'LOGIN_MAX_FAILED' => $new['login_tries'],
	'LOGIN_TIME_LOCKED' => $new['login_locked_out'],
	'LOGIN_TIME_ZERO' => $new['login_try'],
	'ONLINE_TIME' => $new['online_time'],
	'SERVER_NAME' => $new['server_name'],
	'SCRIPT_PATH' => $new['script_path'],
	'SERVER_PORT' => $new['server_port'],
	'SITENAME' => $new['sitename'],
	'SITE_DESCRIPTION' => $new['site_desc'],
	'BOARD_DISABLE_MESSAGE' => htmlspecialchars($new['board_disable_message']),
	'S_DISABLE_BOARD_YES' => $disable_board_yes,
	'S_DISABLE_BOARD_NO' => $disable_board_no,
	'S_REGISTRATION_STATUS_YES' => $registration_status_yes,
	'S_REGISTRATION_STATUS_NO' => $registration_status_no,
	'S_DISABLE_REGISTRATION_IP_CHECK_YES' => $disable_registration_ip_check_yes,
	'S_DISABLE_REGISTRATION_IP_CHECK_NO' => $disable_registration_ip_check_no,
	'REGISTRATION_CLOSED' => $new['registration_closed'],
	'S_MESSAGE_DISABLE_BOARD_NO' => $message_disable_board_no,
	'S_MESSAGE_DISABLE_BOARD_YES' => $message_disable_board_yes,
	'ACTIVATION_NONE' => USER_ACTIVATION_NONE,
	'ACTIVATION_NONE_CHECKED' => $activation_none,
	'ACTIVATION_USER' => USER_ACTIVATION_SELF,
	'ACTIVATION_USER_CHECKED' => $activation_user,
	'ACTIVATION_ADMIN' => USER_ACTIVATION_ADMIN,
	'ACTIVATION_ADMIN_CHECKED' => $activation_admin,
	'CONFIRM_ENABLE' => $confirm_yes,
	'CONFIRM_DISABLE' => $confirm_no,
	'USE_CAPTCHA_ENABLE' => $use_captcha_yes,
	'USE_CAPTCHA_DISABLE' => $use_captcha_no,
	'ACTIVATION_NONE_CHECKED' => $activation_none,
	'ALLOW_AUTOLOGIN_YES' => $allow_autologin_yes,
	'ALLOW_AUTOLOGIN_NO' => $allow_autologin_no,
	'AUTOLOGIN_TIME' => (int) $new['max_autologin_time'],
	'BOARD_EMAIL_FORM_ENABLE' => $board_email_form_yes,
	'BOARD_EMAIL_FORM_DISABLE' => $board_email_form_no,
	'COOKIE_DOMAIN' => $new['cookie_domain'],
	'COOKIE_NAME' => $new['cookie_name'],
	'COOKIE_PATH' => $new['cookie_path'],
	'SESSION_LENGTH' => $new['session_length'],
	'S_COOKIE_SECURE_ENABLED' => $cookie_secure_yes,
	'S_COOKIE_SECURE_DISABLED' => $cookie_secure_no,
	'GZIP_YES' => $gzip_yes,
	'GZIP_NO' => $gzip_no,
	'NAMECHANGE_YES' => $namechange_yes,
	'NAMECHANGE_NO' => $namechange_no,
	'EMAIL_FROM' => $new['board_email'],
	'EMAIL_SIG' => $new['board_email_sig'],
	'SMTP_YES' => $smtp_yes,
	'SMTP_NO' => $smtp_no,
	'SMTP_HOST' => $new['smtp_host'],
	'SMTP_USERNAME' => $new['smtp_username'],
	'SMTP_PASSWORD' => $new['smtp_password'],
	'COPPA_MAIL' => $new['coppa_mail'],
	'COPPA_FAX' => $new['coppa_fax'],

	)
);
$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>