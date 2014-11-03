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

/**
* Images Class
*/
class class_images
{
	/**
	* Initialize vars
	*/
	function var_init()
	{
		return true;
	}

	/**
	* Get image name and upload folder
	*/
	function get_image_upload_data($filename, $extension, $upload_dir)
	{
		global $user;

		$image_upload_data = array('filename' => $filename, 'upload_dir' => $upload_dir);

		if ($user->data['user_id'] < 0)
		{
			$image_upload_data['filename'] = 'guest_' . preg_replace('/[^a-z0-9]+/', '_', $image_upload_data['filename']);
		}
		else
		{
			$image_upload_data['filename'] = preg_replace('/[^a-z0-9]+/', '_', $image_upload_data['filename']);
			if (USERS_SUBFOLDERS_IMG == true)
			{
				if (@is_dir($image_upload_data['upload_dir'] . $user->data['user_id']))
				{
					$image_upload_data['upload_dir'] = $image_upload_data['upload_dir'] . $user->data['user_id'] . '/';
				}
				else
				{
					$dir_creation = @mkdir($image_upload_data['upload_dir'] . $user->data['user_id'], 0777);
					if ($dir_creation == true)
					{
						$image_upload_data['upload_dir'] = $image_upload_data['upload_dir'] . $user->data['user_id'] . '/';
					}
					else
					{
						$image_upload_data['filename'] = 'user_' . $user->data['user_id'] . '_' . $image_upload_data['filename'];
					}
				}
			}
			else
			{
				$image_upload_data['filename'] = 'user_' . $user->data['user_id'] . '_' . $image_upload_data['filename'];
			}
		}
		while(@file_exists($image_upload_data['upload_dir'] . $image_upload_data['filename'] . '.' . $extension))
		{
			$image_upload_data['filename'] = $image_upload_data['filename'] . '_' . time() . '_' . mt_rand(100000, 999999);
		}

		return $image_upload_data;
	}

	/**
	* Upload an image, returns false on error
	*/
	function upload_image($filename, $extension, $upload_dir, $filename_tmp)
	{
		if(is_uploaded_file($filename_tmp))
		{
			@move_uploaded_file($filename_tmp, $upload_dir . $filename . '.' . $extension);
			@chmod($upload_dir . $filename . '.' . $extension, 0777);
		}

		$pic_size = @getimagesize($upload_dir . $filename . '.' . $extension);
		if($pic_size == false)
		{
			@unlink($upload_dir . $filename . '.' . $extension);
			//return false;
		}
		return $pic_size;
	}

	/**
	* Generate image paths
	*/
	function generate_image_paths($image_data)
	{
		global $user, $lang;

		$image_paths = array();
		$image_paths['sub_path'] = (USERS_SUBFOLDERS_IMG && (!empty($image_data['pic_user_id'])) ? ($image_data['pic_user_id'] . '/') : '') . $image_data['pic_filename'];
		$image_paths['url'] = POSTED_IMAGES_PATH . $image_paths['sub_path'];
		$image_paths['thumbnail_fullpath'] = POSTED_IMAGES_THUMBS_S_PATH . $image_paths['sub_path'];
		$image_paths['thumb'] = (@file_exists($image_paths['thumbnail_fullpath']) ? $image_paths['thumbnail_fullpath'] : append_sid(CMS_PAGE_IMAGE_THUMBNAIL_S . '?pic_id=' . urlencode($image_paths['sub_path'])));
		$image_paths['delete_url'] = (($user->data['user_level'] == ADMIN) ? ('<br /><span class="gensmall"><a href="' . append_sid(CMS_PAGE_IMAGES . '?mode=delete&amp;pic_id=' . $image_data['pic_id']) . '">' . $lang['Delete'] . '</a></span>') : '');

		return $image_paths;
	}

