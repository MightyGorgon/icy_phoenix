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
* Christian Knerr (cback) - (www.cback.de)
* Lopalong
*
*/

/**
* English Language File for the CBACK Cracker Tracker
*
* @author Christian Knerr (cback)
* @translator Marc Renninger (mc-dragon)
* @package ctracker
* @version 5.0.0
* @since 21.07.2006 - 17:26:28
* @copyright (c) 2006 www.cback.de
*
*/


if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ctracker_settings_on' => 'Enabled',
	'ctracker_settings_off' => 'Disabled',
	'ctracker_ma_on' => 'ON',
	'ctracker_ma_off' => 'OFF',
	'ctracker_blockmode_0' => 'Off',
	'ctracker_blockmode_1' => 'Ban User',
	'ctracker_blockmode_2' => 'Lock User',
	'ctracker_complex_1' => '[0-9]',
	'ctracker_complex_2' => '[a-z]',
	'ctracker_complex_3' => '[A-Z]',
	'ctracker_complex_4' => '[0-9][a-z]',
	'ctracker_complex_5' => '[0-9][A-Z]',
	'ctracker_complex_6' => '[0-9][a-z][A-Z]',
	'ctracker_complex_7' => '[0-9][*]',
	'ctracker_complex_8' => '[0-9][a-z][*]',
	'ctracker_complex_9' => '[0-9][a-z][A-Z][*]',
	'ctracker_ipb_new_entry' => 'New Entry',
	'ctracker_ipb_added' => 'Entry successfully added!',
	'ctracker_ipb_deleted' => 'Entry successfully deleted!',
	'ctracker_ipb_add_now' => 'Add Entry',
	'ctracker_mu_success' => 'The user has been marked as a "Miserable User".',
	'ctracker_mu_success_bbc' => '[cell class="text_orange"]The user has been marked as a "Miserable User".[/cell]',
	'ctracker_mu_success_html' => '<span class="text_orange">The user has been marked as a "Miserable User".</span>',
	'ctracker_mu_error_admin' => 'Admins or Mods cannot be marked as "Miserable User"!',
	'ctracker_mu_deleted' => 'The chosen user(s) have been deleted from the "Miserable User" Userlist successfully.',
	'ctracker_mu_head' => 'Miserable User',
	'ctracker_error_updating_userdata' => 'CrackerTracker couldn\'t run the database operation in the Usertable.',
	'ctracker_error_database_op' => 'CrackerTracker couldn\'t run the database operation correctly.',
	'ctracker_message_dialog_title' => 'CBACK CrackerTracker Professional',


/*
* Language Strings used for the footer itself
*/
	'ctracker_fdisplay_imgdesc' => 'Board Security',
	'ctracker_fdisplay_n' => '<a href="http://www.cback.de" target="_blank">Security</a> with <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a>.',
	'ctracker_fdisplay_c' => 'Protected by <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a><br /><b>%s</b> Attacks blocked.',
	'ctracker_fdisplay_g' => '<b>%s</b> Attacks blocked',


/*
* Language Strings for the class_ct_database.php
*/
	'ctracker_error_loading_config' => 'The CrackerTracker Configuration couldn\'t be loaded from the database. Have you run the installation script and edited the file "includes/constants.php" correctly?',
	'ctracker_error_updating_config' => 'The CrackerTracker Configuration couldn\'t be updated. Have you run the installation script and edited the file "includes/constants.php" correctly?',
	'ctracker_error_loading_blocklist' => 'The CrackerTracker Blocklist couldn\'t be loaded from the Database. Have you run the installation script and edited the file "includes/constants.php" correctly?',
	'ctracker_error_insert_blocklist' => 'The data couldn\'t be added to CrackerTracker Blocklist. Have you run the installation script and edited the file "includes/constants.php" correctly?',
	'ctracker_error_delete_blocklist' => 'The data couldn\'t be removed from the CrackerTracker Blocklist. Have you run the installation script and edited the file "includes/constants.php" correctly?',
	'ctracker_error_login_history' => 'There has been an error with the database operation inside CrackerTracker Login History. Have you run the installation script and edited the file "includes/constants.php" correctly?',
	'ctracker_error_del_login_history' => 'The CrackerTracker Login History Table couldn\'t be emptied.',


