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

define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if (empty($config['plugins']['cash']['enabled']))
{
	message_die(GENERAL_MESSAGE, 'PLUGIN_DISABLED');
}

if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . PHP_EXT);
}

$set = request_var('set', '');
$submit = request_var('submit', '');
if (1empty($submit)))
{
	switch($submit)
	{
		case $lang['Update']:
			$set = 'updatecurrency';
			break;
		case $lang['Delete']:
			$set = 'deletecurrency';
			break;
	}
}

$num_currencies = 0;
$good_order = true;

// Pull all config data
if (!empty($set))
{
	switch ($set)
	{
		case 'updatecurrency': // Change Currency Name
			$cid = request_post_var('cid', 0);
			if (!empty($cid) && $cash->currency_exists($cid))
			{
				/*
					This update works on several levels. If it's just a name change, it's simple... update the value in the Cash Table.
					However, if there's a default value update required, then we need to update the default value for the user table column.
					Also, if there's a change in decimals, we need to update that for the user column, if applicable.
					(by default, we want to _not_ run redundant updates on the system)
					Note: if the DBMS doesn't use a decimal-specific float field, we don't need to update it.
				*/
				$c_cur = $cash->currency($cid);
				$newname = request_post_var('rename_value', '', true);
				$newdefault = request_post_var('default_value', 0);
				$newdecimal = request_post_var('decimal_value', 0);
				$update_name = ($c_cur->data('cash_name') != $newname);
				$update_default = ($c_cur->data('cash_default') != $newdefault);
				$update_decimal = ($c_cur->data('cash_decimal') != $newdecimal);

				// If it's points, update the config points variable (retro- Points System. bleh)
				if ($c_cur->db() == 'user_points')
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
							SET config_value = '" . $db->sql_escape($new_name) . "'
							WHERE config_name = 'points_name'";
					$db->sql_query($sql);
				}
				$sql = array();
				if ($update_default || $update_decimal)
				{
					$sql[] = "ALTER TABLE " . USERS_TABLE . " MODIFY " . $c_cur->db() . " DECIMAL(11, $newdecimal) DEFAULT '$newdefault' NOT NULL";
				}
				// Update the Cash Table
				$sql[] = "UPDATE " . CASH_TABLE . "
						SET cash_name = '" . $db->sql_escape($newname) . "', cash_default = '" . $newdefault . "', cash_decimals = '" . $newdecimal . "'
						WHERE cash_id = " . $c_cur->id();
				for($i = 0; $i < sizeof($sql); $i++)
				{
					$db->sql_query($sql[$i]);
				}

				// Log the action
				// [admin/mod id][admin/mod name][copied currency name][copied over currency name]

				$action = array($userdata['user_id'],
								$userdata['username'],
								$c_cur->name(true),
								$newname
					);
				cash_create_log(CASH_LOG_ADMIN_RENAME_CURRENCY, $action);

				$db->clear_cache('cash_');
			}
			break;
		case 'deletecurrency': // Delete Currency
			$cid = request_post_var('cid', 0);
			if (!empty($cid) && !isset($_POST['cancel']) && $cash->currency_exists($cid))
			{
				$c_cur = $cash->currency($cid);
				if (!isset($_POST['confirm']))
				{
					$s_hidden_fields = '<input type="hidden" name="set" value="deletecurrency" />';
					$s_hidden_fields .= '<input type="hidden" name="cid" value="' . $c_cur->id() . '" />';
					$l_confirm = sprintf($lang['Cash_confirm_delete'],$c_cur->name(true));
					$template->set_filenames(array('confirm_body' => ADM_TPL . 'confirm_body.tpl'));
					$template->assign_vars(array(
						'MESSAGE_TITLE' => $lang['Information'],
						'MESSAGE_TEXT' => $l_confirm,
						'L_YES' => $lang['Yes'],
						'L_NO' => $lang['No'],
						'S_CONFIRM_ACTION' => append_sid('cash_currencies.' . PHP_EXT),
						'S_HIDDEN_FIELDS' => $s_hidden_fields)
					);
					$template->pparse('confirm_body');
					include('page_footer_admin.' . PHP_EXT);
				}
				else
				{
					// Delete the field
					$sql = array();
					$sql[] = "ALTER TABLE " . USERS_TABLE . " DROP " . $c_cur->db();
					for ($i = 0; $i < sizeof($sql); $i++)
					{
						$db->sql_query($sql[$i]);
					}

					// Delete the cash table entry
					$sql = "DELETE FROM " . CASH_TABLE . "
							WHERE cash_id = " . $c_cur->id();
					$db->sql_query($sql);

					// Delete exchange table entries
					$sql = "DELETE FROM " . CASH_EXCHANGE_TABLE . "
							WHERE ex_cash_id1 = " . $c_cur->id() . " OR ex_cash_id2 = " . $c_cur->id();
					$db->sql_query($sql);

					// Log the action
					// [admin/mod id][admin/mod name][currency name]
					$action = array($userdata['user_id'],
									$userdata['username'],
									$c_cur->name(true)
						);
					cash_create_log(CASH_LOG_ADMIN_DELETE_CURRENCY, $action);
					$_POST['submit'] = true;

					$db->clear_cache('cash_');
				}
			}
			break;
		case 'copycurrency': // Copy Currency
			if (!isset($_POST['cancel']) &&
				isset($_POST['cid1']) &&
				is_numeric($_POST['cid1']) &&
				$cash->currency_exists(intval($_POST['cid1'])) &&
				isset($_POST['cid2']) &&
				is_numeric($_POST['cid2']) &&
				$cash->currency_exists(intval($_POST['cid2'])) &&
				(intval($_POST['cid1']) != intval($_POST['cid2'])))
			{
				$c_cur1 = $cash->currency(intval($_POST['cid1']));
				$c_cur2 = $cash->currency(intval($_POST['cid2']));
				if (!isset($_POST['confirm']))
				{
					$s_hidden_fields = '<input type="hidden" name="set" value="copycurrency" />';
					$s_hidden_fields .= '<input type="hidden" name="cid1" value="' . $c_cur1->id() . '" />';
					$s_hidden_fields .= '<input type="hidden" name="cid2" value="' . $c_cur2->id() . '" />';
					$l_confirm = sprintf($lang['Cash_confirm_copy'],$c_cur1->name(true),$c_cur2->name(true));
					$template->set_filenames(array('confirm_body' => ADM_TPL . 'confirm_body.tpl'));
					$template->assign_vars(array(
						'MESSAGE_TITLE' => $lang['Information'],
						'MESSAGE_TEXT' => $l_confirm,
						'L_YES' => $lang['Yes'],
						'L_NO' => $lang['No'],
						'S_CONFIRM_ACTION' => append_sid('cash_currencies.' . PHP_EXT),
						'S_HIDDEN_FIELDS' => $s_hidden_fields)
					);
					$template->pparse('confirm_body');
					include('page_footer_admin.' . PHP_EXT);
				}
				else
				{
					// Copy the data
					$sql = "UPDATE " . USERS_TABLE . " SET " . $c_cur2->db() . " = " . $c_cur1->db();
					$db->sql_query($sql);

					// Log the action
					// [admin/mod id][admin/mod name][copied currency name][copied over currency name]
					$action = array(
						$userdata['user_id'],
						$userdata['username'],
						$c_cur1->name(true),
						$c_cur2->name(true)
					);
					cash_create_log(CASH_LOG_ADMIN_COPY_CURRENCY, $action);
				}
			}
			break;
		case 'newcurrency': // Create Currency
			if (isset($_POST['currency_name']) && isset($_POST['currency_dbfield']) && isset($_POST['currency_decimals']) && isset($_POST['currency_default']) && is_numeric($_POST['currency_decimals']) && is_numeric($_POST['currency_default']))
			{
				$regex = "/^user_[a-z]+$/";
				$new_name = stripslashes($_POST['currency_name']);
				$new_field = stripslashes($_POST['currency_dbfield']);
				$new_decimals = intval(max(0,intval($_POST['currency_decimals'])));
				$factor = pow(10,$new_decimals);
				$new_default = ($new_decimals > 0) ? (intval($_POST['currency_default'] * $factor) / $factor) : intval($_POST['currency_default']);
				$new_order = $cash->currency_count() + 1;
				//
				// Make sure it matches a valid database field... "user_word"
				//
				if (!(preg_match($regex, $new_field)))
				{
					message_die(GENERAL_ERROR, sprintf($lang['Bad_dbfield'],'$regex = \'/^user_[a-z]+$/\';'));
				}

				// Built insert query
				$sql = "ALTER TABLE " . USERS_TABLE . " ADD $new_field DECIMAL(11,$new_decimals) NOT NULL DEFAULT '$new_default'";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					$flag = false;
					while ($c_cur = &$cash->currency_next($cm_i))
					{
						if ($c_cur->db() == $new_field)
						{
							$flag = true;
						}
					}
					if ($flag || (($new_field != 'user_points') && ($new_field != 'user_cash') && ($new_field != 'user_money')))
					{
						message_die(CRITICAL_ERROR, "Could not update user table (possibly duplicate cash db field)", "", __LINE__, __FILE__, $sql);
					}
				}
				//
				// If it's points, update the config points variable (retro- Points System. bleh)
				//
				if ($new_field == "user_points")
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
							SET config_value = '" . $db->sql_escape($new_name) . "'
							WHERE config_name = 'points_name'";
					$db->sql_query($sql);
				}
				//
				// Insert new entry into the cash table
				//
				$sql = "INSERT INTO " . CASH_TABLE . "
						(cash_name, cash_dbfield, cash_order, cash_decimals)
						VALUES ('" . $db->sql_escape($new_name) . "','" . $new_field . "'," . $new_order . "," . $new_decimals . ")";
				$db->sql_query($sql);
				$cid = $db->sql_nextid();

				$sql = "UPDATE " . CASH_TABLE . "
						SET cash_perpost = cash_perpost * $factor,
							cash_postbonus = cash_postbonus * $factor,
							cash_perreply = cash_perreply * $factor,
							cash_maxearn = cash_maxearn * $factor,
							cash_perpm = cash_perpm * $factor,
							cash_perchar = cash_perchar * $factor,
							cash_allowanceamount = cash_allowanceamount * $factor
						WHERE cash_dbfield = '$new_field'";
				$db->sql_query($sql);
				$cid = $db->sql_nextid();

				// Log the action
				// [admin/mod id][admin/mod name][currency name]
				$action = array(
					$userdata['user_id'],
					$userdata['username'],
					$new_name
				);
				cash_create_log(CASH_LOG_ADMIN_CREATE_CURRENCY, $action);

				$db->clear_cache('cash_');
			}
			break;
	}
}

