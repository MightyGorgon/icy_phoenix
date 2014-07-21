<tr>
	<td class="row1g" colspan="2" style="padding: 20px;">
		<form method="post" action="{S_POLL_ACTION}">
		<strong class="gen"><!-- IF not S_CMS_BLOCK -->{POLL_QUESTION}<!-- ELSE --><a href="{U_VIEW_RESULTS}">{POLL_QUESTION}</a><!-- ENDIF --></strong><br />
		<div class="gensmall">{L_POLL_LENGTH}<!-- IF S_CAN_VOTE and L_POLL_LENGTH --><br /><!-- ENDIF --><!-- IF S_CAN_VOTE -->{L_MAX_VOTES}<!-- ENDIF --></div><br />
		<center>
		<table class="poll-table" align="center" cellspacing="0">
		<!-- BEGIN poll_option -->
		<tr>
			<!-- IF S_CAN_VOTE -->
			<td class="tdalignc"><input type="<!-- IF S_IS_MULTI_CHOICE -->checkbox<!-- ELSE -->radio<!-- ENDIF -->" class="radio" name="vote_id[]" value="{poll_option.POLL_OPTION_ID}"<!-- IF poll_option.POLL_OPTION_VOTED --> checked="checked"<!-- ENDIF --> /></td>
			<!-- ENDIF -->

			<!-- IF not S_CMS_BLOCK --><td><span class="gen">{poll_option.POLL_OPTION_CAPTION}</span></td><!-- ENDIF -->

			<!-- IF S_DISPLAY_RESULTS -->

			<td><!-- IF S_CMS_BLOCK --><span class="gensmall">{poll_option.POLL_OPTION_CAPTION}</span><br /><!-- ENDIF --><img src="{poll_option.POLL_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{poll_option.POLL_GRAPHIC_BODY}" width="{poll_option.POLL_OPTION_IMG_WIDTH}" height="12" alt="{poll_option.POLL_OPTION_PERCENT}" /><img src="{poll_option.POLL_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
			<td class="tdalignc"><b>{poll_option.POLL_OPTION_PERCENT}</b></td>

			<!-- IF not S_CMS_BLOCK -->
			<td class="tdalignc">( {poll_option.POLL_OPTION_RESULT} )</td>
			<!-- IF poll_option.POLL_OPTION_VOTED --><td class="tdalignc"><b class="gensmall" title="{L_POLL_VOTED_OPTION}">x</b></td><!-- ENDIF -->
			<!-- ENDIF -->

			<!-- ENDIF -->
		</tr>
		<!-- END poll_option -->
		</table>
		</center>

		<br clear="all" />
		<!-- IF S_CAN_VOTE -->
		<span class="gensmall">{L_MAX_VOTES}</span><br /><br /><input type="submit" name="submit" value="{L_SUBMIT_VOTE}" class="liteoption" /><br /><br />
		<!-- ENDIF -->
		<!-- IF S_DISPLAY_RESULTS -->
		<b>{L_TOTAL_VOTES} : {TOTAL_VOTES}</b>
		<!-- ELSE -->
		<b><a href="{U_VIEW_RESULTS}" class="gensmall">{L_VIEW_RESULTS}</a></b>
		<!-- ENDIF -->

		{S_HIDDEN_FIELDS}
		</form>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
