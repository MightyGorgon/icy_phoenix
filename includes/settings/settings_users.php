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

$settings_details = array();
$settings_details = array(
	'id' => 'users_settings',
	'name' => '25_Users',
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

	'allow_namechange' => array(
		'lang_key' => 'Allow_name_change',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'allow_sig' => array(
		'lang_key' => 'Allow_sig',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'max_sig_chars' => array(
		'lang_key' => 'Max_sig_length',
		'explain' => 'Max_sig_length_explain',
		'type' => 'SMALLINT',
		'default' => 255,
	),

	'sig_line' => array(
		'lang_key' => 'sig_divider',
		'explain' => 'sig_explain',
		'type' => 'VARCHAR',
		'default' => '____________',
	),

	'user_allow_pm_register' => array(
		'lang_key' => 'IP_user_allow_pm_register',
		'explain' => 'IP_user_allow_pm_register_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'online_time' => array(
		'lang_key' => 'Online_time',
		'explain' => 'Online_time_explain',
		'type' => 'SMALLINT',
		'default' => 60,
	),

	'hidde_last_logon' => array(
		'lang_key' => 'Hidde_last_logon',
		'explain' => 'Hidde_last_logon_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'bluecard_limit_2' => array(
		'lang_key' => 'Bluecard_limit_2',
		'explain' => 'Bluecard_limit_2_explain',
		'type' => 'SMALLINT',
		'default' => 1,
	),

	'bluecard_limit' => array(
		'lang_key' => 'Bluecard_limit',
		'explain' => 'Bluecard_limit_explain',
		'type' => 'SMALLINT',
		'default' => 3,
	),

	'max_user_bancard' => array(
		'lang_key' => 'Max_user_bancard',
		'explain' => 'Max_user_bancard_explain',
		'type' => 'SMALLINT',
		'default' => 3,
	),

	'gender_required' => array(
		'lang_key' => 'Gender_required',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'birthday_required' => array(
		'lang_key' => 'Birthday_required',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'birthday_greeting' => array(
		'lang_key' => 'Enable_birthday_greeting',
		'explain' => 'Birthday_greeting_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'min_user_age' => array(
		'lang_key' => 'Min_user_age',
		'explain' => 'Min_user_age_explain',
		'type' => 'SMALLINT',
		'default' => 13,
	),

	'max_user_age' => array(
		'lang_key' => 'Max_user_age',
		'type' => 'SMALLINT',
		'default' => 100,
	),

	'birthday_check_day' => array(
		'lang_key' => 'Birthday_lookforward',
		'explain' => 'Birthday_lookforward_explain',
		'type' => 'SMALLINT',
		'default' => 7,
	),

	'default_avatar_set' => array(
		'lang_key' => 'Default_avatar',
		'explain' => 'Default_avatar_explain',
		'separator' => 'Avatar_settings',
		'type' => 'LIST_RADIO_BR',
		'default' => 2,
		'values' => array(
			'Default_avatar_guests' => 0,
			'Default_avatar_users' => 1,
			'Default_avatar_both' => 2,
			'Default_avatar_none' => 3,
		),
	),

	'default_avatar_guests_url' => array(
		'lang_key' => 'Default_avatar_guests_url',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'default_avatar_users_url' => array(
		'lang_key' => 'Default_avatar_users_url',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'allow_avatar_local' => array(
		'lang_key' => 'Allow_local',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'allow_avatar_remote' => array(
		'lang_key' => 'Allow_remote',
		'explain' => 'Allow_remote_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'allow_avatar_generator' => array(
		'lang_key' => 'Allow_generator',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'allow_avatar_upload' => array(
		'lang_key' => 'Allow_upload',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'avatar_filesize' => array(
		'lang_key' => 'Max_avatar_filesize',
		'explain' => 'Max_avatar_filesize_explain',
		'type' => 'MEDIUMINT',
		'default' => 15000,
	),

	'avatar_max_width' => array(
		'lang_key' => 'Max_avatar_size_width',
		'explain' => 'Max_avatar_size_explain',
		'type' => 'SMALLINT',
		'default' => 80,
	),

	'avatar_max_height' => array(
		'lang_key' => 'Max_avatar_size_height',
		'explain' => 'Max_avatar_size_explain',
		'type' => 'SMALLINT',
		'default' => 80,
	),

	'avatar_path' => array(
		'lang_key' => 'Avatar_storage_path',
		'explain' => 'Avatar_storage_path_explain',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'avatar_generator_template_path' => array(
		'lang_key' => 'Avatar_generator_template_path',
		'explain' => 'Avatar_generator_template_path_explain',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'avatar_gallery_path' => array(
		'lang_key' => 'Avatar_gallery_path',
		'explain' => 'Avatar_gallery_path_explain',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'enable_gravatars' => array(
		'lang_key' => 'Enable_gravatars',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'gravatar_rating' => array(
		'lang_key' => 'Gravatar_rating',
		'explain' => 'Gravatar_rating_explain',
		'type' => 'LIST_DROP',
		'default' => '',
		'values' => array(
			'None' => '',
			'G' => 'G',
			'PG' => 'PG',
			'F' => 'R',
			'X' => 'X',
		),
	),

	'gravatar_default_image' => array(
		'lang_key' => 'Gravatar_default_image',
		'explain' => 'Gravatar_default_image_explain',
		'type' => 'VARCHAR',
		'default' => '',
	),

);

$this->init_config($settings_details, $settings_data);

?>