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

include(IP_ROOT_PATH . 'includes/bb_usage_stats_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_bb_usage_stats.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bb_usage_stats_functions.' . PHP_EXT);


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
/* =================================================================================== */

/* Determine if bb_usage_stats_viewlevel is set and, if not, set it to default. */
if(!isset($config[BBUS_CONFIGPROP_VIEWLEVEL_NAME]))
{
	$viewlevel = BBUS_CONFIGPROP_VIEWLEVEL_DEFAULT;
	set_bb_usage_stats_property(BBUS_CONFIGPROP_VIEWLEVEL_NAME, $viewlevel);
	$config[BBUS_CONFIGPROP_VIEWLEVEL_NAME] = $viewlevel;
}
/* Otherwise, get the viewlevel value. */
else
{
	$viewlevel = $config[BBUS_CONFIGPROP_VIEWLEVEL_NAME];
}

/* Determine if bb_usage_stats_viewoptions is set and, if not, set it to default. */
if(!isset($config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME]))
{
	$viewoptions = BBUS_CONFIGPROP_VIEWOPTIONS_DEFAULT;
	set_bb_usage_stats_property(BBUS_CONFIGPROP_VIEWOPTIONS_NAME, $viewoptions);
	$config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME] = $viewoptions;
}
/* Otherwise, get the viewoptions value. */
else
{
	$viewoptions = $config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME];
}

/* Determine if user is permitted to view forum usage data */
$view_bb_usage_allowed = false;
if (($viewlevel & BBUS_VIEWLEVEL_ANONYMOUS) != 0 && $userdata['user_id'] == ANONYMOUS)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_SELF) != 0 && $profiledata['user_id'] == $userdata['user_id'])
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_USERS) != 0 && $userdata['user_level'] == USER)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_MODERATORS) != 0 && $userdata['user_level'] == MOD)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_ADMINS) != 0 && $userdata['user_level'] == ADMIN)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_SPECIALGRP) != 0)
{
	/* Determine if special group has been set and is not -1.  If either, ignore. */
	if (isset($config[BBUS_CONFIGPROP_SPECIALGRP_NAME]))
	{
		if ($config[BBUS_CONFIGPROP_SPECIALGRP_NAME] != -1)
		{
			if (is_user_member_of_group($userdata['user_id'], $config[BBUS_CONFIGPROP_SPECIALGRP_NAME]))
			{
				$view_bb_usage_allowed = true;
			}
		}
	}
	else
	{
		create_property(BBUS_CONFIGPROP_SPECIALGRP_NAME, BBUS_CONFIGPROP_SPECIALGRP_DEFAULT);
	}
}

