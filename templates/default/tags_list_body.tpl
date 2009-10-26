<!-- INCLUDE overall_header.tpl -->

<br />
<h2 style="text-align: left;"><a href="{U_TAGS}" style="text-decoration: none;">{L_TOPIC_TAGS}</a></h2>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOPIC_TAGS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="30">#</th>
	<th>{L_TAG_TEXT}</th>
	<th>{L_TAG_COUNT}</th>
</tr>
<!-- BEGIN row -->
<tr class="{row.CLASS} {row.CLASS}h" style="background-image: none;">
	<td class="{row.CLASS} row-center" style="background: none;" nowrap="nowrap"><span class="genmed"><b>{row.ROW_NUMBER}</b></span></td>
	<td class="{row.CLASS}" style="background: none;" nowrap="nowrap"><span class="genmed"><a href="{row.U_TAG_TEXT}">{row.TAG_TEXT}</a></span></td>
	<td class="{row.CLASS} row-center" style="background: none;" nowrap="nowrap"><span class="genmed">{row.TAG_COUNT}</span></td>
</tr>
<!-- END row -->
<tr><td class="spaceRow" colspan="3"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="3">
		<form method="post" action="{S_MODE_ACTION}">
		<div class="genmed" style="text-align: right; margin-top: 5px; margin-bottom: 5px;">
			{L_SORT_ORDER}:&nbsp;{S_SORT_ORDER_SELECT}&nbsp;&nbsp;{L_SORT_DIR}:&nbsp;{S_SORT_DIR_SELECT}&nbsp;&nbsp;
			{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</div>
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<br clear="all" />
<br /><br />

<!-- INCLUDE overall_footer.tpl -->