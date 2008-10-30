<meta http-equiv="content-type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="content-style-type" content="text/css" />
{META}
{META_TAG}
{NAV_LINKS}
<title>{PAGE_TITLE}</title>

<link rel="shortcut icon" href="{FULL_SITE_PATH}images/favicon.ico" />
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}style_{CSS_COLOR}.css" type="text/css" />
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
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

<!-- IF S_XMAS_FX -->
<script type="text/javascript">
<!--
//Edit the next few lines to suit your page. Recommended values are:
//numFlakes = 10; downSpeed = 0.01; lrFlakes = 10;
var pictureSrc = '{FULL_SITE_PATH}images/xmas/snfl_01.gif'; //the location of the snowflakes
var pictureWidth = 10;            //the width of the snowflakes
var pictureHeight = 12;           //the height of the snowflakes
var numFlakes = 20;               //the number of snowflakes
var downSpeed = 0.01;             //the falling speed of snowflakes (portion of screen per 100 ms)
var lrFlakes = 10;                //the speed that the snowflakes should swing from side to side
																	//relative to distance fallen (swing increases with fewer
																	//snowflakes to fill available space)
//-->
</script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/snow_fx.js"></script>
<!-- ENDIF -->

<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
<!--[if IE]>
<style type="text/css">
/* IE hack to emulate the :hover & :focus pseudo-classes - Add the selectors below that required the extra attributes */
.row1h, .row1h-new { behavior: url("{FULL_SITE_PATH}{T_COMMON_TPL_PATH}pseudo-hover.htc"); }
</style>
<![endif]-->

<!-- BEGIN switch_new_download -->
<script type="text/javascript">
<!--
	window.open('{switch_new_download.U_NEW_DOWNLOAD_POPUP}', '_blank', 'width=400,height=225,resizable=yes');
//-->
</script>
<!-- END switch_new_download -->

{UPI2DB_FIRST_USE}

<!-- BEGIN js_include -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{js_include.JS_FILE}"></script>
<!-- END js_include -->
