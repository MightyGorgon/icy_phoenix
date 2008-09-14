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

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('CT_SECLEVEL', 'MEDIUM');
$ct_ignorepvar = array('mod_desc', 'long_desc', 'description', 'warning', 'todo', 'require', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_dl_url', 'dl_name', 'file_name');
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);

$auth_level_req = $board_config['auth_view_download'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		redirect(append_sid(LOGIN_MG . '?redirect=downloads.' . PHP_EXT, true));
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_download'] == 1) ? true : false;


include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_downloads.' . PHP_EXT);

/*
* init and get various values
*/
$params = array(
	'submit' => 'submit',
	'cancel' => 'cancel',
	'confirm' => 'confirm',
	'delete' => 'delete',
	'cdelete' => 'cdelete',
	'save' => 'save',
	'post' => 'post',
	'view' => 'view',
	'show' => 'show',
	'order' => 'order',
	'action' => 'action',
	'save' => 'save',
	'goback' => 'goback',
	'edit' => 'edit',
	'bt_show' => 'bt_show',
	'move' => 'move',
	'fmove' => 'fmove',
	'lock' => 'lock',
	'sort' => 'sort',
	'code' => 'code'
);
while(list($var, $param) = @each($params))
{
	if (!empty($_POST[$param]) || !empty($_GET[$param]))
	{
		$$var = (!empty($_POST[$param])) ? htmlspecialchars($_POST[$param]) : htmlspecialchars($_GET[$param]);
	}
	else
	{
		$$var = '';
	}
}

$params = array(
	'df_id' => 'df_id',
	'cat' => 'cat',
	'new_cat' => 'new_cat',
	'cat_id' => 'cat_id',
	'fav_id' => 'fav_id',
	'dl_id' => 'dl_id',
	'dlo' => 'dlo',
	'start' => 'start',
	'sort_by' => 'sort_by',
	'rate_point' => 'rate_point',
	'del_file' => 'del_file',
	'bt_filter' => 'bt_filter',
	'modcp' => 'modcp'
);
while(list($var, $param) = @each($params))
{
	if (!empty($_POST[$param]) || !empty($_GET[$param]))
	{
		$$var = (!empty($_POST[$param])) ? intval($_POST[$param]) : intval($_GET[$param]);
	}
	else
	{
		$$var = 0;
	}
}

$df_id = ($df_id < 0) ? 0 : $df_id;
$cat = ($cat < 0) ? 0 : $cat;
$new_cat = ($new_cat < 0) ? 0 : $new_cat;
$cat_id = ($cat_id < 0) ? 0 : $cat_id;
$fav_id = ($fav_id < 0) ? 0 : $fav_id;
$dl_id = ($dl_id < 0) ? 0 : $dl_id;
$dlo = ($dlo < 0) ? 0 : $dlo;
$start = ($start < 0) ? 0 : $start;
$sort_by = ($sort_by < 0) ? 0 : $sort_by;
$rate_point = ($rate_point < 0) ? 0 : $rate_point;
$del_file = ($del_file < 0) ? 0 : $del_file;
$modcp = ($modcp < 0) ? 0 : $modcp;

/*
* display the confirmation code if needed
*/
if (($view == 'code') && $code)
{
	$hotlink_id = ($code == 'd') ? 'dlvc' : 'repvc';

	$sql = "SELECT code FROM " . DL_HOTLINK_TABLE . "
		WHERE user_id = " . $userdata['user_id'] . "
			AND hotlink_id = '" . $hotlink_id . "'";
	$sql .= (!$userdata['session_logged_in']) ? " AND session_id = '" . $userdata['session_id'] . "' " : '';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read confirmation code for this session', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$code = $row['code'];
	$db->sql_freeresult($result);

	if (!$code)
	{
		$code = 'ERROR';
	}

	$im = imagecreate(50, 20);
	$background_color = imagecolorallocate ($im, 150, 150, 150);
	imagefill($im, 0, 0, $background_color);
	$text_color = imagecolorallocate ($im, 0, 0, 255);
	imagestring ($im, 5, 2, 2, $code, $text_color);
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
	header("Content-Type: image/jpeg");
	imagejpeg ($im, '', 80);
	imagedestroy($im);
	exit;
}
elseif($view == 'code' && !$code)
{
	exit;
}

/*
* redirect to details or rating íf needed
*/
if ($cat && $df_id && (($view == 'detail') && ($action != 'rate')))
{
	redirect(append_sid('downloads.' . PHP_EXT . '?view=' . $view . '&cat=' . $cat . '&df_id=' . $df_id . '&dlo=' . $dlo, true));
}

/*
* include and create the main class
*/
if ($cat || !count($_GET) || (count($_GET) == 1 && isset($_GET['sid'])))
{
	$enable_desc = true;
}
if ($cat)
{
	$enable_rule = true;
}
include(IP_ROOT_PATH . DL_ROOT_PATH . 'classes/class_dlmod.' . PHP_EXT);
$dl_mod = new dlmod();
$dl_config = array();
$dl_config = $dl_mod->get_config();

/*
* set the right values for comments
*/
if (!$action)
{
	if ($post)
	{
		$view = 'comment';
		$action = 'post';
	}

	if ($show)
	{
		$view = 'comment';
		$action = 'view';
	}

	if ($save)
	{
		$view = 'comment';
		$action = 'save';
	}

	if ($delete)
	{
		$view = 'comment';
		$action = 'delete';
	}

	if ($edit)
	{
		$view = 'comment';
		$action = 'edit';
	}
}

/*
* wanna have smilies ;-)
*/
if ($action == 'smilies')
{
	generate_smilies('window', PAGE_DOWNLOADS);
	exit;
}

if ($goback)
{
	$view = 'detail';
	$action = '';
}

/*
* get the needed index
*/
$index = array();

switch ($view)
{
	case 'overall':
	case 'load':
	case 'detail':
	case 'comment':
	case 'upload':
	case 'modcp':
	case 'bug_tracker':

		$index = $dl_mod->full_index();
		break;

	default:

		$index = ($cat) ? $dl_mod->index($cat) : $dl_mod->index();
}

if ($view != 'load' && $view != 'broken')
{
	$sql_where = '';

	if (!$userdata['session_logged_in'])
	{
		$sql = "SELECT session_id FROM " . SESSIONS_TABLE . "
			WHERE session_user_id = " . ANONYMOUS;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch valid guest sessions', '', __LINE__, __FILE__, $sql);
		}

		$guest_sids = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$guest_sids .= ($guest_sids != '') ? ", '".$row['session_id'] . "'" : "'" . $row['session_id'] . "'";
		}
		$db->sql_freeresult($result);

		$sql_where = " AND session_id NOT IN (" . $guest_sids . ") ";
	}

	$sql = "DELETE FROM " . DL_HOTLINK_TABLE . "
		WHERE user_id = " . $userdata['user_id'] . "
			$sql_where";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not drop old hotlink sessions for this user', '', __LINE__, __FILE__, $sql);
	}
}

//create todo list
if ($view == 'todo')
{
	$dl_todo = array();
	$dl_todo = $dl_mod->get_todo();

	if (count($dl_todo['file_name']))
	{
		$page_title = $lang['Dl_mod_todo'];
		$meta_description = '';
		$meta_keywords = '';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('body' => 'dl_todo_body.tpl'));

		$template->assign_vars(array(
			'L_DESCRIPTION' => $lang['Dl_file_description'],
			'L_DL_TOP' => $lang['Dl_cat_title'],
			'L_DL_TODO' => $lang['Dl_mod_todo'],
			'U_DL_TOP' => append_sid('downloads.' . PHP_EXT)
			)
		);

		for ($i = 0; $i < count($dl_todo['file_name']); $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('todolist_row', array(
				'FILENAME' => $dl_todo['file_name'][$i],
				'FILE_LINK' => $dl_todo['file_link'][$i],
				'HACK_VERSION' => $dl_todo['hack_version'][$i],
				'TODO' => $dl_todo['todo'][$i],
				'ROW_CLASS' => $row_class
				)
			);
		}
	}
	else
	{
		$view = '';
	}
}

