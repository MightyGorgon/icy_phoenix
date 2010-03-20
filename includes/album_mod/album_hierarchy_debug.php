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
* IdleVoid (idlevoid@slater.dk)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}
// -----------------------------------------------
// Debug helper functions
// -----------------------------------------------
// -----------------------------------------------
// This is a crappy piece of code to do some
// debugging, but for now it do the job
// -----------------------------------------------
function album_enable_debug($state = true)
{
	$GLOBALS['album_debug_enabled'] = $state;
}

function album_is_debug_enabled()
{
	global $album_config;

	if (@!array_key_exists('album_debug_enabled', $GLOBALS))
	{
		$GLOBALS['album_debug_enabled'] = false;
	}

	return ($GLOBALS['album_debug_enabled'] || $album_config['album_debug_mode'] == 1);
}

function album_debug()
{
	global $album_config;

	if ($album_config['album_debug_mode'] == 1 || album_is_debug_enabled() == true)
	{
		// simulate the $file and $line parameters for album_debugEx
		$tmparray = array(0 => '', 1 => '');
		$tmparray2 = func_get_args();
		$array = array_merge($tmparray , $tmparray2);
		call_user_func_array('album_debugEx', $array);
	}
}

function album_debugEx($file, $line)
{
	global $album_config;

	if ($album_config['album_debug_mode'] == 1 || album_is_debug_enabled() == true)
	{
		$out_array = array();

		$output_format = 'DEBUG :<br />';
		$output_format .= (!empty($file)) ? 'FILE : ' . $file . '<br />' : '';
		$output_format .= (!empty($line)) ? 'LINE : ' . $line . '<br />' : '';
		$output_format .= '----------------------------------------------------<br />';
		$output_format .= '%s<br />';
		$output_format .= '----------------------------------------------------<br />';

		$array = func_get_args();
		$numargs = func_num_args();

		if (gettype($array[2]) == 'array')
		{
			print('<pre>' . print_r($array[2], true) . '</pre>');
			return;
		}

		$intermediat_format = $array[2];

		for ($i = 3 ; $i <= $numargs ; $i++)
		{
			$out_array[] = album_debug_render($array[$i]);
		}

		$out_text = vsprintf($intermediat_format, $out_array);
		@printf($output_format, $out_text);
	}
}

function album_debug_dump_array($array, $level = 0)
{
	$counted_keys = 1;
	$result = "<i>array</i> = (";

	if ( 0 != ($total_keys =sizeof($array)) )
	{
		$result .= "\n";
		$indent = str_repeat("\t", $level + 1);

		foreach ($array as $key => $value)
		{
			$result .= $indent . "\t<b>[" . album_debug_render($key) . "]</b> => ";
			$result .= album_debug_render($value, $level + 1) . ( ($total_keys != $counted_keys) ? ",\n" : "\n");
			$counted_keys++;
		}
	}

	$result .= $indent . ")";
	return $result;
}

// ------------------------------------------------------------------
// NOTE : NOT USED AT THE MOMENT, NEEDS RECODING !!!!!
// this function is based on some code from php.net,
// I don't remember the author, SORRY
// NOTE : modified a little by me...I might completely redo this
// ------------------------------------------------------------------
function album_debug_dump_array_html($arr)
{
	static $i = 0;
	$i++;

	$indent = str_repeat("\t", $i + 1);

	echo "array = (\n<blockquote>";

	foreach($arr as $key => $val)
	{
		switch (gettype($val))
		{
			case "array":
				echo "<a href=\"#\" onclick=\"document.getElementById('_tree$i').style.display = 'block';\">[" . htmlspecialchars(album_debug_render($key)) . "]</a><br />";
				echo "<div id=\"_tree$i\" style=\"display: none;\">";
				echo album_debug_render($val); //)album_debug_dump_array_html($val);
				echo "</div>";
				break;
			case "integer":
			case "double":
				echo "<b>[" . htmlspecialchars(album_debug_render($key)) . "]</b> => <i>" . htmlspecialchars(album_debug_render($val)) . "</i><br />";
				break;
			case "boolean":
				echo "<b>[" . htmlspecialchars(album_debug_render($key)) . "]</b> => " . album_debug_render($val) . "<br />";
				break;
			case "string":
				echo "<b>[" . htmlspecialchars(album_debug_render($key)) . "]</b> => <code>" . htmlspecialchars(album_debug_render($val)) . "</code><br />";
				break;
			default:
				echo "<b>[" . htmlspecialchars(album_debug_render($key)) . "]</b> => " . gettype($val) . "<br />";
				break;
		}
	}

	echo "</blockquote>$indent)<br />";
}

function album_debug_render($variable, $array_level = 0)
{
	switch (gettype($variable))
	{
		case 'boolean':
			return $variable ? 'TRUE' : 'FALSE';
			break;
		case 'integer':
		case 'double':
			return $variable;
			break;
		case 'string':
			return '\'' . htmlspecialchars($variable) . '\'';
			break;
		case 'array':
			if ($array_level == 0)
				return '<pre>' . album_debug_dump_array($variable, $array_level) . '</pre>';
			return album_debug_dump_array($variable, $array_level);
		case 'object':
			return '<pre>' . print_r($variable, true) . '</pre>';
			break;
		case 'NULL':
			return 'NULL';
			break;
		default:
			return 'Unknown Type';
	}
}

?>