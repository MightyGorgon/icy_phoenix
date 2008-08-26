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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

$userdata = session_pagestart($user_ip);
init_userprefs($userdata);

$help_key = (isset($_GET['help_key'])) ? $_GET['help_key'] : '';

include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_downloads.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_dl_help.' . $phpEx);

$gen_simple_header = true;
$page_title = $lang['HELP_TITLE'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

$template->set_filenames(array('help_body' => 'dl_help_body.tpl'));

// Pull all user config data
if ($help_key && $lang['HELP_' . $help_key])
{
	$help_string = $lang['HELP_' . $help_key];
}
else
{
	$help_string = $lang['Dl_no_help_aviable'];
}

$template->assign_vars(array(
	'L_CLOSE' => $lang['Close_window'],
	'HELP_TITLE' => $lang['HELP_TITLE'],
	'HELP_OPTION' => $lang[$help_key],
	'HELP_STRING' => $help_string
	)
);

$template->pparse('help_body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>