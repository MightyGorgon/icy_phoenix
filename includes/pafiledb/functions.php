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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}


class pafiledb_functions
{
	function set_config($config_name, $config_value, $clear_cache = true)
	{
		global $db, $cache;
		global $pafiledb_config;

		$sql = "UPDATE " . PA_CONFIG_TABLE . " SET
			config_value = '" . $db->sql_escape($config_value) . "'
			WHERE config_name = '" . $db->sql_escape($config_name) . "'";
		$db->sql_query($sql);

		if (!$db->sql_affectedrows() && !isset($pafiledb_config[$config_name]))
		{
			$sql = "INSERT INTO " . PA_CONFIG_TABLE . " (config_name, config_value)
				VALUES ('" . $db->sql_escape($config_name) . "', '" . $db->sql_escape($config_value) . "')";
			$db->sql_query($sql);
		}

		$pafiledb_config[$config_name] = $config_value;

		if ($clear_cache)
		{
			$cache->destroy('_config_pafiledb');
			$db->clear_cache('config_pafiledb_');
		}
	}

	function pafiledb_config($from_cache = true)
	{
		global $db, $cache;

		$sql = "SELECT * FROM " . PA_CONFIG_TABLE;
		$result = $from_cache ? $db->sql_query($sql, 0, 'config_pafiledb_') : $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$config[$row['config_name']] = trim($row['config_value']);
		}
		$db->sql_freeresult($result);

