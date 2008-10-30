<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/* MG Lang DB - BEGIN */
/* MG Lang DB - END */

define('IN_ICYPHOENIX', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['125_Language'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_lang_user_created.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_extend_lang.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

@set_time_limit(0);
$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);

$lang_management = new lang_management();

$value_maxlength = 250;

// Remove the ADMIN / NORMAL options => force $_POST options
$_POST['search_admin'] = 2;
$_POST['new_level'] = 'normal';


// get languages installed
$countries = $lang_management->get_countries();

// get packs installed
$packs = $lang_management->get_packs();

// get entries (all lang keys)
$entries = $lang_management->get_entries();

// get parameters
$mode = '';
if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = isset($_POST['mode']) ? $_POST['mode'] : $_GET['mode'];
}
if (!in_array($mode, array('pack', 'key')))
{
	$mode = '';
}

// level
$level = 'normal';
if (isset($_POST['level']) || isset($_GET['level']))
{
	$level = isset($_POST['level']) ? urldecode($_POST['level']) : urldecode($_GET['level']);
}
if (!in_array($level, array('normal', 'admin')))
{
	$level = 'normal';
}

// pack file
$pack_file = '';
if (isset($_POST['pack_file']) || isset($_GET['pack']))
{
	$pack_file = isset($_POST['pack_file']) ? urldecode($_POST['pack_file']) : urldecode($_GET['pack']);
}
if (!isset($packs[$pack_file]))
{
	$pack_file = '';
	$mode = '';
}

// keys
$key_main = '';
if (isset($_POST['key_main']) || isset($_GET['key']))
{
	$key_main = isset($_POST['key_main']) ? urldecode($_POST['key_main']) : urldecode($_GET['key']);
}
$key_sub = '';
if (isset($_POST['key_sub']) || isset($_GET['sub']))
{
	$key_sub = isset($_POST['key_sub']) ? urldecode($_POST['key_sub']) : urldecode($_GET['sub']);
}
if (empty($key_main))
{
	$key_sub = '';
}
if (!isset($entries['admin'][$key_main][$key_sub]))
{
	$key_main = '';
	$key_sub = '';
}

// buttons
$submit = isset($_POST['submit']);
$delete = isset($_POST['delete']);
$cancel = isset($_POST['cancel']);
$add = isset($_POST['add']);
if ($add || $delete)
{
	$mode = 'key';
}
if (($mode == 'key') && ($pack_file == ''))
{
	$mode = '';
}

if (($mode == '') && $submit)
{
	$mode = 'search';
}

