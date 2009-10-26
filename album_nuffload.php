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
* Nuffmon (nuffmon@hotmail.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$path_to_bin = $album_config['path_to_bin'];
$show_progress_bar = $album_config['show_progress_bar'];
$close_on_finish = $album_config['close_on_finish'];
$max_pause = $album_config['max_pause'];
$multiple_uploads = $album_config['multiple_uploads'];
$zip_uploads = $album_config['zip_uploads'];
$resize_pic = $album_config['resize_pic'];
$resize_width = $album_config['resize_width'];
$resize_height = $album_config['resize_height'];
$resize_quality = $album_config['resize_quality'];
if (!$album_config['perl_uploader']) {$show_progress_bar = 0;}

fix_magic_quotes();

// This part handles files after the upload and passes variables across
if (isset($_REQUEST['psid']))
{
	// Clean up old files first
	$dir = $path_to_bin . 'tmp/';
	if (is_dir($dir))
	{
		if ($dh = opendir($dir))
		{
			while (($file = readdir($dh)) !== false)
			{
				if (filectime($dir . $file) < (time() - 3600))
				{
					@unlink($dir . $file);
				}
			}
			closedir($dh);
		}
	}

	// Session id for this upload.
	$psid = $_REQUEST['psid'];

	// Check if this a multi upload so we transfer the correct upload file
	if ($_GET['multi_id'])
	{
		$multi_tag = "-" . $_GET['multi_id'];
		$multi_id = $_GET['multi_id'];
	}

	// Routine for php uploading, save files to disk.
	// hmmm should probably check full compatibility with this.
	if (!$album_config['perl_uploader'] && !$multi_id)
	{
		$qstr = "";
		$key_names = array_keys($_GET);
		for($a=0;$a< sizeof($key_names);$a++)
		{
			$qstr .= "&" . $key_names[$a] . "=" . $_GET[$key_names[$a]];
		}
		$key_names = array_keys($_POST);
		for($a=0;$a< sizeof($key_names);$a++)
		{
			$qstr .= "&" . $key_names[$a] . "=" . $_POST[$key_names[$a]];
		}
		$key_names = array_keys($_FILES);
		for($a = 0; $a < sizeof($key_names); $a++)
		{
			$qstr .= "&file[field][$a]=" . $key_names[$a];
			$qstr .= "&file[name][$a]=" . $_FILES[$key_names[$a]][name];
			$qstr .= "&file[size][$a]=" . $_FILES[$key_names[$a]][size];
			$qstr .= "&file[tmp_name][$a]=" . 'tmp/' . $psid . "_actualdata" . $a;
			// Move this file to upload directory
			// Inefficient but works at the moment
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
			if ( @$ini_val('open_basedir') != '' )
			{
				if ( @phpversion() < '4.0.3' )
				{
					message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
				}
				$move_file = 'move_uploaded_file';
			}
			else
			{
				$move_file = 'copy';
			}
			$move_file($_FILES[$key_names[$a]][tmp_name], $path_to_bin . 'tmp/' . $psid . "_actualdata" . $a);
		}
		@unlink($path_to_bin . 'tmp/' . $psid . '_qstring');
		$handle = fopen($path_to_bin . 'tmp/' . $psid . '_qstring', 'w');
		fwrite($handle, $qstr);
		fclose($handle);
	}

	// Create variables from query string file.
	$qstr = @join("", @file($path_to_bin . 'tmp/' . $psid . '_qstring'));
	parse_str($qstr);
	$qstr_array = explode("&",$qstr);
	for( $i = 0; $i < sizeof($qstr_array); $i++)
	{
		$temp = explode("=",$qstr_array[$i]);
		if (!preg_match("/^file\[/", $qstr_array[$i]))
		{
			$_GET[$temp[0]] = urldecode($temp[1]);
			$_POST[$temp[0]] = urldecode($temp[1]);
		}
	}
	//print "Query string = " . $qstr . "<br />";


	// Needed for album hierarchy mod
	$album_user_id = $_GET['user_id'];

	// Find the total number of file inputs from the form
	$multi_max = 0;
	$k = sizeof($file['name']);
	for($i=0 ; $i < $k ; $i++)
	{
		$multi_array = explode("-",$file['field'][$i]);
		if (intval($multi_array[1])>$multi_max)
		{
			$multi_max = intval($multi_array[1]);
		}
	}
	//print "File inputs = " . $multi_max . "<br />";
	//exit;

	// Extract archives and save in variable list
	if($zip_uploads && !$multi_id)
	{
		require_once(IP_ROOT_PATH . 'includes/pclzip.lib.' . PHP_EXT);
		$pfm = $multi_max;
		$ptm = $multi_max;
		for($i=0 ; $i < $k ; $i++)
		{
			$archive = new PclZip($path_to_bin . $file['tmp_name'][$i]);
			$list = $archive->extract(PCLZIP_OPT_PATH, $path_to_bin . "tmp", PCLZIP_OPT_REMOVE_ALL_PATH);
			if ($list)
			{
				@unlink($path_to_bin . $file['tmp_name'][$i]);
				$original_filename = $file['tmp_name'][$i];
				rename($path_to_bin . 'tmp/' . basename($list[0]['filename']), $path_to_bin . $original_filename . "0");
				$field_name = explode("-",$file['field'][$i]);
				$file['size'][$i] = $list[0]['size'];
				$file['name'][$i] = basename($list[0]['stored_filename']);
				$file['tmp_name'][$i] = $original_filename . "0";
				for($j = 1; $j < sizeof($list); $j++)
				{
					rename($path_to_bin . 'tmp/' . basename($list[$j]['filename']), $path_to_bin . $original_filename . $j);
					$file['size'][$k] = $list[$j]['size'];
					$file['name'][$k] = basename($list[$j]['stored_filename']);
					$file['tmp_name'][$k] = $original_filename . $j;
					if($field_name[0] == "pic_file")
					{
						$pfm++;
						$file['field'][$k] = $field_name[0] . "-" . $pfm;
					}
					if($field_name[0] == "pic_thumbnail")
					{
						$ptm++;
						$file['field'][$k] = $field_name[0] . "-" . $ptm;
					}
					$k++;
				}
			}
		}
		// Strip "file" from the qstring file so we can rebuild it.
		$qstr_array = explode("&", $qstr);
		$qstr = "";
		for($i=0 ; $i < sizeof($qstr_array) ; $i++)
		{
			if (!preg_match("/^file\[/", $qstr_array[$i]))
			{
				$qstr .= "&" . $qstr_array[$i];
			}
		}
		// Now add "file" variables to qstring file.
		for($i=0 ; $i < $k ; $i++)
		{
			$qstr .= "&file[size][$i]=" . $file['size'][$i];
			$qstr .= "&file[name][$i]=" . $file['name'][$i];
			$qstr .= "&file[tmp_name][$i]=" . $file['tmp_name'][$i];
			$qstr .= "&file[field][$i]=" . $file['field'][$i];
		}
		$multi_max = ($pfm >= $ptm) ? $pfm : $ptm;
		unlink($path_to_bin . 'tmp/' . $psid . '_qstring');
		$handle = fopen($path_to_bin . 'tmp/' . $psid . '_qstring', 'w');
		fwrite($handle, $qstr);
		fclose($handle);
	}

	// Loop through array to find pic and thumbnail to insert.
	for($i=0 ; $i < $k ; $i++)
	{
		// Check for correct thumbnail and transfer variables
		if ($file['field'][$i] == 'pic_thumbnail' . $multi_tag)
		{
			$thumb_type_error = false;
			$_FILES['pic_thumbnail']['tmp_name'] = $path_to_bin . $file['tmp_name'][$i];
			/*
			$split_name = explode("\\",$file['name'][$i]);
			$file_name = $split_name[sizeof($split_name)-1];
			*/
			$file_name = addslashes(stripslashes(basename($file['name'][$i])));
			$_FILES['pic_thumbnail']['name'] = $file_name;
			$_FILES['pic_thumbnail']['size'] = $file['size'][$i];
			// Find image type and check if allowed
			$image_data = @getimagesize($path_to_bin . $file['tmp_name'][$i]);
			switch ($image_data[2])
			{
				case '1':
					if (!$album_config['gif_allowed'])
					{
						$thumb_type_error = true;
					}
					$_FILES['pic_thumbnail']['type'] = 'image/gif';
					break;
				case '2':
					if (!$album_config['jpg_allowed'])
					{
						$thumb_type_error = true;
					}
					$_FILES['pic_thumbnail']['type'] = 'image/jpeg';
					break;
				case '3':
					if (!$album_config['png_allowed'])
					{
						$thumb_type_error = true;
					}
					$_FILES['pic_thumbnail']['type'] = 'image/png';
					break;
				default:
					$thumb_type_error = true;
			}
		}
		// Check for correct picture and transfer variables
		elseif ($file['field'][$i] == 'pic_file' . $multi_tag)
		{
			$pic_type_error = false;
			$_FILES['pic_file']['tmp_name'] = $path_to_bin . $file['tmp_name'][$i];
			/*
			$split_name = explode("\\",$file['name'][$i]);
			$file_name = $split_name[sizeof($split_name)-1];
			*/
			$file_name = addslashes(stripslashes(basename($file['name'][$i])));
			$_FILES['pic_file']['name'] = $file_name;
			$_FILES['pic_file']['size'] = $file['size'][$i];
			// Find image type and check if allowed
			$image_data = @getimagesize($path_to_bin . $file['tmp_name'][$i]);
			$pic_width = $image_data[0];
			$pic_height = $image_data[1];
			switch ($image_data[2])
			{
				case '1':
					if (!$album_config['gif_allowed'])
					{
						$pic_type_error = true;
					}
					$_FILES['pic_file']['type'] = 'image/gif';
					break;
				case '2':
					if (!$album_config['jpg_allowed'])
					{
						$pic_type_error = true;
					}
					$_FILES['pic_file']['type'] = 'image/jpeg';
					break;
				case '3':
					if (!$album_config['png_allowed'])
					{
						$pic_type_error = true;
					}
					$_FILES['pic_file']['type'] = 'image/png';
					break;
				default:
					$pic_type_error = true;
			}
		}
	}

	// Build picture title
	if ($_POST['pic_title'] == '')
	{
		$tmp_pic_file_name = explode(".", $_FILES['pic_file']['name']);
		$_POST['pic_title'] = $tmp_pic_file_name[0];
		unset($tmp_pic_file_name);
	}
	elseif ($multi_max > 0)
	{
		$_POST['pic_title'] .= " - " . str_pad(($multi_id + 1), 3, "0", STR_PAD_LEFT);
	}

	// Handle no pic file error.
	if ($_FILES['pic_file']['size'] == 0)
	{
		message_die(GENERAL_MESSAGE, multi_loop($lang['no_file_received']));
	}

	// Handle no thumbnail file error.
	if ($album_config['gd_version'] == 0 && $_FILES['pic_thumbnail']['size'] == 0)
	{
		message_die(GENERAL_MESSAGE, multi_loop("no_thumbnail_file_received"));
	}

	// Handle pic filetype error.
	if ($pic_type_error)
	{
		message_die(GENERAL_MESSAGE, multi_loop($lang['Not_allowed_file_type']));
	}

	// Handle thumbnail filetype errors here...
	if ($thumb_type_error)
	{
		message_die(GENERAL_MESSAGE, multi_loop($lang['Not_allowed_file_type']));
	}

	// Resize image if option selected
	if ($resize_pic && ($pic_width > $album_config['max_width'] or $pic_height > $album_config['max_height']))
	{
		$_FILES['pic_file']['type'] = resize_image($_FILES['pic_file']['tmp_name'], $resize_width, $resize_height, $resize_quality);
		$_FILES['pic_file']['size'] = filesize($_FILES['pic_file']['tmp_name']);
	}

	// Handle large pic file error.
	if ($_FILES['pic_file']['size'] > $album_config['max_file_size'])
	{
		message_die(GENERAL_MESSAGE, multi_loop($lang['file_too_big']));
	}

	// Handle large thumbnail file error.
	if ($album_config['gd_version'] == 0 && $_FILES['pic_thumbnail']['size'] > $album_config['max_file_size'])
	{
		message_die(GENERAL_MESSAGE, multi_loop($lang['thumbnail_too_big']));
	}

	// Handle large resolution pic error.
	$image_data = getimagesize($_FILES['pic_file']['tmp_name']);
	if ($image_data[0] > $album_config['max_width'] || $image_data[1] > $album_config['max_height'])
	{
		message_die(GENERAL_MESSAGE, multi_loop($lang['image_res_too_high']));
	}

	// Handle large resolution thumbnail error.
	if ($album_config['gd_version'] == 0)
	{
		$image_data = getimagesize($_FILES['pic_thumbnail']['tmp_name']);
		if ($image_data[0] > $album_config['thumbnail_size'] || $image_data[1] > $album_config['thumbnail_size'])
		{
			message_die(GENERAL_MESSAGE, multi_loop($lang['thumb_res_too_high']));
		}
	}

	// Last pass? delete query string because we don't need it anymore...
	if ($multi_id >= $multi_max)
	{
		@unlink($path_to_bin . 'tmp/' . $psid . '_qstring');
	}
	// ...otherwise block the email notification.
	else
	{
		$album_config['email_notification'] = 0;
	}

	// If idlevoids multi mod installed convert array.
	if (isset($album_config['max_files_to_upload']))
	{
		$tmp_tmp_name = $_FILES['pic_file']['tmp_name'];
		$tmp_name = $_FILES['pic_file']['name'];
		$tmp_size = $_FILES['pic_file']['size'];
		$tmp_type = $_FILES['pic_file']['type'];
		$ttmp_tmp_name = $_FILES['pic_thumbnail']['tmp_name'];
		$ttmp_name = $_FILES['pic_thumbnail']['name'];
		$ttmp_size = $_FILES['pic_thumbnail']['size'];
		$ttmp_type = $_FILES['pic_thumbnail']['type'];
		unset($_FILES);
		$_FILES['pic_file']['tmp_name'][0] = $tmp_tmp_name;
		$_FILES['pic_file']['name'][0] = $tmp_name;
		$_FILES['pic_file']['size'][0] = $tmp_size;
		$_FILES['pic_file']['type'][0] = $tmp_type;
		$_FILES['pic_thumbnail']['tmp_name'][0] = $ttmp_tmp_name;
		$_FILES['pic_thumbnail']['name'][0] = $ttmp_name;
		$_FILES['pic_thumbnail']['size'][0] = $ttmp_size;
		$_FILES['pic_thumbnail']['type'][0] = $ttmp_type;
	}
}
// In an include with no session id we create a new session id
else
{
	$psid = md5(uniqid(rand()));
	$cat_id = $_REQUEST['cat_id'];
	$user_id = $_REQUEST['user_id'];
	$album_user_id = intval($_REQUEST['user_id']);
	if($album_config['perl_uploader'])
	{
		$uploader = (function_exists(album_append_uid))? album_append_uid($path_to_bin . "nuffload.cgi?psid=$psid&cat_id=$cat_id") . "&redirect=http://" . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF'] : $path_to_bin . "nuffload.cgi?psid=$psid&cat_id=$cat_id&redirect=http://" . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF'];
	}
	else
	{
		$uploader = (function_exists(album_append_uid))? album_append_uid("album_upload.php?psid=$psid&cat_id=$cat_id") : "album_upload.php?psid=$psid&cat_id=$cat_id";
	}
	$uploader = append_sid($uploader);
}

