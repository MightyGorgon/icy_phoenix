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
* Nuffmon
* Jeffrey
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

// Load up pic_id.
if( isset($_POST['pic_id']) )
{
	$pic_id = intval($_POST['pic_id']);
}
elseif( isset($_GET['pic_id']) )
{
	$pic_id = intval($_GET['pic_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No pic specified');
}

// Is the user logged in.
if (!$userdata['session_logged_in'])
{
	redirect(append_sid(LOGIN_MG . "?redirect=album_avatar.$phpEx?pic_id=$pic_id"));
}

// Is the user allowed avatars
if (!$userdata['user_allowavatar'])
{
	message_die(GENERAL_MESSAGE, "Avatars are not allowed.");
}

// Get this pic info
$sql = "SELECT *
		FROM ". ALBUM_TABLE ."
		WHERE pic_id = '$pic_id'";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$pic_filename = $thispic['pic_filename'];
$file_part = explode('.', strtolower($pic_filename));
$pic_filetype = $file_part[count($file_part) - 1];
$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);

if( empty($thispic) )
{
	die($lang['Pic_not_exist']);
}

// Get the current Category Info
if ($cat_id != PERSONAL_GALLERY)
{
	$sql = "SELECT *
			FROM ". ALBUM_CAT_TABLE ."
			WHERE cat_id = '$cat_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
	}

	$thiscat = $db->sql_fetchrow($result);
}
else
{
	$thiscat = init_personal_gallery_cat($user_id);
}

if (empty($thiscat))
{
	die($lang['Category_not_exist']);
}

// Check the permissions
$album_user_access = album_user_access($cat_id, $thiscat, 1, 0, 0, 0, 0, 0); // VIEW

if ($album_user_access['view'] == 0)
{
	die($lang['Not_Authorised']);
}

// Check Pic Approval
if ($userdata['user_level'] != ADMIN)
{
	if( ($thiscat['cat_approval'] == ADMIN) or (($thiscat['cat_approval'] == MOD) and !$album_user_access['moderator']) )
	{
		if ($thispic['pic_approval'] != 1)
		{
			die($lang['Not_Authorised']);
		}
	}
}

// Generate avatar filename
$avatar_filename = uniqid(rand()) . '.' . $pic_filetype;

// Get image size
$pic_size = getimagesize(ALBUM_UPLOAD_PATH . $pic_filename);
$pic_width = $pic_size[0];
$pic_height = $pic_size[1];

// OK lets resize it the original picture
if($album_config['gd_version'] > 0)
{
	$gd_errored = false;
	switch ($pic_filetype)
	{
		case 'jpg':
			$read_function = 'imagecreatefromjpeg';
			break;
		case 'png':
			$read_function = 'imagecreatefrompng';
			break;
		case 'gif':
			$read_function = 'imagecreatefromgif';
			break;
	}

	$src = @$read_function(ALBUM_UPLOAD_PATH  . $pic_filename);
	if (!$src)
	{
		$gd_errored = true;
		$pic_resize = '';
	}
	elseif( ($pic_width > $board_config['avatar_max_width']) or ($pic_height > $board_config['avatar_max_height']) )
	{
		if ( (($pic_width / $pic_height) > ($board_config['avatar_max_width'] / $board_config['avatar_max_height'])) )
		{
			$resize_width = $board_config['avatar_max_width'];
			$resize_height = $board_config['avatar_max_width'] * ($pic_height/$pic_width);
		}
		else
		{
			$resize_height = $board_config['avatar_max_height'];
			$resize_width = $board_config['avatar_max_height'] * ($pic_width/$pic_height);
		}

		$resize = ($album_config['gd_version'] == 1) ? @imagecreate($resize_width, $resize_height) : @imagecreatetruecolor($resize_width, $resize_height);
		$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';
		@$resize_function($resize, $src, 0, 0, 0, 0, $resize_width, $resize_height, $pic_width, $pic_height);
	}
	else
	{
		$resize = $src;
	}

	if (!$gd_errored)
	{
		switch ($pic_filetype)
		{
			case 'gif':
			case 'jpg':
				@imagejpeg($resize, @phpbb_realpath('./' . $board_config['avatar_path']) . '/' . $avatar_filename, $album_config['thumbnail_quality']);
				break;
			case 'png':
				@imagepng($resize, @phpbb_realpath('./' . $board_config['avatar_path']) . '/' . $avatar_filename);
				break;
		}
		@chmod(@phpbb_realpath('./' . $board_config['avatar_path']) . '/' . $avatar_filename, 0777);
	} // End IF $gd_errored
} // End Picture Resize

// Well that worked ok, lets update the users profile and tell 'em.
$sql = "UPDATE ". USERS_TABLE ."
		SET user_avatar = '$avatar_filename', user_avatar_type = '1'
		WHERE user_id = '" . $userdata['user_id'] . "'";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not update user profile', '', __LINE__, __FILE__, $sql);
}

@unlink(@phpbb_realpath('./' . $board_config['avatar_path']) . '/' . $userdata['user_avatar']);

$message = 'Your profile avatar has been updated.<br />Click <a href="album_cat.' . $phpEx . '?cat_id=' . $cat_id . '&amp;user_id=' . $user_id . '">here</a> to go to image category.<br />Click <a href="album_showpage.' . $phpEx . '?pic_id=' . $pic_id . '&amp;user_id=' . $user_id . '">here</a> to go to image.<br />';
message_die(GENERAL_MESSAGE, $message);

?>