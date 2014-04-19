<script type="text/javascript">
// <![CDATA[
function Collapse_Expand(id, force_collapse, force_expand)
{
	var i;
	var count = document.getElementById('nr-sub-' + id).value;
	var collapse = (((document.getElementById('collapsed-' + id).value == 0) || force_collapse) && !force_expand) ? true : false;
	for (i = 0; i < count; i++)
	{
		if (collapse)
		{
			document.getElementById('sub-' + id + '-' + i).style.display = 'none';
			document.getElementById('sublevels-' + id).style.display = '';
			document.getElementById('i-' + id).src = '{IMG_MAXIMISE}';
			document.getElementById('i-' + id).alt = '+';
			document.getElementById('i-' + id).title = '{L_EXPAND}';
			Collapse_Expand(document.getElementById('sub-id-' + id + '-' + i).value, true, false);
		}
		else
		{
			document.getElementById('sub-' + id + '-' + i).style.display = '';
			document.getElementById('sublevels-' + id).style.display = 'none';
			document.getElementById('i-' + id).src = '{IMG_MINIMISE}';
			document.getElementById('i-' + id).alt = '-';
			document.getElementById('i-' + id).title = '{L_COLLAPSE}';
			if (force_expand)
			{
				Collapse_Expand(document.getElementById('sub-id-'+id+'-'+i).value, false, true);
			}
		}
	}
	
	document.getElementById('collapsed-' + id).value = (collapse) ? 1 : 0;
}

function Collapse_Expand_All(collapse)
{
	var id = document.getElementById('selected_id').value;
	var count = document.getElementById('nr-sub-' + id).value;
	for (i = 0; i < count; i++)
	{
		var to_collapse = document.getElementById('sub-id-' + id + '-' + i).value;
		Collapse_Expand(to_collapse, collapse, !collapse);
	}
}
// ]]>
</script>

<h1>{L_TITLE}</h1>

<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_CAT_DESC}
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{L_TITLE_EXPLAIN}
		</div>
		&nbsp;<br />&nbsp;
	</div>
</div>

<form action="{S_ACTION}" name="post" method="post">
<div class="gensmall">
	<br /><a href="#" onclick="javascript:Collapse_Expand_All(false);">{L_EXPAND_ALL}</a> - <a href="#" onclick="javascript:Collapse_Expand_All(true);">{L_COLLAPSE_ALL}</a>
</div>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_FORUM}</th>
	<th>&nbsp;{L_TOPICS}&nbsp;</th>
	<th>&nbsp;{L_POSTS}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN row -->
<tr id="sub-{row.PARENT_ID}-{row.COLLAPSE_ID}">
	<td class="row1">
		<table width="100%" height="47" cellpadding="2" cellspacing="0" border="0">
		<tr>
			<td nowrap="nowrap" width="{row.LEVEL_WIDTH}"><img src="{SPACER}" height="1" width="{row.LEVEL_WIDTH}" alt="" /></td>
			<td width="24" align="center" style="width: 24px; text-align: center; vertical-align: middle;">
			<!-- BEGIN has_sublevels -->
				<a id="{row.ID}" onclick="Collapse_Expand('{row.ID}', false, false);"><img id="i-{row.ID}" src="{IMG_MINIMISE}" alt="{L_COLLAPSE}" title="{L_COLLAPSE}" /></a>
			<!-- END has_sublevels -->
			<!-- BEGIN has_no_sublevels -->
				<img src="{SPACER}" height="1" width="11" alt="" />
			<!-- END has_no_sublevels -->
			</td>
			<td width="46" align="center"><img src="{row.FOLDER}" alt="{row.L_FOLDER}" title="{row.L_FOLDER}" /></td>
			<!-- BEGIN forum_icon -->
			<td align="center"><img src="{row.ICON_IMG}" alt="{row.ICON}" title="{row.ICON}" /></td>
			<!-- END forum_icon -->
			<td width="100%">
				<span class="forumlink"><a href="{row.U_FORUM}" class="forumlink">{row.FORUM_NAME}</a></span>
				<span class="genmed"><br />{row.FORUM_DESC}</span>
				<span class="gensmall" id="sublevels-{row.ID}" style="display: none;">
					<!-- BEGIN has_sublevels -->
					<br />{row.has_sublevels.LINKS}
					<!-- END has_sublevels -->
				</span>
			</td>
		</tr>
		</table>
	</td>
	<td width="5%" class="row1 row-center" valign="middle"><span class="gensmall">{row.TOPICS}</span></td>
	<td width="5%" class="row1 row-center" valign="middle"><span class="gensmall">{row.POSTS}</span></td>
	<td width="25%" class="row1 row-center">
		{row.S_HIDDEN_FIELDS}
		<a href="{row.U_ADD}"><img src="{IMG_CMS_ICON_ADD}" alt="{L_ADD}" title="{L_ADD}" /></a>
		<a href="{row.U_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>
		<a href="{row.U_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		<a href="{row.U_MOVEUP}"><img src="{IMG_CMS_ARROW_UP}" alt="{L_MOVEUP}" title="{L_MOVEUP}" /></a>
		<a href="{row.U_MOVEDW}"><img src="{IMG_CMS_ARROW_DOWN}" alt="{L_MOVEDW}" title="{L_MOVEDW}" /></a>
		<a href="{row.U_RESYNC}"><img src="{IMG_CMS_ICON_REFRESH}" alt="{L_RESYNC}" title="{L_RESYNC}" /></a>
		<!-- IF not row.IS_CAT --><a href="{row.U_PERMS}"><img src="{IMG_CMS_ICON_PERMISSIONS}" alt="{L_PERMISSIONS}" title="{L_PERMISSIONS}" /></a><!-- ENDIF -->
	</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr><td class="row1" colspan="4" align="center"><span class="gen">{NO_SUBFORUMS}</span></td></tr>
<!-- END empty -->
<tr>
	<td class="cat" colspan="4" align="center">{S_HIDDEN_FIELDS}
		<span class="cattitle">
			<!-- BEGIN no_root -->
			<input type="submit" name="edit" value="{L_EDIT_FORUM}" class="mainoption" />&nbsp;
			<input type="submit" name="create" value="{L_CREATE_FORUM}" class="liteoption" />&nbsp;
			<input type="submit" name="delete" value="{L_DELETE_FORUM}" class="liteoption" />&nbsp;
			<input type="submit" name="resync" value="{L_RESYNC_FORUM}" class="liteoption" />
			<!-- END no_root -->
			<!-- BEGIN root -->
			<input type="submit" name="create" value="{L_CREATE_FORUM}" class="mainoption" />&nbsp;
			<input type="submit" name="resync" value="{L_RESYNC_FORUM}" class="liteoption" />
			<!-- END root -->
		</span>
	</td>
</tr>
</table>
</form>