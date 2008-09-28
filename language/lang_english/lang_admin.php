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

//
// Modules, this replaces the keys used in the modules[][] arrays in each module file
//
$lang['General'] = 'General Admin';
$lang['Users'] = 'User Admin';
$lang['Groups'] = 'Group Admin';
$lang['Forums'] = 'Forum Admin';
$lang['Styles'] = 'Styles Admin';

$lang['Configuration'] = 'Settings';
$lang['Various_Configuration'] = 'Various Settings';
$lang['Permissions'] = 'Permissions';
$lang['Manage'] = 'Management';
$lang['manage'] = 'Management';
$lang['Disallow'] = 'Disallow names';
$lang['Prune'] = 'Pruning';
$lang['Mass_Email'] = 'Mass Email';
$lang['Ranks'] = 'Ranks';
$lang['Smilies'] = 'Smileys';
$lang['Ban_Management'] = 'Ban Control';
$lang['Word_Censor'] = 'Word Censors';
$lang['Export'] = 'Export';
$lang['Create_new'] = 'Create';
$lang['Add_new'] = 'Add';
$lang['Backup_DB'] = 'Backup Database';
$lang['Restore_DB'] = 'Restore Database';
$lang['DB_Maintenance'] = 'Database Tools';
$lang['News_Admin'] = 'News';
$lang['News_Cats'] = 'News Categories';
$lang['News_Config'] = 'News Configuration';
$lang['Security'] = 'Security';
$lang['Member_Tries'] = 'Member Tries';
$lang['Quick_Search'] = 'Quick Search';
$lang['Special'] = 'Special';
$lang['Styles_Management'] = 'Styles Management';
$lang['Manage_Bots'] = 'Bots Management';
$lang['Admin_Notepad'] = 'Notepad';

// Index
$lang['Admin'] = 'Administration';
$lang['Not_admin'] = 'You are not authorised to administer this board';
$lang['Welcome_phpBB'] = 'Welcome to Icy Phoenix';
$lang['Admin_intro'] = 'Thank you for choosing Icy Phoenix as your forum solution. This screen will give you a quick overview of all the various statistics of your site. You can get back to this page by clicking on the <u>Admin Index</u> link above. To return to the index of your board, click on the Forum link (also above). The menu on the left hand side of this screen will allow you to control every aspect of your forum experience. Each secondary option link will have instructions on how to use the tools.';
$lang['Forum_stats'] = 'Site Statistics';
$lang['Admin_Index'] = 'Admin Index';
$lang['Preview_forum'] = 'Preview Forum';
$lang['Click_return_admin_index'] = 'Click %sHere%s to return to the Admin Index';
$lang['Portal'] = 'Home Page';
$lang['Preview_Portal'] = 'Preview Home Page';
$lang['Main_index'] = 'Forum';

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

$lang['ON'] = 'ON'; // This is for GZip compression
$lang['OFF'] = 'OFF';

//
// DB Utils
//
$lang['Database_Utilities'] = 'Database Utilities';

$lang['Restore'] = 'Restore';
$lang['Backup'] = 'Backup';
$lang['Restore_explain'] = 'This will perform a full restore of all Icy Phoenix tables from a saved file. If your server supports it, you may upload a gzip-compressed text file and it will automatically be decompressed. <b>WARNING</b>: This will overwrite any existing data. The restore may take a long time to process, so please do not move from this page until it is complete.';
$lang['Backup_explain'] = 'Back up all your site-related data. If you have any additional custom tables in the same database with Icy Phoenix that you would like to back up as well, please enter their names, separated by commas, in the Additional Tables textbox below. If your server supports it you may also gzip-compress the file to reduce its size before download.';

$lang['Backup_options'] = 'Backup options';
$lang['Start_backup'] = 'Start Backup';
$lang['Full_backup'] = 'Full backup';
$lang['Structure_backup'] = 'Structure-Only backup';
$lang['Data_backup'] = 'Data only backup';
$lang['Additional_tables'] = 'Additional tables';
$lang['phpBB_only'] = 'Only Icy Phoenix related tables';
$lang['Gzip_compress'] = 'Gzip compress file';
$lang['Select_file'] = 'Select a file';
$lang['Start_Restore'] = 'Start Restore';

$lang['Restore_success'] = 'The Database has been successfully restored.<br /><br />Your board should be back to the state it was when the backup was made.';
$lang['Backup_download'] = 'Your download will start shortly; please wait until it begins.';
$lang['Backups_not_supported'] = 'Sorry, but database backups are not currently supported for your database system.';

$lang['Restore_Error_uploading'] = 'Error in uploading the backup file';
$lang['Restore_Error_filename'] = 'Filename problem; please try an alternative file';
$lang['Restore_Error_decompress'] = 'Cannot decompress a gzip file; please upload a plain text version';
$lang['Restore_Error_no_file'] = 'No file was uploaded';


//
// Auth pages
//
$lang['Select_a_User'] = 'Select a User';
$lang['Select_a_Group'] = 'Select a Group';
$lang['Select_a_Forum'] = 'Select a Forum';
$lang['Auth_Control_User'] = 'User Permissions Control';
$lang['Auth_Control_Group'] = 'Group Permissions Control';
$lang['Auth_Control_Forum'] = 'Forum Permissions Control';
// Start add Permission List
$lang['Auth_list_Control_Forum'] = 'All Forums Permissions Control';
// End add Permission List
$lang['Look_up_User'] = 'Look up User';
$lang['Look_up_Group'] = 'Look up Group';
$lang['Look_up_Forum'] = 'Look up Forum';

$lang['Group_auth_explain'] = 'Alter the permissions and moderator status assigned to each user group. Do not forget when changing group permissions that individual user permissions may still allow the user entry to forums, etc. You will be warned if this is the case.';
$lang['User_auth_explain'] = 'Alter the permissions and moderator status assigned to each individual user. Do not forget when changing user permissions that group permissions may still allow the user entry to forums, etc. You will be warned if this is the case.';
$lang['Forum_auth_explain'] = 'Alter the authorisation levels of each forum. You will have both a simple and advanced method for doing this, where advanced offers greater control of each forum operation. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';
// Start add Permission List
$lang['Forum_auth_list_explain'] = 'Alter the authorisation levels of each forum. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';
// End add Permission List

$lang['Simple_mode'] = 'Simple Mode';
$lang['Advanced_mode'] = 'Advanced Mode';
$lang['Moderator_status'] = 'Moderator status';

$lang['Allowed_Access'] = 'Allowed Access';
$lang['Disallowed_Access'] = 'Disallowed Access';
$lang['Is_Moderator'] = 'Is Moderator';
$lang['Not_Moderator'] = 'Not Moderator';

$lang['Conflict_warning'] = 'Authorisation Conflict Warning';
$lang['Conflict_access_userauth'] = 'This user still has access rights to this forum via group membership. You may want to alter the group permissions or remove this user from the group to fully prevent them having access rights. The groups granting rights (and the forums involved) are noted below.';
$lang['Conflict_mod_userauth'] = 'This user still has moderator rights to this forum via group membership. You may want to alter the group permissions or remove this user from the group to fully prevent them having moderator rights. The groups granting rights (and the forums involved) are noted below.';

$lang['Conflict_access_groupauth'] = 'The following user (or users) still have access rights to this forum via their user permission settings. You may want to alter their user permissions to fully prevent them having access rights. The users granted rights (and the forums involved) are noted below.';
$lang['Conflict_mod_groupauth'] = 'The following user (or users) still have moderator rights to this forum via their user permissions settings. You may want to alter their user permissions to fully prevent them having moderator rights. The users granted rights (and the forums involved) are noted below.';

$lang['Public'] = 'Public';
$lang['Private'] = 'Private';
$lang['Registered'] = 'Registered';
$lang['Self'] = 'Self';
$lang['Administrators'] = 'Administrators';
$lang['Hidden'] = 'Hidden';

// These are displayed in the drop down boxes for advanced
// mode forum auth, try and keep them short!
$lang['Forum_NONE'] = 'NONE';
$lang['Forum_ALL'] = 'ALL';
$lang['Forum_REG'] = 'REG';
$lang['Forum_SELF'] = 'SELF';
$lang['Forum_PRIVATE'] = 'PRIVATE';
$lang['Forum_MOD'] = 'MOD';
$lang['Forum_ADMIN'] = 'ADMIN';

$lang['View'] = 'View';
$lang['Read'] = 'Read';
$lang['Post'] = 'Post';
$lang['Reply'] = 'Reply';
$lang['Edit'] = 'Edit';
$lang['Delete'] = 'Delete';
$lang['Sticky'] = 'Sticky';
$lang['Announce'] = 'Announce';
$lang['Vote'] = 'Vote';
$lang['Pollcreate'] = 'Poll create';

$lang['Simple_Permission'] = 'Simple Permissions';

$lang['User_Level'] = 'User Level';
$lang['Auth_User'] = 'User';
$lang['Auth_Junior_Admin'] = 'Junior Administrator';
$lang['Auth_Admin'] = 'Administrator';
$lang['Group_memberships'] = 'Usergroup memberships (in total: %d)';
$lang['Usergroup_members'] = 'This group has the following members (in total: %d)';

$lang['Forum_auth_updated'] = 'Forum permissions updated';
$lang['User_auth_updated'] = 'User permissions updated';
$lang['Group_auth_updated'] = 'Group permissions updated';

$lang['Auth_updated'] = 'Permissions have been updated';
$lang['Click_return_userauth'] = 'Click %sHere%s to return to User Permissions';
$lang['Click_return_groupauth'] = 'Click %sHere%s to return to Group Permissions';
$lang['Click_return_forumauth'] = 'Click %sHere%s to return to Forum Permissions';


//
// Banning
//
$lang['Ban_control'] = 'Ban Control';
$lang['Ban_explain'] = 'Control the banning of users. You can achieve this by banning either or both a specific user or an individual or range of IP addresses or hostnames. These methods prevent a user from even reaching the index page of your site. To prevent a user from registering under a different username you can also specify a banned email address. Please note that banning an email address alone will not prevent that user from being able to log on or post to your board. You should use one of the first two methods to achieve this.';
$lang['Ban_explain_warn'] = 'Please note that entering a range of IP addresses results in all the addresses between the start and end being added to the banlist. Attempts will be made to minimise the number of addresses added to the database by introducing wildcards automatically where appropriate. If you really must enter a range, try to keep it small or better yet state specific addresses.';

$lang['Select_username'] = 'Select a Username';
$lang['Select_ip'] = 'Select an IP address';
$lang['Select_email'] = 'Select an Email address';

$lang['Ban_username'] = 'Ban one or more specific users';
$lang['Ban_username_explain'] = 'You can ban multiple users in one go by using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Ban_IP'] = 'Ban one or more IP addresses or hostnames';
$lang['IP_hostname'] = 'IP addresses or hostnames';
$lang['Ban_IP_explain'] = 'To specify several different IP addresses or hostnames separate them with commas. To specify a range of IP addresses, separate the start and end with a hyphen (-); to specify a wildcard, use an asterisk (*).';

$lang['Ban_email'] = 'Ban one or more email addresses';
$lang['Ban_email_explain'] = 'To specify more than one email address, separate them with commas. To specify a wildcard username, use * like *@hotmail.com';

$lang['Unban_username'] = 'Un-ban one or more specific users';
$lang['Unban_username_explain'] = 'You can un-ban multiple users in one go by using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Unban_IP'] = 'Un-ban one or more IP addresses';
$lang['Unban_IP_explain'] = 'You can un-ban multiple IP addresses in one go by using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Unban_email'] = 'Un-ban one or more email addresses';
$lang['Unban_email_explain'] = 'You can un-ban multiple email addresses in one go by using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['No_banned_users'] = 'No banned usernames';
$lang['No_banned_ip'] = 'No banned IP addresses';
$lang['No_banned_email'] = 'No banned email addresses';

$lang['Ban_update_sucessful'] = 'The banlist has been updated successfully';
$lang['Click_return_banadmin'] = 'Click %sHere%s to return to Ban Control';


//
// Configuration
//
$lang['General_Config'] = 'General Configuration';
$lang['Config_explain'] = '<b>Customize all the general board options. For User and Forum configurations, use the related links on the left hand side.</b>';

$lang['Click_return_config'] = 'Click %sHere%s to return to General Configuration';

$lang['General_settings'] = 'General Board Settings';
$lang['Server_name'] = 'Domain Name';
$lang['Server_name_explain'] = 'The domain name from which this board runs';
$lang['Script_path'] = 'Script path';
$lang['Script_path_explain'] = 'The path where Icy Phoenix is located relative to the domain name';
$lang['Server_port'] = 'Server Port';
$lang['Server_port_explain'] = 'The port your server is running on, usually 80. Only change if different';
$lang['Site_name'] = 'Site name';
$lang['Site_desc'] = 'Site description';
$lang['Board_disable'] = 'Disable site';
$lang['Board_disable_explain'] = 'This will make the site unavailable to users. Administrators are able to access the Administration Panel while the site is disabled.';
$lang['Acct_activation'] = 'Enable account activation';
$lang['Acc_None'] = 'None'; // These three entries are the type of activation
$lang['Acc_User'] = 'User';
$lang['Acc_Admin'] = 'Admin';

$lang['Abilities_settings'] = 'User and Forum Basic Settings';
$lang['Max_poll_options'] = 'Max number of poll options';
$lang['Flood_Interval'] = 'Flood Interval';
$lang['Flood_Interval_explain'] = 'Number of seconds a user must wait between posts';
$lang['Board_email_form'] = 'User email via board';
$lang['Board_email_form_explain'] = 'Users send email to each other via this board';
$lang['Topics_per_page'] = 'Topics Per Page';
$lang['Posts_per_page'] = 'Posts Per Page';
$lang['Hot_threshold'] = 'Posts for Popular Threshold';
$lang['Default_style'] = 'Default Style';
$lang['Override_style'] = 'Override user style';
$lang['Override_style_explain'] = 'Replaces users style with the default';
$lang['Default_language'] = 'Default Language';
$lang['Date_format'] = 'Date Format';
$lang['System_timezone'] = 'System Timezone';
$lang['Enable_gzip'] = 'Enable GZip Compression';
// Start Gzip Compression Level MOD
$lang['Gzip_level'] = 'Gzip Compression Level';
$lang['Gzip_level_explain'] = 'Change the compression level (a number between 0-9). 0 is equivalent to off, 1 is very low, and 9 is the maximum. 9 is recommended.';
// End Gzip Compression Level MOD
$lang['Enable_prune'] = 'Enable Forum Pruning';
$lang['Allow_HTML'] = 'Allow HTML';
$lang['Allow_BBCode'] = 'Allow BBCode';
$lang['Allowed_tags'] = 'Allowed HTML tags';
$lang['Allowed_tags_explain'] = 'Separate tags with commas';
$lang['Allow_smilies'] = 'Allow Smileys';
$lang['Smilies_path'] = 'Smileys Storage Path';
$lang['Smilies_path_explain'] = 'Path under your Icy Phoenix root dir, e.g. images/smiles';
$lang['Allow_sig'] = 'Allow Signatures';
$lang['Max_sig_length'] = 'Maximum signature length';
$lang['Max_sig_length_explain'] = 'Maximum number of characters in user signatures';
$lang['Allow_name_change'] = 'Allow Username changes';

$lang['Avatar_settings'] = 'Avatar Settings';
$lang['Allow_local'] = 'Enable gallery avatars';
$lang['Allow_remote'] = 'Enable remote avatars';
$lang['Allow_remote_explain'] = 'Avatars linked to from another website';
$lang['Allow_upload'] = 'Enable avatar uploading';
$lang['Max_filesize'] = 'Maximum Avatar File Size';
$lang['Max_filesize_explain'] = 'For uploaded avatar files';
$lang['Max_avatar_size'] = 'Maximum Avatar Dimensions';
$lang['Max_avatar_size_explain'] = '(Height x Width in pixels)';
$lang['Avatar_storage_path'] = 'Avatar Storage Path';
$lang['Avatar_storage_path_explain'] = 'Path under your Icy Phoenix root dir, e.g. images/avatars';
$lang['Avatar_gallery_path'] = 'Avatar Gallery Path';
$lang['Avatar_gallery_path_explain'] = 'Path under your Icy Phoenix root dir for pre-loaded images, e.g. images/avatars/gallery';

