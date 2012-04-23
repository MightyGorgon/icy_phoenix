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

// ----------------------------------------------------------------------------
// This function will return the access data of the current user for a category
// Default returning value is "0" (means NOT AUTHORIZED)
//
// All $*_check must be "1" or "0"
//
// $passed_auth must be a full row from ALBUM_CAT_TABLE. This function still works without
// ... but $passed_auth will make it worked very much faster (because this function is often
// called in a loop)
//
function album_user_access($cat_id, $passed_auth = 0, $view_check, $upload_check, $rate_check, $comment_check, $edit_check, $delete_check)
{
	global $db, $user, $album_config;

	// --------------------------------
	// Force to check moderator status
	// --------------------------------
	$moderator_check = 1;


	// --------------------------------
	// Here the array which this function would return. Now we initiate it!
	// --------------------------------
	$album_user_access = array(
		'view' => 0,
		'upload' => 0,
		'rate' => 0,
		'comment' => 0,
		'edit' => 0,
		'delete' => 0,
		'moderator' => 0
	);
	$album_user_access_keys = array_keys($album_user_access);
	//
	// END initiation $album_user_access
	//


	// --------------------------------
	// Check $cat_id
	// --------------------------------
	if ($cat_id <= ALBUM_ROOT_CATEGORY && !is_array($passed_auth))
	{
		message_die(GENERAL_ERROR, 'Bad cat_id arguments for function album_user_access()');
	}
	//
	// END check $cat_id
	//


	// --------------------------------
	// If the current user is an ADMIN (ALBUM_ADMIN == ADMIN)
	// --------------------------------
	if ($user->data['user_level'] == ADMIN)
	{
		for ($i = 0; $i < sizeof($album_user_access); $i++)
		{
			$album_user_access[$album_user_access_keys[$i]] = 1; // Authorized All
		}

		//
		// Function EXIT here
		//
		return $album_user_access;
	}
	//
	// END check ADMIN
	//


	// --------------------------------
	// if this is a GUEST, we will ignore some checking
	// --------------------------------
	if (!$user->data['session_logged_in'])
	{
		$edit_check = 0;
		$delete_check = 0;
		$moderator_check = 0;
	}
	//
	// END check GUEST
	//


	// --------------------------------
	// check if RATE or COMMENT are turned off by Album Config, so we can ignore them
	// --------------------------------
	if ($album_config['rate'] == 0)
	{
		$rate_check = 0;
	}
	if ($album_config['comment'] == 0)
	{
		$comment_check = 0;
	}
	//
	// END Check RATE & COMMENT
	//


	// --------------------------------
	// The array that list all access type this function will look for (except MODERATOR)
	// --------------------------------
	$access_type = array();

	if ($view_check != 0)
	{
		$access_type[] = 'view';
	}

	if ($upload_check != 0)
	{
		$access_type[] = 'upload';
	}

	if ($rate_check != 0)
	{
		$access_type[] = 'rate';
	}

	if ($comment_check != 0)
	{
		$access_type[] = 'comment';
	}

	if ($edit_check != 0)
	{
		$access_type[] = 'edit';
	}

	if ($delete_check != 0)
	{
		$access_type[] = 'delete';
	}
	//
	// END generating array $access_type
	//


	// --------------------------------
	// If everything is empty
	// --------------------------------
	if(empty($access_type) && (!$moderator_check))
	{
		//
		// Function EXIT here
		//
		return $album_user_access;
	}
	//
	// END check empty
	//


	// --------------------------------
	// Generate the SQL query based on $access_type and $moderator_check
	// --------------------------------
	$sql = 'SELECT cat_id, cat_user_id';


	for ($i = 0; $i < sizeof($access_type); $i++)
	{
		$sql .= ', cat_'. $access_type[$i] .'_level, cat_'. $access_type[$i] .'_groups';
	}

	if ($moderator_check)
	{
		$sql .= ', cat_moderator_groups';
	}

	$sql .= "
			FROM " . ALBUM_CAT_TABLE . "
			WHERE cat_id = '$cat_id'";
	//
	// END SQL query generating
	//


	// --------------------------------
	// Query the $sql then Fetchrow if $passed_auth == 0
	// --------------------------------
	if(!is_array($passed_auth))
	{
		$result = $db->sql_query($sql);
		$thiscat = $db->sql_fetchrow($result);
	}
	else
	{
		$thiscat = $passed_auth;
	}
	//
	// END Query and Fetchrow
	//


	// --------------------------------
	// Maybe the access level is not PRIVATE or the groups list is empty
	// ... so we can skip some queries ;)
	// --------------------------------
	$groups_access = array();
	for ($i = 0; $i < sizeof($access_type); $i++)
	{
		switch ($thiscat['cat_'. $access_type[$i] .'_level'])
		{
			case ALBUM_GUEST:
				$album_user_access[$access_type[$i]] = 1;
				break;

			case ALBUM_USER:
				if ($user->data['session_logged_in'])
				{
					$album_user_access[$access_type[$i]] = 1;
				}
				break;

			case ALBUM_PRIVATE:
				if(($thiscat['cat_'. $access_type[$i] .'_groups'] != '') and ($user->data['session_logged_in']))
				{
					$groups_access[] = $access_type[$i];
				}
				break;

			case ALBUM_MOD:
				// this will be checked later
				break;

			case ALBUM_ADMIN:
				// ADMIN already returned before at the checking code
				// at the top of this function. So this user cannot be authorized
				$album_user_access[$access_type[$i]] = 0;
				break;

			default:
				$album_user_access[$access_type[$i]] = 0;
		}
	}
	//
	// END Check Access Level
	//


	// --------------------------------
	// We can return now if $groups_access is empty AND $moderator_check == 0
	// --------------------------------
	if(($moderator_check == 1) && ($thiscat['cat_moderator_groups'] != ''))
	{
		// We can merge them now
		$groups_access[] = 'moderator';
	}

	if (empty($groups_access))
	{
		//
		// Function EXIT here
		//
		return $album_user_access;
	}


	// --------------------------------
	// Now we have the list of usergroups have PRIVATE/MODERATOR access
	// So we will check if this user is in these usergroups or not...
	// --------------------------------
	// upto (6 + 1) loops maximum when this user logged in and All Levels
	// are set to PRIVATE and this function was called to check all.
	// So avoiding PRIVATE will speed up your album. However, these queries are very fast
	for ($i = 0; $i < sizeof($groups_access); $i++)
	{
		$sql = "SELECT group_id, user_id
				FROM ". USER_GROUP_TABLE ."
				WHERE user_id = '". $user->data['user_id'] ."' AND user_pending = 0
					AND group_id IN (". $thiscat['cat_'. $groups_access[$i] .'_groups'] .")";
		$result = $db->sql_query($sql);

		if($db->sql_numrows($result) > 0)
		{
			$album_user_access[$groups_access[$i]] = 1;
		}
	}
	//
	// END check PRIVATE/MODERATOR groups
	//


	// --------------------------------
	// If $moderator_check was called and this user is a MODERATOR he
	// will be authorized for all accesses which were not set to ADMIN
	// --------------------------------
	if(($album_user_access['moderator'] == 1) && ($moderator_check == 1))
	{
		for ($i = 0; $i < sizeof($album_user_access); $i++)
		{
			if($thiscat['cat_'. $album_user_access_keys[$i] .'_level'] != ALBUM_ADMIN)
			{
				$album_user_access[$album_user_access_keys[$i]] = 1;
			}
		}
	}
	//
	// END Moderator
	//


	// --------------------------------
	// Return result...
	// --------------------------------
	return $album_user_access;
}
//
// END function album_user_access()
// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------
// This function will check the access (VIEW, UPLOAD) of current user on
// any personal galleries
function personal_gallery_access($check_view, $check_upload)
{
	global $db, $user, $album_config;

	// This array will contain the result
	$personal_gallery_access = array(
		'view' => 0,
		'upload' => 0,
	);

	// --------------------------------
	// Who can create personal gallery?
	// --------------------------------
	if ($check_upload)
	{
		switch ($album_config['personal_gallery'])
		{
			case ALBUM_USER:
				if ($user->data['session_logged_in'])
				{
					$personal_gallery_access['upload'] = 1;
				}
				break;

			case ALBUM_PRIVATE:
				if(($user->data['session_logged_in']) and ($user->data['user_level'] == ADMIN))
				{
					$personal_gallery_access['upload'] = 1;
				}
				else if(!empty($album_config['personal_gallery_private']) and $user->data['session_logged_in'])
				{
					$sql = "SELECT group_id, user_id
							FROM ". USER_GROUP_TABLE ."
							WHERE user_id = '" . $user->data['user_id'] . "' AND user_pending = 0
								AND group_id IN (" . $album_config['personal_gallery_private'] . ")";
					$result = $db->sql_query($sql);

					if($db->sql_numrows($result) > 0)
					{
						$personal_gallery_access['upload'] = 1;
					}
				}
				break;

			case ALBUM_ADMIN:
				if(($user->data['session_logged_in']) and ($user->data['user_level'] == ADMIN))
				{
					$personal_gallery_access['upload'] = 1;
				}
				break;
		}
	}

	// --------------------------------
	// Who can view other personal gallery?
	// --------------------------------
	if ($check_view)
	{
		switch ($album_config['personal_gallery_view'])
		{
			case ALBUM_GUEST:
				$personal_gallery_access['view'] = 1;
				break;

			case ALBUM_USER:
				if ($user->data['session_logged_in'])
				{
					$personal_gallery_access['view'] = 1;
				}
				break;

			case ALBUM_PRIVATE:
				if(($user->data['session_logged_in']) and ($user->data['user_level'] == ADMIN))
				{
					$personal_gallery_access['view'] = 1;
				}
				elseif(!empty($album_config['personal_gallery_private']) and $user->data['session_logged_in'])
				{
					$sql = "SELECT group_id, user_id
							FROM ". USER_GROUP_TABLE ."
							WHERE user_id = '". $user->data['user_id'] ."' AND user_pending = 0
								AND group_id IN (". $album_config['personal_gallery_private'] .")";
					$result = $db->sql_query($sql);

					if($db->sql_numrows($result) > 0)
					{
						$personal_gallery_access['view'] = 1;
					}
				}
				break;
		}
	}

	return $personal_gallery_access;
}
//
// END function personal_gallery_access()
// ----------------------------------------------------------------------------


