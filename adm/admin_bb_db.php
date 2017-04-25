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
* aUsTiN-Inc - (austin_inc@hotmail.com) - (phpbb-amod.com)
*
*/

define('IN_ICYPHOENIX', true);

// Mighty Gorgon - ACP Privacy - BEGIN
if (function_exists('check_acp_module_access'))
{
	$is_allowed = check_acp_module_access();
	if (empty($is_allowed))
	{
		return;
	}
}
// Mighty Gorgon - ACP Privacy - END

if (!empty($setmodules))
{
	$module['1400_DB_Maintenance']['110_DB_Admin'] = basename(__FILE__);
	$ja_module['1400_DB_Maintenance']['110_DB_Admin'] = false;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Functions - BEGIN
if(!function_exists('admin_db_calculate'))
{
	function admin_db_calculate($size)
	{
		global $lang;
		$gb = 1024 * 1024 * 1024;
		$mb = 1024 * 1024;
		$kb = 1024;
			if ($size >= $gb)
				$newsize = sprintf ("%01.2f", $size/$gb) . " Gb's";
			elseif ($size >= $mb)
				$newsize = sprintf ("%01.2f", $size/$mb) . " Mb's";
			elseif ($size >= $kb)
				$newsize = sprintf ("%01.2f", $size/$kb) . " Kb's";
			else
				$newsize = $size . " B";
		return $newsize;
	}
}

if(!function_exists('admin_db_prepare_sql'))
{
	function admin_db_prepare_sql($sql)
	{
		$lines     = explode("\n", trim($sql));
		$sql       = '';
		$linecount = sizeof($lines);
		$output    = '';

		for ($i = 0; $i < $linecount; $i++)
		{
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
			{
				if (($lines[$i][0] == '#') || ($lines[$i][0] == '-'))
				{
					$output .= "\n";
				}
				else
				{
					$output .= $lines[$i] ."\n";
				}
				$lines[$i] = '';
			}
		}
		return trim($output);
	}
}
// Functions - END

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if (empty($is_allowed))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

setup_extra_lang(array('lang_bb_db_admin'));

$mode = request_var('mode', '');
$action = request_var('action', '');
$mass = request_var('mass', '');
$mass_change = request_var('mass_change', '');

// Auth Check - BEGIN
$allowed = false;
$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
if ($user->data['user_id'] == $founder_id)
{
	$allowed = true;
}
if (!$allowed && defined('MAIN_ADMINS_ID'))
{
	$allowed_admins = explode(',', MAIN_ADMINS_ID);
	if (in_array($user->data['user_id'], $allowed_admins))
	{
		$allowed = true;
	}
}

if (!$allowed)
{
	message_die(GENERAL_ERROR, $lang['db_unauthed']);
}
// Auth Check - END

$images = IP_ROOT_PATH . 'images/bb_admin/';
echo '<table class="forumline tw200px">';
echo '	<tr>';
echo '		<td align="left" valign="middle" class="row2" colspan="15">';
echo '			<span class="genmed">';
echo '				' . $lang['db_sql_query_db'] . '<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=sql_change&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images . 'b_sql.png" alt="" /></a>&nbsp;&nbsp;<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=explain_change&amp;sid=' . $user->data['session_id'] . '"><img src="' . $images . 'b_tipp.png" alt="' . $lang['db_explain'] . '" title="' . $lang['db_explain'] . '" /></a>';
echo '				<div style="text-align: center;"><a href="' . append_sid($_SERVER['SCRIPT_NAME']) . '"><b>' . $dbname . '</b></a></div>';
echo '			</span>';
echo '		</td>';
echo '	</tr>';
echo '</table>';

if (isset($_POST['field_dynamic']))
{
	$old_names = $_POST['field_static'];
	$new_names = $_POST['field_dynamic'];
	$table_name = $_POST['table_name'];

	$q 	= 'DESCRIBE '. $table_name;
	$r	= $db->sql_query($q);
	$d	= $db->sql_fetchrowset($r);
	$qs = $qd = array();
	for ($x = 0; $x < sizeof($d); $x++)
	{
		if ($new_names[$x] != $old_names[$x])
		{
			$qs[] = 'ALTER TABLE `'. $table_name .'` CHANGE `'. $d[$x]['Field'] .'` `'. trim($new_names[$x]) .'` '. $d[$x]['Type'] .' '. (($d[$x]['Null']) ? 'NULL' : 'NOT NULL') .' Default \''. $d[$x]['Default'] .'\' '. $d[$x]['Extra'];
			$qd[] = 'ALTER TABLE `'. $table_name .'` CHANGE `'. trim($old_names[$x]) .'` `'. trim($new_names[$x]) .'` '. $d[$x]['Type'] .' '. (($d[$x]['Null']) ? 'NULL' : 'NOT NULL') .' Default \''. $d[$x]['Default'] .'\' '. $d[$x]['Extra'];
		}
	}

	echo '<table class="forumline">';
	for ($x = 0; $x < sizeof($qs); $x++)
	{
		echo '	<tr>';
		echo '		<th width="100%">';
		echo '			'. sprintf($lang['db_sql_total'], ($x + 1));
		echo '		</th>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td class="row1" width="100%">';
		echo '			<span class="genmed">';
		echo '				'. $qd[$x];
		echo '			</span>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		$db->sql_return_on_error(true);
		$r = $db->sql_query($qs[$x]);
		$db->sql_return_on_error(false);

		if (!$r)
		{
			$error = $db->sql_error();
			echo $error['message'];
		}
		else
		{
			echo $lang['db_sql_field_changed'];
		}
		echo '			</span>';
		echo '		</td>';
		echo '	</tr>';
	}
	echo '	<tr>';
	echo '		<th>&nbsp;</th>';
	echo '	</tr>';
	echo '</table>';
	include_once('page_footer_admin.' . PHP_EXT);
}

if ($mode == 'sql_change')
{
	if (!$action)
	{
		echo '<form name="sql" action="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'" method="post">';
		echo '<table class="forumline">';
		echo '	<tr>';
		echo '		<td class="row1" width="100%">';
		echo '			<textarea cols="100" rows="20" name="sql_input" class="post"></textarea>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td class="row2 row-center" width="100%">';
		echo '			<input type="submit" name="submit_it" value="'. $lang['db_submit_q'] .'" onclick="document.sql.submit()" class="liteoption" />';
		echo '			<input type="hidden" name="mode" value="sql_change" />';
		echo '			<input type="hidden" name="action" value="submit" />';
		echo '		</td>';
		echo '	</tr>';
		echo '</table>';
		echo '</form>';
	}

	if (isset($_POST['action']))
	{
		unset($sql, $new_sql, $x);
		$sql 		= (isset($_POST['sql_input'])) ? $_POST['sql_input'] : '';
		$sql 		= admin_db_prepare_sql($sql);
		$new_sql 	= explode(";", $sql);
		$sql_2		= array();

		if (!$sql)
		{
			message_die(GENERAL_ERROR, $lang['db_no_query']);
		}

		for ($x = 0; $x < sizeof($new_sql); $x++)
		{
			if ($new_sql[$x])
			{
				$sql_2[] = $new_sql[$x] . ';';
			}
		}

		for ($x = 0; $x < sizeof($sql_2); $x++)
		{
			if (!$sql_2[$x])
			{
				break;
			}
			unset($error, $row, $q, $r, $select_statement);

			$words = explode(' ', $sql_2[$x]);
				if (trim(strtolower($words[0])) == 'select')
					$select_statement = TRUE;

			echo '<table class="forumline">';
			echo '	<tr>';
			echo '		<th width="100%">';
			echo '			'. sprintf($lang['db_sql_total'], ($x + 1));
			echo '		</th>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td class="row1" width="100%">';
			echo '			<span class="genmed">';
			echo '				'. str_replace("\n", '<br />', stripslashes($sql_2[$x]));
			echo '			</span>';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td class="row2">';
			echo '			<span class="genmed">';
			$q = stripslashes($sql_2[$x]);
			if ($select_statement)
				{
					$db->sql_return_on_error(true);
					$r = $db->sql_query($q);
					$db->sql_return_on_error(false);

				if (!$r)
					{
				$error = $db->sql_error();
				echo $error['message'];
					}
				else
					{
				$row = $db->sql_fetchrow($r);
				$keys = array_keys($row);
				$vals = array_values($row);
					for ($y = 0; $y < sizeof($keys); $y++)
						echo $keys[$y] .': '. $vals[$y] .'<br />';
					}
				}
			else
				{
					$db->sql_return_on_error(true);
					$r = $db->sql_query($q);
					$db->sql_return_on_error(false);

				if (!$r)
					{
				$error = $db->sql_error();
				echo $error['message'];
					}
				else
					{
				$rows = $db->sql_affectedrows($r);
				echo sprintf($lang['db_aff_total'], number_format($rows));
					}
				}
		echo '			</span>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th>&nbsp;</th>';
		echo '	</tr>';
		echo '</table>';
			}
		}
	}

if ($mode == 'browse')
{
$start = (isset($_GET['start'])) ? intval($_GET['start']) : '0';
$start = ($start < 0) ? 0 : $start;
$order = (isset($_GET['order'])) ? $_GET['order'] : '';
$way = (isset($_GET['way'])) ? $_GET['way'] : '';
$order2 = (($order) && ($way)) ? 'ORDER BY `'. $order .'` '. $way : '';

$q = 'SHOW TABLE STATUS';
$r = $db->sql_query($q);
$tables = $db->sql_fetchrowset($r);

	for ($x = 0; $x < sizeof($tables); $x++)
		{
		if ($x == intval($_GET['table']))
			{
		$table_name = $tables[$x]['Name'];
		$table_id	= $x;
		break;
			}
		}

$q = 'SELECT *
		FROM '. $table_name;
$r = $db->sql_query($q);
$rows = $db->sql_numrows($r);

if ($order2)
	$q = 'SELECT *
			FROM `'. $table_name .'`
			'. $order2 .'
			LIMIT '. $start .', 30';
else
	$q = 'SELECT *
			FROM `'. $table_name .'`
			LIMIT '. $start .', 30';

$r 		= $db->sql_query($q);
$row 	= $db->sql_fetchrowset($r);

if ($rows)
	{
$total 			= $rows;
$pagination 	= generate_pagination($_SERVER['SCRIPT_NAME'] .'?mode=browse&amp;table='. $table_id .'&amp;order='. $order .'&amp;way='. $way .'&amp;sid='. $user->data['session_id'], $rows, 30, $start) . '&nbsp;';
	}
else
	$pagination = '&nbsp;';

$page_number 	= sprintf($lang['Page_of'], (floor($start / 30) + 1), ceil($rows / 30));
$showing 		= "Showing ". number_format($start) ." - ". number_format($start + 30) ." (". number_format($rows) ." Total)";

echo '<table class="forumline tw30pct">';
echo '	<tr>';
echo '		<td class="row1" width="100%">';
echo '			<span class="genmed">';
echo '				'. $showing;
echo '			</span>';
echo '		</td>';
echo '	</tr>';
echo '	<tr>';
echo '		<td class="row2">';
echo '			<span class="genmed">';
echo '				'. str_replace("\n", '<br />', $q);
echo '			</span>';
echo '		</td>';
echo '	</tr>';
echo '	<tr>';
echo '		<td class="row1">';
echo '			<span class="genmed">';
echo '				' . $pagination . '&nbsp;&nbsp;&nbsp;' . $page_number;
echo '			</span>';
echo '		</td>';
echo '	</tr>';
echo '</table>';
echo '<br clear="all">';
echo '<table class="forumline">';
echo '	<tr>';
$fields = '';
$fields = array();
$q = 'DESCRIBE '. $table_name;
$r = $db->sql_query($q);
	while ($d = $db->sql_fetchrow($r))
		{
	if ($d['Field'])
		$fields[] = $d['Field'];
	echo '	<td class="row1">';
	echo '		<span class="genmed">';
	echo '			'. $d['Field'] .'&nbsp;<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=browse&amp;table='. $table_id .'&amp;order='. $d['Field'] .'&amp;way=DESC&amp;start='. $start .'&amp;sid='. $user->data['session_id'] .'"><img src="'. $images .'s_desc.png" border="0"></a>&nbsp;<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=browse&amp;table='. $table_id .'&amp;order='. $d['Field'] .'&amp;way=ASC&amp;start='. $start .'&amp;sid='. $user->data['session_id'] .'"><img src="'. $images .'s_asc.png" border="0"></a>';
	echo '		</span>';
	echo '	</td>';
		}
echo '	</tr>';

	for ($x = 0; $x < sizeof($row); $x++)
		{
	echo '	<tr>';
		for ($y = 0; $y < sizeof($fields); $y++)
			{
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. $row[$x][$fields[$y]];
		echo '			</span>';
		echo '		</td>';
			}
	echo '	</tr>';
		}
echo '</table>';
	}

if ((!$mode) && (!$mass_change))
	{
echo '<form name="mass" action="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'" method="post">';
echo '<table class="forumline">';
echo '	<tr>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_table_name'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th colspan="5">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_action'] .'</b>&nbsp;&nbsp;<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=explain&amp;sid='. $user->data['session_id'] .'"><img src="'. $images .'b_tipp.png" alt="'. $lang['db_explain'] .'" title="'. $lang['db_explain'] .'" border="0"></a>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_type'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_row_format'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_rows'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_avg_r_len'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_data_len'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_max_dat_len'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_index_len'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_overhead'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '		<th>';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_auto_inc'] .'</b>';
echo '			</span>';
echo '		</th>';
echo '	</tr>';

$q = 'SHOW TABLE STATUS';
$r = $db->sql_query($q);
$tables = $db->sql_fetchrowset($r);

$records = $size = $overhead = $table = 0;
$selected_tables = array();
	for ($x = 0; $x < sizeof($tables); $x++)
	{
		echo '	<tr>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				<input type="checkbox" name="selected_tables[]" value="'. $tables[$x]['Name'] .'" />&nbsp;'. $tables[$x]['Name'];
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=browse&amp;table='. $x .'&amp;sid='. $user->data['session_id'] .'">';
		echo '				<img src="'. $images .'b_select.png" alt="'. $lang['db_browse'] .'" title="'. $lang['db_browse'] .'">';
		echo '			</a>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=structure&amp;table='. $x .'&amp;sid='. $user->data['session_id'] .'">';
		echo '				<img src="'. $images .'b_props.png" alt="'. $lang['db_structure'] .'" title="'. $lang['db_structure'] .'">';
		echo '			</a>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=optimize&amp;table='. $x .'&amp;sid='. $user->data['session_id'] .'">';
		echo '				<img src="'. $images .'b_browse.png" alt="'. $lang['db_optimize'] .'" title="'. $lang['db_optimize'] .'">';
		echo '			</a>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=truncate&amp;table='. $x .'&amp;sid='. $user->data['session_id'] .'">';
		echo '				<img src="'. $images .'b_empty.png" alt="'. $lang['db_truncate'] .'" title="'. $lang['db_truncate'] .'">';
		echo '			</a>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=drop&amp;table='. $x .'&amp;sid='. $user->data['session_id'] .'">';
		echo '				<img src="'. $images .'b_drop.png" alt="'. $lang['db_drop'] .'" title="'. $lang['db_drop'] .'">';
		echo '			</a>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. $tables[$x]['Type'] .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. $tables[$x]['Row_format'] .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. number_format($tables[$x]['Rows']) .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. number_format($tables[$x]['Avg_row_length']) .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. admin_db_calculate($tables[$x]['Data_length']) .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. str_replace('.00', '', admin_db_calculate($tables[$x]['Max_data_length'])) .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. $tables[$x]['Index_length'] .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. admin_db_calculate($tables[$x]['Data_free']) .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '		<td class="row2">';
		echo '			<span class="genmed">';
		echo '				'. number_format($tables[$x]['Auto_increment']) .'&nbsp;';
		echo '			</span>';
		echo '		</td>';
		echo '	</tr>';
		$table	 	+= 1;
		$records 	+= $tables[$x]['Rows'];
		$size 		+= $tables[$x]['Data_length'];
		$overhead 	+= $tables[$x]['Data_free'];
	}

	$records 	= number_format($records);
	$size 		= admin_db_calculate($size);
	$overhead 	= admin_db_calculate($overhead);

echo '	<tr>';
echo '		<th>'. $table .'</th>';
echo '		<th colspan="7">&nbsp;</th>';
echo '		<th>'. $records .'</th>';
echo '		<th>&nbsp;</th>';
echo '		<th>'. $size .'</th>';
echo '		<th colspan="2">&nbsp;</th>';
echo '		<th>'. $overhead .'</th>';
echo '		<th>&nbsp;</th>';
echo '	</tr>';
echo '</table>';
echo '<table class="forumline">';
echo '	<tr>';
echo '		<td class="row2" colspan="4">';
echo '			<span class="genmed">';
echo '				'. $lang['db_with_sel'] .'&nbsp;&nbsp;<a href="#" onclick="setCheckboxes(\'mass\', \'selected_tables[]\', true); return false;" class="gensmall">' . $lang['MARK_ALL'] . '</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes(\'mass\', \'selected_tables[]\', false); return false;" class="gensmall">' . $lang['UNMARK_ALL'] . '</a>';
echo '			</span>';
echo '		</td>';
echo '	</tr>';
echo '	<tr>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<input type="submit" name="Optimize" value="'. $lang['db_optimize'] .'" onclick="document.mass.submit()" class="liteoption" />';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<input type="submit" name="Repair" value="'. $lang['db_repair'] .'" onclick="document.mass.submit()" class="liteoption" />';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<input type="submit" name="Truncate" value="'. $lang['db_truncate'] .'" onclick="document.mass.submit()" class="liteoption" />';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<input type="submit" name="Drop" value="'. $lang['db_drop'] .'" onclick="document.mass.submit()" class="liteoption" />';
echo '			</span>';
echo '		</td>';
echo '	</tr>';
echo '</table>';
echo '<input type="hidden" name="mass_change" value="selected" />';
echo '</form>';
	}

if ($mode == 'drop')
	{
$q = 'SHOW TABLE STATUS';
$r = $db->sql_query($q);
$tables = $db->sql_fetchrowset($r);

	for ($x = 0; $x < sizeof($tables); $x++)
		{
		if ($x == intval($_GET['table']))
			{
		$table_name = $tables[$x]['Name'];
		$table_id = $x;
		break;
			}
		}

	if (!$action)
		message_die(GENERAL_MESSAGE, sprintf($lang['db_dro_warning'], $table_name) .'<br /><br /><a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=drop&amp;action=yes&amp;table='. $table_id .'&amp;table_name='. $table_name .'&amp;sid='. $user->data['session_id'] .'">'. $lang['db_warning_y'] .'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'. $_SERVER['SCRIPT_NAME'] .'?action=no&amp;sid='. $user->data['session_id'] .'">'. $lang['db_warning_n'] .'</a>');

	if ($action == 'yes')
		{
	$q = 'DROP TABLE IF EXISTS '. $_GET['table_name'];
	$db->sql_query($q);

	message_die(GENERAL_MESSAGE, sprintf($lang['db_dro_success'], $_GET['table_name']) .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'">', '</a>'));
		}
	elseif ($action == 'no')
		redirect($_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id']);
	}

if ($mode == 'truncate')
	{
$q = 'SHOW TABLE STATUS';
$r = $db->sql_query($q);
$tables = $db->sql_fetchrowset($r);

	for ($x = 0; $x < sizeof($tables); $x++)
		{
		if ($x == intval($_GET['table']))
			{
		$table_name = $tables[$x]['Name'];
		$table_id	= $x;
		break;
			}
		}

	if (!$action)
		message_die(GENERAL_MESSAGE, sprintf($lang['db_tru_warning'], $table_name) .'<br /><br /><a href="'. $_SERVER['SCRIPT_NAME'] .'?mode=truncate&amp;action=yes&amp;table='. $table_id .'&amp;table_name='. $table_name .'&amp;sid='. $user->data['session_id'] .'">'. $lang['db_warning_y'] .'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'. $_SERVER['SCRIPT_NAME'] .'?action=no&amp;sid='. $user->data['session_id'] .'">'. $lang['db_warning_n'] .'</a>');

	if ($action == 'yes')
		{
	$q = 'TRUNCATE '. $_GET['table_name'];
	$db->sql_query($q);

	message_die(GENERAL_MESSAGE, sprintf($lang['db_tru_success'], $_GET['table_name']) .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'">', '</a>'));
		}
	elseif ($action == 'no')
		redirect($_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id']);
	}

if ($mode == 'optimize')
	{
$q = 'SHOW TABLE STATUS';
$r = $db->sql_query($q);
$tables = $db->sql_fetchrowset($r);

	for ($x = 0; $x < sizeof($tables); $x++)
		{
		if ($x == intval($_GET['table']))
			{
		$table_name = $tables[$x]['Name'];
		break;
			}
		}

$q = 'OPTIMIZE TABLE'. $table_name;
$db->sql_query($q);

message_die(GENERAL_MESSAGE, sprintf($lang['db_opt_success'], $table_name) .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'">', '</a>'));
	}

