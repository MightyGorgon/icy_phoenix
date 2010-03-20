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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//define('PERSONAL_GALLERY', 0); // pic_cat_id <- do NOT change this value
define('ALBUM_DATA_ALREADY_READ', -127);

define('ALBUM_ROOT_CATEGORY', -1);
define('ALBUM_PUBLIC_GALLERY', 0);

// Special album jumpbox/selection values
define('ALBUM_JUMPBOX_SEPARATOR', -99999900);
define('ALBUM_JUMPBOX_DELETE', -99999901);
define('ALBUM_JUMPBOX_USERS_GALLERY', -99999902);
define('ALBUM_JUMPBOX_PUBLIC_GALLERY', -99999903);

// Permission rights defined flags
define('ALBUM_AUTH_VIEW', 1);
define('ALBUM_AUTH_UPLOAD', 2);
define('ALBUM_AUTH_CREATE_PERSONAL', 2);
define('ALBUM_AUTH_RATE', 4);
define('ALBUM_AUTH_COMMENT', 8);
define('ALBUM_AUTH_EDIT', 16);
define('ALBUM_AUTH_DELETE', 32);
define('ALBUM_AUTH_MODERATOR', 64);
define('ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES', 128);

// Special 'predefined' combinations
define('ALBUM_AUTH_ALL', 255);
define('ALBUM_AUTH_VIEW_AND_UPLOAD', 3);

// Used to indicate if you are going to read both public & personal album categories
define('ALBUM_READ_ALL_CATEGORIES', 512);
define('ALBUM_CREATE_CAT_ID_LIST', 1024);

// Select/jumpbox defined flags
define('ALBUM_SELECTBOX_INCLUDE_ALL', 1);
define('ALBUM_SELECTBOX_INCLUDE_ROOT', 2);
define('ALBUM_SELECTBOX_DELETING', 4);
define('ALBUM_SELECTBOX_ALL', 7); // all three options
define('ALBUM_VIEW_ALL', 'all');
define('ALBUM_VIEW_ALL_PICS', 'allpics');
define('ALBUM_VIEW_LIST', 'list');
define('ALBUM_VIEW_NORMAL', '');
define('ALBUM_LISTTYPE_PICTURES', 'pic');
define('ALBUM_LISTTYPE_COMMENTS', 'comment');
define('ALBUM_LISTTYPE_RATINGS', 'rating');

define('ALBUM_INCLUDE_PARENT_ID', true);
define('ALBUM_EXCLUDE_PARENT_ID', false);

// User Levels for Album system <- do NOT change these values
define('ALBUM_ANONYMOUS', -1);
define('ALBUM_GUEST', -1);

define('ALBUM_USER', 0);
define('ALBUM_ADMIN', 1);
define('ALBUM_MOD', 2);
define('ALBUM_PRIVATE', 3);

// Path (trailing slash required)
if (USERS_SUBFOLDERS_ALBUM == true)
{
	define('ALBUM_UPLOAD_PATH', ALBUM_FILES_PATH . 'users/');
}
else
{
	define('ALBUM_UPLOAD_PATH', ALBUM_FILES_PATH);
}
define('ALBUM_JUPLOAD_PATH', ALBUM_FILES_PATH . 'jupload/');
define('ALBUM_OTF_PATH', ALBUM_FILES_PATH . 'otf/');
define('ALBUM_CACHE_PATH', ALBUM_FILES_PATH . 'cache/');
define('ALBUM_MED_CACHE_PATH', ALBUM_FILES_PATH . 'med_cache/');
define('ALBUM_WM_CACHE_PATH', ALBUM_FILES_PATH . 'wm_cache/');
define('ALBUM_WM_FILE', ALBUM_MOD_IMG_PATH . 'mark_fap.png');

// Pic watch
define('COMMENT_WATCH_UN_NOTIFIED', 0);
define('COMMENT_WATCH_NOTIFIED', 1);

?>