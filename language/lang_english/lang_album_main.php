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
// Album Index
	'Photo_Album' => 'Photo Album',
	'Pics' => 'Pics',
	'Last_Pic' => 'Last Pic',
	'Public_Categories' => 'Public Categories',
	'No_Pics' => 'No Pics',
	'Users_Personal_Galleries' => 'Users Personal Galleries',
	'Your_Personal_Gallery' => 'Your Personal Gallery',
	'Recent_Public_Pics' => 'Recent Public Pics',
	'Nav_Separator' => '&nbsp;&raquo;&nbsp;',

// Category View
	'Category_not_exist' => 'This category does not exist',
	'Upload_Pic' => 'Upload Pic',
	'Upload_Pics' => 'Upload Pics',
	'JUpload_Pic' => 'Upload Multiple Pictures with Jupload',
	'Pic_Title' => 'Pic Title',
	'View' => 'View',
	'Pic_Poster' => 'Poster',
	'Pic_Image' => 'Image',
	'Waiting' => ' pic(s) waiting for approval',

/*
	'Album_upload_can' => 'You <b>can</b> upload new pics in this category',
	'Album_upload_cannot' => 'You <b>cannot</b> upload new pics in this category',
	'Album_rate_can' => 'You <b>can</b> rate pics in this category',
	'Album_rate_cannot' => 'You <b>cannot</b> rate pics in this category',
	'Album_comment_can' => 'You <b>can</b> post comments to pics in this category',
	'Album_comment_cannot' => 'You <b>cannot</b> post comments to pics in this category',
	'Album_edit_can' => 'You <b>can</b> edit your pics and comments in this category',
	'Album_edit_cannot' => 'You <b>cannot</b> edit your pics and comments in this category',
	'Album_delete_can' => 'You <b>can</b> delete your pics and comments in this category',
	'Album_delete_cannot' => 'You <b>cannot</b> delete your pics and comments in this category',
*/
	'Album_view_can' => 'You <b>can</b> view pics',
	'Album_view_cannot' => 'You <b>cannot</b> view pics',
	'Album_upload_can' => 'You <b>can</b> upload new pics',
	'Album_upload_cannot' => 'You <b>cannot</b> upload new pics',
	'Album_rate_can' => 'You <b>can</b> rate pics',
	'Album_rate_cannot' => 'You <b>cannot</b> rate pics',
	'Album_comment_can' => 'You <b>can</b> post comments to pics',
	'Album_comment_cannot' => 'You <b>cannot</b> post comments to pics',
	'Album_edit_can' => 'You <b>can</b> edit your pics and comments',
	'Album_edit_cannot' => 'You <b>cannot</b> edit your pics and comments',
	'Album_delete_can' => 'You <b>can</b> delete your pics and comments',
	'Album_delete_cannot' => 'You <b>cannot</b> delete your pics and comments',
	'Album_moderate_can' => 'You <b>can</b> %smoderate%s this category',

	'Edit_pic' => 'Edit',
	'Delete_pic' => 'Delete',
	'Rating' => 'Rating',
	'Comments' => 'Comments',
	'Last_Comment' => 'Last Comment',
	'New_Comment' => 'New Comment',
	'Not_rated' => '<i>Not Rated</i>',
	'Random_Pictures' => 'Random Pictures',
	'Highest_Rated_Pictures' => 'Highest Rated Pictures',
	'Most_Viewed_Pictures' => 'Most Viewed Pictures',

	'Avatar_Set' => 'Set as Avatar',
	'BBCode_Copy' => 'Copy BBCode',

