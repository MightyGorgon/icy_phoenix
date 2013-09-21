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
include(IP_ROOT_PATH . 'includes/bb_usage_stats_functions.' . PHP_EXT);

setup_extra_lang(array('lang_bb_usage_stats'));

/* Determine if bb_usage_stats_viewlevel is set and, if not, set it to default. */
if(!isset($config[BBUS_CONFIGPROP_VIEWLEVEL_NAME]))
{
	$viewlevel = BBUS_CONFIGPROP_VIEWLEVEL_DEFAULT;
	set_config(BBUS_CONFIGPROP_VIEWLEVEL_NAME, $viewlevel);
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
	set_config(BBUS_CONFIGPROP_VIEWOPTIONS_NAME, $viewoptions);
	$config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME] = $viewoptions;
}
/* Otherwise, get the viewoptions value. */
else
{
	$viewoptions = $config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME];
}

/* Determine if user is permitted to view forum usage data */
$view_bb_usage_allowed = false;
if (($viewlevel & BBUS_VIEWLEVEL_ANONYMOUS) != 0 && $user->data['user_id'] == ANONYMOUS)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_SELF) != 0 && $profiledata['user_id'] == $user->data['user_id'])
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_USERS) != 0 && $user->data['user_level'] == USER)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_MODERATORS) != 0 && $user->data['user_level'] == MOD)
{
	$view_bb_usage_allowed = true;
}
elseif(($viewlevel & BBUS_VIEWLEVEL_ADMINS) != 0 && $user->data['user_level'] == ADMIN)
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
			if (is_user_member_of_group($user->data['user_id'], $config[BBUS_CONFIGPROP_SPECIALGRP_NAME]))
			{
				$view_bb_usage_allowed = true;
			}
		}
	}
	else
	{
		set_config(BBUS_CONFIGPROP_SPECIALGRP_NAME, BBUS_CONFIGPROP_SPECIALGRP_DEFAULT);
	}
}

/* If the bb_usage_stats_prscale property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (!isset($config[BBUS_CONFIGPROP_PRSCALE_NAME]))
{
	set_config(BBUS_CONFIGPROP_PRSCALE_NAME, BBUS_CONFIGPROP_PRSCALE_DEFAULT);
	$config[BBUS_CONFIGPROP_PRSCALE_NAME] = BBUS_CONFIGPROP_PRSCALE_DEFAULT;
}

/* If the bb_usage_stats_trscale property is not in the board's configuration,
 * add it and generate the select list.  Otherwise, just generate the select
 * list. */
if (!isset($config[BBUS_CONFIGPROP_TRSCALE_NAME]))
{
	set_config(BBUS_CONFIGPROP_TRSCALE_NAME, BBUS_CONFIGPROP_TRSCALE_DEFAULT);
	$config[BBUS_CONFIGPROP_TRSCALE_NAME] = BBUS_CONFIGPROP_TRSCALE_DEFAULT;
}

/* Now, begin the task of constructing the BB Usage Stats data */
if (!empty($show_extra_stats) && $view_bb_usage_allowed)
{

	$is_auth = auth(AUTH_READ, AUTH_LIST_ALL, $user->data);

	$forum_cats = get_root_categories_rows();
	$forum_cats_array = array();
	foreach ($forum_cats as $forum_cat)
	{
		$forum_cats_array[$forum_cat['forum_id']] = $forum_cat['forum_id'];
	}

	/* Retrieve user's forum usage data */
	$forum_usage_rows = &get_forum_usage_rows($profiledata['user_id'], $profiledata['user_posts'], ($viewoptions & BBUS_VIEWOPTION_SHOW_ALL_FORUMS));

	/* Retrieve user's topic start data */
	$forum_topic_starts_rows = &get_forum_topic_starts_rows($profiledata['user_id']);

	/* Retrieve section summary information */
	$section_usage_rows = &get_section_usage_rows($forum_usage_rows, $forum_topic_starts_rows);

	/* Set the file handles to include bb_usage_stats.tpl */
	$template->set_filenames(array('bb_usage_stats_template' => 'bb_usage_stats.tpl'));

	$unpruned_post_count = get_unpruned_post_count($profiledata['user_id']);

	$max_columns = 7;
	if (($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0)
	{
		$max_columns++;
	}

	/* If any forum usage results were returned: */
	if ($forum_usage_rows)
	{
		$prscale = (isset($_POST['prscale'])) ? $_POST['prscale'] : $config[BBUS_CONFIGPROP_PRSCALE_NAME];
		$trscale = (isset($_POST['trscale'])) ? $_POST['trscale'] : $config[BBUS_CONFIGPROP_TRSCALE_NAME];

		/* Pass results on to template... */
		$last_cat_id = -1;
		for ($i = 0; $i < sizeof($forum_usage_rows); $i++)
		{
			$forum_topic_starts = &get_forum_topic_starts($forum_topic_starts_rows, $forum_usage_rows[$i]['forum_id']);

			// The *_per_day calculations assume $memberdays has already been calculated in usercp_viewprofile.php
			$forum_post_rate = sprintf("%01.2f", ($forum_usage_rows[$i]['forum_post_count'] / $memberdays)*$prscale);
			$forum_topic_rate = sprintf("%01.2f", ($forum_topic_starts / $memberdays)*$trscale);

			/* If the section id has changed, set it. */
			$cur_cat_id = $forum_usage_rows[$i]['parent_id'];
			// Mighty Gorgon: with the new category system we may just check if the parent is 0!
			$cur_cat_root_parent = (in_array($forum_usage_rows[$i]['parent_id'], $forum_cats_array) ? true : false);
			$cat_changed = ($cur_cat_id == $last_cat_id) ? false : true;
			if ($cur_cat_root_parent && $cat_changed)
			{
				$section_row = &get_section_usage_row($section_usage_rows, $cur_cat_id);

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
				$last_cat_id = $cur_cat_id;
			}

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

		$u_scale = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;stats=1&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']);

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