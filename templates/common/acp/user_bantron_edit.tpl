<h1>{L_BC_TITLE}</h1>
<p>{L_BC_EXPLAIN}</p>

<form action="{S_BANCENTER_ACTION}" name="post" method="post">
<table class="forumline">
<tr><th colspan="2">{L_ADD_A_NEW_BAN}</th></tr>
<!-- BEGIN username_row -->
<tr>
	<td class="row1">{username_row.L_USERNAME}:</td>
	<td class="row2" style="width:350px;">
		<input class="post" type="text" class="post" name="username" value="{username_row.USERNAME}" maxlength="50" size="20" />
		<input type="hidden" name="mode" value="edit" />
		<input type="button" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{username_row.U_SEARCH_USER}', '_search', 'width=400,height=250,resizable=yes');" />
	</td>
</tr>
<!-- END username_row -->
<!-- BEGIN ip_row -->
<tr>
	<td class="row1">{ip_row.L_IP_OR_HOSTNAME}: <br /><span class="gensmall">{ip_row.L_BAN_IP_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_ip" value="{ip_row.BAN_IP}" size="35" /></td>
</tr>
<!-- END ip_row -->
<!-- BEGIN email_row -->
<tr>
	<td class="row1">{email_row.L_EMAIL_ADDRESS}: <br /><span class="gensmall">{email_row.L_BAN_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_email" value="{email_row.BAN_EMAIL}" size="35" /></td>
</tr>
<!-- END email_row -->
<tr>
	<td class="row1">{L_PRIVATE_REASON}: <br /><span class="gensmall">{L_PRIVATE_REASON_EXPLAIN}</span></td>
	<td class="row2"><textarea name="ban_priv_reason" rows="15" cols="35" style="width:340px;" class="post">{BAN_PRIV_REASON}</textarea></td>
</tr>
<tr>
	<td class="row1">{L_PUBLIC_REASON}: <br /><span class="gensmall">{L_PUBLIC_REASON_EXPLAIN}</span></td>
	<td class="row2">
		<table cellpadding="1" cellspacing="0" border="0">
		<tr><td colspan="2"><input type="radio" name="ban_pub_reason_mode" value="0"{BAN_PUB_REASON_MODE_0} /> {L_GENERIC_REASON}</td></tr>
		<tr><td colspan="2"><input type="radio" name="ban_pub_reason_mode" value="1"{BAN_PUB_REASON_MODE_1} /> {L_MIRROR_PRIVATE_REASON}</td></tr>
		<tr><td colspan="2"><input type="radio" name="ban_pub_reason_mode" value="2"{BAN_PUB_REASON_MODE_2} /> {L_OTHER}</td></tr>
		<tr>
			<td width="30">&nbsp;</td>
			<td><textarea name="ban_pub_reason" rows="15" cols="35" style="width:310px;" class="post">{BAN_PUB_REASON}</textarea></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1">{L_EXPIRE_TIME}: <br /><span class="gensmall">{L_EXPIRE_TIME_EXPLAIN}</span></td>
	<td class="row2">
		<table cellpadding="1" cellspacing="0" border="0">
			<tr><td colspan="2"><input type="radio" name="ban_expire_time_mode" value="never"{BAN_EXPIRE_TIME_MODE_NEVER} /> {L_NEVER}</td></tr>
			<tr><td colspan="2"><input type="radio" name="ban_expire_time_mode" value="relative"{BAN_EXPIRE_TIME_MODE_RELATIVE} /> {L_AFTER_SPECIFIED_LENGTH_OF_TIME}</td></tr>
			<tr>
				<td width="30">&nbsp;</td>
				<td>
					<input type="text" class="post" name="ban_expire_time_relative" maxlength="10" size="4" />
					<select name="ban_expire_time_relative_units">
						<option value="minutes">{L_MINUTES}</option>
						<option value="hours">{L_HOURS}</option>
						<option value="days">{L_DAYS}</option>
						<option value="weeks">{L_WEEKS}</option>
						<option value="months">{L_MONTHS}</option>
						<option value="years">{L_YEARS}</option>
					</select>
				</td>
			</tr>
			<tr><td colspan="2"><input type="radio" name="ban_expire_time_mode" value="absolute"{BAN_EXPIRE_TIME_MODE_ABSOLUTE} /> {L_AFTER_SPECIFIED_DATE}</td></tr>
			<tr>
				<td width="30">
					&nbsp;
				</td>
				<td>
					<input type="text" name="ban_expire_time_absolute_hour" value="{BAN_EXPIRE_TIME_ABSOLUTE_HOUR}" size="2" maxlength="2" style="width: 2em;" />&nbsp;:&nbsp;<input type="text" name="ban_expire_time_absolute_minute" value="{BAN_EXPIRE_TIME_ABSOLUTE_MINUTE}" size="2" maxlength="2" style="width: 2em;">&nbsp;<input type="radio" name="ban_expire_time_absolute_ampm" value="am"{BAN_EXPIRE_TIME_ABSOLUTE_AM} />{L_AM}&nbsp;<input type="radio" name="ban_expire_time_absolute_ampm" value="pm"{BAN_EXPIRE_TIME_ABSOLUTE_PM} />{L_PM}&nbsp;<input type="radio" name="ban_expire_time_absolute_ampm" value=""{BAN_EXPIRE_TIME_ABSOLUTE_24} />{L_24_HOUR}
					<br />
					<input type="text" name="ban_expire_time_absolute_month" value="{BAN_EXPIRE_TIME_ABSOLUTE_MONTH}" size="2" maxlength="2" style="width: 2em;" />&nbsp;/&nbsp;<input type="text" name="ban_expire_time_absolute_mday" value="{BAN_EXPIRE_TIME_ABSOLUTE_MDAY}" size="2" maxlength="2" style="width: 2em;">&nbsp;/&nbsp;<input type="text" name="ban_expire_time_absolute_year" value="{BAN_EXPIRE_TIME_ABSOLUTE_YEAR}" size="4" maxlength="4" style="width: 4em;">
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="cat tdalignc" colspan="2">
		<!-- BEGIN ban_id -->
		<input type="hidden" name="ban_id" value="{ban_id.BAN_ID}" />
		<!-- END ban_id -->
		<input type="submit" name="{SUBMIT}" value="{L_SUBMIT}" class="mainoption" />
	</td>
</tr>
</table>
</form>