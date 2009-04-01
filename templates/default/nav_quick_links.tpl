<!-- IF SWITCH_CMS_GLOBAL_BLOCKS -->
<script type="text/javascript">
<!--
tmp = 'quick_links';
if(GetCookie(tmp) == '2')
{
	ShowHide('quick_links','quick_links2','quick_links');
	setWidth(16);
}
//-->
</script>
<!-- ENDIF -->

<form name="ChangeThemeLang" method="post" action="{REQUEST_URI}">
{IMG_THL}{IMG_THC}
<!-- IF SWITCH_CMS_GLOBAL_BLOCKS -->
<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('quick_links','quick_links2','quick_links');setWidth(16);" alt="{L_HIDE}" />
<!-- ENDIF -->
<span class="forumlink">{L_QUICK_LINKS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">

<!-- IF NAV_MENU_ADS_TOP -->
<tr>
	<th style="cursor:pointer;" align="left">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" title="{L_SPONSORS_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr><td class="row1">{NAV_MENU_ADS_TOP}</td></tr>
<!-- ENDIF -->

<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('main_links','main_links2','main_links');">
		<img src="{IMG_NAV_MENU_APPLICATION}" alt="{L_MAIN_LINKS}" title="{L_MAIN_LINKS}" />&nbsp;
		<a href="javascript:void(0);" title="{L_MAIN_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_MAIN_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="main_links2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<!-- IF S_ADMIN -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left">{U_ACP}</td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_CMS}">{L_CMS}</a></td>
				</tr>
				<!-- ENDIF -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_PORTAL}">{L_HOME}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_PROFILE}">{L_CPL_NAV}</a></td>
				</tr>
				<!-- ENDIF -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_INDEX}">{L_FORUM}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_BOARDRULES}">{L_BOARDRULES}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_FAQ}">{L_FAQ}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_SEARCH}">{L_SEARCH}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_SITEMAP}">{L_SITEMAP}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_ALBUM}">{L_ALBUM}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_CALENDAR}">{L_CALENDAR}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DOWNLOADS_NAV}">{L_DOWNLOADS}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_BOOKMARKS}">{L_BOOKMARKS}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DRAFTS}">{L_DRAFTS}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_UPLOADED_IMAGES}">{L_UPLOADED_IMAGES}</a></td>
				</tr>
				<!--
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="javascript:void(0);" onclick="window.open('chatbox_mod/chatbox.php','_chatbox','resizable=yes,scrollbars=yes,width=600,height=460')">Chat</a></td>
				</tr>
				-->
				<!-- ENDIF -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_AJAX_SHOUTBOX}">{L_AJAX_SHOUTBOX}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_LINKS}">{L_LINKS}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_KB}">{L_KB}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_CONTACT_US}">{L_CONTACT_US}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_SUDOKU}">{L_SUDOKU}</a></td>
				</tr>
				<!-- ENDIF -->
			</table>
		</div>
		<div id="main_links" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'main_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('main_links','main_links2','main_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('news2_links','news2_links2','news2_links');">
		<img src="{IMG_NAV_MENU_NEWSPAPER}" alt="{L_NEWS_LINKS}" title="{L_NEWS_LINKS}" />&nbsp;
		<a href="javascript:void(0);" title="{L_NEWS_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_NEWS_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="news2_links2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_PORTAL_NEWS_CAT}" title="{L_NEWS_CAT}">{L_CATEGORIES}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_PORTAL_NEWS_ARC}" title="{L_NEWS_ARC}">{L_ARCHIVES}</a></td>
				</tr>
				<!-- IF S_LOGGED_IN -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_SEARCH_NEW}">{L_NEW3}</a></td>
				</tr>
				<!-- ENDIF -->
				<!-- BEGIN switch_upi2db_on -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DISPLAY_U}" title="{L_DISPLAY_U_S}">{L_DISPLAY_UNREAD_S}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DISPLAY_M}" title="{L_DISPLAY_M_S}">{L_DISPLAY_MARKED_S}</a></td>
				</tr>
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DISPLAY_P}" title="{L_DISPLAY_P_S}">{L_DISPLAY_PERMANENT_S}</a></td>
				</tr>
				<!-- END switch_upi2db_on -->
				<!-- BEGIN switch_show_digests -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DIGESTS}">{L_DIGESTS}</a></td>
				</tr>
				<!-- END switch_show_digests -->
			</table>
		</div>
		<div id="news2_links" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'news2_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('news2_links','news2_links2','news2_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('info_links','info_links2','info_links');">
		<img src="{IMG_NAV_MENU_INFORMATION}" alt="{L_INFO_LINKS}" title="{L_INFO_LINKS}" />&nbsp;
		<a href="javascript:void(0)" title="{L_INFO_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_INFO_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="info_links2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_HACKS_LIST}">{L_HACKS_LIST}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_REFERRERS}">{L_REFERRERS}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_VIEWONLINE}">{L_WHO_IS_ONLINE}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_STATISTICS}">{L_STATISTICS}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_DELETE_COOKIES}">{L_DELETE_COOKIES}</a></td>
				</tr>
			</table>
		</div>
		<div id="info_links" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'info_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('info_links','info_links2','info_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('users_links','users_links2','users_links');">
		<img src="{IMG_NAV_MENU_GROUP}" alt="{L_USERS_LINKS}" title="{L_USERS_LINKS}" />&nbsp;
		<a href="javascript:void(0)" title="{L_USERS_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_USERS_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="users_links2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_MEMBERLIST}">{L_MEMBERLIST}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_GROUP_CP}">{L_USERGROUPS}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_RANKS}">{L_RANKS}</a></td>
				</tr>
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{U_STAFF}">{L_STAFF}</a></td>
				</tr>
			</table>
		</div>
		<div id="users_links" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'users_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('users_links','users_links2','users_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- BEGIN style_select_on -->
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('style_select','style_select2','style_select');">
		<img src="{IMG_NAV_MENU_PALETTE}" alt="{L_SELECT_STYLE}" title="{L_SELECT_STYLE}" />&nbsp;
		<a href="javascript:void(0);" title="{L_SELECT_STYLE}" style="vertical-align:top;text-decoration:none;"><b>{L_SELECT_STYLE}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="style_select2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td class="genmed" align="center">{STYLE_SELECT_H}</td>
				</tr>
			</table>
		</div>
		<div id="style_select" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'style_select';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('style_select','style_select2','style_select');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- END style_select_on -->
