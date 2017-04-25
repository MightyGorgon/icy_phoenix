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
* Nivisec.com (support@nivisec.com)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1200_Forums']['150_Topic_Shadow'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_topics_shadows.' . PHP_EXT);

if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
if (empty($class_mcp)) $class_mcp = new class_mcp();

/* If for some reason preference cookie saving needs to be disabled, you
can do so by setting this to true */
define('DISABLE_PREFERENCE_SAVING', false);
/* If for some reason you need to disable the version check in THIS HACK ONLY,
change the blow to TRUE instead of FALSE.  No other hacks will be affected
by this change.
*/
define('DISABLE_VERSION_CHECK', false);
/* Changing these will produce false results on your
template, mess up any saved cookie preferences, and produce odd results
for the version checker.  So, DO NOT change them unless necessary!
*/
define('MOD_VERSION', '2.13');
define('MOD_CODE', 2);
define('MOD_COOKIE_PREF_NAME', 'nivisec_phpbb2_mod_preferences');

/*
******************************************************************************************
* Get parameters.  'var_name' => 'default_value'
* Also get any saved cookie preferences.
******************************************************************************************
*/
$preference_cookie = (isset($_COOKIE[$config['cookie_name'] . '_' . MOD_COOKIE_PREF_NAME])) ? unserialize(stripslashes($_COOKIE[$config['cookie_name'] . '_' . MOD_COOKIE_PREF_NAME])) : array();
$preference_cookie['test'] = true;
$params = array(
	'start' => 0,
	'order' => 'DESC',
	'mode' => 'topic_time',
	'delete_all_before_date' => 0,
	'del_month' => 1,
	'del_day' => 1,
	'del_year' => 1970
);
$params_ignore = array('delete_all_before_date');

foreach($params as $var => $default)
{
	$$var = (isset($preference_cookie[MOD_CODE . "_$var"]) && !in_array($var, $params_ignore)) ? $preference_cookie[MOD_CODE . "_$var"] : $default;
	if(isset($_POST[$var]) || isset($_GET[$var]))
	{
		$preference_cookie[MOD_CODE . "_$var"] = (isset($_POST[$var])) ? $_POST[$var] : $_GET[$var];
		$$var = $preference_cookie[MOD_CODE . "_$var"];
	}
}
/*
***************************************************************************
* Includes and cookie settings (with output buffering)
***************************************************************************
*/
/* Make a new output buffer for this page in order to not screw up cookie
setting.  If this is disabled, settings will NEVER be saved */
if(!DISABLE_PREFERENCE_SAVING && !$config['gzip_compress']) ob_start();

$user->set_cookie(MOD_COOKIE_PREF_NAME, serialize($preference_cookie), $user->cookie_expire);

/* Flush the output buffer to display the page header, if the ob_start() is
removed, this one must be removed as well or strange things will happen */
if(!DISABLE_PREFERENCE_SAVING && !$config['gzip_compress']) ob_end_flush();

/*
***************************************************************************
* Constants and Main Vars.
***************************************************************************
*/
$mode_types = array('topic_time', 'topic_title');
$mode = in_array($mode, $mode_types) ? $mode : $mode_types[0];
$order_types = array('DESC', 'ASC');
$order = in_array($order, $order_types) ? $order : $order_types[0];
$meta_content['page_title'] = $lang['Topic_Shadow'];
$status_message = '';

/*
******************************************************************************************
* Check for deletion items
/*****************************************************************************************
*/
if ($delete_all_before_date)
{
	/* Error Checking */
	$error_message = '';
	if ($del_month < 1 || $del_month > 12)
	{
		$error_message .= $lang['Error_Month'];
	}
	if ($del_day < 1 || $del_day > 31)
	{
		$error_message .= $lang['Error_Day'];
	}
	if ($del_year < 1970 || $del_year > 2038)
	{
		$error_message .= $lang['Error_Year'];
	}
	if ($error_message != '')
	{
		message_die(GENERAL_ERROR, $error_message, '', __LINE__, __FILE__);
	}
	/* END Error Checking */

	$set_time = gmmktime(0, 0, 0, $del_month, $del_day, $del_year);
	$sql = 'DELETE FROM ' . TOPICS_TABLE . '
					WHERE topic_status = ' . TOPIC_MOVED . "
					AND topic_time < $set_time";
	$db->sql_query($sql);
	$status_message .= sprintf($lang['Del_Before_Date'], gmdate('M-d-Y', $set_time));
	$status_message .= (SQL_LAYER == 'db2' || SQL_LAYER == 'mysql' || SQL_LAYER == 'mysql4') ? sprintf($lang['Affected_Rows'], $db->sql_affectedrows()) : '';
	$class_mcp->sync('all_forums');
	$status_message .= sprintf($lang['Resync_Ran_On'], $lang['All_Forums']);
}
else
{
	if (sizeof($_POST))
	{
		foreach($_POST as $key => $val)
		{
			if (substr_count($key, 'delete_id_'))
			{
				$topic_id = substr($key, 10);

				/* Get forum info to Resync it */
				$sql = 'SELECT f.forum_id, f.forum_name, t.topic_title FROM ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . " f
								WHERE t.topic_id = $topic_id
								AND t.forum_id = f.forum_id";
				$result = $db->sql_query($sql);
				$forum_data_row = $db->sql_fetchrow($result);

				$sql = 'DELETE FROM ' . TOPICS_TABLE . '
								WHERE topic_status = ' . TOPIC_MOVED . "
								AND topic_id = $topic_id";
				$db->sql_query($sql);
				$status_message .= sprintf($lang['Deleted_Topic'], $forum_data_row['topic_title']);
				$class_mcp->sync('forum', $forum_data_row['forum_id']);
				$status_message .= sprintf($lang['Resync_Ran_On'], $forum_data_row['forum_name']);
			}
		}
	}
}

