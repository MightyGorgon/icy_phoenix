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

$cms_page_id = 'shoutbox';
$cms_page_nav = (!empty($cms_config_layouts[$cms_page_id]['page_nav']) ? true : false);
$cms_global_blocks = (!empty($cms_config_layouts[$cms_page_id]['global_blocks']) ? true : false);
// Force to false...
$cms_page_nav = false;
$cms_global_blocks = false;
$cms_auth_level = (isset($cms_config_layouts[$cms_page_id]['view']) ? $cms_config_layouts[$cms_page_id]['view'] : AUTH_ALL);
check_page_auth($cms_page_id, $cms_auth_level);

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

// see if we need offset
if (isset($_POST['start']) || isset($_GET['start']))
{
	$start = (isset($_POST['start'])) ? intval($_POST['start']) : intval($_GET['start']);
	$start = ($start < 0) ? 0 : $start;
}
else
{
	$start = 0;
}

$template->set_filenames(array('body' => 'shoutbox_view_body.tpl'));

// Define censored word matches
if (!$userdata['user_allowswearywords'])
{
	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}

// display the shoutbox
$sql = "SELECT s.*, u.user_allowsmile, u.username, u.user_id, u.user_active, u.user_color
		FROM " . SHOUTBOX_TABLE . " s, ".USERS_TABLE." u
		WHERE s.shout_user_id = u.user_id
		ORDER BY s.shout_session_time DESC
		LIMIT $start, ".NUM_SHOUT;
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not get shoutbox information', '', __LINE__, __FILE__, $sql);
}
while ($shout_row = $db->sql_fetchrow($result))
{
	$i++;
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
	$user_id = $shout_row['shout_user_id'];
	$username = ($user_id == ANONYMOUS) ? (($shout_row['shout_username'] == '') ? $lang['Guest'] : $shout_row['shout_username']) : colorize_username($shout_row['user_id'], $shout_row['username'], $shout_row['user_color'], $shout_row['user_active'], true);
	$shout = (!$shout_row['shout_active']) ? $shout_row['shout_text'] : $lang['Shout_censor'];
	$bbcode->allow_html = ($board_config['allow_html'] ? true : false);
	$bbcode->allow_bbcode = ($board_config['allow_bbcode'] && $shout_row['enable_bbcode'] ? true : false);
	$bbcode->allow_smilies = ($board_config['allow_smilies'] && $shout_row['user_allowsmile'] && ($shout != '') && $shout_row['enable_smilies'] ? true : false);
	$shout = $bbcode->parse($shout);
	$shout = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, $shout) : $shout;
	//$shout = str_replace("\n", "\n<br />\n", $shout);
	$shout = (preg_match("/<a/", $shout)) ? str_replace("\">" , "\" target=\"_top\">", $shout) : $shout;
	$orig_autolink = array();
	$replacement_autolink = array();
	obtain_autolink_list($orig_autolink, $replacement_autolink, 99999999);
	$shout = $bbcode->acronym_pass($shout);
	if(count($orig_autolink))
	{
		$shout = autolink_transform($shout, $orig_autolink, $replacement_autolink);
	}
	//$shout = kb_word_wrap_pass($shout);
	$template->assign_block_vars('shoutrow', array(
		'ROW_CLASS' => $row_class,
		'SHOUT' => $shout,
		'TIME' => create_date2($lang['Shoutbox_date'], $shout_row['shout_session_time'], $board_config['board_timezone']),
		'USERNAME' => $username
		)
	);
}
$template->assign_vars(array(
	'U_SHOUTBOX_VIEW' => append_sid('shoutbox_view.' . PHP_EXT . '?' . $start),
	'T_NAME' => $theme['template_name'],
	'T_URL' => 'templates/' . $theme['template_name'],
	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'S_CONTENT_ENCODING' => $lang['ENCODING']
	)
);

$template->pparse('body');

?>