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

if (!$cash->currency_count())
{
	message_die(GENERAL_MESSAGE, $lang['Insufficient_currencies']);
}

$update_set = array(
	CURRENCY_ENABLED => 'bool',
	CURRENCY_IMAGE => 'bool',
	'cash_imageurl' => 'text',
	CURRENCY_PREFIX => 'bool',
	'cash_perpost' => 'float',
	'cash_postbonus' => 'float',
	'cash_perreply' => 'float',
	'cash_perchar' => 'float',
	'cash_perthanks' => 'float',
	'cash_maxearn' => 'float',
	'cash_perpm' => 'float',
	CURRENCY_VIEWPROFILE => 'bool',
	CURRENCY_VIEWTOPIC => 'bool',
	CURRENCY_VIEWMEMBERLIST => 'bool',
	CURRENCY_INCLUDEQUOTES => 'bool',
	CURRENCY_EXCHANGEABLE => 'bool',
	CURRENCY_DONATE => 'bool',
	CURRENCY_MODEDIT => 'bool',
	CURRENCY_ALLOWNEG => 'bool',
	'cash_allowance' => 'int',
	'cash_allowanceamount' => 'float',
	'cash_allowancetime' => 'int'
);

$table_updated = false;
while ($c_cur = &$cash->currency_next($cm_i))
{
	$varname = 'cash_' . $c_cur->id();
	if (isset($_POST['submit']) && isset($_POST[$varname]) && is_array($_POST[$varname]))
	{
		$updates = array();
		$settings = $c_cur->data('cash_settings');
		$settings_update = false;
		while (list($key, $type) = each($update_set))
		{
			if (isset($_POST[$varname][$key]))
			{
				$data = $_POST[$varname][$key];
				switch ($type)
				{
					case 'bool':
						if (($data == '0') || ($data == '1'))
						{
							$data = intval($data);
							$key = intval($key);
							if ($data)
							{
								$settings |= $key;
							}
							else
							{
								$settings &= ~$key;
							}
							$settings_update = true;
						}
						break;
					case 'text':
						$updates[] = $key . ' = \'' . $db->sql_escape($data) . '\'';
						break;
					case 'int':
						if (is_numeric($data))
						{
							$updates[] = $key . ' = ' . intval($data);
						}
						break;
					case 'float':
						if (is_numeric($data))
						{
							$updates[] = $key . ' = ' . $c_cur->attribute_pack($key,$data);
						}
						break;
				}
			}
		}
		if ($settings_update)
		{
			$updates[] = 'cash_settings = ' . $settings;
		}
		reset ($update_set);
		if (sizeof($updates) > 0)
		{
			$sql = "UPDATE " . CASH_TABLE . "
					SET " . implode(", ", $updates) . "
					WHERE cash_id = " . $c_cur->id();
			$db->sql_query($sql);
			$table_updated = true;
		}
	}
}

if ($table_updated)
{
	$cash->refresh_table();
	$db->clear_cache('cash_');
}

