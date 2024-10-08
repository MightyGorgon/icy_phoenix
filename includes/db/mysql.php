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
	exit;
}

if (!class_exists('DbObjectStorage'))
{
	include(IP_ROOT_PATH . 'includes/class_cache.' . PHP_EXT);
}

class sql_db
{
	var $db_connect_id;
	var $query_string = '';
	var $query_result;
	var $row;
	var $rowset;
	var $num_queries = array();
	var $open_queries = array();
	var $transaction = false;
	var $transactions = 0;
	var $multi_insert = false;

	var $cache_folder = '';

	var $curtime = 0;
	var $sql_time = 0;
	var $sql_init_time = 0;
	var $sql_start_time = 0;
	var $sql_end_time = 0;

	var $query_hold = '';
	var $html_hold = '';
	var $sql_report = '';

	var $return_on_error = false;
	var $sql_error_triggered = false; // Set to true if error triggered
	var $sql_error_sql = ''; // Holding the last sql query on sql error
	var $sql_error_returned = array(); // Holding the error information - only populated if sql_error_triggered is set

	/**
	* Exact version of the DBAL, directly queried
	*/
	var $sql_server_version = false;

	/**
	* Wildcards for matching any (%) or exactly one (_) character within LIKE expressions
	*/
	var $any_char;
	var $one_char;

	// Constructor
	function __construct($dbms, $sqlserver, $sqluser, $sqlpassword, $database, $persistency = false)
	{
		$this->sql_start_time = $this->sql_get_time();
		$this->sql_init_time = $this->sql_start_time;

		$this->num_queries = array(
			'cached'		=> 0,
			'normal'		=> 0,
			'total'			=> 0,
		);

		// Fill default sql layer based on the class being called.
		// This can be changed by the specified layer itself later if needed.
		$this->sql_layer = SQL_LAYER;

		// Do not change this please! This variable is used to easy the use of it - and is hardcoded.
		$this->any_char = chr(0) . '%';
		$this->one_char = chr(0) . '_';

		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = ($this->persistency) ? 'p:' . (($sqlserver) ? $sqlserver : 'localhost') : $sqlserver;
		$this->dbname = $database;

		$this->row = new DbObjectStorage();
		$this->rowset = new DbObjectStorage();
		$this->open_queries = new DbObjectStorage();
		// Mighty Gorgon: DEBUGGING DB OBJECT STORAGE
		//$this->open_queries = array();

		$server_and_port = explode(':', $this->server);
		$port = isset($server_and_port[1]) ? intval($server_and_port[1]) : null;
		$this->db_connect_id = mysqli_connect($server_and_port[0], $this->user, $this->password, null, $port);

		if($this->db_connect_id)
		{
			if($database != '')
			{
				$this->dbname = $database;
				$dbselect = @mysqli_select_db($this->db_connect_id, $this->dbname);

				if(!$dbselect)
				{
					@mysqli_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}

			$result = $this->db_connect_id;
		}
		else
		{
			$this->sql_error('__CONNECT__');
			$result = false;
		}

		// make db connection UTF-8 aware and set the engine to MYISAM
		if ($this->db_connect_id)
		{
			@mysqli_query($this->db_connect_id, "SET NAMES 'utf8'");
			@mysqli_query($this->db_connect_id, "SET default_storage_engine = MyISAM");
			/*
			// Mighty Gorgon: other useful MyISAM references
			//ALTER TABLE table_name ENGINE = MyISAM;
			//SELECT CONCAT('ALTER TABLE ',table_schema,'.',table_name,' engine = MyISAM;') FROM information_schema.tables WHERE engine = 'InnoDB';
			*/
		}

		$this->sql_server_version = $this->sql_server_info(true);

		$this->sql_end_time = $this->sql_get_time();
		$this->sql_time += $this->sql_end_time - $this->sql_start_time;

		return $result;
	}

	/**
	* Version information about used database
	* @param bool $raw if true, only return the fetched sql_server_version
	* @return string sql server version
	*/
	function sql_server_info($raw = false)
	{
		global $cache;

		if (empty($cache) || ($this->sql_server_version = $cache->get('mysqli_version')) === false)
		{
			$result = @mysqli_query($this->db_connect_id, 'SELECT VERSION() AS version');
			$row = @mysqli_fetch_assoc($result);
			@mysqli_free_result($result);

			$this->sql_server_version = $row['version'];

			if (!empty($cache))
			{
				$cache->put('mysqli_version', $this->sql_server_version);
			}
		}

		return ($raw) ? $this->sql_server_version : 'MySQL(i) ' . $this->sql_server_version;
	}

	/**
	* Get microtime
	*/
	function sql_get_time()
	{
		$mtime = explode(' ', microtime());
		return $mtime[1] + $mtime[0];
	}

	/**
	* Close DB connection
	*/
	function sql_close()
	{
		$this->sql_start_time = $this->sql_get_time();

		if($this->db_connect_id)
		{
			// Commit any remaining transactions
			if($this->transaction)
			{
				do
				{
					$this->sql_transaction('commit');
				}
				while ($this->transaction);
			}

			foreach ($this->open_queries as $query_id)
			{
				$this->sql_freeresult($query_id);
			}

			// Connection closed correctly. Set db_connect_id to false to prevent errors
			if ($result = $this->_sql_close())
			{
				$this->db_connect_id = false;
			}
		}
		else
		{
			$result = false;
		}

		$this->sql_end_time = $this->sql_get_time();
		$this->sql_time += $this->sql_end_time - $this->sql_start_time;

		return $result;
	}

	/**
	* Return number of sql queries and cached sql queries used
	*/
	function sql_num_queries($cached = false)
	{
		return ($cached) ? $this->num_queries['cached'] : $this->num_queries['normal'];
	}

	/**
	* Add to query count
	*/
	function sql_add_num_queries($cached = false)
	{
		$this->num_queries['cached'] += ($cached !== false) ? 1 : 0;
		$this->num_queries['normal'] += ($cached !== false) ? 0 : 1;
		$this->num_queries['total'] += 1;
	}

	/**
	* Build LIMIT query
	* Doing some validation here.
	*/
	function sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0)
	{
		if (empty($query))
		{
			return false;
		}

		// Never use a negative total or offset
		$total = ($total < 0) ? 0 : $total;
		$offset = ($offset < 0) ? 0 : $offset;

		return $this->_sql_query_limit($query, $total, $offset, $cache_ttl);
	}

