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

//GZIP REMOVED IN HEADER...
/*
// gzip_compression
$do_gzip_compress = false;
if ($board_config['gzip_compress'])
{
	$phpver = phpversion();

	$useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ($phpver >= '4.0.4pl1' && (strstr($useragent,'compatible') || strstr($useragent,'Gecko')))
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
*/

// CMS
if(!defined('PORTAL_INIT'))
{
	include(IP_ROOT_PATH . 'includes/functions_cms.' . PHP_EXT);
	cms_config_init($cms_config_vars);
	define('PORTAL_INIT', true);
}

// MG & www.phpBB-SEO.com Dynamic meta tags - BEGIN
if (!empty($_GET[POST_POST_URL]))
{
	$meta_post_id = (intval($_GET[POST_POST_URL]) > 0) ? intval($_GET[POST_POST_URL]) : 0;
}
elseif (!empty($_GET[POST_TOPIC_URL]))
{
	$meta_topic_id = (intval($_GET[POST_TOPIC_URL]) > 0) ? intval($_GET[POST_TOPIC_URL]) : 0;
}
elseif (!empty($_GET[POST_FORUM_URL]))
{
	$meta_forum_id = (intval($_GET[POST_FORUM_URL]) > 0) ? intval($_GET[POST_FORUM_URL]) : 0;
}
elseif (!empty($_GET[POST_CAT_URL]))
{
	$meta_cat_id = (intval($_GET[POST_CAT_URL]) > 0) ? intval($_GET[POST_CAT_URL]) : 0;
}

$page_title = ($page_title == '') ? $board_config['sitename'] : strip_tags($page_title);
$page_title_simple = strip_tags($page_title);

$meta_description = !empty($meta_description) ? $meta_description : '';
$meta_keywords = !empty($meta_keywords) ? $meta_keywords : '';

$page_url = pathinfo($_SERVER['PHP_SELF']);
$no_meta_pages_array = array(LOGIN_MG, 'privmsg.' . PHP_EXT, POSTING_MG);
if (!in_array($page_url['basename'], $no_meta_pages_array) && (!empty($meta_post_id) || !empty($meta_topic_id) || !empty($meta_forum_id) || !empty($meta_cat_id)))
{
	include(IP_ROOT_PATH . 'includes/meta_parsing.' . PHP_EXT);
}
else
{
	$page_title = (defined('IN_LOGIN') ? $lang['Login'] : $page_title);
	$meta_description = $lang['Default_META_Description'];
	$meta_keywords = $lang['Default_META_Keywords'];
}

$meta_description = !empty($meta_description) ? ($meta_description . (META_TAGS_ATTACH ? $lang['Default_META_Description'] : '')) : $lang['Default_META_Description'];
$meta_keywords = !empty($meta_keywords) ? ($meta_keywords . (META_TAGS_ATTACH ? (' - ' . $lang['Default_META_Keywords']) : '')) : $lang['Default_META_Keywords'];

$phpbb_meta = '<meta name="title" content="' . $page_title . '" />' . "\n";
$phpbb_meta .= '<meta name="author" content="' . $lang['Default_META_Author'] . '" />' . "\n";
$phpbb_meta .= '<meta name="copyright" content="' . $lang['Default_META_Copyright'] . '" />' . "\n";
$phpbb_meta .= '<meta name="description" content="' . $meta_description . '" />' . "\n";
$phpbb_meta .= '<meta name="keywords" content="' . $meta_keywords . '" />' . "\n";
$phpbb_meta .= '<meta name="category" content="general" />' . "\n";
if (defined('IN_SEARCH'))
{
	$phpbb_meta .= '<meta name="robots" content="noindex,nofollow" />' . "\n";
}
else
{
	$phpbb_meta .= '<meta name="robots" content="index,follow" />' . "\n";
}
// MG & www.phpBB-SEO.com Dynamic meta tags - END

// Mighty Gorgon - Smart Header - Begin
//$server_url = create_server_url();
$page_url = pathinfo($_SERVER['PHP_SELF']);
$page_query = $_SERVER['QUERY_STRING'];

$doctype_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
//$doctype_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
$doctype_html .= '<html xmlns="http://www.w3.org/1999/xhtml" dir="' . $lang['DIRECTION'] . '" lang="' . $lang['HEADER_LANG'] . '" xml:lang="' . $lang['HEADER_XML_LANG'] . '">' . "\n";

$xhtml_doc_type = true;
if ($board_config['smart_header'])
{
	$html_pages_array = array(PORTAL_MG, VIEWTOPIC_MG, 'album_showpage.' . PHP_EXT, POSTING_MG);
	if (in_array($page_url['basename'], $html_pages_array))
	{
		$xhtml_doc_type = false;
	}

	if ($xhtml_doc_type == true)
	{
		$html_query_array = array("/article/", "/edit/", "/add/", "/results/", "/news=categories/");
		for ($i; $i < count($html_query_array); $i++)
		{
			if (preg_match($html_query_array[$i], $page_query) == 1)
			{
				$xhtml_doc_type = false;
				break;
			}
		}
	}

	if ($xhtml_doc_type == false)
	{
		$doctype_html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">' . "\n";
		$doctype_html .= '<html dir="' . $lang['DIRECTION'] . '">' . "\n";
	}
}

if ($page_url['basename'] == 'viewonline.' . PHP_EXT)
{
	$phpbb_meta .= '<meta http-equiv="refresh" content="180;url=viewonline.' . PHP_EXT . '" />' . "\n";
}
// Mighty Gorgon - Smart Header - End

// gzip_compression
// begin gzip fix
ob_start();
// end gzip fix

// Header tpl
if (defined('IN_CMS'))
{
	$board_config['cms_style'] = (!empty($_GET['cms_style']) ? ((intval($_GET['cms_style']) == 1) ? 1 : 0) : $board_config['cms_style']);
	$cms_style = ($board_config['cms_style'] == 1) ? '' : '_std';
	$header_tpl = CMS_TPL . 'page_header' . $cms_style . '.tpl';
}
elseif (empty($gen_simple_header))
{
	$header_tpl = 'overall_header.tpl';
}
else
{
	$header_tpl = 'simple_header.tpl';
}

$template->set_filenames(array('overall_header' => $header_tpl));

// Mighty Gorgon - Advanced Switches - BEGIN

