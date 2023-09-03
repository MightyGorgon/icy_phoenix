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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

if (!$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=profile_options.' . PHP_EXT, true));
}

// constant
$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

$sid = request_var('sid', '');

$view_user_id = request_var('view_user_id', 0);
$view_user_id = empty($view_user_id) ? request_var(POST_USERS_URL, 0) : $view_user_id;

$target_userdata = array();
if (empty($view_user_id) || ($view_user_id == ANONYMOUS))
{
	$view_user_id = $user->data['user_id'];
	$target_userdata = $user->data;
}
else
{
	$sql = "SELECT * FROM " . USERS_TABLE . " WHERE user_id = '" . $view_user_id . "'";
	$result = $db->sql_query($sql);

	if (!$target_userdata = $db->sql_fetchrow($result))
	{
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_INFO, $lang['NO_USER']);
	}
	$target_userdata['user_level'] = ($target_userdata['user_level'] == JUNIOR_ADMIN) ? ADMIN : $target_userdata['user_level'];
}

// Get the user level
$user_level = $user->data['user_level'];
if ($user_level == MOD)
{
	if ($target_userdata['user_level'] == ADMIN)
	{
		$user_level = USER;
	}
	else
	{
		// Verify that the user is really a moderator (phpBB lack)
		$sql = "SELECT * FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
				WHERE ug.user_id = " . $user->data['user_id'] . "
					AND aa.group_id = ug.group_id
					AND aa.auth_mod = 1
					AND ug.user_pending = 0
				LIMIT 0, 1";
		$db->sql_query($sql);
		if ($db->sql_numrows($result) <= 0)
		{
			$user_level = USER;
		}
		$db->sql_freeresult($result);
	}
}

// Check auth level
if (($view_user_id != $user->data['user_id']) && ($user->data['user_level'] != ADMIN))
{
	message_die(GENERAL_INFO, $lang['Wrong_Profile']);
}

// create entry if NULL: fix isset issue
//@reset($target_userdata);
//while (list($key, $data) = each($target_userdata))
foreach ($target_userdata as $key => $data)
{
	if ($target_userdata[$key] == NULL)
	{
		$target_userdata[$key] = '';
	}
}

// main_menu
$menu_name = request_var('data', '');
if (empty($menu_name))
{
	$menu_name = 'Preferences';
}
elseif (!isset($class_settings->modules[$menu_name]['data']))
{
	// no mods
	$menu_name = '';
}

// mod_id
$mod_id = request_var('mod_id', 0);
$mod_id = empty($mod_id) ? request_var('mod', 0) : $mod_id;

// sub_id
$sub_id = request_var('mod_sub_id', 0);
$sub_id = empty($sub_id) ? request_var('msub', 0) : $sub_id;

// Build a key array
$profile_modules = $class_settings->modules;
$settings_modules_array = $class_settings->process_settings_modules($profile_modules, false, $target_userdata);
$mod_ids = $settings_modules_array['mod_id'][0];
$mod_keys = $settings_modules_array['mod_keys'][0];
$mod_sort = $settings_modules_array['mod_sort'][0];
$sub_keys = $settings_modules_array['sub_keys'][0];
$sub_sort = $settings_modules_array['sub_sort'][0];

$module_id = request_var('module', '');
$module_id_found = false;
if (!empty($module_id))
{
	foreach ($mod_ids as $k => $v)
	{
		if (isset($v[$module_id]))
		{
			$mod_keys_flip = array_flip($mod_keys);
			$mod_id = $mod_keys_flip[$v[$module_id]];
			$module_id_found = true;
			break;
		}
	}
}

// We need to reset this var if not found... so we can use module to url append
if (!$module_id_found)
{
	$module_id = '';
}

// fix mod id
if ($mod_id > sizeof($mod_keys))
{
	$mod_id = 0;
}
if ($sub_id > sizeof($sub_keys[$mod_id]))
{
	$sub_id = 0;
}

// mod name
$mod_name = $mod_keys[$mod_id];

// sub name
$sub_name = !empty($sub_keys[$mod_id][$sub_id]) ? $sub_keys[$mod_id][$sub_id] : '';

// buttons
$submit = isset($_POST['submit']) ? true : false;

// create the back link
$return_link = append_sid('profile_options.' . PHP_EXT . '?sub=' . strtolower($menu_name) . '&amp;' . (!empty($module_id) ? ('module=' . $module_id) : ('mod=' . $mod_id)) . '&amp;msub=' . $sub_id . '&amp;' . POST_USERS_URL . '=' . $view_user_id);

