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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
	exit;
}

// Mighty Gorgon: new attachments cache.
$sql = "SELECT * FROM " . ATTACH_CONFIG_TABLE;
if (!($result = $db->sql_query($sql, false, 'attach_')))
{
	message_die(GENERAL_ERROR, 'Could not query attachment information', '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$attach_config[$row['config_name']] = trim($row['config_value']);
}

// We assign the original default board language here, because it gets overwritten later with the users default language
$attach_config['board_lang'] = trim($board_config['default_lang']);

include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/constants.' . $phpEx);
include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_attach.' . $phpEx);
include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_filetypes.' . $phpEx);
if(defined('IN_DOWNLOAD') || defined('IN_ADMIN') || defined('ATTACH_DISPLAY') || defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_includes.' . $phpEx);
}
if(defined('IN_DOWNLOAD') || defined('IN_ADMIN') || defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_posting.' . $phpEx);
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_delete.' . $phpEx);
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_thumbs.' . $phpEx);
}
if(defined('IN_ADMIN'))
{
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_selects.' . $phpEx);
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_admin.' . $phpEx);
}
if(defined('ATTACH_PROFILE'))
{
	include($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_profile.' . $phpEx);
}

// Please do not change the include-order, it is valuable for proper execution.
// Functions for displaying Attachment Things
if(defined('IN_DOWNLOAD') || defined('ATTACH_DISPLAY') || defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include($phpbb_root_path . ATTACH_MOD_PATH . 'displaying.' . $phpEx);
}

// Posting Attachments Class (HAS TO BE BEFORE PM)
if(defined('ATTACH_PM') || defined('ATTACH_POSTING'))
{
	include($phpbb_root_path . ATTACH_MOD_PATH . 'posting_attachments.' . $phpEx);
}

if(defined('ATTACH_PM'))
{
	// PM Attachments Class
	include($phpbb_root_path . ATTACH_MOD_PATH . 'pm_attachments.' . $phpEx);
}
/*
*/

if (!intval($attach_config['allow_ftp_upload']))
{
	$upload_dir = $attach_config['upload_dir'];
}
else
{
	$upload_dir = $attach_config['download_path'];
}

?>