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
* Set links config values
*/
function set_links_config($config_name, $config_value, $clear_cache = true, $return = false)
{
	global $db, $cache, $links_config;

	$sql = "UPDATE " . LINK_CONFIG_TABLE . "
		SET config_value = '" . $db->sql_escape($config_value) . "'
		WHERE config_name = '" . $db->sql_escape($config_name) . "'";
	$db->sql_return_on_error($return);
	$db->sql_query($sql);
	$db->sql_return_on_error(false);

	if (!$db->sql_affectedrows() && !isset($config[$config_name]))
	{
		$sql = "INSERT INTO " . LINK_CONFIG_TABLE . " (`config_name`, `config_value`)
						VALUES ('" . $db->sql_escape($config_name) . "', '" . $db->sql_escape($config_value) . "')";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);
	}

	$links_config[$config_name] = $config_value;

	if ($clear_cache)
	{
		$db->clear_cache('links_config_');
	}
}

/*
* Get links config
*/
function get_links_config($from_cache = true)
{
	global $db;

	$config = array();
	$from_cache = ($from_cache && (CACHE_CFG == true) && !defined('IN_ADMIN') && !defined('IN_CMS')) ? true : false;
	$sql = "SELECT * FROM " . LINK_CONFIG_TABLE;
	$result = $from_cache ? $db->sql_query($sql, 0, 'links_config_') : $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$config[$row['config_name']] = stripslashes($row['config_value']);
	}
	$db->sql_freeresult($result);

	return $config;
}

?>