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
* Chris Lennert - (calennert@users.sourceforge.net) - (http://lennertmods.sourceforge.net)
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

/******************************************************************************
 * Creates a new property in the CONFIG_TABLE
 ******************************************************************************/
function create_property($property_name, $property_value)
{
	global $db;

	/* Add a new group to the groups table using the admin retrieved above */
	$sql = "INSERT INTO . " . CONFIG_TABLE . "(config_name, config_value) VALUES ('" . $property_name . "', '" . $property_value . "')";
	//$sql = "UPDATE . " . CONFIG_TABLE . " SET config_value = '" . $property_value . "' WHERE config_name = '" . $property_name . "'";

	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Function create_property(): Failed to insert $property_name property into " . CONFIG_TABLE, "", __LINE__, __FILE__, $sql);
	}

	return true;
}

/******************************************************************************
 * Sets the property to the specified value.  Creates the property record
 * in the config table, if necessary.
 ******************************************************************************/
function set_bb_usage_stats_property($property_name, $property_value)
{
	global $db;
	$db_not_found = -999;

	/* First, determine if the $property_name row exists in the config table. */
	$db_value = $db_not_found;
	$sql = "SELECT config_name, config_value FROM " . CONFIG_TABLE . " WHERE config_name = '$property_name'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Function set_bb_usage_stats_property(): Unable to obtain $property_name information from the' . CONFIG_TABLE . 'table', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		$db_value = $row['config_value'];
	}
	$db->sql_freeresult($result);
	unset($sql);

	/* Second, if no value was retrieved from the DB, property needs to be created
	 * (i.e., row needs to be inserted).
	 */
	if ( $db_value == $db_not_found )
	{
		create_property($property_name, $property_value);
	}
	/* OR, if retrieved value is different than specified $property_value, update DB with new property value */
	elseif ( $db_value != $property_value )
	{
		$sql = 'UPDATE ' . CONFIG_TABLE . " SET config_value = $property_value WHERE config_name = '" . $property_name . "'";

		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR,
				'Function set_bb_usage_stats_property(): Failed to update the $property_name row info in the' . CONFIG_TABLE . 'table', '', __LINE__, __FILE__, $sql);
		}
	}

	/* Return */
	return true;
}


/******************************************************************************
 * Creates the select list for post rate and topic rate scaling
 ******************************************************************************/
function scaleby_select($form_name, $select_name, $scale_start, $scale_end, $value_selected) {
	$selected_attribute = 'selected="selected"';

	$select_text = "<select name=\"$select_name\"";

	if ($form_name != '')
	{
		$select_text .= " onchange=\"if(this.options[this.selectedIndex].value != $value_selected ){ forms['$form_name'].submit() }\">";
	}
	else
	{
		$select_text .= ">";
	}

	/* Add factor-of-ten scale values to pull-down list */
	for ($scale = $scale_start; $scale < ($scale_end * 10); )
	{
		$selected = ( $scale == $value_selected ) ? $selected_attribute : '';
		$select_text .= "<option value=\"$scale\" $selected>$scale</option>";
		$scale *= 10;
	}

	$select_text .= '</select>';

	return $select_text;
}

/******************************************************************************
 * Determines whether user is member of group
 ******************************************************************************/
function is_user_member_of_group($user_id, $group_id)
{
	global $db;

	/* Retrieve forum topic start data from database */
	$sql = 'SELECT group_id, user_id FROM ' . USER_GROUP_TABLE . " WHERE group_id = $group_id AND user_id = $user_id";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Function is_user_member_of_group(): Could not obtain user/group membership data.', '', __LINE__, __FILE__, $sql);
	}

	$retval = false;
	if ( $row = $db->sql_fetchrow($result) )
	{
		$retval = true;
	}

	$db->sql_freeresult($result);
	unset($sql);

	/* Return results */
	return $retval;
}

?>