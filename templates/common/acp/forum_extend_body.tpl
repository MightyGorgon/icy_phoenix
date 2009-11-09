<script type="text/javascript">
// <![CDATA[
function Collapse(id, hiding)
{
	var i;
	var count = document.getElementById('nr-sub-' + id).value;
	if ((document.getElementById('collapsed-' + id).value == 0) || hiding)
	{
		for (i = 0; i < count; i++)
		{
			document.getElementById('sub-' + id + '-' + i).style.display = 'none';
			document.getElementById('i-' + id).src = '{IMG_MAXIMISE}';
			document.getElementById('i-' + id).alt = '+';
			Collapse(document.getElementById('sub-id-' + id + '-' + i).value, true);
		}
		document.getElementById('collapsed-' + id).value = 1;
	}
	else
	{
		for (i = 0; i < count; i++)
		{
			document.getElementById('sub-' + id + '-' + i).style.display = '';
			document.getElementById('i-' + id).src = '{IMG_MINIMISE}';
			document.getElementById('i-' + id).alt = '-';
			// If you activate this, when you click to show a category, it will expand everything besides it
			// Collapse(document.getElementById('sub-id-'+id+'-'+i).value);
		}
		document.getElementById('collapsed-' + id).value = 0;
	}
}
// ]]>
</script>

<h1>{L_TITLE}</h1>

<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_CAT_DESC}</span>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{L_TITLE_EXPLAIN}
		</div>
		&nbsp;<br />&nbsp;
	</div>
</div>

<form action="{S_ACTION}" name="post" method="post">
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
			<td nowrap="nowrap" width="{row.LEVEL_WIDTH}"><img src="{SPACER}" height="1" width="{row.LEVEL_WIDTH}" /></td>
			<td width="24" align="center" style="width: 24px; text-align: center; vertical-align: middle;">
			<!-- BEGIN has_sublevels -->
				<a id="{row.ID}" title="{L_COLLAPSE}" onclick="Collapse('{row.ID}');"><img id="i-{row.ID}" src="{IMG_MINIMISE}" alt="-" /></a>
			<!-- END has_sublevels -->
			<!-- BEGIN has_no_sublevels -->
				<img src="{SPACER}" height="1" width="11" />
			<!-- END has_no_sublevels -->
			</td>
			<td width="46" align="center"><img src="{row.FOLDER}" alt="{row.L_FOLDER}" title="{row.L_FOLDER}" /></td>
			<!-- BEGIN forum_icon -->
			<td align="center"><img src="{row.ICON_IMG}" alt="{row.ICON}" title="{row.ICON}" /></td>
			<!-- END forum_icon -->
			<td width="100%">
				<span class="forumlink"><a href="{row.U_FORUM}" class="forumlink">{row.FORUM_NAME}</a></span>
				<span class="genmed"><br />{row.FORUM_DESC}</span>
				<span class="gensmall">{row.LINKS}</span>
			</td>
		</tr>
		</table>
	</td>
	<td width="5%" class="row1 row-center" valign="middle"><span class="gensmall">{row.TOPICS}</span></td>
	<td width="5%" class="row1 row-center" valign="middle"><span class="gensmall">{row.POSTS}</span></td>
	<td width="25%" class="row1 row-center">
		{row.S_HIDDEN_FIELDS}
		<a href="{row.U_EDIT}"><img src="../images/cms/b_edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>
		<a href="{row.U_DELETE}"><img src="../images/cms/b_delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		<a href="{row.U_MOVEUP}"><img src="../images/cms/arrow_up.png" alt="{L_MOVEUP}" title="{L_MOVEUP}" /></a>
		<a href="{row.U_MOVEDW}"><img src="../images/cms/arrow_down.png" alt="{L_MOVEDW}" title="{L_MOVEDW}" /></a>
		<a href="{row.U_RESYNC}"><img src="../images/cms/b_refresh.png" alt="{L_RESYNC}" title="{L_RESYNC}" /></a>
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