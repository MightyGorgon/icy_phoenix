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

if(!function_exists('cms_block_gsearch'))
{
	function cms_block_gsearch()
	{
		global $config, $template, $lang, $block_id, $cms_config_vars;

		$gsearch_style = $cms_config_vars['md_gsearch_style'][$block_id];

		$template->assign_vars(array(
			'GSEARCH_BANNER' => $cms_config_vars['md_gsearch_banner'][$block_id],
			'GSEARCH_SITE' => $cms_config_vars['md_gsearch_site'][$block_id],
			'GSEARCH_SITENAME' => htmlspecialchars($config['sitename']),
			'GSEARCH_HOR' => $cms_config_vars['md_gsearch_style'][$block_id],
			'GSEARCH_TEXT' => htmlspecialchars($cms_config_vars['md_gsearch_text'][$block_id]),

			'L_GSEARCH2' => $lang['GSearch2'],
			'L_GSEARCH_AT' => $lang['GSearch_At'],
			'L_ADVANCED_GSEARCH' => $lang['Advanced_GSearch'],
			'L_FORUM_OPTION' => htmlspecialchars($config['sitename'])
			)
		);
	}
}

cms_block_gsearch();

?>