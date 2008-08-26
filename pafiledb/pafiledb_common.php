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

if ( !defined('IN_PHPBB') )
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

//===================================================
// to make it work with php version under 4.1 and other stuff
//===================================================
if ( @phpversion() < '4.1' )
{
	$_GET = &$_GET;
	$_POST = &$_POST;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$_SERVER;
	$_ENV = &$_ENV;
	$_FILES = &$HTTP_POST_FILES;
	$_SESSION = &$HTTP_SESSION_VARS;
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

if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_pafiledb.' . $phpEx) )
{
	$language = 'english';
}

if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_pafiledb.' . $phpEx) )
{
	$language = 'english';
}

include($phpbb_root_path . 'language/lang_' . $language . '/lang_pafiledb.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_pafiledb.' . $phpEx);

//===================================================
// Include pafiledb data file
//===================================================
include($phpbb_root_path . PA_FILE_DB_PATH . 'includes/pafiledb_constants.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'includes/functions_cache.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'includes/functions.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'includes/template.' . $phpEx);
include($phpbb_root_path . PA_FILE_DB_PATH . 'includes/functions_pafiledb.' . $phpEx);

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
$current_template_cfg_pa = $phpbb_root_path . $template_path . $template_name . '/' . $template_name . '_pa.cfg';
if (file_exists($current_template_cfg_pa))
{
	@include_once($current_template_cfg_pa);
}

?>