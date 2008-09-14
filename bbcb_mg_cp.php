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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$gen_simple_header = true;
$page_title = $lang['bbcb_mg_colorpicker'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
$template->set_filenames(array('body' => 'bbcb_mg_colorpicker.tpl'));
$template->assign_vars(array(
	'L_BBCB_MG_COLOR_PICKER' => $lang['bbcb_mg_colorpicker'],
	)
);
$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>