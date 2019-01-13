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

// MODES - BEGIN
$mode_types = array('list', 'view');
if ($input_allowed)
{
	$mode_types = array_merge($mode_types, array('input', 'save'));
}
if ($admin_allowed)
{
	$mode_types = array_merge($mode_types, array('delete'));
}
$mode = request_var('mode', $mode_types[0]);
$mode = !empty($mode_overlay) ? $mode_overlay : $mode;
//$mode = (!in_array($mode, $mode_types) ? $mode_types[0] : $mode);
if (!in_array($mode, $mode_types) && empty($common_no_auth_check))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}
// MODES - END

// ACTIONS - BEGIN
$action_types = array('list');
if ($input_allowed)
{
	$action_types = array_merge($action_types, array('add'));
}
if ($edit_allowed)
{
	$action_types = array_merge($action_types, array('edit'));
}
if ($admin_allowed)
{
	$action_types = array_merge($action_types, array('delete'));
}
$action = request_var('action', $action_types[0]);
$action = !empty($action_overlay) ? $action_overlay : $action;
$action = ((($mode == 'input') && ($action != 'edit')) ? 'add' : $action);
//$action = (!in_array($action, $action_types) ? $action_types[0] : $action);
if (!in_array($action, $action_types) && empty($common_no_auth_check))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}
// ACTIONS - END

// SORT ORDER AND FILTER - BEGIN
$sort_order_array = array();
$sort_order_select_array = array();
$sort_order_select_lang_array = array();
foreach ($table_fields as $k => $v)
{
	$is_auth = (!in_array($v['type'], array('TEXT', 'HTMLTEXT', 'PASSWORD')) && check_auth_level($v['view_level']));
	if ($is_auth)
	{
		$sort_order_array[] = $k;
		$sort_order_select_array[] = $k;
		$sort_order_select_lang_array[] = $class_form->get_lang($v['lang_key']);
	}
}
$filter_item_default = ((isset($filter_item_default) && in_array($filter_item_default, $sort_order_array)) ? $filter_item_default : '');
$filter_item = request_var('filter_item', $filter_item_default);
$filter_item = (in_array($filter_item, $sort_order_array) ? $filter_item : '');
if (!empty($filter_item))
{
	$filter_item_value_default = (isset($filter_item_value_default) ? $filter_item_value_default : $table_fields[$filter_item]['default']);
	$filter_item_value_default = $class_form->set_type_default_value($filter_item_value_default);
	$filter_item_value = request_var('filter_item_value', $filter_item_value_default);
}
$sort_order_default = ((isset($sort_order_default) && in_array($sort_order_default, $sort_order_array)) ? $sort_order_default : $sort_order_array[0]);
$sort_order = request_var('sort_order', $sort_order_default);
$sort_order = (in_array($sort_order, $sort_order_array) ? $sort_order : $sort_order_array[0]);

$select_name = 'sort_order';
$default = $sort_order;
$select_js = '';
$sort_order_select_box = $class_form->build_select_box($select_name, $default, $sort_order_select_array, $sort_order_select_lang_array, $select_js);
// SORT ORDER AND FILTER - END

// SORT DIR - BEGIN
$sort_dir_default = ((isset($sort_dir_default) && in_array($sort_dir_default, array('ASC', 'DESC'))) ? $sort_dir_default : 'ASC');
$sort_dir = request_var('sort_dir', $sort_dir_default);
$sort_dir = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';

$sort_dir_select_array = array('ASC', 'DESC');
$sort_dir_select_lang_array = array($lang['Sort_Ascending'], $lang['Sort_Descending']);

$select_name = 'sort_dir';
$default = $sort_dir;
$select_js = '';
$sort_dir_select_box = $class_form->build_select_box($select_name, $default, $sort_dir_select_array, $sort_dir_select_lang_array, $select_js);
// SORT DIR - END

// OTHER VARS - BEGIN
$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$n_items_default = !empty($n_items_overlay) ? $n_items_overlay : $config['topics_per_page'];
$n_items = request_var('n_items', $n_items_default);
$n_items = ($n_items < 0) ? $n_items_default : $n_items;
// OTHER VARS - END

// URL APPEND AND HIDDEN FIELDS - BEGIN
$url_full_append = 'sort_order=' . $sort_order . '&amp;sort_dir=' . $sort_dir . (!empty($start) ? ('&amp;start=' . $start) : '') . (!empty($n_items) ? ('&amp;n_items=' . $n_items) : '') . (!empty($filter_item) ? ('&amp;filter_item=' . $filter_item . '&amp;filter_item_value=' . $filter_item_value) : '');

$hidden_array = array('start', 'n_items', 'filter_item', 'filter_item_value');
$s_hidden_fields = '';
for ($i; $i < sizeof($hidden_array); $i++)
{
	if (isset($$hidden_array[$i]))
	{
		$s_hidden_fields .= '<input type="hidden" name="' . $hidden_array[$i] . '" value="' . $$hidden_array[$i] . '" />';
	}
}
// URL APPEND AND HIDDEN FIELDS - END

?>