		return $config;
	}

	/*
	* Get config values
	*/
	function obtain_pafiledb_config()
	{
		global $db, $cache;

		if (($config = $cache->get('_config_pafiledb')) === false)
		{
			$config = array();
			$config = $this->pafiledb_config(false);
			$cache->put('_config_pafiledb', $config);
		}

		return $config;
	}

	function post_icons($file_posticon = '')
	{
		global $lang;
		$curicons = 1;

		if (empty($file_posticon) || ($file_posticon == 'none') || ($file_posticon == 'none.gif'))
		{
			$posticons .= '<input type="radio" name="posticon" value="none" checked="checked" /><a class="gensmall">' . $lang['None'] . '</a>&nbsp;';
		}
		else
		{
			$posticons .= '<input type="radio" name="posticon" value="none" /><a class="gensmall">' . $lang['None'] . '</a>&nbsp;';
		}

		$handle = @opendir(IP_ROOT_PATH . FILES_ICONS_DIR);

		while ($icon = @readdir($handle))
		{
			$file_extensions = array('gif', 'jpg', 'jpeg', 'png');
			$file_extension = substr(strrchr($icon, '.'), 1);
			if (($icon != '.') && ($icon != '..') && in_array($file_extension, $file_extensions) && ($icon != 'spacer.gif') && !is_dir(IP_ROOT_PATH . $icon))
			{
				if ($file_posticon == $icon)
				{
					$posticons .= '<input type="radio" name="posticon" value="' . $icon . '" checked="checked" /><img src="' . IP_ROOT_PATH . FILES_ICONS_DIR . $icon . '" alt="" />&nbsp;';
				}
				else
				{
					$posticons .= '<input type="radio" name="posticon" value="' . $icon . '" /><img src="' . IP_ROOT_PATH . FILES_ICONS_DIR . $icon . '" alt="" />&nbsp;';
				}

				$curicons++;

				if ($curicons == 8)
				{
					$posticons .= '<br />';
					$curicons = 0;
				}
			}
		}
		@closedir($handle);
		return $posticons;
	}

	function license_list($license_id = 0)
	{
		global $db, $lang;

		if ($license_id == 0)
		{
			$list .= '<option calue="0" selected>' . $lang['None'] . '</option>';
		}
		else
		{
			$list .= '<option calue="0">' . $lang['None'] . '</option>';
		}

		$sql = 'SELECT *
			FROM ' . PA_LICENSE_TABLE . '
			ORDER BY license_id';
		$result = $db->sql_query($sql);

		while ($license = $db->sql_fetchrow($result))
		{
			if ($license_id == $license['license_id'])
			{
				$list .= '<option value="' . $license['license_id'] . '" selected>' . $license['license_name'] . '</option>';
			}
			else
			{
				$list .= '<option value="' . $license['license_id'] . '">' . $license['license_name'] . '</option>';
			}
		}
		return $list;
	}

	function gen_unique_name($file_type)
	{
		global $pafiledb_config;

		srand((double) microtime() * 1000000);	// for older than version 4.2.0 of PHP

		do
		{
			$filename = md5(uniqid(rand())) . $file_type;
		}
		while(file_exists($pafiledb_config['upload_dir'] . '/' . $filename));

		return $filename;
	}


	function get_extension($filename)
	{
		$help = explode('.', $filename);
		$tmp = strtolower(array_pop($help));
		return $tmp;
	}

	function upload_file($userfile, $userfile_name, $userfile_size, $upload_dir = '', $local = false)
	{
		global $lang, $config, $pafiledb_config, $user;

		$upload_dir = (substr($upload_dir, 0, 1) == '/') ? substr($upload_dir, 1) : $upload_dir;

		@set_time_limit(0);
		$file_info = array();

		$file_info['error'] = false;

		if(file_exists(IP_ROOT_PATH . $upload_dir . $userfile_name))
		{
			$userfile_name = time() . '_' . $userfile_name;
		}

		// =======================================================
		// if the file size is more than the allowed size another error message
		// =======================================================

		if (($userfile_size > $pafiledb_config['max_file_size']) && ($user->data['user_level'] != ADMIN) && $user->data['session_logged_in'])
		{
			$file_info['error'] = true;
			if(!empty($file_info['message']))
			{
				$file_info['message'] .= '<br />';
			}
			$file_info['message'] .= $lang['Filetoobig'];
		}

		// =======================================================
		// Then upload the file, and check the php version
		// =======================================================

		else
		{
			$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

			$upload_mode = (@$ini_val('open_basedir') || @$ini_val('safe_mode')) ? 'move' : 'copy';
			$upload_mode = ($local) ? 'local' : $upload_mode;

			if($this->do_upload_file($upload_mode, $userfile, IP_ROOT_PATH . $upload_dir . $userfile_name))
			{
				$file_info['error'] = true;
				if(!empty($file_info['message']))
				{
					$file_info['message'] .= '<br />';
				}
				$file_info['message'] .= 'Couldn\'t Upload the File.';
			}

			$file_info['url'] = create_server_url() . $upload_dir . $userfile_name;
		}
		return $file_info;
	}

	function do_upload_file($upload_mode, $userfile, $userfile_name)
	{
		switch ($upload_mode)
		{
			case 'copy':
				if (!@copy($userfile, $userfile_name))
				{
					if (!@move_uploaded_file($userfile, $userfile_name))
					{
						return false;
					}
				}
				@chmod($userfile_name, 0666);
				break;

			case 'move':
				if (!@move_uploaded_file($userfile, $userfile_name))
				{
					if (!@copy($userfile, $userfile_name))
					{
						return false;
					}
				}
				@chmod($userfile_name, 0666);
				break;

			case 'local':
				if (!@copy($userfile, $userfile_name))
				{
					return false;
				}
				@chmod($userfile_name, 0666);
				@unlink($userfile);
				break;
		}

		return;
	}

	function get_file_size($file_id, $file_data = '')
	{
		global $db, $lang, $pafiledb_config;

		$directory = IP_ROOT_PATH . $pafiledb_config['upload_dir'];

		if(empty($file_data))
		{
			$sql = "SELECT file_dlurl, file_size, unique_name, file_dir
				FROM " . PA_FILES_TABLE . "
				WHERE file_id = '" . $file_id . "'";
			$result = $db->sql_query($sql);
			$file_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		$file_url = $file_data['file_dlurl'];
		$file_size = $file_data['file_size'];

		$html_path = create_server_url() . $directory;
		$update_filesize = false;

		if (((substr($file_url, 0, strlen($html_path)) == $html_path) || !empty($file_data['unique_name'])) && empty($file_size))
		{
			$file_url = basename($file_url) ;
			$file_name = basename($file_url);

			if((!empty($file_data['unique_name'])) && (!file_exists(IP_ROOT_PATH . $file_data['file_dir'] . $file_data['unique_name'])))
			{
				return $lang['Not_available'];
			}

			if(empty($file_data['unique_name']))
			{
				$file_size = @filesize($directory . $file_name);
			}
			else
			{
				$file_size = @filesize(IP_ROOT_PATH . $file_data['file_dir'] . $file_data['unique_name']);
			}

			$update_filesize = true;
		}
		elseif(empty($file_size) && ((!(substr($file_url, 0, strlen($html_path)) == $html_path)) || empty($file_data['unique_name'])))
		{
			$ourhead = "";
			$url = parse_url($file_url);
			$host = $url['host'];
			$path = $url['path'];
			$port = (!empty($url['port'])) ? $url['port'] : 80;

			$fp = @fsockopen($host, $port, &$errno, &$errstr, 20);

			if(!$fp)
			{
				return $lang['Not_available'];
			}
			else
			{
				fwrite($fp, "HEAD $file_url HTTP/1.1\r\n");
				fwrite($fp, "HOST: $host\r\n");
				fwrite($fp, "Connection: close\r\n\r\n");

				while (!feof($fp))
				{
					$ourhead = sprintf('%s%s', $ourhead, fgets ($fp,128));
				}
			}
			@fclose ($fp);

			$split_head = explode('Content-Length: ', $ourhead);

			$file_size = round(abs($split_head[1]));
			$update_filesize = true;
		}

		if($update_filesize)
		{
			$sql = 'UPDATE ' . PA_FILES_TABLE . "
				SET file_size = '$file_size'
				WHERE file_id = '$file_id'";
			$db->sql_query($sql);
		}

		if ($file_size < 1024)
		{
			$file_size_out = intval($file_size) . ' ' . $lang['Bytes'];
		}
		if ($file_size >= 1025)
		{
			$file_size_out = round(intval($file_size) / 1024 * 100) / 100 . ' ' . $lang['KB'];
		}
		if ($file_size >= 1048575)
		{
			$file_size_out = round(intval($file_size) / 1048576 * 100) / 100 . ' ' . $lang['MB'];
		}

		return $file_size_out;

	}

	function get_rating($file_id, $file_rating = '')
	{
		global $db, $lang;

		$sql = "SELECT AVG(rate_point) AS rating
			FROM " . PA_VOTES_TABLE . "
			WHERE votes_file = '" . $file_id . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$file_rating = $row['rating'];

		return ($file_rating != 0) ? round($file_rating, 2) . ' / 10' : $lang['Not_rated'];
	}

	function pafiledb_unlink($filename)
	{
		global $pafiledb_config, $lang;

		$deleted = @unlink($filename);

		if (@file_exists($this->pafiledb_realpath($filename)))
		{
			$filesys = eregi_replace('/','\\', $filename);
			$deleted = @system("del $filesys");

			if (@file_exists($this->pafiledb_realpath($filename)))
			{
				$deleted = @chmod ($filename, 0775);
				$deleted = @unlink($filename);
				$deleted = @system("del $filesys");
			}
		}

		return ($deleted);
	}

	function pafiledb_realpath($path)
	{

		return (!@function_exists('realpath') || !@realpath(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT)) ? $path : @realpath($path);
	}

	function sql_query_limit($query, $total, $offset = 0)
	{
		global $db;

		$query .= ' LIMIT ' . ((!empty($offset)) ? $offset . ', ' . $total : $total);
		$db->sql_return_on_error(true);
		$result = $db->sql_query($query);
		$db->sql_return_on_error(false);

		return $result;
	}
}

