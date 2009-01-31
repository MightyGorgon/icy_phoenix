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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// admin_forums.php - BEGIN

function display_admin_index($cur = 'Root', $level = 0, $max_level = -1)
{
	global $template, $lang, $images;
	global $tree;

	// display the level
	$CH_this = isset($tree['keys'][$cur]) ? $tree['keys'][$cur] : -1;

	// root level
	if ($max_level == -1)
	{
		// get max inc level
		$keys = array();
		$max_level = get_max_depth($cur, true, -1, $keys);
		if ($cur != 'Root')
		{
			$max_level++;
		}
		$template->assign_vars(array(
			'INC_SPAN'		=> ($max_level + 3),
			'INC_SPAN_ALL'	=> ($max_level + 7),
			)
		);
	}

	// if forum index, omit one level
	if ($cur == 'Root')
	{
		$level = -1;
	}

	// sub-levels
	if ($CH_this >= -1)
	{
		// cat header row
		if ($tree['type'][$CH_this] == POST_CAT_URL)
		{
			// display a cat row
			$cat = $tree['data'][$CH_this];
			$cat_id = $tree['id'][$CH_this];

			// get the class colors
			$class_catLeft   = 'cat';
			$class_catMiddle = 'cat';
			$class_catRight  = 'cat';

			$cat_title = $cat['cat_title'];
			$cat_title_trad = get_object_lang(POST_CAT_URL . $cat_id, 'name');
			if ($cat_title != $cat_title_trad) $cat_title = '(' . $cat_title . ') ' . $cat_title_trad;

			// title and icon
			$cat_desc = $cat['cat_desc'];
			$cat_desc_trad = get_object_lang(POST_CAT_URL . $cat_id, 'desc');
			if ($cat_desc != $cat_desc_trad)
			{
				$cat_desc = '(' . $cat_desc . ') ' . $cat_desc_trad;
			}
			$cat_icon = empty($cat['icon']) ? '' : '<img src="' . (isset($images[ $cat['icon'] ]) ? '../' . $images[ $cat['icon'] ] : '../' . $cat['icon']) . '" alt="' . $cat['icon'] . '" title="' . $cat['icon'] . '" />';

			// send to template
			$template->assign_block_vars('catrow', array());
			$template->assign_block_vars('catrow.cathead', array(
				'CAT_ID'			=> $cat_id,
				'CAT_TITLE'			=> $cat_title,
				'CAT_DESCRIPTION'	=> $cat_desc,
				'ICON_IMG'			=> $cat_icon,

				'CLASS_CATLEFT'		=> $class_catLeft,
				'CLASS_CATMIDDLE'	=> $class_catMiddle,
				'CLASS_CATRIGHT'	=> $class_catRight,
				'INC_SPAN'			=> $max_level - $level + 3,
				'WIDTH'				=> ($max_level == $level) ? 'width="50%"' : '',

				'U_CAT_EDIT'		=> append_sid('admin_forums.' . PHP_EXT . '?mode=editcat&amp;' . POST_CAT_URL . '=' . $cat_id),
				'U_CAT_DELETE'		=> append_sid('admin_forums.' . PHP_EXT . '?mode=deletecat&amp;' . POST_CAT_URL . '=' . $cat_id),
				'U_CAT_MOVE_UP'		=> append_sid('admin_forums.' . PHP_EXT . '?mode=cat_order&amp;move=-15&amp;' . POST_CAT_URL . '=' . $cat_id),
				'U_CAT_MOVE_DOWN'	=> append_sid('admin_forums.' . PHP_EXT . '?mode=cat_order&amp;move=15&amp;' . POST_CAT_URL . '=' . $cat_id),
				'U_VIEWCAT'			=> append_sid('admin_forums.' . PHP_EXT . '?' . POST_CAT_URL . '=' . $cat_id))
			);
			// add indentation to the display
			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('catrow.cathead.inc', array());
			}
		}

		// forum header row
		if ($tree['type'][$CH_this] == POST_FORUM_URL)
		{
			$forum = $tree['data'][$CH_this];
			$forum_id = $tree['id'][$CH_this];
			$forum_link_img = '';
			if (!empty($tree['data'][$CH_this]['forum_link']))
			{
				$forum_link_img = '<img src="' . $images['acp_link'] . '" />';
			}
			else
			{
				$sub = (isset($tree['sub'][POST_FORUM_URL . $forum_id]));
				$forum_link_img = '<img src="' . (($sub) ? $images['acp_category'] : $images['acp_forum']) . '" />';
				if ($tree['data'][$CH_this]['forum_status'] == FORUM_LOCKED)
				{
					$forum_link_img = '<img src="' . (($sub) ? $images['acp_category_locked'] : $images['acp_forum_locked']) . '" />';
				}
			}

			$forum_name = $forum['forum_name'];
			$forum_name_trad = get_object_lang(POST_FORUM_URL . $forum_id, 'name');
			if ($forum_name != $forum_name_trad) $forum_name = '(' . $forum_name . ') ' . $forum_name_trad;

			$forum_desc = $forum['forum_desc'];
			$forum_desc_trad = get_object_lang(POST_FORUM_URL . $forum_id, 'desc');
			if ($forum_desc != $forum_desc_trad)
			{
				$forum_desc = '(' . $forum_desc . ') ' . $forum_desc_trad;
			}

			$template->assign_block_vars('catrow', array());
			$template->assign_block_vars('catrow.forumrow', array(
				'LINK_IMG'			=> $forum_link_img,
				//'ICON_IMG'			=> empty($forum['icon']) ? '' : '<img src="' . (isset($images[ $forum['icon'] ]) ? IP_ROOT_PATH . $images[ $forum['icon'] ] : $forum['icon']) . '" alt="' . $forum['icon'] . '" title="' . $forum['icon'] . '" />',
				'ICON_IMG'			=> empty($forum['icon']) ? '' : '<img src="' . (isset($images[ $forum['icon'] ]) ? '../' . $images[ $forum['icon'] ] : '../' . $forum['icon']) . '" alt="' . $forum['icon'] . '" title="' . $forum['icon'] . '" />',
				//'ICON_IMG' => ($icon != "") ? '../' . $icon : '../images/spacer.gif',
				'FORUM_NAME'		=> $forum_name,
				'FORUM_DESC'		=> $forum_desc,
				'NUM_TOPICS'		=> $forum['forum_topics'],
				'NUM_POSTS'			=> $forum['forum_posts'],

				'INC_SPAN'			=> $max_level - $level + 1,
				'WIDTH'				=> ($max_level == $level) ? 'width="50%"' : '',

				'U_VIEWFORUM'		=> append_sid('admin_forums.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id),
				'U_FORUM_EDIT'		=> append_sid('admin_forums.' . PHP_EXT . '?mode=editforum&amp;' . POST_FORUM_URL . '=' . $forum_id),
				'U_FORUM_DELETE'	=> append_sid('admin_forums.' . PHP_EXT . '?mode=deleteforum&amp;' . POST_FORUM_URL . '=' . $forum_id),
				'U_FORUM_MOVE_UP'	=> append_sid('admin_forums.' . PHP_EXT . '?mode=forum_order&amp;move=-15&amp;' . POST_FORUM_URL . '=' . $forum_id),
				'U_FORUM_MOVE_DOWN'	=> append_sid('admin_forums.' . PHP_EXT . '?mode=forum_order&amp;move=15&amp;' . POST_FORUM_URL . '=' . $forum_id),
				'U_FORUM_RESYNC'	=> append_sid('admin_forums.' . PHP_EXT . '?mode=forum_sync&amp;' . POST_FORUM_URL . '=' . $forum_id))
			);

			// add indentation to the display
			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('catrow.forumrow.inc', array());
			}
		}

		// display the sub-level
		for ($i = 0; $i < count($tree['sub'][$cur]); $i++)
		{
			display_admin_index($tree['sub'][$cur][$i], $level+1, $max_level);
		}

		// forum footer

		// cat footer
		if ($tree['type'][$CH_this] == POST_CAT_URL)
		{
			// add the footer
			$template->assign_block_vars('catrow', array());
			$template->assign_block_vars('catrow.catfoot', array(
				'S_ADD_FORUM_SUBMIT'	=> "addforum[$cat_id]",
				'S_ADD_CAT_SUBMIT'		=> "addcategory[$cat_id]",
				'S_ADD_NAME'			=> "name[$cat_id]",
				'INC_SPAN'				=> $max_level - $level+3,
				'INC_SPAN_ALL'			=> $max_level - $level+7,
				)
			);
			// add indentation to the display
			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('catrow.catfoot.inc', array());
			}
		}

		// board index footer
		if ($cur == 'Root')
		{
			$template->assign_block_vars('switch_board_footer', array());
			$template->assign_block_vars('switch_board_footer.sub_forum_attach', array());
		}
	}
}

