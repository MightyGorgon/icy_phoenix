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
include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

$comment_text_message = request_var('message', '', true);
$comment_text = request_var('comment', '', true);
$comment_text = (!empty($comment_text_message) ? $comment_text_message : $comment_text);

$mode = request_var('mode', '');
if($mode == 'smilies')
{
	generate_smilies('window');
	exit;
}

// ------------------------------------
// Check the request
// ------------------------------------
$pic_id = request_var('pic_id', 0);
if ($pic_id <= 0)
{
	message_die(GENERAL_MESSAGE, 'No pics specified');
}

$comment_id = request_var('comment_id', 0);
$comment_id = ($comment_id < 0) ? 0 : $comment_id;

// Midthumb & Full Pic
if(isset($_GET['full']) || isset($_POST['full']))
{
	$picm = false;
	$full_size_param = '&amp;full=true';
}
else
{
	if ($album_config['midthumb_use'] == 1)
	{
		$picm = true;
		$full_size_param = '';
	}
	else
	{
		$picm = false;
		$full_size_param = '&amp;full=true';
	}
}

$sort_method = request_var('sort_method', $album_config['sort_method']);
$sort_method = check_var_value($sort_method, array('pic_time', 'pic_title', 'pic_view_count'));

$sort_order = request_var('order', $album_config['sort_order']);
$sort_order = check_var_value(strtoupper($sort_order), array('ASC', 'DESC'));

$sort_append = '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order;

$is_slideshow = request_var('slideshow', 0);
$is_slideshow = !empty($is_slideshow) ? true : false;

// ------------------------------------
// TEMPLATE ASSIGNEMENT
// ------------------------------------
if ($is_slideshow)
{
	$gen_simple_header = true;
	$show_template = 'album_slideshow_body.tpl';
	$nuffimage_pic = ($picm == false) ? 'album_pic.' : 'album_picm.';
}
else
{
	//$show_template = 'album_showpage_body.tpl';
	if ((isset($_GET['nuffimage']) || isset($_POST['nuffimage'])) & ($album_config['enable_nuffimage'] == 1))
	{
		include(ALBUM_MOD_PATH . 'album_nuffimage_box.' . PHP_EXT);
		$template->assign_var_from_handle('NUFFIMAGE_BOX', 'nuffimage_box');
		$show_template = 'album_pic_nuffed_body.tpl';
		$nuffimage_vars = '&amp;nuffimage=true';
		$nuffimage_pic = 'album_pic_nuffed.';
		$nuff_http_full_string = $nuff_http['full_string'];
		$template->assign_block_vars('disable_pic_nuffed', array(
			'L_PIC_UNNUFFED_CLICK' => $lang['Nuff_UnClick'],
			'U_PIC_UNNUFFED_CLICK' => append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . $full_size_param . $sort_append)),
			)
		);
	}
	else
	{
		$show_template = 'album_showpage_body.tpl';
		$nuffimage_vars = '';
		$nuffimage_pic = ($picm == false) ? 'album_pic.' : 'album_picm.';
		$nuff_http_full_string = '';
	}
}


// ------------------------------------
// PREVIOUS / NEXT / PICS NAV
// ------------------------------------

$sql = "SELECT pic_id, pic_cat_id, pic_user_id, pic_time
		FROM " . ALBUM_TABLE . "
		WHERE pic_id = " . $pic_id;
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
if(empty($row))
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
}

$pic_id_old = $pic_id;
$pic_id_tmp = $row['pic_id'];
$pic_cat_id_tmp = $row['pic_cat_id'];
$pic_time_tmp = $row['pic_time'];
$pic_user_id_tmp = $row['pic_user_id'];
$db->sql_freeresult($result);

$sql_order = 'ORDER BY a.' . $sort_method . ' ' . $sort_order;
$sql = "SELECT *
		FROM " . ALBUM_TABLE . " AS a
		WHERE a.pic_cat_id = " . $pic_cat_id_tmp . "
			AND a.pic_approval = 1
		" . $sql_order;
$result = $db->sql_query($sql);
$total_pic_count = $db->sql_numrows($result);
$total_pic_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