function pafiledb_page_header($page_title)
{
	global $db, $cache, $config, $template, $images, $theme, $user, $lang, $tree;
	global $table_prefix, $SID, $_SID;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $session_length, $starttime, $base_memory_usage, $do_gzip_compress, $start;
	global $gen_simple_header, $meta_content, $nav_separator, $nav_links, $nav_pgm, $nav_add_page_title, $skip_nav_cat;
	global $breadcrumbs_address, $breadcrumbs_links_left, $breadcrumbs_links_right;

	global $pafiledb, $pafiledb_config, $action;
	global $admin_level, $level_prior, $debug;

	if($action != 'download')
	{
		$meta_content['page_title'] = $lang['Downloads'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		$nav_server_url = create_server_url();
		$bc_nav_links = '';
		$file_id = request_var('file_id', 0);
		$cat_id = request_var('cat_id', 0);
		switch ($action)
		{
			case 'user_upload':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['User_upload'] . '</a>';
				break;
			case 'license':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['License'] . '</a>';
				break;
			case 'mcp':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['MCP_title'] . '</a>';
				break;
			case 'stats':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['Statistics'] . '</a>';
				break;
			case 'search':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['Search'] . '</a>';
				break;
			case 'toplist':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['Toplist'] . '</a>';
				break;
			case 'viewall':
				$bc_nav_links = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '" class="nav-current">' . $lang['Viewall'] . '</a>';
				break;
		}
		if ($cat_id || $file_id)
		{
			$bc_nav_links = $pafiledb->generate_category_nav_links($cat_id, $file_id);
		}
		$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT) . '"' . (($bc_nav_links == '') ? ' class="nav-current"' : '') . '>' . $lang['Downloads'] . '</a>' . $bc_nav_links;
		$breadcrumbs_links_right = '';
		page_header($meta_content['page_title'], true);
	}

	if($action == 'category')
	{
		$upload_url = append_sid('dload.' . PHP_EXT . "?action=user_upload&amp;cat_id={$_REQUEST['cat_id']}");
		$upload_auth = $pafiledb->modules[$pafiledb->module_name]->auth[$_REQUEST['cat_id']]['auth_upload'];
		$mcp_url = append_sid('dload.' . PHP_EXT . "?action=mcp&amp;cat_id={$_REQUEST['cat_id']}");
		$mcp_auth = $pafiledb->modules[$pafiledb->module_name]->auth[$_REQUEST['cat_id']]['auth_mod'];
	}
	else
	{
		$upload_url = append_sid('dload.' . PHP_EXT . '?action=user_upload');
		$cat_list = $pafiledb->modules[$pafiledb->module_name]->jumpmenu_option(0, 0, '', true, true);
		//$upload_auth = (empty($cat_list)) ? false : true;
		$upload_auth = false;
		$mcp_auth = false;
		unset($cat_list);
	}

	$is_auth_viewall = ($pafiledb_config['settings_viewall']) ? (($pafiledb->modules[$pafiledb->module_name]->auth_global['auth_viewall']) ? true : false) : false;
	$is_auth_search = ($pafiledb->modules[$pafiledb->module_name]->auth_global['auth_search']) ? true : false;
	$is_auth_stats = ($pafiledb->modules[$pafiledb->module_name]->auth_global['auth_stats']) ? true : false;
	$is_auth_toplist = ($pafiledb->modules[$pafiledb->module_name]->auth_global['auth_toplist']) ? true : false;
	$is_auth_upload = $upload_auth;
	$show_top_links = (!$is_auth_viewall && !$is_auth_search && !$is_auth_stats && !$is_auth_toplist && !$is_auth_upload) ? false : true;

	$template->assign_vars(array(
		'S_TOP_LINKS' => $show_top_links,
		'IS_AUTH_VIEWALL' => $is_auth_viewall,
		'IS_AUTH_SEARCH' => $is_auth_search,
		'IS_AUTH_STATS' => $is_auth_stats,
		'IS_AUTH_TOPLIST' => $is_auth_toplist,
		'IS_AUTH_UPLOAD' => $is_auth_upload,
		'IS_ADMIN' => (($user->data['user_level'] == ADMIN) && $user->data['session_logged_in']) ? true : 0,
		//'IS_MOD' => $pafiledb->modules[$pafiledb->module_name]->is_moderator(),
		'IS_MOD' => $pafiledb->modules[$pafiledb->module_name]->auth[$_REQUEST['cat_id']]['auth_mod'],
		'IS_AUTH_MCP' => $mcp_auth,
		'MCP_LINK' => $lang['pa_MCP'],
		'U_MCP' => $mcp_url,

		'L_OPTIONS' => $lang['Options'],
		'L_SEARCH' => $lang['Search'],
		'L_STATS' => $lang['Statistics'],
		'L_TOPLIST' => $lang['Toplist'],
		'L_UPLOAD' => $lang['User_upload'],
		'L_VIEW_ALL' => $lang['Viewall'],

		'SEARCH_IMG' => $images['pa_search'],
		'STATS_IMG' => $images['pa_stats'],
		'TOPLIST_IMG' => $images['pa_toplist'],
		'UPLOAD_IMG' => $images['pa_upload'],
		'VIEW_ALL_IMG' => $images['pa_viewall'],

		'U_TOPLIST' => append_sid('dload.' . PHP_EXT . '?action=toplist'),
		'U_PASEARCH' => append_sid('dload.' . PHP_EXT . '?action=search'),
		'U_UPLOAD' => $upload_url,
		'U_VIEW_ALL' => append_sid('dload.' . PHP_EXT . '?action=viewall'),
		'U_PASTATS' => append_sid('dload.' . PHP_EXT . '?action=stats')
		)
	);

}
//===================================================
// page footer for pafiledb
//===================================================
function pafiledb_page_footer()
{
	global $db, $cache, $config, $template, $images, $theme, $user, $lang, $tree;
	global $table_prefix, $SID, $_SID;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $session_length, $starttime, $base_memory_usage, $do_gzip_compress, $start;
	global $gen_simple_header, $meta_content, $nav_separator, $nav_links, $nav_pgm, $nav_add_page_title, $skip_nav_cat;
	global $breadcrumbs_address, $breadcrumbs_links_left, $breadcrumbs_links_right;

	global $pafiledb, $pafiledb_config, $action;
	global $admin_level, $level_prior, $debug;

	$template->assign_vars(array(
		'JUMPMENU' => $pafiledb->modules[$pafiledb->module_name]->jumpmenu_option(),
		'L_JUMP' => $lang['jump'],
		'S_JUMPBOX_ACTION' => append_sid('dload.' . PHP_EXT),
		'S_TIMEZONE' => sprintf($lang['All_times'], $lang['tz'][str_replace('.0', '', sprintf('%.1f', number_format($config['board_timezone'], 1)))])
		)
	);
	$pafiledb->modules[$pafiledb->module_name]->_pafiledb();
	if(!isset($_GET['explain']))
	{
		//$template->display('body');
		$template->pparse('body');
	}

	if($action != 'download')
	{
		page_footer(true, '', true);
	}
}

