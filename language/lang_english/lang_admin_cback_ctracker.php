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


/*
 * Language Strings used for the ACP Menu points
 */
$lang['ctracker_module_category'] = 'CrackerTracker';
$lang['ctracker_module_1'] = 'Checksum Scanner';
$lang['ctracker_module_2'] = 'Credits';
$lang['ctracker_module_3'] = 'Filescanner';
$lang['ctracker_module_4'] = 'Global News';
$lang['ctracker_module_5'] = 'IP &amp; Agent Blocker';
$lang['ctracker_module_6'] = 'Log Manager';
$lang['ctracker_module_7'] = 'Maintenance & Tests';
$lang['ctracker_module_8'] = 'Miserable User';
$lang['ctracker_module_9'] = 'Settings';
$lang['ctracker_module_10'] = 'Recovery';
$lang['ctracker_module_11'] = 'Footer';


/*
 * Language Strings used in ACP Modules itself
 */
$lang['ctracker_wrong_module'] = 'Unknown module number';
$lang['ctracker_img_descriptions'] = 'Picture';
$lang['ctracker_set_catname1'] = 'IP, Proxy & UserAgent Blocker';
$lang['ctracker_set_catname2'] = 'Search Protection System';
$lang['ctracker_set_catname3'] = 'Login Protection System';
$lang['ctracker_set_catname4'] = 'Automatic Spam Detection';
$lang['ctracker_set_catname5'] = 'Registration Protection System';
$lang['ctracker_set_catname6'] = 'Check Password';
$lang['ctracker_set_catname7'] = 'General Safety Features';
$lang['ctracker_set_catname8'] = 'Other Settings';
$lang['ctracker_settings_head'] = 'CrackerTracker Settings';
$lang['ctracker_settings_expl'] = '<b>Customise all Settings of the CrackerTracker Security System.</b>';
$lang['ctracker_button_submit'] = 'Save Settings';
$lang['ctracker_button_reset'] = 'Restore';

$lang['ctracker_settings_m1'] = 'Activate IP Blocker';
$lang['ctracker_settings_e1'] = 'Enable or Disable the IP, Proxy and UserAgent Blocker.';
$lang['ctracker_settings_m2'] = 'IP Blocker Log size';
$lang['ctracker_settings_e2'] = 'Set the number of entries for the log file of the IP blocker. If the number of entries exceeds the limit, the log file will be automatically deleted in order to save Web space.';
$lang['ctracker_settings_m3'] = 'Activate Search Protection';
$lang['ctracker_settings_e3'] = 'Enable or Disable the Search Protection System.';
$lang['ctracker_settings_m4'] = 'Search Time for users';
$lang['ctracker_settings_e4'] = 'If search protection is enabled: Set the waiting time (in seconds) for registered users until they can search again. ';
$lang['ctracker_settings_m5'] = 'Number of Searches for users';
$lang['ctracker_settings_e5'] = 'Set the number of searches that registered users can make in the time interval indicated above. If this number is exceeded, further searches will be blocked for the time shown above to reduce server load.';
$lang['ctracker_settings_m6'] = 'Search Time for guests';
$lang['ctracker_settings_e6'] = 'Set the Time period (in seconds) guests have to wait if the Search Protection System is activated.';
$lang['ctracker_settings_m7'] = 'Number if Searches for guests';
$lang['ctracker_settings_e7'] = 'Set how many searches in a specified time period guests are allowed to do?. If the number exceeds the limit, further searches will be blocked for the time shown above to reduce server load.';
$lang['ctracker_settings_m8'] = 'Turn on Login Protection';
$lang['ctracker_settings_e8'] = 'Enable or Disable the Login Protection System of CrackerTracker.';
$lang['ctracker_settings_m9'] = 'Log size for wrong Logins';
$lang['ctracker_settings_e9'] = 'Set how many failed Login entries are to be saved before they are automatically deleted in order to save Web space.';
$lang['ctracker_settings_m10'] = 'Number of Logins up to the Visual Confirmation';
$lang['ctracker_settings_e10'] = 'Set how often a user may fail to log in until the protection of BruteForce Attacks is activated and Visual Confirmation is required.';
$lang['ctracker_settings_m11'] = 'Login History';
$lang['ctracker_settings_e11'] = 'Enable or Disable the Login History for users.';
$lang['ctracker_settings_m12'] = 'Entries in the Login History per user';
$lang['ctracker_settings_e12'] = 'Set how many successful Logins from each user to be saved in the Login History. Each user has the option to check the times and IP addresses of their Login.';
$lang['ctracker_settings_m13'] = 'Login IP Feature';
$lang['ctracker_settings_e13'] = '<b>Enable or Disable the Login IP System:</b> The IP Protection System checks for changes to IP addresses. Each user has the option to enable or disable the System on the Login Security page. The user will be informed if their IP Range has been modified since their last Login, and/or if anyone has logged on from a different location.';
$lang['ctracker_settings_m14'] = 'Spammer Detection';
$lang['ctracker_settings_e14'] = 'Set the mode for Automatic Spammer Detection.';
$lang['ctracker_settings_m15'] = 'Spammer Time Period';
$lang['ctracker_settings_e15'] = 'Set the time period (in seconds) for when the users\' posts will be counted for Spammer detection.';
$lang['ctracker_settings_m16'] = 'Spammer Post number';
$lang['ctracker_settings_e16'] = 'Set the allowed number of posts for a period of time. If this number is exceeded the user will be identified as Spammer.';
$lang['ctracker_settings_m17'] = 'Spammer Logsize';
$lang['ctracker_settings_e17'] = 'Set the Log size in (Kb) where users identified as Spammers will be recorded.';
$lang['ctracker_settings_m18'] = 'Register Protection';
$lang['ctracker_settings_e18'] = 'Enable or Disable the Registration Protection.';
$lang['ctracker_settings_m19'] = 'Block Time for Registration';
$lang['ctracker_settings_e19'] = 'Set the time (in seconds) allowed between two registrations.';

