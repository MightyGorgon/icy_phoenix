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
* This PHP File is used for the Visual Confirmation Page if a user has to
* reactivate his/her Account. Its in my opinion easyer to use this central
* site to do this because the previous Version of CrackerTracker showed that
* many people have problems to edit the login.php and the login_body.tpl
* correctly with the Switch. So to solve Problems with login we use this
* stand alone page wich we can also easy update if the Visual Confirmation
* System would change.
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 24.07.2006 - 19:33:16
* @copyright (c) 2006 www.cback.de
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/*
 * We say we're the login page that the Admin has the possibility to
 * reactivate his account again if it should be deactivated on disabled
 * Board.
 */

define('IN_LOGIN', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$sid = request_var('sid', '');
$mode = request_var('mode', '');
$user_id = request_var('uid', 0);

// Ensure that a user is not logged in
if ($user->data['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['ctracker_login_logged']);
}

/*
* Just a little easter egg
* Do you know the film "The Net"? Well but don't panic, our PI-Symbol can
* not bring world domination or backdoors - but IMO its a nice joke. ;-)
*/
/*
$easter_egg_link  = '';
$easter_egg_array = array('http://www.abcp.de', 'http://www.cback.de', 'http://www.german-garrison.de', 'http://www.501st.com', 'http://www.cback.net', 'http://www.google.de', 'http://www.oxpus.de');

srand((double)microtime() * 1000000);
$rnd = rand(0, sizeof($easter_egg_array) - 1);
$easter_egg_link = $easter_egg_array[$rnd];
*/

/*
* Include Visual Confirmation System
*/
define('CRACKER_TRACKER_VCONFIRM', true);
define('CTRACKER_ACCOUNT_FREE', true);
include_once(IP_ROOT_PATH . 'includes/ctracker/engines/ct_visual_confirm.' . PHP_EXT);

// Send some vars to the template
$template->assign_vars(array(
	'CONFIRM_IMAGE' => $confirm_image,
	'PAGE_ICON' => $images['ctracker_key_icon'],
	'S_FORM_ACTION' => append_sid('login_captcha.' . PHP_EXT . '?mode=check&amp;uid=' . $user_id),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'L_HEADER_TEXT' => $lang['ctracker_login_title'],
	'L_DESCRIPTION' => $lang['ctracker_login_confim'],
	'L_BUTTON_TEXT' => $lang['ctracker_login_button'],
	//'EASTER_EGG_LINK' => $easter_egg_link
	)
);

full_page_generation('login_captcha.tpl', $lang['ctracker_login_title'], '', '');

?>