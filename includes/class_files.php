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

defined('CHMOD_ALL') ? true : @define('CHMOD_ALL', 7);
defined('CHMOD_READ') ? true : @define('CHMOD_READ', 4);
defined('CHMOD_WRITE') ? true : @define('CHMOD_WRITE', 2);
defined('CHMOD_EXECUTE') ? true : @define('CHMOD_EXECUTE', 1);

class files_management
{
	function file_output($file_filename, $file_content)
	{
		/*
		'r'  	 Open for reading only; place the file pointer at the beginning of the file.
		'r+' 	 Open for reading and writing; place the file pointer at the beginning of the file.
		'w' 	 Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
		'w+' 	 Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
		'a' 	 Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
		'a+' 	 Open for reading and writing; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
		'x' 	 Create and open for writing only; place the file pointer at the beginning of the file. If the file already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call.
		'x+' 	 Create and open for reading and writing; place the file pointer at the beginning of the file. If the file already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call.
		*/
		$fp = @fopen($file_filename, 'w');
		@flock($fp, LOCK_EX);
		@fwrite($fp, $file_content);
		@flock($fp, LOCK_UN);
		@fclose($fp);
		@chmod($file_filename, 0777);
		return true;
	}

	/*
	* Remove trailing slashes from path
	*/
	function remove_trailing_slashes($dir)
	{
		while(substr($dir, -1, 1) == '/')
		{
			$dir = substr($dir, 0, -1);
		}
		return $dir;
	}

	/*
	* Get File Details: name, extension
	*/
	function get_file_details($file_name)
	{
		$file_details = array();
		$file_tmp = str_replace(array('http://', 'https://'), array('', ''), $file_name);
		$file_path[] = array();
		$file_path = explode('/', $file_tmp);
		$file_details['name_full'] = $file_path[sizeof($file_path) - 1];
		$file_part = explode('.', strtolower($file_details['name_full']));
		$file_details['ext'] = $file_part[sizeof($file_part) - 1];
		$file_details['name'] = substr($file_details['name_full'], 0, strlen($file_details['name_full']) - strlen($file_details['ext']) - 1);
		return $file_details;
	}

	function get_file_extension($file)
	{
		return substr(strrchr($file, '.'), 1);
	}

	function get_dirs_path($full_path)
	{
		$dirs_path = array();
		$path_parts[] = array();
		$file_path[] = array();
		$path_parts = pathinfo($full_path);
		$file_path = explode('/', $path_parts['dirname']);
		$dirs_path['indent'] = sizeof($file_path);
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
		//$last_subfolder['indent'] = sizeof($file_path);
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
		//$last_subfolder['indent'] = sizeof($file_path);
		$parent_subfolder['indent'] = substr_count($full_path, '/');
		if (sizeof($file_path) >= 2)
		{
			$parent_subfolder['name'] = $file_path[$parent_subfolder['indent'] - 2] . '/';
		}
		elseif (sizeof($file_path) == 1)
		{
			$parent_subfolder['name'] = '';
		}
		else
		{
			$parent_subfolder['name'] = '../';
		}
		return $parent_subfolder;
	}