$lang['ctracker_settings_m21'] = 'IP Watcher';
$lang['ctracker_settings_e21'] = 'By enabling this feature, a user with an identical IP Address can only register once until someone has registered from a different IP Address.';
$lang['ctracker_settings_m22'] = 'Password Validity';
$lang['ctracker_settings_e22'] = 'Enable or Disable Checking of Validity of Password for all users.';
$lang['ctracker_settings_m23'] = 'Validity of Password in days';
$lang['ctracker_settings_e23'] = 'Set how long (in days) User passwords will be valid for before the user is notified that the password should be changed.';
$lang['ctracker_settings_m24'] = 'Password Complexity Check';
$lang['ctracker_settings_e24'] = 'Enable this feature to check the complexity of the User\'s passwords.';
$lang['ctracker_settings_m25'] = 'Password Complexity Mode';
$lang['ctracker_settings_e25'] = 'Set the required Characters in passwords.';
$lang['ctracker_settings_m26'] = 'Password Minimum Length';
$lang['ctracker_settings_e26'] = 'Set the minimum number of letters in a password.';
$lang['ctracker_settings_m27'] = 'Password Reset Checker';
$lang['ctracker_settings_e27'] = 'Enabling this allows you to reset a password once in a certain period of time (for users). This prevents attackers from using this feature to spam users using Resetmails.';
$lang['ctracker_settings_m28'] = 'Password Reset Period of Time';
$lang['ctracker_settings_e28'] = 'Time period (in minutes) users may reset their password.';
$lang['ctracker_settings_m29'] = 'E-mail Monitoring';
$lang['ctracker_settings_e29'] = 'Enable or Disable this feature where users can only use the internal Board Mailfunction once in the given period of time. This prevents spamming.';
$lang['ctracker_settings_m30'] = 'E-mail Span Of Time';
$lang['ctracker_settings_e30'] = 'Time period (in minutes) between two E-Mails users can send using the internal Mailfunction.';
$lang['ctracker_settings_m31'] = 'Auto Recovery';
$lang['ctracker_settings_e31'] = 'Enable or Disable the feature to save the Settings of the Board automatically. If this does not work you can use last known running configuration.';
$lang['ctracker_settings_m32'] = 'Visual Confirmation for Guests';
$lang['ctracker_settings_e32'] = 'By enabling this feature, Guests must enter a visual code before submitting any new posts. This protects from automatic Spam-bots.';
$lang['ctracker_settings_m33'] = 'Disposable-Mailservice Protection';
$lang['ctracker_settings_e33'] = 'cTracker has an internal list of so-called Disposable-Mailservices. By enabling this feature, users with such Email Addresses will not be able to register.';
$lang['ctracker_settings_m34'] = 'Identification of incorrect configuration';
$lang['ctracker_settings_e34'] = 'By enabling this feature CrackerTracker checks the general settings of Icy Phoenix for validity. So you can\'t damage your site by misconfiguration!';
$lang['ctracker_settings_m35'] = 'Spammer Detection Boost';
$lang['ctracker_settings_e35'] = 'By enabling this feature cTracker will look for Spammers or Spam-Posts. Most of them will be blocked.';
$lang['ctracker_settings_m36'] = 'Spammer Keyword Check';
$lang['ctracker_settings_e36'] = 'By enabling "Spammer Detection Boost", keywords in Profile and/or Posts will be scanned to identify Spammers.<br /><b>Please Note:</b> There is a possibility for detecting false information from new users. Please check the Log file for Spammer detection.';


