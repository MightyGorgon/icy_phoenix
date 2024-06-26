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

if (!defined('IN_ICYPHOENIX') || !defined('IN_XS'))
{
	die('Hacking attempt');
}

if(defined('XS_INCLUDED'))
{
	return;
}
define('XS_INCLUDED', true);

// uncomment next line to enable updates feature
//define('XS_ENABLE_UPDATES', 1);

// include language file
if(!defined('XS_LANG_INCLUDED'))
{
	global $config, $lang;
	setup_extra_lang(array('lang_xs'));
	define('XS_LANG_INCLUDED', true);
}

define('XS_SHOWNAV_CONFIG', 0);
define('XS_SHOWNAV_INSTALL', 1);
define('XS_SHOWNAV_UNINSTALL', 2);
define('XS_SHOWNAV_DEFAULT', 3);
define('XS_SHOWNAV_CACHE', 4);
define('XS_SHOWNAV_IMPORT', 5);
define('XS_SHOWNAV_EXPORT', 6);
define('XS_SHOWNAV_CLONE', 7);
define('XS_SHOWNAV_DOWNLOAD', 8);
define('XS_SHOWNAV_EDITTPL', 9);
define('XS_SHOWNAV_EDITDB', 10);
define('XS_SHOWNAV_EXPORTDB', 11);
define('XS_SHOWNAV_UPDATES', 12);
//define('XS_SHOWNAV_MAX', 13);
define('XS_SHOWNAV_MAX', defined('XS_ENABLE_UPDATES') ? 13 : 12);

global $xs_shownav_action;
$xs_shownav_action = array(
	'config',
	'install',
	'uninstall',
	'default',
	'cache',
	'import',
	'export',
	'clone',
	'download',
	'edittpl',
	'editdb',
	'exportdb',
	'updates',
	);

// override styles management in admin navigation
function xs_admin_override($modded = false)
{
	if(defined('XS_ADMIN_OVERRIDE_FINISHED'))
	{
		return;
	}
	define('XS_ADMIN_OVERRIDE_FINISHED', true);
	global $module, $xs_shownav_action, $config, $lang;
	// remove default phpBB styles management
	if(isset($module['Styles']))
	{
		$unset = array('Add_new', 'Create_new', 'Manage', 'Export');
		for($i = 0; $i < sizeof($unset); $i++)
		{
			if(isset($module['Styles'][$unset[$i]]))
			{
				unset($module['Styles'][$unset[$i]]);
			}
		}
		$module['Styles']['Menu'] = 'xs_frameset.' . PHP_EXT . '?action=menu&amp;showwarning=1';
	}
	// add new menu
	$module_name = '1300_Extreme_Styles';
	$module[$module_name]['Styles_Management'] = 'xs_frameset.' . PHP_EXT . '?action=menu';
	for($i = 0; $i < XS_SHOWNAV_MAX; $i++)
	{
		$num = pow(2, $i);
		if($i != XS_SHOWNAV_DOWNLOAD && ($config['xs_shownav'] & $num) > 0)
		{
			$module[$module_name][$lang['xs_config_shownav'][$i]] = 'xs_frameset.' . PHP_EXT . '?action=' . $xs_shownav_action[$i];
		}
	}
	// add menu for style configuration
	foreach($config as $var => $value)
	{
		if(substr($var, 0, 9) === 'xs_style_')
		{
			$str = substr($var, 9);
			$module['Template_Config'][$str] = 'xs_frameset.' . PHP_EXT . '?action=style_config&amp;tpl='.urlencode($str);
		}
	}
}


if(!empty($setmodules))
{
	if(@function_exists('jr_admin_get_module_list'))
	{
		$tmp_mod = $module;
		global $module;
		$module = $tmp_mod;
		xs_admin_override(true);
	}
	return;
}

//
// Global defines for eXtreme Styles mod administration panel
//
define('STYLE_HEADER_START', 'xs_style_01<xs>');
define('STYLE_HEADER_END', '</xs>');
define('STYLE_HEADER_VERSION', '1');
define('STYLE_EXTENSION', '.style');
define('TAR_HEADER_PACK', 'a100A8A8A8A12A12A8A1A100A6A2A32A32A8A8a155a12');
define('TAR_HEADER_UNPACK', 'a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor/a155prefix/a12extra');
define('XS_MAX_ITEMS_PER_STYLE', 32);
define('XS_TEMP_DIR', '../cache/');
define('XS_FTP_LOCAL', 'no_ftp');
define('XS_UPDATE_STYLE', 1);
define('XS_UPDATE_MOD', 2);
define('XS_UPDATE_PHPBB', 3);
define('XS_TPL_PATH', '../../templates/common/xs_mod/tpl/');
define('XS_BACKUP_PREFIX', 'backup.');
define('XS_BACKUP_EXT', '.backup');
define('XS_MAX_TIMEOUT', 600); // maximum timeout for downloads/import/installation

