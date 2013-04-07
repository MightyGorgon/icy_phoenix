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
* Philipp Kordowich
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

// Functions available
// Usage: $mtnc[] = array(internal Name, Name of Function, Description of Function, Warning Message (leave empty to avoid), Number of Check function (Integer))
// Use $mtnc[] = array('--', '', '', '', 0) for a space row (you can us a different check function)
$mtnc[] = array('statistic',
	'Statistics',
	'Shows information about the board and the database.',
	'',
	0);
$mtnc[] = array('config',
	'Configuration',
	'Allows the configuration of DB Maintenance.',
	'',
	5);
$mtnc[] = array('--', '', '', '', 0);
$mtnc[] = array('check_user',
	'Check user and group tables',
	'This will check the user and the group tables for errors and will restore missing single user groups.',
	'You will lose all groups without any member by this action. Proceed?',
	0);
$mtnc[] = array('check_post',
	'Check post and topic tables',
	'This will check the post and the topic tables for errors.',
	'You will lose all posts without any text. Proceed?',
	0);
$mtnc[] = array('check_vote',
	'Check vote tables',
	'This will check the vote tables for errors.',
	'You will lose all vote data without a corresponding vote. Proceed?',
	0);
$mtnc[] = array('check_pm',
	'Check private message tables',
	'This will check the private messages tables for errors.',
	'Unread messages will be deleted when either the sender or the recipient does not exist. Proceed?',
	0);
$mtnc[] = array('check_config',
	'Check configuration table',
	'This will check the configuration table for missing entries.',
	'',
	0);
$mtnc[] = array('--', '', '', '', 0);
$mtnc[] = array('check_search_wordmatch',
	'Check search word match table',
	'This will check the word match table for errors. This table is used for the search function.',
	'',
	0);
$mtnc[] = array('check_search_wordlist',
	'Check search word list table',
	'This will remove all unnecessary words in the word list used for search.',
	'This function may take some time to execute. It is not necessary to perform this check but doing so may reduce database size a bit. Proceed?',
	0);
$mtnc[] = array('--', '', '', '', 0);
$mtnc[] = array('synchronize_post',
	'Synchronize forums and topics',
	'This will synchronize the post counters and the post data in the forums and topics.',
	'This command will take some amount of time to complete. If your server does not allow the usage of the set_time_limit() command, this command may be interruped by PHP. No data will get lost by this but some data may not be updated. Proceed?',
	0);
$mtnc[] = array('synchronize_user',
	'Synchronize user post counters',
	'This will synchronize the post counters for the users.',
	'<b>Attention:</b> pruned posts are normally not subtracted from the post counter. When running this command, the pruned posts will be subtracted from the counter and cannot be restored. Proceed?',
	6);
$mtnc[] = array('synchronize_mod_state',
	'Synchronize moderator status',
	'This will resync the moderator status in the user table.',
	'',
	0);
$mtnc[] = array('--', '', '', '', 0);
$mtnc[] = array('reset_date',
	'Reset last post date',
	'This will reset the last post data if it is in the future. This will solve issues where users get a message that they are not allowed to make another post so soon after the last one.',
	'Any time of a post in the future will be set to the current time. Proceed?',
	0);
$mtnc[] = array('reset_sessions',
	'Reset all sessions',
	'This will reset all current sessions by emptying the session table.',
	'All currently active users will lose their session and their search results. Proceed?',
	0);
$mtnc[] = array('--', '', '', '', 8);
$mtnc[] = array('sync_topics_subjects',
	'Synchronize topics subjects',
	'This function will synchronize topic subjects in first post of each topic. You shouldn\'t have to run this function under normal conditions.',
	'This will synchronize topic subjects in first post of each topic. The site will not be accessible during this time. Proceed?',
	0);
$mtnc[] = array('synchronize_notify_forum_id',
	'Synchronize Topic Notifications forum IDs',
	'This function will update forum IDs on topic notifications table. You shouldn\'t have to run this function under normal conditions.',
	'',
	0);
$mtnc[] = array('rebuild_search_index',
	'Rebuild search index',
	'This function will rebuild the index used for searching. You shouldn\'t have to run this function under normal conditions.',
	'This will delete the complete search index and rebuild it. It can take up to several hours to complete this task. The site will not be accessible during this time. Proceed?',
	7);
