{IMG_THL}{IMG_THC}<span class="forumlink">{L_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="160" nowrap="nowrap">{L_AUTHOR}</th>
	<th nowrap="nowrap">{L_MESSAGE}</th>
</tr>
<!-- BEGIN shoutrow -->
<tr>
	<td class="row-post-author">
		<span class="post-name">{shoutrow.SHOUT_USERNAME}&nbsp;{shoutrow.GENDER}</span>
		<br />
		<div class="center-block-text"><div class="post-rank"><b>{shoutrow.RANK_IMAGE}</b></div></div>
		<span class="post-images">{shoutrow.AVATAR}</span>
		<!-- END switch_showavatars -->
		<div class="post-details">
			{shoutrow.JOINED}<br />
			{shoutrow.POSTS}<br />
			{shoutrow.FROM}<br />
		</div>
		&nbsp;<br />
	</td>
	<td class="row-post" width="100%" height="100%">
		<div class="post-buttons-top post-buttons">{shoutrow.CENSOR_IMG}&nbsp;{shoutrow.DELETE_IMG}{shoutrow.IP_IMG}</div>
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