$xs_row_class = array('row1', 'row2');

$template_dir = 'templates/';

$template->assign_vars(array(
	'XS_PATH' => '../templates/common/xs_mod/',
	'XS_UL' => '<table>',
	'XS_UL2' => '</table>',
	'XS_LI' => '<tr><td class="tw20px talignc tvalignm"><img src="../templates/common/xs_mod/images/dot.gif" alt="" /></td><td class="tw100pct talignl tvalignm"><span class="gen">',
	'XS_LI2' => '</span></td></tr>',
	'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />',
	)
);

if(!defined('NO_XS_HEADER'))
{
	$template->set_filenames(array(
		'xs_header' => XS_TPL_PATH . 'xs_header.tpl',
		'xs_footer' => XS_TPL_PATH . 'xs_footer.tpl',
		)
	);
	$template->preparse = 'xs_header';
	$template->postparse = 'xs_footer';
	$template->assign_block_vars('nav_left',array('ITEM' => '<a href="' . append_sid('xs_index.' . PHP_EXT) . '">' . $lang['xs_menu'] . '</a>'));
}


header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

// Check compatibility with mods
define('XS_MODS_CATEGORY_HIERARCHY', true);

// Get FTP configuration
function get_ftp_config($action, $post = array(), $allow_local = false, $show_error = '')
{
	global $db, $config, $template, $lang;
	$config['xs_ftp_local'] = false;
	// check if ftp can be used
	if(!@function_exists('ftp_connect'))
	{
		if($allow_local && xs_dir_writable('../templates/'))
		{
			$config['xs_ftp_local'] = true;
			return true;
		}
		xs_error($lang['xs_ftp_error_fatal']);
	}
	// check if we have configuration
	if(!empty($_POST['get_ftp_config']))
	{
		$vars = array('xs_ftp_host', 'xs_ftp_login', 'xs_ftp_path');
		for($i = 0; $i < sizeof($vars); $i++)
		{
			$var = $vars[$i];
			if($config[$var] !== $_POST[$var])
			{
				$config[$var] = stripslashes($_POST[$var]);
				$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . $db->sql_escape($config[$var]) . "' WHERE config_name = '{$var}'";
				$db->sql_query($sql);
			}
		}
		$config['xs_ftp_pass'] = stripslashes($_POST['xs_ftp_pass']);
		$config['xs_ftp_local'] = empty($_POST['xs_ftp_local']) ? false : true;
		return true;
	}
	// check ftp configuration
	$xs_ftp_host = $config['xs_ftp_host'];
	if(empty($xs_ftp_host))
	{
		$str = $_SERVER['HTTP_HOST'];
		$template->assign_vars(array(
			'HOST_GUESS' => str_replace(array('{HOST}', '{CLICK}'), array($str, 'document.ftp.xs_ftp_host.value=\''.$str.'\''), $lang['xs_ftp_host_guess'])
			));
	}
	$dir = getcwd();
	$xs_ftp_login = $config['xs_ftp_login'];
	if(empty($xs_ftp_login))
	{
		if(substr($dir, 0, 6) === '/home/')
		{
			$str = substr($dir, 6);
			$pos = strpos($str, '/');
			if($pos)
			{
				$str = substr($str, 0, $pos);
				$template->assign_vars(array(
					'LOGIN_GUESS' => str_replace(array('{LOGIN}', '{CLICK}'), array($str, 'document.ftp.xs_ftp_login.value=\''.$str.'\''), $lang['xs_ftp_login_guess'])
				));
			}
		}
	}
	$xs_ftp_path = $config['xs_ftp_path'];
	if(empty($xs_ftp_path))
	{
		if(substr($dir, 0, 6) === '/home/');
		$str = substr($dir, 6);
		$pos = strpos($str, '/');
		if($pos)
		{
			$str = substr($str, $pos + 1);
			$pos = strrpos($str, 'admin');
			if($pos)
			{
				$str = substr($str, 0, $pos-1);
				$template->assign_vars(array(
					'PATH_GUESS' => str_replace(array('{PATH}', '{CLICK}'), array($str, 'document.ftp.xs_ftp_path.value=\''.$str.'\''), $lang['xs_ftp_path_guess'])
				));
			}
		}
	}
	if($allow_local && xs_dir_writable('../templates/'))
	{
		$template->assign_block_vars('xs_ftp_local', array());
	}
	else
	{
		$template->assign_block_vars('xs_ftp_nolocal', array());
	}
	$str = '<input type="hidden" name="get_ftp_config" value="1" />';
	foreach($post as $var => $value)
	{
		$str .= '<input type="hidden" name="' . htmlspecialchars($var) . '" value="' . htmlspecialchars($value) . '" />';
	}
	$template->assign_vars(array(
			'FORM_ACTION' => $action,
			'S_EXTRA_FIELDS' => $str,
			'XS_FTP_HOST' => $xs_ftp_host,
			'XS_FTP_LOGIN' => $xs_ftp_login,
			'XS_FTP_PATH' => $xs_ftp_path,
		));
	if($show_error)
	{
		$template->assign_block_vars('error', array('MSG' => $show_error));
	}
	$template->set_filenames(array('config' => XS_TPL_PATH . 'ftp.tpl'));
	$template->pparse('config');
	return false;
}