$mtnc[] = array('proceed_rebuilding',
	'Restart rebuilding',
	'Use this function if re-creation of the search index was interrupted.',
	'',
	4);
$mtnc[] = array('--', '', '', '', 1);
$mtnc[] = array('check_db',
	'Check database',
	'Checks the database for errors.',
	'',
	1);
$mtnc[] = array('optimize_db',
	'Optimize database',
	'Optimizes the tables. This will reduce the database size after deleting lots of records and so on.',
	'',
	1);
$mtnc[] = array('repair_db',
	'Repair database',
	'Fixes the database when an error is found.',
	'You only should perform this action if an error is reported when checking the database. Proceed?',
	1);
$mtnc[] = array('--', '', '', '', 0);
$mtnc[] = array('reset_auto_increment',
	'Reset auto increment values',
	'This function resets the auto increment values. This should only be performed if there seems to be a problem when inserting new data in the tables.',
	'Do you really want to reset the auto increment values? No data will get lost but this function only should be used if necessary.',
	0);
$mtnc[] = array('heap_convert',
	'Convert Session-Table',
	'This function converts the session-table to HEAP table type. This normaly will be done during installation and speed up Icy Phoenix a bit. You should use this function if your session-table is not of the HEAP table type.',
	'Do you really want to convert the table?',
	2);
$mtnc[] = array('--', '', '', '', 3);
$mtnc[] = array('unlock_db',
	'Unlock the board',
	'Use this function if you got an error during an operation done prevoiusly and the board is still locked.',
	'',
	3);

