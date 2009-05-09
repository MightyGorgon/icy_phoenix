<h2 style="text-align: left;"><a href="{U_VIEW_FORUM}" style="text-decoration: none;">{FORUM_NAME}</a></h2>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="bottom" width="70%">
		<!-- IF not S_BOT -->
		<span class="gensmall">{TOTAL_USERS_ONLINE}&nbsp;</span><br /><span class="gensmall">{LOGGED_IN_USER_LIST}&nbsp;</span><br /><span class="gensmall">{BOT_LIST}&nbsp;</span><br />
		<span class="img-btn"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></span>
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
	<td align="right" valign="bottom">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span><br />
		<!-- IF S_EXTENDED_PAGINATION -->
		<div style="margin-top: 3px;"><form method="post" action="{U_VIEW_FORUM}"><span class="gen"><b>{L_GO_TO_PAGE_NUMBER}</b>&nbsp;<input type="text" name="page_number" value="" size="3" class="post" />&nbsp;&nbsp;<input type="submit" name="submit" value="{L_GO}" class="mainoption" /></span></form></div>
		<!-- ENDIF -->
		<!-- IF not S_BOT --><div style="margin-top: 3px;"><form action="{FULL_SITE_PATH}{U_SEARCH}" method="post"><input name="search_keywords" type="text" class="post search" style="width: 160px;" value="{L_SEARCH_THIS_FORUM}" onclick="if(this.value=='{L_SEARCH_THIS_FORUM}')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH_THIS_FORUM}';" /><input type="hidden" name="search_where" value="{FORUM_ID_FULL}" />&nbsp;<input type="submit" class="mainoption" value="{L_SEARCH}" /></form></div><!-- ENDIF -->
	</td>
</tr>
</table>
{BOARD_INDEX}

<form method="post" action="{S_POST_DAYS_ACTION}" style="display:inline;">
{IMG_THL}{IMG_THC}<a href="{U_VIEW_FORUM}" class="forumlink">{FORUM_NAME}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- IF VIEWFORUM_BANNER_TOP -->
<tr><td class="row3 row-center" colspan="5">{VIEWFORUM_BANNER_TOP}</td></tr>
<!-- ENDIF -->
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
	<th style="max-width: 100px;">{L_RATING}</th>
	<!-- END rating_switch -->
</tr>
<!-- BEGIN topicrow -->
<tr>
	<td class="row1h{topicrow.CLASS_NEW} row-forum" width="100%" onclick="window.location.href='{topicrow.U_VIEW_TOPIC}'" colspan="2">
		<div class="topic-title-hide-flow"><span class="topiclink{topicrow.CLASS_NEW}">{topicrow.NEWEST_POST_IMG}{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE_ICON}<a href="{topicrow.U_VIEW_TOPIC}" class="{topicrow.TOPIC_CLASS}">{topicrow.TOPIC_TITLE}</a></span></div>
		<!-- BEGIN switch_topic_desc -->
		<span class="gensmall">&nbsp;{topicrow.switch_topic_desc.TOPIC_DESCRIPTION}</span><br />
		<!-- END switch_topic_desc -->
		<span class="gotopage">{topicrow.GOTO_PAGE}</span>
	</td>
	<td class="row3 row-center-small" style="padding-left: 2px; padding-right: 2px;" nowrap="nowrap">{topicrow.TOPIC_AUTHOR}</td>
	<td class="row3 row-center-small" style="padding-top: 0;" nowrap="nowrap">{topicrow.LAST_POST_TIME}</td>
	<!-- BEGIN rate_switch_msg -->
	<td class="row3 row-center-small"><img src="images/rates/rate_{topicrow.TOPIC_RATING}.png"></td>
	<!-- END rate_switch_msg -->
</tr>
<!-- IF not S_BOT -->
<!-- BEGIN switch_viewforum_banner -->
<tr><td class="row1h-new row-forum" width="100%" colspan="5">{VIEWFORUM_BANNER_CODE}</td></tr>
<!-- END switch_viewforum_banner -->
<!-- ENDIF -->
<!-- END topicrow -->
<!-- BEGIN switch_no_topics -->
<tr><td class="row1 row-center" colspan="5" height="30"><span class="gen">{L_NO_TOPICS}</span></td></tr>
<!-- END switch_no_topics -->
<!-- IF VIEWFORUM_BANNER_BOTTOM -->
<tr><td class="row3 row-center" colspan="5">{VIEWFORUM_BANNER_BOTTOM}</td></tr>
<!-- ENDIF -->
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
	<td align="left" valign="top" nowrap="nowrap"><span class="img-btn"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></span><br /></td>
	<td width="40%" align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /></td>
</tr>
</table>