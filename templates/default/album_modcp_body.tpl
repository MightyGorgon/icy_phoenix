<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript">
// <![CDATA[
function checkAll(field)
{
	$("[type='checkbox']").each(function(index, element) {
		element.checked = true;
	});
}

function checkReverse()
{
	$("[type='checkbox']").each(function(index, element) {
		element.checked = !element.checked;
	});
}

function checkSelected()
{
	var selected = false;
	$("[type='checkbox']").each(function(index, element) {
		if (element.checked)
		{
			selected = true;
		}
	});

	/* Message needs to be added
	if (!selected)
	{
		alert("{None_selected}"); // Not quite right, but close enough
		return false;
	}
	else
	{
		return true;
	}
	*/
	return true;
}
// ]]>
</script>

<form name="modcp" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkSelected();">
<div style="margin-top: 5px; text-align: right;">
<span class="gensmall">{L_SELECT_SORT_METHOD}:
<select name="sort_method">
	<option {SORT_TIME} value='pic_time'>{L_TIME}</option>
	<option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
	{SORT_USERNAME_OPTION}
	<option {SORT_VIEW} value='pic_view_count'>{L_VIEW}</option>
	{SORT_RATING_OPTION}
	{SORT_COMMENTS_OPTION}
	{SORT_NEW_COMMENT_OPTION}
</select>
&nbsp;{L_ORDER}:
<select name="sort_order">
	<option {SORT_ASC} value='ASC'>{L_ASC}</option>
	<option {SORT_DESC} value='DESC'>{L_DESC}</option>
</select>
&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span>
</div>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_MODCP}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th height="25" nowrap="nowrap">&nbsp;{L_PIC_TITLE}&nbsp;</th>
	<th width="5%" nowrap="nowrap">&nbsp;{L_RATING}&nbsp;</th>
	<th width="5%" nowrap="nowrap">&nbsp;{L_COMMENTS}&nbsp;</th>
	<th width="5%" nowrap="nowrap">&nbsp;{L_STATUS}&nbsp;</th>
	<th width="5%" nowrap="nowrap">&nbsp;{L_APPROVAL}&nbsp;</th>
	<th width="5%" nowrap="nowrap">&nbsp;{L_SELECT}&nbsp;</th>
</tr>
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center" colspan="6" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN picrow -->
<tr>
	<td class="row1" height="25">
		<span class="genmed">
			{L_POSTER}: {picrow.POSTER}<br />
			{L_PIC_TITLE}: {picrow.PIC_TITLE}<br />
			{L_PIC_ID}: {picrow.PIC_ID}<br />
			{L_TIME}: {picrow.TIME}
		</span>
	</td>
	<td class="row1 row-center"><span class="genmed">{picrow.RATING}</span></td>
	<td class="row1 row-center"><span class="genmed">{picrow.COMMENTS}</span></td>
	<td class="row1 row-center"><span class="genmed">{picrow.LOCK}</span></td>
	<td class="row1 row-center"><span class="genmed">{picrow.APPROVAL}</span></td>
	<td class="row1 row-center"><span class="genmed"><input type="checkbox" name="pic_id[]" value="{picrow.PIC_ID}"{picrow.CHECKED} /></span></td>
</tr>
<!-- END picrow -->
<tr>
	<td class="cat" colspan="6" align="right" height="28">
		<input type="hidden" name="mode" value="modcp" />
		<input type="submit" class="liteoption" name="move" value="{L_MOVE}" />
		<input type="submit" class="liteoption" name="copy" value="{L_COPY}" />
		<input type="submit" class="liteoption" name="lock" value="{L_LOCK}" />
		<input type="submit" class="liteoption" name="unlock" value="{L_UNLOCK}" />
		{DELETE_BUTTON}
		{APPROVAL_BUTTON}
		{UNAPPROVAL_BUTTON}
		&nbsp;&nbsp;
		<input type="button" class="liteoption" name="checkButton" value="{L_CHECK_ALL}" onclick="checkAll();">
		<input type="button" class="liteoption" name="inverseButton" value="{L_INVERSE_SELECTION}" onclick="checkReverse();">
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span></td></tr>
<tr><td colspan="3"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->