	/**
	* Get thumbnail data
	*/
	function get_thumbnail_data($pic_thumbnail_path, $pic_thumbnail, $pic_thumbnail_fullpath, $pic_filename, $pic_thumbnail_prefix = '')
	{
		$thumbnail_data = array(
			'thumbnail' => $pic_thumbnail,
			'full_path' => $pic_thumbnail_fullpath
		);

		if (is_dir($pic_thumbnail_path))
		{
			$thumbnail_data['thumbnail'] = $pic_thumbnail_prefix . $pic_filename;
			$thumbnail_data['full_path'] = $pic_thumbnail_path . '/' . $thumbnail_data['thumbnail'];
		}
		else
		{
			$dir_creation = @mkdir($pic_thumbnail_path, 0777);
			if ($dir_creation == true)
			{
				$thumbnail_data['thumbnail'] = $pic_thumbnail_prefix . $pic_filename;
				$thumbnail_data['full_path'] = $pic_thumbnail_path . '/' . $thumbnail_data['thumbnail'];
			}
		}

		return $thumbnail_data;
	}

	/**
	* Get user dir
	*/
	function get_user_dir($upload_dir, $user_upload_dir)
	{
		global $user;

		$user_dir = array(
			'upload_dir' => $upload_dir,
			'user_upload_dir' => $user_upload_dir
		);

		if (is_dir($user_dir['upload_dir'] . $user->data['user_id']))
		{
			$user_dir['user_upload_dir'] = $user->data['user_id'] . '/';
			$user_dir['upload_dir'] = $user_dir['upload_dir'] . $user_dir['user_upload_dir'];
		}
		else
		{
			$dir_creation = @mkdir($user_dir['upload_dir'] . $user->data['user_id'], 0777);
			if ($dir_creation)
			{
				$user_dir['user_upload_dir'] = $user->data['user_id'] . '/';
				$user_dir['upload_dir'] = $user_dir['upload_dir'] . $user_dir['user_upload_dir'];
			}
		}

		return $user_dir;
	}

	/**
	* Get image details
	*/
	function get_image_details($image_path)
	{
		$image_details = array(
			'time' => filemtime($image_path),
			'size' => filesize($image_path),
		);
		return $image_details;
	}

	/**
	* Get user images
	*/
	function get_user_images($user_id, $order_by = '', $start = 0, $n_items = 0)
	{
		global $db, $cache, $config, $lang;

		$images = array();
		$user_id = (int) $user_id;

		if (!empty($user_id))
		{
			$order_sql = " ORDER BY " . (!empty($order_by) ? $order_by : "i.pic_time DESC");
			$limit_sql = (!empty($n_items) ? (" LIMIT " . (!empty($start) ? ($start . ", " . $n_items) : ($n_items . " "))) : "");

			$sql = "SELECT i.*, u.user_id, u.username, u.user_active, u.user_color, u.user_level, u.user_rank, u.user_first_name, u.user_last_name
							FROM " . IMAGES_TABLE . " i, " . USERS_TABLE . " u
							WHERE i.pic_user_id = " . $user_id . "
								AND i.pic_user_id = u.user_id"
							. $order_sql
							. $limit_sql;
			$result = $db->sql_query($sql);
			$images = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}

		return $images;
	}

	/**
	* Get all user images
	*/
	function get_all_user_images($order_by = '', $start = 0, $n_items = 0)
	{
		global $db, $cache, $config, $lang;

		$images = array();

		$order_sql = " ORDER BY " . (!empty($order_by) ? $order_by : "i.pic_time DESC");
		$limit_sql = (!empty($n_items) ? (" LIMIT " . (!empty($start) ? ($start . ", " . $n_items) : ($n_items . " "))) : "");

		// Query only existing users...
		/*
		$sql = "SELECT i.*, u.user_id, u.username, u.user_active, u.user_color, u.user_level, u.user_rank, u.user_first_name, u.user_last_name
						FROM " . IMAGES_TABLE . " i, " . USERS_TABLE . " u
						WHERE i.pic_user_id = u.user_id"
						. $order_sql
						. $limit_sql;
		*/
		// Query all images, regardless if the user still exists or not...
		$sql = "SELECT i.*, u.user_id, u.username, u.user_active, u.user_color, u.user_level, u.user_rank, u.user_first_name, u.user_last_name
						FROM " . IMAGES_TABLE . " AS i LEFT JOIN " . USERS_TABLE . " AS u ON i.pic_user_id = u.user_id"
						. $order_sql
						. $limit_sql;
		$result = $db->sql_query($sql);
		$images = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $images;
	}

