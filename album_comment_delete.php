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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// ------------------------------------
// Check feature enabled
// ------------------------------------

if($album_config['comment'] == 0)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

// ------------------------------------
// Check the request
// ------------------------------------

if(isset($_GET['comment_id']))
{
	$comment_id = intval($_GET['comment_id']);
}
elseif(isset($_POST['comment_id']))
{
	$comment_id = intval($_POST['comment_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No comment_id specified');
}


// ------------------------------------
// Get the comment info
// ------------------------------------
$sql = "SELECT *
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";

if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query this comment information', '', __LINE__, __FILE__, $sql);
}

$thiscomment = $db->sql_fetchrow($result);

if(empty($thiscomment))
{
	message_die(GENERAL_ERROR, 'This comment does not exist');
}


// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------

$sql = "SELECT comment_id, comment_pic_id
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";

if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query comment and pic information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if(empty($row))
{
	message_die(GENERAL_ERROR, 'This comment does not exist');
}

$pic_id = $row['comment_pic_id'];


// ------------------------------------
// Get this pic info and current category info
// ------------------------------------
// NOTE: we don't do a left join here against the category table
// since ALL pictures belong to some category, if not then it's database error
$sql = "SELECT p.*, cat.*, u.user_id, u.username, COUNT(c.comment_id) as comments_count
		FROM ". ALBUM_CAT_TABLE ."  AS cat, ". ALBUM_TABLE ." AS p
			LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
			LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
		WHERE pic_id = '$pic_id'
			AND cat.cat_id = p.pic_cat_id
		GROUP BY p.pic_id
		LIMIT 1";
if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$album_user_id = $thispic['cat_user_id'];

$total_comments = $thispic['comments_count'];
$comments_per_page = $board_config['posts_per_page'];

$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if(empty($thispic))
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_COMMENT|ALBUM_AUTH_DELETE, $thispic);

if(($album_user_access['comment'] == 0) || ($album_user_access['delete'] == 0))
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=album_comment_delete.' . PHP_EXT . '?comment_id=' . $comment_id));
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
		if ($thiscomment['comment_user_id'] != $userdata['user_id'])
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
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
						 Confirm Screen
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// --------------------------------
	// If user give up deleting...
	// --------------------------------
	if(isset($_POST['cancel']))
	{
		redirect(append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id)));
		exit;
	}

	// Start output of page
	$page_title = $lang['Album'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'confirm_body.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Comment_delete_confirm'],
		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],
		'S_CONFIRM_ACTION' => append_sid(album_append_uid('album_comment_delete.' . PHP_EXT . '?comment_id=' . $comment_id)),
		)
	);

	//
	// Generate the page
	//
	$template->pparse('body');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}
else
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
						Do the deleting
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	$sql = "DELETE
			FROM ". ALBUM_COMMENT_TABLE ."
			WHERE comment_id = '$comment_id'";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete this comment', '', __LINE__, __FILE__, $sql);
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$message = $lang['Deleted'];
	$redirect_url = append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id);
	meta_refresh(3, $redirect_url);

	$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');

	$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');


	message_die(GENERAL_MESSAGE, $message);
}

?>