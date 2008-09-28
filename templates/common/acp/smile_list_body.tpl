<h1>{L_SMILEY_TITLE}</h1>
<P>{L_SMILEY_TEXT}</p>

<form method="post" action="{S_SMILEY_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="cat" colspan="6" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_SMILEY_ADD}" class="mainoption" />&nbsp;&nbsp;<input class="liteoption" type="submit" name="import_pack" value="{L_IMPORT_PACK}">&nbsp;&nbsp;<input class="liteoption" type="submit" name="export_pack" value="{L_EXPORT_PACK}"></td>
</tr>
<tr>
	<th>{L_CODE}</th>
	<th>{L_SMILE}</th>
	<th>{L_EMOT}</th>
	<th>{L_MOVE}</th>
	<th colspan="2">{L_ACTION}</th>
</tr>
<!-- BEGIN smiles -->
<tr>
	<td class="{smiles.ROW_CLASS}">{smiles.CODE}</td>
	<td class="{smiles.ROW_CLASS}"><img src="{smiles.SMILEY_IMG}" alt="{smiles.CODE}" /></td>
	<td class="{smiles.ROW_CLASS}">{smiles.EMOT}</td>
	<td class="{smiles.ROW_CLASS} row-center"><a href="{smiles.U_SMILEY_MOVE_TOP}"><img src="../templates/common/images/2uparrow.png" alt="{L_MOVE_TOP} " title="{L_MOVE_TOP}" /></a><a href="{smiles.U_SMILEY_MOVE_UP}"><img src="../templates/common/images/1uparrow.png" alt="{L_MOVE_UP} " title="{L_MOVE_UP}" /></a><a href="{smiles.U_SMILEY_MOVE_DOWN}"><img src="../templates/common/images/1downarrow.png" alt="{L_MOVE_DOWN} " title="{L_MOVE_DOWN}" /></a><a href="{smiles.U_SMILEY_MOVE_END}"><img src="../templates/common/images/2downarrow.png" alt="{L_MOVE_END} " title="{L_MOVE_END}" /></a></td>
	<td class="{smiles.ROW_CLASS}"><a href="{smiles.U_SMILEY_EDIT}">{L_EDIT}</a></td>
	<td class="{smiles.ROW_CLASS}"><a href="{smiles.U_SMILEY_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END smiles -->
<tr>
	<td class="cat" colspan="6" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_SMILEY_ADD}" class="mainoption" />&nbsp;&nbsp;<input class="liteoption" type="submit" name="import_pack" value="{L_IMPORT_PACK}">&nbsp;&nbsp;<input class="liteoption" type="submit" name="export_pack" value="{L_EXPORT_PACK}"></td>
</tr>
</table>
</form>
<form method="post" action="{S_POSITION_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_SMILEY_CONFIG}</th></tr>
<tr><td class="row1">{L_POSITION_NEW_SMILIES}</td><td class="row2">{POSITION_SELECT}</td></tr>
<tr><td class="cat" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="change" value="{L_SMILEY_CHANGE_POSITION}" class="mainoption" /></td></tr>
</table>
</form>