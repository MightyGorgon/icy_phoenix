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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}


/**
* Cache management class
*/
class acm
{
	var $vars = array();
	var $var_expires = array();
	var $is_modified = false;

	var $sql_rowset = array();
	var $sql_row_pointer = array();
	var $cache_dir = '';

	/**
	* Set cache path
	*/
	function acm()
	{
		$this->cache_dir = MAIN_CACHE_FOLDER;
	}

	/**
	* Load global cache
	*/
	function load()
	{
		if (file_exists($this->cache_dir . 'data_global.' . PHP_EXT))
		{
			@include($this->cache_dir . 'data_global.' . PHP_EXT);
		}
		else
		{
			return false;
		}

		return true;
	}

	/**
	* Unload cache object
	*/
	function unload()
	{
		$this->save();
		unset($this->vars);
		unset($this->var_expires);
		unset($this->sql_rowset);
		unset($this->sql_row_pointer);

		$this->vars = array();
		$this->var_expires = array();
		$this->sql_rowset = array();
		$this->sql_row_pointer = array();
	}

	/**
	* Save modified objects
	*/
	function save()
	{
		if (!$this->is_modified)
		{
			return;
		}

		if ($fp = @fopen($this->cache_dir . 'data_global.' . PHP_EXT, 'wb'))
		{
			@flock($fp, LOCK_EX);

			$file_content = "<" . "?php\nif (!defined('IN_ICYPHOENIX')) exit;\n\n";
			$file_content .= "\$created = " . time() . "; // " . gmdate('Y/m/d - H:i:s') . "\n";
			$file_content .= "\n\$this->vars = " . var_export($this->vars, true) . ";\n\n\$this->var_expires = " . var_export($this->var_expires, true) . ";\n";
			$file_content .= "\n?" . ">";

			fwrite($fp, $file_content);
			@flock($fp, LOCK_UN);
			fclose($fp);

			if (!function_exists('phpbb_chmod'))
			{
				include(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
			}

			phpbb_chmod($this->cache_dir . 'data_global.' . PHP_EXT, CHMOD_WRITE);
		}
		else
		{
			// Now, this occurred how often? ... phew, just tell the user then...
			if (!@is_writable($this->cache_dir))
			{
				trigger_error($this->cache_dir . ' is NOT writable.', E_USER_ERROR);
			}

			trigger_error('Not able to open ' . $this->cache_dir . 'data_global.' . PHP_EXT, E_USER_ERROR);
		}

		$this->is_modified = false;
	}

	/**
	* Tidy cache
	*/
	function tidy()
	{
		$dir = @opendir($this->cache_dir);

		if (!$dir)
		{
			return;
		}

		while (($entry = readdir($dir)) !== false)
		{
			if (!preg_match('/^(sql_|data_(?!global))/', $entry))
			{
				continue;
			}

			$expired = true;
			@include($this->cache_dir . $entry);
			if ($expired)
			{
				$this->remove_file($this->cache_dir, $entry);
			}
		}
		closedir($dir);

		if (file_exists($this->cache_dir . 'data_global.' . PHP_EXT))
		{
			if (!sizeof($this->vars))
			{
				$this->load();
			}

			foreach ($this->var_expires as $var_name => $expires)
			{
				if (time() > $expires)
				{
					$this->destroy($var_name);
				}
			}
		}

		set_config('cron_cache_last_run', time());
	}

	/**
	* Get saved cache object
	*/
	function get($var_name)
	{
		if ($var_name[0] == '_')
		{
			if (!$this->_exists($var_name))
			{
				return false;
			}

			@include($this->cache_dir . "data{$var_name}." . PHP_EXT);
			return (isset($data)) ? $data : false;
		}
		else
		{
			return ($this->_exists($var_name)) ? $this->vars[$var_name] : false;
		}
	}

	/**
	* Put data into cache
	*/
	function put($var_name, $var, $ttl = 31536000)
	{
		if ($var_name[0] == '_')
		{
			if ($fp = @fopen($this->cache_dir . "data{$var_name}." . PHP_EXT, 'wb'))
			{
				@flock($fp, LOCK_EX);

				$file_content = "<" . "?php\nif (!defined('IN_ICYPHOENIX')) exit;\n\n";
				$file_content .= "\$created = " . time() . "; // " . gmdate('Y/m/d - H:i:s') . "\n";
				$file_content .= "\$expired = (time() > " . (time() + $ttl) . ") ? true : false;\nif (\$expired) { return; }\n";
				$file_content .= "\n\$data = " . (sizeof($var) ? "unserialize(" . var_export(serialize($var), true) . ");" : 'array();') . "\n";
				$file_content .= "\n?" . ">";

				fwrite($fp, $file_content);
				@flock($fp, LOCK_UN);
				fclose($fp);

				if (!function_exists('phpbb_chmod'))
				{
					include(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
				}

				phpbb_chmod($this->cache_dir . "data{$var_name}." . PHP_EXT, CHMOD_WRITE);
			}
		}
		else
		{
			$this->vars[$var_name] = $var;
			$this->var_expires[$var_name] = time() + $ttl;
			$this->is_modified = true;
		}
	}

	/**
	* Purge cache data
	*/
	function purge()
	{
		// Purge all phpbb cache files
		$dir = @opendir($this->cache_dir);

		if (!$dir)
		{
			return;
		}

		while (($entry = readdir($dir)) !== false)
		{
			if ((strpos($entry, 'sql_') !== 0) && (strpos($entry, 'data_') !== 0) && (strpos($entry, 'ctpl_') !== 0) && (strpos($entry, 'tpl_') !== 0))
			{
				continue;
			}

			$this->remove_file($this->cache_dir, $entry);
		}
		closedir($dir);

		unset($this->vars);
		unset($this->var_expires);
		unset($this->sql_rowset);
		unset($this->sql_row_pointer);

		$this->vars = array();
		$this->var_expires = array();
		$this->sql_rowset = array();
		$this->sql_row_pointer = array();

		$this->is_modified = false;
	}

	/**
	* Destroy cache data
	*/
	function destroy($var_name, $table = '')
	{
		if (($var_name == 'sql') && !empty($table))
		{
			if (!is_array($table))
			{
				$table = array($table);
			}

			$dir = @opendir($this->cache_dir);

			if (!$dir)
			{
				return;
			}

			while (($entry = readdir($dir)) !== false)
			{
				if (strpos($entry, 'sql_') !== 0)
				{
					continue;
				}

				// The following method is more failproof than simply assuming the query is on line 3 (which it should be)
				$check_line = @file_get_contents($this->cache_dir . $entry);

				if (empty($check_line))
				{
					continue;
				}

				// Now get the contents between /* and */
				$check_line = substr($check_line, strpos($check_line, '/* ') + 3, strpos($check_line, ' */') - strpos($check_line, '/* ') - 3);

				$found = false;
				foreach ($table as $check_table)
				{
					// Better catch partial table names than no table names. ;)
					if (strpos($check_line, $check_table) !== false)
					{
						$found = true;
						break;
					}
				}

				if ($found)
				{
					$this->remove_file($this->cache_dir, $entry);
				}
			}
			closedir($dir);

			return;
		}

		if (!$this->_exists($var_name))
		{
			return;
		}

		if ($var_name[0] == '_')
		{
			$this->remove_file($this->cache_dir, 'data' . $var_name . '.' . PHP_EXT, true);
		}
		elseif (isset($this->vars[$var_name]))
		{
			$this->is_modified = true;
			unset($this->vars[$var_name]);
			unset($this->var_expires[$var_name]);

			// We save here to let the following cache hits succeed
			$this->save();
		}
	}

	/**
	* Destroy cache data files
	*/
	function destroy_datafiles($datafiles, $cache_folder = '', $prefix = 'data', $prefix_lookup = false)
	{
		$deleted = 0;
		if (empty($datafiles))
		{
			return $deleted;
		}

		$cache_dirs_array = array(MAIN_CACHE_FOLDER, CMS_CACHE_FOLDER, FORUMS_CACHE_FOLDER, POSTS_CACHE_FOLDER, SQL_CACHE_FOLDER, TOPICS_CACHE_FOLDER, USERS_CACHE_FOLDER);
		$cache_folder = (!empty($cache_folder) && @is_dir($cache_folder) && in_array($cache_folder, $cache_dirs_array)) ? $cache_folder : $this->cache_dir;
		$datafiles = !is_array($datafiles) ? array($datafiles) : $datafiles;

		if (!$prefix_lookup)
		{
			foreach ($datafiles as $datafile)
			{
				$file_deleted = $this->remove_file($cache_folder, $prefix . $datafile . '.' . PHP_EXT, false);
				$deleted = $file_deleted ? $deleted++ : $deleted;
			}
		}
		else
		{
			$dir = @opendir($cache_folder);

			if (!$dir)
			{
				return;
			}

			while (($entry = readdir($dir)) !== false)
			{
				foreach ($datafiles as $datafile)
				{
					if ((strpos($entry, $prefix . $datafile) === 0) && (substr($entry, -(strlen(PHP_EXT) + 1)) === ('.' . PHP_EXT)))
					{
						$file_deleted = $this->remove_file($cache_folder, $entry, false);
						$deleted = $file_deleted ? $deleted++ : $deleted;
						break;
					}
				}
			}
		}

		return $deleted;
	}

	/**
	* Check if a given cache entry exist
	*/
	function _exists($var_name)
	{
		if ($var_name[0] == '_')
		{
			return file_exists($this->cache_dir . 'data' . $var_name . '.' . PHP_EXT);
		}
		else
		{
			if (!sizeof($this->vars))
			{
				$this->load();
			}

			if (!isset($this->var_expires[$var_name]))
			{
				return false;
			}

			return (time() > $this->var_expires[$var_name]) ? false : isset($this->vars[$var_name]);
		}
	}

	/**
	* Build query Hash
	*/
	function sql_query_hash($query = '')
	{
		return md5($query);
	}

	/**
	* Build query hash filename
	*/
	function sql_query_hash_file($query = '', $cache_prefix = '', $cache_folder = SQL_CACHE_FOLDER)
	{
		$cache_folder = !empty($cache_folder) ? $cache_folder : SQL_CACHE_FOLDER;
		$hash = $this->sql_query_hash($query);
		$cache_filename = $cache_folder . $cache_prefix . $hash . '.' . PHP_EXT;
		return $cache_filename;
	}

	/**
	* Load cached sql query
	*/
	function sql_load($query, $cache_prefix = '', $cache_folder = SQL_CACHE_FOLDER)
	{

		$cache_prefix = 'sql_' . $cache_prefix;
		$cache_folder = !empty($cache_folder) ? $cache_folder : SQL_CACHE_FOLDER;
		// The code below should ensures that a correct folder is identified... but maybe it is better avoid extra checks to file system to save CPU and disk charge
		/*
		$cache_folder = (!empty($cache_folder) && @is_dir($cache_folder)) ? $cache_folder : SQL_CACHE_FOLDER;
		$cache_folder = ((@is_dir($cache_folder)) ? $cache_folder : @phpbb_realpath($cache_folder));
		*/

		// Remove extra spaces and tabs
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);
		$query_id = sizeof($this->sql_rowset);
		$cache_filename = $this->sql_query_hash_file($query, $cache_prefix, $cache_folder);

		if (!file_exists($cache_filename))
		{
			return false;
		}

		@include($cache_filename);

		if (!isset($expired))
		{
			return false;
		}
		elseif ($expired)
		{
			$this->remove_file($cache_folder, $cache_prefix . md5($query) . '.' . PHP_EXT, true);
			return false;
		}

		$this->sql_row_pointer[$query_id] = 0;

		return $query_id;
	}

	/**
	* Save sql query
	*/
	function sql_save($query, &$query_result, $ttl = CACHE_SQL_EXPIRY, $cache_prefix = '', $cache_folder = SQL_CACHE_FOLDER)
	{
		global $db;

		$cache_prefix = 'sql_' . $cache_prefix;
		$cache_folder = (!empty($cache_folder) && @is_dir($cache_folder)) ? $cache_folder : SQL_CACHE_FOLDER;
		$cache_folder = ((@is_dir($cache_folder)) ? $cache_folder : @phpbb_realpath($cache_folder));

		// Remove extra spaces and tabs
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);
		$cache_filename = $this->sql_query_hash_file($query, $cache_prefix, $cache_folder);

		if ($fp = @fopen($cache_filename, 'wb'))
		{
			@flock($fp, LOCK_EX);

			$query_id = sizeof($this->sql_rowset);
			$this->sql_rowset[$query_id] = array();
			$this->sql_row_pointer[$query_id] = 0;

			while ($row = $db->sql_fetchrow($query_result))
			{
				$this->sql_rowset[$query_id][] = $row;
			}
			$db->sql_freeresult($query_result);

			$file_content = "<" . "?php\nif (!defined('IN_ICYPHOENIX')) exit;\n\n";
			$file_content .= "/* " . str_replace('*/', '*\/', $query) . " */\n";
			$file_content .= "\$created = " . time() . "; // " . gmdate('Y/m/d - H:i:s') . "\n";
			$file_content .= "\$expired = (time() > " . (time() + $ttl) . ") ? true : false;\nif (\$expired) { return; }\n";
			$file_content .= "\n\$this->sql_rowset[\$query_id] = " . (sizeof($this->sql_rowset[$query_id]) ? "unserialize(" . var_export(serialize($this->sql_rowset[$query_id]), true) . ");" : 'array();') . "\n";
			$file_content .= "\n?" . ">";

			fwrite($fp, $file_content);
			@flock($fp, LOCK_UN);
			fclose($fp);

			if (!function_exists('phpbb_chmod'))
			{
				include(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
			}

			phpbb_chmod($cache_filename, CHMOD_WRITE);

			$query_result = $query_id;
		}
	}

	/**
	* Ceck if a given sql query exist in cache
	*/
	function sql_exists($query_id)
	{
		return isset($this->sql_rowset[$query_id]);
	}

	/**
	* Fetch row from cache (database)
	*/
	function sql_fetchrow($query_id)
	{
		if ($this->sql_row_pointer[$query_id] < sizeof($this->sql_rowset[$query_id]))
		{
			return $this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]++];
		}