$lang['COPPA_settings'] = 'COPPA Settings';
$lang['COPPA_fax'] = 'COPPA Fax Number';
$lang['COPPA_mail'] = 'COPPA Mailing Address';
$lang['COPPA_mail_explain'] = 'This is the mailing address to which parents will send COPPA registration forms';

$lang['Email_settings'] = 'Email Settings';
$lang['Admin_email'] = 'Admin Email Address';
$lang['Email_sig'] = 'Email Signature';
$lang['Email_sig_explain'] = 'This text will be attached to all emails sent from the board';
$lang['Use_SMTP'] = 'Use SMTP Server for email';
$lang['Use_SMTP_explain'] = 'Say yes if you want to, or have to send email via a named server instead of the local mail function';
$lang['SMTP_server'] = 'SMTP Server Address';
$lang['SMTP_username'] = 'SMTP Username';
$lang['SMTP_username_explain'] = 'Only enter a username if your SMTP server requires it';
$lang['SMTP_password'] = 'SMTP Password';
$lang['SMTP_password_explain'] = 'Only enter a password if your SMTP server requires it';

$lang['Disable_privmsg'] = 'Private Messaging';
$lang['Inbox_limits'] = 'Max posts in Inbox';
$lang['Sentbox_limits'] = 'Max posts in Sentbox';
$lang['Savebox_limits'] = 'Max posts in Savebox';

$lang['Cookie_settings'] = 'Cookie settings';
$lang['Cookie_settings_explain'] = 'These details define how cookies are sent to your users\' browsers. In most cases the default values for the cookie settings should be sufficient, but if you need to change them do so with care - incorrect settings can prevent users from logging in';
$lang['Cookie_domain'] = 'Cookie domain';
$lang['Cookie_name'] = 'Cookie name';
$lang['Cookie_path'] = 'Cookie path';
$lang['Cookie_secure'] = 'Cookie secure';
$lang['Cookie_secure_explain'] = 'If your server is running via SSL, set this to enabled, else leave as disabled';
$lang['Session_length'] = 'Session length [ seconds ]';

// Visual Confirmation
$lang['Visual_confirm'] = 'Enable Visual Confirmation';
$lang['Visual_confirm_explain'] = 'Requires users to enter a code defined by an image when registering.';

// Autologin Keys - added 2.0.18
$lang['Allow_autologin'] = 'Allow automatic logins';
$lang['Allow_autologin_explain'] = 'Determines whether users are allowed to select to be automatically logged in when visiting the forum';
$lang['Autologin_time'] = 'Automatic login key expiry';
$lang['Autologin_time_explain'] = 'How long an autologin key is valid for in days if the user does not visit the board. Set to zero to disable expiry.';

// Forum Management
$lang['Forum_admin'] = 'Forum Administration';
$lang['Forum_admin_explain'] = 'Add, delete, edit, re-order and re-synchronise categories and forums';
$lang['Edit_forum'] = 'Edit forum';
$lang['Create_forum'] = 'Create new forum';
$lang['Create_category'] = 'Create new category';
$lang['Remove'] = 'Remove';
$lang['Action'] = 'Action';
$lang['Update_order'] = 'Update Order';
$lang['Config_updated'] = 'Forum Configuration Updated Successfully';
$lang['Move_up'] = 'Move up';
$lang['Move_down'] = 'Move down';
$lang['Resync'] = 'Resync';
$lang['No_mode'] = 'No mode was set';
$lang['Forum_edit_delete_explain'] = 'Customize all the general board options. For User and Forum configurations use the related links on the left hand side';

$lang['Move_contents'] = 'Move all contents';
$lang['Forum_delete'] = 'Delete Forum';
$lang['Forum_delete_explain'] = 'Delete a forum (or category) and decide where you want to put all topics (or forums) it contained.';

$lang['Status_locked'] = 'Locked';
$lang['Status_unlocked'] = 'Unlocked';
$lang['Forum_settings'] = 'General Forum Settings';
$lang['Forum_name'] = 'Forum name';
$lang['Forum_desc'] = 'Description';
$lang['Forum_status'] = 'Forum status';
$lang['Forum_pruning'] = 'Auto-pruning';

$lang['prune_freq'] = 'Check for topic age every';
$lang['prune_days'] = 'Remove topics that have not been posted to in';
$lang['Set_prune_data'] = 'You have turned on auto-prune for this forum but did not set a frequency or number of days to prune. Please go back and do so.';

$lang['Move_and_Delete'] = 'Move and Delete';

$lang['Delete_all_posts'] = 'Delete all posts';
$lang['Nowhere_to_move'] = 'Nowhere to move to';

$lang['Edit_Category'] = 'Edit Category';
$lang['Edit_Category_explain'] = 'Use this form to modify a category name.';

$lang['Forums_updated'] = 'Forum and Category information updated successfully';

$lang['Must_delete_forums'] = 'You need to delete all forums before you can delete this category';

$lang['Click_return_forumadmin'] = 'Click %sHere%s to return to Forum Administration';


//
// Smiley Management
//
$lang['smiley_title'] = 'Smiles Editing Utility';
$lang['smile_desc'] = 'Add, remove and edit the emoticons or smileys that your users can use in their posts and private messages.';

$lang['smiley_config'] = 'Smiley Configuration';
$lang['smiley_code'] = 'Smiley Code';
$lang['smiley_url'] = 'Smiley Image File';
$lang['smiley_emot'] = 'Smiley Emotion';
$lang['smile_add'] = 'Add a new Smiley';
$lang['Smile'] = 'Smile';
$lang['Emotion'] = 'Emotion';

$lang['Select_pak'] = 'Select Pack (.pak) File';
$lang['replace_existing'] = 'Replace Existing Smiley';
$lang['keep_existing'] = 'Keep Existing Smiley';
$lang['smiley_import_inst'] = 'You should unzip the smiley package and upload all files to the appropriate Smiley directory for your installation. Then select the correct information in this form to import the smiley pack.';
$lang['smiley_import'] = 'Smiley Pack Import';
$lang['choose_smile_pak'] = 'Choose a Smile Pack .pak file';
$lang['import'] = 'Import Smileys';
$lang['smile_conflicts'] = 'What should be done in case of conflicts';
$lang['del_existing_smileys'] = 'Delete existing smileys before import';
$lang['import_smile_pack'] = 'Import Smiley Pack';
$lang['export_smile_pack'] = 'Create Smiley Pack';
$lang['export_smiles'] = 'To create a smiley pack from your currently installed smileys, click %sHere%s to download the smiles.pak file. Name this file appropriately making sure to keep the .pak file extension.  Then create a zip file containing all of your smiley images plus this .pak configuration file.';

$lang['smiley_add_success'] = 'The Smiley was successfully added';
$lang['smiley_edit_success'] = 'The Smiley was successfully updated';
$lang['smiley_import_success'] = 'The Smiley Pack was imported successfully!';
$lang['smiley_del_success'] = 'The Smiley was successfully removed';
$lang['Click_return_smileadmin'] = 'Click %sHere%s to return to Smiley Administration';


// User Management
$lang['User_admin'] = 'User Administration';
$lang['User_admin_explain'] = 'Change your users\' information and certain options. To modify the users\' permissions, please use the User and Group permissions system.';

$lang['Look_up_user'] = 'Look up user';

$lang['Admin_user_fail'] = 'Couldn\'t update the user\'s profile.';
$lang['Admin_user_updated'] = 'The user\'s profile was successfully updated.';
$lang['Click_return_useradmin'] = 'Click %sHere%s to return to User Administration';
//Start Quick Administrator User Options and Information MOD
$lang['Click_return_userprofile'] = 'Click %sHere%s to return to the user\'s profile';
//End Quick Administrator User Options and Information MOD
$lang['User_delete'] = 'Delete this user';
$lang['User_delete_explain'] = 'Click here to delete this user; <u><em>this cannot be undone.</em></u>';
$lang['User_deleted'] = 'User was successfully deleted.';

$lang['User_status'] = 'User is active';
$lang['User_allowpm'] = 'Can send Private Messages';
$lang['User_allowavatar'] = 'Can display avatar';

$lang['Admin_avatar_explain'] = 'See and delete the user\'s current avatar.';

$lang['User_special'] = 'Special admin-only fields';
$lang['User_special_explain'] = 'These fields are not able to be modified by the users. You can set their status and other options that are not given to users.';


// Group Management
$lang['Group_administration'] = 'Group Administration';
$lang['Group_admin_explain'] = 'Administer all your usergroups. You can delete, create and edit existing groups. You may choose moderators, toggle open/closed group status and set the group name and description';
$lang['Error_updating_groups'] = 'There was an error while updating the groups';
$lang['Updated_group'] = 'The group was successfully updated';
$lang['Added_new_group'] = 'The new group was successfully created';
$lang['Deleted_group'] = 'The group was successfully deleted';
$lang['New_group'] = 'Create new group';
$lang['Edit_group'] = 'Edit group';
$lang['group_name'] = 'Group name';
$lang['group_description'] = 'Group description';
$lang['group_moderator'] = 'Group moderator';
$lang['group_status'] = 'Group status';
$lang['group_open'] = 'Open group';
$lang['group_closed'] = 'Closed group';
$lang['group_hidden'] = 'Hidden group';
$lang['group_delete'] = 'Delete group';
$lang['group_delete_check'] = 'Delete this group';
$lang['submit_group_changes'] = 'Submit Changes';
$lang['reset_group_changes'] = 'Reset Changes';
$lang['No_group_name'] = 'You must specify a name for this group';
$lang['No_group_moderator'] = 'You must specify a moderator for this group';
$lang['No_group_mode'] = 'You must specify a mode for this group, open or closed';
$lang['No_group_action'] = 'No action was specified';
$lang['delete_group_moderator'] = 'Delete the old group moderator?';
$lang['delete_moderator_explain'] = 'If you\'re changing the group moderator, check this box to remove the old moderator from the group. By not checking it, the user will become a regular member of the group.';
$lang['Click_return_groupsadmin'] = 'Click %sHere%s to return to Group Administration.';
$lang['Select_group'] = 'Select a group';
$lang['Look_up_group'] = 'Look up group';


//
// Prune Administration
//
$lang['Forum_Prune'] = 'Forum Prune';
$lang['Forum_Prune_explain'] = 'This will delete any topic which has not been posted to within the number of days you select. If you do not enter a number then all topics will be deleted. It will not remove topics in which polls are still running nor will it remove announcements. You will need to remove those topics manually.';
$lang['Do_Prune'] = 'Do Prune';
$lang['All_Forums'] = 'All Forums';
$lang['Prune_topics_not_posted'] = 'Prune topics with no replies in this many days';
$lang['Topics_pruned'] = 'Topics pruned';
$lang['Posts_pruned'] = 'Posts pruned';
$lang['Prune_success'] = 'Pruning of forums was successful';


//
// Word censor
//
$lang['Words_title'] = 'Word Censoring';
$lang['Words_explain'] = 'Add, edit and remove words that will be automatically censored on your forums. In addition people will not be allowed to register with usernames containing these words. Wildcards (*) are accepted in the word field. For example, *test* will match detestable, test* would match testing, *test would match detest.';
$lang['Word'] = 'Word';
$lang['Edit_word_censor'] = 'Edit word censor';
$lang['Replacement'] = 'Replacement';
$lang['Add_new_word'] = 'Add new word';
$lang['Update_word'] = 'Update word censor';

$lang['Must_enter_word'] = 'You must enter a word and its replacement';
$lang['No_word_selected'] = 'No word selected for editing';

$lang['Word_updated'] = 'The selected word censor has been successfully updated';
$lang['Word_added'] = 'The word censor has been successfully added';
$lang['Word_removed'] = 'The selected word censor has been successfully removed';

$lang['Click_return_wordadmin'] = 'Click %sHere%s to return to Word Censor Administration';


//
// Mass Email
//
$lang['Mass_email_explain'] = 'Email a message to either all of your users or all users of a specific group.  To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients. If you are emailing a large group of people please be patient after submitting and do not stop the page halfway through. It is normal for a mass emailing to take a long time and you will be notified when the script has completed';
$lang['Compose'] = 'Compose';

$lang['Recipients'] = 'Recipients';
$lang['All_users'] = 'All Users';

$lang['Email_successfull'] = 'Your message has been sent';
$lang['Click_return_massemail'] = 'Click %sHere%s to return to the Mass Email form';


//
// Ranks admin
//
$lang['Ranks_title'] = 'Rank Administration';
$lang['Ranks_explain'] = 'Add, edit, view and delete ranks. You can also create custom ranks which can be applied to a user via the user management facility';

$lang['Add_new_rank'] = 'Add new rank';

$lang['Rank_title'] = 'Rank Title';
$lang['Rank_special'] = 'Set as Special Rank';
$lang['Rank_minimum'] = 'Minimum Posts';
$lang['Rank_maximum'] = 'Maximum Posts';
$lang['Rank_image'] = 'Rank Image (Relative to Icy Phoenix root path)';
$lang['Rank_image_explain'] = 'Use this to define a small image associated with the rank';

$lang['Must_select_rank'] = 'You must select a rank';
$lang['No_assigned_rank'] = 'No special rank assigned';

$lang['Rank_updated'] = 'The rank was successfully updated';
$lang['Rank_added'] = 'The rank was successfully added';
$lang['Rank_removed'] = 'The rank was successfully deleted';
$lang['No_update_ranks'] = 'The rank was successfully deleted. However, user accounts using this rank were not updated. You will need to manually reset the rank on these accounts';

$lang['Click_return_rankadmin'] = 'Click %sHere%s to return to Rank Administration';


//
// Disallow Username Admin
//
$lang['Disallow_control'] = 'Username Disallow Control';
$lang['Disallow_explain'] = 'Control usernames which will not be allowed to be used. Disallowed usernames are allowed to contain a wildcard character of *. Please note that you will not be allowed to specify any username that has already been registered. You must first delete that name and then disallow it.';

$lang['Delete_disallow'] = 'Delete';
$lang['Delete_disallow_title'] = 'Remove a Disallowed Username';
$lang['Delete_disallow_explain'] = 'You can remove a disallowed username by selecting the username from this list and clicking delete';

$lang['Add_disallow'] = 'Add';
$lang['Add_disallow_title'] = 'Add a disallowed username';
$lang['Add_disallow_explain'] = 'You can disallow a username using the wildcard character * to match any character';

$lang['No_disallowed'] = 'No Disallowed Usernames';

$lang['Disallowed_deleted'] = 'The disallowed username has been successfully removed';
$lang['Disallow_successful'] = 'The disallowed username has been successfully added';
$lang['Disallowed_already'] = 'The name you entered could not be disallowed. It either already exists in the list, exists in the word censor list, or a matching username is present.';

$lang['Click_return_disallowadmin'] = 'Click %sHere%s to return to Disallow Username Administration';


//
// Styles Admin
//
$lang['Styles_admin'] = 'Styles Administration';
$lang['Styles_explain'] = 'Add, remove and manage styles (templates and themes) available to your users';
$lang['Styles_addnew_explain'] = 'The following list contains all the themes that are available for the templates you currently have. The items on this list have not yet been installed into the Icy Phoenix database. To install a theme, simply click the install link beside an entry.';

$lang['Select_template'] = 'Select a Template';

$lang['Style'] = 'Style';
$lang['Template'] = 'Template';
$lang['Download'] = 'Download';

$lang['Edit_theme'] = 'Edit Theme';
$lang['Edit_theme_explain'] = 'Edit the settings for the selected theme';

$lang['Create_theme'] = 'Create Theme';
$lang['Create_theme_explain'] = 'Create a new theme for a selected template. When entering colours (for which you should use hexadecimal notation) you must not include the initial #, i.e.. CCCCCC is valid, #CCCCCC is not';

$lang['Export_themes'] = 'Export Themes';
$lang['Export_explain'] = 'Export the theme data for a selected template. Select the template from the list below and the script will create the theme configuration file and attempt to save it to the selected template directory. If it cannot save the file itself it will give you the option to download it. In order for the script to save the file you must give write access to the webserver for the selected template dir. For more information on this see the Icy Phoenix users guide.';

