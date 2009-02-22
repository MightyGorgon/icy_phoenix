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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1800_KB_title']['130_Types_man'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

if(!function_exists('get_list_kb'))
{
	function get_list_kb($id, $select)
	{
		global $db;

		$idfield = 'id';
		$namefield = 'type';

		$sql = "SELECT *
			FROM " . KB_TYPES_TABLE;

		if ($select == 0)
		{
			$sql .= " WHERE $idfield <> $id";
		}

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't get list of types", "", __LINE__, __FILE__, $sql);
		}

		$typelist = "";

		while ($row = $db->sql_fetchrow($result))
		{
			$typelist .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
		}

		return($typelist);
	}
}

// Load default header

if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}
else
{
	if ($create)
	{
		$mode = 'create';
	}
	else if ($edit)
	{
		$mode = 'edit';
	}
	else if ($delete)
	{
		$mode = 'delete';
	}
	else
	{
		$mode = '';
	}
}

switch ($mode)
{
	case 'create':
		$type_name = trim($_POST['new_type_name']);

		if (!$type_name)
		{
			echo "Please put a type name in!";
			exit;
		}

		$sql = "INSERT INTO " . KB_TYPES_TABLE . " (type) VALUES ('$type_name')";

		if (!($results = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, "Could not create type", '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Type_created'] . '<br /><br />' . sprintf($lang['Click_return_type_manager'], '<a href="' . append_sid('admin_kb_types.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		mx_message_die(GENERAL_MESSAGE, $message);
		break;

	case 'edit':

		if (!$_POST['submit'])
		{
			$type_id = intval($_GET['cat']);

			$sql = "SELECT * FROM " . KB_TYPES_TABLE . " WHERE id = " . $type_id;

			if (!($results = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not obtain type", '', __LINE__, __FILE__, $sql);
			}
			if ($type = $db->sql_fetchrow($results))
			{
				$type = $type['type'];
			}

			// Generate page

			$template->set_filenames(array('body' => ADM_TPL . 'kb_type_edit_body.tpl'));

			$template->assign_vars(array('L_EDIT_TITLE' => $lang['Edit_type'],
				'L_CATEGORY' => $lang['Article_type'],
				'L_CAT_SETTINGS' => $lang['Cat_settings'],
				'L_CREATE' => $lang['Edit'],

				'S_ACTION' => append_sid(IP_ROOT_PATH . ADM . '/admin_kb_types.' . PHP_EXT . '?mode=edit'),
				'CAT_NAME' => $type,

				'S_HIDDEN' => '<input type="hidden" name="typeid" value="' . $type_id . '">'
				)
			);
		}
		elseif ($_POST['submit'])
		{
			$type_id = intval($_POST['typeid']);
			$type_name = trim($_POST['catname']);

			if (!$type_name)
			{
				echo "Please put a type name in!";
				exit;
			}

			$sql = "UPDATE " . KB_TYPES_TABLE . " SET type = '" . $type_name . "' WHERE id = " . $type_id;

			if (!($results = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not update type", '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Type_edited'] . '<br /><br />' . sprintf($lang['Click_return_type_manager'], '<a href="' . append_sid('admin_kb_types.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			mx_message_die(GENERAL_MESSAGE, $message);
		}
		break;

	case 'delete':

		if (!$_POST['submit'])
		{
			$type_id = intval($_GET['cat']);

			$sql = "SELECT *
							FROM " . KB_TYPES_TABLE . " WHERE id = '" . $type_id . "'";

			if (!($cat_result = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not obtain type", '', __LINE__, __FILE__, $sql);
			}

			if ($type = $db->sql_fetchrow($cat_result))
			{
				$type_name = $type['type'];
			}

			// Generate page

			$template->set_filenames(array('body' => ADM_TPL . 'kb_cat_del_body.tpl'));

			$template->assign_vars(array(
				'L_DELETE_TITLE' => $lang['Type_delete_title'],
				'L_DELETE_DESCRIPTION' => $lang['Type_delete_desc'],
				'L_CAT_DELETE' => $lang['Type_delete_title'],

				'L_CAT_NAME' => $lang['Article_type'],
				'L_MOVE_CONTENTS' => $lang['Change_type'],
				'L_DELETE' => $lang['Change_and_Delete'],

				'S_HIDDEN_FIELDS' => '<input type="hidden" name="typeid" value="' . $type_id . '">',
				'S_SELECT_TO' => get_list_kb($type_id, 0),
				'S_ACTION' => append_sid(IP_ROOT_PATH . ADM . '/admin_kb_types.' . PHP_EXT . '?mode=delete'),

				'CAT_NAME' => $type_name
				)
			);
		}
		elseif ($_POST['submit'])
		{
			$new_type = $_POST['move_id'];
			$old_type = $_POST['typeid'];

			if ($new_type)
			{
				$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET article_type = '$new_type'
			   WHERE article_type = '$old_type'";
				if (!($move_result = $db->sql_query($sql)))
				{
					mx_message_die(GENERAL_ERROR, "Could not update articles", '', __LINE__, __FILE__, $sql);
				}
			}
			$sql = "DELETE FROM " . KB_TYPES_TABLE . " WHERE id = $old_type";

			if (!($delete_result = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not delete type", '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Type_deleted'] . '<br /><br />' . sprintf($lang['Click_return_type_manager'], '<a href="' . append_sid('admin_kb_types.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			mx_message_die(GENERAL_MESSAGE, $message);
		}
		break;

	default:

		// Generate page

		$template->set_filenames(array('body' => ADM_TPL . 'kb_type_body.tpl')
			);

		$template->assign_vars(array('L_KB_TYPE_TITLE' => $lang['Types_man'],
				'L_KB_TYPE_DESCRIPTION' => $lang['KB_types_description'],

				'L_CREATE_TYPE' => $lang['Create_type'],
				'L_CREATE' => $lang['Create'],
				'L_TYPE' => $lang['Article_type'],
				'L_ACTION' => $lang['Art_action'],

				'S_ACTION' => append_sid(IP_ROOT_PATH . ADM . '/admin_kb_types.' . PHP_EXT . '?mode=create'))
			);
		// get categories
		$sql = "SELECT * FROM " . KB_TYPES_TABLE;
		if (!($cat_result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, "Could not obtain types", '', __LINE__, __FILE__, $sql);
		}

		while ($type = $db->sql_fetchrow($cat_result))
		{
			$type_id = $type['id'];
			$type_name = $type['type'];

			$temp_url = append_sid(IP_ROOT_PATH . ADM . '/admin_kb_types.' . PHP_EXT . '?mode=edit&amp;cat=' . $type_id);
			//$edit = '<a href="' . $temp_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit'] . '"></a>';
			$edit = '<a href="' . $temp_url . '">' . $lang['Edit'] . '</a>';

			$temp_url = append_sid(IP_ROOT_PATH . ADM . '/admin_kb_types.' . PHP_EXT . '?mode=delete&amp;cat=' . $type_id);
			//$delete = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '"></a>';
			$delete = '<a href="' . $temp_url . '">' . $lang['Delete'] . '</a>';

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('typerow', array(
				'TYPE' => $type_name,
				'U_EDIT' => $edit,
				'U_DELETE' => $delete,
				'ROW_CLASS' => $row_class
				)
			);
			$i++;
		}
		break;
}

$template->pparse('body');
// include('./page_footer_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>
