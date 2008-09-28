<h1>{L_KB_CAT_TITLE}</h1>
<p>{L_KB_CAT_DESCRIPTION}</p>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="2" border="0">
<tr><th>{L_CREATE_CAT}&nbsp;&nbsp;<input type="text" name="new_cat_name">&nbsp;&nbsp;<input type="submit" value="{L_CREATE}" class="liteoption"></th></tr>
</table>
</form>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th nowrap="nowrap">{L_CATEGORY}</th>
	<th nowrap="nowrap">{L_ARTICLES}</th>
	<th nowrap="nowrap">{L_ORDER}</th>
	<th nowrap="nowrap">{L_ACTION}</th>
</tr>
<!-- BEGIN catrow -->
<tr>
	<td class="{catrow.ROW_CLASS}"><span class="gen">{catrow.CATEGORY}<br />{catrow.CAT_DESCRIPTION}</span></td>
	<td class="{catrow.ROW_CLASS} row-center"><span class="gen">{catrow.CAT_ARTICLES}</span></td>
	<td class="{catrow.ROW_CLASS} row-center"><span class="gen">{catrow.U_UP}<br />{catrow.U_DOWN}</span></td>
	<td class="{catrow.ROW_CLASS} row-center"><span class="post-buttons">{catrow.U_EDIT} {catrow.U_DELETE}</span></td>
</tr>
<!-- BEGIN subrow -->
<tr>
	<td class="{catrow.subrow.ROW_CLASS}"><span class="gen">{catrow.subrow.INDENT}{catrow.subrow.CATEGORY}<br />&nbsp;&nbsp;&nbsp;&nbsp;{catrow.subrow.CAT_DESCRIPTION}</span></td>
	<td class="{catrow.subrow.ROW_CLASS} row-center"><span class="gen">{catrow.subrow.CAT_ARTICLES}</span></td>
	<td class="{catrow.subrow.ROW_CLASS} row-center"><span class="gen">{catrow.subrow.U_UP}<br />{catrow.subrow.U_DOWN}</span></td>
	<td class="{catrow.subrow.ROW_CLASS} row-center"><span class="post-buttons">{catrow.subrow.U_EDIT} {catrow.subrow.U_DELETE}</span></td>
</tr>
	<!-- END subrow -->
	<!-- END catrow -->
</table>
<br clear="all" />