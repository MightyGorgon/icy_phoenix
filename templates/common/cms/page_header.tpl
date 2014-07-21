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
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}cms_new.css" type="text/css" />
<!--[if IE]>
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}cms_ie.css" type="text/css" />
<![endif]-->
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->

<!-- INCLUDE overall_inc_header_js.tpl -->

<!-- EXTRA CMS JS - BEGIN -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_tiptip.css" type="text/css" media="screen" />
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_tiptip.js" type="text/javascript"></script>
<!-- EXTRA CMS JS - END -->

<!-- EXTRA CMS JS - BEGIN -->
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_cms.js" type="text/javascript"></script>
<!-- EXTRA CMS JS - END -->

</head>
<body>

<div id="global-wrapper">
<span><a id="top"></a></span>

<div class="top-menu">
	<div id="horiz-menu">
		<ul class="menutop">
			<li>{U_ACP}</li>
			<li><a href="{FULL_SITE_PATH}{U_PORTAL}" accesskey="h">{L_HOME}</a></li>
			<li><a href="{FULL_SITE_PATH}{U_INDEX}">{L_INDEX}</a></li>
			<!-- IF S_LOGGED_IN -->
			<li><a href="{FULL_SITE_PATH}{U_PROFILE}">{L_PROFILE}</a></li>
			<!-- ENDIF -->
			<li><a href="{FULL_SITE_PATH}{U_LOGIN_LOGOUT}" accesskey="l">{L_LOGIN_LOGOUT2}</a></li>
		</ul>
	</div>
	<!-- <h2 class="sitename">{SITENAME}</h2> -->
	<img src="{FULL_SITE_PATH}images/icy_phoenix_wb.png" alt="Icy Phoenix" class="sitelogo" /><h2 class="sitename">Icy Phoenix</h2>
</div>

<!-- <div class="top-header">&nbsp;</div> -->

<div class="top-sep">&nbsp;</div>

<!-- PAGE_BEGIN -->
<div id="tabs">
	<ul>
		<!-- BEGIN tabs -->
		<li <!-- IF tabs.S_SELECTED -->class="activetab"<!-- ENDIF -->><a href="{tabs.TAB_LINK}" class="tiptip" title="{tabs.TAB_TIP}"><span><!-- IF tabs.TAB_ICON --><img src="{tabs.TAB_ICON}" alt="{tabs.TAB_TITLE}" title="{tabs.TAB_TITLE}" />&nbsp;<!-- ENDIF -->{tabs.TAB_TITLE}</span></a></li>
		<!-- END tabs -->
	</ul>
</div>

<div id="wrapper"><div id="wrapper-inner">

<table id="forumtable">
<tr>
	<td colspan="3" id="content">

	<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->