// LOGGED IN CHECK - BEGIN
if (!$userdata['session_logged_in'])
{
	$template->assign_block_vars('switch_user_logged_out', array());
	// Allow autologin?
	if (!isset($board_config['allow_autologin']) || $board_config['allow_autologin'])
	{
		$template->assign_block_vars('switch_allow_autologin', array());
	}

	/*
	$pattern = array("'", '\'', '\\\'', '"', ';');
	$replace = array('', '', '', '', '');
	$smart_redirect = strrchr(htmlentities(str_replace($pattern, $replace, htmlentities(stripslashes($_SERVER['PHP_SELF']), ENT_QUOTES))), '/');
	*/
	$smart_redirect = strrchr($_SERVER['PHP_SELF'], '/');
	$smart_redirect = substr($smart_redirect, 1, strlen($smart_redirect));

	if(($smart_redirect == (PROFILE_MG)) || ($smart_redirect == (LOGIN_MG)))
	{
		$smart_redirect = '';
	}

	if(isset($_GET) && !empty($smart_redirect))
	{
		$smart_get_keys = array_keys($_GET);

		for ($i = 0; $i < count($_GET); $i++)
		{
			if ($smart_get_keys[$i] != 'sid')
			{
				$smart_redirect .= '&amp;' . $smart_get_keys[$i] . '=' . urlencode(ip_utf8_decode($_GET[$smart_get_keys[$i]]));
			}
		}
	}
	$u_login_logout = LOGIN_MG;
	$u_login_logout .= (!empty($smart_redirect)) ? '?redirect=' . $smart_redirect : '';
	$l_login_logout = $lang['Login'];
	$l_login_logout2 = $lang['Login'];

	$s_last_visit = '';
	$icon_pm = $images['pm_no_new_msg'];
	$l_privmsgs_text = $lang['Login_check_pm'];
	$l_privmsgs_text_unread = '';
	$s_privmsg_new = 0;
}
else
{
	$template->assign_block_vars('switch_user_logged_in', array());

	if (!empty($userdata['user_popup_pm']))
	{
		$template->assign_block_vars('switch_enable_pm_popup', array());
	}

	$u_login_logout = LOGIN_MG . '?logout=true&amp;sid=' . $userdata['session_id'];
	$l_login_logout = $lang['Logout'] . ' (' . $userdata['username'] . ') ';
	$l_login_logout2 = $lang['Logout'];
	$s_last_visit = create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']);

	// DOWNLOADS ADV - BEGIN
	/*
	if ($userdata['user_new_download'])
	{
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_new_download = 0
			WHERE user_id = " . $userdata['user_id'];
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update new download view for user', '', __LINE__, __FILE__, $sql);
		}

		if ($userdata['user_dl_note_type'])
		{
			$template->assign_block_vars('switch_new_download', array(
				'U_NEW_DOWNLOAD_POPUP' => append_sid('downloads.' . PHP_EXT . '?view=popup')
				)
			);
		}
		else
		{
			$template->assign_block_vars('switch_new_download_message', array(
				'NEW_DOWNLOAD_POPUP' => sprintf($lang['New_download'], '<a href="' . append_sid('downloads.' . PHP_EXT) . '">', '</a>'))
			);
		}
	}

	$sql = "SELECT id FROM " . DL_CAT_TABLE . " WHERE bug_tracker = 1";
	if($result = $db->sql_query($sql))
	{
		$bug_tracker = $db->sql_numrows($result);
	}
	$db->sql_freeresult($result);
	if ($bug_tracker)
	{
		$template->assign_block_vars('bug_tracker_head', array(
			'L_BUG_TRACKER' => $lang['Dl_bug_tracker'],
			'U_BUG_TRACKER' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker')
			)
		);
	}
	*/
	// DOWNLOADS ADV - END

	// Obtain number of new private messages
	if (empty($gen_simple_header))
	{

		// Birthday - BEGIN
		// see if user has or have had birthday, also see if greeting are enabled
		if (($userdata['user_birthday'] != 999999) && $board_config['birthday_greeting'] && (create_date('Ymd', time(), $board_config['board_timezone']) >= $userdata['user_next_birthday_greeting'] . realdate('md', $userdata['user_birthday'])))
		{
			// Birthday PM - BEGIN
			$pm_subject = $lang['Greeting_Messaging'];
			$pm_date = date('U');

			$year = create_date('Y', time(), $board_config['board_timezone']);
			$date_today = create_date('Ymd', time(), $board_config['board_timezone']);
			$user_birthday = realdate('md', $userdata['user_birthday']);
			$user_birthday2 = (($year . $user_birthday < $date_today) ? ($year + 1) : $year) . $user_birthday;

			$user_age = create_date('Y', time(), $board_config['board_timezone']) - realdate('Y', $userdata['user_birthday']);
			if (create_date('md', time(), $board_config['board_timezone']) < realdate('md', $userdata['user_birthday']))
			{
				$user_age--;
			}

			$pm_text = ($user_birthday2 == $date_today) ? sprintf($lang['Birthday_greeting_today'], $user_age) : sprintf($lang['Birthday_greeting_prev'], $user_age, realdate(str_replace('Y', '', $lang['DATE_FORMAT_BIRTHDAY']), $userdata['user_birthday']) . ((!empty($userdata['user_next_birthday_greeting']) ? ($userdata['user_next_birthday_greeting']) : '')));

			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());

			include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);
			$privmsg_subject = sprintf($pm_subject, $board_config['sitename']);
			$privmsg_message = sprintf($pm_text, $board_config['sitename'], $board_config['sitename']);
			$privmsg_sender = $founder_id;
			$privmsg_recipient = $userdata['user_id'];

			$privmsg = new class_pm();
			$privmsg->delete_older_message('PM_INBOX', $privmsg_recipient);
			$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message);
			unset($privmsg);
			// Birthday PM - END

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_next_birthday_greeting = " . (create_date('Y', time(), $board_config['board_timezone']) + 1) . "
				WHERE user_id = " . $userdata['user_id'];
			if(!$status = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update next_birthday_greeting for user.', '', __LINE__, __FILE__, $sql);
			}

			/*
			$template->assign_var('GREETING_POPUP',
				'<script type="text/javascript">
				<!--
				window.open(\'' . append_sid('birthday_popup.' . PHP_EXT) . '\',\'_phpbbprivmsg\',\'height=225,width=400,resizable=yes\');
				//-->
				</script>'
			);
			*/
		} //Sorry user shall not have a greeting this year
		// Birthday - END

		if ($userdata['user_profile_view'] && $userdata['user_profile_view_popup'])
		{
			$template->assign_var('PROFILE_VIEW',
				'<script type="text/javascript">
				<!--
				window.open(\'' . append_sid('profile_view_popup.' . PHP_EXT) . '\',\'_phpbbprivmsg\',\'height=800,width=250,resizable=yes\');
				//-->
				</script>'
			);
		}

		if ($userdata['user_new_privmsg'] && ($board_config['privmsg_disable'] == false))
		{
			$l_message_new = ($userdata['user_new_privmsg'] == 1) ? $lang['New_pm'] : $lang['New_pms'];
			$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

			if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'])
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_last_privmsg = '" . $userdata['user_lastvisit'] . "'
					WHERE user_id = '" . $userdata['user_id'] . "'";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
				}

				$s_privmsg_new = 1;
				$icon_pm = $images['pm_new_msg'];
			}
			else
			{
				$s_privmsg_new = 0;
				$icon_pm = $images['pm_new_msg'];
			}
		}
		else
		{
			$l_privmsgs_text = $lang['No_new_pm'];
			$s_privmsg_new = 0;
			$icon_pm = $images['pm_no_new_msg'];
		}

		if ($userdata['user_unread_privmsg'])
		{
			$l_message_unread = ($userdata['user_unread_privmsg'] == 1) ? $lang['Unread_pm'] : $lang['Unread_pms'];
			$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
		}
		else
		{
			$l_privmsgs_text_unread = $lang['No_unread_pm'];
		}
	}
	else
	{
		$icon_pm = $images['pm_no_new_msg'];
		$l_privmsgs_text = $lang['Login_check_pm'];
		$l_privmsgs_text_unread = '';
		$s_privmsg_new = 0;
	}

	// We don't want this SQL being too expensive... so we will allow the number of new messages only for users which log on frequently
	if (($board_config['enable_new_messages_number'] == true) && ($userdata['user_lastvisit'] > (time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 60 * 60 * 24))))
	{
		$sql = "SELECT COUNT(post_id) as total
			FROM " . POSTS_TABLE . "
			WHERE post_time >= " . $userdata['user_lastvisit'] . "
			AND poster_id != " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		if($result)
		{
			$row = $db->sql_fetchrow($result);
			$lang['Search_new'] = $lang['Search_new'] . ' (' . $row['total'] . ')';
			$lang['New'] = $lang['New'] . ' (' . $row['total'] . ')';
			$lang['New2'] = $lang['New_Label'] . ' (' . $row['total'] . ')';
			$lang['New3'] = $lang['New_Messages_Label'] . ' (' . $row['total'] . ')';
			$lang['Search_new2'] = $lang['Search_new2'] . ' (' . $row['total'] . ')';
			$lang['Search_new_p'] = $lang['Search_new_p'] . ' (' . $row['total'] . ')';
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$lang['New2'] = $lang['New_Label'];
		$lang['New3'] = $lang['New_Messages_Label'];
	}
}
// LOGGED IN CHECK - END