// key modification
if ($mode == 'key')
{
	if ($delete)
	{
		$new_entries = array();
		@reset($entries['admin']);
		while (list($new_main, $subs) = @each($entries['admin']))
		{
			@reset($subs);
			while (list($new_sub, $admin) = @each($subs))
			{
				if (($new_main != $key_main) || ($new_sub != $key_sub))
				{
					$new_entries['admin'][$new_main][$new_sub] = $entries['admin'][$new_main][$new_sub];
					$new_entries['pack'][$new_main][$new_sub] = $entries['pack'][$new_main][$new_sub];
					$new_entries['value'][$new_main][$new_sub] = $entries['value'][$new_main][$new_sub];
				}
			}
		}

		// write the result
		$lang_management->write($new_entries);

		// send message
		$pack_url = append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&amp;pack=' . urlencode($pack_file) . '&amp;level=' . urlencode(($level == 'normal') ? 'normal' : 'admin'));
		message_die(GENERAL_MESSAGE, sprintf($lang['Lang_extend_delete_done'], '<a href="' . $pack_url . '">', '</a>'));

		// back to the list
		$mode = 'pack';
		$delete = false;
	}
	elseif ($cancel)
	{
		// back to list
		$mode = 'pack';
		$cancel = false;
	}
	elseif ($submit)
	{
		// get formular
		$new_main = $_POST['new_main'];
		$new_sub = $_POST['new_sub'];
		$new_level = $_POST['new_level'];
		$new_values = $_POST['new_values'];
		$new_pack = $_POST['new_pack'];

		// force
		if (!in_array($new_level, array('normal', 'admin')))
		{
			$new_level = 'normal';
		}

		// check values
		$error = false;
		$error_msg = false;
		$dft_country = 'lang_' . $board_config['default_language'];
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			if (empty($new_values[$country_dir]))
			{
				$new_values[$country_dir] = $new_values[$dft_country];
			}
			if (empty($new_values[$country_dir]) && ($dft_country != 'lang_english'))
			{
				$new_values[$country_dir] = $new_values['lang_english'];
			}
			if (empty($new_values[$country_dir]) && !$error)
			{
				$error = true;
				$error_msg .= (empty($error_msg) ? '' : '<br /><br />') . $lang['Lang_extend_missing_value'];
			}
		}

		// empty key
		if (empty($new_main))
		{
			$error = true;
			$error_msg .= (empty($error_msg) ? '' : '<br /><br />') . $lang['Lang_extend_key_missing'];
		}

		// we changed the key or create a new one
		if (!empty($new_main) && (($new_main != $key_main) || ($new_sub != $key_sub)))
		{
			// does the new key already exists ?
			if (isset($entries['admin'][$new_key][$new_sub]))
			{
				$error = true;
				$error_msg .= (empty($error_msg) ? '' : '<br /><br />') . sprintf($lang['Lang_extend_duplicate_entry'], $lang_management->get_lang($entries['pack'][$new_key][$new_sub]));
			}
		}

		// error
		if ($error)
		{
			message_die(GENERAL_MESSAGE, '<br />' . $error_msg . '<br /><br />');
			exit;
		}

		// perform the update
		$entries['pack'][$new_main][$new_sub] = $new_pack;
		$entries['admin'][$new_main][$new_sub] = ($new_level == 'admin');
		@reset($new_values);
		while (list($new_country, $new_value) = @each($new_values))
		{
			if (!empty($new_value))
			{
				$entries['value'][$new_main][$new_sub][$new_country] = $new_value;
			}
		}

		// write the result
		$lang_management->write($entries);

		// send message
		$key_url = append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=key&amp;pack=' . urlencode($new_pack) . '&amp;key=' . urlencode($new_main) . '&amp;sub=' . urlencode($new_sub) . '&amp;level=' . urlencode(($new_level == 'normal') ? 'normal' : 'admin'));
		$pack_url = append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&amp;pack=' . urlencode($new_pack) . '&amp;level=' . urlencode(($new_level == 'normal') ? 'normal' : 'admin'));
		message_die(GENERAL_MESSAGE, sprintf($lang['Lang_extend_update_done'], '<a href="' . $key_url . '">','</a>', '<a href="' . $pack_url . '">', '</a>'));
	}
	else
	{
		// template
		$template->set_filenames(array('body' => ADM_TPL . 'lang_user_created_key_body.tpl'));

		// header
		$template->assign_vars(array(
			'L_TITLE'					=> $lang['Lang_extend'],
			'L_TITLE_EXPLAIN'	=> $lang['Lang_extend_explain'],
			'L_KEY'						=> $lang['Lang_extend_entry'],
			'L_LANGUAGES'			=> $lang['Languages'],

			'L_SUBMIT'				=> $lang['Submit'],
			'L_DELETE'				=> $lang['Delete'],
			'L_CANCEL'				=> $lang['Cancel'],
			)
		);

		// pack list
		$s_packs = '';
		@reset($packs);
		while (list($file, $name) = @each($packs))
		{
			$selected = ($file == $pack_file) ? ' selected="selected"' : '';
			/* MG Lang DB - BEGIN */
			$s_packs .= '<option value="' . $file . '"' . $selected . '>' . $name . '</option>';
			/* MG Lang DB - END */
		}
		if (!empty($s_packs))
		{
			$s_packs = sprintf('<select name="new_pack">%s</select>', $s_packs);
		}

		// vars
		$template->assign_vars(array(
			'L_KEY_MAIN'					=> $lang['Lang_extend_key_main'],
			'L_KEY_MAIN_EXPLAIN'	=> $lang['Lang_extend_key_main_explain'],
			'KEY_MAIN'						=> $key_main,
			'L_KEY_SUB'						=> $lang['Lang_extend_key_sub'],
			'L_KEY_SUB_EXPLAIN'		=> $lang['Lang_extend_key_sub_explain'],
			'KEY_SUB'							=> $key_sub,

			'L_PACK'							=> $lang['Lang_extend_pack'],
			'L_PACK_EXPLAIN'			=> $lang['Lang_extend_pack_explain'],
			'S_PACKS'							=> $s_packs,

			'L_LEVEL'							=> $lang['Lang_extend_level'],
			'L_LEVEL_EXPLAIN'			=> $lang['Lang_extend_level_explain'],
			'LEVEL_NORMAL'				=> 'normal',
			'L_EDIT'							=> $lang['Lang_extend_level_edit'],
			'S_LEVEL_NORMAL'			=> ($level == 'normal') ? 'checked="checked"' : '',
			'L_LEVEL_NORMAL'			=> $lang['Lang_extend_level_normal'],
			'LEVEL_ADMIN'					=> 'admin',
			'S_LEVEL_ADMIN'				=> ($level != 'normal') ? 'checked="checked"' : '',
			'L_LEVEL_ADMIN'				=> $lang['Lang_extend_level_admin'],

			'L_PACKS'							=> $lang['Lang_extend_pack'],
			'L_PACKS'							=> $lang['Lang_extend_pack_explain'],
			)
		);

		// get all language values
		@reset($countries);
		while (list($country_dir, $country_name) = each($countries))
		{
			$value = $entries['value'][$key_main][$key_sub][$country_dir];
			$status = $entries['status'][$key_main][$key_sub][$country_dir];
			$l_status = '';
			switch ($status)
			{
				case 1:
					$l_status = $lang['Lang_extend_modified'];
					break;
				case 2:
					$l_status = $lang['Lang_extend_added'];
					break;
				default:
					$l_status = '';
					break;
			}
			$template->assign_block_vars('row', array(
				'L_COUNTRY'		=> $country_name,
				'COUNTRY'			=> $country_dir,
				'VALUE'				=> htmlspecialchars($value),
				'L_STATUS'		=> $l_status,
				)
			);
		}

		// footer
		$s_hidden_fields = '';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="pack_file" value="' . urlencode($pack_file) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="key_main" value="' . urlencode($key_main) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="key_sub" value="' . urlencode($key_sub) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="level" value="' . urlencode($level) . '" />';
		$template->assign_vars(array(
			'S_ACTION'				=> append_sid('admin_lang_user_created.' . PHP_EXT),
			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			)
		);
	}
}