/*
 * Credits page in ACP
 */
$lang['ctracker_credits_head'] = 'Credits';
$lang['ctracker_credits_subhead'] = 'Credits of CBACK CrackerTracker. Here we\'ll give you more information about security and this is also a way to say "Thank You".';
$lang['ctracker_credits_donate'] = 'Donate';
$lang['ctracker_credits_donate_expl'] = 'Do you like <b>CBACK CrackerTracker Professional</b>? Then it would be nice if you donated to the CBACK Project using a PayPal Donation to help reduce the costs of the server. This will help us to Further Develop and to go on with our non-profit project; So we will be able to provide CrackerTracker for free in the future. <br /><br />Thank you very much for your support.';
$lang['ctracker_credits_credits'] = 'Credits';
$lang['ctracker_credits_credits_1'] = 'Idea & Implementation';
$lang['ctracker_credits_credits_2'] = 'Author and Support';
$lang['ctracker_credits_credits_3'] = 'Icons';
$lang['ctracker_credits_credits_4'] = 'Official Download site';
$lang['ctracker_credits_moddownload'] = 'CrackerTracker MOD Download';
$lang['ctracker_credits_thanks'] = 'Thanks to...';
$lang['ctracker_credits_thanks_text'] = 'I would like to say thank you to the following persons:';
$lang['ctracker_credits_thanks_to'] = '<b>Ideas, Safety tests and Proofreading</b><br />Tekin Birdüzen<br /><i>(<a href="http://www.cybercosmonaut.de" target="_blank">cYbercOsmOnauT</a>)</i><br /><br /><br /><br /><b>Ideas:</b><br />Bernhard Jaud<br /><i>(GenuineParts)</i><br /><br /><br /><br /><b>Translator (English)</b><br />Marc Renninger<br /><i>(mc-dragon)</i><br /><br /><br /><br /><b>Corrector (English)</b><br />George <br />Sommerset<br /><i>(<a href="http://www.englisch-hilfen.de" target="_blank">www.englisch-hilfen.de</a>)</i><br /><br /><br /><br /><b>Beta Tester</b><br />Thanks to all participants of Beta-Tests<br />to the CBACK Premium users and of course to<br />our colleagues of the "Mod-Scene" who helped with Beta Tests and Proof-reading, too.</i>';
$lang['ctracker_credits_info'] = 'More Safety?';
$lang['ctracker_credits_info_text'] = 'The perfect add-on for Icy Phoenix and the CrackerTracker: For optimal safety we recommend the Mod <b>Advanced Visual Confirmation</b> by AmigaLink. This MOD expands the CAPTCHA feature of phpBB and CrackerTracker Professional with a more complex system which cannot be read by Bots. This MOD you can download from <a href="http://www.amigalink.de" target="_blank">www.AmigaLink.de</a>.<br /><br /><br /><br />We suggest that you also integrate this MOD into your Board for additional security.';


/*
 * File Hash Check in ACP
 */
