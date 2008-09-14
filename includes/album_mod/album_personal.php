<?php
/***************************************************************************
 *                          album_personal.php
 *                          ------------------------------------------------
 *     begin                : Friday, June 12, 2004
 *     copyright            : (C) 2004 IdleVoid
 *     email                : idlevoid@slater.dk
 *     file version         : 1.0.8
 *     release              : 1.2.0
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// ------------------------------------------------------------------------
// This file is only included in the album.php and album_cat.php, it should be stored in the album_mod folder
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// $album_user_id, $cat_id and $moderators_list are as default set in album.php and in album_cat.php files in the main folder.
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// Get the name of this user
// ------------------------------------------------------------------------
$username = album_get_user_name($album_user_id);
if (empty($username))
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}

$moderators_list = empty($moderators_list) ? $username : ',' . $username;

// check if personal gallery root category exists
if (ALBUM_ROOT_CATEGORY == ($check_cat_id = album_get_personal_root_id($album_user_id)))
{
	// if it doesn't then create the 'fake' category so we can authenticate it
	$thiscat = init_personal_gallery_cat($album_user_id);
	$cat_id = $check_cat_id;
	$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_ALL, $thiscat);
}
else
{
	if ( empty($cat_id) || ($cat_id == 0) )
	{
		$cat_id = $check_cat_id;
	}
	$thiscat = $album_data['data'][$album_data['keys'][$cat_id]];
	$auth_data = album_get_auth_data(album_get_personal_root_id($album_user_id));
}

// ------------------------------------------------------------------------
// Check view permissions
// ------------------------------------------------------------------------

// Old AUTH Check for PG
//$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_ALL, $thiscat);

// Used in album_cat.php
//$auth_data = album_get_auth_data($cat_id);

// New AUTH check for PG
//$auth_data = album_get_auth_data(album_get_personal_root_id($album_user_id));

if ( !album_check_permission($auth_data, ALBUM_AUTH_VIEW) )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=album.' . PHP_EXT . '&user_id=' . $album_user_id));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
	}
}
//
// END check permissions
//

// ------------------------------------------------------------------------
// Check personal gallery creation/upload permission
// ------------------------------------------------------------------------
if ( !album_check_permission($auth_data, ALBUM_AUTH_UPLOAD) && (count($album_data['data']) <= 1) )
{
	if ($album_user_id == $userdata['user_id'])
	{
		message_die(GENERAL_MESSAGE, $lang['Not_allowed_to_create_personal_gallery']);
	}
}
//
// End check own gallery
//

// ------------------------------------------------------------------------
// Check we are the root of the personal gallery and if it have
// children or parents
// ------------------------------------------------------------------------

// if $cat_id is equal to the non existing root category id, then it wasn't supplied to the call of the page
// try to get the personal gallery root category instead (if it doesn't exists it returns ALBUM_ROOT_CATEGORY
if ($cat_id == ALBUM_ROOT_CATEGORY)
{
	$cat_id = album_get_personal_root_id($album_user_id);
}

$is_root_cat = ( ($cat_id == album_get_personal_root_id($album_user_id) || ($cat_id == ALBUM_ROOT_CATEGORY) ) ? true : false);
$has_sub_cats = album_has_sub_cats($cat_id);
$has_parent_cats = album_has_parent_cats($cat_id);

// ------------------------------------------------------------------------
// Setup the correct link page
// ------------------------------------------------------------------------
if ($is_root_cat)
{
	$album_page_url = 'album.' . PHP_EXT;
}
else
{
	$album_page_url = 'album_cat.' . PHP_EXT;
}


include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'album_cat_body.tpl'));

$auth_list = album_build_auth_list($album_user_id, $cat_id, $auth_data);
//$auth_list = album_build_auth_list($album_user_id, $cat_id);

// ------------------------------------------------------------------------
// Setup the correct variables and string acording to if we are showing all
// the pictures or a category, this is infact the 'main' difference betwwen
// these tho 'view modes'.. the rest is done in 'album_build_picture_table'
// ------------------------------------------------------------------------
if ($album_view_mode != ALBUM_VIEW_ALL)
{
	$album_nav_cat_desc = album_make_nav_tree($cat_id, $album_page_url, 'nav', $album_user_id);
	if (!empty($album_nav_cat_desc))
	{
		$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
	}

	$cat_ids = $cat_id;
	$image_toggle_button = $images['all_pic_view_mode'];
	$view_mode_url = append_sid(album_append_uid($album_page_url . '?cat_id=' . intval($cat_id) . '&amp;mode=' . ALBUM_VIEW_ALL));
	$view_mode_text = $lang['Show_all_pic_view_mode'];
}
else
{
	$album_nav_cat_desc = album_make_nav_tree(album_get_personal_root_id($album_user_id), $album_page_url, 'nav', $album_user_id);
	if (!empty($album_nav_cat_desc))
	{
		$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
	}

	if (album_get_personal_root_id($album_user_id) != $cat_id)
	{
		$allowed_cat = $cat_id;
		$tmp_array = array();
		album_get_sub_cat_ids(album_get_personal_root_id($album_user_id), $tmp_array, ALBUM_AUTH_VIEW, true);

		reset($tmp_array);
		while (list($key, $id) = each($tmp_array))
		{
			if ($id != $cat_id)
			{
				$allowed_cat .= ',' . $id;
			}
		}
	}

	$cat_ids = $allowed_cat;
	$image_toggle_button = $images['normal_pic_view_mode'];
	$view_mode_url = append_sid(album_append_uid($album_page_url . '?cat_id=' . intval($cat_id)));
	$view_mode_text = $lang['Show_selected_pic_view_mode'];
}

// ------------------------------------------------------------------------
// Count Pics of the root category of personal gallery,
// - $cat_ids is set in the above IF statement
// ------------------------------------------------------------------------
if ( $cat_ids == '')
{
	$cat_ids = 0;
}

$sql = 'SELECT COUNT(p.pic_id) AS count
		FROM '. ALBUM_TABLE .' AS p, ' . ALBUM_CAT_TABLE .' AS c
		WHERE c.cat_user_id = '.$album_user_id.'
			AND c.cat_id IN (' . $cat_ids . ')
			AND p.pic_cat_id = c.cat_id';

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not count pics !!', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$total_pics = $row['count'];

// ------------------------------------------------------------------------
// Build up the page
// ------------------------------------------------------------------------
//
// I will try to explain how this SHOULD work. Only some testing and studying
// of the code will tell if really does do what it should.
//
// NOTE : this might be change alittle AFTER this has been written so don't
//        take it for granted that it does work this way a 100%
//        But feel free to tell me if this(the description) need updating or
//        if the code needs fixing.
//
// $cat_id     : is the currently selected category
// $allowed_cat: is a list of all the allowed categories that the current user
//               is allowed to view
// £cat_ids    : is synomous with $allowed_cat OR $cat_id, depending on the view mode
//
// 0: Begin of 'work flow'
//
// 1: Check if there are any pictures for the selected catery OR for the selected
//    categories (when in 'simple view' mode - see $allowed_cat above)
//    -  No: No pictures found, go to step 2
//    - Yes: One or more pictures found, go to step 5
//
// 2: Do another check to see if the use got any pictures in all of her
//    personal gallery categories, that the current user is allowed to view
//    -  No: No pictures found, goto step 3
//    - Yes: One or more pictures found, goto step 4
//
// 3: The personal gallery does not have _ANY_pictures at all OR does not have _ANY_
//    at all the current user can view. Goto step 8
//
// 4: Only display the recent pictures of all the categories in this personal gallery
//	  Goto step 10
//
// 5: Check if we are in simple view mode
//    -  No: goto step 6
//    - Yes: goto step 7
//
// 6: We are not in simple view mode, so display the pictures in the category and
//    if enabled; the recent pictures of the this category and it's sub categories.
//    Goto step 10
//
// 7: We are in simple mode, so only display the ALL the pictures of the personal gallery
//    which can be view by the currently logged in user.
//    Goto step 10
//
// 8: Check if personal gallery got sub categories (which can be viewed by current user)
//    - Yes: Display no picture message, since ther really are't any pictures to display
//    -  No: Display message to logged in user that the gallery doesn't exists.
//    Goto step 10
//
// 9: Display message to logged in user that the gallery doesn't exists.
//    Goto step 10
//
// 10: End of 'work flow'
//
// ------------------------------------------------------------------------
$no_personal_gallery = false;

if ($row['count'] == 0)
{
	if ( !strstr($album_nav_cat_desc, sprintf($lang['Personal_Gallery_Of_User'], $username)) )
	{
		$album_nav_cat_desc .= ALBUM_NAV_ARROW . '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT . '?cat_id=' . $cat_id)) . '" class="nav">' . sprintf($lang['Personal_Gallery_Of_User'], $username) . '</a>';
	}

	// ------------------------------------------------------------------------
	// check if there is _any_ pictures at all in the personal gallery of this user.
	// but ONLY if we aren't in simple view mode (then we have already indirectly done the check)
	// ------------------------------------------------------------------------
	if ( ($album_view_mode != ALBUM_VIEW_ALL) && (!empty($allowed_cat)) )
	{
		$sql = 'SELECT COUNT(p.pic_id) AS count
				FROM '. ALBUM_TABLE .' AS p, ' . ALBUM_CAT_TABLE .' AS c
				WHERE c.cat_user_id = ' . $album_user_id . '
					AND c.cat_id IN (' . $allowed_cat.')
					AND p.pic_cat_id = c.cat_id';

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not count pics!!', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$total_pics = $row['count'];
	}

	if ( ($album_config['personal_show_recent_instead_of_nopics'] == 1) && ($row['count'] > 0) )
	{
		album_build_recent_pics($allowed_cat);
	}
	else
	{
		$template->assign_block_vars('index_pics_block', array());
		$template->assign_block_vars('index_pics_block.no_pics', array());
	}

	if ( ($is_root_cat) && (!$has_sub_cats) )
	{
		$sql = "SELECT c.cat_id
				FROM ". ALBUM_CAT_TABLE ." AS c
				WHERE c.cat_user_id = '$album_user_id' AND c.cat_parent = 0
				LIMIT 1";

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
		}

		if ($db->sql_numrows($result) == 0)
		{
			$no_personal_gallery = true;
			$no_picture_message = sprintf($lang['Personal_gallery_not_created'], $username);
			$create_personal_cat_link = '<a href="' . append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?action=create&amp;user_id=' . $album_user_id)) . '">' . $lang['Create_Personal_Categories'] . '</a><br />';
			if ( album_check_permission($auth_data, ALBUM_AUTH_UPLOAD) == true )
			{
				$template->assign_block_vars('index_pics_block.no_pics.manage_personal_gal_folders', array());
			}
		}
		else
		{
			$no_picture_message = $lang['No_Pics'];
			$create_personal_cat_link = '';
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$no_picture_message = $lang['No_Pics'];
	}
}
else
{
	album_build_picture_table($album_user_id, $cat_ids, $thiscat, $auth_data, $start, $sort_method, $sort_order, $total_pics);

	if ( ($album_config['personal_show_recent_in_subcats'] == 1) && ($album_view_mode != ALBUM_VIEW_ALL) )
	{
		album_build_recent_pics($allowed_cat);
	}
}

// ------------------------------------------------------------------------
// Check if we should show the upload picture image/icon
// ------------------------------------------------------------------------
$upload_img = $images['upload_pic'];
$upload_link = append_sid(album_append_uid('album_upload.' . PHP_EXT . '?cat_id=' . intval($cat_id)));
$upload_full_link = '<a href="' . $upload_link . '"><img src="' . $upload_img .'" alt="' . $lang['Upload_Pic'] . '" title="' . $lang['Upload_Pic'] . '" align="middle" border="0" /></a>';

$download_img = $images['download_pic'];
$download_link = append_sid(album_append_uid('album_download.' . PHP_EXT . '?cat_id=' . intval($cat_id) . ( ($sort_method != '') ? '&amp;sort_method=' . $sort_method : '' ) . ( ($sort_order != '') ? '&amp;sort_order=' . $sort_order : '' ) . ( ($start != '') ? '&amp;start=' . $start : '' )));
$download_full_link = '<a href="' . $download_link . '"><img src="' . $download_img . '" alt="' . $lang['Download_page'] . '" title="' . $lang['Download_page'] . '" align="middle" border="0" /></a>';

if( ((album_check_permission($auth_data, ALBUM_AUTH_UPLOAD) == true) && ($enable_picture_upload_switch == false)) || ($no_personal_gallery = false) )
{
	$template->assign_block_vars('enable_picture_upload_pg', array());
}

// Enable download only for own personal galleries
//if ( ($total_pics > 0) && ($enable_picture_download_switch == false) && ($thiscat['cat_user_id'] == $userdata['user_id']) )
if ( ($total_pics > 0) && ($enable_picture_download_switch == false) )
{
	$template->assign_block_vars('enable_picture_download_pg', array());
}

if ($no_personal_gallery == false)
{
	$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_ALL, $thiscat);
	$auth_list = album_build_auth_list($album_user_id, $cat_id);

	//if( (album_check_permission($auth_data, ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES) == true) && ($is_root_cat) && (!$has_sub_cats && !$has_parent_cats))
	if( (album_check_permission($auth_data, ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES) == true) && ((($is_root_cat) && ($row['count'] > 0)) || (!$is_root_cat)))
	{
		$template->assign_block_vars('manage_personal_gal_folders', array());
	}

}
// ------------------------------------------------------------------------
// Check if we should show the view toggle button
// ------------------------------------------------------------------------
if ($album_config['show_all_in_personal_gallery'] == 1)
{
	$template->assign_block_vars('enable_view_toggle', array());
}

if ($thiscat['cat_user_id'] == $userdata['user_id'])
{
	$template->assign_block_vars('switch_own_gallery', array());
}

$template->assign_block_vars('personal_gallery_header', array());

// ------------------------------------------------------------------------
// Do our template info...
// ------------------------------------------------------------------------
$template->assign_vars(array(
	'L_ALBUM' => $lang['Album'],

	'U_VIEW_CAT' => append_sid(album_append_uid($album_page_url . '?cat_id=' . intval($cat_id))),
	'CAT_TITLE' => ($is_root_cat || $album_view_mode == ALBUM_VIEW_ALL) ? sprintf($lang['Personal_Gallery_Of_User'], $username) : $thiscat['cat_title'],
	'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,
	'NAV_CAT_DESC' => $album_nav_cat_desc,

	'L_PERSONAL_GALLERY_EXPLAIN' => $lang['Personal_Gallery_Explain'],

	'L_MODERATORS' => $lang['Moderators'],
	'MODERATORS' => $moderators_list,

	'L_ALBUM_ALLPICS' => $lang['All_Picture_List_Of_User'],
	'L_ALBUM_OTF' => $lang['Pic_Gallery'],
	'L_ALBUM_HON' => $lang['Hot_Or_Not'],
	'L_ALBUM_RDF' => $lang['Pic_RDF'],
	'L_ALBUM_RSS' => $lang['Pic_RSS'],
	'U_ALBUM_ALLPICS' => append_sid(album_append_uid('album_allpics.' . PHP_EXT)),
	'U_ALBUM_OTF' => append_sid(album_append_uid('album_otf.' . PHP_EXT)),
	'U_ALBUM_HON' => append_sid(album_append_uid('album_hotornot.' . PHP_EXT)),
	'U_ALBUM_RDF' => append_sid(album_append_uid('album_rdf.' . PHP_EXT)),
	'U_ALBUM_RSS' => append_sid(album_append_uid('album_rss.' . PHP_EXT)),

	'L_UPLOAD_PIC' => $lang['Upload_Pic'],
	'U_UPLOAD_PIC' => $upload_link,
	'UPLOAD_PIC_IMG' => $upload_img,
	'UPLOAD_FULL_LINK' => $upload_full_link,

	'L_DOWNLOAD_PICS' => $lang['Download_pics'],
	'L_DOWNLOAD_PAGE' => $lang['Download_page'],
	'U_DOWNLOAD' => $download_link,
	'DOWNLOAD_PIC_IMG' => $download_img,
	'DOWNLOAD_FULL_LINK' => $download_full_link,

	//'L_CREATE_PERSONAL_GALLERY' => $lang['Create_Personal_Categories'],
	'U_CREATE_PERSONAL_GALLERY' => $create_personal_cat_link,
	'CREATE_CATEGORY_IMG' => $images['manage_pic'],

	'U_MANAGE_PIC' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)),
	'MANAGE_PIC_IMG' => $images['manage_pic'],
	'L_MANAGE_PIC' => $lang['manage_Pic'],

	'U_TOGGLE_VIEW_ALL' => $view_mode_url,
	'TOGGLE_VIEW_ALL_IMG' => $image_toggle_button,
	'L_TOGGLE_VIEW_ALL' => $view_mode_text,

	'L_CATEGORY' => sprintf($lang['Personal_Gallery_Of_User'], $username),

	'L_NO_PICS' => $no_picture_message,
	'L_PERSONAL_GALLERY_NOT_CREATED' => sprintf($lang['Personal_gallery_not_created'], $username),

	'L_RECENT_PUBLIC_PICS' => sprintf($lang['Recent_Personal_Pics'], $username),

	'S_COLS' => $album_config['cols_per_page'],
	'S_COL_WIDTH' => (100 / $album_config['cols_per_page']) . '%',

	'L_VIEW' => $lang['View'],
	'L_PIC_CAT' => $lang['Pic_Cat'],
	'L_POSTER' => $lang['Pic_Poster'],
	'L_POSTED' => $lang['Posted'],

	'ALBUM_JUMPBOX' => $album_jumpbox,

	'S_ALBUM_ACTION' => append_sid(album_append_uid($album_page_url . '?cat_id=' . intval($cat_id))),

	'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',

	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],

	'L_TIME' => $lang['Time'],
	'L_PIC_ID' => $lang['Pic_ID'],
	'L_PIC_TITLE' => $lang['Pic_Image'],

	'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
	'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
	'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',

	'SORT_RATING_OPTION' => $sort_rating_option,
	'SORT_COMMENTS_OPTION' => $sort_comments_option,
	'SORT_NEW_COMMENT_OPTION' => $sort_new_comment_option,
	'SORT_USERNAME_OPTION' => $sort_username_option,

	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],

	'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
	'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',

	'S_AUTH_LIST' => $auth_list
	)
);

?>