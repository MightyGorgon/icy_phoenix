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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// ------------------------------------
// Check the request
// ------------------------------------

$pic_id = request_var('pic_id', 0);
if ($pic_id <= 0)
{
	message_die(GENERAL_MESSAGE, $lang['NO_PICS_SPECIFIED']);
}

// ------------------------------------
// Get this pic info and current Category Info
// ------------------------------------
$sql = "SELECT p.*, c.*
		FROM " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . "  AS c
		WHERE p.pic_id = '$pic_id'
			AND c.cat_id = p.pic_cat_id";
$result = $db->sql_query($sql);
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
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_delete.' . PHP_EXT . '?pic_id=' . $pic_id));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorized']);
	}
}
else
{
	if((!$album_user_access['moderator']) && ($user->data['user_level'] != ADMIN))
	{
		if ($thispic['pic_user_id'] != $user->data['user_id'])
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
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
		redirect(append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id, true)));
		exit;
	}

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Album_delete_confirm'],
		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],
		'S_CONFIRM_ACTION' => append_sid(album_append_uid('album_delete.' . PHP_EXT . '?pic_id=' . $pic_id)),
		)
	);
	full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
}
else
{
	// --------------------------------
	// It's confirmed. First delete all comments
	// --------------------------------
	$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
			WHERE comment_pic_id = '$pic_id'";
	$result = $db->sql_query($sql);

	// --------------------------------
	// Delete all ratings
	// --------------------------------
	$sql = "DELETE FROM ". ALBUM_RATE_TABLE ."
			WHERE rate_pic_id = '$pic_id'";
	$result = $db->sql_query($sql);

	// --------------------------------
	// Delete cached thumbnail
	// --------------------------------
	if($thispic['pic_thumbnail'] != '')
	{
		$dirs_array = array(IP_ROOT_PATH . ALBUM_CACHE_PATH, IP_ROOT_PATH . ALBUM_MED_CACHE_PATH, IP_ROOT_PATH . ALBUM_WM_CACHE_PATH);
		for ($i = 0; $i < sizeof($dirs_array); $i++)
		{
			$dir = $dirs_array[$i];
			$pic_thumbnail = $thispic['pic_thumbnail'];
			if(@file_exists($dir . $pic_thumbnail))
			{
				@unlink($dir . $pic_thumbnail);
			}
			if (USERS_SUBFOLDERS_ALBUM == true)
			{
				$pic_thumbnail = $thispic['pic_user_id'] . '/' . $thispic['pic_thumbnail'];
				if(@file_exists($dir . $pic_thumbnail))
				{
					@unlink($dir . $pic_thumbnail);
				}
			}
		}
	}

	// --------------------------------
	// Delete File
	// --------------------------------
	$pic_filename = $thispic['pic_filename'];
	$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
	$pic_extra_path = '';
	$pic_new_filename = $pic_extra_path . $pic_filename;
	$pic_fullpath = $pic_base_path . $pic_new_filename;
	@unlink($pic_fullpath);

	// --------------------------------
	// Delete DB entry
	// --------------------------------
	$sql = "DELETE FROM " . ALBUM_TABLE . "
			WHERE pic_id = '" . $pic_id . "'";
	$result = $db->sql_query($sql);

	$is_personal_gallery = (album_get_cat_user_id($cat_id) != false) ? true : false;
	if ($is_personal_gallery == true)
	{
		$sql = "SELECT COUNT(pic_id) AS count
			FROM " . ALBUM_TABLE . "
			WHERE pic_user_id = '". $user->data['user_id'] ."'
			AND pic_cat_id = '" . $cat_id . "'";
		$result = $db->sql_query($sql);
		$personal_pics_count = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$userpics = $personal_pics_count['count'];

		// Check which users category we are in so we don't update the wrong users pic count
		$sql = 'SELECT cat_user_id FROM ' . ALBUM_CAT_TABLE . ' WHERE cat_id = (' . $cat_id . ') LIMIT 1';
		$result = $db->sql_query($sql);
		$usercat = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$cat_user_id = $usercat['cat_user_id'];

		if (!empty($userpics) || $userpics == 0)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_personal_pics_count = '" . $userpics . "'
				WHERE user_id = '" . $cat_user_id . "'";
			$result = $db->sql_query($sql);
		}
		unset($personal_pics_count);
	}


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$message = $lang['Pics_deleted_successfully'];

	$redirect_url = append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id));
	meta_refresh(3, $redirect_url);

	if ($album_user_id == ALBUM_PUBLIC_GALLERY)
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
	}
	else
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT . '?user_id=' . $cat_user_id)) . '">', '</a>');
	}

	$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT)) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);

}

?>