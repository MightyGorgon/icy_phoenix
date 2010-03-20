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
* Chris Lennert - (calennert@users.sourceforge.net) - (http://lennertmods.sourceforge.net)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/******************************************************************************
 * Creates the select list for post rate and topic rate scaling
 ******************************************************************************/
function scaleby_select($form_name, $select_name, $scale_start, $scale_end, $value_selected) {
	$selected_attribute = 'selected="selected"';

	$select_text = "<select name=\"$select_name\"";

	if ($form_name != '')
	{
		$select_text .= " onchange=\"if(this.options[this.selectedIndex].value != $value_selected){ forms['$form_name'].submit() }\">";
	}
	else
	{
		$select_text .= ">";
	}

	/* Add factor-of-ten scale values to pull-down list */
	for ($scale = $scale_start; $scale < ($scale_end * 10);)
	{
		$selected = ($scale == $value_selected) ? $selected_attribute : '';
		$select_text .= "<option value=\"$scale\" $selected>$scale</option>";
		$scale *= 10;
	}

	$select_text .= '</select>';

	return $select_text;
}

/******************************************************************************
 * Determines whether user is member of group
 ******************************************************************************/
function is_user_member_of_group($user_id, $group_id)
{
	global $db;

	/* Retrieve forum topic start data from database */
	$sql = 'SELECT group_id, user_id FROM ' . USER_GROUP_TABLE . " WHERE group_id = $group_id AND user_id = $user_id";
	$result = $db->sql_query($sql);

	$retval = false;
	if ($row = $db->sql_fetchrow($result))
	{
		$retval = true;
	}

	$db->sql_freeresult($result);
	unset($sql);

	/* Return results */
	return $retval;
}

/******************************************************************************
 * Loop through topic starts data to find the number of new topics
 * this user has initiated in the specified forum.  Assume zero
 * starts if no match found.
 ******************************************************************************/
function get_forum_topic_starts(&$topic_starts_rows, $forum_id)
{
	$topic_starts = 0;
	for ($i = 0; $i < sizeof($topic_starts_rows); $i++)
	{
		if ($topic_starts_rows[$i]['forum_id'] == $forum_id)
		{
			$topic_starts = $topic_starts_rows[$i]['forum_topic_starts'];
			break;
		}
	}
	return $topic_starts;
}


/******************************************************************************
 * Returns the row corresponding to the specified $section_id.
 ******************************************************************************/
function &get_section_usage_row(&$s_usage_rows, $section_id)
{
	$row = NULL;
	for ($i = 0; $i < sizeof($s_usage_rows); $i++) {
		if ($s_usage_rows[$i]['section_id'] == $section_id)
		{
			$row = & $s_usage_rows[$i];
			break;
		}
	}
	return $row;
}


/******************************************************************************
 * Uses the retrieved forum rows and topic starts rows to calculate the
 * section summary information.
 ******************************************************************************/
function &get_section_usage_rows(&$f_usage_rows, &$f_topic_starts_rows)
{
	$section_rows = array();
	$row_count = sizeof($f_usage_rows);
	$j = 0;
	$section_post_count = 0;
	$section_topic_starts = 0;
	$section_watch_count = 0;
	$last_section_id = $row_count > 0 ? $f_usage_rows[0]['parent_id'] : -999;

	for ($i = 0; $i < $row_count; $i++)
	{
		/* If the section id has changed, add new row to $section_rows
		 * and reset count variables. Otherwise, simply update section data */
		$cur_section_id = $f_usage_rows[$i]['parent_id'];

		if ($cur_section_id != $last_section_id)
		{
			$section_rows[$j++] = array(
				'section_id' => $last_section_id,
				'section_post_count' => $section_post_count,
				'section_topic_starts' => $section_topic_starts,
				'section_watch_count' => $section_watch_count
			);

			$section_post_count = 0;
			$section_topic_starts = 0;
			$section_watch_count = 0;
		}

		$section_post_count += $f_usage_rows[$i]['forum_post_count'];
		$section_topic_starts += get_forum_topic_starts($f_topic_starts_rows, $f_usage_rows[$i]['forum_id']);
		$section_watch_count += $f_usage_rows[$i]['watch_count'];
		$last_section_id = $cur_section_id;
	}

	/* Make sure we include the last section */
	$section_rows[$j++] = array(
		'section_id' => $last_section_id,
		'section_post_count' => $section_post_count,
		'section_topic_starts' => $section_topic_starts,
		'section_watch_count' => $section_watch_count
	);

	/* Return results */
	return $section_rows;
}


/******************************************************************************
 * If scaling factor is not equal to 1, the function returns an
 * expression describing how much scaling is being performed (e.g., "x 100")
 ******************************************************************************/
function get_scale_suffix($scale_factor)
{
	if ($scale_factor != 1)
	{
		return "<br /><font size=\"-2\">x $scale_factor</font>";
	}
	else
	{
		return '';
	}
}


/******************************************************************************
 * Retrieves from the database a count of the specified user's unpruned posts
 ******************************************************************************/
function get_unpruned_post_count($user_id)
{
	global $db;

	$sql = "SELECT DISTINCT p.poster_id, COUNT(p.poster_id) AS post_count FROM " . POSTS_TABLE . " AS p WHERE p.poster_id = $user_id GROUP BY p.poster_id";
	$result = $db->sql_query($sql);

	$post_count = 0;
	if ($row = $db->sql_fetchrow($result))
	{
		$post_count = $row['post_count'];
	}
	$db->sql_freeresult($result);
	unset($sql);

	/* Return results */
	return $post_count;
}


/******************************************************************************
 * The number of topics being watched per forum
 ******************************************************************************/
function &get_topics_watched_rows($user_id)
{
	global $db;

	$sql = 'SELECT t.forum_id, w.user_id, count(w.topic_id)  AS watch_count FROM ' . TOPICS_WATCH_TABLE . ' AS w INNER  JOIN ' . TOPICS_TABLE . " AS t ON w.topic_id = t.topic_id GROUP  BY w.user_id, t.forum_id HAVING (w.user_id = $user_id) ORDER  BY t.forum_id";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$rows[] = $row;
	}

	$db->sql_freeresult($result);
	unset($sql);

	/* Return results */
	return $rows;
}


