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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Replace values in DB: useful for UTF8 Conversion
*/
function sql_replace($table, $fields, $html_encode = true, $stripslashes = false)
{
	global $db, $cache;

	if (empty($fields) || empty($table))
	{
		return false;
	}

	if (!is_array($fields))
	{
		$fields = array($fields);
	}

	$sql_replace_array = array();

	if ($html_encode)
	{
		foreach ($fields as $field)
		{
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '&', '&amp;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '&&amp;', '&amp;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '\"', '&quot;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '<', '&lt;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '>', '&gt;')";
		}
	}

	if ($stripslashes)
	{
		foreach ($fields as $field)
		{
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", \"\'\", \"'\")";
		}
	}

	for ($i = 0; $i < sizeof($sql_replace_array); $i++)
	{
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql_replace_array[$i]);
		$db->sql_return_on_error(false);
	}

	return true;
}

?>