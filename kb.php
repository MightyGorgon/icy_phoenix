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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

define('PAGE_KB', -500); // If this id generates a conflict with other mods, change it ;)

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '20';
$cms_page_name = 'kb';
$auth_level_req = $board_config['auth_view_kb'];
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
$cms_global_blocks = ($board_config['wide_blocks_kb'] == 1) ? true : false;

include($phpbb_root_path . 'includes/functions_post.' . $phpEx);
include($phpbb_root_path . 'includes/kb_constants.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb_auth.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb_field.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb_mx.' . $phpEx);
include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_search.' . $phpEx);



// Instanciate custom fields
$kb_custom_field = new kb_custom_field();
$kb_custom_field->init();

$show_new = true;

// page number

if (isset($_POST['page_num']) || isset($_GET['page_num']))
{
	$page_num = (isset($_POST['page_num'])) ? intval($_POST['page_num']) : intval($_GET['page_num']);
	$page_num = $page_num - 1;
}
else
{
	$page_num = 0;
}

// Print version
if (isset($_POST['print']) || isset($_GET['print']))
{
	$print_version = (isset($_POST['print'])) ? $_POST['print'] : $_GET['print'];
	$print_version = htmlspecialchars($print_version);
}
else
{
	$print_version = '';
}

// Pull all config data

$sql = "SELECT *
	FROM " . KB_CONFIG_TABLE;
if (!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in kb_config", "", __LINE__, __FILE__, $sql);
}
else
{
	while ($kb_config_row = $db->sql_fetchrow($result))
	{
		$config_name = $kb_config_row['config_name'];
		$config_value = $kb_config_row['config_value'];
		$kb_config[$config_name] = $config_value;
	}
}

$bbcode_on = $kb_config['allow_bbcode'] ? 1 : 0;
$html_on = $kb_config['allow_html'] ? 1 : 0;
$smilies_on = $kb_config['allow_smilies'] ? 1 : 0;
$is_admin = (($userdata['user_level'] == ADMIN) && $userdata['session_logged_in']) ? true : 0;

// mode

if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
	$mode = (htmlspecialchars($mode) != 'cat' || intval ($_GET['cat']) != 0) ? htmlspecialchars($mode) : '';
}

if (isset($_POST['stats']) || isset($_GET['stats']))
{
	$stats = (isset($_POST['stats'])) ? $_POST['stats'] : $_GET['stats'];
	$stats = htmlspecialchars($stats);
}

$reader_mode = false;

if ($mode == 'article')
{
	include($phpbb_root_path . 'includes/kb_article.' . $phpEx);
}
elseif ($mode == 'cat')
{
	include($phpbb_root_path . 'includes/kb_cat.' . $phpEx);
}
elseif ($mode == 'add')
{
	include($phpbb_root_path . 'includes/kb_post.' . $phpEx);
}
elseif ($mode == 'search')
{
	include($phpbb_root_path . 'includes/kb_search.' . $phpEx);
}
elseif ($mode == 'edit')
{
	include($phpbb_root_path . 'includes/kb_post.' . $phpEx);
}
elseif ($mode == 'rate')
{
	include($phpbb_root_path . 'includes/kb_rate.' . $phpEx);
}
elseif ($mode == 'stats')
{
	include($phpbb_root_path . 'includes/kb_stats.' . $phpEx);
}
elseif ($mode == 'moderate')
{
	include($phpbb_root_path . 'includes/kb_moderator.' . $phpEx);
}
else
{
	// DEFAULT ACTION
	$page_title = $lang['KB_title'];
	$meta_description = '';
	$meta_keywords = '';
	if (!$is_block)
	{
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);
	}
	// load header
	include($phpbb_root_path . 'includes/kb_header.' . $phpEx);

	$template->set_filenames(array('body' => 'kb_index_body.tpl'));

	$template->assign_vars(array(
		'L_CATEGORY' => $lang['Category'],
		'L_ARTICLES' => $lang['Articles']
		)
	);

	get_kb_cat_index();
}

$template->pparse('body');

// load footer
if (!$print_version)
{
	include($phpbb_root_path . 'includes/kb_footer.' . $phpEx);
}

if (!$is_block && !$print_version)
{
	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
}

?>