/* If the bb_usage_stats_prscale property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (!isset($config[BBUS_CONFIGPROP_PRSCALE_NAME]))
{
	create_property(BBUS_CONFIGPROP_PRSCALE_NAME, BBUS_CONFIGPROP_PRSCALE_DEFAULT);
	$config[BBUS_CONFIGPROP_PRSCALE_NAME] = BBUS_CONFIGPROP_PRSCALE_DEFAULT;
}

/* If the bb_usage_stats_trscale property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (!isset($config[BBUS_CONFIGPROP_TRSCALE_NAME]))
{
	create_property(BBUS_CONFIGPROP_TRSCALE_NAME, BBUS_CONFIGPROP_TRSCALE_DEFAULT);
	$config[BBUS_CONFIGPROP_TRSCALE_NAME] = BBUS_CONFIGPROP_TRSCALE_DEFAULT;
}

/* Now, begin the task of constructing the BB Usage Stats data */
if ($view_bb_usage_allowed)
{

	$is_auth = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

	/* Retrieve user's forum usage data */
	$forum_usage_rows =& get_forum_usage_rows($profiledata['user_id'], $profiledata['user_posts'], ($viewoptions & BBUS_VIEWOPTION_SHOW_ALL_FORUMS));

	/* Retrieve user's topic start data */
	$forum_topic_starts_rows =& get_forum_topic_starts_rows($profiledata['user_id']);

	/* Retrieve section summary information */
	$section_usage_rows =& get_section_usage_rows($forum_usage_rows, $forum_topic_starts_rows);

	/* Set the file handles to include bb_usage_stats.tpl */
	$template->set_filenames(array('bb_usage_stats_template' => 'bb_usage_stats.tpl'));

	$unpruned_post_count = get_unpruned_post_count($profiledata['user_id']);

	$max_columns = 7;
	if (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
	{
		$max_columns++;
	}

	/* If any forum usage results were returned: */
	if ($forum_usage_rows) {
		$prscale = (isset($_POST['prscale'])) ? $_POST['prscale'] : $config[BBUS_CONFIGPROP_PRSCALE_NAME];
		$trscale = (isset($_POST['trscale'])) ? $_POST['trscale'] : $config[BBUS_CONFIGPROP_TRSCALE_NAME];

		/* Pass results on to template... */
		$last_cat_id = -1;
		for ($i = 0; $i < sizeof($forum_usage_rows); $i++)
		{
			$forum_topic_starts = & get_forum_topic_starts($forum_topic_starts_rows, $forum_usage_rows[$i]['forum_id']);

			// The *_per_day calculations assume $memberdays has already been calculated in usercp_viewprofile.php
			$forum_post_rate = sprintf("%01.2f", ($forum_usage_rows[$i]['forum_post_count'] / $memberdays)*$prscale);
			$forum_topic_rate = sprintf("%01.2f", ($forum_topic_starts / $memberdays)*$trscale);

			/* If the section id has changed, set it. */
			$cur_cat_id = $forum_usage_rows[$i]['parent_id'];
			if ($cur_cat_id != $last_cat_id)
			{
				$section_row = & get_section_usage_row($section_usage_rows, $cur_cat_id);

				/* Avoid Div By Zero */
				if ($profiledata['user_posts'] <= 0)
				{
					$section_post_pctutp = sprintf("%01.2f", 0);
				}
				else
				{
					$section_post_pctutp = sprintf("%01.2f", ($section_row['section_post_count'] / $profiledata['user_posts']) * 100);
				}
				$section_post_rate = sprintf("%01.2f", ($section_row['section_post_count'] / $memberdays) * $prscale);
				$section_topic_rate = sprintf("%01.2f", ($section_row['section_topic_starts'] / $memberdays) * $trscale);

				$u_search = append_sid(CMS_PAGE_SEARCH . '?search_author=' . str_replace(array(' '), array('%20'), $profiledata['username']));

				$template->assign_block_vars('bb_usage_section_row', array(
					'U_SECTION' => $u_search,
					'SECTION_ID' => $forum_usage_rows[$i]['parent_id'],
					'SECTION_NAME' => $forum_usage_rows[$i]['cat_title'],
					'SECTION_POST_COUNT' => $section_row['section_post_count'],
					'SECTION_POSTRATE' => $section_post_rate,
					'SECTION_POST_PCTUTP' => $section_post_pctutp . '%',
					'SECTION_NEWTOPICS' => $section_row['section_topic_starts'],
					'SECTION_TOPICRATE' => $section_topic_rate,
					'SECTION_TOPICS_WATCHED' => $section_row['section_watch_count']
					)
				);

				/* If PCTUTUP column is to be visible.... */
				if (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
				{
					/* Avoid Div By Zero */
					if ($unpruned_post_count <= 0)
					{
						$section_post_pctutup = sprintf("%01.2f",0);
					}
					else
					{
						$section_post_pctutup = sprintf("%01.2f", ($section_row['section_post_count'] / $unpruned_post_count)*100);
					}

					/* Add value to template */
					$template->assign_block_vars('bb_usage_section_row.bb_usage_switch_pctutup_section', array(
						'SECTION_POST_PCTUTUP' => $section_post_pctutup . '%'
						)
					);
				}
			}
			$last_cat_id = $cur_cat_id;

			if (!isset($forum_usage_rows[$i]['watch_count']))
			{
				$watch_count = 0;
			}
			else
			{
				$watch_count = $forum_usage_rows[$i]['watch_count'];
			}

			$cur_forum_id = $forum_usage_rows[$i]['forum_id'];
			/* If viewer is not authorized to view the forum, do not display the row */
			if ($is_auth[$cur_forum_id]['auth_read'])
			{
				$template->assign_block_vars('bb_usage_section_row.bb_usage_forum_row', array(
					'FORUM_URL' => $u_search,
					'FORUM_ID' => $cur_forum_id,
					'FORUM_NAME' => $forum_usage_rows[$i]['forum_name'],
					'FORUM_POST_COUNT' => $forum_usage_rows[$i]['forum_post_count'],
					'FORUM_POST_PCTUTP' => sprintf("%01.2f",$forum_usage_rows[$i]['forum_post_pct']) . '%',
					'FORUM_POSTRATE' => $forum_post_rate,
					'FORUM_NEWTOPICS' => $forum_topic_starts,
					'FORUM_TOPICRATE' => $forum_topic_rate,
					'FORUM_TOPICS_WATCHED' => $watch_count
					)
				);

				if (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
				{
					/* Avoid Div By Zero */
					if ($unpruned_post_count <= 0)
					{
						$forum_post_pctutup = sprintf("%01.2f", 0);
					}
					else
					{
						$forum_post_pctutup = sprintf("%01.2f", ($forum_usage_rows[$i]['forum_post_count'] / $unpruned_post_count)*100);
					}
					$template->assign_block_vars('bb_usage_section_row.bb_usage_forum_row.bb_usage_switch_pctutup_forum', array(
						'FORUM_POST_PCTUTUP' => $forum_post_pctutup . '%',
						)
					);
				}
			}
		}

		$u_scale = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']);

		/* Either post rate, topic rate, or both must be scalable by the viewer
		 * for the scaling row to be visible
		 */
		if (($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_PR) != 0 || ($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_TR) != 0)
		{
			/* Only display post scaling list if enabled by administrator */
			if (($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_PR) != 0)
			{
				$pr_scale_select_list = scaleby_select('scale_form', 'prscale', BBUS_SCALING_MIN, BBUS_SCALING_MAX, $prscale);
			}
			else
			{
				$pr_scale_select_list = '&nbsp;';
			}

			/* Only display topic scaling list if enabled by administrator */
			if (($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_TR) != 0)
			{
				$tr_scale_select_list = scaleby_select('scale_form', 'trscale', BBUS_SCALING_MIN, BBUS_SCALING_MAX, $trscale);
			}
			else
			{
				$tr_scale_select_list = '&nbsp;';
			}

			$template->assign_block_vars('bb_usage_switch_scaling_row', array(
					'SCALE_TEXT' => $lang['BBUS_Scale_By'],
					'U_SCALE' => $u_scale,
					'PRSCALE_SELECT_LIST' => $pr_scale_select_list,
					'TRSCALE_SELECT_LIST' => $tr_scale_select_list
				)
			);

			if (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
			{
				$template->assign_block_vars('bb_usage_switch_scaling_row.pctutup_filler_cell', array(
					'FILLER_CELL' => '<td class="cat" align="right" valign="middle">&nbsp;</td>'
					)
				);
			}
		}

	}
	else
	{
		/* Otherwise, handle situation where user has not posted anything. */
		$template->assign_block_vars('bb_usage_row_noposts', array(
			'L_BBUS_MSG_NOPOSTS' => $lang['BBUS_Msg_NoPosts']
			)
		);
	}


	if (($viewoptions & BBUS_VIEWOPTION_MISC_SECTION_VISIBLE) != 0)
	{
		$template->assign_block_vars('bb_usage_switch_miscellaneous_info', array(
			'L_BBUS_COLHDR_MISC' => $lang['BBUS_Misc']
			)
		);

		if (($viewoptions & BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_VISIBLE) != 0)
		{
			$total_posts_pruned = $profiledata['user_posts'] - $unpruned_post_count;
			$template->assign_block_vars('bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts', array(
				'L_BBUS_PRUNED_POSTS' => $lang['BBUS_Unpruned_Posts'],
				'BBUS_PRUNED_POSTS' => $total_posts_pruned,
				'BBUS_PRUNED_POSTS_COLSPAN1' => ($max_columns - 2),
				'BBUS_PRUNED_POSTS_COLSPAN2' => 2
			));
		}
	}


	if (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
	{
		$template->assign_block_vars('bb_usage_switch_pctutup_colhdr', array(
			'L_BBUS_COLHEADER_PCTUTUP' => $lang['BBUS_ColHeader_PctUTUP']
			)
		);
	}

	$template->assign_vars(array(
		'L_BBUS_MOD_TITLE' => $lang['BBUS_Mod_Title'],
		'L_BBUS_COL_DESCRIPTIONS_CAPTION' => $lang['BBUS_Col_Descriptions_Caption'],

		'L_BBUS_COLHEADER_FORUM' => $lang['Forum'],
		'L_BBUS_COLHEADER_POSTS' => $lang['Posts'],
		'L_BBUS_COLHEADER_POSTRATE' => $lang['BBUS_ColHeader_PostRate'] . get_scale_suffix($prscale),
		'L_BBUS_COLHEADER_PCTUTP' => $lang['BBUS_ColHeader_PctUTP'],
		'L_BBUS_COLHEADER_NEWTOPICS' => $lang['BBUS_ColHeader_NewTopics'],
		'L_BBUS_COLHEADER_TOPICRATE' => $lang['BBUS_ColHeader_TopicRate'] . get_scale_suffix($trscale),
		'L_BBUS_COLHEADER_TOPICS_WATCHED' => $lang['BBUS_ColHeader_Topics_Watched'],

		'URL_COLDESC' => append_sid('includes/bb_usage_stats_coldesc.' . PHP_EXT)
		)
	);

	/* Process template for inclusion in profile_view_body.tpl */
	$template->assign_var_from_handle('BB_USAGE_STATS_TEMPLATE','bb_usage_stats_template');
}
?>