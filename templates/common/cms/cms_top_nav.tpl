<!-- BEGIN cms_dock_off -->
<script type="text/javaScript">
<!--
function icon_text(text)
{
	document.getElementById('description').innerHTML = '<br />' + text + '<br /><br />';
}
-->
</script>

<br />
<br />
<a href="{U_PORTAL}" onmouseover="icon_text('{L_HOME}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_home.png" alt="{L_HOME}" title="{L_HOME}" /></a>&nbsp;
<a href="{U_CMS_ACP}" onmouseover="icon_text('{L_CMS_ACP}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_acp.png" alt="{L_CMS_ACP}" title="{L_CMS_ACP}" /></a>&nbsp;
<a href="{U_CMS}" onmouseover="icon_text('{L_CMS_MANAGEMENT}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_cms.png" alt="{L_CMS_MANAGEMENT}" title="{L_CMS_MANAGEMENT}" /></a>&nbsp;
<a href="{U_CMS_CONFIG}" onmouseover="icon_text('{L_CMS_CONFIG}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_settings.png" alt="{L_CMS_CONFIG}" title="{L_CMS_CONFIG}" /></a>&nbsp;
<a href="{U_CMS_MENU}" onmouseover="icon_text('{L_CMS_MENU_PAGE}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_block.png" alt="{L_CMS_MENU_PAGE}" title="{L_CMS_MENU_PAGE}" /></a>&nbsp;
<a href="{U_CMS_PAGES_PERMISSIONS}" onmouseover="icon_text('{L_CMS_PAGES_PERMISSIONS}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_pages_permissions.png" alt="{L_CMS_PAGES_PERMISSIONS}" title="{L_CMS_PAGES_PERMISSIONS}" /></a>
<a href="{U_CMS_ADS}" onmouseover="icon_text('{L_CMS_ADS}')" onMouseOut="icon_text('&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_ads.png" alt="{L_CMS_ADS}" title="{L_CMS_ADS}" /></a>
<br />
<br />
<div id="description" class="cms-content" style="font-weight:bold"><br />&nbsp;<br /><br /></div>
<!-- END cms_dock_off -->

<!-- BEGIN cms_dock_on -->
<!--[if lt IE 7]>
<style type="text/css">
.dock img { behavior: url('{FULL_SITE_PATH}{T_COMMON_TPL_PATH}iepngfix.htc') }
</style>
<![endif]-->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/jquery.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/interface.js"></script>

<br />
<br />
<br />
<div>
<div class="dock" id="dock">
	<div class="dock-container">
	<a class="dock-item" href="{U_PORTAL}"><span>{L_HOME}</span><img src="{FULL_SITE_PATH}images/cms/cms_home.png" alt="{L_HOME}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_ACP}"><span>{L_CMS_ACP}</span><img src="{FULL_SITE_PATH}images/cms/cms_acp.png" alt="{L_CMS_ACP}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS}"><span>{L_CMS_MANAGEMENT}</span><img src="{FULL_SITE_PATH}images/cms/cms_cms.png" alt="{L_CMS_MANAGEMENT}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_CONFIG}"><span>{L_CMS_CONFIG}</span><img src="{FULL_SITE_PATH}images/cms/cms_settings.png" alt="{L_CMS_CONFIG}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_MENU}"><span>{L_CMS_MENU_PAGE}</span><img src="{FULL_SITE_PATH}images/cms/cms_block.png" alt="{L_CL_CMS_MENU_PAGEMS_MENU}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_PAGES_PERMISSIONS}"><span>{L_CMS_PAGES_PERMISSIONS}</span><img src="{FULL_SITE_PATH}images/cms/cms_pages_permissions.png" alt="{L_CMS_PAGES_PERMISSIONS}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_ADS}"><span>{L_CMS_ADS}</span><img src="{FULL_SITE_PATH}images/cms/cms_ads.png" alt="{L_CMS_ADS}" /></a>&nbsp;&nbsp;
	</div>
</div>
</div>

<script type="text/javaScript">
$(document).ready(
	function()
	{
		$('#dock').Fisheye(
			{
				maxWidth: 60,
				items: 'a',
				itemsText: 'span',
				container: '.dock-container',
				itemWidth: 40,
				proximity: 80,
				alignment : 'left',
				valign: 'bottom',
				halign : 'center'
			}
		)
	}
);
</script>
<!-- END cms_dock_on -->