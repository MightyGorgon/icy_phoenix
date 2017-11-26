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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Header for acp pages
*/
function adm_page_header($page_title)
{
	global $config, $db, $user, $template;
	global $phpbb_admin_path, $SID, $_SID;

	if (defined('HEADER_INC'))
	{
		return;
	}

	define('HEADER_INC', true);

	// gzip_compression
	if ($config['gzip_compress'])
	{
		if (@extension_loaded('zlib') && !headers_sent())
		{
			ob_start('ob_gzhandler');
		}
	}

	$template->assign_vars(array(
		'PAGE_TITLE' => $page_title,
		'USERNAME' => $user->data['username'],

		'SID' => $SID,
		'_SID' => $_SID,
		'SESSION_ID' => $user->session_id,
		'ROOT_PATH' => $phpbb_admin_path,

		'U_LOGOUT' => append_sid(IP_ROOT_PATH . "ucp." . PHP_EXT, 'mode=logout'),
		'U_ADM_LOGOUT' => append_sid("{$phpbb_admin_path}index." . PHP_EXT, 'action=admlogout'),
		'U_ADM_INDEX' => append_sid("{$phpbb_admin_path}index." . PHP_EXT),
		'U_INDEX' => append_sid(IP_ROOT_PATH . "index." . PHP_EXT),

		'T_IMAGES_PATH' => IP_ROOT_PATH . "images/",

		'ICON_MOVE_UP' => '<img src="' . $phpbb_admin_path . 'images/icon_up.gif" alt="' . $user->lang['MOVE_UP'] . '" title="' . $user->lang['MOVE_UP'] . '" />',
		'ICON_MOVE_UP_DISABLED' => '<img src="' . $phpbb_admin_path . 'images/icon_up_disabled.gif" alt="' . $user->lang['MOVE_UP'] . '" title="' . $user->lang['MOVE_UP'] . '" />',
		'ICON_MOVE_DOWN' => '<img src="' . $phpbb_admin_path . 'images/icon_down.gif" alt="' . $user->lang['MOVE_DOWN'] . '" title="' . $user->lang['MOVE_DOWN'] . '" />',
		'ICON_MOVE_DOWN_DISABLED' => '<img src="' . $phpbb_admin_path . 'images/icon_down_disabled.gif" alt="' . $user->lang['MOVE_DOWN'] . '" title="' . $user->lang['MOVE_DOWN'] . '" />',
		'ICON_EDIT' => '<img src="' . $phpbb_admin_path . 'images/icon_edit.gif" alt="' . $user->lang['EDIT'] . '" title="' . $user->lang['EDIT'] . '" />',
		'ICON_EDIT_DISABLED' => '<img src="' . $phpbb_admin_path . 'images/icon_edit_disabled.gif" alt="' . $user->lang['EDIT'] . '" title="' . $user->lang['EDIT'] . '" />',
		'ICON_DELETE' => '<img src="' . $phpbb_admin_path . 'images/icon_delete.gif" alt="' . $user->lang['DELETE'] . '" title="' . $user->lang['DELETE'] . '" />',
		'ICON_DELETE_DISABLED' => '<img src="' . $phpbb_admin_path . 'images/icon_delete_disabled.gif" alt="' . $user->lang['DELETE'] . '" title="' . $user->lang['DELETE'] . '" />',
		'ICON_SYNC' => '<img src="' . $phpbb_admin_path . 'images/icon_sync.gif" alt="' . $user->lang['RESYNC'] . '" title="' . $user->lang['RESYNC'] . '" />',
		'ICON_SYNC_DISABLED' => '<img src="' . $phpbb_admin_path . 'images/icon_sync_disabled.gif" alt="' . $user->lang['RESYNC'] . '" title="' . $user->lang['RESYNC'] . '" />',

		'S_USER_LANG' => $lang['USER_LANG'],
		'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
		'S_CONTENT_ENCODING' => 'UTF-8',
		'S_CONTENT_FLOW_BEGIN' => ($lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
		'S_CONTENT_FLOW_END' => ($lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
		)
	);

	// application/xhtml+xml not used because of IE
	header('Content-type: text/html; charset=UTF-8');

	header('Cache-Control: private, no-cache="set-cookie"');
	header('Expires: 0');
	header('Pragma: no-cache');

	return;
}

/**
* Page footer for acp pages
*/
function adm_page_footer($copyright_html = true)
{
	global $db, $config, $template, $user, $auth, $cache;
	global $starttime, $phpbb_admin_path;

	// Output page creation time
	if (defined('DEBUG'))
	{
		$mtime = explode(' ', microtime());
		$totaltime = $mtime[0] + $mtime[1] - $starttime;

		// Let's remove $auth->acl_get('a_') until I finish coding permissions properly... and also add/remove 'a_' when users are added/removed from administrators in ACP
		//$is_admin = (($user->data['user_level'] == ADMIN) || $auth->acl_get('a_')) ? true : false;
		$is_admin = ($user->data['user_level'] == ADMIN) ? true : false;

		if (!empty($_REQUEST['explain']) && $is_admin && defined('DEBUG_EXTRA') && DEBUG_EXTRA && method_exists($db, 'sql_report'))
		{
			$db->sql_report('display');
		}

		$debug_output = sprintf('Time : %.3fs | ' . $db->sql_num_queries() . ' Queries | GZIP : ' . (($config['gzip_compress']) ? 'On' : 'Off') . (($user->load) ? ' | Load : ' . $user->load : ''), $totaltime);

		if ($is_admin && defined('DEBUG_EXTRA') && DEBUG_EXTRA)
		{
			if (function_exists('memory_get_usage'))
			{
				if ($memory_usage = memory_get_usage())
				{
					global $base_memory_usage;
					$memory_usage -= $base_memory_usage;
					$memory_usage = get_formatted_filesize($memory_usage);

					$debug_output .= ' | Memory Usage: ' . $memory_usage;
				}
			}

			$debug_output .= ' | <a href="' . build_url() . '&amp;explain=1">Explain</a>';
		}
	}

	$template->assign_vars(array(
		'DEBUG_OUTPUT' => (defined('DEBUG')) ? $debug_output : '',
		'TRANSLATION_INFO' => (!empty($user->lang['TRANSLATION_INFO'])) ? $user->lang['TRANSLATION_INFO'] : '',
		'S_COPYRIGHT_HTML' => $copyright_html,
		'VERSION' => $config['version']
		)
	);

	$template->display('body');

	garbage_collection();
	exit_handler();
}

/**
* Build select field options in acp pages
*/
function build_select($option_ary, $option_default = false)
{
	global $user;

	$html = '';
	foreach ($option_ary as $value => $title)
	{
		$selected = (($option_default !== false) && ($value == $option_default)) ? ' selected="selected"' : '';
		$html .= '<option value="' . $value . '"' . $selected . '>' . $user->lang[$title] . '</option>';
	}

	return $html;
}

/**
* Build radio fields in acp pages
*/
function h_radio($name, &$input_ary, $input_default = false, $id = false, $key = false)
{
	global $user;

	$html = '';
	$id_assigned = false;
	foreach ($input_ary as $value => $title)
	{
		$selected = (($input_default !== false) && ($value == $input_default)) ? ' checked="checked"' : '';
		$html .= '<label><input type="radio" name="' . $name . '"' . (($id && !$id_assigned) ? ' id="' . $id . '"' : '') . ' value="' . $value . '"' . $selected . (($key) ? ' accesskey="' . $key . '"' : '') . ' class="radio" /> ' . $user->lang[$title] . '</label>';
		$id_assigned = true;
	}

	return $html;
}

/**
* Build configuration template for acp configuration pages
*/
function build_cfg_template($tpl_type, $key, &$new, $config_key, $vars)
{
	global $user, $module;

	$tpl = '';
	$name = 'config[' . $config_key . ']';

	// Make sure there is no notice printed out for non-existent config options (we simply set them)
	if (!isset($new[$config_key]))
	{
		$new[$config_key] = '';
	}

	switch ($tpl_type[0])
	{
		case 'text':
		case 'password':
			$size = (int) $tpl_type[1];
			$maxlength = (int) $tpl_type[2];

			$tpl = '<input id="' . $key . '" type="' . $tpl_type[0] . '"' . (($size) ? ' size="' . $size . '"' : '') . ' maxlength="' . (($maxlength) ? $maxlength : 255) . '" name="' . $name . '" value="' . $new[$config_key] . '" />';
		break;

		case 'dimension':
			$size = (int) $tpl_type[1];
			$maxlength = (int) $tpl_type[2];

			$tpl = '<input id="' . $key . '" type="text"' . (($size) ? ' size="' . $size . '"' : '') . ' maxlength="' . (($maxlength) ? $maxlength : 255) . '" name="config[' . $config_key . '_width]" value="' . $new[$config_key . '_width'] . '" /> x <input type="text"' . (($size) ? ' size="' . $size . '"' : '') . ' maxlength="' . (($maxlength) ? $maxlength : 255) . '" name="config[' . $config_key . '_height]" value="' . $new[$config_key . '_height'] . '" />';
		break;

		case 'textarea':
			$rows = (int) $tpl_type[1];
			$cols = (int) $tpl_type[2];

			$tpl = '<textarea id="' . $key . '" name="' . $name . '" rows="' . $rows . '" cols="' . $cols . '">' . $new[$config_key] . '</textarea>';
		break;

		case 'radio':
			$key_yes = ($new[$config_key]) ? ' checked="checked"' : '';
			$key_no = (!$new[$config_key]) ? ' checked="checked"' : '';

			$tpl_type_cond = explode('_', $tpl_type[1]);
			$type_no = ($tpl_type_cond[0] == 'disabled' || $tpl_type_cond[0] == 'enabled') ? false : true;

			$tpl_no = '<label><input type="radio" name="' . $name . '" value="0"' . $key_no . ' class="radio" /> ' . (($type_no) ? $user->lang['NO'] : $user->lang['DISABLED']) . '</label>';
			$tpl_yes = '<label><input type="radio" id="' . $key . '" name="' . $name . '" value="1"' . $key_yes . ' class="radio" /> ' . (($type_no) ? $user->lang['YES'] : $user->lang['ENABLED']) . '</label>';

			$tpl = ($tpl_type_cond[0] == 'yes' || $tpl_type_cond[0] == 'enabled') ? $tpl_yes . $tpl_no : $tpl_no . $tpl_yes;
		break;

		case 'select':
		case 'custom':

			$return = '';

			if (isset($vars['method']))
			{
				$call = array($module->module, $vars['method']);
			}
			elseif (isset($vars['function']))
			{
				$call = $vars['function'];
			}
			else
			{
				break;
			}

			if (isset($vars['params']))
			{
				$args = array();
				foreach ($vars['params'] as $value)
				{
					switch ($value)
					{
						case '{CONFIG_VALUE}':
							$value = $new[$config_key];
						break;

						case '{KEY}':
							$value = $key;
						break;
					}

					$args[] = $value;
				}
			}
			else
			{
				$args = array($new[$config_key], $key);
			}

			$return = call_user_func_array($call, $args);

			if ($tpl_type[0] == 'select')
			{
				$tpl = '<select id="' . $key . '" name="' . $name . '">' . $return . '</select>';
			}
			else
			{
				$tpl = $return;
			}

		break;

		default:
		break;
	}

	if (isset($vars['append']))
	{
		$tpl .= $vars['append'];
	}

	return $tpl;
}

/**
* Going through a config array and validate values, writing errors to $error. The validation method  accepts parameters separated by ':' for string and int.
* The first parameter defines the type to be used, the second the lower bound and the third the upper bound. Only the type is required.
*/
function validate_config_vars($config_vars, &$cfg_array, &$error)
{
	global $user;

	$type = 0;
	$min = 1;
	$max = 2;

	foreach ($config_vars as $config_name => $config_definition)
	{
		if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
		{
			continue;
		}

		if (!isset($config_definition['validate']))
		{
			continue;
		}

		$validator = explode(':', $config_definition['validate']);

		// Validate a bit. ;) (0 = type, 1 = min, 2= max)
		switch ($validator[$type])
		{
			case 'string':
				$length = strlen($cfg_array[$config_name]);

				// the column is a VARCHAR
				$validator[$max] = (isset($validator[$max])) ? min(255, $validator[$max]) : 255;

				if (isset($validator[$min]) && $length < $validator[$min])
				{
					$error[] = sprintf($user->lang['SETTING_TOO_SHORT'], $user->lang[$config_definition['lang']], $validator[$min]);
				}
				elseif (isset($validator[$max]) && $length > $validator[2])
				{
					$error[] = sprintf($user->lang['SETTING_TOO_LONG'], $user->lang[$config_definition['lang']], $validator[$max]);
				}
			break;

			case 'bool':
				$cfg_array[$config_name] = ($cfg_array[$config_name]) ? 1 : 0;
			break;

			case 'int':
				$cfg_array[$config_name] = (int) $cfg_array[$config_name];

				if (isset($validator[$min]) && ($cfg_array[$config_name] < $validator[$min]))
				{
					$error[] = sprintf($user->lang['SETTING_TOO_LOW'], $user->lang[$config_definition['lang']], $validator[$min]);
				}
				elseif (isset($validator[$max]) && ($cfg_array[$config_name] > $validator[$max]))
				{
					$error[] = sprintf($user->lang['SETTING_TOO_BIG'], $user->lang[$config_definition['lang']], $validator[$max]);
				}

				if (strpos($config_name, '_max') !== false)
				{
					// Min/max pairs of settings should ensure that min <= max
					// Replace _max with _min to find the name of the minimum
					// corresponding configuration variable
					$min_name = str_replace('_max', '_min', $config_name);

					if (isset($cfg_array[$min_name]) && is_numeric($cfg_array[$min_name]) && ($cfg_array[$config_name] < $cfg_array[$min_name]))
					{
						// A minimum value exists and the maximum value is less than it
						$error[] = sprintf($user->lang['SETTING_TOO_LOW'], $user->lang[$config_definition['lang']], (int) $cfg_array[$min_name]);
					}
				}
			break;

			// Absolute path
			case 'script_path':
				if (!$cfg_array[$config_name])
				{
					break;
				}

				$destination = str_replace('\\', '/', $cfg_array[$config_name]);

				if ($destination !== '/')
				{
					// Adjust destination path (no trailing slash)
					if (substr($destination, -1, 1) == '/')
					{
						$destination = substr($destination, 0, -1);
					}

					$destination = str_replace(array('../', './'), '', $destination);

					if ($destination[0] != '/')
					{
						$destination = '/' . $destination;
					}
				}

				$cfg_array[$config_name] = trim($destination);

			break;

			// Absolute path
			case 'lang':
				if (!$cfg_array[$config_name])
				{
					break;
				}

				$cfg_array[$config_name] = basename($cfg_array[$config_name]);

				if (!file_exists(IP_ROOT_PATH . 'language/' . $cfg_array[$config_name] . '/'))
				{
					$error[] = $user->lang['WRONG_DATA_LANG'];
				}
			break;

			// Relative path (appended IP_ROOT_PATH)
			case 'rpath':
			case 'rwpath':
				if (!$cfg_array[$config_name])
				{
					break;
				}

				$destination = $cfg_array[$config_name];

				// Adjust destination path (no trailing slash)
				if ((substr($destination, -1, 1) == '/') || (substr($destination, -1, 1) == '\\'))
				{
					$destination = substr($destination, 0, -1);
				}

				$destination = str_replace(array('../', '..\\', './', '.\\'), '', $destination);
				if ($destination && ($destination[0] == '/' || $destination[0] == "\\"))
				{
					$destination = '';
				}

				$cfg_array[$config_name] = trim($destination);

			// Path being relative (still prefixed by phpbb_root_path), but with the ability to escape the root dir...
			case 'path':
			case 'wpath':

				if (!$cfg_array[$config_name])
				{
					break;
				}

				$cfg_array[$config_name] = trim($cfg_array[$config_name]);

				// Make sure no NUL byte is present...
				if ((strpos($cfg_array[$config_name], "\0") !== false) || (strpos($cfg_array[$config_name], '%00') !== false))
				{
					$cfg_array[$config_name] = '';
					break;
				}

				if (!file_exists(IP_ROOT_PATH . $cfg_array[$config_name]))
				{
					$error[] = sprintf($user->lang['DIRECTORY_DOES_NOT_EXIST'], $cfg_array[$config_name]);
				}

				if (file_exists(IP_ROOT_PATH . $cfg_array[$config_name]) && !is_dir(IP_ROOT_PATH . $cfg_array[$config_name]))
				{
					$error[] = sprintf($user->lang['DIRECTORY_NOT_DIR'], $cfg_array[$config_name]);
				}

				// Check if the path is writable
				if (($config_definition['validate'] == 'wpath') || ($config_definition['validate'] == 'rwpath'))
				{
					if (file_exists(IP_ROOT_PATH . $cfg_array[$config_name]) && !phpbb_is_writable(IP_ROOT_PATH . $cfg_array[$config_name]))
					{
						$error[] = sprintf($user->lang['DIRECTORY_NOT_WRITABLE'], $cfg_array[$config_name]);
					}
				}

			break;
		}
	}

	return;
}

/**
* Checks whatever or not a variable is OK for use in the Database
* param mixed $value_ary An array of the form array(array('lang' => ..., 'value' => ..., 'column_type' =>))'
* param mixed $error The error array
*/
function validate_range($value_ary, &$error)
{
	global $user;

	$column_types = array(
		'BOOL' => array('php_type' => 'int', 'min' => 0, 'max' => 1),
		'USINT' => array('php_type' => 'int', 'min' => 0, 'max' => 65535),
		'UINT' => array('php_type' => 'int', 'min' => 0, 'max' => (int) 0x7fffffff),
		'INT' => array('php_type' => 'int', 'min' => (int) 0x80000000, 'max' => (int) 0x7fffffff),
		'TINT' => array('php_type' => 'int', 'min' => -128, 'max' => 127),

		'VCHAR' => array('php_type' => 'string', 'min' => 0, 'max' => 255),
	);
	foreach ($value_ary as $value)
	{
		$column = explode(':', $value['column_type']);
		$max = $min = 0;
		$type = 0;
		if (!isset($column_types[$column[0]]))
		{
			continue;
		}
		else
		{
			$type = $column_types[$column[0]];
		}

		switch ($type['php_type'])
		{
			case 'string' :
				$max = (isset($column[1])) ? min($column[1], $type['max']) : $type['max'];
				if (strlen($value['value']) > $max)
				{
					$error[] = sprintf($user->lang['SETTING_TOO_LONG'], $user->lang[$value['lang']], $max);
				}
			break;

			case 'int':
				$min = (isset($column[1])) ? max($column[1], $type['min']) : $type['min'];
				$max = (isset($column[2])) ? min($column[2], $type['max']) : $type['max'];
				if ($value['value'] < $min)
				{
					$error[] = sprintf($user->lang['SETTING_TOO_LOW'], $user->lang[$value['lang']], $min);
				}
				elseif ($value['value'] > $max)
				{
					$error[] = sprintf($user->lang['SETTING_TOO_BIG'], $user->lang[$value['lang']], $max);
				}
			break;
		}
	}
}

/**
* Recalculate Nested Sets
*
* @param int $new_id first left_id (should start with 1)
* @param string $pkey primary key-column (containing the id for the parent_id of the children)
* @param string $table constant or fullname of the table
* @param int $parent_id parent_id of the current set (default = 0)
* @param array $where contains strings to compare closer on the where statement (additional)
*
* @author EXreaction
*/
function recalc_nested_sets(&$new_id, $pkey, $table, $parent_id = 0, $where = array())
{
	global $db;

	$sql = 'SELECT *
		FROM ' . $table . '
		WHERE parent_id = ' . (int) $parent_id .
		((!empty($where)) ? ' AND ' . implode(' AND ', $where) : '') . '
		ORDER BY left_id ASC';
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		// First we update the left_id for this module
		if ($row['left_id'] != $new_id)
		{
			$db->sql_query('UPDATE ' . $table . ' SET ' . $db->sql_build_array('UPDATE', array('left_id' => $new_id)) . " WHERE $pkey = {$row[$pkey]}");
		}
		$new_id++;

		// Then we go through any children and update their left/right id's
		recalc_nested_sets($new_id, $pkey, $table, $row[$pkey], $where);

		// Then we come back and update the right_id for this module
		if ($row['right_id'] != $new_id)
		{
			$db->sql_query('UPDATE ' . $table . ' SET ' . $db->sql_build_array('UPDATE', array('right_id' => $new_id)) . " WHERE $pkey = {$row[$pkey]}");
		}
		$new_id++;
	}
	$db->sql_freeresult($result);
}

/**
* Simple version of jumpbox, just lists authed forums
*/
function make_forum_select($select_id = false, $ignore_id = false, $ignore_acl = false, $ignore_nonpost = false, $ignore_emptycat = true, $only_acl_post = false, $return_array = false)
{
	global $db, $user, $auth;

	// This query is identical to the jumpbox one
	$sql = 'SELECT forum_id, forum_name, parent_id, forum_type, forum_flags, forum_options, left_id, right_id
		FROM ' . FORUMS_TABLE . '
		ORDER BY left_id ASC';
	$result = $db->sql_query($sql, 600);

	$right = 0;
	$padding_store = array('0' => '');
	$padding = '';
	$forum_list = ($return_array) ? array() : '';

	// Sometimes it could happen that forums will be displayed here not be displayed within the index page
	// This is the result of forums not displayed at index, having list permissions and a parent of a forum with no permissions.
	// If this happens, the padding could be "broken"

	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['left_id'] < $right)
		{
			$padding .= '&nbsp; &nbsp;';
			$padding_store[$row['parent_id']] = $padding;
		}
		elseif ($row['left_id'] > $right + 1)
		{
			$padding = (isset($padding_store[$row['parent_id']])) ? $padding_store[$row['parent_id']] : '';
		}

		$right = $row['right_id'];
		$disabled = false;

		if (!$ignore_acl && $auth->acl_gets(array('f_list', 'a_forum', 'a_forumadd', 'a_forumdel'), $row['forum_id']))
		{
			if ($only_acl_post && !$auth->acl_get('f_post', $row['forum_id']) || (!$auth->acl_get('m_approve', $row['forum_id']) && !$auth->acl_get('f_noapprove', $row['forum_id'])))
			{
				$disabled = true;
			}
		}
		elseif (!$ignore_acl)
		{
			continue;
		}

		if (
			((is_array($ignore_id) && in_array($row['forum_id'], $ignore_id)) || $row['forum_id'] == $ignore_id)
			||
			// Non-postable forum with no subforums, don't display
			($row['forum_type'] == FORUM_CAT && ($row['left_id'] + 1 == $row['right_id']) && $ignore_emptycat)
			||
			($row['forum_type'] != FORUM_POST && $ignore_nonpost)
			)
		{
			$disabled = true;
		}

		if ($return_array)
		{
			// Include some more information...
			$selected = (is_array($select_id)) ? ((in_array($row['forum_id'], $select_id)) ? true : false) : (($row['forum_id'] == $select_id) ? true : false);
			$forum_list[$row['forum_id']] = array_merge(array('padding' => $padding, 'selected' => ($selected && !$disabled), 'disabled' => $disabled), $row);
		}
		else
		{
			$selected = (is_array($select_id)) ? ((in_array($row['forum_id'], $select_id)) ? ' selected="selected"' : '') : (($row['forum_id'] == $select_id) ? ' selected="selected"' : '');
			$forum_list .= '<option value="' . $row['forum_id'] . '"' . (($disabled) ? ' disabled="disabled" class="disabled-option"' : $selected) . '>' . $padding . $row['forum_name'] . '</option>';
		}
	}
	$db->sql_freeresult($result);
	unset($padding_store);

	return $forum_list;
}

