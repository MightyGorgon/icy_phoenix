<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_blocks.png" alt="{L_BLOCKS_PAGE_01}" title="{L_BLOCKS_PAGE_01}" /></td>
	<td class="row1" valign="top"><h1>{L_BLOCKS_PAGE_01}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_BLOCK}</th></tr>
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="row1">{L_B_TITLE}</td>
			<td class="row2"><input type="text" maxlength="60" size="30" name="title" value="{TITLE}" class="post" /></td>
		</tr>
		<tr>
			<td class="row1">{L_B_POSITION}</td>
			<td class="row2"><select name="bposition" class="post">{POSITION}</select></td>
		</tr>
		<tr>
			<td class="row1">{L_B_ACTIVE}</td>
			<td class="row2">
				<input type="radio" name="active" value="1" {ACTIVE} /> {L_ENABLED}&nbsp;&nbsp;
				<input type="radio" name="active" value="0" {NOT_ACTIVE} /> {L_DISABLED}
			</td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_BLOCK_FILE}</td>
			<td class="row2"><select name="blockfile" class="post">{BLOCKFILE}</select></td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_VIEW_BY}</td>
			<td class="row2"><select name="view" class="post">{VIEWBY}</select></td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_BORDER}</td>
			<td class="row2">
				<input type="radio" name="border" value="1" {BORDER} /> {L_YES}&nbsp;&nbsp;
				<input type="radio" name="border" value="0" {NO_BORDER} /> {L_NO}
			</td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_TITLEBAR}</td>
			<td class="row2">
				<input type="radio" name="titlebar" value="1" {TITLEBAR} /> {L_YES}&nbsp;&nbsp;
				<input type="radio" name="titlebar" value="0" {NO_TITLEBAR} /> {L_NO}
			</td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_LOCAL}</td>
			<td class="row2">
				<input type="radio" name="local" value="1" {LOCAL} /> {L_YES}&nbsp;&nbsp;
				<input type="radio" name="local" value="0" {NOT_LOCAL} /> {L_NO}
			</td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_BACKGROUND}</td>
			<td class="row2">
				<input type="radio" name="background" value="1" {BACKGROUND} /> {L_YES}&nbsp;&nbsp;
				<input type="radio" name="background" value="0" {NO_BACKGROUND} /> {L_NO}
			</td>
		</tr>
		<tr>
			<td class="row1" align="right">{L_B_GROUP}</td>
			<td class="row2">{GROUP}</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="block_content_setup" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;
		<input type="submit" name="reset" class="liteoption" value="{L_RESET}" />
		<!-- <input type="reset" name="reset" class="liteoption" value="{L_RESET}" /> -->
	</td>
</tr>
</table>
</form>