// connect ftp
function xs_ftp_connect($action, $post = array(), $allow_local = false)
{
	global $ftp, $config, $lang, $template;
	$_POST['get_ftp_config'] = '';
	if($allow_local && !empty($config['xs_ftp_local']))
	{
		$ftp = XS_FTP_LOCAL;
		return true;
	}
	$ftp = @ftp_connect($config['xs_ftp_host']);
	if(!$ftp)
	{
		get_ftp_config($action, $post, $allow_local, str_replace('{HOST}', $config['xs_ftp_host'], $lang['xs_ftp_error_connect']));
	}
	$res = @ftp_login($ftp, $config['xs_ftp_login'], $config['xs_ftp_pass']);
	if(!$res)
	{
		get_ftp_config($action, $post, $allow_local, $lang['xs_ftp_error_login']);
	}
	$res = @ftp_chdir($ftp, $config['xs_ftp_path']);
	if(!$res)
	{
		get_ftp_config($action, $post, $allow_local, str_replace('{DIR}', $config['xs_ftp_path'], $lang['xs_ftp_error_chdir']));
	}
	// check current directory
	$current_dir = @ftp_pwd($ftp);
	$list = @ftp_nlist($ftp, $current_dir);
	for($i = 0; $i < sizeof($list); $i++)
	{
		$list[$i] = strtolower(basename($list[$i]));
	}
	// check few files
	$check = array('templates', 'xs_mod');
	$found = array(false, false);
	for($i = 0; $i < sizeof($list); $i++)
	{
		for($j = 0; $j < sizeof($check); $j++)
		{
			if($list[$i] === $check[$j])
			{
				$found[$j] = true;
			}
		}
	}
	$error = false;
	for($i = 0; $i < sizeof($check); $i++)
	{
		if(!$found[$i])
		{
			$error = true;
		}
	}
	if($error)
	{
		get_ftp_config($action, $post, $allow_local, $lang['xs_ftp_error_nonphpbbdir']);
	}
	$_POST['get_ftp_config'] = '1';
}

// get .style file header
function xs_get_style_header($filename, $str = '')
{
	/*
	header format (v0.01):
	- header
	- header size (4 bytes)
	- file size (4 bytes)
	- number of entries (1 byte)
	- entries sizes (number_of_entries bytes)
	- entries
	- footer
	- gzcompressed tar of style (no crc check in tar)

	entries:
	- template name
	- comment
	- style names
	*/
	global $xs_header_error, $lang;
	$xs_header_error = '';
	if(!$str)
	{
		$f = @fopen($filename, 'rb');
		if(!$f)
		{
			$xs_header_error = $lang['xs_style_header_error_file'];
			return false;
		}
		$str = fread($f, 10240);
		fclose($f);
	}
	if(substr($str, 0, strlen(STYLE_HEADER_START)) !== STYLE_HEADER_START)
	{
		if(substr($str, 0, 7) === 'error: ')
		{
			$xs_header_error = '<br /><br />' . $lang['xs_style_header_error_server'] . substr($str, 7);
		}
		else
		{
			$xs_header_error = $lang['xs_style_header_error_invalid'];
		}
		return false;
	}
	$start = strlen(STYLE_HEADER_START);
	$str1 = substr($str, $start, 8);
	$data = unpack('Nvar1/Nvar2', $str1);
	$start += 8;
	$header_size = $data['var1'];
	$filesize = $data['var2'];
	$total = ord($str[$start]);
	$start++;
	if($total < 3)
	{
		$xs_header_error = $lang['xs_style_header_error_invalid'];
		return false;
	}
	$items_len = array();
	for($i = 0; $i <$total; $i++)
	{
		$items_len[$i] = ord($str[$i+$start]);
	}
	$start += $total;
	$items = array();
	$tpl = '';
	for($i = 0; $i <$total; $i++)
	{
		$str1 = substr($str, $start, $items_len[$i]);
		if($i == 0)	$tpl = $str1;
		elseif($i == 1)	$comment = $str1;
		else	$items[] = $str1;
		$start += $items_len[$i];
	}
	if(substr($str, $start, strlen(STYLE_HEADER_END)) !== STYLE_HEADER_END)
	{
		$xs_header_error = $lang['xs_style_header_error_invalid'];
		return false;
	}
	return array(
		'template' => $tpl,
		'styles' => $items,
		'date' => @filemtime($filename),
		'comment' => $comment,
		'offset' => $header_size,
		'filename' => $filename,
		'filesize' => $filesize,
		);
}


