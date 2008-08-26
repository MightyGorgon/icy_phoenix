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

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['175_Yahoo_search'] = $filename;
	return;
}

$phpbb_root_path = './../';
$no_page_header = true;
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if ( ! strtolower(@ini_get('safe_mode')) )
{
	set_time_limit(180);
}

// Generate page
include('./page_header_admin.' . $phpEx);

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

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get configuration information', '', __LINE__, __FILE__, $sql);
}

while( $row = $db->sql_fetchrow($result) )
{
	$config_name = $row['config_name'];
	$config_value = $row['config_value'];

	$config[$config_name] = $config_value;
}

// Has the forum been submitted?
if ( isset($_POST['submit']) )
{
	$forums_sql = '';

	if ( isset($_POST['forums_select']) )
	{
		$forums_select = $_POST['forums_select'];

		for ( $i = 0; $i < count($forums_select); $i++ )
		{
			if ( $forums_select[$i] )
			{
				$forums_sql .= ( ( $forums_sql != '' ) ? ', ' : '' ) . intval($forums_select[$i]);
			}
		}
	}

	else
	{
		message_die(GENERAL_ERROR, $lang['Yahoo_search_error_no_forums']);
	}

	// Compress the file?
	if ( $_POST['compress_file'] )
	{
		$phpversion = phpversion();

		if ( $phpversion >= '4.0' && extension_loaded('zlib') )
		{
			$out = '';

			$sql = 'SELECT topic_id, topic_title
				FROM ' . TOPICS_TABLE . "
				WHERE forum_id IN ($forums_sql)
				ORDER BY topic_time DESC";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get list of topic IDs.', '', __LINE__, __FILE__, $sql);
			}

			while ( $row = $db->sql_fetchrow($result) )
			{
				$protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
				$server_name = $config['server_name'];
				$server_port = ( $config['server_port'] == '80' ) ? '' : ':' . $config['server_port'];
				$script_path = $config['script_path'];
				//URL REWRITE MOD START
				if ( ($board_config['url_rw'] == '1') || ( ($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS) ) )
				{
					$viewtopic_url = str_replace ('--', '-', make_url_friendly($row['topic_title']) . '-vt' . $row['topic_id'] . '.html');
				}
				else
				{
					$viewtopic_url = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];
				}
				//URL REWRITE MOD END
				//$viewtopic_url = 'viewtopic.' . $phpEx . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];

				$out .= $protocol . $server_name . $server_port . $script_path . $viewtopic_url . "\r\n";
			}

			$out .= ( $_POST['additional_urls'] ) ? trim($_POST['additional_urls']) : '';

			$filename = $phpbb_root_path . '/' . $_POST['search_savepath'] . '/urllist.txt.gz';

			if ( preg_match('#^[0-9]$#', $_POST['compression_level']) )
			{
				$compression_level = $_POST['compression_level'];
			}

			else
			{
				$compression_level = 9;
			}

			if ( !$file_handle = gzopen($filename, 'wb' . $compression_level ) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unopenable_file'], $filename) );
			}

			if ( !gzwrite($file_handle, $out) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unwritable_file'], $filename) );
			}

			if ( gzclose($file_handle) === FALSE )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unclosable_file'], $filename) );
			}

			// Update settings
			$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
				config_value = "' . str_replace("\'", "''", $_POST['search_savepath']) . '"
				WHERE config_name = "yahoo_search_savepath"';

			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_savepath') );
			}

			$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
				config_value = "' . str_replace("\'", "''", $_POST['additional_urls']) . '"
				WHERE config_name = "yahoo_search_additional_urls"';

			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_additional_urls') );
			}

			$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
				config_value = "' . str_replace("\'", "''", $_POST['compress_file']) . '"
				WHERE config_name = "yahoo_search_compress"';

			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_compress') );
			}

			$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
				config_value = "' . str_replace("\'", "''", $_POST['compression_level']) . '"
				WHERE config_name = "yahoo_search_compression_level"';

			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_compression_level') );
			}

			// It looks like everything worked okay....
			if ( file_exists($filename) && filesize($filename) > 1 )
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['Yahoo_search_file_done'], $protocol . $server_name . $server_port . $script_path . $_POST['search_savepath'] . '/urllist.txt.gz' ) );
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
		global $board_config;
		$sql = 'SELECT topic_id, topic_title
			FROM ' . TOPICS_TABLE . "
			WHERE forum_id IN ($forums_sql)
			ORDER BY topic_time DESC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get list of topic IDs.', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
			$server_name = $config['server_name'];
			$server_port = ( $config['server_port'] == '80' ) ? '' : ':' . $config['server_port'];
			$script_path = $config['script_path'];
			//URL REWRITE MOD START
			if ( ($board_config['url_rw'] == '1') || ( ($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS) ) )
			{
				$viewtopic_url = str_replace ('--', '-', make_url_friendly($row['topic_title']) . '-vt' . $row['topic_id'] . '.html');
			}
			else
			{
				$viewtopic_url = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];
			}
			//URL REWRITE MOD END

			//$viewtopic_url = 'viewtopic.' . $phpEx . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];

			$out .= $protocol . $server_name . $server_port . $script_path . $viewtopic_url . "\r\n";
		}

		$out .= ( $_POST['additional_urls'] ) ? trim($_POST['additional_urls']) : '';

		$filename = $phpbb_root_path . $_POST['search_savepath'] . '/urllist.txt';

		if ( !$file_handle = fopen($filename, 'w') )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unopenable_file'], $filename) );
		}

		if ( fwrite($file_handle, $out) === FALSE )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unwritable_file'], $filename) );
		}

		if ( fclose($file_handle) === FALSE )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_unclosable_file'], $filename) );
		}

		// Update settings
		$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
			config_value = "' . str_replace("\'", "''", $_POST['search_savepath']) . '"
			WHERE config_name = "yahoo_search_savepath"';

		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_savepath') );
		}

		$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
			config_value = "' . str_replace("\'", "''", $_POST['additional_urls']) . '"
			WHERE config_name = "yahoo_search_additional_urls"';

		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_additional_urls') );
		}

		$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
			config_value = "' . str_replace("\'", "''", $_POST['compress_file']) . '"
			WHERE config_name = "yahoo_search_compress"';

		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_compress') );
		}

		$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
			config_value = "' . str_replace("\'", "''", $_POST['compression_level']) . '"
			WHERE config_name = "yahoo_search_compression_level"';

		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['Yahoo_search_error_update_sql'], 'yahoo_search_compression_level') );
		}

		// It looks like everything worked okay....
		if ( file_exists($filename) && filesize($filename) > 1 )
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Yahoo_search_file_done'], $protocol . $server_name . $server_port . $script_path . $_POST['search_savepath'] . '/urllist.txt' ) );
		}

		// Maybe not
		else
		{
			message_die(GENERAL_ERROR, $lang['Yahoo_search_error_unknown_file_error']);
		}
	}
}

