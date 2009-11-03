<!-- INCLUDE ../common/lofi/bots/lofi_bots_header.tpl -->

<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_REFERRERS}" class="nav-current">{L_REFERRERS}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>

<form method="post" action="{S_MODE_ACTION}" name="refersrow_values">
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="forumline">
<tr>
	<th nowrap="nowrap">#</th>
	<th nowrap="nowrap">{L_URL}</th>
	<th nowrap="nowrap">{L_HITS}</th>
	<th nowrap="nowrap">{L_FIRST}</th>
	<th nowrap="nowrap">{L_LAST}</th>
	<!-- BEGIN switch_admin_or_mod -->
	<th nowrap="nowrap">{L_IP}</th>
	<th nowrap="nowrap">{L_SELECT}</th>
	<!-- END switch_admin_or_mod -->
</tr>
<!-- BEGIN refersrow -->
<tr>
	<td class="row1 row-center"><span class="gen">{refersrow.ID}</span></td>
	<td class="row1" ><span class="gen">{refersrow.URL}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.HITS}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.FIRST}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.LAST}</span></td>
	<!-- BEGIN switch_admin_or_mod -->
	<td class="row1 row-center"><span class="gen">{refersrow.IP}</span></td>
	<td class="row1 row-center"><span class="gensmall"><input type="checkbox" name="delete_id_{refersrow.REFER_ID}"></span></td>
	<!-- END switch_admin_or_mod -->
</tr>
<!-- END refersrow -->
<tr>
	<td class="catBottom" colspan="6" height="28">&nbsp;</td>
	<!-- BEGIN switch_admin_or_mod -->
	<td class="catBottom" align="center">
	<input type="submit" name="delete_sub" value="{L_DELETE}" class="liteoption"></td>
	<!-- END switch_admin_or_mod -->
</tr>
</table>
</form>
<form method="post" action="{S_MODE_ACTION}">
<table width="100%" cellspacing="0" class="empty-table">

<tr>
	<td align="left"><span class="gen">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>
<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr>
		<td align="right"><span class="nav">

		</td>
	</tr>
	</table>
	<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr>
		<td align="right" valign="top"></td>
	</tr>
</table>

<!-- INCLUDE ../common/lofi/bots/lofi_bots_footer.tpl -->