// check if cache is writable
function xs_check_cache($filename)
{
	// check if filename is valid
	global $str, $template, $lang;
	if(substr($filename, 0, strlen($template->cachedir)) !== $template->cachedir)
	{
		$str .= $lang['xs_check_filename'] . "<br />\n";
		return false;
	}
	else
	{
		// try to open file
		$file = @fopen($filename, 'w');
		if(!$file)
		{
			$str .= sprintf($lang['xs_check_openfile1'], $filename) . "<br />\n";
			// try to create directories
			$dir = substr($filename, strlen($template->cachedir), strlen($filename));
			$dirs = explode('/', $dir);
			$path = $template->cachedir;
			@umask(0);
			if(!@is_dir($path))
			{
				$str .= sprintf($lang['xs_check_nodir'], $path) . "<br />\n";
				if(!@mkdir($path))
				{
					$str .= sprintf($lang['xs_check_nodir2'], $path) . "<br />\n";
					return false;
				}
				else
				{
					$str .= sprintf($lang['xs_check_createddir'], $path) . "<br />\n";
					@chmod($path, 0777);
				}
			}
			else
			{
				$str .= sprintf($lang['xs_check_dir'] , $path) . "<br />\n";
			}
			if(sizeof($dirs) > 0)
			for($i = 0; $i < sizeof($dirs)-1; $i++)
			{
				if($i>0)
				{
					$path .= '/';
				}
				$path .= $dirs[$i];
				if(!@is_dir($path))
				{
					$str .= sprintf($lang['xs_check_nodir'], $path) . "<br />\n";
					if(!@mkdir($path))
					{
						$str .= sprintf($lang['xs_check_nodir2'], $path) . "<br />\n";
						return false;
					}
					else
					{
						$str .= sprintf($lang['xs_check_createddir'], $path) . "<br />\n";
						@chmod($path, 0777);
					}
				}
				else
				{
					$str .= sprintf($lang['xs_check_dir'] , $path) . "<br />\n";
				}
			}
			// try to open file again after directories were created
			$file = @fopen($filename, 'w');
		}
		if(!$file)
		{
			$str .= sprintf($lang['xs_check_openfile2'], $filename) . "<br />\n";
			return false;
		}
		$str .= sprintf($lang['xs_check_ok'], $filename) . "<br />\n";
		fwrite($file, '&nbsp;');
		fclose($file);
		@chmod($filename, 0777);
		return true;
	}
}

// run ftp commands
function ftp_myexec($list)
{
	global $ftp, $ftp_error, $ftp_log, $ftp_host, $ftp_login, $ftp_pass, $lang;
	$ftp_error = '';
	$ftp_log = array();
	if(empty($ftp))
	{
		// checking ftp extensions
		if(!@function_exists('ftp_connect'))
		{
			$ftp_log[] = $ftp_error = $lang['xs_ftp_log_disabled'];
			return false;
		}
		// connect to server
		$ftp_log[] = str_replace('{HOST}', "{$ftp_login}:*@{$ftp_host}", $lang['xs_ftp_log_connecting']);
		$ftp = @ftp_connect($ftp_host);
		if(!$ftp)
		{
			$ftp_log[] = $ftp_error = str_replace('{HOST}', $ftp_host, $lang['xs_ftp_log_noconnect']);
			return false;
		}
		$ftp_log[] = $lang['xs_ftp_log_connected'];
		// logging in
		$logged_in = @ftp_login($ftp, $ftp_login, $ftp_pass);
		if(!$logged_in)
		{
			$ftp_log[] = $ftp_error = str_replace('{USER}', $ftp_login, $lang['xs_ftp_log_nologin']);
			@ftp_close($ftp);
			return false;
		}
		$ftp_log[] = $lang['xs_ftp_log_loggedin'];
	}
	if(!ftp_myexec2($ftp, $list))
	{
		@ftp_close($ftp);
		return false;
	}
	@ftp_close($ftp);
	$ftp_log[] = $lang['xs_ftp_log_end'];
	return true;
}

// remove all files via ftp
function ftp_remove_all($ftp)
{
	// get current directory
	$root_dir = @ftp_pwd($ftp);
	// get list of files
	$files = @ftp_nlist($ftp, $root_dir);
	// remove files/directories
	for($i = 0; $i < sizeof($files); $i++)
	{
		$res = @ftp_chdir($ftp, $files[$i]);
		if($res)
		{
			ftp_remove_all($ftp);
			@ftp_chdir($ftp, $root_dir);
			@ftp_rmdir($ftp, $files[$i]);
		}
		else
		{
			if(!@ftp_delete($ftp, $files[$i]))
			{
				@ftp_rmdir($ftp, $files[$i]);
			}
		}
	}
	// change directory back
	@ftp_chdir($ftp, $root_dir);
}