// pack
if ($mode == 'pack')
{
	if ($cancel)
	{
		// back to the main list
		$mode = '';
		$cancel = false;
	}
	else
	{
		// template
		$template->set_filenames(array('body' => ADM_TPL . 'lang_user_created_pack_body.tpl'));

		// header
		$template->assign_vars(array(
			'L_TITLE'					=> $lang['Lang_extend'],
			'L_TITLE_EXPLAIN'	=> $lang['Lang_extend_explain'],
			'LEVEL'						=> ($level == 'admin') ? $lang['Lang_extend_level_admin'] : $lang['Lang_extend_level_normal'],

			'L_PACK'					=> $lang['Lang_extend_pack'],
			'U_PACK'					=> append_sid('admin_lang_user_created.' . PHP_EXT),
			/* MG Lang DB - BEGIN */
			//'PACK'					=> $lang_management->get_lang('Lang_extend_' . $packs[$pack_file]),
			'PACK'						=> $packs[$pack_file],
			/* MG Lang DB - END */

			'L_EDIT'					=> $lang['Lang_extend_level_edit'],
			'L_LEVEL_NEXT'		=> ($level == 'admin') ? $lang['Lang_extend_level_normal'] : $lang['Lang_extend_level_admin'],
			'U_LEVEL_NEXT'		=> append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&amp;pack=' . urlencode($pack_file) . '&amp;level=' . urlencode(($level == 'admin') ? 'normal' : 'admin')),

			'L_KEYS'					=> $lang['Lang_extend_entries'],
			'L_NONE'					=> $lang['None'],
			'L_ADD'						=> $lang['Lang_extend_add_entry'],
			'L_CANCEL'				=> $lang['Cancel'],
			)
		);

		// dump
		$color = false;
		$i = 0;
		@reset($entries['pack']);
		while (list($key_main, $data) = @each($entries['pack']))
		{
			@reset($data);
			while (list($key_sub, $pack) = @each($data))
			{
				if (($pack == $pack_file) && (($entries['admin'][$key_main][$key_sub] && ($level == 'admin')) || (!$entries['admin'][$key_main][$key_sub] && ($level == 'normal'))))
				{
					$value = trim((empty($key_sub) ? $lang[$key_main] : $lang[$key_main][$key_sub]));
					if (strlen($value) > $value_maxlength)
					{
						$value = substr($value, 0, $value_maxlength-3) . '...';
					}
					$value = htmlspecialchars($value);

					// get the status
					$modified_added = false;
					if ($pack != 'custom')
					{
						$found = false;
						@reset($entries['status'][$key_main][$key_sub]);
						while (list($country_dir, $status) = @each($entries['status'][$key_main][$key_sub]))
						{
							$found = ($status > 0);
							if ($found)
							{
								$modified_added = true;
								break;
							}
						}
					}

					$i++;
					$color = !$color;
					$template->assign_block_vars('row', array(
						'CLASS'			=> $color ? 'row1' : 'row2',
						'KEY_MAIN'	=> "['" . $key_main . "']",
						'KEY_SUB'		=> empty($key_sub) ? '' : "['" . $key_sub . "']",
						'U_KEY'			=> append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=key&amp;pack=' . urlencode($pack_file) . '&amp;level=' . urlencode($level) . '&amp;key=' . urlencode($key_main) . '&amp;sub=' . urlencode($key_sub)),
						'VALUE'			=> $value,
						'STATUS'		=> $modified_added ? $lang['Lang_extend_added_modified'] : '',
						)
					);
				}
			}
		}
		if ($i == 0)
		{
			$template->assign_block_vars('none', array());
		}

		// footer
		$s_hidden_fields = '';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="pack_file" value="' . urlencode($pack_file) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="level" value="' . urlencode($level) . '" />';
		$template->assign_vars(array(
			'S_ACTION'				=> append_sid('admin_lang_user_created.' . PHP_EXT),
			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			)
		);
	}
}

