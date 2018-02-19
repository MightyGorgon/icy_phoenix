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
* We store mysqli results as keys in SplObjectStorage / DbObjectStorage, but those need keys to be objects.
* When pulled out from cache, however, we do not have an object, so we create a fake ID using this class.
*/
class sql_db_fake_id
{
	public $id;

	public function __construct($id)
	{
		$this->id = $id;
	}
}

/**
* This class implements a key-value store based on SplObjectStorage,
* but special-cases sql_db_fake_id to compare equal if same ID (in getHash).
*
* sql_db_fake_id is necessary because mysqli queries return objects, not resources like mysql does.
*/
class DbObjectStorage extends SplObjectStorage
{
	public function getHash($o)
	{
		if ($o instanceof sql_db_fake_id)
		{
			return 'sql_db_fake_id:' . $o->id;
		}
		else
		{
			return parent::getHash($o);
		}
	}
}


/**
* Cache management class
*/
class acm
{
	var $vars = array();
	var $var_expires = array();
	var $is_modified = false;

	var $sql_rowset;
	var $sql_row_pointer;
	var $sql_query_id = '';
	var $cache_dir = '';
	var $cache_dir_sql = '';
	var $cache_dir_backup = '';
	var $cache_dirs = array();
	var $last_query_id = 1;

	var $use_old_ip_cache = false;

	/**
	* Set cache path
	*/
	function acm()
	{
		$this->cache_dir = defined('MAIN_CACHE_FOLDER') ? MAIN_CACHE_FOLDER : 'cache/';
		$this->cache_dir_sql = defined('SQL_CACHE_FOLDER') ? SQL_CACHE_FOLDER : 'cache/sql/';
		$this->cache_dir_backup = $this->cache_dir;

		$this->sql_rowset = new DbObjectStorage();
		$this->sql_row_pointer = new DbObjectStorage();

		$this->cache_dirs = defined('MAIN_CACHE_FOLDER') ? array(MAIN_CACHE_FOLDER, CMS_CACHE_FOLDER, FORUMS_CACHE_FOLDER, POSTS_CACHE_FOLDER, SQL_CACHE_FOLDER, TOPICS_CACHE_FOLDER, USERS_CACHE_FOLDER) : array($this->cache_dir, $this->cache_dir_sql);
	}

	/**
	* Load global cache
	*/
	function load()
	{
		return $this->_read('data_global', $this->cache_dir);
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
		$this->sql_rowset = new DbObjectStorage;
		$this->sql_row_pointer = new DbObjectStorage;
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

		if (!$this->_write('data_global', $this->vars, $this->var_expires, $this->cache_dir))
		{
			// Now, this occurred how often? ... phew, just tell the user then...
			if (!@is_writable($this->cache_dir))
			{
				// We need to use die() here, because else we may encounter an infinite loop (the message handler calls $cache->unload())
				die($this->cache_dir . ' is NOT writable.');
				exit;
			}

			die('Not able to open ' . $this->cache_dir . 'data_global.' . PHP_EXT);
			exit;
		}

		$this->is_modified = false;
	}

