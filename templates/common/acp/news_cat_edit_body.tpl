<h1>{L_NEWS_TITLE}</h1>
<p>{L_NEWS_EXPLAIN}</p>

<script type="text/javascript">
<!--
function update_smiley(newimage)
{
	document.news_image.src = "{S_SMILEY_BASEDIR}/" + newimage;
}
//-->
</script>

<form method="post" action="{S_NEWS_ACTION}">
<table class="forumline" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="3">{L_NEWS_CONFIG}</th></tr>
<tr>
	<td class="row2"><strong>{L_CATEGORY}:</strong></td>
	<td class="row2" colspan="2"><input class="post" type="text" name="category" value="{NEWS_CATEGORY}" maxlength="70" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_NEWS_ICON}:</strong></td>
	<td class="row1"><select name="image_url" onchange="update_smiley(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select></td>
	<td class="row1"><img name="news_image" src="{I_NEWS_IMG}" alt="" /></td>
</tr>
<tr><td class="cat" colspan="3" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" /></td></tr>
</table>
</form>