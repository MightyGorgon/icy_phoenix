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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_poll'))
{
	function cms_block_poll()
	{
		global $db, $cache, $template, $images, $userdata, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['b_poll_option.'] = array();

		$template->assign_block_vars('PORTAL_POLL', array());

		if ($cms_config_vars['md_poll_type'][$block_id] == 0)
		{
			$order_sql = 'ORDER BY t.topic_time DESC';
		}
		else
		{
			$order_sql = 'ORDER BY RAND()';
		}

		$sql = 'SELECT t.*, vd.*
			FROM ' . TOPICS_TABLE . ' AS t, ' . VOTE_DESC_TABLE . ' AS vd
			WHERE
				t.forum_id IN (' . $cms_config_vars['md_poll_forum_id'][$block_id] . ') AND
				t.topic_status <> 1 AND
				t.topic_status <> 2 AND
				t.topic_vote = 1 AND
				t.topic_id = vd.topic_id
				' . $order_sql . '
			LIMIT
				0,1';
		$result = $db->sql_query($sql);

		//	if(!$total_posts = $db->sql_numrows($result))
		//	{
		//		message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
		//	}

		if($total_posts = $db->sql_numrows($result))
		{
			$pollrow = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$topic_id = $pollrow[0]['topic_id'] ;

			$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
				FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
				WHERE vd.topic_id = $topic_id
					AND vr.vote_id = vd.vote_id
				ORDER BY vr.vote_option_id ASC";
			$result = $db->sql_query($sql);

			if($vote_options = $db->sql_numrows($result))
			{
				$vote_info = $db->sql_fetchrowset($result);

				$vote_id = $vote_info[0]['vote_id'];
				$vote_title = $vote_info[0]['vote_text'];

				$sql = "SELECT vote_id
					FROM " . VOTE_USERS_TABLE . "
					WHERE vote_id = $vote_id
						AND vote_user_id = " . $userdata['user_id'];
				$result = $db->sql_query($sql);

				$user_voted = ($db->sql_numrows($result)) ? true : 0;

				if(isset($_GET['vote']) || isset($_POST['vote']))
				{
					$view_result = (((isset($_GET['vote'])) ? $_GET['vote'] : $_POST['vote']) == 'viewresult') ? true : 0;
				}
				else
				{
					$view_result = 0;
				}

				$poll_expired = ($vote_info[0]['vote_length']) ? (($vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time()) ? true : 0) : 0;

				if($user_voted || $view_result || $poll_expired || ($pollrow[0]['topic_status'] == TOPIC_LOCKED))
				{

					$template->set_filenames(array('b_pollbox' => 'portal_poll_result.tpl'));

					$vote_results_sum = 0;

					for($i = 0; $i < $vote_options; $i++)
					{
						$vote_results_sum += $vote_info[$i]['vote_result'];
					}

					$vote_graphic = 0;
					$vote_graphic_max = sizeof($images['voting_graphic']);

					for($i = 0; $i < $vote_options; $i++)
					{
						$vote_percent = ($vote_results_sum > 0) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
						if ($vote_percent <= 0.3)
						{
							$vote_color = 'red';
						}
						elseif (($vote_percent > 0.3) && ($vote_percent <= 0.6))
						{
							$vote_color = 'blue';
						}
						elseif ($vote_percent > 0.6)
						{
							$vote_color = 'green';
						}
						$portal_vote_graphic_length = round($vote_percent * $cms_config_vars['md_poll_bar_length'][$block_id]);

						$voting_bar = 'voting_graphic_' . $vote_color;
						$voting_bar_body = 'voting_graphic_' . $vote_color . '_body';
						$voting_bar_left = 'voting_graphic_' . $vote_color . '_left';
						$voting_bar_right = 'voting_graphic_' . $vote_color . '_right';

						$voting_bar_img = $images[$voting_bar];
						$voting_bar_body_img = $images[$voting_bar_body];
						$voting_bar_left_img = $images[$voting_bar_left];
						$voting_bar_right_img = $images[$voting_bar_right];

						$vote_graphic_img = $images['voting_graphic'][$vote_graphic];
						$vote_graphic_img_left = $images['voting_graphic_left'];
						$vote_graphic_img_right = $images['voting_graphic_right'];
						$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;

						$vote_info[$i]['vote_option_text'] = censor_text($vote_info[$i]['vote_option_text']);

						$template->assign_block_vars('b_poll_option', array(
							'B_POLL_OPTION_COLOR' => $vote_color,
							'B_POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
							'B_POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
							'B_POLL_OPTION_PCT' => $vote_percent * 100,
							'B_POLL_OPTION_PERCENT' => sprintf('%.1d%%', ($vote_percent * 100)),
							'POLL_GRAPHIC' => $voting_bar_img,
							'POLL_GRAPHIC_BODY' => $voting_bar_body_img,
							'POLL_GRAPHIC_LEFT' => $voting_bar_left_img,
							'POLL_GRAPHIC_RIGHT' => $voting_bar_right_img,
							'B_POLL_OPTION_COLOR' => $vote_color,
							'B_POLL_OPTION_IMG' => $vote_graphic_img,
							'B_POLL_OPTION_IMG_WIDTH' => $portal_vote_graphic_length / 1
							)
						);
					}

					$template->assign_vars(array(
						'POLL_GRAPHIC' => $voting_bar_img,
						'POLL_GRAPHIC_BODY' => $voting_bar_body_img,
						'POLL_GRAPHIC_LEFT' => $voting_bar_left_img,
						'POLL_GRAPHIC_RIGHT' => $voting_bar_right_img,
						'B_POLL_OPTION_COLOR' => $vote_color,
						'B_L_TOTAL_VOTES' => $lang['Total_votes'],
						'B_TOTAL_VOTES' => $vote_results_sum,
						'B_POLL_OPTION_IMG_L' => $vote_graphic_img_left,
						'B_POLL_OPTION_IMG_R' => $vote_graphic_img_right,
						'B_L_VIEW_RESULTS' => $lang['View_results'],
						'B_U_VIEW_RESULTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;vote=viewresult')
						)
					);

				}
				else
				{
					$template->set_filenames(array('b_pollbox' => 'portal_poll_ballot.tpl'));

					for($i = 0; $i < $vote_options; $i++)
					{
						$vote_info[$i]['vote_option_text'] = censor_text($vote_info[$i]['vote_option_text']);
						$template->assign_block_vars('b_poll_option', array(
							'B_POLL_OPTION_ID' => $vote_info[$i]['vote_option_id'],
							'B_POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
						);
					}

					$template->assign_vars(array(
						'S_SHOW_BALLOT' => ($i > 0) ? true : false,
						'B_LOGIN_TO_VOTE' => '<b><a href="' . append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_HOME) . '">' . $lang['Login_to_vote'] . '</a></b>'
						)
					);

					$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
				}

				$vote_title = censor_text($vote_title);
				$vote_color = 'green';

				$voting_bar = 'voting_graphic_' . $vote_color;
				$voting_bar_body = 'voting_graphic_' . $vote_color . '_body';
				$voting_bar_left = 'voting_graphic_' . $vote_color . '_left';
				$voting_bar_right = 'voting_graphic_' . $vote_color . '_right';

				$voting_bar_img = $images[$voting_bar];
				$voting_bar_body_img = $images[$voting_bar_body];
				$voting_bar_left_img = $images[$voting_bar_left];
				$voting_bar_right_img = $images[$voting_bar_right];

				$template->assign_vars(array(
					'POLL_GRAPHIC' => $voting_bar_img,
					'POLL_GRAPHIC_BODY' => $voting_bar_body_img,
					'POLL_GRAPHIC_LEFT' => $voting_bar_left_img,
					'POLL_GRAPHIC_RIGHT' => $voting_bar_right_img,
					'B_POLL_OPTION_COLOR' => $vote_color,
					'B_POLL_QUESTION' => $vote_title,
					'B_L_SUBMIT_VOTE' => '<input type="submit" name="submit" value="'.$lang['Submit_vote'].'" class="liteoption" />',
					'B_S_HIDDEN_FIELDS' => (!empty($s_hidden_fields)) ? $s_hidden_fields : '',
					'S_POLL_ACTION' => append_sid('posting.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id)
					)
				);

				$template->assign_var_from_handle('PORTAL_POLL', 'b_pollbox');
			}
		}
		else
		{
			$template->set_filenames(array('pollbox' => 'portal_poll_ballot.tpl'));

			$vote_color = 'green';

			$voting_bar = 'voting_graphic_' . $vote_color;
			$voting_bar_body = 'voting_graphic_' . $vote_color . '_body';
			$voting_bar_left = 'voting_graphic_' . $vote_color . '_left';
			$voting_bar_right = 'voting_graphic_' . $vote_color . '_right';

			$voting_bar_img = $images[$voting_bar];
			$voting_bar_body_img = $images[$voting_bar_body];
			$voting_bar_left_img = $images[$voting_bar_left];
			$voting_bar_right_img = $images[$voting_bar_right];

			$template->assign_vars(array(
				'POLL_GRAPHIC' => $voting_bar_img,
				'POLL_GRAPHIC_BODY' => $voting_bar_body_img,
				'POLL_GRAPHIC_LEFT' => $voting_bar_left_img,
				'POLL_GRAPHIC_RIGHT' => $voting_bar_right_img,
				'B_POLL_OPTION_COLOR' => $vote_color,
				'B_POLL_QUESTION' => $lang['No_poll'],
				'B_L_SUBMIT_VOTE' => '',
				'B_S_HIDDEN_FIELDS' => '',
				'S_POLL_ACTION' => '',
				'B_LOGIN_TO_VOTE' => '&nbsp;'
				)
			);

			$template->assign_var_from_handle('PORTAL_POLL', 'pollbox');
		}
	}
}

cms_block_poll();

?>