// ----------------------------------------------------------------------------
// Build up the array similar to $thiscat array
//
function init_personal_gallery_cat($user_id = 0)
{
	global $user, $db, $lang, $album_config;

	if ($user_id == 0)
	{
		$user_id = $user->data['user_id'];
	}

	$sql = "SELECT COUNT(pic_id) AS count
			FROM " . ALBUM_TABLE . ", ". ALBUM_CAT_TABLE . "
			WHERE pic_cat_id = cat_id
				AND cat_user_id = ". $user_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$count = $row['count'];

	if ($user_id != $user->data['user_id'])
	{
		$sql = "SELECT user_id, username
				FROM ". USERS_TABLE ."
				WHERE user_id = $user_id";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$username = $user_row['username'];
	}
	else
	{
		$username = $user->data['username'];
	}

	$thiscat = array(
		'cat_id' => 0,
		'cat_title' => sprintf($lang['Personal_Gallery_Of_User'], $username),
		'cat_desc' => '',
		'cat_order' => 0,
		'count' => $count,
		'personal' => 1,
		'cat_user_id' => $user_id,
		'cat_view_level' => $album_config['personal_gallery_view'],
		'cat_upload_level' => $album_config['personal_gallery'],
		'cat_rate_level' => $album_config['personal_gallery_view'],
		'cat_comment_level' => $album_config['personal_gallery_view'],
		'cat_edit_level' => $album_config['personal_gallery'],
		'cat_delete_level' => $album_config['personal_gallery'],
		'cat_view_groups' => $album_config['personal_gallery_private'],
		'cat_upload_groups' => $album_config['personal_gallery_private'],
		'cat_rate_groups' => $album_config['personal_gallery_private'],
		'cat_comment_groups' => $album_config['personal_gallery_private'],
		'cat_edit_groups' => $album_config['personal_gallery_private'],
		'cat_delete_groups' => $album_config['personal_gallery_private'],
		'cat_delete_groups' => $album_config['personal_gallery_private'],
		'cat_moderator_groups' => '',
		'cat_approval' => 0
	);

	return $thiscat;
}
//
// END function init_personal_gallery_cat()
// ----------------------------------------------------------------------------


