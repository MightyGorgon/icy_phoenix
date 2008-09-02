{IMG_TBL}<div class="forumline nav-div content-index">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_CAT_DESC}
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="bottom" width="70%">
		<span class="gensmall">{TOTAL_USERS_ONLINE}<br/>{LOGGED_IN_USER_LIST}<br />{BOT_LIST}</span><br />
		<a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a>
	</td>
	<td align="right" valign="bottom">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
		<!-- BEGIN extended_pagination -->
		<br /><form method="post" action="{U_VIEW_FORUM}" style="display:inline;"><span class="gen">{L_GO_TO_PAGE_NUMBER}&nbsp;<input type="text" name="page_number" value="" size="3" class="post">&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></form>
		<!-- END extended_pagination -->
	</td>
</tr>
</table>
{BOARD_INDEX}

<form method="post" action="{S_POST_DAYS_ACTION}" style="display:inline;">
{IMG_THL}{IMG_THC}<a href="{U_VIEW_FORUM}" class="forumlink">{FORUM_NAME}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- IF S_SHOW_ALPHA_BAR -->
<tr>
	<td class="cat" align="center" valign="middle" colspan="5">
	<span class="gensmall">{L_SORT_TOPICS}:{DIVIDER}<a href="{U_NEWEST}">{L_SORT_TOPICS_NEWEST}</a>{DIVIDER}<a href="{U_OLDEST}">{L_SORT_TOPICS_OLDEST}</a>{DIVIDER}<a href="{U_AZ}">A-Z</a>{DIVIDER}<a href="{U_ZA}" >Z-A</a>{DIVIDER}<!-- BEGIN alphabetical_sort --><a href="{alphabetical_sort.U_LETTER}" >{alphabetical_sort.LETTER}</a>{alphabetical_sort.DIVIDER}<!-- END alphabetical_sort -->{DIVIDER}</span>
	</td>
</tr>
<!-- ENDIF -->
<tr>
	<th colspan="2">{L_TOPICS}</th>
	<th width="150">{L_AUTHOR}</th>
	<th width="150">{L_LASTPOST}</th>
	<!-- BEGIN rating_switch -->
	<th width="100">{L_RATING}</th>
	<!-- END rating_switch -->
</tr>
<!-- BEGIN topicrow -->
<tr>
	<td class="row1h{topicrow.CLASS_NEW} row-forum" width="100%" onclick="window.location.href='{topicrow.U_VIEW_TOPIC}'" colspan="2">
		<span class="topiclink{topicrow.CLASS_NEW}">
			{topicrow.NEWEST_POST_IMG}{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE_ICON}<a href="{topicrow.U_VIEW_TOPIC}" class="{topicrow.TOPIC_CLASS}">{topicrow.TOPIC_TITLE}</a>
		</span><br />
		<!-- BEGIN switch_topic_desc -->
		<span class="gensmall">&nbsp;{topicrow.switch_topic_desc.TOPIC_DESCRIPTION}</span><br />
		<!-- END switch_topic_desc -->
		<span class="gotopage">{topicrow.GOTO_PAGE}</span>
	</td>
	<td class="row3 row-center-small" style="padding-left:2px;padding-right:2px;" nowrap="nowrap">{topicrow.TOPIC_AUTHOR}</td>
	<td class="row3 row-center-small" style="padding-top:0;" nowrap="nowrap">{topicrow.LAST_POST_TIME}</td>
	<!-- BEGIN rate_switch_msg -->
	<td class="row3 row-center-small"><img src="images/rates/rate_{topicrow.TOPIC_RATING}.png"></td>
	<!-- END rate_switch_msg -->
</tr>
<!-- END topicrow -->
<!-- BEGIN switch_no_topics -->
<tr><td class="row1 row-center" colspan="5" height="30"><span class="gen">{L_NO_TOPICS}</span></td></tr>
<!-- END switch_no_topics -->
<tr>
	<td class="cat" valign="middle" colspan="5">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td valign="middle" nowrap="nowrap"><span class="genmed">&nbsp;{L_DISPLAY_TOPICS}:</span></td>
			<td valign="middle" nowrap="nowrap">&nbsp;{S_SELECT_TOPIC_DAYS}&nbsp;<input type="submit" class="liteoption jumpbox" value="{L_GO}" name="submit" /></td>
			<td valign="middle" align="right" width="100%"><span class="genmed">{S_TIMEZONE}&nbsp;</span></td>
		</tr>
		</table>
	</td>
</tr>
<!-- IF S_SHOW_ALPHA_BAR -->
<tr>
	<td class="cat" align="center" valign="middle" colspan="5">
	<span class="gensmall">{L_SORT_TOPICS}:{DIVIDER}<a href="{U_NEWEST}">{L_SORT_TOPICS_NEWEST}</a>{DIVIDER}<a href="{U_OLDEST}">{L_SORT_TOPICS_OLDEST}</a>{DIVIDER}<a href="{U_AZ}">A-Z</a>{DIVIDER}<a href="{U_ZA}" >Z-A</a>{DIVIDER}<!-- BEGIN alphabetical_sort --><a href="{alphabetical_sort.U_LETTER}" >{alphabetical_sort.LETTER}</a>{alphabetical_sort.DIVIDER}<!-- END alphabetical_sort -->{DIVIDER}</span>
	</td>
</tr>
<!-- ENDIF -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td align="left" valign="top" nowrap="nowrap"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a><br /></td>
	<td width="40%" align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /></td>
</tr>
</table>