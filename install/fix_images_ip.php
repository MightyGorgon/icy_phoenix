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

$start_time = time();
$time_limit = $_GET['time_limit'];

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$page_filename = 'fix_images_ip.' . PHP_EXT;

$mode_array = array('posted_pics', 'edit_posts', 'album_pics');
$mode = isset($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$mode = (!in_array($mode, $mode_array) ? 'start' : $mode);

$pics_number = isset($_GET['pics_number']) ? intval($_GET['pics_number']) : intval($_POST['pics_number']);
$pic_start = isset($_GET['pic_start']) ? intval($_GET['pic_start']) : intval($_POST['pic_start']);
$total_pics = isset($_GET['total_pics']) ? intval($_GET['total_pics']) : intval($_POST['total_pics']);
$total_pics_modified = isset($_GET['total_pics_modified']) ? intval($_GET['total_pics_modified']) : intval($_POST['total_pics_modified']);

$posts_number = isset($_GET['posts_number']) ? intval($_GET['posts_number']) : intval($_POST['posts_number']);
$post_start = isset($_GET['post_start']) ? intval($_GET['post_start']) : intval($_POST['post_start']);
$total_posts = isset($_GET['total_posts']) ? intval($_GET['total_posts']) : intval($_POST['total_posts']);
$total_posts_modified = isset($_GET['total_posts_modified']) ? intval($_GET['total_posts_modified']) : intval($_POST['total_posts_modified']);

$url_append = 'pics_number=' . $pics_number . '&amp;' . 'pic_start=' . $pic_start . '&amp;' . 'total_pics=' . $total_pics . '&amp;' . 'total_pics_modified=' . $total_pics_modified;
$url_append .= '&amp;';
$url_append .= 'posts_number=' . $posts_number . '&amp;' . 'post_start=' . $post_start . '&amp;' . 'total_posts=' . $total_posts . '&amp;' . 'total_posts_modified=' . $total_posts_modified;

$message = '';

// MAIN FORM
if ($mode == 'start')
{
	$page_title = 'Fix Icy Phoenix Images';
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

?>

		<form action="<?php echo(append_sid($page_filename)); ?>" method="post" enctype="multipart/form-data">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header" colspan="2"><span><?php echo($page_title); ?></span></td></tr>
			<tr>
				<td class="row1" style="text-align: right;"><span class="genmed">Number of pics per step:&nbsp;</span></td>
				<td class="row1" width="50%"><span class="genmed"><input type="text" class="post" name="pics_number" value="100" size="60" /></span></td>
			</tr>
			<tr>
				<td class="row1" style="text-align: right;"><span class="genmed">Number of posts per step (<i>only for images in posts</i>):&nbsp;</span></td>
				<td class="row1" width="50%"><span class="genmed"><input type="text" class="post" name="posts_number" value="500" size="60" /></span></td>
			</tr>
			<tr>
				<td class="row2" style="text-align: right;"><span class="genmed">Start from post (<i>only for images in posts</i>):&nbsp;</span></td>
				<td class="row2"><span class="genmed"><input type="text" class="post" name="post_start" value="0" size="60" /></span></td>
			</tr>
			<tr>
				<td class="row1 row-center" colspan="2">
					<input type="hidden" name="mode" value="<?php echo($mode_array[0]); ?>" />
					<input type="submit" class="mainoption" name="submit" value="START!" />
				</td>
			</tr>
		</table>
		</form>

<?php

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	exit;

}
// RENAME AND MOVE PICS
elseif ($mode == 'posted_pics')
{
	$page_title = 'Rename Move Images';
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	$template->set_filenames(array('body' => 'message_body.tpl'));

	$posted_images_folder = POSTED_IMAGES_PATH;
	$counter = 0;
	$message .= '<div class="post-text" style="align:left;width:100%;"><ul style="align:left;">';
	$dir = @opendir($posted_images_folder);
	while($file = readdir($dir))
	{
		if (!is_dir($file))
		{
			$counter++;
			if ($counter >= $pics_number)
			{
				$tmp_url = $page_filename . '?' . 'mode=' . $mode . '&amp;' . $url_append;
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />'
					)
				);
				$message .= '</ul></div><br /><br />';
				$message .= '<br /><br /><span class="genmed"><a href="' . append_sid($tmp_url) . '">Click here to continue... or wait 3 seconds to be automatically redirected!</a></span>';
				$message .= '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />';
				message_die(GENERAL_MESSAGE, $message);
				exit;
			}
			$process_item = (($file != '.') && ($file != '..') && (!is_dir($posted_images_folder . $file)) && (!is_link($posted_images_folder . $file))) ? true : false;
			if($process_item)
			{
				if(preg_match('/(\.gif$|\.tif$|\.png$|\.jpg$|\.jpeg$)$/is', $file))
				{
					$tmp_split = explode('_', $file);
					if ($tmp_split[0] == 'user')
					{
						$pic_path = POSTED_IMAGES_PATH . $tmp_split[1] . '/';
						$pic_old_file = POSTED_IMAGES_PATH . $file;
						$pic_new_file = POSTED_IMAGES_PATH . $tmp_split[1] . '/' . str_replace('user_' . $tmp_split[1] . '_', '', $file);
						if (!is_dir($pic_path))
						{
							$dir_creation = @mkdir($pic_path, 0777);
							@copy(POSTED_IMAGES_PATH . 'index.html', POSTED_IMAGES_PATH . $tmp_split[1] . '/index.html');
						}
						@chmod($pic_old_file, 0755);
						$copy_success = @copy($pic_old_file, $pic_new_file);
						if ($copy_success)
						{
							@chmod($pic_new_file, 0755);
							$message .= '<li>' . $pic_old_file . ' => ' . $pic_new_file . '</li>';
							@unlink($pic_old_file);
						}
					}
				}
			}
		}
	}

	$tmp_url = $page_filename . '?' . 'mode=' . 'edit_posts' . '&amp;' . $url_append;
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />'
		)
	);
	$message .= '</ul></div><br /><br />';
	$message .= '<br /><br /><span class="genmed"><a href="' . append_sid($tmp_url) . '">Click here to continue with next step... or wait 3 seconds to be automatically redirected!</a></span>';
	$message .= '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />';
	message_die(GENERAL_MESSAGE, $message);
	exit;
}
// EDIT POSTS
elseif ($mode == 'edit_posts')
{
	$page_title = 'Fix images url in posts';
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	$template->set_filenames(array('body' => 'message_body.tpl'));

	if ($total_posts == 0)
	{
		$sql = "SELECT * FROM " . POSTS_TEXT_TABLE;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain total posts numbers', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		// Why -1???
		//$total_posts = $db->sql_numrows($result) - 1;
		$total_posts = $db->sql_numrows($result);
		$total_posts_modified = 0;
	}

	if ($total_posts_modified >= $total_posts)
	{
		$url_append = 'pics_number=' . $pics_number . '&amp;' . 'pic_start=' . $pic_start . '&amp;' . 'total_pics=' . $total_pics . '&amp;' . 'total_pics_modified=' . $total_pics_modified;
		$url_append .= '&amp;';
		$url_append .= 'posts_number=' . $posts_number . '&amp;' . 'post_start=' . $post_start . '&amp;' . 'total_posts=' . $total_posts . '&amp;' . 'total_posts_modified=' . $total_posts_modified;

		$tmp_url = $page_filename . '?' . 'mode=' . 'album_pics' . '&amp;' . $url_append;
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '">'
			)
		);
		$message .= '
