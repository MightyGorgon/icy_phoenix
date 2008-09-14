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

// CTracker_Ignore: File checked by human

// Public Class all Modules are able to use
class Statistics
{
	// init_bar Variables
	var $bar_loaded = false;
	var $loaded_bar_images = array();
	var $current_template_path = '';

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
		/*
		global $theme, $template;
		$colors = array(
			'black',
			'black_yellow',
			'blue',
			'cyan',
			'green',
			'lite_blue',
		);
		$color = $theme['body_background'];
		if(!in_array($color, $colors))
		{
			$color = '';
		}
		else
		{
			$color = '/' . $color;
		}
		$this->loaded_bar_images['left'] = 'images' . $color . '/vote_lcap.gif';
		$this->loaded_bar_images['right'] = 'images' . $color . '/vote_rcap.gif';
		$this->loaded_bar_images['bar'] = 'images' . $color . '/voting_bar.gif';
		*/
	}

	//
	// Setup Bars for Modules (for example Top Posters, Top Smilies etc...)
	//
	function init_bars($bars = '')
	{
		global $board_config, $userdata, $theme, $db, $template;

		/*
		if (is_array($bars))
		{
			$this->loaded_bar_images['left'] = $bars['left'];
			$this->loaded_bar_images['right'] = $bars['right'];
			$this->loaded_bar_images['bar'] = $bars['bar'];
		}

		if ($this->bar_loaded)
		{
			$template->assign_vars(array(
				'LEFT_GRAPH_IMAGE' => IP_ROOT_PATH . $this->current_template_path . $this->loaded_bar_images['left'],
				'RIGHT_GRAPH_IMAGE' => IP_ROOT_PATH . $this->current_template_path . $this->loaded_bar_images['right'],
				'GRAPH_IMAGE' => IP_ROOT_PATH . $this->current_template_path . $this->loaded_bar_images['bar'])
			);

			return;
		}
		*/

		//
		// Getting voting bar info
		//
		if(!$board_config['override_user_style'])
		{
			if(($userdata['user_id'] != ANONYMOUS) && (isset($userdata['user_style'])))
			{
				$style = $userdata['user_style'];
				if(!$theme)
				{
					$style =  $board_config['default_style'];
				}
			}
			else
			{
				$style =  $board_config['default_style'];
			}
		}
		else
		{
			$style =  $board_config['default_style'];
		}

		$sql = 'SELECT *
		FROM ' . THEMES_TABLE . '
		WHERE themes_id = ' . $style;

		if (!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Couldn\'t query database for theme info.');
		}

		if(!$row = $db->sql_fetchrow($result))
		{
			message_die(CRITICAL_ERROR, 'Couldn\'t get theme data for themes_id=' . $style . '.');
		}

		$this->current_template_path = 'templates/' . $row['template_name'] . '/';

		/*
		$template->assign_vars(array(
			'LEFT_GRAPH_IMAGE' => IP_ROOT_PATH . $this->current_template_path . $this->loaded_bar_images['left'],
			'RIGHT_GRAPH_IMAGE' => IP_ROOT_PATH . $this->current_template_path . $this->loaded_bar_images['right'],
			'GRAPH_IMAGE' => IP_ROOT_PATH . $this->current_template_path . $this->loaded_bar_images['bar'])
		);
		*/

		$this->bar_loaded = true;
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

	//
	// Forum Auth (Returns an Forum SQL ID String)
	//
	function forum_auth($userdata, $auth = AUTH_VIEW)
	{
		global $db;

		if (($this->auth_loaded) && ($this->previous_auth == $auth))
		{
			return ($this->auth_data_sql);
		}

		$this->auth_data_sql = '';

		$is_auth_ary = auth($auth, AUTH_LIST_ALL, $userdata);

		$sql = 'SELECT forum_id
			FROM ' . FORUMS_TABLE;

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t retrieve forum_id data', '', __LINE__, __FILE__, $sql);
		}

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

//
// cached database results
//
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
			$data = unserialize(stripslashes($cached_data));
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
			SET module_db_cache = '" . sql_quote(serialize($data)) . "',
			module_cache_time = " . time() . "
			WHERE module_id = " . $module_id;
		}

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Unable to update DB Cache', '', __LINE__, __FILE__, $sql);
		}
	}
}

//
// cached result data
//
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
			$data = unserialize(stripslashes($cached_data));
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

		$this->var_data[$blockname . '.'][] = $template->_tpldata[$blockname . '.'][count($template->_tpldata[$blockname . '.'])-1];
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

		return (count($this->var_data[$blockname . '.']));
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
			SET module_result_cache = '" . sql_quote(serialize($data)) . "',
			module_cache_time = " . time() . "
			WHERE module_id = " . $module_id;
		}

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Unable to update Result Cache', '', __LINE__, __FILE__, $sql);
		}
	}
}

$statistics = new Statistics;
$stat_db = new StatisticsDB;
$result_cache = new Results;

?>