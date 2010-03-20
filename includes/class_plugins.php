<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Plugins management
*/
class class_plugins
{

	var $config = array();
	var $modules = array();
	var $list_yes_no = array('Yes' => 1, 'No' => 0);

	/*
	* Get plugins list
	*/
	function get_plugins_list()
	{
		$plugins_list = array();
		$plugins_path = IP_ROOT_PATH . PLUGINS_PATH;
		$plugins_dir = @opendir($plugins_path);
		while (($plugins_subdir = @readdir($plugins_dir)) !== false)
		{
			$exclude_dirs = array('.', '..');
			if (is_dir($plugins_path . $plugins_subdir) && !in_array($plugins_subdir, $exclude_dirs))
			{
				$plugin_info_file = $plugins_path . $plugins_subdir . '/info.' . PHP_EXT;
				if (file_exists($plugin_info_file))
				{
					@include($plugin_info_file);
					$plugins_list[$plugins_subdir] = array(
						'dir' => $plugins_subdir,
						'config' => (!empty($plugin_details['config']) ? $plugin_details['config'] : $plugins_subdir),
						'name' => (!empty($plugin_details['name']) ? $plugin_details['name'] : $plugins_subdir),
						'description' => (!empty($plugin_details['description']) ? $plugin_details['description'] : ''),
					);
				}
			}
		}
		@closedir($plugins_dir);
		ksort($plugins_list);
		return $plugins_list;
	}

	/**
	* Set plugin config (plugins enabled / disabled and dirs)
	*/
	function set_config($plugin_data, $clear_cache = true, $return = false)
	{
		global $db, $cache, $config, $lang;

		$sql = "UPDATE " . PLUGINS_TABLE . "
			SET plugin_dir = '" . $db->sql_escape($plugin_data['dir']) . "',
				plugin_enabled = '" . $db->sql_escape($plugin_data['enabled']) . "'
			WHERE plugin_name = '" . $db->sql_escape($plugin_data['name']) . "'";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);

		if (!$db->sql_affectedrows())
		{
			$sql = "INSERT INTO " . PLUGINS_TABLE . " (`plugin_name`, `plugin_dir`, `plugin_enabled`)
							VALUES ('" . $db->sql_escape($plugin_data['name']) . "', '" . $db->sql_escape($plugin_data['dir']) . "', '" . $db->sql_escape($plugin_data['enabled']) . "')";
			$db->sql_return_on_error($return);
			$db->sql_query($sql);
			$db->sql_return_on_error(false);
		}

		if ($clear_cache)
		{
			$this->cache_clear();
		}
	}

	/**
	* Remove plugin config
	*/
	function remove_config($plugin_data, $clear_cache = true, $return = false)
	{
		global $db, $cache, $config, $lang;

		$sql = "DELETE FROM " . PLUGINS_TABLE . " WHERE plugin_name = '" . $db->sql_escape($plugin_data['name']) . "'";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);

