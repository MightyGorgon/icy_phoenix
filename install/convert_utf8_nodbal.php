<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
* Thanks to:
* http://www.v-nessa.net/2007/12/06/convert-database-to-utf-8
* http://developer.loftdigital.com/blog/php-utf-8-cheatsheet
* http://www.mysqlperformanceblog.com/2007/12/18/fixing-column-encoding-mess-in-mysql/
*/

//die('Comment this line...');

if (php_sapi_name() === 'cli')
{
	define('IP_ROOT_PATH', dirname(dirname($argv[0])) . '/');
}

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));

@set_time_limit(0);
@ini_set('memory_limit', '32M');

require IP_ROOT_PATH . 'config.' . PHP_EXT;

$db = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

if (mysqli_connect_error())
{
	trigger_error('Database connection failed', E_USER_ERROR);
}

$sql = "ALTER DATABASE {$db->real_escape_string($dbname)}
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_bin
	DEFAULT COLLATE utf8_bin";
$db->query($sql) or die(mysql_error());

$sql = "SHOW TABLES";
$result = $db->query($sql) or die(mysql_error());
while ($row = $result->fetch_row())
{
	$table = $row[0];
	if (strpos($table, $table_prefix) === 0)
	{
		$sql = "ALTER TABLE {$db->real_escape_string($table)}
			DEFAULT CHARACTER SET utf8
			COLLATE utf8_bin";
		$db->query($sql) or die(mysql_error());
		print "$table changed to UTF-8.<br />\n";

		$sql = "SHOW FIELDS FROM {$db->real_escape_string($table)}";
		$result_fields = $db->query($sql);

		while ($row_fields = $result_fields->fetch_row())
		{
			$field_name = $row_fields[0];
			$field_type = $row_fields[1];
			$field_null = $row_fields[2];
			$field_key = $row_fields[3];
			$field_default = $row_fields[4];
			$field_extra = $row_fields[5];
			if ((strpos(strtolower($field_type), 'char') !== false) || (strpos(strtolower($field_type), 'text') !== false) || (strpos(strtolower($field_type), 'blob') !== false) || (strpos(strtolower($field_type), 'binary') !== false))
			{
				//$sql_fields = "ALTER TABLE {$db->real_escape_string($table)} CHANGE " . $db->real_escape_string($field_name) . " " . $db->real_escape_string($field_name) . " " . $db->real_escape_string($field_type) . " CHARACTER SET utf8 COLLATE utf8_bin";
				$sql_fields = "ALTER TABLE {$db->real_escape_string($table)} CHANGE " . $db->real_escape_string($field_name) . " " . $db->real_escape_string($field_name) . " " . $db->real_escape_string($field_type) . " CHARACTER SET utf8 COLLATE utf8_bin " . (($field_null != 'YES') ? "NOT " : "") . "NULL DEFAULT " . (($field_default != 'None') ? ((!empty($field_default) || !is_null($field_default)) ? (is_string($field_default) ? ("'" . $db->real_escape_string($field_default) . "'") : $field_default) : (($field_null != 'YES') ? "''" : "NULL")) : "''");
				$db->query($sql_fields);
				print "\t$sql_fields<br />\n";
			}
		}
	}
}
$result->close();

$sql = "ALTER TABLE {$table_prefix}search_wordlist CHANGE word_text varchar(50) COLLATE utf8_bin NOT NULL DEFAULT ''";
$db->query($sql) or die(mysql_error());

$db->close();

flush();
die('<br /><br />Conversion completed successfully, you can now proceed with the update.');

?>