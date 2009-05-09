<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* This class is used to generate forms
*/
class class_form
{

	/*
	* create_input
	*/
	function create_input($name, $properties)
	{
		global $board_config;

		$input = '';
		$default = !empty($properties['default']) ? (is_array($properties['default']) ? array_map('htmlspecialchars', array_map('ip_stripslashes', $properties['default'])) : htmlspecialchars(ip_stripslashes($properties['default']))) : '';

		switch ($properties['type'])
		{

			case 'HIDDEN':
				$input = '<input type="hidden" name="' . $name . '" value="' . $default . '" />';
				break;

			case 'LIST_RADIO_BR':
			case 'LIST_RADIO':
				@reset($properties['values']);
				while (list($key, $val) = @each($properties['values']))
				{
					$selected = ($default == $val) ? ' checked="checked"' : '';
					$l_key = $this->get_lang($key);
					$input .= '<input type="radio" name="' . $name . '" value="' . $val . '"' . $selected . ' />&nbsp;' . $l_key;
					$input .= (($properties['type'] == 'LIST_RADIO_BR') ? '<br />' : '&nbsp;&nbsp;');
				}
				break;

			case 'LIST_CHECKBOX':
				@reset($properties['values']);
				while (list($key, $val) = @each($properties['values']))
				{
					$selected = (!empty($default) && in_array(trim($val), $default)) ? ' checked="checked"' : '';
					$l_key = $this->get_lang($key);
					$input .= '<input type="checkbox" name="' . $name . '[]" value="' . $val . '"' . $selected . ' />&nbsp;' . $l_key. '<br />';
				}
				break;

			case 'LIST_DROP':
				@reset($properties['values']);
				while (list($key, $val) = @each($properties['values']))
				{
					$selected = ($default == $val) ? ' selected="selected"' : '';
					$l_key = $this->get_lang($key);
					$input .= '<option value="' . $val . '"' . $selected . '>' . $l_key . '</option>';
				}
				$input = '<select name="' . $name . '">' . $input . '</select>';
				break;

			case 'DATE_INPUT':
				$input_time = (!empty($properties['default']) ? $properties['default'] : $current_time);
				$tf = $this->explode_unix_time($input_time);
				$input = $this->date_input($name, $tf['year'], $tf['month'], $tf['day']);
				break;

			case 'TIME_INPUT':
				$input_time = (!empty($properties['default']) ? $properties['default'] : $current_time);
				$tf = $this->explode_unix_time($input_time);
				$input = $this->time_input($name, $tf['hour'], $tf['minute'], $tf['second']);
				break;

			case 'DATE_TIME_INPUT':
				$input_time = (!empty($properties['default']) ? $properties['default'] : $current_time);
				$tf = $this->explode_unix_time($input_time);
				$input = $this->date_input($name, $tf['year'], $tf['month'], $tf['day']);
				$input .= $this->time_input($name, $tf['hour'], $tf['minute'], $tf['second']);
				break;

			case 'TINYINT':
				$input = '<input type="text" name="' . $name . '" maxlength="3" size="3" class="post" value="' . $default . '" />';
				break;

			case 'SMALLINT':
				$input = '<input type="text" name="' . $name . '" maxlength="5" size="5" class="post" value="' . $default . '" />';
				break;

			case 'MEDIUMINT':
				$input = '<input type="text" name="' . $name . '" maxlength="9" size="9" class="post" value="' . $default . '" />';
				break;

			case 'INT':
				$input = '<input type="text" name="' . $name . '" maxlength="13" size="13" class="post" value="' . $default . '" />';
				break;

			case 'VARCHAR':
			case 'HTMLVARCHAR':
				$input = '<input type="text" name="' . $name . '" maxlength="255" size="45" class="post" value="' . $default . '" />';
				break;

			case 'TEXT':
			case 'HTMLTEXT':
				$input = '<div class="message-box"><textarea rows="10" cols="35" name="' . $name . '">' . $default . '</textarea></div>';
				break;

			default:
				if (!empty($properties['get_func']) && function_exists($properties['get_func']))
				{
					$input = $properties['get_func']($name, $default);
				}
				break;

		}

		/*
		// dump to template
		$template->assign_block_vars('field', array(
			'L_NAME' => $this->get_lang($properties['lang_key']),
			'L_EXPLAIN' => !empty($properties['explain']) ? '<br />' . $this->get_lang($properties['explain']) : '',
			'INPUT' => $input,
			)
		);
		*/

		/*
		// to be used within a cycle
		$template->assign_block_vars('field', array(
			'L_NAME' => $mg_class_form->get_lang($v['lang_key']),
			'L_EXPLAIN' => !empty($v['explain']) ? '<br />' . $mg_class_form->get_lang($v['explain']) : '',
			'INPUT' => $mg_class_form->create_input($k, $v),
			)
		);
		*/

		return $input;
	}

