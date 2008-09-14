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

define('IN_ICYPHOENIX', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['120_MG_Configuration'] = $file;
	//$module['1000_Configuration']['170_Configuration_extend'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

// get all the mods settings
$mods = array();
$dir = @opendir(IP_ROOT_PATH . 'includes/mods_settings');
while( $file = @readdir($dir) )
{
	if( preg_match("/^mod_.*?\." . PHP_EXT . "$/", $file) )
	{
		include(IP_ROOT_PATH . 'includes/mods_settings/' . $file);
	}
}
@closedir($dir);

// menu_id
$menu_id = 0;
if ( isset($_GET['menu']) || isset($_POST['menu_id']) )
{
	$menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : intval($_GET['menu']);
}

// mod_id
$mod_id = 0;
if ( isset($_GET['mod']) || isset($_POST['mod_id']) )
{
	$mod_id = isset($_POST['mod_id']) ? intval($_POST['mod_id']) : intval($_GET['mod']);
}

// sub_id
$sub_id = 0;
if ( isset($_GET['msub']) || isset($_POST['sub_id']) )
{
	$sub_id = isset($_POST['sub_id']) ? intval($_POST['sub_id']) : intval($_GET['msub']);
}

// menu
$menu_keys = array();
$menu_sort = array();

// mods
$mod_keys = array();
$mod_sort = array();

// fields
$sub_keys = array();
$sub_sort = array();

// process
@reset($mods);
while ( list($menu_name, $menu) = each($mods) )
{
	// check if there is some config fields in the mods under this menu
	$found = false;

	// menu
	@reset($menu['data']);
	while ( ( list($mod_name, $mod) = @each($menu['data']) ) && !$found )
	{
		// sub menu
		@reset($mod['data']);
		while ( ( list($sub_name, $sub) = @each($mod['data']) ) && !$found )
		{
			// fields
			@reset($sub['data']);
			while ( ( list($field_name, $field) = @each($sub['data']) ) && !$found )
			{
				if ( !isset($field['user_only']) || !$field['user_only'] )
				{
					$found=true;
					break;
				}
			}
		}
	}

	// menu ok
	if ( $found )
	{
		$i = count($menu_keys);
		$menu_keys[$i] = $menu_name;
		$menu_sort[$i] = $menu['sort'];

		// init mod level
		$mod_keys[$i] = array();
		$mod_sort[$i] = array();

		@reset($menu['data']);
		while ( list($mod_name, $mod) = @each($menu['data']) )
		{
			// check if there is some config fields
			$found = false;
			@reset($mod['data']);
			while ( list($sub_name, $sub) = @each($mod['data']) )
			{
				@reset($sub['data']);
				while ( list($field_name, $field) = @each($sub['data']) )
				{
					if ( !isset($field['user_only']) || !$field['user_only'] )
					{
						$found=true;
						break;
					}
				}
			}
			if ($found)
			{
				$j = count($mod_keys[$i]);
				$mod_keys[$i][$j] = $mod_name;
				$mod_sort[$i][$j] = $mod['sort'];

				// init sub levels
				$sub_keys[$i][$j] = array();
				$sub_sort[$i][$j] = array();

				// sub names
				@reset($mod['data']);
				while ( list($sub_name, $sub) = @each($mod['data']) )
				{
					if ( !empty($sub_name) )
					{
						// check if there is some config fields in this level
						$found = false;
						@reset($sub['data']);
						while ( list($field_name, $field) = @each($sub['data']) )
						{
							if ( !isset($field['user_only']) || !$field['user_only'] )
							{
								$found=true;
								break;
							}
						}
						if ($found)
						{
							$sub_keys[$i][$j][] = $sub_name;
							$sub_sort[$i][$j][] = $sub['sort'];
						}
					}
				}
				@array_multisort($sub_sort[$i][$j], $sub_keys[$i][$j]);
			}
		}
		@array_multisort($mod_sort[$i], $mod_keys[$i], $sub_sort[$i], $sub_keys[$i]);
	}
}
@array_multisort($menu_sort, $menu_keys, $mod_sort, $mod_keys, $sub_sort, $sub_keys);

// fix menu id
if ( $menu_id > count($menu_keys) )
{
	$menu_id = 0;
}

// fix mod id
if ( $mod_id > count($mod_keys[$menu_id]) )
{
	$mod_id = 0;
}

// fix sub id
if ( $sub_id > count($sub_keys[$menu_id][$mod_id]) )
{
	$sub_id = 0;
}

// menu name
$menu_name = $menu_keys[$menu_id];

// mod name
$mod_name = $mod_keys[$menu_id][$mod_id];

// sub name
$sub_name = $sub_keys[$menu_id][$mod_id][$sub_id];

// buttons
$submit = isset($_POST['submit']);

// get the real value of board_config
$sql = "SELECT * FROM " . CONFIG_TABLE;
if ( !$result = $db->sql_query($sql) ) message_die(CRITICAL_ERROR, 'Could not query config information', '', __LINE__, __FILE__, $sql);
$config = array();
while ($row = $db->sql_fetchrow($result))
{
	$config[ $row['config_name'] ] = $row['config_value'];
}

// validate
if ($submit)
{
	// init for error
	$error = false;
	$error_msg = '';

	// format and verify data
	@reset($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	while ( list($field_name, $field) = @each($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']) )
	{
		if (isset($_POST[$field_name]))
		{
			switch ($field['type'])
			{
				case 'LIST_RADIO_BR':
				case 'LIST_RADIO':
				case 'LIST_DROP':
					$$field_name = $_POST[$field_name];
					if (!in_array($$field_name, $mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'][$field_name]['values']))
					{
						$error = true;
						$msg = mods_settings_get_lang( $mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'][$field_name]['lang_key'] );
						$error_msg = (empty($error_msg) ? '' : '<br />') . $lang['Error'] . ':&nbsp;' . $msg;
					}
					break;
				case 'TINYINT':
				case 'SMALLINT':
				case 'MEDIUMINT':
				case 'INT':
					$$field_name = intval($_POST[$field_name]);
					break;
				case 'VARCHAR':
				case 'TEXT':
				case 'DATEFMT':
					$$field_name = trim(str_replace("\'", "''", htmlspecialchars($_POST[$field_name])));
					break;
				case 'HTMLVARCHAR':
				case 'HTMLTEXT':
					$$field_name = trim(str_replace("\'", "''", $_POST[$field_name]));
					break;
				default:
					$$field_name = '';
					if ( !empty($field['chk_func']) && function_exists($field['chk_func']) )
					{
						$$field_name = $field['chk_func']($field_name, $_POST[$field_name]);
					}
					else
					{
						message_die(GENERAL_ERROR, 'Unknown type of config data : ' . $field_name, '', __LINE__, __FILE__, '');
					}
					break;
			}
			if ($error)
			{
				$message = $error_msg . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('./admin_board_extend.' . PHP_EXT . '?menu=' . $menu_id. '&amp;mod=' . $mod_id . '&amp;msub=' . $sub_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('./index.' . PHP_EXT . '?pane=right') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}

	// save data
	@reset($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	while ( list($field_name, $field) = @each($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']) )
	{
		if (isset($$field_name))
		{
			// update
			$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '" . $$field_name . "'
					WHERE config_name = '" . $field_name . "'";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Failed to update general configuration for ' . $field_name, '', __LINE__, __FILE__, $sql);
			}
		}
		if ( isset($_POST[$field_name . '_over']) && !empty($field['user']) && isset($userdata[ $field['user'] ]) )
		{
			// update
			$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '" . intval($_POST[$field_name . '_over']) . "'
					WHERE config_name = '$field_name" . "_over'";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Failed to update general configuration for ' . $field_name, '', __LINE__, __FILE__, $sql);
			}
		}
	}

	// send an update message
	$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('./admin_board_extend.' . PHP_EXT . '?menu=' . $menu_id. '&amp;mod=' . $mod_id . '&amp;msub=' . $sub_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('./index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}


// template
$template->set_filenames(array('body' => ADM_TPL . 'board_config_extend_body.tpl'));

// header
$template->assign_vars(array(
	'L_TITLE' => $lang['MG_Configuration'],
	'L_TITLE_EXPLAIN' => $lang['MG_Configuration_Explain'],
	/*
	'L_TITLE' => $lang['Configuration_extend'],
	'L_TITLE_EXPLAIN' => $lang['Config_explain2'],
	*/
	'L_MOD_NAME' => mods_settings_get_lang($menu_name) . ' - ' . mods_settings_get_lang($mod_name) . ( !empty($sub_name) ? ' - ' . mods_settings_get_lang($sub_name) : '' ),
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	)
);

// send menu
for ($i = 0; $i < count($menu_keys); $i++)
{
	$l_menu = $menu_keys[$i];
	if ( count($mod_keys[$i]) == 1 )
	{
		$l_menu = $mod_keys[$i][0];
		if ( count($sub_keys[$i][0]) == 1 )
		{
			$l_menu = $sub_keys[$i][0][0];
		}
	}
	$template->assign_block_vars('menu', array(
		'CLASS'		=> ($menu_id == $i) ? ( (count($mod_keys[$i]) > 1) ? 'row3' : 'row1' ) : 'row2',
		'U_MENU'	=> append_sid('./admin_board_extend.' . PHP_EXT . '?menu=' . $i),
		'L_MENU'	=> sprintf( ( ($menu_id == $i) ? '<b>%s</b>' : '%s' ), mods_settings_get_lang($l_menu) ),
		)
	);
	if ( $menu_id == $i )
	{
		if (count($mod_keys[$i]) > 1 )
		{
			$template->assign_block_vars('menu.title_open', array());
		}
	}
	else
	{
		$template->assign_block_vars('menu.title_close', array() );
	}
	if ($menu_id == $i)
	{
		for ($j = 0; $j < count($mod_keys[$i]); $j++ )
		{
			$l_mod = $mod_keys[$i][$j];
			if ( count($sub_keys[$i][$j]) == 1 )
			{
				$l_mod = $sub_keys[$i][$j][0];
			}
			$template->assign_block_vars('menu.mod', array(
				'CLASS'	=> ( ($menu_id == $i) && ($mod_id == $j) ) ? 'row1' : 'row2',
				'ALIGN'	=> ( ($menu_id == $i) && ($mod_id == $j) && (count($sub_keys[$i][$j]) > 1) ) ? 'left' : 'center',
				'U_MOD'	=> append_sid('./admin_board_extend.' . PHP_EXT . '?menu=' . $i . '&amp;mod=' . $j),
				'L_MOD'	=> sprintf( ( ( ($menu_id == $i) && ($mod_id == $j) ) ? '<b>%s</b>' : '%s' ), mods_settings_get_lang($l_mod) ),
				)
			);
			if ( ($menu_id == $i) && ($mod_id == $j) )
			{
				if ( count($sub_keys[$i][$j]) > 1 )
				{
					$template->assign_block_vars('menu.mod.sub', array());
					for ($k = 0; $k < count($sub_keys[$i][$j]); $k++)
					{
						$template->assign_block_vars('menu.mod.sub.row', array(
							'CLASS'	=> ( ($menu_id == $i) && ($mod_id == $j) && ($sub_id == $k) ) ? 'row1' : 'row2',
							'U_MOD' => append_sid('./admin_board_extend.' . PHP_EXT . '?menu=' . $i . '&amp;mod=' . $j . '&amp;msub=' . $k),
							'L_MOD'	=> sprintf( (($sub_id == $k) ? '<b>%s</b>' : '%s'), mods_settings_get_lang($sub_keys[$i][$j][$k]) ),
							)
						);
					}
				}
			}
		}
	}
}

// send items
@reset($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
while ( list($field_name, $field) = @each($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']) )
{
	// get the field input statement
	$input = '';
	switch ($field['type'])
	{
		case 'LIST_RADIO_BR':
			@reset($field['values']);
			while ( list($key, $val) = @each($field['values']) )
			{
				$selected = ($config[$field_name] == $val) ? ' checked="checked"' : '';
				$l_key = mods_settings_get_lang($key);
				$input .= '<input type="radio" name="' . $field_name . '" value="' . $val . '"' . $selected . ' />' . $l_key . '<br />';
			}
			break;
		case 'LIST_RADIO':
			@reset($field['values']);
			while ( list($key, $val) = @each($field['values']) )
			{
				$selected = ($config[$field_name] == $val) ? ' checked="checked"' : '';
				$l_key = mods_settings_get_lang($key);
				$input .= '<input type="radio" name="' . $field_name . '" value="' . $val . '"' . $selected . ' />' . $l_key . '&nbsp;&nbsp;';
			}
			break;
		case 'LIST_DROP':
			@reset($field['values']);
			while ( list($key, $val) = @each($field['values']) )
			{
				$selected = ($config[$field_name] == $val) ? ' selected="selected"' : '';
				$l_key = mods_settings_get_lang($key);
				$input .= '<option value="' . $val . '"' . $selected . '>' . $l_key . '</option>';
			}
			$input = '<select name="' . $field_name . '">' . $input . '</select>';
			break;
		case 'TINYINT':
			$input = '<input type="text" name="' . $field_name . '" maxlength="3" size="2" class="post" value="' . $config[$field_name] . '" />';
			break;
		case 'SMALLINT':
			$input = '<input type="text" name="' . $field_name . '" maxlength="5" size="5" class="post" value="' . $config[$field_name] . '" />';
			break;
		case 'MEDIUMINT':
			$input = '<input type="text" name="' . $field_name . '" maxlength="8" size="8" class="post" value="' . $config[$field_name] . '" />';
			break;
		case 'INT':
			$input = '<input type="text" name="' . $field_name . '" maxlength="13" size="11" class="post" value="' . $config[$field_name] . '" />';
			break;
		case 'VARCHAR':
		case 'HTMLVARCHAR':
			$input = '<input type="text" name="' . $field_name . '" maxlength="255" size="45" class="post" value="' . $config[$field_name] . '" />';
			break;
		case 'TEXT':
		case 'HTMLTEXT':
			$input = '<textarea rows="5" cols="45" wrap="virtual" name="' . $field_name . '" class="post">' . $config[$field_name] . '</textarea>';
			break;
		default:
			$input = '';
			if ( !empty($field['get_func']) && function_exists($field['get_func']) )
			{
				$input = $field['get_func']($field_name, $config[$field_name]);
			}
			break;
	}

	// overwrite user choice
	$override = '';
	if ( !empty($input) && !empty($field['user']) && isset($userdata[ $field['user'] ]) )
	{
		$override = '';
		@reset($list_yes_no);
		while ( list($key, $val) = @each($list_yes_no) )
		{
			$selected = ($config[$field_name . '_over'] == $val) ? ' checked="checked"' : '';
			$l_key = mods_settings_get_lang($key);
			$override .= '<input type="radio" name="' . $field_name . '_over' . '" value="' . $val . '"' . $selected . ' />' . $l_key . '&nbsp;&nbsp;';
		}
		$override = '<hr />' . $lang['Override_user_choices'] . ':&nbsp;'. $override;
	}

	// dump to template
	$template->assign_block_vars('field', array(
		'L_NAME' => mods_settings_get_lang($field['lang_key']),
		'L_EXPLAIN' => !empty($field['explain']) ? '<br />' . mods_settings_get_lang($field['explain']) : '',
		'INPUT' => $input,
		'OVERRIDE' => $override,
		)
	);
}

// system
$s_hidden_fields = '';
$s_hidden_fields .= '<input type="hidden" name="menu_id" value="' . $menu_id . '" />';
$s_hidden_fields .= '<input type="hidden" name="mod_id" value="' . $mod_id . '" />';
$s_hidden_fields .= '<input type="hidden" name="sub_id" value="' . $sub_id . '" />';
$template->assign_vars(array(
	'S_ACTION' => append_sid('./admin_board_extend.' . PHP_EXT),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	)
);

// footer
$template->pparse('body');
include('./page_footer_admin.' . PHP_EXT);

?>