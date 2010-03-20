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

/*
if (!isset($_REQUEST))
{
	$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
}

if (!get_magic_quotes_gpc())
{
	$_GET = slash_data($_GET);
	$_POST = slash_data($_POST);
	$_COOKIE = slash_data($_COOKIE);
	$_REQUEST = slash_data($_REQUEST);
}
*/

//===================================================
// Get Language
//===================================================
$language = $config['default_lang'];

include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_pafiledb.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_admin_pafiledb.' . PHP_EXT);

//===================================================
// Include pafiledb data file
//===================================================
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'pafiledb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_pafiledb.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'class_custom_fields.' . PHP_EXT);
if (defined('IN_ADMIN'))
{
	include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_pafiledb_admin.' . PHP_EXT);
}

$pafiledb_functions = new pafiledb_functions();

$pafiledb_config = $pafiledb_functions->obtain_pafiledb_config();

$pafiledb_user = new user_info();

$pafiledb = new pafiledb_public();

?>