<!-- INCLUDE overall_header.tpl -->

<!-- BEGIN switch_search_results -->
<table><tr><td align="right" valign="middle">{ALBUM_SEARCH_BOX}</td></tr></table>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_MATCHES}&nbsp;{L_TRESULTS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">&nbsp;</th>
	<th class="tw25pct tdnw">{L_TCATEGORY}</th>
	<th class="tw30pct tdnw">{L_TTITLE}</th>
	<th class="tw20pct tdnw">{L_TSUBMITER}</th>
	<th class="tw20pct tdnw">{L_TSUBMITED}</th>
</tr>

<!-- BEGIN search_results -->
<tr>
	<td class="row1h row-center tvalignm">
		<a href="{switch_search_results.search_results.U_PIC_DL}"{switch_search_results.search_results.PIC_PREVIEW_HS}><img class="picframe" src="{switch_search_results.search_results.THUMBNAIL}" {THUMB_SIZE} alt="{switch_search_results.search_results.PIC_TITLE}" /></a>
	</td>
	<td class="row1h row-forum" data-href="{switch_search_results.search_results.U_PIC_CAT}"><a href="{switch_search_results.search_results.U_PIC_CAT}" class="genmed"><b>{switch_search_results.search_results.CATEGORY}</b></a></td>
	<td class="row1"><a href="{switch_search_results.search_results.U_PIC_SP}" class="genmed">{switch_search_results.search_results.PIC_TITLE}</a></td>
	<td class="row1 row-center"><a href="{switch_search_results.search_results.U_PROFILE}" class="genmed"><b>{switch_search_results.search_results.L_USERNAME}</b></a></td>
	<td class="row1 row-center"><span class="gensmall">{switch_search_results.search_results.TIME}</span></td>
</tr>
<!-- END search_results -->

<tr><td class="catBottom tvalignm" colspan="7">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table>
<tr>
	<td><span class="gensmall">{S_TIMEZONE}</span></td>
	<td class="tdalignr">
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