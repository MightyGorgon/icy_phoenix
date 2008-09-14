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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if ($cancel)
{
	$action = '';
}

$cat_id = $dl_files['cat'];

/*
* Read the ratings for this little download
*/
$sql = "SELECT dl_id, user_id FROM " . DL_RATING_TABLE . "
	WHERE dl_id = $df_id";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not fetch ratings', '', __LINE__, __FILE__, $sql);
}

$ratings = 0;
$rating_access = true;
while ($row = $db->sql_fetchrow($result))
{
	$ratings++;
	if ($userdata['user_id'] == $row['user_id'])
	{
		$rating_access = 0;
	}
}
$db->sql_freeresult($result);

if (!$userdata['session_logged_in'] || $userdata['user_id'] == ANONYMOUS)
{
	$rating_access = 0;
}

if (!$rating_access)
{
	if ($dlo == 1 && $action)
	{
		redirect(append_sid('downloads.' . PHP_EXT . '?view=overall'));
	}
	elseif (!$dlo && $action)
	{
		redirect(append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id));
	}

	$action = '';
}

$rating = $s_hidden_fields = '';

if ($action == 'rate' && (($userdata['session_logged_in'] && $userdata['user_id'] != ANONYMOUS)))
{
	$rating = '<select name="rate_point">';
	for ( $i = 1; $i <= 10; $i++ )
	{
		$rating .= '<option value="'.$i.'">'.$i.'</option>';
	}
	$rating .= '</select>';

	$s_hidden_fields_rate = '<input type="hidden" name="df_id" value="' . $df_id . '" />';
	$s_hidden_fields_rate .= '<input type="hidden" name="cat" value="' . $cat_id . '" />';
	$s_hidden_fields_rate .= '<input type="hidden" name="dlo" value="'.$dlo.'" />';
	$s_hidden_fields_rate .= '<input type="hidden" name="view" value="detail" />';
	$s_hidden_fields_rate .= '<input type="hidden" name="action" value="rate" />';
	$s_hidden_fields_rate .= '<input type="hidden" name="start" value="'.$start.'" />';
}

