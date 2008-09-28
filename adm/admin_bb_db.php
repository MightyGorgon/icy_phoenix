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

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);

// Mighty Gorgon - ACP Privacy - BEGIN
if (function_exists('check_acp_module_access'))
{
	$is_allowed = check_acp_module_access();
	if ($is_allowed == false)
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
require('./pagestart.' . PHP_EXT);

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if ($is_allowed == false)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bb_db_admin.' . PHP_EXT);


if (isset( $_POST['mode'] ) || isset( $_GET['mode'] ))
{
	$mode = ( isset( $_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
}
else
{
	$mode = '';
}

if (isset( $_POST['action'] ) || isset( $_GET['action'] ))
{
	$action = ( isset( $_POST['action']) ) ? $_POST['action'] : $_GET['action'];
}
else
{
	$action = '';
}

if (isset( $_POST['mass'] ) || isset( $_GET['mass'] ))
{
	$mass = ( isset( $_POST['mass']) ) ? $_POST['mass'] : $_GET['mass'];
}
else
{
	$mass = '';
}

if (isset( $_POST['mass_change'] ) || isset( $_GET['mass_change'] ))
{
	$mass_change = ( isset( $_POST['mass_change']) ) ? $_POST['mass_change'] : $_GET['mass_change'];
}
else
{
	$mass_change = '';
}


//==== Start: Only authorized admins can view this
	$allowed = $allowed_admins = '';
	$allowed_admins = array(2,1138);
	for ($x = 0; $x < count($allowed_admins); $x++)
		{
		if ($userdata['user_id'] == $allowed_admins[$x])
			$allowed = TRUE;
		}

	if (!$allowed)
		message_die(GENERAL_ERROR, $lang['db_unauthed']);
//==== End: Only authorized admins can view this

//==== Start: Functions
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

	function admin_db_prepare_sql($sql)
	{
		$lines     = explode("\n", trim($sql));
		$sql       = '';
		$linecount = count($lines);
		$output    = '';

		for ( $i = 0; $i < $linecount; $i++ )
		{
			if ( ($i != ($linecount - 1)) || (strlen($lines[$i]) > 0) )
			{
				if ( ($lines[$i][0] == '#') || ($lines[$i][0] == '-') )
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
//==== End: Functions

	$images = IP_ROOT_PATH . 'images/bb_admin/';
	echo '<table align="center" valign="top" class="forumline">';
	echo '	<tr>';
	echo '		<td align="left" valign="middle" class="row2" colspan="15">';
	echo '			<span class="genmed">';
	echo '				' . $lang['db_sql_query_db'] . '<a href="' . $_SERVER['PHP_SELF'] . '?mode=sql_change&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images . 'b_sql.png" border="0"></a>&nbsp;&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?mode=explain_change&amp;sid=' . $userdata['session_id'] . '"><img src="' . $images . 'b_tipp.png" alt="' . $lang['db_explain'] . '" title="' . $lang['db_explain'] . '" border="0"></a>';
	echo '				<div style="text-align:center;"><a href="' . append_sid($_SERVER['PHP_SELF']) . '"><b>' . $dbname . '</b></a></div>';
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	echo '</table>';

	if (isset($_POST['field_dynamic']))
	{
		$old_names 	= $_POST['field_static'];
		$new_names 	= $_POST['field_dynamic'];
		$table_name = $_POST['table_name'];

		$q 	= 'DESCRIBE '. $table_name;
		$r	= $db->sql_query($q);
		$d	= $db->sql_fetchrowset($r);
		$qs = $qd = array();
		for ($x = 0; $x < count($d); $x++)
		{
			if ($new_names[$x] != $old_names[$x])
			{
				$qs[] = 'ALTER TABLE `'. $table_name .'` CHANGE `'. $d[$x]['Field'] .'` `'. trim($new_names[$x]) .'` '. $d[$x]['Type'] .' '. (($d[$x]['Null']) ? 'NULL' : 'NOT NULL') .' Default \''. $d[$x]['Default'] .'\' '. $d[$x]['Extra'];
				$qd[] = 'ALTER TABLE `'. $table_name .'` CHANGE `'. trim($old_names[$x]) .'` `'. trim($new_names[$x]) .'` '. $d[$x]['Type'] .' '. (($d[$x]['Null']) ? 'NULL' : 'NOT NULL') .' Default \''. $d[$x]['Default'] .'\' '. $d[$x]['Extra'];
			}
		}

		echo '<table align="center" width="100%" class="forumline">';
		for ($x = 0; $x < count($qs); $x++)
		{
			echo '	<tr>';
			echo '		<th width="100%">';
			echo '			'. sprintf($lang['db_sql_total'], ($x + 1));
			echo '		</th>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td class="row1" align="left" width="100%">';
			echo '			<span class="genmed">';
			echo '				'. $qd[$x];
			echo '			</span>';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td class="row2" align="left" width="100%">';
			echo '			<span class="genmed">';
			if (!$db->sql_query($qs[$x]))
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
		echo '		<th width="100%">&nbsp;</th>';
		echo '	</tr>';
		echo '</table>';
		include_once('page_footer_admin.' . PHP_EXT);
	}

	if ($mode == 'sql_change')
	{
		if (!$action)
		{
			echo '<form name="sql" action="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'" method="post">';
			echo '<table align="center" width="100%" class="forumline">';
			echo '	<tr>';
			echo '		<td class="row1" width="100%">';
			echo '			<textarea cols="100" rows="20" name="sql_input" class="post"></textarea>';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td class="row2 row-center" width="100%">';
			echo '			<input type="submit" name="submit_it" value="'. $lang['db_submit_q'] .'" onclick="document.sql.submit()" class="lightoption">';
			echo '			<input type="hidden" name="mode" value="sql_change">';
			echo '			<input type="hidden" name="action" value="submit">';
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

			for ($x = 0; $x < count($new_sql); $x++)
			{
				if ($new_sql[$x])
				{
					$sql_2[] = $new_sql[$x] . ';';
				}
			}

			for ($x = 0; $x < count($sql_2); $x++)
			{
				if (!$sql_2[$x])
				{
					break;
				}
				unset($error, $row, $q, $r, $select_statement);

				$words = explode(' ', $sql_2[$x]);
					if (trim(strtolower($words[0])) == 'select')
						$select_statement = TRUE;

				echo '<table align="center" width="100%" class="forumline">';
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
				echo '		<td class="row2" width="100%">';
				echo '			<span class="genmed">';
				$q = stripslashes($sql_2[$x]);
				if ($select_statement)
					{
					if (!($r = $db->sql_query($q)))
						{
					$error = $db->sql_error();
					echo $error['message'];
						}
					else
						{
					$row = $db->sql_fetchrow($r);
					$keys = array_keys($row);
					$vals = array_values($row);
						for ($y = 0; $y < count($keys); $y++)
							echo $keys[$y] .': '. $vals[$y] .'<br />';
						}
					}
				else
					{
					if (!($r = $db->sql_query($q)))
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
			echo '		<th width="100%">&nbsp;</th>';
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
	$order2 = ( ($order) && ($way) ) ? 'ORDER BY `'. $order .'` '. $way : '';

	$q = 'SHOW TABLE STATUS';
	$r = $db->sql_query($q);
	$tables = $db->sql_fetchrowset($r);

		for ($x = 0; $x < count($tables); $x++)
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
	$pagination 	= generate_pagination($_SERVER['PHP_SELF'] .'?mode=browse&amp;table='. $table_id .'&amp;order='. $order .'&amp;way='. $way .'&amp;sid='. $userdata['session_id'], $rows, 30, $start). '&nbsp;';
		}
	else
		$pagination = '&nbsp;';

	$page_number 	= sprintf($lang['Page_of'], ( floor( $start / 30 ) + 1 ), ceil( $rows / 30 ));
	$showing 		= "Showing ". number_format($start) ." - ". number_format($start + 30) ." ( ". number_format($rows) ." Total )";

	echo '<table align="left" width="30%" class="forumline">';
	echo '	<tr>';
	echo '		<td class="row1" width="100%">';
	echo '			<span class="genmed">';
	echo '				'. $showing;
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td class="row2" width="100%">';
	echo '			<span class="genmed">';
	echo '				'. str_replace("\n", '<br />', $q);
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td class="row1" width="100%">';
	echo '			<span class="genmed">';
	echo '				'. $pagination .'&nbsp;&nbsp;&nbsp;'. $page_number;
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	echo '</table>';
	echo '<br clear="all">';
	echo '<table align="left" width="100%" class="forumline">';
	echo '	<tr>';
	$fields = '';
	$fields = array();
	$q 	= 'DESCRIBE '. $table_name;
	$r	= $db->sql_query($q);
		while ($d = $db->sql_fetchrow($r))
			{
		if ($d['Field'])
			$fields[] = $d['Field'];
		echo '	<td class="row1">';
		echo '		<span class="genmed">';
		echo '			'. $d['Field'] .'&nbsp;<a href="'. $_SERVER['PHP_SELF'] .'?mode=browse&amp;table='. $table_id .'&amp;order='. $d['Field'] .'&amp;way=DESC&amp;start='. $start .'&amp;sid='. $userdata['session_id'] .'"><img src="'. $images .'s_desc.png" border="0"></a>&nbsp;<a href="'. $_SERVER['PHP_SELF'] .'?mode=browse&amp;table='. $table_id .'&amp;order='. $d['Field'] .'&amp;way=ASC&amp;start='. $start .'&amp;sid='. $userdata['session_id'] .'"><img src="'. $images .'s_asc.png" border="0"></a>';
		echo '		</span>';
		echo '	</td>';
			}
	echo '	</tr>';

		for ($x = 0; $x < count($row); $x++)
			{
		echo '	<tr>';
			for ($y = 0; $y < count($fields); $y++)
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

	if ( (!$mode) && (!$mass_change) )
		{
	echo '<script type="text/javascript">';
	echo 'function select_switch(status)';
	echo '	{';
	echo '	for (i = 0; i < document.mass.length; i++)';
	echo '		{';
	echo '	document.mass.elements[i].checked = status;';
	echo '		}';
	echo '	}';
	echo '</script>';
	echo '<form name="mass" action="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'" method="post">';
	echo '<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">';
	echo '	<tr>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_table_name'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle" colspan="5">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_action'] .'</b>&nbsp;&nbsp;<a href="'. $_SERVER['PHP_SELF'] .'?mode=explain&amp;sid='. $userdata['session_id'] .'"><img src="'. $images .'b_tipp.png" alt="'. $lang['db_explain'] .'" title="'. $lang['db_explain'] .'" border="0"></a>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_type'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_row_format'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_rows'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_avg_r_len'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_data_len'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_max_dat_len'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_index_len'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
	echo '			<span class="genmed">';
	echo '				<b>'. $lang['db_overhead'] .'</b>';
	echo '			</span>';
	echo '		</th>';
	echo '		<th valign="middle">';
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
		for ($x = 0; $x < count($tables); $x++)
		{
			echo '	<tr>';
			echo '		<td class="row2" valign="top">';
			echo '			<span class="genmed">';
			echo '				<input type="checkbox" name="selected_tables[]" value="'. $tables[$x]['Name'] .'">&nbsp;'. $tables[$x]['Name'];
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="top">';
			echo '			<a href="'. $_SERVER['PHP_SELF'] .'?mode=browse&amp;table='. $x .'&amp;sid='. $userdata['session_id'] .'">';
			echo '				<img src="'. $images .'b_select.png" alt="'. $lang['db_browse'] .'" title="'. $lang['db_browse'] .'">';
			echo '			</a>';
			echo '		</td>';
			echo '		<td class="row2" valign="top">';
			echo '			<a href="'. $_SERVER['PHP_SELF'] .'?mode=structure&amp;table='. $x .'&amp;sid='. $userdata['session_id'] .'">';
			echo '				<img src="'. $images .'b_props.png" alt="'. $lang['db_structure'] .'" title="'. $lang['db_structure'] .'">';
			echo '			</a>';
			echo '		</td>';
			echo '		<td class="row2" valign="top">';
			echo '			<a href="'. $_SERVER['PHP_SELF'] .'?mode=optimize&amp;table='. $x .'&amp;sid='. $userdata['session_id'] .'">';
			echo '				<img src="'. $images .'b_browse.png" alt="'. $lang['db_optimize'] .'" title="'. $lang['db_optimize'] .'">';
			echo '			</a>';
			echo '		</td>';
			echo '		<td class="row2" valign="top">';
			echo '			<a href="'. $_SERVER['PHP_SELF'] .'?mode=truncate&amp;table='. $x .'&amp;sid='. $userdata['session_id'] .'">';
			echo '				<img src="'. $images .'b_empty.png" alt="'. $lang['db_truncate'] .'" title="'. $lang['db_truncate'] .'">';
			echo '			</a>';
			echo '		</td>';
			echo '		<td class="row2" valign="top">';
			echo '			<a href="'. $_SERVER['PHP_SELF'] .'?mode=drop&amp;table='. $x .'&amp;sid='. $userdata['session_id'] .'">';
			echo '				<img src="'. $images .'b_drop.png" alt="'. $lang['db_drop'] .'" title="'. $lang['db_drop'] .'">';
			echo '			</a>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. $tables[$x]['Type'] .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. $tables[$x]['Row_format'] .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. number_format($tables[$x]['Rows']) .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. number_format($tables[$x]['Avg_row_length']) .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. admin_db_calculate($tables[$x]['Data_length']) .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. str_replace('.00', '', admin_db_calculate($tables[$x]['Max_data_length'])) .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. $tables[$x]['Index_length'] .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
			echo '			<span class="genmed">';
			echo '				'. admin_db_calculate($tables[$x]['Data_free']) .'&nbsp;';
			echo '			</span>';
			echo '		</td>';
			echo '		<td class="row2" valign="middle">';
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
	echo '<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">';
	echo '	<tr>';
	echo '		<td class="row2" valign="middle" colspan="4">';
	echo '			<span class="genmed">';
	echo '				'. $lang['db_with_sel'] .'&nbsp;&nbsp;<a href="javascript:select_switch(true);" class="gensmall">Check All</a>&nbsp;<b>::</b>&nbsp;<a href="javascript:select_switch(false);" class="gensmall">Un-Check All</a>';
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td class="row2" valign="middle">';
	echo '			<span class="genmed">';
	echo '				<input type="submit" name="Optimize" value="'. $lang['db_optimize'] .'" onclick="document.mass.submit()" class="lightoption">';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2" valign="middle">';
	echo '			<span class="genmed">';
	echo '				<input type="submit" name="Repair" value="'. $lang['db_repair'] .'" onclick="document.mass.submit()" class="lightoption">';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2" valign="middle">';
	echo '			<span class="genmed">';
	echo '				<input type="submit" name="Truncate" value="'. $lang['db_truncate'] .'" onclick="document.mass.submit()" class="lightoption">';
	echo '			</span>';
	echo '		</td>';
	echo '		<td class="row2" valign="middle">';
	echo '			<span class="genmed">';
	echo '				<input type="submit" name="Drop" value="'. $lang['db_drop'] .'" onclick="document.mass.submit()" class="lightoption">';
	echo '			</span>';
	echo '		</td>';
	echo '	</tr>';
	echo '</table>';
	echo '<input type="hidden" name="mass_change" value="selected">';
	echo '</form>';
		}

	if ($mode == 'drop')
		{
	$q = 'SHOW TABLE STATUS';
	$r = $db->sql_query($q);
	$tables = $db->sql_fetchrowset($r);

		for ($x = 0; $x < count($tables); $x++)
			{
			if ($x == intval($_GET['table']))
				{
			$table_name = $tables[$x]['Name'];
			$table_id	= $x;
			break;
				}
			}

		if (!$action)
			message_die(GENERAL_MESSAGE, sprintf($lang['db_dro_warning'], $table_name) .'<br /><br /><a href="'. $_SERVER['PHP_SELF'] .'?mode=drop&amp;action=yes&amp;table='. $table_id .'&amp;table_name='. $table_name .'&amp;sid='. $userdata['session_id'] .'">'. $lang['db_warning_y'] .'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'. $_SERVER['PHP_SELF'] .'?action=no&amp;sid='. $userdata['session_id'] .'">'. $lang['db_warning_n'] .'</a>');

		if ($action == 'yes')
			{
		$q = 'DROP TABLE IF EXISTS '. $_GET['table_name'];
		$db->sql_query($q);

		message_die(GENERAL_MESSAGE, sprintf($lang['db_dro_success'], $_GET['table_name']) .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));
			}
		elseif ($action == 'no')
			redirect($_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id']);
		}

	if ($mode == 'truncate')
		{
	$q = 'SHOW TABLE STATUS';
	$r = $db->sql_query($q);
	$tables = $db->sql_fetchrowset($r);

		for ($x = 0; $x < count($tables); $x++)
			{
			if ($x == intval($_GET['table']))
				{
			$table_name = $tables[$x]['Name'];
			$table_id	= $x;
			break;
				}
			}

		if (!$action)
			message_die(GENERAL_MESSAGE, sprintf($lang['db_tru_warning'], $table_name) .'<br /><br /><a href="'. $_SERVER['PHP_SELF'] .'?mode=truncate&amp;action=yes&amp;table='. $table_id .'&amp;table_name='. $table_name .'&amp;sid='. $userdata['session_id'] .'">'. $lang['db_warning_y'] .'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'. $_SERVER['PHP_SELF'] .'?action=no&amp;sid='. $userdata['session_id'] .'">'. $lang['db_warning_n'] .'</a>');

		if ($action == 'yes')
			{
		$q = 'TRUNCATE '. $_GET['table_name'];
		$db->sql_query($q);

		message_die(GENERAL_MESSAGE, sprintf($lang['db_tru_success'], $_GET['table_name']) .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));
			}
		elseif ($action == 'no')
			redirect($_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id']);
		}

	if ($mode == 'optimize')
		{
	$q = 'SHOW TABLE STATUS';
	$r = $db->sql_query($q);
	$tables = $db->sql_fetchrowset($r);

		for ($x = 0; $x < count($tables); $x++)
			{
			if ($x == intval($_GET['table']))
				{
			$table_name = $tables[$x]['Name'];
			break;
				}
			}

	$q = 'OPTIMIZE TABLE'. $table_name;
	$db->sql_query($q);

	message_die(GENERAL_MESSAGE, sprintf($lang['db_opt_success'], $table_name) .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));
		}

	if ($mode == 'structure')
		{
	$q = 'SHOW TABLE STATUS';
	$r = $db->sql_query($q);
	$tables = $db->sql_fetchrowset($r);

		for ($x = 0; $x < count($tables); $x++)
			{
			if ($x == intval($_GET['table']))
				{
			$table_name = $tables[$x]['Name'];
			break;
				}
			}

	echo '<form name="change_field" method="post" action="'. append_sid($_SERVER['PHP_SELF']) .'">';
	echo '<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">';
	echo '	<tr>';
	echo '		<th colspan="6" valign="middle">'. $table_name .'</th>';
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

	$q 	= 'DESCRIBE '. $table_name;
	$r	= $db->sql_query($q);
	$f = 0;
		while ($d = $db->sql_fetchrow($r))
			{
		echo '	<tr>';
		echo '		<td class="row2">';
		echo '			<input type="text" class="post" name="field_dynamic['. $f .']" value="'. $d['Field'] .'">';
		echo '			<input type="hidden" name="field_static['. $f .']" value="'. $d['Field'] .'">';
		echo '			<input type="hidden" name="table_name" value="'. $table_name .'">';
		echo '			<input type="image" onclick="document.change_field.submit()" src="'. $images .'accept.gif" border="0">';
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
		message_die(GENERAL_MESSAGE, $lang['db_explained'] .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));

	if ($mode == 'explain_change')
		message_die(GENERAL_MESSAGE, $lang['db_change_exp'] .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));

	if ($mass_change)
		{
	unset($tables_to_change, $do_optimize, $do_truncate, $do_repair, $do_drop, $q, $l, $msg);
	$tables_to_change = isset($_POST['selected_tables']) ? $_POST['selected_tables'] : '';
		if (count($tables_to_change) > '0')
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

			for ($x = 0; $x < count($tables_to_change); $x++)
				{
			$q2 = $q . $tables_to_change[$x];
			if ($db->sql_query($q2))
				$msg .= sprintf($l, $tables_to_change[$x]) .'<br />';
				}
		message_die(GENERAL_MESSAGE, $msg .'<br /><br />'. sprintf($lang['db_back'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));
			}
		}

	echo '<br clear="all">';
	include_once('page_footer_admin.' . PHP_EXT);

?>