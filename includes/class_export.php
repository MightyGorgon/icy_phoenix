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

class class_export
{
	var $export_type = 'CSV';
	var $export_filename = 'data';

	var $export_mimetype = 'text/csv';
	var $export_filetype = 'csv';

	var $field_delimiter = ',';
	var $text_delimiter = '"';
	var $decimal_delimiter = '.';

	var $field_names = array();
	var $field_values = array();

	function class_export()
	{
	}

	/*
	* Main function to export content as a file downloadable into a browser
	*/
	function export($field_names, $field_values)
	{
		$this->field_names = $field_names;
		$this->field_values = $field_values;

		$string = '';
		switch ($this->export_type)
		{
			case 'CSV':
				$string = $this->export_csv(false);
			break;
			case 'CSV_EXCEL':
				$string = $this->export_csv(true);
			break;
			default:
				// Use standard CSV as a default!
				$string = $this->export_csv(false);
		}

		header('Pragma: no-cache');
		header('Content-Type: ' . $this->export_mimetype . '; name="' . $this->export_filename . '.' . $this->export_filetype . '"');
		header('Content-disposition: attachment; filename=' . $this->export_filename . '.' . $this->export_filetype);
		echo $string;
		exit;
	}

	/*
	* Export as a CSV
	*/
	function export_csv($csv_excel)
	{
		$this->export_mimetype = 'text/csv';
		$this->export_filetype = 'csv';

		$newline = ($csv_excel) ? "\r\n" : "\n";

		$string = '';

		if (($this->decimal_delimiter == ',') && ($this->field_delimiter == $this->decimal_delimiter))
		{
			$this->field_delimiter = ';';
			$this->decimal_delimiter = ',';
		}

		if (sizeof($this->field_names) > 0)
		{
			foreach ($this->field_names as $field_name)
			{
				$string .= $this->text_delimiter . $field_name . $this->text_delimiter . $this->field_delimiter;
			}
			$string = substr($string, 0, -1) . $newline;
		}

		if (sizeof($this->field_values) > 0)
		{
			foreach ($this->field_values as $field_value)
			{
				foreach ($field_value as $field_id => $field_data)
				{
					foreach ($field_data as $field_type => $field_content)
					{
						$text_delimiter = (($field_type == 'str') ? $this->text_delimiter : '');
						$field_content = ((($field_type == 'val') && ($this->decimal_delimiter == ',')) ? str_replace('.', ',', $field_content) : $field_content);
						$string .= $text_delimiter . $field_content . $text_delimiter . $this->field_delimiter;
					}
				}
				$string = substr($string, 0, -1) . $newline;
			}
		}

		return $string;
	}

	/*
	* Export as a CSV file
	*/
	function export_csv_file($handle)
	{
		$fp = @fopen($handle, 'w');
		foreach ($this->field_values as $datarow)
		{
			@fputcsv($fp, $datarow, $this->field_delimiter, $this->text_delimiter);
		}
		@fclose($fp);
	}

}

?>