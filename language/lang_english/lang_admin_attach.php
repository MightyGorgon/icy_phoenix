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
// Modules, this replaces the keys used
	'Control_Panel' => 'Control Panel',
	'Shadow_attachments' => 'Shadow Attachments',
	'Forbidden_extensions' => 'Forbidden Extensions',
	'Extension_control' => 'Extension Control',
	'Extension_group_manage' => 'Extension Groups Control',
	'Special_categories' => 'Special Categories',
	'Sync_attachments' => 'Synchronize Attachments',
	'Quota_limits' => 'Quota Limits',

// Attachments -> Management
	'Attach_settings' => 'Attachment Settings',
	'Manage_attachments_explain' => '<b>Configure the Main Settings for the Attachment Mod.</b><br /> If you press the Test Settings Button, the Attachment Mod does a few System Tests to ensure that the Mod will work properly. If you have problems with uploading Files, please run this Test to get a detailed error-message.',
	'Attach_filesize_settings' => 'Attachment Filesize Settings',
	'Attach_number_settings' => 'Attachment Number Settings',
	'Attach_options_settings' => 'Attachment Options',

	'Upload_directory' => 'Upload Directory',
	'Upload_directory_explain' => 'Enter the relative path from your Icy Phoenix installation to the Attachments upload directory. For example, enter <b>files</b> if your Icy Phoenix Installation is located at http://www.yourdomain.com/ip and the Attachment Upload Directory is located at http://www.yourdomain.com/ip/files.',
	'Attach_img_path' => 'Attachment Posting Icon',
	'Attach_img_path_explain' => 'This Image is displayed next to Attachment Links in individual Posts. Leave this field empty if you don\'t want an icon to be displayed. This Setting will be overwritten by the Settings in Extension Groups Management.',
	'Attach_topic_icon' => 'Attachment Topic Icon',
	'Attach_topic_icon_explain' => 'This Image is displayed before topics with Attachments. Leave this field empty if you don\'t want an icon to be displayed.',
	'Attach_display_order' => 'Attachment Display Order',
	'Attach_display_order_explain' => 'Choose whether to display the Attachments in Posts/PMs in Descending Filetime Order (Newest Attachment First) or Ascending Filetime Order (Oldest Attachment First).',
	'Show_apcp' => 'Show new Attachment Posting Control Panel',
	'Show_apcp_explain' => 'Choose whether to display the Attachment Posting Control Panel (yes) or the old method with two Boxes for Attaching Files and editing your posted Attachments (no) within your Posting Screen. The look of it is very hard to explain, therefore it\'s best to try it out.',

	'Max_filesize_attach' => 'Filesize',
	'Max_filesize_attach_explain' => 'Maximum filesize for Attachments. A value of 0 means \'unlimited\'. This Setting is restricted by your Server Configuration. For example, if your php Configuration only allows a maximum of 2 MB uploads, this cannot be overwritten by the Mod.',
	'Attach_quota' => 'Attachment Quota',
	'Attach_quota_explain' => 'Maximum Disk Space ALL Attachments can hold on your Web space. A value of 0 means \'unlimited\'.',
	'Max_filesize_pm' => 'Maximum Filesize in Private Messages Folder',
	'Max_filesize_pm_explain' => 'Maximum Disk Space Attachments can use up in each User\'s Private Message box. A value of 0 means \'unlimited\'.',
	'Default_quota_limit' => 'Default Quota Limit',
	'Default_quota_limit_explain' => 'Select the Default Quota Limit automatically assigned to newly registered Users, and Users without a defined Quota Limit. The Option \'No Quota Limit\' is for \'not\' using any Attachment Quotas, instead using the default Settings you have defined within this Management Panel.',

	'Max_attachments' => 'Maximum Number of Attachments',
	'Max_attachments_explain' => 'The maximum number of attachments allowed in one post.',
	'Max_attachments_pm' => 'Maximum number of Attachments in one Private Message',
	'Max_attachments_pm_explain' => 'Define the maximum number of attachments the user is allowed to include in a private message.',

	'Disable_mod' => 'Disable Attachment Mod',
	'Disable_mod_explain' => 'This option is mainly for testing new templates or themes, it disables all Attachment Functions except the Admin Panel.',
	'PM_Attachments' => 'Allow Attachments in Private Messages',
	'PM_Attachments_explain' => 'Allow/Disallow attaching files to Private Messages.',
	'Ftp_upload' => 'Enable FTP Upload',
	'Ftp_upload_explain' => 'Enable/Disable the FTP Upload option. If you set it to yes, you have to define the Attachment FTP Settings and the Upload Directory is no longer used.',
	'Attachment_topic_review' => 'Do you want to display Attachments in the Topic Review Window ?',
	'Attachment_topic_review_explain' => 'If you choose yes, all attached Files will be displayed in Topic Review when you post a reply.',

	'Ftp_server' => 'FTP Upload Server',
	'Ftp_server_explain' => 'Enter the IP-Address or FTP-Hostname of the Server used for your uploaded files. If you leave this field empty, the Server on which your Icy Phoenix is installed will be used. Please note that it is not allowed to add ftp:// or something else to the address, just plain ftp.foo.com or (which is a lot faster) the plain IP Address.',

	'ftp_username' => 'Your FTP Username',
	'ftp_password' => 'Your FTP Password',

	'Attach_ftp_path' => 'FTP Path to your upload directory',
	'Attach_ftp_path_explain' => 'The Directory where your Attachments will be stored. This Directory doesn\'t have to be chmodded. Please don\'t enter your IP or FTP-Address here, this input field is only for the FTP Path.<br />For example: /home/web/uploads',
	'Ftp_download_path' => 'Download Link to FTP Path',
	'Ftp_download_path_explain' => 'Enter the URL to your FTP Path where your Attachments are stored.<br />If you are using a Remote FTP Server, please enter the complete url, for example http://www.mystorage.com/ip/upload.<br />If you are using your Local Host to store your Files, you are able to enter the url path relative to your Icy Phoenix Directory, for example \'upload\'.<br />A trailing slash will be removed. Leave this field empty, if the FTP Path is not accessible from the Internet. With this field empty you are unable to use the physical download method.',
	'Ftp_passive_mode' => 'Enable FTP Passive Mode',
	'Ftp_passive_mode_explain' => 'The PASV command requests that the remote server open a port for the data connection and return the address of that port. The remote server listens on that port and the client connects to it.',

	'No_ftp_extensions_installed' => 'You are not able to use the FTP Upload Methods, because FTP Extensions are not compiled into your PHP Installation.',

