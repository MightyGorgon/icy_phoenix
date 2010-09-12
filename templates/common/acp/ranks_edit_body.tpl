<script type="text/javascript">
<!--
function update_rank(newimage)
{
	if(newimage != '')
	{
		document.rank_image.src = '../' + newimage;
		document.post.rank_image_path.value = newimage;
	}
	else
	{
		document.rank_image.src = '../images/spacer.gif';
		document.post.rank_image_path.value = '';
	}
}
//-->
</script>

<h1>{L_RANKS_TITLE}</h1>
<p>{L_RANKS_TEXT}</p>

<form action="{S_RANK_ACTION}" method="post" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_RANKS_TITLE}</th></tr>
<tr>
	<td class="row1" width="38%"><span class="gen">{L_RANK_TITLE}:</span></td>
	<td class="row2"><input class="post" type="text" name="title" size="35" maxlength="40" value="{RANK}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_RANK_SHOW_TITLE}:</span><br /><span class="gensmall">{L_RANK_SHOW_TITLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="rank_show_title" value="1" {RANK_SHOW_TITLE_YES} />{L_YES}<br /><input type="radio" name="rank_show_title" value="0" {RANK_SHOW_TITLE_NO} />{L_NO}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_RANK_SPECIAL}</span></td>
	<td class="row2"><input type="radio" name="special_rank" value="-1" {DAYS_RANK} />{L_DAYS_RANK}<br /><input type="radio" name="special_rank" value="0" {NOT_SPECIAL_RANK} />{L_POSTS_RANK}<br /><input type="radio" name="special_rank" value="1" {SPECIAL_RANK} />{L_SPECIAL_RANK}<br /><input type="radio" name="special_rank" value="2" {GUEST_RANK} />{L_GUEST}<br /><input type="radio" name="special_rank" value="3" {BANNED_RANK} />{L_BANNED}<br /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_MIN_M_D}:</span></td>
	<td class="row2"><input class="post" type="text" name="min_posts" size="5" maxlength="10" value="{MINIMUM}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_RANK_IMAGE}:</span><br /><span class="gensmall">{L_RANK_IMAGE_EXPLAIN}</span></td>
	<td class="row2">{RANK_LIST}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_CURRENT_RANK}:</span></td>
	<td class="row2">{IMAGE_DISPLAY}</td>
</tr>
<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
{S_HIDDEN_FIELDS}</form>
