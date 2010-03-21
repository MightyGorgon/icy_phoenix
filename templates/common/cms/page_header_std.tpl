<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="content-type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="content-style-type" content="text/css" />
{META}
{META_TAG}
{NAV_LINKS}
<title>{PAGE_TITLE}</title>
<link rel="shortcut icon" href="{FULL_SITE_PATH}images/favicon.ico" />
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}acp.css" type="text/css" />
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}dock.css" type="text/css" />
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->

<!-- INCLUDE overall_inc_header_js.tpl -->

</head>
<body>
<span><a name="top"></a></span>
{PAGE_BEGIN}
<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" class="forum-buttons" colspan="3">
		<a href="{FULL_SITE_PATH}{U_PORTAL}">{L_HOME}</a>&nbsp;&nbsp;<img src="{IMG_MENU_SEP}" alt="" />&nbsp;
		<a href="{FULL_SITE_PATH}{U_INDEX}">{L_INDEX}</a>&nbsp;&nbsp;<img src="{IMG_MENU_SEP}" alt="" />&nbsp;
		<!-- IF S_LOGGED_IN -->
		<a href="{FULL_SITE_PATH}{U_PROFILE}">{L_PROFILE}</a>&nbsp;&nbsp;<img src="{IMG_MENU_SEP}" alt="" />&nbsp;
		<!-- ENDIF -->
		<a href="{FULL_SITE_PATH}{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT2}</a>
	</td>
</tr>
<tr>
	<td colspan="3" id="content">

	<!-- IF not HAS_DIED and S_SHOW_CMS_MENU -->
	<!-- INCLUDE ../common/cms/cms_nav_menu_inc_start.tpl -->
	<!-- ENDIF -->
