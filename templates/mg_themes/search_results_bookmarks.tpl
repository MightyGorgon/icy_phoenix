{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_PROFILE}" >{L_CPL_NAV}</a>{NAV_SEP}<a href="#" class="nav-current">{L_BOOKMARKS}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;{L_SEARCH_MATCHES}
	</div>
</div>{IMG_TBR}

{CPL_MENU_OUTPUT}
<form method="post" action="{S_BM_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BOOKMARKS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="18">&nbsp;</th>
	<th>{L_FORUM}</th>
	<th>{L_TOPICS}</th>
	<th>{L_AUTHOR}</th>
	<th>{L_REPLIES}</th>
	<th>{L_LASTPOST}</th>
	<th width="18">&nbsp;</th>
</tr>
<!-- BEGIN switch_no_bookmarks -->
<!-- END switch_no_bookmarks -->
<!-- BEGIN searchresults -->
<tr>
	<td class="row1 row-center">
		<img src="{searchresults.TOPIC_FOLDER_IMG}" alt="{searchresults.L_TOPIC_FOLDER_ALT}" title="{searchresults.L_TOPIC_FOLDER_ALT}" />
	</td>
	<td class="row1h row-forum" onclick="window.location.href='{searchresults.U_VIEW_FORUM}'">
		<span class="forumlink">
			<a href="{searchresults.U_VIEW_FORUM}" class="forumlink">{searchresults.FORUM_NAME}</a>
		</span><br />
	</td>
	<td class="row1h row-forum" onclick="window.location.href='{searchresults.U_VIEW_TOPIC}'">
		<span class="topiclink">
			{searchresults.NEWEST_POST_IMG}{searchresults.TOPIC_TYPE}
			<a href="{searchresults.U_VIEW_TOPIC}" class="topictitle">{searchresults.TOPIC_TITLE}</a>
		</span>
		<span class="gotopage">{searchresults.GOTO_PAGE}</span>
	</td>
	<td class="row2 row-center-small">{searchresults.TOPIC_AUTHOR}</td>
	<td class="row1 row-center-small">{searchresults.REPLIES}</td>
	<td class="row2 row-center-small">{searchresults.LAST_POST_TIME}<br />{searchresults.LAST_POST_AUTHOR}&nbsp;{searchresults.LAST_POST_IMG}</td>
	<td class="row2 row-center-small"><input type="checkbox" name="topic_id_list[]" value="{searchresults.TOPIC_ID}" /></td>
</tr>
<!-- END searchresults -->
<tr align="right">
	<td class="catBottom" colspan="7">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="delete" class="liteoption" value="{L_DELETE}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>

<div align="right">{JUMPBOX}</div>
	</td>
	</tr>
</table>