// Upload
	'Pic_Desc' => 'Pic Description',
	'Plain_text_only' => 'Plain text only',
	'Max_length' => 'Max length (bytes)',
	'Upload_pic_from_machine' => 'Upload a pic from your machine',
	'Upload_to_Category' => 'Upload to Category',
	'Upload_thumbnail_from_machine' => 'Upload its thumbnail from your machine (must be the same type with your pic)',
	'Upload_thumbnail' => 'Upload a thumbnail image',
	'Upload_thumbnail_explain' => 'It must be of the same file type as your picture',
	'Thumbnail_size' => 'Thumbnail size (pixel)',
	'Filetype_and_thumbtype_do_not_match' => 'Your pic and your thumbnail must be the same type',

	'Upload_no_title' => 'You must enter a title for your pic',
	'Upload_no_file' => 'You must enter your path and your filename',
	'Desc_too_long' => 'Your description is too long',

	'JPG_allowed' => 'Allowed to upload JPG files',
	'PNG_allowed' => 'Allowed to upload PNG files',
	'GIF_allowed' => 'Allowed to upload GIF files',

	'Album_reached_quota' => 'This category has reached the quota of pics. Now you cannot upload any more. Please contact the administrators for more information',
	'User_reached_pics_quota' => 'You have reached your quota of pics. Now you cannot upload any more. Please contact the administrators for more information',

	'No_valid_category_selected' => 'No valid album category selected',
	'No_category_to_upload' => 'Unfortunately there are currently no categories you can upload to.',
	'Not_allowed_file_type' => 'Your file type is not allowed',
	'Upload_image_size_too_big' => 'Your image dimension size is too large',
	'Upload_thumbnail_size_too_big' => 'Your thumbnail dimension size is too large',

	'Missed_pic_title' => 'You must enter your pic title',

	'Click_return_category' => 'Click %sHere%s to return to the category',
	'Click_return_album_index' => 'Click %sHere%s to return to the Album Index',

	'Add_File' => 'Add File',
	'File_thumbnail_count_mismatch' => 'The number of uploaded pictures and thumbnails doesn\'t match',
	'No_thumbnail_for_picture_found' => 'There was no thumbnail found for the uploaded picture (named: %s)',
	'No_picture_for_thumbnail_found' => 'There was no picture found for the uploaded thumbnail (named: %s)',
	'Unknown_file_and_thumbnail_error_mismatch' => 'Uknown error got raised when uploading the picture and thumbnail<br />Picture named: %s and Thumbnail named: %s<br />',
	'Picture_exceeded_maximum_size_INI' => 'Picture named \'%s\' is too big. Picture is skipped.<br />',
	'Thumbnail_exceeded_maximum_size_INI' => 'Thumbnail named \'%s\' is too big. Picture and thumbnail are skipped.<br />',
	'Execution_time_exceeded_skipping' => 'The maximum time allowed for script execution has been exceeded. The following files was skipped:<br />',
	'Skipping_uploaded_picture_file' => '%s<br />',
	'Skipping_uploaded_picture_and_thumbnail_file' => '%s (thumbnail: %s)<br />',
	'Album_upload_not_successful' => 'None of your pictures has been uploaded successfully<br /><br />',
	'Album_upload_partially_successful' => 'Only a part of your pictures has been uploaded successfully<br /><br />',
	'No_pictures_selected_for_upload' => 'No pictures selected for upload or unknown error',

// 'Bad_upload_file_size' => 'Your uploaded file is too large or corrupted',
// 'Album_upload_successful' => 'Your pic has been uploaded successfully',
// 'Album_upload_need_approval' => 'Your pic has been uploaded successfully.<br /><br />But the feature Pic Approval has been enabled so your pic must be approved by a administrator or a moderator before posting',

	'Bad_upload' => 'Bad upload',
	'Bad_upload_file_size' => 'Your uploaded file (%s) is too large or corrupted',
	'Album_upload_successful' => 'Your picture(s) has been uploaded successfully',
	'Album_upload_need_approval' => 'Your picture(s) has been uploaded successfully.<br /><br />But the feature Pic Approval has been enabled so your pic must be approved by a administrator or a moderator before posting.',

	'Rotation' => 'Rotate (Anti-Clockwise) - Degrees',

	'Max_file_size' => 'Maximum file size (bytes)',
	'Max_width' => 'Maximum image width before re-compression (pixel)',
	'Max_height' => 'Maximum image height before re-compression (pixel)',