/*
* fetch last comment, if exists
*/
if ($index[$cat_id]['comments'] && $dl_mod->cat_auth_comment_read($cat_id))
{
	$template->assign_block_vars('comment_block', array());

	$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="view" value="comment" />';

	$template->assign_block_vars('comment_block.complete', array(
		'L_LAST_COMMENT' => $lang['Dl_last_comment'],
		'S_COMMENT_ACTION' => append_sid('downloads.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	$sql = "SELECT * FROM " . DL_COMMENTS_TABLE . "
		WHERE cat_id = $cat_id
			AND id = $df_id
			AND approve = " . TRUE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not fetch latest comment for this download', '', __LINE__, __FILE__, $sql);
	}

	$real_comment_exists = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($real_comment_exists)
	{
		$template->assign_block_vars('comment_block.complete.view_comments', array(
			'L_SHOW_COMMENTS' => $lang['Dl_comment_show']
			)
		);
	}

	$sql = "SELECT * FROM " . DL_COMMENTS_TABLE . "
		WHERE cat_id = $cat_id
			AND id = $df_id
			AND approve = " . TRUE . "
		ORDER BY comment_time DESC
		LIMIT 0, " . $dl_config['latest_comments'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not fetch latest comment for this download', '', __LINE__, __FILE__, $sql);
	}

	$comment_exists = $db->sql_numrows($result);
	if ($comment_exists)
	{
		$template->assign_block_vars('comment_block.complete.comments_on', array());

		if ($dl_config['latest_comments'])
		{
			$i = 0;
			while ($row = $db->sql_fetchrow($result))
			{
				$poster_id = $row['user_id'];
				$poster = $row['username'];

				$message = $row['comment_text'];
				$bbcode_uid = $row['bbcode_uid'];
				$comment_time = $row['comment_time'];
				$comment_edit_time = $row['comment_edit_time'];

				$orig_word = array();
				$replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);

				if(count($orig_word))
				{
					$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
				}

				/*
				if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'])
				{
					$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
				}

				if ($bbcode_uid != '')
				{
					$message = ($board_config['allow_bbcode']) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $message);
				}

				$message = make_clickable($message);

				if( $board_config['allow_smilies'] )
				{
					$message = smilies_pass($message);
				}
				*/

				$bbcode->allow_html = ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? true : false;
				$bbcode->allow_bbcode = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode'] ) ? true : false;
				$bbcode->allow_smilies = ( $userdata['user_allowsmile'] && $board_config['allow_smilies'] ) ? true : false;
				$message = $bbcode->parse($message, $bbcode_uid);

				$message = str_replace("\n", "\n<br />\n", $message);

				if($comment_time <> $comment_edit_time)
				{
					$edited_by = '<hr />' . sprintf($lang['Dl_comment_edited'], create_date($board_config['default_dateformat'], $comment_edit_time, $board_config['board_timezone']));
				}
				else
				{
					$edited_by = '';
				}

				/*
				if ($poster_id == ANONYMOUS)
				{
					$poster_url = '';
				}
				else
				{
					$poster_url = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $poster_id);
					$poster = '<a href="' . $poster_url . '">' . $poster . '</a>';
				}
				*/
				$poster = colorize_username($poster_id);

				$post_time = create_date($board_config['default_dateformat'], $comment_time, $board_config['board_timezone']);

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('comment_block.complete.comment_row', array(
					'ROW_CLASS' => $row_class,
					'EDITED_BY' => $edited_by,
					'POSTER' => $poster,
					'MESSAGE' => $message,
					'POST_TIME' => $post_time
					)
				);
				$i++;
			}
		}
	}

	if ($dl_mod->cat_auth_comment_post($cat_id))
	{
		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="view" value="comment" />';

		$template->assign_block_vars('comment_block.post', array(
			'L_POST_COMMENT' => $lang['Dl_comment_write'],

			'S_COMMENT_ACTION' => append_sid('downloads.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}
	$db->sql_freeresult($result);
}

/*
* generate page
*/
$template->set_filenames(array('body' => 'view_dl_body.tpl'));

$user_id = $userdata['user_id'];
$username = $userdata['username'];

/*
* prepare the download for displaying
*/
$description = $dl_files['description'];

$mini_icon = $dl_mod->mini_status_file($cat_id, $df_id);

$hack_version = '&nbsp;' . $dl_files['hack_version'];

$long_desc = stripslashes($dl_files['long_desc']);
/*
$long_desc = ($dl_files['bbcode_uid']) ? bbencode_second_pass($long_desc, $dl_files['bbcode_uid']) : $long_desc;
$long_desc = make_clickable(smilies_pass($long_desc));
*/
$bbcode->allow_html = ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? true : false;
$bbcode->allow_bbcode = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode'] ) ? true : false;
$bbcode->allow_smilies = ( $userdata['user_allowsmile'] && $board_config['allow_smilies'] ) ? true : false;
$long_desc = $bbcode->parse($long_desc, $dl_files['bbcode_uid']);
$long_desc = str_replace("\n", "\n<br />\n", $long_desc);

$file_status = array();
$file_status = $dl_mod->dl_status($df_id);
$status = $file_status['status_detail'];
$file_name = $file_status['file_detail'];
$file_load = $file_status['auth_dl'];

if ($dl_files['extern'])
{
	$file_size_out = $lang['Dl_not_availible'];

	if ($dl_config['shorten_extern_links'])
	{
		if (strlen($file_name) > $dl_config['shorten_extern_links'] && strlen($file_name) <= $dl_config['shorten_extern_links'] * 2)
		{
			$file_name = substr($file_name, strlen($file_name) - $dl_config['shorten_extern_links']);
		}
		else
		{
			$file_name = substr($file_name, 0, $dl_config['shorten_extern_links']) . '...' . substr($file_name, strlen($file_name) - $dl_config['shorten_extern_links']);
		}
	}
}
else
{
	$file_size_out = $dl_mod->dl_size($dl_files['file_size'], 2);
}

$file_klicks = $dl_files['klicks'];
$file_overall_klicks = $dl_files['overall_klicks'];

$cat_name = $index[$cat_id]['cat_name'];
$cat_view = $index[$cat_id]['nav_path'];
$cat_desc = $index[$cat_id]['description'];

$add_user = $add_time = '';
$change_user = $change_time = '';
$sql = "SELECT username, user_id FROM " . USERS_TABLE . "
	WHERE user_id = " . $dl_files['add_user'];
if ($result = $db->sql_query($sql))
{
	$row = $db->sql_fetchrow($result);
	//$add_user = ($row['user_id'] != '' || $row['user_id'] != ANONYMOUS) ? '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">'.$row['username'] . '</a>' : $lang['Guest'];
	$add_user = colorize_username($row['user_id']);
	$add_time = create_date($board_config['default_dateformat'], $dl_files['add_time'], $board_config['board_timezone']);
}
$db->sql_freeresult($result);

if ($dl_files['add_time'] != $dl_files['change_time'])
{
	$sql = "SELECT username, user_id FROM " . USERS_TABLE . "
		WHERE user_id = " . $dl_files['change_user'];
	if ($result = $db->sql_query($sql))
	{
		$row = $db->sql_fetchrow($result);
		//$change_user = ($row['user_id'] != '' || $row['user_id'] != ANONYMOUS) ? '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">'.$row['username'] . '</a>' : $lang['Guest'];
		$change_user = colorize_username($row['user_id']);
		$change_time = create_date($board_config['default_dateformat'], $dl_files['change_time'], $board_config['board_timezone']);
	}
	$db->sql_freeresult($result);
}

$last_time_string = ($dl_files['extern']) ? $lang['Dl_last_time_extern'] : $lang['Dl_last_time'];
$last_time = ($dl_files['last_time']) ? sprintf($last_time_string, create_date($board_config['default_dateformat'], $dl_files['last_time'], $board_config['board_timezone'])) : $lang['Dl_no_last_time'];

$hack_author_email = $dl_files['hack_author_email'];
$hack_author = ( $dl_files['hack_author'] != '' ) ? $dl_files['hack_author'] : 'n/a';
$hack_author_website = $dl_files['hack_author_website'];
$hack_dl_url = $dl_files['hack_dl_url'];

$test = $dl_files['test'];
$require = $dl_files['req'];
$todo = $dl_files['todo'];
$warning = $dl_files['warning'];
/*
$warning = ( $dl_files['bbcode_uid'] != '' ) ? smilies_pass(bbencode_second_pass(stripslashes($warning), $dl_files['bbcode_uid'])) : smilies_pass(stripslashes($warning));
$warning = make_clickable($warning);
*/
$warning = stripslashes($warning);
$bbcode->allow_html = ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? true : false;
$bbcode->allow_bbcode = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode'] ) ? true : false;
$bbcode->allow_smilies = ( $userdata['user_allowsmile'] && $board_config['allow_smilies'] ) ? true : false;
$warning = $bbcode->parse($warning, $dl_files['bbcode_uid']);
$warning = str_replace("\n", "\n<br />\n", $warning);

$mod_desc = $dl_files['mod_desc'];
$mod_list = $dl_files['mod_list'];
/*
$mod_desc = ( $dl_files['bbcode_uid'] != '' ) ? smilies_pass(bbencode_second_pass(stripslashes($mod_desc), $dl_files['bbcode_uid'])) : smilies_pass(stripslashes($mod_desc));
$mod_desc = make_clickable($mod_desc);
*/
$mod_desc = stripslashes($mod_desc);
$bbcode->allow_html = ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? true : false;
$bbcode->allow_bbcode = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode'] ) ? true : false;
$bbcode->allow_smilies = ( $userdata['user_allowsmile'] && $board_config['allow_smilies'] ) ? true : false;
$mod_desc = $bbcode->parse($mod_desc, $dl_files['bbcode_uid']);
$mod_desc = str_replace("\n", "\n<br />\n", $mod_desc);

$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

/*
* Hacklist
*/
if ($dl_files['hacklist'] && $dl_config['use_hacklist'])
{
	$template->assign_block_vars('hacklist', array(
		'HACK_AUTHOR' => ( $hack_author_email != '' ) ? '<a href="mailto:' . $hack_author_email . '">' . $hack_author . '</a>' : $hack_author,
		'HACK_AUTHOR_WEBSITE' => ( $hack_author_website != '' ) ? '<a href="'.$hack_author_website.'" target="_blank">' . $lang['Website'] . '</a>' : 'n/a',
		'HACK_DL_URL' => ( $hack_dl_url != '' ) ? '<a href="'.$hack_dl_url.'">' . $lang['Dl_download'] . '</a>' : 'n/a'
		)
	);

	$rowspan = 10;
}
else
{
	$rowspan = 7;
}

/*
* Enabled Bug Tracker for this download category?
*/
if ($index[$cat_id]['bug_tracker'])
{
	$template->assign_block_vars('bug_tracker', array(
		'L_BUG_TRACKER' => $lang['Dl_bug_tracker'],
		'L_BUG_TRACKER_FILE' => $lang['Dl_bug_tracker_file'],
		'U_BUG_TRACKER' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id)
		)
	);
	$rowspan++;
}

/*
* MOD block
*/
if ($mod_list && $index[$cat_id]['allow_mod_desc'])
{
	$template->assign_block_vars('mod_list', array());

	if ($test)
	{
		$template->assign_block_vars('modlisttest', array(
			'MOD_TEST' => $test
			)
		);
	}

	if ($mod_desc)
	{
		$template->assign_block_vars('modlistdesc', array(
			'MOD_DESC' => $mod_desc
			)
		);
	}

	if ($warning)
	{
		$template->assign_block_vars('modwarning', array(
			'MOD_WARNING' => $warning
			)
		);
	}

	if ($require)
	{
		$template->assign_block_vars('modrequire', array(
			'MOD_REQUIRE' => $require
			)
		);
	}

	if ($todo)
	{
		$template->assign_block_vars('modtodo', array(
			'MOD_TODO' => str_replace("\n", "<br />", $todo)
			)
		);
	}
}

/*
* Check for recurring downloads
*/
if ($dl_config['user_traffic_once'] && !$file_load && !$dl_files['free'] && !$dl_files['extern'] && ($dl_files['file_size'] > $userdata['user_traffic']))
{
	$sql = "SELECT * FROM " . DL_NOTRAF_TABLE . "
		WHERE user_id = " . $userdata['user_id'] . "
			AND dl_id = $df_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not check user download status', '', __LINE__, __FILE__, $sql);
	}

	$still_count = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($still_count)
	{
		$file_load = true;
		$template->assign_block_vars('allow_trafficfree_download', array(
			'L_YOU_CAN_DOWNLOAD' => $lang['Dl_can_download_traffic']
			)
		);
	}
}

/*
* Hotlink or not hotlink, that is the question :-P
* And we will check a broken download inclusive the visual confirmation here ...
*/
if ($file_load)
{
	if (!$dl_files['broken'] || ($dl_files['broken'] && !$dl_config['report_broken_lock']))
	{
		if ($dl_config['prevent_hotlink'])
		{
			$hotlink_id = md5($userdata['user_id'].time().$df_id.$userdata['session_id']);

			$sql = "INSERT INTO " . DL_HOTLINK_TABLE . "
				(user_id, session_id, hotlink_id)
				VALUES
				(" . $userdata['user_id'] . ", '" . $userdata['session_id'] . "', '$hotlink_id')";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not save hotlink id for this download', '', __LINE__, __FILE__, $sql);
			}
		}

		if ($dl_config['download_vc'])
		{
			$code = '';
			$code_string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789';
			srand((double)microtime()*1000000);
			mt_srand((double)microtime()*1000000);

			for ($i = 0; $i < 5; $i++)
			{
				$code_pos = mt_rand(1, strlen($code_string)) - 1;
				$code .= $code_string{$code_pos};
			}

			$sql = "INSERT INTO " . DL_HOTLINK_TABLE . "
				(user_id, session_id, hotlink_id, code)
				VALUES
				(" . $userdata['user_id'] . ", '" . $userdata['session_id'] . "', 'dlvc', '$code')";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not save hotlink id for this download', '', __LINE__, __FILE__, $sql);
			}
		}

		if ($cat_auth['auth_mod'] || $userdata['user_level'] == ADMIN)
		{
			$modcp = ($modcp) ? 1 : 0;
		}
		else
		{
			$modcp = 0;
		}

		$template->assign_block_vars('download_button', array(
			'S_HOTLINK_ID' => $hotlink_id,
			'U_DOWNLOAD' => append_sid('downloads.' . PHP_EXT . '?view=load&amp;df_id=' . $df_id . '&amp;modcp=' . $modcp . '&amp;cat_id=' . $cat_id)
			)
		);

		if ($dl_config['download_vc'])
		{
			$template->assign_block_vars('download_button.vc', array(
				'VC' => append_sid('downloads.' . PHP_EXT . '?view=code&amp;code=d')
				)
			);
		}
	}
}

