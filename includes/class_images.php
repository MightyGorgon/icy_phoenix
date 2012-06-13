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
	* Short description.
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