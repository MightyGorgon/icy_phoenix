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

global $do_gzip_compress;

// Footer tpl
if (defined('IN_CMS'))
{
	$board_config['cms_style'] = (!empty($_GET['cms_style']) ? ((intval($_GET['cms_style']) == 1) ? 1 : 0) : $board_config['cms_style']);
	$cms_style = ($board_config['cms_style'] == 1) ? '' : '_std';
	$footer_tpl = CMS_TPL . 'page_footer' . $cms_style . '.tpl';
}
elseif (empty($gen_simple_header))
{
	$footer_tpl = 'overall_footer.tpl';
}
else
{
	$footer_tpl = 'simple_footer.tpl';
}

if(empty($gen_simple_header) && (!defined('HAS_DIED')) && (!defined('IN_LOGIN')) && (($cms_global_blocks == true) || !empty($cms_page_id)) && (($board_config['board_disable'] == false) || ($userdata['user_level'] == ADMIN)))
{
	$template->assign_var('SWITCH_CMS_GLOBAL_BLOCKS', true);
	if (cms_parse_blocks($cms_page_id, !empty($cms_page_id), $cms_global_blocks, 'tailcenter'))
	{
		$template->assign_var('TC_BLOCK', true);
	}
	if (cms_parse_blocks($cms_page_id, !empty($cms_page_id), $cms_global_blocks, 'tailright'))
	{
		$template->assign_vars(array(
			'FOOTER_WIDTH' => $cms_config_vars['footer_width'],
			'TR_BLOCK' => true,
			)
		);
	}
	cms_parse_blocks($cms_page_id, !empty($cms_page_id), $cms_global_blocks, 'tail');
	/*
	*/
}

$template->set_filenames(array('overall_footer' => $footer_tpl));

if ($board_config['switch_footer_table'] == true)
{
	$template->assign_block_vars('switch_footer_table', array(
		'FOOTER_TEXT' => $board_config['footer_table_text'],
		'L_STAFF_MESSAGE' => $lang['staff_message'],
		)
	);
}

if ($board_config['switch_bottom_html_block'] == true)
{
	$bottom_html_block_text = $board_config['bottom_html_block_text'];
}
else
{
	$bottom_html_block_text = '';
}

include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
$admin_link = jr_admin_make_admin_link();

// CrackerTracker v5.x
include_once(IP_ROOT_PATH . 'ctracker/engines/ct_footer.' . PHP_EXT);
$output_login_status = ($userdata['ct_enable_ip_warn'] ? $lang['ctracker_ma_on'] : $lang['ctracker_ma_off']);
// CrackerTracker v5.x

//Begin Lo-Fi Mod
$path_parts = pathinfo($_SERVER['PHP_SELF']);
$lofi = '<a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . '?' . htmlspecialchars($_SERVER['QUERY_STRING']) . '&amp;lofi=' . (empty($_COOKIE['lofi']) ? '1' : '0')) . '">' . (empty($_COOKIE['lofi']) ? ($lang['Lofi']) : ($lang['Full_Version'])) . '</a><br />';
$template->assign_vars(array(
	'L_LOFI' => $lang['Lofi'],
	'L_FULL_VERSION' => $lang['Full_Version'],
	'LOFI' => $lofi
	)
);
//End Lo-Fi Mod

$template->assign_vars(array(
	'TRANSLATION_INFO' => ((isset($lang['TRANSLATION_INFO'])) && ($lang['TRANSLATION_INFO'] != '')) ? ('<br />&nbsp;' . $lang['TRANSLATION_INFO']) : (((isset($lang['TRANSLATION'])) && ($lang['TRANSLATION'] != '')) ? ('<br />&nbsp;' . $lang['TRANSLATION']) : ''),

	// CrackerTracker v5.x
	'CRACKER_TRACKER_FOOTER' => create_footer_layout($ctracker_config->settings['footer_layout']),
	'L_STATUS_LOGIN' => ($ctracker_config->settings['login_ip_check'] ? sprintf($lang['ctracker_ipwarn_info'], $output_login_status) : ''),
	// CrackerTracker v5.x

	'CMS_ACP' => ($cms_acp_url == '') ? '' : $cms_acp_url,
	'ADMIN_LINK' => $admin_link
	)
);

