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
<script type="text/javascript">
<!--
var S_SID = '{S_SID}';
var FULL_SITE_PATH = '{FULL_SITE_PATH}';
var ip_root_path = '{IP_ROOT_PATH}';
var php_ext = '{PHP_EXT}';
var POST_FORUM_URL = '{POST_FORUM_URL}';
var POST_TOPIC_URL = '{POST_TOPIC_URL}';
var POST_POST_URL = '{POST_POST_URL}';
var LOGIN_MG = '{LOGIN_MG}';
var PORTAL_MG = '{PORTAL_MG}';
var FORUM_MG = '{FORUM_MG}';
var VIEWFORUM_MG = '{VIEWFORUM_MG}';
var VIEWTOPIC_MG = '{VIEWTOPIC_MG}';
var PROFILE_MG = '{PROFILE_MG}';
var POSTING_MG = '{POSTING_MG}';
var SEARCH_MG = '{SEARCH_MG}';
//-->
</script>

<script type="text/javascript" src="{FULL_SITE_PATH}language/lang_{CURRENT_LANG}/bbcb_mg.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ip_scripts.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/prototype.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/chrome.js"></script>

<!-- IF S_LIGHTBOX -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}lightbox/lightbox_old.css" type="text/css" media="screen" />
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}lightbox/lightbox_old.js"></script>
<!-- ENDIF -->

<!-- BEGIN switch_ajax_features -->
<script type="text/javascript">
<!--
var ajax_core_defined = 0;
var ajax_page_charset = '{S_CONTENT_ENCODING}';
//-->
</script>

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ajax/ajax_core.js"></script>
<!-- END switch_ajax_features -->

<!--[if lt IE 7]>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/pngfix.js"></script>
<![endif]-->

<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
<!--[if IE]>
<style type="text/css">
/* IE hack to emulate the :hover & :focus pseudo-classes - Add the selectors below that required the extra attributes */
.row1h, .row1h-new { behavior: url("{FULL_SITE_PATH}{T_COMMON_TPL_PATH}pseudo-hover.htc"); }
</style>
<![endif]-->

<!-- BEGIN js_include -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{js_include.JS_FILE}"></script>
<!-- END js_include -->

</head>
<body id="cms" onload="PreloadFlag=true;">
<!-- IF S_CMS_AUTH -->
<div class="main-header">
	<div class="chromestyle" id="chromemenu">
		<ul>
			<li><a href="#" rel="dropmenu_cms_management">{L_CMS}</a></li>
			<!-- <li><a href="#" rel="dropmenu_cms_adv_management">{L_CMS_ADV}</a></li> -->
			<li><a href="#" rel="dropmenu_cms_settings">{L_CMS_SETTINGS}</a></li>
			<li><a href="#" rel="dropmenu_links">{L_CMS_LINKS}</a></li>
		</ul>
	</div>

	<div id="dropmenu_cms_management" class="dropmenudiv">
		<a href="{U_CMS_GLOBAL_BLOCKS}">&nbsp;<img src="images/cms/menu/cms_blocks.png" alt="" />&nbsp;{L_CMS_GLOBAL_BLOCKS}</a>
		<a href="{U_CMS_STANDARD_PAGES}">&nbsp;<img src="images/cms/menu/cms_standard_pages.png" alt="" />&nbsp;{L_CMS_STANDARD_PAGES}</a>
		<a href="{U_CMS_CUSTOM_PAGES}">&nbsp;<img src="images/cms/menu/cms_custom_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES}</a>
		<a href="{U_CMS_CUSTOM_PAGES_ADV}">&nbsp;<img src="images/cms/menu/cms_custom_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES_ADV}</a>
		<a href="{U_CMS_MENU}">&nbsp;<img src="images/cms/menu/cms_menu.png" alt="" />&nbsp;{L_CMS_MENU}</a>
	</div>

	<!--
	<div id="dropmenu_cms_adv_management" class="dropmenudiv">
		<a href="cms_adv.php?mode=layouts">&nbsp;<img src="images/cms/menu/cms_custom_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES_ADV}</a>
		<a href="cms_adv.php?mode=layouts&amp;cms_type=cms_standard">&nbsp;<img src="images/cms/menu/cms_standard_pages.png" alt="" />&nbsp;{L_CMS_CUSTOM_PAGES}</a>
	</div>
	-->

	<div id="dropmenu_cms_settings" class="dropmenudiv">
		<a href="{U_CMS_CONFIG}">&nbsp;<img src="images/cms/menu/cms_settings.png" alt="" />&nbsp;{L_CMS_CONFIG}</a>
		<a href="{U_CMS_PAGES_PERMISSIONS}">&nbsp;<img src="images/cms/menu/cms_permissions.png" alt="" />&nbsp;{L_CMS_PAGES_PERMISSIONS}</a>
	</div>

	<div id="dropmenu_links" class="dropmenudiv">
		<a href="{U_CMS_ACP}">&nbsp;<img src="images/cms/menu/cms_acp.png" alt="" />&nbsp;{L_CMS_ACP}</a>
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
