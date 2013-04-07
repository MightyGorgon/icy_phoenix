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

/**
* @ignore
*/
if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

/**
* Database Tools for handling cross-db actions such as altering columns, etc.
* Currently not supported is returning SQL for creating tables.
*
* @package dbal
* @note currently not used within phpBB3, but may be utilized later.
*/
class phpbb_db_tools
{
	/**
	* Current sql layer
	*/
	var $sql_layer = '';

	/**
	* @var object DB object
	*/
	var $db = NULL;

	/**
	* The Column types for every database we support
	* @var array
	*/
	var $dbms_type_map = array(
		'mysql_41'			=> array(
			'INT:'				=> 'int(%d)',
			'BINT'				=> 'bigint(20)',
			'UINT'				=> 'mediumint(8) UNSIGNED',
			'UINT:'				=> 'int(%d) UNSIGNED',
			'TINT:'				=> 'tinyint(%d)',
			'USINT'				=> 'smallint(4) UNSIGNED',
			'BOOL'				=> 'tinyint(1) UNSIGNED',
			'VCHAR'				=> 'varchar(255)',
			'VCHAR:'			=> 'varchar(%d)',
			'CHAR:'				=> 'char(%d)',
			'XSTEXT'			=> 'text',
			'XSTEXT_UNI'	=> 'varchar(100)',
			'STEXT'				=> 'text',
			'STEXT_UNI'		=> 'varchar(255)',
			'TEXT'				=> 'text',
			'TEXT_UNI'		=> 'text',
			'MTEXT'				=> 'mediumtext',
			'MTEXT_UNI'		=> 'mediumtext',
			'TIMESTAMP'		=> 'int(11) UNSIGNED',
			'DECIMAL'			=> 'decimal(5,2)',
			'DECIMAL:'		=> 'decimal(%d,2)',
			'PDECIMAL'		=> 'decimal(6,3)',
			'PDECIMAL:'		=> 'decimal(%d,3)',
			'VCHAR_UNI'		=> 'varchar(255)',
			'VCHAR_UNI:'	=> 'varchar(%d)',
			'VCHAR_CI'		=> 'varchar(255)',
			'VARBINARY'		=> 'varbinary(255)',
		),

		'mysql_40'			=> array(
			'INT:'				=> 'int(%d)',
			'BINT'				=> 'bigint(20)',
			'UINT'				=> 'mediumint(8) UNSIGNED',
			'UINT:'				=> 'int(%d) UNSIGNED',
			'TINT:'				=> 'tinyint(%d)',
			'USINT'				=> 'smallint(4) UNSIGNED',
			'BOOL'				=> 'tinyint(1) UNSIGNED',
			'VCHAR'				=> 'varbinary(255)',
			'VCHAR:'			=> 'varbinary(%d)',
			'CHAR:'				=> 'binary(%d)',
			'XSTEXT'			=> 'blob',
			'XSTEXT_UNI'	=> 'blob',
			'STEXT'				=> 'blob',
			'STEXT_UNI'		=> 'blob',
			'TEXT'				=> 'blob',
			'TEXT_UNI'		=> 'blob',
			'MTEXT'				=> 'mediumblob',
			'MTEXT_UNI'		=> 'mediumblob',
			'TIMESTAMP'		=> 'int(11) UNSIGNED',
			'DECIMAL'			=> 'decimal(5,2)',
			'DECIMAL:'		=> 'decimal(%d,2)',
			'PDECIMAL'		=> 'decimal(6,3)',
			'PDECIMAL:'		=> 'decimal(%d,3)',
			'VCHAR_UNI'		=> 'blob',
			'VCHAR_UNI:'	=> array('varbinary(%d)', 'limit' => array('mult', 3, 255, 'blob')),
			'VCHAR_CI'		=> 'blob',
			'VARBINARY'		=> 'varbinary(255)',
		),

	);

	/**
	* A list of types being unsigned for better reference in some db's
	* @var array
	*/
	var $unsigned_types = array('UINT', 'UINT:', 'USINT', 'BOOL', 'TIMESTAMP');

	/**
	* A list of supported DBMS. We change this class to support more DBMS, the DBMS itself only need to follow some rules.
	* @var array
	*/
	var $supported_dbms = array('mysql_40', 'mysql_41');

