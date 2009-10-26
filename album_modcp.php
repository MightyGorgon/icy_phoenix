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
// Get the $pic_id from GET method then query out the category
// If $pic_id not found we will assign it to FALSE
// We will check $pic_id[] in POST method later (in $mode carry out)
// ------------------------------------
$pic_id = request_var('pic_id', 0);
$pic_id = (($pic_id < 0) ? 0 : $pic_id);

if (isset($_POST['cancel']))
{
	$redirect = 'album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id;
	redirect(append_sid($redirect, true));
}

if(!empty($pic_id))
{
	// Get this pic info
	$sql = "SELECT p.*, c.*
			FROM " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . "  AS c
			WHERE p.pic_id = " . $pic_id . "
				AND c.cat_id = p.pic_cat_id";
	$result = $db->sql_query($sql);
	$thiscat = $db->sql_fetchrow($result);
	if(empty($thiscat))
	{
		message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
	}
	$cat_id = $thiscat['pic_cat_id'];
	$album_user_id = $thiscat['cat_user_id'];
}
else
{
	// No $pic_id found, try to find $cat_id
	$cat_id = request_var('cat_id', 0);
	$cat_id = (($cat_id < 0) ? 0 : $cat_id);
	if(empty($cat_id))
	{
		message_die(GENERAL_ERROR, 'No categories specified');
	}
}

// ------------------------------------
// Get the cat info
// ------------------------------------
$sql = "SELECT *
		FROM " . ALBUM_CAT_TABLE . "
		WHERE cat_id = " . $cat_id;
$result = $db->sql_query($sql);
$thiscat = $db->sql_fetchrow($result);

if (empty($thiscat))
{
	message_die(GENERAL_ERROR, $lang['Category_not_exist']);
}

$album_user_id = $thiscat['cat_user_id'];
// END category info

// ------------------------------------
// set $mode (select action)
// ------------------------------------
if(isset($_POST['mode']))
{
	// Oh data from Mod CP
	if(isset($_POST['move']))
	{
		$mode = 'move';
	}
	elseif(isset($_POST['lock']))
	{
		$mode = 'lock';
	}
	elseif(isset($_POST['unlock']))
	{
		$mode = 'unlock';
	}
	elseif(isset($_POST['delete']))
	{
		$mode = 'delete';
	}
	elseif(isset($_POST['approval']))
	{
		$mode = 'approval';
	}
	elseif(isset($_POST['unapproval']))
	{
		$mode = 'unapproval';
	}
	elseif(isset($_POST['copy']))
	{
		$mode = 'copy';
	}
	else
	{
		$mode = '';
	}
}
elseif(isset($_GET['mode']))
{
	$mode = trim($_GET['mode']);
}
else
{
	$mode = '';
}
// END $mode (select action)


//album_read_tree($album_user_id);
album_read_tree(ALBUM_ROOT_CATEGORY);

// ------------------------------------
// Check the permissions
// ------------------------------------
$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW_AND_UPLOAD|ALBUM_AUTH_MODERATOR, $thiscat);

