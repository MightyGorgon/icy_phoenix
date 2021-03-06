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

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if (empty($is_allowed))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

define('IN_XS', true);
include_once('xs_include.' . PHP_EXT);


// check filter
$filter = isset($_GET['filter']) ? stripslashes($_GET['filter']) : (isset($_POST['filter']) ? stripslashes($_POST['filter']) : '');
if(isset($_POST['filter_update']))
{
	$filter_data = array(
		'ext' => trim(stripslashes($_POST['filter_ext'])),
		'data' => trim(stripslashes($_POST['filter_data']))
	);
	$filter = serialize($filter_data);
}
else
{
	$filter_data = @unserialize($filter);
	if(empty($filter_data['ext']))
	{
		$filter_data['ext'] = '';
	}
	if(empty($filter_data['data']))
	{
		$filter_data['data'] = '';
	}
}
$filter_str = '?filter=' . urlencode($filter);


$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_edit.' . PHP_EXT.$filter_str) . '">' . $lang['xs_edit_templates'] . '</a>'));

$editable = array('.htm', '.html', '.tpl', '.css', '.txt', '.cfg', '.xml', '.php', '.htaccess');

// get current directory
$current_dir = isset($_GET['dir']) ? stripslashes($_GET['dir']) : (isset($_POST['dir']) ? stripslashes($_POST['dir']) : 'templates');
$current_dir = xs_fix_dir($current_dir);
if(defined('DEMO_MODE') && substr($current_dir, 0, 9) !== 'templates')
{	// limit access to "templates" in demo mode
	$current_dir = 'templates';
}
$dirs = explode('/', $current_dir);
for($i = 0; $i < sizeof($dirs); $i++)
{
	if(!$dirs[$i] || $dirs[$i] === '.')
	{
		unset($dirs[$i]);
	}
}
$current_dir = implode('/', $dirs);
$current_dir_full = $current_dir; //'templates' . ($current_dir ? '/' . $current_dir : '');
$current_dir_root = $current_dir ? $current_dir . '/' : '';

$return_dir = str_replace('{URL}', append_sid('xs_edit.' . PHP_EXT . $filter_str . '&dir=' . urlencode($current_dir)), $lang['xs_edittpl_back_dir']);
$return_url = $return_dir;
$return_url_root = str_replace('{URL}', append_sid('xs_edit.' . PHP_EXT . $filter_str . '&dir='), $lang['xs_edittpl_back_dir']);


$template->assign_vars(array(
	'FILTER_EXT' => htmlspecialchars($filter_data['ext']),
	'FILTER_DATA' => htmlspecialchars($filter_data['data']),
	'FILTER_URL' => append_sid('xs_edit.' . PHP_EXT),
	'FILTER_DIR' => htmlspecialchars($current_dir),
	'S_FILTER' => '<input type="hidden" name="filter" value="' . htmlspecialchars($filter) . '" />'
	)
);

/*
* show edit form
*/
if(isset($_GET['edit']) && !empty($_GET['restore']))
{
	$file = stripslashes($_GET['edit']);
	$file = xs_fix_dir($file);
	$fullfile = $current_dir_root . $file;
	$localfile = '../' . $fullfile;
	$hash = md5($localfile);
	$backup_name = XS_TEMP_DIR . XS_BACKUP_PREFIX . $hash . '.' . intval($_GET['restore']) . XS_BACKUP_EXT;
	if(@file_exists($backup_name))
	{
		// restore file
		$_POST['edit'] = $_GET['edit'];
		$_POST['content'] = addslashes(implode('', @file($backup_name)));
		unset($_GET['edit']);
		$return_file = str_replace('{URL}', append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file)), $lang['xs_edittpl_back_edit']);
		$return_url = $return_file . '<br /><br />' . $return_dir;
	}
}


