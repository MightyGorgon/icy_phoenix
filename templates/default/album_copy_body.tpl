<!-- INCLUDE overall_header.tpl -->

<form action="{S_ALBUM_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COPY}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1 row-center"><br /><span class="gen">{L_COPY_TO_CATEGORY}</span>&nbsp;{S_CATEGORY_SELECT}&nbsp;<input class="mainoption" type="submit" name="copy" value="{L_COPY}" /><br /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- BEGIN pic_id_array -->
<input type="hidden" name="pic_id[]" value="{pic_id_array.VALUE}" />
<!-- END pic_id_array -->
</form>
<br />

<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->