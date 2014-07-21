<h1>{L_TITLE}</h1>
<p>{L_TITLE_EXPLAIN}</p>

<form action="{S_ACTION}" name="post" method="post">
<table class="forumline">
<tr><th nowrap="nowrap" colspan="4">{L_SEARCH_RESULTS}</th></tr>
<tr>
	<th nowrap="nowrap">{L_PACK}</th>
	<th nowrap="nowrap">{L_KEY}</th>
	<th nowrap="nowrap">{L_VALUE}</th>
	<th nowrap="nowrap">{L_LEVEL}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{row.CLASS}"><a href="{row.U_PACK}" class="gen">{row.PACK}</a></td>
	<td class="{row.CLASS}" nowrap="nowrap"><a href="{row.U_KEY}" class="gen">{row.KEY_MAIN}{row.KEY_SUB}</a><span class="gen">{row.STATUS}</span></td>
	<td class="{row.CLASS}"><span class="gen">{row.VALUE}</span></td>
	<td class="{row.CLASS}" align="center"><span class="gen">{row.LEVEL}</span></td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr><td class="row1 row-center" colspan="4"><span class="gen">{L_NONE}</span></td></tr>
<!-- END none -->
<tr><td class="cat tdalignc" colspan="4"><input type="submit" name="cancel" class="liteoption" value="{L_CANCEL}" /></td></tr>
</table>
{S_HIDDEN_FIELDS}</form>