<?
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

function file_output($file_filename, $file_content)
{
	$fp = fopen($file_filename, 'a');
	@fwrite($fp, $file_content);
	@fclose($fp);
	chmod($file_filename, 0777);
	return true;
}

/*
* get_file_details
* Get File Details: name, extension
*/
function get_file_details($file_name)
{
	$file_details = array();
	$file_tmp = str_replace ('http://', '', $file_name);
	$file_path[] = array();
	$file_path = explode('/', $file_tmp);
	$file_details['name_full'] = $file_path[count($file_path) - 1];
	$file_part = explode('.', strtolower($file_details['name_full']));
	$file_details['ext'] = $file_part[count($file_part) - 1];
	$file_details['name'] = substr($file_details['name_full'], 0, strlen($file_details['name_full']) - strlen($file_details['ext']) - 1);
	return $file_details;
}

function get_dirs_path($full_path)
{
	$dirs_path = array();
	$path_parts[] = array();
	$file_path[] = array();
	$path_parts = pathinfo($full_path);
	$file_path = explode('/', $path_parts['dirname']);
	$dirs_path['indent'] = count($file_path);
	$dirs_path['name'] = $path_parts['dirname'];
	return $dirs_path;
}

function get_last_subfolder($full_path)
{
	$last_subfolder = array();
	$path_parts[] = array();
	$file_path[] = array();
	$path_parts = pathinfo($full_path);
	$file_path = explode('/', $path_parts['dirname']);
	//$last_subfolder['indent'] = count($file_path);
	$last_subfolder['indent'] = substr_count($full_path, '/') + 1;
	$last_subfolder['name'] = $file_path[$last_subfolder['indent'] - 1];
	return $last_subfolder;
}

function get_parent_subfolder($full_path)
{
	$parent_subfolder = array();
	$path_parts[] = array();
	$file_path[] = array();
	$path_parts = pathinfo($full_path);
	$file_path = explode('/', $path_parts['dirname']);
	//$last_subfolder['indent'] = count($file_path);
	$parent_subfolder['indent'] = substr_count($full_path, '/');
	if (count($file_path) >= 2)
	{
		$parent_subfolder['name'] = $file_path[$parent_subfolder['indent'] - 2] . '/';
	}
	elseif (count($file_path) == 1)
	{
		$parent_subfolder['name'] = '';
	}
	else
	{
		$parent_subfolder['name'] = '../';
	}
	return $parent_subfolder;
}

function create_files_list_full($mode, $path, $extensions = '')
{
	$directory = @opendir($path);
	$files_list = array();

	while (@$file = readdir($directory))
	{
		if (!in_array($file, array('.', '..')))
		{
			$is_dir = (is_dir($path . '/' . $file)) ? true : false;

			$temp_path = str_replace('//', '/', ($path . '/' . $file));

			$file_details = get_file_details($temp_path);
			$file_title = $file_details['name'];
			$file_type = strtolower($file_details['ext']);

			$process_file = false;
			if ($extensions == '')
			{
				$file_exclusions_array = array();
				//$file_exclusions_array = array('ace', 'bak', 'bmp', 'css', 'gif', 'hl', 'htc', 'htm', 'html', 'ico', 'jar', 'jpeg', 'jpg', 'js', 'pak', 'png', 'rar', 'sql', 'swf', 'tpl', 'ttf', 'txt', 'wmv', 'zip');
				if (!in_array($file_type, $file_exclusions_array))
				{
					$process_file = true;
				}
			}
			else
			{
				$file_inclusions_array = array();
				$file_inclusions_array = explode($extensions, ',');
				if (in_array($file_type, $file_inclusions_array))
				{
					$process_file = true;
				}
			}

			if ($process_file == true)
			{
				$files_list[] = $temp_path;
			}

			// Directory found, so recall this function
			if ($is_dir && ($mode == 'full'))
			{
				$files_list = array_merge($files_list, create_files_list_full($path . '/' . $file, $extensions));
			}
		}
	}

	@closedir($directory);

	return $files_list;
}

