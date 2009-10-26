<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/****************************************************************************
/** Functions
/***************************************************************************/
function topic_shadow_make_drop_box($prefix = 'mode')
{
	global $mode_types, $lang, $mode, $order_types, $order;

	$rval = '<select name="'.$prefix.'">';

	switch($prefix)
	{
		case 'mode':
		{
			foreach($mode_types as $val)
			{
				$selected = ($mode == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
		}
		case 'order':
		{
			foreach($order_types as $val)
			{
				$selected = ($order == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
		}
	}
	$rval .= '</select>';

	return $rval;
}

function ts_id_2_name($id, $mode = 'user')
{
	global $db;

	if ($id == '')
	{
		return '?';
	}

	switch($mode)
	{
		case 'user_formatted':
		{
			$sql = "SELECT user_id, username, user_active, user_color FROM " . USERS_TABLE . " WHERE user_id = " . $id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$formatted_username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
			return $formatted_username;
			break;
		}
		case 'user':
		{
			$sql = "SELECT username FROM " . USERS_TABLE . " WHERE user_id = " . $id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			return $row['username'];
			break;
		}
		case 'forum':
		{
			$sql = "SELECT f.forum_name
				FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
				WHERE t.topic_id = " . $id . "
					AND t.forum_id = f.forum_id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			return $row['forum_name'];
			break;
		}
	}
}

if (!function_exists('copyright_nivisec'))
{
	/**
	* @return void
	* @desc Prints a sytlized line of copyright for module
	*/
	function copyright_nivisec($name, $year)
	{
		print '<br /><div class="copyright" style="text-align:center;">' . $name . ' ' . MOD_VERSION . ' &copy; ' . $year . ' <a href="http://www.nivisec.com">Nivisec.com</a></div>';
	}
}


?>