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

/**
*
* @Extra credits for this file
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

$lang = array_merge($lang, array(
	'General' => 'General Admin',
	'Users' => 'User Admin',
	'Groups' => 'Group Admin',
	'Forums' => 'Forum Admin',
	'Styles' => 'Styles Admin',

	'Configuration' => 'Settings',
	'Various_Configuration' => 'Various Settings',
	'Permissions' => 'Permissions',
	'Manage' => 'Management',
	'manage' => 'Management',
	'Disallow' => 'Disallow names',
	'Prune' => 'Pruning',
	'Mass_Email' => 'Mass Email',
	'Ranks' => 'Ranks',
	'Smilies' => 'Smileys',
	'Ban_Management' => 'Ban Control',
	'Word_Censor' => 'Word Censors',
	'Export' => 'Export',
	'Create_new' => 'Create',
	'Add_new' => 'Add',
	'Backup_DB' => 'Backup Database',
	'Restore_DB' => 'Restore Database',
	'DB_Maintenance' => 'Database Tools',
	'News_Admin' => 'News',
	'News_Cats' => 'News Categories',
	'News_Config' => 'News Configuration',
	'Security' => 'Security',
	'Member_Tries' => 'Member Tries',
	'Quick_Search' => 'Quick Search',
	'Special' => 'Special',
	'Styles_Management' => 'Styles Management',
	'Manage_Bots' => 'Bots Management',
	'Admin_Notepad' => 'Notepad',

// Index
	'Admin' => 'Administration',
	'Not_admin' => 'You are not authorized to administer this website',
	'Welcome_IP' => 'Welcome to Icy Phoenix',
	'Admin_intro' => 'Thank you for choosing Icy Phoenix as your CMS solution. This screen will give you a quick overview of all the various statistics of your site. You can get back to this page by clicking on the <u>Admin Index</u> link above. To return to the index of your forum, click on the <u>Forum</u> link (also above). The menu on the left hand side of this screen will allow you to control every aspect of your website. Each secondary option link will have instructions on how to use the tools.',
	'PayPalInfo' => 'Icy Phoenix is an open source project, you can show your appreciation and support future development by donating to the project.',
	'Forum_stats' => 'Site Statistics',
	'Admin_Index' => 'Admin Index',
	'Preview_forum' => 'Preview Forum',
	'Click_return_admin_index' => 'Click %sHere%s to return to the Admin Index',
	'Portal' => 'Home Page',
	'Preview_Portal' => 'Preview Home Page',
	'Main_index' => 'Forum',

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
	'NOT_AVAILABLE' => 'Not available',

	'ON' => 'ON', // This is for GZip compression
	'OFF' => 'OFF',

// DB Utils
	'Database_Utilities' => 'Database Utilities',

	'Restore' => 'Restore',
	'Backup' => 'Backup',
	'Restore_explain' => 'This will perform a full restore of all Icy Phoenix tables from a saved file. If your server supports it, you may upload a gzip-compressed text file and it will automatically be decompressed. <b>WARNING</b>: This will overwrite any existing data. The restore may take a long time to process, so please do not move from this page until it is complete.',
	'Backup_explain' => 'Back up all your site-related data. If you have any additional custom tables in the same database with Icy Phoenix that you would like to back up as well, please enter their names, separated by commas, in the Additional Tables textbox below. If your server supports it you may also gzip-compress the file to reduce its size before download.',

	'Backup_options' => 'Backup options',
	'Start_backup' => 'Start Backup',
	'Full_backup' => 'Full backup',
	'Structure_backup' => 'Structure-Only backup',
	'Data_backup' => 'Data only backup',
	'Additional_tables' => 'Additional tables',
	'phpBB_only' => 'Only Icy Phoenix related tables',
	'Gzip_compress' => 'Gzip compress file',
	'Select_file' => 'Select a file',
	'Start_Restore' => 'Start Restore',

	'Restore_success' => 'The Database has been successfully restored.<br /><br />Your board should be back to the state it was when the backup was made.',
	'Backup_download' => 'Your download will start shortly; please wait until it begins.',
	'Backups_not_supported' => 'Sorry, but database backups are not currently supported for your database system.',

	'Restore_Error_uploading' => 'Error in uploading the backup file',
	'Restore_Error_filename' => 'Filename problem; please try an alternative file',
	'Restore_Error_decompress' => 'Cannot decompress a gzip file; please upload a plain text version',
	'Restore_Error_no_file' => 'No file was uploaded',

// Auth pages
	'Select_a_User' => 'Select a User',
	'Select_a_Group' => 'Select a Group',
	'Select_a_Forum' => 'Select a Forum',
	'Auth_Control_User' => 'User Permissions Control',
	'Auth_Control_Group' => 'Group Permissions Control',
	'Auth_Control_Forum' => 'Forum Permissions Control',
// Start add Permission List
	'Auth_list_Control_Forum' => 'All Forums Permissions Control',
// End add Permission List
	'Look_up_User' => 'Look up User',
	'Look_up_Group' => 'Look up Group',
	'Look_up_Forum' => 'Look up Forum',

	'Group_auth_explain' => 'Alter the permissions and moderator status assigned to each user group. Do not forget when changing group permissions that individual user permissions may still allow the user entry to forums, etc. You will be warned if this is the case.',
	'User_auth_explain' => 'Alter the permissions and moderator status assigned to each individual user. Do not forget when changing user permissions that group permissions may still allow the user entry to forums, etc. You will be warned if this is the case.',
	'Forum_auth_explain' => 'Alter the authorization levels of each forum. You will have both a simple and advanced method for doing this, where advanced offers greater control of each forum operation. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.',
// Start add Permission List
	'Forum_auth_list_explain' => 'Alter the authorization levels of each forum. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.',
// End add Permission List

	'Simple_mode' => 'Simple Mode',
	'Advanced_mode' => 'Advanced Mode',
	'Moderator_status' => 'Moderator status',

	'Allowed_Access' => 'Allowed Access',
	'Disallowed_Access' => 'Disallowed Access',
	'Is_Moderator' => 'Is Moderator',
	'Not_Moderator' => 'Not Moderator',

	'Conflict_warning' => 'Authorization Conflict Warning',
	'Conflict_access_userauth' => 'This user still has access rights to this forum via group membership. You may want to alter the group permissions or remove this user from the group to fully prevent them having access rights. The groups granting rights (and the forums involved) are noted below.',
	'Conflict_mod_userauth' => 'This user still has moderator rights to this forum via group membership. You may want to alter the group permissions or remove this user from the group to fully prevent them having moderator rights. The groups granting rights (and the forums involved) are noted below.',

	'Conflict_access_groupauth' => 'The following user (or users) still have access rights to this forum via their user permission settings. You may want to alter their user permissions to fully prevent them having access rights. The users granted rights (and the forums involved) are noted below.',
	'Conflict_mod_groupauth' => 'The following user (or users) still have moderator rights to this forum via their user permissions settings. You may want to alter their user permissions to fully prevent them having moderator rights. The users granted rights (and the forums involved) are noted below.',

	'Public' => 'Public',
	'Private' => 'Private',
	'Registered' => 'Registered',
	'Self' => 'Self',
	'Administrators' => 'Administrators',
	'Hidden' => 'Hidden',

// These are displayed in the drop down boxes for advanced mode forum auth, try and keep them short!
	'Forum_NONE' => 'NONE',
	'Forum_ALL' => 'ALL',
	'Forum_REG' => 'REG',
	'Forum_SELF' => 'SELF',
	'Forum_PRIVATE' => 'PRIVATE',
	'Forum_MOD' => 'MOD',
	'Forum_JADMIN' => 'J ADMIN',
	'Forum_ADMIN' => 'ADMIN',

	'View' => 'View',
	'Read' => 'Read',
	'Post' => 'Post',
	'Reply' => 'Reply',
	'Edit' => 'Edit',
	'Delete' => 'Delete',
	'Sticky' => 'Sticky',
	'Announce' => 'Announce',
	'Vote' => 'Vote',
	'Pollcreate' => 'Poll create',

	'Simple_Permission' => 'Simple Permissions',

	'User_Level' => 'User Level',
	'Auth_User' => 'User',
	'Auth_Junior_Admin' => 'Junior Administrator',
	'Auth_Admin' => 'Administrator',
	'Group_memberships' => 'Usergroup memberships (in total: %d)',
	'Usergroup_members' => 'This group has the following members (in total: %d)',

	'Forum_auth_updated' => 'Forum permissions updated',
	'User_auth_updated' => 'User permissions updated',
	'Group_auth_updated' => 'Group permissions updated',

	'Auth_updated' => 'Permissions have been updated',
	'Click_return_userauth' => 'Click %sHere%s to return to User Permissions',
	'Click_return_groupauth' => 'Click %sHere%s to return to Group Permissions',
	'Click_return_forumauth' => 'Click %sHere%s to return to Forum Permissions',

// Banning
	'Ban_control' => 'Ban Control',
	'Ban_explain' => 'Control the banning of users. You can achieve this by banning either or both a specific user or an individual or range of IP addresses or hostnames. These methods prevent a user from even reaching the index page of your site. To prevent a user from registering under a different username you can also specify a banned email address. Please note that banning an email address alone will not prevent that user from being able to log on or post to your board. You should use one of the first two methods to achieve this.',
	'Ban_explain_warn' => 'Please note that entering a range of IP addresses results in all the addresses between the start and end being added to the banlist. Attempts will be made to minimise the number of addresses added to the database by introducing wildcards automatically where appropriate. If you really must enter a range, try to keep it small or better yet state specific addresses.',

	'Select_username' => 'Select a Username',
	'Select_ip' => 'Select an IP address',
	'Select_email' => 'Select an Email address',

	'Ban_username' => 'Ban one or more specific users',
	'Ban_username_explain' => 'You can ban multiple users in one go by using the appropriate combination of mouse and keyboard for your computer and browser',

	'Ban_IP' => 'Ban one or more IP addresses or hostnames',
	'IP_hostname' => 'IP addresses or hostnames',
	'Ban_IP_explain' => 'To specify several different IP addresses or hostnames separate them with commas. To specify a range of IP addresses, separate the start and end with a hyphen (-); to specify a wildcard, use an asterisk (*).',

	'Ban_email' => 'Ban one or more email addresses',
	'Ban_email_explain' => 'To specify more than one email address, separate them with commas. To specify a wildcard username, use * like *@hotmail.com',

	'Unban_username' => 'Un-ban one or more specific users',
	'Unban_username_explain' => 'You can un-ban multiple users in one go by using the appropriate combination of mouse and keyboard for your computer and browser',

	'Unban_IP' => 'Un-ban one or more IP addresses',
	'Unban_IP_explain' => 'You can un-ban multiple IP addresses in one go by using the appropriate combination of mouse and keyboard for your computer and browser',

	'Unban_email' => 'Un-ban one or more email addresses',
	'Unban_email_explain' => 'You can un-ban multiple email addresses in one go by using the appropriate combination of mouse and keyboard for your computer and browser',

	'No_banned_users' => 'No banned usernames',
	'No_banned_ip' => 'No banned IP addresses',
	'No_banned_email' => 'No banned email addresses',

	'Ban_update_sucessful' => 'The banlist has been updated successfully',
	'Click_return_banadmin' => 'Click %sHere%s to return to Ban Control',

// Configuration
	'General_Config' => 'General Configuration',
	'Config_explain' => '<b>Customize all the general site options. For User and Forum configurations, use the related links on the left hand side.</b>',

	'Click_return_config' => 'Click %sHere%s to return to General Configuration',

	'General_settings' => 'General Site Settings',
	'Server_name' => 'Domain Name',
	'Server_name_explain' => 'The domain name from which this board runs',
	'Script_path' => 'Script path',
	'Script_path_explain' => 'The path where Icy Phoenix is located relative to the domain name',
	'Server_port' => 'Server Port',
	'Server_port_explain' => 'The port your server is running on, usually 80. Only change if different',
	'Site_name' => 'Site name',
	'Site_desc' => 'Site description',
	'Board_disable' => 'Disable site',
	'Board_disable_explain' => 'This will make the site unavailable to users. Administrators are able to access the Administration Panel while the site is disabled.',
	'Acct_activation' => 'Enable account activation',
	'Acc_None' => 'None', // These three entries are the type of activation
	'Acc_User' => 'User',
	'Acc_Admin' => 'Admin',

	'Abilities_settings' => 'User and Site Basic Settings',
	'Max_poll_options' => 'Max number of poll options',
	'Flood_Interval' => 'Flood Interval',
	'Flood_Interval_explain' => 'Number of seconds a user must wait between posts',
	'Board_email_form' => 'User email via site',
	'Board_email_form_explain' => 'Users send email to each other via this site',
	'Topics_per_page' => 'Topics per page',
	'Posts_per_page' => 'Posts per page',
	'Hot_threshold' => 'Posts for popular threshold',
	'Default_style' => 'Default Style',
	'Override_style' => 'Override user style',
	'Override_style_explain' => 'Replaces users style with the default',
	'Default_language' => 'Default Language',
	'Date_format' => 'Date Format',
	'System_timezone' => 'System Timezone',
	'Enable_gzip' => 'Enable GZip Compression',
// Start Gzip Compression Level MOD
	'Gzip_level' => 'Gzip Compression Level',
	'Gzip_level_explain' => 'Change the compression level (a number between 0-9). 0 is equivalent to off, 1 is very low, and 9 is the maximum. 9 is recommended.',
// End Gzip Compression Level MOD
	'Enable_prune' => 'Enable Forum Pruning',
	'Allow_HTML' => 'Allow HTML',
	'Allow_BBCode' => 'Allow BBCode',
	'Allowed_tags' => 'Allowed HTML tags',
	'Allowed_tags_explain' => 'Separate tags with commas',
	'Allow_smilies' => 'Allow Smileys',
	'Smilies_path' => 'Smileys Storage Path',
	'Smilies_path_explain' => 'Path under your Icy Phoenix root dir, e.g. images/smiles',
	'Allow_sig' => 'Allow Signatures',
	'Max_sig_length' => 'Maximum signature length',
	'Max_sig_length_explain' => 'Maximum number of characters in user signatures',
	'Allow_name_change' => 'Allow Username changes',

	'Avatar_settings' => 'Avatar Settings',
	'Allow_local' => 'Enable gallery avatars',
	'Allow_remote' => 'Enable remote avatars',
	'Allow_remote_explain' => 'Avatars linked to from another website',
	'Allow_upload' => 'Enable avatar uploading',
	'Max_avatar_filesize' => 'Maximum Avatar File Size',
	'Max_avatar_filesize_explain' => 'For uploaded avatar files (in bytes)',
	'Max_avatar_size' => 'Maximum Avatar Dimensions',
	'Max_avatar_size_width' => 'Maximum Avatar Width',
	'Max_avatar_size_height' => 'Maximum Avatar Height',
	'Max_avatar_size_explain' => 'Dimension in pixels',
	'Avatar_storage_path' => 'Avatar Storage Path',
	'Avatar_storage_path_explain' => 'Path under your Icy Phoenix root dir, e.g. images/avatars',
	'Avatar_gallery_path' => 'Avatar Gallery Path',
	'Avatar_gallery_path_explain' => 'Path under your Icy Phoenix root dir for pre-loaded images, e.g. images/avatars/gallery',

	'COPPA_settings' => 'COPPA Settings',
	'COPPA_fax' => 'COPPA Fax Number',
	'COPPA_mail' => 'COPPA Mailing Address',
	'COPPA_mail_explain' => 'This is the mailing address to which parents will send COPPA registration forms',

	'Email_settings' => 'Email Settings',
	'Admin_email' => 'Admin Email Address',
	'Email_sig' => 'Email Signature',
	'Email_sig_explain' => 'This text will be attached to all emails sent from the board',
	'Use_SMTP' => 'Use SMTP Server for email',
	'Use_SMTP_explain' => 'Say yes if you want to, or have to send email via a named server instead of the local mail function',
	'SMTP_server' => 'SMTP Server Address',
	'SMTP_port' => 'SMTP Port',
	'SMTP_username' => 'SMTP Username',
	'SMTP_username_explain' => 'Only enter a username if your SMTP server requires it',
	'SMTP_password' => 'SMTP Password',
	'SMTP_password_explain' => 'Only enter a password if your SMTP server requires it',

	'Disable_privmsg' => 'Private Messages',
	'Inbox_limits' => 'Max posts in Inbox',
	'Sentbox_limits' => 'Max posts in Sentbox',
	'Savebox_limits' => 'Max posts in Savebox',

	'Cookie_settings' => 'Cookie settings',
	'Cookie_settings_explain' => 'These details define how cookies are sent to your users\' browsers. In most cases the default values for the cookie settings should be sufficient, but if you need to change them do so with care - incorrect settings can prevent users from logging in',
	'Cookie_domain' => 'Cookie domain',
	'Cookie_name' => 'Cookie name',
	'Cookie_path' => 'Cookie path',
	'Cookie_secure' => 'Cookie secure',
	'Cookie_secure_explain' => 'If your server is running via SSL, set this to enabled, else leave as disabled',
	'Session_length' => 'Session length [ seconds ]',
	'SESSION_LAST_VISIT_RESET' => 'Last Visit Refresh If Session Expired',
	'SESSION_LAST_VISIT_RESET_EXPLAIN' => 'If you enable this option, Last Visit time for use will be updated even after if session expires. Otherwise Last Visit time will be updated only if autologin is enabled and the maximum login time has been reached.',

// Visual Confirmation
	'Visual_confirm' => 'Enable Visual Confirmation',
	'Visual_confirm_explain' => 'Requires users to enter a code defined by an image when registering.',

// Autologin Keys - added 2.0.18
	'Allow_autologin' => 'Allow automatic logins',
	'Allow_autologin_explain' => 'Determines whether users are allowed to select to be automatically logged in when visiting the forum',
	'Autologin_time' => 'Automatic login key expiry',
	'Autologin_time_explain' => 'How long an autologin key is valid for in days if the user does not visit the board. Set to zero to disable expiry.',

// Forum Management
	'Forum_admin' => 'Forum Administration',
	'Forum_admin_explain' => 'Add, delete, edit, re-order and re-synchronise categories and forums',
	'Edit_forum' => 'Edit forum',
	'Create_forum' => 'Create new forum',
	'Create_category' => 'Create new category',
	'Remove' => 'Remove',
	'Action' => 'Action',
	'Update_order' => 'Update Order',
	'Config_updated' => 'Configuration Updated Successfully',
	'MOVE_UP' => 'Move up',
	'MOVE_DOWN' => 'Move down',
	'RESYNC' => 'Resync',
	'No_mode' => 'No mode was set',
	'Forum_edit_delete_explain' => 'Customize all the general board options. For User and Forum configurations use the related links on the left hand side',
	'Forum_Expand' => 'Expand',
	'Forum_Collapse' => 'Collapse',
	'Forum_Expand_all' => 'Expand all',
	'Forum_Collapse_all' => 'Collapse all',

	'Move_contents' => 'Move all contents',
	'Forum_delete' => 'Delete Forum',
	'Forum_delete_explain' => 'Delete a forum (or category) and decide where you want to put all topics (or forums) it contained.',

	'Status_locked' => 'Locked',
	'Status_unlocked' => 'Unlocked',
	'Forum_settings' => 'General Forum Settings',
	'Forum_name' => 'Forum name',
	'Forum_desc' => 'Description',
	'Forum_status' => 'Forum status',
	'Forum_pruning' => 'Auto-pruning',

	'prune_freq' => 'Check for topic age every',
	'prune_days' => 'Remove topics that have not been posted to in',
	'Set_prune_data' => 'You have turned on auto-prune for this forum but did not set a frequency or number of days to prune. Please go back and do so.',

	'FORUM_SIMILAR_TOPICS' => 'Similar Topics',
	'FORUM_SIMILAR_TOPICS_EXPLAIN' => 'If you enable this option you will see a box with similar topics at the bottom of each topic in this forum (please note that you need also to enable the global switch for this feature in Icy Phoenix Settings => SEO TAB)',
	'FORUM_TOPIC_VIEWS' => 'Topics Viewer',
	'FORUM_TOPIC_VIEWS_EXPLAIN' => 'If you enable this option all users that views topics in this forum will be stored in the DB (please note that you need also to enable the global switch for this feature in Icy Phoenix Settings => SQL TAB)',
	'FORUM_TAGS' => 'Forum Tags',
	'FORUM_TAGS_EXPLAIN' => 'If you enable this option you will see a box with all the most used words in this forum (please note that you need also to enable the global switch for this feature in Icy Phoenix Settings => SEO TAB)',
	'FORUM_SORT_BOX' => 'Topic Sort Box',
	'FORUM_SORT_BOX_EXPLAIN' => 'If you enable this option you will see a box wich allows you to alphabetically sort topics in this forum (please note that you need also to enable the global switch for this feature in Icy Phoenix Settings)',
	'FORUM_KB_MODE' => 'KB Mode',
	'FORUM_KB_MODE_EXPLAIN' => 'If you enable this option this forum will be shown in KB Mode (topics listed like Knowledge Base)',
	'FORUM_INDEX_ICONS' => 'Index Icons',
	'FORUM_INDEX_ICONS_EXPLAIN' => 'If you enable this option you will see icons for RSS and New Topic in Forum Index (please note that you need also to enable the global switch for this feature in Icy Phoenix Settings)',
	'FORUM_RECURRING_FIRST_POST' => 'Recurring first post',
	'FORUM_RECURRING_FIRST_POST_EXPLAIN' => 'Shows the first post of a topic on every topic page',

	'Move_and_Delete' => 'Move and Delete',

	'Delete_all_posts' => 'Delete all posts',
	'Nowhere_to_move' => 'Nowhere to move to',

	'Edit_Category' => 'Edit Category',
	'Edit_Category_explain' => 'Use this form to modify a category name.',

	'Forums_updated' => 'Forum and Category information updated successfully',

	'Must_delete_forums' => 'You need to delete all forums before you can delete this category',

	'Click_return_forumadmin' => 'Click %sHere%s to return to Forum Administration',

// Smiley Management
	'smiley_title' => 'Smiles Editing Utility',
	'smile_desc' => 'Add, remove and edit the emoticons or smileys that your users can use in their posts and private messages. Please note that if your browser supports it, you can also use Drag and Drop for a quick arrangement.',

	'smiley_config' => 'Smiley Configuration',
	'smiley_code' => 'Smiley Code',
	'smiley_url' => 'Smiley Image File',
	'smiley_emot' => 'Smiley Emotion',
	'smile_add' => 'Add a new Smiley',
	'Smile' => 'Smile',
	'Emotion' => 'Emotion',

	'Select_pak' => 'Select Pack (.pak) File',
	'replace_existing' => 'Replace Existing Smiley',
	'keep_existing' => 'Keep Existing Smiley',
	'smiley_import_inst' => 'You should unzip the smiley package and upload all files to the appropriate Smiley directory for your installation. Then select the correct information in this form to import the smiley pack.',
	'smiley_import' => 'Smiley Pack Import',
	'choose_smile_pak' => 'Choose a Smile Pack .pak file',
	'import' => 'Import Smileys',
	'smile_conflicts' => 'What should be done in case of conflicts',
	'del_existing_smileys' => 'Delete existing smileys before import',
	'import_smile_pack' => 'Import Smiley Pack',
	'export_smile_pack' => 'Create Smiley Pack',
	'export_smiles' => 'To create a smiley pack from your currently installed smileys, click %sHere%s to download the smiles.pak file. Name this file appropriately making sure to keep the .pak file extension.  Then create a zip file containing all of your smiley images plus this .pak configuration file.',

	'smiley_add_success' => 'The Smiley was successfully added',
	'smiley_edit_success' => 'The Smiley was successfully updated',
	'smiley_import_success' => 'The Smiley Pack was imported successfully!',
	'smiley_del_success' => 'The Smiley was successfully removed',
	'Click_return_smileadmin' => 'Click %sHere%s to return to Smiley Administration',

// User Management
	'User_admin' => 'User Administration',
	'User_admin_explain' => 'Change your users\' information and certain options. To modify the users\' permissions, please use the User and Group permissions system.',

	'Look_up_user' => 'Look up user',

	'Admin_user_fail' => 'Couldn\'t update the user\'s profile.',
	'Admin_user_updated' => 'The user\'s profile was successfully updated.',
	'Click_return_useradmin' => 'Click %sHere%s to return to User Administration',
//Start Quick Administrator User Options and Information MOD
	'Click_return_userprofile' => 'Click %sHere%s to return to the user\'s profile',
//End Quick Administrator User Options and Information MOD
	'User_delete' => 'Delete this user',
	'User_delete_explain' => 'Click here to delete this user; <u><em>this cannot be undone.</em></u>',
	'User_deleted' => 'User was successfully deleted.',

	'User_status' => 'User is active',
	'User_allowpm' => 'Can send Private Messages',
	'User_allowavatar' => 'Can display avatar',

	'Admin_avatar_explain' => 'See and delete the user\'s current avatar.',

	'User_special' => 'Special admin-only fields',
	'User_special_explain' => 'These fields are not able to be modified by the users. You can set their status and other options that are not given to users.',

// Group Management
	'Group_administration' => 'Group Administration',
	'Group_admin_explain' => 'Administer all your usergroups. You can delete, create and edit existing groups. You may choose moderators, toggle open/closed group status and set the group name and description',
	'Error_updating_groups' => 'There was an error while updating the groups',
	'Updated_group' => 'The group was successfully updated',
	'Added_new_group' => 'The new group was successfully created',
	'Deleted_group' => 'The group was successfully deleted',
	'New_group' => 'Create new group',
	'Edit_group' => 'Edit group',
	'group_name' => 'Group name',
	'group_description' => 'Group description',
	'group_moderator' => 'Group moderator',
	'group_status' => 'Group status',
	'group_open' => 'Open group',
	'group_closed' => 'Closed group',
	'group_hidden' => 'Hidden group',
	'group_delete' => 'Delete group',
	'group_delete_check' => 'Delete this group',
	'submit_group_changes' => 'Submit Changes',
	'reset_group_changes' => 'Reset Changes',
	'No_group_name' => 'You must specify a name for this group',
	'No_group_moderator' => 'You must specify a moderator for this group',
	'No_group_mode' => 'You must specify a mode for this group, open or closed',
	'No_group_action' => 'No action was specified',
	'delete_group_moderator' => 'Delete the old group moderator?',
	'delete_moderator_explain' => 'If you\'re changing the group moderator, check this box to remove the old moderator from the group. By not checking it, the user will become a regular member of the group.',
	'Click_return_groupsadmin' => 'Click %sHere%s to return to Group Administration.',
	'Select_group' => 'Select a group',
	'Look_up_group' => 'Look up group',

// Prune Administration
	'Forum_Prune' => 'Forum Prune',
	'Forum_Prune_explain' => 'This will delete any topic which has not been posted to within the number of days you select. If you do not enter a number then all topics will be deleted. It will not remove topics in which polls are still running nor will it remove announcements. You will need to remove those topics manually.',
	'Do_Prune' => 'Do Prune',
	'All_Forums' => 'All Forums',
	'Prune_topics_not_posted' => 'Prune topics with no replies in this many days',
	'Topics_pruned' => 'Topics pruned',
	'Posts_pruned' => 'Posts pruned',
	'Prune_success' => 'Pruning of forums was successful',

// Word censor
	'Words_title' => 'Word Censoring',
	'Words_explain' => 'Add, edit and remove words that will be automatically censored on your forums. In addition people will not be allowed to register with usernames containing these words. Wildcards (*) are accepted in the word field. For example, *test* will match detestable, test* would match testing, *test would match detest.',
	'Word' => 'Word',
	'Edit_word_censor' => 'Edit word censor',
	'Replacement' => 'Replacement',
	'Add_new_word' => 'Add new word',
	'Update_word' => 'Update word censor',

	'Must_enter_word' => 'You must enter a word and its replacement',
	'No_word_selected' => 'No word selected for editing',

	'Word_updated' => 'The selected word censor has been successfully updated',
	'Word_added' => 'The word censor has been successfully added',
	'Word_removed' => 'The selected word censor has been successfully removed',

	'Click_return_wordadmin' => 'Click %sHere%s to return to Word Censor Administration',

// Mass Email
	'Mass_email_explain' => 'Email a message to either all of your users or all users of a specific group.  To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients. If you are emailing a large group of people please be patient after submitting and do not stop the page halfway through. It is normal for a mass emailing to take a long time and you will be notified when the script has completed',
	'Compose' => 'Compose',

	'Recipients' => 'Recipients',
	'All_users' => 'All Users',

	'Email_successfull' => 'Your message has been sent',
	'Click_return_massemail' => 'Click %sHere%s to return to the Mass Email form',

// Ranks admin
	'Ranks_title' => 'Rank Administration',
	'Ranks_explain' => 'Add, edit, view and delete ranks. You can also create custom ranks which can be applied to a user via the user management facility',

	'Add_new_rank' => 'Add new rank',

	'Rank_title' => 'Rank Title',
	'Rank_special' => 'Set as Special Rank',
	'Rank_minimum' => 'Minimum Posts',
	'Rank_maximum' => 'Maximum Posts',
	'Rank_image' => 'Rank Image (Relative to Icy Phoenix root path)',
	'Rank_image_explain' => 'Use this to define a small image associated with the rank',

	'Must_select_rank' => 'You must select a rank',
	'No_assigned_rank' => 'No special rank assigned',

	'Rank_updated' => 'The rank was successfully updated',
	'Rank_added' => 'The rank was successfully added',
	'Rank_removed' => 'The rank was successfully deleted',
	'No_update_ranks' => 'The rank was successfully deleted. However, user accounts using this rank were not updated. You will need to manually reset the rank on these accounts',

	'Click_return_rankadmin' => 'Click %sHere%s to return to Rank Administration',

// Disallow Username Admin
	'Disallow_control' => 'Username Disallow Control',
	'Disallow_explain' => 'Control usernames which will not be allowed to be used. Disallowed usernames are allowed to contain a wildcard character of *. Please note that you will not be allowed to specify any username that has already been registered. You must first delete that name and then disallow it.',

	'Delete_disallow' => 'Delete',
	'Delete_disallow_title' => 'Remove a Disallowed Username',
	'Delete_disallow_explain' => 'You can remove a disallowed username by selecting the username from this list and clicking delete',

	'Add_disallow' => 'Add',
	'Add_disallow_title' => 'Add a disallowed username',
	'Add_disallow_explain' => 'You can disallow a username using the wildcard character * to match any character',

	'No_disallowed' => 'No Disallowed Usernames',

	'Disallowed_deleted' => 'The disallowed username has been successfully removed',
	'Disallow_successful' => 'The disallowed username has been successfully added',
	'Disallowed_already' => 'The name you entered could not be disallowed. It either already exists in the list, exists in the word censor list, or a matching username is present.',

	'Click_return_disallowadmin' => 'Click %sHere%s to return to Disallow Username Administration',

// Styles Admin
	'Styles_admin' => 'Styles Administration',
	'Styles_explain' => 'Add, remove and manage styles (templates and themes) available to your users',
	'Styles_addnew_explain' => 'The following list contains all the themes that are available for the templates you currently have. The items on this list have not yet been installed into the Icy Phoenix database. To install a theme, simply click the install link beside an entry.',

	'Select_template' => 'Select a Template',

	'Style' => 'Style',
	'Template' => 'Template',
	'Download' => 'Download',

	'Edit_theme' => 'Edit Theme',
	'Edit_theme_explain' => 'Edit the settings for the selected theme',

	'Create_theme' => 'Create Theme',
	'Create_theme_explain' => 'Create a new theme for a selected template. When entering colours (for which you should use hexadecimal notation) you must not include the initial #, i.e.. cccccc is valid, #cccccc is not',

	'Export_themes' => 'Export Themes',
	'Export_explain' => 'Export the theme data for a selected template. Select the template from the list below and the script will create the theme configuration file and attempt to save it to the selected template directory. If it cannot save the file itself it will give you the option to download it. In order for the script to save the file you must give write access to the webserver for the selected template dir. For more information on this see the Icy Phoenix users guide.',

	'Theme_installed' => 'The selected theme has been installed successfully',
	'Style_removed' => 'The selected style has been removed from the database. To fully remove this style from your system you must delete the appropriate style from your templates directory.',
	'Theme_info_saved' => 'The theme information for the selected template has been saved. You should now return the permissions on the theme_info.cfg (and if applicable the selected template directory) to read-only',
	'Theme_updated' => 'The selected theme has been updated. You should now export the new theme settings',
	'Theme_created' => 'Theme created. You should now export the theme to the theme configuration file for safe keeping or use elsewhere',

	'Confirm_delete_style' => 'Are you sure you want to delete this style?',

	'Download_theme_cfg' => 'The exporter could not write the theme information file. Click the button below to download this file with your browser. Once you have downloaded it you can transfer it to the directory containing the template files. You can then package the files for distribution and use elsewhere if you desire',
	'No_themes' => 'The template you selected has no themes attached to it. To create a new theme click the Create New link on the left hand panel',
	'No_template_dir' => 'Could not open the template directory. It may be unreadable by the webserver or may not exist',
	'Cannot_remove_style' => 'You cannot remove the style selected since it is currently the forum default. Please change the default style and try again.',
	'Style_exists' => 'The style name you selected already exists, please go back and choose a different name.',

	'Click_return_styleadmin' => 'Click %sHere%s to return to Style Administration',

	'Theme_settings' => 'Theme Settings',
	'Theme_element' => 'Theme Element',
	'Simple_name' => 'Simple Name',
	'Save_Settings' => 'Save Settings',

	'Stylesheet' => 'CSS Stylesheet',
	'Stylesheet_explain' => 'Filename for CSS stylesheet to use for this theme.',
	'Background_image' => 'Background Image',
	'Background_color' => 'Background Colour',
	'Theme_name' => 'Theme Name',
	'Link_color' => 'Link Colour',
	'Text_color' => 'Text Colour',
	'VLink_color' => 'Visited Link Colour',
	'ALink_color' => 'Active Link Colour',
	'HLink_color' => 'Hover Link Colour',
	'Tr_color1' => 'Table Row Colour 1',
	'Tr_color2' => 'Table Row Colour 2',
	'Tr_color3' => 'Table Row Colour 3',
	'Tr_class1' => 'Table Row Class 1',
	'Tr_class2' => 'Table Row Class 2',
	'Tr_class3' => 'Table Row Class 3',
	'Th_color1' => 'Table Header Colour 1',
	'Th_color2' => 'Table Header Colour 2',
	'Th_color3' => 'Table Header Colour 3',
	'Th_class1' => 'Table Header Class 1',
	'Th_class2' => 'Table Header Class 2',
	'Th_class3' => 'Table Header Class 3',
	'Td_color1' => 'Table Cell Colour 1',
	'Td_color2' => 'Table Cell Colour 2',
	'Td_color3' => 'Table Cell Colour 3',
	'Td_class1' => 'Table Cell Class 1',
	'Td_class2' => 'Table Cell Class 2',
	'Td_class3' => 'Table Cell Class 3',

// Admin Userlist Start
	'Userlist' => 'User list',
	'Userlist_description' => 'View a complete list of your users and perform various actions on them',

	'Add_group' => 'Add to a Group',
	'Add_group_explain' => 'Select which group to add the selected users to',

	'Open_close' => 'Open/Close',
	'Active' => 'Active',
	'Group' => 'Group(s)',
	'Rank' => 'Rank',
	'Last_activity' => 'Last Activity',
	'Never' => 'Never',
	'User_manage' => 'Manage',
	'Find_all_posts' => 'Find All Posts',

	'Select_one' => 'Select One',
	'Ban' => 'Ban',
	'Activate_deactivate' => 'Activate/Deactivate',

	'User_id' => 'User id',
	'User_level' => 'User Level',
	'Ascending' => 'Ascending',
	'Descending' => 'Descending',
	'Show' => 'Show',
	'All' => 'All',

	'Member' => 'Member',
	'Pending' => 'Pending',

	'Confirm_user_ban' => 'Are you sure you want to ban the selected user(s)?',
	'Confirm_user_deleted' => 'Are you sure you want to delete the selected user(s)?',

	'User_status_updated' => 'User(s) status updated successfully!',
	'User_banned_successfully' => 'User(s) banned successfully!',
	'User_deleted_successfully' => 'User(s) deleted successfully!',
	'User_add_group_successfully' => 'User(s) added to group successfully!',

	'Click_return_userlist' => 'Click %sHere%s to return to the User List',
//
// Admin Userlist End

// Version Check
	'Version_up_to_date' => 'Your installation is up to date, no updates are available for your version of phpBB.',
	'Version_up_to_date_ip' => 'No updates are available for your version of Icy Phoenix',
	'Version_not_up_to_date' => 'Your installation does <b>not</b> seem to be up to date. Updates are available for your version of phpBB, please visit <a href="http://www.phpbb.com/downloads.php" target="_new">http://www.phpbb.com/downloads.php</a> to obtain the latest version.',
	'Version_not_up_to_date_ip' => 'Updates are available for your version of Icy Phoenix, please visit <a href="http://www.icyphoenix.com/" target="_new">Icy Phoenix</a>.',
	'Latest_version_info' => 'The latest available version is <b>phpBB %s</b>.',
	'Current_version_info' => 'You are running <b>phpBB %s</b>.',
	'Connect_socket_error' => 'Unable to open connection to phpBB Server, reported error is:<br />%s',
	'Connect_socket_error_ip' => 'Unable to open connection to Icy Phoenix Server',
	'Socket_functions_disabled' => 'Unable to use socket functions.',
	'Mailing_list_subscribe_reminder' => 'For the latest information on updates to phpBB, why not <a href="http://www.phpbb.com/support/" target="_new">subscribe to our mailing list</a>.',
	'Version_information' => 'Version Information',
	'Version_not_checked' => 'Version checking is currently disabled.',

// Advanced Signature Divider Control
	'sig_title' => 'Advanced Signature Divider Control',
	'sig_divider' => 'Current Signature Divider',
	'sig_explain' => 'Control what divides the user\'s signature from their post',

// BIRTHDAY - BEGIN
	'Birthday_required' => 'Force users to submit a birthday',
	'Enable_birthday_greeting' => 'Enable birthday greetings',
	'Birthday_greeting_explain' => 'Enable Users who have submitted a date of birth to have a birthday greeting via PM when they visit the board. To enable/disable Birthdays Email, you need to check the <b>Cron</b> section in <b>Main Settings</b>.',
	'Next_birthday_greeting' => 'Next birthday popup year',
	'Next_birthday_greeting_explain' => 'This field keeps track of the next year the user will have a birthday greeting',
	'Wrong_next_birthday_greeting' => 'The supplied, next birthday popup year, was not valid, please try again',
	'Max_user_age' => 'Maximum user age',
	'Min_user_age' => 'Minimum user age',
	'Min_user_age_explain' => 'This is the minimum age a user can set in his profile',
	'Birthday_lookforward' => 'Birthday look forward',
	'Birthday_lookforward_explain' => 'Number of days the script should look forward to for users with a birthday',
// BIRTHDAY - END

// Start add - Yellow card admin MOD
	'Max_user_bancard' => 'Maximum number of warnings',
	'Max_user_bancard_explain' => 'If a user gets more yellow cards than this limit, the user will be banned',
	'ban_card' => 'Yellow card',
	'ban_card_explain' => 'The user will be banned when he/she is in excess of %d yellow cards',
	'Greencard' => 'Un-ban User',
	'Bluecard' => 'Post Report',
	'Bluecard_limit' => 'Interval of bluecard',
	'Bluecard_limit_explain' => 'Notify the moderators again for every x bluecards given to a post',
	'Bluecard_limit_2' => 'Limit of bluecard',
	'Bluecard_limit_2_explain' => 'First notification to moderators is sent when a post gets this number of blue cards',
	'Report_forum' => 'Report forum',
	'Report_forum_explain' => 'Select the forum where users\' reports are to be posted. Users MUST have at least post/reply access to this forum',

// Start add - Last visit MOD
	'Hidde_last_logon' => 'Hide last logon time',
	'Hidde_last_logon_explain' => 'If this is set to yes, users last logon time is visible only to administrators',
// End add - Last visit MOD
//
// Start add - Online/Offline/Hidden Mod
	'Online_time' => 'Online status time',
	'Online_time_explain' => 'Number of seconds a user must be displayed online (do not use a lower value than 60).',
	'Online_setting' => 'Online Status Setting',
	'Online_color' => 'Online text colour',
	'Offline_color' => 'Offline text colour',
	'Hidden_color' => 'Hidden text colour',
// End add - Online/Offline/Hidden Mod

// Disallow other admins to delete or edit the first admin MOD
	'L_LISTOFADMINEDIT' => 'Blocked access to the account of the first admin',
	'L_LISTOFADMINEDITEXP' => 'This is a list of blocked access for the account of the first admin of the forum. Other admins have tried to change, delete or set him to a normal user. No conversion of the profile data and/or the permissions of the first Admin took place and were successfully blocked. This list can be cleared only by the first admin of the forum.',
	'L_LISTOFADMINEDITUSERS' => 'List of blocked access for the first admin account',
	'L_LISTOFADMINTEXT' => 'Successfully blocked access took place through',
	'L_DELETEMSG' => 'Delete entries',
	'L_DELETESUCMSG' => 'The entries were deleted successfully',
	'L_ADMINEDITMSG' => 'You do not have permission to edit the profile data and/or the permissions of the first admin of the forum.<br /><br />This unauthorized access attempt was successfully blocked and recorded!',
	'use_thank' => 'Enable Thanks',
	'Default_avatar' => 'Set a default avatar',
	'Default_avatar_explain' => 'This gives users that haven\'t yet selected an avatar, a default one. Set the default avatar for guests and users, and then select whether you want the avatar to be displayed for registered users, guests, both or none.',
	'Default_avatar_guests' => 'Guests',
	'Default_avatar_users' => 'Users',
	'Default_avatar_both' => 'Both',
	'Default_avatar_none' => 'None',
	'Default_avatar_guests_url' => 'Path to the default avatar for Guests',
	'Default_avatar_users_url' => 'Path to the default avatar for Users',

// Start Optimize Database
	'Optimize' => 'Optimize',
	'Optimize_explain' => 'Optimize and remove empty spaces from the database.<br />This operation should be performed regularly for maximum reliability, speed and execution.',
	'Optimize_DB' => 'Optimize Database',
	'Optimize_Enable_cron' => 'Enable Cron',
	'Optimize_Cron_every' => 'Cron Every',
	'Optimize_Cron_every_explain' => 'Please note that you need also to enable <b>PHP Cron [Global Switch]</b> in <b>ACP &raquo; Configuration &raquo; Main Settings &raquo; Cron</b>',
	'Optimize_month' => 'Month',
	'Optimize_2weeks' => '2 weeks',
	'Optimize_week' => 'Week',
	'Optimize_3days' => '3 days',
	'Optimize_day' => 'Day',
	'Optimize_6hours' => '6 hours',
	'Optimize_hour' => 'Hour',
	'Optimize_30minutes' => '30 minutes',
	'Optimize_20seconds' => '20 seconds (only for test)',
	'Optimize_Current_time' => 'Current Time',
	'Optimize_Next_cron_action' => 'Next Cron Action',
	'Optimize_Performed_Cron' => 'Performed Cron',
	'Optimize_Show_not_optimized' => 'Show only tables not optimized',
	'Optimize_Show_begin_for' => 'Show only tables that begin with',
	'Optimize_Configure' => 'Configure',
	'Optimize_Table' => 'Table',
	'Optimize_Record' => 'Record',
	'Optimize_Type' => 'Type',
	'Optimize_Size' => 'Size',
	'Optimize_Status' => 'Status',
	'Optimize_CheckAll' => 'Check All',
	'Optimize_UncheckAll' => 'Uncheck All',
	'Optimize_InvertChecked' => 'Invert Checked',
	'Optimize_return' => 'Click %sHere%s to return to the Optimize Database',
	'Optimize_success' => 'The Database has been successfully optimized',
	'Optimize_NoTableChecked' => '<b>No</b> Tables Checked',

// End Optimize Database
// Start add - Global announcement MOD
	'Globalannounce' => 'Global Announce',
// End add - Global announcement MOD

// google bot detector by pukapuka
	'Detector' => 'Google Bot Detector',
	'Detector_Explain' => '',
	'Detector_ID' => '#',
	'Detector_Time' => 'Time',
	'Detector_Url' => 'Url',
	'Detector_Clear' => 'Clear All',
	'Detector_No_Bot' => 'No Bot Detected',
	'Detector_Cleared' => 'Detect Information Cleared Successfully',
	'Click_Return_Detector' => 'Click %sHere%s to return to Detector',

// added to Auto group mod
	'group_count' => 'Number of required posts',
	'group_count_max' => 'Number of max posts',
	'group_count_updated' => '%d member(s) have been removed, %d members are added to this group',
	'Group_count_enable' => 'Users automatically added when posting',
	'Group_count_update' => 'Add/Update new users',
	'Group_count_delete' => 'Delete/Update old users',
	'User_allow_ag' => 'Activate Auto Group',
	'group_count_explain' => 'When users have posted more posts than this value <i>(in any forum)</i> then they will be <u>added</u> to this usergroup<br /> This only applies if "Users automatically added when posting" are enabled',
	'group_count_max_explain' => 'When users have posted more posts than this value <i>(in any forum)</i> then they will be <u>removed</u> from this usergroup<br /> This only applies if "Users automatically added when posting" are enabled',
	'autogroup_options' => 'Autogroup Options',

// Start add - Bin Mod
	'Bin_forum' => 'Bin forum',
	'Bin_forum_explain' => 'Use the forum ID to where topics will be trashed; a value of 0 will disable this feature. You should edit this forum permissions to allow or not allow access/view/post or reply for users.',
// End add - Bin Mod

// Mighty Gorgon - Topics Labels - BEGIN
// Begin Quick Title Edition Mod 1.0.0 by Xavier Olive.
	'TOPICS_LABELS' => 'Topics Labels Management',
	'TOPICS_LABELS_EXPLAIN' => 'You can create short bits of text which you will be able to add to the title of a topic, by pushing a single button.<br />If you want the name of the person who performed the action of modifying the title to be shown, just put <strong>%mod%</strong> where you want it to be placed. For instance, [Link OK | checked by <strong>%mod%</strong>] will be displayed as [Link OK |checked by ModeratorName]. You can insert the date in the same way by placing <strong>%date%</strong> wherever you want it to appear.',
	'MUST_SELECT_LABEL' => 'You must select a Topic Label',
	'LABEL_UPDATED' => 'Topic Label updated',
	'LABEL_ADDED' => 'Topic Label added',
	'CLICK_RETURN_TOPICS_LABELS' => 'Click %sHere%s to return to Topics Labels Management',
	'LABEL_REMOVED' => 'Topic Label removed',
	'TOPICS_LABELS_HEAD' => 'Topics Labels',
	'LABEL_INFO' => 'Topics Labels',
	'LABEL_EXAMPLE' => 'Label Example',
	'LABEL_EXAMPLE_EXPLAIN' => 'This is just a demo of how the label will look like... please note that for the sake of the example the code is not processed, it will just be shown as text!',
	'LABEL_NAME' => 'Label Name',
	'LABEL_NAME_EXPLAIN' => 'Just the name of your label',
	'LABEL_CODE' => 'Label Code',
	'LABEL_CODE_EXPLAIN' => 'Code for the label, if you want this code to be processed as BBCode/HTML, please enable the switch below',
	'LABEL_CODE_SWITCH' => 'Label Type',
	'LABEL_CODE_SWITCH_EXPLAIN' => 'Select if you want the label to be displayed in Plain Text or processed by using BBCode/HTML',
	'LABEL_CODE_SWITCH_PT' => 'Plain Text',
	'LABEL_CODE_SWITCH_BBC' => 'BBCode',
	'LABEL_CODE_SWITCH_HTML' => 'HTML',
	'LABEL_CODE_SWITCH_BBC_HTML' => 'BBCode + HTML',
	'LABEL_PERMISSION' => 'Permissions',
	'LABEL_AUTH_ADMIN' => 'Administrator',
	'LABEL_AUTH_MOD' => 'Moderator',
	'LABEL_AUTH_TOPIC_POSTER' => 'Topic poster',
	'ADD_NEW_TOPIC_LABEL' => 'Add a Topic Label',
	'LABEL_AUTH_INFO' => 'Permissions',
	'LABEL_AUTH_INFO_EXPLAIN' => 'Users with these levels will be able to use this Topics Labels',
	'LABEL_BG_COLOR' => 'Label Background Color',
	'LABEL_BG_COLOR_EXPLAIN' => 'Select the background color for the label (hex format: #ff0000)',
	'LABEL_TEXT_COLOR' => 'Label Text Color',
	'LABEL_TEXT_COLOR_EXPLAIN' => 'Select the color for the text within the label (hex format: #000000)',
	'LABEL_ICON' => 'Label Icon (Font Awesome Class Name)',
	'LABEL_ICON_EXPLAIN' => 'Insert the name of the Font Awesome Icon: <a href="http://fontawesome.io/icons/">Font Awesome reference table</a> | <a href="http://fontawesome.io/cheatsheet/">Cheatsheet</a>',
// End Quick Title Edition Mod 1.0.0 by Xavier Olive.
// Mighty Gorgon - Topics Labels - END

// Limit Image Width MOD
	'Available' => 'Available',
	'Unavailable' => 'Unavailable',
	'LIW_title' => 'Limit Image Width MOD',
	'LIW_admin_explain' => 'Switch the Limit Image Width MOD on and off and set the maximum width for each image posted in your forum. If you feel that the SQL table holding the cached images for your forum is getting too large, you can empty it by clicking the \'Empty cache table\' button.<br /><br />The \'Compatibility check\' box below indicates whether or not this MOD will function with your server.',
	'LIW_compatibility_checks' => 'Compatibility checking',
	'LIW_mod_config' => 'MOD Configuration',

	'LIW_config_updated' => 'The Limit Image Width MOD configuration has been successfully updated',
	'LIW_cache_emptied' => 'The cache table has been successfully emptied',
	'LIW_click_return_config' => 'Click %sHere%s to return the Limit Image Width MOD configuration page',

	'LIW_getimagesize' => 'getimagesize() URL support',
	'LIW_getimagesize_explain' => 'Available in PHP 4.0.5',
	'LIW_getimagesize_available' => 'The MOD is able to retrieve image dimensions',
	'LIW_getimagesize_unavailable' => 'The MOD cannot check whether or not an image is too large, and therefore will resize <i>any</i> posted image',

	'LIW_urlaware' => 'URL-aware fopen wrappers',
	'LIW_urlaware_explain' => 'Set allow_url_fopen to Yes in your php.ini',
	'LIW_urlaware_available' => 'The MOD is able to generate a fingerprint for images so it is able to cache their dimensions',
	'LIW_urlaware_unavailable' => 'The MOD cannot generate a fingerprint of the images, and is therefore unable to cache their dimensions',

	'LIW_openssl' => 'openSSL extension loaded',
	'LIW_openssl_explain' => 'Load the openssl.dll extension in your php.ini',
	'LIW_openssl_available' => 'The MOD in able to retrieve dimensions from https:// images so is able to cache them',
	'LIW_openssl_unavailable' => 'The MOD in unable to retrieve dimensions from https:// images so it is unable to cache them',

	'LIW_enable' => 'Resize images in posts',
	'LIW_enable_explain' => 'Set to %s to allow resizing of images in posts', // Set to $lang['Yes'] to ....
	'LIW_sig_enable' => 'Resize images in signatures',
	'LIW_sig_enable_explain' => 'Set to %s to allow resizing of images in signatures',
	'LIW_attach_enable' => 'Resize attached images',
	'LIW_attach_enable_explain' => 'Set to %s to allow resizing of images which are attached to a post using the Attachment MOD',
	'LIW_max_width' => 'Maximum image width',
	'LIW_max_width_explain' => 'Specify the maximum width (in pixels) for an image posted using the [img] tags',
	'LIW_empty_cache' => 'Empty image dimensions cache',
	'LIW_empty_cache_explain' => 'Your cache table currently contains <b>%s</b> records', // Your cache table currently contains <b>312</b> records
	'LIW_empty_cache_note' => '<b>Note:</b> Emptying the cache table will result in re-caching of all image dimensions, which could result in a temporary slowdown when loading a topic',
	'LIW_empty_cache_button' => 'Empty cache table',

// News
	'xs_news_settings' => 'News Settings',
	'xs_news_show' => 'Display News Banner?',
	'xs_news_show_ticker' => 'Display News Ticker?',
	'xs_news_show_ticker_explain' => 'This is a master switch. Setting this to \'No\' will stop any ticker from being shown, if set to \'Yes\' then the display state of each ticker can be controlled from their individual settings.',
	'xs_news_show_ticker_subtitle' => 'Display Ticker subtitle?',
	'xs_news_show_ticker_subtitle_explain' => 'Setting this to Yes will display \'News Tickers\' above the news tickers.',
	'xs_news_show_news_subtitle' => 'Display News subtitle?',
	'xs_news_show_news_subtitle_explain' => 'Setting this to Yes will display \'News Items\' above the news items.',
	'xs_news_dateformat' => 'Date Format',
	'xs_news_dateformat_helper' => 'Using this format: %s',

// Bantron Mod : Begin
	'Bantron' => 'Bantron',
	'BM_Title' => 'Bantron',
	'BM_Explain' => 'Add, edit, view and remove the bans in place on this board.',

	'BM_Show_bans_by' => 'Show bans by',
	'BM_All' => 'All',
	'BM_Show' => 'Show',

	'BM_IP' => 'IP',
	'BM_Last_visit' => 'Last Visit',
	'BM_Banned' => 'Banned',
	'BM_Expires' => 'Expires',
	'BM_By' => 'By',
	'BM_Reasons' => 'Reasons',

	'BM_Add_a_new_ban' => 'Add a new ban',
	'BM_Delete_selected_bans' => 'Delete selected bans',

	'BM_Private_reason' => 'Private reason',
	'BM_Private_reason_explain' => 'This reason for banning the entered usernames, e-mails, and/or IP addresses is only for the administrators purpose.',

	'BM_Public_reason' => 'Public reason',
	'BM_Public_reason_explain' => 'This reason for banning the entered usernames, e-mails, and/or IP addresses is displayed to the banned user(s) when they attempt to access the forums.',
	'BM_Generic_reason' => 'Generic reason',
	'BM_Mirror_private_reason' => 'Mirror private reason',
	'BM_Other' => 'Other',

	'BM_Expire_time' => 'Expire time',
	'BM_Expire_time_explain' => 'By specifying a date, either in relation to the current date or an absolute date, the ban will become inactive after that point in time.',
	'BM_Never' => 'Never',
	'BM_After_specified_length_of_time' => 'After specified length of time',
	'BM_Minutes' => 'Minute(s)',
	'BM_Hours' => 'Hour(s)',
	'BM_Days' => 'Day(s)',
	'BM_Weeks' => 'Week(s)',
	'BM_Months' => 'Month(s)',
	'BM_Years' => 'Year(s)',
	'BM_After_specified_date' => 'After specified date',
	'BM_AM' => 'AM',
	'BM_PM' => 'PM',
	'BM_24_hour' => '24-Hour',

	'BM_Ban_reasons' => 'Ban Reasons',
// Bantron Mod : End

	'board_disable_message' => 'Display a message for the deactivation of the site.',
	'board_disable_message_texte' => 'Message which will appear when the site is deactivated',

// Start Edit Notes MOD
	'Edit_notes_settings' => 'Edit Notes Settings',
	'Edit_notes_enable' => 'Enable edit notes',
	'Edit_notes_enable_explain' => 'Enable/disable edit notes on the board',
	'Max_edit_notes' => 'Maximum edit notes',
	'Max_edit_notes_explain' => 'Set the maximum number of edit notes per post.',
	'Edit_notes_permissions' => 'Edit notes permissions',
	'Edit_notes_permissions_explain' => 'Set which types of users may use the edit notes.',
	'Edit_notes_admin' => 'Administrators only',
	'Edit_notes_staff' => 'Staff (admins and mods)',
	'Edit_notes_reg' => 'Registered users (admins and mods too)',
	'Edit_notes_all' => 'All users (guests, registered users, admins and mods)',
// End Edit Notes MOD

// BEGIN Disable Registration MOD
	'registration_status' => 'Disable registrations',
	'registration_status_explain' => 'This will disable all new registrations to your board.',
	'registration_closed' => 'Reason for closed registrations',
	'registration_closed_explain' => 'A message that explains why registrations are closed, and appears if a user tries to register. Leave blank to show default explanation text.',
// END Disable Registration MOD

	'Gender_required' => 'Force users to submit their gender',

//admin user list mail
	'Usersname' => 'Users Name',
	'Admin_Users_List_Mail_Title' => 'List users e-mail',
	'Admin_Users_List_Mail_Explain' => 'Here a list of your users\'s e-mail',

// Start add - Forum notification MOD
	'Forum_notify' => 'Allow forum notification',
	'Forum_notify_enabled' => 'Allow',
	'Forum_notify_disabled' => 'Do not allow',
// End add - Forum notification MOD

	'Smilie_table_columns' => 'Smileys table columns',
	'Smilie_table_rows' => 'Smileys table rows',
	'Smilie_window_columns' => 'Smileys window columns',
	'Smilie_window_rows' => 'Smileys window rows',
	'Smilie_single_row' => 'Smileys single row number',
	'Smilie_single_row_explain' => 'I.E.: Quick Reply smileys number',

	'Auth_Rating' => 'Ratings',

// Gravatars
	'Enable_gravatars' => 'Enable gravatars',
	'Gravatar_rating' => 'Gravatar maximum rating',
	'Gravatar_rating_explain' => '<a href="http://www.gravatar.com/rating.php" target="_blank">Read the rating guidelines</a> for more information. Set to \'None\' for no restriction.',
	'Gravatar_default_image' => 'Gravatar default image',
	'Gravatar_default_image_explain' => 'If no gravatar is found, the server will return this image. Path to the image is relative to the Icy Phoenix root directory. Leave blank for no image.',

// Admin Account Actions
	'Account_actions' => 'Account Actions',
	'Account_inactive_explain' => 'Users who are inactive, waiting activation or deletion, each with links to edit their permissions and/or profile.',
	'Account_active_explain' => 'Active members that can be deactivated, deleted or their permissions and/or profile can be edited.',
	'Account_active' => 'Active Users',
	'Account_inactive' => 'Inactive Users',
	'Account_activate' => 'Activate marked',
	'Account_deactivate' => 'Deactivate marked',
	'Account_none' => 'There is no user(s) waiting for activation.',
	'Account_total_user' => 'User: <b>%d</b> user',
	'Account_total_users' => 'Number of users: <b>%d</b> users',
	'Account_activation' => 'Activation method',
	'Account_awaits' => 'Awaiting activation since',
	'Account_registered' => 'Registered since',
	'Account_delete_users' => 'Are you sure you want to delete these users?',
	'Account_delete_user' => 'Are you sure you want to delete this user?',
	'Account_sort_letter' => 'Show only accounts starting with',
	'Account_others' => 'others',
	'Account_all' => 'all',
	'Account_year' => 'year',
	'Account_years' => 'years',
	'Account_week' => 'week',
	'Account_weeks' => 'weeks',
	'Account_day' => 'day',
	'Account_days' => 'days',
	'Account_hour' => 'hour',
	'Account_hours' => 'hours',
	'Account_user_activated' => 'The user is activated.',
	'Account_users_activated' => 'The users are activated.',
	'Account_user_deactivated' => 'The user is deactivated.',
	'Account_users_deactivated' => 'The users are deactivated.',
	'Account_user_deleted' => 'The user is deleted.',
	'Account_users_deleted' => 'The users are deleted.',
	'Account_activated' => 'Account activation',
	'Account_activated_text' => 'Your account was activated',
	'Account_deactivated' => 'Account deactivation',
	'Account_deactivated_text' => 'Your account was deactivated',
	'Account_deleted' => 'Account deletion',
	'Account_deleted_text' => 'Your account was deleted',
	'Account_notification' => 'Notification email sent.',

// Acronyms
	'Acronyms_title' => 'Acronyms Administration',
	'Acronyms_explain' => 'Add, edit and remove acronyms that will be automatically added to posts on your forums.',
	'Acronym' => 'Acronym',
	'Acronyms' => 'Acronyms',
	'Edit_acronym' => 'Edit Acronym',
	'Description' => 'Description',
	'Add_new_acronym' => 'Add new acronym',
	'Update_acronym' => 'Update acronym',

	'Must_enter_acronym' => 'You must enter an acronym and its description',
	'No_acronym_selected' => 'No acronym selected for editing',

	'Acronym_updated' => 'The selected acronym has been successfully updated',
	'Acronym_added' => 'The acronym has been successfully added',
	'Acronym_removed' => 'The selected acronym has been successfully removed',

	'Click_return_acronymadmin' => 'Click %sHere%s to return to Acronym Administration',
	'Prune_shouts' => 'Auto prune shouts',
	'Prune_shouts_explain' => 'Number of days before the shouts are deleted. If the value is set to 0, autoprune will be disabled',

	'MOD_OS_ForumRules' => 'Forum Rules',
	'Forum_rules' => 'Forum Rules',
	'Rules_display_title' => 'Display title in the Forum Rules BOX?',
	'Rules_custom_title' => 'Custom title',
	'Rules_appear_in' => 'These rules appear while...',
	'Rules_in_viewforum' => 'Viewing this forum',
	'Rules_in_viewtopic' => 'Viewing topics in this forum',
	'Rules_in_posting' => 'Posting / replying in this forum',

	'Php_Info_Explain' => 'This page lists information on the version of PHP installed on this server. It includes details of loaded modules, available variables and default settings. This information may be useful when diagnosing problems. Please be aware that some hosting companies will limit what information is displayed here for security reasons. You are advised to not give out any details on this page except when asked for by support or other Team Member on the support forums.',

	'IcyPhoenix_Main' => 'Icy Phoenix Home Page',
	'IcyPhoenix_Download' => 'Icy Phoenix Download',
	'IcyPhoenix_Code_Changes' => 'Code Changes Mod',
	'IcyPhoenix_Updates' => 'Icy Phoenix Updates',
	'PhpBB_Upgrade' => 'phpBB Upgrade',
	'Header_Welcome' => 'Welcome to Icy Phoenix Administration Control Panel',

	'Prune_users' => 'Prune users',
	'Prune_Overview' => 'Pruning Overview',
	'Prune_title_explain' => 'Manage the pruning Settings of each Forum.',
	'Prune_forum' => 'Forum',
	'Prune_active' => 'Pruning active',
	'Prune_freq' => 'Remove all',
	'Prune_check' => 'Check all',
	'Prune_days' => 'Days',
	'Prune_days_explain' => '* Remove topics that have not been posted to.',
	'Click_return_admin_po' => '%sClick here%s, to return to Pruning Overview',
	'Prune_update' => 'The Prune Settings was successfully updated',

	'Admin_notepad_title' => 'Notepad',
	'Admin_notepad_explain' => 'Leave global memos for other Administrators.',
	'Allow_generator' => 'Enable avatar generator',
	'Avatar_generator_template_path' => 'Avatar Generator Template Path',
	'Avatar_generator_template_path_explain' => 'Path under your Icy Phoenix root dir for template images, e.g. images/avatars/generator_templates',

// Start Autolinks Mod
	'Autolink_first' => 'Autolink each keyword once per post',

	'Autolinks_title' => 'Autolinks',
	'Autolinks_explain' => 'Add, edit and remove keywords that will be automatically replaced with the url in the message posts. If the url you enter is an internal one and points to the forum/portal, ticking the box will have the session ID added to the link.<br /><br />eg. If found, the <b>Keyword</b> in the post will be replaced with the following,<br /><br />&lt;a href="<b>Url</b>" title="<b>Comment</b>" style="<b>Style</b>"&gt;<b>Text</b>&lt;/a&gt;',
	'links_keyword' => 'Keyword',
	'links_title' => 'Text',
	'links_url' => 'Url',
	'links_comment' => 'Comment',
	'links_style' => 'Style',
	'links_forum' => 'Forum for Autolink',
	'links_forum2' => 'Forum',
	'links_internal' => 'Internal',
	'Autolinks_add' => 'Add an Autolink',
	'Add_keyword' => 'Add Autolink',
	'Autolinks_edit' => 'Edit an Autolink',
	'Edit_keyword' => 'Edit Autolink',
// 'Delete_link' => 'Tick box to delete this autolink.',

	'Select_all_forums' => 'All Forums',
	'Autolink_added' => 'Autolink successfully added.',
	'Autolink_updated' => 'Autolink successfully updated.',
	'Autolink_removed' => 'Autolink successfully deleted.',
	'No_autolink_selected' => 'No autolink was selected for deletion.',
	'No_autolinks' => 'There are no Autolinks in the database.',
	'Must_enter_autolink' => 'You must enter a keyword, link text and a url.',
	'Click_return_autolinkadmin' => 'Click %sHere%s to return to Autolink Administration',
// End Autolinks Mod

// XS BUILD 030

// Login attempts configuration
	'Max_login_attempts' => 'Allowed login attempts',
	'Max_login_attempts_explain' => 'The number of allowed board login attempts.',
	'Login_reset_time' => 'Login lock time',
	'Login_reset_time_explain' => 'Time in minutes the user has to wait until he/she is allowed to login again after exceeding the number of allowed login attempts.',

// XS BUILD 035
// Smilies Order
	'position_new_smilies' => 'Should new smileys be added before or after existing smileys?',
	'smiley_change_position' => 'Change Insert Location',
	'before' => 'Before',
	'after' => 'After',
	'Move_top' => 'Send to Top',
	'Move_end' => 'Send to End',

// XS BUILD 037
// Pages Auth
	'auth_view_title' => 'Page View Auth',
	'auth_view_portal' => 'Home Page',
	'auth_view_forum' => 'Forum',
	'auth_view_viewforum' => 'View Forum',
	'auth_view_viewtopic' => 'View Topic',
	'auth_view_faq' => 'FAQ',
	'auth_view_memberlist' => 'Memberlist',
	'auth_view_groupcp' => 'Usergroups',
	'auth_view_profile' => 'Profile',
	'auth_view_search' => 'Search',
	'auth_view_album' => 'Album',
	'auth_view_links' => 'Links',
	'auth_view_calendar' => 'Calendar',
	'auth_view_attachments' => 'Attachments',
	'auth_view_download' => 'Downloads',
	'auth_view_pic_upload' => 'Pics Upload (Post Icy Images)',
	'auth_view_kb' => 'Knowledge Base',
	'auth_view_ranks' => 'Ranks',
	'auth_view_statistics' => 'Statistics',
	'auth_view_recent' => 'Recent Topics',
	'auth_view_referers' => 'Referers',
	'auth_view_rules' => 'Rules',
	'auth_view_site_hist' => 'Site History',
	'auth_view_shoutbox' => 'Shoutbox',
	'auth_view_viewonline' => 'View Online',
	'auth_view_contact_us' => 'Contact Us',
	'auth_view_ajax_chat' => 'Chat',
	'auth_view_ajax_chat_archive' => 'Chat Archive',
	'auth_view_custom_pages' => 'Custom Pages',

// Bookmark Mod
	'Max_bookmarks_links' => 'Maximum bookmarks send in link-tag',
	'Max_bookmarks_links_explain' => 'Number of bookmarks to send in link-tag at the beginning of the document. This information is for example, used by Mozilla. Enter 0 to disable this function.',

	'Faq_manager' => 'FAQ Manager',
	'Faq_Rules_manager' => 'Faq &amp; Rules',
	'board_rules' => 'Board Rules',
	'board_faq' => 'Board Faq',
	'bbcode_faq' => 'BBcode Faq',
	'attachment_faq' => 'Attachment Faq',
	'prillian_faq' => 'Prillian Faq',
	'bid_faq' => 'Buddy List Faq',


	'Account_active2' => 'Active Users',
	'Account_inactive2' => 'Inactive Users',

// Search Flood Control - added 2.0.20
	'Search_Flood_Interval' => 'Search Flood Interval',
	'Search_Flood_Interval_explain' => 'Number of seconds a user must wait between search requests',
	'Confirm_delete_smiley' => 'Are you sure you want to delete this Smiley?',
	'Confirm_delete_word' => 'Are you sure you want to delete this word censor?',
	'Confirm_delete_rank' => 'Are you sure you want to delete this rank?',

// Custom Profile Fields MOD
	'custom_field_notice_admin' => 'These items have been created by you or another administrator. For more information, check the items under the Profile Fields heading in the navbar. Items marked with a * are required fields. Items marked with a &dagger; are only being displayed to admins.',

	'field_deleted' => 'The specified field has been deleted',
	'double_check_delete' => 'Are you sure you want to delete profile field "%s" from the database permanently?',

	'here' => 'Here',
	'new_field_link' => '<a href="' . append_sid($filename . '?mode=add&amp;pfid=x') . '">%s</a>',
	'edit_field_link' => '<a href="' . append_sid($filename . '?mode=edit&amp;pfid=x') . '">%s</a>',
	'index_link' => '<a href="' . append_sid('admin_profile_fields.' . PHP_EXT . '?mode=edit&amp;pfid=x') . '">%s</a>',
	'field_exists' => 'This field already exists.<br /><br />You can try creating a <a href="' . append_sid($filename . '?mode=add&amp;pfid=x') . '">new</a> profile field,<br /><br />or try <a href="' . append_sid($filename . '?mode=edit&amp;pfid=x') . '">editing</a> the one you already have.',
	'click_here_here' => 'Click <a href="' . append_sid($filename . '?mode=add&amp;pfid=x') . '">here</a> to add another profile field,<br /><br />or click <a href="' . append_sid('admin_profile_fields.' . PHP_EXT . '?mode=edit&amp;pfid=x') . '">here</a> to return to the Admin Index.',
	'field_success' => 'Field successfully submitted!<br /><br />Click <a href="' . append_sid($filename . '?mode=add&amp;pfid=x') . '">here</a> to add another profile field,<br /><br />or click <a href="' . append_sid('admin_profile_fields.' . PHP_EXT . '?mode=edit&amp;pfid=x') . '">here</a> to return to the Admin Index.',
	'Custom_Profile' => 'Profile Fields',
	'profile_field_created' => 'Profile Field Created',
	'profile_field_updated' => 'Profile Field Updated',

	'add_field_title' => 'Add Custom Profile Fields',
	'edit_field_title' => 'Edit Custom Profile Fields',
	'add_field_explain' => 'Create new fields that your users can set in their profiles.',
	'edit_field_explain' => 'Edit fields you have already created in your users profiles.',

	'add_field_general' => 'General Settings',
	'add_field_admin' => 'Administrator Settings',
	'add_field_view' => 'Viewing Settings',
	'add_field_text_field' => 'Text Field Settings',
	'add_field_text_area' => 'Text Area Settings',
	'add_field_radio_button' => 'Radio Button Settings',
	'add_field_checkbox' => 'Checkbox Settings',

	'default_value' => 'Default Value',
	'default_value_explain' => 'This is the default for this field. If a new user does not change this value, this is the value they will have. If this is a required field, this is the value that all existing users will be set to.',
	'default_value_radio_explain' => 'Enter a name identical to one written in the available values field.',
	'default_value_checkbox_explain' => 'Enter values that will default to checked. These values must match values in the available values field',
	'max_length' => 'Maximum Length',
	'max_length_explain' => 'This is the maximum length for this field.',
	'max_length_value' => ' This must be a number between %d and %d.',
	'available_values' => 'Available Values',
	'available_values_explain' => 'Put each value on its own line',

	'add_field_view_disclaimer' => 'All of these settings will be treated as "no" if users are not allowed to view this field',

	'add_field_name' => 'Field Name',
	'add_field_name_explain' => 'Enter the name you want to associate with this field. You can customize the output for several language by editing <b>language/lang_XXX/lang_profile_fields.php</b>.',
	'add_field_description' => 'Field Description',
	'add_field_description_explain' => 'Enter a description you wish to associate with this field. It will be displayed in small text below the field name, just like this text is. You can customize the output for several language by editing <b>language/lang_XXX/lang_profile_fields.php</b>.',
	'add_field_type' => 'Field Type',
	'add_field_type_explain' => 'Select the type of profile field you want to add. Examples of each field type are shown to the far right.',
	'edit_field_type_explain' => 'Select the type of profile field you want to change this field to. Examples of each field type are shown to the far right.',
	'add_field_required' => 'Set as Required',
	'add_field_required_explain' => 'If the field is set to "Required", any user that registers later <strong>must</strong> fill it in, and all existing users will have it filled with a default value.',
	'add_field_user_can_view' => 'Allow Users to View',
	'add_field_user_can_view_explain' => 'If this is set to "yes", the user is allowed to view and edit this field. If it is set to "no", only Administrators may view or edit this value. Also, if this is set to "no", this field cannot be required.',
	'view_in_profile' => 'Viewable in User Profile',
	'profile_locations_explain' => 'These options are for if this field is to be viewed in the user\'s profile.',
	'contacts_column' => 'Contacts Column',
	'about_column' => 'About Column',
	'view_in_memberlist' => 'Viewable in Memberlist',
	'view_in_topic' => 'Viewable in Topic',
	'topic_locations_explain' => 'These options are only if this field is to be viewed in a post.',
	'author_column' => 'Author Section',
	'above' => 'Above ',
	'below' => 'Below ',

	'textarea' => 'Textarea',
	'textarea_example' => 'Example of a scrollable' . "\n" . 'Textarea.',
	'text_field' => 'Text Field',
	'text_field_example' => 'Example of a Text Field',
	'radio' => 'Radio Button',
	'radio_example' => 'Example of two Radio Buttons',
	'checkbox' => 'Checkbox',
	'checkbox_example' => 'Example of two Checkboxes',

	'profile_field_list' => 'Your Custom Profile Fields',
	'profile_field_list_explain' => 'These are all of the custom profiles you have created for your board, with links to edit or delete them.',
	'profile_field_id' => 'ID #',
	'profile_field_name' => 'Field Name',
	'profile_field_action' => 'Action',
	'no_profile_fields_exist' => 'No Custom Profile Fields Exist.',

	'enter_a_name' => 'You <strong>must</strong> enter a field name<br /><br />Press back and try again',
// END Custom Profile Fields MOD

	'Add' => 'Add',
	'split_global_announce' => 'Split Global Announcements',
	'split_announce' => 'Split Announcements',
	'split_sticky' => 'Split Stickies',
	'split_topic_split' => 'Split Topics',
	'Announce_settings' => 'Announcements Settings',
	'Split_settings' => 'Split Settings',
	'Server_Cookies' => 'Server Settings',
	'ENABLE_CHECK_DNSBL' => 'Enable public Blacklist IP check upon register',
	'ENABLE_CHECK_DNSBL_EXPLAIN' => 'If you enable IP check upon register, user IP address will be checked against the public blacklist. Please note that sometimes this check may result in unintentional blocking of regular users who have an IP listed by mistake in the public Blacklist.',
	'ENABLE_CHECK_DNSBL_POSTING' => 'Enable public Blacklist IP check upon posting',
	'ENABLE_CHECK_DNSBL_POSTING_EXPLAIN' => 'If you enable IP check upon posting, user IP address will be checked against the public blacklist.',
	'Config_explain2' => 'Customize calendar and subforums options, change appearance and settings.',
	'Forum_postcount' => 'Count user\'s posts',
	'Use_Captcha' => 'Use CAPTCHA',
	'Use_Captcha_Explain' => 'If set to YES, then advanced confirmation code is generated. If set to NO, standard activation code is generated.',
	'Sync_Pics_Count' => 'Clicking <b>YES</b> all user(s) pics counter will be synchronized.',
	'Pics_Count_Synchronized' => 'User\'s pics counters synchronized correctly',
	'Pics_Count_Not_Synchronized' => 'User\'s pics counters not synchronized correctly',

// IP - BUILD 001
// Ajax Shoutbox - BEGIN
	'Shoutbox_config' => 'AJAX Shoutbox Configuration',
	'Shout_read_only' => 'Read Only',
	'Displayed_shouts' => 'Displayed Shouts',
	'Displayed_shouts_explain' => 'Number of shouts that will be displayed when loading the shoutbox.<br /><i>0 to load all shouts.</i>',
	'Stored_shouts' => 'Stored Shouts',
	'Stored_shouts_explain' => 'Number of shouts that remain in the database.<br />This value should be equal or higher than the number of displayed shouts.<br /><i>0 to store all shouts.</i>',
	'Shout_guest_allowed' => 'Guest Allowed',
	'Shoutbox_flood' => 'Flood Interval',
	'Shoutbox_flood_explain' => 'Number of seconds a user must wait between shouts.',
// Ajax Shoutbox - END

/* lang_postcount.php - BEGIN */
	'Postcounts' => 'Post Counts Management',
	'Post_count_explain' => '<b>Edit the post count of a single user.</b>',
	'Modify_post_counts' => 'Modify Post Counts',
	'Post_count_changed' => 'Success! You have edited a user\'s post count!',
	'Click_return_posts_config' => 'Click %sHere%s to return to the post count configuration',
	'Modify_post_count' => 'Modify post count',
	'Edit_post_count' => 'Edit the post count for <b>%s</b>',
	'Post_count' => 'Number Of Messages',
