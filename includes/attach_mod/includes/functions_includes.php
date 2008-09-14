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

/**
* These are functions called directly from Icy Phoenix Files
* Some functions have been removed by Mighty Gorgon...
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Setup Viewtopic Authentication for f_access (viewtopic.php:includes/topic_review.php)
*/

function attach_setup_viewtopic_auth(&$order_sql, &$sql)
{
	$order_sql = str_replace('f.auth_attachments', 'f.auth_attachments, f.auth_download, t.topic_attachment', $order_sql);
	$sql = str_replace('f.auth_attachments', 'f.auth_attachments, f.auth_download, t.topic_attachment', $sql);
}

/**
* Setup s_auth_can in viewforum and viewtopic (viewtopic.php/viewforum.php)
*/
function attach_build_auth_levels($is_auth, &$s_auth_can)
{
	global $lang, $attach_config, $forum_id;

	if (intval($attach_config['disable_mod']))
	{
		return;
	}

	// If you want to have the rules window link within the forum view too, comment out the two lines, and comment the third line
//	$rules_link = '(<a href="' . IP_ROOT_PATH . 'attach_rules.' . PHP_EXT . '?f=' . $forum_id . '" target="_blank">Rules</a>)';
//	$s_auth_can .= ( ( $is_auth['auth_attachments'] ) ? $rules_link . ' ' . $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';
	$s_auth_can .= (($is_auth['auth_attachments']) ? $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';
	$s_auth_can .= (($is_auth['auth_download']) ? $lang['Rules_download_can'] : $lang['Rules_download_cannot'] ) . '<br />';
}

/**
* Check if a user is within Group
*/
function user_in_group($user_id, $group_id)
{
	global $db;

	$user_id = (int) $user_id;
	$group_id = (int) $group_id;

	if (!$user_id || !$group_id)
	{
		return false;
	}

	$sql = 'SELECT u.group_id
		FROM ' . USER_GROUP_TABLE . ' u, ' . GROUPS_TABLE . " g
		WHERE g.group_single_user = 0
			AND u.group_id = g.group_id
			AND u.user_id = $user_id
			AND g.group_id = $group_id
		LIMIT 1";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not get User Group', '', __LINE__, __FILE__, $sql);
	}

	$num_rows = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($num_rows == 0)
	{
		return false;
	}

	return true;
}

/**
* Physical Filename stored already ?
*/
function physical_filename_already_stored($filename)
{
	global $db;

	if ($filename == '')
	{
		return false;
	}

	$filename = basename($filename);

	$sql = 'SELECT attach_id
		FROM ' . ATTACHMENTS_DESC_TABLE . "
		WHERE physical_filename = '" . attach_mod_sql_escape($filename) . "'
		LIMIT 1";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not get attachment information for filename: ' . htmlspecialchars($filename), '', __LINE__, __FILE__, $sql);
	}
	$num_rows = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	return ($num_rows == 0) ? false : true;
}

/**
* get all attachments from a pm
*/
function get_attachments_from_pm($privmsgs_id_array)
{
	global $db, $attach_config;

	$attachments = array();

	if (!is_array($privmsgs_id_array))
	{
		if (empty($privmsgs_id_array))
		{
			return $attachments;
		}

		$privmsgs_id = intval($privmsgs_id_array);

		$privmsgs_id_array = array();
		$privmsgs_id_array[] = $privmsgs_id;
	}

	$privmsgs_id_array = implode(', ', array_map('intval', $privmsgs_id_array));

	if ($privmsgs_id_array == '')
	{
		return $attachments;
	}

	$display_order = (intval($attach_config['display_order']) == 0) ? 'DESC' : 'ASC';

	$sql = 'SELECT a.privmsgs_id, d.*
		FROM ' . ATTACHMENTS_TABLE . ' a, ' . ATTACHMENTS_DESC_TABLE . " d
		WHERE a.privmsgs_id IN ($privmsgs_id_array)
			AND a.attach_id = d.attach_id
		ORDER BY d.filetime $display_order";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get Attachment Informations for private message number ' . $privmsgs_id_array, '', __LINE__, __FILE__, $sql);
	}

	$num_rows = $db->sql_numrows($result);
	$attachments = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	if ($num_rows == 0 )
	{
		return array();
	}

	return $attachments;
}

