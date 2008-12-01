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
* Volodymyr (CLowN) Skoryk (blaatimmy72@yahoo.com)
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

$page_title = $lang['Search'];
$meta_description = '';
$meta_keywords = '';
$nav_server_url = create_server_url();
$album_nav_cat_desc = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album_search.' . PHP_EXT) . '" class="nav-current">' . $lang['Search'] . '</a>';
$breadcrumbs_address = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'album_search_body.tpl'));

if ((isset($_POST['search']) || isset($_GET['search'])) && (($_POST['search'] != '') || ($_GET['search'] != '')))
{
	$template->assign_block_vars('switch_search_results', array());

	if (isset($_POST['mode']))
	{
		$m = $_POST['mode'];
	}
	elseif (isset($_GET['mode']))
	{
		$m = $_GET['mode'];
	}
	else
	{
		message_die(GENERAL_ERROR, 'Bad request');
	}

	if (isset($_POST['search']))
	{
		$s = mysql_real_escape_string($_POST['search']);
	}
	elseif (isset($_GET['search']))
	{
		$s = mysql_real_escape_string($_GET['search']);
	}

	if ($m == 'user')
	{
		$where = "AND p.pic_username LIKE '%" . $s . "%'";
	}
	elseif ($m == 'name')
	{
		$where = "AND p.pic_title LIKE '%" . $s . "%'";
	}
	elseif ($m == 'desc')
	{
		$where = "AND p.pic_desc LIKE '%" . $s . "%'";
	}
	elseif ($m == 'name_desc')
	{
		$where = "AND (p.pic_desc LIKE '%" . $s . "%' OR p.pic_title LIKE '%" . $s . "%')";
	}
	else
	{
		message_die(GENERAL_ERROR, 'Bad request');
	}

	// --------------------------------
	// Pagination
	// --------------------------------

	// Number of matches displayed
	$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];
	if ($pics_per_page == 0)
	{
		$pics_per_page = 20;
	}
	//$pics_per_page = 4;

	$start = isset($_GET['start']) ? intval($_GET['start']) : (isset($_POST['start']) ? intval($_POST['start']) : 0);
	$start = ($start < 0) ? 0 : $start;

	// ------------------------------------
	// Count pic matches
	// ------------------------------------

	if (($album_config['personal_gallery_view'] == -1) || ($userdata['user_level'] == ADMIN))
	{
		$search_pg = '';
	}
	else
	{
		$search_pg = 'AND c.cat_user_id = 0';
	}
	$limit_sql = ($start == 0) ? $pics_per_page : $start . ',' . $pics_per_page;

	$count_sql = "SELECT COUNT(pic_id) AS count
								FROM " . ALBUM_TABLE . ' AS p,' . ALBUM_CAT_TABLE . " AS c
								WHERE p.pic_approval = 1
								AND p.pic_cat_id = c.cat_id
								" . $where . "
								" . $search_pg;

	if(!($result = $db->sql_query($count_sql)))
	{
		message_die(GENERAL_ERROR, 'Could not count '.$m, '', __LINE__, __FILE__, $count_sql);
	}

	$row = $db->sql_fetchrow($result);

	$total_pics = $row['count'];

$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_approval, c.cat_id, c.cat_title, c.cat_user_id
				FROM " . ALBUM_TABLE . ' AS p,' . ALBUM_CAT_TABLE . " AS c
				WHERE p.pic_approval = 1
					AND p.pic_cat_id = c.cat_id
					" . $where . "
					" . $search_pg . "
				ORDER BY p.pic_time DESC LIMIT ".$limit_sql."";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain a list of matching information (searching for: $search)", "", __LINE__, __FILE__, $sql);
	}

	$numres = 0;

	if ($row = $db->sql_fetchrow($result))
	{
		$in = array();
		do
		{
			if (!in_array($row['pic_id'], $in))
			{
				$album_user_id = $row['cat_user_id'];
				$cat_id = $row['cat_id'];
				//$cat_id = album_get_personal_root_id($album_user_id);

				$check_permissions = ALBUM_AUTH_VIEW|ALBUM_AUTH_RATE|ALBUM_AUTH_COMMENT|ALBUM_AUTH_EDIT|ALBUM_AUTH_DELETE;
				$auth_data = album_permissions($album_user_id, $cat_id, $check_permissions, $row);
				//$auth_data = album_get_auth_data($cat_id);

				if ($album_config['lb_preview'] == 0)
				{
					$pic_preview = '';
				}
				else
				{
					$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $row['pic_id'])) . '\',\'' . addslashes($row[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
				}

				//if(!$auth_data['view'])
				if ($auth_data['view'] >= 0)
				{
					$template->assign_block_vars('switch_search_results.search_results', array(
						'L_USERNAME' => $row['pic_username'],
						'U_PROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&u=' . $row['pic_user_id']),

						'L_CAT' => ($row['cat_user_id'] != ALBUM_PUBLIC_GALLERY) ? $lang['Users_Personal_Galleries'] : $row['cat_title'],
						'U_CAT' => ($row['cat_id'] == $cat_id) ? append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $row['cat_id'])) : append_sid(album_append_uid('album.' . PHP_EXT)),

						'L_PIC' => $row['pic_title'],
						'U_PIC' => ($album_config['fullpic_popup'] == 1) ? append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $row['pic_id'])) : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $row['pic_id'])),
						'THUMBNAIL' => append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $row['pic_id'])),
						'PIC_PREVIEW' => $pic_preview,
						'PIC_TITLE' => htmlspecialchars($row['pic_title']),
						'DESC' => htmlspecialchars($row['pic_desc']),
						'L_TIME' => create_date($board_config['default_dateformat'], $row['pic_time'], $board_config['board_timezone'])
						)
					);

					$in[$numres] = $row['pic_id'];
					$numres++;
				}
			}
		}
		while($row = $db->sql_fetchrow($result));

		$template->assign_vars(array(
			'L_NRESULTS' => $numres,
			'L_TRESULTS' => $total_pics,
			'IMG_FOLDER' => $images['topic_nor_read'],
			'L_TCATEGORY' => $lang['Pic_Cat'],
			'L_TTITLE' => $lang['Pic_Image'],
			'L_TSUBMITER' => $lang['Author'],
			'L_TSUBMITED' => $lang['Time']
			)
		);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_search_match']);
	}
}
else
{
	message_die(GENERAL_ERROR, 'Bad request');
	//$template->assign_block_vars('switch_search', array());
}

// --------------------------------
// Pagination
// --------------------------------

$template->assign_vars(array(
	'PAGINATION' => generate_pagination(append_sid(album_append_uid('album_search.' . PHP_EXT . '?mode=' . $m . '&amp;search=' . $s)), $total_pics, $pics_per_page, $start),
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $pics_per_page) + 1), ceil($total_pics / $pics_per_page))
	)
);

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>