function admin_check_cat()
{
	global $db;

	$res = false;
	// build the cat list
	$mains = array();

	// from cats
	$sql = "SELECT * FROM " . CATEGORIES_TABLE . " ORDER BY cat_id";
	if (!$result = $db->sql_query($sql)) message_die(GENERAL_ERROR, "Couldn't access list of Categories", "", __LINE__, __FILE__, $sql);
	while ($row = $db->sql_fetchrow($result))
	{
		// fix cat_main value
		if (empty($row['cat_main_type']))
		{
			$row['cat_main_type'] = POST_CAT_URL;
		}
		if ($row['cat_main'] == $row['cat_id'])
		{
			$row['cat_main_type'] = POST_CAT_URL;
			$row['cat_main'] = 0;
		}
		// fill hierarchy array
		$mains[ POST_CAT_URL . $row['cat_id'] ] = $row['cat_main_type'] . $row['cat_main'];
	}  // end while ($row = $db->sql_fetchrow($result))

	// from forums
	$sql = "SELECT * FROM " . FORUMS_TABLE . " ORDER BY forum_id";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't access list of Forums", "", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		// fill hierarchy array
		if (empty($row['main_type']))
		{
			$row['main_type'] = POST_CAT_URL;
		}
		$mains[POST_FORUM_URL . $row['forum_id']] = $row['main_type'] . $row['cat_id'];
	}  // end while ($row = $db->sql_fetchrow($result))

	// no forums nor cats
	if (empty($mains)) return false;

	// push each cat
	reset($mains);
	while (list($id, $main) = each($mains))
	{
		$root		= false;
		$cur		= $id;

		$stack		= array();
		$stack[]	= $cur;
		$error		= false;
		while (!$root)
		{
			// parent catagory doesn't exists
			if (($mains[$cur] != 'c0') && !isset($mains[$mains[$cur]]))
			{
				$error = true;
				$mains[$cur] = 'c0';
			}

			// the parent category is already in the stack (recursive attachement)
			if (in_array($mains[$cur], $stack))
			{
				$error = true;
				$mains[$cur] = 'c0';
			}

			// push parent category id
			$stack[] = $mains[$cur];

			// climb up a level
			$root = ($mains[$cur] == 'c0');
			$cur = $mains[$cur];

		}  // while (!$root)

		// update database
		$type				= substr($id, 0, 1);
		$i					= intval(substr($id, 1));
		$main_type	= substr($mains[$id], 0, 1);
		$main_id		= intval(substr($mains[$id], 1));
		if ($i != 0)
		{
			switch($type)
			{
				case POST_CAT_URL:
					$sql = "UPDATE " . CATEGORIES_TABLE . " SET cat_main_type = '$main_type', cat_main = $main_id WHERE cat_id = $i";
					if (!$result = $db->sql_query($sql)) message_die(GENERAL_ERROR, "Couldn't update list of Categories", "", __LINE__, __FILE__, $sql);
					break;
				case POST_FORUM_URL:
					$sql = "UPDATE " . FORUMS_TABLE . " SET main_type = '$main_type', cat_id = '$main_id' WHERE forum_id = $i";
					if (!$result = $db->sql_query($sql)) message_die(GENERAL_ERROR, "Couldn't update list of Forums", "", __LINE__, __FILE__, $sql);
					break;
				default:
					$sql = '';
					break;
			}
		}
	}
	return $error;
}  // end