//******************************************************************************
// Function to produce messages for loop
//     usage : multi_loop(message as string, [success message as bool])
//     returns : Modified message as string
//******************************************************************************
function multi_loop($message, $success=false)
{
	global $multi_id, $multi_max, $template, $psid, $lang, $thiscat, $cat_id, $pic_thumbnail, $album_user_id;

	if($success)
	{
		if ($thiscat['cat_approval'] == 0)
		{
			$message = $lang['Album_upload_successful'];
		}
		else
		{
			$message = $lang['Album_upload_need_approval'];
		}
		$message .= '<br /><br /><img src="' . ALBUM_CACHE_PATH . $pic_thumbnail . '" alt="' . $lang['Album_upload_successful'] . '" />';
	}
	if ($multi_id < $multi_max)
	{
		$multi_id++;
		$return_page = (function_exists(album_append_uid))? album_append_uid('album_upload.' . PHP_EXT . '?psid=' . $psid . '&multi_id=' . $multi_id) : 'album_upload.' . PHP_EXT . '?psid=' . $psid . '&multi_id=' . $multi_id;

		$redirect_url = append_sid($return_page);
		meta_refresh(3, $redirect_url);

		$message .= '<br /><br /><span class="gen">' . $lang['please_wait'] . '<br />' . str_replace("%multi_id%", $multi_id, str_replace("%multi_max%", $multi_max + 1, $lang['uploaded'])) . '</span><br /><br />';
	}
	else
	{
		$multi_id++;
		$message .= '<br /><br /><span class="gen">' . str_replace("%multi_id%", $multi_id, str_replace("%multi_max%", $multi_max + 1, $lang['uploaded'])) . '</span><br /><br />';
		if ($cat_id != PERSONAL_GALLERY)
		{
			$return_page = (function_exists(album_append_uid))? album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id) : 'album_cat.' . PHP_EXT . '?cat_id=' . $cat_id;
			if ($thiscat['cat_approval'] == 0)
			{
				$redirect_url = append_sid($return_page);
				meta_refresh(3, $redirect_url);
			}

			$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid($return_page) . '">', '</a>');
		}
		else
		{
			if ($thiscat['cat_approval'] == 0)
			{
				$redirect_url = append_sid('album_personal.' . PHP_EXT);
				meta_refresh(3, $redirect_url);
			}
			$message .= '<br /><br />' . sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid('album_personal.' . PHP_EXT) . '">', '</a>');
		}
		$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');
	}
	return $message;
}

