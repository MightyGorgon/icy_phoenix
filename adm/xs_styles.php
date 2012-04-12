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
* Vjacheslav Trushkin (http://www.stsoftware.biz)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);

define('IN_XS', true);
include_once('xs_include.' . PHP_EXT);

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_styles.' . PHP_EXT) . '">' . $lang['xs_default_style'] . '</a>'));

// set new default style
$setdefault = request_var('setdefault', 0);
if(!empty($setdefault) && !defined('DEMO_MODE'))
{
	set_config('default_style', $setdefault, false);
}

// change "override" variable
$setoverride = request_var('setoverride', $config['override_user_style']);
if(($setoverride != $config['override_user_style']) && !defined('DEMO_MODE'))
{
	set_config('override_user_style', $setoverride, false);
}

// move all users to some style
$moveusers = request_var('moveusers', 0);
if(!empty($moveusers) && !defined('DEMO_MODE'))
{
	$sql = "UPDATE " . USERS_TABLE . " SET user_style = '" . $moveusers . "'";
	$db->sql_query($sql);
}

// move all users from some style
$moveaway = request_var('moveaway', 0);
$movestyle = request_var('movestyle', 0);
$movestyle = (!empty($movestyle) && (check_style_exists($movestyle) != false)) ? $movestyle : $config['default_style'];
if(!empty($moveaway) && !defined('DEMO_MODE'))
{
	$sql = "UPDATE " . USERS_TABLE . " SET user_style = '" . $movestyle . "' WHERE user_style = " . $moveaway;
	$db->sql_query($sql);
}

// get list of installed styles
$sql = 'SELECT themes_id, template_name, style_name FROM ' . THEMES_TABLE . ' ORDER BY template_name';
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if(!$result)
{
	xs_error($lang['xs_no_style_info'], __LINE__, __FILE__);
}
$style_rowset = $db->sql_fetchrowset($result);

$style_override = $config['override_user_style'];
$style_default = $config['default_style'];
$num_users = 0;
$style_ids = array();

for($i = 0; $i < sizeof($style_rowset); $i++)
{
	$id = $style_rowset[$i]['themes_id'];
	$style_ids[] = $id;
	$sql = 'SELECT count(user_id) as total FROM ' . USERS_TABLE . ' WHERE user_style = ' . $id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		$total = 0;
	}
	else
	{
		$total = $db->sql_fetchrow($result);
		$total = $total['total'];
		$num_users += $total;
	}

	$row_class = $xs_row_class[$i % 2];
	$template->assign_block_vars('styles', array(
		'ROW_CLASS' => $row_class,
		'STYLE' => $style_rowset[$i]['style_name'],
		'TEMPLATE' => $style_rowset[$i]['template_name'],
		'ID' => $id,
		'TOTAL' => $total,
		'U_TOTAL' => append_sid('xs_styles.' . PHP_EXT . '?list=' . $id),
		'U_DEFAULT' => append_sid('xs_styles.' . PHP_EXT . '?setdefault=' . $id),
		'U_OVERRIDE' => append_sid('xs_styles.' . PHP_EXT . '?setoverride=' . ($style_override ? '0' : '1')),
		'U_SWITCHALL' => append_sid('xs_styles.' . PHP_EXT . '?moveusers=' . $id),
		)
	);
	if($total > 0)
	{
		$template->assign_block_vars('styles.users', array());
	}
	if($id == $style_default)
	{
		$template->assign_block_vars('styles.default', array());
		if($style_override)
		{
			$template->assign_block_vars('styles.default.override', array());
		}
		else
		{
			$template->assign_block_vars('styles.default.nooverride', array());
		}
	}
	else
	{
		$template->assign_block_vars('styles.nodefault', array());
	}
	if($total)
	{
		$template->assign_block_vars('styles.total', array());
	}
	else
	{
		$template->assign_block_vars('styles.none', array());
	}
}

// get number of users using default style
$sql = 'SELECT count(user_id) as total FROM ' . USERS_TABLE . ' WHERE user_style = NULL';
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if($result)
{
	$total = $db->sql_fetchrow($result);
	$num_default = $total['total'];
	$num_users += $num_default;
}

// get number of users
$sql = 'SELECT count(user_id) as total FROM ' . USERS_TABLE;
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if(!$result)
{
	$total_users = 0;
}
else
{
	$total = $db->sql_fetchrow($result);
	$total_users = $total['total'];
}

$template->assign_vars(array(
	'U_SCRIPT' => 'xs_styles.' . PHP_EXT,
	'NUM_DEFAULT' => $num_default
	)
);

if($total_users > $num_users)
{
	// fix problem
	$sql = 'UPDATE ' . USERS_TABLE . ' SET user_style = NULL WHERE user_style NOT IN (' . implode(', ', $style_ids) . ')';
	$db->sql_query($sql);
}

// get list of users
$user_style_id = request_get_var('list', 0);
if(!empty($user_style_id))
{
	$template->assign_block_vars('list_users', array());
	$sql = "SELECT user_id, username FROM " . USERS_TABLE . " WHERE user_style = '{$user_style_id}' ORDER BY username ASC";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		xs_error('Could not get users list!', __LINE__, __FILE__);
	}
	$rowset = $db->sql_fetchrowset($result);
	for($i = 0; $i < sizeof($rowset); $i++)
	{
		$template->assign_block_vars('list_users.user', array(
			'NUM' => $i + 1,
			'ID' => $rowset[$i]['user_id'],
			'NAME' => htmlspecialchars($rowset[$i]['username']),
			)
		);
	}
}

$template->set_filenames(array('body' => XS_TPL_PATH . 'styles.tpl'));
$template->pparse('body');
xs_exit();

?>
