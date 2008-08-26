<tr>
	<td class="row1g" colspan="2" style="padding:20px;">
		<strong class="gen">{POLL_QUESTION}</strong><br />
		<br />
		<center>
		<table class="poll-table" align="center" cellspacing="0">
		<!-- BEGIN poll_option -->
		<tr>
			<td>{poll_option.POLL_OPTION_CAPTION}</td>
			<td><img src="{poll_option.POLL_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{poll_option.POLL_GRAPHIC_BODY}" width="{poll_option.POLL_OPTION_IMG_WIDTH}" height="12" alt="{poll_option.POLL_OPTION_PERCENT}" /><img src="{poll_option.POLL_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
			<!--
			<td><img src="{T_TPL_PATH}images/{TPL_COLOR}/vote_lcap_{poll_option.POLL_OPTION_COLOR}.gif" width="4" height="12" alt="" /><img src="{T_TPL_PATH}images/{TPL_COLOR}/voting_bar_{poll_option.POLL_OPTION_COLOR}.gif" width="{poll_option.POLL_OPTION_IMG_WIDTH}" height="12" alt="{poll_option.POLL_OPTION_PERCENT}" /><img src="{T_TPL_PATH}images/{TPL_COLOR}/vote_rcap_{poll_option.POLL_OPTION_COLOR}.gif" width="4" alt="" height="12" /></td>
			-->
			<td align="center"><b>{poll_option.POLL_OPTION_PERCENT}</b></td>
			<td align="center">( {poll_option.POLL_OPTION_RESULT} )</td>
		</tr>
		<!-- END poll_option -->
		</table>
		</center>
		<br clear="all" />
		<b>{L_TOTAL_VOTES} : {TOTAL_VOTES}</b>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
