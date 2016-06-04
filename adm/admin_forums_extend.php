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
* Ptirhiik (admin@rpgnet-fr.com)
*
*/

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1200_Forums']['100_Manage'] = $file;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/def_auth.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);

if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
if (empty($class_mcp)) $class_mcp = new class_mcp();

// Constants
define('POST_FLINK_URL', 'l');

// fields in forums table, except auths ones:
//		table_field => form_field
$forums_fields_list = array(
	'forum_id'										=> 'id',
	'forum_type'									=> 'forum_type',
	'parent_id'										=> 'main_id',
	'main_type'										=> 'main_type',
	'forum_order'									=> 'order',
	'forum_name'									=> 'name',
	'forum_name_clean'						=> 'name_clean',
	'forum_desc'									=> 'desc',
	'forum_status'								=> 'status',
	'forum_likes'									=> 'forum_likes',
	'forum_limit_edit_time'				=> 'forum_limit_edit_time',
	'forum_similar_topics'				=> 'forum_similar_topics',
	'forum_topic_views'						=> 'forum_topic_views',
	'forum_tags'									=> 'forum_tags',
	'forum_sort_box'							=> 'forum_sort_box',
	'forum_kb_mode'								=> 'forum_kb_mode',
	'forum_index_icons'						=> 'forum_index_icons',
	'forum_notify'								=> 'forum_notify',
	'forum_postcount'							=> 'forum_postcount',
	'forum_rules_switch'					=> 'forum_rules_switch',
	'forum_rules'									=> 'forum_rules',
	'forum_rules_custom_title'		=> 'forum_rules_custom_title',
	'forum_rules_display_title'		=> 'forum_rules_display_title',
	'forum_rules_in_viewforum'		=> 'forum_rules_in_viewforum',
	'forum_rules_in_viewtopic'		=> 'forum_rules_in_viewtopic',
	'forum_rules_in_posting'			=> 'forum_rules_in_posting',
	'forum_recurring_first_post'	=> 'forum_recurring_first_post',
	'prune_enable'								=> 'prune_enable',
	'forum_link'									=> 'link',
	'forum_link_internal'					=> 'link_internal',
	'forum_link_hit_count'				=> 'link_hit_count',
	'forum_link_hit'							=> 'link_hit',
	'icon'												=> 'icon',
);

// fields in categories table:
//		table_field => form_field
$categories_fields_list = array(
	'forum_id'							=> 'id',
	'forum_type'						=> 'forum_type',
	'parent_id'							=> 'main_id',
	'main_type'							=> 'main_type',
	'forum_order'						=> 'order',
	'forum_name'						=> 'name',
	'forum_name_clean'			=> 'name_clean',
	'forum_desc'						=> 'desc',
	'icon'									=> 'icon',
);

// type of the form fields
$fields_type = array(
	'type'												=> 'VARCHAR',
	'id'													=> 'INTEGER',
	'main_id'											=> 'INTEGER',
	'main_type'										=> 'VARCHAR',
	'order'												=> 'INTEGER',
	'name'												=> 'HTML',
	'name_clean'									=> 'VARCHAR',
	'desc'												=> 'HTML',
	'icon'												=> 'HTML',
	'status'											=> 'INTEGER',
	'forum_likes'									=> 'INTEGER',
	'forum_limit_edit_time'				=> 'INTEGER',
	'forum_sort_box'							=> 'INTEGER',
	'forum_kb_mode'								=> 'INTEGER',
	'forum_index_icons'						=> 'INTEGER',
	'forum_notify'								=> 'INTEGER',
	'forum_rules_switch'					=> 'INTEGER',
	'forum_rules'									=> 'HTML',
	'forum_rules_custom_title'		=> 'VARCHAR',
	'forum_rules_display_title'		=> 'INTEGER_CB',
	'forum_rules_in_viewforum'		=> 'INTEGER_CB',
	'forum_rules_in_viewtopic'		=> 'INTEGER_CB',
	'forum_rules_in_posting'			=> 'INTEGER_CB',
	'forum_recurring_first_post'	=> 'INTEGER',
	'forum_postcount'							=> 'INTEGER',
	'enable'											=> 'INTEGER',
	'link'												=> 'HTML',
	'link_internal'								=> 'INTEGER',
	'link_hit_count'							=> 'INTEGER',
	'link_hit'										=> 'INTEGER',
);

$zero_array = array('forum_rules_in_viewforum', 'forum_rules_in_viewtopic', 'forum_rules_in_posting');

// list for pull down menu and check of values :
//		value => lang key entry
$forum_type_list = array(
	POST_CAT_URL => 'Category',
	POST_FORUM_URL => 'Forum',
	POST_FLINK_URL => 'Forum_link'
);

// forum status
//		value => lang key entry
$forum_status_list = array(
	FORUM_UNLOCKED => 'Status_unlocked',
	FORUM_LOCKED => 'Status_locked'
);

// prune functions
include(IP_ROOT_PATH . './includes/prune.' . PHP_EXT);

