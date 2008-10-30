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
* Javier B (kinfule@lycos.es)
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('MG_CTRACK_FLAG', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip, false);
init_userprefs($userdata);
// End session management

$cms_page_id = '0';
$cms_page_name = 'ajax_chat';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;
// I would shut wide blocks off since this may be run as stand alone
$cms_global_blocks = false;

$shoutbox_template_parse = true;
include(IP_ROOT_PATH . 'includes/ajax_shoutbox_inc.' . PHP_EXT);

?>