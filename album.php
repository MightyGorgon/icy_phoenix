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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// ------------------------------------
// Check $album_user_id
// ------------------------------------
if (isset ($_POST['user_id']))
{
	$album_user_id = intval($_POST['user_id']);
}
elseif (isset ($_GET['user_id']))
{
	$album_user_id = intval($_GET['user_id']);
}
else
{
	// if no user_id was supplied then we aren't going to show a personal gallery category
	$album_user_id = ALBUM_PUBLIC_GALLERY;
}

if ($album_user_id != ALBUM_PUBLIC_GALLERY)
{
	$cat_id = ALBUM_ROOT_CATEGORY;

	if (isset ($_POST['mode']))
	{
		$album_view_mode = strtolower($_POST['mode']);
	}
	elseif (isset ($_GET['mode']))
	{
		$album_view_mode = strtolower($_GET['mode']);
	}
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

	if (isset ($_POST['cat_id']))
	{
		$cat_id = intval($_POST['cat_id']);
	}
	elseif (isset ($_GET['cat_id']))
	{
		$cat_id = intval($_GET['cat_id']);
	}

	if ($album_user_id < 1)
	{
		if (!$userdata['session_logged_in'])
		{
			redirect(append_sid(album_append_uid(CMS_PAGE_LOGIN . '?redirect=album.' . PHP_EXT, true)));
		}
		else
		{
			$album_user_id = $userdata['user_id'];
			redirect(append_sid(album_append_uid('album.' . PHP_EXT, true)));
		}
	}

	if ( ($cat_id != ALBUM_ROOT_CATEGORY) && ($cat_id != album_get_personal_root_id($album_user_id)) )
	{
		redirect(append_sid(album_append_uid('album_cat.' . PHP_EXT . album_build_url_parameters($_GET), false)));
	}
}

$catrows = array ();
$options = ($album_view_mode == ALBUM_VIEW_LIST ) ? ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW : ALBUM_AUTH_VIEW;
$catrows = album_read_tree($album_user_id, $options);

album_read_tree($album_user_id);
$album_nav_cat_desc = album_make_nav_tree($cat_id, 'album_cat.' . PHP_EXT, 'nav' , $album_user_id);
if ($album_nav_cat_desc != '')
{
	$nav_server_url = create_server_url();
	$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
	$breadcrumbs_address = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;
}
// --------------------------------
// Build allowed category-list (for recent pics after here)
// $catrows array now stores all categories which this user can view.
// --------------------------------
$allowed_cat = ''; // For Recent Public Pics below
for ($i = 0; $i < sizeof($catrows); $i++)
{
	// --------------------------------
	// build list of allowd category id's
	// --------------------------------
	$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ',' . $catrows[$i]['cat_id'];
}
//
// END of Categories Index
//

// ------------------------------------
// Build the sort method and sort order
// information
// ------------------------------------

$start = isset($_GET['start']) ? intval($_GET['start']) : (isset($_POST['start']) ? intval($_POST['start']) : 0);
$start = ($start < 0) ? 0 : $start;

if (isset ($_GET['sort_method']))
{
	switch ($_GET['sort_method'])
	{
		case 'pic_time' :
			$sort_method = 'pic_time';
			break;
		case 'pic_title' :
			$sort_method = 'pic_title';
			break;
		case 'username' :
			$sort_method = 'username';
			break;
		case 'pic_view_count' :
			$sort_method = 'pic_view_count';
			break;
		case 'rating' :
			$sort_method = 'rating';
			break;
		case 'comments' :
			$sort_method = 'comments';
			break;
		case 'new_comment' :
			$sort_method = 'new_comment';
			break;
		default :
			$sort_method = $album_config['sort_method'];
	}
}
elseif (isset ($_POST['sort_method']))
{
	switch ($_POST['sort_method'])
	{
		case 'pic_time' :
			$sort_method = 'pic_time';
			break;
		case 'pic_title' :
			$sort_method = 'pic_title';
			break;
		case 'username' :
			$sort_method = 'username';
			break;
		case 'pic_view_count' :
			$sort_method = 'pic_view_count';
			break;
		case 'rating' :
			$sort_method = 'rating';
			break;
		case 'comments' :
			$sort_method = 'comments';
			break;
		case 'new_comment' :
			$sort_method = 'new_comment';
			break;
		default :
			$sort_method = $album_config['sort_method'];
	}
}
else
{
	$sort_method = $album_config['sort_method'];
}

