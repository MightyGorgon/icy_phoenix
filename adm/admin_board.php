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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['110_Various_Configuration'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
$db->clear_cache('config_');

// Pull all config data
$sql = "SELECT * FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
	// CrackerTracker v5.x
	// Mighty Gorgon: This is not needed here so I remove it!!!
	/*
	if (isset($_POST['submit']) && $ctracker_config->settings['detect_misconfiguration'] == 1)
	{
		// Let's detect some things of misconfiguration
		if ($_POST['server_port'] == '21')
		{
			// FTP Port Mistake
			message_die(GENERAL_MESSAGE, $lang['ctracker_gmb_pu_1']);
		}

		if ($_POST['session_length'] < '100')
		{
			// Session Length Error
			message_die(GENERAL_MESSAGE, $lang['ctracker_gmb_pu_2']);
		}

		if (!preg_match('/\\A\/$|\\A\/.*\/$/', $_POST['script_path']))
		{
			// Script Path Error
			message_die(GENERAL_MESSAGE, $lang['ctracker_gmb_pu_3']);
		}

		if (preg_match('/\/$/', $_POST['server_name']))
		{
			// Server Name Error
			message_die(GENERAL_MESSAGE, $lang['ctracker_gmb_pu_4']);
		}
	}
	*/
	// CrackerTracker v5.x

	if (isset($_POST['submit']) && $ctracker_config->settings['auto_recovery'] == 1)
	{
		define('CTRACKER_ACP', true);
		include_once(IP_ROOT_PATH . 'ctracker/classes/class_ct_adminfunctions.' . PHP_EXT);
		$backup_system = new ct_adminfunctions();
		$backup_system->recover_configuration();
		unset($backup_system);
	}
	// CrackerTracker v5.x
	while($row = $db->sql_fetchrow($result))
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		//$default_config[$config_name] = isset($_POST['submit']) ? addslashes($config_value) : $config_value;
		$default_config[$config_name] = $config_value;
		$new[$config_name] = (isset($_POST[$config_name])) ? $_POST[$config_name] : $default_config[$config_name];
		fix_config_values($config_name, $config_value);

		if(isset($_POST['submit']))
		{
			set_config($config_name, $new[$config_name]);
		}
	}

	if(isset($_POST['submit']))
	{
		$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_board.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

$style_select = style_select($new['default_style'], 'default_style', '../templates');
$lang_select = language_select($new['default_lang'], 'default_lang', 'language');
$timezone_select = tz_select($new['board_timezone'], 'board_timezone');

$disable_board_yes = ($new['board_disable']) ? 'checked="checked"' : '';
$disable_board_no = (!$new['board_disable']) ? 'checked="checked"' : '';

$registration_status_yes = ($new['registration_status']) ? 'checked="checked"' : '';
$registration_status_no = (!$new['registration_status']) ? 'checked="checked"' : '';

$message_disable_board_yes = ($new['board_disable_mess_st']) ? 'checked="checked"' : '';
$message_disable_board_no = (!$new['board_disable_mess_st']) ? 'checked="checked"' : '';

$switch_header_table_yes = ($new['switch_header_table']) ? 'checked="checked"' : '';
$switch_header_table_no = (!$new['switch_header_table']) ? 'checked="checked"' : '';

$default_avatar_guests = ($new['default_avatar_set'] == '0') ? 'checked="checked"' : '';
$default_avatar_users = ($new['default_avatar_set'] == '1') ? 'checked="checked"' : '';
$default_avatar_both = ($new['default_avatar_set'] == '2') ? 'checked="checked"' : '';
$default_avatar_none = ($new['default_avatar_set'] == '3') ? 'checked="checked"' : '';

$cookie_secure_yes = ($new['cookie_secure']) ? 'checked="checked"' : '';
$cookie_secure_no = (!$new['cookie_secure']) ? 'checked="checked"' : '';

$html_tags = $new['allow_html_tags'];

$override_user_style_yes = ($new['override_user_style']) ? 'checked="checked"' : '';
$override_user_style_no = (!$new['override_user_style']) ? 'checked="checked"' : '';

$html_yes = ($new['allow_html']) ? 'checked="checked"' : '';
$html_no = (!$new['allow_html']) ? 'checked="checked"' : '';

$bbcode_yes = ($new['allow_bbcode']) ? 'checked="checked"' : '';
$bbcode_no = (!$new['allow_bbcode']) ? 'checked="checked"' : '';

$activation_none = ($new['require_activation'] == USER_ACTIVATION_NONE) ? 'checked="checked"' : '';
$activation_user = ($new['require_activation'] == USER_ACTIVATION_SELF) ? 'checked="checked"' : '';
$activation_admin = ($new['require_activation'] == USER_ACTIVATION_ADMIN) ? 'checked="checked"' : '';

$confirm_yes = ($new['enable_confirm']) ? 'checked="checked"' : '';
$confirm_no = (!$new['enable_confirm']) ? 'checked="checked"' : '';
$use_captcha_yes = ($new['use_captcha']) ? 'checked="checked"' : '';
$use_captcha_no = (!$new['use_captcha']) ? 'checked="checked"' : '';

$autolink_first_yes = ($new['autolink_first']) ? 'checked="checked"' : '';
$autolink_first_no = (!$new['autolink_first']) ? 'checked="checked"' : '';

$allow_autologin_yes = ($new['allow_autologin']) ? 'checked="checked"' : '';
$allow_autologin_no = (!$new['allow_autologin']) ? 'checked="checked"' : '';

/*
$board_email_form_yes = ($new['board_email_form']) ? 'checked="checked"' : '';
$board_email_form_no = (!$new['board_email_form']) ? 'checked="checked"' : '';
*/

switch ($new['default_time_mode'])
{
	case MANUAL_DST:
		$time_mode_manual_dst_checked='checked="checked"';
		break;
	case SERVER_SWITCH:
		$time_mode_server_switch_checked='checked="checked"';
		break;
	case FULL_SERVER:
		$time_mode_full_server_checked='checked="checked"';
		break;
	case SERVER_PC:
		$time_mode_server_pc_checked='checked="checked"';
		break;
	case FULL_PC:
		$time_mode_full_pc_checked='checked="checked"';
		break;
	default:
		$time_mode_manual_checked='checked="checked"';
}

$gzip_yes = ($new['gzip_compress']) ? 'checked="checked"' : '';
$gzip_no = (!$new['gzip_compress']) ? 'checked="checked"' : '';

$privmsg_on = (!$new['privmsg_disable']) ? 'checked="checked"' : '';
$privmsg_off = ($new['privmsg_disable']) ? 'checked="checked"' : '';

$prune_yes = ($new['prune_enable']) ? 'checked="checked"' : '';
$prune_no = (!$new['prune_enable']) ? 'checked="checked"' : '';

// Start the Designers & Coders Network
$network_drop = ($new['network_type'] == 'drop') ? 'checked="checked"' : '';
$network_line = ($new['network_type'] == 'line') ? 'checked="checked"' : '';
// End the Designers & Coders Network

$hidde_last_logon_yes = ($new['hidde_last_logon']) ? 'checked="checked"' : '';
$hidde_last_logon_no = (!$new['hidde_last_logon']) ? 'checked="checked"' : '';

$birthday_greeting_yes = ($new['birthday_greeting']) ? 'checked="checked"' : '';
$birthday_greeting_no = (!$new['birthday_greeting']) ? 'checked="checked"' : '';
$birthday_required_yes = ($new['birthday_required']) ? 'checked="checked"' : '';
$birthday_required_no = (!$new['birthday_required']) ? 'checked="checked"' : '';
$gender_required_yes = ($new['gender_required']) ? ' checked="checked"' : '';
$gender_required_no = (!$new['gender_required']) ? ' checked="checked"' : '';

$smile_yes = ($new['allow_smilies']) ? 'checked="checked"' : '';
$smile_no = (!$new['allow_smilies']) ? 'checked="checked"' : '';

$sig_yes = ($new['allow_sig']) ? 'checked="checked"' : '';
$sig_no = (!$new['allow_sig']) ? 'checked="checked"' : '';

$namechange_yes = ($new['allow_namechange']) ? 'checked="checked"' : '';
$namechange_no = (!$new['allow_namechange']) ? 'checked="checked"' : '';

$avatars_local_yes = ($new['allow_avatar_local']) ? 'checked="checked"' : '';
$avatars_local_no = (!$new['allow_avatar_local']) ? 'checked="checked"' : '';
$avatars_remote_yes = ($new['allow_avatar_remote']) ? 'checked="checked"' : '';
$avatars_remote_no = (!$new['allow_avatar_remote']) ? 'checked="checked"' : '';
$avatars_upload_yes = ($new['allow_avatar_upload']) ? 'checked="checked"' : '';
$avatars_upload_no = (!$new['allow_avatar_upload']) ? 'checked="checked"' : '';
$gravatars_yes = ($new['enable_gravatars']) ? ' checked="checked"' : '';
$gravatars_no = (!$new['enable_gravatars']) ? ' checked="checked"' : '';
$avatar_generator_yes = ($new['allow_avatar_generator']) ? 'checked="checked"' : '';
$avatar_generator_no = (!$new['allow_avatar_generator']) ? 'checked="checked"' : '';

$smtp_yes = ($new['smtp_delivery']) ? 'checked="checked"' : '';
$smtp_no = (!$new['smtp_delivery']) ? 'checked="checked"' : '';

$template->set_filenames(array('body' => ADM_TPL . 'board_config_body.tpl'));

//report forum selection
$sql = "SELECT f.forum_name, f.forum_id
	FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
	WHERE c.cat_id = f.cat_id ORDER BY c.cat_order ASC, f.forum_order ASC";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Couldn't obtain forum list", "", __LINE__, __FILE__, $sql);
}
$report_forum_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
$report_forum_select_list = '<select name="report_forum">';
$report_forum_select_list .= '<option value="0">' . $lang['None'] . '</option>' . get_tree_option_optg('f' . $new['report_forum'], true, true);
$report_forum_select_list .= '</select>';
$report_forum_select_list = str_replace("value=\"" . 'f' . $new['report_forum'] . "\" selected=\"selected\">", "value=\"" . 'f' . $new['report_forum'] . "\" selected=\"selected\">*" , $report_forum_select_list);