// Digests - BEGIN
if ($board_config['enable_digests'] == true)
{
	if (!defined('DIGEST_SITE_URL'))
	{
		$digest_server_url = create_server_url();
		define('DIGEST_SITE_URL', $digest_server_url);
	}
	@include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_digests.' . PHP_EXT);
	if ($userdata['session_logged_in'])
	{
		$template->assign_block_vars('switch_show_digests', array());
	}

	// DIGESTS TEMP CODE - BEGIN
	// MG PHP Cron Emulation For Digests - BEGIN
	$is_allowed = true;
	// If you want to assign the extra SQL charge to non registered users only, decomment this line... ;-)
	$is_allowed = (!$userdata['session_logged_in']) ? true : false;
	$digests_pages_array = array(PROFILE_MG, POSTING_MG);
	if (($board_config['digests_php_cron'] == true) && $is_allowed && !in_array($page_url['basename'], $digests_pages_array))
	//if (($board_config['digests_php_cron'] == true) && ($board_config['digests_php_cron_lock'] == false) && (!$userdata['session_logged_in']) && !in_array($page_url['basename'], $digests_pages_array))
	{
		if ((time() - $board_config['digests_last_send_time']) > CRON_REFRESH)
		{
			$board_config['digests_last_send_time'] = ($board_config['digests_last_send_time'] == 0) ? (time() - 3600) : $board_config['digests_last_send_time'];
			$last_send_time = getdate($board_config['digests_last_send_time']);
			$cur_time = getdate();
			if ($cur_time['hours'] <> $last_send_time['hours'])
			{
				set_config('digests_php_cron_lock', 1);
				define('PHP_DIGESTS_CRON', true);
				include_once(IP_ROOT_PATH . 'mail_digests.' . PHP_EXT);
			}
		}
	}
	// MG PHP Cron Emulation For Digests - END
	// DIGESTS TEMP CODE - END
}
// Digests - END

// Visit Counter - BEGIN
if ($board_config['visit_counter_switch'] == true)
{
	$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = (config_value + 1)
			WHERE config_name = 'visit_counter'";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not update counter information', '', __LINE__, __FILE__, $SQL);
	}
}
// Visit Counter - END

// Mighty Gorgon - Random Quote - Begin
if ($board_config['show_random_quote'] == true)
{
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_randomquote.' . PHP_EXT);
	$randomquote_phrase = $randomquote[rand(0, count($randomquote) - 1)];
}
else
{
	$randomquote_phrase = '';
}
// Mighty Gorgon - Random Quote - End

// Mighty Gorgon - Change Lang/Style - Begin
$style_select = '';
$lang_select = '';
if (($board_config['select_theme'] == true) || (($board_config['select_lang'] == true) && (!$userdata['session_logged_in'])))
{
	include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

	if ($board_config['select_theme'] == true)
	{
		$template->assign_block_vars('style_select_on', array());
		$style_select = style_select_h($board_config['default_style']);
	}

	if (($board_config['select_lang'] == true) && (!$userdata['session_logged_in']))
	{
		$template->assign_block_vars('lang_select_on', array());
		$lang_installed = language_select_h($board_config['default_lang'], 'language');

		while (list($displayname) = @each($lang_installed))
		{
			$lang_value = $displayname;
			$lang_name = ucwords($displayname);
			$template->assign_block_vars('lang_select_on.lang_select', array(
				'LANG_FLAG' => 'language/lang_' . $displayname . '/flag.png',
				'LANG_NAME' => $lang_name,
				'LANG_VALUE'=> $lang_value,
				'U_LANG_CHANGE'=> append_sid('changelang.' . PHP_EXT . '?' . LANG_URL . '=' . $lang_value),
				)
			);
		}
	}
}
// Mighty Gorgon - Change Lang/Style - End

// Mighty Gorgon - AJAX Features - Begin
if ($board_config['ajax_features'] == true)
{
	$template->assign_block_vars('switch_ajax_features', array());
	$ajax_user_check = 'onkeyup="AJAXUsernameSearch(this.value, 0);"';
	$ajax_user_check_alt = 'onkeyup="AJAXUsernameSearch(this.value, 1);"';
}
else
{
	$ajax_user_check = '';
	$ajax_user_check_alt = '';
}
// Mighty Gorgon - AJAX Features - End

// Mighty Gorgon - Advanced Switches - END

// Show Online Block - BEGIN
// Get basic (usernames + totals) online situation
$online_userlist = '';
$l_online_users = '';
$ac_online_text = '';
$ac_username_lists = '';
if (defined('SHOW_ONLINE'))
{
	include(IP_ROOT_PATH . 'includes/users_online_block.' . PHP_EXT);
}
// Show Online Block - END

// Generate HTML required for Mozilla Navigation bar
$nav_base_url = create_server_url();
if (!isset($nav_links))
{
	$nav_links = array();
}
$nav_links_html = '';
while(list($nav_item, $nav_array) = @each($nav_links))
{
	if (!empty($nav_array['url']))
	{
		$nav_links_html .= '<link rel="' . $nav_item . '" type="text/html" title="' . strip_tags($nav_array['title']) . '" href="' . $nav_base_url . $nav_array['url'] . '" />' . "\n";
	}
	else
	{
		// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
		while(list(,$nested_array) = each($nav_array))
		{
			$nav_links_html .= '<link rel="' . $nav_item . '" type="text/html" title="' . strip_tags($nested_array['title']) . '" href="' . $nav_base_url . $nested_array['url'] . '" />' . "\n";
		}
	}
}

// RSS Autodiscovery - BEGIN
$rss_url = $nav_base_url . 'rss.' . PHP_EXT;
$rss_forum_id = (isset($_GET[POST_FORUM_URL])) ? intval($_GET[POST_FORUM_URL]): 0;
$rss_url_append = '';
$rss_a_url_append = '';
if($rss_forum_id != 0)
{
	$rss_url_append = '?' . POST_FORUM_URL . '=' . $rss_forum_id;
	$rss_a_url_append = '&amp;' . POST_FORUM_URL . '=' . $rss_forum_id;
}
$nav_links_html .= '<link rel="alternate" type="application/rss+xml" title="RSS" href="' . $rss_url . $rss_url_append . '" />' . "\n";
$nav_links_html .= '<link rel="alternate" type="application/atom+xml" title="Atom" href="' . $rss_url . '?atom' . $rss_a_url_append . '" />' . "\n";
// RSS Autodiscovery - END

//<!-- BEGIN Unread Post Information to Database Mod -->
$upi2db_first_use = '';
$u_display_new = array();
if($userdata['upi2db_access'])
{
	$unread = unread();
	$u_display_new = index_display_new($unread);
	$template->assign_block_vars('switch_upi2db_on', array());
	$upi2db_first_use = ($userdata['user_upi2db_datasync'] == '0') ? ('<script type="text/javascript">' . "\n" . '// <![CDATA[' . "\n" . 'alert ("' . $lang['upi2db_first_use_txt'] . '");' . "\n" . '// ]]>' . "\n" . '</script>') : '';
}
else
{
	if ($userdata['session_logged_in'])
	{
		$template->assign_block_vars('switch_upi2db_off', array());
	}
}
//<!-- END Unread Post Information to Database Mod -->

