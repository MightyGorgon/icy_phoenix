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
* Illuminati Gaming Network (whofarted75@yahoo.com)
*
*/

class pafiledb_post_comment extends pafiledb_public
{
	function main($action)
	{
		global $db, $cache, $config, $template, $images, $theme, $user, $lang, $bbcode, $bbcode_tpl;
		global $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;
		global $pafiledb_functions, $pafiledb_config, $view_pic_upload, $starttime, $post_image_lang;

		@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		@include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
		@include_once(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_comment.' . PHP_EXT);
		$file_id = request_var('file_id', 0);
		if (empty($file_id))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		define('IN_PA_POSTING', true);
		define('IN_ICYPHOENIX', true);
		// BBCBMG - BEGIN
		include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
		// BBCBMG - END
		// BBCBMG SMILEYS - BEGIN
		generate_smilies('inline');
		include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
		// BBCBMG SMILEYS - END

		// MX Addon
		$cid = request_var('cid', 0);
		$delete = request_var('delete', '');
		$submit = (isset($_POST['submit'])) ? true : false;
		$preview = (isset($_POST['preview'])) ? true : false;

		$subject = request_post_var('subject', '', true);
		$message = request_post_var('message', '', true);

		$sql = "SELECT file_name, file_catid
			FROM " . PA_FILES_TABLE . "
			WHERE file_id = '" . $file_id . "'";
		$result = $db->sql_query($sql);

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$db->sql_freeresult($result);

		if((!$this->auth[$file_data['file_catid']]['auth_post_comment']))
		{
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=post_comment&file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_download'], $this->auth[$file_data['file_catid']]['auth_post_comment_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$html_on = ($user->data['user_allowhtml'] && $pafiledb_config['allow_html']) ? 1 : 0;
		$bbcode_on = ($user->data['user_allowbbcode'] && $pafiledb_config['allow_bbcode']) ? 1 : 0;
		$smilies_on = ($user->data['user_allowsmile'] && $pafiledb_config['allow_smilies']) ? 1 : 0;

		// =======================================================
		// MX Addon
		// =======================================================
		if($delete == 'do')
		{
			$sql = 'SELECT *
				FROM ' . PA_FILES_TABLE . "
				WHERE file_id = $file_id";
			$result = $db->sql_query($sql);

			$file_info = $db->sql_fetchrow($result);

			if (($this->auth[$file_info['file_catid']]['auth_delete_comment'] && $file_info['user_id'] == $user->data['user_id']) || $this->auth[$file_info['file_catid']]['auth_mod'])
			{
				$sql = 'DELETE FROM ' . PA_COMMENTS_TABLE . "
					WHERE comments_id = $cid";
				$db->sql_query($sql);
				$this->_pafiledb();

				$message = $lang['Comment_deleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$message = sprintf($lang['Sorry_auth_delete'], $this->auth[$cat_id]['auth_upload_type']);
				message_die(GENERAL_MESSAGE, $message);
			}
		}

		if(!$submit)
		{

			// Generate smilies listing for page output
			//$pafiledb_functions->pa_generate_smilies('inline');

			$html_status = ($user->data['user_allowhtml'] && $pafiledb_config['allow_html']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
			$bbcode_status = ($user->data['user_allowbbcode'] && $pafiledb_config['allow_bbcode'] ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
			$smilies_status = ($user->data['user_allowsmile'] && $pafiledb_config['allow_smilies'] ) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
			$links_status = ($pafiledb_config['allow_comment_links'] ) ? $lang['Links_are_ON'] : $lang['Links_are_OFF'];
			$images_status = ($pafiledb_config['allow_comment_images'] ) ? $lang['Images_are_ON'] : $lang['Images_are_OFF'];
			$hidden_form_fields = '<input type="hidden" name="action" value="post_comment" /><input type="hidden" name="file_id" value="' . $file_id . '" /><input type="hidden" name="comment" value="post" />';

			// Output the data to the template
			$this->generate_category_nav($file_data['file_catid']);

			$template->assign_vars(array(
				'HTML_STATUS' => $html_status,
				'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
				'SMILIES_STATUS' => $smilies_status,
				'LINKS_STATUS' => $links_status,
				'IMAGES_STATUS' => $images_status,
				'FILE_NAME' => $file_data['file_name'],
				'DOWNLOAD' => $pafiledb_config['settings_dbname'],
				'MESSAGE_LENGTH' => $pafiledb_config['max_comment_chars'],
				'L_HOME' => $lang['Home'],
				'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

				'L_COMMENT_ADD' => $lang['Comment_add'],
				'L_COMMENT' => $lang['Message_body'],
				'L_COMMENT_TITLE' => $lang['Subject'],
				'L_OPTIONS' => $lang['Options'],
				'L_COMMENT_EXPLAIN' => sprintf($lang['Comment_explain'], $pafiledb_config['max_comment_chars']),
				'L_PREVIEW' => $lang['Preview'],
				'L_SUBMIT' => $lang['Submit'],
				'L_DOWNLOAD'=> $lang['Download'],
				'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
				'L_CHECK_MSG_LENGTH' => $lang['Check_message_length'],
				'L_MSG_LENGTH_1' => $lang['Msg_length_1'],
				'L_MSG_LENGTH_2' => $lang['Msg_length_2'],
				'L_MSG_LENGTH_3' => $lang['Msg_length_3'],
				'L_MSG_LENGTH_4' => $lang['Msg_length_4'],
				'L_MSG_LENGTH_5' => $lang['Msg_length_5'],
				'L_MSG_LENGTH_6' => $lang['Msg_length_6'],

				'U_INDEX' => append_sid(CMS_PAGE_HOME),
				'U_DOWNLOAD_HOME' => append_sid('dload.' . PHP_EXT),
				'U_FILE_NAME' => append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id),

				'S_POST_ACTION' => append_sid('dload.' . PHP_EXT),
				'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields
				)
			);

			// Show preview stuff if user clicked preview
			if($preview)
			{
				$comments_text = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on));

				$title = $subject;

				$title = censor_text($title);
				$comments_text = censor_text($comments_text);

				$bbcode->allow_html = ($html_on ? true : false);
				$bbcode->allow_bbcode = ($bbcode_on ? true : false);
				$bbcode->allow_smilies = ($smilies_on ? true : false);
				$comments_text = $bbcode->parse($comments_text);
				//bbcode parser End

				$comments_text = str_replace("\n", '<br />', $comments_text);

				$template->assign_vars(array(
					'PREVIEW' => true,
					'COMMENT' => stripslashes($_POST['message']),
					'SUBJECT' => stripslashes($_POST['subject']),
					'PRE_COMMENT' => $comments_text
					)
				);
			}
		}

		if($submit)
		{
			$subject = request_post_var('subject', '', true);
			$message = request_post_var('message', '', true);
			$message = htmlspecialchars_decode($message, ENT_COMPAT);
			$length = strlen($message);

			//$comments_text = str_replace('<br />', "\n", $message);
			$comments_text = $message;

			$poster_id = intval($user->data['user_id']);
			$title = $subject;
			$time = time();
			if($length > $pafiledb_config['max_comment_chars'])
			{
				message_die(GENERAL_ERROR, 'Your comment is too long!<br />The maximum length allowed in characters is ' . $pafiledb_config['max_comment_chars'] . '');
			}

			$sql = 'INSERT INTO ' . PA_COMMENTS_TABLE . "(file_id, comments_text, comments_title, comments_time, poster_id)
				VALUES($file_id, '" . $db->sql_escape($comments_text) . "','" . $db->sql_escape($title) . "', $time, $poster_id)";
			$db->sql_query($sql);

			$message = $lang['Comment_posted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		$this->display($lang['Download'], 'pa_comment_posting.tpl');
	}
}

?>