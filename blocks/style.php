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

if(!function_exists('cms_block_style'))
{
	function cms_block_style()
	{
		global $db, $cache, $config, $template, $images, $user, $lang, $block_id, $cms_config_vars, $cms_config_layouts, $cms_page;
		global $style_select, $default_style;

		$default_style = $config['default_style'];
		$select_name = STYLE_URL;
		$style_select = '<select name="' . $select_name . '" onchange="SetTheme();" class="gensmall">';
		$styles = $cache->obtain_styles(true);
		foreach ($styles as $k => $v)
		{
			$selected = ($k == $default_style) ? ' selected="selected"' : '';
			$style_select .= '<option value="' . $k . '"' . $selected . '>' . htmlspecialchars($v) . '</option>';
		}
		$style_select .= '</select>';

		$template->assign_vars(array(
			'STYLE_SELECT_H' => $style_select
			)
		);
	}
}

cms_block_style();

?>