	/**
	* Get image
	*/
	function get_image($pic_id)
	{
		global $db, $cache, $config, $lang;

		$image = array();
		$pic_id = (int) $pic_id;

		if (!empty($pic_id))
		{
			$sql = "SELECT i.*, u.user_id, u.username, u.user_active, u.user_color, u.user_level, u.user_rank, u.user_first_name, u.user_last_name
							FROM " . IMAGES_TABLE . " i, " . USERS_TABLE . " u
							WHERE i.pic_id = " . $pic_id . "
								AND i.pic_user_id = u.user_id";
			$result = $db->sql_query($sql);
			$image = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		return $image;
	}

	/**
	* Get total user images
	*/
	function get_total_user_images($user_id)
	{
		global $db, $cache, $config, $lang;

		$user_id = (int) $user_id;

		$sql = "SELECT COUNT(i.pic_id) AS total_images
						FROM " . IMAGES_TABLE . " i
						WHERE i.pic_user_id = " . $user_id;
		$result = $db->sql_query($sql);
		$images_data = $db->sql_fetchrow($result);
		$total_images = $images_data['total_images'];
		$db->sql_freeresult($result);

		return $total_images;
	}

	/**
	* Get total images
	*/
	function get_total_images()
	{
		global $db, $cache, $config, $lang;

		$sql = "SELECT COUNT(i.pic_id) AS total_images
						FROM " . IMAGES_TABLE . " i";
		$result = $db->sql_query($sql);
		$images_data = $db->sql_fetchrow($result);
		$total_images = $images_data['total_images'];
		$db->sql_freeresult($result);

		return $total_images;
	}

	/**
	* Submit an image
	*/
	function submit_image(&$image_data, $mode = 'insert')
	{
		global $db, $cache, $config, $lang;

		if ($mode == 'insert')
		{
			$sql = "INSERT INTO " . IMAGES_TABLE . " " . $db->sql_build_insert_update($image_data, true);
			$result = $db->sql_query($sql);
			$image_data['pic_id'] = $db->sql_nextid();
		}
		else
		{
			$sql = "UPDATE " . IMAGES_TABLE . " SET
				" . $db->sql_build_insert_update($image_data, false) . "
				WHERE pic_id = " . (int) $image_data['pic_id'];
			$result = $db->sql_query($sql);
		}

		return true;
	}

	/*
	* Remove an image
	*/
	function remove_image($pic_id)
	{
		global $db;

		$pic_id = (int) $pic_id;

		$image_data = $this->get_image($pic_id);
		if (!empty($image_data))
		{
			$image_full_path = POSTED_IMAGES_PATH . ((USERS_SUBFOLDERS_IMG && !empty($image_data['pic_user_id'])) ? ($image_data['pic_user_id'] . '/') : '') . (!empty($image_data['pic_filename']) ? $image_data['pic_filename'] : '');
			if (@file_exists($image_full_path))
			{
				@unlink($image_full_path);
			}
			$thumbnail_full_path = POSTED_IMAGES_THUMBS_PATH . ((USERS_SUBFOLDERS_IMG && !empty($image_data['pic_user_id'])) ? ($image_data['pic_user_id'] . '/') : '') . (!empty($image_data['pic_filename']) ? $image_data['pic_filename'] : '');
			if (@file_exists($thumbnail_full_path))
			{
				@unlink($thumbnail_full_path);
			}
			$thumbnail_list_full_path = POSTED_IMAGES_THUMBS_PATH . ((USERS_SUBFOLDERS_IMG && !empty($image_data['pic_user_id'])) ? ($image_data['pic_user_id'] . '/') : '') . (!empty($image_data['pic_filename']) ? ('_' . $image_data['pic_filename']) : '');
			if (@file_exists($thumbnail_list_full_path))
			{
				@unlink($thumbnail_list_full_path);
			}
			$sql = "DELETE FROM " . IMAGES_TABLE . " WHERE pic_id = " . $pic_id;
			$result = $db->sql_query($sql);
		}

		return true;
	}
}

?>