<h1>{L_FORUM_TITLE}</h1>
<p>{L_FORUM_EXPLAIN}</p>
<form method="post" action="{S_FORUM_ACTION}">

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="{INC_SPAN}" class="row-header"><span>{L_FORUM_TITLE}</span></td>
	<td colspan="4" class="row-header"><span>{L_ACTION}</span></td>
</tr>
<!-- BEGIN catrow -->
<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td class="{catrow.cathead.CLASS_CATLEFT}" colspan="{catrow.cathead.INC_SPAN}" {catrow.cathead.WIDTH}><span class="cattitle"><b><a href="{catrow.cathead.U_VIEWCAT}" class="cattitle">{catrow.cathead.CAT_TITLE}</a></b></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle"><span class="gen"><a href="{catrow.cathead.U_CAT_EDIT}" class="gen">{L_EDIT}</a></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle"><span class="gen"><a href="{catrow.cathead.U_CAT_DELETE}" class="gen">{L_DELETE}</a></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{catrow.cathead.U_CAT_MOVE_UP}" class="gen">{L_MOVE_UP}</a> <a href="{catrow.cathead.U_CAT_MOVE_DOWN}" class="gen">{L_MOVE_DOWN}</a></span></td>
	<td class="{catrow.cathead.CLASS_CATRIGHT}"  align="center" valign="middle"><span class="gen">&nbsp;</span></td>
</tr>
<tr>
	<!-- BEGIN inc -->
	<td class="row1" width="46"><img src="{SPACER}" width="46" height="0" alt="" /></td>
	<!-- END inc -->
	<td class="row1" colspan="{catrow.cathead.INC_SPAN}"><table cellpadding="2" cellspacing="0" border="0" width="100%"><tr><td>{catrow.cathead.ICON_IMG}</td><td width="100%"><span class="gensmall">{catrow.cathead.CAT_DESCRIPTION}</span></td></tr></table></td>
	<td class="row1"><span class="gensmall">&nbsp;</span></td>
	<td class="row1"><span class="gensmall">&nbsp;</span></td>
	<td class="row1"><span class="gensmall">&nbsp;</span></td>
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
	<td class="row1 row-center" valign="middle"><span class="gen"><a href="{catrow.forumrow.U_FORUM_EDIT}">{L_EDIT}</a></span></td>
	<td class="row1 row-center" valign="middle"><span class="gen"><a href="{catrow.forumrow.U_FORUM_DELETE}">{L_DELETE}</a></span></td>
	<td class="row1 row-center" valign="middle"><span class="gen"><a href="{catrow.forumrow.U_FORUM_MOVE_UP}">{L_MOVE_UP}</a> <br /><a href="{catrow.forumrow.U_FORUM_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
	<td class="row1 row-center" valign="middle"><span class="gen"><a href="{catrow.forumrow.U_FORUM_RESYNC}">{L_RESYNC}</a></span></td>
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