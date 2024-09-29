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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

define('HEADER_INC', true);

if (defined('ONLY_FOUNDER_ACP') && (ONLY_FOUNDER_ACP == true))
{
	// Note that the get_founder_id here has the clear cache parameter set to true...
	// This is important as we are in ACP, and we want to make sure we have cache cleaned...
	$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id(true));
	if ($user->data['user_id'] != $founder_id)
	{
		die($lang['Not_Auth_View']);
		//message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

// gzip_compression
$do_gzip_compress = false;
if ($config['gzip_compress_runtime'])
{
	$phpver = phpversion();

	$useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ($phpver >= '4.0.4pl1' && (strstr($useragent, 'compatible') || strstr($useragent, 'Gecko')))
	{
		if (extension_loaded('zlib'))
		{
			ob_start('ob_gzhandler');
		}
	}
	elseif ($phpver > '4.0')
	{
		if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		{
			if (extension_loaded('zlib'))
			{
				$do_gzip_compress = true;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}

$template->set_filenames(array('header' => ADM_TPL . 'page_header.tpl'));

// Mighty Gorgon - AJAX Features - Begin
$ajax_blur = '';
$ajax_user_check = !empty($config['ajax_features']) ? 'onkeyup="AJAXUsernameSearch(this.value, 0);"' : '';
// Mighty Gorgon - AJAX Features - End

// Not needed anymore...
/*
$js_temp = array('js/prototype.js', 'scriptaculous/scriptaculous.js', 'scriptaculous/unittest.js');
$template->js_include = array_merge($template->js_include, $js_temp);
unset($js_temp);
*/

//
// The following assigns all _common_ variables that may be used at any point
// in a template. Note that all URL's should be wrapped in append_sid, as
// should all S_x_ACTIONS for forms.
//
$template->assign_vars(array(
	'IP_ROOT_PATH' => IP_ROOT_PATH,
	'PHP_EXT' => PHP_EXT,
	'POST_FORUM_URL' => POST_FORUM_URL,
	'POST_TOPIC_URL' => POST_TOPIC_URL,
	'POST_POST_URL' => POST_POST_URL,
	'CMS_PAGE_LOGIN' => CMS_PAGE_LOGIN,
	'CMS_PAGE_HOME' => CMS_PAGE_HOME,
	'CMS_PAGE_FORUM' => CMS_PAGE_FORUM,
	'CMS_PAGE_VIEWFORUM' => CMS_PAGE_VIEWFORUM,
	'CMS_PAGE_VIEWTOPIC' => CMS_PAGE_VIEWTOPIC,
	'CMS_PAGE_PROFILE' => CMS_PAGE_PROFILE,
	'CMS_PAGE_POSTING' => CMS_PAGE_POSTING,
	'CMS_PAGE_SEARCH' => CMS_PAGE_SEARCH,
	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],

	'SITENAME' => $config['sitename'],
	'PAGE_TITLE' => ( isset($meta_content['page_title']) ? $meta_content['page_title'] : '' ),

	'S_PRINT_SIZE' => (!empty($config['display_print_size']) ? true : false),
	'S_JQUERY_UI' => (!empty($config['jquery_ui']) ? true : false),
	'S_JQUERY_UI_TP' => (!empty($config['jquery_ui_tp']) ? true : false),
	'S_JQUERY_UI_BA' => (!empty($config['jquery_ui_ba']) ? true : false),
	'S_JQUERY_UI_STYLE' => (!empty($config['jquery_ui_style']) ? $config['jquery_ui_style'] : 'cupertino'),
	'S_HIGHSLIDE' => (!empty($config['thumbnail_highslide']) ? true : false),

	// AJAX Features - BEGIN
	'S_AJAX_FEATURES' => (!empty($config['ajax_features']) ? true : false),
	'S_AJAX_BLUR' => $ajax_blur,
	'S_AJAX_USER_CHECK' => $ajax_user_check,
	// AJAX Features - END

	'L_ADMIN' => $lang['Admin'],
	'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
	'L_FAQ' => $lang['FAQ'],

	'U_INDEX' => append_sid(IP_ROOT_PATH . CMS_PAGE_FORUM),

	'ACP_IMAGES_PATH' => IP_ROOT_PATH . 'images/acp/',
	'U_ACP_FORUMS' => append_sid('admin_forums_extend.' . PHP_EXT),
	'U_ACP_USERS' => append_sid('admin_userlist.' . PHP_EXT),
	'U_ACP_GROUPS' => append_sid('admin_groups.' . PHP_EXT),
	'U_ACP_EMAIL' => append_sid('admin_megamail.' . PHP_EXT),
	'U_ACP_DOWNLOADS' => append_sid('admin_pa_category.' . PHP_EXT),
	'U_ACP_SETTINGS' => append_sid('admin_config_settings.' . PHP_EXT),
	'U_ACP_CACHE' => append_sid('admin_board_clearcache.' . PHP_EXT),

	'L_ACP_FORUMS' => $lang['1200_Forums'] . ' ' . $lang['100_Manage'],
	'L_ACP_USERS' => $lang['110_Manage'] . ' ' . $lang['1610_Users'],
	'L_ACP_GROUPS' => $lang['110_Manage_Groups'],
	'L_ACP_EMAIL' => $lang['130_Mass_Email'],
	'L_ACP_DOWNLOADS' => $lang['2000_Downloads'],
	'L_ACP_SETTINGS' => $lang['100_Main_Settings'],
	'L_ACP_CACHE' => $lang['127_Clear_Cache'],

	'S_TIMEZONE' => sprintf($lang['All_times'], $lang['tz'][str_replace('.0', '', sprintf('%.1f', number_format($config['board_timezone'], 1)))]),
	'S_LOGIN_ACTION' => append_sid('../' . CMS_PAGE_LOGIN),
	'S_JUMPBOX_ACTION' => append_sid('../' . CMS_PAGE_VIEWFORUM),
	'S_CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

	//'SPACER' => '../' . $images['spacer'],

	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	)
);

// Work around for "current" Apache 2 + PHP module which seems to not cope with private cache control setting
if (!defined('AJAX_HEADERS'))
{
	if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header ('Cache-Control: no-cache, pre-check=0, post-check=0');
	}
	else
	{
		header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header ('Expires: 0');
	header ('Pragma: no-cache');
}

$template->pparse('header');

?>