//handle reported broken download
if (($view == 'broken') && $df_id && $cat_id && ($userdata['session_logged_in'] || (!$userdata['session_logged_id'] && $dl_config['report_broken'])))
{
	if ($dl_config['report_broken_vc'])
	{
		if ($confirm != 'code')
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

			$sql = "DELETE FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $userdata['user_id'] . "
					AND hotlink_id = 'repvc'";
			if (!$userdata['session_logged_id'])
			{
				$sql .= " AND session_id = '" . $userdata['session_id'] . "'";
			}

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not delete confirmation code for this download', '', __LINE__, __FILE__, $sql);
			}

			$sql = "INSERT INTO " . DL_HOTLINK_TABLE . "
				(user_id, session_id, hotlink_id, code)
				VALUES
				(" . $userdata['user_id'] . ", '" . $userdata['session_id'] . "', 'repvc', '$code')";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not save confirmation code for this download', '', __LINE__, __FILE__, $sql);
			}

			include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

			$template->set_filenames(array('code_body' => 'dl_report_code_body.tpl'));

			$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="view" value="broken" />';
			$s_hidden_fields .= '<input type="hidden" name="confirm" value="code" />';

			$template->assign_vars(array(
				'L_SUBMIT' => $lang['Submit'],

				'MESSAGE_TITLE' => $lang['Information'],
				'MESSAGE_TEXT' => $lang['Dl_report_confirm_code'],
				'CONFIRM_CODE' => append_sid('downloads.' . PHP_EXT . '?view=code&amp;code=r'),

				'S_CONFIRM_ACTION' => append_sid('downloads.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			$template->pparse('code_body');

			include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
		}
		else
		{
			$sql = "SELECT code FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $userdata['user_id'] . "
					AND session_id = '" . $userdata['session_id'] . "'
					AND hotlink_id = 'repvc'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not read confirmation code', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);
			$saved_code = $row['code'];
			$db->sql_freeresult($result);

			if ($saved_code == $code)
			{
				$code_match = true;
			}
			else
			{
				$code_match = 0;
			}
		}
	}

	if ($dl_config['report_broken_vc'] && !$code_match)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_report_broken_vc_mismatch']);
	}
	else
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET broken = " . true . "
			WHERE id = " . (int) $df_id;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not set broken status for download', '', __LINE__, __FILE__, $sql);
		}

		$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_mod');
		$processing_user .= ($processing_user) ? '' : 0;

		$email_template = 'downloads_report_broken';

		$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
			WHERE user_id IN ($processing_user)
				OR user_level = " . ADMIN;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not send email for broken downloads', '', __LINE__, __FILE__, $sql);
		}

		$script_path = $board_config['script_path'];
		$server_name = trim($board_config['server_name']);
		$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
		$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		while ($row = $db->sql_fetchrow($result))
		{
			// Let's do some checking to make sure that mass mail functions are working in win32 versions of php.

			if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
			{
				$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

				// We are running on windows, force delivery to use our smtp functions since php's are broken by default
				$board_config['smtp_delivery'] = 1;
				$board_config['smtp_host'] = @$ini_val('SMTP');
			}

			$username = (!$userdata['session_logged_in']) ? $lang['Dl_a_guest'] : $userdata['username'];

			$emailer = new emailer($board_config['smtp_delivery']);

			$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
			$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
			$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
			$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

			$emailer->use_template($email_template, $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			$emailer->extra_headers($email_headers);
			$emailer->set_subject();

			$emailer->assign_vars(array(
				'BOARD_EMAIL' => $board_config['board_email_sig'],
				'SITENAME' => $board_config['sitename'],
				'REPORTER' => $username,
				'USERNAME' => $row['username'],
				'U_DOWNLOAD' => $server_url . 'downloads.' . PHP_EXT . '?view=detail&cat_id=' . $cat_id . '&df_id=' . $df_id
				)
			);

			$emailer->send();
			$emailer->reset();
		}
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

//reset reported broken download if allowed
if ($view == 'unbroken' && $df_id && $cat_id)
{
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if ($index[$cat_id]['auth_mod'] || $cat_auth['auth_mod'] || $userdata['user_level'] == ADMIN)
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET broken = 0
			WHERE id = " . (int) $df_id;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not reset broken status for download', '', __LINE__, __FILE__, $sql);
		}
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

//set favorite for the choosen download
if (($view == 'fav') && $df_id && $cat_id && $userdata['session_logged_in'])
{
	$sql = "SELECT * FROM " . DL_FAVORITES_TABLE . "
		WHERE fav_dl_id = " . (int) $df_id . "
			AND fav_user_id = " . $userdata['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not check favorites', '', __LINE__, __FILE__, $sql);
	}

	$fav_check = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if (!$fav_check)
	{
		$sql = "INSERT INTO " . DL_FAVORITES_TABLE . "
			(fav_dl_id, fav_dl_cat, fav_user_id)
			VALUES
			(" . (int) $df_id . ", " . (int) $cat_id . ", " . $userdata['user_id'] . ")";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not save favorites', '', __LINE__, __FILE__, $sql);
		}
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

//drop favorite for the choosen download
if (($view == 'unfav') && $fav_id && $df_id && $cat_id && $userdata['session_logged_in'])
{
	$sql = "DELETE FROM " . DL_FAVORITES_TABLE . "
		WHERE fav_id = " . (int) $fav_id . "
			AND fav_dl_id = " . (int) $df_id . "
			AND fav_user_id = " . $userdata['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not drop favorites for download', '', __LINE__, __FILE__, $sql);
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

/*
* open the bug tracker, if choosen and possible
*/
if ($view == 'bug_tracker')
{
	if ($userdata['session_logged_in'])
	{
		$bug_tracker = $dl_mod->bug_tracker();
		if ($bug_tracker)
		{
			$inc_module = true;
			$page_title = $lang['Dl_bug_tracker'];
			$meta_description = '';
			$meta_keywords = '';
			include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
			include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_bug_tracker.' . PHP_EXT);
		}
		else
		{
			$view = '';
		}
	}
	else
	{
		$view = '';
	}
}

/*
* No real hard work until here? Must at least run one of the default modules?
*/
$inc_module = false;
if ($view == 'stat')
{
	//getting some stats
	$inc_module = true;
	$page_title = $lang['Dl_stats'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_stats.' . PHP_EXT);
}
elseif ($view == 'user_config')
{
	//drop choosen favorites
	$fav_ids = (isset($_POST['fav_id'])) ? $_POST['fav_id'] : array();
	if ($action == 'drop' && count($fav_ids))
	{
		$sql_fav_ids = '';
		for ($i = 0; $i < count($fav_ids); $i++)
		{
			$sql_fav_ids .= ($sql_fav_ids == '') ? intval($fav_ids[$i]) : ', ' . intval($fav_ids[$i]);
		}

		$sql = "DELETE FROM " . DL_FAVORITES_TABLE . "
			WHERE fav_id IN ($sql_fav_ids)
				AND fav_user_id = " . $userdata['user_id'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not drop favorites', '', __LINE__, __FILE__, $sql);
		}

		$action = '';
		$submit = '';
		$fav_ids = array();
	}

	//display the user config for the downloads
	$inc_module = true;
	$page_title = $lang['Dl_config'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_user_config.' . PHP_EXT);
}
elseif ($view == 'detail')
{
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if (!$userdata['user_level'] == ADMIN && !$cat_auth['auth_mod'])
	{
		$modcp = 0;
	}

	//default entry point for download details
	$dl_files = array();
	$dl_files = $dl_mod->all_files(0, '', 'ASC', '', $df_id, $modcp);

	//check the permissions
	$check_status = array();
	$check_status = $dl_mod->dl_status($df_id);
	if (!$dl_files['id'])
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	//save rating into database after submitting
	if ($submit && ($action == 'rate'))
	{
		$rate_user_id = $userdata['user_id'];
		$rate_point = round($rate_point, 2);

		$sql = "INSERT INTO " . DL_RATING_TABLE . " (dl_id, user_id, rate_point)
				VALUES ($df_id, $rate_user_id, '$rate_point')";

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not insert new rating', '', __LINE__, __FILE__, $sql);
		}

		$sql = "SELECT AVG(rate_point) AS rating FROM " . DL_RATING_TABLE . "
			WHERE dl_id = $df_id
			GROUP BY dl_id";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query rating information', '', __LINE__, __FILE__, $sql);
		}
		$this_id = $db->sql_fetchrow($result);
		$new_rating = ceil($this_id['rating']);
		$db->sql_freeresult($result);

		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET rating = $new_rating
			WHERE id = $df_id";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not insert new average rating', '', __LINE__, __FILE__, $sql);
		}

		$view = 'detail';
		$action = '';
		$submit = '';
		$cancel = '';

		if ($dlo == 1)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?view=overall'));
		}
		elseif ($dlo == 2 && $df_id)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
		}
		elseif (!$dlo)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?cat=' . $cat . '&start=' . $start, true));
		}
	}

	$inc_module = true;
	$page_title = $lang['Download'] . ' - ' . $dl_files['description'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_details.' . PHP_EXT);
}
elseif ($view == 'search')
{
	//open the search for downloads
	$inc_module = true;
	$page_title = $lang['Search'] . ' ' . $lang['Downloads'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_search.' . PHP_EXT);
}
elseif ($view == 'popup')
{
	//display the popup for a new or changed download
	$gen_simple_header = true;
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'privmsgs_popup.tpl'));

	$template->assign_vars(array(
		'L_CLOSE_WINDOW' => $lang['Close_window'],
		'L_MESSAGE' => sprintf($lang['New_download'], '<a href="javascript:jump_to_inbox();">', '</a>'),
		'U_PRIVATEMSGS' => append_sid('downloads.' . PHP_EXT)
		)
	);

	$template->pparse('body');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

	exit;
}
elseif ($view == 'load')
{
	//check for hotlinking
	$hotlink_disabled = false;
	$sql_where = '';

	if ($dl_config['prevent_hotlink'])
	{
		$hotlink_id = $_POST['hotlink_id'];
		if (!$hotlink_id)
		{
			$hotlink_disabled = true;
		}
		else
		{
			if (!$userdata['session_logged_in'])
			{
				$sql_where = " AND session_id = '" . $userdata['session_id'] . "' ";
			}

			$sql = "SELECT hotlink_id FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $userdata['user_id'] . "
					AND hotlink_id = '" . $hotlink_id . "'
					$sql_where";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not read current hotlink_id for this user', '', __LINE__, __FILE__, $sql);
			}

			$total_hotlinks = $db->sql_numrows($result);
			if ($total_hotlinks <> 1)
			{
				$hotlink_disabled = true;
			}

			$db->sql_freeresult($result);
		}
		if ($hotlink_disabled)
		{
			if ($dl_config['hotlink_action'])
			{
				redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Dl_hotlink_permission']);
			}
		}
	}

	if ($dl_config['download_vc'])
	{
			if (!$userdata['session_logged_in'])
			{
				$sql_where = " AND session_id = '" . $userdata['session_id'] . "' ";
			}

			$sql = "SELECT code FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $userdata['user_id'] . "
					AND hotlink_id = 'dlvc'
					$sql_where";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not read current confirmation code for this user', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($row['code'] != $code)
			{
				message_die(GENERAL_MESSAGE, $lang['Dl_vc_permission']);
			}
	}

	// The basic function to get the download!
	$dl_file = array();
	$dl_file = $dl_mod->all_files(0, '', 'ASC', '', $df_id, $modcp);

	$cat_id = ($modcp) ? $cat_id : $dl_file['cat'];

	if ($modcp && $cat_id)
	{
		$cat_auth = array();
		$cat_auth = $dl_mod->dl_cat_auth($cat_id);

		if (!$userdata['user_level'] == ADMIN && !$cat_auth['auth_mod'])
		{
			$modcp = 0;
		}
	}
	else
	{
		$modcp = 0;
	}

	//check the permissions
	$check_status = array();
	$check_status = $dl_mod->dl_status($df_id);
	$status = $check_status['auth_dl'];
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if ($modcp)
	{
		$check_status['auth_dl'] = true;
	}

	$browser = $dl_mod->dl_client();

	if ($check_status['auth_dl'] && $dl_file['id'])
	{
		//fix the mod and admin auth if needed
		if (!$dl_file['approve'])
		{
			if ((($cat_auth['auth_mod'] || $index[$cat_id]['auth_mod']) && $userdata['user_level'] != ADMIN) || $userdata['user_level'] == ADMIN)
			{
				$status = true;
			}
		}

		//update all statistics
		if ($status)
		{
			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET klicks = klicks + 1, overall_klicks = overall_klicks + 1, last_time = " . time() . ", down_user = " . $userdata['user_id'] . "
				WHERE id = $df_id";
			if (!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update user download data', '', __LINE__, __FILE__, $sql);
			}

			if ($userdata['session_logged_in'] && !$dl_file['free'] && !$dl_file['extern'])
			{
				$count_user_traffic = true;
				// MG DL Counter - BEGIN
				if (($dl_config['user_download_limit_flag'] == true) && ($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_download_counter = (user_download_counter + 1)
						WHERE user_id = " . $userdata['user_id'];
					$db->sql_query($sql);
				}
				// MG DL Counter - END

				if ($dl_config['user_traffic_once'])
				{
					$sql = "SELECT * FROM " . DL_NOTRAF_TABLE . "
						WHERE user_id = " . $userdata['user_id'] . "
							AND dl_id = " . $dl_file['id'];
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not check user download status', '', __LINE__, __FILE__, $sql);
					}

					$still_count = $db->sql_numrows($result);
					$db->sql_freeresult($result);

					if ($still_count)
					{
						$count_user_traffic = false;
					}
				}

				if ($count_user_traffic)
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = user_traffic - " . $dl_file['file_size'] . "
						WHERE user_id = " . $userdata['user_id'];
					if (!($db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not update download data', '', __LINE__, __FILE__, $sql);
					}

					if ($dl_config['user_traffic_once'])
					{
						$sql = "INSERT INTO " . DL_NOTRAF_TABLE . "
							(user_id, dl_id) VALUES (" . $userdata['user_id'] . ", " . $dl_file['id'] . ")";
						if (!($db->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, 'Could not update download userdata', '', __LINE__, __FILE__, $sql);
						}
					}
				}
			}

			if (!$dl_file['extern'])
			{
				$sql = "UPDATE " . DL_CONFIG_TABLE . "
					SET config_value = config_value + " . $dl_file['file_size'] . "
					WHERE config_name = 'remain_traffic'";
				if (!($db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update download data', '', __LINE__, __FILE__, $sql);
				}

				$sql = "UPDATE " . DL_CAT_TABLE . "
					SET cat_traffic_use = cat_traffic_use + " . $dl_file['file_size'] . "
					WHERE id = $cat_id";
				if (!($db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update categories data', '', __LINE__, __FILE__, $sql);
				}
			}

			if ($index[$cat_id]['statistics'])
			{
				if ($index[$cat_id]['stats_prune'])
				{
					$stat_prune = $dl_mod->dl_prune_stats($cat_id, $index[$cat_id]['stats_prune']);
				}

				$sql = "INSERT INTO " . DL_STATS_TABLE . "
					(cat_id, id, user_id, username, traffic, direction, user_ip, browser, time_stamp) VALUES
					($cat_id, $df_id, " . $userdata['user_id'] . ", '" . str_replace("\'", "''", $userdata['username']) . "', " . $dl_file['file_size'] . ", 0, '" . $userdata['session_ip'] . "', '" . str_replace("\'", "''", $browser) . "', " . time() . ")";
				if (!($db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update download statistics data', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		/*
		* not it is time and we are ready to rumble: send the file to the user client to download it there!
		*/
		if ($dl_file['extern'])
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $dl_file['file_name']);
		}
		elseif ($status)
		{
			$dl_file_url = $dl_config['dl_path'] . $index[$cat_id]['cat_path'] . $dl_file['file_name'];

			$dl_file_size = sprintf("%u", @filesize($dl_file_url));

			$mem_limit = ini_get('memory_limit');

			$last = strlen($mem_limit) - 1;
			$max_mem_limit = (int)$mem_limit;

			switch($mem_limit{$last})
			{
				case 'G':
					$max_mem_limit *= 1024;
				case 'M':
					$max_mem_limit *= 1024;
				case 'K':
					$max_mem_limit *= 1024;
			}

			if ($dl_file_size > $max_mem_limit && $dl_config['dl_direct'])
			{
				$script_path = $board_config['script_path'];
				$server_name = trim($board_config['server_name']);
				$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
				$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

				$server_url = $server_name . $server_port . $script_path;
				$server_url = $server_protocol . str_replace('//', '/', $server_url);

				$dl_file_url = $server_url . $dl_config['download_dir'] . $index[$cat_id]['cat_path'] . $dl_file['file_name'];
				$dl_file_url = str_replace(" ", "%20", $dl_file_url);

				header("HTTP/1.1 301 Moved Permanently");
				header("Location: ".$dl_file_url);
			}
			else
			{
				if ($dl_config['dl_method'] == 1)
				{
					header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=\"".$dl_file['file_name']."\"");
					readfile($dl_file_url);
				}
				elseif ($dl_config['dl_method'] == 2)
				{
					$size = sprintf("%u", @filesize($dl_file_url));
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Content-Type: application/octet-stream");
					header("Content-Length: ".$size);
					header("Content-Transfer-Encoding: binary");
					header("Content-Disposition: attachment; filename=\"".$dl_file['file_name']."\"");
					if ($size > $dl_config['dl_method_quota'])
					{
						$dl_mod->readfile_chunked($dl_file_url);
					}
					else
					{
						readfile($dl_file_url);
					}
				}
			}
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Dl_no_access']);
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_access']);
	}

	exit;
}
elseif ($view == 'comment')
{
	//check general permissions
	if (!$dl_mod->cat_auth_comment_read($cat_id))
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if (!$cat_auth['auth_view'] && !$index[$cat_id]['auth_view'] && $userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	//redirect to download details if comments are disabled for this category
	if (!$index[$cat_id]['comments'] || $goback)
	{
		$view = 'detail';
		$action = '';
	}

	//take the message if entered
	$comment_text = (!empty($_POST['message'])) ? htmlspecialchars(trim($_POST['message'])) : '';

	//someone cancel a job? list the list again and again...
	if ($cancel && $action == 'delete')
	{
		$action = '';
	}

	//check permissions to manage comments
	$sql = "SELECT user_id FROM " . DL_COMMENTS_TABLE . "
		WHERE id = $df_id
			AND dl_id = $dl_id
			AND approve = " . TRUE . "
			AND cat_id = $cat_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read comment user', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$allow_manage = 0;
	if ($row['user_id'] == $userdata['user_id'] || $cat_auth['auth_mod'] || $index[$cat_id]['auth_mod'] || $userdata['user_level'] == ADMIN)
	{
		$allow_manage = true;
	}

	$deny_post = 0;
	if (!$dl_mod->cat_auth_comment_post($cat_id))
	{
		$allow_manage = 0;
		$deny_post = true;
	}

	//open the comments view for this download if allowed
	if ($action)
	{
		$inc_module = true;
		$page_title = $lang['Dl_comments'];
		$meta_description = '';
		$meta_keywords = '';
		include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_comments.' . PHP_EXT);
	}
	else
	{
		if ($df_id)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
		}
		elseif ($cat_id)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id));
		}
		else
		{
			redirect(append_sid('downloads.' . PHP_EXT));
		}
	}
}
elseif ($view == 'upload')
{
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	$physical_size = $dl_mod->read_dl_sizes($dl_config['dl_path']);
	if ($physical_size >= $dl_config['physical_quota'])
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_blue_explain']);
	}

	if (($dl_config['stop_uploads'] && $userdata['user_level'] != ADMIN) || !count($index) || (!$cat_auth['auth_up'] && !$index[$cat_id]['auth_up'] && $userdata['user_level'] != ADMIN))
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	$inc_module = true;
	$page_title = $lang['Dl_upload'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_upload.' . PHP_EXT);
}
elseif ($view == 'modcp')
{
	$deny_modcp = 0;

	if (($action == 'edit' || $action == 'save') && $dl_config['edit_own_downloads'])
	{
		$sql = "SELECT add_user FROM " . DOWNLOADS_TABLE . "
			WHERE id = $df_id";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't get edit permissions", "", __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row['add_user'] == $userdata['user_id'])
		{
			$own_edit = true;
		}
		else
		{
			$own_edit = 0;
		}
	}
	else
	{
		$owm_edit = 0;
	}

	if ($own_edit)
	{
		$access_cat[0] = $cat_id;
		$deny_modcp = 0;
	}
	else
	{
		$access_cat = array();
		$access_cat = $dl_mod->full_index(0, 0, 0, 2);
		if (!count($access_cat) && $userdata['user_level'] != ADMIN)
		{
			$deny_modcp = true;
		}
	}

	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if (!$cat_id && !$cat_auth['auth_mod'] && !$index[$cat_id]['auth_mod'] && $userdata['user_level'] != ADMIN)
	{
		$deny_modcp = true;
	}

	if ($deny_modcp)
	{
		$view = '';
		$action = '';
	}
	else
	{
		$action = ($move) ? 'move' : $action;
		$action = ($delete) ? 'delete' : $action;
		$action = ($cdelete) ? 'cdelete' : $action;
		$action = ($lock) ? 'lock' : $action;
		$action = ($cancel) ? 'manage' : $action;

		$action = (!$action) ? 'manage' : $action;

		$meta_description = '';
		$meta_keywords = '';

		switch ($action)
		{
			case 'approve':
				$page_title = $lang['Dl_modcp_approve'];
				break;
			case 'capprove':
				$page_title = $lang['Dl_modcp_capprove'];
				break;
			case 'edit':
			case 'save':
				$page_title = $lang['Dl_modcp_edit'];
				break;
			case 'manage':
			case 'delete':
			case 'cdelete':
			case 'lock':
			case 'move':
				$page_title = $lang['Dl_modcp_manage'];
				break;
			default:
				$page_title = $lang['Dl_modcp_manage'];
				$action = 'manage';
		}

		$dl_id = (isset($_POST['dlo_id'])) ? $_POST['dlo_id'] : array();

		if ($fmove && $userdata['user_level'] == ADMIN)
		{
			if ($fmove == 'ABC')
			{
				$sql = "SELECT id FROM " . DOWNLOADS_TABLE . "
					WHERE cat = $cat_id
					ORDER BY description ASC";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				$sql_move = ($fmove == 1) ? '+ 15' : '-15';

				$sql = "UPDATE " . DOWNLOADS_TABLE . "
					SET sort = sort $sql_move
					WHERE id = $df_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Couldn't change downloads order", "", __LINE__, __FILE__, $sql);
				}

				$sql = "SELECT id FROM " . DOWNLOADS_TABLE . "
					WHERE cat = $cat_id
					ORDER BY sort ASC";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
				}
			}

			$i = 10;

			while($row = $db->sql_fetchrow($result))
			{
				$sql_sort = "UPDATE " . DOWNLOADS_TABLE . "
						SET sort = $i
						WHERE id = " . $row['id'];
				if(!$db->sql_query($sql_sort))
				{
					message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql_sort);
				}
				$i += 10;
			}

			$db->sql_freeresult($result);

			$action = 'manage';
		}

		$fmove = '';

		$inc_module = true;
		include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_modcp.' . PHP_EXT);
	}
}