function move_tree($type, $id, $move)
{
	global $db;
	global $tree;

	// search the object
	$CH_this = (isset($tree['keys'][$type . $id])) ? $tree['keys'][$type . $id] : -1;

	// get the root id
	$main = ($CH_this < 0) ? 'Root' : $tree['main'][$CH_this];

	// renum objects of the same level and regenerate all
	$cats = array();
	$forums = array();
	$order = 0;
	$parents = array();
	for ($i = 0; $i < count($tree['data']); $i++)
	{
		if ($tree['main'][$i] == $main)
		{
			$order = $order + 10;
			$worder = ($i == $CH_this) ? $order + $move : $order;
			$field_name = ($tree['type'][$i] == POST_CAT_URL) ? 'cat_order' : 'forum_order';
			$tree['data'][$i][$field_name] = $worder;
		}
		if ($tree['type'][$i] == POST_CAT_URL)
		{
			$idx = count($cats);
			$cats[$idx] = $tree['data'][$i];
			$parents[POST_CAT_URL][$tree['main'][$i]][] = $idx;
		}
		else
		{
			$idx = count($forums);
			$forums[$idx] = $tree['data'][$i];
			$parents[POST_FORUM_URL][$tree['main'][$i]][] = $idx;
		}
	}

	// build the tree
	$tree = array();
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	empty_cache_folders(TOPICS_CACHE_FOLDER);
	cache_tree_level('Root', $parents, $cats, $forums);

	// re-order all
	$order = 0;
	for ($i = 0; $i < count($tree['data']); $i++)
	{
		$order = $order + 10;
		if ($tree['type'][$i] == POST_CAT_URL)
		{
			$sql = "UPDATE " . CATEGORIES_TABLE . " SET cat_order = $order WHERE cat_id=" . $tree['id'][$i];
		}
		else
		{
			$sql = "UPDATE " . FORUMS_TABLE . " SET forum_order = $order WHERE forum_id=" . $tree['id'][$i];
		}
		if (!$db->sql_query($sql)) message_die(GENERAL_ERROR, 'Couldn\'t update cat/forum order', '', __LINE__, __FILE__, $sql);
	}
}

