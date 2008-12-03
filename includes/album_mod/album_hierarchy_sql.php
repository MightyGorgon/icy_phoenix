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

// ------------------------------------------------------------------------
// Reorder_cat updates the database with the new order value, normally used
// after delete or cat move
// ------------------------------------------------------------------------
function reorder_cat($user_id = ALBUM_PUBLIC_GALLERY)
{
	album_reorder_cat($user_id);
}

function album_reorder_cat($user_id = ALBUM_PUBLIC_GALLERY)
{
	global $album_data , $db;

	if (count($album_data['data']) == 0)
	{
		album_read_tree($user_id);
	}

	// update with new order
	$order = 0;
	for ($i = 0; $i < count($album_data['data']); $i++)
	{
		if (!empty($album_data['id'][$i]))
		{
			$order += 10;
			$sql = "UPDATE " . ALBUM_CAT_TABLE . "
					SET cat_order = $order
					WHERE cat_id = " . intval($album_data['id'][$i]);

			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t reorder forums/categories table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	// re-read the tree
	album_read_tree($user_id);
}

// ------------------------------------------------------------------------
// Read the album information from the database, either public or personal
// ------------------------------------------------------------------------
function album_read_tree($user_id = ALBUM_PUBLIC_GALLERY, $options = ALBUM_AUTH_VIEW)
{
	global $db, $album_data, $userdata;

	$can_view = (int) checkFlag($options, ALBUM_AUTH_VIEW);
	$can_upload = (int) checkFlag($options, ALBUM_AUTH_UPLOAD);
	$can_rate = (int) checkFlag($options, ALBUM_AUTH_RATE);
	$can_comment = (int) checkFlag($options, ALBUM_AUTH_COMMENT);
	$can_edit = (int) checkFlag($options, ALBUM_AUTH_EDIT);
	$can_delete = (int) checkFlag($options, ALBUM_AUTH_DELETE);

	// parent categories
	$parents = array();
	// read categories and categories with right user access rights
	$cats = array();

	if (count($album_data['data']) > 0)
	{
		return ALBUM_DATA_ALREADY_READ;
	}

	$parent_root_id = ALBUM_ROOT_CATEGORY;

	if (checkFlag($options, ALBUM_READ_ALL_CATEGORIES))
	{
		// All galleries, both public and personal
		$sql = "SELECT c.*, COUNT(p.pic_id) AS count, u.username AS username
				FROM " . ALBUM_CAT_TABLE . " AS c
					LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id
					LEFT JOIN " . USERS_TABLE . " AS u ON c.cat_user_id = u.user_id
				WHERE cat_id <> 0
				GROUP BY cat_id " . album_get_sql_category_sort();
	}
	else
	{
		if ($user_id == ALBUM_PUBLIC_GALLERY)
		{
			// Public galleries
			$sql = "SELECT c.*, COUNT(p.pic_id) AS count, '' AS username
					FROM " . ALBUM_CAT_TABLE . " AS c
						LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id
					WHERE cat_id <> 0 AND c.cat_user_id = 0
					GROUP BY cat_id " . album_get_sql_category_sort();
		}
		else
		{
			// Personal galleries
			$sql = "SELECT c.*, COUNT(p.pic_id) AS count, u.username
					FROM " . ALBUM_CAT_TABLE . " AS c
						LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id
						LEFT JOIN " . USERS_TABLE . " AS u ON c.cat_user_id = u.user_id
					WHERE u.user_id = " . $user_id ."
					GROUP BY c.cat_id " . album_get_sql_category_sort();
		}
	}

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't access list of Album Categories", "", __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) == 0)
	{
		if (album_is_debug_enabled() == true)
		{
			album_debugEx('album_read_tree : no rows was selected using this sql = %s', $sql);
		}
		return;
	}

	while ($row = $db->sql_fetchrow($result))
	{
		// ------------------------------------------------------------------------
		// if current category id is the same as the parent id, then replace parent id with 0
		// ------------------------------------------------------------------------
		if ($row['cat_parent'] == $row['cat_id'])
		{
			$row['cat_parent'] = 0;
		}
		// store the parent id for this category in the row array
		$row['parent'] = ($row['cat_parent'] == 0) ? $parent_root_id : $row['cat_parent'];
		$idx = count($cats);
		$cats[$idx] = $row;
		$parents[$row['parent']][] = $idx;
	}

	$db->sql_freeresult($result);

	// build the tree
	$album_data  = array();
	album_build_tree($cats, $parents);

	// populate the authentication data to the album tree
	album_create_user_auth($user_id);

	if (album_is_debug_enabled() == true)
	{
		album_debug('album_read_tree : user id = %d, $album_data[\'auth\'] = %s', $user_id, $album_data['auth']);
	}

	// ------------------------------------------------------------------------
	// from the authenticated categories, build alist of allowed categories
	// where the authentication rights fits the one that was specified in the
	// function call (album_read_tree)
	// ------------------------------------------------------------------------
	if (!empty($album_data['auth']) || count($album_data['auth']) > 0)
	{
		$cats = array(); // re-create an array
		for ($idx = 0; $idx < count($album_data['auth']); $idx++)
		{
			$cat_id = $album_data['id'][$idx];

			if (($album_data['auth'][$cat_id]["view"] >= $can_view) &&
				($album_data['auth'][$cat_id]["upload"] >= $can_upload) &&
				($album_data['auth'][$cat_id]["rate"] >= $can_rate) &&
				($album_data['auth'][$cat_id]["comment"] >= $can_comment) &&
				($album_data['auth'][$cat_id]["edit"] >= $can_edit) &&
				($album_data['auth'][$cat_id]["delete"] >= $can_delete))
			{
				if (checkFlag($options, ALBUM_CREATE_CAT_ID_LIST))
				{
					$cats[0] .= ((empty($cats[0])) ? '' : ',') . $album_data['data'][$idx]['cat_id'];
				}
				else
				{
					$cats[] = $album_data['data'][$idx];
				}
			}
		}
	}

	if (album_is_debug_enabled() == true)
	{
		album_debug('album_read_tree : $cats = %s', $cats);
	}

	if (checkFlag($options, ALBUM_CREATE_CAT_ID_LIST))
	{
		return $cats[0];
	}
	else
	{
		return $cats;
	}
}

function album_init_personal_gallery($user_id)
{
	global $db, $album_data , $userdata;

	// parent categories
	$parents = array();
	// read categories and categories with right user access rights
	$cats = array();

	$parent_root_id = ALBUM_ROOT_CATEGORY;

	$row = init_personal_gallery_cat($user_id);

	if ($row['cat_parent'] == $row['cat_id'])
	{
		$row['cat_parent'] = 0;
	}

	// store the parent id for this category in the row array
	$row['parent'] = ($row['cat_parent'] == 0) ? $parent_root_id : $row['cat_parent'];
	$idx = count($cats);
	$cats[$idx] = $row;
	$parents[$row['parent']][] = $idx;

	// build the tree
	$album_data  = array();
	album_build_tree($cats, $parents);

	// populate the authentication data to the album tree
	album_create_user_auth($user_id);
}

// ------------------------------------------------------------------------
// Returns the category root id for the users personal gallery
// ------------------------------------------------------------------------
function album_get_personal_root_id($user_id)
{
	global $db, $album_data;

	// ------------------------------------------------------------------------
	// if we aren't in a personal gallery cat
	// then return public root category id
	// ------------------------------------------------------------------------
	if ($user_id == ALBUM_PUBLIC_GALLERY)
	{
		return ALBUM_ROOT_CATEGORY;
	}

	//if (is_array($album_data) && count($album_data['data']) > 0)
	if (($userdata['user_id'] == $user_id) && is_array($album_data) && count($album_data['data']) > 0 && $album_data['personal'][0] == 1)
	{
		return $album_data['id'][0]; // the first array index is always root
	}
	else
	{
		$sql = "SELECT cat_id
				FROM " . ALBUM_CAT_TABLE . "
				WHERE cat_user_id = $user_id AND cat_parent = 0
				LIMIT 1";

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't get personal root id for user (id: $user_id)", "", __LINE__, __FILE__, $sql);
		}

		if ($db->sql_numrows($result) == 0)
		{
			return ALBUM_ROOT_CATEGORY;
		}

		$row = $db->sql_fetchrow($result);

		$db->sql_freeresult($result);

		return $row['cat_id'];
	}
}

// ------------------------------------------------------------------------
// Return a list of user ids and usernames which doesn't have a personal gallery
// ------------------------------------------------------------------------
function album_get_nonexisting_personal_gallery_info()
{
	global $db, $lang;

	$userinfo = array();
	$album_user_ids = ANONYMOUS;

	// ------------------------------------------------------------------------
	// since MySQL doesn't support sub selects in select statements I have to split
	// this statement up into two statements... or maybe I should try harder to do it in one ;)
	// ------------------------------------------------------------------------

	// first get the list of users who does have a personal gallery
	$sql = "SELECT DISTINCT user_id, cat_id
		FROM ". USERS_TABLE ." AS u, ". ALBUM_CAT_TABLE ." AS c
		WHERE c.cat_user_id = u.user_id
			AND c.cat_parent = 0";

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Internal Message : Could not read user infor for fake list.', '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$album_user_ids .= ($album_user_ids == '') ? $row['user_id'] : ',' . $row['user_id'];
	}

	// get user names and user ids for info list
	$sql = "SELECT user_id, username
			FROM ". USERS_TABLE . "
			WHERE user_id NOT IN (" . $album_user_ids .")";
			// AND user_id <> " . ANONYMOUS;

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Internal Message : Could not read user info for fake list.', '', __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) > 0)
	{
		while ($row = $db->sql_fetchrow($result))
		{
			$userinfo[] = $row;
		}
	}

	$db->sql_freeresult($result);

	return $userinfo;
}


