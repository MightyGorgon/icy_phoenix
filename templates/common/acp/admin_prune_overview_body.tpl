<h1>{L_PRUNE_TITLE}</h1>
<p>{L_PRUNE_TEXT}</p>

<form action="{S_PRUNE_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>&nbsp;{L_PRUNE_FORUM}&nbsp;</th>
	<th>&nbsp;{L_PRUNE_FREQ} *&nbsp;</th>
	<th>&nbsp;{L_PRUNE_CHECK}&nbsp;</th>
	<th>&nbsp;{L_PRUNE_ACTIVE}&nbsp;</th>
</tr>
<!-- BEGIN prune_overview -->
<tr>
	<td class="{prune_overview.ROW_CLASS}"><input type="hidden" name="forum_id[{prune_overview.S_PRUNE_INDEX}]" value="{prune_overview.FORUM_ID}" /><span class="forumlink">{prune_overview.PRUNE_FORUM}</span></td>
	<td class="{prune_overview.ROW_CLASS} row-center"><span class="gensmall"><input type="text" class="post" maxlength="5" name="prune_days[{prune_overview.S_PRUNE_INDEX}]" size="5" value="{prune_overview.PRUNE_DAYS}" /> {L_DAYS}</span></td>
	<td class="{prune_overview.ROW_CLASS} row-center"><span class="gensmall"><input type="text" class="post" maxlength="5" size="5" name="prune_freq[{prune_overview.S_PRUNE_INDEX}]" value="{prune_overview.PRUNE_FREQ}" /> {L_DAYS}</span></td>
	<td class="{prune_overview.ROW_CLASS} row-center" valign="middle"><input type="checkbox" name="prune_enable[{prune_overview.S_PRUNE_INDEX}]" value="1" {prune_overview.S_PRUNE_ENABLED} /></td>
</tr>
<!-- END prune_overview -->
<tr><td class="row3" colspan="4"><span class="gensmall">{L_DAYS_EXPLAIN}</span></td></tr>
<tr>
	<td class="cat" colspan="4" height="20" align="right">
		<span class="genmed">{L_ENABLE_PRUNE}</span>
		<input type="checkbox" name="enable_prune" value="{ENABLE_PRUNE}" {ENABLE_PRUNE} />&nbsp;
		<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>
</form>

<br clear="all">