function create_files_list($dir, $process_subdirs = true, $list_subdirs = true)
{
	if (empty($dir))
	{
		die('Provide a valid root dir');
		exit;
	}

	$directory = @opendir($dir);
	$files_list = array();

	while (@$file = readdir($directory))
	{
		if (!in_array($file, array('.', '..')))
		{
			$is_dir = (is_dir($dir . '/' . $file)) ? true : false;

			$temp_path = str_replace('//', '/', ($dir . '/' . $file));

			if (!$is_dir || ($is_dir && $list_subdirs))
			{
				$files_list[] = $temp_path;
			}

			// Directory found, so recall this function
			if ($is_dir && $process_subdirs)
			{
				$files_list = array_merge($files_list, create_files_list($dir . '/' . $file), $process_subdirs);
			}
		}
	}

	@closedir($directory);
	sort($files_list);

	return $files_list;
}

function create_dirs_array($dir, $list_subdirs = true)
{
	if (empty($dir))
	{
		die('Provide a valid root dir');
		exit;
	}

	$directory = @opendir($dir);
	$dirs_list = array();

	while (@$file = readdir($directory))
	{
		if (!in_array($file, array('.', '..')))
		{
			if (is_dir($dir . '/' . $file))
			{
				$dirs_list[$file] = $dir . '/' . $file;
				if ($list_subdirs == true)
				{
					$dirs_list[$file] = create_dirs_array($dir . '/' . $file);
					ksort($dirs_list[$file]);
				}
			}
		}
	}

	ksort($dirs_list);
	@closedir($directory);
	return $dirs_list;
}

function parse_sub_dirs($dirs, $path = '')
{
	$subdirs_input_array = array();
	$subdirs_output_array = array();
	foreach ($dirs as $k => $v)
	{
		$subdirs_input_array[] = $path . $k;
		if (is_array($v))
		{
			$subdirs_input_array[] = parse_sub_dirs($v, $path . $k . '/');
		}
		$subdirs_input_array[] = $path . $k;
	}

	$subdirs_output_array = join_nested_arrays($subdirs_input_array);

	return $subdirs_output_array;
}

function join_nested_arrays($input_array)
{
	$output_array = array();
	$temp_array = array();
	while (list($k, $v) = each($input_array))
	{
		if (!is_array($v))
		{
			$output_array[] = $v;
		}
		else
		{
			$temp_array = join_nested_arrays($v);
			foreach ($temp_array as $kk=> $vv)
			{
				if (!is_array($vv))
				{
					$output_array[] = $vv;
				}
			}
		}
	}

	return $output_array;
}

function compare_files($source_path, $target_path)
{
	if (is_dir($source_path) || !file_exists($target_path))
	{
		return false;
	}

	$fs01 = filesize($source_path);
	$fs02 = filesize($target_path);
	//die("$fs01 - $fs02");

	$ft01 = filemtime($source_path);
	$ft02 = filemtime($target_path);
	//die("$ft01 - $ft02");

	if (($fs01 == $fs02) && ($ft01 == $ft02))
	{
		unlink($target_path);
		return true;
	}
	return false;
}

