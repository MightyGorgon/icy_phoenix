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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// Load up pic_id.
$pic_id = request_var('pic_id', 0);
if ($pic_id <= 0)
{
	message_die(GENERAL_ERROR, 'No pic specified');
}

// Is the user logged in.
if (!$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_avatar.' . PHP_EXT . '?pic_id=' . $pic_id));
}

// Is the user allowed avatars
if (!$user->data['user_allowavatar'])
{
	message_die(GENERAL_MESSAGE, "Avatars are not allowed.");
}

// Get this pic info
$sql = "SELECT *
		FROM " . ALBUM_TABLE . "
		WHERE pic_id = '$pic_id'";
$result = $db->sql_query($sql);
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$pic_filename = $thispic['pic_filename'];
$file_part = explode('.', strtolower($pic_filename));
$pic_filetype = $file_part[sizeof($file_part) - 1];
$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);

if( empty($thispic) )
{
	die($lang['Pic_not_exist']);
}

// Get the current Category Info
if ($cat_id != PERSONAL_GALLERY)
{
	$sql = "SELECT *
			FROM " . ALBUM_CAT_TABLE . "
			WHERE cat_id = '$cat_id'";
	$result = $db->sql_query($sql);
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
	die($lang['Not_Authorized']);
}

// Check Pic Approval
if ($user->data['user_level'] != ADMIN)
{
	if( ($thiscat['cat_approval'] == ADMIN) or (($thiscat['cat_approval'] == MOD) and !$album_user_access['moderator']) )
	{
		if ($thispic['pic_approval'] != 1)
		{
			die($lang['Not_Authorized']);
		}
	}
}

// Generate avatar filename
$avatar_filename = uniqid(rand()) . '.' . $pic_filetype;

// Get image size
$pic_base_path = ALBUM_UPLOAD_PATH;
$pic_extra_path = '';
$pic_new_filename = $pic_extra_path . $pic_filename;
$pic_fullpath = $pic_base_path . $pic_new_filename;
$pic_size = getimagesize($pic_fullpath);
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

	$src = @$read_function($pic_fullpath);
	if (!$src)
	{
		$gd_errored = true;
		$pic_resize = '';
	}
	elseif( ($pic_width > $config['avatar_max_width']) or ($pic_height > $config['avatar_max_height']) )
	{
		if ( (($pic_width / $pic_height) > ($config['avatar_max_width'] / $config['avatar_max_height'])) )
		{
			$resize_width = $config['avatar_max_width'];
			$resize_height = $config['avatar_max_width'] * ($pic_height/$pic_width);
		}
		else
		{
			$resize_height = $config['avatar_max_height'];
			$resize_width = $config['avatar_max_height'] * ($pic_width/$pic_height);
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
				@imagejpeg($resize, @phpbb_realpath('./' . $config['avatar_path']) . '/' . $avatar_filename, $album_config['thumbnail_quality']);
				break;
			case 'png':
				@imagepng($resize, @phpbb_realpath('./' . $config['avatar_path']) . '/' . $avatar_filename);
				break;
		}
		@chmod(@phpbb_realpath('./' . $config['avatar_path']) . '/' . $avatar_filename, 0777);
	} // End IF $gd_errored
} // End Picture Resize

// Well that worked ok, lets update the users profile and tell 'em.
$sql = "UPDATE ". USERS_TABLE ."
		SET user_avatar = '" . $db-sql_escape($avatar_filename) . "', user_avatar_type = '1'
		WHERE user_id = '" . $user->data['user_id'] . "'";
$result = $db->sql_query($sql);

@unlink(@phpbb_realpath('./' . $config['avatar_path']) . '/' . $user->data['user_avatar']);

$message = 'Your profile avatar has been updated.<br />Click <a href="album_cat.' . PHP_EXT . '?cat_id=' . $cat_id . '&amp;user_id=' . $user_id . '">here</a> to go to image category.<br />Click <a href="album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . '&amp;user_id=' . $user_id . '">here</a> to go to image.<br />';
message_die(GENERAL_MESSAGE, $message);

?>