/*
* save modified file
*/
if(isset($_POST['edit']) && !defined('DEMO_MODE'))
{
	$file = stripslashes($_POST['edit']);
	$content = stripslashes($_POST['content']);
	$fullfile = $current_dir_root . $file;
	$localfile = '../' . $fullfile;
	if(!empty($_POST['trim']))
	{
		$content = trim($content);
	}
	if(!empty($_FILES['upload']['tmp_name']) && @file_exists($_FILES['upload']['tmp_name']))
	{
		$content = @implode('', @file($_FILES['upload']['tmp_name']));
	}
	$params = array(
		'edit' => $file,
		'dir' => $current_dir,
		'content' => $content,
		'filter' => $filter,
	);
	$return_file = str_replace('{URL}', append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file)), $lang['xs_edittpl_back_edit']);
	$return_url = $return_file . '<br /><br />' . $return_dir;
	// get ftp configuration
	$write_local = false;
	if(!get_ftp_config(append_sid('xs_edit.' . PHP_EXT), $params, true))
	{
		xs_exit();
	}
	xs_ftp_connect(append_sid('xs_edit.' . PHP_EXT), $params, true);
	if($ftp === XS_FTP_LOCAL)
	{
		$write_local = true;
		$local_filename = $localfile;
	}
	else
	{
		$local_filename = XS_TEMP_DIR . 'edit_' . time() . '.tmp';
	}
	$f = @fopen($local_filename, 'wb');
	if(!$f)
	{
		xs_error($lang['xs_error_cannot_open'] . '<br /><br />' . $return_url);
	}
	fwrite($f, $content);
	fclose($f);
	if($write_local)
	{
		xs_message($lang['Information'], $lang['xs_edit_file_saved'] . '<br /><br />' . $return_url);
	}
	// generate ftp actions
	$actions = array();
	// chdir to template directory
	for($i = 0; $i < sizeof($dirs); $i++)
	{
		$actions[] = array(
			'command' => 'chdir',
			'dir' => $dirs[$i]
		);
	}
	$actions[] = array(
		'command' => 'upload',
		'local' => $local_filename,
		'remote' => $fullfile
	);
	$ftp_log = array();
	$ftp_error = '';
	$res = ftp_myexec($actions);
	echo "<!--\n\n";
	echo "\$actions dump:\n\n";
	print_r($actions);
	echo "\n\n\$ftp_log dump:\n\n";
	print_r($ftp_log);
	echo "\n\n -->";
	@unlink($local_filename);
	if($res)
	{
		xs_message($lang['Information'], $lang['xs_edit_file_saved'] . '<br /><br />' . $return_url);
	}
	xs_error($ftp_error . '<br /><br />' . $return_url);
}


