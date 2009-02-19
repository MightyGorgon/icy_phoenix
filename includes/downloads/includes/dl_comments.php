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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if ($action == 'save' && !$deny_post)
{
	$html_on = 0;
	$bbcode_on = 0;
	$smilies_on = 0;

	if ($board_config['allow_html'])
	{
		$html_on = $userdata['user_allowhtml'];
	}

	if ($board_config['allow_bbcode'])
	{
		$bbcode_on = $userdata['user_allowbbcode'];
	}

	if ($board_config['allow_smilies'])
	{
		$smilies_on = $userdata['user_allowsmile'];
	}

	$comment_text = stripslashes(prepare_message(addslashes(unprepare_message($comment_text)), $html_on, $bbcode_on, $smilies_on));

	$sql = "SELECT description FROM " . DOWNLOADS_TABLE . "
		WHERE id = $df_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not fetch download name', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$description = $row['description'];
	$db->sql_freeresult($result);

	if ($index[$cat_id]['approve_comments'] && ($userdata['user_level'] != ADMIN) )
	{
		$approve = 1;
	}
	else
	{
		$approve = 0;
	}

	if ($dl_id)
	{
		$sql = "UPDATE " . DL_COMMENTS_TABLE . "
			SET comment_edit_time = " . time() . ", comment_text = '" . str_replace("\'", "''", $comment_text) . "', approve = $approve
			WHERE dl_id = $dl_id";
		if (!($db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update comment', '', __LINE__, __FILE__, $sql);
		}

		$comment_message = $lang['Dl_comment_updated'];
	}
	else
	{
		$sql = "INSERT INTO " . DL_COMMENTS_TABLE . " (id, cat_id, user_id, username, comment_time, comment_edit_time, comment_text, approve) VALUES
			($df_id, $cat_id, " . $userdata['user_id'] . ", '" . str_replace("\'", "''", $userdata['username']) . "', " . time() . ", " . time() . ", '" . str_replace("\'", "''", $comment_text) . "', $approve)";
		if (!($db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not save comment', '', __LINE__, __FILE__, $sql);
		}

		$comment_message = $lang['Dl_comment_added'];
	}

	if (!$approve)
	{
		$processing_user = ($dl_mod->cat_auth_comment_read($cat_id) == 3) ? 0 : $dl_mod->dl_auth_users($cat_id, 'auth_mod');
		$processing_user .= ($processing_user) ? '' : 0;

		$email_template = 'downloads_approve_comment';

		$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
			WHERE user_id IN ($processing_user)
				OR user_level = " . ADMIN;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not send notify email for new downloads', '', __LINE__, __FILE__, $sql);
		}

		$script_path = $board_config['script_path'];
		$server_name = trim($board_config['server_name']);
		$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		while ($row = $db->sql_fetchrow($result))
		{
			//
			// Let's do some checking to make sure that mass mail functions
			// are working in win32 versions of php.
			//

			if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
			{
				$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

				// We are running on windows, force delivery to use our smtp functions
				// since php's are broken by default
				$board_config['smtp_delivery'] = 1;
				$board_config['smtp_host'] = @$ini_val('SMTP');
			}

			$emailer = new emailer($board_config['smtp_delivery']);

			$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
			$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
			$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
			$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

			$emailer->use_template($email_template, $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			$emailer->extra_headers($email_headers);
			$emailer->set_subject();

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'],
				'BOARD_EMAIL' => $board_config['board_email_sig'],
				'CATEGORY' => $index[$cat_id]['cat_name'],
				'USERNAME' => $row['username'],
				'DOWNLOAD' => $description,
				'U_APPROVE' => $server_url.'downloads.' . PHP_EXT . '?view=modcp&amp;action=capprove',
				'U_DOWNLOAD' => $server_url.'downloads.' . PHP_EXT . '?view=comment&action=view&amp;cat_id=' . $cat_id . '&amp;df_id=' . $df_id)
			);

			$emailer->send();
			$emailer->reset();
		}
	}

	$approve_message = ($approve) ? '' : '<br />' . $lang['Dl_must_be_approve_comment'];

	$message = $lang['Dl_comment_added'] . $approve_message . '<br /><br />' . sprintf($lang['Click_return_download_details'], '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

if ($action == 'delete' && $allow_manage)
{
	// Delete comment by poster or admin or dl_mod
	if (!$confirm)
	{
		// Confirm deletion
		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="dl_id" value="' . $dl_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';
		$s_hidden_fields .= '<input type="hidden" name="view" value="comment" />';

		$l_confirm = $lang['Confirm_delete'];

		$page_title = $lang['Downloads'];
		$meta_description = '';
		$meta_keywords = '';
		$nav_server_url = create_server_url();
		$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('confirm_body' => 'confirm_body.tpl'));

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $l_confirm,

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('downloads.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	}

	$sql = "DELETE FROM " . DL_COMMENTS_TABLE . "
		WHERE cat_id = $cat_id
			AND id = $df_id
			AND dl_id = $dl_id";
	if(!($db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not delete comment', '', __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT dl_id FROM " . DL_COMMENTS_TABLE . "
		WHERE cat_id = $cat_id
			AND id = $df_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not check for existing comments', '', __LINE__, __FILE__, $sql);
	}
	$total_comments = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if (!$total_comments)
	{
		redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
	}
	else
	{
		$action = 'view';
	}
}

if (($action == 'edit' && $allow_manage) || ($action == 'post' && !$deny_post))
{
	$sql = "SELECT * FROM " . DOWNLOADS_TABLE . "
		WHERE cat = $cat_id
			AND id = $df_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not fetch download name', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$description = stripslashes($row['description']);
	$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
	$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
	$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
	$description = $bbcode->parse($description);
	$description = str_replace("\n", "\n<br />\n", $description);

	$cat_name = $index[$cat_id]['cat_name'];
	$cat_name = str_replace("&nbsp;&nbsp;|___&nbsp;", "", $cat_name);

	// Edit or add a comment
	if ($action == 'edit')
	{
		$sql = "SELECT comment_text FROM " . DL_COMMENTS_TABLE . "
			WHERE dl_id = $dl_id
				AND id = $df_id
				AND cat_id = $cat_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch comment text', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$comment_text = $row['comment_text'];
		$db->sql_freeresult($result);
	}

	$s_hidden_fields = '<input type="hidden" name="dl_id" value="' . $dl_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';

	if ( $board_config['allow_smilies'] )
	{
		$u_smilies = append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=smilies');
		$l_smilies = $lang['Emoticons'];
	}
	else
	{
		$u_smilies = '';
		$l_smilies = '';
	}

	$page_title = $lang['Downloads'];
	$meta_description = '';
	$meta_keywords = '';
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id) . '">' . $cat_name . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $description . '</a>';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'dl_edit_comments_body.tpl'));

	$template->assign_vars(array(
		'L_DL_COMMENT' => $lang['Dl_comment'],
		'L_FONT_COLOR' => $lang['Font_color'],
		'L_COLOR_DEFAULT' => $lang['color_default'],
		'L_COLOR_DARK_RED' => $lang['color_dark_red'],
		'L_COLOR_RED' => $lang['color_red'],
		'L_COLOR_ORANGE' => $lang['color_orange'],
		'L_COLOR_BROWN' => $lang['color_brown'],
		'L_COLOR_YELLOW' => $lang['color_yellow'],
		'L_COLOR_GREEN' => $lang['color_green'],
		'L_COLOR_OLIVE' => $lang['color_olive'],
		'L_COLOR_CYAN' => $lang['color_cyan'],
		'L_COLOR_BLUE' => $lang['color_blue'],
		'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
		'L_COLOR_INDIGO' => $lang['color_indigo'],
		'L_COLOR_VIOLET' => $lang['color_violet'],
		'L_COLOR_WHITE' => $lang['color_white'],
		'L_COLOR_BLACK' => $lang['color_black'],
		'L_FONT_SIZE' => $lang['Font_size'],
		'L_FONT_TINY' => $lang['font_tiny'],
		'L_FONT_SMALL' => $lang['font_small'],
		'L_FONT_NORMAL' => $lang['font_normal'],
		'L_FONT_LARGE' => $lang['font_large'],
		'L_FONT_HUGE' => $lang['font_huge'],
		'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
		'L_DOWNLOADS' => $lang['Dl_cat_title'],
		'L_SUBMIT' => $lang['Submit'],
		'L_CANCEL' => $lang['Cancel'],
		'L_SMILIES' => $l_smilies,
		'L_EMPTY_MESSAGE' => $lang['Empty_message'],
		'L_BBCURL_URL' => $lang['bbcurl_url'],
		'L_BBCURL_NAME' => $lang['bbcurl_name'],
		'L_BBCURL_DESC' => $lang['bbcurl_desc'],
		'L_BBCURL_NO_URL' => $lang['bbcurl_no_url'],
		'L_BBCURL_NO_NAME' => $lang['bbcurl_no_name'],

		'COMMENT_TEXT' => $comment_text,
		'CAT_NAME' => $cat_name,
		'DESCRIPTION' => $description,

		'S_FORM_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=comment'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields,

		'U_DL_LINK' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id),
		'U_CAT_LINK' => append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id),
		'U_DL_TOP' => append_sid('downloads.' . PHP_EXT),
		'U_SMILIES' => $u_smilies
		)
	);

	if ($board_config['allow_bbcode'])
	{
		$template->assign_block_vars('switch_bbcode_on', array());
	}

	// BBCBMG - BEGIN
	//$bbcbmg_in_acp = true;
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);
	include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
	// BBCBMG - END
	// BBCBMG SMILEYS - BEGIN
	generate_smilies('inline');
	include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
	// BBCBMG SMILEYS - END
}

