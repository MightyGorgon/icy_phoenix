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
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* Leave a Message to all your users.
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if (!defined('IN_ICYPHOENIX') || !defined('CTRACKER_ACP'))
{
	die('Hacking attempt!');
}

/*
* Output the page
*/
$template->set_filenames(array('ct_body' => ADM_TPL . 'acp_globalmessage.tpl'));

/*
* If site was submitted we update the configuration
*/
if (isset($_POST['submit']))
{
	$adminfunctions = new ct_adminfunctions();

	set_config('ctracker_global_message_type', $_POST['ctracker_global_message_type']);

	set_config('ctracker_global_message', $_POST['ctracker_global_message']);

	$adminfunctions->set_global_message();
	unset($adminfunctions);

	$message = sprintf($lang['ctracker_glob_msg_saved'], append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=4'));
	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['pull_back']))
{
	$adminfunctions = new ct_adminfunctions();
	$adminfunctions->unset_global_message();
	unset($adminfunctions);

	$message = sprintf($lang['ctracker_glob_msg_reset_ok'], append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=4'));
	message_die(GENERAL_MESSAGE, $message);
}

/*
* Select wich option field?
*/
$checked_mode   = '';
$check_status_1 = '';
$check_status_2 = '';
if ($config['ctracker_global_message_type'] == 1)
{
	$checked_mode   = $lang['ctracker_glob_msg_txt'];
	$check_status_1 = ' checked="checked"';
}
else
{
	$checked_mode   = $lang['ctracker_glob_msg_link'];
	$check_status_2 = ' checked="checked"';
}


/*
 * Send some vars to the template
 */
$template->assign_vars(array(
	'L_HEADLINE' => $lang['ctracker_glob_msg_head'],
	'L_SUBHEADLINE' => $lang['ctracker_glob_msg_subhead'],
	'L_GLOBALMSG' => $lang['ctracker_glob_msg_entry'],
	'L_SUBMIT' => $lang['ctracker_glob_msg_submit'],
	'L_RESET' => $lang['ctracker_glob_msg_reset'],
	'L_MSG_TYPE' => $lang['ctracker_glob_msg_type'],
	'L_MSG_TYPE_1' => $lang['ctracker_glob_type_1'],
	'L_MSG_TYPE_2' => $lang['ctracker_glob_type_2'],
	'L_MSG_TXT' => $lang['ctracker_glob_msg_txt'],
	'L_GLOBALMSG_RESET' => $lang['ctracker_glob_msg_reset'],
	'L_GLOB_RESET_TXT' => $lang['ctracker_glob_res_txt'],
	'L_MSG_LINK' => $lang['ctracker_glob_msg_link'],
	'L_FIELD_DESC' => $checked_mode,

	'IMG_ICON_GLOB_MSG' => $images['ctracker_global_msg'],
	'IMG_ICON_GLOB_RES' => $images['ctracker_global_res'],

	'S_CHK_STATUS_1' => $check_status_1,
	'S_CHK_STATUS_2' => $check_status_2,
	'S_CURRENT_TEXT' => $config['ctracker_global_message'],
	'S_FORM_ACTION' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=4')
	)
);

// Generate the page
$template->pparse('ct_body');

?>