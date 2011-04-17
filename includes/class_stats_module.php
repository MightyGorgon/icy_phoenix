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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Public Class all Modules are able to use
class Statistics
{
	// init_bar Variables
	var $bar_loaded = true;
	var $loaded_bar_images = array();

	// do_math variables
	var $percentage = 0;
	var $bar_percent = 0;

	// forum auth variables
	var $auth_loaded = false;
	var $previous_auth = AUTH_VIEW;
	var $auth_data_sql = '';

	// cached methods used ?
	var $result_cache_used = false;
	var $db_cache_used = false;

	function Statistics()
	{
	}

	// Setup Bars for Modules (for example Top Posters, Top Smilies etc...)
	// Mighty Gorgon: code removed because it has been replaced with standard Icy Phoenix template system
	function init_bars($bars = '')
	{
	}

	//
	// Do Percentage and Bar Percentage Calculation
	//
	// Variables:
	//		firstval: The biggest value from the 'thing'
	//		value: The current Value from the 'thing'
	//		total: The complete number of all 'things'
	//
	function do_math($firstval, $value, $total)
	{
		$cst = ($firstval > 0) ? 90 / $firstval : 90;

		if ($value != 0)
		{
			$this->percentage = ($total) ? round(min(100, ($value / $total) * 100)) : 0;
		}
		else
		{
			$percentage = 0;
		}

		$this->bar_percent = round($value * $cst);
	}

	// Forum Auth (Returns an Forum SQL ID String)
	function forum_auth($user_data, $auth = AUTH_VIEW)
	{
		global $db;

		if (($this->auth_loaded) && ($this->previous_auth == $auth))
		{
			return ($this->auth_data_sql);
		}

		$this->auth_data_sql = '';

		$is_auth_ary = auth($auth, AUTH_LIST_ALL, $user_data);

		$sql = "SELECT forum_id
			FROM " . FORUMS_TABLE . "
			WHERE forum_type = " . FORUM_POST . "
			ORDER BY forum_order";
		$result = $db->sql_query($sql, 0, 'forums_ids_');
		while ($row = $db->sql_fetchrow($result))
		{
			if ($is_auth_ary[$row['forum_id']]['auth_view'])
			{
				$this->auth_data_sql .= ($this->auth_data_sql != '') ? ', ' . $row['forum_id'] : $row['forum_id'];
			}
		}

		$this->auth_loaded = true;
		$this->previous_auth = $auth;
		return ($this->auth_data_sql);
	}
}

// cached database results
class cached_db
{
	var $n = array();
	var $fs = array();
	var $f = array();

	function cached_db($numrows, $fetchrowset, $fetchrow)
	{
		$this->n = $numrows;
		$this->fs = $fetchrowset;
		$this->f = $fetchrow;
	}
}

class StatisticsDB
{
	var $db_result = array();
	var $index = -2;
	var $numrows_data = array();
	var $fetchrowset_data = array();
	var $fetchrow_data = array();
	var $db_cached = false;
	var $use_cache = false;
	var $curr_n_row = 0;
	var $curr_fs_row = 0;
	var $curr_f_row = 0;

	function StatisticsDB()
	{
	}

	function begin_cached_query($cache_enabled = false, $cached_data = '')
	{
		$this->db_result = array();
		$this->numrows_data = array();
		$this->fetchrowset_data = array();
		$this->fetchrow_data = array();
		$this->index = -1;
		$this->db_cached = false;
		$this->use_cache = false;

		if ($cache_enabled)
		{
			$this->db_cached = true;
			$this->use_cache = true;
			$data = unserialize($cached_data);
			$this->numrows_data = $data->n;
			$this->fetchrowset_data = $data->fs;
			$this->fetchrow_data = $data->f;
			$this->curr_n_row = 0;
			$this->curr_fs_row = 0;
			$this->curr_f_row = 0;
		}
	}

	function begin_new_transaction()
	{
	}

	function end_previous_transaction()
	{
	}

	function sql_query($query = "", $transaction = false)
	{
		global $db;

		if ($this->index == -2)
		{
			// Not called begin_cached_query... we will do it then
			$this->begin_cached_query();
		}

		if (!$this->db_cached)
		{
			$this->db_cached = true;
		}

		if ($this->index > 0)
		{
			$this->end_previous_transaction();
		}

		$this->index++;

		if (!$this->use_cache)
		{
			$result = $db->sql_query($query, $transaction);
		}
		else
		{
			$result = $this->index;
			$this->curr_n_row = 0;
			$this->curr_fs_row = 0;
			$this->curr_f_row = 0;
		}

		$this->db_result[$this->index] = $result;

		$this->begin_new_transaction();

		if (!$this->use_cache)
		{
			return ($result);
		}
		else
		{
			return (true);
		}
	}

	function sql_numrows($query_id = 0)
	{
		global $db;

		if (!$this->use_cache)
		{
			$result = $db->sql_numrows($query_id);
			$this->numrows_data[$this->index][] = $result;
		}
		else
		{
			$result = $this->numrows_data[$this->index][$this->curr_n_row++];
		}

		return ($result);
	}

