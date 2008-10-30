<p class="maintitle">{PAGE_TITLE}</p>
<form method="get" name="ratings_form" action="{U_RATINGS}">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="gen">{L_SCREEN_TYPE}</td>
	<td class="gen">{L_FORUM}</td>
	<td class="gen">{L_INCLUDE_BY}</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="gen">
		<select name="type">
			<!-- BEGIN screen_type -->
			<option value="{screen_type.VALUE}"{screen_type.SELECTED}>{screen_type.TITLE}</option>
			<!-- END screen_type -->
		</select>
	</td>
	<td>
		<select name="forum_id">
			<!-- BEGIN forums -->
			<option value="{forums.ID}"{forums.SELECTED}>{forums.TITLE}</option>
			<!-- END forums -->
		</select>
	</td>
	<td>
		<select name="ratingsby">
			<!-- BEGIN ratingsby -->
			<option value="{ratingsby.ID}"{ratingsby.SELECTED}>{ratingsby.TITLE}</option>
			<!-- END ratingsby -->
		</select>
	</td>
	<td><input type="submit" value="Go" class="liteoption" name="submit" /></td>
</tr>
</table>
</form>

<table class="forumline" width="100%" cellspacing="0">
<tr>
	<th>{L_COLUMN1}</th>
	<th>{L_TOPIC}</th>
	<th>{L_COLUMN3}</th>
	<th>{L_COLUMN4}</th>
	<th>{L_COLUMN5}</th>
</tr>
<!-- BEGIN rating -->
<tr>
	<td class="row1 row-center" valign="middle"><span class="name">{rating.COLUMN1}</span></td>
	<td class="row2" valign="middle"><span class="forumlink"><a href="{rating.COLUMN2}">{rating.TOPIC_TITLE}</a></span></td>
	<td class="row1 row-center" valign="middle"><span class="name">{rating.COLUMN3}</span></td>
	<td class="row2 row-center" valign="middle"><span class="postdetails">{rating.COLUMN4}</span></td>
	<td class="row3Right" valign="middle" nowrap="nowrap"><span class="postdetails">{rating.COLUMN5}</span></td>
</tr>
<!-- END rating -->
<!-- BEGIN norating -->
<tr><td class="row1 row-center" colspan="5" height="30" valign="middle"><span class="gen">{L_NO_RATINGS}</span></td></tr>
<!-- END norating -->
</table>