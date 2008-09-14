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

if( $album_config['comment'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}


// ------------------------------------
// Check the request
// ------------------------------------

if (isset($_POST['message']))
{
	$_POST['comment'] = $_POST['message'];
}

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

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query this comment information', '', __LINE__, __FILE__, $sql);
}

$thiscomment = $db->sql_fetchrow($result);

if( empty($thiscomment) )
{
	message_die(GENERAL_ERROR, 'This comment does not exist');
}


// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------

$sql = "SELECT comment_id, comment_pic_id
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query comment and pic information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

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

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
//$user_id = $thispic['pic_user_id'];
$album_user_id = $thispic['cat_user_id'];

$total_comments = $thispic['comments_count'];
$comments_per_page = $board_config['posts_per_page'];

$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if( empty($thispic) )
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_COMMENT|ALBUM_AUTH_EDIT, $thispic);

if( ($album_user_access['comment'] == 0) || ($album_user_access['edit'] == 0) )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=album_comment_edit.' . PHP_EXT . '?comment_id=' . $comment_id));
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


if( !isset($_POST['comment']) )
{
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Comments Screen
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) || ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $thispic['user_id']) . '">' . $thispic['username'] . '</a>';
	}

	// Start output of page
	$page_title = $lang['Album'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'album_comment_body.tpl'));

	$template->assign_block_vars('switch_comment_post', array());

	$image_rating = ImageRating($thispic['rating']);

	//begin shows smilies
	$max_smilies = 20;

	$sql = 'SELECT emoticon, code, smile_url
		FROM ' . SMILIES_TABLE . '
			GROUP BY smile_url
			ORDER BY smilies_id LIMIT ' . $max_smilies;

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve smilies list", '', __LINE__, __FILE__, $sql);
	}
	$smilies_count = $db->sql_numrows($result);
	$smilies_data = $db->sql_fetchrowset($result);


	for ($i = 1; $i < $smilies_count+1; $i++)
	{
		$template->assign_block_vars('switch_comment_post.smilies', array(
			'CODE' => $smilies_data[$i - 1]['code'],
			'URL' => $board_config['smilies_path'] . '/' . $smilies_data[$i - 1]['smile_url'],
			'DESC' => $smilies_data[$i - 1]['emoticon']
			)
		);

		if ( is_integer($i / 5) )
		{
			$template->assign_block_vars('switch_comment_post.smilies.new_col', array());
		}
	}

	$template->assign_vars(array(
		'CAT_TITLE' => $thispic['cat_title'],
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)),

		'U_THUMBNAIL' => append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $pic_id)),
		'U_PIC' => append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $pic_id)),

		'PIC_ID' => $pic_id,
		'PIC_TITLE' => htmlspecialchars($thispic['pic_title']),
		'PIC_DESC' => nl2br($thispic['pic_desc']),
		'POSTER' => $poster,
		'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_COMMENTS' => $total_comments,
		'S_MESSAGE' => $thiscomment['comment_text'],

		'L_PIC_ID' => $lang['Pic_ID'],
		'L_PIC_TITLE' => $lang['Pic_Image'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Pic_Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['View'],
		'L_COMMENTS' => $lang['Comments'],

		'L_POST_YOUR_COMMENT' => $lang['Post_your_comment'],
		'L_MESSAGE' => $lang['Message'],
		'L_USERNAME' => $lang['Username'],
		'L_COMMENT_NO_TEXT' => $lang['Comment_no_text'],
		'L_COMMENT_TOO_LONG' => $lang['Comment_too_long'],
		'L_MAX_LENGTH' => $lang['Max_length'],
		'S_MAX_LENGTH' => $album_config['desc_length'],

		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid(album_append_uid('album_comment_edit.' . PHP_EXT . '?comment_id=' . $comment_id))
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
						Comment Submited
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	$comment_text = str_replace("\'", "''", htmlspecialchars(substr(trim($_POST['comment']), 0, $album_config['desc_length'])));

	if( empty($comment_text) )
	{
		message_die(GENERAL_ERROR, $lang['Comment_no_text']);
	}


	// --------------------------------
	// Prepare variables
	// --------------------------------

	$comment_edit_time = time();
	$comment_edit_user_id = $userdata['user_id'];


	// --------------------------------
	// Update the DB
	// --------------------------------

	$sql = "UPDATE ". ALBUM_COMMENT_TABLE ."
			SET comment_text = '$comment_text', comment_edit_time = '$comment_edit_time', comment_edit_count = comment_edit_count + 1, comment_edit_user_id = '$comment_edit_user_id'
			WHERE comment_id = '$comment_id'";

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update comment data', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$return_url = 'album_showpage';

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid(album_append_uid($return_url . '.' . PHP_EXT . '?pic_id=' . $pic_id)) .'">'
		)
	);

	$message = $lang['Stored'] . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid(album_append_uid($return_url . '.' . PHP_EXT . '?pic_id=' . $pic_id)) . '#' . $comment_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

?>