$lang['ctracker_fchk_head'] = 'CrackerTracker Checksum Scanner';
$lang['ctracker_fchk_subhead'] = '<b>Create a checksum of each PHP file on your Board.</b><br /> Click on "Create or upgrade Checksums". Afterwards, you have the possibility with \'Verify File changes\' to determine whether or not the files have changed since last producing checksums. If files have changed without your prior knowledge it could be a sign that someone had gained access to your forum site. Pay attention to the last time that you checked to see if an unauthorized person activated the checksum scanner!<br /><br /><b>Please Note:</b> Not all servers support this feature. Occasionally it can come to Script Timeout if the server takes too long to produce the Icy Phoenix file list. Other servers stop the procedure since it is quite performance intensive.<br /><br />&raquo; The last actualization of the file check totals took place <b>%s</b>.';
$lang['ctracker_fchk_funcheader'] = 'Features';
$lang['ctracker_fchk_tableheader'] = 'System Output';
$lang['ctracker_fchk_option1'] = 'Create or upgrade Checksums';
$lang['ctracker_fchk_option2'] = 'Verify File changes';
$lang['ctracker_fchk_select_action'] = 'Please choose an action!';
$lang['ctracker_fchk_update_action'] = 'Checksums were updated!';
$lang['ctracker_fchk_tablehead1'] = 'File path';
$lang['ctracker_fchk_tablehead2'] = 'State';
$lang['ctracker_file_unchanged'] = 'UNMODIFIED';
$lang['ctracker_file_changed'] = 'MODIFIED';
$lang['ctracker_file_deleted'] = 'DELETED';


/*
 * File Safety Scanner in ACP
 */
$lang['ctracker_fscan_complete'] = 'The File scan was executed successfully. Please click on "Show Results" to see the results. You can correct the files.<br /><br /><br /><u>Please Note:</u><br /><br />Occasionally it can happen that CrackerTracker detects a file as insecure. This can happen as PHP files can be very different, and sometimes a developer wants the code to be writable from outside. In this case - and ONLY if are absolutely sure you can tell CrackerTracker that this file is secure. To do this add to the file at the very beginning "AFTER" ?php - the following code: <br /><br /><i>// CTracker_Ignore: File Checked By Human</i><br /><br />If you are unsure on what to do, you can also visit the <a href="http://www.community.cback.de" target="_blank">CBACK Community</a> for more detailed instructions.';
$lang['ctracker_fscan_unchecked'] = 'NOT CHECKED';
$lang['ctracker_fscan_ok'] = 'SAFE';
$lang['ctracker_fscan_prob_1'] = 'extension.inc not / or included too late';
$lang['ctracker_fscan_prob_2'] = 'IP_ROOT_PATH may not be initialised correctly';
$lang['ctracker_fscan_prob_3'] = 'common.php / pagestart.php may have not be included or included too late.';
$lang['ctracker_fscan_prob_4'] = 'Code in the file is possibly executable from beyond Icy Phoenix';
$lang['ctracker_fscan_prob_5'] = 'extension.inc is missing and / or IP_ROOT_PATH and / or constant not found';
$lang['ctracker_fscan_prob_def'] = 'An undefined case occurred during scanning';
$lang['ctracker_fscan_important'] = 'Please Read This!';
$lang['ctracker_fscan_sel_action'] = 'To start the check of all files please click on "Start Filecheck". When this is completed click on "Show Results" to show the results of the check. This list can be retrieved any time using the ACP until a new check is started.<br /><br />For technical reasons it is not possible to give <u>unambiguous</u> and <u>unfailing</u> information about the security of a PHP Script. So don\'t be too certain. It can happen, that the scanner classifies a secure file as insecure, and vice versa. PHP code is complex - so there can\'t be a hundred
percent guarantee that there won\'t be insecure scripts anymore. ;-)<br /><br />This scanner is specialised to detect security holes in included files. With this scanner you can easily find these risks and correct them.<br /><br />For more detailed instructions please visit CBACK Community!<br /><br />';
$lang['ctracker_fscan_head'] = 'CBACK CrackerTracker Security Scanner';
$lang['ctracker_fscan_subhead'] = 'The Security scanner checks all PHP files of your Forum to try to detect security holes which could be exploited by Worms. These holes can be accessed from outside Icy Phoenix by not having the protection of the board security, or the CrackerTracker System. This scan presents the opportunity to correct those files.<br /><br /><b>Please note:</b> The algorithm of this Scanner is on one\'s best optimised and not all servers support this feature! With very large Boards it can occur that this performance-intensive Scan-system oversteps the PHP Execution Time and fails.<br /><br /><b><em>Please consider this if it fails.</em></b><br /><br />&raquo; The last check took place at <b>%s</b>.';
$lang['ctracker_fscan_option1'] = 'Start Filecheck';
$lang['ctracker_fscan_option2'] = 'Show Results';


