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
* Bicet (bicets@gmail.com)
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

$plugin_name = 'links';
if (empty($config['plugins'][$plugin_name]['enabled']) || empty($config['plugins'][$plugin_name]['dir']))
{
	message_die(GENERAL_MESSAGE, 'PLUGIN_DISABLED');
}
include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$plugin_name]['dir'] . 'common.' . PHP_EXT);

$links_config = get_links_config(true);

$template->assign_vars(array(
	'L_LINK_US' => $lang['Link_us'] . $config['sitename'],
	'L_LINK_US_EXPLAIN' => sprintf($lang['Link_us_explain'], $config['sitename']),'L_SUBMIT' => $lang['Submit'],
	'L_CLOSE_WINDOW' => $lang['Close_window'],
	'LINK_US_SYNTAX' => str_replace(' ', '&nbsp;', sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $links_config['site_url'], $links_config['site_logo'], $links_config['width'], $links_config['height'], htmlspecialchars(str_replace('"', '', $config['sitename'])))),

	'U_SITE_LOGO' => $links_config['site_logo'],
	)
);

$gen_simple_header = true;
full_page_generation($class_plugins->get_tpl_file(LINKS_TPL_PATH, 'links_me.tpl'), $lang['Link_ME'], '', '');

?>