/* lang_postcount.php - END */

/* lang_megamail.php - BEGIN */
	'Megamail_Explain' => 'This feature allows you to send private messages or email to either all of your users, or all users in a specific group. To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients.<br />Emails will be sent in several batches: this should circumvent timeout and server-load issues. The status of the mass mail sending will be saved in the db. You can close the window when you want to pause mass-mail-sending (the current batch will be sent out). You can simply continue later from where you left off.<br /><b>If HTML emails are enabled, then you should write emails using HTML code, inserting &lt;br /&gt; for a line break.</b><br /><b>If you chose to send FULL HTML emails, then remember that no template or css is used, so you have to insert a full html code, including HEAD and BODY tags.</b><br /><b>Please remember that Mass PM supports only BBCode, if you write a PM in HTML then it will not be correctly shown.</b>',
	'megamail_inactive_users' => 'Non visiting users in the last {DAYS} days',
	'megamail_header' => 'Your Email-Sessions',
	'megamail_id' => 'Mail-ID',
	'megamail_batchstart' => 'Processed',
	'megamail_batchsize' => 'Batch',
	'megamail_batchwait' => 'Pause',
	'megamail_created_message' => 'The Mass Mail has been saved to the database.<br /><br /> To start sending %sclick here%s or wait until the Meta-Refresh takes you there...',
	'megamail_send_message' => 'The Current Batch (%s - %s) has been sent .<br /><br /> To continue sending %sclick here%s or wait until the Meta-Refresh takes you there...',
	'megamail_status' => 'Status',
	'megamail_proceed' => '%sProceed now%s',
	'megamail_done' => 'DONE',
	'megamail_none' => 'No records were found.',
	'megamail_delete_confirm' => 'Do you really want to delete this email?',
	'megamail_deleted' => 'Email deleted successfully',
	'megamail_click_return' => 'Click %sHere%s to return to Mass Emails / PM',
