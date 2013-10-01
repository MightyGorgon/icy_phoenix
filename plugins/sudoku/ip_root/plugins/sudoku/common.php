<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

define('SUDOKU_ROOT_PATH', IP_ROOT_PATH . $config['plugins'][$plugin_name]['dir']);
define('SUDOKU_TPL_PATH', '../../' . SUDOKU_ROOT_PATH . 'templates/');

$cms_page['page_id'] = $plugin_name;
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

define('SUDOKU_SESSIONS', $table_prefix . 'sudoku_sessions');
define('SUDOKU_SOLUTIONS', $table_prefix . 'sudoku_solutions');
define('SUDOKU_STARTS', $table_prefix . 'sudoku_starts');
define('SUDOKU_STATS', $table_prefix . 'sudoku_stats');
define('SUDOKU_USERS', $table_prefix . 'sudoku_users');

include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
$class_plugins = new class_plugins();
$class_plugins->setup_lang($config['plugins'][$plugin_name]['dir']);

include(SUDOKU_ROOT_PATH . 'includes/functions_sudoku.' . PHP_EXT);

?>