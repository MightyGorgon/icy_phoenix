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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

$allowed_extensions = array();
$display_categories = array();
$download_modes = array();
$upload_icons = array();
$attachments = array();

/**
* Check if Attachment exist
*/
function attachment_exists($filename)
{
	global $upload_dir, $config;

	$filename = basename($filename);

	if (!intval($config['allow_ftp_upload']))
	{
		if (!@file_exists(@amod_realpath($upload_dir . '/' . $filename)))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		$found = false;

		$conn_id = attach_init_ftp();

		$file_listing = array();

		$file_listing = @ftp_rawlist($conn_id, $filename);

		for ($i = 0, $size = sizeof($file_listing); $i < $size; $i++)
		{
			if (preg_match("/([-d])[rwxst-]{9}.* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9]) ([0-9]{2}:[0-9]{2}) (.+)/", $file_listing[$i], $regs))
			{
				if ($regs[1] == 'd')
				{
					$dirinfo[0] = 1;	// Directory == 1
				}
				$dirinfo[1] = $regs[2]; // Size
				$dirinfo[2] = $regs[3]; // Date
				$dirinfo[3] = $regs[4]; // Filename
				$dirinfo[4] = $regs[5]; // Time
			}

			if ($dirinfo[0] != 1 && $dirinfo[4] == $filename)
			{
				$found = true;
			}
		}

		@ftp_quit($conn_id);

		return $found;
	}
}

