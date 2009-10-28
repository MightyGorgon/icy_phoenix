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

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . PHP_EXT);
}

if (!$cash->currency_count())
{
	message_die(GENERAL_MESSAGE, $lang['Insufficient_currencies']);
}

$mode = isset($_POST['mode']) ? $_POST['mode'] : 'main';

switch ($mode)
{
	case 'reset':
		if (isset($_POST['confirm']) && isset($_POST['cash_amount']) && is_array($_POST['cash_amount']) && isset($_POST['cids']))
		{
			$cids = explode(",",$_POST['cids']);
			$cash_check = array();
			for ($i = 0; $i < sizeof($cids);$i++)
			{
				if (isset($_POST['cash_amount'][$cids[$i]]))
				{
					$cash_check[$cids[$i]] = cash_floatval($_POST['cash_amount'][$cids[$i]]);
				}
			}
			$update_clause = array();
			while ($c_cur = &$cash->currency_next($cm_i))
			{
				if (isset($cash_check[$c_cur->id()]))
				{
					$update_clause[] = $c_cur->db() . ' = ' . $cash_check[$c_cur->id()];
				}
			}
			if (sizeof($update_clause))
			{
				$sql = "UPDATE " . USERS_TABLE . "
						SET " . implode(", ", $update_clause);
				$db->sql_query($sql);

				message_die(GENERAL_MESSAGE, '<br />' . $lang['Update_successful'] . '<br /><br />' .  sprintf($lang['Click_return_cash_reset'], '<a href="' . append_sid("cash_reset." . PHP_EXT) . '">', '</a>') . '<br /><br />');
			}
		}
		break;
	case "submitted":
		if (isset($_POST['submit']) && isset($_POST['cash_check']) && is_array($_POST['cash_check']))
		{
			switch($_POST['submit'])
			{
				case $lang['Set_checked']:
					if (isset($_POST['cash_amount']) && is_array($_POST['cash_amount']))
					{
						$s_hidden_fields = '';
						$c_ids = array();

						while ($c_cur = &$cash->currency_next($cm_i))
						{
							if (isset($_POST['cash_check'][$c_cur->id()]) && isset($_POST['cash_amount'][$c_cur->id()]))
							{
								$c_ids[] = $c_cur->id();
								$s_hidden_fields .= '<input type="hidden" name="cash_amount[' . $c_cur->id() . ']" value="' . cash_floatval($_POST['cash_amount'][$c_cur->id()]) . '" />';
							}
						}
						if (sizeof($c_ids))
						{
							$s_hidden_fields .= '<input type="hidden" name="cids" value="' . implode(',',$c_ids) . '" />';
							$s_hidden_fields .= '<input type="hidden" name="mode" value="reset" />';
							$l_confirm = $lang['Cash_confirm_reset'];
							$template->set_filenames(array('confirm_body' => ADM_TPL . 'confirm_body.tpl'));
							$template->assign_vars(array(
								'MESSAGE_TITLE' => $lang['Information'],
								'MESSAGE_TEXT' => $l_confirm,
								'L_YES' => $lang['Yes'],
								'L_NO' => $lang['No'],
								'S_CONFIRM_ACTION' => append_sid('cash_reset.' . PHP_EXT),
								'S_HIDDEN_FIELDS' => $s_hidden_fields
								)
							);
							$template->pparse('confirm_body');
							include('page_footer_admin.' . PHP_EXT);
						}
					}
					break;
				case $lang['Recount_checked']:
					$c_ids = array();
					while ($c_cur = &$cash->currency_next($cm_i))
					{
						if (isset($_POST['cash_check'][$c_cur->id()]))
						{
							$c_ids[] = $c_cur->id();
						}
					}
					if (sizeof($c_ids))
					{
						$s_hidden_fields = '<input type="hidden" name="cids" value="' . implode(',',$c_ids) . '" />';
						$l_confirm = sprintf($lang['Cash_confirm_recount'],'<a href="' . append_sid('admin_board.' . PHP_EXT) . '">','</a>');
						$template->set_filenames(array('confirm_body' => ADM_TPL . 'confirm_body.tpl'));
						$template->assign_vars(array(
							'MESSAGE_TITLE' => $lang['Information'],
							'MESSAGE_TEXT' => $l_confirm,
							'L_YES' => $lang['Yes'],
							'L_NO' => $lang['No'],
							'S_CONFIRM_ACTION' => append_sid('cash_recount.' . PHP_EXT),
							'S_HIDDEN_FIELDS' => $s_hidden_fields)
						);
						$template->pparse('confirm_body');
						include('page_footer_admin.' . PHP_EXT);
					}
					break;
			}
		}
		else
		{
			message_die(GENERAL_MESSAGE, '<br />' .  sprintf($lang['Click_return_cash_reset'], '<a href="' . append_sid('cash_reset.' . PHP_EXT) . '">', '</a>') . '<br /><br />');
		}
		break;
	default:

		if(isset($config['cash_resetting']))
		{
			$template->set_filenames(array('body' => ADM_TPL . 'cash_resetting.tpl'));

			$strings = explode(",",$config['cash_resetting']);

			$template->assign_vars(array(
				'L_CASH_RESETTING' => $lang['Cash_resetting'],
				'L_USER' => sprintf($lang['User_of'],$strings[0],$strings[1]))
			);
		}
		else
		{
			$template->set_filenames(array('body' => ADM_TPL . 'cash_reset.tpl'));
			$hidden_fields = '<input type="hidden" name="mode" value="submitted" />';

			$template->assign_vars(array(
				'S_RESET_ACTION' => append_sid('cash_reset.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields,

				'L_CASH_RESET_TITLE' => $lang['Cash_reset_title'],
				'L_CASH_RESET_EXPLAIN' => $lang['Cash_reset_explain'],

				'L_SET_CHECKED' => $lang['Set_checked'],
				'L_RECOUNT_CHECKED' => $lang['Recount_checked'],
				'L_RESET' => $lang['Reset']
				)
			);

			$i = 0;
			while($c_cur = &$cash->currency_next($cm_i))
			{
				$i++;
				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('cashrow', array(
					'ID' => $c_cur->id(),
					'NAME' => $c_cur->name(),
					'DEFAULT' => $c_cur->data('cash_default'),

					'ROW_CLASS' => $row_class,
					)
				);
			}
		}

		$template->pparse('body');

		include('./page_footer_admin.' . PHP_EXT);

		break;
}

?>