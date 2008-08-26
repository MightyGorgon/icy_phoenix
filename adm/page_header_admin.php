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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

define('HEADER_INC', true);

$sql = "SELECT * FROM " . CONFIG_TABLE . " WHERE config_name = 'allow_only_main_admin_id'";
if(!($result = $db->sql_query($sql)))
{
	message_die(CRITICAL_ERROR, 'Could not query config information', '', __LINE__, __FILE__, $sql);
}
if ($row = $db->sql_fetchrow($result))
{
	$allow_only_main_admin_id = $row['config_value'];
}
$db->sql_freeresult($result);

$sql = "SELECT * FROM " . CONFIG_TABLE . " WHERE config_name = 'main_admin_id'";
if(!($result = $db->sql_query($sql)))
{
	message_die(CRITICAL_ERROR, 'Could not query config information', '', __LINE__, __FILE__, $sql);
}
if ($row = $db->sql_fetchrow($result))
{
	$main_admin_id = $row['config_value'];
}
$db->sql_freeresult($result);

if ($allow_only_main_admin_id == true)
{
	$main_admin_id = (intval($main_admin_id) >= 2) ? $main_admin_id : '2';
	$sql = "SELECT user_id
		FROM " . USERS_TABLE . "
		WHERE user_id = '" . $main_admin_id . "'
		LIMIT 1";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t obtain user id', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$main_admin_id = $row['user_id'];
		$db->sql_freeresult($result);
		if ($userdata['user_id'] != $main_admin_id)
		{
			die($lang['Not_Auth_View']);
			//message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}

// gzip_compression
$do_gzip_compress = false;
if ($board_config['gzip_compress'])
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
if ($board_config['ajax_features'] == true)
{
	$template->assign_block_vars('switch_ajax_features', array());
	$ajax_blur = '';
	$ajax_user_check = 'onkeyup="AJAXUsernameSearch(this.value, 0);"';
}
else
{
	$ajax_blur = '';
	$ajax_user_check = '';
}
// Mighty Gorgon - AJAX Features - End

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

if(is_array($css_style_include))
{
	for ($i = 0; $i < count($css_style_include); $i++)
	{
		$template->assign_block_vars('css_style_include', array(
			'CSS_FILE' => $css_style_include[$i],
			)
		);
	}
}

if(is_array($css_include))
{
	for ($i = 0; $i < count($css_include); $i++)
	{
		$template->assign_block_vars('css_include', array(
			'CSS_FILE' => $css_include[$i],
			)
		);
	}
}

if(is_array($js_include))
{
	for ($i = 0; $i < count($js_include); $i++)
	{
		$template->assign_block_vars('js_include', array(
			'JS_FILE' => $js_include[$i],
			)
		);
	}
}


//
// The following assigns all _common_ variables that may be used at any point
// in a template. Note that all URL's should be wrapped in append_sid, as
// should all S_x_ACTIONS for forms.
//
$template->assign_vars(array(
	'PHPBB_ROOT_PATH' => $phpbb_root_path,
	'PHPEX' => $phpEx,
	'S_SID' => $userdata['session_id'],
	'POST_FORUM_URL' => POST_FORUM_URL,
	'POST_TOPIC_URL' => POST_TOPIC_URL,
	'POST_POST_URL' => POST_POST_URL,
	'LOGIN_MG' => LOGIN_MG,
	'PORTAL_MG' => PORTAL_MG,
	'FORUM_MG' => FORUM_MG,
	'VIEWFORUM_MG' => VIEWFORUM_MG,
	'VIEWTOPIC_MG' => VIEWTOPIC_MG,
	'PROFILE_MG' => PROFILE_MG,
	'POSTING_MG' => POSTING_MG,
	'SEARCH_MG' => SEARCH_MG,
	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],

	'SITENAME' => $board_config['sitename'],
	'PAGE_TITLE' => $page_title,

	// AJAX Features - BEGIN
	'S_AJAX_BLUR' => $ajax_blur,
	'S_AJAX_USER_CHECK' => $ajax_user_check,
	// AJAX Features - END

	'L_ADMIN' => $lang['Admin'],
	'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
	'L_FAQ' => $lang['FAQ'],

	'U_INDEX' => append_sid('../' . FORUM_MG),

	'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
	'S_LOGIN_ACTION' => append_sid('../' . LOGIN_MG),
	'S_JUMPBOX_ACTION' => append_sid('../' . VIEWFORUM_MG),
	'S_CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),

	//'SPACER' => '../' . $images['spacer'],

	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_VLINK' => '#'.$theme['body_vlink'],
	'T_BODY_ALINK' => '#'.$theme['body_alink'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],
	'T_TH_COLOR2' => '#'.$theme['th_color2'],
	'T_TH_COLOR3' => '#'.$theme['th_color3'],
	'T_TH_CLASS1' => $theme['th_class1'],
	'T_TH_CLASS2' => $theme['th_class2'],
	'T_TH_CLASS3' => $theme['th_class3'],
	'T_TD_COLOR1' => '#'.$theme['td_color1'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_TD_COLOR3' => '#'.$theme['td_color3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	'T_FONTFACE1' => $theme['fontface1'],
	'T_FONTFACE2' => $theme['fontface2'],
	'T_FONTFACE3' => $theme['fontface3'],
	'T_FONTSIZE1' => $theme['fontsize1'],
	'T_FONTSIZE2' => $theme['fontsize2'],
	'T_FONTSIZE3' => $theme['fontsize3'],
	'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
	'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_SPAN_CLASS1' => $theme['span_class1'],
	'T_SPAN_CLASS2' => $theme['span_class2'],
	'T_SPAN_CLASS3' => $theme['span_class3']
	)
);

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
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