	/*
	* Set the default value for each var type
	*/
	function set_type_default_value($var)
	{
		$var_type = gettype($var);
		switch ($var_type)
		{
			case 'boolean':
				return false;

			case 'integer':
				return 0;

			case 'double':
				return 0;

			case 'array':
				return array();

			case 'object':
				return null;

			case 'resource':
				return null;

			case 'NULL':
				return null;

			case 'unknown type':
				return null;

			default:
				return null;

		}
	}

	/*
	* Build inputs_array
	*/
	function create_inputs_array($mode, $action)
	{
		global $table_fields, $inputs_array, $current_time, $item_id;

		foreach ($table_fields as $k => $v)
		{
			if (($v['type'] != 'HIDDEN') && isset($v['is_time']) && $v['is_time'])
			{
				$date_time_array = array('year', 'month', 'day', 'hour', 'minute', 'second');
				$input_time = (!empty($v['default']) ? $v['default'] : $current_time);
				$tf = $this->explode_unix_time($input_time);
				$var_fragment = array();
				foreach ($date_time_array as $time_fragment)
				{
					$var_name = $k . '_' . $time_fragment;
					$var_fragment[$time_fragment] = request_var($var_name, $tf[$time_fragment]);
				}
				$inputs_array[$k] = $this->implode_unix_time($var_fragment['year'], $var_fragment['month'], $var_fragment['day'], $var_fragment['hour'], $var_fragment['minute'], $var_fragment['second']);
			}
			else
			{
				$multibyte = (in_array($v['type'], array('HIDDEN', 'VARCHAR', 'HTMLVARCHAR', 'TEXT', 'HTMLTEXT'))) ? true : false;
				$inputs_array[$k] = request_var($k, $v['default'], $multibyte);
				$inputs_array[$k] = is_string($inputs_array[$k]) ? ip_addslashes($inputs_array[$k]) : $inputs_array[$k];
			}

			// We want to force each value the user isn't allowed to add/edit to the default value
			if (($k != $item_id) && ((($action == 'add') && !check_auth_level($v['input_level'])) || (($action == 'edit') && !check_auth_level($v['edit_level']))))
			{
				$inputs_array[$k] = $v['default'];
			}
		}
	}

