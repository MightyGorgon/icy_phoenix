<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_header_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_header.tpl -->
<!-- ENDIF -->

<script type="text/javaScript">
<!--
function icon_text(text)
{
	document.getElementById('description').innerHTML = '<br />' + text + '<br /><br />';
}
-->
</script>

<div id="description" class="forumline" style="text-align:center;font-weight:bold"><br />&nbsp;<br /><br /></div>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="33%" height="180" class="row1h row-center"><a href="{U_CMS_STANDARD_PAGES}" onmouseover="icon_text('{L_CMS_STANDARD_PAGES}')" onmouseout="icon_text('&nbsp;')"><img src="images/cms/cms_index_standard_pages.png" alt="{L_CMS_STANDARD_PAGES}" title="{L_CMS_STANDARD_PAGES}" /></a><br /><b>{L_CMS_STANDARD_PAGES}</b></td>
	<td width="34%" class="row1h row-center"><a href="{U_CMS_CUSTOM_PAGES}" onmouseover="icon_text('{L_CMS_CUSTOM_PAGES}')" onmouseout="icon_text('&nbsp;')"><img src="images/cms/cms_index_custom_pages.png" alt="{L_CMS_CUSTOM_PAGES}" title="{L_CMS_CUSTOM_PAGES}" /></a><br /><b>{L_CMS_CUSTOM_PAGES}</b></td>
	<td width="33%" class="row1h row-center"><a href="{U_CMS_GLOBAL_BLOCKS}" onmouseover="icon_text('{L_CMS_GLOBAL_BLOCKS}')" onmouseout="icon_text('&nbsp;')"><img src="images/cms/cms_index_global_blocks.png" alt="{L_CMS_GLOBAL_BLOCKS}" title="{L_CMS_GLOBAL_BLOCKS}" /></a><br /><b>{L_CMS_GLOBAL_BLOCKS}</b></td>
</tr>
<tr>
	<td height="180" class="row1h row-center"><a href="{U_CMS_MENU}" onmouseover="icon_text('{L_CMS_MENU_PAGE}')" onmouseout="icon_text('&nbsp;')"><img src="images/cms/cms_index_menu.png" alt="{L_CMS_MENU_PAGE}" title="{L_CMS_MENU_PAGE}" /></a><br /><b>{L_CMS_MENU_PAGE}</b></td>
	<td class="row1h row-center"><a href="{U_CMS_CONFIG}" onmouseover="icon_text('{L_CMS_CONFIG}')" onmouseout="icon_text('&nbsp;')"><img src="images/cms/cms_index_config.png" alt="{L_CMS_CONFIG}" title="{L_CMS_CONFIG}" /></a><br /><b>{L_CMS_CONFIG}</b></td>
	<td class="row1h row-center"><a href="{U_CMS_ADS}" onmouseover="icon_text('{L_CMS_ADS}')" onmouseout="icon_text('&nbsp;')"><img src="images/cms/cms_index_ads.png" alt="{L_CMS_ADS}" title="{L_CMS_ADS}" /></a><br /><b>{L_CMS_ADS}</b></td>
</tr>
</table>

<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_footer_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->
<!-- ENDIF -->