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
*
*/

/**
*
* @Extra credits for this file
* psoTFX
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (isset($_POST['not_agreed']))
{
	redirect(append_sid(CMS_PAGE_HOME));
}

// Adding CPL_NAV only if needed
if (!defined('PARSE_CPL_NAV'))
{
	define('PARSE_CPL_NAV', true);
}

include_once(IP_ROOT_PATH . 'includes/auth_db.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
$server_url = create_server_url();
$profile_server_url = $server_url . CMS_PAGE_PROFILE;

$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

// BEGIN Disable Registration MOD
if($config['registration_status'] && !$userdata['session_logged_in'])
{
	if(empty($config['registration_closed']))
	{
		message_die(GENERAL_MESSAGE, 'registration_status', 'Information');
	}
	else
	{
		message_die(GENERAL_MESSAGE, $config['registration_closed'], 'Information');
	}
}
// END Disable Registration MOD

// CrackerTracker v5.x
// BEGIN CrackerTracker v5.x
include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
$profile_security = new ct_userfunctions();
$profile_security->handle_profile();
(isset($_POST['submit']))? $profile_security->password_functions() : null;
// END CrackerTracker v5.x
// CrackerTracker v5.x

// Load agreement template since user has not yet agreed to registration conditions/coppa
function show_coppa()
{
	global $userdata, $template, $config, $lang;

	// Load the appropriate Rules file
	$lang_file = 'lang_rules';
	$l_title = $lang['BoardRules'];

	// Include the rules settings
	include(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT);

	//
	// Pull the array data from the lang pack
	//
	$j = 0;
	$counter = 0;
	$counter_2 = 0;
	$rules_block = array();
	$rules_block_titles = array();

	for($i = 0; $i < sizeof($rules); $i++)
	{
		if($rules[$i][0] != '--')
		{
			$rules_block[$j][$counter]['id'] = $counter_2;
			$rules_block[$j][$counter]['question'] = $rules[$i][0];
			$rules_block[$j][$counter]['answer'] = $rules[$i][1];

			$counter++;
			$counter_2++;
		}
		else
		{
			$j = ($counter != 0) ? $j + 1 : 0;

			$rules_block_titles[$j] = $rules[$i][1];

			$counter = 0;
		}
	}

	$template->set_filenames(array('body' => 'agreement.tpl'));

	$template->assign_vars(array(
		'L_PAGE_TITLE' => $lang['Registration'],
		'REGISTRATION' => $lang['Registration'],
		'AGREEMENT' => $lang['Reg_agreement'],
		'L_AGREEMENT' => $lang['Agreement'],
		'L_PRIVACY_DISCLAIMER' => $lang['PrivacyDisclaimer'],
		'AGREE_OVER_13' => $lang['Agree_over_13'],
		'AGREE_UNDER_13' => $lang['Agree_under_13'],
		'DO_NOT_AGREE' => $lang['Agree_not'],
		'AGREE_CHECKBOX' => $lang['Agree_checkbox'],

		'L_RULES_TITLE' => $l_title,
		'L_BACK_TO_TOP' => $lang['Back_to_top'],

		'S_AGREE_ACTION' => append_sid(CMS_PAGE_PROFILE . '?mode=register&amp;agreed=true'),
		'U_AGREE_OVER13' => append_sid(CMS_PAGE_PROFILE . '?mode=register&amp;agreed=true'),
		'U_AGREE_UNDER13' => append_sid(CMS_PAGE_PROFILE . '?mode=register&amp;agreed=true&amp;coppa=true')
		)
	);

	for($i = 0; $i < sizeof($rules_block); $i++)
	{
		if(sizeof($rules_block[$i]))
		{
			$template->assign_block_vars('rules_block', array(
				'BLOCK_TITLE' => $rules_block_titles[$i]
				)
			);
			$template->assign_block_vars('rules_block_link', array(
				'BLOCK_TITLE' => $rules_block_titles[$i]
				)
			);

			for($j = 0; $j < sizeof($rules_block[$i]); $j++)
			{
				$row_class = (!($j % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('rules_block.rules_row', array(
					'ROW_CLASS' => $row_class,
					'RULES_QUESTION' => $rules_block[$i][$j]['question'],
					'RULES_ANSWER' => $rules_block[$i][$j]['answer'],

					'U_RULES_ID' => $rules_block[$i][$j]['id']
					)
				);

				$template->assign_block_vars('rules_block_link.rules_row_link', array(
					'ROW_CLASS' => $row_class,
					'RULES_LINK' => $rules_block[$i][$j]['question'],

					'U_RULES_LINK' => '#' . $rules_block[$i][$j]['id']
					)
				);
			}
		}
	}
	$template->pparse('body');
}

$error = false;
$error_msg = '';
$meta_content['page_title'] = ($mode == 'editprofile') ? $lang['Profile'] : $lang['Register'];
//$meta_content['page_title'] = ($mode == 'editprofile') ? $lang['Cpl_Navigation'] : $lang['Register'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';

// Block DNSBL Blacklisted Registrants (by TerraFrost) - BEGIN
$blacklist_enabled = ($config['disable_registration_ip_check'] == 1) ? false : true;
if (($mode == 'register') && (isset($_POST['submit'])) && ($blacklist_enabled == true))
{
	$address = $_SERVER['REMOTE_ADDR'];
	$rev = implode('.',array_reverse(explode('.', $address)));

	$lookup = "$rev.l1.spews.dnsbl.sorbs.net";
	if ($lookup != gethostbyname($lookup))
	{
		message_die(GENERAL_MESSAGE, strtr($lang['dsbl'],array('%url%' => "http://www.spews.org/ask.cgi?x=$address")));
	}

	$lookup = "$rev.sbl-xbl.spamhaus.org";
	if ($lookup != gethostbyname($lookup))
	{
		message_die(GENERAL_MESSAGE, strtr($lang['dsbl'],array('%url%' => "http://www.spamhaus.org/query/bl?ip=$address")));
	}

	$lookup = "$rev.list.dsbl.org";
	if ($lookup != gethostbyname($lookup))
	{
		message_die(GENERAL_MESSAGE, strtr($lang['dsbl'],array('%url%' => "http://dsbl.org/listing?$address")));
	}
}
// Block DNSBL Blacklisted Registrants (by TerraFrost) - END

if (isset($_GET['cpl_mode']) || isset($_POST['cpl_mode']))
{
	$cpl_mode = (isset($_GET['cpl_mode'])) ? $_GET['cpl_mode'] : $_POST['cpl_mode'];
	$cpl_mode = htmlspecialchars($cpl_mode);
}
if (($mode == 'register') || (($cpl_mode != 'all') && ($cpl_mode != 'reg_info') && ($cpl_mode != 'profile_info') && ($cpl_mode != 'preferences') && ($cpl_mode != 'board_settings') && ($cpl_mode != 'avatar') && ($cpl_mode != 'signature')))
{
	$cpl_mode = 'all';
}

//if (($mode == 'register') && !isset($_POST['agreed']) && !isset($_GET['agreed']))
if (($mode == 'register') && (!isset($_POST['agreed']) || !isset($_POST['privacy'])))
{
	$link_name = $lang['Register'];
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE . '?mode=register') . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
	page_header('', true);
	if (isset($_POST['agreed']))
	{
		$template->set_filenames(array('reg_header' => 'error_body.tpl'));
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $lang['Agree_checkbox_Error']
			)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}
	show_coppa();
	page_footer(true, '', true);
}

if ($mode != 'register')
{
	$template->assign_block_vars('switch_cpl_menu', array());
}

// Check and initialize some variables if needed
if (isset($_POST['submit']) || isset($_POST['avatargallery']) || isset($_POST['submitavatar']) || isset($_POST['avatargenerator']) || isset($_POST['submitgenava']) || isset($_POST['cancelavatar']) || $mode == 'register')
{
	// Reset some config values which are overridden from init_userprefs
	$config_tmp = get_config(false);
	$config['default_lang'] = $config_tmp['default_lang'];
	$config['board_timezone'] = $config_tmp['board_timezone'];
	$config['default_dateformat'] = $config_tmp['default_dateformat'];

	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	$sid = request_var('sid', '');

	$strip_var_list = array(
		'email' => 'email',
		'email_confirm' => 'email_confirm',
		'icq' => 'icq',
		'aim' => 'aim',
		'msn' => 'msn',
		'yim' => 'yim',
		'skype' => 'skype',
		'website' => 'website',
		'location' => 'location',
		'occupation' => 'occupation',
		'interests' => 'interests',
		'phone' => 'phone',
		'confirm_code' => 'confirm_code'
	);

	// Strip all tags from data ... may p**s some people off, bah, strip_tags is doing the job but can still break HTML output ... have no choice, have to use htmlspecialchars ... be prepared to be moaned at.
	while(list($var, $param) = @each($strip_var_list))
	{
		$$var = request_post_var($param, '', true);
	}

	if ($mode == 'editprofile')
	{
		$user_id = request_post_var('user_id', 0);
		$current_email = request_post_var('current_email', '', true);
		$email_confirm = request_post_var('current_email', '', true);
	}

	$trim_var_list = array(
		'cur_password' => 'cur_password',
		'new_password' => 'new_password',
		'password_confirm' => 'password_confirm',
		'signature' => 'signature',
		'selfdes' => 'selfdes',
		'username' => 'username'
	);
	while(list($var, $param) = @each($trim_var_list))
	{
		$$var = request_post_var($param, '', true);
		$$var = htmlspecialchars_decode($$var, ENT_COMPAT);
	}
	$signature = str_replace('<br />', "\n", $signature);
	$selfdes = str_replace('<br />', "\n", $selfdes);
	$username = phpbb_clean_username($username);

	$gender = request_post_var('gender', 0);

	// Birthday - BEGIN
	if (isset($_POST['birthday']))
	{
		$birthday = intval($_POST['birthday']);
		if ($birthday != 999999)
		{
			$birthday_day = realdate('j', $birthday);
			$birthday_month = realdate('n', $birthday);
			$birthday_year = realdate('Y', $birthday);
		}
	}
	else
	{
		$birthday_day = request_post_var('b_day', 0);
		$birthday_month = request_post_var('b_md', 0);
		$birthday_year = request_post_var('b_year', 0);
		if ($birthday_day && $birthday_month && $birthday_year)
		{
			$birthday = mkrealdate($birthday_day, $birthday_month, $birthday_year);
		}
		else
		{
			$birthday = 999999;
		}
	}
	// Birthday - END

	// Run some validation on the optional fields. These are pass-by-ref, so they'll be changed to empty strings if they fail.
	validate_optional_fields($icq, $aim, $msn, $yim, $skype, $website, $location, $occupation, $interests, $phone, $selfdes, $signature);

//<!-- BEGIN Unread Post Information to Database Mod -->
	$upi2db_which_system = request_post_var('upi2db_which_system', 0);
	$upi2db_new_word = request_post_var('upi2db_new_word', 0);
	$upi2db_edit_word = request_post_var('upi2db_edit_word', 0);
	$upi2db_unread_color = request_post_var('upi2db_unread_color', 0);
//<!-- END Unread Post Information to Database Mod -->

	$allowviewonline = request_post_var('hideonline', 0);
	$allowviewonline = !empty($allowviewonline) ? 0 : 1;
	$profile_view_popup = request_post_var('profile_view_popup', 0);
	$viewemail = request_post_var('viewemail', 0);
	$allowmassemail = request_post_var('allowmassemail', 1);
	$allowpmin = request_post_var('allowpmin', 1);
	$notifyreply = request_post_var('notifyreply', 0);
	$notifypm = request_post_var('notifypm', 1);
	$popup_pm = request_post_var('popup_pm', 1);
	$setbm = request_post_var('setbm', 0);

	if ($mode == 'register')
	{
		$attachsig = request_post_var('attachsig', 0);
		$allowhtml = request_post_var('allowhtml', $config['allow_html']);
		$allowbbcode = request_post_var('allowbbcode', $config['allow_bbcode']);
		$allowsmilies = request_post_var('allowsmilies', $config['allow_smilies']);
		$allowswearywords = request_post_var('allowswearywords', 0);
		$showavatars = request_post_var('showavatars', 1);
		$showsignatures = request_post_var('showsignatures', 1);
		$user_topics_per_page = request_post_var('user_topics_per_page', $config['topics_per_page']);
		$user_posts_per_page = request_post_var('user_posts_per_page', $config['posts_per_page']);
		$user_hot_threshold = request_post_var('user_hot_threshold', $config['hot_threshold']);
	}
	else
	{
		$attachsig = request_post_var('attachsig', $userdata['user_attachsig']);
		$retrosig = request_post_var('retrosig', 0);
		$allowhtml = request_post_var('allowhtml', $userdata['user_allowhtml']);
		$allowbbcode = request_post_var('allowbbcode', $userdata['user_allowbbcode']);
		$allowsmilies = request_post_var('allowsmilies', $userdata['user_allowsmile']);
		$allowswearywords = request_post_var('allowswearywords', $userdata['user_allowswearywords']);
		$showavatars = request_post_var('showavatars', $userdata['user_showavatars']);
		$showsignatures = request_post_var('showsignatures', $userdata['user_showsignatures']);
		$user_topics_per_page = request_post_var('user_topics_per_page', $userdata['topics_per_page']);
		$user_posts_per_page = request_post_var('user_posts_per_page', $userdata['posts_per_page']);
		$user_hot_threshold = request_post_var('user_hot_threshold', $userdata['hot_threshold']);
	}

	$user_topics_per_page = ($user_topics_per_page > 100) ? 100 : $user_topics_per_page;
	$user_posts_per_page = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;
	$user_hot_threshold = ($user_hot_threshold > 50) ? 50 : $user_hot_threshold;

	$user_style = request_post_var('style', $config['default_style']);

	$user_lang = request_post_var('language', $config['default_lang']);
	if (!empty($user_lang))
	{
		if (!preg_match('/^[a-z_]+$/i', $user_lang))
		{
			$error = true;
			$error_msg = $lang['Fields_empty'];
		}
	}
	else
	{
		$user_lang = $config['default_lang'];
	}

	$user_flag = request_post_var('user_flag', '');
	$user_timezone = request_post_var('timezone', $config['board_timezone']);
	$time_mode = request_post_var('time_mode', $config['default_time_mode']);

	$dst_time_lag = request_post_var('dst_time_lag', $config['default_dst_time_lag']);

	if (eregi("[^0-9]", $dst_time_lag) || ($dst_time_lag < 0) || ($dst_time_lag > 120))
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['dst_time_lag_error'];
	}

	$user_dateformat = request_post_var('dateformat', $config['default_dateformat']);

	$user_avatarselect = request_post_var('avatarselect', '');
	$user_avatarlocal = request_post_var('avatarlocal', '');
	$user_avatarcatname = request_post_var('avatarcatname', '');
	$user_avatarimage = request_post_var('avatarimage', '');
	$user_avatartext = request_post_var('avatartext', '');
	$user_avatar_filename = request_post_var('avatar_filename', '');
	$user_avatargenerator = request_post_var('avatargenerator', '');
	$user_avatar_remoteurl = request_post_var('avatarremoteurl', '');
	$user_avatarurl = request_post_var('avatarurl', '');
	$user_gravatar = request_post_var('gravatar', '');

	$user_avatar_local = (!empty($user_avatarselect) && !empty($_POST['submitavatar']) && $config['allow_avatar_local']) ? $user_avatarselect : ((!empty($user_avatarlocal) ? $user_avatarlocal : ''));
	$user_avatar_category = (!empty($user_avatarcatname) && $config['allow_avatar_local']) ? $user_avatarcatname : '' ;
	$user_avatar_generator = (!empty($user_avatarimage) && !empty($user_avatartext) && !empty($_POST['submitgenava']) && $config['allow_avatar_generator']) ? $user_avatar_filename : (!empty($user_avatargenerator) ? $user_avatargenerator : '');

	$user_avatar_remoteurl = (!empty($user_avatar_remoteurl) ? $user_avatar_remoteurl : '');
	$user_avatar_upload = (!empty($user_avatarurl) ? $user_avatarurl : (($_FILES['avatar']['tmp_name'] != 'none') ? $_FILES['avatar']['tmp_name'] : ''));
	$user_avatar_name = (!empty($_FILES['avatar']['name']) ? $_FILES['avatar']['name'] : '');
	$user_avatar_size = (!empty($_FILES['avatar']['size']) ? $_FILES['avatar']['size'] : 0);
	$user_avatar_filetype = (!empty($_FILES['avatar']['type']) ? $_FILES['avatar']['type'] : '');
	$user_gravatar = (!empty($user_gravatar) ? $user_gravatar : '');

	$user_avatar = (empty($user_avatar_local) && $mode == 'editprofile') ? $userdata['user_avatar'] : '';
	$user_avatar_type = (empty($user_avatar_local) && $mode == 'editprofile') ? $userdata['user_avatar_type'] : '';

	if ((isset($_POST['avatargallery']) || isset($_POST['submitavatar'])|| isset($_POST['avatargenerator']) || isset($_POST['submitgenava']) || isset($_POST['cancelavatar'])) && (!isset($_POST['submit'])))
	{
		$cur_password = htmlspecialchars($cur_password);
		$new_password = htmlspecialchars($new_password);
		$password_confirm = htmlspecialchars($password_confirm);
		$selfdes = htmlspecialchars($selfdes);
		$signature = htmlspecialchars($signature);

		if (!isset($_POST['cancelavatar']))
		{
			if (isset($_POST['submitgenava']))
			{
				$user_avatar = $user_avatar_generator;
				$user_avatar_type = USER_AVATAR_GENERATOR;
			}
			else
			{
				$user_avatar = $user_avatar_category . '/' . $user_avatar_local;
				$user_avatar_type = USER_AVATAR_GALLERY;
			}
		}
	}
}

//
// Let's make sure the user isn't logged in while registering,
// and ensure that they were trying to register a second time
// (Prevents double registrations)
//
if (($mode == 'register') && ($userdata['session_logged_in'] || ($username == $userdata['username'])))
{
	message_die(GENERAL_MESSAGE, $lang['Username_taken'], '', __LINE__, __FILE__);
}

//
// Did the user submit? In this case build a query to update the users profile in the DB
//
if (isset($_POST['submit']))
{
	include(IP_ROOT_PATH . 'includes/usercp_avatar.' . PHP_EXT);

	// session id check
	if (($sid == '') || ($sid != $userdata['session_id']))
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Session_invalid'];
	}

	$passwd_sql = '';
	if ($mode == 'editprofile')
	{
		if ($user_id != $userdata['user_id'])
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Wrong_Profile'];
		}
	}
	elseif ($mode == 'register')
	{
		if (empty($username) || empty($new_password) || empty($password_confirm) || empty($email) || empty($email_confirm))
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
		}
	}

	if ($email != $email_confirm)
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Email_mismatch'];
	}

	// Custom Profile Fields - BEGIN
	if (($mode == 'register') || ($cpl_mode == 'profile_info'))
	{
		$profile_data = get_fields('WHERE users_can_view = ' . ALLOW_VIEW);
		$profile_names = array();

		foreach($profile_data as $fields)
		{
			$name = text_to_column($fields['field_name']);
			$type = $fields['field_type'];
			$required = ($fields['is_required'] == REQUIRED) ? true : false;

			// Mighty Gorgon: maybe better using the old way instead of request_var... unless we decide to rewrite the way this mod works
			//$temp = request_post_var($name, '', true);
			$temp = (isset($_POST[$name])) ? $_POST[$name] : array();
			if($type == CHECKBOX)
			{
				$temp2 = '';
				foreach($temp as $temp3)
				{
					$temp2 .= htmlspecialchars($temp3) . ',';
				}
				$temp2 = substr($temp2, 0, strlen($temp2) - 1);

				$temp = $temp2;
			}
			else
			{
				//$temp = is_numeric($temp) ? intval($temp) : htmlspecialchars($temp);
				$temp = is_numeric($temp) ? intval($temp) : (is_array($temp) ? array_map('htmlspecialchars', $temp) : htmlspecialchars($temp));
			}
			$profile_names[$name] = $temp;

			if($required && empty($profile_names[$name]))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}
		}
	}
	// Custom Profile Fields - END

	if ($config['enable_confirm'] && ($mode == 'register'))
	{
		if (empty($_POST['confirm_id']))
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['CONFIRM_CODE_WRONG'];
		}
		else
		{
			$confirm_id = htmlspecialchars($_POST['confirm_id']);
			if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
			{
				$confirm_id = '';
			}

			$sql = "SELECT code
				FROM " . CONFIRM_TABLE . "
				WHERE confirm_id = '" . $confirm_id . "'
					AND session_id = '" . $userdata['session_id'] . "'";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{

				if ($row['code'] != $confirm_code)
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['CONFIRM_CODE_WRONG'];
				}
				else
				{
					$sql = "DELETE FROM " . CONFIRM_TABLE . "
						WHERE confirm_id = '" . $confirm_id . "'
							AND session_id = '" . $userdata['session_id'] . "'";
					$db->sql_query($sql);
				}
			}
			else
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['CONFIRM_CODE_WRONG'];
			}
			$db->sql_freeresult($result);
		}
	}

	$passwd_sql = '';
	if (!empty($new_password) && !empty($password_confirm))
	{
		if ($new_password != $password_confirm)
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_mismatch'];
		}
		elseif (strlen($new_password) > 32)
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_long'];
		}
		else
		{
			if ($mode == 'editprofile')
			{
				$login_result = login_db($username, $cur_password, $user_id, false);
				if ($login_result['status'] !== LOGIN_SUCCESS)
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Current_password_mismatch'];
				}
			}

			if (!$error)
			{
				// CrackerTracker v5.x
				$profile_security->pw_create_date($user_id);
				// CrackerTracker v5.x
				//$new_password = md5($new_password);
				$passwd_sql = "user_password = '" . $db->sql_escape(phpbb_hash($new_password)) . "', ";
			}
		}
	}
	elseif ((empty($new_password) && !empty($password_confirm)) || (!empty($new_password) && empty($password_confirm)))
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_mismatch'];
	}

	// Do a ban check on this email address
	//if (($email != $userdata['user_email']) || ($mode == 'register'))
	if (($email != $userdata['user_email']) || ($email_confirm != $userdata['user_email']) || ($email != $email_confirm) || ($mode == 'register'))
	{
		$result = validate_email($email);
		if ($result['error'])
		{
			$email = $userdata['user_email'];
			$email_confirm = $userdata['user_email'];
			$email = $email_confirm;

			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
		}

		if ($email != $email_confirm)
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Email_mismatch'];
		}

		if ($mode == 'editprofile')
		{
			$login_result = login_db($username, $cur_password, $user_id, false);
			if ($login_result['status'] !== LOGIN_SUCCESS)
			{
				$email = $userdata['user_email'];
				$email_confirm = $userdata['user_email'];
				$email = $email_confirm;

				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Current_password_mismatch'];
			}
		}
	}

	$username_sql = '';
	if ($config['allow_namechange'] || ($mode == 'register'))
	{
		if (empty($username))
		{
			// Error is already triggered, since one field is empty.
			$error = true;
		}
		elseif (($username != $userdata['username']) || ($mode == 'register'))
		{
			if (strtolower($username) != strtolower($userdata['username']) || $mode == 'register')
			{
				$result = validate_username($username);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
			}

			if (!$error)
			{
				$username_sql = "username = '" . $db->sql_escape($username) . "', ";
			}
		}
	}

	if ($signature != '')
	{
		if ($userdata['user_level'] != ADMIN)
		{
			if (strlen(preg_replace('#(<)([\/]?.*?)(>)#is', "", preg_replace('#(\[)([\/]?.*?)(\])#is', "", $signature))) > $config['max_sig_chars'])
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Signature_too_long'];
			}
		}

		$signature = prepare_message($signature, $allowhtml, $allowbbcode, $allowsmilies);
	}

/*
	if ($selfdes != '')
	{

		$config['extra_max'] = 0;
		if ($config['extra_max'] != '0')
		{
			if (strlen($selfdes) > $config['extra_max'])
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . sprintf($lang['Extra_too_long'],$config['extra_max']);
			}
		}

		$bbcode->allow_html = $config['allow_html'];
		$bbcode->allow_bbcode = $config['allow_bbcode'];
		$bbcode->allow_smilies = $config['allow_smilies'];
		$selfdes = $bbcode->parse($selfdes);
	}
*/

	if ($website != '')
	{
		rawurlencode($website);
	}

	$avatar_sql = '';

	if (isset($_POST['avatardel']) && ($mode == 'editprofile'))
	{
		$avatar_sql = user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']);
	}
	elseif ((!empty($user_avatar_upload) || !empty($user_avatar_name)) && $config['allow_avatar_upload'])
	{
		if (!empty($user_avatar_upload))
		{
			$avatar_mode = (empty($user_avatar_name)) ? 'remote' : 'local';
			$avatar_sql = user_avatar_upload($mode, $avatar_mode, $userdata['user_avatar'], $userdata['user_avatar_type'], $error, $error_msg, $user_avatar_upload, $user_avatar_name, $user_avatar_size, $user_avatar_filetype);
		}
		elseif (!empty($user_avatar_name))
		{
			$l_avatar_size = sprintf($lang['Avatar_filesize'], round($config['avatar_filesize'] / 1024));

			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $l_avatar_size;
		}
	}
	elseif ($user_avatar_remoteurl != '' && $config['allow_avatar_remote'])
	{
		user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']);
		$avatar_sql = user_avatar_url($mode, $error, $error_msg, $user_avatar_remoteurl);
	}
	elseif ($user_avatar_local != '' && $config['allow_avatar_local'])
	{
		user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']);
		$avatar_sql = user_avatar_gallery($mode, $error, $error_msg, $user_avatar_local, $user_avatar_category);
	}
	elseif ($user_avatar_generator != '' && $config['allow_avatar_generator'])
	{
		if (@file_exists(@phpbb_realpath('./' . $config['avatar_path'] . '/' . $userdata['user_avatar'])))
		{
			@unlink(@phpbb_realpath('./' . $config['avatar_path'] . '/' . $userdata['user_avatar']));
		}
		$avatar_sql = user_avatar_generator($mode, $error, $error_msg, $user_avatar_generator);
	}
	elseif ($user_gravatar != '' && $config['enable_gravatars'])
	{
		$avatar_sql = ($mode == 'editprofile') ? ", user_avatar = '" . $db->sql_escape($user_gravatar) . "', user_avatar_type = " . USER_GRAVATAR : '';
	}

	// Start add - Gender Mod
	if ($config['gender_required'])
	{
		if (!$gender)
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Gender_require'];
		}
	}
	// End add - Gender Mod

	// Birthday - BEGIN
	// find the birthday values, reflected by the $lang['Submit_date_format']
	if ($birthday_day || $birthday_month || $birthday_year) //if a birthday is submited, then validate it
	{
		$user_age = (gmdate('md') >= $birthday_month . (($birthday_day <= 9) ? '0' : '') . $birthday_day) ? gmdate('Y') - $birthday_year : gmdate('Y') - $birthday_year - 1;
		// Check date, maximum / minimum user age
		if(!checkdate($birthday_month, $birthday_day, $birthday_year))
		{
			$error = true;
			if(isset($error_msg))
			{
				$error_msg .= '<br />';
			}
			$error_msg .= $lang['Wrong_birthday_format'];
		}
		elseif($user_age>$config['max_user_age'])
		{
			$error = true;
			if(isset($error_msg))
			{
				$error_msg .= '<br />';
			}
			$error_msg .= sprintf($lang['Birthday_to_high'], $config['max_user_age']);
		}
		elseif($user_age<$config['min_user_age'])
		{
			$error = true;
			if(isset($error_msg))
			{
				$error_msg .= '<br />';
			}
			$error_msg .= sprintf($lang['Birthday_to_low'], $config['min_user_age']);
		}
		else
		{
			$birthday = ($error) ? $birthday : mkrealdate($birthday_day, $birthday_month, $birthday_year);
			$next_birthday_greeting = (gmdate('md') < $birthday_month . (($birthday_day <= 9) ? '0' : '') . $birthday_day) ? gmdate('Y') : gmdate('Y') + 1 ;
		}
	}
	else
	{
		if ($config['birthday_required'])
		{
			$error = true;
			if(isset($error_msg))
			{
				$error_msg .= '<br />';
			}
			$error_msg .= sprintf($lang['Birthday_require']);
		}
		$birthday = 999999;
		$next_birthday_greeting = '0';
	}
	// Birthday - END


	if (!$error)
	{
		if ($avatar_sql == '')
		{
			$avatar_sql = ($mode == 'editprofile') ? '' : "'', " . USER_AVATAR_NONE;
		}

		if ($mode == 'editprofile')
		{
			if (($email != $userdata['user_email']) && ($config['require_activation'] != USER_ACTIVATION_NONE) && ($userdata['user_level'] != ADMIN))
			{
				$user_active = 0;

				$user_actkey = gen_rand_string();
				//$key_len = 54 - (strlen($server_url));
				$key_len = 54 - (strlen($profile_server_url));
				$key_len = ($key_len > 6) ? $key_len : 6;
				$user_actkey = substr($user_actkey, 0, $key_len);

				if ($userdata['session_logged_in'])
				{
					session_end($userdata['session_id'], $userdata['user_id']);
				}
			}
			else
			{
				$user_active = 'user_active';
				$user_actkey = 'user_actkey';
			}
// Unread Post Information to Database Mod
// IN LINE ADD
// , user_upi2db_which_system = $upi2db_which_system, user_upi2db_new_word = $upi2db_new_word, user_upi2db_edit_word = $upi2db_edit_word, user_upi2db_unread_color = $upi2db_unread_color
			$sql = "UPDATE " . USERS_TABLE . "
				SET " . $username_sql . $passwd_sql . "user_email = '" . $db->sql_escape($email) ."', user_upi2db_which_system = $upi2db_which_system, user_upi2db_new_word = $upi2db_new_word, user_upi2db_edit_word = $upi2db_edit_word, user_upi2db_unread_color = $upi2db_unread_color, user_icq = '" . $db->sql_escape($icq) . "', user_website = '" . $db->sql_escape($website) . "', user_occ = '" . $db->sql_escape($occupation) . "', user_from = '" . $db->sql_escape($location) . "', user_from_flag = '$user_flag', user_interests = '" . $db->sql_escape($interests) . "', user_phone = '" . $db->sql_escape($phone) . "', user_selfdes = '" . $db->sql_escape($selfdes) . "', user_profile_view_popup = $profile_view_popup, user_birthday = '$birthday', user_birthday_y = '$birthday_year', user_birthday_m = '$birthday_month', user_birthday_d = '$birthday_day', user_next_birthday_greeting = '$next_birthday_greeting', user_viewemail = $viewemail, user_aim = '" . $db->sql_escape(str_replace(' ', '+', $aim)) . "', user_yim = '" . $db->sql_escape($yim) . "', user_msnm = '" . $db->sql_escape($msn) . "', user_skype = '" . $db->sql_escape($skype) . "', user_attachsig = $attachsig, user_setbm = $setbm, user_allowsmile = $allowsmilies, user_showavatars = $showavatars, user_showsignatures = $showsignatures, user_allowswearywords = $allowswearywords, user_allowhtml = $allowhtml, user_allowbbcode = $allowbbcode, user_allow_mass_email = $allowmassemail, user_allow_pm_in = $allowpmin, user_allow_viewonline = $allowviewonline, user_notify = $notifyreply, user_notify_pm = $notifypm, user_popup_pm = $popup_pm, user_timezone = $user_timezone, user_time_mode = $time_mode, user_dst_time_lag = $dst_time_lag, user_dateformat = '" . $db->sql_escape($user_dateformat) . "', user_posts_per_page = '" . $db->sql_escape($user_posts_per_page) . "', user_topics_per_page = '" . $db->sql_escape($user_topics_per_page) . "', user_hot_threshold = '" . $db->sql_escape($user_hot_threshold) . "', user_lang = '" . $db->sql_escape($user_lang) . "', user_style = $user_style, user_active = $user_active, user_actkey = '$user_actkey'" . $avatar_sql . ", user_gender = '" . $gender . "'
				WHERE user_id = " . $user_id;
			$result = $db->sql_query($sql);

			if (!empty($username_sql))
			{
				update_user_posts_details($user_id, '', '', true, true);
			}

			// Custom Profile Fields - BEGIN
			if (($mode == 'register') || ($cpl_mode == 'profile_info'))
			{
				$profile_data = get_fields('WHERE users_can_view = ' . ALLOW_VIEW);
				$profile_names = array();
				$semaphore = 0;

				if (!$profile_data)
				{

				}
				else
				{
					$sql2 = "UPDATE " . USERS_TABLE . " SET ";
					foreach($profile_data as $fields)
					{
						$name = text_to_column($fields['field_name']);
						$type = $fields['field_type'];
						$required = ($fields['is_required'] == REQUIRED) ? true : false;

						if(isset($_POST[$name]))
						{
							$temp = $_POST[$name];
							if($type == CHECKBOX)
							{
								$temp2 = '';
								foreach($temp as $temp3)
								{
									$temp2 .= htmlspecialchars($temp3) . ',';
								}
								$temp2 = substr($temp2, 0, strlen($temp2) - 1);
								$temp = $temp2;
							}
							else
							{
								//$temp = is_numeric($temp) ? intval($temp) : htmlspecialchars($temp);
								$temp = is_numeric($temp) ? intval($temp) : (is_array($temp) ? array_map('htmlspecialchars', $temp) : htmlspecialchars($temp));
							}
							$profile_names[$name] = $temp;
							$sql2 .= $name . " = '" . $db->sql_escape($profile_names[$name]) . "', ";
						}
						else
						{
							$sql2 .= $name . " = '', ";
						}
						$semaphore++;
					}
					$sql2 = substr($sql2, 0, strlen($sql2) - 2) . " WHERE user_id = '" . $userdata['user_id'] . "'";
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2 && $semaphore)
					{
						message_die(GENERAL_ERROR,'Could not update custom profile fields','',__LINE__,__FILE__,$sql2);
					}
				}
			}
			// Custom Profile Fields - END

			// We remove all stored login keys since the password has been updated
			// and change the current one (if applicable)
			if (!empty($passwd_sql))
			{
				session_reset_keys($user_id, $user_ip);
			}
			// Retroactive Signature - BEGIN
			if ($retrosig)
			{
				$sql = "UPDATE " . POSTS_TABLE .
					 " SET enable_sig = 1" .
					 " WHERE poster_id = '$user_id'";
				$result = $db->sql_query($sql);
			}
			// Retroactive Signature - END

			if (!$user_active)
			{
				//
				// The users account has been deactivated, send them an email with a new activation key
				//
				include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
				$emailer = new emailer();

				if ($config['require_activation'] != USER_ACTIVATION_ADMIN)
				{
					$emailer->use_template('user_activate', stripslashes($user_lang));
					$emailer->to($email);
					$emailer->set_subject($lang['Reactivate']);

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
						'EMAIL_SIG' => $email_sig,
						//'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
						'U_ACTIVATE' => $profile_server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
						)
					);
					$emailer->send();
					$emailer->reset();
				}
				elseif ($config['require_activation'] == USER_ACTIVATION_ADMIN)
				{
					$sql = 'SELECT user_email, user_lang
						FROM ' . USERS_TABLE . '
						WHERE user_level = ' . ADMIN;
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$emailer->from($config['board_email']);
						$emailer->replyto($config['board_email']);

						$emailer->email_address(trim($row['user_email']));
						$emailer->use_template('admin_activate', $row['user_lang']);
						$emailer->set_subject($lang['Reactivate']);

						$email_sig = create_signature($config['board_email_sig']);
						$emailer->assign_vars(array(
							'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
							'EMAIL_SIG' => $email_sig,
							//'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
							'U_ACTIVATE' => $profile_server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
							)
						);
						$emailer->send();
						$emailer->reset();
					}
					$db->sql_freeresult($result);
				}

				$message = $lang['Profile_updated_inactive'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');
			}
			else
			{
				$redirect_url = CMS_PAGE_PROFILE . '?mode=editprofile&' . $_SERVER['QUERY_STRING'];
				$message = $lang['Profile_updated'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>') . '<br /><br />' . sprintf($lang['Cpl_Click_Return_Cpl'], '<a href="' . append_sid($redirect_url) . '">', '</a>');
			}

			$redirect_url = append_sid($redirect_url);
			meta_refresh(3, $redirect_url);
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$sql = "SELECT MAX(user_id) AS total
				FROM " . USERS_TABLE;
			$result = $db->sql_query($sql);

			if (!($row = $db->sql_fetchrow($result)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}
			$user_id = $row['total'] + 1;
			// Start Advanced IP Tools Pack MOD
			$user_registered_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
			$user_registered_hostname = (gethostbyaddr($user_registered_ip)) ? gethostbyaddr($user_registered_ip) : $user_registered_ip;
			$user_registered_hostname = addslashes($user_registered_hostname);
			$user_registered_ip = encode_ip($user_registered_ip);
			// End Advanced IP Tools Pack MOD

			// Get current date
// Unread Post Information to Database Mod
// IN LINE ADD
// , user_upi2db_which_system, user_upi2db_new_word, user_upi2db_edit_word, user_upi2db_unread_color
// , $upi2db_which_system, $upi2db_new_word, $upi2db_edit_word, $upi2db_unread_color
			$sql = "INSERT INTO " . USERS_TABLE . " (user_registered_ip, user_registered_hostname, user_id, username, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_from_flag, user_interests, user_phone, user_selfdes, user_profile_view_popup, user_sig, user_avatar, user_avatar_type, user_viewemail, user_upi2db_which_system, user_upi2db_new_word, user_upi2db_edit_word, user_upi2db_unread_color, user_aim, user_yim, user_msnm, user_skype, user_attachsig, user_allowsmile, user_showavatars, user_showsignatures, user_allowswearywords, user_allowhtml, user_allowbbcode, user_allow_pm_in, user_allow_mass_email, user_allow_viewonline, user_notify, user_notify_pm, user_popup_pm, user_timezone, user_time_mode, user_dst_time_lag, user_dateformat, user_posts_per_page, user_topics_per_page, user_hot_threshold, user_lang, user_style, user_gender, user_level, user_allow_pm, user_birthday, user_birthday_y, user_birthday_m, user_birthday_d, user_next_birthday_greeting, user_active, user_actkey)
				VALUES ('" . $db->sql_escape($user_registered_ip) . "', '" . $db->sql_escape($user_registered_hostname) . "', $user_id, '" . $db->sql_escape($username) . "', " . time() . ", '" . $db->sql_escape(phpbb_hash($new_password)) . "', '" . $db->sql_escape($email) . "', '" . $db->sql_escape($icq) . "', '" . $db->sql_escape($website) . "', '" . $db->sql_escape($occupation) . "', '" . $db->sql_escape($location) . "', '$user_flag', '" . $db->sql_escape($interests) . "', '" . $db->sql_escape($phone) . "', '" . $db->sql_escape($selfdes) . "', $profile_view_popup, '" . $db->sql_escape($signature) . "', $avatar_sql, $viewemail, $upi2db_which_system, $upi2db_new_word, $upi2db_edit_word, $upi2db_unread_color, '" . $db->sql_escape(str_replace(' ', '+', $aim)) . "', '" . $db->sql_escape($yim) . "', '" . $db->sql_escape($msn) . "', '" . $db->sql_escape($skype) . "', $attachsig, $allowsmilies, $showavatars, $showsignatures, $allowswearywords, $allowhtml, $allowbbcode, $allowmassemail, $allowpmin, $allowviewonline, $notifyreply, $notifypm, $popup_pm, $user_timezone, $time_mode, $dst_time_lag, '" . $db->sql_escape($user_dateformat) . "', '" . $db->sql_escape($user_posts_per_page) . "', '" . $db->sql_escape($user_topics_per_page) . "', '" . $db->sql_escape($user_hot_threshold) . "', '" . $db->sql_escape($user_lang) . "', $user_style, '$gender', 0, 1, '$birthday', '$birthday_year', '$birthday_month', '$birthday_day', '$next_birthday_greeting', ";
			if (($config['require_activation'] == USER_ACTIVATION_SELF) || ($config['require_activation'] == USER_ACTIVATION_ADMIN) || $coppa)
			{
				$user_actkey = gen_rand_string();
				//$key_len = 54 - (strlen($server_url));
				$key_len = 54 - (strlen($profile_server_url));
				$key_len = ($key_len > 6) ? $key_len : 6;
				$user_actkey = substr($user_actkey, 0, $key_len);
				$sql .= "0, '" . $db->sql_escape($user_actkey) . "')";
			}
			else
			{
				$sql .= "1, '')";
			}
			$db->sql_transaction('begin');
			$result = $db->sql_query($sql);

			// CrackerTracker v5.x
			// BEGIN CrackerTracker v5.x
			($mode == 'register') ? $profile_security->pw_create_date($user_id) : null;
			($mode == 'register') ? $profile_security->reg_done() : null;
			// END CrackerTracker v5.x
			// CrackerTracker v5.x

			$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
				VALUES ('', 'Personal User', 1, 0)";
			$result = $db->sql_query($sql);
			$group_id = $db->sql_nextid();

			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
				VALUES ($user_id, $group_id, 0)";
			$result = $db->sql_query($sql);
			$db->sql_transaction('commit');

			// PM ON REGISTER - BEGIN
			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
			include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);
			$privmsg_sender = $founder_id;
			$privmsg_recipient = $user_id;
			$privmsg_subject = sprintf($lang['register_pm_subject'], $config['sitename']);
			$privmsg_message = sprintf($lang['register_pm'], $config['sitename'], $config['sitename']);

			$privmsg = new class_pm();
			$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message);
			unset($privmsg);
			// PM ON REGISTER - END

			board_stats();

			if ($coppa)
			{
				$message = $lang['COPPA'];
				$email_template = 'coppa_welcome_inactive';
			}
			elseif ($config['require_activation'] == USER_ACTIVATION_SELF)
			{
				$message = $lang['Account_inactive'];
				$email_template = 'user_welcome_inactive';
			}
			elseif ($config['require_activation'] == USER_ACTIVATION_ADMIN)
			{
				$message = $lang['Account_inactive_admin'];
				$email_template = 'admin_welcome_inactive';
			}
			else
			{
				$message = $lang['Account_added'];
				$email_template = 'user_welcome';
			}

			$sql = "SELECT ug.user_id, g.group_id as g_id, g.group_name , u.user_posts, g.group_count FROM (" . GROUPS_TABLE . " g, " . USERS_TABLE . " u)
					LEFT JOIN " . USER_GROUP_TABLE . " ug ON g.group_id=ug.group_id AND ug.user_id = '" . $user_id . "'
					WHERE u.user_id = $user_id
						 AND ug.user_id is NULL
						 AND g.group_count = 0
						 AND g.group_single_user = 0
						 AND g.group_moderator <> $user_id";
			$result = $db->sql_query($sql);

			clear_user_color_cache($user_id);

			while ($group_data = $db->sql_fetchrow($result))
			{
				//user join a autogroup
				$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
					VALUES (".$group_data['g_id'].", $user_id, 0)";
				$db->sql_query($sql);
			}

			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();

			$emailer->use_template($email_template, stripslashes($user_lang));
			$emailer->to($email);
			$emailer->set_subject(sprintf($lang['Welcome_subject'], $config['sitename']));

			if($coppa)
			{
				$email_sig = create_signature($config['board_email_sig']);
				$emailer->assign_vars(array(
					'SITENAME' => $config['sitename'],
					'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
					'PASSWORD' => $password_confirm,
					'EMAIL_SIG' => $email_sig,

					'FAX_INFO' => $config['coppa_fax'],
					'MAIL_INFO' => $config['coppa_mail'],
					'EMAIL_ADDRESS' => $email,
					'ICQ' => $icq,
					'AIM' => $aim,
					'YIM' => $yim,
					'SKYPE' => $skype,
					'MSN' => $msn,
					'WEB_SITE' => $website,
					'FROM' => $location,
					'OCC' => $occupation,
					'INTERESTS' => $interests,
					'SITENAME' => $config['sitename']
					)
				);
			}
			else
			{
				$email_sig = create_signature($config['board_email_sig']);
				$emailer->assign_vars(array(
					'SITENAME' => $config['sitename'],
					'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $config['sitename']),
					'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
					'PASSWORD' => $password_confirm,
					'EMAIL_SIG' => $email_sig,
					//'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
					'U_ACTIVATE' => $profile_server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
					)
				);
			}

			$emailer->send();
			$emailer->reset();

			if ($config['require_activation'] == USER_ACTIVATION_ADMIN)
			{
				$sql = "SELECT user_email, user_lang
					FROM " . USERS_TABLE . "
					WHERE user_level = " . ADMIN;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$emailer->from($config['board_email']);
					$emailer->replyto($config['board_email']);

					$emailer->email_address(trim($row['user_email']));
					$emailer->use_template('admin_activate', $row['user_lang']);
					$emailer->set_subject($lang['New_account_subject']);

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
						'EMAIL_SIG' => $email_sig,
						'U_ACTIVATE' => $profile_server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
						)
					);
					$emailer->send();
					$emailer->reset();
				}
				$db->sql_freeresult($result);
			}

			// Custom Profile Fields - BEGIN
			$profile_data = get_fields('WHERE users_can_view = ' . ALLOW_VIEW);
			$profile_names = array();
			$semaphore = 0;
			$sql2 = "UPDATE " . USERS_TABLE . " SET ";
			foreach($profile_data as $fields)
			{
				$name = text_to_column($fields['field_name']);
				$type = $fields['field_type'];
				$required = ($fields['is_required'] == REQUIRED) ? true : false;

				if(isset($_POST[$name]))
				{
					$temp = $_POST[$name];
					if($type == CHECKBOX)
					{
						$temp2 = '';
						foreach($temp as $temp3)
						{
							$temp2 .= htmlspecialchars($temp3) . ',';
						}
						$temp2 = substr($temp2, 0, strlen($temp2) - 1);

						$temp = $temp2;
					}
					else
					{
						$temp = htmlspecialchars($temp);
					}
					$profile_names[$name] = $temp;
					$sql2 .= $name . " = '" . $db->sql_escape($profile_names[$name]) . "', ";
				}
				else
				{
					$sql2 .= $name . " = '', ";
				}
				$semaphore++;
			}
			$sql2 = substr($sql2, 0, strlen($sql2) - 2) . " WHERE user_id = " . $user_id;
			$db->sql_return_on_error(true);
			$result2 = $db->sql_query($sql2);
			$db->sql_return_on_error(false);
			if(!$result2 && $semaphore)
			{
				message_die(GENERAL_ERROR, 'Could not update custom profile fields', '', __LINE__, __FILE__, $sql2);
			}
			// Custom Profile Fields - END

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		} // if mode == register
	}
} // End of submit