/**
* Determine if an Attachment exist in a post/pm
*/
function attachment_exists_db($post_id, $page = 0)
{
	global $db;

	$post_id = (int) $post_id;

	if ($page == PAGE_PRIVMSGS)
	{
		$sql_id = 'privmsgs_id';
	}
	else
	{
		$sql_id = 'post_id';
	}

	$sql = 'SELECT attach_id
		FROM ' . ATTACHMENTS_TABLE . "
		WHERE $sql_id = $post_id
		LIMIT 1";
	$result = $db->sql_query($sql);
	$num_rows = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($num_rows > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
* Get allowed Extensions and their respective Values
*/
function get_extension_informations()
{
	global $db;

	$extensions = array();
	// Don't count on forbidden extensions table, because it is not allowed to allow forbidden extensions at all
	$sql = 'SELECT e.extension, g.cat_id, g.download_mode, g.upload_icon
		FROM ' . EXTENSIONS_TABLE . ' e, ' . EXTENSION_GROUPS_TABLE . ' g
		WHERE e.group_id = g.group_id
			AND g.allow_group = 1';
	$result = $db->sql_query($sql);
	$extensions = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	return $extensions;
}

/**
* Clear the templates compile cache
*/
function display_compile_cache_clear($filename, $template_var)
{
	global $template;

	if (isset($template->cachedir))
	{
		$filename = str_replace($template->root, '', $filename);
		if (substr($filename, 0, 1) == '/')
		{
			$filename = substr($filename, 1, strlen($filename));
		}

		if (file_exists($template->cachedir . $filename . '.php'))
		{
			@unlink($template->cachedir . $filename . '.php');
		}
	}

	return;
}

/**
* Create needed arrays for Extension Assignments
*/
function init_complete_extensions_data()
{
	global $db, $allowed_extensions, $display_categories, $download_modes, $upload_icons;

	$extension_informations = get_extension_informations();
	$allowed_extensions = array();

	for ($i = 0, $size = sizeof($extension_informations); $i < $size; $i++)
	{
		$extension = strtolower(trim($extension_informations[$i]['extension']));
		$allowed_extensions[] = $extension;
		$display_categories[$extension] = intval($extension_informations[$i]['cat_id']);
		$download_modes[$extension] = intval($extension_informations[$i]['download_mode']);
		$upload_icons[$extension] = trim($extension_informations[$i]['upload_icon']);
	}
}

/**
* Writing Data into plain Template Vars
*/
function init_display_template($template_var, $replacement, $filename = 'viewtopic_attach_body.tpl')
{
	global $template, $config;

	// This function is adapted from the old template class
	// I wish i had the functions from the 3.x one. :D (This class rocks, can't await to use it in Mods)

	// Handle Attachment Informations
	if (!isset($template->uncompiled_code[$template_var]) && empty($template->uncompiled_code[$template_var]))
	{
		// If we don't have a file assigned to this handle, die.
		if (!isset($template->files[$template_var]))
		{
			die("Template->loadfile(): No file specified for handle $template_var");
		}

		$filename_2 = $template->files[$template_var];

		$str = implode('', @file($filename_2));
		if (empty($str))
		{
			die("Template->loadfile(): File $filename_2 for handle $template_var is empty");
		}

		$template->uncompiled_code[$template_var] = $str;
	}

	$complete_filename = $filename;
	if (substr($complete_filename, 0, 1) != '/')
	{
		$complete_filename = $template->root . '/' . $complete_filename;
		if (!file_exists($complete_filename))
		{
			$complete_filename = IP_ROOT_PATH . 'templates/' . $config['xs_def_template'] . '/' . $filename;
		}
	}

	if (!file_exists($complete_filename))
	{
		die("Template->make_filename(): Error - file $complete_filename does not exist");
	}

	$content = implode('', file($complete_filename));
	if (empty($content))
	{
		die('Template->loadfile(): File ' . $complete_filename . ' is empty');
	}

	// replace $replacement with uncompiled code in $filename
	$template->uncompiled_code[$template_var] = str_replace($replacement, $content, $template->uncompiled_code[$template_var]);

	// Force Reload on cached version
	display_compile_cache_clear($template->files[$template_var], $template_var);
}

/**
* BEGIN ATTACHMENT DISPLAY IN POSTS
*/

/**
* Returns the image-tag for the topic image icon
*/
function topic_attachment_image($switch_attachment)
{
	global $config, $is_auth, $lang;

	if (intval($switch_attachment) == 0 || (!($is_auth['auth_download'] && $is_auth['auth_view'])) || intval($config['disable_attachments_mod']) || $config['topic_icon'] == '')
	{
		return '';
	}

	$image = '<img src="' . $config['topic_icon'] . '" alt="' . $lang['ATTACHMENTS'] .'" title="' . $lang['ATTACHMENTS'] . '" /> ';

	return $image;
}

/**
* Display Attachments in Posts
*/
function display_post_attachments($post_id, $switch_attachment)
{
	global $config, $is_auth;

	if (intval($switch_attachment) == 0 || intval($config['disable_attachments_mod']))
	{
		return;
	}

	if ($is_auth['auth_download'] && $is_auth['auth_view'])
	{
		display_attachments($post_id, 'postrow');
	}
	else
	{
		// Display Notice (attachment there but not having permissions to view it)
		// Not included because this would mean template and language file changes (at this stage this is not a wise step. ;))
	}
}

/*
//
// Generate the Display Assign File Link
//
function display_assign_link($post_id)
{
	global $config, $is_auth;

	$image = 'templates/subSilver/images/icon_mini_message.gif';

	if ((intval($config['disable_attachments_mod'])) || (!(($is_auth['auth_download']) && ($is_auth['auth_view']))))
	{
		return ('');
	}

	$temp_url = append_sid('assign_file.' . PHP_EXT . '?p=' . $post_id);
	$link = '<a href="' . $temp_url . '" target="_blank"><img src="' . $image . '" alt="Add File" title="Add File" /></a>';

	return ($link);
}
*/

/**
* Initializes some templating variables for displaying Attachments in Posts
*/
function init_display_post_attachments($switch_attachment, $article = array(), $forum_attach = false, $block_id = 0)
{
	global $config, $db, $is_auth, $template, $lang, $postrow, $total_posts, $attachments, $forum_row, $forum_topic_data;
	global $is_news_index;

	if (empty($forum_topic_data) && !empty($forum_row))
	{
		$switch_attachment = $forum_row['topic_attachment'];
	}
	if (!empty($article) && ($switch_attachment == ''))
	{
		$switch_attachment = $article['topic_attachment'];
	}

	if (sizeof($article) == 0)
	{
		if ((intval($switch_attachment) == 0) || intval($config['disable_attachments_mod']) || (!($is_auth['auth_download'] && $is_auth['auth_view'])))
		{
			return;
		}
	}

	$post_id_array = array();

	if (sizeof($article) == 0)
	{
		for ($i = 0; $i < $total_posts; $i++)
		{
			if ($postrow[$i]['post_attachment'] == 1)
			{
				$post_id_array[] = (int) $postrow[$i]['post_id'];
			}
		}
	}
	else
	{
		if ($article['post_attachment'] == 1)
		{
			$post_id_array[] = $article['post_id'];
		}
	}
	if (sizeof($post_id_array) == 0)
	{
		return;
	}

	$rows = get_attachments_from_post($post_id_array);
	$num_rows = sizeof($rows);

	if ($num_rows == 0)
	{
		return;
	}

	@reset($attachments);

	for ($i = 0; $i < $num_rows; $i++)
	{
		$attachments['_' . $rows[$i]['post_id']][] = $rows[$i];
	}

	if (sizeof($article) == 0)
	{
		init_display_template('body', '{postrow.ATTACHMENTS}');
	}
	else
	{
		if ($forum_attach == true)
		{
			//init_display_template('forum_attach_block', '{articles_fp.ATTACHMENTS}', 'news_fp_attach_body.tpl');
			init_display_template('forum_attach_block_' . $block_id, '{articles_fp.ATTACHMENTS}', 'news_fp_attach_body.tpl');
		}
		elseif ($is_news_index == true)
		{
			init_display_template('news', '{articles.ATTACHMENTS}', 'news_attach_body.tpl');
		}
		else
		{
			init_display_template('news', '{articles.ATTACHMENTS}', 'news_attach_body.tpl');
			//init_display_template('news_block', '{articles.ATTACHMENTS}', 'news_attach_body.tpl');
			init_display_template('news_block_' . $block_id, '{articles.ATTACHMENTS}', 'news_attach_body.tpl');
		}
	}

	init_complete_extensions_data();

	$template->assign_vars(array(
		'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
		'L_KILOBYTE' => $lang['KB']
		)
	);
}

/**
* END ATTACHMENT DISPLAY IN POSTS
*/

/**
* BEGIN ATTACHMENT DISPLAY IN PM's
*/

/**
* Returns the image-tag for the PM image icon
*/
function privmsgs_attachment_image($privmsg_id)
{
	global $config, $user;

	$auth = ($user->data['user_level'] == ADMIN) ? 1 : intval($config['allow_pm_attach']);

	if (!attachment_exists_db($privmsg_id, PAGE_PRIVMSGS) || !$auth || intval($config['disable_attachments_mod']) || $config['topic_icon'] == '')
	{
		return '';
	}

	$image = '<img src="' . $config['topic_icon'] . '" alt="" /> ';

	return $image;
}

/**
* Display Attachments in PM's
*/
function display_pm_attachments($privmsgs_id, $switch_attachment)
{
	global $config, $user, $template, $lang;

	if ($user->data['user_level'] == ADMIN)
	{
		$auth_download = 1;
	}
	else
	{
		$auth_download = intval($config['allow_pm_attach']);
	}

	if (intval($switch_attachment) == 0 || intval($config['disable_attachments_mod']) || !$auth_download)
	{
		return;
	}

	display_attachments($privmsgs_id, 'postrow');

	$template->assign_block_vars('switch_attachments', array());
	$template->assign_vars(array(
		'L_DELETE_ATTACHMENTS' => $lang['Delete_attachments']
		)
	);
}

/**
* Initializes some templating variables for displaying Attachments in Private Messages
*/
function init_display_pm_attachments($switch_attachment)
{
	global $config, $template, $user, $lang, $attachments, $privmsg;

	if ($user->data['user_level'] == ADMIN)
	{
		$auth_download = 1;
	}
	else
	{
		$auth_download = intval($config['allow_pm_attach']);
	}

	if (intval($switch_attachment) == 0 || intval($config['disable_attachments_mod']) || !$auth_download)
	{
		return;
	}

	$privmsgs_id = $privmsg['privmsgs_id'];

	@reset($attachments);
	$attachments['_' . $privmsgs_id] = get_attachments_from_pm($privmsgs_id);

	if (sizeof($attachments['_' . $privmsgs_id]) == 0)
	{
		return;
	}

	$template->assign_block_vars('postrow', array());

	init_display_template('body', '{ATTACHMENTS}');

	init_complete_extensions_data();

	$template->assign_vars(array(
		'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
		'L_KILOBYTE' => $lang['KB']
		)
	);

	display_pm_attachments($privmsgs_id, $switch_attachment);
}

/**
* END ATTACHMENT DISPLAY IN PM's
*/

/**
* BEGIN ATTACHMENT DISPLAY IN TOPIC REVIEW WINDOW
*/

/**
* Display Attachments in Review Window
*/
function display_review_attachments($post_id, $switch_attachment, $is_auth)
{
	global $config, $attachments;

	if (intval($switch_attachment) == 0 || intval($config['disable_attachments_mod']) || (!($is_auth['auth_download'] && $is_auth['auth_view'])) || intval($config['attachment_topic_review']) == 0)
	{
		return;
	}

	@reset($attachments);
	$attachments['_' . $post_id] = get_attachments_from_post($post_id);

	if (sizeof($attachments['_' . $post_id]) == 0)
	{
		return;
	}

	display_attachments($post_id, 'postrow');
}

/**
* Initializes some templating variables for displaying Attachments in Review Topic Window
*/
function init_display_review_attachments($is_auth)
{
	global $config;

	if (intval($config['disable_attachments_mod']) || (!($is_auth['auth_download'] && $is_auth['auth_view'])) || intval($config['attachment_topic_review']) == 0)
	{
		return;
	}

	init_display_template('reviewbody', '{postrow.ATTACHMENTS}');

	init_complete_extensions_data();

}

/**
* END ATTACHMENT DISPLAY IN TOPIC REVIEW WINDOW
*/

/**
* BEGIN DISPLAY ATTACHMENTS -> PREVIEW
*/
function display_attachments_preview($attachment_id_list, $attachment_list, $attachment_filesize_list, $attachment_filename_list, $attachment_comment_list, $attachment_extension_list, $attachment_thumbnail_list)
{
	global $config, $is_auth, $allowed_extensions, $user, $lang, $display_categories, $upload_dir, $upload_icons, $template, $db, $theme;

	if (sizeof($attachment_list) != 0)
	{
		init_display_template('preview', '{ATTACHMENTS}');

		init_complete_extensions_data();

		$template->assign_block_vars('postrow', array());
		$template->assign_block_vars('postrow.attach', array());

		for ($i = 0, $size = sizeof($attachment_list); $i < $size; $i++)
		{
			$filename = $upload_dir . '/' . basename($attachment_list[$i]);
			$thumb_filename = $upload_dir . '/' . THUMB_DIR . '/t_' . basename($attachment_list[$i]);
			if (!file_exists($thumb_filename))
			{
				$thumb_filename = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachment_id_list[$i] . '&thumb=1');
			}

			$filesize = $attachment_filesize_list[$i];
			$size_lang = ($filesize >= 1048576) ? $lang['MB'] : (($filesize >= 1024) ? $lang['KB'] : $lang['Bytes']);

			if ($filesize >= 1048576)
			{
				$filesize = (round((round($filesize / 1048576 * 100) / 100), 2));
			}
			elseif ($filesize >= 1024)
			{
				$filesize = (round((round($filesize / 1024 * 100) / 100), 2));
			}

			$display_name = $attachment_filename_list[$i];
			$comment = $attachment_comment_list[$i];
			$comment = str_replace("\n", '<br />', $comment);

			$extension = $attachment_extension_list[$i];

			$denied = false;

			// Admin is allowed to view forbidden Attachments, but the error-message is displayed too to inform the Admin
			if (!in_array($extension, $allowed_extensions))
			{
				$denied = true;

				$template->assign_block_vars('postrow.attach.denyrow', array(
					'L_DENIED' => sprintf($lang['Extension_disabled_after_posting'], $extension)
					)
				);
			}

			if (!$denied)
			{
				// Some basic Template Vars
				$template->assign_vars(array(
					'L_DESCRIPTION' => $lang['Description'],
					'L_DOWNLOAD' => $lang['Download'],
					'L_FILENAME' => $lang['File_name'],
					'L_FILESIZE' => $lang['Filesize']
					)
				);

				// define category
				$image = false;
				$stream = false;
				$swf = false;
				$thumbnail = false;
				$link = false;

				if (intval($display_categories[$extension]) == STREAM_CAT)
				{
					$stream = true;
				}
				elseif (intval($display_categories[$extension]) == SWF_CAT)
				{
					$swf = true;
				}
				elseif ((intval($display_categories[$extension]) == IMAGE_CAT) && intval($config['img_display_inlined']))
				{
					if ((intval($config['img_link_width']) != 0) || (intval($config['img_link_height']) != 0))
					{
						list($width, $height) = image_getdimension($filename);

						if ($width == 0 && $height == 0)
						{
							$image = true;
						}
						else
						{
							if (($width <= intval($config['img_link_width'])) && ($height <= intval($config['img_link_height'])))
							{
								$image = true;
							}
						}
					}
					else
					{
						$image = true;
					}
				}

				if ((intval($display_categories[$extension]) == IMAGE_CAT) && (intval($attachment_thumbnail_list[$i]) == 1))
				{
					$thumbnail = true;
					$image = false;
				}

				if (!$image && !$stream && !$swf && !$thumbnail)
				{
					$link = true;
				}

				if ($image)
				{
					// Images
					$template->assign_block_vars('postrow.attach.cat_images', array(
						'DOWNLOAD_NAME' => $display_name,
						'IMG_SRC' => $filename,
						'FILESIZE' => $filesize,
						'SIZE_VAR' => $size_lang,
						'COMMENT' => $comment,
						'L_DOWNLOADED_VIEWED' => $lang['Viewed']
						)
					);
				}

				if ($thumbnail)
				{
					// Images, but display Thumbnail
					$template->assign_block_vars('postrow.attach.cat_thumb_images', array(
						'DOWNLOAD_NAME' => $display_name,
						'IMG_SRC' => $filename,
						'IMG_THUMB_SRC' => $thumb_filename,
						'FILESIZE' => $filesize,
						'SIZE_VAR' => $size_lang,
						'COMMENT' => $comment,
						'L_DOWNLOADED_VIEWED' => $lang['Viewed']
						)
					);
				}

				if ($stream)
				{
					// Streams
					$template->assign_block_vars('postrow.attach.cat_stream', array(
						'U_DOWNLOAD_LINK' => $filename,
						'DOWNLOAD_NAME' => $display_name,
						'FILESIZE' => $filesize,
						'SIZE_VAR' => $size_lang,
						'COMMENT' => $comment,
						'L_DOWNLOADED_VIEWED' => $lang['Viewed']
						)
					);
				}

				if ($swf)
				{
					// Macromedia Flash Files
					list($width, $height) = swf_getdimension($filename);

					$template->assign_block_vars('postrow.attach.cat_swf', array(
						'U_DOWNLOAD_LINK' => $filename,
						'DOWNLOAD_NAME' => $display_name,
						'FILESIZE' => $filesize,
						'SIZE_VAR' => $size_lang,
						'COMMENT' => $comment,
						'L_DOWNLOADED_VIEWED' => $lang['Viewed'],
						'WIDTH' => $width,
						'HEIGHT' => $height
						)
					);
				}

				if ($link)
				{
					$upload_image = '';

					if ($config['upload_img'] != '' && $upload_icons[$extension] == '')
					{
						$upload_image = '<img src="' . $config['upload_img'] . '" alt="" />';
					}
					elseif (trim($upload_icons[$extension]) != '')
					{
						$upload_image = '<img src="' . $upload_icons[$extension] . '" alt="" />';
					}

					$target_blank = 'target="_blank"';

					// display attachment
					$template->assign_block_vars('postrow.attach.attachrow', array(
						'U_DOWNLOAD_LINK' => $filename,
						'S_UPLOAD_IMAGE' => $upload_image,

						'DOWNLOAD_NAME' => $display_name,
						'FILESIZE' => $filesize,
						'SIZE_VAR' => $size_lang,
						'COMMENT' => $comment,
						'L_DOWNLOADED_VIEWED' => $lang['Downloaded'],
						'TARGET_BLANK' => $target_blank
						)
					);
				}
			}
		}
	}
}

/**
* END DISPLAY ATTACHMENTS -> PREVIEW
*/

/**
* Assign Variables and Definitions based on the fetched Attachments - internal
* used by all displaying functions, the Data was collected before, it's only dependend on the template used. :)
* before this function is usable, init_display_attachments have to be called for specific pages (pm, posting, review etc...)
*/
function display_attachments($post_id, $type = 'postrow')
{
	global $db, $config, $template, $user, $lang;
	global $upload_dir, $allowed_extensions, $display_categories, $download_modes, $attachments, $upload_icons, $username_from;
	$num_attachments = sizeof($attachments['_' . $post_id]);
	if ($num_attachments == 0)
	{
		return;
	}

	$template->assign_block_vars($type . '.attach', array());

	for ($i = 0; $i < $num_attachments; $i++)
	{
		// Some basic things...
		$physical_filename = get_physical_filename($attachments['_' . $post_id][$i]['physical_filename'], false);
		$physical_filename_thumb = get_physical_filename($attachments['_' . $post_id][$i]['physical_filename'], true);
		$filename = $upload_dir . '/' . $physical_filename;
		$thumbnail_filename = $upload_dir . '/' . $physical_filename_thumb;

		$upload_image = '';

		if ($config['upload_img'] != '' && trim($upload_icons[$attachments['_' . $post_id][$i]['extension']]) == '')
		{
			$upload_image = '<img src="' . $config['upload_img'] . '" alt="" />';
		}
		elseif (trim($upload_icons[$attachments['_' . $post_id][$i]['extension']]) != '')
		{
			$upload_image = '<img src="' . $upload_icons[$attachments['_' . $post_id][$i]['extension']] . '" alt="" />';
		}

		$filesize = $attachments['_' . $post_id][$i]['filesize'];
		$size_lang = ($filesize >= 1048576) ? $lang['MB'] : (($filesize >= 1024) ? $lang['KB'] : $lang['Bytes']);

		if ($filesize >= 1048576)
		{
			$filesize = (round((round($filesize / 1048576 * 100) / 100), 2));
		}
		elseif ($filesize >= 1024)
		{
			$filesize = (round((round($filesize / 1024 * 100) / 100), 2));
		}

		$display_name = $attachments['_' . $post_id][$i]['real_filename'];
		$comment = $attachments['_' . $post_id][$i]['comment'];
		$comment = str_replace("\n", '<br />', $comment);

		$denied = false;

		// Admin is allowed to view forbidden Attachments, but the error-message is displayed too to inform the Admin
		if (!in_array($attachments['_' . $post_id][$i]['extension'], $allowed_extensions))
		{
			$denied = true;

			$template->assign_block_vars($type . '.attach.denyrow', array(
				'L_DENIED' => sprintf($lang['Extension_disabled_after_posting'], $attachments['_' . $post_id][$i]['extension'])
				)
			);
		}

		if (!$denied || $user->data['user_level'] == ADMIN)
		{
			// Some basic Template Vars
			$template->assign_vars(array(
				'L_DESCRIPTION' => $lang['Description'],
				'L_DOWNLOAD' => $lang['Download'],
				'L_FILENAME' => $lang['File_name'],
				'L_FILESIZE' => $lang['Filesize']
				)
			);

			// define category
			$image = false;
			$stream = false;
			$swf = false;
			$thumbnail = false;
			$link = false;

			if (intval($display_categories[$attachments['_' . $post_id][$i]['extension']]) == STREAM_CAT)
			{
				$stream = true;
			}
			elseif (intval($display_categories[$attachments['_' . $post_id][$i]['extension']]) == SWF_CAT)
			{
				$swf = true;
			}
			elseif (intval($display_categories[$attachments['_' . $post_id][$i]['extension']]) == IMAGE_CAT && intval($config['img_display_inlined']))
			{
				if (intval($config['img_link_width']) != 0 || intval($config['img_link_height']) != 0)
				{
					list($width, $height) = image_getdimension($filename);

					if (($width == 0) && ($height == 0))
					{
						$image = true;
					}
					else
					{
						if ($width <= intval($config['img_link_width']) && $height <= intval($config['img_link_height']))
						{
							$image = true;
						}
					}
				}
				else
				{
					$image = true;
				}
			}

			if (intval($display_categories[$attachments['_' . $post_id][$i]['extension']]) == IMAGE_CAT && $attachments['_' . $post_id][$i]['thumbnail'] == 1)
			{
				$thumbnail = true;
				$image = false;
			}

			if (!$image && !$stream && !$swf && !$thumbnail)
			{
				$link = true;
			}

			if ($image)
			{
				// Images
				// NOTE: If you want to use the download.php everytime an image is displayed inlined, replace the
				// Section between BEGIN and END with (Without the // of course):
				//	$img_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id']);
				//	$download_link = true;
				//
				//
				if (intval($config['allow_ftp_upload']) && trim($config['download_path']) == '')
				{
					$img_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id']);
					$download_link = true;
				}
				else
				{
					// Check if we can reach the file or if it is stored outside of the webroot
					if (($config['upload_dir'][0] == '/') || (($config['upload_dir'][0] != '/') && ($config['upload_dir'][1] == ':')))
					{
						$img_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id']);
						$download_link = true;
					}
					else
					{
						// BEGIN
						$img_source = $filename;
						$download_link = false;
						// END
					}
				}
				$max_image_width = intval($config['liw_max_width']);

				$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
				$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
				$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
				$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['script_path']));
				$script_name = ($script_name == '') ? $script_name : '/' . $script_name;

				if (($max_image_width != 0) && ($config['liw_attach_enabled'] == 1) && !isset($username_from))
				{
					if (!function_exists('liw_get_dimensions'))
					{
						include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_includes.' . PHP_EXT);
					}
					list($image_width, $image_height) = liw_get_dimensions($server_protocol . $server_name . $server_port . $script_name . '/' . $img_source, $post_id);

					if ($image_width && ($image_width > $max_image_width) || empty($image_width) || empty($image_height))
					{
						$img_code = generate_liw_img_popup($img_source, $image_width, $image_height, $max_image_width);
					}
					else
					{
						$img_code = '<img src="' . $img_source . '" alt="' . $display_name . '" />';
					}
				}
				else
				{
					$img_code = '<img src="' . $img_source . '" alt="' . $display_name . '" />';
				}
				$download_count_link = (($attachments['_' . $post_id][$i]['download_count'] > '0') && ($user->data['user_level'] == ADMIN)) ? ('<a href="' . append_sid(IP_ROOT_PATH . 'attachments.' . PHP_EXT . '?attach_id=' . $attachments['_' . $post_id][$i]['attach_id']) . '">' . sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']) . '</a>') : sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']);
				$template->assign_block_vars($type . '.attach.cat_images', array(
					'DOWNLOAD_NAME' => $display_name,
					'S_UPLOAD_IMAGE' => $upload_image,
					'IMG_CODE' => $img_code,
					'IMG_SRC' => $img_source,
					'FILESIZE' => $filesize,
					'SIZE_VAR' => $size_lang,
					'COMMENT' => $comment,
					'L_DOWNLOADED_VIEWED' => $lang['Viewed'],
					'L_DOWNLOAD_COUNT' => $download_count_link
					)
				);

				// Directly Viewed Image ... update the download count
				if (!$download_link)
				{
					update_attachments_stats($attachments['_' . $post_id][$i]['attach_id']);
				}
			}

			if ($thumbnail)
			{
				// Images, but display Thumbnail
				// NOTE: If you want to use the download.php everytime an thumbnail is displayed inlined, replace the
				// Section between BEGIN and END with (Without the // of course):
				//	$thumb_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id'] . '&thumb=1');
				//
				if (intval($config['allow_ftp_upload']) && (trim($config['download_path']) == ''))
				{
					$thumb_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id'] . '&thumb=1');
				}
				else
				{
					// Check if we can reach the file or if it is stored outside of the webroot
					if (($config['upload_dir'][0] == '/') || (($config['upload_dir'][0] != '/') && ($config['upload_dir'][1] == ':')))
					{
						$thumb_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id'] . '&thumb=1');
					}
					else
					{
						if (file_exists($thumbnail_filename))
						{
							// BEGIN
							$thumb_source = $thumbnail_filename;
							// END
						}
						else
						{
							$thumb_source = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id'] . '&thumb=1');
						}
					}
				}

				$download_count_link = (($attachments['_' . $post_id][$i]['download_count'] > '0') && ($user->data['user_level'] == ADMIN)) ? ('<a href="' . append_sid(IP_ROOT_PATH . 'attachments.' . PHP_EXT . '?attach_id=' . $attachments['_' . $post_id][$i]['attach_id']) . '">' . sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']) . '</a>') : sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']);
				$template->assign_block_vars($type . '.attach.cat_thumb_images', array(
					'DOWNLOAD_NAME' => $display_name,
					'S_UPLOAD_IMAGE' => $upload_image,

					'IMG_SRC' => append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id']),
					'IMG_THUMB_SRC' => $thumb_source,
					'FILESIZE' => $filesize,
					'SIZE_VAR' => $size_lang,
					'COMMENT' => $comment,
					'L_DOWNLOADED_VIEWED' => $lang['Viewed'],
					'L_DOWNLOAD_COUNT' => $download_count_link
					)
				);
			}

			if ($stream)
			{
				// Streams
				$download_count_link = (($attachments['_' . $post_id][$i]['download_count'] > '0') && ($user->data['user_level'] == ADMIN)) ? ('<a href="' . append_sid(IP_ROOT_PATH . 'attachments.' . PHP_EXT . '?attach_id=' . $attachments['_' . $post_id][$i]['attach_id']) . '">' . sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']) . '</a>') : sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']);
				$template->assign_block_vars($type . '.attach.cat_stream', array(
					'U_DOWNLOAD_LINK' => $filename,
					'S_UPLOAD_IMAGE' => $upload_image,

//					'U_DOWNLOAD_LINK' => append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id']),
					'DOWNLOAD_NAME' => $display_name,
					'FILESIZE' => $filesize,
					'SIZE_VAR' => $size_lang,
					'COMMENT' => $comment,
					'L_DOWNLOADED_VIEWED' => $lang['Viewed'],
					'L_DOWNLOAD_COUNT' => $download_count_link
					)
				);

				// Viewed/Heared File ... update the download count (download.php is not called here)
				update_attachments_stats($attachments['_' . $post_id][$i]['attach_id']);
			}

			if ($swf)
			{
				// Macromedia Flash Files
				list($width, $height) = swf_getdimension($filename);

				$download_count_link = (($attachments['_' . $post_id][$i]['download_count'] > '0') && ($user->data['user_level'] == ADMIN)) ? ('<a href="' . append_sid(IP_ROOT_PATH . 'attachments.' . PHP_EXT . '?attach_id=' . $attachments['_' . $post_id][$i]['attach_id']) . '">' . sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']) . '</a>') : sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']);
				$template->assign_block_vars($type . '.attach.cat_swf', array(
					'U_DOWNLOAD_LINK' => $filename,
					'S_UPLOAD_IMAGE' => $upload_image,

					'DOWNLOAD_NAME' => $display_name,
					'FILESIZE' => $filesize,
					'SIZE_VAR' => $size_lang,
					'COMMENT' => $comment,
					'L_DOWNLOADED_VIEWED' => $lang['Viewed'],
					'L_DOWNLOAD_COUNT' => $download_count_link,
					'WIDTH' => $width,
					'HEIGHT' => $height
					)
				);

				// Viewed/Heared File ... update the download count (download.php is not called here)
				update_attachments_stats($attachments['_' . $post_id][$i]['attach_id']);
			}

			if ($link)
			{
				$target_blank = 'target="_blank"'; //((intval($display_categories[$attachments['_' . $post_id][$i]['extension']]) == IMAGE_CAT)) ? 'target="_blank"' : '';

				// display attachment
				$download_count_link = (($attachments['_' . $post_id][$i]['download_count'] > '0') && ($user->data['user_level'] == ADMIN)) ? ('<a href="' . append_sid(IP_ROOT_PATH . 'attachments.' . PHP_EXT . '?attach_id=' . $attachments['_' . $post_id][$i]['attach_id']) . '">' . sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']) . '</a>') : sprintf($lang['Download_number'], $attachments['_' . $post_id][$i]['download_count']);
				$template->assign_block_vars($type . '.attach.attachrow', array(
					'U_DOWNLOAD_LINK' => append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments['_' . $post_id][$i]['attach_id']),
					'S_UPLOAD_IMAGE' => $upload_image,

					'DOWNLOAD_NAME' => $display_name,
					'FILESIZE' => $filesize,
					'SIZE_VAR' => $size_lang,
					'COMMENT' => $comment,
					'TARGET_BLANK' => $target_blank,

					'L_DOWNLOADED_VIEWED' => $lang['Downloaded'],
					'L_DOWNLOAD_COUNT' => $download_count_link
					)
				);

			}
		}
	}
}

?>