if ($album_config['slideshow_script'])
{
	$template->assign_block_vars('switch_slideshow_scripts', array());

	$pic_link = ($picm == false) ? 'album_pic.' : 'album_picm.';

	$pic_list = '';
	$tit_list = '';
	$des_list = '';

	for($i = 0; $i < $total_pic_count; $i++)
	{
		if ($pic_id == $total_pic_rows[$i]['pic_id'])
		{
			$pic_array_id = $i;
		}
		$pic_list .= 'Pic[' . $i . '] = \'' . append_sid(album_append_uid($pic_link . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id']), true) . '\'; ' . "\n";
		/*
		$tit_list .= 'Tit[' . $i . '] = \'' . str_replace("'", "\'", $total_pic_rows[$i]['pic_title']) . '\'; ' . "\n";
		$des_list .= 'Des[' . $i . '] = \'' . str_replace(array("\r\n", "\n", "\r"), array('\n', '\n', '\n'), str_replace("'", "\'", $total_pic_rows[$i]['pic_desc'])) . '\'; ' . "\n";
		*/
		$tit_list .= 'Tit[' . $i . '] = \'' . addslashes($total_pic_rows[$i]['pic_title']) . '\'; ' . "\n";
		$des_list .= 'Des[' . $i . '] = \'' . str_replace(array("\r\n", "\n", "\r"), array('\n', '\n', '\n'), addslashes($total_pic_rows[$i]['pic_desc'])) . '\'; ' . "\n";
		/*
		$pic_list .= 'Pic[' . $i . '] = \'' . ALBUM_UPLOAD_PATH . $total_pic_rows[$i]['pic_filename'] . '\'; ' . "\n";
		*/
	}

	$template->assign_vars(array(
		'S_SLIDESHOW_SCRIPTS' => true,
		'PIC_LIST' => $pic_list,
		'TIT_LIST' => $tit_list,
		'DES_LIST' => $des_list,
		)
	);
}
else
{
	for($i = 0; $i < $total_pic_count; $i++)
	{
		if ($pic_id == $total_pic_rows[$i]['pic_id'])
		{
			$pic_array_id = $i;
		}
	}
}

$first_pic_id = $total_pic_rows[0]['pic_id'];
$last_pic_id = $total_pic_rows[$total_pic_count - 1]['pic_id'];

if ($pic_array_id == 0)
{
	$no_prev_pic = true;
	if(isset($_GET['mode']) && ($_GET['mode'] == 'next'))
	{
		message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
	}
}

if ($pic_array_id == ($total_pic_count - 1))
{
	$no_next_pic = true;
	if(isset($_GET['mode']) && ($_GET['mode'] == 'prev'))
	{
		message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
	}
}

// ------------------------------------
// PREVIOUS & NEXT
// ------------------------------------
$pic_id_old = $total_pic_rows[$pic_array_id]['pic_id'];
if(isset($_GET['mode']) && ($_GET['mode'] == 'next'))
{
	$new_pic_array_id = $pic_array_id - 1;
	if ($new_pic_array_id == 0)
	{
		$no_prev_pic = true;
	}
	else
	{
		$no_prev_pic = false;
		$no_next_pic = false;
	}
}
elseif(isset($_GET['mode']) && ($_GET['mode'] == 'prev'))
{
	$new_pic_array_id = $pic_array_id + 1;
	if ($new_pic_array_id == ($total_pic_count - 1))
	{
		$no_next_pic = true;
	}
	else
	{
		$no_next_pic = false;
		$no_prev_pic = false;
	}
}
else
{
	$new_pic_array_id = $pic_array_id;
}
$pic_id_tmp = $total_pic_rows[$new_pic_array_id]['pic_id'];
$pic_cat_id_tmp = $total_pic_rows[$new_pic_array_id]['pic_cat_id'];
$pic_time_tmp = $total_pic_rows[$new_pic_array_id]['pic_time'];
$pic_user_id_tmp = $total_pic_rows[$new_pic_array_id]['pic_user_id'];
$next_pic_count = ($total_pic_count - $new_pic_array_id - 1);
$prev_pic_count = ($new_pic_array_id);

if(isset($_GET['mode']))
{
	if (($_GET['mode'] == 'next') || ($_GET['mode'] == 'prev'))
	{
		$pic_id = $pic_id_tmp;
	}
}

if ($album_config['show_pics_nav'] == 1)
{
	$template->assign_block_vars('pics_nav', array(
		'L_PICS_NAV' => $lang['Pics_Nav'],
		'L_PICS_NAV_NEXT' => $lang['Pics_Nav_Next'],
		'L_PICS_NAV_PREV' => $lang['Pics_Nav_Prev'],
		)
	);
}

if (!$album_config['invert_nav_arrows'])
{
	$max_pic_counter = min(($total_pic_count - 1), ($new_pic_array_id + 2));
	$min_pic_counter = max(0, ($new_pic_array_id - 2));
	for($i = $min_pic_counter; $i <= $max_pic_counter; $i++)
	{
		$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id']));
		if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
		{
			$thumbnail_file = picture_quick_thumb($total_pic_rows[$i]['pic_filename'], $total_pic_rows[$i]['pic_thumbnail'], $thumbnail_file);
		}

		$pic_preview = '';
		$pic_preview_hs = '';
		if ($album_config['lb_preview'])
		{
			$slideshow_cat = '';
			$slideshow = !empty($slideshow_cat) ? ', { slideshowGroup: \'' . $slideshow_cat . '\' } ' : '';
			$pic_preview_hs = ' class="highslide" onclick="return hs.expand(this' . $slideshow . ');"';

			$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id'])) . '\',\'' . addslashes($total_pic_rows[$i]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
		}

		if ($album_config['show_pics_nav'])
		{
			$template->assign_block_vars('pics_nav.pics', array(
				'U_PIC_THUMB' => $thumbnail_file,
				'U_PIC_LINK' => ($i == $new_pic_array_id) ? '#' : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id'] . $full_size_param . $nuffimage_vars . $sort_append)),
				'U_PIC_LINK_HS' => append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id'])),
				'PIC_TITLE' => $total_pic_rows[$i]['pic_title'],
				'PIC_PREVIEW_HS' => $pic_preview_hs,
				'PIC_PREVIEW' => ($i == $new_pic_array_id) ? '' : $pic_preview,
				'STYLE' => ($i == $new_pic_array_id) ? 'border: solid 3px #ff5522;' : '',
				)
			);
		}
	}
}
else
{
	$max_pic_counter = max(0, ($new_pic_array_id - 2));
	$min_pic_counter = min(($total_pic_count - 1), ($new_pic_array_id + 2));
	for($i = $min_pic_counter; $i >= $max_pic_counter; $i--)
	{
		$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id']));
		if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
		{
			$thumbnail_file = picture_quick_thumb($total_pic_rows[$i]['pic_filename'], $total_pic_rows[$i]['pic_thumbnail'], $thumbnail_file);
		}

		$pic_preview = '';
		$pic_preview_hs = '';
		if ($album_config['lb_preview'])
		{
			$slideshow_cat = '';
			$slideshow = !empty($slideshow_cat) ? ', { slideshowGroup: \'' . $slideshow_cat . '\' } ' : '';
			$pic_preview_hs = ' class="highslide" onclick="return hs.expand(this' . $slideshow . ');"';

			$pic_preview = 'onmouseover="showtrail(\'' . append_sid(album_append_uid('album_picm.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id'])) . '\',\'' . addslashes($total_pic_rows[$i]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
		}

		if ($album_config['show_pics_nav'] == 1)
		{
			$template->assign_block_vars('pics_nav.pics', array(
				'U_PIC_THUMB' => $thumbnail_file,
				'U_PIC_LINK' => ($i == $new_pic_array_id) ? '#' : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id'] . $full_size_param . $nuffimage_vars . $sort_append)),
				'U_PIC_LINK_HS' => append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $total_pic_rows[$i]['pic_id'])),
				'PIC_TITLE' => $total_pic_rows[$i]['pic_title'],
				'PIC_PREVIEW_HS' => $pic_preview_hs,
				'PIC_PREVIEW' => ($i == $new_pic_array_id) ? '' : $pic_preview,
				'STYLE' => ($i == $new_pic_array_id) ? 'border: solid 3px #FF5522;' : '',
				)
			);
		}
	}
}

// ------------------------------------
// SPECIAL FX
// ------------------------------------
if ($album_config['enable_nuffimage'] == 1)
{
	$template->assign_block_vars('pic_nuffed_enabled', array(
		'L_PIC_NUFFED_CLICK' => $lang['Nuff_Click'],
		'U_PIC_NUFFED_CLICK' => append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . $full_size_param . '&amp;nuffimage=true&amp;' . $sort_append)),
		)
	);
}
else
{
	$template->assign_block_vars('switch_slideshow_no_scripts', array());
}

// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------

if(!empty($comment_id) && $album_config['comment'] == 1)
{
	$sql = "SELECT comment_id, comment_pic_id
			FROM ". ALBUM_COMMENT_TABLE ."
			WHERE comment_id = '$comment_id'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if(empty($row))
	{
		message_die(GENERAL_ERROR, 'This comment does not exist');
	}

	$pic_id = $row['comment_pic_id'];
}

