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
* Vic D'Elfant (vic@pythago.nl)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['170_LIW'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Attempt to get the configuration values
$sql = "SELECT config_name, config_value FROM " . CONFIG_TABLE . " WHERE config_name LIKE 'liw_%' OR config_name = 'liw_max_width'";
$result = $db->sql_query($sql);

while ($record = $db->sql_fetchrow())
{
	$default_config[$record['config_name']] = $record['config_value'];
}

// Let's try to get the number of rows in phpbb_liw_cache
$sql = "SELECT COUNT(image_checksum) AS rowcount FROM " . LIW_CACHE_TABLE;
$result = $db->sql_query($sql);
$record = $db->sql_fetchrow($result);
$cache_rowcount = $record['rowcount'];


// Get the size of the phpbb_liw_cache table
$sql = "SELECT VERSION() AS mysql_version";
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if ($result)
{
	$record = $db->sql_fetchrow($result);
	if(preg_match("/^(3\.23|4\.)/", $record['mysql_version']))
	{
		$sql = "SHOW TABLE STATUS FROM " . $dbname;
		$result = $db->sql_query($sql);

		while ($record = $db->sql_fetchrow($result))
		{
			if ($record['Name'] == LIW_CACHE_TABLE)
			{
				$cache_rowcount .= ' (' . sprintf("%.2f KB", ($record['Data_length'] / 1024)) . ')';
			}
		}
	}
}

// If the form has been submitted update the config values
if (isset($_POST['submit']))
{
	$config_fields = array('liw_enabled', 'liw_sig_enabled', 'liw_attach_enabled', 'liw_max_width', 'liw_max_width');
	reset($config_fields);

	while (list($temp, $config_name) = each($config_fields))
	{
		$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = " . intval($_POST[$config_name]) . " WHERE config_name = '" . $config_name . "'";
		$db->sql_query($sql);
	}

	$message = $lang['LIW_config_updated'] . '<br /><br />' . sprintf($lang['LIW_click_return_config'], '<a href="' . append_sid('admin_liw.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}


//
// Are we going to empty the cache table?
//
if (isset($_POST['empty_cache']))
{
	$sql = "DELETE FROM " . LIW_CACHE_TABLE;
	$db->sql_query($sql);

	$message = $lang['LIW_cache_emptied'] . '<br /><br />' . sprintf($lang['LIW_click_return_config'], '<a href="' . append_sid('admin_liw.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}


$template->set_filenames(array(
	'body' => ADM_TPL . 'liw_body.tpl')
);

//
// Do compatibility checks...
//

// getimagesize() URL support
if (@phpversion() >= '4.0.5')
{
	$comp_getimagesize_status = '<span class="text_green">' . $lang['Available'] . '</span>';
	$comp_getimagesize_text = $lang['LIW_getimagesize_available'];
}
else
{
	$comp_getimagesize_status = '<span class="text_red">' . $lang['Unavailable'] . '</span>';
	$comp_getimagesize_text = $lang['LIW_getimagesize_unavailable'];
}

// fopen URL wrappers
$ini_val = (phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

if ($ini_val('allow_url_fopen'))
{
	$comp_urlaware_status = '<span class="text_green">' . $lang['Available'] . '</span>';
	$comp_urlaware_text = $lang['LIW_urlaware_available'];
}
else
{
	$comp_urlaware_status = '<span class="text_red">' . $lang['Unavailable'] . '</span>';
	$comp_urlaware_text = $lang['LIW_urlaware_unavailable'];
}

// openSSL extension
if (@extension_loaded('openssl'))
{
	$comp_openssl_status = '<span class="text_green">' . $lang['Available'] . '</span>';
	$comp_openssl_text = $lang['LIW_openssl_available'];
}
else
{
	$comp_openssl_status = '<span class="text_red">' . $lang['Unavailable'] . '</span>';
	$comp_openssl_text = $lang['LIW_openssl_unavailable'];
}

if (isset($default_config['liw_attach_enabled']))
{
	$template->assign_block_vars('switch_attach_mod_installed', array());
}


$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_liw.' . PHP_EXT),

	// Configuration
	'S_ENABLED_YES' => (($default_config['liw_enabled']) ? 'checked="checked"' : ''),
	'S_ENABLED_NO' => (($default_config['liw_enabled']) ? '' : 'checked="checked"'),
	'S_SIG_ENABLED_YES' => (($default_config['liw_sig_enabled']) ? 'checked="checked"' : ''),
	'S_SIG_ENABLED_NO' => (($default_config['liw_sig_enabled']) ? '' : 'checked="checked"'),
	'S_ATTACH_ENABLED_YES' => (($default_config['liw_attach_enabled']) ? 'checked="checked"' : ''),
	'S_ATTACH_ENABLED_NO' => (($default_config['liw_attach_enabled']) ? '' : 'checked="checked"'),

	'MAX_IMG_WIDTH' => $default_config['liw_max_width'],

	// Compatibility check
	'L_COMP_GETIMAGESIZE_STATUS' => $comp_getimagesize_status,
	'L_COMP_GETIMAGESIZE_TEXT' => $comp_getimagesize_text,
	'L_COMP_URLAWARE_STATUS' => $comp_urlaware_status,
	'L_COMP_URLAWARE_TEXT' => $comp_urlaware_text,
	'L_COMP_OPENSSL_STATUS' => $comp_openssl_status,
	'L_COMP_OPENSSL_TEXT' => $comp_openssl_text,

	// Common
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_EXPLAIN' => $lang['LIW_admin_explain'],
	'L_COMPATIBILITY_CHECKS' => $lang['LIW_compatibility_checks'],
	'L_MOD_CONFIG' => $lang['LIW_mod_config'],
	'L_ENABLE' => $lang['LIW_enable'],
	'L_ENABLE_EXPLAIN' => sprintf($lang['LIW_enable_explain'], $lang['Yes']),
	'L_SIG_ENABLE' => $lang['LIW_sig_enable'],
	'L_SIG_ENABLE_EXPLAIN' => sprintf($lang['LIW_sig_enable_explain'], $lang['Yes']),
	'L_ATTACH_ENABLE' => $lang['LIW_attach_enable'],
	'L_ATTACH_ENABLE_EXPLAIN' => sprintf($lang['LIW_attach_enable_explain'], $lang['Yes']),
	'L_MAX_WIDTH' => $lang['LIW_max_width'],
	'L_MAX_WIDTH_EXPLAIN' => $lang['LIW_max_width_explain'],
	'L_EMPTY_CACHE' => $lang['LIW_empty_cache'],
	'L_EMPTY_CACHE_EXPLAIN' => sprintf($lang['LIW_empty_cache_explain'], $cache_rowcount),
	'L_EMPTY_CACHE_NOTE' => $lang['LIW_empty_cache_note'],
	'L_EMPTY_CACHE_BUTTON' => $lang['LIW_empty_cache_button'],

	'L_GETIMAGESIZE' => $lang['LIW_getimagesize'],
	'L_GETIMAGESIZE_EXPLAIN' => $lang['LIW_getimagesize_explain'],
	'L_URLAWARE' => $lang['LIW_urlaware'],
	'L_URLAWARE_EXPLAIN' => $lang['LIW_urlaware_explain'],
	'L_OPENSSL' => $lang['LIW_openssl'],
	'L_OPENSSL_EXPLAIN' => $lang['LIW_openssl_explain'])
);

$template->pparse('body');

include('page_footer_admin.' . PHP_EXT);

?>