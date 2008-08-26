<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

$album_config_tabs[] =  array(
	'order' => 8,
	'selection' => 'clearcache',
	'title' => $lang['Clear_Cache_Tab'],
	'detail' => '',
	'sub_config' => array(
		/*
		0 => array(
			'order' => 0,
			'selection' => '',
			'title' => '',
			'detail' => ''
		)
		*/
	),
	'config_table_name' => ALBUM_CONFIG_TABLE,
	'generate_function' => 'album_generate_config_clearcache',
	'template_file' => $acp_prefix . 'album_config_clearcache_body.tpl'
);

function album_generate_config_clearcache($config_data)
{
	global $template, $lang, $new;

	$template->assign_vars(array(
		'CLEARCACHE_TEXT' => $lang['Album_clear_cache_confirm'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No']
		)
	);
}
?>