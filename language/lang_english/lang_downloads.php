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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
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
/*
* mod version string
*/
	'Dl_mod_version' => 'Download MOD v%s&nbsp;&copy;(c) 2002 - 2007 by Hotschi, Demolition Fabi, OXPUS',

/*
* general messages after successfull data managements
*/
	'Click_return_categoryadmin' => 'Click %shere%s to return to the administration of the categories',
	'Click_return_dl_config' => 'Click %shere%s to return to the download configuration',
	'Click_return_downloadadmin' => 'Click %shere%s to return to the administration of the downloads',
	'Click_return_downloads' => 'Click %shere%s to return to the Downloads Categories',
	'Click_return_download_details' => 'Click %shere%s to return to the download',
	'Click_return_file_management' => 'Click %shere%s to return to the file management',
	'Click_return_modcp_approve' => 'Click %shere%s to return to the unapproved downloads',
	'Click_return_modcp_manage' => 'Click %shere%s to return to the moderator panel',
	'Click_return_user_traffic_admin' => 'Click %shere%s to modify the traffic of another user',
	'Click_return_usergroup_traffic_admin' => 'Click %shere%s to modify the traffic of another usergroup',

/*
* message strings
*/
	'Dl_category_added' => 'Category added',
	'Dl_category_updated' => 'Category updated',
	'Dl_category_removed' => 'Category removed successfully',
	'DL_upload_error' => 'Error while uploading this file. Please go back and retry.<br />Contact the admin, if this error still exists.',
	'Dl_approve_overview' => 'There are %s unapproved downloads. Click here to approve them.',
	'Dl_approve_overview_one' => 'There is %s unapproved download. Click here to approve it.',
	'Dl_config_updated' => 'Download configuration saved successfully',
	'Dl_confirm_cat_delete' => 'Do you really want to delete the category <b>%s</b>?',
	'Dl_confirm_delete_multiple_files' => 'Do you really want to delete these <b>%d downloads</b>?',
	'Dl_confirm_delete_single_file' => 'Do you really want to delete the download <b>%s</b>?',
	'Dl_delete_cat_and_files' => 'Delete this category and all files inside it',
	'Dl_delete_cat_confirm' => 'Choose a catagory to which each download will be moved to, or use another option',
	'Dl_delete_cat_only' => 'Delete only this category',
	'Dl_delete_file_confirm' => 'Also delete the file(s)',
	'Dl_empty_category' => 'This category does not contain any downloads',
	'Dl_no_access' => 'Access denied!<br /><br />You have no rights to download this file!',
	'Dl_no_category' => 'There are no categories. Add a new category before you set any permissions.',
	'Dl_no_category_index' => 'This download section has no categories',
	'Dl_no_external_url' => 'You have to enter a valid url if you want to submit an external download!',
	'Dl_no_filename_entered' => 'You have to select a file if you want to upload it!',
	'Dl_no_groups_for_traffic' => 'No usergroup found!!!<br /><br />Add a usergroup before you set the download traffic for it',
	'Dl_no_more_remain_traffic' => 'The traffic quota for downloads in %s has been reached for this month. You must Wait until next month or ask an administrator.',
	'Dl_no_permission' => 'Access denied!<br /><br />You have no rights to do this!',
	'Dl_no_upload_traffic' => 'Sorry, but there is no upload traffic left. Please contact the admin if you want to upload this file',
	'Dl_path_not_exist' => 'The category path <b>%s</b> does not exist!<br />Go back and enter another pathname<br />or create this folder with the file management.',
	'Dl_permission_updated' => 'Download permissions saved successfully',
	'Dl_user_config_saved' => 'User configuration for downloads updated successfully<br /><br />Click %shere%s to return to the downloads',
	'Download_added' => 'The Download was added',
	'Download_removed' => 'The Download was deleted',
	'Download_updated' => 'The information has been updated',
	'New_download' => 'A new download was uploaded or updated.<br />Click %shere%s to go directly to the downloads.',
	'Dl_confirm_cat_stats_delete' => 'Are you sure you want to delete the statistics from the category <b>%s</b>?',
	'Dl_confirm_all_stats_delete' => 'Are you sure you want to delete all statistics?',
	'Dl_confirm_cat_comments_delete' => 'Are you sure you want to delete the comments from the category <b>%s</b>?',
	'Dl_confirm_all_comments_delete' => 'Are you sure you want to delete all comments?',
	'Dl_file_not_found' => '<b>The file %s was not found!</b><br /><br />Make sure that this file exists in the folder %s!',
	'Dl_no_change_edit_time' => 'Do not log this update',
	'Dl_thumb_upload' => 'Thumbnail uploaded successfully',
	'Dl_thumb_del' => 'Thumbnail deleted successfully',
	'Dl_thumb_to_big' => 'The thumbnail is too large!<br />Please use a smaller file or an image with smaller dimensions.<br />Use the back button of your browser to retry the upload.',
	'Dl_hotlink_permission' => 'You are not allowed to download this file by linking to !',
	'Dl_vc_permission' => 'The confirmation code for this download was wrong. Please go back and retry.',
	'Dl_report_broken_vc_mismatch' => 'The confirmation code for this report was wrong. Please go back and retry.',
	'Dl_vc_not_found' => 'The confirmation code could not be found. Please go back and retry.',

