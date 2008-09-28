<h1>{L_LINK_CAT_TITLE}</h1>
<p>{L_LINK_CAT_EXPLAIN}</p>

<form action="{S_LINK_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="4">{L_LINK_CAT_TITLE}</th></tr>
<!-- BEGIN catrow -->
<tr>
	<td class="{catrow.COLOR}" width="60%" height="25"><span class="gen"><strong>{catrow.TITLE}</strong><br /></span><span class="gensmall">{catrow.DESC}</span></td>
	<td class="{catrow.COLOR} row-center"><span
	class="genmed"><a href="{catrow.S_MOVE_UP}">{L_MOVE_UP}</a><br /><a href="{catrow.S_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
	<td class="{catrow.COLOR} row-center"><span
	class="genmed"><a href="{catrow.S_EDIT_ACTION}">{L_EDIT}</a></span></td>
	<td class="{catrow.COLOR} row-center"><span
	class="genmed"><a href="{catrow.S_DELETE_ACTION}">{L_DELETE}</a></span></td>
</tr>
<!-- END catrow -->
<tr><td class="cat" align="center" colspan="4"><input type="hidden" value="new" name="mode" /><input name="submit" type="submit" value="{L_CREATE_CATEGORY}" class="liteoption" /></td></tr>
</table>
</form>
<div align="center"><span class="copyright">Links MOD v1.2.1 by <a href="http://www.phpbb2.de" target="_blank">phpBB2.de</a> and OOHOO</span></div>
<br />