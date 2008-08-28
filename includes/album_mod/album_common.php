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
* Smartor (smartor_xp@hotmail.com)
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

define('IN_ALBUM', true);

if (defined('IS_ICYPHOENIX'))
{
	if (!defined('IMG_THUMB'))
	{
		$cms_page_id = '12';
		$cms_page_name = 'album';
		$auth_level_req = $board_config['auth_view_album'];
		if ($auth_level_req > AUTH_ALL)
		{
			if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
			if ($userdata['user_level'] != ADMIN)
			{
				if ($auth_level_req == AUTH_ADMIN)
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}
				if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}
			}
		}
		$cms_global_blocks = ($board_config['wide_blocks_album'] == 1) ? true : false;
	}
}

// Include Language
$language = $board_config['default_lang'];

if (!file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_album_main.' . $phpEx))
{
	$language = 'english';
}

include($phpbb_root_path . 'language/lang_' . $language . '/lang_album_main.' . $phpEx);

// Get Album Config
$album_config = array();
$sql = "SELECT * FROM ". ALBUM_CONFIG_TABLE;
if(!$result = $db->sql_query($sql, false, 'album_config_'))
{
	message_die(GENERAL_ERROR, 'Could not query Album config information', '', __LINE__, __FILE__, $sql);
}
while($row = $db->sql_fetchrow($result))
{
	$album_config[$row['config_name']] = $row['config_value'];
}
$db->sql_freeresult($result);

if($album_config['album_debug_mode'] == 1)
{
	$GLOBALS['album_debug_enabled'] = true;
}
else
{
	$GLOBALS['album_debug_enabled'] = false;
}

if ($album_config['show_img_no_gd'] == 1)
{
	//$thumb_size = 'width="' . $album_config['thumbnail_size'] . '" height="' . $album_config['thumbnail_size'] . '"';
	$thumb_size = 'width="' . $album_config['thumbnail_size'] . '"';
}
else
{
	$thumb_size = '';
}

if ((intval($album_config['set_memory']) > '0') && (intval($album_config['set_memory']) < '17'))
{
	@ini_set('memory_limit', intval($album_config['set_memory']) . 'M');
}

if ($album_config['show_inline_copyright'] == 0)
{
	/*
	$album_copyright = '<div align="center" class="gensmall" style="font-family: Verdana, Arial, Helvetica, sans-serif; letter-spacing: -1px"><b>Photo Album Powered by</b><br />';
	$album_copyright .= 'Photo Album 2' . $album_config['album_version'] . '&nbsp;&copy;&nbsp;2002-2003&nbsp;<a href="http://smartor.is-root.com" target="_blank">Smartor</a><br />';
	$album_copyright .= 'Volodymyr (CLowN) Skoryk\'s SP1 Addon 1.5.1<br />';
	$album_copyright .= 'IdleVoid\'s Album Category Hierarchy 1.3.0<br />';
	$album_copyright .= '<a href="http://www.mightygorgon.com" target="_blank">Mighty Gorgon</a> Full Album Pack ' . $album_config['fap_version'];
	$album_copyright .= '</div>';
	*/
	$album_copyright = '<div align="center" class="gensmall" style="font-family: Verdana, Arial, Helvetica, sans-serif; letter-spacing: -1px">';
	$album_copyright .= 'Photo Album Powered by:&nbsp;<a href="http://www.icyphoenix.com" target="_blank">Mighty Gorgon</a> Full Album Pack ' . $album_config['fap_version'] . '&nbsp;&copy;&nbsp;2007<br />';
	$album_copyright .= '[based on <a href="http://smartor.is-root.com" target="_blank">Smartor</a> Photo Album plus IdleVoid\'s Album CH &amp; CLowN SP1]';
	$album_copyright .= '</div>';
}
else
{
	/*
	$album_copyright = '<div align="center" class="gensmall" style="font-family: Verdana, Arial, Helvetica, sans-serif; letter-spacing: -1px"><b>Photo Album Powered by:</b>&nbsp;';
	$album_copyright .= 'Photo Album 2' . $album_config['album_version'] . '&nbsp;<a href="http://smartor.is-root.com" target="_blank">Smartor</a>&nbsp;-&nbsp;';
	$album_copyright .= 'CLowN SP1 Addon 1.5.1&nbsp;-&nbsp;';
	$album_copyright .= 'IdleVoid\'s Album CH 1.3.0&nbsp;-&nbsp;';
	$album_copyright .= '<a href="http://www.mightygorgon.com" target="_blank">Mighty Gorgon</a> Full Album Pack ' . $album_config['fap_version'];
	$album_copyright .= '</div>';
	*/
	$album_copyright = '<div align="center" class="gensmall" style="font-family: Verdana, Arial, Helvetica, sans-serif; letter-spacing: -1px">';
	$album_copyright .= 'Photo Album Powered by:&nbsp;<a href="http://www.icyphoenix.com" target="_blank">Mighty Gorgon</a> Full Album Pack ' . $album_config['fap_version'] . '&nbsp;&copy;&nbsp;2007';
	$album_copyright .= '&nbsp;[based on <a href="http://smartor.is-root.com" target="_blank">Smartor</a> Photo Album plus IdleVoid\'s Album CH &amp; CLowN SP1]';
	$album_copyright .= '</div>';
}

if ($album_config['lb_preview'] == 0)
{
	$preview_lb_div = '';
}
else
{
	$preview_lb_div = '<script type="text/javascript" src="' . ALBUM_MOD_PATH . 'fap_loader.js"></script>';
	$preview_lb_div .= '<div id="preview_div" style="display: none; position: absolute; z-index: 110; left: -600px; top: -600px;">';
	$preview_lb_div .= '	<div class="border_preview" style="width: ' . $album_config['midthumb_width'] . 'px; height: ' . $album_config['midthumb_height'] . 'px;">';
	$preview_lb_div .= '		<div id="loader_container" style="display: none; visibility: hidden;">';
	$preview_lb_div .= '			<div id="loader">';
	$preview_lb_div .= '				<div align="center">Loading preview...</div>';
	$preview_lb_div .= '				<div id="loader_bg">';
	$preview_lb_div .= '					<div id="progress" style="left: 96px; width: 16px;"></div>';
	$preview_lb_div .= '				</div>';
	$preview_lb_div .= '			</div>';
	$preview_lb_div .= '		</div>';
	$preview_lb_div .= '		Preview';
	$preview_lb_div .= '		<div class="preview_temp_load">';
	$preview_lb_div .= '			<img onload="javascript:remove_loading();" src="" alt="" />';
	$preview_lb_div .= '		</div>';
	$preview_lb_div .= '	</div>';
	$preview_lb_div .= '</div>';
	$preview_lb_div .= '<br /><br />';
}

include_once($album_root_path . 'album_functions.' . $phpEx);
include_once($album_root_path . 'album_hierarchy_functions.' . $phpEx);

$album_search_box = '<form name="search" action="' . append_sid(album_append_uid('album_search.' . $phpEx)) . '">';
$album_search_box .= '	<span class="gensmall">' . $lang['Search'] . ':&nbsp;</span>';
$album_search_box .= '	<select name="mode">';
$album_search_box .= '		<option value="user">' . $lang['Username'] . '</option>';
$album_search_box .= '		<option value="name">' . $lang['Pic_Name'] . '</option>';
$album_search_box .= '		<option value="desc">' . $lang['Description'] . '</option>';
$album_search_box .= '		<option value="name_desc">' . $lang['Title_Description'] . '</option>';
$album_search_box .= '	</select>';
$album_search_box .= '	' . $lang['Search_Contents'];
$album_search_box .= '	<input class="post" type="text" name="search" maxlength="30" />&nbsp;&nbsp;';
$album_search_box .= '	<input class="liteoption" type="submit" value="' . $lang['Go'] . '" />';
$album_search_box .= '</form>';

$template->assign_vars(array(
	'IMG_ALBUM_FOLDER' => $images['pm_outbox'],
	'IMG_ALBUM_SUBFOLDER' => $images['pm_inbox'],
	'IMG_ALBUM_FOLDER_SMALL' => defined('IS_ICYPHOENIX') ? $images['topic_nor_read'] : $images['folder'],
	'IMG_ALBUM_FOLDER_SMALL_NEW' => defined('IS_ICYPHOENIX') ? $images['topic_nor_unread'] : $images['folder_new'],
	'IMG_ALBUM_SUBFOLDER_SMALL' => $images['icon_minipost'],
	'IMG_ALBUM_SUBFOLDER_SMALL_NEW' => $images['icon_minipost_new'],
	'IMG_ALBUM_FOLDER_NEW' => $images['pm_savebox'],
	'IMG_ALBUM_FOLDER_SS' => $images['pm_sentbox'],
	'IMG_SLIDESHOW' => $images['icon_latest_reply'],
	'IMG_SLIDESHOW_NEW' => $images['icon_newest_reply'],

	'ALBUM_SEARCH_BOX' => $album_search_box,

	'THUMB_SIZE' => $thumb_size,
	'MIDTHUMB_W' => $album_config['midthumb_width'],
	'MIDTHUMB_H' => $album_config['midthumb_height'],
	'PREVIEW_LB_DIV' => $preview_lb_div,

	'U_ALBUM_SEARCH' => append_sid(album_append_uid('album_search.' . $phpEx)),
	'U_ALBUM_UPLOAD' => append_sid(album_append_uid('album_upload.' . $phpEx)),

	'ALBUM_VERSION' => '2' . $album_config['album_version'],
	'ALBUM_COPYRIGHT' => $preview_lb_div . $album_copyright
	)
);

if (!defined('IS_ICYPHOENIX'))
{
	$template->assign_vars(array(
		'NAV_SEP' => $lang['Nav_Separator'],
		'NAV_DOT' => '&#8226;',
		'SPACER' => $images['spacer'],
		)
	);
}

?>