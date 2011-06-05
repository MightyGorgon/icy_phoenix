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

$sql_download = ($download != -1) ? " AND p.post_id = " . intval($download) . " " : '';

$sql = "SELECT u.user_id, u.username, p.*
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
	WHERE p.topic_id = " . $topic_id . "
		" . $sql_download . "
		AND p.deleted = 0
		AND u.user_id = p.poster_id
		ORDER BY p.post_time ASC, p.post_id ASC";
$result = $db->sql_query($sql);
$download_file = '';

$is_auth_read = array();
$break = "\r\n";
$line = '-----------------------------------';

while ($row = $db->sql_fetchrow($result))
{
	$is_auth_read = auth(AUTH_ALL, $row['forum_id'], $user->data);

	$poster_id = $row['user_id'];
	$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : $row['username'];

	$post_date = create_date($config['default_dateformat'], $row['post_time'], $config['board_timezone']);

	$post_subject = !empty($row['post_subject']) ? htmlspecialchars_decode($row['post_subject'], ENT_COMPAT) : '';

	$message = $row['post_text'];
	$message = strip_tags($message);
	$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
	$message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
	if($user->data['session_logged_in'] && !$user->data['is_bot'])
	{
		if (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD))
		{
			$show = true;
		}
		else
		{
			$sql = "SELECT p.poster_id, p.topic_id
				FROM " . POSTS_TABLE . " p
				WHERE p.topic_id = '" . $topic_id . "'
				AND p.poster_id = '" . $user->data['user_id'] . "'";
			$resultat = $db->sql_query($sql);
			$show = $db->sql_numrows($resultat) ? true : false;
		}
	}

	if(!$show && preg_match('/\[hide/i', $message))
	{
		$search = array("/\[hide\](.*?)\[\/hide\]/");
		$replace = array($lang['xs_bbc_hide_message']. ':' . $break . $lang['xs_bbc_hide_message_explain'] . $break);
		$message = preg_replace($search, $replace, $message);
	}
	$message = unprepare_message($message);
	$search = array('/&#40;/', '/&#41;/', '/&#58;/', '/&#91;/', '/&#93;/', '/&#123;/', '/&#125;/');
	$replace = array('(', ')', ':', '[', ']', '{', '}',);
	$message = preg_replace($search, $replace, $message);

	$post_subject = censor_text($post_subject);
	$message = censor_text($message);

	$download_file .= $line . $break . $poster . $break . $post_date . $break . $break . $post_subject . $break . $line . $break . $message . $break . $break . $break;
}
$db->sql_freeresult($result);

$disp_folder = ($download == -1) ? 'topic_' . $topic_id : 'post_' . $download;
$this_download_src = create_server_url() . (CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&' . $topic_id_append . (($download > 0) ? ('&' . POST_POST_URL . '=' . $download . '#p' . $download) : ''));

$download_file = $this_download_src . $break . $download_file;

if (!$is_auth_read['auth_read'])
{
	$download_file = sprintf($lang['Sorry_auth_read'], $is_auth_read['auth_read_type']);
	$disp_folder = 'Download';
}

$filename = ip_clean_string($config['sitename'], $lang['ENCODING']) . '_' . ip_clean_string($post_subject, $lang['ENCODING']) . '_' . $disp_folder . '_' . gmdate('Ymd') . '.txt';
header('Content-Type: text/x-delimtext; name="' . $filename . '"');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Content-Transfer-Encoding: plain/text');
header('Content-Length: ' . strlen($download_file));
print $download_file;

?>