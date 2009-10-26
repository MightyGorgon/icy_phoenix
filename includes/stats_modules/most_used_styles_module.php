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

// Most Used Styles
$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_TITLE' => $lang['module_name_most_used_styles'],
	'L_STYLE' => $lang['Style'],
	'L_GRAPH' => $lang['Graph'],
	'L_HOWMANY' => $lang['How_many']
	)
);

$sql = "SELECT COUNT(u.user_style) as used_counter, t.style_name
	FROM " . USERS_TABLE . " u, " . THEMES_TABLE . " t
	WHERE u.user_style = t.themes_id
	GROUP BY t.themes_id, t.style_name
	ORDER BY used_counter DESC
	LIMIT " . $return_limit;
$result = $stat_db->sql_query($sql);
$themes_count = $stat_db->sql_numrows($result);
$themes_data = $stat_db->sql_fetchrowset($result);

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

for ($i = 0; $i < $themes_count; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$template->assign_block_vars('stats_row', array(
		'RANK' => $i + 1,
		'CLASS' => $class,
		'STYLE' => $themes_data[$i]['style_name'],
		'HOWMANY' => $themes_data[$i]['used_counter']
		)
	);
}

?>