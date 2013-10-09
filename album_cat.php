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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// ------------------------------------
// Check the request
// ------------------------------------
$cat_id = request_var('cat_id', 0);
if ($cat_id <= 0)
{
	message_die(GENERAL_ERROR, 'No categories specified');
}

// ------------------------------------
// Check $album_user_id
// ------------------------------------
$sql = "SELECT cat_user_id
				FROM " . ALBUM_CAT_TABLE . "
				WHERE cat_id = '" . $cat_id . "'
				LIMIT 1";
$result = $db->sql_query($sql, 0, 'album_cat_');

// if no user_id was supplied then we aren't going to show a personal gallery category
$album_user_id = ALBUM_PUBLIC_GALLERY;
$catsrow = array();
while ($row = $db->sql_fetchrow($result))
{
	$catsrow[] = $row;
	$album_user_id = $row['cat_user_id'];
}
$db->sql_freeresult($result);

/*
$album_user_id = request_var('user_id', 0);
if(empty($album_user_id))
{
	// if no user_id was supplied then we aren't going to show a personal gallery category
	$album_user_id = ALBUM_PUBLIC_GALLERY;
}
*/

$mode = request_var('mode', '');
$album_view_mode = strtolower($mode);

// make sure that it only contains some valid value
switch ($album_view_mode)
{
	case ALBUM_VIEW_ALL:
		$album_view_mode = ALBUM_VIEW_ALL;
		break;
	case ALBUM_VIEW_LIST:
		$album_view_mode = ALBUM_VIEW_LIST;
		break;
	default:
		$album_view_mode = '';
}
// END check request

// if requested gallery is the root category of the public categories, OR
// the category is the root category of the personal gallery - then show root album instead
if (($cat_id <= (ALBUM_ROOT_CATEGORY + 1)) || (album_get_personal_root_id($album_user_id) == $cat_id))
{
	if ($cat_id == ALBUM_JUMPBOX_PUBLIC_GALLERY)
	{
		redirect(append_sid(album_append_uid('album.' . PHP_EXT)));
	}

	if ($cat_id == ALBUM_JUMPBOX_USERS_GALLERY)
	{
		redirect(append_sid(album_append_uid('album_personal_index.' . PHP_EXT)));
	}
	redirect(append_sid(album_append_uid('album.' . PHP_EXT)));
}

// ------------------------------------
// Get this cat info
// ------------------------------------
$thiscat = array(); // this category
$catrows = array(); // all categories for jumpbox
$auth_data = array(); // the authothentication data for current category for current user

if (($album_user_id != ALBUM_PUBLIC_GALLERY) && !album_check_user_exists($album_user_id))
{
	redirect(append_sid(album_append_uid('album.' . PHP_EXT)));
}

$read_options = ($album_view_mode == ALBUM_VIEW_LIST) ? (ALBUM_READ_ALL_CATEGORIES | ALBUM_AUTH_VIEW) : ALBUM_AUTH_VIEW;
$catrows = album_read_tree($album_user_id, $read_options);

// check if the category exists in the album_tree data
if (@!array_key_exists($cat_id, $album_data['keys']))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist']);
}

$thiscat = $album_data['data'][$album_data['keys'][$cat_id]];
$total_pics = $thiscat['count'];
$auth_data = album_get_auth_data($cat_id);
//$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_ALL, $thiscat);


// ------------------------------------
// Check permissions
// ------------------------------------
if(!$auth_data['view'])
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(album_append_uid(CMS_PAGE_LOGIN . '?redirect=album_cat.' . PHP_EXT . '&cat_id=' . $cat_id)));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorized']);
	}
}
// END check permissions

if (empty($thiscat))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist']);
}

// ------------------------------------
// Build the list of allowed sub category id's
// ------------------------------------
$subcats = array();
$allowed_cat = $cat_id;
album_get_sub_cat_ids($cat_id, $subcats);
for ($i = 0; $i < sizeof($subcats); $i++)
{
	$allowed_cat .= ',' . $subcats[$i];
}
// END cat info


// ------------------------------------
// Build Auth List
// ------------------------------------
$auth_list = album_build_auth_list($album_user_id, $cat_id);
// END Auth List


// ------------------------------------
// Build Moderators List
// ------------------------------------

$grouprows = array();
$moderators_list = '';

