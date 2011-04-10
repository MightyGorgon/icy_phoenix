<!-- IF S_SHOW_AVATARS -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN topposter -->
<tr>
	<td class="row1 row-center">
		<br /><a href="{topposter.U_VIEWPOSTER}" class="gensmall">{topposter.AVATAR_IMG}</a><br /><br />
		{topposter.USERNAME}&nbsp;[<b><a href="{topposter.U_VIEWPOSTS}" class="gensmall">{topposter.POSTS}</a></b>]<br /><br />
	</td>
</tr>
<!-- END topposter -->
</table>
<br />
<!-- ELSE -->
<!-- BEGIN topposter -->
<div style="float: right;">[<b><a href="{topposter.U_VIEWPOSTS}" class="gensmall">{topposter.POSTS}</a></b>]</div>{topposter.USERNAME}&nbsp;<br clear="all" />
<!-- END topposter -->
<!-- ENDIF -->
