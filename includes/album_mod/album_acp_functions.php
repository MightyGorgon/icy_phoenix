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
* Smartor (smartor_xp@hotmail.com)
* IdleVoid (idlevoid@slater.dk)
* Volodymyr (CLowN) Skoryk (blaatimmy72@yahoo.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('IN_ADMIN'))
{
	die('Can only be used from the album ACP');
}

//------------------------------------------------------------------------
// This file is included in the admin/admin_album_config_extended.php
// which is the new ACP for the ablum.
//
// The file contains all the helper functions nedded for the ACP
//------------------------------------------------------------------------

//------------------------------------------------------------------------
// this little helper function basicly does the template generation
// NOTE : the $config_box_generation_function is defined in the
// $album_config_tabs array that exists in all of the admin_album_config_*
// files. It is just a function 'pointer' to the actual generator function
//------------------------------------------------------------------------
function build_config_box($config_data)
{
	global $template;
	$function = $config_data['generate_function'];

	$template->set_filenames(array('configuration_box' => $config_data['template_file']));

	build_subtab_box($config_data);

	if (!empty($function))
	{
		$function($config_data);
	}

	$template->assign_var_from_handle('CONFIGURATION_BOX', 'configuration_box');
}

function build_sub_config_box($config_data)
{
	global $template;

	$selected_subtab = $config_data['selected_subtab'];

	$function = (!empty($selected_subtab['generate_function'])) ? $selected_subtab['generate_function'] : $config_data['generate_function'];

	$template->set_filenames(array('sub_configuration_box' => $selected_subtab['template_file']));

	if (!empty($function))
	{
		$function($config_data);
	}

	$template->assign_var_from_handle('SUB_CONFIGURATION_BOX', 'sub_configuration_box');
}

function build_subtab_box($config_data) //, $selected_subtab)
{
	global $template;
	$selected_index = 0;

	$selected_subtab = get_selected_tab_from_config($config_data);

	if (0 == sizeof($selected_subtab))
	{
		return;
	}

	build_sub_config_box($config_data);

	for ($i = 0; $i < sizeof($config_data['sub_config']); $i++)
	{
		if ($config_data['sub_config'][$i]['selection'] == $selected_subtab['selection'])
		{
			$selected_index = $i;
		}

		$template->assign_block_vars('subtab_row', array(
			'TAB_SELECT_NAME' => $config_data['sub_config'][$i]['selection'],
			'L_TAB_TITLE' => $config_data['sub_config'][$i]['title'],
			'TAB_LINKS' => ($config_data['sub_config'][$i]['selection'] == $selected_subtab['selection']) ? 'tab_links' : 'tab_links_unsel',
			'TAB_CLASS' => ($config_data['sub_config'][$i]['selection'] == $selected_subtab['selection']) ? 'tab_headers' : 'tab_headers_unsel'
			)
		);

		$template->assign_vars(array(
			'L_CONFIGURATION_BOX' => $config_data['sub_config'][$selected_index]['title']
			)
		);
	}

}

function get_selected_tab_from_config($config_data)
{
	return $config_data['selected_subtab'];
}

function get_config_table($selection)
{
	global $album_config_tabs;

	for ($i = 0; $i < sizeof($album_config_tabs); $i++)
	{
		if (0 == strcasecmp($album_config_tabs[$i]['selection'],$selection))
		{
			return $album_config_tabs[$i]['config_table_name'];
		}
	}

	return '';
}

function is_valid_config_tab($config_array)
{
	// these two array holds the minimum required fields for an config tab array
	// if there are other that's accepted just don't remove these
	// NOTE : the order of the keys are not important
	$valid_config_keys = array(0 => 'order', 1 => 'selection', 2 => 'title', 3 => 'detail', 4 => 'sub_config', 5 => 'config_table_name', 6 => 'generate_function', 7 => 'template_file');

	$valid_sub_config_keys = array(0 => 'order', 1 => 'selection', 2=> 'title', 3 => 'detail', 4 => 'template_file');

	if (sizeof($config_array) == 0)
	{
		return false;
	}

	for ($outer = 0; $outer < sizeof($valid_config_keys); $outer++)
	{
		// does the key exists ?
		if (@!array_key_exists($valid_config_keys[$outer],$config_array))
		{
			return false;
		}

		if (strcasecmp($valid_config_keys[$outer], 'sub_config') == 0)
		{
			// check each sub_config in the config array
			for ($inner = 0; $inner < sizeof($config_array['sub_config']); $inner++)
			{
				// and check eacj key in each sub_config array
				for ($i = 0; $i < sizeof($valid_sub_config_keys); $i++)
				{
					// does the key exists ?
					if (@!array_key_exists($valid_sub_config_keys[$i],$config_array['sub_config'][$inner]))
					{
						return false;
					}
				}
			}
		}

	}
	return true;
}

function remove_config_array($config_array, $index)
{
	$temp_array = array();
	for($i = 0; $i < sizeof($config_array); $i++)
	{
		if ($i != $index)
		{
			$temp_array[] = $config_array[$i];
		}
	}

	return $temp_array;
}

//------------------------------------------------------------------------
// This is the helper/comparation function for the usort function.
//------------------------------------------------------------------------
function sort_cmp($a, $b)
{
	if ($a['order'] == $b['order'])
	{
		return 0;
	}
	return ($a['order'] < $b['order']) ? -1 : 1;
}

function showResultMessage($in_message)
{
	global $lang, $album_user_id;

	$message = $in_message . '<br /><br />' . sprintf($lang['Click_return_album_category'], '<a href="' . append_sid('admin_album_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

?>