// ------------------------------------------------------------------------
// Create a users personal gallery, by creating the root category, IF it
// doens't exists already
// ------------------------------------------------------------------------
function album_create_personal_gallery($user_id, $view_level, $upload_level, $options = 0)
{
	global $album_config, $template, $lang, $userdata, $db;

	if ($user_id == ALBUM_PUBLIC_GALLERY)
	{
		return false;
	}

	// ------------------------------------------------------------------------
	// Check if the personal gallery already exists
	// ------------------------------------------------------------------------
	$sql = "SELECT c.cat_id
			FROM ". ALBUM_CAT_TABLE ." AS c
			WHERE c.cat_user_id = '$user_id' AND c.cat_parent = 0
			LIMIT 1";

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
	}

	// ------------------------------------------------------------------------
	// if we didn't find any category then create the root category for the user
	// ------------------------------------------------------------------------
	if ($db->sql_numrows($result) == 0)
	{
		$db->sql_freeresult($result);

		// ------------------------------------------------------------------------
		// Build the personal gallery root name (is not directly shown in the php pages)
		// ------------------------------------------------------------------------
		$root_cat_name = sprintf($lang['Personal_Gallery_Of_User'], str_replace("\'", "''", album_get_user_name($user_id)));

		// ------------------------------------------------------------------------
		// insert the personal gallery root category
		// NOTE : the edit and delete level are set to PRIVATE !!
		// ------------------------------------------------------------------------
		$sql = "INSERT INTO ". ALBUM_CAT_TABLE ."
				(cat_title, cat_desc,
				cat_order, cat_view_level,
				cat_upload_level, cat_rate_level,
				cat_comment_level, cat_edit_level,
				cat_delete_level, cat_approval,
				cat_parent, cat_user_id)
				VALUES
				('" . $root_cat_name . "', '" . $root_cat_name . "',
				'0', '" . $view_level . "',
				'" . $upload_level . "', '0',
				'0', '" . ALBUM_PRIVATE . "',
				'" . ALBUM_PRIVATE . "', '0',
				'0', '$user_id')";

		if(!$result = $db->sql_query($sql))
		{
			//message_die(GENERAL_ERROR, 'Could not create new Personal Root Category', '', __LINE__, __FILE__, $sql);
			return false;
		}

	}
	else
	{
		$db->sql_freeresult($result);
	}


	return true;
}

// ------------------------------------------------------------------------
// build the sql sorting clause
// ------------------------------------------------------------------------
function album_get_sql_category_sort()
{
	global $album_config;

	switch ($album_config['album_category_sorting_direction'])
	{
		case 'DESC':
			$sql_sort_direction = 'DESC';
			break;
		default:
			$sql_sort_direction = 'ASC';
	}

	switch ($album_config['album_category_sorting'])
	{
		case 'cat_id':
			$sql_sort_method = 'ORDER BY cat_id ' . $sql_sort_direction;
			break;
		case 'cat_title':
			$sql_sort_method = 'ORDER BY cat_title ' . $sql_sort_direction;
			break;
		default:
			$sql_sort_method = 'ORDER BY cat_order ASC';
	}

	return $sql_sort_method;
}

// ------------------------------------------------------------------------
// move the tree up or down in the category order
// ------------------------------------------------------------------------
function album_move_tree($cat_id, $move, $user_id = ALBUM_PUBLIC_GALLERY)
{
	global $db, $album_data ;

	// if the album_tree is NOT filled then reload the data
	// this will ensure that the album IS populated with data
	if (count($album_data['data']) == 0)
	{
		album_read_tree($user_id);
	}

	// 'search' the object
	$AH_this = (isset($album_data['keys'][$cat_id])) ? $album_data['keys'][$cat_id] : ALBUM_ROOT_CATEGORY;

	// get the root or parent cat id
	$parent = ($AH_this < 0) ? ALBUM_ROOT_CATEGORY : $album_data['parent'][$AH_this];

	// renum objects of the same level and regenerate all
	$order = 0;
	$cats = array();
	$parents = array();

	// for the nuber of rows read/categories do this loop
	for ($i=0; $i < count($album_data['data']); $i++)
	{
		// ------------------------------------------------------------------------
		// if the current itetorated parent id is equal to the selected category's parent id then
		// reorder the cat_order, the way that, if the found category is the selected category
		// then move the category by the sequentual order number + 'move direction value'
		// else give it the sequentual order number...this will ensure that the selected category
		// always is moved up or down compared to its siblings
		// ------------------------------------------------------------------------
		if ($album_data['parent'][$i] == $parent)
		{
			$order = $order + 10;
			$neworder = ($i == $AH_this) ? $order + $move : $order;
			$album_data['data'][$i]['cat_order'] = $neworder;
		}

		// ------------------------------------------------------------------------
		// fill these arrays which are going to be need in building the tree
		// (see album_read_tree for similiar code)
		// ------------------------------------------------------------------------
		$idx = count($cats);
		$cats[$idx] = $album_data['data'][$i];
		$parents[ $album_data['parent'][$i] ][] = $idx;
	}

	// rebuild the tree
	$album_data  = array();
	album_build_tree($cats, $parents);

	// ------------------------------------------------------------------------
	// re-order all categories...in the database acording to the album_tree
	// is really the same things as the reorder_cat in admin/album_cat.php
	// ------------------------------------------------------------------------
	$order = 0;
	for ($i=0; $i < count($album_data['data']); $i++)
	{
		$order = $order + 10;
		$sql = "UPDATE " . ALBUM_CAT_TABLE . " SET cat_order=$order WHERE cat_id=" . $album_data['id'][$i];

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t update album category order', '', __LINE__, __FILE__, $sql);
		}
	}
}

// ------------------------------------------------------------------------
// Get the number of new pictures, from a given date, in several categories
// Return the result in an array grouped by catgory id
// ------------------------------------------------------------------------
function album_no_newest_pictures($check_date, $cats, $exclude_cat_id = 0)
{
	global $db, $lang, $album_config, $board_config, $userdata;

	$user_last_visit = $userdata['user_lastvisit'];
	$pictotalrows = array();

	if (is_null($cats))
	{
		return $pictotalrows;
	}

	// --------------------------------------------------------------------
	// NOTE : this function is weighted, meaning that days has higher
	// priority then months, and month higher priority then hours
		//
	// if $check_data = 12HMD, then we uses 12 days to calcuate
	// if $check_data = 12HM, then we uses 12 month calcuate...and so on
		// --------------------------------------------------------------------

		$check_date = strtoupper($check_date);

	// are we checking hours ?
	if (strstr($check_date,'H') != false)
	{
		$multiplier = 60 * 60;
	}

	// are we checking months ?
	if (strstr($check_date,'M') != false)
	{
		$multiplier = (30 * 24 * 60 * 60);  // in my world a month is always 30 days ;)
	}

	// are we checking weeks ?
	if (strstr($check_date,'W') != false)
	{
	 	$multiplier = (7 * 24 * 60 * 60);  // in my world a month is always 30 days ;)
	}

		// are we checking days (default) ? - yes if multiplier is zero
	if (strstr($check_date,'D') != false || $multiplier == 0)
	{
		$multiplier = (24 * 60 * 60);
	}

	// remove all the alpha characters from the string, since they aren't needed anymore
	$check_date = ereg_replace("[A-Z]+", "", trim($check_date));

	// doa final test to see if it's a valid checkm further more
	// if intval should return 0 then we will not find any images
	// that are new, except those that only are a few second old
	// but we don't want to do a trip to the database just because of that
	// the minimum is 1 hour.
	if (intval($check_date) == 0)
	{
		return $pictotalrows;
	}

	// calculate the difference from today and the desired check date (beta code !)
	$curtime = time() - ($multiplier * intval($check_date));

	//album_debug('date = %s',create_date($board_config['default_dateformat'], $curtime, $board_config['board_timezone']));

	if ($album_config['show_index_last_pic_lv'] == 1)
	{
		$sql_time = ' AND p.pic_time >= ' . $user_last_visit;
	}
	else
	{
		$sql_time = ' AND p.pic_time >= ' . $curtime;
	}
	$sql_exclude = ($exclude_cat_id != 0) ? ' AND NOT IN (' . $exclude_cat_id .')' : '';
	$sql_include = (is_array($cats)) ? implode(',', $cats) : $cats;

	$sql = 'SELECT c.cat_id, p.pic_id, COUNT(p.pic_id) AS pic_total
			FROM ' . ALBUM_TABLE . ' AS p, ' . ALBUM_CAT_TABLE . ' AS c
			WHERE c.cat_id IN ('. $sql_include  .')' .  $sql_exclude . '
			AND p.pic_cat_id = c.cat_id ' . $sql_time . '
			GROUP BY c.cat_id';

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not get the cat user id of this category ', '', __LINE__, __FILE__, $sql);
	}


	while ($row = $db->sql_fetchrow($result))
	{
		$pictotalrows[ $row['cat_id'] ] = $row['pic_total'];
	}

	$db->sql_freeresult($result);

		if (album_is_debug_enabled() == true)
		{
			album_debug('album_no_newest_pictures sql = %s', $sql);
			album_debug('$pictotalrows = %s', $pictotalrows);
		}
	return $pictotalrows;
}

