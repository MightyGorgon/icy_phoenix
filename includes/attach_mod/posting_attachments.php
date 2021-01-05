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

/**
* @package attachment_mod
* Base Class for Attaching
*/
class attach_parent
{
	var $post_attach = false;
	var $attach_filename = '';
	var $filename = '';
	var $type = '';
	var $extension = '';
	var $file_comment = '';
	var $num_attachments = 0; // number of attachments in message
	var $filesize = 0;
	var $filetime = 0;
	var $thumbnail = 0;
	var $page = 0; // On which page we are on ? This should be filled by child classes.

	// Switches
	var $add_attachment_body = 0;
	var $posted_attachments_body = 0;

	/**
	* Constructor
	*/
	function __construct()
	{
		$this->attach_parent_init();
	}

	/**
	* Attach Parent INIT
	*/
	function attach_parent_init()
	{
		$this->add_attachment_body = request_var('add_attachment_body', 0);
		$this->posted_attachments_body = request_var('posted_attachments_body', 0);

		$this->file_comment = request_var('filecomment', '', true);
		$this->attachment_id_list = request_var('attach_id_list', array(0));
		$this->attachment_comment_list = request_var('comment_list', array(''), true);
		$this->attachment_filesize_list = request_var('filesize_list', array(0));
		$this->attachment_filetime_list = request_var('filetime_list', array(0));
		$this->attachment_filename_list = request_var('filename_list', array(''));
		$this->attachment_extension_list = request_var('extension_list', array(''));
		$this->attachment_mimetype_list = request_var('mimetype_list', array(''));

		$this->filename = (isset($_FILES['fileupload']) && isset($_FILES['fileupload']['name']) && $_FILES['fileupload']['name'] != 'none') ? trim(stripslashes($_FILES['fileupload']['name'])) : '';

		$this->attachment_list = request_var('attachment_list', array(''));
		$this->attachment_thumbnail_list = request_var('attach_thumbnail_list', array(0));
	}

	/**
	* Get Quota Limits
	*/
	function get_quota_limits($userdata_quota, $user_id = 0)
	{
		global $config, $db;

		//
		// Define Filesize Limits (Prepare Quota Settings)
		// Priority: User, Group, Management
		//
		// This method is somewhat query intensive, but i think because this one is only executed while attaching a file,
		// it does not make much sense to come up with an new db-entry.
		// Maybe i will change this in a future version, where you are able to disable the User Quota Feature at all (using
		// Default Limits for all Users/Groups)
		//

		// Change this to 'group;user' if you want to have first priority on group quota settings.
//		$priority = 'group;user';
		$priority = 'user;group';

		if ($userdata_quota['user_level'] == ADMIN)
		{
			$config['pm_filesize_limit'] = 0; // Unlimited
			$config['upload_filesize_limit'] = 0; // Unlimited
			return;
		}

		if ($this->page == PAGE_PRIVMSGS)
		{
			$quota_type = QUOTA_PM_LIMIT;
			$limit_type = 'pm_filesize_limit';
			$default = 'max_filesize_pm';
		}
		else
		{
			$quota_type = QUOTA_UPLOAD_LIMIT;
			$limit_type = 'upload_filesize_limit';
			$default = 'attachment_quota';
		}

		if (!$user_id)
		{
			$user_id = intval($userdata_quota['user_id']);
		}

		$priority = explode(';', $priority);
		$found = false;

		for ($i = 0; $i < sizeof($priority); $i++)
		{
			if (($priority[$i] == 'group') && (!$found))
			{
				// Get Group Quota, if we find one, we have our quota
				$sql = 'SELECT u.group_id
					FROM ' . USER_GROUP_TABLE . ' u, ' . GROUPS_TABLE . ' g
					WHERE g.group_single_user = 0
						AND u.group_id = g.group_id
						AND u.user_id = ' . $user_id;
				$result = $db->sql_query($sql);
				$rows = $db->sql_fetchrowset($result);
				$num_rows = $db->sql_numrows($result);
				$db->sql_freeresult($result);

				if ($num_rows > 0)
				{
					$group_id = array();

					for ($j = 0; $j < $num_rows; $j++)
					{
						$group_id[] = (int) $rows[$j]['group_id'];
					}

					$sql = 'SELECT l.quota_limit
						FROM ' . QUOTA_TABLE . ' q, ' . QUOTA_LIMITS_TABLE . ' l
						WHERE q.group_id IN (' . implode(', ', $group_id) . ')
							AND q.group_id <> 0
							AND q.quota_type = ' . $quota_type . '
							AND q.quota_limit_id = l.quota_limit_id
						ORDER BY l.quota_limit DESC
						LIMIT 1';
					$result = $db->sql_query($sql);

					if ($db->sql_numrows($result) > 0)
					{
						$row = $db->sql_fetchrow($result);
						$config[$limit_type] = $row['quota_limit'];
						$found = true;
					}
					$db->sql_freeresult($result);
				}
			}

			if ($priority[$i] == 'user' && !$found)
			{
				// Get User Quota, if the user is not in a group or the group has no quotas
				$sql = 'SELECT l.quota_limit
					FROM ' . QUOTA_TABLE . ' q, ' . QUOTA_LIMITS_TABLE . ' l
					WHERE q.user_id = ' . $user_id . '
						AND q.user_id <> 0
						AND q.quota_type = ' . $quota_type . '
						AND q.quota_limit_id = l.quota_limit_id
					LIMIT 1';
				$result = $db->sql_query($sql);

				if ($db->sql_numrows($result) > 0)
				{
					$row = $db->sql_fetchrow($result);
					$config[$limit_type] = $row['quota_limit'];
					$found = true;
				}
				$db->sql_freeresult($result);
			}
		}

		if (!$found)
		{
			// Set Default Quota Limit
			$quota_id = ($quota_type == QUOTA_UPLOAD_LIMIT) ? $config['default_upload_quota'] : $config['default_pm_quota'];

			if ($quota_id == 0)
			{
				$config[$limit_type] = $config[$default];
			}
			else
			{
				$sql = 'SELECT quota_limit
					FROM ' . QUOTA_LIMITS_TABLE . '
					WHERE quota_limit_id = ' . (int) $quota_id . '
					LIMIT 1';
				$result = $db->sql_query($sql);

				if ($db->sql_numrows($result) > 0)
				{
					$row = $db->sql_fetchrow($result);
					$config[$limit_type] = $row['quota_limit'];
				}
				else
				{
					$config[$limit_type] = $config[$default];
				}
				$db->sql_freeresult($result);
			}
		}

		// Never exceed the complete Attachment Upload Quota
		if ($quota_type == QUOTA_UPLOAD_LIMIT)
		{
			if ($config[$limit_type] > $config[$default])
			{
				$config[$limit_type] = $config[$default];
			}
		}
	}

