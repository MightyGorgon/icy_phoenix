<h1>{L_RANKS_TITLE}</h1>
<p>{L_RANKS_TEXT}</p>

<form method="post" action="{S_RANKS_ACTION}">
<table class="forumline" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_RANK}</th>
	<th>{L_RANK_MINIMUM}</th>
	<th>{L_SPECIAL_RANK}</th>
	<th>{L_EDIT}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN ranks -->
<tr>
	<td class="row1 row-center">{ranks.RANK}</td>
	<td class="row1 row-center">{ranks.RANK_MIN}</td>
	<td class="row1 row-center">{ranks.SPECIAL_RANK}</td>
	<td class="row1 row-center"><a href="{ranks.U_RANK_EDIT}">{L_EDIT}</a></td>
	<td class="row1 row-center"><a href="{ranks.U_RANK_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END ranks -->
<tr><td class="cat" align="center" colspan="6"><input type="submit" class="mainoption" name="add" value="{L_ADD_RANK}" /></td></tr>
</table>
</form>