//sorting downloads
if ($dl_config['sort_preform'])
{
	$sort_by = 0;
	$order = ASC;
}
else
{
	$sort_by = (!$sort_by) ? $userdata['user_dl_sort_fix'] : $sort_by;
	$order = (!$order) ? (($userdata['user_dl_sort_dir']) ? 'DESC' : 'ASC') : $order;
}

switch ($sort_by)
{
	case 1:
		$sql_sort_by = 'description';
		break;
	case 2:
		$sql_sort_by = 'file_name';
		break;
	case 3:
		$sql_sort_by = 'klicks';
		break;
	case 4:
		$sql_sort_by = 'free';
		break;
	case 5:
		$sql_sort_by = 'extern';
		break;
	case 6:
		$sql_sort_by = 'file_size';
		break;
	case 7:
		$sql_sort_by = 'change_time';
		break;
	case 8:
		$sql_sort_by = 'rating';
		break;
	default:
		$sql_sort_by = 'sort';
}

switch ($order)
{
	case 'ASC':
		$sql_order = 'ASC';
		break;
	case 'DESC':
		$sql_order = 'DESC';
		break;
	default:
		$sql_order = 'ASC';
}

if (!$dl_config['sort_preform'] && $userdata['user_dl_sort_opt'])
{
	$template->assign_block_vars('sort_options', array());

	$s_sort_by = '<select name="sort_by" onchange="forms[\'dl_mod\'].submit()">';
	$s_sort_by .= '<option value="0">' . $lang['Dl_default_sort'] . '</option>';
	$s_sort_by .= '<option value="1">' . $lang['Dl_file_description'] . '</option>';
	$s_sort_by .= '<option value="2">' . $lang['Dl_file_name'] . '</option>';
	$s_sort_by .= '<option value="3">' . $lang['Dl_klicks'] . '</option>';
	$s_sort_by .= '<option value="4">' . $lang['Dl_free'] . '</option>';
	$s_sort_by .= '<option value="5">' . $lang['Dl_extern'] . '</option>';
	$s_sort_by .= '<option value="6">' . $lang['Dl_file_size'] . '</option>';
	$s_sort_by .= '<option value="7">' . $lang['Last_updated'] . '</option>';
	$s_sort_by .= '<option value="8">' . $lang['Dl_rating'] . '</option>';
	$s_sort_by .= '</select>';
	$s_sort_by = str_replace('value="' . $sort_by . '">', 'value="' . $sort_by . '" selected="selected">', $s_sort_by);

	$s_order = '<select name="order" onchange="forms[\'dl_mod\'].submit()">';
	$s_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option>';
	$s_order .= '<option value="DESC">' . $lang['Sort_Descending'] . '</option>';
	$s_order .= '</select>';
	$s_order = str_replace('value="' . $order . '">', 'value="' . $order . '" selected="selected">', $s_order);
}

