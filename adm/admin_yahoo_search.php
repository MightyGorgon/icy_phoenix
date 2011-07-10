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
* Jeremy Conley - (pentapenguin@bluebottle.com) - (www.pentapenguin.com)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['195_Yahoo_search'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);

if (! strtolower(@ini_get('safe_mode')))
{
	set_time_limit(180);
}

// Generate page
include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);

$template->set_filenames(array('body' => ADM_TPL . 'yahoo_search_body.tpl'));

// Pull common config settings
$sql = 'SELECT config_name, config_value
	FROM ' . CONFIG_TABLE . '
	WHERE config_name = "yahoo_search_savepath"
	OR config_name = "yahoo_search_additional_urls"
	OR config_name = "yahoo_search_compress"
	OR config_name = "yahoo_search_compression_level"
	OR config_name = "cookie_secure"
	OR config_name = "server_name"
	OR config_name = "server_port"
	OR config_name = "script_path"';
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result))
{
	$config_name = $row['config_name'];
	$config_value = $row['config_value'];

	$default_config[$config_name] = $config_value;
}

// Has the forum been submitted?
if (isset($_POST['submit']))
{
	$forums_sql = '';

	if (isset($_POST['forums_select']))
	{
		$forums_select = $_POST['forums_select'];

		for ($i = 0; $i < sizeof($forums_select); $i++)
		{
			if ($forums_select[$i])
			{
				$forums_sql .= (($forums_sql != '') ? ', ' : '') . intval($forums_select[$i]);
			}
		}
	}

	else
	{
		message_die(GENERAL_ERROR, $lang['Yahoo_search_error_no_forums']);
	}

	// Compress the file?
	if ($_POST['compress_file'])
	{
		$phpversion = phpversion();

		if ($phpversion >= '4.0' && extension_loaded('zlib'))
		{
			$out = '';

			$sql = 'SELECT topic_id, topic_title
				FROM ' . TOPICS_TABLE . "
				WHERE forum_id IN ($forums_sql)
				ORDER BY topic_time DESC";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$protocol = ($default_config['cookie_secure']) ? 'https://' : 'http://';
				$server_name = $default_config['server_name'];
				$server_port = ($default_config['server_port'] == '80') ? '' : ':' . $default_config['server_port'];
				$script_path = $default_config['script_path'];
				//URL REWRITE MOD START
				if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
				{
					$viewtopic_url = str_replace ('--', '-', make_url_friendly($row['topic_title']) . '-vt' . $row['topic_id'] . '.html');
				}
				else
				{
					$viewtopic_url = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];
				}
				//URL REWRITE MOD END
				//$viewtopic_url = 'viewtopic.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];

				$out .= $protocol . $server_name . $server_port . $script_path . $viewtopic_url . "\r\n";
			}

			$out .= request_var('additional_urls', '', true);
			$search_savepath = request_var('search_savepath', '', true);

			$filename = IP_ROOT_PATH . '/' . $search_savepath . '/urllist.txt.gz';

			if (preg_match('#^[0-9]$#', $_POST['compression_level']))
			{
				$compression_level = $_POST['compression_level'];
			}
			else
			{
				$compression_level = 9;
			}

			if (!$file_handle = gzopen($filename, 'wb' . $compression_level))
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unopenable_file'], $filename));
			}

			if (!gzwrite($file_handle, $out))
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unwritable_file'], $filename));
			}

			if (gzclose($file_handle) === FALSE)
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unclosable_file'], $filename));
			}

			// Update settings
			set_config('yahoo_search_savepath', request_post_var('search_savepath', '', true), false);
			set_config('yahoo_search_additional_urls', request_post_var('additional_urls', '', true), false);
			set_config('yahoo_search_compress', request_post_var('compress_file', '', true), false);
			set_config('yahoo_search_compression_level', request_post_var('compression_level', '', true), false);

			// It looks like everything worked okay....
			if (file_exists($filename) && filesize($filename) > 1)
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['Yahoo_search_file_done'], $protocol . $server_name . $server_port . $script_path . $search_savepath . '/urllist.txt.gz'));
			}

			// Maybe not
			else
			{
				message_die(GENERAL_ERROR, $lang['Yahoo_search_error_unknown_file_error']);
			}
		}

		else
		{
			message_die(GENERAL_ERROR, $lang['Yahoo_search_error_no_gzip']);
		}
	}

	else
	{
		$out = '';
		global $config;
		$sql = 'SELECT topic_id, topic_title
			FROM ' . TOPICS_TABLE . "
			WHERE forum_id IN ($forums_sql)
			ORDER BY topic_time DESC";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$protocol = ($default_config['cookie_secure']) ? 'https://' : 'http://';
			$server_name = $default_config['server_name'];
			$server_port = ($default_config['server_port'] == '80') ? '' : ':' . $default_config['server_port'];
			$script_path = $default_config['script_path'];
			//URL REWRITE MOD START
			if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
			{
				$viewtopic_url = str_replace ('--', '-', make_url_friendly($row['topic_title']) . '-vt' . $row['topic_id'] . '.html');
			}
			else
			{
				$viewtopic_url = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];
			}
			//URL REWRITE MOD END

			//$viewtopic_url = 'viewtopic.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];

			$out .= $protocol . $server_name . $server_port . $script_path . $viewtopic_url . "\r\n";
		}

		$out .= request_var('additional_urls', '', true);
		$search_savepath = request_var('search_savepath', '', true);

		$filename = IP_ROOT_PATH . $search_savepath . '/urllist.txt';

		if (!$file_handle = fopen($filename, 'w'))
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unopenable_file'], $filename));
		}

		if (fwrite($file_handle, $out) === FALSE)
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unwritable_file'], $filename));
		}

		if (fclose($file_handle) === false)
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unclosable_file'], $filename));
		}

		// Update settings
		set_config('yahoo_search_savepath', request_post_var('search_savepath', '', true), false);
		set_config('yahoo_search_additional_urls', request_post_var('additional_urls', '', true), false);
		set_config('yahoo_search_compress', request_post_var('compress_file', '', true), false);
		set_config('yahoo_search_compression_level', request_post_var('compression_level', '', true), false);

		// It looks like everything worked okay....
		if (file_exists($filename) && filesize($filename) > 1)
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Yahoo_search_file_done'], $protocol . $server_name . $server_port . $script_path . $search_savepath . '/urllist.txt'));
		}

		// Maybe not
		else
		{
			message_die(GENERAL_ERROR, $lang['Yahoo_search_error_unknown_file_error']);
		}
	}
}
else
{
	// Display the admin page
	$sql = 'SELECT c.forum_id AS cat_id, c.forum_name AS cat_title, c.forum_order AS cat_order
			FROM ' . FORUMS_TABLE . ' c
			WHERE c.forum_type = ' . FORUM_CAT . '
			ORDER BY c.forum_order';
	$result = $db->sql_query($sql);

	$category_rows = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$category_rows[] = $row;
	}

	if ($total_categories = sizeof($category_rows))
	{
		$sql = 'SELECT *
			FROM ' . FORUMS_TABLE . ' c
			WHERE c.forum_type <> ' . FORUM_CAT . '
			ORDER BY c.forum_order';
		$result = $db->sql_query($sql);
		$forums_select = '<select name="forums_select[]" multiple="multiple" size="10" style="width: 250px;">';

		$forum_rows = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$forum_rows[] = $row;
		}

		if ($total_forums = sizeof($forum_rows))
		{
			for ($i = 0; $i < $total_categories; $i++)
			{
				$boxstring_forums = '';
				for ($j = 0; $j < $total_forums; $j++)
				{
					if ($forum_rows[$j]['parent_id'] == $category_rows[$i]['cat_id'])
					{
						if ($forum_rows[$j]['auth_view'] == AUTH_ALL)
						{
							$boxstring_forums .=  '<option value="' . $forum_rows[$j]['forum_id'] . '" selected="selected">' . $forum_rows[$j]['forum_name'] . '</option>';
						}
						else
						{
							$boxstring_forums .=  '<option value="' . $forum_rows[$j]['forum_id'] . '">' . $forum_rows[$j]['forum_name'] . '</option>';
						}
					}
				}

				if ($boxstring_forums != '')
				{
					$forums_select .= '<optgroup label="' . $category_rows[$i]['cat_title'] . '">';
					$forums_select .= $boxstring_forums;
					$forums_select .= '</optgroup>';
				}
			}
		}

		$forums_select .= '</select>';
	}

	$template->assign_vars(array(
		'L_YAHOO_SEARCH' => $lang['Yahoo_search'],
		'L_YAHOO_SEARCH_SETTINGS' => $lang['Yahoo_search_settings'],
		'L_YAHOO_SEARCH_SETTINGS_EXPLAIN' => $lang['Yahoo_search_settings_explain'],
		'L_YAHOO_SEARCH_SELECT_FORUMS' => $lang['Yahoo_search_select_forums'],
		'L_YAHOO_SEARCH_SELECT_FORUMS_EXPLAIN' => $lang['Yahoo_search_select_forums_explain'],
		'L_YAHOO_SEARCH_SAVEPATH' => $lang['Yahoo_search_savepath'],
		'L_YAHOO_SEARCH_SAVEPATH_EXPLAIN' => $lang['Yahoo_search_savepath_explain'],
		'L_YAHOO_SEARCH_ADDITIONAL_URLS' => $lang['Yahoo_search_additional_urls'],
		'L_YAHOO_SEARCH_ADDITIONAL_URLS_EXPLAIN' => $lang['Yahoo_search_additional_urls_explain'],
		'L_YAHOO_SEARCH_COMPRESS_FILE' => $lang['Yahoo_search_compress_file'],
		'L_YAHOO_SEARCH_COMPRESS_FILE_EXPLAIN' => $lang['Yahoo_search_compress_file_explain'],
		'L_YAHOO_SEARCH_COMPRESSION_LEVEL' => $lang['Yahoo_search_compression_level'],
		'L_YAHOO_SEARCH_COMPRESSION_LEVEL_EXPLAIN' => $lang['Yahoo_search_compression_level_explain'],
		'L_YAHOO_SEARCH_GENERATE_FILE' => $lang['Yahoo_search_generate_file'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'SEARCH_SAVEPATH' => $default_config['yahoo_search_savepath'],
		'ADDITIONAL_URLS' => $default_config['yahoo_search_additional_urls'],
		'S_USER_ACTION' => append_sid('admin_yahoo_search.' . PHP_EXT),
		'S_FORUMS_SELECT' => $forums_select,
		'S_COMPRESS_FILE_YES' => ($default_config['yahoo_search_compress']) ? 'checked="checked"' : '',
		'S_COMPRESS_FILE_NO' => (!$default_config['yahoo_search_compress']) ? 'checked="checked"' : '',
		'COMPRESSION_LEVEL' => $default_config['yahoo_search_compression_level'],
		)
	);

	$template->pparse('body');
}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>