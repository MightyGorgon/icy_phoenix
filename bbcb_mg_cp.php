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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$bbcb_mg_cp_form = request_var('cpf', 'post');
$bbcb_mg_cp_object = request_var('cpo', 'message');
$bbcb_mg_cp_tag = request_var('cpt', 'color');
$bbcb_mg_cp_is_ext = request_var('cpie', true);
$bbcb_mg_cp_is_plain = request_var('cpip', false);

$template->assign_vars(array(
	'BBCB_MG_CP_FORM' => $bbcb_mg_cp_form,
	'BBCB_MG_CP_OBJECT' => $bbcb_mg_cp_object,
	'BBCB_MG_CP_TAG' => $bbcb_mg_cp_tag,
	'BBCB_MG_CP_IS_EXT' => $bbcb_mg_cp_is_ext,
	'BBCB_MG_CP_IS_PLAIN' => $bbcb_mg_cp_is_plain,
	)
);

$gen_simple_header = true;
full_page_generation('bbcb_mg_colorpicker.tpl', $lang['bbcb_mg_colorpicker'], '', '');

?>