// ------------------------------------
// Get this pic info and current category info
// ------------------------------------

$sql = "SELECT p.*, ac.*, u.user_id, u.username, u.user_active, u.user_color, u.user_rank, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments_count
		FROM " . ALBUM_CAT_TABLE . " AS ac, " . ALBUM_TABLE . " AS p
			LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
			LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_id = c.comment_pic_id
			LEFT JOIN " . ALBUM_RATE_TABLE . " AS r ON p.pic_id = r.rate_pic_id
		WHERE pic_id = '$pic_id'
			AND ac.cat_id = p.pic_cat_id
		GROUP BY p.pic_id
		LIMIT 1";
$result = $db->sql_query($sql);
$thispic = $db->sql_fetchrow($result);

$cat_id = ($thispic['pic_cat_id'] != 0) ? $thispic['pic_cat_id'] : $thispic['cat_id'];
$album_user_id = $thispic['cat_user_id'];

$total_comments = $thispic['comments_count'];
$comments_per_page = $config['posts_per_page'];

if(empty($thispic))
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist'] . $lang['Nav_Separator'] . $pic_id);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$check_permissions = ALBUM_AUTH_VIEW|ALBUM_AUTH_RATE|ALBUM_AUTH_COMMENT|ALBUM_AUTH_EDIT|ALBUM_AUTH_DELETE;
$auth_data = album_permissions($album_user_id, $cat_id, $check_permissions, $thispic);

if ($auth_data['view'] == 0)
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_showpage.' . PHP_EXT . '&amp;pic_id=' . $pic_id));
		exit;
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorized']);
	}
}

// ------------------------------------
//RATING:  Additional Check: if this user already rated
// ------------------------------------
$own_pic_rate = false;
if($user->data['session_logged_in'])
{
	$sql = "SELECT *
			FROM ". ALBUM_RATE_TABLE ."
			WHERE rate_pic_id = '$pic_id'
				AND rate_user_id = '". $user->data['user_id'] ."'
			LIMIT 1";
	$result = $db->sql_query($sql);

	if ($db->sql_numrows($result) > 0)
	{
		$already_rated = true;
	}
	else
	{
		$already_rated = false;
	}

	if ($thispic['pic_user_id'] == $user->data['user_id'])
	{
		$own_pic_rate = true;
	}
}
else
{
	$already_rated = false;
}

// Watch pic for comments - BEGIN
if($user->data['session_logged_in'])
{
	//$can_watch_comment = true;

	$sql = "SELECT notify_status
		FROM " . ALBUM_COMMENT_WATCH_TABLE . "
		WHERE pic_id = $pic_id
			AND user_id = " . $user->data['user_id'] . "
		LIMIT 1";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$is_watching_comments = true;
		if (isset($_GET['unwatch']))
		{
			if ($_GET['unwatch'] == 'comment')
			{
				$sql = "DELETE FROM " . ALBUM_COMMENT_WATCH_TABLE . "
					WHERE pic_id = $pic_id
						AND user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);
				$is_watching_comment = false;
			}

			$redirect_url = append_sid('album.' . PHP_EXT);
			meta_refresh(3, $redirect_url);

			$message = $lang['No_longer_watching_comment'] . '<br /><br />' . sprintf($lang['Click_return_pic'], '<a href="' . append_sid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_comment = true;

			if ($row['notify_status'])
			{
				$sql = "UPDATE " . ALBUM_COMMENT_WATCH_TABLE . "
					SET notify_status = 0
					WHERE pic_id = $pic_id
						AND user_id = " . $user->data['user_id'];
				$result = $db->sql_query($sql);
			}
		}
	}
	// Set pic for watch request
	if (isset($_GET['watch']))
	{
		if ($_GET['watch'] == 'comment')
		{
			$sql = "INSERT INTO " . ALBUM_COMMENT_WATCH_TABLE . " (pic_id, user_id, notify_status)
				VALUES ($pic_id, " . $user->data['user_id'] . ", 0)";
			$result = $db->sql_query($sql);
		}

		$redirect_url = append_sid('album.' . PHP_EXT);
		meta_refresh(3, $redirect_url);

		$message = $lang['Watching_comment'] . '<br /><br />' . sprintf($lang['Click_return_pic'], '<a href="' . append_sid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);

	}
}
// Watch pic for comments - END

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/
album_read_tree($album_user_id);
$album_nav_cat_desc = album_make_nav_tree($cat_id, 'album_cat.' . PHP_EXT, 'nav' , $album_user_id);
if ($album_nav_cat_desc != '')
{
	$nav_server_url = create_server_url();
	$album_nav_cat_desc = ALBUM_NAV_ARROW . $album_nav_cat_desc;
	$breadcrumbs_address = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;
}

