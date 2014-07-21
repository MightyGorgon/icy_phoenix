<!-- INCLUDE overall_header.tpl -->

<table>
<tr>
	<td class="nav">&nbsp;</td>
	<td class="tdalignr tdnw"><span class="gensmall">{ALBUM_SEARCH_BOX}</td>
</tr>
</table>

{ALBUM_BOARD_INDEX}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_USERS_PERSONAL_GALLERIES}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw100pct tdnw">&nbsp;{L_USERS}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_JOINED}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_PICS}&nbsp;</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="row1h">&nbsp;<a href="{memberrow.U_VIEWGALLERY}" class="genmed" style="text-decoration: none;">{memberrow.USERNAME}</a></td>
	<td class="row1 row-center tdnw"><span class="gensmall">&nbsp;{memberrow.JOINED}&nbsp;</span></td>
	<td class="row1 row-center" align="center"><span class="gensmall">{memberrow.PICS}</span></td>
</tr>
<!-- END memberrow -->
<tr>
	<td class="cat tdalignc" colspan="3">
		<form method="post" action="{S_MODE_ACTION}">
			<span class="gensmall">
			{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
			<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
			</span>
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table>
<tr><td class="tdalignr tvalignb"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></tr>
</table>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->