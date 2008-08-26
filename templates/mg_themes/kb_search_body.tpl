<form action="{S_SEARCH_ACTION}" method="post">
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_KB}">{L_KB}</a>{NAV_SEP}<a href="#" class="nav-current">{L_SEARCH_QUERY}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="4">{L_SEARCH_QUERY}</th></tr>
<tr>
	<td class="row1" colspan="2" width="50%">
		<span class="gen">{L_SEARCH_KEYWORDS}:</span><br />
		<span class="gensmall">{L_SEARCH_KEYWORDS_EXPLAIN}</span>
	</td>
	<td class="row2" colspan="2" valign="top">
		<span class="genmed">
			<input type="text" style="width: 300px" class="post" name="search_keywords" size="30" /><br />
			<input type="radio" name="search_terms" value="any" checked="checked" /> {L_SEARCH_ANY_TERMS}<br />
			<input type="radio" name="search_terms" value="all" /> {L_SEARCH_ALL_TERMS}
		</span>
	</td>
</tr>
<tr><td class="catBottom" colspan="4">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{S_SEARCH}" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr><td align="right" valign="middle"><span class="gensmall">{S_TIMEZONE}</span></td></tr>
</table></form>
