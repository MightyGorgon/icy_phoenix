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
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}cms.css" type="text/css" />
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->

<!-- INCLUDE overall_inc_header_js.tpl -->

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/chrome.js"></script>

</head>
<body id="cms" onload="PreloadFlag=true;">
<!-- IF S_CMS_AUTH -->
<div class="main-header">
	<div class="chromestyle" id="chromemenu">
		<ul>
			<!-- IF not HAS_DIED and S_SHOW_CMS_MENU --><li><a href="#" rel="dropmenu_cms_management">{L_CMS}</a></li><!-- ENDIF -->
			<!-- <li><a href="#" rel="dropmenu_cms_adv_management">{L_CMS_ADV}</a></li> -->
			<!-- IF not HAS_DIED and S_SHOW_CMS_MENU --><li><a href="#" rel="dropmenu_cms_settings">{L_CMS_SETTINGS}</a></li><!-- ENDIF -->
			<li><a href="#" rel="dropmenu_links">{L_CMS_LINKS}</a></li>
		</ul>
	</div>

	<div id="dropmenu_cms_management" class="dropmenudiv">
		<a href="{U_CMS_GLOBAL_BLOCKS}">&nbsp;<img src="images/cms/menu/cms_blocks.png" alt="" />&nbsp;{L_CMS_GLOBAL_BLOCKS}</a>
		<a href="{U_CMS_STANDARD_PAGES}">&nbsp;<img src="images/cms/menu/cms_standard_pages.png" alt="" />&nbsp;{L_CMS_STANDARD_PAGES}</a>
		<a href="{U_CMS_CUSTOM_PAGES}">&nbsp;<img src="images/cms/menu/cms_custom_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES}</a>
		<a href="{U_CMS_CUSTOM_PAGES_ADV}">&nbsp;<img src="images/cms/menu/cms_custom_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES_ADV}</a>
		<a href="{U_CMS_MENU}">&nbsp;<img src="images/cms/menu/cms_menu.png" alt="" />&nbsp;{L_CMS_MENU_PAGE}</a>
	</div>

	<!--
	<div id="dropmenu_cms_adv_management" class="dropmenudiv">
		<a href="cms_adv.php?mode=layouts">&nbsp;<img src="images/cms/menu/cms_custom_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES_ADV}</a>
		<a href="cms_adv.php?mode=layouts&amp;cms_type=cms_standard">&nbsp;<img src="images/cms/menu/cms_standard_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES}</a>
	</div>
	-->

	<div id="dropmenu_cms_settings" class="dropmenudiv">
		<a href="{U_CMS_CONFIG}">&nbsp;<img src="images/cms/menu/cms_settings.png" alt="" />&nbsp;{L_CMS_CONFIG}</a>
		<a href="{U_CMS_ADS}">&nbsp;<img src="images/cms/menu/cms_ads.png" alt="" />&nbsp;{L_CMS_ADS}</a>
	</div>

	<div id="dropmenu_links" class="dropmenudiv">
		<!-- IF not HAS_DIED and S_SHOW_CMS_MENU --><a href="{U_CMS_ACP}">&nbsp;<img src="images/cms/menu/cms_acp.png" alt="" />&nbsp;{L_CMS_ACP}</a><!-- ENDIF -->
		<a href="{U_PORTAL}">&nbsp;<img src="images/cms/menu/cms_home.png" alt="" />&nbsp;{L_PORTAL}</a>
		<a href="{U_INDEX}">&nbsp;<img src="images/cms/menu/cms_forum.png" alt="" />&nbsp;{L_INDEX}</a>
		<hr />
		<a href="http://www.icyphoenix.com">&nbsp;<img src="images/cms/menu/cms_icy_phoenix.png" alt="" />&nbsp;Icy Phoenix</a>
	</div>

	<script type="text/javascript">cssdropdown.startchrome("chromemenu")</script>
</div>
<!-- ENDIF -->
<div class="main-content">
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%" valign="top">
		<a name="top"></a>