/*
* Language Strings used in class_ct_userfunctions.php
*/
	'ctracker_info_search_time' => "For safety reasons the search is only possible %s times within %s seconds. If this number was exceeded, you must now wait <span id=\"waittime\">%s</span> seconds, until you can implement the next search. <script type=\"text/javascript\"><!-- \n var wait = %s; var waitt = wait * 1000; for(i=1; i <= wait; i++) { window.setTimeout(\"newoutput(\" + i + \")\", i * 1000); } function newoutput(waitcounter) { if ( (waitt/1000) == waitcounter ) { document.getElementById(\"waittime\").innerHTML = \"0\"; } else { document.getElementById(\"waittime\").innerHTML = (waitt/1000) - waitcounter; } } //--></script>",
	'ctracker_info_regist_time' => "For safety reasons, registration is only possible every %s seconds. If this number was exceeded, you must now wait <span id=\"waittime\">%s</span> seconds, before you can submit a new registration. <script type=\"text/javascript\"><!-- \n var wait = %s; var waitt = wait * 1000; for(i=1; i <= wait; i++) { window.setTimeout(\"newoutput(\" + i + \")\", i * 1000); } function newoutput(waitcounter) { if ( (waitt/1000) == waitcounter ) { document.getElementById(\"waittime\").innerHTML = \"0\"; } else { document.getElementById(\"waittime\").innerHTML = (waitt/1000) - waitcounter; } } //--></script>",
	'ctracker_info_regip_double' => 'There has already been a registration from this IP-Address. From security reasons only one registration from the same IP address is possible.',
	'ctracker_info_profile_spammer' => 'This registration was identified as a spam account! If you think that this was in error, please contact the Administrator of this forum.',
	'ctracker_info_password_minlng' => 'The Administrator requires that the password must contain at minimum <b>%s</b> characters. Your chosen password has only <b>%s</b> characters. Please go back and enter a new password.',
	'ctracker_info_password_cmplx' => 'The Administrator requires that the password must contain at <b>minimum</b> the following things: %s',
	'ctracker_info_password_cmplx_1' => 'Figures',
	'ctracker_info_password_cmplx_2' => 'Lower case',
	'ctracker_info_password_cmplx_3' => 'Capitals',
	'ctracker_info_password_cmplx_4' => 'Special Characters',
	'ctracker_info_pw_expired' => 'The Administrator has decided that a password is only valid for <b>%s days</b>. We recommend for security reasons that you change your password now. (<a href="' . CMS_PAGE_PROFILE . '?mode=editprofile&amp;' . POST_USERS_URL . '=%d">Profile</a>)',

/*
* Language Strings used in ct_visual_confirm.php
*/
	'ctracker_login_wrong' => 'The Visual Confirmation Code you entered was incorrect!',
	'ctracker_code_dbconn' => 'Couldn\'t load the Visual Confirmation Code from the database! If you have phpBB-Plus you must install the phpBB international modules for the Visual Confirmation. Please read the references to phpBB-Plus in the "add_ons" folder of the CrackerTracker MOD Package!',
	'ctracker_login_success' => 'Your Account has been reactivated.<br /><br />Click <a href="%s">HERE</a> to go back to Login.',
	'ctracker_code_count' => 'The number of entries of Visual Confirmation has exceeded the limit for this session.',

/*
* Language Strings used in login_captcha.php
*/
	'ctracker_login_title' => 'CrackerTracker Account Activation',
	'ctracker_login_logged' => 'Logged In Users cannot access the site.',
	'ctracker_login_confim' => 'The number of wrong Logins for your Account has been reached. Therefore your Account has been locked and will have to be reactivated using Visual Confirmation.<br /><br />Please type in the following code and click on "Unlock" to unlock your account. When this is done you can log in again.',
	'ctracker_login_button' => 'Activate',

