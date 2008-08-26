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
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
		var $in_transaction = 0;
		var $caching = false;
		var $cached = false;
		var $cache = array();
		//
		// Constructor
		//
		function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
			$this->persistency = $persistency;
			$this->user = $sqluser;
			$this->password = $sqlpassword;
			$this->server = $sqlserver;
			$this->dbname = $database;

			$this->db_connect_id = ($this->persistency) ? mysql_pconnect($this->server, $this->user, $this->password) : mysql_connect($this->server, $this->user, $this->password);

			if( $this->db_connect_id )
			{
				if( $database != "" )
				{
					$this->dbname = $database;
					$dbselect = mysql_select_db($this->dbname);

					if( !$dbselect )
					{
						mysql_close($this->db_connect_id);
						$this->db_connect_id = $dbselect;
					}
				}

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return $this->db_connect_id;
			}
			else
			{
				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		//
		// Other base methods
		//
		function sql_close()
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if( $this->db_connect_id )
			{
				//
				// Commit any remaining transactions
				//
				if( $this->in_transaction )
				{
					mysql_query('COMMIT', $this->db_connect_id);
				}

				return mysql_close($this->db_connect_id);

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		//
		// Base query method
		//
		function sql_query($query = '', $transaction = false, $cache = false)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			// Mighty Gorgon - Extra Debug - BEGIN
			if ( defined('DEBUG_EXTRA') && ($query != ''))
			{
				$this->sql_report('start', $query);
			}
			// Mighty Gorgon - Extra Debug - END

			// Mighty Gorgon - Extra Debug - BEGIN
			if ( CACHE_SQL == false)
			{
				$cache = false;
			}
			// Mighty Gorgon - Extra Debug - END

			// Remove any pre-existing queries
			unset($this->query_result);
			// Check cache
			$this->query_string = $query;
			$this->caching = false;
			$this->cache = array();
			$this->cached = false;
			if( ($query !== '') && $cache)
			{
				global $phpbb_root_path;
				$hash = md5($query);
				if(strlen($cache))
				{
					$hash = $cache . $hash;
				}
				$filename = $phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_' . $hash . '.php';
				if(@file_exists($filename))
				{
					$set = array();
					include($filename);
					$this->cache = $set;
					$this->cached = true;
					$this->caching = false;
					// Mighty Gorgon - Extra Debug - BEGIN
					if (defined('DEBUG_EXTRA'))
					{
						$mtime = microtime();
						$mtime = explode(' ', $mtime);
						$mtime = $mtime[1] + $mtime[0];
						$endtime = $mtime;

						$this->sql_time += $endtime - $starttime;

						$this->sql_report('stop', $query);
					}
					// Mighty Gorgon - Extra Debug - END
					return 'cache';
				}
			// echo 'cache is missing: ', $filename, '<br />';
				$this->caching = $hash;
			}
			// not cached
			//echo 'sql: ', htmlspecialchars($query), '<br />';

			// Mighty Gorgon - Debug SQL Cache - BEGIN
			// Cache SQL in the same file plus underscore
			if (defined('DEBUG_EXTRA_LOG'))
			{
				/*
				$f = fopen($phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_' . $hash . '_.php', 'w');
				@fputs($f, '\'' . $query . '\'');
				@fclose($f);
				*/
				// Cache SQL history in a file
				if ( !defined('IN_ADMIN') )
				{
					$f = fopen($phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_history.php', 'a+');
					@fputs($f, date('Y/m/d - H:i:s') . ' => ' . $hash . "\n\n" . $query . "\n\n\n=========================\n\n");
					@fclose($f);
				}
			}
			// Mighty Gorgon - Debug SQL Cache - END

			if( $query != '' )
			{
				$this->num_queries++;
				if( $transaction == BEGIN_TRANSACTION && !$this->in_transaction )
				{
					$result = mysql_query('BEGIN', $this->db_connect_id);
					if(!$result)
					{
						return false;
					}
					$this->in_transaction = true;
				}

				$this->query_result = mysql_query($query, $this->db_connect_id);
			}
			else
			{
				if( $transaction == END_TRANSACTION && $this->in_transaction )
				{
					$result = mysql_query('COMMIT', $this->db_connect_id);
				}
			}

			if( $this->query_result )
			{
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);

				if( $transaction == END_TRANSACTION && $this->in_transaction )
				{
					$this->in_transaction = false;

					if ( !mysql_query('COMMIT', $this->db_connect_id) )
					{
						mysql_query('ROLLBACK', $this->db_connect_id);
						return false;
					}
				}

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

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
				if( $this->in_transaction )
				{
					mysql_query('ROLLBACK', $this->db_connect_id);
					$this->in_transaction = false;
				}

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				// Mighty Gorgon - Extra Debug - BEGIN
				if (defined('DEBUG_EXTRA'))
				{
					$this->sql_report('stop', $query);
				}
				// Mighty Gorgon - Extra Debug - END

				return false;
			}
		}

		//
		// Other query methods
		//
		function sql_numrows($query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
			if($query_id === 'cache' && $this->cached)
			{
				return count($this->cache);
			}

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ($query_id) ? mysql_num_rows($query_id) : false;
		}

		function sql_affectedrows()
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ( $this->db_connect_id ) ? mysql_affected_rows($this->db_connect_id) : false;
		}

		function sql_numfields($query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ($query_id) ? mysql_num_fields($query_id) : false;
		}

		function sql_fieldname($offset, $query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ($query_id) ? mysql_field_name($query_id, $offset) : false;
		}

		function sql_fieldtype($offset, $query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ($query_id) ? mysql_field_type($query_id, $offset) : false;
		}

		function sql_fetchrow($query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
			if($query_id === 'cache' && $this->cached)
			{
				return count($this->cache) ? array_shift($this->cache) : false;
			}

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if($query_id)
			{
				$this->row[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC);
				if($this->caching)
				{
					if($this->row[$query_id] === false)
					{
						$this->write_cache();
					}
					$this->cache[] = $this->row[$query_id];
				}

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return $this->row[$query_id];
			}
			else
			{
				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;
				return false;
			}
		}

		function sql_fetchrowset($query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
			if($query_id === 'cache' && $this->cached)
			{
				return $this->cache;
			}

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if($query_id)
			{
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);

				while($this->rowset[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC))
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

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_fetchfield($field, $rownum = -1, $query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if($query_id)
			{
				if( $rownum > -1 )
				{
					$result = mysql_result($query_id, $rownum, $field);
				}
				else
				{
					if( empty($this->row[$query_id]) && empty($this->rowset[$query_id]) )
					{
						if( $this->sql_fetchrow() )
						{
							$result = $this->row[$query_id][$field];
						}
					}
					else
					{
						if( $this->rowset[$query_id] )
						{
							$result = $this->rowset[$query_id][0][$field];
						}
						else if( $this->row[$query_id] )
						{
							$result = $this->row[$query_id][$field];
						}
					}
				}

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return $result;
			}
			else
			{
				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_rowseek($rownum, $query_id = 0)
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ($query_id) ? mysql_data_seek($query_id, $rownum) : false;
		}

		function sql_nextid()
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return ( $this->db_connect_id ) ? mysql_insert_id($this->db_connect_id) : false;
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

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if(!$query_id)
			{
				$query_id = $this->query_result;
			}

			if ($query_id)
			{
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);

				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return true;
			}
			else
			{
				$mtime = microtime();
				$mtime = explode(' ', $mtime);
				$mtime = $mtime[1] + $mtime[0];
				$endtime = $mtime;

				$this->sql_time += $endtime - $starttime;

				return false;
			}
		}

		function sql_error()
		{
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			$result['message'] = mysql_error($this->db_connect_id);
			$result['code'] = mysql_errno($this->db_connect_id);

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;

			$this->sql_time += $endtime - $starttime;

			return $result;
		}

		function write_cache()
		{
			if(!$this->caching)
			{
				return;
			}
			global $phpbb_root_path;
			@unlink($phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_' . $this->caching . '.php');
			$f = fopen($phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_' . $this->caching . '.php', 'w');
			$data = var_export($this->cache, true);
			//$time_check = "\n" . '$expired = (time() > ' . (time() + 86400) . ') ? true : false;' . "\n" . 'if ($expired) { return; }' . "\n\n";
			//$f_content = '<' . '?php' . "\n" . '$sql_time_c = \'' . time() . '\';' . "\n\n" . '$sql_string_c = \'' . addslashes($this->query_string) . '\';' . "\n\n" . '$set = ' . $data . ';' . "\n" . 'return;' . "\n" . '?' . '>';
			$f_content = '<' . '?php' . "\n" . '$set = ' . $data . ';' . "\n" . 'return;' . "\n" . '?' . '>';
			@fputs($f, $f_content);
			@fclose($f);
			//@chmod($phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_' . $this->caching . '.php', 0775);
			@chmod($phpbb_root_path . MAIN_CACHE_FOLDER . 'sql_' . $this->caching . '.php', 0777);
			$this->caching = false;
			$this->cached = false;
			$this->cache = array();
		}

		function clear_cache($prefix = '')
		{
			global $phpbb_root_path;
			$this->caching = false;
			$this->cached = false;
			$this->cache = array();
			$prefix = 'sql_' . $prefix;
			$prefix_len = strlen($prefix);
			$res = opendir($phpbb_root_path . 'cache');
			if($res)
			{
				while(($file = readdir($res)) !== false)
				{
					if(substr($file, 0, $prefix_len) === $prefix)
					{
						@unlink($phpbb_root_path . MAIN_CACHE_FOLDER . $file);
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
			global $starttime, $phpbb_root_path;

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
					$totaltime = $mtime[0] + $mtime[1] - $starttime;
					echo ('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<link rel="stylesheet" href="' . $phpbb_root_path . 'templates/common/acp.css" type="text/css" />
	<meta name="author" content="Mighty Gorgon" />
	<title>Icy Phoenix</title>
	<!--[if lt IE 7]>
	<script type="text/javascript" src="' . $phpbb_root_path . 'templates/common/js/pngfix.js"></script>
	<![endif]-->
</head>

<body>
<a name="top"></a>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
	<tr>
		<td class="leftshadow" width="9" valign="top"><img src="' . $phpbb_root_path . 'images/spacer.gif" alt="" width="9" height="1" /></td>
		<td width="100%" valign="top">
<div style="text-align:center;">
<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" colspan="3" valign="top">
	<div id="top_logo">
	<table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
	<td height="150" align="left" valign="middle">
		<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="' . $phpbb_root_path . 'images/logo_ip.png" alt="Icy Phoenix" title="Icy Phoenix" border="0" /></a>
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
</div>
		</td>
		<td class="rightshadow" width="9" valign="top"><img src="' . $phpbb_root_path . 'images/spacer.gif" alt="" width="9" height="1" /></td>
	</tr>
</table>
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
						$this->sql_report .= 'Elapsed: <b style="color:#224488;">' . sprintf('%.5f', $endtime - $this->curtime) . 's</b> &bull; [Before: ' . sprintf('%.5f', $this->curtime - $starttime) . 's | After: ' . sprintf('%.5f', $endtime - $starttime) . 's]';
						if (preg_match('/^(UPDATE|DELETE|REPLACE)/', $query))
						{
							$this->sql_report .= ' - [Affected rows: <b style="color:#224488;">' . $this->sql_affectedrows($this->query_result) . '</b>]';
						}
					}
					elseif ($this->cached == true)
					{
						$this->sql_report .= '<b style="color:#228844;">FROM CACHE</b>';
						$this->sql_report .= ' ==> Elapsed: <b style="color:#224488;">' . sprintf('%.5f', $endtime - $this->curtime) . 's</b> &bull; [Before: ' . sprintf('%.5f', $this->curtime - $starttime) . 's | After: ' . sprintf('%.5f', $endtime - $starttime) . 's]';
					}
					else
					{
						$error = $this->sql_error();
						$this->sql_report .= '<b style="color:#CC3333;">FAILED</b> - ' . $this->sql_layer . ' Error ' . $error['code'] . ': ' . htmlspecialchars($error['message']);
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