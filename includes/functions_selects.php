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

// Pick a language, any language ...
function language_select($default, $select_name = 'language', $dirname='language')
{

	$dir = opendir(IP_ROOT_PATH . $dirname);

	$lang = array();
	while ($file = readdir($dir))
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file)) && !is_link(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$lang[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lang);
	@reset($lang);

	$lang_select = '<select name="' . $select_name . '">';
	while (list($displayname, $filename) = @each($lang))
	{
		$selected = (strtolower($default) == strtolower($filename)) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
	}
	$lang_select .= '</select>';

	return $lang_select;
}

function language_select_h($default, $select_name = LANG_URL, $dirname = 'language')
{
	$dir = opendir(IP_ROOT_PATH . $dirname);

	$lang = array();
	while ($file = readdir($dir))
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file)) && !is_link(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$lang[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lang);
	@reset($lang);

	/*
	$lang_select = '<select name="' . $select_name . '" onchange="SetLangTheme();" class="gensmall">';
	while (list($displayname, $filename) = @each($lang))
	{
		$selected = (strtolower($default) == strtolower($filename)) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
		// <img src="' . $dirname . '/lang_' . $filename . '/flag.png" />
	}
	$lang_select .= '</select>';

	return $lang_select;
	*/

	$lang_installed = array();
	$lang_installed = $lang;

	return $lang_installed;
}

// Pick a template/theme combo,
function style_select($default_style, $select_name = 'style', $dirname = 'templates')
{
	global $db;

	$sql = "SELECT themes_id, style_name
		FROM " . THEMES_TABLE . "
		ORDER BY template_name, themes_id";

	if (!($result = $db->sql_query($sql, false, 'themes_')))
	{
		message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
	}

	$style_select = '<select name="' . $select_name . '">';
	while ($row = $db->sql_fetchrow($result))
	{
		$selected = ($row['themes_id'] == $default_style) ? ' selected="selected"' : '';
		$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . $row['style_name'] . '</option>';
	}
	$style_select .= '</select>';

	return $style_select;
}

function style_select_h($default_style, $select_name = STYLE_URL, $dirname = 'templates')
{
	global $db;

	$sql = "SELECT themes_id, style_name
		FROM " . THEMES_TABLE . "
		ORDER BY template_name, themes_id";
	if (!($result = $db->sql_query($sql, false, 'themes_')))
	{
		message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
	}

	$style_select = '<select name="' . $select_name . '" onchange="SetLangTheme();" class="gensmall">';
	while ($row = $db->sql_fetchrow($result))
	{
		/*
		$tsql = "SELECT COUNT(user_style) AS total_style
			FROM " . USERS_TABLE . "
			WHERE user_style = " . $row['themes_id'];
		if (!($tresult = $db->sql_query($tsql, false, 'themes_users_')))
		{
			message_die(GENERAL_ERROR, "Couldn't query user table", "", __LINE__, __FILE__, $tsql);
		}
		$trow = $db->sql_fetchrow($tresult);
		*/

		$selected = ($row['themes_id'] == $default_style) ? ' selected="selected"' : '';
		//$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . $row['style_name'] . ' [' . $trow['total_style'] . ']</option>';
		$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . $row['style_name'] . '</option>';
	}
	$style_select .= '</select>';

	return $style_select;
}

// Pick a timezone
function tz_select($default, $select_name = 'timezone')
{
	global $sys_timezone, $lang;

	if (!isset($default))
	{
		$default == $sys_timezone;
	}
	$tz_select = '<select name="' . $select_name . '">';

	while(list($offset, $zone) = @each($lang['tz']))
	{
		$selected = ($offset == $default) ? ' selected="selected"' : '';
		$tz_select .= '<option value="' . $offset . '"' . $selected . '>' . str_replace('GMT', 'UTC', $zone) . '</option>';
	}
	$tz_select .= '</select>';

	return $tz_select;
}

