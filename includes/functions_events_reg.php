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
* NetizenKane (info@fragthe.net)
* KugeLSichA (http://www.caromonline.de)
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function check_max_registration($topic_id, $regstate)
{
	global $db;

	$sql = "SELECT reg_max_option1, reg_max_option2, reg_max_option3 FROM " . REGISTRATION_DESC_TABLE ."
					WHERE topic_id = $topic_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$reg_max_option1 = $row['reg_max_option1'];
	$reg_max_option2 = $row['reg_max_option2'];
	$reg_max_option3 = $row['reg_max_option3'];

	$num_max_reg = 0;
	if (($regstate == 1) && ($reg_max_option1 > 0))
	{
		$num_max_reg = $reg_max_option1;
	}
	elseif (($regstate == 2) && ($reg_max_option2 > 0))
	{
		$num_max_reg = $reg_max_option2;
	}
	elseif (($regstate == 3) && ($reg_max_option3 > 0))
	{
		$num_max_reg = $reg_max_option3;
	}

	if ($num_max_reg > 0)
	{
		// we have to check if maximum of accepted registrations is already reached
		$sql = "SELECT COUNT(registration_status) cnt_status FROM " . REGISTRATION_TABLE . "
						WHERE topic_id = $topic_id AND registration_status = $regstate";
		$result = $db->sql_query($sql);
		$cnt_status = $db->sql_fetchfield('cnt_status', 0, $result);

		if ($cnt_status >= $num_max_reg)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

function check_slots_left($topic_id, $regstate)
{
	global $db;

	// check if maximum amount of registrations is reached
	$sql = "SELECT reg_max_option1, reg_max_option2, reg_max_option3 FROM " . REGISTRATION_DESC_TABLE ."
					WHERE topic_id = $topic_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$reg_max_option1 = $row['reg_max_option1'];
	$reg_max_option2 = $row['reg_max_option2'];
	$reg_max_option3 = $row['reg_max_option3'];

	$num_max_reg = 0;
	if (($regstate == 1) && ($reg_max_option1 > 0))
	{
		$num_max_reg = $reg_max_option1;
	}
	elseif (($regstate == 2) && ($reg_max_option2 > 0))
	{
		$num_max_reg = $reg_max_option2;
	}
	elseif (($regstate == 3) && ($reg_max_option3 > 0))
	{
		$num_max_reg = $reg_max_option3;
	}

	if ($num_max_reg == 0)
	{
		return -1;
	}
	else
	{
		// we have to check the current amount of registrations for this option
		$sql = "SELECT COUNT(registration_status) cnt_status FROM " . REGISTRATION_TABLE . "
						WHERE topic_id = $topic_id
						AND registration_status = $regstate";
		$result = $db->sql_query($sql);
		$cnt_status = $db->sql_fetchfield('cnt_status', 0, $result);

		$slots_left = ($num_max_reg - $cnt_status);
		return $slots_left;
	}
}

function check_user_registered($topic_id, $user_id, $regstate)
{
	global $db;
	$sql = "SELECT COUNT(registration_status) cnt_status FROM " . REGISTRATION_TABLE . "
					WHERE topic_id = $topic_id
					AND registration_status = $regstate
					AND registration_user_id = $user_id";
	$result = $db->sql_query($sql);
	$cnt_status = $db->sql_fetchfield('cnt_status', 0, $result);

	if ($cnt_status > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function check_reg_active($topic_id)
{
	global $db;
	$sql = "SELECT reg_active FROM " . REGISTRATION_DESC_TABLE . "
					WHERE topic_id = $topic_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result || (1 != $db->sql_fetchfield('reg_active', 0, $result)))
	{
		return false;
	}
	return true;
}

?>