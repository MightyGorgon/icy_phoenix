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
* CodeMonkeyX.net (webmaster@codemonkeyx.net)
* Mighty_Y <http://www.portedmods.com>
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$page_title = $lang['Acronyms'];
$meta_description = '';
$meta_keywords = '';

include('includes/page_header.' . $phpEx);

$template->set_filenames(array('body' => 'acronym_body.tpl'));

$sql = "SELECT * FROM " . ACRONYMS_TABLE . " ORDER BY acronym ASC";
if(!$result = $db->sql_query($sql, false, 'acronyms_'))
{
	message_die(GENERAL_ERROR, "Could not obtain acronym data", "", __LINE__, __FILE__, $sql);
}

$i = 0;

while($acronym_row = $db->sql_fetchrow($result))
{
	$acronym = $acronym_row['acronym'];
	$description = $acronym_row['description'];
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	$template->assign_block_vars('acronym_row', array(
		'ROW_CLASS' => $row_class,
		'ACRONYM' => $acronym,
		'DESCRIPTION' => $description,
		)
	);
	$i++;
}

$template->assign_vars(array(
	'L_ACRONYM' => $lang['Acronym'],
	'L_ACRONYMS' => $lang['Acronyms'],
	'L_DESCRIPTION' => $lang['Description'],
	)
);

$template->pparse('body');

include('includes/page_tail.' . $phpEx);
?>