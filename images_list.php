<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

include(IP_ROOT_PATH . 'includes/class_images.' . PHP_EXT);
$class_images = new class_images();

// This page is not in layout special...
$cms_page['page_id'] = 'pic_upload';
$cms_page['page_nav'] = true;
$cms_page['global_blocks'] = false;
$cms_auth_level = (isset($config['auth_view_pic_upload']) ? $config['auth_view_pic_upload'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);
// Force the page_id to album
$cms_page['page_id'] = 'album';

if (!$user->data['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

$pic_id = request_var('pic_id', 0);

$mode_array = array('show', 'delete', 'full');
$mode = request_var('mode', '');
$mode = (($user->data['user_level'] == ADMIN) && in_array($mode, $mode_array)) ? $mode : $mode_array[0];

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$pic_user_id = (($user->data['user_level'] == ADMIN) ? request_var('pic_user_id', $user->data['user_id']) : $user->data['user_id']);

if (($user->data['user_level'] == ADMIN) && ($mode == 'delete') && !empty($pic_id))
{
	$image_deleted = $class_images->remove_image($pic_id);
	redirect(append_sid(CMS_PAGE_IMAGES));
}

$server_path = create_server_url();

$total_pics = 0;
$pics_cols_per_page = 4;
$pics_rows_per_page = 5;
$pics_per_page = $pics_rows_per_page * $pics_cols_per_page;
$pic_row_count = 0;
$pic_col_count = 0;
$s_colspan = $pics_cols_per_page;
$s_colwidth = ((100 / $s_colspan) . '%');

$images_data = array();
if ($mode == 'full')
{
	$images_data = $class_images->get_all_user_images('i.pic_id DESC', $start, $pics_per_page);
	$total_pics = $class_images->get_total_images();
}
else
{
	$images_data = $class_images->get_user_images($pic_user_id, 'i.pic_id DESC', $start, $pics_per_page);
	$total_pics = $class_images->get_total_user_images($pic_user_id);
}

$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid(CMS_PAGE_IMAGES) . '">' . $lang['Uploaded_Images_Local'] . '</a>';

if (empty($images_data))
{
	message_die(GENERAL_MESSAGE, $lang['No_Pics']);
}

$pics_parsed = 0;
foreach ($images_data as $image_data)
{
	$pics_parsed++;
	if(empty($pic_col_count) || ($pic_col_count == $pics_cols_per_page))
	{
		$template->assign_block_vars('pic_row', array());
		$pic_col_count = 0;
	}
	$pic_col_count++;

	// We are checking for small thumbnails... added an underscore to distinguish those small thumbs respect to mid sized!
	$image_paths = $class_images->generate_image_paths($image_data);
	$pic_title = ((strlen($image_data['pic_title']) > 25) ? (substr($image_data['pic_title'], 0, 22) . '...') : $image_data['pic_title']);

	$template->assign_block_vars('pic_row.pic_column', array(
		'PIC_DELETE' => $image_paths['delete_url'],
		'PIC_IMAGE' => $image_paths['url'],
		'PIC_THUMB' => $image_paths['thumb'],
		'PIC_BBC_INPUT' => 'bbcode_box_' . $pics_parsed,
		'PIC_BBC' => '[img]' . $server_path . substr($image_paths['url'], strlen(IP_ROOT_PATH)) . '[/img]',
		'PIC_NAME' => $pic_title
		)
	);
}

if($pic_col_count < $pics_cols_per_page)
{
	for($i = $pic_col_count; $i < $pics_cols_per_page; $i++)
	{
		$template->assign_block_vars('pic_row.pic_end_row', array());
	}
}

$template->assign_vars(array(
	'L_PIC_GALLERY' => $lang['Uploaded_Images_Local'],
	'L_BBCODE' => $lang['BBCode'],
	'L_BBCODE_DES' => $lang['Uploaded_Image_BBC'],
	'S_ACTION' => append_sid(CMS_PAGE_IMAGES),
	'S_COLSPAN' => $s_colspan,
	'S_COLWIDTH' => $s_colwidth,
	'S_THUMBNAIL_SIZE' => $config['thumbnail_s_size'],
	)
);

$template->assign_vars(array(
	'PAGINATION' => generate_pagination(append_sid(CMS_PAGE_IMAGES . '?sort=standard&amp;pic_user_id=' . $pic_user_id . (!empty($mode) ? ('&amp;mode=' . $mode) : '')), $total_pics, $pics_per_page, $start),
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $pics_per_page) + 1), ceil($total_pics / $pics_per_page))
	)
);

full_page_generation('images_list_body.tpl', $lang['Uploaded_Images_Local'], '', '');

?>