if ($error)
{
	$cur_password = '';
	$new_password = '';
	$password_confirm = '';

	$aim = str_replace('+', ' ', $aim);
	$phone = htmlspecialchars($phone);
}
elseif (($mode == 'editprofile') && !isset($_POST['avatargallery']) && !isset($_POST['submitavatar']) && !isset($_POST['avatargenerator']) && !isset($_POST['submitgenava']) && !isset($_POST['cancelavatar']))
{
	$user_id = $userdata['user_id'];
	$username = $userdata['username'];
	$email = $userdata['user_email'];
	$email_confirm = $userdata['user_email'];
	$cur_password = '';
	$new_password = '';
	$password_confirm = '';

	$icq = $userdata['user_icq'];
	$aim = str_replace('+', ' ', $userdata['user_aim']);
	$msn = $userdata['user_msnm'];
	$yim = $userdata['user_yim'];
	$skype = $userdata['user_skype'];

	$website = $userdata['user_website'];
	$location = $userdata['user_from'];
	$user_flag = $userdata['user_from_flag'];
	$phone = htmlspecialchars($userdata['user_phone']);
	$occupation = $userdata['user_occ'];
	$interests = $userdata['user_interests'];
	$gender = $userdata['user_gender'];
	$birthday = $userdata['user_birthday'];
	$selfdes = $userdata['user_selfdes'];
	$signature = $userdata['user_sig'];

//<!-- BEGIN Unread Post Information to Database Mod -->
	$upi2db_which_system = $userdata['user_upi2db_which_system'];
	$upi2db_new_word = $userdata['user_upi2db_new_word'];
	$upi2db_edit_word = $userdata['user_upi2db_edit_word'];
	$upi2db_unread_color = $userdata['user_upi2db_unread_color'];
//<!-- END Unread Post Information to Database Mod -->
	$viewemail = $userdata['user_viewemail'];
	$allowmassemail = $userdata['user_allow_mass_email'];
	$allowpmin = $userdata['user_allow_pm_in'];
	$notifypm = $userdata['user_notify_pm'];
	$popup_pm = $userdata['user_popup_pm'];
	$notifyreply = $userdata['user_notify'];
	$attachsig = $userdata['user_attachsig'];
	$setbm = $userdata['user_setbm'];
	$allowhtml = $userdata['user_allowhtml'];
	$allowbbcode = $userdata['user_allowbbcode'];
	$allowsmilies = $userdata['user_allowsmile'];
	$showavatars = $userdata['user_showavatars'];
	$showsignatures = $userdata['user_showsignatures'];
	$allowviewonline = $userdata['user_allow_viewonline'];
	$profile_view_popup = $userdata['user_profile_view_popup'];
	$allowswearywords = $userdata['user_allowswearywords'];

	$user_avatar = ($userdata['user_allowavatar']) ? $userdata['user_avatar'] : '';
	$user_avatar_type = ($userdata['user_allowavatar']) ? $userdata['user_avatar_type'] : USER_AVATAR_NONE;

	$user_style = $userdata['user_style'];
	$user_lang = $userdata['user_lang'];
	$user_timezone = $userdata['user_timezone'];
	$time_mode=$userdata['user_time_mode'];
	$dst_time_lag=$userdata['user_dst_time_lag'];
	$user_dateformat = $userdata['user_dateformat'];
}

