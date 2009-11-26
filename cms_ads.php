<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_CMS', true);
define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

setup_extra_lang(array('lang_admin', 'lang_cms'));

include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

$access_allowed = get_cms_access_auth('cms_ads');

if (!$access_allowed)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

if (!$userdata['session_admin'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=cms_ads.' . PHP_EXT . '&admin=1', true));
}

$ad_positions_array = array('glt', 'glb', 'glh', 'glf', 'fix', 'fit', 'fib', 'vfx', 'vft', 'vfb', 'vtx', 'vtt', 'vtb', 'nmt', 'nmb');
$ad_positions_cfg_value_array = array(1, 0);
$ad_positions_cfg_lang_array = array($lang['Yes'], $lang['No']);
$ad_positions_cfg_array = array();
$ad_positions_lang_array = array();
for ($i = 0; $i < sizeof($ad_positions_array); $i++)
{
	$ad_positions_lang_array[] = $lang['AD_POS_' . strtoupper($ad_positions_array[$i])];
	$ad_positions_cfg_array[] = 'ads_' . $ad_positions_array[$i];
}

$ad_auths_array = array(AUTH_ADMIN, AUTH_MOD, AUTH_REG, AUTH_ALL);
$ad_auths_lang_array = array($lang['AD_AUTH_ADMIN'], $lang['AD_AUTH_MOD'], $lang['AD_AUTH_REG'], $lang['AD_AUTH_GUESTS']);

$ad_active_array = array(1, 0);
$ad_active_lang_array = array($lang['Yes'], $lang['No']);

$ad_format_array = array(1, 0);
$ad_format_lang_array = array($lang['BBCode'], $lang['HTML']);

$mode_array = array('add', 'delete', 'save', 'update');
$mode = request_var('mode', '');
$mode = (in_array($mode, $mode_array) ? $mode : '');

$update = request_var('update', false);
if ($update)
{
	$mode = 'update';
}

$ad_id = request_var('ad_id', 0);
$ad_title = request_var('ad_title', '');
$ad_text = request_var('ad_text', '');
$ad_position = request_var('ad_position', '');
$ad_position = in_array($ad_position, $ad_positions_array) ? $ad_position : $ad_positions_array[0];
$ad_auth = request_var('ad_auth', 0);
$ad_format = request_var('ad_format', 0);
$ad_active = request_var('ad_active', 0);

$ad_sort_by = request_var('sort_by', '');
$ad_sort_by_array = array('ad_position', 'ad_id', 'ad_title', 'ad_auth', 'ad_format', 'ad_active');
$ad_sort_by = in_array($ad_sort_by, $ad_sort_by_array) ? $ad_sort_by : $ad_sort_by_array[0];
$ad_sort_order = request_var('sort_order', '');

$show_cms_menu = (($userdata['user_level'] == ADMIN) || ($userdata['user_cms_level'] == CMS_CONTENT_MANAGER)) ? true : false;
$template->assign_vars(array(
	'S_CMS_AUTH' => true,
	'S_SHOW_CMS_MENU' => $show_cms_menu
	)
);

if ($config['cms_dock'])
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

if($mode == 'save')
{
	if(($ad_title == '') || ($ad_text == ''))
	{
		message_die(GENERAL_MESSAGE, $lang['ERR_AD_ADD']);
	}

	$input_table = ADS_TABLE;
	// htmlspecialchars_decode is supported only since PHP 5+ (an alias has been added into functions.php, if you want to use a PHP 4 default function you can use html_entity_decode instead)
	$input_array = array(
		'ad_title' => '\'' . addslashes($ad_title) . '\'',
		'ad_text' => '\'' . htmlspecialchars_decode(addslashes($ad_text), ENT_COMPAT) . '\'',
		'ad_position' => '\'' . $ad_position . '\'',
		'ad_auth' => $ad_auth,
		'ad_format' => $ad_format,
		'ad_active' => $ad_active,
	);

	$input_fields_sql = '';
	$input_values_sql = '';
	$update_sql = '';
	foreach ($input_array as $k => $v)
	{
		$input_fields_sql .= (($input_fields_sql == '') ? ('(' . $k) : (', ' . $k));
		$input_values_sql .= (($input_values_sql == '') ? ('(' . $v) : (', ' . $v));
		$update_sql .= (($update_sql == '') ? ($k . ' = ' . $v) : (', ' . $k . ' = ' . $v));
	}
	$input_fields_sql .= (($input_fields_sql == '') ? '' : ')');
	$input_values_sql .= (($input_values_sql == '') ? '' : ')');

	$where_sql = ' WHERE ad_id = ' . $ad_id;

	if(($ad_id > 0) && !empty($update_sql))
	{
		$message = $lang['AD_UPDATED'];
		$sql = "UPDATE " . $input_table . " SET " . $update_sql . $where_sql;
		$result = $db->sql_query($sql);
	}
	elseif(!empty($input_fields_sql))
	{
		$message = $lang['AD_ADDED'];
		$sql = "INSERT INTO " . $input_table . " " . $input_fields_sql . " VALUES " . $input_values_sql;
		$result = $db->sql_query($sql);
	}
	else
	{
		$message = $lang['Error'];
	}
	$db->clear_cache('ads_');
	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_ADS'], '<a href="' . append_sid('cms_ads.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'delete')
{
	$sql = "DELETE FROM " . ADS_TABLE . "
		WHERE ad_id = " . $ad_id;
	$result = $db->sql_query($sql);
	$db->clear_cache('ads_');
	$message = $lang['AD_DELETED'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_ADS'], '<a href="' . append_sid('cms_ads.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'update')
{
	$ads_upd = array();
	$ads_upd = $_POST['ads'];
	$ads_upd_n = sizeof($ads_upd);
	$sql_no_gb = '';

	$sql = "SELECT * FROM " . ADS_TABLE;
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$a_active = empty($ads_upd) ? 0 : (in_array($row['ad_id'], $ads_upd) ? 1 : 0);
		$sql_upd = "UPDATE " . ADS_TABLE . "
						SET ad_active = '" . $a_active . "'
						WHERE ad_id = " . $row['ad_id'];
		$result_upd = $db->sql_query($sql_upd);
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < sizeof($ad_positions_array); $i++)
	{
		set_config('ads_' . $ad_positions_array[$i], request_var('ads_' . $ad_positions_array[$i], 0));
	}

	$db->clear_cache('ads_');
	$message = $lang['AD_UPDATED'];
	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_ADS'], '<a href="' . append_sid('cms_ads.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);

}
elseif ($mode == 'add')
{
	$template_to_parse = CMS_TPL . 'cms_ads_add_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_ADS']);

	if ($ad_id > 0)
	{
		$sql = "SELECT *
			FROM " . ADS_TABLE . "
			WHERE ad_id = " . $ad_id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$ad_id = $row['ad_id'];
		$ad_title = stripslashes($row['ad_title']);
		$ad_text = htmlspecialchars(stripslashes($row['ad_text']));
		$ad_position = $row['ad_position'];
		$ad_auth = $row['ad_auth'];
		$ad_format = $row['ad_format'];
		$ad_active = $row['ad_active'];
	}

	$ad_active = ($ad_id > 0) ? $ad_active : 1;
	$ad_auth = ($ad_id > 0) ? $ad_auth : AUTH_ADMIN;

	$ad_position_select = $class_form->build_select_box('ad_position', $row['ad_position'], $ad_positions_array, $ad_positions_lang_array, '');
	$ad_auth_select = $class_form->build_select_box('ad_auth', $ad_auth, $ad_auths_array, $ad_auths_lang_array, '');
	$ad_format_radio = $class_form->build_radio_box('ad_format', $row['ad_format'], $ad_format_array, $ad_format_lang_array, '');
	$ad_active_radio = $class_form->build_radio_box('ad_active', $ad_active, $ad_active_array, $ad_active_lang_array, '');

	$template->assign_vars(array(
		'L_FORM_TITLE' => (($ad_id > 0) ? $lang['AD_EDIT'] : $lang['AD_ADD']),
		'AD_TITLE' => $ad_title,
		'AD_TEXT' => $ad_text,
		'AD_POSITION' => $ad_position_select,
		'AD_AUTH' => $ad_auth_select,
		'AD_FORMAT' => $ad_format_radio,
		'AD_ACTIVE' => $ad_active_radio,

		'S_HIDDEN_FIELDS' => '<input type="hidden" name="ad_id" value="' . $ad_id . '" /><input type="hidden" name="mode" value="save" />',
		'S_ADS_ACTION' => append_sid('cms_ads.' . PHP_EXT . '?mode=save'),
		)
	);

}
else
{
	// Main Page
	$template_to_parse = CMS_TPL . 'cms_ads_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_ADS']);

	$u_sort_order = (($ad_sort_order == 'ASC') ? 'DESC' : 'ASC');
	$template->assign_vars(array(
		'U_AD_SORT_ID' => append_sid('cms_ads.' . PHP_EXT . '?sort_by=ad_id&amp;sort_order=' . $u_sort_order),
		'U_AD_SORT_TITLE' => append_sid('cms_ads.' . PHP_EXT . '?sort_by=ad_title&amp;sort_order=' . $u_sort_order),
		'U_AD_SORT_POSITION' => append_sid('cms_ads.' . PHP_EXT . '?sort_by=ad_position&amp;sort_order=' . $u_sort_order),
		'U_AD_SORT_ACTIVE' => append_sid('cms_ads.' . PHP_EXT . '?sort_by=ad_active&amp;sort_order=' . $u_sort_order),
		'U_AD_AUTH' => append_sid('cms_ads.' . PHP_EXT . '?sort_by=ad_auth&amp;sort_order=' . $u_sort_order),
		'U_AD_FORMAT' => append_sid('cms_ads.' . PHP_EXT . '?sort_by=ad_format&amp;sort_order=' . $u_sort_order),

		'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="add" />',
		'S_ADS_ACTION' => append_sid('cms_ads.' . PHP_EXT . '?mode=add'),
		)
	);

	for ($i = 0; $i < sizeof($ad_positions_array); $i++)
	{
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$template->assign_block_vars('ads_cfg', array(
			'ROW_CLASS' => $row_class,
			'AD_CFG' => $ad_positions_lang_array[$i],
			'AD_RADIO' => $class_form->build_radio_box($ad_positions_cfg_array[$i], $config[$ad_positions_cfg_array[$i]], $ad_positions_cfg_value_array, $ad_positions_cfg_lang_array, ''),
			)
		);
	}

	$sql_sort = 'ad_id ASC';
	if ($ad_sort_by != '')
	{
		$sql_sort = $ad_sort_by . (($ad_sort_order == 'DESC') ? ' DESC' : ' ASC');
	}

	$sql = "SELECT *
		FROM " . ADS_TABLE . "
		ORDER BY " . $sql_sort;
	$result = $db->sql_query($sql);

	$i = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$i++;
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$ad_auth_lang = $lang['AD_AUTH_GUESTS'];
		switch ($row['ad_auth'])
		{
			case AUTH_ALL:
				$ad_auth_lang = $lang['AD_AUTH_GUESTS'];
				break;
			case AUTH_REG:
				$ad_auth_lang = $lang['AD_AUTH_REG'];
				break;
			case AUTH_MOD:
				$ad_auth_lang = $lang['AD_AUTH_MOD'];
				break;
			case AUTH_ADMIN:
				$ad_auth_lang = $lang['AD_AUTH_ADMIN'];
				break;
		}

		$template->assign_block_vars('ads', array(
			'ROW_CLASS' => $row_class,
			'AD_ID' => $row['ad_id'],
			'AD_ACTIVE' => ($row['ad_active'] ? $lang['YES'] : $lang['NO']),
			'AD_ACTIVE_CHECKED' => ($row['ad_active'] ? ' checked="checked"' : ''),
			'AD_TITLE' => $row['ad_title'],
			'AD_FORMAT' => ($row['ad_format'] ? $lang['BBCode'] : $lang['HTML']),
			'AD_AUTH' => $ad_auth_lang,
			'AD_POSITION' => $lang['AD_POS_' . strtoupper($row['ad_position'])],

			'U_EDIT' => append_sid('cms_ads.' . PHP_EXT . '?mode=add&amp;ad_id=' . $row['ad_id']),
			'U_DELETE' => append_sid('cms_ads.' . PHP_EXT . '?mode=delete&amp;ad_id=' . $row['ad_id'])
			)
		);
	}

	if($i == 0)
	{
		$template->assign_block_vars('no_ads', array());
	}
	$db->sql_freeresult($result);

}

full_page_generation($template_to_parse, $lang['Home'], '', '');

?>