if (isset($_POST['submit']))
{
	$message = $lang['Cash_settings_updated'] . '<br /><br />' . sprintf($lang['Click_return_cash_settings'], '<a href="' . append_sid('cash_settings.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$template->set_filenames(array('body' => ADM_TPL . 'cash_settings.tpl'));

$template->assign_vars(array(
	'S_CASH_SETTINGS_ACTION' => append_sid('cash_settings.' . PHP_EXT),

	'NUM_COLUMNS' => ($cash->currency_count() + 1),

	'L_DISPLAY' => $lang['Display'],
	'L_IMPLEMENTATION' => $lang['Implementation'],
	'L_ALLOWANCES' => $lang['Allowances'],
	'L_ALLOWANCES_EXPLAIN' => $lang['Allowances_explain'],

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_CASH_SETTINGS_TITLE' => $lang['Cash_settings'],
	'L_CASH_SETTINGS_EXPLAIN' => $lang['Cash_settings_explain'],

	'L_CASH_ENABLED' => $lang['Cash_enabled'],
	'L_CASH_CURRENCY' => $lang['Cash_custom_currency'],
	'L_PREFIX' => $lang['Prefix'],
	'L_POSTFIX' => $lang['Postfix'],
	'L_CASH_IMAGE' => $lang['Cash_image'],
	'L_CASH_IMAGEURL' => $lang['Cash_imageurl'],
	'L_CASH_IMAGEURL_EXPLAIN' => $lang['Cash_imageurl_explain'],
	'L_CASH_CURRENCY_STYLE' => $lang['Cash_currency_style'],
	'L_CASH_CURRENCY_STYLE_EXPLAIN' => $lang['Cash_currency_style_explain'],
	'L_CASH_DISPLAY_USERCP' => $lang['Cash_display_usercp'],
	'L_CASH_DISPLAY_USERPOST' => $lang['Cash_display_userpost'],
	'L_CASH_DISPLAY_MEMBERLIST' => $lang['Cash_display_memberlist'],

	'L_CASH_AMOUNT_PER_POST' => $lang['Cash_amount_per_post'],
	'L_CASH_AMOUNT_POST_BONUS' => $lang['Cash_amount_post_bonus'],
	'L_CASH_AMOUNT_PER_REPLY' => $lang['Cash_amount_per_reply'],
	'L_CASH_AMOUNT_PER_CHARACTER' => $lang['Cash_amount_per_character'],
	'L_CASH_AMOUNT_PER_THANKS' => $lang['Cash_amount_per_thanks'],
	'L_CASH_MAXEARN' => $lang['Cash_maxearn'],
	'L_CASH_AMOUNT_PER_PM' => $lang['Cash_amount_per_pm'],

	'L_CASH_INCLUDE_QUOTES' => $lang['Cash_include_quotes'],
	'L_CASH_ALLOW_EXCHANGE' => $lang['Cash_exchangeable'],
	'L_CASH_ALLOW_DONATE' => $lang['Cash_allow_donate'],
	'L_CASH_ALLOW_MOD_EDIT' => $lang['Cash_allow_mod_edit'],
	'L_CASH_ALLOW_NEGATIVE' => $lang['Cash_allow_negative'],

	'L_CASH_ALLOWANCE_ENABLED' => $lang['Cash_allowance_enabled'],
	'L_CASH_ALLOWANCE_AMOUNT' => $lang['Cash_allowance_amount'],
	'L_CASH_ALLOWANCE_FREQUNECY' => $lang['Cash_allownace_frequency'],
	'L_CASH_ALLOWANCE_FREQUNECIES_DAY' => $lang['Cash_allownace_frequencies'][CASH_ALLOW_DAY],
	'L_CASH_ALLOWANCE_FREQUNECIES_WEEK' => $lang['Cash_allownace_frequencies'][CASH_ALLOW_WEEK],
	'L_CASH_ALLOWANCE_FREQUNECIES_MONTH' => $lang['Cash_allownace_frequencies'][CASH_ALLOW_MONTH],
	'L_CASH_ALLOWANCE_FREQUNECIES_YEAR' => $lang['Cash_allownace_frequencies'][CASH_ALLOW_YEAR],
	'L_CASH_ALLOWANCE_NEXT' => $lang['Cash_allowance_next'],

	'CURRENCY_ENABLED' => CURRENCY_ENABLED,
	'CURRENCY_IMAGE' => CURRENCY_IMAGE,
	'CURRENCY_PREFIX' => CURRENCY_PREFIX,
	'CURRENCY_VIEWPROFILE' => CURRENCY_VIEWPROFILE,
	'CURRENCY_VIEWTOPIC' => CURRENCY_VIEWTOPIC,
	'CURRENCY_VIEWMEMBERLIST' => CURRENCY_VIEWMEMBERLIST,
	'CURRENCY_INCLUDEQUOTES' => CURRENCY_INCLUDEQUOTES,
	'CURRENCY_EXCHANGEABLE' => CURRENCY_EXCHANGEABLE,
	'CURRENCY_DONATE' => CURRENCY_DONATE,
	'CURRENCY_MODEDIT' => CURRENCY_MODEDIT,
	'CURRENCY_ALLOWNEG' => CURRENCY_ALLOWNEG,
	'CURRENCY_ALLOWANCE' => CURRENCY_ALLOWANCE,

	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset']
	)
);

while ($c_cur = &$cash->currency_next($cm_i))
{
	$enabled_cash_yes = ($c_cur->mask(CURRENCY_ENABLED)) ? 'checked="checked"' : '';
	$enabled_cash_no = (!$c_cur->mask(CURRENCY_ENABLED)) ? 'checked="checked"' : '';

	$image_yes = ($c_cur->mask(CURRENCY_IMAGE)) ? 'checked="checked"' : '';
	$image_no = (!$c_cur->mask(CURRENCY_IMAGE)) ? 'checked="checked"' : '';

	$currency_style_before = ($c_cur->mask(CURRENCY_PREFIX)) ? 'checked="checked"' : '';
	$currency_style_after = (!$c_cur->mask(CURRENCY_PREFIX)) ? 'checked="checked"' : '';

	$include_quotes_yes = ($c_cur->mask(CURRENCY_INCLUDEQUOTES)) ? 'checked="checked"' : '';
	$include_quotes_no = (!$c_cur->mask(CURRENCY_INCLUDEQUOTES)) ? 'checked="checked"' : '';

	$display_in_profile_yes = ($c_cur->mask(CURRENCY_VIEWPROFILE)) ? 'checked="checked"' : '';
	$display_in_profile_no = (!$c_cur->mask(CURRENCY_VIEWPROFILE)) ? 'checked="checked"' : '';

	$allow_exchange_yes = ($c_cur->mask(CURRENCY_EXCHANGEABLE)) ? 'checked="checked"' : '';
	$allow_exchange_no = (!$c_cur->mask(CURRENCY_EXCHANGEABLE)) ? 'checked="checked"' : '';

	$allow_donate_yes = ($c_cur->mask(CURRENCY_DONATE)) ? 'checked="checked"' : '';
	$allow_donate_no = (!$c_cur->mask(CURRENCY_DONATE)) ? 'checked="checked"' : '';

	$allow_mod_edit_yes = ($c_cur->mask(CURRENCY_MODEDIT)) ? 'checked="checked"' : '';
	$allow_mod_edit_no = (!$c_cur->mask(CURRENCY_MODEDIT)) ? 'checked="checked"' : '';

	$allow_negative_yes = ($c_cur->mask(CURRENCY_ALLOWNEG)) ? 'checked="checked"' : '';
	$allow_negative_no = (!$c_cur->mask(CURRENCY_ALLOWNEG)) ? 'checked="checked"' : '';

	$display_in_posts_yes = ($c_cur->mask(CURRENCY_VIEWTOPIC)) ? 'checked="checked"' : '';
	$display_in_posts_no = (!$c_cur->mask(CURRENCY_VIEWTOPIC)) ? 'checked="checked"' : '';

	$display_in_memberlist_yes = ($c_cur->mask(CURRENCY_VIEWMEMBERLIST)) ? 'checked="checked"' : '';
	$display_in_memberlist_no = (!$c_cur->mask(CURRENCY_VIEWMEMBERLIST)) ? 'checked="checked"' : '';

	$allowances_enabled_yes = ((!defined(CASH_ALLOWANCES_ENABLED)) ? 'disabled="disabled" ' : '') . (($c_cur->data('cash_allowance')) ? 'checked="checked"' : '');
	$allowances_enabled_no = (!$c_cur->data('cash_allowance')) ? 'checked="checked"' : '';

	$allowances_freq_day   = ($c_cur->data('cash_allowancetime') == CASH_ALLOW_DAY  ) ? 'checked="checked"' : '';
	$allowances_freq_week  = ($c_cur->data('cash_allowancetime') == CASH_ALLOW_WEEK ) ? 'checked="checked"' : '';
	$allowances_freq_month = ($c_cur->data('cash_allowancetime') == CASH_ALLOW_MONTH) ? 'checked="checked"' : '';
	$allowances_freq_year  = ($c_cur->data('cash_allowancetime') == CASH_ALLOW_YEAR ) ? 'checked="checked"' : '';

	$allowances_next = $c_cur->data('cash_allowancenext');

	$template->assign_block_vars('cashrow', array(
		'CASH_INDEX' => $c_cur->id(),
		'ENABLED_CASH_YES' => $enabled_cash_yes,
		'ENABLED_CASH_NO' => $enabled_cash_no,
		'CURRENCY' => $c_cur->name(),
		'IMAGE_YES' => $image_yes,
		'IMAGE_NO' => $image_no,
		'IMAGEURL' => $c_cur->data('cash_imageurl'),
		'CURRENCY_STYLE_BEFORE' => $currency_style_before,
		'CURRENCY_STYLE_AFTER' => $currency_style_after,
		'AMOUNT_PER_POST' => $c_cur->perpost(),
		'AMOUNT_POST_BONUS' => $c_cur->postbonus(),
		'AMOUNT_PER_REPLY' => $c_cur->perreply(),
		'AMOUNT_PER_CHAR' => $c_cur->perchar(),
		'AMOUNT_PER_THANKS' => $c_cur->perthanks(),
		'MAXEARN' => $c_cur->maxearn(),
		'AMOUNT_PER_PM' => $c_cur->perpm(),
		'INCLUDE_QUOTES_YES' => $include_quotes_yes,
		'INCLUDE_QUOTES_NO' => $include_quotes_no,
		'DISPLAY_IN_PROFILE_YES' => $display_in_profile_yes,
		'DISPLAY_IN_PROFILE_NO' => $display_in_profile_no,
		'ALLOW_EXCHANGE_YES' => $allow_exchange_yes,
		'ALLOW_EXCHANGE_NO' => $allow_exchange_no,
		'ALLOW_DONATE_YES' => $allow_donate_yes,
		'ALLOW_DONATE_NO' => $allow_donate_no,
		'ALLOW_MOD_EDIT_YES' => $allow_mod_edit_yes,
		'ALLOW_MOD_EDIT_NO' => $allow_mod_edit_no,
		'ALLOW_NEGATIVE_YES' => $allow_negative_yes,
		'ALLOW_NEGATIVE_NO' => $allow_negative_no,
		'DISPLAY_IN_POSTS_YES' => $display_in_posts_yes,
		'DISPLAY_IN_POSTS_NO' => $display_in_posts_no,
		'DISPLAY_IN_MEMBERLIST_YES' => $display_in_memberlist_yes,
		'DISPLAY_IN_MEMBERLIST_NO' => $display_in_memberlist_no,
		'ALLOWANCES_ENABLED_YES' => $allowances_enabled_yes,
		'ALLOWANCES_ENABLED_NO' => $allowances_enabled_no,
		'ALLOWANCE_AMOUNT' => $c_cur->allowanceamount(),
		'ALLOWANCES_FREQ_DAY' => $allowances_freq_day,
		'ALLOWANCES_FREQ_WEEK' => $allowances_freq_week,
		'ALLOWANCES_FREQ_MONTH' => $allowances_freq_month,
		'ALLOWANCES_FREQ_YEAR' => $allowances_freq_year,
		'ALLOWANCE_NEXT' => $allowances_next
		)
	);
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>