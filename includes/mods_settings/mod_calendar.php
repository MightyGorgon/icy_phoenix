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
$mod_name = '60_Calendar_settings';

$config_fields = array(

	'show_calendar_box_index' => array(
		'lang_key' => 'Calendar_block_display',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	/*
	'calendar_display_open' => array(
		'lang_key' => 'Calendar_display_open',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'user' => 'user_calendar_display_open',
		'values' => $list_yes_no,
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
		'default' => $lang['datetime']['Monday'],
		'user' => 'user_calendar_week_start',
		'values' => array(
			$lang['datetime']['Sunday'] => 0,
			$lang['datetime']['Monday'] => 1,
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
		'default' => 'Yes',
		'hide' => (!isset($lang['HAPPY_BIRTHDAY']) || !isset($userdata['user_birthday'])),
		'values' => $list_yes_no,
		),

	'calendar_forum' => array(
		'lang_key' => 'Calendar_forum',
		'type' => 'LIST_RADIO',
		'user' => 'user_calendar_forum',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),
);

// init config table
init_board_config($mod_name, $config_fields);

?>