	/**
	* This is set to true if user only wants to return the 'to-be-executed' SQL statement(s) (as an array).
	* This mode has no effect on some methods (inserting of data for example). This is expressed within the methods command.
	*/
	var $return_statements = false;

	/**
	* Constructor. Set DB Object and set {@link $return_statements return_statements}.
	*
	* @param phpbb_dbal	$db					DBAL object
	* @param bool		$return_statements	True if only statements should be returned and no SQL being executed
	*/
	function phpbb_db_tools(&$db, $return_statements = false)
	{
		$this->db = $db;
		$this->return_statements = $return_statements;

		// Determine mapping database type
		switch ($this->db->sql_layer)
		{
			case 'mysql':
				$this->sql_layer = 'mysql_40';
			break;

			case 'mysql4':
				if (version_compare($this->db->sql_server_info(true), '4.1.3', '>='))
				{
					$this->sql_layer = 'mysql_41';
				}
				else
				{
					$this->sql_layer = 'mysql_40';
				}
			break;

			case 'mysqli':
				$this->sql_layer = 'mysql_41';
			break;

			default:
				$this->sql_layer = $this->db->sql_layer;
			break;
		}
	}

	/**
	* Check if table exists
	*
	*
	* @param string	$table_name	The table name to check for
	* @return bool true if table exists, else false
	*/
	function sql_table_exists($table_name)
	{
		$this->db->sql_return_on_error(true);
		$result = $this->db->sql_query_limit('SELECT * FROM ' . $table_name, 1);
		$this->db->sql_return_on_error(false);

		if ($result)
		{
			$this->db->sql_freeresult($result);
			return true;
		}

		return false;
	}

	/**
	* Create SQL Table
	*
	* @param string	$table_name	The table name to create
	* @param array	$table_data	Array containing table data.
	* @return array	Statements if $return_statements is true.
	*/
	function sql_create_table($table_name, $table_data)
	{
		// holds the DDL for a column
		$columns = $statements = array();

		if ($this->sql_table_exists($table_name))
		{
			return $this->_sql_run_sql($statements);
		}

		// Begin transaction
		$statements[] = 'begin';

		// Determine if we have created a PRIMARY KEY in the earliest
		$primary_key_gen = false;

		// Determine if the table must be created with TEXTIMAGE
		$create_textimage = false;

		// Determine if the table requires a sequence
		$create_sequence = false;

		// Begin table sql statement
		switch ($this->sql_layer)
		{
			default:
				$table_sql = 'CREATE TABLE ' . $table_name . ' (' . "\n";
			break;
		}

		// Iterate through the columns to create a table
		foreach ($table_data['COLUMNS'] as $column_name => $column_data)
		{
			// here lies an array, filled with information compiled on the column's data
			$prepared_column = $this->sql_prepare_column_data($table_name, $column_name, $column_data);

			// here we add the definition of the new column to the list of columns
			switch ($this->sql_layer)
			{
				default:
					$columns[] = "\t {$column_name} " . $prepared_column['column_type_sql'];
				break;
			}

			// see if we have found a primary key set due to a column definition if we have found it, we can stop looking
			if (!$primary_key_gen)
			{
				$primary_key_gen = isset($prepared_column['primary_key_set']) && $prepared_column['primary_key_set'];
			}

			// create textimage DDL based off of the existance of certain column types
			if (!$create_textimage)
			{
				$create_textimage = isset($prepared_column['textimage']) && $prepared_column['textimage'];
			}

			// create sequence DDL based off of the existance of auto incrementing columns
			if (!$create_sequence && isset($prepared_column['auto_increment']) && $prepared_column['auto_increment'])
			{
				$create_sequence = $column_name;
			}
		}

		// this makes up all the columns in the create table statement
		$table_sql .= implode(",\n", $columns);

		// we have yet to create a primary key for this table,
		// this means that we can add the one we really wanted instead
		if (!$primary_key_gen)
		{
			// Write primary key
			if (isset($table_data['PRIMARY_KEY']))
			{
				if (!is_array($table_data['PRIMARY_KEY']))
				{
					$table_data['PRIMARY_KEY'] = array($table_data['PRIMARY_KEY']);
				}

				$table_sql .= ",\n\t PRIMARY KEY (" . implode(', ', $table_data['PRIMARY_KEY']) . ')';
			}
		}

		// close the table
		switch ($this->sql_layer)
		{
			case 'mysql_41':
				// make sure the table is in UTF-8 mode
				$table_sql .= "\n) CHARACTER SET `utf8` COLLATE `utf8_bin`;";
				$statements[] = $table_sql;
			break;

			case 'mysql_40':
				$table_sql .= "\n);";
				$statements[] = $table_sql;
			break;

		}

		// Write Keys
		if (isset($table_data['KEYS']))
		{
			foreach ($table_data['KEYS'] as $key_name => $key_data)
			{
				if (!is_array($key_data[1]))
				{
					$key_data[1] = array($key_data[1]);
				}

				$old_return_statements = $this->return_statements;
				$this->return_statements = true;

				$key_stmts = ($key_data[0] == 'UNIQUE') ? $this->sql_create_unique_index($table_name, $key_name, $key_data[1]) : $this->sql_create_index($table_name, $key_name, $key_data[1]);

				foreach ($key_stmts as $key_stmt)
				{
					$statements[] = $key_stmt;
				}

				$this->return_statements = $old_return_statements;
			}
		}

		// Commit Transaction
		$statements[] = 'commit';

		return $this->_sql_run_sql($statements);
	}