if ($action == 'view' || !$action)
{
	/*
	* view the comments - users default entry point
	*/
	$sql = "SELECT * FROM " . DL_COMMENTS_TABLE . "
		WHERE cat_id = $cat_id
			AND id = $df_id
			AND approve = " . TRUE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Could not count download comments", '', __LINE__, __FILE__, $sql);
	}

	$total_comments = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($total_comments)
	{
		$comment_row = array();

		$sql = "SELECT * FROM " . DL_COMMENTS_TABLE . "
			WHERE cat_id = $cat_id
				AND id = $df_id
				AND approve = " . TRUE . "
			ORDER BY comment_time DESC
			LIMIT $start, " . $board_config['posts_per_page'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not obtain download comments", '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$comment_row[] = $row;
		}
		$db->sql_freeresult($result);

		if ($total_comments > $board_config['posts_per_page'])
		{
			$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=comment&amp;cat_id=' . $cat_id . '&amp;df_id=' . $df_id, $total_comments, $board_config['posts_per_page'], $start);
		}
		else
		{
			$pagination = '';
		}

		$sql = "SELECT description FROM " . DOWNLOADS_TABLE . "
			WHERE id = $df_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch download name', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$description = $row['description'];
		$db->sql_freeresult($result);

		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace("&nbsp;&nbsp;|___&nbsp;", "", $cat_name);

		$page_title = $lang['Downloads'];
		$meta_description = '';
		$meta_keywords = '';
		$nav_server_url = create_server_url();
		$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id) . '">' . $cat_name . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $description . '</a>';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('body' => 'dl_view_comments_body.tpl'));

		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';

		$template->assign_vars(array(
			'L_COMMENTS' => $page_title,
			'L_POSTER' => $lang['Username'],
			'L_MESSAGE' => $lang['Dl_comment'],
			'L_DL_DELETE' => $lang['Dl_comment_delete'],
			'L_DL_EDIT' => $lang['Dl_comment_edit'],
			'L_POST_COMMENT' => ($deny_post) ? '' : $lang['Dl_comment_write'],
			'L_CAT_NAME' => $lang['Dl_cat_name'],
			'L_DOWNLOADS' => $lang['Dl_cat_title'],

			'CAT_NAME' => $cat_name,
			'DESCRIPTION' => $description,
			'PAGINATION' => $pagination,

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_FORM_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=comment'),

			'U_DL_LINK' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id),
			'U_CAT_LINK' => append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id),
			'U_DL_TOP' => append_sid('downloads.' . PHP_EXT)
			)
		);

		if (!$deny_post)
		{
			$template->assign_block_vars('comment_button', array());
		}

		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		for($i = 0; $i < $total_comments; $i++)
		{
			$poster_id = $comment_row[$i]['user_id'];
			$poster = $comment_row[$i]['username'];
			$dl_id = $comment_row[$i]['dl_id'];

			$message = $comment_row[$i]['comment_text'];
			$comment_time = $comment_row[$i]['comment_time'];
			$comment_edit_time = $comment_row[$i]['comment_edit_time'];

			if(!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
			{
				$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
			}

			$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
			$message = $bbcode->parse($message);
			$message = str_replace("\n", "\n<br />\n", $message);

			if($comment_time <> $comment_edit_time)
			{
				$edited_by = '<hr />'.sprintf($lang['Dl_comment_edited'], create_date($board_config['default_dateformat'], $comment_edit_time, $board_config['board_timezone']));
			}
			else
			{
				$edited_by = '';
			}

			$poster = colorize_username($poster_id);

			$post_time = create_date($board_config['default_dateformat'], $comment_time, $board_config['board_timezone']);

			$u_delete_comment = append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=delete&amp;cat_id=' . $cat_id . '&amp;df_id=' . $df_id . '&amp;dl_id=' . $dl_id);
			$u_edit_comment = append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=edit&amp;cat_id=' . $cat_id . '&amp;df_id=' . $df_id . '&amp;dl_id=' . $dl_id);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('comment_row', array(
				'ROW_CLASS' => $row_class,
				'EDITED_BY' => $edited_by,
				'POSTER' => $poster,
				'MESSAGE' => $message,
				'POST_TIME' => $post_time,
				'DL_ID' => $dl_id,
				'U_DELETE_COMMENT' => $u_delete_comment,
				'U_EDIT_COMMENT' => ($deny_post) ? '' : $u_edit_comment
				)
			);

			if (($poster_id == $userdata['user_id'] || $cat_auth['auth_mod'] || $index[$cat]['auth_mod'] || $userdata['user_level'] == ADMIN) && !$deny_post)
			{
				$template->assign_block_vars('comment_row.action_button', array());
			}
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_search_match']);
	}
}

?>