/**
* Generate size select options
*/
function size_select_options($size_compare)
{
	global $user;

	$size_types_text = array($user->lang['BYTES'], $user->lang['KIB'], $user->lang['MIB']);
	$size_types = array('b', 'kb', 'mb');

	$s_size_options = '';

	for ($i = 0, $size = sizeof($size_types_text); $i < $size; $i++)
	{
		$selected = ($size_compare == $size_types[$i]) ? ' selected="selected"' : '';
		$s_size_options .= '<option value="' . $size_types[$i] . '"' . $selected . '>' . $size_types_text[$i] . '</option>';
	}

	return $s_size_options;
}

/**
* Obtain either the members of a specified group, the groups the specified user is subscribed to
* or checking if a specified user is in a specified group. This function does not return pending memberships.
*
* Note: Never use this more than once... first group your users/groups
*/
function group_memberships($group_id_ary = false, $user_id_ary = false, $return_bool = false)
{
	global $db;

	if (!$group_id_ary && !$user_id_ary)
	{
		return true;
	}

	if ($user_id_ary)
	{
		$user_id_ary = (!is_array($user_id_ary)) ? array($user_id_ary) : $user_id_ary;
	}

	if ($group_id_ary)
	{
		$group_id_ary = (!is_array($group_id_ary)) ? array($group_id_ary) : $group_id_ary;
	}

	$sql = 'SELECT ug.*, u.username, u.username_clean, u.user_email
		FROM ' . USER_GROUP_TABLE . ' ug, ' . USERS_TABLE . ' u
		WHERE ug.user_id = u.user_id
			AND ug.user_pending = 0 AND ';

	if ($group_id_ary)
	{
		$sql .= ' ' . $db->sql_in_set('ug.group_id', $group_id_ary);
	}

	if ($user_id_ary)
	{
		$sql .= ($group_id_ary) ? ' AND ' : ' ';
		$sql .= $db->sql_in_set('ug.user_id', $user_id_ary);
	}

	$result = ($return_bool) ? $db->sql_query_limit($sql, 1) : $db->sql_query($sql);

	$row = $db->sql_fetchrow($result);

	if ($return_bool)
	{
		$db->sql_freeresult($result);
		return ($row) ? true : false;
	}

	if (!$row)
	{
		return false;
	}

	$return = array();

	do
	{
		$return[] = $row;
	}
	while ($row = $db->sql_fetchrow($result));

	$db->sql_freeresult($result);

	return $return;
}

