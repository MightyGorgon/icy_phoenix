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
* The ACP Module for the System Restore Feature
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if ( !defined('IN_PHPBB') || !defined('CTRACKER_ACP') )
{
	die('Hacking attempt!');
}

/*
 * Template File definition
 */
$template->set_filenames(array('ct_body' => ADM_TPL . 'acp_systemrestore.tpl'));

if ( $_GET['mode'] == 'backup')
{
	$backup_system = new ct_adminfunctions();
	$backup_system->recover_configuration();
	unset($backup_system);

	// Send the user the OK message
	$template->assign_block_vars('infobox', array(
				'COLOR'				=> 'DBFFCF',
				'L_MESSAGE_TEXT'	=> $lang['ctracker_rec_succ'])
		);
}
elseif ( $_GET['mode'] == 'restore' )
{
	$backup_system = new ct_adminfunctions();
	$backup_system->restore_configuration();
	unset($backup_system);

	// Send the User the OK message
	$template->assign_block_vars('infobox', array(
				'COLOR'				=> 'DBFFCF',
				'L_MESSAGE_TEXT'	=> $lang['ctracker_rec_succ']
			)
		);
}

/*
 * Load backup status
 */
$save_status = '';
$saved_now   = false;
$sql = 'SELECT * FROM ' . CTRACKER_BACKUP . ' WHERE config_name = \'ct_last_backup\'';
if ( !$result = $db->sql_query($sql) )
{
	$save_status = $lang['ctracker_rec_never_saved'];
}
else
{
	$saved_now = true;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$backup[$row['config_name']] = $row['config_value'];
	}
	$save_status = sprintf($lang['ctracker_rec_last_saved'], date($board_config['default_dateformat'], $backup['ct_last_backup']));
}


/*
 * Send some vars to the template
 */
$template->assign_vars(array(
		'IMG_RECOVERY'		=> $phpbb_root_path . $images['ctracker_recovery'],
		'L_HEADLINE'		=> $lang['ctracker_rec_head'],
		'L_SUBHEADLINE'		=> $lang['ctracker_rec_subhead'],
		'L_BACKUP'			=> $lang['ctracker_rec_backup'],
		'L_RESTORE'			=> ($saved_now)? $lang['ctracker_rec_restore'] : $lang['ctracker_rec_pab'],
		'L_SAVE_STATUS'		=> $save_status,

		'U_LINK_BACKUP'		=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=10&mode=backup'),
		'U_LINK_RESTORE'	=> ($saved_now)? append_sid('admin_cracker_tracker.' . $phpEx . '?modu=10&mode=restore') : ''
		)
	);


// Generate the page
$template->pparse('ct_body');


?>