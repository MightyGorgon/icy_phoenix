{IMG_THL}{IMG_THC}<span class="forumlink">{L_IP_INFO}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th>{L_THIS_POST_IP}</th></tr>
<tr>
	<td class="row1h" onclick="{U_LOOKUP_IP}'">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>&nbsp;<span class="gen">{IP} [ {POSTS} ]</span></td>
			<td align="right"><span class="gen">[ <a href="{U_LOOKUP_IP}">{L_LOOKUP_IP}</a>]&nbsp;</span></td>
		</tr>
		</table>
	</td>
</tr>
<tr><th>{L_OTHER_USERS}</th></tr>
<!-- BEGIN userrow -->
<tr>
	<td class="row1h" onclick="{userrow.U_PROFILE}'">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>&nbsp;<span class="gen">{userrow.U_PROFILE_COL}&nbsp;[&nbsp;{userrow.POSTS}&nbsp;]</span></td>
			<td align="right" class="post-buttons"><a href="{userrow.U_SEARCHPOSTS}" title="{userrow.L_SEARCH_POSTS}"><img src="{SEARCH_IMG}" alt="{L_SEARCH}" /></a>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END userrow -->
<tr><th>{L_OTHER_IPS}</th></tr>
<!-- BEGIN iprow -->
<tr>
	<td class="row1h" onclick="{iprow.U_LOOKUP_IP}'">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>&nbsp;<span class="gen">{iprow.IP} [ {iprow.POSTS} ]</span></td>
			<td align="right"><span class="gen">[ <a href="{iprow.U_LOOKUP_IP}">{L_LOOKUP_IP}</a>]&nbsp;</span></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END iprow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}