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

$files = (isset($_POST['files'])) ? $_POST['files'] : array();

if ($files && $file_assign)
{
	$file_names = $file_paths = array();

	for ($i = 0; $i < count($files); $i++)
	{
		$temp = strpos($files[$i], '|');
		$files_path[] = substr($files[$i],0,$temp);
		$files_name[] = substr($files[$i],$temp+1);
	}

	if ($file_assign == 'del')
	{
		for ($i = 0; $i < count($files); $i++)
		{
			$dl_dir = ($files_path[$i]) ? substr($dl_config['dl_path'], 0, strlen($dl_config['dl_path'])-1) : $dl_config['dl_path'];

			@unlink($dl_dir . $files_path[$i] . '/' . $files_name[$i]);

			$sql = "DELETE FROM " . DOWNLOADS_TABLE . "
				WHERE file_name = '".$files_name[$i]."'
					AND cat NOT IN (".implode(', ', $index).")";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete download data', '', __LINE__, __FILE__, $sql);
			}


		}
	}
	else
	{
		$dl_dir = substr($dl_config['dl_path'], 0, strlen($dl_config['dl_path'])-1);

		for ($i = 0; $i < count($files); $i++)
		{
			$sql = "SELECT path FROM " . DL_CAT_TABLE . "
				WHERE id = $file_assign";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not assign download data', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			$cat_path = $row['path'];
			$db->sql_freeresult($result);

			if ($cat_path != substr($files_path[$i], 1).'/')
			{
				@copy ($dl_dir . $files_path[$i] . '/' . $files_name[$i], $dl_config['dl_path'] . $cat_path . $files_name[$i]);
				@unlink($dl_dir . $files_path[$i] . '/' . $files_name[$i]);
			}

			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET cat = $file_assign
				WHERE cat NOT IN (".implode(', ', $index).")
					AND file_name = '".$files_name[$i]."'";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not assign download data', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	$file_action = $file_command = $new_path = '';
}

if (count($index))
{
	$unas_files = $files_temp = array();

	$sql = "SELECT description, file_name FROM " . DOWNLOADS_TABLE . "
		WHERE cat NOT IN (".implode(', ', $index).")";
	if ($result = $db->sql_query($sql))
	{
		$total_unassigned_files = $db->sql_numrows($result);
		if ($action == 'unassigned')
		{
			$i = 0;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_name = $row['file_name'];
				$file_desc = $row['description'];
				$unas_files[$i] = $file_name;
				$unas_files[$file_name] = $file_desc;
				$i++;
			}
		}
		$db->sql_freeresult($result);
	}

	if ($action == 'unassigned')
	{
		$read_files = $dl_mod->read_dl_files($dl_config['dl_path']);
		$read_files = substr($read_files, 0, strlen($read_files) - 1 );
		$files = split('[\|]', $read_files);
		for ($i = 0; $i < count($files); $i++)
		{
			$temp = strrpos($files[$i], '/');
			$files_data[] = substr($files[$i],0,$temp).'|'.substr($files[$i],$temp+1);
		}
	}
}

if ($action == 'check_file_sizes')
{
	$sql = "SELECT dl.*, c.path FROM " . DOWNLOADS_TABLE . " dl, " . DL_CAT_TABLE . " c
		WHERE dl.cat = c.id
			AND dl.extern <> 1
		ORDER BY dl.id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not get file data", "", __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$file_size = $row['file_size'];
		$file_name = $row['file_name'];
		$file_path = $row['path'];
		$file_id = $row['id'];

		$check_file_size = sprintf("%u", @filesize($dl_config['dl_path'] . $file_path . $file_name));
		if ( $check_file_size == 0 || $check_file_size == '' )
		{
			$message .= $file_name.'<br />';
		}
		elseif ($check_file_size <> $file_size)
		{
			$sql_new = "UPDATE " . DOWNLOADS_TABLE . "
					SET file_size = " . $check_file_size . "
					WHERE id = $file_id";
			if( !$db->sql_query($sql_new) )
			{
				$message .= $file_name.'<br />';
			}
		}
	}
	$action = '';

	if ( $message != '' )
	{
		$check_message = $lang['Dl_check_filesizes_result_error'] . '<br /><br />' . $message;
	}
	else
	{
		$check_message = $lang['Dl_check_filesizes_result'];
	}

	$check_message .= '<br /><br />' . sprintf($lang['Click_return_file_management'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $check_message);
}

