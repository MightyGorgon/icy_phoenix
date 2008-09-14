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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/*
* fetch all config data
*/
$sql = "SELECT *
	FROM " . DL_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in downloads", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($_POST['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

		$new[$config_name] = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $default_config[$config_name];

		if( isset($_POST['submit']) )
		{
			if ($config_name == 'thumb_xsize')
			{
					$new[$config_name] = floor($new[$config_name]);
			}

			if ($config_name == 'thumb_ysize')
			{
					$new[$config_name] = floor($new[$config_name]);
			}

			if ($config_name == 'thumb_fsize')
			{
				if ($_POST['f_quote'] == 'kb')
				{
					$new[$config_name] = floor($new[$config_name] * 1024);
				}
				else
				{
					$new[$config_name] = floor($new[$config_name]);
				}
			}

			if ($config_name == 'physical_quota')
			{
				$x = $_POST['x_quota'];
				switch($x)
				{
					case 'kb':
						$new[$config_name] = floor($new[$config_name] * 1024);
						break;
					case 'mb':
						$new[$config_name] = floor($new[$config_name] * 1048576);
						break;
					case 'gb':
						$new[$config_name] = floor($new[$config_name] * 1073741824);
						break;
				}
			}

			if ($config_name == 'overall_traffic')
			{
				$x = $_POST['x_over'];
				switch($x)
				{
					case 'kb':
						$new[$config_name] = floor($new[$config_name] * 1024);
						break;
					case 'mb':
						$new[$config_name] = floor($new[$config_name] * 1048576);
						break;
					case 'gb':
						$new[$config_name] = floor($new[$config_name] * 1073741824);
						break;
				}
			}

			if ($config_name == 'newtopic_traffic')
			{
				$x = $_POST['x_new'];
				switch($x)
				{
					case 'kb':
						$new[$config_name] = floor($new[$config_name] * 1024);
						break;
					case 'mb':
						$new[$config_name] = floor($new[$config_name] * 1048576);
						break;
				}
			}

			if ($config_name == 'reply_traffic')
			{
				$x = $_POST['x_reply'];
				switch($x)
				{
					case 'kb':
						$new[$config_name] = floor($new[$config_name] * 1024);
						break;
					case 'mb':
						$new[$config_name] = floor($new[$config_name] * 1048576);
						break;
				}
			}

			if ($config_name == 'dl_method_quota')
			{
				$m = $_POST['m_quota'];
				switch($m)
				{
					case 'kb':
						$new[$config_name] = floor($new[$config_name] * 1024);
						break;
					case 'mb':
						$new[$config_name] = floor($new[$config_name] * 1048576);
						break;
					case 'gb':
						$new[$config_name] = floor($new[$config_name] * 1073741824);
						break;
				}
			}

			if ($config_name == 'dl_direct')
			{
				$new[$config_name] = (int)$_POST['dl_direct'];
			}

			$sql = "UPDATE " . DL_CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($_POST['submit']) )
	{
		$message = $lang['Dl_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_dl_config'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=config') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array('config' => ADM_TPL . 'dl_config_body.tpl'));

$enable_post_dl_traffic_yes = ( $new['enable_post_dl_traffic'] ) ? 'checked="checked"' : '';
$enable_post_dl_traffic_no = ( !$new['enable_post_dl_traffic'] ) ? 'checked="checked"' : '';

$stop_uploads_yes = ( $new['stop_uploads'] ) ? 'checked="checked"' : '';
$stop_uploads_no = ( !$new['stop_uploads'] ) ? 'checked="checked"' : '';

$upload_traffic_count_yes = ( $new['upload_traffic_count'] ) ? 'checked="checked"' : '';
$upload_traffic_count_no = ( !$new['upload_traffic_count'] ) ? 'checked="checked"' : '';

$disable_email_yes = ( $new['disable_email'] ) ? 'checked="checked"' : '';
$disable_email_no = ( !$new['disable_email'] ) ? 'checked="checked"' : '';

$disable_popup_yes = ( $new['disable_popup'] ) ? 'checked="checked"' : '';
$disable_popup_no = ( !$new['disable_popup'] ) ? 'checked="checked"' : '';

$disable_popup_notify_yes = ( $new['disable_popup_notify'] ) ? 'checked="checked"' : '';
$disable_popup_notify_no = ( !$new['disable_popup_notify'] ) ? 'checked="checked"' : '';

$guest_stats_show_yes = ( $new['guest_stats_show'] ) ? 'checked="checked"' : '';
$guest_stats_show_no = ( !$new['guest_stats_show'] ) ? 'checked="checked"' : '';

$dl_method_old = ( $new['dl_method'] == 1 ) ? 'checked="checked"' : '';
$dl_method_new = ( $new['dl_method'] == 2 ) ? 'checked="checked"' : '';

$dl_direct = ( $new['dl_direct'] ) ? 'checked="checked"' : '';

$use_hacklist_yes = ( $new['use_hacklist'] ) ? 'checked="checked"' : '';
$use_hacklist_no = ( !$new['use_hacklist'] ) ? 'checked="checked"' : '';

$use_ext_blacklist_yes = ( $new['use_ext_blacklist']) ? 'checked="checked"' : '';
$use_ext_blacklist_no = ( !$new['use_ext_blacklist']) ? 'checked="checked"' : '';

$allow_thumbs_yes = ( $new['allow_thumbs'] ) ? 'checked="checked"' : '';
$allow_thumbs_no = ( !$new['allow_thumbs'] ) ? 'checked="checked"' : '';

$show_footer_legend_yes = ( $new['show_footer_legend']) ? 'checked="checked"' : '';
$show_footer_legend_no = ( !$new['show_footer_legend']) ? 'checked="checked"' : '';

$show_footer_stat_yes = ( $new['show_footer_stat']) ? 'checked="checked"' : '';
$show_footer_stat_no = ( !$new['show_footer_stat']) ? 'checked="checked"' : '';

$thumbs_xsize = $new['thumb_xsize'];
$thumbs_ysize = $new['thumb_ysize'];
$thumbs_fsize = $new['thumb_fsize'];

$show_real_filetime_yes = ( $new['show_real_filetime']) ? 'checked="checked"' : '';
$show_real_filetime_no = ( !$new['show_real_filetime']) ? 'checked="checked"' : '';

$limit_desc_on_index = $new['limit_desc_on_index'];

$user_traffic_once_yes = ( $new['user_traffic_once']) ? 'checked="checked"' : '';
$user_traffic_once_no = ( !$new['user_traffic_once']) ? 'checked="checked"' : '';

$prevent_hotlink_yes = ( $new['prevent_hotlink']) ? 'checked="checked"' : '';
$prevent_hotlink_no = ( !$new['prevent_hotlink']) ? 'checked="checked"' : '';

$hotlink_action_one = ( $new['hotlink_action']) ? 'checked="checked"' : '';
$hotlink_action_two = ( !$new['hotlink_action']) ? 'checked="checked"' : '';

$edit_own_downloads_yes = ( $new['edit_own_downloads']) ? 'checked="checked"' : '';
$edit_own_downloads_no = ( !$new['edit_own_downloads']) ? 'checked="checked"' : '';

$icon_free_for_reg_yes = ( $new['icon_free_for_reg']) ? 'checked="checked"' : '';
$icon_free_for_reg_no = ( !$new['icon_free_for_reg']) ? 'checked="checked"' : '';

switch ($new['report_broken'])
{
	case 1:
		$report_broken_on = 'checked="checked"';
		$report_broken_off = '';
		$report_broken_guests = '';
		break;

	case 2:
		$report_broken_on = '';
		$report_broken_off = '';
		$report_broken_guests = 'checked="checked"';
		break;

	default:
		$report_broken_on = '';
		$report_broken_off = 'checked="checked"';
		$report_broken_guests = '';
}

$report_broken_lock_yes = ( $new['report_broken_lock']) ? 'checked="checked"' : '';
$report_broken_lock_no = ( !$new['report_broken_lock']) ? 'checked="checked"' : '';

$report_broken_message_yes = ( $new['report_broken_message']) ? 'checked="checked"' : '';
$report_broken_message_no = ( !$new['report_broken_message']) ? 'checked="checked"' : '';

$report_broken_vc_yes = ( $new['report_broken_vc']) ? 'checked="checked"' : '';
$report_broken_vc_no = ( !$new['report_broken_vc']) ? 'checked="checked"' : '';

$download_vc_yes = ( $new['download_vc']) ? 'checked="checked"' : '';
$download_vc_no = ( !$new['download_vc']) ? 'checked="checked"' : '';

$sort_preform_fix = ( $new['sort_preform']) ? 'checked="checked"' : '';
$sort_preform_user = ( !$new['sort_preform']) ? 'checked="checked"' : '';

if ($thumbs_fsize < 1024)
{
	$thumbs_fsize_out = $thumbs_fsize;
	$f_quota1 = 'checked="checked"';
	$f_quota2 = '';
}
else
{
	$thumbs_fsize_out = number_format($thumbs_fsize / 1024, 2);
	$f_quota1 = '';
	$f_quota2 = 'checked="checked"';
}

$physical_quota = $new['physical_quota'];
if ($physical_quota < 1024)
{
	$physical_quota_out = $physical_quota;
	$x_quota2 = $x_quota3 = $x_quota4 = '';
	$x_quota1 = 'checked="checked"';
}
elseif ($physical_quota < 1048576)
{
	$physical_quota_out = number_format($physical_quota / 1024, 2);
	$x_quota1 = $x_quota3 = $x_quota4 = '';
	$x_quota2 = 'checked="checked"';
}
elseif ($physical_quota < 1073741824)
{
	$physical_quota_out = number_format($physical_quota / 1048576, 2);
	$x_quota1 = $x_quota2 = $x_quota4 = '';
	$x_quota3 = 'checked="checked"';
}
else
{
	$physical_quota_out = number_format($physical_quota / 1073741824, 2);
	$x_quota1 = $x_quota2 = $x_quota3 = '';
	$x_quota4 = 'checked="checked"';
}

$overall_traffic = $new['overall_traffic'];
if ($overall_traffic < 1024)
{
	$overall_traffic_out = $overall_traffic;
	$x_over2 = $x_over3 = $x_over4 = '';
	$x_over1 = 'checked="checked"';
}
elseif ($overall_traffic < 1048576)
{
	$overall_traffic_out = number_format($overall_traffic / 1024, 2);
	$x_over1 = $x_over3 = $x_over4 = '';
	$x_over2 = 'checked="checked"';
}
elseif ($overall_traffic < 1073741824)
{
	$overall_traffic_out = number_format($overall_traffic / 1048576, 2);
	$x_over1 = $x_over2 = $x_over4 = '';
	$x_over3 = 'checked="checked"';
}
else
{
	$overall_traffic_out = number_format($overall_traffic / 1073741824, 2);
	$x_over1 = $x_over2 = $x_over3 = '';
	$x_over4 = 'checked="checked"';
}

$remain_traffic_text = $lang['Dl_remain_overall_traffic'];
$remain_traffic = $dl_config['overall_traffic'] - $dl_config['remain_traffic'];
$remain_traffic = ($remain_traffic <= 0) ? 0 : $remain_traffic;
if ($remain_traffic < 1024)
{
	$remain_traffic_out = $remain_traffic;
	$x_rem = ' ' . $lang['Dl_Bytes_long'];
}
elseif ($remain_traffic < 1048576)
{
	$remain_traffic_out = number_format($remain_traffic / 1024, 2);
	$x_rem = ' ' . $lang['Dl_KB'];
}
elseif ($remain_traffic < 1073741824)
{
	$remain_traffic_out = number_format($remain_traffic / 1048576, 2);
	$x_rem = ' ' . $lang['Dl_MB'];
}
else
{
	$remain_traffic_out = number_format($remain_traffic / 1073741824, 2);
	$x_rem = ' ' . $lang['Dl_GB'];
}
$remain_text_out = $remain_traffic_text . $remain_traffic_out . $x_rem;

$newtopic_traffic = $new['newtopic_traffic'];
$x_new1 = $x_new2 = $x_new3 = '';
if ($newtopic_traffic < 1024)
{
	$newtopic_traffic_out = $newtopic_traffic;
	$x_new1 = 'checked="checked"';
}
elseif ($newtopic_traffic < 1048576)
{
	$newtopic_traffic_out = number_format($newtopic_traffic / 1024, 2);
	$x_new2 = 'checked="checked"';
}
else
{
	$newtopic_traffic_out = number_format($newtopic_traffic / 1048576, 2);
	$x_new3 = 'checked="checked"';
}

$reply_traffic = $new['reply_traffic'];
$x_reply1 = $x_reply2 = $x_reply3 = '';
if ($reply_traffic < 1024)
{
	$reply_traffic_out = $reply_traffic;
	$x_reply1 = 'checked="checked"';
}
elseif ($reply_traffic < 1048576)
{
	$reply_traffic_out = number_format($reply_traffic / 1024, 2);
	$x_reply2 = 'checked="checked"';
}
else
{
	$reply_traffic_out = number_format($reply_traffic / 1048576, 2);
	$x_reply3 = 'checked="checked"';
}

$dl_method_quota = $new['dl_method_quota'];
if ($dl_method_quota < 1024)
{
	$dl_method_quota_out = $dl_method_quota;
	$m_quota2 = $m_quota3 = $m_quota4 = '';
	$m_quota1 = 'checked="checked"';
}
elseif ($dl_method_quota < 1048576)
{
	$dl_method_quota_out = number_format($dl_method_quota / 1024, 2);
	$m_quota1 = $m_quota3 = $m_quota4 = '';
	$m_quota2 = 'checked="checked"';
}
elseif ($dl_method_quota < 1073741824)
{
	$dl_method_quota_out = number_format($dl_method_quota / 1048576, 2);
	$m_quota1 = $m_quota2 = $m_quota4 = '';
	$m_quota3 = 'checked="checked"';
}
else
{
	$dl_method_quota_out = number_format($dl_method_quota / 1073741824, 2);
	$m_quota1 = $m_quota2 = $m_quota3 = '';
	$m_quota4 = 'checked="checked"';
}

$s_stats_perm_select = '<select name="dl_stats_perm">';
$s_stats_perm_select .= '<option value="0">' . $lang['Dl_stat_perm_all'] . '</option>';
$s_stats_perm_select .= '<option value="1">' . $lang['Dl_stat_perm_user'] . '</option>';
$s_stats_perm_select .= '<option value="2">' . $lang['Dl_stat_perm_mod'] . '</option>';
$s_stats_perm_select .= '<option value="3">' . $lang['Dl_stat_perm_admin'] . '</option>';
$s_stats_perm_select .= '</select>';
$s_stats_perm_select = str_replace('value="'.$new['dl_stats_perm'] . '">', 'value="' . $new['dl_stats_perm'] . '" selected="selected">', $s_stats_perm_select);

$user_download_limit_flag_yes = ( $new['user_download_limit_flag'] ) ? 'checked="checked"' : '';
$user_download_limit_flag_no = ( !$new['user_download_limit_flag'] ) ? 'checked="checked"' : '';

$template->assign_vars(array(
	'L_DL_LINKS_PER_PAGE' => $lang['Dl_Links_per_page'],
	'L_DL_LINKS_PER_PAGE_EXPLAIN' => 'Dl_Links_per_page',
	'L_DL_POSTS' => $lang['DL_posts'],
	'L_DL_POSTS_EXPLAIN' => 'DL_posts',
	'L_BYTES' => $lang['Dl_Bytes_long'],
	'L_CONFIGURATION_EXPLAIN' => $lang['Dl_config_explain'],
	'L_CONFIGURATION_TITLE' => $lang['Dl_config'],
	'L_DAYS' => $lang['Dl_days'],
	'L_DL_DELAY_AUTO_TRAFFIC' => $lang['Dl_delay_auto_traffic'],
	'L_DL_DELAY_AUTO_TRAFFIC_EXPLAIN' => 'Dl_delay_auto_traffic',
	'L_DL_DELAY_POST_TRAFFIC' => $lang['Dl_delay_post_traffic'],
	'L_DL_DELAY_POST_TRAFFIC_EXPLAIN' => 'Dl_delay_post_traffic',
	'L_DL_DISABLE_EMAIL' => $lang['Dl_disable_email'],
	'L_DL_DISABLE_EMAIL_EXPLAIN' => 'Dl_disable_email',
	'L_DL_DISABLE_POPUP' => $lang['Dl_disable_popup'],
	'L_DL_DISABLE_POPUP_EXPLAIN' => 'Dl_disable_popup',
	'L_DL_DISABLE_POPUP_NOTIFY' => $lang['Dl_disable_popup_notify'],
	'L_DL_DISABLE_POPUP_NOTIFY_EXPLAIN' => 'Dl_disable_popup_notify',
	'L_DL_EDIT_TIME' => $lang['DL_edit_time'],
	'L_DL_EDIT_TIME_EXPLAIN' => 'DL_edit_time',
	'L_DL_GUEST_STATS_SHOW' => $lang['Dl_guest_stats_show'],
	'L_DL_GUEST_STATS_SHOW_EXPLAIN' => 'Dl_guest_stats_show',
	'L_DL_HOTLINK_ACTION' => $lang['Dl_hotlink_action'],
	'L_DL_HOTLINK_ACTION_EXPLAIN' => 'Dl_hotlink_action',
	'L_DL_HOTLINK_ACTION_ONE' => $lang['Dl_hotlink_action_one'],
	'L_DL_HOTLINK_ACTION_TWO' => $lang['Dl_hotlink_action_two'],
	'L_DL_METHOD' => $lang['Dl_method'],
	'L_DL_METHOD_EXPLAIN' => 'Dl_method',
	'L_DL_METHOD_QUOTA' => $lang['Dl_method_quota'],
	'L_DL_METHOD_QUOTA_EXPLAIN' => 'Dl_method_quota',
	'L_DL_NEW_TIME' => $lang['DL_new_time'],
	'L_DL_NEW_TIME_EXPLAIN' => 'DL_new_time',
	'L_DL_PATH' => $lang['Download_path'],
	'L_DL_PATH_EXPLAIN' => 'Download_path',
	'L_DL_PHYSICAL_QUOTA' => $lang['Dl_physical_quota'],
	'L_DL_PHYSICAL_QUOTA_EXPLAIN' => 'Dl_physical_quota',
	'L_DL_PHYSICAL_QUOTA_SECOND' => sprintf($lang['Dl_physical_quota_explain'], $total_size),
	'L_DL_RECENT' => $lang['Number_recent_dl_on_portal'],
	'L_DL_RECENT_EXPLAIN' => 'Number_recent_dl_on_portal',
	'L_DL_STATS_PERM' => $lang['Dl_stat_perm'],
	'L_DL_STATS_PERM_EXPLAIN' => 'Dl_stat_perm',
	'L_DL_STOP_UPLOADS' => $lang['Dl_stop_uploads'],
	'L_DL_STOP_UPLOADS_EXPLAIN' => 'Dl_stop_uploads',
	'L_DL_THUMBSNAIL_DIM' => $lang['Dl_thumb_max_dim'],
	'L_DL_THUMBSNAIL_DIM_EXPLAIN' => 'Dl_thumb_max_dim',
	'L_DL_THUMBSNAIL_SIZE' => $lang['Dl_thumb_max_size'],
	'L_DL_THUMBSNAIL_SIZE_EXPLAIN' => 'Dl_thumb_max_size',
	'L_DL_UPLOAD_TRAFFIC_COUNT' => $lang['Dl_upload_traffic_count'],
	'L_DL_UPLOAD_TRAFFIC_COUNT_EXPLAIN' => 'Dl_upload_traffic_count',
	'L_DL_USE_EXT_BLACKLIST' => $lang['Dl_use_ext_blacklist'],
	'L_DL_USE_EXT_BLACKLIST_EXPLAIN' => 'Dl_use_ext_blacklist',
	'L_DL_USE_HACKLIST' => $lang['Dl_use_hacklist'],
	'L_DL_USE_HACKLIST_EXPLAIN' => 'Dl_use_hacklist',
	'L_ENABLE_POST_TRAFFIC' => $lang['Dl_enable_post_traffic'],
	'L_ENABLE_POST_TRAFFIC_EXPLAIN' => 'Dl_enable_post_traffic',
	'L_GB' => $lang['Dl_GB'],
	'L_KB' => $lang['Dl_KB'],
	'L_LIMIT_DESC_ON_INDEX' => $lang['Dl_limit_desc_on_index'],
	'L_LIMIT_DESC_ON_INDEX_EXPLAIN' => 'Dl_limit_desc_on_index',
	'L_MB' => $lang['Dl_MB'],
	'L_NEW' => $lang['Dl_method_new'],
	'L_NEWTOPIC_TRAFFIC' => $lang['Dl_newtopic_traffic'],
	'L_NEWTOPIC_TRAFFIC_EXPLAIN' => 'Dl_newtopic_traffic',
	'L_NO' => $lang['No'],
	'L_OLD' => $lang['Dl_method_old'],
	'L_OVERALL_TRAFFIC' => $lang['Dl_overall_traffic'],
	'L_OVERALL_TRAFFIC_EXPLAIN' => 'Dl_overall_traffic',
	'L_PREVENT_HOTLINK' => $lang['Dl_prevent_hotlink'],
	'L_PREVENT_HOTLINK_EXPLAIN' => 'Dl_prevent_hotlink',
	'L_REPLY_TRAFFIC' => $lang['Dl_reply_traffic'],
	'L_REPLY_TRAFFIC_EXPLAIN' => 'Dl_reply_traffic',
	'L_RESET' => $lang['Reset'],
	'L_SHOW_FOOTER_LEGEND' => $lang['Dl_show_footer_legend'],
	'L_SHOW_FOOTER_LEGEND_EXPLAIN' => 'Dl_show_footer_legend',
	'L_SHOW_FOOTER_STATS' => $lang['Dl_show_footer_stat'],
	'L_SHOW_FOOTER_STATS_EXPLAIN' => 'Dl_show_footer_stat',
	'L_SHOW_REAL_FILETIME' => $lang['Dl_show_real_filetime'],
	'L_SHOW_REAL_FILETIME_EXPLAIN' => 'Dl_show_real_filetime',
	'L_SORT_PREFORM' => $lang['Dl_sort_preform'],
	'L_SORT_PREFORM_EXPLAIN' => 'Dl_sort_preform',
	'L_SORT_FIX' => $lang['Dl_sort_acp'],
	'L_SORT_USER' => $lang['Dl_sort_user'],
	'L_SUBMIT' => $lang['Submit'],
	'L_USER_TRAFFIC_ONCE' => $lang['Dl_user_traffic_once'],
	'L_USER_TRAFFIC_ONCE_EXPLAIN' => 'Dl_user_traffic_once',
	'L_YES' => $lang['Yes'],
	'L_REPORT_BROKEN' => $lang['Dl_report_broken'],
	'L_REPORT_BROKEN_EXPLAIN' => 'Dl_report_broken',
	'L_DL_REPORT_BROKEN_MESSAGE' => $lang['Dl_report_broken_message'],
	'L_DL_REPORT_BROKEN_MESSAGE_EXPLAIN' => 'Dl_report_broken_message',
	'L_DL_REPORT_BROKEN_LOCK' => $lang['Dl_report_broken_lock'],
	'L_DL_REPORT_BROKEN_LOCK_EXPLAIN' => 'Dl_report_broken_lock',
	'L_DL_REPORT_BROKEN_VC' => $lang['Dl_report_broken_vc'],
	'L_DL_REPORT_BROKEN_VC_EXPLAIN' => 'Dl_report_broken_vc',
	'L_DOWNLOAD_VC' => $lang['Dl_visual_confirmation'],
	'L_DOWNLOAD_VC_EXPLAIN' => 'Dl_visual_confirmation',
	'L_NO_GUESTS' => $lang['Dl_off_guests'],
	'L_EDIT_OWN_DOWNLOADS' => $lang['Dl_edit_own_downloads'],
	'L_EDIT_OWN_DOWNLOADS_EXPLAIN' => 'Dl_edit_own_downloads',
	'L_SHORTEN_EXTERN_LINKS' => $lang['Dl_shorten_extern_links'],
	'L_SHORTEN_EXTERN_LINKS_EXPLAIN' => 'Dl_shorten_extern_links',
	'L_ICON_FREE_FOR_REG' => $lang['Dl_icon_free_for_reg'],
	'L_ICON_FREE_FOR_REG_EXPLAIN' => 'Dl_icon_free_for_reg',
	'L_LATEST_COMMENTS' => $lang['Dl_latest_comments'],
	'L_LATEST_COMMENTS_EXPLAIN' => 'Dl_latest_comments',
	'L_DL_DIRECT' => $lang['Dl_direct_download'],
	'L_USER_DOWNLOAD_LIMIT_FLAG' => $lang['Dl_user_download_limit_flag'],
	'L_USER_DOWNLOAD_LIMIT_FLAG_EXPLAIN' => 'Dl_user_download_limit_flag',
	'L_USER_DOWNLOAD_LIMIT' => $lang['Dl_user_download_limit'],
	'L_USER_DOWNLOAD_LIMIT_EXPLAIN' => 'Dl_user_download_limit',

	'DL_LINKS_PER_PAGE' => $new['dl_links_per_page'],
	'DL_POSTS' => $new['dl_posts'],
	'DELAY_AUTO_TRAFFIC' => $new['delay_auto_traffic'],
	'DELAY_POST_TRAFFIC' => $new['delay_post_traffic'],
	'DL_EDIT_TIME' => $new['dl_edit_time'],
	'DL_METHOD_QUOTA' => $dl_method_quota_out,
	'DL_NEW_TIME' => $new['dl_new_time'],
	'DL_PATH' => $new['download_dir'],
	'DL_RECENT' => $new['recent_downloads'],
	'DL_DIRECT' => $dl_direct,
	'LATEST_COMMENTS' => $new['latest_comments'],
	'LIMIT_DESC_ON_INDEX' => $limit_desc_on_index,
	'M_QUOTA1' => $m_quota1,
	'M_QUOTA2' => $m_quota2,
	'M_QUOTA3' => $m_quota3,
	'M_QUOTA4' => $m_quota4,
	'METHOD_NEW' => $dl_method_new,
	'METHOD_OLD' => $dl_method_old,
	'NEWTOPIC_TRAFFIC' => $newtopic_traffic_out,
	'OVERALL_TRAFFIC' => $overall_traffic_out,
	'PHYSICAL_QUOTA' => $physical_quota_out,
	'SHORTEN_EXTERN_LINKS' => $new['shorten_extern_links'],
	'THUMB_F_RANGE_1' => $f_quota1,
	'THUMB_F_RANGE_2' => $f_quota2,
	'THUMB_FSIZE' => $thumbs_fsize_out,
	'THUMB_XSIZE' => $thumbs_xsize,
	'THUMB_YSIZE' => $thumbs_ysize,
	'USER_DOWNLOAD_LIMIT' => $new['user_download_limit'],

	'X_NEW1' => $x_new1,
	'X_NEW2' => $x_new2,
	'X_NEW3' => $x_new3,
	'X_OVER1' => $x_over1,
	'X_OVER2' => $x_over2,
	'X_OVER3' => $x_over3,
	'X_OVER4' => $x_over4,
	'X_QUOTA1' => $x_quota1,
	'X_QUOTA2' => $x_quota2,
	'X_QUOTA3' => $x_quota3,
	'X_QUOTA4' => $x_quota4,
	'X_REPLY1' => $x_reply1,
	'X_REPLY2' => $x_reply2,
	'X_REPLY3' => $x_reply3,

	'DISABLE_EMAIL_YES' => $disable_email_yes,
	'DISABLE_EMAIL_NO' => $disable_email_no,

	'DISABLE_POPUP_YES' => $disable_popup_yes,
	'DISABLE_POPUP_NO' => $disable_popup_no,

	'DISABLE_POPUP_NOTIFY_YES' => $disable_popup_notify_yes,
	'DISABLE_POPUP_NOTIFY_NO' => $disable_popup_notify_no,

	'DOWNLOAD_VC_YES' => $download_vc_yes,
	'DOWNLOAD_VC_NO' => $download_vc_no,

	'EDIT_OWN_DOWNLOADS_YES' => $edit_own_downloads_yes,
	'EDIT_OWN_DOWNLOADS_NO' => $edit_own_downloads_no,

	'ENABLE_POST_TRAFFIC_YES' => $enable_post_dl_traffic_yes,
	'ENABLE_POST_TRAFFIC_NO' => $enable_post_dl_traffic_no,

	'GUEST_REPORT_BROKEN_YES' => $guest_report_broken_yes,
	'GUEST_REPORT_BROKEN_NO' => $guest_report_broken_no,

	'GUEST_STATS_SHOW_YES' => $guest_stats_show_yes,
	'GUEST_STATS_SHOW_NO' => $guest_stats_show_no,

	'HOTLINK_ACTION_ONE' => $hotlink_action_one,
	'HOTLINK_ACTION_TWO' => $hotlink_action_two,

	'ICON_FREE_FOR_REG_YES' => $icon_free_for_reg_yes,
	'ICON_FREE_FOR_REG_NO' => $icon_free_for_reg_no,

	'REMAINING_OVERALL_TRAFFIC' => $remain_text_out,
	'REPLY_TRAFFIC' => $reply_traffic_out,

	'PREVENT_HOTLINK_YES' => $prevent_hotlink_yes,
	'PREVENT_HOTLINK_NO' => $prevent_hotlink_no,

	'REPORT_BROCKEN_ON' => $report_broken_on,
	'REPORT_BROCKEN_OFF' => $report_broken_off,
	'REPORT_BROCKEN_GUESTS' => $report_broken_guests,

	'REPORT_BROCKEN_LOCK_YES' => $report_broken_lock_yes,
	'REPORT_BROCKEN_LOCK_NO' => $report_broken_lock_no,

	'REPORT_BROCKEN_MESSAGE_YES' => $report_broken_message_yes,
	'REPORT_BROCKEN_MESSAGE_NO' => $report_broken_message_no,

	'REPORT_BROCKEN_VC_YES' => $report_broken_vc_yes,
	'REPORT_BROCKEN_VC_NO' => $report_broken_vc_no,

	'SHOW_FOOTER_LEGEND_NO' => $show_footer_legend_no,
	'SHOW_FOOTER_LEGEND_YES' => $show_footer_legend_yes,

	'SHOW_FOOTER_STATS_NO' => $show_footer_stat_no,
	'SHOW_FOOTER_STATS_YES' => $show_footer_stat_yes,

	'SHOW_REAL_FILETIME_NO' => $show_real_filetime_no,
	'SHOW_REAL_FILETIME_YES' => $show_real_filetime_yes,

	'SORT_PREFORM_FIX' => $sort_preform_fix,
	'SORT_PREFORM_USER' => $sort_preform_user,

	'STOP_UPLOADS_NO' => $stop_uploads_no,
	'STOP_UPLOADS_YES' => $stop_uploads_yes,

	'UPLOAD_TRAFFIC_COUNT_YES' => $upload_traffic_count_yes,
	'UPLOAD_TRAFFIC_COUNT_NO' => $upload_traffic_count_no,

	'USE_EXT_BLACKLIST_YES' => $use_ext_blacklist_yes,
	'USE_EXT_BLACKLIST_NO' => $use_ext_blacklist_no,

	'USE_HACKLIST_YES' => $use_hacklist_yes,
	'USE_HACKLIST_NO' => $use_hacklist_no,
	'USE_HACKLIST_NO' => $use_hacklist_no,
	'USE_HACKLIST_NO' => $use_hacklist_no,

	'USER_TRAFFIC_ONCE_NO' => $user_traffic_once_no,
	'USER_TRAFFIC_ONCE_YES' => $user_traffic_once_yes,

	'USER_DOWNLOAD_LIMIT_FLAG_YES' => $user_download_limit_flag_yes,
	'USER_DOWNLOAD_LIMIT_FLAG_NO' => $user_download_limit_flag_no,

	'S_STATS_PERM_SELECT' => $s_stats_perm_select,
	'S_CONFIG_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=config')
	)
);

/*
if (@file_exists(IP_ROOT_PATH . 'portal.' . PHP_EXT))
{
	$template->assign_block_vars('portal_block', array());
}
*/

$template->pparse('config');

?>