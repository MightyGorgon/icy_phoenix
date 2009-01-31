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
* IdleVoid (idlevoid@slater.dk)
*
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
if (!defined('IN_ADMIN'))
{
	define('IN_ADMIN', true);
}

//------------------------------------------------------------------------
// setup the link to this phpbb ACP 'module'
//------------------------------------------------------------------------
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['2200_Photo_Album']['110_Album_Config'] = $filename;
	return;
}
//------------------------------------------------------------------------

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

// the language files....
require_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_album_main.' . PHP_EXT);
require_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_album_admin.' . PHP_EXT);

// the debugging functions and the actual ACP helper functions...
require_once(ALBUM_MOD_PATH . 'album_hierarchy_debug.' . PHP_EXT);
require_once(ALBUM_MOD_PATH . 'album_acp_functions.' . PHP_EXT);

//------------------------------------------------------------------------
// If you want to user an alternative template layout,
// then comment out the first define and remove the
// comment from the second one
// This will enable a virtical layout of the acp....
define('ALBUM_ACP_TEMPLATE', ADM_TPL . 'album_config_body_extended.tpl' );
//define('ALBUM_ACP_TEMPLATE', ADM_TPL . 'album_config_body_extended_vert.tpl');
//------------------------------------------------------------------------

// Mighty Gorgon - Clear Cache - Begin
if( isset($_POST['confirm_clear_cache']) )
{
	$cache_dir = @opendir('../' . ALBUM_CACHE_PATH);

	while( $cache_file = @readdir($cache_dir) )
	{
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
		{
			@unlink('../' . ALBUM_CACHE_PATH . $cache_file);
		}
	}

	@closedir($cache_dir);

	$cache_dir = @opendir('../' . ALBUM_MED_CACHE_PATH);

	while( $cache_file = @readdir($cache_dir) )
	{
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
		{
			@unlink('../' . ALBUM_MED_CACHE_PATH . $cache_file);
		}
	}

	@closedir($cache_dir);

	$cache_dir = @opendir('../' . ALBUM_WM_CACHE_PATH);

	while( $cache_file = @readdir($cache_dir) )
	{
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
		{
			@unlink('../' . ALBUM_WM_CACHE_PATH . $cache_file);
		}
	}

	@closedir($cache_dir);

	message_die(GENERAL_MESSAGE, $lang['Thumbnail_cache_cleared_successfully']);
}
// Mighty Gorgon - Clear Cache - End


//------------------------------------------------------------------------
// $valid_tab_selections will hold the list of valid tab selections
// $album_config_tabs will hold all the configuration data for the tabs (tab = one index in that array)
$valid_tab_selections = array();
$album_config_tabs = array();
//------------------------------------------------------------------------

//------------------------------------------------------------------------
// load each admin_album_config_*.php file..its the config files for the
// new album ACP.
//------------------------------------------------------------------------
$dir = @opendir(".");
$config_tabs_index = 0;
while( $config_file = @readdir($dir) )
{
	if( preg_match('/^admin_album_config_.*?\.' . PHP_EXT . '$/', $config_file) && strcmp("admin_album_config_extended." . PHP_EXT,$config_file) != 0)
	{
		//------------------------------------------------------------------------
		// include the configuration file
		//------------------------------------------------------------------------
		include($config_file);

		//------------------------------------------------------------------------
		// does the config file include a valid $album_config_tabs config ?
		//------------------------------------------------------------------------
		if (false == is_valid_config_tab($album_config_tabs[$config_tabs_index]))
		{
			//------------------------------------------------------------------------
			// remove the empty sub array completely, and get the next config file
			//------------------------------------------------------------------------
			$album_config_tabs = remove_config_array($album_config_tabs, $config_tabs_index);
			continue;
		}

		//------------------------------------------------------------------------
		// add the name of the actual configuration file. used in error message
		// and could be usefull for other uses too
		//------------------------------------------------------------------------
		$album_config_tabs[$config_tabs_index]['config_file_name'] = basename($config_file);

		//------------------------------------------------------------------------
		// build a list of valid tab selections, where the key in the
		// valid_selections is the actual selection key, value is just set to 0
		// NOTE : $album_config_tabs is included in each loaded config file!!!
		//------------------------------------------------------------------------
		if ( array_key_exists('selection', $album_config_tabs[$config_tabs_index]) )
		{
			$valid_subtab_selections = array();

			//------------------------------------------------------------------------
			// now find all the valid sub tabs for this valid tab
			// (anyone knowing what I'm talking about?)
			//------------------------------------------------------------------------
			for ($i = 0; $i < count($album_config_tabs[$config_tabs_index]['sub_config']); $i++ )
			{
				if ( array_key_exists('selection', $album_config_tabs[$config_tabs_index]['sub_config'][$i]) )
				{
					$valid_subtab_selections[ strval($album_config_tabs[$config_tabs_index]['sub_config'][$i]['selection']) ] = $i;
				}
			}

			$valid_tab_selections[ strval($album_config_tabs[$config_tabs_index]['selection'])] = $valid_subtab_selections;
		}

		$config_tabs_index++;
	}
}
@closedir($dir);

