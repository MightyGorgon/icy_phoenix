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
* Xore (mods@xore.ca)
* Lopalong
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

// Admin menu
$lang['Cmcat_main'] = 'Main';
$lang['Cmcat_addons'] = 'Add-ons';
$lang['Cmcat_other'] = 'Other';
$lang['Cmcat_help'] = 'Help';

$lang['Cash_Configuration'] = 'Cash&nbsp;Configuration';
$lang['Cash_Currencies'] = 'Cash&nbsp;Currencies';
$lang['Cash_Exchange'] = 'Cash&nbsp;Exchange';
$lang['Cash_Events'] = 'Cash&nbsp;Events';
$lang['Cash_Forums'] = 'Cash&nbsp;Forums';
$lang['Cash_Groups'] = 'Cash&nbsp;Groups';
$lang['Cash_Help'] = 'Cash&nbsp;Help';
$lang['Cash_Logs'] = 'Cash&nbsp;Logs';
$lang['Cash_Settings'] = 'Cash&nbsp;Settings';

$lang['Cmenu_cash_config'] = 'Global Cash Mod Settings that affect all Currencies';
$lang['Cmenu_cash_currencies'] = 'Add, Remove or reorder Currencies';
$lang['Cmenu_cash_settings'] = 'Specific settings for each Currency';
$lang['Cmenu_cash_events'] = 'Cash Amounts to give out on user events';
$lang['Cmenu_cash_reset'] = 'Reset or Recount Cash amounts';
$lang['Cmenu_cash_exchange'] = 'Enable/Disable Currency exchange, exchange rates';
$lang['Cmenu_cash_forums'] = 'Turn Currencies on or off for each forum';
$lang['Cmenu_cash_groups'] = 'Custom Settings for specific usergroups, ranks and levels';
$lang['Cmenu_cash_log'] = 'View/Delete Logged Cash Mod actions';
$lang['Cmenu_cash_help'] = 'Cash Mod help';

// Config
$lang['Cash_config'] = 'Cash Mod Configuration';
$lang['Cash_config_explain'] = 'Set your Cash Mod configuration.';

$lang['Cash_admincp'] = 'Cash Mod AdminCP Mode';
$lang['Cash_adminnavbar'] = 'Cash Mod Navbar';
$lang['Sidebar'] = 'Sidebar';
$lang['Menu'] = 'Menu';

$lang['Messages'] = 'Messages';
$lang['Spam'] = 'Spam';
$lang['Click_return_cash_config'] = 'Click %sHere%s to return to Cash Mod Configuration';
$lang['Cash_config_updated'] = 'Cash Mod Configuration Updated Successfully';
$lang['Cash_disabled'] = 'Disable Cash Mod';
$lang['Cash_message'] = 'Show Earnings in Post/Reply confirmation screen';
$lang['Cash_display_message'] = 'Message to display user earnings';
$lang['Cash_display_message_explain'] = 'Must have exactly one "%s" in it';
$lang['Cash_spam_disable_num'] = 'Number of posts to disable earnings after (spam prevention)';
$lang['Cash_spam_disable_time'] = 'Time period over which these posts must exceed (hours)';
$lang['Cash_spam_disable_message'] = 'Spam announcement for null earnings';

// Currencies
$lang['Cash_currencies'] = 'Cash Mod Currencies';
$lang['Cash_currencies_explain'] = 'Manage your currencies.';

$lang['Click_return_cash_currencies'] = 'Click %sHere%s to return to Cash Mod Currencies';
$lang['Cash_currencies_updated'] = 'Cash Mod Currencies Updated Successfully';
$lang['Cash_field'] = 'Field';
$lang['Cash_currency'] = 'Currency';
$lang['Name_of_currency'] = 'Name of Currency';
$lang['Default'] = 'Default';
$lang['Cash_order'] = 'Order';
$lang['Cash_set_all'] = 'Set for all users';
$lang['Cash_delete'] = 'Delete Currency';
$lang['Decimals'] = 'Decimals';

$lang['Cash_confirm_copy'] = 'Copy all user\'s %s data to %s?<br />This cannot be undone';
$lang['Cash_confirm_delete'] = 'Delete %s?<br />This cannot be undone';

