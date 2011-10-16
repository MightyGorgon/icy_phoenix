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
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$confirm = ($_POST['confirm'] ? true : false);

if (isset($_POST['cancel']))
{
	redirect(append_sid('index.' . PHP_EXT));
}

if ($confirm)
{
	$cookies_array = array('u', 'k', 'sid', 'f_all', 'f', 't');
	foreach ($cookies_array as $cookie_name)
	{
		$user->set_cookie($cookie_name, '', time() - 3600);
	}

	message_die(GENERAL_MESSAGE, $lang['Cookies_deleted']);
}
else
{
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
	full_page_generation('confirm_body.tpl', $lang['Delete_cookies'], '', '');
}

?>