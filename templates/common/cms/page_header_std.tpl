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
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->
<script type="text/javascript">
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
</script>

<script type="text/javascript" src="{FULL_SITE_PATH}language/lang_{CURRENT_LANG}/bbcb_mg.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ip_scripts.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/prototype.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}scriptaculous/scriptaculous.js"></script>

<!-- IF S_LIGHTBOX -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}lightbox/lightbox_old.css" type="text/css" media="screen" />
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}lightbox/lightbox_old.js"></script>
<!-- ENDIF -->

<!-- IF S_HIGHSLIDE -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}highslide/highslide.css" type="text/css" media="screen" />
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}highslide/highslide-full.packed.js"></script>
<script type="text/javascript">
hs.graphicsDir = '{FULL_SITE_PATH}{T_COMMON_TPL_PATH}highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'glossy-dark';
hs.showCredits = false;
hs.fadeInOut = true;
hs.numberOfImagesToPreload = 5;
hs.outlineWhileAnimating = 2; // 0 = never, 1 = always, 2 = HTML only
hs.loadingOpacity = 0.75;
hs.dimmingOpacity = 0.75;

// Add the controlbar
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: .75,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});
</script>
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
