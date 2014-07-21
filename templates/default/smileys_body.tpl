<!-- INCLUDE simple_header.tpl -->

<!-- INCLUDE smileys_js.tpl -->

<form action="{S_ACTION}" method="post">
<!-- {IMG_THL}{IMG_THC}<span class="forumlink">{L_SMILEYS_GALLERY}</span>{IMG_THR}<table class="forumlinenb"> -->
<table class="forumline">
<tr><td class="row-header" colspan="{S_COLSPAN}"><span>{L_SMILEYS_GALLERY}</span></td></tr>
<tr><th colspan="{S_COLSPAN}"><span class="genmed">{L_SMILEYS_CATEGORY}:&nbsp;{S_CATEGORY_SELECT}&nbsp;<input type="submit" class="mainoption" value="{L_GO}" /></span></th>
</tr>
<!-- BEGIN smileys_row -->
<tr>
<!-- BEGIN smileys_column -->
	<td class="row1g row-center">
		<!-- IF smileys_row.smileys_column.SMILEY_IMG -->
		<br /><img src="{smileys_row.smileys_column.SMILEY_IMG}" alt="" onmouseover="this.style.cursor='pointer';" onclick="emoticon('{smileys_row.smileys_column.SMILEY_BBC}');" /><br /><br />
		<input class="post" name="{smileys_row.smileys_column.SMILEY_BBC_INPUT}" style="width: 60px;" value="{smileys_row.smileys_column.SMILEY_BBC}" type="text" readonly="readonly" onclick="this.form.{smileys_row.smileys_column.SMILEY_BBC_INPUT}.focus(); this.form.{smileys_row.smileys_column.SMILEY_BBC_INPUT}.select();" />
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
<!-- END smileys_column -->
</tr>
<!-- END smileys_row -->
<tr><td class="cat" colspan="{S_COLSPAN}"><span class="genmed"><a href="{U_STANDARD_SMILEYS}" class="genmed">{L_SMILEYS_STANDARD}</a></span></td></tr>
<tr><td class="cat" colspan="{S_COLSPAN}"><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span></td></tr>
</table><!-- {IMG_TFL}{IMG_TFC}{IMG_TFR} -->
</form>

<!-- INCLUDE simple_footer.tpl -->