	/**
	* Handle passed database update array.
	* Expected structure...
	* Key being one of the following
	*	change_columns: Column changes (only type, not name)
	*	add_columns: Add columns to a table
	*	drop_keys: Dropping keys
	*	drop_columns: Removing/Dropping columns
	*	add_primary_keys: adding primary keys
	*	add_unique_index: adding an unique index
	*	add_index: adding an index
	*
	* The values are in this format:
	*		{TABLE NAME}		=> array(
	*			{COLUMN NAME}		=> array({COLUMN TYPE}, {DEFAULT VALUE}, {OPTIONAL VARIABLES}),
	*			{KEY/INDEX NAME}	=> array({COLUMN NAMES}),
	*		)
	*
	* For more information have a look at /develop/create_schema_files.php (only available through SVN)
	*/
	function perform_schema_changes($schema_changes)
	{
		if (empty($schema_changes))
		{
			return;
		}

		$statements = array();
		$sqlite = false;

		// For SQLite we need to perform the schema changes in a much more different way
		if ($this->db->sql_layer == 'sqlite' && $this->return_statements)
		{
			$sqlite_data = array();
			$sqlite = true;
		}

		// Change columns?
		if (!empty($schema_changes['change_columns']))
		{
			foreach ($schema_changes['change_columns'] as $table => $columns)
			{
				foreach ($columns as $column_name => $column_data)
				{
					// If the column exists we change it, else we add it ;)
					if ($column_exists = $this->sql_column_exists($table, $column_name))
					{
						$result = $this->sql_column_change($table, $column_name, $column_data, true);
					}
					else
					{
						$result = $this->sql_column_add($table, $column_name, $column_data, true);
					}

					if ($sqlite)
					{
						if ($column_exists)
						{
							$sqlite_data[$table]['change_columns'][] = $result;
						}
						else
						{
							$sqlite_data[$table]['add_columns'][] = $result;
						}
					}
					elseif ($this->return_statements)
					{
						$statements = array_merge($statements, $result);
					}
				}
			}
		}

		// Add columns?
		if (!empty($schema_changes['add_columns']))
		{
			foreach ($schema_changes['add_columns'] as $table => $columns)
			{
				foreach ($columns as $column_name => $column_data)
				{
					// Only add the column if it does not exist yet, else change it (to be consistent)
					if ($column_exists = $this->sql_column_exists($table, $column_name))
					{
						$result = $this->sql_column_change($table, $column_name, $column_data, true);
					}
					else
					{
						$result = $this->sql_column_add($table, $column_name, $column_data, true);
					}

					if ($sqlite)
					{
						if ($column_exists)
						{
							$sqlite_data[$table]['change_columns'][] = $result;
						}
						else
						{
							$sqlite_data[$table]['add_columns'][] = $result;
						}
					}
					elseif ($this->return_statements)
					{
						$statements = array_merge($statements, $result);
					}
				}
			}
		}

		// Remove keys?
		if (!empty($schema_changes['drop_keys']))
		{
			foreach ($schema_changes['drop_keys'] as $table => $indexes)
			{
				foreach ($indexes as $index_name)
				{
					$result = $this->sql_index_drop($table, $index_name);

					if ($this->return_statements)
					{
						$statements = array_merge($statements, $result);
					}
				}
			}
		}

		// Drop columns?
		if (!empty($schema_changes['drop_columns']))
		{
			foreach ($schema_changes['drop_columns'] as $table => $columns)
			{
				foreach ($columns as $column)
				{
					// Only remove the column if it exists...
					if ($this->sql_column_exists($table, $column))
					{
						$result = $this->sql_column_remove($table, $column, true);
						$statements = array_merge($statements, $result);
					}
				}
			}
		}

		// Add primary keys?
		if (!empty($schema_changes['add_primary_keys']))
		{
			foreach ($schema_changes['add_primary_keys'] as $table => $columns)
			{
				$result = $this->sql_create_primary_key($table, $columns, true);
				$statements = array_merge($statements, $result);
			}
		}

		// Add unqiue indexes?
		if (!empty($schema_changes['add_unique_index']))
		{
			foreach ($schema_changes['add_unique_index'] as $table => $index_array)
			{
				foreach ($index_array as $index_name => $column)
				{
					$result = $this->sql_create_unique_index($table, $index_name, $column);

					if ($this->return_statements)
					{
						$statements = array_merge($statements, $result);
					}
				}
			}
		}

		// Add indexes?
		if (!empty($schema_changes['add_index']))
		{
			foreach ($schema_changes['add_index'] as $table => $index_array)
			{
				foreach ($index_array as $index_name => $column)
				{
					$result = $this->sql_create_index($table, $index_name, $column);

					if ($this->return_statements)
					{
						$statements = array_merge($statements, $result);
					}
				}
			}
		}

		if ($sqlite)
		{
			foreach ($sqlite_data as $table_name => $sql_schema_changes)
			{
				// Create temporary table with original data
				$statements[] = 'begin';

				$sql = "SELECT sql
					FROM sqlite_master
					WHERE type = 'table'
						AND name = '{$table_name}'
					ORDER BY type DESC, name;";
				$result = $this->db->sql_query($sql);

				if (!$result)
				{
					continue;
				}

				$row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				// Create a backup table and populate it, destroy the existing one
				$statements[] = preg_replace('#CREATE\s+TABLE\s+"?' . $table_name . '"?#i', 'CREATE TEMPORARY TABLE ' . $table_name . '_temp', $row['sql']);
				$statements[] = 'INSERT INTO ' . $table_name . '_temp SELECT * FROM ' . $table_name;
				$statements[] = 'DROP TABLE ' . $table_name;

				// Get the columns...
				preg_match('#\((.*)\)#s', $row['sql'], $matches);

				$plain_table_cols = trim($matches[1]);
				$new_table_cols = preg_split('/,(?![\s\w]+\))/m', $plain_table_cols);
				$column_list = array();

				foreach ($new_table_cols as $declaration)
				{
					$entities = preg_split('#\s+#', trim($declaration));
					if ($entities[0] == 'PRIMARY')
					{
						continue;
					}
					$column_list[] = $entities[0];
				}

				// note down the primary key notation because sqlite only supports adding it to the end for the new table
				$primary_key = false;
				$_new_cols = array();

				foreach ($new_table_cols as $key => $declaration)
				{
					$entities = preg_split('#\s+#', trim($declaration));
					if ($entities[0] == 'PRIMARY')
					{
						$primary_key = $declaration;
						continue;
					}
					$_new_cols[] = $declaration;
				}

				$new_table_cols = $_new_cols;

				// First of all... change columns
				if (!empty($sql_schema_changes['change_columns']))
				{
					foreach ($sql_schema_changes['change_columns'] as $column_sql)
					{
						foreach ($new_table_cols as $key => $declaration)
						{
							$entities = preg_split('#\s+#', trim($declaration));
							if (strpos($column_sql, $entities[0] . ' ') === 0)
							{
								$new_table_cols[$key] = $column_sql;
							}
						}
					}
				}

				if (!empty($sql_schema_changes['add_columns']))
				{
					foreach ($sql_schema_changes['add_columns'] as $column_sql)
					{
						$new_table_cols[] = $column_sql;
					}
				}

				// Now drop them...
				if (!empty($sql_schema_changes['drop_columns']))
				{
					foreach ($sql_schema_changes['drop_columns'] as $column_name)
					{
						// Remove from column list...
						$new_column_list = array();
						foreach ($column_list as $key => $value)
						{
							if ($value === $column_name)
							{
								continue;
							}

							$new_column_list[] = $value;
						}

						$column_list = $new_column_list;

						// Remove from table...
						$_new_cols = array();
						foreach ($new_table_cols as $key => $declaration)
						{
							$entities = preg_split('#\s+#', trim($declaration));
							if (strpos($column_name . ' ', $entities[0] . ' ') === 0)
							{
								continue;
							}
							$_new_cols[] = $declaration;
						}
						$new_table_cols = $_new_cols;
					}
				}

				// Primary key...
				if (!empty($sql_schema_changes['primary_key']))
				{
					$new_table_cols[] = 'PRIMARY KEY (' . implode(', ', $sql_schema_changes['primary_key']) . ')';
				}
				// Add a new one or the old primary key
				elseif ($primary_key !== false)
				{
					$new_table_cols[] = $primary_key;
				}

				$columns = implode(',', $column_list);

				// create a new table and fill it up. destroy the temp one
				$statements[] = 'CREATE TABLE ' . $table_name . ' (' . implode(',', $new_table_cols) . ');';
				$statements[] = 'INSERT INTO ' . $table_name . ' (' . $columns . ') SELECT ' . $columns . ' FROM ' . $table_name . '_temp;';
				$statements[] = 'DROP TABLE ' . $table_name . '_temp';

				$statements[] = 'commit';
			}
		}

		if ($this->return_statements)
		{
			return $statements;
		}
	}