/* lang_megamail.php - END */

/* lang_admin_voting.php - BEGIN */
	'Admin_Vote_Explain' => 'Poll Results (who voted and how they voted).',
	'Admin_Vote_Title' => 'Poll Administration',
	'Vote_id' => '#',
	'Poll_topic' => 'Poll Topic',
	'Vote_username' => 'Voter(s)',
	'Vote_end_date' => 'Vote Duration',
	'Sort_vote_id' => 'Poll Number',
	'Sort_poll_topic' => 'Poll Topic',
	'Sort_poll_title' => 'Poll Title',
	'Sort_poll_start' => 'Start Date',
	'Sort_poll_end' => 'End Date',
	'Submit' => 'Submit',
	'Select_sort_field' => 'Select sort field',
	'Sort_ascending' => 'Ascending',
	'Sort_descending' => 'Descending',
/* lang_admin_voting.php - END */

/* lang_admin_gd_info.php - BEGIN */
	'GD_Title' => 'GD Info',
	'NO_GD' => 'No GD',
	'GD_Description' => 'Retrieve information about the currently installed GD library',
	'GD_Freetype_Support' => 'Freetype Fonts Support:',
	'GD_Freetype_Linkage' => 'Freetype Link Type:',
	'GD_T1lib_Support' => 'T1lib Support:',
	'GD_Gif_Read_Support' => 'Gif Read Support:',
	'GD_Gif_Create_Support' => 'Gif Create Support:',
	'GD_Jpg_Support' => 'Jpg/Jpeg Support:',
	'GD_Png_Support' => 'Png Support:',
	'GD_Wbmp_Support' => 'WBMP Support:',
	'GD_XBM_Support' => 'XBM Support:',
	'GD_Jis_Mapped_Support' => 'Japanese Font Support:',
	'GD_True' => 'Yes',
	'GD_False' => 'No',