<br /><br />
<ul type="circle" style="align:left;">
	<li><span style="color:#00aa00;"><b>Work Complete!</b></span></li>
	<li><span style="color:#0000aa;"><b>' . $total_posts_modified . ' posts modified!</b></span></li>
</ul>
<br /><br />
';
		$message .= '<span class="genmed"><a href="' . append_sid($tmp_url) . '">Click here to continue with next step... or wait 3 seconds to be automatically redirected!</a></span>';
		$message .= '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />';
		message_die(GENERAL_MESSAGE, $message);
	}

	$sql = "SELECT *
		FROM " . POSTS_TEXT_TABLE . "
		ORDER BY post_id ASC
		LIMIT " . $post_start . ", " . $posts_number;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$post_text_f = $row['post_text'];
		$post_text_f = mg_img_replace($post_text_f);

		$sql_update = "UPDATE " . POSTS_TEXT_TABLE . " SET post_text = '" . addslashes($post_text_f) . "' WHERE post_id = '" . $row['post_id'] . "'";

		if (!$result_new = $db->sql_query($sql_update))
		{
			echo($db->sql_error());
		}

		$total_posts_modified++;
	}

	if ($total_posts_modified > $total_posts)
	{
		$total_posts_modified = $total_posts;
	}

	$url_append = 'pics_number=' . $pics_number . '&amp;' . 'pic_start=' . $pic_start . '&amp;' . 'total_pics=' . $total_pics . '&amp;' . 'total_pics_modified=' . $total_pics_modified;
	$url_append .= '&amp;';
	$url_append .= 'posts_number=' . $posts_number . '&amp;' . 'post_start=' . ($post_start + $posts_number) . '&amp;' . 'total_posts=' . $total_posts . '&amp;' . 'total_posts_modified=' . $total_posts_modified;

	$tmp_url = $page_filename . '?' . 'mode=' . 'edit_posts' . '&amp;' . $url_append;
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />'
		)
	);
	$message .= '
