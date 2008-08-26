<tr>
	<td colspan="2">
		<form method="post" action="{S_POLL_ACTION}">
		<table class="empty-table" width="100%" align="center" cellspacing="0">
		<tr><td align="center"><span class="gensmall"><b>{B_POLL_QUESTION}</b></span></td></tr>
		<tr>
			<td align="center">
				<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
				<!-- BEGIN b_poll_option -->
				<tr>
					<td align="right"><input type="radio" name="vote_id" value="{b_poll_option.B_POLL_OPTION_ID}" />&nbsp;</td>
					<td align="left"><span class="gensmall">{b_poll_option.B_POLL_OPTION_CAPTION}</span></td>
				</tr>
				<!-- END b_poll_option -->
				</table>
			</td>
		</tr>
		<tr>
			<td align="center">
				<!-- BEGIN switch_user_logged_in -->
				{B_S_HIDDEN_FIELDS}
				{B_L_SUBMIT_VOTE}
				<!-- END switch_user_logged_in -->		
				<!-- BEGIN switch_user_logged_out -->
				<span class="gensmall">{B_LOGIN_TO_VOTE}</span>
				<!-- END switch_user_logged_out -->
			</td>
		</tr>
		</table>
		</form>
	</td>
</tr>