//******************************************************************************
// Function to resize image
//     usage : resize_image(filename as string, width as integer,
//                          height as integer, quality as integer)
//     Returns : Mime Image type as string or FALSE on error
//******************************************************************************
function resize_image($image_file_name, $resize_width, $resize_height, $resize_quality)
{
	// Check file and read into memory
	$image_data = getimagesize($image_file_name);
	$pic_width = $image_data[0];
	$pic_height = $image_data[1];
	switch ($image_data[2])
	{
		case '1':
			$read_function = 'imagecreatefromgif';
			$type = 'image/gif';
			break;
		case '2':
			$read_function = 'imagecreatefromjpeg';
			$type = 'image/jpeg';
			break;
		case '3':
			$read_function = 'imagecreatefrompng';
			$type = 'image/png';
			break;
		default:
			return false;
	}
	$src = @$read_function($image_file_name);

	// Resize image
	if (!$src)
	{
		return false;
	}
	if (($pic_width / $pic_height) > ($resize_width / $resize_height))
	{
		$resize_height = $resize_width * ($pic_height/$pic_width);
	}
	else
	{
		$resize_width = $resize_height * ($pic_width/$pic_height);
	}
	$resize = (gdVersion() == 1) ? @imagecreate($resize_width, $resize_height) : @imagecreatetruecolor($resize_width, $resize_height);
	$resize_function = (gdVersion == 1) ? 'imagecopyresized' : 'imagecopyresampled';
	@$resize_function($resize, $src, 0, 0, 0, 0, $resize_width, $resize_height, $pic_width, $pic_height);

	// Write file to disk
	switch ($image_data[2]){
		case '1':
			@unlink($image_file_name);
			// Check gif support and use convert to jpeg if not possible
			if (imagetypes() & IMG_GIF)
			{
				@imagegif($resize, $image_file_name);
				$type = 'image/gif';
			}
			else
			{
				@imagejpeg($resize, $image_file_name, $resize_quality);
				$type = 'image/jpeg';
			}
			break;
		case '2':
			@unlink($image_file_name);
			@imagejpeg($resize, $image_file_name, $resize_quality);
			$type = 'image/jpeg';
			break;
		case '3':
			@unlink($image_file_name);
			@imagepng($resize, $image_file_name);
			$type = 'image/png';
			break;
	}
	@chmod($image_file_name, 0777);
	imagedestroy($src);
	imagedestroy($resize);
	return $type;
}

