<p class="maintitle">{HEADING}</p>
{ERROR_REPORT}
{GENERAL_ERRORS}
<form name="rating_form" method="post" action="{FORM_ACTION}">
<input type="hidden" name="rating_form_submitted" value="y">
<table cellspacing="10">
<tr>
<td>
	<table class="forumline">
	<tr><th colspan=2>{L_GENERAL_CONFIGURATION}</th></tr>
	<!-- BEGIN config -->
	<tr><td class="{config.ROWCSS}" nowrap>{config.LABEL}</td><td class="{config.ROWCSS}">{config.INPUT}</td></tr>
	<!-- END config -->
	</table>
	<br />
	{CONFIG_SUBMIT}
</td>
<td>
<p class="gen">{L_OVERVIEW_TEXT}</p>
<p class="gen">{L_OVERALL_SETTINGS_TITLE}</p>
<p class="genmed">{L_OVERALL_SETTINGS_TEXT}</p>
</td>
</tr>
</table>
</form>
<hr>
{OPTION_ERRORS}

<a id="options"></a>
<form name="rating_options_form" method="post" action="{FORM_ACTION}#options">
<input type="hidden" name="rating_form_submitted" value="y" />
<table cellspacing="10">
<tr>
<td>
	<table class="forumline">
	<tr><th colspan="7">{L_RATING_OPTIONS}</th></tr>
	<tr>
	<th>{L_POINTS}</th>
	<th>{L_LABEL}</th>
	<th>{L_WEIGHTING_THRESHOLD}</th>
	<th>{L_WHO}</th>
	<th>{L_USED}</th>
	<th>{L_DELETE}</th>
	<th>{L_UPDATE}</th>
	</tr>
	<!-- BEGIN option -->
	<tr>
	<td class="{option.ROWCSS}">
		<input type="hidden" name="r_option_0[{option.ID}]" value="{option.POINTS}" />
		<input class="post" type="text" size="4" maxlength="4" name="r_option_1[{option.ID}]" value="{option.POINTS}" />
	</td>
	<td class="{option.ROWCSS}"><input class="post" type="text" name="r_option_2[{option.ID}]" value="{option.LABEL}" /></td>
	<td class="{option.ROWCSS}" align="center"><input class="post" type="text" size="4" maxlength="8" name="r_option_3[{option.ID}]" value="{option.THRESHOLD}" /></td>
	<td class="{option.ROWCSS}" align="center">
		<select name="r_option_4[{option.ID}]">
		<!-- BEGIN who -->
		<option value="{option.who.ID}" {option.who.SELECTED}>{option.who.LABEL}</option>
		<!-- END who -->
		</select>
	</td>
	<td class="{option.ROWCSS}" align="center"><input type="hidden" name="r_option_5[{option.ID}]" value="{option.USED}" /><span class="genmed">{option.USED}</span></td>
	<td class="{option.ROWCSS}" align="center"><span class="gen">{option.DELETE}</span></td>
	<td class="{option.ROWCSS}" align="center"><span class="gen">{option.SUBMIT}</span></td>
	</tr>
	<!-- END option -->
	</table>
</td>
<td>
<p class="gen">{L_OPTION_TITLE}</p>
<p class="genmed">{L_OPTION_TEXT}</p>
</td>
</tr>
</table>
<p class="gen">{L_RECALC_TEXT}</p>
<input class="mainoption" type="submit" method="post" name="recalculate" value="{L_RECALC_BUTTON}" onclick="return confirm('{L_RECALC_CONFIRM}')" />

</form>

<hr>
<p class="gen">{L_RANK_TITLE}</p>
<p class="genmed">{L_RANK_TEXT}</p>
{TOTAL_ERRORS}

<a id="totals"></a>
<form name="rating_totals_form" method="post" action="{FORM_ACTION}#totals">
<input type="hidden" name="rating_form_submitted" value="y" />
<table cellspacing="10">
<tr>
<td>
	<table class="forumline">
	<tr><th colspan="7">{L_RATING_RANKS}</th></tr>
	<tr>
	<th>{L_APPLIES_TO}</th>
	<th>{L_AVERAGE}</th>
	<th>{L_SUM}</th>
	<th>{L_LABEL}</th>
	<th>{L_ICON}</th>
	<th>{L_DELETE}</th>
	<th>{L_UPDATE}</th>
	</tr>
	<!-- BEGIN total -->
	<tr>
	<td class="{total.ROWCSS}" align="center">
		<select name="r_total_1[{total.ID}]">
		<!-- BEGIN type -->
		<option value="{total.type.ID}" {total.type.SELECTED}>{total.type.LABEL}</option>
		<!-- END type -->
		</select>
	</td>
	<td class="{total.ROWCSS}" align="center"><input class="post" type="text" size="4" maxlength="4" name="r_total_2[{total.ID}]" value="{total.AVERAGE}" /></td>
	<td class="{total.ROWCSS}" align="center"><input class="post" type="text" size="5" name="r_total_3[{total.ID}]" value="{total.SUM}" /></td>
	<td class="{total.ROWCSS}"><input class="post" type="text" name="r_total_4[{total.ID}]" value="{total.LABEL}" /></td>
	<td class="{total.ROWCSS}"><input class="post" type="text" name="r_total_5[{total.ID}]" value="{total.ICON}" /></td>
	<td class="{total.ROWCSS}" align="center">{total.DELETE}</td>
	<td class="{total.ROWCSS}" align="center">{total.SUBMIT}</td>
	</tr>
	<!-- END total -->
	</table>
</td>
</tr>
</table>
</form>
<br />
{USER_ERRORS}

<form name="rating_users_form" method="post" action="{FORM_ACTION}#users">
<input type="hidden" name="rating_form_submitted" value="y">
<table cellspacing="10">
<tr>
<td>
	<table class="forumline">
	<tr><th colspan="5">{L_USER_RANKS_TITLE}</th></tr>
	<tr>
	<th>{L_BOARD_RANK}</th>
	<th>{L_AVERAGE}</th>
	<th>{L_SUM}</th>
	<th>{L_DELETE}</th>
	<th>{L_UPDATE}</th>
	</tr>
	<!-- BEGIN user -->
	<tr>
	<td class="{user.ROWCSS}" align="center">
		<select name="r_total_6[{user.ID}]">
		<!-- BEGIN rank -->
		<option value="{user.rank.ID}" {user.rank.SELECTED}>{user.rank.LABEL}</option>
		<!-- END rank -->
		</select>
		<input type="hidden" name="r_total_1[{user.ID}]" value="3" />
	</td>
	<td class="{user.ROWCSS}" align="center"><input class="post" type="text" size="4" maxlength="4" name="r_total_2[{user.ID}]" value="{user.AVERAGE}" /></td>
	<td class="{user.ROWCSS}" align="center"><input class="post" type="text" size="5" name="r_total_3[{user.ID}]" value="{user.SUM}" /></td>
	<td class="{user.ROWCSS}" align="center">{user.DELETE}</td>
	<td class="{user.ROWCSS}" align="center">{user.SUBMIT}</td>
	</tr>
	<!-- END user -->
	</table>
<a id="users"></a>
</td>
</tr>
</table>

</form>