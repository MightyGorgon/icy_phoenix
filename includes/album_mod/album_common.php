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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

define('IN_ALBUM', true);
define('ALBUM_NAV_ARROW', $lang['Nav_Separator']);

if (!defined('IMG_THUMB'))
{
	$cms_page['page_id'] = 'album';
	$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
	$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
	$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
	check_page_auth($cms_page['page_id'], $cms_auth_level);
}

// Include Language
$language = $config['default_lang'];

if (!file_exists(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_album_main.' . PHP_EXT))
{
	$language = 'english';
}

include(IP_ROOT_PATH . 'language/lang_' . $language . '/lang_album_main.' . PHP_EXT);

// Get Album Config
$album_config = array();
$sql = "SELECT * FROM " . ALBUM_CONFIG_TABLE;
$result = $db->sql_query($sql, 0, 'album_config_');
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

if ((intval($album_config['set_memory']) > '0') && (intval($album_config['set_memory']) < '33'))
{
	@ini_set('memory_limit', intval($album_config['set_memory']) . 'M');
}

if ($album_config['show_inline_copyright'] == 0)
{
	$album_copyright = '<div class="gensmall" style="text-align: center; font-family: Verdana, Arial, Helvetica, sans-serif; letter-spacing: -1px">';
	$album_copyright .= 'Photo Album Powered by:&nbsp;<a href="http://www.icyphoenix.com" target="_blank">Mighty Gorgon</a> Full Album Pack ' . $album_config['fap_version'] . '&nbsp;&copy;&nbsp;2009<br />';
	$album_copyright .= '[based on <a href="http://smartor.is-root.com" target="_blank">Smartor</a> Photo Album plus IdleVoid\'s Album CH &amp; CLowN SP1]';
	$album_copyright .= '</div>';
}
else
{
	$album_copyright = '<div class="gensmall" style="text-align: center; font-family: Verdana, Arial, Helvetica, sans-serif; letter-spacing: -1px">';
	$album_copyright .= 'Photo Album Powered by:&nbsp;<a href="http://www.icyphoenix.com" target="_blank">Mighty Gorgon</a> Full Album Pack ' . $album_config['fap_version'] . '&nbsp;&copy;&nbsp;2009';
	$album_copyright .= '&nbsp;[based on <a href="http://smartor.is-root.com" target="_blank">Smartor</a> Photo Album plus IdleVoid\'s Album CH &amp; CLowN SP1]';
	$album_copyright .= '</div>';
}


include_once(ALBUM_MOD_PATH . 'album_functions.' . PHP_EXT);
include_once(ALBUM_MOD_PATH . 'album_hierarchy_functions.' . PHP_EXT);

$album_search_box = '<form name="search" action="' . append_sid(album_append_uid('album_search.' . PHP_EXT)) . '">';
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
	'IMG_ALBUM_FOLDER_SMALL' => $images['topic_nor_read'],
	'IMG_ALBUM_FOLDER_SMALL_NEW' => $images['topic_nor_unread'],
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

	'U_ALBUM_SEARCH' => append_sid(album_append_uid('album_search.' . PHP_EXT)),
	'U_ALBUM_UPLOAD' => append_sid(album_append_uid('album_upload.' . PHP_EXT)),

	'ALBUM_VERSION' => '2' . $album_config['album_version'],
	'ALBUM_COPYRIGHT' => $album_copyright
	)
);

?>