/******************************************************************************
 * Retrieves the ordered list of forums with category names and id's
 ******************************************************************************/
function &get_forum_categories_rows($show_all_forums)
{
	global $db;

	if($show_all_forums)
	{
		$sql = 'SELECT c.forum_id AS cat_id, c.forum_name AS cat_title, f.forum_id, f.forum_name, 0 AS forum_post_count, 0 AS forum_post_pct FROM ' . FORUMS_TABLE . ' AS f INNER JOIN ' . FORUMS_TABLE . ' AS c ON f.parent_id = c.forum_id ORDER BY f.forum_order';
	}
	else
	{
		$sql = 'SELECT c.forum_id AS cat_id, c.forum_name AS cat_title, f.forum_id, f.forum_name FROM ' . FORUMS_TABLE . ' AS f INNER JOIN ' . FORUMS_TABLE . ' AS c ON f.parent_id = c.forum_id ORDER  BY f.forum_order';
	}
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$rows[] = $row;
	}

	$db->sql_freeresult($result);
	unset($sql);

	/* Return results */
	return $rows;
}


/******************************************************************************
 * Retrieves from the database the forum usage data for the specified user
 ******************************************************************************/
function &get_forum_usage_rows($user_id, $user_posts, $show_all_forums)
{
	global $db;

	/* First, retrieve the forum categories rows */
	$forum_categories_rows = & get_forum_categories_rows($show_all_forums);

	/* Then, the number of topics watched per forum */
	$topics_watched_rows = & get_topics_watched_rows($user_id);

	/* Next, retrieve user's forum usage info */
	$sql = "SELECT f.forum_id, f.forum_name, p.poster_id, COUNT(p.poster_id) AS forum_post_count, (COUNT(p.poster_id) / $user_posts)*100 AS forum_post_pct FROM " . POSTS_TABLE . " AS p INNER JOIN " . FORUMS_TABLE . " AS f ON f.forum_id = p.forum_id GROUP BY p.forum_id, p.poster_id HAVING (p.poster_id = $user_id) ORDER BY f.forum_id";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$rows[] = $row;
	}

	$db->sql_freeresult($result);
	unset($sql);

	if ($show_all_forums)
	{
		/* Merge the forum usage info with the forum categories info. */
		for ($i = 0; $i < sizeof($forum_categories_rows); $i++)
		{
			for ($j = 0; $j < sizeof($rows); $j++)
			{
				if ($forum_categories_rows[$i]['forum_id'] == $rows[$j]['forum_id'])
				{
					$forum_categories_rows[$i] = array_merge($forum_categories_rows[$i], $rows[$j]);
					break;
				}
			}
		}

		for ($i = 0; $i < sizeof($topics_watched_rows); $i++)
		{
			for ($j = 0; $j < sizeof($forum_categories_rows); $j++)
			{
				if ($topics_watched_rows[$i]['forum_id'] == $forum_categories_rows[$j]['forum_id'])
				{
					$forum_categories_rows[$j] = array_merge($forum_categories_rows[$j], $topics_watched_rows[$i]);
					break;
				}
			}
		}

		/* Merge the topics watched info. */
		return $forum_categories_rows;
	}
	else
	{
		$return_rows = array();
		$h = 0;
		/* Match up the forum usage info with the forum categories info. */
		for ($i = 0; $i < sizeof($forum_categories_rows); $i++)
		{
			for ($j = 0; $j < sizeof($rows); $j++)  {
				if ($forum_categories_rows[$i]['forum_id'] == $rows[$j]['forum_id']) {
					$return_rows[$h++] = array_merge($forum_categories_rows[$i], $rows[$j]);
					break;
				}
			}
		}

		for ($i = 0; $i < sizeof($topics_watched_rows); $i++)
		{
			for ($j = 0; $j < sizeof($return_rows); $j++)
			{
				if ($topics_watched_rows[$i]['forum_id'] == $return_rows[$j]['forum_id'])
				{
					$return_rows[$j] = array_merge($return_rows[$j], $topics_watched_rows[$i]);
					break;
				}
			}
		}

		return $return_rows;
	}
}


/******************************************************************************
 * Retrieves from the database the topic start counts for the specified user
 ******************************************************************************/
function &get_forum_topic_starts_rows($user_id)
{
	global $db;

	/* Retrieve forum topic start data from database */
	$sql = 'SELECT COUNT(topic_id) AS forum_topic_starts, forum_id, topic_poster FROM  ' . TOPICS_TABLE . " GROUP BY forum_id, topic_poster HAVING (topic_poster = $user_id)";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$rows[] = $row;
	}

	$db->sql_freeresult($result);
	unset($sql);

	/* Return results */
	return $rows;
}

?>