if (($album_user_id == ALBUM_PUBLIC_GALLERY) && ($thiscat['cat_moderator_groups'] != ''))
{
	// Get the namelist of moderator usergroups
	$sql = "SELECT group_id, group_name, group_type, group_single_user
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user = '0'
				AND group_type <> ". GROUP_HIDDEN ."
				AND group_id IN (" . $thiscat['cat_moderator_groups'] . ")
			ORDER BY group_name ASC";
	$result = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($result))
	{
		$grouprows[] = $row;
	}

	if(sizeof($grouprows) > 0)
	{
		for ($j = 0; $j < sizeof($grouprows); $j++)
		{
			$group_link = '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?'. POST_GROUPS_URL . '=' . $grouprows[$j]['group_id']) . '">' . $grouprows[$j]['group_name'] . '</a>';

			$moderators_list .= ($moderators_list == '') ? $group_link : ', ' . $group_link;
		}
	}
}
// END Moderator List

// Update the naVigation tree
$album_nav_cat_desc = album_make_nav_tree($cat_id, 'album_cat.' . PHP_EXT, 'nav' , $album_user_id);
if ($album_nav_cat_desc != '')
{
	$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
}

$cat_desc = album_get_object_lang($cat_id, 'desc');

// ------------------------------------
// Build the thumbnail page
// ------------------------------------

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sort_method = request_var('sort_method', $album_config['sort_method']);
$sort_method = check_var_value($sort_method, array('pic_time', 'pic_title', 'username', 'pic_view_count', 'rating', 'comments', 'new_comment'));

$sort_order = request_var('sort_order', $album_config['sort_order']);
$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

$sort_append = '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order;

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

// ------------------------------------
// additional sorting options
// ------------------------------------
$sort_rating_option = '';
$sort_username_option = '';
$sort_comments_option = '';
$sort_new_comment_option = '';
if($album_config['rate'] == 1)
{
	$sort_rating_option = '<option value="rating" ';
	$sort_rating_option .= ($sort_method == 'rating') ? 'selected="selected"' : '';
	$sort_rating_option .= '>' . $lang['Rating'] .'</option>';
}
if($album_config['comment'] == 1)
{
	$sort_comments_option = '<option value="comments" ';
	$sort_comments_option .= ($sort_method == 'comments') ? 'selected="selected"' : '';
	$sort_comments_option .= '>' . $lang['Comments'] .'</option>';

	$sort_new_comment_option = '<option value="new_comment" ';
	$sort_new_comment_option .= ($sort_method == 'new_comment') ? 'selected="selected"' : '';
	$sort_new_comment_option .= '>' . $lang['New_Comment'] .'</option>';
}

if($album_user_id == ALBUM_PUBLIC_GALLERY)
{
	$sort_username_option = '<option value="username" ';
	$sort_username_option .= ($sort_method == 'username') ? 'selected="selected"' : '';
	$sort_username_option .= '>' . $lang['SORT_USERNAME'] .'</option>';
}

// ------------------------------------
// Build Jumpbox
// ------------------------------------
$album_jumpbox = album_build_jumpbox($cat_id, $album_user_id);
// END build jumpbox

$upload_img = $images['upload_pic'];
$upload_link = append_sid(album_append_uid('album_upload.' . PHP_EXT . '?cat_id=' . $cat_id));
$upload_full_link = '<a href="' . $upload_link . '"><img src="' . $upload_img .'" alt="' . $lang['Upload_Pic'] . '" title="' . $lang['Upload_Pic'] . '" style="border:0px;vertical-align:middle;" /></a>';

$jupload_img = $images['jupload_pic'];
$jupload_link = append_sid(album_append_uid('album_jupload.' . PHP_EXT . '?cat_id=' . $cat_id));
$jupload_full_link = '<a href="' . $jupload_link . '"><img src="' . $jupload_img .'" alt="' . $lang['JUpload_Pic'] . '" title="' . $lang['Jupload_Pic'] . '" style="border:0px;vertical-align:middle;" /></a>';

$download_img = $images['download_pic'];
$download_link = append_sid(album_append_uid('album_download.' . PHP_EXT . '?cat_id=' . $cat_id . (($sort_method != '') ? '&amp;sort_method=' . $sort_method : '') . (($sort_order != '') ? '&amp;sort_order=' . $sort_order : '') . (($start != '') ? '&start=' . $start : '')));
$download_full_link = '<a href="' . $download_link . '"><img src="' . $download_img . '" alt="' . $lang['Download_page'] . '" title="' . $lang['Download_page'] . '" style="border:0px;vertical-align:middle;" /></a>';