/**
* Generate list of groups (option fields without select)
*
* @param int $group_id The default group id to mark as selected
* @param array $exclude_ids The group ids to exclude from the list, false (default) if you whish to exclude no id
* @param int $manage_founder If set to false (default) all groups are returned, if 0 only those groups returned not being managed by founders only, if 1 only those groups returned managed by founders only.
*
* @return string The list of options.
*/
function group_select_options($group_id, $exclude_ids = false, $manage_founder = false)
{
	global $db, $config, $user;

	$exclude_sql = ($exclude_ids !== false && sizeof($exclude_ids)) ? 'WHERE ' . $db->sql_in_set('group_id', array_map('intval', $exclude_ids), true) : '';
	$sql_and = (($exclude_sql || $sql_and) ? ' AND ' : ' WHERE ') . ' group_single_user = 0 ';
	$sql_founder = '';

	$sql = 'SELECT group_id, group_name
		FROM ' . GROUPS_TABLE . "
		$exclude_sql
		$sql_and
		$sql_founder
		ORDER BY group_name ASC";
	$result = $db->sql_query($sql);

	$s_group_options = '';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ($row['group_id'] == $group_id) ? ' selected="selected"' : '';
		$s_group_options .= '<option value="' . $row['group_id'] . '"' . $selected . '>' . $row['group_name'] . '</option>';
	}
	$db->sql_freeresult($result);

	return $s_group_options;
}

