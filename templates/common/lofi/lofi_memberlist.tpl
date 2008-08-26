<form method="post" action="{S_MODE_ACTION}">
	<table align="center" width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
		<td align="right" nowrap="nowrap">
			<span class="genmed">
				{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
				<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
			</span>
		</td>
	</tr>
	</table>
	<div class="index">
		<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0">
		<tr>
			<th nowrap="nowrap">#</th>
			<th>{L_PM}</th>
			<th>{L_USERNAME}</th>
			<th>{L_EMAIL}</th>
			<th>{L_FROM}</th>
			<th>{L_JOINED}</th>
			<th>{L_POSTS}</th>
			<th class="thCornerR" nowrap="nowrap">{L_WEBSITE}</th>
		</tr>
		<!-- BEGIN memberrow -->
		<tr>
			<td align="center">&nbsp;{memberrow.ROW_NUMBER}&nbsp;</span></td>
			<td align="center">&nbsp;{memberrow.PM}&nbsp;</td>
			<td align="center"><a href="{memberrow.U_VIEWPROFILE}" class="gen">{memberrow.USERNAME}</a></td>
			<td align="center" valign="middle">&nbsp;{memberrow.EMAIL}&nbsp;</td>
			<td align="center" valign="middle">{memberrow.FROM}</td>
			<td align="center" valign="middle">{memberrow.JOINED}</td>
			<td align="center" valign="middle">{memberrow.POSTS}</td>
			<td align="center">&nbsp;{memberrow.WWW}&nbsp;</td>
		</tr>
		<!-- END memberrow -->
		</table>
		<table width="100%" align="center" cellspacing="2" cellpadding="2" border="0"><tr><td align="right" valign="top">&nbsp;</td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><span class="gensmall">{PAGE_NUMBER}</span></td>
			<td align="right"><span class="desc">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span></td>
		</tr>
		</table>
	</div>
</form>
<table width="100%" cellspacing="2" border="0" align="center"><tr><td valign="top" align="right">{JUMPBOX}</td></tr></table>
<br />