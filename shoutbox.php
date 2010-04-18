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
define ('NUM_SHOUT', 20);

// Start session management
$userdata = session_pagestart($user_ip, false);
init_userprefs($userdata);
// End session management

$cms_page['page_id'] = 'shoutbox';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
// Force to false...
$cms_page['page_nav'] = false;
$cms_page['global_blocks'] = false;
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

// Start auth check
switch ($userdata['user_level'])
{
	case ADMIN :
	case MOD : $is_auth['auth_mod'] = 1;
	default:
		$is_auth['auth_read'] = 1;
		$is_auth['auth_view'] = 1;
		if ($userdata['user_id']==ANONYMOUS)
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
// End auth check

$refresh = (check_http_var_exists('auto_refresh', false) || check_http_var_exists('refresh', false)) ? 1 : 0;
$submit = (isset($_POST['shout']) && isset($_POST['message'])) ? 1 : 0;
$mode = request_var('mode', '');

// Set toggles for various options
if (!$config['allow_html'])
{
	$html_on = 0;
}
else
{
	$html_on = ($submit || $refresh || $preview) ? ((!empty($_POST['disable_html'])) ? 0 : 1) : (($userdata['user_id'] == ANONYMOUS) ? $config['allow_html'] : $userdata['user_allowhtml']);
}
if (!$config['allow_bbcode'])
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ($submit || $refresh || $preview) ? ((!empty($_POST['disable_bbcode'])) ? 0 : 1) : (($userdata['user_id'] == ANONYMOUS) ? $config['allow_bbcode'] : $userdata['user_allowbbcode']);
}

if (!$config['allow_smilies'])
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ($submit || $refresh || $preview) ? ((!empty($_POST['disable_smilies'])) ? 0 : 1) : (($userdata['user_id'] == ANONYMOUS) ? $config['allow_smilies'] : $userdata['user_allowsmile']);
	if ($smilies_on)
	{
		include(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
		generate_smilies('inline');
		if ($mode == 'smilies')
		{
			generate_smilies('window');
			exit;
		}
	}
}

if ($refresh)
{
	$message = request_post_var('message', '', true);
	if (!empty($message))
	{
		$template->assign_var('MESSAGE',$message);
	}
}
elseif ($submit || isset($_POST['message']))
{
	$current_time = time();
	// Flood control
	$where_sql = ($userdata['user_id'] == ANONYMOUS) ? "shout_ip = '$user_ip'" : 'shout_user_id = ' . $userdata['user_id'];
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
			if (($row['last_post_time'] > 0) && (($current_time - $row['last_post_time']) < $config['flood_interval']) && ($userdata['user_level'] != ADMIN))
			{
				$error = true;
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Flood_Error'] : $lang['Flood_Error'];
			}
		}
	}
	// Check username
	$username = $userdata['session_logged_in'] ? htmlspecialchars($userdata['username']) : request_post_var('username', '', true);
	if (!$userdata['session_logged_in'] && !empty($username))
	{
		include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
		$result = validate_username($username);
		if ($result['error'])
		{
			$error_msg .= (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];
		}
	}

	$message = request_post_var('message', '', true);
	$message = htmlspecialchars_decode($message, ENT_COMPAT);
	// insert shout !
	if (!empty($message) && $is_auth['auth_post'] && !$error)
	{
		include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
		$message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on);
		if ($config['img_shoutbox'] == true)
		{
			$message = preg_replace ("#\[url=(http://)([^ \"\n\r\t<]*)\]\[img\](http://)([^ \"\n\r\t<]*)\[/img\]\[/url\]#i", '[url=\\1\\2]\\4[/url]', $message);
			$message = preg_replace ("#\[img\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $message);
			$message = preg_replace ("#\[img align=left\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $message);
			$message = preg_replace ("#\[img align=right\](http://)([^ \"\n\r\t<]*)\[/img\]#i", '[url=\\1\\2]\\2[/url]', $message);
		}
		$sql = "INSERT INTO " . SHOUTBOX_TABLE . " (shout_text, shout_session_time, shout_user_id, shout_ip, shout_username, enable_bbcode, enable_html, enable_smilies)
				VALUES ('" . $db->sql_escape($message) . "', '" . time() . "', '" . $userdata['user_id'] . "', '$user_ip', '" . $db->sql_escape($username) . "', $bbcode_on, $html_on, $smilies_on)";
		$result = $db->sql_query($sql);

		// auto prune
		if ($config['prune_shouts'])
		{
			$sql = "DELETE FROM " . SHOUTBOX_TABLE . " WHERE shout_session_time<=" . (time() - (86400 * $config['prune_shouts']));
			$result = $db->sql_query($sql);
		}
	}
}

// see if we need offset
$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;
if ($submit)
{
	$start = 0;
}

// Show simple shoutbox
if ($is_auth['auth_post'])
{
	$template->assign_block_vars('switch_auth_post', array());
}
else
{
	$template->assign_block_vars('switch_auth_no_post', array());
}

if ($bbcode_on)
{
	$template->assign_block_vars('switch_auth_post.switch_bbcode', array());
}
$template->set_filenames(array('body' => 'shoutbox_body.tpl'));


$template->assign_vars(array(
	'U_SHOUTBOX' => append_sid('shoutbox.' . PHP_EXT . '?start=' . $start),
	'U_SHOUTBOX_VIEW' => append_sid('shoutbox_view.' . PHP_EXT . '?start=' . $start),
	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_NAME' => $theme['template_name'],

	'L_SHOUTBOX' => $lang['Shoutbox'],
	'L_SHOUT_PREVIEW' => $lang['Preview'],
	'L_SHOUT_SUBMIT' => $lang['Go'],
	'L_SHOUT_TEXT' => $lang['Shout_text'],
	'L_SHOUT_REFRESH' => $lang['Shout_refresh'],
	'L_SMILIES' => $lang['Smilies'],
	'T_URL' => 'templates/' . $theme['template_name'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
	'L_SHOUTBOX_LOGIN' => $lang['Login_join'],

	'SHOUT_VIEW_SIZE' => ($max) ? $max : 0,
	'S_HIDDEN_FIELDS' => $s_hidden_fields
	)
);

if($error_msg != '')
{
	$template->set_filenames(array('reg_header' => 'error_body.tpl'));
	$template->assign_vars(array('ERROR_MESSAGE' => $error_msg));
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	$message = request_var('message', '', true);
	$template->assign_var('MESSAGE', $message);
}

$template->pparse('body');

?>