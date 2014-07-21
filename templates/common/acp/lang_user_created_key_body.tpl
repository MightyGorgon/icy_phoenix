<h1>{L_TITLE}</h1>
<p>{L_TITLE_EXPLAIN}</p>

<form action="{S_ACTION}" name="post" method="post">
<table class="forumline">
<tr><th nowrap="nowrap" colspan="2">{L_KEY}</th></tr>
<tr>
	<td class="row1" width="40%"><span class="genmed"><strong>{L_KEY_MAIN}</strong></span><br /><span class="gensmall">{L_KEY_MAIN_EXPLAIN}</span></td>
	<td class="row2" width="60%"><input type="text" class="post" name="new_main" size="64" value="{KEY_MAIN}" /></td>
</tr>
<tr>
	<td class="row1" width="40%"><span class="genmed"><strong>{L_KEY_SUB}</strong></span><br /><span class="gensmall">{L_KEY_SUB_EXPLAIN}</span></td>
	<td class="row2" width="60%"><input type="text" class="post" name="new_sub" size="64" value="{KEY_SUB}" /></td>
</tr>
<tr>
	<td class="row1" width="40%"><span class="genmed"><strong>{L_PACK}</strong></span><br /><span class="gensmall">{L_PACK_EXPLAIN}</span></td>
	<td class="row2" width="60%"><span class="gen">{S_PACKS}</span></td>
</tr>
<!--
<tr>
	<td class="row1" width="40%"><span class="gen">{L_LEVEL}</span><br /><span class="gensmall">{L_LEVEL_EXPLAIN}</span></td>
	<td class="row2" width="60%"><span class="gen"><input type="radio" class="radio" name="new_level" value="{LEVEL_NORMAL}" {S_LEVEL_NORMAL}/>{L_LEVEL_NORMAL}&nbsp;<input type="radio" class="radio" name="new_level" value="{LEVEL_ADMIN}" {S_LEVEL_ADMIN}/>{L_LEVEL_ADMIN}</span></td>
</tr>
-->
<tr><th colspan="2"><span>{L_LANGUAGES}</span></td></tr>
<!-- BEGIN row -->
<tr>
	<td class="row1 row-center"><span class="gen">{row.L_COUNTRY}</span><span class="gensmall"><br />{row.L_STATUS}</span></td>
	<td class="row2"><textarea rows="8" cols="64" name="new_values[{row.COUNTRY}]" class="post">{row.VALUE}</textarea></td>
</tr>
<!-- END row -->
<tr>
	<td class="cat tdalignc" colspan="2">
		<input type="submit" accesskey="s" name="submit" class="mainoption" value="{L_SUBMIT}" />
		<input type="submit" name="delete" class="liteoption" value="{L_DELETE}" />
		<input type="submit" name="cancel" class="liteoption" value="{L_CANCEL}" />
	</td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>