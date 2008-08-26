<!-- BEGIN switch_search -->
<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}" class="nav">{L_ALBUM}</a>{NAV_SEP}<a href="{U_ALBUM_SEARCH}" class="nav-current">{L_SEARCH}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="right" valign="middle">{ALBUM_SEARCH_BOX}</td></tr></table>
<!-- BEGIN switch_search_results -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_MATCHES}&nbsp;{L_NRESULTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<th nowrap="nowrap">&nbsp;</th>
		<th nowrap="nowrap" width="25%">{L_TCATEGORY}</th>
		<th nowrap="nowrap" width="32%">{L_TTITLE}</th>
		<th nowrap="nowrap" width="18%">{L_TSUBMITER}</th>
		<th nowrap="nowrap" width="20%">{L_TSUBMITED}</th>
	</tr>

	<!-- BEGIN search_results -->
	<tr>
	<td class="row1h row-center" align="center" valign="middle">
		<center>
		<div class="picshadow"><div class="picframe">
		<a href="{switch_search_results.search_results.U_PIC}" class="genmed"><img src="{switch_search_results.search_results.THUMBNAIL}" {THUMB_SIZE} alt="{switch_search_results.search_results.DESC}" border="0" {switch_search_results.search_results.PIC_PREVIEW} /></a>
		</div></div>
		</center>
	</td>
	<td class="row1h row-forum" onclick="window.location.href='{switch_search_results.search_results.U_CAT}'"><a href="{switch_search_results.search_results.U_CAT}" class="genmed"><b>{switch_search_results.search_results.L_CAT}</b></a></td>
	<td class="row1"><a href="{switch_search_results.search_results.U_PIC}" class="genmed">{switch_search_results.search_results.L_PIC}</a></td>
	<td class="row1 row-center"><a href="{switch_search_results.search_results.U_PROFILE}" class="genmed"><b>{switch_search_results.search_results.L_USERNAME}</b></a></td>
	<td class="row1 row-center"><span class="gensmall">{switch_search_results.search_results.L_TIME}</span></td>
	</tr>
	<!-- END search_results -->

	<tr>
	<td class="catBottom" colspan="7" height="28" valign="middle">&nbsp; </td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr><td align="right"><span class="gensmall">{S_TIMEZONE}</span></td></tr>
</table>
<!-- END switch_search_results -->
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}