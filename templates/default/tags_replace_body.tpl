<!-- INCLUDE overall_header.tpl -->

<br />
<!-- INCLUDE tags_search_jq.tpl -->
<h2 style="text-align: left;">{L_TAGS_SEARCH_REPLACE}</h2>
<br clear="all" />
<br />

<div>
<form method="post" action="{U_TAGS_SEARCH_REPLACE}">
<label>{L_TAGS_SEARCH_REPLACE_SRC}:&nbsp;<input name="tag_old" type="text" class="post" style="width: 160px;" value="" /></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>{L_TAGS_SEARCH_REPLACE_TGT}:&nbsp;<input name="tag_new" type="text" class="post" style="width: 160px;" value="" /></label><br />
<br /><br />
<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" />
</form>
</div>

<!-- INCLUDE overall_footer.tpl -->