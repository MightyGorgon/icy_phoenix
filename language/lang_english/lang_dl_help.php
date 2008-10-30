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
* no help found?
*/
	'Dl_no_help_aviable' => 'There is no help available for this option',

/*
* title of help popup
*/
	'HELP_TITLE' => 'Download MOD Online Help',

/*
* general configuration
*/
	'HELP_Dl_delay_auto_traffic' => 'Enter the days after which a new user will get the first auto traffic.<br />The delay starts with the registration day.<br />Enter 0 to disable.',
	'HELP_Dl_delay_post_traffic' => 'Enter the number of days after which a new user will get the first traffic for posts.<br />The delay starts with the registration day.<br />Enter 0 to disable.',
	'HELP_DL_edit_time' => 'Enter the number of days how long an edited download will be marked for.<br />Enter 0 to disable.',
	'HELP_Dl_guest_stats_show' => 'This option will only include or exclude the statistical data about guests from the public category statistics.<br />The script will still collect all data.<br />The ACP statistics tool always displays the complete statistical data.',
	'HELP_Dl_method' => 'The "old" method will send the file directly to the web client.<br />This method is compatible with most web environments, but cannot display the real filesize while downloading, so the user client cannot calculate the remaining download time.<br />The "new" method will display the real filesize, but it can produce errors.<br />Use the "old" method, if you have trouble with the new one.<br />If nothing works, check the box "direct" to link the download directly to the file on the server if this is bigger than the PHP memory limit.',
	'HELP_Dl_method_quota' => 'Set the filesize here from which of the chunked file will be read as a big file if you have chosen the "new" download method.<br />Under this quota the file will be fetched by readfile(); and sent directly to the web client.',
	'HELP_DL_new_time' => 'Enter how long a new download will be marked for (In Days).<br />Enter 0 to disable this function.',
	'HELP_Download_path' => 'The relative path under your icyphoenix root folder.<br />On first installation of this package you will find the value "downloads/".<br />Regard case-sensitve names while renaming this folder if you are using Unix/Linux.<br />The slash at the end of the folder name is required, but do not add a slash at the beginning.<br />This and all included subfolders must be at CHMOD 777 to allow all functions to work.<br />Under this folder you must create one or more subfolders which will contain all physical files.<br />It is recommended to create a subfolder for each logical group of categories.<br />This subfolder must be entered as a path in the category with the same syntax as the main folder, but without using the main folder (for more information, read the help in the category management).<br />You can create more subfolders by using an ftp client or by using the Toolbox (see the link on the top right of this page).',
	'HELP_Dl_thumb_max_size' => 'Enter 0 as filesize to disable thumbnails in all categories.<br />If you allow thumbnails, then enter useful image sizes for new thumbnails.<br />If you disable thumbnails you will also not be able to see existing thumbnails in the download details.',
	'HELP_Dl_use_ext_blacklist' => 'If you enable the blacklist, all entered filetypes will be blocked for newly uploaded or edited downloads.',
	'HELP_Dl_enable_post_traffic' => 'Set the amount of traffic a user will get for creating new topics, reply or quote etc, in the next two options.',
	'HELP_Dl_limit_desc_on_index' => 'Cut the descriptions after the entered number of characters.<br />Enter 0 to disable.',
	'HELP_Dl_prevent_hotlink' => 'Enable this option if you want to prevent each direct download link except from the download details.<br />This option does <b>not</b> build folder protection!',
	'HELP_Dl_user_traffic_once' => 'Choose if downloads should only decrease the user traffic for the first time download.<br />This option will NOT change the download status itself!',
	'HELP_Dl_edit_own_downloads' => 'If you enable this option, each user will be able to edit their own uploaded files.',
	'HELP_Dl_shorten_extern_links' => 'Enter the length of the displayed external download link on the download details.<br />Based on the length of the link it will be cut in the middle, or shortened - beginning from the right side.<br />Leave this field empty or enter 0 to disable.',
	'HELP_Dl_show_footer_legend' => 'This option will turn the legend with the download status icons in the download footer on or off.<br />The icons which are beside the downloads will not be changed by this option.',
	'HELP_Dl_show_footer_stat' => 'This option will hide or show the statistics lines in the download footer.',
	'HELP_Dl_show_real_filetime' => 'Display the real last edit time of the download files in the download details.<br />This is the exact timecode even if files are uploaded with an ftp client or not updated while editing the downloads.',
	'HELP_Dl_visual_confirmation' => 'Enable this option to force the user enter a 5 digit confirmation code to access the download.<br />If the user does not enter the code correctly the MOD will display a message and stop the download.',
	'HELP_Dl_report_broken' => 'Turn the function on or off to report broken downloads.<br />If you set it to "not for guests", only registered users can report downloads.',
	'HELP_Dl_report_broken_lock' => 'If you enable this option the download will be locked while it is reported as broken.<br />It will hide the download button and no one can download the file until an Administrator or Download Moderator has unlocked it.',
	'HELP_Dl_report_broken_message' => 'If a download is reported as broken, a message will be displayed.<br />If you enable this option the message will only appear while the download is locked.<br />In this case not under but instead the download button is replaced.',
	'HELP_Dl_report_broken_vc' => 'Enables visual confirmation if a user wishes to report a broken download.<br />If the code was correct, the report will be saved and Administrators and Download Moderators will be informed by email.',
	'HELP_Dl_Links_per_page' => 'This option controlls, how many downloads will be displayed on each category page and ACP statistics.<br />In the hacklist and overview list, the board setting "topics per page" will be used.',
	'HELP_Number_recent_dl_on_portal' => 'The number of latest downloads users can see on the portal.<br />Note: The block parses the last edit time for this list, so it is possible to have an older download on top of this list.',
	'HELP_DL_posts' => 'Each user, Administrator and Download Moderator, must have posted at least this number of posts to be able to download non-free downloads.<br />It is recommended you also install an Anti-Spam MOD to avoid spam posts.<br />Enter 0 to disable. (recommended for young boards).',

	'HELP_Dl_physical_quota' => 'The overall physical limit the MOD will be able to use to save and manage downloads.<br />If this limit is reached, new downloads can only be added when they are uploaded with an ftp client and added with the file management in the ACP.',
	'HELP_Dl_overall_traffic' => 'The overall limit for all downloads and, if enabled - also includes all uploads which cannot be exceeded in the current month.<br />After reaching this limit, each download will be marked and locked and, if enabled, uploads will be impossible also.',
	'HELP_Dl_newtopic_traffic' => 'For each new posted topic the author will get the entered traffic on top of their traffic amount.',
	'HELP_Dl_reply_traffic' => 'For each new reply and quote the user will get the entered traffic on top of their traffic amount.',
	'HELP_Dl_stop_uploads' => 'Enable or disable uploads with this option.<br />If you disable this option, only adminstrators will be able to upload new files.<br />Enable this option to allow users to upload, depending on the category and group permissions.',
	'HELP_Dl_upload_traffic_count' => 'If the option is enabled, uploads will lower the monthly overall traffic also.<br />After the overall limit has been reached no upload will be possible and new downloads must be uploaded with an ftp client and added in the ACP.',
	'HELP_Dl_thumb_max_dim' => 'This value will limit the possible image size of uploaded thumbnails.<br />Enter 0 to disable thumbnails (not recommended if the thumbnail filesize has slready been set).<br />Existing thumbnails prior to changing this will still be displayed.',
	'HELP_Dl_disable_email' => 'Enable or disable email notification about new, added or edited downloads.<br />While this function is enabled here, it can individually be disabled while adding or editing a download.<br />Only users who have activated email notifications for new or edited downloads in their download configuration will receive them.',
	'HELP_Dl_disable_popup' => 'Enable or disable popup notifications or board message in the forum header about new, added or edited downloads.<br />While this function is enabled here, it can individually be disabled while adding or editing a download.<br />Only users who have activated the popup notifications about new or edited downloads in their download configuration will receive them.',
	'HELP_Dl_disable_popup_notify' => 'If this option is enabled, you can disable it to change the edit time while editing a download.',

	'HELP_Dl_stat_perm' => 'Select from which userlevel users can view the download statistic page.<br />E.g. if you enable it for Download Moderators, board administrators and download moderators (NOT forum moderator!) can open and view this page.<br />Note that this page can produce a heavy load, so we recommended you do not open it for the masses if you have a large board and/or manage many downloads.',
	'HELP_Dl_hotlink_action' => 'Choose how the download script should react while it prevents a direct link to a download (also see the last option).<br />It will display a message (reduces the server load) or it redirects to the download (produces additional traffic).',
	'HELP_Dl_use_hacklist' => 'Switch the hacklist on or off.<br />If enabled, you can enter hack information while adding or editing a download and insert the download into the hacklist.<br />If you disable the hacklist, it will be completely hidden from each user as if it\'s not installed, but you can enable it anytime.<br />Note: Each hacks information in the downloads will be lost if you edit the file after the hacklist was disabled.',
	'HELP_Dl_icon_free_for_reg' => 'Switch the white icon for downloads (free download for registered users, and guests.)<br />If you disable this option, guests will see the red icon instead of the white one.',
	'HELP_Dl_latest_comments' => 'This option displays the latest X comments of the download details. Enter 0 to disable.',
	'HELP_Dl_sort_preform' => 'The option "Preset" will sort all downloads in all categories for all users like they are sorted in the ACP.<br />With the option "User" each user can select how downloads will be sorted for him/her even if this sorting is fixed or extended with other sort criteria.',