		if ($clear_cache)
		{
			$this->cache_clear();
		}
	}

	/**
	* Set plugin config value
	*/
	function set_plugin_config($config_name, $config_value, $clear_cache = true, $return = false)
	{
		global $db, $cache, $config, $lang;

		$sql = "UPDATE " . PLUGINS_CONFIG_TABLE . "
			SET config_value = '" . $db->sql_escape($config_value) . "'
			WHERE config_name = '" . $db->sql_escape($config_name) . "'";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);

		if (!$db->sql_affectedrows() && !isset($this->config[$config_name]))
		{
			$sql = "INSERT INTO " . PLUGINS_CONFIG_TABLE . " (`config_name`, `config_value`)
							VALUES ('" . $db->sql_escape($config_name) . "', '" . $db->sql_escape($config_value) . "')";
			$db->sql_return_on_error($return);
			$db->sql_query($sql);
			$db->sql_return_on_error(false);
		}

		$this->config[$config_name] = $config_value;

		if ($clear_cache)
		{
			$this->cache_clear();
		}
	}

	/**
	* Get plugin config values
	*/
	function get_plugin_config($plugin_prefix = '', $from_cache = true)
	{
		global $db, $cache, $config, $lang;

		$plugin_config = array();
		$from_cache = ($from_cache && (CACHE_CFG == true) && !defined('IN_ADMIN') && !defined('IN_CMS')) ? true : false;
		$sql_where = "";
		if (!empty($plugin_prefix))
		{
			$sql_where = " WHERE config_name LIKE '" . $plugin_prefix . "%'";
		}
		$sql = "SELECT * FROM " . PLUGINS_CONFIG_TABLE . $sql_where;
		$result = $from_cache ? $db->sql_query($sql, 0, 'config_plugins_config_' . $plugin_prefix) : $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$config_name = $row['config_name'];
			$config_value = stripslashes($row['config_value']);
			$plugin_config[$config_name] = $config_value;
			$this->config[$config_name] = $config_value;
		}
		$db->sql_freeresult($result);

		return $plugin_config;
	}

	/*
	* Get the user config if defined
	*/
	function user_config_key($key, $user_field = '', $over_field = '')
	{
		global $config, $userdata;

		// Get the user fields name if not given
		if (empty($user_field))
		{
			$user_field = 'user_' . $key;
		}

		// Get the overwrite allowed switch name if not given
		if (empty($over_field))
		{
			$over_field = $key . '_over';
		}

		// Does the key exists?
		if (!isset($this->config[$key])) return;

		// Does the user field exists ?
		if (!isset($userdata[$user_field])) return;

		// Does the overwrite switch exists?
		if (!isset($this->config[$over_field]))
		{
			$this->config[$over_field] = 0; // no overwrite
		}

		// Overwrite with the user data only if not overwrite set, not anonymous, logged in
		// If the user is admin we will not overwrite his setting either...
		if ((!intval($this->config[$over_field]) && ($userdata['user_id'] != ANONYMOUS) && $userdata['session_logged_in']) || ($userdata['user_level'] == ADMIN))
		{
			$this->config[$key] = $userdata[$user_field];
		}
		else
		{
			$userdata[$user_field] = $this->config[$key];
		}
	}

	/*
	* Initialize plugins configuration
	*/
	function init_plugins_config($settings_details, $settings_data)
	{
		global $db, $cache, $config, $lang;

		@reset($settings_data);
		while (list($config_key, $config_data) = each($settings_data))
		{
			if (!isset($config_data['user_only']) || !$config_data['user_only'])
			{
				// Create the key value
				$config_value = (!empty($config_data['values']) ? $config_data['values'][$config_data['default']] : $config_data['default']);
				if (!isset($this->config[$config_key]))
				{
					$this->set_plugin_config($config_key, $config_value, false, false);
				}
				if (!empty($config_data['user']))
				{
					$config_key_over = $config_key . '_over';
					if (!isset($config[$config_key_over]))
					{
						// Create the "overwrite user choice" value
						$this->set_plugin_config($config_key_over, 0, false, false);
					}

					// Get user choice value
					$this->user_config_key($config_key, $config_data['user']);
				}
			}

			// Deliver it for input only if not hidden
			if (!isset($config_data['hide']) || !$config_data['hide'])
			{
				$this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['data'][$settings_details['sub_name']]['data'][$config_key] = $config_data;

				// Sort values: overwrite only if not yet provided
				if (empty($this->modules[$settings_details['menu_name']]['sort']) || ($this->modules[$settings_details['menu_name']]['sort'] == 0))
				{
					$this->modules[$settings_details['menu_name']]['sort'] = $settings_details['menu_sort'];
				}
				if (empty($this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['sort']) || ($this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['sort'] == 0))
				{
					$this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['sort'] = $settings_details['sort'];
				}
				if (empty($this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['data'][$settings_details['sub_name']]['sort']) || ($this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['data'][$settings_details['sub_name']]['sort'] == 0))
				{
					$this->modules[$settings_details['menu_name']]['data'][$settings_details['name']]['data'][$settings_details['sub_name']]['sort'] = $settings_details['sub_sort'];
				}
			}
		}

		if ($settings_details['clear_cache'])
		{
			$this->cache_clear();
		}
	}

	/*
	* Get template file: check if template file exists and set the correct path to template file
	*/
	function get_tpl_file($tpl_base_path, $tpl_file)
	{
		global $theme;

		$tpl_path = $tpl_base_path . 'default/' . $tpl_file;
		$tpl_temp_file = $tpl_base_path . $theme['template_name'] . '/' . $tpl_file;
		if (file_exists($tpl_temp_file))
		{
			$tpl_path = $tpl_temp_file;
		}
		return $tpl_path;
	}

	/*
	* Get lang var
	*/
	function get_lang($key)
	{
		global $lang;
		return ((!empty($key) && isset($lang[$key])) ? $lang[$key] : $key);
	}

	/**
	* Setup plugin
	*/
	function setup_plugin($plugin_dir)
	{
		global $db, $cache, $config, $lang;

		// Search for modules...
		$plugin_path = IP_ROOT_PATH . PLUGINS_PATH . basename($plugin_dir) . '/' . ADM . '/';
		$dir = @opendir($plugin_path);

		if ($dir)
		{
			while (($file = @readdir($dir)) !== false)
			{
				if ((strpos($file, 'config_') === 0) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
				{
					@include($plugin_path . $file);
				}
			}
			@closedir($dir);
		}

		return true;
	}

	/**
	* Cache clear
	*/
	function cache_clear()
	{
		global $db, $cache, $config, $lang;

		$cache->destroy('config_plugins');
		$db->clear_cache('config_plugins_');

		$cache->destroy('config_plugins_config');
		$db->clear_cache('config_plugins_config_');

		return true;
	}

}

?>