<br /><br />
<ul type="circle" style="align:left;">
	<li><span style="color:#00aa00;"><b>Posts from ' . ($post_start + 1) . ' to ' . ((($post_start + $posts_number) > $total_posts) ? $total_posts : ($post_start + $posts_number)). ' modified!</b></span></li>
	<li><span style="color:#0000aa;"><b>' . $total_posts_modified . ' of ' . $total_posts . ' total posts modified!</b></span></li>
</ul>
<br /><br />
';
	$message .= '<span class="genmed"><a href="' . append_sid($tmp_url) . '">Click here to continue with next step... or wait 3 seconds to be automatically redirected!</a></span>';
	$message .= '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />';
	message_die(GENERAL_MESSAGE, $message);
	exit;
}
// RENAME, MOVE PICS AND EDIT ALBUM TABLE
elseif ($mode == 'album_pics')
{
	$page_title = 'Fixing album images';
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	$template->set_filenames(array('body' => 'message_body.tpl'));

	if ($total_pics == 0)
	{
		$sql = "SELECT * FROM " . ALBUM_TABLE;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain total posts numbers', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		// Why -1???
		//$total_pics = $db->sql_numrows($result) - 1;
		$total_pics = $db->sql_numrows($result);
		$total_pics_modified = 0;
	}

	if ($total_pics_modified >= $total_pics)
	{
		$message = '<span style="color:#dd2222;"><b>Work Complete!</b></span>';
		message_die(GENERAL_MESSAGE, $message);
	}

	//$pic_base_path = ALBUM_UPLOAD_PATH;
	$pic_base_path = 'files/album/';
	$pic_extra_path = 'users/';
	if (!is_dir($pic_base_path . $pic_extra_path))
	{
		$dir_creation = @mkdir($pic_base_path . $pic_extra_path, 0777);
		@copy($pic_base_path . 'index.html', $pic_base_path . $pic_extra_path . 'index.html');
	}

	$sql = "SELECT *
		FROM " . ALBUM_TABLE . "
		ORDER BY pic_id ASC
		LIMIT " . $pic_start . ", " . $pics_number;
	$result = $db->sql_query($sql);

	$message .= '<div class="post-text" style="align:left;width:100%;"><ul style="align:left;">';
	while ($row = $db->sql_fetchrow($result))
	{
		$pic_old_file = $pic_base_path . $row['pic_filename'];
		$pic_size = @filesize($pic_old_file);
		$pic_size = (!$pic_size ? 0 : $pic_size);
		$user_subfolder = (intval($row['pic_user_id']) >= 2) ? (intval($row['pic_user_id']) . '/') : '';
		$pic_new_path = $pic_base_path . $pic_extra_path . $user_subfolder;
		if ($user_subfolder != '')
		{
			$pic_new_file = $pic_new_path . $row['pic_filename'];
			$pic_new_filename = $user_subfolder . $row['pic_filename'];
			if (!is_dir($pic_new_path))
			{
				$dir_creation = @mkdir($pic_new_path, 0777);
				@copy($pic_base_path . 'index.html', $pic_new_path . 'index.html');
			}
			@chmod($pic_old_file, 0755);
			$copy_success = @copy($pic_old_file, $pic_new_file);
			if ($copy_success)
			{
				@chmod($pic_new_file, 0755);
				$message .= '<li>' . $pic_old_file . ' => ' . $pic_new_file . '</li>';
				@unlink($pic_old_file);
			}
		}
		else
		{
			$pic_new_file = $pic_base_path . $pic_extra_path . $row['pic_filename'];
			$pic_new_filename = $row['pic_filename'];
			@chmod($pic_old_file, 0755);
			$copy_success = @copy($pic_old_file, $pic_new_file);
			if ($copy_success)
			{
				@chmod($pic_new_file, 0755);
				$message .= '<li>' . $pic_old_file . ' => ' . $pic_new_file . '</li>';
				@unlink($pic_old_file);
			}
		}

		$sql_update = "UPDATE " . ALBUM_TABLE . " SET pic_filename = '" . $pic_new_filename . "', pic_size = '" . $pic_size . "' WHERE pic_id = '" . $row['pic_id'] . "'";
		if (!$result_new = $db->sql_query($sql_update))
		{
			echo($db->sql_error());
		}

		$total_pics_modified++;
	}
	$message .= '</ul></div><br /><br />';

	if ($total_pics_modified > $total_pics)
	{
		$total_pics_modified = $total_pics;
	}

	$url_append = 'pics_number=' . $pics_number . '&amp;' . 'pic_start=' . ($pic_start + $pics_number) . '&amp;' . 'total_pics=' . $total_pics . '&amp;' . 'total_pics_modified=' . $total_pics_modified;
	$url_append .= '&amp;';
	$url_append .= 'posts_number=' . $posts_number . '&amp;' . 'post_start=' . $post_start . '&amp;' . 'total_posts=' . $total_posts . '&amp;' . 'total_posts_modified=' . $total_posts_modified;

	$tmp_url = $page_filename . '?' . 'mode=' . 'album_pics' . '&amp;' . $url_append;
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />'
		)
	);
	$message .= '