//------------------------------------------------------------------------
// sort the config tabs accordig to the 'order' setting in $album_config_tabs
//------------------------------------------------------------------------
usort($album_config_tabs, sort_cmp);

//album_enable_debug();
//album_debug('$album_config_tabs = %s', $album_config_tabs);
//album_enable_debug(false);

//------------------------------------------------------------------------
// get the selected tab selection from the submitted form
//------------------------------------------------------------------------
if (isset ($_POST['tab']))
{
	$selected_tab = strtolower($_POST['tab']);
}
elseif (isset ($_GET['tab']))
{
	$selected_tab = strtolower($_GET['tab']);
}

//------------------------------------------------------------------------
// get the selected sub tab selection from the submitted form
// NOTE : a sub tab, is a tab in the left or right side of a configuration
// for alittle hint see the template file ADM_TPL . 'album_config_sub_body.tpl'
//------------------------------------------------------------------------
if (isset ($_POST['subtab']))
{
	$selected_subtab = strtolower($_POST['subtab']);
}
elseif (isset ($_GET['subtab']))
{
	$selected_subtab = strtolower($_GET['subtab']);
}

//------------------------------------------------------------------------
// get the config table which the updated settings should be stored in
// the actual table name is not posted only the selection name of the tab
// the function 'get_config_table' will then get the album config table
// for the selected tab, meaning you can have different table for each tab
//
// We don't post the actual table name for security reasons !!!
//------------------------------------------------------------------------
if (isset ($_POST['config_table']))
{
	$config_table = get_config_table($_POST['config_table']);
}
elseif (isset ($_GET['config_table']))
{
	$config_table = get_config_table($_GET['config_table']);
}

//------------------------------------------------------------------------
// the $valid_selections array hols the list of valid selection, in it's
// key assigment so check if the selected tab can be found in that 'key'
// list if it can't then set the selected tab to the first in the sorted
// album config tabs (it will always exists)
//------------------------------------------------------------------------
if ( @!array_key_exists($selected_tab,$valid_tab_selections) )
{
	$selected_tab = $album_config_tabs[0]['selection'];
}

//------------------------------------------------------------------------
// check if a valid sub tab selection has been made
//------------------------------------------------------------------------
if (is_array($valid_tab_selections) && count($valid_tab_selections) > 0)
{
	if ( @!array_key_exists($selected_subtab,$valid_tab_selections[$selected_tab]) )
	{
		$tmp_array = array_flip($valid_tab_selections[$selected_tab]);

		// this code does a manual array_flip instead of usign the array_flip function
		// since it is only available since php4 and code should be php3 compatible
		/*
		$tmp_array = array();
		foreach( $valid_tab_selections[$selected_tab] as $key => $element )
		{
			$tmp_array[$element] = $key;
		}
		*/
		$selected_subtab = $tmp_array[0];

	}
}


//------------------------------------------------------------------------
// now build the selected config tab and if needed update the database
//------------------------------------------------------------------------

$template->set_filenames(array('body' => ALBUM_ACP_TEMPLATE));

//------------------------------------------------------------------------
// build the tab header list and  find the selected tab and setup the tab
// data, like template filename, generation function and langauge stuff
//------------------------------------------------------------------------
$selected_tab_data = array();
$selected_subtab_data = array();
for ($outer = 0; $outer < count($album_config_tabs); $outer++)
{
	$template->assign_block_vars('header_row', array(
			'TAB_SELECT_NAME' => $album_config_tabs[$outer]['selection'],
			'L_TAB_TITLE' => $album_config_tabs[$outer]['title'],
			'HEADER_TAB_CLASS' => (strcasecmp($selected_tab,$album_config_tabs[$outer]['selection']) != 0) ? 'tab_headers_unsel' : 'tab_headers',
			'TAB_LINKS' => (strcasecmp($selected_tab,$album_config_tabs[$outer]['selection']) != 0) ? 'tab_links_unsel' : 'tab_links'
		)
	);

	//------------------------------------------------------------------------
	// find the selected tab and gets the data for that tab; template file...
	//------------------------------------------------------------------------
	if ( strcasecmp($selected_tab, $album_config_tabs[$outer]['selection']) == 0)
	{
		//------------------------------------------------------------------------
		// now find the selected sub tab..if there are any sub tabs at all
		// and get the data for it; template file etc....
		//------------------------------------------------------------------------
		for ($inner = 0; $inner < count($album_config_tabs[$outer]['sub_config']); $inner++)
		{
			//------------------------------------------------------------------------
			// sort the sub tabs according to the order key in the array
			//------------------------------------------------------------------------
			usort($album_config_tabs[$outer]['sub_config'], sort_cmp);

			//------------------------------------------------------------------------
			// did we find the selected sub tab ?
			//------------------------------------------------------------------------
			if ( strcasecmp($selected_subtab, $album_config_tabs[$outer]['sub_config'][$inner]['selection']) == 0)
			{
				$selected_subtab_data = $album_config_tabs[$outer]['sub_config'][$inner];
			}
		}

		$selected_tab_data = $album_config_tabs[$outer];
		$selected_tab_data['selected_subtab'] = $selected_subtab_data;
	}
}