/* lang_admin_gd_info.php - END */

/* lang_admin_captcha.php - BEGIN */
	'VC_Captcha_Config' => 'CAPTCHA',
	'captcha_config_explain' => '<b>Determine the appearance of the picture used by visual confirmation when activated.</b>',
	'VC_active' => 'Visual Confirmation is active!',
	'VC_inactive' => 'Visual Confirmation is not active!',
	'background_configs' => 'Background',
	'Click_return_captcha_config' => 'Click %sHere%s to return to CAPTCHA Configuration',

	'CAPTCHA_width' => 'CAPTCHA width',
	'CAPTCHA_height' => 'CAPTCHA height',
	'background_color' => 'Background colour',
	'background_color_explain' => 'Indication in hexadecimal (eg. #0000FF for blue).',
	'pre_letters' => 'Number of shade letters',
	'pre_letters_explain' => '',
	'great_pre_letters' => 'Shade letter increase',
	'great_pre_letters_explain' => '',
	'Random' => 'Random',
	'random_font_per_letter' => 'Random font per letter',
	'random_font_per_letter_explain' => 'Each letter uses a random font.',

	'back_chess' => 'Chess sample',
	'back_chess_explain' => 'Fill the complete background with 16 rectangles.',
	'back_ellipses' => 'Ellipses',
	'back_arcs' => 'Curved lines',
	'back_lines' => 'Lines',
	'back_image' => 'Background image',
	'back_image_explain' => '(This function is not integrated yet)',

	'foreground_lattice' => 'Foreground lattice',
	'foreground_lattice_explain' => '(width x height)<br />Generate a white lattice over the CAPTCHA',
	'foreground_lattice_color' => 'Lattice colour',
	'foreground_lattice_color_explain' => 'Indication in hexadecimal (eg. #0000FF for blue).',
	'gammacorrect' => 'Contrast correction',
	'gammacorrect_explain' => '(0 = off)<br />NOTE!!! Changes of the value have direct effect on the legibility of the CAPTCHA!',
	'generate_jpeg' => 'Image type',
	'generate_jpeg_explain' => 'The JPEG format has a higher compression ratio than png has, and the outcome (max 95%), has a direct influence on the legibility of the CAPTCHA.',
	'generate_jpeg_quality' => 'Quality',