// Time Management - BEGIN
// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$s_timezone = str_replace('.0', '', sprintf('%.1f', number_format($board_config['board_timezone'], 1)));
$l_timezone = $lang['tzs'][$s_timezone];

if (!$userdata['session_logged_in'])
{
	$userdata['user_time_mode'] = $board_config['default_time_mode'];
}

switch ($userdata['user_time_mode'])
{
	case MANUAL_DST:
		$time_message = sprintf($lang['All_times'], $l_timezone) . $lang['dst_enabled_mode'];
		break;
	case SERVER_SWITCH:
		$time_message = sprintf($lang['All_times'], $l_timezone);
		if (date('I', time()))
		{
			$time_message = $time_message . $lang['dst_enabled_mode'];
		}
		break;
	default:
		$time_message = sprintf($lang['All_times'], $l_timezone);
		break;
}
$time_message = str_replace('GMT', 'UTC', $time_message);
// Time Management - END

// CrackerTracker v5.x
/*
 * CrackerTracker IP Range Scanner
 */
if (isset($_GET['marknow']) && ($_GET['marknow'] == 'ipfeature') && $userdata['session_logged_in'])
{
	// Mark IP Feature Read
	$userdata['ct_last_ip'] = $userdata['ct_last_used_ip'];
	$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_last_ip = ct_last_used_ip WHERE user_id=' . $userdata['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
	}
	if (!empty($_SERVER['HTTP_REFERER']))
	{
		preg_match('#/([^/]*?)$#', $_SERVER['HTTP_REFERER'], $backlink);
		redirect($backlink[1]);
	}
}

if (!empty($ctracker_config) && ($ctracker_config->settings['login_ip_check'] == 1) && ($userdata['ct_enable_ip_warn'] == 1) && $userdata['session_logged_in'])
{
	include_once(IP_ROOT_PATH . '/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
	$ctracker_user = new ct_userfunctions();
	$check_ip_range = $ctracker_user->check_ip_range();

	if ($check_ip_range != 'allclear')
	{
		$template->assign_block_vars('ctracker_message', array(
			'ROW_COLOR' => 'ffdfdf',
			'ICON_GLOB' => $images['ctracker_note'],
			'L_MESSAGE_TEXT' => $check_ip_range,
			'L_MARK_MESSAGE' => $lang['ctracker_gmb_markip'],
			'U_MARK_MESSAGE' => append_sid('index.' . PHP_EXT . '?marknow=ipfeature')
			)
		);
	}
}

/*
 * CrackerTracker Global Message Function
 */
if (isset($_GET['marknow']) && ($_GET['marknow'] == 'globmsg') && $userdata['session_logged_in'])
{
	// Mark Global Message as read
	$userdata['ct_global_msg_read'] = 0;
	$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_global_msg_read = 0 WHERE user_id=' . $userdata['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
	}
	if (!empty($_SERVER['HTTP_REFERER']))
	{
		preg_match('#/([^/]*?)$#', $_SERVER['HTTP_REFERER'], $backlink);
		redirect($backlink[1]);
	}
}

if (!empty($ctracker_config) && ($userdata['ct_global_msg_read'] == 1) && $userdata['session_logged_in'] && ($ctracker_config->settings['global_message'] != ''))
{
	// Output Global Message
	$global_message_output = '';

	if ($ctracker_config->settings['global_message_type'] == 1)
	{
		$global_message_output = $ctracker_config->settings['global_message'];
	}
	else
	{
		$global_message_output = sprintf($lang['ctracker_gmb_link'], $ctracker_config->settings['global_message'], $ctracker_config->settings['global_message']);
	}

	$template->assign_block_vars('ctracker_message', array(
		'ROW_COLOR' => 'e1ffdf',
		'ICON_GLOB' => $images['ctracker_note'],
		'L_MESSAGE_TEXT' => $global_message_output,
		'L_MARK_MESSAGE' => $lang['ctracker_gmb_mark'],
		'U_MARK_MESSAGE' => append_sid('index.' . PHP_EXT . '?marknow=globmsg')
		)
	);
}

if (!empty($ctracker_config) && ((($ctracker_config->settings['login_history'] == 1) || ($ctracker_config->settings['login_ip_check'] == 1)) && ($userdata['session_logged_in'])))
{
	$template->assign_block_vars('login_sec_link', array());
}

/*
 * CrackerTracker Password Expirement Check
 */
if ($userdata['session_logged_in'] && ($ctracker_config->settings['pw_control'] == 1))
{
	if (time() > $userdata['ct_last_pw_reset'])
	{
		$template->assign_block_vars('ctracker_message', array(
			'ROW_COLOR' => 'ffdfdf',
			'ICON_GLOB' => $images['ctracker_note'],
			'L_MESSAGE_TEXT' => sprintf($lang['ctracker_info_pw_expired'], $ctracker_config->settings['pw_validity'], $userdata['user_id']),
			'L_MARK_MESSAGE' => '',
			'U_MARK_MESSAGE' => ''
			)
		);
	}
}
/*
 * CrackerTracker Debug Mode Check
 */
if (defined('CT_DEBUG_MODE') && (CT_DEBUG_MODE === true) && ($userdata['user_level'] == ADMIN))
{
	$template->assign_block_vars('ctracker_message', array(
		'ROW_COLOR' => 'ffdfdf',
		'ICON_GLOB' => $images['ctracker_note'],
		'L_MESSAGE_TEXT' => $lang['ctracker_dbg_mode'],
		'L_MARK_MESSAGE' => '',
		'U_MARK_MESSAGE' => ''
		)
	);
}
// CrackerTracker v5.x

if ($board_config['switch_header_table'])
{
	$template->assign_block_vars('switch_header_table', array(
		'HEADER_TEXT' => ip_stripslashes($board_config['header_table_text']),
		'L_STAFF_MESSAGE' => $lang['staff_message'],
		)
	);
}

$top_html_block_text = get_ad('glt');
$header_banner_text = get_ad('glh');
$nav_menu_ads_top = get_ad('nmt');
$nav_menu_ads_bottom = get_ad('nmb');

if(!empty($css_style_include) && is_array($css_style_include))
{
	for ($i = 0; $i < count($css_style_include); $i++)
	{
		$template->assign_block_vars('css_style_include', array(
			'CSS_FILE' => $css_style_include[$i],
			)
		);
	}
}

if(!empty($css_include) && is_array($css_include))
{
	for ($i = 0; $i < count($css_include); $i++)
	{
		$template->assign_block_vars('css_include', array(
			'CSS_FILE' => $css_include[$i],
			)
		);
	}
}

if(!empty($js_include) && is_array($js_include))
{
	for ($i = 0; $i < count($js_include); $i++)
	{
		$template->assign_block_vars('js_include', array(
			'JS_FILE' => $js_include[$i],
			)
		);
	}
}

