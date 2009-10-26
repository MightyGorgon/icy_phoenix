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
require('./pagestart.' . PHP_EXT);

// check if mod is installed
if(empty($template->xs_version) || $template->xs_version !== 8)
{
	message_die(GENERAL_ERROR, isset($lang['xs_error_not_installed']) ? $lang['xs_error_not_installed'] : 'eXtreme Styles mod is not installed. You forgot to upload includes/template.php');
}

define('IN_XS', true);
include_once('xs_include.' . PHP_EXT);

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_styles.' . PHP_EXT) . '">' . $lang['xs_default_style'] . '</a>'));

//
// set new default style
//
if(!empty($_GET['setdefault']) && !defined('DEMO_MODE'))
{
	$config['default_style'] = intval($_GET['setdefault']);
	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value='" . $config['default_style'] . "' WHERE config_name='default_style'";
	if(defined('XS_MODS_ADMIN_TEMPLATES'))
	{
		$sql = str_replace(' WHERE config_name', ', theme_public=\'1\' WHERE config_name', $sql);
	}
	$db->sql_query($sql);
}

//
// change "override" variable
//
if(isset($_GET['setoverride']) && !defined('DEMO_MODE'))
{
	$config['override_user_style'] = intval($_GET['setoverride']);
	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value='" . $config['override_user_style'] . "' WHERE config_name='override_user_style'";
	$db->sql_query($sql);
}

//
// move all users to some style
//
if(!empty($_GET['moveusers']) && !defined('DEMO_MODE'))
{
	$id = intval($_GET['moveusers']);
	$sql = "UPDATE " . USERS_TABLE . " SET user_style='" . $id . "' WHERE user_id > 0";
	$db->sql_query($sql);
}

//
// move all users from some style
//
if(!empty($_GET['moveaway']) && !defined('DEMO_MODE'))
{
	$id = intval($_GET['moveaway']);
	$id2 = intval($_GET['movestyle']);
	if($id2)
	{
		$sql = "UPDATE " . USERS_TABLE . " SET user_style='" . $id2 . "' WHERE user_style = " . $id;
	}
	else
	{
		$sql = "UPDATE " . USERS_TABLE . " SET user_style = NULL WHERE user_style = " . $id;
	}
	$db->sql_query($sql);
}

//
// set admin-only style (Admin Templates mod)
//
if(!empty($_GET['setadmin']) && !defined('DEMO_MODE'))
{
	$id = intval($_GET['setadmin']);
	$setadmin = empty($_GET['admin']) ? 0 : 1;
	$sql = "UPDATE " . THEMES_TABLE . " SET theme_public='{$setadmin}' WHERE themes_id='{$id}'";
	$db->sql_query($sql);
	if(defined('XS_MODS_CATEGORY_HIERARCHY210'))
	{
		// recache themes table
		if ( empty($themes) )
		{
			$themes = new themes();
		}
		if ( !empty($themes) )
		{
			$themes->read(true);
		}
	}
}

//
// get list of installed styles
//
$sql = 'SELECT themes_id, template_name, style_name FROM ' . THEMES_TABLE . ' ORDER BY template_name';
if(defined('XS_MODS_ADMIN_TEMPLATES'))
{
	$sql = str_replace(', style_name', ', style_name, theme_public', $sql);
}
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

for($i=0; $i< sizeof($style_rowset); $i++)
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
		'ROW_CLASS'			=> $row_class,
		'STYLE'				=> $style_rowset[$i]['style_name'],
		'TEMPLATE'			=> $style_rowset[$i]['template_name'],
		'ID'				=> $id,
		'TOTAL'				=> $total,
		'U_TOTAL'			=> append_sid('xs_styles.' . PHP_EXT . '?list=' . $id),
		'U_DEFAULT'			=> append_sid('xs_styles.' . PHP_EXT . '?setdefault=' . $id),
		'U_OVERRIDE'		=> append_sid('xs_styles.' . PHP_EXT . '?setoverride=' . ($style_override ? '0' : '1')),
		'U_SWITCHALL'		=> append_sid('xs_styles.' . PHP_EXT . '?moveusers=' . $id),
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
		if(defined('XS_MODS_ADMIN_TEMPLATES'))
		{
			if($style_rowset[$i]['theme_public'])
			{
				$template->assign_block_vars('styles.nodefault.admin_only', array(
					'U_CHANGE'	=> append_sid('xs_styles.' . PHP_EXT . '?setadmin='.$id.'&admin=0')
				));
			}
			else
			{
				$template->assign_block_vars('styles.nodefault.public', array(
					'U_CHANGE'	=> append_sid('xs_styles.' . PHP_EXT . '?setadmin='.$id.'&admin=1')
				));
			}
		}
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
	'U_SCRIPT'		=> 'xs_styles.' . PHP_EXT,
	'NUM_DEFAULT'	=> $num_default
	)
);

if($total_users > $num_users)
{
	// fix problem
	$sql = 'UPDATE ' . USERS_TABLE . ' SET user_style = NULL WHERE user_style NOT IN (' . implode(', ', $style_ids) . ')';
	$db->sql_query($sql);
}

//
// get list of users
//
if(isset($_GET['list']))
{
	$id = intval($_GET['list']);
	$template->assign_block_vars('list_users', array());
	$sql = "SELECT user_id, username FROM " . USERS_TABLE . " WHERE user_style='{$id}' ORDER BY username ASC";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		xs_error('Could not get users list!', __LINE__, __FILE__);
	}
	$rowset = $db->sql_fetchrowset($result);
	for($i=0; $i< sizeof($rowset); $i++)
	{
		$template->assign_block_vars('list_users.user', array(
			'NUM'		=> $i + 1,
			'ID'		=> $rowset[$i]['user_id'],
			'NAME'		=> htmlspecialchars($rowset[$i]['username']),
			)
		);
	}
}

$template->set_filenames(array('body' => XS_TPL_PATH . 'styles.tpl'));
$template->pparse('body');
xs_exit();

?>