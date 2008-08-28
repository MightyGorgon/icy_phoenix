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

// CTracker_Ignore: File Checked By Human
define('IN_PHPBB', true);
define('IN_LOGIN', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// session id check
if (!empty($_POST['sid']) || !empty($_GET['sid']))
{
	$sid = (!empty($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}

// Get URL vars
$mode         = $_GET['mode'];
$user_id      = $_GET['uid'];

// Ensure that a user is not logged in
if ( $userdata['session_logged_in'] )
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

srand((double)microtime()*1000000);
$rnd = rand(0,count($easter_egg_array)-1);
$easter_egg_link = $easter_egg_array[$rnd];
*/

// Include the page_header
$page_title = $lang['ctracker_login_title'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);


// Define the Template for this file
$template->set_filenames(array('body' => 'ctracker_login.tpl'));


/*
 * Include Visual Confirmation System
 */
if ( $ctracker_config->settings['loginfeature'] == 1 )
{
	define('CRACKER_TRACKER_VCONFIRM', true);
	define('CTRACKER_ACCOUNT_FREE', true);
	include_once( $phpbb_root_path . 'ctracker/engines/ct_visual_confirm.' . $phpEx );
}

// Send some vars to the template
$template->assign_vars(array(
	'CONFIRM_IMAGE'   => $confirm_image,
	'PAGE_ICON'       => $images['ctracker_key_icon'],
	'S_FORM_ACTION'   => append_sid('ctracker_login.' . $phpEx . '?mode=check&uid=' . $user_id),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'L_HEADER_TEXT'   => $lang['ctracker_login_title'],
	'L_DESCRIPTION'   => $lang['ctracker_login_confim'],
	'L_BUTTON_TEXT'   => $lang['ctracker_login_button'],
	//'EASTER_EGG_LINK' => $easter_egg_link
	)
);

// Generate the page
$template->pparse('body');

// Include the page_tail.php file
include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>