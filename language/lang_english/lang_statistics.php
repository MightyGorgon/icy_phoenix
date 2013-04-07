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

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Original Statistics Mod (c) 2002 Nivisec - http://nivisec.com/mods

$lang = array_merge($lang, array(
// If you want to credit the Author on the Statistics Page, uncomment the second line.
// 'Version_info' => '<br />Statistics Mod Version %s', //%s = number
	'Version_info' => '<br />Statistics recoded by <a href="http://www.icyphoenix.com/">Mighty Gorgon</a> based on Statistics Mod Version %s &copy; 2002 <a href="http://www.opentools.de/board">Acyd Burn</a>',

// These Language Variables are available for all installed Modules
	'Rank' => 'Rank',
	'Percent' => 'Percent',
	'Graph' => 'Graph',
	'Uses' => 'Uses',
	'How_many' => 'How Many',

// Main Language

// Page Header/Footer
	'Install_info' => 'Installed on %s', //%s = date
	'Viewed_info' => 'Statistics Page Loaded %d Times', //%d = number
	'Statistics_title' => 'Board Statistics',

// Admin Language
	'Statistics_management' => 'Statistics Modules',
	'Statistics_config' => 'Statistics Configuration',

// Statistics Config
	'Statistics_config_title' => 'Statistics Configuration',

	'Return_limit' => 'Return Limit',
	'Return_limit_desc' => '<b>The number of items to include in each ranking.</b><br /> This is auto-parsed to all modules by being specified here.',
	'Clear_cache' => 'Clear Module Cache',
	'Clear_cache_desc' => 'Check Box to Clear all the current cached data for all modules.',
	'Modules_directory' => 'Modules Directory',
	'Modules_directory_desc' => 'The directory relative to the home Icy Phoenix directory where modules are located.<br /><b>Note:</b> A trailing / or \ must not be used!',

// Status Messages
	'Messages' => 'Admin Messages',
	'Updated' => 'Updated',
	'Active' => 'Active',
	'Activate' => 'Activate',
	'Activated' => 'Activated',
	'Not_active' => 'Not Active',
	'Deactivate' => 'Deactivate',
	'Deactivated' => 'Deactivated',
	'Install' => 'Install',
	'Installed' => 'Installed',
	'Uninstall' => 'Uninstall',
	'Uninstalled' => 'Uninstalled',
	'Update_time' => 'Update Time',
	'Auth_settings_updated' => 'Authorization Settings - [These are always updated]',

// Modules Management
	'Back_to_management' => 'Back to the Modules Management Screen',
	'Statistics_modules_title' => 'Statistics Module Management',

	'Module_name' => 'Name',
	'Module_file_name' => 'Module Name',
	'Modules_order_update' => 'Modules Updated',
	'Directory_name' => 'Directory Name',
	'Status' => 'Status',
	'Update_time_minutes' => 'Update Time in Minutes',
	'Update_time_desc' => 'Time Interval (in Minutes) for refreshing the cached data with new Data.',
	'AUTO_SET_UPDATE_TIME' => '<b>Determine and set recommended Update Times for every Installed (and Active) Module.</b><br /><b>Be aware: This may take a long time.</b>',
	'STAT_BLOCKS_SORT' => '<i>Hint: You can adjust modules order using drag and drop.</i>',
	'Uninstall_module' => 'Uninstall Module',
	'Uninstall_module_desc' => 'Marks the module with "not installed" status, so that you may re-install it with the install command.  It does not delete the module from your file system, you will manually need to delete the module folder to remove it completely.',
	'Active_desc' => 'Option for if the Module is Active, so it is displayed depending on the set Permissions.',
	'Go' => 'Go',
	'Update_Modules' => 'Update Modules',

	'Not_allowed_to_install' => 'You are not able to install this Module. Mostly this is because you haven\'t installed a Mod needed in order to run this Module. Please contact the Author of this Module if you have questions and if the Extra Info printed here makes no sense to you.',
	'Wrong_stats_mod_version' => 'You are not able to install this Module, because your Statistics Mod Version does not match the Version required by the Module. In order to install and run the Module, you need at least Version %s of the Statistics Mod.', // replace %s with Version (2.1.3 for example)
	'Module_install_error' => 'There was an error while installing this module. More than likely some SQL commands could not be executed, check for failure messages above.',

	'Preview_debug_info' => 'This Module was generated in %f seconds: %d queries were executed.', // Replace %f with seconds and %d with queries
	'Update_time_recommend' => 'The Statistics Mod recommends (depending on the debug info) an update time of <b>%d</b> Minutes.', // Replace %d with Minutes

// Modules

	'module_name__stats_overview_section' => 'Statistics Overview',

	'module_name_admin_statistics' => 'Administrative Statistics',
	'Statistic' => 'Statistic',
	'Value' => 'Value',
	'Number_posts' => 'Number of posts',
	'Posts_per_day' => 'Posts per day',
	'Number_topics' => 'Number of topics',
	'Topics_per_day' => 'Topics per day',
	'Number_users' => 'Number of users',
	'Users_per_day' => 'Users per day',
	'Board_started' => 'Board started',
	'Avatar_dir_size' => 'Avatar directory size',
	'Database_size' => 'Database size',
	'Gzip_compression' => 'Gzip compression',
	'Not_available' => 'Not available',
	'Board_Up_Days' => 'Board Up Days',
	'Latest_Reg_User' => 'Latest User Registered',
	'Latest_Reg_User_Date' => 'Latest User Registered Date',
	'Most_Ever_Online' => 'Most Users Ever Online',
	'Most_Ever_Online_Date' => 'Most Users Ever Online Date',
	'Disk_usage' => 'Disk Usage',
	'Title' => 'Average Posts per User',
	'Average_Posts' => 'Average Posts per User:',

	'module_name_average_posts_per_user' => 'Average Posts Per User',

	'module_name_age_clusters' => 'Age Clusters',
	'AGE' => 'Age',
	'LESS_THAN' => 'Less than',
	'MORE_THAN' => 'More than',

	'module_name_fastest_users' => 'Fastest Users',
	'time_on_forum' => 'Days on forum',
	'posts_day' => 'Messages per day',
	'Statistics' => 'Statistics',

	'module_name_interesting_topics' => 'Most Interesting Topics',
	'Rate' => 'Rate (views/messages)',
	'Topic' => 'Topic',

	'module_name_latest_topics' => 'Latest Topics',
	'Rank' => 'Rank',
	'Latest_Topics' => 'Latest Topics',
	'Post_time' => 'Post time',

	'module_name_month_stat' => 'Monthly Statistics',
	'New_users' => 'New Users',
	'New_replies' => 'New Replies',
	'New_topics' => 'New Topics',
	'Avg_Table' => 'Table Average',
	'Sum_Table' => 'Table Sum',

	'module_name_most_active_topics' => 'Most Active Topics',

	'module_name_most_active_topicstarter' => 'Most Active Topics Starter',

	'module_name_most_logged_on_users' => 'Most Logged On Users',
	'Time2' => 'Logged On Time',

	'module_name_most_used_languages' => 'Languages',
	'Language' => 'Language',

	'module_name_most_used_styles' => 'Styles',
	'Style' => 'Style',

	'module_name_most_viewed_topics' => 'Most Viewed Topics',
	'Month_jan' => 'Jan',
	'Month_feb' => 'Feb',
	'Month_mar' => 'Mar',
	'Month_apr' => 'Apr',
	'Month_may' => 'May',
	'Month_jun' => 'Jun',
	'Month_jul' => 'Jul',
	'Month_aug' => 'Aug',
	'Month_sep' => 'Sep',
	'Month_oct' => 'Oct',
	'Month_nov' => 'Nov',
	'Month_dec' => 'Dec',
	'Year' => 'Year',
	'Month' => 'Month',
	'Number' => 'Number',

	'module_name_new_posts_by_month' => 'New Posts By Month',
	'Posts_month' => 'Number of new posts by month',

	'module_name_new_topics_by_month' => 'New Topics By Month',
	'Topics_month' => 'Number of new topics by month',

	'module_name_new_users_by_month' => 'New Users By Month',
	'Signup_month' => 'Number of new users by month',

	'module_name_posting_by_day_of_week' => 'Day-of-Week Traffic: Posts',
	'Traffic_posts' => 'Day-of-Week Traffic: Posting',
	'Dow' => 'Day',

	'module_name_registrations_by_day_of_week' => 'Day-of-Week Traffic: Registrations',
	'Traffic_reg' => 'Day-of-Week Traffic: Registrations',
	'New_users' => 'New Users',

	'module_name_site_hist_daily_stats_current_week' => 'Current Week Daily Statistics',

	'module_name_site_hist_hours_stats_current_day' => 'Current Day Statistics',

	'module_name_site_hist_month_top_posters' => 'Current Month Top Posting Users',
	'Month_Var' => '[%s]', // %s will be replaced by the current Month

	'module_name_site_hist_monthly_stats_current_year' => 'Current Year Monthly Statistics',

	'module_name_site_hist_week_top_posters' => 'Current Week Top Posting Users',
	'Week_Var' => '[%s]', // %s will be replaced by the current Week

	'module_name_top_attachments' => 'Top Downloaded Attachments',
	'File_name' => 'Filename',
	'File_comment' => 'File Comment',

	'module_name_top_posters' => 'Top Posters',

	'module_name_top_smilies' => 'Most Used Smileys',
	'smiley_url' => 'Image',
	'smiley_code' => 'Code',

	'module_name_top_words' => 'Most Used Words',
	'Word' => 'Word',
	'Uses2' => 'Uses',

	'module_name_topics_by_day_of_week' => 'Day-of-Week Traffic: Topics',
	'Traffic_topics' => 'Day-of-Week Traffic: Topics',
	'Dow' => 'Day',

	'module_name_users_from_where' => 'Users Location',
	'From_where_title' => 'Where are users from',
	'From_where' => 'From where',

	'module_name_users_gender' => 'Gender',
	'Users' => 'Users',

	'module_name_users_ranks' => 'Ranks',
	'Rank_image' => 'Rank Image',
	'Rank_range' => 'Rank messages',
	'Rank_special' => 'Special rank',
	)
);

?>