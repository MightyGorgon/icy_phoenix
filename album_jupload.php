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
* Shawn McBride
* OryNider (orynider@rdslink.ro)
*
*/

// CTracker_Ignore: File checked by human
session_start();
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_validate.' . $phpEx);

// Start session management
$userdata = defined('IS_ICYPHOENIX') ? session_pagestart($user_ip) : session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
// End session management

if ( !defined('ALBUM_MOD_PATH') )
{
	define('ALBUM_MOD_PATH', 'album_mod/');
}

// Get general album information
$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH;
include_once($album_root_path . 'album_common.' . $phpEx);

// ------------------------------------
// Check the request
// for this Upload script, we prefer POST to GET
// ------------------------------------

if( isset($_POST['user_id']) )
{
	$album_user_id = intval($_POST['user_id']);
}
elseif( isset($_GET['user_id']) )
{
	$album_user_id = intval($_GET['user_id']);
}
else
{
	// it's a public category we are uploading too
	$album_user_id = ALBUM_PUBLIC_GALLERY;
}

if( isset($_POST['cat_id']) )
{
	$cat_id = intval($_POST['cat_id']);
}
elseif( isset($_GET['cat_id']) )
{
	$cat_id = intval($_GET['cat_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No categories specified');
}


// ------------------------------------
// Get the current Category Info
// ------------------------------------
$sql = "SELECT c.*, COUNT(p.pic_id) AS count, IF (cat_user_id > 0, 1, 0) AS personal
		FROM ". ALBUM_CAT_TABLE ." AS c
			LEFT JOIN ". ALBUM_TABLE ." AS p ON c.cat_id = p.pic_cat_id
		WHERE c.cat_id = '$cat_id'
		GROUP BY c.cat_id
		LIMIT 1";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
}

$thiscat = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

// check if its a personal gallery request and if the gallery exists (checking $thiscat)
if (empty($thiscat) && $album_user_id != ALBUM_PUBLIC_GALLERY)
{
	//check if user exsts
	$user_name = album_get_user_name($album_user_id);
	if ( !empty($user_name) )
	{
		$thiscat = init_personal_gallery_cat($album_user_id);
	}
	else
	{
		// generate mesage saying that the user specified doesn't exists
		message_die(GENERAL_ERROR, $lang['No_user_id_specified']);
	}
}

if (empty($thiscat))
{
	message_die(GENERAL_ERROR, $lang['Category_not_exist']);
}

// ------------------------------------
// now get the gategory information
// ------------------------------------
$cat_id = $thiscat['cat_id'];
$current_pics = $thiscat['count'];


// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW_AND_UPLOAD, $thiscat);

if ($album_user_access['upload'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(album_append_uid(LOGIN_MG . '?redirect=album_jupload.' . $phpEx . '?cat_id=' . $cat_id)));
	}
	else
	{
		message_die(GENERAL_ERROR, "You are not authorized to add photos to this album.");
	}
}

//  ------------------------------------------------------------------------------------------------------------------------
//  --------------------------------album_jupload.php image processing settings.--------------------------------------------
//  ------------------------------------------------------------------------------------------------------------------------
	$jupload_temp_dir = ALBUM_JUPLOAD_PATH;			//Temporary directory where Jupload stores images.
	$use_pic_dirs = 0;													// 0=False, 1=True: Used to store uploaded pictures by username.
	$resize_method = 'PHP';											// "Jupload", "PHP" or "none" for image resizing options;
	$max_process_size = 4000000;								// Max size to process if resize_method is set to PHP.

