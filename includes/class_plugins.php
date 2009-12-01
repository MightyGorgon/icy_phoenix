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
	* Set plugin config
	*/
	function set_config($plugin_data, $clear_cache = true, $return = false)
	{
		global $db, $cache, $config;

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
			$cache->destroy('config_plugins');
			//$db->clear_cache('config_plugins_');
		}
	}

	/**
	* Remove plugin config
	*/
	function remove_config($plugin_data, $clear_cache = true, $return = false)
	{
		global $db, $cache, $config;

		$sql = "DELETE FROM " . PLUGINS_TABLE . " WHERE plugin_name = '" . $db->sql_escape($plugin_data['name']) . "'";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);

		if ($clear_cache)
		{
			$cache->destroy('config_plugins');
			//$db->clear_cache('config_plugins_');
		}
	}
}

?>