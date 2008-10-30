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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '20';
$cms_page_name = 'kb';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

include(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);



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
	include(IP_ROOT_PATH . 'includes/kb_article.' . PHP_EXT);
}
elseif ($mode == 'cat')
{
	include(IP_ROOT_PATH . 'includes/kb_cat.' . PHP_EXT);
}
elseif ($mode == 'add')
{
	include(IP_ROOT_PATH . 'includes/kb_post.' . PHP_EXT);
}
elseif ($mode == 'search')
{
	include(IP_ROOT_PATH . 'includes/kb_search.' . PHP_EXT);
}
elseif ($mode == 'edit')
{
	include(IP_ROOT_PATH . 'includes/kb_post.' . PHP_EXT);
}
elseif ($mode == 'rate')
{
	include(IP_ROOT_PATH . 'includes/kb_rate.' . PHP_EXT);
}
elseif ($mode == 'stats')
{
	include(IP_ROOT_PATH . 'includes/kb_stats.' . PHP_EXT);
}
elseif ($mode == 'moderate')
{
	include(IP_ROOT_PATH . 'includes/kb_moderator.' . PHP_EXT);
}
else
{
	// DEFAULT ACTION
	$page_title = $lang['KB_title'];
	$meta_description = '';
	$meta_keywords = '';
	if (!$is_block)
	{
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	}
	// load header
	include(IP_ROOT_PATH . 'includes/kb_header.' . PHP_EXT);

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
	include(IP_ROOT_PATH . 'includes/kb_footer.' . PHP_EXT);
}

if (!$is_block && !$print_version)
{
	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}

?>