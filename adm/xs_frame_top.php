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

define('IN_PHPBB', true);
$phpbb_root_path = './../';
$no_page_header = true;
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

define('IN_XS', true);
define('NO_XS_HEADER', true);
include_once('xs_include.' . $phpEx);

$template->set_filenames(array('body' => XS_TPL_PATH . 'frame_top.tpl'));

$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_index.' . $phpEx),
	'TEXT'	=> $lang['xs_menu_lc']
	));
/* $template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_download.' . $phpEx),
	'TEXT'	=> $lang['xs_download_styles_lc']
	)); */
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_import.' . $phpEx),
	'TEXT'	=> $lang['xs_import_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_install.' . $phpEx),
	'TEXT'	=> $lang['xs_install_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> 'http://www.phpbbstyles.com',
	'TEXT'	=> $lang['xs_support_forum_lc']
	));


$template->pparse('body');
xs_exit();

?>