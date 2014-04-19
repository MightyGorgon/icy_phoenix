<script type="text/javascript">
<!--
function ColorExample(ColorCode)
{
	var color_example_box = window.document.getElementById('color_group_example');
	if ( (ColorCode.length == 6) || (ColorCode.length == 3) )
	{
		color_example_box.style.color = '#' + ColorCode;
	}
	else if ( ((ColorCode.length == 7) || (ColorCode.length == 4)) && (ColorCode.charAt(0) == '#') )
	{
		color_example_box.style.color = ColorCode;
	}
}
//-->
</script>

<!-- IF S_AJAX_FEATURES -->
<script type="text/javascript">
<!--
last_username = '{GROUP_MODERATOR}';
//-->
</script>
<!-- ENDIF -->

<h1>{L_GROUP_TITLE}</h1>

<form action="{S_GROUP_ACTION}" method="post" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_GROUP_EDIT_DELETE}</th></tr>
<tr><td class="row1" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td></tr>
<tr>
	<td class="row1" width="38%"><span class="gen">{L_GROUP_NAME}:</span></td>
	<td class="row2" width="62%"><input class="post" type="text" name="group_name" size="35" maxlength="40" value="{GROUP_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_DESCRIPTION}:</span></td>
	<td class="row2"><textarea class="post" name="group_description" rows="5" cols="51">{GROUP_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_MODERATOR}:</span></td>
	<td class="row2"><input class="post" type="text" class="post" name="username" id="username" maxlength="50" size="20" value="{GROUP_MODERATOR}" {S_AJAX_USER_CHECK} /><span id="username_list" style="display: none;"><span id="username_select">&nbsp;</span>&nbsp;</span>&nbsp;<input type="button" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}', '_search', 'width=400,height=250,resizable=yes');" /></td>
</tr>
<tr id="username_error_tbl" style="display: none;">
	<td class="row1">&nbsp;</td>
	<td class="row2" id="username_error_text">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_STATUS}:</span></td>
	<td class="row2">
	<input type="radio" name="group_type" value="{S_GROUP_OPEN_TYPE}" {S_GROUP_OPEN_CHECKED} /> {L_GROUP_OPEN} &nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_CLOSED_TYPE}" {S_GROUP_CLOSED_CHECKED} /> {L_GROUP_CLOSED} &nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_HIDDEN_TYPE}" {S_GROUP_HIDDEN_CHECKED} /> {L_GROUP_HIDDEN}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_RANK}:</span></td>
	<td class="row2"><select name="group_rank">{RANK_SELECT_BOX}</select></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_COLOR}:</span></td>
	<td class="row2">
		#<input class="post" type="text" name="group_color" size="9" maxlength="7" value="{GROUP_COLOR}" onblur="ColorExample(this.value);" />&nbsp;
		[ <span id="color_group_example"{GROUP_COLOR_STYLE}>{L_EXAMPLE}</span> ]
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_LEGEND}:</span></td>
	<td class="row2"><input type="checkbox" name="group_legend" {GROUP_LEGEND_CHECKED} value="1">{L_YES}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_COUNT}:<br />{L_GROUP_COUNT_MAX}:</span><br />
	<span class="gensmall">{L_GROUP_COUNT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="group_count" maxlength="12" size="12" value="{GROUP_COUNT}" /><br /><input type="text" class="post" name="group_count_max" maxlength="12" size="12" value="{GROUP_COUNT_MAX}" />
	<br />&nbsp;&nbsp; <span class="gen"></span><input type="checkbox" name="group_count_enable" {GROUP_COUNT_ENABLE_CHECKED} >&nbsp;{L_GROUP_COUNT_ENABLE}
	<br />&nbsp;&nbsp; <input type="checkbox" name="group_count_update" value="0"/>&nbsp;{L_GROUP_COUNT_UPDATE}
	<br />&nbsp;&nbsp; <input type="checkbox" name="group_count_delete" value="0"/>&nbsp;{L_GROUP_COUNT_DELETE}</span>
	</td>
</tr>
<!-- BEGIN group_edit -->
<tr>
	<td class="row1"><span class="gen">{L_DELETE_MODERATOR}</span>
	<br />
	<span class="gensmall">{L_DELETE_MODERATOR_EXPLAIN}</span></td>
	<td class="row2">
	<input type="checkbox" name="delete_old_moderator" value="1">
	{L_YES}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_DELETE}:</span></td>
	<td class="row2">
	<input type="checkbox" name="group_delete" value="1">
	{L_GROUP_DELETE_CHECK}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_UPLOAD_QUOTA}</span></td>
	<td class="row2">{S_SELECT_UPLOAD_QUOTA}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_PM_QUOTA}</span></td>
	<td class="row2">{S_SELECT_PM_QUOTA}</td>
</tr>
<!-- END group_edit -->
<tr>
	<td class="cat" colspan="2" align="center">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="group_update" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
	</td>
</tr>
</table>
</form>