function scan_file_inc($file_path)
{
	$format = true;
	$lines_output = array();
	if(is_dir($file_path))
	{
		return $lines_output;
	}
	$lines = file($file_path);
	$color = '#DD2222';
	for ($i = 0; $i <= count($lines)-1; $i++)
	{
		$scanline = $lines[$i];
		// convert to lower case
		$scanline = strtolower($lines[$i]);

		// remove spaces, tabs, chr 13
		$look_up_array = array(
			' ',
			"\t",
			chr(13)
		);

		$replacement_array = array(
			'',
			'',
			''
		);

		$scanline = str_replace($look_up_array, $replacement_array, $scanline);

		$to_output = false;
		if(preg_match('/phpbb_root_path\=/', $scanline))
		{
			$color = '#228844';
			$to_output = true;
		}
		elseif(preg_match('/defined\(\'in_phpbb\'\)/', $scanline))
		{
			$color = '#228844';
			//$to_output = true;
		}
		elseif(preg_match('/include\(/', $scanline))
		{
			$to_output = true;
		}
		elseif(preg_match('/include_once\(/', $scanline))
		{
			$to_output = true;
		}
		elseif(preg_match('/require\(/', $scanline))
		{
			$to_output = true;
		}
		elseif(preg_match('/require_once\(/', $scanline))
		{
			$to_output = true;
		}
		elseif(preg_match('/phpbb_root_path/', $scanline))
		{
			$to_output = true;
		}
		elseif(preg_match('/album_root_path/', $scanline))
		{
			$to_output = true;
		}
		else
		{
			$to_output = false;
		}

		/*
		if(preg_match('/phpbb_root_path/', $scanline))
		{
			$to_output = false;
		}
		*/

		if(preg_match('/\(\'includes\/page_header/', $scanline))
		{
			$to_output = false;
		}
		elseif(preg_match('/\(\'includes\/page_tail/', $scanline))
		{
			$to_output = false;
		}
		elseif(preg_match('/\(\'\.\/pagestart/', $scanline) || preg_match('/\(\'pagestart/', $scanline))
		{
			$to_output = false;
		}
		elseif(preg_match('/\(\'\.\/page_header_admin/', $scanline) || preg_match('/\(\'page_header_admin/', $scanline))
		{
			$to_output = false;
		}
		elseif(preg_match('/\(\'\.\/page_footer_admin/', $scanline) || preg_match('/\(\'page_footer_admin/', $scanline))
		{
			$to_output = false;
		}

		if($to_output == true)
		{
			if ($format == true)
			{
				$look_up_array = array(
					'$phpbb_root_path',
					'in_phpbb',
				);

				$replacement_array = array(
					'<span style="color:' . $color . ';">$phpbb_root_path</span>',
					'<span style="color:' . $color . ';">IN_PHPBB</span>',
				);

				$scanline = str_replace($look_up_array, $replacement_array, $scanline);
			}
			$lines_output[] = $scanline;
		}
	}
	return $lines_output;
}

function scan_file_css($file_path)
{
	$lines_output = array();
	$lines = file($file_path);
	for ($i = 0; $i <= count($lines)-1; $i++)
	{
		$scanline = $lines[$i];
		// convert to lower case
		$scanline = strtolower($lines[$i]);

		// remove spaces, tabs, chr 13
		$look_up_array = array(
			' ',
			"\t",
			chr(13)
		);

		$replacement_array = array(
			'',
			'',
			''
		);

		$scanline = str_replace($look_up_array, $replacement_array, $scanline);

		if(preg_match('/url\(/', $scanline))
		{
			$lines_output[] = $scanline;
		}
	}
	return $lines_output;
}

function clean_garbage_css($input_string)
{
	$clean_string = $input_string;

	$look_up_array = array(
		'background-image:',
		'background:',
		'repeat-x',
		'repeat-y',
		'no-repeat',
		'transparenturl',
		'"'
	);

	$replacement_array = array(
		'',
		'',
		'',
		'',
		'',
		'url',
		'',
	);

	$clean_string = str_replace($look_up_array, $replacement_array, $clean_string);

	$reg_look_up_array = array(
		'/#[0-9,a-f]{6}/',
		'/#[0-9,a-f]{3}/',
		'/[0-9]{3}\%/',
		'/[0-9]{2}\%/',
		'/[0-9]{1}\%/',
		'/\)[0-9]/',
		'/[0-9]\;/',
		'/[0-9]{4}px\;/',
		'/[0-9]{3}px\;/',
		'/[0-9]{2}px\;/',
		'/[0-9]{1}px\;/',
	);

	$reg_replacement_array = array(
		'',
		'',
		'',
		'',
		'',
		')',
		';',
		';',
		';',
		';',
		';',
	);

	$clean_string = preg_replace($reg_look_up_array, $reg_replacement_array, $clean_string);

	$look_up_array = array(
		'scroll;',
		'url(',
		');',
	);

	$replacement_array = array(
		';',
		'',
		'',
	);

	$clean_string = str_replace($look_up_array, $replacement_array, $clean_string);

	return $clean_string;
}

?>