	/**
	* Handle all modes... (intern)
	* @private
	*/
	function handle_attachments($mode)
	{
		global $is_auth, $config, $refresh, $post_id, $submit, $preview, $error, $error_msg, $lang, $template, $user, $db;

		//
		// ok, what shall we do ;)
		//

		// Some adjustments for PM's
		if ($this->page == PAGE_PRIVMSGS)
		{
			global $privmsg_id;

			$post_id = $privmsg_id;

			if ($mode == 'post')
			{
				$mode = 'newtopic';
			}
			elseif ($mode == 'edit')
			{
				$mode = 'editpost';
			}

			if ($user->data['user_level'] == ADMIN)
			{
				$is_auth['auth_attachments'] = 1;
				$max_attachments = ADMIN_MAX_ATTACHMENTS;
			}
			else
			{
				$is_auth['auth_attachments'] = intval($config['allow_pm_attach']);
				$max_attachments = intval($config['max_attachments_pm']);
			}
		}
		else
		{
			if ($user->data['user_level'] == ADMIN)
			{
				$max_attachments = ADMIN_MAX_ATTACHMENTS;
			}
			else
			{
				$max_attachments = intval($config['max_attachments']);
			}
		}

		// nothing, if the user is not authorized or attachment mod disabled
		if (intval($config['disable_attachments_mod']) || !$is_auth['auth_attachments'])
		{
			return false;
		}

		// Init Vars
		$attachments = array();

		if (!$refresh)
		{
			$add = (isset($_POST['add_attachment'])) ? true : false;
			$delete = (isset($_POST['del_attachment'])) ? true : false;
			$edit = (isset($_POST['edit_comment'])) ? true : false;
			$update_attachment = (isset($_POST['update_attachment'])) ? true : false;
			$del_thumbnail = (isset($_POST['del_thumbnail'])) ? true : false;

			$add_attachment_box = (!empty($_POST['add_attachment_box'])) ? true : false;
			$posted_attachments_box = (!empty($_POST['posted_attachments_box'])) ? true : false;

			$refresh = $add || $delete || $edit || $del_thumbnail || $update_attachment || $add_attachment_box || $posted_attachments_box;
		}

		// Get Attachments
		if ($this->page == PAGE_PRIVMSGS)
		{
			$attachments = get_attachments_from_pm($post_id);
		}
		else
		{
			$attachments = get_attachments_from_post($post_id);
		}

		if ($this->page == PAGE_PRIVMSGS)
		{
			if ($user->data['user_level'] == ADMIN)
			{
				$auth = true;
			}
			else
			{
				$auth = (intval($config['allow_pm_attach'])) ? true : false;
			}

			if (sizeof($attachments) == 1)
			{
				$template->assign_block_vars('switch_attachments',array());

				$template->assign_vars(array(
					'L_DELETE_ATTACHMENTS' => $lang['Delete_attachment'])
				);
			}
			elseif (sizeof($attachments) > 0)
			{
				$template->assign_block_vars('switch_attachments',array());

				$template->assign_vars(array(
					'L_DELETE_ATTACHMENTS' => $lang['Delete_attachments'])
				);
			}
		}
		else
		{
			$auth = ($is_auth['auth_edit'] || $is_auth['auth_mod']) ? true : false;
		}

		if (!$submit && $mode == 'editpost' && $auth)
		{
			if (!$refresh && !$preview && !$error && !isset($_POST['del_poll_option']))
			{
				for ($i = 0; $i < sizeof($attachments); $i++)
				{
					$this->attachment_list[] = $attachments[$i]['physical_filename'];
					$this->attachment_comment_list[] = $attachments[$i]['comment'];
					$this->attachment_filename_list[] = $attachments[$i]['real_filename'];
					$this->attachment_extension_list[] = $attachments[$i]['extension'];
					$this->attachment_mimetype_list[] = $attachments[$i]['mimetype'];
					$this->attachment_filesize_list[] = $attachments[$i]['filesize'];
					$this->attachment_filetime_list[] = $attachments[$i]['filetime'];
					$this->attachment_id_list[] = $attachments[$i]['attach_id'];
					$this->attachment_thumbnail_list[] = $attachments[$i]['thumbnail'];
				}
			}
		}

		$this->num_attachments = sizeof($this->attachment_list);

		if ($submit && $mode != 'vote')
		{
			if ($mode == 'newtopic' || $mode == 'reply' || $mode == 'editpost')
			{
				if ($this->filename != '')
				{
					if ($this->num_attachments < intval($max_attachments))
					{
						$this->upload_attachment($this->page);

						if (!$error && $this->post_attach)
						{
							array_unshift($this->attachment_list, $this->attach_filename);
							array_unshift($this->attachment_comment_list, $this->file_comment);
							array_unshift($this->attachment_filename_list, $this->filename);
							array_unshift($this->attachment_extension_list, $this->extension);
							array_unshift($this->attachment_mimetype_list, $this->type);
							array_unshift($this->attachment_filesize_list, $this->filesize);
							array_unshift($this->attachment_filetime_list, $this->filetime);
							array_unshift($this->attachment_id_list, '0');
							array_unshift($this->attachment_thumbnail_list, $this->thumbnail);

							$this->file_comment = '';

							// This Variable is set to false here, because the Attachment Mod enter Attachments into the
							// Database in two modes, one if the id_list is 0 and the second one if post_attach is true
							// Since post_attach is automatically switched to true if an Attachment got added to the filesystem,
							// but we are assigning an id of 0 here, we have to reset the post_attach variable to false.
							//
							// This is very relevant, because it could happen that the post got not submitted, but we do not
							// know this circumstance here. We could be at the posting page or we could be redirected to the entered
							// post. :)
							$this->post_attach = false;
						}
					}
					else
					{
						$error = true;
						if (!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['Too_many_attachments'], intval($max_attachments));
					}
				}
			}
		}

		if ($preview || $refresh || $error)
		{
			$delete_attachment = (isset($_POST['del_attachment'])) ? true : false;
			$delete_thumbnail = (isset($_POST['del_thumbnail'])) ? true : false;

			$add_attachment = (isset($_POST['add_attachment'])) ? true : false;
			$edit_attachment = (isset($_POST['edit_comment'])) ? true : false;
			$update_attachment = (isset($_POST['update_attachment']) ) ? true : false;

			// Perform actions on temporary attachments
			if ($delete_attachment || $delete_thumbnail)
			{
				// store old values
				$actual_id_list = request_var('attach_id_list', array(0));
				$actual_comment_list = request_var('comment_list', array(''), true);
				$actual_filename_list = request_var('filename_list', array(''));
				$actual_extension_list = request_var('extension_list', array(''));
				$actual_mimetype_list = request_var('mimetype_list', array(''));
				$actual_filesize_list = request_var('filesize_list', array(0));
				$actual_filetime_list = request_var('filetime_list', array(0));

				$actual_list = request_var('attachment_list', array(''));
				$actual_thumbnail_list = request_var('attach_thumbnail_list', array(0));

				// clean values
				$this->attachment_list = array();
				$this->attachment_comment_list = array();
				$this->attachment_filename_list = array();
				$this->attachment_extension_list = array();
				$this->attachment_mimetype_list = array();
				$this->attachment_filesize_list = array();
				$this->attachment_filetime_list = array();
				$this->attachment_id_list = array();
				$this->attachment_thumbnail_list = array();

				// restore values :)
				if (isset($_POST['attachment_list']))
				{
					for ($i = 0; $i < sizeof($actual_list); $i++)
					{
						$restore = false;
						$del_thumb = false;

						if ($delete_thumbnail)
						{
							if (!isset($_POST['del_thumbnail'][$actual_list[$i]]))
							{
								$restore = true;
							}
							else
							{
								$del_thumb = true;
							}
						}

						if ($delete_attachment)
						{
							if (!isset($_POST['del_attachment'][$actual_list[$i]]))
							{
								$restore = true;
							}
						}

						if ($restore)
						{
							$this->attachment_list[] = $actual_list[$i];
							$this->attachment_comment_list[] = $actual_comment_list[$i];
							$this->attachment_filename_list[] = $actual_filename_list[$i];
							$this->attachment_extension_list[] = $actual_extension_list[$i];
							$this->attachment_mimetype_list[] = $actual_mimetype_list[$i];
							$this->attachment_filesize_list[] = $actual_filesize_list[$i];
							$this->attachment_filetime_list[] = $actual_filetime_list[$i];
							$this->attachment_id_list[] = $actual_id_list[$i];
							$this->attachment_thumbnail_list[] = $actual_thumbnail_list[$i];
						}
						elseif (!$del_thumb)
						{
							// delete selected attachment
							if ($actual_id_list[$i] == '0' )
							{
								unlink_attach($actual_list[$i]);

								if ($actual_thumbnail_list[$i] == 1)
								{
									unlink_attach($actual_list[$i], MODE_THUMBNAIL);
								}
							}
							else
							{
								delete_attachment($post_id, $actual_id_list[$i], $this->page);
							}
						}
						elseif ($del_thumb)
						{
							// delete selected thumbnail
							$this->attachment_list[] = $actual_list[$i];
							$this->attachment_comment_list[] = $actual_comment_list[$i];
							$this->attachment_filename_list[] = $actual_filename_list[$i];
							$this->attachment_extension_list[] = $actual_extension_list[$i];
							$this->attachment_mimetype_list[] = $actual_mimetype_list[$i];
							$this->attachment_filesize_list[] = $actual_filesize_list[$i];
							$this->attachment_filetime_list[] = $actual_filetime_list[$i];
							$this->attachment_id_list[] = $actual_id_list[$i];
							$this->attachment_thumbnail_list[] = 0;

							if ($actual_id_list[$i] == 0)
							{
								unlink_attach($actual_list[$i], MODE_THUMBNAIL);
							}
							else
							{
								$sql = 'UPDATE ' . ATTACHMENTS_DESC_TABLE . '
									SET thumbnail = 0
									WHERE attach_id = ' . (int) $actual_id_list[$i];
								$db->sql_query($sql);
							}
						}
					}
				}
			}
			elseif ($edit_attachment || $update_attachment || $add_attachment || $preview)
			{
				if ($edit_attachment)
				{
					$actual_comment_list = request_var('comment_list', array(''), true);

					$this->attachment_comment_list = array();

					for ($i = 0; $i < sizeof($this->attachment_list); $i++)
					{
						$this->attachment_comment_list[$i] = $actual_comment_list[$i];
					}
				}

				if ($update_attachment)
				{
					if ($this->filename == '')
					{
						$error = true;
						if(!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= $lang['Error_empty_add_attachbox'];
					}

					$this->upload_attachment($this->page);

					if (!$error)
					{
						$actual_list = request_var('attachment_list', array(''));
						$actual_id_list = request_var('attach_id_list', array(0));

						$attachment_id = 0;
						$actual_element = 0;

						for ($i = 0; $i < sizeof($actual_id_list); $i++)
						{
							if (isset($_POST['update_attachment'][$actual_id_list[$i]]))
							{
								$attachment_id = intval($actual_id_list[$i]);
								$actual_element = $i;
							}
						}

						// Get current informations to delete the Old Attachment
						$sql = 'SELECT physical_filename, comment, thumbnail
							FROM ' . ATTACHMENTS_DESC_TABLE . '
							WHERE attach_id = ' . (int) $attachment_id;
						$result = $db->sql_query($sql);

						if ($db->sql_numrows($result) != 1)
						{
							$error = true;
							if(!empty($error_msg))
							{
								$error_msg .= '<br />';
							}
							$error_msg .= $lang['Error_missing_old_entry'];
						}

						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$comment = (trim($this->file_comment) == '') ? trim($row['comment']) : trim($this->file_comment);

						// Update Entry
						$sql_ary = array(
							'physical_filename' => (string) basename($this->attach_filename),
							'real_filename' => (string) basename($this->filename),
							'comment' => (string) $comment,
							'extension' => (string) strtolower($this->extension),
							'mimetype' => (string) strtolower($this->type),
							'filesize' => (int) $this->filesize,
							'filetime' => (int) $this->filetime,
							'thumbnail' => (int) $this->thumbnail
						);

						$sql = 'UPDATE ' . ATTACHMENTS_DESC_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE attach_id = ' . (int) $attachment_id;
						$db->sql_query($sql);

						// Delete the Old Attachment
						unlink_attach($row['physical_filename']);

						if (intval($row['thumbnail']) == 1)
						{
							unlink_attach($row['physical_filename'], MODE_THUMBNAIL);
						}

						// Make sure it is displayed
						$this->attachment_list[$actual_element] = $this->attach_filename;
						$this->attachment_comment_list[$actual_element] = $comment;
						$this->attachment_filename_list[$actual_element] = $this->filename;
						$this->attachment_extension_list[$actual_element] = $this->extension;
						$this->attachment_mimetype_list[$actual_element] = $this->type;
						$this->attachment_filesize_list[$actual_element] = $this->filesize;
						$this->attachment_filetime_list[$actual_element] = $this->filetime;
						$this->attachment_id_list[$actual_element] = $actual_id_list[$actual_element];
						$this->attachment_thumbnail_list[$actual_element] = $this->thumbnail;
						$this->file_comment = '';
					}
				}

				if (($add_attachment || $preview) && $this->filename != '')
				{
					if ($this->num_attachments < intval($max_attachments))
					{
						$this->upload_attachment($this->page);

						if (!$error)
						{
							array_unshift($this->attachment_list, $this->attach_filename);
							array_unshift($this->attachment_comment_list, $this->file_comment);
							array_unshift($this->attachment_filename_list, $this->filename);
							array_unshift($this->attachment_extension_list, $this->extension);
							array_unshift($this->attachment_mimetype_list, $this->type);
							array_unshift($this->attachment_filesize_list, $this->filesize);
							array_unshift($this->attachment_filetime_list, $this->filetime);
							array_unshift($this->attachment_id_list, '0');
							array_unshift($this->attachment_thumbnail_list, $this->thumbnail);

							$this->file_comment = '';
						}
					}
					else
					{
						$error = true;
						if(!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['Too_many_attachments'], intval($max_attachments));
					}
				}
			}
		}

		return true;
	}

	/**
	* Basic Insert Attachment Handling for all Message Types
	*/
	function do_insert_attachment($mode, $message_type, $message_id)
	{
		global $db, $upload_dir;

		if (intval($message_id) < 0)
		{
			return false;
		}

		if ($message_type == 'pm')
		{
			global $user, $to_userdata;

			$post_id = 0;
			$privmsgs_id = (int) $message_id;
			$user_id_1 = (int) $user->data['user_id'];
			$user_id_2 = (int) $to_userdata['user_id'];
		}
		elseif ($message_type = 'post')
		{
			global $post_info, $user;

			$post_id = (int) $message_id;
			$privmsgs_id = 0;
			$user_id_1 = (isset($post_info['poster_id'])) ? (int) $post_info['poster_id'] : 0;
			$user_id_2 = 0;

			if (!$user_id_1)
			{
				$user_id_1 = (int) $user->data['user_id'];
			}
		}

		if ($mode == 'attach_list')
		{
			for ($i = 0; $i < sizeof($this->attachment_list); $i++)
			{
				if ($this->attachment_id_list[$i])
				{
					// update entry in db if attachment already stored in db and filespace
					$sql = 'UPDATE ' . ATTACHMENTS_DESC_TABLE . "
						SET comment = '" . $db->sql_escape($this->attachment_comment_list[$i]) . "'
						WHERE attach_id = " . $this->attachment_id_list[$i];
					$db->sql_query($sql);
				}
				else
				{
					// insert attachment into db
					$sql_ary = array(
						'physical_filename' => (string) basename($this->attachment_list[$i]),
						'real_filename' => (string) basename($this->attachment_filename_list[$i]),
						'comment' => (string) $this->attachment_comment_list[$i],
						'extension' => (string) strtolower($this->attachment_extension_list[$i]),
						'mimetype' => (string) strtolower($this->attachment_mimetype_list[$i]),
						'filesize' => (int) $this->attachment_filesize_list[$i],
						'filetime' => (int) $this->attachment_filetime_list[$i],
						'thumbnail' => (int) $this->attachment_thumbnail_list[$i]
					);

					$sql = 'INSERT INTO ' . ATTACHMENTS_DESC_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
					$db->sql_query($sql);
					$attach_id = $db->sql_nextid();

					$sql_ary = array(
						'attach_id' => (int) $attach_id,
						'post_id' => (int) $post_id,
						'privmsgs_id' => (int) $privmsgs_id,
						'user_id_1' => (int) $user_id_1,
						'user_id_2' => (int) $user_id_2
					);

					$sql = 'INSERT INTO ' . ATTACHMENTS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
					$db->sql_query($sql);
				}
			}

			return true;
		}

		if ($mode == 'last_attachment')
		{
			if ($this->post_attach && !isset($_POST['update_attachment']))
			{
				// insert attachment into db, here the user submited it directly
				$sql_ary = array(
					'physical_filename' => (string) basename($this->attach_filename),
					'real_filename' => (string) basename($this->filename),
					'comment' => (string) $this->file_comment,
					'extension' => (string) strtolower($this->extension),
					'mimetype' => (string) strtolower($this->type),
					'filesize' => (int) $this->filesize,
					'filetime' => (int) $this->filetime,
					'thumbnail' => (int) $this->thumbnail
				);

				$sql = 'INSERT INTO ' . ATTACHMENTS_DESC_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
				// Inform the user that his post has been created, but nothing is attached
				$db->sql_query($sql);
				$attach_id = $db->sql_nextid();

				$sql_ary = array(
					'attach_id' => (int) $attach_id,
					'post_id' => (int) $post_id,
					'privmsgs_id' => (int) $privmsgs_id,
					'user_id_1' => (int) $user_id_1,
					'user_id_2' => (int) $user_id_2
				);

				$sql = 'INSERT INTO ' . ATTACHMENTS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
				$db->sql_query($sql);
			}
		}
	}

	/**
	* Attachment Mod entry switch/output (intern)
	* @private
	*/
	function display_attachment_bodies()
	{
		global $config, $db, $is_auth, $lang, $mode, $template, $upload_dir, $user, $forum_id;

		// Choose what to display
		$value_add = $value_posted = 0;

		if (intval($config['show_apcp']))
		{
			if (!empty($_POST['add_attachment_box']))
			{
				$value_add = ($this->add_attachment_body == 0) ? 1 : 0;
				$this->add_attachment_body = $value_add;
			}
			else
			{
				$value_add = ($this->add_attachment_body == 0) ? 0 : 1;
			}

			if (!empty($_POST['posted_attachments_box']))
			{
				$value_posted = ($this->posted_attachments_body == 0) ? 1 : 0;
				$this->posted_attachments_body = $value_posted;
			}
			else
			{
				$value_posted = ($this->posted_attachments_body == 0) ? 0 : 1;
			}
			$template->assign_block_vars('show_apcp', array());
		}
		else
		{
			$this->add_attachment_body = 1;
			$this->posted_attachments_body = 1;
		}

		$template->set_filenames(array('attachbody' => 'posting_attach_body.tpl'));

		display_compile_cache_clear($template->files['attachbody'], 'attachbody');

		$s_hidden = '<input type="hidden" name="add_attachment_body" value="' . $value_add . '" />';
		$s_hidden .= '<input type="hidden" name="posted_attachments_body" value="' . $value_posted . '" />';

		if ($this->page == PAGE_PRIVMSGS)
		{
			$u_rules_id = 0;
		}
		else
		{
			$u_rules_id = $forum_id;
		}

		$template->assign_vars(array(
			'L_ATTACH_POSTING_CP' => $lang['Attach_posting_cp'],
			'L_ATTACH_POSTING_CP_EXPLAIN' => $lang['Attach_posting_cp_explain'],
			'L_OPTIONS' => $lang['Options'],
			'L_ADD_ATTACHMENT_TITLE' => $lang['Add_attachment_title'],
			'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
			'L_FILE_NAME' => $lang['File_name'],
			'L_FILE_COMMENT' => $lang['File_comment'],
			'RULES' => '<a href="javascript:attach_rules(' . $u_rules_id . ')">' . $lang['Allowed_extensions_and_sizes'] . '</a>',

			'S_HIDDEN' => $s_hidden
			)
		);

		$attachments = array();

		if (sizeof($this->attachment_list) > 0)
		{
			if (intval($config['show_apcp']))
			{
				$template->assign_block_vars('switch_posted_attachments', array());
			}

			for ($i = 0; $i < sizeof($this->attachment_list); $i++)
			{
				$hidden =  '<input type="hidden" name="attachment_list[]" value="' . $this->attachment_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="filename_list[]" value="' . $this->attachment_filename_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="extension_list[]" value="' . $this->attachment_extension_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="mimetype_list[]" value="' . $this->attachment_mimetype_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="filesize_list[]" value="' . $this->attachment_filesize_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="filetime_list[]" value="' . $this->attachment_filetime_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="attach_id_list[]" value="' . $this->attachment_id_list[$i] . '" />';
				$hidden .= '<input type="hidden" name="attach_thumbnail_list[]" value="' . $this->attachment_thumbnail_list[$i] . '" />';

				if (!$this->posted_attachments_body || sizeof($this->attachment_list) == 0)
				{
					$hidden .= '<input type="hidden" name="comment_list[]" value="' . $this->attachment_comment_list[$i] . '" />';
				}

				$template->assign_block_vars('hidden_row', array(
					'S_HIDDEN' => $hidden)
				);
			}
		}

		if ($this->add_attachment_body)
		{
			init_display_template('attachbody', '{ADD_ATTACHMENT_BODY}', 'add_attachment_body.tpl');

			$form_enctype = 'enctype="multipart/form-data"';

			$template->assign_vars(array(
				'L_ADD_ATTACH_TITLE' => $lang['Add_attachment_title'],
				'L_ADD_ATTACH_EXPLAIN' => $lang['Add_attachment_explain'],
				'L_ADD_ATTACHMENT' => $lang['Add_attachment'],

				'FILE_COMMENT' => htmlspecialchars($this->file_comment),
				'FILESIZE' => $config['max_filesize'],
				'FILENAME' => htmlspecialchars($this->filename),

				'S_FORM_ENCTYPE' => $form_enctype
				)
			);
		}

		if ($this->posted_attachments_body && sizeof($this->attachment_list) > 0)
		{
			init_display_template('attachbody', '{POSTED_ATTACHMENTS_BODY}', 'posted_attachments_body.tpl');

			$template->assign_vars(array(
				'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
				'L_UPDATE_COMMENT' => $lang['Update_comment'],
				'L_UPLOAD_NEW_VERSION' => $lang['Upload_new_version'],
				'L_DELETE_ATTACHMENT' => $lang['Delete_attachment'],
				'L_DELETE_THUMBNAIL' => $lang['Delete_thumbnail'],
				'L_OPTIONS' => $lang['Options']
				)
			);

			for ($i = 0; $i < sizeof($this->attachment_list); $i++)
			{
				if ($this->attachment_id_list[$i] == 0)
				{
					$download_link = $upload_dir . '/' . basename($this->attachment_list[$i]);
				}
				else
				{
					$download_link = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $this->attachment_id_list[$i]);
				}

				$template->assign_block_vars('attach_row', array(
					'FILE_NAME' => $this->attachment_filename_list[$i],
					'ATTACH_FILENAME' => $this->attachment_list[$i],
					'FILE_COMMENT' => $this->attachment_comment_list[$i],
					'ATTACH_ID' => $this->attachment_id_list[$i],

					'U_VIEW_ATTACHMENT' => $download_link
					)
				);

				// Thumbnail there ? And is the User Admin or Mod ? Then present the 'Delete Thumbnail' Button
				if (intval($this->attachment_thumbnail_list[$i]) == 1 && ((isset($is_auth['auth_mod']) && $is_auth['auth_mod']) || $user->data['user_level'] == ADMIN))
				{
					$template->assign_block_vars('attach_row.switch_thumbnail', array());
				}

				if ($this->attachment_id_list[$i])
				{
					$template->assign_block_vars('attach_row.switch_update_attachment', array());
				}
			}
		}

		$template->assign_var_from_handle('ATTACHBOX', 'attachbody');
	}

