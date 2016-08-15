<!-- INCLUDE overall_header.tpl -->

<!-- IF not IS_USER_RECENT -->
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header"><a href="#" class="nav-current">{STATUS}</a></p>
	<div class="nav-links">
		<form name="form" method="get" action="{FORM_ACTION}">
			{L_SELECT_MODE}
			[ <a href="{U_RECENT_TODAY}" class="mainmenu">{L_TODAY}</a> ]
			[ <a href="{U_RECENT_YESTERDAY}" class="mainmenu">{L_YESTERDAY}</a> ]
			[ <a href="{U_RECENT_LAST24}" class="mainmenu">{L_LAST24}</a> ]
			[ <a href="{U_RECENT_LASTWEEK}" class="mainmenu">{L_LASTWEEK}</a> ]
			[ <a href="javascript:document.form.submit();" class="mainmenu">{L_LAST}</a> <input type="hidden" name="mode" value="lastXdays" />
			<input type="text" name="amount_days" size="2" value="{AMOUNT_DAYS}" maxlength="3" class="post" />
			<a href="javascript:document.form.submit();" class="mainmenu">{L_DAYS}</a> ]
		</form>
	</div>
</div>{IMG_TBR}
<!-- ENDIF -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_RECENT_TITLE}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th>&nbsp;</th>
	<th><a href="{U_SORT_CAT}">{L_FORUM}</a></th>
	<th>{L_TOPICS}</th>
	<th>{L_AUTHOR}</th>
	<th>{L_VIEWS}</th>
	<th>{L_REPLIES}</th>
	<th><a href="{U_SORT_TIME}">{L_LASTPOST}</a></th>
</tr>
<!-- BEGIN recent -->
<tr>
	<td class="row1 row-center tw22px"><img src="{recent.TOPIC_FOLDER_IMG}" alt="{recent.L_TOPIC_FOLDER_ALT}" title="{recent.L_TOPIC_FOLDER_ALT}" style="margin-right: 4px;" /></td>
	<td class="row1h{recent.CLASS_NEW} row-forum" data-href="{recent.U_VIEW_FORUM}">
		<span class="topiclink{recent.CLASS_NEW}"><a href="{recent.U_VIEW_FORUM}">{recent.FORUM_NAME}</a></span>
	</td>
	<td class="row1h{recent.CLASS_NEW} row-forum" data-href="{recent.U_VIEW_TOPIC}">
		<div class="topic-title-hide-flow"><div style="float: right; display: inline; vertical-align: top; margin-top: 0px !important; padding-top: 0px !important; padding-right: 3px;">{recent.TOPIC_ATTACHMENT_IMG}{recent.TOPIC_TYPE_ICON}</div>{recent.NEWEST_POST_IMG}<span class="topiclink{recent.CLASS_NEW}"><a href="{recent.U_VIEW_TOPIC}" class="{recent.TOPIC_CLASS}">{recent.TOPIC_TITLE}</a><!-- BEGIN display_reg --> [{recent.REG_OPTIONS}]&nbsp;{recent.REG_USER_OWN_REG}<!-- END display_reg --></span></div>
		{recent.GOTO_PAGE_FULL}
	</td>
	<td class="row3 row-center-small tdnw" style="padding-top: 0px; padding-left: 2px; padding-right: 2px;">{recent.FIRST_POST_TIME}<br />{recent.FIRST_AUTHOR}</td>
	<td class="row2 row-center-small">{recent.VIEWS}</td>
	<td class="row2 row-center-small">{recent.REPLIES}</td>
	<td class="row3 row-center-small" style="padding-top: 0px; padding-left: 2px; padding-right: 2px;" nowrap="nowrap">{recent.LAST_POST_TIME}<br />{recent.LAST_AUTHOR}&nbsp;{recent.LAST_URL}</td>
</tr>
<!-- END recent -->
<!-- BEGIN switch_no_topics -->
<tr><td class="row1 row-center" colspan="7"><span class="gen"><i>{L_NO_TOPICS}</i></span></td></tr>
<!-- END switch_no_topics -->
<tr><td class="cat" colspan="7">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr tdnw"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->