// execute ftp command. recursive.
function ftp_myexec2($ftp, $list)
{
	global $ftp_log, $ftp_error, $lang;
	// getting current directory
	$root_dir = @ftp_pwd($ftp);
	if($root_dir === false)
	{
		$ftp_log[] = $ftp_error = $lang['xs_ftp_log_nopwd'];
		return false;
	}
	$current_dir = strlen($root_dir) ? $root_dir . '/' : '';
	// run commands
	for($i = 0; $i < sizeof($list); $i++)
	{
		$item=$list[$i];
		if($item['command'] == 'mkdir')
		{
			// create new directory
			$res = @ftp_mkdir($ftp, $item['dir']);
			if(!$res)
			{
				$ftp_log[] = $ftp_error = str_replace('{DIR}', $item['dir'], $lang['xs_ftp_log_nomkdir']);
				if(empty($item['ignore']))
				{
					return false;
				}
			}
			else
			{
				$ftp_log[] = str_replace('{DIR}', $item['dir'], $lang['xs_ftp_log_mkdir']);
			}
		}
		elseif($item['command'] == 'chdir')
		{
			// change current directory
			$res = @ftp_chdir($ftp, $item['dir']);
			if(!$res)
			{
				$ftp_log[] = $ftp_error = str_replace('{DIR}', $item['dir'], $lang['xs_ftp_log_nochdir']);
				if(empty($item['ignore']))
				{
					return false;
				}
			}
			else
			{
				$ftp_log[] = str_replace('{DIR}', $item['dir'], $lang['xs_ftp_log_chdir']);
			}
		}
		elseif($item['command'] == 'cdup')
		{
			// change current directory
			$res = @ftp_cdup($ftp);
			if(!$res)
			{
				$ftp_log[] = $ftp_error = str_replace('{DIR}', '..', $lang['xs_ftp_log_nochdir']);
				if(empty($item['ignore']))
				{
					return false;
				}
			}
			else
			{
				$ftp_log[] = str_replace('{DIR}', '..', $lang['xs_ftp_log_chdir']);
			}
		}
		elseif($item['command'] == 'rmdir')
		{
			// remove directory
			$res = @ftp_rmdir($ftp, $item['dir']);
			if(!$res)
			{
				$ftp_log[] = $ftp_error = str_replace('{DIR}', $item['dir'], $lang['xs_ftp_log_normdir']);
				if(empty($item['ignore']))
				{
					return false;
				}
			}
			else
			{
				$ftp_log[] = str_replace('{DIR}', $item['dir'], $lang['xs_ftp_log_rmdir']);
			}
		}
		elseif($item['command'] == 'upload')
		{
			// upload file
			$res = @ftp_put($ftp, $current_dir . $item['remote'], $item['local'], FTP_BINARY);
			if(!$res)
			{
				$ftp_log[] = $ftp_error = str_replace('{FILE}', $item['remote'], $lang['xs_ftp_log_noupload']);
				if(empty($item['ignore']))
				{
					return false;
				}
			}
			else
			{
				$ftp_log[] = str_replace('{FILE}', $item['remote'], $lang['xs_ftp_log_upload']);
			}
		}
		elseif($item['command'] == 'chmod')
		{
			// upload file
			$res = @ftp_chmod($ftp, $item['mode'], $current_dir . $item['file']);
			if(!$res)
			{
				$ftp_log[] = str_replace('{FILE}', $item['file'], $lang['xs_ftp_log_nochmod']);
				if(empty($item['ignore']))
				{
					return false;
				}
			}
			else
			{
				$ftp_log[] = str_replace(array('{FILE}', '{MODE}'), array($item['file'], $item['mode']), $lang['xs_ftp_log_chmod']);
			}
		}
		elseif($item['command'] == 'exec')
		{
			$res = ftp_myexec2($ftp, $item['list']);
			if(!$res)
			{
				return false;
			}
		}
		elseif($item['command'] == 'removeall')
		{
			ftp_remove_all($ftp);
		}
		else
		{
			$ftp_log[] = str_replace('{COMMAND}', $item['command'], $lang['xs_ftp_log_invalidcommand']);
			if(empty($item['ignore']))
			{
				return false;
			}
		}
	}
	// changing current directory back
	$ftp_log[] = str_replace('{DIR}', $root_dir, $lang['xs_ftp_log_chdir2']);
	if(!@ftp_chdir($ftp, $root_dir))
	{
		$ftp_log[] = $ftp_error = str_replace('{DIR}', $root_dir, $lang['xs_ftp_log_nochdir2']);
		return false;
	}
	return true;
}

