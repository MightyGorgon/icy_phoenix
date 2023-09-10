<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1100_General']['190_Acronyms'] = $file;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$modes_array = array('add', 'delete', 'edit', 'save');
$mode = request_var('mode', '');
$mode = in_array($mode, $modes_array) ? $mode : '';
$mode = !empty($mode) ? $mode : (isset($_POST['add']) ? 'add' : (isset($_POST['save']) ? 'save' : ''));

$acronym_id = request_var('id', 0);

$s_hidden_fields = '';

if(!empty($mode))
{
	if(in_array($mode, array('edit', 'add')))
	{
		$template->set_filenames(array('body' => ADM_TPL . 'acronyms_edit_body.tpl'));

		if($mode == 'edit')
		{
			if(!empty($acronym_id))
			{
				$sql = "SELECT * FROM " . ACRONYMS_TABLE . " WHERE acronym_id = " . (int) $acronym_id;
				$result = $db->sql_query($sql);
				$acronym_info = $db->sql_fetchrow($result);
				$s_hidden_fields .= '<input type="hidden" name="id" value="' . $acronym_id . '" />';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_acronym_selected']);
			}
		}

		$template->assign_vars(array(
			'ACRONYM' => $acronym_info['acronym'],
			'DESCRIPTION' => $acronym_info['description'],

			'L_ACRONYMS_TITLE' => $lang['Acronyms_title'],
			'L_ACRONYMS_TEXT' => $lang['Acronyms_explain'],
			'L_ACRONYM_EDIT' => $lang['Edit_acronym'],
			'L_ACRONYM' => $lang['Acronym'],
			'L_DESCRIPTION' => $lang['Description'],
			'L_SUBMIT' => $lang['Submit'],

			'S_ACRONYMS_ACTION' => append_sid('admin_acronyms.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('body');

		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
	elseif($mode == 'save')
	{
		$acronym = request_post_var('acronym', '', true);
		$description = request_post_var('description', '', true);

		if(empty($acronym) || empty($description))
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_acronym']);
		}

		if(!empty($acronym_id))
		{
			$sql = "UPDATE " . ACRONYMS_TABLE . "
				SET acronym = '" . $db->sql_escape($acronym) . "', description = '" . $db->sql_escape($description) . "'
				WHERE acronym_id = " . (int) $acronym_id;
			$message = $lang['Acronym_updated'];
		}
		else
		{
			$sql = 'SELECT acronym FROM ' . ACRONYMS_TABLE . " WHERE acronym = '" . $db->sql_escape($acronym) . "'";
			$result = $db->sql_query($sql);

			if($db->sql_fetchrow($result))
			{
				$message = 'Acronym already in Database.';
				$message .= '<br /><br />' . sprintf($lang['Click_return_acronymadmin'], '<a href="' . append_sid('admin_acronyms.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
				$db->sql_freeresult($result);
				message_die(GENERAL_MESSAGE, $message);
			}

			$db->sql_freeresult($result);
			$sql = "INSERT INTO " . ACRONYMS_TABLE . " (acronym, description)
				VALUES ('" . $db->sql_escape($acronym) . "', '" . $db->sql_escape($description) . "')";
			$message = $lang['Acronym_added'];
		}

		$result = $db->sql_query($sql);
		$db->clear_cache('acronyms_', TOPICS_CACHE_FOLDER);

		$message .= '<br /><br />' . sprintf($lang['Click_return_acronymadmin'], '<a href="' . append_sid('admin_acronyms.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		if(!empty($acronym_id))
		{
			$sql = "DELETE FROM " . ACRONYMS_TABLE . " WHERE acronym_id = " . (int) $acronym_id;
			$result = $db->sql_query($sql);
			$db->clear_cache('acronyms_', TOPICS_CACHE_FOLDER);

			$message = $lang['Acronym_removed'] . '<br /><br />' . sprintf($lang['Click_return_acronymadmin'], '<a href="' . append_sid('admin_acronyms.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_acronym_selected']);
		}
	}
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'acronyms_list_body.tpl'));

	$sql = "SELECT * FROM " . ACRONYMS_TABLE . " ORDER BY acronym";
	$result = $db->sql_query($sql);
	$word_rows = $db->sql_fetchrowset($result);
	$word_count = sizeof($word_rows);

	$template->assign_vars(array(
		'L_ACRONYMS_TITLE' => $lang['Acronyms_title'],
		'L_ACRONYMS_TEXT' => $lang['Acronyms_explain'],
		'L_ACRONYM' => $lang['Acronym'],
		'L_DESCRIPTION' => $lang['Description'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_ADD_ACRONYM' => $lang['Add_new_acronym'],
		'L_ACTION' => $lang['Action'],
		'S_ACRONYM_ACTION' => append_sid('admin_acronyms.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	for($i = 0; $i < $word_count; $i++)
	{
		$acronym = $word_rows[$i]['acronym'];
		$description = $word_rows[$i]['description'];
		$acronym_id = $word_rows[$i]['acronym_id'];

		$row_class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

		$template->assign_block_vars('acronyms', array(
			'ROW_CLASS' => $row_class,
			'ACRONYM' => $acronym,
			'DESCRIPTION' => $description,
			'U_ACRONYM_EDIT' => append_sid('admin_acronyms.' . PHP_EXT . '?mode=edit&amp;id=' . $acronym_id),
			'U_ACRONYM_DELETE' => append_sid('admin_acronyms.' . PHP_EXT . '?mode=delete&amp;id=' . $acronym_id)
			)
		);
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>