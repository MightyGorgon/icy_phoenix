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
* Philipp Kordowich
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

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

	function base_extractor($download = false, $store = false, $format = 'text', $time = '', $filepath = './', $filename = '')
	{
		$this->download = $download;
		$this->store = $store;
		$this->time = (empty($time) ? time() : $time);
		$this->format = $format;

		switch ($format)
		{
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
			case 'text':
			default:
				$ext = '.sql';
				$open = 'fopen';
				$this->write = 'fwrite';
				$this->close = 'fclose';
				$mimetype = 'text/x-sql';
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
			$file = $filepath . $filename . $ext;

			//$this->fp = $open($file, 'w');
			$this->fp = $open($file, 'a');

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
		if ($this->download && ($this->format === 'bzip2'))
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
			if (($this->format === 'bzip2') || ($this->format === 'text') || (($this->format === 'gzip') && !$this->run_comp))
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
	function write_start($table_prefix, $started = false)
	{
		$sql_data = "";
		if (!$started)
		{
			$sql_data .= "#\n";
			$sql_data .= "# Icy Phoenix Backup Script\n";
			$sql_data .= "# Dump of tables for $table_prefix\n";
			$sql_data .= "# DATE : " . gmdate("d-m-Y H:i:s", $this->time) . " GMT\n";
			$sql_data .= "#\n";
			$sql_data .= "\n";
		}
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

	function write_data_mysql($table_name, $start = 0, $limit = ROWS_PER_STEP, $complete_insert = true, $extended_insert = false, $compact_line_breaks = false)
	{
		global $db;
		$start = ($start <= 0) ? 0 : (int) $start;
		$limit = ($limit <= 0) ? 0 : (int) $limit;
		$limit_sql = '';
		if (!empty($limit))
		{
			$limit_sql = ' LIMIT ' . $start . ', ' . $limit;
		}
		$sql = "SELECT * FROM " . $table_name . $limit_sql;
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
			$sql_data = 'INSERT INTO ' . $table_name . ' ';
			if ($complete_insert)
			{
				$sql_data .= '(' . $fields . ') VALUES ';
			}
			else
			{
				$sql_data .= 'VALUES ';
			}
			$first_set = true;
			$query = '';
			$query_len = 0;
			$max_len = get_usable_memory();

			$rows_cnt = 0;
			while ($row = mysql_fetch_row($result))
			{
				$values = array();
				if ($first_set || !$extended_insert)
				{
					$query .= $sql_data . (($extended_insert && !$compact_line_breaks) ? "\n" : '') . '(';
				}
				else
				{
					$query .= ',' . (!$compact_line_breaks ? "\n" : '') . '(';
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
				if (!$extended_insert)
				{
					$query .= ';' . "\n";
				}

				$query_len += strlen($query);
				if ($query_len > $max_len)
				{
					if ($extended_insert)
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
				$rows_cnt++;
			}
			mysql_free_result($result);

			// check to make sure we have nothing left to flush
			if (!$first_set && $query)
			{
				if ($extended_insert)
				{
					$query .= ';' . "\n";
				}
				$this->flush($query . "\n\n");
			}
			return $rows_cnt;
		}
		return false;
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
			$line = '  ' . $row['Field'] . ' ' . $row['Type'];

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
			$line = '  ';

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
		if (sizeof($matches[0]))
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

	if (!sizeof($array))
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

	if (sizeof($array))
	{
		return array_shift($array);
	}

	return false;
}

?>