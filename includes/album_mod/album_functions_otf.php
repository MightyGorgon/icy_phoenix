<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

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
				VALUES ('" . $db->sql_escape($pic_extra_path . $otf_pic_full_filename) . "', '', '" . $db->sql_escape($otf_pic_title) . "', '" . $db->sql_escape($otf_pic_des) . "', '" . $otf_pic_user_id . "', '" . $otf_pic_user_ip . "', '" . $db->sql_escape($otf_pic_username) . "', '" . $otf_pic_time . "', '" . $otf_pic_cat . "', '1')";
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
				if (($file == '.') || ($file == '..') || ($file == '.DS_Store'))
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