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
	'id' => 'calendar_settings',
	'name' => '60_Calendar_settings',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'show_calendar_box_index' => array(
		'lang_key' => 'Calendar_block_display',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	/*
	'calendar_display_open' => array(
		'lang_key' => 'Calendar_display_open',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'user' => 'user_calendar_display_open',
		'values' => $this->list_yes_no,
	),
	*/

	'calendar_header_cells' => array(
		'lang_key' => 'Calendar_header_cells',
		'type' => 'TINYINT',
		'default' => 0,
		'user' => 'user_calendar_header_cells',
	),

	'calendar_week_start' => array(
		'lang_key' => 'Calendar_week_start',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'user' => 'user_calendar_week_start',
		'values' => array(
			'WEEK_DAY_SUNDAY' => 0,
			'WEEK_DAY_MONDAY' => 1,
		),
	),

	'calendar_title_length' => array(
		'lang_key' => 'Calendar_title_length',
		'type' => 'TINYINT',
		'default' => 30,
	),

	'calendar_text_length' => array(
		'lang_key' => 'Calendar_text_length',
		'type' => 'SMALLINT',
		'default' => 200,
	),

	'calendar_nb_row' => array(
		'lang_key' => 'Calendar_nb_row',
		'type' => 'TINYINT',
		'user' => 'user_calendar_nb_row',
		'default' => 5,
	),

	'calendar_birthday' => array(
		'lang_key' => 'Calendar_birthday',
		'type' => 'LIST_RADIO',
		'user' => 'user_calendar_birthday',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'calendar_forum' => array(
		'lang_key' => 'Calendar_forum',
		'type' => 'LIST_RADIO',
		'user' => 'user_calendar_forum',
		'default' => 1,
		'values' => $this->list_yes_no,
	),
);

$this->init_config($settings_details, $settings_data);

?>