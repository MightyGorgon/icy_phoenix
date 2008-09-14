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
* Everything wich CrackerTracker can handle on the Database
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
 * Include footer file
 */
include(IP_ROOT_PATH . 'ctracker/engines/ct_footer.' . PHP_EXT);


/*
 * Template
 */
$template->set_filenames(array(
	'ct_body' => ADM_TPL . 'acp_footer_config.tpl')
);


/*
 * Do we have a submission?
 */
if ( isset($_POST['submit']) )
{
	$ctracker_config->change_configuration('footer_layout', intval($_POST['footer_layout']));
	$ctracker_config->settings['footer_layout'] = intval($_POST['footer_layout']);
	$template->assign_block_vars('infobox', array());
}


/*
 * Footer outputs
 */
for($i = 1; $i <= 8; $i++)
{
	$template->assign_block_vars('footer_output', array(
			'ROW_CLASS'		=> ($i % 2 == 0)? 'row1': 'row2',
			'IMG_FOOTER'	=> create_footer_layout($i),
			'S_SELECT'		=> $i,
			'S_SELECTED'	=> ($i == $ctracker_config->settings['footer_layout'])? ' checked="checked"': '')
	);
}

/*
 * Send some vars to the template
 */
$template->assign_vars(array(
		'L_HEADLINE'		=> $lang['ctracker_footer_head'],
		'L_SUBHEADLINE' 	=> $lang['ctracker_footer_subhead'],
		'L_SELECT_FOOTER' 	=> $lang['ctracker_select_footer'],
		'L_SUBMIT_BUTTON'	=> $lang['ctracker_footer_saveit'],
		'L_MESSAGE_TEXT'	=> $lang['ctracker_footer_done'],
		'S_FORM_ACTION'		=> append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=11'))
  );


// Generate the page
$template->pparse('ct_body');


?>
