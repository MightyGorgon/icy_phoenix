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

define('NO_GENDER', 0);
define('MALE', 1);
define('FEMALE', 2);

//Vote Images based on the theme path, (i.e. templates/CURRNT_THEME/ is already inserted below)

$rank = 0;

// Gender SQL
$sql = 'SELECT COUNT(user_gender) used_counter, user_gender
FROM ' . USERS_TABLE . '
WHERE user_id != -1
GROUP BY user_gender ORDER BY used_counter DESC';

if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve users data", '', __LINE__, __FILE__, $sql);
}

$user_count = $db->sql_numrows($result);
$user_data = $db->sql_fetchrowset($result);
$percentage = 0;
$bar_percent = 0;
$usercount = $board_config['max_users'];

$firstcount = $user_data[0]['used_counter'];
$cst = ($firstcount > 0) ? 90 / $firstcount : 90;

$template->_tpldata['gender.'] = array();
//reset($template->_tpldata['gender.']);

for ($i = 0; $i < $user_count; $i++)
{

	if ($user_data[$i]['used_counter'] != 0 )
	{
		$percentage = round(min(100, ($user_data[$i]['used_counter'] /$usercount) * 100));
	}
	else
	{
		$percentage = 0;
	}

	$bar_percent = round($user_data[$i]['used_counter'] * $cst);
	switch ($user_data[$i]['user_gender']){
		case NO_GENDER: $gender = $lang['No_gender_specify']; $gender_image =''; break;
		case MALE: $gender = '<img src="' . $images['icon_minigender_male'] . '" border="0" alt="' . $lang['Male'] . '" />'; break;
		case FEMALE: $gender = '<img src="' . $images['icon_minigender_female'] . '" border="0" alt="' . $lang['Female'] . '" />'; break;
	}

	$template->assign_block_vars('gender', array(
		'RANK' => $i + 1,
		'CLASS' => (!($i + 1 % 2)) ? $theme['td_class2'] : $theme['td_class1'],
		'GENDER' => $gender,
		'USERS' => $user_data[$i]['used_counter'],
		'PERCENTAGE' => $percentage,
		'BAR' => $bar_percent
		)
	);
}

$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_USERS' => $lang['Users'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_GENDER' => $lang['Gender'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_users_gender']
	)
);

?>