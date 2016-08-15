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

if(!function_exists('cms_block_referers'))
{
	function cms_block_referers()
	{
		global $db, $template;

		$template->_tpldata['linkrow1.'] = array();
		$template->_tpldata['linkrow2.'] = array();

		$sql = "SELECT COUNT(DISTINCT host) AS count
						FROM " . REFERERS_TABLE;
		$result = $db->sql_query($sql);
		$total_referers = (int) $db->sql_fetchfield('count', 0, $result);
		$db->sql_freeresult($result);

		// Query referer info...
		$sql = "SELECT DISTINCT host, SUM(hits) AS hits, MIN(firstvisit) AS firstvisit, MAX(lastvisit) AS lastvisit
			FROM " . REFERERS_TABLE . "
			GROUP BY host
			ORDER BY host";
		$result = $db->sql_query($sql);

		$i = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$server_protocol = !empty($config['cookie_secure']) ? 'https://' : 'http://';
			$link_ref = htmlspecialchars('http://' . $row['host']);
			$link_text = htmlspecialchars('http://' . $row['host']);
			//2nd column
			if($i >= $total_referers / 2)
			{
				$template->assign_block_vars('linkrow2', array(
					'U_REF_LINK' => $link_ref,
					'LINK_TEXT' => $link_text
					)
				);
			}
			else //1st column
			{
				$template->assign_block_vars('linkrow1', array(
					'U_REF_LINK' => $link_ref,
					'LINK_TEXT' => $link_text
					)
				);
			}
			$i++;
		}
		$db->sql_freeresult($result);
	}
}

cms_block_referers();

?>