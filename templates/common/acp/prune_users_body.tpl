<script type="text/javascript">
<!--
function SetDays()
{
	document.DaysFrm.submit()
}
// -->
</script>

<h1>{L_PRUNE_USERS}</h1>
<p>{L_PRUNE_USERS_EXPLAIN}</p>

<form name="DaysFrm" action="{S_PRUNE_USERS}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th><b>{L_DAYS}</b></th>
	<th nowrap="nowrap"><b>{L_PRUNE_ACTION}</b></th>
	<th><b>{L_PRUNE_LIST}</b></th>
</tr>
<!-- BEGIN prune_list -->
<tr>
	<td class="row1">{prune_list.S_DAYS}</td>
	<td class="row2">({prune_list.USER_COUNT})<br/>{prune_list.U_PRUNE}<br/>{prune_list.L_PRUNE_EXPLAIN}</td>
	<td class="row3">{prune_list.LIST}</td>
</tr>
<!-- END prune_list -->
</table>
<input type="hidden" name="confirm" value="YES" />
</form>