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
* This file is for the Login History each User can see for his Account.
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 17.08.2006 - 02:42:16
* @copyright (c) 2006 www.cback.de
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// session id check
$sid = request_var('sid', '');

// Ensure that a user is logged in and the feature is available
if (!$user->data['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['ctracker_lhistory_err']);
}

// Output Login History
if ($config['ctracker_login_history'])
{
	$sql = 'SELECT * FROM ' . CTRACKER_LOGINHISTORY . ' WHERE ct_user_id = ' . $user->data['user_id'] . ' ORDER BY ct_login_time DESC';
	$result = $db->sql_query($sql);
	$count = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$count++;

		$template->assign_block_vars('login_output', array(
			'ROW_CLASS' => ($count % 2 == 0)? $theme['td_class1'] : $theme['td_class2'],
			'VALUE_1' => $count,
			'VALUE_2' => create_date($config['default_dateformat'], $row['ct_login_time'], $config['board_timezone']),
			'VALUE_3' => $row['ct_login_ip']
			)
		);
	}
}


// Output settings for Login Checker
if ($config['ctracker_login_ip_check'] == 1)
{

	$sel1 = '';
	$sel2 = '';

	if ($_POST['submit'])
	{
		$newsetting = intval($_POST['ct_enable_ip_warn']);
		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_enable_ip_warn = ' . $newsetting . ' WHERE user_id = ' . $user->data['user_id'];
		$user->data['ct_enable_ip_warn'] = $newsetting;
		$result = $db->sql_query($sql);
	}

	($user->data['ct_enable_ip_warn'] == 1) ? $sel1 = ' checked="checked"' : $sel2 = ' checked';

	$template->assign_block_vars('log_set', array(
			'S_FORM_ACTION' => append_sid('ct_login_history.' . PHP_EXT),
			'L_HEADER_TEXT' => $lang['ctracker_ipwarn_prof'],
			'L_DESC' => $lang['ctracker_ipwarn_pdes'],
			'L_ON' => $lang['ctracker_settings_on'],
			'L_OFF' => $lang['ctracker_settings_off'],
			'L_SEND' => $lang['ctracker_ipwarn_send'],
			'S_SELECT_ON' => $sel1,
			'S_SELECT_OFF' => $sel2,
			'IMG_ICON' => $images['ctracker_log_manager'])
	);
}

// Send some vars to the template
$template->assign_vars(array(
	'L_HEADER_TEXT' => $lang['ctracker_lhistory_h'],
	'L_DESCRIPTION' => ($config['ctracker_login_history'] == 1) ? sprintf($lang['ctracker_lhistory_i'], $config['ctracker_login_history_count']) : $lang['ctracker_lhistory_off'],
	'L_TABLEHEAD_1' => $lang['ctracker_lhistory_h1'],
	'L_TABLEHEAD_2' => $lang['ctracker_lhistory_h2']
	)
);

full_page_generation('ctracker_login_history.tpl', $lang['ctracker_lhistory_nav'], '', '');

?>