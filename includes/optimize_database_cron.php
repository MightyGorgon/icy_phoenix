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
* Sko22 (sko22@quellicheilpc.com)
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// Optimize database config
$sql = "SELECT * FROM " . OPTIMIZE_DB_TABLE . " ";
$opt_result = $db->sql_query($sql, false, 'cron_');
if( !( $opt_row = $db->sql_fetchrow($opt_result) ) )
{
	message_die(GENERAL_ERROR, 'Could not obtain optimize database config', '', __LINE__, __FILE__, $sql);
}

if ( ( $opt_row['cron_enable'] == '1' ) && ( $opt_row['cron_lock'] == '1' ) )
{
	// Lock cron for contemporary accesses
	$sql = "UPDATE " . OPTIMIZE_DB_TABLE . " SET cron_lock = '0' ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not lock optimize database cron', '', __LINE__, __FILE__, $sql);
	}
	$db->clear_cache('cron_');
	$current_time = time();
	// Check cron next
	if ( $opt_row['cron_next'] <= $current_time )
	{
		ignore_user_abort();
		// Get tables list
		$list = mysql_list_tables($dbname);
		// Optimize tables
		while ( $tab = $db->sql_fetchrow($list) )
		{
			foreach ($tab as $column_value)
			{
				$sql = "OPTIMIZE TABLES $column_value";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't optimize database", "", __LINE__, __FILE__, $sql);
				}
			}
		}
		$sql = "UPDATE " . OPTIMIZE_DB_TABLE . " SET cron_next = " . ( $current_time + $opt_row['cron_every'] ) . ", cron_count = cron_count + 1 ";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update next optimize time', '', __LINE__, __FILE__, $sql);
		}
		//$db->clear_cache('cron_');
		$db->clear_cache();
	}
}
else
{
	// Unlock cron for contemporary accesses
	$sql = "UPDATE " . OPTIMIZE_DB_TABLE . " SET cron_lock = '1' ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not unlock optimize database cron', '', __LINE__, __FILE__, $sql);
	}
}

?>