	/*
	* Build input form
	*/
	function create_input_form($mode, $action, $items_row)
	{
		global $board_config, $template, $theme, $lang, $s_hidden_fields;
		global $table_fields, $inputs_array, $current_time, $s_bbcb_global;

		foreach ($table_fields as $k => $v)
		{
			$inputs_array[$k] = (isset($items_row[$k]) ? $items_row[$k] : $v['default']);
			$table_fields[$k]['default'] = $inputs_array[$k];
			$v['default'] = $inputs_array[$k];

			if (($v['type'] == 'HIDDEN') || (($action == 'add') && !check_auth_level($v['input_level'])) || (($action == 'edit') && !check_auth_level($v['edit_level'])))
			{
				$v['type'] = 'HIDDEN';
				$s_hidden_fields .= $this->create_input($k, $v);
			}
			else
			{
				$class = (empty($class) || ($class == $theme['td_class2'])) ? $theme['td_class1'] : $theme['td_class2'];
				$template->assign_block_vars('field', array(
					'CLASS' => $class,
					'L_NAME' => $this->get_lang($v['lang_key']),
					'L_EXPLAIN' => isset($v['explain']) ? $this->get_lang($v['explain']) : '',
					'S_BBCB' => ((isset($v['bbcode_box']) && $v['bbcode_box']) ? true : false),
					'INPUT' => $this->create_input($k, $v),
					)
				);

				if (($v['type'] != 'HIDDEN') && (isset($v['bbcode_box']) && $v['bbcode_box']))
				{
					$s_bbcb_global = true;
					$html_status = ($board_config['allow_html']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
					$bbcode_status = ($board_config['allow_bbcode']) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
					$bbcode_status = sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>');
					$smilies_status = ($board_config['allow_smilies']) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
					$formatting_rules = '<br />' . $html_status . '<br />' . $bbcode_status . '<br />' . $smilies_status . '<br />';
					$template->assign_vars(array(
						'BBCB_FORMATTING_RULES' => $formatting_rules,
						'BBCB_FORM_NAME' => 'input_form',
						'BBCB_TEXT_NAME' => $k,
						)
					);
				}
			}
		}
	}

	/*
	* Build item view page
	*/
	function create_view_page($items_row)
	{
		global $board_config, $template, $theme, $lang, $bbcode;
		global $table_fields, $inputs_array;

		foreach ($table_fields as $k => $v)
		{
			$inputs_array[$k] = (isset($items_row[$k]) ? $items_row[$k] : $v['default']);
			$inputs_array[$k] = is_string($inputs_array[$k]) ? ip_stripslashes($inputs_array[$k]) : $inputs_array[$k];
			// We convert HTML entities only if we do not need to pars HTML...
			if (is_string($inputs_array[$k]) && empty($v['html_parse']))
			{
				$value = htmlspecialchars($inputs_array[$k]);
			}

			$auth_level = $v['view_level'];
			$is_auth = check_auth_level($auth_level);
			if ($is_auth)
			{
				$value = $inputs_array[$k];
				$s_bbcb = !empty($v['bbcode_box']) ? true : false;

				// SPECIAL PROCESSING - BEGIN
				// Convert back values from RADIO, SELECT or CHECKBOX
				if (in_array($v['type'], array('LIST_RADIO_BR', 'LIST_RADIO', 'LIST_CHECKBOX', 'LIST_DROP')))
				{
					$tmp_value = $this->get_lang_from_value($inputs_array[$k], $v['values']);
					$value = ($tmp_value != '') ? $tmp_value : $value;
				}

				// Apply number format if needed
				if (!empty($v['number_format']))
				{
					$value = number_format($inputs_array[$k], $v['number_format']['decimals'], $v['number_format']['decimals_sep'], $v['number_format']['thousands_sep']);
				}

				// Text processing... BBCode, HTML or plain text
				if ($s_bbcb || !empty($v['bbcode_parse']))
				{
					$value = $bbcode->parse($inputs_array[$k]);
				}
				else
				{
					if (empty($v['html_parse']) && in_array($v['type'], array('TEXT', 'HTMLTEXT')))
					{
						$value = nl2br($inputs_array[$k]);
					}
				}

				// Convert dates and times
				if ($v['is_time'])
				{
					$value = create_date2($board_config['default_dateformat'], $inputs_array[$k], $board_config['board_timezone']);
				}

				// Create user link
				if ($v['is_user_id'])
				{
					$value = colorize_username($inputs_array[$k]);
				}

				// Create thumbnails for images
				if ($v['is_image'])
				{
					$value = '<a href="' . append_sid($inputs_array[$k]) . '"><img src="' . append_sid('posted_img_thumbnail.' . PHP_EXT . '?pic_id=' . urlencode($inputs_array[$k]) . (isset($v['thumbnail_size']) ? ('&amp;thumbnail_size=' . intval($v['thumbnail_size'])) : '')) . '" alt="" /></a>';
				}
				// SPECIAL PROCESSING - END

				$class = (empty($class) || ($class == $theme['td_class2'])) ? $theme['td_class1'] : $theme['td_class2'];
				$template->assign_block_vars('field', array(
					'CLASS' => $class,
					'L_NAME' => $this->get_lang($v['lang_key']),
					'L_EXPLAIN' => !empty($v['explain']) ? $this->get_lang($v['explain']) : '',
					'S_BBCB' => $s_bbcb ? true : false,
					'VALUE' => !empty($value) ? $value : '&nbsp;',
					)
				);
			}
		}
	}

	/*
	* get_lang
	*/
	function get_lang($key)
	{
		global $lang;
		return ((!empty($key) && isset($lang[$key])) ? $lang[$key] : $key);
	}

	/*
	* Get lang from values array
	*/
	function get_lang_from_value($value, $values_array)
	{
		global $lang;

		foreach ($values_array as $k => $v)
		{
			if ($value == $v)
			{
				if (isset($lang[$k]))
				{
					return $lang[$k];
				}
				else
				{
					return '';
				}
			}
		}

		return '';
	}

	/*
	* Select Box Builder
	*/
	function build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js = '')
	{
		$select_js = (!empty($select_js) ? $select_js : '');
		$select_box = '<select name="' . $select_name . '"' . $select_js . '>';
		for($j = 0; $j < count($options_array); $j++)
		{
			$selected = ($options_array[$j] == $default) ? ' selected="selected"' : '';
			$select_box .= '<option value="' . $options_array[$j] . '"' . $selected . '>' . $options_langs_array[$j] . '</option>';
		}
		$select_box .= '</select>';

		return $select_box;
	}

