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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

$userdata = session_pagestart($user_ip);
init_userprefs($userdata);

$help_key = (isset($_GET['help_key'])) ? $_GET['help_key'] : '';

include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_downloads.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_dl_help.' . PHP_EXT);

$gen_simple_header = true;
$page_title = $lang['HELP_TITLE'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

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

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>