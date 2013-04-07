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
class phpbb_captcha_nogd extends phpbb_default_captcha
{

	function phpbb_captcha_nogd()
	{
		if (!class_exists('captcha'))
		{
			include_once(IP_ROOT_PATH . 'includes/captcha/captcha_non_gd.' . PHP_EXT);
		}
	}

	function &get_instance()
	{
		$instance =& new phpbb_captcha_nogd();
		return $instance;
	}

	function is_available()
	{
		return true;
	}

	function get_name()
	{
		return 'CAPTCHA_NO_GD';
	}

	function get_class_name()
	{
		return 'phpbb_captcha_nogd';
	}

	function acp_page($id, &$module)
	{
		global $lang;

		trigger_error($lang['CAPTCHA_NO_OPTIONS'] . page_back_link($module->u_action));
	}
}

?>