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
?>
<html>
<head>
<title><?php echo($user->lang['ADMIN_TITLE']); ?></title>
</head>

<frameset rows="60, *" border="0" framespacing="0" frameborder="NO">
	<frame src="<?php echo('index.' . PHP_EXT . '?' . $SID . '&amp;pane=top'); ?>" name="title" noresize marginwidth="0" marginheight="0" scrolling="no">
	<frameset cols="155,*" rows="*" border="2" framespacing="0" frameborder="yes">
		<frame src="<?php echo('index.' . PHP_EXT . '?' . $SID . '&amp;pane=left'); ?>" name="nav" marginwidth="3" marginheight="3" scrolling="yes">
		<frame src="<?php echo($adm_url); ?>" name="main" id="main" marginwidth="0" marginheight="0" scrolling="auto" title="main">
	</frameset>
</frameset>

<noframes><body bgcolor="#ffffff" text="#000000"><p><?php echo($user->lang['NO_FRAMES']); ?></p></body></noframes>
</html>