/*
* page descriptions
*/
	'Dl_page_dl_hackslist' => 'Hackslist',
	'Dl_page_downloads' => 'Downloads',

/*
* commands
*/
	'Add_new_download' => 'Add a new download',
	'Dl_add' => 'Add',
	'Dl_add_category' => 'Add category',
	'Dl_approve' => 'Approve',
	'Dl_check_file_sizes' => 'Check filesizes',
	'Dl_check_thumbnails' => 'Check thumbnails',
	'Dl_delete' => 'Delete',
	'Dl_down' => 'Down',
	'Dl_edit' => 'Edit',
	'Dl_go' => 'Go',
	'Dl_klick_to_rate' => 'Rate',
	'Dl_lock' => 'Lock',
	'Dl_mark_all' => 'Mark all',
	'Dl_move' => 'Move to',
	'Dl_set' => 'Set',
	'Dl_unmark' => 'Unmark all',
	'Dl_up' => 'Up',
	'Dl_delete_cat' => 'Delete Category',
	'Dl_stats_delete' => 'Delete Statistics',
	'Dl_stats_delete_all' => 'Delete all Statistics',
	'Dl_comments_delete' => 'Delete Comments',
	'Dl_comments_delete_all' => 'Delete all Comments',
	'Dl_sub_sort_asc' => 'Sort all entries of this category ascending',
	'Dl_sub_sort_asc_zero' => 'Sort main categories ascending',

/*
* categories related
*/
	'Dl_cat_description' => 'Description',
	'Dl_cat_files' => 'Files',
	'Dl_cat_index' => 'Highest Level',
	'Dl_cat_name' => 'Category',
	'Dl_cat_parent' => 'Parent category',
	'Dl_cat_path' => 'Path',
	'Dl_cat_title' => 'Downloads - Categories',
	'Dl_choose_category' => 'Choose a category',
	'Dl_mod_desc_allow' => 'Allow detailed descriptions',
	'Dl_must_approve' => 'Uploads to this category must be approved',
	'Dl_statistics' => 'Enable detailed statistics',
	'Dl_stats_prune' => 'Prune statistics',
	'Dl_stats_delete' => 'Delete statistics',
	'Dl_stats_delete_all' => 'Delete all statistics',
	'Dl_comments_delete' => 'Delete comments',
	'Dl_comments_delete_all' => 'Delete all comments',
	'Dl_cat_traffic' => 'Traffic quota (currently aviable: %s)',
	'Dl_cat_traffic_off' => 'Traffic quota (currently off)',
	'Dl_cat_traffic_main' => 'For now %s traffic is left in this category',
	'Dl_thumb_cat' => 'Allow thumbnails',
	'Dl_approve_comments' => 'Automatically approve every new comment',
	'Dl_cat_rules' => 'Rules',

