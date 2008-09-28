{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_DL_INDEX}">{L_DOWNLOADS}</a>{NAV_SEP}<a href="#" class="nav-current">{PAGE_NAME}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

<form action="{U_DOWNLOADS_ADV}" method="post" name="dl_mod">
{IMG_THL}{IMG_THC}<span class="forumlink">{PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">{L_STATUS}</th>
	<th nowrap="nowrap" width="100%" colspan="2">{L_NAME}</th>
	<th nowrap="nowrap">{L_SIZE}</th>
	<th nowrap="nowrap">{L_KLICKS}<br />{L_OVERALL_KLICKS}</th>
	<th nowrap="nowrap">{L_RATING}</th>
</tr>
<!-- BEGIN download -->
<tr>
	<td class="{download.ROW_CLASS} row-center">{download.STATUS}</td>
	<td class="{download.ROW_CLASS}" align="left"><a href="{download.U_DL_LINK}" class="nav">{download.DESCRIPTION}</a>&nbsp;<span class="genmed">{download.HACK_VERSION}</span></td>
	<td class="{download.ROW_CLASS} row-center"><a href="{download.U_CAT_VIEW}" class="gensmall">{download.CAT_NAME}</a></td>
	<td class="{download.ROW_CLASS}" align="right"><span class="genmed">{download.FILE_SIZE}</span></td>
	<td class="{download.ROW_CLASS} row-center"><span class="genmed">{download.FILE_KLICKS} / {download.FILE_OVERALL_KLICKS}</span></td>
	<td class="{download.ROW_CLASS}" nowrap="nowrap">
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" nowrap="nowrap">{download.RATING_IMG}</td>
		<td align="right" nowrap="nowrap"><span class="postdetails">{download.RATINGS}</span></td>
	</tr>
	<tr><td colspan="2" align="center" nowrap="nowrap"><span class="postdetails">&nbsp;{download.U_RATING}&nbsp;</span></td></tr>
	</table>
	</td>
</tr>
<!-- END download -->
<tr>
	<td align="center" class="cat" colspan="6">&nbsp;
		<!-- BEGIN sort_options -->
		<span class="genmed">{L_SORT_BY}&nbsp;{S_SORT_BY}&nbsp;&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER}</span>
		<input type="submit" class="liteoption" value="{L_GO}" />
		<!-- END sort_options -->
	&nbsp;</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right"><span class="pagination">{PAGINATION}</span></td></tr>
</table>
<br />