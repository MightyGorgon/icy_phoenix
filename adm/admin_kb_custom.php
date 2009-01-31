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

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1800_KB_title']['140_Custom_Field'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

// ===================================================
// addslashes to vars if magic_quotes_gpc is off
// ===================================================
if (!@function_exists('slash_input_data'))
{
	function slash_input_data(&$data)
	{
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$data[$k] = (is_array($v)) ? slash_input_data($v) : addslashes($v);
			}
		}
		return $data;
	}
}

if (!isset($_REQUEST))
{
	$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
}

$kb_custom_field = new kb_custom_field();
$kb_custom_field->init();

$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : 'select';
$field_id = (isset($_REQUEST['field_id'])) ? intval($_REQUEST['field_id']) : 0;
$field_type = (isset($_REQUEST['field_type'])) ? intval($_REQUEST['field_type']) : $kb_custom_field->field_rowset[$field_id]['field_type'];
$field_ids = (isset($_REQUEST['field_ids'])) ? $_REQUEST['field_ids'] : '';
$submit = (isset($_POST['submit'])) ? true : false;

switch ($mode)
{
	case 'addfield':
		$template_file = ADM_TPL . 'kb_field_add.tpl';
		break;
	case 'editfield':
		$template_file = ADM_TPL . 'kb_field_add.tpl';
		break;
	case 'edit':
		$template_file = ADM_TPL . 'kb_select_field_edit.tpl';
		break;
	case 'add':
		$template_file = ADM_TPL . 'kb_select_field_type.tpl';
		break;
	case 'delete':
		$template_file = ADM_TPL . 'kb_select_field_delete.tpl';
		break;
	case 'select':
		$template_file = ADM_TPL . 'kb_select.tpl';
		break;
}

if ($submit)
{
	if ($mode == 'do_add' && !$field_id)
	{
		$kb_custom_field->update_add_field($field_type);

		$message = $lang['Fieldadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_kb_custom.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		mx_message_die(GENERAL_MESSAGE, $message);
	}
	elseif ($mode == 'do_add' && $field_id)
	{
		$kb_custom_field->update_add_field($field_type, $field_id);

		$message = $lang['Fieldedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_kb_custom.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		mx_message_die(GENERAL_MESSAGE, $message);
	}
	elseif ($mode == 'do_delete')
	{
		foreach($field_ids as $key => $value)
		{
			$kb_custom_field->delete_field($key);
		}

		$message = $lang['Fieldsdel'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_kb_custom.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		mx_message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array('admin' => $template_file)
	);

switch ($mode)
{
	case 'add':
	case 'addfield':
		$l_title = $lang['Afieldtitle'];
		break;
	case 'edit':
		$l_title = $lang['Efieldtitle'];
		break;
	case 'editfield':
		$l_title = $lang['Efieldtitle'];
		break;
	case 'delete':
		$l_title = $lang['Dfieldtitle'];
		break;
	case 'select':
		$l_title = $lang['Mfieldtitle'];
		break;
}

if ($mode == 'add')
{
	$s_hidden_fields = '<input type="hidden" name="mode" value="addfield">';
}
elseif ($mode == 'addfield' || $mode == 'editfield')
{
	$s_hidden_fields = '<input type="hidden" name="field_type" value="' . $field_type . '">';
	$s_hidden_fields .= '<input type="hidden" name="field_id" value="' . $field_id . '">';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="do_add">';
}
elseif ($mode == 'edit')
{
	$s_hidden_fields = '<input type="hidden" name="mode" value="editfield">';
}
elseif ($mode == 'delete')
{
	$s_hidden_fields = '<input type="hidden" name="mode" value="do_delete">';
}

$template->assign_vars(array(
		'L_FIELD_TITLE' => $l_title,
		'L_FIELD_EXPLAIN' => $lang['Fieldexplain'],
		'L_SELECT_TITLE' => $lang['Fieldselecttitle'],

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_FIELD_ACTION' => append_sid('admin_kb_custom.' . PHP_EXT)
		)
	);

if ($mode == 'addfield' || $mode == 'editfield')
{
	if ($field_id)
	{
		$data = $kb_custom_field->get_field_data($field_id);
	}

	$template->assign_vars(array('L_FIELD_NAME' => $lang['Fieldname'],
			'L_FIELD_NAME_INFO' => $lang['Fieldnameinfo'],
			'L_FIELD_DESC' => $lang['Fielddesc'],
			'L_FIELD_DESC_INFO' => $lang['Fielddescinfo'],
			'L_FIELD_DATA' => $lang['Field_data'],
			'L_FIELD_DATA_INFO' => $lang['Field_data_info'],
			'L_FIELD_REGEX' => $lang['Field_regex'],
			'L_FIELD_REGEX_INFO' => sprintf($lang['Field_regex_info'], '<a href="http://www.php.net/manual/en/function.preg-match.php" target="_blank">', '</a>'),
			'L_FIELD_ORDER' => $lang['Field_order'],

			//'DATA' => ($field_type != INPUT && $field_type != TEXTAREA) ? true : false,
			//'REGEX' => ($field_type == INPUT || $field_type == TEXTAREA) ? true : false,
			//'ORDER' => ($field_id) ? true : false,

			'FIELD_NAME' => $data['custom_name'],
			'FIELD_DESC' => $data['custom_description'],
			'FIELD_DATA' => $data['data'],
			'FIELD_REGEX' => $data['regex'],
			'FIELD_ORDER' => $data['field_order']
			)
		);

		if ($field_type != INPUT && $field_type != TEXTAREA)
		{
			$template->assign_block_vars('data', array());
		}
		if ($field_type == INPUT || $field_type == TEXTAREA)
		{
			$template->assign_block_vars('regex', array());
		}
		if ($field_id)
		{
			$template->assign_block_vars('order', array());
		}
}
elseif ($mode == 'add')
{
	$field_types = array(INPUT => $lang['Field_Input'], TEXTAREA => $lang['Field_Textarea'], RADIO => $lang['Field_Radio'], SELECT => $lang['Field_Select'], SELECT_MULTIPLE => $lang['Field_Select_multiple'], CHECKBOX => $lang['Field_Checkbox']);

	$field_type_list = '<select name="field_type">';
	foreach($field_types as $key => $value)
	{
		$field_type_list .= '<option value="' . $key . '">' . $value . '</option>';
	}
	$field_type_list .= '</select>';

	$template->assign_vars(array('S_SELECT_FIELD_TYPE' => $field_type_list)
		);
}
elseif ($mode == 'edit' || $mode == 'delete' || $mode == 'select')
{
	foreach($kb_custom_field->field_rowset as $field_id => $field_data)
	{
		$template->assign_block_vars('field_row', array(
				'FIELD_ID' => $field_id,
				'FIELD_NAME' => $field_data['custom_name'],
				'FIELD_DESC' => $field_data['custom_description'])
			);
	}
}

$template->pparse('admin');

// MX Module
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>