$lang['Theme_installed'] = 'The selected theme has been installed successfully';
$lang['Style_removed'] = 'The selected style has been removed from the database. To fully remove this style from your system you must delete the appropriate style from your templates directory.';
$lang['Theme_info_saved'] = 'The theme information for the selected template has been saved. You should now return the permissions on the theme_info.cfg (and if applicable the selected template directory) to read-only';
$lang['Theme_updated'] = 'The selected theme has been updated. You should now export the new theme settings';
$lang['Theme_created'] = 'Theme created. You should now export the theme to the theme configuration file for safe keeping or use elsewhere';

$lang['Confirm_delete_style'] = 'Are you sure you want to delete this style?';

$lang['Download_theme_cfg'] = 'The exporter could not write the theme information file. Click the button below to download this file with your browser. Once you have downloaded it you can transfer it to the directory containing the template files. You can then package the files for distribution and use elsewhere if you desire';
$lang['No_themes'] = 'The template you selected has no themes attached to it. To create a new theme click the Create New link on the left hand panel';
$lang['No_template_dir'] = 'Could not open the template directory. It may be unreadable by the webserver or may not exist';
$lang['Cannot_remove_style'] = 'You cannot remove the style selected since it is currently the forum default. Please change the default style and try again.';
$lang['Style_exists'] = 'The style name you selected already exists, please go back and choose a different name.';

$lang['Click_return_styleadmin'] = 'Click %sHere%s to return to Style Administration';

$lang['Theme_settings'] = 'Theme Settings';
$lang['Theme_element'] = 'Theme Element';
$lang['Simple_name'] = 'Simple Name';
$lang['Save_Settings'] = 'Save Settings';

$lang['Stylesheet'] = 'CSS Stylesheet';
$lang['Stylesheet_explain'] = 'Filename for CSS stylesheet to use for this theme.';
$lang['Background_image'] = 'Background Image';
$lang['Background_color'] = 'Background Colour';
$lang['Theme_name'] = 'Theme Name';
$lang['Link_color'] = 'Link Colour';
$lang['Text_color'] = 'Text Colour';
$lang['VLink_color'] = 'Visited Link Colour';
$lang['ALink_color'] = 'Active Link Colour';
$lang['HLink_color'] = 'Hover Link Colour';
$lang['Tr_color1'] = 'Table Row Colour 1';
$lang['Tr_color2'] = 'Table Row Colour 2';
$lang['Tr_color3'] = 'Table Row Colour 3';
$lang['Tr_class1'] = 'Table Row Class 1';
$lang['Tr_class2'] = 'Table Row Class 2';
$lang['Tr_class3'] = 'Table Row Class 3';
$lang['Th_color1'] = 'Table Header Colour 1';
$lang['Th_color2'] = 'Table Header Colour 2';
$lang['Th_color3'] = 'Table Header Colour 3';
$lang['Th_class1'] = 'Table Header Class 1';
$lang['Th_class2'] = 'Table Header Class 2';
$lang['Th_class3'] = 'Table Header Class 3';
$lang['Td_color1'] = 'Table Cell Colour 1';
$lang['Td_color2'] = 'Table Cell Colour 2';
$lang['Td_color3'] = 'Table Cell Colour 3';
$lang['Td_class1'] = 'Table Cell Class 1';
$lang['Td_class2'] = 'Table Cell Class 2';
$lang['Td_class3'] = 'Table Cell Class 3';
$lang['fontface1'] = 'Font Face 1';
$lang['fontface2'] = 'Font Face 2';
$lang['fontface3'] = 'Font Face 3';
$lang['fontsize1'] = 'Font Size 1';
$lang['fontsize2'] = 'Font Size 2';
$lang['fontsize3'] = 'Font Size 3';
$lang['fontcolor1'] = 'Font Colour 1';
$lang['fontcolor2'] = 'Font Colour 2';
$lang['fontcolor3'] = 'Font Colour 3';
$lang['span_class1'] = 'Span Class 1';
$lang['span_class2'] = 'Span Class 2';
$lang['span_class3'] = 'Span Class 3';
$lang['img_poll_size'] = 'Polling Image Size [px]';
$lang['img_pm_size'] = 'Private Message Status size [px]';

//
// Admin Userlist Start
//
$lang['Userlist'] = 'User list';
$lang['Userlist_description'] = 'View a complete list of your users and perform various actions on them';

$lang['Add_group'] = 'Add to a Group';
$lang['Add_group_explain'] = 'Select which group to add the selected users to';

$lang['Open_close'] = 'Open/Close';
$lang['Active'] = 'Active';
$lang['Group'] = 'Group(s)';
$lang['Rank'] = 'Rank';
$lang['Last_activity'] = 'Last Activity';
$lang['Never'] = 'Never';
$lang['User_manage'] = 'Manage';
$lang['Find_all_posts'] = 'Find All Posts';

$lang['Select_one'] = 'Select One';
$lang['Ban'] = 'Ban';
$lang['Activate_deactivate'] = 'Activate/Deactivate';

$lang['User_id'] = 'User id';
$lang['User_level'] = 'User Level';
$lang['Ascending'] = 'Ascending';
$lang['Descending'] = 'Descending';
$lang['Show'] = 'Show';
$lang['All'] = 'All';

$lang['Member'] = 'Member';
$lang['Pending'] = 'Pending';

$lang['Confirm_user_ban'] = 'Are you sure you want to ban the selected user(s)?';
$lang['Confirm_user_deleted'] = 'Are you sure you want to delete the selected user(s)?';

$lang['User_status_updated'] = 'User(s) status updated successfully!';
$lang['User_banned_successfully'] = 'User(s) banned successfully!';
$lang['User_deleted_successfully'] = 'User(s) deleted successfully!';
$lang['User_add_group_successfully'] = 'User(s) added to group successfully!';

$lang['Click_return_userlist'] = 'Click %shere%s to return to the User List';
//
// Admin Userlist End

//
// Version Check
//
$lang['Version_up_to_date'] = 'Your installation is up to date, no updates are available for your version of phpBB.';
$lang['Version_up_to_date_ip'] = 'No updates are available for your version of Icy Phoenix';
$lang['Version_not_up_to_date'] = 'Your installation does <b>not</b> seem to be up to date. Updates are available for your version of phpBB, please visit <a href="http://www.phpbb.com/downloads.php" target="_new">http://www.phpbb.com/downloads.php</a> to obtain the latest version.';
$lang['Version_not_up_to_date_ip'] = 'Updates are available for your version of Icy Phoenix, please visit <a href="http://www.icyphoenix.com/" target="_new">Icy Phoenix</a> to obtain the latest version.';
$lang['Latest_version_info'] = 'The latest available version is <b>phpBB %s</b>.';
$lang['Current_version_info'] = 'You are running <b>phpBB %s</b>.';
$lang['Connect_socket_error'] = 'Unable to open connection to phpBB Server, reported error is:<br />%s';
$lang['Connect_socket_error_ip'] = 'Unable to open connection to Icy Phoenix Server';
$lang['Socket_functions_disabled'] = 'Unable to use socket functions.';
$lang['Mailing_list_subscribe_reminder'] = 'For the latest information on updates to phpBB, why not <a href="http://www.phpbb.com/support/" target="_new">subscribe to our mailing list</a>.';
$lang['Version_information'] = 'Version Information';
$lang['Version_not_checked'] = 'Version checking is currently disabled, please visit Icy Phoenix support forum for information about new Icy Phoenix versions.';

// Advanced Signature Divider Control
$lang['sig_title'] = 'Advanced Signature Divider Control';
$lang['sig_divider'] = 'Current Signature Divider';
$lang['sig_explain'] = 'Control what divides the user\'s signature from their post';

//
// That's all Folks!
// -------------------------------------------------

// Start add - Birthday MOD
$lang['Birthday_required'] = 'Force users to submit a birthday';
$lang['Enable_birthday_greeting'] = 'Enable birthday greetings';
$lang['Birthday_greeting_expain'] = 'Enable Users who have submitted a date of birth to have a birthday greeting when they visit the board';
$lang['Next_birthday_greeting'] = 'Next birthday popup year';
$lang['Next_birthday_greeting_expain'] = 'This field keeps track of the next year the user will have a birthday greeting';
$lang['Wrong_next_birthday_greeting'] = 'The supplied, next birthday popup year, was not valid, please try again';
$lang['Max_user_age'] = 'Maximum user age';
$lang['Min_user_age'] = 'Minimum user age';
$lang['Birthday_lookforward'] = 'Birthday look forward';
$lang['Birthday_lookforward_explain'] = 'Number of days the script should look forward to for users with a birthday';
// End add - Birthday MOD


// Start add - Yellow card admin MOD
$lang['Max_user_bancard'] = 'Maximum number of warnings';
$lang['Max_user_bancard_explain'] = 'If a user gets more yellow cards than this limit, the user will be banned';
$lang['ban_card'] = 'Yellow card';
$lang['ban_card_explain'] = 'The user will be banned when he/she is in excess of %d yellow cards';
$lang['Greencard'] = 'Un-ban User';
$lang['Bluecard'] = 'Post Report';
$lang['Bluecard_limit'] = 'Interval of bluecard';
$lang['Bluecard_limit_explain'] = 'Notify the moderators again for every x bluecards given to a post';
$lang['Bluecard_limit_2'] = 'Limit of bluecard';
$lang['Bluecard_limit_2_explain'] = 'First notification to moderators is sent when a post gets this number of blue cards';
$lang['Report_forum'] = 'Report forum';
$lang['Report_forum_explain'] = 'Select the forum where users\' reports are to be posted. Users MUST have at least post/reply access to this forum';


// Start add - Last visit MOD
$lang['Hidde_last_logon'] = 'Hidden last logon time';
$lang['Hidde_last_logon_expain'] = 'If this is set to yes, users last logon time is visible only to administrators';
// End add - Last visit MOD
//
// Start add - Online/Offline/Hidden Mod
$lang['Online_time'] = 'Online status time';
$lang['Online_time_explain'] = 'Number of seconds a user must be displayed online (do not use a lower value than 60).';
$lang['Online_setting'] = 'Online Status Setting';
$lang['Online_color'] = 'Online text colour';
$lang['Offline_color'] = 'Offline text colour';
$lang['Hidden_color'] = 'Hidden text colour';
// End add - Online/Offline/Hidden Mod

// Disallow other admins to delete or edit the first admin MOD
$lang['L_LISTOFADMINEDIT'] = 'Blocked access to the account of the first admin';
$lang['L_LISTOFADMINEDITEXP'] = 'This is a list of blocked access for the account of the first admin of the forum. Other admins have tried to change, delete or set him to a normal user. No conversion of the profile data and/or the permissions of the first Admin took place and were successfully blocked. This list can be cleared only by the first admin of the forum.';
$lang['L_LISTOFADMINEDITUSERS'] = 'List of blocked access for the first admin account';
$lang['L_LISTOFADMINTEXT'] = 'Successfully blocked access took place through';
$lang['L_DELETEMSG'] = 'Delete entries';
$lang['L_DELETESUCMSG'] = 'The entries were deleted successfully';
$lang['L_ADMINEDITMSG'] = 'You do not have permission to edit the profile data and/or the permissions of the first admin of the forum.<br /><br />This unauthorised access attempt was successfully blocked and recorded!';
// Begin Thanks Mod
$lang['use_thank'] = 'Enable Thanks';
// End Thanks Mod
// Default avatar MOD, By Manipe (Begin)
$lang['Default_avatar'] = 'Set a default avatar';
$lang['Default_avatar_explain'] = 'This gives users that haven\'t yet selected an avatar, a default one. Set the default avatar for guests and users, and then select whether you want the avatar to be displayed for registered users, guests, both or none.';
$lang['Default_avatar_guests'] = 'Guests';
$lang['Default_avatar_users'] = 'Users';
$lang['Default_avatar_both'] = 'Both';
$lang['Default_avatar_none'] = 'None';
// Default avatar MOD, By Manipe (End)


// Start Optimize Database

$lang['Optimize'] = 'Optimize';
$lang['Optimize_explain'] = 'Optimize and remove empty spaces from the database.<br />This operation should be performed regularly for maximum reliability, speed and execution.';
$lang['Optimize_DB'] = 'Optimize Database';
$lang['Optimize_Enable_cron'] = 'Enable Cron';
$lang['Optimize_Cron_every'] = 'Cron Every';
$lang['Optimize_month'] = 'Month';
$lang['Optimize_2weeks'] = '2 weeks';
$lang['Optimize_week'] = 'Week';
$lang['Optimize_3days'] = '3 days';
$lang['Optimize_day'] = 'Day';
$lang['Optimize_6hours'] = '6 hours';
$lang['Optimize_hour'] = 'Hour';
$lang['Optimize_30minutes'] = '30 minutes';
$lang['Optimize_20seconds'] = '20 seconds (only for test)';
$lang['Optimize_Current_time'] = 'Current Time';
$lang['Optimize_Next_cron_action'] = 'Next Cron Action';
$lang['Optimize_Performed_Cron'] = 'Performed Cron';
$lang['Optimize_Show_not_optimized'] = 'Show only tables not optimized';
$lang['Optimize_Show_begin_for'] = 'Show only tables that begin with';
$lang['Optimize_Configure'] = 'Configure';
$lang['Optimize_Table'] = 'Table';
$lang['Optimize_Record'] = 'Record';
$lang['Optimize_Type'] = 'Type';
$lang['Optimize_Size'] = 'Size';
$lang['Optimize_Status'] = 'Status';
$lang['Optimize_CheckAll'] = 'Check All';
$lang['Optimize_UncheckAll'] = 'Uncheck All';
$lang['Optimize_InvertChecked'] = 'Invert Checked';
$lang['Optimize_return'] = 'Click %sHere%s to return to the Optimize Database';
$lang['Optimize_success'] = 'The Database has been successfully optimized';
$lang['Optimize_NoTableChecked'] = '<b>No</b> Tables Checked';

// End Optimize Database
// Start add - Global announcement MOD
$lang['Globalannounce'] = 'Global Announce';
// End add - Global announcement MOD


//
// google bot detector by pukapuka
//
$lang['Detector'] = 'Google Bot Detector';
$lang['Detector_Explain'] = '';
$lang['Detector_ID'] = '#';
$lang['Detector_Time'] = 'Time';
$lang['Detector_Url'] = 'Url';
$lang['Detector_Clear'] = 'Clear All';
$lang['Detector_No_Bot'] = 'No Bot Detected';
$lang['Detector_Cleared'] = 'Detect Information Cleared Successfully';
$lang['Click_Return_Detector'] = 'Click %sHere%s to return to Detector';

// added to Auto group mod
$lang['group_count'] = 'Number of required posts';
$lang['group_count_max'] = 'Number of max posts';
$lang['group_count_updated'] = '%d member(s) have been removed, %d members are added to this group';
$lang['Group_count_enable'] = 'Users automatically added when posting';
$lang['Group_count_update'] = 'Add/Update new users';
$lang['Group_count_delete'] = 'Delete/Update old users';
$lang['User_allow_ag'] = 'Activate Auto Group';
$lang['group_count_explain'] = 'When users have posted more posts than this value <i>(in any forum)</i> then they will be added to this usergroup<br/> This only applies if "' . $lang['Group_count_enable'] . '" are enabled';

// Start add - Bin Mod
$lang['Bin_forum'] = 'Bin forum';
$lang['Bin_forum_explain'] = 'Use the forum ID to where topics will be trashed; a value of 0 will disable this feature. You should edit this forum permissions to allow or not allow access/view/post or reply for users.';
// End add - Bin Mod

