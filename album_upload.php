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
* IdleVoid (idlevoid@slater.dk)
* Volodymyr (CLowN) Skoryk (blaatimmy72@yahoo.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

if ($album_config['switch_nuffload'] == 1)
{
	$template->assign_block_vars('switch_nuffload_enabled', array());
	include(IP_ROOT_PATH . 'album_nuffload.' . PHP_EXT);
}
else
{
	$template->assign_block_vars('switch_nuffload_disabled', array());
}

/*
+----------------------------------------------------------
| Common Check
+----------------------------------------------------------
*/

// ------------------------------------
// Check the request
// for this Upload script, we prefer POST to GET
// ------------------------------------
$album_user_id = request_var('user_id', 0);
if (empty($album_user_id))
{
	// if no user_id was supplied then it's a public category
	$album_user_id = ALBUM_PUBLIC_GALLERY;
}

$cat_id = request_var('cat_id', 0);
if ($cat_id <= 0)
{
	message_die(GENERAL_ERROR, 'No categories specified');
}

// check if it's a 'fake' category id, which look like this -<user_id> (a minus sign followed by the userid)
if(isset($_POST['pic_title'])) // is it submitted?
{
	if (!album_validate_jumpbox_selection($cat_id))
	{
		message_die(GENERAL_ERROR, $lang['No_valid_category_selected']);
	}

	if ($cat_id < 0)
	{
		message_die(GENERAL_ERROR, $lang['No_valid_category_selected']);
		/*
		$album_user_id = abs($cat_id); // convert the negative 'cat_id' into to a user id
		if ($album_user_id > 0 && album_check_user_exists($album_user_id))
		{
			// NOTE : if we want to create personal galleries the upload setting ($album_config['personal_gallery']) as set in the ACP
			//        we should change the next line so it looks like this :
			//
			//album_create_personal_gallery($album_upload_user_id, $album_config['personal_gallery_view'], $album_config['personal_gallery']);
			//
			// this will how ever make it possible for all users to upload to other persons personal galleries as default.
			// So the best solution would be this which sets the upload permission to private which in this case means a moderator or the
			// owner of the gallery and of cause the admin :)
			album_create_personal_gallery($album_user_id, $album_config['personal_gallery_view'], ALBUM_PRIVATE);
			$cat_id = album_get_personal_root_id($album_user_id);
		}
		*/
	}
}

// ------------------------------------
// Get the current Category Info
// ------------------------------------
$sql = "SELECT c.*, COUNT(p.pic_id) AS count, IF (cat_user_id > 0, 1, 0) AS personal
		FROM " . ALBUM_CAT_TABLE . " AS c
			LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id
		WHERE c.cat_id = '$cat_id'
		GROUP BY c.cat_id
		LIMIT 1";
$result = $db->sql_query($sql);
$thiscat = $db->sql_fetchrow($result);
$db->sql_freeresult($result);
// check if its a personal gallery request and if the gallery exists (checking $thiscat)
if (empty($thiscat) && ($album_user_id != ALBUM_PUBLIC_GALLERY))
{
	//check if user exsts
	$user_name = album_get_user_name($album_user_id);
	if (!empty($user_name))
	{
		$thiscat = init_personal_gallery_cat($album_user_id);
	}
	else
	{
		// generate mesage saying that the user specified doesn't exists
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_ERROR, 'NO_USER');
	}
}

if (empty($thiscat))
{
	message_die(GENERAL_ERROR, $lang['Category_not_exist']);
}
// ------------------------------------
// now get the category information
// ------------------------------------
$cat_id = $thiscat['cat_id'];
$current_pics = $thiscat['count'];

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW_AND_UPLOAD, $thiscat);

if ($album_user_access['upload'] == 0)
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(album_append_uid(CMS_PAGE_LOGIN . '?redirect=album_upload.' . PHP_EXT . '?cat_id=' . $cat_id), true));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorized']);
	}
}

