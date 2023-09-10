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

if (defined('IN_ADMIN'))
{
	if (!function_exists('style_select'))
	{
		include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
	}
}

$settings_details = array();
$settings_details = array(
	'id' => 'site_settings',
	'name' => '10_Site_Settings',
	'sort' => 0,
	'sub_name' => 'Preferences',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

if (defined('IN_ADMIN'))
{
	$settings_details['sub_name'] = '';
}

$settings_data = array();
$settings_data = array(

	'default_style' => array(
		'lang_key' => 'Default_style',
		'get_func' => 'style_select',
		'default' => 1,
	),

	'override_user_style' => array(
		'lang_key' => 'Override_style',
		'explain' => 'Override_style_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'mobile_style_disable' => array(
		'lang_key' => 'IP_mobile_style_disable',
		'explain' => 'IP_mobile_style_disable_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'default_lang' => array(
		'lang_key' => 'Default_language',
		'get_func' => 'language_select',
		'default' => 'english',
	),

	'default_dateformat' => array(
		'lang_key' => 'Date_format',
		'get_func' => 'date_select',
		'default' => 'D d M, Y H:i',
	),

	'board_timezone' => array(
		'lang_key' => 'System_timezone',
		'get_func' => 'tz_select',
		'default' => '0',
	),

	'default_time_mode' => array(
		'lang_key' => 'time_mode_dst',
		'explain' => 'time_mode_text',
		'type' => 'LIST_RADIO',
		'default' => SERVER_SWITCH,
		'values' => array(
			'No' => MANUAL,
			'Yes' => MANUAL_DST,
			'time_mode_dst_server' => SERVER_SWITCH,
		),
	),

	'dst_time_lag' => array(
		'lang_key' => 'time_mode_dst_time_lag',
		'explain' => 'time_mode_dst_mn_explain',
		'type' => 'SMALLINT',
		'default' => 60,
	),

	'prune_enable' => array(
		'lang_key' => 'Enable_prune',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'prune_shouts' => array(
		'lang_key' => 'Prune_shouts',
		'explain' => 'Prune_shouts_explain',
		'type' => 'SMALLINT',
		'default' => 0,
	),

	'report_forum' => array(
		'lang_key' => 'Report_forum',
		'explain' => 'Report_forum_explain',
		'get_func' => 'forums_select_box',
		'default' => 'f4',
	),

	'bin_forum' => array(
		'lang_key' => 'Bin_forum',
		'explain' => 'Bin_forum_explain',
		'get_func' => 'forums_select_box',
		'default' => 'f5',
	),

);

$this->init_config($settings_details, $settings_data);

?>