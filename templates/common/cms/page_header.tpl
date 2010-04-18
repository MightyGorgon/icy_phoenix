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
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}cms.css" type="text/css" />
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->

<!-- INCLUDE overall_inc_header_js.tpl -->

<script type="text/javascript">
<!--
	function show_nav(menu_id)
	{
		for(i=0; i<{N_TABS}; i++)
		{
			if (i == menu_id)
		  {
				document.getElementById('cms_nav').innerHTML = document.getElementById('menu' + i).innerHTML;
				document.getElementById('tc_' + i).className += ' selected';
				document.getElementById('link_' + i).className = 'selected';
			}
			else
			{
				document.getElementById('tc_' + i).className = 'tabs_center';
				document.getElementById('link_' + i).className = '';
			}
		}
	}
//-->
</script>

</head>
<body id="cms" onload="PreloadFlag=true;">
<!-- IF S_CMS_AUTH -->

<div class="main-header">
	<div id="cms_menu">
		<ul class="tabs">
			<!-- BEGIN tabs -->
			<li>
				<div onclick="show_nav('{tabs.TABS_ID}');">
					<div id="tc_{tabs.TABS_ID}" class="tabs_center {tabs.SELECTED}">
					<div id="link_{tabs.TABS_ID}" class="{tabs.SELECTED}">{tabs.TABS_TITLE}</div>
					</div>
				</div>
			</li>
			<!-- END tabs -->
		</ul>
		 <div id="cms_nav">{CURRENT_NAV}</div>
	</div>
</div>

<!-- BEGIN tabs -->
<div id="menu{tabs.TABS_ID}" style="display:none">{tabs.TABS_NAV}</div>
<!-- END tabs -->

<!-- ENDIF -->
<div class="main-content">
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%" valign="top">
		<a name="top"></a>
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<!-- IF S_CMS_AUTH -->
			<td valign="top" width="110">
				<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
					<tr><td height="80" class="row1h row-center">
					<!-- IF S_L_ADD || S_L_EDIT || S_L_DELETE -->
					<a href="{U_CMS_USERS_CUSTOM_PAGES}"><img src="images/cms/cms_users_pages.png" alt="{L_CMS_USERS_LAYOUTS}" title="{L_CMS_USERS_LAYOUTS}" /></a>
					<!-- ELSE -->
					<img src="images/cms/cms_users_pages_locked.png" alt="{L_CMS_USERS_LAYOUTS}" title="{L_CMS_USERS_LAYOUTS}" />
					<!-- ENDIF -->
					<br /><b>{L_CMS_USERS_LAYOUTS}</b></td></tr>
					<tr><td class="row1h row-center">
					<!-- IF S_B_ADD || S_B_EDIT || S_B_DELETE -->
					<a href="{U_CMS_USERS_BLOCK_SETTINGS}"><img src="images/cms/cms_users_installed_blocks.png" alt="{L_CMS_BLOCK_SETTINGS}" title="{L_CMS_BLOCK_SETTINGS}" /></a>
					<!-- ELSE -->
					<img src="images/cms/cms_users_installed_blocks_locked.png" alt="{L_CMS_BLOCK_SETTINGS}" title="{L_CMS_BLOCK_SETTINGS}" />
					<!-- ENDIF -->
					<br /><b>{L_CMS_BLOCK_SETTINGS}</b></td></tr>
					<tr><td class="row1h row-center">
					<!-- IF S_B_ADD || S_B_EDIT || S_B_DELETE -->
					<a href="{U_CMS_USERS_GLOBAL_BLOCKS}"><img src="images/cms/cms_users_global_blocks.png" alt="{L_CMS_GLOBAL_BLOCKS}" title="{L_CMS_GLOBAL_BLOCKS}" /></a>
					<!-- ELSE -->
					<img src="images/cms/cms_users_global_blocks_locked.png" alt="{L_CMS_GLOBAL_BLOCKS}" title="{L_CMS_GLOBAL_BLOCKS}" />
					<!-- ENDIF -->
					<br /><b>{L_CMS_GLOBAL_BLOCKS}</b></td></tr>
					<tr><td class="row1h row-center"><a href="{U_CMS_MENU}"><img src="images/cms/cms_users_menu.png" alt="{L_CMS_USERS_MENU}" title="{L_CMS_USERS_MENU}" /></a><br /><b>{L_CMS_USERS_MENU}</b></td></tr>
					<tr><td class="row1h row-center">
					<!-- IF S_EDIT_SETTINGS -->
					<a href="{U_CMS_USERS_CONFIG}"><img src="images/cms/cms_users_config.png" alt="{L_CMS_USERS_CONFIG}" title="{L_CMS_USERS_CONFIG}" /></a>
					<!-- ELSE -->
					<img src="images/cms/cms_users_config_locked.png" alt="{L_CMS_USERS_CONFIG}" title="{L_CMS_USERS_CONFIG}" />
					<!-- ENDIF -->
					<br /><b>{L_CMS_USERS_CONFIG}</b></td></tr>
					<tr><td class="row1h row-center">
					<!-- IF S_EDIT_SETTINGS -->
					<a href="{U_CMS_USERS_AUTH}"><img src="images/cms/cms_users_auth.png" alt="{L_CMS_AUTH}" title="{L_CMS_AUTH}" /></a>
					<!-- ELSE -->
					<img src="images/cms/cms_users_auth_locked.png" alt="{L_CMS_USERS_AUTH}" title="{L_CMS_USERS_AUTH}" />
					<!-- ENDIF -->
					<br /><b>{L_CMS_AUTH}</b></td></tr>
					
				</table>
			</td>
			<!-- ENDIF -->
			<td valign="top">
				<div style="padding-left:7px">