/*
* traffic related
*/
	'Dl_auto_traffic' => 'Automatic download traffic',
	'Dl_enable_post_traffic' => 'Enable traffic addition for posting',
	'Dl_group_auto_traffic' => 'Traffic for usergroups',
	'Dl_newtopic_traffic' => 'Download traffic for every newly created topic',
	'Dl_overall_traffic' => 'Traffic for all files per month',
	'Dl_remain_overall_traffic' => 'Remaining overall Traffic for this month: ',
	'Dl_reply_traffic' => 'Download traffic for every post, reply or quote',
	'Dl_traffic' => 'Max. traffic',
	'Dl_user_auto_traffic' => 'Traffic for users',
	'Single_user_traffic_title' => 'Traffic for a single user',
	'Traffic' => 'Traffic',
	'Traffic_now' => 'Currently on account',
	'Usergroup_traffic_title' => 'Traffic for members of a usergroup',
	'Users_traffic_title' => 'Traffic for all users',
	'Dl_user_traffic_once' => 'Decrease user traffic only once per download',
	'Dl_can_download_traffic' => 'You have already downloaded this file.<br />The administrator has allowed you to download again without paying traffic for it.',
	'Dl_can_download_traffic_footer' => '<b>The administrator has allowed you to load already downloaded files again without paying traffic for them.</b><br /><br />',
	'Traffic_all_users_admin_explain' => 'Add or set the traffic for all users.',
	'Traffic_single_user_admin_explain' => 'Add or set the traffic for a single user.',
	'Traffic_usergroup_admin_explain' => 'Add or set the traffic for all members of a usergroup.',
	'Dl_auto_traffic_explain' => 'Set the traffic that all users or all members of a usergroup will get every month for downloads.',

/*
* auth values
*/
	'Dl_permissions_all' => 'Set permissions for all users',
	'Dl_auth_dl' => 'Download',
	'Dl_auth_mod' => 'Moderate',
	'Dl_auth_up' => 'Upload',
	'Dl_auth_view' => 'View',
	'Dl_permissions' => 'Permissions for members of the following usergroups',
	'Dl_stop_uploads' => 'Disable uploads',
	'Dl_stat_perm' => 'Permission to statistics page from userlevel',
	'Dl_stat_perm_all' => 'Everybody',
	'Dl_stat_perm_user' => 'Registered users',
	'Dl_stat_perm_mod' => 'Download moderators',
	'Dl_stat_perm_admin' => 'Board administrators',
	'Dl_auth_cread' => 'Read comments',
	'Dl_auth_cpost' => 'Write comments',
	'Dl_perm_all' => 'Everybody',
	'Dl_perm_reg' => 'Registered Users',
	'Dl_perm_grg' => 'Usergroups',

/*
* hacks and mods related
*/
	'Dl_hack_autor' => 'Author',
	'Dl_hack_autor_email' => 'Author email',
	'Dl_hack_autor_website' => 'Author website',
	'Dl_hack_dl_url' => 'Alternative Download',
	'Dl_hack_version' => 'Hack Version',
	'Dl_hacklist' => 'List in Hacklist',
	'Dl_hacks_list' => 'Hacklist',
	'Dl_mod_desc' => 'How the mod works',
	'Dl_mod_list' => 'Display extra information',
	'Dl_mod_require' => 'Requires',
	'Dl_mod_test' => 'Mod tested on/with',
	'Dl_mod_todo' => 'Todo',
	'Dl_mod_warning' => 'Warnings',

/*
* moderator panel
*/
	'Dl_modcp_approve' => 'Moderator panel - approve downloads',
	'Dl_modcp_edit' => 'Moderator panel - edit download',
	'Dl_modcp_manage' => 'Moderator panel - manage downloads',
	'Dl_modcp_mod_auth' => 'You <b>can</b> %smoderate this category%s',

/*
* ACP file management
*/
	'Dl_check_filesizes_result' => 'All files are up to date and no errors were found',
	'Dl_check_filesizes_result_error' => 'The following files could not be checked:',
	'Dl_manage' => 'File toolbox',
	'Dl_manage_content_count' => '%s Entries',
	'Dl_manage_create_dir' => 'Create this folder',
	'Dl_manage_empty_folder' => 'This folder is empty...',
	'Dl_manage_explain' => 'Manage the files of the downloads. You can use the following functions:<br /><br />- Delete or move unassigned files<br />- Join assigned files without an existing category to an existing one<br />- Browse the files (<i>Default after opening the management</i>)<br />- Create a new folder<br />- Delete empty folders<br />- Check the filesize for each not external download<br /><br /><b>Attention:</b><br />This tool will not replace a FTP client!<br />Functions like uploading, moving each file or replacing files are not possible with this tool!',
	'Dl_physical_quota' => 'Physical quota overall files',
	'Dl_unassigned_files' => 'Check for unassigned downloads',