$lang['Cash_copy_currency'] = 'Copy Currency Data';

$lang['Cash_new_currency'] = 'Create new Currency';
$lang['Cash_currency_dbfield'] = 'Database field for currency';
$lang['Cash_currency_decimals'] = 'Number of decimals for currency';
$lang['Cash_currency_default'] = 'Default value for currency';

$lang['Bad_dbfield'] = 'Bad field name, must be in the form \'user_word\'<br /><br />%s<br /><br />Examples:<br />user_points<br />user_cash<br />user_money<br />user_warnings<br /><br />';

// 0 currencies (most admin panels won't work... )
$lang['Insufficient_currencies'] = 'You need to create Currencies before you can alter settings';

// Add-ons ?

// Events
$lang['Cash_events'] = 'Cash Mod Events';
$lang['Cash_events_explain'] = 'Set cash amounts to be given for custom events.';

$lang['No_events'] = 'No events listed';
$lang['Existing_events'] = 'Existing Events';
$lang['Add_an_event'] = 'Add an event';
$lang['Cash_events_updated'] = 'Cash Events Updated Successfully';
$lang['Click_return_cash_events'] = 'Click %sHere%s to return to Cash Events';

//Reset
$lang['Cash_reset_title'] = 'Cash Mod Reset';
$lang['Cash_reset_explain'] = 'Activate a global reset of all users\' Cash amounts';

$lang['Cash_resetting'] = 'Cash Resetting';
$lang['User_of'] = 'User %s of %s';

$lang['Set_checked'] = 'Set checked currencies';
$lang['Recount_checked'] = 'Recount checked currencies';

$lang['Cash_confirm_reset'] = 'Confirm resetting of selected currencies?<br />This cannot be undone';
$lang['Cash_confirm_recount'] = 'Confirm recounting of selected currencies?<br />This cannot be undone.<br /><br />This action is not recommended for boards with large a large amount of users and/or topics.<br /><br />It is recommended that you disable your board while this action is occurring. <br />You can disable your board via %sConfiguration%s';

$lang['Update_successful'] = 'Update successful!';
$lang['Click_return_cash_reset'] = 'Click %sHere%s to return to Cash Reset';
$lang['User_updated'] = '%s updated<br />';

// Others

// Exchange
$lang['Cash_exchange'] = 'Cash Mod Exchange';
$lang['Cash_exchange_explain'] = 'Set the relative value of your currencies, and enable users to exchange.';

$lang['Exchange_insufficient_currencies'] = 'You don\'t have enough Currencies created to create exchange rates<br />At least 2 are required';

// Forums
$lang['Forum_cm_settings'] = 'Cash Mod Forum Settings';
$lang['Forum_cm_settings_explain'] = 'Set which forums have Cash Mod enabled';

// Groups
$lang['Cash_groups'] = 'Cash Mod Groups';
$lang['Cash_groups_explain'] = 'Set special privileges on ranks, usergroups, administrators and moderators';

$lang['Click_return_cash_groups'] = 'Click %sHere%s to return to Cash Groups';
$lang['Cash_groups_updated'] = 'Cash Groups Updated Successfully';

$lang['Set'] = 'Set';
$lang['Up'] = 'Up';
$lang['Down'] = 'Down';

// Help
$lang['Cmh_support'] = 'Cash Mod Support';
$lang['Cmh_troubleshooting'] = 'Troubleshooting';
$lang['Cmh_upgrading'] = 'Upgrading';
$lang['Cmh_addons'] = 'Add-Ons';
$lang['Cmh_demo_boards'] = 'Demo Boards';
$lang['Cmh_translations'] = 'Translations';
$lang['Cmh_features'] = 'Features';

$lang['Cmhe_support'] = 'General Information';
$lang['Cmhe_troubleshooting'] = 'If you\'re having problems with Cash Mod, check here for fixes';
$lang['Cmhe_upgrading'] = 'You currently have version %s, upgrades will be posted here to the latest version';
$lang['Cmhe_addons'] = 'A list of MODs that take advantage of Cash Mod\'s features';
$lang['Cmhe_demo_boards'] = 'A list of some demo boards that use Cash Mod';
$lang['Cmhe_translations'] = 'A list of translations for Cash Mod';
$lang['Cmhe_features'] = 'A list of Cash Mod\'s features, and development on future versions';