// The following assigns all _common_ variables that may be used at any point in a template.
$template->assign_vars(array(
	'DOCTYPE_HTML' => $doctype_html,
	'IP_ROOT_PATH' => IP_ROOT_PATH,
	'PHP_EXT' => PHP_EXT,
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
	'S_TIMEZONE' => $time_message,
	'SITENAME' => $board_config['sitename'],
	'SITE_DESCRIPTION' => $board_config['site_desc'],
	'PAGE_TITLE' => ($board_config['page_title_simple'] == true ? $page_title_simple : $page_title),
	'L_PAGE_TITLE' => $page_title_simple,
	'META_TAG' => $phpbb_meta,
	'U_ACP' => '<a href="' . ADM . '/index.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a>',
	'S_LOGIN_ACTION' => append_sid(LOGIN_MG),
	'NAV_SEP' => $lang['Nav_Separator'],
	'NAV_DOT' => '&#8226;',
	'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
	'TOTAL_USERS_ONLINE' => $l_online_users,
	'LOGGED_IN_USER_LIST' => $online_userlist,
	'BOT_LIST' => !empty($online_botlist) ? $online_botlist : '',
	'AC_LIST_TEXT' => $ac_online_text,
	'AC_LIST' => $ac_username_lists,
	'RECORD_USERS' => sprintf($lang['Record_online_users'], $board_config['record_online_users'], create_date($board_config['default_dateformat'], $board_config['record_online_date'], $board_config['board_timezone'])),
	'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
	'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
	'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,
	'PRIVMSG_IMG' => $icon_pm,
//<!-- BEGIN Unread Post Information to Database Mod -->
	'UPI2DB_FIRST_USE' => $upi2db_first_use,
//<!-- END Unread Post Information to Database Mod -->

	'TOP_HTML_BLOCK' => $top_html_block_text,
	'HEADER_BANNER_CODE' => $header_banner_text,
	'NAV_MENU_ADS_TOP' => $nav_menu_ads_top,
	'NAV_MENU_ADS_BOTTOM' => $nav_menu_ads_bottom,

	// SWITCHES - BEGIN
	'S_HEADER_DROPDOWN' => ($board_config['switch_header_dropdown'] ? true : false),
	'S_HEADER_DD_LOGGED_IN' => (($board_config['switch_header_dropdown'] && $userdata['upi2db_access']) ? true : false),
	'S_HEADER_BANNER' => (empty($header_banner_text) ? false : true),
	'S_HIGHSLIDE' => ($board_config['thumbnail_highslide'] ? true : false),
	'S_LIGHTBOX' => ((!$board_config['thumbnail_highslide'] && $board_config['thumbnail_lightbox']) ? true : false),
	// SWITCHES - END

	// CrackerTracker v5.x
	'L_LOGIN_SEC' => $lang['ctracker_gmb_loginlink'],
	'U_LOGIN_SEC' => append_sid('ct_login_history.' . PHP_EXT),
	// CrackerTracker v5.x

	'L_USERNAME' => $lang['Username'],
	'L_PASSWORD' => $lang['Password'],
	'L_LOGIN_LOGOUT' => $l_login_logout,
	'L_LOGIN_LOGOUT2' => $l_login_logout2,
	'L_LOGIN' => $lang['Login'],
	'L_HOME' => $lang['Home'],
	'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
	'L_REGISTER' => $lang['Register'],
	'L_BOARDRULES' => $lang['BoardRules'],
	'L_PROFILE' => $lang['Profile'],
	'L_CPL_NAV' => $lang['Profile'],
	'L_SEARCH' => $lang['Search'],
	'L_PRIVATEMSGS' => $lang['Private_Messages'],
	'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
	'L_MEMBERLIST' => $lang['Memberlist'],
	'L_FAQ' => $lang['FAQ'],
	'L_REFERRERS' => $lang['Referrers'],
	'L_ADV_SEARCH' => $lang['Adv_Search'],
	'L_SEARCH_EXPLAIN' => $lang['Search_Explain'],

	'L_KB' => $lang['KB_title'],
	'L_NEWS' => $lang['News_Cmx'],
	'L_USERGROUPS' => $lang['Usergroups'],
	'L_SEARCH_NEW' => $lang['Search_new'],
	'L_SEARCH_NEW2' => $lang['Search_new2'],
	'L_NEW' => $lang['New'],
	'L_NEW2' => (empty($lang['New2']) ? $lang['New_Label'] : $lang['New2']),
	'L_NEW3' => (empty($lang['New3']) ? $lang['New_Messages_Label'] : $lang['New3']),
	'L_POSTS' => $lang['Posts'],
//<!-- BEGIN Unread Post Information to Database Mod -->
	'L_DISPLAY_ALL' => (!empty($u_display_new) ? $u_display_new['all'] : ''),
	'L_DISPLAY_U' => (!empty($u_display_new) ? $u_display_new['u'] : ''),
	'L_DISPLAY_M' => (!empty($u_display_new) ? $u_display_new['m'] : ''),
	'L_DISPLAY_P' => (!empty($u_display_new) ? $u_display_new['p'] : ''),
	'L_DISPLAY_UNREAD' => (!empty($u_display_new) ? $u_display_new['unread'] : ''),
	'L_DISPLAY_MARKED' => (!empty($u_display_new) ? $u_display_new['marked'] : ''),
	'L_DISPLAY_PERMANENT' => (!empty($u_display_new) ? $u_display_new['permanent'] : ''),

	'L_DISPLAY_U_S' => (!empty($u_display_new) ? $u_display_new['u_string_full'] : ''),
	'L_DISPLAY_M_S' => (!empty($u_display_new) ? $u_display_new['m_string_full'] : ''),
	'L_DISPLAY_P_S' => (!empty($u_display_new) ? $u_display_new['p_string_full'] : ''),
	'L_DISPLAY_UNREAD_S' => (!empty($u_display_new) ? $u_display_new['unread_string'] : ''),
	'L_DISPLAY_MARKED_S' => (!empty($u_display_new) ? $u_display_new['marked_string'] : ''),
	'L_DISPLAY_PERMANENT_S' => (!empty($u_display_new) ? $u_display_new['permanent_string'] : ''),
	'U_DISPLAY_U' => (!empty($u_display_new) ? $u_display_new['u_url'] : ''),
	'U_DISPLAY_M' => (!empty($u_display_new) ? $u_display_new['m_url'] : ''),
	'U_DISPLAY_P' => (!empty($u_display_new) ? $u_display_new['p_url'] : ''),
//<!-- END Unread Post Information to Database Mod -->
	'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
	'L_BOARD_DISABLE' => $lang['Board_disabled'],

	'L_SEARCH_SELF' => $lang['Search_your_posts'],
	'L_RECENT' => $lang['Recent_topics'],
	'L_WATCHED_TOPICS' => $lang['Watched_Topics'],
	'L_BOOKMARKS' => $lang['Bookmarks'],
	'L_DIGESTS' => $lang['Digests'],
	'L_DRAFTS' => $lang['Drafts'],

	'U_SEARCH_UNANSWERED' => append_sid(SEARCH_MG . '?search_id=unanswered'),
	'U_SEARCH_SELF' => append_sid(SEARCH_MG . '?search_id=egosearch'),
	'U_SEARCH_NEW' => append_sid(SEARCH_MG . '?search_id=newposts'),
	'U_PORTAL' => append_sid(PORTAL_MG),
	'U_INDEX' => append_sid(FORUM_MG),
	'U_REGISTER' => append_sid(PROFILE_MG . '?mode=register'),
	'U_BOARDRULES' => append_sid('rules.' . PHP_EXT),
	'U_PROFILE' => append_sid('profile_main.' . PHP_EXT),
	'U_PRIVATEMSGS' => append_sid('privmsg.' . PHP_EXT . '?folder=inbox'),
	'U_PRIVATEMSGS_POPUP' => append_sid('privmsg.' . PHP_EXT . '?mode=newpm'),
	'U_SEARCH' => append_sid(SEARCH_MG),

	'U_MEMBERLIST' => append_sid('memberlist.' . PHP_EXT),
	'U_MODCP' => append_sid('modcp.' . PHP_EXT),
	'U_FAQ' => append_sid('faq.' . PHP_EXT),
	'U_REFERRERS' => append_sid('referrers.' . PHP_EXT),
	'U_KB' => append_sid('kb.' . PHP_EXT),
	'U_NEWS' => append_sid($board_config['news_base_url'] . $board_config['news_index_file']),
	'U_WATCHED_TOPICS' => append_sid(IP_ROOT_PATH . 'watched_topics.' . PHP_EXT),

	'U_VIEWONLINE' => append_sid('viewonline.' . PHP_EXT),
	'U_LOGIN_LOGOUT' => append_sid($u_login_logout),
	'U_GROUP_CP' => append_sid('groupcp.' . PHP_EXT),
	'U_SUDOKU' => append_sid('sudoku.' . PHP_EXT),
	'U_BOOKMARKS' => append_sid(SEARCH_MG . '?search_id=bookmarks'),
	'U_RECENT' => append_sid('recent.' . PHP_EXT),
	'U_DIGESTS' => append_sid('digests.' . PHP_EXT),
	'U_DRAFTS' => append_sid('drafts.' . PHP_EXT),
	// Activity - BEGIN
	/*
	'L_WHOSONLINE_GAMES' => '<a href="'. append_sid('activity.' . PHP_EXT) .'"><span style="color:#'. str_replace('#', '', $board_config['ina_online_list_color']) . ';">' . $board_config['ina_online_list_text'] . '</span></a>',
	*/
	'P_ACTIVITY_MOD_PATH' => ACTIVITY_MOD_PATH,
	'U_ACTIVITY' => append_sid('activity.' . PHP_EXT),
	'L_ACTIVITY' => $lang['Activity'],
	// Activity - END

	// AJAX Features - BEGIN
	'S_AJAX_USER_CHECK' => $ajax_user_check,
	'S_AJAX_USER_CHECK_ALT' => $ajax_user_check_alt,
	// AJAX Features - END

	// Ajax Shoutbox - BEGIN
	'L_AJAX_SHOUTBOX' => $lang['Ajax_Chat'],
	'U_AJAX_SHOUTBOX' => append_sid('ajax_chat.' . PHP_EXT),
	'U_AJAX_SHOUTBOX_PP' => append_sid('ajax_shoutbox.' . PHP_EXT),
	// Ajax Shoutbox - END

	'L_BACK_TOP' => $lang['Back_to_top'],
	'L_BACK_BOTTOM' => $lang['Back_to_bottom'],
	'U_BACK_TOP' => '#top',
	'U_BACK_BOTTOM' => '#bottom',

	// Mighty Gorgon - Nav Links - BEGIN
	'L_CALENDAR' => $lang['Calendar'],
	'L_DOWNLOADS' => $lang['Downloads'],
	'L_DOWNLOADS_ADV' => $lang['Downloads_ADV'],
	'L_HACKS_LIST' => $lang['Hacks_List'],
	'L_SUDOKU' => $lang['Sudoku'],
	'L_AVATAR_GEN' => $lang['AvatarGenerator'],
	'L_LINKS' => $lang['Links'],
	'L_RSS_FEEDS' => $lang['Rss_news_feeds'],
	'L_WORDGRAPH' => $lang['Wordgraph'],
	'L_ACRONYMS' => $lang['Acronyms'],
	'L_DELETE_COOKIES' => $lang['Delete_cookies'],
	'L_SITEMAP' => $lang['Sitemap'],
	//'L_' => $lang[''],

	'U_CALENDAR' => append_sid('calendar.' . PHP_EXT),
	'U_DOWNLOADS_NAV' => append_sid(DOWNLOADS_MG),
	'U_DOWNLOADS' => append_sid('dload.' . PHP_EXT),
	'U_DOWNLOADS_ADV' => append_sid('downloads.' . PHP_EXT),
	'U_HACKS_LIST' => append_sid('credits.' . PHP_EXT),
	'U_STATISTICS' => append_sid('statistics.' . PHP_EXT),
	'U_PORTAL_NEWS_CAT' => append_sid(PORTAL_MG . '?news=categories'),
	'U_PORTAL_NEWS_ARC' => append_sid(PORTAL_MG . '?news=archives'),
	'U_LINKS' => append_sid('links.' . PHP_EXT),
	'U_WORDGRAPH' => append_sid('wordgraph.' . PHP_EXT),
	'U_ACRONYMS' => append_sid('acronyms.' . PHP_EXT),
	'U_DELETE_COOKIES' => append_sid('remove_cookies.' . PHP_EXT),
	'U_SITEMAP' => append_sid('sitemap.' . PHP_EXT),
	//'U_' => append_sid('.' . PHP_EXT),
	// Mighty Gorgon - Nav Links - END
	// Mighty Gorgon - Multiple Ranks - BEGIN
	'L_RANKS' => $lang['Rank_Header'],
	'L_STAFF' => $lang['Staff'],
	'U_RANKS' => append_sid('ranks.' . PHP_EXT),
	'U_STAFF' => append_sid('memberlist.' . PHP_EXT . '?mode=staff'),
	// Mighty Gorgon - Multiple Ranks - END
	//'U_STAFF' => append_sid('staff.' . PHP_EXT),
	'L_CONTACT_US' => $lang['Contact_us'],
	'U_CONTACT_US' => append_sid('contact_us.' . PHP_EXT),
	'L_UPLOAD_IMAGE' => $lang['Upload_Image_Local'],
	'U_UPLOAD_IMAGE' => append_sid('upload.' . PHP_EXT),
	'L_UPLOADED_IMAGES' => $lang['Uploaded_Images_Local'],
	'U_UPLOADED_IMAGES' => append_sid('posted_img_list.' . PHP_EXT),
	// Mighty Gorgon - Full Album Pack - BEGIN
	'L_ALBUM' => $lang['Album'],
	'U_ALBUM' => append_sid('album.' . PHP_EXT),
	'L_PIC_NAME' => $lang['Pic_Name'],
	'L_DESCRIPTION' => $lang['Description'],
	'L_GO' => $lang['Go'],
	'L_SEARCH_CONTENTS' => $lang['Search_Contents'],
	'L_SEARCH_MATCHES' => $lang['Search_Matches'],
	// Mighty Gorgon - Full Album Pack - END

	'U_ATTACH_RULES' => 'attach_rules.' . PHP_EXT . '?' . POST_FORUM_URL . '=',

	// Mighty Gorgon - Random Quote - Begin
	'RANDOM_QUOTE' => $randomquote_phrase,
	// Mighty Gorgon - Random Quote - End

	// Mighty Gorgon - CMS - BEGIN
	'L_CMS' => $lang['CMS_TITLE'],
	'U_CMS' => append_sid('cms.' . PHP_EXT),
	'U_CMS_CONFIG' => append_sid('cms.' . PHP_EXT . '?mode=config'),
	'U_CMS_PAGES_PERMISSIONS' => append_sid('cms_auth.' . PHP_EXT),
	'U_CMS_ADS' => append_sid('cms_ads.' . PHP_EXT),
	'U_CMS_MENU' => append_sid('cms_menu.' . PHP_EXT),
	'L_CMS_ACP' => $lang['Admin_panel'],
	'U_CMS_ACP' => ADM . '/index.' . PHP_EXT . '?sid=' . $userdata['session_id'],
	'U_CMS_GLOBAL_BLOCKS' => append_sid('cms.' . PHP_EXT . '?mode=blocks&amp;l_id=0&amp;action=editglobal'),
	'U_CMS_STANDARD_PAGES' => append_sid('cms.' . PHP_EXT . '?mode=layouts_special'),
	'U_CMS_CUSTOM_PAGES' => append_sid('cms.' . PHP_EXT . '?mode=layouts'),
	'U_CMS_CUSTOM_PAGES_ADV' => append_sid('cms.' . PHP_EXT . '?mode=layouts_adv'),
	// Mighty Gorgon - CMS - END

	// Mighty Gorgon - Change Lang/Style - Begin
	'REQUEST_URI' => htmlspecialchars(urldecode($_SERVER['REQUEST_URI'])),
	'STYLE_SELECT_H' => $style_select,
	'L_SELECT_STYLE' => $lang['Change_Style'],

	'LANGUAGE_SELECT_H' => $lang_select,
	'L_SELECT_LANG' => $lang['Change_Lang'],
	// Mighty Gorgon - Change Lang/Style - End

	'U_PREFERENCES' => append_sid('profile_options.' . PHP_EXT),
	'L_PREFERENCES' => $lang['Preferences'],

	// Mighty Gorgon - CPL - BEGIN
	'L_VIEWER' => $lang['Username'],
	'L_NUMBER' => $lang['Views'],
	'L_STAMP' => $lang['Last_updated'],
	'L_YOUR_ACTIVITY' => $lang['Cpl_Personal_Profile'],
	'L_PROFILE_EXPLAIN' => $lang['profile_explain'],
	'L_PROFILE_MAIN' => $lang['profile_main'],

	'L_CPL_NAV' => $lang['Profile'],
	'L_CPL_REG_INFO' => $lang['Registration_info'],
	'L_CPL_DELETE_ACCOUNT' => $lang['Delete_My_Account'],
	'L_CPL_PROFILE_INFO' => $lang['Profile_info'],
	'L_CPL_PROFILE_VIEWED' => $lang['Profile_viewed'],
	'L_CPL_AVATAR_PANEL' => $lang['Avatar_panel'],
	'L_CPL_SIG_EDIT' => $lang['sig_edit_link'],
	'L_CPL_PREFERENCES' => $lang['Preferences'],
	'L_CPL_SETTINGS_OPTIONS' => $lang['Cpl_Settings_Options'],
	'L_CPL_BOARD_SETTINGS' => $lang['Cpl_Board_Settings'],
	'L_CPL_MORE_INFO' => $lang['Cpl_More_info'],
	'L_CPL_NEWMSG' => $lang['Cpl_NewMSG'],
	'L_CPL_PERSONAL_PROFILE' => $lang['Cpl_Personal_Profile'],
	'L_CPL_OWN_POSTS' => $lang['Search_your_posts'],
	'L_CPL_OWN_PICTURES' => $lang['Personal_Gallery'],
	'L_CPL_BOOKMARKS' => $lang['Bookmarks'],
	'L_CPL_SUBSCFORUMS' => $lang['UCP_SubscForums'],
	'L_CPL_PRIVATE_MESSAGES' => $lang['Private_Messages'],
	'L_CPL_INBOX' => $lang['Inbox'],
	'L_CPL_OUTBOX' => $lang['Outbox'],
	'L_CPL_SAVEBOX' => $lang['Savebox'],
	'L_CPL_SENTBOX' => $lang['Sentbox'],
	'L_CPL_DRAFTS' => $lang['Drafts'],
	'L_CPL_ZEBRA' => $lang['UCP_ZEBRA'],

	'L_CPL_ZEBRA_EXPLAIN' => $lang['FRIENDS_EXPLAIN'],

	'U_CPL_PROFILE_VIEWED' => append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $userdata['user_id']),
	'U_CPL_NEWMSG' => append_sid('privmsg.' . PHP_EXT . '?mode=post'),
	'U_CPL_REGISTRATION_INFO' => append_sid(PROFILE_MG . '?mode=editprofile&amp;cpl_mode=reg_info'),
	'U_CPL_DELETE_ACCOUNT' => append_sid('contact_us.' . PHP_EXT . '?account_delete=' . $userdata['user_id']),
	'U_CPL_PROFILE_INFO' => append_sid(PROFILE_MG . '?mode=editprofile&amp;cpl_mode=profile_info'),
	'U_CPL_PREFERENCES' => append_sid(PROFILE_MG . '?mode=editprofile&amp;cpl_mode=preferences'),
	'U_CPL_BOARD_SETTINGS' => append_sid(PROFILE_MG . '?mode=editprofile&amp;cpl_mode=board_settings'),
	'U_CPL_AVATAR_PANEL' => append_sid(PROFILE_MG . '?mode=editprofile&amp;cpl_mode=avatar'),
	'U_CPL_SIGNATURE' => append_sid(PROFILE_MG . '?mode=signature'),
	'U_CPL_OWN_POSTS' => append_sid(SEARCH_MG. '?search_author=' . urlencode($userdata['username']) . '&amp;showresults=posts'),
	'U_CPL_OWN_PICTURES' => append_sid('album.' . PHP_EXT . '?user_id=' . $userdata['user_id']),
	'U_CPL_CALENDAR_SETTINGS' => append_sid('profile_options.' . PHP_EXT . '?sub=preferences&amp;mod=1&amp;' . POST_USERS_URL . '=' . $userdata['user_id']),
	'U_CPL_SUBFORUM_SETTINGS' => append_sid('profile_options.' . PHP_EXT . '?sub=preferences&amp;mod=0&amp;' . POST_USERS_URL . '=' . $userdata['user_id']),
	'U_CPL_SUBSCFORUMS' => append_sid('subsc_forums.' . PHP_EXT),
	'U_WATCHED_TOPICS' => append_sid('watched_topics.' . PHP_EXT),
	'U_CPL_BOOKMARKS' => append_sid(SEARCH_MG . '?search_id=bookmarks'),
	'U_PRIVATEMSGS' => append_sid('privmsg.' . PHP_EXT . '?folder=inbox'),
	'U_CPL_INBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=inbox'),
	'U_CPL_OUTBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=outbox'),
	'U_CPL_SAVEBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=savebox'),
	'U_CPL_SENTBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=sentbox'),
	'U_CPL_DRAFTS' => append_sid('drafts.' . PHP_EXT),
	'U_CPL_ZEBRA' => append_sid(PROFILE_MG . '?mode=zebra&amp;zmode=friends'),
	// Mighty Gorgon - CPL - END

	'SHOW_QUICK_LINKS_IMG' => $images['show_quick_links'],
	'SHOW_LATEST_NEWS_IMG' => $images['show_latest_news'],

	//Style vars
	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#' . $theme['body_bgcolor'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],

	'NAV_LINKS' => $nav_links_html
	)
);