// Default pages
$cp_section = '';
if ($cpl_mode == 'reg_info')
{
	$cp_section = $lang['Registration_info'];
}
elseif ($cpl_mode == 'profile_info')
{
	$cp_section = $lang['Profile_info'];
}
elseif ($cpl_mode == 'preferences')
{
	$cp_section = $lang['Preferences'];
}
elseif ($cpl_mode == 'board_settings')
{
	$cp_section = $lang['Cpl_Board_Settings'];
}
elseif ($cpl_mode == 'avatar')
{
	$cp_section = $lang['Avatar_panel'];
}

if ($mode == 'register')
{
	$main_nav = $lang['Register'];
}
else
{
	$main_nav = $lang['Profile'];
}

$link_name = (($cp_section == '') ? '' : $cp_section);
$link_url = (($cp_section == '') ? '#' : append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=' . $cpl_mode));
$nav_server_url = create_server_url();
$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE_MAIN) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $main_nav . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="' . $link_url . '">' . $link_name . '</a>') : '');

make_jumpbox(CMS_PAGE_VIEWFORUM);

if ($mode == 'editprofile')
{
	if ($user_id != $userdata['user_id'])
	{
		$error = true;
		$error_msg = $lang['Wrong_Profile'];
	}
}

if(isset($_POST['avatargallery']) && !$error)
{
	include(IP_ROOT_PATH . 'includes/usercp_avatar.' . PHP_EXT);

	$avatar_category = request_post_var('avatarcategory', '');

	$template_to_parse = 'profile_avatar_gallery.tpl';

	$allowviewonline = !$allowviewonline;

	display_avatar_gallery($mode, $avatar_category, $user_id, $email, $current_email, $email_confirm, $coppa, $username, $new_password, $cur_password, $password_confirm, $icq, $aim, $msn, $yim, $skype, $website, $location, $user_flag, $occupation, $interests, $phone, $selfdes, $signature, $viewemail, $notifypm, $popup_pm, $notifyreply, $attachsig, $setbm, $allowhtml, $allowbbcode, $allowsmilies, $showavatars, $showsignatures, $allowswearywords, $allowmassemail, $allowpmin, $allowviewonline, $user_style, $user_lang, $user_timezone, $time_mode, $dst_time_lag, $user_dateformat, $profile_view_popup, $userdata['session_id'], $birthday, $gender, $upi2db_which_system, $upi2db_new_word, $upi2db_edit_word, $upi2db_unread_color);
}
elseif(isset($_POST['avatargenerator']) && !$error)
{
	if (!defined('CTRACKER_DISABLE_OUTPUT'))
	{
		define('CTRACKER_DISABLE_OUTPUT', true);
	}
	include(IP_ROOT_PATH . 'includes/usercp_avatar.' . PHP_EXT);

	$avatar_filename = request_post_var('avatar_filename', '');
	$avatar_filename = (!empty($avatar_filename) ? $avatar_filename : POSTED_IMAGES_THUMBS_PATH . uniqid(rand()) . '.gif');
	//$avatar_filename = (!empty($avatar_filename) ? $avatar_filename : $config['avatar_path'] . '/' . uniqid(rand()) . '.gif');

	if (file_exists(@phpbb_realpath('./' . $avatar_filename)))
	{
		@unlink('./' . $avatar_filename);
	}

	$avatar_image = request_post_var('avatarimage', '');
	$avatar_text = request_post_var('avatartext', '');

	$avatar_image = (!empty($avatar_image) ? $avatar_image : 'Random');
	$avatar_text = (!empty($avatar_text) ? $avatar_text : $username);

	$template_to_parse = 'profile_avatar_generator.tpl';

	display_avatar_generator($mode, $avatar_filename, $avatar_image, $avatar_text, $user_id, $email, $current_email, $email_confirm, $coppa, $username, $new_password, $cur_password, $password_confirm, $icq, $aim, $msn, $yim, $skype, $website, $location, $user_flag, $occupation, $interests, $phone, $selfdes, $signature, $viewemail, $notifypm, $popup_pm, $notifyreply, $attachsig, $setbm, $allowhtml, $allowbbcode, $allowsmilies, $showavatars, $showsignatures, $allowswearywords, $allowmassemail, $allowpmin, $allowviewonline, $user_style, $user_lang, $user_timezone, $time_mode, $dst_time_lag, $user_dateformat, $profile_view_popup, $userdata['session_id'], $birthday, $gender, $upi2db_which_system, $upi2db_new_word, $upi2db_edit_word, $upi2db_unread_color);

}
else
{
	if (!isset($coppa))
	{
		$coppa = false;
	}

	if (!isset($user_style))
	{
		$user_style = $config['default_style'];
	}

	$avatar_img = '';
	if ($user_avatar_type)
	{
		switch($user_avatar_type)
		{
			case USER_AVATAR_UPLOAD:
				$avatar_img = ($config['allow_avatar_upload']) ? '<img src="' . $config['avatar_path'] . '/' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$avatar_img = resize_avatar($userdata['user_id'], $userdata['user_level'], $user_avatar);
				break;
			case USER_AVATAR_GALLERY:
				$avatar_img = ($config['allow_avatar_local']) ? '<img src="' . $config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_AVATAR_GENERATOR:
				$avatar_img = ($config['allow_avatar_generator']) ? '<img src="' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_GRAVATAR:
				$avatar_img = ($config['enable_gravatars']) ? '<img src="' . get_gravatar($user_avatar) . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
		}
	}

	if ($avatar_img == '')
	{
		$avatar_img = get_default_avatar($userdata['user_id']);
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="privacy" value="1" /><input type="hidden" name="coppa" value="' . $coppa . '" />';
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	if($mode == 'editprofile')
	{
		$s_hidden_fields .= '<input type="hidden" name="user_id" value="' . $userdata['user_id'] . '" />';
		//
		// Send the users current email address. If they change it, and account activation is turned on
		// the user account will be disabled and the user will have to reactivate their account.
		//
		$s_hidden_fields .= '<input type="hidden" name="current_email" value="' . $userdata['user_email'] . '" />';
	}

	if (!empty($user_avatar_local))
	{
		$s_hidden_fields .= '<input type="hidden" name="avatarlocal" value="' . $user_avatar_local . '" /><input type="hidden" name="avatarcatname" value="' . $user_avatar_category . '" />';
	}
	if (!empty($user_avatar_generator))
	{
		$s_hidden_fields .= '<input type="hidden" name="avatargenerator" value="' . $user_avatar_generator . '" />';
	}
	$html_status = ($userdata['user_allowhtml'] && $config['allow_html']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
	$bbcode_status = ($userdata['user_allowbbcode'] && $config['allow_bbcode']) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
	$smilies_status = ($userdata['user_allowsmile'] && $config['allow_smilies']) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];

	//Per Page Settings START
	$user_topics_per_page = $userdata['user_topics_per_page'];
	$user_posts_per_page = $userdata['user_posts_per_page'];
	$user_hot_threshold = $userdata['user_hot_threshold'];
	//Per Page Settings END

	$l_time_mode_0 = '';
	$l_time_mode_1 = '';
	$l_time_mode_2 = $lang['time_mode_dst_server'];

	switch ($config['default_time_mode'])
	{
		case MANUAL_DST:
			$l_time_mode_1 = $l_time_mode_1 . '*';
			break;
		case SERVER_SWITCH:
			$l_time_mode_2 = $l_time_mode_2 . '*';
			break;
		default:
			$l_time_mode_0 = $l_time_mode_0 . '*';
			break;
	}

	switch ($time_mode)
	{
		case MANUAL_DST:
			$time_mode_manual_dst_checked = 'checked="checked"';
			break;
		case SERVER_SWITCH:
			$time_mode_server_switch_checked = 'checked="checked"';
			break;
		default:
			$time_mode_manual_checked = 'checked="checked"';
			break;
	}

	switch ($gender)
	{
		case 1:
			$gender_male_checked = 'checked="checked"';
			break;
		case 2:
			$gender_female_checked = 'checked="checked"';
			break;
		default:
			$gender_no_specify_checked = 'checked="checked"';
	}

	if ($birthday != 999999)
	{
		$birthday_day = realdate('j', $birthday);
		$birthday_month = realdate('n', $birthday);
		$birthday_year = realdate('Y', $birthday);
		$birthday = realdate($lang['Submit_date_format'], $birthday);
	}
	else
	{
		$birthday_day = '';
		$birthday_month = '';
		$birthday_year = '';
		$birthday = '';
	}

	if ($error)
	{
		$template->set_filenames(array('reg_header' => 'error_body.tpl'));
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $error_msg
			)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}

	// Custom Profile Fields - BEGIN
	if ($mode == 'register')
	{

		$template_to_parse = 'profile_register_body.tpl';
		if ($config['enable_confirm'])
		{
			$template->assign_block_vars('switch_confirm', array());
		}
	}
	else
	{
		$template_to_parse = 'profile_add_body.tpl';
	}
	// Custom Profile Fields - END
	// Start Mod User CP Organize: Variables for Handling the creation of deeper switches
	$cpl_registration_info = '';
	$cpl_avatar_control = '';
	// Assign profile blocks selected for display
	if (($cpl_mode == 'reg_info') || ($cpl_mode == 'all'))
	{
		$template->assign_block_vars('switch_cpl_reg_info', array());
		$template->assign_vars(array(
			'L_PROFILE' => $cp_section
			)
		);
		$cpl_registration_info = 'switch_cpl_reg_info.';
	}
	if (($cpl_mode == 'profile_info') || ($cpl_mode == 'all'))
	{
		$template->assign_block_vars('switch_cpl_profile_info', array());
		// Custom Profile Fields - BEGIN
		$profile_data = get_fields('WHERE users_can_view = ' . ALLOW_VIEW);

		if(sizeof($profile_data) > 0)
		{
			$template->assign_block_vars('switch_cpl_profile_info.switch_custom_fields', array(
				'L_CUSTOM_FIELD_NOTICE' => $lang['custom_field_notice']
				)
			);

			// Include Language
			setup_extra_lang(array('lang_profile_fields'));

			foreach($profile_data as $field)
			{
				$field_id = $field['field_id'];
				$field_name = $field['field_name'];
				$field_description = $field['field_description'];
				$name = text_to_column($field_name);
				if (isset($lang[$field_id . '_' . $field_name]))
				{
					$field_name = $lang[$field_id . '_' . $field_name];
				}
				if (isset($lang[$field_id . '_' . $field_description]))
				{
					$field_description = $lang[$field_id . '_Description'];
				}

				if($field['is_required'] == REQUIRED)
				{
					$required = true;
				}

				switch($field['field_type'])
				{
					case TEXT_FIELD:
						$value = $userdata[$name];
						$length = $field['text_field_maxlen'];
						$field_html_code = '<input type="text" class="post" style="width: 200px;" name="' . $name . '" size="35" maxlength="' . $length . '" value="' . $value . '" />';
						$hidden_fields_custom_name = $name;
						$hidden_fields_custom_value = $value;
						break;

					case TEXTAREA:
						$value = $userdata[$name];
						$field_html_code = '<textarea name="' . $name . '" style="width: 300px;" rows="6" cols="30" class="post">' . $value . '</textarea>';
						$hidden_fields_custom_name = $name;
						$hidden_fields_custom_value = $value;
						break;

					case RADIO:
						$value = $userdata[$name];
						$radio_list = explode(',',$field['radio_button_values']);
						$html_list = array();
						foreach($radio_list as $num => $radio_name)
						{
							$temp = '<input type="radio" name="' . $name . '" value="' . $radio_name . '"';
							if($radio_name == $value)
							{
								$temp .= ' checked="checked"';
							}
							$field_radio_name = $radio_name;
							if (isset($lang[$field_id . '_' . $radio_name]))
							{
								$field_radio_name = $lang[$field_id . '_' . $radio_name];
							}
							$temp .= ' />&nbsp;<span class="gen">' . $field_radio_name . '</span>';
							if($num < sizeof($radio_list))
							{
								$temp .= '<br />';
							}
							$html_list[] = $temp;
						}
						$field_html_code = '';
						$hidden_fields_custom_name = $name;
						$hidden_fields_custom_value = $value;
						foreach($html_list as $line)
						{
							$field_html_code .= $line . "\n";
						}
						break;

					case CHECKBOX:
						$value_array = explode(',',$userdata[$name]);
						$check_list = explode(',',$field['checkbox_values']);
						$html_list = array();
						foreach($check_list as $num => $check_name)
						{
							$temp = "<input type=\"checkbox\" name=\"{$name}[]\" value=\"$check_name\"";
							foreach($value_array as $val)
							{
								if($val == $check_name)
								{
									$temp .= ' checked="checked"';
									break;
								}
							}
							$field_check_name = $check_name;
							if (isset($lang[$field_id . '_' . $check_name]))
							{
								$field_check_name = $lang[$field_id . '_' . $check_name];
							}
							$temp .= ' />&nbsp;<span class="gen">' . $field_check_name . '</span>';
							if($num < sizeof($check_list))
							{
								$temp .= '<br />';
							}
							$html_list[] = $temp;
						}
						$field_html_code = '';
						$hidden_fields_custom_name = $name;
						$hidden_fields_custom_value = $value;
						foreach($html_list as $line)
						{
							$field_html_code .= $line . "\n";
						}
						break;
				}

				$template->assign_block_vars('switch_cpl_profile_info.custom_fields',array(
					'NAME' => $field_name,
					'FIELD' => $field_html_code,
					'REQUIRED' => $required ? ' *' : ''
					)
				);

				if(($field['field_description'] != NULL) && (!empty($field['field_description'])))
				{
					$template->assign_block_vars('switch_cpl_profile_info.custom_fields.switch_description',array(
						'DESCRIPTION' => $field_description
						)
					);
				}
			}
		}
		// Custom Profile Fields - END

		$template->assign_vars(array(
			'L_PROFILE' => $cp_section
			)
		);
	}

	if (($cpl_mode == 'preferences') || ($cpl_mode == 'all'))
	{
		$template->assign_block_vars('switch_cpl_preferences', array());
		$template->assign_vars(array(
			'L_PROFILE' => $cp_section
			)
		);
		$cpl_preferences = 'switch_cpl_preferences.';
	}

	if (($cpl_mode == 'board_settings') || ($cpl_mode == 'all'))
	{
		$template->assign_block_vars('switch_cpl_board_settings', array());
		$template->assign_vars(array(
			'L_PROFILE' => $cp_section
			)
		);
	}

	if (($cpl_mode == 'signature') || ($cpl_mode == 'all'))
	{
		$template->assign_block_vars('switch_cpl_signature', array());
	}

	if (($cpl_mode == 'avatar') || ($cpl_mode == 'all'))
	{
		$template->assign_block_vars('switch_cpl_avatar', array());
		$template->assign_vars(array(
			'L_PROFILE' => $cp_section
			)
		);
		$cpl_avatar_control = 'switch_cpl_avatar.';
	}

	// Add Hidden inputs to replace the ones not displayed.
	if (($cpl_mode != 'reg_info') && ($cpl_mode != 'all'))
	{
		$s_hidden_fields .= '<input type="hidden" name="username" value="' . $username . '" />';
		$s_hidden_fields .= '<input type="hidden" name="email" value="' . $email . '" />';
	}

	if (($cpl_mode != 'profile_info') && ($cpl_mode != 'all'))
	{
		$s_hidden_fields .= '<input type="hidden" name="icq" value="' . $icq . '" />';
		$s_hidden_fields .= '<input type="hidden" name="aim" value="' . $aim . '" />';
		$s_hidden_fields .= '<input type="hidden" name="msn" value="' . $msn . '" />';
		$s_hidden_fields .= '<input type="hidden" name="yim" value="' . $yim . '" />';
		$s_hidden_fields .= '<input type="hidden" name="skype" value="' . $skype . '" />';

		$s_hidden_fields .= '<input type="hidden" name="website" value="' . $website . '" />';
		$s_hidden_fields .= '<input type="hidden" name="location" value="' . $location . '" />';
		$s_hidden_fields .= '<input type="hidden" name="user_flag" value="' . $user_flag . '" />';
		$s_hidden_fields .= '<input type="hidden" name="occupation" value="' . $occupation . '" />';
		$s_hidden_fields .= '<input type="hidden" name="interests" value="' . $interests . '" />';
		$s_hidden_fields .= '<input type="hidden" name="phone" value="' . $phone . '" />';
		$s_hidden_fields .= '<input type="hidden" name="selfdes" value="' . htmlspecialchars($selfdes) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="b_day" value="' . $birthday_day . '" />';
		$s_hidden_fields .= '<input type="hidden" name="b_md" value="' . $birthday_month . '" />';
		$s_hidden_fields .= '<input type="hidden" name="b_year" value="' . $birthday_year . '" />';
		$s_hidden_fields .= '<input type="hidden" name="gender" value="' . $gender . '" />';
		$s_hidden_fields .= '<input type="hidden" name="signature" value="' . $signature . '" />';
		$profile_data = get_fields('WHERE users_can_view = ' . ALLOW_VIEW);

		// Custom Profile Fields - BEGIN
		foreach($profile_data as $field)
		{
			$field_name = $field['field_name'];
			$name = text_to_column($field_name);

			if($field['is_required'] == REQUIRED)
			{
				$required = true;
			}

			switch($field['field_type'])
			{
				case TEXT_FIELD:
					$value = $userdata[$name];
					$length = $field['text_field_maxlen'];
					$field_html_code = '<input type="text" class="post" style="width: 200px" name="' . $name . '" size="35" maxlength="' . $length . '" value="' . $value . '" />';
					$hidden_fields_custom_name = $name;
					$hidden_fields_custom_value = $value;
					break;

				case TEXTAREA:
					$value = $userdata[$name];
					$field_html_code = '<textarea name="' . $name . '" style="width: 300px" rows="6" cols="30" class="post">' . $value . '</textarea>';
					$hidden_fields_custom_name = $name;
					$hidden_fields_custom_value = $value;
					break;

				case RADIO:
					$value = $userdata[$name];
					$radio_list = explode(',',$field['radio_button_values']);
					$html_list = array();
					foreach($radio_list as $num => $radio_name)
					{
						$temp = '<input type="radio" name="' . $name . '" value="' . $radio_name . '"';
						if($radio_name == $value)
						{
							$temp .= ' checked="checked"';
						}
						$temp .= ' /> <span class="gen">' . $radio_name . '</span>';
						if($num < sizeof($radio_list))
						{
							$temp .= '<br />';
						}
						$html_list[] = $temp;
					}
					$field_html_code = '';
					$hidden_fields_custom_name = $name;
					$hidden_fields_custom_value = $value;
					foreach($html_list as $line)
					{
						$field_html_code .= $line . "\n";
					}
					break;

				case CHECKBOX:
					$value_array = explode(',', $userdata[$name]);
					$check_list = explode(',', $field['checkbox_values']);
					$html_list = array();
					foreach($check_list as $num => $check_name)
					{
						//$temp = "<input type=\"checkbox\" name=\"{$name}[]\" value=\"$check_name\"";
						$temp = '<input type="checkbox" name="' . $name . '" value="' . $check_name . '"';
						foreach($value_array as $val)
						{
							if($val == $check_name)
							{
								$temp .= ' checked="checked"';
								break;
							}
						}
						$temp .= ' /> <span class="gen">' . $check_name . '</span>';
						if($num < sizeof($check_list))
						{
							$temp .= '<br />';
						}
						$html_list[] = $temp;
					}
					$field_html_code = '';
					$hidden_fields_custom_name = $name;
					$hidden_fields_custom_value = $value;
					foreach($html_list as $line)
					{
						$field_html_code .= $line . "\n";
					}
					break;
			}
			$s_hidden_fields .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}
		// Custom Profile Fields - END
	}

	if (($cpl_mode != 'preferences') && ($cpl_mode != 'all'))
	{
		$s_hidden_fields .= '<input type="hidden" name="viewemail" value="' . $viewemail . '" />';
		$s_hidden_fields .= '<input type="hidden" name="allowmassemail" value="' . $allowmassemail . '" />';
		$s_hidden_fields .= '<input type="hidden" name="allowpmin" value="' . $allowpmin . '" />';
		$s_hidden_fields .= '<input type="hidden" name="notifypm" value="' . $notifypm . '" />';
		$s_hidden_fields .= '<input type="hidden" name="popup_pm" value="' . $popup_pm . '" />';
		$s_hidden_fields .= '<input type="hidden" name="notifyreply" value="' . $notifyreply . '" />';
		$s_hidden_fields .= '<input type="hidden" name="attachsig" value="' . $attachsig . '" />';
		$s_hidden_fields .= '<input type="hidden" name="allowhtml" value="' . $allowhtml . '" />';
		$s_hidden_fields .= '<input type="hidden" name="allowbbcode" value="' . $allowbbcode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="allowsmilies" value="' . $allowsmilies . '" />';
		$s_hidden_fields .= '<input type="hidden" name="user_topics_per_page" value="' . $user_topics_per_page . '" />';
		$s_hidden_fields .= '<input type="hidden" name="user_posts_per_page" value="' . $user_posts_per_page . '" />';
		$s_hidden_fields .= '<input type="hidden" name="user_hot_threshold" value="' . $user_hot_threshold . '" />';
		$s_hidden_fields .= '<input type="hidden" name="hideonline" value="' . !$allowviewonline . '" />';
		$s_hidden_fields .= '<input type="hidden" name="showavatars" value="' . $showavatars . '" />';
		$s_hidden_fields .= '<input type="hidden" name="showsignatures" value="' . $showsignatures . '" />';
		$s_hidden_fields .= '<input type="hidden" name="allowswearywords" value="' . $showsignatures . '" />';
//<!-- BEGIN Unread Post Information to Database Mod -->
		$s_hidden_fields .= '<input type="hidden" name="upi2db_which_system" value="' . $upi2db_which_system . '" />';
		$s_hidden_fields .= '<input type="hidden" name="upi2db_new_word" value="' . $upi2db_new_word . '" />';
		$s_hidden_fields .= '<input type="hidden" name="upi2db_edit_word" value="' . $upi2db_edit_word . '" />';
		$s_hidden_fields .= '<input type="hidden" name="upi2db_unread_color" value="' . $upi2db_unread_color . '" />';
//<!-- END Unread Post Information to Database Mod -->
	}

	if (($cpl_mode != 'board_settings') && ($cpl_mode != 'all'))
	{
		$s_hidden_fields .= '<input type="hidden" name="style" value="' . $user_style . '" />';
		$s_hidden_fields .= '<input type="hidden" name="language" value="' . $user_lang . '" />';
		$s_hidden_fields .= '<input type="hidden" name="timezone" value="' . $user_timezone . '" />';
		$s_hidden_fields .= '<input type="hidden" name="dateformat" value="' . $user_dateformat . '" />';
		$s_hidden_fields .= '<input type="hidden" name="time_mode" value="' . $time_mode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="dst_time_lag" value="' . $dst_time_lag . '" />';

	}
	// End Mod User CP Organize

	if ($mode == 'editprofile')
	{
		// Mod User CP Organize: Added This Code => $cpl_registration_info .
		$template->assign_block_vars($cpl_registration_info . 'switch_edit_profile', array());
	}
//<!-- BEGIN Unread Post Information to Database Mod -->

	if(!$userdata['user_upi2db_disable'] && ($config['upi2db_on'] != '0'))
	{
		if(check_group_auth($userdata) == true)
		{
			if ($config['upi2db_on'] != '0')
			{
				$template->assign_block_vars($cpl_preferences . 'switch_upi2db_is_on', array());
				$template->assign_block_vars($cpl_preferences . 'switch_upi2db_words', array());
			}
			if ($config['upi2db_on'] == '2')
			{
				$template->assign_block_vars($cpl_preferences . 'switch_upi2db_user_select', array());
			}
		}
	}
//<!-- END Unread Post Information to Database Mod -->

	if (($mode == 'register') || ($config['allow_namechange']))
	{
		// Mod User CP Organize: Added This Code => $cpl_registration_info .
		$template->assign_block_vars($cpl_registration_info . 'switch_namechange_allowed', array());
	}
	else
	{
		// Mod User CP Organize: Added This Code => $cpl_registration_info .
		$template->assign_block_vars($cpl_registration_info . 'switch_namechange_disallowed', array());
	}

	// Flag Start
	// Query to get the list of flags
	$sql = "SELECT * FROM " . FLAG_TABLE . " ORDER BY flag_name";
	$flags_result = $db->sql_query($sql, 0, 'flags_');
	$flag_row = $db->sql_fetchrowset($flags_result);
	$num_flags = sizeof($flag_row);
	$db->sql_freeresult($flags_result);

	// Build the html select statement
	$flag_start_image = 'blank.gif' ;
	$selected = (!empty($user_flag)) ? '' : ' selected="selected"';
	$flag_select = '<select name="user_flag" onchange="document.images[\'user_flag\'].src = \'images/flags/\' + this.value;" >';
	$flag_select .= '<option value="blank.gif"' . $selected . '>' . $lang['Select_Country'] . '</option>';
	for ($i = 0; $i < $num_flags; $i++)
	{
		$flag_name = $flag_row[$i]['flag_name'];
		$flag_image = $flag_row[$i]['flag_image'];
		$selected = '';
		if (!empty($user_flag) && ($user_flag == $flag_image))
		{
			$selected = ' selected="selected"';
			$flag_start_image = $flag_image;
		}
		$flag_select .= '<option value="' . $flag_image . '"' . $selected . '>' . $flag_name . '</option>';
	}
	$flag_select .= '</select>';
	// Flag End

	// Visual Confirmation
	$confirm_image = '';
	if (!empty($config['enable_confirm']) && $mode == 'register')
	{
		$sql = "SELECT session_id
			FROM " . SESSIONS_TABLE;
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$confirm_sql = '';
			do
			{
				$confirm_sql .= (($confirm_sql != '') ? ', ' : '') . "'" . $row['session_id'] . "'";
			}
			while ($row = $db->sql_fetchrow($result));

			$sql = "DELETE FROM " . CONFIRM_TABLE . "
				WHERE session_id NOT IN (" . $confirm_sql . ")";
			$db->sql_query($sql);
		}
		$db->sql_freeresult($result);

		$sql = "SELECT COUNT(session_id) AS attempts
			FROM " . CONFIRM_TABLE . "
			WHERE session_id = '" . $userdata['session_id'] . "'";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			if ($row['attempts'] > 3)
			{
				message_die(GENERAL_MESSAGE, $lang['TOO_MANY_ATTEMPTS']);
			}
		}
		$db->sql_freeresult($result);

		// Generate the required confirmation code
		// NB 0 (zero) could get confused with O (the letter) so we make change it
		$code = unique_id();
		$code = substr(str_replace('0', 'Z', strtoupper(base_convert($code, 16, 35))), 2, 6);
		$confirm_id = md5(uniqid($user_ip));
		$sql = "INSERT INTO " . CONFIRM_TABLE . " (confirm_id, session_id, code)
			VALUES ('" . $confirm_id . "', '" . $userdata['session_id'] . "', '" . $code . "')";
		$db->sql_query($sql);

		unset($code);

		$confirm_image = '<img src="' . append_sid(CMS_PAGE_PROFILE . '?mode=confirm&amp;id=' . $confirm_id) . '" alt="" title="" />';
		$s_hidden_fields .= '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';

	// Mod User CP Organize: Added This Code => $cpl_registration_info .
		$template->assign_block_vars($cpl_registration_info . 'switch_confirm', array());
	}