// Logs
$lang['Logs'] = 'Cash Mod Logs';
$lang['Logs_explain'] = 'Logged Cash Mod events';

// Settings
$lang['Cash_settings'] = 'Cash Mod Settings';
$lang['Cash_settings_explain'] = 'Customize all the Currency Settings.';


$lang['Display'] = 'Display';
$lang['Implementation'] = 'Implementation';
$lang['Allowances'] = 'Allowances';
$lang['Allowances_explain'] = 'Allowances requires the Cash Mod Allowances plug-in';
$lang['Click_return_cash_settings'] = 'Click %sHere%s to return to Cash Mod Settings';
$lang['Cash_settings_updated'] = 'Cash Mod Settings Updated Successfully';

$lang['Cash_enabled'] = 'Enable Currency';
$lang['Cash_custom_currency'] = 'Custom Currency for Cash Mod';
$lang['Cash_image'] = 'Display the currency as an image';
$lang['Cash_imageurl'] = 'Image (Relative to Icy Phoenix root path):';
$lang['Cash_imageurl_explain'] = 'Use this to define a small image associated with the currency';
$lang['Prefix'] = 'Prefix';
$lang['Postfix'] = 'Postfix';
$lang['Cash_currency_style'] = 'Currency style for Cash Mod';
$lang['Cash_currency_style_explain'] = 'Currency symbol as ' . $lang['Prefix'] . ' or ' . $lang['Postfix'];
$lang['Cash_display_usercp'] = 'Show earnings in UserCp';
$lang['Cash_display_userpost'] = 'Show earnings in Post Profile';
$lang['Cash_display_memberlist'] = 'Show earnings in the Memberlist';

$lang['Cash_amount_per_post'] = 'Amount of cash earned per new topic';
$lang['Cash_amount_post_bonus'] = 'Amount of bonus cash earned per reply for topic author';
$lang['Cash_amount_per_reply'] = 'Amount of cash earned per reply';
$lang['Cash_amount_per_character'] = 'Amount of cash earned per character';
$lang['Cash_amount_per_thanks'] = 'Amount of cash earned per thanks received';
$lang['Cash_maxearn'] = 'Maximum amount of cash earned for posting';
$lang['Cash_amount_per_pm'] = 'Amount of cash earned per private message';
$lang['Cash_include_quotes'] = 'Include quotes when calculating cash per character';
$lang['Cash_exchangeable'] = 'Allow users to exchange this currency';
$lang['Cash_allow_donate'] = 'Allow users to donate their cash to other users';
$lang['Cash_allow_mod_edit'] = 'Allow moderators to edit user\'s cash';
$lang['Cash_allow_negative'] = 'Allow users to have negative cash amounts';

$lang['Cash_allowance_enabled'] = 'Enable allowances';
$lang['Cash_allowance_amount'] = 'Amount of cash earned as allowances';
$lang['Cash_allownace_frequency'] = 'How often allowances are given';
$lang['Cash_allownace_frequencies'][CASH_ALLOW_DAY] = 'Day';
$lang['Cash_allownace_frequencies'][CASH_ALLOW_WEEK] = 'Week';
$lang['Cash_allownace_frequencies'][CASH_ALLOW_MONTH] = 'Month';
$lang['Cash_allownace_frequencies'][CASH_ALLOW_YEAR] = 'Year';
$lang['Cash_allowance_next'] = 'Time until next allowance';

// Groups
$lang['Cash_status_type'][CASH_GROUPS_DEFAULT] = 'Default';
$lang['Cash_status_type'][CASH_GROUPS_CUSTOM] = 'Custom';
$lang['Cash_status_type'][CASH_GROUPS_OFF] = 'Off';
$lang['Cash_status'] = 'Status';

// Cash Mod Log Text
// Note: there isn't really a whole lot i can do about it, if languages use a
// grammar that requires these arguments (%s) to be in a different order, it's stuck in
// this order. The up side is that this is about 10x more comprehensive than the
// last way i did it.
//

