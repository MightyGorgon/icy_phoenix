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
* Chris Lennert - (calennert@users.sourceforge.net) - (http://lennertmods.sourceforge.net)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
include(IP_ROOT_PATH . 'includes/bb_usage_stats_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bb_usage_stats_functions.' . PHP_EXT);

setup_extra_lang(array('lang_bb_usage_stats'));

/******************************************************************************
 * Creates the select list for the Board Usage Stats Special Access Group
 ******************************************************************************/
function specialgrp_select($select_name, $selected_group)
{
	global $db;
	$selected_attribute = 'selected="selected"';

	/* First, add the "---------------" (value=-1) option to the list */
	$group_select = "<select name=\"$select_name\">";
	if ($selected_group == -1)
	{
		$selected = $selected_attribute;
	}

	$group_select .= "<option value=\"-1\" $selected>---------------</option>";
	$selected = ""; // Reset to empty string

	/* Next, retrieve users groups from GROUPS_TABLE */
	$sql = 'SELECT group_id, group_name FROM ' . GROUPS_TABLE . ' WHERE group_single_user <> ' . TRUE . ' ORDER BY group_name';
	$result = $db->sql_query($sql);

	/* Loop through query results adding each group to the pull-down list */
	while ($row = $db->sql_fetchrow($result))
	{
		if ($selected_group != -1)
		{
			$selected = ($row['group_id'] == $selected_group) ? $selected_attribute : '';
		}
		$group_select .= '<option value="' . $row['group_id'] . "\" $selected>" . $row['group_name'] . '</option>';
	}
	$db->sql_freeresult($result);
	unset($sql);

	$group_select .= '</select>';

	return $group_select;
}
/* =================================================================================== */

/* Determine if bb_usage_stats_viewlevel is set and, if not,
 * set it to default
 */
if(is_null($new[BBUS_CONFIGPROP_VIEWLEVEL_NAME]))
{
	$viewlevel = BBUS_CONFIGPROP_VIEWLEVEL_DEFAULT;
	set_config(BBUS_CONFIGPROP_VIEWLEVEL_NAME, $viewlevel);
	$new[BBUS_CONFIGPROP_VIEWLEVEL_NAME] = $viewlevel;
}
/* Otherwise, evaluate the viewlevel to determine which check boxes will be checked.
 */
else
{
	$viewlevel = $new[BBUS_CONFIGPROP_VIEWLEVEL_NAME];
}

/* Determine if bb_usage_stats_viewoptions is set and, if not,
 * set it to default
 */
if(is_null($new[BBUS_CONFIGPROP_VIEWOPTIONS_NAME]))
{
	$viewoptions = BBUS_CONFIGPROP_VIEWOPTIONS_DEFAULT;
	set_config(BBUS_CONFIGPROP_VIEWOPTIONS_NAME, $viewoptions);
	$new[BBUS_CONFIGPROP_VIEWOPTIONS_NAME] = $viewoptions;
}
/* Otherwise, evaluate the viewlevel to determine which check boxes will be checked.
 */
else
{
	$viewoptions = $config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME];
}

