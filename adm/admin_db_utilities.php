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
	//$module['1400_DB_Maintenance']['120_Backup_DB'] = $filename . '?perform=backup';
	$file_uploads = (@phpversion() >= '4.0.0') ? @ini_get('file_uploads') : @get_cfg_var('file_uploads');
	if((empty($file_uploads) || $file_uploads != 0) && (strtolower($file_uploads) != 'off') && (@phpversion() != '4.0.4pl1'))
	{
		$module['1400_DB_Maintenance']['135_Restore_DB'] = $filename . '?perform=restore';
		$ja_module['1400_DB_Maintenance']['135_Restore_DB'] = false;
	}
	$module['1400_DB_Maintenance']['140_Optimize_DB'] = $filename . '?perform=optimize';
	$ja_module['1400_DB_Maintenance']['140_Optimize_DB'] = false;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cron.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_db_utilities.' . PHP_EXT);

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if (empty($is_allowed))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

// Set VERBOSE to 1  for debugging info..
define('VERBOSE', 0);

// Increase maximum execution time, but don't complain about it if it isn't allowed.
@set_time_limit(0);

// Begin program proper
$perform = request_var('perform', '');
if(!empty($perform))
{

	switch($perform)
	{
		//Start Optimize Database 1.2.2 by Sko22 < sko22@quellicheilpc.it >
		case 'optimize':
			include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
			$current_time = time();

			// If has been clicked the button reset
			if(isset($_POST['reset']))
			{
				set_config('cron_database_interval', 0);
				set_config('cron_database_last_run', 0);
				set_config('cron_db_count', 0);
				set_config('cron_db_show_begin_for', '');
				set_config('cron_db_show_not_optimized', 0);
			}

			// If has been clicked the button configure
			if(isset($_POST['configure']) || isset($_POST['show_begin_for']))
			{
				set_config('cron_db_show_begin_for', $_POST['show_begin_for']);

				if (isset($_POST['configure']))
				{
					// Update optimize database cronfiguration
					set_config('cron_database_interval', $_POST['cron_every']);
					set_config('cron_database_last_run', $current_time);
					set_config('cron_db_show_begin_for', $_POST['show_begin_for']);
					set_config('cron_db_show_not_optimized', $_POST['show_not_optimized']);
				}
			}

			// If has been clicked the button optimize
			if(!isset($_POST['optimize']))
			{
				$sql = "SHOW TABLE STATUS LIKE '" . $config['cron_db_show_begin_for'] . "%' ";
				$result = $db->sql_query($sql);

				$i = 0;
				while ($opt = $db->sql_fetchrow($result))
				{
					if (($opt['Data_free'] != 0) || !$config['cron_db_show_not_optimized'])
					{
						$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
						$dbsize = $opt['Data_length'] + $opt['Index_length'];
						// Exact weight of a table of a database
						$dbsize = format_file_size($dbsize);
						$opt['Data_free'] != 0 ? $data_free = 'No OK' : $data_free = 'OK';
						$opt['Data_free'] != 0 ? $check = ' checked="checked"' : $check = '';

						// Make list tables of database
						$template->assign_block_vars('optimize', array(
							'ROW_CLASS' => $row_class,
							'S_SELECT_TABLE' => '<input type="checkbox" name="selected_tbl[]" value="' . $opt['Name'] . '"' . $check . ' />',
							'TABLE' => $opt['Name'],
							'RECORD' => $opt['Rows'],
							'TYPE' => $opt['Type'],
							'SIZE' => $dbsize,
							'STATUS' => $data_free,
							'TOT_TABLE' => $i
							)
						);
						$total_tab = $i +1;
						$total_rec = $total_rec + $opt['Rows'];
						$total_size = $total_size + $opt['Data_length'] + $opt['Index_length'];
						if ($data_free == 'No OK')
						{
							$total_stat = 'No OK';
						}
					}
					else
					{
						$i--;
					}
					$i++;
				}
				$total_size = round(($total_size / 1048576), 1) . ' Mb';
				$total_stat = ($total_stat == 'No OK') ? 'No OK' : 'OK';

				$template->set_filenames(array('body' => ADM_TPL . 'db_utils_optimize_body.tpl'));
				$s_hidden_fields = '<input type="hidden" name="perform" value="' . $perform . '" />';

				// Enable the select tables script
				if ($i != 1)
				{
					$select_script = "
					<script type=\"text/javascript\">
					// I have copied and modified a script of phpMyAdmin.net
					<!--
					function setCheckboxesDB(the_form, do_check)
					{
						var elts = (typeof(document.forms[the_form].elements['selected_tbl[]']) != 'undefined')
						? document.forms[the_form].elements['selected_tbl[]']
						: document.forms[the_form].elements = '';

						var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;

						if (elts_cnt)
						{
							for (var i = 0; i < elts_cnt; i++)
							{
								if (do_check == \"invert\")
								{
									elts[i].checked == true ? elts[i].checked = false : elts[i].checked = true;
								}
								else
								{
									elts[i].checked = do_check;
								}
							} // end for
						}
						else
						{
							elts.checked = do_check;
						} // end if... else

						return true;
					}
					//-->
					</script>
					";
				}
				else
				{
					$select_script = "
					<script type=\"text/javascript\">
					<!--
					function setCheckboxesDB(the_form, do_check)
					{

					}
					//-->
					</script>";
				}

				$list_cron_intervals = array(
					'Disabled' => 0,
					'15M' => 900,
					'30M' => 1800,
					'1H' => 3600,
					'2H' => 7200,
					'3H' => 10800,
					'6H' => 21600,
					'12H' => 43200,
					'1D' => 86400,
					'3D' => 259200,
					'7D' => 604800,
					'14D' => 1209600,
					'30D' => 2592000,
				);

				$cron_intervals_select = '';
				$cron_intervals_select .= '<select name="cron_every">';
				foreach ($list_cron_intervals as $k => $v)
				{
					$cron_intervals_select .= '<option value="' . $v . '"' . (($config['cron_database_interval'] == $v) ? ' selected="selected"' : '') . '>' . $lang[$k] . '</option>';
				}
				$cron_intervals_select .= '</select>';

				$config['cron_db_show_not_optimized'] != '1' ? ($enable_not_optimized_no = ' checked="checked"') : ($enable_not_optimized_yes = ' checked="checked"');

				if (($config['cron_database_interval'] == 0) || ($config['cron_database_last_run'] == 0))
				{
					$next_cron = ' - - ';
					$performed_cron = ' - - ';
				}
				else
				{
					$next_cron = create_date('d M Y H:i:s', ($config['cron_database_last_run'] + $config['cron_database_interval']), $config['board_timezone']);
					$performed_cron = $config['cron_db_count'];
				}

				// Build the template
				$template->assign_vars(array(
					'SELECT_SCRIPT' => $select_script,
					'TOT_TABLE' => $total_tab,
					'TOT_RECORD' => $total_rec,
					'TOT_SIZE' => $total_size,
					'TOT_STATUS' => $total_stat,
					'NEXT_CRON' => $next_cron,
					'CURRENT_TIME' => create_date('d M Y H:i:s', $current_time, $config['board_timezone']),
					'PERFORMED_CRON' => $performed_cron,
					'CRON_INTERVALS_SELECT' => $cron_intervals_select,

					'L_ENABLE_CRON' => $lang['Optimize_Enable_cron'],
					'L_YES' => $lang['Yes'],
					'L_NO' => $lang['No'],
					'L_CRON_EVERY' => $lang['Optimize_Cron_every'],
					'L_CRON_EVERY_EXPLAIN' => $lang['Optimize_Cron_every_explain'],
					'L_CURRENT_TIME' => $lang['Optimize_Current_time'],
					'L_NEXT_CRON_ACTION' => $lang['Optimize_Next_cron_action'],
					'L_PERFORMED_CRON' => $lang['Optimize_Performed_Cron'],
					'L_DATABASE_OPTIMIZE' => $lang['Database_Utilities'] . ': ' . $lang['Optimize'],
					'L_OPTIMIZE_EXPLAIN' => $lang['Optimize_explain'],
					'L_OPTIMIZE_DB' => $lang['Optimize_DB'],
					'L_CONFIGURATION' => $lang['Configuration'],
					'L_SHOW_NOT_OPTIMIZED' => $lang['Optimize_Show_not_optimized'],
					'L_SHOW_BEGIN_FOR' => $lang['Optimize_Show_begin_for'],
					'L_CONFIGURE' => $lang['Optimize_Configure'],
					'L_RESET' => $lang['Reset'],
					'L_TABLE' => $lang['Optimize_Table'],
					'L_RECORD' => $lang['Optimize_Record'],
					'L_TYPE' => $lang['Optimize_Type'],
					'L_SIZE' => $lang['Optimize_Size'],
					'L_STATUS' => $lang['Optimize_Status'],
					'L_CHECKALL' => $lang['Optimize_CheckAll'],
					'L_UNCHECKALL' => $lang['Optimize_UncheckAll'],
					'L_INVERTCHECKED' => $lang['Optimize_InvertChecked'],
					'L_START_OPTIMIZE' => $lang['Optimize'],
					'S_DBUTILS_ACTION' => append_sid('admin_db_utilities.' . PHP_EXT),
					'S_ENABLE_CRON_YES' => $enable_cron_yes,
					'S_ENABLE_CRON_NO' => $enable_cron_no,
					'S_SHOW_BEGIN_FOR' => $config['cron_db_show_begin_for'],
					'S_ENABLE_NOT_OPTIMIZED_YES' => $enable_not_optimized_yes,
					'S_ENABLE_NOT_OPTIMIZED_NO' => $enable_not_optimized_no,
					'S_HIDDEN_FIELDS' => $s_hidden_fields
					)
				);

				$template->pparse('body');
				break;
			}
			else
			{
				$sql = 'OPTIMIZE TABLE ';
				// Make optimize query
				if ($_POST['selected_tbl'] != '')
				{
					$i = 1;
					foreach ($_POST['selected_tbl'] as $var => $value)
					{
						if($i< sizeof($_POST['selected_tbl']))
						{
							$sql .= "`$value`, ";
						}
						else
						{
							$sql .= "`$value`";
						}
					$i++;
					}
				}
				$sql .= ' ;';

				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					$optimize_notablechecked = true;
				}
				//
				// Create information message
				//
				if ($optimize_notablechecked == true)
				{
					$message = $lang['Optimize_NoTableChecked'] . '.' .
					'<br /><br />' . sprintf($lang['Optimize_return'], '<a href="' . append_sid('admin_db_utilities.' . PHP_EXT . '?perform=optimize') . '">', '</a>') .
					'<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
				}
				else
				{
					$message = $message = $lang['Optimize_success'] . '.' .
					'<br /><br />' . sprintf($lang['Optimize_return'], '<a href="' . append_sid('admin_db_utilities.' . PHP_EXT . '?perform=optimize') . '">', '</a>') .
					'<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
				}
				message_die(GENERAL_MESSAGE, $message);
			}
			break;

	//
	// End Optimize Database 1.2.2 by Sko22 < sko22@quellicheilpc.it >
		case 'backup':

			$phpbb_only = (!empty($_POST['phpbb_only'])) ? $_POST['phpbb_only'] : ((!empty($_GET['phpbb_only'])) ? $_GET['phpbb_only'] : 0);
			$sql = 'SHOW TABLES';
			$field = "Tables_in_{$dbname}";
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$current_table = $row[$field];
				$current_prefix = substr($current_table, 0, strlen($table_prefix));
				if ($phpbb_only && $current_prefix != $table_prefix)
				{
					continue;
				}
				else
				{
					$tables[] = $current_table;
				}
			}
			$additional_tables = (isset($_POST['additional_tables'])) ? $_POST['additional_tables'] : ((isset($_GET['additional_tables'])) ? $_GET['additional_tables'] : "");

			$backup_type = (isset($_POST['backup_type'])) ? $_POST['backup_type'] : ((isset($_GET['backup_type'])) ? $_GET['backup_type'] : '');

			$gzipcompress = (!empty($_POST['gzipcompress'])) ? $_POST['gzipcompress'] : ((!empty($_GET['gzipcompress'])) ? $_GET['gzipcompress'] : 0);

			$drop = (!empty($_POST['drop'])) ? intval($_POST['drop']) : ((!empty($_GET['drop'])) ? intval($_GET['drop']) : 0);

			if(!empty($additional_tables))
			{
				if(false !== strpos($additional_tables, ","))
				{
					$additional_tables = split(",", $additional_tables);

					for($i = 0; $i < sizeof($additional_tables); $i++)
					{
						$tables[] = trim($additional_tables[$i]);
					}

				}
				else
				{
					$tables[] = trim($additional_tables);
				}
			}

			if(!isset($_POST['backupstart']) && !isset($_GET['backupstart']))
			{
				include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);

				$template->set_filenames(array('body' => ADM_TPL . 'db_utils_backup_body.tpl'));
				$s_hidden_fields = '<input type="hidden" name="perform" value="backup" /><input type="hidden" name="drop" value="1" /><input type="hidden" name="perform" value="' . $perform . '" />';

				$template->assign_vars(array(
					'L_DATABASE_BACKUP' => $lang['Database_Utilities'] . ': ' . $lang['Backup'],
					'L_BACKUP_EXPLAIN' => $lang['Backup_explain'],
					'L_FULL_BACKUP' => $lang['Full_backup'],
					'L_STRUCTURE_BACKUP' => $lang['Structure_backup'],
					'L_DATA_BACKUP' => $lang['Data_backup'],
					'L_ADDITIONAL_TABLES' => $lang['Additional_tables'],
					'L_START_BACKUP' => $lang['Start_backup'],
					'L_BACKUP_OPTIONS' => $lang['Backup_options'],
					'L_PHPBB_ONLY' => $lang['phpBB_only'],
					'L_GZIP_COMPRESS' => $lang['Gzip_compress'],
					'L_NO' => $lang['No'],
					'L_YES' => $lang['Yes'],
					'S_HIDDEN_FIELDS' => $s_hidden_fields,
					'S_DBUTILS_ACTION' => append_sid('admin_db_utilities.' . PHP_EXT)
					)
				);
				$template->pparse('body');
				break;
			}
			elseif(!isset($_POST['startdownload']) && !isset($_GET['startdownload']))
			{
				if(is_array($additional_tables))
				{
					$additional_tables = implode(',', $additional_tables);
				}

				$template->set_filenames(array('body' => ADM_TPL . 'admin_message_body.tpl'));

				$redirect_url = append_sid(ADM . '/admin_db_utilities.' . PHP_EXT . '?perform=backup&amp;additional_tables=' . quotemeta($additional_tables) . '&amp;backup_type=' . $backup_type . '&amp;drop=1&amp;backupstart=1&amp;phpbb_only=' . $phpbb_only . '&amp;gzipcompress=' . $gzipcompress . '&amp;startdownload=1');
				meta_refresh(3, $redirect_url);

				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Database_Utilities'] . ': ' . $lang['Backup'],
					'MESSAGE_TEXT' => $lang['Backup_download']
					)
				);

				include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
				$template->pparse('body');
				include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
			}
			header('Pragma: no-cache');
			$do_gzip_compress = false;
			if($gzipcompress)
			{
				$phpver = phpversion();
				if($phpver >= '4.0')
				{
					if(extension_loaded("zlib"))
					{
						$do_gzip_compress = true;
					}
				}
			}
			$gendate = str_replace(' ', '_', create_date($lang['DATE_FORMAT'], time(), $config['board_timezone']));

			if($do_gzip_compress)
			{
				@ob_start();
				@ob_implicit_flush(0);
				header("Content-Type: application/x-gzip; name=\"phpbb_backup_$gendate.sql.gz\"");
				header("Content-disposition: attachment; filename=phpbb_backup_$gendate.sql.gz");
			}
			else
			{
				header("Content-Type: text/x-delimtext; name=\"phpbb_backup_$gendate.sql\"");
				header("Content-disposition: attachment; filename=phpbb_backup_$gendate.sql");
			}

			// Build the sql script file...
			echo "#\n";
			echo "# Icy Phoenix Backup Script\n";
			echo "# Dump of tables for $dbname\n";
			echo "#\n# DATE : " .  gmdate("d-m-Y H:i:s", time()) . " GMT\n";
			echo "#\n";

			for($i = 0; $i < sizeof($tables); $i++)
			{
				$table_name = $tables[$i];
				$table_def_function = 'get_table_def_mysql';
				$table_content_function = 'get_table_content_mysql';
				if($backup_type != 'data')
				{
					echo "#\n# TABLE: " . $table_name . "\n#\n";
					echo $table_def_function($table_name, "\n") . "\n";
				}

				if($backup_type != 'structure')
				{
					$table_content_function($table_name, 'output_table_content');
				}
			}

			if($do_gzip_compress)
			{
				$Size = ob_get_length();
				$Crc = crc32(ob_get_contents());
				$contents = gzcompress(ob_get_contents());
				ob_end_clean();
				echo "\x1f\x8b\x08\x00\x00\x00\x00\x00".substr($contents, 0, strlen($contents) - 4).gzip_PrintFourChars($Crc).gzip_PrintFourChars($Size);
			}
			exit;
			break;

		case 'restore':
			if(!isset($_POST['restore_start']))
			{
				//
				// Define Template files...
				//
				include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
				$template->set_filenames(array('body' => ADM_TPL . 'db_utils_restore_body.tpl'));

				$s_hidden_fields = "<input type=\"hidden\" name=\"perform\" value=\"restore\" /><input type=\"hidden\" name=\"perform\" value=\"$perform\" />";

				$template->assign_vars(array(
					'L_DATABASE_RESTORE' => $lang['Database_Utilities'] . ': ' . $lang['Restore'],
					'L_RESTORE_EXPLAIN' => $lang['Restore_explain'],
					'L_SELECT_FILE' => $lang['Select_file'],
					'L_START_RESTORE' => $lang['Start_Restore'],

					'S_DBUTILS_ACTION' => append_sid('admin_db_utilities.' . PHP_EXT),
					'S_HIDDEN_FIELDS' => $s_hidden_fields
					)
				);
				$template->pparse('body');
				break;
			}
			else
			{
				//
				// Handle the file upload ....
				// If no file was uploaded report an error...
				//
				$backup_file_name = (!empty($_FILES['backup_file']['name'])) ? $_FILES['backup_file']['name'] : '';
				$backup_file_tmpname = ($_FILES['backup_file']['tmp_name'] != 'none') ? $_FILES['backup_file']['tmp_name'] : '';
				$backup_file_type = (!empty($_FILES['backup_file']['type'])) ? $_FILES['backup_file']['type'] : '';

				if($backup_file_tmpname == '' || $backup_file_name == '')
				{
					message_die(GENERAL_MESSAGE, $lang['Restore_Error_no_file']);
				}
				//
				// If I file was actually uploaded, check to make sure that we
				// are actually passed the name of an uploaded file, and not
				// a hackers attempt at getting us to process a local system
				// file.
				//
				if(file_exists(@phpbb_realpath($backup_file_tmpname)))
				{
					if(preg_match("/^(text\/[a-zA-Z]+)|(application\/(x\-)?gzip(\-compressed)?)|(application\/octet-stream)$/is", $backup_file_type))
					{
						if(preg_match("/\.gz$/is",$backup_file_name))
						{
							$do_gzip_compress = false;
							$phpver = phpversion();
							if($phpver >= "4.0")
							{
								if(extension_loaded("zlib"))
								{
									$do_gzip_compress = true;
								}
							}

							if($do_gzip_compress)
							{
								$gz_ptr = gzopen($backup_file_tmpname, 'rb');
								$sql_query = "";
								while(!gzeof($gz_ptr))
								{
									$sql_query .= gzgets($gz_ptr, 100000);
								}
							}
							else
							{
								message_die(GENERAL_ERROR, $lang['Restore_Error_decompress']);
							}
						}
						else
						{
							$sql_query = fread(fopen($backup_file_tmpname, 'r'), filesize($backup_file_tmpname));
						}
						//
						// Comment this line out to see if this fixes the stuff...
						//
						//$sql_query = stripslashes($sql_query);
					}
					else
					{
						message_die(GENERAL_ERROR, $lang['Restore_Error_filename'] ." $backup_file_type $backup_file_name");
					}
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Restore_Error_uploading']);
				}

				if(!empty($sql_query))
				{
					// Strip out sql comments...
					$db->remove_remarks($sql_query);
					$pieces = $db->split_sql_file($sql_query, ';');

					$sql_count = sizeof($pieces);
					for($i = 0; $i < $sql_count; $i++)
					{
						$sql = trim($pieces[$i]);

						if(!empty($sql) && ($sql[0] != "#"))
						{
							if(VERBOSE == 1)
							{
								echo "Executing: $sql\n<br />";
								flush();
							}
							$result = $db->sql_query($sql);
						}
					}
				}
				include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
				$template->set_filenames(array('body' => ADM_TPL . 'admin_message_body.tpl'));
				$message = $lang['Restore_success'];
				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Database_Utilities'] . ': ' . $lang['Restore'],
					'MESSAGE_TEXT' => $message
					)
				);
				$template->pparse('body');
				break;
			}
			break;
	}
}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>