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
* Nivisec.com (support@nivisec.com)
* Lopalong
*
*/

// Original Statistics Mod (c) 2002 Nivisec - http://nivisec.com/mods

// If you want to credit the Author on the Statistics Page, uncomment the second line.
//$lang['Version_info'] = '<br />Statistics Mod Version %s'; //%s = number
$lang['Version_info'] = 'Statistics Mod Version %s &copy; 2002 <a href="http://www.opentools.de/board">Acyd Burn</a>';

// These Language Variables are available for all installed Modules
$lang['Rank'] = 'Rank';
$lang['Percent'] = 'Percent';
$lang['Graph'] = 'Graph';
$lang['Uses'] = 'Uses';
$lang['How_many'] = 'How Many';

// Main Language

// Page Header/Footer
$lang['Install_info'] = 'Installed on %s'; //%s = date
$lang['Viewed_info'] = 'Statistics Page Loaded %d Times'; //%d = number
$lang['Statistics_title'] = 'Board Statistics';

// Admin Language
$lang['Statistics_management'] = 'Statistics Modules';
$lang['Statistics_config'] = 'Statistics Configuration';

// Statistics Config
$lang['Statistics_config_title'] = 'Statistics Configuration';

$lang['Return_limit'] = 'Return Limit';
$lang['Return_limit_desc'] = '<b>The number of items to include in each ranking.</b><br /> This is auto-parsed to all modules by being specified here.';
$lang['Clear_cache'] = 'Clear Module Cache';
$lang['Clear_cache_desc'] = 'Check Box to Clear all the current cached data for all modules.';
$lang['Modules_directory'] = 'Modules Directory';
$lang['Modules_directory_desc'] = 'The directory relative to the home Icy Phoenix directory where modules are located.<br /><b>Note:</b> A trailing / or \ must not be used!';

// Status Messages
$lang['Messages'] = 'Admin Messages';
$lang['Updated'] = 'Updated';
$lang['Active'] = 'Active';
$lang['Activate'] = 'Activate';
$lang['Activated'] = 'Activated';
$lang['Not_active'] = 'Not Active';
$lang['Deactivate'] = 'Deactivate';
$lang['Deactivated'] = 'Deactivated';
$lang['Install'] = 'Install';
$lang['Installed'] = 'Installed';
$lang['Uninstall'] = 'Uninstall';
$lang['Uninstalled'] = 'Uninstalled';
$lang['Move_up'] = 'Move Up';
$lang['Move_down'] = 'Move Down';
$lang['Update_time'] = 'Update Time';
$lang['Auth_settings_updated'] = 'Authorization Settings - [These are always updated]';

// Modules Management
$lang['Back_to_management'] = 'Back to the Modules Management Screen';
$lang['Statistics_modules_title'] = 'Statistics Module Management';

$lang['Module_name'] = 'Name';
$lang['Directory_name'] = 'Directory Name';
$lang['Status'] = 'Status';
$lang['Update_time_minutes'] = 'Update Time in Minutes';
$lang['Update_time_desc'] = 'Time Interval (in Minutes) for refreshing the cached data with new Data.';
$lang['Auto_set_update_time'] = '<span class="genmed"><b>Determine and set recommended Update Times for every Installed (and Active) Module. <br />Be aware: This may take a long time.</span></b>';
$lang['Uninstall_module'] = 'Uninstall Module';
$lang['Uninstall_module_desc'] = 'Marks the module with "not installed" status, so that you may re-install it with the install command.  It does not delete the module from your file system, you will manually need to delete the module folder to remove it completely.';
$lang['Active_desc'] = 'Option for if the Module is Active, so it is displayed depending on the set Permissions.';
$lang['Go'] = 'Go';

$lang['Not_allowed_to_install'] = 'You are not able to install this Module. Mostly this is because you haven\'t installed a Mod needed in order to run this Module. Please contact the Author of this Module if you have questions and if the Extra Info printed here makes no sense to you.';
$lang['Wrong_stats_mod_version'] = 'You are not able to install this Module, because your Statistics Mod Version does not match the Version required by the Module. In order to install and run the Module, you need at least Version %s of the Statistics Mod.'; // replace %s with Version (2.1.3 for example)
$lang['Module_install_error'] = 'There was an error while installing this module. More than likely some SQL commands could not be executed, check for failure messages above.';

$lang['Preview_debug_info'] = 'This Module was generated in %f seconds: %d queries were executed.'; // Replace %f with seconds and %d with queries
$lang['Update_time_recommend'] = 'The Statistics Mod recommends (depending on the debug info) an update time of <b>%d</b> Minutes.'; // Replace %d with Minutes

// Modules

$lang['module_name__stats_overview_section'] = 'Statistics overview';