/* If the bb_usage_stats_specialgrp property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (is_null($new[BBUS_CONFIGPROP_SPECIALGRP_NAME]))
{
	set_config(BBUS_CONFIGPROP_SPECIALGRP_NAME, BBUS_CONFIGPROP_SPECIALGRP_DEFAULT);
	$specialgrp = specialgrp_select(BBUS_CONFIGPROP_SPECIALGRP_NAME, BBUS_CONFIGPROP_SPECIALGRP_DEFAULT);
}
else
{
	$specialgrp = specialgrp_select(BBUS_CONFIGPROP_SPECIALGRP_NAME, $new[BBUS_CONFIGPROP_SPECIALGRP_NAME]);
}

/* If the bb_usage_stats_prscale property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (is_null($new[BBUS_CONFIGPROP_PRSCALE_NAME]))
{
	set_config(BBUS_CONFIGPROP_PRSCALE_NAME, BBUS_CONFIGPROP_PRSCALE_DEFAULT);
	$prscale_select = scaleby_select('', BBUS_CONFIGPROP_PRSCALE_NAME, BBUS_SCALING_MIN, BBUS_SCALING_MAX, BBUS_CONFIGPROP_PRSCALE_DEFAULT);
}
else
{
	$prscale_select = scaleby_select('', BBUS_CONFIGPROP_PRSCALE_NAME, BBUS_SCALING_MIN, BBUS_SCALING_MAX, $new[BBUS_CONFIGPROP_PRSCALE_NAME]);
}

/* If the bb_usage_stats_trscale property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (is_null($new[BBUS_CONFIGPROP_TRSCALE_NAME]))
{
	set_config(BBUS_CONFIGPROP_TRSCALE_NAME, BBUS_CONFIGPROP_TRSCALE_DEFAULT);
	$trscale_select = scaleby_select('', BBUS_CONFIGPROP_TRSCALE_NAME, BBUS_SCALING_MIN, BBUS_SCALING_MAX, BBUS_CONFIGPROP_TRSCALE_DEFAULT);
}
else
{
	$trscale_select = scaleby_select('', BBUS_CONFIGPROP_TRSCALE_NAME, BBUS_SCALING_MIN, BBUS_SCALING_MAX, $new[BBUS_CONFIGPROP_TRSCALE_NAME]);
}

/* Check checkboxes for the view level */
$checkedOn = 'checked="checked"';
$chkVLAnonymous = (($viewlevel & BBUS_VIEWLEVEL_ANONYMOUS) != 0) ? $checkedOn : '';
$chkVLSelf = (($viewlevel & BBUS_VIEWLEVEL_SELF) != 0) ? $checkedOn : '';
$chkVLUsers = (($viewlevel & BBUS_VIEWLEVEL_USERS) != 0) ? $checkedOn : '';
$chkVLModerators = (($viewlevel & BBUS_VIEWLEVEL_MODERATORS) != 0) ? $checkedOn : '';
$chkVLAdmins = (($viewlevel & BBUS_VIEWLEVEL_ADMINS) != 0) ? $checkedOn : '';
$chkVLSpecialGrp = (($viewlevel & BBUS_VIEWLEVEL_SPECIALGRP) != 0) ? $checkedOn : '';

/* Check checkboxes for the view options */
$chkVOShowAllForums = (($viewoptions & BBUS_VIEWOPTION_SHOW_ALL_FORUMS) != 0) ? $checkedOn : '';
$chkVOPctUTUPColumnVisible = (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0) ? $checkedOn : '';
$chkVOMiscSectionVisible = (($viewoptions & BBUS_VIEWOPTION_MISC_SECTION_VISIBLE) != 0) ? $checkedOn : '';
$chkVOMiscTotPrunedPosts = (($viewoptions & BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_VISIBLE) != 0) ? $checkedOn : '';
$chkVOViewerScalablePR = (($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_PR) != 0) ? $checkedOn : '';
$chkVOViewerScalableTR = (($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_TR) != 0) ? $checkedOn : '';

$template->set_filenames(array('bb_usage_stats_admin_template' => ADM_TPL . 'bb_usage_stats_admin.tpl'));