/**
* Obtain authed forums list
*/
function get_forum_list($acl_list = 'f_list', $id_only = true, $postable_only = false, $no_cache = false)
{
	global $db, $auth;
	static $forum_rows;

	if (!isset($forum_rows))
	{
		// This query is identical to the jumpbox one
		$expire_time = ($no_cache) ? 0 : 600;

		$sql = 'SELECT forum_id, forum_name, parent_id, forum_type, left_id, right_id
			FROM ' . FORUMS_TABLE . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql, $expire_time);

		$forum_rows = array();

		$right = $padding = 0;
		$padding_store = array('0' => 0);

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['left_id'] < $right)
			{
				$padding++;
				$padding_store[$row['parent_id']] = $padding;
			}
			elseif ($row['left_id'] > $right + 1)
			{
				// Ok, if the $padding_store for this parent is empty there is something wrong. For now we will skip over it.
				// @todo digging deep to find out "how" this can happen.
				$padding = (isset($padding_store[$row['parent_id']])) ? $padding_store[$row['parent_id']] : $padding;
			}

			$right = $row['right_id'];
			$row['padding'] = $padding;

			$forum_rows[] = $row;
		}
		$db->sql_freeresult($result);
		unset($padding_store);
	}

	$rowset = array();
	foreach ($forum_rows as $row)
	{
		if ($postable_only && $row['forum_type'] != FORUM_POST)
		{
			continue;
		}

		if ($acl_list == '' || ($acl_list != '' && $auth->acl_gets($acl_list, $row['forum_id'])))
		{
			$rowset[] = ($id_only) ? (int) $row['forum_id'] : $row;
		}
	}

	return $rowset;
}