// Album Nuffload
	'time_elapsed' => 'Time Elapsed',
	'time_remaining' => 'Time Remaining',
	'upload_in_progress' => 'Upload In Progress',
	'please_wait' => 'Please Wait...',
	'uploaded' => 'Uploaded %multi_id% of %multi_max% images.',
	'no_file_received' => 'No image file received',
	'no_thumbnail_file_received' => 'No thumbnail file received',
	'file_too_big' => 'Image file size too big',
	'thumbnail_too_big' => 'Thumbnail file size too big',
	'image_res_too_high' => 'Image resolution too high',
	'add_field' => 'Add file upload field',
	'remove_field' => 'Remove file upload field',
	'ZIP_allowed' => 'Allowed to upload ZIP files',

// View Pic
	'Pic_ID' => 'ID',
	'Pic_Details' => 'Image Details',
	'Pic_Size' => 'Size',
	'Pic_Type' => 'Image Type',
	'Pic_BBCode' => 'BBCode',
	'Pic_not_exist' => 'This pic does not exist',
	'Click_enlarge' => 'Click on image to view larger image',
	'Prev_Pic' => 'View Previous Picture',
	'Next_Pic' => 'View Next Picture',
	'Slideshow' => 'Slide Show',
	'Slideshow_Delay' => 'Slide Show Delay',
	'Slideshow_On' => 'Slide Show',
	'Slideshow_Off' => 'Stop Slide Show',
	'Pics_Nav' => 'Pictures Navigation',
	'Pics_Nav_Next' => 'Next Picture',
	'Pics_Nav_Prev' => 'Previous Picture',
	'Pics_Counter' => 'Viewing Pic %s of %s',

// Edit Pic
	'Edit_Pic_Info' => 'Edit Pic Information',
	'Pics_updated_successfully' => 'Your pic information has been updated successfully',

// Delete Pic
	'Album_delete_confirm' => 'Are you sure you want to delete these pic(s)?',
	'Pics_deleted_successfully' => 'These pic(s) have been deleted successfully',

// ModCP
	'Approval' => 'Approval',
	'Approve' => 'Approve',
	'Unapprove' => 'Unapprove',
	'Status' => 'Status',
	'Locked' => 'Locked',
	'Not_approved' => 'Not approved',
	'Approved' => 'Approved',
	'Copy' => 'Copy',
	'Move_to_Category' => 'Move to category',
	'Pics_moved_successfully' => 'Your pic(s) have been moved successfully',
	'Copy_to_Category' => 'Copy to category',
	'Pics_copied_successfully' => 'Your pic(s) have been copied successfully',
	'Pics_locked_successfully' => 'Your pic(s) have been locked successfully',
	'Pics_unlocked_successfully' => 'Your pic(s) have been unlocked successfully',
	'Pics_approved_successfully' => 'Your pic(s) have been approved successfully',
	'Pics_unapproved_successfully' => 'Your pic(s) have been unapproved successfully',

// Rate
	'Current_Rating' => 'Current Rating',
	'Please_Rate_It' => 'Please Rate It',
	'Login_To_Vote' => 'Please Login To Vote',
	'Already_rated' => 'You have already rated this pic',
	'Own_Pic_Rate' => 'You cannot rate your pictures',
	'Album_rate_successfully' => 'Your pic has been rated successfully.',
	'Click_rate_more' => 'Click %sHere%s to rate more pictures.',
	'Hot_Or_Not' => 'Hot Or Not',

// Comment
	'Comment_no_text' => 'Please enter your comment',
	'Comment_too_long' => 'Your comment is too long',
	'Comment_delete_confirm' => 'Are you sure you want to delete this comment?',
	'Pic_Locked' => 'Sorry, this pic was locked. So you cannot post a comment for this pic anymore',
	'Post_your_comment' => 'Please Enter Your Comment!',