/*
 * Global message in ACP
 */
$lang['ctracker_glob_msg_head'] = 'Global Message';
$lang['ctracker_glob_msg_subhead'] = '<b>Leave a global message to all users.</b><br /> This message will be seen by the user on their next Login. You have the option to either refer to a thread or to write your own text.<br />
<b>Note:</b> There is an imposed text limit of (255 characters).';
$lang['ctracker_glob_msg_entry'] = 'Set global message ';
$lang['ctracker_glob_msg_submit'] = 'Insert';
$lang['ctracker_glob_msg_reset'] = 'Cancel Message';
$lang['ctracker_glob_msg_type'] = 'Type of global message';
$lang['ctracker_glob_type_1'] = 'Text';
$lang['ctracker_glob_type_2'] = 'Link';
$lang['ctracker_glob_msg_txt'] = 'Text of global message';
$lang['ctracker_glob_msg_link'] = 'Link Destination in the message';
$lang['ctracker_glob_msg_reset'] = 'Cancel current message';
$lang['ctracker_glob_res_txt'] = 'When you click on "Cancel current message" any current message will be cancelled.';
$lang['ctracker_glob_msg_saved'] = 'The global message was successfully saved.<br /><br />Click <a href="%s">HERE</a> to go back to CrackerTracker Management.';
$lang['ctracker_glob_msg_reset_ok'] = 'The global message was deleted from the user table. The entered message will not be shown any more.<br /><br />Click <a href="%s">HERE</a> to go back to CrackerTracker Management.';
$lang['ctracker_dbg_mode'] = '<b>CrackerTracker runs on DEBUG MODE. This should not be a permanent condition.<br />Please set back to normal mode as soon as possible.<br /><br /><u>This message cannot be deleted!</u></b>';

/*
 * IP&Agent Blocker
 */
$lang['ctracker_ipb_delete'] = 'Delete Entry';
$lang['ctracker_ipb_blocklist'] = 'Block list entries';
$lang['ctracker_ipb_head'] = 'Proxy, IP & UserAgent Blocker';
$lang['ctracker_ipb_description'] = '<b>Manage the Blocklist for the cTracker Proxy, IP and UserAgent Blocker.</b><br /> You can delete existing entries and / or add new ones. With a new entry you have the option to use (*) to enter any combination out of the filter in the list. For example: lwp* locks lwp-1 as well as lwp-simple etc. or 100.*.*.* locks all IP-Addresses beginning with 100. .<br /><br /><b>CAUTION</b> Be careful that you don\'t lock your own UserAgent or IP-Address. Otherwise you are out of your Forum!';


/*
 * Log Manager
 */
