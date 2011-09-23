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
* Chris Lennert - (calennert@users.sourceforge.net) - (http://lennertmods.sourceforge.net)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bb_usage_stats_constants.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

setup_extra_lang(array('lang_bb_usage_stats'));

include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);

/* Set the file handles to include bb_usage_stats.tpl */
$template->set_filenames(array('bbus_coldesc_template' => 'bb_usage_stats_coldesc.tpl'));

/* If the %UTUP column is enabled, display its column description info. */
if (($config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME] & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
{
	$template->assign_block_vars('bb_usage_switch_pctutup_coldesc', array(
		'L_BBUS_COLHEADER_PCTUTUP' => $lang['BBUS_ColHeader_PctUTUP'],
		'L_BBUS_COLHEADER_PCTUTUP_EXPLAIN' => $lang['BBUS_ColHeader_PctUTUP_Explain']
		)
	);
}

/* Assign template variables. */
$template->assign_vars(array(
	'L_CLOSE_WINDOW' => $lang['Close_window'],
	'L_BBUS_COL_DESCRIPTIONS_CAPTION' => $lang['BBUS_Col_Descriptions_Caption'],
	'L_BBUS_COLHEADER_FORUM' => $lang['Forum'],
	'L_BBUS_COLHEADER_POSTS' => $lang['Posts'],
	'L_BBUS_COLHEADER_POSTRATE' => $lang['BBUS_ColHeader_PostRate'],
	'L_BBUS_COLHEADER_PCTUTP' => $lang['BBUS_ColHeader_PctUTP'],
	'L_BBUS_COLHEADER_NEWTOPICS' => $lang['BBUS_ColHeader_NewTopics'],
	'L_BBUS_COLHEADER_TOPICRATE' => $lang['BBUS_ColHeader_TopicRate'],
	'L_BBUS_COLHEADER_TOPICS_WATCHED' => $lang['BBUS_ColHeader_Topics_Watched'],

	'L_BBUS_COLHEADER_POSTS_EXPLAIN' => $lang['BBUS_ColHeader_Posts_Explain'],
	'L_BBUS_COLHEADER_POSTRATE_EXPLAIN' => $lang['BBUS_ColHeader_PostRate_Explain'],
	'L_BBUS_COLHEADER_PCTUTP_EXPLAIN' => $lang['BBUS_ColHeader_PctUTP_Explain'],
	'L_BBUS_COLHEADER_NEWTOPICS_EXPLAIN' => $lang['BBUS_ColHeader_NewTopics_Explain'],
	'L_BBUS_COLHEADER_TOPICRATE_EXPLAIN' => $lang['BBUS_ColHeader_TopicRate_Explain'],
	'L_BBUS_COLHEADER_TOPICS_WATCHED_EXPLAIN' => $lang['BBUS_ColHeader_Topics_Watched_Explain'],

	'L_BBUS_COLHEADER_HEADER' => $lang['BBUS_ColHeader_Header'],
	'L_BBUS_COLHEADER_DESCRIPTION' => $lang['BBUS_ColHeader_Description']
	)
);

$template->pparse('bbus_coldesc_template');

?>