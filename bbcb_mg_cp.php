<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$gen_simple_header = true;
$page_title = $lang['bbcb_mg_colorpicker'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);
$template->set_filenames(array('body' => 'bbcb_mg_colorpicker.tpl'));
$template->assign_vars(array(
	'L_BBCB_MG_COLOR_PICKER' => $lang['bbcb_mg_colorpicker'],
	)
);
$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>