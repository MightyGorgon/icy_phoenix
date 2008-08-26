<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
$phpbb_root_path = './';
//$non_xs = true;
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Prepend all Variables with '__' to prevent conflicts with Variables from included Variables.
$__stats_config = array();

$sql = 'SELECT * FROM ' . STATS_CONFIG_TABLE;
if (!($result = $db->sql_query($sql, false, 'stats_')))
{
	message_die(GENERAL_ERROR, 'Could not query statistics config table', '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$__stats_config[$row['config_name']] = trim($row['config_value']);
}

include($phpbb_root_path . 'includes/functions_stats.' . $phpEx);
include($phpbb_root_path . 'includes/functions_module.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$language = $board_config['default_lang'];
if (!file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_statistics.' . $phpEx))
{
	$language = 'english';
}
include($phpbb_root_path . 'language/lang_' . $language . '/lang_statistics.' . $phpEx);

$cms_page_id = '14';
$cms_page_name = 'statistics';
$auth_level_req = $board_config['auth_view_statistics'];
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
$cms_global_blocks = ($board_config['wide_blocks_statistics'] == 1) ? true : false;

$page_title = $lang['Statistics_title'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

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

for ($__count = 0; $__count < count($__stat_module_rows); $__count++)
{
	$__module_name = trim($__stat_module_rows[$__count]['module_name']);
	$__module_id = intval($__stat_module_rows[$__count]['module_id']);

	// Clear Template and Destroy Language Variables
	//$template->destroy();

	if (module_auth_check($__stat_module_data[$__module_id], $userdata))
	{
		print '<a name="s' . $__module_id . '"></a>';

		$__module_info = generate_module_info($__stat_module_data[$__module_id]);

		$__tpl_name = 'module_tpl_' . $__module_id;
		$__module_root_path = './../../' . $phpbb_root_path;
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

				include($phpbb_root_path . $__stats_config['modules_dir'] . '/' . $__module_name . '/module.' . $phpEx);

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
			include($phpbb_root_path . $__stats_config['modules_dir'] . '/' . $__module_name . '/module.' . $phpEx);
			$stat_db->end_cached_query($__module_id);
			$result_cache->end_cached_query($__module_id);
		}

		$template->set_filenames(array($__tpl_name => $__module_root_path . $__stats_config['modules_dir'] . '/' . $__module_info['dname'] . '/module.tpl'));

		$template->assign_vars(array(
			'GRAPH_IMAGE' => $images['voting_graphic_body'],
			'LEFT_GRAPH_IMAGE' => $images['voting_graphic_left'],
			'RIGHT_GRAPH_IMAGE' => $images['voting_graphic_right'],
			)
		);

		$template->pparse($__tpl_name);

		print '<br />';
	}
}

$sql = "UPDATE " . STATS_CONFIG_TABLE . "
SET config_value = " . (intval($__stats_config['page_views']) + 1) . "
WHERE (config_name = 'page_views')";

if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Unable to Update View Counter', '', __LINE__, __FILE__, $sql);
}

$template->assign_vars(array(
	'VIEWED_INFO' => sprintf($lang['Viewed_info'], $__stats_config['page_views']),
	'INSTALL_INFO' => sprintf($lang['Install_info'], create_date($board_config['default_dateformat'], $__stats_config['install_date'], $board_config['board_timezone'])),
	'VERSION_INFO' => (isset($lang['Version_info'])) ? sprintf($lang['Version_info'], $__stats_config['version']) : ''
	)
);

$template->assign_block_vars('main_bottom',array());

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>