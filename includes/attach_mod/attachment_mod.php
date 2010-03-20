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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

// We assign the original default board language here, because it gets overwritten later with the users default language
$config['board_lang'] = trim($config['default_lang']);

// Needed to correctly process attachments!
define('PAGE_PRIVMSGS', -10);

include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/constants.' . PHP_EXT);
include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_attach.' . PHP_EXT);
include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_filetypes.' . PHP_EXT);
if(defined('IN_DOWNLOAD') || defined('IN_ADMIN') || defined('ATTACH_DISPLAY') || defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_includes.' . PHP_EXT);
}
if(defined('IN_DOWNLOAD') || defined('IN_ADMIN') || defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_posting.' . PHP_EXT);
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_delete.' . PHP_EXT);
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_thumbs.' . PHP_EXT);
}
if(defined('IN_ADMIN'))
{
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_admin.' . PHP_EXT);
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_selects.' . PHP_EXT);
}
if(defined('ATTACH_PROFILE'))
{
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_profile.' . PHP_EXT);
}

// Please do not change the include-order, it is valuable for proper execution.
// Functions for displaying Attachment Things
if(defined('IN_DOWNLOAD') || defined('ATTACH_DISPLAY') || defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'displaying.' . PHP_EXT);
}

// Posting Attachments Class (HAS TO BE BEFORE PM)
if(defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'posting_attachments.' . PHP_EXT);
}

if(defined('ATTACH_PM'))
{
	// PM Attachments Class
	include(IP_ROOT_PATH . ATTACH_MOD_PATH . 'pm_attachments.' . PHP_EXT);
}
/*
*/

if (!intval($config['allow_ftp_upload']))
{
	$upload_dir = $config['upload_dir'];
}
else
{
	$upload_dir = $config['download_path'];
}

?>