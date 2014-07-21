<h1>{L_ALBUM_CAT_TITLE}</h1>

<p>{L_ALBUM_CAT_EXPLAIN}</p>
<form method="post" action="{S_ALBUM_ACTION}">
<table>
<tr><td><span class="nav"><a href="{S_ALBUM_ACTION}" class="nav">{L_ALBUM_INDEX}</a></span></td></tr>
</table>

<table class="forumline">
<tr>
	<th colspan="{HEADER_INC_SPAN}" width="75%">{L_ALBUM_TITLE}</th>
	<th colspan="3" width="25%">{L_ALBUM_ACTION}</th>
</tr>

<!-- BEGIN catrow -->
	<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td class="row2" rowspan="{catrow.cathead.inc.ROWSPAN}" width="46">&nbsp;</td>
	<!-- END inc -->
	<td class="row1" colspan="{catrow.cathead.INC_SPAN}" {catrow.cathead.WIDTH}><span class="forumlink"><b><a href="{catrow.cathead.U_VIEWCAT}" class="cattitle">{catrow.cathead.CAT_TITLE}</a></b></span></td>
	<td class="row1 row-center tvalignm"><span class="gensmall"><a href="{catrow.cathead.U_CAT_EDIT}">{catrow.cathead.L_EDIT}</a></span></td>
	<td class="row1 row-center tvalignm"><span class="gensmall"><a href="{catrow.cathead.U_CAT_DELETE}">{catrow.cathead.L_DELETE}</a></span></td>
	<td class="row1 row-center tvalignm" nowrap="nowrap"><span class="gensmall"><a href="{catrow.cathead.U_CAT_MOVE_UP}">{catrow.cathead.L_MOVE_UP}</a> | <a href="{catrow.cathead.U_CAT_MOVE_DOWN}">{catrow.cathead.L_MOVE_DOWN}</a></span></td>
</tr>
	<!-- END cathead -->
	<!-- BEGIN cattitle -->
<tr><td class="row3" colspan="{catrow.cattitle.INC_SPAN_ALL}"><span class="gensmall">{catrow.cattitle.CAT_DESCRIPTION}</span></td></tr>
	<!-- END cattitle -->

	<!-- BEGIN catfoot -->
<tr>
		<!-- BEGIN inc -->
	<td class="row2 tw46px">&nbsp;</td>
		<!-- END inc -->
	<td class="row2" colspan="{catrow.catfoot.INC_SPAN_ALL}" nowrap="nowrap">&nbsp;
		<input class="post" type="text" name="{catrow.catfoot.S_ADD_NAME}" />&nbsp;
		<input type="submit" {DISABLE_CREATION} class="liteoption"  name="{catrow.catfoot.S_ADD_CAT_SUBMIT}" value="{L_CREATE_CATEGORY}" />
	</td>
</tr>
<tr>
		<!-- BEGIN inc -->
	<td class="row2 tw46px">&nbsp;</td>
		<!-- END inc -->
	<td colspan="{catrow.catfoot.INC_SPAN_ALL}" height="1" class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td>
</tr>
	<!-- END catfoot -->
<!-- END catrow -->

<!-- BEGIN switch_board_footer -->
<tr>
	<td colspan="{INC_SPAN_ALL}" class="cat">
		<input class="post" type="text" name="name[0]" />&nbsp;&nbsp;
		<input type="submit" {DISABLE_CREATION} class="mainoption" name="addcategory[0]" value="{L_CREATE_CATEGORY}" />&nbsp;&nbsp;
		<input type="submit" class="liteoption" name="sync_pics_counter" value="{L_SYNC_PICS_COUNTER}" />
	</td>
</tr>
<!-- END switch_board_footer -->
</table>
<input type="hidden" value="new" name="mode" />
</form>