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
	$module['2000_Downloads']['120_File_manage_title'] = $file;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'pafiledb_common.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'includes/functions_field.' . $phpEx);

$custom_field = new custom_field();
$custom_field->init();

$pafiledb->init();

$cat_id = (isset($_REQUEST['cat_id'])) ? intval($_REQUEST['cat_id']) : 0;
$file_id = (isset($_REQUEST['file_id'])) ? intval($_REQUEST['file_id']) : 0;
$file_ids = (isset($_POST['file_ids'])) ? array_map('intval', $_POST['file_ids']) : array();
$start = ( isset($_REQUEST['start']) ) ? intval($_REQUEST['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : '';
$mode_js = (isset($_REQUEST['mode_js'])) ? htmlspecialchars($_REQUEST['mode_js']) : '';
$mode = (isset($_POST['addfile'])) ? 'add' : $mode;
$mode = (isset($_POST['delete'])) ? 'delete' : $mode;
$mode = (isset($_POST['approve'])) ? 'do_approve' : $mode;
$mode = (isset($_POST['unapprove'])) ? 'do_unapprove' : $mode;
$mode = (empty($mode)) ? $mode_js : $mode;

$mirrors = (isset($_POST['mirrors'])) ? TRUE : 0;

if( isset($_REQUEST['sort_method']) )
{
	switch ($_REQUEST['sort_method'])
	{
		case 'file_name':
			$sort_method = 'file_name';
			break;
		case 'file_time':
			$sort_method = 'file_time';
			break;
		case 'file_dls':
			$sort_method = 'file_dls';
			break;
		case 'file_rating':
			$sort_method = 'rating';
			break;
		case 'file_update_time':
			$sort_method = 'file_update_time';
			break;
		default:
			$sort_method = $pafiledb_config['sort_method'];
	}
}
else
{
	$sort_method = $pafiledb_config['sort_method'];
}

if( isset($_REQUEST['sort_order']) )
{
	switch ($_REQUEST['sort_order'])
	{
		case 'ASC':
			$sort_order = 'ASC';
			break;
		case 'DESC':
			$sort_order = 'DESC';
			break;
		default:
			$sort_order = $pafiledb_config['sort_order'];
	}
}
else
{
	$sort_order = $pafiledb_config['sort_order'];
}

$s_file_actions = array('approved' => $lang['Approved_files'],
						'broken' => $lang['Broken_files'],
						'file_cat' => $lang['File_cat'],
						'all_file' => $lang['All_files'],
						'maintenance' => $lang['Maintenance']);




switch($mode)
{
	case '':
	case 'approved':
	case 'broken':
	case 'do_approve':
	case 'do_unapprove':
	case 'delete':
	case 'file_cat':
	case 'all_file':
	default:
		$template_file = ADM_TPL . 'pa_admin_file.tpl';
		$l_title = $lang['File_manage_title'];
		$l_explain = $lang['Fileexplain'];
		//$s_hidden_fields = '<input type="hidden" name="mode" value="add">';
		break;
	case 'add':
		$template_file = ADM_TPL . 'pa_admin_file_edit.tpl';
		$l_title = $lang['Afiletitle'];
		$l_explain = $lang['Fileexplain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="do_add">';
		break;
	case 'edit':
	case 'do_add':
		$template_file = ADM_TPL . 'pa_admin_file_edit.tpl';
		$l_title = $lang['Efiletitle'];
		$l_explain = $lang['Fileexplain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="do_add">';
		$s_hidden_fields .= '<input type="hidden" name="file_id" value="' . $file_id . '">';
		break;
	case 'maintenance':
		$template_file = ADM_TPL . 'pa_admin_file_checker.tpl';
		$l_title = $lang['File_checker'];
		$l_explain = $lang['File_checker_explain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="do_maintenace">';
		break;
	case 'mirrors':
		$template_file = ADM_TPL . 'pa_admin_file_mirrors.tpl';
		$l_title = $lang['Mirrors'];
		$l_explain = $lang['Mirrors_explain'];
		$s_hidden_fields = '<input type="hidden" name="mode" value="mirrors">';
		$s_hidden_fields .= '<input type="hidden" name="file_id" value="' . $file_id . '">';
		break;
}

if($mode == 'do_add' && !$file_id)
{
	$file_id = $pafiledb->update_add_file();
	$custom_field->file_update_data($file_id);
	$pafiledb->_pafiledb();
	$mode = 'edit';
	if(!$mirrors)
	{
		$message = $lang['Fileadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_pa_file.php") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif($mode == 'do_add' && $file_id)
{
	$file_id = $pafiledb->update_add_file($file_id);
	$custom_field->file_update_data($file_id);
	$pafiledb->_pafiledb();
	$mode = 'edit';
	if(!$mirrors)
	{
		$message = $lang['Fileedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_pa_file.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif($mode == 'delete')
{
	if(is_array($file_ids) && !empty($file_ids))
	{
		foreach($file_ids as $temp_file_id)
		{
			$pafiledb->delete_files($temp_file_id);
		}
	}
	else
	{
		$pafiledb->delete_files($file_id);
	}
	$pafiledb->_pafiledb();
}
elseif($mode == 'do_maintenace')
{
	$pafiledb->file_mainenance();
}
elseif($mode == 'do_approve' || $mode == 'do_unapprove')
{
	if(is_array($file_ids) && !empty($file_ids))
	{
		foreach($file_ids as $temp_file_id)
		{
			$pafiledb->file_approve($mode, $temp_file_id);
		}
	}
	else
	{
		$pafiledb->file_approve($mode, $file_id);
	}
	$pafiledb->_pafiledb();
}

$pafiledb_template->set_filenames(array(
	'admin' => $template_file)
);

$pafiledb_template->assign_vars(array(
	'L_FILE_TITLE' => $l_title,
	'L_FILE_EXPLAIN' => $l_explain,
	'L_ADD_FILE' => $lang['Afiletitle'],

	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_FILE_ACTION' => append_sid("admin_pa_file.$phpEx"))
);


if(in_array($mode, array('', 'approved', 'broken', 'do_approve', 'do_unapprove', 'delete', 'file_cat', 'all_file')))
{
	$mode = (in_array($mode, array('do_approve', 'do_unapprove', 'delete'))) ? '' : $mode;

	if($mode != 'approved' && $mode != 'broken')
	{
		$where_sql = ($mode == 'file_cat') ? "AND file_catid = '$cat_id'" : '';
		$sql = "SELECT file_name, file_approved, file_id, file_broken
			FROM " . PA_FILES_TABLE . " as f1
			WHERE file_approved = '1'
			$where_sql
			ORDER BY file_time DESC";

			if($mode == '' || $mode == 'file_cat' || $mode == 'all_file')
			{
				if( (!$result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
				}

				$total_files = $db->sql_numrows($result);
			}

		if ( !($result = $pafiledb_functions->sql_query_limit($sql, $pafiledb_config['settings_file_page'], $start)) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
		}
		while($row = $db->sql_fetchrow($result))
		{
			$all_file_rowset[] = $row;
		}
	}


	if($mode == '' || $mode == 'approved' || $mode == 'broken' || $mode == 'file_cat' || $mode == 'all_file')
	{
		if($mode == '')
		{
			$limit = 5;
			$temp_start = 0;
		}
		else
		{
			$limit = $pafiledb_config['settings_file_page'];
			$temp_start = $start;
		}

		if($mode == '' || $mode == 'approved')
		{
			$sql = "SELECT file_name, file_approved, file_id, file_broken
				FROM " . PA_FILES_TABLE . "
				WHERE file_approved = '0'
				ORDER BY file_time DESC";

			if($mode == 'approved')
			{
				if( (!$result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
				}

				$total_files = $db->sql_numrows($result);
			}

			if ( !($result = $pafiledb_functions->sql_query_limit($sql, $limit, $temp_start)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				$approved_file_rowset[] = $row;
			}
		}

		if($mode == '' || $mode == 'broken')
		{
			$sql = "SELECT file_name, file_approved, file_id, file_broken
				FROM " . PA_FILES_TABLE . "
				WHERE file_broken = '1'
				ORDER BY file_time DESC";

			if($mode == 'broken')
			{
				if( (!$result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
				}

				$total_files = $db->sql_numrows($result);
			}

			if ( !($result = $pafiledb_functions->sql_query_limit($sql, $limit, $temp_start)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				$broken_file_rowset[] = $row;
			}
		}

		if($mode == '')
		{
			$global_array = array(0 => array('lang_var' => $lang['Approved_files'],
				 							 'row_set' => $approved_file_rowset,
											 'approval' => 'approve'),
		   						  1 => array('lang_var' => $lang['Broken_files'],
											 'row_set' => $broken_file_rowset,
 											 'approval' => 'both'),
								  2 => array('lang_var' => $lang['All_files'],
											 'row_set' => $all_file_rowset,
											 'approval' => 'unapprove'));
		}
		elseif($mode == 'all_file' || $mode == 'file_cat')
		{
			$global_array = array(0 => array('lang_var' => $lang['All_files'],
											 'row_set' => $all_file_rowset,
											 'approval' => 'unapprove'));
		}
		elseif($mode == 'approved')
		{
			$global_array = array(0 => array('lang_var' => $lang['Approved_files'],
											 'row_set' => $approved_file_rowset,
											 'approval' => 'approve'));
		}
		elseif($mode == 'broken')
		{
			$global_array = array(0 => array('lang_var' => $lang['Broken_files'],
											 'row_set' => $broken_file_rowset,
											 'approval' => 'both'));
		}
	}

	$s_file_list = '';
	foreach($s_file_actions as $file_mode => $lang_var)
	{
		$s = '';
		if($mode == $file_mode)
		{
			$s = ' selected="selected"';
		}
		$s_file_list .= '<option value="' . $file_mode . '"' . $s . '>' . $lang_var . '</option>';
	}

	$cat_list = '<select name="cat_id">';
	if (!$pafiledb->cat_rowset[$cat_id]['cat_parent'])
	{
		$cat_list .= '<option value="0" selected>' . $lang['None'] . '</option>\n';
	}
	else
	{
		$cat_list .= '<option value="0">' . $lang['None'] . '</option>\n';
	}
	$cat_list .= $pafiledb->jumpmenu_option(0, 0, array($cat_id => 1), true);
	$cat_list .= '</select>';

	$pafiledb_template->assign_vars(array(
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_CATEGORY' => $lang['Category'],
		'L_MODE' => $lang['View'],
		'L_GO' => $lang['Go'],
		'L_DELETE_FILE' => $lang['Delete_selected'],
		'L_APPROVE' => $lang['Approve'],
		'L_UNAPPROVE' => $lang['Unapprove'],
		'L_APPROVE_FILE' => $lang['Approve_selected'],
		'L_UNAPPROVE_FILE' => $lang['Unapprove_selected'],
		'L_NO_FILES' => $lang['No_file'],

		'PAGINATION' => generate_pagination(append_sid("admin_pa_file.$phpEx?mode=$mode&amp;sort_method=$sort_method&amp;sort_order=$sort_order&cat_id=$cat_id"), $total_files, $pafiledb_config['settings_file_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pafiledb_config['settings_file_page'] ) + 1 ), ceil( $total_files / $pafiledb_config['settings_file_page'] )),

		'S_CAT_LIST' => $cat_list,
		'S_MODE_SELECT' => $s_file_list)
	);

	foreach($global_array as $files_data)
	{
		$approve = false;
		$unapprove = false;
		if($files_data['approval'] == 'both')
		{
			$approve = $unapprove = true;
		}
		elseif($files_data['approval'] == 'approve')
		{
			$approve = true;
		}
		elseif($files_data['approval'] == 'unapprove')
		{
			$unapprove = true;
		}

		$pafiledb_template->assign_block_vars('file_mode', array(
			'L_FILE_MODE' => $files_data['lang_var'],
			'DATA' => (isset($files_data['row_set'])) ? TRUE : FALSE,
			'APPROVE' => $approve,
			'UNAPPROVE' => $unapprove)
		);

		if(isset($files_data['row_set']))
		{
			$i = $start + 1;
			foreach($files_data['row_set'] as $file_data)
			{
				$approve_mode = ($file_data['file_approved']) ? 'do_unapprove' : 'do_approve';
				$pafiledb_template->assign_block_vars('file_mode.file_row', array(
					'FILE_NAME' => $file_data['file_name'],
					'FILE_NUMBER' => $i++,
					'FILE_ID' => $file_data['file_id'],
					'U_FILE_EDIT' => append_sid("admin_pa_file.$phpEx?mode=edit&file_id={$file_data['file_id']}"),
					'U_FILE_DELETE' => append_sid("admin_pa_file.$phpEx?mode=delete&file_id={$file_data['file_id']}"),
					'U_FILE_APPROVE' => append_sid("admin_pa_file.$phpEx?mode=$approve_mode&file_id={$file_data['file_id']}"),
					'L_APPROVE' => ($file_data['file_approved']) ? $lang['Unapprove'] : $lang['Approve'])
				);

			}
		}
	}
}
elseif($mode == 'add' || $mode == 'edit' || $mirrors)
{
	if($mode == 'add')
	{
		$file_name = '';
		$file_desc = '';
		$file_long_desc = '';
		$file_author = '';
		$file_version = '';
		$file_website = '';
		$file_posticons = $pafiledb_functions->post_icons();
		$file_cat_list = $pafiledb->jumpmenu_option(0, 0, '', true);
		$file_license = $pafiledb_functions->license_list();
		$pin_checked_yes = '';
		$pin_checked_no = ' checked';
		$file_download = 0;
		$approved_checked_yes = ' checked';
		$approved_checked_no = '';
		$file_ssurl = '';
		$ss_checked_yes = '';
		$ss_checked_no = ' checked';
		$file_url = '';
		$custom_exist = $custom_field->display_edit();
	}
	else
	{
		$sql = 'SELECT *
			FROM ' . PA_FILES_TABLE . "
			WHERE file_id = $file_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
		}
		$file_info = $db->sql_fetchrow($result);

		$file_name = $file_info['file_name'];
		$file_desc = $file_info['file_desc'];
		$file_long_desc = $file_info['file_longdesc'];
		$file_author = $file_info['file_creator'];
		$file_version = $file_info['file_version'];
		$file_website = $file_info['file_docsurl'];
		$file_posticons = $pafiledb_functions->post_icons($file_info['file_posticon']);
		$file_cat_list = $pafiledb->jumpmenu_option(0, 0, array($file_info['file_catid'] => 1), true);
		$file_license = $pafiledb_functions->license_list($file_info['file_license']);
		$pin_checked_yes = ($file_info['file_pin']) ? ' checked' : '';
		$pin_checked_no = (!$file_info['file_pin']) ? ' checked' : '';
		$file_download = intval($file_info['file_dls']);
		$approved_checked_yes = ($file_info['file_approved']) ? ' checked' : '';
		$approved_checked_no = (!$file_info['file_approved']) ? ' checked' : '';
		$file_ssurl = $file_info['file_ssurl'];
		$ss_checked_yes = ($file_info['file_sshot_link']) ? ' checked' : '';
		$ss_checked_no = (!$file_info['file_sshot_link']) ? ' checked' : '';
		$file_url = $file_info['file_dlurl'];
		$file_unique_name = $file_info['unique_name'];
		$file_dir = $file_info['file_dir'];
		$custom_exist = $custom_field->display_edit($file_id);
	}

	$pafiledb_template->assign_vars(array(
		'U_MIRRORS_PAGE' => append_sid("admin_pa_file.$phpEx?mode=mirrors&file_id=$file_id"),

		'ADD_MIRRORS' => $mirrors,
		'MODE_EDIT' => ($mode == 'edit') ? true : false,
		'MODE' => $mode,
		'FILESIZE' => intval($pafiledb_config['max_file_size']),
		'FILE_NAME' => $file_name,
		'FILE_DESC' => $file_desc,
		'FILE_LONG_DESC' => $file_long_desc,
		'FILE_AUTHOR' => $file_author,
		'FILE_VERSION' => $file_version,
		'FILE_SSURL' => $file_ssurl,
		'FILE_WEBSITE' => $file_website,
		'FILE_DLURL' => $file_url,
		'FILE_DOWNLOAD' => $file_download,
		'CUSTOM_EXIST' => $custom_exist,
		'APPROVED_CHECKED_YES' => $approved_checked_yes,
		'APPROVED_CHECKED_NO' => $approved_checked_no,
		'SS_CHECKED_YES' => $ss_checked_yes,
		'SS_CHECKED_NO' => $ss_checked_no,
		'PIN_CHECKED_YES' => $pin_checked_yes,
		'PIN_CHECKED_NO' => $pin_checked_no,
		'MIRROR_FILE' => $file_unique_name,
		'U_UPLOADED_MIRROR' => get_formated_url() . '/' . $file_dir . $file_unique_name,

		'L_FILE_APPROVED' => $lang['Approved'],
		'L_FILE_APPROVED_INFO' => $lang['Approved_info'],
		'L_ADDTIONAL_FIELD' => $lang['Addtional_field'],
		'L_SCREENSHOT' => $lang['Scrsht'],
		'L_FILES' => $lang['Files'],
		'L_FILE_NAME' => $lang['Filename'],
		'L_FILE_NAME_INFO' => $lang['Filenameinfo'],
		'L_FILE_SHORT_DESC' => $lang['Filesd'],
		'L_FILE_SHORT_DESC_INFO' => $lang['Filesdinfo'],
		'L_FILE_LONG_DESC' => $lang['Fileld'],
		'L_FILE_LONG_DESC_INFO' => $lang['Fileldinfo'],
		'L_FILE_AUTHOR' => $lang['Filecreator'],
		'L_FILE_AUTHOR_INFO' => $lang['Filecreatorinfo'],
		'L_FILE_VERSION' => $lang['Fileversion'],
		'L_FILE_VERSION_INFO' => $lang['Fileversioninfo'],
		'L_FILESS' => $lang['Filess'],
		'L_FILESSINFO' => $lang['Filessinfo'],
		'L_FILESS_UPLOAD' => $lang['Filess_upload'],
		'L_FILESSINFO_UPLOAD' => $lang['Filessinfo_upload'],
		'L_FILE_SSLINK' => $lang['Filess_link'],
		'L_FILE_SSLINK_INFO' => $lang['Filess_link_info'],
		'L_FILESSUPLOAD' => $lang['Filessupload'],
		'L_FILE_WEBSITE' => $lang['Filedocs'],
		'L_FILE_WEBSITE_INFO' => $lang['Filedocsinfo'],
		'L_FILE_URL' => $lang['Fileurl'],
		'L_FILE_UPLOAD' => $lang['File_upload'],
		'L_FILEINFO_UPLOAD' => $lang['Fileinfo_upload'],
		'L_FILE_URL_INFO' => $lang['Fileurlinfo'],
		'L_FILE_POSTICONS' => $lang['Filepi'],
		'L_FILE_POSTICONS_INFO' => $lang['Filepiinfo'],
		'L_FILE_CAT' => $lang['Filecat'],
		'L_FILE_CAT_INFO' => $lang['Filecatinfo'],
		'L_FILE_LICENSE' => $lang['Filelicense'],
		'L_NONE' => $lang['None'],
		'L_FILE_LICENSE_INFO' => $lang['Filelicenseinfo'],
		'L_FILE_PINNED' => $lang['Filepin'],
		'L_FILE_PINNED_INFO' => $lang['Filepininfo'],
		'L_FILE_DOWNLOAD' => $lang['Filedls'],
		'L_MIRRORS' => $lang['Mirrors'],
		'L_MIRRORS_INFO' => $lang['Mirrors_explain'],
		'L_CLICK_HERE_MIRRORS' => $lang['Click_here_mirrors'],
		'L_UPLOADED_FILE' => $lang['Uploaded_file'],
		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],

		'S_POSTICONS' => $file_posticons,
		'S_LICENSE_LIST' => $file_license,
		'S_CAT_LIST' => $file_cat_list)
	);
}
elseif($mode == 'mirrors')
{
	if(isset($_POST['delete_mirrors']))
	{
		$mirror_ids = (isset($_POST['mirror_ids'])) ? array_map('intval', $_POST['mirror_ids']) : array();

		if(!empty($mirror_ids))
		{
			$pafiledb->delete_mirror($mirror_ids);
		}
	}
	if(isset($_POST['add_new']))
	{
		$file_upload = ( empty($_POST['new_download_url']) ) ? TRUE : FALSE;
		$file_remote_url = (!empty($_POST['new_download_url'])) ? $_POST['new_download_url'] : '';
		$file_local = ( $_FILES['new_userfile']['tmp_name'] !== 'none') ? $_FILES['new_userfile']['tmp_name'] : '';
		$file_realname = ( $_FILES['new_userfile']['name'] !== 'none' ) ? $_FILES['new_userfile']['name'] : '';
		$file_size = ( !empty($_FILES['new_userfile']['size']) ) ? $_FILES['new_userfile']['size'] : '';
		$file_type = ( !empty($_FILES['new_userfile']['type']) ) ? $_FILES['new_userfile']['type'] : '';
		$mirror_location = (!empty($_POST['new_location'])) ? $_POST['new_location'] : '';

		$pafiledb->mirror_add_update($file_id, $file_upload, $file_remote_url, $file_local, $file_realname, $file_size, $file_type, $mirror_location);
	}

	if(isset($_POST['modify']))
	{
		$file_urls = (!empty($_POST['download_url'])) ? $_POST['download_url'] : array();
		$userfiles = (!empty($_FILES['userfile'])) ? $_FILES['userfile'] : array();
		$locations = (!empty($_POST['location'])) ? $_POST['location'] : array();

		$data = array();

		foreach($file_urls as $mirror_id => $file_url)
		{
			$data[$mirror_id]['download_url'] = $file_url;
		}

		foreach(array_keys($userfiles) as $key)
		{
			foreach($userfiles[$key] as $mirror_id => $userfile)
			{
				$data[$mirror_id][$key] = $userfile;
			}
		}

		foreach($locations as $mirror_id => $location)
		{
			$data[$mirror_id]['location'] = $location;
		}


		unset($file_urls);
		unset($userfiles);
		unset($locations);

		foreach($data as $mirror_id => $mirror_data)
		{
			$file_upload = ( empty($mirror_data['download_url']) ) ? TRUE : FALSE;
			$file_remote_url = (!empty($mirror_data['download_url'])) ? $mirror_data['download_url'] : '';
			$file_local = ( $mirror_data['tmp_name'] !== 'none') ? $mirror_data['tmp_name'] : '';
			$file_realname = ( $mirror_data['name'] !== 'none' ) ? $mirror_data['name'] : '';
			$file_size = ( !empty($mirror_data['size']) ) ? $mirror_data['size'] : '';
			$file_type = ( !empty($mirror_data['type']) ) ? $mirror_data['type'] : '';

			$mirror_location = (!empty($mirror_data['location'])) ? $mirror_data['location'] : '';

			$pafiledb->mirror_add_update($file_id, $file_upload, $file_remote_url, $file_local, $file_realname, $file_size, $file_type, $mirror_location, $mirror_id);
		}

		unset($data);
	}

	$sql = 'SELECT f.*
		FROM ' . PA_MIRRORS_TABLE . " AS f
		WHERE f.file_id = '" . $file_id . "'
		ORDER BY mirror_id";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt select download', '', __LINE__, __FILE__, $sql);
	}

	$mirrors_data = array();
	while($row = $db->sql_fetchrow($result))
	{
		$mirrors_data[$row['mirror_id']] = $row;
	}

	$pafiledb_template->assign_vars(array(
		'ROW_NOT_EMPTY' => (empty($mirrors_data)) ? FALSE : TRUE,
		'FILESIZE' => intval($pafiledb_config['max_file_size']),

		'L_MIRROR_LOCATION' => $lang['Mirror_location'],
		'L_FILE_UPLOAD' => $lang['File_upload'],
		'L_FILE_DELETE' => $lang['Delete'],
		'L_DELETE' => $lang['Delete_selected'],
		'L_FILEINFO_UPLOAD' => $lang['Fileinfo_upload'],
		'L_UPLOADED_FILE' => $lang['Uploaded_file'],
		'L_FILE_URL' => $lang['Fileurl'],
		'L_FILE_URL_INFO' => $lang['Fileurl'],
		'L_MODIFY' => $lang['Efiletitle'],
		'L_ADD_NEW' => $lang['Afiletitle'],
		'L_ADD_NEW_MIRROR' => $lang['Add_new_mirror'])
	);

	foreach($mirrors_data as $mirror_id => $mirror_data)
	{
		$pafiledb_template->assign_block_vars('row', array(
			'LOCATION' => $mirror_data['mirror_location'],
			'MIRROR_ID' => $mirror_id,
			'MIRROR_URL' => $mirror_data['file_dlurl'],
			'MIRROR_FILE' => $mirror_data['unique_name'],
			'U_UPLOADED_MIRROR' => get_formated_url() . '/' . $mirror_data['file_dir'] . $mirror_data['unique_name'])
		);
	}
}

$pafiledb_template->assign_vars(array(
	'ERROR' => (count($pafiledb->error)) ? implode('<br />', $pafiledb->error) : ''
	)
);

$pafiledb_template->display('admin');

$pafiledb->_pafiledb();
$cache->unload();

include('./page_footer_admin.' . $phpEx);

?>