// Mighty Gorgon - CMS IMAGES - BEGIN
if (defined('IN_CMS'))
{
	$template->assign_vars(array(
		'IMG_LAYOUT_BLOCKS_EDIT' => $images['layout_blocks_edit'],
		'IMG_LAYOUT_PREVIEW' => $images['layout_preview'],
		'IMG_BLOCK_EDIT' => $images['block_edit'],
		'IMG_BLOCK_DELETE' => $images['block_delete'],
		'IMG_CMS_ARROW_UP' => $images['arrows_cms_up'],
		'IMG_CMS_ARROW_DOWN' => $images['arrows_cms_down'],
		)
	);
}
// Mighty Gorgon - CMS IMAGES - END

// Add no-cache control for cookies if they are set
//$c_no_cache = (isset($_COOKIE[$board_config['cookie_name'] . '_sid']) || isset($_COOKIE[$board_config['cookie_name'] . '_data'])) ? 'no-cache="set-cookie", ' : '';

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

// get the nav sentence
$nav_key = '';
if (isset($_POST[POST_CAT_URL]) || isset($_GET[POST_CAT_URL]))
{
	$nav_key = POST_CAT_URL . ((isset($_POST[POST_CAT_URL])) ? intval($_POST[POST_CAT_URL]) : intval($_GET[POST_CAT_URL]));
}
if (isset($_POST[POST_FORUM_URL]) || isset($_GET[POST_FORUM_URL]))
{
	$nav_key = POST_FORUM_URL . ((isset($_POST[POST_FORUM_URL])) ? intval($_POST[POST_FORUM_URL]) : intval($_GET[POST_FORUM_URL]));
}
if (isset($_POST[POST_TOPIC_URL]) || isset($_GET[POST_TOPIC_URL]))
{
	$nav_key = POST_TOPIC_URL . ((isset($_POST[POST_TOPIC_URL])) ? intval($_POST[POST_TOPIC_URL]) : intval($_GET[POST_TOPIC_URL]));
}
if (isset($_POST[POST_POST_URL]) || isset($_GET[POST_POST_URL]))
{
	$nav_key = POST_POST_URL . ((isset($_POST[POST_POST_URL])) ? intval($_POST[POST_POST_URL]) : intval($_GET[POST_POST_URL]));
}
if (empty($nav_key) && (isset($_POST['selected_id']) || isset($_GET['selected_id'])))
{
	$nav_key = isset($_GET['selected_id']) ? $_GET['selected_id'] : $_POST['selected_id'];
}
if (empty($nav_key))
{
	$nav_key = 'Root';
}

