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
//$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);
include_once(ALBUM_MOD_PATH . 'album_functions_otf.' . PHP_EXT);

$pic_id = request_var('pic_id', '');
$pic_cat = request_var('pic_cat', '');
$cat_id = request_var('cat_id', 0);
$cat_id = ($cat_id < 0) ? 0 : $cat_id;
$mode = request_var('mode', '');

$upload_pics = false;
$cat_to_upload = false;
if(isset($_POST['pic_upload']))
{
	if (!empty($_POST['pic_upload']) && (!empty($cat_id)))
	{
		$cat_to_upload = $cat_id;
		$upload_pics = true;
	}
}

if (($mode == 'delete') && !empty($pic_id))
{
	$pic_id = basename($pic_id);
	if ($pic_id != '')
	{
		if (@file_exists(@phpbb_realpath('./' . ALBUM_OTF_PATH . $pic_id)))
		{
			@unlink('./' . ALBUM_OTF_PATH . $pic_id);
		}
	}
}

$pic_images = array();
$pic_cat_names = array();
$pic_file_names = array();
$pic_names = array();
$dir = @opendir(ALBUM_OTF_PATH);

//while($file = @readdir($dir))
while(false !== ($file = readdir($dir)))
{
	if(($file != '.') && ($file != '..') && (!is_file(ALBUM_OTF_PATH . $file)) && (!is_link(ALBUM_OTF_PATH . $file)))
	{
		$sub_dir = @opendir(ALBUM_OTF_PATH . $file);

		$pic_row_count = 0;
		$pic_col_count = 0;
		while($sub_file = @readdir($sub_dir))
		{
			if(preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $sub_file))
			{
				$pic_images[$file][$pic_row_count][$pic_col_count] = $file . '/' . $sub_file;
				$pic_cat_names[$file][$pic_row_count][$pic_col_count] = $file;
				$pic_file_names[$file][$pic_row_count][$pic_col_count] = $sub_file;
				$pic_names[$file][$pic_row_count][$pic_col_count] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $sub_file)));

				$pic_col_count++;
				if($pic_col_count == $album_config['cols_per_page'])
				{
					$pic_row_count++;
					$pic_col_count = 0;
				}
			}
		}
		@closedir($sub_dir);
	}
}
@closedir($dir);

@ksort($pic_images);
@reset($pic_images);

if(empty($pic_cat))
{
	list($pic_cat) = each($pic_images);
}
@reset($pic_images);

$s_categories = '<select name="pic_cat">';
while(list($key) = each($pic_images))
{
	$selected = ($key == $pic_cat) ? ' selected="selected"' : '';
	if(sizeof($pic_images[$key]))
	{
		$s_categories .= '<option value="' . $key . '"' . $selected . '>' . ucfirst($key) . '</option>';
	}
}
$s_categories .= '</select>';

$s_colspan = 0;

$pic_cat_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_cat);
$js_include = '';
$js_images_list = '';

// Upload To Album - BEGIN
$select_cat = '';
if($user->data['user_level'] == ADMIN)
{
	$template->assign_block_vars('upload_allowed', array());

	$cat_id = ALBUM_ROOT_CATEGORY;
	album_read_tree($user->data['user_id'], ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW_AND_UPLOAD);
	$userinfo = album_get_nonexisting_personal_gallery_info();
	$count = sizeof($userinfo);
	for($idx = 0; $idx < $count; $idx++)
	{
		$personal_gallery = init_personal_gallery_cat($userinfo[$idx]['user_id']);
		$album_user_access = album_permissions($userinfo[$idx]['user_id'], 0, ALBUM_AUTH_CREATE_PERSONAL, $personal_gallery);
		if (album_check_permission($album_user_access, ALBUM_AUTH_CREATE_PERSONAL) == true)
		{
			$selected = (($user->data['user_id'] ==  $userinfo[$idx]['user_id'])) ? ' selected="selected"' : '';
			$personal_gallery_list .= '<option value="-' . $userinfo[$idx]['user_id'] . '" ' . $selected . '>' . sprintf($lang['Personal_Gallery_Of_User'], $userinfo[$idx]['username']) . '</option>';
		}
	}
	if (!empty($personal_gallery_list))
	{
		$personal_gallery_list = '<option value="' . ALBUM_JUMPBOX_SEPARATOR . '">------------------------------</option>' . $personal_gallery_list;
	}
	$temp_tree = album_get_tree_option($cat_id, ALBUM_AUTH_VIEW_AND_UPLOAD) . $personal_gallery_list;
	if ($temp_tree == '')
	{
		message_die(GENERAL_ERROR, $lang['No_category_to_upload']);
	}
	$select_cat = '<select name="cat_id">';
	$select_cat .= $temp_tree;
	$select_cat .= '</select>';
	unset($personal_gallery_list);
	album_free_album_data();
}
// Upload To Album - END

$nav_server_url = create_server_url();
$breadcrumbs_address = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . ALBUM_NAV_ARROW . '<a class="nav-current" href="' . $nav_server_url . append_sid('album_otf.' . PHP_EXT) . '">' . $lang['Pic_Gallery'] . '</a>';

