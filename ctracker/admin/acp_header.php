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
* A header file wich we can include in all ACP Modules
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
 * Currently we have just the header template here but we created this file
 * to ensure that we have a global ACP file if we need one in the future.
 */

$template->set_filenames(array('ct_header' => ADM_TPL . 'acp_header.tpl'));


// Send some vars to the template
$template->assign_vars(array(
	'HEADER_BACKGROUND_IMAGE' => $images['ctracker_acp_bg'],
	'HEADER_LOGO' => $images['ctracker_acp_logo'],
	'L_PICTURE' => $lang['ctracker_img_descriptions']
	)
);


// Generate the page
$template->pparse('ct_header');


?>