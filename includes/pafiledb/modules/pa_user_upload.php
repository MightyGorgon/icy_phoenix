<?php
/*
  paFileDB 3.0
  ©2001/2002 PHP Arena
  Written by Todd
  todd@phparena.net
  http://www.phparena.net
  Keep all copyright links on the script visible
  Please read the license included with this script for more information.
*/

class pafiledb_user_upload extends pafiledb_public
{
	function main($action)
	{
		global $pafiledb_config;
		global $pafiledb_template, $db, $lang, $userdata, $user_ip, $pafiledb_functions, $board_config;

		// =======================================================
		// Get Vars
		// =======================================================

		include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_field.' . PHP_EXT);

		$custom_field = new custom_field();
		$custom_field->init();

		$cat_id = ( isset($_REQUEST['cat_id']) ) ? intval($_REQUEST['cat_id']) : 0;
// MX Addon
		$do = (isset($_REQUEST['do'])) ? intval($_REQUEST['do']) : '';
		$file_id = (isset($_REQUEST['file_id'])) ? intval($_REQUEST['file_id']) : 0;
// END
		$mirrors = (isset($_POST['mirrors'])) ? true : 0;

		$dropmenu = (!$cat_id) ? $this->jumpmenu_option(0, 0, '', true, true) : $this->jumpmenu_option(0, 0, array($cat_id => 1), true, true);

		if(!empty($cat_id))
		{
			if(!$this->auth[$cat_id]['auth_upload'])
			{
				if ( !$userdata['session_logged_in'] )
				{
					redirect(append_sid(LOGIN_MG . '?redirect=dload.' . PHP_EXT . '&action=user_upload&cat_id=' . $cat_id, true));
				}

				$message = sprintf($lang['Sorry_auth_upload'], $this->auth[$cat_id]['auth_upload_type']);
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			if(empty($dropmenu))
			{
				if ( !$userdata['session_logged_in'] )
				{
					redirect(append_sid(LOGIN_MG . '?redirect=dload.' . PHP_EXT . '&action=user_upload', true));
				}

				$message = sprintf($lang['Sorry_auth_upload'], $this->auth[$cat_id]['auth_upload_type']);
				message_die(GENERAL_MESSAGE, $message);
			}
		}

		// =======================================================
		// MX Addon
		// =======================================================
		if($do == 'delete' )
		{
				$sql = 'SELECT *
				FROM ' . PA_FILES_TABLE . "
				WHERE file_id = $file_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
			}
			$file_info = $db->sql_fetchrow($result);

			if ( ($this->auth[$file_info['file_catid']]['auth_delete_file'] && $file_info['user_id'] == $userdata['user_id']) || $this->auth[$file_info['file_catid']]['auth_mod'] )
			{
			$this->delete_files($file_id);
			$this->_pafiledb();
			$message = $lang['Filedeleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.php') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$message = sprintf($lang['Sorry_auth_delete'], $this->auth[$cat_id]['auth_upload_type']);
				message_die(GENERAL_MESSAGE, $message);
			}
		}

		// =======================================================
		// IF submit then upload the file and update the sql for it
		// =======================================================

		if ( isset($_POST['submit']) )
		{
			if(!$file_id)
			{
				$temp_id = $this->update_add_file();
				$custom_field->file_update_data($temp_id);
				$this->_pafiledb();
				if ( $pafiledb_config['need_validation'] == '0')
				{
					$message = $lang['Fileadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $temp_id) . '">', '</a>');
				}
				else
				{
					$message = $lang['Fileadded_not_validated'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $cat_id) . '">', '</a>');
				}
//				$mode = 'edit';
			}
			elseif($file_id != '')
			{
				$file_id = $this->update_add_file($file_id);
				$custom_field->file_update_data($file_id);
				$this->_pafiledb();
				$message = $lang['Fileedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id) . '">', '</a>');
//				$mode = 'edit';
			}
			$message = $lang['Fileadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=user_upload') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		// =======================================================
		// IF not submit then load data form
		// =======================================================
		{
			if(!$file_id)
			{
				$file_name = '';
				$file_desc = '';
				$file_long_desc = '';
				$file_author = '';
				$file_version = '';
				$file_website = '';
				$file_posticons = $pafiledb_functions->post_icons();
// MX				$file_cat_list = $this->jumpmenu_option(0, 0, '', true);
				$file_cat_list = (!$cat_id) ? $this->jumpmenu_option(0, 0, '', true) : $this->jumpmenu_option(0, 0, array($cat_id => 1), true, true);
				$file_license = $pafiledb_functions->license_list();
				$pin_checked_yes = '';
				$pin_checked_no = ' checked';
				$file_download = 0;
				$approved_checked_yes = '';
				$approved_checked_no = ' checked';
				$file_ssurl = '';
				$ss_checked_yes = '';
				$ss_checked_no = ' checked';
				$file_url = '';
				$custom_exist = $custom_field->display_edit();
				$mode = 'ADD';
				$l_title = $lang['Afiletitle'];
			}
			elseif($file_id != '' )
			{
					$sql = 'SELECT *
						FROM ' . PA_FILES_TABLE . "
						WHERE file_id = $file_id";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
					}
					$file_info = $db->sql_fetchrow($result);

					// AUTH CHECK
					if ( !(($this->auth[$file_info['file_catid']]['auth_edit_file'] && $file_info['user_id'] == $userdata['user_id']) || $this->auth[$file_info['file_catid']]['auth_mod']) )
					{
						$message = sprintf($lang['Sorry_auth_edit'], $this->auth[$cat_id]['auth_upload_type']);
						message_die(GENERAL_MESSAGE, $message);
					}

					$file_name = $file_info['file_name'];
					$file_desc = $file_info['file_desc'];
					$file_long_desc = $file_info['file_longdesc'];
					$file_author = $file_info['file_creator'];
					$file_version = $file_info['file_version'];
					$file_website = $file_info['file_docsurl'];
					$file_posticons = $pafiledb_functions->post_icons($file_info['file_posticon']);
					$file_cat_list = $this->jumpmenu_option(0, 0, array($file_info['file_catid'] => 1), true);
					$file_license = $pafiledb_functions->license_list($file_info['file_license']);
					$pin_checked_yes = ($file_info['file_pin']) ? ' checked' : '';
					$pin_checked_no = (!$file_info['file_pin']) ? ' checked' : '';
					$file_download = intval($file_info['file_dls']);
					$approved_checked_yes = ($file_info['file_approved']) ? ' checked' : '';
					$approved_checked_no = (!$file_info['file_approved']) ? ' checked' : '';
					// MX addon
					$file_approved = ($file_info['file_approved'] == '1') ? 1 : 0;
					$file_ssurl = $file_info['file_ssurl'];
					$ss_checked_yes = ($file_info['file_sshot_link']) ? ' checked' : '';
					$ss_checked_no = (!$file_info['file_sshot_link']) ? ' checked' : '';
					$file_url = $file_info['file_dlurl'];
					$file_unique_name = $file_info['unique_name'];
					$file_dir = $file_info['file_dir'];
					$custom_exist = $custom_field->display_edit($file_id);
					$mode = 'EDIT';
					$l_title = $lang['Efiletitle'];
					$s_hidden_fields = '<input type="hidden" name="file_id" value="' . $file_id . '" />';
				}

				$s_hidden_fields .= '<input type="hidden" name="action" value="user_upload" />';


			$pafiledb_template->assign_vars(array(
				'S_ADD_FILE_ACTION' => append_sid('dload.' . PHP_EXT),
				'L_HOME' => $lang['Home'],
				'DOWNLOAD' => $pafiledb_config['settings_dbname'],
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
				'AUTH_APPROVAL' => false,
				'APPROVED_CHECKED_YES' => $approved_checked_yes,
				'APPROVED_CHECKED_NO' => $approved_checked_no,
				'SS_CHECKED_YES' => $ss_checked_yes,
				'SS_CHECKED_NO' => $ss_checked_no,
				'PIN_CHECKED_YES' => $pin_checked_yes,
				'PIN_CHECKED_NO' => $pin_checked_no,
				'L_HOME' => $lang['Home'],
				'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),

				'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
				'L_UPLOAD' => $lang['User_upload'],
				'L_FILE_TITLE' => $l_title,
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
				'L_NO' => $lang['No'],
				'L_YES' => $lang['Yes'],

				'S_POSTICONS' => $file_posticons,
				'S_LICENSE_LIST' => $file_license,
				'S_CAT_LIST' => $file_cat_list,
				'S_HIDDEN_FIELDS' => $s_hidden_fields,

// MX Addon
				'MODE' => $mode,

				'U_INDEX' => append_sid(PORTAL_MG),
				'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT)
				)
			);

			$this->display($lang['Download'] . ' :: ' . $lang['User_upload'], 'pa_file_add.tpl');
		}
	}
}

?>