// return data from theme_info.cfg
function xs_get_themeinfo($tpl)
{
	// Get contents of theme_info.cfg
	// Run inside function to avoid theme_info.cfg accessing global variables
	$tpl = str_replace(array('/', '\\'), array('', ''), $tpl);
	include('../templates/' . $tpl . '/theme_info.cfg');
	return isset(${$tpl}) ? ${$tpl} : array();
}

// install style
function xs_install_style($tpl, $num)
{
	global $db;
	$data = xs_get_themeinfo($tpl);
	if(empty($data[$num]))
	{
		return false;
	}
	$data = $data[$num];
	if(empty($data['style_name']))
	{
		return false;
	}
	$sql = "SELECT themes_id FROM " . THEMES_TABLE . " WHERE style_name = '" . $db->sql_escape($data['style_name']) . "'";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		return false;
	}
	$row = $db->sql_fetchrow($result);
	if(!empty($row['themes_id']))
	{
		return false;
	}
	$vars = array();
	$values = array();
	foreach($data as $var => $value)
	{
		$vars[] = $db->sql_escape($var);
		$values[] = $db->sql_escape($value);
	}
	$sql = "INSERT INTO " . THEMES_TABLE . " (" . implode(', ', $vars) . ") VALUES ('" . implode("', '", $values) . "')";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		return false;
	}

	// add configuration
	global $template;
	if($template->add_config($tpl))
	{
		define('REFRESH_NAVBAR', true);
	}
	return true;
}

// generate theme_info.cfg for template
function xs_generate_themeinfo($theme_rowset, $export, $exportas, $total)
{
	$vars = array('template_name', 'style_name', 'head_stylesheet', 'body_background', 'body_bgcolor', 'tr_class1', 'tr_class2', 'tr_class3', 'td_class1', 'td_class2', 'td_class3');
	$theme_data = '<?php'."\n\n";
	$theme_data .= "//\n// eXtreme Styles mod (compatible with phpBB 2.0.x) auto-generated theme config file for $exportas\n// Do not change anything in this file unless you know exactly what you are doing!\n//\n\n";
	for($i = 0; $i < sizeof($theme_rowset); $i++)
	{
		$id = $theme_rowset[$i]['themes_id'];
		$theme_name = $theme_rowset[$i]['style_name'];
		for($j = 0; $j < $total; $j++)
		{
			if(!empty($_POST['export_style_name_'.$j]) && $_POST['export_style_id_'.$j] == $id)
			{
				$theme_name = stripslashes($_POST['export_style_name_'.$j]);
				$theme_rowset[$i]['style_name'] = $theme_name;
			}
		}
		for($j=0; $j< sizeof($vars); $j++)
		{
			$key = $vars[$j];
			$val = $theme_rowset[$i][$key];
			if($key === 'style_name')
			{
				$theme_data .= '${\'' . $exportas . "'}[$i]['$key'] = \"" . str_replace(array("'", '"'), array("\'", "\\\""), $theme_name) . "\";\n";
			}
			elseif($key === 'template_name')
			{
				$theme_data .= '${\'' . $exportas . "'}[$i]['$key'] = \"" . str_replace(array("'", '"'), array("\'", "\\\""), $exportas) . "\";\n";
			}
			else
			{
				$theme_data .= '${\'' . $exportas . "'}[$i]['$key'] = \"" . str_replace(array("'", '"'), array("\'", "\\\""), str_replace($export, $exportas, $val)) . "\";\n";
			}
		}
		$theme_data .= "\n";
	}
	$theme_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!
	return $theme_data;
}

// Checks if directory is writable
function xs_dir_writable($dir)
{
	$filename = 'tmp_' . time();
	$f = @fopen($dir . $filename, 'wb');
	if($f)
	{
		fclose($f);
		@unlink($dir . $filename);
		return true;
	}
	return false;
}

// Write to file. Create directory if necessary
function xs_write_file($filename, $data)
{
	$f = @fopen($filename, 'wb');
	if(!$f)
	{
		// try to create directories
		$pos = strrpos($filename, '/');
		if(!$pos)
		{
			return false;
		}
		$dir = substr($filename, 0, $pos);
		xs_create_dir($dir);
		$f = @fopen($filename, 'wb');
		if(!$f)
		{
			return false;
		}
	}
	fwrite($f, $data);
	fclose($f);
	@chmod($filename, 0777);
	return true;
}

// Create local directory
function xs_create_dir($dir)
{
	if(!$dir)
	{
		return false;
	}
	// remove trailing /
	if(substr($dir, strlen($dir) - 1) === '/')
	{
		$dir = substr($dir, 0, strlen($dir) - 1);
		if(!$dir)
		{
			return false;
		}
	}
	if($dir === '.' || $dir === '..')
	{
		return false;
	}
	$res = @mkdir($dir, 0777);
	if($res)
	{
		return true;
	}
	// try to create previous directory
	$pos = strrpos($dir, '/');
	if(!$pos)
	{
		return false;
	}
	$dir1 = substr($dir, 0, $pos);
	$dir2 = substr($dir, $pos+1);
	if($dir2 === '.' || $dir2 === '..')
	{
		return false;
	}
	if(!xs_create_dir($dir1))
	{
		return false;
	}
	$res = @mkdir($dir2, 0777);
	return $res ? true : false;
}

