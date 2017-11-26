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
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
define('NUM_SHOUT', 20);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Force all active content BBCodes OFF!
$config['switch_bbcb_active_content'] = 0;

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

$cms_page['page_id'] = 'shoutbox';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

// Start auth check
switch ($user->data['user_level'])
{
	//Customize this, if you need other permission settings
	// please also make same changes to other shoutbox php files
	case ADMIN:
	case MOD:
		$is_auth['auth_mod'] = 1;
	default:
			$is_auth['auth_read'] = 1;
			$is_auth['auth_view'] = 1;
			if ($user->data['user_id'] == ANONYMOUS)
			{
				$is_auth['auth_delete'] = 0;
				$is_auth['auth_post'] = 0;
			}
			else
			{
				$is_auth['auth_delete'] = 1;
				$is_auth['auth_post'] = 1;
			}
}

if(!$is_auth['auth_read'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}

$refresh = (check_http_var_exists('auto_refresh', false) || check_http_var_exists('refresh', false)) ? 1 : 0;
$preview = (isset($_POST['preview'])) ? 1 : 0;
$submit = (isset($_POST['shout']) && isset($_POST['message'])) ? 1 : 0;

$mode = request_var('mode', '');

// Set toggles for various options
if (!$config['allow_html'])
{
	$html_on = 0;
}
else
{
	$html_on = ($submit || $refresh || $preview) ? ((!empty($_POST['disable_html'])) ? 0 : 1) : (($user->data['user_id'] == ANONYMOUS) ? $config['allow_html'] : $user->data['user_allowhtml']);
}
if (!$config['allow_bbcode'])
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ($submit || $refresh || $preview) ? ((!empty($_POST['disable_bbcode'])) ? 0 : 1) : (($user->data['user_id'] == ANONYMOUS) ? $config['allow_bbcode'] : $user->data['user_allowbbcode']);
}

if (!$config['allow_smilies'])
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ($submit || $refresh || $preview) ? ((!empty($_POST['disable_smilies'])) ? 0 : 1) : (($user->data['user_id'] == ANONYMOUS) ? $config['allow_smilies'] : $user->data['user_allowsmile']);
}
if(!$user->data['session_logged_in'] || ($mode == 'editpost' && $post_info['poster_id'] == ANONYMOUS))
{
	$template->assign_block_vars('switch_username_select', array());
}
$username = request_post_var('username', '', true);
$username = htmlspecialchars_decode($username, ENT_COMPAT);
// Check username
if (!empty($username))
{
	$username = phpbb_clean_username($username);
	if (!$user->data['session_logged_in'])
	{
		require_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
		$result = validate_username($username);
		if ($result['error'])
		{
			$error = true;
			$error_msg .= (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];
		}
	}
}

