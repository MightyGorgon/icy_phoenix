<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th>{L_TOPICS}&nbsp;{L_FOR_FORUM}</th>
	<th class="tw20pct">{L_RATES}</th>
	<th width="20%" >{L_RATING}</th>
	<th class="tw20pct">{L_MIN}</th>
	<th class="tw20pct">{L_MAX}</th>
</tr>
<!-- BEGIN notopics -->
<tr><td class="row1 row-center tvalignm" colspan="6"><span class="postdetails"><br /><br />{notopics.MESSAGE}<br /><br /></span></td></tr>
<!-- END notopics -->
<!-- BEGIN topicrow -->
<tr>
	<td class="row1 tw100pct">
		<table>
		<tr>
			<td><span class="topiclink"><a href="{topicrow.URL}" class="topiclink">{topicrow.TITLE}</a></span></td>
			<td class="tdalignr"><span class="nav">{topicrow.L_VIEW_DETAILS}</span></td>
		</tr>
		</table>
	</td>
	<td class="row3 row-center tvalignm"><span class="postdetails">{topicrow.NUMBER_OF_RATES}</span></td>
	<td class="row2 row-center tvalignm"><span class="name">{topicrow.RATING}</span></td>
	<td class="row3 row-center tvalignm"><span class="postdetails">{topicrow.MIN}</span></td>
	<td class="row2 row-center tvalignm"><span class="postdetails">{topicrow.MAX}</span></td>
</tr>
<!-- END topicrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<form method="post" action="{S_MODE_ACTION}">
<table>
<tr><td class="tdalignr"><span class="genmed"><b>{L_BY_FORUM}</b>&nbsp;&nbsp;{S_FORUMS}&nbsp;&nbsp;{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_VIEW}" class="mainoption" /></span></td></tr>
</table>
</form>

<!-- Do not remove this copyright notice -->
{NIVISEC_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->