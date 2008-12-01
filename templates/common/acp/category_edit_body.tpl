<script type="text/javascript">
<!--
function update_icon(newimage)
{
	if(newimage != '')
	{
		document.icon_image.src = '../' + newimage;
		document.post.icon.value = newimage;
	}
	else
	{
		document.icon_image.src = '../images/spacer.gif';
		document.post.icon.value = '';
	}
}
//-->
</script>

<p>{L_EDIT_CATEGORY_EXPLAIN}</p>

<form action="{S_FORUM_ACTION}" method="post" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_CATEGORY}</th></tr>
<tr>
	<td class="row1">{L_CATEGORY}</td>
	<td class="row2"><input class="post" type="text" size="25" name="cat_title" value="{CAT_TITLE}" /></td>
</tr>
<tr>
	<td class="row1">{L_CAT_DESCRIPTION}</td>
	<td class="row2"><textarea rows="5" cols="45" wrap="virtual" name="cat_desc" class="post">{CAT_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ICON}</span><span class="gensmall"><br />{L_ICON_EXPLAIN}</span></td>
	<td class="row2">{ICON_LIST}</td>
</tr>
<tr>
	<td class="row1">{L_CATEGORY_ATTACHMENT}</td>
	<td class="row2"><select name="cat_main">{S_CAT_LIST}</select></td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td></tr>
</table>
</form>

<br clear="all" />
