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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Parse and show the overall footer.

$template->set_filenames( array( 'kb_footer' => 'kb_footer.tpl' ) );

if ( !empty($kb_auth_can) )
{
	$template->assign_block_vars( 'auth_can', array() );
}

if ( !empty($kb_quick_nav) )
{
	$template->assign_block_vars( 'quick_nav', array() );
}

$template->assign_block_vars('copy_footer', array());

$quick_nav_action = this_kb_mxurl();
$s_hidden_vars = '<input type="hidden" name="mode" value="cat"><input type="hidden" name="page" value="' . $page_id . '">';

$template->assign_vars( array(
		'QUICK_JUMP_ACTION' => $quick_nav_action,
		'S_HIDDEN_VARS' => $s_hidden_vars,
		//'SID' => $userdata['session_id'],

		'L_QUICK_NAV' => $lang['Quick_nav'],
		'L_QUICK_JUMP' => $lang['Quick_jump'],
		'QUICK_NAV' => $kb_quick_nav,

		'S_AUTH_LIST' => $kb_auth_can,

		'L_MODULE_VERSION' => $kb_module_version,
		'L_MODULE_ORIG_AUTHOR' => $kb_module_orig_author,
		'L_MODULE_AUTHOR' => $kb_module_author
		)
	);

$template->pparse('kb_footer');

?>