/**
* Count Filesize for Attachments in Users PM Boxes (Do not count the SENT Box)
*/
function get_total_attach_pm_filesize($direction, $user_id)
{
	global $db;

	if ($direction != 'from_user' && $direction != 'to_user')
	{
		return 0;
	}
	else
	{
		$user_sql = ($direction == 'from_user') ? '(a.user_id_1 = ' . intval($user_id) . ')' : '(a.user_id_2 = ' . intval($user_id) . ')';
	}

	$sql = 'SELECT a.attach_id
		FROM ' . ATTACHMENTS_TABLE . ' a, ' . PRIVMSGS_TABLE . " p
		WHERE $user_sql
			AND a.privmsgs_id <> 0 AND a.privmsgs_id = p.privmsgs_id
			AND p.privmsgs_type <> " . PRIVMSGS_SENT_MAIL;

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query Attachment Informations', '', __LINE__, __FILE__, $sql);
	}

	$pm_filesize_total = 0;
	$attach_id = array();
	$num_rows = $db->sql_numrows($result);

	if ($num_rows == 0)
	{
		$db->sql_freeresult($result);
		return $pm_filesize_total;
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$attach_id[] = $row['attach_id'];
	}
	$db->sql_freeresult($result);

	$pm_filesize_total = get_total_attach_filesize($attach_id);
	return $pm_filesize_total;
}

/**
* Get Extension
*/
function get_extension($filename)
{
	if (!stristr($filename, '.'))
	{
		return '';
	}

	$extension = strrchr(strtolower($filename), '.');
	$extension[0] = ' ';
	$extension = strtolower(trim($extension));

	if (is_array($extension))
	{
		return '';
	}
	else
	{
		return $extension;
	}
}

/**
* Delete Extension
*/
function delete_extension($filename)
{
	return substr($filename, 0, strrpos(strtolower(trim($filename)), '.'));
}

/**
* Prune Attachments (includes/prune.php)
*/
function prune_attachments($sql_post)
{
	// prune it.
	delete_attachment($sql_post);
}

// Limit Image Width MOD --- BEGIN
function liw_get_dimensions($image_source, $identifier = '')
{
	global $db, $board_config;

	$image_checksum = '';
	$result_rowcount = 0;

	if ( ( @extension_loaded('openssl') && strstr($image_source, 'https://') ) || strstr($image_source, 'http://') )
	{
		if ($handle = @fopen($image_source, 'rb'))
		{
			if ( strrchr($image_source, '.') == '.gif' )
			{
				$image_checksum .= md5(@fgets($handle, 100) . $identifier);
			}
			else
			{
				for ( $line = 0; $line != 5; $line++ )
				{
					$image_checksum .= md5(@fgets($handle, 100) . $identifier);
				}
			}

			$image_checksum = md5($image_checksum);

			@fclose($handle);
		}
	}

	if ( $image_checksum )
	{
		$sql = "SELECT image_width, image_height FROM " . LIW_CACHE_TABLE . " WHERE image_checksum = '" . $image_checksum . "'";

		if ( $result = $db->sql_query($sql) )
		{
			$result_rowcount = $db->sql_numrows();

			if ( $result_rowcount > 0 )
			{
				$image_data = $db->sql_fetchrow();
			}
		}
	}

	$return = array();

	if ( !$handle )
	{
		$return[] = 1;
		$return[] = 1;
	}
	else
	{
		if ( $result_rowcount == 0 )
		{
			if ( strstr($image_source, $board_config['server_name'] . $board_config['script_path']) )
			{
				$image_source = substr($image_source, ( strpos($image_source, $board_config['server_name'] . $board_config['script_path']) + strlen($board_config['server_name'] . $board_config['script_path']) ));
				$image_source = realpath('.') . '/' . $image_source;
			}

			list($image_width, $image_height) = @getimagesize($image_source);

			if ( !empty($image_checksum) )
			{
				$sql = "INSERT INTO " . LIW_CACHE_TABLE . " (image_checksum, image_width, image_height) VALUES ('" . $image_checksum . "', '" . $image_width . "', '" . $image_height . "')";
				$db->sql_query($sql);
			}
		}
		else
		{
			$image_width = $image_data['image_width'];
			$image_height = $image_data['image_height'];
		}

		$return[] = $image_width;
		$return[] = $image_height;
	}

	return $return;
}

function generate_liw_img_popup($image_source, $image_width = '', $image_height = '', $max_image_width)
{
	global $lang;

	$rand = rand(1, 10000);
	$return = '<a name="img_' . $rand . '"><a href="#img_' . $rand . '" onClick="img_popup(\'' . str_replace("'", "\'", $image_source) . '\', ' . ( ( !empty($image_width) ) ? $image_width : '\'\'' ) . ', ' . ( ( !empty($image_height) ) ? $image_height : '\'\'' ) . ', ' . $rand  . ');"><img src="' . $image_source . '"' . ( ( !empty($image_width) ) ? ' width="' . $max_image_width . '"' : '' ) . ' alt="' . $lang['LIW_click_image'] . '" border="0"></a></a><br /><span class="gensmall">' . $lang['LIW_click_image_explain'] . '</span>';

	return $return;
}
// Limit Image Width MOD --- END

?>