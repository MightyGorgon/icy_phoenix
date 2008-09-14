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
* See if files were changed since you were last on your forum
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if ( !defined('IN_ICYPHOENIX') || !defined('CTRACKER_ACP') )
{
	die('Hacking attempt!');
}


/*
 * Create our Admin Class Objects
 */
$ct_admin = new ct_adminfunctions();


/*
 * Wich action do we have?
 */
$action = $_GET['action'];

if ( $action == 'akt' )
{
	/*
	 * Update the File Hashes
	 */
	$timestamp = time();
	$ctracker_config->change_configuration('last_checksum_scan', $timestamp);
	$ctracker_config->settings['last_checksum_scan'] = $timestamp;
	$ct_admin->do_filechk();

	$template->assign_block_vars('akt_complete', array(
		'L_UPDATE_ACTION'	=> $lang['ctracker_fchk_update_action'])
	);
}
else if ( $action == 'chk' )
{
	/*
	 * Lets check the files for changes
	 */
	$sql = 'SELECT * FROM ' . CTRACKER_FILECHK;
	$table_class = false;

	if ( (!$result = $db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
	}

	$template->assign_block_vars('header_table_cell', array());

	while( $row = $db->sql_fetchrow($result) )
	{
		$table_class    = !$table_class;

		$current_hash = '';
		$current_hash = @filesize($row['filepath']) . '-' . count(@file($row['filepath']));

		if ( $current_hash == '-1' )
		{
			$filestatus = $lang['ctracker_file_deleted'];
			$color      = '#0300FF';
		}
		elseif( md5($current_hash) != $row['hash'])
		{
			$filestatus = $lang['ctracker_file_changed'];
			$color      = '#FF1200';
		}
		else
		{
			$filestatus = $lang['ctracker_file_unchanged'];
			$color      = '#269F00';
		}

		$path_cleaned = str_replace('./../', '', $row['filepath']);

		$template->assign_block_vars('file_output', array(
			'PATH'   => $path_cleaned,
			'STATUS' => $filestatus,
			'CLASS'  => ($table_class)? 'row1' : 'row2',
			'COLOR'  => $color)
		);
	}
}
else
{
	/*
	 * No action selected
	 */
	$template->assign_block_vars('no_action', array(
		'L_SELECT_ACTION'	=> $lang['ctracker_fchk_select_action'])
	);
}


/*
 * Output the page
 */
$template->set_filenames(array(
	'ct_body' => ADM_TPL . 'acp_changedfiles.tpl')
);


/*
 * Send some vars to the template
 */
$template->assign_vars(array(
	'L_HEADLINE' 		=> $lang['ctracker_fchk_head'],
	'L_SUBHEADLINE'		=> sprintf($lang['ctracker_fchk_subhead'], date($board_config['default_dateformat'], $ctracker_config->settings['last_checksum_scan'])),
	'L_FUNC_HEADER'		=> $lang['ctracker_fchk_funcheader'],
	'L_TABLE_HEADER'	=> $lang['ctracker_fchk_tableheader'],
	'L_OPTION_1'		=> $lang['ctracker_fchk_option1'],
	'L_OPTION_2'		=> $lang['ctracker_fchk_option2'],
	'L_ALT_TEXT'		=> $lang['ctracker_img_descriptions'],

	'L_TABLEHEAD_1'		=> $lang['ctracker_fchk_tablehead1'],
	'L_TABLEHEAD_2'		=> $lang['ctracker_fchk_tablehead2'],

	'U_LINK_OPTION_1'	=> append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=1&action=akt'),
	'U_LINK_OPTION_2'	=> append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=1&action=chk'),

	'IMG_ICON_1'		=> IP_ROOT_PATH . $images['ctracker_fc_icon_1'],
	'IMG_ICON_2'		=> IP_ROOT_PATH . $images['ctracker_fc_icon_2'])
  );


// Generate the page
$template->pparse('ct_body');


// Unset unused Objects
unset($ct_admin);

?>