// ----------------------------------------------------------------------------
// You must keep my copyright notice with its original content visible
// Do NOT modify anything!!!
function album_end()
{
	global $album_config;

	echo '<div align="center" style="font-family: Verdana; font-size: 10px; letter-spacing: -1px">Powered by Photo Album Addon 2' . $album_config['album_version'] . ' &copy; 2002, 2003 <a href="http://smartor.is-root.com" target="_blank">Smartor</a></div>';
}
//
// OR you can pay me for the copyright notice removal. Contact me!
// ----------------------------------------------------------------------------

//--- Multiple File Upload - BEGIN
// ----------------------------------------------------------------
// check if the file at index $index was uploaded
// ----------------------------------------------------------------
function was_file_uploaded($files_array, $index)
{
	return (((empty($files_array['tmp_name'][$index]) || $files_array['tmp_name'][$index] == 'none') || $files_array['size'][$index] == 0) || $files_array['error'][$index] == 4) ? false : true;
}

// ----------------------------------------------------------------
// check if the file has exceeded the maximum allowed file upload
// set in php.ini
// ----------------------------------------------------------------
function file_uploaded_exceeds_max_size($files_array, $index)
{
	// for some bizar reason I can't get the next few lines to work right 'error' is always = 0
	// UPLOAD_ERR_INI_SIZE == 1 (was first defined in 4.3.0, so 1 here instead)
	return ($files_array['error'][$index] == 1) ? true : false;
}