//BIN forum selection
$sql = "SELECT f.forum_name, f.forum_id
	FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
	WHERE c.cat_id = f.cat_id ORDER BY c.cat_order ASC, f.forum_order ASC";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Couldn't obtain forum list", "", __LINE__, __FILE__, $sql);
}
$bin_forum_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
$bin_forum_select_list = '<select name="bin_forum">';
$bin_forum_select_list .= '<option value="0">' . $lang['None'] . '</option>' . get_tree_option_optg('f' . $new['bin_forum'], true, true);
$bin_forum_select_list .= '</select>';
$bin_forum_select_list = str_replace("value=\"" . 'f' . $new['bin_forum'] . "\" selected=\"selected\">", "value=\"" . 'f' . $new['bin_forum'] . "\" selected=\"selected\">*" , $bin_forum_select_list);

// Escape any quotes in the site description for proper display in the text box on the admin page
$new['site_desc'] = str_replace('"', '&quot;', $new['site_desc']);
$new['sitename'] = str_replace('"', '&quot;', strip_tags($new['sitename']));
$new['registration_closed'] = str_replace('"', '&quot;', $new['registration_closed']);
$new['sig_line'] = str_replace('"', '&quot;', $new['sig_line']);

// Ajax Shoutbox - BEGIN
$shoutguest_yes = ($new['shout_allow_guest'] == 1) ? 'checked="checked"' : '';
$shoutguest_read = ($new['shout_allow_guest'] == 2) ? 'checked="checked"' : '';
$shoutguest_no = ($new['shout_allow_guest'] == 0) ? 'checked="checked"' : '';
// Ajax Shoutbox - END

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board.' . PHP_EXT),
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],

	'L_HEADER_FOOTER' => $lang['MG_SW_Header_Footer'],
	'L_HEADER_TABLE_SWITCH' => $lang['MG_SW_Header_Table'],
	'L_HEADER_TABLE_SWITCH_EXPLAIN' =>$lang['MG_SW_Header_Table_Explain'],
	'L_HEADER_TABLE_TEXT' =>$lang['MG_SW_Header_Table_Text'],

	'L_DEFAULT_AVATAR' => $lang['Default_avatar'],
	'L_DEFAULT_AVATAR_EXPLAIN' => $lang['Default_avatar_explain'],
	'L_DEFAULT_AVATAR_GUESTS' => $lang['Default_avatar_guests'],
	'L_DEFAULT_AVATAR_USERS' => $lang['Default_avatar_users'],
	'L_DEFAULT_AVATAR_BOTH' => $lang['Default_avatar_both'],
	'L_DEFAULT_AVATAR_NONE' => $lang['Default_avatar_none'],
	'L_CONFIGURATION_TITLE' => $lang['General_Config'],
	'L_CONFIGURATION_EXPLAIN' => $lang['Config_explain'],
	'L_GENERAL_SETTINGS' => $lang['General_settings'],
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
	/*
	'L_MESSAGE_DISABLE_BOARD' => $lang['board_disable_message'],
	'L_MESSAGE_DISABLE_BOARD_TEXT' => $lang['board_disable_message_texte'],
	*/
	'L_ACCT_ACTIVATION' => $lang['Acct_activation'],
	'L_NONE' => $lang['Acc_None'],
	'L_USER' => $lang['Acc_User'],
	'L_ADMIN' => $lang['Acc_Admin'],
	'L_VISUAL_CONFIRM' => $lang['Visual_confirm'],
	'L_VISUAL_CONFIRM_EXPLAIN' => $lang['Visual_confirm_explain'],
	'L_USE_CAPTCHA' => $lang['Use_Captcha'],
	'L_USE_CAPTCHA_EXPLAIN' => $lang['Use_Captcha_Explain'],
	'L_AUTOLINK_FIRST' => $lang['Autolink_first'],
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
	'L_INBOX_LIMIT' => $lang['Inbox_limits'],
	'L_SENTBOX_LIMIT' => $lang['Sentbox_limits'],
	'L_SAVEBOX_LIMIT' => $lang['Savebox_limits'],
	'L_DISABLE_PRIVATE_MESSAGING' => $lang['Disable_privmsg'],
	'L_ENABLED' => $lang['Enabled'],
	'L_DISABLED' => $lang['Disabled'],
	'L_ABILITIES_SETTINGS' => $lang['Abilities_settings'],
	'L_MAX_POLL_OPTIONS' => $lang['Max_poll_options'],
	'L_MAX_LOGIN_ATTEMPTS'			=> $lang['Max_login_attempts'],
	'L_MAX_LOGIN_ATTEMPTS_EXPLAIN'	=> $lang['Max_login_attempts_explain'],
	'L_LOGIN_RESET_TIME'			=> $lang['Login_reset_time'],
	'L_LOGIN_RESET_TIME_EXPLAIN'	=> $lang['Login_reset_time_explain'],
	'MAX_LOGIN_ATTEMPTS'			=> $new['max_login_attempts'],
	'LOGIN_RESET_TIME'				=> $new['login_reset_time'],
	'L_FLOOD_INTERVAL' => $lang['Flood_Interval'],
	'L_FLOOD_INTERVAL_EXPLAIN' => $lang['Flood_Interval_explain'],
	'L_SEARCH_FLOOD_INTERVAL' => $lang['Search_Flood_Interval'],
	'L_SEARCH_FLOOD_INTERVAL_EXPLAIN' => $lang['Search_Flood_Interval_explain'],
	'L_BOARD_EMAIL_FORM' => $lang['Board_email_form'],
	'L_BOARD_EMAIL_FORM_EXPLAIN' => $lang['Board_email_form_explain'],
	'L_TOPICS_PER_PAGE' => $lang['Topics_per_page'],
	'L_POSTS_PER_PAGE' => $lang['Posts_per_page'],
	'L_HOT_THRESHOLD' => $lang['Hot_threshold'],
	'L_DEFAULT_STYLE' => $lang['Default_style'],
	'L_OVERRIDE_STYLE' => $lang['Override_style'],
	'L_OVERRIDE_STYLE_EXPLAIN' => $lang['Override_style_explain'],
	'L_DEFAULT_LANGUAGE' => $lang['Default_language'],
	'L_DATE_FORMAT' => $lang['Date_format'],
	'L_SYSTEM_TIMEZONE' => $lang['System_timezone'],
	'L_TIME_MODE' => $lang['time_mode'],
	'L_TIME_MODE_TEXT' => $lang['time_mode_text'],
	'L_TIME_MODE_MANUAL' => $lang['time_mode_manual'],
	'L_TIME_MODE_DST' => $lang['time_mode_dst'],
	'L_TIME_MODE_DST_SERVER' => $lang['time_mode_dst_server'],
	'L_TIME_MODE_DST_TIME_LAG' => $lang['time_mode_dst_time_lag'],
	'L_TIME_MODE_DST_MN' => $lang['time_mode_dst_mn'],
	'L_TIME_MODE_TIMEZONE' => $lang['time_mode_timezone'],
	'L_TIME_MODE_AUTO' => $lang['time_mode_auto'],
	'L_TIME_MODE_FULL_SERVER' => $lang['time_mode_full_server'],
	'L_TIME_MODE_SERVER_PC' => $lang['time_mode_server_pc'],
	'L_TIME_MODE_FULL_PC' => $lang['time_mode_full_pc'],
	'L_ENABLE_GZIP' => $lang['Enable_gzip'],
	'L_ENABLE_PRUNE' => $lang['Enable_prune'],
	'L_PRUNE_SHOUTS' => $lang['Prune_shouts'],
	'L_PRUNE_SHOUTS_EXPLAIN' => $lang['Prune_shouts_explain'],
	'L_HIDDE_LAST_LOGON' => $lang['Hidde_last_logon'],
	'L_HIDDE_LAST_LOGON_EXPLAIN' => $lang['Hidde_last_logon_expain'],
	'L_BLUECARD_LIMIT' => $lang['Bluecard_limit'],
	'L_BLUECARD_LIMIT_EXPLAIN' => $lang['Bluecard_limit_explain'],
	'L_BLUECARD_LIMIT_2' => $lang['Bluecard_limit_2'],
	'L_BLUECARD_LIMIT_2_EXPLAIN' => $lang['Bluecard_limit_2_explain'],
	'L_MAX_USER_BANCARD' => $lang['Max_user_bancard'],
	'L_MAX_USER_BANCARD_EXPLAIN' => $lang['Max_user_bancard_explain'],
	'L_REPORT_FORUM' => $lang['Report_forum'],
	'L_REPORT_FORUM_EXPLAIN' => $lang['Report_forum_explain'],
	'L_ENABLE_BIRTHDAY_GREETING' => $lang['Enable_birthday_greeting'],
	'L_BIRTHDAY_GREETING_EXPLAIN' => $lang['Birthday_greeting_expain'],
	'L_BIRTHDAY_REQUIRED' => $lang['Birthday_required'],
	'L_MAX_USER_AGE' => $lang['Max_user_age'],
	'L_MIN_USER_AGE' => $lang['Min_user_age'],
	'L_MIN_USER_AGE_EXPLAIN' => $lang['Min_user_age_explain'],
	'L_BIRTHDAY_LOOKFORWARD' => $lang['Birthday_lookforward'],
	'L_BIRTHDAY_LOOKFORWARD_EXPLAIN' => $lang['Birthday_lookforward_explain'],
	'L_SIG_TITLE' => $lang['sig_title'],
	'L_SIG_EXPLAIN' => $lang['sig_explain'],
	'L_SIG_INPUT' => $lang['sig_divider'],
	'L_ALLOW_HTML' => $lang['Allow_HTML'],
	'L_ALLOW_BBCODE' => $lang['Allow_BBCode'],
	'L_ALLOWED_TAGS' => $lang['Allowed_tags'],
	'L_ALLOWED_TAGS_EXPLAIN' => $lang['Allowed_tags_explain'],
	'L_ALLOW_SMILIES' => $lang['Allow_smilies'],
	'L_SMILIES_PATH' => $lang['Smilies_path'],
	'L_SMILIES_PATH_EXPLAIN' => $lang['Smilies_path_explain'],
	'L_SMILIE_TABLE_COLUMNS' => $lang['Smilie_table_columns'],
	'L_SMILIE_TABLE_ROWS' => $lang['Smilie_table_rows'],
	'L_SMILIE_WINDOW_COLUMNS' => $lang['Smilie_window_columns'],
	'L_SMILIE_WINDOW_ROWS' => $lang['Smilie_window_rows'],
	'L_SMILIE_SINGLE_ROW' => $lang['Smilie_single_row'],
	'L_ALLOW_SIG' => $lang['Allow_sig'],
	'L_MAX_SIG_LENGTH' => $lang['Max_sig_length'],
	'L_MAX_SIG_LENGTH_EXPLAIN' => $lang['Max_sig_length_explain'],
	'L_ALLOW_NAME_CHANGE' => $lang['Allow_name_change'],
	'L_MAX_LINK_BOOKMARKS' => $lang['Max_bookmarks_links'],
	'L_MAX_LINK_BOOKMARKS_EXPLAIN' => $lang['Max_bookmarks_links_explain'],
	'L_AVATAR_SETTINGS' => $lang['Avatar_settings'],
	'L_ALLOW_LOCAL' => $lang['Allow_local'],
	'L_ALLOW_REMOTE' => $lang['Allow_remote'],
	'L_ALLOW_REMOTE_EXPLAIN' => $lang['Allow_remote_explain'],
	'L_ALLOW_UPLOAD' => $lang['Allow_upload'],
	'L_ALLOW_GENERATOR' => $lang['Allow_generator'],
	'L_MAX_FILESIZE' => $lang['Max_filesize'],
	'L_MAX_FILESIZE_EXPLAIN' => $lang['Max_filesize_explain'],
	'L_MAX_AVATAR_SIZE' => $lang['Max_avatar_size'],
	'L_MAX_AVATAR_SIZE_EXPLAIN' => $lang['Max_avatar_size_explain'],
	'L_AVATAR_STORAGE_PATH' => $lang['Avatar_storage_path'],
	'L_AVATAR_STORAGE_PATH_EXPLAIN' => $lang['Avatar_storage_path_explain'],
	'L_AVATAR_GALLERY_PATH' => $lang['Avatar_gallery_path'],
	'L_AVATAR_GALLERY_PATH_EXPLAIN' => $lang['Avatar_gallery_path_explain'],
	'L_AVATAR_GENERATOR_TEMPLATE_PATH' => $lang['Avatar_generator_template_path'],
	'L_AVATAR_GENERATOR_TEMPLATE_PATH_EXPLAIN' => $lang['Avatar_generator_template_path_explain'],

	'L_ENABLE_GRAVATARS' => $lang['Enable_gravatars'],
	'L_GRAVATAR_RATING' => $lang['Gravatar_rating'],
	'L_GRAVATAR_RATING_EXPLAIN' => $lang['Gravatar_rating_explain'],
	'L_GRAVATAR_DEFAULT_IMAGE' => $lang['Gravatar_default_image'],
	'L_GRAVATAR_DEFAULT_IMAGE_EXPLAIN' => $lang['Gravatar_default_image_explain'],
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

	'HEADER_TBL_YES' => $switch_header_table_yes,
	'HEADER_TBL_NO' => $switch_header_table_no,
	'HEADER_TBL_TXT' => ip_stripslashes($new['header_table_text']),

	'LOGIN_MAX_FAILED' => $new['login_tries'],
	'LOGIN_TIME_LOCKED' => $new['login_locked_out'],
	'LOGIN_TIME_ZERO' => $new['login_try'],
	'L_GENDER_REQUIRED' => $lang['Gender_required'],
	'GENDER_REQUIRED_YES' => $gender_required_yes,
	'GENDER_REQUIRED_NO' => $gender_required_no,
	'L_BIN_FORUM' => $lang['Bin_forum'],
	'L_BIN_FORUM_EXPLAIN' => $lang['Bin_forum_explain'],
	'BIN_FORUM' => $new['bin_forum'],
	'L_ONLINE_TIME' => $lang['Online_time'],
	'L_ONLINE_TIME_EXPLAIN' => $lang['Online_time_explain'],
	'ONLINE_TIME' => $new['online_time'],
	'SERVER_NAME' => $new['server_name'],
	'SCRIPT_PATH' => $new['script_path'],
	'SERVER_PORT' => $new['server_port'],
	'SITENAME' => $new['sitename'],
	'SITE_DESCRIPTION' => $new['site_desc'],
	/*
	'BOARD_DISABLE_MESSAGE' => $new['board_disable_message'],
	*/
	'S_DISABLE_BOARD_YES' => $disable_board_yes,
	'S_DISABLE_BOARD_NO' => $disable_board_no,
	'S_REGISTRATION_STATUS_YES' => $registration_status_yes,
	'S_REGISTRATION_STATUS_NO' => $registration_status_no,
	'REGISTRATION_CLOSED' => $new['registration_closed'],
	'S_MESSAGE_DISABLE_BOARD_NO' => $message_disable_board_no,
	'S_MESSAGE_DISABLE_BOARD_YES' => $message_disable_board_yes,
	'DEFAULT_AVATAR_GUESTS_URL' => $new['default_avatar_guests_url'],
	'DEFAULT_AVATAR_USERS_URL' => $new['default_avatar_users_url'],
	'DEFAULT_AVATAR_GUESTS' => $default_avatar_guests,
	'DEFAULT_AVATAR_USERS' => $default_avatar_users,
	'DEFAULT_AVATAR_BOTH' => $default_avatar_both,
	'DEFAULT_AVATAR_NONE' => $default_avatar_none,
	'ACTIVATION_NONE' => USER_ACTIVATION_NONE,
	'ACTIVATION_NONE_CHECKED' => $activation_none,
	'AUTOLINK_FIRST_YES' => $autolink_first_yes,
	'AUTOLINK_FIRST_NO' => $autolink_first_no,
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
	'SEARCH_FLOOD_INTERVAL' => $new['search_flood_interval'],
	/*
	'BOARD_EMAIL_FORM_ENABLE' => $board_email_form_yes,
	'BOARD_EMAIL_FORM_DISABLE' => $board_email_form_no,
	*/
	'MAX_POLL_OPTIONS' => $new['max_poll_options'],
	'FLOOD_INTERVAL' => $new['flood_interval'],
	'TOPICS_PER_PAGE' => $new['topics_per_page'],
	'POSTS_PER_PAGE' => $new['posts_per_page'],
	'HOT_TOPIC' => $new['hot_threshold'],
	'STYLE_SELECT' => $style_select,
	'OVERRIDE_STYLE_YES' => $override_user_style_yes,
	'OVERRIDE_STYLE_NO' => $override_user_style_no,
	'LANG_SELECT' => $lang_select,
	'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
	'DEFAULT_DATEFORMAT' => date_select($new['default_dateformat'],'default_dateformat'),
	'TIMEZONE_SELECT' => $timezone_select,
	'TIME_MODE' => $time_mode,
	'TIME_MODE_MANUAL_CHECKED' => $time_mode_manual_checked,
	'TIME_MODE_MANUAL_DST_CHECKED' => $time_mode_manual_dst_checked,
	'TIME_MODE_SERVER_SWITCH_CHECKED' => $time_mode_server_switch_checked,
	'TIME_MODE_FULL_SERVER_CHECKED' => $time_mode_full_server_checked,
	'TIME_MODE_SERVER_PC_CHECKED' => $time_mode_server_pc_checked,
	'TIME_MODE_FULL_PC_CHECKED' => $time_mode_full_pc_checked,
	'DST_TIME_LAG' => $new['default_dst_time_lag'],
	'S_PRIVMSG_ENABLED' => $privmsg_on,
	'S_PRIVMSG_DISABLED' => $privmsg_off,
	'INBOX_LIMIT' => $new['max_inbox_privmsgs'],
	'SENTBOX_LIMIT' => $new['max_sentbox_privmsgs'],
	'SAVEBOX_LIMIT' => $new['max_savebox_privmsgs'],
	'COOKIE_DOMAIN' => $new['cookie_domain'],
	'COOKIE_NAME' => $new['cookie_name'],
	'COOKIE_PATH' => $new['cookie_path'],
	'SESSION_LENGTH' => $new['session_length'],
	'S_COOKIE_SECURE_ENABLED' => $cookie_secure_yes,
	'S_COOKIE_SECURE_DISABLED' => $cookie_secure_no,
	'GZIP_YES' => $gzip_yes,
	'GZIP_NO' => $gzip_no,
	'PRUNE_YES' => $prune_yes,
	'PRUNE_NO' => $prune_no,
	'PRUNE_SHOUTS' => $new['prune_shouts'],
	'HIDDE_LAST_LOGON_YES' => $hidde_last_logon_yes,
	'HIDDE_LAST_LOGON_NO' => $hidde_last_logon_no,
	'BLUECARD_LIMIT' => $new['bluecard_limit'],
	'BLUECARD_LIMIT_2' => $new['bluecard_limit_2'],
	'MAX_USER_BANCARD' => $new['max_user_bancard'],
	'S_REPORT_FORUM' => $report_forum_select_list,
	'S_BIN_FORUM' => $bin_forum_select_list,
	'BIRTHDAY_GREETING_YES' => $birthday_greeting_yes,
	'BIRTHDAY_GREETING_NO' => $birthday_greeting_no,
	'BIRTHDAY_REQUIRED_YES' => $birthday_required_yes,
	'BIRTHDAY_REQUIRED_NO' => $birthday_required_no,
	'MAX_USER_AGE' => $new['max_user_age'],
	'MIN_USER_AGE' => $new['min_user_age'],
	'BIRTHDAY_LOOKFORWARD' => $new['birthday_check_day'],
	'SIG_DIVIDERS' => $new['sig_line'],
	'HTML_TAGS' => $html_tags,
	'HTML_YES' => $html_yes,
	'HTML_NO' => $html_no,
	'BBCODE_YES' => $bbcode_yes,
	'BBCODE_NO' => $bbcode_no,
	'SMILE_YES' => $smile_yes,
	'SMILE_NO' => $smile_no,
	'SIG_YES' => $sig_yes,
	'SIG_NO' => $sig_no,
	'SIG_SIZE' => $new['max_sig_chars'],
	'NAMECHANGE_YES' => $namechange_yes,
	'NAMECHANGE_NO' => $namechange_no,
	'LINK_BOOKMARKS' => $new['max_link_bookmarks'],

	'AVATARS_LOCAL_YES' => $avatars_local_yes,
	'AVATARS_LOCAL_NO' => $avatars_local_no,
	'AVATARS_REMOTE_YES' => $avatars_remote_yes,
	'AVATARS_REMOTE_NO' => $avatars_remote_no,
	'AVATARS_UPLOAD_YES' => $avatars_upload_yes,
	'AVATARS_UPLOAD_NO' => $avatars_upload_no,
	'AVATAR_GENERATOR_YES' => $avatar_generator_yes,
	'AVATAR_GENERATOR_NO' => $avatar_generator_no,
	'AVATAR_FILESIZE' => $new['avatar_filesize'],
	'AVATAR_MAX_HEIGHT' => $new['avatar_max_height'],
	'AVATAR_MAX_WIDTH' => $new['avatar_max_width'],
	'AVATAR_PATH' => $new['avatar_path'],
	'AVATAR_GALLERY_PATH' => $new['avatar_gallery_path'],
	'AVATAR_GENERATOR_TEMPLATE_PATH' => $new['avatar_generator_template_path'],
	'ENABLE_GRAVATARS_YES' => $gravatars_yes,
	'ENABLE_GRAVATARS_NO' => $gravatars_no,
	'GRAVATAR_RATING' => select_gravatar_rating($new['gravatar_rating']),
	'GRAVATAR_DEFAULT_IMAGE' => $new['gravatar_default_image'],
	'SMILIES_PATH' => $new['smilies_path'],
	'SMILIE_COLUMNS' => $new['smilie_columns'],
	'SMILIE_ROWS' => $new['smilie_rows'],
	'SMILIE_WINDOW_COLUMNS' => $new['smilie_window_columns'],
	'SMILIE_WINDOW_ROWS' => $new['smilie_window_rows'],
	'SMILIE_SINGLE_ROW' => $new['smilie_single_row'],
	'INBOX_PRIVMSGS' => $new['max_inbox_privmsgs'],
	'SENTBOX_PRIVMSGS' => $new['max_sentbox_privmsgs'],
	'SAVEBOX_PRIVMSGS' => $new['max_savebox_privmsgs'],
	'EMAIL_FROM' => $new['board_email'],
	'EMAIL_SIG' => $new['board_email_sig'],
	'SMTP_YES' => $smtp_yes,
	'SMTP_NO' => $smtp_no,
	'SMTP_HOST' => $new['smtp_host'],
	'SMTP_USERNAME' => $new['smtp_username'],
	'SMTP_PASSWORD' => $new['smtp_password'],
	'COPPA_MAIL' => $new['coppa_mail'],
	'COPPA_FAX' => $new['coppa_fax'],

	// Ajax Shoutbox - BEGIN
	'L_SHOUTBOX_CONFIG' => $lang['Shoutbox_config'],
	'L_DISPLAYED_SHOUTS' => $lang['Displayed_shouts'],
	'L_DISPLAYED_SHOUTS_EXPLAIN' => $lang['Displayed_shouts_explain'],
	'L_STORED_SHOUTS' => $lang['Stored_shouts'],
	'L_STORED_SHOUTS_EXPLAIN' => $lang['Stored_shouts_explain'],
	'L_SHOUTBOX_FLOOD' => $lang['Shoutbox_flood'],
	'L_SHOUTBOX_FLOOD_EXPLAIN' => $lang['Shoutbox_flood_explain'],
	'L_SHOUT_GUEST_READONLY' => $lang['Shout_read_only'],
	'L_GUEST_ALLOWED' => $lang['Shout_guest_allowed'],
	'L_SHOUTBOX_REFRESHTIME' => $lang['Shoutbox_refreshtime'],
	'L_SHOUTBOX_REFRESH_EXPLAIN' => $lang['Shoutbox_refresh_explain'],
	'DISPLAYED_SHOUTS' => $new['display_shouts'],
	'STORED_SHOUTS' => $new['stored_shouts'],
	'SHOUTBOX_FLOODINTERVAL' => $new['shoutbox_floodinterval'],
	'SHOUT_GUEST_YES' => $shoutguest_yes,
	'SHOUT_GUEST_READONLY' => $shoutguest_read,
	'SHOUT_GUEST_NO' => $shoutguest_no,
	'SHOUTBOX_REFRESHTIME' => $new['shoutbox_refreshtime'],
	// Ajax Shoutbox - END
	)
);
include(IP_ROOT_PATH . ADM . '/bb_usage_stats_admin.' . PHP_EXT);
$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>