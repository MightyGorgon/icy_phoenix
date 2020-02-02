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
* Placeholder for autoload
*/
if (!class_exists('phpbb_default_captcha'))
{
	include(IP_ROOT_PATH . 'includes/captcha/plugins/captcha_abstract.' . PHP_EXT);
}

/**
* @package VC
*/
class phpbb_captcha_gd_wave extends phpbb_default_captcha
{

	function __construct()
	{
		if (!class_exists('captcha'))
		{
			include_once(IP_ROOT_PATH . 'includes/captcha/captcha_gd_wave.' . PHP_EXT);
		}
	}

	function get_instance()
	{
		return new phpbb_captcha_gd_wave();
	}

	function is_available()
	{
		if (@extension_loaded('gd'))
		{
			return true;
		}

		if (!function_exists('can_load_dll'))
		{
			include(IP_ROOT_PATH . 'includes/functions_install.' . PHP_EXT);
		}

		return can_load_dll('gd');
	}

	function get_name()
	{
		return 'CAPTCHA_GD_3D';
	}

	function get_class_name()
	{
		return 'phpbb_captcha_gd_wave';
	}

	function acp_page($id, &$module)
	{
		global $config, $db, $template, $lang;

		trigger_error($lang['CAPTCHA_NO_OPTIONS'] . page_back_link($module->u_action));
	}
}

?>