// Attachments -> Shadow Attachments
	'Shadow_attachments_explain' => 'Delete attachment data from posts when the files are missing from your file system, and delete files that are no longer attached to any posts. You can download or view a file if you click on it; if no link is present, the file does not exist.',
	'Shadow_attachments_file_explain' => 'Delete all attachments files that exist on your file system and are not assigned to an existing post.',
	'Shadow_attachments_row_explain' => 'Delete all post attachment data for files that don\'t exist on your file system.',
	'Empty_file_entry' => 'Empty File Entry',

// Attachments -> Sync
	'Sync_thumbnail_recreated' => 'Thumbnail created for Attachment: %s', // replace %s with physical Filename
	'Sync_thumbnail_resetted' => 'Thumbnail reset for Attachment: %s', // replace %s with physical Filename
	'Attach_sync_finished' => 'Attachment Syncronization Finished.',
	'Sync_topics' => 'Sync Topics',
	'Sync_posts' => 'Sync Posts',
	'Sync_thumbnails' => 'Sync Thumbnails',


// Extensions -> Extension Control
	'Manage_extensions' => 'Manage Extensions',
	'Manage_extensions_explain' => 'Manage your File Extensions. If you want to allow/disallow an Extension to be uploaded, please use the Extension Groups Management.',
	'Explanation' => 'Explanation',
	'Extension_group' => 'Extension Group',
	'Invalid_extension' => 'Invalid Extension',
	'Extension_exist' => 'The Extension %s already exist', // replace %s with the Extension
	'Unable_add_forbidden_extension' => 'The Extension %s is forbidden, you are not able to add it to the allowed Extensions', // replace %s with Extension

