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
* IdleVoid (idlevoid@slater.dk)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Album Data Structure description
// indexes :
// - id  : the category id : ie ALBUM_ROOT_CATEGORY, 1, 20, 12 and so on
// - idx : array index
// $album_data['keys'][id]			=> idx, returns the key value for the sub, parent, id and data array
// $album_data['auth'][id]			=> auth_value array : ie album_tree_data['auth'][id]['auth_view'],
// $album_data['sub'][id]			=> array of sub-level ids,
// $album_data['parent'][idx]		=> parent id,
// $album_data['id'][idx]			=> value of the row id : cat_id for cats
// $album_data['personal'][idx]		=> list of db table row which indicated if it's personal category,
// $album_data['data'][idx]			=> db table row,
// --------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------
// generate the album category hierarchy
// authentication data for the tree
// ------------------------------------------------------------------------
function album_create_user_auth($user_id)
{
	global $album_data;

	// read the user auth if requiered
	if (empty ($album_data['auth']))
	{
		$album_data['auth'] = array ();

		for ($idx = 0; $idx < count($album_data['data']); $idx++)
		{
			$cat = $album_data['data'][$idx];
			$cat_id = $cat['cat_id'];

			// check all access rights for current user
			//$album_user_access = album_user_access($cat_id, $cat, 1, 1, 1, 1, 1, 1);
			$album_user_access = album_permissions($user_id, $cat_id, ALBUM_AUTH_ALL, $cat);
			if (!empty ($album_user_access))
			{
				reset($album_user_access);
				while (list ($key, $data) = each($album_user_access))
				{
					$album_data['auth'][$cat_id][$key] = $data;
				}
			}
		}
	}

	return $album_data['auth'];
}

// ------------------------------------------------------------------------
// Builds the array of authentication row ids
// where authentication means where $auth_key was
// fullfiled
// ------------------------------------------------------------------------
// Authentication data structure
//
// - id  : the category id : ie ALBUM_ROOT_CATEGORY, 1, 20, 12 and so on
// - auth_id  : array index
// - idx : array in the album_data array structure
//
// $keys['keys'][id]				=> returns ,
// $keys['id'][auth_id]				=> id (used by $album_data ),
// $keys['real_level'][auth_id]		=> level in this auth-tree (root=-1),
// $keys['level'][auth_id]			=> level adjust for display (sub-level=parent level under certain conditions)
// $keys['idx'][auth_id]			=> idx (used by $album_data)
// --------------------------------------------------------------------------------------------------
function album_get_auth_keys($cur_cat_id = ALBUM_ROOT_CATEGORY, $auth_key = ALBUM_AUTH_VIEW, $all = false, $level = -1, $max = -1)
{
	global $album_data;

	$keys = array ();
	$last_i = -1;

	// add the level
	if ( ($max < ALBUM_ROOT_CATEGORY + 1) || ($level < $max) || (($level == $max) && ($album_data['parent'][$album_data['keys'][$cur_cat_id]] == ALBUM_ROOT_CATEGORY)) )
	{
		if ($cur_cat_id == ALBUM_ROOT_CATEGORY || album_check_permission($album_data['auth'][$cur_cat_id], $auth_key) || $all)
		{
			// if child of cat, align the level on the parent one
			$orig_level = $level;

			// store this level
			$last_i++;

			$keys['keys'][$cur_cat_id] = $last_i;
			$keys['id'][$last_i] = $cur_cat_id;
			$keys['real_level'][$last_i] = $orig_level;
			$keys['level'][$last_i] = $level;
			$keys['idx'][$last_i] = (isset ($album_data['keys'][$cur_cat_id]) ? $album_data['keys'][$cur_cat_id] : ALBUM_ROOT_CATEGORY);

			// get sub-levels
			for ($i = 0; $i < count($album_data['sub'][$cur_cat_id]); $i++)
			{
				$subkeys = array ();
				$subkeys = album_get_auth_keys($album_data['sub'][$cur_cat_id][$i], $auth_key, $all, $orig_level + 1, $max);

				// add sub-levels
				for ($j = 0; $j < count($subkeys['id']); $j++)
				{
					$last_i++;
					$keys['keys'][$subkeys['id'][$j]] = $last_i;
					$keys['id'][$last_i] = $subkeys['id'][$j];
					$keys['real_level'][$last_i] = $subkeys['real_level'][$j];
					$keys['level'][$last_i] = $subkeys['level'][$j];
					$keys['idx'][$last_i] = $subkeys['idx'][$j];
				} // for( $j = 0.....
			} // for($i = 0.....
		} // if ($cur_cat_id == ALBUM_ROOT....
	} // if (($max < 0 .....

	if ($level <= ALBUM_ROOT_CATEGORY && ALBUM_HIERARCHY_DEBUG_ENABLED == true)
	{
		album_debug('album_get_auth_keys = %s', $keys);
	}

	return $keys;
}