/*
* Display the link ro report the download as broken
*/
if ($dl_config['report_broken'] && !$dl_files['broken'])
{
	if ($userdata['session_logged_in'] || (!$userdata['session_logged_in'] && $dl_config['report_broken'] == 1))
	{
		$template->assign_block_vars('report_broken_dl', array(
			'L_BROKEN_DOWNLOAD' => $lang['Dl_broken'],
			'U_BROKEN_DOWNLOAD' => append_sid('downloads.' . PHP_EXT . '?view=broken&amp;df_id=' . $df_id . '&amp;cat_id=' . $cat_id)
			)
		);
	}
}

/*
* some permissions, please!
*/
$cat_auth = array();
$cat_auth = $dl_mod->dl_cat_auth($cat_id);

/*
* Second part of the report link
*/
if ($dl_files['broken'])
{
	if ($index[$cat_id]['auth_mod'] || $cat_auth['auth_mod'] || $userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('dl_broken_mod', array(
			'L_REPORT' => $lang['Dl_broken_mod'],
			'U_REPORT' => append_sid('downloads.' . PHP_EXT . '?view=unbroken&amp;df_id=' . $df_id . '&amp;cat_id=' . $cat_id)
			)
		);
	}

	if (!$dl_config['report_broken_message'] || ($dl_config['report_broken_lock'] && $dl_config['report_broken_message']))
	{
		$template->assign_block_vars('dl_broken_cur', array(
			'L_REPORT' => $lang['Dl_broken_cur']
			)
		);
	}
}

