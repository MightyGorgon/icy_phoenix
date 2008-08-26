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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = defined('IS_ICYPHOENIX') ? session_pagestart($user_ip) : session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
// End session management

// Get general album information
$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH;
include($album_root_path . 'album_common.' . $phpEx);

if ( !$userdata['session_logged_in'] )
{
	message_die(GENERAL_MESSAGE, $lang['Login_To_Vote']);
}

// Force to use the same table for backward compatibility - BEGIN
$album_config['hon_rate_sep'] = false;
// Force to use the same table for backward compatibility - END

if ( isset($_POST['hon_rating']) )
{
	$rate_point = intval($_POST['hon_rating']);
}
elseif ( isset($_GET['hon_rating']) )
{
	$rate_point = intval($_GET['hon_rating']);
}
else
{
	$rate_point = 0;
}

//if user havent rated a picture, show page, else update database
if ( ($rate_point < 1) || ($rate_point > $album_config['rate_scale']) )
{
	// ------------------------------------
	// get a random pic from album
	// ------------------------------------
	if ($album_config['hon_rate_where'] == '')
	{
		$sql = "SELECT `pic_id` FROM " . ALBUM_TABLE . "
						WHERE pic_user_id <> '" . $userdata['user_id'] . "'
						ORDER BY RAND() LIMIT 1";
	}
	else
	{
		$sql = "SELECT `pic_id` FROM " . ALBUM_TABLE . "
						WHERE pic_cat_id IN (" . $album_config['hon_rate_where'] . ")
							AND pic_user_id <> '" . $userdata['user_id'] . "'
						ORDER BY RAND() LIMIT 1";
	}

	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
	}
	$pic_id_temp = $db->sql_fetchrow($result);
	$pic_id = $pic_id_temp['pic_id'];


	// ------------------------------------
	// Get this pic info and current category info
	// ------------------------------------
	$rating_from = ($album_config['hon_rate_sep'] == 1) ? 'AVG(r.rate_hon_point) AS rating' : 'AVG(r.rate_point) AS rating';
	$sql_where = '';
	if ($userdata['user_level'] != ADMIN)
	{
		$sql_where = 'AND p.pic_approval = 1';
	}

	$sql = "SELECT p.*, cat.*,  u.user_id, u.username, r.rate_pic_id, " . $rating_from . ", COUNT(DISTINCT c.comment_id) AS comments
			FROM ". ALBUM_CAT_TABLE ."  AS cat, ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE pic_id = '$pic_id'
				AND cat.cat_id = p.pic_cat_id
			" . $sql_where . "
			GROUP BY p.pic_id";

	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
	}
	$thispic = $db->sql_fetchrow($result);

	$cat_id = $thispic['pic_cat_id'];
	$album_user_id = $thispic['cat_user_id'];

	if( empty($thispic) || !file_exists(ALBUM_UPLOAD_PATH . $pic_filename) )
	{
		message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
	}

	// ------------------------------------
	// Check the permissions
	// ------------------------------------
	if ($album_config['hon_rate_users'] == 0)
	{
		$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW, $thispic);

		if ($album_user_access['view'] == 0)
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(LOGIN_MG . '?redirect=album_hotornot.' . $phpEx));
			}
			else
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorised']);
			}
		}
	}

	// ------------------------------------
	// Check Pic Approval
	// ------------------------------------

	if ($userdata['user_level'] != ADMIN)
	{
		if( ($thiscat['cat_approval'] == ADMIN) || (($thiscat['cat_approval'] == MOD) && !$album_user_access['moderator']) )
		{
			if ($thispic['pic_approval'] != 1)
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorised']);
			}
		}
	}

	/*
	+----------------------------------------------------------
	| Main work here...
	+----------------------------------------------------------
	*/

	// Start output of page
	$page_title = $lang['Album'];
	$meta_description = '';
	$meta_keywords = '';
	include($phpbb_root_path . 'includes/page_header.' . $phpEx);

	$template->set_filenames(array('body' => 'album_hon.tpl'));

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) or ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $thispic['user_id']) . '">' . $thispic['username'] . '</a>';
	}

	//decide how user wants to show their rating
	$image_rating = ImageRating($thispic['rating']);

	//hot or not rating
	if ( CanRated($pic_id, $userdata['user_id']))
	{
		$template->assign_block_vars('hon_rating', array());

		for ($i = 0; $i < $album_config['rate_scale']; $i++)
		{
			$template->assign_block_vars('hon_rating.hon_row', array(
				'VALUE' => ($i + 1)));
		}
	}
	else
	{
		$template->assign_block_vars('hon_rating_cant', array());
	}

	$template->assign_vars(array(
		'L_PLEASE_RATE_IT' => $lang['Please_Rate_It'],
		'L_ALREADY_RATED' => $lang['Already_rated'],
		'L_PIC_ID' => $lang['Pic_ID'],
		'L_RATING' => $lang['Rating'],
		'L_PIC_TITLE' => $lang['Pic_Title'] . $album_config['clown_rateType'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Pic_Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['View'],
		'L_COMMENTS' => $lang['Comments'],

		'CAT_TITLE' => $thiscat['cat_title'],
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . $phpEx . '?cat_id=' . $cat_id)),
		'U_PIC' => append_sid(album_append_uid('album_pic.' . $phpEx . '?pic_id=' . $pic_id)),
		'U_COMMENT' => append_sid(album_append_uid('album_showpage.' . $phpEx . '?pic_id=' . $pic_id)),
		'S_ACTION' => append_sid(album_append_uid('album_hotornot.' . $phpEx)),

		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => nl2br($thispic['pic_desc']),
		'POSTER' => $poster,
		'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_RATING' => $image_rating,
		'PIC_COMMENTS' => $thispic['comments'],
		'PIC_ID' => $pic_id,
		'PICTURE_ID' => $pic_id,

		)
	);

	if ($album_config['rate'])
	{
		$template->assign_block_vars('rate_switch', array());
	}

	if ($album_config['comment'])
	{
		$template->assign_block_vars('comment_switch', array());
	}

	// Generate the page
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
}
else
{
	if ( !$userdata['session_logged_in'] )
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}

	$rate_user_id = $userdata['user_id'];
	$rate_user_ip = $userdata['session_ip'];
	$pic_id = ( isset($_POST['pic_id']) || isset($_GET['pic_id']) ) ? (isset($_POST['pic_id'])) ? $_POST['pic_id'] : $_GET['pic_id'] : 0;
	if( $pic_id == 0 )
	{
		message_die(GENERAL_ERROR, 'Wrong Pic ID');
	}

	if ($album_config['hon_rate_sep'] == true)
	{
		$rating_field = 'rate_hon_point';
	}
	else
	{
		$rating_field = 'rate_point';
	}

	$sql = "SELECT * FROM " . ALBUM_RATE_TABLE . "
					WHERE rate_pic_id = '" . $pic_id . "'
						AND rate_user_id = '" . $rate_user_id . "'
						AND " . $rating_field . " > '0'";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not query rating table', '', __LINE__, __FILE__, $sql);
	}

	if ( !($rated = $db->sql_fetchrow($result)) )
	{
		$sql = "INSERT INTO " . ALBUM_RATE_TABLE . " (rate_pic_id, rate_user_id, rate_user_ip, " . $rating_field . ")
				VALUES ('$pic_id', '$rate_user_id', '$rate_user_ip', '$rate_point')";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert new rating', '', __LINE__, __FILE__, $sql);
		}
		$rate_string = $lang['Album_rate_successfully'];
	}
	else
	{
		$rate_string = $lang['Already_rated'];
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid(album_append_uid('album_hotornot.' . $phpEx)) . '">'
		)
	);

	$message = $rate_string . '<br /><br />' . sprintf($lang['Click_rate_more'], '<a href="' . append_sid(album_append_uid('album_hotornot.' . $phpEx)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . $phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

?>