//=========================================
// This class is used to determin Browser and operating system info of the user
//
//  Copyright (c) 2002 Chip Chapin <cchapin@chipchapin.com>
//                     http://www.chipchapin.com
//  All rights reserved.
//=========================================


class user_info
{
	var $agent = 'unknown';
	var $ver = 0;
	var $majorver = 0;
	var $minorver = 0;
	var $platform = 'unknown';

	/* Constructor
	 Determine client browser type, version and platform using
	 heuristic examination of user agent string.
	 @param $user_agent_pa allows override of user agent string for testing.
	*/

	function user_info($user_agent_pa = '')
	{
		global $HTTP_USER_AGENT;

		if (!empty($_SERVER['HTTP_USER_AGENT']))
		{
			$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		}
		elseif (!empty($_SERVER['HTTP_USER_AGENT']))
		{
			$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		}
		elseif (!isset($HTTP_USER_AGENT))
		{
			$HTTP_USER_AGENT = '';
		}

		if (empty($user_agent_pa))
		{
			$user_agent_pa = $HTTP_USER_AGENT;
		}

		$user_agent_pa = strtolower($user_agent_pa);

		// Determine browser and version
		// The order in which we test the agents patterns is important
		// Intentionally ignore Konquerer.  It should show up as Mozilla.
		// post-Netscape Mozilla versions using Gecko show up as Mozilla 5.0

		if (preg_match('/(opera |opera\/)([0-9]*).([0-9]{1,2})/', $user_agent_pa, $matches)) ;
		elseif (preg_match('/(msie)([0-9]*).([0-9]{1,2})/', $user_agent_pa, $matches)) ;
		elseif (preg_match('/(mozilla\/)([0-9]*).([0-9]{1,2})/', $user_agent_pa, $matches)) ;
		else
		{
			$matches[1] = 'unknown';
			$matches[2] = 0;
			$matches[3] = 0;
		}

		$this->majorver = $matches[2];
		$this->minorver = $matches[3];
		$this->ver = $matches[2] . '.' . $matches[3];

		switch ($matches[1])
		{
			case 'opera/':
			case 'opera ':
				$this->agent = 'OPERA';
				break;
			case 'msie ':
				$this->agent = 'IE';
				break;
			case 'mozilla/':
				$this->agent = 'NETSCAPE';
				if($this->majorver >= 5)
				{
					$this->agent = 'MOZILLA';
				}
				break;
			case 'unknown':
				$this->agent = 'OTHER';
				break;

			default:
				$this->agent = 'Oops!';
		}

		// Determine platform
		// This is very incomplete for platforms other than Win/Mac

		if (preg_match('/(win|mac|linux|unix)/', $user_agent_pa, $matches));
		else $matches[1] = 'unknown';

		switch ($matches[1])
		{
			case 'win':
				$this->platform = 'Win';
				break;

			case 'mac':
				$this->platform = 'Mac';
				break;

			case 'linux':
				$this->platform = 'Linux';
				break;

			case 'unix':
				$this->platform = 'Unix';
				break;

			case 'unknown':
				$this->platform = 'Other';
				break;

			default:
				$this->platform = 'Oops!';
		}
	}

