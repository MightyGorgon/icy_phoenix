<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// configuration of module: number of columns to use for displaying the links, may be 1..n
$num_columns = 2;

// get language information about myself to do filtering afterwards
// this is necessary for handling the included language files and their own $lang['module_name'] variables

$my_lang_module_name = $lang['module_name__stats_overview_section'];

// get information about the installed modules

$sql = 'SELECT module_id, name
	FROM ' . MODULES_TABLE . '
	WHERE active = 1
	AND installed = 1
	ORDER BY display_order';

if (!($result = $db->sql_query($sql)))
{
	message_die(CRITICAL_ERROR, 'Couldn\'t query database for statistic modules.');
}

$module_count = $db->sql_numrows($result);
$module_data = $db->sql_fetchrowset($result);

//
// for all installed modules output an inpage link
//
$num_modules = 0;
for ($i = 0; $i < $module_count; $i++)
{
	// dont show the link to index and dont show this module itself
	if (($module_data[$i]['name'] != '_stats_overview_section') && ($module_data[$i]['name'] != 'forum_index'))
	{
		$module_dir = trim($module_data[$i]['name']);

		// unset (eventually included) language variable of last loop
		unset($mod_name);
		$mod_name = $lang['module_name_' . $module_data[$i]['name']];

		// fall back solution in case no appropriate language file was found or the variable was not declared (module name from txt file)
		if ($mod_name=='')
		{
			$other_module_info = generate_module_info($module_data[$i]);
			$mod_name = $other_module_info['name'];
		}

		$template->assign_block_vars('stats_row_link', array(
			'START' => ((($num_modules % $num_columns) == 0) ? '<tr>' : ''),
			'END' => ((($num_modules % $num_columns) == ($num_columns - 1)) ? '</tr>' : ''),
			'COL_WIDTH' => ($num_modules % $num_columns == $num_columns - 1 ? '*' : floor(100 / $num_columns) . '%'),
			'U_STATS_LINK' => $phpbb_root_path . 'statistics.' . $phpEx . '#s' . $module_data[$i]['module_id'],
			'STATS_LINK' => $mod_name . $width
			)
		);
		$num_modules++;
	}

	// add empty cells for padding the last table row
	$empty_cells='';
	if ($num_modules % $num_columns != 0)
	{
		for ($j = 1; $j <= ($num_columns -($num_modules % $num_columns)); $j++)
		{
			$empty_cells.='<td class="row1"><span class="gen">&nbsp;</span></td>';
		}
		$empty_cells.='</tr>';
	}

}

$template->assign_vars(array(
	'MODULE_NAME' => $my_lang_module_name,
	'NUM_COLUMNS' => $num_columns,
	'EMPTY_CELLS' => $empty_cells)
);

?>