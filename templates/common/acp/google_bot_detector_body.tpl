<h1>{L_DETECTOR_TITLE}</h1>
<form action="{S_ACTION}" method="post">
<p>{L_DETECTOR_EXPLAIN}</p>
<br /><br />

<!-- BEGIN page -->
<table>
<tr>
	<td class="pagination" width="30%">{page.PAGE_NUMBER}</td>
	<td align="right" class="pagination">{page.PAGINATION}</td>
</tr>
</table>
<!-- END page -->

<table class="forumline">
<tr>
	<th class="tw10pct">{L_DETECTOR_ID}</th>
	<th class="tw30pct">{L_DETECTOR_TIME}</th>
	<th>{L_DETECTOR_URL}</th>
</tr>
<!-- BEGIN detector -->
<tr>
	<td class="row1 tdalignr">{detector.ID}</td>
	<td class="row2">{detector.TIME}</td>
	<td class="row1">{detector.URL}</td>
</tr>
<!-- END detector -->
<!-- BEGIN nobot -->
<tr><td class="row1 row-center" colspan="3">{nobot.L_EXPLAIN}</td></tr>
<!-- END nobot -->
<tr><td class="cat tdalignc" colspan="3"><input type="submit" name="clear" value="{L_CLEAR}" class="mainoption" /></td></tr>
</table>
</form>

<br class="clear" />