/*
* statistics
*/
	'Dl_latest_downloads' => 'Latest Downloads',
	'Dl_latest_uploads' => 'Latest Uploads',
	'Dl_downloads_cur_month' => 'Downloads Current Month',
	'Dl_downloads_overall' => 'Downloads Overall',
	'Dl_downloads_count' => 'Downloads',
	'Dl_downloads_traffic' => 'Overall Download Traffic',
	'Dl_uploads_count' => 'Uploads',
	'Dl_uploads_traffic' => 'Overall Upload Traffic',
	'Dl_pos' => 'Pos.',
	'Dl_time' => 'Time',
	'Dl_stats' => 'Download statistics',
	'Dl_direction' => 'Action',
	'Dl_browser' => 'Web-browser',
	'Dl_ip' => 'IP Adress',
	'Dl_traffic_cur_month' => 'Traffic this month',
	'Dl_traffic_overall' => 'Traffic overall',
	'Dl_guest_stat_delete' => 'Delete all data for guests',
	'Dl_no_filter' => '- no filter -',
	'Dl_total_entries' => 'Found Entries',
	'Dl_filter' => 'Filter',
	'Dl_filter_string' => 'Use * or % as placeholder',
	'Dl_guest_stats_admin' => 'Show guests data also',
	'Dl_stat_edit' => 'edited',

/*
* comments
*/
	'Dl_comment' => 'Comment',
	'Dl_comments' => 'Comments',
	'Dl_last_comment' => 'Last comment',
	'Dl_post_comment' => 'Write',
	'Dl_view_comments' => 'Show',
	'Dl_comment_edited' => 'Comment last edited on %s',
	'Dl_comment_write' => 'Write a comment',
	'Dl_comment_show' => 'Show all comments for this download',
	'Dl_comment_delete' => 'Delete',
	'Dl_comment_edit' => 'Edit',
	'Dl_comment_added' => 'Comment successfully added',
	'Dl_comment_updated' => 'Comment successfully updated',
	'Dl_must_be_approve_comment' => 'This comment must be approved by a moderator or administrator!',
	'Dl_approve_overview_one_comment' => 'There is one unapproved comment. Click the text to check it.',
	'Dl_approve_overview_comments' => 'There are %s unapproved comments. Click the text to check them.',

/*
* ACP management main page
*/
	'Dl_acp_traffic_management' => 'Manage traffic quotas and auto presets',
	'Dl_acp_categories_management' => 'Manage categories and their permissions',
	'Dl_acp_config_management' => 'Set the general configuration',
	'Dl_acp_files_management' => 'Manage the downloads',
	'Dl_acp_stats_management' => 'View and check statistics',
	'Dl_acp_managemant_page' => 'Download MOD administration',
	'Dl_acp_managemant_page_explain' => 'Administration settings and functions for the Download MOD.<br />Choose one of the functions below to change its settings.',