// replacement for in_array() (because of compatibility problems)
function xs_in_array($needle, $haystack)
{
	for($i = 0; $i < sizeof($haystack); $i++)
	{
		if($haystack[$i] == $needle)
		{
			return true;
		}
	}
	return false;
}

// show error and exit
function xs_error($error, $line = 0, $file = '')
{
	global $template, $lang;
	if($line || $file)
	{
		$error = basename($file) . '(' . $line . '): ' . $error;
	}
	$template->set_filenames(array('errormsg' => XS_TPL_PATH . 'message.tpl'));
	$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Error'],
			'MESSAGE_TEXT' => $error
		));
	$template->pparse('errormsg');
	xs_exit();
}

// show message and exit
function xs_message($title, $message)
{
	global $template;
	$template->set_filenames(array('msg' => XS_TPL_PATH . 'message.tpl'));
	$template->assign_vars(array(
			'MESSAGE_TITLE' => $title,
			'MESSAGE_TEXT' => $message
		));
	$template->pparse('msg');
	xs_exit();
}

// pack style to .style
function pack_style($name, $newname, $themes, $comment)
{
	/*
	header format (v0.01):
	- header
	- header size (4 bytes)
	- file size (4 bytes)
	- number of entries (1 byte)
    - entries sizes (number_of_entries bytes)
    - entries
	- footer
	- gzcompressed tar of style (no crc check in tar)

	entries:
	  - template name
      - comment
	  - style names
	*/
	global $template_dir;
	$data = gzcompress(pack_dir(IP_ROOT_PATH . $template_dir . $name, '', $name, $newname));
	$items_data = chr(strlen($newname)) . chr(strlen($comment));
	$items_str = $newname . $comment;
	for($i = 0; $i < sizeof($themes); $i++)
	{
		$str = $themes[$i]['style_name'];
		$items_data .= chr(strlen($str));
		$items_str .= $str;
	}
	$header_size = strlen(STYLE_HEADER_START) + 8 + 1 + strlen($items_data) + strlen($items_str) + strlen(STYLE_HEADER_END);
	$filesize = $header_size + strlen($data);
	$header = STYLE_HEADER_START . pack('NN', $header_size, $filesize) . chr(strlen($items_data)) . $items_data . $items_str . STYLE_HEADER_END;
	return $header . $data;
}

