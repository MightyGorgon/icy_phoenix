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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('imp_referers_block_func'))
{
	function imp_referers_block_func()
	{
		global $template, $db;

		$template->_tpldata['linkrow1.'] = array();
		$template->_tpldata['linkrow2.'] = array();

		$sql = "SELECT COUNT(DISTINCT referrer_host) AS count
						FROM " . REFERRERS_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query referrers table", "Error", __LINE__, __FILE__, $sql);
		}
		$total_referers = (int) $db->sql_fetchfield('count', 0, $result);

		// Query referer info...
		$sql = "SELECT DISTINCT referrer_host, SUM(referrer_hits) AS referrer_hits, MIN(referrer_firstvisit) AS referrer_firstvisit, MAX(referrer_lastvisit) AS referrer_lastvisit
			FROM " . REFERRERS_TABLE . "
			GROUP BY referrer_host
			ORDER BY referrer_host";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not obtain HTTP referrers', '', __LINE__, __FILE__, $sql);
		}

		$i = 0;
		while($row = $db->sql_fetchrow($result))
		{
			//2nd column
			if($i >= $total_referers / 2)
			{
				$template->assign_block_vars('linkrow2', array(
					'U_REF_LINK' => htmlspecialchars('http://' . $row['referrer_host']),
					'LINK_TEXT' => htmlspecialchars('http://' . $row['referrer_host'])
					)
				);
			}
			else //1st column
			{
				$template->assign_block_vars('linkrow1', array(
					'U_REF_LINK' => htmlspecialchars('http://' . $row['referrer_host']),
					'LINK_TEXT' => htmlspecialchars('http://' . $row['referrer_host'])
					)
				);
			}
			$i++;
		}
	}
}

imp_referers_block_func();

?>