// Begin Quick Title Edition Mod 1.0.0 by Xavier Olive.
$lang['Title_infos'] = 'Quick Title Edition Management';
$lang['Must_select_title'] = 'You must select a quick title add-on';
$lang['Title_updated'] = 'Quick Title Add-on updated';
$lang['Title_added'] = 'Quick Title Add-on added';
$lang['Click_return_titleadmin'] = 'Click %sHere%s to return to Quick Title Management';
$lang['Title_removed'] = 'Quick Title Add-on removed';
$lang['Quick_title_explain'] = 'You can create short bits of text which you will be able to add to the title of a topic, by pushing a single button.<br />If you want the name of the person who performed the action of modifying the title to be shown, just put %mod% where you want it to be placed. For instance, [Link OK | checked by %mod%] will be displayed as [Link OK |checked by ModeratorName]. You can insert the date in the same way by placing %date% wherever you want it to appear.';
$lang['Title_head'] = 'Quick Title Add-on';
$lang['Title_auth'] = 'Permissions';
$lang['Administrator'] = 'Administrator';
$lang['Topic_poster'] = 'Topic poster';
$lang['Add_new_title_info'] = 'Add a Quick Title Add-on';
$lang['Title_perm_info'] = 'Permissions';
$lang['Title_perm_info_explain'] = 'Users with these levels will be able to use this Quick Title Add-on';
$lang['Title_info'] = 'Quick Title Add-on';
// End Quick Title Edition Mod 1.0.0 by Xavier Olive.

// Limit Image Width MOD
$lang['Available'] = 'Available';
$lang['Unavailable'] = 'Unavailable';
$lang['LIW_title'] = 'Limit Image Width MOD';
$lang['LIW_admin_explain'] = 'Switch the Limit Image Width MOD on and off and set the maximum width for each image posted in your forum. If you feel that the SQL table holding the cached images for your forum is getting too large, you can empty it by clicking the \'Empty cache table\' button.<br /><br />The \'Compatibility check\' box below indicates whether or not this MOD will function with your server.';
$lang['LIW_compatibility_checks'] = 'Compatibility checking';
$lang['LIW_mod_config'] = 'MOD Configuration';

$lang['LIW_config_updated'] = 'The Limit Image Width MOD configuration has been successfully updated';
$lang['LIW_cache_emptied'] = 'The cache table has been successfully emptied';
$lang['LIW_click_return_config'] = 'Click %shere%s to return the Limit Image Width MOD configuration page';

$lang['LIW_getimagesize'] = 'getimagesize() URL support';
$lang['LIW_getimagesize_explain'] = 'Available in PHP 4.0.5';
$lang['LIW_getimagesize_available'] = 'The MOD is able to retrieve image dimensions';
$lang['LIW_getimagesize_unavailable'] = 'The MOD cannot check whether or not an image is too large, and therefore will resize <i>any</i> posted image';

$lang['LIW_urlaware'] = 'URL-aware fopen wrappers';
$lang['LIW_urlaware_explain'] = 'Set allow_url_fopen to Yes in your php.ini';
$lang['LIW_urlaware_available'] = 'The MOD is able to generate a fingerprint for images so it is able to cache their dimensions';
$lang['LIW_urlaware_unavailable'] = 'The MOD cannot generate a fingerprint of the images, and is therefore unable to cache their dimensions';

$lang['LIW_openssl'] = 'openSSL extension loaded';
$lang['LIW_openssl_explain'] = 'Load the openssl.dll extension in your php.ini';
$lang['LIW_openssl_available'] = 'The MOD in able to retrieve dimensions from https:// images so is able to cache them';
$lang['LIW_openssl_unavailable'] = 'The MOD in unable to retrieve dimensions from https:// images so it is unable to cache them';

$lang['LIW_enable'] = 'Resize images in posts';
$lang['LIW_enable_explain'] = 'Set to %s to allow resizing of images in posts'; // Set to $lang['Yes'] to ....
$lang['LIW_sig_enable'] = 'Resize images in signatures';
$lang['LIW_sig_enable_explain'] = 'Set to %s to allow resizing of images in signatures';
$lang['LIW_attach_enable'] = 'Resize attached images';
$lang['LIW_attach_enable_explain'] = 'Set to %s to allow resizing of images which are attached to a post using the Attachment MOD';
$lang['LIW_max_width'] = 'Maximum image width';
$lang['LIW_max_width_explain'] = 'Specify the maximum width (in pixels) for an image posted using the [img] tags';
$lang['LIW_empty_cache'] = 'Empty image dimensions cache';
$lang['LIW_empty_cache_explain'] = 'Your cache table currently contains <b>%s</b> records'; // Your cache table currently contains <b>312</b> records
$lang['LIW_empty_cache_note'] = '<b>Note:</b> Emptying the cache table will result in re-caching of all image dimensions, which could result in a temporary slowdown when loading a topic';
$lang['LIW_empty_cache_button'] = 'Empty cache table';

// News
$lang['xs_news_settings'] = 'News Settings';
$lang['xs_news_show'] = 'Display News Banner?';
$lang['xs_news_show_ticker'] = 'Display News Ticker?';
$lang['xs_news_show_ticker_explain'] = 'This is a master switch. Setting this to \'No\' will stop any ticker from being shown, if set to \'Yes\' then the display state of each ticker can be controlled from their individual settings.';
$lang['xs_news_show_ticker_subtitle'] = 'Display Ticker subtitle?';
$lang['xs_news_show_ticker_subtitle_explain'] = 'Setting this to Yes will display \'News Tickers\' above the news tickers.';
$lang['xs_news_show_news_subtitle'] = 'Display News subtitle?';
$lang['xs_news_show_news_subtitle_explain'] = 'Setting this to Yes will display \'News Items\' above the news items.';
$lang['xs_news_dateformat'] = 'Date Format';
$lang['xs_news_dateformat_helper'] = 'Using this format: %s';

// Bantron Mod : Begin
$lang['Bantron'] = 'Bantron';
$lang['BM_Title'] = 'Bantron';
$lang['BM_Explain'] = 'Add, edit, view and remove the bans in place on this board.';

$lang['BM_Show_bans_by'] = 'Show bans by';
$lang['BM_All'] = 'All';
$lang['BM_Show'] = 'Show';

$lang['BM_IP'] = 'IP';
$lang['BM_Last_visit'] = 'Last Visit';
$lang['BM_Banned'] = 'Banned';
$lang['BM_Expires'] = 'Expires';
$lang['BM_By'] = 'By';
$lang['BM_Reasons'] = 'Reasons';

$lang['BM_Add_a_new_ban'] = 'Add a new ban';
$lang['BM_Delete_selected_bans'] = 'Delete selected bans';

$lang['BM_Private_reason'] = 'Private reason';
$lang['BM_Private_reason_explain'] = 'This reason for banning the entered usernames, e-mails, and/or IP addresses is only
for the administrators purpose.';

$lang['BM_Public_reason'] = 'Public reason';
$lang['BM_Public_reason_explain'] = 'This reason for banning the entered usernames, e-mails, and/or IP addresses is displayed to the banned user(s) when they attempt to access the forums.';
$lang['BM_Generic_reason'] = 'Generic reason';
$lang['BM_Mirror_private_reason'] = 'Mirror private reason';
$lang['BM_Other'] = 'Other';

$lang['BM_Expire_time'] = 'Expire time';
$lang['BM_Expire_time_explain'] = 'By specifying a date, either in relation to the current date or an absolute date, the ban will become inactive after that point in time.';
$lang['BM_Never'] = 'Never';
$lang['BM_After_specified_length_of_time'] = 'After specified length of time';
$lang['BM_Minutes'] = 'Minute(s)';
$lang['BM_Hours'] = 'Hour(s)';
$lang['BM_Days'] = 'Day(s)';
$lang['BM_Weeks'] = 'Week(s)';
$lang['BM_Months'] = 'Month(s)';
$lang['BM_Years'] = 'Year(s)';
$lang['BM_After_specified_date'] = 'After specified date';
$lang['BM_AM'] = 'AM';
$lang['BM_PM'] = 'PM';
$lang['BM_24_hour'] = '24-Hour';

$lang['BM_Ban_reasons'] = 'Ban Reasons';
// Bantron Mod : End

$lang['board_disable_message'] = 'Display a message for the deactivation of the site.';
$lang['board_disable_message_texte'] = 'Message which will appear when the site is deactivated';

// Start Edit Notes MOD
$lang['Edit_notes_settings'] = 'Edit Notes Settings';
$lang['Edit_notes_enable'] = 'Enable edit notes';
$lang['Edit_notes_enable_explain'] = 'Enable/disable edit notes on the board';
$lang['Max_edit_notes'] = 'Maximum edit notes';
$lang['Max_edit_notes_explain'] = 'Set the maximum number of edit notes per post.';
$lang['Edit_notes_permissions'] = 'Edit notes permissions';
$lang['Edit_notes_permissions_explain'] = 'Set which types of users may use the edit notes.';
$lang['Edit_notes_admin'] = 'Administrators only';
$lang['Edit_notes_staff'] = 'Staff (admins and mods)';
$lang['Edit_notes_reg'] = 'Registered users (admins and mods too)';
$lang['Edit_notes_all'] = 'All users (guests, registered users, admins and mods)';
// End Edit Notes MOD

// BEGIN Disable Registration MOD
$lang['registration_status'] = 'Disable registrations';
$lang['registration_status_explain'] = 'This will disable all new registrations to your board.';
$lang['registration_closed'] = 'Reason for closed registrations';
$lang['registration_closed_explain'] = 'A message that explains why registrations are closed, and appears if a user tries to register. Leave blank to show default explanation text.';
// END Disable Registration MOD

$lang['Gender_required'] = 'Force users to submit their gender';

//admin user list mail
$lang['Usersname'] = 'Users Name';
$lang['Admin_Users_List_Mail_Title'] = 'List users e-mail';
$lang['Admin_Users_List_Mail_Explain'] = 'Here a list of your users\'s e-mail';

// Start add - Forum notification MOD
$lang['Forum_notify'] = 'Allow forum notification';
$lang['Forum_notify_enabled'] = 'Allow';
$lang['Forum_notify_disabled'] = 'Do not allow';
// End add - Forum notification MOD

$lang['Smilie_table_columns'] = 'Smileys table columns';
$lang['Smilie_table_rows'] = 'Smileys table rows';
$lang['Smilie_window_columns'] = 'Smileys window columns';
$lang['Smilie_window_rows'] = 'Smileys window rows';
$lang['Smilie_single_row'] = 'Smileys single row number<br /> (I.E.: Quick Reply smileys number)';

$lang['Auth_Rating'] = 'Ratings';

// Gravatars
$lang['Enable_gravatars'] = 'Enable gravatars';
$lang['Gravatar_rating'] = 'Gravatar maximum rating';
$lang['Gravatar_rating_explain'] = '<a href="http://www.gravatar.com/rating.php" target="_blank">Read the rating guidelines</a> for more information. Set to \'None\' for no restriction.';
$lang['Gravatar_default_image'] = 'Gravatar default image';
$lang['Gravatar_default_image_explain'] = 'If no gravatar is found, the server will return this image. Path to the image is relative to the Icy Phoenix root directory. Leave blank for no image.';

// Admin Account Actions
$lang['Account_actions'] = 'Account Actions';
$lang['Account_inactive_explain'] = 'Users who are inactive, waiting activation or deletion, each with links to edit their permissions and/or profile.';
$lang['Account_active_explain'] = 'Active members that can be deactivated, deleted or their permissions and/or profile can be edited.';
$lang['Account_active'] = 'Active Users';
$lang['Account_inactive'] = 'Inactive Users';
$lang['Account_activate'] = 'Activate marked';
$lang['Account_deactivate'] = 'Deactivate marked';
$lang['Account_none'] = 'There is no user(s) waiting for activation.';
$lang['Account_total_user'] = 'User: <b>%d</b> user';
$lang['Account_total_users'] = 'Number of users: <b>%d</b> users';
$lang['Account_activation'] = 'Activation method';
$lang['Account_awaits'] = 'Awaiting activation since';
$lang['Account_registered'] = 'Registered since';
$lang['Account_delete_users'] = 'Are you sure you want to delete these users?';
$lang['Account_delete_user'] = 'Are you sure you want to delete this user?';
$lang['Account_sort_letter'] = 'Show only accounts starting with';
$lang['Account_others'] = 'others';
$lang['Account_all'] = 'all';
$lang['Account_year'] = 'year';
$lang['Account_years'] = 'years';
$lang['Account_week'] = 'week';
$lang['Account_weeks'] = 'weeks';
$lang['Account_day'] = 'day';
$lang['Account_days'] = 'days';
$lang['Account_hour'] = 'hour';
$lang['Account_hours'] = 'hours';
$lang['Account_user_activated'] = 'The user is activated.';
$lang['Account_users_activated'] = 'The users are activated.';
$lang['Account_user_deactivated'] = 'The user is deactivated.';
$lang['Account_users_deactivated'] = 'The users are deactivated.';
$lang['Account_user_deleted'] = 'The user is deleted.';
$lang['Account_users_deleted'] = 'The users are deleted.';
$lang['Account_activated'] = 'Account activation';
$lang['Account_activated_text'] = 'Your account was activated';
$lang['Account_deactivated'] = 'Account deactivation';
$lang['Account_deactivated_text'] = 'Your account was deactivated';
$lang['Account_deleted'] = 'Account deletion';
$lang['Account_deleted_text'] = 'Your account was deleted';
$lang['Account_notification'] = 'Notification email sent.';

// Acronyms
$lang['Acronyms_title'] = 'Acronyms Administration';
$lang['Acronyms_explain'] = 'Add, edit and remove acronyms that will be automatically added to posts on your forums.';
$lang['Acronym'] = 'Acronym';
$lang['Acronyms'] = 'Acronyms';
$lang['Edit_acronym'] = 'Edit Acronym';
$lang['Description'] = 'Description';
$lang['Add_new_acronym'] = 'Add new acronym';
$lang['Update_acronym'] = 'Update acronym';

$lang['Must_enter_acronym'] = 'You must enter an acronym and its description';
$lang['No_acronym_selected'] = 'No acronym selected for editing';

$lang['Acronym_updated'] = 'The selected acronym has been successfully updated';
$lang['Acronym_added'] = 'The acronym has been successfully added';
$lang['Acronym_removed'] = 'The selected acronym has been successfully removed';

$lang['Click_return_acronymadmin'] = 'Click %sHere%s to return to Acronym Administration';
$lang['Prune_shouts'] = 'Auto prune shouts';
$lang['Prune_shouts_explain'] = 'Number of days before the shouts are deleted. If the value is set to 0, autoprune will be disabled';

$lang['MOD_OS_ForumRules'] = 'Olympus-Style Forum Rules';
$lang['Forum_rules'] = 'Forum Rules';
$lang['Rules_display_title'] = 'Display title in the Forum Rules BOX?';
$lang['Rules_custom_title'] = 'Custom title';
$lang['Rules_appear_in'] = 'These Forum Rules appear while ...';
$lang['Rules_in_viewforum'] = 'Viewing this forum';
$lang['Rules_in_viewtopic'] = 'Viewing topics in this forum';
$lang['Rules_in_posting'] = 'Posting/Replying in this forum';

$lang['Php_Info_Explain'] = 'This page lists information on the version of PHP installed on this server. It includes details of loaded modules, available variables and default settings. This information may be useful when diagnosing problems. Please be aware that some hosting companies will limit what information is displayed here for security reasons. You are advised to not give out any details on this page except when asked for by support or other Team Member on the support forums.';

//XS2 Forum Header
$lang['IcyPhoenix_Main'] = 'Icy Phoenix Home Page';
$lang['IcyPhoenix_Download'] = 'Icy Phoenix Download';
$lang['IcyPhoenix_Code_Changes'] = 'Code Changes Mod';
$lang['IcyPhoenix_Updates'] = 'Icy Phoenix Updates';
$lang['PhpBB_Upgrade'] = 'phpBB Upgrade';
$lang['Header_Welcome'] = 'Welcome on Icy Phoenix Administration Control Panel';

$lang['Prune_users'] = 'Prune users';
$lang['Prune_Overview'] = 'Pruning Overview';
$lang['Prune_title_explain'] = 'Manage the pruning Settings of each Forum.';
$lang['Prune_forum'] = 'Forum';
$lang['Prune_active'] = 'Pruning active';
$lang['Prune_freq'] = 'Remove all';
$lang['Prune_check'] = 'Check all';
$lang['Prune_days'] = 'Days';
$lang['Prune_days_explain'] = '* Remove topics that have not been posted to.';
$lang['Click_return_admin_po'] = '%sClick here%s, to return to Pruning Overview';
$lang['Prune_update'] = 'The Prune Settings was successfully updated';