$lang['module_name_admin_statistics'] = 'Administrative Statistics';
$lang['Statistic'] = 'Statistic';
$lang['Value'] = 'Value';
$lang['Number_posts'] = 'Number of posts';
$lang['Posts_per_day'] = 'Posts per day';
$lang['Number_topics'] = 'Number of topics';
$lang['Topics_per_day'] = 'Topics per day';
$lang['Number_users'] = 'Number of users';
$lang['Users_per_day'] = 'Users per day';
$lang['Board_started'] = 'Board started';
$lang['Avatar_dir_size'] = 'Avatar directory size';
$lang['Database_size'] = 'Database size';
$lang['Gzip_compression'] ='Gzip compression';
$lang['Not_available'] = 'Not available';
$lang['Board_Up_Days'] = 'Board Up Days';
$lang['Latest_Reg_User'] = 'Latest User Registered';
$lang['Latest_Reg_User_Date'] = 'Latest User Registered Date';
$lang['Most_Ever_Online'] = 'Most Users Ever Online';
$lang['Most_Ever_Online_Date'] = 'Most Users Ever Online Date';
$lang['Disk_usage'] = 'Disk Usage';
$lang['Title'] = 'Average Posts per User';
$lang['Average_Posts'] = 'Average Posts per User:';

$lang['module_name_average_posts_per_user'] = 'Average posts per user';

$lang['module_name_fastest_users'] = 'Fastest users';
$lang['time_on_forum'] = 'Days on forum';
$lang['posts_day'] = 'Messages per day';
$lang['Statistics'] = 'Statistics';

$lang['module_name_interesting_topics'] = 'Most interesting topics';
$lang['Rate'] = 'Rate (views/messages)';
$lang['Topic'] = $lang['Topic'];

$lang['module_name_latest_topics'] = 'Latest Topics';
$lang['Rank'] = 'Rank';
$lang['Latest_Topics'] = 'Latest Topics';
$lang['Post_time'] = 'Post time';

$lang['module_name_month_stat'] = 'Monthly Statistics';
$lang['New_users'] = 'New Users';
$lang['New_replies'] = 'New Replies';
$lang['New_topics'] = 'New Topics';
$lang['Avg_Table'] = 'Table Average';
$lang['Sum_Table'] = 'Table Sum';

$lang['module_name_most_active_topics'] = 'Most Active Topics';

$lang['module_name_most_active_topicstarter'] = 'Users who created most of the topics';

$lang['module_name_most_logged_on_users'] = 'Most Logged On Users';
$lang['Time2'] = 'Logged On Time';

$lang['module_name_most_used_languages'] = 'Used Languages';
$lang['Language'] = 'Language';

$lang['module_name_most_used_styles'] = 'Used Styles';
$lang['Style'] = 'Style';

$lang['module_name_most_viewed_topics'] = 'Most Viewed Topics';
$lang['Month_jan'] = 'Jan';
$lang['Month_feb'] = 'Feb';
$lang['Month_mar'] = 'Mar';
$lang['Month_apr'] = 'Apr';
$lang['Month_may'] = 'May';
$lang['Month_jun'] = 'Jun';
$lang['Month_jul'] = 'Jul';
$lang['Month_aug'] = 'Aug';
$lang['Month_sep'] = 'Sep';
$lang['Month_oct'] = 'Oct';
$lang['Month_nov'] = 'Nov';
$lang['Month_dec'] = 'Dec';
$lang['Year'] = 'Year';
$lang['Month'] = 'Month';
$lang['Number'] = 'Number';

$lang['module_name_new_posts_by_month'] = 'Number of new posts by month';
$lang['Posts_month'] = 'Number of new posts by month';

$lang['module_name_new_topics_by_month'] = 'Number of new topics by month';
$lang['Topics_month'] = 'Number of new topics by month';

$lang['module_name_new_users_by_month'] = 'Number of new users by month';
$lang['Signup_month'] = 'Number of new users by month';

$lang['module_name_posting_by_day_of_week'] = 'Day-of-Week Traffic: Posting';
$lang['Traffic_posts'] = 'Day-of-Week Traffic: Posting';
$lang['Dow'] = 'Day';

$lang['module_name_registrations_by_day_of_week'] = 'Day-of-Week Traffic: Registrations';
$lang['Traffic_reg'] = 'Day-of-Week Traffic: Registrations';
$lang['New_users'] = 'New Users';

$lang['module_name_site_hist_month_top_posters'] = 'Top posting users this Month';
$lang['Month_Var'] = '[%s]'; // %s will be replaced by the current Month

$lang['module_name_site_hist_week_top_posters'] = 'Top posting users this week';
$lang['Week_Var'] = '[%s]'; // %s will be replaced by the current Week

$lang['module_name_top_attachments'] = 'Top Downloaded Attachments';
$lang['File_name'] = 'Filename';
$lang['File_comment'] = 'File Comment';

$lang['module_name_top_posters'] = 'Top Posters';

$lang['module_name_top_smilies'] = 'Top Used Smileys';
$lang['smiley_url'] = 'Emoticon Image';
$lang['smiley_code'] = 'Emoticon Code';

$lang['module_name_top_words'] = 'Most used words';
$lang['Word'] = 'Word';
$lang['Uses2'] = 'Uses';

$lang['module_name_topics_by_day_of_week'] = 'New topics by day of the week ';
$lang['Traffic_topics'] = 'Day-of-Week Traffic: Topics';
$lang['Dow'] = 'Day';

$lang['module_name_users_from_where'] = 'Where our members are from';
$lang['From_where_title'] = 'Where are users from';
$lang['From_where'] = 'From where';
$lang['How_many'] = 'How many';

$lang['module_name_users_gender'] = 'Gender';
$lang['Users'] = 'Users';

$lang['module_name_users_ranks'] = 'Ranks';
$lang['Rank_image'] = 'Rank Image';
$lang['Rank_range'] = 'Rank messages';
$lang['Rank_special'] = 'Special rank';

?>