// ----------------------------------------------------------------
// generates a picture title, depending on the parameter supplied
// ----------------------------------------------------------------
function generate_picture_title($file_name, $pic_title, $pic_filetype)
{
	global $album_config;

	static $counter = 1;

	// if the user didn't supply a picture title then generate it from the
	// picture filename..and clean it up (remove trailing space, underscores and propercase it)
	if (empty($pic_title))
	{
		// remove file extension,
		// NOTE : were do a lowecase of the filename, to ensure that extension with in BIG or misc cApS get removed also
		$pic_title = str_replace($pic_filetype, '', strtolower($file_name));
		// remove underscores '_' and traling spaces
		$pic_title = trim(str_replace('_', ' ', $pic_title));

		if ($album_config['propercase_pic_title'] == 1)
		{
			// convert the first character in each word to upper case and the rest to lower case
			$pic_title = ucwords(strtolower($pic_title));
		}
		/*
		else
		{
			// convert only the first character in a string to upper case, the rest to lower case
			$pic_title = ucfirst(strtolower($pic_title));
		}
		*/
	}
	else
	{
		if ($album_config['propercase_pic_title'] == 1)
		{
			// convert the first character in each word to upper case and the rest to lower case
			$pic_title = ucwords(strtolower($pic_title));
		}
		/*
		else
		{
			// convert only the first character in a string to upper case, the rest to lower case
			$pic_title = ucfirst(strtolower($pic_title));
		}
		*/
		switch ($counter)
		{
			case ($counter < 10):
				$pic_title .= ' - 00' . $counter;
				break;
			case ($counter < 100):
				$pic_title .= ' - 0' . $counter;
				break;
			default:
				$pic_title .= ' - ' . $counter;
				break;
		}
		$counter++;
	}

	return $pic_title;
}
//--- Multiple File Upload - END

function generate_single_pic_title($file_name, $pic_title, $pic_filetype)
{
	global $album_config;

	// if the user didn't supply a picture title then generate it from the
	// picture filename and clean it up (remove trailing space, underscores and propercase it)
	if (empty($pic_title))
	{
		// remove file extension,
		// NOTE : were do a lowecase of the filename, to ensure that extension with in BIG or misc cApS get removed also
		$pic_title = str_replace($pic_filetype, '', strtolower($file_name));
		// remove underscores '_' and traling spaces
		$pic_title = trim(str_replace('_', ' ', $pic_title));

		if ($album_config['propercase_pic_title'] == 1)
		{
			// convert the first character in each word to upper case and the rest to lower case
			$pic_title = ucwords(strtolower($pic_title));
		}
		/*
		else
		{
			// convert only the first character in a string to upper case, the rest to lower case
			$pic_title = ucfirst(strtolower($pic_title));
		}
		*/
	}
	else
	{
		if ($album_config['propercase_pic_title'] == 1)
		{
			// convert the first character in each word to upper case and the rest to lower case
			$pic_title = ucwords(strtolower($pic_title));
		}
		/*
		else
		{
			// convert only the first character in a string to upper case, the rest to lower case
			$pic_title = ucfirst(strtolower($pic_title));
		}
		*/
	}

	return $pic_title;
}

