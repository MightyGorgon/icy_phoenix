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
// Toplist
	'Toplist' => 'Toplist',
	'Select_list' => 'Select the type of list to show',
	'Latest_downloads' => 'The Newest Files',
	'Most_downloads' => 'Most Popular Files',
	'Rated_downloads' => 'Top Rated Files',
	'Total_new_files' => 'Total New Downloads',
	'Show' => 'Show',
	'One_week' => 'One Week',
	'Two_week' => 'Two Week',
	'30_days' => '30 Days',
	'New_Files' => 'Total new files for last %d days',
	'Last_week' => 'Last Week',
	'Last_30_days' => 'Last 30 Days',
	'Show_top' => 'Show Top',
	'Or_top' => 'or Top',
	'Popular_num' => 'Top %d out of %d files in the database',
	'Popular_per' => 'Top %d %% of all %d files in the database',
	'General_Info' => 'General Information',
	'Downloads_stats' => 'User\'s Downloads Stats',
	'Rating_stats' => 'User\'s Rating Stats',
	'Os' => 'Operating System',
	'Browsers' => 'Browsers',

// Main
	'Files' => 'Files',
	'Viewall' => 'View All Files',
	'Vainfo' => 'View all of the files in the database',
	'Jump' => 'Select a category',
	'Sub_category' => 'Subcategory',
	'Last_file' => 'Last File',

// Sort
	'Sort' => 'Sort',
	'Name' => 'Name',
	'Update_time' => 'Last Updated',

// Category
	'No_files' => 'No files found',
	'No_files_cat' => 'There are no files in this category.',
	'Cat_not_exist' => 'The category you selected does not exist.',
	'File_not_exist' => 'The file you selected does not exist.',
	'License_not_exist' => 'The license you selected does not exist.',
	'No_dl_categories_exists' => 'Either you are not allowed to view any category or there is no category in the database.',

// File
	'File' => 'File',
	'Desc' => 'Description',
	'Creator' => 'Creator',
	'Version' => 'Version',
	'Scrsht' => 'Screenshot',
	'Docs' => 'Web Site',
	'Lastdl' => 'Last Download',
	'Never' => 'Never',
	'Votes' => ' Votes',
	'Date' => 'Date',
	'Update_time' => 'Last Updated',
	'DlRating' => 'Rating',
	'Dls' => ' Downloads',
	'Downloadfile' => 'Download File',
	'File_size' => 'File Size',
	'Not_available' => 'Not Available!',

	'Mirrors' => 'Mirrors',
	'Mirrors_explain' => 'Add or edit mirrors for this file, make sure to verify all the information because the file will be submitted to the database',
	'Click_here_mirrors' => 'Click Here to Add mirrors',
	'Mirror_location' => 'Mirror Location',
	'Add_new_mirror' => 'Add new mirror',

//User Upload
	'User_upload' => 'User Upload',

// License
	'License' => 'License Agreement',
	'Licensewarn' => 'You must agree to this license agreement to download',
	'Iagree' => 'I Agree',
	'Dontagree' => 'I Disagree',

// Search
	'Search' => 'Search',
	'Search_for' => 'Search for',
	'Results' => 'Results for',
	'No_matches' => 'Sorry, no matches were found for',
	'Matches' => 'matches were found for',
	'All' => 'All Categories',
	'Choose_cat' => 'Choose Category:',
	'Include_comments' => 'Include Comments',
	'Submiter' => 'Submitted by',

// Statistics
	'Statistics' => 'Statistics',
	'Select_chart_type' => 'Select Chart Type',
	'Bars' => 'Bars',
	'Lines' => 'Lines',
	'Area' => 'Area',
	'Linepoints' => 'Line Points',
	'Points' => 'Points',
	'Chart_header' => 'Files Stats - Files added to the database each month',
	'Chart_legend' => 'Files',
	'X_label' => 'Months',
	'Y_label' => 'Number of Files',

// Rate
	'Rate' => 'Rate File',
	'Rerror' => 'Sorry, you have already rated this file.',
	'Rateinfo' => 'You are about to rate the file <i>{filename}</i>.<br />Please select a rating below. 1 is the worst, 10 is the best.',
	'Rconf' => 'You have given <i>{filename}</i> a rating of {rate}.<br />This makes the files\' new rating {newrating}.',
	'R1' => '1',
	'R2' => '2',
	'R3' => '3',
	'R4' => '4',
	'R5' => '5',
	'R6' => '6',
	'R7' => '7',
	'R8' => '8',
	'R9' => '9',
	'R10' => '10',
	'Not_rated' => 'Not Rated',

// Email
	'Emailfile' => 'E-mail File to a Friend',
	'Emailinfo' => 'If you would like a friend to know about this file, you can fill out and submit this form and an e-mail containing the files\' information will be e-mailed to your friend!<br />Items marked with a * are required unless stated otherwise',
	'Yname' => 'Your Name',
	'Yemail' => 'Your E-mail Address',
	'Fname' => 'Friends Name',
	'Femail' => 'Friends E-mail Address',
	'Esub' => 'E-mail Subject',
	'Etext' => 'E-mail Text',
	'Defaultmail' => 'I thought you might be interested in downloading the file located at',
	'Semail' => 'Send E-mail',
	'Econf' => 'Your e-mail has been sent successfully.',

