<h1>{L_ALBUM_CAT_TITLE}</h1>

<p>{L_ALBUM_CAT_EXPLAIN}</p>
<form method="post" action="{S_ALBUM_ACTION}">
<table>
<tr><td><span class="nav"><a href="{S_ALBUM_ACTION}" class="nav">{L_ALBUM_INDEX}</a></span></td></tr>
</table>

<table class="forumline">
<tr>
	<th colspan="{HEADER_INC_SPAN}" width="75%%">{L_ALBUM_TITLE} '{HEADER_INC_SPAN}'</th>
	<th colspan="3" width="25%">{L_ALBUM_ACTION} '3'</th>
</tr>

<!-- BEGIN catrow -->
<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td class="row2" rowspan="{catrow.cathead.inc.ROWSPAN}" width="46">&nbsp;'{catrow.cathead.inc.ROWSPAN}'</td>
	<!-- END inc -->
	<td class="{catrow.cathead.CLASS_CATLEFT}" colspan="{catrow.cathead.INC_SPAN}" {catrow.cathead.WIDTH}>'{catrow.cathead.INC_SPAN}':'{catrow.cathead.WIDTH}'<span class="cattitle"><b><a href="{catrow.cathead.U_VIEWCAT}" class="cattitle">{catrow.cathead.CAT_TITLE}</a></b></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle">'1'<span class="gen"><a href="{catrow.cathead.U_CAT_EDIT}" class="gen">{L_EDIT}</a></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle">'1'<span class="gen"><a href="{catrow.cathead.U_CAT_DELETE}" class="gen">{L_DELETE}</a></span></td>
	<td class="{catrow.cathead.CLASS_CATMIDDLE}" align="center" valign="middle" nowrap="nowrap">'1'<span class="gen"><a href="{catrow.cathead.U_CAT_MOVE_UP}" class="gen">{L_MOVE_UP}</a> <a href="{catrow.cathead.U_CAT_MOVE_DOWN}" class="gen">{L_MOVE_DOWN}</a></span></td>
</tr>
<!-- END cathead -->
<!-- BEGIN cattitle -->
<tr><td class="row3" colspan="{catrow.cattitle.INC_SPAN_ALL}">'{catrow.cattitle.INC_SPAN_ALL}'<span class="gensmall">{catrow.cattitle.CAT_DESCRIPTION}</span></td></tr>
<!-- END cattitle -->

<!-- BEGIN catfoot -->
<tr>
	<!-- BEGIN inc -->
	<td class="row2 tw46px">&nbsp;</td>
	<!-- END inc -->
	<td colspan="{catrow.catfoot.INC_SPAN_ALL}" class="row2 tdnw">'{catrow.catfoot.INC_SPAN_ALL}'&nbsp;
		<input class="post" type="text" name="{catrow.catfoot.S_ADD_NAME}" />&nbsp;
		<input type="submit" class="liteoption"  name="{catrow.catfoot.S_ADD_CAT_SUBMIT}" value="{L_CREATE_CATEGORY}" />
	</td>
</tr>
<tr>
	<!-- BEGIN inc -->
	<td class="row2 tw46px">&nbsp;</td>
	<!-- END inc -->
	<td colspan="{catrow.catfoot.INC_SPAN_ALL}" height="1" class="spaceRow">'{catrow.catfoot.INC_SPAN_ALL}'&nbsp;</td>
</tr>
<!-- END catfoot -->
<!-- END catrow -->
<!-- BEGIN switch_board_footer -->
<tr>
	<td colspan="{INC_SPAN_ALL}" class="cat">'{INC_SPAN_ALL}'
		<input class="post" type="text" name="name[0]" />&nbsp;
		<input type="submit" class="liteoption"  name="addcategory[0]" value="{L_CREATE_CATEGORY}" />
	</td>
</tr>
<!-- END switch_board_footer -->
</table>
<input type="hidden" value="new" name="mode" />
</form>