$lang['Admin_notepad_title'] = 'Notepad';
$lang['Admin_notepad_explain'] = 'Leave global memos for other Administrators.';
$lang['Allow_generator'] = 'Enable avatar generator';
$lang['Avatar_generator_template_path'] = 'Avatar Generator Template Path';
$lang['Avatar_generator_template_path_explain'] = 'Path under your Icy Phoenix root dir for template images, e.g. images/avatars/generator_templates';

// Start Autolinks Mod
$lang['Autolink_first'] = 'Autolink each keyword once per post';

$lang['Autolinks_title'] = 'Autolinks';
$lang['Autolinks_explain'] = 'Add, edit and remove keywords that will be automatically replaced with the url in the message posts. If the url you enter is an internal one and points to the forum/portal, ticking the box will have the session ID added to the link.<br /><br />eg. If found, the <b>Keyword</b> in the post will be replaced with the following,<br /><br />&lt;a href="<b>Url</b>" title="<b>Comment</b>" style="<b>Style</b>"&gt;<b>Text</b>&lt;/a&gt;';
$lang['links_keyword'] = 'Keyword';
$lang['links_title'] = 'Text';
$lang['links_url'] = 'Url';
$lang['links_comment'] = 'Comment';
$lang['links_style'] = 'Style';
$lang['links_forum'] = 'Forum for Autolink';
$lang['links_forum2'] = 'Forum';
$lang['links_internal'] = 'Internal';
$lang['Autolinks_add'] = 'Add an Autolink';
$lang['Add_keyword'] = 'Add Autolink';
$lang['Autolinks_edit'] = 'Edit an Autolink';
$lang['Edit_keyword'] = 'Edit Autolink';
//$lang['Delete_link'] = 'Tick box to delete this autolink.';

$lang['Select_all_forums'] = 'All Forums';
$lang['Autolink_added'] = 'Autolink successfully added.';
$lang['Autolink_updated'] = 'Autolink successfully updated.';
$lang['Autolink_removed'] = 'Autolink successfully deleted.';
$lang['No_autolink_selected'] = 'No autolink was selected for deletion.';
$lang['No_autolinks'] = 'There are no Autolinks in the database.';
$lang['Must_enter_autolink'] = 'You must enter a keyword, link text and a url.';
$lang['Click_return_autolinkadmin'] = 'Click %sHere%s to return to Autolink Administration';
// End Autolinks Mod

// XS BUILD 030

// Login attempts configuration
$lang['Max_login_attempts'] = 'Allowed login attempts';
$lang['Max_login_attempts_explain'] = 'The number of allowed board login attempts.';
$lang['Login_reset_time'] = 'Login lock time';
$lang['Login_reset_time_explain'] = 'Time in minutes the user has to wait until he/she is allowed to login again after exceeding the number of allowed login attempts.';

// XS BUILD 035
// Smilies Order
$lang['position_new_smilies'] = 'Should new smileys be added before or after existing smileys?';
$lang['smiley_change_position'] = 'Change Insert Location';
$lang['before'] = 'Before';
$lang['after'] = 'After';
$lang['Move_top'] = 'Send to Top';
$lang['Move_end'] = 'Send to End';

// XS BUILD 037
// Pages Auth
$lang['auth_view_title'] = 'Page View Auth';
$lang['auth_view_portal'] = 'Home Page';
$lang['auth_view_forum'] = 'Forum';
$lang['auth_view_viewf'] = 'View Forum';
$lang['auth_view_viewt'] = 'View Topic';
$lang['auth_view_faq'] = 'FAQ';
$lang['auth_view_memberlist'] = 'Memberlist';
$lang['auth_view_group_cp'] = 'Usergroups';
$lang['auth_view_profile'] = 'Profile';
$lang['auth_view_search'] = 'Search';
$lang['auth_view_album'] = 'Album';
$lang['auth_view_links'] = 'Links';
$lang['auth_view_calendar'] = 'Calendar';
$lang['auth_view_attachments'] = 'Attachments';
$lang['auth_view_download'] = 'Downloads';
$lang['auth_view_pic_upload'] = 'Pics Upload (Post Icy Images)';
$lang['auth_view_kb'] = 'Knowledge Base';
$lang['auth_view_ranks'] = 'Ranks';
$lang['auth_view_statistics'] = 'Statistics';
$lang['auth_view_recent'] = 'Recent Topics';
$lang['auth_view_referrers'] = 'Referrers';
$lang['auth_view_rules'] = 'Rules';
$lang['auth_view_site_hist'] = 'Site History';
$lang['auth_view_shoutbox'] = 'Shoutbox';
$lang['auth_view_viewonline'] = 'View Online';
$lang['auth_view_contact_us'] = 'Contact Us';
$lang['auth_view_ajax_chat'] = 'Chat';
$lang['auth_view_ajax_chat_archive'] = 'Chat Archive';
$lang['auth_view_custom_pages'] = 'Custom Pages';

// XS BUILD 041
// Begin Yahoo Submit Your Site MOD by www.pentapenguin.com
$lang['Yahoo_search'] = 'Yahoo Search';
$lang['Yahoo_search_settings'] = 'Yahoo Search Settings';
$lang['Yahoo_search_settings_explain'] = 'Configure settings for the Yahoo Submit Your Site MOD. For more info, please see the <a href="http://submit.search.yahoo.com/free/request" target="_blank">Yahoo Submit Your Site website</a>.';
$lang['Yahoo_search_select_forums'] = 'Select Forums';
$lang['Yahoo_search_select_forums_explain'] = 'Select which forums to include in this list. You may select as many forums as you wish by clicking each forum name while holding down the Ctrl Key (Windows) or the Command Key (Macintosh). By default, all publicly viewable forums are selected.';
$lang['Yahoo_search_savepath'] = 'Save path for the URL list';
$lang['Yahoo_search_savepath_explain'] = 'Enter a location for the URL listing file to be saved. Enter the path relative to the Icy Phoenix base path - i.e. if you save the file in the cache directory in <b>www.yoursite.com/cache/</b>, then enter <b>cache</b>. Remember that you will need to CHMOD the directory to 755 or 777 as applicable to your server.';
$lang['Yahoo_search_additional_urls'] = 'Enter additional URLs';
$lang['Yahoo_search_additional_urls_explain'] = 'Enter additional URLs you would like Yahoo to crawl on one per line. You must enter the full URL - i.e. <b>http://www.yoursite.com/yourpage.html</b>.';
$lang['Yahoo_search_compress_file'] = 'Compress the list of URLs';
$lang['Yahoo_search_compress_file_explain'] = 'If you choose \'Yes\' to this option and your server has Gzip support, the list of URLs will be compressed with Gzip which will result in a much smaller file thus less bandwidth from the Yahoo bot.';
$lang['Yahoo_search_compression_level'] = 'Compression level for file';
$lang['Yahoo_search_compression_level_explain'] = 'Choose a compression level for the file. 9 is the recommended setting unless you encounter problems. In that case you should make the value lower.';
$lang['Yahoo_search_generate_file'] = 'Generate File';
$lang['Yahoo_search_error_no_forums'] = 'Error: no forums selected. Go back and choose at least one forum.';
$lang['Yahoo_search_error_no_gzip'] = 'Error: either you are using an old version of PHP or your web host does not support Gzip. Please go back and choose <b>No</b> for the <b>Compress the list of URLs</b> option.';
$lang['Yahoo_search_error_unopenable_file'] = 'Error: cannot open file %s.';
$lang['Yahoo_search_error_unwritable_file'] = 'Error: cannot write to file %s.';
$lang['Yahoo_search_error_unclosable_file'] = 'Error: cannot close file %s.';
$lang['Yahoo_search_error_update_sql'] = 'Error: cannot update field: %s';
$lang['Yahoo_search_error_unknown_file_error'] = 'Error: the file was not saved due to an unknown error.';
$lang['Yahoo_search_file_done'] = 'The URL listing file has finished processing. Please copy the URL below and paste it in the appropriate field in Yahoo:<br /><b>%s</b>';
// Finish Yahoo Submit Your Site MOD by www.pentapenguin.com

// XS BUILD 044

// Bookmark Mod
$lang['Max_bookmarks_links'] = 'Maximum bookmarks send in link-tag';
$lang['Max_bookmarks_links_explain'] = 'Number of bookmarks to send in link-tag at the beginning of the document. This information is for example, used by Mozilla. Enter 0 to disable this function.';

$lang['Faq_manager'] = 'FAQ Manager';
$lang['Faq_Rules_manager'] = 'Faq &amp; Rules';
$lang['board_rules'] = 'Board Rules';
$lang['board_faq'] = 'Board Faq';
$lang['bbcode_faq'] = 'BBcode Faq';
$lang['attachment_faq'] = 'Attachment Faq';
$lang['prillian_faq'] = 'Prillian Faq';
$lang['bid_faq'] = 'Buddy List Faq';


$lang['Account_active2'] = 'Active Users';
$lang['Account_inactive2'] = 'Inactive Users';

// Search Flood Control - added 2.0.20
$lang['Search_Flood_Interval'] = 'Search Flood Interval';
$lang['Search_Flood_Interval_explain'] = 'Number of seconds a user must wait between search requests';
$lang['Confirm_delete_smiley'] = 'Are you sure you want to delete this Smiley?';
$lang['Confirm_delete_word'] = 'Are you sure you want to delete this word censor?';
$lang['Confirm_delete_rank'] = 'Are you sure you want to delete this rank?';

// Custom Profile Fields MOD
$lang['custom_field_notice_admin'] = 'These items have been created by you or another administrator. For more information, check the items under the Profile Fields heading in the navbar. Items marked with a * are required fields. Items marked with a &dagger; are only being displayed to admins.';

$lang['field_deleted'] = 'The specified field has been deleted';
$lang['double_check_delete'] = 'Are you sure you want to delete profile field "%s" from the database permanently?';

$lang['here'] = 'Here';
$lang['new_field_link'] = '<a href="' . append_sid($filename . '?mode=add&pfid=x') . '">%s</a>';
$lang['edit_field_link'] = '<a href="' . append_sid($filename . '?mode=edit&pfid=x') . '">%s</a>';
$lang['index_link'] = '<a href="' . append_sid('admin_profile_fields.' . PHP_EXT . '?mode=edit&pfid=x') . '">%s</a>';
$lang['field_exists'] = 'This field already exists.<br /><br />You can try creating a ' . sprintf($lang['new_field_link'],'new') . ' profile field,<br /><br />or try ' . sprintf($lang['edit_field_link'],'editing') . ' the one you already have.';
$lang['click_here_here'] = 'Click ' . sprintf($lang['new_field_link'],$lang['here']) . ' to add another profile field,<br /><br />or click ' . sprintf($lang['index_link'],$lang['here']) . ' to return to the Admin Index.';
$lang['field_success'] = 'Field successfully submitted!<br /><br />' . $lang['click_here_here'];
$lang['Custom_Profile'] = 'Profile Fields';
$lang['profile_field_created'] = 'Profile Field Created';
$lang['profile_field_updated'] = 'Profile Field Updated';

$lang['add_field_title'] = 'Add Custom Profile Fields';
$lang['edit_field_title'] = 'Edit Custom Profile Fields';
$lang['add_field_explain'] = 'Create new fields that your users can set in their profiles.';
$lang['edit_field_explain'] = 'Edit fields you have already created in your users profiles.';

$lang['add_field_general'] = 'General Settings';
$lang['add_field_admin'] = 'Administrator Settings';
$lang['add_field_view'] = 'Viewing Settings';
$lang['add_field_text_field'] = 'Text Field Settings';
$lang['add_field_text_area'] = 'Text Area Settings';
$lang['add_field_radio_button'] = 'Radio Button Settings';
$lang['add_field_checkbox'] = 'Checkbox Settings';

$lang['default_value'] = 'Default Value';
$lang['default_value_explain'] = 'This is the default for this field. If a new user does not change this value, this is the value they will have. If this is a required field, this is the value that all existing users will be set to.';
$lang['default_value_radio_explain'] = 'Enter a name identical to one written in the available values field.';
$lang['default_value_checkbox_explain'] = 'Enter values that will default to checked. These values must match values in the available values field';
$lang['max_length'] = 'Maximum Length';
$lang['max_length_explain'] = 'This is the maximum length for this field.';
$lang['max_length_value'] = ' This must be a number between %d and %d.';
$lang['available_values'] = 'Available Values';
$lang['available_values_explain'] = 'Put each value on its own line';

$lang['add_field_view_disclaimer'] = 'All of these settings will be treated as "no" if users are not allowed to view this field';

$lang['add_field_name'] = 'Field Name';
$lang['add_field_name_explain'] = 'Enter the name you want to associate with this field.';
$lang['add_field_description'] = 'Field Description';
$lang['add_field_description_explain'] = 'Enter a description you wish to associate with this field. It will be displayed in small text below the field name, just like this text is.';
$lang['add_field_type'] = 'Field Type';
$lang['add_field_type_explain'] = 'Select the type of profile field you want to add. Examples of each field type are shown to the far right.';
$lang['edit_field_type_explain'] = 'Select the type of profile field you want to change this field to. Examples of each field type are shown to the far right.';
$lang['add_field_required'] = 'Set as Required';
$lang['add_field_required_explain'] = 'If the field is set to "Required", any user that registers later <strong>must</strong> fill it in, and all existing users will have it filled with a default value.';
$lang['add_field_user_can_view'] = 'Allow Users to View';
$lang['add_field_user_can_view_explain'] = 'If this is set to "yes", the user is allowed to view and edit this field. If it is set to "no", only Administrators may view or edit this value. Also, if this is set to "no", this field cannot be required.';
$lang['view_in_profile'] = 'Viewable in User Profile';
$lang['profile_locations_explain'] = 'These options are for if this field is to be viewed in the user\'s profile.';
$lang['contacts_column'] = 'Contacts Column';
$lang['about_column'] = 'About Column';
$lang['view_in_memberlist'] = 'Viewable in Memberlist';
$lang['view_in_topic'] = 'Viewable in Topic';
$lang['topic_locations_explain'] = 'These options are only if this field is to be viewed in a post.';
$lang['author_column'] = 'Author Section';
$lang['above'] = 'Above ';
$lang['below'] = 'Below ';

$lang['textarea'] = 'Textarea';
$lang['textarea_example'] = "Example of a scrollable\n Textarea.";
$lang['text_field'] = 'Text Field';
$lang['text_field_example'] = 'Example of a Text Field';
$lang['radio'] = 'Radio Button';
$lang['radio_example'] = 'Example of two Radio Buttons';
$lang['checkbox'] = 'Checkbox';
$lang['checkbox_example'] = 'Example of two Checkboxes';

$lang['profile_field_list'] = 'Your Custom Profile Fields';
$lang['profile_field_list_explain'] = 'These are all of the custom profiles you have created for your board, with links to edit or delete them.';
$lang['profile_field_id'] = 'ID #';
$lang['profile_field_name'] = 'Field Name';
$lang['profile_field_action'] = 'Action';
$lang['no_profile_fields_exist'] = 'No Custom Profile Fields Exist.';

$lang['enter_a_name'] = 'You <strong>must</strong> enter a field name<br /><br />Press back and try again';
// END Custom Profile Fields MOD

// XS BUILD 045
$lang['Add'] = 'Add';
$lang['split_global_announce'] = 'Split Global Announcements';
$lang['split_announce'] = 'Split Announcements';
$lang['split_sticky'] = 'Split Stickies';
$lang['split_topic_split'] = 'Split Topics';
$lang['Announce_settings'] = 'Announcements Settings';
$lang['Split_settings'] = 'Split Settings';

// XS BUILD 046
//$lang['Server_Cookies'] = 'Server &amp; Cookies';
$lang['Server_Cookies'] = 'Server Settings';

// XS BUILD 050
$lang['Disable_Registration_IP_Check'] = 'Disable public blacklist IP check upon register';
$lang['Disable_Registration_IP_Check_Explain'] = 'If you disable IP check upon register, then IP\'s will not be checked against the public blacklist. Disabling this may be useful, because sometimes this check may result in unintentional blocking of regular users who have an IP listed by mistake in the public blacklist.';
$lang['Config_explain2'] = 'Customize calendar and subforums options, change appearance and settings.';
$lang['Forum_postcount'] = 'Count user\'s posts';

