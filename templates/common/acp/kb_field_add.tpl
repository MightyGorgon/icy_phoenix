<h1>{L_FIELD_TITLE}</h1>
<p>{L_FIELD_EXPLAIN}</p>

<form action="{S_FIELD_ACTION}" method="post">
<!-- BEGIN error -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="row2 row-center">{ERROR}</td></tr></table>
<br />
<!-- END error -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_FIELD_TITLE}</th></tr>
<tr>
	<td width="50%" class="row1">{L_FIELD_NAME}<br /><span class="gensmall">{L_FIELD_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field_name" value="{FIELD_NAME}" /></td>
</tr>
<tr>
	<td class="row1">{L_FIELD_DESC}<br /><span class="gensmall">{L_FIELD_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field_desc" value="{FIELD_DESC}" /></td>
</tr>
<!-- BEGIN data -->
<tr>
	<td class="row1">{L_FIELD_DATA}<br /><span class="gensmall">{L_FIELD_DATA_INFO}</span></td>
	<td class="row2"><textarea rows="6" name="data" cols="32">{FIELD_DATA}</textarea></td>
	</tr>
<!-- END data -->
<!-- BEGIN regex -->
<tr>
	<td class="row1">{L_FIELD_REGEX}<br /><span class="gensmall">{L_FIELD_REGEX_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="regex" value="{FIELD_REGEX}" /></td>
</tr>
<!-- END regex -->
<!-- BEGIN order -->
<tr>
	<td width="50%" class="row1">{L_FIELD_ORDER}</td>
	<td class="row2"><input type="text" class="post" size="6" name="field_order" value="{FIELD_ORDER}" /></td>
</tr>
<!-- END order -->
<tr>
	<td class="cat" align="center" colspan="2">
		{S_HIDDEN_FIELDS}
		<input class="liteoption" type="submit" value="{L_FIELD_TITLE}" name="submit" />
	</td>
</tr>
</table>
</form>