	/**
	* Upload an Attachment to Filespace (intern)
	*/
	function upload_attachment()
	{
		global $db, $user, $lang, $config, $forum_id, $error, $error_msg, $upload_dir;

		$this->post_attach = ($this->filename != '') ? true : false;

		if ($this->post_attach)
		{
			$r_file = trim(basename(htmlspecialchars($this->filename)));
			$file = $_FILES['fileupload']['tmp_name'];
			$this->type = $_FILES['fileupload']['type'];

			if (isset($_FILES['fileupload']['size']) && $_FILES['fileupload']['size'] == 0)
			{
				message_die(GENERAL_ERROR, 'Tried to upload empty file');
			}

			// Opera add the name to the mime type
			$this->type = (strstr($this->type, '; name')) ? str_replace(strstr($this->type, '; name'), '', $this->type) : $this->type;
			$this->type = strtolower($this->type);
			$this->extension = strtolower(get_extension($this->filename));

			$this->filesize = @filesize($file);
			$this->filesize = intval($this->filesize);

			$sql = 'SELECT g.allow_group, g.max_filesize, g.cat_id, g.forum_permissions
				FROM ' . EXTENSION_GROUPS_TABLE . ' g, ' . EXTENSIONS_TABLE . " e
				WHERE g.group_id = e.group_id
					AND e.extension = '" . $db->sql_escape($this->extension) . "'
				LIMIT 1";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$allowed_filesize = ($row['max_filesize']) ? $row['max_filesize'] : $config['max_filesize'];
			$cat_id = intval($row['cat_id']);
			$auth_cache = trim($row['forum_permissions']);

			// check Filename
			if (preg_match("#[\\/:*?\"<>|]#i", $this->filename))
			{
				$error = true;
				if(!empty($error_msg))
				{
					$error_msg .= '<br />';
				}
				$error_msg .= sprintf($lang['Invalid_filename'], $this->filename);
			}

			// check php upload-size
			if (!$error && $file == 'none')
			{
				$error = true;
				if(!empty($error_msg))
				{
					$error_msg .= '<br />';
				}
				$ini_val = (phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

				$max_size = @$ini_val('upload_max_filesize');

				if ($max_size == '')
				{
					$error_msg .= $lang['Attachment_php_size_na'];
				}
				else
				{
					$error_msg .= sprintf($lang['Attachment_php_size_overrun'], $max_size);
				}
			}

			// Check Extension
			if (!$error && intval($row['allow_group']) == 0)
			{
				$error = true;
				if(!empty($error_msg))
				{
					$error_msg .= '<br />';
				}
				$error_msg .= sprintf($lang['Disallowed_extension'], $this->extension);
			}

			// Check Forum Permissions
			if (!$error && ($this->page != PAGE_PRIVMSGS) && ($user->data['user_level'] != ADMIN) && !is_forum_authed($auth_cache, $forum_id) && (trim($auth_cache) != ''))
			{
				$error = true;
				if(!empty($error_msg))
				{
					$error_msg .= '<br />';
				}
				$error_msg .= sprintf($lang['Disallowed_extension_within_forum'], $this->extension);
			}

			// Upload File
			$this->thumbnail = 0;

			if (!$error)
			{
				// Prepare Values
				$this->filetime = time();

				$this->filename = $r_file;

				// physical filename
				$this->attach_filename = strtolower($this->filename);

				// To re-add cryptic filenames, change this variable to true
				$cryptic = false;

				if (!$cryptic)
				{
					$this->attach_filename = html_entity_decode(trim(stripslashes($this->attach_filename)));
					$this->attach_filename = delete_extension($this->attach_filename);
					$this->attach_filename = str_replace(array(' ','-'), array('_','_'), $this->attach_filename);
					$this->attach_filename = str_replace('__', '_', $this->attach_filename);
					$this->attach_filename = str_replace(array(',', '.', '!', '?', 'ü', 'Ü', 'ö', 'Ö', 'ä', 'Ä', ';', ':', '@', "'", '"', '&'), array('', '', '', '', 'ue', 'ue', 'oe', 'oe', 'ae', 'ae', '', '', '', '', '', 'and'), $this->attach_filename);
					$this->attach_filename = str_replace(array('$', 'ß', '>','<','§','%','=','/','(',')','#','*','+',"\\",'{','}','[',']'), array('dollar', 'ss','greater','lower','paragraph','percent','equal','','','','','','','','','','',''), $this->attach_filename);
					// Remove non-latin characters
					$this->attach_filename = preg_replace("/([\xC2\xC3])([\x80-\xBF])/e", "chr(ord('\\1')<<6&0xC0|ord('\\2')&0x3F)", $this->attach_filename);
					$this->attach_filename = rawurlencode($this->attach_filename);
					$this->attach_filename = preg_replace("/(%[0-9A-F]{1,2})/i", '', $this->attach_filename);
					$this->attach_filename = trim($this->attach_filename);

					$new_filename = $this->attach_filename;

					if (!$new_filename)
					{
						$u_id = (intval($user->data['user_id']) == ANONYMOUS) ? 0 : intval($user->data['user_id']);
						$new_filename = $u_id . '_' . $this->filetime . '.' . $this->extension;
					}

					do
					{
						$this->attach_filename = $new_filename . '_' . substr(rand(), 0, 3) . '.' . $this->extension;
					}
					while (physical_filename_already_stored($this->attach_filename));

					unset($new_filename);
				}
				else
				{
					$u_id = (intval($user->data['user_id']) == ANONYMOUS) ? 0 : intval($user->data['user_id']);
					$this->attach_filename = $u_id . '_' . $this->filetime . '.' . $this->extension;
				}

				// Do we have to create a thumbnail ?
				if (($cat_id == IMAGE_CAT) && intval($config['img_create_thumbnail']))
				{
					$this->thumbnail = 1;
				}
			}

			if ($error)
			{
				$this->post_attach = false;
				return;
			}

			// Upload Attachment
			if (!$error)
			{
				if (!(intval($config['allow_ftp_upload'])))
				{
					// Descide the Upload method
					$ini_val = ( phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

					$safe_mode = @$ini_val('safe_mode');

					if (@$ini_val('open_basedir'))
					{
						if (@phpversion() < '4.0.3')
						{
							$upload_mode = 'copy';
						}
						else
						{
							$upload_mode = 'move';
						}
					}
					elseif (@$ini_val('safe_mode'))
					{
						$upload_mode = 'move';
					}
					else
					{
						$upload_mode = 'copy';
					}
				}
				else
				{
					$upload_mode = 'ftp';
				}

				// Ok, upload the Attachment
				if (!$error)
				{
					$this->move_uploaded_attachment($upload_mode, $file);
				}
			}

			// Now, check filesize parameters
			if (!$error)
			{
				if ($upload_mode != 'ftp' && !$this->filesize)
				{
					$this->filesize = intval(@filesize($upload_dir . '/' . $this->attach_filename));
				}
			}

			// Check Image Size, if it's an image
			if (!$error && ($cat_id == IMAGE_CAT))
			{
				$pic_size = image_getdimension($upload_dir . '/' . $this->attach_filename);
				if ($pic_size == false)
				{
					$error = true;
					if(!empty($error_msg))
					{
						$error_msg .= '<br />';
					}
					$error_msg .= $lang['FileType_Mismatch'];
				}

				if (!$error && ($user->data['user_level'] != ADMIN))
				{
					list($width, $height) = $pic_size;

					if ($width != 0 && $height != 0 && intval($config['img_max_width']) != 0 && intval($config['img_max_height']) != 0)
					{
						if ($width > intval($config['img_max_width']) || $height > intval($config['img_max_height']))
						{
							$error = true;
							if(!empty($error_msg))
							{
								$error_msg .= '<br />';
							}
							$error_msg .= sprintf($lang['Error_imagesize'], intval($config['img_max_width']), intval($config['img_max_height']));
						}
					}
				}
			}

			// check Filesize
			if (!$error && ($allowed_filesize != 0) && ($this->filesize > $allowed_filesize) && ($user->data['user_level'] != ADMIN))
			{
				$size_lang = ($allowed_filesize >= 1048576) ? $lang['MB'] : ( ($allowed_filesize >= 1024) ? $lang['KB'] : $lang['Bytes'] );

				if ($allowed_filesize >= 1048576)
				{
					$allowed_filesize = round($allowed_filesize / 1048576 * 100) / 100;
				}
				elseif ($allowed_filesize >= 1024)
				{
					$allowed_filesize = round($allowed_filesize / 1024 * 100) / 100;
				}

				$error = true;
				if(!empty($error_msg))
				{
					$error_msg .= '<br />';
				}
				$error_msg .= sprintf($lang['Attachment_too_big'], $allowed_filesize, $size_lang);
			}

			// Check our complete quota
			if ($config['attachment_quota'])
			{
				$sql = 'SELECT sum(filesize) as total FROM ' . ATTACHMENTS_DESC_TABLE;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$total_filesize = $row['total'];

				if (($total_filesize + $this->filesize) > $config['attachment_quota'])
				{
					$error = true;
					if(!empty($error_msg))
					{
						$error_msg .= '<br />';
					}
					$error_msg .= $lang['Attach_quota_reached'];
				}

			}

			$this->get_quota_limits($user->data);

			// Check our user quota
			if ($this->page != PAGE_PRIVMSGS)
			{
				if ($config['upload_filesize_limit'])
				{
					$sql = 'SELECT attach_id
						FROM ' . ATTACHMENTS_TABLE . '
						WHERE user_id_1 = ' . (int) $user->data['user_id'] . '
							AND privmsgs_id = 0
						GROUP BY attach_id';
					$result = $db->sql_query($sql);
					$attach_ids = $db->sql_fetchrowset($result);
					$num_attach_ids = $db->sql_numrows($result);
					$db->sql_freeresult($result);

					$attach_id = array();

					for ($i = 0; $i < $num_attach_ids; $i++)
					{
						$attach_id[] = intval($attach_ids[$i]['attach_id']);
					}

					if ($num_attach_ids > 0)
					{
						// Now get the total filesize
						$sql = 'SELECT sum(filesize) as total
							FROM ' . ATTACHMENTS_DESC_TABLE . '
							WHERE attach_id IN (' . implode(', ', $attach_id) . ')';
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						$total_filesize = $row['total'];
					}
					else
					{
						$total_filesize = 0;
					}

					if (($total_filesize + $this->filesize) > $config['upload_filesize_limit'])
					{
						$upload_filesize_limit = $config['upload_filesize_limit'];
						$size_lang = ($upload_filesize_limit >= 1048576) ? $lang['MB'] : ( ($upload_filesize_limit >= 1024) ? $lang['KB'] : $lang['Bytes'] );

						if ($upload_filesize_limit >= 1048576)
						{
							$upload_filesize_limit = round($upload_filesize_limit / 1048576 * 100) / 100;
						}
						elseif ($upload_filesize_limit >= 1024)
						{
							$upload_filesize_limit = round($upload_filesize_limit / 1024 * 100) / 100;
						}

						$error = true;
						if(!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['User_upload_quota_reached'], $upload_filesize_limit, $size_lang);
					}
				}
			}

			// If we are at Private Messaging, check our PM Quota
			if ($this->page == PAGE_PRIVMSGS)
			{
				if ($config['pm_filesize_limit'])
				{
					$total_filesize = get_total_attach_pm_filesize('from_user', $user->data['user_id']);

					if (($total_filesize + $this->filesize) > $config['pm_filesize_limit'])
					{
						$error = true;
						if(!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= $lang['Attach_quota_sender_pm_reached'];
					}
				}

				$to_user = (isset($_POST['username']) ) ? $_POST['username'] : '';

				// Check Receivers PM Quota
				if (!empty($to_user) && $user->data['user_level'] != ADMIN)
				{
					$u_data = get_userdata($to_user, true);

					$user_id = (int) $u_data['user_id'];
					$this->get_quota_limits($u_data, $user_id);

					if ($config['pm_filesize_limit'])
					{
						$total_filesize = get_total_attach_pm_filesize('to_user', $user_id);

						if (($total_filesize + $this->filesize) > $config['pm_filesize_limit'])
						{
							$error = true;
							if(!empty($error_msg))
							{
								$error_msg .= '<br />';
							}
							$error_msg .= sprintf($lang['Attach_quota_receiver_pm_reached'], $to_user);
						}
					}
				}
			}

			if ($error)
			{
				unlink_attach($this->attach_filename);
				unlink_attach($this->attach_filename, MODE_THUMBNAIL);
				$this->post_attach = false;
			}
		}
	}

	// Copy the temporary attachment to the right location (copy, move_uploaded_file or ftp)
	function move_uploaded_attachment($upload_mode, $file)
	{
		global $error, $error_msg, $lang, $upload_dir;

		if (!is_uploaded_file($file))
		{
			message_die(GENERAL_ERROR, 'Unable to upload file. The given source has not been uploaded.', __LINE__, __FILE__);
		}

		switch ($upload_mode)
		{
			case 'copy':

				if (!@copy($file, $upload_dir . '/' . basename($this->attach_filename)))
				{
					if (!@move_uploaded_file($file, $upload_dir . '/' . basename($this->attach_filename)))
					{
						$error = true;
						if(!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['General_upload_error'], './' . $upload_dir . '/' . $this->attach_filename);
						return;
					}
				}
				@chmod($upload_dir . '/' . basename($this->attach_filename), 0666);

			break;

			case 'move':

				if (!@move_uploaded_file($file, $upload_dir . '/' . basename($this->attach_filename)))
				{
					if (!@copy($file, $upload_dir . '/' . basename($this->attach_filename)))
					{
						$error = true;
						if(!empty($error_msg))
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['General_upload_error'], './' . $upload_dir . '/' . $this->attach_filename);
						return;
					}
				}
				@chmod($upload_dir . '/' . $this->attach_filename, 0666);

			break;

			case 'ftp':
				ftp_file($file, basename($this->attach_filename), $this->type);
			break;
		}

		if (!$error && $this->thumbnail == 1)
		{
			if ($upload_mode == 'ftp')
			{
				$source = $file;
				$dest_file = THUMB_DIR . '/t_' . basename($this->attach_filename);
			}
			else
			{
				$source = $upload_dir . '/' . basename($this->attach_filename);
				$dest_file = amod_realpath($upload_dir);
				$dest_file .= '/' . THUMB_DIR . '/t_' . basename($this->attach_filename);
			}

			if (!create_thumbnail($source, $dest_file, $this->type))
			{
				if (!$file || !create_thumbnail($file, $dest_file, $this->type))
				{
					$this->thumbnail = 0;
				}
			}
		}
	}
}

/**
* @package attachment_mod
* Attachment posting
*/
class attach_posting extends attach_parent
{
	/**
	* Constructor
	*/
	function __construct()
	{
		// Mighty Gorgon: to be verified if attachments are working properly
		$this->attach_parent_init();
		$this->page = 0;
	}

	/**
	* Preview Attachments in Posts
	*/
	function preview_attachments()
	{
		global $config, $is_auth, $user;

		if (intval($config['disable_attachments_mod']) || !$is_auth['auth_attachments'])
		{
			return false;
		}

		display_attachments_preview($this->attachment_id_list, $this->attachment_list, $this->attachment_filesize_list, $this->attachment_filename_list, $this->attachment_comment_list, $this->attachment_extension_list, $this->attachment_thumbnail_list);
	}

	/**
	* Insert an Attachment into a Post (this is the second function called from posting.php)
	*/
	function insert_attachment($post_id)
	{
		global $db, $is_auth, $mode, $user, $error, $error_msg;

		// Insert Attachment ?
		if (!empty($post_id) && (($mode == 'newtopic') || ($mode == 'reply') || ($mode == 'editpost')) && $is_auth['auth_attachments'])
		{
			$this->do_insert_attachment('attach_list', 'post', $post_id);
			$this->do_insert_attachment('last_attachment', 'post', $post_id);

			if ((sizeof($this->attachment_list) > 0 || $this->post_attach) && !isset($_POST['update_attachment']))
			{
				$sql = 'UPDATE ' . POSTS_TABLE . '
					SET post_attachment = 1
					WHERE post_id = ' . (int) $post_id;
				$db->sql_query($sql);

				$sql = 'SELECT topic_id
					FROM ' . POSTS_TABLE . '
					WHERE post_id = ' . (int) $post_id;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$sql = 'UPDATE ' . TOPICS_TABLE . '
					SET topic_attachment = 1
					WHERE topic_id = ' . (int) $row['topic_id'];
				$db->sql_query($sql);
			}
		}
	}

	/**
	* Handle Attachments (Add/Delete/Edit/Show) - This is the first function called from every message handler
	*/
	function posting_attachment_mod()
	{
		global $mode, $confirm, $is_auth, $post_id, $delete, $refresh, $_POST;

		if (!$refresh)
		{
			$add_attachment_box = (!empty($_POST['add_attachment_box'])) ? true : false;
			$posted_attachments_box = (!empty($_POST['posted_attachments_box'])) ? true : false;
			$refresh = $add_attachment_box || $posted_attachments_box;
		}

		// Choose what to display
		$result = $this->handle_attachments($mode);

		if ($result === false)
		{
			return;
		}

		if ($confirm && ($delete || ($mode == 'delete') || ($mode == 'editpost')) && ($is_auth['auth_delete'] || $is_auth['auth_mod']))
		{
			if ($post_id)
			{
				delete_attachment($post_id);
			}
		}

		$this->display_attachment_bodies();
	}

}

/**
* Entry Point
*/
function execute_posting_attachment_handling()
{
	global $attachment_mod;
	$attachment_mod['posting'] = new attach_posting();
	$attachment_mod['posting']->posting_attachment_mod();
}

?>