function ImageRating($rating, $css_style = 'border-style:none')
{
//Pre: returns what type of rating style to display

	global $db, $lang, $album_config;

	//decide how user wants to show their rating
	if ($album_config['rate_type'] == 0) //display only images
	{
		if (!$rating)
		{
			return $lang['Not_rated'];
		}
		else
		{
			$r = '';
			for ($temp = 1; $temp <= $rating; $temp++)
			{
				$r .= '<img src="' . ALBUM_MOD_IMG_PATH . 'rating_star.png" style="' . $css_style . '" alt="" />';
			}
			return ($r);
		}
	}
	elseif ($album_config['rate_type'] == 1) //display just text
	{
		if (!$rating)
		{
			return $lang['Not_rated'];
		}
		else
		{
			return (round($rating, 2));
		}
	}
	else //display both images and text
	{
		if (!$rating)
		{
			return $lang['Not_rated'];
		}
		else
		{
			$r = '';
			for ($temp = 1; $temp <= $rating; $temp++)
			{
				$r .= '<img src="' . ALBUM_MOD_IMG_PATH . 'rating_star.png" style="' . $css_style . '" alt="" />';
			}
		}
		return (round($rating, 2) . '&nbsp;' . $r);
	}
}

function CanRate($picID, $userID)
{
	//PRE: decide if user can rate things on hot or not
	global $db, $user, $album_config;

	if (!$user->data['session_logged_in'] && ($album_config['hon_rate_users'] == 1))
	{
		$allowed = true;
	}
	elseif ($user->data['session_logged_in'] && ($album_config['hon_rate_times'] == 0))
	{
		$sql = "SELECT *
					FROM ". ALBUM_RATE_TABLE ."
					WHERE rate_pic_id = $picID
						AND rate_user_id = $userID
					LIMIT 1";
		$result = $db->sql_query($sql);

		if ($db->sql_numrows($result) > 0)
		{
			$allowed = false;
		}
		else
		{
			$allowed = true;
		}
	}
	else
	{
		$allowed = true;
	}

	return ($allowed);
}

function album_comment_notify($pic_id)
{
	global $db, $config, $user, $lang, $album_config;

	// One row SQL for caching purpose...
	$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid <> 0 ORDER BY ban_userid ASC";
	$result = $db->sql_query($sql, 0, 'ban_', USERS_CACHE_FOLDER);

	$user_id_sql = '';
	while ($row = $db->sql_fetchrow($result))
	{
		if (isset($row['ban_userid']) && !empty($row['ban_userid']))
		{
			$user_id_sql .= ', ' . $row['ban_userid'];
		}
	}

	$sql = "SELECT u.user_id, u.user_email, u.user_lang, p.pic_title
				FROM " . ALBUM_COMMENT_WATCH_TABLE . " cw, " . USERS_TABLE . " u
				LEFT JOIN " . ALBUM_TABLE . " AS p ON p.pic_id = $pic_id
				WHERE cw.pic_id = $pic_id
					AND cw.user_id NOT IN (" . $user->data['user_id'] . ", " . ANONYMOUS . $user_id_sql . ")
					AND cw.notify_status = " . COMMENT_WATCH_UN_NOTIFIED . "
					AND u.user_id = cw.user_id";
	$result = $db->sql_query($sql);

	$bcc_list_ary = array();

	if ($row = $db->sql_fetchrow($result))
	{
		$pic_title = $row['pic_title'];

		// Sixty second limit
		@set_time_limit(60);

		do
		{
			if ($row['user_email'] != '')
			{
				$bcc_list_ary[$row['user_lang']][] = $row['user_email'];
			}
				$update_watched_sql .= ($update_watched_sql != '') ? ', ' . $row['user_id'] : $row['user_id'];
		}

		while ($row = $db->sql_fetchrow($result));
		//
		// Let's do some checking to make sure that mass mail functions
		// are working in win32 versions of php.
		//
		if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$config['smtp_delivery'])
		{
			$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$config['smtp_delivery'] = 1;
			$config['smtp_host'] = @$ini_val('SMTP');
		}

		if (sizeof($bcc_list_ary))
		{
			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();

			$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
			$script_name = ($script_name != '') ? $script_name . '/album_showpage.' . PHP_EXT : 'album_showpage.' . PHP_EXT;
			$server_name = trim($config['server_name']);
			$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
			$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';

			@reset($bcc_list_ary);
			while (list($user_lang, $bcc_list) = each($bcc_list_ary))
			{
				$emailer->use_template('album_comment_notify', $user_lang);

				for ($i = 0; $i < sizeof($bcc_list); $i++)
				{
					$emailer->bcc($bcc_list[$i]);
				}
				// The Comment_notification lang string below will be used
				// if for some reason the mail template subject cannot be read
				// ... note it will not necessarily be in the posters own language!
				$emailer->set_subject($lang['Pic_comment_notification']);

				// This is a nasty kludge to remove the username var ... till translators update their templates
				$emailer->msg = preg_replace('#[ ]?{USERNAME}#', '', $emailer->msg);

				$email_sig = create_signature($config['board_email_sig']);
				$emailer->assign_vars(array(
					'EMAIL_SIG' => $email_sig,
					'SITENAME' => $config['sitename'],
					'PIC_TITLE' => $pic_title,
					'U_PIC' => $server_protocol . $server_name . $server_port . $script_name . '?pic_id=' . $pic_id,
					'U_STOP_WATCHING_COMMENT' => $server_protocol . $server_name . $server_port . $script_name . '?pic_id=' . $pic_id . '&unwatch=comment'
					)
				);

				$emailer->send();
				$emailer->reset();
			}
		}
	}
	$db->sql_freeresult($result);

	if ($update_watched_sql != '')
	{
		$sql = "UPDATE " . ALBUM_COMMENT_WATCH_TABLE . "
			SET notify_status = " . COMMENT_WATCH_NOTIFIED . "
			WHERE pic_id = $pic_id
				AND user_id IN ($update_watched_sql)";
		$db->sql_query($sql);
	}
}