/**
* Get forum branch
*/
function get_forum_branch($forum_id, $type = 'all', $order = 'descending', $include_forum = true)
{
	global $db;

	switch ($type)
	{
		case 'parents':
			$condition = 'f1.left_id BETWEEN f2.left_id AND f2.right_id';
		break;

		case 'children':
			$condition = 'f2.left_id BETWEEN f1.left_id AND f1.right_id';
		break;

		default:
			$condition = 'f2.left_id BETWEEN f1.left_id AND f1.right_id OR f1.left_id BETWEEN f2.left_id AND f2.right_id';
		break;
	}

	$rows = array();

	$sql = 'SELECT f2.*
		FROM ' . FORUMS_TABLE . ' f1
		LEFT JOIN ' . FORUMS_TABLE . " f2 ON ($condition)
		WHERE f1.forum_id = $forum_id
		ORDER BY f2.left_id " . (($order == 'descending') ? 'ASC' : 'DESC');
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		if (!$include_forum && $row['forum_id'] == $forum_id)
		{
			continue;
		}

		$rows[] = $row;
	}
	$db->sql_freeresult($result);

	return $rows;
}

/**
* Copies permissions from one forum to others
*
* @param int $src_forum_id The source forum we want to copy permissions from
* @param array $dest_forum_ids The destination forum(s) we want to copy to
* @param bool $clear_dest_perms True if destination permissions should be deleted
* @param bool $add_log True if log entry should be added
*
* @return bool False on error
*
* @author bantu
*/
function copy_forum_permissions($src_forum_id, $dest_forum_ids, $clear_dest_perms = true, $add_log = true)
{
	global $db;

	// Only one forum id specified
	if (!is_array($dest_forum_ids))
	{
		$dest_forum_ids = array($dest_forum_ids);
	}

	// Make sure forum ids are integers
	$src_forum_id = (int) $src_forum_id;
	$dest_forum_ids = array_map('intval', $dest_forum_ids);

	// No source forum or no destination forums specified
	if (empty($src_forum_id) || empty($dest_forum_ids))
	{
		return false;
	}

	// Check if source forum exists
	$sql = 'SELECT forum_name
		FROM ' . FORUMS_TABLE . '
		WHERE forum_id = ' . $src_forum_id;
	$result = $db->sql_query($sql);
	$src_forum_name = $db->sql_fetchfield('forum_name');
	$db->sql_freeresult($result);

	// Source forum doesn't exist
	if (empty($src_forum_name))
	{
		return false;
	}

	// Check if destination forums exists
	$sql = 'SELECT forum_id, forum_name
		FROM ' . FORUMS_TABLE . '
		WHERE ' . $db->sql_in_set('forum_id', $dest_forum_ids);
	$result = $db->sql_query($sql);

	$dest_forum_ids = $dest_forum_names = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$dest_forum_ids[] = (int) $row['forum_id'];
		$dest_forum_names[] = $row['forum_name'];
	}
	$db->sql_freeresult($result);

	// No destination forum exists
	if (empty($dest_forum_ids))
	{
		return false;
	}

	// From the mysql documentation:
	// Prior to MySQL 4.0.14, the target table of the INSERT statement cannot appear
	// in the FROM clause of the SELECT part of the query. This limitation is lifted in 4.0.14.
	// Due to this we stay on the safe side if we do the insertion "the manual way"

	// Rowsets we're going to insert
	$users_sql_ary = $groups_sql_ary = array();

	// Query acl users table for source forum data
	$sql = 'SELECT user_id, auth_option_id, auth_role_id, auth_setting
		FROM ' . ACL_USERS_TABLE . '
		WHERE forum_id = ' . $src_forum_id;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$row = array(
			'user_id' => (int) $row['user_id'],
			'auth_option_id' => (int) $row['auth_option_id'],
			'auth_role_id' => (int) $row['auth_role_id'],
			'auth_setting' => (int) $row['auth_setting'],
		);

		foreach ($dest_forum_ids as $dest_forum_id)
		{
			$users_sql_ary[] = $row + array('forum_id' => $dest_forum_id);
		}
	}
	$db->sql_freeresult($result);

	// Query acl groups table for source forum data
	$sql = 'SELECT group_id, auth_option_id, auth_role_id, auth_setting
		FROM ' . ACL_GROUPS_TABLE . '
		WHERE forum_id = ' . $src_forum_id;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$row = array(
			'group_id' => (int) $row['group_id'],
			'auth_option_id' => (int) $row['auth_option_id'],
			'auth_role_id' => (int) $row['auth_role_id'],
			'auth_setting' => (int) $row['auth_setting'],
		);

		foreach ($dest_forum_ids as $dest_forum_id)
		{
			$groups_sql_ary[] = $row + array('forum_id' => $dest_forum_id);
		}
	}
	$db->sql_freeresult($result);

	$db->sql_transaction('begin');

	// Clear current permissions of destination forums
	if ($clear_dest_perms)
	{
		$sql = 'DELETE FROM ' . ACL_USERS_TABLE . '
			WHERE ' . $db->sql_in_set('forum_id', $dest_forum_ids);
		$db->sql_query($sql);

		$sql = 'DELETE FROM ' . ACL_GROUPS_TABLE . '
			WHERE ' . $db->sql_in_set('forum_id', $dest_forum_ids);
		$db->sql_query($sql);
	}

	$db->sql_multi_insert(ACL_USERS_TABLE, $users_sql_ary);
	$db->sql_multi_insert(ACL_GROUPS_TABLE, $groups_sql_ary);

	if ($add_log)
	{
		add_log('admin', 'LOG_FORUM_COPIED_PERMISSIONS', $src_forum_name, implode(', ', $dest_forum_names));
	}

	$db->sql_transaction('commit');

	return true;
}