// ------------------------------------------------------------------------
// Check the permissions for public and personal galleries
// If we are checking a personal gallery and it doesn't exists
// the function will then validate it by calling 'personal_gallery_access'
// if the gallery exists or its a public gallery then we use the code in
// album_user_access. After these calls, some more checks are done in this
// function.
// ------------------------------------------------------------------------
function album_permissions($user_id, $cat_id, $permission_checks, $catdata = 0)
{
	global $db, $lang, $userdata, $album_config, $album_data;

	$moderator_check = 1;

	if (album_is_debug_enabled() == true)
	{
		if (!defined('ALBUM_AUTH_VIEW') || !defined('ALBUM_AUTH_UPLOAD') || !defined('ALBUM_AUTH_DELETE') )
		{
			album_debug("album_permissions : The defined authentication constants are NOT found !!!");
		}
	}

	$view_check = (int) checkFlag($permission_checks, ALBUM_AUTH_VIEW);
	$upload_check = (int) checkFlag($permission_checks, ALBUM_AUTH_UPLOAD);
	$rate_check = (int) checkFlag($permission_checks, ALBUM_AUTH_RATE);
	$comment_check = (int) checkFlag($permission_checks, ALBUM_AUTH_COMMENT);
	$edit_check = (int) checkFlag($permission_checks, ALBUM_AUTH_EDIT);
	$delete_check = (int) checkFlag($permission_checks, ALBUM_AUTH_DELETE);

	// ------------------------------------------------------------------------
	// if we are checkinfg the personal gallery category management permission
	// we need to do these also : view and upload
	// ------------------------------------------------------------------------
	if ( checkFlag($permission_checks, ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES) == true)
	{
		$view_check = 1;
		$upload_check = 1;
	}

	// ------------------------------------------------------------------------
	// did we pass some category data or not ?
	// ------------------------------------------------------------------------
	if (!is_array($catdata))
	{
		$sql = "SELECT *
				FROM ". ALBUM_CAT_TABLE ."
				WHERE cat_id = '$cat_id'";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not query Album Category information for authentication' ,'' , __LINE__, __FILE__, $sql);
		}

		// ------------------------------------------------------------------------
		// did we find the category or not ?
		// ------------------------------------------------------------------------
		if ($db->sql_numrows($result) == 0)
		{
			// ------------------------------------------------------------------------
			// is it a personal gallery ?
			// ------------------------------------------------------------------------
			if ($user_id != ALBUM_PUBLIC_GALLERY)
			{
				$AH_thiscat = init_personal_gallery_cat($user_id);
				$album_permission = personal_gallery_access(1,1); //$view_check, $upload_check);
			}
			else
			{
				message_die(GENERAL_ERROR, $lang['Category_not_exist'] ,'' , __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$AH_thiscat = $db->sql_fetchrow($result);
		}
	}
	else
	{
		$AH_thiscat = $catdata;

		// ------------------------------------------------------------------------
		// it is the root category of a non existing personal gallery
		// ------------------------------------------------------------------------
		if ($AH_thiscat['cat_user_id'] != 0 && $AH_thiscat['cat_id'] == 0)
		{
			$album_permission = personal_gallery_access(1, 1); //$view_check, $upload_check);
		}
	}

	// ------------------------------------------------------------------------
	// if we set our $AH_thiscat and not our permission array then we must
	// authenticate it
	// ------------------------------------------------------------------------
	if (album_is_debug_enabled() == true)
	{
		album_debug('album_permissions : before album_user_access : %s(id=%d), $album_permission = %s',$AH_thiscat['cat_title'],$AH_thiscat['cat_id'], $album_permission);
	}

	if (!empty($AH_thiscat) && !is_array($album_permission))
	{
		$album_permission = album_user_access($cat_id, $AH_thiscat, $view_check, $upload_check, $rate_check, $comment_check, $edit_check, $delete_check);
	}

	if (album_is_debug_enabled() == true)
	{
		album_debug('album_permissions : after album_user_access : %s(id=%d), $album_permission = %s',$AH_thiscat['cat_title'],$AH_thiscat['cat_id'], $album_permission);
	}
	// ------------------------------------------------------------------------
	// as default nobody can manage the galleries (personal galleries that is)
	// check is done later, but only for personal galleries, so its not possible
	// to manage the categories in the public galleries, only in the ACP
	// ------------------------------------------------------------------------
	$album_permission['manage'] = 0;

	// ------------------------------------------------------------------------
	// $album_permission should now hold our permission stuff for either a personal
	// gallery or a public gallery.
	// lets now do some more authentication for the personal galleries
	// ------------------------------------------------------------------------
	if ($AH_thiscat['cat_user_id'] != 0)
	{
		if (album_is_debug_enabled() == true)
		{
			album_Debug('$album_config[\'personal_gallery\'] = %d', $album_config['personal_gallery']);
		}

		switch ($album_config['personal_gallery'])
		{
			case ALBUM_USER:
				// ------------------------------------------------------------------------
				// are we checking a non existing personal gallery ?
				// ------------------------------------------------------------------------
				if (empty($AH_thiscat) || $AH_thiscat['cat_id'] == 0 || $cat_id == ALBUM_ROOT_CATEGORY)
				{
					// ------------------------------------------------------------------------
					// if the admin has set the creation of personal galleries to 'registered users'
					// then filter out all other users then the current logged in user (and NON ADMIN)
					// ------------------------------------------------------------------------
					if ($userdata['user_id'] != $AH_thiscat['cat_user_id'] && $userdata['user_level'] != ADMIN)
					{
						$album_permission['upload'] = 0;
					}
					// ------------------------------------------------------------------------
					// set the other permissions to the same value of the upload
					// for this non exsting personal gallery,
					// ------------------------------------------------------------------------
					$album_permission['rate'] = $album_permission['upload'];
					$album_permission['edit'] = $album_permission['upload'];
					$album_permission['delete'] = $album_permission['upload'];
					$album_permission['comment'] = $album_permission['upload'];
				}
				break;
			case ALBUM_ADMIN:
				// ------------------------------------------------------------------------
				// Only admins can upload images to users personal gallery
				// ------------------------------------------------------------------------
				if ($userdata['user_level'] != ADMIN)
				{
					$album_permission['upload'] = 0;
				}
				break;
			default:
				// NOTHING;
		}

		// ------------------------------------------------------------------------
			// we need to check the upload permission again to full fill all the
			// permission criterias
			// ------------------------------------------------------------------------
			switch ($AH_thiscat['cat_upload_level'])
			{
				case ALBUM_PRIVATE:
					// ------------------------------------------------------------------------
					// make sure the owner of the personal gallery can upload to his personal gallery
					// it the permission is set to private BUT only for existing personal galleries
					// if ($AH_thiscat['cat_id'] != 0 && ($user_id == $userdata['user_id']) )
					// ------------------------------------------------------------------------
					if ( $AH_thiscat['cat_id'] != 0 && ($AH_thiscat['cat_user_id'] == $userdata['user_id']) )
					{
						if ($album_config['personal_gallery'] == ALBUM_ADMIN && $userdata['user_level'] != ADMIN)
						{
							$album_permission['upload'] = 0;
						}
						else
						{
						$album_permission['upload'] = 1;
					}
				}
				break;
			default:
				// NOTHING;
		}

		// ------------------------------------------------------------------------
		// Check if we can moderate the personal gallery AND also check if we can
		// manage the personal gallery categories
		// ------------------------------------------------------------------------
		if ( ($userdata['user_level'] == ADMIN) ||
			(($album_permission['upload'] == 1) &&
			($album_config['personal_allow_gallery_mod'] == 1) &&
			($AH_thiscat['cat_user_id'] == $userdata['user_id'])) )
		{
			$album_permission['moderator'] = 1;
		}

		if ( ($userdata['user_level'] == ADMIN) ||
			(($album_config['personal_allow_sub_categories'] == 1) &&
			($album_config['personal_sub_category_limit'] != 0) &&
			($AH_thiscat['cat_user_id'] == $userdata['user_id']) &&
			($album_permission['upload'] == 1)) )
		{
			$album_permission['manage'] = 1;
		}

		// ------------------------------------------------------------------------
		// If $moderator_check was called and this user is a MODERATOR the user
		// will be authorized for all accesses which were not set to ADMIN
		// except for the management of the categories in the personal gallery
		// ------------------------------------------------------------------------
		if ($album_permission['moderator'] == 1)
		{
			$album_permission_keys = array_keys($album_permission);

			for ($i = 0; $i < count($album_permission); $i++)
			{
				if( ($AH_thiscat['cat_'. $album_permission_keys[$i] .'_level'] != ALBUM_ADMIN) && ($album_permission_keys[$i] != 'manage') )
				{
					$album_permission[$album_permission_keys[$i]] = 1;
				}
			}
		}
	}

	if (album_is_debug_enabled() == true)
	{
		album_debug('final : $album_permission = %s', $album_permission);
	}

	return $album_permission;
}

// ------------------------------------------------------------------------
// Returns true if the access checks are full filled, the code is made in
// such a way that it will calculate what it needs to be checked
// automatically.
// If $or_check is true, then we do an OR instead of an AND check, which
// mean, the very first time we get ONE of the needed rights accepted we
// end and return 'true' on all other check too, and thus we have fullfilled
// the permission. BECAREFUL when you user the or_check
// ------------------------------------------------------------------------
function album_check_permission($auth_data, $access_check, $or_check = false)
{
	// NOTE : ALBUM_AUTH_CREATE_PERSONAL and ALBUM_AUTH_UPLOAD are synomous for each other
	//	and thus only the ALBUM_AUTH_UPLOAD is present here
	$access_type = array (
		ALBUM_AUTH_VIEW => 'view',
		ALBUM_AUTH_UPLOAD => 'upload',
		ALBUM_AUTH_RATE => 'rate',
		ALBUM_AUTH_COMMENT => 'comment',
		ALBUM_AUTH_EDIT => 'edit',
		ALBUM_AUTH_DELETE => 'delete',
		ALBUM_AUTH_MODERATOR => 'moderator',
		ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES => 'manage'
	);

	$access_index = array (
		'0' => ALBUM_AUTH_VIEW,
		'1' => ALBUM_AUTH_UPLOAD,
		'2' => ALBUM_AUTH_RATE,
		'3' => ALBUM_AUTH_COMMENT,
		'4' => ALBUM_AUTH_EDIT,
		'5' => ALBUM_AUTH_DELETE,
		'6' => ALBUM_AUTH_MODERATOR,
		'7' => ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES
	);

	$access_to_check = array ();

	// build up the array of checks to perform
	for ($idx = 0; $idx < count($access_index); $idx++)
	{
		if (checkFlag($access_check, $access_index[$idx]))
		{
			$access_to_check[] = $access_index[$idx];
		}
	}

	$result = 0;
	// now check every check in the acess_check array
	for ($idx = 0; $idx < count($access_to_check); $idx++)
	{
		// $access_string should hold strings like 'view', 'upload' and so on
		$access_string = $access_type[$access_to_check[$idx]];
		if ($auth_data[$access_string] == 1)
		{
			$result += $access_to_check[$idx];
			// simulate that all check got verified successfully
			if ($or_check == true)
			{
				$result = $access_check;
				break;
			}
		}
	}

	// $result now holds to total sum of check
	// which should be qual to the value of
	// the $access_check parameter
	return ($result == $access_check) ? true : false;
}

// ------------------------------------------------------------------------
// Get the authentication data for the category usefull to be used for
// simple authentication, I think it's not used at all !?!
// ------------------------------------------------------------------------
function album_get_auth_data($cat_id)
{
	global $album_data;

	if ( ($cat_id != ALBUM_ROOT_CATEGORY) && (!isset($album_data) || !is_array($album_data) || (count($album_data) == 0)) )
	{
		//$auth_data = //album_user_access($cat_id, 0, 1, 1, 1, 1, 1, 1);
		$auth_data = album_permissions(0, $cat_id, 0, ALBUM_AUTH_ALL);
		return $auth_data;
	}

	if (album_is_debug_enabled() == true)
	{
		if (!array_key_exists($cat_id, $album_data['auth']))
			return false;
	}
	else
	{
		if (@!array_key_exists($cat_id, $album_data['auth']))
		{
			return false;
		}
	}

	return $album_data['auth'][$cat_id];
}

// ------------------------------------------------------------------------
// Builds the authentication list, at the bottom of the album pages
// ------------------------------------------------------------------------
function album_build_auth_list($user_id, $cat_id = ALBUM_ROOT_CATEGORY, $auth_data = 0)
{
	global $lang, $userdata, $album_config;

	$auth_list = '';

	if (!is_array($auth_data))
	{
		if ($cat_id == ALBUM_ROOT_CATEGORY)
		{
			message_die(GENERAL_ERROR, "Invalid combination of category id and authentication data");
		}

		$auth_data = album_get_auth_data($cat_id);
	}

	$auth_key = array_keys($auth_data);

	for ($i = 0; $i < (count($auth_data) - 1); $i++) // ignore MODERATOR in this loop
	{
		// we should skip a loop if RATE and COMMENT is disabled
		if ((($album_config['rate'] == 0) && ($auth_key[$i] == 'rate')) || (($album_config['comment'] == 0) && ($auth_key[$i] == 'comment')))
		{
			continue;
		}

		$auth_list .= ($auth_data[$auth_key[$i]] == 1) ? $lang['Album_' . $auth_key[$i] . '_can'] : $lang['Album_'.$auth_key[$i] . '_cannot'];
		$auth_list .= '<br />';
	}

	// ------------------------------------------------------------------------
	// add Moderator Control Panel here
	// ------------------------------------------------------------------------
	if (($userdata['user_level'] == ADMIN) || ($auth_data['moderator'] == 1))
	{
		$auth_list .= sprintf($lang['Album_moderate_can'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
		$auth_list .= '<br />';
	}

	// ------------------------------------------------------------------------
	// if admin has allowed user to manage his sub categories AND also have
	// allowed for more then one category then enable the personal gallery
	// category admin
	// ------------------------------------------------------------------------
	if (($user_id != ALBUM_PUBLIC_GALLERY) && ($auth_data['manage'] == 1))
	{
		/*
		if ( ($userdata['user_level'] == ADMIN) ||
			(($album_config['personal_allow_gallery_mod'] == 1) &&
			($album_config['personal_allow_sub_categories'] == 1) &&
			($album_config['personal_sub_category_limit'] != 0)) )
		*/
		if (($userdata['user_level'] == ADMIN) || (($album_config['personal_allow_sub_categories'] == 1) && ($album_config['personal_sub_category_limit'] != 0)))
		{
			$auth_list .= sprintf($lang['Album_Can_Manage_Categories'], '<a href="' . append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
			$auth_list .= '<br />';
		}
	}

	return $auth_list;
}

?>