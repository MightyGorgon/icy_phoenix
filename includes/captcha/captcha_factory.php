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
* A small class for 3.0.x (no autoloader in 3.0.x)
*
* @package VC
*/
class phpbb_captcha_factory
{
	/**
	* return an instance of class $name in file $name_plugin.php
	*/
	function &get_instance($name)
	{
		$name = basename($name);
		if (!class_exists($name))
		{
			include(IP_ROOT_PATH . "includes/captcha/plugins/{$name}_plugin." . PHP_EXT);
		}
		$instance = call_user_func(array($name, 'get_instance'));
		return $instance;
	}

	/**
	* Call the garbage collector
	*/
	function garbage_collect($name)
	{
		$name = basename($name);
		if (!class_exists($name))
		{
			include(IP_ROOT_PATH . "includes/captcha/plugins/{$name}_plugin." . PHP_EXT);
		}
		call_user_func(array($name, 'garbage_collect'), 0);
	}

	/**
	* return a list of all discovered CAPTCHA plugins
	*/
	function get_captcha_types()
	{
		$captchas = array(
			'available' => array(),
			'unavailable' => array(),
		);

		$dp = @opendir(IP_ROOT_PATH . 'includes/captcha/plugins');

		if ($dp)
		{
			while (($file = readdir($dp)) !== false)
			{
				if ((preg_match('#_plugin\.' . PHP_EXT . '$#', $file)))
				{
					$name = preg_replace('#^(.*?)_plugin\.' . PHP_EXT . '$#', '\1', $file);
					if (!class_exists($name))
					{
						include(IP_ROOT_PATH . "includes/captcha/plugins/$file");
					}

					if (call_user_func(array($name, 'is_available')))
					{
						$captchas['available'][$name] = call_user_func(array($name, 'get_name'));
					}
					else
					{
						$captchas['unavailable'][$name] = call_user_func(array($name, 'get_name'));
					}
				}
			}
			closedir($dp);
		}

		return $captchas;
	}

}

?>