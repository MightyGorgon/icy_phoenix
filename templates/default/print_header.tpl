<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title>{SITENAME} :: {PAGE_TITLE}</title>
	<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_compressed.js"></script>
	<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}print_version.css" type="text/css" />
	<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}print_size_normal.css" type="text/css" title="A" />
	<link rel="alternate stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}print_size_small.css" type="text/css" title="A-" />
	<link rel="alternate stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}print_size_medium.css" type="text/css" title="A+" />
	<link rel="alternate stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}print_size_large.css" type="text/css" title="A++" />
	<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
	<!--[if IE]>
	<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}common_ie.css" type="text/css" />
	<![endif]-->

	<!--[if lt IE 7]>
	<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/pngfix.js"></script>
	<![endif]-->

	<script type="text/javascript">
	// <![CDATA[
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

	<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ip_scripts.js"></script>
	<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/styleswitcher.js"></script>
</head>
<body>
<div align="center"><a title="{L_CHANGE_FONT_SIZE}" onkeypress="return fontsizeup(event);" onclick="fontsizeup(); return false;" href="#">[+/-]</a></div>