$lang = array_merge($lang, array(
	'DB_Maintenance' => 'Database Tools',
	'DB_Maintenance_Description' => 'Check your database for inconsistencies and errors.<br /><b>Attention:</b> Some operations will take a longer time to perform. The site will be <b>locked</b> during these operations.<br /><br /><b>It is always recommended to backup your database before using any of the functions listed below!</b>',
	'Function' => 'Function',
	'Function_Description' => 'Description',

	'Incomplete_configuration' => 'A setting for <b>%s</b> was not found in the board configuration. DB Maintenance cannot run without this setting.<br />Maybe you forgot to execute the SQL statements as described in the installation instructions.',
	'dbtype_not_supported' => 'Sorry, this function does not support your database',
	'no_function_specified' => 'No function was specified',
	'function_unknown' => 'The specified function is unknown',
	'Old_MySQL_Version' => 'Sorry, your MySQL-Version does not support this function. Please use version 3.23.17 or newer.',

	'Back_to_DB_Maintenance' => 'Back to Database Maintenance',
	'Processing_time' => 'DB Maintenance took %f seconds for the operations',

	'Lock_db' => 'Disabling board',
	'Unlock_db' => 'Enabling board',
	'Already_locked' => 'Site was already locked',
	'Ignore_unlock_command' => 'Site was locked when starting this command. Site will not be unlocked',
	'Delay_info' => 'Delaying three seconds to allow database actions to finish...',

	'Affected_row' => 'One affected dataset',
	'Affected_rows' => '%d affected datasets',
	'Done' => 'Done',
// The following variable is used when nothing had to be fixed in the database. It needs the complete paragraph-tag.
// If you do not want a message to be displayed in these cases, just leave the variable empty.
	'Nothing_to_do' => '<p class="gen"><i>Nothing to do :-)</i></p><br />' . "\n",

// Names for new records in several tables
	'New_cat_name' => 'Restored forums',
	'New_forum_name' => 'Restored topics',
	'New_topic_name' => 'Restored posts',
	'Restored_topic_name' => 'Restored topic',
	'New_poster_name' => 'Restored post', // Name for Poster of a restored post

// Function specific vars
// statistic
	'Statistic_title' => 'Site and database statistics',
	'Database_table_info' => 'Database statistics will deliver three different values: these for all tables of the database, these of all tables deliverd by Icy Phoenix by default (core tables) and these starting with the prefix of the board tables (advanced tables).',
	'Board_statistic' => 'Site statistics',
	'Database_statistic' => 'Database statistics',
	'Version_info' => 'Version information',
	'Thereof_deactivated_users' => 'thereof deactivated',
	'Thereof_Moderators' => 'thereof moderators',
	'Thereof_Administrators' => 'thereof administrators',
	'Users_with_Admin_Privileges' => 'Users with administrator privileges',
	'Number_tables' => 'Number of tables',
	'Number_records' => 'Number of records',
	'DB_size' => 'Size of the database',
	'Thereof_phpbb_core' => 'thereof Icy Phoenix core tables',
	'Thereof_phpbb_advanced' => 'thereof advanced Icy Phoenix tables',
	'Version_of_board' => 'Version of Icy Phoenix',
	'Version_of_mod' => 'Version of DB Maintenance',
	'Version_of_PHP' => 'Version of PHP',
	'Version_of_MySQL' => 'Version of MySQL',
// config
	'Config_title' => 'DB Maintenance configuration',
	'Config_info' => 'The following options allow you to configure the behaviour of DB Maintenance. Please keep in mind that any misconfiguration may lead to unexpected results.',
	'General_Config' => 'General configuration',
	'Rebuild_Config' => 'Configuration of rebuilding of search index',
	'Current_Rebuild_Config' => 'Configuration of current rebuilding',
	'Rebuild_Settings_Explain' => 'These settings adjust the behaviour of DB Maintenance when rebuilding the search index.',
	'Current_Rebuild_Settings_Explain' => 'These settings are used by DB Maintenance to store the position of the current rebuild. There is no need to change these settings under normal conditions.',
	'Disallow_postcounter' => 'Disallow synchronization of user post counters',
	'Disallow_postcounter_Explain' => 'This will disable the function to synchronize the user post counters. You can disallow this function if you do not want that pruned posts get subtracted from the post counters of the users.',
	'Disallow_rebuild' => 'Disallow rebuilding of the search index',
	'Disallow_rebuild_Explain' => 'This will disable the rebuilding of the search index. An interrupted rebuild can be continued however.',
	'Rebuildcfg_Timelimit' => 'Maximum execution time for rebuilding (in seconds)',
	'Rebuildcfg_Timelimit_Explain' => 'Maximum time used for one step when rebuilding (default: 240). This value limits the execution time even if a longer time would be possible.',
	'Rebuildcfg_Timeoverwrite' => 'Fixed amount of time available for execution (in seconds)',
	'Rebuildcfg_Timeoverwrite_Explain' => 'Fixed estimated time available for execution (default: 0). With 0 the result of the calculation is used as execution time, any other value overwrites the calculated value.',
	'Rebuildcfg_Maxmemory' => 'Maximum post size for rebuilding (in kByte)',
	'Rebuildcfg_Maxmemory_Explain' => 'Maximum size of posts indexed in one step (default: 500). When the sum of the post sizes gets over this value, no further post is indexed in the current step.',
	'Rebuildcfg_Minposts' => 'Minimum posts to index per step',
	'Rebuildcfg_Minposts_Explain' => 'Minimum number of posts indexed per step (default: 3). Defines the number of posts that are at least indexed per step.',
	'Rebuildcfg_PHP3Only' => 'Use only standard PHP 3 compatible method for indexing',
	'Rebuildcfg_PHP3Only_Explain' => 'DB Maintenance uses an advanced method for indexing when PHP 4.0.5 or newer is available. You can switch off the advanced method so that DB Maintenance will make use of the standard method of the board.',
	'Rebuildcfg_PHP4PPS' => 'Posts indexed per second when using advanced indexing method',
	'Rebuildcfg_PHP4PPS_Explain' => 'Estimated value of posts that can be indexed per second when using the advanced indexing method (default: 8).',
	'Rebuildcfg_PHP3PPS' => 'Posts indexed per second when using standard indexing method',
	'Rebuildcfg_PHP3PPS_Explain' => 'Estimated value of posts that can be indexed per second when using the standard indexing method (default: 1).',
	'Rebuild_Pos' => 'Last post indexed',
	'Rebuild_Pos_Explain' => 'ID of the last successful indexed post. Is -1 when rebuilding has finished.',
	'Rebuild_End' => 'Last post to index',
	'Rebuild_End_Explain' => 'ID of the last post to index. Is 0 when rebuilding has finished.',
	'Dbmtnc_config_updated' => 'Configuration Updated Successfully',
	'Click_return_dbmtnc_config' => 'Click %sHere%s to return to configuration',
// check_user
	'Checking_user_tables' => 'Checking user and group tables',
	'Checking_missing_anonymous' => 'Checking for missing anonymous account',
	'Anonymous_recreated' => 'Anonymous account recreated',
	'Checking_incorrect_pending_information' => 'Checking for incorrect pending information',
	'Updating_invalid_pendig_user' => 'Updated invalid pending information of one user',
	'Updating_invalid_pendig_users' => 'Updated invalid pending information of %d users',
	'Updating_pending_information' => 'Updating pending information of single user groups',
	'Checking_missing_user_groups' => 'Checking for users with multiple or no single user group',
	'Found_multiple_SUG' => 'Found users with multiple single user groups',
	'Resolving_user_id' => 'Resolving users to group',
	'Removing_groups' => 'Removing groups',
	'Removing_user_groups' => 'Removing user to group connection',
	'Recreating_SUG' => 'Re-creating single user groups for user',
	'Checking_for_invalid_moderators' => 'Checking for invalid group moderator settings',
	'Updating_Moderator' => 'Setting current user as moderator for group',
	'Checking_moderator_membership' => 'Checking group membership of moderators',
	'Updating_mod_membership' => 'Updating membership of group moderators',
	'Moderator_added' => 'Moderator added to group',
	'Moderator_changed_pending' => 'Changed pending state of moderator',
	'Remove_invalid_user_data' => 'Removing invalid user data in user-group-table',
	'Remove_empty_groups' => 'Removing empty groups',
	'Remove_invalid_group_data' => 'Removing invalid group data in user-group-table',
	'Checking_ranks' => 'Checking for invalid ranks',
	'Invalid_ranks_found' => 'Found users with invalid ranks',
	'Removing_invalid_ranks' => 'Removing invalid ranks',
	'Checking_themes' => 'Checking for invalid themes settings',
	'Updating_users_without_style' => 'Updating users with no theme set',
	'Default_theme_invalid' => '<b>Attention:</b> The default style is invalid. Please check your configuration.',
	'Updating_themes' => 'Updating invalid themes to theme %d',
	'Checking_theme_names' => 'Checking for invalid theme name data',
	'Removing_invalid_theme_names' => 'Removing invalid theme name data',
	'Checking_languages' => 'Checking for invalid language settings',
	'Invalid_languages_found' => 'Found users with invalid language settings',
	'Default_language_invalid' => '<b>Attention:</b> The default language is invalid. Please check your configuration.',
	'English_language_invalid' => '<b>Attention:</b> The default language is invalid and the English language-files do not exist. You have to restore the <b>lang_english</b>-directory.',
	'Changing_language' => 'Changing language \'%s\' to \'%s\'',
	'Remove_invalid_ban_data' => 'Removing invalid ban data',
	'Remove_invalid_session_keys' => 'Removing invalid session keys',
// check_post
	'Checking_post_tables' => 'Checking post and topic tables',
	'Checking_invalid_forums' => 'Checking for forums with invalid category',
	'Invalid_forums_found' => 'Found forums with invalid category',
	'Setting_category' => 'Moving forums to category \'%s\'',
	'Checking_posts_wo_text' => 'Checking for posts without a text',
	'Posts_wo_text_found' => 'Found posts without text',
	'Deleting_post_wo_text' => '%d (Topic: %s (%d); User: %s (%d))',
	'Deleting_Posts' => 'Deleting post data',
	'Checking_topics_wo_post' => 'Checking for topics without a post',
	'Topics_wo_post_found' => 'Found topics without a post',
	'Deleting_topics' => 'Deleting topic data',
	'Checking_invalid_topics' => 'Checking for topics with invalid forum',
	'Invalid_topics_found' => 'Found topics with invalid forum',
	'Setting_forum' => 'Moving topics to forum \'%s\'',
	'Checking_invalid_posts' => 'Checking for posts with invalid topic',
	'Invalid_posts_found' => 'Found posts with invalid topic',
	'Setting_topic' => 'Moving posts %s to topic \'%s\' (%d) in forum \'%s\'',
	'Checking_invalid_forums_posts' => 'Checking for posts with invalid forum',
	'Invalid_forum_posts_found' => 'Found posts with invalid forum',
	'Setting_post_forum' => '%d: Moving from forum \'%s\' (%d) to \'%s\' (%d)',
	'Checking_texts_wo_post' => 'Checking for post text without a post',
	'Invalid_texts_found' => 'Found text without a post',
	'Recreating_post' => 'Recreating post %d and move it to topic \'%s\' in forum \'%s\'<br />Extract: %s',
	'Checking_invalid_topic_posters' => 'Checking topics for invalid posters',
	'Invalid_topic_poster_found' => 'Found topics with invalid poster',
	'Updating_topic' => 'Updating topic %d (Poster: %d -&gt; %d)',
	'Checking_invalid_posters' => 'Checking posts for invalid posters',
	'Invalid_poster_found' => 'Found posts with invalid poster',
	'Updating_posts' => 'Updating posts',
	'Checking_moved_topics' => 'Checking moved topics',
	'Deleting_invalid_moved_topics' => 'Deleting invalid moved topics',
	'Updating_invalid_moved_topic' => 'Updating invalid moved information for one unmoved topics',
	'Updating_invalid_moved_topics' => 'Updating invalid moved information for %d unmoved topics',
	'Checking_prune_settings' => 'Checking for invalid prune data',
	'Removing_invalid_prune_settings' => 'Removing invalid prune settings',
	'Updating_invalid_prune_setting' => 'Updating invalid prune settings of one forum',
	'Updating_invalid_prune_settings' => 'Updating invalid prune settings of %d forums',
	'Checking_topic_watch_data' => 'Checking for invalid watched topics',
	'Checking_auth_access_data' => 'Checking for invalid group authorization data',
	'Must_synchronize' => 'You have to synchronize the post data before using the board. Click to proceed.',
// rebuild last poster details
	'Rebuild_Last_Poster_Details' => 'Rebuilding last poster details',
// check_vote
	'Checking_vote_tables' => 'Check vote tables',
	'Checking_votes_wo_topic' => 'Checking for votes without corresponding topic',
	'Votes_wo_topic_found' => 'Found votes without topic',
	'Invalid_vote' => '%s (%d) - Start date: %s - End date: %s',
	'Deleting_votes' => 'Deleting votes',
	'Checking_votes_wo_result' => 'Checking for votes without any result',
	'Votes_wo_result_found' => 'Found votes without result',
	'Checking_topics_vote_data' => 'Checking vote data in topic tables',
	'Updating_topics_wo_vote' => 'Updating topics marked as vote without a corresponding vote',
	'Updating_topics_w_vote' => 'Updating topics not marked as vote but with a corresponding vote',
	'Checking_results_wo_vote' => 'Checking for results without corresponding vote',
	'Results_wo_vote_found' => 'Found results without vote',
	'Invalid_result' => 'Deleting result: %s (Votes: %d)',
	'Checking_voters_data' => 'Checking for invalid voting data',
// check_pm
	'Checking_pm_tables' => 'Checking private messages tables',
	'Checking_pms_wo_text' => 'Checking for private messages without a text',
	'Pms_wo_text_found' => 'Found private messages without text',
	'Deleting_pn_wo_text' => '%d (Subject: %s; Sender: %s (%d); Recipient: %s (%d))',
	'Deleting_Pms' => 'Deleting private message data',
	'Checking_texts_wo_pm' => 'Checking for private messages text without a message',
	'Deleting_pm_texts' => 'Deleting invalid private messages text',
	'Checking_invalid_pm_senders' => 'Checking private messages for invalid senders',
	'Invalid_pm_senders_found' => 'Found private messages with invalid sender',
	'Updating_pms' => 'Updating private messages',
	'Checking_invalid_pm_recipients' => 'Checking private messages for invalid recipients',
	'Invalid_pm_recipients_found' => 'Found private messages with invalid recipient',
	'Checking_pm_deleted_users' => 'Checking private messages for deleted senders or recipients',
	'Invalid_pm_users_found' => 'Found private messages with deleted senders or recipients',
	'Deleting_pms' => 'Deleting private messages',
	'Synchronize_new_pm_data' => 'Synchronizing new private messages counter',
	'Synchronizing_users' => 'Updating users',
	'Synchronizing_user' => 'Updating user %s (%d)',
	'Synchronize_unread_pm_data' => 'Synchronizing unread private messages counter',
// check_config
	'Checking_config_table' => 'Checking configuration table',
	'Checking_config_entries' => 'Checking entries of configuration table',
	'Restoring_config' => 'Restoring entries',
// check_search_wordmatch
	'Checking_search_wordmatch_tables' => 'Checking word match table',
	'Checking_search_data' => 'Checking for invalid search data',
// check_search_wordlist
	'Checking_search_wordlist_tables' => 'Checking word match table',
	'Checking_search_words' => 'Checking for unnecessary search words',
	'Removing_part_invalid_words' => 'Removing part of unnecessary search words',
	'Removing_invalid_words' => 'Removing unnecessary search words',
// synchronize topics subjects
	'Sync_topics_subjects' => 'Synchronize topics subjects',
	'Sync_topics_subjects_progress' => 'Synchronization in progress',
// rebuild_search_index
	'Rebuilding_search_index' => 'Rebuilding search index',
	'Deleting_search_tables' => 'Emptying search tables',
	'Reset_search_autoincrement' => 'Resetting counter of search tables',
	'Preparing_config_data' => 'Setting configuration data',
	'Can_start_rebuilding' => 'You can now start with rebuilding the search index',
	'Click_once_warning' => '<b>Only click link once!</b> - it can take up to several minutes until a new page is displayed.',
// proceed_rebuilding
	'Preparing_to_proceed' => 'Preparing tables to allow proceeding',
	'Preparing_search_tables' => 'Preparing search tables for proceeding',
// perform_rebuild
	'Click_or_wait_to_proceed' => 'Click here to proceed or wait a few seconds',
	'Indexing_progress' => '%d of %d posts (%01.1f%%) have been indexed. Last post indexed: %d',
	'Indexing_finished' => 'Rebuilding the index was finished successfully',
// synchronize_notify_forum_id
	'Synchronizing_notify_forum_ids' => 'Synchronizing topics notifications forum IDs',
// synchronize_post
	'Synchronize_posts' => 'Synchronizing post data',
	'Synchronize_topic_data' => 'Synchronizing topics',
	'Synchronizing_topics' => 'Updating topics',
	'Synchronizing_topic' => 'Updating topic %d (%s)',
	'Synchronize_moved_topic_data' => 'Synchronizing moved topics',
	'Inconsistencies_found' => 'Inconsistencies in your database were found. Please %scheck the post and topic tables%s',
	'Synchronizing_moved_topics' => 'Updating moved topics',
	'Synchronizing_moved_topic' => 'Updating moved topic %d -&gt; %d (%s)',
	'Synchronize_forum_topic_data' => 'Synchronizing topic-data of forums',
	'Synchronizing_forums' => 'Updating forums',
	'Synchronizing_forum' => 'Updating forum %d (%s)',
	'Synchronize_forum_data_wo_topic' => 'Synchronizing forums without any topic',
	'Synchronize_forum_post_data' => 'Synchronizing post-data of forums',
	'Synchronize_forum_data_wo_post' => 'Synchronizing forums without any post',
// synchronize_user
	'Synchronize_post_counters' => 'Synchronizing post counters',
	'Synchronize_user_post_counter' => 'Synchronizing post counter of users',
	'Synchronizing_user_counter' => 'Updating user %s (%d): %d -&gt; %d',
// synchronize_mod_state
	'Synchronize_moderators' => 'Synchronizing moderator status in user table',
	'Getting_moderators' => 'Getting moderators',
	'Checking_non_moderators' => 'Checking for users with moderator status who do not moderate any forum',
	'Updating_mod_state' => 'Updating moderator status of users',
	'Changing_moderator_status' => 'Changing moderator status of user %s (%d)',
	'Checking_moderators' => 'Checking for users without moderator status who do moderate a forum',
// reset_date
	'Resetting_future_post_dates' => 'Resetting last post dates in the future',
	'Checking_post_dates' => 'Checking dates of posts',
	'Checking_pm_dates' => 'Checking dates of private messages',
	'Checking_email_dates' => 'Checking dates of last e-mail',
// reset_sessions
	'Resetting_sessions' => 'Resetting sessions',
	'Deleting_session_tables' => 'Emptying session and search result tables',
	'Restoring_session' => 'Restoring session of active user',
// check_db
	'Checking_db' => 'Checking database',
	'Checking_tables' => 'Checking tables',
	'Table_OK' => 'OK',
	'Table_HEAP_info' => 'Command not available for HEAP-tables',
// repair_db
	'Repairing_db' => 'Repairing database',
	'Repairing_tables' => 'Repairing tables',
// optimize_db
	'Optimizing_db' => 'Optimizing database',
	'Optimizing_tables' => 'Optimizing tables',
	'Optimization_statistic' => 'Optimization reduced size of tables from %s to %s. That is a reduction of %s or %01.2f%%.',
// reset_auto_increment
	'Reset_ai' => 'Resetting auto increment values',
	'Ai_message_update_table' => 'table updated',
	'Ai_message_no_update' => 'no update necessary',
	'Ai_message_update_table_old_mysql' => 'table updated', // Used if an old version of MySQL is used which does not allow a table check before updating the table
// heap_convert
	'Converting_heap' => 'Converting Session-Table to HEAP',
// unlock_db
	'Unlocking_db' => 'Unlocking database',

// Emergency Recovery Console
	'Forum_Home' => 'Forum Home',
	'ERC' => 'Emergency Recovery Console',
	'Submit_text' => 'Send',
	'Select_Language' => 'Select a language',
	'No_selectable_language' => 'No selectable language exist',
	'Select_Option' => 'Select an option',
	'Option_Help' => 'Hints for the options',
	'Authenticate_methods' => 'There are two ways to authenticate',
	'Authenticate_methods_help_text' => 'You have to authenticate to do any changes on the board configuration. There are two ways to do so: First, you can authenticate by entering name and password of any active administrator account of the board (preferred method); Second, you can authenticate by entering the name and password of the database account the board uses for accessing the database.',
	'Authenticate_user_only' => 'You have to authenticate with an active administrator account',
	'Authenticate_user_only_help_text' => 'You have to authenticate to do any changes on the board configuration. You can only authenticate by
	entering name and password of any active administrator account of the board.',
	'Admin_Account' => 'Admin account of board',
	'Database_Login' => 'Database user',
	'Username' => 'Username',
	'Password' => 'Password',
	'Auth_failed' => 'Authentication failed!',
	'Return_ERC' => 'Return to Emergency Recovery Console',
	'cur_setting' => 'Current setting',
	'rec_setting' => 'Recommended setting',
	'secure' => 'Secure',
	'secure_yes' => 'yes (https)',
	'secure_no' => 'no (http)',
	'domain' => 'Domain',
	'port' => 'Port',
	'path' => 'Path',
	'Cookie_domain' => 'Cookie domain',
	'Cookie_name' => 'Cookie name',
	'Cookie_path' => 'Cookie path',
	'select_language' => 'Select new language',
	'select_theme' => 'Select new theme',
	'reset_thmeme' => 'Recreate default theme',
	'new_admin_user' => 'User to grant admin privileges',
	'dbms' => 'Database Type',
	'DB_Host' => 'Database Server Hostname / DSN',
	'DB_Name' => 'Your Database Name',
	'DB_Username' => 'Database Username',
	'DB_Password' => 'Database Password',
	'Table_Prefix' => 'Prefix for tables in database',
	'New_config_php' => 'This is your new config.' . PHP_EXT,
// Options
	'cls' => 'Clear all sessions',
	'ecf' => 'Clear cache',
	'rdb' => 'Repair database tables',
	'cct' => 'Check config table',
	'rpd' => 'Reset path data',
	'rcd' => 'Reset cookie data',
	'rld' => 'Reset language data',
	'rtd' => 'Reset template data',
	'dgc' => 'Disable GZip compression',
	'cbl' => 'Clear ban list',
	'raa' => 'Remove all administrators',
	'mua' => 'Grant user admin privileges',
	'rcp' => 'Recreate config.php',
// Info for options
	'cls_info' => 'When proceeding all sessions will be cleared.',
	'ecf_info' => 'When proceeding cache will be cleared.',
	'rdb_info' => 'When proceeding the tables of the database will be repaired.',
	'cct_info' => 'When proceeding the config table will be checked and missing entries be restored.',
	'rpd_info' => 'When proceeding the config data will be updated if the recommended setting is selected.',
	'rcd_info' => 'When proceeding the cookie data will be updated. The Option whether to set a secure cookie or not can be found under \'Reset path data\'.',
	'rld_info' => 'When proceeding the selected language will be used for both the board and the user used to authenticate.',
	'rtd_info' => 'When proceeding either the selected style will be used for both the board and the user used to authenticate or the default theme (Icy Phoenix) will be recreated and used for board and user.',
	'rtd_info_no_theme' => 'When proceeding the default theme (Icy Phoenix) will be recreated and used for both the board and the user used to authenticate.',
	'dgc_info' => 'When proceeding the GZip compression will be disabled.',
	'cbl_info' => 'When proceeding both the ban list and the disallowed users will be cleared.',
	'raa_info' => 'When proceeding all admins will be set to normal users. If you use an admin account to authenticate, the account used for authentication will keep its admin level.',
	'mua_info' => 'When proceeding the selected user will be granted administrator privileges. The user will also be activated.',
	'rcp_info' => 'When proceeding a new config.php will be created with the data entered.',
// Success messages for options
	'cls_success' => 'All sessions were cleared successfully.',
	'ecf_success' => 'Cache cleared successfully.',
	'rdb_success' => 'The tables of the database were repaired.',
	'rpd_success' => 'Site configuration updated successfully.',
	'cct_success' => 'Config table checked successfully.',
	'rcd_success' => 'Cookie data updated successfully.',
	'rld_success' => 'The language data was updated successfully.',
	'rld_failed' => 'The required language files (lang_main.' . PHP_EXT . ' and lang_admin.' . PHP_EXT . ') do not exist.',
	'rtd_restore_success' => 'The default style was restored successfully.',
	'rtd_success' => 'The style data was updated successfully.',
	'dgc_success' => 'The GZip compression was disabled successfully.',
	'cbl_success' => 'The ban list and the disallowed users were cleared successfully.',
	'cbl_success_anonymous' => 'The ban list and the disallowed users were cleared successfully. The anonymous account has been recreated. Since group data of the anonymous account may be missing, it is recommended to use the function &quot;Check user and group tables&quot; in the main part of DB Maintenance.',
	'raa_success' => 'All admins were removed successfully.',
	'mua_success' => 'The selected user has now admin privileges.',
	'mua_failed' => '<b>Error:</b> The selected user does not exist or already has admin privileges.',
	'rcp_success' => "Copy the text to a text file, rename it to <b>config.php</b> and upload it to the root directory of the forum. Please ensure that there is no character (including spaces and line feeds) before the <b>&lt;?php</b> and after the <b>?&gt;</b>.<br />You can also %sdownload%s the file to your computer.",
// Text for success messages
	'Removing_admins' => 'Removing admins',
// Help Text
	'Option_Help_Text' => '<p>If you get a report that there was an error creating a session or so, you can clear the session data by selecting <b>Clear all sessions</b>. If you have Problems with accessing database tables, you can repair the tables by selecting <b>Repair database tables</b>. <b>Check config table</b> will check the config table for missing entries, which may be helpful for several types of errors.</p><p>If you are not able to log on or to enter the admin panel, there may be a fault in your path or your cookie settings. You can reset them with <b>Reset path data</b> or <b>Reset cookie data</b>. You can also reset the language setting with <b>Reset language data</b> or the template data with <b>Reset template data</b>.</p><p>If problems occur after activating the GZip compression, you can deactivate it by selecting <b>Disable GZip compression</b>.</p><p>If you lost the password of your account, you can grant a user admin privileges by selecting <b>Grant user admin privileges</b>. This will also activate the user so you can use a user just created before. If you are not able to add a new user, you can clear the ban list with <b>Clear ban list</b> (this will also restore the anonymous user).</p><p>If your board was hacked, it\'s recommended that you remove all admin accounts by selecting <b>Remove all administrators</b>. (The account itself will not be deleted but the rights will be removed.)</p><p>If you need to restore your config.php you can do so by selecting <b>Recreate config.php</b>.</p>',

	'dbmntc_Invalid_Option' => 'Invalid Option',
	)
);

?>