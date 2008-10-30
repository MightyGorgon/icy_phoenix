<div align="center">
<p class="maintitle">{PAGE_TITLE}</p>
<p class="gen">
	<a href="{U_ALT_SCREEN}">{L_ALT_SCREEN}</a>&nbsp;|&nbsp;
	<a href="{U_RATINGS}">{L_RATINGS}</a>&nbsp;|&nbsp;
	<a href="{U_FORUM_INDEX}">{L_FORUM_INDEX}</a>
</p>

<table class="forumline" width="100%" cellspacing="0">
<tr>
	<th>{L_BIAS}</th>
	<th>{L_WHO}</th>
	<th>{L_WHEN}</th>
	<th>{L_REASON}</th>
	<th>{L_CURRENT}</th>
</tr>
<!-- BEGIN bias -->
<tr>
	<td class="row1 row-center" valign="middle">
		<span class="name">{bias.BIAS}</span>
	</td>
	<td class="row2 row-center" valign="middle">
		<span class="name">{bias.WHO}</span>
	</td>
	<td class="row1 row-center" valign="middle">
		<span class="name">{bias.WHEN}</span>
	</td>
	<td class="row2" valign="middle">
		<span class="name">{bias.REASON}</span>
	</td>
	<td class="row3Right" valign="middle" nowrap="nowrap">
		<span class="name">{bias.CURRENT}</span>
	</td>
</tr>
<!-- END bias -->
<!-- BEGIN nobias -->
<tr>
	<td class="row1 row-center" colspan="5" valign="middle">
		<span class="gen">{L_NO_BIAS}</span>
	</td>
</tr>
<!-- END nobias -->
</table>

</div>