/**
* Get physical file listing
*/
function filelist($rootdir, $dir = '', $type = 'gif|jpg|jpeg|png')
{
	$matches = array($dir => array());

	// Remove initial / if present
	$rootdir = (substr($rootdir, 0, 1) == '/') ? substr($rootdir, 1) : $rootdir;
	// Add closing / if not present
	$rootdir = ($rootdir && substr($rootdir, -1) != '/') ? $rootdir . '/' : $rootdir;

	// Remove initial / if present
	$dir = (substr($dir, 0, 1) == '/') ? substr($dir, 1) : $dir;
	// Add closing / if not present
	$dir = ($dir && substr($dir, -1) != '/') ? $dir . '/' : $dir;

	if (!is_dir($rootdir . $dir))
	{
		return $matches;
	}

	$dh = @opendir($rootdir . $dir);

	if (!$dh)
	{
		return $matches;
	}

	while (($fname = readdir($dh)) !== false)
	{
		if (is_file("$rootdir$dir$fname"))
		{
			if (filesize("$rootdir$dir$fname") && preg_match('#\.' . $type . '$#i', $fname))
			{
				$matches[$dir][] = $fname;
			}
		}
		elseif ($fname[0] != '.' && is_dir("$rootdir$dir$fname"))
		{
			$matches += filelist($rootdir, "$dir$fname", $type);
		}
	}
	closedir($dh);

	return $matches;
}

