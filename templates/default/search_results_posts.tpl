{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_SEARCH}">{L_SEARCH}</a>{NAV_SEP}<a href="#" class="nav-current">{L_SEARCH_MATCHES}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_MATCHES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="150">{L_AUTHOR}</th>
	<th width="100%">{L_MESSAGE}</th>
	<!-- BEGIN switch_upi2db_on -->
	<th>{L_MAR}</th>
	<!-- END switch_upi2db_on -->
</tr>
<!-- BEGIN searchresults -->
<tr>
	<td class="row-post-author">
		<span class="post-name">{searchresults.POSTER_NAME}</span><br />
		<div class="post-details">
			{L_REPLIES}: {searchresults.TOPIC_REPLIES}<br />
			{L_VIEWS}: {searchresults.TOPIC_VIEWS}
		</div><br />
		<img src="{SPACER}" width="150" height="3" alt="" />
	</td>
	<td class="row-post" width="100%">
		<div class="{searchresults.UNREAD_COLOR}">
			<a href="{searchresults.U_POST}"><img src="{searchresults.MINI_POST_IMG}" alt="{searchresults.L_MINI_POST_ALT}" title="{searchresults.L_MINI_POST_ALT}" /></a>
			<span class="post-details">{L_FORUM}:&nbsp;<b><a href="{searchresults.U_FORUM}">{searchresults.FORUM_NAME}</a></b>&nbsp; &nbsp;{L_POSTED}: {searchresults.POST_DATE}&nbsp; &nbsp;{L_SUBJECT}: <b><a href="{searchresults.U_POST}">{searchresults.POST_SUBJECT}</a></b></span>
		</div>
		<div class="post-text"><br />{searchresults.MESSAGE}</div>
	</td>
	<!-- BEGIN switch_upi2db_on -->
	<td valign="top" class="row-post">{searchresults.UNREAD_IMG}</td>
	<!-- END switch_upi2db_on -->
</tr>
<tr><td class="spaceRow" colspan="3"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END searchresults -->
<tr><td class="catBottom" colspan="3">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>

<div align="right">{JUMPBOX}</div>