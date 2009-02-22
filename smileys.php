<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

$cat = request_var('cat', '');

$server_url = create_server_url();
$smileys_base_path = $board_config['smilies_path'] . '/';
$toc_folder = $smileys_base_path . 'cats/';
if (is_dir($toc_folder))
{
	$skip_files = array(
		'.',
		'..',
		'.htaccess',
		'index.htm',
		'index.html',
		'index.' . PHP_EXT,
	);

	$files_list = array();
	$cats_list = array();
	$dir = @opendir($toc_folder);
	while($file = @readdir($dir))
	{
		$file_part = explode('.', strtolower($file));
		$file_ext = $file_part[count($file_part) - 1];
		if(!is_dir($file) && !in_array($file, $skip_files) && ($file_ext == PHP_EXT))
		{
			$files_list[] = $file;
			@include($toc_folder . $file);
			$cats_list[] = $cat_name;
		}
	}
	closedir($dir);
	@ksort($cats_list);
	@reset($cats_list);
}

if (empty($files_list))
{
	message_die(GENERAL_ERROR, $lang['SMILEYS_NO_CATEGORIES']);
}

$page_title = $lang['SMILEYS'];
$meta_description = '';
$meta_keywords = '';
$gen_simple_header = true;
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'smileys_body.tpl'));

$cat = empty($cat) ? $files_list[0] : $cat;
if (!array_search($cat, $files_list))
{
	$cat = $files_list[0];
}

$s_categories = '<select name="cat">';
foreach ($cats_list as $k => $v)
{
	$selected = ($files_list[$k] == $cat) ? ' selected="selected"' : '';
	$s_categories .= '<option value="' . $files_list[$k] . '"' . $selected . '>' . $v . '</option>';
}
$s_categories .= '</select>';

require($toc_folder . $cat);

$smileys_columns = $board_config['smilie_window_columns'];
$smileys_rows = $board_config['smilie_window_rows'];
$smileys_count = count($smileys_list);
$s_colspan = $smileys_columns;
$s_colwidth = ($s_colspan == 0) ? '100%' : 100 / $s_colspan . '%';

for($i = 0; $i < $smileys_count; $i++)
{
	if (($i % $smileys_columns) == 0)
	{
		$template->assign_block_vars('smileys_row', array());
	}

	$smiley_url = $server_url . $smileys_base_path . $smileys_list[$i];

	$template->assign_block_vars('smileys_row.smileys_column', array(
		'SMILEY_IMG' => $smiley_url,
		'SMILEY_BBC_INPUT' => 'sm_' . $i,
		'SMILEY_BBC' => '[img]' . $smiley_url . '[/img]'
		)
	);
}

while (($i % $smileys_columns) != 0)
{
	$smiley_url = $server_url . 'images/blank.gif';
	$template->assign_block_vars('smileys_row.smileys_column', array(
		'SMILEY_IMG' => false,
		'SMILEY_BBC_INPUT' => 'sm_' . $i,
		'SMILEY_BBC' => ''
		)
	);
	$i++;
}

$template->assign_vars(array(
	'L_CLOSE_WINDOW' => $lang['Close_window'],
	'U_STANDARD_SMILEYS' => append_sid('posting.' . PHP_EXT . '?mode=smilies'),

	'S_CATEGORY_SELECT' => $s_categories,
	'S_COLSPAN' => $s_colspan,
	'S_COLWIDTH' => $s_colwidth,
	'S_ACTION' => append_sid('smileys.' . PHP_EXT),
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>