/*
* Send the values to the template to be able to read something *g*
*/
$template->assign_block_vars('downloads', array(
	'DESCRIPTION' => $description,
	'MINI_IMG' => $mini_icon,
	'HACK_VERSION' => $hack_version,
	'LONG_DESC' => $long_desc,
	'STATUS' => $status,
	'USER_TRAFFIC' => $user_traffic_out,
	'FILE_SIZE' => $file_size_out,
	'FILE_KLICKS' => $file_klicks,
	'FILE_OVERALL_KLICKS' => $file_overall_klicks,
	'FILE_NAME' => $file_name,
	'LAST_TIME' => $last_time,
	'ADD_USER' => ($add_user != '') ? sprintf($lang['Dl_add_user'], $add_time, $add_user) : '',
	'CHANGE_USER' => ($change_user != '') ? sprintf($lang['Dl_change_user'], $change_time, $change_user) : ''
	)
);

/*
* Thumbnails? Okay, getting some thumbs, if they exists...
*/
if ($index[$cat_id]['allow_thumbs'] && $dl_config['thumb_fsize'] && $dl_files['thumbnail'])
{
	if (@file_exists(POSTED_IMAGES_THUMBS_PATH . $dl_files['thumbnail']))
	{
		$template->assign_block_vars('downloads.thumbnail', array(
			'L_THUMBNAIL' => $lang['Dl_thumb'],
			'THUMBNAIL' => POSTED_IMAGES_THUMBS_PATH . $dl_files['thumbnail']
			)
		);
	}
}

