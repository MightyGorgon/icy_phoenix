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
* Xore (mods@xore.ca)
*
*/

define('IN_CASHMOD', true);
define('IN_ICYPHOENIX', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('admin_cash.' . PHP_EXT);
}

if (!$cash->currency_count())
{
	message_die(GENERAL_MESSAGE, $lang['Insufficient_currencies']);
}

// Mode setting
$mode = request_var('mode', '');

// Begin program proper
if (isset($_POST['submit']))
{
	$cash_forums = array();
	$current_list = array();

	$forum_types = array(FORUM_POST);
	$forums_array = get_forums_ids($forum_types, false, false);
	foreach ($forums_array as $forum)
	{
		$cash_forums[] = $forum['forum_id'];
	}

	while ($c_cur = &$cash->currency_next($cm_i))
	{
		$varname = 'cash_' . $c_cur->id();
		if (isset($_POST[$varname]) && is_array($_POST[$varname]))
		{
			$activated = array(array(),array());
			for ($i = 0; $i < sizeof($cash_forums); $i++)
			{
				if (isset($_POST[$varname][$cash_forums[$i]]))
				{
					$activated[intval($_POST[$varname][$cash_forums[$i]])][] = $cash_forums[$i];
				}
			}
			$sql_list = '';
			$settings = $c_cur->data('cash_settings');
			if (sizeof($activated[0]) > sizeof($activated[1]))
			{
				$sql_list = implode(",",$activated[1]);
				$settings &= ~CURRENCY_FORUMLISTTYPE;
			}
			else
			{
				$sql_list = implode(",",$activated[0]);
				$settings |= CURRENCY_FORUMLISTTYPE;
			}
			$sql = "UPDATE " . CASH_TABLE . "
				SET cash_settings = $settings, cash_forumlist = '$sql_list'
				WHERE cash_id = " . $c_cur->id();
			$db->sql_query($sql);
		}
	}
	$cash->refresh_table();
	$db->clear_cache('cash_');
}

// Start page proper
$template->set_filenames(array('body' => ADM_TPL . 'cash_forum.tpl'));

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid('cash_forums.' . PHP_EXT),
	'L_FORUM_SETTINGS_TITLE' => $lang['Forum_cm_settings'],
	'L_FORUM_SETTINGS_EXPLAIN' => $lang['Forum_cm_settings_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'NUM_ROWS' => ((2*$cash->currency_count()) + 3),

	'L_ON' => ucwords(strtolower($lang['ON'])),
	'L_OFF' => ucwords(strtolower($lang['OFF']))
	)
);

$boolean_forums = array();

while ($c_cur = &$cash->currency_next($cm_i))
{
	$template->assign_block_vars('cashrow', array(
		'CASH_NAME' => $c_cur->name()
		)
	);
}

$sql = "SELECT forum_id AS cat_id, forum_name AS cat_title, forum_order AS cat_order
	FROM " . FORUMS_TABLE . "
	WHERE forum_type = " . FORUM_CAT . "
	ORDER BY forum_order";
$q_categories = $db->sql_query($sql);

if ($total_categories = $db->sql_numrows($q_categories))
{
	$category_rows = $db->sql_fetchrowset($q_categories);

	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		WHERE forum_type = " . FORUM_POST . "
		ORDER BY forum_order";
	$q_forums = $db->sql_query($sql);

	if ($total_forums = $db->sql_numrows($q_forums))
	{
		$forum_rows = $db->sql_fetchrowset($q_forums);
	}

	// Okay, let's build the index
	$gen_cat = array();

	for ($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		$template->assign_block_vars('catrow', array(
			'S_ADD_FORUM_SUBMIT' => "addforum[$cat_id]",
			'S_ADD_FORUM_NAME' => "forumname[$cat_id]",

			'CAT_ID' => $cat_id,
			'CAT_DESC' => $category_rows[$i]['cat_title'],

			'U_VIEWCAT' => append_sid(IP_ROOT_PATH . CMS_PAGE_FORUM . '?' . POST_CAT_URL . '=' . $cat_id)
			)
		);

		for ($j = 0; $j < $total_forums; $j++)
		{
			$forum_id = $forum_rows[$j]['forum_id'];

			if ($forum_rows[$j]['parent_id'] == $cat_id)
			{
				$template->assign_block_vars('catrow.forumrow', array(
					'FORUM_NAME' => $forum_rows[$j]['forum_name'],
					'FORUM_DESC' => $forum_rows[$j]['forum_desc'],
					'NUM_TOPICS' => $forum_rows[$j]['forum_topics'],
					'NUM_POSTS' => $forum_rows[$j]['forum_posts'],

					'U_VIEWFORUM' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id)
					)
				);

				while ($c_cur = &$cash->currency_next($cm_i))
				{
					$template->assign_block_vars('catrow.forumrow.cashrow', array(
						'S_ON' => (($c_cur->forum_active($forum_id)) ? ' checked="checked"' : ''),
						'S_OFF' => (($c_cur->forum_active($forum_id)) ? '' : ' checked="checked"'),
						'S_NAME' => 'cash_' . $c_cur->id() . '[' . $forum_id . ']'
						)
					);
				}

			}// if ... forumid == catid

		} // for ... forums

	} // for ... categories

}// if ... total_categories

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>