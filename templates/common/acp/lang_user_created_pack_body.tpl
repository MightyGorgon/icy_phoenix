<h1>{L_TITLE}</h1>

<p>{L_TITLE_EXPLAIN}</p>

<form action="{S_ACTION}" name="post" method="post">
<table class="p2px">
<tr>
	<td class="tw100pct"><span class="genmed"><a href="{U_PACK}"><b>{L_PACK}:</b></a><!-- &nbsp;{LEVEL}&nbsp;- -->&nbsp;{PACK}</span></td>
	<td nowrap="nowrap" align="right">&nbsp;<!-- <span class="genmed"><a href="{U_LEVEL_NEXT}">{L_LEVEL_NEXT}</a></span> --></td>
</tr>
</table>

<table class="forumline">
<tr><th class="tdnw" colspan="2">{L_KEYS}</th></tr>
<!-- BEGIN row -->
<tr>
	<td class="{row.CLASS}" nowrap="nowrap"><span class="genmed"><a href="{row.U_KEY}">{row.KEY_MAIN}{row.KEY_SUB}</a></span><span class="gensmall">&nbsp;{row.STATUS}</span></td>
	<td class="{row.CLASS}" width="100%"><span class="genmed">{row.VALUE}</span></td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr><td class="row1 row-center"><span class="gen">{L_NONE}</span></td></tr>
<!-- END none -->
<tr>
	<td class="cat tdalignc" colspan="2">
		<input type="submit" name="add" class="mainoption" value="{L_ADD}" />
		<input type="submit" name="cancel" class="liteoption" value="{L_CANCEL}" />
	</td>
</tr>
</table>{S_HIDDEN_FIELDS}</form>