// ------------------------------------------------------------------------
// Check wheter the category is a personal category or apublic one
// Returns the user id if it's a personal category, else FALSE
// ------------------------------------------------------------------------
function album_is_personal_gallery($cat_id) // for backward compability... for now
{
	return album_get_cat_user_id($cat_id);
}

function album_get_cat_user_id($cat_id)
{
	global $db, $album_data;

	if (@!array_key_exists($cat_id, $album_data['keys']) || !isset($album_data) || !is_array($album_data))
	{
		$sql = 'SELECT cat_user_id FROM ' . ALBUM_CAT_TABLE . ' WHERE cat_id IN (' . $cat_id . ') LIMIT 1';

		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get the cat user id of this category ', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		$db->sql_freeresult($result);

		return ($row['cat_user_id'] != 0) ? $row['cat_user_id'] : false;
	}
	else
	{
		$index = $album_data['keys'][$cat_id];
		return ($album_data['personal'][$cat_id] != 0) ? $album_data['data'][$index]['cat_user_id'] : false;
	}
}

// ------------------------------------------------------------------------
// Checks where user id exists or not
// ------------------------------------------------------------------------
function album_check_user_exists($user_id)
{
	if ($user_id == ALBUM_PUBLIC_GALLERY)
	{
		return true;
	}

	$tmpusername = album_get_user_name($user_id);
	return  (!empty($tmpusername)) ? true : false;
}

// ------------------------------------------------------------------------
// Returns the name of an user
// ------------------------------------------------------------------------
function album_get_user_name($user_id)
{
	global $db;

	if ($user_id == ALBUM_PUBLIC_GALLERY)
	{
		return "";
	}

	$sql = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = $user_id
			LIMIT 1";

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not get the username of this category owner', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return $row['username'];
}

// ------------------------------------------------------------------------
// Get last picture info from database in the specified categories ($cats)
// Functions is based on the SP mod by CLowN
// ------------------------------------------------------------------------
function album_get_last_pic_info($cats, &$last_pic_id)
{
	global $board_config, $lang, $db, $album_data , $album_config;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	// check whter we are running an album with CLowN's SP mod..
	// and correct album picture url
	$album_pic_url = 'album_showpage.' . PHP_EXT;

	$categories = implode(",", $cats);

	$AH_this = isset($album_data['keys'][$cats[0]]) ? $album_data['keys'][$cats[0]] : ALBUM_ROOT_CATEGORY;
	$cat = $album_data['data'][$AH_this];

	// Check Pic Approval
	// the cat array should be the 'current' category (data)...
	if (($cat['cat_approval'] == ALBUM_ADMIN) || ($cat['cat_approval'] == ALBUM_MOD))
	{
		$pic_approval_sql = 'AND p.pic_approval = 1'; // Pic Approval ON
	}
	else
	{
		$pic_approval_sql = ''; // Pic Approval OFF
	}

	// OK, we may do a query now... get last picture information
	$sql = "SELECT p.pic_id, p.pic_title, p.pic_user_id, p.pic_username, p.pic_time, p.pic_cat_id, u.user_id, u.username
			FROM " . ALBUM_TABLE . " AS p
			LEFT JOIN " . USERS_TABLE . " AS u  ON p.pic_user_id = u.user_id
			WHERE p.pic_cat_id IN (" . $categories  .") $pic_approval_sql
			ORDER BY p.pic_time DESC
			LIMIT 1";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get last pic information', '', __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) == 0)
	{
		$last_pic_id = 0;
		return '';
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	// Write the Date
	$info = create_date2($board_config['default_dateformat'], $row['pic_time'], $board_config['board_timezone']);
	$info .= '<br />';

	// Write username of last poster
	if (($row['user_id'] == ALBUM_GUEST) || ($row['username'] == ''))
	{
		$info .= ($row['pic_username'] == '') ? $lang['Guest'] : colorize_username($row['user_id']);
	}
	else
	{
		$info .= $lang['Pic_Poster'] . ': ' . colorize_username($row['user_id']);
	}

	// Write the last pic's title. Truncate it if it's too long
	if (!isset($album_config['last_pic_title_length']))
	{
		$album_config['last_pic_title_length'] = 25;
	}

	if (strlen($row['pic_title']) > $album_config['last_pic_title_length'])
	{
		$row['pic_title'] = substr($row['pic_title'], 0, $album_config['last_pic_title_length']) . '...';
	}

	$info .= '<br />' . $lang['Pic_Image'] . ': <a href="';
	$info .= ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $row['pic_id'])) . '" target="_blank">' : append_sid(album_append_uid($album_pic_url . '?pic_id=' . $row['pic_id'])) . '">' ;
	$info .= $row['pic_title'] . '</a>';

	$last_pic_id = $row['pic_id'];

	return $info;
}

// ------------------------------------------------------------------------
// Get last picture info from database in the specified category ($ss_cat_id)
// Mighty Gorgon - SlideShow
// ------------------------------------------------------------------------
function album_get_last_pic_id($ss_cat_id)
{
	global $db;
	$sql = "SELECT *
			FROM " . ALBUM_TABLE . " AS p
			WHERE p.pic_cat_id = $ss_cat_id
			ORDER BY p.pic_time DESC
			LIMIT 1";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get last pic information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	return $row['pic_id'];
}

// ------------------------------------------------------------------------
// Get first picture info from database in the specified category ($ss_cat_id)
// Mighty Gorgon - SlideShow
// ------------------------------------------------------------------------
function album_get_first_pic_id($ss_cat_id)
{
	global $db;
	$sql = "SELECT *
			FROM " . ALBUM_TABLE . " AS p
			WHERE p.pic_cat_id = $ss_cat_id
			ORDER BY p.pic_time ASC
			LIMIT 1";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get last pic information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	return $row['pic_id'];
}

// ------------------------------------------------------------------------
// Returns the number of pictures in the specified category ($ss_cat_id)
// Mighty Gorgon - SlideShow
// ------------------------------------------------------------------------
function album_get_total_pic_cat($ss_cat_id)
{
	global $db;

	$sql = "SELECT COUNT(p.pic_id) AS count
			FROM " . ALBUM_TABLE . " AS p
			WHERE p.pic_cat_id = $ss_cat_id";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get total number of pictures for an album category", "", __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return intval($row['count']);
}

// ------------------------------------------------------------------------
// Get last comment information from database in the specified categories
// ($cats)
// Functions is based on the SP mod by CLowN
// ------------------------------------------------------------------------
function album_get_last_comment_info($cats)
{
	global $board_config, $lang, $db, $album_data;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$album_pic_url = 'album_showpage.' . PHP_EXT;

	$categories = implode(",", $cats);

	if ($categories == '')
	{
		return '';
	}

	// get last comment information, and user, comment and pic informations
	$sql = "SELECT c.comment_pic_id, c.comment_user_id, c.comment_username, c.comment_time, u.user_id, u.username, a.pic_id, a.pic_cat_id, a.pic_title
		FROM " . ALBUM_COMMENT_TABLE . " AS c
		LEFT JOIN " . USERS_TABLE . " AS u ON c.comment_user_id = u.user_id
		LEFT JOIN " . ALBUM_TABLE . " AS a ON c.comment_pic_id = a.pic_id
		WHERE a.pic_cat_id IN (" . $categories . ")
		ORDER BY c.comment_time DESC
		LIMIT 1";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get last comment information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	// last comment
	if ($db->sql_numrows($result) == 0)
	{
		return '';
	}

	$db->sql_freeresult($result);

	$info = create_date2($board_config['default_dateformat'], $row['comment_time'], $board_config['board_timezone']);
	$info .= '<br />' . $lang['Pic_Poster'] . ': ';

	if (($row['user_id'] == ALBUM_GUEST) || ($row['comment_username'] == ''))
	{
		$info .= ($row['comment_username'] == '') ? $lang['Guest'] : colorize_username($row['user_id']);
	}
	else
	{
		$info .= colorize_username($row['user_id']);
	}

	$info .= '<br />' . $lang['Pic_Image'] . ': <a href="' . append_sid(album_append_uid($album_pic_url . '?pic_id=' . $row['pic_id'])) . '">' . $row['pic_title'] . '</a>';

	return $info;
}

