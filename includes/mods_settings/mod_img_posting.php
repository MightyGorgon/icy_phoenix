<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

include_once(IP_ROOT_PATH . 'includes/functions_mods_settings.' . PHP_EXT);
$mod_name = '40_IMG_Posting';

$config_fields = array(

	'auth_view_pic_upload' => array(
		'lang_key' => 'IP_auth_view_pic_upload',
		'type' => 'LIST_RADIO_BR',
		'default' => 'CFG_REG',
		'values' => array(
			'CFG_ALL' => AUTH_ALL,
			'CFG_REG' => AUTH_REG,
			'CFG_MOD' => AUTH_MOD,
			'CFG_ADMIN' => AUTH_ADMIN,
			),
		),

	'enable_postimage_org' => array(
		'lang_key' => 'IP_enable_postimage_org',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'gd_version' => array(
		'lang_key' => 'IP_gd_version',
		'type' => 'LIST_RADIO',
		'default' => 'GD_2',
		'values' => array(
			'GD_0' => 0,
			'GD_1' => 1,
			'GD_2' => 2,
			),
		),

	'show_img_no_gd' => array(
		'lang_key' => 'IP_show_img_no_gd',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'thumbnail_posts' => array(
		'lang_key' => 'IP_thumbnail_posts',
		'explain' => 'IP_thumbnail_posts_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'show_pic_size_on_thumb' => array(
		'lang_key' => 'IP_show_pic_size_on_thumb',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'thumbnail_highslide' => array(
		'lang_key' => 'IP_thumbnail_highslide',
		'explain' => 'IP_thumbnail_highslide_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'thumbnail_lightbox' => array(
		'lang_key' => 'IP_thumbnail_lightbox',
		'explain' => 'IP_thumbnail_lightbox_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'thumbnail_cache' => array(
		'lang_key' => 'IP_thumbnail_cache',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'thumbnail_quality' => array(
		'lang_key' => 'IP_thumbnail_quality',
		'type' => 'VARCHAR',
		'default' => '75',
		),

	'thumbnail_size' => array(
		'lang_key' => 'IP_thumbnail_size',
		'type' => 'VARCHAR',
		'default' => '400',
		),

);

init_board_config($mod_name, $config_fields);

?>