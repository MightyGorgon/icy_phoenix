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
	global $pafiledb_template, $lang, $board_config, $pafiledb_config, $db, $images;
	global $userdata, $db, $pafiledb, $pafiledb_functions, $bbcode, $comment_bbcode_uid;
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
	require_once(IP_ROOT_PATH . 'includes/functions_mg_ranks.' . PHP_EXT);
	$ranks_sql = query_ranks();
	//
	// Define censored word matches
	//

	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);

	$pafiledb_template->assign_vars(array(
		'L_COMMENTS' => $lang['Comments']
		)
	);

	$sql = 'SELECT c.*, u.*
		FROM ' . PA_COMMENTS_TABLE . ' AS c
			LEFT JOIN ' . USERS_TABLE . " AS u ON c.poster_id = u.user_id
		WHERE c.file_id = '" . $file_data['file_id'] . "'
		ORDER BY c.comments_time ASC";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt select comments', '', __LINE__, __FILE__, $sql);
	}

	if (!($comment_number = $db->sql_numrows($result)))
	{
		$pafiledb_template->assign_vars(array(
			'L_NO_COMMENTS' => $lang['No_comments'],
			'NO_COMMENTS' => TRUE)
		);
	}

	$ranksrow = array();
	$pafiledb_functions->obtain_ranks($ranksrow);

	while ($comments_row = $db->sql_fetchrow($result))
	{
		$time = create_date2($board_config['default_dateformat'], $comments_row['comments_time'], $board_config['board_timezone']);

		$comments_text = $comments_row['comments_text'];
		$comments_text = comment_suite($comments_text);

		//bbcode parser Start
		$comment_bbcode_uid = make_bbcode_uid();
		$bbcode->allow_html = ( $pafiledb_config['allow_html'] ? true : false );
		$bbcode->allow_bbcode = ( $pafiledb_config['allow_bbcode'] ? true : false );
		$bbcode->allow_smilies = ( $pafiledb_config['allow_smilies'] ? true : false );
		$comments_text = $bbcode->parse($comments_text, $comment_bbcode_uid);
		//bbcode parser End

		if ( count($orig_word) )
		{
			if ( $comments_text != '' )
			{
				$comments_text = preg_replace($orig_word, $replacement_word, $comments_text);
			}
		}



		$poster = ( $comments_row['user_id'] == ANONYMOUS ) ? $lang['Guest'] : colorize_username($comments_row['user_id']);

		$poster_posts = ( $comments_row['user_id'] != ANONYMOUS ) ? $lang['Posts'] . ': ' . $comments_row['user_posts'] : '';

		$poster_from = ( $comments_row['user_from'] && $comments_row['user_id'] != ANONYMOUS ) ? $lang['Location'] . ': ' . $comments_row['user_from'] : '';

		$poster_joined = ( $comments_row['user_id'] != ANONYMOUS ) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $comments_row['user_regdate'], $board_config['board_timezone']) : '';

		$poster_avatar = '';
		if ( $comments_row['user_avatar_type'] && $poster_id != ANONYMOUS && $comments_row['user_allowavatar'] )
		{
			switch( $comments_row['user_avatar_type'] )
			{
				case USER_AVATAR_UPLOAD:
					$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $comments_row['user_avatar'] . '" alt="" />' : '';
					break;
				case USER_AVATAR_REMOTE:
					$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $comments_row['user_avatar'] . '" alt="" />' : '';
					break;
				case USER_AVATAR_GALLERY:
					$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $comments_row['user_avatar'] . '" alt="" />' : '';
					break;
			}
		}

		if ($poster_avatar == '')
		{
			$poster_avatar = get_default_avatar($comments_row['user_id']);
		}

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_ranks = generate_ranks($comments_row, $ranks_sql);

		$user_rank_01 = ($user_ranks['rank_01'] == '') ? '' : ($user_ranks['rank_01'] . '<br />');
		$user_rank_01_img = ($user_ranks['rank_01_img'] == '') ? '' : ($user_ranks['rank_01_img'] . '<br />');
		$user_rank_02 = ($user_ranks['rank_02'] == '') ? '' : ($user_ranks['rank_02'] . '<br />');
		$user_rank_02_img = ($user_ranks['rank_02_img'] == '') ? '' : ($user_ranks['rank_02_img'] . '<br />');
		$user_rank_03 = ($user_ranks['rank_03'] == '') ? '' : ($user_ranks['rank_03'] . '<br />');
		$user_rank_03_img = ($user_ranks['rank_03_img'] == '') ? '' : ($user_ranks['rank_03_img'] . '<br />');
		$user_rank_04 = ($user_ranks['rank_04'] == '') ? '' : ($user_ranks['rank_04'] . '<br />');
		$user_rank_04_img = ($user_ranks['rank_04_img'] == '') ? '' : ($user_ranks['rank_04_img'] . '<br />');
		$user_rank_05 = ($user_ranks['rank_05'] == '') ? '' : ($user_ranks['rank_05'] . '<br />');
		$user_rank_05_img = ($user_ranks['rank_05_img'] == '') ? '' : ($user_ranks['rank_05_img'] . '<br />');
		// Mighty Gorgon - Multiple Ranks - END


		$comments_text = str_replace("\n", "\n<br />\n", $comments_text);

		$pafiledb_template->assign_block_vars('text', array(
			'POSTER' => $poster,
			'U_COMMENT_DELETE' => ( ($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_delete_comment'] && $file_info['user_id'] == $userdata['user_id']) || $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_mod']) ? append_sid("dload.php?action=post_comment&cid={$comments_row['comments_id']}&delete=do&file_id={$file_data['file_id']}") : '',
			'AUTH_COMMENT_DELETE' => ( ($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_delete_comment'] && $file_info['user_id'] == $userdata['user_id']) || $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_mod']) ? TRUE : FALSE,
			'DELETE_IMG' => ( ($pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_delete_comment'] && $file_info['user_id'] == $userdata['user_id']) || $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_mod']) ? $images['icon_delpost'] : '',
			'ICON_MINIPOST_IMG' => IP_ROOT_PATH . $images['icon_minipost'],
			'ICON_SPACER' => IP_ROOT_PATH . 'images/spacer.gif',
			'USER_RANK_01' => $user_rank_01,
			'USER_RANK_01_IMG' => $user_rank_01_img,
			'USER_RANK_02' => $user_rank_02,
			'USER_RANK_02_IMG' => $user_rank_02_img,
			'USER_RANK_03' => $user_rank_03,
			'USER_RANK_03_IMG' => $user_rank_03_img,
			'USER_RANK_04' => $user_rank_04,
			'USER_RANK_04_IMG' => $user_rank_04_img,
			'USER_RANK_05' => $user_rank_05,
			'USER_RANK_05_IMG' => $user_rank_05_img,
			'POSTER_JOINED' => $poster_joined,
			'POSTER_POSTS' => $poster_posts,
			'POSTER_FROM' => $poster_from,
			'POSTER_AVATAR' => $poster_avatar,
			'TITLE' => $comments_row['comments_title'],
			'TIME' => $time,
			'TEXT' => $comments_text)
		);
	}

	$db->sql_freeresult($result);

	$pafiledb_template->assign_vars(array(
		'REPLY_IMG' => ( $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_post_comment'] ) ? $images['pa_comment_post'] : '',
		'AUTH_POST' => ( $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_post_comment'] ) ? TRUE : FALSE,
		'L_COMMENT_DO' => ( $pafiledb->modules[$pafiledb->module_name]->auth[$file_data['file_catid']]['auth_post_comment'] ) ? $lang['Comment_do'] : '',
		'L_COMMENTS' => $lang['Comments'],
		'L_AUTHOR' => $lang['Author'],
		'L_POSTED' => $lang['Posted'],
		'L_COMMENT_SUBJECT' => $lang['Comment_subject'],
		'L_COMMENT_ADD' => $lang['Comment_add'],
		'L_COMMENT_DELETE' => $lang['Comment_delete'],
		'L_COMMENTS_NAME' => $lang['Name'],
		'L_BACK_TO_TOP' => $lang['Back_to_top'],
		'U_COMMENT_DO' => append_sid('dload.php?action=post_comment&file_id='.$file_data['file_id']))
	);
}

function comment_suite($comments_text)
{
	global $pafiledb_config;

	// Start Remove images/links in comments text
	if ( $comments_text != '' )
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

			if (preg_match("#([\n ])http://www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $comments_text) )
			{
				$comments_text = preg_replace("#([\n ])http://www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $no_link_message, $comments_text);
			}

			if (preg_match("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $comments_text) )
			{
				$comments_text = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,\t \n\r]*)?)#i", $no_link_message, $comments_text);
			}
		}
	}
	return $comments_text;
}

?>