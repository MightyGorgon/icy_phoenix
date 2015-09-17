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

if(!function_exists('cms_block_paypal'))
{
	function cms_block_paypal()
	{
		global $template, $lang;

		$template->assign_vars(array(
			'PAYPAL_CODE' => $lang['PayPal'],
			'L_SUPPORT_US' => $lang['Support_Us'],
			)
		);
	}
}

cms_block_paypal();

?>