// search
if ($mode == 'search')
{
	if ($cancel)
	{
		$cancel = '';
		$mode = '';
	}
	else
	{
		// formular
		$search_words = str_replace("\'", "'", str_replace("''", "'", trim($_POST['search_words'])));
		$search_logic = intval($_POST['search_logic']);
		$search_in = intval($_POST['search_in']);
		$search_country = $_POST['search_language'];
		$search_admin = intval($_POST['search_admin']);

		// results
		$results = array();

		// get all the words to search
		if (empty($search_words))
		{
			$main_url = append_sid('admin_lang_user_created.' . PHP_EXT);
			message_die(GENERAL_MESSAGE, sprintf($lang['Lang_extend_search_no_words'], '<a href="' . $main_url . '">', '</a>'));
			exit;
		}
		$w_words = explode(' ', strtolower(str_replace('_', ' ', str_replace("\'", "'", str_replace("''", "'", $search_words)))));
		for ($i = 0; $i < count($w_words); $i++)
		{
			if (!empty($w_words[$i]))
			{
				$words[] = $w_words[$i];
			}
		}

		// check each entry
		@reset($entries['pack']);
		while (list($key_main, $subs) = @each($entries['pack']))
		{
			@reset($subs);
			while (list($key_sub, $pack_dir) = @each($subs))
			{
				$admin = $entries['admin'][$key_main][$key_sub];
				if (($admin && ($search_admin != 1)) || (!$admin && ($search_admin != 0)))
				{
					$w_key = strtolower(str_replace('_', ' ', str_replace("\'", "'", str_replace("''", "'", $key_main))));
					$w_key .= ' ' . strtolower(str_replace('_', ' ', str_replace("\'", "'", str_replace("''", "'", $key_sub))));
					$w_words = explode(' ', $w_key);

					$words_key = array();
					for ($i = 0; $i < count($w_words); $i++)
					{
						if (!empty($w_words[$i]))
						{
							$words_key[] = $w_words[$i];
						}
					}

					$words_val = array();
					@reset($countries);
					while (list($country, $country_name) = @each($countries))
					{
						if (empty($search_country) || ($country == $search_country))
						{
							$w_words_val = explode(' ', strtolower(str_replace("\'", "'", str_replace("''", "'", $entries['value'][$key_main][$key_sub][$country]))));
							for ($i = 0; $i < count($w_words_val); $i++)
							{
								if (!empty($w_words_val[$i]))
								{
									if (empty($words_val) || !in_array($w_words_val[$i], $words_val))
									{
										$words_val[] = $w_words_val[$i];
									}
								}
							}
						}
					}

					// is this key convenient ?
					$ok = ($search_logic == 0);
					for ($i = 0; $i < count($words); $i++)
					{
						$found = ((($search_in != 1) && in_array($words[$i], $words_key)) || (($search_in != 0) && in_array($words[$i], $words_val)));
						if (($search_logic == 1) && $found)
						{
							$ok = true;
							break;
						}
						if (($search_logic == 0) && !$found)
						{
							$ok = false;
							break;
						}
					}
					if ($ok)
					{
						$results[] = array('main' => $key_main, 'sub' => $key_sub);
					}
				}
			}
		}

		// template
		$template->set_filenames(array('body' => ADM_TPL . 'lang_user_created_search_body.tpl'));

		// header
		$template->assign_vars(array(
			'L_TITLE'						=> $lang['Lang_extend'],
			'L_TITLE_EXPLAIN'		=> $lang['Lang_extend_explain'],
			'L_SEARCH_RESULTS'	=> $lang['Lang_extend_search_results'],
			'L_PACK'						=> $lang['Lang_extend_pack'],
			'L_KEY'							=> $lang['Lang_extend_entries'],
			'L_VALUE'						=> $lang['Lang_extend_value'],
			'L_LEVEL'						=> $lang['Lang_extend_level_leg'],
			'L_NONE'						=> $lang['None'],
			'L_CANCEL'					=> $lang['Cancel'],
			)
		);

		$color = false;
		for ($i = 0; $i < count($results); $i++)
		{
			// get data
			$key_main	= $results[$i]['main'];
			$key_sub	= $results[$i]['sub'];
			$pack_file	= $entries['pack'][$key_main][$key_sub];
			$pack_name	= $packs[$pack_file];
			$admin		= $entries['admin'][$key_main][$key_sub];

			// value
			$value = trim((empty($key_sub) ? $lang[$key_main] : $lang[$key_main][$key_sub]));
			if (strlen($value) > $value_maxlength)
			{
				$value = substr($value, 0, $value_maxlength-3) . '...';
			}
			$value = htmlspecialchars($value);

			// status
			$modified_added = false;
			if ($pack_file != 'custom')
			{
				$found = false;
				@reset($entries['status'][$key_main][$key_sub]);
				while (list($country_dir, $status) = @each($entries['status'][$key_main][$key_sub]))
				{
					$found = ($status > 0);
					if ($found)
					{
						$modified_added = true;
						break;
					}
				}
			}

			$color = !$color;
			$template->assign_block_vars('row', array(
				'CLASS'			=> $color ? 'row1' : 'row2',
				/* MG Lang DB - BEGIN */
				//'PACK'		=> $lang_management->get_lang('Lang_extend_' . $pack_name),
				'PACK'			=> $pack_name,
				/* MG Lang DB - END */
				'KEY_MAIN'	=> "['" . $key_main . "']",
				'KEY_SUB'		=> empty($key_sub) ? '' : "['" . $key_sub . "']",
				'VALUE'			=> $value,
				'L_EDIT'		=> $lang['Lang_extend_level_edit'],
				'LEVEL'			=> $admin ? $lang['Lang_extend_level_admin'] : $lang['Lang_extend_level_normal'],
				'STATUS'		=> $modified_added ? $lang['Lang_extend_added_modified'] : '',

				'U_PACK'		=> append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&pack=' . urlencode($pack_file) . '&level=' . urlencode($admin ? 'admin' : 'normal')),
				'U_KEY'			=> append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=key&pack=' . urlencode($pack_file) . '&level=' . urlencode($admin ? 'admin' : 'normal') . '&key=' . urlencode($key_main). '&sub=' . urlencode($key_sub)),
				)
			);
		}

		if (count($results) == 0)
		{
			$template->assign_block_vars('none', array());
		}

		// footer
		$s_hidden_fields = '';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_words" value="' . urlencode(str_replace("'", "\'", $search_words)) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_logic" value="' . $search_logic . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_in" value="' . $search_in . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_language" value="' . urlencode($search_language) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_admin" value="' . $search_admin . '" />';

		$template->assign_vars(array(
			'S_ACTION'			=> append_sid('admin_lang_user_created.' . PHP_EXT),
			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			)
		);
	}
}