/*
* Urgh, the real filetime..... Heavy information, very important :D
*/
if ($dl_config['show_real_filetime'] && !$dl_files['extern'])
{
	$rowspan++;
	if (@file_exists($dl_config['dl_path'].$index[$cat_id]['cat_path'].$dl_files['file_name']))
	{
		$template->assign_block_vars('downloads.real_filetime', array(
			'L_REAL_FILETIME' => $lang['Dl_real_filetime'],
			'REAL_FILETIME' => create_date($board_config['default_dateformat'], @filemtime($dl_config['dl_path'] . $index[$cat_id]['cat_path'] . $file_name), $board_config['board_timezone'])
			)
		);
	}
}

/*
* Like to rate? Do it!
*/
$rating_points = $dl_files['rating'];

$l_rating_text = $u_rating_text = '';
if ((!$rating_points || $rating_access) && $userdata['session_logged_in'])
{
	$l_rating_text = $lang['Dl_klick_to_rate'];
	$u_rating_text = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;action=rate&amp;df_id=' . $df_id . '&amp;dlo=2');
}

if ($ratings)
{
	$rating_count_text = '&nbsp;[ '.$ratings.' ]';
}
else
{
	$rating_count_text = '';
}

$template->assign_vars(array(
	'RATING_IMG' => $dl_mod->rating_img($rating_points),
	'RATINGS' => $rating_count_text
	)
);

if ($action == 'rate' && $rating_access)
{
	$template->assign_block_vars('rating', array(
		'RATING' => $rating
		)
	);
}
elseif ($l_rating_text)
{
	$template->assign_block_vars('rating_view', array(
		'L_RATING' => $l_rating_text,
		'U_RATING' => $u_rating_text
		)
	);

}

