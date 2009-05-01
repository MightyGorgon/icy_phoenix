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

<!-- INCLUDE overall_inc_header_js.tpl -->

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

<!-- BEGIN switch_new_download -->
<script type="text/javascript">
<!--
	window.open('{switch_new_download.U_NEW_DOWNLOAD_POPUP}', '_blank', 'width=400,height=225,resizable=yes');
//-->
</script>
<!-- END switch_new_download -->

{UPI2DB_FIRST_USE}
