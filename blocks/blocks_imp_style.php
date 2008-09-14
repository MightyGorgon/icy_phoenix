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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_style_block_func))
{
	function imp_style_block_func()
	{
			global $template, $db, $board_config, $lang, $images, $userdata;
			global $head_foot_ext, $cms_global_blocks, $cms_page_id, $cms_config_vars, $block_id;
			global $style_select, $default_style;

			$default_style = $board_config['default_style'];
			$select_name = STYLE_URL;
			$sql = "SELECT themes_id, style_name
							FROM " . THEMES_TABLE . "
							ORDER BY template_name, themes_id";
			if (!($result = $db->sql_query($sql, false, 'themes_')))
			{
				message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
			}
			$style_select = '<select name="' . $select_name . '" onchange="SetTheme();" class="gensmall">';
			while ($row = $db->sql_fetchrow($result))
			{
				$selected = ($row['themes_id'] == $default_style) ? ' selected="selected"' : '';
				$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . $row['style_name'] . '</option>';
			}
			$style_select .= '</select>';

			$template->assign_vars(array(
				'STYLE_SELECT_H' => $style_select
				)
			);
	}
}

imp_style_block_func();

?>