/**
* Cache moderators, called whenever permissions are changed via admin_permissions. Changes of username
* and group names must be carried through for the moderators table
*/
function cache_moderators()
{
	global $db, $cache, $auth;

	// Remove cached sql results
	$cache->destroy('sql', MODERATOR_CACHE_TABLE);
	$db->sql_query('TRUNCATE TABLE ' . MODERATOR_CACHE_TABLE);

	// We add moderators who have forum moderator permissions without an explicit ACL_NEVER setting
	$hold_ary = $ug_id_ary = $sql_ary = array();

	// Grab all users having moderative options...
	$hold_ary = $auth->acl_user_raw_data(false, 'm_%', false);

	// Add users?
	if (sizeof($hold_ary))
	{
		// At least one moderative option warrants a display
		$ug_id_ary = array_keys($hold_ary);

		// Remove users who have group memberships with DENY moderator permissions
		$sql = $db->sql_build_query('SELECT', array(
			'SELECT' => 'a.forum_id, ug.user_id, g.group_id',

			'FROM' => array(
				ACL_OPTIONS_TABLE => 'o',
				USER_GROUP_TABLE => 'ug',
				GROUPS_TABLE => 'g',
				ACL_GROUPS_TABLE => 'a',
			),

			'LEFT_JOIN' => array(
				array(
					'FROM' => array(ACL_ROLES_DATA_TABLE => 'r'),
					'ON' => 'a.auth_role_id = r.role_id'
				)
			),

			'WHERE' => '(o.auth_option_id = a.auth_option_id OR o.auth_option_id = r.auth_option_id)
				AND ((a.auth_setting = ' . ACL_NEVER . ' AND r.auth_setting IS NULL)
					OR r.auth_setting = ' . ACL_NEVER . ')
				AND a.group_id = ug.group_id
				AND g.group_id = ug.group_id
				AND NOT (ug.group_leader = 1 AND g.group_skip_auth = 1)
				AND ' . $db->sql_in_set('ug.user_id', $ug_id_ary) . "
				AND ug.user_pending = 0
				AND o.auth_option " . $db->sql_like_expression('m_' . $db->any_char),
		));
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if (isset($hold_ary[$row['user_id']][$row['forum_id']]))
			{
				unset($hold_ary[$row['user_id']][$row['forum_id']]);
			}
		}
		$db->sql_freeresult($result);

		if (sizeof($hold_ary))
		{
			// Get usernames...
			$sql = 'SELECT user_id, username
				FROM ' . USERS_TABLE . '
				WHERE ' . $db->sql_in_set('user_id', array_keys($hold_ary));
			$result = $db->sql_query($sql);

			$usernames_ary = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$usernames_ary[$row['user_id']] = $row['username'];
			}

			foreach ($hold_ary as $user_id => $forum_id_ary)
			{
				// Do not continue if user does not exist
				if (!isset($usernames_ary[$user_id]))
				{
					continue;
				}

				foreach ($forum_id_ary as $forum_id => $auth_ary)
				{
					$sql_ary[] = array(
						'forum_id' => (int) $forum_id,
						'user_id' => (int) $user_id,
						'username' => (string) $usernames_ary[$user_id],
						'group_id' => 0,
						'group_name' => ''
					);
				}
			}
		}
	}

	// Now to the groups...
	$hold_ary = $auth->acl_group_raw_data(false, 'm_%', false);

	if (sizeof($hold_ary))
	{
		$ug_id_ary = array_keys($hold_ary);

		// Make sure not hidden or special groups are involved...
		$sql = 'SELECT group_name, group_id, group_type
			FROM ' . GROUPS_TABLE . '
			WHERE ' . $db->sql_in_set('group_id', $ug_id_ary);
		$result = $db->sql_query($sql);

		$groupnames_ary = array();
		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['group_type'] == GROUP_HIDDEN || $row['group_type'] == GROUP_SPECIAL)
			{
				unset($hold_ary[$row['group_id']]);
			}

			$groupnames_ary[$row['group_id']] = $row['group_name'];
		}
		$db->sql_freeresult($result);

		foreach ($hold_ary as $group_id => $forum_id_ary)
		{
			// If there is no group, we do not assign it...
			if (!isset($groupnames_ary[$group_id]))
			{
				continue;
			}

			foreach ($forum_id_ary as $forum_id => $auth_ary)
			{
				$flag = false;
				foreach ($auth_ary as $auth_option => $setting)
				{
					// Make sure at least one ACL_YES option is set...
					if ($setting == ACL_YES)
					{
						$flag = true;
						break;
					}
				}

				if (!$flag)
				{
					continue;
				}

				$sql_ary[] = array(
					'forum_id' => (int) $forum_id,
					'user_id' => 0,
					'username' => '',
					'group_id' => (int) $group_id,
					'group_name' => (string) $groupnames_ary[$group_id]
				);
			}
		}
	}

	$db->sql_multi_insert(MODERATOR_CACHE_TABLE, $sql_ary);
}

/**
* Re-cache moderators and foes if group has a_ or m_ permissions
*/
function group_update_listings($group_id)
{
	global $auth;

	$hold_ary = $auth->acl_group_raw_data($group_id, array('a_', 'm_'));

	if (!sizeof($hold_ary))
	{
		return;
	}

	$mod_permissions = $admin_permissions = false;

	foreach ($hold_ary as $g_id => $forum_ary)
	{
		foreach ($forum_ary as $forum_id => $auth_ary)
		{
			foreach ($auth_ary as $auth_option => $setting)
			{
				if ($mod_permissions && $admin_permissions)
				{
					break 3;
				}

				if ($setting != ACL_YES)
				{
					continue;
				}

				if ($auth_option == 'm_')
				{
					$mod_permissions = true;
				}

				if ($auth_option == 'a_')
				{
					$admin_permissions = true;
				}
			}
		}
	}

	if ($mod_permissions)
	{
		cache_moderators();
	}
}

