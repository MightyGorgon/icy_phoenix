<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1400_DB_Maintenance']['100_Backup_DB'] = $filename . '?mode=backup';
	$module['1400_DB_Maintenance']['110_Restore_DB'] = $filename . '?mode=restore';
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');

// If download action is enabled, don't load header
if ($_GET['action'] == 'download')
{
	$no_page_header = true;
}
require('./pagestart.' . $phpEx);

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

				if (!count($table))
				{
					message_die(GENERAL_ERROR, $lang['Table_Select_Error'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid($phpbb_root_path . ADM . '/admin_db_backup.' . $phpEx . '?mode=backup') . '">', '</a>'), $lang['Error']);
				}

				$store = $download = $structure = $schema_data = false;

				if ($where == 'store_and_download' || $where == 'store')
				{
					$store = true;
				}

				if ($where == 'store_and_download' || $where == 'download')
				{
					$download = true;
				}

				if ($type == 'full' || $type == 'structure')
				{
					$structure = true;
				}

				if ($type == 'full' || $type == 'data')
				{
					$schema_data = true;
				}

				@set_time_limit(1200);

				$time = time();
				$datecode = date('Ymd');
				$filename = 'backup_' . $time . '_' . $datecode . '_' . unique_id();

				$extractor = new mysql_extractor($download, $store, $format, $filename, $time);

				$extractor->write_start($table_prefix);

				foreach ($table as $table_name)
				{
					// Get the table structure
					if ($structure)
					{
						$extractor->write_table($table_name);
					}
					else
					{
						$extractor->flush('TRUNCATE TABLE ' . $table_name . ";\n");
					}

					// Data
					if ($schema_data)
					{
						$extractor->write_data_mysql($table_name, true, false);
					}
				}

				$extractor->write_end();

				if ($download == true)
				{
					exit;
				}

				message_die(GENERAL_MESSAGE, $lang['Backup_Success'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid($phpbb_root_path . ADM . '/admin_db_backup.'.$phpEx.'?mode=backup') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid($phpbb_root_path . ADM . '/index.' . $phpEx . '?pane=right') . '">', '</a>'), $lang['Information']);
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
					message_die(GENERAL_ERROR, $lang['No_Backup_Selected'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid($phpbb_root_path . ADM . '/admin_db_backup.' . $phpEx . '?mode=restore') . '">', '</a>'), $lang['Error']);
				}

				$file_name = $phpbb_root_path . BACKUP_PATH . $matches[0];

				if (!file_exists($file_name) || !is_readable($file_name))
				{
					message_die(GENERAL_ERROR, $lang['Backup_Invalid'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid($phpbb_root_path . ADM . '/admin_db_backup.' . $phpEx . '?mode=restore') . '">', '</a>'), $lang['Error']);
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

							'S_CONFIRM_ACTION' => append_sid('admin_db_backup.' . $phpEx . '?mode=restore'),
							'S_HIDDEN_FIELDS' => $hidden_fields
							)
						);
					}
					else
					{
						@unlink($file_name);
						message_die(GENERAL_MESSAGE, $lang['Backup_Deleted'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid($phpbb_root_path . ADM . '/admin_db_backup.' . $phpEx . '?mode=restore') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid($phpbb_root_path . ADM . '/index.' . $phpEx . '?pane=right').'">', '</a>'), $lang['Information']);
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
					message_die(GENERAL_MESSAGE, $lang['Restore_Success'] . '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . append_sid($phpbb_root_path . ADM . '/admin_db_backup.' . $phpEx . '?mode=restore') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid($phpbb_root_path . ADM . '/index.' . $phpEx . '?pane=right').'">', '</a>'), $lang['Information']);
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

				$dir = $phpbb_root_path . BACKUP_PATH;
				$dh = @opendir($dir);

				if ($dh)
				{
					while (($file = readdir($dh)) !== false)
					{
						if (preg_match('#^backup_(\d{10,})_[a-z\d]{8}_[a-z\d]{16}\.(sql(?:\.(?:gz|bz2))?)$#', $file, $matches))
						{
							$supported = in_array($matches[2], $methods);

							if ($supported == 'true')
							{
								$template->assign_block_vars('restore.files', array(
									'FILE' => $file,
									'NAME' => gmdate("Y/m/d - H:i:s", $matches[1]),
									'SUPPORTED' => $supported
								));
							}
						}
					}
					closedir($dh);
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
include('./page_footer_admin.' . $phpEx);

/**
* @package db
*/
class base_extractor
{
	var $fh;
	var $fp;
	var $write;
	var $close;
	var $store;
	var $download;
	var $time;
	var $format;
	var $run_comp = false;

	function base_extractor($download = false, $store = false, $format, $filename, $time)
	{
		$this->download = $download;
		$this->store = $store;
		$this->time = $time;
		$this->format = $format;

		switch ($format)
		{
			case 'text':
				$ext = '.sql';
				$open = 'fopen';
				$this->write = 'fwrite';
				$this->close = 'fclose';
				$mimetype = 'text/x-sql';
			break;
			case 'bzip2':
				$ext = '.sql.bz2';
				$open = 'bzopen';
				$this->write = 'bzwrite';
				$this->close = 'bzclose';
				$mimetype = 'application/x-bzip2';
			break;
			case 'gzip':
				$ext = '.sql.gz';
				$open = 'gzopen';
				$this->write = 'gzwrite';
				$this->close = 'gzclose';
				$mimetype = 'application/x-gzip';
			break;
		}

		if ($download == true)
		{
			$name = $filename . $ext;
			header('Pragma: no-cache');
			header("Content-Type: $mimetype; name=\"$name\"");
			header("Content-disposition: attachment; filename=$name");

			switch ($format)
			{
				case 'bzip2':
					ob_start();
				break;

				case 'gzip':
					if ((isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie') === false)
					{
						ob_start('ob_gzhandler');
					}
					else
					{
						$this->run_comp = true;
					}
				break;
			}
		}

		if ($store == true)
		{
			global $phpbb_root_path;
			$file = $phpbb_root_path . BACKUP_PATH . $filename . $ext;

			$this->fp = $open($file, 'w');

			if (!$this->fp)
			{
				message_die(GENERAL_ERROR, 'Unable to write temporary file to storage folder', $lang['Error']);
			}
		}
	}

	function write_end()
	{
		static $close;
		if ($this->store)
		{
			if ($close === null)
			{
				$close = $this->close;
			}
			$close($this->fp);
		}

		// bzip2 must be written all the way at the end
		if ($this->download && $this->format === 'bzip2')
		{
			$c = ob_get_clean();
			echo bzcompress($c);
		}
	}

	function flush($data)
	{
		static $write;
		if ($this->store === true)
		{
			if ($write === null)
			{
				$write = $this->write;
			}
			$write($this->fp, $data);
		}

		if ($this->download === true)
		{
			if ($this->format === 'bzip2' || $this->format === 'text' || ($this->format === 'gzip' && !$this->run_comp))
			{
				echo $data;
			}

			// we can write the gzip data as soon as we get it
			if ($this->format === 'gzip')
			{
				if ($this->run_comp)
				{
					echo gzencode($data);
				}
				else
				{
					ob_flush();
					flush();
				}
			}
		}
	}
}

/**
* @package db
*/
class mysql_extractor extends base_extractor
{
	function write_start($table_prefix)
	{
		$sql_data = "#\n";
		$sql_data .= "# Icy Phoenix Backup Script\n";
		$sql_data .= "# Dump of tables for $table_prefix\n";
		$sql_data .= "# DATE : " . gmdate("d-m-Y H:i:s", $this->time) . " GMT\n";
		$sql_data .= "#\n";
		$sql_data .= "\n";
		$this->flush($sql_data);
	}

	function write_table($table_name)
	{
		global $db;
		static $new_extract;

		if ($new_extract === null)
		{
			if (version_compare($db->mysql_version, '3.23.20', '>='))
			{
				$new_extract = true;
			}
			else
			{
				$new_extract = false;
			}
		}

		if ($new_extract)
		{
			$this->new_write_table($table_name);
		}
		else
		{
			$this->old_write_table($table_name);
		}
	}

	function write_data_mysql($table_name, $complete_insert = true, $extended_insert = false)
	{
		global $db;
		$sql = "SELECT *
			FROM $table_name";
		$result = mysql_unbuffered_query($sql, $db->db_connect_id);

		if ($result != false)
		{
			$fields_cnt = mysql_num_fields($result);

			// Get field information
			$field = array();
			for ($i = 0; $i < $fields_cnt; $i++)
			{
				$field[] = mysql_fetch_field($result, $i);
			}
			$field_set = array();

			for ($j = 0; $j < $fields_cnt; $j++)
			{
				$field_set[] = $field[$j]->name;
			}

			$search = array("\\", "'", "\x00", "\x0a", "\x0d", "\x1a", '"');
			$replace = array("\\\\", "\\'", '\0', '\n', '\r', '\Z', '\\"');
			$fields = implode(', ', $field_set);
			if ($complete_insert == true)
			{
				$sql_data = 'INSERT INTO ' . $table_name . ' (' . $fields . ') VALUES ';
			}
			else
			{
				$sql_data = 'INSERT INTO ' . $table_name . ' VALUES ';
			}
			$first_set = true;
			$query = '';
			$query_len = 0;
			$max_len = get_usable_memory();

			while ($row = mysql_fetch_row($result))
			{
				$values = array();
				if (($first_set) || ($extended_insert == false))
				{
					$query .= $sql_data . '(';
				}
				else
				{
					$query .= ',(';
				}

				for ($j = 0; $j < $fields_cnt; $j++)
				{
					if (!isset($row[$j]) || is_null($row[$j]))
					{
						$values[$j] = 'NULL';
					}
					elseif ($field[$j]->numeric && ($field[$j]->type !== 'timestamp'))
					{
						$values[$j] = $row[$j];
					}
					else
					{
						$values[$j] = "'" . str_replace($search, $replace, $row[$j]) . "'";
					}
				}
				$query .= implode(', ', $values) . ')';
				if ($extended_insert == false)
				{
					$query .= ';' . "\n";
				}

				$query_len += strlen($query);
				if ($query_len > $max_len)
				{
					if ($extended_insert == true)
					{
						$query .= ';' . "\n";
					}
					$this->flush($query);
					$query = '';
					$query_len = 0;
					$first_set = true;
				}
				else
				{
					$first_set = false;
				}
			}
			mysql_free_result($result);

			// check to make sure we have nothing left to flush
			if (!$first_set && $query)
			{
				if ($extended_insert == true)
				{
					$query .= ';' . "\n";
				}
				$this->flush($query . "\n\n");
			}
		}
	}

	function new_write_table($table_name)
	{
		global $db;

		$sql = 'SHOW CREATE TABLE ' . $table_name;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		$sql_data = '# Table: ' . $table_name . "\n";
		$sql_data .= "DROP TABLE IF EXISTS $table_name;\n";
		$this->flush($sql_data . $row['Create Table'] . ";\n\n");

		$db->sql_freeresult($result);
	}

	function old_write_table($table_name)
	{
		global $db;

		$sql_data = '# Table: ' . $table_name . "\n";
		$sql_data .= "DROP TABLE IF EXISTS $table_name;\n";
		$sql_data .= "CREATE TABLE $table_name(\n";
		$rows = array();

		$sql = "SHOW FIELDS
			FROM $table_name";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$line = '   ' . $row['Field'] . ' ' . $row['Type'];

			if (!is_null($row['Default']))
			{
				$line .= " DEFAULT '{$row['Default']}'";
			}

			if ($row['Null'] != 'YES')
			{
				$line .= ' NOT NULL';
			}

			if ($row['Extra'] != '')
			{
				$line .= ' ' . $row['Extra'];
			}

			$rows[] = $line;
		}
		$db->sql_freeresult($result);

		$sql = "SHOW KEYS
			FROM $table_name";

		$result = $db->sql_query($sql);

		$index = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$kname = $row['Key_name'];

			if ($kname != 'PRIMARY')
			{
				if ($row['Non_unique'] == 0)
				{
					$kname = "UNIQUE|$kname";
				}
			}

			if ($row['Sub_part'])
			{
				$row['Column_name'] .= '(' . $row['Sub_part'] . ')';
			}
			$index[$kname][] = $row['Column_name'];
		}
		$db->sql_freeresult($result);

		foreach ($index as $key => $columns)
		{
			$line = '   ';

			if ($key == 'PRIMARY')
			{
				$line .= 'PRIMARY KEY (' . implode(', ', $columns) . ')';
			}
			elseif (strpos($key, 'UNIQUE') === 0)
			{
				$line .= 'UNIQUE ' . substr($key, 7) . ' (' . implode(', ', $columns) . ')';
			}
			elseif (strpos($key, 'FULLTEXT') === 0)
			{
				$line .= 'FULLTEXT ' . substr($key, 9) . ' (' . implode(', ', $columns) . ')';
			}
			else
			{
				$line .= "KEY $key (" . implode(', ', $columns) . ')';
			}

			$rows[] = $line;
		}

		$sql_data .= implode(",\n", $rows);
		$sql_data .= "\n);\n\n";

		$this->flush($sql_data);
	}
}

// get how much space we allow for a chunk of data, very similar to phpMyAdmin's way of doing things ;-) (hey, we only do this for MySQL anyway :P)
function get_usable_memory()
{
	$val = trim(@ini_get('memory_limit'));

	if (preg_match('/(\\d+)([mkg]?)/i', $val, $regs))
	{
		$memory_limit = (int) $regs[1];
		switch ($regs[2])
		{

			case 'k':
			case 'K':
				$memory_limit *= 1024;
			break;

			case 'm':
			case 'M':
				$memory_limit *= 1048576;
			break;

			case 'g':
			case 'G':
				$memory_limit *= 1073741824;
			break;
		}

		// how much memory PHP requires at the start of export (it is really a little less)
		if ($memory_limit > 6100000)
		{
			$memory_limit -= 6100000;
		}

		// allow us to consume half of the total memory available
		$memory_limit /= 2;
	}
	else
	{
		// set the buffer to 1M if we have no clue how much memory PHP will give us :P
		$memory_limit = 1048576;
	}

	return $memory_limit;
}

function sanitize_data_generic($text)
{
	$data = preg_split('/[\n\t\r\b\f]/', $text);
	preg_match_all('/[\n\t\r\b\f]/', $text, $matches);

	$val = array();

	foreach ($data as $value)
	{
		if (strlen($value))
		{
			$val[] = "'" . $value . "'";
		}
		if (count($matches[0]))
		{
			$val[] = "'" . array_shift($matches[0]) . "'";
		}
	}

	return implode('||', $val);
}

// modified from PHP.net
function fgetd(&$fp, $delim, $read, $seek, $eof, $buffer = 8192)
{
	$record = '';
	$delim_len = strlen($delim);

	while (!$eof($fp))
	{
		$pos = strpos($record, $delim);
		if ($pos === false)
		{
			$record .= $read($fp, $buffer);
			if ($eof($fp) && ($pos = strpos($record, $delim)) !== false)
			{
				$seek($fp, $pos + $delim_len - strlen($record), SEEK_CUR);
				return substr($record, 0, $pos);
			}
		}
		else
		{
			$seek($fp, $pos + $delim_len - strlen($record), SEEK_CUR);
			return substr($record, 0, $pos);
		}
	}

	return false;
}

function fgetd_seekless(&$fp, $delim, $read, $seek, $eof, $buffer = 8192)
{
	static $array = array();
	static $record = '';

	if (!count($array))
	{
		while (!$eof($fp))
		{
			if (strpos($record, $delim) !== false)
			{
				$array = explode($delim, $record);
				$record = array_pop($array);
				break;
			}
			else
			{
				$record .= $read($fp, $buffer);
			}
		}
		if ($eof($fp) && strpos($record, $delim) !== false)
		{
			$array = explode($delim, $record);
			$record = array_pop($array);
		}
	}

	if (count($array))
	{
		return array_shift($array);
	}

	return false;
}

/**
* Return unique id
* @param string $extra additional entropy
*/
function unique_id($extra = 'c')
{
	global $board_config;

	$val = $board_config['rand_seed'] . microtime();
	$val = md5($val);
	$config['rand_seed'] = md5($board_config['rand_seed'] . $val . $extra);

	return substr($val, 4, 16);
}

?>