/*
* category management
*/
	'HELP_Dl_approve_comments' => 'If you disable this option, each new comment must be approved by a download moderator or administrator before other user can see them.',
	'HELP_Dl_cat_rules' => 'These rules will be displayed over the subcategories and downloads while viewing the category.',
	'HELP_Dl_stats_prune' => 'Enter the number of data rows the statistics for this category can reach.<br />Each new row will delete the oldest one.<br />Enter 0 to disable pruning.',
	'HELP_Dl_cat_traffic' => 'Enter the maximum monthly traffic for this category.<br />This traffic does not increase the overall traffic!<br />Enter 0 to disable the limit.',
	'HELP_Dl_cat_path' => 'You must enter an existing path to your downloads.<br />This value must be the name of a subfolder under the main folder (e.g. downloads/) which you have defined in the main configuration.<br />Enter the foldername with a slash at the end.<br />For example, if there exists the folder "downloads/mods/" you must enter a category path just "mods/".<br />If you send this form, the folder will be checked.<br />Be sure, the entered subfolder really exists!<br />If the folder is a subfolder of a subfolder, enter the complete hierarchy here.<br />E.g. "downloads/mods/misc/" must be entered as category path "mods/misc/".<br />Be sure that each subfolder has CHMOD 0777 permission and case sensitive foldernames if you uses Unix/Linux.',
	'HELP_Dl_cat_name' => 'This is the name of the category which is shown at every point.<br />Try to avoid special chars to avoid confused entries in the jumpbox.',

	'HELP_Dl_cat_description' => 'A short description for this category.<br />BBCodes are not aviable here.<br />This description will be shown on the downloads index and on subcategories.',
	'HELP_Dl_cat_parent' => 'The main level or another category this category can be joined to.<br />With this dynamic drop down you can build hierarchical structures for your downloads.',
	'HELP_Dl_must_approve' => 'Enable this option to approve each new uploaded download file before it is displayed in this category.<br />Administrators and Download Moderators will get an email about each new unapproved download.',
	'HELP_Dl_mod_desc_allow' => 'Enables the mod information block while adding or editing a download.',
	'HELP_Dl_statistics' => 'Enables detailed statistics about the files.<br />Note that these statistics will produces additional database queries and datasets in a separate table.',
	'HELP_Dl_comments' => 'Activate the comment system for this category.<br />Users you can enable with the upcoming drop-downs, can view and/or post comments in this category.<br />Administrators and Download Moderators can edit and delete all comments, and authors can manage their own text.',
	'HELP_Dl_thumb_cat' => 'Enable Thumbnails on downloads in this category.<br />The size of these Images will be based on the settings in the main configuration of this MOD.',
	'HELP_Dl_bug_tracker_cat' => 'Enables the Bug Tracker for downloads in this category.<br />Bugs can be posted and viewed by every registered user for the related downloads and from other categories, if the bug tracker is enabled globally.<br />Only Administrators and Board Moderators can manage the bugs.<br />For each update of the bug-status the author, and the team member working on the bug, will receive an email',