// Start add - Birthday MOD
	$s_b_day = '<span class="genmed">' . $lang['Day'] . '&nbsp;</span><select name="b_day" size="1" class="gensmall">
		<option value="0">&nbsp;-&nbsp;</option>
		<option value="1">&nbsp;1&nbsp;</option>
		<option value="2">&nbsp;2&nbsp;</option>
		<option value="3">&nbsp;3&nbsp;</option>
		<option value="4">&nbsp;4&nbsp;</option>
		<option value="5">&nbsp;5&nbsp;</option>
		<option value="6">&nbsp;6&nbsp;</option>
		<option value="7">&nbsp;7&nbsp;</option>
		<option value="8">&nbsp;8&nbsp;</option>
		<option value="9">&nbsp;9&nbsp;</option>
		<option value="10">&nbsp;10&nbsp;</option>
		<option value="11">&nbsp;11&nbsp;</option>
		<option value="12">&nbsp;12&nbsp;</option>
		<option value="13">&nbsp;13&nbsp;</option>
		<option value="14">&nbsp;14&nbsp;</option>
		<option value="15">&nbsp;15&nbsp;</option>
		<option value="16">&nbsp;16&nbsp;</option>
		<option value="17">&nbsp;17&nbsp;</option>
		<option value="18">&nbsp;18&nbsp;</option>
		<option value="19">&nbsp;19&nbsp;</option>
		<option value="20">&nbsp;20&nbsp;</option>
		<option value="21">&nbsp;21&nbsp;</option>
		<option value="22">&nbsp;22&nbsp;</option>
		<option value="23">&nbsp;23&nbsp;</option>
		<option value="24">&nbsp;24&nbsp;</option>
		<option value="25">&nbsp;25&nbsp;</option>
		<option value="26">&nbsp;26&nbsp;</option>
		<option value="27">&nbsp;27&nbsp;</option>
		<option value="28">&nbsp;28&nbsp;</option>
		<option value="29">&nbsp;29&nbsp;</option>
		<option value="30">&nbsp;30&nbsp;</option>
		<option value="31">&nbsp;31&nbsp;</option>
		</select>&nbsp;&nbsp;';
	$s_b_md = '<span class="genmed">' . $lang['Month'] . '&nbsp;</span><select name="b_md" size="1" class="gensmall">
		<option value="0">&nbsp;-&nbsp;</option>
		<option value="1">&nbsp;'.$lang['datetime']['January'].'&nbsp;</option>
		<option value="2">&nbsp;'.$lang['datetime']['February'].'&nbsp;</option>
		<option value="3">&nbsp;'.$lang['datetime']['March'].'&nbsp;</option>
		<option value="4">&nbsp;'.$lang['datetime']['April'].'&nbsp;</option>
		<option value="5">&nbsp;'.$lang['datetime']['May'].'&nbsp;</option>
		<option value="6">&nbsp;'.$lang['datetime']['June'].'&nbsp;</option>
		<option value="7">&nbsp;'.$lang['datetime']['July'].'&nbsp;</option>
		<option value="8">&nbsp;'.$lang['datetime']['August'].'&nbsp;</option>
		<option value="9">&nbsp;'.$lang['datetime']['September'].'&nbsp;</option>
		<option value="10">&nbsp;'.$lang['datetime']['October'].'&nbsp;</option>
		<option value="11">&nbsp;'.$lang['datetime']['November'].'&nbsp;</option>
		<option value="12">&nbsp;'.$lang['datetime']['December'].'&nbsp;</option>
		</select>&nbsp;&nbsp;';
	// Mighty Gorgon - Generate Years Select - BEGIN
	$i_start = gmdate('Y') - $config['max_user_age'];
	$i_end = gmdate('Y') - $config['min_user_age'];
	$s_birthday_year = '';
	$y_selected = ($birthday_year == 0) ? ' selected="selected"' : '';
	$s_birthday_year .= '<option value="0"' . $y_selected . '> ---- </option>';
	//for ($i = $i_start; $i <= $i_end; $i++)
	for ($i = $i_end; $i >= $i_start; $i--)
	{
		$y_selected = ($birthday_year == $i) ? ' selected="selected"' : '';
		$s_birthday_year .= '<option value="' . $i . '"' . $y_selected . '>' . $i . '</option>';
	}
	$s_birthday_year = '<select name="b_year">' . $s_birthday_year . '</select>';
	// Mighty Gorgon - Generate Years Select - END
	$s_b_day= str_replace('value="' . $birthday_day . '">', 'value="' . $birthday_day . '" selected="selected">', $s_b_day);
	$s_b_md = str_replace('value="' . $birthday_month . '">', 'value="' . $birthday_month . '" selected="selected">', $s_b_md);
	//$s_b_year = '<span class="genmed">' . $lang['Year'] . '&nbsp;</span><input type="text" class="post" style="width: 50px" name="b_year" size="4" maxlength="4" value="' . $birthday_year . '" />&nbsp;&nbsp;';
	$s_b_year = '<span class="genmed">' . $lang['Year'] . '&nbsp;</span>' . $s_birthday_year . '&nbsp;&nbsp;';
	$i = 0;
	$s_birthday = '';
	for ($i = 0; $i <= strlen($lang['Submit_date_format']); $i++)
	{
		switch ($lang['Submit_date_format'][$i])
		{
			case d:
				$s_birthday .= $s_b_day;
				break;
			case m:
				$s_birthday .= $s_b_md;
				break;
			case Y:
				$s_birthday .= $s_b_year;
				break;
		}
	}
