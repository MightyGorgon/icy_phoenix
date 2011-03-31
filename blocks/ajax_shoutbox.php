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

if(!function_exists('cms_block_ajax_shoutbox'))
{
	function cms_block_ajax_shoutbox()
	{
		global $db, $cache, $config, $template, $theme, $images, $user, $lang, $table_prefix, $bbcode, $block_id, $cms_config_vars, $cms_config_layouts, $cms_page;
		$shoutbox_template_parse = false;
		if(($config['shout_allow_guest'] <= 0) && !$user->data['session_logged_in'])
		{
			//include(IP_ROOT_PATH . 'ajax_shoutbox.' . PHP_EXT);
		}
		else
		{
			include(IP_ROOT_PATH . 'includes/ajax_shoutbox_inc.' . PHP_EXT);
		}
	}
}

cms_block_ajax_shoutbox();

?>