	/**
	* Base query method
	*/
	function sql_query($query = '', $cache_ttl = 0, $cache_prefix = false, $cache_folder = '')
	{
		if (empty($query))
		{
			return false;
		}

		global $cache;

		$this->sql_start_time = $this->sql_get_time();

		$cache_folder = (empty($cache_folder) ? ( defined('SQL_CACHE_FOLDER') ? SQL_CACHE_FOLDER : '' ) : $cache_folder);

		if (defined('DEBUG_EXTRA') && DEBUG_EXTRA)
		{
			$this->sql_report('start', $query);
		}

		if (defined('CACHE_SQL') && (CACHE_SQL == false))
		{
			$cache_prefix = false;
		}

		$cache_ttl = empty($cache_prefix) ? 0 : (empty($cache_ttl) ? CACHE_SQL_EXPIRY : $cache_ttl);

		// Cache SQL to the same file plus underscore
		if (defined('SQL_DEBUG_LOG') && SQL_DEBUG_LOG && !defined('IN_ADMIN') && !defined('IN_INSTALL'))
		{
			$f = fopen($this->cache_folder . 'sql_history.' . PHP_EXT, 'a+');
			@flock($f, LOCK_EX);
			@fwrite($f, gmdate('Y/m/d - H:i:s') . ' => ' . $hash . "\n\n" . $query . "\n\n\n=========================\n\n");
			@flock($f, LOCK_UN);
			@fclose($f);
		}

		$this->query_result = ($cache_ttl && method_exists($cache, 'sql_load')) ? $cache->sql_load($query, $cache_prefix, $cache_folder) : false;
		$this->sql_add_num_queries($this->query_result);

		if ($this->query_result === false)
		{
			if ((($this->query_result = @mysqli_query($this->db_connect_id, $query)) === false) && !defined('IN_INSTALL'))
			{
				$this->sql_end_time = $this->sql_get_time();
				$this->sql_time += $this->sql_end_time - $this->sql_start_time;

				$this->sql_error($query);
			}

			if (defined('DEBUG_EXTRA') && DEBUG_EXTRA)
			{
				$this->sql_end_time = $this->sql_get_time();
				$this->sql_time += $this->sql_end_time - $this->sql_start_time;

				$this->sql_report('stop', $query);
			}

			if ($cache_ttl && method_exists($cache, 'sql_save') && $this->query_result)
			{
				$this->open_queries[$this->query_result] = $this->query_result;
				$cache->sql_save($query, $this->query_result, $cache_ttl, $cache_prefix, $cache_folder);
			}
			elseif ((strpos($query, 'SELECT') === 0) && ($this->query_result))
			{
				$this->open_queries[$this->query_result] = $this->query_result;
			}
		}
		elseif (defined('DEBUG_EXTRA') && DEBUG_EXTRA)
		{
			$this->sql_report('fromcache', $query);
		}

		$this->sql_end_time = $this->sql_get_time();
		$this->sql_time += $this->sql_end_time - $this->sql_start_time;

		return $this->query_result;
	}