/* lang_admin_captcha.php - END */

/* lang_admin_topic_shadow.php - BEGIN */
	'Del_Before_Date' => 'Deleted all Shadow Topics before %s<br />', // %s = insertion of date
	'Deleted_Topic' => 'Deleted Shadow Topic %s<br />', // %s = topic name
	'Affected_Rows' => '%d known entries were affected<br />', // %d = affected rows (not avail with all databases!)
	'Delete_From_Date' => 'All Shadow Topics that were created before the entered date will be removed.',
	'Delete_Before_Date_Button' => 'Delete All Before Date',
	'No_Shadow_Topics' => 'No Shadow Topics were found.',
	'Topic_Shadow' => 'Topic Shadow',
	'TS_Desc' => '<b>Remove shadow topics without removing the actual message.</b><br /> Shadow topics are created when you move a post from one forum to another and choose to leave a link to the moved post.',
	'Month' => 'Month',
	'Day' => 'Day',
	'Year' => 'Year',
	'Clear' => 'Clear',
	'Resync_Ran_On' => 'Resync Ran On %s<br />', // %s = insertion of forum name
	'Version' => 'Version',

	'Title' => 'Title',
	'Moved_To' => 'Moved To',
	'Moved_From' => 'Moved From',

/* Modes */
	'topic_time' => 'Topic Time',
	'topic_title' => 'Topic Title',

/* Errors */
	'Error_Month' => 'Your input month must be between 1 and 12',
	'Error_Day' => 'Your input day must be between 1 and 31',
	'Error_Year' => 'Your input year must be between 1970 and 2038',
	'Error_Topics_Table' => 'Error accessing topics table',
/* lang_admin_topic_shadow.php - END */

/* lang_admin_rebuild_search.php - BEGIN */
	'Rebuild_search' => 'Rebuild Search',
	'Rebuild_search_desc' => 'This will index every post in your Knowledge Base and rebuild the search tables. It may take a long time to process, so please do not move from this page until it is complete.',
	'Post_limit' => 'Post limit',
	'Time_limit' => 'Time limit',
	'Refresh_rate' => 'Refresh rate',

	'Next' => 'Next',
	'Finished' => 'Finished',
/* lang_admin_rebuild_search.php - END */

/* lang_admin_faq_editor.php - BEGIN */
	'faq_editor' => 'Edit Language',
	'faq_editor_explain' => '<b>Edit and re-arrange your FAQ, BBCode FAQ or Board Rules.</b><br /><br /> You <u>should not</u> remove or alter the section entitled <b>phpBB 2 Issues</b> or <b>About Icy Phoenix</b>.',

	'faq_select_language' => 'Choose the language file you want to edit',
	'faq_retrieve' => 'Retrieve File',

	'faq_block_delete' => 'Are you sure you want to delete this block?',
	'faq_quest_delete' => 'Are you sure you want to delete this question (and its answer)?',

	'faq_quest_edit' => 'Edit Question &amp; Answer',
	'faq_quest_create' => 'Create New Question &amp; Answer',

	'faq_quest_edit_explain' => 'Edit the question and answer. Change the block if you wish.',
	'faq_quest_create_explain' => 'Type the new question and answer and press Submit.',

	'faq_block' => 'Block',
	'faq_quest' => 'Question',
	'faq_answer' => 'Answer',

	'faq_block_name' => 'Block Name',
	'faq_block_rename' => 'Rename a block',
	'faq_block_rename_explain' => 'Change the name of a block in the file',

	'faq_block_add' => 'Add Block',
	'faq_quest_add' => 'Add Question',

	'faq_no_quests' => 'No questions in this block. This will prevent any blocks after this one being displayed. Delete the block or add one or more questions.',
	'faq_no_blocks' => 'No blocks defined. Add a new block by typing a name below.',

	'faq_write_file' => 'Could not write to the language file!',
	'faq_write_file_explain' => 'You must make the language file in language/lang_english/ or equivalent <i>writable</i> to use this control panel. On UNIX, this means running <code>chmod 666 filename</code>. Most FTP clients can do this through the properties sheet of a file, otherwise you can use telnet or SSH.',
/* lang_admin_faq_editor.php - END */

/* lang_admin_rules_editor.php - BEGIN */
	'rules_editor' => 'Edit Language',
	'rules_editor_explain' => 'Edit and re-arrange your Board rules. ',

	'rules_select_language' => 'Choose the language of the file you want to edit',
	'rules_retrieve' => 'Retrieve File',

	'rules_block_delete' => 'Are you sure you want to delete this block?',
	'rules_quest_delete' => 'Are you sure you want to delete this question (and its answer)?',

	'rules_quest_edit' => 'Edit Question &amp; Answer',
	'rules_quest_create' => 'Create New Question &amp; Answer',

	'rules_quest_edit_explain' => 'Edit the question and answer. Change the block if you wish.',
	'rules_quest_create_explain' => 'Type the new question and answer and press Submit.',

	'rules_block' => 'Block',
	'rules_quest' => 'Question',
	'rules_answer' => 'Answer',

	'rules_block_name' => 'Block Name',
	'rules_block_rename' => 'Rename a block',
	'rules_block_rename_explain' => 'Change the name of a block in the file',

	'rules_block_add' => 'Add Block',
	'rules_quest_add' => 'Add Question',

	'rules_no_quests' => 'No questions in this block. This will prevent any blocks after this one being displayed. Delete the block or add one or more questions.',
	'rules_no_blocks' => 'No blocks defined. Add a new block by typing a name below.',

	'rules_write_file' => 'Could not write to the language file!',
	'rules_write_file_explain' => 'You must make the language file in language/lang_english/ or equivalent <i>writable</i> to use this control panel. On UNIX, this means running <code>chmod 666 filename</code>. Most FTP clients can do this through the properties sheet of a file, otherwise you can use telnet or SSH.',
/* lang_admin_rules_editor.php - END */

/* lang_admin_priv_msgs.php - BEGIN */
	'PM_View_Type' => 'PM View Type',
	'Show_IP' => 'Show IP Address',
	'Rows_Per_Page' => 'Rows Per Page',
	'Archive_Feature' => 'Archive Feature',
	'Inline' => 'Inline',
	'Pop_up' => 'Pop-up',
	'Current' => 'Current',
	'Rows_Plus_5' => 'Add 5 Rows',
	'Rows_Minus_5' => 'Remove 5 Rows',
	'Enable' => 'Enable',
	'Disable' => 'Disable',
	'Inserted_Default_Value' => '%s Configuration Item did not exist, inserted a default value<br />', // %s = config name
	'Updated_Config' => 'Updated Configuration Item %s<br />', // %s = config item
	'Archive_Table_Inserted' => 'Archive Table did not exist, created it<br />',
	'Switch_Normal' => 'Switch To Normal Mode',
	'Switch_Archive' => 'Switch To Archive Mode',

/* General */
	'Deleted_Message' => 'Deleted Private Message - %s <br />', // %s = PM title
	'Archived_Message' => 'Archived Private Message - %s <br />', // %s = PM title
	'Archived_Message_No_Delete' => 'Cannot Delete %s, It Was Marked For Archive As Well <br />', // %s = PM title
	'Private_Messages' => 'Private Messages',
	'Private_Messages_Archive' => 'Private Messages Archive',
	'Archive' => 'Archive',
	'To' => 'To',
	'Subject' => 'Subject',
	'Sent_Date' => 'Sent Date',
	'From' => 'From',
	'Sort' => 'Sort',
	'Filter_By' => 'Filter By',
	'PM_Type' => 'PM Type',
	'Status' => 'Status',
	'No_PMS' => 'No Private Messages Matching Your Sort Criteria To Display',
	'Archive_Desc' => 'Private Messages you have chosen to archive are listed here. Users are no longer able to access these (sender and receiver), but you can view or delete them at any time.',
	'Normal_Desc' => 'All Private Messages on your board may be managed here. You can read any you\'d like and choose to delete or archive (keep, but users cannot view) the messages as well.',
	'Remove_Old' => 'Orphan PMs:</a> <span class="gensmall">Users who no longer exist could have left PMs behind, this will remove them.</span>',
	'Remove_Sent' => 'Sent Box PMs:</a> <span class="gensmall">PMs in the sent box are just copies of the exact same message that was sent, except assigned to the sender after the other user has read the PM. These are not generally needed.</span>',
	'Removed_Old' => 'Removed All Orphan PMs<br />',
	'Removed_Sent' => 'Removed All Sent PMs<br />',
	'Utilities' => 'Mass Deletion Utilities',
	'Nivisec_Com' => 'Nivisec.com',