function mx_album_uploadfilename($UploadFile)
{
	$UploadFileNameTmp = explode('.', $UploadFile);
	$y = sizeof($UploadFileNameTmp) - 1;
	$r = '';
	for ($a = 0; $y > $a; $a++)
	{
		$r .= '.' . $UploadFileNameTmp[$a];
	}
	return $r;
}

function mx_album_uploadfiletype($UploadFile)
{
	$UploadFileTypeTmp = explode('.', $UploadFile);
	$y = sizeof($UploadFileTypeTmp) - 1;
	$r = $UploadFileTypeTmp[$y];
	return $r;
}

function picture_quick_thumb($pic_filename, $pic_thumbnail, $thumbnail_file)
{
	if (USERS_SUBFOLDERS_ALBUM == true)
	{
		if (strpos($pic_filename, '/') !== false)
		{
			$pic_path[] = array();
			$pic_path = explode('/', $pic_filename);
			$pic_filename = end($pic_path);
		}
	}

	$file_part = explode('.', strtolower($pic_filename));
	$pic_filetype = end($file_part);
	$pic_filename_only = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
	$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
	$pic_extra_path = '';
	$pic_new_filename = $pic_extra_path . $pic_filename;
	$pic_fullpath = $pic_base_path . $pic_new_filename;
	$pic_thumbnail = ($pic_thumbnail != '') ? $pic_thumbnail : (md5($pic_filename) . '.' . $pic_filetype);
	$pic_thumbnail_fullpath = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_thumbnail;

	if (USERS_SUBFOLDERS_ALBUM == true)
	{
		if (sizeof($pic_path) == 2)
		{
			$pic_extra_path = $pic_path[0] . '/';
			$pic_thumbnail_path = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_extra_path;
			if (is_dir($pic_thumbnail_path))
			{
				$pic_new_filename = $pic_extra_path . $pic_filename;
				$pic_fullpath = $pic_base_path . $pic_new_filename;
				$pic_thumbnail_fullpath = $pic_thumbnail_path . $pic_thumbnail;
			}
		}
	}

	if (file_exists($pic_thumbnail_fullpath))
	{
		$thumbnail_file = $pic_thumbnail_fullpath;
	}
	return $thumbnail_file;
}

