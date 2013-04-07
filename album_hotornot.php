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
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

if (!$user->data['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['Login_To_Vote']);
}

// Force to use the same table for backward compatibility - BEGIN
$album_config['hon_rate_sep'] = false;
// Force to use the same table for backward compatibility - END

$rate_point = request_var('hon_rating', 0);

//if user hasn't rated a picture, show page, else update database
if (($rate_point < 1) || ($rate_point > $album_config['rate_scale']))
{
	// ------------------------------------
	// get a random pic from album
	// ------------------------------------
	if (empty($album_config['hon_rate_where']))
	{
		$sql = "SELECT `pic_id` FROM " . ALBUM_TABLE . "
						WHERE pic_user_id <> '" . $user->data['user_id'] . "'
						ORDER BY RAND() LIMIT 1";
	}
	else
	{
		$sql = "SELECT `pic_id` FROM " . ALBUM_TABLE . "
						WHERE pic_cat_id IN (" . $album_config['hon_rate_where'] . ")
							AND pic_user_id <> '" . $user->data['user_id'] . "'
						ORDER BY RAND() LIMIT 1";
	}
	$result = $db->sql_query($sql);
	$pic_id_temp = $db->sql_fetchrow($result);
	$pic_id = $pic_id_temp['pic_id'];


	// ------------------------------------
	// Get this pic info and current category info
	// ------------------------------------
	$rating_from = ($album_config['hon_rate_sep'] == 1) ? 'AVG(r.rate_hon_point) AS rating' : 'AVG(r.rate_point) AS rating';
	$sql_where = '';
	if ($user->data['user_level'] != ADMIN)
	{
		$sql_where = 'AND p.pic_approval = 1';
	}

	$sql = "SELECT p.*, cat.*, u.user_id, u.username, r.rate_pic_id, " . $rating_from . ", COUNT(DISTINCT c.comment_id) AS comments
			FROM " . ALBUM_CAT_TABLE . " AS cat, " . ALBUM_TABLE . " AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE pic_id = '$pic_id'
				AND cat.cat_id = p.pic_cat_id
			" . $sql_where . "
			GROUP BY p.pic_id";
	$result = $db->sql_query($sql);
	$thispic = $db->sql_fetchrow($result);

	$cat_id = $thispic['pic_cat_id'];
	$album_user_id = $thispic['cat_user_id'];

	$pic_base_path = ALBUM_UPLOAD_PATH;
	$pic_extra_path = '';
	$pic_new_filename = $pic_extra_path . $pic_filename;
	$pic_fullpath = $pic_base_path . $pic_new_filename;

	if(empty($thispic) || !file_exists($pic_fullpath))
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
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_hotornot.' . PHP_EXT));
			}
			else
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}
		}
	}

	// ------------------------------------
	// Check Pic Approval
	// ------------------------------------

	if ($user->data['user_level'] != ADMIN)
	{
		if(($thiscat['cat_approval'] == ADMIN) || (($thiscat['cat_approval'] == MOD) && !$album_user_access['moderator']))
		{
			if ($thispic['pic_approval'] != 1)
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}
		}
	}

	/*
	+----------------------------------------------------------
	| Main work here...
	+----------------------------------------------------------
	*/

	if(($thispic['pic_user_id'] == ALBUM_GUEST) or ($thispic['username'] == ''))
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $thispic['user_id']) . '">' . $thispic['username'] . '</a>';
	}

	//decide how user wants to show their rating
	$image_rating = ImageRating($thispic['rating']);

	//hot or not rating
	if (CanRate($pic_id, $user->data['user_id']))
	{
		$template->assign_block_vars('hon_rating', array());

		for ($i = 0; $i < $album_config['rate_scale']; $i++)
		{
			$template->assign_block_vars('hon_rating.hon_row', array(
				'VALUE' => ($i + 1)
				)
			);
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
		'U_VIEW_CAT' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id)),
		'U_PIC' => append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $pic_id)),
		'U_COMMENT' => append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id)),
		'S_ACTION' => append_sid(album_append_uid('album_hotornot.' . PHP_EXT)),

		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => nl2br($thispic['pic_desc']),
		'POSTER' => $poster,
		'PIC_TIME' => create_date($config['default_dateformat'], $thispic['pic_time'], $config['board_timezone']),
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

	full_page_generation('album_hon.tpl', $lang['Album'], '', '');
}
else
{
	if (!$user->data['session_logged_in'])
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}

	$rate_user_id = $user->data['user_id'];
	$rate_user_ip = $user->data['session_ip'];
	$pic_id = request_var('pic_id', 0);
	if($pic_id <= 0)
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
	$result = $db->sql_query($sql);

	if (!($rated = $db->sql_fetchrow($result)))
	{
		$sql = "INSERT INTO " . ALBUM_RATE_TABLE . " (rate_pic_id, rate_user_id, rate_user_ip, " . $rating_field . ")
				VALUES ('" . $db->sql_escape($pic_id) . "', '" . $db->sql_escape($rate_user_id) . "', '" . $db->sql_escape($rate_user_ip) . "', '" . $db->sql_escape($rate_point) . "')";
		$result = $db->sql_query($sql);
		$rate_string = $lang['Album_rate_successfully'];
	}
	else
	{
		$rate_string = $lang['Already_rated'];
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$redirect_url =  append_sid(album_append_uid('album_hotornot.' . PHP_EXT));
	meta_refresh(3, $redirect_url);

	$message = $rate_string . '<br /><br />' . sprintf($lang['Click_rate_more'], '<a href="' . append_sid(album_append_uid('album_hotornot.' . PHP_EXT)) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

?>