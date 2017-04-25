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

// Mighty Gorgon - ACP Privacy - BEGIN
if (function_exists('check_acp_module_access'))
{
	$is_allowed = check_acp_module_access();
	if (empty($is_allowed))
	{
		return;
	}
}
// Mighty Gorgon - ACP Privacy - END

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1400_DB_Maintenance']['120_Backup_DB'] = $filename . '?mode=backup';
	$ja_module['1400_DB_Maintenance']['120_Backup_DB'] = false;
	$module['1400_DB_Maintenance']['130_Restore_DB'] = $filename . '?mode=restore';
	$ja_module['1400_DB_Maintenance']['130_Restore_DB'] = false;
	return;
}

// If download action is enabled, don't load header
if (isset($_GET['action']) && ($_GET['action'] == 'download'))
{
	$no_page_header = true;
}
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if (empty($is_allowed))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

// Define constants and then include functions
define('ROWS_PER_STEP', 3000);
include_once(IP_ROOT_PATH . 'includes/class_db_backup.' . PHP_EXT);

// Request some vars
$mode = request_var('mode', '');
$action = request_var('cancel', '') ? '' : request_var('action', '');
$u_action = append_sid(basename(__FILE__) . '?mode=' . $mode);
$submit = isset($_POST['submit']) ? true : false;

$template->set_filenames(array('body' => ADM_TPL . 'admin_db_backup.tpl'));
$template->assign_vars(array(
	'MODE' => $mode
	)
);

