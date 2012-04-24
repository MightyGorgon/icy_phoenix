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
* Smartor (smartor_xp@hotmail.com)
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
// 01 - Configuration
	'Album_config' => 'Album',
	'Album_config_explain' => 'Change the Photo Album settings here.<br />',
	'Album_config_updated' => 'Album Configuration has been updated successfully',
	'Click_return_album_config' => 'Click %sHere%s to return to the Album Configuration',
	'Max_pics' => 'Maximum pics for each Category (-1 = unlimited)',
	'User_pics_limit' => 'Pics limit per category for each user (-1 = unlimited)',
	'Moderator_pics_limit' => 'Pics limit per category for each moderator (-1 = unlimited)',
	'Pics_Approval' => 'Pics Approval',
	'Rows_per_page' => 'Number of rows on thumbnail page',
	'Cols_per_page' => 'Number of columns on thumbnail page',
	'Thumbnail_quality' => 'Thumbnail quality (1-100)',
	'Thumbnail_cache' => 'Thumbnail cache',
	'Manual_thumbnail' => 'Manual thumbnail',
	'GD_version' => 'Optimize for the version of GD',
	'Pic_Desc_Max_Length' => 'Pic Description/Comment Max Length (bytes)',
	'Hotlink_prevent' => 'Hotlink Prevention',
	'Hotlink_allowed' => 'Allowed domains for hotlink (separated by a comma)',
	'Personal_gallery' => 'Allowed to create personal gallery for users',
	'Personal_gallery_limit' => 'Pics limit for each personal gallery (-1 = unlimited)',
	'Personal_gallery_view' => 'Who can view personal galleries by default',
	'Rate_system' => 'Enable rating system',
	'Rate_Scale' => 'Rating Scale',
	'Comment_system' => 'Enable comment system',
	'Thumbnail_Settings' => 'Thumbnail Settings',
	'Quick_Thumbnails' => 'Quick Thumbnails',
	'Quick_Thumbnails_explain' => 'Enable this option for a system-check if thumbnails have already been generated and try to send them directly to the browser. This will speed up thumbnails generation when viewing cats.',
	'Extra_Settings' => 'Extra Settings',
	'Default_Sort_Method' => 'Default Sort Method',
	'Default_Sort_Order' => 'Default Sort Order',
	'Fullpic_Popup' => 'View full pic as a popup',
	'Email_Notification' => 'Enable email notification on new images on album (only to admins)',
	'Show_Download' => 'Show DOWNLOAD button (which enables the downloading of pictures in ZIP format) only to those who have UPLOAD permission in the Album (if you choose ALWAYS the button will be always available even if the users have no UPLOAD permissions)',
	'Show_Slideshow' => 'Enable Slideshow feature',
	'Show_Slideshow_Script' => 'Enable transition effects for Slideshow',
	'Show_Pic_Size' => 'Show the pic size on thumbnail',
	'Show_IMG_No_GD' => 'Show GIF thumbnails without using GD libraries (full images are loaded and then just shown resized).',
	'Show_GIF_MidThumb' => 'Show full GIF images if Mid Thumb is enabled.',
	'Show_Pics_Nav' => 'Show Picture Navigation Box in Show Page',
	'Invert_Nav_Arrows' => 'Invert the Arrows link in Showpage (right arrow = more recent)',
	'Show_Inline_Copyright' => 'Show Copyright Info on a single line',
	'Enable_Nuffimage' => 'Enable Pictures Special Effects page based on Nuffmon Images Class',
	'Enable_Sepia_BW' => 'Enable Sepia and B&W in Special Effects page (disable this function if you want to not load the server\'s CPU)',
	'Show_EXIF_Info' => 'Show picture EXIF information',
	'Set_Memory' => 'Set a memory limit via PHP (MB)',
	'Set_Memory_Explain' => 'This feature may be used to increase memory limit in PHP (this setting must be allowed by your hosting IP): you can try to increase memory when your images are not loaded correctly. To avoid higher memory limits the max value is forced to be 16MB.',
	'LB_Preview' => 'Enable LightBox Preview',
	'LB_Preview_Explain' => 'This feature will show a LightBox with picture preview when moving the mouse over a thumbnail.',
	'Album_config_notice' => 'If you change the current Photo Album settings and then select another tab, you will be prompted to save your changes.<br />The system will <b>not save</b> the changes for you automatically.',
	'Save_sucessfully_confimation' => '%s was saved successfully',
	'Show_Recent_In_Subcats' => 'Show recent pictures in subcategories',
	'Show_Recent_Instead_of_NoPics' => 'Show recent pictures instead of no picture message',
	'Show_Last_Comments' => 'Display last comments block on album index and categories',
	'Album_Index_Settings' => 'Album Index',
	'Album_Index_Page_Settings' => 'Album Index Page Settings',
	'Show_Index_Subcats' => 'Show subcategories in index table',
	'Show_Index_Thumb' => 'Show category thumbnails in index table',
	'Show_Index_Pics' => 'Show the number of pictures in current category in index table',
	'Show_Index_Comments' => 'Show the number of comments in current category in index table',
	'Show_Index_Total_Pics' => 'Show the number of total pictures for current categories and all its subcategories in index table',
	'Show_Index_Total_Comments' => 'Show the number of total comments for current categories and all its subcategories in index table',
	'Show_Index_Last_Comment' => 'Show last comments for current categories and all its subcategories in index table',
	'Show_Index_Last_Pic' => 'Show last picture info for current categories and all its subcategories in index table',
	'Line_Break_Subcats' => 'Show each subcategory on a new line',
	'Show_Personal_Gallery_Link' => 'Show Personal Gallery and Users Personal Gallery link in Subcategories',
	'Album_Personal_Auth_Explain' => 'Choose which usergroup(s) can be the moderators for <b>all</b> personal album categories or just has the private access to them',
	'Album_debug_mode' => 'Enable the hierarchy debug mode.<br /><span class="gensmall">This will generate a lot of extra output on the page and also some header warnings, which are all ok.<br />This option should <b>only</b> be used when having problems.</span>',
	'New_Pic_Check_Interval' => 'The time to use to see if a picture is new or not.<br /><span class="gensmall"><b>Format</b> : &lt;number&gt;&lt;type&gt; Where type is either h, d, w or m (hour, day, week or month)<br /> e.g. 12H = 12 hours and 12D = 12 days and 12W = 12 weeks and 12M = 12 months<br />If no type is specified the system will use <b>days</b></span>',
	'New_Pic_Check_Interval_Desc' => '<span class="gensmall">H = HOURS, D = DAYS, W = WEEKS, M = MONTHS</span>',
	'New_Pic_Check_Interval_LV' => 'Enabling this option the new pics counter is based on users last visit time.',
	'Enable_Show_All_Pics' => 'Enable toggling of personal gallery view mode (all pictures or only selected category).<br /> When set to <b>no</b>, only selected category is shown.',
	'Enable_Index_Supercells' => 'Enable super cells in the index table. <br /><span class="gensmall">This will enable the mouseover effects on the columns, also knows as the supercell effect.</span>',
	'Show_OTF_Link' => 'Show "Album OTF" link on Album Index',
	'Show_AllPics_Link' => 'Show "All Pics" link on Album Index',
	'Show_PG_Link' => 'Show "Personal Galleries" link on Album Index',