// ------------------------------------------------------------------------
// Get moderator information for the the category
// ------------------------------------------------------------------------
function album_get_moderator_info($cat)
{
	global $lang, $db;

	// Most of this code is copyrighted by Smartor
	// Modifications are done by IdleVoid
	$moderators = '';
	$grouprows = array();

	// We have usergroup_ID, now we need usergroup name
	$sql = "SELECT group_id, group_name
		FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> 1
			AND group_type <> " . GROUP_HIDDEN . "
			AND group_id IN (" . $cat['cat_moderator_groups'] . ")
		ORDER BY group_name ASC";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain usergroups data', '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$grouprows[] = $row;
	}

	$db->sql_freeresult($result);

	if (count($grouprows) > 0)
	{
		for ($j = 0; $j < count($grouprows); $j++)
		{
			$group_link = '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $grouprows[$j]['group_id']) . '">' . $grouprows[$j]['group_name'] . '</a>';
			$moderators .= ($moderators == '') ? $group_link : ', ' . $group_link;
		}
	}
	return $moderators;
}

// ------------------------------------------------------------------------
// Returns the number of comments for current category and it subs
// (if cat is anarray)
// ------------------------------------------------------------------------
function album_get_comment_count($cat)
{
	global $db;

	if (is_array($cat))
	{
		$sql_where = " WHERE pic_cat_id IN (". implode(",", $cat) .")";
	}
	else
	{
		$sql_where = " WHERE pic_cat_id = '" . $cat  ."'";
	}

	$sql = "SELECT COUNT(comment_id) AS comment_count
			FROM " . ALBUM_COMMENT_TABLE . "
			LEFT JOIN " . ALBUM_TABLE . " ON comment_pic_id = pic_id " . $sql_where;

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get category comment count information', '', __LINE__, __FILE__, $sql);
	}
	$comment_row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return intval($comment_row['comment_count']);
}

/*
Synchronize total pics for each cat.
*/
function synchronize_all_cat_pics_counter()
{
	global $db, $lang;
	$sql = "SELECT cat_id FROM " . ALBUM_CAT_TABLE;
	$result = $db->sql_query($sql);
	if (!$result)
	{
		message_die('Couldn\'t query album categories!', __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		synchronize_cat_pics_counter($row['cat_id']);
	}
	message_die(GENERAL_MESSAGE, $lang['Cat_Pics_Synchronized']);
	return true;
}

/*
Count pictures in cat.
*/
function synchronize_cat_pics_counter($cat_id)
{
	global $db;
	$sql = "SELECT COUNT(pic_id) AS count
			FROM " . ALBUM_TABLE . " AS p
			WHERE pic_cat_id = " . $cat_id;
	$result = $db->sql_query($sql);
	if (!$result)
	{
		message_die('Couldn\'t query album pics!', __LINE__, __FILE__, $sql);
	}
	$pics_count = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$pics_count = intval($row['count']);
	}
	$db->sql_freeresult($result);

	$sql = "UPDATE " . ALBUM_CAT_TABLE . "
		SET cat_pics = " . $pics_count . "
		WHERE cat_id = " . $cat_id;
	$result = $db->sql_query($sql);
	if (!$result)
	{
		message_die('Couldn\'t update album categories!', __LINE__, __FILE__, $sql);
	}

	return true;
}

// ------------------------------------------------------------------------
// Returns the number of pictures for current catgory and it subs
// ------------------------------------------------------------------------
function album_get_total_pics($cats)
{
	global $db;

	$sql_where = " WHERE cat_id " . ((is_array($cats)) ? "IN (". implode(",", $cats) .")" : "= " . $cats);

	$sql = "SELECT cat_id, cat_pics
			FROM " . ALBUM_CAT_TABLE . "
			" . $sql_where;

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get total number of pictures for album categories and sub categories", "", __LINE__, __FILE__, $sql);
	}

	$pics_count = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$pics_count = $pics_count + intval($row['cat_pics']);
	}
	$db->sql_freeresult($result);

	return $pics_count;
}

