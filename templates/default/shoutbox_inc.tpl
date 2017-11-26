{IMG_THL}{IMG_THC}<span class="forumlink">{L_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th width="160" nowrap="nowrap">{L_AUTHOR}</th>
	<th class="tdnw">{L_MESSAGE}</th>
</tr>
<!-- BEGIN shoutrow -->
<tr>
	<td class="row-post-author tdnw">
		{shoutrow.AVATAR_IMG}
		<br />
		<span class="post-name">{shoutrow.SHOUT_USERNAME}&nbsp;{shoutrow.GENDER}</span>
		<br />
		<div class="center-block-text"><div class="post-rank"><b>{shoutrow.RANK_IMAGE}</b></div></div>
		<!--
		<div class="post-details">
			{shoutrow.JOINED}<br />
			{shoutrow.POSTS}<br />
			{shoutrow.FROM}<br />
		</div>
		-->
		&nbsp;<br />
	</td>
	<td class="row-post" width="90%" height="100%">
		<div class="post-buttons-top post-buttons">{shoutrow.CENSOR_IMG}&nbsp;{shoutrow.DELETE_IMG}{shoutrow.IP_IMG}</div>
		<div class="post-subject"><span class="genmed">{L_POSTED}:&nbsp;{shoutrow.TIME}</span></div>
		<div class="post-text post-text-hide-flow">{shoutrow.SHOUT}{shoutrow.SIGNATURE}</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END shoutrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<table>
<tr>
	<td class="tdalignr tvalignb">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>