//create download overall view
if ($view == 'overall' && count($index))
{
	$page_title = $lang['Dl_overview'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$sql = "SELECT dl_id, user_id FROM " . DL_RATING_TABLE;
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not fetch ratings', '', __LINE__, __FILE__, $sql);
	}

	$ratings = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$ratings[$row['dl_id']][] = $row['user_id'];
	}
	$db->sql_freeresult($result);

	$template->set_filenames(array('body' => 'dl_overview_body.tpl'));

	$template->assign_vars(array(
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_DL_CAT' => $lang['Dl_cat_name'],
		'L_DL_FILES' => $lang['Dl_cat_files'],
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_STATUS' => $lang['Dl_info'],
		'L_KLICKS' => $lang['Dl_klicks'],
		'L_OVERALL_KLICKS' => $lang['Dl_overall_klicks'],
		'L_KL_M_T' => $lang['Dl_klicks_total'],
		'L_SIZE' => $lang['Dl_file_size'],
		'L_NAME' => $lang['Dl_name'],
		'L_TOP' => $lang['Back_to_top'],
		'L_SORT_BY' => $lang['Sort_by'],
		'L_ORDER' => $lang['Order'],
		'L_RATING' => $lang['Dl_rating'],
		'L_DOWNLOADS' => $lang['Dl_cat_title'],
		'L_GO' => $lang['Go'],

		'S_SORT_BY' => $s_sort_by,
		'S_ORDER' => $s_order,

		'U_DOWNLOADS_ADV' => append_sid('downloads.' . PHP_EXT . '?view=overall'),
		'U_DL_INDEX' => append_sid('downloads.' . PHP_EXT),

		'COLSPAN' => $colspan,
		'PAGE_NAME' => $page_title
		)
	);

	$dl_files = array();
	$dl_files = $dl_mod->all_files();

	$total_files = 0;

	if (count($dl_files))
	{
		for ($i = 0; $i < count($dl_files); $i++)
		{
			$cat_id = $dl_files[$i]['cat'];
			$cat_auth = array();
			$cat_auth = $dl_mod->dl_cat_auth($cat_id);
			if ($cat_auth['auth_view'] || $index[$cat_id]['auth_view'] || $userdata['user_level'] == ADMIN)
			{
				$total_files++;
			}
		}
	}

	if ($total_files > $board_config['topics_per_page'])
	{
		$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=overall&amp;sort_by=' . $sort_by . '&amp;order=' . $order, $total_files, $board_config['topics_per_page'], $start);
		$template->assign_vars(array(
			'PAGINATION' => $pagination
			)
		);
	}

	$sql_sort_by = ($sql_sort_by == 'sort') ? 'cat, sort' : $sql_sort_by;

	$dl_files = array();
	$dl_files = $dl_mod->all_files(0, '', '', ' ORDER BY ' . $sql_sort_by . ' ' . $sql_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'], 0, 0, 'cat, id, description, hack_version, extern, file_size, klicks, overall_klicks, rating');

	if (count($dl_files))
	{
		for ($i = 0; $i < count($dl_files); $i++)
		{
			$cat_id = $dl_files[$i]['cat'];
			$cat_auth = array();
			$cat_auth = $dl_mod->dl_cat_auth($cat_id);
			if ($cat_auth['auth_view'] || $index[$cat_id]['auth_view'] || $userdata['user_level'] == ADMIN)
			{
				$cat_name = $index[$cat_id]['cat_name'];
				$cat_name = str_replace('&nbsp;&nbsp;|', '', $cat_name);
				$cat_name = str_replace('___&nbsp;', '', $cat_name);
				$cat_view = $index[$cat_id]['nav_path'];

				$file_id = $dl_files[$i]['id'];
				$mini_file_icon = $dl_mod->mini_status_file($cat_id, $file_id);

				$description = $dl_files[$i]['description'];
				$dl_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$hack_version = '&nbsp;' . $dl_files[$i]['hack_version'];

				$dl_status = array();
				$dl_status = $dl_mod->dl_status($file_id);
				$status = $dl_status['status'];

				if ($dl_files[$i]['extern'])
				{
					$file_size = $lang['Dl_not_availible'];
				}
				else
				{
					$file_size = $dl_mod->dl_size($dl_files[$i]['file_size'], 2);
				}

				$file_klicks = $dl_files[$i]['klicks'];
				$file_overall_klicks = $dl_files[$i]['overall_klicks'];

				$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$rating_points = $dl_files[$i]['rating'];

				$u_rating_link = '';
				if (($rating_points == 0 || !@in_array($userdata['user_id'], $ratings[$file_id])) && $userdata['session_logged_in'])
				{
					$u_rating_link = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;action=rate&amp;df_id=' . $file_id . '&amp;dlo=1') . '">' . $lang['Dl_klick_to_rate'] . '</a>';
				}

				if (count($ratings[$file_id]))
				{
					$rating_count_text = '&nbsp;[ ' . count($ratings[$file_id]) . ' ]';
				}
				else
				{
					$rating_count_text = '';
				}

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('download', array(
					'ROW_CLASS' => $row_class,
					'DESCRIPTION' => $mini_icon.$description,
					'HACK_VERSION' => $hack_version,
					'STATUS' => $status,
					'FILE_SIZE' => $file_size,
					'FILE_KLICKS' => $file_klicks,
					'FILE_OVERALL_KLICKS' => $file_overall_klicks,
					'RATING_IMG' => $dl_mod->rating_img($rating_points),
					'RATINGS' => $rating_count_text,
					'U_CAT_VIEW' => $cat_view,
					'CAT_NAME' => $cat_name,
					'U_RATING' => $u_rating_link,
					'U_DL_LINK' => $dl_link
					)
				);
			}
		}
	}
}

