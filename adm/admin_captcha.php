<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


// Mighty Gorgon: force to return until new CAPTCHA is effectively in place!
return;

if(defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['140_CAPTCHA'] = $filename;
	return;
}
define('IN_ICYPHOENIX', true);

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// SETTINGS - BEGIN
$is_plugin = false;
$settings_basename = 'captcha';
$acp_file = IP_ROOT_PATH . ADM . '/' . basename(__FILE__);
// SETTINGS - END

$class_settings->setup_modules('', $settings_basename . '_');

$lang_files = $class_settings->obtain_lang_files($settings_basename . '_');
if (!empty($lang_files))
{
	setup_extra_lang($lang_files);
}

// OTHERS SETTINGS - BEGIN
$acp_module_title = $lang['ACP_CAPTCHA'];
$acp_module_title_explain = $lang['ACP_CAPTCHA_EXPLAIN'];
$acp_modules = $class_settings->modules;
$acp_default_config = get_config(false);
// OTHERS SETTINGS - END

/*
include(IP_ROOT_PATH . 'includes/captcha/captcha_factory.' . PHP_EXT);
$captcha =& phpbb_captcha_factory::get_instance($config['captcha_plugin']);
*/

include(IP_ROOT_PATH . ADM . '/acp_config_include.' . PHP_EXT);

// footer
$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>