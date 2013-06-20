{IMG_THL}{IMG_THC}<span class="forumlink">{L_PENDING_MEMBERS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_USERNAME}</th>
	<th>{L_POSTS}</th>
	<th>{L_FROM}</th>
	<th>{L_ONLINE_STATUS}</th>
	<th>{L_PM}</th>
	<th>{L_EMAIL}</th>
	<th>{L_WEBSITE}</th>
	<th>{L_SELECT}</th>
</tr>
<!-- BEGIN pending_members_row -->
<tr>
	<td class="row1h row-center" data-href="{pending_members_row.U_VIEWPROFILE}"><a href="{pending_members_row.U_VIEWPROFILE}">{pending_members_row.USERNAME}</a></td>
	<td class="row2 row-center">{pending_members_row.POSTS}</td>
	<td class="row1 row-center">&nbsp;{pending_members_row.FROM}&nbsp;</td>
	<td class="row1 row-center">&nbsp;{pending_members_row.ONLINE_STATUS_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{pending_members_row.PM_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{pending_members_row.EMAIL_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{pending_members_row.WWW_IMG}&nbsp;</td>
	<td class="row3 row-center"><input type="checkbox" name="pending_members[]" value="{pending_members_row.USER_ID}" checked="checked" /></td>
</tr>
<!-- END pending_members_row -->
<tr>
	<td class="cat" colspan="9" align="right">
		<input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="mainoption" />
		<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}