$template->assign_vars(array(
	'L_BBUS_SETTINGS_CAPTION' => $lang['BBUS_Settings_Caption'],
	'L_BBUS_SETTINGS_EXPLAIN' => $lang['BBUS_Settings_Explain'],

	'L_BBUS_SETTING_VIEWLEVEL_CAPTION' => $lang['BBUS_Setting_ViewLevel_Caption'],
	'L_BBUS_SETTING_VIEWLEVEL_NOTE' => $lang['BBUS_Setting_ViewLevel_Note'],
	'L_BBUS_SETTING_VIEWLEVEL_EXPLAIN1' => $lang['BBUS_Setting_ViewLevel_Explain1'],
	'L_BBUS_SETTING_VIEWLEVEL_EXPLAIN2' => $lang['BBUS_Setting_ViewLevel_Explain2'],

	'L_BBUS_SETTING_SPECIALGRP_CAPTION' => $lang['BBUS_Setting_SpecialGrp_Caption'],
	'L_BBUS_SETTING_SPECIALGRP_EXPLAIN' => $lang['BBUS_Setting_SpecialGrp_Explain'],
	'BBUS_SETTING_SPECIALGRP_SELECT' => $specialgrp,

	'L_BBUS_SETTING_VIEWOPTIONS_CAPTION' => $lang['BBUS_Setting_ViewOptions_Caption'],
	'L_BBUS_SETTING_VIEWOPTIONS_NOTE' => $lang['BBUS_Setting_ViewOptions_Note'],
	'L_BBUS_SETTING_VIEWOPTIONS_EXPLAIN1' => $lang['BBUS_Setting_ViewOptions_Explain1'],
	'L_BBUS_SETTING_VIEWOPTIONS_EXPLAIN2' => $lang['BBUS_Setting_ViewOptions_Explain2'],
	'BBUS_SETTING_VIEWOPTIONS_VALUE' => $new[BBUS_CONFIGPROP_VIEWOPTIONS_NAME],

	'L_BBUS_VIEWLEVEL_ANONYMOUS_CAPTION' => $lang['BBUS_ViewLevel_Anonymous_Caption'],
	'L_BBUS_VIEWLEVEL_SELF_CAPTION' => $lang['BBUS_ViewLevel_Self_Caption'],
	'L_BBUS_VIEWLEVEL_USERS_CAPTION' => $lang['BBUS_ViewLevel_Users_Caption'],
	'L_BBUS_VIEWLEVEL_MODERATORS_CAPTION' => $lang['BBUS_ViewLevel_Moderators_Caption'],
	'L_BBUS_VIEWLEVEL_ADMINS_CAPTION' => $lang['BBUS_ViewLevel_Admins_Caption'],
	'L_BBUS_VIEWLEVEL_SPECIALGRP_CAPTION' => $lang['BBUS_ViewLevel_SpecialGrp_Caption'],

	'L_BBUS_VIEWLEVEL_ANONYMOUS_EXPLAIN' => $lang['BBUS_ViewLevel_Anonymous_Explain'],
	'L_BBUS_VIEWLEVEL_SELF_EXPLAIN' => $lang['BBUS_ViewLevel_Self_Explain'],
	'L_BBUS_VIEWLEVEL_USERS_EXPLAIN' => $lang['BBUS_ViewLevel_Users_Explain'],
	'L_BBUS_VIEWLEVEL_MODERATORS_EXPLAIN' => $lang['BBUS_ViewLevel_Moderators_Explain'],
	'L_BBUS_VIEWLEVEL_ADMINS_EXPLAIN' => $lang['BBUS_ViewLevel_Admins_Explain'],
	'L_BBUS_VIEWLEVEL_SPECIALGRP_EXPLAIN' => $lang['BBUS_ViewLevel_SpecialGrp_Explain'],

	'L_BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CAPTION' => $lang['BBUS_ViewOption_Show_All_Forums_Caption'],
	'L_BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CAPTION' => $lang['BBUS_ViewOption_PCTUTUP_Column_Visible_Caption'],
	'L_BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CAPTION' => $lang['BBUS_ViewOption_Misc_Section_Visible_Caption'],
	'L_BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CAPTION' => $lang['BBUS_ViewOption_Misc_TotPrunedPosts_Visible_Caption'],
	'L_BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CAPTION' => $lang['BBUS_ViewOption_Viewer_Scalable_PR_Caption'],
	'L_BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CAPTION' => $lang['BBUS_ViewOption_Viewer_Scalable_TR_Caption'],

	'L_BBUS_VIEWOPTION_SHOW_ALL_FORUMS_EXPLAIN' => $lang['BBUS_ViewOption_Show_All_Forums_Explain'],
	'L_BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_EXPLAIN' => $lang['BBUS_ViewOption_PCTUTUP_Column_Visible_Explain'],
	'L_BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_EXPLAIN' => $lang['BBUS_ViewOption_Misc_Section_Visible_Explain'],
	'L_BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_EXPLAIN' => $lang['BBUS_ViewOption_Misc_TotPrunedPosts_Visible_Explain'],
	'L_BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_EXPLAIN' => $lang['BBUS_ViewOption_Viewer_Scalable_PR_Explain'],
	'L_BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_EXPLAIN' => $lang['BBUS_ViewOption_Viewer_Scalable_TR_Explain'],

	'L_BBUS_DEFAULT_POST_RATE_SCALING_CAPTION' => $lang['BBUS_Settings_Default_Post_Rate_Scaling_Caption'],
	'L_BBUS_DEFAULT_POST_RATE_SCALING_EXPLAIN' => $lang['BBUS_Settings_Default_Post_Rate_Scaling_Explain'],

	'L_BBUS_DEFAULT_TOPIC_RATE_SCALING_CAPTION' => $lang['BBUS_Settings_Default_Topic_Rate_Scaling_Caption'],
	'L_BBUS_DEFAULT_TOPIC_RATE_SCALING_EXPLAIN' => $lang['BBUS_Settings_Default_Topic_Rate_Scaling_Explain'],

	'BBUS_SETTING_VIEWLEVEL_VALUE' => $new[BBUS_CONFIGPROP_VIEWLEVEL_NAME],

	'BBUS_VIEWLEVEL_ANONYMOUS_CHKED' => $chkVLAnonymous,
	'BBUS_VIEWLEVEL_SELF_CHKED' => $chkVLSelf,
	'BBUS_VIEWLEVEL_USERS_CHKED' => $chkVLUsers,
	'BBUS_VIEWLEVEL_MODERATORS_CHKED' => $chkVLModerators,
	'BBUS_VIEWLEVEL_ADMINS_CHKED' => $chkVLAdmins,
	'BBUS_VIEWLEVEL_SPECIALGRP_CHKED' => $chkVLSpecialGrp,

	'BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE' => BBUS_VIEWLEVEL_ANONYMOUS,
	'BBUS_VIEWLEVEL_SELF_FLAGVALUE' => BBUS_VIEWLEVEL_SELF,
	'BBUS_VIEWLEVEL_USERS_FLAGVALUE' => BBUS_VIEWLEVEL_USERS,
	'BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE' => BBUS_VIEWLEVEL_MODERATORS,
	'BBUS_VIEWLEVEL_ADMINS_FLAGVALUE' => BBUS_VIEWLEVEL_ADMINS,
	'BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE' => BBUS_VIEWLEVEL_SPECIALGRP,

	'BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CHKED' => $chkVOShowAllForums,
	'BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CHKED' => $chkVOPctUTUPColumnVisible,
	'BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CHKED' => $chkVOMiscSectionVisible,
	'BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CHKED' => $chkVOMiscTotPrunedPosts,
	'BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CHKED' => $chkVOViewerScalablePR,
	'BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CHKED' => $chkVOViewerScalableTR,

	'BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE' => BBUS_VIEWOPTION_SHOW_ALL_FORUMS,
	'BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE' => BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE,
	'BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE' => BBUS_VIEWOPTION_MISC_SECTION_VISIBLE,
	'BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE' => BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_VISIBLE,
	'BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE' => BBUS_VIEWOPTION_VIEWER_SCALABLE_PR,
	'BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE' => BBUS_VIEWOPTION_VIEWER_SCALABLE_TR,

	'BBUS_DEFAULT_POST_RATE_SCALING_SELECT' => $prscale_select,
	'BBUS_DEFAULT_TOPIC_RATE_SCALING_SELECT' => $trscale_select
	)
);

/* Process template for inclusion in board_config_body.tpl */
$template->assign_var_from_handle('BB_USAGE_STATS_ADMIN_TEMPLATE','bb_usage_stats_admin_template');

?>