function get_info($mode, $id)
{
	global $db;

	$extra_from = '';
	$extra_where = '';
	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'f.forum_id';
			$extra_from = ' f, ' . FORUMS_RULES_TABLE . ' fr';
			$extra_where = ' AND fr.forum_id = f.forum_id';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}
	$sql = "SELECT count(*) as total
		FROM $table";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get Forum / Category information", "", __LINE__, __FILE__, $sql);
	}
	$count = $db->sql_fetchrow($result);
	$count = $count['total'];

	$sql = "SELECT *
		FROM " . $table . $extra_from . "
		WHERE " . $idfield . " = " . $id . $extra_where;

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
	}

	if($db->sql_numrows($result) != 1)
	{
		message_die(GENERAL_ERROR, "Forum / Category doesn't exist or multiple forums/categories with ID $id", "", __LINE__, __FILE__);
	}

	$return = $db->sql_fetchrow($result);
	$return['number'] = $count;
	return $return;
}

function get_list($mode, $id, $select)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$namefield = 'forum_name';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT *
		FROM $table";
	if($select == 0)
	{
		$sql .= " WHERE $idfield <> $id";
	}

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories/Forums", "", __LINE__, __FILE__, $sql);
	}

	$cat_list = "";

	while($row = $db->sql_fetchrow($result))
	{
		$s = "";
		if ($row[$idfield] == $id)
		{
			$s = " selected=\"selected\"";
		}
		$catlist .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}

	return($catlist);
}