function pic_info($pic_filename, $pic_thumbnail, $pic_title = '')
{
	$pic_info = array();
	$pic_info['exists'] = false;
	$pic_info['filename'] = $pic_filename;
	$pic_info['thumbnail'] = $pic_thumbnail;
	$pic_info['title'] = $pic_title;
	if (USERS_SUBFOLDERS_ALBUM == true)
	{
		if (strpos($pic_info['filename'], '/') !== false)
		{
			$pic_path[] = array();
			$pic_path = explode('/', $pic_info['filename']);
			$pic_info['filename'] = end($pic_path);
		}
	}

	$file_part = explode('.', strtolower($pic_info['filename']));
	$pic_info['filetype'] = end($file_part);
	$pic_info['filename_only'] = substr($pic_info['filename'], 0, strlen($pic_info['filename']) - strlen($pic_info['filetype']) - 1);
	$pic_info['base_path'] = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
	$pic_info['base_t_s_path'] = IP_ROOT_PATH . ALBUM_CACHE_PATH;
	$pic_info['base_t_m_path'] = IP_ROOT_PATH . ALBUM_MED_CACHE_PATH;
	$pic_info['base_t_w_path'] = IP_ROOT_PATH . ALBUM_WM_CACHE_PATH;
	$pic_info['extra_path'] = '';
	$pic_info['new_filename'] = $pic_info['extra_path'] . $pic_info['filename'];
	$pic_info['fullpath'] = $pic_info['base_path'] . $pic_info['new_filename'];
	$pic_info['thumbnail_new'] = md5($pic_info['filename']) . '.' . $pic_info['filetype'];
	$pic_info['thumbnail'] = ($pic_info['thumbnail'] == '') ? $pic_info['thumbnail_new'] : $pic_info['thumbnail'];
	$thumbs_prefixes = array('s', 'm', 'w');
	for ($i = 0; $i < sizeof($thumbs_prefixes); $i++)
	{
		$cp = $thumbs_prefixes[$i];
		$pic_info['thumbnail_' . $cp . '_fullpath'] = $pic_info['base_t_' . $cp . '_path'] . $pic_info['thumbnail'];
		$pic_info['thumbnail_new_' . $cp . '_fullpath'] = $pic_info['base_t_' . $cp . '_path'] . $pic_info['thumbnail_new'];
		if ($cp == 'w')
		{
			$pic_info['thumbnail_' . $cp . '_f_fullpath'] = $pic_info['base_t_' . $cp . '_path'] . 'full_' . $pic_info['thumbnail'];
			$pic_info['thumbnail_new_' . $cp . '_f_fullpath'] = $pic_info['base_t_' . $cp . '_path'] . 'full_' . $pic_info['thumbnail_new'];
		}
	}
	$pic_info['title_reg'] = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_info['title']);

	if (USERS_SUBFOLDERS_ALBUM == true)
	{
		if (sizeof($pic_path) == 2)
		{
			$pic_info['extra_path'] = $pic_path[0] . '/';
			if (is_dir($pic_info['base_path'] . $pic_info['extra_path']))
			{
				$pic_info['new_filename'] = $pic_info['extra_path'] . $pic_info['filename'];
				$pic_info['fullpath'] = $pic_info['base_path'] . $pic_info['new_filename'];
			}
			else
			{
				$pic_info['exists'] = false;
				return $pic_info;
			}

			for ($i = 0; $i < sizeof($thumbs_prefixes); $i++)
			{
				$cp = $thumbs_prefixes[$i];
				$dir_exists = false;
				$current_t_path = $pic_info['base_t_' . $cp . '_path'] . $pic_info['extra_path'];
				if (is_dir($current_t_path))
				{
					$dir_exists = true;
				}
				else
				{
					$dir_creation = @mkdir($current_t_path, 0777);
					if ($dir_creation == true)
					{
						@copy($pic_info['base_path'] . 'index.html', $current_t_path . 'index.html');
						@chmod($current_t_path . 'index.html', 0755);
						$dir_exists = true;
					}
				}

				if ($dir_exists == true)
				{
					$pic_info['thumbnail_' . $cp . '_fullpath'] = $current_t_path . $pic_info['thumbnail'];
					$pic_info['thumbnail_new_' . $cp . '_fullpath'] = $current_t_path . $pic_info['thumbnail_new'];
					if ($cp == 'w')
					{
						$pic_info['thumbnail_' . $cp . '_f_fullpath'] = $current_t_path . 'full_' . $pic_info['thumbnail'];
						$pic_info['thumbnail_new_' . $cp . '_f_fullpath'] = $current_t_path . 'full_' . $pic_info['thumbnail_new'];
					}
				}
			}
		}
	}
	$pic_info['exists'] = true;
	return $pic_info;
}