		return false;
	}

	/**
	* Fetch a field from the current row of a cached database result (database)
	*/
	function sql_fetchfield($query_id, $field)
	{
		if ($this->sql_row_pointer[$query_id] < sizeof($this->sql_rowset[$query_id]))
		{
			return (isset($this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]][$field])) ? $this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]][$field] : false;
		}

		return false;
	}

	/**
	* Seek a specific row in an a cached database result (database)
	*/
	function sql_rowseek($rownum, $query_id)
	{
		if ($rownum >= sizeof($this->sql_rowset[$query_id]))
		{
			return false;
		}

		$this->sql_row_pointer[$query_id] = $rownum;
		return true;
	}

	/**
	* Free memory used for a cached database result (database)
	*/
	function sql_freeresult($query_id)
	{
		if (!isset($this->sql_rowset[$query_id]))
		{
			return false;
		}

		unset($this->sql_rowset[$query_id]);
		unset($this->sql_row_pointer[$query_id]);

		return true;
	}

	/**
	* Removes/unlinks file
	*/
	function remove_file($cache_folder, $filename, $check = false)
	{
		$cache_folder = !empty($cache_folder) ? $cache_folder : MAIN_CACHE_FOLDER;
		// The code below should ensures that a correct folder is identified... but maybe it is better avoid extra checks to file system to save CPU and disk charge
		/*
		$cache_folder = (!empty($cache_folder) && @is_dir($cache_folder)) ? $cache_folder : MAIN_CACHE_FOLDER;
		$cache_folder = ((@is_dir($cache_folder)) ? $cache_folder : @phpbb_realpath($cache_folder));
		*/

		if ($check && !@is_writable($cache_folder))
		{
			// E_USER_ERROR - not using language entry - intended.
			trigger_error('Unable to remove files within ' . $cache_folder . '. Please check directory permissions.', E_USER_ERROR);
		}

		return @unlink($cache_folder . $filename);
	}
}

?>