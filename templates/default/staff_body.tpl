<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_STAFF}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_USERNAME}</th>
	<th>{L_FORUMS}</th>
	<th>{L_STATS}</th>
	<th>{L_CONTACT}</th>
</tr>
<!-- BEGIN user_level -->
<tr><td class="forum-buttons2" colspan="6"><span><b>{user_level.USER_LEVEL}</b></span></td></tr>
<!-- BEGIN staff -->
<tr>
	<td class="row1g" valign="top">
		<span class="gen">{user_level.staff.USERNAME}</span>
		<div class="post-rank"><b>{user_level.staff.RANK}</b>{user_level.staff.RANK_IMAGE}</div>
		<span class="post-images">{user_level.staff.AVATAR}</span>
	</td>
	<td class="row1g" valign="top"><span class="forumlink">{user_level.staff.FORUMS}</span></td>
	<td class="row1g" valign="top">
		<span class="genmed">
			<b>{L_POSTS}:</b>&nbsp;{user_level.staff.POSTS}&nbsp;&nbsp;({user_level.staff.POST_PERCENT},&nbsp;ø&nbsp;{user_level.staff.POSTS_PER_DAY})<br />
			<b>{L_TOPICS}:</b>&nbsp;{user_level.staff.TOPICS}&nbsp;&nbsp;({user_level.staff.TOPIC_PERCENT},&nbsp;ø&nbsp;{user_level.staff.TOPICS_PER_DAY})<br />
			<!-- <b>{L_LAST_POST}:</b>&nbsp;{user_level.staff.LAST_POST}<br /> -->
			<b>{L_JOINED}:</b>&nbsp;{user_level.staff.JOINED}&nbsp;&nbsp;({user_level.staff.PERIOD})
		</span>
	</td>
	<td class="row1g row-center" valign="top">
		<span class="post-buttons">{user_level.staff.PM_IMG}&nbsp;{user_level.staff.EMAIL_IMG}<!-- IF user_level.staff.WWW_IMG -->&nbsp;{user_level.staff.WWW_IMG}<!-- ENDIF --></span><br /><br />
		<!-- IF user_level.staff.AIM_IMG -->&nbsp;{user_level.staff.AIM_IMG}<!-- ENDIF --><!-- IF user_level.staff.ICQ_IMG -->&nbsp;{user_level.staff.ICQ_IMG}<!-- ENDIF --><!-- IF user_level.staff.MSN_IMG -->&nbsp;{user_level.staff.MSN_IMG}<!-- ENDIF --><!-- IF user_level.staff.SKYPE_IMG -->&nbsp;{user_level.staff.SKYPE_IMG}<!-- ENDIF --><!-- IF user_level.staff.YIM_IMG -->&nbsp;{user_level.staff.YIM_IMG}<!-- ENDIF -->&nbsp;
	</td>
</tr>
<!-- END staff -->
<!-- END user_level -->
<tr><td class="cat" colspan="6">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- INCLUDE overall_footer.tpl -->