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
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* This File is the ACP Module to manage all CrackerTracker Settings
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if (!defined('IN_ICYPHOENIX') || !defined('CTRACKER_ACP'))
{
	die('Hacking attempt!');
}

include(IP_ROOT_PATH . 'includes/ctracker/constants.' . PHP_EXT);

/*
* Submit?
*/
if(isset($_POST['submit']))
{
	// Update new config
	for($i = 0; $i < sizeof($ct_config_array); $i++)
	{
		if (isset($_POST[$ct_config_array[$i]]))
		{
			set_config($ct_config_array[$i], $_POST[$ct_config_array[$i]], false);
		}
	}
	$cache->destroy('config');
}


/*
* Generate Objects we need and initialize used vars
*/
$configuration = array();
$adminclass = new ct_adminfunctions();

/*
* Set correct configuration values
* We do this for each config value seperate - more code but safer handling
*/
$configuration['ctracker_ipblock_enabled'] = $adminclass->ct_generate_on_off($config['ctracker_ipblock_enabled']);
$configuration['ctracker_ipblock_logsize'] = $adminclass->ct_generate_number_field(1, 400, $config['ctracker_ipblock_logsize']);
$configuration['ctracker_search_feature_enabled'] = $adminclass->ct_generate_on_off($config['ctracker_search_feature_enabled']);
$configuration['ctracker_search_time_user'] = $adminclass->ct_generate_number_field(1, 90, $config['ctracker_search_time_user']);
$configuration['ctracker_search_count_user'] = $adminclass->ct_generate_number_field(1, 6, $config['ctracker_search_count_user']);
$configuration['ctracker_search_time_guest'] = $adminclass->ct_generate_number_field(1, 90, $config['ctracker_search_time_guest']);
$configuration['ctracker_search_count_guest'] = $adminclass->ct_generate_number_field(1, 6, $config['ctracker_search_count_guest']);
$configuration['ctracker_loginfeature'] = $adminclass->ct_generate_on_off($config['ctracker_loginfeature']);
$configuration['ctracker_logsize_logins'] = $adminclass->ct_generate_number_field(1, 400, $config['ctracker_logsize_logins']);
$configuration['ctracker_login_history'] = $adminclass->ct_generate_on_off($config['ctracker_login_history']);
$configuration['ctracker_login_history_count'] = $adminclass->ct_generate_number_field(1, 60, $config['ctracker_login_history_count']);
$configuration['ctracker_login_ip_check'] = $adminclass->ct_generate_on_off($config['ctracker_login_ip_check']);
$configuration['ctracker_spammer_blockmode'] = $adminclass->ct_spammer_block($config['ctracker_spammer_blockmode']);
$configuration['ctracker_spammer_postcount'] = $adminclass->ct_generate_number_field(1, 12, $config['ctracker_spammer_postcount']);
$configuration['ctracker_spammer_time'] = $adminclass->ct_generate_number_field(1, 90, $config['ctracker_spammer_time']);
$configuration['ctracker_logsize_spammer'] = $adminclass->ct_generate_number_field(1, 400, $config['ctracker_logsize_spammer']);
$configuration['ctracker_reg_protection'] = $adminclass->ct_generate_on_off($config['ctracker_reg_protection']);
$configuration['ctracker_reg_blocktime'] = $adminclass->ct_generate_number_field(1, 200, $config['ctracker_reg_blocktime']);
$configuration['ctracker_reg_ip_scan'] = $adminclass->ct_generate_on_off($config['ctracker_reg_ip_scan']);
$configuration['ctracker_pw_control'] = $adminclass->ct_generate_on_off($config['ctracker_pw_control']);
$configuration['ctracker_pw_validity'] = $adminclass->ct_generate_number_field(6, 365, $config['ctracker_pw_validity']);
$configuration['ctracker_pw_complex'] = $adminclass->ct_generate_on_off($config['ctracker_pw_complex']);
$configuration['ctracker_pw_complex_mode'] = $adminclass->ct_complex_mode($config['ctracker_pw_complex_mode']);
$configuration['ctracker_pw_complex_min'] = $adminclass->ct_generate_number_field(1, 20, $config['ctracker_pw_complex_min']);
$configuration['ctracker_pw_reset_feature'] = $adminclass->ct_generate_on_off($config['ctracker_pw_reset_feature']);
$configuration['ctracker_pwreset_time'] = $adminclass->ct_generate_number_field(1, 180, $config['ctracker_pwreset_time']);
$configuration['ctracker_massmail_protection'] = $adminclass->ct_generate_on_off($config['ctracker_massmail_protection']);
$configuration['ctracker_massmail_time'] = $adminclass->ct_generate_number_field(1, 180, $config['ctracker_massmail_time']);
$configuration['ctracker_auto_recovery'] = $adminclass->ct_generate_on_off($config['ctracker_auto_recovery']);
$configuration['ctracker_vconfirm_guest'] = $adminclass->ct_generate_on_off($config['ctracker_vconfirm_guest']);
$configuration['ctracker_autoban_mails'] = $adminclass->ct_generate_on_off($config['ctracker_autoban_mails']);
$configuration['ctracker_spam_attack_boost'] = $adminclass->ct_generate_on_off($config['ctracker_spam_attack_boost']);
$configuration['ctracker_spam_keyword_det'] = $adminclass->ct_keyword_b_block($config['ctracker_spam_keyword_det']);