/*
* global strings
*/
	'Dl_account' => 'You have <b>%s</b> traffic left this month.',
	'Dl_add_user' => 'This download was added on <b>%s</b> by <b>%s</b>',
	'Dl_all' => 'All',
	'Dl_Bytes' => 'B',
	'Dl_Bytes_long' => 'Bytes',
	'Dl_change_user' => ' and last edited on <b>%s</b> by <b>%s</b>',
	'Dl_config' => 'Download configuration',
	'Dl_days' => 'Days',
	'Dl_default_sort' => 'Default sorting',
	'Dl_delay_auto_traffic' => 'Delay auto traffic for new User',
	'Dl_delay_post_traffic' => 'Delay traffic for posts for new User',
	'Dl_direct_download' => 'direct',
	'Dl_detail' => 'Details',
	'Dl_disable_email' => 'Disable notify per email',
	'Dl_disable_popup' => 'Disable notify per popup/board message',
	'Dl_disable_email_files' => 'Disable notify per email',
	'Dl_disable_popup_files' => 'Disable notify per popup/board message',
	'Dl_download' => 'Download',
	'DL_edit' => 'Updated download',
	'DL_edit_time' => 'Number of days an edited download will be marked',
	'Dl_extern' => 'External',
	'Dl_extern_up' => 'External',
	'Dl_file_description' => 'Description',
	'Dl_file_name' => 'File',
	'Dl_file_size' => 'Size',
	'Dl_files_title' => 'Files',
	'Dl_files_url' => 'URL',
	'Dl_free' => 'Free download',
	'Dl_function' => 'Function',
	'Dl_GB' => 'GB',
	'Dl_group_name' => 'Group Names',
	'Dl_guest_stats_show' => 'Show guests in the detailed category statistics',
	'Dl_hotlink_action' => 'Link action for direct download links',
	'Dl_hotlink_action_one' => 'redirect to details',
	'Dl_hotlink_action_two' => 'display message',
	'Dl_info' => 'Info',
	'Dl_is_free' => 'Free Download',
	'Dl_is_free_reg' => 'Free for registered users',
	'Dl_KB' => 'KB',
	'Dl_klicks' => 'Clicks Month',
	'Dl_last_time' => ' Last download on <b>%s</b>',
	'Dl_last_time_extern' => ' Last download from external URL on <b>%s</b>',
	'Dl_limit_desc_on_index' => 'Limit the Download descriptions on Index',
	'Dl_Links_per_page' => 'Downloads per page',
	'Dl_MB' => 'MB',
	'Dl_method' => 'Download method',
	'Dl_method_new' => 'New',
	'Dl_method_old' => 'Old',
	'Dl_method_quota' => 'Quota for chunked file read method',
	'Dl_modcp_capprove' => 'Approve comments',
	'Dl_must_be_approved' => 'This download must be approved by an administrator or a moderator of this category.',
	'Dl_name' => 'Name',
	'DL_new' => 'New download',
	'DL_new_time' => 'Number of days a new download will be marked',
	'Dl_no' => 'No',
	'Dl_no_config' => 'User configuration locked',
	'Dl_no_last_time' => ' No downloads counted up to now...',
	'Dl_no_mod_todo' => 'No Mod ToDo\'s for now',
	'Dl_not_availible' => 'Not availible',
	'Dl_order' => 'Sort',
	'Dl_overall_klicks' => 'Overall Clicks',
	'Dl_klicks_total' => 'Clicks Month / Total',
	'Dl_overview' => 'Overview list for all downloads',
	'DL_posts' => 'Number of Posts a user needs to get download access',
	'Dl_prevent_hotlink' => 'Prevent links from direct downloads',
	'Dl_rating' => 'Rating',
	'Dl_real_filetime' => 'Last file modification',
	'Dl_search_author' => 'For users who have uploaded or changed downloads',
	'Dl_show_footer_legend' => 'Show legend on download footer',
	'Dl_show_footer_stat' => 'Show statistics on download footer',
	'Dl_show_real_filetime' => 'Show the time of the last file modification on download details',
	'Dl_sort_preform' => 'Perform sorting',
	'Dl_sort_acp' => 'Preset',
	'Dl_sort_user' => 'User',
	'Dl_sort_user_opt' => 'Sort downloads for',
	'Dl_sort_user_ext' => 'with other criteria',
	'Dl_thumb' => 'Thumbnail',
	'Dl_thumb_dim_size' => 'The thumbnail can have a maximum dimension of %s x %s pixels and the filesize must be smaller than %s.',
	'Dl_thumb_max_dim' => 'Maximum dimensions in pixels X * Y',
	'Dl_thumb_max_size' => 'Maximum thumbnail filesize',
	'Dl_total_stat' => 'There are overall %s downloads with a size of %s / %s including %s external Downloads.',
	'Dl_unapproved' => '<br /><span class="gensmall">[ unapproved ]</span>',
	'Dl_upload' => 'Upload a file',
	'Dl_upload_file' => 'Upload',
	'Dl_upload_max_filesize' => 'Maximum filesize that is allowed for uploads: %s',
	'Dl_upload_traffic' => 'The file size of uploads will decrease the overall traffic. Regard this on choosing the file size!',
	'Dl_upload_traffic_count' => 'Also decrease the overall traffic for uploads',
	'Dl_use_hacklist' => 'Activate hacklist',
	'Dl_users_without_group' => 'Users without membership in a usergroup',
	'Dl_white_explain' => 'Free download without traffic count for registered users',
	'Dl_yes' => 'Yes',
	'Dl_yes_reg' => 'Yes for<br />reg. User',
	'Download_path' => 'Path to your downloads, e.g. <b>downloads/</b>',
	'Downloads' => 'Downloads',
	'Must_select_download' => 'Choose a download.',
	'Number_recent_dl_on_portal' => 'Number of recent downloads that are displayed on the portal',
	'Recent_downloads' => 'Recent downloads',
	'User_allow_fav_download_email' => 'Enable emails for changes to favorite downloads',
	'User_allow_fav_download_popup' => 'Enable board messag for changes to favorite downloads',
	'User_allow_new_download_email' => 'Enable emails for new downloads',
	'User_allow_new_download_popup' => 'Enable board message for new downloads',
	'User_download_email' => 'Email<br />notification',
	'User_download_popup' => 'Board<br />Message',
	'User_download_notify_type' => 'Type of board message',
	'User_download_notify_type_popup' => 'Popup',
	'User_download_notify_type_message' => 'Message on board header',
	'Dl_edit_own_downloads' => 'User can edit own files',
	'Dl_report_confirm_code' => 'Please enter the confirmation code here to report this download as broken:',
	'Dl_shorten_extern_links' => 'Shorten displayed external download link',
	'Dl_physical_quota_explain' => 'If this quota (currently %s in use) is reached, no more uploads will be allowed',
	'Dl_blue_explain' => 'No more overall traffic left this month!',
	'Dl_blue_explain_file' => 'No more file traffic left this month!',
	'Dl_blue_explain_foot' => 'No more overall traffic or file/category traffic left this month!',
	'Dl_green_explain' => 'Download! No deductions from user account.',
	'Dl_grey_explain' => 'Download! External source. No deductions from user account.',
	'Dl_red_explain' => 'Not enough traffic or posts<br />(%s posts required). Do not spam! Spammers will automatically be blocked!',
	'Dl_red_explain_alt' => 'Not enough traffic or posts (%s posts required). Do not spam! Spammers will automatically be blocked!',
	'Dl_red_explain_perm' => 'No rights to download!',
	'Dl_yellow_explain' => 'Download! Traffic will be deducted from user account.',
	'Dl_config_explain' => 'Enable or disable various functions and/or settings.',
	'Dl_cat_edit_explain' => 'Manage the categories for the Download MOD.',

