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
* Mohd - (mohdalbasri@hotmail.com)
*
*/

function display_comments(&$file_data)
{
	global $pafiledb, $pafiledb_config, $pafiledb_functions;
	global $db, $cache, $config, $template, $images, $userdata, $lang, $bbcode;
	@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	@include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);

	$template->assign_vars(array(
		'L_COMMENTS' => $lang['Comments']
		)
	);

	$sql = 'SELECT c.*, u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastlogon, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang
		FROM ' . PA_COMMENTS_TABLE . ' AS c
			LEFT JOIN ' . USERS_TABLE . " AS u ON c.poster_id = u.user_id
		WHERE c.file_id = '" . $file_data['file_id'] . "'
		ORDER BY c.comments_time ASC";
	$result = $db->sql_query($sql);

	if (!($comment_number = $db->sql_numrows($result)))
	{
		$template->assign_vars(array(
			'L_NO_COMMENTS' => $lang['No_comments'],
			'NO_COMMENTS' => true
			)
		);
	}

	$ranks_array = $cache->obtain_ranks(false);

	while ($comments_row = $db->sql_fetchrow($result))
	{
		$time = create_date_ip($config['default_dateformat'], $comments_row['comments_time'], $config['board_timezone']);

		$comments_text = $comments_row['comments_text'];
		$comments_text = comment_suite($comments_text);

		$comments_text = censor_text($comments_text);

		//bbcode parser Start
		$bbcode->allow_html = ($pafiledb_config['allow_html'] ? true : false);
		$bbcode->allow_bbcode = ($pafiledb_config['allow_bbcode'] ? true : false);
		$bbcode->allow_smilies = ($pafiledb_config['allow_smilies'] ? true : false);
		$comments_text = $bbcode->parse($comments_text);
		//bbcode parser End

		$poster = ($comments_row['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($comments_row['user_id'], $comments_row['username'], $comments_row['user_color'], $comments_row['user_active']);

		$user_info = array();
		$user_info = generate_user_info($comments_row);
		foreach ($user_info as $k => $v)
		{
			$$k = $v;
		}

		$poster_posts = ($comments_row['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $comments_row['user_posts'] : '';
		$poster_from = $user_info['from'];
		$poster_joined = $user_info['joined'];
		$poster_avatar = $user_info['avatar'];

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_ranks = generate_ranks($comments_row, $ranks_array);
		// Mighty Gorgon - Multiple Ranks - END


		$comments_text = str_replace("\n", "\n<br />\n", $comments_text);

		$template->assign_block_vars('text', array(
			'POSTER' => $poster,
			'U_COMMENT_DELETE' => (($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_delete_comment'] && ($file_info['user_id'] == $userdata['user_id'])) || $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_mod']) ? append_sid('dload.' . PHP_EXT . "?action=post_comment&amp;cid={$comments_row['comments_id']}&amp;delete=do&amp;file_id={$file_data['file_id']}") : '',
			'AUTH_COMMENT_DELETE' => (($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_delete_comment'] && ($file_info['user_id'] == $userdata['user_id'])) || $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_mod']) ? true : false,
			'DELETE_IMG' => (($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_delete_comment'] && ($file_info['user_id'] == $userdata['user_id'])) || $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_mod']) ? $images['icon_delpost'] : '',
			'ICON_MINIPOST_IMG' => IP_ROOT_PATH . $images['icon_minipost'],
			'ICON_SPACER' => IP_ROOT_PATH . $images['spacer'],
			'GENDER' => $user_info['gender'],
			'USER_RANK_01' => $user_ranks['rank_01_html'],
			'USER_RANK_01_IMG' => $user_ranks['rank_01_img_html'],
			'USER_RANK_02' => $user_ranks['rank_02_html'],
			'USER_RANK_02_IMG' => $user_ranks['rank_02_img_html'],
			'USER_RANK_03' => $user_ranks['rank_03_html'],
			'USER_RANK_03_IMG' => $user_ranks['rank_03_img_html'],
			'USER_RANK_04' => $user_ranks['rank_04_html'],
			'USER_RANK_04_IMG' => $user_ranks['rank_04_img_html'],
			'USER_RANK_05' => $user_ranks['rank_05_html'],
			'USER_RANK_05_IMG' => $user_ranks['rank_05_img_html'],
			'POSTER_JOINED' => $poster_joined,
			'POSTER_POSTS' => $poster_posts,
			'POSTER_FROM' => $poster_from,
			'POSTER_AVATAR' => $poster_avatar,
			'TITLE' => $comments_row['comments_title'],
			'TIME' => $time,
			'TEXT' => $comments_text
			)
		);
	}

	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'REPLY_IMG' => ($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_post_comment']) ? $images['pa_comment_post'] : '',
		'AUTH_POST' => ($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_post_comment']) ? true : false,
		'L_COMMENT_DO' => ($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_post_comment']) ? $lang['Comment_do'] : '',
		'L_COMMENTS' => $lang['Comments'],
		'L_AUTHOR' => $lang['Author'],
		'L_POSTED' => $lang['Posted'],
		'L_COMMENT_SUBJECT' => $lang['Comment_subject'],
		'L_COMMENT_ADD' => $lang['Comment_add'],
		'L_COMMENT_DELETE' => $lang['Comment_delete'],
		'L_COMMENTS_NAME' => $lang['Name'],
		'L_BACK_TO_TOP' => $lang['Back_to_top'],
		'SPACER' => $images['spacer'],
		'U_COMMENT_DO' => append_sid('dload.' . PHP_EXT . '?action=post_comment&amp;file_id=' . $file_data['file_id'])
		)
	);
}

function comment_suite($comments_text)
{
	global $pafiledb_config;

	// Start Remove images/links in comments text
	if ($comments_text != '')
	{
		if($pafiledb_config['allow_comment_images'] == 0)
		{
			$no_image_message = $pafiledb_config['no_comment_image_message'];
			if(preg_match('/(<img src=)(.+?)(\>)/i', $comments_text))
			{
				$comments_text = preg_replace('/(<img src=)(.+?)(\>)/i', $no_image_message, $comments_text);
			}

			if(preg_match('/(\[img\])([^\[]*)(\[\/img\])/i', $comments_text))
			{
				$comments_text = preg_replace('/(\[img\])([^\[]*)(\[\/img\])/i', $no_image_message, $comments_text);
			}
		}

		if($pafiledb_config['allow_comment_links'] == 0)
		{
			$no_link_message = $pafiledb_config['no_comment_link_message'];

			if(preg_match('/(\[url=(.*?)\])([^\[]*)(\[\/url\])/i', $comments_text))
			{
				$comments_text = preg_replace('/(\[url=(.*?)\])([^\[]*)(\[\/url\])/i', $no_link_message, $comments_text);
			}

			if(preg_match('/(\[url\])([^\[]*)(\[\/url\])/i', $comments_text))
			{
				$comments_text = preg_replace('/(\[url\])([^\[]*)(\[\/url\])/i', $no_link_message, $comments_text);
			}

			if (preg_match("#([\n ])http://www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $comments_text))
			{
				$comments_text = preg_replace("#([\n ])http://www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $no_link_message, $comments_text);
			}

			if (preg_match("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $comments_text))
			{
				$comments_text = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $no_link_message, $comments_text);
			}
		}
	}
	return $comments_text;
}

?>