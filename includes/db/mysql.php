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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if(!defined('SQL_LAYER'))
{
	define('SQL_LAYER', 'mysql');

	class sql_db
	{
		var $db_connect_id;
		var $query_string = '';
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
		var $caching = false;
		var $cached = false;
		var $cache = array();
		var $cache_folder = '';
		var $sql_time = 0;

		// Constructor
		function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			$this->persistency = $persistency;
			$this->user = $sqluser;
			$this->password = $sqlpassword;
			$this->server = $sqlserver;
			$this->dbname = $database;

			if($this->persistency)
			{
				$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
			}
			else
			{
				$this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
			}
			if($this->db_connect_id)
			{
				if($database != "")
				{
					$this->dbname = $database;
					$dbselect = @mysql_select_db($this->dbname);
					if(!$dbselect)
					{
						@mysql_close($this->db_connect_id);
						$this->db_connect_id = $dbselect;
					}
				}

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $this->db_connect_id;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		// Other base methods
		function sql_close()
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if($this->db_connect_id)
			{
				if(!empty($this->query_result))
				{
					@mysql_free_result($this->query_result);
				}
				$result = @mysql_close($this->db_connect_id);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		// Base query method
		function sql_query($query = '', $transaction = false, $cache = false, $cache_folder = SQL_CACHE_FOLDER)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			// Remove any pre-existing queries
			unset($this->query_result);
			// Check cache
			$this->query_string = $query;
			$this->caching = false;
			$this->cached = false;
			$this->cache = array();
			$this->cache_folder = $cache_folder;
			$this->cache_folder = ((is_dir($this->cache_folder)) ? $this->cache_folder : @phpbb_realpath($this->cache_folder));
			if(($query !== '') && $cache)
			{
				$hash = md5($query);
				if(strlen($cache))
				{
					$hash = $cache . $hash;
				}
				$filename = $this->cache_folder . 'sql_' . $hash . '.php';
				if(@file_exists($filename))
				{
					$set = array();
					$cache_included = false;
					@include($filename);
					if ($cache_included == true)
					{
						$this->cache = $set;
						$this->cached = true;
						$this->caching = false;
						return 'cache';
					}
				}
	//			echo 'cache is missing: ', $filename, '<br />';
				$this->caching = $hash;
			}
			// not cached
	//		echo 'sql: ', htmlspecialchars($query), '<br />';

			if($query != '')
			{
				$this->num_queries++;

				$this->query_result = @mysql_query($query, $this->db_connect_id);
			}
			if($this->query_result)
			{
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $this->query_result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return ($transaction == END_TRANSACTION) ? true : false;
			}
		}

		// Other query methods
		function sql_numrows($query_id = 0)
		{
			if($query_id === 'cache' && $this->cached)
			{
				return count($this->cache);
			}
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$result = @mysql_num_rows($query_id);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_affectedrows()
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if($this->db_connect_id)
			{
				$result = @mysql_affected_rows($this->db_connect_id);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_numfields($query_id = 0)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$result = @mysql_num_fields($query_id);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_fieldname($offset, $query_id = 0)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$result = @mysql_field_name($query_id, $offset);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_fieldtype($offset, $query_id = 0)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$result = @mysql_field_type($query_id, $offset);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_fetchrow($query_id = 0)
		{
			if(($query_id === 'cache') && $this->cached)
			{
				return count($this->cache) ? array_shift($this->cache) : false;
			}
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$this->row[$query_id] = @mysql_fetch_array($query_id);
				if($this->caching)
				{
					if($this->row[$query_id] === false)
					{
						$this->write_cache();
					}
					$this->cache[] = $this->row[$query_id];
				}

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $this->row[$query_id];
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_fetchrowset($query_id = 0)
		{
			if(($query_id === 'cache') && $this->cached)
			{
				return $this->cache;
			}
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];
			$result = false;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);
				while($this->rowset[$query_id] = @mysql_fetch_array($query_id))
				{
					if($this->caching)
					{
						if($this->row[$query_id] === false)
						{
							$this->write_cache();
						}
						$this->cache[] = $this->row[$query_id];
					}
					$result[] = $this->rowset[$query_id];
				}
				if($this->caching)
				{
					$this->write_cache();
				}

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_fetchfield($field, $rownum = -1, $query_id = 0)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				if($rownum > -1)
				{
					$result = @mysql_result($query_id, $rownum, $field);
				}
				else
				{
					if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
					{
						if($this->sql_fetchrow())
						{
							$result = $this->row[$query_id][$field];
						}
					}
					else
					{
						if($this->rowset[$query_id])
						{
							$result = $this->rowset[$query_id][$field];
						}
						else if($this->row[$query_id])
						{
							$result = $this->row[$query_id][$field];
						}
					}
				}

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_rowseek($rownum, $query_id = 0)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$result = @mysql_data_seek($query_id, $rownum);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_nextid()
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if($this->db_connect_id)
			{
				$result = @mysql_insert_id($this->db_connect_id);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		/**
		* Build SQL to INSERT or UPDATE from the provided array
		*/
		function sql_build_insert_update($sql_input_array, $sql_insert = true)
		{
			$insert_fields_sql = '';
			$insert_values_sql = '';
			$update_sql = '';
			foreach ($sql_input_array as $k => $v)
			{
				$insert_fields_sql .= (($insert_fields_sql == '') ? '' : ', ') . $k;
				$insert_values_sql .= (($insert_values_sql == '') ? '' : ', ') . (is_numeric($v) ? '' : "'") . $v . (is_numeric($v) ? '' : "'");
				$update_sql .= (($update_sql == '') ? '' : ', ') . $k . ' = ' . (is_numeric($v) ? '' : "'") . $v . (is_numeric($v) ? '' : "'");
			}

			$sql_string = $sql_insert ? (' (' . $insert_fields_sql . ') VALUES (' . $insert_values_sql . ')') : $update_sql;

			return $sql_string;
		}

		/**
		* Escape string used in sql query
		*/
		function sql_escape($msg)
		{
			if (!$this->db_connect_id)
			{
				return @mysql_real_escape_string($msg);
			}

			return @mysql_real_escape_string($msg, $this->db_connect_id);
		}

		function sql_freeresult($query_id = 0)
		{
			if($query_id === 'cache')
			{
				$this->caching = false;
				$this->cached = false;
				$this->cache = array();
			}
			if($this->caching)
			{
				$this->write_cache();
			}

			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if ( $query_id )
			{
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);

				@mysql_free_result($query_id);

				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return true;
			}
			else
			{
				$mtime = explode(' ', microtime());
				$endtime = $mtime[1] + $mtime[0];

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_error($query_id = 0)
		{
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];

			$result['message'] = @mysql_error($this->db_connect_id);
			$result['code'] = @mysql_errno($this->db_connect_id);

			$mtime = explode(' ', microtime());
			$endtime = $mtime[1] + $mtime[0];

			$this->sql_time += $endtime - $starttime;

			return $result;
		}

		function write_cache()
		{
			if(!$this->caching)
			{
				return;
			}
			$this->cache_folder = (empty($this->cache_folder) ? SQL_CACHE_FOLDER : $this->cache_folder);
			$this->cache_folder = ((is_dir($this->cache_folder)) ? $this->cache_folder : @phpbb_realpath($this->cache_folder));
			$cache_file_name = $this->cache_folder . 'sql_' . $this->caching . '.php';
			@unlink($cache_file_name);
			$f = fopen($cache_file_name, 'w');
			@flock($f, LOCK_EX);
			$data = var_export($this->cache, true);
			//$f_content = '<' . '?php' . "\n" . '$sql_time_c = \'' . time() . '\';' . "\n\n" . '$sql_string_c = \'' . addslashes($this->query_string) . '\';' . "\n\n" . '$set = ' . $data . ';' . "\n" . 'return;' . "\n" . '?' . '>';
			$f_content = '<' . '?php' . "\n";
			$f_content .= '/* SQL: ' . str_replace('*/', '*\/', $this->query_string) . ' */' . "\n\n";
			$f_content .= '/* TIME: ' . time() . ' */' . "\n\n";
			//$f_content .= '$expired = (time() > ' . (time() + 86400) . ') ? true : false;' . "\n" . 'if ($expired) { return; }' . "\n\n";
			$f_content .= '$set = ' . $data . ';' . "\n";
			$f_content .= '$cache_included = true;' . "\n" . 'return;' . "\n";
			$f_content .= '?' . '>';
			@fwrite($f, $f_content);
			@flock($f, LOCK_UN);
			@fclose($f);
			@chmod($cache_file_name, 0666);
			$this->caching = false;
			$this->cached = false;
			$this->cache = array();
		}

		function clear_cache($prefix = '', $cache_folder = SQL_CACHE_FOLDER, $files_per_step = 0)
		{
			$this->caching = false;
			$this->cached = false;
			$this->cache = array();
			$prefix = 'sql_' . $prefix;
			$prefix_len = strlen($prefix);
			$this->cache_folder = $cache_folder;
			$this->cache_folder = ((is_dir($this->cache_folder)) ? $this->cache_folder : @phpbb_realpath($this->cache_folder));
			$res = opendir($this->cache_folder);
			if($res)
			{
				$files_counter = 0;
				while(($file = readdir($res)) !== false)
				{
					if(!is_dir($file) && substr($file, 0, $prefix_len) === $prefix)
					{
						@unlink($this->cache_folder . $file);
						$files_counter++;
					}
					if (($files_per_step > 0) && ($files_counter >= $files_per_step))
					{
						closedir($res);
						return $files_per_step;
					}
				}
			}
			@closedir($res);
		}
	} // class sql_db

} // if ... define

?>