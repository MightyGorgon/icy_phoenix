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

	var $date_format_jq = 'yy/mm/dd';
	var $time_format_jq = 'hh:ss';
	var $date_format_php = 'Y/m/d';
	var $time_format_php = 'H:i';
	var $date_sep = '/';
	var $time_sep = ':';
	var $decimal_sep = '.';
	var $thousands_sep = ',';

/*
	// Mighty Gorgon: overwrite some defaults for this plugin...
	$class_form->date_format_jq = 'yy-mm-dd';
	$class_form->date_format_php = 'Y-m-d';
	$class_form->date_sep = '-';
	$class_form->time_sep = ':';
*/

	/**
	* Instantiate the class
	*/
	function class_form()
	{
		global $lang;

		$this->date_format_jq = $lang['DATE_FORMAT_DATE_JQUI_JQ'];
		$this->time_format_jq = $lang['DATE_FORMAT_TIME_JQUI_JQ'];
		$this->date_format_php = $lang['DATE_FORMAT_DATE_JQUI_PHP'];
		$this->time_format_php = $lang['DATE_FORMAT_TIME_JQUI_PHP'];
		$this->date_sep = $lang['NUMBER_FORMAT_DATE_SEP'];
		$this->time_sep = $lang['NUMBER_FORMAT_TIME_SEP'];
	}


	/*
	* Create_input
	*/
	function create_input($name, $properties)
	{
		global $user, $config, $lang;

		$input = '';
		$default = !empty($properties['default']) ? (is_array($properties['default']) ? array_map('htmlspecialchars', $properties['default']) : htmlspecialchars($properties['default'])) : '';
		$readonly = !empty($properties['readonly']) ? ' readonly="readonly"' : '';

		switch ($properties['type'])
		{

			case 'HIDDEN':
				$input = '<input type="hidden" name="' . $name . '" value="' . $default . '" />';
				break;

			case 'LIST_CHECKBOX':
			case 'LIST_FLAGS':
				if (!is_array($default))
				{
					$default = explode(',', $default);
				}
				@reset($properties['values']);
				while (list($key, $val) = @each($properties['values']))
				{
					if ($properties['type'] == 'LIST_FLAGS')
					{
						$selected = ((int) $val & $default) ? ' checked="checked"' : '';
					}
					else
					{
						$selected = (!empty($default) && is_array($default) && in_array(trim($val), $default)) ? ' checked="checked"' : '';
					}
					$l_key = $this->get_lang($key);
					$input .= '<label><input type="checkbox" name="' . $name . '[]" value="' . $val . '"' . $selected . ' />&nbsp;' . $l_key. '</label><br />';
				}
				break;

			/*
			case 'SINGLE_CHECKBOX':
				$selected = ((int) $properties['value'] & $default) ? ' checked="checked"' : '';
				$l_key = $this->get_lang($properties['label']);
				$input .= '<label><input type="checkbox" name="' . $name . '" value="' . $val . '"' . $selected . ' />&nbsp;' . $l_key. '</label><br />';
				break;
			*/

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

			case 'LIST_RADIO':
			case 'LIST_RADIO_BR':
				@reset($properties['values']);
				while (list($key, $val) = @each($properties['values']))
				{
					$selected = ($default == $val) ? ' checked="checked"' : '';
					$l_key = $this->get_lang($key);
					$input .= '<input type="radio" name="' . $name . '" value="' . $val . '"' . $selected . ' />&nbsp;' . $l_key;
					$input .= (($properties['type'] == 'LIST_RADIO_BR') ? '<br />' : '&nbsp;&nbsp;');
				}
				break;

			case 'DATE_INPUT_JQUI':
			case 'TIME_INPUT_JQUI':
			case 'DATE_INPUT':
			case 'TIME_INPUT':
			case 'DATE_TIME_INPUT':
				$input_time = (!empty($properties['default']) ? $properties['default'] : $current_time);
				$tf = $this->explode_unix_time($input_time);
				$select_date = $this->date_input($name, $tf['year'], $tf['month'], $tf['day']);
				$select_time = $this->time_input($name, $tf['hour'], $tf['minute'], $tf['second']);

				switch ($properties['type'])
				{
					case 'DATE_INPUT_JQUI':
						$default = ($properties['datetime_format'] == 'mysql') ? format_date_mysql_php($default, 'date', 'php') : create_date($this->date_format_php, $default, $config['board_timezone']);
						$jquery_ui = '<script type="text/javascript">$(function() { $.datepicker.setDefaults( $.datepicker.regional["' . $lang['HEADER_LANG_JQUERY'] . '"] ); $("#' . $name . '").datepicker({ dateFormat: "' . $this->date_format_jq . '", changeMonth: true, changeYear: true }); });</script>';
						$input = $jquery_ui . '<input type="text" name="' . $name . '" id="' . $name . '" maxlength="12" size="16" readonly="readonly" class="post" value="' . $default . '" />';
						break;
					case 'TIME_INPUT_JQUI':
						$default = ($properties['datetime_format'] == 'mysql') ? format_date_mysql_php($default, 'time', 'php') : create_date($this->time_format_php, $default, $config['board_timezone']);
						$jquery_ui = '<script type="text/javascript">$(function() { $.timepicker.setDefaults( $.timepicker.regional["' . $lang['HEADER_LANG_JQUERY'] . '"] ); $("#' . $name . '").timepicker({ timeSeparator: "' . $this->time_sep . '" }); });</script>';
						$input = $jquery_ui . '<input type="text" name="' . $name . '" id="' . $name . '" maxlength="8" size="12" readonly="readonly" class="post" value="' . $default . '" />';
						break;
					case 'DATE_INPUT':
						$input = $select_date;
						break;
					case 'TIME_INPUT':
						$input = $select_time;
						break;
					case 'DATE_TIME_INPUT':
					default:
						$input = $select_date . $select_time;
						break;
				}
				break;

			case 'USERNAME_INPUT':
				$user_data = get_userdata($default, false);
				$username = !empty($user_data) ? $user_data['username'] : '';
				$input = '<input type="text" name="' . $name . '" id="' . $name . '" maxlength="255" size="30" class="post" value="' . htmlspecialchars($username) . '" />';
				$input .= '<input type="submit" name="' . $name . '_search_button" value="' . $lang['FIND_USERNAME'] . '" class="mainoption" onclick="window.open(\'' . append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser&amp;target_form_name=input_form&amp;target_element_name=' . $name) . '\', \'_search\', \'width=400,height=250,resizable=yes\'); return false;" />';
				break;

			case 'USERNAME_INPUT_JQUI':
				$ajax_user_id = $default;
				$ajax_user_data = get_userdata($ajax_user_id, false);
				$ajax_user_name = htmlspecialchars($ajax_user_data['username']);
				$ajax_user_field_id = $name . '_jqui';
				$ajax_user_field_name = $name . '_jqui';
				$ajax_user_field_id_hidden = $name;
				$ajax_user_field_name_hidden = $name;
				$ajax_user_jquery_ui = '<script type="text/javascript">
				$(function()
				{
					$("#' . $ajax_user_field_id . '").autocomplete(
					{
						source: "ajax.php?mode=user_search_json&json=1&sid=' . $user->data['session_id'] . '",
						minLength: 2,
						select: function(event, ui)
						{
							if (ui.item)
							{
								$("#' . $ajax_user_field_id_hidden . '").val(ui.item.id);
							}
						}
					});
				});
				</script>';
				$ajax_user_input_hidden = '<input type="hidden" name="' . $ajax_user_field_name_hidden . '" id="' . $ajax_user_field_id_hidden . '" value="' . $ajax_user_id . '" />';
				$input = $ajax_user_jquery_ui . $ajax_user_input_hidden . '<input type="text" name="' . $ajax_user_field_name . '" id="' . $ajax_user_field_id . '" maxlength="255" size="30" class="post" value="' . $ajax_user_name . '" />';
				break;

			case 'AJAX_INPUT_JQUI':
				$ajax_input_id = $default;
				$ajax_input_data = $properties['ajax_get_func']($ajax_input_id);
				$ajax_input_value = ((is_array($ajax_input_data) && !empty($ajax_input_data)) ? $ajax_input_data['value'] : $ajax_input_data);
				$ajax_input_name = htmlspecialchars($ajax_input_value);
				$ajax_input_field_id = $name . '_jqui';
				$ajax_input_field_name = $name . '_jqui';
				$ajax_input_field_id_hidden = $name;
				$ajax_input_field_name_hidden = $name;
				$ajax_input_field_id_extra = $properties['ajax_callback_input_extra'];
				$ajax_input_jquery_ui = '<script type="text/javascript">
				$(function()
				{
					$("#' . $ajax_input_field_id . '").autocomplete(
					{
						source: "ajax.php?mode=' . $properties['ajax_mode'] . '&json=1' . (!empty($properties['ajax_plugin']) ? ('&plugin=' . $properties['ajax_plugin']) : '') . '&sid=' . $user->data['session_id'] . '",
						minLength: 2,
						select: function(event, ui)
						{
							if (ui.item)
							{
								$("#' . $ajax_input_field_id_hidden . '").val(ui.item.id);
								' . (!empty($ajax_input_field_id_extra) ? 'if (ui.item.extra) { $("#' . $ajax_input_field_id_extra . '").val(ui.item.extra); }' : '') . '
							}
						}
					});
				});
				</script>';
				$ajax_input_input_hidden = '<input type="hidden" name="' . $ajax_input_field_name_hidden . '" id="' . $ajax_input_field_id_hidden . '" value="' . $ajax_input_id . '" />';
				$input = $ajax_input_jquery_ui . $ajax_input_input_hidden . '<input type="text" name="' . $ajax_input_field_name . '" id="' . $ajax_input_field_id . '" maxlength="255" size="30" class="post" value="' . $ajax_input_name . '" />';
				break;

			case 'TINYINT':
			case 'SMALLINT':
			case 'MEDIUMINT':
			case 'INT':
			case 'FLOAT':
				$field_length = $this->set_number_length($properties['type']);
				$input = '<input type="text" name="' . $name . '" maxlength="' . $field_length . '" size="' . $field_length . '" class="post" value="' . (empty($default) ? 0 : $default) . '"' . $readonly . ' />';
				break;

			case 'TINYTEXT':
			case 'PASSWORD':
			case 'VARCHAR':
			case 'HTMLVARCHAR':
				$maxlength = ($properties['type'] == 'TINYTEXT') ? 20 : 255;
				$size = ($properties['type'] == 'TINYTEXT') ? 20 : 45;
				$default = !in_array($properties['type'], array('HTMLVARCHAR')) ? htmlspecialchars_decode($default, ENT_COMPAT) : $default;
				$input = '<input type="' . (($properties['type'] == 'PASSWORD') ? 'password' : 'text') . '" name="' . $name . '" maxlength="' . $maxlength . '" size="' . $size . '" class="post" value="' . $default . '"' . $readonly . ' />';
				break;

			case 'TEXT':
			case 'HTMLTEXT':
				$default = !in_array($properties['type'], array('HTMLTEXT')) ? htmlspecialchars_decode($default, ENT_COMPAT) : $default;
				$input = '<div class="message-box"><textarea rows="10" cols="35" name="' . $name . '">' . $default . '</textarea></div>';
				break;

			case 'FUNCTION':
			default:
				if (!empty($properties['get_func']) && function_exists($properties['get_func']))
				{
					$input = $properties['get_func']($name, $default);
				}
				break;

		}

		if (!empty($properties['input_extra']))
		{
			$id_extra = $properties['input_extra']['id'];
			$name_extra = $properties['input_extra']['name'];
			$default_extra = (!empty($properties['input_extra']['default_func']) ? $properties['input_extra']['default_func']() : $properties['input_extra']['default']);
			$readonly_extra = !empty($properties['input_extra']['readonly']) ? ' readonly="readonly"' : '';

			$properties['input_extra']['type'] = ($properties['type'] == 'HIDDEN') ? 'HIDDEN' : $properties['input_extra']['type'];

			switch ($properties['input_extra']['type'])
			{

				case 'HIDDEN':
					$input_extra = '<input type="hidden" name="' . $name_extra . '" ' . (!empty($id_extra) ? ('id="' . $id_extra . '" ') : '') . 'value="' . $default_extra . '" />';
					break;

				case 'TINYINT':
				case 'SMALLINT':
				case 'MEDIUMINT':
				case 'INT':
				case 'FLOAT':
					$field_length = $this->set_number_length($properties['type']);
					$input_extra = '<input type="text" name="' . $name_extra . '" ' . (!empty($id_extra) ? ('id="' . $id_extra . '" ') : '') . 'maxlength="' . $field_length . '" size="' . $field_length . '" class="post" value="' . (empty($default_extra) ? 0 : $default_extra) . '"' . $readonly_extra . ' />';
					break;

				case 'TINYTEXT':
				case 'VARCHAR':
					$maxlength = ($properties['input_extra']['type'] == 'TINYTEXT') ? 20 : 255;
					$size = ($properties['input_extra']['type'] == 'TINYTEXT') ? 20 : 45;
					$input_extra = '<input type="text" name="' . $name_extra . '" ' . (!empty($id_extra) ? ('id="' . $id_extra . '" ') : '') . 'maxlength="' . $maxlength . '" size="' . $size . '" class="post" value="' . $default_extra . '"' . $readonly_extra . ' />';
					break;

				case 'FUNCTION':
				default:
					if (!empty($properties['input_extra']['get_func']) && function_exists($properties['input_extra']['get_func']))
					{
						$input_extra = $properties['input_extra']['get_func']($name_extra, $default_extra);
					}
					break;
			}

			$input = $input . '&nbsp;&nbsp;' . $input_extra;
		}

		/*
		// Dump to template
		$template->assign_block_vars('field', array(
			'L_NAME' => $this->get_lang($properties['lang_key']),
			'L_EXPLAIN' => !empty($properties['explain']) ? $this->get_lang($properties['explain']) : '',
			'INPUT' => $input,
			)
		);
		*/

		/*
		// To be used within a cycle
		$template->assign_block_vars('field', array(
			'L_NAME' => $class_form->get_lang($v['lang_key']),
			'L_EXPLAIN' => !empty($v['explain']) ? $class_form->get_lang($v['explain']) : '',
			'INPUT' => $class_form->create_input($k, $v),
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

			case 'float':
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
	* Validate value
	*/
	function validate_value($config_data)
	{
		global $lang;

		$config_value = $config_data['default'];
		switch ($config_data['type'])
		{
			case 'HIDDEN':
				break;

			case 'LIST_CHECKBOX':
			case 'LIST_FLAGS':
				$flags_sum = 0;
				if (($config_data['type'] == 'LIST_FLAGS') && !empty($config_value) && is_array($config_value))
				{
					foreach ($config_value as $k => $v)
					{
						$flags_sum += (int) $v;
					}
					$config_value = $flags_sum;
				}

				if (is_array($config_value))
				{
					foreach ($config_value as $k => $v)
					{
						if (!in_array($v, $config_data['values']))
						{
							unset($config_value[$k]);
						}
					}
				}

				if (!is_array($config_value) && !in_array($config_value, $config_data['values']))
				{
					$config_value = $config_data['values'][0];
				}
				break;

			case 'LIST_DROP':
			case 'LIST_RADIO':
			case 'LIST_RADIO_BR':
				if (!in_array($config_value, $config_data['values']))
				{
					$config_value = $config_data['values'][0];
				}
				break;

			case 'TINYINT':
			case 'SMALLINT':
			case 'MEDIUMINT':
			case 'INT':
				$config_value = (int) ($config_value);
				break;

			case 'FLOAT':
				$config_value = (float) str_replace(',', '.', $config_value);
				break;

			case 'TINYTEXT':
			case 'PASSWORD':
			case 'VARCHAR':
			case 'TEXT':
			case 'DATEFMT':
				$config_value = (string) trim(htmlspecialchars($config_value));
				break;

			case 'HTMLVARCHAR':
			case 'HTMLTEXT':
				$config_value = (string) trim($config_value);
				break;

			case 'FUNCTION':
			default:
				if (!empty($config_data['chk_func']) && function_exists($config_data['chk_func']))
				{
					$config_value = $config_data['chk_func']($config_data['name'], $config_value);
				}
				break;
		}

		return $config_value;
	}

	/*
	* Set number length
	*/
	function set_number_length($number_type)
	{
		switch ($number_type)
		{
			case 'TINYINT':
				$length = 3;
				break;

			case 'SMALLINT':
				$length = 5;
				break;

			case 'MEDIUMINT':
				$length = 9;
				break;

			case 'INT':
				$length = 13;
				break;

			case 'FLOAT':
				$length = 13;
				break;

			default:
				$length = 13;
				break;
		}

		return $legth;
	}

	/*
	* Build inputs_array
	*/
	function create_inputs_array(&$table_fields, &$inputs_array, &$current_time, &$item_id, $mode, $action)
	{
		global $lang;

		foreach ($table_fields as $k => $v)
		{
			if (($v['type'] != 'HIDDEN') && (!empty($v['is_time']) || !empty($v['is_date'])))
			{
				if (($v['datetime_format'] == 'mysql') || ($v['type'] == 'TIME_INPUT_JQUI'))
				{
					$inputs_array[$k] = request_var($k, $v['default']);
					if (in_array($v['type'], array('DATE_INPUT_JQUI', 'TIME_INPUT_JQUI')))
					{
						$output = ($v['type'] == 'DATE_INPUT_JQUI') ? 'date' : 'time';
						$inputs_array[$k] = format_date_mysql_php($inputs_array[$k], $output, 'mysql');
					}
				}
				else
				{
					if (in_array($v['type'], array('DATE_INPUT_JQUI')))
					{
						$inputs_array[$k] = request_var($k, $v['default']);
						$var_fragment = strutime($inputs_array[$k], $this->date_format_php);
					}
					else
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
					}
					$inputs_array[$k] = $this->implode_unix_time($var_fragment['year'], $var_fragment['month'], $var_fragment['day'], $var_fragment['hour'], $var_fragment['minute'], $var_fragment['second']);
				}
			}
			else
			{
				$multibyte = (isset($v['type']) && in_array($v['type'], array('HIDDEN', 'VARCHAR', 'HTMLVARCHAR', 'TEXT', 'HTMLTEXT'))) ? true : false;
				$html_decode = (isset($v['type']) && in_array($v['type'], array('HTMLVARCHAR', 'HTMLTEXT'))) ? true : false;
				$inputs_array[$k] = request_var($k, $v['default'], $multibyte);
				$inputs_array[$k] = $html_decode ? htmlspecialchars_decode($inputs_array[$k]) : $inputs_array[$k];
			}

			// We want to force each value the user isn't allowed to add/edit to the default value
			if (($k != $item_id) && ((($action == 'add') && !check_auth_level($v['input_level'])) || (($v['type'] != 'HIDDEN') && ($action == 'edit') && !check_auth_level($v['edit_level']))))
			{
				$inputs_array[$k] = $v['default'];
			}
		}
	}

	/*
	* Request vars data
	*/
	function request_vars_data(&$data_array)
	{
		$vars_data = array();
		foreach ($data_array as $k => $v)
		{
			$multibyte = (isset($v['type']) && in_array($v['type'], array('HIDDEN', 'VARCHAR', 'HTMLVARCHAR', 'TEXT', 'HTMLTEXT'))) ? true : false;
			$html_decode = (isset($v['type']) && in_array($v['type'], array('HTMLVARCHAR', 'HTMLTEXT'))) ? true : false;
			if ($v['type'] == 'LIST_FLAGS')
			{
				$vars_data[$k] = array_sum(request_var($k, array(0)));
			}
			else
			{
				$vars_data[$k] = request_var($k, $v['default'], $multibyte);
			}
			$vars_data[$k] = $html_decode ? htmlspecialchars_decode($vars_data[$k], ENT_COMPAT) : $vars_data[$k];
			$data_array[$k]['default'] = $vars_data[$k];
		}
		return $vars_data;
	}

	/*
	* Build input form
	*/
	function create_input_form(&$table_fields, &$inputs_array, &$current_time, &$s_bbcb_global, $mode, $action, $items_row)
	{
		global $config, $template, $theme, $lang, $s_hidden_fields;

		$row_class = '';
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
				$row_class = ip_zebra_rows($row_class);
				$template->assign_block_vars('field', array(
					'CLASS' => $row_class,
					'L_NAME' => $this->get_lang($v['lang_key']),
					'L_EXPLAIN' => isset($v['explain']) ? $this->get_lang($v['explain']) : '',
					'S_BBCB' => ((isset($v['bbcode_box']) && $v['bbcode_box']) ? true : false),
					'INPUT' => $this->create_input($k, $v),
					)
				);

				if (($v['type'] != 'HIDDEN') && (isset($v['bbcode_box']) && $v['bbcode_box']))
				{
					$s_bbcb_global = true;
					$html_status = ($config['allow_html']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
					$bbcode_status = ($config['allow_bbcode']) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
					$bbcode_status = sprintf($bbcode_status, '<a href="' . append_sid(IP_ROOT_PATH . CMS_PAGE_FAQ . '?mode=bbcode') . '" target="_blank">', '</a>');
					$smilies_status = ($config['allow_smilies']) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
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
	function create_view_page(&$table_fields, &$inputs_array, $items_row, $template_row = 'field')
	{
		global $config, $template, $theme, $lang, $bbcode;

		$row_class = '';
		foreach ($table_fields as $k => $v)
		{
			$inputs_array[$k] = (isset($items_row[$k]) ? $items_row[$k] : $v['default']);
			// We convert HTML entities only if we do not need to parse HTML...
			if (is_string($inputs_array[$k]) && !empty($v['html_parse']))
			{
				$value = htmlspecialchars_decode($inputs_array[$k], ENT_COMPAT);
			}

			$auth_level = $v['view_level'];
			$is_auth = check_auth_level($auth_level);
			if ($is_auth)
			{
				$value = $inputs_array[$k];
				$s_bbcb = !empty($v['bbcode_box']) ? true : false;

				// SPECIAL PROCESSING - BEGIN
				// Convert back values from RADIO, SELECT or CHECKBOX
				if (in_array($v['type'], array('LIST_CHECKBOX', 'LIST_DROP', 'LIST_FLAGS', 'LIST_RADIO', 'LIST_RADIO_BR')))
				{
					$tmp_value = $this->get_lang_from_value($inputs_array[$k], $v['values']);
					$value = ($tmp_value != '') ? $tmp_value : $value;
				}

				// Convert empty numbers fields to 0
				if (!empty($v['is_number']) && empty($value))
				{
					$value = (int) 0;
				}

				// Apply number format if needed
				if (!empty($v['number_format']))
				{
					$v['number_format']['decimals'] = isset($v['number_format']['decimals']) ? $v['number_format']['decimals'] : (isset($lang['NUMBER_FORMAT_DECIMALS']) ? $lang['NUMBER_FORMAT_DECIMALS'] : 0);
					$v['number_format']['decimals_sep'] = !empty($v['number_format']['decimals_sep']) ? $v['number_format']['decimals_sep'] : (isset($lang['NUMBER_FORMAT_DECIMALS_SEP']) ? $lang['NUMBER_FORMAT_DECIMALS_SEP'] : ',');
					$v['number_format']['thousands_sep'] = !empty($v['number_format']['thousands_sep']) ? $v['number_format']['thousands_sep'] : (isset($lang['NUMBER_FORMAT_THOUSANDS_SEP']) ? $lang['NUMBER_FORMAT_THOUSANDS_SEP'] : '.');
					$value = number_format($inputs_array[$k], $v['number_format']['decimals'], $v['number_format']['decimals_sep'], $v['number_format']['thousands_sep']);
				}

				// Create rating image
				if (!empty($v['is_rating']))
				{
					$rating = $value;
					$rating_path = !empty($v['rating_path']) ? $v['rating_path'] : (IP_ROOT_PATH . 'images/rates/');
					$rating_min = !empty($v['rating_min']) ? $v['rating_min'] : 0;
					$rating_max = !empty($v['rating_max']) ? $v['rating_max'] : 10;
					$rating_extension = !empty($v['rating_extension']) ? $v['rating_extension'] : 'png';
					$value = '<img src="' . $this->build_rating_image($rating, $rating_path, $rating_min, $rating_max, $rating_extension) . '" alt="' . $rating . '" title="' . $rating . '" />';
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
				$v['datetime_format'] = !empty($v['datetime_format']) ? $v['datetime_format'] : 'unix';
				switch ($v['datetime_format'])
				{
					case 'mysql':
						$output = !empty($v['is_date']) ? 'date' : (!empty($v['is_time']) ? 'time' : 'datetime');
						$value = format_date_mysql_php($inputs_array[$k], $output, 'php');
						break;

					default:
						if (!empty($v['is_time']))
						{
							$value = create_date_ip($config['default_dateformat'], $inputs_array[$k], $config['board_timezone']);
						}

						if (!empty($v['is_date']))
						{
							$value = create_date($this->date_format_php, $inputs_array[$k], $config['board_timezone']);
						}
						break;
				}

				// Create user link (with user_id)
				if ($v['is_user_id'])
				{
					$value = colorize_username($inputs_array[$k]);
				}

				// Create user link (with username)
				if ($v['is_username'])
				{
					$target_userid = $this->get_user_id($inputs_array[$k]);
					$value = colorize_username($target_userid);
				}

				// Create user link (with username)
				if ($v['type'] == 'PASSWORD')
				{
					$value = '********';
				}

				// Create thumbnails for images
				if ($v['is_image'])
				{
					$value = '<a href="' . append_sid($inputs_array[$k]) . '"><img src="' . append_sid(CMS_PAGE_IMAGE_THUMBNAIL . '?pic_id=' . urlencode($inputs_array[$k]) . (isset($v['thumbnail_size']) ? ('&amp;thumbnail_size=' . intval($v['thumbnail_size'])) : '')) . '" alt="" /></a>';
				}

				if (!empty($v['display_func']) && function_exists($v['display_func']))
				{
					$value = $v['display_func']($inputs_array[$k]);
				}
				// SPECIAL PROCESSING - END

				$row_class = ip_zebra_rows($row_class);
				$template_row = empty($template_row) ? 'field' : (string) $template_row;
				$template->assign_block_vars($template_row, array(
					'CLASS' => $row_class,
					'L_NAME' => $this->get_lang($v['lang_key']),
					'L_EXPLAIN' => !empty($v['explain']) ? $this->get_lang($v['explain']) : '',
					'S_BBCB' => $s_bbcb ? true : false,
					'VALUE' => (!empty($value) || ($value === 0)) ? $value : '&nbsp;',
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
		for($j = 0; $j < sizeof($options_array); $j++)
		{
			$selected = ($options_array[$j] == $default) ? ' selected="selected"' : '';
			$select_box .= '<option value="' . $options_array[$j] . '"' . $selected . '>' . $options_langs_array[$j] . '</option>';
		}
		$select_box .= '</select>';

		return $select_box;
	}

	/*
	* Radio Box Builder
	*/
	function build_radio_box($radio_name, $default, $options_array, $options_lang_array, $radio_js = '')
	{
		$radio_js = (!empty($radio_js) ? $radio_js : '');
		$radio_box = '';
		for($j = 0; $j < sizeof($options_array); $j++)
		{
			$checked = ($options_array[$j] == $default) ? ' checked="checked"' : '';
			$radio_box .= (($j > 0) ? '&nbsp;&nbsp;' : '');
			$radio_box .= '<input type="radio" name="' . $radio_name . '" value="' . $options_array[$j] . '"' . $checked . $radio_js . ' />&nbsp;' . $options_lang_array[$j];
		}

		return $radio_box;
	}

	/*
	* Get user_id from username or validate user_id
	*/
	function get_user_id($user)
	{
		global $db;

		$target_userdata = array();
		$target_userdata = get_userdata($user, true);
		if (empty($target_userdata))
		{
			$target_userdata = get_userdata($user);
		}
		$target_user_id = empty($target_userdata) ? ANONYMOUS : $target_userdata['user_id'];

		return $target_user_id;
	}

	/*
	* Get username from user_id
	*/
	function get_username($user_id)
	{
		global $db;

		$target_userdata = array();
		$target_userdata = get_userdata($user_id);
		$target_username = empty($target_userdata) ? '' : $target_userdata['username'];

		return $target_username;
	}

	/**
	* Search for a field in the DB
	*/
	function field_search($name, $default, $search_url, $search_mode)
	{
		$input = '<input type="text" name="' . $name . '" id="' . $name . '" maxlength="255" size="45" readonly="readonly" class="post" value="' . $default . '" />';
		$input .= '<input type="submit" name="' . $name . '_search_button" value="' . $lang['Find'] . '" class="mainoption" onclick="window.open(\'' . append_sid($search_url . '?mode=' . $search_mode . '&amp;target_form_name=input_form&amp;target_element_name=' . $name) . '\', \'_field_search\', \'width=400,height=250,resizable=yes\'); return false;" />';

		return $input;
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
	* Explode time fragments from UNIX or MySQL time
	*/
	function explode_time($time, $format = 'unix')
	{
		switch ($format)
		{
			case 'mysql':
				$time_fragments = $this->explode_mysql_time($time);
				break;

			default:
				$time_fragments = $this->explode_unix_time($time);
				break;
		}

		return $time_fragments;
	}

	/*
	* Explode time fragments from MySQL time
	*/
	function explode_mysql_time($time)
	{
		$time_fragments = array(
			'year' => '0000',
			'month' => '00',
			'day' => '00',
			'hour' => '00',
			'minute' => '00',
			'second' => '00',
		);

		switch (strlen($time))
		{
			// DATE only
			case 10:
				$mysql_date = explode('-', $time);
				$time_fragments['year'] = !empty($mysql_date[0]) ? $mysql_date[0] : '0000';
				$time_fragments['month'] = !empty($mysql_date[1]) ? $mysql_date[1] : '00';
				$time_fragments['day'] = !empty($mysql_date[2]) ? $mysql_date[2] : '00';
				break;

			// TIME only
			case 8:
				$mysql_time = explode(':', $time);
				$time_fragments['hour'] = !empty($mysql_time[0]) ? $mysql_time[0] : '00';
				$time_fragments['minute'] = !empty($mysql_time[1]) ? $mysql_time[1] : '00';
				$time_fragments['second'] = !empty($mysql_time[2]) ? $mysql_time[2] : '00';
				break;

			// DATETIME field
			default:
				$mysql_time_full = explode(' ', $time);

				$mysql_date = explode('-', $mysql_time_full[0]);
				$time_fragments['year'] = !empty($mysql_date[0]) ? $mysql_date[0] : '0000';
				$time_fragments['month'] = !empty($mysql_date[1]) ? $mysql_date[1] : '00';
				$time_fragments['day'] = !empty($mysql_date[2]) ? $mysql_date[2] : '00';

				$mysql_time = explode(':', $mysql_time_full[1]);
				$time_fragments['hour'] = !empty($mysql_time[0]) ? $mysql_time[0] : '00';
				$time_fragments['minute'] = !empty($mysql_time[1]) ? $mysql_time[1] : '00';
				$time_fragments['second'] = !empty($mysql_time[2]) ? $mysql_time[2] : '00';
				break;
		}
		return $time_fragments;
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
		global $config, $lang, $user;

		$time_zone = (isset($user->data['user_timezone']) ? $user->data['user_timezone'] : $config['board_timezone']);
		$time_mode = (isset($user->data['user_time_mode']) ? $user->data['user_time_mode'] : $config['default_time_mode']);
		$dst_time_lag = (isset($user->data['user_dst_time_lag']) ? $user->data['user_dst_time_lag'] : $config['default_dst_time_lag']);

		switch ($time_mode)
		{
			case MANUAL_DST:
				$dst_sec = $dst_time_lag * 60;
				break;
			case SERVER_SWITCH:
				$dst_sec = @date('I', $unix_time + (3600 * $time_zone)) * $dst_time_lag * 60;
				break;
			default:
				$dst_sec = 0;
				break;
		}
		$unix_time = $unix_time + (3600 * $time_zone * $factor) + ($dst_sec * $factor);

		return $unix_time;
	}

	/*
	* Build rating image
	*/
	function build_rating_image($rating, $rating_img_path, $rating_min = 0, $rating_max = 10, $rating_img_extension = 'png')
	{
		$rating_scale = (($rating_max - $rating_min) == 0) ? 10 : ($rating_max - $rating_min);
		$rating_level = round(($rating / $rating_scale) * 10, 0) / 2;
		$rating_img_suffix = strval(number_format($rating_level, 1, '.', ''));
		$rating_image = $rating_img_path . 'rate_' . $rating_img_suffix . '.' . $rating_img_extension;

		return $rating_image;
	}

	/*
	* Outputs image with correct headers
	*/
	function image_output($pic_full_path, $pic_title_reg, $pic_filetype)
	{
		switch($pic_filetype)
		{
			case 'gif':
				$file_header = 'image/gif';
				break;
			case 'jpg':
			case 'jpeg':
				$file_header = 'image/jpeg';
				break;
			case 'png':
				$file_header = 'image/png';
				break;
			default:
				$file_header = 'image/jpeg';
				break;
		}
		header('Content-type: ' . $file_header);
		header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
		readfile($pic_full_path);
	}

}

?>