function renumber_order($mode, $cat = 0)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$orderfield = 'cat_order';
			$cat = 0;
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$orderfield = 'forum_order';
			$catfield = 'cat_id';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if($cat != 0)
	{
		$sql .= " WHERE $catfield = $cat";
	}
	$sql .= " ORDER BY $orderfield ASC";


	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;
	$inc = 10;

	while($row = $db->sql_fetchrow($result))
	{
		$sql = "UPDATE $table
			SET $orderfield = $i
			WHERE $idfield = " . $row[$idfield];
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}

}

// admin_forums.php - END

// admin_forums_extend.php - BEGIN

function admin_add_error($msg)
{
	global $error, $error_msg, $lang;

	$error = true;
	$error_msg .= (empty($error_msg) ? '<br />' : '<br /><br />') . (isset($lang[$msg]) ? $lang[$msg] : $msg);
}

function admin_get_nav_cat_desc($cur = '')
{
	global $nav_separator, $lang;

	$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

	$nav_cat_desc = make_cat_nav_tree($cur, 'admin_forums_extend');
	if (!empty($nav_cat_desc))
	{
		$nav_cat_desc = $nav_separator . $nav_cat_desc;
	}
	return $nav_cat_desc;
}

function delete_item($old, $new='', $topic_dest='')
{
	global $db;

	// no changes
	if ($old == $new) return;

	// old type and id
	$old_type = substr($old, 0, 1);
	$old_id = intval(substr($old, 1));

	// new type and id
	$new_type = substr($new, 0, 1);
	$new_id = intval(substr($new, 1));
	if (($new_id == 0) || !in_array($new_type, array(POST_FORUM_URL, POST_CAT_URL)))
	{
		$new_type = POST_CAT_URL;
		$new_id = 0;
	}

	// topic dest
	$dst_type = substr($topic_dest, 0, 1);
	$dst_id = intval(substr($topic_dest, 1));
	if (($dst_id == 0) || ($dst_type != POST_FORUM_URL))
	{
		$topic_dest = '';
	}

	// re-attach all the content to the new id
	if (!empty($new))
	{
		$sql = "UPDATE " . FORUMS_TABLE . "
					SET main_type = '$new_type', cat_id = $new_id
					WHERE main_type = '$old_type' AND cat_id = $old_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t update forum attachement', '', __LINE__, __FILE__, $sql);
		}

		// categories
		$sql = "UPDATE " . CATEGORIES_TABLE . "
					SET cat_main_type = '$new_type', cat_main = $new_id
					WHERE cat_main_type = '$old_type' AND cat_main = $old_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t update categories attachement', '', __LINE__, __FILE__, $sql);
		}
	}

	// topics move
	if (!empty($topic_dest) && ($dst_type == POST_FORUM_URL))
	{
		if (($dst_type == POST_FORUM_URL) && ($old_type == POST_FORUM_URL))
		{
			// topics
			$sql = "UPDATE " . TOPICS_TABLE . " SET forum_id = $dst_id WHERE forum_id = $old_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t move topics to other forum', '', __LINE__, __FILE__, $sql);
			}

			// posts
			$sql = "UPDATE " . POSTS_TABLE . " SET forum_id = $dst_id WHERE forum_id = $old_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't move posts to other forum", "", __LINE__, __FILE__, $sql);
			}
			sync('forum', $dst_id);
		}
	}

	// all what is attached to a forum
	if ($old_type == POST_FORUM_URL)
	{
		// read current moderators for the old forum
		$sql = "SELECT ug.user_id FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug
					WHERE a.forum_id = $old_id
						AND a.auth_mod = 1
						AND ug.group_id = a.group_id";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t obtain moderator list', '', __LINE__, __FILE__, $sql);
		}
		$user_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$user_ids[] = $row['user_id'];
		}

		// remove moderator status for those ones
		if (!empty($user_ids))
		{
			$old_moderators = implode(', ', $user_ids);

			// check which ones remain moderators
			$sql = "SELECT ug.user_id FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug
						WHERE a.forum_id <> $old_id
							AND a.auth_mod = 1
							AND ug.group_id = a.group_id
							AND ug.user_id IN ($old_moderators)";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t obtain moderator list', '', __LINE__, __FILE__, $sql);
			}
			$user_ids = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$user_ids[] = $row['user_id'];
			}
			$new_moderators = empty($user_ids) ? '' : implode(', ', $user_ids);

			// update users status
			$sql = "UPDATE " . USERS_TABLE . "
						SET user_level = " . USER . "
						WHERE user_id IN ($old_moderators)
							AND user_level NOT IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t update users mod level', '', __LINE__, __FILE__, $sql);
			}
			if (!empty($new_moderators))
			{
				$sql = "UPDATE " . USERS_TABLE . "
							SET user_level = " . MOD . "
							WHERE user_id IN ($new_moderators)
								AND user_level NOT IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Couldn\'t update users mod level', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		// remove auth for the old forum
		$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " WHERE forum_id = $old_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t remove from auth table', '', __LINE__, __FILE__, $sql);
		}

		// prune table
		$sql = "DELETE FROM " . PRUNE_TABLE . " WHERE forum_id = $old_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t remove from prune table old forum type', '', __LINE__, __FILE__, $sql);
		}

		// polls
		$sql = "SELECT v.vote_id FROM " . VOTE_DESC_TABLE . " v, " . TOPICS_TABLE . " t
					WHERE t.forum_id = $old_id
						AND v.topic_id = t.topic_id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t obtain list of vote ids', '', __LINE__, __FILE__, $sql);
		}
		$vote_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$vote_ids[] = $row['vote_id'];
		}
		$s_vote_ids = empty($vote_ids) ? '' : implode(', ', $vote_ids);
		if (!empty($s_vote_ids))
		{
			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " WHERE vote_id IN ($s_vote_ids)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t remove from vote results table', '', __LINE__, __FILE__, $sql);
			}
			$sql = "DELETE FROM " . VOTE_USERS_TABLE . " WHERE vote_id IN ($s_vote_ids)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t remove from vote results table', '', __LINE__, __FILE__, $sql);
			}
			$sql = "DELETE FROM " . VOTE_DESC_TABLE . " WHERE vote_id IN ($s_vote_ids)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t remove from vote desc table', '', __LINE__, __FILE__, $sql);
			}
		}

		// topics
		prune($old_id, 0, true); // Delete everything from forum
	}

	// delete forums rules
	if ($old_type == POST_FORUM_URL)
	{
		$sql = "DELETE FROM " . FORUMS_RULES_TABLE . " WHERE forum_id = $old_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t delete forum rules', '', __LINE__, __FILE__, $sql);
		}
	}

	// delete the old one
	if ($old_type == POST_FORUM_URL)
	{
		$sql = "DELETE FROM " . FORUMS_TABLE . " WHERE forum_id = $old_id";
	}
	else
	{
		$sql = "DELETE FROM " . CATEGORIES_TABLE . " WHERE cat_id = $old_id";
	}
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t delete old forum/category', '', __LINE__, __FILE__, $sql);
	}

}

function reorder_tree()
{
	global $tree, $db;

	// Make sure forums cache is empty...
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	empty_cache_folders(TOPICS_CACHE_FOLDER);
	// Read the tree
	read_tree(true);

	// Update with new order
	$order = 0;
	for ($i = 0; $i < count($tree['data']); $i++)
	{
		if (!empty($tree['id'][$i]))
		{
			$order += 10;
			if ($tree['type'][$i] == POST_FORUM_URL)
			{
				$sql = "UPDATE " . FORUMS_TABLE . "
							SET forum_order = $order
							WHERE forum_id = " . intval($tree['id'][$i]);
			}
			else
			{
				$sql = "UPDATE " . CATEGORIES_TABLE . "
							SET cat_order = $order
							WHERE cat_id = " . intval($tree['id'][$i]);
			}
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Couldn\'t reorder forums/categories table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	// Make sure forums cache is empty again...
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	empty_cache_folders(TOPICS_CACHE_FOLDER);
	// Re-cache the tree
	cache_tree(true);
	board_stats();
}

// admin_forums_extend.php - END

?>