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

// Prepend all Variables with '__' to prevent conflicts with Variables from included Variables.
$__stats_config = array();

$sql = 'SELECT * FROM ' . STATS_CONFIG_TABLE;
$result = $db->sql_query($sql, 0, 'stats_config_');

while ($row = $db->sql_fetchrow($result))
{
	$__stats_config[$row['config_name']] = trim($row['config_value']);
}

include(IP_ROOT_PATH . 'includes/functions_stats.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/class_stats_module.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

setup_extra_lang(array('lang_statistics'));

$cms_page['page_id'] = 'statistics';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$meta_content['page_title'] = $lang['Statistics_title'];
page_header($lang['Statistics_title'], true);
$template->set_filenames(array('body' => 'statistics.tpl'));

$__module_rows = get_module_list_from_db();
$__stat_module_data = get_module_data_from_db();
$return_limit = $__stats_config['return_limit'];

@reset($__module_rows);

$__stat_module_rows = array();
$__count = 0;

while (list($__module_id, $__module_name) = each($__module_rows))
{
	$__stat_module_rows[$__count]['module_id'] = $__module_id;
	$__stat_module_rows[$__count]['module_name'] = $__module_name;
	$__count++;
}

for ($__count = 0; $__count < sizeof($__stat_module_rows); $__count++)
{
	$__module_name = trim($__stat_module_rows[$__count]['module_name']);
	$__module_id = intval($__stat_module_rows[$__count]['module_id']);

	// Clear Template and Destroy Language Variables
	//$template->destroy();

	if (module_auth_check($__stat_module_data[$__module_id], $user->data))
	{
		print '<a name="s' . $__module_id . '"></a>';

		$__module_info = generate_module_info($__stat_module_data[$__module_id]);

		$__tpl_name = 'module_tpl_' . $__module_id;
		$__module_root_path = './../../' . IP_ROOT_PATH;
		$__module_data = $__stat_module_data[$__module_id];
		$mod_lang = 'module_language_parse';

		$__reload = false;

		if ((trim($__module_data['module_db_cache']) != '') || (trim($__module_data['module_result_cache']) != ''))
		{
			if (($__module_data['module_cache_time'] + ($__module_data['update_time'] * 60)) > time())
			{
				if (trim($__module_data['module_db_cache']) != '')
				{
					$statistics->db_cache_used = true;
					$stat_db->begin_cached_query(true, trim($__module_data['module_db_cache']));
				}

				if (trim($__module_data['module_result_cache']) != '')
				{
					$statistics->result_cache_used = true;
					$result_cache->begin_cached_results(true, trim($__module_data['module_result_cache']));
				}

				include(IP_ROOT_PATH . $__stats_config['modules_dir'] . '/' . $__module_name . '_module.' . PHP_EXT);

				if (trim($__module_data['module_db_cache']) != '')
				{
					$stat_db->end_cached_query($__module_id);
				}
				if (trim($__module_data['module_result_cache']) != '')
				{
					$result_cache->end_cached_query($__module_id);
				}
			}
			else
			{
				$__reload = true;
			}
		}
		else
		{
			$__reload = true;
		}

		if ($__reload)
		{
			$statistics->result_cache_used = false;
			$statistics->db_cache_used = false;

			$stat_db->begin_cached_query();
			$result_cache->begin_cached_results();
			include(IP_ROOT_PATH . $__stats_config['modules_dir'] . '/' . $__module_name . '_module.' . PHP_EXT);
			$stat_db->end_cached_query($__module_id);
			$result_cache->end_cached_query($__module_id);
		}

		$template->set_filenames(array($__tpl_name => STATS_TPL . $__module_info['dname'] . '.tpl'));

		$template->assign_vars(array(
			'GRAPH_IMAGE' => $images['voting_graphic_body'],
			'LEFT_GRAPH_IMAGE' => $images['voting_graphic_left'],
			'RIGHT_GRAPH_IMAGE' => $images['voting_graphic_right'],
			'R_GRAPH_IMAGE' => $images['voting_graphic_red_body'],
			'R_LEFT_GRAPH_IMAGE' => $images['voting_graphic_red_left'],
			'R_RIGHT_GRAPH_IMAGE' => $images['voting_graphic_red_right'],
			'G_GRAPH_IMAGE' => $images['voting_graphic_green_body'],
			'G_LEFT_GRAPH_IMAGE' => $images['voting_graphic_green_left'],
			'G_RIGHT_GRAPH_IMAGE' => $images['voting_graphic_green_right'],
			'B_GRAPH_IMAGE' => $images['voting_graphic_blue_body'],
			'B_LEFT_GRAPH_IMAGE' => $images['voting_graphic_blue_left'],
			'B_RIGHT_GRAPH_IMAGE' => $images['voting_graphic_blue_right'],
			)
		);

		$template->pparse($__tpl_name);

		// print '<br />';
	}
}

$sql = "UPDATE " . STATS_CONFIG_TABLE . "
SET config_value = " . (intval($__stats_config['page_views']) + 1) . "
WHERE (config_name = 'page_views')";
$db->sql_query($sql);

$template->assign_vars(array(
	'VIEWED_INFO' => sprintf($lang['Viewed_info'], $__stats_config['page_views']),
	'INSTALL_INFO' => sprintf($lang['Install_info'], create_date($config['default_dateformat'], $__stats_config['install_date'], $config['board_timezone'])),
	'VERSION_INFO' => (isset($lang['Version_info'])) ? sprintf($lang['Version_info'], $__stats_config['version']) : ''
	)
);

$template->assign_block_vars('main_bottom',array());

page_footer(true, 'body', true);

?>