<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//
// Modules should be considered to already have access to the following variables which
// the parser will give out to it:

// $return_limit - Control Panel defined number of items to display
// $module_info['name'] - The module name specified in the info.txt file
// $module_info['email'] - The author email
// $module_info['author'] - The author name
// $module_info['version'] - The version
// $module_info['url'] - The author url
//
// To make the module more compatible, please do not use any functions here
// and put all your code inline to keep from redeclaring functions on accident.
//

//
// All your code
//
// Where are users from
//
// Updated by Acyd Burn on 2002-09-13
//

$sql = "SELECT user_from, COUNT(*) as number
FROM " . USERS_TABLE . "
WHERE user_from <> ''
GROUP BY user_from
ORDER BY number DESC
LIMIT " . $return_limit;

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t retrieve user data', '', __LINE__, __FILE__, $sql);
}

$user_count = $db->sql_numrows($result);
$user_data = $db->sql_fetchrowset($result);

$template->_tpldata['fromwhere.'] = array();
//reset($template->_tpldata['fromwhere.']);

for ($i = 0; $i < $user_count; $i++)
{
	$class = (!($i + 1 % 2)) ? $theme['td_class2'] : $theme['td_class1'];

	$template->assign_block_vars('fromwhere', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'FROMWHERE' => $user_data[$i]['user_from'],
		'HOWMANY' => $user_data[$i]['number']
		)
	);
}

$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_FROMWHERETITLE' => $lang['module_name_users_from_where'],
	'L_FROMWHERE' => $lang['From_where'],
	'L_HOWMANY' => $lang['How_many']
	)
);

?>