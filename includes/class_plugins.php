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

	var $plugin_name = '';
	var $plugin_version = '';
	var $plugin_dir = '';

	var $plugins_path = '';
	var $plugins_settings_path = '';

	var $config = array();
	var $settings = array();
	var $modules = array();
	var $list_yes_no = array('Yes' => 1, 'No' => 0);

	/**
	* Instantiate class
	*/
	function class_plugins()
	{
		$this->plugins_path = IP_ROOT_PATH . PLUGINS_PATH;
		$this->plugins_settings_path = 'settings';
	}

	/**
	* Install plugin
	*/
	function install($plugin_data, $clear_cache = true)
	{
		global $db, $config, $table_prefix;

		$sql_results = array();
		$plugin_info = $this->get_plugin_info($plugin_data['dir']);
		$plugin_install_data = $this->get_plugin_install_data($plugin_data['dir']);
		if (!empty($plugin_install_data))
		{
			foreach ($plugin_install_data as $version => $instructions)
			{
				if (!empty($plugin_install_data[$version]['sql']))
				{
					// We need to force this because in MySQL 5.5.5 the new default DB Engine is InnoDB, not MyISAM any more
					$sql_engine = "SET storage_engine=MYISAM";
					$db->sql_return_on_error(true);
					$db->sql_query($sql_engine);
					$db->sql_return_on_error(false);

					foreach ($plugin_install_data[$version]['sql'] as $sql_statement)
					{
						$error = array();
						$message = '';
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql_statement);
						if (!$result)
						{
							$error = $db->sql_error();
							$message = $error['message'];
						}
						// This has to be here, otherwise we are not able to catch all errors by using $db->sql_error()
						$db->sql_return_on_error(false);
						$sql_results[] = array(
							'sql' => $sql_statement,
							'message' => htmlspecialchars($message),
							'success' => empty($message) ? true : false
						);
					}
				}

				if (!empty($plugin_install_data[$version]['functions']))
				{
					foreach ($plugin_install_data[$version]['functions'] as $install_function)
					{
						eval($install_function);
					}
				}
			}
		}

		$plugin_data['name'] = $plugin_info['config'];
		$plugin_data['version'] = $plugin_info['version'];
		$plugin_data['enabled'] = 1;
		$this->set_config($plugin_data, false, true);

		if ($clear_cache)
		{
			$this->cache_clear();
		}

		return $sql_results;
	}

	/**
	* Update plugin
	*/
	function update($plugin_data, $clear_cache = true)
	{
		global $db, $config, $table_prefix;

		$sql_results = array();
		$plugin_info = $this->get_plugin_info($plugin_data['dir']);
		$plugin_install_data = $this->get_plugin_install_data($plugin_data['dir']);
		if (!empty($plugin_install_data))
		{
			foreach ($plugin_install_data as $version => $instructions)
			{
				if (version_compare($plugin_data['version'], $version, '<'))
				{
					if (!empty($plugin_install_data[$version]['sql']))
					{
						foreach ($plugin_install_data[$version]['sql'] as $sql_statement)
						{
							$error = array();
							$message = '';
							$db->sql_return_on_error(true);
							$result = $db->sql_query($sql_statement);
							$db->sql_return_on_error(false);
							if (!$result)
							{
								$error = $db->sql_error();
								$message = $error['message'];
							}
							$sql_results[] = array(
								'sql' => $sql_statement,
								'message' => htmlspecialchars($message),
								'success' => empty($message) ? true : false
							);
						}
					}

					if (!empty($plugin_install_data[$version]['functions']))
					{
						foreach ($plugin_install_data[$version]['functions'] as $install_function)
						{
							eval($install_function);
						}
					}
				}
			}
		}

		$plugin_data['version'] = !empty($plugin_info['version']) ? $plugin_info['version'] : $plugin_data['version'];
		$this->set_config($plugin_data, true, true);

		if ($clear_cache)
		{
			$this->cache_clear();
		}

		return $sql_results;
	}

	/**
	* Uninstall plugin
	*/
	function uninstall($plugin_data, $clear_cache = true)
	{
		global $db, $config, $table_prefix;

		$sql_results = array();
		$plugin_info = $this->get_plugin_info($plugin_data['dir']);
		$plugin_uninstall_data = $this->get_plugin_uninstall_data($plugin_data['dir']);
		if (!empty($plugin_uninstall_data))
		{
			foreach ($plugin_uninstall_data['sql'] as $sql_statement)
			{
				$error = array();
				$message = '';
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql_statement);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					$error = $db->sql_error();
					$message = $error['message'];
				}
				$sql_results[] = array(
					'sql' => $sql_statement,
					'message' => htmlspecialchars($message),
					'success' => empty($message) ? true : false
				);
			}
			foreach ($plugin_uninstall_data['functions'] as $uninstall_function)
			{
				eval($uninstall_function);
			}
		}

		if ($clear_cache)
		{
			$this->cache_clear();
		}

		return $sql_results;
	}

	/*
	* Get plugin info
	*/
	function get_plugin_info($plugin_dir)
	{
		global $lang;

		$plugin_info = array();
		$plugin_info_file = $this->plugins_path . $plugin_dir . '/info.' . PHP_EXT;
		if (file_exists($plugin_info_file))
		{
			$plugin_info_lang_path = $this->plugins_path . $plugin_dir . '/language/';
			setup_extra_lang(array('lang_info'), $plugin_info_lang_path);

			@include($plugin_info_file);
			$plugin_info = array(
				'dir' => $plugin_dir,
				'config' => (!empty($plugin_details['config']) ? $plugin_details['config'] : $plugins_subdir),
				'name' => (!empty($plugin_details['name']) ? $plugin_details['name'] : $plugins_subdir),
				'version' => (!empty($plugin_details['version']) ? $plugin_details['version'] : '1.0.0'),
				'description' => (!empty($plugin_details['description']) ? $plugin_details['description'] : ''),
			);
		}

		return $plugin_info;
	}

	/*
	* Get plugin install data
	*/
	function get_plugin_install_data($plugin_dir)
	{
		global $config, $table_prefix;

		$install_data = array();
		$plugin_install_file = $this->plugins_path . $plugin_dir . '/install/install.' . PHP_EXT;
		if (file_exists($plugin_install_file))
		{
			@include($plugin_install_file);
		}

		return $install_data;
	}

	/*
	* Get plugin uninstall data
	*/
	function get_plugin_uninstall_data($plugin_dir)
	{
		global $config, $table_prefix;

		$install_data = array();
		$plugin_install_file = $this->plugins_path . $plugin_dir . '/install/install.' . PHP_EXT;
		if (file_exists($plugin_install_file))
		{
			@include($plugin_install_file);
		}

		return $uninstall_data;
	}

	/*
	* Get plugins list
	*/
	function get_plugins_list()
	{
		$plugins_list = array();
		$plugins_dir = @opendir($this->plugins_path);
		while (($plugins_subdir = @readdir($plugins_dir)) !== false)
		{
			$exclude_dirs = array('.', '..');
			if (is_dir($this->plugins_path . $plugins_subdir) && !in_array($plugins_subdir, $exclude_dirs))
			{
				$plugin_info_file = $this->plugins_path . $plugins_subdir . '/info.' . PHP_EXT;
				if (file_exists($plugin_info_file))
				{
					$plugins_list[$plugins_subdir] = $this->get_plugin_info($plugins_subdir);
				}
			}
		}
		@closedir($plugins_dir);
		ksort($plugins_list);
		return $plugins_list;
	}

	/*
	* Get plugin config
	*/
	function get_config($plugin_dir)
	{
		global $db, $cache, $lang;

		$sql = "SELECT * FROM " . PLUGINS_TABLE . " WHERE plugin_dir = '" . $db->sql_escape($plugin_dir) . "'";
		$result = $db->sql_query($sql);

		$plugin_info = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$plugin_info = $row;
		}
		$db->sql_freeresult($result);

		return $plugin_info;
	}


	/**
	* Map plugin config data from db input
	*/
	function config_map($plugin_data, $plugin_data_input)
	{
		$plugin_data_map = array();
		if (!empty($plugin_data_input))
		{
			$plugin_data_map = array(
				'name' => !empty($plugin_data_input['plugin_name']) ? $plugin_data_input['plugin_name'] : (!empty($plugin_data['name']) ? $plugin_data['name'] : ''),
				'version' => !empty($plugin_data_input['plugin_version']) ? $plugin_data_input['plugin_version'] : (!empty($plugin_data['version']) ? $plugin_data['version'] : ''),
				'dir' => !empty($plugin_data_input['plugin_dir']) ? $plugin_data_input['plugin_dir'] : (!empty($plugin_data['dir']) ? $plugin_data['dir'] : ''),
				'enabled' => !empty($plugin_data_input['plugin_enabled']) ? $plugin_data_input['plugin_enabled'] : (!empty($plugin_data['enabled']) ? $plugin_data['enabled'] : 0),
			);
		}

		return $plugin_data_map;
	}

	/**
	* Set plugin config (plugins enabled / disabled and dirs)
	*/
	function set_config($plugin_data, $clear_cache = true, $return = false)
	{
		global $db, $cache, $config, $lang;

		$plugin_data_sql = array(
			'plugin_version' => !empty($plugin_data['version']) ? $plugin_data['version'] : '1.0.0',
			'plugin_dir' => !empty($plugin_data['dir']) ? $plugin_data['dir'] : $plugin_data['name'],
			'plugin_enabled' => !empty($plugin_data['enabled']) ? $plugin_data['enabled'] : '0'
		);
		$db->sql_build_insert_update($plugin_data_sql, true);

		$sql = "UPDATE " . PLUGINS_TABLE . " SET " . $db->sql_build_insert_update($plugin_data_sql, false) . "
			WHERE plugin_name = '" . $db->sql_escape($plugin_data['name']) . "'";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);

		if (!$db->sql_affectedrows())
		{
			$plugin_data_sql = array_merge(array('plugin_name' => $plugin_data['name']), $plugin_data_sql);
			$sql = "INSERT INTO " . PLUGINS_TABLE . " " . $db->sql_build_insert_update($plugin_data_sql, true);
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
		global $config, $user;

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
		if (!isset($user->data[$user_field])) return;

		// Does the overwrite switch exists?
		if (!isset($this->config[$over_field]))
		{
			$this->config[$over_field] = 0; // no overwrite
		}

		// Overwrite with the user data only if not overwrite set, not anonymous, logged in
		// If the user is admin we will not overwrite his setting either...
		if ((!intval($this->config[$over_field]) && ($user->data['user_id'] != ANONYMOUS) && $user->data['session_logged_in']) || ($user->data['user_level'] == ADMIN))
		{
			$this->config[$key] = $user->data[$user_field];
		}
		else
		{
			$user->data[$user_field] = $this->config[$key];
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
		if (@file_exists($tpl_temp_file))
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

	/*
	* Get plugin settings
	*/
	function get_plugin_db_settings($plugin_dir)
	{
		global $db, $cache, $config, $user, $lang;

		// Search for settings...
		$plugins_settings_path = IP_ROOT_PATH . PLUGINS_PATH . basename($plugin_dir) . '/' . $this->plugins_settings_path . '/';
		$dir = @opendir($plugins_settings_path);

		$list_yes_no = $this->list_yes_no;
		$current_time = time();

		if ($dir)
		{
			while (($file = @readdir($dir)) !== false)
			{
				if ((strpos($file, 'db_settings_') === 0) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
				{
					@include($plugins_settings_path . $file);

					$table_name = !empty($table_name) ? $table_name : substr(substr($file, 0, strlen($file) - (strlen(PHP_EXT) + 1)), strlen('db_settings_'));

					// Make sure every field has auths and defaults...
					foreach ($table_fields as $k => $v)
					{
						$table_fields[$k]['admin_level'] = (isset($table_fields[$k]['admin_level']) ? $table_fields[$k]['admin_level'] : AUTH_FOUNDER);
						$table_fields[$k]['input_level'] = (isset($table_fields[$k]['input_level']) ? $table_fields[$k]['input_level'] : AUTH_FOUNDER);
						$table_fields[$k]['edit_level'] = (isset($table_fields[$k]['edit_level']) ? $table_fields[$k]['edit_level'] : AUTH_FOUNDER);
						$table_fields[$k]['view_level'] = (isset($table_fields[$k]['view_level']) ? $table_fields[$k]['view_level'] : AUTH_FOUNDER);
						$table_fields[$k]['default'] = (isset($table_fields[$k]['default']) ? $table_fields[$k]['default'] : 0);
					}

					if (!empty($table_name) && !empty($table_fields))
					{
						$this->settings[$plugin_dir]['db_tables'][$table_name] = $table_fields;
					}
				}
			}
			@closedir($dir);
		}

		return true;
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