<?php
/**
*
* @package Icy Phoenix
* @version $Id: functions.php 175 2010-02-14 19:59:14Z Mighty Gorgon $
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
* thanks to:
* http://www.v-nessa.net/2007/12/06/convert-database-to-utf-8
* http://developer.loftdigital.com/blog/php-utf-8-cheatsheet
* http://www.mysqlperformanceblog.com/2007/12/18/fixing-column-encoding-mess-in-mysql/
*/

die('Comment this line...');

if (php_sapi_name() === 'cli')
{
	define('IP_ROOT_PATH', dirname(dirname($argv[0])) . '/');
}

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));

require(IP_ROOT_PATH . 'config.' . PHP_EXT);
define('SQL_LAYER', 'mysql4');
require(IP_ROOT_PATH . 'includes/db/mysql.' . PHP_EXT);

$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);
if(!$db->db_connect_id)
{
	trigger_error('Database connection failed', E_USER_ERROR);
}

$sql = "ALTER DATABASE {$db->sql_escape($dbname)}
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_bin
	DEFAULT COLLATE utf8_bin";
$db->sql_query($sql) or die($db->sql_error());

$sql = "SHOW TABLES";
$result = $db->sql_query($sql) or die($db->sql_error());
while ($row = $db->sql_fetchrow($result))
{
	$table = $row[0];
	$sql = "ALTER TABLE {$db->sql_escape($table)}
		DEFAULT CHARACTER SET utf8
		COLLATE utf8_bin";
	$db->sql_query($sql) or die($db->sql_error());
	print "$table changed to UTF-8.<br />\n";

	$sql = "SHOW FIELDS FROM {$db->sql_escape($table)}";
	$result_fields = $db->sql_query($sql);

	while ($row_fields = $db->sql_fetchrow($result_fields))
	{
		$field_name = $row_fields[0];
		$field_type = $row_fields[1];
		$field_null = $row_fields[2];
		$field_key = $row_fields[3];
		$field_default = $row_fields[4];
		$field_extra = $row_fields[5];
		if ((strpos(strtolower($field_type), 'char') !== false) || (strpos(strtolower($field_type), 'text') !== false) || (strpos(strtolower($field_type), 'blob') !== false) || (strpos(strtolower($field_type), 'binary') !== false))
		{
			//$sql_fields = "ALTER TABLE {$db->sql_escape($table)} CHANGE " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_type) . " CHARACTER SET utf8 COLLATE utf8_bin";
			$sql_fields = "ALTER TABLE {$db->sql_escape($table)} CHANGE " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_type) . " CHARACTER SET utf8 COLLATE utf8_bin " . (($field_null != 'YES') ? "NOT " : "") . "NULL DEFAULT " . (($field_default != 'None') ? ((!empty($field_default) || !is_null($field_default)) ? (is_string($field_default) ? ("'" . $db->sql_escape($field_default) . "'") : $field_default) : (($field_null != 'YES') ? "''" : "NULL")) : "''");
			$db->sql_query($sql_fields);
			print "\t$sql_fields<br />\n";
		}
	}
}
$result->close();

$sql = "ALTER TABLE {$table_prefix}search_wordlist CHANGE word_text varchar(50) COLLATE utf8_bin NOT NULL DEFAULT ''";
$db->sql_query($sql) or die($db->sql_error());

$db->sql_close();

?>