// ------------------------------------------------------------------------
// Returns the number of pictures for current catgory and it subs
// ------------------------------------------------------------------------
function album_get_total_pics_old($cats)
{
	global $db;

	$sql_where = " WHERE c.cat_id " . ((is_array($cats)) ? "IN (". implode(",", $cats) .")" : "= " . $cats);

	$sql = "SELECT COUNT(p.pic_id) AS count
			FROM " . ALBUM_CAT_TABLE . " AS c
			LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id " . $sql_where;

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get total number of pictures for album categories and sub categories", "", __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) == 0)
	{
		return 0;
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return intval($row['count']);
}

// ------------------------------------------------------------------------
// Builds the table showing the pictures in rows and columns, default
// ------------------------------------------------------------------------
function album_build_picture_table($user_id, $cat_ids, $AH_thiscat, $auth_data, $start, $sort_method, $sort_order, $total_pics)
{
	global $board_config, $album_data, $album_config, $template, $lang, $userdata, $db;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$viewmode = (strpos($cat_ids, ',') != false) ? '&mode=' . ALBUM_VIEW_ALL : '';

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;
	$album_rate_pic_url = $album_show_pic_url;
	$album_comment_pic_url = $album_show_pic_url;

	if (intval($cat_ids) == album_get_personal_root_id($user_id) && $user_id != ALBUM_PUBLIC_GALLERY)
	{
		$album_pagination_page_url = 'album.' . PHP_EXT;
	}
	else
	{
		$album_pagination_page_url = 'album_cat.' . PHP_EXT;
	}

	$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];

	$limit_sql = ($start == 0) ? $pics_per_page : $start . ',' . $pics_per_page;

	$pic_approval_sql = 'AND p.pic_approval = 1';

	//if (($AH_thiscat['cat_approval'] != ALBUM_USER) || (($album_config['personal_pics_approval'] == 1) && ($AH_thiscat['cat_user_id'] > 0)))
	if (($AH_thiscat['cat_approval'] != ALBUM_USER) || (($album_config['personal_pics_approval'] == 1) && (album_get_cat_user_id($cat_ids) != false)))
	{
		if(($userdata['user_level'] == ADMIN) || (($auth_data['moderator'] == 1) && ($AH_thiscat['cat_approval'] == ALBUM_MOD)))
		{
			$pic_approval_sql = '';
		}
	}

	$sort_methods_array = array('pic_time', 'pic_title', 'username', 'pic_view_count', 'rating', 'comments', 'new_comment');
	$sort_method = in_array($sort_method, $sort_methods_array) ? $sort_method : $album_config['sort_method'];

	switch ($sort_method)
	{
		case 'pic_time':
			$sort_method_sql = 'p.pic_time';
			break;
		case 'pic_title':
			$sort_method_sql = 'p.pic_title';
			break;
		case 'username':
			$sort_method_sql = 'u.username';
			break;
		case 'pic_view_count':
			$sort_method_sql = 'p.pic_view_count';
			break;
		case 'rating':
			$sort_method_sql = 'rating';
			break;
		case 'comments':
			$sort_method_sql = 'comments';
			break;
		case 'new_comment':
			$sort_method_sql = 'new_comment';
			break;
		default:
			$sort_method_sql = 'p.pic_id';
	}

	$sql = "SELECT ct.cat_user_id, ct.cat_id, ct.cat_title, p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments, MAX(c.comment_id) as new_comment
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
				LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
			WHERE p.pic_cat_id IN ($cat_ids) $pic_approval_sql
			GROUP BY p.pic_id
			ORDER BY $sort_method_sql $sort_order
			LIMIT $limit_sql";

	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query pics information', '', __LINE__, __FILE__, $sql);
	}

	$picrow = array();

	while($row = $db->sql_fetchrow($result))
	{
		$picrow[] = $row;
	}

	$tot_unapproved = 0;
	for ($i = 0 ; $i < count($picrow); $i++)
	{
		if ($picrow[$i]['pic_approval'] == 0)
		{
			$tot_unapproved++;
		}
	}

	$db->sql_freeresult($result);

	$template->assign_block_vars('index_pics_block', array());
	$template->assign_block_vars('index_pics_block.enable_gallery_title', array());

	for ($i = 0; $i < count($picrow); $i += $album_config['cols_per_page'])
	{
		$template->assign_block_vars('index_pics_block.picrow', array());

		for ($j = $i; $j < ($i + $album_config['cols_per_page']); $j++)
		{
			if($j >= count($picrow))
			{
				$template->assign_block_vars('index_pics_block.picrow.nopiccol', array());
				$template->assign_block_vars('index_pics_block.picrow.picnodetail', array());
				continue;
				//break;
			}
			//if (($AH_thiscat['cat_approval'] != ALBUM_USER) || (($album_config['personal_pics_approval'] == 1) && ($AH_thiscat['cat_user_id'] > 0)))
			if (($AH_thiscat['cat_approval'] != ALBUM_USER) || (($album_config['personal_pics_approval'] == 1) && (album_get_cat_user_id($cat_ids) != false)))
			{
				if(($userdata['user_level'] == ADMIN) || (($auth_data['moderator'] == 1) && ($AH_thiscat['cat_approval'] == ALBUM_MOD)))
				{
					$approval_mode = ($picrow[$j]['pic_approval'] == 0) ? 'approval' : 'unapproval';

					$approval_link = '<a href="'. append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=' . $approval_mode . '&amp;pic_id=' . $picrow[$j]['pic_id'])) . '">';

					$approval_link .= ($picrow[$j]['pic_approval'] == 0) ? '<b>' . $lang['Approve'] . '</b>' : $lang['Unapprove'];

					$approval_link .= '</a>';
				}
			}

			$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id']));
			if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
			{
				$thumbnail_file = picture_quick_thumb($picrow[$j]['pic_filename'], $picrow[$j]['pic_thumbnail'], $thumbnail_file);
			}
			if ($album_config['lb_preview'] == 0)
			{
				$pic_preview = '';
			}
			else
			{
				$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id'])) . '\',\'' . addslashes($picrow[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
			}


			$template->assign_block_vars('index_pics_block.picrow.piccol', array(
				'U_PIC' => ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id'])) : append_sid(album_append_uid($album_show_pic_url . '?pic_id='. $picrow[$j]['pic_id'] . '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order)),
				'THUMBNAIL' => $thumbnail_file,
				'PIC_PREVIEW' => $pic_preview,
				'PIC_TITLE' => htmlspecialchars($picrow[$j]['pic_title']),
				'DESC' => htmlspecialchars($picrow[$j]['pic_desc']),
				'APPROVAL' => $approval_link,
				)
			);

			if(($picrow[$j]['user_id'] == ALBUM_GUEST) || ($picrow[$j]['username'] == ''))
			{
				$pic_poster = ($picrow[$j]['pic_username'] == '') ? $lang['Guest'] : colorize_username($picrow[$j]['user_id']);
			}
			else
			{
				$pic_poster = colorize_username($picrow[$j]['user_id']);
			}

			$image_rating = ImageRating($picrow[$j]['rating']);
			$image_rating_link_style = ($image_rating == $lang['Not_rated']) ? '' : 'style="text-decoration: none;"';

			$image_comment = ($picrow[$j]['comments'] == 0) ? $lang['Not_commented'] : $picrow[$j]['comments'];

			$edit_rights = (($auth_data['edit'] && ($picrow[$j]['pic_user_id'] == $userdata['user_id'])) || ($auth_data['moderator'] && ($AH_thiscat['cat_edit_level'] != ALBUM_ADMIN)) || ($userdata['user_level'] == ADMIN)) ? true : false;

			$delete_rights = (($auth_data['delete'] && ($picrow[$j]['pic_user_id'] == $userdata['user_id'])) || ($auth_data['moderator'] && ($AH_thiscat['cat_delete_level'] != ALBUM_ADMIN)) || ($userdata['user_level'] == ADMIN)) ? true : false;

			$template->assign_block_vars('index_pics_block.picrow.pic_detail', array(
				'PIC_ID' => $picrow[$j]['pic_id'],
				'PIC_TITLE' => htmlspecialchars($picrow[$j]['pic_title']),
				//'TITLE' => '<a href = "' . $album_show_pic_url . '?pic_id=' . $picrow[$j]['pic_id'] . '">' . $picrow[$j]['pic_title'] . '</a>',
				'TITLE' => '<a href = "' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $picrow[$j]['pic_id'])) . '">' . htmlspecialchars($picrow[$j]['pic_title']) . '</a>',
				'POSTER' => $pic_poster,
				'TIME' => create_date2($board_config['default_dateformat'], $picrow[$j]['pic_time'], $board_config['board_timezone']),

				'VIEW' => $picrow[$j]['pic_view_count'],

				'RATING' => ($album_config['rate'] == 1) ?('<a href="' . append_sid(album_append_uid($album_rate_pic_url .'?pic_id='. $picrow[$j]['pic_id'])) . '"' . $image_rating_link_style . '>' . $lang['Rating'] . '</a>: ' . $image_rating . '<br />') : '',

				'COMMENTS' => ($album_config['comment'] == 1) ? ('<a href="' . append_sid(album_append_uid($album_comment_pic_url .'?pic_id='. $picrow[$j]['pic_id'])) . '">' . $lang['Comments'] . '</a>: ' . $image_comment . '<br />') : '',

				'EDIT' =>  ($edit_rights) ? '<a href="'. append_sid(album_append_uid('album_edit.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id'])) . '">' . $lang['Edit_pic'] . '</a>' : '',

				'DELETE' => ($delete_rights) ? '<a href="'. append_sid(album_append_uid('album_delete.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id'])) . '">' . $lang['Delete_pic'] . '</a>' : '',

				'MOVE' => ($auth_data['moderator']) ? '<a href="'. append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=move&amp;pic_id=' . $picrow[$j]['pic_id'])) .'">'. $lang['Move'] .'</a>' : '',

				'COPY' => ($auth_data['moderator']) ? '<a href="'. append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=copy&amp;pic_id=' . $picrow[$j]['pic_id'])) .'">'. $lang['Copy'] .'</a>' : '',

				'LOCK' => ($auth_data['moderator']) ? '<a href="'. append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=' . (($picrow[$j]['pic_lock'] == 0) ? 'lock' : 'unlock') . '&amp;pic_id=' . $picrow[$j]['pic_id'])) .'">'. (($picrow[$j]['pic_lock'] == 0) ? $lang['Lock'] : $lang['Unlock']) .'</a>' : '',

				'IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . decode_ip($picrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($picrow[$j]['pic_user_ip']) .'</a><br />' : '',

				//'AVATAR_PIC' => (($album_config['personal_allow_avatar_gallery'] == 1) && ($userdata['user_id'] == $picrow[$j]['pic_user_id'])) ? '<br /><a href="'. append_sid('album_avatar.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id']) . '">' . $lang['Avatar_Set'] . '</a>' : '',

				'AVATAR_PIC' => (($album_config['personal_allow_avatar_gallery'] == 1) && ($userdata['user_id'] == $picrow[$j]['pic_user_id']) && ($picrow[$j]['cat_user_id'] != 0)) ? '<br /><a href="'. append_sid('album_avatar.' . PHP_EXT . '?pic_id=' . $picrow[$j]['pic_id']) . '">' . $lang['Avatar_Set'] . '</a>' : '',

				'IMG_BBCODE' => (($userdata['user_level'] == ADMIN) || ($userdata['user_id'] == $picrow[$j]['pic_user_id'])) ? '<br /><a href="javasript://" OnClick="window.clipboardData.setData(\'Text\', \'[albumimg]' . $picrow[$j]['pic_id'] . '[/albumimg]\'); return false;">' . $lang['BBCode_Copy'] . '</a>' : ''
				)
			);

			// Mighty Gorgon - Slideshow - BEGIN
			if ($album_config['show_slideshow'] == 1)
			{
				$last_pic_id = $picrow[$j]['pic_id'];
				$slideshow_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $last_pic_id . '&amp;slideshow=5'));
				$slideshow_link_full = '&nbsp;[<a href="' . $slideshow_link . '">' . $lang['Slideshow'] . '</a>]&nbsp;';
			}
			else
			{
				$slideshow_link_full = '';
			}
			// Mighty Gorgon - Slideshow - END

			if (is_array($cats))
			{
				// is a personal category that the picture belongs to AND
				// is it the main category in the personal gallery ?
				if ($picrow[$j]['cat_user_id'] != 0 && $picrow[$j]['cat_id'] == album_get_personal_root_id($picrow[$j]['cat_user_id']))
				{
					$album_page_url = 'album.' . PHP_EXT;
				}
				else
				{
					$album_page_url = 'album_cat.' . PHP_EXT;
				}

				$image_cat_url = append_sid(album_append_uid($album_page_url . '?cat_id=' . $picrow[$j]['cat_id'] . '&user_id=' . $picrow[$j]['cat_user_id']));

				$template->assign_block_vars('index_pics_block.picrow.pic_detail.cats', array(
					'CATEGORY' => $picrow[$j]['cat_title'],
					'U_PIC_CAT' => $image_cat_url
					)
				);
			}
		}
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(append_sid(album_append_uid($album_pagination_page_url . '?cat_id=' . intval($cat_ids) . '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order . $viewmode)), $total_pics, $pics_per_page, $start),
		'SLIDESHOW' => $slideshow_link_full,
		$waiting = ($tot_unapproved == 0) ? "" : $tot_unapproved . $lang['Waiting'],
		'WAITING' => ($userdata['user_level'] == ADMIN) ? (($tot_unapproved == 0) ? '&nbsp;' : '<br /><span class="gensmall"><b>' . $tot_unapproved . $lang['Waiting'] . '</b></span>') : '&nbsp;',
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $pics_per_page) + 1), ceil($total_pics / $pics_per_page))
		)
	);
}