$lang['ctracker_log_manager_title'] = 'Logfile Manager';
$lang['ctracker_log_manager_subtitle'] = 'Show or delete all Logfiles from CrackerTracker.';
$lang['ctracker_log_manager_overview'] = 'Log Manager Overview';
$lang['ctracker_log_manager_blocked'] = 'CrackerTracker has blocked <b>%s</b> attacks so far.';
$lang['ctracker_log_manager_overview'] = 'Logfile Overview';
$lang['ctracker_log_manager_head1'] = 'Logname';
$lang['ctracker_log_manager_head2'] = 'Number of entries';
$lang['ctracker_log_manager_head3'] = 'Features';
$lang['ctracker_log_manager_name2'] = 'Worm & Exploit Protection';
$lang['ctracker_log_manager_name3'] = 'IP, Proxy & UserAgent Blocker';
$lang['ctracker_log_manager_name4'] = 'Incorrect Logins';
$lang['ctracker_log_manager_name5'] = 'Blocked Spammers';
$lang['ctracker_log_manager_name6'] = 'Debug Entries';
$lang['ctracker_log_manager_view'] = 'VIEW';
$lang['ctracker_log_manager_delete'] = 'DELETE';
$lang['ctracker_log_manager_delete_all'] = 'Delete All Logfiles';
$lang['ctracker_log_manager_deleted'] = 'The log file has been deleted successfully!';
$lang['ctracker_log_manager_all_deleted'] = 'All log files have been deleted successfully!';
$lang['ctracker_log_manager_showheader1'] = 'There is <b>one</b> entry in this log file. Click <b><a href="%s">HERE</a></b> to go back to Logfile overview.';
$lang['ctracker_log_manager_showheader'] = 'There are <b>%s</b> entries in this log file.<br />Click <b><a href="%s">HERE</a></b> to go back to Logfile overview.';
$lang['ctracker_log_manager_showlog'] = 'View Logfile';
$lang['ctracker_log_manager_cell_1'] = 'Date / Time';
$lang['ctracker_log_manager_cell_2a'] = 'Appeal';
$lang['ctracker_log_manager_cell_2b'] = 'Username';
$lang['ctracker_log_manager_cell_3'] = 'Referrer';
$lang['ctracker_log_manager_cell_4'] = 'UserAgent';
$lang['ctracker_log_manager_cell_5'] = 'IP Address';
$lang['ctracker_log_manager_cell_6'] = 'Remote Host';
$lang['ctracker_log_manager_sysmsg'] = 'Last clearing of the Logfile was <b>%s</b>.';


/*
 * Footer configuration
 */
$lang['ctracker_footer_head'] = 'Footer Management';
$lang['ctracker_footer_subhead'] = 'Choose which footer CrackerTracker should show in your Forum. Please do not change the footer and the link to www.cback.de!';
$lang['ctracker_select_footer'] = 'Choose Footer';
$lang['ctracker_footer_saveit'] = 'Accept Footer Layout';
$lang['ctracker_footer_done'] = 'Changes to Footer were saved successfully!';


/*
 * Maintenance Module in ACP
 */
$lang['ctracker_ma_unknown'] = '<span class="text_orange">UNKNOWN</span>';
$lang['ctracker_ma_secure'] = '<span class="text_green">SAFE</span>';
$lang['ctracker_ma_warning'] = '<span class="text_red">CAUTION</span>';
$lang['ctracker_ma_active'] = '<span class="text_green">ACTIVE</span>';
$lang['ctracker_ma_inactive'] = '<span class="text_red">INACTIVE</span>';
$lang['ctracker_ma_on'] = 'ON';
$lang['ctracker_ma_off'] = 'OFF';
$lang['ctracker_ma_ca'] = '<span class="text_green">OK</span>';
$lang['ctracker_ma_ci'] = '<span class="text_red">NOT SET</span>';
$lang['ctracker_ma_head'] = 'Maintenance and System check';
$lang['ctracker_ma_subhead'] = 'This system check automatically examines the cTracker safety modules for features, and shows tips on how to optimize your system.';
$lang['ctracker_ma_systest'] = 'Automatic System Test';
$lang['ctracker_ma_sectest'] = 'Security Test';
$lang['ctracker_ma_maint'] = 'Service Function';
$lang['ctracker_ma_name_1'] = 'Worm & Exploit protection System';
$lang['ctracker_ma_name_2'] = 'Variable Control Unit';
$lang['ctracker_ma_name_3'] = 'IP, Proxy & UserAgent Protection Unit';
$lang['ctracker_ma_name_4'] = 'Worm Heuristics Definitions Batch - Number of Definitions: <b>%s</b>';
$lang['ctracker_ma_syshead_1'] = 'Security Module';
$lang['ctracker_ma_syshead_2'] = 'Status';
$lang['ctracker_ma_seccheck_1'] = 'Checkpoint';
$lang['ctracker_ma_seccheck_2'] = 'Version / Status';
$lang['ctracker_ma_seccheck_3'] = 'Reference';
$lang['ctracker_ma_seccheck_4'] = 'Status';
$lang['ctracker_ma_scheck_1'] = 'PHP Version (<a href="http://www.php.net" target="_blank">Visit Website</a>)';
$lang['ctracker_ma_scheck_2'] = '&raquo; PHP SAFE MODE';
$lang['ctracker_ma_scheck_3'] = '&raquo; PHP GLOBALS';
$lang['ctracker_ma_scheck_4'] = 'Icy Phoenix Version (<a href="http://www.icyphoenix.com" target="_blank">Visit Website</a>)';
$lang['ctracker_ma_scheck_4a'] = '&raquo; Visual Confirmation';
$lang['ctracker_ma_scheck_4b'] = '&raquo; Account Activation';
$lang['ctracker_ma_scheck_5'] = 'CBACK CrackerTracker (<a href="http://www.cback.de" target="_blank">Visit Website</a>)';
$lang['ctracker_ma_chmod'] = '<b>CHMOD777 Status:</b> ';
$lang['ctracker_ma_desc_link'] = 'EXECUTE NOW';
$lang['ctracker_ma_desc1'] = '<b>Clear IP, Proxy & UserAgent Table</b><br />Delete <u>all</u> entries from IP, Proxy & UserAgent Table.';
$lang['ctracker_ma_desc2'] = '<b>Factory setting: IP, Proxy & UserAgent Blocker</b><br />Restore the default status of the IP, Proxy & user agent database tables. Your filters are lost, however!';
$lang['ctracker_ma_desc3'] = '<b>Delete Login History</b><br />Delete all entries from Login History, regardless of the user or the adjusted number of saves per user.';
$lang['ctracker_ma_desc4'] = '<b>Clear File-Hash checktable</b><br />Delete all saved entries from the File-Hashcheck table.';
$lang['ctracker_ma_desc5'] = '<b>Clear Security scanner Table</b><br />Delete all results that were stored during the file security examination of the data base.';
$lang['ctracker_ma_succ_main'] = 'Process executed successfully!';
$lang['ctracker_ma_err_main'] = 'Process executed unsuccessfully!';