if (!album_check_permission($auth_data, ALBUM_AUTH_MODERATOR))
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(album_append_uid(CMS_PAGE_LOGIN . '?redirect=album_modcp.' . PHP_EXT . '&amp;cat_id=' . $cat_id)));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorized']);
	}
}
// END permissions

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if ($mode == '')
{
	// --------------------------------
	// Moderator Control Panel
	// --------------------------------

	// Set Variables
	$start = isset($_GET['start']) ? intval($_GET['start']) : (isset($_POST['start']) ? intval($_POST['start']) : 0);
	$start = ($start < 0) ? 0 : $start;

	if(isset($_GET['sort_method']))
	{
		switch ($_GET['sort_method'])
		{
			case 'pic_title':
				$sort_method = 'pic_title';
				break;
			case 'pic_user_id':
				$sort_method = 'pic_user_id';
				break;
			case 'pic_view_count':
				$sort_method = 'pic_view_count';
				break;
			case 'rating':
				$sort_method = 'rating';
				break;
			case 'comments':
				$sort_method = 'comments';
				break;
			case 'new_comment':
				$sort_method = 'new_comment';
				break;
			default:
				$sort_method = 'pic_time';
		}
	}
	elseif(isset($_POST['sort_method']))
	{
		switch ($_POST['sort_method'])
		{
			case 'pic_title':
				$sort_method = 'pic_title';
				break;
			case 'pic_user_id':
				$sort_method = 'pic_user_id';
				break;
			case 'pic_view_count':
				$sort_method = 'pic_view_count';
				break;
			case 'rating':
				$sort_method = 'rating';
				break;
			case 'comments':
				$sort_method = 'comments';
				break;
			case 'new_comment':
				$sort_method = 'new_comment';
				break;
			default:
				$sort_method = 'pic_time';
		}
	}
	else
	{
		$sort_method = 'pic_time';
	}

	if(isset($_GET['sort_order']))
	{
		switch ($_GET['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
		}
	}
	elseif(isset($_POST['sort_order']))
	{
		switch ($_POST['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
		}
	}
	else
	{
		$sort_order = 'DESC';
	}

	// Count Pics
	$sql = "SELECT COUNT(pic_id) AS count
			FROM " . ALBUM_TABLE . "
			WHERE pic_cat_id = '$cat_id'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$total_pics = $row['count'];

	$pics_per_page = $config['topics_per_page']; // Text list only

	// get information from DB
	if ($total_pics > 0)
	{
		$limit_sql = ($start == 0) ? $pics_per_page : $start .', '. $pics_per_page;

		// Old Approval
		/*
		$pic_approval_sql = '';
		if(($userdata['user_level'] != ADMIN) && ($thiscat['cat_approval'] == ALBUM_ADMIN))
		{
			// because he went through my Permission Checking above so he must be at least a Moderator
			$pic_approval_sql = 'AND p.pic_approval = 1';
		}
		*/

		if($userdata['user_level'] == ADMIN)
		{
			$pic_approval_sql = '';
			$is_auth_approve = true;
		}
		else
		{
			// because the user went through Permission Checking on top of this file he must be at least a Moderator
			$pic_approval_sql = 'AND p.pic_approval = 1';
			$is_auth_approve = false;

			if($thiscat['cat_approval'] == ALBUM_USER)
			{
				$pic_approval_sql = '';
				$is_auth_approve = true;
			}

			if(($userdata['user_id'] == $thiscat['cat_user_id']) && ($album_config['personal_allow_gallery_mod'] == 1) && ($album_config['personal_pics_approval'] == ALBUM_MOD))
			{
				$pic_approval_sql = '';
				$is_auth_approve = true;
			}
		}



		$sql = "SELECT p.pic_id, p.pic_title, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, p.pic_lock, p.pic_approval, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(c.comment_id) AS comments, MAX(c.comment_id) AS new_comment
				FROM " . ALBUM_TABLE . " AS p
					LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
					LEFT JOIN " . ALBUM_RATE_TABLE . " AS r ON p.pic_id = r.rate_pic_id
					LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_id = c.comment_pic_id
				WHERE p.pic_cat_id = '$cat_id' $pic_approval_sql
				GROUP BY p.pic_id
				ORDER BY $sort_method $sort_order
				LIMIT $limit_sql";
		$result = $db->sql_query($sql);
		$picrow = array();
		while($row = $db->sql_fetchrow($result))
		{
			$picrow[] = $row;
		}

		for ($i = 0; $i < sizeof($picrow); $i++)
		{
			if(($picrow[$i]['user_id'] == ALBUM_GUEST) || ($picrow[$i]['username'] == ''))
			{
				$pic_poster = ($picrow[$i]['pic_username'] == '') ? $lang['Guest'] : $picrow[$i]['pic_username'];
			}
			else
			{
				$pic_poster = '<a href="'. append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $picrow[$i]['user_id']) .'">'. $picrow[$i]['username'] .'</a>';
			}

			$template->assign_block_vars('picrow', array(
				'PIC_ID' => $picrow[$i]['pic_id'],
				'PIC_TITLE' => '<a href="'. append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $picrow[$i]['pic_id'])) . '" target="_blank">' . htmlspecialchars($picrow[$i]['pic_title']) . '</a>',
				'POSTER' => $pic_poster,
				'TIME' => create_date($config['default_dateformat'], $picrow[$i]['pic_time'], $config['board_timezone']),
				'RATING' => ($picrow[$i]['rating'] == 0) ? $lang['Not_rated'] : round($picrow[$i]['rating'], 2),
				'COMMENTS' => $picrow[$i]['comments'],
				'LOCK' => ($picrow[$i]['pic_lock'] == 0) ? '' : $lang['Locked'],
				'APPROVAL' => ($picrow[$i]['pic_approval'] == 0) ? $lang['Not_approved'] : $lang['Approved']
				)
			);
		}

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id . '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order)), $total_pics, $pics_per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $pics_per_page) + 1), ceil($total_pics / $pics_per_page))
			)
		);
	}
	else
	{
		// No Pics
		$template->assign_block_vars('no_pics', array());
	}

	// Start output of page (ModCP)
	$nav_server_url = create_server_url();
	$breadcrumbs_address = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . ALBUM_NAV_ARROW . '<a class="nav-current" href="' . $nav_server_url . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">' . $thiscat['cat_title'] . '</a>';

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
		$sort_username_option .= ($sort_method == 'pic_user_id') ? 'selected="selected"' : '';
		$sort_username_option .= '>' . $lang['Sort_Username'] .'</option>';
	}

	$template->assign_vars(array(
		'U_VIEW_CAT' => append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'CAT_TITLE' => $thiscat['cat_title'],

		'L_CATEGORY' => $lang['Category'],
		'L_MODCP' => $lang['Mod_CP'],

		'L_NO_PICS' => $lang['No_Pics'],

		'L_VIEW' => $lang['View'],
		'L_POSTER' => $lang['Pic_Poster'],
		'L_POSTED' => $lang['Posted'],

		'S_ALBUM_ACTION' => append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)),

		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],

		'L_PIC_ID' => $lang['Pic_ID'],
		'L_TIME' => $lang['Time'],
		'L_PIC_TITLE' => $lang['Pic_Image'],
		'L_POSTER' => $lang['Pic_Poster'],
		'L_RATING' => $lang['Rating'],
		'L_COMMENTS' => $lang['Comments'],
		'L_STATUS' => $lang['Status'],
		'L_APPROVAL' => $lang['Approval'],
		'L_SELECT' => $lang['Select'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVE' => $lang['Move'],
		'L_COPY' => $lang['Copy'],
		'L_LOCK' => $lang['Lock'],
		'L_UNLOCK' => $lang['Unlock'],

		'DELETE_BUTTON' => ($auth_data['delete'] == 1) ? '<input type="submit" class="liteoption" name="delete" value="'. $lang['Delete'] . '" />' : '',

		'APPROVAL_BUTTON' => ($is_auth_approve == true) ? '<input type="submit" class="liteoption" name="approval" value="' . $lang['Approve'] . '" />' : '',

		'UNAPPROVAL_BUTTON' => ($is_auth_approve == true) ? '<input type="submit" class="liteoption" name="unapproval" value="' . $lang['Unapprove'] . '" />' : '',

		'L_CHECK_ALL' => $lang['Modcp_check_all'],
		'L_UNCHECK_ALL' => $lang['Modcp_uncheck_all'],
		'L_INVERSE_SELECTION' => $lang['Modcp_inverse_selection'],

		'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
		'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
		'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',

		'SORT_RATING_OPTION' => $sort_rating_option,
		'SORT_USERNAME_OPTION' => $sort_username_option,
		'SORT_COMMENTS_OPTION' => $sort_comments_option,
		'SORT_NEW_COMMENT_OPTION' => $sort_new_comment_option,

		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],

		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : ''
		)
	);
	full_page_generation('album_modcp_body.tpl', $lang['Album'], '', '');
}
else
{
	// Switch with $mode
	if ($mode == 'move')
	{
		//-----------------------------
		// MOVE
		//-----------------------------

		if(!isset($_POST['target']))
		{
			// if "target" has not been set, we will open the category select form
			//
			// we must check POST method now
			$pic_id_array = array();
			if (!empty($pic_id)) // from GET
			{
				$pic_id_array[] = $pic_id;
			}
			else
			{
				// Check $pic_id[] on POST Method now
				if(isset($_POST['pic_id']))
				{
					$pic_id_array = $_POST['pic_id'];
					if(!is_array($pic_id_array))
					{
						message_die(GENERAL_ERROR, 'Invalid request');
					}
				}
				else
				{
					message_die(GENERAL_ERROR, 'No pics specified');
				}
			}

			// We must send out the $pic_id_array to store data between page changing
			for ($i = 0; $i < sizeof($pic_id_array); $i++)
			{
				$template->assign_block_vars('pic_id_array', array(
					'VALUE' => $pic_id_array[$i])
				);
			}

			// Create categories select
			//album_read_tree($album_user_id, ALBUM_AUTH_VIEW_AND_UPLOAD); // only categories user can view AND upload too
			album_read_tree($userdata['user_id'], ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW_AND_UPLOAD);
			$category_select = '<select name="target">';
			if($userdata['user_level'] == ADMIN)
			{
				$category_select .= album_get_simple_tree_option(ALBUM_ROOT_CATEGORY, ALBUM_AUTH_MODERATOR);
			}
			else
			{
				$category_select .= album_get_simple_tree_option(ALBUM_ROOT_CATEGORY, ALBUM_AUTH_MODERATOR);
			}
			$category_select .= '</select>';
			// end write

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=move&amp;cat_id=' . $cat_id)),
				'L_MOVE' => $lang['Move'],
				'L_MOVE_TO_CATEGORY' => $lang['Move_to_Category'],
				'S_CATEGORY_SELECT' => $category_select
				)
			);
			full_page_generation('album_move_body.tpl', $lang['Album'], '', '');
		}
		else
		{
			// Do the MOVE action
			//
			// Now we only get $pic_id[] via POST (after the select target screen)
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}
			// if we are trying to move picture(s) to root category or a
			// personal gallary (shouldn't be possible), but better save then sorry
			// ...then return an error
			if (intval($_POST['target']) <= 0)
			{
				message_die(GENERAL_ERROR, 'Can\'t move pictures directly to Root category');
			}

			// well, we got the array of pic_id but we must do a check to make sure all these
			// pics are in this category (prevent some naughty moderators to access un-authorized pics)
			$sql = "SELECT pic_id
					FROM " . ALBUM_TABLE . "
					WHERE pic_id IN ($pic_id_sql) AND pic_cat_id <> $cat_id";
			$result = $db->sql_query($sql);
			if($db->sql_numrows($result) > 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
			}

			// Update the DB
			$sql = "UPDATE " . ALBUM_TABLE . "
					SET pic_cat_id = ". intval($_POST['target']) ."
					WHERE pic_id IN ($pic_id_sql)";
			$result = $db->sql_query($sql);

			$message = $lang['Pics_moved_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	elseif ($mode == 'lock')
	{
		//-----------------------------
		// LOCK
		//-----------------------------

		// we must check POST method now
		if (!empty($pic_id)) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorized pics)
		$sql = "SELECT pic_id
				FROM " . ALBUM_TABLE . "
				WHERE pic_id IN ($pic_id_sql) AND pic_cat_id <> $cat_id";
		$result = $db->sql_query($sql);
		if($db->sql_numrows($result) > 0)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
		}

		// update the DB
		$sql = "UPDATE " . ALBUM_TABLE . "
				SET pic_lock = 1
				WHERE pic_id IN ($pic_id_sql)";
		$result = $db->sql_query($sql);

		$message = $lang['Pics_locked_successfully'] .'<br /><br />';

		if ($album_user_id == ALBUM_PUBLIC_GALLERY)
		{
			$message .= sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />';
		}
		else
		{
			$message .= sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ($mode == 'unlock')
	{
		//-----------------------------
		// UNLOCK
		//-----------------------------

		// we must check POST method now
		if (!empty($pic_id)) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorized pics)
		$sql = "SELECT pic_id
				FROM " . ALBUM_TABLE . "
				WHERE pic_id IN ($pic_id_sql) AND pic_cat_id <> $cat_id";
		$result = $db->sql_query($sql);
		if($db->sql_numrows($result) > 0)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
		}

		// update the DB
		$sql = "UPDATE " . ALBUM_TABLE . "
				SET pic_lock = 0
				WHERE pic_id IN ($pic_id_sql)";
		$result = $db->sql_query($sql);

		$message = $lang['Pics_unlocked_successfully'] .'<br /><br />';

		if ($album_user_id == ALBUM_PUBLIC_GALLERY)
		{
			$message .= sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />';
		}
		else
		{
			$message .= sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>');
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ($mode == 'approval')
	{
		//-----------------------------
		// APPROVAL
		//-----------------------------

		// we must check POST method now
		if (!empty($pic_id)) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorized pics)
		$sql = "SELECT pic_id
				FROM " . ALBUM_TABLE . "
				WHERE pic_id IN ($pic_id_sql) AND pic_cat_id <> $cat_id";
		$result = $db->sql_query($sql);
		if($db->sql_numrows($result) > 0)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
		}

		// update the DB
		$sql = "UPDATE " . ALBUM_TABLE . "
				SET pic_approval = 1
				WHERE pic_id IN ($pic_id_sql)";
		$result = $db->sql_query($sql);

		$message = $lang['Pics_approved_successfully'] . '<br /><br />' . sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ($mode == 'unapproval')
	{
		//-----------------------------
		// UNAPPROVAL
		//-----------------------------

		// we must check POST method now
		if (!empty($pic_id)) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorized pics)
		$sql = "SELECT pic_id
				FROM " . ALBUM_TABLE . "
				WHERE pic_id IN ($pic_id_sql) AND pic_cat_id <> $cat_id";
		$result = $db->sql_query($sql);
		if($db->sql_numrows($result) > 0)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
		}

		// update the DB
		$sql = "UPDATE " . ALBUM_TABLE . "
				SET pic_approval = 0
				WHERE pic_id IN ($pic_id_sql)";
		$result = $db->sql_query($sql);

		$message = $lang['Pics_unapproved_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') .'<br /><br />'. sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ($mode == 'copy')
	{
		//-----------------------------
		// COPY TO
		//-----------------------------

		if(!isset($_POST['target']))
		{
			// if "target" has not been set, we will open the category select form
			//
			// we must check POST method now
			$pic_id_array = array();
			if (!empty($pic_id)) // from GET
			{
				$pic_id_array[] = $pic_id;
			}
			else
			{
				// Check $pic_id[] on POST Method now
				if(isset($_POST['pic_id']))
				{
					$pic_id_array = $_POST['pic_id'];
					if(!is_array($pic_id_array))
					{
						message_die(GENERAL_ERROR, 'Invalid request');
					}
				}
				else
				{
					message_die(GENERAL_ERROR, 'No pics specified');
				}
			}

			// We must send out the $pic_id_array to store data between page changing
			for ($i = 0; $i < sizeof($pic_id_array); $i++)
			{
				$template->assign_block_vars('pic_id_array', array(
					'VALUE' => $pic_id_array[$i])
				);
			}

			// Create categories select
			//album_read_tree($album_user_id, ALBUM_AUTH_VIEW_AND_UPLOAD); // only categories user can view AND upload too
			album_read_tree($userdata['user_id'], ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW_AND_UPLOAD);
			$category_select = '<select name="target">';
			if($userdata['user_level'] == ADMIN)
			{
				$category_select .= album_get_simple_tree_option(ALBUM_ROOT_CATEGORY, ALBUM_AUTH_MODERATOR);
			}
			else
			{ // Get list of cats where upload is allowed for user
				$category_select .= album_get_simple_tree_option(ALBUM_ROOT_CATEGORY, ALBUM_AUTH_UPLOAD);
			}
			$category_select .= '</select>';
			// end write

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=copy&amp;cat_id=' . $cat_id)),
				'L_COPY' => $lang['Copy'],
				'L_COPY_TO_CATEGORY' => $lang['Copy_to_Category'],
				'S_CATEGORY_SELECT' => $category_select
				)
			);
			full_page_generation('album_copy_body.tpl', $lang['Album'], '', '');
		}
		else
		{
			// Do the Copy action
			//
			// Now we only get $pic_id[] via POST (after the select target screen)
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}
			// if we are trying to copy picture(s) to root category or a
			// personal gallery (shouldn't be possible), but better safe than sorry
			// ...then return an error
			if (intval($_POST['target']) <= 0)
			{
				message_die(GENERAL_ERROR, 'Can\'t copy pictures directly to Root category');
			}

			// we have the array of pic_id but we must do a query to get each pics info
			$sql = "SELECT *
					FROM " . ALBUM_TABLE . "
					WHERE pic_id IN (" . $pic_id_sql . ")"; // AND pic_cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);

			while($row = $db->sql_fetchrow($result))
			{
				$picrow[]= $row;
			}

			for ($i = 0; $i < sizeof($picrow); $i++)
			{
				// Though each cat entry would work off of the same pic_filename
				// we need an actual copy of the pic with a different filename in case of deletions
				$pic_filename = $picrow[$i]['pic_filename'];

				if (USERS_SUBFOLDERS_ALBUM == true)
				{
					if (strpos($pic_filename, '/') !== false)
					{
						$pic_path[] = array();
						$pic_path = explode('/', $pic_filename);
						$pic_filename = $pic_path[sizeof($pic_path) - 1];
					}
				}

				$file_part = explode('.', strtolower($pic_filename));
				$pic_filetype = $file_part[sizeof($file_part) - 1];
				$pic_filename_only = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
				$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
				$pic_extra_path = '';
				$pic_new_filename = $pic_extra_path . $pic_filename;
				$pic_fullpath = $pic_base_path . $pic_new_filename;
				$pic_title = $picrow[$i]['pic_title'];
				$pic_title_reg = preg_replace('/[^A-Za-z0-9]*/', '_', $pic_title);
				$pic_thumbnail = '';

				if (USERS_SUBFOLDERS_ALBUM == true)
				{
					if (sizeof($pic_path) == 2)
					{
						$pic_extra_path = $pic_path[0] . '/';
						$pic_base_full_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH . $pic_extra_path;
						if (is_dir($pic_base_full_path))
						{
							$pic_new_filename = $pic_extra_path . $pic_filename;
							$pic_fullpath = $pic_base_path . $pic_new_filename;
						}
						else
						{
							message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
						}
					}
				}

				while (file_exists($pic_fullpath))
				{
					$pic_new_filename = $pic_extra_path . $pic_filename_only . '_' . time() . '_' . mt_rand(100000, 999999) . '.' . $pic_filetype;
					$pic_fullpath = $pic_base_path . $pic_new_filename;
				}

				if (!copy($pic_base_path . $picrow[$i]['pic_filename'], $pic_fullpath))
				{
					message_die(GENERAL_ERROR, 'Could not copy image');
				}

				$pic_title = addslashes($picrow[$i]['pic_title']);
				$pic_desc = addslashes($picrow[$i]['pic_title']);
				$pic_time = time() + $i; // Gives each pic a different timestamp

				$sql = "INSERT INTO " . ALBUM_TABLE . " (pic_filename, pic_title, pic_desc, pic_user_id, pic_user_ip, pic_username, pic_time, pic_cat_id, pic_approval)
				VALUES ('" . $pic_new_filename . "', '" . $pic_title . "', '" . $pic_desc . "', '" . $picrow[$i]['pic_user_id'] . "', '" . $picrow[$i]['pic_user_ip'] . "', '" . $picrow[$i]['pic_username'] . "', '" . $pic_time . "', '" . intval($_POST['target']) . "', '" . $picrow[$i]['pic_approval'] . "')";
				$result = $db->sql_query($sql);
			}

			$message = $lang['Pics_copied_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	elseif ($mode == 'delete')
	{
		//-----------------------------
		// DELETE
		//-----------------------------

		if ($auth_data['delete'] == 0)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorized']);
		}

		if(!isset($_POST['confirm']))
		{
			// we must check POST method now
			$pic_id_array = array();
			if (!empty($pic_id)) // from GET
			{
				$pic_id_array[] = $pic_id;
			}
			else
			{
				// Check $pic_id[] on POST Method now
				if(isset($_POST['pic_id']))
				{
					$pic_id_array = $_POST['pic_id'];
					if(!is_array($pic_id_array))
					{
						message_die(GENERAL_ERROR, 'Invalid request');
					}
				}
				else
				{
					message_die(GENERAL_ERROR, 'No pics specified');
				}
			}

			// We must send out the $pic_id_array to store data between page changing
			$hidden_field = '';
			for ($i = 0; $i < sizeof($pic_id_array); $i++)
			{
				$hidden_field .= '<input name="pic_id[]" type="hidden" value="' . $pic_id_array[$i] . '" />' . "\n";
			}

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Album_delete_confirm'],
				'S_HIDDEN_FIELDS' => $hidden_field,
				'L_NO' => $lang['No'],
				'L_YES' => $lang['Yes'],
				'S_CONFIRM_ACTION' => append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?mode=delete&amp;cat_id=' . $cat_id)),
				)
			);
			full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			// Do the delete here...
			if(isset($_POST['pic_id']))
			{
				$pic_id = $_POST['pic_id'];
				if(is_array($pic_id))
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Invalid request');
				}
			}
			else
			{
				message_die(GENERAL_ERROR, 'No pics specified');
			}

			// well, we got the array of pic_id but we must do a check to make sure all these
			// pics are in this category (prevent some naughty moderators to access un-authorized pics)
			$sql = "SELECT pic_id
					FROM " . ALBUM_TABLE . "
					WHERE pic_id IN ($pic_id_sql) AND pic_cat_id <> $cat_id";
			$result = $db->sql_query($sql);
			if($db->sql_numrows($result) > 0)
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}

			// Delete all comments
			$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
					WHERE comment_pic_id IN ($pic_id_sql)";
			$result = $db->sql_query($sql);

			// Delete all ratings
			$sql = "DELETE FROM ". ALBUM_RATE_TABLE ."
					WHERE rate_pic_id IN ($pic_id_sql)";
			$result = $db->sql_query($sql);

			// Delete Physical Files
			// first we need filenames
			$sql = "SELECT pic_filename, pic_thumbnail
					FROM " . ALBUM_TABLE . "
					WHERE pic_id IN ($pic_id_sql)";
			$result = $db->sql_query($sql);
			$filerow = array();
			while($row = $db->sql_fetchrow($result))
			{
				$filerow[] = $row;
			}
			for ($i = 0; $i < sizeof($filerow); $i++)
			{

				$pic_filename = $filerow[$i]['pic_filename'];

				if (USERS_SUBFOLDERS_ALBUM == true)
				{
					if (strpos($pic_filename, '/') !== false)
					{
						$pic_path[] = array();
						$pic_path = explode('/', $pic_filename);
						$pic_filename = $pic_path[sizeof($pic_path) - 1];
					}
				}

				$file_part = explode('.', strtolower($pic_filename));
				$pic_filetype = $file_part[sizeof($file_part) - 1];
				$pic_filename_only = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
				$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
				$pic_extra_path = '';
				$pic_new_filename = $pic_extra_path . $pic_filename;
				$pic_fullpath = $pic_base_path . $pic_new_filename;
				$pic_thumbnail = $filerow[$i]['pic_thumbnail'];
				$pic_thumbnail_fullpath = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_thumbnail;

				if (USERS_SUBFOLDERS_ALBUM == true)
				{
					if (sizeof($pic_path) == 2)
					{
						$pic_extra_path = $pic_path[0] . '/';
						$pic_base_full_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH . $pic_extra_path;
						$pic_thumbnail_path = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_extra_path;
						if (is_dir($pic_base_full_path))
						{
							$pic_new_filename = $pic_extra_path . $pic_filename;
							$pic_fullpath = $pic_base_path . $pic_new_filename;
							$pic_thumbnail_fullpath = $pic_thumbnail_path . $pic_thumbnail;
						}
						else
						{
							message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
						}
					}
				}

				@unlink($pic_thumbnail_fullpath);
				@unlink(ALBUM_MED_CACHE_PATH . $pic_extra_path . $pic_thumbnail);
				@unlink(ALBUM_WM_CACHE_PATH . $pic_extra_path . $pic_thumbnail);
				@unlink($pic_fullpath);
			}

			// Delete DB entry
			$sql = "DELETE FROM " . ALBUM_TABLE . "
					WHERE pic_id IN (" . $pic_id_sql . ")";
			$result = $db->sql_query($sql);

			$message = $lang['Pics_deleted_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], '<a href="' . append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . append_sid(album_append_uid('album_modcp.' . PHP_EXT . '?cat_id=' . $cat_id)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		message_die(GENERAL_ERROR, 'Invalid_mode');
	}
}

?>