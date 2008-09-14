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

define('IN_XS', true);
define('NO_XS_HEADER', true);
include_once('xs_include.' . PHP_EXT);

$template->set_filenames(array('body' => XS_TPL_PATH . 'frame_top.tpl'));

$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_index.' . PHP_EXT),
	'TEXT'	=> $lang['xs_menu_lc']
	));
/* $template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_download.' . PHP_EXT),
	'TEXT'	=> $lang['xs_download_styles_lc']
	)); */
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_import.' . PHP_EXT),
	'TEXT'	=> $lang['xs_import_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_install.' . PHP_EXT),
	'TEXT'	=> $lang['xs_install_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> 'http://www.phpbbstyles.com',
	'TEXT'	=> $lang['xs_support_forum_lc']
	));


$template->pparse('body');
xs_exit();

?>