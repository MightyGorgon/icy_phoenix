<h1>{L_HEADLINE}</h1>
<p>{L_SUBHEADLINE}</p>

<br />

<form action="{S_FORM_ACTION}" method="post">
<div align="center">
<table class="forumline" width="50%" cellspacing="0" cellpadding="0" border="0">
<tr><th>{L_NEW_ENTRY}</th></tr>
<!-- BEGIN deleted -->
<tr>
	<td class="row2 row-center">
		<table border="0" width="100%" cellspacing="4" cellpadding="4">
		<tr>
			<td><img src="{IMG_DELETED}" alt="{deleted.L_SUCCESSFULLY_DELETED}" title="{deleted.L_SUCCESSFULLY_DELETED}" border="0" /></td>
			<td>{deleted.L_SUCCESSFULLY_DELETED}</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END deleted -->
<!-- BEGIN added -->
<tr>
	<td class="row2 row-center">
		<table border="0" width="100%" cellspacing="4" cellpadding="4">
		<tr>
			<td><img src="{IMG_INFO}" alt="{added.L_SUCCESSFULLY_ADDED}" title="{added.L_SUCCESSFULLY_ADDED}" border="0" /></td>
			<td>{added.L_SUCCESSFULLY_ADDED}</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END added -->
	<tr><td class="row1 row-center"><input type="text" name="entry" class="post" value="" maxlength="200" size="60" /></td></tr>
	<tr><td class="cat" align="center"><input type="submit" name="submit" value="{L_ADD_NOW}" class="mainoption" /></td></tr>
</table>
</div>
</form>

<br /><br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_BLOCKLIST}</th></tr>
<!-- BEGIN ipblocker -->
<tr>
	<td width="90%" class="{ipblocker.ROW_CLASS}">{ipblocker.BLOCKER_VALUE}</td>
	<td width="10%" class="{ipblocker.ROW_CLASS} row-center"><a href="{ipblocker.BLOCKER_ID}"><img src="{ipblocker.IMG_ICON}" alt="{ipblocker.L_DELETE}" title="{ipblocker.L_DELETE}" /></a></td>
</tr>
<!-- END ipblocker -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>