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

// check if mod is installed
if(empty($template->xs_version) || $template->xs_version !== 8)
{
	message_die(GENERAL_ERROR, isset($lang['xs_error_not_installed']) ? $lang['xs_error_not_installed'] : 'eXtreme Styles mod is not installed. You forgot to upload includes/template.php');
}

define('IN_XS', true);
include_once('xs_include.' . PHP_EXT);

$tpl = isset($_POST['tpl']) ? $_POST['tpl'] : (isset($_GET['tpl']) ? $_GET['tpl'] : '');
$filename = IP_ROOT_PATH . 'templates/' . $tpl . '/xs_config.cfg';

if(empty($tpl))
{
	xs_error($lang['xs_invalid_style_name']);
}
if(!@file_exists($filename))
{
	// remove from config
	$config_name = 'xs_style_' . $tpl;
	$sql = "DELETE FROM " . CONFIG_TABLE . " WHERE config_name='" . addslashes($config_name) . "'";
	$db->sql_query($sql);
	// recache config table for cat_hierarchy 2.1.0
	if(isset($GLOBALS['config']) && is_object($GLOBALS['config']))
	{
		global $config;
		$config->read(true);
	}
	$template->assign_block_vars('left_refresh', array(
			'ACTION'	=> append_sid('index.' . PHP_EXT . '?pane=left')
		));
	xs_error($lang['xs_invalid_style_name']);
}

// get configuration
$style_config = array();
include($filename);
$data = $template->get_config($tpl, false);
for($i=0; $i<count($style_config); $i++)
{
	if(!isset($data[$style_config[$i]['var']]))
	{
		$data[$style_config[$i]['var']] = $style_config[$i]['default'];
	}
}


// check submitted form
if(isset($_POST['tpl']) && !defined('DEMO_MODE'))
{
	for($i=0; $i<count($style_config); $i++)
	{
		$item = &$style_config[$i];
		$var = $style_config[$i]['var'];
		if($item['type'] === 'list')
		{
			$value = isset($_POST['cfg_' . $var]) && is_array($_POST['cfg_' . $var]) ? $_POST['cfg_' . $var] : array();
			$list = array();
			foreach($value as $var1 => $value1)
			{
				$list[] = $var1;
			}
			$value = implode(',', $list);
		}
		else
		{
			$value = isset($_POST['cfg_' . $var]) ? stripslashes($_POST['cfg_' . $var]) : 0;
		}
		$data[$var] = $value;
	}
	// update config
	$str = $template->_serialize($data);
	$config_name = 'xs_style_' . $tpl;
	if(isset($board_config[$config_name]))
	{
		$sql = "UPDATE " . CONFIG_TABLE . " SET config_value='" . addslashes($str) . "' WHERE config_name='" . addslashes($config_name) . "'";
	}
	else
	{
		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('" . addslashes($config_name) . "', '" . addslashes($str) . "')";
	}
	$db->sql_query($sql);
	$board_config[$config_name] = $str;
	// recache config table for cat_hierarchy 2.1.0
	if(isset($config) && is_object($config))
	{
		$config->read(true);
	}
}


// show form
$last_cat = '';
for($i=0; $i<count($style_config); $i++)
{
	$item = &$style_config[$i];
	$var = $style_config[$i]['var'];
	$template->assign_block_vars('item', array(
		'VAR'		=> 'cfg_' . $var,
		'VALUE'		=> htmlspecialchars($data[$var]),
		'DEF'		=> $item['default'],
		'TYPE'		=> $item['type'],
		'TEXT'		=> htmlspecialchars($item['text']),
		'EXPLAIN'	=> isset($item['explain']) ? $item['explain'] : '',
		));
	if($item['type'] === 'select')
	{
		foreach($item['selection'] as $var1 => $value1)
		{
			$template->assign_block_vars('item.select', array(
				'VALUE'		=> htmlspecialchars($var1),
				'TEXT'		=> htmlspecialchars($value1),
				'SELECTED'	=> $data[$var] === $var1 ? 1 : 0,
				));
		}
	}
	if($item['type'] === 'list')
	{
		$values = explode(',', $data[$var]);
		foreach($item['selection'] as $var => $value)
		{
			$selected = false;
			for($j=0; $j<count($values); $j++)
			{
				if($values[$j] === $var)
				{
					$selected = true;
				}
			}
			$template->assign_block_vars('item.list', array(
				'VALUE'		=> htmlspecialchars($var),
				'TEXT'		=> htmlspecialchars($value),
				'SELECTED'	=> $selected,
				));
			$num++;
		}
	}
	if(!empty($item['cat']) && $item['cat'] !== $last_cat)
	{
		$template->assign_block_vars('item.cat', array(
			'TEXT'	=> htmlspecialchars($item['cat'])
			));
		$last_cat = $item['cat'];
	}
}

$template->assign_vars(array(
	'TPL'		=> htmlspecialchars($tpl),
	'U_FORM'	=> 'xs_style_config.' . PHP_EXT . '?sid='.$userdata['session_id'],
	));

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_style_config.' . PHP_EXT . '?tpl='.urlencode($tpl)) . '">' . $lang['xs_style_configuration'] . ': ' . $tpl . '</a>'));

$template->set_filenames(array('body' => XS_TPL_PATH . 'style_config.tpl'));
$template->pparse('body');
xs_exit();

?>