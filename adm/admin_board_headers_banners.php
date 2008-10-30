<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['140_MG_Configuration_Headers_Banners'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
$db->clear_cache('config_');

// Pull all config data
$sql = "SELECT * FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
	while($row = $db->sql_fetchrow($result))
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;

		$new[$config_name] = (isset($_POST[$config_name])) ? $_POST[$config_name] : $default_config[$config_name];

		if(isset($_POST['submit']) && isset($_POST[$config_name]))
		{
			if (strstr($new[$config_name], '_text'))
			{
				$new[$config_name] = addslashes($new[$config_name]);
			}
			$sql = "UPDATE " . CONFIG_TABLE . " SET
				config_value = '" . $new[$config_name] . "'
				WHERE config_name = '$config_name'";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if(isset($_POST['submit']))
	{
		$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config_mg'], '<a href="' . append_sid('admin_board_headers_banners.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}


$switch_top_html_block_yes = ($new['switch_top_html_block']) ? 'checked="checked"' : '';
$switch_top_html_block_no = (!$new['switch_top_html_block']) ? 'checked="checked"' : '';
$switch_bottom_html_block_yes = ($new['switch_bottom_html_block']) ? 'checked="checked"' : '';
$switch_bottom_html_block_no = (!$new['switch_bottom_html_block']) ? 'checked="checked"' : '';

$switch_header_table_yes = ($new['switch_header_table']) ? 'checked="checked"' : '';
$switch_header_table_no = (!$new['switch_header_table']) ? 'checked="checked"' : '';
$switch_footer_table_yes = ($new['switch_footer_table']) ? 'checked="checked"' : '';
$switch_footer_table_no = (!$new['switch_footer_table']) ? 'checked="checked"' : '';

$switch_header_banner_yes = ($new['switch_header_banner']) ? 'checked="checked"' : '';
$switch_header_banner_no = (!$new['switch_header_banner']) ? 'checked="checked"' : '';
$switch_viewtopic_banner_yes = ($new['switch_viewtopic_banner']) ? 'checked="checked"' : '';
$switch_viewtopic_banner_no = (!$new['switch_viewtopic_banner']) ? 'checked="checked"' : '';

$new['top_html_block_text'] = stripslashes($new['top_html_block_text']);
$new['bottom_html_block_text'] = stripslashes($new['bottom_html_block_text']);
$new['header_table_text'] = stripslashes($new['header_table_text']);
$new['footer_table_text'] = stripslashes($new['footer_table_text']);
$new['header_banner_text'] = stripslashes($new['header_banner_text']);
$new['viewtopic_banner_text'] = stripslashes($new['viewtopic_banner_text']);

$template->set_filenames(array('body' => ADM_TPL . 'board_config_headers_banners.tpl'));

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board_headers_banners.' . PHP_EXT),

	'L_CONFIGURATION_TITLE' => $lang['MG_Configuration'],
	'L_CONFIGURATION_EXPLAIN' => $lang['MG_Configuration_Explain'],
	'L_GENERAL_SETTINGS' => $lang['General_settings'],

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_ENABLED' => $lang['Enabled'],
	'L_DISABLED' => $lang['Disabled'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_TOP_HEADER_BOTTOM_FOOTER' => $lang['MG_SW_Top_Bottom_HTML_Block'],
	'L_TOP_HTML_BLOCK_SWITCH' => $lang['MG_SW_Top_HTML_Block'],
	'L_TOP_HTML_BLOCK_SWITCH_EXPLAIN' =>$lang['MG_SW_Top_HTML_Block_Explain'],
	'L_TOP_HTML_BLOCK_TEXT' =>$lang['MG_SW_Top_HTML_Block_Text'],
	'L_BOTTOM_HTML_BLOCK_SWITCH' => $lang['MG_SW_Bottom_HTML_Block'],
	'L_BOTTOM_HTML_BLOCK_SWITCH_EXPLAIN' =>$lang['MG_SW_Bottom_HTML_Block_Explain'],
	'L_BOTTOM_HTML_BLOCK_TEXT' =>$lang['MG_SW_Bottom_HTML_Block_Text'],

	'L_HEADER_FOOTER' => $lang['MG_SW_Header_Footer'],
	'L_HEADER_TABLE_SWITCH' => $lang['MG_SW_Header_Table'],
	'L_HEADER_TABLE_SWITCH_EXPLAIN' =>$lang['MG_SW_Header_Table_Explain'],
	'L_HEADER_TABLE_TEXT' =>$lang['MG_SW_Header_Table_Text'],
	'L_FOOTER_TABLE_SWITCH' => $lang['MG_SW_Footer_Table'],
	'L_FOOTER_TABLE_SWITCH_EXPLAIN' =>$lang['MG_SW_Footer_Table_Explain'],
	'L_FOOTER_TABLE_TEXT' =>$lang['MG_SW_Footer_Table_Text'],

	'L_BANNER_TITLE' => $lang['MG_SW_Banner_Title'],
	'L_BANNER_HEADER' => $lang['MG_SW_Header_Banner'],
	'L_BANNER_HEADER_EXPLAIN' => $lang['MG_SW_Header_Banner_Explain'],
	'L_BANNER_HEADER_CODE' => $lang['MG_SW_Header_Banner_Code'],
	'L_BANNER_HEADER_CODE_EXPLAIN' => $lang['MG_SW_Header_Banner_Code_Explain'],
	'L_BANNER_VIEWTOPIC' => $lang['MG_SW_Viewtopic_Banner'],
	'L_BANNER_VIEWTOPIC_EXPLAIN' => $lang['MG_SW_Viewtopic_Banner_Explain'],
	'L_BANNER_VIEWTOPIC_CODE' => $lang['MG_SW_Viewtopic_Banner_Code'],
	'L_BANNER_VIEWTOPIC_CODE_EXPLAIN' => $lang['MG_SW_Viewtopic_Banner_Code_Explain'],

	'TOP_HTML_BLOCK_YES' => $switch_top_html_block_yes,
	'TOP_HTML_BLOCK_NO' => $switch_top_html_block_no,
	'TOP_HTML_BLOCK_TXT' => $new['top_html_block_text'],
	'BOTTOM_HTML_BLOCK_YES' => $switch_bottom_html_block_yes,
	'BOTTOM_HTML_BLOCK_NO' => $switch_bottom_html_block_no,
	'BOTTOM_HTML_BLOCK_TXT' => $new['bottom_html_block_text'],

	'HEADER_TBL_YES' => $switch_header_table_yes,
	'HEADER_TBL_NO' => $switch_header_table_no,
	'HEADER_TBL_TXT' => $new['header_table_text'],
	'FOOTER_TBL_YES' => $switch_footer_table_yes,
	'FOOTER_TBL_NO' => $switch_footer_table_no,
	'FOOTER_TBL_TXT' => $new['footer_table_text'],

	'HEADER_BANNER_YES' => $switch_header_banner_yes,
	'HEADER_BANNER_NO' => $switch_header_banner_no,
	'HEADER_BANNER_CODE' => $new['header_banner_text'],
	'VIEWTOPIC_BANNER_YES' => $switch_viewtopic_banner_yes,
	'VIEWTOPIC_BANNER_NO' => $switch_viewtopic_banner_no,
	'VIEWTOPIC_BANNER_CODE' => $new['viewtopic_banner_text'],
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>