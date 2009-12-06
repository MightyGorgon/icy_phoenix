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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

if(isset($_GET['pic_id']))
{
	$pic_id = $_GET['pic_id'];
}
elseif(isset($_POST['pic_id']))
{
	$pic_id = $_POST['pic_id'];
}
else
{
	$pic_id = '';
}

if(isset($_GET['pic_cat']))
{
	$pic_cat = $_GET['pic_cat'];
}
elseif(isset($_POST['pic_cat']))
{
	$pic_cat = $_POST['pic_cat'];
}
else
{
	$pic_cat = '';
}

/*
if (!empty($_POST['pic_cat']))
{
	$pic_cat = htmlspecialchars($_POST['pic_cat']);
}
elseif (!empty($_GET['pic_cat']))
{
	$pic_cat = htmlspecialchars($_GET['pic_cat']);
}
*/

$upload_pics = false;
$cat_to_upload = false;
if(isset($_POST['pic_upload']))
{
	if (($_POST['pic_upload'] == true) && (isset($_POST['cat_id'])))
	{
		$cat_to_upload = $_POST['cat_id'];
		$upload_pics = true;
	}
}

if(isset($_GET['mode']))
{
	$mode = $_GET['mode'];
}
elseif(isset($_POST['mode']))
{
	$mode = $_POST['mode'];
}
else
{
	$mode = '';
}

if (($mode == 'delete') && ($pic_id != ''))
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
/*
if ($album_config['enable_mooshow'])
{
	$template->assign_block_vars('mooshow', array());
	$js_images_list = get_images_list(ALBUM_OTF_PATH . $pic_cat, $pic_cat_reg);

	$js_include .= '<script type="text/javascript" src="templates/common/album/prototype.lite.js"></script>' . "\n";
	$js_include .= '<script type="text/javascript" src="templates/common/album/moo.fx.js"></script>' . "\n";
	$js_include .= '<script type="text/javascript" src="templates/common/album/moo.fx.pack.js"></script>' . "\n";
	$js_include .= '<script type="text/javascript" src="templates/common/album/mooshow.1.04.js"></script>' . "\n";
	$js_include .= '<script type="text/javascript">var showsIE = new Array("' . $pic_cat_reg . '");</script>' . "\n";
}
*/