/*
******************************************************************************************
* Main Page
******************************************************************************************
*/

$template->set_filenames(array('body' => ADM_TPL . 'admin_topic_shadow.tpl'));

if ($status_message != '')
{
	$template->assign_block_vars('statusrow', array());
}

$template->assign_vars(array(
	'L_DELETE_FROM_EXPLAN' => $lang['Delete_From_Date'],
	'L_DELETE_BEFORE' => $lang['Delete_Before_Date_Button'],
	'L_MONTH' => $lang['Month'],
	'L_DAY' => $lang['Day'],
	'L_YEAR' => $lang['Year'],
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_TITLE' => $lang['Title'],
	'L_TIME' => $lang['Time'],
	'L_POSTER' => $lang['Poster'],
	'L_MOVED_TO' => $lang['Moved_To'],
	'L_PAGE_NAME' => $meta_content['page_title'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_DELETE' => $lang['Delete'],
	'L_NO_TOPICS_FOUND' => $lang['No_Shadow_Topics'],
	'L_STATUS' => $lang['Status'],
	'L_PAGE_DESC' => $lang['TS_Desc'],
	'L_CLEAR' => $lang['Clear'],
	'L_MOVED_FROM' => $lang['Moved_From'],
	'L_VERSION' => $lang['Version'],
	'VERSION' => MOD_VERSION,

	'I_STATUS_MESSAGE' => $status_message,

	'S_MONTH' => gmdate('m'),
	'S_DAY' => gmdate('d'),
	'S_YEAR' => gmdate('Y'),
	'S_MODE' => $mode,
	'S_ORDER' => $order,
	'S_MODE_SELECT' => topic_shadow_make_drop_box('mode'),
	'S_ORDER_SELECT' => topic_shadow_make_drop_box('order'),
	'S_MODE_ACTION' => append_sid($_SERVER['SCRIPT_NAME'])
	)
);

/* See if we actually have any shadow topics */
$sql = "SELECT COUNT(topic_status) as count FROM " . TOPICS_TABLE . " WHERE topic_status = '" . TOPIC_MOVED . "'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
if ($row['count'] <= 0)
{
	$template->assign_block_vars('emptyrow', array());
}
else
{

	$sql = "SELECT * FROM " . TOPICS_TABLE . "
					WHERE topic_status = " . TOPIC_MOVED . "
					ORDER BY " . $db->sql_escape($mode) . " " . $db->sql_escape($order);
	$result = $db->sql_query($sql);

	$i = 0;
	while ($messages = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('topicrow', array(
			'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
			'TITLE' => $messages['topic_title'],
			'MOVED_TO' => ts_id_2_name($messages['topic_moved_id'], 'forum'),
			'MOVED_FROM' => ts_id_2_name($messages['topic_id'], 'forum'),
			'POSTER' => ts_id_2_name($messages['topic_poster'], 'user_formatted'),
			'TIME' => create_date($lang['DATE_FORMAT'], $messages['topic_time'], $config['board_timezone']),
			'TOPIC_ID' => $messages['topic_id']
			)
		);
		$i++;
	}
}

/*
***********************************************************************
* Begin The Version Check Feature
***********************************************************************
*/
if (file_exists(IP_ROOT_PATH . 'nivisec_version_check.' . PHP_EXT) && !DISABLE_VERSION_CHECK)
{
	include(IP_ROOT_PATH . 'nivisec_version_check.' . PHP_EXT);
}

/*
***********************************************************************
* End The Version Check Feature
***********************************************************************
*/

$template->pparse('body');
copyright_nivisec($meta_content['page_title'], '2001-2003');
include('page_footer_admin.' . PHP_EXT);

?>