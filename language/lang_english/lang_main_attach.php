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
* (c) 2002 Meik Sievertsen (Acyd Burn)
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
// Auth Related Entries
/*
	'Rules_attach_can' => 'You <b>can</b> attach files in this forum',
	'Rules_attach_cannot' => 'You <b>cannot</b> attach files in this forum',
	'Rules_download_can' => 'You <b>can</b> download files in this forum',
	'Rules_download_cannot' => 'You <b>cannot</b> download files in this forum',
*/
	'Rules_attach_can' => 'You <b>can</b> attach files',
	'Rules_attach_cannot' => 'You <b>cannot</b> attach files',
	'Rules_download_can' => 'You <b>can</b> download files',
	'Rules_download_cannot' => 'You <b>cannot</b> download files',
	'Sorry_auth_view_attach' => 'Sorry but you are not authorized to view or download this Attachment',

// Viewtopic -> Display of Attachments
	'Description' => 'Description', // used in Administration Panel too...
	'Downloaded' => 'Downloaded',
	'Download' => 'Download', // this Language Variable is defined in lang_admin.php too, but we are unable to access it from the main Language File
	'Filesize' => 'Filesize',
	'Viewed' => 'Viewed',
	'Download_number' => '%d Time(s)', // replace %d with count
	'Extension_disabled_after_posting' => 'The Extension \'%s\' was deactivated by a board admin, therefore this Attachment is not displayed.', // used in Posts and PM's, replace %s with mime type

// Posting/PM -> Initial Display
	'Attach_posting_cp' => 'Attachment Posting Control Panel',
	'Attach_posting_cp_explain' => 'If you click on \'Add an Attachment\', you will see the box for adding Attachments.<br />If you click on \'Posted Attachments\', you will see a list of already attached Files and you are able to edit them.<br />If you want to Replace (Upload new Version) an Attachment, you have to click both links. Add the Attachment as you normally would do, thereafter don\'t click on Add Attachment, rather click on Upload New Version at the Attachment Entry you intend to update.',

// Posting/PM -> Posting Attachments
	'Add_attachment' => 'Add Attachment',
	'Add_attachment_title' => 'Add an Attachment',
	'Add_attachment_explain' => 'If you do not want to add an Attachment to your Post, please leave the Fields blank',
	'File_name' => 'Filename',
	'File_comment' => 'File Comment',

// Posting/PM -> Posted Attachments
	'Posted_attachments' => 'Posted Attachments',
	'Options' => 'Options',
	'Update_comment' => 'Update Comment',
	'Delete_attachments' => 'Delete Attachments',
	'Delete_attachment' => 'Delete Attachment',
	'Delete_thumbnail' => 'Delete Thumbnail',
	'Upload_new_version' => 'Upload New Version',

// Errors -> Posting Attachments
	'Invalid_filename' => '%s is an invalid filename', // replace %s with given filename
	'Attachment_php_size_na' => 'The Attachment is too big.<br />Couldn\'t get the maximum Size defined in PHP.<br />The Attachment Mod is unable to determine the maximum Upload Size defined in the php.ini.',
	'Attachment_php_size_overrun' => 'The Attachment is too big.<br />Maximum Upload Size: %d MB.<br />Please note that this Size is defined in php.ini, this means it\'s set by PHP and the Attachment Mod cannot override this value.', // replace %d with ini_get('upload_max_filesize')
	'FileType_Mismatch' => 'File type mismatch',
	'Disallowed_extension' => 'The Extension %s is not allowed', // replace %s with extension (e.g. .php)
	'Disallowed_extension_within_forum' => 'You are not allowed to post Files with the Extension %s within this Forum', // replace %s with the Extension
	'Attachment_too_big' => 'The Attachment is too big.<br />Max Size: %d %s', // replace %d with maximum file size, %s with size var
	'Attach_quota_reached' => 'Sorry, but the maximum filesize for all Attachments has been reached. Please contact the Board Administrator if you have questions.',
	'Too_many_attachments' => 'Attachment cannot be added, since the max. number of %d Attachments in this post has been reached', // replace %d with maximum number of attachments
	'Error_imagesize' => 'The Attachment/Image must be less than %d pixels wide and %d pixels high',
	'General_upload_error' => 'Upload Error: Could not upload Attachment to %s.', // replace %s with local path

	'Error_empty_add_attachbox' => 'You have to enter values in the \'Add an Attachment\' Box',
	'Error_missing_old_entry' => 'Unable to Update Attachment, could not find old Attachment Entry',