// End add - Birthday MOD

	$verify_un_js = '';
	$verify_email_js = '';
	if ($config['ajax_checks_register'] == true)
	{
		$verify_un_js = 'onblur="verifyUsername(this.value)"';
		$verify_email_js = 'onblur="verifyEmail(this.value)"';
		/*
		$verify_un_js = 'onKeyUp="verifyUsername(this.value)"';
		$verify_email_js = 'onKeyUp="verifyEmail(this.value)"';
		*/
	}

	// Let's do an overall check for settings/versions which would prevent us from doing file uploads....
	$ini_val = (phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';
	$form_enctype = (@$ini_val('file_uploads') == '0' || strtolower(@$ini_val('file_uploads') == 'off') || (phpversion() == '4.0.4pl1') || !$config['allow_avatar_upload'] || ((phpversion() < '4.0.3') && (@$ini_val('open_basedir') != ''))) ? '' : 'enctype="multipart/form-data"';

	$template->assign_vars(array(
		'S_REGISTER_MESSAGE' => (empty($lang['REGISTER_MESSAGE']) ? false : true),
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'USERNAME' => isset($username) ? $username : '',
		'CUR_PASSWORD' => isset($cur_password) ? htmlspecialchars($cur_password) : '',
		'NEW_PASSWORD' => isset($new_password) ? htmlspecialchars($new_password) : '',
		'PASSWORD_CONFIRM' => isset($password_confirm) ? htmlspecialchars($password_confirm) : '',
		'EMAIL' => isset($email) ? $email : '',
		'SIG_EDIT_LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=signature'),
		'SIG_DESC' => $lang['sig_description'],
		'SIG_BUTTON_DESC' => $lang['sig_edit'],
		'CONFIRM_IMG' => $confirm_image,
		'YIM' => $yim,
		'SKYPE' => $skype,
		'ICQ' => $icq,
		'MSN' => $msn,
		'AIM' => $aim,
		'OCCUPATION' => $occupation,
		'INTERESTS' => $interests,

		'FLAG_SELECT' => $flag_select,
		'FLAG_START' => $flag_start_image,
		'PHONE' => $phone,
		'SELFDES' => str_replace('<br />', "\n", $selfdes),
		'L_FLAG' => $lang['Country_Flag'],
		'L_PHONE' => $lang['UserPhone'],
		'L_EXTRA_PROFILE_INFO' => $lang['Extra_profile_info'],
		'L_EXTRA_PROFILE_INFO_EXPLAIN' => sprintf($lang['Extra_profile_info_explain'], $config['extra_max']),
		'PROFILE_VIEW_POPUP_YES' => ($profile_view_popup) ? 'checked="checked"' : '',
		'PROFILE_VIEW_POPUP_NO' => (!$profile_view_popup) ? 'checked="checked"' : '',
		'L_PROFILE_VIEW_POPUP' => $lang['Profile_view_option'],
		'S_BIRTHDAY' => $s_birthday,
		'BIRTHDAY_REQUIRED' => ($config['birthday_required']) ? '*' : '',
		'LOCATION' => $location,
		'WEBSITE' => $website,
		'SIGNATURE' => str_replace('<br />', "\n", $signature),
		'LOCK_GENDER' =>($mode != 'register') ? 'DISABLED' : '',
		'GENDER' => $gender,
		'GENDER_REQUIRED' => ($config['gender_required']) ? ' *' : '',
		'GENDER_NO_SPECIFY_CHECKED' => $gender_no_specify_checked,
		'GENDER_MALE_CHECKED' => $gender_male_checked,
		'GENDER_FEMALE_CHECKED' => $gender_female_checked,
//<!-- BEGIN Unread Post Information to Database Mod -->
		'UPI2DB_SYSTEM' => $upi2db_which_system ? 'checked="checked"' : '',
		'COOKIE_SYSTEM' => !$upi2db_which_system ? 'checked="checked"' : '',
		'UPI2DB_NEW_WORD_YES' => $upi2db_new_word ? 'checked="checked"' : '',
		'UPI2DB_NEW_WORD_NO' => !$upi2db_new_word ? 'checked="checked"' : '',
		'UPI2DB_EDIT_WORD_YES' => $upi2db_edit_word ? 'checked="checked"' : '',
		'UPI2DB_EDIT_WORD_NO' => !$upi2db_edit_word ? 'checked="checked"' : '',
		'UPI2DB_UNREAD_COLOR_YES' => $upi2db_unread_color ? 'checked="checked"' : '',
		'UPI2DB_UNREAD_COLOR_NO' => !$upi2db_unread_color ? 'checked="checked"' : '',
//<!-- END Unread Post Information to Database Mod -->
		'HIDE_USER_YES' => !$allowviewonline ? 'checked="checked"' : '',
		'HIDE_USER_NO' => $allowviewonline ? 'checked="checked"' : '',
		'VIEW_EMAIL_YES' => $viewemail ? 'checked="checked"' : '',
		'VIEW_EMAIL_NO' => !$viewemail ? 'checked="checked"' : '',
		'ALLOW_MASS_EMAIL_YES' => $allowmassemail ? 'checked="checked"' : '',
		'ALLOW_MASS_EMAIL_NO' => !$allowmassemail ? 'checked="checked"' : '',
		'ALLOW_PM_IN_YES' => $allowpmin ? 'checked="checked"' : '',
		'ALLOW_PM_IN_NO' => !$allowpmin ? 'checked="checked"' : '',
		'NOTIFY_PM_YES' => $notifypm ? 'checked="checked"' : '',
		'NOTIFY_PM_NO' => !$notifypm ? 'checked="checked"' : '',
		'POPUP_PM_YES' => $popup_pm ? 'checked="checked"' : '',
		'POPUP_PM_NO' => !$popup_pm ? 'checked="checked"' : '',
		'ALWAYS_ADD_SIGNATURE_YES' => $attachsig ? 'checked="checked"' : '',
		'ALWAYS_ADD_SIGNATURE_NO' => !$attachsig ? 'checked="checked"' : '',
		'ALWAYS_SET_BOOKMARK_YES' => $setbm ? 'checked="checked"' : '',
		'ALWAYS_SET_BOOKMARK_NO' => !$setbm ? 'checked="checked"' : '',

		'NOTIFY_REPLY_YES' => $notifyreply ? 'checked="checked"' : '',
		'NOTIFY_REPLY_NO' => !$notifyreply ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_BBCODE_YES' => $allowbbcode ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_BBCODE_NO' => !$allowbbcode ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_HTML_YES' => $allowhtml ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_HTML_NO' => !$allowhtml ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SMILIES_YES' => $allowsmilies ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SMILIES_NO' => !$allowsmilies ? 'checked="checked"' : '',
		'SHOW_AVATARS_YES' => $showavatars ? 'checked="checked"' : '',
		'SHOW_AVATARS_NO' => !$showavatars ? 'checked="checked"' : '',
		'SHOW_SIGNATURES_YES' => $showsignatures ? 'checked="checked"' : '',
		'SHOW_SIGNATURES_NO' => !$showsignatures ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SWEARYWORDS_YES' => $allowswearywords ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SWEARYWORDS_NO' => !$allowswearywords ? 'checked="checked"' : '',
		'POSTS_PER_PAGE' => ($userdata['user_posts_per_page'] == '') ? $config['posts_per_page'] : intval($userdata['user_posts_per_page']),
		'TOPICS_PER_PAGE' => ($userdata['user_topics_per_page'] == '') ? $config['topics_per_page'] : intval($userdata['user_topics_per_page']),
		'HOT_TOPIC' => ($userdata['user_hot_threshold'] == '') ? $config['hot_threshold'] : $userdata['user_hot_threshold'],
		'ALLOW_AVATAR' => $config['allow_avatar_upload'],
		'AVATAR' => $avatar_img,
		'AVATAR_SIZE' => $config['avatar_filesize'],
		'GRAVATAR' => ($userdata['user_avatar_type'] == USER_GRAVATAR) ? $userdata['user_avatar'] : '',
		'LANGUAGE_SELECT' => language_select('language', $user_lang),
		'STYLE_SELECT' => style_select('style', $user_style),
		'TIMEZONE_SELECT' => tz_select('timezone', $user_timezone),
		'DATE_FORMAT' => date_select('dateformat', $user_dateformat),
		'TIME_MODE' => $time_mode,
		'TIME_MODE_MANUAL_CHECKED' => $time_mode_manual_checked,
		'TIME_MODE_MANUAL_DST_CHECKED' => $time_mode_manual_dst_checked,
		'TIME_MODE_SERVER_SWITCH_CHECKED' => $time_mode_server_switch_checked,
		'DST_TIME_LAG' => $dst_time_lag,
		'HTML_STATUS' => $html_status,
		'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
		'SMILIES_STATUS' => $smilies_status,

		'L_CURRENT_PASSWORD' => $lang['Current_password'],
		'L_NEW_PASSWORD' => ($mode == 'register') ? $lang['Password'] : $lang['New_password'],
		'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
		'L_CONFIRM_PASSWORD_EXPLAIN' => ($mode == 'editprofile') ? $lang['Confirm_password_explain'] : '',
		'L_PASSWORD_IF_CHANGED' => ($mode == 'editprofile') ? $lang['password_if_changed'] : '',
		'L_PASSWORD_CONFIRM_IF_CHANGED' => ($mode == 'editprofile') ? $lang['password_confirm_if_changed'] : '',
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_ICQ_NUMBER' => $lang['ICQ'],
		'L_MESSENGER' => $lang['MSNM'],
		'L_YAHOO' => $lang['YIM'],
		'L_SKYPE' => $lang['SKYPE'],
		'L_WEBSITE' => $lang['Website'],
		'L_AIM' => $lang['AIM'],
		'L_LOCATION' => $lang['Location'],
		'L_OCCUPATION' => $lang['Occupation'],
		'L_BOARD_LANGUAGE' => $lang['Board_lang'],
		'L_BOARD_STYLE' => $lang['Board_style'],
		'L_TIMEZONE' => $lang['Timezone'],
		'L_TIME_MODE' => $lang['time_mode'],
		'L_TIME_MODE_TEXT' => $lang['time_mode_text'],
		'L_TIME_MODE_MANUAL' => $lang['time_mode_manual'],
		'L_TIME_MODE_DST' => $lang['time_mode_dst'],
		'L_TIME_MODE_DST_OFF' => $l_time_mode_0,
		'L_TIME_MODE_DST_ON' => $l_time_mode_1,
		'L_TIME_MODE_DST_SERVER' => $l_time_mode_2,
		'L_TIME_MODE_DST_TIME_LAG' => $lang['time_mode_dst_time_lag'],
		'L_TIME_MODE_DST_MN' => $lang['time_mode_dst_mn'],
		'L_TIME_MODE_TIMEZONE' => $lang['time_mode_timezone'],
		'L_TIME_MODE_AUTO' => $lang['time_mode_auto'],
		'L_DATE_FORMAT' => $lang['Date_format'],
		'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
		'L_INTERESTS' => $lang['Interests'],
		'L_GENDER' =>$lang['Gender'],
		'L_GENDER_MALE' =>$lang['Male'],
		'L_GENDER_FEMALE' =>$lang['Female'],
		'L_GENDER_NOT_SPECIFY' =>$lang['No_gender_specify'],
		'L_BIRTHDAY' => $lang['Birthday'],
		'L_ALWAYS_ALLOW_SMILIES' => $lang['Always_smile'],
		'L_ALWAYS_ALLOW_SWEARYWORDS' => $lang['Always_swear'],
		'L_ALWAYS_ALLOW_BBCODE' => $lang['Always_bbcode'],
		'L_ALWAYS_ALLOW_HTML' => $lang['Always_html'],
		'L_HOT_THRESHOLD' => $lang['Hot_threshold'],
		'L_POSTS_PER_PAGE' => $lang['Posts_per_page'],
		'L_TOPICS_PER_PAGE' => $lang['Topics_per_page'],
		'L_HIDE_USER' => $lang['Hide_user'],
		'L_ALWAYS_ADD_SIGNATURE' => $lang['Always_add_sig'],
		'L_ALWAYS_SET_BOOKMARK' => $lang['Always_set_bm'],
		'L_SHOW_AVATARS' => $lang['Show_avatars'],
		'L_SHOW_SIGNATURES' => $lang['Show_signatures'],
		'L_RETRO_SIG' => $lang['Retro_sig'],
		'L_RETRO_SIG_EXPLAIN' => $lang['Retro_sig_explain'],
		'L_RETRO_SIG_CHECKBOX' => $lang['Retro_sig_checkbox'],
		'L_AVATAR_SIGNATURE' => $lang['Signature_Panel'],
		'L_AVATAR_PANEL' => $lang['Avatar_panel'],
		'L_AVATAR_EXPLAIN' => sprintf($lang['Avatar_explain'], $config['avatar_max_width'], $config['avatar_max_height'], (round($config['avatar_filesize'] / 1024))),
		'L_UPLOAD_AVATAR_FILE' => $lang['Upload_Avatar_file'],
		'L_UPLOAD_AVATAR_URL' => $lang['Upload_Avatar_URL'],
		'L_UPLOAD_AVATAR_URL_EXPLAIN' => $lang['Upload_Avatar_URL_explain'],
		'L_AVATAR_GALLERY' => $lang['Select_from_gallery'],
		'L_SHOW_GALLERY' => $lang['View_avatar_gallery'],
		'L_GENERATE_AVATAR' => $lang['Create_with_generator'],
		'L_AVATAR_GENERATOR' => $lang['View_avatar_generator'],
		'L_LINK_REMOTE_AVATAR' => $lang['Link_remote_Avatar'],
		'L_LINK_REMOTE_AVATAR_EXPLAIN' => $lang['Link_remote_Avatar_explain'],
		'L_GRAVATAR' => $lang['Gravatar'],
		'L_GRAVATAR_EXPLAIN' => $lang['Gravatar_explain'],
		'L_DELETE_AVATAR' => $lang['Delete_Image'],
		'L_CURRENT_IMAGE' => $lang['Current_Image'],

		'L_SIGNATURE' => $lang['Signature'],
		'L_SIGNATURE_EXPLAIN' => sprintf($lang['Signature_explain'], $config['max_sig_chars']),
		'L_NOTIFY_ON_REPLY' => $lang['Always_notify'],
		'L_NOTIFY_ON_REPLY_EXPLAIN' => $lang['Always_notify_explain'],
		'L_NOTIFY_ON_PRIVMSG' => $lang['Notify_on_privmsg'],
		'L_POPUP_ON_PRIVMSG' => $lang['Popup_on_privmsg'],
		'L_POPUP_ON_PRIVMSG_EXPLAIN' => $lang['Popup_on_privmsg_explain'],
		'L_PREFERENCES' => $lang['Preferences'],
//<!-- BEGIN Unread Post Information to Database Mod -->
		'L_UPI2DB_SYSTEM' => $lang['upi2db_system'],
		'L_UPI2DB_WHICH_SYSTEM' => $lang['upi2db_which_system'],
		'L_UPI2DB_WHICH_SYSTEM_EXPLAIN' => $lang['upi2db_which_system_explain'],
		'L_UPI2DB_NEW_WORD' => $lang['upi2db_new_word'],
		'L_UPI2DB_NEW_WORD_EXPLAIN' => $lang['upi2db_new_word_explain'],
		'L_UPI2DB_EDIT_WORD' => $lang['upi2db_edit_word'],
		'L_UPI2DB_EDIT_WORD_EXPLAIN' => $lang['upi2db_edit_word_explain'],
		'L_COOKIE_SYSTEM' => $lang['cookie_system'],
		'L_UPI2DB_SYSTEM' => $lang['upi2db_system'],
		'L_UPI2DB_UNREAD_COLOR' => $lang['upi2db_unread_color'],
//<!-- END Unread Post Information to Database Mod -->
		'L_PUBLIC_VIEW_EMAIL' => $lang['Public_view_email'],
		'L_MASS_EMAIL' => $lang['Admin_Emails'],
		'L_PM_IN' => $lang['Allow_PM_IN'],
		'L_PM_IN_EXPLAIN' => $lang['Allow_PM_IN_Explain'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		'L_REGISTRATION_INFO' => $lang['Registration_info'],
		'L_PROFILE_INFO' => $lang['Profile_info'],
		'L_PROFILE_INFO_NOTICE' => $lang['Profile_info_warn'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_CONFIRM_EMAIL' => $lang['Email_confirm'],
		'EMAIL_CONFIRM' => $email_confirm,

		'L_CONFIRM_CODE_IMPAIRED' => sprintf($lang['CONFIRM_CODE_IMPAIRED'], '<a href="mailto:' . $config['board_email'] . '">', '</a>'),

		'U_AJAX_VERIFY' => 'ajax_verify.' . PHP_EXT,
		'VERIFY_UN_JS' => $verify_un_js,
		'VERIFY_EMAIL_JS' => $verify_email_js,
		'L_UN_SHORT' => $lang['Reg_Username_Short'],
		'L_UN_LONG' => $lang['Reg_Username_Long'],
		'L_UN_TAKEN' => $lang['Reg_Username_Taken'],
		'L_UN_FREE' => $lang['Reg_Username_Free'],
		'L_PWD_SHORT' => $lang['Reg_PWD_Short'],
		'L_PWD_EASY' => $lang['Reg_PWD_Easy'],
		'L_PWD_OK' => $lang['Reg_PWD_OK'],
		'L_EMAIL_INVALID' => $lang['Reg_Email_Invalid'],
		'L_EMAIL_OK' => $lang['Reg_Email_OK'],

		'S_ALLOW_AVATAR_UPLOAD' => $config['allow_avatar_upload'],
		'S_ALLOW_AVATAR_LOCAL' => $config['allow_avatar_local'],
		'S_ALLOW_AVATAR_REMOTE' => $config['allow_avatar_remote'],
		'S_ALLOW_AVATAR_GENERATOR' => $config['allow_avatar_generator'],

		'S_ENABLE_GRAVATARS' => $config['enable_gravatars'],
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_FORM_ENCTYPE' => $form_enctype,
		'S_PROFILE_ACTION' => append_sid(CMS_PAGE_PROFILE . '?cpl_mode=' . $cpl_mode)
		)
	);

	//
	// This is another cheat using the block_var capability
	// of the templates to 'fake' an IF...ELSE...ENDIF solution
	// it works well :)
	//
	if ($mode != 'register')
	{
		if ($userdata['user_allowavatar'] && ($config['allow_avatar_upload'] || $config['allow_avatar_local'] || $config['allow_avatar_remote']|| $config['enable_gravatars'] || $config['allow_avatar_generator']))
		{
			$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block', array());

			if ($config['allow_avatar_upload'] && file_exists(@phpbb_realpath('./' . $config['avatar_path'])))
			{
				if ($form_enctype != '')
				{
					$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_local_upload', array());
				}
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_remote_upload', array());
			}

			if ($config['allow_avatar_remote'])
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_remote_link', array());
			}

			if ($config['allow_avatar_local'] && file_exists(@phpbb_realpath('./' . $config['avatar_gallery_path'])))
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_local_gallery', array());
			}

			if ($config['allow_avatar_generator'] && file_exists(@phpbb_realpath('./' . $config['avatar_generator_template_path'])))
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_generator', array());
			}

			if($config['enable_gravatars'])
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_gravatar', array());
			}
		}
	}
}

full_page_generation($template_to_parse, '', '', '');

?>