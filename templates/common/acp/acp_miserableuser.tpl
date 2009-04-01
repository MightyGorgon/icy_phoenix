<h1>{L_HEADLINE}</h1>
<p>{L_SUBHEADLINE}</p>

<br />

<!-- BEGIN infobox -->
<div align="center">
<table class="forumline" width="80%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="center" style="background-color:#{infobox.COLOR};"><b>{infobox.L_MESSAGE_TEXT}</b></td></tr>
</table>
</div>

<br /><br />
<!-- END infobox -->

<form method="post" name="post" action="{S_FORM_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th>{L_MARK_MU}</th></tr>
<tr>
	<td class="row1 row-center">
		<input type="text" class="post" name="username" maxlength="50" size="20" />
		<input type="Submit" name="submit" value="{L_LOOK_UP}" class="mainoption" />
		<input type="button" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}','_phpbbsearch','width=400,height=250,resizable=yes');" />
	</td>
</tr>
</table>
</form>

<br /><br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_USER_ENTR}</th></tr>
<tr>
	<th width="80%">{L_TH1}</th>
	<th width="20%">{L_TH2}</th>
</tr>
<!-- BEGIN output -->
<tr>
	<td class="{output.ROW_CLASS}">{output.L_USERNAME}</td>
	<td class="{output.ROW_CLASS} row-center"><b>[ <a href="{output.U_DELLINK}">{L_DELETE}</a> ]</b></td>
</tr>
<!-- END output -->
<!-- BEGIN no_entry -->
<tr><td class="row3 row-center" colspan="2"><b>{L_NOTHING}</b></td></tr>
<!-- END no_entry -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>