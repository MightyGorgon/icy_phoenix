<h1>{L_TITLE}</h1>
<p>{L_TITLE_EXPLAIN}</p>

<form action="{S_ACTION}" name="post" method="post">
<table class="forumline">
<tr><th nowrap="nowrap" colspan="2">{L_PACK}</th></tr>
<!-- BEGIN row -->
<tr>
	<td class="{row.COLOR}" width="70%"><span class="gen">{row.PACK}</span></td>
	<td class="{row.COLOR} row-center" nowrap="nowrap"><span class="genmed"><a href="{row.U_PACK_NORMAL}">{row.L_EDIT}</a></span></td>
	<!-- <td class="{row.COLOR} row-center" nowrap="nowrap"><span class="gen"><a href="{row.U_PACK_NORMAL}">{row.L_PACK_NORMAL}</a>&nbsp;|&nbsp;<a href="{row.U_PACK_ADMIN}">{row.L_PACK_ADMIN}</a></span></td> -->
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr><td class="row1 row-center" colspan="2"><span class="gen">{L_NONE}</span></td></tr>
<!-- END none -->
</table>

<br />

<table class="forumline">
<tr><th colspan="2">{L_SEARCH}</th></tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_SEARCH_WORDS}</strong></span><br /><span class="gensmall">{L_SEARCH_WORDS_EXPLAIN}</span><br /></td>
	<td class="row2">
		<input type="text" class="post" name="search_words" value="{SEARCH_WORDS}" size="64" /><br />
		<input type="radio" name="search_logic" value="0" {SEARCH_ALL} /><span class="gen">&nbsp;{L_SEARCH_ALL}&nbsp;&nbsp;</span>
		<input type="radio" name="search_logic" value="1" {SEARCH_ONE} /><span class="gen">&nbsp;{L_SEARCH_ONE}</span>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_SEARCH_IN}</strong></span><br /><span class="gensmall">{L_SEARCH_IN_EXPLAIN}</span><br /></td>
	<td class="row2">
		&nbsp;<input type="radio" name="search_in" value="0" {SEARCH_IN_KEY} />&nbsp;<span class="gen">{L_SEARCH_IN_KEY}</span><br />
		&nbsp;<input type="radio" name="search_in" value="1" {SEARCH_IN_VALUE} />&nbsp;<span class="gen">{L_SEARCH_IN_VALUE}</span><br />
		&nbsp;<input type="radio" name="search_in" value="2" {SEARCH_IN_BOTH} />&nbsp;<span class="gen">{L_SEARCH_IN_BOTH}</span><hr />
		&nbsp;<span class="gen">{S_LANGUAGES}</span>&nbsp;&nbsp;
		<!--
		<input type="radio" name="search_admin" value="0" {SEARCH_LEVEL_ADMIN} />&nbsp;<span class="gen">{L_SEARCH_LEVEL_ADMIN}</span>&nbsp;&nbsp;
		<input type="radio" name="search_admin" value="1" {SEARCH_LEVEL_NORMAL} />&nbsp;<span class="gen">{L_SEARCH_LEVEL_NORMAL}</span>&nbsp;&nbsp;
		<input type="radio" name="search_admin" value="2" {SEARCH_LEVEL_BOTH} />&nbsp;<span class="gen">{L_SEARCH_LEVEL_BOTH}</span>&nbsp;
		-->
	</td>
</tr>
<tr><td class="cat tdalignc" colspan="2"><input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" /></td></tr>
</table>
{S_HIDDEN_FIELDS}</form>