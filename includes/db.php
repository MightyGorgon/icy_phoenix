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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

switch($dbms)
{
	case 'mysql':
		include($phpbb_root_path . 'includes/db/mysql.' . $phpEx);
		break;
	case 'mysql4':
		include($phpbb_root_path . 'includes/db/mysql4.' . $phpEx);
		break;
}

// Make the database connection.
$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);
if(!$db->db_connect_id)
{
	message_die(CRITICAL_ERROR, "Could not connect to the database");
}

?>