$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;
$nav_cat_desc = '';
if (!isset($skip_nav_cat))
{
	$nav_pgm = empty($nav_pgm) ? '' : $nav_pgm;
	$nav_cat_desc = make_cat_nav_tree($nav_key, $nav_pgm);
}

if ($nav_cat_desc != '')
{
	$nav_server_url = create_server_url();
	$nav_cat_desc = $nav_separator . $nav_cat_desc;
	$breadcrumbs_address = $nav_separator . '<a href="' . $nav_server_url . append_sid(FORUM_MG) . '">' . $lang['Forum'] . '</a>' . $nav_cat_desc;
	if (isset($nav_add_page_title) && ($nav_add_page_title == true))
	{
		$breadcrumbs_address = $breadcrumbs_address . $nav_separator . '<a href="#" class="nav-current">' . $page_title . '</a>';
	}
}

// send to template
$template->assign_vars(array(
	//'SPACER' => $images['spacer'],
	'S_PAGE_NAV' => (isset($cms_page_nav) ? $cms_page_nav : true),
	'NAV_SEPARATOR' => $nav_separator,
	'NAV_CAT_DESC' => $nav_cat_desc,
	'BREADCRUMBS_ADDRESS' => (empty($breadcrumbs_address) ? (($page_title_simple != $board_config['sitename']) ? ($lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $page_title_simple . '</a>') : '') : $breadcrumbs_address),
	'S_BREADCRUMBS_LINKS_LEFT' => (empty($breadcrumbs_links_left) ? false : true),
	'BREADCRUMBS_LINKS_LEFT' => (empty($breadcrumbs_links_left) ? false : $breadcrumbs_links_left),
	'S_BREADCRUMBS_LINKS_RIGHT' => (empty($breadcrumbs_links_right) ? false : true),
	'BREADCRUMBS_LINKS_RIGHT' => (empty($breadcrumbs_links_right) ? '&nbsp;' : $breadcrumbs_links_right),
	)
);

