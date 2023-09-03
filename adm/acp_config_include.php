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

$var_menu = request_get_var('menu', 0);
$var_menu_id = request_post_var('menu_id', 0);
$menu_id = 0;
if (!empty($var_menu) || !empty($var_menu_id))
{
	$menu_id = !empty($var_menu_id) ? $var_menu_id : $var_menu;
}

$var_mod = request_get_var('mod', 0);
$var_mod_id = request_post_var('mod_id', 0);
$mod_id = 0;
if (!empty($var_mod) || !empty($var_mod_id))
{
	$mod_id = !empty($var_mod_id) ? $var_mod_id : $var_mod;
}

$var_sub = request_get_var('msub', 0);
$var_sub_id = request_post_var('sub_id', 0);
$sub_id = 0;
if (!empty($var_sub) || !empty($var_sub_id))
{
	$sub_id = !empty($var_sub_id) ? $var_sub_id : $var_sub;
}

$settings_modules_array = $class_settings->process_settings_modules($acp_modules, true, false);

// fix menu id
if ($menu_id > sizeof($settings_modules_array['menu_keys']))
{
	$menu_id = 0;
}

// fix mod id
if ($mod_id > sizeof($settings_modules_array['mod_keys'][$menu_id]))
{
	$mod_id = 0;
}

// fix sub id
if ($sub_id > sizeof($settings_modules_array['sub_keys'][$menu_id][$mod_id]))
{
	$sub_id = 0;
}

// menu name
$menu_name = $settings_modules_array['menu_keys'][$menu_id];

// mod name
$mod_name = $settings_modules_array['mod_keys'][$menu_id][$mod_id];

// sub name
$sub_name = $settings_modules_array['sub_keys'][$menu_id][$mod_id][$sub_id];

// buttons
$submit = isset($_POST['submit']);

// get the real value of config
$default_config = array();
$default_config = $acp_default_config;