/* PM Types */
	'PM_-1' => 'All Types', //PRIVMSGS_ALL_MAIL = -1
	'PM_0' => 'Read PMs', //PRIVMSGS_READ_MAIL = 0
	'PM_1' => 'New PMs', //PRIVMSGS_NEW_MAIL = 1
	'PM_2' => 'Sent PMs', //PRIVMSGS_SENT_MAIL = 2
	'PM_3' => 'Saved PMs (In)', //PRIVMSGS_SAVED_IN_MAIL = 3
	'PM_4' => 'Saved PMs (Out)', //PRIVMSGS_SAVED_OUT_MAIL = 4
	'PM_5' => 'Unread PMs', //PRIVMSGS_UNREAD_MAIL = 5

/* Errors */
	'Error_Other_Table' => 'Error querying a required table.',
	'Error_PM_Text_Table' => 'Error querying Private Messages Text table.',
	'Error_PM_Table' => 'Error querying Private Messages table.',
	'Error_PM_Archive_Table' => 'Error querying Private Messages Archive table.',
	'No_Message_ID' => 'No message ID was specified.',
/* lang_admin_priv_msgs.php - END */

/* lang_admin_link.php - BEGIN */
// Categories
	'Link_Categories_Title' => 'Link Categories Control',
	'Link_Categories_Explain' => '<b>Manage your categories:</b><br /><br /> Create, alter, delete or sort, etc.',
	'Category_Permissions' => 'Category Permissions',
	'Category_Title' => 'Category Title',
	'Category_Desc' => 'Category Description',
	'View_level' => 'View Level',
	'Upload_level' => 'Upload Level',
	'Rate_level' => 'Rate Level',
	'Comment_level' => 'Comment Level',
	'Edit_level' => ' Edit Level',
	'Delete_level' => 'Delete Level',
	'New_category_created' => 'New category has been created successfully',
	'Click_return_link_category' => 'Click %sHere%s to return to the Link Categories Manager',
	'Category_updated' => 'This category has been updated successfully',
	'Delete_Category' => 'Delete Category',
	'Delete_Category_Explain' => 'Delete this category?',
	'Category_deleted' => 'The category has been deleted successfully',
	'Category_changed_order' => 'The category order has been changed successfully',

// Config
	'Link_Config' => 'Link Config Control',
	'Link_config_explain' => 'Change the general settings of your link here',
	'lock_submit_site' => 'Lock user submit site',
	'allow_guest_submit_site' => 'Allow guest(s) to submit site',
	'allow_no_logo' => 'Allow submit site without a banner',
	'site_logo' => 'The url where your logo can be found (full url)',
	'site_url' => 'The url of your website',
	'width' => 'Max banner width',
	'height' => 'Max banner height',
	'linkspp' => 'Max links per page',
	'interval' => 'How fast the banners are displayed',
	'display_logo' => 'How many banners are displayed at once',
	'Link_display_links_logo' => 'Display Links site banner',
	'Link_email_notify' => 'While Link added, send an e-mail to all site admins',
	'Link_pm_notify' => 'While Link added, notify all site admins in a private message',
	'Link_config_updated' => 'Links configuration has been updated successfully',
	'Click_return_link_config' => 'Click %sHere%s to return to the Link Config Manager',

// Link_MOD
	'Links' => 'Links Management',
	'Links_explain' => 'Preview the status of, edit or remove selected links.',
	'Add_link' => 'Add Link',
	'Add_link_explain' => 'Add a new link.',
	'Edit_link' => 'Edit Link',
	'Edit_link_explain' => 'Edit this link\'s details. You can also choose to ',
	'Delete_link' => 'Delete Link',
	'Delete_link_explain' => 'Delete this link. You can also choose to ',
	'Link_update' => 'Update link detail',
	'Link_delete' => 'Delete this link',
	'Link_title' => 'Site Name',
	'Link_url' => 'Site URL',
	'Link_logo_src' => 'Site Logo (88x31 pixels, file-size no more than 10K)',
	'Link_category' => 'Site Category',
	'Link_desc' => 'Site Description',
	'link_hits' => 'Hits',
	'Link_basic_setting' => 'Link Basic Detail',
	'Link_adv_setting' => 'Advanced Setting',
	'Link_active' => 'Active Status',

	'Link_admin_add_success' => 'The link was successfully added',
	'Link_admin_add_fail' => 'Unable to add the new link, please try again later',
	'Link_admin_update_success' => 'The link was successfully updated',
	'Link_admin_update_fail' => 'Unable to update the link, please try again later',
	'Link_admin_delete_success' => 'The link was successfully removed',
	'Link_admin_delete_fail' => 'Unable to remove the link, please try again later',
	'Click_return_lastpage' => 'Click %sHere%s to return to the previous page',
	'Click_return_admin_links' => 'Click %sHere%s to return to Links Manage',
	'Preview' => 'Preview',
	'Search_site' => 'Search Site',
	'Search_site_title' => 'Search Site Name/Description:',
/* lang_admin_link.php - END */

/* lang_.php - BEGIN */
/* lang_.php - END */

// Icy Phoenix - BUILD 009
	'Replace_title' => 'Replace In Posts',
	'Replace_text' => 'Replace words or lines with whatever you wish. <br /><b>Note!</b> This cannot be undone.',
	'Link' => 'Link',
	'Str_old' => 'Current text',
	'Str_new' => 'Replace with',
	'No_results' => 'No results found',
	'Replaced_count' => 'Total posts updated: %s',

// Icy Phoenix - BUILD 016
	'group_rank' => 'Rank',
	'group_color' => 'Colour',
	'group_legend' => 'Show in legend',
	'group_legend_short' => 'Legend',
	'group_main' => 'Main group',
	'group_members' => 'Members',
	'group_update' => 'Apply Changes',

/* lang_color_groups.php - BEGIN */
	'Color_Groups' => 'Colour Groups',
	'Manage_Color_Groups' => 'Manage Colour Groups',
	'Add_New_Group' => 'Add New Group',
	'Color' => 'Colour',
	'User_Count' => 'User Count',
	'Color_List' => 'Colour Name List:',
	'Group_Name' => 'Group Name',
	'Define_Users' => 'Define Users',
	'Color_Group_User_List' => 'Colour Group User List',
	'Options' => 'Options',
	'Example' => 'Example',
	'User_List' => 'Full User List',
	'Unassigned_User_List' => 'Users With No Group',
	'Assigned_User_List' => 'Users With A Group',
	'Add_Arrow' => 'Add To List',
	'Update' => 'Update',
	'Updated_Group' => 'Updated Group User List<br />',
	'Deleted_Group' => 'Deleted Specified Group. All users that were in this group have been reset to no group membership<br />',
	'Hide' => 'Hide',
	'Un-hide' => 'Un-hide',
	'Group_Hidden' => 'Group Hidden<br />',
	'Group_Unhidden' => 'Group Visible<br />',
	'Groups_Updated' => 'Group changes have been updated<br />',
	'Moved_Group' => 'Moved group order<br />',

//Descriptions
	'Manage_Color_Groups_Desc' => 'Update or add a new group, or manage the users assigned to a particular colour group.<br />Groups that you choose to "Hide" will not show up on the main index list.',
	'Color_Group_User_List_Desc' => 'Add or remove users to a specified colour group.',

//Errors
	'Error_Group_Table' => 'Error querying the colour groups table.',
	'Error_Font_Color' => '<b><u>Warning:</b></u> The specified font colour appears to be invalid!',
	'Color_Ok' => 'The specified font colour appears to be valid.',
	'No_Groups_Exist' => 'No groups exist.',
	'Error_Users_Table' => 'Error querying the users table.',
	'Invalid_Group_Add' => '%s is an invalid or duplicate group name.<br />',

//Dynamic
	'Group_Updated' => 'Updated Colour Group %s<br />',
	'Editing_Group' => 'Currently editing the user list for %s.',
	'Invalid_User' => '%s is an invalid username, skipping<br />',
	'Invalid_Order_Num' => '%s contained an invalid order number, but it has been fixed. Please try your move up/down again.',

//New for 1.2.0
	'Users_List' => 'Users List',
	'Groups_List' => 'User Groups List',
	'List_Info' => '<b>Notes</b>: <ul><li>Hold CTRL when clicking to select multiple names. <li>If a user belongs to a user group, and is added to a specific colour group, the colour group that contains the user will be used; not the one the user group belongs to. <li>The list names are formatted as NAME (CURRENT_COLOR_GROUP). There will be no (CURRENT_COLOR_GROUP) if the entry doesn\'t belong to one. <li>If a user is a member of 2 or more user groups, the highest ranking colour group will be assigned (you order their appearance on the main page).</ul>',
/* lang_color_groups.php - END */

// Icy Phoenix - BUILD 023
	'Empty_Cache_Main_Question' => 'If you click yes, all files in main cache folder will be permanently deleted.<br /><br /><em> Are you sure you want to do this? </em>',
	'Empty_Cache_Posts_Question' => 'If you click yes, precompiled posts field in posts table will be permanently deleted.<br /><br /><em> Are you sure you want to do this? </em>',
	'Empty_Cache_Thumbs_Question' => 'If you click yes, all thumbnails generated in posts will be permanently deleted.<br /><br /><em> Are you sure you want to do this? </em>',
	'Empty_Cache_Success' => 'Cache folders emptied successfully.',

	'Copy_Auth' => 'Copy permissions from',
	'Copy_Auth_Explain' => 'Please note that you can copy permissions only from forums, not from categories!',

// Icy Phoenix - BUILD 027
/* lang_admin_db_backup.php - BEGIN */
	'SELECT_ALL' => 'Select all',
	'SELECT_FILE' => 'Select a file',
	'START_BACKUP' => 'Start backup',
	'START_RESTORE' => 'Start restore',
	'STORE_AND_DOWNLOAD' => 'Store and download',
	'STORE_LOCAL' => 'Store file locally',
	'STRUCTURE_ONLY' => 'Structure only',

// Backup
	'ACP_BACKUP' => 'Backup Database',
	'ACP_BACKUP_EXPLAIN' => 'Backup all your site related data. Backup will be stored <b><samp>backup/</samp></b> (make sure this folder is <b>writable</b>) folder so you can download or restore it from the <b>Restore</b> page. Your server configuration may also allow you to save the file in compressed gzip format.<br /><br /><span class="text_red">Backup will be performed on several steps to avoid timeouts: the script should be able to perform the full process all automatically, so you have just to wait it to complete the automated task.</span><br /><br />',

	'BACKUP_OPTIONS' => 'Backup Options',
	'BACKUP_TYPE' => 'Backup type',

	'DATABASE' => 'Database Utilities',
	'DATA_ONLY' => 'Data only',
	'DELETE_BACKUP' => 'Delete backup',
	'DELETE_SELECTED_BACKUP' => 'Are you sure you want to delete the selected backup?',
	'DESELECT_ALL' => 'Deselect all',
	'DOWNLOAD_BACKUP' => 'Download backup',

	'FILE_TYPE' => 'File type',
	'FULL_BACKUP' => 'Full',

	'BACKUP_TYPE_COMPLETE' => 'Complete',
	'BACKUP_TYPE_EXTENDED' => 'Extended',
	'BACKUP_TYPE_COMPACT' => 'Compact Line Breaks',

	'BACKUP_SUCCESS' => 'The backup file has been created successfully.',
	'BACKUP_DELETED' => 'The backup file has been deleted successfully.',

	'TABLE_SELECT' => 'Table select',

	'BACKUP_IN_PROGRESS' => 'Backup in progress...',
	'BACKUP_IN_PROGRESS_TABLE' => 'Backing up table: <b>%s</b>',
	'BACKUP_IN_PROGRESS_REDIRECT' => 'You will be automatically redirected to next step in few seconds',
	'BACKUP_IN_PROGRESS_REDIRECT_CLICK' => 'If you are not automatically redirected within few seconds you may click %sHere%s',
	'BACKUP_OPTIONS_RETURN' => 'Click %sHere%s to return to Backup Management',

// Errors
	'Table_Select_Error' => 'You must select at least one table.',

// Restore
	'ACP_RESTORE' => 'Restore Database',
	'ACP_RESTORE_EXPLAIN' => 'Restore of all your database tables from a saved backup file. If your server supports it you can use a gzip or bzip2 compressed text file and it will be automatically decompressed. <strong>WARNING</strong> This will overwrite any existing data. The restore may take a long time to process, please <b>do not</b> move from this page until it is complete. Backups are stored in the <b><samp>backup/</samp></b> folder, and are assumed to be generated by this site backup functions. Restoring backups that were not created by the built in system may not work properly.<br /><br /><strong class="text_red">Please note that if the DB to be restored is too big this script may time out and you could not be able to use the site again. In case this will happen, you could try to download the backup from FTP and then restore it using a different method such as phpMyAdmin or MySQLDumper.</strong><br /><br />',
	'RESTORE_OPTIONS' => 'Restore Options',

	'Restore_Success' => 'The database has been successfully restored.<br />Your site should be back to the state it was in when the backup was made.',

// Errors
	'No_Backup_Selected' => 'You haven\'t selected any backup, so you can\'t restore it.',
	'Backup_Invalid' => 'The selected file to backup is invalid.',
	'RESTORE_FAILURE' => 'The backup file may be corrupt.',
/* lang_admin_db_backup.php - END */

/* Logs - BEGIN */
	'LOGS_ACTIONS_FILTER' => 'Actions filter',
	'LOGS_TITLE' => 'Logs',
	'LOGS_EXPLAIN' => 'All relevant actions stored in the DB',
	'LOGS_TARGET' => 'Target',
	'LOGS_DELETE' => 'Delete Selected',
	'LOGS_DELETE_ALL' => 'Empty Logs Table',
	'LOGS_DENY' => 'Not authorized!',
	'LOGS_POST_EDIT' => 'edited a post posted by',
	'LOGS_POST_DELETE' => 'deleted a post posted by',
	'LOGS_GROUP_JOIN' => 'requested to join the group',
	'LOGS_GROUP_EDIT' => 'edited group options of %s',
	'LOGS_GROUP_ADD' => 'added %s to the group',
	'LOGS_GROUP_TYPE' => 'edited group %s status, now the group is %s',
	'LOGS_GROUP_TYPE_0' => 'open',
	'LOGS_GROUP_TYPE_1' => 'closed',
	'LOGS_GROUP_TYPE_2' => 'hidden',
	'LOGS_MESSAGE' => 'message to the user, code <b>%s</b>',
	'LOGS_MODCP_DELETE' => 'deleted some messages in %s through MODCP',
	'LOGS_MODCP_RECYCLE' => 'trashed some messages in %s through MODCP',
	'LOGS_MODCP_LOCK' => 'locked some messages in %s through MODCP',
	'LOGS_MODCP_UNLOCK' => 'unlocked some messages in %s through MODCP',
	'LOGS_MODCP_MOVE' => 'moved some messages in %s through MODCP',
	'LOGS_MODCP_MERGE' => 'merged some messages in %s through MODCP',
	'LOGS_MODCP_SPLIT' => 'splitted some messages in %s through MODCP',
	'LOGS_TOPIC_BIN' => 'trashed a message in',
	'LOGS_TOPIC_ATTACK' => 'hacking attempt to message',
	'LOGS_CARD_BAN' => 'banned',
	'LOGS_CARD_WARN' => 'warned',
	'LOGS_CARD_UNBAN' => 'unbanned',
	'LOGS_ADMIN_CAT_ADD' => 'added a forum',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP' => 'DB backup %s',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP_full' => 'full',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP_structure' => 'structure only',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP_data' => 'data',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP_store_and_download' => ', downloaded and stored',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP_store' => ', stored',
	'LOGS_ADMIN_DB_UTILITIES_BACKUP_download' => ', downloaded',
	'LOGS_ADMIN_DB_UTILITIES_RESTORE' => 'DB restored from',
	'LOGS_ADMIN_BOARD_CONFIG' => 'edited config settings',
	'LOGS_ADMIN_BOARD_IP_CONFIG' => 'edited Icy Phoenix settings',
	'LOGS_ADMIN_GROUP_NEW' => 'group created',
	'LOGS_ADMIN_GROUP_DELETE' => 'group deleted',
	'LOGS_ADMIN_GROUP_EDIT' => 'group edited',
	'LOGS_ADMIN_USER_AUTH' => 'edited permissions of',
	'LOGS_ADMIN_GROUP_AUTH' => 'edited group permissions',
	'LOGS_ADMIN_USER_BAN' => 'banned someone from ACP',
	'LOGS_ADMIN_USER_UNBAN' => 'unbanned someone from ACP',
	'LOGS_ADMIN_USER_DELETE' => 'user deleted',
	'LOGS_ADMIN_USER_EDIT' => 'profile edited of',
	'LOGS_CMS_LAYOUT_EDIT' => 'edited %sTHIS%s page',
	'LOGS_CMS_LAYOUT_DELETE' => 'deleted a page [ID = %s]',
	'LOGS_CMS_BLOCK_EDIT' => 'edited a block [ID = %s] in %sTHIS%s page',
	'LOGS_CMS_BLOCK_EDIT_LS' => 'edited a block [ID = %s] in a standard page [%s]',
	'LOGS_CMS_BLOCK_DELETE' => 'deleted a block [ID = %s] in %sTHIS%s page',
	'LOGS_CMS_BLOCK_DELETE_LS' => 'deleted a block [ID = %s] in a standard page [%s]',
/* Logs - END */

	'SMILEYS_UPDATED' => 'Smileys Updated',