if ($mode == 'structure')
	{
$q = 'SHOW TABLE STATUS';
$r = $db->sql_query($q);
$tables = $db->sql_fetchrowset($r);

	for ($x = 0; $x < sizeof($tables); $x++)
		{
		if ($x == intval($_GET['table']))
			{
		$table_name = $tables[$x]['Name'];
		break;
			}
		}

echo '<form name="change_field" method="post" action="'. append_sid($_SERVER['SCRIPT_NAME']) .'">';
echo '<table class="forumline">';
echo '	<tr>';
echo '		<th colspan="6">'. $table_name .'</th>';
echo '	</tr>';
echo '	<tr>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_field'] .'</b>';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_type'] .'</b>';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_null'] .'</b>';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_key'] .'</b>';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_default'] .'</b>';
echo '			</span>';
echo '		</td>';
echo '		<td class="row2 row-center">';
echo '			<span class="genmed">';
echo '				<b>'. $lang['db_extra'] .'</b>';
echo '			</span>';
echo '		</td>';
echo '	</tr>';

$q = 'DESCRIBE '. $table_name;
$r = $db->sql_query($q);
$f = 0;
	while ($d = $db->sql_fetchrow($r))
		{
	echo '	<tr>';
	echo '		<td class="row2">';
	echo '			<input type="text" class="post" name="field_dynamic['. $f .']" value="'. $d['Field'] .'" />';
	echo '			<input type="hidden" name="field_static['. $f .']" value="'. $d['Field'] .'" />';
	echo '			<input type="hidden" name="table_name" value="'. $table_name .'" />';
	echo '			<input type="image" onclick="document.change_field.submit()" src="'. $images .'accept.gif" border="0" />';
	echo '		</td>';
	echo '		<td class="row2">';
	echo '			<span class="genmed">';
	echo '				'. $d['Type'] .'&nbsp;';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2">';
	echo '			<span class="genmed">';
	echo '				'. $d['Null'] .'&nbsp;';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2">';
	echo '			<span class="genmed">';
	echo '				'. $d['Key'] .'&nbsp;';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2">';
	echo '			<span class="genmed">';
	echo '				'. $d['Default'] .'&nbsp;';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2">';
	echo '			<span class="genmed">';
	echo '				'. $d['Extra'] .'&nbsp;';
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	$f++;
		}
