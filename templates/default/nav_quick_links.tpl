<!-- IF SWITCH_CMS_SHOW_HIDE -->
<script type="text/javascript">
<!--
tmp = 'quick_links';
if(GetCookie(tmp) == '2')
{
	ShowHide('quick_links', 'quick_links2', 'quick_links');
	setWidth('var_width', 16);
}
//-->
</script>
<!-- ENDIF -->

<form name="ChangeThemeLang" method="post" action="{REQUEST_URI}">
{IMG_THL}{IMG_THC}
<!-- IF SWITCH_CMS_SHOW_HIDE -->
<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('quick_links', 'quick_links2', 'quick_links'); setWidth('var_width', 16); setWidth('full_width', '100%'); setWidth('full_width_cpl', '100%');" alt="{L_HIDE}" />
<!-- ENDIF -->
<span class="forumlink">{L_QUICK_LINKS}</span>{IMG_THR}<table class="forumlinenb">

<!-- IF NAV_MENU_ADS_TOP -->
<tr>
	<th style="cursor: pointer;">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_SPONSORS_LINKS}" class="nav-menu-link"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr><td class="row1">{NAV_MENU_ADS_TOP}</td></tr>
<!-- ENDIF -->

<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('main_links', 'main_links2', 'main_links');">
		<img src="{IMG_NAV_MENU_APPLICATION}" alt="{L_MAIN_LINKS}" title="{L_MAIN_LINKS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_MAIN_LINKS}" class="nav-menu-link"><b>{L_MAIN_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="main_links2" class="nav-menu">
			<table class="forumline-no2 p2px">
				<!-- IF S_ADMIN -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_CMS_ACP}">{L_LINK_ACP}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_CMS}">{L_LINK_CMS}</a></td>
				</tr>
				<!-- ENDIF -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_PORTAL}">{L_LINK_HOME}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_PROFILE}">{L_LINK_PROFILE}</a></td>
				</tr>
				<!-- ENDIF -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_INDEX}">{L_LINK_FORUM}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_BOARDRULES}">{L_LINK_BOARDRULES}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_PRIVACY_POLICY}">{L_LINK_PRIVACY_POLICY}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_COOKIE_POLICY}">{L_LINK_COOKIE_POLICY}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_FAQ}">{L_LINK_FAQ}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_SEARCH}">{L_LINK_SEARCH}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_SITEMAP}">{L_LINK_SITEMAP}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_CALENDAR}">{L_LINK_CALENDAR}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_DOWNLOADS_NAV}">{L_LINK_DOWNLOADS}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_BOOKMARKS}">{L_LINK_BOOKMARKS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_DRAFTS}">{L_LINK_DRAFTS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_UPLOADED_IMAGES}">{L_LINK_UPLOADED_IMAGES}</a></td>
				</tr>
				<!-- ENDIF -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_AJAX_SHOUTBOX}">{L_LINK_AJAX_SHOUTBOX}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_CONTACT_US}">{L_LINK_CONTACT_US}</a></td>
				</tr>
			</table>
		</div>
		<div id="main_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'main_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('main_links', 'main_links2', 'main_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('news2_links', 'news2_links2', 'news2_links');">
		<img src="{IMG_NAV_MENU_NEWSPAPER}" alt="{L_NEWS_LINKS}" title="{L_NEWS_LINKS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_NEWS_LINKS}" class="nav-menu-link"><b>{L_NEWS_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="news2_links2" class="nav-menu">
			<table class="forumline-no2 p2px">
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_PORTAL_NEWS_CAT}">{L_LINK_NEWS_CAT}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_PORTAL_NEWS_ARC}">{L_LINK_NEWS_ARC}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_SEARCH_NEW}">{L_LINK_NEW_MESSAGES}</a></td>
				</tr>
				<!-- ENDIF -->
			</table>
		</div>
		<div id="news2_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'news2_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('news2_links', 'news2_links2', 'news2_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('info_links', 'info_links2', 'info_links');">
		<img src="{IMG_NAV_MENU_INFORMATION}" alt="{L_INFO_LINKS}" title="{L_INFO_LINKS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_INFO_LINKS}" class="nav-menu-link"><b>{L_INFO_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="info_links2" class="nav-menu">
			<table class="forumline-no2 p2px">
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_CREDITS}">{L_LINK_CREDITS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_REFERERS}">{L_LINK_REFERERS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_VIEWONLINE}">{L_LINK_VIEWONLINE}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_STATISTICS}">{L_LINK_STATISTICS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_DELETE_COOKIES}">{L_LINK_DELETE_COOKIES}</a></td>
				</tr>
			</table>
		</div>
		<div id="info_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'info_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('info_links', 'info_links2', 'info_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('users_links', 'users_links2', 'users_links');">
		<img src="{IMG_NAV_MENU_GROUP}" alt="{L_USERS_LINKS}" title="{L_USERS_LINKS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_USERS_LINKS}" class="nav-menu-link"><b>{L_USERS_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="users_links2" class="nav-menu">
			<table class="forumline-no2 p2px">
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_MEMBERLIST}">{L_LINK_MEMBERLIST}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_GROUP_CP}">{L_LINK_USERGROUPS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_RANKS}">{L_LINK_RANKS}</a></td>
				</tr>
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="{U_STAFF}">{L_LINK_STAFF}</a></td>
				</tr>
			</table>
		</div>
		<div id="users_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'users_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('users_links', 'users_links2', 'users_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- IF S_STYLES_SELECT -->