/*
* build in add on cash to traffic
*/
	'Dl_cash_to_traffic' => 'Exchange cash to traffic',
	'Dl_cash_to_traffic_explain' => 'Set the exchange between cash currencies and download traffic. The exchange will change one full value of the currency to the entered amount of traffic.',
	'Dl_cash_currency' => 'Cash currency',
	'Dl_cash_exchange' => 'Exchange: 1 %s into %s traffic',
	'Dl_cash_current_amount' => 'You have %s %s',

/*
* new on Download MOD 5.0.10: Blacklist for filetypes
*/
	'Dl_ext_blacklist' => 'Blacklist filetypes',
	'Dl_use_ext_blacklist' => 'Enable blacklist for filetypes',
	'Dl_extention' => 'New forbidden file extention',
	'Dl_extentions' => 'Forbidden file extentions',
	'Dl_add_extention' => 'Add File extention',
	'Dl_confirm_delete_extention' => 'Are you sure you want to drop the extention <b>%s</b>?',
	'Dl_confirm_delete_extentions' => 'Are you sure you want to drop the extentions <b>%s</b>?',
	'Dl_delete_extention_confirm' => 'Drop file extention from blacklist',
	'Dl_delete_extentions_confirm' => 'Drop file extentions from blacklist',
	'Extention_removed' => 'File extention successfully dropped from blacklist.',
	'Extentions_removed' => 'File extentions successfully dropped from blacklist.',
	'Click_return_extblacklistadmin' => 'Click %shere%s to return to the File extention blacklist',
	'Dl_forbidden_extention' => 'This file extention is currently not allowed!<br />Please go back and use another file type.',
	'Dl_forbidden_ext_explain' => 'Forbidden file extentions: %s',
	'Dl_ext_blacklist_explain' => 'Enter file extentions that are to be banned from uploading with this MOD.<br />The banned file extentions added in the Icy Phoenix ACP will also be used.<br />This doesn\'t include existing downloads.',

/*
* new on Download MOD 5.0.12: Disable time edit informations
*/
	'Dl_disable_popup_notify' => 'Allow disabling of the edit-time information on edit a download',