// return message after update
$return_msg .= '<br /><br />' . sprintf($lang['Click_return_forumadmin'], '<a href="' . append_sid('admin_forums_extend.' . PHP_EXT . '?selected_id=' . $selected_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

$mode = request_var('mode', '');
$mode = check_var_value($mode, array('edit', 'create', 'delete', 'moveup', 'movedw', 'resync'), '');

$cat_id = request_var(POST_CAT_URL, 0);
$cat_id = ($cat_id < 0) ? 0 : $cat_id;

$forum_id = request_var(POST_FORUM_URL, 0);
$forum_id = ($forum_id < 0) ? 0 : $forum_id;

// selected id: current displayed id
$selected_id = request_var('selected_id', '');
$type = substr($selected_id, 0, 1);
$id = intval(substr($selected_id, 1));

if (!empty($forum_id))
{
	$type = POST_FORUM_URL;
	$id = $forum_id;
}

if (!empty($cat_id))
{
	$type = POST_CAT_URL;
	$id = $cat_id;
}

if (!in_array($type, array(POST_CAT_URL, POST_FORUM_URL)) || ($id == 0))
{
	$type = POST_CAT_URL;
	$id = 0;
}
$selected_id = $type . $id;

// check if the selected id is a valid one
if (!isset($tree['keys'][$selected_id]))
{
	$selected_id = 'Root';
}

// work id
$fid = request_var('fid', '');
$type = substr($fid, 0, 1);
$id = intval(substr($fid, 1));
$fid = $type . $id;

// check buttons
$edit_forum = isset($_POST['edit']);
$create_forum = isset($_POST['create']) || ($mode == 'create');
$delete_forum = isset($_POST['delete']);
$resync_forum = isset($_POST['resync']);

$submit = isset($_POST['update']);
$cancel = isset($_POST['cancel']);

if ($edit_forum || $delete_forum || $resync_forum)
{
	$fid = $selected_id;
}

// check when the fid is required if it is a valid one
if (!isset($tree['keys'][$fid]) && ($edit_forum || $delete_forum || ($mode == 'edit') || ($mode == 'create') || ($mode == 'moveup') || ($mode == 'movedw') || ($mode == 'resync')))
{
	$fid = '';
	$edit_forum = false;
	$delete_forum = false;
	if (!in_array($mode, array('create', 'resync')) && !$create_forum && !$resync_forum)
	{
		$mode = '';
	}
}

// convert buttons to mode
if ($edit_forum)
{
	$mode = 'edit';
}
if ($delete_forum)
{
	$mode = 'delete';
}
if ($create_forum)
{
	$mode = 'create';
	$fid = '';
}
if ($resync_forum)
{
	$mode = 'resync';
}

if ($mode == 'delete')
{
	$delete_forum = true;
}

// reset the selected id
if (isset($tree['keys'][$fid]) && !empty($tree['main'][ $tree['keys'][$fid] ]) && ($mode != 'moveup') && ($mode != 'movedw'))
{
	$selected_id = $tree['main'][ $tree['keys'][$fid] ];
}

// Process
// move up/down
if (($mode == 'moveup') || ($mode == 'movedw'))
{
	$prec = '';
	$next = '';
	$main = $tree['main'][$tree['keys'][$fid]];
	for ($i = 0; $i < sizeof($tree['sub'][$main]); $i++)
	{
		$prec = ($i == 0) ? $main : $tree['sub'][$main][$i - 1];
		$found = ($tree['sub'][$main][$i] == $fid);
		if ($found)
		{
			$next = (($i + 1) < sizeof($tree['sub'][$main])) ? $tree['sub'][$main][$i+1] : $tree['sub'][$main][$i];
			break;
		}
	}
	if ($found)
	{
		// moving up/down
		$ref = ($mode == 'moveup') ? $prec : $next;
		$inc = ($mode == 'moveup') ? -5 : +5;
		if ((($mode == 'moveup') && ($ref != $main)) || (($mode == 'movedw') && ($ref != $fid)))
		{
			$idx = $tree['keys'][$ref];
			$order = $tree['data'][$idx]['forum_order'] + $inc;

			// update the current one
			$sql = "UPDATE " . FORUMS_TABLE . "
						SET forum_order = " . $order . "
						WHERE forum_id = " . intval(substr($fid, 1));
			$db->sql_query($sql);
		}
	}

	// reorder
	reorder_tree();

	// add topics count and various informations
	get_user_tree($user->data);
	$mode = '';
}

// resync
if ($mode == 'resync')
{
	$tkeys = array();
	$tkeys = get_auth_keys($fid, true);
	for ($i = 0; $i < sizeof($tkeys['id']); $i++)
	{
		$wid = $tkeys['id'][$i];
		if (substr($wid, 0, 1) == POST_FORUM_URL)
		{
			$class_mcp->sync('forum', intval(substr($wid, 1)));
		}
	}

	// reorder
	reorder_tree();

	// end message
	$message = $lang['Forums_updated'] . $return_msg;
	message_die(GENERAL_MESSAGE, $message);
	exit;
}

// handle edit
if (($mode == 'edit') || ($mode == 'create') || ($mode == 'delete'))
{
	$CH_this = isset($tree['keys'][$fid]) ? $fid : '';
	$idx = isset($tree['keys'][$fid]) ? $tree['keys'][$fid] : '';
	$item = array();

	// Get values from memory
	// Get type and id
	$old_type = empty($CH_this) ? POST_FORUM_URL : substr($fid, 0, 1);
	$old_id = empty($CH_this) ? 0 : intval(substr($fid, 1));

	// choose the appropriate list of field (forums or categories table)
	switch ($old_type)
	{
		case POST_FORUM_URL:
			$fields_list = 'forums_fields_list';
			break;
		case POST_CAT_URL:
			$fields_list = 'categories_fields_list';
			break;
		default:
			$fields_list = 'forums_fields_list';
			break;
	}

	// get value from the tree for all fields in the list
	@reset($$fields_list);
	while (list($table_field, $process_field) = @each($$fields_list))
	{
		$item[$process_field] = empty($CH_this) ? '' : trim($tree['data'][$idx][$table_field]);
		//echo($process_field . ' = ' . $item[$process_field] . '<br />');
	}

	// add fields not present in the list or having a special treatment
	$item['type'] = $old_type;

	// parent id
	$item['main'] = empty($CH_this) ? $selected_id : $item['main_type'] . $item['main_id'];
	$item['main_type'] = substr($item['main'], 0, 1);
	$item['main_id'] = intval(substr($item['main'], 1));
	if ((intval($item['main_id']) == 0) || !in_array($item['main_type'], array(POST_CAT_URL, POST_FORUM_URL)))
	{
		$item['main'] = 'Root';
		$item['main_type'] = POST_CAT_URL;
		$item['main_id'] = 0;
	}

	// position : added field
	$item['position'] = $item['main'];
	$found = false;
	if (!empty($CH_this))
	{
		for ($i = 0; $i < sizeof($tree['sub'][$item['main']]); $i++)
		{
			$item['position'] = ($i == 0) ? $item['main'] : $tree['sub'][$item['main']][$i - 1];
			$found = ($tree['sub'][$item['main']][$i] == $fid);
			if ($found)
			{
				break;
			}
		}
	}
	if (!$found && !empty($tree['sub'][$item['main']]))
	{
		$i = sizeof($tree['sub'][$item['main']]);
		$item['position'] = $tree['sub'][$item['main']][$i - 1];
	}

	// move topic : added field
	$item['move'] = '';

	// links specific
	if (!empty($item['link']) && ($item['type'] == POST_FORUM_URL))
	{
		$item['forum_type'] = FORUM_LINK;
		$item['type'] = POST_FLINK_URL;
	}

	// prune information
	$row = array();
	if (!empty($CH_this) && ($item['type'] == POST_FORUM_URL))
	{
		// read the auto-prune table
		$sql = "SELECT * FROM " . PRUNE_TABLE . " WHERE forum_id = " . $item['id'];
		$result = $db->sql_query($sql);

		if (!$row = $db->sql_fetchrow($result))
		{
			$row = array();
		}
	}
	$item['prune_days'] = empty($row) ? 7 : $row['prune_days'];
	$item['prune_freq'] = empty($row) ? 1 : $row['prune_freq'];
	if (isset($_POST['prune_days']))
	{
		$item['prune_days'] = intval($_POST['prune_days']);
	}
	if (isset($_POST['prune_freq']))
	{
		$item['prune_freq'] = intval($_POST['prune_freq']);
	}

	// auth
	$forum_auth = array();

	// initiate with the first preset (default)
	@reset($field_names);
	$i = 0;
	while(list($auth_key, $auth_name) = @each($field_names))
	{
		$auth_value = isset($simple_auth_ary[0][$i]) ? $simple_auth_ary[0][$i] : AUTH_ADMIN;
		$forum_auth[$auth_key] = $auth_value;
		$i++;
	}

	// get the value from memory
	@reset($tree['data'][$idx]);
	while (list($key, $value) = @each($tree['data'][$idx]))
	{
		if (substr($key, 0, strlen('auth_')) == 'auth_')
		{
			$forum_auth[$key] = $value;
		}
	}

	// Get values from form

	// Type
	$item['type'] = isset($_POST['type']) ? $_POST['type'] : $item['type'];
	if (!isset($forum_type_list[ $item['type'] ]))
	{
		$item['type'] = POST_FORUM_URL;
	}

	// Choose the appropriate list of field (forums or categories table)
	switch ($item['type'])
	{
		case POST_FLINK_URL:
			$item['forum_type'] = FORUM_LINK;
			$fields_list = 'forums_fields_list';
			break;
		case POST_FORUM_URL:
			$item['forum_type'] = FORUM_POST;
			$fields_list = 'forums_fields_list';
			break;
		case POST_CAT_URL:
			$item['forum_type'] = FORUM_CAT;
			$fields_list = 'categories_fields_list';
			break;
		default:
			$item['forum_type'] = FORUM_POST;
			$fields_list = 'forums_fields_list';
			break;
	}

	// Get values from form
	@reset($$fields_list);
	while (list($table_field, $process_field) = @each($$fields_list))
	{
		// Set correct value for checkboxes
		if (($submit) && ($fields_type[$process_field] == 'INTEGER_CB'))
		{
			$form_field = isset($_POST[$process_field]) ? 1 : 0;
			$item[$process_field] = $form_field;
		}
		elseif (isset($_POST[$process_field]))
		{
			// Get field from form
			switch ($fields_type[$process_field])
			{
				case 'INTEGER':
					$form_field = request_var($process_field, 0);
					break;
				case 'INTEGER_CB':
					$form_field = isset($_POST[$process_field]) ? 1 : 0;
					break;
				case 'HTML':
					$form_field = request_var($process_field, '', true);
					$form_field = htmlspecialchars_decode($form_field, ENT_COMPAT);
					break;
				default:
					$form_field = request_var($process_field, '', true);
					break;
			}
			// store
			$item[$process_field] = $form_field;
		}
	}

	// parent id
	$item['main'] = isset($_POST['main']) ? $_POST['main'] : ((isset($_GET['main']) && $create_forum) ? $_GET['main'] : $item['main']);
	$item['main_type'] = substr($item['main'], 0, 1);
	$item['main_id'] = intval(substr($item['main'], 1));
	if (($item['main_id'] == 0) || !in_array($item['main_type'], array(POST_CAT_URL, POST_FORUM_URL)))
	{
		$item['main'] = 'Root';
		$item['main_type'] = POST_CAT_URL;
		$item['main_id'] = 0;
	}
	else
	{
		$item['main'] = $item['main_type'] . $item['main_id'];
	}

	// position
	if (isset($_POST['position']))
	{
		$type = substr($_POST['position'], 0, 1);
		$id = intval(substr($_POST['position'], 1));
		if (!in_array($type, array(POST_FORUM_URL, POST_CAT_URL)) || ($id == 0))
		{
			$item['position'] = 'Root';
		}
		else
		{
			$item['position'] = $type . $id;
		}
	}

	// move topics
	if (isset($_POST['move']))
	{
		$type = substr($_POST['move'], 0, 1);
		$id = intval(substr($_POST['move'], 1));
		if (($type != POST_FORUM_URL) || ($id == 0))
		{
			$item['move'] = '';
		}
		else
		{
			$item['move'] = $type . $id;
		}
	}

	// status
	if (!isset($forum_status_list[ $item['status'] ]))
	{
		@reset($forum_status_list);
		list($status, $value) = @each($forum_status_list);
		$item['status'] = $status;
	}

	// auth
	@reset($forum_auth);
	while (list($key, $value) = @each($forum_auth))
	{
		if (isset($_POST[$key]))
		{
			$forum_auth[$key] = intval($_POST[$key]);
		}
	}

	// check a preset choose
	$forum_preset = -1;
	if (isset($_POST['preset_choice']) && (intval($_POST['preset_choice']) == 1))
	{
		if (isset($simple_auth_ary[intval($_POST['forum_preset'])]))
		{
			$forum_preset = intval($_POST['forum_preset']);
			$preset_data = $simple_auth_ary[$forum_preset];
			@reset($field_names);
			$i = 0;
			while (list($field_key, $field_lang) = @each($field_names))
			{
				$forum_auth[$field_key] = $preset_data[$i];
				$i++;
			}
		}
	}
	else
	{
		// try to identify a preset
		@reset($simple_auth_ary);
		while(list($preset_key, $preset_data) = @each($simple_auth_ary))
		{
			$matched = true;
			@reset($field_names);
			$i = 0;
			while (list($field_key, $field_lang) = @each($field_names))
			{
				$matched = ($forum_auth[$field_key] == $preset_data[$i]);
				if (!$matched)
				{
					break;
				}
				$i++;
			}
			if ($matched)
			{
				$forum_preset = $preset_key;
				break;
			}
		}
	}

	// Process
	if ($cancel)
	{
		$mode = '';
	}
	elseif ($submit)
	{
		// do some check
		$error = false;
		$error_msg = '';

		// forum name
		if (empty($item['name']))
		{
			admin_add_error('Forum_name_missing');
		}
		$item['name_clean'] = (empty($item['name_clean']) ? $item['name'] : $item['name_clean']);
		$item['name_clean'] = substr(ip_clean_string($item['name_clean'], $lang['ENCODING']), 0, 254);

		// check move dest
		if (!empty($item['move']))
		{
			$type = substr($item['move'], 0, 1);
			$id = intval(substr($item['move'], 1));
			$werror = false;
			if (($type != POST_FORUM_URL) || ($id == 0))
			{
				$werror = true;
			}
			elseif (!isset($tree['keys'][$type . $id]))
			{
				$werror = true;
			}
			elseif (!empty($tree['data'][$tree['keys'][$type . $id]]['forum_link']))
			{
				$werror = true;
			}
			if ($werror)
			{
				admin_add_error('Nowhere_to_move');
			}
		}

		// force to choose a dest for attached items if delete
		if ($delete_forum)
		{
			if (empty($item['move']) && !empty($tree['sub'][$fid]))
			{
				admin_add_error('Nowhere_to_move');
			}
			else
			{
				$item['type'] = substr($item['move'], 0, 1);
				$item['id'] = intval(substr($item['move'], 1));
			}
		}

		// recursive attachment
		if (!empty($fid))
		{
			$main = $item['main'];
			while ($main != 'Root')
			{
				if ($main == $fid)
				{
					admin_add_error('Recursive_attachment');
					break;
				}
				$main = $tree['main'][$tree['keys'][$main]];
			}
		}

		// recursive dest
		if (!empty($item['move']) && $delete_forum)
		{
			$main = $item['move'];
			while ($main != 'Root')
			{
				if ($main == $fid)
				{
					admin_add_error('Recursive_attachment');
					break;
				}
				$main = $tree['main'][$tree['keys'][$main]];
			}
		}

		// category check
		if ($item['type'] == POST_CAT_URL)
		{
		}

		// forum link type check
		if ($item['type'] == POST_FLINK_URL)
		{
			// is the link ok ?
			if (empty($item['link']))
			{
				admin_add_error('Link_missing');
			}

			// is there something already attached to the forum
			if (!empty($fid))
			{
				// forums and cats
				if (!empty($tree['sub'][$fid]))
				{
					admin_add_error('Forum_link_with_attachment_deny');
				}
			}
		}

		// forums
		if ($item['type'] == POST_FORUM_URL)
		{
			// Reset the link url...
			$item['link'] = '';
			// prune
			if ($item['prune_enable'])
			{
				if (empty($item['prune_days']) || empty($item['prune_freq']))
				{
					admin_add_error('Set_prune_data');
				}
			}
		}

		// check content
		if (($old_type == POST_FORUM_URL) && ($item['type'] != POST_FORUM_URL))
		{
			// check if topics are present
			$sql = "SELECT * FROM " . TOPICS_TABLE . " WHERE forum_id = $old_id LIMIT 0, 1";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$move_found = empty($item['move']); // empty = delete
				if (!empty($item['move']))
				{
					$type = substr($item['move'], 0, 1);
					$id = intval(substr($item['move'], 1));
					if ($type == POST_FORUM_URL)
					{
						if (isset($tree['keys'][$item['move']]) && ($item['move'] != $fid))
						{
							$move_found = true;
						}
					}
				}
				if (!$move_found)
				{
					if ($new_type == POST_CAT_URL)
					{
						admin_add_error('Category_with_topics_deny');
					}
					elseif ($new_type == POST_FLINK_URL)
					{
						admin_add_error('Forum_link_with_topics_deny');
					}
					else
					{
						admin_add_error('Nowhere_to_move');
					}
				}
			}
		}

		// send errors
		if ($error)
		{
			$selected_id = $item['main'];
			$error_msg .= $return_msg;
			empty_cache_folders(FORUMS_CACHE_FOLDER);
			empty_cache_folders(TOPICS_CACHE_FOLDER);
			message_die(GENERAL_MESSAGE, $error_msg);
		}

		// get an order
		$item['order'] = 0;
		if (!empty($item['position']) && ($item['position'] != 'Root'))
		{
			$order_idx = $tree['keys'][$item['position']];
			$item['order'] = $tree['data'][$order_idx]['forum_order'];
		}
		$item['order'] += 5;

		// get an id
		$item['type'] = ($item['type'] == POST_FLINK_URL) ? POST_FORUM_URL : $item['type'];
		$new_item = false;
		if ((empty($fid) || ($old_type != $item['type'])) && !$delete_forum)
		{
			$new_item = true;
			$item['id'] = 0;
			$sql = "SELECT MAX(forum_id) AS max_id FROM " . FORUMS_TABLE;
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$item['id'] = $row['max_id'];
			}
			$item['id']++;
		}

		if (!$delete_forum)
		{
			// update
			$fields_list = ($item['type'] == POST_FORUM_URL) ? 'forums_fields_list' : 'categories_fields_list';
			$sql_fields = '';
			$sql_values = '';
			$sql_update = '';

			$item['forum_rules_switch'] = 0;
			for ($i = 0; $i < sizeof($zero_array); $i++)
			{
				$item['forum_rules_switch'] = (!empty($item[$zero_array[$i]]) ? 1 : $item['forum_rules_switch']);
			}
			//echo($item['forum_rules_switch'] . '<br />');

			// if we are editing a forum / cat it is better to unset the id
			if (!$new_item)
			{
				unset($forums_fields_list['forum_id']);
				unset($categories_fields_list['forum_id']);
			}

			// regular fields
			@reset($$fields_list);
			while (list($table_field, $process_field) = @each($$fields_list))
			{
				$table_value = (($fields_type[$process_field] == 'INTEGER') || ($fields_type[$process_field] == 'INTEGER_CB')) ? intval($item[$process_field]) : sprintf("'%s'", $db->sql_escape(str_replace('\"', '"', $item[$process_field])));
				$sql_fields .= (empty($sql_fields) ? '' : ', ') . $table_field;
				$sql_values .= (empty($sql_values) ? '' : ', ') . $table_value;
				$sql_update .= (empty($sql_update) ? '' : ', ') . $table_field . '=' . $table_value;
			}

			// auth fields
			if ($item['type'] == POST_FORUM_URL)
			{
				@reset($forum_auth);
				while (list($table_field, $auth_value) = @each($forum_auth))
				{
					$table_value = intval($auth_value);
					$sql_fields .= (empty($sql_fields) ? '' : ', ') . $table_field;
					$sql_values .= (empty($sql_values) ? '' : ', ') . $table_value;
					$sql_update .= (empty($sql_update) ? '' : ', ') . $table_field . '=' . $table_value;
				}
			}

			// build the final sql request
			$table = FORUMS_TABLE;
			$index_field = 'forum_id';
			$index_value = intval($item['id']);
			if ($new_item)
			{
				$sql = "INSERT INTO " . $table . " (" . $sql_fields . ") VALUES(" . $sql_values . ")";
			}
			else
			{
				$sql = "UPDATE " . $table . " SET " . $sql_update . " WHERE " . $index_field . " = " . $index_value;
			}
			//echo($sql . '<br />');
			$db->sql_query($sql);

			if ($item['type'] == POST_FORUM_URL)
			{
				if ($_POST['dup_auth'] != -1)
				{
					duplicate_auth(str_replace(POST_FORUM_URL, '', $_POST['dup_auth']), $index_value);
				}
			}
		}

		// prune table
		if ($item['type'] == POST_FORUM_URL)
		{
			if (!$item['prune_enable'] || $delete_forum)
			{
				$sql = "DELETE FROM " . PRUNE_TABLE . " WHERE forum_id = " . intval($item['id']);
				$db->sql_query($sql);
			}
			else
			{
				$sql = "SELECT * FROM " . PRUNE_TABLE . " WHERE forum_id = " . intval($item['id']);
				$result = $db->sql_query($sql);

				if($db->sql_numrows($result) > 0)
				{
					$sql = "UPDATE " . PRUNE_TABLE . "
								SET prune_days = " . intval($item['prune_days']) . ",
									prune_freq = " . intval($item['prune_freq']) . "
								WHERE forum_id = " . intval($item['id']);
				}
				else
				{
					$sql = "INSERT INTO " . PRUNE_TABLE . "
								(
									forum_id,
									prune_days,
									prune_freq
								)
								VALUES(
									" . intval($item['id']) . ",
									" . intval($item['prune_days']) . ",
									" . intval($item['prune_freq']) . "
								)";
				}
			$db->sql_query($sql);
			}
		}

		// clean previous if new created
		if ($new_item || $delete_forum)
		{
			delete_item($fid, $item['type'] . $item['id'], $item['move']);
		}

		// reorder
		reorder_tree();

		// end message
		$selected_id = $item['main'];
		$message = $lang['Forums_updated'] . $return_msg;
		message_die(GENERAL_MESSAGE, $message);
		exit;
	}
	else
	{
		// template
		$template->set_filenames(array('body' => ADM_TPL . 'forum_extend_edit_body.tpl'));

		// header
		$template->assign_vars(array(
			'L_TITLE' => $lang['Edit_forum'],
			'L_TITLE_EXPLAIN' => $lang['Forum_edit_delete_explain'],

			'L_TYPE' => $lang['Forum_type'],
			'L_NAME' => $lang['Forum_name'],
			'L_NAME_CLEAN' => $lang['CLEAN_NAME'],
			'L_DESC' => $lang['Forum_desc'],
			'L_MAIN' => $lang['Category_attachment'],
			'L_POSITION' => $lang['Position_after'],
			'L_STATUS' => $lang['Forum_status'],
			'L_MOVE' => $lang['Move_contents'],
			'L_ICON' => $lang['icon'],
			'L_ICON_EXPLAIN' => $lang['icon_explain'],

			'L_PRUNE_ENABLE' => $lang['Forum_pruning'],
			'L_ENABLED' => $lang['Enabled'],
			'L_PRUNE_DAYS' => $lang['prune_days'],
			'L_PRUNE_FREQ' => $lang['prune_freq'],
			'L_FORUM_NOTIFY' => $lang['Forum_notify'],
			'L_POSTCOUNT' => $lang['Forum_postcount'],
			'L_MOD_OS_FORUMRULES' => $lang['MOD_OS_ForumRules'],
			'L_FORUM_RULES' => $lang['Forum_rules'],
			'L_RULES_DISPLAY_TITLE' => $lang['Rules_display_title'],
			'L_RULES_CUSTOM_TITLE' => $lang['Rules_custom_title'],
			'L_RULES_APPEAR_IN' => $lang['Rules_appear_in'],
			'L_RULES_IN_VIEWFORUM' => $lang['Rules_in_viewforum'],
			'L_RULES_IN_VIEWTOPIC' => $lang['Rules_in_viewtopic'],
			'L_RULES_IN_POSTING' => $lang['Rules_in_posting'],
			'L_LINK' => $lang['Forum_link'],
			'L_FORUM_LINK' => $lang['Forum_link_url'],
			'L_FORUM_LINK_EXPLAIN' => $lang['Forum_link_url_explain'],
			'L_FORUM_LINK_INTERNAL' => $lang['Forum_link_internal'],
			'L_FORUM_LINK_INTERNAL_EXPLAIN' => $lang['Forum_link_internal_explain'],
			'L_FORUM_LINK_HIT_COUNT' => $lang['Forum_link_hit_count'],
			'L_FORUM_LINK_HIT_COUNT_EXPLAIN' => $lang['Forum_link_hit_count_explain'],

			'L_AUTH' => $lang['Auth_Control_Forum'],
			'L_PRESET' => $lang['Presets'],

			'L_SUBMIT' => $delete_forum ? $lang['Delete'] : $lang['Submit'],
			'L_CANCEL' => $lang['Cancel'],
			'L_REFRESH' => $lang['Refresh'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_DAYS' => $lang['Days'],
			)
		);

		// type select list
		$s_type_opt = '';
		@reset($forum_type_list);
		while (list($key, $value) = @each($forum_type_list))
		{
			$selected = ($item['type'] == $key) ? ' selected="selected"' : '';
			$s_type_opt .= '<option value="' . $key . '"' . $selected . '>' . $lang[$value] . '</option>';
		}

		// status select list
		$s_status_opt = '';
		@reset($forum_status_list);
		while (list($key, $value) = @each($forum_status_list))
		{
			$selected = ($item['status'] == $key) ? ' selected="selected"' : '';
			$s_status_opt .= '<option value="' . $key . '"' . $selected . '>' . $lang[$value] . '</option>';
		}

		// presets list
		$s_presets = '';
		$selected = ($forum_preset < 0) ? ' selected="selected"' : '';
		$s_presets .= '<option value="-1"' . $selected . '>' . $lang['None'] . '</option>';
		@reset($simple_auth_ary);
		$i = 0;
		while (list($preset_key, $preset_data) = @each($simple_auth_ary))
		{
			$selected = ($preset_key == $forum_preset) ? ' selected="selected"' : '';
			$s_presets .= '<option value="' . $preset_key . '"' . $selected . '>' . $simple_auth_types[$i] . '</option>';
			$i++;
		}

		// position list
		$s_post_opt = '';
		$selected = ($item['position'] == $item['main']) ? ' selected="selected"' : '';
		$s_pos_opt .= '<option value="' . $item['main'] . '"' . $selected . '>' . get_object_lang($item['main'], 'name', true) . '</option>';
		for ($i = 0; $i < sizeof($tree['sub'][ $item['main'] ]); $i++)
		{
			if ($tree['sub'][ $item['main'] ][$i] != $fid)
			{
				$selected = ($tree['sub'][ $item['main'] ][$i] == $item['position']) ? ' selected="selected"' : '';
				$s_pos_opt .= '<option value="' . $tree['sub'][ $item['main'] ][$i] . '"' . $selected . '>|--&nbsp;' . get_object_lang($tree['sub'][ $item['main'] ][$i], 'name', true) . '</option>';
			}
		}

		// Auth duplication
		$forumlist = '<option value="-1">' . $lang['None'] . '</option>' . get_tree_option_optg('', true, true);

		// place to move topics and attachements
		$s_move_opt = get_tree_option('--', true);
		$s_move_opt = '<option value="" selected="selected">' . $lang['Delete_all_posts'] . '</option>' . $s_move_opt;

		// icon
		$icon_img = empty($item['icon']) ? '' : '<br /><img src="' . (isset($images[$item['icon']]) ? $images[$item['icon']] : $item['icon']) . '" alt="' . $item['icon'] . '" title="' . $item['icon'] . '" />';

		$icon = $item['icon'];
		// Mighty Gorgon - Forum Icons Select - BEGIN
		$icons_list = build_icons_select_box('../', 'images/forums/', 'icon', 'icon_image_sel', $icon, false, false, ' onchange="update_icon(this.options[selectedIndex].value);"');
		// Mighty Gorgon - Forum Icons Select - END

		// vars
		$template->assign_vars(array(
			'L_COPY_AUTH' => $lang['Copy_Auth'],
			'L_COPY_AUTH_EXPLAIN' => $lang['Copy_Auth_Explain'],
			'S_FORUM_LIST' => $forumlist,

			'S_TYPE_OPT' => $s_type_opt,
			'NAME' => htmlspecialchars(stripslashes($item['name'])),
			'NAME_CLEAN' => $item['name_clean'],
			'DESC' => htmlspecialchars(stripslashes($item['desc'])),
			'S_FORUMS_OPT' => get_tree_option($item['main'], true),
			'S_POS_OPT' => $s_pos_opt,
			'S_STATUS_OPT' => $s_status_opt,
			'S_MOVE_OPT' => $s_move_opt,
			'ICON' => $item['icon'],
			//'ICON_IMG' => $icon_img,
			'ICON_LIST' => $icons_list,
			'ICON_IMG' => IP_ROOT_PATH . (($icon != '') ? $icon : 'images/spacer.gif'),
			'MODE' => $mode,

			'PRUNE_DISPLAY' => $item['prune_enable'] ? '' : 'none',
			'PRUNE_ENABLE_YES' => $item['prune_enable'] ? 'checked="checked"' : '',
			'PRUNE_ENABLE_NO' => !$item['prune_enable'] ? 'checked="checked"' : '',
			'PRUNE_DAYS' => $item['prune_days'],
			'PRUNE_FREQ' => $item['prune_freq'],
			'FORUM_LINK' => $item['link'],
			'FORUM_LIKE_YES' => $item['forum_likes'] ? ' checked="checked"' : '',
			'FORUM_LIKE_NO' => !$item['forum_likes'] ? ' checked="checked"' : '',
			'FORUM_LIMIT_EDIT_TIME_YES' => $item['forum_limit_edit_time'] ? ' checked="checked"' : '',
			'FORUM_LIMIT_EDIT_TIME_NO' => !$item['forum_limit_edit_time'] ? ' checked="checked"' : '',
			'FORUM_SIMILAR_TOPICS_YES' => ($item['forum_similar_topics']) ? ' checked="checked"' : '',
			'FORUM_SIMILAR_TOPICS_NO' => (!$item['forum_similar_topics']) ? ' checked="checked"' : '',
			'FORUM_TOPIC_VIEWS_YES' => ($item['forum_topic_views']) ? ' checked="checked"' : '',
			'FORUM_TOPIC_VIEWS_NO' => (!$item['forum_topic_views']) ? ' checked="checked"' : '',
			'FORUM_TAGS_YES' => ($item['forum_tags']) ? ' checked="checked"' : '',
			'FORUM_TAGS_NO' => (!$item['forum_tags']) ? ' checked="checked"' : '',
			'FORUM_SORT_BOX_YES' => ($item['forum_sort_box']) ? ' checked="checked"' : '',
			'FORUM_SORT_BOX_NO' => (!$item['forum_sort_box']) ? ' checked="checked"' : '',
			'FORUM_KB_MODE_YES' => ($item['forum_kb_mode']) ? ' checked="checked"' : '',
			'FORUM_KB_MODE_NO' => (!$item['forum_kb_mode']) ? ' checked="checked"' : '',
			'FORUM_RECURRING_FIRST_POST_YES' => ($item['forum_recurring_first_post']) ? ' checked="checked"' : '',
			'FORUM_RECURRING_FIRST_POST_NO' => (!$item['forum_recurring_first_post']) ? ' checked="checked"' : '',
			'FORUM_INDEX_ICONS_YES' => ($item['forum_index_icons']) ? ' checked="checked"' : '',
			'FORUM_INDEX_ICONS_NO' => (!$item['forum_index_icons']) ? ' checked="checked"' : '',
			'FORUM_NOTIFY_YES' => $item['forum_notify'] ? ' checked="checked"' : '',
			'FORUM_NOTIFY_NO' => !$item['forum_notify'] ? ' checked="checked"' : '',
			'FORUM_POST_COUNT_YES' => ($item['forum_postcount']) ? ' checked="checked"' : '',
			'FORUM_POST_COUNT_NO' => (!$item['forum_postcount']) ? ' checked="checked"' : '',
			'FORUM_RULES' => $item['forum_rules_switch'] ? ' checked="checked"' : '',
			'RULES' => $item['forum_rules'],
			'RULES_CUSTOM_TITLE' => $item['forum_rules_custom_title'],
			'S_RULES_DISPLAY_TITLE_ENABLED' => !empty($item['forum_rules_display_title']) ? 'checked="checked"' : '',
			'S_RULES_VIEWFORUM_ENABLED' => !empty($item['forum_rules_in_viewforum']) ? 'checked="checked"' : '',
			'S_RULES_VIEWTOPIC_ENABLED' => !empty($item['forum_rules_in_viewtopic']) ? 'checked="checked"' : '',
			'S_RULES_POSTING_ENABLED' => !empty($item['forum_rules_in_posting']) ? 'checked="checked"' : '',
			'LINK_INTERNAL_YES' => $item['link_internal'] ? 'checked="checked"' : '',
			'LINK_INTERNAL_NO' => !$item['link_internal'] ? 'checked="checked"' : '',
			'LINK_COUNT_YES' => $item['link_hit_count'] ? 'checked="checked"' : '',
			'LINK_COUNT_NO' => !$item['link_hit_count'] ? 'checked="checked"' : '',

			'S_PRESET_OPT' => $s_presets,
			'AUTH_SPAN' => ($item['type'] == POST_FORUM_URL) ? 4 : 1,
			)
		);

		// some switches
		if ($item['type'] == POST_CAT_URL)
		{
			$template->assign_block_vars('category', array());
		}
		else
		{
			$template->assign_block_vars('no_category', array());
		}
		if ($item['type'] == POST_FORUM_URL)
		{
			$template->assign_block_vars('forum', array());
		}
		else
		{
			$template->assign_block_vars('no_forum', array());
		}
		if ($item['type'] == POST_FLINK_URL)
		{
			$template->assign_block_vars('link', array());
		}
		else
		{
			$template->assign_block_vars('no_link', array());
		}
		if (in_array($item['type'], array(POST_FORUM_URL, POST_FLINK_URL)))
		{
			$template->assign_block_vars('forum_link', array());
			if ($item['type'] == POST_FLINK_URL)
			{
				$template->assign_block_vars('forum_link.link', array());
			}
			else
			{
				$template->assign_block_vars('forum_link.no_link', array());
			}
		}

		// place to move topics
		if ($delete_forum || (($old_type == POST_FORUM_URL) && ($item['type'] != POST_FORUM_URL)))
		{
			// check if any topics in this forum
			$topics = false;
			$sql = "SELECT * FROM " . TOPICS_TABLE . " WHERE forum_id = $old_id LIMIT 0, 1";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$topics = true;
			}
			if ($topics || !empty($tree['sub'][$fid]))
			{
				$template->assign_block_vars('move', array());
				$template->assign_var('S_FORUM_DELETE', true);
			}
		}

		// auth
		if ($item['type'] != POST_CAT_URL)
		{
			// list of auth
			$offset = 3;
			$color_line = false;
			@reset($forum_auth);
			while (list($key, $value) = @each($forum_auth))
			{
				// forum link only use the auth view
				if (($item['type'] == POST_FORUM_URL) || ($key == 'auth_view'))
				{
					$s_auth_opt = '';
					for ($i = 0; $i < sizeof($forum_auth_const); $i++)
					{
						$auth_key = $forum_auth_const[$i];
						$auth_value = $forum_auth_levels[$i];
						$selected = ($auth_key == $value) ? ' selected="selected"' : '';
						$s_auth_opt .= '<option value="' . $auth_key . '"' . $selected . '>' . (isset($lang['Forum_' . $auth_value]) ? $lang['Forum_' . $auth_value] : $auth_value) . '</option>';
					}

					// try to find a legend
					$l_key = $key;
					if (isset($field_names[$key]))
					{
						$l_key = $field_names[$key];
					}
					else
					{
						$l_key = ucfirst(str_replace('_', ' ', substr($key, strlen('auth_'))));
					}

					// new line
					$offset++;
					if ($offset > 3)
					{
						$color_line = !$color_line;
						$template->assign_block_vars('forum_link.auth', array());
						$offset = 0;
						$color = !$color_line;
					}
					$color = !$color;
					$template->assign_block_vars('forum_link.auth.cell', array(
						'COLOR' => $color ? 'row1' : 'row2',
						'L_AUTH' => isset($lang[$l_key]) ? $lang[$l_key] : $l_key,
						'AUTH' => $key,
						'S_AUTH_OPT' => $s_auth_opt,
						)
					);
				}
			}

			// finish the line
			if (($item['type'] == POST_FORUM_URL) && ($offset < 3))
			{
				$template->assign_block_vars('forum_link.auth.empty', array(
					'SPAN' => 3 - $offset,
					)
				);
			}
		}

		// footer
		$s_hidden_fields = '';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="selected_id" value="' . $selected_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="fid" value="' . $fid . '" />';
		$template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
			'NAV_CAT_DESC' => admin_get_nav_cat_desc($selected_id),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'U_INDEX' => append_sid('admin_forums_extend.' . PHP_EXT),
			'S_ACTION' => append_sid('admin_forums_extend.' . PHP_EXT),
			)
		);
	}
}

