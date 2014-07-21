<!-- INCLUDE smileys_js.tpl -->

<form name="SmileysPerPage" method="post" action="{REQUEST_URI}">
<table class="forumline">
<tr><td class="row-header"><span>{L_EMOTICONS}</span></td></tr>
<tr>
	<td class="row1 row-center tw100pct">
		<table class="p5px">
		<!-- BEGIN smilies_row -->
		<tr>
		<!-- BEGIN smilies_col -->
			<td class="tdalignc tvalignm">
				<img src="{smilies_row.smilies_col.SMILEY_IMG}" border="0" onmouseover="this.style.cursor='pointer';" onclick="emoticon('{smilies_row.smilies_col.SMILEY_CODE}');" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" />
			</td>
		<!-- END smilies_col -->
		</tr>
		<!-- END smilies_row -->
		<!-- BEGIN switch_smilies_extra -->
		<tr><td class="tdalignc" colspan="{S_SMILIES_COLSPAN}"><span class="nav"><a href="{U_MORE_SMILIES}" onclick="open_window('{U_MORE_SMILIES}', 250, 300);return false" target="_smilies" class="nav">{L_MORE_SMILIES}</a></span></td></tr>
		<!-- END switch_smilies_extra -->
		</table>
	</td>
</tr>
<tr><td class="tdalignc"><span class="genmed">{L_SMILEYS_PER_PAGE}:&nbsp;{SELECT_SMILEYS_PP}</span></td></tr>
<tr><td class="tdalignc"><span class="genmed">{PAGINATION}</span></td></tr>
<tr><td class="cat"><span class="genmed"><a href="{U_SMILEYS_GALLERY}" class="genmed">{L_SMILEYS_GALLERY}</a></span></td></tr>
<tr><td class="cat"><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span></td></tr>
</table>
</form>