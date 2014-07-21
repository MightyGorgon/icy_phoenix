<h1>{L_CAT_TITLE}</h1>
<p>{L_CAT_EXPLAIN}</p>

<form method="post" action="{S_CAT_ACTION}">
<table class="forumline">
<tr><th colspan="2">{L_CAT_TITLE}</th></tr>
<!-- BEGIN cat_row -->
<tr>
	<td class="row1 row-center" width="160" valign="middle"><span class="genmed"><a href="{cat_row.U_CAT_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{cat_row.U_CAT_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>&nbsp;<a href="{cat_row.U_CAT_MOVE_UP}"><img src="{IMG_CMS_ARROW_UP}" alt="{L_MOVE_UP}" title="{L_MOVE_UP}" /></a>&nbsp;<a href="{cat_row.U_CAT_MOVE_DOWN}"><img src="{IMG_CMS_ARROW_DOWN}" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}" /></a>&nbsp;<a href="{cat_row.U_CAT_RESYNC}"><img src="{IMG_CMS_ICON_REFRESH}" alt="{L_RESYNC}" title="{L_RESYNC}" /></a></span></td>
	<td class="row1" valign="middle" nowrap="nowrap">{cat_row.PRE}{NAV_SEP}<!-- IF cat_row.IS_HIGHER_CAT --><strong><!-- ENDIF --><a href="{cat_row.U_CAT}" class="cattitle">{cat_row.CAT_NAME}</a><!-- IF cat_row.IS_HIGHER_CAT --></strong><!-- ENDIF --></td>
</tr>
<!-- END cat_row -->
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" class="liteoption"  name="addcategory" value="{L_CREATE_CATEGORY}" /></td></tr>
</table></form>