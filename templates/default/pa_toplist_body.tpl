<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOPLIST}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1 row-center" colspan="7"><span class="gen"><b>{L_CURRENT_TOPLIST} - {L_NEW_FILES}</b></span></td></tr>
<tr>
	<td colspan="7" class="row2 row-center"><span class="genmed"><a href="{U_NEWEST_FILE}" class="genmed">{L_NEWEST_FILE}</a> | <a href="{U_MOST_POPULAR}" class="genmed">{L_MOST_POPULAR}</a> | <a href="{U_TOP_RATED}" class="genmed">{L_TOP_RATED}</a></span></td>
</tr>
<!-- IF IS_NEWEST -->
<tr>
	<td class="row1 row-center" colspan="7">
		<span class="gen">
			<b>{L_TOTAL_NEW_FILE}:</b> {L_LAST_WEEK} ( {TOTAL_FILE_WEEK} ) | {L_LAST_30_DAYS} ( {TOTAL_FILE_MONTH} )<br />
			<b>{L_SHOW}:</b> <a href="{U_ONE_WEEK}" class="gen">{L_ONE_WEEK}</a> - <a href="{U_TWO_WEEK}" class="gen">{L_TWO_WEEK}</a> - <a href="{U_30_DAYS}" class="gen">{L_30_DAYS}</a>
		</span>
	</td>
</tr>
<!-- IF FILE_DATE -->
<tr>
	<td class="row1 row-center" colspan="7">
	<span class="gen">
	<!-- BEGIN files_date -->
	<strong><big>&middot;</big></strong> <a href="{files_date.U_DATES}">{files_date.DATES}</a>&nbsp;({files_date.TOTAL_DOWNLOADS})<br />
	<!-- END files_date -->
	</span>
	</td>
</tr>
<!-- ENDIF -->

<!-- ENDIF -->
<!-- IF IS_POPULAR -->
<tr>
	<td class="row1 row-center" colspan="7">
	<span class="genmed">
	<b>{L_SHOW_TOP}:</b> [ <a href="{U_TOP_10}" class="genmed">10</a> - <a href="{U_TOP_25}" class="genmed">25</a> - <a href="{U_TOP_50}" class="genmed">50</a> ]
	<b>{L_OR_TOP}:</b> [ <a href="{U_TOP_PER_1}" class="genmed">1%</a> - <a href="{U_TOP_PER_5}" class="genmed">5%</a> - <a href="{U_TOP_PER_10}" class="genmed">10%</a> ]
	</span>
	</td>
</tr>

<!-- ENDIF -->

<!-- IF FILE_LIST -->
<tr>
<th class="tw35pct tdnw" colspan="2"><span class="cattitle">&nbsp;{L_CATEGORY}&nbsp;</span></td>
<th class="tdnw"><span class="cattitle">&nbsp;{L_FILE}&nbsp;</span></td>
<th class="tdnw"><span class="cattitle">&nbsp;{L_SUBMITER}&nbsp;</span></td>
<th class="tdnw"><span class="cattitle">&nbsp;{L_DATE}&nbsp;</span></td>
<th class="tdnw"><span class="cattitle">&nbsp;{L_DOWNLOADS}&nbsp;</span></td>
<th class="tdnw"><span class="cattitle">&nbsp;{L_RATE}&nbsp;</span></td>
</tr>
	<!-- BEGIN files_row -->
<tr>
	<td class="row1 row-center tw30px"><a href="{files_row.U_FILE}" class="topiclink">{files_row.PIN_IMAGE}</a></td>
	<td class="row1h{files_row.XS_NEW} row-forum tw35pct" data-href="{files_row.U_CAT}"><span class="forumlink"><a href="{files_row.U_CAT}" class="forumlink{files_row.XS_NEW}">{files_row.CAT_NAME}</a></span>&nbsp;</td>

	<td class="row1h{files_row.XS_NEW} row-forum tw60pct" data-href="{files_row.U_FILE}"><span class="forumlink"><a href="{files_row.U_FILE}" class="forumlink{files_row.XS_NEW}">{files_row.FILE_NAME}</a></span>&nbsp;
	<!-- IF file_rows.IS_NEW_FILE -->
	<!-- <img src="{file_rows.FILE_NEW_IMAGE}" alt="{L_NEW_FILE}" /> -->
	<!-- ENDIF -->
	<br /><span class="genmed">{files_row.FILE_DESC}</span></td>
	<td class="row1 row-center"><span class="name">{files_row.FILE_SUBMITER}</span></td>
	<td class="row2 row-center tdnw"><span class="postdetails">{files_row.DATE}</td>
	<td class="row2 row-center"><span class="postdetails">{files_row.DOWNLOADS}</td>
	<td class="row2 row-center tdnw"><span class="postdetails"><img src="images/rates/rate_{files_row.RATING}.png" alt="" /></td>
</tr>
	<!-- END files_row -->
<tr><td class="cat tdalignc" colspan="7">&nbsp;</td></tr>
<!-- ENDIF -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- INCLUDE pa_footer.tpl -->