	function create_files_list_full($mode, $path, $include_types = false, $exclude_types = true)
	{
		$path = $this->remove_trailing_slashes($path);
		$include_types = empty($include_types) ? false : (is_array($include_types) ? $include_types : explode(',', $include_types));
		$exclude_types = empty($exclude_types) ? false : (is_array($exclude_types) ? $exclude_types : explode(',', $exclude_types));
		//$exclude_types = array('ace', 'bak', 'bmp', 'css', 'gif', 'hl', 'htc', 'htm', 'html', 'ico', 'jar', 'jpeg', 'jpg', 'js', 'pak', 'png', 'rar', 'sql', 'swf', 'tpl', 'ttf', 'txt', 'wmv', 'zip');

		$directory = @opendir($path);
		$files_list = array();

		while (@$file = @readdir($directory))
		{
			$process_file = false;
			if (!in_array($file, array('.', '..')))
			{
				$is_dir = (@is_dir($path . '/' . $file)) ? true : false;
				$temp_path = $path . '/' . $file;
				$file_details = $this->get_file_details($temp_path);
				$file_title = $file_details['name'];
				$file_type = strtolower($file_details['ext']);

				if (empty($include_types) && empty($exclude_types))
				{
					$process_file = true;
				}
				elseif (empty($include_types) && !empty($exclude_types))
				{
					$process_file = !in_array($file_type, $exclude_types) ? true : false;
				}
				elseif (!empty($include_types) && empty($exclude_types))
				{
					$process_file = in_array($file_type, $include_types) ? true : false;
				}
				else
				{
					$process_file = (in_array($file_type, $include_types) && !in_array($file_type, $exclude_types)) ? true : false;
				}

				if ($process_file)
				{
					$files_list[] = $temp_path;
				}

				// Directory found, so recall this function
				if ($is_dir && ($mode == 'full'))
				{
					$files_list = array_merge($files_list, $this->create_files_list_full($mode, $path . '/' . $file, $include_types, $exclude_types));
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

		$dir = $this->remove_trailing_slashes($dir);
		$directory = @opendir($dir);
		$files_list = array();

		while (@$file = @readdir($directory))
		{
			if (!in_array($file, array('.', '..')))
			{
				$is_dir = (@is_dir($dir . '/' . $file)) ? true : false;
				$temp_path = $dir . '/' . $file;
				if (!$is_dir || ($is_dir && $list_subdirs))
				{
					$files_list[] = $temp_path;
				}

				// Directory found, so recall this function
				if ($is_dir && $process_subdirs)
				{
					$files_list = array_merge($files_list, $this->create_files_list($dir . '/' . $file, $process_subdirs, $list_subdirs));
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

		$dir = $this->remove_trailing_slashes($dir);
		$directory = @opendir($dir);
		$dirs_list = array();

		while (@$file = @readdir($directory))
		{
			if (!in_array($file, array('.', '..')))
			{
				if (@is_dir($dir . '/' . $file))
				{
					$dirs_list[$file] = $dir . '/' . $file;
					if ($list_subdirs == true)
					{
						$dirs_list[$file] = $this->create_dirs_array($dir . '/' . $file, $list_subdirs);
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
				$subdirs_input_array[] = $this->parse_sub_dirs($v, $path . $k . '/');
			}
			$subdirs_input_array[] = $path . $k;
		}

		$subdirs_output_array = $this->join_nested_arrays($subdirs_input_array);

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
				$temp_array = $this->join_nested_arrays($v);
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

	function list_files($source_folder)
	{
		$source_folder = $this->remove_trailing_slashes($source_folder);
		$directory = @opendir($source_folder);
		$files_list = array();
		while (@$file = @readdir($directory))
		{
			$full_path_file = $source_folder . '/' . $file;
			if (!in_array($file, array('.', '..')))
			{
				if (@is_dir($source_folder . '/' . $file))
				{
					$files_list = array_merge($files_list, $this->list_files($full_path_file));
				}
				else
				{
					$files_list[] = $full_path_file;
				}
			}
		}
		@closedir($directory);
		sort($files_list);
		return $files_list;
	}

	function list_files_type($source_folder, $extension)
	{
		$source_folder = $this->remove_trailing_slashes($source_folder);
		$directory = @opendir($source_folder);
		$files_list = array();
		while (@$file = @readdir($directory))
		{
			$full_path_file = $source_folder . '/' . $file;
			if (!in_array($file, array('.', '..')))
			{
				if (@is_dir($source_folder . '/' . $file))
				{
					$files_list = array_merge($files_list, $this->list_files_type($full_path_file, $extension));
				}
				else
				{
					$file_details = $this->get_file_details($full_path_file);
					if ($file_details['ext'] == $extension)
					{
						$files_list[] = $full_path_file;
					}
				}
			}
		}
		@closedir($directory);
		sort($files_list);
		return $files_list;
	}

	/*
	* This function duplicates a folder with some options
	* $extensions: allowed extensions
	* $duplicate_subfolder: if set to true subfolders will be duplicated as well
	*/
	function duplicate_folder($source_folder, $target_folder, $extensions, $duplicate_subfolder = true)
	{
		$source_folder = $this->remove_trailing_slashes($source_folder);
		$target_folder = $this->remove_trailing_slashes($target_folder);
		$new_source_folder = $source_folder;
		$new_target_folder = $target_folder;
		$directory = @opendir($source_folder);
		while (@$file = @readdir($directory))
		{
			$full_path_file = $source_folder . '/' . $file;
			if (!in_array($file, array('.', '..')))
			{
				if (@is_dir($full_path_file))
				{
					if ($duplicate_subfolder == true)
					{
						$new_source_folder = $source_folder . '/' . $file;
						$new_target_folder = $target_folder . '/' . $file;
						@mkdir($new_target_folder, 0777);
						//echo('<br />' . $new_source_folder . ' - ' . $new_target_folder);
						$this->duplicate_folder($new_source_folder, $new_target_folder, $extensions, $duplicate_subfolder);
					}
				}
				else
				{
					if (!@is_dir($target_folder))
					{
						@mkdir($target_folder, 0777);
					}
					$current_file_extension = $this->get_file_extension($file);
					if (empty($extensions) || (!is_array($extensions) && ($current_file_extension == $extensions)) || (is_array($extensions) && in_array($current_file_extension, $extensions)))
					{
						if (@file_exists($target_folder . '/' . $file))
						{
							@unlink($target_folder . '/' . $file);
						}
						@copy($source_folder . '/' . $file, $target_folder . '/' . $file);
					}
				}
			}
		}
	}

	function create_subfolders_structure($path, $chmod = 0777)
	{
		$tmp_paths = array();
		$tmp_paths = explode('/', $path);
		$recursive_path = '';
		for ($i = 0; $i < sizeof($tmp_paths); $i++)
		{
			if (!empty($tmp_paths[$i]))
			{
				$recursive_path = (!empty($recursive_path) ? ($recursive_path . '/') : '') . $tmp_paths[$i];
				if (!@is_dir($recursive_path))
				{
					@mkdir($recursive_path, $chmod);
				}
			}
		}
		return true;
	}

	function remove_files($dir, $include_types = false, $exclude_types = false)
	{
		$dir = $this->remove_trailing_slashes($dir);
		$include_types = empty($include_types) ? false : (is_array($include_types) ? $include_types : explode(',', $include_types));
		$exclude_types = empty($exclude_types) ? false : (is_array($exclude_types) ? $exclude_types : explode(',', $exclude_types));

		$res = @opendir($dir);
		if($res === false) return;
		while(($file = @readdir($res)) !== false)
		{
			$remove_file = false;
			if(($file !== '.') && ($file !== '..'))
			{
				$file_full_path = $dir . '/' . $file;
				if(!@is_dir($file_full_path) && !@is_link($file_full_path))
				{
					$current_file_extension = $this->get_file_extension($file_full_path);
					if (empty($include_types) && empty($exclude_types))
					{
						$remove_file = true;
					}
					else
					{
						if (!empty($include_types) && !empty($exclude_types))
						{
							if (in_array($current_file_extension, $include_types) && !in_array($current_file_extension, $exclude_types))
							{
								$remove_file = true;
							}
						}
						else
						{
							if (!$remove_file && !empty($include_types) && in_array($current_file_extension, $include_types))
							{
								$remove_file = true;
							}
							if (!$remove_file && !empty($exclude_types) && !in_array($current_file_extension, $exclude_types))
							{
								$remove_file = true;
							}
						}
					}

					if ($remove_file)
					{
						@unlink($file_full_path);
					}
				}
			}
		}
		@closedir($res);
	}

	function clear_dir($dir)
	{
		$dir = $this->remove_trailing_slashes($dir);
		$res = @opendir($dir);
		if($res === false) return;
		while(($file = @readdir($res)) !== false)
		{
			if(($file !== '.') && ($file !== '..'))
			{
				if(@is_dir($dir . '/' . $file))
				{
					$this->clear_dir($dir . '/' . $file);
					@rmdir($dir . '/' . $file);
				}
				else
				{
					@unlink($dir . '/' . $file);
				}
			}
		}
		@closedir($res);
	}

	function empty_data_folder($dir)
	{
		$dir = $this->remove_trailing_slashes($dir);
		$skip_files = array(
			'.',
			'..',
			'.htaccess',
			'index.html',
		);

		$dir = $this->remove_trailing_slashes($dir);
		$res = @opendir($dir);
		while(@$file = @readdir($res))
		{
			if (!in_array($file, $skip_files))
			{
				$res2 = @unlink($dir . '/' . $file);
			}
		}
		@closedir($res);
		return true;
	}

	function compare_files($source_path, $target_path)
	{
		if (@is_dir($source_path) || !@file_exists($target_path))
		{
			return false;
		}

		$fs01 = @filesize($source_path);
		$fs02 = @filesize($target_path);
		//die("$fs01 - $fs02");

		$ft01 = @filemtime($source_path);
		$ft02 = @filemtime($target_path);
		//die("$ft01 - $ft02");

		if (($fs01 == $fs02) && ($ft01 == $ft02))
		{
			@unlink($target_path);
			return true;
		}
		return false;
	}

/*
	function scan_file_inc($file_path)
	{
		$format = true;
		$lines_output = array();
		if(@is_dir($file_path))
		{
			return $lines_output;
		}
		$lines = file($file_path);
		$color = '#dd2222';
		for ($i = 0; $i <= sizeof($lines)-1; $i++)
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
			if(preg_match('/\'ip_root_path\'/', $scanline))
			{
				$color = '#228822';
				$to_output = true;
			}
			elseif(preg_match('/defined\(\'in_icyphoenix\'\)/', $scanline))
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
			elseif(preg_match('/ip_root_path/', $scanline))
			{
				$to_output = true;
			}
			else
			{
				$to_output = false;
			}

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
						'ip_root_path',
						'in_icyphoenix',
					);

					$replacement_array = array(
						'<span style="color:' . $color . ';">IP_ROOT_PATH</span>',
						'<span style="color:' . $color . ';">IN_ICYPHOENIX</span>',
					);

					$scanline = str_replace($look_up_array, $replacement_array, $scanline);
				}
				$lines_output[] = $scanline;
			}
		}
		return $lines_output;
	}
*/

/*
	function scan_file_css($file_path)
	{
		$lines_output = array();
		$lines = file($file_path);
		for ($i = 0; $i <= sizeof($lines)-1; $i++)
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
*/

	// usage: $result = gzcompressfile('my_data.sql');
	function gzcompressfile($source, $level = false)
	{
		$dest = $source . '.gz';
		$mode = 'wb' . $level;
		$error = false;
		if($fp_out = gzopen($dest, $mode))
		{
			if($fp_in = @fopen($source, 'rb'))
			{
				while(!@feof($fp_in))
				{
					gzwrite($fp_out, @fread($fp_in, 1024 * 512));
				}
				@fclose($fp_in);
			}
			else
			{
				$error = true;
			}
			gzclose($fp_out);
		}
		else
		{
			$error = true;
		}
		if($error)
		{
			return false;
		}
		else
		{
			return $dest;
		}
	}

	/**
	* Global function for chmodding directories and files for internal use
	* This function determines owner and group whom the file belongs to and user and group of PHP and then set safest possible file permissions.
	* The function determines owner and group from common.php file and sets the same to the provided file. Permissions are mapped to the group, user always has rw(x) permission.
	* The function uses bit fields to build the permissions.
	* The function sets the appropiate execute bit on directories.
	*
	* Supported constants representing bit fields are:
	*
	* CHMOD_ALL - all permissions (7)
	* CHMOD_READ - read permission (4)
	* CHMOD_WRITE - write permission (2)
	* CHMOD_EXECUTE - execute permission (1)
	*
	* NOTE: The function uses POSIX extension and fileowner()/filegroup() functions. If any of them is disabled, this function tries to build proper permissions, by calling is_readable() and is_writable() functions.
	*
	* @param $filename The file/directory to be chmodded
	* @param $perms Permissions to set
	* @return true on success, otherwise false
	*
	* @author faw, phpBB Group
	*/
	function ip_chmod($filename, $perms = CHMOD_READ)
	{
		// Return if the file no longer exists.
		if (!@file_exists($filename))
		{
			return false;
		}

		if (!function_exists('fileowner') || !function_exists('filegroup'))
		{
			$file_uid = $file_gid = false;
			$common_php_owner = $common_php_group = false;
		}
		else
		{
			// Determine owner/group of this file and the filename we want to change here
			$common_php_owner = fileowner(__FILE__);
			$common_php_group = filegroup(__FILE__);

			$file_uid = fileowner($filename);
			$file_gid = filegroup($filename);

			// Try to set the owner to the same common.php has
			if (($common_php_owner !== $file_uid) && ($common_php_owner !== false) && ($file_uid !== false))
			{
				// Will most likely not work
				if (@chown($filename, $common_php_owner));
				{
					clearstatcache();
					$file_uid = fileowner($filename);
				}
			}

			// Try to set the group to the same common.php has
			if (($common_php_group !== $file_gid) && ($common_php_group !== false) && ($file_gid !== false))
			{
				if (@chgrp($filename, $common_php_group));
				{
					clearstatcache();
					$file_gid = filegroup($filename);
				}
			}
		}

		// And the owner and the groups PHP is running under.
		$php_uid = (function_exists('posix_getuid')) ? @posix_getuid() : false;
		$php_gids = (function_exists('posix_getgroups')) ? @posix_getgroups() : false;

		// Who is PHP?
		if (($file_uid === false) || ($file_gid === false) || ($php_uid === false) || ($php_gids === false))
		{
			$php = NULL;
		}
		elseif (($file_uid == $php_uid) /* && ($common_php_owner !== false) && ($common_php_owner === $file_uid) */)
		{
			$php = 'owner';
		}
		elseif (in_array($file_gid, $php_gids))
		{
			$php = 'group';
		}
		else
		{
			$php = 'other';
		}

		// Owner always has read/write permission
		$owner = CHMOD_READ | CHMOD_WRITE;
		if (@is_dir($filename))
		{
			$owner |= CHMOD_EXECUTE;

			// Only add execute bit to the permission if the dir needs to be readable
			if ($perms & CHMOD_READ)
			{
				$perms |= CHMOD_EXECUTE;
			}
		}

		switch ($php)
		{
			case null:
			case 'owner':
				/*
				//ATTENTION: if php is owner or NULL we set it to group here. This is the most failsafe combination for the vast majority of server setups.

				$result = @chmod($filename, ($owner << 6) + (0 << 3) + (0 << 0));

				clearstatcache();

				if (!is_null($php) || (is_readable($filename) && is_writable($filename)))
				{
					break;
				}
			*/

			case 'group':
				$result = @chmod($filename, ($owner << 6) + ($perms << 3) + (0 << 0));

				clearstatcache();

				if (!is_null($php) || ((!($perms & CHMOD_READ) || is_readable($filename)) && (!($perms & CHMOD_WRITE) || is_writable($filename))))
				{
					break;
				}

			case 'other':
				$result = @chmod($filename, ($owner << 6) + ($perms << 3) + ($perms << 0));

				clearstatcache();

				if (!is_null($php) || ((!($perms & CHMOD_READ) || is_readable($filename)) && (!($perms & CHMOD_WRITE) || is_writable($filename))))
				{
					break;
				}

			default:
				return false;
			break;
		}

		return $result;
	}
}

?>