//default user entry. redirect to index or category
if (empty($view) && !$inc_module)
{
	$view = 'view';

	if (!$cat)
	{
		$template->set_filenames(array('body' => 'view_dl_cat_body.tpl'));
	}
	else
	{
		$cat_auth = array();
		$cat_auth = $dl_mod->dl_cat_auth($cat);
		$index_auth = array();
		$index_auth = $dl_mod->full_index($cat);

		if (!$cat_auth['auth_view'] && !$index_auth[$cat]['auth_view'] && $userdata['user_level'] != ADMIN)
		{
			redirect(append_sid('downloads.' . PHP_EXT));
		}

		$template->set_filenames(array('body' => 'downloads_body.tpl'));

		$sql = "SELECT dl_id, user_id FROM " . DL_RATING_TABLE;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not fetch ratings', '', __LINE__, __FILE__, $sql);
		}

		$ratings = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$ratings[$row['dl_id']][] = $row['user_id'];
		}
		$db->sql_freeresult($result);
	}

	$path_dl_array = array();
	$page_title = $lang['Downloads'] . (($cat) ? ' ' . $dl_mod->dl_nav($cat, 'text') : '');
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
	$path_dl_array = array();

	$user_id = $userdata['user_id'];
	$username = $userdata['username'];
	$user_traffic = $userdata['user_traffic'];

	$sql = "SELECT c.parent, d.cat, d.id, d.change_time, d. description, d.change_user, u.username
		FROM " . DOWNLOADS_TABLE . " d, " . USERS_TABLE . " u, " . DL_CAT_TABLE . " c
		WHERE d.change_user = u.user_id
			AND d.approve = " . TRUE . "
			AND d.cat = c.id
		ORDER BY cat, change_time DESC, id DESC";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not fetch last downloads for categories', '', __LINE__, __FILE__, $sql);
	}

	$last_dl = array();
	$last_id = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['cat'] != $last_id)
		{
			$last_id = $row['cat'];
			$last_dl[$last_id]['change_time'] = $row['change_time'];
			$last_dl[$last_id]['parent'] = $row['parent'];
			$last_dl[$last_id]['desc'] = $row['description'];
			$last_dl[$last_id]['user'] = ($row['username'] == '') ? $lang['Guest'] : $row['username'];
			$last_dl[$last_id]['time'] = create_date($board_config['default_dateformat'], $row['change_time'], $board_config['board_timezone']);
			$last_dl[$last_id]['link'] = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $row['id']);
			//$last_dl[$last_id]['user_link'] = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['change_user']);
			$last_dl[$last_id]['user_link'] = colorize_username($row['change_user']);
		}
	}
	$db->sql_freeresult($result);

	if (count($index) > 0)
	{
		$i = 0;
		foreach(array_keys($index) as $key)
		{
			$cat_id = $key;
			$parent_id = $index[$cat_id]['parent'];
			$cat_name = $index[$cat_id]['cat_name'];
			$cat_desc = $index[$cat_id]['description'];
			$cat_view = $index[$cat_id]['nav_path'];
			$cat_bbcode_uid = $index[$cat_id]['bbcode_uid'];
			$cat_sublevel = $index[$cat_id]['sublevel'];

			if ($cat_desc)
			{
				/*
				$cat_desc = ($board_config['allow_html']) ? make_clickable($cat_desc) : preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $cat_desc);
				$cat_desc = ($board_config['allow_bbcode'] && $cat_bbcode_uid) ? $bbcode->parse($cat_desc, $cat_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $cat_desc);
				$cat_desc = ($board_config['allow_smilies']) ? smilies_pass($cat_desc) : $cat_desc;
				*/
				$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
				$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
				$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
				$cat_desc = $bbcode->parse($cat_desc, $cat_bbcode_uid);
				$cat_desc = str_replace("\n", "\n<br />", $cat_desc);
			}

			$mini_icon = array();
			$mini_icon = $dl_mod->mini_status_cat($cat_id, $cat_id);

			$mini_cat_icon = '';

			if ($mini_icon[$cat_id]['new'])
			{
				$mini_cat_icon .= '<img src="' . $images['Dl_new'] . '" border="0" alt="' . $lang['DL_new'] . '" title="' . $lang['DL_new'] . '" />&nbsp;';
			}
			if ($mini_icon[$cat_id]['edit'])
			{
				$mini_cat_icon .= '<img src="' . $images['Dl_edit'] . '" border="0" alt="' . $lang['DL_edit'] . '" title="' . $lang['DL_edit'] . '" />&nbsp;';
			}

			$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$cat_pages = '';
			if ($index[$cat_id]['total'] > $dl_config['dl_links_per_page'])
			{
				$cat_pages .= '<br />[&nbsp;' . $lang['Goto_page'] . ':';
				$page = 1;
				for ($j = 0; $j < $index[$cat_id]['total']; $j += $dl_config['dl_links_per_page'])
				{
					$cat_pages .= '&nbsp;<a href="' . append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id . '&amp;start=' . $j) . '">' . $page . '</a>';
					if ($page < ceil($index[$cat_id]['total'] / $dl_config['dl_links_per_page']))
					{
						$cat_pages .= ',';
					}
					$page++;
				}
				$cat_pages .= '&nbsp;]';
			}

			$last_dl_time = $dl_mod->find_latest_dl($last_dl, $cat_id);
			$last_cat_id = $last_dl_time['cat_id'];

			if ($last_dl[$cat_id]['change_time'] > $last_dl_time['change_time'])
			{
				$last_cat_id = $cat_id;
			}

			if ($cat)
			{
				$template->set_filenames(array('subcats' => 'view_dl_subcat_body.tpl'));

				$block = 'subcats';

				$template->assign_vars(array(
					'L_DESCRIPTION' => $lang['Dl_file_description'],
					'L_DL_CAT' => $lang['Dl_cat_name'],
					'L_DL_FILES' => $lang['Dl_cat_files'],
					'L_LAST' => $lang['Last_updated']
					)
				);
			}
			else
			{
				$block = 'downloads';
			}

			if ((time() - $last_dl[$last_cat_id]['change_time']) < $dl_config['dl_new_time'])
			{
				$cat_img = $images['forum_nor_read'];
			}
			else
			{
				$cat_img = $images['forum_nor_unread'];
			}

			$template->assign_block_vars($block, array(
				'ROW_CLASS' => $row_class,
				'ROW_SPAN' => (count($cat_sublevel['cat_path'])) ? 'rowspan="2"' : '',
				'MINI_IMG' => $mini_cat_icon,
				'U_CAT_VIEW' => $cat_view,
				'SUBLEVEL' => $cat_sublevel_out,
				'CAT_IMG' => $cat_img,
				'CAT_DESC' => $cat_desc,
				'CAT_NAME' => $cat_name,
				'CAT_PAGES' => $cat_pages,
				'CAT_DL' => $index[$cat_id]['total'],
				'CAT_LAST_DL' => $last_dl[$last_cat_id]['desc'],
				'CAT_LAST_USER' => $last_dl[$last_cat_id]['user'],
				'CAT_LAST_TIME' => $last_dl[$last_cat_id]['time'],
				'U_CAT_LAST_LINK' => $last_dl[$last_cat_id]['link'],
				'U_CAT_LAST_USER' => $last_dl[$last_cat_id]['user_link']
				)
			);

			if (count($cat_sublevel['cat_path']))
			{
				$template->assign_block_vars($block . '.sub', array());
			}

			for ($j = 0; $j < count($cat_sublevel['cat_path']); $j++)
			{
				$sub_id = $cat_sublevel['cat_sub'][$j];
				$mini_icon = array();
				$mini_icon = $dl_mod->mini_status_cat($sub_id, $sub_id);

				$mini_cat_icon = '';

				if ($mini_icon[$sub_id]['new'])
				{
					$mini_cat_icon .= '<img src="' . $images['Dl_new'] . '" border="0" alt="' . $lang['DL_new'] . '" title="' . $lang['DL_new'] . '" />&nbsp;';
				}
				if ($mini_icon[$sub_id]['edit'])
				{
					$mini_cat_icon .= '<img src="' . $images['Dl_edit'] . '" border="0" alt="' . $lang['DL_edit'] . '" title="' . $lang['DL_edit'] . '" />&nbsp;';
				}

				$row_class = (($j % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars($block.'.sub.sublevel_row', array(
					'ROW_CLASS' => $row_class,
					'L_SUBLEVEL' => $cat_sublevel['cat_name'][$j],
					'SUBLEVEL_COUNT' => $cat_sublevel['total'][$j] + $dl_mod->get_sublevel_count($cat_sublevel['cat_id'][$j]),
					'M_SUBLEVEL' => $mini_cat_icon,
					'U_SUBLEVEL' => $cat_sublevel['cat_path'][$j]
					)
				);
			}

			$i++;

			if ($cat)
			{
				$template->assign_var_from_handle('SUBCAT_BOX', 'subcats');
			}
		}
	}
	else
	{
		$template->assign_block_vars('no_category', array(
			'L_NO_CATEGORY' => $lang['Dl_no_category_index']
			)
		);
	}

	if ($cat)
	{
		$index_cat = array();
		$index_cat = $dl_mod->full_index($cat);
		$total_downloads = $index_cat[$cat]['total'];

		if ($total_downloads)
		{
			$pagination = generate_pagination('downloads.' . PHP_EXT . '?cat=' . $cat . '&amp;sort_by=' . $sort_by . '&amp;order=' . $order, $total_downloads, $dl_config['dl_links_per_page'], $start) . '&nbsp;';

			$template->assign_vars(array(
				'PAGINATION' => $pagination,
				'PAGE_NUMBER' => ($total_downloads > $dl_config['dl_links_per_page']) ? sprintf($lang['Page_of'], (floor($start / $dl_config['dl_links_per_page']) + 1), ceil($total_downloads / $dl_config['dl_links_per_page'])) : '',
				'L_GOTO_PAGE' => $lang['Goto_page']
				)
			);
		}

		if ($index_cat[$cat]['rules'])
		{
			$cat_rule = $index_cat[$cat]['rules'];
			$cat_bbcode_uid = $index_cat[$cat]['bbcode_uid'];
			/*
			$cat_rule = ($board_config['allow_html']) ? make_clickable($cat_rule) : preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $cat_rule);
			$cat_rule = ($board_config['allow_bbcode'] && $cat_bbcode_uid) ? $bbcode->parse($cat_rule, $cat_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $cat_rule);
			$cat_rule = ($board_config['allow_smilies']) ? smilies_pass($cat_rule) : $cat_rule;
			*/
			$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
			$cat_rule = $bbcode->parse($cat_rule, $cat_bbcode_uid);
			$cat_rule = str_replace("\n", "\n<br />", $cat_rule);
			$template->assign_block_vars('cat_rule', array(
				'CAT_RULE' => $cat_rule
				)
			);
		}

		if ($dl_mod->user_auth($cat, 'auth_mod'))
		{
			$template->assign_block_vars('modcp', array(
				'DL_MODCP' => ($total_downloads) ? sprintf($lang['Dl_modcp_mod_auth'], '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;cat_id=' . $cat) . '">', '</a>') : ''
				)
			);
		}

		$physical_size = $dl_mod->read_dl_sizes($dl_config['dl_path']);
		if ($physical_size < $dl_config['physical_quota'] && (!$dl_config['stop_uploads']) || $userdata['user_level'] == ADMIN)
		{
			if ($dl_mod->user_auth($cat, 'auth_up'))
			{
				$template->assign_vars(array(
					'DL_UPLOAD' => '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=upload&amp;cat_id=' . $cat) . '"><img src="' . $images['Dl_upload'] . '" border="0" alt="' . $lang['Dl_upload'] . '"  title="' . $lang['Dl_upload'] . '" /></a>&nbsp;&nbsp;&nbsp;'
					)
				);
			}
		}

		$cat_traffic = $index_cat[$cat]['cat_traffic'] - $index_cat[$cat]['cat_traffic_use'];
		if ($index_cat[$cat]['cat_traffic'] && $cat_traffic > 0)
		{
			$cat_traffic = ($cat_traffic > $dl_config['overall_traffic']) ? $dl_config['overall_traffic'] : $cat_traffic;
			$cat_traffic = $dl_mod->dl_size($cat_traffic);

			$template->assign_block_vars('cat_traffic', array(
				'CAT_TRAFFIC' => sprintf($lang['Dl_cat_traffic_main'], $cat_traffic)
				)
			);
		}
	}

	$i = 0;

	if ($cat && $total_downloads)
	{
		$dl_files = array();
		$dl_files = $dl_mod->files($cat, $sql_sort_by, $sql_order, $start, $dl_config['dl_links_per_page'], 'id, description, hack_version, extern, file_size, klicks, overall_klicks, rating, bbcode_uid, long_desc');

		if ($dl_mod->cat_auth_comment_read($cat))
		{
			$sql = "SELECT count(dl_id) AS total_comments, id FROM " . DL_COMMENTS_TABLE . "
				WHERE cat_id = $cat
					AND approve = " . TRUE . "
				GROUP BY id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not count comments for each file', '', __LINE__, __FILE__, $sql);
			}

			$comment_count = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$comment_count[$row['id']] = $row['total_comments'];
			}
			$db->sql_freeresult($result);
		}

		for ($i = 0; $i < count($dl_files); $i++)
		{
			$file_id = $dl_files[$i]['id'];
			$mini_file_icon = $dl_mod->mini_status_file($cat, $file_id);

			$description = $dl_files[$i]['description'];
			$file_url = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

			$hack_version = '&nbsp;' . $dl_files[$i]['hack_version'];

			$bbcode_uid = $dl_files[$i]['bbcode_uid'];
			$long_desc = stripslashes($dl_files[$i]['long_desc']);
			if (intval($dl_config['limit_desc_on_index']) && strlen($long_desc) > intval($dl_config['limit_desc_on_index']))
			{
				$long_desc = substr($long_desc, 0, intval($dl_config['limit_desc_on_index'])) . ' [...]';
			}

			/*
			$long_desc = ($bbcode_uid) ? $bbcode->parse($long_desc, $bbcode_uid) : $long_desc;
			$long_desc = make_clickable(smilies_pass($long_desc));
			*/
			$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
			$long_desc = $bbcode->parse($long_desc, $bbcode_uid);
			$long_desc = str_replace("\n", "\n<br />\n", $long_desc);

			$dl_status = array();
			$dl_status = $dl_mod->dl_status($file_id);
			$status = $dl_status['status'];

			if ($dl_files[$i]['extern'])
			{
				$file_size = $lang['Dl_not_availible'];
			}
			else
			{
				$file_size = $dl_mod->dl_size($dl_files[$i]['file_size'], 2);
			}

			$file_klicks = $dl_files[$i]['klicks'];
			$file_overall_klicks = $dl_files[$i]['overall_klicks'];

			$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			if ($cat)
			{
				$rating_points = $dl_files[$i]['rating'];

				$l_rating_text = $u_rating_text = '';
				if ((!$rating_points || !@in_array($userdata['user_id'], $ratings[$file_id])) && $userdata['session_logged_in'])
				{
					$l_rating_text = $lang['Dl_klick_to_rate'];
					$u_rating_text = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;action=rate&amp;df_id=' . $file_id . '&amp;dlo=2&amp;start=' . $start);
				}

				if (count($ratings[$file_id]))
				{
					$rating_count_text = '&nbsp;[ ' . count($ratings[$file_id]) . ' ]';
				}
				else
				{
					$rating_count_text = '';
				}
			}

			$template->assign_block_vars('downloads', array(
				'ROW_CLASS' => $row_class,
				'DESCRIPTION' => $description,
				'MINI_IMG' => $mini_file_icon,
				'HACK_VERSION' => $hack_version,
				'LONG_DESC' => $long_desc,
				'RATING_IMG' => $dl_mod->rating_img($rating_points),
				'RATINGS' => $rating_count_text,
				'L_RATING' => $l_rating_text,
				'U_RATING' => $u_rating_text,
				'STATUS' => $status,
				'FILE_SIZE' => $file_size,
				'FILE_KLICKS' => $file_klicks,
				'FILE_OVERALL_KLICKS' => $file_overall_klicks,
				'U_FILE' => $file_url
				)
			);

			$col_width = 6;

			if ($index_cat[$cat]['comments'] && $dl_mod->cat_auth_comment_read($cat))
			{
				$col_width++;

				if ($comment_count[$file_id])
				{
					$template->assign_block_vars('downloads.comments', array(
						'L_COMMENT_POST' => ($dl_mod->cat_auth_comment_post($cat)) ? $lang['Dl_post_comment'] : '',
						'L_COMMENT_SHOW' => $lang['Dl_view_comments'],
						'BREAK' => ($dl_mod->cat_auth_comment_post($cat)) ? '<br />' : '',
						'U_COMMENT_POST' => ($dl_mod->cat_auth_comment_post($cat)) ? append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=post&amp;cat_id=' . $cat . '&amp;df_id=' . $file_id) : '',
						'U_COMMENT_SHOW' => append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=view&amp;cat_id=' . $cat . '&amp;df_id=' . $file_id)
						)
					);
				}
				elseif ($dl_mod->cat_auth_comment_post($cat))
				{
					$template->assign_block_vars('downloads.comments', array(
						'L_COMMENT_POST' => $lang['Dl_post_comment'],
						'U_COMMENT_POST' => append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=post&amp;cat_id=' . $cat . '&amp;df_id=' . $file_id)
						)
					);
				}
				else
				{
					$template->assign_block_vars('downloads.comments', array());
				}
			}
		}
	}

	if ($i)
	{
		$template->assign_block_vars('download_rows', array());

		if ($index_cat[$cat]['comments'] && $dl_mod->cat_auth_comment_read($cat))
		{
			$sql = "SELECT count(dl_id) as total_comments, id FROM " . DL_COMMENTS_TABLE . "
				WHERE cat_id = $cat
					AND approve = " . TRUE . "
				GROUP BY id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not count comments for this category', '', __LINE__, __FILE__, $sql_comment);
			}

			$comment_count = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$comment_count[$row['id']] = $row['total_comments'];
			}
			$db->sql_freeresult($result_comment);

			$template->assign_block_vars('download_rows.comment_header', array(
				'L_COMMENTS' => $lang['Dl_comments']
				)
			);
		}
	}

	if ($cat && !$total_downloads)
	{
		$template->assign_block_vars('empty_category', array(
			'L_EMPTY_CATEGORY' => $lang['Dl_empty_category'],
			'COL_WIDTH' => 6
			)
		);
	}

	$template->assign_vars(array(
		'L_INFO' => $lang['Dl_info'],
		'L_NAME' => $lang['Dl_name'],
		'L_DL_CAT' => $lang['Dl_cat_name'],
		'L_DL_FILES' => $lang['Dl_cat_files'],
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_SIZE' => $lang['Dl_file_size'],
		'L_KLICKS' => $lang['Dl_klicks'],
		'L_OVERALL_KLICKS' => $lang['Dl_overall_klicks'],
		'L_KL_M_T' => $lang['Dl_klicks_total'],
		'L_RATING' => $lang['Dl_rating'],
		'L_SORT_BY' => $lang['Sort_by'],
		'L_ORDER' => $lang['Order'],
		'L_DL_TOP' => $lang['Dl_cat_title'],
		'L_LAST' => $lang['Last_updated'],
		'L_GO' => $lang['Go'],
		'L_LEGEND' => $lang['legend'],

		'COL_WIDTH' => $col_width,
		'ROW_1' => $theme['td_class1'],
		'ROW_2' => $theme['td_class2'],

		'S_SORT_BY' => $s_sort_by,
		'S_ORDER' => $s_order,

		'T_DL_CAT' => ($cat) ? $index[$cat]['cat_name'] : $lang['Dl_cat_name'],

		'U_DOWNLOADS' => append_sid('downloads.' . PHP_EXT . '?start=' . $start . '&amp;cat=' . $cat),
		'U_SEARCH' => (count($index) || $cat) ? '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=search') . '"><img src="' . $images['icon_search'] . '" border="0" alt="' . $lang['Search'] . '" title="' . $lang['Search'] . '" /></a>' : '&nbsp;',
		'U_DL_CAT' => ($cat) ? $dl_mod->dl_nav($cat, 'url') : '',

		'U_DL_TOP' => append_sid('downloads.' . PHP_EXT)
		)
	);
}

$view_check = array('comment', 'detail', 'load', 'modcp', 'overall', 'popup', 'search', 'stat', 'todo', 'upload', 'user_config', 'view', 'broken', 'unbroken', 'fav', 'unfav', 'bug_tracker');
if (!in_array($view, $view_check))
{
	message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
}

$template->assign_vars(array(
	'U_HELP_POPUP' => IP_ROOT_PATH . 'dl_help.' . PHP_EXT . '?help_key='
	)
);

//parse and build page
$template->pparse('body');

/*
* include the mod footer
*/
include(IP_ROOT_PATH . DL_ROOT_PATH . 'includes/dl_footer.' . PHP_EXT);

//include the phpBB footer
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>