// default entry
if ($mode == '')
{
	// search
	$search_words = isset($_POST['search_words']) ? str_replace("\'", "'", urldecode($_POST['search_words'])) : '';
	$search_logic = isset($_POST['search_logic']) ? intval($_POST['search_logic']) : 0;
	$search_in = isset($_POST['search_in']) ? intval($_POST['search_in']) : 2;
	$search_country = isset($_POST['search_language']) ? str_replace("\'", "'", urldecode($_POST['search_language'])) : 'lang_' . $board_config['default_language'];
	$search_admin = isset($_POST['search_admin']) ? intval($_POST['search_admin']) : 2;

	// template
	$template->set_filenames(array('body' => ADM_TPL . 'lang_user_created_body.tpl'));

	// header
	$template->assign_vars(array(
		'L_TITLE'					=> $lang['Lang_extend'],
		'L_TITLE_EXPLAIN'	=> $lang['Lang_extend_explain'],
		'L_PACK'					=> $lang['Lang_extend_pack'],
		'L_EDIT'					=> $lang['Lang_extend_level_edit'],
		'L_ADMIN'					=> $lang['Lang_extend_level_admin'],
		'L_NORMAL'				=> $lang['Lang_extend_level_normal'],

		'L_NONE'					=> $lang['None'],
		'L_SUBMIT'				=> $lang['Submit'],
		)
	);

	// display packs
	$i = 0;
	$color = false;
	@reset($packs);
	while (list($pack_file, $pack_name) = @each($packs))
	{
		$i++;
		$color = !$color;
		/* MG Lang DB - BEGIN */
		// ALL LANG EXTEND
		//if(preg_match("/^lang_extend.*?\." . PHP_EXT . "$/", urlencode($pack_file)))
		// LANG USER CREATED AND LANG MAIN SETTINGS
		//if((preg_match("/^lang_user_created.*?\." . PHP_EXT . "$/", urlencode($pack_file))) || (preg_match("/^lang_main_settings.*?\." . PHP_EXT . "$/", urlencode($pack_file))))
		// ONLY LANG USER CREATED
		if(preg_match("/^lang_user_created.*?\." . PHP_EXT . "$/", urlencode($pack_file)))
		{
			$l_normal = $lang['Lang_extend_level_normal'];
			$u_normal = append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&amp;pack=' . urlencode($pack_file) . '&amp;level=normal');
			$l_admin = $lang['Lang_extend_level_admin'];
			$u_admin = append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&amp;pack=' . urlencode($pack_file) . '&amp;level=admin');
		}
		else
		{
			$l_normal = $lang['Lang_extend_level_edit'];
			$u_normal = append_sid('admin_lang_user_created.' . PHP_EXT . '?mode=pack&amp;pack=' . urlencode($pack_file) . '&amp;level=normal');
			$l_admin = '&bull;';
			$u_admin = '#';
		}
		/* MG Lang DB - END */

		$template->assign_block_vars('row', array(
			'COLOR'					=> $color ? 'row1' : 'row2',
			/* MG Lang DB - BEGIN */
			//'PACK'				=> $lang_management->get_lang('Lang_extend_' . $pack_name),
			'PACK'					=> $pack_name,
			/* MG Lang DB - END */
			'L_EDIT'	=> $lang['Edit'],
			'L_PACK_ADMIN'	=> $l_admin,
			'U_PACK_ADMIN'	=> $u_admin,
			'L_PACK_NORMAL'	=> $l_normal,
			'U_PACK_NORMAL'	=> $u_normal,
			)
		);
	}
	if ($i == 0)
	{
		$template->assign_block_vars('none', array());
	}

	// search form
	$template->assign_vars(array(
		'L_SEARCH'								=> $lang['Lang_extend_search'],
		'L_SEARCH_WORDS'					=> $lang['Lang_extend_search_words'],
		'L_SEARCH_WORDS_EXPLAIN'	=> $lang['Lang_extend_search_words_explain'],
		'L_SEARCH_ALL'						=> $lang['Lang_extend_search_all'],
		'L_SEARCH_ONE'						=> $lang['Lang_extend_search_one'],
		'L_SEARCH_IN'							=> $lang['Lang_extend_search_in'],
		'L_SEARCH_IN_EXPLAIN'			=> $lang['Lang_extend_search_in_explain'],
		'L_SEARCH_IN_KEY'					=> $lang['Lang_extend_search_in_key'],
		'L_SEARCH_IN_VALUE'				=> $lang['Lang_extend_search_in_value'],
		'L_SEARCH_IN_BOTH'				=> $lang['Lang_extend_search_in_both'],
		'L_EDIT'									=> $lang['Lang_extend_level_edit'],
		'L_SEARCH_LEVEL_ADMIN'		=> $lang['Lang_extend_level_admin'],
		'L_SEARCH_LEVEL_NORMAL'		=> $lang['Lang_extend_level_normal'],
		'L_SEARCH_LEVEL_BOTH'			=> $lang['Lang_extend_search_in_both'],
		)
	);

	// list of lang installed
	$selected = empty($search_country) ? ' selected="selected"' : '';
	$s_languages = '<option value=""' . $selected . '>' . $lang['Lang_extend_search_all_lang'] . '</option>';
	@reset($countries);
	while (list($country_dir, $country_name) = @each($countries))
	{
		$selected = ($country_dir == $search_country) ? ' selected="selected"' : '';
		$s_languages .= '<option value="' . $country_dir . '"' . $selected . '>' . $country_name . '</option>';
	}
	$s_languages = sprintf('<select name="search_language">%s</select>', $s_languages);

	$template->assign_vars(array(
		'SEARCH_WORDS'			=> $search_words,
		'SEARCH_ALL'			=> ($search_logic == 0) ? 'checked="checked"' : '',
		'SEARCH_ONE'			=> ($search_logic == 1) ? 'checked="checked"' : '',
		'SEARCH_IN_KEY'			=> ($search_in == 0) ? 'checked="checked"' : '',
		'SEARCH_IN_VALUE'		=> ($search_in == 1) ? 'checked="checked"' : '',
		'SEARCH_IN_BOTH'		=> ($search_in == 2) ? 'checked="checked"' : '',
		'SEARCH_LEVEL_ADMIN'	=> ($search_in == 0) ? 'checked="checked"' : '',
		'SEARCH_LEVEL_NORMAL'	=> ($search_in == 1) ? 'checked="checked"' : '',
		'SEARCH_LEVEL_BOTH'		=> ($search_in == 2) ? 'checked="checked"' : '',
		'S_LANGUAGES'			=> $s_languages,
		)
	);

	// footer
	$s_hidden_fields = '';
	$template->assign_vars(array(
		'S_ACTION'			=> append_sid('admin_lang_user_created.' . PHP_EXT),
		'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
		)
	);
}

// dump
$template->pparse('body');
include('./page_footer_admin.' . PHP_EXT);

?>