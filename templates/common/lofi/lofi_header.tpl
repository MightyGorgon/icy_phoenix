<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
	<meta http-equiv="content-type" content="text/html; charset={S_CONTENT_ENCODING}" />
	<meta http-equiv="content-style-type" content="text/css" />
	{META}
	{META_TAG}
	{NAV_LINKS}
	<title>{PAGE_TITLE}</title>
	<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}lofi/lofi.css" type="text/css" />
	<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ip_scripts.js"></script>
</head>
<body>
	<div id="wrapper">
		<div id="navigation">
			<div class="nav">
				<h1 style="font-size:14px;"><a href="{FULL_SITE_PATH}{U_INDEX}">{SITENAME}</a></h1>
				<h2 style="font-size:12px;">{SITE_DESCRIPTION}</h2>
			</div>
		</div>
		<div class="nav-toolbar">
			<a href="{FULL_SITE_PATH}{U_PORTAL}">{L_HOME}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_INDEX}">{L_FORUM}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_CALENDAR}">{L_CALENDAR}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_FAQ}">{L_FAQ}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_SEARCH}">{L_SEARCH}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_MEMBERLIST}">{L_MEMBERLIST}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_STATISTICS}">{L_STATISTICS}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_GROUP_CP}">{L_USERGROUPS}</a>&nbsp;&#8226;&nbsp;
			<!-- BEGIN switch_user_logged_out -->
			<a href="{FULL_SITE_PATH}{U_REGISTER}">{L_REGISTER}</a></span>&nbsp;&#8226;&nbsp;
			<!-- END switch_user_logged_out -->
			<a href="{FULL_SITE_PATH}{U_PROFILE}">{L_PROFILE}</a>&nbsp;&#8226;&nbsp;
			<a href="{FULL_SITE_PATH}{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a>&nbsp;
		</div>
		<div id="content" class="content">
