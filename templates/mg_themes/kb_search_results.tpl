{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_KB}">{L_KB}</a>{NAV_SEP}<a href="#" class="nav-current">{L_SEARCH_MATCHES}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_MATCHES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_TYPE}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
</tr>
<!-- BEGIN searchresults -->
<tr>
	<td class="row1">
		<span class="forumlink"><a href="{searchresults.U_VIEW_ARTICLE}" class="forumlink">{searchresults.ARTICLE_TITLE}</a></span><br />
		<span class="gen">{searchresults.ARTICLE_DESCRIPTION}</span>
	</td>
	<td class="row2 row-center" valign="middle"><span class="genmed">{searchresults.ARTICLE_AUTHOR}</span></td>
	<td class="row3 row-center" valign="middle"><span class="genmed">{searchresults.ARTICLE_TYPE}</span></td>
	<td class="row2 row-center" valign="middle"><span class="gen">{searchresults.ARTICLE_CATEGORY}</span></td>
</tr>
<!-- END searchresults -->
<tr><td class="cat" colspan="7">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>
