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

if(isset($_GET['pic_id']))
{
	$pic_id = intval($_GET['pic_id']);
}
elseif(isset($_POST['pic_id']))
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

if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['cat_id'];
$album_user_id = $thispic['cat_user_id'];

$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if(empty($thispic))
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_DELETE, $thispic);

if ($album_user_access['delete'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=album_delete.' . $phpEx . '?pic_id=' . $pic_id));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
else
{
	if((!$album_user_access['moderator']) && ($userdata['user_level'] != ADMIN))
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

if(!isset($_POST['confirm']))
{
	// --------------------------------
	// If user give up deleting...
	// --------------------------------
	if(isset($_POST['cancel']))
	{
		redirect(append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id, true)));
		exit;
	}

	// Start output of page
	$page_title = $lang['Album'];
	$meta_description = '';
	$meta_keywords = '';
	include($phpbb_root_path . 'includes/page_header.' . $phpEx);

	$template->set_filenames(array('body' => 'confirm_body.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Album_delete_confirm'],
		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],
		'S_CONFIRM_ACTION' => append_sid(album_append_uid('album_delete.' . $phpEx . '?pic_id=' . $pic_id)),
		)
	);

	// Generate the page
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
}
else
{
	// --------------------------------
	// It's confirmed. First delete all comments
	// --------------------------------
	$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
			WHERE comment_pic_id = '$pic_id'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete related comments', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Delete all ratings
	// --------------------------------
	$sql = "DELETE FROM ". ALBUM_RATE_TABLE ."
			WHERE rate_pic_id = '$pic_id'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete related ratings', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Delete cached thumbnail
	// --------------------------------
	if(($thispic['pic_thumbnail'] != '') and @file_exists(ALBUM_CACHE_PATH . $thispic['pic_thumbnail']))
	{
		@unlink(ALBUM_CACHE_PATH . $thispic['pic_thumbnail']);
	}


	// --------------------------------
	// Delete File
	// --------------------------------
	@unlink(ALBUM_UPLOAD_PATH . $thispic['pic_filename']);


	// --------------------------------
	// Delete DB entry
	// --------------------------------
	$sql = "DELETE FROM ". ALBUM_TABLE ."
			WHERE pic_id = '$pic_id'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete DB entry', '', __LINE__, __FILE__, $sql);
	}

// --- Smart Album Button BEGIN ----------------
// added by Tom XS2 Build 054
	if (defined('IS_ICYPHOENIX'))
	{
		$is_personal_gallery = (album_get_cat_user_id($cat_id) != false) ? true : false;
		if ($is_personal_gallery == true)
		{
			$sql = "SELECT COUNT(pic_id) AS count
				FROM ". ALBUM_TABLE ."
				WHERE pic_user_id = '". $userdata['user_id'] ."'
				AND pic_cat_id = '$cat_id'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query personal pic count', '', __LINE__, __FILE__, $sql);
			}
			$personal_pics_count = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$userpics = $personal_pics_count['count'];

			// Check which users category we are in so we don't update the wrong users pic count
			$sql = 'SELECT cat_user_id FROM ' . ALBUM_CAT_TABLE . ' WHERE cat_id = (' . $cat_id . ') LIMIT 1';
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get the cat user id of this category ', '', __LINE__, __FILE__, $sql);
			}
			$usercat = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$cat_user_id = $usercat['cat_user_id'];

			if (!empty($userpics) || $userpics == 0)
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_personal_pics_count = '$userpics'
					WHERE user_id = $cat_user_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
				}
			}
			unset($personal_pics_count);
		}
	}
// --- Smart Album Button END ------------------


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$message = $lang['Pics_deleted_successfully'];

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">')
	);

	if ($album_user_id == ALBUM_PUBLIC_GALLERY)
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">', '</a>');
	}
	else
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album.' . $phpEx . '?user_id=' . $cat_user_id)) . '">', '</a>');
	}

	$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid(album_append_uid('album.' . $phpEx)) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);

}

?>