// Visual pick Date Format for non technical users
function date_select($default_format, $select_name = 'dateformat')
{
	global $lang, $board_config;

	//---------------------------------------------------
	$date_format_list[] = array('Y/m/d - H:i');
	$date_format_list[] = array('Y.m.d - H:i');
	$date_format_list[] = array('d/m/Y - H:i');
	$date_format_list[] = array('d.m.Y - H:i');
	//---------------------------------------------------
	$date_format_list[] = array('F d Y, H:i');
	$date_format_list[] = array('F d Y, G:i');
	$date_format_list[] = array('F d Y, h:i A');
	$date_format_list[] = array('F d Y');
	//---------------------------------------------------
	$date_format_list[] = array('d F Y');
	$date_format_list[] = array('d F Y, H:i');
	$date_format_list[] = array('d F Y, G:i');
	$date_format_list[] = array('d F Y, h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('l, d F Y');
	$date_format_list[] = array('l, d F Y, H:i');
	$date_format_list[] = array('l, d F Y, G:i');
	$date_format_list[] = array('l, d F Y, h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('D, M d Y');
	$date_format_list[] = array('D, M d Y, H:i');
	$date_format_list[] = array('D, M d Y, G:i');
	$date_format_list[] = array('D, M d Y, h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('D d M');
	$date_format_list[] = array('D d M, Y H:i');
	$date_format_list[] = array('D d M, Y G:i');
	$date_format_list[] = array('D d M, Y h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('d/m/Y');
	$date_format_list[] = array('d/m/Y H:i');
	$date_format_list[] = array('d/m/Y G:i');
	$date_format_list[] = array('d/m/Y h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('m/d/Y');
	$date_format_list[] = array('m/d/Y H:i');
	$date_format_list[] = array('m/d/Y G:i');
	$date_format_list[] = array('m/d/Y h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('m.d.Y');
	$date_format_list[] = array('m.d.Y H:i');
	$date_format_list[] = array('m.d.Y G:i');
	$date_format_list[] = array('m.d.Y h:i A');
	//---------------------------------------------------
	$date_format_list[] = array('d.m.Y');
	$date_format_list[] = array('d.m.Y H:i');
	$date_format_list[] = array('d.m.Y G:i');
	$date_format_list[] = array('d.m.Y h:i A');
	//---------------------------------------------------


	//---------------------------------------------------
	// Set a default value.
	//---------------------------------------------------
	if (empty($default_format))
	{
		$default_format = $date_format_list[11][0];
	}


	$date_select = '<select name="' . $select_name . '">' . "\n";
	for($i = 0; $i < count($date_format_list); $i++)
	{
		$date_format = $date_format_list[$i][0];
		$date_desc   = create_date($date_format_list[$i][0], time(), $board_config['board_timezone']);

		$selected = ($date_format == $default_format) ? ' selected="selected"' : '';
		$date_select .= '<option value="' . $date_format . '"' . $selected . '>' . $date_desc . '</option>' . "\n";

		$counter = 0;
	}
	$date_select .= '</select>' . "\n";

	return $date_select;
}

function select_gravatar_rating($default = '')
{
	global $lang;

	$symbols = array('G', 'PG', 'R', 'X');

	$select_box = '<select name="gravatar_rating"><option value="">' . $lang['None'] . '</option>';
	foreach($symbols as $rating)
	{
		$selected = ($rating == $default) ? ' selected="selected"' : '';
		$select_box .= '<option value="' . $rating . '"' . $selected . '>' . $rating . '</option>';
	}
	$select_box .= '</select>';

	return $select_box;
}

// Auth List
function auth_select($default, $select_name)
{
	global $lang;
	$auth_array_lang = array($lang['Forum_ALL'], $lang['Forum_REG'], $lang['Forum_MOD'], $lang['Forum_ADMIN']);
	//$auth_array = array(ANONYMOUS, USER, MOD, ADMIN);
	$auth_array = array(AUTH_ALL, AUTH_REG, AUTH_MOD, AUTH_ADMIN);

	$auth_select = '<select name="' . $select_name . '">';

	for($j = 0; $j < count($auth_array); $j++)
	{
		$selected = ($auth_array[$j] == $default) ? ' selected="selected"' : '';
		$auth_select .= '<option value="' . $auth_array[$j] . '"' . $selected . '>' . $auth_array_lang[$j] . '</option>';
	}
	$auth_select .= '</select>';

	return $auth_select;
}

?>