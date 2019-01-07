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

define('MOD_VERSION', '2.0.5');
define('MOD_CODE', 1);
define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['100_Jr_Admin'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include_once(IP_ROOT_PATH . 'includes/functions_jr_admin_acp.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
include_once('pagestart.' . PHP_EXT);

setup_extra_lang(array('lang_jr_admin'));

/**************************************************************************
* Module Actual Start
**************************************************************************/
/* If for some reason you need to disable the version check in THIS HACK ONLY,
change the blow to true instead of false.  No other hacks will be affected
by this change.
*/
define('DISABLE_VERSION_CHECK', false);
/* Debugging for this file */
$debug = false;

/**************************************************************************
* Constants and Main Vars.
**************************************************************************/
$status_message = '';
//Check for color groups mod
define('UPDATE_MODULE_PREFIX', 'update_module_');
$update_find_pattern = "/^.+_" . UPDATE_MODULE_PREFIX . "/";

/**************************************************************************
* Get parameters.  'var_name' => 'default'
**************************************************************************/
if ($debug)
{
	//Dump out the get and post vars if in debug mode
	echo '<pre><div class="gensmall"><span class="text_blue">DEBUG - POST VARS -</span><br /><span class="text_blue">';
	print_r($_POST);
	echo '</span><br />';
	echo '<span class="text_red">DEBUG - GET VARS -</span><br /><span class="text_red">';
	print_r($_GET);
	echo '</span><br /></div></pre>';
}

$user_search = request_var('user_search', '', true);
$alphanum = request_var('alphanum', '', true);
$params = array(
	'mode' => '',
	'user_id' => '',
	'color_group_id' => '',
	'order' => 'ASC',
	'sort_item' => 'username',
	'start' => 0,
);
foreach($params as $var => $default)
{
	$$var = request_var($var, $default);
}
// constrain $order value to prevent SQL injection
$order = ($order == 'ASC') ? 'ASC' : 'DESC';

// Check for edit user
if (sizeof($_POST))
{
	foreach ($_POST as $key => $val)
	{
		if (preg_match("/^edit_user_/", $key))
		{
			$user_id = str_replace('edit_user_', '', $key);
			$user_id = (int) intval($user_id);
		}
	}
}
$meta_content['page_title'] = $lang['Jr_Admin'];
$page_desc = $lang['Permissions_Page_Desc'];

if (!empty($user_id) && !isset($_POST['update_user']))
{
	$sql = "SELECT username, user_id, user_active, user_color, user_level  FROM " . USERS_TABLE . "
		WHERE user_id = '" . $user_id . "'
		ORDER BY username ASC";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if ($debug)
	{
		//Dump out the get and post vars if in debug mode
		echo '<pre><div class="gensmall"><span class="text_green">DEBUG - User Info -</span><br /><span class="text_green">';
		print_r($row);
		echo '</span><br /></div></pre>';
	}
	$jr_admin_row = jr_admin_get_user_info($user_id);
	$module_list = jr_admin_get_module_list();
	$user_module_list = explode(EXPLODE_SEPARATOR_CHAR, $jr_admin_row['user_jr_admin']);
	if ($debug)
	{
		//Dump out the get and post vars if in debug mode
		echo '<pre><div class="gensmall"><span class="text_blue">DEBUG - Modules -</span><br /><span class="text_blue">';
		print_r($module_list);
		echo '</span><br />';
		echo '<span class="text_red">DEBUG - User Modules -</span><br /><span class="text_red">';
		print_r($user_module_list);
		echo '</span><br /></div></pre>';
	}

	jr_admin_include_all_lang_files();

	$i = 0;
	foreach($module_list as $cat => $info_array)
	{
		$cat_started = false;
		foreach($info_array as $module_name => $file_array)
		{
			if ($file_array['junior_admin'] == true)
			{
				if ($cat_started == false)
				{
					$template->assign_block_vars('catrow', array(
						'CAT' => (isset($lang[$cat])) ? $lang[$cat] : preg_replace("/_/", ' ', $cat),
						'NUM' => $i,
						)
					);
					$cat_started = true;
				}
				$file_hash = $file_array['file_hash'];
				$checked = (in_array($file_hash, $user_module_list)) ? 'checked="checked"' : '';
				$template->assign_block_vars('catrow.modulerow', array(
					'ROW' => ($i % 2) ? 'row1' : 'row2',
					'NAME' => (isset($lang[$module_name])) ? $lang[$module_name] : preg_replace("/_/", ' ', $module_name),
					'FILENAME' => $file_array['filename'],
					'FILE_HASH' => $file_hash,
					'CHECKED' => $checked
					)
				);
			}
		}
		$i++;
	}

	$disabled = 'disabled';
	$disabled_text = $lang['Disabled_Color_Groups'];
	$template->assign_vars(array(
		'USER_ID' => $user_id,
		'USERNAME_FULL' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
		'USERNAME' => $row['username'],
		'DISABLED' => $disabled,
		'DISABLED_TEXT' => $disabled_text,
		'START_DATE' => ($jr_admin_row['start_date']) ? create_date_ip($config['default_dateformat'], $jr_admin_row['start_date'], $config['board_timezone']) : $lang['Never'],
		'UPDATE_DATE' => ($jr_admin_row['update_date']) ? create_date_ip($config['default_dateformat'], $jr_admin_row['update_date'], $config['board_timezone']) : $lang['Never'],
		'NOTES' => $jr_admin_row['admin_notes'],
		'NOTES_VIEW_CHECKED' => ($jr_admin_row['notes_view']) ? 'checked="checked"' : '',
		'ADMIN_TEXT' => ($row['user_level'] == ADMIN) ? $lang['Admin_Note'] : ''
		)
	);

	$template->set_filenames(array('body' => ADM_TPL . 'jr_admin_user_permissions.tpl'));
}
else
{
	//Update info like module list and color groups
	if (isset($_POST['update_user']) && !empty($user_id))
	{
		$user_update_list = '';
		foreach ($_POST as $key => $val)
		{
			if (preg_match($update_find_pattern, $key))
			{
				$user_update_list .= (!empty($user_update_list)) ? EXPLODE_SEPARATOR_CHAR : '';
				$user_update_list .= preg_replace($update_find_pattern, '', $key);
			}
		}

		if (!jr_admin_user_exist($user_id))
		{
			//If the user_id doesn't exist in the table, we need to add it
			//before we can update!
			sql_query_nivisec(
			'INSERT INTO ' . JR_ADMIN_TABLE . "
			(user_id, start_date) VALUES ($user_id, " . time() . ')',
			$lang['Error_Module_Table']
			);
		}

		$notes_view = (isset($_POST['notes_view'])) ? 1 : 0;
		$admin_notes = $_POST['admin_notes'];

		//Do the information update
		$sql = 'UPDATE ' . JR_ADMIN_TABLE . "
			SET user_jr_admin = '$user_update_list',
			update_date = " . time() . ",
			admin_notes = '" . $db->sql_escape($admin_notes) . "',
			notes_view = $notes_view
			WHERE user_id = $user_id";
		$db->sql_query($sql);
		$status_message .= $lang['Updated_Permissions'];
		clear_user_color_cache($user_id);
	}

	//No user_id was found or we are done updating, take them to the info page
	$alpha_where = '';
	$proof = '';
	if (!$sort_item) $sort_item = 'username';
	for ($i = 97; $i <= 122; $i++)
	{
		$proof .= " AND u.username NOT LIKE '" . chr($i) . "%' ";
	}
	$alpha_where = ($alphanum == '0') ? $proof : (($alphanum != '') ? "AND u.username LIKE '" . $db->sql_escape($alphanum) . "%'" : '');

	$user_where = !empty($user_search) ? " AND u.username LIKE ('" . $db->sql_escape($user_search) . "'%)" : '';

	$per_page = $config['topics_per_page'];
	if ($sort_item == 'user_modules')
	{
		$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_rank, u.user_allow_pm, u.user_allowavatar
			FROM " . USERS_TABLE . " u, " . JR_ADMIN_TABLE . " j
			WHERE u.user_id <> " . ANONYMOUS . "
			$alpha_where
			$user_where
				AND j.user_id = u.user_id
			ORDER BY u.username ASC
			LIMIT $start, $per_page";
	}
	else
	{
		$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_rank, u.user_allow_pm, u.user_allowavatar
			FROM " . USERS_TABLE . " u
			WHERE u.user_id <> " . ANONYMOUS . "
			$alpha_where
			$user_where
			ORDER BY u." . $sort_item . " " . $order . "
			LIMIT $start, $per_page";
	}
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$jr_admin_row = jr_admin_get_user_info($row['user_id']);
		$module_count = (!empty($jr_admin_row['user_jr_admin'])) ? sizeof(explode(EXPLODE_SEPARATOR_CHAR, $jr_admin_row['user_jr_admin'])) : 0;
		$block_text = 'userrow';

		$template->assign_block_vars($block_text, array(
			'USERNAME_FULL' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
			'NAME' => $row['username'],
			'ID' => $row['user_id'],
			'ALLOW_PM' => ($row['user_allow_pm']) ? 'checked="checked"' : '',
			'ALLOW_AVATAR' => ($row['user_allowavatar']) ? 'checked="checked"' : '',
			'ACTIVE' =>($row['user_active']) ? 'checked="checked"' : '',
			'ROW_CLASS' => ($i++ % 2) ? 'row1' : 'row2',
			'RANK_LIST' => jr_admin_make_rank_list($row['user_id'], $row['user_rank']),
			'BOOKMARK' => (!$assigned_current_letter_link) ? '<a id="' . $current_letter . '">' : '',
			'BOOKMARK_END' => (!$assigned_current_letter_link) ? '</a>' : '',
			'MODULES' => ($module_count != 0) ? sprintf($lang['Modules_Owned'], $module_count) : '&nbsp;',
			'MODULE_COUNT' => ($module_count != 0) ? sprintf($lang['Modules_Owned'], $module_count) : ''
			)
		);
		//We 'know' we assigned it if it wasn't already now
		$assigned_current_letter_link = true;
	}

	if ($sort_item == 'user_modules')
	{
		$sql = "SELECT u.user_id
			FROM " . USERS_TABLE . " u, " . JR_ADMIN_TABLE . " j
			WHERE u.user_id <> " . ANONYMOUS . "
			$alpha_where
			$user_where
				AND j.user_id = u.user_id";
	}
	else
	{
		$sql = "SELECT u.user_id
			FROM " . USERS_TABLE . " u
			WHERE u.user_id <> " . ANONYMOUS . "
			$alpha_where
			$user_where";
	}
	$result = $db->sql_query($sql);
	$row = $db->sql_numrows($result);
	$total_users_count = $row;

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination('admin_jr_admin.' . PHP_EXT . '?sort_item=' . $sort_item . '&amp;order=' . $order . '&amp;alphanum=' . $alphanum, $total_users_count, $per_page, $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $per_page) + 1), ceil($total_users_count / $per_page))
		)
	);

	//Make sort image choice and sorting links
	$base_order = ($order == 'ASC') ? 'order=DESC' : 'order=ASC';
	$base_filename = append_sid(basename(__FILE__) . '?' . $base_order);
	$desc_img = '<img src="' . IP_ROOT_PATH . $lang['DESC_Image'] . '" alt="" />';
	$asc_img = '<img src="' . IP_ROOT_PATH . $lang['ASC_Image'] . '" alt="" />';
	$template->assign_vars(array(
		'IMG_USERNAME' => ($sort_item == 'username') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
		'IMG_MODULES' => ($sort_item == 'user_modules') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
		'IMG_RANK' => ($sort_item == 'user_rank') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
		'IMG_ACTIVE' => ($sort_item == 'user_active') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
		'IMG_AVATAR' => ($sort_item == 'user_allowavatar') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
		'IMG_PM' => ($sort_item == 'user_allow_pm') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
		'S_USERNAME' => $base_filename . '&amp;sort_item=username&amp;alphanum=' . $alphanum,
		'S_MODULES' => $base_filename . '&amp;sort_item=user_modules&amp;alphanum=' . $alphanum,
		'S_RANK' => $base_filename . '&amp;sort_item=user_rank&amp;alphanum=' . $alphanum,
		'S_ACTIVE' => $base_filename . '&amp;sort_item=user_active&amp;alphanum=' . $alphanum,
		'S_AVATAR' => $base_filename . '&amp;sort_item=user_allowavatar&amp;alphanum=' . $alphanum,
		'S_PM' => $base_filename . '&amp;sort_item=user_allow_pm&amp;alphanum=' . $alphanum
		)
	);

	$sql = "SELECT username FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$test_letter = strtoupper(substr($row['username'], 0, 1));
		if ($test_letter != $current_letter)
		{
			//If we have a new letter, get it here.
			$current_letter = $test_letter;
			$assigned_current_letter_link = false;
			$letter_list[ord($current_letter)] = true;
		}
	}

	if ($sort_item == 'username')
	{
		$template->assign_var('LETTER_HEADING', jr_admin_make_bookmark_heading($letter_list, $start));
	}

	$template->set_filenames(array('body' => ADM_TPL . 'jr_admin_user_list.tpl'));
}
//Common Variables
$template->assign_vars(array(
	'S_ACTION' => append_sid(basename(__FILE__)),
	'S_USER_PERM' => append_sid('admin_ug_auth.' . PHP_EXT),
	'S_PROFILE' => append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE),
	'S_MANAGEMENT' => append_sid('admin_users.' . PHP_EXT),
	'S_USER_POST_URL' => POST_USERS_URL,
	'L_SEARCH' => $lang['Search'],
	'L_NONE' => $lang['None'],
	'L_ALLOW' => $lang['Allow_Access'],
	'L_VERSION' => $lang['Version'],
	'L_PAGE_NAME' => $meta_content['page_title'],
	'L_PAGE_DESC' => $page_desc,
	'MOD_NUMBER' => MOD,
	'L_COLOR_GROUP' => $lang['Color_Group'],
	'VERSION' => MOD_VERSION,
	'L_USERS_W_ACCESS' => $lang['Users_with_Access'],
	'L_USERS_WOUT_ACCESS' => $lang['Users_without_Access'],
	'L_MODULES' => $lang['Modules'],
	'L_MODULE_COUNT' => $lang['Module_Count'],
	'L_EDIT' => $lang['Edit'],
	'L_UPDATE' => $lang['Update'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_EXAMPLE' => $lang['Example'],
	'L_MODULE_INFO' => $lang['Module_Info'],
	'L_CHECK_ALL_IN_CAT' => $lang['Cat_Check_All'],
	'L_CHECK_ALL' => $lang['Check_All'],
	'L_OPTIONS' => $lang['Options'],
	'L_EDIT_PERMISSIONS' => $lang['Edit_Permissions'],
	'L_VIEW_PROFILE' => $lang['View_Profile'],
	'L_EDIT_USER_DETAILS' => $lang['Edit_User_Details'],
	'L_NOTES' => $lang['Notes'],
	'L_ALLOW_VIEW' => $lang['Allow_View'],
	'L_START_DATE' => $lang['Start_Date'],
	'L_UPDATE_DATE' => $lang['Update_Date'],
	'L_USERNAME' => $lang['Username'],
	'L_EDIT_LIST' => $lang['Edit_Modules'],
	'L_USER_STATS' => $lang['User_Stats'],
	'L_USER_INFO' => $lang['User_Info'],
	'L_ACTIVE' => $lang['User_Active'],
	'L_PM' => $lang['Allow_PM'],
	'L_AVATAR' => $lang['Allow_Avatar'],
	'L_COLOR_GROUP' => $lang['Color_Group'],
	'L_RANK' => $lang['Rank'],
	'L_ADMIN_NOTES' => $lang['Admin_Notes']
	)
);

if ($status_message != '')
{
	$template->assign_block_vars('statusrow', array());
	$template->assign_vars(array(
		'L_STATUS' => $lang['Status'],
		'I_STATUS_MESSAGE' => $status_message
		)
	);
}
/************************************************************************
* Begin The Version Check Feature
************************************************************************/
if (file_exists(IP_ROOT_PATH . 'nivisec_version_check.' . PHP_EXT) && !DISABLE_VERSION_CHECK)
{
	include(IP_ROOT_PATH . 'nivisec_version_check.' . PHP_EXT);
}
/************************************************************************
* End The Version Check Feature
************************************************************************/

$template->pparse('body');
copyright_nivisec($meta_content['page_title'], '2002-2003');
include('page_footer_admin.' . PHP_EXT);

?>