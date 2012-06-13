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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

setup_extra_lang(array('lang_features'));

$img_true = '<img src="' . IP_ROOT_PATH . $images['cms_icon_ok'] . '" alt="True" />';
$img_false = '<img src="' . IP_ROOT_PATH . $images['cms_icon_cancel'] . '" alt="False" />';

$features_array = array(

	'general' => array (
		'license' => array('bb2' => $lang['bb2_license'], 'ip' => $lang['ip_license'], 'bb3' => $lang['bb3_license']),
		'price' => array('bb2' => $lang['bb2_price'], 'ip' => $lang['ip_price'], 'bb3' => $lang['bb3_price']),
		'programming' => array('bb2' => $lang['bb2_programming'], 'ip' => $lang['ip_programming'], 'bb3' => $lang['bb3_programming']),
		'latest' => array('bb2' => $lang['bb2_latest'], 'ip' => $lang['ip_latest'], 'bb3' => $lang['bb3_latest']),
		'release' => array('bb2' => $lang['bb2_release'], 'ip' => $lang['ip_release'], 'bb3' => $lang['bb3_release']),
	),

	'basic_features' => array(
		'utf8' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'user_preferences' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'mod' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'admin' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'search_engine' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'un_mess_track' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'pms' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'stat' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'security' => array(
		'perm_ban' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'temp_ban' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'permission' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'paid_sec' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'form_hand' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		't_a_p_h' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		't_a_d_l' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'p_h' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'antispam' => array(
		'c_v_c' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'f_c' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'groups' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'acls' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'b_l' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'banning' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'suspension' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'warn' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'u_l' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'u_p_ip_l' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'r_p' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'post_m' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'datamanagement' => array(
		'mysql' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'mssql' => array('bb2' => $img_true, 'ip' => $img_false, 'bb3' => $img_true),
		'mssql_odbc' => array('bb2' => $img_true, 'ip' => $img_false, 'bb3' => $img_true),
		'postgre' => array('bb2' => $img_true, 'ip' => $img_false, 'bb3' => $img_true),
		'ms_access' => array('bb2' => $img_true, 'ip' => $img_false, 'bb3' => $img_false),
		'oracle' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'firebird' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'sql_lite' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'db_back' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'db_restore' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'p_t_pruning' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'registration' => array(
		'coppa' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'l_r_a' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'username_length' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'pass_length' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'l_u_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		's_p_c_r' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'f_p_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'e_mail_address_reusage' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
	),

	'posting' => array(
		't_d_m' => array('bb2' => $lang['bb2_t_d_m'], 'ip' => $lang['ip_t_d_m'], 'bb3' => $lang['bb3_t_d_m']),
		'bbcode' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'bbcode_custom' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'html_in_post' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_false),
		'emoticon' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'quoting' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'q_m_p' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'f_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'w_c' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		's_h' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'attachments' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'p_d' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'polls' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_p_o_v' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'u_b_p_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'all_dis_v_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'beaten_p_review' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'p_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'flood_control' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'attachments' => array(
		'a_i_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'a_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'm_a' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'a_p' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'caching' => array(
		'db_q_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		't_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'a_d' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'm_c_r' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'profile' => array(
		'c_p_f' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'u_p_s' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_i_m' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_p_d' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_list_search' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'u_m_v' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'search_system' => array(
		'ft_n' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'c_t_s' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'c_f_s' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'my_sql_text' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'a_s' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'a_search' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'v_a_post' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'a_n_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'p_s_l_v' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		's_f_l' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'forums' => array(
		'categories' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'p_p_f' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'f_s_s' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'url_l_r_f' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'f_rules' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'subforum' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'last_post' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'forum_pruning' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'd_a_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		's_t_forums' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		's_t_topics' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'b_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		't_s' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'p_s' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		't_p_t' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'print_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'e_mail_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'ucp' => array(
		'u_s' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_a' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_ranks' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_o_l' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'user_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_p_s' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_s_pm_post_d' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'm_book' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'm_attach' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'm_s_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'c_p_f' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'friend_foe_list' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'priv_messaging' => array(
		'add_pm_folder' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'custom_pm_filter' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'send_multiple_reci' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'send_blind_carbon' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'send_to_group' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'f_message' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'a_book' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'message_d' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'exp_mess' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'attach_message' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'convers_view' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'c_m_h_r' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
	),

	'usergroups' => array(
		'g_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'ucp_group_manage' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_g_l' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'c_g_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'group_ranks' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'group_avatar' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'group_based_memberlist' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'mcp' => array(
		'global_moder' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'forum_moder' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		't_post_m_q' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'm_r_t_p' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		't_h' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'f_t_logs' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'l_m_l' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'p_e' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'p_l' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'post_details' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'c_p_author' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'quick_mod_tools' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'moving_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_m_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'merging_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'merging_p' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'merging_multiple_t' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'split_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'locking_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'deleting_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'copying_t' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'global_topics' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'announcement_t' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		's_topics' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'mange_bans' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'm_u_w' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'banning_by_username' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'banning_by_email' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'banning_by_ip' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'user_notes' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
	),

	'acp' => array(
		'f_s_b_c' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'l_s' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'p_i_u' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'm_r' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_groups' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'm_group_membership' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'manage_attach_setting' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'manage_user_attachment' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'user_editing' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'list_group_index' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'topic_icons' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'mass_e_mail' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'manage_report_reasons' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'module_manage' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_bbcode' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_profile_field' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_profile_field_placement' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_profile_data_types' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'manage_bans' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'ban_by_username' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'ban_by_email' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'ban_by_ip' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'styles' => array(
		's_style' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'install_style' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_style' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_themes' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'custom_imageset' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'permission' => array(
		'g_b_g_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'g_b_a_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'g_b_f_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_b_g_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_b_m_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_b_a_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'u_b_f_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'admin_perm_roles' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'user_perm_roles' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'mod_perm_roles' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'forum_perm_roles' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'mask_perm_multi' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
	),

	'notifications' => array(
		'e_mail_true' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'instant_mess' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'book_m' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
	),

	'localisations' => array(
		's_m_l_p' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'language' => array('bb2' => $lang['bb2_language'], 'ip' => $lang['ip_language'], 'bb3' => $lang['bb3_language']),
		'right_t_left' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'l_p_w_edit' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'install' => array(
		'install_wizard' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
		'update_wizard' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'converter_wizard' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'file_merge_diff_engine' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
	),

	'technical' => array(
		'cms_integra' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'xhtml_compliant' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'plugins' => array(
		'auth_plug' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'search_plug' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'cache_plug' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'captcha_plug' => array('bb2' => $img_false, 'ip' => $img_false, 'bb3' => $img_true),
		'hooks_sys' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'expand_module' => array('bb2' => $img_true, 'ip' => $img_true, 'bb3' => $img_true),
	),

	'features' => array(
		'feat_cms' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_album' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_downloads' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_kb' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_links' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_news' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_stats' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_contact_us' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_lofi' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_ajax_shoutbox' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_shoutbox' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_calendar' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_pm_notifications' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
	),

	'features_acp' => array(
		'feat_info' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_db_mntnc' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_mysqladmin' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_editable_f_r' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_multiple_ranks' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
	),

	'features_ucp' => array(
		'feat_ajax_checks' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_upi2db' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_custom_avatar' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_profile_stats' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_user_self_deactivation' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_login_archive' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
	),

	'features_bbcodes' => array(
		'feat_random_quotes' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_bbcodes' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_acronyms' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_autolinks' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_smiley_creator' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
	),

	'features_seo' => array(
		'feat_url_rewrite' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_rss' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_referers_tracking' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_bots_tracking' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_true),
		'feat_browser_tracking' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_banners' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
	),

	'features_forum' => array(
		'feat_bin' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_forum_icons' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_link_this_topic' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_edit_notes' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_topic_prefixes' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_topic_description' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_rating' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_thanks' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_topic_views' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_digests' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_upload_images' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_quick_reply' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_social_bookmars' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_ftr' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
		'feat_detailed_notifications' => array('bb2' => $img_false, 'ip' => $img_true, 'bb3' => $img_false),
	),
);

foreach ($features_array as $f_cat => $f_item)
{
	$class = 'row1';
	$template->assign_block_vars('feature_cat', array(
		'CAT_NAME' => $lang['cat_' . $f_cat],
		)
	);
	foreach ($f_item as $k => $v)
	{
		$class = ($class == 'row1') ? 'row2' : 'row1';
		$template->assign_block_vars('feature_cat.feature_item', array(
			'ROW_CLASS' => $class,
			'ITEM_NAME' => $lang['item_' . $k],
			'BB2' => $features_array[$f_cat][$k]['bb2'],
			'IP' => $features_array[$f_cat][$k]['ip'],
			'BB3' => $features_array[$f_cat][$k]['bb3'],
			)
		);
	}
}

full_page_generation('features_body.tpl', $lang['Features'], '', '');

?>