switch ($mode)
{
	case 'backup':
		$template->assign_block_vars('backup', array());
		switch ($action)
		{
			case 'download':
				$type = request_var('type', '');
				$table = request_var('table', array(''));
				$format = request_var('method', '');
				$where = request_var('where', '');
				$complete = request_var('complete', 1);
				$complete = $complete ? 1 : 0;
				$extended = request_var('extended', 1);
				$extended = $extended ? 1 : 0;
				$compact = request_var('compact', 1);
				$compact = $compact ? 1 : 0;

				$table_get = request_var('table_get', '');
				if (function_exists('gzcompress') && function_exists('gzuncompress') && !empty($table_get))
				{
					$table_get = unserialize(gzuncompress(stripslashes(base64_decode(strtr($table_get, '-_,', '+/=')))));
				}
				$start_default = 0;
				$limit_default = ROWS_PER_STEP;
				$started = request_var('started', false);
				$start = request_var('start', $start_default);
				$limit = request_var('limit', $limit_default);
				$progress = request_var('progress', 'false');
				$progress = ($progress == 'false') ? false : true;
				$time = request_var('time', time());
				$datecode = request_var('datecode', gmdate('Ymd'));
				$unique_id = request_var('unique_id', unique_id());

				$filepath = IP_ROOT_PATH . BACKUP_PATH;
				$filename = 'backup_' . $time . '_' . $datecode . '_' . $unique_id;

				$table = (!empty($table_get) ? explode(',', $table_get) : $table);
				$this_file_url = IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?started=1&amp;mode=' . $mode . '&amp;action=' . $action . '&amp;method=' . $format . '&amp;where=' . $where . '&amp;complete=' . $complete . '&amp;extended=' . $extended . '&amp;compact=' . $compact . '&amp;type=' . $type . '&amp;time=' . $time . '&amp;datecode=' . $datecode . '&amp;unique_id=' . $unique_id;

				// Reassign these to boolean...
				$complete = $complete ? true : false;
				$extended = $extended ? true : false;
				$compact = $compact ? true : false;

				if (!sizeof($table))
				{
					message_die(GENERAL_ERROR, $lang['Table_Select_Error'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?mode=backup') . '">', '</a>'), $lang['Error']);
				}

				$store = $download = $structure = $schema_data = false;

				if (($where == 'store_and_download') || ($where == 'store'))
				{
					$store = true;
				}

				if (($where == 'store_and_download') || ($where == 'download'))
				{
					$download = true;
				}

				if (($type == 'full') || ($type == 'structure'))
				{
					$structure = true;
				}

				if (($type == 'full') || ($type == 'data'))
				{
					$schema_data = true;
				}

				@set_time_limit(1200);

				$extractor = new mysql_extractor($download, $store, $format, $time, $filepath, $filename);

				$extractor->write_start($table_prefix, $started);

				if ($schema_data)
				{
					$archived_rows = 0;
					$table_get = $table;
				}

				foreach ($table as $table_name)
				{
					// Table Structure
					if (!$progress)
					{
						if ($structure)
						{
							$extractor->write_table($table_name);
						}
						else
						{
							$extractor->flush('TRUNCATE TABLE ' . $table_name . ";\n");
						}
					}

					// Table Data
					if ($schema_data)
					{
						$archived_rows_this_step = $extractor->write_data_mysql($table_name, $start, $limit, $complete, $extended, $compact);

						$archived_rows_prev_step = $archived_rows;
						$archived_rows += ($archived_rows_this_step !== false) ? $archived_rows_this_step : 0;
						if (($limit > 0) && ($archived_rows_this_step !== false) && ($archived_rows >= $limit))
						{
							$extractor->write_end();

							$limit = $limit_default;
							if ($archived_rows_prev_step == 0)
							{
								$start += $archived_rows_this_step;
							}

							$table_get = implode(',', $table_get);
							if (function_exists('gzcompress') && function_exists('gzuncompress') && !empty($table_get))
							{
								$table_get = strtr(base64_encode(addslashes(gzcompress(serialize($table_get), 9))), '+/=', '-_,');
							}
							$this_file_url .= '&amp;progress=true';
							$this_file_url .= '&amp;table_get=' . $table_get;
							$this_file_url .= '&amp;start=' . $start . '&amp;limit=' . $limit;

							$redirect_url = append_sid($this_file_url);
							$meta_tag = '</body><head><meta http-equiv="refresh" content="1;url=' . $redirect_url . '"></head><body>';
							$message .= $lang['BACKUP_IN_PROGRESS'] . '<br /><br />' . sprintf($lang['BACKUP_IN_PROGRESS_TABLE'], $table_name) . '<br /><br />' . $lang['BACKUP_IN_PROGRESS_REDIRECT'] . '<br /><br />' . sprintf($lang['BACKUP_IN_PROGRESS_REDIRECT_CLICK'], '<a href="' . $redirect_url . '">', '</a>');
							message_die(GENERAL_MESSAGE, $meta_tag . $message);
						}
						else
						{
							$progress = false;
							$start = $start_default;
							$limit -= (($limit > 0) && ($archived_rows_this_step !== false)) ? $archived_rows_this_step : 0;
							$table_get = array_diff($table_get, array($table_name));
						}
					}
				}

				$extractor->write_end();

				if ($download == true)
				{
					exit;
				}

				message_die(GENERAL_MESSAGE, $lang['BACKUP_SUCCESS'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?mode=backup') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>'), $lang['Information']);
			break;

			default:
				$sql = 'SHOW TABLES';
				$result = $db->sql_query($sql);
				$tables = array();

				while ($row = $db->sql_fetchrow($result))
				{
					$tables[] = current($row);
				}

				$db->sql_freeresult($result);
				foreach ($tables as $table_name)
				{
					if (strlen($table_prefix) === 0 || strpos(strtolower($table_name), strtolower($table_prefix)) === 0)
					{
						$template->assign_block_vars('backup.tables', array(
							'TABLE' => $table_name
							)
						);
					}
				}
				unset($tables);

				$template->assign_vars(array(
					'U_ACTION' => $u_action . '&amp;action=download'
					)
				);

				$available_methods = array('gzip' => 'zlib', 'bzip2' => 'bz2');

				foreach ($available_methods as $type => $module)
				{
					if (!@extension_loaded($module))
					{
						continue;
					}
					$template->assign_block_vars('backup.methods', array(
						'FIRST_ROW' => (($type == 'gzip') ? ' id="method" checked="checked"' : ''),
						'TYPE' => $type
						)
					);
				}

				$template->assign_block_vars('backup.methods', array(
					'TYPE' => 'text'
					)
				);
			break;
		}
	break;

	case 'restore':

		$template->assign_block_vars('restore', array());
		switch ($action)
		{
			case 'submit':
				$delete = request_var('delete', '');
				$file = request_var('file', '');
				$confirm = request_var('confirm', '');

				if (!preg_match('#^backup_\d{10,}_[a-z\d]{8}_[a-z\d]{16}\.(sql(?:\.(?:gz|bz2))?)$#', $file, $matches))
				{
					message_die(GENERAL_ERROR, $lang['No_Backup_Selected'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?mode=restore') . '">', '</a>'), $lang['Error']);
				}

				$file_name = IP_ROOT_PATH . BACKUP_PATH . $matches[0];

				if (!file_exists($file_name) || !is_readable($file_name))
				{
					message_die(GENERAL_ERROR, $lang['Backup_Invalid'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?mode=restore') . '">', '</a>'), $lang['Error']);
				}

				if ($delete)
				{
					if (!$confirm)
					{
						$hidden_fields = '<input type="hidden" name="file" value="' . $file . '" /><input type="hidden" name="delete" value="' . $delete . '" /><input type="hidden" name="action" value="' . $action . '" />';
						$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));
						$template->assign_vars(array(
							'MESSAGE_TITLE' => $lang['Delete'],
							'MESSAGE_TEXT' => $lang['DELETE_SELECTED_BACKUP'],

							'U_INDEX' => '',
							'L_INDEX' => '',

							'L_YES' => $lang['Yes'],
							'L_NO' => $lang['No'],

							'S_CONFIRM_ACTION' => append_sid('admin_db_backup.' . PHP_EXT . '?mode=restore'),
							'S_HIDDEN_FIELDS' => $hidden_fields
							)
						);
					}
					else
					{
						@unlink($file_name);
						message_die(GENERAL_MESSAGE, $lang['BACKUP_DELETED'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?mode=restore') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right').'">', '</a>'), $lang['Information']);
					}
				}
				else
				{
					$download = request_var('download', '');

					if ($download)
					{
						$name = $matches[0];

						switch ($matches[1])
						{
							case 'sql':
								$mimetype = 'text/x-sql';
							break;
							case 'sql.bz2':
								$mimetype = 'application/x-bzip2';
							break;
							case 'sql.gz':
								$mimetype = 'application/x-gzip';
							break;
						}

						header('Pragma: no-cache');
						header("Content-Type: $mimetype; name=\"$name\"");
						header("Content-disposition: attachment; filename=$name");

						@set_time_limit(0);

						$fp = @fopen($file_name, 'rb');

						if ($fp !== false)
						{
							while (!feof($fp))
							{
								echo fread($fp, 8192);
							}
							fclose($fp);
						}

						flush();
						exit;
					}

					switch ($matches[1])
					{
						case 'sql':
							$fp = fopen($file_name, 'rb');
							$read = 'fread';
							$seek = 'fseek';
							$eof = 'feof';
							$close = 'fclose';
							$fgetd = 'fgetd';
						break;

						case 'sql.bz2':
							$fp = bzopen($file_name, 'r');
							$read = 'bzread';
							$seek = '';
							$eof = 'feof';
							$close = 'bzclose';
							$fgetd = 'fgetd_seekless';
						break;

						case 'sql.gz':
							$fp = gzopen($file_name, 'rb');
							$read = 'gzread';
							$seek = 'gzseek';
							$eof = 'gzeof';
							$close = 'gzclose';
							$fgetd = 'fgetd';
						break;
					}

					while (($sql = $fgetd($fp, ";\n", $read, $seek, $eof)) !== false)
					{
						$db->sql_query($sql);
					}

					$close($fp);
					message_die(GENERAL_MESSAGE, $lang['Restore_Success'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_db_backup.' . PHP_EXT . '?mode=restore') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right').'">', '</a>'), $lang['Information']);
					break;
				}

			default:
				$methods = array('sql');
				$available_methods = array('sql.gz' => 'zlib', 'sql.bz2' => 'bz2');

				foreach ($available_methods as $type => $module)
				{
					if (!@extension_loaded($module))
					{
						continue;
					}
					$methods[] = $type;
				}

				$dir = IP_ROOT_PATH . BACKUP_PATH;
				$dh = @opendir($dir);

				if ($dh)
				{
					while (($file = @readdir($dh)) !== false)
					{
						if (preg_match('#^backup_(\d{10,})_[a-z\d]{8}_[a-z\d]{16}\.(sql(?:\.(?:gz|bz2))?)$#', $file, $matches))
						{
							$supported = in_array($matches[2], $methods);

							if ($supported == 'true')
							{
								$tz = $config['board_timezone'];
								$time_mode = $user->data['user_time_mode'];
								$dst_time_lag = $user->data['user_dst_time_lag'];
								switch ($time_mode)
								{
									case MANUAL_DST:
										$dst_sec = $dst_time_lag * 60;
										$backup_time = $matches[1] + (3600 * $tz) + $dst_sec;
										break;
									case SERVER_SWITCH:
										$dst_sec = gmdate('I', $matches[1]) * $dst_time_lag * 60;
										$backup_time = $matches[1] + (3600 * $tz) + $dst_sec;
										break;
									default:
										$backup_time = $matches[1] + (3600 * $tz);
										break;
								}
								$template->assign_block_vars('restore.files', array(
									'FILE' => $file,
									'NAME' => gmdate('Y/m/d - H:i:s', $backup_time),
									'SUPPORTED' => $supported
									)
								);
							}
						}
					}
					@closedir($dh);
				}

				$template->assign_vars(array(
					'U_ACTION' => $u_action . '&amp;action=submit'
					)
				);
			break;
		}
	break;
}

$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>