{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{REQUEST_URI}" class="nav-current">{PAGE_NAME}</a>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th align="center">{L_TOPICS}&nbsp;{L_FOR_FORUM}</th>
	<th width="20%">{L_RATES}</th>
	<th width="20%" >{L_RATING}</th>
	<th width="20%">{L_MIN}</th>
	<th width="20%">{L_MAX}</th>
</tr>
<!-- BEGIN notopics -->
<tr><td class="row3 row-center" valign="middle" colspan="6"><span class="postdetails"><br /><br />{notopics.MESSAGE}<br /><br /></span></td></tr>
<!-- END notopics -->
<!-- BEGIN topicrow -->
<tr>
	<td class="row1" width="100%">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="left">
					<span class="topictitle">
						<a href="{topicrow.URL}" class="topictitle">{topicrow.TITLE}</a>
					</span>
				</td>
				<td align="right">
					<span class="nav">{topicrow.L_VIEW_DETAILS}</span>
				</td>
			</tr>
		</table>
	</td>
	<td class="row3 row-center" valign="middle"><span class="postdetails">{topicrow.NUMBER_OF_RATES}</span></td>
	<td class="row2 row-center" valign="middle"><span class="name">{topicrow.RATING}</span></td>
	<td class="row3 row-center" valign="middle"><span class="postdetails">{topicrow.MIN}</span></td>
	<td class="row2 row-center" valign="middle"><span class="postdetails">{topicrow.MAX}</span></td>
</tr>
<!-- END topicrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<form method="post" action="{S_MODE_ACTION}">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right"><span class="gen">{L_BY_FORUM}&nbsp;&nbsp;{S_FORUMS}&nbsp;&nbsp;{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_VIEW}" class="lightoption" /></span></td></tr>
</table>
</form>