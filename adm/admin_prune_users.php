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
* Niels (ncr@db9.dk)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	//$module['Users'][$lang['Prune_users']] = $filename;
	$module['1610_Users']['190_Prune_users'] = $filename;
	return;
}
// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);

// ********************************************************************************
// from here you can define you own delete criterias, if you makes more, then you shall also
// edit the files lang_main.php, and the file delete_users.php, so they hold the same amount
// of options

$sql_up = array();
$default = array();

// Initial selection
// Zero posters
$default[0] = 180;
$sql_up[0] = ' AND user_posts = \'0\'';

// Users who have never logged in
$default[1] = 180;
$sql_up[1] = ' AND user_lastvisit = \'0\'';

// Not activated users
$default[2] = 180;
$sql_up[2] = ' AND user_lastvisit = \'0\' AND user_active = \'0\'';
//$sql_up[2] = ' AND user_active = \'0\'';

// Users not visiting since 60 days
$default[3] = 180;
$sql_up[3] = ' AND user_lastvisit < ' . (time() - (86400 * 60));

// Users with less than 0.1 posts per day avg.
$default[4] = 360;
$sql_up[4] = ' AND user_posts / ((user_lastvisit - user_regdate) / 86400) < "0.1"';

// Zero posters not visiting
$default[5] = 180;
$sql_up[5] = ' AND user_posts = \'0\' AND user_lastvisit < ' . (time() - (86400 * $default[5]));


// ********************************************************************************
// ****************** Do not change any thing below *******************************

$options = '<option value="1">&nbsp;' . $lang['1_DAY'] . '</option>
	<option value="7">&nbsp;' . $lang['7_DAYS'] . '</option>
	<option value="14">&nbsp;' . $lang['2_WEEKS'] . '</option>
	<option value="21">&nbsp;' . sprintf($lang['X_WEEKS'], 3) . '</option>
	<option value="30">&nbsp;' . $lang['1_MONTH'] . '</option>
	<option value="60">&nbsp;' . sprintf($lang['X_MONTHS'], 2) . '</option>
	<option value="90">&nbsp;' . $lang['3_MONTHS'] . '</option>
	<option value="180">&nbsp;' . $lang['6_MONTHS'] . '</option>
	<option value="365">&nbsp;' . $lang['1_YEAR'] . '</option>
</select>';

// Generate page
include('page_header_admin.' . PHP_EXT);
$template->set_filenames(array('body' => ADM_TPL . 'prune_users_body.tpl'));
$n = 0;
while (!empty($sql_up[$n]))
{
	$vars = 'days_' . $n;

	$default [$n] = ($default[$n]) ? $default[$n] : 10;
	$days [$n] = isset($_GET[$vars]) ? $_GET[$vars] : (isset($_POST[$vars]) ? intval($_POST[$vars]) : $default[$n]);

	// make a extra option if the parsed days value does not already exisit
	if (!strpos($options, 'value="' . $days[$n]))
	{
		$options = '<option value="' . $days[$n] . '">&nbsp;' . sprintf($lang['X_DAYS'], $days[$n]) . '</option>' . $options;
	}
	$select[$n] = '<select name="days_' . $n . '" size="1" onchange="SetDays();" class="gensmall">' . str_replace('value="' . $days[$n] . '">&nbsp;', 'value="' . $days[$n] . '" selected="selected">&nbsp;*', $options);

	$sql_full = "SELECT user_id , username, user_active, user_color, user_level
							FROM " . USERS_TABLE . "
							WHERE user_id <> '" . ANONYMOUS . "'
							" . $sql_up[$n] . "
							AND user_regdate < '" . (time() - (86400 * $days[$n])) . "'
							ORDER BY username LIMIT 800";

	$result = $db->sql_query($sql_full);
	$user_list = $db->sql_fetchrowset($result);
	$user_count = sizeof($user_list);
	for($i = 0; $i < $user_count; $i++)
	{
		$list[$n] .= ' ' . colorize_username($user_list[$i]['user_id'], $user_list[$i]['username'], $user_list[$i]['user_color'], $user_list[$i]['user_active']);
	}
	$db->sql_freeresult($result);
	$template->assign_block_vars('prune_list', array(
			'LIST' => ($list[$n]) ? $list[$n] : $lang['None'],
			'USER_COUNT' => $user_count,
			'L_PRUNE' => $lang['Prune_commands'][$n],
			'L_PRUNE_EXPLAIN' => sprintf($lang['Prune_explain'][$n], $days[$n]),
			'S_PRUNE_USERS' => append_sid('admin_prune_users.' . PHP_EXT),
			'S_DAYS' => $select[$n],
			//'U_PRUNE' => '<a href="' . append_sid(IP_ROOT_PATH . 'delete_users.' . PHP_EXT . '?mode=prune_' . $n . '&amp;days=' . $days[$n]) . '" onclick="return confirm(\'' . sprintf($lang['Prune_on_click'], $user_count) . '\')">' . $lang['Prune_commands'][$n] . '</a>',
			'U_PRUNE' => '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/admin_prune_users_loop.' . PHP_EXT . '?mode=prune_' . $n . '&amp;days=' . $days[$n]) . '" onclick="return confirm(\'' . sprintf($lang['Prune_on_click'], $user_count) . '\')">' . $lang['Prune_commands'][$n] . '</a>',
		)
	);
	$n++;
}

$template->assign_vars(array(
	'L_PRUNE_ACTION' => $lang['Prune_Action'],
	'L_PRUNE_LIST' => $lang['Prune_user_list'],
	'L_DAYS' => $lang['Days'],
	'L_PRUNE_USERS' => $lang['Prune_users'],
	'L_PRUNE_USERS_EXPLAIN' => $lang['Prune_users_explain'],
	)
);

$template->pparse('body');
include('page_footer_admin.' . PHP_EXT);

?>