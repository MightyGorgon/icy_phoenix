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

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('MAIN_CACHE_FOLDER')) define('MAIN_CACHE_FOLDER', IP_ROOT_PATH . 'cache/');
if (!defined('UPLOADS_CACHE_FOLDER')) define('UPLOADS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'uploads/');
if (!defined('POSTED_IMAGES_PATH')) define('POSTED_IMAGES_PATH', IP_ROOT_PATH . 'files/images/');
if (!defined('POSTED_IMAGES_THUMBS_PATH')) define('POSTED_IMAGES_THUMBS_PATH', IP_ROOT_PATH . 'files/thumbs/');
if (!defined('POSTED_IMAGES_THUMBS_S_PATH')) define('POSTED_IMAGES_THUMBS_S_PATH', POSTED_IMAGES_THUMBS_PATH . 's/');
/*
*/

if (!defined('CHMOD_ALL')) define('CHMOD_ALL', 7);
if (!defined('CHMOD_READ')) define('CHMOD_READ', 4);
if (!defined('CHMOD_WRITE')) define('CHMOD_WRITE', 2);
if (!defined('CHMOD_EXECUTE')) define('CHMOD_EXECUTE', 1);

if (!defined('FILE_UPLOAD_NOT_UPLOADED')) define('FILE_UPLOAD_NOT_UPLOADED', 0);
if (!defined('FILE_UPLOAD_UPLOADED')) define('FILE_UPLOAD_UPLOADED', 1);
if (!defined('FILE_UPLOAD_ERROR')) define('FILE_UPLOAD_ERROR', 2);
if (!defined('FILE_UPLOAD_TOO_BIG')) define('FILE_UPLOAD_TOO_BIG', 3);
if (!defined('FILE_UPLOAD_TYPE_ERROR')) define('FILE_UPLOAD_TYPE_ERROR', 4);

/**
* Files management class
*/
class class_files
{
	var $files = array();
	var $files_list = array();
	var $cache_folder = '';
	var $uploads_folder = '';
	var $temp_folder = '';

	var $allowed_extensions = array();
	var $disallowed_extensions = array();
	var $files_to_skip = array();

	var $max_size = 128000;
	var $max_width = 600;
	var $max_height = 600;

	var $rand_seed = 9999;

	/**
	* Class initialization
	*/
	function __construct()
	{
		$this->cache_folder = MAIN_CACHE_FOLDER;
		$this->uploads_folder = UPLOADS_CACHE_FOLDER;
		$this->temp_folder = $this->uploads_folder . time() . '/';

		$this->allowed_extensions = array('gif', 'jpeg', 'jpg', 'png');
		$this->disallowed_extensions = array('asp', 'php', 'htm', 'html');
		$this->files_to_skip = array('.', '..');
	}

	/*
	* Removes trailing slashes from path
	*/
	function remove_trailing_slashes($dir)
	{
		while(substr($dir, -1, 1) == '/')
		{
			$dir = substr($dir, 0, -1);
		}
		return $dir;
	}

	/**
	* Return unique id
	* @param string $extra additional entropy
	*/
	function unique_id($extra = 'mg')
	{
		$val = $this->rand_seed . microtime();
		$val = strtolower(md5($val));
		$this->rand_seed = md5($this->rand_seed . $val . $extra);

		return substr($val, 4, 16);
	}

	/**
	* Sanitizes a string to only lowercase letters, numbers and underscore
	*/
	function clean_string($string, $lowercase = true)
	{
		$string = !empty($lowercase) ? preg_replace('/[^a-z0-9]+/', '_', strtolower($string)) : preg_replace('/[^A-Za-z0-9]+/', '_', $string);
		return $string;
	}

	/**
	* Sanitizes a string for JavaScript
	*/
	function clean_string_js($string)
	{
		$string = str_replace(array("'", '/'), array("\'", '\/'), $string);
		return $string;
	}

	/**
	* Generates all file details
	*/
	function get_file_details($file_name)
	{
		$file_details = array();
		$file_details = pathinfo($file_name);
		$file_details['clean_name'] = $this->clean_string($file_details['filename'], true) . (!empty($file_details['extension']) ? ('.' . $this->clean_string($file_details['extension'])) : '');
		return $file_details;
	}

	/*
	* Get only file extension
	*/
	function get_file_extension($file_name)
	{
		return substr(strrchr($file_name, '.'), 1);
	}

	/**
	* Creates a temporary dir to be removed later after all processing
	*/
	function create_temp_dir($dir_name = '')
	{
		$return = false;

		if (empty($dir_name))
		{
			$dir_name = time() . '_' . $this->unique_id();
		}

		$dir_name = $this->clean_string($dir_name, true);
		$dir_full_path = $this->uploads_folder . $dir_name;
		while(@is_dir($dir_full_path))
		{
			$dir_full_path = $this->uploads_folder . time() . '_' . $this->unique_id();
		}

		$creation_result = @mkdir($dir_full_path, 0666);
		if ($creation_result)
		{
			$this->temp_folder = $dir_full_path . '/';
			$return = $this->temp_folder;
		}

		return $return;
	}

	/**
	* Checks if a file already exists in a dir and in case generates a new filename
	*/
	function generate_file_name($target_dir, $file_name)
	{
		$file_details = $this->get_file_details($file_name);
		if (!empty($target_dir))
		{
			$target_dir = $this->remove_trailing_slashes($target_dir) . '/';
		}
		$file_full_path = $target_dir . $file_details['clean_name'];
		while (@file_exists($file_full_path))
		{
			$file_full_path = $target_dir . $this->clean_string($file_details['filename'], true) . '_' . time() . '_' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT) . '.' . $this->clean_string($file_details['extension']);
		}
		return $file_full_path;
	}

	/*
	* Get directory information
	*/
	function get_dirs_path($full_path)
	{
		$dirs_path = array();
		$path_parts[] = array();
		$file_path[] = array();

		$path_parts = pathinfo($full_path);
		$file_path = explode('/', $path_parts['dirname']);

		$dirs_path['indent'] = sizeof($file_path);
		$dirs_path['name'] = $path_parts['dirname'];
		$dirs_path['last_subfolder_indent'] = substr_count($full_path, '/') + 1;
		$dirs_path['last_subfolder_name'] = $file_path[$last_subfolder['indent'] - 1];

		return $dirs_path;
	}

	/*
	* Get parent subfolder info
	*/
	function get_parent_subfolder($full_path)
	{
		$parent_subfolder = array();
		$path_parts[] = array();
		$file_path[] = array();

		$path_parts = pathinfo($full_path);
		$file_path = explode('/', $path_parts['dirname']);

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

	/*
	* Write some content to a file
	*/
	function file_output($file_name, $file_content, $flag = 'w')
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
		$flags_array = array('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+');
		$flag = (empty($flag) || !in_array($flag, $flags_array)) ? 'w' : $flag;

		$fp = @fopen($file_name, $flag);
		@flock($fp, LOCK_EX);
		@fwrite($fp, $file_content);
		@flock($fp, LOCK_UN);
		@fclose($fp);
		@chmod($file_name, 0777);

		return true;
	}

	/*
	* Create an array of directories
	*/
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
		@closedir($directory);
		ksort($dirs_list);

		return $dirs_list;
	}

	/*
	* Pars all subdirs in a path
	*/
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

	/*
	* Join nested arrays
	*/
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

	/*
	* List all files
	*/
	function list_files($dir, $recursive = true, $allowed_extensions = false, $disallowed_extensions = false)
	{
		$dir = $this->remove_trailing_slashes($dir);
		$directory = @opendir($dir);
		$files_list = array();

		if (!empty($allowed_extensions))
		{
			$allowed_extensions = is_array($allowed_extensions) ? $allowed_extensions : array($allowed_extensions);
			$allowed_extensions = array_map('strtolower', $allowed_extensions);
		}

		if (!empty($disallowed_extensions))
		{
			$disallowed_extensions = is_array($disallowed_extensions) ? $disallowed_extensions : array($disallowed_extensions);
			$disallowed_extensions = array_map('strtolower', $disallowed_extensions);
			//$disallowed_extensions = array('ace', 'bak', 'bmp', 'css', 'gif', 'hl', 'htc', 'htm', 'html', 'ico', 'jar', 'jpeg', 'jpg', 'js', 'pak', 'png', 'rar', 'sql', 'swf', 'tpl', 'ttf', 'txt', 'wmv', 'zip');
		}

		while (@$file = @readdir($directory))
		{
			$full_path_file = $dir . '/' . $file;
			if (!in_array($file, array('.', '..')))
			{
				if (@is_dir($dir . '/' . $file))
				{
					if (!empty($recursive))
					{
						$files_list = array_merge($files_list, $this->list_files($full_path_file, $recursive, $allowed_extensions, $disallowed_extensions));
					}
				}
				else
				{
					$process_file = true;
					if (!empty($allowed_extensions) || !empty($disallowed_extensions))
					{
						$process_file = false;
						$file_details = $this->get_file_details($full_path_file);

						if (!empty($disallowed_extensions) && !in_array(strtolower($file_details['extension']), $disallowed_extensions))
						{
							$process_file = true;
						}

						if (!empty($file_details['extension']) && !empty($allowed_extensions) && in_array(strtolower($file_details['extension']), $allowed_extensions))
						{
							$process_file = true;
						}
					}

					if ($process_file)
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

	/**
	* Creates an images list from a folder
	*/
	function create_images_list($source_path, $allowed_image_types = '')
	{
		$allowed_image_types = empty($allowed_image_types) ? array('gif', 'jpg', 'jpeg', 'png') : (array) $allowed_image_types;

		// Re-define some keys for getimagesize
		// Defines the keys we want instead of 0, 1, 2, 3, 'bits', 'channels', and 'mime'.
		$image_data_keys = array(
			'width',
			'height',
			'type',
			'attr',
			'bits',
			'channels',
			'mime'
		);

		// Assign useful values for the third index.
		$image_types = array(
			1 => 'gif',
			2 => 'jpg',
			3 => 'png',
			4 => 'swf',
			5 => 'psd',
			6 => 'bmp',
			7 => 'tiff (intel byte order)',
			8 => 'tiff (motorola byte order)',
			9 => 'jpc',
			10 => 'jp2',
			11 => 'jpx',
			12 => 'jb2',
			13 => 'swc',
			14 => 'iff',
			15 => 'wbmp',
			16 => 'xbm'
		);

		$images_list = array();
		if (@is_dir($source_path))
		{
			$files_list = $this->list_files($source_path, false, $allowed_image_types, false);
			foreach ($files_list as $image)
			{
				$temp = array();
				$data = array();
				$temp = @getimagesize($image);
				if(!empty($temp))
				{
					// Convert keys to numbers
					$temp = array_values($temp);

					// Make an array using values from $redefine_keys as keys and values from $temp as values.
					foreach ($temp as $k => $v)
					{
						$image_data[$image_data_keys[$k]] = $v;
					}

					// Convert the image type
					$image_data['type'] = $image_types[$image_data['type']];

					$process_image = (empty($image_data['width']) || empty($image_data['height']) || !in_array($image_data['type'], $allowed_image_types)) ? false : true;
					if ($process_image)
					{
						$image_size = @filesize($image);
						$images_list[] = array(
							'src' => $image,
							'width' => (int) $image_data['width'],
							'height' => (int) $image_data['height'],
							'type' => $image_data['type'],
							'size' => (int) $image_size
						);
					}
				}
			}
		}

		return $images_list;
	}

	/*
	* Gets the number of files
	*/
	function get_number_of_files($dir, $recursive = true, $allowed_extensions = false, $disallowed_extensions = false)
	{
		$dir = $this->remove_trailing_slashes($dir);
		if (empty($this->files_list))
		{
			$this->files_list = $this->list_files($dir, $recursive, $allowed_extensions, $disallowed_extensions);
		}
		$files_count = sizeof($this->files_list);

		return $files_count;
	}

	/*
	* Gets the dir size
	*/
	function get_dir_size($dir, $recursive = true, $allowed_extensions = false, $disallowed_extensions = false)
	{
		$dir = $this->remove_trailing_slashes($dir);
		if (empty($this->files_list))
		{
			$this->files_list = $this->list_files($dir, $recursive, $allowed_extensions, $disallowed_extensions);
		}

		$dir_size = 0;
		foreach ($this->files_list as $file)
		{
			$dir_size += (int) @filesize($file);
		}

		return $dir_size;
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
		@closedir($directory);
	}

	/*
	* Duplicate an exact subfolder structure
	*/
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

	/*
	* Compare files by size and time
	*/
	function basic_compare_files($source_path, $target_path)
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
	* Remove files
	*/
	function remove_files($dir, $include_types = false, $exclude_types = false)
	{
		$dir = $this->remove_trailing_slashes($dir);
		$include_types = empty($include_types) ? false : (is_array($include_types) ? $include_types : explode(',', $include_types));
		$exclude_types = empty($exclude_types) ? false : (is_array($exclude_types) ? $exclude_types : explode(',', $exclude_types));
		$files_to_skip = array('.', '..');

		$res = @opendir($dir);
		if($res === false) return;
		while(($file = @readdir($res)) !== false)
		{
			$remove_file = false;
			if(!in_array($file, $files_to_skip))
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

	/**
	* Page Header
	*/
	function simple_page_header($page_title)
	{
		echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n");
		echo('<html>' . "\n");
		echo('<head>' . "\n");
		echo('	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n");
		echo('	<meta name="author" content="Mighty Gorgon" />' . "\n");
		echo('	<title>' . (!empty($page_title) ? $page_title : 'Icy Phoenix') . '</title>' . "\n");
		echo('</head>' . "\n");
		echo('<body>' . "\n");
	}

	/**
	* Page Footer
	*/
	function simple_page_footer()
	{
		echo('</body>' . "\n");
		echo('</html>' . "\n");
	}

	/**
	* Explode any single-dimensional array into a full blown tree structure, based on the delimiters found in it's keys.
	*
	* @author    Kevin van Zonneveld <kevin@vanzonneveld.net>
	* @author    Lachlan Donald
	* @author    Takkie
	* @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	* @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
	* @version   SVN: Release: $Id: explodeTree.inc.php 89 2008-09-05 20:52:48Z kevin $
	* @link      http://kevin.vanzonneveld.net/
	*
	* @param array   $array
	* @param string  $delimiter
	* @param boolean $baseval
	*
	* @return array
	*/
	/*
	if(exec("find /etc/php5", $files))
	{
		// the $files array now holds the path as it's values, but we also want the paths as keys:
		$key_files = array_combine(array_values($files), array_values($files));
		// show the array
		print_r($key_files);
	}
	// let '/' be our delimiter
	$tree = explode_tree($key_files, "/");
	// show the array
	print_r($tree);
	// now the 3rd argument, the baseval, is true
	$tree = explode_tree($key_files, "/", true);
	*/
	function explode_tree($array, $delimiter = '_', $baseval = false)
	{
		if(!is_array($array)) return false;
		$split_re  = '/' . preg_quote($delimiter, '/') . '/';
		$return_array = array();
		foreach ($array as $key => $val)
		{
			// Get parent parts and the current leaf
			$parts = preg_split($split_re, $key, -1, PREG_SPLIT_NO_EMPTY);
			$leaf_part = array_pop($parts);

			// Build parent structure
			// Might be slow for really deep and large structures
			$parent_array = &$return_array;
			foreach ($parts as $part)
			{
				if (!isset($parent_array[$part]))
				{
					$parent_array[$part] = array();
				}
				elseif (!is_array($parent_array[$part]))
				{
					if ($baseval)
					{
						$parent_array[$part] = array('__base_val' => $parent_array[$part]);
					}
					else
					{
						$parent_array[$part] = array();
					}
				}
				$parent_array = &$parent_array[$part];
			}

			// Add the final part to the structure
			if (empty($parent_array[$leaf_part]))
			{
				$parent_array[$leaf_part] = $val;
			}
			elseif ($baseval && is_array($parent_array[$leaf_part]))
			{
				$parent_array[$leaf_part]['__base_val'] = $val;
			}
		}

		return $return_array;
	}

	function plot_tree($arr, $indent = 0, $mother_run = true)
	{
		if($mother_run)
		{
			// the beginning of plotTree. We're at rootlevel
			echo "start\n";
		}

		foreach($arr as $k => $v)
		{
			// skip the baseval thingy. Not a real node.
			if($k == '__base_val') continue;
			// determine the real value of this node.
			$show_val = ( is_array($v) ? $v['__base_val'] : $v );

			// show the indents
			echo str_repeat('  ', $indent);
			if($indent == 0)
			{
				// this is a root node. no parents
				echo 'O ';
			}
			elseif(is_array($v))
			{
				// this is a normal node. parents and children
				echo '+ ';
			}
			else
			{
				// this is a leaf node. no children
				echo '- ';
			}

			// show the actual node
			echo $k . ' (' . $show_val . ')' . "\n";

			if(is_array($v))
			{
				// this is what makes it recursive, rerun for childs
				$this->plot_tree($v, ($indent + 1), false);
			}
		}

		if($mother_run)
		{
			echo "end\n";
		}
	}

	function bytes_to_string($size, $precision = 2)
	{
		$sizes = array('YB', 'ZB', 'EB', 'PB', 'TB', 'GB', 'MB', 'KB', 'Bytes');
		$total = count($sizes);

		while($total-- && ($size > 1024)) $size /= 1024;
		return round($size, $precision) . " " . $sizes[$total];
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

	/**
	* Recursive CHMOD re-set for files that cannot be handled via FTP because of different owner
	* Usage: $this->rchmod($dir, 0777, 0777);
	*/
	function rchmod($resource, $dmod = 0666, $fmod = 0666)
	{
		$resource = $this->remove_trailing_slashes($resource);
		$files_to_skip = array('.', '..');

		if (@is_dir($resource))
		{
			$old = @umask(0000);
			@chmod($resource, $dmod);
			@umask($old);
			$res = @opendir($resource);
			if($res === false) return false;
			while(($file = @readdir($res)) !== false)
			{
				$filename = $resource . '/' . $file;
				if(!in_array($file, $files_to_skip) && !@is_link($filename))
				{
					if (@is_dir($filename))
					{
						$this->rchmod($filename, $dmod, $fmod);
					}
					else
					{
						$old = @umask(0000);
						@chmod($filename, $fmod);
						@umask($old);
					}
				}
			}
			@closedir($res);
		}
		else
		{
			$old = @umask(0000);
			@chmod($resource, $fmod);
			@umask($old);
		}
	}

	/*
	* Clean and remove temporary folder
	*/
	function cleanup($dir, $files_to_skip = false, $self_remove = true, $recursive = true, $sub_remove = true)
	{
		$dir = $this->remove_trailing_slashes($dir);
		if (!empty($self_remove))
		{
			$files_to_skip = array('.', '..');
			$recursive = true;
			$sub_remove = true;
		}
		else
		{
			$files_to_skip = empty($files_to_skip) ? (array) $this->files_to_skip : array_merge((array) $this->files_to_skip, (array) $files_to_skip);
			$files_to_skip = array_unique(array_merge(array('.', '..'), (array) $files_to_skip));
		}

		$res = @opendir($dir);
		if($res === false) return false;
		while(($file = @readdir($res)) !== false)
		{
			$filename = $dir . '/' . $file;
			if(!in_array($file, $files_to_skip) && !@is_link($filename))
			{
				if(@is_dir($filename))
				{
					if (!empty($recursive))
					{
						$files_to_skip_recursive = !empty($sub_remove) ? array('.', '..') : $files_to_skip;
						$this->cleanup($filename, $files_to_skip_recursive, $self_remove, $recursive, $sub_remove);
						if (!empty($sub_remove))
						{
							$this->rchmod($filename);
							@rmdir($filename);
						}
					}
				}
				else
				{
					$this->rchmod($filename);
					@unlink($filename);
				}
			}
		}
		@closedir($res);

		if (!empty($self_remove))
		{
			$this->rchmod($dir);
			@rmdir($dir);
		}
		return true;
	}

	/*
	* Empty directory contents
	*/
	function clear_dir($dir)
	{
		$files_to_skip = array('.', '..');
		$this->cleanup($dir, $files_to_skip, false, true, true);
		return true;
	}

	/*
	* Empty data folder preserving some core files
	*/
	function empty_data_folder($dir)
	{
		$files_to_skip = array('.', '..', '.htaccess', 'index.html');
		$this->cleanup($dir, $files_to_skip, false, false, false);
		return true;
	}

}

?>