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

if(!function_exists(imp_shoutbox_block_func))
{
	function imp_shoutbox_block_func()
	{
		global $template, $phpEx;

		$template->assign_vars(array(
			'U_SHOUTBOX' => append_sid('shoutbox.' . $phpEx),
			)
		);
	}
}

imp_shoutbox_block_func();

?>