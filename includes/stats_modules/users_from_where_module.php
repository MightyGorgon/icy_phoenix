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

// Where are users from
$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_FROMWHERETITLE' => $lang['module_name_users_from_where'],
	'L_FROMWHERE' => $lang['From_where'],
	'L_HOWMANY' => $lang['How_many']
	)
);

$sql = "SELECT user_from, COUNT(*) as number
	FROM " . USERS_TABLE . "
	WHERE user_from <> ''
	GROUP BY user_from
	ORDER BY number DESC
	LIMIT " . $return_limit;
$result = $stat_db->sql_query($sql);
$user_count = $stat_db->sql_numrows($result);
$user_data = $stat_db->sql_fetchrowset($result);

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < $user_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'FROMWHERE' => $user_data[$i]['user_from'],
		'HOWMANY' => $user_data[$i]['number']
		)
	);
}

?>