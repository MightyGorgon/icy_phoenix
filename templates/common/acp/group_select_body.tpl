<script type="text/javascript">
<!--
function ColorExample(ColorCode, ID)
{
	var color_example_box = window.document.getElementById('color_group_example_' + ID);
	//ColorCode = CheckColor(ColorCode);
	if ( (ColorCode.length == 6) || (ColorCode.length == 3) )
	{
		color_example_box.style.color = '#' + ColorCode;
	}
	else if ( ((ColorCode.length == 7) || (ColorCode.length == 4)) && (ColorCode.charAt(0) == '#') )
	{
		color_example_box.style.color = ColorCode;
	}
}

function CheckColor(ColorCode)
{
	ColorCode = ColorCode.toLowerCase();
	if(/^\#?[0-9a-f]{6}$/i.test(ColorCode))
	{
		return ColorCode;
	}
	else if(/^\#?[0-9a-f]{3}$/i.test(ColorCode))
	{
		return ColorCode;
	}
	else
	{
		return '#000000';
	}
}
//-->
</script>
<h1>{L_GROUP_TITLE}</h1>
<p>{L_GROUP_EXPLAIN}</p>

<form method="post" action="{S_GROUP_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_GROUP_NAME}</th>
	<th>{L_GROUP_COLOR}</th>
	<th>{L_MANAGE}</th>
	<th>{L_GROUP_STATUS}</th>
	<th>{L_GROUP_MEMBERS}</th>
	<th>{L_GROUP_LEGEND}</th>
</tr>
<!-- BEGIN group_row -->
<tr class="{group_row.ROW_CLASS}h">
	<td class="{group_row.ROW_CLASS}" style="background: none;" nowrap="nowrap"><span id="color_group_example_{group_row.GROUP_ID}" class="genmed"{group_row.GROUP_COLOR_STYLE}>{group_row.GROUP_NAME}</span></td>
	<td class="{group_row.ROW_CLASS} row-center" style="background: none;"><span class="genmed">#<input type="text" class="post" name="group_color_{group_row.GROUP_ID}" value="{group_row.GROUP_COLOR}" size="9" maxlength="6" onblur="ColorExample(this.value,{group_row.GROUP_ID});" /></span></td>
	<td class="{group_row.ROW_CLASS} row-center" style="background: none;"><span class="genmed"><a href="{group_row.U_GROUP_EDIT}">{L_MANAGE}</a> / <a href="{group_row.U_GROUP_PERMISSIONS}">{L_PERMISSIONS}</a></span></td>
	<td class="{group_row.ROW_CLASS} row-center" style="background: none;"><span class="genmed">{group_row.GROUP_STATUS}</span></td>
	<td class="{group_row.ROW_CLASS} row-center" style="background: none;"><span class="genmed">{group_row.GROUP_MEMBERS}</span></td>
	<td class="{group_row.ROW_CLASS} row-center" style="background: none;"><input type="checkbox" name="group_legend_{group_row.GROUP_ID}"{group_row.GROUP_LEGEND_CHECKED} value="1" style="vertical-align:top;" />{group_row.GROUP_LEGEND_MOVE}</td>
</tr>
<!-- END group_row -->
<tr class="{ROW_CLASS_ACTIVE_USERS}h">
	<td class="{ROW_CLASS_ACTIVE_USERS}" style="background: none;" nowrap="nowrap"><span id="color_group_example_active_users" class="genmed"{ACTIVE_USERS_COLOR_STYLE}>{L_ACTIVE_USERS_GROUP}</span></td>
	<td class="{ROW_CLASS_ACTIVE_USERS} row-center" style="background: none;"><span class="genmed">#<input type="text" class="post" name="active_users_color" value="{ACTIVE_USERS_COLOR}" size="9" maxlength="7" onblur="ColorExample(this.value,'active_users');" /></span></td>
	<td class="{ROW_CLASS_ACTIVE_USERS} row-center" style="background: none;"><span class="genmed">-</span></td>
	<td class="{ROW_CLASS_ACTIVE_USERS} row-center" style="background: none;"><span class="genmed">{L_GROUP_OPEN}</span></td>
	<td class="{ROW_CLASS_ACTIVE_USERS} row-center" style="background: none;"><span class="genmed">{ACTIVE_MEMBERS}</span></td>
	<td class="{ROW_CLASS_ACTIVE_USERS} row-center" style="background: none;"><input type="checkbox" name="active_users_legend"{ACTIVE_USERS_LEGEND_CHECKED} value="1"></td>
</tr>
<tr class="{ROW_CLASS_BOTS}h">
	<td class="{ROW_CLASS_BOTS}" style="background: none;" nowrap="nowrap"><span id="color_group_example_bots" class="genmed"{BOTS_COLOR_STYLE}>{L_BOTS_GROUP}</span></td>
	<td class="{ROW_CLASS_BOTS} row-center" style="background: none;"><span class="genmed">#<input type="text" class="post" name="bots_color" value="{BOTS_COLOR}" size="9" maxlength="7" onblur="ColorExample(this.value,'bots');" /></span></td>
	<td class="{ROW_CLASS_BOTS} row-center" style="background: none;"><span class="genmed">-</span></td>
	<td class="{ROW_CLASS_BOTS} row-center" style="background: none;"><span class="genmed">{L_GROUP_OPEN}</span></td>
	<td class="{ROW_CLASS_BOTS} row-center" style="background: none;"><span class="genmed">-</span></td>
	<td class="{ROW_CLASS_BOTS} row-center" style="background: none;"><input type="checkbox" name="bots_legend"{BOTS_LEGEND_CHECKED} value="1"></td>
</tr>
<!-- BEGIN select_box -->
<!--
<tr><td class="spaceRow" colspan="6"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="row1 row-center" colspan="6">{S_GROUP_SELECT}&nbsp;&nbsp;<input type="submit" name="edit" value="{L_LOOK_UP}" class="mainoption" /></td>
</tr>
-->
<!-- END select_box -->
<tr>
	<td class="cat" colspan="6">
		{S_HIDDEN_FIELDS}
		<input type="submit" class="liteoption" name="mass_update" value="{L_MASS_UPDATE}" />
		<input type="submit" class="liteoption" name="new" value="{L_CREATE_NEW_GROUP}" />
	</td>
</tr>
</table>
</form>
