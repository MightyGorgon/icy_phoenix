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
if(!function_exists('cms_block_paypal_donate'))
{
	function cms_block_paypal_donate()
	{
		global $db, $cache, $config, $template, $lang, $table_prefix, $block_id, $cms_config_vars;

		if (empty($config['plugins']['donations']['enabled']))
		{
			$cms_config_vars['md_paypal_total_donors'][$block_id] = false;
			$cms_config_vars['md_paypal_total_amount'][$block_id] = false;
		}

		$total_donors = 0;
		if (!empty($cms_config_vars['md_paypal_total_donors'][$block_id]))
		{
			$sql = "SELECT COUNT(transaction_id) AS total FROM " . $table_prefix . 'donations_data';
			$result = $db->sql_query($sql, 86400, 'donations_data_');

			if ($total = $db->sql_fetchrow($result))
			{
				$total_donors = $total['total'];
			}
			$db->sql_freeresult($result);
		}

		$total_amount = '';
		if (!empty($cms_config_vars['md_paypal_total_amount'][$block_id]))
		{
			$sql = "SELECT mc_currency, SUM(payment_gross) AS total_amount FROM " . $table_prefix . 'donations_data' . " GROUP BY mc_currency";
			$result = $db->sql_query($sql, 86400, 'donations_data_');
			$total_amounts = array();
			$total_amounts = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			for ($i = 0; $i < sizeof($total_amounts); $i++)
			{
				$total_amount .= (empty($total_amount) ? '' : ', ') . $total_amounts[$i]['mc_currency'] . ' ' . $total_amounts[$i]['total_amount'];
			}
		}

		$template->assign_vars(array(
			'L_DONATE_LINK' => sprintf($lang['PAYPAL_DONATE_DES'], $cms_config_vars['md_paypal_donate_link'][$block_id]),
			'L_DONATE_USERS' => !empty($cms_config_vars['md_paypal_total_donors'][$block_id]) ? sprintf($lang['PAYPAL_DONATE_USERS'], $total_donors) : '',
			'L_DONATE_AMOUNT' => !empty($cms_config_vars['md_paypal_total_amount'][$block_id]) ? sprintf($lang['PAYPAL_DONATE_AMOUNT'], $total_amount) : '',
			)
		);
	}
}

cms_block_paypal_donate();

?>