if ($refresh || $preview)
{
	$message = request_post_var('message', '', true);
	if (!empty($message))
	{
		if ($preview)
		{

			$preview_message = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on));
			if ($config['img_shoutbox'])
			{
				$preview_message = preg_replace ("#\[url=(http://)([^ \"\n\r\t<]*)\]\[img\](http://)([^ \"\n\r\t<]*)\[/img\]\[/url\]#i", '[url=\\1\\2]\\4[/url]', $preview_message);
				$preview_message = preg_replace ("#\[img\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $preview_message);
				$preview_message = preg_replace ("#\[img align=left\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $preview_message);
				$preview_message = preg_replace ("#\[img align=right\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $preview_message);
			}

			$preview_message = censor_text($preview_message);

			$bbcode->allow_html = ($config['allow_html'] ? true : false);
			$bbcode->allow_bbcode = ($config['allow_bbcode'] && $bbcode_on ? true : false);
			$bbcode->allow_smilies = ($config['allow_smilies'] && $smilies_on ? true : false);

			$preview_message = $bbcode->parse($preview_message);
			//$preview_message = str_replace("\n", '<br />', $preview_message);

			$preview_message = $bbcode->acronym_pass($preview_message);
			$preview_message = $bbcode->autolink_text($preview_message, '999999');

			$template->set_filenames(array('preview' => 'posting_preview.tpl'));
			$template->assign_vars(array(
				'USERNAME' => $username,
				'POST_DATE' => create_date_ip($config['default_dateformat'], time(), $config['board_timezone']),
				'PREVIEW_MESSAGE' => $preview_message,
				'L_POSTED' => $lang['Posted'],
				'L_PREVIEW' => $lang['Preview']
				)
			);
			$template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
		}
		$template->assign_var('MESSAGE', $message);
	}
}
elseif ($submit || isset($_POST['message']))
{
	$current_time = time();
	// Flood control
	$where_sql = ($user->data['user_id'] == ANONYMOUS) ? "shout_ip = '$user_ip'" : 'shout_user_id = ' . $user->data['user_id'];
	$sql = "SELECT MAX(shout_session_time) AS last_post_time
		FROM " . SHOUTBOX_TABLE . "
		WHERE $where_sql";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		if ($row = $db->sql_fetchrow($result))
		{
			if (($row['last_post_time'] > 0) && (($current_time - $row['last_post_time']) < $config['flood_interval']) && ($user->data['user_level'] != ADMIN))
			{
				$error = true;
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Flood_Error'] : $lang['Flood_Error'];
			}
		}
	}

	$message = request_post_var('message', '', true);
	$message = htmlspecialchars_decode($message, ENT_COMPAT);
	// insert shout !
	if (!empty($message) && $is_auth['auth_post'] && !$error)
	{
		if ($config['img_shoutbox'] == true)
		{
			$message = preg_replace ("#\[url=(http://)([^ \"\n\r\t<]*)\]\[img\](http://)([^ \"\n\r\t<]*)\[/img\]\[/url\]#i", '[url=\\1\\2]\\4[/url]', $message);
			$message = preg_replace ("#\[img\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $message);
			$message = preg_replace ("#\[img align=left\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $message);
			$message = preg_replace ("#\[img align=right\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $message);
		}

		$message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on);
		//$message = (!get_magic_quotes_gpc()) ? addslashes($message) : stripslashes($message);
		$sql = "INSERT INTO " . SHOUTBOX_TABLE. " (shout_text, shout_session_time, shout_user_id, shout_ip, shout_username, enable_bbcode, enable_html, enable_smilies)
				VALUES ('" . $db->sql_escape($message) . "', '" . time() . "', '" . $user->data['user_id'] . "', '$user_ip', '" . $db->sql_escape($username) . "', $bbcode_on, $html_on, $smilies_on)";
		$result = $db->sql_query($sql);

		// auto prune
		if ($config['prune_shouts'])
		{
			$sql = "DELETE FROM " . SHOUTBOX_TABLE . " WHERE shout_session_time<=" . (time() - (86400 * $config['prune_shouts']));
			$result = $db->sql_query($sql);
		}
	}
}
elseif (($mode == 'delete') || ($mode == 'censor'))
{
	// make shout inactive
	$post_id = request_var(POST_POST_URL, 0);
	if (empty($post_id))
	{
		message_die(GENERAL_ERROR, 'Error no shout id specifyed for delete/censor.', '', __LINE__, __FILE__);
	}
	$sql = "SELECT s.shout_user_id, shout_ip FROM " . SHOUTBOX_TABLE . " s WHERE s.shout_id = $post_id";
	$result = $db->sql_query($sql);
	$shout_identifyer = $db->sql_fetchrow($result);
	$user_id = $shout_identifyer['shout_user_id'];

	if ((($user->data['user_id'] != ANONYMOUS) || (($user->data['user_id'] == ANONYMOUS) && ($user->data['session_ip'] == $shout_identifyer['shout_ip']))) && ((($user->data['user_id'] == $user_id) && $is_auth['auth_delete']) || $is_auth['auth_mod']) && ($mode == 'censor'))
	{
		$sql = "UPDATE " . SHOUTBOX_TABLE . " SET shout_active = " . $user->data['user_id'] . " WHERE shout_id = $post_id";
		$result = $db->sql_query($sql);
	}
	elseif ($is_auth['auth_mod'] && ($mode == 'delete'))
	{
		$sql = "DELETE FROM " . SHOUTBOX_TABLE . " WHERE shout_id = $post_id";
		$result = $db->sql_query($sql);
	}
	else
	{
		message_die(GENERAL_MESSAGE, 'Not allowed.', '', __LINE__, __FILE__);
	}
}
elseif ($mode == 'ip')
{
	//	show the ip
	if (!$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, 'Not allowed.', '', __LINE__, __FILE__);
	}
	if (isset($_GET[POST_POST_URL]) || isset($_POST[POST_POST_URL]))
	{
		$post_id = (isset($_POST[POST_POST_URL])) ? intval($_POST[POST_POST_URL]) : intval($_GET[POST_POST_URL]);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Error no shout id specifyed for show ip', '', __LINE__, __FILE__);
	}
	$sql = "SELECT s.shout_user_id, shout_username, shout_ip FROM " . SHOUTBOX_TABLE . " s WHERE s.shout_id = $post_id";
	$result = $db->sql_query($sql);
	$shout_identifyer = $db->sql_fetchrow($result);
	$poster_id = $shout_identifyer['shout_user_id'];
	$rdns_ip_num = (isset($_GET['rdns'])) ? $_GET['rdns'] : '';

	$ip_this_post = $shout_identifyer['shout_ip'];
	$ip_this_post = ($rdns_ip_num == $ip_this_post) ? gethostbyaddr($ip_this_post) : $ip_this_post;

	$template->assign_vars(array(
		'L_IP_INFO' => $lang['IP_info'],
		'L_THIS_POST_IP' => $lang['This_posts_IP'],
		'L_OTHER_IPS' => $lang['Other_IP_this_user'],
		'L_OTHER_USERS' => $lang['Users_this_IP'],
		'L_LOOKUP_IP' => $lang['Lookup_IP'],
		'L_SEARCH' => $lang['Search'],
		'SEARCH_IMG' => $images['icon_search'],
		'IP' => htmlspecialchars($ip_this_post),
		'U_LOOKUP_IP' => append_sid('shoutbox_max.' . PHP_EXT . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;rdns=' . htmlspecialchars(urlencode($ip_this_post)))
		)
	);

	// Get other IP's this user has posted under
	$sql = "SELECT shout_ip, COUNT(*) AS postings
		FROM " . SHOUTBOX_TABLE . "
		WHERE shout_user_id = $poster_id
		GROUP BY shout_ip
		ORDER BY postings DESC";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$i = 0;
		do
		{
			if ($row['shout_ip'] == $post_row['shout_ip'])
			{
				$template->assign_vars(array(
					'POSTS' => $row['postings'] . ' ' . (($row['postings'] == 1) ? $lang['Post'] : $lang['Posts'])
					)
				);
				continue;
			}

			$ip = $row['shout_ip'];
			$ip = (($rdns_ip_num == $row['shout_ip']) || ($rdns_ip_num == 'all')) ? gethostbyaddr($ip) : $ip;

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('iprow', array(
				'ROW_CLASS' => $row_class,
				'IP' => htmlspecialchars($ip),
				'POSTS' => $row['postings'] . ' ' . (($row['postings'] == 1) ? $lang['Post'] : $lang['Posts']),

				'U_LOOKUP_IP' => append_sid('shoutbox_max.' . PHP_EXT . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;rdns=' . htmlspecialchars(urlencode($row['shout_ip'])))
				)
			);

			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
	}

	// Get other users who've posted under this IP
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, COUNT(*) as postings
		FROM " . USERS_TABLE ." u, " . POSTS_TABLE . " p
		WHERE p.poster_id = u.user_id
			AND p.poster_ip = '" . $shout_identifyer['shout_ip'] . "'
		GROUP BY u.user_id, u.username
		ORDER BY postings DESC";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$i = 0;
		do
		{
			$id = $row['user_id'];
			$shout_username = ($id == ANONYMOUS) ? $lang['Guest'] : $row['username'];

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('userrow', array(
				'ROW_CLASS' => $row_class,
				'SHOUT_USERNAME' => $shout_username,
				'POSTS' => $row['postings'] . ' ' . (($row['postings'] == 1) ? $lang['Post'] : $lang['Posts']),
				'L_SEARCH_POSTS' => sprintf($lang['Search_user_posts'], $shout_username),

				'U_PROFILE_COL' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
				'U_PROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $id),
				'U_SEARCHPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode($shout_username) . '&amp;showresults=topics')
				)
			);

			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
	}

	full_page_generation('modcp_viewip.tpl', $lang['Shoutbox'], '', '');
}

// Was a highlight request part of the URI?
$highlight_match = $highlight = '';
if (isset($_GET['highlight']))
{
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars($_GET['highlight'])));

	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', phpbb_preg_quote($words[$i], '#'));
		}
	}
	unset($words);

	$highlight = urlencode($_GET['highlight']);
	$highlight_match = rtrim($highlight_match, "\\");
}

