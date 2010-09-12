<!-- BEGIN cms_dock_off -->

<br />
<br />
<a href="{U_PORTAL}" onmouseover="display_text('description', '{L_HOME}')" onmouseout="display_text('description', '&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_home.png" alt="{L_HOME}" title="{L_HOME}" /></a>&nbsp;
<a href="{U_CMS_ACP}" onmouseover="display_text('description', '{L_LINK_ACP}')" onmouseout="display_text('description', '&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_acp.png" alt="{L_LINK_ACP}" title="{L_LINK_ACP}" /></a>&nbsp;
<a href="{U_CMS}" onmouseover="display_text('description', '{L_CMS_MANAGEMENT}')" onmouseout="display_text('description', '&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_cms.png" alt="{L_CMS_MANAGEMENT}" title="{L_CMS_MANAGEMENT}" /></a>&nbsp;
<a href="{U_CMS_CONFIG}" onmouseover="display_text('description', '{L_CMS_CONFIG}')" onmouseout="display_text('description', '&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_settings.png" alt="{L_CMS_CONFIG}" title="{L_CMS_CONFIG}" /></a>&nbsp;
<a href="{U_CMS_MENU}" onmouseover="display_text('description', '{L_CMS_MENU_PAGE}')" onmouseout="display_text('description', '&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_block.png" alt="{L_CMS_MENU_PAGE}" title="{L_CMS_MENU_PAGE}" /></a>&nbsp;
<a href="{U_CMS_ADS}" onmouseover="display_text('description', '{L_CMS_ADS}')" onmouseout="display_text('description', '&nbsp;')"><img src="{FULL_SITE_PATH}images/cms/cms_ads.png" alt="{L_CMS_ADS}" title="{L_CMS_ADS}" /></a>
<br />
<br />
<div id="description" class="cms-content" style="text-align: center; font-weight: bold;"><br />&nbsp;<br /><br /></div>
<!-- END cms_dock_off -->

<!-- BEGIN cms_dock_on -->
<!--[if lt IE 7]>
<style type="text/css">
.dock img { behavior: url('{FULL_SITE_PATH}{T_COMMON_TPL_PATH}iepngfix.htc') }
</style>
<![endif]-->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_compressed.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/interface.js"></script>

<br />
<br />
<br />
<div>
<div class="dock" id="dock">
	<div class="dock-container">
	<a class="dock-item" href="{U_PORTAL}"><span>{L_HOME}</span><img src="{FULL_SITE_PATH}images/cms/cms_home.png" alt="{L_HOME}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_ACP}"><span>{L_LINK_ACP}</span><img src="{FULL_SITE_PATH}images/cms/cms_acp.png" alt="{L_LINK_ACP}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS}"><span>{L_CMS_MANAGEMENT}</span><img src="{FULL_SITE_PATH}images/cms/cms_cms.png" alt="{L_CMS_MANAGEMENT}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_CONFIG}"><span>{L_CMS_CONFIG}</span><img src="{FULL_SITE_PATH}images/cms/cms_settings.png" alt="{L_CMS_CONFIG}" /></a>&nbsp;&nbsp;
	<a class="dock-item" href="{U_CMS_MENU}"><span>{L_CMS_MENU_PAGE}</span><img src="{FULL_SITE_PATH}images/cms/cms_block.png" alt="{L_CL_CMS_MENU_PAGEMS_MENU}" /></a>&nbsp;&nbsp;
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