// Upload To Album - BEGIN
$select_cat = '';
if($userdata['user_level'] == ADMIN)
{
	$template->assign_block_vars('upload_allowed', array());

	$cat_id = ALBUM_ROOT_CATEGORY;
	album_read_tree($userdata['user_id'], ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW_AND_UPLOAD);
	$userinfo = album_get_nonexisting_personal_gallery_info();
	$count = sizeof($userinfo);
	for($idx=0; $idx < count; $idx++)
	{
		$personal_gallery = init_personal_gallery_cat($userinfo[$idx]['user_id']);
		$album_user_access = album_permissions($userinfo[$idx]['user_id'], 0, ALBUM_AUTH_CREATE_PERSONAL, $personal_gallery);
		if (album_check_permission($album_user_access, ALBUM_AUTH_CREATE_PERSONAL) == true)
		{
			$selected = (($userdata['user_id'] ==  $userinfo[$idx]['user_id'])) ? ' selected="selected"' : '';
			$personal_gallery_list .= '<option value="-' . $userinfo[$idx]['user_id'] . '" ' . $selected . '>' . sprintf($lang['Personal_Gallery_Of_User'], $userinfo[$idx]['username']) . '</option>';
		}
	}
	if (!empty($personal_gallery_list))
	{
		$personal_gallery_list = '<option value="' . ALBUM_JUMPBOX_SEPERATOR . '">------------------------------</option>' . $personal_gallery_list;
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

// FUNCTIONS - BEGIN
function pic_upload_to_cat($otf_pic_path, $otf_pic_filename, $otf_pic_extension, $otf_pic_title, $otf_pic_des, $otf_pic_cat, $otf_pic_time)
{
	global $db, $userdata;

	$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
	$pic_extra_path = '';
	$upload_path = $pic_base_path . $pic_extra_path;
	if (USERS_SUBFOLDERS_ALBUM == true)
	{
		$pic_extra_path = $userdata['user_id'] . '/';
		$upload_path = $pic_base_path . $pic_extra_path;
		if (!is_dir($upload_path))
		{
			$dir_creation = @mkdir($upload_path, 0777);
			if ($dir_creation == true)
			{
				@copy($pic_base_path . 'index.html', $upload_path . 'index.html');
				@chmod($upload_path . 'index.html', 0755);
			}
			else
			{
				$upload_path = $pic_base_path;
			}
		}
	}

	while (file_exists($upload_path . $otf_pic_filename . '.' . $otf_pic_extension))
	{
		$otf_pic_filename = $otf_pic_filename . '_' . time() . '_' . mt_rand(100000, 999999);
	}
	$otf_pic_full_filename = $otf_pic_filename . '.' . $otf_pic_extension;

	if ($otf_pic_time == '')
	{
		$otf_pic_time = time();
	}
	$otf_pic_username = $userdata['username'];
	$otf_pic_user_id = $userdata['user_id'];
	$otf_pic_user_ip = $userdata['session_ip'];

	$move_file = 'rename';
	//$move_file = 'copy';
	//$move_file = 'move_uploaded_file';
	$upload_success = $move_file($otf_pic_path, $upload_path . $otf_pic_full_filename);

	if ($upload_success)
	{
		@chmod($upload_path . $otf_pic_full_filename, 0777);
		$sql = "INSERT INTO " . ALBUM_TABLE . " (pic_filename, pic_thumbnail, pic_title, pic_desc, pic_user_id, pic_user_ip, pic_username, pic_time, pic_cat_id, pic_approval)
				VALUES ('" . ($pic_extra_path . $otf_pic_full_filename) . "', '', '" . $otf_pic_title . "', '" . $otf_pic_des . "', '" . $otf_pic_user_id . "', '" . $otf_pic_user_ip . "', '" . $otf_pic_username . "', '" . $otf_pic_time . "', '" . $otf_pic_cat . "', '1')";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			return false;
		}
	}

	return true;
}

function get_images_list($path, $gallery_name)
{

	$files = array();
	$file_names = array();
	$i = 0;

	if (is_dir($path))
	{
		if ($dh = opendir($path))
		{
			while (($file = readdir($dh)) !== false)
			{
				if ($file == "." || $file == ".." || $file == ".DS_Store")
				{
					continue;
				}
				if(preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file))
				{
					$fullpath = $path . '/' . $file;
					$fkey = strtolower($file);
					while (array_key_exists($fkey, $file_names))
					{
						$fkey .= ' ';
					}
					$a = stat($fullpath);
					$files[$fkey]['size'] = $a['size'];
					if ($a['size'] == 0)
					{
						$files[$fkey]['sizetext'] = '-';
					}
					elseif ($a['size'] > 1024)
					{
						$files[$fkey]['sizetext'] = (ceil($a['size']/1024*100)/100) . ' Kb';
					}
					elseif ($a['size'] > 1024 * 1024)
					{
						$files[$fkey]['sizetext'] = (ceil($a['size']/(1024*1024)*100)/100) . ' Mb';
					}
					else
					{
						$files[$fkey]['sizetext'] = $a['size'] . ' bytes';
					}
					$files[$fkey]['name'] = $file;
					$files[$fkey]['type'] = filetype($fullpath);
					$file_names[$i++] = $fkey;
				}
			}
			closedir($dh);
		}
		else
		{
			die ('Cannot open directory: ' . $path);
		}
	}
	else
	{
		die ('Path is not a directory: ' . $path);
	}
	sort($file_names, SORT_STRING);
	$sortedFiles = array();
	$i = 0;
	foreach($file_names as $f)
	{
		$sortedFiles[$i++] = $files[$f];
	}

	$js_images_list = '';
	$js_images_list .= '<script type="text/javascript">' . "\n";
	$js_images_list .= 'var ' . $gallery_name . ' = new Array(' . "\n";

	foreach ($sortedFiles as $file)
	{
		// get image sizes
		//list($width, $height, $type, $attr) = getimagesize("$path/$file[name]", &$info);
		list($width, $height, $type, $attr) = getimagesize($path . '/' . $file['name'], $info);
		/*
		$width = imagesx($path . '/' . $file['name']);
		$height = imagesy($path . '/' . $file['name']);
		*/
		$size = $file['sizetext'];
		$iptc = iptcparse($info['APP13']);
		// iptc info
		$iptc = iptcparse($info['APP13']);
		$title = $iptc['2#005'][0];
		$description = $iptc['2#120'][0];
		$description = str_replace("\r", '<br />', $description);
		$description = addslashes($description);
		$keywords = $iptc['2#025'][0];
		$author = $iptc['2#080'][0];
		$copyright = $iptc['2#116'][0];
		$js_images_list .= 'new Array(\'' . $path . '/' . $file['name'] . '\', \'' . $width . '\', \'' . $height . '\', \'' . $size . '\', \'' . $title . '\', \'' . $author . '\', \'' . $copyright . '\', \'' . $description . '\'),' . "\n";
	}
	$js_images_list .= 'new Array(\'\', \'\')' . "\n";
	$js_images_list .= ');' . "\n";
	$js_images_list .= '</script>' . "\n";

	return $js_images_list;
}
// FUNCTIONS - END

?>