	function update_downloader_info($file_id)
	{
		global $db, $user;

		$where_sql = ($user->data['user_id'] != ANONYMOUS) ? "user_id = '" . $user->data['user_id'] . "'" : "downloader_ip = '" . $db->sql_escape($user->ip) . "'";

		$sql = "SELECT user_id, downloader_ip
			FROM " . PA_DOWNLOAD_INFO_TABLE . "
			WHERE $where_sql";
		$result = $db->sql_query($sql);

		if(!$db->sql_numrows($result))
		{
			$sql = "INSERT INTO " . PA_DOWNLOAD_INFO_TABLE . " (file_id, user_id, download_time, downloader_ip, downloader_os, downloader_browser, browser_version)
						VALUES('" . $file_id . "', '" . $user->data['user_id'] . "', '" . time() . "', '" . $db->sql_escape($user->ip) . "', '" . $db->sql_escape($this->platform) . "', '" . $db->sql_escape($this->agent) . "', '" . $db->sql_escape($this->ver) . "')";
			$db->sql_query($sql);
		}

		$db->sql_freeresult($result);
	}

	function update_voter_info($file_id, $rating)
	{
		global $db, $user, $lang;

		$where_sql = ($user->data['user_id'] != ANONYMOUS) ? "user_id = '" . $user->data['user_id'] . "'" : "votes_ip = '" . $db->sql_escape($user->ip) . "'";

		$sql = "SELECT user_id, votes_ip
			FROM " . PA_VOTES_TABLE . "
			WHERE $where_sql
			AND votes_file = '" . $file_id . "'
			LIMIT 1";
		$result = $db->sql_query($sql);

		if(!$db->sql_numrows($result))
		{
			$sql = "INSERT INTO " . PA_VOTES_TABLE . " (user_id, votes_ip, votes_file, rate_point, voter_os, voter_browser, browser_version)
						VALUES('" . $user->data['user_id'] . "', '" . $db->sql_escape($user->ip) . "', '" . $file_id . "','" . $rating ."', '" . $db->sql_escape($this->platform) . "', '" . $db->sql_escape($this->agent) . "', '" . $db->sql_escape($this->ver) . "')";
			$db->sql_query($sql);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Rerror']);
		}

		$db->sql_freeresult($result);
	}
}

?>