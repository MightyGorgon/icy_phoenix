<!-- INCLUDE breadcrumbs.tpl -->

<form action="{U_SHOUTBOX}" method="post" name="post" onsubmit="return checkForm(this)">
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="cat" colspan="2">{S_HIDDEN_FORM_FIELDS}<input type="submit" tabindex="1" name="refresh" class="mainoption" value="{L_SHOUT_REFRESH}" />&nbsp;</td></tr>
</table>
</form>

<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>
{IMG_THL}{IMG_THC}<span class="forumlink"><b>{L_SHOUTBOX}<b/></span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="160" nowrap="nowrap">{L_AUTHOR}</th>
	<th nowrap="nowrap">{L_MESSAGE}</th>
</tr>
<!-- BEGIN shoutrow -->
<tr>
	<td width="160" align="left" valign="top" class="{shoutrow.ROW_CLASS}">
		<span class="name"><b>{shoutrow.SHOUT_USERNAME}</b></span><br />
		<span class="postdetails">{shoutrow.USER_RANK}<br />
		{shoutrow.RANK_IMAGE}<br/>
		{shoutrow.USER_AVATAR}<br /><br/>{shoutrow.USER_JOINED}</span>
	</td>
	<td class="{shoutrow.ROW_CLASS}" width="100%" height="28" valign="top">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="100%">
					<a href="{shoutrow.U_MINI_POST}"><img src="{shoutrow.MINI_POST_IMG}" alt="{shoutrow.L_MINI_POST_ALT}" title="{shoutrow.L_MINI_POST_ALT}" /></a>
					<span class="postdetails">{L_POSTED}: {shoutrow.TIME}</span>
				</td>
				<td valign="top" align="right" nowrap="nowrap">{shoutrow.QUOTE_IMG}{shoutrow.EDIT_IMG}{shoutrow.DELETE_IMG}{shoutrow.IP_IMG}</td>
			</tr>
			<tr><td colspan="2"><hr/></td></tr>
			<tr><td colspan="2"><div class="post-text">{shoutrow.SHOUT}{shoutrow.SIGNATURE}</div></td></tr>
		</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" alt="" width="1" height="3" /></td></tr>
<!-- END shoutrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>