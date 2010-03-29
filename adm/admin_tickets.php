<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1100_General']['220_Tickets_Emails'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// DB CLASS - BEGIN
include(IP_ROOT_PATH . 'includes/class_db.' . PHP_EXT);
$class_db = new class_db();
$class_db->main_db_table = TICKETS_CAT_TABLE;
$class_db->main_db_item = 'ticket_cat_id';
// DB CLASS - END

// MODES - BEGIN
$mode_types = array('list', 'add', 'edit', 'save', 'delete');
$mode = request_var('mode', $mode_types[0]);
$mode = (isset($_POST['add']) ? 'add' : (isset($_POST['save']) ? 'save' : $mode));
$mode = (!in_array($mode, $mode_types) ? $mode_types[0] : $mode);
// MODES - END

// VARS - BEGIN
$ticket_cat_id = request_var('ticket_cat_id', 0);
$s_hidden_fields = '';
// VARS - END

if($mode != 'list')
{
	if(($mode == 'edit') || ($mode == 'add'))
	{
		$template->set_filenames(array('body' => ADM_TPL . 'tickets_edit_body.tpl'));

		if($mode == 'edit')
		{
			if($ticket_cat_id)
			{
				$sql = "SELECT * FROM " . TICKETS_CAT_TABLE . " WHERE ticket_cat_id = " . $ticket_cat_id;
				$result = $db->sql_query($sql);
				$ticket_info = $db->sql_fetchrow($result);
				$s_hidden_fields .= '<input type="hidden" name="ticket_cat_id" value="' . $ticket_cat_id . '" />';
				$db->sql_freeresult($result);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['TICKETS_NO_TICKET_SEL']);
			}
		}

		$template->assign_vars(array(
			'TICKET_TITLE' => $ticket_info['ticket_cat_title'],
			'TICKET_DESCRIPTION' => $ticket_info['ticket_cat_des'],
			'TICKET_EMAILS' => $ticket_info['ticket_cat_emails'],

			'L_SUBMIT' => $lang['Submit'],

			'S_TICKETS_ACTION' => append_sid('admin_tickets.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}
	elseif($mode == 'save')
	{
		$ticket_cat_title = request_var('ticket_cat_title', '', true);
		$ticket_cat_des = request_var('ticket_cat_des', '', true);
		$ticket_cat_emails = request_var('ticket_cat_emails', '', true);

		if($ticket_cat_title == '')
		{
			message_die(GENERAL_MESSAGE, $lang['TICKETS_NO_TICKET_TITLE']);
		}

		$inputs_array = array(
			'ticket_cat_title' => $ticket_cat_title,
			'ticket_cat_des' => $ticket_cat_des,
			'ticket_cat_emails' => $ticket_cat_emails,
		);

		if ($ticket_cat_id > 0)
		{
			$class_db->update_item($ticket_cat_id, $inputs_array);
			$message = '<br /><br />' . $lang['TICKETS_DB_UPDATED'];
		}
		else
		{
			$class_db->insert_item($inputs_array);
			$message = '<br /><br />' . $lang['TICKETS_DB_ADDED'];
		}

		$message .= '<br /><br />' . sprintf($lang['TICKETS_DB_CLICK'], '<a href="' . append_sid('admin_tickets.' . PHP_EXT) . '">', '</a>');
		$message .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		$ticket_cat_id = request_var('ticket_cat_id', 0);

		if($ticket_cat_id > 0)
		{
			$class_db->delete_item($ticket_cat_id);
			$message = '<br /><br />' . $lang['TICKETS_DB_DELETED'];
		}
		else
		{
			$message = '<br /><br />' . $lang['TICKETS_NO_TICKET_SEL'];
		}
		$message .= '<br /><br />' . sprintf($lang['TICKETS_DB_CLICK'], '<a href="' . append_sid('admin_tickets.' . PHP_EXT) . '">', '</a>');
		$message .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'tickets_list_body.tpl'));

	$sql = "SELECT * FROM " . TICKETS_CAT_TABLE . " ORDER BY ticket_cat_id ASC";
	$result = $db->sql_query($sql);
	$ticket_rows = $db->sql_fetchrowset($result);
	$tickets_count = sizeof($ticket_rows);
	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'S_TICKETS_ACTION' => append_sid('admin_tickets.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	if ($tickets_count == 0)
	{
		$template->assign_var('S_NO_TICKETS', true);
	}
	else
	{
		for($i = 0; $i < $tickets_count; $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('ticket', array(
				'ROW_CLASS' => $row_class,
				'TICKET_TITLE' => $ticket_rows[$i]['ticket_cat_title'],
				'TICKET_DESCRIPTION' => $ticket_rows[$i]['ticket_cat_des'],
				'TICKET_EMAILS' => $ticket_rows[$i]['ticket_cat_emails'],

				'U_EDIT' => append_sid('admin_tickets.' . PHP_EXT . '?mode=edit&amp;ticket_cat_id=' . $ticket_rows[$i]['ticket_cat_id']),
				'U_DELETE' => append_sid('admin_tickets.' . PHP_EXT . '?mode=delete&amp;ticket_cat_id=' . $ticket_rows[$i]['ticket_cat_id'])
				)
			);
		}
	}
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>