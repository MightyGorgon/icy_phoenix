<!-- BEGIN switch_show_news -->
<div align="center">{XS_NEWS}</div>
<!-- END switch_show_news -->

<!-- IF not S_BOT -->
{CALENDAR_BOX}
<!-- ENDIF -->

<!-- IF FORUMINDEX_BANNER_TOP --><div class="center-block-text" style="overflow:auto;">{FORUMINDEX_BANNER_TOP}</div><!-- ENDIF -->
{BOARD_INDEX}
<!-- IF FORUMINDEX_BANNER_BOTTOM --><div class="center-block-text" style="overflow:auto;">{FORUMINDEX_BANNER_BOTTOM}</div><!-- ENDIF -->

<!-- IF not S_BOT -->
<!-- IF S_VIEWONLINE -->
<div id="viewonline_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('viewonline','viewonline_h','viewonline');" alt="{L_SHOW}" /><a href="{U_VIEWONLINE}" class="forumlink">{L_WHO_IS_ONLINE}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="viewonline">
<script type="text/javascript">
<!--
tmp = 'viewonline';
if(GetCookie(tmp) == '2')
{
	ShowHide('viewonline', 'viewonline_h', 'viewonline');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('viewonline','viewonline_h','viewonline');" alt="{L_HIDE}" /><a href="{U_VIEWONLINE}" class="forumlink">{L_WHO_IS_ONLINE}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" rowspan="5" width="95" valign="middle">
		<span class="genmed">{L_WELCOME}<br />{USER_NAME}</span><br /><br />
		<div class="center-block">{AVATAR_IMG}</div>
	</td>
</tr>
<tr>
	<td class="row1" valign="middle">
		<span class="gensmall">
		{L_ONLINE_EXPLAIN}<br />
		{TOTAL_USERS_ONLINE}<br />
		{LOGGED_IN_USER_LIST}<br />
		{BOT_LIST}
		</span>
	</td>
</tr>
<!-- BEGIN switch_ac_online -->
<tr><td class="row1" valign="middle"><span class="gensmall">{AC_LIST_TEXT}&nbsp;{AC_LIST}&nbsp;[&nbsp;<a href="{U_AJAX_SHOUTBOX_PP}" target="_blank">{L_AJAX_SHOUTBOX}</a>&nbsp;]</span></td></tr>
<!-- END switch_ac_online -->
<tr>
	<td class="row1">
		<span class="gensmall">
		{L_Online_today}<br />
		{L_USERS_TODAY}{L_USERS_LASTHOUR}<br />
		{ADMINS_TODAY_LIST}<br />
		{MODS_TODAY_LIST}<br />
		{USERS_TODAY_LIST}<br />
		</span>
	</td>
</tr>
<tr><td class="row1"><span class="gensmall"><b>{L_LEGEND}:&nbsp;</b>{GROUPS_LIST_LEGEND}</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>

<div id="statistics_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('statistics','statistics_h','statistics');" alt="{L_SHOW}" /><a href="{U_STATISTICS}" class="forumlink">{L_STATISTICS}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="statistics">
<script type="text/javascript">
<!--
tmp = 'statistics';
if(GetCookie(tmp) == '2')
{
	ShowHide('statistics', 'statistics_h', 'statistics');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('statistics','statistics_h','statistics');" alt="{L_HIDE}" /><a href="{U_STATISTICS}" class="forumlink">{L_STATISTICS}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center" rowspan="5" width="95" valign="middle"><div class="center-block"><img src="{STATS_IMG}" alt="" /></div></td></tr>
<tr><td class="row1" colspan="2"><span class="gensmall">{TOTAL_POSTS}<br />{TOTAL_USERS}<br />{NEWEST_USER}<br />{RECORD_USERS}<br /></span></td></tr>
<!-- BEGIN show_recent -->
<tr>
	<td class="row-recent" nowrap="nowrap" align="left">
		<span class="gensmall">
			<b>{L_RECENT_TOPICS}:</b>
			<marquee id="recent_topics" behavior="scroll" direction="left" width="90%" height="12" scrolldelay="100" scrollamount="4" loop="true" onmouseover="this.stop()" onmouseout="this.start()">|
				<!-- BEGIN recent_topic_row -->
				<a href="{recent_topic_row.U_TITLE}" title ="{recent_topic_row.L_TITLE}" onmouseover="document.all.recent_topics.stop()" onmouseout="document.all.recent_topics.start()">{recent_topic_row.L_TITLE}</a>&nbsp;|&nbsp;
				<!-- END recent_topic_row -->
			</marquee>
		</span>
	</td>
</tr>
<!-- END show_recent -->
<!-- BEGIN switch_show_random_quote -->
<tr><td class="row1" colspan="2"><span class="gensmall"><b>{L_RANDOMQUOTE}: </b>{RANDOM_QUOTE}</span></td></tr>
<!-- END switch_show_random_quote -->
<!-- BEGIN top_posters -->
<tr><td class="row1" colspan="2"><span class="gensmall"><b>{L_TOP_POSTERS}: </b>{top_posters.TOP_POSTERS}</span></td></tr>
<!-- END top_posters -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- ENDIF -->

<!-- IF S_BIRTHDAYS -->
<div id="birthday_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('birthday','birthday_h','birthday');" alt="{L_SHOW}" /><a href="{U_CALENDAR}" class="forumlink">{L_BIRTHDAYS}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="birthday">
<script type="text/javascript">
<!--
tmp = 'birthday';
if(GetCookie(tmp) == '2')
{
	ShowHide('birthday', 'birthday_h', 'birthday');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('birthday','birthday_h','birthday');" alt="{L_HIDE}" /><a href="{U_CALENDAR}" class="forumlink">{L_BIRTHDAYS}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" rowspan="2" width="95" valign="middle"><div class="center-block"><img src="{BIRTHDAY_IMG}" alt="" /></div></td>
	<td class="row1"><span class="gensmall">{L_WHOSBIRTHDAY_TODAY}</span><br /><!-- IF L_WHOSBIRTHDAY_WEEK --><span class="gensmall">{L_WHOSBIRTHDAY_WEEK}</span><br /><!-- ENDIF --></td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- ENDIF -->

<!-- IF S_SHOUTBOX -->
<div id="shoutbox_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('shoutbox','shoutbox_h','shoutbox');" alt="{L_SHOW}" /><a href="{U_SHOUTBOX_MAX}" class="forumlink">{L_SHOUTBOX}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="shoutbox">
<script type="text/javascript">
<!--
tmp = 'shoutbox';
if(GetCookie(tmp) == '2')
{
	ShowHide('shoutbox', 'shoutbox_h', 'shoutbox');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('shoutbox','shoutbox_h','shoutbox');" alt="{L_HIDE}" /><a href="{U_SHOUTBOX_MAX}" class="forumlink">{L_SHOUTBOX}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td><iframe src="{U_SHOUTBOX}" scrolling="no" width="100%" height="190" frameborder="0" marginheight="0" marginwidth="0"></iframe></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- ENDIF -->

<!-- IF S_LINKS -->
<div id="links_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('links','links_h','links');" alt="{L_SHOW}" /><a href="{U_LINKS}" class="forumlink">{L_LINKS}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="links">
<script type="text/javascript">
<!--
tmp = 'links';
if(GetCookie(tmp) == '2')
{
	ShowHide('links', 'links_h', 'links');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('links','links_h','links');" alt="{L_HIDE}" /><a href="{U_LINKS}" class="forumlink">{L_LINKS}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1g" nowrap="nowrap" width="100%"><iframe marginwidth="0" marginheight="0" src="{U_LINKS_JS}" frameborder="0" scrolling="no" width="100%" height="{SITE_LOGO_HEIGHT}"></iframe></td>
	<td class="row1g" nowrap="nowrap" width="100"><a href="javascript:links_me()"><img src="{U_SITE_LOGO}" alt="{SITENAME}" width="{SITE_LOGO_WIDTH}" height="{SITE_LOGO_HEIGHT}" /></a></td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- ENDIF -->
<!-- ENDIF -->

<br />
<div class="center-block" style="text-align:center;margin:30px;">
{IMG_TBL}<table class="forumlinenb" style="padding:2px;border-top-width:1px;" width="100%" cellspacing="0" cellpadding="2">
<tr>
	<td class="row-center-small"><img src="{FORUM_NEW_CAT_IMG}" alt="{L_CAT_NEW_POSTS}" title="{L_CAT_NEW_POSTS}" /></td>
	<td class="row-center-small" width="11%">{L_CAT_NEW_POSTS}</td>
	<td class="row-center-small"><img src="{FORUM_CAT_IMG}" alt="{L_CAT_NO_NEW_POSTS}" title="{L_CAT_NO_NEW_POSTS}" /></td>
	<td class="row-center-small" width="11%">{L_CAT_NO_NEW_POSTS}</td>
	<td class="row-center-small"><img src="{FORUM_NEW_IMG}" alt="{L_FORUM_NEW_POSTS}" title="{L_FORUM_NEW_POSTS}" /></td>
	<td class="row-center-small" width="11%">{L_FORUM_NEW_POSTS}</td>
	<td class="row-center-small"><img src="{FORUM_IMG}" alt="{L_FORUM_NO_NEW_POSTS}" title="{L_FORUM_NO_NEW_POSTS}" /></td>
	<td class="row-center-small" width="11%">{L_FORUM_NO_NEW_POSTS}</td>
	<!--
	<td class="row-center-small"><img src="{FOLDER_AR_BIG}" alt="{L_FORUM_AR}" title="{L_FORUM_AR}" /></td>
	<td class="row-center-small">{L_FORUM_AR}</td>
	-->
	<td class="row-center-small"><img src="{FORUM_LINK_IMG}" alt="{L_LINKS}" title="{L_LINKS}" /></td>
	<td class="row-center-small" width="11%">{L_LINKS}</td>
	<td class="row-center-small"><img src="{FORUM_LOCKED_IMG}" alt="{L_FORUM_LOCKED}" title="{L_FORUM_LOCKED}" /></td>
	<td class="row-center-small" width="11%">{L_FORUM_LOCKED}</td>
</tr>
</table>{IMG_TBR}
</div>