/**
* Get database size
* Currently only mysql and mssql are supported
*/
function get_database_size()
{
	global $db, $user, $lang, $table_prefix;

	$database_size = false;

	$sql = 'SELECT VERSION() AS mysql_version';
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	if ($row)
	{
		$version = $row['mysql_version'];

		if (preg_match('#(3\.23|[45]\.)#', $version))
		{
			$db_name = (preg_match('#^(?:3\.23\.(?:[6-9]|[1-9]{2}))|[45]\.#', $version)) ? "`{$db->dbname}`" : $db->dbname;

			$sql = 'SHOW TABLE STATUS
				FROM ' . $db_name;
			$result = $db->sql_query($sql, 7200);

			$database_size = 0;
			while ($row = $db->sql_fetchrow($result))
			{
				if ((isset($row['Type']) && $row['Type'] != 'MRG_MyISAM') || (isset($row['Engine']) && ($row['Engine'] == 'MyISAM' || $row['Engine'] == 'InnoDB')))
				{
					if ($table_prefix != '')
					{
						if (strpos($row['Name'], $table_prefix) !== false)
						{
							$database_size += $row['Data_length'] + $row['Index_length'];
						}
					}
					else
					{
						$database_size += $row['Data_length'] + $row['Index_length'];
					}
				}
			}
			$db->sql_freeresult($result);
		}
	}

	$database_size = ($database_size !== false) ? get_formatted_filesize($database_size) : $lang['NOT_AVAILABLE'];

	return $database_size;
}

/**
* Add permission language - this will make sure custom files will be included
*/
function add_permission_language()
{
	global $user, $class_plugins;

	// First of all, our own file. We need to include it as the first file because it presets all relevant variables.
	// MIGHTY GORGON - LANG - BEGIN
	global $class_plugins;
	global $db, $cache, $lang;

	setup_extra_lang(array('lang_cms_permissions', 'lang_permissions'));

	// Add Plugins Lang!
	if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
	if (empty($class_plugins)) $class_plugins = new class_plugins();

	foreach ($cache->obtain_plugins_config() as $k => $plugin)
	{
		if (!empty($plugin['plugin_enabled']))
		{
			$class_plugins->setup_lang($plugin['plugin_dir'] . '/', 'permissions');
		}
	}

	// Merge $lang with $user->lang
	merge_user_lang();
	// MIGHTY GORGON - LANG - END

	// CODE REMOVED
	/*
	$files_to_add = array();

	// Now search in acp and mods folder for permissions_ files.
	foreach (array('acp/', 'mods/') as $path)
	{
		$dh = @opendir($user->lang_path . $user->lang_name . '/' . $path);

		if ($dh)
		{
			while (($file = readdir($dh)) !== false)
			{
				if (($file !== 'permissions_phpbb.' . PHP_EXT) && (strpos($file, 'permissions_') === 0) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
				{
					$files_to_add[] = $path . substr($file, 0, -(strlen(PHP_EXT) + 1));
				}
			}
			closedir($dh);
		}
	}

	if (!sizeof($files_to_add))
	{
		return false;
	}

	$user->add_lang($files_to_add);
	*/

	return true;

}

/**
* Obtain user_ids from usernames or vice versa. Returns false on
* success else the error string
*
* @param array &$user_id_ary The user ids to check or empty if usernames used
* @param array &$username_ary The usernames to check or empty if user ids used
* @param mixed $user_type Array of user types to check, false if not restricting by user type
*/
function user_get_id_name(&$user_id_ary, &$username_ary, $user_type = false)
{
	global $db;

	// Are both arrays already filled? Yep, return else
	// are neither array filled?
	if ($user_id_ary && $username_ary)
	{
		return false;
	}
	elseif (!$user_id_ary && !$username_ary)
	{
		return 'NO_USERS';
	}

	$which_ary = ($user_id_ary) ? 'user_id_ary' : 'username_ary';

	if ($$which_ary && !is_array($$which_ary))
	{
		$$which_ary = array($$which_ary);
	}

	$sql_in = ($which_ary == 'user_id_ary') ? array_map('intval', $$which_ary) : array_map('utf8_clean_string', $$which_ary);
	unset($$which_ary);

	$user_id_ary = $username_ary = array();

	// Grab the user id/username records
	$sql_where = ($which_ary == 'user_id_ary') ? 'user_id' : 'username_clean';
	$sql = 'SELECT user_id, username
		FROM ' . USERS_TABLE . '
		WHERE ' . $db->sql_in_set($sql_where, $sql_in);

	if ($user_type !== false && !empty($user_type))
	{
		$sql .= ' AND ' . $db->sql_in_set('user_type', $user_type);
	}

	$result = $db->sql_query($sql);

	if (!($row = $db->sql_fetchrow($result)))
	{
		$db->sql_freeresult($result);
		return 'NO_USERS';
	}

	do
	{
		$username_ary[$row['user_id']] = $row['username'];
		$user_id_ary[] = $row['user_id'];
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);

	return false;
}

/**
* Enables a particular flag in a bitfield column of a given table.
*
* @param string $table_name The table to update
* @param string $column_name The column containing a bitfield to update
* @param int $flag The binary flag which is OR-ed with the current column value
* @param string $sql_more This string is attached to the sql query generated to update the table.
*
* @return void
*/
function enable_bitfield_column_flag($table_name, $column_name, $flag, $sql_more = '')
{
	global $db;

	$sql = 'UPDATE ' . $table_name . '
		SET ' . $column_name . ' = ' . $db->sql_bit_or($column_name, $flag) . '
		' . $sql_more;
	$db->sql_query($sql);
}

?>