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
* Nuffmon (nuffmon@hotmail.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

function hms($sec)
{
	$thetime = str_pad(intval(intval($sec) / 3600), 2, "0", STR_PAD_LEFT) . ":" . str_pad(intval(($sec / 60) % 60), 2, "0", STR_PAD_LEFT) . ":" . str_pad(intval($sec % 60), 2, "0", STR_PAD_LEFT) ;
	return $thetime;
}

// Check session_id and monitor upload or quit
if(isset($_REQUEST['sessionid']))
{
	// Set unlimited timeout
	set_time_limit(0);
	$start_time = time(); //Set start time as now
	$sessionid = $_REQUEST['sessionid'];

	// Path to data files
	$info_file = $album_config['path_to_bin'] . "tmp/$sessionid"."_flength";
	$data_file = $album_config['path_to_bin'] . "tmp/$sessionid"."_postdata";
	//$signal_file = $album_config['path_to_bin'] . "tmp/$sessionid"."_signal";

	// Dump page header
	$gen_simple_header = true;
	$page_title = $lang['upload_in_progress'];
	$meta_description = '';
	$meta_keywords = '';
	if(!$album_config['simple_format'])
	{
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	}

	// Load template
	$template->set_filenames(array('body' => 'album_nuffload_pbar_body.tpl'));

	// Load template variable
	$template->assign_vars(array(
		'L_ALBUM' => $lang['album'],
		'L_UPLOAD_PIC' => $lang['Upload_Pic'],
		'L_UPLOAD_IN_PROGRESS' => $lang['upload_in_progress'],
		'L_TIME_ELAPSED' => $lang['time_elapsed'],
		'L_TIME_REMAINING' => $lang['time_remaining'],
		'L_NUFFLOAD_VERSION' => 'v1.4.2'
		)
	);

	//Output page
	$template->pparse('body');

	$template->set_filenames(array(
		'overall_footer' => 'simple_footer.tpl'
		)
	);

	$template->pparse('overall_footer');

	$db->sql_close();

	// Loop/monitor filesize until complete
	$upload_started = false;
	for(;$percent_done < 100;)
	{
		// Open info file to find filesize
		// info file created by perl script
		if (intval($total_size) <= 0)
		{
			if ($fp = @fopen($info_file,"r"))
			{
				$fd = fread($fp,1000);
				fclose($fp);
				$total_size = $fd;
			}
		}

		$time_elapsed = time()- $start_time;
		$previous_size = $current_size;
		clearstatcache();
		if (file_exists($data_file))
		{
			$current_size = filesize($data_file);
			$upload_started = true;
		}
		else
		{
			if ($upload_started)
			{
				?>
				<script type="text/javascript">
					<!--
						top.close();
					// -->
				</script>
				<?php
				exit;
			}
		}
		// This section checks for no activity and stops processing
		if ($previous_size < $current_size)
		{
			$activity_time = 0;
		}
		else
		{
			$activity_time++;
		}
		if ($activity_time >= $album_config['max_pause'])
		{
			?>
			<script type="text/javascript">
				<!--
					top.close();
				// -->
			</script>
			<?php
			exit;
		}

		// Calculate progress values if upload started.
		if ($current_size > 0 && $time_elapsed > 0)
		{
			$percent_done = sprintf("%.0f",($current_size / $total_size) * 100);
			$speed = ($current_size / $time_elapsed);
			if ($speed == 0) {$speed = 1024;}
			$time_remain_str = hms(($total_size-$current_size) / $speed);
			$time_elapsed_str = hms($time_elapsed);
		}
		if ($percent_done < 1)
		{
			$percent_done = 1;
		}
		?>
		<script type="text/javascript">
			<!--
				document.getElementById("progress1").width = "<?php print $percent_done; ?>%";
				document.getElementById("progress2").innerHTML = '<? echo $current_size; ?>/<? echo $total_size; ?> (<? echo $percent_done; ?>%) <? echo printf("%.2f",$speed/1024); ?> kbit/s<br /><? echo $lang['time_elapsed'] . ": " . $time_elapsed_str; ?><br /><? echo $lang['time_remaining'] . ": " . $time_remain_str; ?>';
			// -->
		</script>
<?php
		ob_flush();
		flush();
		sleep(1);
	}

	//  Now we have finished we can delete the data files
	/*
	@unlink($info_file);
	@unlink($data_file);
	@unlink($signal_file);
	*/

	// Send javascript to close form if required
	if ($album_config['close_on_finish'])
	{
		?>
		<script type="text/javascript">
			<!--
				top.close();
			// -->
		</script>
		<?php
	}
}
?>