$download_all_img = $images['download_all_pic'];
$download_all_link = append_sid(album_append_uid('album_download.' . PHP_EXT . '?cat_id=' . $cat_id . (($sort_method != '') ? '&amp;sort_method=' . $sort_method : '') . (($sort_order != '') ? '&amp;sort_order=' . $sort_order : '') . '&amp;download_all_pics=true'));
$download_all_full_link = '<a href="' . $download_all_link . '"><img src="' . $download_all_img . '" alt="' . $lang['Download_page'] . '" title="' . $lang['Download_page'] . '" style="border:0px;vertical-align:middle;" /></a>';

if($auth_data['upload'] == true)
{
	$enable_picture_upload_switch = true;
	$template->assign_block_vars('enable_picture_upload', array());
}

// Enable download only for own personal galleries
//if ($thiscat['cat_user_id'] == $user->data['user_id'])
if((($user->data['user_level'] == ADMIN) || (($album_config['show_download'] == 1) && ($auth_data['upload'] == true)) || (($album_config['show_download'] == 2))) && ($total_pics > 0))
{
	$enable_picture_download_switch = true;
	$template->assign_block_vars('enable_picture_download', array());
}

// Start output of page

//$meta_content['page_title'] = $lang['Album'];
$meta_content['page_title'] = $thiscat['cat_title'];
$meta_content['description'] = $lang['Album'] . ' - ' . $thiscat['cat_title'] . ' - ' . $cat_desc;
$meta_content['keywords'] = $lang['Album'] . ', ' . $thiscat['cat_title'] . ', ' . $cat_desc . ', ';


