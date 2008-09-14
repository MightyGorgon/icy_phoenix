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
* Vjacheslav Trushkin (http://www.stsoftware.biz)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('./pagestart.' . PHP_EXT);

// check if mod is installed
if(empty($template->xs_version) || $template->xs_version !== 8)
{
	message_die(GENERAL_ERROR, isset($lang['xs_error_not_installed']) ? $lang['xs_error_not_installed'] : 'eXtreme Styles mod is not installed. You forgot to upload includes/template.php');
}

define('IN_XS', true);
include_once('xs_include.' . PHP_EXT);

if(isset($_GET['showwarning']))
{
	$msg = str_replace('{URL}', append_sid('xs_index.' . PHP_EXT), $lang['xs_main_comment3']);
	xs_message($lang['Information'], $msg);
}

$template->assign_vars(array(
	'U_CONFIG'				=> append_sid('xs_config.' . PHP_EXT),
	'U_DEFAULT_STYLE'		=> append_sid('xs_styles.' . PHP_EXT),
	'U_MANAGE_CACHE'		=> append_sid('xs_cache.' . PHP_EXT),
	'U_IMPORT_STYLES'		=> append_sid('xs_import.' . PHP_EXT),
	'U_EXPORT_STYLES'		=> append_sid('xs_export.' . PHP_EXT),
	'U_CLONE_STYLE'			=> append_sid('xs_clone.' . PHP_EXT),
	'U_DOWNLOAD_STYLES'		=> append_sid('xs_download.' . PHP_EXT),
	'U_INSTALL_STYLES'		=> append_sid('xs_install.' . PHP_EXT),
	'U_UNINSTALL_STYLES'	=> append_sid('xs_uninstall.' . PHP_EXT),
	'U_EDIT_STYLES'			=> append_sid('xs_edit.' . PHP_EXT),
	'U_EDIT_STYLES_DATA'	=> append_sid('xs_edit_data.' . PHP_EXT),
	'U_EXPORT_DATA'			=> append_sid('xs_export_data.' . PHP_EXT),
	'U_UPDATES'				=> append_sid('xs_update.' . PHP_EXT),
	'S_SHOW_UPDATES'		=> defined('XS_ENABLE_UPDATES') ? 1 : 0,
	)
);

$template->set_filenames(array('body' => XS_TPL_PATH . 'index.tpl'));
$template->pparse('body');
xs_exit();

?>