/*
* Some user like to link to each favorite page, download, programm, friend, house friend... ahrrrrrrggggg...
*/
if ($userdata['session_logged_in'] && !$dl_config['disable_email'])
{
	$sql = "SELECT fav_id FROM " . DL_FAVORITES_TABLE . "
		WHERE fav_dl_id = $df_id
			AND fav_user_id = " . $userdata['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not get favorite status for this download', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$fav_id = $row['fav_id'];
	$db->sql_freeresult($result);

	$template->assign_block_vars('fav_block', array());

	if ($fav_id)
	{
		$l_favorite = $lang['Dl_favorite_drop'];
		$u_favorite = append_sid('downloads.' . PHP_EXT . '?view=unfav&amp;df_id=' . $df_id . '&amp;cat_id=' . $cat_id . '&amp;fav_id=' . $fav_id);
	}
	else
	{
		$l_favorite = $lang['Dl_favorite_add'];
		$u_favorite = append_sid('downloads.' . PHP_EXT . '?view=fav&amp;df_id=' . $df_id . '&amp;cat_id=' . $cat_id);
	}
}
else
{
	$l_favorite = '';
	$u_favorite = '';
}

$file_id = $dl_files['id'];
$cat_id = $dl_files['cat'];

/*
* Can we edit the download? Yes we can, or not?
*/
if ($dl_mod->user_auth($dl_files['cat'], 'auth_mod') || ($dl_config['edit_own_downloads'] && $dl_files['add_user'] == $userdata['user_id']))
{
	$template->assign_block_vars('edit_button', array(
		'EDIT_IMG' => '<img src="' . $images['icon_edit'] . '" border="0" alt="" title="" />',
		'U_EDIT' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=edit&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id)
		)
	);
}

/*
* A little bit more values and strings for the template *bfg*
*/
$template->assign_vars(array(
	'L_DESCRIPTION' => $lang['Dl_file_description'],
	'L_DETAILS' => $lang['Dl_detail'],
	'L_DL_HACK_AUTHOR' => $lang['Dl_hack_autor'],
	'L_DL_HACK_AUTHOR_WEBSITE' => $lang['Dl_hack_autor_website'],
	'L_DL_HACK_DL_URL' => $lang['Dl_hack_dl_url'],
	'L_DL_MOD_REQUIRE' => $lang['Dl_mod_require'],
	'L_DL_MOD_TEST' => $lang['Dl_mod_test'],
	'L_DL_MOD_TODO' => $lang['Dl_mod_todo'],
	'L_DOWNLOAD' => $lang['Dl_download'],
	'L_FILE_NAME' => $lang['Dl_file_name'],
	'L_KLICKS' => $lang['Dl_klicks'],
	'L_OVERALL_KLICKS' => $lang['Dl_overall_klicks'],
	'L_KL_M_T' => $lang['Dl_klicks_total'],
	'L_NAME' => $lang['Dl_name'],
	'L_SIZE' => $lang['Dl_file_size'],
	'L_RATING_TITLE' => $lang['Dl_rating'],
	'L_DL_MOD_DESC' => $lang['Dl_mod_desc'],
	'L_DL_MOD_WARNING' => $lang['Dl_mod_warning'],
	'L_DL_TOP' => $lang['Dl_cat_title'],
	'L_FAVORITE' => $l_favorite,
	'L_CANCEL' => $lang['Cancel'],
	'L_SUBMIT' => $lang['Submit'],

	'ROW_CLASS1' => $theme['td_class1'],
	'ROW_CLASS2' => $theme['td_class2'],
	'ROWSPAN' => $rowspan,

	'S_ACTION' => append_sid('downloads.' . PHP_EXT),
	'S_HIDDEN_FIELDS_RATE' => $s_hidden_fields_rate,

	'U_FAVORITE' => $u_favorite,
	'U_SEARCH' => '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=search') . '"><img src="' . $images['icon_search'] . '" border="0" title="' . $lang['Search'] . '" alt="' . $lang['Search'] . '" /></a>',
	'U_DL_CAT' => $dl_mod->dl_nav($cat_id, 'url'),
	'U_DL_TOP' => append_sid('downloads.' . PHP_EXT))
);

/*
* The end... Yes? Yes!
*/

?>