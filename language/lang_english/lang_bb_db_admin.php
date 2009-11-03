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
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'db_browse' => 'Browse',
	'db_truncate' => 'Truncate',
	'db_optimize' => 'Optimize',
	'db_drop' => 'Drop',
	'db_repair' => 'Repair',
	'db_structure' => 'Structure',
	'db_explain' => 'Explain Actions',

	'db_table_name' => 'Table Name',
	'db_action' => 'Action',
	'db_type' => 'Type',
	'db_row_format' => 'Row Format',
	'db_rows' => 'Rows',
	'db_avg_r_len' => 'Avg Row Length',
	'db_data_len' => 'Data Length',
	'db_max_dat_len' => 'Max Data Length',
	'db_index_len' => 'Index Length',
	'db_overhead' => 'Overhead',
	'db_auto_inc' => 'Auto Increment',
	'db_with_sel' => 'With Selected Tables:',
	'db_field' => 'Field',
	'db_type' => 'Type',
	'db_null' => 'Null',
	'db_key' => 'Key',
	'db_default' => 'Default',
	'db_extra' => 'Extra',

	'db_unauthed' => 'Unauthorized Access.',
	'db_tru_warning' => 'Are you sure you want to truncate %s?',
	'db_dro_warning' => 'Are you sure you want to drop %s?',
	'db_warning_y' => 'Yes',
	'db_warning_n' => 'No',
	'db_opt_success' => 'Optimize Table <b>%s</b>, Successfully Completed.',
	'db_tru_success' => 'Truncate Table <b>%s</b>, Successfully Completed.',
	'db_dro_success' => 'Drop Table <b>%s</b>, Successfully Completed.',
	'db_rep_success' => 'Repair Table <b>%s</b>, Successfully Completed.',
	'db_explained' => 'Optimize Table: Will empty out any leftover bits of data.<br />Truncate Table: Will empty a table of all its contents.<br />Browse: Will allow you to view the data in a table.<br />Drop: Will remove a table from your database.',
	'db_back' => 'Click %sHere%s to return to the main page.',
	'db_change_exp' => 'By using this, you will be able to execute SQL\'s such as, INSERT INTO, ALTER TABLE, UPDATE, DELETE FROM, DROP TABLE, DESCRIBE, etc... Add as many as you want, seperate each SQL command with a semi-colon.',
	'db_submit_q' => ' Submit Query ',
	'db_sql_total' => 'SQL Query # %s',
	'db_aff_total' => 'Successfully Completed. Affected Rows: %s',
	'db_no_query' => 'You did not enter a query!',
	'db_sql_field_changed' => 'Field Name Changed.',
	'db_sql_query_db' => 'Query Your Database: ',
	'DB_Admin' => 'IP MyAdmin',
	)
);

?>