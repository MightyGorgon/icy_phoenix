{DOCTYPE_HTML}
<head>
<!-- INCLUDE overall_inc_header.tpl -->
</head>
<body>
<span><a name="top"></a></span>
{TOP_HTML_BLOCK}
{PROFILE_VIEW}
<!-- {GREETING_POPUP} -->

<div style="margin:5px;">
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="9" height="47" valign="bottom"><img src="{FULL_SITE_PATH}{LOGO_LEFT}" width="9" height="47" alt="" /></td>
	<td width="100%" class="logo"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="left"><a href="{FULL_SITE_PATH}{U_PORTAL}"><img src="{FULL_SITE_PATH}{SITELOGO}" height="47" alt="{L_HOME}" /></a></td></tr></table></td>
	<td width="9" valign="bottom"><img src="{FULL_SITE_PATH}{LOGO_RIGHT}" width="9" height="47" alt="" /></td>
</tr>
<tr>
	<td height="22"><img src="{FULL_SITE_PATH}{T_IMAGESET_PATH}/buttons_left1.gif" width="9" height="22" alt="" /></td>
	<td class="buttons1" align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="12" align="right"><img src="{FULL_SITE_PATH}{T_IMAGESET_PATH}buttons_left2.gif" width="12" height="22" alt="" /></td>
		<td class="buttons" nowrap="nowrap" valign="top">
			<a href="{FULL_SITE_PATH}{U_PORTAL}">{L_HOME}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<a href="{FULL_SITE_PATH}{U_INDEX}">{L_INDEX}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<!-- BEGIN switch_upi2db_off -->
			<a href="{FULL_SITE_PATH}{U_SEARCH_NEW}">{L_NEW2}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<!-- END switch_upi2db_off -->
			<!-- BEGIN switch_upi2db_on -->
			<span >{L_POSTS}:&nbsp;</span><a href="{FULL_SITE_PATH}{U_SEARCH_NEW}">{L_NEW2}</a><span >&nbsp;&#8226;&nbsp;</span>{L_DISPLAY_U}<span >&nbsp;&#8226;&nbsp;</span>{L_DISPLAY_M}<span >&nbsp;&#8226;&nbsp;</span>{L_DISPLAY_P}&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<!-- END switch_upi2db_on -->
			<!-- IF S_LOGGED_IN -->
			<a href="{FULL_SITE_PATH}{U_PROFILE}">{L_PROFILE}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<!-- ENDIF -->
			<a href="{FULL_SITE_PATH}{U_SEARCH}">{L_SEARCH}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<a href="{FULL_SITE_PATH}{U_FAQ}">{L_FAQ}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<!-- IF not S_LOGGED_IN -->
			<a href="{FULL_SITE_PATH}{U_REGISTER}">{L_REGISTER}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" />&nbsp;
			<!-- ENDIF -->
			<a href="{FULL_SITE_PATH}{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT2}</a>
		</td>
		<td width="12" align="left"><img src="{FULL_SITE_PATH}{T_IMAGESET_PATH}buttons_right2.gif" width="12" height="22" alt="" /></td>
	</tr>
	</table></td>
	<td><img src="{FULL_SITE_PATH}{T_IMAGESET_PATH}buttons_right1.gif" width="9" height="22" alt="" /></td>
</tr>

{TPL_CONTENT_TOPNAV1}<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="navbar-links" align="left">&nbsp;</td>
	<td class="navbar-text" align="right">&nbsp;</td>
</tr>
</table>{TPL_CONTENT_TOPNAV2}
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">

<!-- INCLUDE overall_inc_body.tpl -->
