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
* geocator (geocator@gmail.com)
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

$confirm = ( $_POST['confirm'] ) ? true : 0;

if ( isset($_POST['cancel']) )
{
	redirect(append_sid('index.' . PHP_EXT));
}

if ($confirm)
{
	setcookie($board_config['cookie_name'] . '_sid', $session_id, - 3600, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);

	setcookie($board_config['cookie_name'] . '_f_all', time(), - 3600, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);

	setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), - 3600, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);

	setcookie($board_config['cookie_name'] . '_f', serialize($tracking_forums), - 3600, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);

	setcookie($board_config['cookie_name'] . '_data', serialize($sessiondata), - 3600, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);

	message_die(GENERAL_MESSAGE, $lang['Cookies_deleted']);
}
else
{
	$page_title = $lang['Delete_cookies'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	// Not confirmed, show confirmation message
	$template->set_filenames(array('confirm' => 'confirm_body.tpl'));
	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['cookies_confirm'],
		'L_YES' =>  $lang['Yes'],
		'L_NO' => $lang['No'],
		'S_CONFIRM_ACTION' => append_sid('remove_cookies.' . PHP_EXT)
		)
	);

	$template->pparse('confirm');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}

?>