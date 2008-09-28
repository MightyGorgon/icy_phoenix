<h1>{L_REPLACE_TITLE}</h1>
<P>{L_REPLACE_TEXT}</p>

<!-- BEGIN switch_forum_sent -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="5%" align="center">#</th>
	<th width="25%" align="center">{L_FORUM}</th>
	<th width="30%" align="center">{L_TOPIC}</th>
	<th width="20%" align="center">{L_AUTHOR}</th>
	<th width="20%" align="center">{L_LINK}</th>
</tr>
<tr>
	<td class="cat" colspan="3" align="center">{L_STR_OLD}: {STR_OLD}</td>
	<td class="cat" colspan="2" align="center">{L_STR_NEW}: {STR_NEW}</td>
</tr>
<!-- BEGIN switch_no_results -->
<tr><td class="row1 row-center" colspan="5" style="padding:10px;">{L_NO_RESULTS}</td></tr>
<!-- END switch_no_results -->
<!-- BEGIN replaced -->
<tr>
	<td class="{switch_forum_sent.replaced.ROW_CLASS} row-center">{switch_forum_sent.replaced.NUMBER}</td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}"><a href="{switch_forum_sent.replaced.U_FORUM}" target="_blank">{switch_forum_sent.replaced.FORUM_NAME}</a></td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}"><a href="{switch_forum_sent.replaced.U_TOPIC}" target="_blank">{switch_forum_sent.replaced.TOPIC_TITLE}</a></td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS}"><a href="{switch_forum_sent.replaced.U_AUTHOR}" target="_blank">{switch_forum_sent.replaced.AUTHOR}</a></td>
	<td class="{switch_forum_sent.replaced.ROW_CLASS} row-center"><a href="{switch_forum_sent.replaced.U_POST}" target="_blank"><img src="{POST_IMG}" alt="" title="" border="" /></a> {switch_forum_sent.replaced.POST}</td>
</tr>
<!-- END replaced -->
<tr><td class="cat" colspan="5" align="center" style="font-weight:bold;">{REPLACED_COUNT}</td></tr>
</table>
<!-- END switch_forum_sent -->

<form method="post" action="{S_FORM_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2" width="25%" align="center">{L_REPLACE_TITLE}</th></tr>
<tr>
	<td class="row1" width="50%" align="right"><strong>{L_STR_OLD}:</strong></td>
	<td class="row1" width="50%"><input class="post" type="text" name="str_old" value="" style="width:99%;" /></td>
</tr>
<tr>
	<td class="row2" align="right"><strong>{L_STR_NEW}:</strong></td>
	<td class="row2"><input class="post" type="text" name="str_new" value="" style="width:99%;" /></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center">
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>