// validate
if ($submit)
{
	// init for error
	$error = false;
	$error_msg = '';

	// format and verify data
	@reset($acp_modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	//while (list($config_name, $config_data) = @each($acp_modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']))
	foreach ($acp_modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'] as $config_name => $config_data)
	{
		if (isset($_POST[$config_name]))
		{
			$config_data['name'] = $config_name;
			$config_data['default'] = $_POST[$config_name];
			$config_value = $class_form->validate_value($config_data);

			// Save data
			if (empty($is_plugin))
			{
				$config_value = fix_config_values($config_name, $config_value);
				$class_settings->set_config($config_name, $config_value, false, false);
			}
			else
			{
				$class_plugins->set_plugin_config($config_name, $config_value, false, false);
			}
		}
		if (isset($_POST[$config_name . '_over']))
		{
			$config_name = $config_name . '_over';
			$config_value = !empty($_POST[$config_name]) ? '1' : '0';

			// Save data
			if (empty($is_plugin))
			{
				$class_settings->set_config($config_name, $config_value, false, false);
			}
			else
			{
				$class_plugins->set_plugin_config($config_name, $config_value, false, false);
			}
		}
	}

	if (empty($is_plugin))
	{
		$class_settings->cache_clear();
	}
	else
	{
		$class_plugins->cache_clear();
	}

	// send an update message
	$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid($acp_file . '?menu=' . $menu_id . '&amp;mod=' . $mod_id . '&amp;msub=' . $sub_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('./index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

// template
$template->set_filenames(array('body' => ADM_TPL . 'config_settings_body.tpl'));

// header
$template->assign_vars(array(
	'L_TITLE' => $acp_module_title,
	'L_TITLE_EXPLAIN' => $acp_module_title_explain,
	'L_MOD_NAME' => $class_settings->get_lang($menu_name) . ' - ' . $class_settings->get_lang($mod_name) . (!empty($sub_name) ? ' - ' . $class_settings->get_lang($sub_name) : ''),
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	)
);

// send menu
for ($i = 0; $i < sizeof($settings_modules_array['menu_keys']); $i++)
{
	$l_menu = $settings_modules_array['menu_keys'][$i];
	if (sizeof($settings_modules_array['mod_keys'][$i]) == 1)
	{
		$l_menu = $settings_modules_array['mod_keys'][$i][0];
		if (sizeof($settings_modules_array['sub_keys'][$i][0]) == 1)
		{
			$l_menu = $settings_modules_array['sub_keys'][$i][0][0];
		}
	}
	$template->assign_block_vars('menu', array(
		'CLASS' => ($menu_id == $i) ? ((sizeof($settings_modules_array['mod_keys'][$i]) > 1) ? 'row3' : 'row1') : 'row2',
		'U_MENU' => append_sid($acp_file . '?menu=' . $i),
		'L_MENU' => sprintf((($menu_id == $i) ? '<b>%s</b>' : '%s'), $class_settings->get_lang($l_menu)),
		)
	);
	if ($menu_id == $i)
	{
		if (sizeof($settings_modules_array['mod_keys'][$i]) > 1)
		{
			$template->assign_block_vars('menu.title_open', array());
		}
	}
	else
	{
		$template->assign_block_vars('menu.title_close', array());
	}
	if ($menu_id == $i)
	{
		for ($j = 0; $j < sizeof($settings_modules_array['mod_keys'][$i]); $j++)
		{
			$l_mod = $settings_modules_array['mod_keys'][$i][$j];
			if (sizeof($settings_modules_array['sub_keys'][$i][$j]) == 1)
			{
				$l_mod = $settings_modules_array['sub_keys'][$i][$j][0];
			}
			$template->assign_block_vars('menu.mod', array(
				'CLASS' => (($menu_id == $i) && ($mod_id == $j)) ? 'row1' : 'row2',
				'ALIGN' => (($menu_id == $i) && ($mod_id == $j) && (sizeof($settings_modules_array['sub_keys'][$i][$j]) > 1)) ? 'left' : 'center',
				'U_MOD' => append_sid($acp_file . '?menu=' . $i . '&amp;mod=' . $j),
				'L_MOD' => sprintf(((($menu_id == $i) && ($mod_id == $j)) ? '<b>%s</b>' : '%s'), $class_settings->get_lang($l_mod)),
				)
			);
			if (($menu_id == $i) && ($mod_id == $j))
			{
				if (sizeof($settings_modules_array['sub_keys'][$i][$j]) > 1)
				{
					$template->assign_block_vars('menu.mod.sub', array());
					for ($k = 0; $k < sizeof($settings_modules_array['sub_keys'][$i][$j]); $k++)
					{
						$template->assign_block_vars('menu.mod.sub.row', array(
							'CLASS' => (($menu_id == $i) && ($mod_id == $j) && ($sub_id == $k)) ? 'row1' : 'row2',
							'U_MOD' => append_sid($acp_file . '?menu=' . $i . '&amp;mod=' . $j . '&amp;msub=' . $k),
							'L_MOD' => sprintf((($sub_id == $k) ? '<b>%s</b>' : '%s'), $class_settings->get_lang($settings_modules_array['sub_keys'][$i][$j][$k])),
							)
						);
					}
				}
			}
		}
	}
}

// send items
@reset($acp_modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
//while (list($config_name, $config_data) = @each($acp_modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']))
foreach ($acp_modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'] as $config_name => $config_data)
{
	// Create the field input
	$config_data['default'] = $default_config[$config_name];
	$input = $class_form->create_input($config_name, $config_data);

	// overwrite user choice
	$override = '';
	if (!empty($input) && !empty($config_data['user']) && isset($user->data[$config_data['user']]))
	{
		$override = '';
		@reset($class_settings->list_yes_no);
		//while (list($key, $val) = @each($class_settings->list_yes_no))
		foreach ($class_settings->list_yes_no as $key => $val)
		{
			$selected = ($default_config[$config_name . '_over'] == $val) ? ' checked="checked"' : '';
			$l_key = $class_settings->get_lang($key);
			$override .= '<input type="radio" name="' . $config_name . '_over' . '" value="' . $val . '"' . $selected . ' />' . $l_key . '&nbsp;&nbsp;';
		}
		$override = '<hr />' . $lang['Override_user_choices'] . ':&nbsp;'. $override;
	}

	// dump to template
	$template->assign_block_vars('field', array(
		'L_SEPARATOR' => !empty($config_data['separator']) ? $class_settings->get_lang($config_data['separator']) : false,
		'L_SEPARATOR_EXPLAIN' => !empty($config_data['separator_explain']) ? $class_settings->get_lang($config_data['separator_explain']) : false,
		'L_NAME' => $class_settings->get_lang($config_data['lang_key']),
		'L_EXPLAIN' => !empty($config_data['explain']) ? $class_settings->get_lang($config_data['explain']) : '',
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
	'S_DISPLAY_CONFIG_MENU' => ((sizeof($settings_modules_array['mod_keys'][0]) > 1) ? true : false),
	'S_ACTION' => append_sid($acp_file),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	)
);

?>