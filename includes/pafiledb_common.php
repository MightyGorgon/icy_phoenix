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
* Mohd - (mohdalbasri@hotmail.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//===================================================
// addslashes to vars if magic_quotes_gpc is off
//===================================================
if(!@function_exists('slash_input_data'))
{
	function slash_input_data(&$data)
	{
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$data[$k] = (is_array($v)) ? slash_input_data($v) : addslashes($v);
			}
		}
		return $data;
	}
}

if (!isset($_REQUEST))
{
	$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
}

if (!get_magic_quotes_gpc())
{
	$_GET = slash_input_data($_GET);
	$_POST = slash_input_data($_POST);
	$_COOKIE = slash_input_data($_COOKIE);
	$_REQUEST = slash_input_data($_REQUEST);
}

//===================================================
// Get Language
//===================================================
$language = $board_config['default_lang'];

include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_pafiledb.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_admin_pafiledb.' . PHP_EXT);

//===================================================
// Include pafiledb data file
//===================================================
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'pafiledb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_cache.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'template.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_pafiledb.' . PHP_EXT);
if (defined('IN_ADMIN'))
{
	include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_pafiledb_admin.' . PHP_EXT);
}

$cache = new acm();
$pafiledb_functions = new pafiledb_functions();

if ($cache->exists('config'))
{
	$pafiledb_config = $cache->get('config');
}
else
{
	$pafiledb_config = $pafiledb_functions->pafiledb_config();
	$cache->put('config', $pafiledb_config);
}

$pafiledb_user = new user_info();
$pafiledb_template = new pafiledb_template();
$pafiledb_template->set_template($theme['template_name']);

$pafiledb = new pafiledb_public();

$template_path = 'templates/';
$template_name = $theme['template_name'];
$current_template_path = $template_path . $template_name;
$current_template_cfg_pa = IP_ROOT_PATH . $template_path . $template_name . '/' . $template_name . '_pa.cfg';
if (file_exists($current_template_cfg_pa))
{
	@include_once($current_template_cfg_pa);
}
else
{
	$current_template_cfg_pa = IP_ROOT_PATH . $template_path . $board_config['xs_def_template'] . '/' . $board_config['xs_def_template'] . '_pa.cfg';
	if (file_exists($current_template_cfg_pa))
	{
		@include_once($current_template_cfg_pa);
	}
}

?>