// Personal Gallery
	'Personal_Gallery_Explain' => 'You can view the personal galleries of other members by clicking on the link in their profiles',
	'Personal_gallery_not_created' => 'The personal gallery of %s has not been created',
	'Not_allowed_to_create_personal_gallery' => 'Sorry, the administrators of this board do not allow you to create your personal gallery',
	'Click_return_personal_gallery' => 'Click %sHere%s to return to the personal gallery',

// Download Archive
	'Download_pics' => 'Download Pics (ZIP)',
	'Download_page' => 'Download Pics In This Page (ZIP)',
	'No_Download_auth' => 'You are not authorized to archive photos from this album!',

// Email Notification
	'Email_Notification' => 'Album Email Notification',
	'Email_Notification_Explain' => 'This setting allow admins to receive a notification when a new picture is posted in the album',
	'Approvation_OK' => 'Approved',
	'Approvation_NO' => 'To Be Approved',

// Album Hierarchy Index Table
	'Last_Comment' => 'Last Comment',
	'Last_Comments' => 'Last Comments',
	'No_Comment_Info' => 'No Comments',
	'No_Pictures_In_Cat' => 'No Pictures In Category',
	'Total_Pics' => 'Total Pics',
	'Total_Comments' => 'Total Comments',
	'Last_Index_Thumbnail' => 'Last Pic',
	'One_Sub_Total_Pics' => '%d Pic',
	'Multiple_Sub_Total_Pics' => '%d Pics',
	'Album_sub_categories' => 'Subcategories',
	'No_Public_Galleries' => 'No Public Galleries',
	'One_new_picture' => '%d new picture',
	'Multiple_new_pictures' => '%d new pictures',

// Personal Album Hierarchy Index Table
	'Personal_Categories' => 'Personal Gallery',
	'Create_Personal_Categories' => 'Create Personal Gallery',
	'Personal_Cat_Admin' => 'Personal Gallery Category Admin',
	'Recent_Personal_Pics' => 'Recent Pictures From the Personal Gallery of %s',

// Album Moderator Control Panel
	'Modcp_check_all' => 'Check All',
	'Modcp_uncheck_all' => 'Uncheck All',
	'Modcp_inverse_selection' => 'Inverse Selection',

	'Show_selected_pic_view_mode' => 'Show Only The Selected Personal Gallery Category',
	'Show_all_pic_view_mode' => 'Show All Pictures In this Personal Gallery',

// Access language strings
	'Album_Can_Manage_Categories' => 'You <b>can</b> %smanage%s the categories in the gallery',
	'No_Personal_Category_admin' => 'You are not allowed to manage your personal gallery categories',

// The picture list of a member (album_memberlist.php)
	'Pic_Cat' => 'Category',
	'Picture_List_Of_User' => 'All Pictures by %s',
	'Member_Picture_List_Explain' => 'You can view the complete list of picture contributed by other members by clicking on the link in their profiles',
	'Comment_List_Of_User' => 'All Comments by %s',
	'Rating_List_Of_User' => 'All Ratings by %s',
	'Show_All_Pictures_Of_user' => 'Show All Pictures by %s',
	'Show_All_Comments_Of_user' => 'Show All Comments by %s',
	'Show_All_Ratings_Of_user' => 'Show All Ratings by %s',

// The pictures list
	'All_Picture_List_Of_User' => 'All Pictures',
	'All_Comment_List_Of_User' => 'All Comments',
	'All_Rating_List_Of_User' => 'All Ratings',
	'All_Show_All_Pictures_Of_user' => 'Show All Pictures',
	'All_Show_All_Comments_Of_user' => 'Show All Comments',
	'All_Show_All_Ratings_Of_user' => 'Show All Ratings',

	'Not_commented' => '<i>Not Commented</i>',

