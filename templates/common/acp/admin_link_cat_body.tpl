<h1>{L_LINK_CAT_TITLE}</h1>
<p>{L_LINK_CAT_EXPLAIN}</p>

<form action="{S_LINK_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="3">{L_LINK_CAT_TITLE}</th></tr>
<!-- BEGIN catrow -->
<tr>
	<td class="{catrow.ROW_CLASS} row-center" width="30" style="padding-right:5px;" nowrap="nowrap"><img src="{IMG_NAV_MENU_STAR}" alt="{catrow.TITLE}" title="{catrow.TITLE}" /></td>
	<td class="{catrow.ROW_CLASS}" width="60%" height="25"><span class="gen"><strong>{catrow.TITLE}</strong></span></td>
	<td class="{catrow.ROW_CLASS} row-center"><a href="{catrow.S_MOVE_DOWN}"><img src="{IMG_CMS_ARROW_DOWN}" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}" /></a>&nbsp;<a href="{catrow.S_MOVE_UP}"><img src="{IMG_CMS_ARROW_UP}" alt="{L_MOVE_UP}" title="{L_MOVE_UP}" /></a>&nbsp;<a href="{catrow.S_EDIT_ACTION}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{catrow.S_DELETE_ACTION}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a></td>
</tr>
<!-- END catrow -->
<tr><td class="cat" colspan="3"><input type="hidden" value="new" name="mode" /><input name="submit" type="submit" value="{L_CREATE_CATEGORY}" class="liteoption" /></td></tr>
</table>
</form>
<div align="center"><span class="copyright">Links MOD v1.2.1 by <a href="http://www.phpbb2.de" target="_blank">phpBB2.de</a> and OOHOO</span></div>
<br />