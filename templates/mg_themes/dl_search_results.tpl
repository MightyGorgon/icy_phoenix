{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_NAV1}">{L_NAV1}</a>{NAV_SEP}<a href="{U_NAV2}">{L_NAV2}</a>{NAV_SEP}<a href="#" class="nav-current">{L_SEARCH_TITLE}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">&nbsp;{L_STATUS}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_DESCRIPTION}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_FILENAME}&nbsp;</th>
</tr>
<tr>
	<td class="row3">&nbsp;</td>
	<td class="row3" colspan="3">&nbsp;<b>{L_LONG_DESC}</b>&nbsp;</td>
</tr>
<!-- BEGIN searchresults -->
<tr>
	<td class="{searchresults.ROW_CLASS} row-center" valign="middle">{searchresults.STATUS}</td>
	<td class="{searchresults.ROW_CLASS}"><span class="forumlink"><a href="{searchresults.U_CAT_LINK}" class="forumlink">{searchresults.CAT_NAME}</a></span></td>
	<td class="{searchresults.ROW_CLASS}"><span class="topictitle">{searchresults.MINI_ICON}<a href="{searchresults.U_FILE_LINK}" class="topictitle">{searchresults.DESCRIPTION}</span></td>
	<td class="{searchresults.ROW_CLASS}"><span class="name">{searchresults.FILE_NAME}</span></td>
</tr>
<tr>
	<td class="{searchresults.ROW_CLASS}">&nbsp;</td>
	<td class="{searchresults.ROW_CLASS}" colspan="3"><span class="postdetails">{searchresults.LONG_DESC}</span></td>
</tr>
<!-- END searchresults -->
<!-- BEGIN no_searchresults -->
<tr><td class="row1 row-center" valign="middle" colspan="4">{no_searchresults.L_NO_RESULTS}</td></tr>
<!-- END no_searchresults -->
<tr><td class="cat" align="center" valign="middle" colspan="4">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
<tr><td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td></tr>
</table>

<table width="100%" cellspacing="2" border="0" align="center">
<tr><td valign="top" align="right">{JUMPBOX}</td></tr>
</table>