	/**
	* Get numrows
	*/
	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		return ($query_id) ? @mysqli_num_rows($query_id) : false;
	}

	/**
	* Get affected rows
	*/
	function sql_affectedrows()
	{
		return ($this->db_connect_id) ? @mysqli_affected_rows($this->db_connect_id) : false;
	}

	/**
	* Fetch current row
	*/
	function sql_fetchrow($query_id = false)
	{
		global $cache;

		if (!$query_id && $this->query_result)
		{
			$query_id = $this->query_result;
		}

		if (!$query_id)
		{
			return false;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_fetchrow($query_id);
		}

		return @mysqli_fetch_assoc($query_id);
	}

	/**
	* Fetch all rows
	*/
	function sql_fetchrowset($query_id = false)
	{
		$result = false;
		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($query_id !== false)
		{
			$result = array();
			while ($row = $this->sql_fetchrow($query_id))
			{
				$result[] = $row;
			}

			return $result;
		}

		return false;
	}

	/**
	* Fetch field
	* if rownum is false, the current row is used, else it is pointing to the row (zero-based)
	*/
	function sql_fetchfield($field, $rownum = false, $query_id = false)
	{
		global $cache;

		$result = false;
		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($query_id !== false)
		{
			if ($rownum !== false)
			{
				$this->sql_rowseek($rownum, $query_id);
			}

			if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
			{
				return $cache->sql_fetchfield($query_id, $field);
			}

			$row = $this->sql_fetchrow($query_id);
			$result = (isset($row[$field])) ? $row[$field] : false;
		}

		return $result;
	}

	/**
	* Get num fields
	*/
	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		return ($query_id) ? @mysqli_num_fields($query_id) : false;
	}

	/**
	* Get field name
	*/
	function sql_fieldname($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		return ($query_id) ? @mysqli_field_name($query_id, $offset) : false;
	}

	/**
	* Get field type
	*/
	function sql_fieldtype($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		return ($query_id) ? @mysqli_field_type($query_id, $offset) : false;
	}

	/**
	* Seek to given row number
	* rownum is zero-based
	*/
	function sql_rowseek($rownum, &$query_id)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_rowseek($rownum, $query_id);
		}

		return ($query_id !== false) ? @mysqli_data_seek($query_id, $rownum) : false;
	}

	/**
	* Gets the estimated number of rows in a specified table.
	* @param string $table_name		Table name
	* @return string Number of rows in $table_name. Prefixed with ~ if estimated (otherwise exact).
	*/
	function get_estimated_row_count($table_name)
	{
		$table_status = $this->get_table_status($table_name);

		if (isset($table_status['Engine']))
		{
			if ($table_status['Engine'] === 'MyISAM')
			{
				return $table_status['Rows'];
			}
			elseif (($table_status['Engine'] === 'InnoDB') && ($table_status['Rows'] > 100000))
			{
				return '~' . $table_status['Rows'];
			}
		}
		return $this->get_row_count($table_name);
	}

	/**
	* Gets the exact number of rows in a specified table.
	* @param string $table_name Table name
	* @return string Exact number of rows in $table_name.
	*/
	function get_row_count($table_name)
	{
		$table_status = $this->get_table_status($table_name);
		if (isset($table_status['Engine']) && ($table_status['Engine'] === 'MyISAM'))
		{
			return $table_status['Rows'];
		}

		$sql = 'SELECT COUNT(*) AS rows_total FROM ' . $this->sql_escape($table_name);
		$result = $this->sql_query($sql);
		$rows_total = $this->sql_fetchfield('rows_total');
		$this->sql_freeresult($result);

		return $rows_total;
	}

	/**
	* Gets some information about the specified table.
	* @param string $table_name Table name
	* @return array
	*/
	function get_table_status($table_name)
	{
		$sql = "SHOW TABLE STATUS LIKE '" . $this->sql_escape($table_name) . "'";
		$result = $this->sql_query($sql);
		$table_status = $this->sql_fetchrow($result);
		$this->sql_freeresult($result);

		return $table_status;
	}

	/**
	* Run LOWER() on DB column of type text (i.e. neither varchar nor char).
	* @param string $column_name The column name to use
	* @return string A SQL statement like "LOWER($column_name)"
	*/
	function sql_lower_text($column_name)
	{
		return "LOWER($column_name)";
	}

	/**
	* Get last inserted id after insert statement
	*/
	function sql_nextid()
	{
		return ($this->db_connect_id) ? @mysqli_insert_id($this->db_connect_id) : false;
	}

	/**
	* Function for validating values
	*/
	function sql_validate_value($var)
	{
		if (is_null($var))
		{
			return 'NULL';
		}
		elseif (is_string($var))
		{
			return "'" . $this->sql_escape($var) . "'";
		}
		else
		{
			return (is_bool($var)) ? intval($var) : $var;
		}
	}

	/**
	* Escape string used in sql query
	*/
	function sql_escape($msg)
	{
		return @mysqli_real_escape_string($this->db_connect_id, $msg);
	}

	/**
	* Correctly adjust LIKE expression for special characters
	* Some DBMS are handling them in a different way
	*
	* @param string $expression The expression to use. Every wildcard is escaped, except $this->any_char and $this->one_char
	* @return string LIKE expression including the keyword!
	*/
	function sql_like_expression($expression)
	{
		$expression = str_replace(array('_', '%'), array("\_", "\%"), $expression);
		$expression = str_replace(array(chr(0) . "\_", chr(0) . "\%"), array('_', '%'), $expression);

		return $this->_sql_like_expression('LIKE \'' . $this->sql_escape($expression) . '\'');
	}

	/**
	* Build sql statement from array for select and select distinct statements
	* Possible query values: SELECT, SELECT_DISTINCT
	*/
	function sql_build_query($query, $array)
	{
		$sql = '';
		switch ($query)
		{
			case 'SELECT':
			case 'SELECT_DISTINCT';

				// flatten the SELECT clause
				if (is_array($array['SELECT']))
				{
					$array['SELECT'] = implode(', ', $array['SELECT']);
				}
				$sql = str_replace('_', ' ', $query) . ' ' . $array['SELECT'] . ' FROM ';

				// Build table array. We also build an alias array for later checks.
				$table_array = $aliases = array();
				$used_multi_alias = false;

				foreach ($array['FROM'] as $table_name => $alias)
				{
					if (is_array($alias))
					{
						$used_multi_alias = true;

						foreach ($alias as $multi_alias)
						{
							$table_array[] = $table_name . ' ' . $multi_alias;
							$aliases[] = $multi_alias;
						}
					}
					else
					{
						$table_array[] = $table_name . ' ' . $alias;
						$aliases[] = $alias;
					}
				}

				// We run the following code to determine if we need to re-order the table array. ;)
				// The reason for this is that for multi-aliased tables (two equal tables) in the FROM statement the last table need to match the first comparison.
				// DBMS who rely on this: Oracle, PostgreSQL and MSSQL. For all other DBMS it makes absolutely no difference in which order the table is.
				if (!empty($array['LEFT_JOIN']) && sizeof($array['FROM']) > 1 && $used_multi_alias !== false)
				{
					// Take first LEFT JOIN
					$join = current($array['LEFT_JOIN']);

					// Determine the table used there (even if there are more than one used, we only want to have one
					preg_match('/(' . implode('|', $aliases) . ')\.[^\s]+/U', str_replace(array('(', ')', 'AND', 'OR', ' '), '', $join['ON']), $matches);

					// If there is a first join match, we need to make sure the table order is correct
					if (!empty($matches[1]))
					{
						$first_join_match = trim($matches[1]);
						$table_array = $last = array();

						foreach ($array['FROM'] as $table_name => $alias)
						{
							if (is_array($alias))
							{
								foreach ($alias as $multi_alias)
								{
									($multi_alias === $first_join_match) ? $last[] = $table_name . ' ' . $multi_alias : $table_array[] = $table_name . ' ' . $multi_alias;
								}
							}
							else
							{
								($alias === $first_join_match) ? $last[] = $table_name . ' ' . $alias : $table_array[] = $table_name . ' ' . $alias;
							}
						}

						$table_array = array_merge($table_array, $last);
					}
				}

				$sql .= $this->_sql_custom_build('FROM', implode(', ', $table_array));

				if (!empty($array['LEFT_JOIN']))
				{
					foreach ($array['LEFT_JOIN'] as $join)
					{
						$sql .= ' LEFT JOIN ' . key($join['FROM']) . ' ' . current($join['FROM']) . ' ON (' . $join['ON'] . ')';
					}
				}

				if (!empty($array['WHERE']))
				{
					if (is_array($array['WHERE']))
					{
						$array['WHERE'] = implode(' AND ', $array['WHERE']);
					}
					$sql .= ' WHERE ' . $this->_sql_custom_build('WHERE', $array['WHERE']);
				}

				if (!empty($array['GROUP_BY']))
				{
					$sql .= ' GROUP BY ' . $array['GROUP_BY'];
				}

				if (!empty($array['ORDER_BY']))
				{
					$sql .= ' ORDER BY ' . $array['ORDER_BY'];
				}

			break;
		}

		return $sql;
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
			$validated_v = $this->sql_validate_value($v);
			$insert_fields_sql .= (($insert_fields_sql == '') ? '' : ', ') . $k;
			$insert_values_sql .= (($insert_values_sql == '') ? '' : ', ') . $validated_v;
			$update_sql .= (($update_sql == '') ? '' : ', ') . $k . ' = ' . $validated_v;
		}

		$sql_string = $sql_insert ? (' (' . $insert_fields_sql . ') VALUES (' . $insert_values_sql . ')') : $update_sql;

		return $sql_string;
	}

	/**
	* Build sql statement from array for insert/update/select statements
	*
	* Idea for this from Ikonboard
	* Possible query values: INSERT, INSERT_SELECT, UPDATE, SELECT
	*
	*/
	function sql_build_array($query, $assoc_ary = false)
	{
		if (!is_array($assoc_ary))
		{
			return false;
		}

		$fields = $values = array();

		if (($query == 'INSERT') || ($query == 'INSERT_SELECT'))
		{
			foreach ($assoc_ary as $key => $var)
			{
				$fields[] = $key;

				if (is_array($var) && is_string($var[0]))
				{
					// This is used for INSERT_SELECT(s)
					$values[] = $var[0];
				}
				else
				{
					$values[] = $this->sql_validate_value($var);
				}
			}

			$query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
		}
		elseif ($query == 'MULTI_INSERT')
		{
			trigger_error('The MULTI_INSERT query value is no longer supported. Please use sql_multi_insert() instead.', E_USER_ERROR);
		}
		elseif (($query == 'UPDATE') || ($query == 'SELECT'))
		{
			$values = array();
			foreach ($assoc_ary as $key => $var)
			{
				$values[] = "$key = " . $this->sql_validate_value($var);
			}
			$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
		}

		return $query;
	}

	/**
	* Build IN or NOT IN sql comparison string, uses <> or = on single element arrays to improve comparison speed
	*
	* @access public
	* @param string $field name of the sql column that shall be compared
	* @param array $array array of values that are allowed (IN) or not allowed (NOT IN)
	* @param bool $negate true for NOT IN (), false for IN () (default)
	* @param bool $allow_empty_set If true, allow $array to be empty, this function will return 1=1 or 1=0 then. Default to false.
	*/
	function sql_in_set($field, $array, $negate = false, $allow_empty_set = false)
	{
		if (!sizeof($array))
		{
			if (!$allow_empty_set)
			{
				// Print the backtrace to help identifying the location of the problematic code
				$this->sql_error('No values specified for SQL IN comparison');
			}
			else
			{
				// NOT IN () actually means everything so use a tautology
				if ($negate)
				{
					return '1=1';
				}
				// IN () actually means nothing so use a contradiction
				else
				{
					return '1=0';
				}
			}
		}

		if (!is_array($array))
		{
			$array = array($array);
		}

		if (sizeof($array) == 1)
		{
			@reset($array);
			$var = current($array);

			return $field . ($negate ? ' <> ' : ' = ') . $this->sql_validate_value($var);
		}
		else
		{
			return $field . ($negate ? ' NOT IN ' : ' IN ') . '(' . implode(', ', array_map(array($this, 'sql_validate_value'), $array)) . ')';
		}
	}

	/**
	* Run more than one insert statement.
	*
	* @param string $table table name to run the statements on
	* @param array &$sql_ary multi-dimensional array holding the statement data.
	*
	* @return bool false if no statements were executed.
	* @access public
	*/
	function sql_multi_insert($table, &$sql_ary)
	{
		if (!sizeof($sql_ary))
		{
			return false;
		}

		if ($this->multi_insert)
		{
			$ary = array();
			foreach ($sql_ary as $id => $_sql_ary)
			{
				// If by accident the sql array is only one-dimensional we build a normal insert statement
				if (!is_array($_sql_ary))
				{
					$this->sql_query('INSERT INTO ' . $table . ' ' . $this->sql_build_array('INSERT', $sql_ary));
					return true;
				}

				$values = array();
				foreach ($_sql_ary as $key => $var)
				{
					$values[] = $this->_sql_validate_value($var);
				}
				$ary[] = '(' . implode(', ', $values) . ')';
			}

			$this->sql_query('INSERT INTO ' . $table . ' ' . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary));
		}
		else
		{
			foreach ($sql_ary as $ary)
			{
				if (!is_array($ary))
				{
					return false;
				}

				$this->sql_query('INSERT INTO ' . $table . ' ' . $this->sql_build_array('INSERT', $ary));
			}
		}

		return true;
	}

	/**
	* Free sql result
	*/
	function sql_freeresult($query_id = false)
	{
		global $cache;

		if (!$query_id)
		{
			$query_id = $this->query_result;
		}

		// Mighty Gorgon: DEBUGGING DB OBJECT STORAGE
		if(empty($query_id))
		{
			return false;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_freeresult($query_id);
		}

		// Mighty Gorgon: DEBUGGING DB OBJECT STORAGE
		//echo(gettype($this->open_queries[$query_id]));
		//echo(gettype($query_id));
		if (isset($this->open_queries[$query_id]))
		{
			unset($this->open_queries[$query_id]);
			return @mysqli_free_result($query_id);
		}

		return false;
	}

	/**
	* Errors handling
	*/
	function sql_error($sql = '')
	{
		global $lang;

		// Set var to retrieve errored status
		$this->sql_error_triggered = true;
		$this->sql_error_sql = $sql;

		$this->sql_error_returned = $this->_sql_error();

		if (!$this->return_on_error && (!defined('IN_INSTALL') || $sql === '__CONNECT__'))
		{
			$message = '<b>SQL ERROR [ ' . SQL_LAYER . ' ]</b><br /><br />' . $this->sql_error_returned['message'] . ' [' . $this->sql_error_returned['code'] . ']';

			// Show complete SQL error and path to administrators only
			// Additionally show complete error on installation or if extended debug mode is enabled
			// The DEBUG_EXTRA constant is for development only!
			if (defined('IN_INSTALL') || (defined('DEBUG_EXTRA') && DEBUG_EXTRA))
			{
				$backtrace = new Exception();

				$message .= ($sql) ? '<br /><br /><b>SQL</b><br /><br />' . htmlspecialchars($sql) : '';
				$message .= '<br /><br /><b>BACKTRACE</b><br />' . $backtrace->getTraceAsString();
				$message .= '<br />';
			}
			else
			{
				// If error occurs in initiating the session we need to use a pre-defined language string
				// This could happen if the connection could not be established for example (then we are not able to grab the default language)
				if (!isset($lang['SQL_ERROR_OCCURRED']))
				{
					$message .= '<br /><br />An sql error occurred while fetching this page. Please contact site administrator if this problem persists.';
				}
				else
				{
					$message .= '<br /><br />' . $lang['SQL_ERROR_OCCURRED'];
				}
			}

			if ($this->transaction)
			{
				$this->sql_transaction('rollback');
			}

			global $msg_code;
			//$msg_code = CRITICAL_MESSAGE;
			$message = '<div style="text-align: left;">' . $message . '</div>';

			if (strlen($message) > 1024)
			{
				// We need to define $msg_long_text here to circumvent text stripping.
				global $msg_long_text;
				$msg_long_text = $message;

				@trigger_error(false, E_USER_NOTICE);
			}

			@trigger_error($message, E_USER_NOTICE);
			/*
			$msg_text = $message;
			$msg_title = isset($lang['Error']) ? $lang['Error'] : 'Error';
			message_die($msg_code, $msg_text, $msg_title, __LINE__, __FILE__, $sql);
			*/
		}

		if ($this->transaction)
		{
			$this->sql_transaction('rollback');
		}

		return $this->sql_error_returned;
	}

	/**
	* return on error or display error message
	*/
	function sql_return_on_error($fail = false)
	{
		$this->sql_error_triggered = false;
		$this->sql_error_sql = '';

		$this->return_on_error = $fail;
	}

	/**
	* SQL Transaction
	* @access private
	*/
	function sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				// If we are within a transaction we will not open another one, but enclose the current one to not loose data (preventing auto commit)
				if ($this->transaction)
				{
					$this->transactions++;
					return true;
				}

				$result = $this->_sql_transaction('begin');

				if (!$result)
				{
					$this->sql_error();
				}

				$this->transaction = true;
			break;

			case 'commit':
				// If there was a previously opened transaction we do not commit yet... but count back the number of inner transactions
				if ($this->transaction && $this->transactions)
				{
					$this->transactions--;
					return true;
				}

				// Check if there is a transaction (no transaction can happen if there was an error, with a combined rollback and error returning enabled)
				// This implies we have transaction always set for autocommit db's
				if (!$this->transaction)
				{
					return false;
				}

				$result = $this->_sql_transaction('commit');

				if (!$result)
				{
					$this->sql_error();
				}

				$this->transaction = false;
				$this->transactions = 0;
			break;

			case 'rollback':
				$result = $this->_sql_transaction('rollback');
				$this->transaction = false;
				$this->transactions = 0;
			break;

			default:
				$result = $this->_sql_transaction($status);
			break;
		}

		return $result;
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

		if (!$query && ($this->query_hold != ''))
		{
			$query = $this->query_hold;
		}

		switch ($mode)
		{
			case 'display':
				if (!empty($cache))
				{
					$cache->unload();
				}
				$this->sql_close();

				$mtime = explode(' ', microtime());
				$totaltime = $mtime[0] + $mtime[1] - $this->sql_init_time;
				echo ('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="' . IP_ROOT_PATH . 'templates/common/acp.css" type="text/css" />
	<meta name="author" content="Mighty Gorgon" />
	<title>Icy Phoenix</title>
	<!--[if lt IE 7]>
	<script type="text/javascript" src="' . IP_ROOT_PATH . 'templates/common/js/pngfix.js"></script>
	<![endif]-->
</head>

<body>
<a id="top">&nbsp;</a>
<div id="global-wrapper" style="width: 960px; clear: both; margin: 0 auto;">
<div class="leftshadow"><div class="rightshadow"><div id="wrapper-inner">
<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="tvalignt" colspan="3">
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
		<p><b>Page generated in ' . round($totaltime, 4) . " seconds with {$this->num_queries['normal']} queries" . (($this->num_queries['cached']) ? " + {$this->num_queries['cached']} " . (($this->num_queries['cached'] == 1) ? 'query' : 'queries') . ' returning data from cache' : '') . '</b></p>
		<p>Time spent on ' . $this->num_queries['total'] . ' queries: <b>' . round($this->sql_time, 5) . 's</b></p>
		<p>Time spent on PHP: <b>' . round($totaltime - $this->sql_time, 5) . 's</b></p>
		<br /><br />
		' . $this->sql_report . '
	</div>
	</td>
</tr>
<tr>
	<td colspan="3">
	<div id="bottom_logo_ext">
	<div id="bottom_logo">
		<table>
			<tr>
				<td nowrap="nowrap" width="45%" align="left">
					<br /><span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></span><br /><br />
				</td>
				<td nowrap="nowrap" align="center"><div style="text-align: center;">&nbsp;</div></td>
				<td nowrap="nowrap" width="45%" align="right">
					<br /><span class="copyright">Design by <a href="http://www.lucalibralato.com/" target="_blank">Luca Libralato</a>&nbsp;</span><br /><br />
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
				exit_handler();
				exit;
				break;

			case 'stop':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];
				$this->sql_report .= '
<table class="forumline">
<thead>
<tr><th>Query #' . $this->num_queries['total'] . '</th></tr>
</thead>
<tbody>
<tr>
	<td class="row1"><textarea style="font-family: \'Courier New\', monospace; width: 99%;" rows="8" cols="160">' . preg_replace('/\t(AND|OR)(\W)/', "\$1\$2", htmlspecialchars(preg_replace('/[\s]*[\n\r\t]+[\n\r\s\t]*/', "\n", $query))) . '</textarea></td>
</tr>
</tbody>
</table>
' . $this->html_hold . '
<p class="helpline" style="padding: 2px;">
				';
				if ($this->query_result)
				{
					$this->sql_report .= 'Elapsed: <b style="color:#224488;">' . sprintf('%.5f', $endtime - $this->curtime) . 's</b> &bull; [Before: ' . sprintf('%.5f', $this->curtime - $this->sql_start_time) . 's | After: ' . sprintf('%.5f', $endtime - $this->sql_start_time) . 's]';
					if (preg_match('/^(UPDATE|DELETE|REPLACE)/', $query))
					{
						$this->sql_report .= ' - [Affected rows: <b style="color:#224488;">' . $this->sql_affectedrows($this->query_result) . '</b>]';
					}
				}
				else
				{
					$error = $this->sql_error();
					$this->sql_report .= '<b style="color:#cc3333;">FAILED</b> - ' . SQL_LAYER . ' Error ' . $error['code'] . ': ' . htmlspecialchars($error['message']);
				}
				$this->sql_report .= '</p><br /><br />';
				$this->sql_time += $endtime - $this->curtime;
			break;

			case 'start':
				$this->query_hold = $query;
				$this->html_hold = '';
				$this->curtime = explode(' ', microtime());
				$this->curtime = $this->curtime[0] + $this->curtime[1];
			break;

			case 'add_select_row':

				$html_table = func_get_arg(2);
				$row = func_get_arg(3);

				if (!$html_table && sizeof($row))
				{
					$html_table = true;
					$this->html_hold .= '<table class="forumline"><tr>';

					foreach (array_keys($row) as $val)
					{
						$this->html_hold .= '<th>' . (($val) ? ucwords(str_replace('_', ' ', $val)) : '&nbsp;') . '</th>';
					}
					$this->html_hold .= '</tr>';
				}
				$this->html_hold .= '<tr>';

				$class = 'row1';
				foreach (array_values($row) as $val)
				{
					$class = ($class == 'row1') ? 'row2' : 'row1';
					$this->html_hold .= '<td class="' . $class . '">' . (($val) ? $val : '&nbsp;') . '</td>';
				}
				$this->html_hold .= '</tr>';

				return $html_table;

			break;

			case 'fromcache':

				$this->_sql_report($mode, $query);

			break;

			case 'record_fromcache':

				$endtime = func_get_arg(2);
				$splittime = func_get_arg(3);

				$time_cache = $endtime - $this->curtime;
				$time_db = $splittime - $endtime;
				$color = ($time_db > $time_cache) ? '#228844' : '#cc3333';

				$this->sql_report .= '<table class="forumline">';
				$this->sql_report .= '<thead><tr><th>Query #' . $this->num_queries['total'] . ' [From Cache]</th></tr></thead><tbody><tr>';
				$this->sql_report .= '<td class="row3"><textarea style="font-family: \'Courier New\', monospace; width: 99%;" rows="8" cols="160">' . preg_replace('/\t(AND|OR)(\W)/', "\$1\$2", htmlspecialchars(preg_replace('/[\s]*[\n\r\t]+[\n\r\s\t]*/', "\n", $query))) . '</textarea></td></tr></tbody></table>';
				$this->sql_report .= '<p class="helpline" style="padding: 2px;">';
				$this->sql_report .= 'Before: ' . sprintf('%.5f', $this->curtime - $this->sql_start_time) . 's | After: ' . sprintf('%.5f', $endtime - $this->sql_start_time) . 's | Elapsed [cache]: <b style="color: ' . $color . '">' . sprintf('%.5f', ($time_cache)) . 's</b> | Elapsed [db]: <b>' . sprintf('%.5f', $time_db) . 's</b></p><br /><br />';

				// Pad the start time to not interfere with page timing
				$this->sql_start_time += $time_db;

			break;

			default:
				$this->_sql_report($mode, $query);
			break;
		}

		return true;
	}

	/**
	* Build LIMIT query
	*/
	function _sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0)
	{
		$this->query_result = false;

		// if $total is set to 0 we do not want to limit the number of rows
		if ($total == 0)
		{
			// Having a value of -1 was always a bug
			$total = '18446744073709551615';
		}

		$query .= "\n LIMIT " . ((!empty($offset)) ? $offset . ', ' . $total : $total);

		return $this->sql_query($query, $cache_ttl);
	}

	/**
	* Build LIKE expression
	* @access private
	*/
	function _sql_like_expression($expression)
	{
		return $expression;
	}

	/**
	* Build db-specific query data
	* @access private
	*/
	function _sql_custom_build($stage, $data)
	{
		switch ($stage)
		{
			case 'FROM':
				$data = '(' . $data . ')';
			break;
		}

		return $data;
	}

	/**
	* SQL Transaction
	* @access private
	*/
	function _sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				return @mysqli_autocommit($this->db_connect_id, false);
			break;

			case 'commit':
				$result = @mysqli_commit($this->db_connect_id);
				@mysqli_autocommit($this->db_connect_id, true);
				return $result;
			break;

			case 'rollback':
				$result = @mysqli_rollback($this->db_connect_id);
				@mysqli_autocommit($this->db_connect_id, true);
				return $result;
			break;
		}
		/*
		switch ($status)
		{
			case 'begin':
				return @mysqli_query('BEGIN', $this->db_connect_id);
			break;

			case 'commit':
				return @mysqli_query('COMMIT', $this->db_connect_id);
			break;

			case 'rollback':
				return @mysqli_query('ROLLBACK', $this->db_connect_id);
			break;
		}
		*/

		return true;
	}

	/**
	* return sql error array
	* @access private
	*/
	function _sql_error()
	{
		if (!$this->db_connect_id)
		{
			return array(
				'message' => @mysqli_connect_error(),
				'code' => @mysqli_connect_errno()
			);
		}

		return array(
			'message' => @mysqli_error($this->db_connect_id),
			'code' => @mysqli_errno($this->db_connect_id)
		);
	}

	/**
	* Build db-specific report
	* @access private
	*/
	function _sql_report($mode, $query = '')
	{
		static $test_prof;

		// current detection method, might just switch to see the existance of INFORMATION_SCHEMA.PROFILING
		if ($test_prof === null)
		{
			$test_prof = false;
			if (version_compare($this->sql_server_info(true), '5.0.37', '>=') && version_compare($this->sql_server_info(true), '5.1', '<'))
			{
				$test_prof = true;
			}
		}

		switch ($mode)
		{
			case 'start':

				$explain_query = $query;
				if (preg_match('/UPDATE ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}
				elseif (preg_match('/DELETE FROM ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}

				if (preg_match('/^SELECT/', $explain_query))
				{
					$html_table = false;

					// begin profiling
					if ($test_prof)
					{
						@mysqli_query('SET profiling = 1;', $this->db_connect_id);
					}

					if ($result = @mysqli_query("EXPLAIN $explain_query", $this->db_connect_id))
					{
						while ($row = @mysqli_fetch_assoc($result))
						{
							$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
						}
					}
					@mysqli_free_result($result);

					if ($html_table)
					{
						$this->html_hold .= '</table>';
					}

					if ($test_prof)
					{
						$html_table = false;

						// get the last profile
						if ($result = @mysqli_query('SHOW PROFILE ALL;', $this->db_connect_id))
						{
							$this->html_hold .= '<br />';
							while ($row = @mysqli_fetch_assoc($result))
							{
								// make <unknown> HTML safe
								if (!empty($row['Source_function']))
								{
									$row['Source_function'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $row['Source_function']);
								}

								// remove unsupported features
								foreach ($row as $key => $val)
								{
									if ($val === null)
									{
										unset($row[$key]);
									}
								}
								$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
							}
						}
						@mysqli_free_result($result);

						if ($html_table)
						{
							$this->html_hold .= '</table>';
						}

						@mysqli_query('SET profiling = 0;', $this->db_connect_id);
					}
				}

			break;

			case 'fromcache':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];

				$result = @mysqli_query($query, $this->db_connect_id);
				while ($void = @mysqli_fetch_assoc($result))
				{
					// Take the time spent on parsing rows into account
				}
				@mysqli_free_result($result);

				$splittime = explode(' ', microtime());
				$splittime = $splittime[0] + $splittime[1];

				$this->sql_report('record_fromcache', $query, $endtime, $splittime);

			break;
		}
	}

	/**
	* Close sql connection
	* @access private
	*/
	function _sql_close()
	{
		return @mysqli_close($this->db_connect_id);
	}

	/**
	* remove_comments will strip the sql comment lines out of an uploaded sql file
	* specifically for mssql and postgres type files in the install...
	*/
	function remove_comments(&$output)
	{
		$lines = explode("\n", $output);
		$output = '';

		// try to keep mem. use down
		$linecount = sizeof($lines);

		$in_comment = false;
		for ($i = 0; $i < $linecount; $i++)
		{
			if (trim($lines[$i]) == '/*')
			{
				$in_comment = true;
			}

			if (!$in_comment)
			{
				$output .= $lines[$i] . "\n";
			}

			if (trim($lines[$i]) == '*/')
			{
				$in_comment = false;
			}
		}

		unset($lines);
		return $output;
	}

	/**
	* remove_remarks will strip the sql comment lines out of an uploaded sql file
	*/
	function remove_remarks(&$sql)
	{
		$sql = preg_replace('/\n{2,}/', "\n", preg_replace('/^#.*$/m', "\n", $sql));
	}

	/**
	* split_sql_file will split an uploaded sql file into single sql statements.
	* Note: expects trim() to have already been run on $sql.
	*/
	function split_sql_file($sql, $delimiter)
	{
		$sql = str_replace("\r" , '', $sql);
		$data = preg_split('/' . preg_quote($delimiter, '/') . '$/m', $sql);

		$data = array_map('trim', $data);

		// The empty case
		$end_data = end($data);

		if (empty($end_data))
		{
			unset($data[key($data)]);
		}

		return $data;
	}

	/**
	* Cache clear function
	*/
	function clear_cache($cache_prefix = '', $cache_folder = SQL_CACHE_FOLDER, $files_per_step = 0)
	{
		$cache_folder = (empty($cache_folder) ? SQL_CACHE_FOLDER : $cache_folder);

		$cache_prefix = 'sql_' . $cache_prefix;
		$cache_folder = (!empty($cache_folder) && @is_dir($cache_folder)) ? $cache_folder : SQL_CACHE_FOLDER;
		$cache_folder = ((@is_dir($cache_folder)) ? $cache_folder : @phpbb_realpath($cache_folder));

		$res = opendir($cache_folder);
		if($res)
		{
			$files_counter = 0;
			while(($file = readdir($res)) !== false)
			{
				if(!@is_dir($file) && (substr($file, 0, strlen($cache_prefix)) === $cache_prefix) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
				{
					@unlink($cache_folder . $file);
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

}

?>