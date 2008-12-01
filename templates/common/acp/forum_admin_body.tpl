<h1>{L_FORUM_TITLE}</h1>
<p>{L_FORUM_EXPLAIN}</p>
<form method="post" action="{S_FORUM_ACTION}">

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="{INC_SPAN}"><span>{L_FORUM_TITLE}</span></th>
	<th style="width: 120px;"><span>{L_ACTION}</span></th>
</tr>
<!-- BEGIN catrow -->
<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td class="{catrow.cathead.CLASS_CATLEFT}" colspan="{catrow.cathead.INC_SPAN}" {catrow.cathead.WIDTH}><span class="cattitle"><b><a href="{catrow.cathead.U_VIEWCAT}" class="cattitle">{catrow.cathead.CAT_TITLE}</a></b></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle">
		<a href="{catrow.cathead.U_CAT_EDIT}"><img src="../images/cms/b_edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>
		<a href="{catrow.cathead.U_CAT_DELETE}"><img src="../images/cms/b_delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		<a href="{catrow.cathead.U_CAT_MOVE_UP}"><img src="../images/cms/arrow_up.png" alt="{L_MOVE_UP}" title="{L_MOVE_UP}" /></a>
		<a href="{catrow.cathead.U_CAT_MOVE_DOWN}"><img src="../images/cms/arrow_down.png" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}" /></a>
	</td>
</tr>
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td class="row1" colspan="{catrow.cathead.INC_SPAN}"><table cellpadding="2" cellspacing="0" border="0" width="100%"><tr><td>{catrow.cathead.ICON_IMG}</td><td width="100%"><span class="gensmall">{catrow.cathead.CAT_DESCRIPTION}</span></td></tr></table></td>
	<td class="row1"><span class="gensmall">&nbsp;</span></td>
</tr>
<!-- END cathead -->
<!-- BEGIN forumrow -->
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td class="row1" colspan="{catrow.forumrow.INC_SPAN}" {catrow.forumrow.WIDTH}><table cellspacing="0" cellpadding="0" width="100%"><tr><td>{catrow.forumrow.LINK_IMG}</td><td>{catrow.forumrow.ICON_IMG}</td><td width="100%"><span class="gen"><a href="{catrow.forumrow.U_VIEWFORUM}">{catrow.forumrow.FORUM_NAME}</a></span><br /><span class="gensmall">{catrow.forumrow.FORUM_DESC}</span></td></tr></table></td>
	<td class="row1 row-center" valign="middle"><span class="gen">{catrow.forumrow.NUM_TOPICS}</span></td>
	<td class="row1 row-center" valign="middle"><span class="gen">{catrow.forumrow.NUM_POSTS}</span></td>
	<td class="row1 row-center" valign="middle">
		<a href="{catrow.forumrow.U_FORUM_EDIT}"><img src="../images/cms/b_edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>
		<a href="{catrow.forumrow.U_FORUM_DELETE}"><img src="../images/cms/b_delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		<a href="{catrow.forumrow.U_FORUM_MOVE_UP}"><img src="../images/cms/arrow_up.png" alt="{L_MOVE_UP}" title="{L_MOVE_UP}" /></a>
		<a href="{catrow.forumrow.U_FORUM_MOVE_DOWN}"><img src="../images/cms/arrow_down.png" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}" /></a>
		<a href="{catrow.forumrow.U_FORUM_RESYNC}"><img src="../images/cms/b_refresh.png" alt="{L_RESYNC}" title="{L_RESYNC}" /></a>
	</td>
</tr>
<!-- END forumrow -->
<!-- BEGIN catfoot -->
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td colspan="{catrow.catfoot.INC_SPAN_ALL}" class="row2" nowrap="nowrap">
		<img src="{SPACER}" width="46" height="0" alt="" />
		<input class="post" type="text" name="{catrow.catfoot.S_ADD_NAME}" />&nbsp;
		<input type="submit" class="liteoption"  name="{catrow.catfoot.S_ADD_FORUM_SUBMIT}" value="{L_CREATE_FORUM}" />
		<input type="submit" class="liteoption"  name="{catrow.catfoot.S_ADD_CAT_SUBMIT}" value="{L_CREATE_CATEGORY}" />
	</td>
</tr>
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td colspan="{catrow.catfoot.INC_SPAN_ALL}" height="1" class="spaceRow"><img src="{SPACER}" width="46" height="0" alt="" /></td>
</tr>
<!-- END catfoot -->
<!-- END catrow -->
<tr>
	<td colspan="{INC_SPAN_ALL}" class="cat">
		<!-- BEGIN switch_board_footer -->
		<input class="post" type="text" name="name[0]" />&nbsp;
		<!-- BEGIN sub_forum_attach -->
		<input type="submit" class="liteoption" name="addforum[0]" value="{L_CREATE_FORUM}" />
		<!-- END sub_forum_attach -->
		<input type="submit" class="liteoption" name="addcategory[0]" value="{L_CREATE_CATEGORY}" />
		<!-- END switch_board_footer -->
	</td>
</tr>
</table>
</form>