echo '</table>';
echo '</form>';
	}

if ($mode == 'explain')
	message_die(GENERAL_MESSAGE, $lang['db_explained'] .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'">', '</a>'));

if ($mode == 'explain_change')
	message_die(GENERAL_MESSAGE, $lang['db_change_exp'] .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'">', '</a>'));

if ($mass_change)
	{
unset($tables_to_change, $do_optimize, $do_truncate, $do_repair, $do_drop, $q, $l, $msg);
$tables_to_change = isset($_POST['selected_tables']) ? $_POST['selected_tables'] : '';
	if (sizeof($tables_to_change) > '0')
		{
	$do_optimize 	= isset($_POST['Optimize']) ? TRUE : '';
	$do_repair 		= isset($_POST['Repair']) ? TRUE : '';
	$do_truncate 	= isset($_POST['Truncate']) ? TRUE : '';
	$do_drop 		= isset($_POST['Drop']) ? TRUE : '';

		if ($do_optimize)
			{
		$q = 'OPTIMIZE TABLE ';
		$l = $lang['db_opt_success'];
			}
		elseif ($do_repair)
			{
		$q = 'REPAIR TABLE ';
		$l = $lang['db_rep_success'];
			}
		elseif ($do_truncate)
			{
		$q = 'TRUNCATE ';
		$l = $lang['db_tru_success'];
			}
		elseif ($do_drop)
			{
		$q = 'DROP TABLE IF EXISTS ';
		$l = $lang['db_dro_success'];
			}

		for ($x = 0; $x < sizeof($tables_to_change); $x++)
			{
		$q2 = $q . $tables_to_change[$x];
		$db->sql_return_on_error(true);
		$result = $db->sql_query($q2);
		$db->sql_return_on_error(false);
		if ($result)
		{
			$msg .= sprintf($l, $tables_to_change[$x]) .'<br />';
		}
	}
		message_die(GENERAL_MESSAGE, $msg .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['SCRIPT_NAME'] .'?sid='. $user->data['session_id'] .'">', '</a>'));
		}
	}

echo '<br clear="all">';
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>