$ranks_array = $cache->obtain_ranks(false);

// get statistics
$sql = "SELECT COUNT(*) as total FROM " . SHOUTBOX_TABLE;
$result = $db->sql_query($sql);
$total_shouts = $db->sql_fetchrow($result);
$total_shouts = $total_shouts['total'];
// parse post permission
if ($is_auth['auth_post'])
{
	$template_to_parse = 'shoutbox_max_body.tpl';
}
else
{
	$template_to_parse = 'shoutbox_max_guest_body.tpl';
}

// Generate smilies listing for page output
//generate_smilies('inline');

// Smilies toggle selection
if ($config['allow_smilies'])
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
	$smilies_status = $lang['Smilies_are_OFF'];
}

// HTML toggle selection
if ($config['allow_html'] || (($user->data['user_level'] == ADMIN) && $config['allow_html_only_for_admins']))
{
	$html_status = $lang['HTML_is_ON'];
	$template->assign_block_vars('switch_html_checkbox', array());
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

// BBCode toggle selection
if ($config['allow_bbcode'])
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

// display the shoutbox
$sql = "SELECT s.*, u.username, u.user_id, u.user_active, u.user_color, u.user_posts, u.user_from, u.user_from_flag, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_skype, u.user_regdate, u.user_msnm, u.user_allow_viewemail, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, u.user_allow_viewonline, u.user_session_time, u.user_warnings, u.user_level, u.user_birthday, u.user_next_birthday_greeting, u.user_gender, u.user_personal_pics_count, u.user_style, u.user_lang
				FROM " . SHOUTBOX_TABLE . " s, " . USERS_TABLE . " u
				WHERE s.shout_user_id = u.user_id
				ORDER BY s.shout_session_time DESC
				LIMIT $start, " . $config['posts_per_page'];
$result = $db->sql_query($sql);

while ($shout_row = $db->sql_fetchrow($result))
{
	$i++;
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
	$user_id = $shout_row['shout_user_id'];
	$shout_username = ($user_id == ANONYMOUS) ? (($shout_row['shout_username'] == '') ? $lang['Guest'] : $shout_row['shout_username']) : colorize_username($shout_row['user_id'], $shout_row['username'], $shout_row['user_color'], $shout_row['user_active']);

	$user_info = array();
	$user_info = generate_user_info($shout_row);
	foreach ($user_info as $k => $v)
	{
		$$k = $v;
	}

	$user_posts = ($shout_row['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $shout_row['user_posts'] : '';
	$user_from = ($shout_row['user_from'] && ($shout_row['user_id'] != ANONYMOUS)) ? $lang['Location'] . ': ' . $shout_row['user_from'] : '';
	$user_joined = ($shout_row['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $shout_row['user_regdate'], $config['board_timezone']) : '';

	$user_avatar = $user_info['avatar'];
	$user_avatar_img = user_get_avatar($shout_row['user_id'], $shout_row['user_level'], $shout_row['user_avatar'], $shout_row['user_avatar_type'], $shout_row['user_allowavatar'], '', 30);

	$shout = $shout_row['shout_text'];
	$user_sig = ($shout_row['enable_sig'] && ($shout_row['user_sig'] != '') && $config['allow_sig']) ? $shout_row['user_sig'] : '';

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$user_ranks = generate_ranks($shout_row, $ranks_array);
	if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html']  == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
	{
		$user_ranks['rank_01_html'] = '&nbsp;';
	}
	// Mighty Gorgon - Multiple Ranks - END

	$user_rank = $user_ranks['rank_01_html'];
	$rank_image = $user_ranks['rank_01_img_html'];

	if ($user_sig != '')
	{
		$bbcode->allow_html = ($config['allow_html'] ? true : false);
		$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
		$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);
		$bbcode->is_sig = true;
		$user_sig = $bbcode->parse($user_sig);
		$bbcode->is_sig = false;
	}


	// Highlight active words (primarily for search)
	if ($highlight_match)
	{
		$shout = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace('#\b(" . str_replace('\\', '\\\\', addslashes($highlight_match)) . ")\b#i', '<span class=\"highlight-w\"><b>\\\\1</b></span>', '\\0')", '>' . $shout . '<'), 1, -1));
	}

	$user_sig = censor_text($user_sig);
	$shout = censor_text($shout);

	$bbcode->allow_html = ($config['allow_html'] ? true : false);
	$bbcode->allow_bbcode = ($config['allow_bbcode'] && $shout_row['enable_bbcode'] ? true : false);
	$bbcode->allow_smilies = ($config['allow_smilies'] && $shout != '' && $shout_row['enable_smilies'] ? true : false);

	$shout = $bbcode->parse($shout);
	//$shout = str_replace("\n", "\n<br />\n", $shout);

	$shout = ((!$shout_row['shout_active']) ? $shout : ($lang['Shout_censor'] . ($is_auth['auth_mod'] ? ('<br /><hr /><br />' . $shout) : '')));

	$shout = $bbcode->acronym_pass($shout);
	$shout = $bbcode->autolink_text($shout, '999999');

	if ($is_auth['auth_mod'] && $is_auth['auth_delete'])
	{
		$ip_url = append_sid('shoutbox_max.' . PHP_EXT . '?mode=ip&amp;' . POST_POST_URL . '=' . $shout_row['shout_id']);
		$ip_img = '<a href="' . $ip_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '" /></a>';
		$ip = '<a href="' . $ip_url . '">' . $lang['View_IP'] . '</a>';

		$delshout_url = append_sid('shoutbox_max.' . PHP_EXT . '?mode=delete&amp;' . POST_POST_URL . '=' . $shout_row['shout_id']);
		$delshout_img = '<a href="' . $delshout_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>&nbsp;';
		$delshout = '<a href="' . $delshout_url . '">' . $lang['Delete_post'] . '</a>';

		$censorshout_url = append_sid('shoutbox_max.' . PHP_EXT . '?mode=censor&amp;' . POST_POST_URL . '=' . $shout_row['shout_id']);
		$censorshout_img = '<a href="' . $censorshout_url . '"><img src="' . $images['icon_censor'] . '" alt="' . $lang['Censor'] . '" title="' . $lang['Censor'] . '" /></a>&nbsp;';
		$censorshout = '<a href="' . $censorshout_url . '">' . $lang['Delete_post'] . '</a>';
	}
	else
	{
		$ip_url = '';
		$ip_img = '';
		$ip = '';

		if ((($user->data['user_id'] == $user_id) && $is_auth['auth_delete']) && (($user->data['user_id'] != ANONYMOUS) || (($user->data['user_id'] == ANONYMOUS) && ($user->data['session_ip'] == $shout_row['shout_ip']))))
		{
			$censorshout_url = append_sid('shoutbox_max.' . PHP_EXT . '?mode=censor&amp;' . POST_POST_URL . '=' . $shout_row['shout_id']);
			$censorshout_img = '<a href="' . $censorshout_url . '"><img src="' . $images['icon_censor'] . '" alt="' . $lang['Censor'] . '" title="' . $lang['Censor'] . '" /></a>&nbsp;';
			$censorshout = '<a href="' . $censorshout_url . '">' . $lang['Delete_post'] . '</a>';
		}
		else
		{
			$delshout_url = '';
			$delshout_img = '';
			$delshout = '';

			$censorshout_url = '';
			$censorshout_img = '';
			$censorshout = '';
		}
	}

	$template->assign_block_vars('shoutrow', array(
		'ROW_CLASS' => $row_class,
		'SHOUT' => $shout,
		'TIME' => create_date_ip($config['default_dateformat'], $shout_row['shout_session_time'], $config['board_timezone']),
		'SHOUT_USERNAME' => $shout_username,
		'GENDER' => $gender,
		'AVATAR' => $user_avatar,
		'AVATAR_IMG' => $user_avatar_img,
		'USER_RANK' => $user_rank,
		'RANK_IMAGE' => $rank_image,
		'JOINED' => $user_joined,
		'POSTS' => $user_posts,
		'FROM' => $user_from,

		'IP_IMG' => $ip_img,
		'IP_URL' => $ip_url,
		'IP' => $ip,
		'DELETE_IMG' => $delshout_img,
		'DELETE_URL' => $delshout_url,
		'DELETE' => $delshout,
		'CENSOR_IMG' => $censorshout_img,
		'CENSOR_URL' => $censorshout_url,
		'CENSOR' => $censorshout,
		'U_VIEW_USER_PROFILE' => $user_profile,
		'U_SHOUT_ID' => $shout_row['shout_id']
		)
	);
}

// Show post options
if ($is_auth['auth_post'])
{
	$template->assign_block_vars('switch_auth_post', array());
}
else
{
	$template->assign_block_vars('switch_auth_no_post', array());
}

$template->assign_vars(array(
	'USERNAME' => $username,
	'NUMBER_OF_SHOUTS' => $total_shouts,
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
	'L_SHOUTBOX_LOGIN' => $lang['Login_join'],
	'L_POSTED' => $lang['Posted'],
	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'U_SHOUTBOX' => append_sid('shoutbox_max.' . PHP_EXT . '?start=' . $start),
	'T_NAME' => $theme['template_name'],
	'T_URL' => 'templates/' . $theme['template_name'],
	'L_SHOUTBOX' => $lang['Shoutbox'],
	'L_SHOUT_PREVIEW' => $lang['Preview'],
	'L_SHOUT_SUBMIT' => $lang['Go'],
	'L_SHOUT_TEXT' => $lang['Shout_text'],
	'L_SHOUT_REFRESH' => $lang['Shout_refresh'],
	'S_HIDDEN_FIELDS' => $s_hidden_fields,

	'L_CENSOR' => $lang['Censor'],
	'L_DELETE' => $lang['Delete_post'],
	'L_VIEW_IP' => $lang['View_IP'],

	'SMILIES_STATUS' => $smilies_status,
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],

	'L_DISABLE_HTML' => $lang['Disable_HTML_post'],
	'L_DISABLE_BBCODE' => $lang['Disable_BBCode_post'],
	'L_DISABLE_SMILIES' => $lang['Disable_Smilies_post'],

	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
	'L_STYLES_TIP' => $lang['Styles_tip'],
	'S_HTML_CHECKED' => (!$html_on) ? 'checked="checked"' : '',
	'S_BBCODE_CHECKED' => (!$bbcode_on) ? 'checked="checked"' : '',
	'S_SMILIES_CHECKED' => (!$smilies_on) ? 'checked="checked"' : ''
	)
);

if($error_msg != '')
{
	$template->set_filenames(array('reg_header' => 'error_body.tpl'));
	$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	$message = request_post_var('message', '', true);
	$template->assign_var('MESSAGE', $message);
}

// BBCBMG - BEGIN
include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
// BBCBMG - END
// BBCBMG SMILEYS - BEGIN
generate_smilies('inline');
include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
// BBCBMG SMILEYS - END

// Generate pagination for shoutbox view
$pagination = ($highlight_match) ? generate_pagination('shoutbox_max.' . PHP_EXT . '?highlight=' . $highlight, $total_shouts, $config['posts_per_page'], $start) : generate_pagination('shoutbox_max.' . PHP_EXT . '?dummy=1', $total_shouts, $config['posts_per_page'], $start);

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'NUMBER_OF_SHOUTS' => $total_shouts,
	)
);

full_page_generation($template_to_parse, $lang['Shoutbox'], '', '');

?>