/*
+----------------------------------------------------------
| Upload Quota Check
+----------------------------------------------------------
*/
// if we are in a public category
if ($album_user_id == ALBUM_PUBLIC_GALLERY)
{
	// ------------------------------------
	// Check This Category Quota
	// ------------------------------------
	if ($album_config['max_pics'] >= 0)
	{
		// $current_pics was set at "Get the current Category Info"
		if($current_pics >= $album_config['max_pics'])
		{
			message_die(GENERAL_MESSAGE, $lang['Album_reached_quota']);
		}
	}

	// ------------------------------------
	// Check This User Limit Quota
	// ------------------------------------
	$check_user_limit = false;

	if(($user->data['user_level'] != ADMIN) && ($user->data['session_logged_in']))
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
	if ($check_user_limit != false)
	{
		$sql = "SELECT COUNT(pic_id) AS count
				FROM " . ALBUM_TABLE . "
				WHERE pic_user_id = '" . $user->data['user_id'] . "'
					AND pic_cat_id = '$cat_id'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if($row['count'] >= $album_config[$check_user_limit])
		{
			message_die(GENERAL_MESSAGE, $lang['User_reached_pics_quota']);
		}

		unset($row);
	}
}
// it's a personal gallery category
else
{
	$sql = "SELECT COUNT(p.pic_id) AS count
			FROM " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . " AS c
			WHERE c.cat_user_id = '" . $album_user_id . "'
				AND p.pic_cat_id = c.cat_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	if(($row['count'] >= $album_config['personal_gallery_limit']) && ($album_config['personal_gallery_limit'] >= 0))
	{
		message_die(GENERAL_MESSAGE, $lang['Album_reached_quota']);
	}

	unset($row);
}

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if(!isset($_POST['pic_title'])) // is it not submitted?
{
	// --------------------------------
	// Build categories select
	// --------------------------------
	album_read_tree($user->data['user_id'], ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW_AND_UPLOAD);
	if($user->data['session_logged_in'])
	{
		// build fake list of personal galleries (these will get created when needed later automatically
		$userinfo = album_get_nonexisting_personal_gallery_info();

		//for($idx = 0; $idx < sizeof($userinfo); $idx++)
		//Replaced to fix slowdown
		$count = sizeof($userinfo);
		for($idx = 0; $idx < count; $idx++)
		//End Replace
		{
			// Is user allowed to create this personal gallery?
			// NOTE : that it isn't necessary to create the $personal_gallery variable first,
			//        it will be generated inside the album_permissions function if needed
			//        but here it's done to make the code easier to read
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

	// Start output of page
	$nav_server_url = create_server_url();
	$album_nav_cat_desc = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '" class="nav-current">' . $thiscat['cat_title'] . '</a>';
	$breadcrumbs['address'] = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;

	// make sure that if we have disabled dynamic generation and pre-generated upload fields
	// we should then at least make sure we create at least on upload field.
	if (($album_config['dynamic_fields'] == 0) && ($album_config['pregenerate_fields'] == 0))
	{
		$album_config['max_files_to_upload'] = 1;
	}

	$html_status = ($config['allow_html']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
	$bbcode_status = ($config['allow_bbcode']) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
	$bbcode_status = sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>');
	$smilies_status = ($config['allow_smilies']) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
	$formatting_status = '<br />' . $html_status . '<br />' . $bbcode_status . '<br />' . $smilies_status . '<br />';

	$template->assign_vars(array(
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'CAT_TITLE' => $thiscat['cat_title'],

		'L_UPLOAD_PIC' => $lang['Upload_Pic'],

		'L_USERNAME' => $lang['Username'],
		'L_PIC_TITLE' => $lang['Pic_Image'],

		'L_PIC_DESC' => $lang['Pic_Desc'],
		//'L_PLAIN_TEXT_ONLY' => $lang['Plain_text_only'],
		'L_PLAIN_TEXT_ONLY' => $formatting_status,
		'L_MAX_LENGTH' => $lang['Max_length'],
		'S_PIC_DESC_MAX_LENGTH' => $album_config['desc_length'],

		'L_UPLOAD_PIC_FROM_MACHINE' => $lang['Upload_pic_from_machine'],
		'L_UPLOAD_TO_CATEGORY' => $lang['Upload_to_Category'],
		'L_PIC_COMMENT_WATCH' => $lang['Pic_comment_watch_checkbox'],

		'SELECT_CAT' => $select_cat,

		'L_ROTATION' => $lang['Rotation'],

		'L_MAX_FILESIZE' => $lang['Max_file_size'],
		'S_MAX_FILESIZE' => $album_config['max_file_size'],

		'L_MAX_WIDTH' => $lang['Max_width'],
		'L_MAX_HEIGHT' => $lang['Max_height'],

		'S_MAX_WIDTH' => $album_config['max_width'],
		'S_MAX_HEIGHT' => $album_config['max_height'],

		'L_ALLOWED_JPG' => $lang['JPG_allowed'],
		'L_ALLOWED_PNG' => $lang['PNG_allowed'],
		'L_ALLOWED_GIF' => $lang['GIF_allowed'],

		'S_JPG' => ($album_config['jpg_allowed'] == 1) ? $lang['Yes'] : $lang['No'],
		'S_PNG' => ($album_config['png_allowed'] == 1) ? $lang['Yes'] : $lang['No'],
		'S_GIF' => ($album_config['gif_allowed'] == 1) ? $lang['Yes'] : $lang['No'],

		'S_MAX_FILE_UPLOADS' => max(1,$album_config['max_files_to_upload']),
		'L_ADD_FILE' => $lang['Add_File'],
		'S_MAX_PREGEN_FILE_UPLOADS' => max(1,min($album_config['max_pregenerated_fields'], $album_config['max_files_to_upload'])),
		'DYNAMIC_GENERATION_STATUS' => ($album_config['dynamic_fields'] == 1 && $album_config['max_pregenerated_fields'] != $album_config['max_files_to_upload']) ? 'visible' : 'hidden',

		'L_UPLOAD_NO_TITLE' => $lang['Upload_no_title'],
		'L_UPLOAD_NO_FILE' => $lang['Upload_no_file'],
		'L_DESC_TOO_LONG' => $lang['Desc_too_long'],

		'S_ALBUM_JUMPBOX_PUBLIC_GALLERY' => intval(ALBUM_JUMPBOX_PUBLIC_GALLERY),
		'S_ALBUM_JUMPBOX_USERS_GALLERY' => intval(ALBUM_JUMPBOX_USERS_GALLERY),
		'S_ALBUM_JUMPBOX_SEPARATOR' => intval(ALBUM_JUMPBOX_SEPARATOR),
		'S_ALBUM_ROOT_CATEGORY' => intval(ALBUM_ROOT_CATEGORY),
		'L_NO_VALID_CAT_SELECTED' => $lang['No_valid_category_selected'],

		// Manual Thumbnail
		'L_UPLOAD_THUMBNAIL' => $lang['Upload_thumbnail'],
		'L_UPLOAD_THUMBNAIL_EXPLAIN' => $lang['Upload_thumbnail_explain'],
		'L_THUMBNAIL_SIZE' => $lang['Thumbnail_size'],
		'S_THUMBNAIL_SIZE' => $album_config['thumbnail_size'],

		'L_RESET' => $lang['Reset'],
		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid(album_append_uid('album_upload.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'S_ON_SUBMIT' => 'return(checkAlbumForm())',
		)
	);

	if ($album_config['pregenerate_fields'] == 1)
	{
		$template->assign_block_vars('pre_generate', array());
	}

	if ($album_config['gd_version'] == 0)
	{
		$template->assign_block_vars('switch_manual_thumbnail', array());
		if ($album_config['switch_nuffload'] == 1)
		{
			$template->assign_block_vars('switch_manual_thumbnail.switch_nuffload_enabled', array());
		}
		else
		{
			$template->assign_block_vars('switch_manual_thumbnail.switch_nuffload_disabled', array());
		}
	}

	if (($album_config['gd_version'] > 0) && ($album_config['switch_nuffload'] == 0))
	{
		$template->assign_block_vars('switch_nuffload_disabled.switch_rotation', array());
	}

	if ($album_config['switch_nuffload'] == 1)
	{
		if ($multiple_uploads == 1)
		{
			$template->assign_block_vars('switch_nuffload_enabled.switch_multiple_uploads', array());
		}
		if ($show_progress_bar == 1)
		{
			$template->assign_block_vars('switch_nuffload_enabled.switch_show_progress_bar', array());
		}
		$template->assign_vars(array(
			'S_ALBUM_ACTION' => $uploader,
			'S_ON_SUBMIT' => 'return(postIt())',
			'PSID' => $psid,
			'ADD_FIELD' => $lang['add_field'],
			'REMOVE_FIELD' => $lang['remove_field'],
			'S_ZIP' => ($album_config['zip_uploads'] == 1) ? $lang['Yes'] : $lang['No'],
			'L_ALLOWED_ZIP' => $lang['ZIP_allowed'],
			'MAX_UPLOADS' => $album_config['max_uploads'],
			)
		);
	}

	full_page_generation('album_upload_body.tpl', $lang['Album'], '', '');
}
else
{
	// --------------------------------
	// Check posted info
	// --------------------------------
	$pic_title = request_var('pic_title', '', true);
	$pic_desc = request_var('pic_desc', '', true);
	$pic_desc = substr($pic_desc, 0, $album_config['desc_length']);
	$pic_username = request_var('pic_username', '', true);
	$pic_username = substr($pic_username, 0, 32);
	$pic_username = (!$user->data['session_logged_in']) ? $pic_username : $user->data['username'];

	if(!isset($_FILES['pic_file']))
	{
		message_die(GENERAL_ERROR, $lang['Bad_upload']);
	}

	// save the user entered picture title
	$org_pic_title = $pic_title;

	// ----------------------------------------------------------------
	// count the numbers of _VALID_ uploaded picture
	// ----------------------------------------------------------------
	$pic_count = 0;
	$thumb_count = 0;
	$upload_files = $_FILES['pic_file'];
	$thumbnail_upload_files = $_FILES['pic_thumbnail'];
	for($index = 0; $index < sizeof($upload_files['name']); $index++)
	{
		if (was_file_uploaded($upload_files, $index) == true)
		{
			$pic_count++;
		}

		// ----------------------------------------------------------------
		// for manual thumbnail upload, check we got the same number of
		// uploaded pictures as we got thumbnails uploaded, else error
		// ----------------------------------------------------------------
		if ($album_config['gd_version'] == 0)
		{
			if (was_file_uploaded($thumbnail_upload_files, $index) == true)
			{
				$thumb_count++;
			}

			if ($pic_count != $thumb_count)
			{
				message_die(GENERAL_MESSAGE, $lang['File_thumbnail_count_mismatch']);
			}
		}
	}

	if ($pic_count == 0)
	{
		message_die(GENERAL_MESSAGE, $lang['No_pictures_selected_for_upload']);
	}

	// check if we are uploading ONLY one picture, if so, then check for picture title
	/*
	if ((sizeof($_FILES['pic_file']['name']) == 1 || $pic_count == 1) && empty($pic_title))
	{
		message_die(GENERAL_ERROR, $lang['Missed_pic_title']);
	}
	*/

	$album_user_id = album_is_personal_gallery($cat_id);

	// --------------------------------
	// Check username for guest posting
	// --------------------------------

	if (!$user->data['session_logged_in'])
	{
		if ($pic_username != '')
		{
			$result = validate_username($pic_username);
			if ($result['error'])
			{
				message_die(GENERAL_MESSAGE, $result['error_msg']);
			}
		}
	}


	/*
	//Structure of the $_FILES ($_FILES) variable
	//This information it left here for other mod authors to use

	echo "<pre>\$_FILES = ";
	print_r($_FILES);
	echo "</pre><br />";

	$_FILES = Array
	(
		[pic_file] => Array
			(
				[name] => Array
					(
						[0] => pic1.jpg
						[1] => pic.jpg
					)
				[type] => Array
					(
						[0] => image/jpeg
						[1] => image/jpeg
					)
				[tmp_name] => Array
					(
						[0] => C:\WINDOWS\TEMP\php301.tmp
						[1] => C:\WINDOWS\TEMP\php302.tmp
					)
				[error] => Array
					(
						[0] => 0
						[1] => 0
					)
				[size] => Array
					(
						[0] => 5457
						[1] => 46612
					)
			)
	)
	*/

	// ----------------------------------------------------------------
	// this array will hold a list of non fatal error messages generated
	// by the Multiple File Upload mod
	// ----------------------------------------------------------------
	$upload_errors = array();

	// ----------------------------------------------------------------
	// get the max execution time, this is needed incase the uploaded
	// pictures take more processing time then we are allowed to.
	// ----------------------------------------------------------------
	@set_time_limit(360);
	$time_end = 0;
	$time_end = getmicrotime();
	$time_start = getmicrotime();
	$time = $time_end - $time_start;
	$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

	if (@$ini_val(' max_execution_time') != '')
	{
		$timeout = (@$ini_val(' max_execution_time')-$time);
	}
	else
	{
		$timeout = 29 - $time;
	}
	for($index = 0; $index < sizeof($upload_files['name']);$index++)
	{
		// ----------------------------------------------------------------
		// check the file exceeds the upload_max_filesize directive in php.ini
		// then skip to the next file
		// ----------------------------------------------------------------
		if (file_uploaded_exceeds_max_size($upload_files, $index))
		{
			$upload_errors[] = sprintf($lang['Picture_exceeded_maximum_size_INI'], $upload_files['name'][$index]);
			continue;
		}

		// ----------------------------------------------------------------
		// check if we got a file at all. if we haven't gotten a file then
		// try the next file in the array
		// ----------------------------------------------------------------
		if (was_file_uploaded($upload_files,$index) == false)
		{
			continue;
		}

		// we are doing manual thumbnail uploading, then do some checking
		if ($album_config['gd_version'] == 0)
		{
			if (file_uploaded_exceeds_max_size($thumbnail_upload_files, $index))
			{
				$upload_errors[] = sprintf($lang['Thumbnail_exceeded_maximum_size_INI'], $thumbnail_upload_files['name'][$index]);
				continue;
			}
			// ----------------------------------------------------------------
			// check that both the picture and thumbnail got uploaded without
			// errors, and the order of the picture and thumbnail a 'almost' the
			// same. I'm not sure that this few lines of code will cover it 100%
			// ----------------------------------------------------------------

			// we are missing the upload file together with the picture, that a NO NO
			if (was_file_uploaded($thumbnail_upload_files,$index) == false && was_file_uploaded($upload_files,$index) == true)
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['No_thumbnail_for_picture_found'], $upload_files['name'][$index]));
			}
			elseif (was_file_uploaded($thumbnail_upload_files,$index) == true && was_file_uploaded($upload_files,$index) == false)
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['No_picture_for_thumbnail_found'], $thumbnail_upload_files['name'][$index]));
			}
			else
			{
				if (@phpversion() >= '4.2.0')
				{
					if ($thumbnail_upload_files['error'][$index] != $upload_files['error'][$index])
					{
						message_die(GENERAL_MESSAGE, sprintf($lang['Unknown_file_and_thumbnail_error_mismatch'], $upload_files['name'][$index], $thumbnail_upload_files['name'][$index]));
					}
				}
			}
		}

		// ----------------------------------------------------------------
		// check the time left before timeout, for each iteration/loop
		// NOTE : Original code is copyrighted by Luciano
		// ----------------------------------------------------------------
		$time_end = 0;
		$time_end = getmicrotime();
		$time = $time_end - $time_start;
		$time_start = $time_end;
		$timeout = $timeout - $time;

		if ($timeout < 2)
		{
			$upload_error_msg = "";
			for($inner_index = $index; $inner_index < sizeof($upload_files['name']); $inner_index++)
			{
				if ($album_config['gd_version'] == 0)
				{
					if (was_file_uploaded($upload_files,$inner_index) || was_file_uploaded($thumbnail_upload_files,$inner_index))
					{
						$upload_error_msg = sprintf($lang['Skipping_uploaded_picture_and_thumbnail_file'], $upload_files['name'][$inner_index], $thumbnail_upload_files['name'][$inner_index]);
					}
				}
				else
				{
					if (was_file_uploaded($upload_files,$inner_index) == true)
					{
						$upload_error_msg = sprintf($lang['Skipping_uploaded_picture_file'], $upload_files['name'][$inner_index]);
					}
				}

				if (!empty($upload_error_msg))
				{
					if ($inner_index == $index)
					{
						$upload_errors[] = $lang['Execution_time_exceeded_skipping'] . $upload_error_msg;
					}
					else
					{
						$upload_errors[] = $upload_error_msg;
					}
					$upload_error_msg = "";
				}
			}
			break;
		}

		// --------------------------------
		// Get File Upload Info
		// --------------------------------

		$filetype = $upload_files['type'][$index];
		$filesize = $upload_files['size'][$index];
		$filetmp = $upload_files['tmp_name'][$index];

		if ($album_config['gd_version'] == 0)
		{
			$thumbtype = $thumbnail_upload_files['type'][$index];
			$thumbsize = $thumbnail_upload_files['size'][$index];
			$thumbtmp = $thumbnail_upload_files['tmp_name'][$index];
		}

		// --------------------------------
		// Prepare variables
		// --------------------------------

		// this should ensure that the images don't all have the same timestamp
		if ($pic_time == '')
		{
			$pic_time = time() + 1;
		}
		else
		{
			$pic_time += 2;
		}
		$pic_user_id = $user->data['user_id'];
		$pic_user_ip = $user->data['session_ip'];


		// --------------------------------
		// Check file size
		// --------------------------------

		$recompress = 0;
		if (($album_config['dynamic_pic_resampling'] == 1) && (intval($album_config['max_file_size_resampling']) > intval($album_config['max_file_size'])) && ($album_config['switch_nuffload'] == 0))
		{
			// Resize on upload
			if(($filesize == 0) || ($filesize > $album_config['max_file_size_resampling']))
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['Bad_upload_file_size'], $upload_files['name'][$index]));
			}
			if ($filesize > $album_config['max_file_size'])
			{
				$recompress = 1;
			}
		}
		else
		{
			if(($filesize == 0) || ($filesize > $album_config['max_file_size']))
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['Bad_upload_file_size'], $upload_files['name'][$index]));
			}
		}

		if ($album_config['gd_version'] == 0)
		{
			if(($thumbsize == 0) || ($thumbsize > $album_config['max_file_size']))
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['Bad_upload_file_size'], $thumbnail_upload_files['name'][$index]));
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
					message_die(GENERAL_ERROR, $lang['Not_allowed_file_type']);
				}
				$pic_filetype = 'jpg';
				break;

			case 'image/png':
			case 'image/x-png':
				if ($album_config['png_allowed'] == 0)
				{
					message_die(GENERAL_ERROR, $lang['Not_allowed_file_type']);
				}
				$pic_filetype = 'png';
				break;

			case 'image/gif':
				if ($album_config['gif_allowed'] == 0)
				{
					message_die(GENERAL_ERROR, $lang['Not_allowed_file_type']);
				}
				$pic_filetype = 'gif';
				break;
			default:
				message_die(GENERAL_ERROR, $lang['Not_allowed_file_type']);
		}

		if ($album_config['gd_version'] == 0)
		{
			if ($filetype != $thumbtype)
			{
				message_die(GENERAL_ERROR, $lang['Filetype_and_thumbtype_do_not_match']);
			}
		}

		if ($pic_count > 1)
		{
			$pic_title = generate_picture_title($upload_files['name'][$index], $org_pic_title, ('.' . $pic_filetype));
		}
		elseif ($pic_count == 1)
		{
			$pic_title = generate_single_pic_title($upload_files['name'][$index], $org_pic_title, ('.' . $pic_filetype));
		}

		// --------------------------------
		// Generate filename
		// --------------------------------

		srand((double)microtime() * 1000000); // for older than version 4.2.0 of PHP

		$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
		$pic_extra_path = '';
		$upload_path = $pic_base_path . $pic_extra_path;
		if (USERS_SUBFOLDERS_ALBUM == true)
		{
			$pic_extra_path = $user->data['user_id'] . '/';
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
					$pic_extra_path = '';
					$upload_path = $pic_base_path . $pic_extra_path;
				}
			}
		}

		do
		{
			$pic_filename = md5(uniqid(rand())) . '.' . $pic_filetype;
		}
		while(file_exists($upload_path . $pic_filename));
		$pic_fullpath = $upload_path . $pic_filename;

		if ($album_config['gd_version'] == 0)
		{
			$pic_thumbnail = $pic_filename;
		}
		$pic_thumbnail_fullpath = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_thumbnail;


		// --------------------------------
		// Move this file to upload directory
		// --------------------------------

		$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

		if (@$ini_val('open_basedir') != '')
		{
			if (@phpversion() < '4.0.3')
			{
				message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
			}

			if ($album_config['switch_nuffload'] == 1)
			{
				$move_file = 'rename';
			}
			else
			{
				$move_file = 'move_uploaded_file';
			}
		}
		else
		{
			$move_file = 'copy';
		}

		$move_file($filetmp, $pic_fullpath);
		if ($album_config['switch_nuffload'] == 1)
		{
			@unlink($filetmp);
		}

		@chmod($pic_fullpath, 0777);

		if ($album_config['gd_version'] == 0)
		{
			$move_file($thumbtmp, $pic_thumbnail_fullpath);
			@unlink($thumbtmp);
			@chmod($pic_thumbnail_fullpath, 0777);
		}

		// --------------------------------
		// Well, it's an image. Check its image size
		// --------------------------------

		$pic_size = getimagesize($pic_fullpath);

		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];

		/*
		if (($pic_width > $album_config['max_width']) || ($pic_height > $album_config['max_height']))
		{
			@unlink($pic_fullpath);

			if ($album_config['gd_version'] == 0)
			{
				@unlink($pic_thumbnail_fullpath);
			}

			message_die(GENERAL_ERROR, $lang['Upload_image_size_too_big']);
		}
		*/

		// Resize on upload
		if (((($pic_width > $album_config['max_width']) || ($pic_height > $album_config['max_height'])) || ($recompress == 1)) && ($pic_filetype != 'gif'))
		{
			if ($album_config['gd_version'] == 0)
			{
				@unlink($pic_fullpath);
				@unlink($pic_thumbnail_fullpath);
				message_die(GENERAL_ERROR, $lang['Upload_image_size_too_big'] . " Error code: 001");
			}
			$gd_errored = false;

			switch ($pic_filetype)
			{
				case 'jpg':
					$read_function = 'imagecreatefromjpeg';
					break;
				case 'png':
					$read_function = 'imagecreatefrompng';
					break;
			}

			$src = @$read_function($pic_fullpath);

			if (!$src)
			{
				$gd_errored = true;
				$pic_thumbnail = '';
			}

			if (($pic_width > $album_config['max_width']) || ($pic_height > $album_config['max_height']))
			{
				if ($pic_width > $pic_height)
				{
					$new_width = $album_config['max_width'];
					$new_height = $album_config['max_width'] * ($pic_height / $pic_width);
				}
				else
				{
					$new_height = $album_config['max_height'];
					$new_width = $album_config['max_height'] * ($pic_width / $pic_height);
				}
			}
			else
			{
				$new_width = $pic_width;
				$new_height = $pic_height;
			}
			$new_pic = ($album_config['gd_version'] == 1) ? @imagecreate($new_width, $new_height) : @imagecreatetruecolor($new_width, $new_height);

			$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			@$resize_function($new_pic, $src, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);

			if (!$gd_errored)
			{
				// print $recompress ."<br />".$pic_filename; exit;
				// overwrite old image
				@unlink($pic_fullpath);

				switch ($pic_filetype)
				{
					case 'jpg':
						@imagejpeg($new_pic, $pic_fullpath, $album_config['thumbnail_quality']);
						break;
					case 'png':
						@imagepng($new_pic, $pic_fullpath);
						break;
				}

				@chmod($pic_thumbnail_fullpath, 0777);
				$pic_width = $new_width;
				$pic_height = $new_height;
			} // End IF $gd_errored
			else
			{
				@unlink($pic_fullpath);

				if ($album_config['gd_version'] == 0)
				{
					@unlink($pic_thumbnail_fullpath);
				}

				message_die(GENERAL_ERROR, $lang['Upload_image_size_too_big']." Error code: 002");
			}
		}

		if ($album_config['gd_version'] == 0)
		{
			$thumb_size = getimagesize($pic_thumbnail_fullpath);

			$thumb_width = $thumb_size[0];
			$thumb_height = $thumb_size[1];

			if (($thumb_width > $album_config['thumbnail_size']) || ($thumb_height > $album_config['thumbnail_size']))
			{
				@unlink($pic_fullpath);

				@unlink($pic_thumbnail_fullpath);

				message_die(GENERAL_ERROR, $lang['Upload_thumbnail_size_too_big']);
			}
		}

		// Image Rotation - BEGIN
		// --------------------------------------
		// Need to rotate before thumbnail cache
		// --------------------------------------
		if(($pic_filetype != 'gif') && ($album_config['gd_version'] > 0) && ($_POST['rotation'] > 0) && (!$HotLinked) && ($album_config['switch_nuffload'] == 0))
		{
			// Uncomment the next line if you want to rotate clockwise and remember to change the language setting
			//$_POST['rotation'] = $_POST['rotation'] * -1;

			$gd_errored = false;

			switch ($pic_filetype)
			{
				case 'jpg':
					$read_function = 'imagecreatefromjpeg';
					break;
				case 'png':
					$read_function = 'imagecreatefrompng';
					break;
			}

			$src = @$read_function($pic_fullpath);

			if (!$src)
			{
				$gd_errored = true;
			}
			else
			{
				$rotate = imagerotate($src, $_POST['rotation'], 0);
			}

			if (!$gd_errored)
			{
				// Write to disk
				switch ($pic_filetype)
				{
					case 'jpg':
						@unlink($upload_path . $pic_rotate);
						@imagejpeg($rotate, $pic_fullpath, $album_config['thumbnail_size']);
						break;
					case 'png':
						@unlink($upload_path . $pic_rotate);
						@imagepng($rotate, $upload_path . $pic_resize);
						break;
				}

				@chmod($pic_fullpath, 0777);
				$pic_size = getimagesize($pic_fullpath);
				$pic_width = $pic_size[0];
				$pic_height = $pic_size[1];
			}

		}
		// Image Rotation - END

		// --------------------------------
		// This image is okay, we can cache its thumbnail now
		// --------------------------------

		if(($album_config['thumbnail_cache'] == 1) && ($pic_filetype != 'gif') && ($album_config['gd_version'] > 0))
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
				case '.gif':
					$read_function = 'imagecreatefromgif';
					break;
			}

			$src = @$read_function($pic_fullpath);

			if (!$src)
			{
				$gd_errored = true;
				$pic_thumbnail = '';
			}
			elseif(($pic_width > $album_config['thumbnail_size']) || ($pic_height > $album_config['thumbnail_size']))
			{
				// Resize it
				if ($pic_width > $pic_height)
				{
					$thumbnail_width = $album_config['thumbnail_size'];
					$thumbnail_height = $album_config['thumbnail_size'] * ($pic_height / $pic_width);
				}
				else
				{
					$thumbnail_height = $album_config['thumbnail_size'];
					$thumbnail_width = $album_config['thumbnail_size'] * ($pic_width / $pic_height);
				}

				if($album_config['show_pic_size_on_thumb'] == 1)
				{
					$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height + 16) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height + 16);
				}
				else
				{
					$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
				}

				$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

				@$resize_function($thumbnail, $src, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);

				if($album_config['show_pic_size_on_thumb'] == '1')
				{
					$dimension_font = 1;
					$dimension_filesize = filesize($pic_fullpath);
					$dimension_string = $pic_width . 'x' . $pic_height . '(' . intval($dimension_filesize/1024) . 'KB)';
					$dimension_colour = ImageColorAllocate($thumbnail,255,255,255);
					$dimension_height = imagefontheight($dimension_font);
					$dimension_width = imagefontwidth($dimension_font) * strlen($dimension_string);
					$dimension_x = ($thumbnail_width - $dimension_width) / 2;
					$dimension_y = $thumbnail_height + ((16 - $dimension_height) / 2);
					imagestring($thumbnail, 1, $dimension_x, $dimension_y, $dimension_string, $dimension_colour);
				}
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
					case 'jpg':
						@imagejpeg($thumbnail, $pic_thumbnail_fullpath, $album_config['thumbnail_quality']);
						break;
					case 'png':
						@imagepng($thumbnail, $pic_thumbnail_fullpath);
						break;
					case '.gif':
						@imagegif($thumbnail, $pic_thumbnail_fullpath);
						break;
				}

				@chmod($pic_thumbnail_fullpath, 0777);

			} // End IF $gd_errored

		} // End Thumbnail Cache
		elseif ($album_config['gd_version'] > 0)
		{
			$pic_thumbnail = '';
		}

		// --------------------------------
		// Check Pic Approval
		// --------------------------------
		/*
		$sql = "SELECT cat_user_id
				FROM " . ALBUM_CAT_TABLE . " AS c
				WHERE c.cat_id = '$cat_id'
				LIMIT 1";
		$result = $db->sql_query($sql);
		$this_cat_user_id = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$is_personal_gallery = ($this_cat_user_id > 0) ? true : false;
		*/

		$is_personal_gallery = (album_get_cat_user_id($cat_id) != false) ? true : false;

		if ($is_personal_gallery == true)
		{
			$pic_approval = ($album_config['personal_pics_approval'] == 0) ? 1 : 0;
		}
		else
		{
			$pic_approval = ($thiscat['cat_approval'] == 0) ? 1 : 0;
		}

		// --------------------------------
		// Insert into DB
		// --------------------------------

		$sql = "INSERT INTO " . ALBUM_TABLE . " (pic_filename, pic_thumbnail, pic_title, pic_desc, pic_user_id, pic_user_ip, pic_username, pic_time, pic_cat_id, pic_approval)
				VALUES ('" . $pic_extra_path . $pic_filename . "', '" . $pic_thumbnail . "', '" . $db->sql_escape($pic_title) . "', '" . $db->sql_escape($pic_desc) . "', '" . $pic_user_id . "', '" . $pic_user_ip . "', '" . $db->sql_escape($pic_username) . "', '" . $pic_time . "', '" . $cat_id . "', '" . $pic_approval . "')";
		$result = $db->sql_query($sql);

		if ($is_personal_gallery == true)
		{
			$sql = "SELECT COUNT(pic_id) AS count
				FROM " . ALBUM_TABLE . "
				WHERE pic_user_id = '" . $user->data['user_id'] . "'
				AND pic_cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);
			$personal_pics_count = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$userpics = $personal_pics_count['count'];

			// Check which users category we are in so we don't update the wrong users pic count
			$sql = 'SELECT cat_user_id FROM ' . ALBUM_CAT_TABLE . ' WHERE cat_id = (' . $cat_id . ') LIMIT 1';
			$result = $db->sql_query($sql);
			$usercat = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$cat_user_id = $usercat['cat_user_id'];

			// Update the users personal_pics_count
			if (!empty($userpics) || ($userpics == 0))
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_personal_pics_count = '" . $userpics . "'
					WHERE user_id = '" . $cat_user_id . "'";
				$result = $db->sql_query($sql);
			}
			unset($personal_pics_count);
		}

		// Mighty Gorgon - Send email to admin for notification/approval - BEGIN
		if ($album_config['email_notification'])
		{
			$sql = "SELECT pic_id FROM " . ALBUM_TABLE . "
							WHERE pic_filename = '" . $pic_extra_path . $pic_filename . "'
							AND pic_time = '" . $pic_time . "'
							LIMIT 1";
			$result = $db->sql_query($sql);
			$new_pic_id = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

			$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
			$server_name = trim($config['server_name']);
			$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';
			$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
			$script_name = ($script_name == '') ? '' : $script_name . '/';
			$server_path = $server_protocol . $server_name . $server_port . $script_name;

			$sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active, username, user_level
				FROM " . USERS_TABLE . " AS u
				WHERE u.user_level = " . ADMIN . "
				AND u.user_id <> " . ANONYMOUS . "
				ORDER BY u.username ASC";
			$result = $db->sql_query($sql);

			while ($to_users = $db->sql_fetchrow($result))
			{
				if ($to_users['user_notify_pm'] && !empty($to_users['user_email']) && $to_users['user_active'])
				{
					$emailer = new emailer();
					$emailer->use_template('album_notify', $to_users['user_lang']);
					$emailer->to($to_users['user_email']);
					$emailer->set_subject(); //$lang['Notification_subject']

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'USERNAME' => $to_users['username'],
						'SITENAME' => $config['sitename'],
						'EMAIL_SIG' => $email_sig,
						'FROM' => $user->data['username'],
						'PIC_TITLE' => $pic_title,
						'PIC_ID' => $new_pic_id['pic_id'],
						'PIC_APPROVAL' => ($pic_approval ? $lang['Approvation_OK'] : $lang['Approvation_NO']),
						'DATE' => create_date($config['default_dateformat'], time(), $config['board_timezone']),
						'SUBJECT' => $lang['Email_Notification'],
						'U_PIC' => $server_path . 'album_showpage.' . PHP_EXT . '?pic_id=' . $new_pic_id['pic_id']
						)
					);

					$emailer->send();
					$emailer->reset();
				}
			}
			$db->sql_freeresult($result);
		}
		// Mighty Gorgon - Send email to admin for notification/approval - END
		// Watch pic for comments - BEGIN
		if (isset($_POST['comment_watch']))
		{
			if ($_POST['comment_watch'] == 0)
			{
				//Get the pic id for this pic
				$sql = "SELECT pic_id FROM " . ALBUM_TABLE . "
						WHERE pic_filename = '" . $pic_filename . "'
						AND pic_time = '" . $pic_time . "'
						LIMIT 1";
				$result = $db->sql_query($sql);
				$new_pic_id = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
				$pic_id = $new_pic_id['pic_id'];

				$sql = "INSERT INTO " . ALBUM_COMMENT_WATCH_TABLE . " (pic_id, user_id, notify_status)
					VALUES ('" . $pic_id . "', '" . $user->data['user_id'] . "', 0)";
				$result = $db->sql_query($sql);
			}
		}
		// Watch pic for comments - END
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	if (sizeof($upload_errors) > 0)
	{
		if ($pic_count == sizeof($upload_errors))
		{
			$message = $lang['Album_upload_not_successful'];
		}
		else
		{
			$message = $lang['Album_upload_partially_successful'];
		}

		for ($index = 0; $index < sizeof($upload_errors); $index++)
		{
			$message .= $upload_errors[$index];
		}
	}
	elseif ($thiscat['cat_approval'] == 0)
	{
		$message = $lang['Album_upload_successful'];
	}
	else
	{
		$message = $lang['Album_upload_need_approval'];
	}

	if (($thiscat['cat_approval'] == 0) && (sizeof($upload_errors) == 0))
	{
		if (album_is_debug_enabled() == false)
		{
			$redirect_url = append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id));
			meta_refresh(3, $redirect_url);
		}
	}
	if ($album_user_id == ALBUM_PUBLIC_GALLERY)
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
	}
	else
	{
		$message .= '<br /><br />' . sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
	}

	$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

	synchronize_cat_pics_counter($cat_id);
	if ($album_config['switch_nuffload'] == 1)
	{
		message_die(GENERAL_MESSAGE, multi_loop($message, true));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $message);
	}
}

function getmicrotime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float) $usec + (float) $sec);
}

?>