// display the main list
if ($mode == '')
{
	// template
	$template->set_filenames(array('body' => ADM_TPL . 'forum_extend_body.tpl'));

	// header
	$template->assign_vars(array(
		'L_TITLE' => $lang['Forum_admin'],
		'L_TITLE_EXPLAIN' => $lang['Forum_admin_explain'],

		'L_ICON' => $lang['icon'],
		'L_ICON_EXPLAIN' => $lang['icon_explain'],
		'L_FORUM' => get_object_lang($selected_id, 'name', true),
		'L_TOPICS' => $lang['Topics'],
		'L_POSTS' => $lang['Posts'],
		'L_ACTION' => $lang['Action'],

		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVEUP' => $lang['MOVE_UP'],
		'L_MOVEDW' => $lang['MOVE_DOWN'],
		'IMG_MOVEUP' => $images['acp_up_arrow2'],
		'IMG_MOVEDW' => $images['acp_down_arrow2'],
		'L_EXPAND' => $lang['Forum_Expand'],
		'L_COLLAPSE' => $lang['Forum_Collapse'],
		'L_EXPAND_ALL' => $lang['Forum_Expand_all'],
		'L_COLLAPSE_ALL' => $lang['Forum_Collapse_all'],
		'L_RESYNC' => $lang['RESYNC'],

		'L_CREATE_FORUM' => $lang['Create_forum'],
		'L_EDIT_FORUM' => $lang['Edit_forum'],
		'L_DELETE_FORUM' => $lang['Forum_delete'],
		'L_RESYNC_FORUM' => $lang['RESYNC'],

		'NO_SUBFORUMS' => $lang['No_subforums'],
		)
	);
	if ($selected_id != 'Root')
	{
		$template->assign_block_vars('no_root', array());
	}
	else
	{
		$template->assign_block_vars('root', array());
	}

	// Build forums list
	$color = false;
	for($i = 0; $i < sizeof($tree['sub'][$selected_id]); $i++)
	{
		$CH_this = $tree['sub'][$selected_id][$i];
		$idx = $tree['keys'][$CH_this];
		add_row($idx, $CH_this, 0, $i);
	}

	// no subforums
	if (empty($tree['sub'][$selected_id]))
	{
		$template->assign_block_vars('empty', array());
	}

	// footer
	$s_hidden_fields = '';
	$s_hidden_fields .= '<input type="hidden" id="selected_id" name="selected_id" value="' . $selected_id . '" />';
	// fields for javascript collapsing (all)
	$s_hidden_fields .= '<input type="hidden" id="nr-sub-' . $selected_id . '" value="' . sizeof($tree['sub'][$selected_id]) . '" />';
	$s_hidden_fields .= '<input type="hidden" id="collapsed-' . $selected_id . '" value="0" />';
	$template->assign_vars(array(
		'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
		'NAV_CAT_DESC' => admin_get_nav_cat_desc($selected_id),
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'U_INDEX' => append_sid('admin_forums_extend.' . PHP_EXT),
		'S_ACTION' => append_sid('admin_forums_extend.' . PHP_EXT),
		)
	);
}

