<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_MATCHES}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw4pct tdnw">&nbsp;</th>
	<th class="tdnw">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_FILE}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_SUBMITER}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_DATE}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_DOWNLOADS}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_RATE}&nbsp;</th>
</tr>

<!-- BEGIN searchresults -->
<tr>
	<td class="row1 row-center"><a href="{searchresults.U_FILE}" class="topiclink">{searchresults.PIN_IMAGE}</a></td>
	<td class="row1h{searchresults.XS_NEW} row-forum" data-href="{searchresults.U_CAT}"><span class="forumlink"><a href="{searchresults.U_CAT}" class="forumlink{searchresults.XS_NEW}">{searchresults.CAT_NAME}</a></span>&nbsp;</td>
	<td class="row1h{searchresults.XS_NEW} row-forum" data-href="{searchresults.U_FILE}"><span class="forumlink"><a href="{searchresults.U_FILE}" class="forumlink{searchresults.XS_NEW}">{searchresults.FILE_NAME}</a></span>&nbsp;
	<br /><span class="genmed">{searchresults.FILE_DESC}</span>
	</td>
	<td class="row1 row-center tvalignm"><span class="name">{searchresults.FILE_SUBMITER}</span></td>
	<td class="row2 row-center tvalignm"><span class="postdetails">{searchresults.DATE}</span></td>
	<td class="row1 row-center tvalignm"><span class="postdetails">{searchresults.DOWNLOADS}</span></td>
	<td class="row2 row-center tdnw"><span class="postdetails"><img src="images/rates/rate_{searchresults.RATING}.png" alt="" /></td>
</tr>
<!-- END searchresults -->
<tr><td class="cat" colspan="7">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr tdnw"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- INCLUDE pa_footer.tpl -->