/* ADS - BEGIN */
	'ADS_TITLE' => 'Ads &amp; Sponsors',
	'ADS_TITLE_EXPLAIN' => 'This section allows you to configure banners, ads and sponsors to be shown on your site. You can add different types of banners and decide where these banners have to be shown or which level of users won\'t see them. If you specify more than one banner for a single position, then one banner will be shown randomly among all of those specified for the same position.',
	'AD_DES' => 'Description',
	'AD_TEXT' => 'Content',
	'AD_ENABLED' => 'Enabled',
	'AD_STATUS' => 'Status',
	'AD_STATUS_EXPLAIN' => 'Select YES if you want to enable this ad or NO if you want to disable it',
	'AD_POSITION' => 'Position',
	'AD_AUTH' => 'Permission',
	'AD_AUTH_EXPLAIN' => 'Users who will see this ad',
	'AD_AUTH_GUESTS' => 'Guests only',
	'AD_AUTH_REG' => 'Guests and Registered (not ADMINS and MODS)',
	'AD_AUTH_MOD' => 'All but Administrators',
	'AD_AUTH_ADMIN' => 'All',
	'AD_FORMAT' => 'Format',
	'AD_POS_GLT' => 'Global Top',
	'AD_POS_GLB' => 'Global Bottom',
	'AD_POS_GLH' => 'Global Header',
	'AD_POS_GLF' => 'Global Footer',
	'AD_POS_FIX' => 'Forum Index Element',
	'AD_POS_FIT' => 'Forum Index Top',
	'AD_POS_FIB' => 'Forum Index Bottom',
	'AD_POS_VFX' => 'View Forum Element',
	'AD_POS_VFT' => 'View Forum Top',
	'AD_POS_VFB' => 'View Forum Bottom',
	'AD_POS_VTX' => 'View Topic Element',
	'AD_POS_VTT' => 'View Topic Top',
	'AD_POS_VTB' => 'View Topic Bottom',
	'AD_POS_NMT' => 'Nav Menu Top',
	'AD_POS_NMB' => 'Nav Menu Bottom',
	'AD_ADD' => 'Add Ad',
	'AD_EDIT' => 'Edit Ad',
	'AD_ADDED' => 'Ad added successfully',
	'ADS_UPDATE' => 'Update Ads',
	'AD_UPDATED' => 'Ad updated successfully',
	'AD_DELETED' => 'Ad deleted successfully',
	'CLICK_RETURN_ADS' => 'Click %sHere%s to return to Ads administration',
	'AD_NO_ADS' => 'No ads defined',
	'ERR_AD_ADD' => 'Please fill all required fields',
/* ADS - END */

	'FULL_HTML' => 'Full HTML',
	'ACTIONS' => 'Actions',
	'EDIT' => 'Edit',
	'DELETE' => 'Delete',

	// Tickets Submission - BEGIN
	'TICKETS_EMAILS' => 'Email Tickets',
	'TICKETS_EMAILS_EXPLAIN' => 'This section allows you to specify several categories which could be chosen in "Contact Us" page when sending emails. For each category one or more email address can be specified, so the user will be allowed to choose a specific subject and the email will be sent to the linked email addresses.',
	'TICKET_CAT' => 'Category',
	'TICKET_CAT_TITLE' => 'Title',
	'TICKET_CAT_DES' => 'Description',
	'TICKET_CAT_EMAILS' => 'Email Addresses',
	'TICKET_CAT_EMAILS_EXPLAIN' => 'Insert here all email addresses you want the email to be sent.<br />Separate all addresses by semicolon (a@a.com;b@b.com;c@c.com).',
	'TICKETS_NO_TICKETS' => 'No Tickets',
	'TICKETS_NO_TICKET_SEL' => 'No ticket selected',
	'TICKETS_NO_TICKET_TITLE' => 'You have to enter at least title field',
	'TICKETS_DB_ADD' => 'Add Category',
	'TICKETS_DB_ADDED' => 'Category Added Successfully',
	'TICKETS_DB_UPDATED' => 'Category Updated Successfully',
	'TICKETS_DB_DELETED' => 'Category Deleted Successfully',
	'TICKETS_DB_CLICK' => 'Click %sHere%s to return to Email Tickets',
	// Tickets Submission - END

	'FORUM_LIMIT_EDIT_TIME' => 'Limit User Post Edit Time',
	'FORUM_LIMIT_EDIT_TIME_EXPLAIN' => 'By enabling this option users will be allowed to edit own messages only within the limit set in Posts configuration of Icy Phoenix (ACP &raquo; Configuration &raquo; Main Settings &raquo; Posting And Messages)',

	// Custom BBCodes - BEGIN
	'BBCODES_CUSTOM_BBCODES' => 'Custom BBCodes',
	'BBCODES_CUSTOM_BBCODES_EXPLAIN' => 'BBCode is a special implementation of HTML offering greater control over what and how something is displayed. From this page you can add, remove and edit custom BBCodes. To be able to use these Custom BBCodes you need to enable <b>Enable Custom BBCodes</b> in <b>ACP &raquo; Configuration &raquo; Main Settings &raquo; Posting And Messages</b> section.',
	'BBCODES_NO_BBCODES' => 'No BBCodes',
	'BBCODES_NO_BBCODES_SEL' => 'No BBCode selected',
	'BBCODES_NO_BBCODES_INPUT' => 'You have to fill the BBCode tag',
	'BBCODES_DB_ADD' => 'Add BBCode',
	'BBCODES_DB_ADDED' => 'BBCode Added Successfully',
	'BBCODES_DB_UPDATED' => 'BBCode Updated Successfully',
	'BBCODES_DB_DELETED' => 'BBCode Deleted Successfully',
	'BBCODES_DB_CLICK' => 'Click %sHere%s to return to Custom BBCodes',

	'BBCODE_ADDED' => 'BBCode added successfully.',
	'BBCODE_EDITED' => 'BBCode edited successfully.',
	'BBCODE_NOT_EXIST' => 'The BBCode you selected does not exist.',
	'BBCODE_HELPLINE' => 'Help line',
	'BBCODE_HELPLINE_EXPLAIN' => 'This field contains the mouse over text of the BBCode.',
	'BBCODE_HELPLINE_TEXT' => 'Help line text',
	'BBCODE_HELPLINE_TOO_LONG' => 'The help line you entered is too long.',

	'BBCODE_INVALID_TAG_NAME' => 'The BBCode tag name that you selected already exists.',
	'BBCODE_INVALID' => 'Your BBCode is constructed in an invalid form.',
	'BBCODE_OPEN_ENDED_TAG' => 'Your custom BBCode must contain both an opening and a closing tag.',
	'BBCODE_TAG' => 'Tag',
	'BBCODE_TAG_TOO_LONG' => 'The tag name you selected is too long.',
	'BBCODE_TAG_DEF_TOO_LONG' => 'The tag definition that you have entered is too long, please shorten your tag definition.',
	'BBCODE_USAGE' => 'BBCode usage',
	'BBCODE_USAGE_EXAMPLE' => '[highlight={COLOR}]{TEXT}[/highlight]<br /><br />[font={SIMPLETEXT1}]{SIMPLETEXT2}[/font]',
	'BBCODE_USAGE_EXPLAIN' => 'Here you define how to use the BBCode. Replace any variable input by the corresponding token (%ssee below%s).',

	'EXAMPLE' => 'Example:',
	'EXAMPLES' => 'Examples:',

	'HTML_REPLACEMENT' => 'HTML replacement',
	'HTML_REPLACEMENT_EXAMPLE' => '&lt;span style=&quot;background-color: {COLOR};&quot;&gt;{TEXT}&lt;/span&gt;<br /><br />&lt;span style=&quot;font-family: {SIMPLETEXT1};&quot;&gt;{SIMPLETEXT2}&lt;/span&gt;',
	'HTML_REPLACEMENT_EXPLAIN' => 'Here you define the default HTML replacement. Do not forget to put back tokens you used above!',

	'TOKEN' => 'Token',
	'TOKENS' => 'Tokens',
	'TOKENS_EXPLAIN' => 'Tokens are placeholders for user input. The input will be validated only if it matches the corresponding definition. If needed, you can number them by adding a number as the last character between the braces, e.g. {TEXT1}, {TEXT2}.<br /><br />Within the HTML replacement you can also use any language string present in your language/ directory like this: {L_<em>&lt;STRINGNAME&gt;</em>} where <em>&lt;STRINGNAME&gt;</em> is the name of the translated string you want to add. For example, {L_WROTE} will be displayed as &quot;wrote&quot; or its translation according to user’s locale.<br /><br /><strong>Please note that only tokens listed below are able to be used within custom BBCodes.</strong>',
	'TOKEN_DEFINITION' => 'What can it be?',
	'TOO_MANY_BBCODES' => 'You cannot create any more BBCodes. Please remove one or more BBCodes then try again.',

	'BBCODES_TOKENS_DESCRIPTION' => '
<b>TEXT</b> &raquo; Any text, including foreign characters, numbers, etc... You should not use this token in HTML tags. Instead try to use IDENTIFIER or SIMPLETEXT.<br />
<b>SIMPLETEXT</b> &raquo; Characters from the latin alphabet (A-Z), numbers, spaces, commas, dots, minus, plus, hyphen and underscore<br />
<b>IDENTIFIER</b> &raquo; Characters from the latin alphabet (A-Z), numbers, hyphen and underscore<br />
<b>NUMBER</b> &raquo; Any series of digits<br />
<b>EMAIL</b> &raquo; A valid e-mail address<br />
<b>URL</b> &raquo; A valid URL using any protocol (http, ftp, etc... cannot be used for javascript exploits). If none is given, &quot;http://&quot; is prefixed to the string.<br />
<b>LOCAL_URL</b> &raquo; A local URL. The URL must be relative to the topic page and cannot contain a server name or protocol.<br />
<b>COLOR</b> &raquo; A HTML colour, can be either in the numeric form <samp>#ff1234</samp> or a <a href="http://www.w3.org/TR/CSS21/syndata.html#value-def-color">CSS colour keyword</a> such as <samp>fuchsia</samp> or <samp>InactiveBorder</samp>',
	// Custom BBCodes - END

	// PLUGINS - BEGIN
	'PLUGINS' => 'Plugins',
	'PLUGINS_EXPLAIN' => 'In this section you can enable or disable Icy Phoenix Plugins',
	'PLUGINS_FOLDER' => 'Folder',
	'PLUGINS_NAME' => 'Name',
	'PLUGINS_DESCRIPTION' => 'Description',
	'PLUGINS_VERSION' => 'Version',
	'PLUGINS_CURRENT_VERSION' => 'Installed version',
	'PLUGINS_LAST_VERSION' => 'Latest version',
	'PLUGINS_INSTALL' => 'Install',
	'PLUGINS_UPGRADE' => 'Upgrade',
	'PLUGINS_UNINSTALL' => 'Uninstall',
	'PLUGINS_UP_TO_DATE' => 'Up to date',
	'PLUGINS_NOT_INSTALLED' => 'Not installed',
	'PLUGINS_OUTDATED' => 'Outdated',
	'PLUGINS_UPDATE_CONFIG' => 'Update Configuration',
	'PLUGINS_CONFIG_UPDATED' => 'Plugins configuration updated successfully. Please note that you need to reload ACP (F5) to update modules.',
	'PLUGINS_RETURN_CLICK' => 'Click %sHere%s to return to Plugins',
	'PLUGINS_NO_PLUGINS' => 'No Plugins to be configured',
	// PLUGINS - END

	'BBCODE_SETTINGS' => 'BBCode, HTML And Smileys Settings',
	'POSTING_SETTINGS' => 'Posting Settings',

	'POLL_INFINITE' => 'Infinite...',
	'POLL_ONGOING' => ' (ongoing)',
	'POLL_COMPLETED' => ' (completed)',

	'FORUM_LIKES' => 'Like Posts',
	'FORUM_LIKES_EXPLAIN' => 'Allow users to like single posts in a topic (you need to enable the global switch in Icy Phoenix Settings [SQL Charge] to be able to use this feature)',

	'POSTS_PICS' => 'Posts / Pics',

	'INACTIVE_USER_FEATURE' => 'Mask this user',
	'INACTIVE_USER_FEATURE_EXPLAIN' => 'By enabling this switch, the user will be masked in forums and topics. User profile details will be replaced by anonymous data. User will be masked only if its account is not active.',

	'RANK_SHOW_TITLE' => 'Display rank title',
	'RANK_SHOW_TITLE_EXPLAIN' => 'By disabling this option only rank image will be shown',

	'AJAX_CHAT_MSGS_REFRESH' => 'Refresh Time Interval',
	'AJAX_CHAT_MSGS_REFRESH_EXPLAIN' => 'Please enter the time interval in seconds for the system to check for new chat messages. This value should not be below 2 seconds (it will be forced to 1 second if a lower value is set).',
	'AJAX_CHAT_SESSION_REFRESH' => 'Session Length',
	'AJAX_CHAT_SESSION_REFRESH_EXPLAIN' => 'Please enter the chat session duration. This value is used to check if users in chat left the conversation. This value should not be below 10 seconds (this value will be forced to be 5 seconds if a lower value is set).',
	'AJAX_CHAT_LINK_TYPE' => 'Chat Window Format',
	'AJAX_CHAT_LINK_TYPE_EXPLAIN' => 'Please specify if you want a simplified chat window or a window with full header and footer (simplified is cleaner and faster).',
	'AJAX_CHAT_LINK_TYPE_SIMPLE' => 'Simplified',
	'AJAX_CHAT_LINK_TYPE_FULL' => 'Full',
	'AJAX_CHAT_NOTIFICATION' => 'Private Chat Notification',
	'AJAX_CHAT_NOTIFICATION_EXPLAIN' => 'If you enable this option, a notification will be sent to the user in case of Private Chat Request.',
	'AJAX_CHAT_CHECK_ONLINE' => 'Private Chat Link Only For Online Users',
	'AJAX_CHAT_CHECK_ONLINE_EXPLAIN' => 'If you enable this option, the Private Chat link will be displayed only for users already in chat, otherwise it will be displayed for all users online in the site.',

	'FORUMS_SUBMIT_AUTH' => 'Update Permissions',
	'FORUMS_SUBMIT_CFG' => 'Update Settings',
	'FORUMS_SELECTION_MULTIPLE' => 'You can select more than one forum by clicking CTRL.',

	'FAILED_LOGINS_COUNTER' => 'Failed Logins Counter',

	'ACP_USER_POSTS_EXPORT_TITLE' => 'Posts Export',
	'ACP_USER_POSTS_EXPORT_EXPLAIN' => 'This tool can be used to export all posts for one or more users',
	'UPE_TITLE' => 'Posts Export',
	'UPE_USER_IDS' => 'User IDS',
	'UPE_USER_IDS_EXPLAIN' => 'Please use comma to separate users in the list (i.e.: 1,2,3,5,7)',
	'UPE_USER_IDS_JQ' => 'Search User',
	'UPE_USER_IDS_JQ_EXPLAIN' => 'Ajax search for users, start typing username and a list of available usernames will be returned on left column below. You can drag and drop all the users you need into the second column.',
	'UPE_USER_IDS_JQ_LIST' => 'Userlist',
	'UPE_USER_IDS_JQ_LIST_EXPLAIN' => 'Start typing username in the SEARCH USER box, then drag and drop from the left column to the right column the users you want to export their posts. The left column contains results of the AJAX search, while the right column contains the all users selected for posts exporting.',
	'UPE_PROCESSING' => 'Export in progress... please do not close the browser!',
	'UPE_PROCESSING_CURRENT_LOOP' => 'Processing:',
	'UPE_NO_USER' => 'No User',
	'UPE_EXPORT_COMPLETE' => 'Export Complete!',
	'UPE_LIMIT' => 'Posts Limit Per Cycle',
	'UPE_LIMIT_EXPLAIN' => 'Maximum amount of posts to be exported per cycle',
	'UPE_POSTS_TYPE' => 'Posts type',
	'UPE_POSTS_TYPE_EXPLAIN' => 'Select if exporting all posts or only first post for all topics started',
	'UPE_POSTS_TYPE_TOPICS' => 'Topics Only',
	'UPE_POSTS_TYPE_ALL' => 'All Posts',
	'UPE_IN_PROGRESS_REDIRECT' => 'You will be automatically redirected to next step in three seconds',
	'UPE_IN_PROGRESS_REDIRECT_CLICK' => 'If you are not automatically redirected within three seconds you may click %sHere%s',
	'UPE_COMPLETE_REDIRECT_CLICK' => 'Click %sHere%s to return to Posts Export',
	'UPE_FILES_LIST' => 'Posts Export - Archive',
	'UPE_FILES_LIST_SELECT' => 'Select a file',
	'UPE_FILES_LIST_SELECT_EXPLAIN' => 'You can select one file and download or delete it',
	'UPE_FILES_LIST_DOWNLOAD' => 'Download',
	'UPE_FILES_LIST_DELETE' => 'Delete',
	'UPE_FILES_LIST_DELETE_SELECTED' => 'Are you sure you want to delete the selected file?',
	'UPE_FILES_LIST_DELETED' => 'The selected file has been deleted succesfully',
	'UPE_FILES_LIST_INVALID' => 'File not valid',