// Display the admin page
else
{
	$sql = 'SELECT c.cat_id, c.cat_title, c.cat_order
			FROM ' . CATEGORIES_TABLE . ' AS c, '. FORUMS_TABLE . ' AS f
			WHERE f.cat_id = c.cat_id
			GROUP BY c.cat_id, c.cat_title, c.cat_order
			ORDER BY c.cat_order';

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain category list.', '', __LINE__, __FILE__, $sql);
	}

	$category_rows = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$category_rows[] = $row;
	}

	if ( $total_categories = count($category_rows) )
	{
		$sql = 'SELECT *
			FROM ' . FORUMS_TABLE . '
			ORDER BY cat_id, forum_order';

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain forums information.', '', __LINE__, __FILE__, $sql);
		}

		$forums_select = '<select name="forums_select[]" multiple="multiple" size="10" style="width: 250px;">';

		$forum_rows = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$forum_rows[] = $row;
		}

		if ( $total_forums = count($forum_rows) )
		{
			for ( $i = 0; $i < $total_categories; $i++ )
			{
				$boxstring_forums = '';
				for ( $j = 0; $j < $total_forums; $j++ )
				{
					if ( $forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] )
					{
						if ( $forum_rows[$j]['auth_view'] == AUTH_ALL )
						{
							$boxstring_forums .=  '<option value="' . $forum_rows[$j]['forum_id'] . '" selected="selected">' . $forum_rows[$j]['forum_name'] . '</option>';
						}

						else
						{
							$boxstring_forums .=  '<option value="' . $forum_rows[$j]['forum_id'] . '">' . $forum_rows[$j]['forum_name'] . '</option>';
						}
					}
				}

				if ( $boxstring_forums != '' )
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

		'SEARCH_SAVEPATH' => $config['yahoo_search_savepath'],
		'ADDITIONAL_URLS' => $config['yahoo_search_additional_urls'],
		'S_USER_ACTION' => append_sid('admin_yahoo_search.' . $phpEx),
		'S_FORUMS_SELECT' => $forums_select,
		'S_COMPRESS_FILE_YES' => ( $config['yahoo_search_compress'] ) ? 'checked="checked"' : '',
		'S_COMPRESS_FILE_NO' => ( !$config['yahoo_search_compress'] ) ? 'checked="checked"' : '',
		'COMPRESSION_LEVEL' => $config['yahoo_search_compression_level'],
	));

	$template->pparse('body');
}

include('./page_footer_admin.' . $phpEx);

?>