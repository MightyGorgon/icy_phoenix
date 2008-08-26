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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_ajax_shoutbox_block_func))
{
	function imp_ajax_shoutbox_block_func()
	{
		global $phpbb_root_path, $phpEx, $template, $images, $lang, $board_config, $userdata, $db;
		$shoutbox_template_parse = false;
		if(($board_config['shout_allow_guest'] <= 0) && !($userdata['session_logged_in']))
		{
			//include($phpbb_root_path . 'ajax_shoutbox.' . $phpEx);
		}
		else
		{
			include($phpbb_root_path . 'includes/ajax_shoutbox_inc.' . $phpEx);
		}
	}
}

imp_ajax_shoutbox_block_func();

?>