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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
* UseLess
*
*/

define('IN_ICYPHOENIX', true);

// start script
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

if ( !defined('XS_TPL_PATH') )
{
	define('XS_TPL_PATH', '../../templates/common/xs_mod/tpl_news/');
}

$mode = $_GET['mode'];

if ( $mode == 'smilies' )
{
	generate_smilies('window');
	exit;
}

// Start functions
// Borrowed from functions_post.php
function generate_smilies($mode)
{
	global $db, $board_config, $template, $lang, $images, $theme;
	global $user_ip, $session_length, $starttime;
	global $userdata, $user;

	$inline_columns = 4;
	$inline_rows = 5;
	$window_columns = 10;

	if ($mode == 'window')
	{
		// Start session management
		$userdata = session_pagestart($user_ip);
		init_userprefs($userdata);
		// End session management

		$gen_simple_header = true;

		$page_title = $lang['Emoticons'] . ' - ' . $topic_title;
		include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);

		$template->set_filenames(array('smiliesbody' => XS_TPL_PATH . 'news_smilies.tpl'));
	}

	$sql = "SELECT emoticon, code, smile_url FROM " . SMILIES_TABLE . " ORDER BY smilies_order";
	if ($result = $db->sql_query($sql, false, 'smileys_'))
	{
		$num_smilies = 0;
		$rowset = array();
		while ($row = $db->sql_fetchrow($result))
		{
			if (empty($rowset[$row['smile_url']]))
			{
				$rowset[$row['smile_url']]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row['code']));
				$rowset[$row['smile_url']]['emoticon'] = $row['emoticon'];
				$num_smilies++;
			}
		}

		if ($num_smilies)
		{
			$smilies_count = ($mode == 'inline') ? min(19, $num_smilies) : $num_smilies;
			$smilies_split_row = ($mode == 'inline') ? $inline_columns - 1 : $window_columns - 1;

			$s_colspan = 0;
			$row = 0;
			$col = 0;

			while (list($smile_url, $data) = @each($rowset))
			{
				if (!$col)
				{
					$template->assign_block_vars('smilies_row', array());
				}

				$template->assign_block_vars('smilies_row.smilies_col', array(
					'SMILEY_CODE' => $data['code'],
					'SMILEY_IMG' => 'http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $smile_url,
					'SMILEY_DESC' => $data['emoticon'])
				);

				$s_colspan = max($s_colspan, $col + 1);

				if ($col == $smilies_split_row)
				{
					if ($mode == 'inline' && $row == $inline_rows - 1)
					{
						break;
					}
					$col = 0;
					$row++;
				}
				else
				{
					$col++;
				}
			}

			if ($mode == 'inline' && $num_smilies > $inline_rows * $inline_columns)
			{
				$template->assign_block_vars('switch_smilies_extra', array());

				$template->assign_vars(array(
					'L_MORE_SMILIES' => $lang['More_emoticons'],
					'U_MORE_SMILIES' => append_sid('posting.' . PHP_EXT . '?mode=smilies'))
				);
			}

			$template->assign_vars(array(
				'L_EMOTICONS' => $lang['Emoticons'],
				'L_CLOSE_WINDOW' => $lang['Close_window'],
				'S_SMILIES_COLSPAN' => $s_colspan,
				'W_WIDTH_SMILIES' => 400,
				'W_HEIGHT_SMILIES' => 360
				)
			);
		}
	}

	if ($mode == 'window')
	{
		$template->pparse('smiliesbody');

		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
}
// End Functions

?>