<br /><br />
<ul type="circle" style="align:left;">
	<li><span style="color:#00aa00;"><b>Pics from ' . ($pic_start + 1) . ' to ' . ((($pic_start + $pics_number) > $total_pics) ? $total_pics : ($pic_start + $pics_number)). ' modified!</b></span></li>
	<li><span style="color:#0000aa;"><b>' . $total_pics_modified . ' of ' . $total_pics . ' total pics modified!</b></span></li>
</ul>
<br /><br />
';
	$message .= '<span class="genmed"><a href="' . append_sid($tmp_url) . '">Click here to continue with next step... or wait 3 seconds to be automatically redirected!</a></span>';
	$message .= '<meta http-equiv="refresh" content="3;url=' . append_sid($tmp_url) . '" />';
	message_die(GENERAL_MESSAGE, $message);
	exit;

}

$page_title = 'Fixing Icy Phoenix images';
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
$message .= '<span class="genmed">No correct mode specified!!!</span>';
message_die(GENERAL_MESSAGE, $message);
exit;

function mg_img_replace($text)
{
	global $board_config;
	$server_name = trim($board_config['server_name']);
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
	$server_url = $server_name . $server_port . $script_name . '/';
	$server_url = (substr($server_url, strlen($server_url) - 2, 2) == '//') ? substr($server_url, 0, strlen($server_url) - 1) : $server_url;
	//$server_url = 'icyphoenix.com';
	$server_url_input = str_replace('/', '\/', $server_url);
	$look_up = "/" . $server_url_input . "files\/posted_images\/user_([0-9]{1,4})_/";
	$replacement = $server_url . "files/posted_images/$1/";
	$text = preg_replace($look_up, $replacement, $text);
	return $text;
}

?>