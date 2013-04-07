<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['100_Main_Settings'] = $file;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Get all settings
define('BOARD_CONFIG', true);
$class_settings->setup_settings();

// SETTINGS - BEGIN
$is_plugin = false;
$settings_basename = 'settings';
$acp_file = IP_ROOT_PATH . ADM . '/' . basename(__FILE__);
// SETTINGS - END

$class_settings->setup_modules('', $settings_basename . '_');

$lang_files = $class_settings->obtain_lang_files($settings_basename . '_');
if (!empty($lang_files))
{
	setup_extra_lang($lang_files);
}

// LANG SETTINGS - BEGIN
$acp_module_title = $lang['IP_CONFIGURATION'];
$acp_module_title_explain = $lang['IP_CONFIGURATION_EXPLAIN'];
$acp_modules = $class_settings->modules;
$acp_default_config = get_config(false);
// LANG SETTINGS - END

include(IP_ROOT_PATH . ADM . '/acp_config_include.' . PHP_EXT);

// footer
$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>