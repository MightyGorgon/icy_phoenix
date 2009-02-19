<h1>{L_KB_CAT_TITLE}</h1>
<p>{L_KB_CAT_DESCRIPTION}</p>

<form action="{S_ACTION}" method="post">
<div style="text-align:right;padding:3px;">
<span class="genmed"><strong>{L_CREATE_CAT}</strong>&nbsp;&nbsp;<input type="text" name="new_cat_name" />&nbsp;&nbsp;<input type="submit" value="{L_CREATE}" class="liteoption" /></span>
</div>
</form>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th nowrap="nowrap">{L_CATEGORY}</th>
	<th nowrap="nowrap">{L_ARTICLES}</th>
	<th nowrap="nowrap">{L_ACTION}</th>
</tr>
<!-- BEGIN catrow -->
<tr>
	<td class="{catrow.ROW_CLASS}"><span class="gen">{catrow.CATEGORY}<br />{catrow.CAT_DESCRIPTION}</span></td>
	<td class="{catrow.ROW_CLASS} row-center"><span class="gen">{catrow.CAT_ARTICLES}</span></td>
	<td class="{catrow.ROW_CLASS} row-center"><span class="gensmall">{catrow.U_EDIT}&nbsp;{catrow.U_DELETE}&nbsp;{catrow.U_UP}&nbsp;{catrow.U_DOWN}</span></td>
</tr>
<!-- BEGIN subrow -->
<tr>
	<td class="{catrow.subrow.ROW_CLASS}"><span class="gen">{catrow.subrow.INDENT}{catrow.subrow.CATEGORY}<br />&nbsp;&nbsp;&nbsp;&nbsp;{catrow.subrow.CAT_DESCRIPTION}</span></td>
	<td class="{catrow.subrow.ROW_CLASS} row-center"><span class="gen">{catrow.subrow.CAT_ARTICLES}</span></td>
	<td class="{catrow.subrow.ROW_CLASS} row-center"><span class="gensmall">{catrow.subrow.U_EDIT}&nbsp;{catrow.subrow.U_DELETE}&nbsp;{catrow.subrow.U_UP}&nbsp;{catrow.subrow.U_DOWN}</span></td>
</tr>
	<!-- END subrow -->
	<!-- END catrow -->
<tr><td class="cat" colspan="3">&nbsp;</td></tr>
</table>
<br clear="all" />