if ($action == 'check_thumbnails')
{
	$thumbs = (isset($_POST['thumb'])) ? $_POST['thumb'] : array();

	if (isset($_POST['del_real_thumbs']))
	{
		for ($i = 0; $i < count($thumbs); $i++)
		{
			@unlink(POSTED_IMAGES_THUMBS_PATH . $thumbs[$i]);
		}
	}

	$real_thumbnails = array();
	@$dir = opendir(POSTED_IMAGES_THUMBS_PATH);

	while (false !== ($file=@readdir($dir)))
	{
		if ($file{0} != "." && !is_dir($file))
		{
			$real_thumbnails['file_name'][] = $file;
			$real_thumbnails['file_size'][] = sprintf("%u", @filesize(POSTED_IMAGES_THUMBS_PATH . $file));
		}
	}

	@closedir($dir);

	$sql = "SELECT thumbnail FROM " . DOWNLOADS_TABLE . "
		WHERE thumbnail <> ''";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not get file data", "", __LINE__, __FILE__, $sql);
	}

	$dl_thumbs = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$dl_thumbs[] = $row['thumbnail'];
	}
	$db->sql_freeresult($result);

	if (count($real_thumbnails['file_name']))
	{
		$template->set_filenames(array(
			'toolbox' => ADM_TPL . 'dl_thumbs_body.tpl')
		);

		$template->assign_vars(array(
			'L_DELETE' => $lang['Delete'],
			'L_DL_THUMBNAILS' => $lang['Dl_thumb'],
			'L_MARK_ALL' => $lang['Dl_mark_all'],
			'L_UNMARK_ALL' => $lang['Dl_unmark'],
			'S_MANAGE_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=check_thumbnails'))
		);

		for ($i = 0; $i < count($real_thumbnails['file_name']); $i++)
		{
			$real_file = $real_thumbnails['file_name'][$i];
			if (!in_array($real_file, $dl_thumbs))
			{
				$checkbox = '<input type="checkbox" name="thumb[]" value="' . $real_file . '" />';
			}
			else
			{
				$checkbox = '';
			}

			$template->assign_block_vars('thumbnails', array(
				'CHECKBOX' => $checkbox,
				'REAL_FILE' => $real_file,
				'U_REAL_FILE' => POSTED_IMAGES_THUMBS_PATH . $real_file,
				'FILE_SIZE' => $dl_mod->dl_size($real_thumbnails['file_size'][$i])
				)
			);
		}
	}
	else
	{
		$action = 'browse';
	}
}

if ($files && $file_command)
{
	$path_temp = $path;
	$path .= ($path) ? '/' : '';

	if ($file_command == 'del')
	{
		for ($i = 0; $i < count($files); $i++)
		{
			@unlink($dl_config['dl_path'] . $path . $files[$i]);
		}
	}
	else
	{
		for ($i = 0; $i < count($files); $i++)
		{
			@copy ($dl_config['dl_path'] . $path . $files[$i], $file_command . $files[$i]);
			@unlink($dl_config['dl_path'] . $path . $files[$i]);
		}
	}

	$path = $path_temp;
	$file_action = $file_command = $new_path = '';
}

if ($dir_name && $dircreate)
{
	@mkdir($dl_config['dl_path'] . $path . '/' . $dir_name);
	@chmod($dl_config['dl_path'] . $path . '/' . $dir_name, 0777);
}

if ($action == 'dirdelete')
{
	$file_name = basename($path);

	$content_count = 0;

	$sh = @opendir($dl_config['dl_path'] . $path . '/' . $file);

	while (false !== ($subfile=@readdir($sh)))
	{
		if (substr($subfile,0,1)!=".")
		{
			$content_count++;
		}
	}

	@closedir($sh);

	if ($content_count == 0)
	{
		@rmdir($dl_config['dl_path'] . $path);
	}

	$action = '';

	$path = ($path != $file_name) ? substr($path, 0, strlen($path) - strlen($file_name)-1) : '';
}