	/**
	* Check if a specified column exist
	*
	* @param string	$table			Table to check the column at
	* @param string	$column_name	The column to check
	*
	* @return bool True if column exists, else false
	*/
	function sql_column_exists($table, $column_name)
	{
		$sql = "SHOW COLUMNS FROM $table";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// lower case just in case
			if (strtolower($row['Field']) == $column_name)
			{
				$this->db->sql_freeresult($result);
				return true;
			}
		}
		$this->db->sql_freeresult($result);
		return false;
	}

	/**
	* Private method for performing sql statements (either execute them or return them)
	* @access private
	*/
	function _sql_run_sql($statements)
	{
		if ($this->return_statements)
		{
			return $statements;
		}

		// We could add error handling here...
		foreach ($statements as $sql)
		{
			if ($sql === 'begin')
			{
				$this->db->sql_transaction('begin');
			}
			elseif ($sql === 'commit')
			{
				$this->db->sql_transaction('commit');
			}
			else
			{
				$this->db->sql_query($sql);
			}
		}

		return true;
	}

	/**
	* Function to prepare some column information for better usage
	* @access private
	*/
	function sql_prepare_column_data($table_name, $column_name, $column_data)
	{
		// Get type
		if (strpos($column_data[0], ':') !== false)
		{
			list($orig_column_type, $column_length) = explode(':', $column_data[0]);
			if (!is_array($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']))
			{
				$column_type = sprintf($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':'], $column_length);
			}
			else
			{
				if (isset($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['rule']))
				{
					switch ($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['rule'][0])
					{
						case 'div':
							$column_length /= $this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['rule'][1];
							$column_length = ceil($column_length);
							$column_type = sprintf($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':'][0], $column_length);
						break;
					}
				}

				if (isset($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['limit']))
				{
					switch ($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['limit'][0])
					{
						case 'mult':
							$column_length *= $this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['limit'][1];
							if ($column_length > $this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['limit'][2])
							{
								$column_type = $this->dbms_type_map[$this->sql_layer][$orig_column_type . ':']['limit'][3];
							}
							else
							{
								$column_type = sprintf($this->dbms_type_map[$this->sql_layer][$orig_column_type . ':'][0], $column_length);
							}
						break;
					}
				}
			}
			$orig_column_type .= ':';
		}
		else
		{
			$orig_column_type = $column_data[0];
			$column_type = $this->dbms_type_map[$this->sql_layer][$column_data[0]];
		}

		// Adjust default value if db-dependant specified
		if (is_array($column_data[1]))
		{
			$column_data[1] = (isset($column_data[1][$this->sql_layer])) ? $column_data[1][$this->sql_layer] : $column_data[1]['default'];
		}

		$sql = '';

		$return_array = array();

		$sql .= " {$column_type} ";

		// For hexadecimal values do not use single quotes
		if (!is_null($column_data[1]) && substr($column_type, -4) !== 'text' && substr($column_type, -4) !== 'blob')
		{
			$sql .= (strpos($column_data[1], '0x') === 0) ? "DEFAULT {$column_data[1]} " : "DEFAULT '{$column_data[1]}' ";
		}
		$sql .= 'NOT NULL';

		if (isset($column_data[2]))
		{
			if ($column_data[2] == 'auto_increment')
			{
				$sql .= ' auto_increment';
			}
			elseif ($this->sql_layer === 'mysql_41' && $column_data[2] == 'true_sort')
			{
				$sql .= ' COLLATE utf8_unicode_ci';
			}
		}

		$return_array['column_type_sql'] = $sql;

		return $return_array;
	}

	/**
	* Add new column
	*/
	function sql_column_add($table_name, $column_name, $column_data, $inline = false)
	{
		$column_data = $this->sql_prepare_column_data($table_name, $column_name, $column_data);
		$statements = array();

		$statements[] = 'ALTER TABLE `' . $table_name . '` ADD COLUMN `' . $column_name . '` ' . $column_data['column_type_sql'];
		return $this->_sql_run_sql($statements);
	}

	/**
	* Drop column
	*/
	function sql_column_remove($table_name, $column_name, $inline = false)
	{
		$statements = array();

		$statements[] = 'ALTER TABLE `' . $table_name . '` DROP COLUMN `' . $column_name . '`';
		return $this->_sql_run_sql($statements);
	}

	/**
	* Drop Index
	*/
	function sql_index_drop($table_name, $index_name)
	{
		$statements = array();

		$statements[] = 'DROP INDEX ' . $index_name . ' ON ' . $table_name;
		return $this->_sql_run_sql($statements);
	}

	/**
	* Drop Table
	*/
	function sql_table_drop($table_name)
	{
		$statements = array();

		if (!$this->sql_table_exists($table_name))
		{
			return $this->_sql_run_sql($statements);
		}

		// the most basic operation, get rid of the table
		$statements[] = 'DROP TABLE ' . $table_name;
		return $this->_sql_run_sql($statements);
	}

	/**
	* Add primary key
	*/
	function sql_create_primary_key($table_name, $column, $inline = false)
	{
		$statements = array();

		$statements[] = 'ALTER TABLE ' . $table_name . ' ADD PRIMARY KEY (' . implode(', ', $column) . ')';
		return $this->_sql_run_sql($statements);
	}

	/**
	* Add unique index
	*/
	function sql_create_unique_index($table_name, $index_name, $column)
	{
		$statements = array();

		$statements[] = 'CREATE UNIQUE INDEX ' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ')';
		return $this->_sql_run_sql($statements);
	}

	/**
	* Add index
	*/
	function sql_create_index($table_name, $index_name, $column)
	{
		$statements = array();

		$statements[] = 'CREATE INDEX ' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ')';
		return $this->_sql_run_sql($statements);
	}

	/**
	* List all of the indices that belong to a table,
	* does not count:
	* * UNIQUE indices
	* * PRIMARY keys
	*/
	function sql_list_index($table_name)
	{
		$index_array = array();

		if ($this->sql_layer == 'mssql')
		{
			$sql = "EXEC sp_statistics '$table_name'";
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($row['TYPE'] == 3)
				{
					$index_array[] = $row['INDEX_NAME'];
				}
			}
			$this->db->sql_freeresult($result);
		}
		else
		{
			$sql = 'SHOW KEYS
				FROM ' . $table_name;
			$col = 'Key_name';

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				if (!$row['Non_unique'])
				{
					continue;
				}
				$index_array[] = $row[$col];
			}
			$this->db->sql_freeresult($result);
		}

		return array_map('strtolower', $index_array);
	}

	/**
	* Change column type (not name!)
	*/
	function sql_column_change($table_name, $column_name, $column_data, $inline = false)
	{
		$column_data = $this->sql_prepare_column_data($table_name, $column_name, $column_data);
		$statements = array();

		$statements[] = 'ALTER TABLE `' . $table_name . '` CHANGE `' . $column_name . '` `' . $column_name . '` ' . $column_data['column_type_sql'];
		return $this->_sql_run_sql($statements);
	}
}

?>