// validate
if ($submit)
{
	// session id check
	if ($sid != $user->data['session_id'])
	{
		message_die(GENERAL_ERROR, 'INVALID_SESSION');
	}

	// init for error
	$error = false;
	$error_msg = '';

	// format and verify data
	//@reset($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	//while (list($config_name, $config_data) = @each($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']))
	foreach ($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'] as $config_name => $config_data)
	{
		$user_check = (!empty($config_data['user']) && isset($_POST[$config_data['user']])) ? true : false;
		$config_data['auth'] = !empty($config_data['auth']) ? $config_data['auth'] : false;

		if (!empty($user_check) && $class_settings->is_auth($config_data['auth']))
		{
			$config_data['name'] = $config_data['user'];
			$config_data['default'] = $_POST[$config_data['user']];
			$config_value = $class_form->validate_value($config_data);

			if ((isset($target_userdata[$config_data['name']]) && (!$config[$config_name . '_over'] || ($user->data['user_level'] == ADMIN))) || !empty($config_data['system']))
			{
				// update
				$sql = "UPDATE " . USERS_TABLE . "
						SET " . $config_data['name'] . " = '" . $db->sql_escape($config_value) . "'
						WHERE user_id = " . $target_userdata['user_id'];
				$db->sql_query($sql);
			}
		}
	}

	// send an update message
	$redirect_url = $return_link;
	meta_refresh(3, $redirect_url);

	$message = $lang['Profile_updated'] . '<br /><br />' . sprintf($lang['Click_return_preferences'], '<a href="' . $return_link . '">', '</a>') . '<br /><br />';
	message_die(GENERAL_MESSAGE, $message);
}
else
{
	$pcp_section = $class_settings->get_lang($mod_name) . (!empty($sub_name) ? ' - ' . $class_settings->get_lang($sub_name) : '');
	$link_name = $pcp_section;
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $nav_separator . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE_MAIN) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($nav_separator . '<a class="nav-current" href="' . $nav_server_url . $return_link . '">' . $link_name . '</a>') : '');
	include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

	// header
	$template->assign_vars(array(
		'L_OPTION' => !empty($meta_content['page_title']) ? $meta_content['page_title'] : '',
		'U_OPTION' => $return_link,
		'L_MOD_NAME' => $pcp_section,
		'U_USER' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $view_user_id),
		'L_USER' => $target_userdata['username'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		)
	);

	// send menu
	for ($i = 0; $i < sizeof($mod_keys); $i++)
	{
		$template->assign_block_vars('mod', array(
			'CLASS' => ($mod_id == $i) ? $theme['td_class1'] : $theme['td_class2'],
			'ALIGN' => (($mod_id == $i) && (sizeof($sub_keys[$i]) > 1)) ? 'left' : 'center',
			'U_MOD' => append_sid('./profile_options.' . PHP_EXT . '?sub=' . $menu_name . '&mod=' . $i . '&amp;' . POST_USERS_URL . '=' . $view_user_id),
			'L_MOD' => sprintf((($mod_id == $i) ? '<b>%s</b>' : '%s'), $class_settings->get_lang($mod_keys[$i])),
			)
		);
		if ($mod_id == $i)
		{
			if (sizeof($sub_keys[$i]) > 1)
			{
				$template->assign_block_vars('mod.sub', array());
				for ($j=0; $j < sizeof($sub_keys[$i]); $j++)
				{
					$template->assign_block_vars('mod.sub.row', array(
						'CLASS' => ($sub_id == $j) ? $theme['td_class1'] : $theme['td_class2'],
						'U_MOD' => append_sid('./profile_options.' . PHP_EXT . '?sub=' . $menu_name . '&amp;mod=' . $i . '&amp;msub=' . $j . '&amp;' . POST_USERS_URL . '=' . $view_user_id),
						'L_MOD' => sprintf((($sub_id == $j) ? '<b>%s</b>' : '%s'), $class_settings->get_lang($sub_keys[$i][$j])),
						)
					);
				}
			}
		}
	}

	// send items
	//@reset($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	//while (list($config_name, $config_data) = @each($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']))
	//print_r($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	$modules_settings = array();
	if (!empty($class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']))
	{
		$modules_settings = $class_settings->modules[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'];
	}
	else
	{
		$modules_settings = $class_settings->modules[$menu_name]['data'][$mod_name]['data'];
	}
	foreach ($modules_settings as $config_name => $config_data)
	{
		// process only fields from users table
		$user_field = !empty($config_data['user']) ? $config_data['user'] : false;
		if (((!empty($user_field) && isset($target_userdata[$user_field]) && (!$config[$config_name . '_over'] || ($user->data['user_level'] == ADMIN))) || !empty($config_data['system'])) && (empty($config_data['auth']) ||$class_settings->is_auth($config_data['auth'], $user_level)))
		{
			$config_data['name'] = $config_data['user'];
			$config_data['default'] = $target_userdata[$user_field];
			$input = $class_form->create_input($config_data['name'], $config_data);

			// dump to template
			$template->assign_block_vars('field', array(
				'L_NAME' => $class_settings->get_lang($config_data['lang_key']),
				'L_EXPLAIN' => !empty($config_data['explain']) ? $class_settings->get_lang($config_data['explain']) : '',
				'INPUT' => $input,
				)
			);
		}
	}

	// system
	$s_hidden_fields = '';
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';
	$s_hidden_fields .= '<input type="hidden" name="view_user_id" value="' . $view_user_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="sub" value="' . $menu_name . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mod_id" value="' . $mod_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mod_sub_id" value="' . $sub_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="set" value="add" />';
	$template->assign_vars(array(
		'S_PROFILCP_ACTION' => append_sid('profile_options.' . PHP_EXT),
		'NAV_SEPARATOR' => $nav_separator,
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		)
	);

	full_page_generation('profile_options_body.tpl', $lang['Preferences'], '', '');
}

?>