if ($board_config['show_calendar_box_index'] == true)
{
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	if ($path_parts['basename'] != LOGIN_MG)
	{
		if (!defined('IN_CALENDAR'))
		{
			if (intval($board_config['calendar_header_cells']) > 0)
			{
				$template->assign_block_vars('switch_calendar_box', array());
				include_once(IP_ROOT_PATH . './includes/functions_calendar.' . PHP_EXT);
				display_calendar('CALENDAR_BOX', intval($board_config['calendar_header_cells']));
			}
		}
	}
}

if ($board_config['board_disable'] && ($userdata['user_level'] == ADMIN))
{
	$template->assign_block_vars('switch_admin_disable_board', array());
}

$cms_global_blocks = (empty($cms_global_blocks) ? false : true);
//$cms_global_blocks = ((!isset($cms_page_id) || !$cms_global_blocks) ? false : true);
$cms_page_blocks = ((empty($cms_page_id) || empty($cms_config_layouts[$cms_page_id])) ? false : true);
if(empty($gen_simple_header) && !defined('HAS_DIED') && !defined('IN_LOGIN') && ($cms_global_blocks || $cms_page_blocks) && (!$board_config['board_disable'] || ($userdata['user_level'] == ADMIN)))
{
	$template->assign_var('SWITCH_CMS_GLOBAL_BLOCKS', true);
	cms_parse_blocks($cms_page_id, !empty($cms_page_id), $cms_global_blocks, 'header');
	if (cms_parse_blocks($cms_page_id, !empty($cms_page_id), $cms_global_blocks, 'headerleft'))
	{
		$template->assign_vars(array(
			'HEADER_WIDTH' => $cms_config_vars['header_width'],
			'HL_BLOCK' => true,
			)
		);
	}
	if (cms_parse_blocks($cms_page_id, !empty($cms_page_id), $cms_global_blocks, 'headercenter'))
	{
		$template->assign_var('HC_BLOCK', true);
	}
}

if(empty($gen_simple_header))
{
	if (cms_parse_blocks(0, true, true, 'ghtop'))
	{
		$template->assign_var('GT_BLOCK', true);
	}
	if (cms_parse_blocks(0, true, true, 'ghbottom'))
	{
		$template->assign_var('GB_BLOCK', true);
	}
	if (cms_parse_blocks(0, true, true, 'ghleft'))
	{
		$template->assign_var('GL_BLOCK', true);
	}
	if (cms_parse_blocks(0, true, true, 'ghright'))
	{
		$template->assign_var('GR_BLOCK', true);
	}
}
/*
*/

$template->pparse('overall_header');

if (($userdata['user_level'] != ADMIN) && $board_config['board_disable'] && !defined('IN_ADMIN') && !defined('IN_LOGIN'))
{
	if($board_config['board_disable_mess_st'])
	{
		$sql = "SELECT config_value FROM " . CONFIG_TABLE . " WHERE config_name = 'board_disable_message'";
		$gm_result = $db->sql_query($sql) or message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
		$mon_message = mysql_result($gm_result, 0);
		$mon_message = ip_stripslashes($mon_message);
		message_die(GENERAL_MESSAGE, $mon_message);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Board_disabled']);
	}
}

if ($board_config['google_bot_detector'] == true)
{
	//if($userdata['bot_id'] != false)
	if (eregi('googlebot', $_SERVER['HTTP_USER_AGENT']))
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . (($_SERVER['QUERY_STRING'] != '') ? '?' . $_SERVER['QUERY_STRING'] : '');
		$now = time();

		$sql = "INSERT INTO " . GOOGLE_BOT_DETECTOR_TABLE . "(detect_time, detect_url) VALUES('$now', '$url')";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update detect information', '', __LINE__, __FILE__, $sql);
		}
	}
}

if (defined('PARSE_CPL_NAV'))
{
	$template->set_filenames(array('cpl_menu_output' => 'profile_cpl_menu.tpl'));
	$template->assign_var_from_handle('CPL_MENU_OUTPUT', 'cpl_menu_output');
}

?>