	/**
	* Tidy cache
	*/
	function tidy()
	{
		foreach ($this->cache_dirs as $cache_folder)
		{
			$cache_folder = $this->validate_cache_folder($cache_folder, false, true);
			$dir = @opendir($cache_folder);

			if (!$dir)
			{
				return;
			}

			$time = time();

			while (($entry = readdir($dir)) !== false)
			{
				if (!preg_match('/^(sql_|data_(?!global))/', $entry))
				{
					continue;
				}

				$expired = $this->is_expired($time, $entry, $cache_folder);
				if ($expired)
				{
					$this->remove_file($entry, false, $cache_folder);
				}
			}
			closedir($dir);
		}

		if (file_exists($this->cache_dir . 'data_global.' . PHP_EXT))
		{
			if (!sizeof($this->vars))
			{
				$this->load();
			}

			foreach ($this->var_expires as $var_name => $expires)
			{
				if ($time >= $expires)
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

			return $this->_read('data' . $var_name, $this->cache_dir);
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
			$this->_write('data' . $var_name, $var, time() + $ttl);
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
		// Purge all cache files
		foreach ($this->cache_dirs as $cache_folder)
		{
			$cache_folder = $this->validate_cache_folder($cache_folder, false, true);
			$dir = @opendir($cache_folder);

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

				$this->remove_file($entry, false, $cache_folder);
			}
			closedir($dir);
		}

		unset($this->vars);
		unset($this->var_expires);
		unset($this->sql_rowset);
		unset($this->sql_row_pointer);

		$this->vars = array();
		$this->var_expires = array();
		$this->sql_rowset = new DbObjectStorage;
		$this->sql_row_pointer = new DbObjectStorage;

		$this->is_modified = false;
	}

	/**
	* Destroy cache data
	*/
	function destroy($var_name, $table = '', $cache_folder = '')
	{
		if (($var_name == 'sql') && !empty($table))
		{
			if (!is_array($table))
			{
				$table = array($table);
			}

			$cache_folder = $this->validate_cache_folder($cache_folder, true, false);
			$dir = @opendir($cache_folder);

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

				$query = $this->get_query_string($entry);

				if (empty($query))
				{
					continue;
				}

				foreach ($table as $check_table)
				{
					// Better catch partial table names than no table names. ;)
					if (strpos($query, $check_table) !== false)
					{
						$this->remove_file($entry, false, $cache_folder);
						break;
					}
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
			$this->remove_file('data' . $var_name . '.' . PHP_EXT, true, $this->cache_dir);
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

		$cache_folder = $this->validate_cache_folder($cache_folder, false, true);
		$datafiles = !is_array($datafiles) ? array($datafiles) : $datafiles;

		if (!$prefix_lookup)
		{
			foreach ($datafiles as $datafile)
			{
				$file_deleted = $this->remove_file($prefix . $datafile . '.' . PHP_EXT, false, $cache_folder);
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
						$file_deleted = $this->remove_file($entry, false, $cache_folder);
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
	* Load cached sql query
	*/
	function sql_load($query, $cache_prefix = '', $cache_folder = '')
	{
		$cache_prefix = 'sql_' . $cache_prefix;
		$cache_folder = $this->validate_cache_folder($cache_folder, true, false);

		// Remove extra spaces and tabs
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);
		if (($rowset = $this->_read($cache_prefix . $this->sql_query_hash($query), $cache_folder)) === false)
		{
			return false;
		}

		$this->sql_query_id = new sql_db_fake_id($this->last_query_id++);
		$this->sql_rowset[$this->sql_query_id] = $rowset;
		$this->sql_row_pointer[$this->sql_query_id] = 0;

		return $this->sql_query_id;
	}

	/**
	* Save sql query
	*/
	function sql_save($query, &$query_result, $ttl = CACHE_SQL_EXPIRY, $cache_prefix = '', $cache_folder = '')
	{
		global $db;

		$cache_prefix = 'sql_' . $cache_prefix;
		$cache_folder = $this->validate_cache_folder($cache_folder, true, true);

		// Remove extra spaces and tabs
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);
		$this->sql_query_id = new sql_db_fake_id($this->last_query_id++);
		$this->sql_row_pointer[$this->sql_query_id] = 0;
		$this->sql_rowset[$this->sql_query_id] = $db->sql_fetchrowset($query_result);
		$db->sql_freeresult($query_result);

		if ($this->_write($cache_prefix . $this->sql_query_hash($query), $this->sql_rowset[$this->sql_query_id], time() + $ttl, $query, $cache_folder))
		{
			$query_result = $this->sql_query_id;
		}
	}

	/**
	* Check if a given sql query exist in cache
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
			// SplObjectStorage doesn't support ++
			$newp = $this->sql_row_pointer[$query_id];
			$this->sql_row_pointer[$query_id] += 1;
			return $this->sql_rowset[$query_id][$newp];
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
			// SplObjectStorage doesn't support ++
			$newp = $this->sql_row_pointer[$query_id];
			$this->sql_row_pointer[$query_id] += 1;
			return (isset($this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]][$field])) ? $this->sql_rowset[$query_id][$newp][$field] : false;
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
	* Read cached data from a specified file
	*
	* @access private
	* @param string $filename Filename to write
	* @return mixed False if an error was encountered, otherwise the data type of the cached data
	*/
	function _read($filename, $cache_folder = '')
	{
		if (!empty($this->use_old_ip_cache))
		{
			return $this->_read_ip($filename, $cache_folder);
		}

		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);
		$file = $cache_folder . $filename . '.' . PHP_EXT;

		$type = substr($filename, 0, strpos($filename, '_'));

		if (!file_exists($file))
		{
			return false;
		}

		if (!($handle = @fopen($file, 'rb')))
		{
			return false;
		}

		// Skip the PHP header
		fgets($handle);

		if ($filename == 'data_global')
		{
			$this->vars = $this->var_expires = array();

			$time = time();

			while (($expires = (int) fgets($handle)) && !feof($handle))
			{
				// Number of bytes of data
				$bytes = substr(fgets($handle), 0, -1);

				if (!is_numeric($bytes) || ($bytes = (int) $bytes) === 0)
				{
					// We cannot process the file without a valid number of bytes so we discard it
					fclose($handle);

					$this->vars = $this->var_expires = array();
					$this->is_modified = false;

					$this->remove_file($file, false, $cache_folder);

					return false;
				}

				if ($time >= $expires)
				{
					fseek($handle, $bytes, SEEK_CUR);

					continue;
				}

				$var_name = substr(fgets($handle), 0, -1);

				// Read the length of bytes that consists of data.
				$data = fread($handle, $bytes - strlen($var_name));
				$data = @unserialize($data);

				// Don't use the data if it was invalid
				if ($data !== false)
				{
					$this->vars[$var_name] = $data;
					$this->var_expires[$var_name] = $expires;
				}

				// Absorb the LF
				fgets($handle);
			}

			fclose($handle);

			$this->is_modified = false;

			return true;
		}
		else
		{
			$data = false;
			$line = 0;

			while (($buffer = fgets($handle)) && !feof($handle))
			{
				$buffer = substr($buffer, 0, -1); // Remove the LF

				// $buffer is only used to read integers
				// if it is non numeric we have an invalid
				// cache file, which we will now remove.
				if (!is_numeric($buffer))
				{
					break;
				}

				if ($line == 0)
				{
					$expires = (int) $buffer;

					if (time() >= $expires)
					{
						break;
					}

					if ($type == 'sql')
					{
						// Skip the query
						fgets($handle);
					}
				}
				elseif ($line == 1)
				{
					$bytes = (int) $buffer;

					// Never should have 0 bytes
					if (!$bytes)
					{
						break;
					}

					// Grab the serialized data
					$data = fread($handle, $bytes);

					// Read 1 byte, to trigger EOF
					fread($handle, 1);

					if (!feof($handle))
					{
						// Somebody tampered with our data
						$data = false;
					}
					break;
				}
				else
				{
					// Something went wrong
					break;
				}
				$line++;
			}
			fclose($handle);

			// unserialize if we got some data
			$data = ($data !== false) ? @unserialize($data) : $data;

			if ($data === false)
			{
				$this->remove_file($file, false, $cache_folder);
				return false;
			}

			return $data;
		}
	}

	/**
	* Write cache data to a specified file
	*
	* 'data_global' is a special case and the generated format is different for this file:
	* <code>
	* < ? php exit; ? >
	* (expiration)
	* (length of var and serialised data)
	* (var)
	* (serialised data)
	* ... (repeat)
	* </code>
	*
	* The other files have a similar format:
	* <code>
	* < ? php exit; ? >
	* (expiration)
	* (query) [SQL files only]
	* (length of serialised data)
	* (serialised data)
	* </code>
	*
	* @access private
	* @param string $filename Filename to write
	* @param mixed $data Data to store
	* @param int $expires Timestamp when the data expires
	* @param string $query Query when caching SQL queries
	* @return bool True if the file was successfully created, otherwise false
	*/
	function _write($filename, $data = null, $expires = 0, $query = '', $cache_folder = '')
	{
		if (!empty($this->use_old_ip_cache))
		{
			return $this->_write_ip($filename, $data, $expires, $query, $cache_folder);
		}

		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);
		$file = $cache_folder . $filename . '.' . PHP_EXT;

		if ($handle = @fopen($file, 'wb'))
		{
			@flock($handle, LOCK_EX);

			// File header
			fwrite($handle, '<' . '?php exit; ?' . '>');

			if ($filename == 'data_global')
			{
				// Global data is a different format
				foreach ($this->vars as $var => $data)
				{
					if ((strpos($var, "\r") !== false) || (strpos($var, "\n") !== false))
					{
						// CR/LF would cause fgets() to read the cache file incorrectly
						// do not cache test entries, they probably won't be read back
						// the cache keys should really be alphanumeric with a few symbols.
						continue;
					}
					$data = serialize($data);

					// Write out the expiration time
					fwrite($handle, "\n" . $this->var_expires[$var] . "\n");

					// Length of the remaining data for this var (ignoring two LF's)
					fwrite($handle, strlen($data . $var) . "\n");
					fwrite($handle, $var . "\n");
					fwrite($handle, $data);
				}
			}
			else
			{
				fwrite($handle, "\n" . $expires . "\n");

				if (strpos($filename, 'sql_') === 0)
				{
					fwrite($handle, $query . "\n");
				}
				$data = serialize($data);

				fwrite($handle, strlen($data) . "\n");
				fwrite($handle, $data);
			}

			@flock($handle, LOCK_UN);
			fclose($handle);

			if (!function_exists('phpbb_chmod'))
			{
				include(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
			}

			phpbb_chmod($file, CHMOD_READ | CHMOD_WRITE);

			return true;
		}

		return false;
	}

	/**
	* Read cached data (IP Version)
	*
	* @access private
	* @param string $filename Filename to write
	* @return mixed False if an error was encountered, otherwise the data type of the cached data
	*/
	function _read_ip($filename, $cache_folder = '')
	{
		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);
		$file = $cache_folder . $filename . '.' . PHP_EXT;

		if (file_exists($file))
		{
			@include($file);
			if (!empty($expired))
			{
				$this->remove_file($filename . '.' . PHP_EXT, true, $cache_folder);
				return false;
			}
		}
		else
		{
			return false;
		}

		if ($filename == 'data_global')
		{
			return true;
		}
		else
		{
			return (isset($data)) ? $data : false;
		}
	}

	/**
	* Write cache data to a specified file (IP Version)
	*
	* @access private
	* @param string $filename Filename to write
	* @param mixed $data Data to store
	* @param int $expires Timestamp when the data expires
	* @param string $query Query when caching SQL queries
	* @return bool True if the file was successfully created, otherwise false
	*/
	function _write_ip($filename, $data = null, $expires = 0, $query = '', $cache_folder = '')
	{
		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);
		$file = $cache_folder . $filename . '.' . PHP_EXT;

		if ($fp = @fopen($file, 'wb'))
		{
			@flock($fp, LOCK_EX);

			$file_content = "<" . "?php\nif (!defined('IN_ICYPHOENIX')) exit;\n\n";
			$file_content .= "\$created = " . time() . "; // " . gmdate('Y/m/d - H:i:s') . "\n";
			if ($filename == 'data_global')
			{
				$file_content .= "\n\$this->vars = " . var_export($data, true) . ";\n";
				$file_content .= "\n\$this->var_expires = " . var_export($expires, true) . ";\n";
			}
			elseif (!empty($query))
			{
				$file_content .= "/* " . str_replace('*/', '*\/', $query) . " */\n";
				$file_content .= "\$expired = (time() >= " . $expires . ") ? true : false;\nif (\$expired) { return; }\n";
				$file_content .= "\n\$this->sql_rowset[\$this->sql_query_id] = " . (sizeof($this->sql_rowset[$this->sql_query_id]) ? "unserialize(" . var_export(serialize($this->sql_rowset[$this->sql_query_id]), true) . ");" : 'array();') . "\n";
			}
			else
			{
				$file_content .= "\$expired = (time() >= " . $expires . ") ? true : false;\nif (\$expired) { return; }\n";
				$file_content .= "\n\$data = " . (sizeof($data) ? "unserialize(" . var_export(serialize($data), true) . ");" : 'array();') . "\n";
			}
			$file_content .= "\n?" . ">";

			fwrite($fp, $file_content);
			@flock($fp, LOCK_UN);
			fclose($fp);

			if (!function_exists('phpbb_chmod'))
			{
				include(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
			}

			phpbb_chmod($file, CHMOD_WRITE);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Removes/unlinks file
	*/
	function remove_file($filename, $check = false, $cache_folder = '')
	{
		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);
		$cache_filename = $cache_folder . $filename;
		if (@file_exists($cache_filename))
		{
			$file_unlink = @unlink($cache_filename);
			if ($check && !$file_unlink && !@is_writable($cache_folder))
			{
				// Better avoid calling trigger_error
				die('Unable to remove ' . $cache_filename . '. Please check directory permissions.');
			}
			return $file_unlink;
		}

		return true;
	}

	/**
	* Checks cache folder
	*/
	function validate_cache_folder($cache_folder, $is_sql = false, $deep_check = false)
	{

		$default_cache_folder = (!empty($is_sql) ? $this->cache_dir_sql : $this->cache_dir);
		$cache_folder = (!empty($cache_folder) && in_array($cache_folder, $this->cache_dirs)) ? $cache_folder : $default_cache_folder;
		if (!empty($deep_check))
		{
			$cache_folder = @is_dir($cache_folder) ? $cache_folder : $default_cache_folder;
			// This part of code should should ensure realpath folder identified...
			$cache_folder = @is_dir($cache_folder) ? $cache_folder : @phpbb_realpath($cache_folder);
		}

		return $cache_folder;
	}

	/**
	* Checks if cache expired
	*/
	function is_expired($time, $filename, $cache_folder = '')
	{
		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);

		if (!file_exists($cache_folder . $filename))
		{
			return false;
		}

		if (!empty($this->use_old_ip_cache))
		{
			$expired = true;
			@include($cache_folder . $filename);
			return (!empty($expired) ? true : false);
		}
		else
		{
			if (!($handle = @fopen($cache_folder . $filename, 'rb')))
			{
				return true;
			}

			// Skip the PHP header
			fgets($handle);

			// Skip expiration
			$expires = (int) fgets($handle);

			fclose($handle);

			$expired = ($time >= $expires) ? true : false;
			return (!empty($expired) ? true : false);
		}
	}

	/**
	* Gets query string
	*/
	function get_query_string($filename, $cache_folder = '')
	{
		$cache_folder = $this->validate_cache_folder($cache_folder, false, false);

		if (!empty($this->use_old_ip_cache))
		{
			$check_line = @file_get_contents($cache_folder . $filename);

			if (empty($check_line))
			{
				return false;
			}

			// Now get the contents between /* and */
			$query = substr($check_line, strpos($check_line, '/* ') + 3, strpos($check_line, ' */') - strpos($check_line, '/* ') - 3);
		}
		else
		{
			if (!($handle = @fopen($cache_folder . $filename, 'rb')))
			{
				return false;
			}

			// Skip the PHP header
			fgets($handle);

			// Skip expiration
			fgets($handle);

			// Grab the query, remove the LF
			$query = substr(fgets($handle), 0, -1);

			fclose($handle);
		}

		return $query;
	}
}

?>