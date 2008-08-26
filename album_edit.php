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
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = defined('IS_ICYPHOENIX') ? session_pagestart($user_ip) : session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
// End session management

// Get general album information
$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH;
include($album_root_path . 'album_common.' . $phpEx);

// ------------------------------------
// Check the request
// ------------------------------------
if( isset($_GET['pic_id']) )
{
	$pic_id = intval($_GET['pic_id']);
}
elseif( isset($_POST['pic_id']) )
{
	$pic_id = intval($_POST['pic_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No pics specified');
}

// ------------------------------------
// Get this pic info and current Category Info
// ------------------------------------
$sql = "SELECT p.*, c.*
		FROM ". ALBUM_TABLE ." AS p, ". ALBUM_CAT_TABLE ."  AS c
		WHERE p.pic_id = '$pic_id'
			AND c.cat_id = p.pic_cat_id";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['cat_id'];
$album_user_id = $thispic['cat_user_id'];

$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if( empty($thispic) )
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_EDIT, $thispic);

if ($album_user_access['edit'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . "?redirect=album_edit.$phpEx?pic_id=$pic_id"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
else
{
	if( (!$album_user_access['moderator']) && ($userdata['user_level'] != ADMIN) )
	{
		if ($thispic['pic_user_id'] != $userdata['user_id'])
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']);
		}
	}
}


/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if( !isset($_POST['pic_title']) )
{
	// Start output of page
	$page_title = $lang['Album'];
	$meta_description = '';
	$meta_keywords = '';
	include($phpbb_root_path . 'includes/page_header.' . $phpEx);

	$template->set_filenames(array('body' => 'album_edit_body.tpl'));

	$template->assign_vars(array(
		'L_EDIT_PIC_INFO' => $lang['Edit_Pic_Info'],

		'CAT_TITLE' => $thispic['cat_title'],
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)),

		'L_PIC_ID' => $lang['Pic_ID'],
		'L_PIC_TITLE' => $lang['Pic_Image'],
		'PIC_ID' => $pic_id,
		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => $thispic['pic_desc'],

		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_PLAIN_TEXT_ONLY' => $lang['Plain_text_only'],
		'L_MAX_LENGTH' => $lang['Max_length'],

		'L_UPLOAD_NO_TITLE' => $lang['Upload_no_title'],
		'L_DESC_TOO_LONG' => $lang['Desc_too_long'],
		'S_PIC_DESC_MAX_LENGTH' => $album_config['desc_length'],

		'L_RESET' => $lang['Reset'],
		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid(album_append_uid("album_edit.$phpEx?pic_id=$pic_id")),
		)
	);

	// Generate the page
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
}
else
{
	// --------------------------------
	// Check posted info
	// --------------------------------
	$pic_title = str_replace("\'", "''", htmlspecialchars(trim($_POST['pic_title'])));

	$pic_desc = str_replace("\'", "''", htmlspecialchars(substr(trim($_POST['pic_desc']), 0, $album_config['desc_length'])));

	if( empty($pic_title) )
	{
		message_die(GENERAL_ERROR, $lang['Missed_pic_title']);
	}


	// --------------------------------
	// Update the DB
	// --------------------------------
	$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_title = '" . $pic_title . "', pic_desc= '" . $pic_desc . "'
			WHERE pic_id = '" . $pic_id . "'";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------
	$message = $lang['Pics_updated_successfully'];

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">')
	);

	$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">', '</a>');

	$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . $phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);

}

?>