if(empty($comment_text) && !isset($_POST['rating']))
{

	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Comments Screen
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// ------------------------------------
	// Get the comments thread
	// Beware: when this script was called with comment_id (without start)
	// ------------------------------------
	if ($album_config['comment'])
	{
		if(empty($comment_id))
		{
			$start = request_var('start', 0);
			$start = ($start < 0) ? 0 : $start;
		}
		else
		{
			// We must do a query to co-ordinate this comment
			$sql = "SELECT COUNT(comment_id) AS count
					FROM ". ALBUM_COMMENT_TABLE ."
					WHERE comment_pic_id = $pic_id
						AND comment_id < $comment_id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			if(!empty($row))
			{
				$start = floor($row['count'] / $comments_per_page) * $comments_per_page;
			}
			else
			{
				$start = 0;
			}
		}

		if ($total_comments > 0)
		{
			$template->assign_block_vars('coment_switcharo_top', array());

			$limit_sql = ($start == 0) ? $comments_per_page : $start . ',' . $comments_per_page;
			$comment_sort_order = (!empty($_GET['comment_sort_order'])) ? $_GET['comment_sort_order'] : 'ASC';
			$comment_sort_order = (strtoupper($comment_sort_order) == 'DESC') ? 'DESC' : 'ASC';

			$sql = "SELECT c.*, u.*
				FROM " . ALBUM_COMMENT_TABLE . " AS c
					LEFT JOIN " . USERS_TABLE . " AS u ON c.comment_user_id = u.user_id
				WHERE c.comment_pic_id = '$pic_id'
				ORDER BY c.comment_id $comment_sort_order
				LIMIT $limit_sql";
			$result = $db->sql_query($sql);
			$commentrow = array();
			while($row = $db->sql_fetchrow($result))
			{
				$commentrow[] = $row;
			}
			$db->sql_freeresult($result);

			// Ranks
			@include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
			$ranks_array = $cache->obtain_ranks(false);

			for ($i = 0; $i < sizeof($commentrow); $i++)
			{
				$poster = ($commentrow[$i]['comment_username'] == '') ? $lang['Guest'] : colorize_username($commentrow[$i]['user_id'], $commentrow[$i]['username'], $commentrow[$i]['user_color']);

				if ($commentrow[$i]['comment_edit_count'] > 0)
				{
					$sql = "SELECT c.comment_id, c.comment_edit_user_id, u.user_id, u.username, u.user_color
							FROM " . ALBUM_COMMENT_TABLE . " AS c
								LEFT JOIN " . USERS_TABLE . " AS u ON c.comment_edit_user_id = u.user_id
							WHERE c.comment_id = '".$commentrow[$i]['comment_id']."'
							LIMIT 1";
					$result = $db->sql_query($sql);
					$lastedit_row = $db->sql_fetchrow($result);
					$edit_info = ($commentrow[$i]['comment_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
					$edit_info = '<br /><br />&raquo;&nbsp;' . sprintf($edit_info, colorize_username($lastedit_row['user_id'], $lastedit_row['username'], $lastedit_row['user_color']), create_date_ip($config['default_dateformat'], $commentrow[$i]['comment_edit_time'], $config['board_timezone']), $commentrow[$i]['comment_edit_count']) .'<br />';
				}
				else
				{
					$edit_info = '';
				}

				$commentrow[$i]['comment_text'] = censor_text($commentrow[$i]['comment_text']);

				// Smilies
				$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
				$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
				$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;
				$bbcode->allow_html = $html_on;
				$bbcode->allow_bbcode = $bbcode_on;
				$bbcode->allow_smilies = $smilies_on;

				$commentrow[$i]['comment_text'] = strtr($commentrow[$i]['comment_text'], array_flip(get_html_translation_table(HTML_ENTITIES)));
				$commentrow[$i]['comment_text'] = $bbcode->parse($commentrow[$i]['comment_text']);

				$commentrow[$i]['comment_text'] = $bbcode->acronym_pass($commentrow[$i]['comment_text']);
				$commentrow[$i]['comment_text'] = $bbcode->autolink_text($commentrow[$i]['comment_text'], '999999');

				$user_sig = ($config['allow_sig']) ? trim($commentrow[$i]['user_sig']) : '';
				if($user_sig != '')
				{
					$bbcode->is_sig = true;
					$user_sig = $bbcode->parse($user_sig);
					$bbcode->is_sig = false;
				}

				$user_info = array();
				$user_info = generate_user_info($commentrow[$i]);
				foreach ($user_info as $k => $v)
				{
					$$k = $v;
				}

				$poster_avatar = $user_info['avatar'];

				// Mighty Gorgon - Multiple Ranks - BEGIN
				$user_ranks = generate_ranks($commentrow[$i], $ranks_array);
				if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html']  == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
				{
					$user_ranks['rank_01_html'] = '&nbsp;';
				}
				// Mighty Gorgon - Multiple Ranks - END

				$poster_rank = $user_ranks['rank_01_html'];
				$rank_image = $user_ranks['rank_01_img_html'];

				// Handle anon users posting with usernames
				if (($commentrow[$i]['user_id'] == ANONYMOUS) && ($commentrow[$i]['post_username'] != ''))
				{
					$poster = colorize_username($commentrow[$i]['user_id'], $commentrow[$i]['username'], $commentrow[$i]['user_color']);
					$poster_rank = $lang['Guest'];
				}

				if ($user->data['user_level'] == ADMIN)
				{
					$ip_url = 'http://www.nic.com/cgi-bin/whois.cgi?query=' . htmlspecialchars(urlencode($commentrow[$i]['comment_user_ip']));
					$ip_img = '<a href="' . $ip_url . '" target="_blank"><img src="' . $images['icon_ip2'] . '" alt="' . $lang['View_IP'] . ' (' . htmlspecialchars($commentrow[$i]['comment_user_ip']) . ')" title="' . $lang['View_IP'] . ' (' . htmlspecialchars($commentrow[$i]['comment_user_ip']) . ')" /></a>';
					$ip = '<a href="' . $ip_url . '">' . $lang['View_IP'] . '</a>';
				}
				else
				{
					$ip_img = '';
					$ip = '';
				}

				$edit_url = append_sid(album_append_uid('album_comment_edit.' . PHP_EXT . '?comment_id=' . $commentrow[$i]['comment_id']));
				$delete_url = append_sid(album_append_uid('album_comment_delete.' . PHP_EXT . '?comment_id=' . $commentrow[$i]['comment_id']));

				$template->assign_block_vars('commentrow', array(
					'ID' => $commentrow[$i]['comment_id'],
					'POSTER_NAME' => $poster,
					'COMMENT_TIME' => create_date_ip($config['default_dateformat'], $commentrow[$i]['comment_time'], $config['board_timezone']),
					'IP' => ($user->data['user_level'] == ADMIN) ? '<a href="' . $ip_url . '" target="_blank">' . htmlspecialchars($commentrow[$i]['comment_user_ip']) .'</a><br />' : '',
					'IP_IMG' => $ip_img,
					'POSTER_ONLINE_STATUS_IMG' => $online_status_img,

					//users mesangers, website, email
					'PROFILE_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? '<a href="' . $profile_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>' : '',
					'PM_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>' : '',
					'EMAIL_IMG' => (($commentrow[$i]['user_id'] != ANONYMOUS) && ($email_url != '')) ? '<a href="' . $email_url . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>' : '',
					'WWW_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? ($commentrow[$i]['user_website']) ? '<a href="' . $commentrow[$i]['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '' : '',
					'AIM_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $aim_img : '',
					'SKYPE_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $skype_img : '',
					'ICQ_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $icq_img : '',
					'MSNM_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $msn_img : '',
					'YIM_IMG' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $yahoo_img : '',

					'U_PROFILE' => $profile_url,
					'U_PM' => $pm_url,
					'U_IP' => $ip_url,
					'U_EMAIL' => $email_url,
					'U_WWW' => $www_url,
					'U_AIM' => $aim_url,
					'U_ICQ' => $icq_url,
					'U_MSN' => $msn_url,
					'U_SKYPE' => $skype_url,
					'U_YIM' => $yahoo_url,
					'L_POSTER_ONLINE_STATUS' => $online_status_lang,
					'POSTER_ONLINE_STATUS_CLASS' => $online_status_class,
					'U_POSTER_ONLINE_STATUS' => $online_status_url,

					'POSTER_AVATAR' => $poster_avatar,
					'POSTER_RANK' => $poster_rank,
					'POSTER_RANK_IMAGE' => $rank_image,
					'POSTER_JOINED' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $commentrow[$i]['user_regdate'], $config['board_timezone']) : '',
					'POSTER_POSTS' => ($commentrow[$i]['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $commentrow[$i]['user_posts'] : '',
					'POSTER_FROM' => ($commentrow[$i]['user_from'] && $commentrow[$i]['user_id'] != ANONYMOUS) ? $lang['Location'] . ': ' . $commentrow[$i]['user_from'] : '',
					'POSTER_SIGNATURE' => $user_sig,

					'TEXT' => $commentrow[$i]['comment_text'],
					'EDIT_INFO' => $edit_info,

					'U_EDIT' => (($auth_data['edit'] && ($commentrow[$i]['comment_user_id'] == $user->data['user_id'])) || ($auth_data['moderator'] && ($thispic['cat_edit_level'] != ALBUM_ADMIN)) || ($user->data['user_level'] == ADMIN)) ? $edit_url : '',

					'EDIT' => (($auth_data['edit'] && ($commentrow[$i]['comment_user_id'] == $user->data['user_id'])) || ($auth_data['moderator'] && ($thispic['cat_edit_level'] != ALBUM_ADMIN)) || ($user->data['user_level'] == ADMIN)) ? '<a href="' . $edit_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" /></a>' : '',

					'U_DELETE' => (($auth_data['delete'] && ($commentrow[$i]['comment_user_id'] == $user->data['user_id'])) || ($auth_data['moderator'] && ($thispic['cat_delete_level'] != ALBUM_ADMIN)) || ($user->data['user_level'] == ADMIN)) ? $delete_url : '',

					'DELETE' => (($auth_data['delete'] && ($commentrow[$i]['comment_user_id'] == $user->data['user_id'])) || ($auth_data['moderator'] && ($thispic['cat_delete_level'] != ALBUM_ADMIN)) || ($user->data['user_level'] == ADMIN)) ? '<a href="' . $delete_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>' : ''

					)
				);
			}
		}
	}

	// Mighty Gorgon - Slideshow - BEGIN
	if ($is_slideshow)
	{
		$template->assign_var('S_SLIDESHOW', true);

		$css_temp = array('fap_slideshow.css');
		$template->css_include = array_merge($template->css_include, $css_temp);
		unset($css_temp);

		$slideshow_delay = request_var('slideshow', 0);
		$slideshow_select = '';
		$slideshow_onoff = $lang['Slideshow_Off'];
		$slideshow_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id));
		$slideshow_link_full = '<a href="' . $slideshow_link . '">' . $lang['Slideshow_Off'] . '</a>';
		$pic_link = append_sid(album_append_uid($nuffimage_pic . PHP_EXT . '?pic_id=' . $pic_id));
		$pic_base_link = 'album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . $full_size_param . $sort_append;
		if ($album_config['invert_nav_arrows'] == 0)
		{
			$next_pic = ($no_prev_pic == false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=next' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_left_arrow3'] . '" title="' . $lang['Next_Pic'] . '" alt="' . $lang['Next_Pic'] . '" style="border:0px;vertical-align:middle;" /></a>' : '';
			$prev_pic = ($no_next_pic == false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=prev' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_right_arrow3'] . '" title="' . $lang['Prev_Pic'] . '" alt="' . $lang['Prev_Pic'] . '" style="border:0px;vertical-align:middle;" /></a>' : '';

			$next_pic_url = ($no_prev_pic == false) ? append_sid(album_append_uid($pic_base_link . '&amp;mode=next&amp;slideshow=' . $slideshow_delay)) . '#TopPic' : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $first_pic_id . $full_size_param . $sort_append)) . '#TopPic';
			$prev_pic_url = ($no_next_pic == false) ? append_sid(album_append_uid($pic_base_link . '&amp;mode=prev&amp;slideshow=' . $slideshow_delay)) . '#TopPic' : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $last_pic_id . $full_size_param . $sort_append)) . '#TopPic';
		}
		else
		{
			$next_pic = ($no_next_pic== false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=prev' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_left_arrow3'] . '" title="' . $lang['Prev_Pic'] . '" alt="' . $lang['Prev_Pic'] . '" style="border:0px;vertical-align:middle;" /></a>' : '';
			$prev_pic = ($no_prev_pic  == false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=next' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_right_arrow3'] . '" title="' . $lang['Next_Pic'] . '" alt="' . $lang['Next_Pic'] . '" style="border:0px;vertical-align:middle;" /></a>' : '';

			$next_pic_url = ($no_next_pic == false) ? append_sid(album_append_uid($pic_base_link . '&amp;mode=prev&amp;slideshow=' . $slideshow_delay)) . '#TopPic' : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $first_pic_id . $full_size_param . $sort_append)) . '#TopPic';
			$prev_pic_url = ($no_prev_pic == false) ? append_sid(album_append_uid($pic_base_link . '&amp;mode=next&amp;slideshow=' . $slideshow_delay)) . '#TopPic' : append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $last_pic_id . $full_size_param . $sort_append)) . '#TopPic';
		}
	}
	else
	{
		if ($album_config['show_slideshow'])
		{
			$template->assign_block_vars('switch_slideshow_enabled', array());
		}
		//$slideshow_delay = 5;
		$slideshow_select = $lang['Slideshow_Delay'] . ':&nbsp;';
		$slideshow_select .= '<select name="slideshow">';
		$slideshow_select .= '<option value="1">1 Sec</option>';
		$slideshow_select .= '<option value="3">3 Sec</option>';
		$slideshow_select .= '<option value="5" selected="selected">5 Sec</option>';
		$slideshow_select .= '<option value="7">7 Sec</option>';
		$slideshow_select .= '<option value="10">10 Sec</option>';
		$slideshow_select .= '</select>&nbsp;';
		$slideshow_onoff = $lang['Slideshow_On'];
		//$slideshow_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . '&amp;full=true&amp;slideshow=' . $slideshow_delay));
		$slideshow_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . '&amp;full=true'));
		$slideshow_link_full = '<a href="' . $slideshow_link . '">' . $lang['Slideshow_On'] . '</a>';
		$pic_link = append_sid(album_append_uid($nuffimage_pic . PHP_EXT . '?pic_id=' . $pic_id . $sort_append . $full_size_param . $nuff_http_full_string));
		$pic_base_link = 'album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . $full_size_param . $sort_append;
		if ($album_config['invert_nav_arrows'] == 0)
		{
			$next_pic = ($no_prev_pic == false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=next' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_left_arrow3'] . '" title="' . $lang['Next_Pic'] . '" style="border:0px;vertical-align:middle;" alt="' . $lang['Next_Pic'] . '" /></a>' : '';
			$prev_pic = ($no_next_pic == false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=prev' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_right_arrow3'] . '" title="' . $lang['Prev_Pic'] . '" style="border:0px;vertical-align:middle;" alt="' . $lang['Prev_Pic'] . '" /></a>' : '';
		}
		else
		{
			$next_pic = ($no_next_pic== false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=prev' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_left_arrow3'] . '" title="' . $lang['Prev_Pic'] . '" style="border:0px;vertical-align:middle;" alt="' . $lang['Prev_Pic'] . '" /></a>' : '';
			$prev_pic = ($no_prev_pic  == false) ? '<a href="' . append_sid(album_append_uid($pic_base_link . '&amp;mode=next' . $nuffimage_vars)) . '#TopPic" style="background-image: none; display: inline;"><img src="' . $images['icon_right_arrow3'] . '" title="' . $lang['Next_Pic'] . '" style="border:0px;vertical-align:middle;" alt="' . $lang['Next_Pic'] . '" /></a>' : '';
		}
	}

	//$temp_js = '<script type="text/javascript">window.attachEvent(\'onload\', runSlideShow();)</script>';
	if ($album_config['slideshow_script'])
	{
		$template->assign_var('S_SLIDESHOW_SCRIPT', true);
		//$slideshow_refresh = '</body><body onload="runSlideShow()">';
		//$slideshow_refresh = '<script type="text/javascript">onload_functions.push(\'runSlideShow()\');</script>';
		//$slideshow_refresh = $temp_js;
	}
	else
	{
		$slideshow_refresh = '<meta http-equiv="refresh" content="' . $slideshow_delay .  ';url=' . $next_pic_url . '">';
	}

	$template->assign_vars(array(
		// Mighty Gorgon - Slideshow - BEGIN
		'L_SLIDESHOW' => $lang['Slideshow'],
		'L_SLIDESHOW_DELAY' => $lang['Slideshow_Delay'],
		'L_SLIDESHOW_ONOFF' => $slideshow_onoff,

		'S_SLIDESHOW_REFRESH' => $slideshow_refresh,

		'SLIDESHOW_SELECT' => $slideshow_select,
		'SLIDESHOW_DELAY' => $slideshow_delay,

		'U_SLIDESHOW' => $slideshow_link,
		'U_SLIDESHOW_FULL' => $slideshow_link_full,
		'U_SLIDESHOW_REFRESH' => $slideshow_refresh,
		'U_SLIDESHOW_REFRESH_META' => '<meta http-equiv="refresh" content="' . $slideshow_delay .  ';url=' . $next_pic_url . '">',
		// Mighty Gorgon - Slideshow - END
		)
	);

	// Mighty Gorgon - Slideshow - END

	// Start output of page
	$meta_content['page_title'] = $lang['Album'] . ' - ' . $thispic['pic_title'];
	$meta_content['description'] = $lang['Album'] . ' - ' . strip_tags($thispic['cat_title']) . ' - ' . $thispic['pic_title'] . ' - ' . $thispic['pic_desc'];
	$meta_content['keywords'] = $lang['Album'] . ', ' . strip_tags($thispic['cat_title']) . ', ' . $thispic['pic_title'] . ', ' . $thispic['pic_desc'] . ', ';

	$poster = ($thispic['username'] == '') ? $lang['Guest'] : colorize_username($thispic['user_id'], $thispic['username'], $thispic['user_color'], $thispic['user_active']);

	//---------------------------------
	// Comment Posting Form
	//---------------------------------

	if (($auth_data['comment'] == 1) && $album_config['comment'])
	{
		$template->assign_block_vars('switch_comment_post', array());

		if(!$user->data['session_logged_in'])
		{
			$template->assign_block_vars('switch_comment_post.logout', array());
		}
	}

	// Rating System
	if ($album_config['rate'])
	{
		$image_rating = ImageRating($thispic['rating']);
		$template->assign_block_vars('rate_switch', array());

		if ($auth_data['rate'] == 1 && ($already_rated == false) && (($own_pic_rate == false) || ($user->data['user_level'] == ADMIN)))
		{
			$template->assign_block_vars('rate_switch.rate_row', array());
			for ($i = 0; $i < $album_config['rate_scale']; $i++)
			{
				$template->assign_block_vars('rate_switch.rate_row.rate_scale_row', array(
					'POINT' => ($i + 1)
					)
				);
			}
		}
	}

	// Mighty Gorgon - Pic Size - BEGIN
	$pic_fullpath = ALBUM_UPLOAD_PATH . $thispic['pic_filename'];
	$pic_size = @getimagesize($pic_fullpath);
	$pic_width = $pic_size[0];
	$pic_height = $pic_size[1];
	$pic_filesize = @filesize($pic_fullpath);
	// Mighty Gorgon - Pic Size - END

	if (($album_config['show_exif'] == 1) && (function_exists('exif_read_data')))
	{
		//echo(function_exists(exif_read_data));
		$template->assign_block_vars('switch_exif_enabled', array());
		$xif = @exif_read_data($pic_fullpath, 0, true);
		if (!empty($xif[IFD0]) || !empty($xif[EXIF]))
		{
			include_once(ALBUM_MOD_PATH . 'album_exif_info.' . PHP_EXT);
		}
	}

	$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = trim($config['server_name']);
	$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
	$script_name = ($script_name == '') ? '' : $script_name . '/';
	$server_path = $server_protocol . $server_name . $server_port . $script_name;

	$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $pic_id));
	if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
	{
		$thumbnail_file = picture_quick_thumb($thispic[$i]['pic_filename'], $thispic[$i]['pic_thumbnail'], $thumbnail_file);
	}

	$edit_link_content = $lang['Edit_pic'];
	$delete_link_content = $lang['Delete_pic'];
	$lock_link_content = ($thispic['pic_lock'] == 0) ? $lang['Lock'] : $lang['Unlock'];
	$move_link_content = $lang['Move'];
	$copy_link_content = $lang['Copy'];
	$style_used = explode('/', $template->files['body']);
	$allowed_styles = array(
		//'ca_aphrodite',
		'floreal',
		'icy_phoenix',
		'mg_themes',
		//'morpheus',
		'pearl',
		//'squared',
	);
	if(in_array($style_used[2], $allowed_styles) && (!empty($template->xs_version)))
	{
		$edit_link_content = '<img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_pic'] . '" title="' . $lang['Edit_pic'] . '" />';
		$delete_link_content = '<img src="' . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_pic'] . '" title="' . $lang['Delete_pic'] . '" />';
		$lock_link_content = ($thispic['pic_lock'] == 0) ? '<img src="' . $images['topic_mod_lock'] . '" alt="' . $lang['Lock'] . '" title="' . $lang['Lock'] . '" />' : '<img src="' . $images['topic_mod_unlock'] . '" alt="' . $lang['Unlock'] . '" title="' . $lang['Unlock'] . '" />';
		$move_link_content = '<img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move'] . '" title="' . $lang['Move'] . '" />';
		$copy_link_content = '<img src="' . $images['topic_mod_copy'] . '" alt="' . $lang['Copy'] . '" title="' . $lang['Copy'] . '" />';
	}

	$pic_desc = $thispic['pic_desc'];
	$parse_desc_bbcode = true;
	if ($parse_desc_bbcode)
	{
		$bbcode->allow_html = ($config['allow_html'] ? true : false);
		$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
		$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);
		$pic_desc = $bbcode->parse($pic_desc);
	}
	else
	{
		$pic_desc = nl2br(htmlspecialchars($pic_desc));
	}

	$pic_sp_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $thispic['pic_id']));
	$pic_dl_link = append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $thispic['pic_id']));

	$template->assign_vars(array(
		'CAT_TITLE' => $thispic['cat_title'],
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,
		'NAV_CAT_DESC' => $album_nav_cat_desc,
		'EDIT' => (($auth_data['moderator']) || ($user->data['user_id'] == $thispic['pic_user_id'])) ? '<a href="' . append_sid(album_append_uid('album_edit.' . PHP_EXT . '?pic_id=' . $thispic['pic_id'])) . '">' . $edit_link_content . '</a>' : '',
		'DELETE' => (($auth_data['moderator']) || ($user->data['user_id'] == $thispic['pic_user_id'])) ? '<a href="' . append_sid(album_append_uid('album_delete.' . PHP_EXT . '?pic_id=' . $thispic['pic_id'])) . '">' . $delete_link_content . '</a>' : '',
		'LOCK' => ($auth_data['moderator']) ? '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=' . (($thispic['pic_lock'] == 0) ? 'lock' : 'unlock') . '&amp;pic_id=' . $thispic['pic_id'])) . '">' . $lock_link_content . '</a>' : '',
		'MOVE' => ($auth_data['moderator']) ? '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=move&amp;pic_id=' . $thispic['pic_id'])) . '">' . $move_link_content . '</a>' : '',
		'COPY' => ($auth_data['moderator']) ? '<a href="'. append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=copy&amp;pic_id=' . $thispic['pic_id'])) . '">' . $copy_link_content . '</a>' : '',

		'U_PIC_FULL_URL' => $server_path . $pic_fullpath,

		//'U_PIC' => append_sid(album_append_uid($nuffimage_pic . PHP_EXT . '?pic_id=' . $pic_id . $sort_append . $full_size_param . $nuff_http_full_string)),
		'U_PIC' => $pic_link,
		'U_PIC_SP' => $pic_sp_link,
		'U_PIC_DL' => $pic_dl_link,
		//'U_PIC_L1' => ($picm == false) ? '' : '<a href="album_showpage.' . PHP_EXT . '?full=true&amp;pic_id=' . $pic_id . $nuffimage_vars . '">',
		'U_PIC_L1' => ($picm == false) ? '' : '<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?full=true&amp;pic_id=' . $pic_id . $sort_append . $nuffimage_vars)) . '">',
		'U_PIC_L2' => ($picm == false) ? '' : '</a>',
		'U_PIC_CLICK' => ($picm == false) ? '' : $lang['Click_enlarge'],
		'U_PIC_THUMB' => append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $pic_id . $sort_append)),
		'U_SMILEY_CREATOR' => append_sid('smiley_creator.' . PHP_EXT . '?mode=text2shield'),

		'PIC_COUNT' => sprintf($lang['Pics_Counter'], ($new_pic_array_id + 1), $total_pic_count),

		'NEXT_PIC' => $next_pic,
		'PREV_PIC' => $prev_pic,

		// Mighty Gorgon - Pic Size - BEGIN
		'L_PIC_DETAILS' => $lang['Pic_Details'],
		'L_PIC_SIZE' => $lang['Pic_Size'],
		'L_PIC_TYPE' => $lang['Pic_Type'],
		'PIC_SIZE' => $pic_width . ' x ' . $pic_height . ' (' . intval($pic_filesize/1024) . 'KB)',
		'PIC_TYPE' => strtoupper(substr($thispic['pic_filename'], strlen($thispic['pic_filename']) - 3, 3)),
		// Mighty Gorgon - Pic Size - END

		//'PIC_RATING' => $image_rating . (($already_rated == true) ? ('&nbsp;(' . $lang['Already_rated'] . ')') : ''),
		'PIC_RATING' => $image_rating . (($own_pic_rate == true) ? '&nbsp;(' . $lang['Own_Pic_Rate'] . ')' : (($already_rated == true) ? ('&nbsp;(' . $lang['Already_rated'] . ')') : '')),

		'PIC_ID' => $pic_id,
		'PIC_BBCODE' => '[albumimg]' . $pic_id . '[/albumimg]',
		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => $pic_desc,

		'POSTER' => $poster,

		'PIC_TIME' => create_date_ip($config['default_dateformat'], $thispic['pic_time'], $config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_COMMENTS' => $total_comments,

		'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',

		'L_PIC_ID' => $lang['Pic_ID'],
		'L_PIC_BBCODE' => $lang['Pic_BBCode'],
		'L_PIC_TITLE' => $lang['Pic_Image'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Pic_Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['Views'],
		'L_COMMENTS' => $lang['Comments'],
		'L_RATING' => $lang['Rating'],

		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_USER_WWW' => $lang['Website'],
		'L_USER_EMAIL' => $lang['Send_Email'],
		'L_USER_PROFILE' => $lang['Profile'],
		'L_ONLINE_STATUS' => $lang['Online_status'],
		'L_PM' => $lang['Private_Message'],

		'L_POST_YOUR_COMMENT' => $lang['Post_your_comment'],
		'L_MESSAGE' => $lang['Message'],
		'L_USERNAME' => $lang['Username'],
		'L_COMMENT_NO_TEXT' => $lang['Comment_no_text'],
		'L_COMMENT_TOO_LONG' => $lang['Comment_too_long'],
		'L_MAX_LENGTH' => $lang['Max_length'],
		'S_MAX_LENGTH' => $album_config['desc_length'],

		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],
		'L_BACK_TO_TOP' => $lang['Back_to_top'],
		'L_COMMENT_WATCH' =>$lang['Pic_comment_notification'],

		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',

		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id)),

		'U_COMMENT_WATCH_LINK' =>($is_watching_comments) ? '<a href="' . append_sid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . '&amp;unwatch=comment') . '">' . $lang['Unwatch_pic'] . '</a>' : ($user->data['session_logged_in'] ? '<a href="' . append_sid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . '&amp;watch=comment') . '">' . $lang['Watch_pic'] . '</a>' : ''),

		// Rating
		//'S_RATE_MSG' => (!$user->data['session_logged_in'] && $auth_data['rate'] == 0) ? $lang['Login_To_Vote'] : (($already_rated) ? $lang['Already_rated'] : $lang['Please_Rate_It']),
		'S_RATE_MSG' => (!$user->data['session_logged_in'] && $auth_data['rate'] == 0) ? $lang['Login_To_Vote'] : (($own_pic_rate == true) ? $lang['Own_Pic_Rate'] : (($already_rated == true) ? $lang['Already_rated'] : $lang['Please_Rate_It'])),
		'PIC_RATING' => $image_rating . (($own_pic_rate == true) ? '&nbsp;(' . $lang['Own_Pic_Rate'] . ')' : (($already_rated == true) ? ('&nbsp;(' . $lang['Already_rated'] . ')') : '')),
		'L_CURRENT_RATING' => $lang['Current_Rating'],
		'L_PLEASE_RATE_IT' => $lang['Please_Rate_It']
		)
	);

	// Social Bookmarks
	if ($config['show_social_bookmarks'] == true)
	{
		$template->assign_block_vars('social_bookmarks', array());
	}
	$topic_title_enc = urlencode(ip_utf8_decode($thispic['pic_title']));
	$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . 'album_showpage.' . PHP_EXT . '?pic_id=' . $thispic['pic_id'] . $full_size_param . '&amp;mode=prev' . $nuffimage_vars . $sort_append));
	$template->assign_vars(array(
		// Social Bookmarks - BEGIN
		'TOPIC_TITLE_ENC' => $topic_title_enc,
		'TOPIC_URL_ENC' => $topic_url_enc,
		'L_SHARE_TOPIC' => $lang['ShareThisTopic'],
		// Social Bookmarks - END
		)
	);

	// BBCBMG - BEGIN
	include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
	// BBCBMG - END
	// BBCBMG SMILEYS - BEGIN
	generate_smilies('inline');
	include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
	// BBCBMG SMILEYS - END

	if (($album_config['comment'] == 1) && ($total_comments > 0))
	{
		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . '&amp;sort_order=' . $sort_order)), $total_comments, $comments_per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $comments_per_page) + 1), ceil($total_comments / $comments_per_page))
			)
		);
		$template->assign_block_vars('switch_comment', array());
		$template->assign_block_vars('comment_switcharo_bottom', array());
	}

	$template_to_parse = $show_template;
	full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);
}
else
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				Comment Or Rate Submited
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// ------------------------------------
	// Check the permissions: COMMENT
	// ------------------------------------

	if (($album_config['comment'] == 0) && ($album_config['rate'] == 0))
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorized']);
	}
	if (($auth_data['comment'] == 0) && ($auth_data['rate'] == 0))
	{
		if (!$user->data['session_logged_in'])
		{
			redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_showpage.' . PHP_EXT . '&amp;pic_id=' . $pic_id));
		}
		else
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
		}
	}

	// Comment System
	if (($album_config['comment'] == 1) && ($auth_data['comment'] == 1))
	{
		$comment_text = substr($comment_text, 0, $album_config['desc_length']);

		$comment_username = request_var('comment_username', '', true);
		$comment_username = substr($comment_username, 0, 32);
		$comment_username = !$user->data['session_logged_in'] ? $comment_username : htmlspecialchars($user->data['username']);

		// Check Pic Locked
		if(($thispic['pic_lock'] == 1) && (!$auth_data['moderator']))
		{
			message_die(GENERAL_ERROR, $lang['Pic_Locked']);
		}

		// Check username for guest posting
		if (!$user->data['session_logged_in'])
		{
			if ($comment_username != '')
			{
				$result = validate_username($comment_username);
				if ($result['error'])
				{
					message_die(GENERAL_MESSAGE, $result['error_msg']);
				}
			}
		}

		// Prepare variables
		$comment_time = time();
		$comment_user_id = $user->data['user_id'];
		$comment_user_ip = $user->data['session_ip'];

		// Get $comment_id
		$sql = "SELECT MAX(comment_id) AS max
				FROM ". ALBUM_COMMENT_TABLE;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$comment_id = $row['max'] + 1;

		// Insert into DB
		// If user only rated, but didn't enter a comment... only update rating
		if ($comment_text != '')
		{
			$sql = "INSERT INTO " . ALBUM_COMMENT_TABLE ." (comment_id, comment_pic_id, comment_cat_id, comment_user_id, comment_username, comment_user_ip, comment_time, comment_text)
					VALUES ('$comment_id', '$pic_id', '$cat_id', '$comment_user_id', '" . $db->sql_escape($comment_username) . "', '" . $db->sql_escape($comment_user_ip) . "', '$comment_time', '" . $db->sql_escape($comment_text) . "')";
			$result = $db->sql_query($sql);
			// Watch pic for comments - BEGIN
			// Here we send email notification
			album_comment_notify($pic_id);
			// Watch pic for comments - END
			$message = $lang['Stored'] . '<br /><br />';
		}
	}

	// Rating System
	if (($album_config['rate'] == 1) && ($auth_data['rate'] == 1) && ($user->data['session_logged_in']))
	{
		// Check Pic Locked
		if(($thispic['pic_lock'] == 1) && (!$auth_data['moderator']))
		{
			message_die(GENERAL_ERROR, $lang['Pic_Locked']);
		}

		$rate_point = request_var('rating', -1);

		if ($rate_point != -1)//if user didnt vote, dont update database
		{
			if(($rate_point <= 0) || ($rate_point > $album_config['rate_scale']))
			{
				message_die(GENERAL_ERROR, 'Bad submitted value - ' . $rate_point);
			}

			$rate_user_id = $user->data['user_id'];
			$rate_user_ip = $user->data['session_ip'];

			$sql = "INSERT INTO " . ALBUM_RATE_TABLE . " (rate_pic_id, rate_user_id, rate_user_ip, rate_point)
					VALUES ('" . $db->sql_escape($pic_id) . "', '" . $db->sql_escape($rate_user_id) . "', '" . $db->sql_escape($rate_user_ip) . "', '" . $db->sql_escape($rate_point) . "')";
			$result = $db->sql_query($sql);
			$message = $lang['Album_rate_successfully'] . '<br /><br />';
		}
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$redirect_url = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id));
	meta_refresh(3, $redirect_url);

	$message .= sprintf($lang['Click_return_pic'], '<a href="' . append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

?>