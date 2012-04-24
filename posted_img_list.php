<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// This page is not in layout special...
$cms_page['page_id'] = 'pic_upload';
$cms_page['page_nav'] = true;
$cms_page['global_blocks'] = false;
$cms_auth_level = (isset($config['auth_view_pic_upload']) ? $config['auth_view_pic_upload'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);
// Force the page_id to album
$cms_page['page_id'] = 'album';

if (!$user->data['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

$pic_id = request_var('pic_id', '');

$mode_array = array('show', 'delete', 'full');
$mode = request_var('mode', '');
$mode = (($user->data['user_level'] == ADMIN) && in_array($mode, $mode_array)) ? $mode : $mode_array[0];

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

if ($user->data['user_level'] == ADMIN)
{
	$pic_user_id = request_var('user', $user->data['user_id']);
}
else
{
	$pic_user_id = $user->data['user_id'];
}

if ($user->data['user_level'] == ADMIN)
{
	if (($mode == 'delete') && ($pic_id != ''))
	{
		$pic_id = urldecode($pic_id);
		if ($pic_id != '')
		{
			if (@file_exists(@phpbb_realpath(POSTED_IMAGES_PATH . $pic_id)))
			{
				@unlink(POSTED_IMAGES_PATH . $pic_id);
				$pic_user_id_array = explode('/', $pic_id);
				$pic_user_id = $pic_user_id_array[0];
				$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_' . $pic_user_id . '.dat';
				@unlink($cache_data_file);
				redirect(append_sid('posted_img_list.' . PHP_EXT));
			}
		}
	}
}

$server_path = create_server_url();

$pic_images = array();
$pic_names = array();
$total_pics = 0;

if (USERS_SUBFOLDERS_IMG == true)
{
	if ($mode == 'full')
	{
		$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_full.dat';
	}
	else
	{
		$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_' . $pic_user_id . '.dat';
	}

	$cache_update = true;
	$cache_file_time = time();
	if (@is_file($cache_data_file))
	{
		$cache_file_time = @filemtime($cache_data_file);
		if (((gmdate('YzH') - gmdate('YzH', $cache_file_time)) < 30) && ((gmdate('Y') == gmdate('Y', $cache_file_time))))
		{
			$cache_update = false;
		}
	}

	if (!$cache_update)
	{
		include($cache_data_file);
	}
	else
	{
		$cache_data = '';
		if ($mode == 'full')
		{
			$posted_images_folder = POSTED_IMAGES_PATH;
		}
		else
		{
			$posted_images_folder = POSTED_IMAGES_PATH . $pic_user_id . '/';
		}
		if (!file_exists($posted_images_folder))
		{
			message_die(GENERAL_MESSAGE, $lang['No_Pics']);
		}
		$dir = @opendir($posted_images_folder);
		while($file = readdir($dir))
		{
			if ($mode == 'full')
			{
				if (($file != '.') && ($file != '..') && is_dir($posted_images_folder . $file))
				{
					$tmp_subfolder_path = $posted_images_folder . $file . '/';
					$subdir = @opendir($tmp_subfolder_path);
					while($subfile = readdir($subdir))
					{
						$process_item = (($subfile != '.') && ($subfile != '..') && (!is_dir($tmp_subfolder_path . $subfile)) && (!is_link($tmp_subfolder_path . $subfile))) ? true : false;
						if($process_item)
						{
							if(preg_match('/(\.gif$|\.tif$|\.png$|\.jpg$|\.jpeg$)$/is', $subfile))
							{
								$pic_time[$total_pics] = gmdate('U', @filemtime($tmp_subfolder_path . $subfile));
								$pic_images[$total_pics] = $file . '/' . $subfile;
								$total_pics++;
							}
						}
					}
				}
			}
			else
			{
				$process_item = (($file != '.') && ($file != '..') && (!is_dir($posted_images_folder . $file)) && (!is_link($posted_images_folder . $file))) ? true : false;
				if($process_item)
				{
					if(preg_match('/(\.gif$|\.tif$|\.png$|\.jpg$|\.jpeg$)$/is', $file))
					{
						$pic_time[$total_pics] = gmdate('U', @filemtime($posted_images_folder . $file));
						$pic_images[$total_pics] = $pic_user_id . '/' . $file;
						$total_pics++;
					}
				}
			}
		}
		@closedir($dir);
		@array_multisort($pic_time, $pic_images);
		@reset($pic_time);
		@reset($pic_images);

		for($i = 0; $i < $total_pics; $i++)
		{
			$cache_data .= '$pic_time[' . $i . '] = \'' . $pic_time[$i] . '\';' . "\n";
			$cache_data .= '$pic_images[' . $i . '] = \'' . $pic_images[$i] . '\';' . "\n";
		}

		$data = '<' . '?php' . "\n";
		$data .= '$total_pics = \'' . $total_pics . '\';' . "\n\n";
		$data .= $cache_data . "\n";
		$data .= '?' . '>';

		$fp = fopen($cache_data_file, 'w');
		@fwrite($fp, $data);
		@fclose($fp);
	}

	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('posted_img_list.' . PHP_EXT) . '">' . $lang['Uploaded_Images_Local'] . '</a>';

	$album_config['cols_per_page'] = ($album_config['cols_per_page'] == 0) ? 4 : $album_config['cols_per_page'];
	$album_config['rows_per_page'] = ($album_config['rows_per_page'] == 0) ? 5 : $album_config['rows_per_page'];
	$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];
	$pic_row_count = 0;
	$pic_col_count = 0;
	$s_colspan = $album_config['cols_per_page'];
	$s_colwidth = ((100 / $s_colspan) . '%');

	$start = ($start >= sizeof($pic_images)) ? 0 : $start;
	$end_counter = $start + $pics_per_page;
	$end_counter = ($end_counter > $total_pics) ? $total_pics : $end_counter;
	$pics_parsed = 0;
	for($i = $start; $i < $end_counter; $i++)
	{
		if(($pic_col_count == $album_config['cols_per_page']) || ($i == $start))
		{
			$template->assign_block_vars('pic_row', array());
			$pic_col_count = 0;
		}
		$pic_col_count++;
		if ($mode == 'full')
		{
			//$pic_names[$i] = ucfirst(str_replace('_', ' ', preg_replace('/^(.*)\..*$/', '\1', $pic_images[$i])));
			$pic_names[$i] = $pic_images[$i];
		}
		else
		{
			$pic_names[$i] = str_replace($pic_user_id . '/', '', $pic_images[$i]);
		}

		$pic_img_url = POSTED_IMAGES_PATH . $pic_images[$i];
		$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_PATH . $pic_images[$i];

		if(file_exists($pic_thumbnail_fullpath))
		{
			$pic_img_thumb = $pic_thumbnail_fullpath;
		}
		else
		{
			$pic_img_thumb = append_sid('posted_img_list_thumbnail.' . PHP_EXT . '?pic_id=' . urlencode($pic_images[$i]));
		}

		$pic_delete_url = '';
		if ($user->data['user_level'] == ADMIN)
		{
			$pic_delete_url = '<br /><span class="gensmall"><a href="' . append_sid('posted_img_list.' . PHP_EXT . '?mode=delete&amp;pic_id=' . urlencode($pic_images[$i])) . '">' . $lang['Delete'] . '</a></span>';
		}

		if(strlen($pic_names[$i]) > 25)
		{
			$pic_names[$i] = substr($pic_names[$i], 0, 22) . '...';
		}

		$template->assign_block_vars('pic_row.pic_column', array(
			'PIC_DELETE' => $pic_delete_url,
			'PIC_IMAGE' => $pic_img_url,
			'PIC_THUMB' => $pic_img_thumb,
			'PIC_BBC_INPUT' => 'bbcode_box_' . $i,
			'PIC_BBC' => '[img]' . $server_path . substr($pic_img_url, strlen(IP_ROOT_PATH)) . '[/img]',
			'PIC_NAME' => $pic_names[$i]
			)
		);

		$pics_parsed++;
		if ($pics_parsed == $pics_per_page)
		{
			break;
		}
	}
}
else
{
	if ($user->data['user_level'] != ADMIN)
	{
		$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_' . $user->data['user_id'] . '.dat';
	}
	else
	{
		$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_admins.dat';
	}
	$cache_update = true;
	$cache_file_time = time();
	if (@is_file($cache_data_file))
	{
		$cache_file_time = @filemtime($cache_data_file);
		if (((gmdate('YzH') - gmdate('YzH', $cache_file_time)) < 30) && ((gmdate('Y') == gmdate('Y', $cache_file_time))))
		{
			$cache_update = false;
		}
	}

	if (!$cache_update)
	{
		include($cache_data_file);
	}
	else
	{
		$cache_data = '';
		$dir = @opendir(POSTED_IMAGES_PATH);
		while($file = readdir($dir))
		{
			if(($file != '.') && ($file != '..') && (!is_dir(POSTED_IMAGES_PATH . $file)) && (!is_link(POSTED_IMAGES_PATH . $file)))
			{
				if(preg_match('/(\.gif$|\.tif$|\.png$|\.jpg$|\.jpeg$)$/is', $file))
				{
					$own_pics = false;
					if ($user->data['user_level'] != ADMIN)
					{
						$own_pics = (strpos($file, 'user_' . $user->data['user_id'] . '_') === false) ? false : true;
					}
					else
					{
						$own_pics = true;
					}

					if ($own_pics == true)
					{
						$pic_time[$total_pics] = gmdate('U', @filemtime(POSTED_IMAGES_PATH . $file));
						$pic_images[$total_pics] = $file;
						$total_pics++;
					}
				}
			}
		}
		@closedir($dir);
		@array_multisort($pic_time, $pic_images);
		@reset($pic_time);
		@reset($pic_images);

		for($i = 0; $i < $total_pics; $i++)
		{
			$cache_data .= '$pic_time[' . $i . '] = \'' . $pic_time[$i] . '\';' . "\n";
			$cache_data .= '$pic_images[' . $i . '] = \'' . $pic_images[$i] . '\';' . "\n";
		}

		$data = '<' . '?php' . "\n";
		$data .= '$total_pics = \'' . $total_pics . '\';' . "\n\n";
		$data .= $cache_data . "\n";
		$data .= '?' . '>';
		$fp = fopen($cache_data_file, 'w');
		@fwrite($fp, $data);
		@fclose($fp);

	}

	$album_config['cols_per_page'] = ($album_config['cols_per_page'] == 0) ? 4 : $album_config['cols_per_page'];
	$album_config['rows_per_page'] = ($album_config['rows_per_page'] == 0) ? 5 : $album_config['rows_per_page'];
	$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];
	$pic_row_count = 0;
	$pic_col_count = 0;
	$s_colspan = $album_config['cols_per_page'];
	$s_colwidth = ((100 / $s_colspan) . '%');

	$start = ($start >= sizeof($pic_images)) ? 0 : $start;
	$end_counter = $start + $pics_per_page;
	$end_counter = ($end_counter > $total_pics) ? $total_pics : $end_counter;
	$pics_parsed = 0;
	for($i = $start; $i < $end_counter; $i++)
	{
		if(($pic_col_count == $album_config['cols_per_page']) || ($i == $start))
		{
			$template->assign_block_vars('pic_row', array());
			$pic_col_count = 0;
		}
		$pic_col_count++;
		//$pic_names[$i] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $pic_images[$i])));
		$pic_names[$i] = $pic_images[$i];

		$pic_img_url = append_sid(POSTED_IMAGES_PATH . $pic_images[$i]);
		$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_PATH . 'thumb_' . $pic_images[$i];

		if(file_exists($pic_thumbnail_fullpath))
		{
			$pic_img_thumb = $pic_thumbnail_fullpath;
		}
		else
		{
			$pic_img_thumb = append_sid('posted_img_list_thumbnail.' . PHP_EXT . '?pic_id=' . $pic_images[$i]);
		}

		$pic_delete_url = '';
		if ($user->data['user_level'] == ADMIN)
		{
			$pic_delete_url = '<br /><span class="gensmall"><a href="' . append_sid('posted_img_list.' . PHP_EXT . '?mode=delete&amp;pic_id=' . urlencode($pic_images[$i])) . '">' . $lang['Delete'] . '</a></span>';
		}

		if(strlen($pic_names[$i]) > 25)
		{
			$pic_names[$i] = substr($pic_names[$i], 0, 22) . '...';
		}

		$template->assign_block_vars('pic_row.pic_column', array(
			'PIC_DELETE' => $pic_delete_url,
			'PIC_IMAGE' => $pic_img_url,
			'PIC_THUMB' => $pic_img_thumb,
			'PIC_BBC_INPUT' => 'bbcode_box_' . $i,
			'PIC_BBC' => '[img]' . $server_path . substr($pic_img_url, strlen(IP_ROOT_PATH)) . '[/img]',
			'PIC_NAME' => $pic_names[$i]
			)
		);

		$pics_parsed++;
		if ($pics_parsed == $pics_per_page)
		{
			break;
		}
	}
}

if($pic_col_count < $album_config['cols_per_page'])
{
	for($i = $pic_col_count; $i < $album_config['cols_per_page']; $i++)
	{
		$template->assign_block_vars('pic_row.pic_end_row', array());
	}
}

$template->assign_vars(array(
	'L_PIC_GALLERY' => $lang['Uploaded_Images_Local'],
	'L_BBCODE' => $lang['BBCode'],
	'L_BBCODE_DES' => $lang['Uploaded_Image_BBC'],
	'S_ACTION' => append_sid('posted_img_list.' . PHP_EXT),
	'S_COLSPAN' => $s_colspan,
	'S_COLWIDTH' => $s_colwidth,
	'S_THUMBNAIL_SIZE' => $album_config['thumbnail_size'],
	)
);

$template->assign_vars(array(
	'PAGINATION' => generate_pagination(append_sid('posted_img_list.' . PHP_EXT . '?sort=standard'), $total_pics, $pics_per_page, $start),
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $pics_per_page) + 1), ceil($total_pics / $pics_per_page))
	)
);

full_page_generation('posted_img_list_body.tpl', $lang['Uploaded_Images_Local'], '', '');

?>