if (isset($_POST['submit']))
{
	$message = $lang['Cash_currencies_updated'] . '<br /><br />' . sprintf($lang['Click_return_cash_currencies'], '<a href="' . append_sid('cash_currencies.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

if (isset($_GET['set']) && isset($_GET['cord']) && is_numeric($_GET['cord']))
{
	$cord = intval($_GET['cord']);
	$old = $new = 0;
	switch ($_GET['set'])
	{
		case 'up':
			$old = $cord;
			$new = $cord - 1;
			break;
		case 'down':
			$old = $cord;
			$new = $cord + 1;
			break;
		default:
			$old = $cord;
			$new = $cord;
			break;
	}
	$focal = $old + $new;
	//
	// Update the order by flipping the entry we want to change, and it's replacement, over their common focal point
	// ie, switch 6 and 7, set 6 to 13 - 6 = 7, and set 7 to 13 - 7 = 6.
	// thus swapping the two values
	// If we're swapping with a null entry, the re-orderer will catch it on the way out
	//
	$sql = "UPDATE " . CASH_TABLE . "
			SET cash_order = $focal - cash_order
			WHERE cash_order = $old OR cash_order = $new";
	$db->sql_query($sql);
	$cash->refresh_table();
	$db->clear_cache('cash_');
}

$cash->reorder();

$template->set_filenames(array('body' => ADM_TPL . 'cash_currencies.tpl'));

$template->assign_vars(array(
	'S_CASH_CURRENCY_ACTION' => append_sid('cash_currency.' . PHP_EXT),

	'L_CASH_CURRENCIES_TITLE' => $lang['Cash_currencies'],
	'L_CASH_CURRENCIES_EXPLAIN' => $lang['Cash_currencies_explain'],

	'L_NEW_CURRENCY' => $lang['Cash_new_currency'],
	'L_COPY_CURRENCY' => $lang['Cash_copy_currency'],
	'L_FIELD' => $lang['Cash_field'],
	'L_CURRENCY' => $lang['Name_of_currency'],
	'L_DEFAULT' => $lang['Default'],
	'L_DECIMALS' => $lang['Decimals'],
	'L_UPDATE' => $lang['Update'],
	'L_ORDER' => $lang['Cash_order'],
	'L_DELETE_CURRENCY' => $lang['Cash_delete'],
	'L_MOVE_UP' => $lang['Move_up'],
	'L_MOVE_DOWN' => $lang['Move_down'],
	'L_CURRENCY_DBFIELD' => $lang['Cash_currency_dbfield'],
	'L_CURRENCY_DECIMALS' => $lang['Cash_currency_decimals'],
	'L_CURRENCY_DEFAULT' => $lang['Cash_currency_default'],
	'L_SET' => $lang['Set'],
	'L_DELETE' => $lang['Delete'],
	'L_FROM' => ucwords($lang['From']),
	'L_TO' => ucwords($lang['To']),
	'L_SELECT_ONE' => $lang['Select_one'],

	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset']
	)
);

$c_cur = 0;
while ($c_cur = &$cash->currency_next($cm_i))
{
	$template->assign_block_vars('cashrow',array(
		'U_MOVE_UP' => append_sid('cash_currencies.' . PHP_EXT . '?set=up&cord=' . $c_cur->data('cash_order')),
		'U_MOVE_DOWN' => append_sid('cash_currencies.' . PHP_EXT . '?set=down&cord=' . $c_cur->data('cash_order')),

		'CASH_INDEX' => $c_cur->id(),
		'DBFIELD' => $c_cur->db(),
		'DEFAULT' => $c_cur->data('cash_default'),
		'DECIMALS' => $c_cur->data('cash_decimals'),
		'CURRENCY' => $c_cur->name(true,'"'),
		'NAME' => $c_cur->name(true)
		)
	);

}

$template->pparse('body');

include('page_footer_admin.' . PHP_EXT);

?>