if ($album_user_id == ALBUM_PUBLIC_GALLERY)
{
	if(empty($moderators_list))
	{
		$moderators_list = $lang['None'];
	}

	album_read_tree($album_user_id);
	$album_nav_cat_desc = album_make_nav_tree($cat_id, 'album_cat.' . PHP_EXT, 'nav', $album_user_id);
	if ($album_nav_cat_desc != '')
	{
		$nav_server_url = create_server_url();
		$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
		$breadcrumbs['address'] = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;
	}

	if ($album_config['show_slideshow'] && ($total_pics > 0))
	{
		$first_pic_id = album_get_first_pic_id($cat_id);
		$slideshow_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $first_pic_id . '&amp;slideshow=5'));
		$slideshow_link_full = '[<a href="' . $slideshow_link . '">' . $lang['Slideshow'] . '</a>]';
		$breadcrumbs['bottom_right_links'] = $slideshow_link_full;
	}
	else
	{
		$slideshow_link_full = '';
	}

	$template_to_parse = 'album_cat_body.tpl';

	if ($total_pics > 0)
	{
		album_build_picture_table($album_user_id, $cat_id, $thiscat, $auth_data, $start, $sort_method, $sort_order, $total_pics);

		// Last Comments
		if ($album_config['show_last_comments'] == 1)
		{
			album_build_last_comments_info($allowed_cat);
		}

		// Recent Public Pics
		if ($album_config['show_recent_in_subcats'] == 1)
		{
			album_build_recent_pics($allowed_cat);
		}

		// Most Viewed Pics
		if ($album_config['disp_mostv'] == 1)
		{
			album_build_most_viewed_pics($allowed_cat);
		}
	}
	else
	{
		// ------------------------------------
		// Build Recent Public Pics
		// ------------------------------------
		$has_sub_cats = album_has_sub_cats($cat_id);
		if ($has_sub_cats && ($album_config['show_recent_instead_of_nopics'] == 1))
		{
			album_build_recent_pics($allowed_cat);
			$template->assign_vars(array('S_NO_PICS' => '1'));
		}
		else
		{
			$template->assign_block_vars('index_pics_block', array());
			$template->assign_block_vars('index_pics_block.no_pics', array());
			$template->assign_block_vars('index_pics_block.enable_gallery_title', array());
			$template->assign_vars(array('S_NO_PICS' => '1'));
		}
	}
	// END thumbnails table

	// MOVED UP
	/*
	album_read_tree($album_user_id);
	$album_nav_cat_desc = album_make_nav_tree($cat_id, 'album_cat.' . PHP_EXT, 'nav', $album_user_id);
	if ($album_nav_cat_desc != '')
	{
		$nav_server_url = create_server_url();
		$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
		$breadcrumbs['address'] = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;
	}
	*/

	// Maybe we should also add a new check to see if user really can upload or not
	// this is not even in the original code by smartor

	$template->assign_vars(array(
		'ALBUM_NAV' => $album_nav_cat_desc,
		'L_ALBUM' => $lang['Album'],

		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'CAT_TITLE' => $thiscat['cat_title'],
		'CAT_DESC' => $cat_desc,
		//'CAT_DESC' => $thiscat['cat_des'],

		'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,
		'NAV_CAT_DESC' => $album_nav_cat_desc,

		'L_MODERATORS' => $lang['Moderators'],
		'MODERATORS' => $moderators_list,

		'L_UPLOAD_PIC' => $lang['Upload_Pic'],
		'U_UPLOAD_PIC' => $upload_link,
		'UPLOAD_PIC_IMG' => $upload_img,
		'UPLOAD_LINK' => $upload_link,
		'UPLOAD_FULL_LINK' => $upload_full_link,

		'JUPLOAD_LINK' => $jupload_link,
		'JUPLOAD_FULL_LINK' => $jupload_full_link,
		'U_JUPLOAD_PIC' => append_sid(album_append_uid('album_jupload.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'JUPLOAD_PIC_IMG' => $images['jupload_pic'],
		'L_JUPLOAD_PIC' => $lang['JUpload_Pic'],

		'L_ALBUM_ALLPICS' => $lang['All_Picture_List_Of_User'],
		'L_ALBUM_OTF' => $lang['Pic_Gallery'],
		'L_ALBUM_HON' => $lang['Hot_Or_Not'],
		'L_ALBUM_RDF' => $lang['Pic_RDF'],
		'L_ALBUM_RSS' => $lang['Pic_RSS'],
		'U_ALBUM_ALLPICS' => append_sid(album_append_uid('album_allpics.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'U_ALBUM_OTF' => append_sid(album_append_uid('album_otf.' . PHP_EXT)),
		'U_ALBUM_HON' => append_sid(album_append_uid('album_hotornot.' . PHP_EXT)),
		'U_ALBUM_RDF' => append_sid(album_append_uid('album_rdf.' . PHP_EXT)),
		'U_ALBUM_RSS' => append_sid(album_append_uid('album_rss.' . PHP_EXT)),

		'L_DOWNLOAD_PICS' => $lang['Download_pics'],
		'L_DOWNLOAD_PAGE' => $lang['Download_page'],
		'U_DOWNLOAD' => $download_link,
		'DOWNLOAD_PIC_IMG' => $download_img,
		'DOWNLOAD_LINK' => $download_link,
		'DOWNLOAD_FULL_LINK' => $download_full_link,
		'U_DOWNLOAD_ALL' => $download_all_link,
		'DOWNLOAD_ALL_PIC_IMG' => $download_all_img,
		'DOWNLOAD_ALL_LINK' => $download_all_link,
		'DOWNLOAD_ALL_FULL_LINK' => $download_all_full_link,

		'L_CATEGORY' => $lang['Category'],

		//'SLIDESHOW' => $slideshow_link_full,

		'L_NO_PICS' => $lang['No_Pics'],
		'L_RECENT_PUBLIC_PICS' => $lang['Recent_Public_Pics'],
		'L_HI_RATINGS' => $lang['Highest_Rated_Pictures'],
		'L_MOST_VIEWED' => $lang['Most_Viewed_Pictures'],

		'S_COLS' => $album_config['cols_per_page'],
		'S_COL_WIDTH' => (100 / $album_config['cols_per_page']) . '%',
		'S_THUMBNAIL_SIZE' => $album_config['thumbnail_size'],

		'L_VIEW' => $lang['View'],
		'L_POSTER' => $lang['Pic_Poster'],
		//'L_POSTER' => $lang['Poster'],
		'L_POSTED' => $lang['Posted'],

		'ALBUM_JUMPBOX' => $album_jumpbox,

		'S_ALBUM_ACTION' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)),

		'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',

		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],

		'L_TIME' => $lang['Time'],
		'L_PIC_ID' => $lang['Pic_ID'],
		'L_PIC_TITLE' => $lang['Pic_Image'],
		//'L_PIC_TITLE' => $lang['Pic_Title'],

		'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
		'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
		'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',
		'SORT_USERNAME' => ($sort_method == 'username') ? 'selected="selected"' : '',

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
}
else
{
	include(ALBUM_MOD_PATH . 'album_personal.' . PHP_EXT);
}

//$template->assign_block_vars('index_pics_block.enable_gallery_title', array());

if (empty($album_view_mode))
{
	$show_personal_gallery_link = ($album_config['show_personal_gallery_link'] == 1) ? true : false;
	album_display_index($album_user_id, $cat_id, true, $show_personal_gallery_link, true);
}

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>