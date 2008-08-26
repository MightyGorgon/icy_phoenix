<table width="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td width="50%" valign="top">
		<form action="{S_PROFILE_ACTION_USER}" method="post" name="user_traffic">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th colspan="2">{L_USER_TITLE}</th></tr>
		<tr><td class="row3 row-center" colspan="2"><span class="gensmall">{L_USER_EXPLAIN}</span></td></tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_USERNAME}:</strong></span></td>
			<td class="row2" width="75%"><input type="text" class="post" name="username" size="20" maxlength="32" value="" /></td>
		</tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_TRAFFIC}:</strong></span></td>
			<td class="row2" width="75%">
			<span class="genmed">
			<input type="text" class="post" name="user_traffic" size="20" maxlength="40" />&nbsp;<br />
			<input type="radio" name="x" value="b"/>&nbsp;{L_DL_BYTES}&nbsp;
			<input type="radio" name="x" value="kb" checked="checked" />&nbsp;{L_DL_KB}&nbsp;
			<input type="radio" name="x" value="mb"/>&nbsp;{L_DL_MB}&nbsp;
			<input type="radio" name="x" value="gb"/>&nbsp;{L_DL_GB}
			</span>
			</td>
		</tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_FUNCTION}:</strong></span></td>
			<td class="row2" width="75%"><span class="genmed"><input type="radio" name="func" value="add" checked="checked"/>&nbsp;{L_ADD}&nbsp;&nbsp;&nbsp;<input type="radio" name="func" value="set" />&nbsp;{L_SET}</span></td>
		</tr>
		<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
		</table>
		</form>

		<form action="{S_PROFILE_ACTION_ALL}" method="post" name="all_traffic">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th colspan="2" align="center">{L_ALL_USERS_TITLE}</th></tr>
		<tr><td class="row3 row-center" width="100%" colspan="2"><span class="gensmall">{L_ALL_USERS_EXPLAIN}</span></td></tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_TRAFFIC}:</strong></span></td>
			<td class="row2" width="75%">
			<span class="genmed">
			<input type="text" class="post" name="all_traffic" size="20" maxlength="40" />&nbsp;<br />
			<input type="radio" name="y" value="b" />&nbsp;{L_DL_BYTES}&nbsp;
			<input type="radio" name="y" value="kb" checked="checked" />&nbsp;{L_DL_KB}&nbsp;
			<input type="radio" name="y" value="mb" />&nbsp;{L_DL_MB}&nbsp;
			<input type="radio" name="y" value="gb" />&nbsp;{L_DL_GB}
			</span>
			</td>
		</tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_FUNCTION}:</strong></span></td>
			<td class="row2" width="75%"><span class="genmed"><input type="radio" name="func" value="add" checked="checked"/>&nbsp;{L_ADD}&nbsp;&nbsp;&nbsp;<input type="radio" name="func" value="set"/>&nbsp;{L_SET}</span></td>
		</tr>
		<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
		</table>
		</form>

		<!-- BEGIN group_block -->
		<form action="{S_PROFILE_ACTION_GROUP}" method="post" name="group_traffic">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th colspan="2">{L_USERGROUP_TITLE}</th></tr>
		<tr><td class="row3" colspan="2" align="center"><span class="gensmall">{L_USERGROUP_EXPLAIN}</span></td></tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_USERGROUP}:</strong></span></td>
			<td class="row2" width="75%">{S_GROUP_SELECT}</td>
		</tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_TRAFFIC}:</strong></span></td>
			<td class="row2">
			<span class="genmed">
			<input type="text" class="post" name="group_traffic" size="20" maxlength="40" />&nbsp;<br />
			<input type="radio" name="z" value="b"/>&nbsp;{L_DL_BYTES}&nbsp;
			<input type="radio" name="z" value="kb" checked="checked" />&nbsp;{L_DL_KB}&nbsp;
			<input type="radio" name="z" value="mb"/>&nbsp;{L_DL_MB}&nbsp;
			<input type="radio" name="z" value="gb"/>&nbsp;{L_DL_GB}
			</span>
			</td>
		</tr>
		<tr>
			<td class="row1" width="25%"><span class="genmed"><strong>{L_FUNCTION}:</strong></span></td>
			<td class="row2" width="75%"><span class="genmed"><input type="radio" name="func" value="add" checked="checked"/>&nbsp;{L_ADD}&nbsp;&nbsp;&nbsp;<input type="radio" name="func" value="set"/>&nbsp;{L_SET}</span></td>
		</tr>
		<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
		</table>
		</form>
		<!-- END group_block -->

	</td>
	<td width="50%" valign="top">

		<form action="{S_CONFIG_ACTION}" method="post" name="auto_traffic">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th colspan="2">{L_CONFIGURATION_TITLE}</th></tr>
		<tr><td class="row3 row-center" colspan="2"><span class="gensmall">{L_CONFIGURATION_EXPLAIN}</span></td></tr>
		<tr><th colspan="2">{L_USER_WITHOUT_GROUP}</th></tr>
		<tr>
			<td class="row1" width="30%"><span class="genmed"><strong>{L_USER_DL_AUTO_TRAFFIC}</strong></span></td>
			<td class="row2" width="70%">
			<span class="genmed">
			<input name="user_dl_auto_traffic" type="text" class="post" size="10" maxlength="50" value="{USER_DL_AUTO_TRAFFIC}" />&nbsp;<br />
			<input name="data_user_range" type="radio" value="B" {USER_DATA_RANGE_B} />&nbsp;{L_DL_BYTES}&nbsp;
			<input name="data_user_range" type="radio" value="KB" {USER_DATA_RANGE_KB} />&nbsp;{L_DL_KB}&nbsp;
			<input name="data_user_range" type="radio" value="MB" {USER_DATA_RANGE_MB} />&nbsp;{L_DL_MB}&nbsp;
			<input name="data_user_range" type="radio" value="GB" {USER_DATA_RANGE_GB} />&nbsp;{L_DL_GB}
			</span>
			</td>
		</tr>
		<!-- BEGIN group_block -->
		<tr>
			<th><b>{L_GROUP_NAME}</b></th>
			<th width="75%"><b>{L_GROUP_DL_AUTO_TRAFFIC}</b></th>
		</tr>
		<!-- END group_block -->
		<!-- BEGIN group_row -->
		<tr>
			<td class="row1" width="30%"><span class="genmed"><strong>{group_row.GROUP_NAME}</strong></span></td>
			<td class="row2" width="70%">
			<span class="genmed">
			<input name="group_dl_auto_traffic[{group_row.GROUP_ID}]" type="text" class="post" size="10" maxlength="50" value="{group_row.GROUP_DL_AUTO_TRAFFIC}" />&nbsp;<br />
			<input name="data_group_range[{group_row.GROUP_ID}]" type="radio" value="B" {group_row.GROUP_DATA_RANGE_B} />&nbsp;{L_DL_BYTES}&nbsp;
			<input name="data_group_range[{group_row.GROUP_ID}]" type="radio" value="KB" {group_row.GROUP_DATA_RANGE_KB} />&nbsp;{L_DL_KB}&nbsp;
			<input name="data_group_range[{group_row.GROUP_ID}]" type="radio" value="MB" {group_row.GROUP_DATA_RANGE_MB} />&nbsp;{L_DL_MB}&nbsp;
			<input name="data_group_range[{group_row.GROUP_ID}]" type="radio" value="GB" {group_row.GROUP_DATA_RANGE_GB} />&nbsp;{L_DL_GB}
			</span>
			</td>
		</tr>
		<!-- END group_row -->
		<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
		</table>
		</form>

	</td>
</tr>
</table>