if (isset ($_GET['sort_order']))
{
	switch ($_GET['sort_order'])
	{
		case 'ASC' :
			$sort_order = 'ASC';
			break;
		case 'DESC' :
			$sort_order = 'DESC';
			break;
		default :
			$sort_order = $album_config['sort_order'];
	}
}
elseif (isset ($_POST['sort_order']))
{
	switch ($_POST['sort_order'])
	{
		case 'ASC' :
			$sort_order = 'ASC';
			break;
		case 'DESC' :
			$sort_order = 'DESC';
			break;
		default :
			$sort_order = $album_config['sort_order'];
	}
}
else
{
	$sort_order = $album_config['sort_order'];
}

// ------------------------------------
// additional sorting options
// ------------------------------------
if ($album_user_id != ALBUM_PUBLIC_GALLERY)
{
	$sort_rating_option = '';
	$sort_comments_option = '';
	$sort_new_comment_option = '';

	if ($album_config['rate'] == 1)
	{
		$sort_rating_option = '<option value="rating" ';
		$sort_rating_option .= ($sort_method == 'rating') ? 'selected="selected"' : '';
		$sort_rating_option .= '>'.$lang['Rating'].'</option>';
	}
	if ($album_config['comment'] == 1)
	{
		$sort_comments_option = '<option value="comments" ';
		$sort_comments_option .= ($sort_method == 'comments') ? 'selected="selected"' : '';
		$sort_comments_option .= '>'.$lang['Comments'].'</option>';

		$sort_new_comment_option = '<option value="new_comment" ';
		$sort_new_comment_option .= ($sort_method == 'new_comment') ? 'selected="selected"' : '';
		$sort_new_comment_option .= '>' . $lang['New_Comment'] . '</option>';
	}
}

/*
+----------------------------------------------------------
| Start output the page
+----------------------------------------------------------
*/
$meta_content['page_title'] = $lang['Album'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';


// is it a public gallery ?
if ($album_user_id == ALBUM_PUBLIC_GALLERY)
{
	$template_to_parse = 'album_index_body.tpl';

	$cols = ($album_config['img_cols'] == 0 ? 4 : $album_config['img_cols']);
	$cols_width = (100 / $cols) . '%';

	// Last Comments
	if ($album_config['show_last_comments'] == 1)
	{
		album_build_last_comments_info($allowed_cat);
	}

	// Recent Public Pics
	if ($album_config['disp_late'] == 1)
	{
		album_build_recent_pics($allowed_cat);
	}

	// Highest Rated Pics
	if ($album_config['disp_high'] == 1)
	{
		album_build_highest_rated_pics($allowed_cat);
	}

	// Most Viewed Pics
	if ($album_config['disp_mostv'] == 1)
	{
		album_build_most_viewed_pics($allowed_cat);
	}

	//Random Pics
	if ($album_config['disp_rand'] == 1)
	{
		album_build_random_pics($allowed_cat);
	}

	$template->assign_vars(array(
		'BREADCRUMBS_ADDRESS' => (empty($breadcrumbs_address) ? (($meta_content['page_title_clean'] != htmlspecialchars($config['sitename'])) ? ($lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $meta_content['page_title_clean'] . '</a>') : '') : $breadcrumbs_address),

		'ALBUM_NAV' => $album_nav_cat_desc,
		'S_COLS' => $cols,
		'S_COL_WIDTH' => $cols_width,
		'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',
		'L_RAND_PICS' => $lang['Random_Pictures'],
		'L_HI_RATINGS' => $lang['Highest_Rated_Pictures'],
		'L_RECENT_PUBLIC_PICS' => $lang['Recent_Public_Pics'],
		'L_MOST_VIEWED' => $lang['Most_Viewed_Pictures'],
		'L_NO_PICS' => $lang['No_Pics'],
		'L_PIC_TITLE' => $lang['Pic_Image'],
		'L_PIC_ID' => $lang['Pic_ID'],
		'L_VIEW' => $lang['View'],
		'L_POSTER' => $lang['Pic_Poster'],
		'L_POSTED' => $lang['Posted'],

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
		)
	);
}
// it's a personal gallery, and in the root folder
else
{
	if ($album_view_mode == ALBUM_VIEW_LIST)
	{
		include (ALBUM_MOD_PATH . 'album_memberlist.' . PHP_EXT);
	}
	else
	{
		// include our special personal gallery files
		// this file holds all the code to handle personal galleries
		// except moderation and management of personal gallery categories.
		include (ALBUM_MOD_PATH . 'album_personal.' . PHP_EXT);
	}
}

if (empty($album_view_mode))
{
	album_display_index($album_user_id, ALBUM_ROOT_CATEGORY, true, true, true);
}

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>