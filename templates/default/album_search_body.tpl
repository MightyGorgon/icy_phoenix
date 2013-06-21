<!-- INCLUDE overall_header.tpl -->

<!-- BEGIN switch_search_results -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="right" valign="middle">{ALBUM_SEARCH_BOX}</td></tr></table>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_MATCHES}&nbsp;{L_TRESULTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
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
		<a href="{switch_search_results.search_results.U_PIC_DL}"{switch_search_results.search_results.PIC_PREVIEW_HS}><img src="{switch_search_results.search_results.THUMBNAIL}" {THUMB_SIZE} alt="{switch_search_results.search_results.PIC_TITLE}" border="0" /></a>
		</div></div>
		</center>
	</td>
	<td class="row1h row-forum" data-href="{switch_search_results.search_results.U_PIC_CAT}"><a href="{switch_search_results.search_results.U_PIC_CAT}" class="genmed"><b>{switch_search_results.search_results.CATEGORY}</b></a></td>
	<td class="row1"><a href="{switch_search_results.search_results.U_PIC_SP}" class="genmed">{switch_search_results.search_results.PIC_TITLE}</a></td>
	<td class="row1 row-center"><a href="{switch_search_results.search_results.U_PROFILE}" class="genmed"><b>{switch_search_results.search_results.L_USERNAME}</b></a></td>
	<td class="row1 row-center"><span class="gensmall">{switch_search_results.search_results.TIME}</span></td>
</tr>
<!-- END search_results -->

<tr><td class="catBottom" colspan="7" height="28" valign="middle">&nbsp; </td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="gensmall">{S_TIMEZONE}</span></td>
	<td align="right" valign="top">
		<!-- IF PAGINATION -->
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
</tr>
</table>
<!-- END switch_search_results -->
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->