<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOP_RATED}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw50px">{L_RATING}</th>
	<th colspan="2">{L_TOPICS}</th>
</tr>
<!-- BEGIN hnotopics -->
	<td class="row1" align="left" valign="top"><span class="gensmall">{hnotopics.MESSAGE}</span></td>
<!-- END hnotopics -->
<!-- BEGIN hratingrow -->
<tr>
	<td class="row2 row-center-small"><b>{hratingrow.RATING}</b></td>
	<td class="row1h{topicrow.LINK_CLASS} row-forum tw100pct"><span class="topiclink{topicrow.LINK_CLASS}"><a href="{hratingrow.URL}">{hratingrow.TITLE}</a></span></td>
</tr>
<!-- END hratingrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- INCLUDE overall_footer.tpl -->