/*
* new on Download MOD 5.0.15: Banlist and report broken downloads
*/
	'Dl_acp_banlist' => 'Banlist',
	'Dl_user_id' => 'User ID',
	'Dl_confirm_delete_ban_values' => 'Are you sure you want to delete these banlist entries?',
	'Dl_banlist_updated' => 'Banlist successfully updated',
	'Dl_banned' => 'You are banned and unable to download any file!',
	'Click_return_banlistadmin' => 'Click %shere%s to return to the banlist',
	'Dl_broken' => 'Report a broken download',
	'Dl_broken_mod' => 'Reset broken download status',
	'Dl_broken_cur' => 'This download is currently reported as broken',
	'Dl_report_broken' => 'Allow reporting of broken downloads',
	'Dl_a_guest' => 'a guest',
	'Dl_favorite_add' => 'Notify me about changes to this download',
	'Dl_favorite_drop' => 'Remove notifications about this download',
	'Dl_favorite' => 'Download Favourites',
	'Dl_acp_banlist_explain' => 'Enter different values to ban access to the downloads.<br />Each value will be used concurrently, even if they are entered as one dataset.',

/*
* new on Download MOD 5.1.0
*/
	'Dl_report_broken_lock' => 'Disable download while it is reported as broken',
	'Dl_report_broken_message' => 'Display note about a broken download only if it is also disabled',
	'Dl_report_broken_vc' => 'Enable visual confirmation to report a broken download',
	'Dl_visual_confirmation' => 'Enable visual confirmation to download a file',
	'Dl_off_guests' => 'Not for guests',

/*
* new on Download MOD 5.1.1
*/
	'Dl_icon_free_for_reg' => 'Display the white download icon for guests',

/*
* new on Download MOD 5.1.3
*/
	'Dl_latest_comments' => 'Displays the latest X comments on download details',

/*
* new on Download MOD 5.2.0 - The Bug Tracker !!!!!!!!!!!! -------------  * :-(((((
*/
	'Dl_bug_tracker' => 'Bug Tracker',
	'Dl_bug_tracker_file' => 'for this download',
	'Dl_bug_tracker_cat' => 'Enable Bug Tracker',

	'Confirm_delete_bug_report' => 'Are you sure you want to delete this bug report?',
	'Dl_bug_report_id' => 'Report ID',
	'Dl_bug_report_title' => 'Report Title',
	'Dl_bug_report_title_details' => 'Report',
	'Dl_bug_report_text' => 'Description',
	'Dl_bug_report_date' => 'Reported at',
	'Dl_bug_report_php' => 'PHP',
	'Dl_bug_report_db' => 'DB',
	'Dl_bug_report_forum' => 'Forum',
	'Dl_bug_report_file' => 'Download',
	'Dl_bug_report_author' => 'Reported by',
	'Dl_bug_report_assigned' => 'Assigned to',
	'Dl_bug_report_assign_date' => 'Assigned at',
	'Dl_bug_report_status' => 'Status',
	'Dl_bug_report_status_date' => 'Last time of status',
	'Dl_bug_report_detail' => 'Details',
	'Dl_bug_report_history' => 'History',
	'Dl_bug_report_reassign' => 'reassign',
	'Dl_bug_report_assign' => 'assign',
	'Dl_bug_report_unassigned' => 'unassigned',
	'Dl_no_bug_tracker' => 'No reports found',
	'Dl_bug_report_no_title' => 'You have not entered a title for this bug report!',
	'Dl_bug_report_no_text' => 'You have not entered a description for this bug report!',
	'Dl_bug_report_added' => 'Bug report successfully added',
	'Click_return_bug_tracker' => 'Click %shere%s to return to the bug tracker.',
	'Dl_bug_report_status_text' => 'Some text for the new status (will be sent per email to the author of this bug report)',
	'Dl_bug_report_status_update' => 'update status',
	'Dl_filter_bt_own' => 'Show my reports',
	'Dl_filter_bt_assign' => 'Show my assigns',

	'Dl_user_download_limit_flag' => 'Limit number of downloads per month',
	'Dl_user_download_limit' => 'Max number of downloads per month',
	)
);

$lang['Dl_report_status'][0] = 'new';
$lang['Dl_report_status'][1] = 'viewed';
$lang['Dl_report_status'][2] = 'in progress';
$lang['Dl_report_status'][3] = 'pending';
$lang['Dl_report_status'][4] = 'finish';
$lang['Dl_report_status'][5] = 'dropped';
$lang['Dl_bug_report_email_status'] = 'This message is for the new status:
--------------------
%s
--------------------';

?>