/*
* show edit form
*/
if(isset($_GET['edit']))
{
	$file = stripslashes($_GET['edit']);
	$file = xs_fix_dir($file);
	$fullfile = $current_dir_root . $file;
	$localfile = '../' . $fullfile;
	$hash = md5($localfile);
	if(!@file_exists($localfile))
	{
		xs_error($lang['xs_edit_not_found'] . '<br /><br />' . $return_url);
	}
	$content = @file($localfile);
	if(!is_array($content))
	{
		xs_error($lang['xs_edit_not_found'] . '<br /><br />' . $return_url);
	}
	$content = implode('', $content);
	if(isset($_GET['download']) && !defined('DEMO_MODE'))
	{
		xs_download_file($file, $content);
		xs_exit();
	}
	if(isset($_GET['downloadbackup']) && !defined('DEMO_MODE'))
	{
		$backup_name = XS_TEMP_DIR . XS_BACKUP_PREFIX . $hash . '.' . intval($_GET['downloadbackup']) . XS_BACKUP_EXT;
		xs_download_file($file, implode('', @file($backup_name)));
		xs_exit();
	}
	$return_file = str_replace('{URL}', append_sid('xs_edit.' . PHP_EXT.$filter_str.'&amp;dir=' . urlencode($current_dir).'&edit='.urlencode($file)), $lang['xs_edittpl_back_edit']);
	$return_url = $return_file . '<br /><br />' . $return_dir;
	$template->assign_vars(array(
		'U_ACTION' => append_sid('xs_edit.' . PHP_EXT),
		'U_BROWSE' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir)),
		'U_EDIT' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file)),
		'U_BACKUP' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&dobackup=1&amp;dir='.urlencode($current_dir) . '&amp;edit=' . urlencode($file)),
		'U_DOWNLOAD' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&download=1&amp;dir='.urlencode($current_dir) . '&amp;edit=' . urlencode($file)),
		'CURRENT_DIR' => htmlspecialchars($current_dir_full),
		'DIR' => htmlspecialchars($current_dir),
		'FILE' => htmlspecialchars($file),
		'FULLFILE' => htmlspecialchars($fullfile),
		'CONTENT' => defined('DEMO_MODE') ? $lang['xs_error_demo_edit'] : htmlspecialchars($content),
		)
	);
	if($current_dir_full)
	{
		$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_edit.' . PHP_EXT . $filter_str . '&dir='.$current_dir) . '">' . htmlspecialchars($current_dir_full) . '</a>'));
	}

	// show tree
	$arr = array();
	$template->assign_block_vars('tree', array(
		'ITEM' => 'Icy Phoenix',
		'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&dir='),
		'SEPARATOR' => '',
		));
	$back_dir = '';
	for($i = 0; $i < sizeof($dirs); $i++)
	{
		$arr[] = $dirs[$i];
		$str = implode('/', $arr);
		if(sizeof($dirs) > ($i + 1))
		{
			$back_dir = $str;
		}
		$template->assign_block_vars('tree', array(
			'ITEM' => htmlspecialchars($dirs[$i]),
			'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($str)),
			'SEPARATOR' => '/',
			));
	}

	// view backup
	if(!empty($_GET['viewbackup']) && !defined('DEMO_MODE'))
	{
		$backup_name = XS_TEMP_DIR . XS_BACKUP_PREFIX . $hash . '.' . intval($_GET['viewbackup']) . XS_BACKUP_EXT;
		$template->assign_vars(array(
			'CONTENT' => implode('', @file($backup_name))
			)
		);
	}

	// save backup
	if(isset($_GET['dobackup']) && !defined('DEMO_MODE'))
	{
		$backup_name = XS_TEMP_DIR . XS_BACKUP_PREFIX . $hash . '.' . time() . XS_BACKUP_EXT;
		$f = @fopen($backup_name, 'wb');
		if(!$f)
		{
			xs_error(str_replace('{FILE}', $backup_name, $lang['xs_error_cannot_create_tmp']) . '<br /><br />' . $return_url);
		}
		fwrite($f, $content);
		fclose($f);
		@chmod($backup_name, 0777);
	}

	// delete backup
	if(isset($_GET['delbackup']) && !defined('DEMO_MODE'))
	{
		$backup_name = XS_TEMP_DIR . XS_BACKUP_PREFIX . $hash . '.' . intval($_GET['delbackup']) . XS_BACKUP_EXT;
		@unlink($backup_name);
	}

	// show backups
	$backups = array();
	$res = opendir(XS_TEMP_DIR);
	$match = XS_BACKUP_PREFIX . $hash . '.';
	$match_len = strlen($match);
	while(($f = readdir($res)) !== false)
	{
		if(substr($f, 0, $match_len) === $match)
		{
			$str = substr($f, $match_len, strlen($f) - $match_len - strlen(XS_BACKUP_EXT));
			if(intval($str))
			{
				$backups[] = intval($str);
			}
		}
	}
	closedir($res);
	sort($backups);
	for($i = 0; $i < sizeof($backups); $i++)
	{
		$template->assign_block_vars('backup', array(
			'TIME' => create_date($config['default_dateformat'], $backups[$i], $config['board_timezone']),
			'U_RESTORE' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file) . '&amp;restore=' . $backups[$i]),
			'U_DELETE' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file) . '&amp;delbackup=' . $backups[$i]),
			'U_DOWNLOAD' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file) . '&amp;downloadbackup=' . $backups[$i]),
			'U_VIEW' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file) . '&amp;viewbackup=' . $backups[$i]),
			)
		);
	}

	// show template
	$template->set_filenames(array('body' => XS_TPL_PATH . 'edit_file.tpl'));
	$template->pparse('body');
	xs_exit();
}

/*
*  show file browser
*/

// show tree
$arr = array();
$template->assign_block_vars('tree', array(
	'ITEM' => 'Icy Phoenix',
	'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&dir='),
	'SEPARATOR' => '',
	));
$back_dir = '';
for($i = 0; $i < sizeof($dirs); $i++)
{
	$arr[] = $dirs[$i];
	$str = implode('/', $arr);
	if(sizeof($dirs) > ($i + 1))
	{
		$back_dir = $str;
	}
	$template->assign_block_vars('tree', array(
		'ITEM' => htmlspecialchars($dirs[$i]),
		'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($str)),
		'SEPARATOR' => '/',
		)
	);
}

