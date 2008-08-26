<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human

function get_fields($where_clause = '', $expect_multiple = true, $selection = '*')
{
	global $db;

	$sql = "SELECT $selection FROM " . PROFILE_FIELDS_TABLE . "
		$where_clause
		ORDER BY field_id ASC";
	if(!($result = $db->sql_query($sql, false, 'profile_fields_')))
	{
		message_die(GENERAL_ERROR,'Could not select from ' . PROFILE_FIELDS_TABLE,'',__LINE__,__FILE__,$sql);
	}

	if($expect_multiple)
	{
		$profile_data = array();
		while($temp = $db->sql_fetchrow($result))
		{
			if(!empty($temp))
			{
				$profile_data[] = $temp;
			}
		}
	}
	else
	{
		$profile_data = $db->sql_fetchrow($result);
	}

	return $profile_data;
}

function text_to_column($text)
{
	$text = utf8_decode($text);
	$pattern = array("#&quot;#", "#&amp;#", "#&lt;#", "#&gt;#");
	$replace = array('"', '&', '<', '>');
	$text = preg_replace($pattern, $replace,$text);
	$pattern = "#[\s\*\$\(\)!\.,\-\?\/\\\[\]\{\};\:'´`\"&\^+=<>\|]#";
	$replace = "_";
	$text = preg_replace($pattern, $replace,$text);
	return strtolower($text);
}

function displayable_field_data($data, $type)
{
	global $lang;
	switch($type)
	{
		case TEXTAREA:
			return str_replace("\r\n","<br />",$data);
			break;
		case TEXT_FIELD:
		case RADIO:
			return $data;
			break;
		case CHECKBOX:
			$data_list = explode(',',$data);
			$tmp = array();
			foreach($data_list as $val)
			{
				if(!empty($val))
				{
					$tmp[] = $val;
				}
			}
			$data_list = $tmp;
			$list_size = count($data_list);
			$data = str_replace(',',', ',$data);

			if($list_size == 0)
			{
				return '';
			}
			elseif($list_size == 1)
			{
				return $data_list[0];
			}
			else
			{
				return substr($data,0,strrpos($data,', ')) . $lang['and'] . substr($data,strrpos($data,', ') + 2);
			}
	}
}

function get_topic_udata($postrow_data, $profile_data)
{
	global $lang;
	static $cp_udata_cache;

	$id = $postrow_data['user_id'];

	if (!$cp_udata_cache[$id])
	{
		$profile_names = array();
		$cp_udata_cache[$id]['aboves'] = array();
		$cp_udata_cache[$id]['belows'] = array();
		$cp_udata_cache[$id]['author'] = array();
		foreach($profile_data as $field)
		{
			$name = $field['field_name'];
			$col_name = text_to_column($field['field_name']);
			$type = $field['field_type'];
			$location = $field['topic_location'];

			$field_id = $field['field_id'];
			$field_name = $field['field_name'];
			if (isset($lang[$field_id . '_' . $field_name]))
			{
				$field_name = $lang[$field_id . '_' . $field_name];
			}

			$profile_names[$name] = displayable_field_data($postrow_data[$col_name], $field['field_type']);
			$tmp_field = $profile_names[$name];
			if (isset($lang[$field_id . '_' . $tmp_field]))
			{
				$profile_names[$name] = $lang[$field_id . '_' . $tmp_field];
			}

			if($location == AUTHOR)
			{
				//$cp_udata_cache[$id]['author'][] = ($profile_names[$name]) ? $name . ': ' . $profile_names[$name] : '';
				$cp_udata_cache[$id]['author'][] = ($profile_names[$name]) ? $field_name . ': ' . $profile_names[$name] : '';
			}
			elseif($location == ABOVE_SIGNATURE)
			{
				//$cp_udata_cache[$id]['aboves'][] = ($profile_names[$name]) ? $name . ': ' . $profile_names[$name] : '';
				$cp_udata_cache[$id]['aboves'][] = ($profile_names[$name]) ? $field_name . ': ' . $profile_names[$name] : '';
			}
			else
			{
				//$cp_udata_cache[$id]['belows'][] = ($profile_names[$name]) ? $name . ': ' . $profile_names[$name] : '';
				$cp_udata_cache[$id]['belows'][] = ($profile_names[$name]) ? $field_name . ': ' . $profile_names[$name] : '';
			}
		}
	}

	return $cp_udata_cache[$id];
}

function get_udata_txt($profile_data, $add = '')
{
	$cp_sql_txt = '';
	foreach($profile_data as $field)
	{
		$cp_sql_txt .= ', ' . $add . text_to_column($field['field_name']);
	}

	return $cp_sql_txt;
}

?>