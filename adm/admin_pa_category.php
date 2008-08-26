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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['2000_Downloads']['110_Cat_manage_title'] = $file;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'pafiledb_common.' . $phpEx);

$pafiledb->init();

$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : '';
$cat_id = (isset($_REQUEST['cat_id'])) ? intval($_REQUEST['cat_id']) : 0;
$cat_id_other = (isset($_REQUEST['cat_id_other'])) ? intval($_REQUEST['cat_id_other']) : 0;

if($mode == 'do_add' && !$cat_id)
{
	$cat_id = $pafiledb->update_add_cat();
	$mode = 'add';
	if(!count($pafiledb->error))
	{
		$pafiledb->_pafiledb();
		$message = $lang['Catadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_category.' . $phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_edit_permissions'], '<a href="' . append_sid('admin_pa_catauth.' . $phpEx . '?cat_id=' . $cat_id) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif($mode == 'do_add' && $cat_id)
{
	$cat_id = $pafiledb->update_add_cat($cat_id);
	if(!count($pafiledb->error))
	{
		$pafiledb->_pafiledb();
		$message = $lang['Catedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_category.' . $phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_edit_permissions'], '<a href="' . append_sid('admin_pa_catauth.' . $phpEx . '?cat_id=' . $cat_id) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif($mode == 'do_delete')
{
	$pafiledb->delete_cat($cat_id);
	if(!count($pafiledb->error))
	{
		$pafiledb->_pafiledb();
		$message = $lang['Catsdeleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_category.' . $phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif($mode == 'cat_order')
{
	$pafiledb->order_cat($cat_id_other);
}
elseif($mode == 'sync')
{
	$pafiledb->sync($cat_id_other);
}
elseif($mode == 'sync_all')
{
	$pafiledb->sync_all();
}

switch($mode)
{
	case '':
	case 'cat_order':
	case 'sync':
	default:
		$template_file = ADM_TPL . 'pa_admin_cat.tpl';
		$l_title = $lang['Cat_manage_title'];
		$l_explain = $lang['Catexplain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="add" />';
		break;
	case 'add':
		$template_file = ADM_TPL . 'pa_admin_cat_edit.tpl';
		$l_title = $lang['Acattitle'];
		$l_explain = $lang['Catexplain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="do_add" />';
		break;
	case 'edit':
		$template_file = ADM_TPL . 'pa_admin_cat_edit.tpl';
		$l_title = $lang['Ecattitle'];
		$l_explain = $lang['Catexplain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="do_add" />';
		$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		break;
	case 'delete':
		$template_file = ADM_TPL . 'pa_admin_cat_delete.tpl';
		$l_title = $lang['Dcattitle'];
		$l_explain = $lang['Catexplain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="do_delete" />';
		break;
}

$pafiledb_template->set_filenames(array('admin' => $template_file));

$pafiledb_template->assign_vars(array(
	'L_CAT_TITLE' => $l_title,
	'L_CAT_EXPLAIN' => $l_explain,
	'ERROR' => (count($pafiledb->error)) ? implode('<br />', $pafiledb->error) : '',

	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_CAT_ACTION' => append_sid('admin_pa_category.' . $phpEx)
	)
);

if($mode == '' || $mode == 'cat_order' || $mode == 'sync' || $mode == 'sync_all')
{
	$pafiledb_template->assign_vars(array(
		'L_CREATE_CATEGORY' => $lang['Create_category'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVE_UP' => $lang['Move_up'],
		'L_MOVE_DOWN' => $lang['Move_down'],
		'L_SUB_CAT' => $lang['Sub_category'],
		'L_RESYNC' => $lang['Resync'])
	);
	admin_cat_main($cat_id);
}
elseif($mode == 'add' || $mode == 'edit')
{
	if($mode == 'add')
	{
		if(!$_POST['cat_parent'])
		{
			$cat_list .= '<option value="0" selected>' . $lang['None'] . '</option>';
		}
		else
		{
			$cat_list .= '<option value="0">' . $lang['None'] . '</option>';
		}
		$cat_list .= (!$_POST['cat_parent']) ? $pafiledb->jumpmenu_option() : $pafiledb->jumpmenu_option(0, 0, array($_POST['cat_parent'] => 1));
		$checked_yes = ($_POST['cat_allow_file']) ? ' checked' : '';
		$checked_no = (!$_POST['cat_allow_file']) ? ' checked' : '';
		// MX Addon
		$checked_comments_yes = ($_POST['cat_allow_comments']) ? ' checked' : '';
		$checked_comments_no = (!$_POST['cat_allow_comments']) ? ' checked' : '';
		$checked_ratings_yes = ($_POST['cat_allow_ratings']) ? ' checked' : '';
		$checked_ratings_no = (!$_POST['cat_allow_ratings']) ? ' checked' : '';
		// End
		$cat_name = (!empty($_POST['cat_name'])) ? $_POST['cat_name'] : '';
		$cat_desc = (!empty($_POST['cat_desc'])) ? $_POST['cat_desc'] : '';
	}
	else
	{
		if (!$pafiledb->cat_rowset[$cat_id]['cat_parent'])
		{
			$cat_list .= '<option value="0" selected>' . $lang['None'] . '</option>\n';
		}
		else
		{
			$cat_list .= '<option value="0">' . $lang['None'] . '</option>\n';
		}
		$cat_list .= $pafiledb->jumpmenu_option(0, 0, array($pafiledb->cat_rowset[$cat_id]['cat_parent'] => 1));

		if($pafiledb->cat_rowset[$cat_id]['cat_allow_file'])
		{
			$checked_yes = ' checked';
			$checked_no = '';
		}
		else
		{
			$checked_yes = '';
			$checked_no = ' checked';
		}

		if($pafiledb->cat_rowset[$cat_id]['cat_allow_comments'])
		{
			$checked_comments_yes = ' checked';
			$checked_comments_no = '';
		}
		else
		{
			$checked_comments_yes = '';
			$checked_comments_no = ' checked';
		}

		if($pafiledb->cat_rowset[$cat_id]['cat_allow_ratings'])
		{
			$checked_ratings_yes = ' checked';
			$checked_ratings_no = '';
		}
		else
		{
			$checked_ratings_yes = '';
			$checked_ratings_no = ' checked';
		}

		$cat_name = $pafiledb->cat_rowset[$cat_id]['cat_name'];
		$cat_desc = $pafiledb->cat_rowset[$cat_id]['cat_desc'];
	}

	$pafiledb_template->assign_vars(array(
		'CAT_NAME' => $cat_name,
		'CAT_DESC' => $cat_desc,
		'CHECKED_YES' => $checked_yes,
		'CHECKED_NO' => $checked_no,

		'CHECKED_ALLOWCOMMENTS_YES' => $checked_comments_yes,
		'CHECKED_ALLOWCOMMENTS_NO' => $checked_comments_no,
		'CHECKED_ALLOWRATINGS_YES' => $checked_ratings_yes,
		'CHECKED_ALLOWRATINGS_NO' => $checked_ratings_no,

		'L_CAT_NAME' => $lang['Catname'],
		'L_CAT_NAME_INFO' => $lang['Catnameinfo'],
		'L_CAT_DESC' => $lang['Catdesc'],
		'L_CAT_DESC_INFO' => $lang['Catdescinfo'],
		'L_CAT_PARENT' => $lang['Catparent'],
		'L_CAT_PARENT_INFO' => $lang['Catparentinfo'],
		'L_CAT_ALLOWFILE' => $lang['Allow_file'],
		'L_CAT_ALLOWFILE_INFO' => $lang['Allow_file_info'],

		'L_CAT_ALLOWCOMMENTS' => $lang['Allow_comments'],
		'L_CAT_ALLOWCOMMENTS_INFO' => $lang['Allow_comments_info'],
		'L_CAT_ALLOWRATINGS' => $lang['Allow_ratings'],
		'L_CAT_ALLOWRATINGS_INFO' => $lang['Allow_ratings_info'],

		'L_NONE' => $lang['None'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_CAT_NAME_FIELD_EMPTY' => $lang['Cat_name_missing'],
		'S_CAT_LIST' => $cat_list)
	);
}
elseif($mode == 'delete')
{
	$select_cat = $pafiledb->jumpmenu_option(0, 0, array($cat_id => 1));
	$file_to_select_cat = $pafiledb->jumpmenu_option(0, 0, '', true);

	$pafiledb_template->assign_vars(array(
		'S_SELECT_CAT' => $select_cat,
		'S_FILE_SELECT_CAT' => $file_to_select_cat,

		'L_DELETE'=> $lang['Delete'],
		'L_DO_FILE' => $lang['Delfiles'],
		'L_DO_CAT' => $lang['Do_cat'],
		'L_MOVE_TO' => $lang['Move_to'],
		'L_SELECT_CAT' => $lang['Select_a_Category'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVE' => $lang['Move'])
	);
}

$pafiledb_template->display('admin');

$pafiledb->_pafiledb();
$cache->unload();

include('./page_footer_admin.' . $phpEx);

function admin_cat_main($cat_parent = 0, $depth = 0)
{
	global $pafiledb, $phpbb_root_path, $pafiledb_template, $phpEx;

	$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
	if(isset($pafiledb->subcat_rowset[$cat_parent]))
	{
		foreach($pafiledb->subcat_rowset[$cat_parent] as $subcat_id => $cat_data)
		{
			$pafiledb_template->assign_block_vars('cat_row', array(
				'IS_HIGHER_CAT' => ($cat_data['cat_allow_file'] == PA_CAT_ALLOW_FILE) ? false : true,
				'U_CAT' => append_sid('admin_pa_category.php?cat_id=' . $subcat_id),
				'U_CAT_EDIT' => append_sid('admin_pa_category.' . $phpEx . '?mode=edit&amp;cat_id=' . $subcat_id),
				'U_CAT_DELETE' => append_sid('admin_pa_category.' . $phpEx . '?mode=delete&amp;cat_id=' . $subcat_id),
				'U_CAT_MOVE_UP' => append_sid('admin_pa_category.' . $phpEx . '?mode=cat_order&amp;move=-15&amp;cat_id_other=' . $subcat_id),
				'U_CAT_MOVE_DOWN' => append_sid('admin_pa_category.' . $phpEx . '?mode=cat_order&amp;move=15&amp;cat_id_other=' . $subcat_id),
				'U_CAT_RESYNC' => append_sid('admin_pa_category.' . $phpEx . '?mode=sync&amp;cat_id_other=' . $subcat_id),
				'CAT_NAME' => $cat_data['cat_name'],
				'PRE' => $pre
				)
			);
			admin_cat_main($subcat_id, $depth + 1);
		}
		return;
	}
	return;
}
?>