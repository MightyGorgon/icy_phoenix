<h1>{L_RANKS_TITLE}</h1>
<p>{L_RANKS_TEXT}</p>

<form method="post" action="{S_RANKS_ACTION}">
<table class="forumline">
<tr>
	<th>{L_RANK}</th>
	<th>{L_RANK_MINIMUM}</th>
	<th>{L_SPECIAL_RANK}</th>
	<th>{L_ACTION}</th>
</tr>
<!-- BEGIN ranks -->
<tr>
	<td class="{ranks.ROW_CLASS} row-center">{ranks.RANK}</td>
	<td class="{ranks.ROW_CLASS} row-center">{ranks.RANK_MIN}</td>
	<td class="{ranks.ROW_CLASS} row-center">{ranks.SPECIAL_RANK}</td>
	<td class="{ranks.ROW_CLASS} row-center">&nbsp;<a href="{ranks.U_RANK_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{ranks.U_RANK_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>&nbsp;</td>
</tr>
<!-- END ranks -->
<tr><td class="cat tdalignc" colspan="4"><input type="submit" class="mainoption" name="add" value="{L_ADD_RANK}" /></td></tr>
</table>
</form>