Album_Category_Sorting
// 02 - Personal Galleries
	'Personal_Galleries' => 'Personal Galleries',
	'Album_personal_gallery_title' => 'Personal Gallery',
	'Album_personal_gallery_explain' => 'Choose which usergroups have right to create and view personal galleries. These settings only affect when you set "PRIVATE" for "Allowed to create personal gallery for users" or "Who can view personal galleries" in Album Configuration screen',
	'Album_personal_successfully' => 'The setting has been updated successfully',
	'Click_return_album_personal' => 'Click %sHere%s to return to the Personal Gallery Settings',
	'Allow_Album_Avatars' => 'Allow users to use own posted images in Album as Avatar',
	'Album_Personal_Settings' => 'Personal Galleries',
	'Show_Personal_Sub_Cats' => 'Show personal subcategories in index table',
	'Personal_Gallery_Approval' => 'Personal gallery pics approval',
	'Personal_Gallery_MOD' => 'Personal gallery can be moderated by owner',
	'Personal_Sub_Cat_Limit' => 'Maximum number of subcategories (-1 = unlimited)',
	'User_Can_Create_Personal_SubCats' => 'Users can create subcategories in own personal gallery',
	'Click_return_personal_gallery_index' => 'Click %sHere%s to return to the personal gallery index',
	'Show_Recent_In_Personal_Subcats' => 'Show recent pictures in personal subcategories',
	'Show_Recent_Instead_of_Personal_NoPics' => 'Show recent pictures instead of no picture message in personal gallery',

