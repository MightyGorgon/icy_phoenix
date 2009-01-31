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
* Ptirhiik (admin@rpgnet-fr.com)
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_ICYPHOENIX', true);
define('IN_CALENDAR', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

@set_time_limit(0);
$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '16';
$cms_page_name = 'calendar';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

// get parameters

// set the page title and include the page header
$page_title = $lang['Calendar'];
$meta_description = '';
$meta_keywords = '';
include (IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

// get paramters
$start_date = 0;
if (isset($_GET['start']))
{
	$p_date = intval($_GET['start']);
	$year = intval(substr($p_date, 0, 4));
	$month = intval(substr($p_date, 4, 2));
	$day = intval(substr($p_date, 6, 2));
	if (($year <= 0) || ($month <= 0) || ($day <= 0))
	{
		$year = 0;
	}
	if (!empty($year))
	{
		$start_date = mktime(0,0,0, $month, $day, $year);
	}
}

if (isset($_POST['start_month']))
{
	$month = intval($_POST['start_month']);
	$year = intval($_POST['start_year']);
	if (($month > 0) && ($year > 0))
	{
		$start_date = mktime(0,0,0, $month, 01, $year);
	}
}

if (empty($start_date) || ($start_date <= 0))
{
	$start_date = mktime(0, 0, 0, intval(date('m', cal_date(time(),$board_config['board_timezone']))), intval(date('d', cal_date(time(),$board_config['board_timezone']))), intval(date('Y', cal_date(time(),$board_config['board_timezone']))));
}

// get the forum id selected
$fid = '';
if (isset($_POST['selected_id']) || isset($_GET['fid']))
{
	$fid = isset($_POST['selected_id']) ? $_POST['selected_id'] : $_GET['fid'];
	if ($fid != 'Root')
	{
		$type = substr($fid, 0, 1);
		$id = intval(substr($fid, 1));
		if (!in_array($type, array(POST_FORUM_URL, POST_CAT_URL)))
		{
			$type = POST_CAT_URL;
			$id = 0;
		}
		$fid = $type . $id;
		if ($fid == POST_CAT_URL . '0')
		{
			$fid = 'Root';
		}
	}
}

$template->set_filenames(array('body' => 'calendar_body.tpl'));

// Header
$template->assign_vars(array(
	'L_CALENDAR' => $lang['Calendar'],
	'U_CALENDAR' => append_sid('calendar.' . PHP_EXT),
	)
);

display_calendar('CALENDAR_MONTH', 0, $start_date, $fid);

// system
$s_hidden_fields = '';
$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;
$template->assign_vars(array(
	'NAV_SEPARATOR' => $nav_separator,
	'S_ACTION' => append_sid('calendar.' . PHP_EXT),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	)
);

// send to browser
$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>