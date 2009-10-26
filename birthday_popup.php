<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

$gen_simple_header = true;

$year = create_date('Y', time(), $config['board_timezone']);
$date_today = create_date('Ymd', time(), $config['board_timezone']);
$user_birthday = realdate('md', $userdata['user_birthday']);
$user_birthday2 = (($year . $user_birthday < $date_today) ? ($year + 1) : $year) . $user_birthday;
$l_greeting = ($user_birthday2 == $date_today) ? sprintf($lang['Birthday_greeting_today'], gmdate('Y') - realdate('Y', $userdata['user_birthday'])) : sprintf($lang['Birthday_greeting_prev'], gmdate('Y') - realdate('Y', $userdata['user_birthday']), realdate(str_replace('Y', '', $lang['DATE_FORMAT_BIRTHDAY']), $userdata['user_birthday']));

$template->assign_vars(array(
	'L_CLOSE_WINDOW' => $lang['Close_window'],
	'L_MESSAGE' => $l_greeting
	)
);

full_page_generation('greeting_popup.tpl', $lang['Greeting_Messaging'], '', '');

?>