/*
* file management
*/
	'HELP_Dl_name' => 'This is the name of the downloads which will shown at the different places.<br />Try to avoid special chars to reduce display errors.',
	'HELP_Dl_choose_category' => 'Choose the category which will include this download.<br />The file must already be saved in the folder you have entered in the category management before you can save this download.<br />Otherwise you will get an error message.',
	'HELP_Dl_file_description' => 'A short description for this download.<br />This will be displayed in the download category.<br />BBCodes are off for this text.<br />Please enter only a short description to reduces heavy data loads while opening the category.',
	'HELP_Dl_files_url' => 'The filename of this download.<br />Enter this name without a leading file path or slash.<br />The file must exist before saving this download otherwise you will get an error message.<br />Note forbidden file extentions: Each file which has a forbidden extention will be blocked.',
	'HELP_Dl_upload_file' => 'Upload file from your computer.<br />Be sure, the filesize is smaller than the shown limit, and the file extention is not included in the list you can see under this field.',
	'HELP_Dl_extern' => 'Activate this function for the external file you enter in the field above (http://www.example.com/media.mp3).<br />The function "free" becomes insignificant.',
	'HELP_Dl_extern_up' => 'Activate this function for an external file which you enter in the right field (http://www.example.com/media.mp3). The function "free" becomes insignificant.',
	'HELP_Dl_thumb' => 'This field can upload a small image (note the displayed file size and image dimensions under this field) to display it in the download details.<br />If there\'s already an existing thumbsnail, you can upload a new one to replace it.<br />Check the existing thumbnail for "delete" to drop it.',
	'HELP_Dl_is_free' => 'Activate this function if the download is free to download for everybody.<br />The traffic accounts will not be used.<br />Choose free for reg. Users to enable a free download only for registered users.',
	'HELP_Dl_traffic' => 'The maximum traffic a file will be allowed to produce.<br />A value of 0 deactivates the traffic control',

	'HELP_Dl_approve' => 'This will approve the download immediately when you submit this form.<br />On the other hand this download will be hidden for users.',
	'HELP_Dl_no_change_edit_time' => 'Check this option to suppress to update the latest edit time for this download.<br />This will not affect the email and popup notification/board message.',
	'HELP_Dl_disable_popup_files' => 'Check this option to suppress the popup notification/board message.<br />This will not affect the email notifications or updating of the last edit time.',
	'HELP_Dl_disable_email_files' => 'Check this option to suppress the email notification.<br />This will not affect the popup notification/board message or updating of the last edit time.',
	'HELP_Dl_hacklist' => 'Add this download to the hacklist? (this must be enabled in the main configuration).<br />No. will not insert the download into the hacklist.<br />\'Show extra information\' will display this block only in the download details.',
	'HELP_Dl_hack_version' => 'The declaration about the download release.<br />This will be at all times displayed beside the download.<br />You cannot search for this.',
	'HELP_Dl_hack_autor' => 'The author of this download file.<br />Leave this empty to hide this value at the download details and overall view.',
	'HELP_Dl_hack_autor_email' => 'The email adress of the author.<br />If you do not enter it here, it will be hidden at the download details and overall view.',
	'HELP_Dl_hack_autor_website' => 'Website of the author.<br />This URL should be the website of the author, not the page for the download (not always the same).<br />Please do not enter links to protected sites or websites with doubtful contents.',
	'HELP_Dl_hack_dl_url' => 'The page to an alternative download for this file.<br />This can be the website of the author or another alternative website.<br />Please do not enter links for a direct download if the author does not allow this.',
	'HELP_Dl_mod_desc' => 'Detailed descriptions about the MOD.<br />You can use BBCodes and Smileys in this text.<br />Line feeds will all be formatted.<br />This text will only be shown in the download details.',
	'HELP_Dl_mod_list' => 'Activate this block in the download details.<br />If you do not enable this option, the complete block will not be displayed.',
	'HELP_Dl_mod_require' => 'Declarations which other MODs a user needs to install or use this Download for.<br />This text will only be shown in the download details.',
	'HELP_Dl_mod_test' => 'Declare on which Icy Phoenix this MOD was tested successfully.<br />Simply enter the release from the test board.<br />The script will display it as phpBB X, so you must only enter X here.<br />This text will only be shown in the download details.',
	'HELP_Dl_mod_todo' => 'Enter the next steps you have planned for this MOD or what\'s currently in work.<br />This will create the ToDo list which can be opened from the downloads footer.<br />With this text a user will be informed about the latest status of this MOD.<br />Line feeds will be formatted, BBCodes are not available here.<br />The ToDo list will still be filled even this block is disabled.',
	'HELP_Dl_mod_warning' => 'Important advice about this MOD which must be regarded on installation, using or interaction with other MODs.<br />This text will be shown in the download details, and formatted with another colour (by default the font colour is red).<br />Line feeds will be formatted.<br />BBCodes are not available here.',

	'HELP_Dl_user_download_limit_flag' => 'This option lets you specify a limit for the number of downloads per month for each user. E.G.: if you enable this and if you set the "Max number of downloads per month" equal to 30, then each user can download only 30 files per month. This limit won\'t be applied to administrators and moderators.',
	'HELP_Dl_user_download_limit' => 'Specify the maximum number of downloads allowed per month for each user. This limit has to be enabled with the switch called "Limit number of downloads per month". This limit doesn\'t apply to administrators and moderators.',
	)
);

?>