/*
 * Kick Objects we don't use anymore now
 */
unset($adminclass);

/*
 * Output the page
 */
$template->set_filenames(array('ct_body' => ADM_TPL . 'acp_settings.tpl'));

// Send some vars to the template
$template->assign_vars(array(
	'L_CATNAME_1' => $lang['ctracker_set_catname1'],
	'L_CATNAME_2' => $lang['ctracker_set_catname2'],
	'L_CATNAME_3' => $lang['ctracker_set_catname3'],
	'L_CATNAME_4' => $lang['ctracker_set_catname4'],
	'L_CATNAME_5' => $lang['ctracker_set_catname5'],
	'L_CATNAME_6' => $lang['ctracker_set_catname6'],
	'L_CATNAME_7' => $lang['ctracker_set_catname7'],
	'L_CATNAME_8' => $lang['ctracker_set_catname8'],

	'L_CT_HEAD' => $lang['ctracker_settings_head'],
	'L_CT_EXPL' => $lang['ctracker_settings_expl'],
	'L_SUBMIT' => $lang['ctracker_button_submit'],
	'L_RESET' => $lang['ctracker_button_reset'],

	'L_MOD_1' => $lang['ctracker_settings_m1'],
	'L_EXP_1' => $lang['ctracker_settings_e1'],
	'L_MOD_2' => $lang['ctracker_settings_m2'],
	'L_EXP_2' => $lang['ctracker_settings_e2'],
	'L_MOD_3' => $lang['ctracker_settings_m3'],
	'L_EXP_3' => $lang['ctracker_settings_e3'],
	'L_MOD_4' => $lang['ctracker_settings_m4'],
	'L_EXP_4' => $lang['ctracker_settings_e4'],
	'L_MOD_5' => $lang['ctracker_settings_m5'],
	'L_EXP_5' => $lang['ctracker_settings_e5'],
	'L_MOD_6' => $lang['ctracker_settings_m6'],
	'L_EXP_6' => $lang['ctracker_settings_e6'],
	'L_MOD_7' => $lang['ctracker_settings_m7'],
	'L_EXP_7' => $lang['ctracker_settings_e7'],
	'L_MOD_8' => $lang['ctracker_settings_m8'],
	'L_EXP_8' => $lang['ctracker_settings_e8'],
	'L_MOD_9' => $lang['ctracker_settings_m9'],
	'L_EXP_9' => $lang['ctracker_settings_e9'],
	'L_MOD_10' => $lang['ctracker_settings_m10'],
	'L_EXP_10' => $lang['ctracker_settings_e10'],
	'L_MOD_11' => $lang['ctracker_settings_m11'],
	'L_EXP_11' => $lang['ctracker_settings_e11'],
	'L_MOD_12' => $lang['ctracker_settings_m12'],
	'L_EXP_12' => $lang['ctracker_settings_e12'],
	'L_MOD_13' => $lang['ctracker_settings_m13'],
	'L_EXP_13' => $lang['ctracker_settings_e13'],
	'L_MOD_14' => $lang['ctracker_settings_m14'],
	'L_EXP_14' => $lang['ctracker_settings_e14'],
	'L_MOD_15' => $lang['ctracker_settings_m15'],
	'L_EXP_15' => $lang['ctracker_settings_e15'],
	'L_MOD_16' => $lang['ctracker_settings_m16'],
	'L_EXP_16' => $lang['ctracker_settings_e16'],
	'L_MOD_17' => $lang['ctracker_settings_m17'],
	'L_EXP_17' => $lang['ctracker_settings_e17'],
	'L_MOD_18' => $lang['ctracker_settings_m18'],
	'L_EXP_18' => $lang['ctracker_settings_e18'],
	'L_MOD_19' => $lang['ctracker_settings_m19'],
	'L_EXP_19' => $lang['ctracker_settings_e19'],
	'L_MOD_21' => $lang['ctracker_settings_m21'],
	'L_EXP_21' => $lang['ctracker_settings_e21'],
	'L_MOD_22' => $lang['ctracker_settings_m22'],
	'L_EXP_22' => $lang['ctracker_settings_e22'],
	'L_MOD_23' => $lang['ctracker_settings_m23'],
	'L_EXP_23' => $lang['ctracker_settings_e23'],
	'L_MOD_24' => $lang['ctracker_settings_m24'],
	'L_EXP_24' => $lang['ctracker_settings_e24'],
	'L_MOD_25' => $lang['ctracker_settings_m25'],
	'L_EXP_25' => $lang['ctracker_settings_e25'],
	'L_MOD_26' => $lang['ctracker_settings_m26'],
	'L_EXP_26' => $lang['ctracker_settings_e26'],
	'L_MOD_27' => $lang['ctracker_settings_m27'],
	'L_EXP_27' => $lang['ctracker_settings_e27'],
	'L_MOD_28' => $lang['ctracker_settings_m28'],
	'L_EXP_28' => $lang['ctracker_settings_e28'],
	'L_MOD_29' => $lang['ctracker_settings_m29'],
	'L_EXP_29' => $lang['ctracker_settings_e29'],
	'L_MOD_30' => $lang['ctracker_settings_m30'],
	'L_EXP_30' => $lang['ctracker_settings_e30'],
	'L_MOD_31' => $lang['ctracker_settings_m31'],
	'L_EXP_31' => $lang['ctracker_settings_e31'],
	'L_MOD_32' => $lang['ctracker_settings_m32'],
	'L_EXP_32' => $lang['ctracker_settings_e32'],
	'L_MOD_33' => $lang['ctracker_settings_m33'],
	'L_EXP_33' => $lang['ctracker_settings_e33'],
	'L_MOD_34' => $lang['ctracker_settings_m34'],
	'L_EXP_34' => $lang['ctracker_settings_e34'],
	'L_MOD_35' => $lang['ctracker_settings_m35'],
	'L_EXP_35' => $lang['ctracker_settings_e35'],
	'L_MOD_36' => $lang['ctracker_settings_m36'],
	'L_EXP_36' => $lang['ctracker_settings_e36'],

	'CAT_ICON_1' => $images['ctracker_icon_set_1'],
	'CAT_ICON_2' => $images['ctracker_icon_set_2'],
	'CAT_ICON_3' => $images['ctracker_icon_set_3'],
	'CAT_ICON_4' => $images['ctracker_icon_set_4'],
	'CAT_ICON_5' => $images['ctracker_icon_set_5'],
	'CAT_ICON_6' => $images['ctracker_icon_set_6'],
	'CAT_ICON_7' => $images['ctracker_icon_set_7'],
	'CAT_ICON_8' => $images['ctracker_icon_set_8'],

	'S_OUTPUT_1' => $configuration['ctracker_ipblock_enabled'],
	'S_OUTPUT_2' => $configuration['ctracker_ipblock_logsize'],
	'S_OUTPUT_3' => $configuration['ctracker_search_feature_enabled'],
	'S_OUTPUT_4' => $configuration['ctracker_search_time_user'],
	'S_OUTPUT_5' => $configuration['ctracker_search_count_user'],
	'S_OUTPUT_6' => $configuration['ctracker_search_time_guest'],
	'S_OUTPUT_7' => $configuration['ctracker_search_count_guest'],
	'S_OUTPUT_8' => $configuration['ctracker_loginfeature'],
	'S_OUTPUT_9' => $configuration['ctracker_logsize_logins'],

	'S_OUTPUT_11' => $configuration['ctracker_login_history'],
	'S_OUTPUT_12' => $configuration['ctracker_login_history_count'],
	'S_OUTPUT_13' => $configuration['ctracker_login_ip_check'],
	'S_OUTPUT_14' => $configuration['ctracker_spammer_blockmode'],
	'S_OUTPUT_15' => $configuration['ctracker_spammer_time'],
	'S_OUTPUT_16' => $configuration['ctracker_spammer_postcount'],
	'S_OUTPUT_17' => $configuration['ctracker_logsize_spammer'],
	'S_OUTPUT_18' => $configuration['ctracker_reg_protection'],
	'S_OUTPUT_19' => $configuration['ctracker_reg_blocktime'],
	'S_OUTPUT_21' => $configuration['ctracker_reg_ip_scan'],
	'S_OUTPUT_22' => $configuration['ctracker_pw_control'],
	'S_OUTPUT_23' => $configuration['ctracker_pw_validity'],
	'S_OUTPUT_24' => $configuration['ctracker_pw_complex'],
	'S_OUTPUT_25' => $configuration['ctracker_pw_complex_mode'],
	'S_OUTPUT_26' => $configuration['ctracker_pw_complex_min'],
	'S_OUTPUT_27' => $configuration['ctracker_pw_reset_feature'],
	'S_OUTPUT_28' => $configuration['ctracker_pwreset_time'],
	'S_OUTPUT_29' => $configuration['ctracker_massmail_protection'],
	'S_OUTPUT_30' => $configuration['ctracker_massmail_time'],
	'S_OUTPUT_31' => $configuration['ctracker_auto_recovery'],
	'S_OUTPUT_32' => $configuration['ctracker_vconfirm_guest'],
	'S_OUTPUT_33' => $configuration['ctracker_autoban_mails'],
	'S_OUTPUT_35' => $configuration['ctracker_spam_attack_boost'],
	'S_OUTPUT_36' => $configuration['ctracker_spam_keyword_det'],

	'S_FORM_ACTION' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=9')
	)
);

// Generate the page
$template->pparse('ct_body');

?>