/* argument order: [donater id][donater name][currency list][receiver id][receiver name]

eg.
Joe donated 14 gold, $10, 3 points to Peter
*/
$lang['Cash_clause'][CASH_LOG_DONATE] = '<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a> donated <b>%s</b> to <a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a>';

/* argument order: [admin/mod id][admin/mod name][editee id][editee name][Added list][removed list][Set list]

eg.
Joe modified Peter's Cash:
Added 14 gold
Removed $10
Set 3 points
*/
$lang['Cash_clause'][CASH_LOG_ADMIN_MODEDIT] = '<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new">%s</a> edited <a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a>\'s Cash:<br />Added <b>%s</b><br />Removed <b>%s</b><br />Set to <b>%s</b>';

/* argument order: [admin/mod id][admin/mod name][currency name]

eg.
Joe created points
*/
$lang['Cash_clause'][CASH_LOG_ADMIN_CREATE_CURRENCY] = '<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a> created <b>%s</b>';

/* argument order: [admin/mod id][admin/mod name][currency name]

eg.
Joe deleted $
*/
$lang['Cash_clause'][CASH_LOG_ADMIN_DELETE_CURRENCY] = '<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a> deleted <b>%s</b>';

/* argument order: [admin/mod id][admin/mod name][old currency name][new currency name]

eg.
Joe renamed silver to gold
*/
$lang['Cash_clause'][CASH_LOG_ADMIN_RENAME_CURRENCY] = '<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a> renamed <b>%s</b> to <b>%s</b>';

/* argument order: [admin/mod id][admin/mod name][copied currency name][copied over currency name]

eg.
Joe copied users' gold to points
*/
$lang['Cash_clause'][CASH_LOG_ADMIN_COPY_CURRENCY] = '<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=%s" target="_new"><b>%s</b></a> copied users\' <b>%s</b> to <b>%s</b>';

$lang['Log'] = 'Log';
$lang['Action'] = 'Action';
$lang['Type'] = 'Type';
$lang['Cash_all'] = 'All';
$lang['Cash_admin'] = 'Admin';
$lang['Cash_user'] = 'User';
$lang['Delete_all_logs'] = 'Delete all logs';
$lang['Delete_admin_logs'] = 'Delete admin logs';
$lang['Delete_user_logs'] = 'Delete user logs';
$lang['All'] = 'All';
$lang['Day'] = 'Day';
$lang['Week'] = 'Week';
$lang['Month'] = 'Month';
$lang['Year'] = 'Year';
$lang['Page'] = 'Page';
$lang['Per_page'] = 'per page';

// Now for some regular stuff...

// User CP
$lang['Donate'] = 'Donate';
$lang['Mod_usercash'] = 'Modify %s\'s Cash';
$lang['Exchange'] = 'Exchange';

// Exchange
$lang['Convert'] = 'Convert';
$lang['Select_one'] = 'Select One';
$lang['Exchange_lack_of_currencies'] = 'There aren\'t enough Currencies for you to be able to exchange<br />To enable this feature, your admin needs to create at least 2 currencies';
$lang['You_have'] = 'You have';
$lang['One_worth'] = 'One %s is worth:';
$lang['Cannot_exchange'] = 'You cannot exchange %s, currently';

// Donate
$lang['Amount'] = 'Amount';
$lang['Donate_to'] = 'Donate to %s';
$lang['Donation_recieved'] = 'You have received a donation from %s';
$lang['Has_donated'] = '%s has donated [b]%s[/b] to you.' . "\n\n" . '%s wrote: ' . "\n";

// Mod Edit
$lang['Add'] = 'Add';
$lang['Remove'] = 'Remove';
$lang['Omit'] = 'Omit';
$lang['Amount'] = 'Amount';
$lang['Donate_to'] = 'Donate to %s';
$lang['Has_moderated'] = '%s has edited the balance of your %s';
$lang['Has_added'] = '[*]Added: [b]%s[/b]' . "\n";
$lang['Has_removed'] = '[*]Removed: [b]%s[/b]' . "\n";
$lang['Has_set'] = '[*]Set to: [b]%s[/b]' . "\n";

// That's all folks!

?>