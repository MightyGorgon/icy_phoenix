{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_PROFILE}">{L_CPL_NAV}</a>{NAV_SEP}<a href="{U_OPTION}" class="nav-current">{L_MOD_NAME}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{CURRENT_TIME} | {S_TIMEZONE}
		</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

<!-- INCLUDE profile_cpl_menu_inc_start.tpl -->
<form method="post" action="{S_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MOD_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td colspan="2" class="row-header"><span>{L_MOD_NAME}</span></td></tr>
<!-- BEGIN field -->
<tr>
	<td class="row1" width="50%">
		<span class="gen">{field.L_NAME}</span>
		<span class="gensmall">{field.L_EXPLAIN}</span>
	</td>
	<td class="row2" width="50%" nowrap="nowrap"><span class="gen">{field.INPUT}</span></td>
</tr>
<!-- END field -->
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE profile_cpl_menu_inc_end.tpl -->