// Extensions -> Extension Groups Management
	'Manage_extension_groups' => 'Manage Extension Groups',
	'Manage_extension_groups_explain' => 'Add, delete and modify your Extension Groups, you can disable Extension Groups, assign a special Category to them, change the download mechanism and you can define an Upload Icon which will be displayed in front of an Attachment belonging to the Group.',
	'Special_category' => 'Special Category',
	'Category_images' => 'Images',
	'Category_stream_files' => 'Stream Files',
	'Category_swf_files' => 'Flash Files',
	'Allowed' => 'Allowed',
	'Allowed_forums' => 'Allowed Forums',
	'Ext_group_permissions' => 'Group Permissions',
	'Download_mode' => 'Download Mode',
	'Upload_icon' => 'Upload Icon',
	'Max_groups_filesize' => 'Maximum Filesize',
	'Extension_group_exist' => 'The Extension Group %s already exist', // replace %s with the group name
	'Collapse' => '+',
	'Decollapse' => '-',

// Extensions -> Special Categories
	'Manage_categories' => 'Manage Special Categories',
	'Manage_categories_explain' => 'Set up Special Parameters and Conditions for the Special Categories assigned to an Extension Group.',
	'Settings_cat_images' => 'Settings for Special Category: Images',
	'Settings_cat_streams' => 'Settings for Special Category: Stream Files',
	'Settings_cat_flash' => 'Settings for Special Category: Flash Files',
	'Display_inlined' => 'Display Images Inline',
	'Display_inlined_explain' => 'Choose whether to display images directly within the post (yes) or to display images as a link ?',
	'Max_image_size' => 'Maximum Image Dimensions',
	'Max_image_size_explain' => 'Define the maximum allowed Image Dimension to be attached (Width x Height in pixels).<br />If it is set to 0x0, this feature is disabled. With some Images this Feature will not work due to limitations in PHP.',
	'Image_link_size' => 'Image Link Dimensions',
	'Image_link_size_explain' => 'If this defined Dimension of an Image is reached, the Image will be displayed as a Link, rather than displaying it inline,<br />if Inline View is enabled (Width x Height in pixels).<br />If it is set to 0x0, this feature is disabled. With some Images this Feature will not work due to limitations in PHP.',
	'Assigned_group' => 'Assigned Group',

	'Image_create_thumbnail' => 'Create Thumbnail',
	'Image_create_thumbnail_explain' => 'Always create a Thumbnail. This feature overrides nearly all Settings within this Special Category, except of the Maximum Image Dimensions. With this Feature a Thumbnail will be displayed within the post, the User can click it to open the real Image.<br />Please Note that this feature requires Imagick to be installed, if it\'s not installed or if Safe-Mode is enabled the GD-Extension of PHP will be used. If the Image-Type is not supported by PHP, this Feature will be not used.',
	'Image_min_thumb_filesize' => 'Minimum Thumbnail Filesize',
	'Image_min_thumb_filesize_explain' => 'If an Image is smaller than this defined Filesize, no Thumbnail will be created, because it\'s small enough.',
	'Image_imagick_path' => 'Imagick Program (Complete Path)',
	'Image_imagick_path_explain' => 'Enter the Path to the conversion program of imagick, normally /usr/bin/convert (on windows: c:/imagemagick/convert.exe).',
	'Image_search_imagick' => 'Search Imagick',

	'Use_gd2' => 'Make use of GD2 Extension',
	'Use_gd2_explain' => 'PHP is able to be compiled with the GD1 or GD2 Extension for image manipulation. To correctly create Thumbnails without imagemagick the Attachment Mod uses two different methods, based on your selection here. If your thumbnails are in a bad quality or screwed up, try to change this setting.',
	'Attachment_version' => 'Attachment Mod Version %s', // %s is the version number

// Extensions -> Forbidden Extensions
	'Manage_forbidden_extensions' => 'Manage Forbidden Extensions',
	'Manage_forbidden_extensions_explain' => 'Add or delete the forbidden extensions. The Extensions php, php3 and php4 are forbidden by default for security reasons, you cannot delete them.',
	'Forbidden_extension_exist' => 'The forbidden Extension %s already exists', // replace %s with the extension
	'Extension_exist_forbidden' => 'The Extension %s is defined in your allowed Extensions, please delete it there before you add it here.', // replace %s with the extension