// Errors -> PM Related
	'Attach_quota_sender_pm_reached' => 'Sorry, but the maximum filesize for all Attachments in your Private Message Folder has been reached. Please delete some of your received/sent Attachments.',
	'Attach_quota_receiver_pm_reached' => 'Sorry, but the maximum filesize for all Attachments in the Private Message Folder of \'%s\' has been reached. Please let him/her know, or wait until he/she has deleted some of his/her Attachments.',

// Errors -> Download
	'No_attachment_selected' => 'You haven\'t selected an attachment to download or view.',
	'Error_no_attachment' => 'The selected Attachment no longer exists',

// Delete Attachments
	'Confirm_delete_attachments' => 'Are you sure you want to delete the selected Attachments?',
	'Deleted_attachments' => 'The selected Attachments have been deleted.',
	'Error_deleted_attachments' => 'Could not delete Attachments.',
	'Confirm_delete_pm_attachments' => 'Are you sure you want to delete all Attachments posted in this PM?',

// General Error Messages
	'Attachment_feature_disabled' => 'The Attachment Feature is disabled.',

	'Directory_does_not_exist' => 'The Directory \'%s\' does not exist or couldn\'t be found.', // replace %s with directory
	'Directory_is_not_a_dir' => 'Please check if \'%s\' is a directory.', // replace %s with directory
	'Directory_not_writeable' => 'Directory \'%s\' is not writable. You\'ll have to create the upload path and chmod it to 777 (or change the owner to you httpd-servers owner) to upload files.<br />If you have only plain ftp-access change the \'Attribute\' of the directory to rwxrwxrwx.', // replace %s with directory

	'Ftp_error_connect' => 'Could not connect to FTP Server: \'%s\'. Please check your FTP-Settings.',
	'Ftp_error_login' => 'Could not login to FTP Server. The Username \'%s\' or the Password is wrong. Please check your FTP-Settings.',
	'Ftp_error_path' => 'Could not access ftp directory: \'%s\'. Please check your FTP Settings.',
	'Ftp_error_upload' => 'Could not upload files to ftp directory: \'%s\'. Please check your FTP Settings.',
	'Ftp_error_delete' => 'Could not delete files in ftp directory: \'%s\'. Please check your FTP Settings.<br />Another reason for this error could be the non-existence of the Attachment, please check this first in Shadow Attachments.',
	'Ftp_error_pasv_mode' => 'Unable to enable/disable FTP Passive Mode',

// Attach Rules Window
	'Rules_page' => 'Attachment Rules',
	'Attach_rules_title' => 'Allowed Extension Groups and their Sizes',
	'Group_rule_header' => '%s -> Maximum Upload Size: %s', // Replace first %s with Extension Group, second one with the Size STRING
	'Allowed_extensions_and_sizes' => 'Allowed Extensions and Sizes',
	'Note_user_empty_group_permissions' => 'NOTE:<br />You are normally allowed to attach files within this Forum, <br />but since no Extension Group is allowed to be attached here, <br />you are unable to attach anything. If you try, <br />you will receive an Error Message.<br />',

// Quota Variables
	'Upload_quota' => 'Upload Quota',
	'Pm_quota' => 'PM Quota',
	'User_upload_quota_reached' => 'Sorry, you have reached your maximum Upload Quota Limit of %d %s', // replace %d with Size, %s with Size Lang (MB for example)

// User Attachment Control Panel
	'User_acp_title' => 'User ACP',
	'UACP' => 'User Attachment Control Panel',
	'User_uploaded_profile' => 'Uploaded: %s',
	'User_quota_profile' => 'Quota: %s',
	'Upload_percent_profile' => '%d%% of total',

// Common Variables
	'Attach_search_query' => 'Search Attachments',
	'Test_settings' => 'Test Settings',
	'Not_assigned' => 'Not Assigned',
	'No_file_comment_available' => 'No File Comment available',
	'Attachbox_limit' => 'Attachbox [%d%% full]',
	'No_quota_limit' => 'No Quota Limit',
	'Unlimited' => 'Unlimited',
	)
);

?>