// ####################### [ Icy Phoenix Options BEGIN ] #####################
	'IP_CONFIGURATION' => 'Icy Phoenix Settings',
	'IP_CONFIGURATION_EXPLAIN' => '<em><b>Advanced Icy Phoenix Settings</b></em>',

	'MG_SW_Precompiled_Posts_Title' => 'Precompiled Posts',
	'MG_SW_Logins_Title' => 'Logins Recording',
	'MG_SW_Edit_Notes_Title' => 'Edit Notes',

	'MG_SW_Header_Footer' => 'Header Table Message',
	'MG_SW_Header_Table' => 'Header Table',
	'MG_SW_Header_Table_Explain' => 'Enabling this option shows a customised message in the header table of each page.',
	'MG_SW_Header_Table_Text' => 'Insert your text here.',

	'MG_SW_Empty_Precompiled_Posts' => 'Empty precompiled posts',
	'MG_SW_Empty_Precompiled_Posts_Explain' => 'Empty all precompiled posts.',
	'MG_SW_Empty_Precompiled_Posts_Success' => 'Precompiled posts emptied correctly.',
	'MG_SW_Empty_Precompiled_Posts_Fail' => 'Errors in emptying precompiled posts.',
	'MG_SW_Empty_Precompiled_Posts_InProgress' => 'Emptying cache folders in progress...',
	'MG_SW_Empty_Precompiled_Posts_InProgress_Redirect' => 'You will be automatically redirected to next step in three seconds',
	'MG_SW_Empty_Precompiled_Posts_InProgress_Redirect_Click' => 'If you are not automatically redirected within three seconds you may click %sHere%s',
	'MG_SW_Empty_Precompiled_Posts_Redirect_Click' => 'Click %sHere%s to return to Cache Management',

	'MG_FNF_Header' => 'Quick Settings',
	'MG_FNF_Header_Explain' => '<b>Configuration options for your site.</b><br /> These configuration packages have been created to easily allow users to mass change their settings without having to modify each option one by one in the configuration panel, and may be used as a starting point for future customizations: For example you can choose "Fast And Furious" and then modify only the options of this package that you don\'t want.<br /><br /><span class="text_red"><b>Please note! that once you have applied one of these set of options you cannot automatically restore your old settings, and you have to set them up again manually.</b></span>',
	'MG_FNF_Options_Set' => 'Set Of Options',
	'MG_FNF_FNF' => 'Fast And Furious',
	'MG_FNF_FNF_Explain' => 'This set of options will increase the speed of your site, because most of the features which requires high CPU charge or DB access will be disabled. This package is ideal for wanting a very fast site.',
	'MG_FNF_MGS' => 'Mighty Gorgon\'s Suggested',
	'MG_FNF_MGS_Explain' => 'This set of options is balanced and a good starting point for most sites. Some options will be enabled while some others which requires high CPU loads will be switched off.',
	'MG_FNF_Full_Features' => 'Full Features',
	'MG_FNF_Full_Features_Explain' => 'This set of options could be enabled if you don\'t have bandwidth limit or if you like having all Icy Phoenix features enabled. Please note! that some of the features may not be compatible with your server.',

	'MG_SW_ACRONYMS' => 'Disable acronyms',
	'MG_SW_ACRONYMS_Explain' => 'Disable acronyms parsing?',
	'MG_SW_AUTOLINKS' => 'Disable autolinks',
	'MG_SW_AUTOLINKS_Explain' => 'Disable autolinks parsing?',
	'MG_SW_CENSOR' => 'Disable word censor',
	'MG_SW_CENSOR_Explain' => 'Disable word censor parsing?',

	'MG_SW_No_Right_Click' => 'Block Right Click',

	'Click_return_config_mg' => 'Click %sHere%s to return to Icy Phoenix Settings',
// ####################### [ Icy Phoenix Options END ] #######################
	)
);

/* Special Cases, Do not bother to change for another language */
$lang['ASC'] = $lang['Sort_Ascending'];
$lang['DESC'] = $lang['Sort_Descending'];
$lang['privmsgs_date'] = $lang['Sent_Date'];
$lang['privmsgs_subject'] = $lang['Subject'];
$lang['privmsgs_from_userid'] = $lang['From'];
$lang['privmsgs_to_userid'] = $lang['To'];
$lang['privmsgs_type'] = $lang['PM_Type'];

// ####################### [ Icy Phoenix Navigation BEGIN ] #######################
// Use numbers to sort the ACP Navigation menu
// Numbers have to be changed in all /adm/*.php files too

// Configuration
$lang['1000_Configuration'] = 'Configuration'; // admin_board.php, admin_config_settings.php, admin_bots.php, admin_captcha_config.php, admin_upi2db.php, admin_ctracker.php
$lang['100_Main_Settings'] = 'Main Settings'; // admin_config_settings.php
$lang['110_Various_Configuration'] = 'Various Settings'; // admin_board.php
$lang['115_CT_Config'] = 'CTracker Settings'; // admin_ctracker.php
$lang['127_Clear_Cache'] = 'Clear Cache'; // admin_board_clearcache.php
$lang['130_UPI2DB_Mod'] = 'Unread Posts'; // admin_upi2db.php
$lang['140_CAPTCHA'] = 'CAPTCHA'; // admin_captcha.php
$lang['145_Captcha_Config'] = 'Visual Confirmation'; // admin_captcha_config.php
$lang['150_Similar_topics'] = 'Similar Topics'; // admin_similar_topics.php
$lang['170_LIW'] = 'Limit Image Width'; // admin_liw.php
$lang['190_Spider_Bots'] = 'Spider / Bots'; // admin_bots.php
$lang['195_Yahoo_search'] = 'Yahoo Search'; // admin_yahoo_search.php
$lang['197_HTTP_REF'] = 'HTTP Referers'; // admin_referers.php
$lang['200_Language'] = 'Language'; // admin_lang_extend.php
$lang['210_MG_Quick_Settings'] = 'Quick Settings'; // admin_board_quick_settings.php
$lang['230_PHP_INFO'] = 'PHP Info'; // admin_phpinfo.php
$lang['240_GD_Info'] = 'GD Info'; // admin_gd_info.php

// General
$lang['1100_General'] = 'General'; // admin_acronyms.php, admin_autolinks.php, admin_force_read.php, admin_helpdesk.php, admin_liw.php, admin_force_read.php, admin_mass_email.php, admin_megamail.php, admin_notepad.php, admin_topics_labels.php, admin_smilies.php, admin_words.php, admin_yahoo_search.php, admin_lang_user_created.php
$lang['130_Mass_Email'] = 'Mass Email'; // admin_mass_email.php
$lang['140_Mega_Mail'] = 'Mass Email / PM'; // admin_megamail.php
$lang['150_Custom_BBCodes'] = 'Custom BBCodes'; // admin_bbcodes.php
$lang['170_Smilies'] = 'Smileys'; // admin_smilies.php
$lang['180_Word_Censor'] = 'Word Censor'; // admin_words.php
$lang['190_Acronyms'] = 'Acronyms'; // admin_acronyms.php
$lang['195_Autolinks'] = 'Autolinks'; // admin_autolinks.php
$lang['200_Notepad'] = 'Admin Notepad'; // admin_notepad.php
$lang['210_Help_Desk'] = 'Help Desk'; // admin_helpdesk.php
$lang['220_Tickets_Emails'] = 'Emails Categories'; // admin_tickets.php
$lang['230_Language'] = 'Custom Lang Vars'; // admin_lang_user_created.php

// CMS
$lang['1150_CMS'] = 'CMS'; // cms.php

// Forum
$lang['1200_Forums'] = 'Forum'; // admin_forum_prune.php, admin_forumauth_list.php, admin_forums.php, admin_forums_extend.php, admin_prune_overview.php, admin_topic_shadow.php
$lang['100_Manage'] = 'Management'; // admin_forums.php
$lang['110_Manage_extend'] = 'Advanced Management'; // admin_forums_extend.php
$lang['120_Permissions_List'] = 'Permissions List'; // admin_forumauth_list.php
$lang['122_Permissions_Adv'] = 'Permissions ADV'; // admin_forumauth_adv.php
$lang['125_Permissions_Forum'] = 'Permissions'; // admin_forumauth.php
$lang['130_Prune'] = 'Pruning'; // admin_forum_prune.php
$lang['140_Prune_Overview'] = 'Prune Overview'; // admin_prune_overview.php
$lang['150_Topic_Shadow'] = 'Shadow Topics'; // admin_topic_shadow.php
$lang['160_Topics_Labels'] = 'Topics Labels'; // admin_topics_labels.php
$lang['170_Topic_Rating_Config'] = 'Topic Rating Config'; // admin_rate.php
$lang['180_Topic_Rating_Auth'] = 'Topic Rating Permissions'; // admin_rate.php
$lang['240_Replace_title'] = 'Replace In Posts'; // admin_replace.php
$lang['250_FTR_Config'] = 'Force Topic Read'; // admin_force_read.php
$lang['260_FTR_Users'] = 'Force Topic Read Users'; // admin_force_read.php

// News
$lang['1250_News_Admin'] = 'News'; // admin_news.php, admin_news_cats.php, admin_xs_news.php, admin_xs_news_xml.php
$lang['100_News_Config'] = 'News Configuration'; // admin_news.php
$lang['110_News_Cats'] = 'News Categories'; // admin_news_cats.php
$lang['120_XS_News_Config'] = 'News Ticker Configuration'; // admin_xs_news.php
$lang['130_XS_News'] = 'News Ticker Articles'; // admin_xs_news.php
$lang['140_XS_News_Tickers'] = 'News Ticker'; // admin_xs_news_xml.php

// eXtreme Styles
$lang['1300_Extreme_Styles'] = 'Styles &amp; Templates'; // xs_include.php -> $module_name

// DB Maintenance & Security
$lang['1400_DB_Maintenance'] = 'DB And Security'; // admin_bb_db.php, admin_db_generator.php, admin_db_maintenance.php, admin_db_utilities.php, admin_logs.php
$lang['100_Actions_LOG'] = 'Actions Log'; // admin_logs.php
$lang['110_DB_Admin'] = 'IP MySQLAdmin'; // admin_bb_db.php
$lang['120_Backup_DB'] = 'DB Backup'; // admin_db_utilities.php, admin_db_backup
$lang['130_Restore_DB'] = 'DB Restore'; // admin_db_utilities.php, admin_db_backup
$lang['135_Restore_DB'] = 'DB Restore From File'; // admin_db_utilities.php
$lang['140_Optimize_DB'] = 'Optimize Database'; // admin_db_utilities.php
$lang['150_DB_Maintenance'] = 'Database Maintenance'; // admin_db_maintenance.php
$lang['170_db_update_generator'] = 'DB Update Generator'; // admin_db_generator.php
$lang['180_msqd'] = 'MySQLDumper'; // admin_msqd.php

// Users
$lang['1610_Users'] = 'Users'; // admin_account.php, admin_disallow.php, admin_email_list.php, admin_jr_admin.php, admin_postcount.php, admin_priv_msgs.php, admin_profile_fields.php, admin_ranks.php, admin_ug_auth.php, admin_user_ban.php, admin_user_bantron.php, admin_user_register.php, admin_user_search.php, admin_userlist.php, admin_users.php, admin_voting.php
$lang['100_Jr_Admin'] = 'Junior Admin'; // admin_jr_admin.php
$lang['110_Manage'] = 'Manage'; // admin_users.php
$lang['113_Permissions_Users'] = 'Permissions'; // admin_ug_auth.php
$lang['116_CMS_Permissions_Users'] = 'CMS Permissions'; // admin_cms_auth.php
$lang['120_Ranks'] = 'Ranks'; // admin_ranks.php
$lang['130_Userlist'] = 'Userlist'; // admin_userlist.php
$lang['140_Email_List'] = 'Email List'; // admin_email_list.php
$lang['150_Private_Messages'] = 'Private Messages'; // admin_priv_msgs.php
$lang['160_Account_active'] = 'Active Accounts'; // admin_account.php
$lang['170_Account_inactive'] = 'Inactive Accounts'; // admin_account.php
$lang['180_Add_New_User'] = 'Add New User'; // admin_user_register.php
$lang['190_Prune_users'] = 'Prune Users'; // admin_prune_users.php
$lang['200_Disallow'] = 'Disallow Usernames'; // admin_disallow.php
$lang['210_Ban_Management'] = 'Ban Users'; // admin_user_ban.php
$lang['220_Bantron'] = 'Bantron'; // admin_user_bantron.php
$lang['250_Postcount_Config'] = 'Edit Postcounts'; // admin_postcount.php
$lang['260_CPF_Add'] = 'Add Custom Profile Fields'; // admin_profile_fields.php
$lang['270_CPF_Edit'] = 'Edit Custom Profile Fields'; // admin_profile_fields.php
$lang['280_User_Search'] = 'Extended User Search'; // admin_user_search.php
$lang['290_Poll_Results'] = 'Poll Results'; // admin_voting.php
$lang['300_Picscount_Config'] = 'Sync Pics Count'; // admin_postcount.php
$lang['310_POSTS_EXPORT'] = 'Posts Export'; // admin_user_posts_export.php

// Groups
$lang['1620_Groups'] = 'Groups'; // admin_color_groups.php, admin_groups.php, admin_ug_auth.php
$lang['110_Manage_Groups'] = 'Manage Groups'; // admin_groups.php
$lang['120_Color_Groups'] = 'Colour Groups'; // admin_color_groups.php
$lang['130_Permissions_Group'] = 'Permissions'; // admin_ug_auth.php

// Knowledge Base
$lang['1800_KB_title'] = 'Knowledge Base'; // admin_kb_art.php, admin_kb_auth.php, admin_kb_cat.php, admin_kb_config.php, admin_kb_custom.php, admin_kb_rebuild_search.php, admin_kb_types.php
$lang['100_KB_Configuration'] = 'Configuration'; // admin_kb_config.php
$lang['110_Art_man'] = 'Article Management'; // admin_kb_art.php
$lang['120_Cat_man'] = 'Categories Management'; // admin_kb_cat.php
$lang['130_Types_man'] = 'Article Types'; // admin_kb_types.php
$lang['140_Custom_Field'] = 'Custom Fields'; // admin_kb_custom.php
$lang['150_Permissions'] = 'Permissions'; // admin_kb_auth.php
$lang['160_Optimize_tables'] = 'Optimize Tables'; // admin_kb_rebuild_search.php

// Attachments
$lang['1900_Attachments'] = 'Attachments'; // admin_attach_cp.php, admin_attachments.php, admin_extensions.php
$lang['100_Control_Panel'] = 'Control Panel'; // admin_attach_cp.php
$lang['110_Att_Manage'] = 'Management'; // admin_attachments.php
$lang['120_Quota_limits'] = 'Quota Limits'; // admin_attachments.php
$lang['130_Shadow_attachments'] = 'Shadow Attachments'; // admin_attachments.php
$lang['140_Sync_attachments'] = 'Synchronize Attachments'; // admin_attachments.php
$lang['150_Extension_control'] = 'Extension Control'; // admin_extensions.php
$lang['160_Extension_group_manage'] = 'Manage Extension Groups'; // admin_extensions.php
$lang['170_Forbidden_extensions'] = 'Forbidden Extensions'; // admin_extensions.php
$lang['180_Special_categories'] = 'Special Categories'; // admin_attachments.php

// Downloads
$lang['2000_Downloads'] = 'Downloads'; // admin_pa_catauth.php, admin_pa_category.php, admin_pa_custom.php, admin_pa_fchecker.php, admin_pa_file.php, admin_pa_license.php, admin_pa_settings.php
$lang['100_Settings'] = 'Configuration'; // admin_pa_settings.php
$lang['110_Cat_manage_title'] = 'Manage Categories'; // admin_pa_category.php
$lang['120_File_manage_title'] = 'Manage Files'; // admin_pa_file.php
$lang['130_Fchecker'] = 'File Checker'; // admin_pa_fchecker.php
$lang['140_Mfieldtitle'] = 'Custom Fields'; // admin_pa_custom.php
$lang['150_License_title'] = 'Manage Licenses'; // admin_pa_license.php
$lang['160_Permissions'] = 'Permissions'; // admin_pa_catauth.php

// Downloads
$lang['2050_Downloads'] = 'Downloads ADV'; // admin_pa_catauth.php, admin_pa_category.php, admin_pa_custom.php, admin_pa_fchecker.php, admin_pa_file.php, admin_pa_license.php, admin_pa_settings.php
$lang['100_DL_Settings'] = 'Configuration'; // admin_pa_settings.php

// Links
$lang['2100_Links'] = 'Links'; // admin_links.php, admin_links_cat.php, admin_links_config.php
$lang['100_Configuration'] = 'Configuration'; // admin_links_config.php
$lang['110_Category'] = 'Manage Categories'; // admin_links_cat.php
$lang['120_Add_new'] = 'Add Link'; // admin_links.php
$lang['130_Link_Manage'] = 'Manage Links'; // admin_links.php

// Album
$lang['3300_Photo_Album'] = 'Photo Album'; // admin_album_auth.php, admin_album_cat.php, admin_album_config_extended.php
$lang['110_Album_Config'] = 'Configuration'; // admin_album_config_extended.php
$lang['120_Album_Categories'] = 'Manage Categories'; // admin_album_cat.php
$lang['130_Album_Permissions'] = 'Permissions'; // admin_album_auth.php
$lang['140_Personal_Galleries'] = 'Personal Galleries'; // admin_album_personal.php

// FAQ
$lang['2300_FAQ'] = 'FAQ &amp; Rules'; // admin_faq_editor.php
$lang['110_FAQ_BBCode'] = 'BBCode FAQ'; // admin_faq_editor.php
$lang['120_FAQ_Board'] = 'Site FAQ'; // admin_faq_editor.php
$lang['130_FAQ_Rules'] = 'Site Rules'; // admin_faq_editor.php

// STATS
$lang['2500_STATS'] = 'Statistics'; // admin_statistics.php

// Plugins
$lang['3000_Plugins'] = 'Plugins'; // admin_plugins.php
$lang['100_Plugins_Modules'] = 'Plugins Modules'; // admin_plugins.php

// Cash
$lang['3100_CASH'] = 'Cash / Points'; // admin_cash.php
$lang['110_Cash_Admin'] = 'Cash Management'; // admin_cash.php
$lang['120_Cash_Help'] = 'Help'; // admin_cash.php

// Activity
$lang['3200_ACTIVITY'] = 'Activity / Games'; // admin_activity.php, admin_ina_ban.php, admin_ina_bulk_add.php, admin_ina_category.php, admin_ina_disable.php, admin_ina_in_un.php, admin_ina_mass.php, admin_ina_xtras.php
$lang['105_DB_Adjustments'] = 'Install / Uninstall'; // admin_ina_in_un.php
$lang['110_Configuration'] = 'Configuration'; // admin_activity.php
$lang['120_Add_Game'] = 'Add Game'; // admin_activity.php
$lang['130_Edit_Games'] = 'Edit Games'; // admin_activity.php
$lang['140_User_Ban'] = 'Ban Users'; // admin_ina_ban.php
$lang['150_Bulk_Add_Games'] = 'Bulk Add Games'; // admin_ina_bulk_add.php
$lang['160_Category'] = 'Categories Management'; // admin_ina_category.php
$lang['170_Char_Settings'] = 'Chars Settings'; // admin_ina_char.php
$lang['180_Hide_Show_Games'] = 'Show/Hide Games'; // admin_ina_disable.php
$lang['200_Mass_Change'] = 'Mass Configuration'; // admin_ina_mass.php
$lang['210_Scores_Editor'] = 'Edit Hi-Scores'; // admin_ina_scores.php
$lang['220_Xtras'] = 'Extra Settings'; // admin_ina_xtras.php
$lang['230_Check_Games'] = 'Games List'; // admin_ina_xtras.php

// ####################### [ ACP Navigation END ] #######################

?>