	/*
	* Create date input
	*/
	function date_input($name_prefix, $year = 1969, $month = 1, $day = 1)
	{
		global $lang;

		$name_prefix = !empty($name_prefix) ? ($name_prefix . '_') : '';

		$date_input = '';

		$default = $year;
		$select_name = $name_prefix . 'year';
		$options_array = array();
		$options_langs_array = array();
		for ($i = 2020; $i >= 1969; $i--)
		{
			$options_array[] = $i;
			$options_langs_array[] = $i;
		}
		$date_input .= '&nbsp;' . $lang['TIME_YEAR'] . ':&nbsp;' . $this->build_select_box($select_name, $default, $options_array, $options_langs_array, '');

		$default = $month;
		$select_name = $name_prefix . 'month';
		$options_array = array();
		$options_langs_array = array();
		for ($i = 1; $i <= 12; $i++)
		{
			$options_array[] = $i;
			$options_langs_array[] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		$date_input .= '&nbsp;' . $lang['TIME_MONTH'] . ':&nbsp;' . $this->build_select_box($select_name, $default, $options_array, $options_langs_array, '');

		$default = $day;
		$select_name = $name_prefix . 'day';
		$options_array = array();
		$options_langs_array = array();
		for ($i = 1; $i <= 31; $i++)
		{
			$options_array[] = $i;
			$options_langs_array[] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		$date_input .= '&nbsp;' . $lang['TIME_DAY'] . ':&nbsp;' . $this->build_select_box($select_name, $default, $options_array, $options_langs_array, '');

		return $date_input;
	}

	/*
	* Create time input
	*/
	function time_input($name_prefix, $hour = 0, $minute = 0, $second = 0)
	{
		global $lang;

		$name_prefix = !empty($name_prefix) ? ($name_prefix . '_') : '';

		$time_input = '';

		$default = $hour;
		$select_name = $name_prefix . 'hour';
		$options_array = array();
		$options_langs_array = array();
		for ($i = 0; $i <= 23; $i++)
		{
			$options_array[] = $i;
			$options_langs_array[] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		$time_input .= '&nbsp;' . $lang['TIME_HOUR'] . ':&nbsp;' . $this->build_select_box($select_name, $default, $options_array, $options_langs_array, '');

		$default = $minute;
		$select_name = $name_prefix . 'minute';
		$options_array = array();
		$options_langs_array = array();
		for ($i = 0; $i <= 59; $i++)
		{
			$options_array[] = $i;
			$options_langs_array[] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		$time_input .= '&nbsp;' . $lang['TIME_MINUTE'] . ':&nbsp;' . $this->build_select_box($select_name, $default, $options_array, $options_langs_array, '');

		$default = $second;
		$select_name = $name_prefix . 'second';
		$options_array = array();
		$options_langs_array = array();
		for ($i = 0; $i <= 59; $i++)
		{
			$options_array[] = $i;
			$options_langs_array[] = str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		$time_input .= '&nbsp;' . $lang['TIME_SECOND'] . ':&nbsp;' . $this->build_select_box($select_name, $default, $options_array, $options_langs_array, '');

		return $time_input;
	}

	/*
	* Create UNIX time from time fragments
	*/
	function implode_unix_time($year = 1969, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0)
	{
		$mktime = gmmktime($hour, $minute, $second, $month, $day, $year);
		$mktime = $this->fix_unix_time($mktime, -1);
		return $mktime;
	}

	/*
	* Explode time fragments from UNIX time
	*/
	function explode_unix_time($unix_time)
	{
		$unix_time = $this->fix_unix_time($unix_time, 1);
		$time_fragments = array(
			'year' => gmdate('Y', $unix_time),
			'month' => gmdate('m', $unix_time),
			'day' => gmdate('d', $unix_time),
			'hour' => gmdate('H', $unix_time),
			'minute' => gmdate('i', $unix_time),
			'second' => gmdate('s', $unix_time),
		);
		return $time_fragments;
	}

	/*
	* Fix UNIX time
	*/
	function fix_unix_time($unix_time, $factor = 1)
	{
		global $board_config, $lang, $userdata;

		$time_zone = (isset($userdata['user_timezone']) ? $userdata['user_timezone'] : $board_config['board_timezone']);
		$time_mode = (isset($userdata['user_time_mode']) ? $userdata['user_time_mode'] : $board_config['default_time_mode']);
		$dst_time_lag = (isset($userdata['user_dst_time_lag']) ? $userdata['user_dst_time_lag'] : $board_config['default_dst_time_lag']);

		switch ($time_mode)
		{
			case MANUAL_DST:
				$dst_sec = $dst_time_lag * 60;
				break;
			case SERVER_SWITCH:
				$dst_sec = date('I', $unix_time) * $dst_time_lag * 60;
				break;
			default:
				$dst_sec = 0;
				break;
		}
		$unix_time = $unix_time + (3600 * $time_zone * $factor) + ($dst_sec * $factor);

		return $unix_time;
	}

}

?>