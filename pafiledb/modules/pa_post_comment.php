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
		global $pafiledb_template, $lang, $board_config, $pafiledb_config, $db, $images, $theme, $userdata, $bbcode_tpl;
		global $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;
		global $pafiledb_functions, $bbcode, $template, $view_pic_upload, $user_ip, $session_length, $starttime, $post_image_lang;

		include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
		include_once(IP_ROOT_PATH . PA_FILE_DB_PATH . 'includes/functions_comment.' . PHP_EXT);
		if ( isset($_REQUEST['file_id']) )
		{
			$file_id = intval($_REQUEST['file_id']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		define('IN_PA_POSTING', true);
		define('IN_ICYPHOENIX', true);
		// BBCBMG - BEGIN
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);
		include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
		// BBCBMG - END
		// BBCBMG SMILEYS - BEGIN
		generate_smilies('inline');
		include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
		// BBCBMG SMILEYS - END

// MX Addon
		if ( isset($_REQUEST['cid']) )
		{
			$cid = intval($_REQUEST['cid']);
		}

		$delete = (isset($_REQUEST['delete'])) ? intval($_REQUEST['delete']) : '';

		$submit = (isset($_POST['submit'])) ? true : 0;
		$preview = (isset($_POST['preview'])) ? true : 0;

		$subject = ( !empty($_POST['subject']) ) ? htmlspecialchars(trim(stripslashes($_POST['subject']))) : '';
		$message = ( !empty($_POST['message']) ) ? htmlspecialchars(trim(stripslashes($_POST['message']))) : '';


		$sql = "SELECT file_name, file_catid
			FROM " . PA_FILES_TABLE . "
			WHERE file_id = '" . $file_id . "'";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt select download', '', __LINE__, __FILE__, $sql);
		}

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$db->sql_freeresult($result);

		if( (!$this->auth[$file_data['file_catid']]['auth_post_comment']) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				redirect(append_sid(LOGIN_MG . '?redirect=dload.' . PHP_EXT . '&action=post_comment&file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_download'], $this->auth[$file_data['file_catid']]['auth_post_comment_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$html_on = ( $userdata['user_allowhtml'] && $pafiledb_config['allow_html'] ) ? 1 : 0;
		$bbcode_on = ( $userdata['user_allowbbcode'] && $pafiledb_config['allow_bbcode'] ) ? 1 : 0;
		$smilies_on = ( $userdata['user_allowsmile'] && $pafiledb_config['allow_smilies'] ) ? 1 : 0;

		// =======================================================
		// MX Addon
		// =======================================================
		if($delete == 'do' )
		{
				$sql = 'SELECT *
				FROM ' . PA_FILES_TABLE . "
				WHERE file_id = $file_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
			}
			$file_info = $db->sql_fetchrow($result);

			if ( ($this->auth[$file_info['file_catid']]['auth_delete_comment'] && $file_info['user_id'] == $userdata['user_id']) || $this->auth[$file_info['file_catid']]['auth_mod'] )
			{

			$sql = 'DELETE FROM ' . PA_COMMENTS_TABLE . "
				WHERE comments_id = $cid";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt delete comment', '', __LINE__, __FILE__, $sql);
			}

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

			$html_status = ( $userdata['user_allowhtml'] && $pafiledb_config['allow_html'] ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
			$bbcode_status = ( $userdata['user_allowbbcode'] && $pafiledb_config['allow_bbcode']  ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
			$smilies_status = ( $userdata['user_allowsmile'] && $pafiledb_config['allow_smilies']  ) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
			$links_status = ( $pafiledb_config['allow_comment_links']  ) ? $lang['Links_are_ON'] : $lang['Links_are_OFF'];
			$images_status = ( $pafiledb_config['allow_comment_images']  ) ? $lang['Images_are_ON'] : $lang['Images_are_OFF'];
			$hidden_form_fields = '<input type="hidden" name="action" value="post_comment" /><input type="hidden" name="file_id" value="' . $file_id . '" /><input type="hidden" name="comment" value="post" />';

			// Output the data to the template
			$this->generate_category_nav($file_data['file_catid']);

			$pafiledb_template->assign_vars(array(
				'HTML_STATUS' => $html_status,
				'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_phpbbcode">', '</a>'),
				'SMILIES_STATUS' => $smilies_status,
				'LINKS_STATUS' => $links_status,
				'IMAGES_STATUS' => $images_status,
				'FILE_NAME' => $file_data['file_name'],
				'DOWNLOAD' => $pafiledb_config['settings_dbname'],
				'MESSAGE_LENGTH' => $pafiledb_config['max_comment_chars'],
				'L_HOME' => $lang['Home'],
				'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),

				'L_COMMENT_ADD' => $lang['Comment_add'],
				'L_COMMENT' => $lang['Message_body'],
				'L_COMMENT_TITLE' => $lang['Subject'],
				'L_OPTIONS' => $lang['Options'],
				'L_COMMENT_EXPLAIN' => sprintf($lang['Comment_explain'], $pafiledb_config['max_comment_chars']),
				'L_PREVIEW' => $lang['Preview'],
				'L_SUBMIT' => $lang['Submit'],
				'L_DOWNLOAD'=> $lang['Download'],
				'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
				'L_CHECK_MSG_LENGTH' => $lang['Check_message_length'],
				'L_MSG_LENGTH_1' => $lang['Msg_length_1'],
				'L_MSG_LENGTH_2' => $lang['Msg_length_2'],
				'L_MSG_LENGTH_3' => $lang['Msg_length_3'],
				'L_MSG_LENGTH_4' => $lang['Msg_length_4'],
				'L_MSG_LENGTH_5' => $lang['Msg_length_5'],
				'L_MSG_LENGTH_6' => $lang['Msg_length_6'],

				'U_INDEX' => append_sid(PORTAL_MG),
				'U_DOWNLOAD_HOME' => append_sid('dload.' . PHP_EXT),
				'U_FILE_NAME' => append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id),

				'S_POST_ACTION' => append_sid('dload.' . PHP_EXT),
				'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields
				)
			);

			// Show preview stuff if user clicked preview
			if($preview)
			{
				$orig_word = array();
				$replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);

				$comments_text = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on));

				$title = $subject;

				$bbcode->allow_html = ($html_on ? true : false);
				$bbcode->allow_bbcode = ($bbcode_on ? true : false);
				$bbcode->allow_smilies = ($smilies_on ? true : false);
				$comments_text = $bbcode->parse($comments_text);
				//bbcode parser End

				if( !empty($orig_word) )
				{
					$title = ( !empty($title) ) ? preg_replace($orig_word, $replacement_word, $title) : '';
					$comments_text = ( !empty($comments_text) ) ? preg_replace($orig_word, $replacement_word, $comments_text) : '';
				}

				$comments_text = str_replace("\n", '<br />', $comments_text);

				$pafiledb_template->assign_vars(array(
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
			$length = strlen($_POST['message']);
			$comments_text = str_replace('<br />', "\n", $_POST['message']);

			$poster_id = intval($userdata['user_id']);
			$title = stripslashes($_POST['subject']);
			$time = time();
			if($length > $pafiledb_config['max_comment_chars'])
			{
				message_die(GENERAL_ERROR, 'Your comment is too long!<br/>The maximum length allowed in characters is ' . $pafiledb_config['max_comment_chars'] . '');
			}

			$sql = 'INSERT INTO ' . PA_COMMENTS_TABLE . "(file_id, comments_text, comments_title, comments_time, poster_id)
				VALUES($file_id, '" . str_replace("\'", "''", $comments_text) . "','" . str_replace("\'", "''", $title) . "', $time, $poster_id)";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt insert comments', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Comment_posted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		$this->display($lang['Download'], 'pa_comment_posting.tpl');
	}
}

?>