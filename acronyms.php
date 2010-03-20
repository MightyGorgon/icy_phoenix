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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$i = 0;
$sql = "SELECT * FROM " . ACRONYMS_TABLE . " ORDER BY acronym ASC";
$result = $db->sql_query($sql);
while($acronym_row = $db->sql_fetchrow($result))
{
	$acronym = $acronym_row['acronym'];
	$description = $acronym_row['description'];
	$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];
	$template->assign_block_vars('acronym_row', array(
		'ROW_CLASS' => $class,
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

full_page_generation('acronym_body.tpl', $lang['Acronyms'], '', '');

?>