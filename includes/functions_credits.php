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
* Nivisec.com (support@nivisec.com)
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

/****************************************************************************
/** Constants and Main Vars.
/***************************************************************************/
/* Sub-dir to scan for .hl files.  Notice the trailing /!!! */
define('HL_DIR', 'docs/hl/');
/* This will turn all author e-mails into a cryptic item; for example
user@domain.ext => user at domain dot ext */
define('USE_CRYPTIC_EMAIL', false);
/* Setting this to true will print out TONS debug information I use */
define('DEBUG_THIS_MOD', false);
$hl_cache_list = array();


function setup_hacks_list_array()
{
	global $db, $lang, $hl_cache_list;

	$sql = 'SELECT * FROM ' . HACKS_LIST_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Hacks_List_Table'], '', __LINE__, __FILE__, $sql);
	}
	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$hl_cache_list[$row['hack_file']] = $row;
	}
}

function cryptize_hl_email($email)
{
	$cr_email = preg_replace("/@/", " at ", $email);
	$cr_email = preg_replace("/\./", " dot ", $cr_email);

	return $cr_email;
}

function scan_hl_files()
{
	global $board_config, $lang, $hl_cache_list, $phpbb_root_path;

	if (DEBUG_THIS_MOD) $board_config['hacks_list_hl_dir'] = 'hl/';

	// The list of dirs to scan.  By default this is the main phpbb root path and the dir set in the options.
	$scan_dir_list = array($phpbb_root_path, $phpbb_root_path . HL_DIR);

	foreach($scan_dir_list as $dir_item)
	{
		$dir_handle = opendir($dir_item);

		while (false !== ($file = readdir($dir_handle)))
		{
			if (substr($file, -3, 3) == '.hl')
			{
				if (DEBUG_THIS_MOD) print "<font color=\"red\">DEBUG - HL File Found: $file<br /></font>";
				if (!isset($hl_cache_list[$dir_item.$file]) || $hl_cache_list[$dir_item.$file]['hack_file_mtime'] != filemtime($dir_item.$file))
				{
					update_hl_file_cache($dir_item.$file);
				}
			}
		}
		closedir($dir_handle);
	}
}

function update_hl_file_cache($filename)
{
	global $db, $lang, $hl_cache_list;

	if (DEBUG_THIS_MOD) print "<font color=\"blue\">DEBUG - Updating File Cache: $filename<br /></font>";

	if (file_exists($filename))
	{
		//Open up the file and read in the data to send to the parse function
		// in an array for each newline
		$parsed_array = parse_hl_file(@file($filename));
		$parsed_array['hack_file'] = $filename;
		$parsed_array['hack_file_mtime'] = filemtime($filename);

		//Make the sql replace command
		$sql_1 = '';
		$sql_2 = '';
		foreach ($parsed_array as $key => $val)
		{
			if ($sql_1 != '') $sql_1 .= ', ';
			if ($sql_2 != '') $sql_2 .= ', ';

			$sql_1 .= $key;
			//Version is also considered numeric here, but we don't want it to be!
			$sql_2 .= (is_numeric($val) && $key != 'hack_version') ? $val : "'".addslashes($val)."'";
		}
		$sql = 'REPLACE INTO ' . HACKS_LIST_TABLE . " ($sql_1) VALUES ($sql_2)";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Error_Hacks_List_Table'], '', __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		message_die(GENERAL_ERROR, sprintf($lang['HL_File_Error'], $filename), '', __LINE__, __FILE__, $sql);
	}
}

function parse_hl_file($file_data)
{
	$data_array = array();
	//Remove commented lines (##) from data
	for ($i=0; $i < count($file_data); $i++)
	{
		if(substr(trim($file_data[$i]), 0, 2) == '##')
		{
			$file_data[$i] = null;
		}
	}

	//Item List
	$search_info_list = array(
	'Name', 'Description', 'Author',
	'Version', 'Download_URL', 'Author_EMAIL',
	'Author_WEB'
	);
	//Item to column in dbase name
	$database_columns = array(
	'Name' => 'hack_name', 'Description' => 'hack_desc', 'Author' => 'hack_author',
	'Version' => 'hack_version', 'Download_URL' => 'hack_download_url', 'Author_EMAIL' => 'hack_author_email',
	'Author_WEB' => 'hack_author_website'
	);

	//Sort out the two arrays
	sort($file_data, SORT_STRING);
	sort($search_info_list, SORT_STRING);

	//Go through to find our items
	foreach($search_info_list as $search_item)
	{
		$found = false;
		$continue = true;
		$i = 0;
		while ($i < count($file_data) && !$found)
		{
			//Our preg_xxxx pattern
			$pattern = '/'.$search_item.'?[ ]=?[ ]/';
			if (preg_match($pattern, $file_data[$i]))
			{
				//Make into tokens
				$tmp = preg_replace($pattern, '', trim($file_data[$i]));
				if (DEBUG_THIS_MOD) print "<span class=\"text_green\">DEBUG - Found $search_item in $i; using pattern \"$pattern\"<br /></span>";
				$data_array[$database_columns[$search_item]] .= substr($tmp, 1, strlen($tmp) - 2);
				if (DEBUG_THIS_MOD) print "<span class=\"text_gray\">DEBUG - Tokens Data - " . $data_array[$database_columns[$search_item]] . "</span><br />";
				$found = true;
			}
			$i++;
		}
	}
	return $data_array;
}

if (!function_exists('copyright_nivisec'))
{
	/**
	* @return void
	* @desc Prints a sytlized line of copyright for module
	*/
	function copyright_nivisec($name, $year)
	{
		print '<br /><div class="copyright" style="text-align:center;">' . $name . ' &copy; ' . $year . ' <a href="http://www.nivisec.com">Nivisec.com</a>.</div>';
	}
}

?>