	function sql_fetchrowset($query_id = 0)
	{
		global $db;

		if (!$this->use_cache)
		{
			$result = $db->sql_fetchrowset($query_id);
			$this->fetchrowset_data[$this->index][] = $result;
		}
		else
		{
			$result = $this->fetchrowset_data[$this->index][$this->curr_fs_row++];
		}

		return ($result);
	}

	function sql_fetchrow($query_id = 0)
	{
		global $db;

		if (!$this->use_cache)
		{
			$result = $db->sql_fetchrow($query_id);
			$this->fetchrow_data[$this->index][] = $result;
		}
		else
		{
			$result = $this->fetchrow_data[$this->index][$this->curr_f_row++];
		}

		return ($result);
	}

	/**
	* Escape string used in sql query
	*/
	function sql_escape($msg)
	{
		return @mysql_real_escape_string($msg);
	}

	function end_cached_query($module_id)
	{
		global $db;

		if ($this->use_cache)
		{
			return;
		}

		if (!$this->db_cached)
		{
			$sql = "UPDATE " . MODULES_TABLE . "
			SET module_db_cache = ''
			WHERE module_id = " . $module_id;
		}
		else
		{
			$data = new cached_db($this->numrows_data, $this->fetchrowset_data, $this->fetchrow_data);
			$sql = "UPDATE " . MODULES_TABLE . "
			SET module_db_cache = '" . $this->sql_escape(serialize($data)) . "',
			module_cache_time = " . time() . "
			WHERE module_id = " . $module_id;
		}
		$db->sql_query($sql);
	}
}

// cached result data
class cached_result
{
	var $var_data = array();

	function cached_result($var_data)
	{
		$this->var_data = $var_data;
	}
}


class Results
{
	var $result_enabled = false;
	var $var_data = array();
	var $index = -2;
	var $use_cache = false;

	function Results()
	{
	}

	function begin_cached_results($cache_enabled = false, $cached_data = '')
	{
		$this->result_enabled = false;
		$this->var_data = array();
		$this->index = -1;
		$this->use_cache = false;

		if ($cache_enabled)
		{
			$this->use_cache = true;
			//$cached_data = stripslashes($cached_data);
			$data = unserialize($cached_data);
			$this->var_data = $data->var_data;
		}
	}

	function begin_new_transaction()
	{
	}

	function end_previous_transaction()
	{
	}

	function init_result_cache()
	{
		$this->result_enabled = true;
	}

	// Assign vars to the result cache
	// Module Authors are able to use cached variables
	function assign_vars($vararray)
	{
		if ($this->use_cache)
		{
			return;
		}

		reset ($vararray);
		while (list($key, $val) = each($vararray))
		{
			$this->var_data['.'][0][$key] = $val;
		}
	}

	// Assign Block Vars to the Result Cache
	function assign_block_vars($blockname, $vararray)
	{
		if ($this->use_cache)
		{
			return;
		}

		$this->var_data[$blockname . '.'][] = $vararray;
	}

	// Assign the last to the template added Block Iteration
	function assign_template_block_vars($blockname)
	{
		global $template;
		$this->var_data[$blockname . '.'][] = end($template->_tpldata[$blockname . '.']);
	}

	// Get Variable from Cache
	function get_var($key)
	{
		if (!$this->use_cache)
		{
			return;
		}

		return ($this->var_data['.'][0][$key]);
	}

	// Get number of Variables from cached Block
	function block_num_vars($blockname)
	{
		if (!$this->use_cache)
		{
			return;
		}

		return (sizeof($this->var_data[$blockname . '.']));
	}

	// Get Variable Array from cached Block
	function get_block_array($blockname, $count)
	{
		if (!$this->use_cache)
		{
			return;
		}

		return ($this->var_data[$blockname . '.'][$count]);
	}

	// Get Variable from Cached Block
	function get_block_var($blockname, $key, $count)
	{
		if (!$this->use_cache)
		{
			return;
		}

		return ($this->var_data[$blockname . '.'][$count][$key]);
	}

	/**
	* Escape string used in sql query
	*/
	function sql_escape($msg)
	{
		return @mysql_real_escape_string($msg);
	}

	function end_cached_query($module_id)
	{
		global $db;

		if ($this->use_cache)
		{
			return;
		}

		if (!$this->result_enabled)
		{
			$sql = "UPDATE " . MODULES_TABLE . "
			SET module_result_cache = ''
			WHERE module_id = " . $module_id;
		}
		else
		{
			$data = new cached_result($this->var_data);
			$sql = "UPDATE " . MODULES_TABLE . "
			SET module_result_cache = '" . $this->sql_escape(serialize($data)) . "',
			module_cache_time = " . time() . "
			WHERE module_id = " . $module_id;
		}
		$db->sql_query($sql);
	}
}

$statistics = new Statistics;
$stat_db = new StatisticsDB;
$result_cache = new Results;

?>