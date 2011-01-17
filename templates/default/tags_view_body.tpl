<!-- INCLUDE overall_header.tpl -->

<br />
<!-- INCLUDE tags_search_js.tpl -->
<h2 style="text-align: left;"><a href="{U_TAG_RESULTS}" style="text-decoration: none;">{L_TAG_RESULTS}</a></h2>
<br clear="all" />

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOPIC_TAGS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>&nbsp;</th>
	<th>{L_FORUM}</th>
	<th>{L_TOPICS}</th>
	<th>{L_AUTHOR}</th>
	<th>{L_VIEWS}</th>
	<th>{L_REPLIES}</th>
	<th>{L_LASTPOST}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="row1 row-center" width="22"><img src="{row.TOPIC_FOLDER_IMG}" alt="{row.L_TOPIC_FOLDER_ALT}" title="{row.L_TOPIC_FOLDER_ALT}" style="margin-right: 4px;" /></td>
	<td class="row1h{row.CLASS_NEW} row-forum" onclick="window.location.href='{row.U_VIEW_FORUM}'">
		<span class="topiclink{row.CLASS_NEW}"><a href="{row.U_VIEW_FORUM}">{row.FORUM_NAME}</a></span>
	</td>
	<td class="row1h{row.CLASS_NEW} row-forum" onclick="window.location.href='{row.U_VIEW_TOPIC}'">
		<div class="topic-title-hide-flow"><div style="float: right; display: inline; vertical-align: top; margin-top: 0px !important; padding-top: 0px !important; padding-right: 3px;">{row.TOPIC_TYPE_ICON}</div>{row.NEWEST_POST_IMG}<span class="topiclink{row.CLASS_NEW}"><a href="{row.U_VIEW_TOPIC}" class="{row.TOPIC_CLASS}">{row.TOPIC_TITLE}</a></span><br /><span class="gensmall">{L_TAGS_TEXT}:&nbsp;{row.TOPIC_TAGS}</span></div>
		{row.GOTO_PAGE_FULL}
	</td>
	<td class="row3 row-center-small" nowrap="nowrap" style="padding-top: 0px; padding-left: 2px; padding-right: 2px;">{row.FIRST_TIME}<br />{row.FIRST_AUTHOR}</td>
	<td class="row2 row-center-small">{row.VIEWS}</td>
	<td class="row2 row-center-small">{row.REPLIES}</td>
	<td class="row3 row-center-small" style="padding-top: 0px; padding-left: 2px; padding-right: 2px;" nowrap="nowrap">{row.LAST_TIME}<br />{row.LAST_AUTHOR}&nbsp;{row.LAST_URL}</td>
</tr>
<!-- END row -->
<!-- BEGIN switch_no_topics -->
<tr><td colspan="7" class="row1 row-center"><span class="gen"><i>{L_NO_TOPICS}</i></span></td></tr>
<!-- END switch_no_topics -->
<tr><td class="cat" colspan="7">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->