<!-- IF S_POPUP -->
<!-- INCLUDE simple_header.tpl -->
<!-- ELSE -->
<!-- INCLUDE overall_header.tpl -->
<!-- ENDIF -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PAGE_TITLE}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th width="30">#</th>
	<th>{L_USERNAME}</th>
	<th>{L_CONTACTS}</th>
	<th>{L_FROM}</th>
	<th>{L_JOINED}</th>
	<!-- IF not S_POSTS_LIKES --><th class="tw50px">{L_VIEWS_COUNT}</th><!-- ENDIF -->
	<th>{L_LAST_VIEWED}</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="row1 row-center">{memberrow.ROW_NUMBER}</td>
	<td class="row1" style="padding-left: 2px;">{memberrow.USERNAME}</td>
	<td class="row1 row-center-small">{memberrow.ONLINE_STATUS_IMG}&nbsp;{memberrow.PM_IMG}&nbsp;{memberrow.EMAIL_IMG}&nbsp;{memberrow.WWW_IMG}</td>
	<td class="row1 row-center-small">{memberrow.FROM}</td>
	<td class="row1 row-center-small">{memberrow.JOINED}</td>
	<!-- IF not S_POSTS_LIKES --><td class="row1 row-center">{memberrow.VIEWS_COUNT}</td><!-- ENDIF -->
	<td class="row1 row-center-small">{memberrow.LAST_VIEWED}</td>
</tr>
<!-- END memberrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table>
<tr>
	<td><span class="pagination">{PAGINATION}</span></td>
	<td class="tdalignr tdnw">
		<form method="post" action="{S_MODE_ACTION}">
			<span class="genmed">
				{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
				<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption jumpbox" />
			</span>
		</form>
	</td>
</tr>
<tr>
	<td><span class="gen">{PAGE_NUMBER}</span></td>
	<td class="tdalignr">{JUMPBOX}</td>
</tr>
</table>

<!-- IF S_POPUP -->
<br /><br />
<div class="genmed" style="margin: 0 auto; text-align: center;"><a href="javascript:window.close();">{L_CLOSE_WINDOW}</a></div>
<!-- INCLUDE simple_footer.tpl -->
<!-- ELSE -->
<!-- INCLUDE overall_footer.tpl -->
<!-- ENDIF -->