//******************************************************************************
// Function to find version (1 or 2) of the GD extension.
//   Usage : gdVersion()
//   Returns : version number as integer
//******************************************************************************
function gdVersion($user_ver = 0)
{
	if (! extension_loaded('gd'))
	{
		return;
	}
	static $gd_ver = 0;
	if ($user_ver == 1)
	{
		$gd_ver = 1;
		return 1;
	}
	if ($user_ver !=2 && $gd_ver > 0 )
	{
		return $gd_ver;
	}
	if (function_exists('gd_info'))
	{
		$ver_info = gd_info();
		preg_match('/\d/', $ver_info['GD Version'], $match);
		$gd_ver = $match[0];
		return $match[0];
	}
	if (preg_match('/phpinfo/', ini_get('disable_functions')))
	{
		if ($user_ver == 2)
		{
			$gd_ver = 2;
			return 2;
		}
		else
		{
			$gd_ver = 1;
			return 1;
		}
	}
	ob_start();
	phpinfo(8);
	$info = ob_get_contents();
	ob_end_clean();
	$info = stristr($info, 'gd version');
	preg_match('/\d/', $info, $match);
	$gd_ver = $match[0];
	return $match[0];
}

//******************************************************************************
// Function to emulate magic quotes being turned off
//   Usage : fix_magic_quotes ($var = NULL, $sybase = NULL)
//   Returns : specified var $VAR or converts all superglobals
//******************************************************************************
function fix_magic_quotes ($var = NULL, $sybase = NULL)
{
	// if sybase style quoting isn't specified, use ini setting
	if ( !isset ($sybase) )
	{
		$sybase = ini_get ('magic_quotes_sybase');
	}

	// if no var is specified, fix all affected superglobals
	if ( !isset ($var) )
	{
		// if magic quotes is enabled
		if ( get_magic_quotes_gpc () )
		{
			// workaround because magic_quotes does not change $_SERVER['argv']
			$argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : NULL;

			// fix all affected arrays
			foreach (array('_ENV', '_REQUEST', '_GET', '_POST', '_COOKIE', '_SERVER') as $var )
			{
				$GLOBALS[$var] = fix_magic_quotes ($GLOBALS[$var], $sybase);
			}

			$_SERVER['argv'] = $argv;

			// turn off magic quotes, this is so scripts which
			// are sensitive to the setting will work correctly
			ini_set ('magic_quotes_gpc', 0);
		}

		// disable magic_quotes_sybase
		if ( $sybase )
		{
			ini_set ('magic_quotes_sybase', 0);
		}

		// disable magic_quotes_runtime
		set_magic_quotes_runtime (0);
		return TRUE;
	}

	// if var is an array, fix each element
	if ( is_array ($var) )
	{
		foreach ( $var as $key => $val )
		{
			$var[$key] = fix_magic_quotes ($val, $sybase);
		}

		return $var;
	}

	// if var is a string, strip slashes
	if ( is_string ($var) )
	{
		return $sybase ? str_replace ('\'\'', '\'', $var) : stripslashes ($var);
	}

	// otherwise ignore
	return $var;
}
?>