<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td id="var_width" width="160" valign="top" align="left" style="padding-right: 7px;" nowrap="nowrap">
		<div id="quick_links_cms2" style="padding-top: 5px; display: none; margin-left: 0px; text-align: left; position: relative; float: left;"><a href="javascript:ShowHide('quick_links_cms','quick_links_cms2','quick_links_cms');setWidth('var_width',160);setWidth('full_width','auto');" title="{L_CMS_TITLE}"><img src="{IMG_LAYOUT_BLOCKS_EDIT}" alt="{L_CMS_TITLE}" /></a></div>
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
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th>
				<span style="float: right;"><img class="max-min-right" style="padding-top: 3px; padding-right: 6px; vertical-align: top;" src="{IMG_MINIMISE}" onclick="ShowHide('quick_links_cms','quick_links_cms2','quick_links_cms');setWidth('var_width',16);setWidth('full_width','100%');" alt="{L_SHOW}" /></span><img src="{IMG_NAV_MENU_APPLICATION}" alt="{L_CMS_TITLE}" title="{L_CMS_TITLE}" />&nbsp;<span style="vertical-align: top;">{L_CMS_TITLE}</span>
			</th>
		</tr>
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('cms_management','cms_management2','cms_management');">
				<img src="{IMG_BLOCK_EDIT}" alt="{L_CMS_MANAGEMENT}" title="{L_CMS_MANAGEMENT}" />&nbsp;
				<a href="#" onclick="return false;" title="{L_CMS_MANAGEMENT}" class="nav-menu-link"><b>{L_CMS_MANAGEMENT}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row1" style="padding: 0px;">
				<div id="cms_management2" class="nav-menu">
					<table class="forumlinenb" style="border-width:0px;" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_AJAX_SWITCH}">{L_CMS_AJAX_SWITCH}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_GLOBAL_BLOCKS}">{L_CMS_GLOBAL_BLOCKS}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_STANDARD_PAGES}">{L_CMS_STANDARD_PAGES}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_CUSTOM_PAGES}">{L_CMS_CUSTOM_PAGES}</a></td>
					</tr>
					<!--
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_CUSTOM_PAGES_ADV}">{L_CMS_CUSTOM_PAGES_ADV}</a></td>
					</tr>
					-->
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_MENU}">{L_CMS_MENU_PAGE}</a></td>
					</tr>
					</table>
				</div>
				<div id="cms_management" class="js-sh-box">
					<script type="text/javascript">
					<!--
					tmp = 'cms_management';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('cms_management','cms_management2','cms_management');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('cms_settings_options','cms_settings_options2','cms_settings_options');">
				<img src="{IMG_NAV_MENU_WSETTINGS}" alt="{L_CMS_SETTINGS}" title="{L_CMS_SETTINGS}" />&nbsp;
				<a href="#" onclick="return false;" title="{L_CMS_SETTINGS}" class="nav-menu-link"><b>{L_CMS_SETTINGS}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row1" style="padding:0px;">
				<div id="cms_settings_options2" class="nav-menu">
					<table class="forumlinenb" style="border-width: 0px;" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_CONFIG}">{L_CMS_CONFIG}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CMS_ADS}">{L_CMS_ADS}</a></td>
					</tr>
					</table>
				</div>
				<div id="cms_settings_options" class="js-sh-box">
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
		</table>
		</div>
	</td>
	<td id="full_width" valign="top" style="padding-left: 7px;">