// Comments
	'Comments' => 'Comments',
	'Comments_title' => 'Comments Title',
	'Comment_subject' => 'Comment subject',
	'Comment' => 'Comment',
	'Comment_explain' => 'Use the text box above to give your opinion on this file!',
	'Comment_add' => 'Add Comment',
	'Comment_delete' => 'Delete',
	'Comment_posted' => 'Your comment has been entered successfully',
	'Comment_deleted' => 'The comment you selected has been deleted successfully',
	'Comment_desc' => 'Title',
	'No_comments' => 'No Comments have been posted yet.',
	'Links_are_ON' => 'Links is <u>ON</u>',
	'Links_are_OFF' => 'Links is <u>OFF</u>',
	'Images_are_ON' => 'Images is <u>ON</u>',
	'Images_are_OFF' => 'Images is <u>OFF</u>',
	'Check_message_length' => 'Check Message Length',
	'Msg_length_1' => 'Your message is ',
	'Msg_length_2' => ' characters long.',
	'Msg_length_3' => 'You have ',
	'Msg_length_4' => ' characters available.',
	'Msg_length_5' => 'There are ',
	'Msg_length_6' => ' characters left to use.',


// Download
	'Directly_linked' => 'You cannot download this file directly from another site!',

//Permission
	'Sorry_auth_view' => 'Sorry, but only %s can view files and subcategories in this category.',
	'Sorry_auth_file_view' => 'Sorry, but only %s can view this file in this category.',
	'Sorry_auth_upload' => 'Sorry, but only %s can upload file in this category.',
	'Sorry_auth_download' => 'Sorry, but only %s can download files in this category.',
	'Sorry_auth_rate' => 'Sorry, but only %s can rate files in this category.',
	'Sorry_auth_view_comments' => 'Sorry, but only %s can view comments in this category.',
	'Sorry_auth_post_comments' => 'Sorry, but only %s can post comments in this category.',
	'Sorry_auth_edit_comments' => 'Sorry, but only %s can edit comments in this category.',
	'Sorry_auth_delete_comments' => 'Sorry, but only %s can delete comments in this category.',
// MX
	'Sorry_auth_edit' => 'Sorry, but you cannot edit files in this category.',
	'Sorry_auth_delete' => 'Sorry, but you cannot delete files in this category.',
	'Sorry_auth_mcp' => 'Sorry, but you cannot moderate this category.',
	'Sorry_auth_approve' => 'Sorry, but you cannot approve files in this category.',


// General
	'Category' => 'Category',
	'Error_no_download' => 'The selected File no longer exists',
	'Options' => 'Options',
	'Click_return' => 'Click %sHere%s to return to the previous page',
	'Click_here' => 'Click Here',
	'never' => 'None',
	'pafiledb_disable' => 'Download Database is disabled',
	'jump' => 'Select a category',
	'viewall_disabled' => 'This feature is disabled by the admin.',
	'New_file' => 'New file',
	'No_new_file' => 'No new file',
	'None' => 'None',
	'No_file' => 'No Files',
	'View_latest_file' => 'View Latest File',

// Toplists mx blocks
	'Recent_Public_Files' => 'Latest downloads',
	'Random_Public_Files' => 'Random downloads',
	'Toprated_Public_Files' => 'Top-rated downloads',
	'Most_Public_Files' => 'Most downloaded',
	'File_Title' => 'Title',
	'File_Desc' => 'Description',
	'Rating' => 'Rating',
	'Dls' => 'Downloaded',

// MX Addon
	'Deletefile' => 'Delete file',
	'Editfile' => 'Edit file',
	'pa_MCP' => 'ModeratorCP',
	'Click_return_not_validated' => 'Click %sHere%s to return to the previous page',
	)
);

$lang['Stats_text'] = "There are {total_files} files in {total_categories} categories<br />";
$lang['Stats_text'] .= "There have been {total_downloads} total downloads<br /><br />";
$lang['Stats_text'] .= "The newest file is <a href={u_newest_file}>{newest_file}</a><br />";
$lang['Stats_text'] .= "The oldest file is <a href={u_oldest_file}>{oldest_file}</a><br /><br />";
$lang['Stats_text'] .= "The average file rating is {average}/10<br />";
$lang['Stats_text'] .= "The most popular file based on ratings is <a href={u_popular}>{popular}</a> with a rating of {most}/10<br />";
$lang['Stats_text'] .= "The least popular file based on ratings is <a href={u_lpopular}>{lpopular}</a> with a rating of {least}/10<br /><br />";
$lang['Stats_text'] .= "The average amount of downloads each file has is {avg_dls}<br />";
$lang['Stats_text'] .= "The most popular file based on downloads is <a href={u_most_dl}>{most_dl}</a> with {most_no} downloads<br />";
$lang['Stats_text'] .= "The least popular file based on downloads is <a href={u_least_dl}>{least_dl}</a> with {least_no} downloads<br />";

?>