if ($action == 'browse' || $action == '' || $action == 'unassigned')
{
	if ($action != 'unassigned')
	{
		$temp_url = '';
		$temp_dir = array();

		$dl_navi = $dl_config['dl_path'];
		$dl_navi = str_replace(IP_ROOT_PATH, '', $dl_navi);
		$dl_navi = '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=browse') . '">' . $dl_navi;
		$dl_navi = substr($dl_navi, 0, strlen($dl_navi)-1).'</a>/';

		if ($path)
		{
			$path = ($path{0} == '/') ? substr($path, 1) : $path;
			$temp_dir = split('[/]', $path);
			if (count($temp_dir) > 0)
			{
				for ($i = 0; $i < count($temp_dir); $i++)
				{
					$temp_url .= '/'.$temp_dir[$i];
					$temp_path = preg_replace('#[/]*#', '', $temp_dir[$i]);
					$dl_navi .= '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=browse&amp;path=' . $temp_url) . '">' . $temp_path . '</a>/';
				}
			}
		}

		$dirs = $dirs_delete = $files = $filen = $sizes = $exist = array();

		$existing_files = array();
		$existing_files = $dl_mod->read_exist_files();

		$dh = @opendir($dl_config['dl_path'] . $path);

		while (false !== ($file=@readdir($dh)))
		{
			if (substr($file,0,1)!=".")
			{
				if (is_dir($dl_config['dl_path'] . $path . '/' . $file))
				{
					$slash = ($path) ? '/' : '';
					$dirs[] = '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=browse&amp;path=' . $path . $slash . $file) . '">' . $file . '</a>/';

					$sh = @opendir($dl_config['dl_path'] . $path . '/' . $file);

					$content_count = 0;

					while (false !== ($subfile=@readdir($sh)))
					{
						if (substr($subfile,0,1)!=".")
						{
							$content_count++;
						}
					}

					@closedir($sh);

					$dirs_delete[] = ($content_count == 0) ? '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=dirdelete&amp;path=' . $path . $slash . $file) . '"><img src="../' . $images['icon_delpost'] . '" border="0" alt="" title="" /></a>' : sprintf($lang['Dl_manage_content_count'], $content_count);
				}
				else
				{
					$files[] = '<a href="' . $dl_config['dl_path'] . $path . '/' . $file.'" target="_blank">' . $file . '</a>';
					$filen[] = $file;
					$sizes[] = sprintf("%u", @filesize($dl_config['dl_path'] . $path .'/' . $file));
					$exist[] = (in_array($file, $existing_files)) ? true : 0;
				}
			}
		}

		@closedir($dh);

		$template->assign_block_vars('create_dir_command', array());
	}
	else
	{
		$dl_navi = $lang['Dl_unassigned_files'];
	}

	$template->set_filenames(array('toolbox' => ADM_TPL . 'dl_toolbox_body.tpl'));

	$template->assign_vars(array(
		'L_DL_MANAGE' => $lang['Dl_manage'],
		'L_DL_MANAGE_EXPLAIN' => $lang['Dl_manage_explain'],
		'L_DL_MANAGE_CREATE_DIR' => $lang['Dl_manage_create_dir'],
		'L_DOWNLOADS_CHECK_FILES' => $lang['Dl_check_file_sizes'],
		'L_DOWNLOADS_CHECK_THUMBS' => $lang['Dl_check_thumbnails'],

		'L_GO' => $lang['Dl_go'],
		'L_MARK_ALL' => $lang['Dl_mark_all'],
		'L_UNMARK_ALL' => $lang['Dl_unmark'],

		'DL_NAVI' => $dl_navi,

		'S_MANAGE_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;path=' . $path),

		'U_DOWNLOADS_CHECK_FILES' => append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=check_file_sizes'),
		'U_DOWNLOADS_CHECK_THUMB' => append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=check_thumbnails')
		)
	);

	$existing_thumbs = 0;
	@$dir = opendir(POSTED_IMAGES_THUMBS_PATH);

	while (false !== ($file=@readdir($dir)))
	{
		if ($file{0} != "." && !is_dir($file))
		{
			$existing_thumbs = TRUE;
			break;
		}
	}

	@closedir($dir);

	if ($existing_thumbs)
	{
		$template->assign_block_vars('thumbnail_check', array());
	}

	if (!$dirs && !$files)
	{
		$template->assign_block_vars('empty_folder', array(
			'L_NO_CONTENT' => $lang['Dl_manage_empty_folder']
			)
		);
	}

	if ($total_unassigned_files && $action != 'unassigned')
	{
		$template->assign_block_vars('unassigned_files', array(
			'L_UNASSIGNED_FILES' => $lang['Dl_unassigned_files'],
			'U_UNASSIGNED_FILES' => append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox&amp;action=unassigned')
			)
		);
	}

	if ($dirs)
	{
		natcasesort($dirs);
		for($i = 0; $i < count($dirs); $i++)
		{
			$template->assign_block_vars('dirs_row', array(
				'DIR_LINK' => '&raquo;&nbsp;' . $dirs[$i],
				'DIR_DELETE_LINK' => $dirs_delete[$i])
			);
		}
	}

	if ($files)
	{
		natcasesort($files);
		$overall_size = 0;
		for($i = 0; $i < count($files); $i++)
		{
			$file_size = ($action != 'unassigned') ? $sizes[$i] : sprintf("%u", @filesize($dl_config['dl_path'] . $files[$i]));

			$file_size_tmp = $dl_mod->dl_size($file_size, 2, 'no');
			$file_size_out = $file_size_tmp['size_out'];
			$file_size_range = $file_size_tmp['range'];

			if ($action != 'unassigned')
			{
				$template->assign_block_vars('files_row', array(
					'FILE_NAME' => $files[$i],
					'FILE_SIZE' => $file_size_out,
					'FILE_SIZE_RANGE' => $file_size_range,
					'FILE_EXIST' => (!$exist[$i]) ? '<input type="checkbox" name="files[]" value="' . $filen[$i] . '" />' : '')
				);
			}
			else
			{
				$template->assign_block_vars('files_row', array(
					'FILE_NAME' => '<b>' . $unas_files[substr($files[$i], strrpos($files[$i], '/') + 1)] . '</b> (' . $files[$i] . ')',
					'FILE_SIZE' => $file_size_out,
					'FILE_SIZE_RANGE' => $file_size_range,
					'FILE_EXIST' => '<input type="checkbox" name="files[]" value="' . $files_data[$i] . '" />')
				);
			}
			$overall_size += $file_size;
		}

		$overall_size_tmp = array();
		$overall_size_tmp = $dl_mod->dl_size($overall_size, 2, 'no');
		$overall_size_out = $overall_size_tmp['size_out'];
		$file_size_range = $overall_size_tmp['range'];

		$cur = $path;

		if ($action != 'unassigned')
		{
			$s_file_action = '<select name="file_command">';
			$s_file_action .= '<option value="del">' . $lang['Dl_delete'] . '</option>';
			$s_file_action .= '<option value="---">---------------</option>';
			$s_file_action .= $dl_mod->read_dl_dirs($dl_config['dl_path']);
		}
		else
		{
			$s_file_action = '<select name="file_assign">';
			$s_file_action .= '<option value="del">' . $lang['Dl_delete'] . '</option>';
			$s_file_action .= '<option value="---">---------------</option>';
			$s_file_action .= $dl_mod->dl_dropdown(0, 0, 0, 'auth_view');
		}
		$s_file_action .= '</select>';

		$template->assign_block_vars('overall_size', array(
			'OVERALL_SIZE' => $overall_size_out,
			'OVERALL_SIZE_RANGE' => $file_size_range,

			'S_FILE_ACTION' => $s_file_action)
		);
	}
	else
	{
		$template->assign_block_vars('default_footer', array());
	}
}

$template->pparse('toolbox');

?>