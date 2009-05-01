<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td id="var_width" width="160" valign="top" align="left" style="padding-right: 7px;" nowrap="nowrap">
		<div id="quick_links_cms2" style="padding-top: 5px; display: none; margin-left: 0px; text-align: left; position: relative; float: left;"><a href="javascript:ShowHide('quick_links_cms','quick_links_cms2','quick_links_cms');setWidth('var_width',160);setWidth('full_width','auto');" title="{L_CMS}"><img src="{IMG_LAYOUT_BLOCKS_EDIT}" alt="{L_CMS}" /></a></div>
		<div id="quick_links_cms">
		<script type="text/javascript">
		<!--
		tmp = 'quick_links_cms';
		if(GetCookie(tmp) == '2')
		{
			ShowHide('quick_links_cms','quick_links_cms2','quick_links_cms');
			setWidth('var_width', 16);
			//setWidth('full_width', '100%');
		}
		//-->
		</script>
		{IMG_THL}{IMG_THC}<img style="padding-top: 3px; padding-right: 6px; float: right; cursor: pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('quick_links_cms','quick_links_cms2','quick_links_cms');setWidth('var_width',16);setWidth('full_width','100%');" alt="{L_SHOW}" />
		<span class="forumlink" nowrap="nowrap">{L_CMS_ADV}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('cms_adv_management','cms_adv_management2','cms_adv_management');">
				<div style="min-width: 130px">
					<img src="{IMG_BLOCK_EDIT}" alt="{L_CMS_MANAGEMENT}" title="{L_CMS_MANAGEMENT}" />&nbsp;
					<a href="javascript:void(0);" title="{L_CMS_MANAGEMENT}" style="vertical-align: top; text-decoration: none;"><b>{L_CMS_MANAGEMENT} ADV</b></a>
				</div>
			</th>
		</tr>
		<tr>
			<td class="row5">
				<div id="cms_adv_management2" style="display: none; position: relative; padding-top: 0px; padding-bottom: 0px;">
					<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="cms_adv.php?mode=layouts">{L_CMS_ADV_CUSTOM_PAGES}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_MENU}">{L_CMS_MENU_PAGE}</a></td>
					</tr>
					</table>
				</div>
				<div id="cms_adv_management" style="display: inline; position: relative;">
					<script type="text/javascript">
					<!--
					tmp = 'cms_adv_management';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('cms_adv_management','cms_adv_management2','cms_adv_management');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		<tr>
			<th style="cursor:pointer;" align="left" onclick="ShowHide('cms_settings_options','cms_settings_options2','cms_settings_options');">
				<img src="{IMG_NAV_MENU_WSETTINGS}" alt="{L_CPL_SETTINGS_OPTIONS}" title="{L_CPL_SETTINGS_OPTIONS}" />&nbsp;
				<a href="javascript:void(0);" title="{L_CPL_SETTINGS_OPTIONS}" style="vertical-align: top; text-decoration: none;"><b>{L_CPL_SETTINGS_OPTIONS}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row5">
				<div id="cms_settings_options2" style="display: none; position: relative; padding-top: 0px; padding-bottom: 0px;">
					<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_CONFIG}">{L_CMS_CONFIG}</a></td>
					</tr>
					</table>
				</div>
				<div id="cms_settings_options" style="display: inline; position: relative;">
					<script type="text/javascript">
					<!--
					tmp = 'cms_settings_options';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('cms_settings_options','cms_settings_options2','cms_settings_options');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		</div>
	</td>
	<td id="full_width" valign="top" style="padding-left: 7px;">
