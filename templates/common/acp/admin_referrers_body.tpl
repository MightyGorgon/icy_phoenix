<script type="text/javascript">
<!--
function toggle_check_all()
{
	for( var i=0; i < document.refersrow_values.elements.length; i++ )
	{
		var checkbox_element = document.refersrow_values.elements[i];
		if( (checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox') )
		{
			checkbox_element.checked = document.refersrow_values.check_all_box.checked;
		}
	}
}
-->
</script>

<h1>{L_TITLE}</h1>
<form method="post" action="{S_MODE_ACTION}" name="refersrow_values">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="8">{L_REFERRERS}</th></tr>
<tr>
	<th nowrap="nowrap">#</th>
	<th nowrap="nowrap">{L_HOST}</th>
	<th nowrap="nowrap">{L_URL}</th>
	<th nowrap="nowrap">{L_HITS}</th>
	<th nowrap="nowrap">{L_FIRST}</th>
	<th nowrap="nowrap">{L_LAST}</th>
	<th nowrap="nowrap">{L_IP}</th>
	<th width="1" nowrap="nowrap"><input type="checkbox" name="check_all_box" onClick="toggle_check_all()" /></th>
</tr>
<!-- BEGIN refersrow -->
<tr>
	<td class="row1 row-center"><span class="gen">{refersrow.ID}</span></td>
	<td class="row1" ><span class="gen">{refersrow.HOST}</span></td>
	<td class="row1" ><span class="gen">{refersrow.URL}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.HITS}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.FIRST}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.LAST}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.IP}</span></td>
	<td class="row1 row-center"><span class="gensmall"><input type="checkbox" name="delete_id_{refersrow.REFER_ID}" /></span></td>
</tr>
<!-- END refersrow -->
<tr>
	<td class="cat" colspan="8" height="28">
		<input type="submit" name="delete_sub" value="{L_DELETE}" class="liteoption" />
		<input type="submit" name="clear" value="{L_CLEAR}" class="liteoption" />
		<!-- <form action="{S_ACTION}" method="post"><input type="submit" name="clear" value="{L_CLEAR}" class="liteoption" /></form> -->
	</td>
</tr>
</table>
</form>

<form method="post" action="{S_MODE_ACTION}">
<table width="100%" cellspacing="0" class="empty-table">
<tr>
	<td align="left"><span class="pagination">{PAGINATION}</span></td>
	<td align="right" valign="top" nowrap="nowrap">
		<form method="post" action="{S_MODE_ACTION}">
		<span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption jumpbox" /></span>
		</form>
	</td>
</tr>
<tr>
	<td align="left"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>
<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2"><tr><td align="right" valign="top">&nbsp;</td></tr></table>