// XS BUILD 057
$lang['Use_Captcha'] = 'Use CAPTCHA';
$lang['Use_Captcha_Explain'] = 'If set to YES, then advanced confirmation code is generated. If set to NO, standard activation code is generated.';
$lang['Sync_Pics_Count'] = 'Clicking <b>YES</b> all user(s) pics counter will be synchronized.';
$lang['Pics_Count_Synchronized'] = 'User\'s pics counters synchronized correctly';
$lang['Pics_Count_Not_Synchronized'] = 'User\'s pics counters not synchronized correctly';

// IP - BUILD 001
$lang['Enable_Digests'] = 'Enable Digests';
$lang['Enable_Digests_PHPCron'] = 'Enable Digests PHP Cron';
$lang['Enable_Digests_PHPCron_Explain'] = 'This feature will enable a PHP emulation of the CRON trying to send the emails once per hour, but since it is based on a PHP emulation it may not be correctly executed every time. This means that sometimes emails may not be sent. If you can enable CRON on your server, use CRON instead of this feature.';

// Ajax Shoutbox - BEGIN
$lang['Shoutbox_config'] = 'AJAX Shoutbox Configuration';
$lang['Shout_read_only'] = 'Read Only';
$lang['Displayed_shouts'] = 'Displayed Shouts';
$lang['Displayed_shouts_explain'] = 'Number of shouts that will be displayed when loading the shoutbox.<br /><i>0 to load all shouts.</i>';
$lang['Stored_shouts'] = 'Stored Shouts';
$lang['Stored_shouts_explain'] = 'Number of shouts that remain in the database.<br />This value should be equal or higher than the number of displayed shouts.<br /><i>0 to store all shouts.</i>';
$lang['Shout_guest_allowed'] = 'Guest Allowed';
$lang['Shoutbox_flood'] = 'Flood Interval';
$lang['Shoutbox_flood_explain'] = 'Number of seconds a user must wait between shouts.';
$lang['Shoutbox_refreshtime'] = 'Refresh rate';
$lang['Shoutbox_refresh_explain'] = 'Time in milliseconds for the shoutbox to read new messages.<br /><i>This value should be over the 1000 milliseconds.</i>';
// Ajax Shoutbox - END

//
// ####################### [ Icy Phoenix Options BEGIN ] #####################
//
$lang['MG_Configuration'] = 'Icy Phoenix Settings';
$lang['MG_Configuration_Explain'] = '<em><b>Advanced Icy Phoenix Settings</b></em>';

$lang['MG_Configuration_Headers_Banners'] = 'Headers &amp; Banners';
$lang['MG_Configuration_Queries'] = 'SQL Optimization';
$lang['MG_Configuration_Permissions'] = 'Page Permissions';
$lang['MG_Configuration_Posting'] = 'Posting';
$lang['MG_SW_Precompiled_Posts_Title'] = 'Precompiled Posts';
$lang['MG_SW_Logins_Title'] = 'Logins Recording';
$lang['MG_SW_Edit_Notes_Title'] = 'Edit Notes';
$lang['MG_Configuration_IMG_Posting'] = 'Images In Posting';

$lang['MG_SW_Top_Header_Bottom_Footer'] = 'Header and Footer HTML Blocks';
$lang['MG_SW_Top_HTML_Block'] = 'HTML Header Block';
$lang['MG_SW_Top_HTML_Block_Explain'] = 'Enabling this option shows the specified HTML code on top of each page.';
$lang['MG_SW_Top_HTML_Block_Text'] = 'Insert the HTML code for the header.';
$lang['MG_SW_Bottom_HTML_Block'] = 'HTML Footer Block';
$lang['MG_SW_Bottom_HTML_Block_Explain'] = 'Enabling this option shows the specified HTML code on the bottom of each page.';
$lang['MG_SW_Bottom_HTML_Block_Text'] = 'Insert the HTML code for the footer.';

$lang['MG_SW_Header_Footer'] = 'Header and Footer Messages';
$lang['MG_SW_Header_Table'] = 'Header Table';
$lang['MG_SW_Header_Table_Explain'] = 'Enabling this option shows a customised message in the header table of each page.';
$lang['MG_SW_Header_Table_Text'] = 'Insert your text here.';
$lang['MG_SW_Footer_Table'] = 'Footer Table';
$lang['MG_SW_Footer_Table_Explain'] = 'Enabling this option shows a customised message in the footer table of each page.';
$lang['MG_SW_Footer_Table_Text'] = 'Insert your text here.';

$lang['MG_SW_Banner_Title'] = 'Banner Management';
$lang['MG_SW_Header_Banner'] = 'Header Banner';
$lang['MG_SW_Header_Banner_Explain'] = 'Enabling this option shows a Header Banner on every page.';
$lang['MG_SW_Header_Banner_Code'] = 'Header Banner Code';
$lang['MG_SW_Header_Banner_Code_Explain'] = 'Insert your advertisement code for the Header here.';
$lang['MG_SW_Viewtopic_Banner'] = 'Viewtopic Banner';
$lang['MG_SW_Viewtopic_Banner_Explain'] = 'Enabling this option will show a banner after the first post on every topic page.';
$lang['MG_SW_Viewtopic_Banner_Code'] = 'Viewtopic Banner Code';
$lang['MG_SW_Viewtopic_Banner_Code_Explain'] = 'Insert your advertisement code for Viewtopic here.';

$lang['MG_SW_Empty_Precompiled_Posts'] = 'Empty precompiled posts';
$lang['MG_SW_Empty_Precompiled_Posts_Explain'] = 'Empty all precompiled posts.';
$lang['MG_SW_Empty_Precompiled_Posts_Success'] = 'Precompiled posts emptied correctly.';
$lang['MG_SW_Empty_Precompiled_Posts_Fail'] = 'Errors in emptying precompiled posts.';

$lang['MG_FNF_Header'] = 'Quick Settings';
$lang['MG_FNF_Header_Explain'] = '<b>Configuration options for your site.</b><br /> These configuration packages have been created to easily allow users to mass change their settings without having to modify each option one by one in the configuration panel, and may be used as a starting point for future customizations: For example you can choose "Fast And Furious" and then modify only the options of this package that you don\'t want.<br /><br /><span class="text_red"><b>Please note! that once you have applied one of these set of options you cannot automatically restore your old settings, and you have to set them up again manually.</b></span>';
$lang['MG_FNF_Options_Set'] = 'Set Of Options';
$lang['MG_FNF_FNF'] = 'Fast And Furious';
$lang['MG_FNF_FNF_Explain'] = 'This set of options will increase the speed of your site, because most of the features which requires high CPU charge or DB access will be disabled. This package is ideal for wanting a very fast site.';
$lang['MG_FNF_MGS'] = 'Mighty Gorgon\'s Suggested';
$lang['MG_FNF_MGS_Explain'] = 'This set of options is balanced and a good starting point for most sites. Some options will be enabled while some others which requires high CPU loads will be switched off.';
$lang['MG_FNF_Full_Features'] = 'Full Features';
$lang['MG_FNF_Full_Features_Explain'] = 'This set of options could be enabled if you don\'t have bandwidth limit or if you like having all Icy Phoenix features enabled. Please note! that some of the features may not be compatible with your server.';

$lang['MG_SW_ACRONYMS'] = 'Disable acronyms';
$lang['MG_SW_ACRONYMS_Explain'] = 'Disable acronyms parsing?';
$lang['MG_SW_AUTOLINKS'] = 'Disable autolinks';
$lang['MG_SW_AUTOLINKS_Explain'] = 'Disable autolinks parsing?';
$lang['MG_SW_CENSOR'] = 'Disable word censor';
$lang['MG_SW_CENSOR_Explain'] = 'Disable word censor parsing?';

$lang['MG_SW_No_Right_Click'] = 'Block Right Click';

$lang['Click_return_config_mg'] = 'Click %sHere%s to return to Icy Phoenix Settings';

/*
$lang['MG_SW_'] = '';
*/
//
// ####################### [ Icy Phoenix Options END ] #######################
//

/* lang_postcount.php - BEGIN */
$lang['Postcounts'] = 'Post Counts Management';
$lang['Post_count_explain'] = '<b>Edit the post count of a single user.</b>';
$lang['Modify_post_counts'] = 'Modify Post Counts';
$lang['Post_count_changed'] = 'Success! You have edited a user\'s post count!';
$lang['Click_return_posts_config'] = 'Click %sHere%s to return to the post count configuration';
$lang['Modify_post_count'] = 'Modify post count';
$lang['Edit_post_count'] = 'Edit the post count for <b>%s</b>';
$lang['Post_count'] = 'Number Of Messages';
/* lang_postcount.php - END */

/* lang_megamail.php - BEGIN */
$lang['Megamail_Explain'] = 'Email a message to either all of your users, or all users of a specific group. To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients.<br />
This modified script will send the emails in several batches. This should circumvent timeout and server-load issues. The status of the mass mail sending will be saved in the db. You can close the window when you want to pause mass-mail-sending (the current batch will be sent out). You can simply continue later from where you left off.<br />
<b>If HTML emails are enabled, then you should write emails using HTML code, inserting &lt;br /&gt; for a line break.</b>';
$lang['megamail_header'] = 'Your Email-Sessions';
$lang['megamail_id'] = 'Mail-ID';
$lang['megamail_batchstart'] = 'Processed';
$lang['megamail_batchsize'] = 'Mails per Batch';
$lang['megamail_batchwait'] = 'Pause';
$lang['megamail_created_message'] = 'The Mass Mail has been saved to the database.<br /><br/> To start sending %sclick here%s or wait until the Meta-Refresh takes you there...';
$lang['megamail_send_message'] = 'The Current Batch (%s - %s) has been sent .<br /><br/> To continue sending %sclick here%s or wait until the Meta-Refresh takes you there...';
$lang['megamail_status'] = 'Status';
$lang['megamail_proceed'] = '%sProceed now%s';
$lang['megamail_done'] = 'DONE';
$lang['megamail_none'] = 'No records were found.';
/* lang_megamail.php - END */

/* lang_admin_voting.php - BEGIN */
$lang['Admin_Vote_Explain'] = 'Poll Results (who voted and how they voted).';
$lang['Admin_Vote_Title'] = 'Poll Administration';
$lang['Vote_id'] = '#';
$lang['Poll_topic'] = 'Poll Topic';
$lang['Vote_username'] = 'Voter(s)';
$lang['Vote_end_date'] = 'Vote Duration';
$lang['Sort_vote_id'] = 'Poll Number';
$lang['Sort_poll_topic'] = 'Poll Topic';
$lang['Sort_vote_start'] = 'Start Date';
$lang['Submit'] = 'Submit';
$lang['Select_sort_field'] = 'Select sort field';
$lang['Sort_ascending'] = 'Ascending';
$lang['Sort_descending'] = 'Descending';
/* lang_admin_voting.php - END */

/* lang_admin_gd_info.php - BEGIN */
$lang['GD_Title'] = 'GD Info';
$lang['NO_GD'] = 'No GD';
$lang['GD_Description'] = 'Retrieve information about the currently installed GD library';
$lang['GD_Freetype_Support'] = 'Freetype Fonts Support:';
$lang['GD_Freetype_Linkage'] = 'Freetype Link Type:';
$lang['GD_T1lib_Support'] = 'T1lib Support:';
$lang['GD_Gif_Read_Support'] = 'Gif Read Support:';
$lang['GD_Gif_Create_Support'] = 'Gif Create Support:';
$lang['GD_Jpg_Support'] = 'Jpg/Jpeg Support:';
$lang['GD_Png_Support'] = 'Png Support:';
$lang['GD_Wbmp_Support'] = 'WBMP Support:';
$lang['GD_XBM_Support'] = 'XBM Support:';
$lang['GD_Jis_Mapped_Support'] = 'Japanese Font Support:';
$lang['GD_True'] = 'Yes';
$lang['GD_False'] = 'No';
/* lang_admin_gd_info.php - END */

/* lang_admin_captcha.php - BEGIN */
$lang['VC_Captcha_Config'] = 'CAPTCHA';
$lang['captcha_config_explain'] = '<b>Determine the appearance of the picture used by visual confirmation when activated.</b>';
$lang['VC_active'] = 'Visual Confirmation is active!';
$lang['VC_inactive'] = 'Visual Confirmation is not active!';
$lang['background_configs'] = 'Background';
$lang['Click_return_captcha_config'] = 'Click %sHere%s to return to CAPTCHA Configuration';

$lang['CAPTCHA_width'] = 'CAPTCHA width';
$lang['CAPTCHA_height'] = 'CAPTCHA height';
$lang['background_color'] = 'Background colour';
$lang['background_color_explain'] = 'Indication in hexadecimal (eg. #0000FF for blue).';
$lang['pre_letters'] = 'Number of shade letters';
$lang['pre_letters_explain'] = '';
$lang['great_pre_letters'] = 'Shade letter increase';
$lang['great_pre_letters_explain'] = '';
$lang['Random'] = 'Random';
$lang['random_font_per_letter'] = 'Random font per letter';
$lang['random_font_per_letter_explain'] = 'Each letter uses a random font.';

$lang['back_chess'] = 'Chess sample';
$lang['back_chess_explain'] = 'Fill the complete background with 16 rectangles.';
$lang['back_ellipses'] = 'Ellipses';
$lang['back_arcs'] = 'Curved lines';
$lang['back_lines'] = 'Lines';
$lang['back_image'] = 'Background image';
$lang['back_image_explain'] = '(This function is not integrated yet)';

$lang['foreground_lattice'] = 'Foreground lattice';
$lang['foreground_lattice_explain'] = '(width x height)<br />Generate a white lattice over the CAPTCHA';
$lang['foreground_lattice_color'] = 'Lattice colour';
$lang['foreground_lattice_color_explain'] = $lang['background_color_explain'];
$lang['gammacorrect'] = 'Contrast correction';
$lang['gammacorrect_explain'] = '(0 = off)<br />NOTE!!! Changes of the value have direct effect on the legibility of the CAPTCHA!';
$lang['generate_jpeg'] = 'Image type';
$lang['generate_jpeg_explain'] = 'The JPEG format has a higher compression ratio than png has, and the outcome (max 95%), has a direct influence on the legibility of the CAPTCHA.';
$lang['generate_jpeg_quality'] = 'Quality';
/* lang_admin_captcha.php - END */

/* lang_admin_topic_shadow.php - BEGIN */
$lang['Del_Before_Date'] = 'Deleted all Shadow Topics before %s<br />'; // %s = insertion of date
$lang['Deleted_Topic'] = 'Deleted Shadow Topic %s<br />'; // %s = topic name
$lang['Affected_Rows'] = '%d known entries were affected<br />'; // %d = affected rows (not avail with all databases!)
$lang['Delete_From_Date'] = 'All Shadow Topics that were created before the entered date will be removed.';
$lang['Delete_Before_Date_Button'] = 'Delete All Before Date';
$lang['No_Shadow_Topics'] = 'No Shadow Topics were found.';
$lang['Topic_Shadow'] = 'Topic Shadow';
$lang['TS_Desc'] = '<b>Remove shadow topics without removing the actual message.</b><br /> Shadow topics are created when you move a post from one forum to another and choose to leave a link to the moved post.';
$lang['Month'] = 'Month';
$lang['Day'] = 'Day';
$lang['Year'] = 'Year';
$lang['Clear'] = 'Clear';
$lang['Resync_Ran_On'] = 'Resync Ran On %s<br />'; // %s = insertion of forum name
$lang['Version'] = 'Version';

$lang['Title'] = 'Title';
$lang['Moved_To'] = 'Moved To';
$lang['Moved_From'] = 'Moved From';

/* Modes */
$lang['topic_time'] = 'Topic Time';
$lang['topic_title'] = 'Topic Title';

/* Errors */
$lang['Error_Month'] = 'Your input month must be between 1 and 12';
$lang['Error_Day'] = 'Your input day must be between 1 and 31';
$lang['Error_Year'] = 'Your input year must be between 1970 and 2038';
$lang['Error_Topics_Table'] = 'Error accessing topics table';

//Special Cases, Do not change for another language
$lang['ASC'] = $lang['Sort_Ascending'];
$lang['DESC'] = $lang['Sort_Descending'];
/* lang_admin_topic_shadow.php - END */