/*
 * Miserable User Module in ACP...
 */
$lang['ctracker_mu_subhead'] = 'A user tagged as "Miserable user" will only have their posts visible to themselves and to the Admin\'s of the board. This assumes that they will soon get tired of getting no responses to their threads or posts and leave the board.<br /><br />
<b>Note:</b> This function only makes the posts disappear from a thread. Using "Quote" or "Search" will reveal those hidden posts"!';

$lang['ctracker_mu_select'] = 'Mark user as Miserable User';
$lang['ctracker_mu_find'] = 'Look for Usernames';
$lang['ctracker_mu_send'] = 'Enter Usernames';
$lang['ctracker_mu_entr'] = 'Marked Usernames';
$lang['ctracker_mu_uname'] = 'Entered Username';
$lang['ctracker_mu_remove'] = 'Delete Entries';
$lang['ctracker_mu_no_defined'] = 'There are no users marked as "Miserable User" up to now.';


/*
 * Recovery feature in ACP
 */
$lang['ctracker_rec_head'] = 'System Recovery';
$lang['ctracker_rec_subhead'] = 'Back up the Configuration Table from your Forum or you can go to the last running configuration. If you have activated this feature in the general settings of CrackerTracker, then it will be backed up every time you change the General Settings. (CAUTION! It is <b>NOT</b> a Backup of the complete database!)<br /><br />When you are not in the ACP after you have changed settings, then you can reactivate the last running configuration using the Emergency Console of CrackerTracker, also. Please read the file comment in <i>ctracker/emergency.php</i> for more instructions of Forum configurations in an emergency. Please note, that this file has to be enabled before using.<br /><br /><b>CAUTION!</b> This feature should be only used when there is a serious problem!';
$lang['ctracker_rec_last_saved'] = 'Last Backup of the Configuration Table: <b>%s</b>';
$lang['ctracker_rec_never_saved'] = 'The Configuration Table has not been backed up so far!';
$lang['ctracker_rec_backup'] = '<span class="gen">Backup the Configuration Table</span>';
$lang['ctracker_rec_restore'] = '<span class="gen">Recover the last running Configuration Table</span>';
$lang['ctracker_rec_succ'] = '<span class="gen">The database process has been executed successfully.</span>';
$lang['ctracker_rec_pab'] = '<span class="gen">Recovery is not available before you have made a successful Backup!</span>';

?>