<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('style_select', 'style_select2', 'style_select');">
		<img src="{IMG_NAV_MENU_PALETTE}" alt="{L_SELECT_STYLE}" title="{L_SELECT_STYLE}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_SELECT_STYLE}" class="nav-menu-link"><b>{L_SELECT_STYLE}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="style_select2" class="nav-menu"><table class="forumline-no2 p2px"><tr><td class="genmed" align="center">{STYLE_SELECT_H}</td></tr></table></div>
		<div id="style_select" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'style_select';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('style_select', 'style_select2', 'style_select');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- ENDIF -->
<!-- IF S_LANGS_SELECT -->
<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('lang_select', 'lang_select2', 'lang_select');">
		<img src="{IMG_NAV_MENU_WORLD}" alt="{L_SELECT_LANG}" title="{L_SELECT_LANG}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_SELECT_LANG}" class="nav-menu-link"><b>{L_SELECT_LANG}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="lang_select2" class="nav-menu">
			<table class="forumline-no2 p2px">
				<!-- BEGIN lang_select -->
				<tr>
					<td class="tdalignl tw8px"><img src="{lang_select_on.lang_select.LANG_FLAG}" alt="{lang_select_on.lang_select.LANG_NAME}" /></td>
					<td class="genmed tdalignl"><a href="{lang_select_on.lang_select.U_LANG_CHANGE}">{lang_select_on.lang_select.LANG_NAME}</a>
					</td>
				</tr>
				<!-- END lang_select -->
			</table>
		</div>
		<div id="lang_select" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'lang_select';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('lang_select', 'lang_select2', 'lang_select');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- ENDIF -->
<tr>
	<th style="cursor: pointer; text-align: left;" onclick="ShowHide('rss_feed', 'rss_feed2', 'rss_feed');">
		<img src="{IMG_NAV_MENU_FEED}" alt="{L_RSS_FEEDS}" title="{L_RSS_FEEDS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_RSS_FEEDS}" class="nav-menu-link"><b>{L_RSS_FEEDS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="rss_feed2" class="nav-menu">
			<table class="forumline-no2 p2px">
				<tr>
					<td class="tdalignl tw8px"><img src="{IMG_NAV_MENU_RSS_FEED}" alt="RSS" title="RSS" /></td>
					<td class="genmed tdalignl">&nbsp;{RSS_NEWS_HELP}</td>
				</tr>
			</table>
		</div>
		<div id="rss_feed" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'rss_feed';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('rss_feed', 'rss_feed2', 'rss_feed');
			}
			//-->
			</script>
		</div>
	</td>
</tr>

<!-- IF NAV_MENU_ADS_BOTTOM -->
<tr>
	<th style="cursor: pointer; text-align: left;">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" title="{L_SPONSORS_LINKS}" class="nav-menu-link"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr><td class="row1">{NAV_MENU_ADS_BOTTOM}</td></tr>
<!-- ENDIF -->

<!--
<tr>
	<th style="cursor: pointer; text-align: left;">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" onclick="return false;" title="{L_SPONSORS_LINKS}" class="nav-menu-link"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="sponsors" style="position: relative; padding-top: 0px; padding-bottom: 0px;">
			<table class="forumline-no2 p2px">
				<tr>
					<td class="tdalignl tw8px">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed tdalignl"><a href="http://www.lucalibralato.com/">Luca Libralato</a></td>
				</tr>
			</table>
		</div>
	</td>
</tr>
-->

</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>