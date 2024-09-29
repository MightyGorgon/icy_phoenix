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


if(defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$file = basename(__FILE__);
	$module['2000_Downloads']['110_Cat_manage_title'] = $file;
	return;
}
define('IN_ICYPHOENIX', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/pafiledb_common.' . PHP_EXT);

$pafiledb->init();

$mode = request_var('mode', '');
$cat_id = request_var('cat_id', 0);
$cat_id_other = request_var('cat_id_other', 0);

if($mode == 'do_add')
{
	if (!empty($cat_id))
	{
		$lang_var = $lang['Catedited'];
	}
	else
	{
		$lang_var = $lang['Catadded'];
		$mode = 'add';
	}

	$cat_id = $pafiledb->update_add_cat($cat_id);
	if(!sizeof($pafiledb->error))
	{
		$pafiledb->_pafiledb();
		$message = $lang_var . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_category.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_edit_permissions'], '<a href="' . append_sid('admin_pa_catauth.' . PHP_EXT . '?cat_id=' . $cat_id) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif($mode == 'do_delete')
{
	$pafiledb->delete_cat($cat_id);
	if(!sizeof($pafiledb->error))
	{
		$pafiledb->_pafiledb();
		$message = $lang['Catsdeleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_category.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
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

$template->set_filenames(array('admin' => $template_file));

$template->assign_vars(array(
	'L_CAT_TITLE' => $l_title,
	'L_CAT_EXPLAIN' => $l_explain,
	'ERROR' => (sizeof($pafiledb->error)) ? implode('<br />', $pafiledb->error) : '',

	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_CAT_ACTION' => append_sid('admin_pa_category.' . PHP_EXT)
	)
);

if(empty($mode) || ($mode == 'cat_order') || ($mode == 'sync') || ($mode == 'sync_all'))
{
	$template->assign_vars(array(
		'L_CREATE_CATEGORY' => $lang['Create_category'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVE_UP' => $lang['MOVE_UP'],
		'L_MOVE_DOWN' => $lang['MOVE_DOWN'],
		'L_SUB_CAT' => $lang['Sub_category'],
		'L_RESYNC' => $lang['RESYNC']
		)
	);
	admin_cat_main($cat_id);
}
elseif(($mode == 'add') || ($mode == 'edit'))
{
	if($mode == 'add')
	{
		if(!$_POST['cat_parent'])
		{
			$cat_list .= '<option value="0" selected="selected">' . $lang['None'] . '</option>';
		}
		else
		{
			$cat_list .= '<option value="0">' . $lang['None'] . '</option>';
		}
		$cat_list .= (!$_POST['cat_parent']) ? $pafiledb->jumpmenu_option() : $pafiledb->jumpmenu_option(0, 0, array($_POST['cat_parent'] => 1));
		$checked_yes = ($_POST['cat_allow_file']) ? ' checked="checked"' : '';
		$checked_no = (!$_POST['cat_allow_file']) ? ' checked="checked"' : '';
		// MX Addon
		$checked_comments_yes = ($_POST['cat_allow_comments']) ? ' checked="checked"' : '';
		$checked_comments_no = (!$_POST['cat_allow_comments']) ? ' checked="checked"' : '';
		$checked_ratings_yes = ($_POST['cat_allow_ratings']) ? ' checked="checked"' : '';
		$checked_ratings_no = (!$_POST['cat_allow_ratings']) ? ' checked="checked"' : '';
		// End
		$cat_name = (!empty($_POST['cat_name'])) ? $_POST['cat_name'] : '';
		$cat_desc = (!empty($_POST['cat_desc'])) ? $_POST['cat_desc'] : '';
	}
	else
	{
		if (!$pafiledb->cat_rowset[$cat_id]['cat_parent'])
		{
			$cat_list .= '<option value="0" selected="selected">' . $lang['None'] . '</option>\n';
		}
		else
		{
			$cat_list .= '<option value="0">' . $lang['None'] . '</option>\n';
		}
		$cat_list .= $pafiledb->jumpmenu_option(0, 0, array($pafiledb->cat_rowset[$cat_id]['cat_parent'] => 1));

		if($pafiledb->cat_rowset[$cat_id]['cat_allow_file'])
		{
			$checked_yes = ' checked="checked"';
			$checked_no = '';
		}
		else
		{
			$checked_yes = '';
			$checked_no = ' checked="checked"';
		}

		if($pafiledb->cat_rowset[$cat_id]['cat_allow_comments'])
		{
			$checked_comments_yes = ' checked="checked"';
			$checked_comments_no = '';
		}
		else
		{
			$checked_comments_yes = '';
			$checked_comments_no = ' checked="checked"';
		}

		if($pafiledb->cat_rowset[$cat_id]['cat_allow_ratings'])
		{
			$checked_ratings_yes = ' checked="checked"';
			$checked_ratings_no = '';
		}
		else
		{
			$checked_ratings_yes = '';
			$checked_ratings_no = ' checked="checked"';
		}

		$cat_name = $pafiledb->cat_rowset[$cat_id]['cat_name'];
		$cat_desc = $pafiledb->cat_rowset[$cat_id]['cat_desc'];
	}

	$template->assign_vars(array(
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
		'S_CAT_LIST' => $cat_list
		)
	);
}
elseif($mode == 'delete')
{
	$select_cat = $pafiledb->jumpmenu_option(0, 0, array($cat_id => 1));
	$file_to_select_cat = $pafiledb->jumpmenu_option(0, 0, '', true);

	$template->assign_vars(array(
		'S_SELECT_CAT' => $select_cat,
		'S_FILE_SELECT_CAT' => $file_to_select_cat,

		'L_DELETE'=> $lang['Delete'],
		'L_DO_FILE' => $lang['Delfiles'],
		'L_DO_CAT' => $lang['Do_cat'],
		'L_MOVE_TO' => $lang['Move_to'],
		'L_SELECT_CAT' => $lang['Select_a_Category'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVE' => $lang['Move']
		)
	);
}

$template->display('admin');

$pafiledb->_pafiledb();

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>