// JHL - Builds the common picture column template variables
function album_build_column_vars(&$result, $data, $page_params = '')
{
	global $config, $user, $lang, $album_config;

	$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $data['pic_id'])); // album_hierarchy_functions
	if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
	{
		$thumbnail_file = picture_quick_thumb($data['pic_filename'], $data['pic_thumbnail'], $thumbnail_file);
	}

	$pic_sp_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $data['pic_id'] . $page_params));
	$pic_dl_link = append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $data['pic_id']));

	$result['U_PIC'] = ($album_config['fullpic_popup'] ? $pic_dl_link : $pic_sp_link);
	$result['U_PIC_SP'] = $pic_sp_link;
	$result['U_PIC_DL'] = $pic_dl_link;
	$result['THUMBNAIL'] = $thumbnail_file;
	$result['PIC_TITLE'] = htmlspecialchars($data['pic_title']);
	$result['DESC'] = htmlspecialchars($data['pic_desc']);
}

// JHL - Builds the common picture details template data
function album_build_detail_vars(&$result, $data, $page_params = '', $auth_rights = false)
{
	global $config, $user, $lang, $album_config;

	album_build_column_vars(&$result, $data, $page_params);

	$rating = '';
	if ($album_config['rate'] == 1)
	{
		$image_rating = ImageRating($data['rating']);
		$image_rating_link_class = ($image_rating == $lang['Not_rated']) ? '' : 'class="rated"';
		$rating = $lang['Rating'] . ' : <a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $data['pic_id'] . $page_params)) . '" ' . $image_rating_link_class . '>' . $image_rating . '</a>';
	}

	$comments = '';
	if ($album_config['comment'] == 1)
	{
		$image_comment = ($data['comments'] == 0) ? $lang['Not_commented'] : $data['comments'];
		$image_comment_link_class = ($image_comment == $lang['Not_commented']) ? '' : 'class="commented"';
		$comments = $lang['Comments'] . ' : <a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $data['pic_id'] . $page_params)) . '" ' . $image_comment_link_class . '>' . $image_comment . '</a>';
	}

	$result['PIC_ID'] = $data['pic_id'];
	$result['TITLE'] = '<a href = "' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $data['pic_id'] . $page_params)) . '">' . htmlspecialchars($data['pic_title']) . '</a>';
	$result['TIME'] = create_date($config['default_dateformat'], $data['pic_time'], $config['board_timezone']);
	$result['VIEW'] = $data['pic_view_count'];
	$result['RATING'] = $rating;
	$result['COMMENTS'] = $comments;

	if (empty($auth_rights))
	{
		$auth_rights = array(
			'edit' => true,
			'delete' => true,
			'admin' => true,
		);
	}
	$can_edit = (($user->data['user_level'] == ADMIN) or ($user->data['user_id'] == $data['pic_user_id']));
	$result['EDIT'] = ($can_edit && $auth_rights['edit']) ? '<a href="' . append_sid(album_append_uid('album_edit.' . PHP_EXT . '?pic_id=' . $data['pic_id'])) . '">' . $lang['Edit_pic'] . '</a>' : '';
	$result['DELETE'] = ($can_edit && $auth_rights['delete']) ? '<a href="' . append_sid(album_append_uid('album_delete.' . PHP_EXT . '?pic_id=' . $data['pic_id'])) . '">' . $lang['Delete_pic'] . '</a>' : '';

	$is_admin = ($user->data['user_level'] == ADMIN);
	$result['LOCK'] = (($is_admin && $auth_rights['admin']) ? '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=lock&amp;pic_id=' . $data['pic_id'])) . '">' . $lang['Lock'] . '</a>' : '');
	$result['MOVE'] = (($is_admin && $auth_rights['admin']) ? '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=move&amp;pic_id=' . $data['pic_id'])) . '">' . $lang['Move'] . '</a>' : '');
	$result['COPY'] = (($is_admin && $auth_rights['admin']) ? '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=copy&amp;pic_id=' . $data['pic_id'])) . '">' . $lang['Copy'] . '</a>' : '');
	$result['IP'] = ($is_admin ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . htmlspecialchars(urlencode($data['pic_user_ip'])) . '" target="_blank">' . htmlspecialchars($data['pic_user_ip']) . '</a>' : '');
}

if (!function_exists('setFlag'))
{
	function setFlag($flags, $flag)
	{
		return $flags | $flag;
	}
}

if (!function_exists('clearFlag'))
{
	function clearFlag($flags, $flag)
	{
		return ($flags & ~$flag);
	}
}

if (!function_exists('checkFlag'))
{
	function checkFlag($flags, $flag)
	{
		return (($flags & $flag) == $flag) ? true : false;
	}
}

?>