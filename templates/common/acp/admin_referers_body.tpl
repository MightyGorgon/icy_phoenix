<script type="text/javascript">
<!--
function toggle_check_all()
{
	for(var i = 0; i < document.refersrow_values.elements.length; i++)
	{
		var checkbox_element = document.refersrow_values.elements[i];
		if((checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox'))
		{
			checkbox_element.checked = document.refersrow_values.check_all_box.checked;
		}
	}
}
-->
</script>

<h1>{L_TITLE}</h1>
<br /><br />

<form method="post" action="{S_DELETE_ACTION}" name="ref_kill_form">
<div class="genmed" style="text-align: right; margin-top: 5px; margin-bottom: 5px;">
	{L_REFERER_KILL}&nbsp;&nbsp;<input type="text" name="ref_kill_text" value="" class="post" />&nbsp;&nbsp;{S_SMART_DELETE_SELECT}&nbsp;&nbsp;<input type="submit" name="ref_kill" value="{L_REFERER_DELETE}" class="mainoption" />
</div>
</form>


<form method="post" action="{S_MODE_ACTION}" name="refersrow_values">
<table class="forumline">
<tr><th colspan="9">{L_REFERERS}</th></tr>
<tr>
	<th nowrap="nowrap">#</th>
	<th nowrap="nowrap">{L_HOST}</th>
	<th nowrap="nowrap">{L_URL}</th>
	<th nowrap="nowrap">{L_REFERER_T_URL}</th>
	<th nowrap="nowrap">{L_HITS}</th>
	<th nowrap="nowrap">{L_FIRST}</th>
	<th nowrap="nowrap">{L_LAST}</th>
	<th nowrap="nowrap">{L_IP}</th>
	<th class="tw1pct tdnw"><input type="checkbox" name="check_all_box" onclick="toggle_check_all()" /></th>
</tr>
<!-- BEGIN refersrow -->
<tr>
	<td class="row1 row-center"><span class="gensmall">{refersrow.ID}</span></td>
	<td class="row1" ><span class="gensmall">{refersrow.HOST}</span></td>
	<td class="row1" ><span class="gensmall">{refersrow.URL}</span></td>
	<td class="row1" ><span class="gensmall"><!-- IF refersrow.T_URL -->{refersrow.T_URL}<!-- ELSE -->&nbsp;<!-- ENDIF --></span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.HITS}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.FIRST}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.LAST}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.IP}</span></td>
	<td class="row1 row-center"><span class="gensmall"><input type="checkbox" name="delete_id_{refersrow.REFER_ID}" /></span></td>
</tr>
<!-- END refersrow -->
<tr>
	<td class="cat" colspan="9" height="28">
		<input type="submit" name="delete_sub" value="{L_DELETE}" class="liteoption" />
		<input type="submit" name="clear" value="{L_CLEAR}" class="liteoption" />
	</td>
</tr>
</table>
</form>

<form method="post" action="{S_MODE_ACTION}">
<table>
<tr>
	<td align="left"><span class="pagination">{PAGINATION}</span></td>
	<td align="right" valign="top" nowrap="nowrap">
		<form method="post" action="{S_MODE_ACTION}">
		<span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;{L_REFERER_GROUP_BY}:&nbsp;{S_GROUP_BY_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></span>
		</form>
	</td>
</tr>
<tr>
	<td align="left"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right">{JUMPBOX}</td>
</tr>
</table>
