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

$result_cache->init_result_cache();

// Administrative Statistics
$template->assign_vars(array(
	'L_ADMIN_STATISTICS' => $lang['module_name_admin_statistics'],
	'L_STATISTIC' => $lang['Statistic'],
	'L_VALUE' => $lang['Value']
	)
);

// Attachment mod is installed, so we force these options...
$attachment_mod_installed = true;
$attachment_version = ATTACH_VERSION;
@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_admin.' . PHP_EXT);

$total_topics = $config['max_topics'];
$total_posts = $config['max_posts'];
$total_users = $config['max_users'];
$newest_user = $cache->obtain_newest_user();
$newest_uid = $config['last_user_id'];

$start_date = create_date($config['default_dateformat'], $config['board_startdate'], $config['board_timezone']);

$boarddays = max(1, round((time() - $config['board_startdate']) / 86400));

$posts_per_day = sprintf('%.2f', $total_posts / $boarddays);
$topics_per_day = sprintf('%.2f', $total_topics / $boarddays);
$users_per_day = sprintf('%.2f', $total_users / $boarddays);

$avatar_dir_size = 0;

if ($avatar_dir = @opendir(IP_ROOT_PATH . $config['avatar_path']))
{
	while($file = @readdir($avatar_dir))
	{
		if(($file != '.') && ($file != '..'))
		{
			$avatar_dir_size += @filesize(IP_ROOT_PATH . $config['avatar_path'] . '/' . $file);
		}
	}
	@closedir($avatar_dir);

	//
	// This bit of code translates the avatar directory size into human readable format
	// Borrowed the code from the PHP.net annoted manual, origanally written by:
	// Jesse (jesse@jess.on.ca)
	//
	if (!$attachment_mod_installed)
	{
		if($avatar_dir_size >= 1048576)
		{
			$avatar_dir_size = round($avatar_dir_size / 1048576 * 100) / 100 . ' MB';
		}
		elseif($avatar_dir_size >= 1024)
		{
			$avatar_dir_size = round($avatar_dir_size / 1024 * 100) / 100 . ' KB';
		}
		else
		{
			$avatar_dir_size = $avatar_dir_size . ' Bytes';
		}
	}
	else
	{
		if($avatar_dir_size >= 1048576)
		{
			$avatar_dir_size = round($avatar_dir_size / 1048576 * 100) / 100 . ' ' . $lang['MB'];
		}
		elseif($avatar_dir_size >= 1024)
		{
			$avatar_dir_size = round($avatar_dir_size / 1024 * 100) / 100 . ' ' . $lang['KB'];
		}
		else
		{
			$avatar_dir_size = $avatar_dir_size . ' ' . $lang['Bytes'];
		}
	}

}
else
{
	$avatar_dir_size = $lang['Not_available'];
}

if ($posts_per_day > $total_posts)
{
	$posts_per_day = $total_posts;
}

if ($topics_per_day > $total_topics)
{
	$topics_per_day = $total_topics;
}

if ($users_per_day > $total_users)
{
	$users_per_day = $total_users;
}

//
// DB size ... MySQL only
//
// This code is heavily influenced by a similar routine in phpMyAdmin 2.2.0
//
if (!$statistics->result_cache_used)
{
	$dbsize = 0;

	if(preg_match("/^mysql/", SQL_LAYER))
	{
		$sql = "SELECT VERSION() AS mysql_version";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			$row = $db->sql_fetchrow($result);
			$version = $row['mysql_version'];

			if(preg_match("/^(3\.23|4\.)/", $version))
			{
				$db_name = (preg_match("/^(3\.23\.[6-9])|(3\.23\.[1-9][1-9])|(4\.)/", $version)) ? "`$dbname`" : $dbname;

				$sql = "SHOW TABLE STATUS
				FROM " . $db_name;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if ($result)
				{
					$tabledata_ary = $db->sql_fetchrowset($result);

					$dbsize = 0;
					for($i = 0; $i < sizeof($tabledata_ary); $i++)
					{
						if($tabledata_ary[$i]['Type'] != "MRG_MyISAM")
						{
							if($table_prefix != "")
							{
								if(strstr($tabledata_ary[$i]['Name'], $table_prefix))
								{
									$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
							}
							else
							{
								$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
							}
						}
					}
				}
			}
		}
	}

	$result_cache->assign_vars(array('dbsize' => $dbsize));
}
else
{
	$dbsize = $result_cache->get_var('dbsize');
}

$dbsize = intval($dbsize);

if ($dbsize != 0)
{
	$dbsize = format_file_size($dbsize);
}
else
{
	$dbsize = $lang['Not_available'];
}

$sql = 'SELECT user_regdate
	FROM ' . USERS_TABLE . '
	WHERE user_id = ' . $newest_uid . '
	LIMIT 1';
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrow($result);
$newest_user_date = $row['user_regdate'];

// Most Online data
$sql = "SELECT *
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'record_online_users' OR config_name = 'record_online_date'";
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrowset($result);
$most_users_date = $lang['Not_available'];
$most_users = $lang['Not_available'];

for ($i = 0; $i < sizeof($row); $i++)
{
	if ((intval($row[$i]['config_value']) > 0) && ($row[$i]['config_name'] == 'record_online_date'))
	{
		$most_users_date = create_date($config['default_dateformat'], intval($row[$i]['config_value']), $config['board_timezone']);
	}
	elseif ((intval($row[$i]['config_value']) > 0) && ($row[$i]['config_name'] == 'record_online_users'))
	{
		$most_users = intval($row[$i]['config_value']);
	}
}

$statistic_array = array($lang['Number_posts'], $lang['Posts_per_day'], $lang['Number_topics'], $lang['Topics_per_day'], $lang['Number_users'], $lang['Users_per_day'], $lang['Board_started'], $lang['Board_Up_Days'], $lang['Database_size'], $lang['Avatar_dir_size'], $lang['Latest_Reg_User_Date'], $lang['Latest_Reg_User'], $lang['Most_Ever_Online_Date'], $lang['Most_Ever_Online'], $lang['Gzip_compression']);

$value_array = array($total_posts, $posts_per_day, $total_topics, $topics_per_day, $total_users, $users_per_day, $start_date, sprintf('%.2f', $boarddays), $dbsize, $avatar_dir_size, create_date($config['default_dateformat'], $newest_user_date, $config['board_timezone']), $newest_user, $most_users_date, $most_users, (($config['gzip_compress']) ? $lang['Enabled'] : $lang['Disabled']));

// Disk Usage, if Attachment Mod is installed
if ($attachment_mod_installed)
{
	$disk_usage = get_formatted_dirsize();

	$statistic_array[] = $lang['Disk_usage'];
	$value_array[] = $disk_usage;
}

$template->_tpldata['adminrow.'] = array();
//reset($template->_tpldata['adminrow.']);

for ($i = 0; $i < sizeof($statistic_array); $i += 2)
{
	$template->assign_block_vars('adminrow', array(
		'STATISTIC' => $statistic_array[$i],
		'VALUE' => $value_array[$i],
		'STATISTIC2' => (isset($statistic_array[$i+1])) ? $statistic_array[$i + 1] : '',
		'VALUE2' => (isset($value_array[$i+1])) ? $value_array[$i + 1] : ''
		)
	);
}

?>