/* lang_admin_rebuild_search.php - BEGIN */
$lang['Rebuild_search'] = 'Rebuild Search';
$lang['Rebuild_search_desc'] = 'This will index every post in your Knowledge Base and rebuild the search tables. It may take a long time to process, so please do not move from this page until it is complete.';
$lang['Post_limit'] = 'Post limit';
$lang['Time_limit'] = 'Time limit';
$lang['Refresh_rate'] = 'Refresh rate';

$lang['Next'] = 'Next';
$lang['Finished'] = 'Finished';
/* lang_admin_rebuild_search.php - END */

/* lang_admin_faq_editor.php - BEGIN */
$lang['faq_editor'] = 'Edit Language';
$lang['faq_editor_explain'] = '<b>Edit and re-arrange your FAQ, BBCode FAQ or Board Rules.</b><br /><br /> You <u>should not</u> remove or alter the section entitled <b>phpBB 2 Issues</b> or <b>About Icy Phoenix</b>.';

$lang['faq_select_language'] = 'Choose the language file you want to edit';
$lang['faq_retrieve'] = 'Retrieve File';

$lang['faq_block_delete'] = 'Are you sure you want to delete this block?';
$lang['faq_quest_delete'] = 'Are you sure you want to delete this question (and its answer)?';

$lang['faq_quest_edit'] = 'Edit Question & Answer';
$lang['faq_quest_create'] = 'Create New Question & Answer';

$lang['faq_quest_edit_explain'] = 'Edit the question and answer. Change the block if you wish.';
$lang['faq_quest_create_explain'] = 'Type the new question and answer and press Submit.';

$lang['faq_block'] = 'Block';
$lang['faq_quest'] = 'Question';
$lang['faq_answer'] = 'Answer';

$lang['faq_block_name'] = 'Block Name';
$lang['faq_block_rename'] = 'Rename a block';
$lang['faq_block_rename_explain'] = 'Change the name of a block in the file';

$lang['faq_block_add'] = 'Add Block';
$lang['faq_quest_add'] = 'Add Question';

$lang['faq_no_quests'] = 'No questions in this block. This will prevent any blocks after this one being displayed. Delete the block or add one or more questions.';
$lang['faq_no_blocks'] = 'No blocks defined. Add a new block by typing a name below.';

$lang['faq_write_file'] = 'Could not write to the language file!';
$lang['faq_write_file_explain'] = 'You must make the language file in language/lang_english/ or equivalent <i>writable</i> to use this control panel. On UNIX, this means running <code>chmod 666 filename</code>. Most FTP clients can do this through the properties sheet of a file, otherwise you can use telnet or SSH.';
/* lang_admin_faq_editor.php - END */

/* lang_admin_rules_editor.php - BEGIN */
$lang['rules_editor'] = 'Edit Language';
$lang['rules_editor_explain'] = 'Edit and re-arrange your Board rules. ';

$lang['rules_select_language'] = 'Choose the language of the file you want to edit';
$lang['rules_retrieve'] = 'Retrieve File';

$lang['rules_block_delete'] = 'Are you sure you want to delete this block?';
$lang['rules_quest_delete'] = 'Are you sure you want to delete this question (and its answer)?';

$lang['rules_quest_edit'] = 'Edit Question & Answer';
$lang['rules_quest_create'] = 'Create New Question & Answer';

$lang['rules_quest_edit_explain'] = 'Edit the question and answer. Change the block if you wish.';
$lang['rules_quest_create_explain'] = 'Type the new question and answer and press Submit.';

$lang['rules_block'] = 'Block';
$lang['rules_quest'] = 'Question';
$lang['rules_answer'] = 'Answer';

$lang['rules_block_name'] = 'Block Name';
$lang['rules_block_rename'] = 'Rename a block';
$lang['rules_block_rename_explain'] = 'Change the name of a block in the file';

$lang['rules_block_add'] = 'Add Block';
$lang['rules_quest_add'] = 'Add Question';

$lang['rules_no_quests'] = 'No questions in this block. This will prevent any blocks after this one being displayed. Delete the block or add one or more questions.';
$lang['rules_no_blocks'] = 'No blocks defined. Add a new block by typing a name below.';

$lang['rules_write_file'] = 'Could not write to the language file!';
$lang['rules_write_file_explain'] = 'You must make the language file in language/lang_english/ or equivalent <i>writable</i> to use this control panel. On UNIX, this means running <code>chmod 666 filename</code>. Most FTP clients can do this through the properties sheet of a file, otherwise you can use telnet or SSH.';
/* lang_admin_rules_editor.php - END */

/* lang_admin_priv_msgs.php - BEGIN */
/* Added in 1.6.0 */
$lang['PM_View_Type'] = 'PM View Type';
$lang['Show_IP'] = 'Show IP Address';
$lang['Rows_Per_Page'] = 'Rows Per Page';
$lang['Archive_Feature'] = 'Archive Feature';
$lang['Inline'] = 'Inline';
$lang['Pop_up'] = 'Pop-up';
$lang['Current'] = 'Current';
$lang['Rows_Plus_5'] = 'Add 5 Rows';
$lang['Rows_Minus_5'] = 'Remove 5 Rows';
$lang['Enable'] = 'Enable';
$lang['Disable'] = 'Disable';
$lang['Inserted_Default_Value'] = '%s Configuration Item did not exist, inserted a default value<br />'; // %s = config name
$lang['Updated_Config'] = 'Updated Configuration Item %s<br />'; // %s = config item
$lang['Archive_Table_Inserted'] = 'Archive Table did not exist, created it<br />';
$lang['Switch_Normal'] = 'Switch To Normal Mode';
$lang['Switch_Archive'] = 'Switch To Archive Mode';

/* General */
$lang['Deleted_Message'] = 'Deleted Private Message - %s <br />'; // %s = PM title
$lang['Archived_Message'] = 'Archived Private Message - %s <br />'; // %s = PM title
$lang['Archived_Message_No_Delete'] = 'Cannot Delete %s, It Was Marked For Archive As Well <br />'; // %s = PM title
$lang['Private_Messages'] = 'Private Messages';
$lang['Private_Messages_Archive'] = 'Private Messages Archive';
$lang['Archive'] = 'Archive';
$lang['To'] = 'To';
$lang['Subject'] = 'Subject';
$lang['Sent_Date'] = 'Sent Date';
$lang['From'] = 'From';
$lang['Sort'] = 'Sort';
$lang['Filter_By'] = 'Filter By';
$lang['PM_Type'] = 'PM Type';
$lang['Status'] = 'Status';
$lang['No_PMS'] = 'No Private Messages Matching Your Sort Criteria To Display';
$lang['Archive_Desc'] = 'Private Messages you have chosen to archive are listed here. Users are no longer able to access these (sender and receiver), but you can view or delete them at any time.';
$lang['Normal_Desc'] = 'All Private Messages on your board may be managed here. You can read any you\'d like and choose to delete or archive (keep, but users cannot view) the messages as well.';
$lang['Remove_Old'] = 'Orphan PMs:</a> <span class="gensmall">Users who no longer exist could have left PMs behind, this will remove them.</span>';
$lang['Remove_Sent'] = 'Sent Box PMs:</a> <span class="gensmall">PMs in the sent box are just copies of the exact same message that was sent, except assigned to the sender after the other user has read the PM. These are not generally needed.</span>';
$lang['Removed_Old'] = 'Removed All Orphan PMs<br />';
$lang['Removed_Sent'] = 'Removed All Sent PMs<br />';
$lang['Utilities'] = 'Mass Deletion Utilities';
$lang['Nivisec_Com'] = 'Nivisec.com';

/* PM Types */
$lang['PM_-1'] = 'All Types'; //PRIVMSGS_ALL_MAIL = -1
$lang['PM_0'] = 'Read PMs'; //PRIVMSGS_READ_MAIL = 0
$lang['PM_1'] = 'New PMs'; //PRIVMSGS_NEW_MAIL = 1
$lang['PM_2'] = 'Sent PMs'; //PRIVMSGS_SENT_MAIL = 2
$lang['PM_3'] = 'Saved PMs (In)'; //PRIVMSGS_SAVED_IN_MAIL = 3
$lang['PM_4'] = 'Saved PMs (Out)'; //PRIVMSGS_SAVED_OUT_MAIL = 4
$lang['PM_5'] = 'Unread PMs'; //PRIVMSGS_UNREAD_MAIL = 5

/* Errors */
$lang['Error_Other_Table'] = 'Error querying a required table.';
$lang['Error_Posts_Text_Table'] = 'Error querying Private Messages Text table.';
$lang['Error_Posts_Table'] = 'Error querying Private Messages table.';
$lang['Error_Posts_Archive_Table'] = 'Error querying Private Messages Archive table.';
$lang['No_Message_ID'] = 'No message ID was specified.';


/* Special Cases, Do not bother to change for another language */
$lang['ASC'] = $lang['Sort_Ascending'];
$lang['DESC'] = $lang['Sort_Descending'];
$lang['privmsgs_date'] = $lang['Sent_Date'];
$lang['privmsgs_subject'] = $lang['Subject'];
$lang['privmsgs_from_userid'] = $lang['From'];
$lang['privmsgs_to_userid'] = $lang['To'];
$lang['privmsgs_type'] = $lang['PM_Type'];
/* lang_admin_priv_msgs.php - END */

/* lang_admin_link.php - BEGIN */
// Categories
$lang['Link_Categories_Title'] = 'Link Categories Control';
$lang['Link_Categories_Explain'] = '<b>Manage your categories:</b><br /><br /> Create, alter, delete or sort, etc.';
$lang['Category_Permissions'] = 'Category Permissions';
$lang['Category_Title'] = 'Category Title';
$lang['Category_Desc'] = 'Category Description';
$lang['View_level'] = 'View Level';
$lang['Upload_level'] = 'Upload Level';
$lang['Rate_level'] = 'Rate Level';
$lang['Comment_level'] = 'Comment Level';
$lang['Edit_level'] = ' Edit Level';
$lang['Delete_level'] = 'Delete Level';
$lang['New_category_created'] = 'New category has been created successfully';
$lang['Click_return_link_category'] = 'Click %sHere%s to return to the Link Categories Manager';
$lang['Category_updated'] = 'This category has been updated successfully';
$lang['Delete_Category'] = 'Delete Category';
$lang['Delete_Category_Explain'] = 'Delete this category?';
$lang['Category_deleted'] = 'The category has been deleted successfully';
$lang['Category_changed_order'] = 'The category order has been changed successfully';

// Config
$lang['Link_Config'] ='Link Config Control';
$lang['Link_config_explain'] = 'Change the general settings of your link here';
$lang['lock_submit_site'] = 'Lock user submit site';
$lang['allow_guest_submit_site'] = 'Allow guest(s) to submit site';
$lang['allow_no_logo'] = 'Allow submit site without a banner';
$lang['site_logo'] = 'The url where your logo can be found (full url)';
$lang['site_url'] = 'The url of your website';
$lang['width'] = 'Max banner width';
$lang['height'] = 'Max banner height';
$lang['linkspp'] = 'Max links per page';
$lang['interval'] = 'How fast the banners are displayed';
$lang['display_logo'] = 'How many banners are displayed at once';
$lang['Link_display_links_logo'] = 'Display Links site banner';
$lang['Link_email_notify'] = 'While Link added, send an e-mail to all site admins';
$lang['Link_pm_notify'] = 'While Link added, notify all site admins in a private message';
$lang['Link_config_updated'] = 'Links configuration has been updated successfully';
$lang['Click_return_link_config'] = 'Click %sHere%s to return to the Link Config Manager';

// Link_MOD
$lang['Links'] = 'Links Management';
$lang['Links_explain'] = 'Preview the status of, edit or remove selected links.';
$lang['Add_link'] = 'Add Link';
$lang['Add_link_explain'] = 'Add a new link.';
$lang['Edit_link'] = 'Edit Link';
$lang['Edit_link_explain'] = 'Edit this link\'s details. You can also choose to ';
$lang['Delete_link'] = 'Delete Link';
$lang['Delete_link_explain'] = 'Delete this link. You can also choose to ';
$lang['Link_update'] = 'Update link detail';
$lang['Link_delete'] = 'Delete this link';
$lang['Link_title'] = 'Site Name';
$lang['Link_url'] = 'Site URL';
$lang['Link_logo_src'] = 'Site Logo (88x31 pixels, file-size no more than 10K)';
$lang['Link_category'] = 'Site Category';
$lang['Link_desc'] = 'Site Description';
$lang['link_hits'] = 'Hits';
$lang['Link_basic_setting'] = 'Link Basic Detail';
$lang['Link_adv_setting'] = 'Advanced Setting';
$lang['Link_active'] = 'Active Status';

$lang['Link_admin_add_success'] = 'The link was successfully added';
$lang['Link_admin_add_fail'] = 'Unable to add the new link, please try again later';
$lang['Link_admin_update_success'] = 'The link was successfully updated';
$lang['Link_admin_update_fail'] = 'Unable to update the link, please try again later';
$lang['Link_admin_delete_success'] = 'The link was successfully removed';
$lang['Link_admin_delete_fail'] = 'Unable to remove the link, please try again later';
$lang['Click_return_lastpage'] = 'Click %sHere%s to return to the previous page';
$lang['Click_return_admin_links'] = 'Click %sHere%s to return to Links Manage';
$lang['Preview'] = 'Preview';
$lang['Search_site'] = 'Search Site';
$lang['Search_site_title'] = 'Search Site Name/Description:';
/* lang_admin_link.php - END */

/* lang_.php - BEGIN */
/* lang_.php - END */

// Icy Phoenix - BUILD 009
$lang['Replace_title'] = 'Replace In Posts';
$lang['Replace_text'] = 'Replace words or lines with whatever you wish. <br /><b>Note!</b> This cannot be undone.';
$lang['Link'] = 'Link';
$lang['Str_old'] = 'Current text';
$lang['Str_new'] = 'Replace with';
$lang['No_results'] = 'No results found';
$lang['Replaced_count'] = 'Total posts updated: %s';

// Icy Phoenix - BUILD 016
$lang['group_rank'] = 'Rank';
$lang['group_color'] = 'Colour';
$lang['group_legend'] = 'Show in legend';
$lang['group_legend_short'] = 'Legend';
$lang['group_main'] = 'Main group';
$lang['group_members'] = 'Members';
$lang['group_update'] = 'Apply Changes';

/* lang_color_groups.php - BEGIN */
$lang['Color_Groups'] = 'Colour Groups';
$lang['Manage_Color_Groups'] = 'Manage Colour Groups';
$lang['Add_New_Group'] = 'Add New Group';
$lang['Color'] = 'Colour';
$lang['User_Count'] = 'User Count';
$lang['Color_List'] = 'Colour Name List:';
$lang['Group_Name'] = 'Group Name';
$lang['Define_Users'] = 'Define Users';
$lang['Color_Group_User_List'] = 'Colour Group User List';
$lang['Options'] = 'Options';
$lang['Example'] = 'Example';
$lang['User_List'] = 'Full User List';
$lang['Unassigned_User_List'] = 'Users With No Group';
$lang['Assigned_User_List'] = 'Users With A Group';
$lang['Add_Arrow'] = 'Add To List';
$lang['Update'] = 'Update';
$lang['Updated_Group'] = 'Updated Group User List<br />';
$lang['Deleted_Group'] = 'Deleted Specified Group. All users that were in this group have been reset to no group membership<br />';
$lang['Hide'] = 'Hide';
$lang['Un-hide'] = 'Un-hide';
$lang['Move_Up'] = 'Move Up';
$lang['Move_Down'] = 'Move Down';
$lang['Group_Hidden'] = 'Group Hidden<br />';
$lang['Group_Unhidden'] = 'Group Visible<br />';
$lang['Groups_Updated'] = 'Group changes have been updated<br />';
$lang['Moved_Group'] = 'Moved group order<br />';


//Descriptions
$lang['Manage_Color_Groups_Desc'] = 'Update or add a new group, or manage the users assigned to a particular colour group.<br />Groups that you choose to "Hide" will not show up on the main index list.';
$lang['Color_Group_User_List_Desc'] = 'Add or remove users to a specified colour group.';