if (!isset($_GET['mode']))
{
	//message_die(GENERAL_ERROR,"Set template stuff for main body here");

	// Start main page stuff. Applet is defined in jupload body template file...
	$page_title = $lang['Album'];
	$meta_description = '';
	$meta_keywords = '';

	include($phpbb_root_path . 'includes/page_header.' . $phpEx);

	$template->set_filenames(array('body' => 'album_jupload_body.tpl'));

	$template->assign_vars(array(
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)),
		'SELECTED_CAT' => $cat_id,
		'CAT_TITLE' => $thiscat['cat_title'],
		'APLET_PATH' => $phpbb_root_path,
		'ALBUM_MOD_PATH' => $album_root_path,
		'L_UPLOAD_PIC' => $lang['JUpload_Pic'],
		'L_USERNAME' => $lang['Username']
		)
	);

	// Assign Jupload Paramaters
	$template->assign_block_vars('jsize', array(
		'WIDTH' => '500',
		'HEIGHT' => '350'
		)
	);

	//$complete_url = append_sid(this_smartor_mxurl("smartor_mode=album_jupload&cat_id=".$cat_id."&mode=process"));
	//$complete_url = append_sid("../" . "album." . $phpEx . "?smartor_mode=album_jupload&cat_id=" . $cat_id . "&mode=process");
	//$action_url   = append_sid(this_smartor_mxurl("smartor_mode=album_jupload&cat_id=".$cat_id."&mode=upload"));
	//$action_url   = append_sid("../" . "album." . $phpEx . "?smartor_mode=album_jupload&cat_id=" . $cat_id . "&mode=upload" . '&sid=' . $userdata['session_id']);

	$complete_url = append_sid('album_jupload.' . $phpEx . '?cat_id=' . $cat_id . '&mode=process');
	$action_url   = append_sid('album_jupload.' . $phpEx . '?cat_id=' . $cat_id . '&mode=upload');

	$jparams = array();
	$jparams[] ="<param name=\"progressbar\" value=\"true\">";
	$jparams[] ="<param name=\"boxmessage\" value=\"Loading JUpload Applet ...\">";
	$jparams[] ="<param name=\"showThumbnails\" value=\"false\">";
	$jparams[] ="<param name=\"mainSplitpaneLocation\" value=\"300\">";

	if ($resize_method == "Jupload")
	{
		$jparams[] ="<param name=\"convertImagesToFormat\" value=\"jpg\">";
		$jparams[] ="<param name=\"resizeInterpolationAlgorithm\" value=\"none\">";
		$jparams[] ="<param name=\"resizeImageMaxWidth\" value=\"".$album_config['max_width']."\">";
		$jparams[] ="<param name=\"resizeImageMaxHeight\" value=\"".$album_config['max_height']."\">";
	}

	$jparams[] ="<param name=\"disableContextMenu\" value = \"true\">";
	$jparams[] ="<param name=\"hideShowAll\" value=\"true\">";
	$jparams[] ="<param name=\"customFileFilter\" value=\"true\">";
	$jparams[] ="<param name=\"customFileFilterDescription\" value=\"Jpeg Images\">";
	$jparams[] ="<param name=\"customFileFilterExtensions\" value=\"jpg,jpeg,png,gif\">";

	$jparams[] ="<param name=\"actionURL\" value=\"$action_url\">";
	$jparams[] ="<param name=\"completeURL\" value=\"$complete_url\">";
	$jparams[] ="<param name=\"debug\" value=\"true\">";
	$jparams[] ="<param name=\"debugLogfile\" value=\"c:/jupload.log\">";


	foreach ($jparams as $thisparam)
	{
		$template->assign_block_vars('jparams', array('PARAM' => $thisparam));
	}

	// Generate the page
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

}
elseif ($_GET['mode'] == 'upload')
{
	//message_die(GENERAL_ERROR,"This is where Jupload control stuff goes");


	foreach($_FILES as $tagname=>$object)
	{
		// get the temporary name (e.g. /tmp/php34634.tmp)
		$tempName = $object['tmp_name'];

		// get the real filename
		$realName = $object['name'];

		// Temporary storage location?
		$target = $jupload_temp_dir . $realName;

		// print something to the user
		echo "Processing file $realName...<br>\n";
		flush();

		// move the file to the target directory
		move_uploaded_file($tempName,$target);
		chmod($target,0777);
		$file_list[] = $target;
	}
	$_SESSION['file_list'] = $file_list;

	echo "Importing photos.  This may take a minute... <br>";
	flush();

}
elseif ($_GET['mode'] == "process")  // Files are on the server and we need to process each one.
{
	$message = '';  // Initialize output message

	$file_list = $_SESSION['file_list'];
	unset($_SESSION);
	session_destroy();

	// Set initial value to compare against timestamp.
	$old_time = 0;

	// $cat_id and $current_pics already defined. $current_pics++ at the end to keep quota check working...

	@set_time_limit(300); // 5 minutes of processing time allowed...

	foreach ($file_list as $this_file)
	{
		$pic_title = basename(strtolower($this_file), '.jpg');
		$pic_desc = '';
		$pic_username = (!$userdata['session_logged_in']) ? substr(str_replace("\'", "''", htmlspecialchars(trim($_POST['pic_username']))), 0, 32) : str_replace("'", "''", $userdata['username']);

		/*
		+----------------------------------------------------------
		| Upload Quota Check
		+----------------------------------------------------------
		*/
		if ($album_user_id == ALBUM_PUBLIC_GALLERY)
		{
			// ------------------------------------
			// Check This Category Quota
			// ------------------------------------
			if ($album_config['max_pics'] >= 0)
			{
				// $current_pics was set at "Get the current Category Info"
				if( $current_pics >= $album_config['max_pics'] )
				{
					$message.= $pic_title . ': ' . $lang['Album_reached_quota'] . '<br />';
					continue; // go to next file in file_list;
				}
			}

			// ------------------------------------
			// Check This User Limit Quota
			// ------------------------------------
			$check_user_limit = FALSE;

			if( ($userdata['user_level'] != ADMIN) && ($userdata['session_logged_in']) )
			{
				if ($album_user_access['moderator'])
				{
					if ($album_config['mod_pics_limit'] >= 0)
					{
						$check_user_limit = 'mod_pics_limit';
					}
				}
				else
				{
					if ($album_config['user_pics_limit'] >= 0)
					{
						$check_user_limit = 'user_pics_limit';
					}
				}
			}

			// Do the check here
			if ($check_user_limit != FALSE)
			{
				$sql = "SELECT COUNT(pic_id) AS count
						FROM ". ALBUM_TABLE ."
						WHERE pic_user_id = '". $userdata['user_id'] ."'
						AND pic_cat_id = '$cat_id'";

				if( !($result = $db->sql_query($sql)) )
				{
					$message.= $pic_title.": SQL problem: Could not get pic count!  __LINE__, __FILE__, $sql<br>";
					continue;  // move on to next file...
				}
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if( $row['count'] >= $album_config[$check_user_limit] )
				{
					$message.= $pic_title . ': ' . $lang['User_reached_pics_quota'] . '<br />';
					continue; // move to next file
				}
				unset($row);
			}
		}
		else // it's a personal gallery category
		{
			$sql = "SELECT COUNT(p.pic_id) AS count
					FROM ". ALBUM_TABLE ." AS p, ". ALBUM_CAT_TABLE ." AS c
					WHERE c.cat_user_id = '". $album_user_id ."'
					AND p.pic_cat_id = c.cat_id";

			if( !($result = $db->sql_query($sql)) )
			{
				$message .= $pic_title . ": SQL problem: Could not get pic count!  __LINE__, __FILE__, $sql<br />";
				continue;  // move on to next file...
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if( ($row['count'] >= $album_config['personal_gallery_limit']) && ($album_config['personal_gallery_limit'] >= 0) )
			{
				$messsage .= $pic_title . ': ' . $lang['Album_reached_quota'] . '<br />';
				continue;
			}
			unset($row);
		}

		/*
		+----------------------------------------------------------
		| Main work here...
		+----------------------------------------------------------
		*/


		// --------------------------------
		// Check posted info
		// --------------------------------
		$album_user_id = album_is_personal_gallery($cat_id);

		// --------------------------------
		// Check username for guest posting
		// --------------------------------

		if (!$userdata['session_logged_in'])
		{
			if ($pic_username != '')
			{
				$result = validate_username($pic_username);
				if ( $result['error'] )
				{
					$message .= $pic_title . ': ' . $result['error_msg'] . '<br />';
					continue;
				}
			}
		}


		// --------------------------------
		// Get File Upload Info
		// --------------------------------

		// mime_content_type not working for some reason... Fix this later, limit with Jupload for now...
		$filetype = 'image/jpg';
		$filesize = filesize($this_file);
		$filetmp = $this_file;

		if ($album_config['gd_version'] == 0)
		{
			$thumbtype = 'image/jpg';
			$thumbsize = filesize($this_file);
			$thumbtmp = $this_file;
		}

		// --------------------------------
		// Prepare variables
		// --------------------------------

		$pic_time = time();
		if ($pic_time <= $old_time)
		{
			$old_time++;
			$pic_time = $old_time;
		}
		else
		{
			$old_time = $pic_time;
		}

		$pic_user_id = $userdata['user_id'];
		$pic_user_ip = $userdata['session_ip'];

		// --------------------------------
		// Check file size
		// --------------------------------

		if ($resize_method == 'PHP')
		{
			if( ($filesize == 0) or ($filesize > $max_process_size) )
			{
				@unlink ($filetmp);
				$message .= $pic_title . ': ' . $lang['Bad_upload_file_size']. '<br />';
				continue;
			}

			$recompress = 0;
			if ($filesize > $album_config['max_file_size'])
			{
					$recompress = 1;
			}
		}
		elseif ($resize_method != 'Jupload')
		{
			if( ($filesize == 0) or ($filesize > $album_config['max_file_size']) )
			{
				@unlink ($filetmp);
				$message .= $pic_title . ': ' . $lang['Bad_upload_file_size']. '<br />';
				continue;
			}
		}



		if ($album_config['gd_version'] == 0)
		{
			if( ($thumbsize == 0) || ($thumbsize > $album_config['max_file_size']) )
			{
				$message .= $pic_title . ': ' . $lang['Bad_upload_file_size']. '<br />';
				continue;
			}
		}


		// --------------------------------
		// Check file type
		// --------------------------------

		switch ($filetype)
		{
			case 'image/jpeg':
			case 'image/jpg':
			case 'image/pjpeg':
				if ($album_config['jpg_allowed'] == 0)
				{
					$message .= $pic_title . ': ' . $lang['Not_allowed_file_type']. '<br />';
					continue 2;
				}
				$pic_filetype = '.jpg';
				break;

			case 'image/png':
			case 'image/x-png':
				if ($album_config['png_allowed'] == 0)
				{
					$message .= $pic_title . ': ' . $lang['Not_allowed_file_type']. '<br />';
					continue 2;
				}
				$pic_filetype = '.png';
				break;

			case 'image/gif':
				if ($album_config['gif_allowed'] == 0)
				{
					$message .= $pic_title . ': ' . $lang['Not_allowed_file_type']. '<br />';
					continue 2;
				}
				$pic_filetype = '.gif';
				break;
			default:
				$message .= $pic_title . ': ' . $lang['Not_allowed_file_type']. '<br />';
				continue 2 ;
		}

		if ($album_config['gd_version'] == 0)
		{
			if ($filetype != $thumbtype)
			{
				$message .= $pic_title . ': ' . $lang['Filetype_and_thumbtype_do_not_match']. '<br />';
				continue;
			}
		}


		// --------------------------------
		// Generate filename
		// --------------------------------

		// Do we want to store pictures in directories by username?
		if ($use_pic_dirs)
		{
			$pic_dir = str_replace(' ', '_', mb_strtolower($userdata['username'])) . '/';

			if (!is_dir(ALBUM_UPLOAD_PATH . $pic_dir))
			{
				mkdir(ALBUM_UPLOAD_PATH . $pic_dir);
				chmod(ALBUM_UPLOAD_PATH . $pic_dir, 0777);
				copy (ALBUM_UPLOAD_PATH . 'index.html', ALBUM_UPLOAD_PATH . $pic_dir . 'index.html');
			}
			if (!is_dir(ALBUM_CACHE_PATH . $pic_dir))
			{
				mkdir(ALBUM_CACHE_PATH . $pic_dir);
				chmod(ALBUM_CACHE_PATH . $pic_dir, 0777);
				copy (ALBUM_CACHE_PATH . 'index.html', ALBUM_CACHE_PATH . $pic_dir . 'index.html');
			}
		}
		else
		{
			$pic_dir = '';
		}


		// Work the name modified by OryNider
		$UploadFile = basename(strtolower($this_file),$pic_filetype);
		$UploadFileName = substr(mx_album_uploadfilename($UploadFile), 1);
		$UploadFileType = '.' . mx_album_uploadfiletype($UploadFile);
		switch ($UploadFileType)
		{
			case '.jpeg':
			case '.jpg':
			case '.pjpeg':
				$real_imagetype = '.jpg';
				break;

			case '.gif':
				$real_imagetype = '.gif';
				break;

			case '.png':
				$real_imagetype = '.png';
				break;

			default:
				$real_imagetype = $pic_filetype;
		}

		do
		{
			$pic_filename = $UploadFileName . '_' . substr(md5(uniqid(rand())),10,5) . $real_imagetype;
		}
		while (file_exists(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename));


		if ($album_config['gd_version'] == 0)
		{
			$pic_thumbnail = $pic_filename;
		}


		// --------------------------------
		// Move this file to upload directory
		// --------------------------------

		copy($filetmp, ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);
		@chmod(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename, 0777);
		@unlink($filetmp);

		if ($album_config['gd_version'] == 0)
		{
			$move_file($thumbtmp, ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail);
			@chmod(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail, 0777);
		}


		// --------------------------------
		// Well, it's an image. Check its image size
		// --------------------------------

		$pic_size = getimagesize(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);

		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];


		// Resize Mod
		if ( $resize_method == 'PHP' && ( ($pic_width > $album_config['max_width']) || ($pic_height > $album_config['max_height']) || ($recompress == 1)) )
		{

			if ($album_config['gd_version'] == 0)
			{
				@unlink(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);
				@unlink(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail);
				$message .= $pic_title . ': ' . $lang['Upload_image_size_too_big'] . '<br />';
				continue;
			}
			$gd_errored = false;

			switch ($pic_filetype)
			{
				case '.jpg':
					$read_function = 'imagecreatefromjpeg';
					break;
				case '.png':
				$read_function = 'imagecreatefrompng';
				break;
			}

			$src = @$read_function(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);

			if (!$src)
			{
				$gd_errored = true;
				$pic_thumbnail = '';
			}

			$new_width = $pic_width;
			$new_height = $pic_height;

			if ($new_width > $album_config['max_width'])
			{
				$new_width = $album_config['max_width'];
				$new_height = $pic_height / ($pic_width/ $album_config['max_width']);
			}
			if ($new_height > $album_config['max_height'])
			{
				$new_width = $new_width / ($new_height/$album_config['max_height']);
				$new_height = $album_config['max_height'];
			}

			$new_pic = ($album_config['gd_version'] == 1) ? @imagecreate($new_width, $new_height) : @imagecreatetruecolor($new_width, $new_height);

			$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			@$resize_function($new_pic, $src, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);

			if (!$gd_errored)
			{
				// overwrite old image
				@unlink(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);

				switch ($pic_filetype)
				{
					case '.jpg':
						@imagejpeg($new_pic, ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename, 85);
						break;
					case '.png':
						@imagepng($new_pic, ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);
						break;
				}

				@chmod(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail, 0777);
				$pic_width = $new_width;
				$pic_height = $new_height;
				$message .= $pic_title . ': Image has been resized...<br />';
				// no continue here.  Just informational.

			} // End IF !$gd_errored
			else
			{
				@unlink(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);

				if ($album_config['gd_version'] == 0)
				{
					@unlink(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail);
				}

				$message .= $pic_title . ': ' . $lang['Upload_image_size_too_big']. '<br />';
				continue;
			}
		}
		elseif ($resize_method != 'Jupload')
		{
		   if ( ($pic_width > $album_config['max_width']) or ($pic_height > $album_config['max_height']) )
			{
				@unlink(ALBUM_UPLOAD_PATH . $pic_filename);

				if ($album_config['gd_version'] == 0)
				{
					@unlink(ALBUM_CACHE_PATH . $pic_thumbnail);
				}

				$message .= $pic_title . ': ' . $lang['Upload_image_size_too_big']. '<br />';
				continue;
			}
		}
		// End Resize Mod

		if ($album_config['gd_version'] == 0)
		{
			$thumb_size = getimagesize(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail);

			$thumb_width = $thumb_size[0];
			$thumb_height = $thumb_size[1];

			if ( ($thumb_width > $album_config['thumbnail_size']) or ($thumb_height > $album_config['thumbnail_size']) )
			{
				@unlink(ALBUM_UPLOAD_PATH . $pic_dir . $pic_filename);

				@unlink(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail);

				$message .= $pic_title . ': ' . $lang['Upload_thumbnail_size_too_big']. '<br />';
				continue;
			}
		}


		// --------------------------------
		// This image is okay, we can cache its thumbnail now
		// --------------------------------

		if( ($album_config['thumbnail_cache'] == 1) && ($pic_filetype != '.gif') && ($album_config['gd_version'] > 0) )
		{
			$gd_errored = false;

			switch ($pic_filetype)
			{
				case '.jpg':
					$read_function = 'imagecreatefromjpeg';
					break;
				case '.png':
					$read_function = 'imagecreatefrompng';
					break;
			}

			$src = @$read_function(ALBUM_UPLOAD_PATH  . $pic_dir . $pic_filename);

			if (!$src)
			{
				$gd_errored = true;
				$pic_thumbnail = '';
			}
			else if( ($pic_width > $album_config['thumbnail_size']) || ($pic_height > $album_config['thumbnail_size']) )
			{
				// Resize it
				if ($pic_width > $pic_height)
				{
					$thumbnail_width = $album_config['thumbnail_size'];
					$thumbnail_height = $album_config['thumbnail_size'] * ($pic_height/$pic_width);
				}
				else
				{
					$thumbnail_height = $album_config['thumbnail_size'];
					$thumbnail_width = $album_config['thumbnail_size'] * ($pic_width/$pic_height);
				}

				$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
				$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';
				@$resize_function($thumbnail, $src, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
			}
			else
			{
				$thumbnail = $src;
			}

			if (!$gd_errored)
			{
				$pic_thumbnail = $pic_filename;

				// Write to disk
				switch ($pic_filetype)
				{
					case '.jpg':
						@imagejpeg($thumbnail, ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail, $album_config['thumbnail_quality']);
						break;
					case '.png':
						@imagepng($thumbnail, ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail);
						break;
				}
				@chmod(ALBUM_CACHE_PATH . $pic_dir . $pic_thumbnail, 0777);

			} // End IF !$gd_errored

		} // End Thumbnail Cache
		elseif ($album_config['gd_version'] > 0)
		{
			$pic_thumbnail = '';
		}

		// --------------------------------
		// Check Pic Approval
		// --------------------------------

		$pic_approval = ($thiscat['cat_approval'] == 0) ? 1 : 0;


		// --------------------------------
		// Insert into DB
		// --------------------------------

		$sql = "INSERT INTO ". ALBUM_TABLE ." (pic_filename, pic_thumbnail, pic_title, pic_desc, pic_user_id, pic_user_ip, pic_username, pic_time, pic_cat_id, pic_approval)
				VALUES ('$pic_dir$pic_filename', '$pic_dir$pic_thumbnail', '$pic_title', '$pic_desc', '$pic_user_id', '$pic_user_ip', '$pic_username', '$pic_time', '$cat_id', '$pic_approval')";
		if( !$result = $db->sql_query($sql) )
		{
			$message .= $pic_title . ': ' . "'Could not insert new entry', '', __LINE__, __FILE__, $sql<br />";
			continue;
		}


		// --------------------------------
		// Complete... now send a message to user
		// --------------------------------

		if ($thiscat['cat_approval'] == 0)
		{
			$message .= $pic_title . ': ' . $lang['Album_upload_successful']. '<br />';
		}
		else
		{
			$message .= $pic_title . ': ' . $lang['Album_upload_need_approval']. '<br />';
		}

		{
			if (album_is_debug_enabled() == false)
			{
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">'
					)
				);
			}
		}

		$current_pics ++;

	} // end foreach file...

	if ($album_user_id == ALBUM_PUBLIC_GALLERY)
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">', '</a>');
	}
	else
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)) . '">', '</a>');
	}

	$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid(album_append_uid('album.' . $phpEx)) . '">', '</a>');

	synchronize_cat_pics_counter($cat_id);
	message_die(GENERAL_MESSAGE, $message);
}

?>