// ------------------------------------------------------------------------
// Creates the table for recent pictures
// Based on CLowN's Super Charged Pack
// ------------------------------------------------------------------------
function album_build_recent_pics($cats)
{
	global $db, $board_config, $album_config, $template, $lang, $profiledata;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;
	$album_rate_pic_url = $album_show_pic_url;
	$album_comment_pic_url = $album_show_pic_url;
	$limit_sql = $album_config['img_cols'] * $album_config['img_rows'];
	$cols_per_page = $album_config['img_cols'];

####################################################################

	$where = (!empty($_GET['user_id'])) ? "WHERE p.pic_cat_id IN ($cats) AND (p.pic_approval = 1 OR ct.cat_approval = 0)" : "WHERE (p.pic_approval = 1 OR ct.cat_approval = 0)";

##############################################################

	if (!empty($cats))
	{
		$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
				FROM ". ALBUM_TABLE ." AS p
					LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
					LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
					LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
					LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
					" . $where . "
				GROUP BY p.pic_id
				ORDER BY pic_time DESC
				LIMIT $limit_sql";
//				WHERE p.pic_cat_id IN ($cats) AND (p.pic_approval = 1 OR ct.cat_approval = 0) //// ".$where."
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query recent pics information', '', __LINE__, __FILE__, $sql);
		}

		$recentrow = array();

		while($row = $db->sql_fetchrow($result))
		{
			$recentrow[] = $row;
		}

		$db->sql_freeresult($result);

		$template->assign_block_vars('recent_pics_block', array());

		if (count($recentrow) > 0)
		{
			for ($i = 0; $i < count($recentrow); $i += $cols_per_page)
			{
				$template->assign_block_vars('recent_pics_block.recent_pics', array());

				for ($j = $i; $j < ($i + $cols_per_page); $j++)
				{
					if($j >= count($recentrow))
					{
						break;
					}

					$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id']));
					if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
					{
						$thumbnail_file = picture_quick_thumb($recentrow[$j]['pic_filename'], $recentrow[$j]['pic_thumbnail'], $thumbnail_file);
					}
					if ($album_config['lb_preview'] == 0)
					{
						$pic_preview = '';
					}
					else
					{
						$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id'])) . '\',\'' . addslashes($recentrow[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
					}

					$template->assign_block_vars('recent_pics_block.recent_pics.recent_col', array(
						'U_PIC' => ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id'])) : append_sid(album_append_uid($album_show_pic_url. '?pic_id='. $recentrow[$j]['pic_id'])),
						'THUMBNAIL' => $thumbnail_file,
						'PIC_PREVIEW' => $pic_preview,
						'PIC_TITLE' => htmlspecialchars($recentrow[$j]['pic_title']),
						'DESC' => htmlspecialchars($recentrow[$j]['pic_desc'])
						)
					);

					if(($recentrow[$j]['user_id'] == ALBUM_GUEST) || ($recentrow[$j]['username'] == ''))
					{
						$recent_poster = ($recentrow[$j]['pic_username'] == '') ? $lang['Guest'] : colorize_username($recentrow[$j]['user_id']);
					}
					else
					{
						$recent_poster = colorize_username($recentrow[$j]['user_id']);
					}

					$image_rating = ImageRating($recentrow[$j]['rating']);
					$image_rating_link_style = ($image_rating == $lang['Not_rated']) ? '' : 'style="text-decoration: none;"';

					$image_comment = ($recentrow[$j]['comments'] == 0) ? $lang['Not_commented'] : $recentrow[$j]['comments'];

					$template->assign_block_vars('recent_pics_block.recent_pics.recent_detail', array(
						'PIC_ID' => $recentrow[$j]['pic_id'],
						'PIC_TITLE' => htmlspecialchars($recentrow[$j]['pic_title']),
						'TITLE' => '<a href = "' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $recentrow[$j]['pic_id'])) . '">' . htmlspecialchars($recentrow[$j]['pic_title']) . '</a>',
						'POSTER' => $recent_poster,
						'TIME' => create_date2($board_config['default_dateformat'], $recentrow[$j]['pic_time'], $board_config['board_timezone']),

						'VIEW' => $recentrow[$j]['pic_view_count'],

						'RATING' => ($album_config['rate'] == 1) ? ('<a href="'. append_sid(album_append_uid($album_rate_pic_url .'?pic_id='. $recentrow[$j]['pic_id'])) . '" ' . $image_rating_link_style .'>' . $lang['Rating'] . '</a>: ' . $image_rating . '<br />') : '',

						'COMMENTS' => ($album_config['comment'] == 1) ? ('<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id'])) . '">' . $lang['Comments'] . '</a>: ' . $image_comment . '<br />') : '',

						'IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . decode_ip($recentrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($recentrow[$j]['pic_user_ip']) .'</a><br />' : ''
						)
					);
				}
			}
		}
		else
		{
			// No Pics Found
			$template->assign_block_vars('recent_pics_block.no_pics', array());
		}
	}

	if (empty($cats))
	{
		// No Cats Found
		$template->assign_block_vars('recent_pics_block', array());
		$template->assign_block_vars('recent_pics_block.no_pics', array());
	}
}

// ------------------------------------------------------------------------
// Creates the table for higest rated pictures
// Based on CLowN's Super Charged Pack
// ------------------------------------------------------------------------
function album_build_highest_rated_pics($cats)
{
	global $db, $board_config, $album_config, $template, $lang;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;
	$album_rate_pic_url = $album_show_pic_url;

	$limit_sql = $album_config['img_cols'] * $album_config['img_rows'];
	$cols_per_page = $album_config['img_cols'];

	if (!empty($cats))
	{
		$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id IN ($cats)
				AND (p.pic_approval = 1 OR ct.cat_approval = 0)
			GROUP BY p.pic_id
			ORDER BY rating DESC, RAND()
			LIMIT $limit_sql";

		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query highest rated pics information', '', __LINE__, __FILE__, $sql);
		}

		$highestrow = array();

		while($row = $db->sql_fetchrow($result))
		{
			$highestrow[] = $row;
		}

		$db->sql_freeresult($result);

		$template->assign_block_vars('highest_pics_block', array());

		if (count($highestrow) > 0)
		{
			$rated_images = 0;
			for ($i = 0; $i < count($highestrow); $i += $cols_per_page)
			{
				$template->assign_block_vars('highest_pics_block.highest_pics', array());

				for ($j = $i; $j < ($i + $cols_per_page); $j++)
				{
					if($j >= count($highestrow))
					{
						break;
					}

					$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $highestrow[$j]['pic_id']));
					if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
					{
						$thumbnail_file = picture_quick_thumb($highestrow[$j]['pic_filename'], $highestrow[$j]['pic_thumbnail'], $thumbnail_file);
					}

					if ($album_config['lb_preview'] == 0)
					{
						$pic_preview = '';
					}
					else
					{
						$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $highestrow[$j]['pic_id'])) . '\',\'' . addslashes($highestrow[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
					}

					if ($highestrow[$j]['rating'] > 0)
					{
						$template->assign_block_vars('highest_pics_block.highest_pics.highest_col', array(
							'U_PIC' => ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $highestrow[$j]['pic_id'])) : append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $highestrow[$j]['pic_id'])),
							'THUMBNAIL' => $thumbnail_file,
							'PIC_PREVIEW' => $pic_preview,
							'PIC_TITLE' => htmlspecialchars($highestrow[$j]['pic_title']),
							'DESC' => htmlspecialchars($highestrow[$j]['pic_desc'])
							)
						);
					}

					if(($highestrow[$j]['user_id'] == ALBUM_GUEST) || ($highestrow[$j]['username'] == ''))
					{
						$highest_poster = ($highestrow[$j]['pic_username'] == '') ? $lang['Guest'] : colorize_username($highestrow[$j]['user_id']);
					}
					else
					{
						$highest_poster = colorize_username($highestrow[$j]['user_id']);
					}

					$image_rating = ImageRating($highestrow[$j]['rating']);
					$image_rating_link_style = ($image_rating == $lang['Not_rated']) ? '' : 'style="text-decoration: none;"';

					$image_comment = ($highestrow[$j]['comments'] == 0) ? $lang['Not_commented'] : $highestrow[$j]['comments'];

					if ($highestrow[$j]['rating'] > 0)
					{
						$rated_images++;
						$template->assign_block_vars('highest_pics_block.highest_pics.highest_detail', array(
							'PIC_ID' => $highestrow[$j]['pic_id'],
							'PIC_TITLE' => htmlspecialchars($highestrow[$j]['pic_title']),
							'H_TITLE' => '<a href = "' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $highestrow[$j]['pic_id'])) . '">' . htmlspecialchars($highestrow[$j]['pic_title']) . '</a>',
							'H_POSTER' => $highest_poster,
							'H_TIME' => create_date2($board_config['default_dateformat'], $highestrow[$j]['pic_time'], $board_config['board_timezone']),

							'H_VIEW' => $highestrow[$j]['pic_view_count'],

							'H_RATING' => ($album_config['rate'] == 1) ? ('<a href="'. append_sid(album_append_uid($album_rate_pic_url .'?pic_id='. $highestrow[$j]['pic_id'])) . '" ' . $image_rating_link_style .'>' . $lang['Rating'] . '</a>: ' . $image_rating . '<br />') : '',

							'H_COMMENTS' => ($album_config['comment'] == 1) ? ('<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $highestrow[$j]['pic_id'])) . '">' . $lang['Comments'] . '</a>: ' . $image_comment . '<br />') : '',

							'H_IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . decode_ip($highestrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($highestrow[$j]['pic_user_ip']) .'</a><br />' : ''
							)
						);
					}
				}
			}
		}
		elseif ($rated_images == 0)
		{
			// No Pics Found
			$template->assign_block_vars('highest_pics_block.no_pics', array());
		}
		else
		{
			// No Pics Found
			$template->assign_block_vars('highest_pics_block.no_pics', array());
		}
	}

	if (empty($cats))
	{
		// No Cats Found
		$template->assign_block_vars('highest_pics_block', array());
		$template->assign_block_vars('highest_pics_block.no_pics', array());
	}
}