// Nuff's Stuff
	'Nuff_Click' => 'Click here to apply Special Effects',
	'Nuff_UnClick' => 'Click here for normal visualization',
	'Nuff_Title' => 'Special Effects',
	'Nuff_Explain' => 'Apply multiple effects to the pictures.<br />Remember that this is a <i><b>very heavy operation on server CPU load</b></i>, so please do not abuse it. Some effects will automatically resize the output image to prevent too much charge on the server CPU.',
	'Nuff_Normal' => 'Normal Image',
	'Nuff_Normal_Explain' => 'No effects applied',
	'Nuff_BW' => 'Black & White',
	'Nuff_BW_Explain' => 'Transform the image into Black and White',
	'Nuff_Sepia' => 'Sepia Tone',
	'Nuff_Sepia_Explain' => 'Apply sepia toning to the picture',
	'Nuff_Flip' => 'Flip',
	'Nuff_Flip_Explain' => 'Flip the image',
	'Nuff_Mirror' => 'Mirror',
	'Nuff_Mirror_Explain' => 'Mirror the image',
	'Nuff_Flip_H' => 'Horizontal',
	'Nuff_Flip_V' => 'Vertical',
	'Nuff_Rotate' => 'Picture Rotation (Anti Clockwise)',
	'Nuff_Rotate_Explain' => 'Rotates the images anti clockwise',
	'Nuff_Resize' => 'Resize',
	'Nuff_Resize_Explain' => 'Image resize',
	'Nuff_Resize_W' => 'Width',
	'Nuff_Resize_H' => 'Height',
	'Nuff_Resize_No_Resize' => 'No Resize',
	'Nuff_Watermark' => 'Watermark',
	'Nuff_Watermark_Explain' => 'Apply a watermark to the image',
	'Nuff_Recompress' => 'Recompress',
	'Nuff_Recompress_Explain' => 'Re-compress the image',
	'Nuff_Alpha' => 'Alpha',
	'Nuff_Alpha_Explain' => 'Overlay an alpha channel to the image',
	'Nuff_Blur' => 'Blur',
	'Nuff_Blur_Explain' => 'Apply a blur filter to the image',
	'Nuff_Pixelate' => 'Pixelate',
	'Nuff_Pixelate_Explain' => 'Apply a pixelate filter to the image',
	'Nuff_Scatter' => 'Scatter',
	'Nuff_Scatter_Explain' => 'Apply a scatter filter to the image',
	'Nuff_Infrared' => 'Infrared',
	'Nuff_Infrared_Explain' => 'Apply an infrared filter to the image',
	'Nuff_Tint' => 'Tint',
	'Nuff_Tint_Explain' => 'Apply a red tint to the image',
	'Nuff_Interlace' => 'Interlace (Horizontal Lines)',
	'Nuff_Interlace_Explain' => 'Overlay an interlace channel to the image',
	'Nuff_Screen' => 'Screen (Hor Ver Lines)',
	'Nuff_Screen_Explain' => 'Overlay a screen channel to the image',
	'Nuff_Stereogram' => 'Stereograph',
	'Nuff_Stereogram_Explain' => 'Convert the image to a stereograph (BW 16 bit required)',

	'Pic_Gallery' => 'OTF Gallery',
	'Select_Pic' => 'Select Pic',
	'Select_Category' => 'Select Category',
	'Title_Description' => 'Title &amp; Description',

// Pic watch
	'No_longer_watching_comment' => 'You are no longer watching this pic for comments',
	'Watching_comment' => 'You are now watching this pic for comments',
	'Pic_comment_notification' => 'Album Comment Notification',
	'Pic_comment_watch_checkbox' => 'Check box to be notified on pic comments:',
	'Watch_pic' => 'Watch this pic for comments',
	'Unwatch_pic' => 'Stop watching this pic for comments',
	'Click_return_pic' => 'Click %sHere%s to return to pic',

	'Pic_RDF' => 'RSS Feed 1.0',
	'Pic_RSS' => 'RSS Feed 2.0',
	)
);

?>