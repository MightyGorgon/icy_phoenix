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
* Bicet (bicets@gmail.com)
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['240_GD_Info'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$template->set_filenames(array('body' => ADM_TPL . 'admin_gd_info_body.tpl'));

if (function_exists('gd_info'))
{
	$var_dump = gd_info();
}
$true = '<span style="color:green">' . $lang['GD_True'] . '</span>';
$false = '<span style="color:red">' . $lang['GD_False'] . '</span>';

$template->assign_vars(array(
	'VERSION' => $var_dump['GD Version'],
	'FREETYPE_SUPPORT' =>  ( $var_dump['FreeType Support'] ) ? $true : $false,
	'FREETYPE_LINKAGE' => $var_dump['FreeType Linkage'],
	'T1LIB_SUPPORT' => ( $var_dump['T1Lib Support'] ) ? $true : $false,
	'GIF_READ_SUPPORT' => ( $var_dump['GIF Read Support'] ) ? $true : $false,
	'GIF_CREATE_SUPPORT' => ( $var_dump['GIF Create Support'] ) ? $true : $false,
	'JPG_SUPPORT' => ( $var_dump['JPG Support'] ) ? $true : $false,
	'PNG_SUPPORT' => ( $var_dump['PNG Support'] ) ? $true : $false,
	'WBMP_SUPPORT' => ( $var_dump['WBMP Support'] ) ? $true : $false,
	'XBM_SUPPORT' => ( $var_dump['XBM Support'] ) ? $true : $false,
	'JIS_MAPPED_SUPPORT' => ( $var_dump['JIS-mapped Japanese Font Support'] ) ? $true : $false,

	'L_TITLE' => $lang['GD_Title'],
	'L_DESCRIPTION' => $lang['GD_Description'],
	'L_VERSION' => $lang['GD_Version'],
	'L_FREETYPE_SUPPORT' => $lang['GD_Freetype_Support'],
	'L_FREETYPE_LINKAGE' => $lang['GD_Freetype_Linkage'],
	'L_T1LIB_SUPPORT' => $lang['GD_T1lib_Support'],
	'L_GIF_READ_SUPPORT' => $lang['GD_Gif_Read_Support'],
	'L_GIF_CREATE_SUPPORT' => $lang['GD_Gif_Create_Support'],
	'L_JPG_SUPPORT' => $lang['GD_Jpg_Support'],
	'L_PNG_SUPPORT' => $lang['GD_Png_Support'],
	'L_WBMP_SUPPORT' => $lang['GD_Wbmp_Support'],
	'L_XBM_SUPPORT' => $lang['GD_XBM_Support'],
	'L_JIS_MAPPED_SUPPORT' => $lang['GD_Jis_Mapped_Support'],

	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>