// pack directory
function pack_dir($dir1, $dir2, $search, $replace)
{
	global $pack_error, $pack_list, $pack_replace, $lang;
	// replacements in content
	$search2 = array('templates/' . $search . '/' . $search, 'templates/' . $search);
	$replace2 = array('templates/' . $replace . '/' . $replace, 'templates/' . $replace);
	// replacements in filename
	$search3 = './'.$search;
	$replace3 = './'.$replace;
	if($pack_error)
	{
		return '';
	}
	$dir = $dir1 . '/' . $dir2;
	$res = @opendir($dir);
	$str = '';
	if(!$res)
	{
		$pack_error = str_replace('{DIR}', $lang['xs_export_no_open_dir'], $dir);
		return '';
	}
	// get list of files/directories
	$files = array();
	$subdir = array();
	while(($file = readdir($res)) !== false)
	{
		if($file !== '.' && $file !== '..')
		{
			if(@is_dir($dir . '/' . $file))
			{
				$subdir[] = $file;
			}
			elseif(@is_file($dir . '/' . $file))
			{
				$files[] = $file;
			}
		}
	}
	closedir($res);
	// add current directory
	$base_dir = ($dir2 ? $dir2 : '.') . '/';
	$header = array(
		'filename' => $base_dir,
		'mode' => '40777',
		'uid' => '0',
		'gid' => '0',
		'size' => decoct(0),
		'mtime' => decoct(@filemtime($dir)),
		'checksum' => '0',	// ignore checksum
		'typeflag' => '5',
		'link' => '',
		'magic' => "ustar",
		'version' => '',
		'uname' => 'user',
		'gname' => 'group',
		'devmajor' => '',
		'devminor' => '',
		'prefix' => '',
		'extra' => ''
		);
	$header_str = pack(TAR_HEADER_PACK, $header['filename'], $header['mode'], $header['uid'], $header['gid'], $header['size'], $header['mtime'], $header['checksum'], $header['typeflag'], $header['linkname'], $header['magic'], $header['version'], $header['uname'], $header['gname'], $header['devmajor'], $header['devminor'], $header['prefix'], $header['extra']);
	$file_str = '';
	$extra_str = '';
	$str .= $header_str . $file_str . $extra_str;
	// add all files
	for($i = 0; $i < sizeof($files); $i++)
	{
		$file = $files[$i];
		$header['filename'] = $base_dir . $file;
		$pack_list[] = $header['filename'];
		if(isset($pack_replace[$header['filename']]))
		{
			$file_str = $pack_replace[$header['filename']];
			$file_size = strlen($file_str);
		}
		else
		{
			$f = @fopen($dir . '/' . $file, 'rb');
			if(!$f)
			{
				$pack_error = str_replace('{FILE}', $dir . '/' . $file, $lang['xs_export_no_open_file']);
				return '';
			}
			$file_size = @filesize($dir . '/' . $file);
			if($file_size)
			{
				$file_str = fread($f, $file_size);
			}
			else
			{
				$file_str = '';
			}
			if(strlen($file_str) != $file_size)
			{
				$pack_error = str_replace('{FILE}', $dir . '/' . $file, $lang['xs_export_no_read_file']);
				return '';
			}
			fclose($f);
			if($search !== $replace)
			{
				$file_str = str_replace($search2, $replace2, $file_str);
			}
		}
		if($search !== $replace && substr($header['filename'], strlen($header['filename']) - 4) !== '.tpl')
		{
			if(substr($header['filename'], 0, strlen($search)) === $search)
			{
				$header['filename'] = $replace . substr($header['filename'], strlen($search));
			}
			elseif(substr($header['filename'], 0, strlen($search3)) === $search3)
			{
				$header['filename'] = $replace3 . substr($header['filename'], strlen($search3));
			}
		}
		/*
		echo 'filename: ', $header['filename'], '<br />';
		if($header['filename'] === './overall_header.tpl')
		{
			echo 'overall_header.tpl:<br /><hr />', nl2br(htmlspecialchars($file_str)), '<br /><hr /><br />';
		}*/
		$size = strlen($file_str);
		$header['size'] = decoct($size);
		$header['typeflag'] = '0';
		$header['mode'] = '100666';
		$full_size = floor(($size + 511) / 512) * 512;
		$extra_str = $full_size > $size ? str_repeat("\0", $full_size - $size) : '';
		$header_str = pack(TAR_HEADER_PACK, $header['filename'], $header['mode'], $header['uid'], $header['gid'], $header['size'], $header['mtime'], $header['checksum'], $header['typeflag'], $header['linkname'], $header['magic'], $header['version'], $header['uname'], $header['gname'], $header['devmajor'], $header['devminor'], $header['prefix'], $header['extra']);
		$str .= $header_str . $file_str . $extra_str;
	}
	// add all directories
	for($i = 0; $i < sizeof($subdir); $i++)
	{
		$str .= pack_dir($dir1, $dir2 ? $dir2 . '/' . $subdir[$i] : $subdir[$i], $search, $replace);
	}
	if(!$dir2)
	{
		$str .= str_repeat("\0", 1024);
	}
	return $str;
}

// save export configuration
function set_export_method($method, $data)
{
	global $db, $config;
	$data['method'] = $method;
	$str = $db->sql_escape(serialize($data));
	$sql = isset($config['xs_export_data']) ? "UPDATE " . CONFIG_TABLE . " SET config_value='{$str}' WHERE config_name='xs_export_data'" : "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('xs_export_data', '{$str}')";
	$db->sql_query($sql);
}

// send file
function xs_download_file($filename, $content, $content_type = '')
{
	if(empty($content_type))
	{
		$content_type = 'application/unknown';
	}
	header('Content-Type: ' . $content_type);
	header('Content-Length: ' . strlen($content));
	header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	if($filename)
	{
		header('Content-Disposition: inline; filename="' . $filename . '"');
	}
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	echo $content;
}

// strip slashes for sql
function xs_sql($sql, $strip = false)
{
	global $db;
	$sql = $db->sql_escape($sql);
	return $sql;
	// Mighty Gorgon: old code removed...
	/*
	if($strip)
	{
		$sql = stripslashes($sql);
	}
	return str_replace('\\\'', '\'\'', addslashes($sql));
	*/
}

// clean template name
function xs_tpl_name($name)
{
	return str_replace(array('\\', '/', "'", '"'), array('','','',''), $name);
}

// close database and maybe do some other stuff
function xs_exit()
{
	global $db;
	if(isset($db))
	{
		$db->sql_close();
	}
	exit;
}

// check directory name/filename
function xs_fix_dir($dir)
{
	$dir = str_replace('\\', '/', $dir);
	$dir = str_replace('../', './', $dir);
	while(strlen($dir > 1) && substr($dir, strlen($dir) - 2) === '..')
	{
		$dir = substr($dir, 0, strlen($dir) - 1);
	}
	return $dir;
}

?>
