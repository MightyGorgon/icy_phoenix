<!-- IF S_LOFI -->
<!-- IF S_LOFI_BOTS -->
<!-- INCLUDE ../common/lofi/bots/lofi_bots_header.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/lofi/lofi_header.tpl -->
<!-- ENDIF -->
<!-- ELSE -->
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
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}style_{CSS_COLOR}.css" type="text/css" />
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->

<!-- IF IS_PROSILVER -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}style_css.{PHP_EXT}?color={CSS_COLOR}&amp;lang={CURRENT_TPL_LANG}" type="text/css" />
<!-- ENDIF -->

<!-- INCLUDE overall_inc_header_js.tpl -->

</head>
<body<!-- IF S_SLIDESHOW_SCRIPT --> onload="runSlideShow()"<!-- ENDIF --><!-- IF NO_PADDING --> style="margin: 0 !important; padding: 0 !important;"<!-- ENDIF -->>
<a id="top">&nbsp;</a>
<!-- IF not NO_PADDING --><div class="simple_header_wrapper"><!-- ENDIF -->
<div class="rounded_line">
<table id="forumtable" width="100%" cellspacing="0" style="width: 100% !important;">
<tr>
	<td id="content"<!-- IF NO_PADDING --> style="margin: 0 !important; padding: 0 !important;"<!-- ENDIF -->>
<!-- ENDIF -->