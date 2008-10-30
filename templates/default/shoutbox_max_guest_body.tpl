<!-- INCLUDE breadcrumbs.tpl -->

<form action="{U_SHOUTBOX}" method="post" name="post" onsubmit="return checkForm(this)">
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="catBottom" colspan="2">{S_HIDDEN_FORM_FIELDS}<input type="submit" tabindex="1" name="refresh" class="mainoption" value="{L_SHOUT_REFRESH}" />&nbsp;</td></tr>
</table>
</form>
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="160" nowrap="nowrap">{L_AUTHOR}</th>
	<th nowrap="nowrap">{L_MESSAGE}</th>
</tr>
<!-- BEGIN shoutrow -->
<tr>
	<td class="row-post-author" nowrap="nowrap">
		<span class="post-name">{shoutrow.SHOUT_USERNAME}</span><br />
		{postrow.RANK_IMAGE}<br />
		<span class="post-images">{shoutrow.USER_AVATAR}</span>
		<div class="post-details">{shoutrow.USER_JOINED}<br /></div><br />
		<img src="{SPACER}" width="150" height="1" alt="" />
	</td>
	<td class="row-post" width="100%" height="100%">
		<div class="post-buttons-top post-buttons">{shoutrow.CENSOR_IMG}&nbsp;{shoutrow.EDIT_IMG}{shoutrow.DELETE_IMG}{shoutrow.IP_IMG}</div>
		<div class="post-subject"><span class="genmed">{L_POSTED}:&nbsp;{shoutrow.TIME}</span></div>
		<div class="post-text">{shoutrow.SHOUT}{shoutrow.SIGNATURE}</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END shoutrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>