/*
* Language Strings for IP Warning Engine
*/
	'ctracker_ipwarn_info' => 'IP Range Scanning for your Account is <b>%s</b>',
	'ctracker_ipwarn_prof' => 'IP Range Scanner',
	'ctracker_ipwarn_pdes' => 'The IP Range Scanner checks the so-called IP Range for changes. If someone has logged into your account from another location you will get a short message (also if you are or have previously logged in from a different location).<br /> Please check the footer to see if the warning feature is still activated, as an aggressor could have deactivated this. Your Login however remains active, so you still have the ability to make changes after this warning.',
	'ctracker_ipwarn_chng' => '<b>&raquo; ADVICE &laquo;</b><br />The IP Range for your account has changed. The actual Login took place from <b>%s</b>, the previous from <b>%s</b>. If you didn\'t log on previously from another location, then maybe an aggressor has used your account without authorization!',
	'ctracker_ipwarn_welc' => '<b>&raquo; ADVICE &laquo;</b><br />The IP Range Scanner for your Account has not been initialised yet. This happens after two Logins. If you would like to initialise the Scanner now, please log in and out twice.',
	'ctracker_ipwarn_send' => 'Save settings',

/*
* Language Strings for Login History
*/
	'ctracker_lhistory_h' => 'Login History',
	'ctracker_lhistory_i' => 'View your recorded IP addresses and the login-times for your last <b>%s</b> login\'s and see if your account was used by someone else. If there are unknown log-in times or IP addresses in the Login History - it is possible that someone knows your password. In this case you should change the password for your account and also check your e-mail account URL.',
	'ctracker_lhistory_h1' => 'Login Date and Time',
	'ctracker_lhistory_h2' => 'Saved IP address',
	'ctracker_lhistory_nav' => 'CrackerTracker Login History',
	'ctracker_lhistory_err' => 'You must be logged in to use the features of CrackerTracker.',
	'ctracker_lhistory_off' => 'Login History was deactivated by Admin.',

/*
* Other Language Strings used in the Board itself
*/
	'ctracker_gmb_link' => 'The Admin has written an important note to all users. This note can be seen here:<br /><br /><a href="%s">%s</a><br />',
	'ctracker_gmb_mark' => 'Mark Post Read',
	'ctracker_gmb_markip' => 'Remove tip',
	'ctracker_gmb_loginlink' => 'Login Security',
	'ctracker_gmb_1stadmin' => 'The Setup or Settings of the first Admin cannot be changed.',
	'ctracker_gmb_pu_1' => '<b>CrackerTracker - Misconfiguration</b><br /><br />Port 21 is used for FTP Services. If the Forum is directed to use this Port, the Forum will no longer be executable. This is because Browsers use this Port for FTP as well.',
	'ctracker_gmb_pu_2' => '<b>CrackerTracker - Misconfiguration</b><br /><br />The Session length is set undersize! and you will always be logged out of the Forum before you can correct the setting.',
	'ctracker_gmb_pu_3' => '<b>CrackerTracker - Misconfiguration</b><br /><br />The Script-path begins and/or ends either without a Slash (/www/) or doesn\'t only contain the Slash (/)!',
	'ctracker_gmb_pu_4' => '<b>CrackerTracker - Misconfiguration</b><br /><br />The Server-name doesn\'t end with a Slash (/) !',
	'ctracker_binf_spammer' => 'The Anti-Spam Security System has determined that you have reached your maximum number of posts within %s seconds. If you try to write another post within <b>%s</b> seconds, your account will be <b>blocked!</b><br /><br />Please wait! as this is necessary for blocking spammers.',
	'ctracker_binf_sban' => 'The Spam Block System has banned your account because you have been identified as a spammer.',
	'ctracker_sendmail_info' => 'Due to security reasons you are only allowed to send an e-mail every %s minutes.',
	'ctracker_pwreset_info' => 'Due to security reasons it is not possible to send a new password every %s minutes. Please contact the administrator if you are having difficulties!',
	'ctracker_vc_guest_post' => 'Visual Confirmation for Guests',
	'ctracker_vc_guest_expl' => 'Anti-Spam Security: Please enter the following code before submitting your post.',
	'ctracker_dbg_mode' => '<b>CrackerTracker runs on DEBUG MODE. This should not be a permanent condition.<br />Please set back to normal mode as soon as possible.<br /><br /><u>This message cannot be deleted!</u></b>',
	)
);

?>