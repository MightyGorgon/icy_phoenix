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

if (!defined('POSTED_IMAGES_PATH')) define('POSTED_IMAGES_PATH', IP_ROOT_PATH . 'files/images/');
if (!defined('POSTED_IMAGES_THUMBS_PATH')) define('POSTED_IMAGES_THUMBS_PATH', IP_ROOT_PATH . 'files/thumbs/');
if (!defined('POSTED_IMAGES_THUMBS_S_PATH')) define('POSTED_IMAGES_THUMBS_S_PATH', POSTED_IMAGES_THUMBS_PATH . 's/');

if (!defined('FILE_UPLOAD_NOT_UPLOADED')) define('FILE_UPLOAD_NOT_UPLOADED', 0);
if (!defined('FILE_UPLOAD_UPLOADED')) define('FILE_UPLOAD_UPLOADED', 1);
if (!defined('FILE_UPLOAD_ERROR')) define('FILE_UPLOAD_ERROR', 2);
if (!defined('FILE_UPLOAD_TOO_BIG')) define('FILE_UPLOAD_TOO_BIG', 3);
if (!defined('FILE_UPLOAD_TYPE_ERROR')) define('FILE_UPLOAD_TYPE_ERROR', 4);

/**
* Files upload management class
*/
class class_files_upload extends class_files
{
	var $target_folder = '';
	var $thumbs_folder = '';

	/**
	* Class initialization
	*/
	function __construct()
	{
		$this->target_folder = POSTED_IMAGES_PATH;
		$this->thumbs_folder = POSTED_IMAGES_THUMBS_PATH;
	}

	/*
	* Upload the file
	*/
	function upload($should_be_image = false)
	{
		$return_array = array(
			'result' => FILE_UPLOAD_NOT_UPLOADED,
			'name' => '',
			'full_path' => '',
			'size' => 0,
			'width' => 0,
			'height' => 0,
			'is_image' => false
		);

		$file_uploaded = false;
		$file_name = basename($_FILES[$file_var_name]['name']);
		$file_tmp_name = $_FILES[$file_var_name]['tmp_name'];
		$file_size = $_FILES[$file_var_name]['size'];
		$file_details = $this->get_file_details($file_name);

		if (!in_array($file_details['extension'], $this->allowed_extensions) || in_array($file_details['extension'], $this->disallowed_extensions))
		{
			$return_array['result'] = FILE_UPLOAD_TYPE_ERROR;
			return $return_array;
		}

		if($file_size < $this->max_size)
		{
			if (!@is_dir($this->temp_folder))
			{
				$this->create_temp_dir();
			}
			$temp_file_name = $this->generate_file_name($this->temp_folder, $file_name);
			$target_file_name = $this->generate_file_name($this->target_folder, $file_name);
			if(@is_uploaded_file($file_tmp_name))
			{
				if (@move_uploaded_file($file_tmp_name, $temp_file_name))
				{
					@chmod($temp_file_name, 0666);

					$uploaded_file_size = @filesize($temp_file_name);
					if(empty($uploaded_file_size) || ($uploaded_file_size > $this->max_size))
					{
						@unlink($temp_file_name);
						$this->cleanup($this->temp_folder);
						$return_array['result'] = FILE_UPLOAD_TOO_BIG;
						return $return_array;
					}
					$return_array['size'] = $uploaded_file_size;

					if ($should_be_image)
					{
						$image_size = @getimagesize($temp_file_name);
						if(empty($image_size))
						{
							@unlink($temp_file_name);
							$this->cleanup($this->temp_folder);
							$return_array['result'] = FILE_UPLOAD_TYPE_ERROR;
							return $return_array;
						}
						else
						{
							if (empty($image_size[0]) || ($image_size[0] > $this->max_width) || empty($image_size[1]) || ($image_size[1] > $this->max_height))
							{
								@unlink($temp_file_name);
								$this->cleanup($this->temp_folder);
								$return_array['result'] = FILE_UPLOAD_TOO_BIG;
								return $return_array;
							}
							$return_array['width'] = $image_size[0];
							$return_array['height'] = $image_size[1];
							$file_uploaded = true;
						}
					}
					else
					{
						$file_uploaded = true;
					}

					if ($file_uploaded)
					{
						$move_result = @rename($temp_file_name, $target_file_name);
						if (!empty($move_result))
						{
							@chmod($target_file_name, 0777);
							$return_array['result'] = FILE_UPLOAD_UPLOADED;
							$return_array['name'] = basename($target_file_name);
							$return_array['full_path'] = $target_file_name;
							return $return_array;
						}
						else
						{
							@unlink($temp_file_name);
							$this->cleanup($this->temp_folder);
							$return_array['result'] = FILE_UPLOAD_ERROR;
							return $return_array;
						}
					}

				}
			}
		}
		else
		{
			$return_array['result'] = FILE_UPLOAD_TOO_BIG;
			return $return_array;
		}

		$return_array['result'] = FILE_UPLOAD_NOT_UPLOADED;
		return $return_array;
	}

	/*
	* Clean all upload garbage
	*/
	function full_cleanup()
	{
		$files_to_skip = array_unique(array_merge((array) $this->files_to_skip, array('.htaccess', 'index.html')));

		/*
		// Mighty Gorgon: I need to implement everywhere this code
		// Remove only temporary folders oldest than 2 hours ago
		$this->uploads_folder = $this->remove_trailing_slashes($this->uploads_folder) . '/';
		$t = floor(time() / 3600);
		// Start removing from $hours_start ago (recent folders are preserved)
		$hours_start = 2;
		// How many hours should the script go back...
		$hours_back = 24 * 7;
		for($i = $hours_start; $i < $hours_back; $i++)
		{
			$num = $t - $i;
			if(@is_dir($this->uploads_folder . $num))
			{
				$this->cleanup($this->uploads_folder . $num, $files_to_skip, true, true, true);
			}
		}
		*/

		$this->cleanup($this->uploads_folder, $files_to_skip, false, true, true);
		return true;
	}

}

?>