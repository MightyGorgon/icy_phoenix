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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

// PLEASE DON'T TAKE THIS CLASS AND USE IT, I WILL KEEP MY EYES ON IT
// I KNOW SOME PEOPLE MAY TAKE IT AND USE IT TO DO CUSTOM FIELD FOR PROFILE
// BUT I AM PLANNING TO MAKE THIS FEATURE VERY SOON

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

class kb_custom_field
{
	var $field_rowset = array();
	var $field_data_rowset = array();
	// ===================================================
	// prepare data
	// ===================================================
	function init()
	{
		global $db;

		$sql = "SELECT *
			FROM " . KB_CUSTOM_TABLE . "
			ORDER BY field_order ASC";

		if (!($result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, 'Couldnt Query Custom field', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->field_rowset[$row['custom_id']] = $row;
		}
		unset($row);
		$db->sql_freeresult($result);

		$sql = "SELECT *
			FROM " . KB_CUSTOM_DATA_TABLE;

		if (!($result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, 'Couldnt Query Custom field', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->field_data_rowset[$row['customdata_file']][$row['customdata_custom']] = $row;
		}

		unset($row);

		$db->sql_freeresult($result);
	}
	// ===================================================
	// check if there is a data in the database
	// ===================================================
	function field_data_exist()
	{
		if (!empty($this->field_data_rowset))
		{
			return true;
		}
		return false;
	}

	function field_exist()
	{
		if (!empty($this->field_rowset))
		{
			return true;
		}
		return false;
	}

	// ===================================================
	// display data in the comment
	// ===================================================
	function add_comment($file_id)
	{
		global $template;
		if ($this->field_data_exist())
		{
			if (isset($this->field_data_rowset[$file_id]))
			{
				$message = '';
				foreach($this->field_data_rowset[$file_id] as $field_id => $data)
				{
					if (!empty($data['data']))
					{
						switch ($this->field_rowset[$field_id]['field_type'])
						{
							case INPUT:
							case TEXTAREA:
							case RADIO:
							case SELECT:
								$field_data = $data['data'];
								break;
							case SELECT_MULTIPLE:
							case CHECKBOX:
								$field_data = @implode(', ', unserialize($data['data']));
								break;
						}
						$message .= "\n" . "[b]" . $this->field_rowset[$field_id]['custom_name'] . ":[/b] " . $field_data . "\n";
					}
					else
					{
						global $db;

						$sql = "DELETE FROM " . KB_CUSTOM_DATA_TABLE . "
							WHERE customdata_file = '$file_id'
							AND customdata_custom = '$field_id'";

						if (!($db->sql_query($sql)))
						{
							mx_message_die(GENERAL_ERROR, 'Could not delete custom data', '', __LINE__, __FILE__, $sql);
						}
					}
				}
				return $message;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	// ===================================================
	// display data in the file page
	// ===================================================
	function display_data($file_id)
	{
		global $template;
		if ($this->field_data_exist())
		{
			if (isset($this->field_data_rowset[$file_id]))
			{
				foreach($this->field_data_rowset[$file_id] as $field_id => $data)
				{
					if (!empty($data['data']))
					{
						switch ($this->field_rowset[$field_id]['field_type'])
						{
							case INPUT:
							case TEXTAREA:
							case RADIO:
							case SELECT:
								$field_data = $data['data'];
								break;
							case SELECT_MULTIPLE:
							case CHECKBOX:
								$field_data = @implode(', ', unserialize($data['data']));
								break;
						}

						$template->assign_block_vars('custom_field', array('CUSTOM_NAME' => $this->field_rowset[$field_id]['custom_name'],
								'DATA' => $field_data)
							);
					}
					else
					{
						global $db;

						$sql = "DELETE FROM " . KB_CUSTOM_DATA_TABLE . "
							WHERE customdata_file = '$file_id'
							AND customdata_custom = '$field_id'";

						if (!($db->sql_query($sql)))
						{
							mx_message_die(GENERAL_ERROR, 'Could not delete custom data', '', __LINE__, __FILE__, $sql);
						}
					}
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	// ===================================================
	// display custom field and data in the add/edit page
	// ===================================================
	function display_edit($file_id = false)
	{
		global $template;

		$return = false;
		if ($this->field_exist())
		{
			foreach($this->field_rowset as $field_id => $field_data)
			{
				switch ($field_data['field_type'])
				{
					case INPUT:
						$this->display_edit_input($file_id, $field_id, $field_data);
						break;
					case TEXTAREA:
						$this->display_edit_textarea($file_id, $field_id, $field_data);
						break;
					case RADIO:
						$this->display_edit_radio($file_id, $field_id, $field_data);
						break;
					case SELECT:
						$this->display_edit_select($file_id, $field_id, $field_data);
						break;
					case SELECT_MULTIPLE:
						$this->display_edit_select_multiple($file_id, $field_id, $field_data);
						break;
					case CHECKBOX:
						$this->display_edit_checkbox($file_id, $field_id, $field_data);
						break;
				}

				$return = true;
			}
		}
		return $return;
	}

	function display_edit_input($file_id, $field_id, $field_data)
	{
		global $template;
		$template->assign_block_vars('input', array('FIELD_NAME' => $field_data['custom_name'],
				'FIELD_ID' => $field_data['custom_id'],
				'FIELD_DESCRIPTION' => $field_data['custom_description'],
				'FIELD_VALUE' => (!empty($this->field_data_rowset[$file_id][$field_id]['data'])) ? $this->field_data_rowset[$file_id][$field_id]['data'] : '')
			);
	}

	function display_edit_textarea($file_id, $field_id, $field_data)
	{
		global $template;
		$template->assign_block_vars('textarea', array('FIELD_NAME' => $field_data['custom_name'],
				'FIELD_ID' => $field_data['custom_id'],
				'FIELD_DESCRIPTION' => $field_data['custom_description'],
				'FIELD_VALUE' => (!empty($this->field_data_rowset[$file_id][$field_id]['data'])) ? $this->field_data_rowset[$file_id][$field_id]['data'] : '')
			);
	}

	function display_edit_radio($file_id, $field_id, $field_data)
	{
		global $template;
		$template->assign_block_vars('radio', array('FIELD_NAME' => $field_data['custom_name'],
				'FIELD_ID' => $field_data['custom_id'],
				'FIELD_DESCRIPTION' => $field_data['custom_description'])
			);

		$data = (!empty($this->field_data_rowset[$file_id][$field_id]['data'])) ? $this->field_data_rowset[$file_id][$field_id]['data'] : array();
		$field_datas = (!empty($field_data['data'])) ? unserialize(stripslashes($field_data['data'])) : array();

		if (!empty($field_datas))
		{
			foreach($field_datas as $key => $value)
			{
				$template->assign_block_vars('radio.row', array('FIELD_VALUE' => $value,
						'FIELD_SELECTED' => ($data == $value) ? ' checked="checked"' : '')
					);
			}
		}
	}

	function display_edit_select($file_id, $field_id, $field_data)
	{
		global $template;
		$template->assign_block_vars('select', array('FIELD_NAME' => $field_data['custom_name'],
				'FIELD_ID' => $field_data['custom_id'],
				'FIELD_DESCRIPTION' => $field_data['custom_description'])
			);

		$data = (!empty($this->field_data_rowset[$file_id][$field_id]['data'])) ? $this->field_data_rowset[$file_id][$field_id]['data'] : '';
		$field_datas = (!empty($field_data['data'])) ? unserialize(stripslashes($field_data['data'])) : array();

		if (!empty($field_datas))
		{
			foreach($field_datas as $key => $value)
			{
				$template->assign_block_vars('select.row', array('FIELD_VALUE' => $value,
						'FIELD_SELECTED' => ($data == $value) ? ' selected="selected"' : '')
					);
			}
		}
	}

	function display_edit_select_multiple($file_id, $field_id, $field_data)
	{
		global $template;
		$template->assign_block_vars('select_multiple', array('FIELD_NAME' => $field_data['custom_name'],
				'FIELD_ID' => $field_data['custom_id'],
				'FIELD_DESCRIPTION' => $field_data['custom_description'])
			);

		$data = (!empty($this->field_data_rowset[$file_id][$field_id]['data'])) ? unserialize($this->field_data_rowset[$file_id][$field_id]['data']) : array();
		$field_datas = (!empty($field_data['data'])) ? unserialize(stripslashes($field_data['data'])) : array();

		if (!empty($field_datas))
		{
			foreach($field_datas as $key => $value)
			{
				$selected = '';
				foreach($data as $field_value)
				{
					if ($field_value == $value)
					{
						$selected = '  selected="selected"';
						break;
					}
				}
				$template->assign_block_vars('select_multiple.row', array('FIELD_VALUE' => $value,
						'FIELD_SELECTED' => $selected)
					);
			}
		}
	}

	function display_edit_checkbox($file_id, $field_id, $field_data)
	{
		global $template;
		$template->assign_block_vars('checkbox', array('FIELD_NAME' => $field_data['custom_name'],
				'FIELD_ID' => $field_data['custom_id'],
				'FIELD_DESCRIPTION' => $field_data['custom_description'])
			);

		$data = (!empty($this->field_data_rowset[$file_id][$field_id]['data'])) ? unserialize($this->field_data_rowset[$file_id][$field_id]['data']) : array();
		$field_datas = (!empty($field_data['data'])) ? unserialize(stripslashes($field_data['data'])) : array();

		if (!empty($field_datas))
		{
			foreach($field_datas as $key => $value)
			{
				$checked = '';
				foreach($data as $field_value)
				{
					if ($field_value == $value)
					{
						$checked = ' checked';
						break;
					}
				}
				$template->assign_block_vars('checkbox.row', array('FIELD_VALUE' => $value,
						'FIELD_CHECKED' => $checked)
					);
			}
		}
	}

	function update_add_field($field_type, $field_id = false)
	{
		global $db, $db, $lang;

		$field_name = (isset($_POST['field_name'])) ? htmlspecialchars($_POST['field_name']) : '';
		$field_desc = (isset($_POST['field_desc'])) ? htmlspecialchars($_POST['field_desc']) : '';
		$regex = (isset($_POST['regex'])) ? $_POST['regex'] : '';
		$data = (isset($_POST['data'])) ? $_POST['data'] : '';
		$field_order = (isset($_POST['field_order'])) ? $_POST['field_order'] : '';

		if ($field_id)
		{
			$field_order = (isset($_POST['field_order'])) ? intval($_POST['field_order']) : '';
		}

		if (!empty($data))
		{
			$data = explode("\n", htmlspecialchars(trim($data)));

			foreach($data as $key => $value)
			{
				$data[$key] = trim($value);
			}
			$data = addslashes(serialize($data));
		}

		if (empty($field_name))
		{
			mx_message_die(GENERAL_ERROR, $lang['Missing_field']);
		}

		if ((($field_type != INPUT && $field_type != TEXTAREA) && empty($data)))
		{
			mx_message_die(GENERAL_ERROR, $lang['Missing_field']);
		}

		if (!$field_id)
		{
			$sql = "INSERT INTO " . KB_CUSTOM_TABLE . " (custom_name, custom_description, data, regex, field_type)
				VALUES('" . $field_name . "', '" . $field_desc . "', '" . $data . "', '" . $regex . "', '" . $field_type . "')";

			if (!($db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, 'Could not add the new fields', '', __LINE__, __FILE__, $sql);
			}

			$field_id = $db->sql_nextid();

			$sql = "UPDATE " . KB_CUSTOM_TABLE . "
				SET field_order = '$field_id'
				WHERE custom_id = $field_id";

			if (!($db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, 'Could not set the order for the giving field', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "UPDATE " . KB_CUSTOM_TABLE . "
				SET custom_name = '$field_name', custom_description = '$field_desc', data = '$data', regex = '$regex', field_order='$field_order'
				WHERE custom_id = $field_id";

			if (!($db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, 'Could not update information for the giving field', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	function delete_field($field_id)
	{
		global $db;

		$sql = "DELETE FROM " . KB_CUSTOM_DATA_TABLE . "
			WHERE customdata_custom = '$field_id'";

		if (!($db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, 'Could not delete custom data', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . KB_CUSTOM_TABLE . "
			WHERE custom_id = '$field_id'";

		if (!($db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, 'Could not delete the selected field', '', __LINE__, __FILE__, $sql);
		}
	}

	function get_field_data($field_id)
	{
		$return_array = $this->field_rowset[$field_id];
		$return_array['data'] = !empty($return_array['data']) ? implode("\n", unserialize(stripslashes($return_array['data']))) : '';
		return $return_array;
	}

	// ===================================================
	// file data in custom field operations
	// ===================================================
	function file_update_data($file_id)
	{
		global $db;
		$field = (isset($_POST['field'])) ? $_POST['field'] : '';
		if (!empty($field))
		{
			foreach($field as $field_id => $field_data)
			{
				if (!empty($this->field_rowset[$field_id]['regex']))
				{
					if (!preg_match('#' . $this->field_rowset[$field_id]['regex'] . '#siU', $field_data))
					{
						$field_data = '';
					}
				}

				switch ($this->field_rowset[$field_id]['field_type'])
				{
					case INPUT:
					case TEXTAREA:
					case RADIO:
					case SELECT:
						$data = htmlspecialchars($field_data);
						break;
					case SELECT_MULTIPLE:
					case CHECKBOX:
						$data = addslashes(serialize($field_data));
						break;
				}

				$sql = "DELETE FROM " . KB_CUSTOM_DATA_TABLE . "
					WHERE customdata_file = '$file_id'
					AND customdata_custom = '$field_id'";

				if (!$db->sql_query($sql))
				{
					mx_message_die(GENERAL_ERROR, 'Could not delete data from custom data table', '', __LINE__, __FILE__, $sql);
				}

				if (!empty($data))
				{
					$sql = "INSERT INTO " . KB_CUSTOM_DATA_TABLE . " (customdata_file, customdata_custom, data)
						VALUES('$file_id', '$field_id', '$data')";

					if (!$db->sql_query($sql))
					{
						mx_message_die(GENERAL_ERROR, 'Could not add additional data', '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}
	}
}

?>