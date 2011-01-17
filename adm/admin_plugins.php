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

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['3000_Plugins']['100_Plugins_Modules'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

define('THIS_PAGE', 'admin_plugins.' . PHP_EXT);

// FORM CLASS - BEGIN
include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();
// FORM CLASS - END

// PLUGINS CLASS - BEGIN
include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
$class_plugins = new class_plugins();
// PLUGINS CLASS - END

// MODES - BEGIN
$mode_types = array('list', 'save');
$mode = request_var('mode', $mode_types[0]);
$mode = (isset($_POST['save']) ? 'save' : $mode);
$mode = (!in_array($mode, $mode_types) ? $mode_types[0] : $mode);
// MODES - END

// ACTIONS - BEGIN
$actions_types = array('none', 'update', 'install', 'uninstall');
$action = request_var('action', $actions_types[0]);
$action = (!in_array($action, $actions_types) ? $actions_types[0] : $action);
// ACTIONS - END

$plugin_dir = request_var('plugin_dir', '');
if (!empty($plugin_dir))
{
	$plugin_info_file = $class_plugins->plugins_path . $plugin_dir . '/info.' . PHP_EXT;
	if (file_exists($plugin_info_file))
	{
		$plugin_data['dir'] = $plugin_dir;
	}
	else
	{
		$action = 'none';
	}
}

// VARS - BEGIN
$s_hidden_fields = '';
// VARS - END

$plugins_list = $class_plugins->get_plugins_list();
$plugins_config = $cache->obtain_plugins_config();

if($mode == 'save')
{
	if ($action == 'install')
	{
		$result = $class_plugins->install($plugin_data);
	}
	elseif (($action == 'update') || ($action == 'uninstall'))
	{
		$plugin_info_db = $class_plugins->get_config($plugin_data['dir']);
		if (!empty($plugin_info_db))
		{
			$plugin_data = $class_plugins->config_map($plugin_data, $plugin_info_db);
		}

		if ($action == 'update')
		{
			$result = $class_plugins->update($plugin_data);
		}
		elseif ($action == 'uninstall')
		{
			$result = $class_plugins->uninstall($plugin_data);
		}
	}
	else
	{
		$existing_plugins = array();
		foreach ($plugins_list as $plugin)
		{
			$existing_plugins[] = $plugin['config'];
			$plugin_data = array();
			$plugin_data = array(
				'name' => $plugin['config'],
				'version' => $plugin['version'],
				'dir' => $plugin['dir'],
				'enabled' => (isset($_POST[$plugin['config']]) ? $_POST[$plugin['config']] : 0),
			);
			$class_plugins->set_config($plugin_data, false, true);
		}

		foreach ($plugins_config as $k => $v)
		{
			if (!in_array($k, $existing_plugins))
			{
				$plugin_data = array();
				$plugin_data = array('name' => $k);
				$class_plugins->remove_config($plugin_data, false);
			}
		}
	}

	$cache->destroy('config_plugins');

	$message = $lang['PLUGINS_CONFIG_UPDATED'];
	$message .= '<br /><br />' . sprintf($lang['PLUGINS_RETURN_CLICK'], '<a href="' . append_sid(THIS_PAGE) . '">', '</a>');
	$message .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'plugins_list_body.tpl'));

	if (empty($plugins_list))
	{
		$template->assign_var('S_NO_PLUGINS', true);
	}
	else
	{
		$hidden_fields_array = array('save' => 1);
		$s_hidden_fields = build_hidden_fields($hidden_fields_array, true, STRIP);

		foreach ($plugins_list as $plugin)
		{
			$row_class = (empty($row_class) || ($row_class == $theme['td_class2'])) ? $theme['td_class1'] : $theme['td_class2'];

			unset($plugin_array);
			$plugin_array = array(
				'name' => $plugin['config'],
				'type' => 'LIST_RADIO',
				'default' => empty($plugins_config[$plugin['config']]['plugin_enabled']) ? 0 : 1,
				'values' => array('Enabled' => 1, 'Disabled' => 0),
			);

			$install_link = append_sid(THIS_PAGE . '?plugin_dir=' . htmlspecialchars(urlencode($plugin['dir'])) . '&amp;mode=save&amp;action=install');
			$install_img = '<a href="' . $install_link . '" class="text_green" style="text-decoration: none; vertical-align: middle;">&nbsp;' . $lang['PLUGINS_INSTALL'] . '&nbsp;<img src="' . IP_ROOT_PATH . 'images/cms/b_add.png" style="text-decoration: none; vertical-align: middle;" alt="' . $lang['PLUGINS_INSTALL'] . '" title="' . $lang['PLUGINS_INSTALL'] . '" /></a>';

			$update_link = append_sid(THIS_PAGE . '?plugin_dir=' . htmlspecialchars(urlencode($plugin['dir'])) . '&amp;mode=save&amp;action=update');
			$update_img = '<a href="' . $update_link . '" class="text_green" style="text-decoration: none; vertical-align: middle;">&nbsp;' . $lang['PLUGINS_UPGRADE'] . '&nbsp;<img src="' . IP_ROOT_PATH . 'images/cms/b_refresh.png" style="text-decoration: none; vertical-align: middle;" alt="' . $lang['PLUGINS_UPGRADE'] . '" title="' . $lang['PLUGINS_UPGRADE'] . '" /></a>';

			$uninstall_link = append_sid(THIS_PAGE . '?plugin_dir=' . htmlspecialchars(urlencode($plugin['dir'])) . '&amp;mode=save&amp;action=uninstall');
			$uninstall_img = '<a href="' . $uninstall_link . '" class="text_red" style="text-decoration: none; vertical-align: middle;">&nbsp;' . $lang['PLUGINS_UNINSTALL'] . '&nbsp;<img src="' . IP_ROOT_PATH . 'images/cms/b_delete.png" style="text-decoration: none; vertical-align: middle;" alt="' . $lang['PLUGINS_UNINSTALL'] . '" title="' . $lang['PLUGINS_UNINSTALL'] . '" /></a>';

			$plugin_up_to_date = version_compare($plugins_config[$plugin['config']]['plugin_version'], $plugin['version'], '=');
			$plugin_installed = !empty($plugins_config[$plugin['config']]['plugin_version']) ? true : false;
			$template->assign_block_vars('plugin', array(
				'ROW_CLASS' => $row_class,
				'PLUGIN_DIR' => htmlspecialchars($plugin['dir']),
				'PLUGIN_CURRENT_VERSION' => $plugin_installed ? htmlspecialchars($plugins_config[$plugin['config']]['plugin_version']) : false,
				'PLUGIN_LAST_VERSION' => htmlspecialchars($plugin['version']),
				'PLUGIN_STATUS_COLOR' => ' class="' . ($plugin_up_to_date ? 'text_green' : 'text_red') . '"',
				'PLUGIN_STATUS' => ($plugin_up_to_date ? $lang['PLUGINS_UP_TO_DATE'] : $lang['PLUGINS_OUTDATED']),
				'PLUGIN_NAME' => htmlspecialchars($plugin['name']),
				'PLUGIN_DESCRIPTION' => htmlspecialchars($plugin['description']),
				'PLUGIN_RADIO' => $class_form->create_input($plugin['config'], $plugin_array),
				'PLUGIN_INSTALLED' => $plugin_installed,
				'PLUGIN_UP_TO_DATE' => $plugin_up_to_date,
				'PLUGIN_LINK_INSTALL' => $install_img,
				'PLUGIN_LINK_UPDATE' => $update_img,
				'PLUGIN_LINK_UNINSTALL' => $uninstall_img,
				)
			);
		}
	}
}

$template->assign_vars(array(
	'S_PLUGINS_ACTION' => append_sid(THIS_PAGE),
	'S_HIDDEN_FIELDS' => $s_hidden_fields
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>