<!-- BEGIN lang_select_on -->
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('lang_select','lang_select2','lang_select');">
		<img src="{IMG_NAV_MENU_WORLD}" alt="{L_SELECT_LANG}" title="{L_SELECT_LANG}" />&nbsp;
		<a href="javascript:void(0);" title="{L_SELECT_LANG}" style="vertical-align:top;text-decoration:none;"><b>{L_SELECT_LANG}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="lang_select2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<!-- BEGIN lang_select -->
				<tr>
					<td width="8" align="left"><img src="{lang_select_on.lang_select.LANG_FLAG}" alt="{lang_select_on.lang_select.LANG_NAME}" /></td>
					<td class="genmed" align="left"><a href="{lang_select_on.lang_select.U_LANG_CHANGE}">{lang_select_on.lang_select.LANG_NAME}</a>
					</td>
				</tr>
				<!-- END lang_select -->
			</table>
		</div>
		<div id="lang_select" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'lang_select';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('lang_select','lang_select2','lang_select');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- END lang_select_on -->
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('rss_feed','rss_feed2','rss_feed');">
		<img src="{IMG_NAV_MENU_FEED}" alt="{L_RSS_FEEDS}" title="{L_RSS_FEEDS}" />&nbsp;
		<a href="javascript:void(0);" title="{L_RSS_FEEDS}" style="vertical-align:top;text-decoration:none;"><b>{L_RSS_FEEDS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="rss_feed2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left"><img src="{IMG_NAV_MENU_RSS_FEED}" alt="RSS" title="RSS" /></td>
					<td class="genmed" align="left">&nbsp;{RSS_NEWS_HELP}</td>
				</tr>
			</table>
		</div>
		<div id="rss_feed" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'rss_feed';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('rss_feed','rss_feed2','rss_feed');
			}
			//-->
			</script>
		</div>
	</td>
</tr>

<!-- IF NAV_MENU_ADS_BOTTOM -->
<tr>
	<th style="cursor:pointer;" align="left">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" title="{L_SPONSORS_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr><td class="row1">{NAV_MENU_ADS_BOTTOM}</td></tr>
<!-- ENDIF -->

<!--
<tr>
	<th style="cursor:pointer;" align="left">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="javascript:void(0);" title="{L_SPONSORS_LINKS}" style="vertical-align:top;text-decoration:none;"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="sponsors" style="position: relative; padding-top: 0px; padding-bottom: 0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="http://www.mightygorgon.com/">Mighty Gorgon</a></td>
				</tr>
			</table>
		</div>
	</td>
</tr>
-->

</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>