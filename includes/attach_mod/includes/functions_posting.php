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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

/**
* FTP File to Location
*/
function ftp_file($source_file, $dest_file, $mimetype, $disable_error_mode = false)
{
	global $attach_config, $lang, $error, $error_msg;

	$conn_id = attach_init_ftp();

	// Binary or Ascii ?
	$mode = FTP_BINARY;
	if (preg_match("/text/i", $mimetype) || preg_match("/html/i", $mimetype))
	{
		$mode = FTP_ASCII;
	}

	$res = @ftp_put($conn_id, $dest_file, $source_file, $mode);

	if (!$res && !$disable_error_mode)
	{
		$error = true;
		if (!empty($error_msg))
		{
			$error_msg .= '<br />';
		}
		$error_msg = sprintf($lang['Ftp_error_upload'], $attach_config['ftp_path']) . '<br />';
		@ftp_quit($conn_id);
		return false;
	}

	if (!$res)
	{
		return false;
	}

	@ftp_site($conn_id, 'CHMOD 0644 ' . $dest_file);
	@ftp_quit($conn_id);
	return true;
}

/**
* Build sql statement from array for insert/update/select statements
*
* Idea for this from Ikonboard
* Possible query values: INSERT, INSERT_SELECT, MULTI_INSERT, UPDATE, SELECT
*/
function attach_mod_sql_build_array($query, $assoc_ary = false)
{
	if (!is_array($assoc_ary))
	{
		return false;
	}

	$fields = array();
	$values = array();
	if ($query == 'INSERT' || $query == 'INSERT_SELECT')
	{
		foreach ($assoc_ary as $key => $var)
		{
			$fields[] = $key;

			if (is_null($var))
			{
				$values[] = 'NULL';
			}
			elseif (is_string($var))
			{
				$values[] = "'" . attach_mod_sql_escape($var) . "'";
			}
			elseif (is_array($var) && is_string($var[0]))
			{
				$values[] = $var[0];
			}
			else
			{
				$values[] = (is_bool($var)) ? intval($var) : $var;
			}
		}

		$query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
	}
	elseif ($query == 'MULTI_INSERT')
	{
		$ary = array();
		foreach ($assoc_ary as $id => $sql_ary)
		{
			$values = array();
			foreach ($sql_ary as $key => $var)
			{
				if (is_null($var))
				{
					$values[] = 'NULL';
				}
				elseif (is_string($var))
				{
					$values[] = "'" . attach_mod_sql_escape($var) . "'";
				}
				else
				{
					$values[] = (is_bool($var)) ? intval($var) : $var;
				}
			}
			$ary[] = '(' . implode(', ', $values) . ')';
		}

		$query = ' (' . implode(', ', array_keys($assoc_ary[0])) . ') VALUES ' . implode(', ', $ary);
	}
	elseif ($query == 'UPDATE' || $query == 'SELECT')
	{
		$values = array();
		foreach ($assoc_ary as $key => $var)
		{
			if (is_null($var))
			{
				$values[] = "$key = NULL";
			}
			elseif (is_string($var))
			{
				$values[] = "$key = '" . attach_mod_sql_escape($var) . "'";
			}
			else
			{
				$values[] = (is_bool($var)) ? "$key = " . intval($var) : "$key = $var";
			}
		}
		$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
	}

	return $query;
}

?>