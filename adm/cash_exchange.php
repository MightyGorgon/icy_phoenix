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

if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . PHP_EXT);
}

if ($cash->currency_count() < 2)
{
	message_die(GENERAL_MESSAGE, $lang['Exchange_insufficient_currencies']);
}

$exchange_update = false;
if (isset($_POST['currency_val']) && is_array($_POST['currency_val']))
{
	$exchange_update = true;
}

$exchange = array();
while ($c_cur = &$cash->currency_next($cm_i))
{
	$exval = $c_cur->exchange();
	if ($exchange_update &&
		 isset($_POST['currency_val'][$c_cur->id()]) &&
		 is_numeric($_POST['currency_val'][$c_cur->id()]) &&
		 (intval($_POST['currency_val'][$c_cur->id()]) > 0))
	{
		$newvalue = $c_cur->attribute_pack('cash_exchange',cash_floatval($_POST['currency_val'][$c_cur->id()]));
		$sql = "UPDATE " . CASH_TABLE . "
				SET cash_exchange = $newvalue
				WHERE cash_id = " . $c_cur->id();
		$db->sql_query($sql);
		$exval = $newvalue / $c_cur->factor();
	}
	$exchange[] = array('id' => $c_cur->id(), 'name' => $c_cur->name(), 'exchange' => $exval);
}
$db->clear_cache('cash_');

for ($i1 = 0; $i1 < sizeof($exchange); $i1++)
{
	$i = $exchange[$i1]['id'];
	$varname = 'exchange_' . $i;
	if (isset($_POST[$varname]) && is_array($_POST[$varname]))
	{
		for ($i2 = 0; $i2 < sizeof($exchange); $i2++)
		{
			$j = $exchange[$i2]['id'];
			if (isset($_POST[$varname][$j]) && ($i != $j))
			{
				$sql = '';
				if ($_POST[$varname][$j] == $lang['Disabled'])
				{
					$sql = "INSERT INTO " . CASH_EXCHANGE_TABLE . "
							(ex_cash_id1, ex_cash_id2, ex_cash_enabled)
							VALUES (" . $i . "," . $j . ", 1)";
				}
				else
				{
					$sql = "DELETE FROM " . CASH_EXCHANGE_TABLE . "
							WHERE ex_cash_id1 = " .  $i . " AND ex_cash_id2 = " . $j;
				}
				$db->sql_query($sql);
			}
		}
	}
}

$sql = "SELECT * FROM " . CASH_EXCHANGE_TABLE;
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$exchange_table[$row['ex_cash_id1']][$row['ex_cash_id2']] = 1;
}

// Start page proper
$template->set_filenames(array('body' => ADM_TPL . 'cash_exchange.tpl'));

$template->assign_vars(array(
	'S_EXCHANGE_ACTION' => append_sid('cash_exchange.' . PHP_EXT),
	'L_EXCHANGE_TITLE' => $lang['Cash_exchange'],
	'L_EXCHANGE_EXPLAIN' => $lang['Cash_exchange_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_EXCHANGE' => $lang['Exchange'],
	'L_TO' => ucwords($lang['To']),
	'L_FROM' => ucwords($lang['From']),

	'CORNER_CLASS' => $theme['td_class1'],
	'SIDE_CLASS' => 'row3',

	'NUM_COLUMNS' => sizeof($exchange))
);

for ($i = 0; $i < sizeof($exchange); $i++)
{
	$template->assign_block_vars('cashrow',array(
		'ROW_CLASS' => ((!(($i - 1) % 3)) ? $theme['td_class2'] : $theme['td_class1']),
		'CURRENCY_ID' => $exchange[$i]['id'],
		'CURRENCY_NAME' => $exchange[$i]['name'],
		'CURRENCY_EXCHANGE' => $exchange[$i]['exchange']
		)
	);
}

for ($i = 0; $i < sizeof($exchange); $i++)
{
	$template->assign_block_vars('siderow', array(
		'ROW_CLASS' => ((!(($i - 1) % 3)) ? $theme['td_class2'] : $theme['td_class1']),
		'CURRENCY_NAME' => $exchange[$i]['name']
		)
	);
	if (!$i)
	{
		$template->assign_block_vars('siderow.switch_first', array());
	}

	for ($j = 0; $j < sizeof($exchange); $j++)
	{
		$node = ($i != $j);
		$row_class = (((!(($j - 1) % 3)) || (!(($i - 1) % 3))) ? $theme['td_class2'] : $theme['td_class1']);

		$text = (($exchange_table[$exchange[$i]['id']][$exchange[$j]['id']])? ('1 : ' . ($exchange[$j]['exchange'] / $exchange[$i]['exchange'])):$lang['Disabled']);
		$button = '<input type="submit" style="width:75" name="exchange_' . $exchange[$i]['id'] . '[' . $exchange[$j]['id'] . ']" value="' . $text . '" class="liteoption" />';

		$template->assign_block_vars('siderow.entry',array(
			'ROW_CLASS' => $row_class,
			'CURRENCY_EX' => (($node) ? $button : "x")
			)
		);
	}
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>