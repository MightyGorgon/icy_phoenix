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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (count($index) || $cat)
{
	/*
	* check and create link if we must approve downloads
	*/
	$total_approve = $dl_mod->count_dl_approve();
	if ($total_approve)
	{
		$approve_string = ($total_approve == 1) ? $lang['Dl_approve_overview_one'] : $lang['Dl_approve_overview'];
		$template->assign_block_vars('approve', array(
			'L_APPROVE_DOWNLOADS' => sprintf($approve_string, $total_approve),
			'U_APPROVE_DOWNLOADS' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=approve')
			)
		);
	}

	/*
	* check and create link if we must approve comments
	*/
	$total_comment_approve = $dl_mod->count_comments_approve();
	if ($total_comment_approve)
	{
		$approve_comment_string = ($total_comment_approve == 1) ? $lang['Dl_approve_overview_one_comment'] : $lang['Dl_approve_overview_comments'];
		$template->assign_block_vars('approve_comments', array(
			'L_APPROVE_COMMENTS' => sprintf($approve_comment_string, $total_comment_approve),
			'U_APPROVE_COMMENTS' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=capprove')
			)
		);
	}

	$config_rows = '4';

	/*
	* check and create link if user have permissions to view statistics
	*/
	$stats_view = $dl_mod->stats_perm();
	if ($stats_view)
	{
		$template->assign_block_vars('switch_stats_view_on', array(
			'L_DL_STATS' => $lang['Dl_stats'],
			'U_STATS' => append_sid('downloads.' . PHP_EXT . '?view=stat')
			)
		);
	}
	else
	{
		$config_rows--;
	}

	/*
	* create overall mini statistics
	*/
	if ($dl_config['show_footer_stat'])
	{
		$total_todo = count($dl_mod->all_files(0, '', 'ASC', "AND todo <> '' AND todo IS NOT NULL"));
		$total_size = $dl_mod->get_dl_overall_size();
		$total_dl = $dl_mod->get_sublevel_count();
		$total_extern = count($dl_mod->all_files(0, '', 'ASC', "AND extern = 1"));

		$physical_limit = $dl_config['physical_quota'];
		$total_size = ($total_size > $physical_limit) ? $physical_limit : $total_size;

		$physical_limit = $dl_mod->dl_size($physical_limit, 2);

		if ($total_dl && $total_size)
		{
			$total_size = $dl_mod->dl_size($total_size, 2);

			$template->assign_block_vars('total_stat', array(
				'TOTAL_STAT' => sprintf($lang['Dl_total_stat'], $total_dl, $total_size, $physical_limit, $total_extern))
			);
		}
	}

	/*
	* create the overall dl mod jumpbox
	*/
	$dl_jumpbox = '<form method="get" name="dl_jumpbox" action="' . append_sid('downloads.' . PHP_EXT . '?sort_by=' . $sort_by . '&amp;order=' . $order).'" onsubmit="if(this.options[this.selectedIndex].value == -1){ return false; }"><select name="cat" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'dl_jumpbox\'].submit() }">';
	$dl_jumpbox .= '<option value="-1">' . $lang['Dl_cat_name'] . '</option>';
	$dl_jumpbox .= '<option value="-1">----------</option>';
	$dl_jumpbox .= $dl_mod->dl_dropdown(0, 0, $cat, 'auth_view');
	$dl_jumpbox .= '</select>&nbsp;<input type="submit" value="' . $lang['Go'] . '" class="liteoption" /></form>';

	/*
	* create the overall board jumpbox
	*/
	make_jumpbox('viewforum.' . PHP_EXT);

	/*
	* check if users can config something
	*/
	if ((!$dl_config['disable_email'] || !$dl_config['disable_popup'] || defined('CASH_TABLE')) && $userdata['user_id'] != ANONYMOUS)
	{
		$template->assign_block_vars('switch_config_on', array(
			'L_CONFIG' => $lang['Dl_config'],
			'U_CONFIG' => append_sid('downloads.' . PHP_EXT . '?view=user_config')
			)
		);
	}
	else
	{
		$config_rows--;
	}

	if ($total_todo)
	{
		$template->assign_block_vars('switch_todo_on', array(
			'L_TODOLIST' => $lang['Dl_mod_todo'],
			'U_TODOLIST' => append_sid('downloads.' . PHP_EXT . '?view=todo')
			)
		);
	}
	else
	{
		$config_rows--;
	}

	$width = floor(100 / $config_rows) . '%';

	if ($dl_config['user_traffic_once'])
	{
		$l_can_download_again = $lang['Dl_can_download_traffic_footer'];
	}
	else
	{
		$l_can_download_again = '';
	}

	/*
	* load footer template and send default values
	*/
	$template->set_filenames(array('dl_footer' => 'dl_footer.tpl'));

	$template->assign_vars(array(
		'L_DL_BLUE_EXPLAIN' => $lang['Dl_blue_explain_foot'],
		'L_DL_GREEN_EXPLAIN' => $lang['Dl_green_explain'],
		'L_DL_WHITE_EXPLAIN' => $lang['Dl_white_explain'],
		'L_DL_GREY_EXPLAIN' => $lang['Dl_grey_explain'],
		'L_DL_RED_EXPLAIN' => sprintf($lang['Dl_red_explain'], $dl_config['dl_posts']),
		'L_DL_RED_EXPLAIN_ALT' => sprintf($lang['Dl_red_explain_alt'], $dl_config['dl_posts']),
		'L_DL_YELLOW_EXPLAIN' => $lang['Dl_yellow_explain'],
		'L_NEW_DL' => $lang['DL_new'],
		'L_EDIT_DL' => $lang['DL_edit'],
		'L_OVERALL_VIEW' => $lang['Dl_overview'],
		'L_CAN_DOWNLOAD_AGAIN' => $l_can_download_again,

		'DL_JUMPBOX' => $dl_jumpbox,
		'DL_MOD_RELEASE' => sprintf($lang['Dl_mod_version'], $dl_config['dl_mod_version']),

		'NEW_DL' => $images['Dl_new'],
		'EDIT_DL' => $images['Dl_edit'],
		'BLUE' => $images['Dl_blue'],
		'GREEN' => $images['Dl_green'],
		'WHITE' => $images['Dl_white'],
		'GREY' => $images['Dl_grey'],
		'RED' => $images['Dl_red'],
		'YELLOW' => $images['Dl_yellow'],

		'WIDTH' => $width,

		'U_OVERALL_VIEW' => append_sid('downloads.' . PHP_EXT . '?view=overall')
		)
	);

	if ($dl_config['show_footer_stat'])
	{
		if ($dl_config['overall_traffic'] - $dl_config['remain_traffic'] <= 0)
		{
			$overall_traffic = $dl_mod->dl_size($dl_config['overall_traffic']);

			$template->assign_block_vars('no_remain_traffic', array(
				'NO_OVERALL_TRAFFIC' => sprintf($lang['Dl_no_more_remain_traffic'], $overall_traffic))
			);
		}
		else
		{
			$remain_traffic = $dl_config['overall_traffic'] - $dl_config['remain_traffic'];

			$remain_text_out = $lang['Dl_remain_overall_traffic'] . '<b>' . $dl_mod->dl_size($remain_traffic, 2) . '</b>';

			$template->assign_block_vars('remain_traffic', array(
				'REMAIN_TRAFFIC' => $remain_text_out)
			);

			$user_traffic = ($userdata['user_traffic'] > $remain_traffic) ? $remain_traffic : $userdata['user_traffic'];

			$user_traffic_out = $dl_mod->dl_size($user_traffic, 2);

			$template->assign_block_vars('userdata', array(
				'ACCOUNT_TRAFFIC' => ($userdata['user_id'] != ANONYMOUS) ? sprintf($lang['Dl_account'], $user_traffic_out) : ''
				)
			);
		}
	}

	if ($dl_config['show_footer_legend'])
	{
		$template->assign_block_vars('footer_legend', array());
	}

	/*
	* display the page and return after this
	*/
	$template->pparse('dl_footer');
}

?>