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

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);

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
require('./pagestart.' . PHP_EXT);
if ($cancel)
{
	redirect(ADM . '/' . append_sid('admin_words.' . PHP_EXT, true));
}

if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_GET['mode'])) ? $_GET['mode'] : $_POST['mode'];
	$mode = htmlspecialchars($mode);
}
else
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
	else
	{
		$mode = '';
	}
}
// Restrict mode input to valid options
$mode = (in_array($mode, array('add', 'edit', 'save', 'delete'))) ? $mode : '';

if($mode != '')
{
	if($mode == 'edit' || $mode == 'add')
	{
		$word_id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;

		$template->set_filenames(array('body' => ADM_TPL . 'words_edit_body.tpl'));
		$word_info = array('word' => '', 'replacement' => '');
		$s_hidden_fields = '';

		if($mode == 'edit')
		{
			if($word_id)
			{
				$sql = "SELECT *
					FROM " . WORDS_TABLE . "
					WHERE word_id = $word_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not query words table", "Error", __LINE__, __FILE__, $sql);
				}

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

		include('./page_footer_admin.' . PHP_EXT);
	}
	elseif($mode == 'save')
	{
		$word_id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
		$word = (isset($_POST['word'])) ? trim($_POST['word']) : "";
		$replacement = (isset($_POST['replacement'])) ? trim($_POST['replacement']) : "";

		if($word == "" || $replacement == "")
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_word']);
		}

		if($word_id)
		{
			$sql = "UPDATE " . WORDS_TABLE . "
				SET word = '" . str_replace("\'", "''", $word) . "', replacement = '" . str_replace("\'", "''", $replacement) . "'
				WHERE word_id = $word_id";
			$message = $lang['Word_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . WORDS_TABLE . " (word, replacement)
				VALUES ('" . str_replace("\'", "''", $word) . "', '" . str_replace("\'", "''", $replacement) . "')";
			$message = $lang['Word_added'];
		}

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not insert data into words table", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_wordadmin'], '<a href="' . append_sid('admin_words.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		cache_words();
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		if(isset($_POST['id']) ||  isset($_GET['id']))
		{
			$word_id = (isset($_POST['id'])) ? $_POST['id'] : $_GET['id'];
			$word_id = intval($word_id);
		}
		else
		{
			$word_id = 0;
		}

		$confirm = isset($_POST['confirm']);

		if($word_id && $confirm)
		{
			$sql = "DELETE FROM " . WORDS_TABLE . "
				WHERE word_id = $word_id";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not remove data from words table", $lang['Error'], __LINE__, __FILE__, $sql);
			}
			cache_words();
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
				'S_HIDDEN_FIELDS' => $hidden_fields)
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
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query words table", $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$word_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	$word_count = count($word_rows);

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

		$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('words', array(
			'ROW_COLOR' => '#' . $row_color,
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

include('./page_footer_admin.' . PHP_EXT);

?>