// ------------------------------------------------------------------------
// Creates the table for most viewed pictures
// Based on CLowN's Super Charged Pack
// ------------------------------------------------------------------------
function album_build_most_viewed_pics($cats)
{
	global $db, $board_config, $album_config, $template, $lang;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;
	$album_rate_pic_url = $album_show_pic_url;
	$limit_sql = $album_config['img_cols'] * $album_config['img_rows'];
	$cols_per_page = $album_config['img_cols'];

	if (!empty($cats))
	{
		$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id IN ($cats) AND (p.pic_approval = 1 OR ct.cat_approval = 0)
			GROUP BY p.pic_id
			ORDER BY p.pic_view_count DESC
			LIMIT $limit_sql";

		//if(!($result = $db->sql_query($sql, false, 'album_pics_')))
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query most viewed pics information', '', __LINE__, __FILE__, $sql);
		}

		$mostviewed = array();

		while($row = $db->sql_fetchrow($result))
		{
			$mostviewed[] = $row;
		}

		$db->sql_freeresult($result);

		$template->assign_block_vars('mostviewed_pics_block', array());

		if (count($mostviewed) > 0)
		{
			for ($i = 0; $i < count($mostviewed); $i += $cols_per_page)
			{
				$template->assign_block_vars('mostviewed_pics_block.mostviewed_pics', array());

				for ($j = $i; $j < ($i + $cols_per_page); $j++)
				{
					if($j >= count($mostviewed))
					{
						break;
					}

					$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $mostviewed[$j]['pic_id']));
					if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
					{
						$thumbnail_file = picture_quick_thumb($mostviewed[$j]['pic_filename'], $mostviewed[$j]['pic_thumbnail'], $thumbnail_file);
					}

					if ($album_config['lb_preview'] == 0)
					{
						$pic_preview = '';
					}
					else
					{
						$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $mostviewed[$j]['pic_id'])) . '\',\'' . addslashes($mostviewed[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
					}

					$template->assign_block_vars('mostviewed_pics_block.mostviewed_pics.mostviewed_col', array(
						'U_PIC' => ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $mostviewed[$j]['pic_id'])) : append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $mostviewed[$j]['pic_id'])),
						'THUMBNAIL' => $thumbnail_file,
						'PIC_PREVIEW' => $pic_preview,
						'PIC_TITLE' => htmlspecialchars($mostviewed[$j]['pic_title']),
						'DESC' => htmlspecialchars($mostviewed[$j]['pic_desc'])
						)
					);

					if(($mostviewed[$j]['user_id'] == ALBUM_GUEST) || ($mostviewed[$j]['username'] == ''))
					{
						$mostviewed_poster = ($mostviewed[$j]['pic_username'] == '') ? $lang['Guest'] : colorize_username($mostviewed[$j]['user_id']);
					}
					else
					{
						$mostviewed_poster = colorize_username($mostviewed[$j]['user_id']);
					}

					$image_rating = ImageRating($mostviewed[$j]['rating']);
					$image_rating_link_style = ($image_rating == $lang['Not_rated']) ? '' : 'style="text-decoration: none;"';

					$image_comment = ($mostviewed[$j]['comments'] == 0) ? $lang['Not_commented'] : $mostviewed[$j]['comments'];

					$template->assign_block_vars('mostviewed_pics_block.mostviewed_pics.mostviewed_detail', array(
						'PIC_ID' => $mostviewed[$j]['pic_id'],
						'PIC_TITLE' => htmlspecialchars($mostviewed[$j]['pic_title']),
						'H_TITLE' => '<a href = "' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $mostviewed[$j]['pic_id'])) . '">' . htmlspecialchars($mostviewed[$j]['pic_title']) . '</a>',
						'H_POSTER' => $mostviewed_poster,
						'H_TIME' => create_date2($board_config['default_dateformat'], $mostviewed[$j]['pic_time'], $board_config['board_timezone']),

						'H_VIEW' => $mostviewed[$j]['pic_view_count'],

						'H_RATING' => ($album_config['rate'] == 1) ? ('<a href="'. append_sid(album_append_uid($album_rate_pic_url .'?pic_id='. $mostviewed[$j]['pic_id'])) . '" ' . $image_rating_link_style .'>' . $lang['Rating'] . '</a>: ' . $image_rating . '<br />') : '',

						'H_COMMENTS' => ($album_config['comment'] == 1) ? ('<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $mostviewed[$j]['pic_id'])) . '">' . $lang['Comments'] . '</a>: ' . $image_comment . '<br />') : '',

						'H_IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . decode_ip($mostviewed[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($mostviewed[$j]['pic_user_ip']) .'</a><br />' : ''
						)
					);
			 	}
			}
		}
		else
		{
			// No Pics Found
			$template->assign_block_vars('mostviewed_pics_block.no_pics', array());
		}
	}

	if (empty($cats))
	{
		// No Cats Found
		$template->assign_block_vars('mostviewed_pics_block', array());
		$template->assign_block_vars('mostviewed_pics_block.no_pics', array());
	}
}