// dump
$template->pparse('body');
include('page_footer_admin.' . PHP_EXT);

function add_row($idx, $CH_this, $level, $id)
{
	global $template, $images, $lang;
	global $tree, $selected_id;

	// get data for this level
	$folder = $images['acp_forum'];
	$l_folder = $lang['Forum'];
	if ($tree['data'][$idx]['forum_status'] == FORUM_LOCKED)
	{
		$folder = $images['acp_forum_locked'];
		$l_folder = $lang['Forum_locked'];
	}
	if (($tree['type'][$idx] == POST_CAT_URL) || !empty($tree['sub'][$CH_this]))
	{
		$folder = $images['acp_category'];
		$l_folder = $lang['Category'];
		if ($tree['data'][$idx]['forum_status'] == FORUM_LOCKED)
		{
			$folder = $images['acp_category_locked'];
			$l_folder = $lang['Forum_locked'];
		}
	}
	if (!empty($tree['data'][$idx]['forum_link']))
	{
		$folder = $images['acp_link'];
		$l_folder = $lang['Forum_link'];
	}

	$icon = '';
	if (!empty($tree['data'][$idx]['icon']))
	{
		$icon = $tree['data'][$idx]['icon'];
		$icon_img = $icon;
		if (isset($images[$icon_img]))
		{
			$icon_img = $images[$icon_img];
		}
	}

	$color = !$color;
	if ($selected_id == '') $selected_id = 'Root';

	// Fields for javascript collapsing
	$s_hidden_fields = '<input type="hidden" id="sub-id-' . $tree['main'][$idx] . '-' . $id . '" value="' . $CH_this . '" />';
	$s_hidden_fields .= '<input type="hidden" id="nr-sub-' . $CH_this . '" value="' . sizeof($tree['sub'][$CH_this]) . '" />';
	$s_hidden_fields .= '<input type="hidden" id="collapsed-' . $CH_this . '" value="0" />';

	$template->assign_block_vars('row', array(
		'ID' => $CH_this,
		'PARENT_ID' => $tree['main'][$idx],
		'COLLAPSE_ID' => $id,
		'LEVEL_WIDTH' => $level * 20,
		'COLOR' => $color ? 'row1' : 'row2',
		'FOLDER' => $folder,
		'L_FOLDER' => $l_folder,
		//'ICON_IMG' => $icon_img,
		'ICON_IMG' => ($icon_img != '') ? IP_ROOT_PATH . $icon_img : IP_ROOT_PATH . 'images/spacer.gif',
		'ICON' => $icon,
		'FORUM_NAME' => get_object_lang($CH_this, 'name', true),
		'FORUM_DESC' => get_object_lang($CH_this, 'desc', true),
		'TOPICS' => $tree['data'][$idx]['tree.forum_topics'],
		'POSTS' => $tree['data'][$idx]['tree.forum_posts'],
		'IS_CAT' => $tree['type'][$idx] == POST_CAT_URL,

		'U_FORUM' => append_sid('admin_forums_extend.' . PHP_EXT . '?selected_id=' . $CH_this),
		'U_ADD' => append_sid('admin_forums_extend.' . PHP_EXT . '?mode=create&amp;main=' . $CH_this),
		'U_EDIT' => append_sid('admin_forums_extend.' . PHP_EXT . '?mode=edit&amp;fid=' . $CH_this),
		'U_DELETE' => append_sid('admin_forums_extend.' . PHP_EXT . '?mode=delete&amp;fid=' . $CH_this),
		'U_RESYNC' => append_sid('admin_forums_extend.' . PHP_EXT . '?mode=resync&amp;fid=' . $CH_this),
		'U_MOVEUP' => append_sid('admin_forums_extend.' . PHP_EXT . '?mode=moveup&amp;fid=' . $CH_this . '&amp;selected_id=' . $selected_id),
		'U_MOVEDW' => append_sid('admin_forums_extend.' . PHP_EXT . '?mode=movedw&amp;fid=' . $CH_this . '&amp;selected_id=' . $selected_id),
		'U_PERMS' => append_sid('admin_forumauth.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $CH_this),

		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	if (!empty($icon))
	{
		$template->assign_block_vars('row.forum_icon', array());
	}

	// is there some sub-levels for this level ?
	if (isset($tree['sub'][$CH_this]))
	{
		// build sub-levels links (collapsed)
		$links = '';
		for ($i = 0; $i < sizeof($tree['sub'][$CH_this]); $i++)
		{
			$sub_this = $tree['sub'][$CH_this][$i];
			$sub_idx = $tree['keys'][$sub_this];

			$sub_folder = $images['acp_icon_minipost'];
			$sub_l_folder = $lang['Forum'];
			if ($tree['data'][$sub_idx]['forum_status'] == FORUM_LOCKED)
			{
				$sub_folder = $images['acp_icon_minipost_lock'];
				$sub_l_folder = $lang['Forum_locked'];
			}
			if (($tree['type'][$sub_idx] == POST_CAT_URL) || !empty($tree['sub'][$sub_this]))
			{
				$sub_folder = $images['acp_icon_minicat'];
				$sub_l_folder = $lang['Category'];
				if ($tree['data'][$sub_idx]['forum_status'] == FORUM_LOCKED)
				{
					$sub_folder = $images['acp_icon_minicat_locked'];
					$sub_l_folder = $lang['Category_locked'];
				}
			}
			if (!empty($tree['data'][$sub_idx]['forum_link']))
			{
				$sub_folder = $images['acp_icon_minilink'];
				$sub_l_folder = $lang['Forum_link'];
			}

			$link = '<a href="' . append_sid('admin_forums_extend.' . PHP_EXT . '?selected_id=' . $sub_this) . '" class="gensmall" style="text-decoration: none;">';
			$link .= '<img src="' . $sub_folder . '" alt="' . $sub_l_folder . '" style="vertical-align: middle;" />';
			$link .= '&nbsp;' . get_object_lang($sub_this, 'name', true) . '</a>';
			$links .= (empty($links) ? '' : ', ') . $link;
		}

		$template->assign_block_vars('row.has_sublevels', array(
			'LINKS' => $links
			)
		);

		// build sub-levels (expanded)
		for ($i = 0; $i < sizeof($tree['sub'][$CH_this]); $i++)
		{
			$sub_this = $tree['sub'][$CH_this][$i];
			$sub_idx = $tree['keys'][$sub_this];
			add_row($sub_idx, $sub_this, $level+1, $i);
		}
	}
	else
	{
		$template->assign_block_vars('row.has_no_sublevels', array());
	}
}

?>