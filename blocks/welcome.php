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

if(!function_exists('cms_block_welcome'))
{
	function cms_block_welcome()
	{
		global $template, $lang;
		$template->assign_vars(array(
			'L_WELCOME_MESSAGE' => $lang['Welcome_Message'],
			)
		);
	}
}

cms_block_welcome();

?>