//------------------------------------------------------------------------
// if the configuration table isn't specified then halt and show error
//------------------------------------------------------------------------
if (empty($selected_tab_data['config_table_name']))
{
	$message = sprintf("No album configuration table was specified !!!<br />Please check your configuration setup in <b>%s</b>", $selected_tab_data['config_file_name']);
	message_die(CRITICAL_ERROR, $message , "", __LINE__, __FILE__);
}

//------------------------------------------------------------------------
// save the data from the requested tab (or tab that we are 'leaving')
//------------------------------------------------------------------------
if( strcmp($_POST['save_config'], 'true') == 0 )
{
	if (empty($config_table))
	{
		$config_table = $selected_tab_data['config_table_name'];
	}

	$sql = "SELECT * FROM " . $config_table;

	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, "Could not query album config information", "", __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $row['config_value'];

			$sql = "UPDATE " . $config_table . " SET
				config_value = '" . str_replace("\'", "''", $config_value) . "'
				WHERE config_name = '$config_name'";

			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update Album configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
		$db->sql_freeresult($result);

		//------------------------------------------------------------------------
		// did the user click 'submit' then display the standard confirmation page
		//------------------------------------------------------------------------
		if( isset($_POST['submitted']) )
		{
			if (isset($_POST['personal_gallery_view']))
			{
				$sql = "UPDATE " . ALBUM_CAT_TABLE . "
					SET cat_view_level = '" . $_POST['personal_gallery_view'] . "'
					WHERE cat_user_id != 0";

				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update personal gallery table', '', __LINE__, __FILE__, $sql);
				}
				//$db->sql_freeresult($result);
			}
			$message = $lang['Album_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_album_config'], '<a href="' . append_sid(basename(__FILE__) . '?tab=' . $selected_tab . '&amp;subtab=' . $selected_subtab) . '">', '</a>');
			$message .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}

		//------------------------------------------------------------------------
		// did she just change tab, when there was changes that needed to be saved ?
		// display a message telling her that the changes was saved successfully
		//------------------------------------------------------------------------
		else
		{
			$template->assign_block_vars('switch_on_save_confirmation', array());
			$saved_info_message = sprintf($lang['Save_sucessfully_confimation'], ucfirst(strtolower($selected_tab_data['title'])));
		}
	}
}

//------------------------------------------------------------------------
// load the configuration data from the database for the requested tab
//------------------------------------------------------------------------
$default_config = array();
$sql = "SELECT * FROM " . $selected_tab_data['config_table_name'];
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query album config information", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;

		$new[$config_name] = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $default_config[$config_name];
	}
	//$db->sql_freeresult($result);
}

//album_enable_debug();
//album_debug('$selected_tab_data = %s', $selected_tab_data);
//album_enable_debug(false);

//------------------------------------------------------------------------
// build/generate the actual configuation content (including sub configs)
//------------------------------------------------------------------------
build_config_box($selected_tab_data);

//------------------------------------------------------------------------
//  build the standard/common config page
//------------------------------------------------------------------------
$template->assign_vars(array(
	'HEADER_COL_SPAN' => count($album_config_tabs)+1,

	'L_ASK_SAVE_CHANGES' => $lang['acp_ask_save_changes'],
	'L_NOTHING_TO_SAVE' => $lang['acp_nothing_to_save'],
	'L_SETTINGS_CHANGED_ASK_SAVE' => $lang['acp_settings_changed_ask_save'],

	'H_SELECTED_TAB' => $selected_tab,
	'V_SELECTED_TAB' => $selected_subtab,
	'CONFIG_TABLE' => $selected_tab_data['selection'],
	'S_ALBUM_CONFIG_ACTION' => append_sid('admin_album_config_extended.' . PHP_EXT),

	'L_CONFIG_TAB' => $selected_tab_data['title'],
	'L_ALBUM_CONFIG' => $lang['Album_config'],
	'L_ALBUM_CONFIG_NOTICE' => $lang['Album_config_notice'],
	'L_ALBUM_CONFIG_EXPLAIN' => $lang['Album_config_explain'],
	'L_ALBUM_CONFIG_EXPLAIN_DETAIL' => $selected_tab_data['detail'],

	'L_SETTINGS_SAVED' => $saved_info_message,

	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'])
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>