// get list of files/directories
$list_files = array();			// non-editable files
$list_files_editable = array();	// editable files
$list_dirs = array();			// directories
$res = @opendir('../' . $current_dir_full);
if(!$res)
{
	xs_error(str_replace('{DIR}', $current_dir_full, $lang['xs_export_no_open_dir']) . '<br /><br />' . $return_url_root);
}
while(($file = readdir($res)) !== false)
{
	if($file !== '.' && $file !== '..')
	{
		$filename = '../' . ($current_dir_full ? $current_dir_full . '/' : '') . $file;
		if(is_dir($filename))
		{
			$list_dirs[] = $file;
		}
		else
		{
			$pos = strrpos($file, '.');
			if($pos !== false)
			{
				$ext = strtolower(substr($file, $pos));
				$ext1 = substr($ext, 1);
				if((!$filter_data['ext'] && xs_in_array($ext, $editable)) || $ext1 === $filter_data['ext'])
				{
					// check filter
					if($filter_data['data'])
					{
						$content = @implode('', @file($filename));
						if(strpos($content, $filter_data['data']) !== false)
						{
							$list_files_editable[] = $file;
						}
					}
					else
					{
						$list_files_editable[] = $file;
					}
				}
				else
				{
					$list_files[] = $file;
				}
			}
		}
	}
}
closedir($res);

$list_dirs_count = sizeof($list_dirs);
$list_files_count = sizeof($list_files) + sizeof($list_files_editable);

if($current_dir || sizeof($list_dirs))
{
	$template->assign_block_vars('begin_dirs', array(
		'COUNT' => sizeof($list_dirs),
		'L_COUNT' => str_replace('{COUNT}', sizeof($list_dirs), $lang['xs_fileman_dircount'])
		));
}
else
{
	$template->assign_block_vars('begin_nodirs', array());
}
if($current_dir)
{
	$template->assign_block_vars('begin_dirs.dir', array(
		'NAME' => '..',
		'FULLNAME' => htmlspecialchars($back_dir ? $back_dir . '/' : ''),
		'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($back_dir)),
		)
	);
}

// show subdirectories
sort($list_dirs);
for($i = 0; $i < sizeof($list_dirs); $i++)
{
	$dir = $list_dirs[$i];
	$str = $current_dir_root . $dir;
	$template->assign_block_vars('begin_dirs.dir', array(
		'NAME' => htmlspecialchars($dir),
		'FULLNAME' => htmlspecialchars($current_dir_root . $dir),
		'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($str)),
		)
	);
}

// show editable files
if(sizeof($list_files_editable))
{
	$template->assign_block_vars('begin_files', array('COUNT' => sizeof($list_files_editable)));
}
else
{
	$template->assign_block_vars('begin_nofiles', array('COUNT' => sizeof($list_files_editable)));
}
sort($list_files_editable);
// get today start
$today = floor((time() + 3600 * $config['board_timezone']) / 86400) * 86400 - (3600 * $config['board_timezone']);
for($i = 0; $i < sizeof($list_files_editable); $i++)
{
	$file = $list_files_editable[$i];
	$fullfile = $current_dir_root . $file;
	$localfile = '../' . $fullfile;
	$row_class = $xs_row_class[$i % 2];
	$t = @filemtime($localfile);
	$filetime = $t ? create_date($config['default_dateformat'], $t, $config['board_timezone']) : '&nbsp;';
	$template->assign_block_vars('begin_files.file', array(
		'ROW_CLASS' => $row_class,
		'NAME' => htmlspecialchars($file),
		'FULLNAME' => htmlspecialchars($fullfile),
		'SIZE' => @filesize($localfile),
		'TIME' => $filetime,
		'URL' => append_sid('xs_edit.' . PHP_EXT . $filter_str . '&amp;dir=' . urlencode($current_dir) . '&amp;edit=' . urlencode($file))
		)
	);
	if($t < $today)
	{
		$template->assign_block_vars('begin_files.file.old', array());
	}
	else
	{
		$template->assign_block_vars('begin_files.file.today', array());
	}
}

$template->set_filenames(array('body' => XS_TPL_PATH . 'edit.tpl'));
$template->pparse('body');
xs_exit();

?>