<script type="text/javascript">
// <![CDATA[
// OS / BROWSER VARS - BEGIN
// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf('msie') != -1) && (clientPC.indexOf('opera') == -1));
var is_win = ((clientPC.indexOf('win') != -1) || (clientPC.indexOf('16bit') != -1));
var is_iphone = ((clientPC.indexOf('iphone')) != -1);

// Other check in vars...
var uAgent = navigator.userAgent;
// NS 4
var ns4 = (document.layers) ? true : false;
// IE 4
var ie4 = (document.all) ? true : false;
// DOM
var dom = (document.getElementById) ? true : false;
// + OP5
var ope = ((uAgent.indexOf("Opera") > -1) && dom) ? true : false;
// IE5
var ie5 = (dom && ie4 && !ope) ? true : false;
// + NS 6
var ns6 = (dom && (uAgent.indexOf("Netscape") > -1)) ? true : false;
// + Konqueror
var khtml = (uAgent.indexOf("khtml") > -1) ? true : false;
//alert("UserAgent: "+uAgent+"\nns4 :"+ns4+"\nie4 :"+ie4+"\ndom :"+dom+"\nie5 :"+ie5+"\nns6 :"+ns6+"\nope :"+ope+"\nkhtml :"+khtml);
// OS / BROWSER VARS - END

var S_SID = '{S_SID}';
var FULL_SITE_PATH = '{FULL_SITE_PATH}';
var ip_root_path = '{IP_ROOT_PATH}';
var php_ext = '{PHP_EXT}';
var POST_FORUM_URL = '{POST_FORUM_URL}';
var POST_TOPIC_URL = '{POST_TOPIC_URL}';
var POST_POST_URL = '{POST_POST_URL}';
var CMS_PAGE_LOGIN = '{CMS_PAGE_LOGIN}';
var CMS_PAGE_HOME = '{CMS_PAGE_HOME}';
var CMS_PAGE_FORUM = '{CMS_PAGE_FORUM}';
var CMS_PAGE_VIEWFORUM = '{CMS_PAGE_VIEWFORUM}';
var CMS_PAGE_VIEWTOPIC = '{CMS_PAGE_VIEWTOPIC}';
var CMS_PAGE_PROFILE = '{CMS_PAGE_PROFILE}';
var CMS_PAGE_POSTING = '{CMS_PAGE_POSTING}';
var CMS_PAGE_SEARCH = '{CMS_PAGE_SEARCH}';
var form_name = 'post';
var text_name = 'message';
var onload_functions = new Array();
var onunload_functions = new Array();

/**
* New function for handling multiple calls to window.onload and window.unload by pentapenguin
*/
window.onload = function()
{
	for (var i = 0; i < onload_functions.length; i++)
	{
		eval(onload_functions[i]);
	}
}

window.onunload = function()
{
	for (var i = 0; i < onunload_functions.length; i++)
	{
		eval(onunload_functions[i]);
	}
}
// ]]>
</script>

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ip_scripts.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/run_active_content.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_compressed.js"></script>

<!-- IF S_JQUERY_UI -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/ui/themes/{S_JQUERY_UI_STYLE}/jquery-ui.css" type="text/css" media="screen" />
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/ui/jquery-ui-ip.css" type="text/css" media="screen" />
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/ui/jquery-ui-i18n.min.js" type="text/javascript"></script>
<!-- ENDIF -->

<!-- IF S_JQUERY_UI_TP -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/ui/jquery-ui-timepicker.css" type="text/css" media="screen" />
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/ui/jquery-ui-timepicker.js" type="text/javascript"></script>
<!-- ENDIF -->

<!-- IF S_JQ_CYCLE_SLIDESHOW -->
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_cycle_all_compressed.js" type="text/javascript"></script>
<!-- ENDIF -->

<!-- IF S_JQ_NIVO_SLIDER -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_nivo_slider_custom.css" type="text/css" media="screen" />
<script src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_nivo_slider_compressed.js" type="text/javascript"></script>
<!-- ENDIF -->

<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
<!--[if IE]>
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}common_ie.css" type="text/css" />
<![endif]-->

<!--[if lt IE 7]>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/pngfix.js"></script>
<![endif]-->

<!-- IF S_HIGHSLIDE -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}highslide/highslide.css" type="text/css" media="screen" />
<!--[if lt IE 7]>
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}highslide/highslide-ie6.css" type="text/css" />
<![endif]-->
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
	<!-- IF S_HIGHSLIDER -->
	,
	thumbstrip: {
		position: 'above',
		mode: 'horizontal',
		relativeTo: 'expander'
	}
	<!-- ENDIF -->
});
</script>
<!-- ENDIF -->

<!-- IF S_AJAX_FEATURES -->
<script type="text/javascript">
<!--
var ajax_core_defined = 0;
var ajax_page_charset = '{S_CONTENT_ENCODING}';
//-->
</script>

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ajax/ajax_core.js"></script>
<!-- ENDIF -->

<!-- BEGIN js_include -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{js_include.JS_FILE}"></script>
<!-- END js_include -->

<!-- IF S_SLIDESHOW -->
<!-- INCLUDE album_slideshow_inc_js.tpl -->
<!-- ENDIF -->
