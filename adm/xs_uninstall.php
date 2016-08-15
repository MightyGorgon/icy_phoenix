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

function remove_all($dir)
{
	$res = opendir($dir);
	if(!$res)
	{
		return false;
	}
	while(($file = readdir($res)) !== false)
	{
		if($file !== '.' && $file !== '..')
		{
			$str = $dir . '/' . $file;
			if(is_dir($str))
			{
				remove_all($str);
				@rmdir($str);
			}
			else
			{
				@unlink($str);
			}
		}
	}
	closedir($res);
}

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_uninstall.' . PHP_EXT) . '">' . $lang['xs_uninstall_styles'] . '</a>'));

$lang['xs_uninstall_back'] = str_replace('{URL}', append_sid('xs_uninstall.' . PHP_EXT), $lang['xs_uninstall_back']);
$lang['xs_goto_default'] = str_replace('{URL}', append_sid('xs_styles.' . PHP_EXT), $lang['xs_goto_default']);

// uninstall style
$remove_id = request_var('remove', 0);
$remove_dir = request_get_var('dir', '');
$remove_tpl = request_post_var('remove', '');
$nocfg = request_get_var('nocfg', '');
if(!empty($remove_id) && !defined('DEMO_MODE'))
{
	if($config['default_style'] == $remove_id)
	{
		xs_error(str_replace('{URL}', append_sid('xs_styles.' . PHP_EXT), $lang['xs_uninstall_default']) . '<br /><br />' . $lang['xs_uninstall_back']);
	}
	$sql = "SELECT themes_id, template_name, style_name FROM " . THEMES_TABLE . " WHERE themes_id='{$remove_id}'";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		xs_error($lang['xs_no_style_info'] . '<br /><br />' . $lang['xs_uninstall_back'], __LINE__, __FILE__);
	}
	$row = $db->sql_fetchrow($result);
	if(empty($row['themes_id']))
	{
		xs_error($lang['xs_no_style_info'] . '<br /><br />' . $lang['xs_uninstall_back'], __LINE__, __FILE__);
	}
	$sql = "UPDATE " . USERS_TABLE . " SET user_style = '" . $config['default_style'] . "' WHERE user_style = '{$remove_id}'";
	$db->sql_query($sql);
	$sql = "DELETE FROM " . THEMES_TABLE . " WHERE themes_id = '{$remove_id}'";
	$db->sql_query($sql);
	$template->assign_block_vars('removed', array());

	// clear cache
	$db->clear_cache('styles_');
	$cache->destroy_datafiles(array('_styles'), MAIN_CACHE_FOLDER, 'data', false);

	// remove files
	if(!empty($remove_dir))
	{
		$remove_tpl = $row['template_name'];
	}
	// remove config
	if(empty($nocfg) && isset($config['xs_style_'.$row['template_name']]))
	{
		$sql = "DELETE FROM " . CONFIG_TABLE . " WHERE config_name='" . $db->sql_escape("xs_style_{$row['template_name']}") . "'";
		$db->sql_query($sql);
		$template->assign_block_vars('left_refresh', array(
			'ACTION' => append_sid('index.' . PHP_EXT . '?pane=left')
			)
		);
	}
}

// remove files
if(!empty($remove_tpl) && !defined('DEMO_MODE'))
{
	$remove = $remove_tpl;
	$params = array('remove' => $remove);
	if(!get_ftp_config(append_sid('xs_uninstall.' . PHP_EXT), $params, true))
	{
		xs_exit();
	}
	xs_ftp_connect(append_sid('xs_uninstall.' . PHP_EXT), $params, true);
	$write_local = false;
	if($ftp === XS_FTP_LOCAL)
	{
		$write_local = true;
		$write_local_dir = '../templates/';
	}
	if(!$write_local)
	{
		// Generate actions list
		$actions = array();
		// chdir to templates directory
		$actions[] = array(
				'command' => 'chdir',
				'dir' => 'templates'
			);
		// chdir to template
		$actions[] = array(
				'command' => 'chdir',
				'dir' => $remove
			);
		// remove all files
		$actions[] = array(
				'command' => 'removeall',
				'ignore' => true
			);
		$actions[] = array(
				'command' => 'cdup'
			);
		$actions[] = array(
				'command' => 'rmdir',
				'dir' => $remove
			);
		$ftp_log = array();
		$ftp_error = '';
		$res = ftp_myexec($actions);
		/*
		echo "<!--\n\n";
		echo "\$actions dump:\n\n";
		print_r($actions);
		echo "\n\n\$ftp_log dump:\n\n";
		print_r($ftp_log);
		echo "\n\n -->";
		*/
	}
	else
	{
		remove_all('../templates/'.$remove);
		@rmdir('../templates/'.$remove);
	}
	$template->assign_block_vars('removed', array());
}

// get list of installed styles
$sql = 'SELECT themes_id, template_name, style_name FROM ' . THEMES_TABLE . ' ORDER BY template_name, style_name';
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if(!$result)
{
	xs_error($lang['xs_no_style_info'], __LINE__, __FILE__);
}
$style_rowset = $db->sql_fetchrowset($result);

$tpl = array();
for($i = 0; $i < sizeof($style_rowset); $i++)
{
	$item = $style_rowset[$i];
	$tpl[$item['template_name']][] = $item;
}

$j = 0;
foreach($tpl as $tpl => $styles)
{
	$row_class = $xs_row_class[$j % 2];
	$j++;
	$template->assign_block_vars('styles', array(
			'ROW_CLASS' => $row_class,
			'TPL' => htmlspecialchars($tpl),
			'ROWS' => sizeof($styles),
		)
	);
	if(sizeof($styles) > 1)
	{
		for($i = 0; $i < sizeof($styles); $i++)
		{
			$template->assign_block_vars('styles.item', array(
					'ID' => $styles[$i]['themes_id'],
					'THEME' => htmlspecialchars($styles[$i]['style_name']),
					'U_DELETE' => append_sid('xs_uninstall.' . PHP_EXT . '?remove=' . $styles[$i]['themes_id'] . '&amp;nocfg=1'),
				)
			);
			$template->assign_block_vars('styles.item.nodelete', array());
		}
	}
	else
	{
		$i = 0;
		$template->assign_block_vars('styles.item', array(
				'ID' => $styles[$i]['themes_id'],
				'THEME' => htmlspecialchars($styles[$i]['style_name']),
				'U_DELETE' => append_sid('xs_uninstall.' . PHP_EXT . '?remove=' . $styles[$i]['themes_id']),
			)
		);
		$template->assign_block_vars('styles.item.delete', array(
				'U_DELETE' => append_sid('xs_uninstall.' . PHP_EXT . '?dir=1&amp;remove=' . $styles[$i]['themes_id']),
			)
		);
	}
}

$template->set_filenames(array('body' => XS_TPL_PATH . 'uninstall.tpl'));
$template->pparse('body');
xs_exit();

?>