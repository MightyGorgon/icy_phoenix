<h1>{L_GROUP_PERMISSIONS_TITLE}</h1>
<p>{L_GROUP_PERMISSIONS_EXPLAIN}</p>

<table>
<tr>
	<td class="tdalignc">
		<form method="post" action="{A_PERM_ACTION}">
		<table class="forumline talignc tw90pct">
		<tr><th>{L_ALLOWED_FORUMS}</th></tr>
		<tr>
			<td class="row1 row-center">
				<select style="width: 560px;" name="entries[]" multiple="multiple" size="5" style="min-height: 200px;">
				<!-- BEGIN allow_option_values -->
				<option value="{allow_option_values.VALUE}">{allow_option_values.OPTION}</option>
				<!-- END allow_option_values -->
				</select>
			</td>
		</tr>
		<tr>
			<td class="cat tdalignc"> <input class="liteoption" type="submit" name="del_forum" value="{L_REMOVE_SELECTED}" /> &nbsp; <input class="liteoption" type="submit" name="close_perm" value="{L_CLOSE_WINDOW}" /><input type="hidden" name="e_mode" value="perm" /></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<tr>
	<td>
		<form method="post" action="{A_PERM_ACTION}">
		<table class="forumline talignc tw90pct">
		<tr><th>{L_ADD_FORUMS}</th></tr>
		<tr>
			<td class="row1 row-center">
			<select style="width: 560px;" name="entries[]" multiple="multiple" size="5" style="min-height: 200px;">
			<!-- BEGIN forum_option_values -->
			<option value="{forum_option_values.VALUE}">{forum_option_values.OPTION}</option>
			<!-- END forum_option_values -->
			</select>
			</td>
		</tr>
		<tr>
			<td class="cat tdalignc"> <input type="submit" name="add_forum" value="{L_ADD_SELECTED}" class="mainoption" />&nbsp; <input type="reset" value="{L_RESET}" class="liteoption" />&nbsp; <input type="hidden" name="e_mode" value="perm" /></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>

<br class="clear" />