if ($board_config['page_gen'] == 1)
{
	// Page generation time for Admin - BEGIN
	/* Un-comment the line below to restrict Admins only to view page generation info */
	//if($userdata['user_level'] == ADMIN)
	//{
	$gzip_text = ($board_config['gzip_compress']) ? 'GZIP ' . $lang['Enabled']: 'GZIP ' . $lang['Disabled'];
	$debug_text = (DEBUG == true) ? $lang['Debug_On'] : $lang['Debug_Off'];
	$memory_usage_text = '';
	$excuted_queries = $db->num_queries;
	$mtime = microtime();
	$mtime = explode(" ", $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$gentime = round(($endtime - $starttime), 4); // You can adjust the number 6
	$sql_time = round($db->sql_time, 4);

	$sql_part = round($sql_time / $gentime * 100);
	$php_part = 100 - $sql_part;

	// Mighty Gorgon - Extra Debug - BEGIN
	if (defined('DEBUG_EXTRA') && ($userdata['user_level'] == ADMIN))
	{
		if (function_exists('memory_get_usage'))
		{
			if ($memory_usage = memory_get_usage())
			{
				global $base_memory_usage;
				$memory_usage -= $base_memory_usage;
				$memory_usage = ($memory_usage >= 1048576) ? round((round($memory_usage / 1048576 * 100) / 100), 2) . ' ' . 'MB' : (($memory_usage >= 1024) ? round((round($memory_usage / 1024 * 100) / 100), 2) . ' ' . 'KB' : $memory_usage . ' ' . 'BYTES');
				$memory_usage_text = ' - ' . $lang['Memory_Usage'] . ': ' . $memory_usage;
			}
		}
		if (defined('DEBUG_EXTRA'))
		{
			$tmp_query_string = htmlspecialchars(str_replace(array('&explain=1', 'explain=1'), array('', ''), $_SERVER['QUERY_STRING']));
			$gzip_text .= ' - <a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . (!empty($tmp_query_string) ? ('?' . $tmp_query_string . '&amp;explain=1') : '?explain=1')) . '">Extra ' . $lang['Debug_On'] . '</a>';
		}
	}

	//if (defined('DEBUG_EXTRA') && ($userdata['user_level'] == ADMIN))
	if (defined('DEBUG_EXTRA') && !empty($_REQUEST['explain']) && ($userdata['user_level'] == ADMIN) && method_exists($db, 'sql_report'))
	{
		$db->sql_report('display');
	}
	// Mighty Gorgon - Extra Debug - END

	$template->assign_vars(array(
		'SPACER' => $images['spacer'],
		'PAGE_GEN_TIME' => $lang['Page_Generation_Time'] . ':',
		'GENERATION_TIME' => $gentime,
		'NUMBER_QUERIES' => $excuted_queries,
		'MEMORY_USAGE' => $memory_usage_text,
		'GZIP_TEXT' => $gzip_text,
		'SQL_QUERIES' => $lang['SQL_Queries'],
		'SQL_PART' => $sql_part,
		'PHP_PART' => $php_part,
		'OVERALL_QUERIES_TODAY' => $show_in_footer_today,
		'OVERALL_QUERIES_TOP' => $show_in_footer_top,
		'OVERALL_PAGES_VIEWED' => $spvt,
		'DEBUG_TEXT' => $debug_text,
		'BOTTOM_HTML_BLOCK' => $bottom_html_block_text,
		)
	);

	$template->assign_block_vars('generation_time_switch', array());

	/*
	$gen_log_file = IP_ROOT_PATH . 'cache/gen_log.txt';
	$fp = fopen ($gen_log_file, "a+");
	fwrite($fp, $gentime . "\t" . $memory_usage . "\n");
	fclose($fp);
	*/

	//}
	// Page generation time for Admin - END
}

$template->pparse('overall_footer');

// Close our DB connection.
if (!empty($db))
{
	$db->sql_close();
}

// Compress buffered output if required and send to browser

// URL Rewrite - BEGIN
if (($board_config['url_rw'] == true) || (($board_config['url_rw_guests'] == true) && ($userdata['user_id'] == ANONYMOUS)))
{
	$contents = rewrite_urls(ob_get_contents());
}
else
{
	$contents = ob_get_contents();
}

if(function_exists(ob_gzhandler) && ($board_config['gzip_compress'] == true))
{
	ob_end_clean();
	ob_start('ob_gzhandler');
	echo $contents;
	ob_end_flush();
}
else
{
	ob_end_clean();
	echo $contents;
}
// URL Rewrite - END

exit;

?>