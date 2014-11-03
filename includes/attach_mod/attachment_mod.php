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

// Set this to false if you want to use subfolders for images and remove the basename feature which doesn't allow subfolders
define('ATTACHMENT_MOD_BASENAME', true);


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

$upload_dir = get_upload_dir(false);

?>