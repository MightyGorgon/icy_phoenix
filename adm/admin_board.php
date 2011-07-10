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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['110_Various_Configuration'] = $file;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

// Pull all config data
$tmp_config = array();
$tmp_config = get_config(false);
foreach ($tmp_config as $k => $v)
{
	$default_config[$k] = $v;
	$tmp_value = request_post_var($k, '', true);
	$new[$k] = isset($_POST[$k]) ? $tmp_value : $default_config[$k];
	$new[$k] = fix_config_values($k, $new[$k]);

	if(isset($_POST['submit']) && isset($_POST[$k]))
	{
		set_config($k, $new[$k], false);
	}
}

if(isset($_POST['submit']))
{
	$cache->destroy('config');

	$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_board.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$switch_header_table_yes = ($new['switch_header_table']) ? 'checked="checked"' : '';
$switch_header_table_no = (!$new['switch_header_table']) ? 'checked="checked"' : '';

// Ajax Shoutbox - BEGIN
$shoutguest_yes = ($new['shout_allow_guest'] == 1) ? 'checked="checked"' : '';
$shoutguest_read = ($new['shout_allow_guest'] == 2) ? 'checked="checked"' : '';
$shoutguest_no = ($new['shout_allow_guest'] == 0) ? 'checked="checked"' : '';
// Ajax Shoutbox - END

$template->set_filenames(array('body' => ADM_TPL . 'board_config_body.tpl'));

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board.' . PHP_EXT),

	'L_HEADER_FOOTER' => $lang['MG_SW_Header_Footer'],
	'L_HEADER_TABLE_SWITCH' => $lang['MG_SW_Header_Table'],
	'L_HEADER_TABLE_SWITCH_EXPLAIN' =>$lang['MG_SW_Header_Table_Explain'],
	'L_HEADER_TABLE_TEXT' =>$lang['MG_SW_Header_Table_Text'],

	'HEADER_TBL_YES' => $switch_header_table_yes,
	'HEADER_TBL_NO' => $switch_header_table_no,
	'HEADER_TBL_TXT' => htmlspecialchars($new['header_table_text']),

	'L_CONFIGURATION_TITLE' => $lang['General_Config'],
	'L_CONFIGURATION_EXPLAIN' => $lang['Config_explain'],
	'L_GENERAL_SETTINGS' => $lang['General_settings'],

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],

	'L_ENABLED' => $lang['Enabled'],
	'L_DISABLED' => $lang['Disabled'],

	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	// Ajax Shoutbox - BEGIN
	'L_SHOUTBOX_CONFIG' => $lang['Shoutbox_config'],
	'L_DISPLAYED_SHOUTS' => $lang['Displayed_shouts'],
	'L_DISPLAYED_SHOUTS_EXPLAIN' => $lang['Displayed_shouts_explain'],
	'L_STORED_SHOUTS' => $lang['Stored_shouts'],
	'L_STORED_SHOUTS_EXPLAIN' => $lang['Stored_shouts_explain'],
	'L_SHOUTBOX_FLOOD' => $lang['Shoutbox_flood'],
	'L_SHOUTBOX_FLOOD_EXPLAIN' => $lang['Shoutbox_flood_explain'],
	'L_SHOUT_GUEST_READONLY' => $lang['Shout_read_only'],
	'L_GUEST_ALLOWED' => $lang['Shout_guest_allowed'],
	'L_SHOUTBOX_REFRESHTIME' => $lang['Shoutbox_refreshtime'],
	'L_SHOUTBOX_REFRESH_EXPLAIN' => $lang['Shoutbox_refresh_explain'],
	'DISPLAYED_SHOUTS' => $new['display_shouts'],
	'STORED_SHOUTS' => $new['stored_shouts'],
	'SHOUTBOX_FLOODINTERVAL' => $new['shoutbox_floodinterval'],
	'SHOUT_GUEST_YES' => $shoutguest_yes,
	'SHOUT_GUEST_READONLY' => $shoutguest_read,
	'SHOUT_GUEST_NO' => $shoutguest_no,
	'SHOUTBOX_REFRESHTIME' => $new['shoutbox_refreshtime'],
	// Ajax Shoutbox - END
	)
);
include(IP_ROOT_PATH . ADM . '/bb_usage_stats_admin.' . PHP_EXT);
$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>