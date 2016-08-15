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
require('pagestart.' . PHP_EXT);

define('IN_XS', true);
include_once('xs_include.' . PHP_EXT);

$template->assign_block_vars('nav_left', array('ITEM' => '&raquo; <a href="' . append_sid('xs_install.' . PHP_EXT) . '">' . $lang['xs_install_styles'] . '</a>'));

$lang['xs_install_back'] = str_replace('{URL}', append_sid('xs_install.' . PHP_EXT), $lang['xs_install_back']);
$lang['xs_goto_default'] = str_replace('{URL}', append_sid('xs_styles.' . PHP_EXT), $lang['xs_goto_default']);

// remove timeout. useful for forum with 100+ styles
@set_time_limit(XS_MAX_TIMEOUT);

// install style
$style = request_get_var('style', '', true);
$num = request_get_var('num', 0);
if(!empty($style) && !defined('DEMO_MODE'))
{
	$res = xs_install_style($style, $num);
	if(defined('REFRESH_NAVBAR'))
	{
		$template->assign_block_vars('left_refresh', array(
			'ACTION' => append_sid('index.' . PHP_EXT . '?pane=left')
			)
		);
	}
	if($res)
	{
		$db->clear_cache('styles_');
		$cache->destroy_datafiles(array('_styles'), MAIN_CACHE_FOLDER, 'data', false);
		xs_message($lang['Information'], $lang['xs_install_installed'] . '<br /><br />' . $lang['xs_install_back'] . '<br /><br />' . $lang['xs_goto_default']);
	}
	xs_error($lang['xs_install_error'] . '<br /><br />' . $lang['xs_install_back']);
}

// install styles
$total = request_var('total', 0);
if(!empty($total) && !defined('DEMO_MODE'))
{
	$tpl = array();
	$num = array();
	for($i = 0; $i < $total; $i++)
	{
		if(!empty($_POST['install_' . $i]))
		{
			$tpl[] = request_post_var('install_' . $i . '_style', '', true);
			$num[] = request_post_var('install_' . $i . '_num', 0);
		}
	}
	if(sizeof($tpl))
	{
		for($i = 0; $i < sizeof($tpl); $i++)
		{
			xs_install_style($tpl[$i], $num[$i]);
		}
		if(defined('REFRESH_NAVBAR'))
		{
			$template->assign_block_vars('left_refresh', array(
				'ACTION' => append_sid('index.' . PHP_EXT . '?pane=left')
				)
			);
		}
		$db->clear_cache('styles_');
		$cache->destroy_datafiles(array('_styles'), MAIN_CACHE_FOLDER, 'data', false);
		xs_message($lang['Information'], $lang['xs_install_installed'] . '<br /><br />' . $lang['xs_install_back'] . '<br /><br />' . $lang['xs_goto_default']);
	}
}

// get all installed styles
$sql = 'SELECT themes_id, template_name, style_name FROM ' . THEMES_TABLE . ' ORDER BY template_name';
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if(!$result)
{
	xs_error($lang['xs_no_style_info'], __LINE__, __FILE__);
}
$style_rowset = $db->sql_fetchrowset($result);

// find all styles to install
$res = @opendir('../templates/');
$styles = array();
while(($file = readdir($res)) !== false)
{
	if(($file !== '.') && ($file !== '..') && @file_exists('../templates/' . $file . '/theme_info.cfg') && @file_exists('../templates/' . $file . '/' . $file . '.cfg'))
	{
		$arr = xs_get_themeinfo($file);
		for($i = 0; $i < sizeof($arr); $i++)
		{
			if(isset($arr[$i]['template_name']) && $arr[$i]['template_name'] === $file)
			{
				$arr[$i]['num'] = $i;
				$style = $arr[$i]['style_name'];
				$found = false;
				for($j = 0; $j < sizeof($style_rowset); $j++)
				{
					if($style_rowset[$j]['style_name'] == $style)
					{
						$found = true;
					}
				}
				if(!$found)
				{
					$styles[$arr[$i]['style_name']] = $arr[$i];
				}
			}
		}
	}
}
closedir($res);

if(!sizeof($styles))
{
	xs_message($lang['Information'], $lang['xs_install_none'] . '<br /><br />' . $lang['xs_goto_default']);
}

ksort($styles);

$j = 0;
foreach($styles as $var => $value)
{
	$row_class = $xs_row_class[$j % 2];
	$template->assign_block_vars('styles', array(
			'ROW_CLASS' => $row_class,
			'STYLE' => htmlspecialchars($value['template_name']),
			'THEME' => htmlspecialchars($value['style_name']),
			'U_INSTALL' => append_sid('xs_install.' . PHP_EXT . '?style=' . urlencode($value['template_name']) . '&num=' . $value['num']),
			'CB_NAME' => 'install_' . $j,
			'NUM' => $value['num'],
		)
	);
	$j++;
}

$template->assign_vars(array(
	'U_INSTALL' => append_sid('xs_install.' . PHP_EXT),
	'TOTAL' => sizeof($styles)
	)
);

$template->set_filenames(array('body' => XS_TPL_PATH . 'install.tpl'));
$template->pparse('body');
xs_exit();

?>