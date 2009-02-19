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
define('MG_KILL_CTRACK', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

$access_allowed = get_cms_access_auth('cms_auth');

if (!$access_allowed)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

if (!$userdata['session_admin'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=cms_auth.' . PHP_EXT . '&admin=1', true));
}

// Pull all config data
$sql = "SELECT * FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
	while($row = $db->sql_fetchrow($result))
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;

		$new[$config_name] = (isset($_POST[$config_name])) ? $_POST[$config_name] : $default_config[$config_name];

		if(isset($_POST['submit']) && isset($_POST[$config_name]))
		{
			set_config($config_name, $new[$config_name]);
		}
	}

	if(isset($_POST['submit']))
	{
		$message = $lang['CMS_Config_updated'] . '<br /><br />' . sprintf($lang['CMS_Click_return_config'], '<a href="' . append_sid('cms_auth.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['CMS_Click_return_cms'], '<a href="' . append_sid('cms.' . PHP_EXT) . '">', '</a>') . '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}
}

$auth_view['portal'] = auth_select($new['auth_view_portal'], 'auth_view_portal');
$auth_view['forum'] = auth_select($new['auth_view_forum'], 'auth_view_forum');
$auth_view['viewf'] = auth_select($new['auth_view_viewf'], 'auth_view_viewf');
$auth_view['viewt'] = auth_select($new['auth_view_viewt'], 'auth_view_viewt');
$auth_view['faq'] = auth_select($new['auth_view_faq'], 'auth_view_faq');
$auth_view['memberlist'] = auth_select($new['auth_view_memberlist'], 'auth_view_memberlist');
$auth_view['group_cp'] = auth_select($new['auth_view_group_cp'], 'auth_view_group_cp');
$auth_view['profile'] = auth_select($new['auth_view_profile'], 'auth_view_profile');
$auth_view['search'] = auth_select($new['auth_view_search'], 'auth_view_search');
$auth_view['album'] = auth_select($new['auth_view_album'], 'auth_view_album');
$auth_view['links'] = auth_select($new['auth_view_links'], 'auth_view_links');
$auth_view['calendar'] = auth_select($new['auth_view_calendar'], 'auth_view_calendar');
$auth_view['attachments'] = auth_select($new['auth_view_attachments'], 'auth_view_attachments');
$auth_view['download'] = auth_select($new['auth_view_download'], 'auth_view_download');
$auth_view['pic_upload'] = auth_select($new['auth_view_pic_upload'], 'auth_view_pic_upload');
$auth_view['kb'] = auth_select($new['auth_view_kb'], 'auth_view_kb');
$auth_view['ranks'] = auth_select($new['auth_view_ranks'], 'auth_view_ranks');
$auth_view['statistics'] = auth_select($new['auth_view_statistics'], 'auth_view_statistics');
$auth_view['recent'] = auth_select($new['auth_view_recent'], 'auth_view_recent');
$auth_view['referrers'] = auth_select($new['auth_view_referrers'], 'auth_view_referrers');
$auth_view['rules'] = auth_select($new['auth_view_rules'], 'auth_view_rules');
$auth_view['shoutbox'] = auth_select($new['auth_view_shoutbox'], 'auth_view_shoutbox');
$auth_view['viewonline'] = auth_select($new['auth_view_viewonline'], 'auth_view_viewonline');
$auth_view['contact_us'] = auth_select($new['auth_view_contact_us'], 'auth_view_contact_us');
$auth_view['ajax_chat'] = auth_select($new['auth_view_ajax_chat'], 'auth_view_ajax_chat');
$auth_view['ajax_chat_archive'] = auth_select($new['auth_view_ajax_chat_archive'], 'auth_view_ajax_chat_archive');
$auth_view['custom_pages'] = auth_select($new['auth_view_custom_pages'], 'auth_view_custom_pages');

$wide_blocks_portal_yes = ($new['wide_blocks_portal']) ? 'checked="checked"' : '';
$wide_blocks_portal_no = (!$new['wide_blocks_portal']) ? 'checked="checked"' : '';
$wide_blocks_forum_yes = ($new['wide_blocks_forum']) ? 'checked="checked"' : '';
$wide_blocks_forum_no = (!$new['wide_blocks_forum']) ? 'checked="checked"' : '';
$wide_blocks_viewf_yes = ($new['wide_blocks_viewf']) ? 'checked="checked"' : '';
$wide_blocks_viewf_no = (!$new['wide_blocks_viewf']) ? 'checked="checked"' : '';
$wide_blocks_viewt_yes = ($new['wide_blocks_viewt']) ? 'checked="checked"' : '';
$wide_blocks_viewt_no = (!$new['wide_blocks_viewt']) ? 'checked="checked"' : '';
$wide_blocks_faq_yes = ($new['wide_blocks_faq']) ? 'checked="checked"' : '';
$wide_blocks_faq_no = (!$new['wide_blocks_faq']) ? 'checked="checked"' : '';
$wide_blocks_memberlist_yes = ($new['wide_blocks_memberlist']) ? 'checked="checked"' : '';
$wide_blocks_memberlist_no = (!$new['wide_blocks_memberlist']) ? 'checked="checked"' : '';
$wide_blocks_group_cp_yes = ($new['wide_blocks_group_cp']) ? 'checked="checked"' : '';
$wide_blocks_group_cp_no = (!$new['wide_blocks_group_cp']) ? 'checked="checked"' : '';
$wide_blocks_profile_yes = ($new['wide_blocks_profile']) ? 'checked="checked"' : '';
$wide_blocks_profile_no = (!$new['wide_blocks_profile']) ? 'checked="checked"' : '';
$wide_blocks_search_yes = ($new['wide_blocks_search']) ? 'checked="checked"' : '';
$wide_blocks_search_no = (!$new['wide_blocks_search']) ? 'checked="checked"' : '';
$wide_blocks_album_yes = ($new['wide_blocks_album']) ? 'checked="checked"' : '';
$wide_blocks_album_no = (!$new['wide_blocks_album']) ? 'checked="checked"' : '';
$wide_blocks_links_yes = ($new['wide_blocks_links']) ? 'checked="checked"' : '';
$wide_blocks_links_no = (!$new['wide_blocks_links']) ? 'checked="checked"' : '';
$wide_blocks_calendar_yes = ($new['wide_blocks_calendar']) ? 'checked="checked"' : '';
$wide_blocks_calendar_no = (!$new['wide_blocks_calendar']) ? 'checked="checked"' : '';
$wide_blocks_attachments_yes = ($new['wide_blocks_attachments']) ? 'checked="checked"' : '';
$wide_blocks_attachments_no = (!$new['wide_blocks_attachments']) ? 'checked="checked"' : '';
$wide_blocks_download_yes = ($new['wide_blocks_download']) ? 'checked="checked"' : '';
$wide_blocks_download_no = (!$new['wide_blocks_download']) ? 'checked="checked"' : '';
$wide_blocks_kb_yes = ($new['wide_blocks_kb']) ? 'checked="checked"' : '';
$wide_blocks_kb_no = (!$new['wide_blocks_kb']) ? 'checked="checked"' : '';
$wide_blocks_ranks_yes = ($new['wide_blocks_ranks']) ? 'checked="checked"' : '';
$wide_blocks_ranks_no = (!$new['wide_blocks_ranks']) ? 'checked="checked"' : '';
$wide_blocks_statistics_yes = ($new['wide_blocks_statistics']) ? 'checked="checked"' : '';
$wide_blocks_statistics_no = (!$new['wide_blocks_statistics']) ? 'checked="checked"' : '';
$wide_blocks_recent_yes = ($new['wide_blocks_recent']) ? 'checked="checked"' : '';
$wide_blocks_recent_no = (!$new['wide_blocks_recent']) ? 'checked="checked"' : '';
$wide_blocks_referrers_yes = ($new['wide_blocks_referrers']) ? 'checked="checked"' : '';
$wide_blocks_referrers_no = (!$new['wide_blocks_referrers']) ? 'checked="checked"' : '';
$wide_blocks_rules_yes = ($new['wide_blocks_rules']) ? 'checked="checked"' : '';
$wide_blocks_rules_no = (!$new['wide_blocks_rules']) ? 'checked="checked"' : '';
$wide_blocks_shoutbox_yes = ($new['wide_blocks_shoutbox']) ? 'checked="checked"' : '';
$wide_blocks_shoutbox_no = (!$new['wide_blocks_shoutbox']) ? 'checked="checked"' : '';
$wide_blocks_viewonline_yes = ($new['wide_blocks_viewonline']) ? 'checked="checked"' : '';
$wide_blocks_viewonline_no = (!$new['wide_blocks_viewonline']) ? 'checked="checked"' : '';
$wide_blocks_contact_us_yes = ($new['wide_blocks_contact_us']) ? 'checked="checked"' : '';
$wide_blocks_contact_us_no = (!$new['wide_blocks_contact_us']) ? 'checked="checked"' : '';
$wide_blocks_ajax_chat_yes = ($new['wide_blocks_ajax_chat']) ? 'checked="checked"' : '';
$wide_blocks_ajax_chat_no = (!$new['wide_blocks_ajax_chat']) ? 'checked="checked"' : '';
$wide_blocks_ajax_chat_archive_yes = ($new['wide_blocks_ajax_chat_archive']) ? 'checked="checked"' : '';
$wide_blocks_ajax_chat_archive_no = (!$new['wide_blocks_ajax_chat_archive']) ? 'checked="checked"' : '';
$wide_blocks_custom_pages_yes = ($new['wide_blocks_custom_pages']) ? 'checked="checked"' : '';
$wide_blocks_custom_pages_no = (!$new['wide_blocks_custom_pages']) ? 'checked="checked"' : '';

$show_cms_menu = (($userdata['user_level'] == ADMIN) || ($userdata['user_cms_level'] == CMS_CONTENT_MANAGER)) ? true : false;

$page_title = $lang['Home'];
$meta_description = '';
$meta_keywords = '';
$template->assign_vars(array(
	'S_CMS_AUTH' => true,
	'S_SHOW_CMS_MENU' => $show_cms_menu
	)
);
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

if ($board_config['cms_dock'] == true)
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

$template->set_filenames(array('body' => CMS_TPL . 'cms_pages_auth_body.tpl'));
$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_PAGES_PERMISSIONS']);

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('cms_auth.' . PHP_EXT),

	'L_CONFIGURATION_TITLE' => $lang['CMS_PAGES_PERMISSIONS'],
	'L_CONFIGURATION_EXPLAIN' => $lang['CMS_PAGES_PERMISSIONS_EXPLAIN'],
	'L_GENERAL_CONFIG' => $lang['Portal_General_Config'],
	'L_GENERAL_SETTINGS' => $lang['General_settings'],
	'L_PAGE' => $lang['CMS_Page'],
	'L_PERMISSION' => $lang['CMS_Permissions'],
	'L_WIDE_BLOCKS' => $lang['CMS_GLOBAL_BLOCKS'],

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_ENABLED' => $lang['Enabled'],
	'L_DISABLED' => $lang['Disabled'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_AUTH_VIEW_TITLE' => $lang['auth_view_title'],
	'L_AUTH_VIEW_PORTAL' => $lang['auth_view_portal'],
	'L_AUTH_VIEW_FORUM' => $lang['auth_view_forum'],
	'L_AUTH_VIEW_VIEWF' => $lang['auth_view_viewf'],
	'L_AUTH_VIEW_VIEWT' => $lang['auth_view_viewt'],
	'L_AUTH_VIEW_FAQ' => $lang['auth_view_faq'],
	'L_AUTH_VIEW_MEMBERLIST' => $lang['auth_view_memberlist'],
	'L_AUTH_VIEW_GROUP_CP' => $lang['auth_view_group_cp'],
	'L_AUTH_VIEW_PROFILE' => $lang['auth_view_profile'],
	'L_AUTH_VIEW_SEARCH' => $lang['auth_view_search'],
	'L_AUTH_VIEW_ALBUM' => $lang['auth_view_album'],
	'L_AUTH_VIEW_LINKS' => $lang['auth_view_links'],
	'L_AUTH_VIEW_CALENDAR' => $lang['auth_view_calendar'],
	'L_AUTH_VIEW_ATTACHMENTS' => $lang['auth_view_attachments'],
	'L_AUTH_VIEW_DOWNLOAD' => $lang['auth_view_download'],
	'L_AUTH_VIEW_PIC_UPLOAD' => $lang['auth_view_pic_upload'],
	'L_AUTH_VIEW_KB' => $lang['auth_view_kb'],
	'L_AUTH_VIEW_RANKS' => $lang['auth_view_ranks'],
	'L_AUTH_VIEW_STATISTICS' => $lang['auth_view_statistics'],
	'L_AUTH_VIEW_RECENT' => $lang['auth_view_recent'],
	'L_AUTH_VIEW_REFERRERS' => $lang['auth_view_referrers'],
	'L_AUTH_VIEW_RULES' => $lang['auth_view_rules'],
	'L_AUTH_VIEW_SHOUTBOX' => $lang['auth_view_shoutbox'],
	'L_AUTH_VIEW_VIEWONLINE' => $lang['auth_view_viewonline'],
	'L_AUTH_VIEW_CONTACT_US' => $lang['auth_view_contact_us'],
	'L_AUTH_VIEW_AJAX_CHAT' => $lang['auth_view_ajax_chat'],
	'L_AUTH_VIEW_AJAX_CHAT_ARCHIVE' => $lang['auth_view_ajax_chat_archive'],
	'L_AUTH_VIEW_CUSTOM_PAGES' => $lang['auth_view_custom_pages'],

	'S_AUTH_VIEW_PORTAL' => $auth_view['portal'],
	'S_AUTH_VIEW_FORUM' => $auth_view['forum'],
	'S_AUTH_VIEW_VIEWF' => $auth_view['viewf'],
	'S_AUTH_VIEW_VIEWT' => $auth_view['viewt'],
	'S_AUTH_VIEW_FAQ' => $auth_view['faq'],
	'S_AUTH_VIEW_MEMBERLIST' => $auth_view['memberlist'],
	'S_AUTH_VIEW_GROUP_CP' => $auth_view['group_cp'],
	'S_AUTH_VIEW_PROFILE' => $auth_view['profile'],
	'S_AUTH_VIEW_SEARCH' => $auth_view['search'],
	'S_AUTH_VIEW_ALBUM' => $auth_view['album'],
	'S_AUTH_VIEW_LINKS' => $auth_view['links'],
	'S_AUTH_VIEW_CALENDAR' => $auth_view['calendar'],
	'S_AUTH_VIEW_ATTACHMENTS' => $auth_view['attachments'],
	'S_AUTH_VIEW_DOWNLOAD' => $auth_view['download'],
	'S_AUTH_VIEW_PIC_UPLOAD' => $auth_view['pic_upload'],
	'S_AUTH_VIEW_KB' => $auth_view['kb'],
	'S_AUTH_VIEW_RANKS' => $auth_view['ranks'],
	'S_AUTH_VIEW_STATISTICS' => $auth_view['statistics'],
	'S_AUTH_VIEW_RECENT' => $auth_view['recent'],
	'S_AUTH_VIEW_REFERRERS' => $auth_view['referrers'],
	'S_AUTH_VIEW_RULES' => $auth_view['rules'],
	'S_AUTH_VIEW_SHOUTBOX' => $auth_view['shoutbox'],
	'S_AUTH_VIEW_VIEWONLINE' => $auth_view['viewonline'],
	'S_AUTH_VIEW_CONTACT_US' => $auth_view['contact_us'],
	'S_AUTH_VIEW_AJAX_CHAT' => $auth_view['ajax_chat'],
	'S_AUTH_VIEW_AJAX_CHAT_ARCHIVE' => $auth_view['ajax_chat_archive'],
	'S_AUTH_VIEW_CUSTOM_PAGES' => $auth_view['custom_pages'],

	'WIDE_BLOCKS_PORTAL_YES' => $wide_blocks_portal_yes,
	'WIDE_BLOCKS_FORUM_YES' => $wide_blocks_forum_yes,
	'WIDE_BLOCKS_VIEWF_YES' => $wide_blocks_viewf_yes,
	'WIDE_BLOCKS_VIEWT_YES' => $wide_blocks_viewt_yes,
	'WIDE_BLOCKS_FAQ_YES' => $wide_blocks_faq_yes,
	'WIDE_BLOCKS_MEMBERLIST_YES' => $wide_blocks_memberlist_yes,
	'WIDE_BLOCKS_GROUP_CP_YES' => $wide_blocks_group_cp_yes,
	'WIDE_BLOCKS_PROFILE_YES' => $wide_blocks_profile_yes,
	'WIDE_BLOCKS_SEARCH_YES' => $wide_blocks_search_yes,
	'WIDE_BLOCKS_ALBUM_YES' => $wide_blocks_album_yes,
	'WIDE_BLOCKS_LINKS_YES' => $wide_blocks_links_yes,
	'WIDE_BLOCKS_CALENDAR_YES' => $wide_blocks_calendar_yes,
	'WIDE_BLOCKS_ATTACHMENTS_YES' => $wide_blocks_attachments_yes,
	'WIDE_BLOCKS_DOWNLOAD_YES' => $wide_blocks_download_yes,
	'WIDE_BLOCKS_KB_YES' => $wide_blocks_kb_yes,
	'WIDE_BLOCKS_RANKS_YES' => $wide_blocks_ranks_yes,
	'WIDE_BLOCKS_STATISTICS_YES' => $wide_blocks_statistics_yes,
	'WIDE_BLOCKS_RECENT_YES' => $wide_blocks_recent_yes,
	'WIDE_BLOCKS_REFERRERS_YES' => $wide_blocks_referrers_yes,
	'WIDE_BLOCKS_RULES_YES' => $wide_blocks_rules_yes,
	'WIDE_BLOCKS_SHOUTBOX_YES' => $wide_blocks_shoutbox_yes,
	'WIDE_BLOCKS_VIEWONLINE_YES' => $wide_blocks_viewonline_yes,
	'WIDE_BLOCKS_CONTACT_US_YES' => $wide_blocks_contact_us_yes,
	'WIDE_BLOCKS_AJAX_CHAT_YES' => $wide_blocks_ajax_chat_yes,
	'WIDE_BLOCKS_AJAX_CHAT_ARCHIVE_YES' => $wide_blocks_ajax_chat_archive_yes,
	'WIDE_BLOCKS_CUSTOM_PAGES_YES' => $wide_blocks_custom_pages_yes,

	'WIDE_BLOCKS_PORTAL_NO' => $wide_blocks_portal_no,
	'WIDE_BLOCKS_FORUM_NO' => $wide_blocks_forum_no,
	'WIDE_BLOCKS_VIEWF_NO' => $wide_blocks_viewf_no,
	'WIDE_BLOCKS_VIEWT_NO' => $wide_blocks_viewt_no,
	'WIDE_BLOCKS_FAQ_NO' => $wide_blocks_faq_no,
	'WIDE_BLOCKS_MEMBERLIST_NO' => $wide_blocks_memberlist_no,
	'WIDE_BLOCKS_GROUP_CP_NO' => $wide_blocks_group_cp_no,
	'WIDE_BLOCKS_PROFILE_NO' => $wide_blocks_profile_no,
	'WIDE_BLOCKS_SEARCH_NO' => $wide_blocks_search_no,
	'WIDE_BLOCKS_ALBUM_NO' => $wide_blocks_album_no,
	'WIDE_BLOCKS_LINKS_NO' => $wide_blocks_links_no,
	'WIDE_BLOCKS_CALENDAR_NO' => $wide_blocks_calendar_no,
	'WIDE_BLOCKS_ATTACHMENTS_NO' => $wide_blocks_attachments_no,
	'WIDE_BLOCKS_DOWNLOAD_NO' => $wide_blocks_download_no,
	'WIDE_BLOCKS_KB_NO' => $wide_blocks_kb_no,
	'WIDE_BLOCKS_RANKS_NO' => $wide_blocks_ranks_no,
	'WIDE_BLOCKS_STATISTICS_NO' => $wide_blocks_statistics_no,
	'WIDE_BLOCKS_RECENT_NO' => $wide_blocks_recent_no,
	'WIDE_BLOCKS_REFERRERS_NO' => $wide_blocks_referrers_no,
	'WIDE_BLOCKS_RULES_NO' => $wide_blocks_rules_no,
	'WIDE_BLOCKS_SHOUTBOX_NO' => $wide_blocks_shoutbox_no,
	'WIDE_BLOCKS_VIEWONLINE_NO' => $wide_blocks_viewonline_no,
	'WIDE_BLOCKS_CONTACT_US_NO' => $wide_blocks_contact_us_no,
	'WIDE_BLOCKS_AJAX_CHAT_NO' => $wide_blocks_ajax_chat_no,
	'WIDE_BLOCKS_AJAX_CHAT_ARCHIVE_NO' => $wide_blocks_ajax_chat_archive_no,
	'WIDE_BLOCKS_CUSTOM_PAGES_NO' => $wide_blocks_custom_pages_no,
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>