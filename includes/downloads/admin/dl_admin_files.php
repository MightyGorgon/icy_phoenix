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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if ($df_id)
{
	$dl_file = array();
	$dl_file = $dl_mod->all_files(0, '', ASC, $extra, $df_id, TRUE);
	if (!$dl_file['id'])
	{
		message_die(GENERAL_MESSAGE, $lang['Must_select_download']);
	}
}

$index = array();
$index = $dl_mod->full_index($cat_id);

if ($cancel)
{
	$action = '';
}

if($action == 'edit' || $action == 'add')
{
	$s_hidden_fields = '<input type="hidden" name="action" value="save" />';

	$cat_id = ($cat_id) ? $cat_id : $dl_file['cat'];

	if ($index[$cat_id]['allow_thumbs'] && $dl_config['thumb_fsize'])
	{
		$thumbnail_explain = sprintf($lang['Dl_thumb_dim_size'], $dl_config['thumb_xsize'], $dl_config['thumb_ysize'], $dl_mod->dl_size($dl_config['thumb_fsize']));
		$template->assign_block_vars('allow_thumbs', array());
		$enctype = 'enctype="multipart/form-data"';
	}
	else
	{
		$enctype = '';
	}

	if($action == 'edit')
	{
		$description = $dl_file['description'];
		$file_traffic = $dl_file['file_traffic'];
		$file_name = $dl_file['file_name'];
		$cat_id = $dl_file['cat'];
		$hacklist = $dl_file['hacklist'];
		$hack_author = $dl_file['hack_author'];
		$hack_author_email = $dl_file['hack_author_email'];
		$hack_author_website = $dl_file['hack_author_website'];
		$hack_version = $dl_file['hack_version'];
		$hack_dl_url = $dl_file['hack_dl_url'];
		$long_desc = $dl_file['long_desc'];
		$mod_test = $dl_file['test'];
		$require = $dl_file['req'];
		$todo = $dl_file['todo'];
		$warning = $dl_file['warning'];
		$mod_desc = $dl_file['mod_desc'];
		$mod_list = ($dl_file['mod_list']) ? 'checked="checked"' : '';
		$mod_desc = stripslashes($mod_desc);
		$long_desc = stripslashes($long_desc);
		$description = stripslashes($description);
		$warning = stripslashes($warning);

		if ($file_traffic > 1023)
		{
			$file_traffic_out = number_format($file_traffic / 1024, 2);
			$file_traffic_range_kb = 'checked="checked"';
			$file_traffic_range_mb = '';
			$file_traffic_range_gb = '';
		}
		if ($file_traffic > 1048575)
		{
			$file_traffic_out = number_format($file_traffic / 1048576, 2);
			$file_traffic_range_kb = '';
			$file_traffic_range_mb = 'checked="checked"';
			$file_traffic_range_gb = '';
		}
		if ($file_traffic > 1073741823)
		{
			$file_traffic_out = number_format($file_traffic / 1073741824, 2);
			$file_traffic_range_kb = '';
			$file_traffic_range_mb = '';
			$file_traffic_range_gb = 'checked="checked"';
		}

		switch ($dl_file['free'])
		{
			case 1:
				$check_not_free = '';
				$checkfree = 'checked="checked"';
				$checkfree_reg = '';
				$free_reg = '';
				break;

			case 2:
				$check_not_free = '';
				$checkfree = '';
				$checkfree_reg = 'checked="checked"';
				$free_reg = '<br />' . $lang['Dl_is_free_reg'];
				break;

			default:
				$check_not_free = 'checked="checked"';
				$checkfree = '';
				$checkfree_reg = '';
				$free_reg = '';
		}

		if ($dl_file['extern'])
		{
			$checkextern = 'checked="checked"';
		}

		if ($dl_file['approve'])
		{
			$approve = 'checked="checked"';
		}

		if (!$dl_config['disable_popup'])
		{
			$template->assign_block_vars('change_time', array(
				'L_CHANGE_TIME' => $lang['Dl_no_change_edit_time']
				)
			);
		}

		if (!$dl_config['disable_email'])
		{
			$template->assign_block_vars('email_block', array(
				'L_DL_SEND_NOTIFY' => $lang['Dl_disable_email_files']
				)
			);
		}

		$thumbnail = $dl_file['thumbnail'];

		if ($thumbnail)
		{
			$template->assign_block_vars('allow_thumbs.thumbnail', array(
				'THUMBNAIL' => POSTED_IMAGES_THUMBS_PATH . $thumbnail
				)
			);
		}

		$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
	}
	else
	{
		$approve = 'checked="checked"';
	}

	$select_code = '<select name="cat_id">';
	$select_code .= $dl_mod->dl_dropdown(0, 0, $cat_id, 'auth_up');
	$select_code .= '</select>';

	$template->set_filenames(array('files' => ADM_TPL . 'dl_files_edit_body.tpl'));

	if ($dl_config['use_hacklist'])
	{
		$template->assign_block_vars('use_hacklist', array());
	}

	if ($index[$cat_id]['allow_mod_desc'])
	{
		$template->assign_block_vars('use_mod_desc', array());
	}

	if (!$dl_config['disable_popup'] && $dl_config['disable_popup_notify'])
	{
		$template->assign_block_vars('popup_notify', array(
			'L_DISABLE_POPUP' => $lang['Dl_disable_popup_files']
			)
		);
	}

	$template->assign_vars(array(
		'L_DL_APPROVE' => $lang['Dl_approve'],
		'L_DL_APPROVE_EXPLAIN' => 'Dl_approve',
		'L_DL_CAT_NAME' => $lang['Dl_choose_category'],
		'L_DL_CAT_NAME_EXPLAIN' => 'Dl_choose_category',
		'L_DL_DESCRIPTION' => $lang['Dl_file_description'],
		'L_DL_DESCRIPTION_EXPLAIN' => 'Dl_file_description',
		'L_DL_EXTERN' => $lang['Dl_extern'],
		'L_DL_EXTERN_EXPLAIN' => 'Dl_extern',
		'L_DL_FILE_NAME' => $lang['Dl_file_name'],
		'L_DL_FILES_TITLE' => $lang['Dl_files_title'],
		'L_DL_HACK_AUTHOR' => $lang['Dl_hack_autor'],
		'L_DL_HACK_AUTHOR_EXPLAIN' => 'Dl_hack_autor',
		'L_DL_HACK_AUTHOR_EMAIL' => $lang['Dl_hack_autor_email'],
		'L_DL_HACK_AUTHOR_EMAIL_EXPLAIN' => 'Dl_hack_autor_email',
		'L_DL_HACK_AUTHOR_WEBSITE' => $lang['Dl_hack_autor_website'],
		'L_DL_HACK_AUTHOR_WEBSITE_EXPLAIN' => 'Dl_hack_autor_website',
		'L_DL_HACK_DL_URL' => $lang['Dl_hack_dl_url'],
		'L_DL_HACK_DL_URL_EXPLAIN' => 'Dl_hack_dl_url',
		'L_DL_HACK_VERSION' => $lang['Dl_hack_version'],
		'L_DL_HACK_VERSION_EXPLAIN' => 'Dl_hack_version',
		'L_DL_HACKLIST' => $lang['Dl_hacklist'],
		'L_DL_HACKLIST_EXPLAIN' => 'Dl_hacklist',
		'L_DL_IS_FREE' => $lang['Dl_is_free'],
		'L_DL_IS_FREE_EXPLAIN' => 'Dl_is_free',
		'L_DL_MOD_DESC' => $lang['Dl_mod_desc'],
		'L_DL_MOD_DESC_EXPLAIN' => 'Dl_mod_desc',
		'L_DL_MOD_LIST' => $lang['Dl_mod_list'],
		'L_DL_MOD_LIST_EXPLAIN' => 'Dl_mod_list',
		'L_DL_MOD_REQUIRE' => $lang['Dl_mod_require'],
		'L_DL_MOD_REQUIRE_EXPLAIN' => 'Dl_mod_require',
		'L_DL_MOD_TEST' => $lang['Dl_mod_test'],
		'L_DL_MOD_TEST_EXPLAIN' => 'Dl_mod_test',
		'L_DL_MOD_TODO' => $lang['Dl_mod_todo'],
		'L_DL_MOD_TODO_EXPLAIN' => 'Dl_mod_todo',
		'L_DL_MOD_WARNING' => $lang['Dl_mod_warning'],
		'L_DL_MOD_WARNING_EXPLAIN' => 'Dl_mod_warning',
		'L_DL_NAME' => $lang['Dl_name'],
		'L_DL_NAME_EXPLAIN' => 'Dl_name',
		'L_DL_ORDER' => $lang['Dl_order'],
		'L_DL_TRAFFIC' => $lang['Dl_traffic'],
		'L_DL_TRAFFIC_EXPLAIN' => 'Dl_traffic',
		'L_FREE_REG' => $lang['Dl_is_free_reg'],
		'L_LINK_URL' => $lang['Dl_files_url'],
		'L_LINK_URL_EXPLAIN' => 'Dl_files_url',
		'L_DL_THUMBNAIL' => $lang['Dl_thumb'],
		'L_DL_THUMBNAIL_EXPLAIN' => 'Dl_thumb',
		'L_DL_THUMBNAIL_SECOND' => $thumbnail_explain,
		'L_CHANGE_TIME_EXPLAIN' => 'Dl_no_change_edit_time',
		'L_DISABLE_POPUP_EXPLAIN' => 'Dl_disable_popup_files',
		'L_DL_SEND_NOTIFY_EXPLAIN' => 'Dl_disable_email_files',

		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_DELETE' => $lang['Delete'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_BYTES' => $lang['Dl_Bytes'],
		'L_KB' => $lang['Dl_KB'],
		'L_MB' => $lang['Dl_MB'],
		'L_GB' => $lang['Dl_GB'],

		'CHECKEXTERN' => $checkextern,
		'CHECKNOTFREE' => $check_not_free,
		'CHECKFREE' => $checkfree,
		'CHECKFREE_REG' => $checkfree_reg,
		'DESCRIPTION' => $description,
		'FILE_NAME' => $file_name,
		'HACK_AUTHOR' => $hack_author,
		'HACK_AUTHOR_EMAIL' => $hack_author_email,
		'HACK_AUTHOR_WEBSITE' => $hack_author_website,
		'HACK_DL_URL' => $hack_dl_url,
		'HACK_VERSION' => $hack_version,
		'HACKLIST_EVER' => ($hacklist == 2) ? 'checked="checked"' : '',
		'HACKLIST_NO' => ($hacklist == 0) ? 'checked="checked"' : '',
		'HACKLIST_YES' => ($hacklist == 1) ? 'checked="checked"' : '',
		'LONG_DESC' => $long_desc,
		'MOD_DESC' => $mod_desc,
		'MOD_LIST' => $mod_list,
		'MOD_REQUIRE' => $require,
		'MOD_TEST' => $mod_test,
		'MOD_TODO' => $todo,
		'MOD_WARNING' => $warning,
		'TRAFFIC' => $file_traffic_out,
		'URL' => $file_name,
		'FILE_TRAFFIC_RANGE_KB' => $file_traffic_range_kb,
		'FILE_TRAFFIC_RANGE_MB' => $file_traffic_range_mb,
		'FILE_TRAFFIC_RANGE_GB' => $file_traffic_range_gb,
		'APPROVE' => $approve,
		'SELECT_CAT' => $select_code,
		'ENCTYPE' => $enctype,

		'S_DOWNLOADS_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=files'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
elseif($action == 'save')
{
	$description = (isset($_POST['description'])) ? trim($_POST['description']) : "";
	$file_traffic = (isset($_POST['file_traffic'])) ? intval($_POST['file_traffic']) : 0;
	$file_traffic_range = (isset($_POST['file_traffic_range'])) ? $_POST['file_traffic_range'] : 'KB';

	$approve = (isset($_POST['approve'])) ? intval($_POST['approve']) : 0;

	$hacklist = (isset($_POST['hacklist'])) ? intval($_POST['hacklist']) : 0;
	$hack_author = (isset($_POST['hack_author'])) ? trim($_POST['hack_author']) : "";
	$hack_author_email = (isset($_POST['hack_author_email'])) ? trim($_POST['hack_author_email']) : "";
	$hack_author_website = (isset($_POST['hack_author_website'])) ? trim($_POST['hack_author_website']) : "";
	$hack_version = (isset($_POST['hack_version'])) ? trim($_POST['hack_version']) : "";
	$hack_dl_url = (isset($_POST['hack_dl_url'])) ? trim($_POST['hack_dl_url']) : "";

	$test = (isset($_POST['test'])) ? trim($_POST['test']) : "";
	$require = (isset($_POST['require'])) ? trim($_POST['require']) : "";
	$todo = (isset($_POST['todo'])) ? trim($_POST['todo']) : "";
	$warning = (isset($_POST['warning'])) ? trim($_POST['warning']) : "";
	$mod_desc = (isset($_POST['mod_desc'])) ? trim($_POST['mod_desc']) : "";
	$mod_list = ($_POST['mod_list'] == 1) ? 1 : 0;

	$long_desc = (isset($_POST['long_desc'])) ? trim($_POST['long_desc']) : "";
	$file_name = (isset($_POST['file_name'])) ? trim($_POST['file_name']) : "";
	$file_free = (isset($_POST['file_free'])) ? intval($_POST['file_free']) : 0;
	$file_extern = (isset($_POST['file_extern'])) ? intval($_POST['file_extern']) : 0;

	$description = prepare_message(trim($description), 0, 1, 1);
	$long_desc = prepare_message(trim($long_desc), 0, 1, 1);
	$mod_desc = prepare_message(trim($mod_desc), 0, 1, 1);
	$warning = prepare_message(trim($warning), 0, 1, 1);

	$send_notify = (isset($_POST['send_notify'])) ? intval($_POST['send_notify']) : 0;
	$change_time = (isset($_POST['change_time'])) ? intval($_POST['change_time']) : 0;
	$disable_popup_notify = (isset($_POST['disable_popup_notify'])) ? intval($_POST['disable_popup_notify']) : 0;
	$del_thumb = (isset($_POST['del_thumb'])) ? intval($_POST['del_thumb']) : 0;

	$extention = str_replace('.', '', trim(strrchr(strtolower($file_name), '.')));
	$ext_blacklist = $dl_mod->get_ext_blacklist();
	if (in_array($extention, $ext_blacklist))
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_forbidden_extention']);
	}

	if ($file_traffic_range == 'KB')
	{
		$file_traffic = $file_traffic * 1024;
	}
	elseif ($file_traffic_range == 'MB')
	{
		$file_traffic = $file_traffic * 1048576;
	}
	elseif ($file_traffic_range == 'GB')
	{
		$file_traffic = $file_traffic * 1073741824;
	}

	if ($df_id && !$file_extern)
	{
		$dl_file = array();
		$dl_file = $dl_mod->all_files(0, 0, 'ASC', 0, $df_id);

		$file_name_old = $dl_file['file_name'];
		$file_cat_old = $dl_file['cat'];

		$index_new = array();
		$index_new = $dl_mod->full_index($file_cat_old);

		$file_path_old = $index_new[$file_cat_old]['cat_path'];
		$file_path_new = $index[$cat_id]['cat_path'];

		if ($file_cat_old != $cat_id)
		{
			if ($file_path_old != $file_path_new)
			{
				@copy($dl_config['dl_path'] . $file_path_old . $file_name_old, $dl_config['dl_path'] . $file_path_new . $file_name);
				@unlink($dl_config['dl_path'] . $file_path_old . $file_name_old);
			}

			$sql = "UPDATE " . DL_STATS_TABLE . "
				SET cat_id = $cat_id
				WHERE id = $df_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_COMMENTS_TABLE . "
				SET cat_id = $cat_id
				WHERE id = $df_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	$file_path = $index[$cat_id]['cat_path'];
	$cat_name = $index[$cat_id]['cat_name'];

	$file_size = (!$file_extern) ? sprintf("%u", @filesize($dl_config['dl_path'] . $file_path . $file_name)) : -1;
	if (!$file_size && !$file_extern)
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Dl_file_not_found'], $file_name, $dl_config['dl_path'] . $file_path));
	}

	$current_time = time();
	$current_user = $userdata['user_id'];

	if (!$file_extern)
	{
		$file_name = (strpos($file_name, '/')) ? substr($file_name, strrpos($file_name, '/') + 1) : $file_name;
	}

	if($df_id)
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . " SET
			description = '" . str_replace("\'", "''", $description) . "',
			file_traffic = '" . str_replace("\'", "''", $file_traffic) . "',
			long_desc = '" . str_replace("\'", "''", $long_desc) . "',
			file_name = '" . str_replace("\'", "''", $file_name) . "',
			free = '" . str_replace("\'", "''", $file_free) . "',
			extern = '" . str_replace("\'", "''", $file_extern) . "',
			cat = '" . str_replace("\'", "''", $cat_id) . "',
			hacklist = '" . str_replace("\'", "''", $hacklist) . "',
			hack_author = '" . str_replace("\'", "''", $hack_author) . "',
			hack_author_email = '" . str_replace("\'", "''", $hack_author_email) . "',
			hack_author_website = '" . str_replace("\'", "''", $hack_author_website) . "',
			hack_version = '" . str_replace("\'", "''", $hack_version) . "',
			hack_dl_url = '" . str_replace("\'", "''", $hack_dl_url) . "',
			test = '" . str_replace("\'", "''", $test) . "',
			req = '" . str_replace("\'", "''", $require) . "',
			todo = '" . str_replace("\'", "''", $todo) . "',
			warning = '" . str_replace("\'", "''", $warning) . "',
			mod_desc = '" . str_replace("\'", "''", $mod_desc) . "',
			mod_list = '" . str_replace("\'", "''", $mod_list) . "',
			approve = '" . str_replace("\'", "''", $approve) . "',
			file_size = '" . str_replace("\'", "''", $file_size) . "'";

		if (!$change_time)
		{
			$sql .= ", change_time = $current_time, change_user = $current_user ";
		}

		$sql .= "WHERE id = $df_id";

		$message = $lang['Download_updated'];
	}
	else
	{
		$sql = "INSERT INTO " . DOWNLOADS_TABLE . "
			(file_name, cat, description, long_desc, free, extern,
			hacklist, hack_author, hack_author_email, hack_author_website,
			hack_version, hack_dl_url, test, req, todo, warning, mod_desc, approve,
			mod_list, file_size, change_time, add_time,
			change_user, add_user, file_traffic)
			VALUES
			('" . str_replace("\'", "''", $file_name) . "',
			'" . str_replace("\'", "''", $cat_id) . "',
			'" . str_replace("\'", "''", $description) . "',
			'" . str_replace("\'", "''", $long_desc) . "',
			'" . str_replace("\'", "''", $file_free) . "',
			'" . str_replace("\'", "''", $file_extern) . "',
			$hacklist,
			'" . str_replace("\'", "''", $hack_author) . "',
			'" . str_replace("\'", "''", $hack_author_email) . "',
			'" . str_replace("\'", "''", $hack_author_website) . "',
			'" . str_replace("\'", "''", $hack_version) . "',
			'" . str_replace("\'", "''", $hack_dl_url) . "',
			'" . str_replace("\'", "''", $test) . "',
			'" . str_replace("\'", "''", $require) . "',
			'" . str_replace("\'", "''", $todo ) . "',
			'" . str_replace("\'", "''", $warning) . "',
			'" . str_replace("\'", "''", $mod_desc) . "',
			$approve, $mod_list,
			$file_size,
			$current_time, $current_time, $current_user, $current_user,
			'" . str_replace("\'", "''", $file_traffic) . "')";

		$message = $lang['Download_added'];
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't update/insert into download table", "", __LINE__, __FILE__, $sql);
	}

	if ($index[$cat_id]['allow_thumbs'] && $dl_config['thumb_fsize'])
	{
		$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

		if ( @$ini_val('open_basedir') != '' )
		{
			if ( @phpversion() < '4.0.3' )
			{
				message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
			}

			$move_file = 'move_uploaded_file';
		}
		else
		{
			$move_file = 'copy';
		}

		$thumb_error = 0;
		if (!$del_thumb)
		{
			$thumb_size = $HTTP_POST_FILES['thumb_name']['size'];
			$thumb_temp = $HTTP_POST_FILES['thumb_name']['tmp_name'];
			$thumb_name = $HTTP_POST_FILES['thumb_name']['name'];

			if ($HTTP_POST_FILES['thumb_name']['error'] && $thumb_name)
			{
				message_die(GENERAL_MESSAGE, $lang['DL_upload_error']);
			}

			if ($thumb_name)
			{
				$pic_size = @getimagesize($thumb_temp);
				$pic_width = $pic_size[0];
				$pic_height = $pic_size[1];

				if (!$pic_width || !$pic_height)
				{
					$thumb_error = true;
				}

				if ($pic_width > $dl_config['thumb_xsize'] || $pic_height > $dl_config['thumb_ysize'] || (sprintf("%u", @filesize($thumb_temp)) > $dl_config['thumb_fsize']))
				{
					$thumb_error = true;
				}

				if (!$thumb_error)
				{
					$df_id = ($df_id) ? $df_id : $db->sql_nextid();
					@unlink(POSTED_IMAGES_THUMBS_PATH . $dl_file['thumbnail']);
					@unlink(POSTED_IMAGES_THUMBS_PATH . $df_id . '_' . $thumb_name);
					$move_file($thumb_temp, POSTED_IMAGES_THUMBS_PATH . $df_id . '_' . $thumb_name);

					@chmod(POSTED_IMAGES_THUMBS_PATH . $df_id . '_' . $thumb_name, 0777);

					$thumb_message = '<br />' . $lang['Dl_thumb_upload'];
				}

				$sql = "UPDATE " . DOWNLOADS_TABLE . "
					SET thumbnail = '" . str_replace("\'", "''", $df_id . '_' . $thumb_name) . "'
					WHERE id = $df_id";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not write thumbnail information', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		elseif ($del_thumb)
		{
			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET thumbnail = ''
				WHERE id = $df_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not write thumbnail information', '', __LINE__, __FILE__, $sql);
			}

			@unlink(POSTED_IMAGES_THUMBS_PATH . $dl_file['thumbnail']);

			$thumb_message = '<br />' . $lang['Dl_thumb_del'];
		}
	}

	if ($approve)
	{
		$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_dl');
		$email_template = ($df_id) ? 'downloads_change_notify' : 'downloads_new_notify';
	}
	else
	{
		$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_mod');
		$email_template = 'downloads_approve_notify';
	}

	$processing_user .= ($processing_user == '') ? 0 : '';

	if ($df_id)
	{
		$sql = "SELECT fav_user_id FROM " . DL_FAVORITES_TABLE . "
			WHERE fav_dl_id = " . (int) $df_id;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch favorites for this download', '', __LINE__, __FILE__, $sql);
		}

		$fav_user = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$fav_user .= ($fav_user != '') ? ', ' . $row['fav_user_id'] : $row['fav_user_id'];
		}
		$db->sql_freeresult($result);

		$sql_fav_user = ($fav_user) ? ' AND user_id IN (' . $fav_user . ') ' : '';
	}

	if (!$dl_config['disable_email'] && !$send_notify && $df_id && $sql_fav_user)
	{
		$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
			WHERE user_allow_fav_download_email = 1
				AND user_id IN ($processing_user)
				$sql_fav_user";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not send notify email for new downloads', '', __LINE__, __FILE__, $sql);
		}

		$script_path = $board_config['script_path'];
		$server_name = trim($board_config['server_name']);
		$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		while ($row = $db->sql_fetchrow($result))
		{
			//
			// Let's do some checking to make sure that mass mail functions
			// are working in win32 versions of php.
			//

			if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
			{
				$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

				// We are running on windows, force delivery to use our smtp functions
				// since php's are broken by default
				$board_config['smtp_delivery'] = 1;
				$board_config['smtp_host'] = @$ini_val('SMTP');
			}

			$emailer = new emailer($board_config['smtp_delivery']);

			$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
			$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
			$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
			$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

			$emailer->use_template($email_template, $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			$emailer->extra_headers($email_headers);
			$emailer->set_subject();

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'],
				'BOARD_EMAIL' => $board_config['board_email_sig'],
				'USERNAME' => $row['username'],
				'DOWNLOAD' => $description,
				'DESCRIPTION' => $long_desc,
				'CATEGORY' => str_replace("&nbsp;&nbsp;|___&nbsp;", '', $index[$cat_id]['cat_name']),
				'U_APPROVE' => $server_url.'downloads.' . PHP_EXT . '?view=modcp&amp;action=approve',
				'U_CATEGORY' => $server_url.'downloads.' . PHP_EXT . '?cat=' . $cat_id
				)
			);

			$emailer->send();
			$emailer->reset();
		}
	}
	elseif (!$dl_config['disable_email'] && !$send_notify && !$df_id)
	{
		$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
			WHERE user_allow_new_download_email = 1
				AND user_id IN ($processing_user)";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not send notify email for new downloads', '', __LINE__, __FILE__, $sql);
		}

		$script_path = $board_config['script_path'];
		$server_name = trim($board_config['server_name']);
		$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		while ($row = $db->sql_fetchrow($result))
		{
			//
			// Let's do some checking to make sure that mass mail functions
			// are working in win32 versions of php.
			//

			if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
			{
				$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

				// We are running on windows, force delivery to use our smtp functions
				// since php's are broken by default
				$board_config['smtp_delivery'] = 1;
				$board_config['smtp_host'] = @$ini_val('SMTP');
			}

			$emailer = new emailer($board_config['smtp_delivery']);

			$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
			$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
			$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
			$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

			$emailer->use_template($email_template, $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			$emailer->extra_headers($email_headers);
			$emailer->set_subject();

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'],
				'BOARD_EMAIL' => $board_config['board_email_sig'],
				'USERNAME' => $row['username'],
				'DOWNLOAD' => $description,
				'DESCRIPTION' => $long_desc,
				'CATEGORY' => $index[$cat_id]['cat_name'],
				'U_APPROVE' => $server_url.'downloads.' . PHP_EXT . '?view=modcp&amp;action=approve',
				'U_CATEGORY' => $server_url.'downloads.' . PHP_EXT . '?cat=' . $cat_id
				)
			);

			$emailer->send();
			$emailer->reset();
		}
	}

	if (!$dl_config['disable_popup'] && !$disable_popup_notify)
	{
		$sql = '';
		if ($df_id && $sql_fav_user)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_download = 1
				WHERE user_allow_fav_download_popup = 1
					$sql_fav_user
					AND user_id IN ($processing_user)";
		}
		elseif (!$df_id)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_download = 1
				WHERE user_allow_new_download_popup = 1
					AND user_id IN ($processing_user)";
		}

		if ($sql)
		{
			if(!$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not set popup for new/edited download on users table", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	$message .= $thumb_message . '<br /><br />' . sprintf($lang['Click_return_downloadadmin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;cat_id=' . $cat_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif($action == 'delete')
{
	if (!$confirm)
	{
		$description = $dl_file['description'];

		$template->set_filenames(array('confirm_body' => 'dl_confirm_body.tpl'));

		$template->assign_block_vars('delete_files_confirm', array());

		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';
		$s_hidden_fields .= '<input type="hidden" name="confirm" value="1" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => sprintf($lang['Dl_confirm_delete_single_file'], $description),

			'L_DELETE_FILE_TOO' => $lang['Dl_delete_file_confirm'],
			'S_SWITCH_CAT' => $s_switch_cat,

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=files'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include('./page_footer_admin.' . PHP_EXT);
	}
	else
	{
		if ($del_file)
		{
			$path = $index[$cat_id]['cat_path'];
			$file_name = $dl_file['file_name'];

			@unlink($dl_config['dl_path'] . $path . $file_name);
		}

		@unlink(POSTED_IMAGES_THUMBS_PATH . $dl_file['thumbnail']);

		$sql = "DELETE FROM " . DOWNLOADS_TABLE . "
			WHERE id = $df_id";

		if( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't delete download data", "", __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DL_STATS_TABLE . "
			WHERE id = $df_id";

		if( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't delete download statistical data", "", __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DL_COMMENTS_TABLE . "
			WHERE id = $df_id";

		if( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't delete download statistical data", "", __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DL_NOTRAF_TABLE . "
			WHERE dl_id = $df_id";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't delete download traffic marks", "", __LINE__, __FILE__, $sql);
		}

		$message = $lang['Download_removed'] . '<br /><br />' . sprintf($lang['Click_return_downloadadmin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . "?submod=files&amp;cat_id=$cat_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);

	}
}
elseif($action == 'downloads_order')
{
	$sql = "UPDATE " . DOWNLOADS_TABLE . "
		SET sort = sort - $move
		WHERE id = $df_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't change downloads order", "", __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT id FROM " . DOWNLOADS_TABLE . "
		WHERE cat = $cat_id
		ORDER BY sort ASC";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while($row = $db->sql_fetchrow($result))
	{
		$sql_sort = "UPDATE " . DOWNLOADS_TABLE . "
				SET sort = $i
				WHERE id = " . $row['id'];
		if( !$db->sql_query($sql_sort) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql_sort);
		}
		$i += 10;
	}

	$db->sql_freeresult($result);

	$action = '';
}
elseif($action == 'downloads_order_all')
{
	$sql = "SELECT * FROM " . DOWNLOADS_TABLE . "
		WHERE cat = $cat_id
		ORDER BY description ASC";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while($row = $db->sql_fetchrow($result))
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET sort = $i
			WHERE id = " . $row['id'];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}

	$db->sql_freeresult($result);

	$action = '';
}

if ($action == '')
{
	$sql = "SELECT hacklist, hack_version, file_name, description, id, free, extern, test, cat, klicks, overall_klicks, file_traffic, file_size, approve FROM " . DOWNLOADS_TABLE . "
		WHERE cat = $cat_id
		ORDER BY sort";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$file_path = $index[$cat_id]['cat_path'];
		$hacklist = ($row['hacklist']) ? $lang['Yes'] : $lang['No'];
		$version = $row['hack_version'];
		$file_name = $row['file_name'];
		$description = $row['description'];
		$file_id = $row['id'];
		$file_free = $row['free'];
		$file_extern = $row['extern'];
		$test = ($row['test']) ? '['.$row['test'] . ']' : '';
		$cat_id = $row['cat'];

		switch ($file_free)
		{
			case 1:
				$file_free_out = $lang['Dl_yes'];
				break;

			case 2:
				$file_free_out = $lang['Dl_yes_reg'];
				break;

			default:
				$file_free_out = $lang['Dl_no'];
		}

		$file_extern_out = ($file_extern) ? $lang['Dl_yes'] : $lang['Dl_no'];

		$file_klicks = $row['klicks'];
		$file_overall_klicks = $row['overall_klicks'];
		$file_traffic = $row['file_traffic'];
		if ($file_traffic)
		{
			$file_traffic = $dl_mod->dl_size($file_traffic);
		}
		else
		{
			$file_traffic = $lang['Dl_not_availible'];
		}

		if (!$file_extern)
		{
			$file_size = $row['file_size'];
			$file_size_kb = number_format($file_size / 1024, 2);
		}
		else
		{
			$file_size_kb = $lang['Dl_not_availible'];
		}

		$unapprove = ($row['approve']) ? '' : $lang['Dl_unapproved'];

		$dl_edit = append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;action=edit&amp;df_id=' . $file_id);
		$dl_delete = append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;action=delete&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id);

		$dl_move_up = append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;action=downloads_order&amp;move=15&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id);
		$dl_move_down = append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;action=downloads_order&amp;move=-15&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('downloads', array(
			'U_FILE_EDIT' => $dl_edit,
			'U_FILE_DELETE' => $dl_delete,
			'U_DOWNLOAD_MOVE_UP' => $dl_move_up,
			'U_DOWNLOAD_MOVE_DOWN' => $dl_move_down,
			'ROW_CLASS' => $row_class,
			'DESCRIPTION' => $description,
			'TEST' => $test,
			'STATUS' => $status,
			'USER_TRAFFIC' => $user_traffic_kb,
			'FILE_ID' => $file_id,
			'FILE_SIZE' => $file_size_kb,
			'FILE_FREE' => $file_free_out,
			'FILE_EXTERN' => $file_extern_out,
			'FILE_KLICKS' => $file_klicks,
			'FILE_TRAFFIC' => $file_traffic,
			'UNAPPROVED' => $unapprove,
			'FILE_OVERALL_KLICKS' => $file_overall_klicks,
			'HACKLIST' => $hacklist,
			'VERSION' => $version,
			'FILE_NAME' => ($file_extern) ? $lang['Dl_extern'] : $file_name
			)
		);

		$i++;
	}

	$categories = '<select name="cat_id" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'cat_id\'].submit() }">';
	$categories .= '<option value="-1">' . $lang['Dl_choose_category'] . '</option>';
	$categories .= '<option value="-1">----------</option>';
	$categories .= $dl_mod->dl_dropdown(0, 0, $cat_id, 'auth_up');
	$categories .= '</select>&nbsp;<input type="submit" value="' . $lang['Go'] . '" class="liteoption" />';

	$template->set_filenames(array('files' => ADM_TPL . 'dl_files_body.tpl'));

	$template->assign_vars(array(
		'L_ACTION' => $lang['Action'],
		'L_ADD_DOWNLOAD' => $lang['Add_new_download'],
		'L_DELETE' => $lang['Delete'],
		'L_DL_EXTERN' => $lang['Dl_extern'],
		'L_DL_FILE_KLICKS' => $lang['Dl_klicks'],
		'L_DL_FILE_NAME' => $lang['Dl_file_name'],
		'L_DL_FILE_OVERALL_KLICKS' => $lang['Dl_overall_klicks'],
		'L_DL_KL_M_T' => $lang['Dl_klicks_total'],
		'L_DL_FILE_SIZE' => $lang['Dl_file_size'] . '<br />KB',
		'L_DL_FILES_TEXT' => $lang['Dl_files_explain'],
		'L_DL_FILES_TITLE' => $lang['Dl_files_title'],
		'L_DL_HACKLIST' => $lang['Dl_hacks_list'],
		'L_DL_IS_FREE' => $lang['Dl_is_free'],
		'L_DL_NAME' => $lang['Dl_name'],
		'L_DL_FILE_TRAFFIC' => $lang['Dl_traffic'],
		'L_EDIT' => $lang['Edit'],
		'L_SORT' => $lang['Sort'] . ' ASC',
		'L_DL_UP' => $lang['Dl_up'],
		'L_DL_DOWN' => $lang['Dl_down'],

		'CAT' => $catid,
		'CATEGORIES' => $categories,
		'DL_COUNT' => $i.'&nbsp;' . $lang['Downloads'],

		'S_DOWNLOADS_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;cat_id=' . $cat_id),

		'U_DOWNLOAD_ORDER_ALL' => append_sid('admin_downloads.' . PHP_EXT . '?submod=files&amp;action=downloads_order_all&amp;cat_id=' . $cat_id)
		)
	);
}

$template->pparse('files');

?>