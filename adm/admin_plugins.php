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
	$module['1500_Plugins']['100_Plugins_Modules'] = $file;
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

// VARS - BEGIN
$s_hidden_fields = '';
// VARS - END

$plugins_list = $class_plugins->get_plugins_list();
$plugins_config = $cache->obtain_plugins_config();

if($mode == 'save')
{
	$existing_plugins = array();
	foreach ($plugins_list as $plugin)
	{
		$existing_plugins[] = $plugin['config'];
		$plugin_data = array();
		$plugin_data = array(
			'name' => $plugin['config'],
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
		$s_hidden_fields = build_hidden_fields($hidden_fields_array, true, false);

		foreach ($plugins_list as $plugin)
		{
			$row_class = (empty($row_class) || ($row_class == $theme['td_class2'])) ? $theme['td_class1'] : $theme['td_class2'];

			unset($plugin_array);
			$plugin_array = array(
				'name' => $plugin['config'],
				'type' => 'LIST_RADIO',
				'default' => empty($plugins_config[$plugin['config']]['plugin_enabled']) ? 0 : 1,
				'values' => array('Yes' => 1, 'No' => 0),
			);

			$template->assign_block_vars('plugin', array(
				'ROW_CLASS' => $row_class,
				'PLUGIN_DIR' => htmlspecialchars($plugin['dir']),
				'PLUGIN_NAME' => htmlspecialchars($plugin['name']),
				'PLUGIN_DESCRIPTION' => htmlspecialchars($plugin['description']),
				'PLUGIN_RADIO' => $class_form->create_input($plugin['config'], $plugin_array),
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