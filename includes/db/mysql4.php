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
	define('SQL_LAYER', 'mysql4');

	class sql_db
	{
		var $db_connect_id;
		var $query_string = '';
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
		var $in_transaction = 0;
		var $caching = false;
		var $cached = false;
		var $cache = array();
		var $cache_folder = '';
		var $sql_start_time = 0;
		var $sql_end_time = 0;
		var $sql_time = 0;

		// Constructor
		function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
		{
			$this->sql_start_time = $this->sql_set_start_time();

			$this->persistency = $persistency;
			$this->user = $sqluser;
			$this->password = $sqlpassword;
			$this->server = $sqlserver;
			$this->dbname = $database;

			$this->db_connect_id = ($this->persistency) ? @mysql_pconnect($this->server, $this->user, $this->password) : @mysql_connect($this->server, $this->user, $this->password);

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

				$result = $this->db_connect_id;
			}
			else
			{
				$result = false;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return $result;
		}

		// Set start time
		function sql_set_start_time()
		{
			$mtime = explode(' ', microtime());
			$this->sql_start_time = $mtime[1] + $mtime[0];
			return $this->sql_start_time;
		}

		// Set end time
		function sql_set_end_time()
		{
			$mtime = explode(' ', microtime());
			$this->sql_end_time = $mtime[1] + $mtime[0];
			return $this->sql_end_time;
		}

		// Other base methods
		function sql_close()
		{
			$this->sql_start_time = $this->sql_set_start_time();

			if($this->db_connect_id)
			{
				// Commit any remaining transactions
				if($this->in_transaction)
				{
					@mysql_query('COMMIT', $this->db_connect_id);
				}

				// Mighty Gorgon: to be checked, maybe we should remove this...
				if(!empty($this->query_result))
				{
					@mysql_free_result($this->query_result);
				}

				$result = @mysql_close($this->db_connect_id);
			}
			else
			{
				$result = false;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return $result;
		}

		// Base query method
		function sql_query($query = '', $transaction = false, $cache = false, $cache_folder = SQL_CACHE_FOLDER)
		{
			$this->sql_start_time = $this->sql_set_start_time();

			// Mighty Gorgon - Extra Debug - BEGIN
			if (defined('DEBUG_EXTRA') && ($query != ''))
			{
				$this->sql_report('start', $query);
			}
			// Mighty Gorgon - Extra Debug - END

			// Mighty Gorgon - Extra Debug - BEGIN
			if (CACHE_SQL == false)
			{
				$cache = false;
			}
			// Mighty Gorgon - Extra Debug - END

			// Remove any pre-existing queries
			unset($this->query_result);
			// Check cache
			$this->query_string = $query;
			$this->caching = false;
			$this->cached = false;
			$this->cache = array();
			$this->cache_folder = $cache_folder;
			$this->cache_folder = ((@is_dir($this->cache_folder)) ? $this->cache_folder : @phpbb_realpath($this->cache_folder));
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
						// Mighty Gorgon - Extra Debug - BEGIN
						if (defined('DEBUG_EXTRA'))
						{
							$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

							$this->sql_report('stop', $query);
						}
						// Mighty Gorgon - Extra Debug - END
						return 'cache';
					}
				}
			// echo 'cache is missing: ', $filename, '<br />';
				$this->caching = $hash;
			}
			// not cached
			//echo 'sql: ', htmlspecialchars($query), '<br />';

			// Mighty Gorgon - Debug SQL Cache - BEGIN
			// Cache SQL in the same file plus underscore
			if (defined('SQL_DEBUG_LOG') && (SQL_DEBUG_LOG == true))
			{
				/*
				$f = fopen($this->cache_folder . 'sql_' . $hash . '_.php', 'w');
				@flock($f, LOCK_EX);
				@fwrite($f, '\'' . $query . '\'');
				@flock($f, LOCK_UN);
				@fclose($f);
				*/
				// Cache SQL history in a file
				if (!defined('IN_ADMIN'))
				{
					$f = fopen($this->cache_folder . 'sql_history.php', 'a+');
					@flock($f, LOCK_EX);
					@fwrite($f, date('Y/m/d - H:i:s') . ' => ' . $hash . "\n\n" . $query . "\n\n\n=========================\n\n");
					@flock($f, LOCK_UN);
					@fclose($f);
				}
			}
			// Mighty Gorgon - Debug SQL Cache - END

			if($query != '')
			{
				$this->num_queries++;
				if($transaction == BEGIN_TRANSACTION && !$this->in_transaction)
				{
					$result = @mysql_query('BEGIN', $this->db_connect_id);
					if(!$result)
					{
						return false;
					}
					$this->in_transaction = true;
				}

				$this->query_result = @mysql_query($query, $this->db_connect_id);
			}
			else
			{
				if($transaction == END_TRANSACTION && $this->in_transaction)
				{
					$result = @mysql_query('COMMIT', $this->db_connect_id);
				}
			}

			if($this->query_result)
			{
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);

				if($transaction == END_TRANSACTION && $this->in_transaction)
				{
					$this->in_transaction = false;

					if (!@mysql_query('COMMIT', $this->db_connect_id))
					{
						@mysql_query('ROLLBACK', $this->db_connect_id);
						return false;
					}
				}

				$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

				// Mighty Gorgon - Extra Debug - BEGIN
				if (defined('DEBUG_EXTRA'))
				{
					$this->sql_report('stop', $query);
				}
				// Mighty Gorgon - Extra Debug - END

				return $this->query_result;
			}
			else
			{
				if($this->in_transaction)
				{
					@mysql_query('ROLLBACK', $this->db_connect_id);
					$this->in_transaction = false;
				}

				$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

				// Mighty Gorgon - Extra Debug - BEGIN
				if (defined('DEBUG_EXTRA'))
				{
					$this->sql_report('stop', $query);
				}
				// Mighty Gorgon - Extra Debug - END

				return false;
			}
		}

		// Other query methods
		function sql_numrows($query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();
			if(($query_id === 'cache') && $this->cached)
			{
				$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
				return count($this->cache);
			}

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($query_id) ? @mysql_num_rows($query_id) : false;
		}

		function sql_affectedrows()
		{
			$this->sql_start_time = $this->sql_set_start_time();

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($this->db_connect_id) ? @mysql_affected_rows($this->db_connect_id) : false;
		}

		function sql_numfields($query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($query_id) ? @mysql_num_fields($query_id) : false;
		}

		function sql_fieldname($offset, $query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($query_id) ? @mysql_field_name($query_id, $offset) : false;
		}

		function sql_fieldtype($offset, $query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($query_id) ? @mysql_field_type($query_id, $offset) : false;
		}

		function sql_fetchrow($query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();
			if(($query_id === 'cache') && $this->cached)
			{
				$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
				return count($this->cache) ? array_shift($this->cache) : false;
			}

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if($query_id)
			{
				$this->row[$query_id] = @mysql_fetch_array($query_id, MYSQL_ASSOC);
				if($this->caching)
				{
					if($this->row[$query_id] === false)
					{
						$this->write_cache();
					}
					$this->cache[] = $this->row[$query_id];
				}

				$result = $this->row[$query_id];
			}
			else
			{
				$result = false;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
			return $result;
		}

		function sql_fetchrowset($query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();
			if(($query_id === 'cache') && $this->cached)
			{
				$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
				return $this->cache;
			}
			$result = false;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if($query_id)
			{
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);

				while($this->rowset[$query_id] = @mysql_fetch_array($query_id, MYSQL_ASSOC))
				{
					if($this->caching)
					{
						if($this->row[$query_id] === false)
						{
							$this->write_cache();
						}
						$this->cache[] = $this->row[$query_id];
					}
					if($this->caching)
					{
						$this->write_cache();
					}

					$result[] = $this->rowset[$query_id];
				}
			}
			else
			{
				$result = false;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
			return $result;
		}

		function sql_fetchfield($field, $rownum = -1, $query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();

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
							$result = $this->rowset[$query_id][0][$field];
						}
						elseif($this->row[$query_id])
						{
							$result = $this->row[$query_id][$field];
						}
					}
				}
			}
			else
			{
				$result = false;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
			return $result;
		}

		function sql_rowseek($rownum, $query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($query_id) ? @mysql_data_seek($query_id, $rownum) : false;
		}

		function sql_nextid()
		{
			$this->sql_start_time = $this->sql_set_start_time();

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;

			return ($this->db_connect_id) ? @mysql_insert_id($this->db_connect_id) : false;
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

		/**
		* Build an SQL LIKE expression
		*/
		function sql_like_expression($expression)
		{
			$expression = str_replace(array('_', '%'), array("\_", "\%"), $expression);
			$expression = str_replace(array(chr(0) . "\_", chr(0) . "\%"), array('_', '%'), $expression);
			$like_expression = ('LIKE \'' . $this->sql_escape($expression) . '\'');
			return $like_expression;
		}

		function sql_freeresult($query_id = 0)
		{
			$this->sql_start_time = $this->sql_set_start_time();

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

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if ($query_id)
			{
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);

				@mysql_free_result($query_id);

				$result = true;
			}
			else
			{
				$result = false;
			}

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
			return $result;
		}

		function sql_error()
		{
			$this->sql_start_time = $this->sql_set_start_time();

			$result['message'] = @mysql_error($this->db_connect_id);
			$result['code'] = @mysql_errno($this->db_connect_id);

			$this->sql_time += $this->sql_set_end_time() - $this->sql_start_time;
			return $result;
		}

		function write_cache()
		{
			if(!$this->caching)
			{
				return;
			}
			$this->cache_folder = (empty($this->cache_folder) ? SQL_CACHE_FOLDER : $this->cache_folder);
			$this->cache_folder = ((@is_dir($this->cache_folder)) ? $this->cache_folder : @phpbb_realpath($this->cache_folder));
			$cache_file_name = $this->cache_folder . 'sql_' . $this->caching . '.php';
			@unlink($cache_file_name);
			$f = fopen($cache_file_name, 'w');
			@flock($f, LOCK_EX);
			$data = var_export($this->cache, true);
			//$f_content = '<' . '?php' . "\n" . '$sql_time_c = \'' . time() . '\';' . "\n\n" . '$sql_string_c = \'' . addslashes($this->query_string) . '\';' . "\n\n" . '$set = ' . $data . ';' . "\n" . 'return;' . "\n" . '?' . '>';
			$f_content = '<' . '?php' . "\n";
			$f_content .= '/* SQL: ' . str_replace('*/', '*\/', $this->query_string) . ' */' . "\n\n";
			$f_content .= '/* UNIX TIME: ' . time() . ' */' . "\n\n";
			$f_content .= '/* TIME: ' . date('Y/m/d - H:i:s') . ' */' . "\n\n";
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
			$this->cache_folder = ((@is_dir($this->cache_folder)) ? $this->cache_folder : @phpbb_realpath($this->cache_folder));
			$res = opendir($this->cache_folder);
			if($res)
			{
				$files_counter = 0;
				while(($file = readdir($res)) !== false)
				{
					if(!@is_dir($file) && substr($file, 0, $prefix_len) === $prefix)
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

		/**
		* Explain queries
		*/
		function sql_report($mode, $query = '')
		{
			if (empty($_REQUEST['explain']))
			{
				return false;
			}

			if (!$query && $this->query_hold != '')
			{
				$query = $this->query_hold;
			}

			switch ($mode)
			{
				case 'display':
					$this->sql_close();
					$mtime = explode(' ', microtime());
					$totaltime = $mtime[0] + $mtime[1] - $this->sql_start_time;
					echo ('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<link rel="stylesheet" href="' . IP_ROOT_PATH . 'templates/common/acp.css" type="text/css" />
	<meta name="author" content="Mighty Gorgon" />
	<title>Icy Phoenix</title>
	<!--[if lt IE 7]>
	<script type="text/javascript" src="' . IP_ROOT_PATH . 'templates/common/js/pngfix.js"></script>
	<![endif]-->
</head>

<body>
<a name="top"></a>
<div id="global-wrapper" style="width: 960px; clear: both; margin: 0 auto;">
<div class="leftshadow"><div class="rightshadow"><div id="wrapper-inner">
<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" colspan="3" valign="top">
	<div id="top_logo">
	<table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
	<td height="150" align="left" valign="middle">
		<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="' . IP_ROOT_PATH . 'images/logo_ip.png" alt="Icy Phoenix" title="Icy Phoenix" border="0" /></a>
	</td>
	</tr>
	</table>
	</div>
	</td>
</tr>
<tr><td colspan="3" class="forum-buttons" valign="middle">Icy Phoenix Extra Debug</td></tr>
<tr>
	<td colspan="3" id="content">
	<div class="post-text">
		<br />
		<h1>SQL Report</h1>
		<br />
		<p><b>Page generated in ' . round($totaltime, 4) . ' seconds with ' . $this->num_queries . ' queries' . '</b></p>
		<p>Time spent on ' . $this->num_queries . ' queries: <b>' . round($this->sql_time, 5) . 's</b></p>
		<p>Time spent on PHP: <b>' . round($totaltime - $this->sql_time, 5) . 's</b></p>
		<br /><br />
		' . $this->sql_report . '
	</div>
	</td>
</tr>
<tr>
	<td width="100%" colspan="3">
	<div id="bottom_logo_ext">
	<div id="bottom_logo">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td nowrap="nowrap" width="45%" align="left">
					<br /><span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></span><br /><br />
				</td>
				<td nowrap="nowrap" align="center"><div style="text-align:center;">&nbsp;</div></td>
				<td nowrap="nowrap" width="45%" align="right">
					<br /><span class="copyright">Design by <a href="http://www.mightygorgon.com" target="_blank">Mighty Gorgon</a>&nbsp;</span><br /><br />
				</td>
			</tr>
		</table>
	</div>
	</div>
	</td>
</tr>
</table>
</div></div></div>
</div>
</body>
</html>
');
					exit;
					break;

				case 'stop':
					if (empty($this->num_queries))
					{
						$this->num_queries = 1;
					}
					else
					{
						$this->num_queries++;
					}
					$endtime = explode(' ', microtime());
					$endtime = $endtime[0] + $endtime[1];
					$this->sql_report .= '
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<thead>
<tr><th>Query #' . $this->num_queries . '</th></tr>
</thead>
<tbody>
<tr>
	<td class="row1"><textarea style="font-family:\'Courier New\',monospace;width:99%" rows="8" cols="160">' . preg_replace('/\t(AND|OR)(\W)/', "\$1\$2", htmlspecialchars(preg_replace('/[\s]*[\n\r\t]+[\n\r\s\t]*/', "\n", $query))) . '</textarea></td>
</tr>
</tbody>
</table>
<p class="helpline" style="padding:2px;">
					';
					if ($this->query_result)
					{
						$this->sql_report .= 'Elapsed: <b style="color:#224488;">' . sprintf('%.5f', $endtime - $this->curtime) . 's</b> &bull; [Before: ' . sprintf('%.5f', $this->curtime - $this->sql_start_time) . 's | After: ' . sprintf('%.5f', $endtime - $this->sql_start_time) . 's]';
						if (preg_match('/^(UPDATE|DELETE|REPLACE)/', $query))
						{
							$this->sql_report .= ' - [Affected rows: <b style="color:#224488;">' . $this->sql_affectedrows($this->query_result) . '</b>]';
						}
					}
					elseif ($this->cached == true)
					{
						$this->sql_report .= '<b style="color:#228822;">FROM CACHE</b>';
						$this->sql_report .= ' ==> Elapsed: <b style="color:#224488;">' . sprintf('%.5f', $endtime - $this->curtime) . 's</b> &bull; [Before: ' . sprintf('%.5f', $this->curtime - $this->sql_start_time) . 's | After: ' . sprintf('%.5f', $endtime - $this->sql_start_time) . 's]';
					}
					else
					{
						$error = $this->sql_error();
						$this->sql_report .= '<b style="color:#cc3333;">FAILED</b> - ' . $this->sql_layer . ' Error ' . $error['code'] . ': ' . htmlspecialchars($error['message']);
					}
					$this->sql_report .= '</p><br /><br />';
					$this->sql_time += $endtime - $this->curtime;
				break;

				case 'start':
					$this->query_hold = $query;
					$this->curtime = explode(' ', microtime());
					$this->curtime = $this->curtime[0] + $this->curtime[1];
				break;

				default:
				break;
			}

			return true;
		}

	} // class sql_db

} // if ... define

?>