//Errors
$lang['Error_Group_Table'] = 'Error querying the colour groups table.';
$lang['Error_Font_Color'] = '<b><u>Warning:</b></u> The specified font colour appears to be invalid!';
$lang['Color_Ok'] = 'The specified font colour appears to be valid.';
$lang['No_Groups_Exist'] = 'No groups exist.';
$lang['Error_Users_Table'] = 'Error querying the users table.';
$lang['Invalid_Group_Add'] = '%s is an invalid or duplicate group name.<br />';

//Dynamic
$lang['Group_Updated'] = 'Updated Colour Group %s<br />';
$lang['Editing_Group'] = 'Currently editing the user list for %s.';
$lang['Invalid_User'] = '%s is an invalid username, skipping<br />';
$lang['Invalid_Order_Num'] = '%s contained an invalid order number, but it has been fixed. Please try your move up/down again.';

//New for 1.2.0
$lang['Users_List'] = 'Users List';
$lang['Groups_List'] = 'User Groups List';
$lang['List_Info'] = '<b>Notes</b>: <ul><li>Hold CTRL when clicking to select multiple names. <li>If a user belongs to a user group, and is added to a specific colour group, the colour group that contains the user will be used; not the one the user group belongs to. <li>The list names are formatted as NAME (CURRENT_COLOR_GROUP). There will be no (CURRENT_COLOR_GROUP) if the entry doesn\'t belong to one. <li>If a user is a member of 2 or more user groups, the highest ranking colour group will be assigned (you order their appearance on the main page).</ul>';
/* lang_color_groups.php - END */

// Icy Phoenix - BUILD 023
$lang['Empty_Cache_Main_Question'] = 'If you click yes, all files in main cache folder will be permanently deleted.<br /><br /><em> Are you sure you want to do this? </em>';
$lang['Empty_Cache_Posts_Question'] = 'If you click yes, precompiled posts field in posts table will be permanently deleted.<br /><br /><em> Are you sure you want to do this? </em>';
$lang['Empty_Cache_Thumbs_Question'] = 'If you click yes, all thumbnails generated in posts will be permanently deleted.<br /><br /><em> Are you sure you want to do this? </em>';
$lang['Empty_Cache_Success'] = 'Cache folders emptied successfully.';

$lang['Copy_Auth'] = 'Copy permissions from';
$lang['Copy_Auth_Explain'] = 'Please note that you can copy permissions only from forums, not from categories!';

// Icy Phoenix - BUILD 027
/* lang_admin_db_backup.php - BEGIN */
$lang['SELECT_ALL'] = 'Select all';
$lang['SELECT_FILE'] = 'Select a file';
$lang['START_BACKUP'] = 'Start backup';
$lang['START_RESTORE'] = 'Start restore';
$lang['STORE_AND_DOWNLOAD'] = 'Store and download';
$lang['STORE_LOCAL'] = 'Store file locally';
$lang['STRUCTURE_ONLY'] = 'Structure only';

// Backup
$lang['ACP_BACKUP'] = 'Backup Database';
$lang['ACP_BACKUP_EXPLAIN'] = 'Backup all your site related data. You can store the resulting archive in your <samp>backup/</samp> folder or download it directly. Your server configuration may also allow you to save the file in compressed gzip format.';

$lang['BACKUP_OPTIONS'] = 'Backup Options';
$lang['BACKUP_TYPE'] = 'Backup type';

$lang['DATABASE'] = 'Database Utilities';
$lang['DATA_ONLY'] = 'Data only';
$lang['DELETE_BACKUP'] = 'Delete backup';
$lang['DELETE_SELECTED_BACKUP'] = 'Are you sure you want to delete the selected backup?';
$lang['DESELECT_ALL'] = 'Deselect all';
$lang['DOWNLOAD_BACKUP'] = 'Download backup';

$lang['FILE_TYPE'] = 'File type';
$lang['FULL_BACKUP'] = 'Full';

$lang['Backup_Success'] = 'The backup file has been created successfully.';
$lang['Backup_Deleted'] = 'The backup file has been deleted successfully.';

$lang['TABLE_SELECT'] = 'Table select';
// Errors
$lang['Table_Select_Error'] = 'You must select at least one table.';

// Restore
$lang['ACP_RESTORE'] = 'Restore Database';
$lang['ACP_RESTORE_EXPLAIN'] = 'Restore of all your database tables from a saved backup file. If your server supports it you can use a gzip or bzip2 compressed text file and it will be automatically decompressed. <strong>WARNING</strong> This will overwrite any existing data. The restore may take a long time to process, please <b>do not</b> move from this page until it is complete. Backups are stored in the <samp>backup/</samp> folder, and are assumed to be generated by this site backup functions. Restoring backups that were not created by the built in system may not work properly.';
$lang['RESTORE_OPTIONS'] = 'Restore Options';

$lang['Restore_Success'] = 'The database has been successfully restored.<br />Your site should be back to the state it was in when the backup was made.';

// Errors
$lang['No_Backup_Selected'] = 'You haven\'t selected any backup, so you can\'t restore it.';
$lang['Backup_Invalid'] = 'The selected file to backup is invalid.';
$lang['RESTORE_FAILURE'] = 'The backup file may be corrupt.';
/* lang_admin_db_backup.php - END */

/* Logs - BEGIN */
$lang['LOGS_TITLE'] = 'Logs';
$lang['LOGS_EXPLAIN'] = 'All relevant actions stored in the DB';
$lang['LOGS_TARGET'] = 'Target';
$lang['LOGS_DENY'] = 'Not authorized!';
$lang['LOGS_POST_EDIT'] = 'edited a post posted by';
$lang['LOGS_POST_DELETE'] = 'deleted a post posted by';
$lang['LOGS_GROUP_JOIN'] = 'requested to join the group';
$lang['LOGS_GROUP_EDIT'] = 'edited group options of %s';
$lang['LOGS_GROUP_ADD'] = 'added %s to the group';
$lang['LOGS_GROUP_TYPE'] = 'edited group %s status, now the group is %s';
$lang['LOGS_GROUP_TYPE_0'] = 'open';
$lang['LOGS_GROUP_TYPE_1'] = 'closed';
$lang['LOGS_GROUP_TYPE_2'] = 'hidden';
$lang['LOGS_MESSAGE'] = 'message to the user, code <b>%s</b>';
$lang['LOGS_MODCP_DELETE'] = 'deleted some messages in %s through MODCP';
$lang['LOGS_MODCP_RECYCLE'] = 'trashed some messages in %s through MODCP';
$lang['LOGS_MODCP_LOCK'] = 'locked some messages in %s through MODCP';
$lang['LOGS_MODCP_UNLOCK'] = 'unlocked some messages in %s through MODCP';
$lang['LOGS_MODCP_MOVE'] = 'moved some messages in %s through MODCP';
$lang['LOGS_MODCP_MERGE'] = 'merged some messages in %s through MODCP';
$lang['LOGS_MODCP_SPLIT'] = 'splitted some messages in %s through MODCP';
$lang['LOGS_TOPIC_BIN'] = 'trashed a message in';
$lang['LOGS_TOPIC_ATTACK'] = 'hacking attempt to message';
$lang['LOGS_CARD_BAN'] = 'banned';
$lang['LOGS_CARD_WARN'] = 'warned';
$lang['LOGS_CARD_UNBAN'] = 'unbanned';
$lang['LOGS_ADMIN_CAT_ADD'] = 'added a forum category';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP'] = 'backupped the DB %s';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_full'] = 'full';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_structure'] = 'structure only';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_data'] = 'data';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_store_and_download'] = ', downloaded and stored';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_store'] = ', stored';
$lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_download'] = ', downloaded';
$lang['LOGS_ADMIN_DB_UTILITIES_RESTORE'] = 'restored the DB from';
$lang['LOGS_ADMIN_BOARD_CONFIG'] = 'edited config settings';
$lang['LOGS_ADMIN_BOARD_IP_CONFIG'] = 'edited Icy Phoenix settings';
$lang['LOGS_ADMIN_GROUP_NEW'] = 'group created';
$lang['LOGS_ADMIN_GROUP_DELETE'] = 'group deleted';
$lang['LOGS_ADMIN_GROUP_EDIT'] = 'group edited';
$lang['LOGS_ADMIN_USER_AUTH'] = 'edited permissions of';
$lang['LOGS_ADMIN_GROUP_AUTH'] = 'edited group permissions';
$lang['LOGS_ADMIN_USER_BAN'] = 'banned someone from ACP';
$lang['LOGS_ADMIN_USER_UNBAN'] = 'unbanned someone from ACP';
$lang['LOGS_ADMIN_USER_DELETE'] = 'user deleted';
$lang['LOGS_ADMIN_USER_EDIT'] = 'profile edited of';
$lang['LOGS_CMS_LAYOUT_EDIT'] = 'edited %sTHIS%s page';
$lang['LOGS_CMS_LAYOUT_DELETE'] = 'deleted a page [ID = %s]';
$lang['LOGS_CMS_BLOCK_EDIT'] = 'edited a block [ID = %s] in %sTHIS%s page';
$lang['LOGS_CMS_BLOCK_EDIT_LS'] = 'edited a block [ID = %s] in a standard page [%s]';
$lang['LOGS_CMS_BLOCK_DELETE'] = 'deleted a block [ID = %s] in %sTHIS%s page';
$lang['LOGS_CMS_BLOCK_DELETE_LS'] = 'deleted a block  [ID = %s] in a standard page [%s]';
//$lang['LOGS_'] = '';
/* Logs - END */

/*
$lang['MG_SW_'] = '';
*/

//
// ####################### [ Icy Phoenix Options END ] #######################
//

//
// ####################### [ Icy Phoenix Navigation BEGIN ] #######################
//
// Use numbers to sort the ACP Navigation menu
// Numbers have to be changed in all /adm/*.php files too

// Configuration
$lang['1000_Configuration'] = 'Configuration'; // admin_board.php, admin_board_extend.php, admin_board_headers_banners.php, admin_board_main.php, admin_board_permissions.php, admin_board_server.php, admin_board_posting.php, admin_board_queries.php, admin_captcha_config.php, admin_lang_user_created.php, admin_upi2db.php
$lang['100_Server_Configuration'] = 'Server'; // admin_board_server.php
$lang['110_Various_Configuration'] = 'Site'; // admin_board.php
$lang['120_MG_Configuration'] = 'Icy Phoenix'; // admin_board.php
$lang['125_Language'] = 'Custom Lang Vars'; // admin_lang_user_created.php
$lang['127_Clear_Cache'] = 'Clear Cache'; // admin_board_clearcache.php
$lang['130_UPI2DB_Mod'] = 'Unread Posts'; // admin_upi2db.php
$lang['140_MG_Configuration_Headers_Banners'] = 'Headers &amp; Banners'; // admin_board_headers_banners.php
$lang['145_Captcha_Config'] = 'Visual Confirmation'; // admin_captcha_config.php
$lang['150_Similar_topics'] = 'Similar Topics'; // admin_similar_topics.php
$lang['160_Title_infos'] = 'Quick Title Management'; // admin_quick_title.php
$lang['170_LIW'] = 'Limit Image Width'; // admin_liw.php
$lang['175_Yahoo_search'] = 'Yahoo Search'; // admin_yahoo_search.php
$lang['180_MG_Configuration_Permissions'] = 'Page Permissions'; // admin_board_permissions.php
$lang['200_Language'] = 'Language'; // admin_lang_extend.php
$lang['210_MG_Quick_Settings'] = 'Quick Settings'; // admin_board_quick_settings.php

// General
$lang['1100_General'] = 'General'; // admin_acronyms.php, admin_autolinks.php, admin_force_read.php, admin_helpdesk.php, admin_liw.php, admin_force_read.php, admin_mass_email.php, admin_megamail.php, admin_notepad.php, admin_quick_title.php, admin_smilies.php, admin_words.php, admin_yahoo_search.php
$lang['100_Acronyms'] = 'Acronyms'; // admin_acronyms.php
$lang['110_Autolinks'] = 'Autolinks'; // admin_autolinks.php
$lang['130_Mass_Email'] = 'Mass Email'; // admin_mass_email.php
$lang['140_Mega_Mail'] = 'Mega Mail'; // admin_megamail.php
$lang['150_FTR_Config'] = 'FTR'; // admin_force_read.php
$lang['160_FTR_Users'] = 'FTR User'; // admin_force_read.php
$lang['170_Smilies'] = 'Smileys'; // admin_smilies.php
$lang['180_Word_Censor'] = 'Word Censor'; // admin_words.php
$lang['200_Notepad'] = 'Admin Notepad'; // admin_notepad.php
$lang['210_Help_Desk'] = 'Help Desk'; // admin_helpdesk.php
$lang['240_Replace_title'] = 'Replace in posts'; // admin_replace.php

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

// IM Portal
$lang['1500_IM_Portal'] = 'Portal'; // admin_blocks.php, admin_blocks_pos.php, admin_blocks_var.php, admin_clear_cache.php, admin_layout.php, admin_portal.php
$lang['100_Portal_Configuration'] = 'Portal Configuration'; // admin_portal.php
$lang['110_Page_Management'] = 'Page Management'; // admin_layout.php
$lang['115_Page_Management'] = 'Custom Page Management'; // admin_layout_cp.php
$lang['120_Blocks_Management'] = 'Block Management'; // admin_blocks.php
$lang['130_Blocks_Position_Tag'] = 'Block Position Tag'; // admin_blocks_pos.php
$lang['140_Blocks_Variables'] = 'Block Variables'; // admin_blocks_var.php
$lang['150_Delete_Cache_Files'] = 'Delete Cache Files'; // admin_clear_cache.php

// News
$lang['1600_News_Admin'] = 'News'; // admin_news.php, admin_news_cats.php, admin_xs_news.php, admin_xs_news_xml.php
$lang['100_News_Config'] = 'News Configuration'; // admin_news.php
$lang['110_News_Cats'] = 'News Categories'; // admin_news_cats.php
$lang['120_XS_News_Config'] = 'News Configuration'; // admin_xs_news.php
$lang['130_XS_News'] = 'News Articles'; // admin_xs_news.php
$lang['140_XS_News_Tickers'] = 'News Ticker'; // admin_xs_news_xml.php

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

// Groups
$lang['1620_Groups'] = 'Groups'; // admin_color_groups.php, admin_groups.php, admin_ug_auth.php
$lang['110_Manage_Groups'] = 'Manage Groups'; // admin_groups.php
$lang['120_Color_Groups'] = 'Colour Groups'; // admin_color_groups.php
$lang['130_Permissions_Group'] = 'Permissions'; // admin_ug_auth.php

// Topic Rating
$lang['1700_Topic_Rating'] = 'Topic Rating'; // admin_rate.php

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
$lang['2200_Photo_Album'] = 'Photo Album'; // admin_album_auth.php, admin_album_cat.php, admin_album_config_extended.php
$lang['110_Album_Config'] = 'Configuration'; // admin_album_config_extended.php
$lang['120_Album_Categories'] = 'Manage Categories'; // admin_album_cat.php
$lang['130_Album_Permissions'] = 'Permissions'; // admin_album_auth.php
$lang['140_Personal_Galleries'] = 'Personal Galleries'; // admin_album_personal.php

// FAQ
$lang['2300_FAQ'] = 'FAQ & Rules'; // admin_faq_editor.php
$lang['110_FAQ_BBCode'] = 'BBCode FAQ'; // admin_faq_editor.php
$lang['120_FAQ_Board'] = 'Site FAQ'; // admin_faq_editor.php
$lang['130_FAQ_Rules'] = 'Site Rules'; // admin_faq_editor.php

// INFO
$lang['2400_INFO'] = 'Info'; // admin_logs.php, admin_phpinfo.php, admin_gd_info.php, admin_referrers.php, admin_google_bot_detector.php
$lang['110_Actions_LOG'] = 'Actions Log'; // admin_logs.php
$lang['120_PHP_INFO'] = 'PHP Info'; // admin_phpinfo.php
$lang['130_GD_Info'] = 'GD Info'; // admin_gd_info.php
$lang['140_HTTP_REF'] = 'HTTP Referrers'; // admin_referrers.php
$lang['150_Google_BOT'] = 'Google Bot Detector'; // admin_google_bot_detector.php

//
// ####################### [ ACP Navigation END ] #######################
//

?>