// ------------------------------------------------------------------------
// Creates the table for random pictures
// Based on CLowN's Super Charged Pack
// ------------------------------------------------------------------------
function album_build_random_pics($cats)
{
	global $db, $board_config, $album_config, $template, $lang;
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;
	$album_rate_pic_url = $album_show_pic_url;
	$limit_sql = $album_config['img_cols'] * $album_config['img_rows'];
	$cols_per_page = $album_config['img_cols'];

	if (!empty($cats))
	{
		$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
				FROM ". ALBUM_TABLE ." AS p
					LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
					LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
					LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
					LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
				WHERE p.pic_cat_id IN ($cats) AND (p.pic_approval = 1 OR ct.cat_approval = 0)
				GROUP BY p.pic_id
				ORDER BY RAND()
				LIMIT $limit_sql";

		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query rand pics information', '', __LINE__, __FILE__, $sql);
		}

		$randrow = array();

		while($row = $db->sql_fetchrow($result))
		{
			$randrow[] = $row;
		}

		$db->sql_freeresult($result);

		$template->assign_block_vars('random_pics_block', array());

		if (count($randrow) > 0)
		{
			for ($i = 0; $i < count($randrow); $i += $cols_per_page)
			{
				$template->assign_block_vars('random_pics_block.rand_pics', array());

				for ($j = $i; $j < ($i + $cols_per_page); $j++)
				{
					if($j >= count($randrow))
					{
						break;
					}

					$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $randrow[$j]['pic_id']));
					if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
					{
						$thumbnail_file = picture_quick_thumb($randrow[$j]['pic_filename'], $randrow[$j]['pic_thumbnail'], $thumbnail_file);
					}

					if ($album_config['lb_preview'] == 0)
					{
						$pic_preview = '';
					}
					else
					{
						$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $randrow[$j]['pic_id'])) . '\',\'' . addslashes($randrow[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
					}

					$template->assign_block_vars('random_pics_block.rand_pics.rand_col', array(
						'U_PIC' => ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $randrow[$j]['pic_id'])) : append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $randrow[$j]['pic_id'])),
						'THUMBNAIL' => $thumbnail_file,
						'PIC_PREVIEW' => $pic_preview,
						'PIC_TITLE' => htmlspecialchars($randrow[$j]['pic_title']),
						'DESC' => htmlspecialchars($randrow[$j]['pic_desc'])
						)
					);

					if(($randrow[$j]['user_id'] == ALBUM_GUEST) || ($randrow[$j]['username'] == ''))
					{
						$rand_poster = ($randrow[$j]['pic_username'] == '') ? $lang['Guest'] : colorize_username($randrow[$j]['user_id']);
					}
					else
					{
						$rand_poster = colorize_username($randrow[$j]['user_id']);
					}

					$image_rating = ImageRating($randrow[$j]['rating']);
					$image_rating_link_style = ($image_rating == $lang['Not_rated']) ? '' : 'style="text-decoration: none;"';


					$image_comment = ($randrow[$j]['comments'] == 0) ? $lang['Not_commented'] : $randrow[$j]['comments'];

					$template->assign_block_vars('random_pics_block.rand_pics.rand_detail', array(
						'PIC_ID' => $randrow[$j]['pic_id'],
						'PIC_TITLE' => htmlspecialchars($randrow[$j]['pic_title']),
						'TITLE' => '<a href = "' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $randrow[$j]['pic_id'])) . '">' . htmlspecialchars($randrow[$j]['pic_title']) . '</a>',
						'POSTER' => $rand_poster,
						'TIME' => create_date2($board_config['default_dateformat'], $randrow[$j]['pic_time'], $board_config['board_timezone']),

						'VIEW' => $randrow[$j]['pic_view_count'],

						'RATING' => ($album_config['rate'] == 1) ? ('<a href="'. append_sid(album_append_uid($album_rate_pic_url .'?pic_id='. $randrow[$j]['pic_id'])) . '" ' . $image_rating_link_style .'>' . $lang['Rating'] . '</a>: ' . $image_rating . '<br />') : '',

						'COMMENTS' => ($album_config['comment'] == 1) ? ('<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $randrow[$j]['pic_id'])) . '">' . $lang['Comments'] . '</a>: ' . $image_comment . '<br />') : '',

						'IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . decode_ip($randrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($randrow[$j]['pic_user_ip']) .'</a><br />' : ''
						)
					);
				}
			}
		}
		else
		{
			// No Pics Found
			$template->assign_block_vars('random_pics_block.no_pics', array());
		}
	}

	if (empty($cats))
	{
		// No Cats Found
		$template->assign_block_vars('random_pics_block', array());
		$template->assign_block_vars('random_pics_block.no_pics', array());
	}
}


function album_build_last_comments_info($cats)
{
	global $db, $board_config, $album_config, $template, $lang, $album_data, $bbcode, $userdata;

	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

	$number_of_comments = 5;
	$album_show_pic_url = 'album_showpage.' . PHP_EXT;

	if ($cats == '')
	{
		$sql_where = '';
	}
	else
	{
		$sql_where = 'WHERE a.pic_cat_id IN (' . $cats . ')';
	}

	//$sql_group = 'GROUP BY c.comment_pic_id';
	$sql_group = '';

	// get last comment information, and user, comment and pic informations
	$sql = "SELECT c.*, u.user_id, u.username, a.*
		FROM " . ALBUM_COMMENT_TABLE . " AS c
		LEFT JOIN " . USERS_TABLE . " AS u ON c.comment_user_id = u.user_id
		LEFT JOIN " . ALBUM_TABLE . " AS a ON c.comment_pic_id = a.pic_id
		$sql_where
		$sql_group
		ORDER BY c.comment_id DESC
		LIMIT $number_of_comments";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get last comment information', '', __LINE__, __FILE__, $sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$commentsrow[] = $row;
	}

	$db->sql_freeresult($result);

	if (count($commentsrow) > 0)
	{
		$template->assign_block_vars('recent_comments_block', array(
			'L_COMMENTS' => $lang['Comments'],
			'L_LAST_COMMENT' => $lang['Last_Comment'],
			'L_LAST_COMMENT_INFO' => $lang['Last_Comments'],
			)
		);
		for ($i = 0; $i < count($commentsrow); $i++)
		{
			if (($commentsrow[$i]['comment_username'] == ALBUM_GUEST) || ($commentsrow[$i]['comment_username'] == ''))
			{
				$poster = ($commentsrow[$i]['comment_username'] == '') ? $lang['Guest'] : colorize_username($commentsrow[$i]['user_id']);
			}
			else
			{
				$poster = colorize_username($commentsrow[$i]['user_id']);
			}

			$info .= '<br />' . $lang['Pic_Image'] . ': <a href="' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $commentsrow[$i]['pic_id'])) . '">' . $commentsrow[$i]['pic_title'] . '</a>';

			$image_comment = ($commentsrow[$i]['comments'] == 0) ? $lang['Not_commented'] : $commentsrow[$i]['comments'];

			$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $commentsrow[$i]['pic_id']));
			if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
			{
				$thumbnail_file = picture_quick_thumb($commentsrow[$i]['pic_filename'], $commentsrow[$i]['pic_thumbnail'], $thumbnail_file);
			}

			if ($album_config['lb_preview'] == 0)
			{
				$pic_preview = '';
			}
			else
			{
				$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $commentsrow[$i]['pic_id'])) . '\',\'' . addslashes($commentsrow[$i]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
			}

			$html_on = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? 1 : 0 ;
			$bbcode_on = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? 1 : 0 ;
			$smilies_on = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? 1 : 0 ;
			$bbcode->allow_html = $html_on;
			$bbcode->allow_bbcode = $bbcode_on;
			$bbcode->allow_smilies = $smilies_on;

			$commentsrow[$i]['comment_text'] = $bbcode->parse($commentsrow[$i]['comment_text']);
			$commentsrow[$i]['comment_text'] = strtr($commentsrow[$i]['comment_text'], array_flip(get_html_translation_table(HTML_ENTITIES)));

			$commentsrow[$i]['comment_text'] = $bbcode->acronym_pass($commentsrow[$i]['comment_text']);
			if(count($orig_autolink))
			{
				$commentsrow[$i]['comment_text'] = autolink_transform($commentsrow[$i]['comment_text'], $orig_autolink, $replacement_autolink);
			}
			//$commentsrow[$i]['comment_text'] = kb_word_wrap_pass ($commentsrow[$i]['comment_text']);
			$commentsrow[$i]['comment_text'] = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $commentsrow[$i]['comment_text']) : $commentsrow[$i]['comment_text'];

			$template->assign_block_vars('recent_comments_block.comment_row', array(
				'U_PIC' => ($album_config['fullpic_popup']) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $commentsrow[$i]['pic_id'])) : append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $commentsrow[$i]['pic_id'])),
				'THUMBNAIL' => $thumbnail_file,
				'PIC_PREVIEW' => $pic_preview,
				'PIC_TITLE' => htmlspecialchars($commentsrow[$j]['pic_title']),
				'DESC' => htmlspecialchars($commentsrow[$i]['pic_desc']),
				'COMMENT_TEXT' => $commentsrow[$i]['comment_text'],
				'PIC_ID' => $commentsrow[$i]['pic_id'],
				'TITLE' => '<a href = "' . append_sid(album_append_uid($album_show_pic_url . '?pic_id=' . $commentsrow[$i]['pic_id'])) . '">' . $commentsrow[$i]['pic_title'] . '</a>',
				'POSTER' => $poster,
				'TIME' => create_date2($board_config['default_dateformat'], $commentsrow[$i]['comment_time'], $board_config['board_timezone']),
				'VIEW' => $commentsrow[$i]['pic_view_count'],
				'RATING' => ($album_config['rate'] == 1) ? ('<a href="'. append_sid(album_append_uid($album_rate_pic_url .'?pic_id='. $commentsrow[$i]['pic_id'])) . '" ' . $image_rating_link_style .'>' . $lang['Rating'] . '</a>: ' . $image_rating . '<br />') : '',
				'COMMENTS' => ($album_config['comment'] == 1) ? ('<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $commentsrow[$i]['pic_id'])) . '">' . $lang['Comments'] . '</a>: ' . $image_comment . '<br />') : '',
				'IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://whois.sc/' . decode_ip($commentsrow[$i]['pic_user_ip']) . '" target="_blank">' . decode_ip($commentsrow[$i]['pic_user_ip']) .'</a><br />' : ''
				)
			);
		}
	}
}


?>