// 03 - Categories
	'Categories' => 'Album Categories',
	'Album_Categories_Title' => 'Album Categories',
	'Album_Categories_Explain' => 'Manage your categories: create, alter, delete or sort, etc.',
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
	'Click_return_album_category' => 'Click %sHere%s to return to the Album Categories Manager',
	'Category_updated' => 'This category has been updated successfully',
	'Delete_Category' => 'Delete Category',
	'Delete_Category_Explain' => 'Delete a category and decide where you want to put pics it contained',
	'Delete_all_pics' => 'Delete all pics',
	'Category_deleted' => 'This category has been deleted successfully',
	'Category_changed_order' => 'This category has been changed in order successfully',
	'Personal_Root_Gallery' => 'Personal Gallery Root Category',
	'Parent_Category' => 'Parent Category (for this category)',
	'Child_Category_Moved' => 'Selected category had child categories. The child categories got moved to the <b>%s</b> category.',
	'No_Self_Refering_Cat' => 'You cannot set a category\'s parent to itself',
	'Can_Not_Change_Main_Parent' => 'You cannot change to parent of the main category of your personal gallery',
	'Watermark' => 'WaterMark',
	'Watermark_explain' => 'You can specify the watermark file to be used in this category. Insert the watermark file path respect to your Icy Phoenix root (i.e.: <b>images/album/mark_fap.png</b>). The watermark will be applied only if Watermark feature is on.',
	'Cat_Pics_Synchronize' => 'Sync Pics Counter',
	'Cat_Pics_Synchronized' => 'All pictures counter have been synchronized.',

// 04 - Permissions
	'Album_Auth_Title' => 'Album Permissions',
	'Album_Auth_Explain' => 'Choose which usergroup(s) can be the moderators for each album category or just has the private access',
	'Select_a_Category' => 'Select a Category',
	'Look_up_Category' => 'Look up Category',
	'Album_Auth_successfully' => 'Auth has been updated successfully',
	'Click_return_album_auth' => 'Click %sHere%s to return to the Album Permissions',
	'Upload' => 'Upload',
	'Rate' => 'Rate',
	'Comment' => 'Comment',

// 05 - Thumbnails
	'Use_Old_Thumbnails' => 'Use old thumbnails functions',
	'Use_Old_Thumbnails_Explain' => 'If you enable this feature, you will use the old thumbnails functions to generate small and mid thumbnails and a quick full pic loading. You should use this feature only if you are having problems without enabling it.',

// 06 - Sorting
	'Album_Category_Sorting' => 'Sorting of the album categories',
	'Album_Category_Sorting_Id' => 'ID',
	'Album_Category_Sorting_Name' => 'Name',
	'Album_Category_Sorting_Order' => 'Sort Order (default)',
	'Album_Category_Sorting_Direction' => 'Sorting direction (only valid for ID and Name sorting)',
	'Album_Category_Sorting_Asc' => 'Ascending',
	'Album_Category_Sorting_Desc' => 'Descending',

	'Album_Picture_Sorting' => 'Sorting of the album pictures',
	'Album_Picture_Sorting_Time' => 'Time',
	'Album_Picture_Sorting_Title' => 'Image',
	'Album_Picture_Sorting_View' => 'View',
	'Album_Picture_Sorting_Direction' => 'Sorting direction',
	'Album_Picture_Sorting_Asc' => 'Ascending',
	'Album_Picture_Sorting_Desc' => 'Descending',

// 07 - Clear Cache
	'Clear_Cache_Tab' => 'Cache',
	'Clear_Cache' => 'Clear Cache',
	'Album_clear_cache_confirm' => 'If you use the Thumbnail Cache feature you must clear your thumbnail cache after changing your thumbnail settings in Album Configuration to make them re-generated.<br /><br /> Do you want to clear them now?',
	'Thumbnail_cache_cleared_successfully' => '<br />Your thumbnail cache has been cleared successfully<br />&nbsp;',

// ACP - Javascript text
	'acp_ask_save_changes' => 'Do you want to save the changes ?\n(OK = Yes, Cancel = No)',
	'acp_nothing_to_save' => 'Nothing to save!',
	'acp_settings_changed_ask_save' => 'You have changed the settings. Do you want to save them?\n(OK = Yes, Cancel = No)',

// GD Info
	'GD_Info' => 'GD Info',
	'GD_Title' => 'GD Info',
	'GD_Description' => 'Retrieve information about the currently installed GD library',
	'GD_Version' => 'Version:',
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

