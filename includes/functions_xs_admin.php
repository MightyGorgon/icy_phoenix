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
* UseLess
*
*/

function xsm_get_info($mode, $id)
{
	global $db;

	switch($mode)
	{
		case 'block':
			$table = NAV_BLOCKS_TABLE;
			$idfield = 'block_id';
			$namefield = 'block_title';

			$err_name = 'Block';
			break;

		case 'menu':
			$table = NAV_MENUS_TABLE;
			$idfield = 'menu_id';
			$namefield = 'menu_title';

			$err_name = 'Menu';
			break;

		case 'news':
			$table = XS_NEWS_TABLE;
			$idfield = 'news_id';
			$namefield = 'news_text';

			$err_name = 'News';
			break;

		case 'ticker':
			$table = XS_NEWS_XML_TABLE;
			$idfield = 'xml_id';

			$err_name = 'News Ticker';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for function xsn_get_info", "", __LINE__, __FILE__);
			break;
	}

	$err_count = "Couldn't get $err_name Count";
	$err_info = "Couldn't get $err_name information";
	$err_items = "$err_name doesn't exist or multiple $err_name items with ID";

	$sql = "SELECT count(*) as total
		FROM $table";
	$result = $db->sql_query($sql);
	$count = $db->sql_fetchrow($result);
	$count = $count['total'];

	$sql = "SELECT *
		FROM $table
		WHERE $idfield = $id";
	$result = $db->sql_query($sql);

	if($db->sql_numrows($result) != 1)
	{
		message_die(GENERAL_ERROR, $err_items . ' ' . $id, "", __LINE__, __FILE__);
	}

	$return = $db->sql_fetchrow($result);
	$return['number'] = $count;
	return $return;
}

function xsm_get_list($mode, $id, $select)
{
	global $db;

	switch($mode)
	{
		case 'block':
			$table = NAV_BLOCKS_TABLE;
			$idfield = 'block_id';
			$namefield = 'block_title';
			break;

		case 'menu':
			$table = NAV_MENUS_TABLE;
			$idfield = 'menu_id';
			$namefield = 'menu_title';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( $select == 1 && $mode == 'menu')
	{
		$sql .= " WHERE menu_is_sub <> 1";
	}
	elseif( $select == 0 )
	{
		$sql .= " WHERE $idfield <> $id";
	}
	$result = $db->sql_query($sql);

	$block_list = "";
	while( $row = $db->sql_fetchrow($result) )
	{
		$s = "";
		if ($row[$idfield] == $id)
		{
			$s = " selected=\"selected\"";
		}
		$block_list .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}

	return($block_list);
}

function xsm_renumber_order($mode, $block = 0)
{
	global $db;

	switch($mode)
	{
		case 'block':
			$table = NAV_BLOCKS_TABLE;
			$idfield = 'block_id';
			$orderfield = 'block_order';
			$block = 0;
			break;

		case 'menu':
			$table = NAV_MENUS_TABLE;
			$idfield = 'menu_id';
			$orderfield = 'menu_order';
			$blockfield = 'block_id';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( $block != 0)
	{
		$sql .= " WHERE $blockfield = $block";
	}
	$sql .= " ORDER BY $orderfield ASC";
	$result = $db->sql_query($sql);

	$i = 10;
	$inc = 10;
	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table
			SET $orderfield = $i
			WHERE $idfield = " . $row[$idfield];
		$db->sql_query($sql);
		$i += 10;
	}

}

function bullet_picker($bullet_dir)
{
	$files = array();
	$names = array();

	$allowed_ext = array('gif', 'png', 'jpg', 'jpeg');

	$dir = @opendir($bullet_dir);

	while(($file = @readdir($dir)) !== false)
	{
		$fileinfo = pathinfo($file);
		$file_ext = strtolower($fileinfo['extension']);

		if(($file !== '.') && ($file !== '..') && !is_dir($file) && in_array($file_ext, $allowed_ext))
		{
			$files[] = $file;
			$tmp = substr($file, 0 , strrpos($file, "."));
			$names[] = ucwords(str_replace('_', ' ', $tmp));
		}
	}
	@closedir($dir);

	sort($files);
	sort($names);

	return array($files, $names);
}

?>