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
* Here you can manage users wich you marked as "Miserable User"
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

/* Template definition */
$template->set_filenames(array('ct_body' => ADM_TPL . 'acp_miserableuser.tpl'));

$userid = request_var('userid', 0);

$username = request_var('username', '', true);
$username = htmlspecialchars_decode($username, ENT_COMPAT);

$mode = request_var('mode', '');

if (isset($_POST['submit']))
{
	$user_id = 0;
	$user_level = 0;
	$this_userdata = get_userdata($username, true);

	if(!$this_userdata)
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
	}


	if (($this_userdata['user_level'] == ADMIN) || ($this_userdata['user_level'] == MOD))
	{
		// Admin or Mods can not be defined as miserable user
		$template->assign_block_vars('infobox', array(
			'COLOR' => 'ffdddd',
			'L_MESSAGE_TEXT' => $lang['ctracker_mu_error_admin']
			)
		);
	}
	else
	{
		// Mark user as miserable user
		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_miserable_user = 1 WHERE user_id = ' . $this_userdata['user_id'];
		$result = $db->sql_query($sql);

		$template->assign_block_vars('infobox', array(
			'COLOR' => 'ddffcc',
			'L_MESSAGE_TEXT' => $lang['ctracker_mu_success_html']
			)
		);
	}
}
elseif ($mode == 'unmis')
{
	$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_miserable_user = 0 WHERE user_id = ' . $userid;
	$result = $db->sql_query($sql);

	$template->assign_block_vars('infobox', array(
		'COLOR' => 'ddffcc',
		'L_MESSAGE_TEXT' => $lang['ctracker_mu_deleted']
		)
	);
}


// List output
$sql = 'SELECT username, user_id, user_active, user_color FROM ' . USERS_TABLE . ' WHERE ct_miserable_user = 1';
$result = $db->sql_query($sql);

// Define some vars
$row_class = false;
$entry_def = false;

while ($row = $db->sql_fetchrow($result))
{
	$row_class = !$row_class;	// row class changer without counter
	$entry_def = true;				// yes, we have users in our list!
	$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);

	$template->assign_block_vars('output', array(
		'ROW_CLASS' => ($row_class) ? 'row1' : 'row2',
		'U_DELLINK' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=8&mode=unmis&userid=' . $row['user_id']),
		'L_USERNAME' => $username
		)
	);
}

// No entry in List?
($entry_def) ? null : $template->assign_block_vars('no_entry', array());

/* Send some vars to the template */
$template->assign_vars(array(
	'U_SEARCH_USER' => append_sid(IP_ROOT_PATH . 'search.' . PHP_EXT . '?mode=searchuser'),
	'S_FORM_ACTION' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=8'),
	'L_HEADLINE' => $lang['ctracker_mu_head'],
	'L_SUBHEADLINE' => $lang['ctracker_mu_subhead'],
	'L_MARK_MU' => $lang['ctracker_mu_select'],
	'L_LOOK_UP' => $lang['ctracker_mu_send'],
	'L_FIND_USERNAME' => $lang['ctracker_mu_find'],
	'L_USER_ENTR' => $lang['ctracker_mu_entr'],
	'L_TH1' => $lang['ctracker_mu_uname'],
	'L_TH2' => $lang['ctracker_mu_remove'],
	'L_DELETE' => $lang['ctracker_ipb_delete'],
	'L_NOTHING' => $lang['ctracker_mu_no_defined']
	)
);

// Generate the page
$template->pparse('ct_body');

?>