// Extensions -> Extension Groups Control -> Group Permissions
	'Group_permissions_title' => 'Extension Group Permissions -> \'%s\'', // Replace %s with the Groups Name
	'Group_permissions_explain' => 'Restrict the selected Extension Group to Forums of your choice (defined in the Allowed Forums Box). The Default is to allow Extension Groups to all Forums the User is able to Attach Files into (the normal way the Attachment Mod did since the beginning). Just add those Forums you want the Extension Group (the Extensions within this Group) to be allowed there, the default ALL FORUMS will disappear when you add Forums to the List. You are able to re-add ALL FORUMS at any given Time. If you add a Forum to your Board and the Permission is set to ALL FORUMS nothing will change. But if you have changed and restricted the access to certain Forums, you have to check back here to add your newly created Forum. It is easy to do this automatically, but this will force you to edit a bunch of Files, therefore I have chosen the way it is now. Please keep in mind, that all of your Forums will be listed here.',
	'Note_admin_empty_group_permissions' => 'NOTE:<br />Within the below listed Forums your Users are normally allowed to attach files, but since no Extension Group is allowed to be attached there, your Users are unable to attach anything. If they try, they will receive Error Messages. Maybe you want to set the Permission \'Post Files\' to ADMIN at these Forums.<br /><br />',
	'Add_forums' => 'Add Forums',
	'Add_selected' => 'Add Selected',
	'Perm_all_forums' => 'ALL FORUMS',

// Attachments -> Quota Limits
	'Manage_quotas' => 'Manage Attachment Quota Limits',
	'Manage_quotas_explain' => 'Add, delete or change Quota Limits. You are able to assign these Quota Limits to Users and Groups later. To assign a Quota Limit to a User, you have to go to Users->Management, select the User and you will see the Options at the bottom. To assign a Quota Limit to a Group, go to Groups->Management, select the Group to edit it, and you will see the Configuration Settings. If you want to see which Users and Groups are assigned to a specific Quota Limit, click on \'View\' at the left of the Quota Description.',
	'Assigned_users' => 'Assigned Users',
	'Assigned_groups' => 'Assigned Groups',
	'Quota_limit_exist' => 'The Quota Limit %s already exists.', // Replace %s with the Quota Description

// Attachments -> Control Panel
	'Control_panel_title' => 'File Attachment Control Panel',
	'Control_panel_explain' => 'View and manage all attachments based on Users, Attachments, Views etc...',
	'File_comment_cp' => 'File Comment',

// Control Panel -> Search
	'Search_wildcard_explain' => 'Use * as a wildcard for partial matches',
	'Size_smaller_than' => 'Attachment size smaller than (bytes)',
	'Size_greater_than' => 'Attachment size greater than (bytes)',
	'Count_smaller_than' => 'Download count is smaller than',
	'Count_greater_than' => 'Download count is greater than',
	'More_days_old' => 'More than this many days old',
	'No_attach_search_match' => 'No Attachments met your search criteria',

// Control Panel -> Statistics
	'Number_of_attachments' => 'Number of Attachments',
	'Total_filesize' => 'Total Filesize',
	'Number_posts_attach' => 'Number of Posts with Attachments',
	'Number_topics_attach' => 'Number of Topics with Attachments',
	'Number_users_attach' => 'Independent Users Posted Attachments',
	'Number_pms_attach' => 'Total Number of Attachments in Private Messages',

// Control Panel -> Attachments
	'Statistics_for_user' => 'Attachment Statistics for %s', // replace %s with username
	'Size_in_kb' => 'Size (KB)',
	'Downloads' => 'Downloads',
	'Post_time' => 'Post Time',
	'Posted_in_topic' => 'Posted in Topic',
	'Submit_changes' => 'Submit Changes',

// Sort Types
	'Sort_Attachments' => 'Attachments',
	'Sort_Size' => 'Size',
	'Sort_Filename' => 'Filename',
	'Sort_Comment' => 'Comment',
	'Sort_Extension' => 'Extension',
	'Sort_Downloads' => 'Downloads',
	'Sort_Posttime' => 'Post Time',
	'Sort_Posts' => 'Posts',

// View Types
	'View_Statistic' => 'Statistics',
	'View_Search' => 'Search',
	'View_Username' => 'Username',
	'View_Attachments' => 'Attachments',

// Successfully updated
	'Attach_config_updated' => 'Attachment Configuration updated successfully',
	'Click_return_attach_config' => 'Click %sHere%s to return to Attachment Configuration',
	'Test_settings_successful' => 'Settings Test completed, configuration seems to be fine.',

// Some basic definitions
	'Attachments' => 'Attachments',
	'Attachment' => 'Attachment',
	'Extensions' => 'Extensions',
	'Extension' => 'Extension',

// Auth pages
	'Auth_attach' => 'Post Files',
	'Auth_download' => 'Download Files',
	)
);

?>