// Multiple Uploads Admin configuration
	'Upload_Settings' => 'Upload',
	'Max_Files_To_Upload' => 'Maximum number of files user can upload at a time',
	'Album_upload_settings' => 'Album Upload Settings',
	'Max_pregenerated_fields' => 'Maximum number of fields to pre-generate',
	'Dynamic_field_generation' => 'Enable dynamic adding of upload fields',
	'Pre_generate_fields' => 'Pre-generate the upload fields',
	'Propercase_pic_title' => 'Proper-case picture title e.g. <i>\'This Is A Picture Title\'</i><br />Setting it to \'NO\' will result in this <i>\'This is a picture title\'</i>',
	'Pic_Resampling' => 'Enabling this option, each image will be resized on the fly if needed (to keep image properties respecting the album settings in ACP).',
	'Max_file_size_resampling' => 'Maximum file size before re-sampling (bytes)',

// CLowN
	'SP_Album_config' => 'CLowN SP',
	'SP_Album_config_explain' => 'Configure some options for the Album Service Pack',
	'SP_Album_sp_general' => 'General Config',
	'SP_Album_sp_watermark' => 'WaterMark Config',
	'SP_Album_sp_hotornot' => 'Hot or Not Config',
	'SP_Rate_type' => 'Select picture rating display',
	'SP_Rate_type_0' => 'Images only',
	'SP_Rate_type_1' => 'Numbers only',
	'SP_Rate_type_2' => 'Numbers and Images',
	'SP_Display_latest' => 'Display latest submitted pictures block',
	'SP_Display_highest' => 'Display highest rated pictures block',
	'SP_Display_most_viewed' => 'Display most viewed pictures block',
	'SP_Display_random' => 'Display random pictures block',
	'SP_Pic_row' => 'Number of rows on thumbnail blocks',
	'SP_Pic_col' => 'Number of columns on thumbnail blocks',
	'SP_Midthumb_use' => 'Use mid-thumbnail',
	'SP_Midthumb_cache' => 'Enable caching of mid-thumbnail',
	'SP_Midthumb_high' => 'Height of mid-thumbnail (pixel)',
	'SP_Midthumb_width' => 'Width of mid-thumbnail (pixel)',
	'SP_Watermark' => 'Use WaterMark',
	'SP_Watermark_users' => 'Show WaterMark for all users, if \'No\' only display to unregistered users',
	'SP_Watermark_placent' => 'WaterMark position on the picture',
	'SP_Hon_already_rated' => 'Unlimited rating on Hot or Not page',
	'SP_Hon_sep_rating' => 'Store Hot or Not rating in a separate table',
	'SP_Hon_where' => 'Display pictures on hot or not from what categories? (leave blank to use pictures from all of the categories, if more then one category, separate by commas)',
	'SP_Hon_users' => 'Can unregistered users rate',

	'SP_Disabled' => 'Disabled',
	'SP_Enabled' => 'Enabled',
	'SP_Yes' => 'Yes',
	'SP_No' => 'No',
	'SP_Always' => 'Always',
	'SP_Submit' => 'Submit',
	'SP_Reset' => 'Reset',

// Nuffload
	'Nuffload_Config' => 'Nuffload Configuration',
	'Enable_Nuffload' => 'Enable Nuffload',
	'Enable_Nuffload_Explain' => 'Enabling this option, Nuffload will be used instead of the standard upload form.',
	'progress_bar_configuration' => 'Nuffload - Progress Bar Configuration',
	'perl_uploader' => 'Enable Perl uploader',
	'path_to_bin' => 'Path from icyphoenix root to cgi-bin (i.e. <b>./cgi-bin/</b> if you have icyphoenix in a sub folder)',
	'show_progress_bar' => 'Show progress bar on upload',
	'close_progress_bar' => 'Close progress bar on finish',
	'activity_timeout' => 'Activity timeout (secs)',
	'simple_format' => 'Use simple formatting for progress bar',
	'multiple_uploads_configuration' => 'Nuffload - Multiple Uploads Configuration',
	'multiple_uploads' => 'Enable multiple uploads',
	'max_uploads' => 'Maximum upload fields',
	'zip_uploads' => 'Enable zip uploads',
	'image_resizing_configuration' => 'Nuffload - Image Resizing Configuration',
	'image_resizing' => 'Enable image resizing',
	'image_width' => 'Image width',
	'image_height' => 'Image height',
	'image_quality' => 'Image quality',
	)
);
?>