// Upload To Album - BEGIN
$upload_counter = 0;

$otf_pic_time = time();

for($i = 0; $i < sizeof($pic_images[$pic_cat]); $i++)
{
	$template->assign_block_vars('pic_row', array());

	$s_colspan = max($s_colspan, sizeof($pic_images[$pic_cat][$i]));
	$s_colwidth = ($s_colspan == 0) ? '100%' : 100 / $s_colspan . '%';

	for($j = 0; $j < sizeof($pic_images[$pic_cat][$i]); $j++)
	{
		$otf_pic_time = $otf_pic_time + 1;
		$pic_img_url = append_sid(ALBUM_OTF_PATH . $pic_images[$pic_cat][$i][$j]);

		/*
		$pic_thumbnail = $pic_cat_names[$pic_cat][$i][$j] . '_' . $pic_file_names[$pic_cat][$i][$j];
		$pic_thumbnail_fullpath = ALBUM_CACHE_PATH . $pic_thumbnail;
		if (file_exists($pic_thumbnail_fullpath))
		{
			$pic_img_thumb = $pic_thumbnail_fullpath;
		}
		else
		{
			$pic_img_thumb = append_sid(album_append_uid('album_otf_thumbnail.' . PHP_EXT . '?pic_cat=' . $pic_cat_names[$pic_cat][$i][$j] . '&amp;pic_id=' . $pic_file_names[$pic_cat][$i][$j]));
		}
		*/

		$pic_img_thumb = append_sid(album_append_uid('album_otf_thumbnail.' . PHP_EXT . '?pic_cat=' . $pic_cat_names[$pic_cat][$i][$j] . '&amp;pic_id=' . $pic_file_names[$pic_cat][$i][$j]));

		if (($upload_pics == true) && ($cat_to_upload > 0))
		{
			if ($upload_counter < 9)
			{
				$otf_pic_title = $pic_cat . ' - 00' . ($upload_counter + 1);
			}
			elseif (($upload_counter > 8) && ($upload_counter < 99))
			{
				$otf_pic_title = $pic_cat . ' - 0' . ($upload_counter + 1);
			}
			else
			{
				$otf_pic_title = $pic_cat . ' - ' . ($upload_counter + 1);
			}
			$otf_pic_path = ALBUM_OTF_PATH . $pic_images[$pic_cat][$i][$j];
			$otf_pic_filename = $pic_file_names[$pic_cat][$i][$j];
			$file_split = explode('.', $otf_pic_filename);
			$otf_pic_extension = $file_split[sizeof($file_split) - 1];
			$otf_pic_filename = substr($otf_pic_filename, 0, strlen($otf_pic_filename) - strlen($otf_pic_extension) - 1);
			if (pic_upload_to_cat($otf_pic_path, $otf_pic_filename, $otf_pic_extension, ucfirst($otf_pic_title), $pic_names[$pic_cat][$i][$j], $cat_to_upload, $otf_pic_time))
			{
				$upload_counter++;
			}
		}
		$template->assign_block_vars('pic_row.pic_column', array(
			'PIC_IMAGE' => $pic_img_url,
			'PIC_THUMB' => $pic_img_thumb,
			'PIC_NAME' => $pic_names[$pic_cat][$i][$j]
			)
		);

		$template->assign_block_vars('pic_row.pic_option_column', array(
			'S_OPTIONS_PIC' => $pic_images[$pic_cat][$i][$j]
			)
		);
	}
}

if (($upload_pics == true) && ($cat_to_upload > 0))
{
	synchronize_cat_pics_counter($cat_to_upload);
	//$template->assign_block_vars('upload_confirm', array());
	if ($upload_counter > 0)
	{
		$message = $lang['Album_upload_successful'] . ' (' . $upload_counter . ')';
	}
	elseif ($upload_counter < sizeof($pic_images[$pic_cat]))
	{
		$message = $lang['Album_upload_partially_successful'] . ' (' . $upload_counter . ')';
	}
	else
	{
		$message = $lang['Album_upload_not_successful'];
	}
	message_die(GENERAL_MESSAGE, $message);
}
// Upload To Album - END

$template->assign_vars(array(
	'L_PIC_GALLERY' => $lang['Pic_Gallery'],
	'L_SELECT_PIC' => $lang['Select_Pic'],
	'L_CATEGORY' => $lang['Select_Category'],
	'L_UPLOAD_PICS' => $lang['Upload_Pics'],

	'JS_INCLUDE' => $js_images_list . "\n" . $js_include,

	'SELECTED_CAT' => $pic_cat,
	'SELECTED_CAT_REG' => $pic_cat_reg,

	// Admins Only
	'SELECT_CAT' => $select_cat,
	'UPLOADED_PIC' => $upload_counter,

	'S_CATEGORY_SELECT' => $s_categories,
	'S_COLSPAN' => $s_colspan,
	'S_COLWIDTH' => $s_colwidth,
	'S_ACTION' => append_sid('album_otf.' . PHP_EXT),
	)
);

full_page_generation('album_otf_body.tpl', $lang['Album'], '', '');

?>