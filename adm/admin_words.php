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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1100_General']['180_Word_Censor'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$cancel = (isset($_POST['cancel'])) ? true : false;
$no_page_header = $cancel;
require('pagestart.' . PHP_EXT);
if ($cancel)
{
	redirect(ADM . '/' . append_sid('admin_words.' . PHP_EXT, true));
}

$mode = request_var('mode', '');
if(empty($mode))
{
	// These could be entered via a form button
	if(isset($_POST['add']))
	{
		$mode = 'add';
	}
	elseif(isset($_POST['save']))
	{
		$mode = 'save';
	}
}

// Restrict mode input to valid options
$mode = (in_array($mode, array('add', 'edit', 'save', 'delete'))) ? $mode : '';

if($mode != '')
{
	if(($mode == 'edit') || ($mode == 'add'))
	{
		$word_id = request_var('id', 0);

		$template->set_filenames(array('body' => ADM_TPL . 'words_edit_body.tpl'));
		$word_info = array('word' => '', 'replacement' => '');
		$s_hidden_fields = '';

		if($mode == 'edit')
		{
			if(!empty($word_id))
			{
				$sql = "SELECT *
					FROM " . WORDS_TABLE . "
					WHERE word_id = $word_id";
				$result = $db->sql_query($sql);
				$word_info = $db->sql_fetchrow($result);
				$s_hidden_fields .= '<input type="hidden" name="id" value="' . $word_id . '" />';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_word_selected']);
			}
		}

		$template->assign_vars(array(
			'WORD' => htmlspecialchars($word_info['word']),
			'REPLACEMENT' => htmlspecialchars($word_info['replacement']),

			'L_WORDS_TITLE' => $lang['Words_title'],
			'L_WORDS_TEXT' => $lang['Words_explain'],
			'L_WORD_CENSOR' => $lang['Edit_word_censor'],
			'L_WORD' => $lang['Word'],
			'L_REPLACEMENT' => $lang['Replacement'],
			'L_SUBMIT' => $lang['Submit'],

			'S_WORDS_ACTION' => append_sid('admin_words.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('body');

		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
	elseif($mode == 'save')
	{
		$word_id = request_post_var('id', 0);
		$word = request_post_var('word', '', true);
		$word = htmlspecialchars_decode($word, ENT_COMPAT);
		$replacement = request_post_var('replacement', '', true);
		$replacement = htmlspecialchars_decode($replacement, ENT_COMPAT);

		if(empty($word) || empty($replacement))
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_word']);
		}

		if(!empty($word_id))
		{
			$sql = "UPDATE " . WORDS_TABLE . "
				SET word = '" . $db->sql_escape($word) . "', replacement = '" . $db->sql_escape($replacement) . "'
				WHERE word_id = $word_id";
			$message = $lang['Word_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . WORDS_TABLE . " (word, replacement)
				VALUES ('" . $db->sql_escape($word) . "', '" . $db->sql_escape($replacement) . "')";
			$message = $lang['Word_added'];
		}
		$result = $db->sql_query($sql);
		$cache->destroy('_word_censors');

		$message .= '<br /><br />' . sprintf($lang['Click_return_wordadmin'], '<a href="' . append_sid('admin_words.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		$word_id = request_var('id', 0);

		$confirm = isset($_POST['confirm']);

		if($word_id && $confirm)
		{
			$sql = "DELETE FROM " . WORDS_TABLE . "
				WHERE word_id = $word_id";
			$result = $db->sql_query($sql);
			$cache->destroy('_word_censors');

			$message = $lang['Word_removed'] . '<br /><br />' . sprintf($lang['Click_return_wordadmin'], '<a href="' . append_sid('admin_words.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			}
		elseif($word_id && !$confirm)
		{
			// Present the confirmation screen to the user
			$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));

			$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="id" value="' . $word_id . '" />';

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_word'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('admin_words.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_word_selected']);
		}
	}
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'words_list_body.tpl'));

	$sql = "SELECT *
		FROM " . WORDS_TABLE . "
		ORDER BY word";
	$result = $db->sql_query($sql);
	$word_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	$word_count = sizeof($word_rows);

	$template->assign_vars(array(
		'L_WORDS_TITLE' => $lang['Words_title'],
		'L_WORDS_TEXT' => $lang['Words_explain'],
		'L_WORD' => $lang['Word'],
		'L_REPLACEMENT' => $lang['Replacement'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_ADD_WORD' => $lang['Add_new_word'],
		'L_ACTION' => $lang['Action'],

		'S_WORDS_ACTION' => append_sid('admin_words.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => ''
		)
	);

	for($i = 0; $i < $word_count; $i++)
	{
		$word = $word_rows[$i]['word'];
		$replacement = $word_rows[$i]['replacement'];
		$word_id = $word_rows[$i]['word_id'];

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('words', array(
			'ROW_CLASS' => $row_class,
			'WORD' => htmlspecialchars($word),
			'REPLACEMENT' => htmlspecialchars($replacement),

			'U_WORD_EDIT' => append_sid('admin_words.' . PHP_EXT . '?mode=edit&amp;id=' . $word_id),
			'U_WORD_DELETE' => append_sid('admin_words.' . PHP_EXT . '?mode=delete&amp;id=' . $word_id)
			)
		);
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>