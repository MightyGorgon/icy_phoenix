<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:11 +0000 (time = 1380296051)

if (!defined('IN_ICYPHOENIX')) exit;

?><!-- This goes first, so that the other scripts can be 'jQuerized' -->
<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_compressed.js"></script>

<?php if ($this->vars['S_JQUERY_UI']) {  ?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/ui/themes/<?php echo isset($this->vars['S_JQUERY_UI_STYLE']) ? $this->vars['S_JQUERY_UI_STYLE'] : $this->lang('S_JQUERY_UI_STYLE'); ?>/jquery-ui.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/ui/jquery-ui-ip.css" type="text/css" media="screen" />
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/ui/jquery-ui-i18n.min.js" type="text/javascript"></script>
<?php } ?>

<?php if ($this->vars['S_JQUERY_UI_TP']) {  ?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/ui/jquery-ui-timepicker.css" type="text/css" media="screen" />
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/ui/jquery-ui-timepicker.js" type="text/javascript"></script>
<?php } ?>

<?php if ($this->vars['S_JQ_CYCLE_SLIDESHOW']) {  ?>
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_cycle_all_compressed.js" type="text/javascript"></script>
<?php } ?>

<?php if ($this->vars['S_JQ_NIVO_SLIDER']) {  ?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_nivo_slider_custom.css" type="text/css" media="screen" />
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_nivo_slider_compressed.js" type="text/javascript"></script>
<?php } ?>

<?php if ($this->vars['S_JQUERY_TAGS']) {  ?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_tagedit.css" type="text/css" media="screen" />
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_auto_grow_input.js" type="text/javascript"></script>
<script src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>jquery/jquery_tagedit.js" type="text/javascript"></script>
<?php } ?>

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

var S_SID = '<?php echo isset($this->vars['S_SID']) ? $this->vars['S_SID'] : $this->lang('S_SID'); ?>';
var FULL_SITE_PATH = '<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?>';
var ip_root_path = '<?php echo isset($this->vars['IP_ROOT_PATH']) ? $this->vars['IP_ROOT_PATH'] : $this->lang('IP_ROOT_PATH'); ?>';
var php_ext = '<?php echo isset($this->vars['PHP_EXT']) ? $this->vars['PHP_EXT'] : $this->lang('PHP_EXT'); ?>';
var POST_FORUM_URL = '<?php echo isset($this->vars['POST_FORUM_URL']) ? $this->vars['POST_FORUM_URL'] : $this->lang('POST_FORUM_URL'); ?>';
var POST_TOPIC_URL = '<?php echo isset($this->vars['POST_TOPIC_URL']) ? $this->vars['POST_TOPIC_URL'] : $this->lang('POST_TOPIC_URL'); ?>';
var POST_POST_URL = '<?php echo isset($this->vars['POST_POST_URL']) ? $this->vars['POST_POST_URL'] : $this->lang('POST_POST_URL'); ?>';
var CMS_PAGE_LOGIN = '<?php echo isset($this->vars['CMS_PAGE_LOGIN']) ? $this->vars['CMS_PAGE_LOGIN'] : $this->lang('CMS_PAGE_LOGIN'); ?>';
var CMS_PAGE_HOME = '<?php echo isset($this->vars['CMS_PAGE_HOME']) ? $this->vars['CMS_PAGE_HOME'] : $this->lang('CMS_PAGE_HOME'); ?>';
var CMS_PAGE_FORUM = '<?php echo isset($this->vars['CMS_PAGE_FORUM']) ? $this->vars['CMS_PAGE_FORUM'] : $this->lang('CMS_PAGE_FORUM'); ?>';
var CMS_PAGE_VIEWFORUM = '<?php echo isset($this->vars['CMS_PAGE_VIEWFORUM']) ? $this->vars['CMS_PAGE_VIEWFORUM'] : $this->lang('CMS_PAGE_VIEWFORUM'); ?>';
var CMS_PAGE_VIEWTOPIC = '<?php echo isset($this->vars['CMS_PAGE_VIEWTOPIC']) ? $this->vars['CMS_PAGE_VIEWTOPIC'] : $this->lang('CMS_PAGE_VIEWTOPIC'); ?>';
var CMS_PAGE_PROFILE = '<?php echo isset($this->vars['CMS_PAGE_PROFILE']) ? $this->vars['CMS_PAGE_PROFILE'] : $this->lang('CMS_PAGE_PROFILE'); ?>';
var CMS_PAGE_POSTING = '<?php echo isset($this->vars['CMS_PAGE_POSTING']) ? $this->vars['CMS_PAGE_POSTING'] : $this->lang('CMS_PAGE_POSTING'); ?>';
var CMS_PAGE_SEARCH = '<?php echo isset($this->vars['CMS_PAGE_SEARCH']) ? $this->vars['CMS_PAGE_SEARCH'] : $this->lang('CMS_PAGE_SEARCH'); ?>';
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
};

window.onunload = function()
{
	for (var i = 0; i < onunload_functions.length; i++)
	{
		eval(onunload_functions[i]);
	}
};

// ]]>
</script>

<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>js/ip_scripts.js"></script>
<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>js/run_active_content.js"></script>

<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
<!--[if IE]>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>common_ie.css" type="text/css" />
<![endif]-->

<!--[if lt IE 7]>
<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>js/pngfix.js"></script>
<![endif]-->

<?php if ($this->vars['S_HIGHSLIDE']) {  ?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>highslide/highslide.css" type="text/css" media="screen" />
<!--[if lt IE 7]>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>highslide/highslide-ie6.css" type="text/css" />
<![endif]-->
<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>highslide/highslide-full.packed.js"></script>
<script type="text/javascript">
// <![CDATA[
hs.graphicsDir = '<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>highslide/graphics/';
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
	<?php if ($this->vars['S_HIGHSLIDER']) {  ?>
	thumbstrip: {
		position: 'above',
		mode: 'horizontal',
		relativeTo: 'expander'
	},
	<?php } ?>
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
// ]]>
</script>
<?php } ?>

<?php if ($this->vars['S_AJAX_FEATURES']) {  ?>
<script type="text/javascript">
// <![CDATA[
var ajax_core_defined = 0;
var ajax_page_charset = '<?php echo isset($this->vars['S_CONTENT_ENCODING']) ? $this->vars['S_CONTENT_ENCODING'] : $this->lang('S_CONTENT_ENCODING'); ?>';
// ]]>
</script>

<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>js/ajax/ajax_core.js"></script>
<?php } ?>

<?php

$js_include_count = ( isset($this->_tpldata['js_include.']) ) ? sizeof($this->_tpldata['js_include.']) : 0;
for ($js_include_i = 0; $js_include_i < $js_include_count; $js_include_i++)
{
 $js_include_item = &$this->_tpldata['js_include.'][$js_include_i];
 $js_include_item['S_ROW_COUNT'] = $js_include_i;
 $js_include_item['S_NUM_ROWS'] = $js_include_count;

?>
<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?><?php echo isset($js_include_item['JS_FILE']) ? $js_include_item['JS_FILE'] : ''; ?>"></script>
<?php

} // END js_include

if(isset($js_include_item)) { unset($js_include_item); } 

?>

<?php if ($this->vars['S_SLIDESHOW']) {  ?>
<?php  $this->set_filename('xs_include_5b0f902e54a67715bd801e19ae891e7a', 'album_slideshow_inc_js.tpl', true);  $this->pparse('xs_include_5b0f902e54a67715bd801e19ae891e7a');  ?>
<?php } ?>
