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
* chadsmith (snowblades83@hotmail.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/class_archives.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// ------------------------------------
// Get the request
// ------------------------------------

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$cat_id = request_var('cat_id', 0);
$user_id = request_var('user_id', 0);

$sort_method = request_var('sort_method', $album_config['sort_method']);
$sort_method = check_var_value($sort_method, array('pic_title', 'pic_view_count', 'rating', 'comments', 'new_comment'));

$sort_order = request_var('sort_order', $album_config['sort_order']);
$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];

$auth_view = 0;
if(!empty($cat_id))
{
	$album_user_access = album_user_access($cat_id, $thiscat, 1, 0, 0, 0, 0, 0); // VIEW
	$auth_view = $album_user_access['view'];
	//$auth_view = ($user->data['user_level'] == ADMIN);
}
elseif(!empty($user_id))
{
	$cat_id = PERSONAL_GALLERY . " AND pic_user_id = $user_id";
	$personal_gallery_access = personal_gallery_access(1, 0);
	$auth_view = $personal_gallery_access['view'];
	//$auth_view = (($user->data['user_id'] == $user_id) || ($user->data['user_level'] > 0)) ? 1 : 0;
}

// ------------------------------------
// Check authorization
// ------------------------------------

if ((!$auth_view) || (($album_config['show_download'] == 0) && ($user->data['user_level'] != ADMIN)))
{
	message_die(GENERAL_ERROR, $lang['No_Download_auth']);
}
//
// END check request
//

// ------------------------------------
// Count Pics
// ------------------------------------

$sql = "SELECT COUNT(pic_id) AS count
		FROM " . ALBUM_TABLE . "
		WHERE pic_cat_id = $cat_id";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$total_pics = $row['count'];

// ------------------------------------
// Build archive
// ------------------------------------

if ($total_pics > 0)
{
	if (isset($_GET['download_all_pics']))
	{
		$limit_sql = ' ';
	}
	else
	{
		$limit_sql = ($start == 0) ? ' LIMIT ' . $pics_per_page : ' LIMIT ' . $start . ',' . $pics_per_page;
	}
	$sql = "SELECT pic_filename
			FROM " . ALBUM_TABLE . "
			WHERE pic_cat_id = $cat_id
			ORDER BY $sort_method $sort_order
			$limit_sql";
	$result = $db->sql_query($sql);

	// ------------------------------------
	// If you wish to use a format other than zip uncomment the necessary line, "archive" can also be renamed
	// ------------------------------------

	$archive = new zip_file('archive.zip'); // save as zip
	// $archive = new tar_file('archive.tar'); // save as tar
	// $archive = new gzip_file('archive.tgz'); // save as gzip

	$archive->set_options(array('inmemory' => 1, 'storepaths' => 0, 'comment' => 'Archived photos from ' . $config['sitename']));
	$DLpics = array();
	while($row = $db->sql_fetchrow($result))
	{
		$DLpics[] = $row;
	}

	for ($num = 0; $num < sizeof($DLpics); $num++)
	{
		$archive->add_files(ALBUM_UPLOAD_PATH . $DLpics[$num]['pic_filename']);
	}
	$archive->create_archive();
	$archive->download_file();
}
else
{
	message_die(GENERAL_ERROR, 'There are no pictures to download');
}
?>
