<h1>{L_THEMES_TITLE}</h1>
<p>{L_THEMES_EXPLAIN}</p>

<form action="{S_THEME_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="3">{L_THEME_SETTINGS}</th></tr>
<tr>
	<td class="row1">{L_THEME_NAME}:</td>
	<td class="row2" colspan="2"><input class="post" type="text" size="25" maxlength="30" name="style_name" value="{THEME_NAME}"></td>
</tr>
<tr>
	<td class="row1">{L_TEMPLATE}:</td>
	<td class="row2" colspan="2">{S_TEMPLATE_SELECT}</td>
</tr>
<tr>
	<th>{L_THEME_ELEMENT}</th>
	<th>{L_VALUE}</th>
</tr>
<tr>
	<td class="row1">{L_STYLESHEET}:<br /><span class="gensmall">{L_STYLESHEET_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="head_stylesheet" value="{HEAD_STYLESHEET}"></td>
</tr>
<tr>
	<td class="row1">{L_BACKGROUND_IMAGE}:</td>
	<td class="row2" ><input class="post" type="text" size="25" maxlength="100" name="body_background" value="{BODY_BACKGROUND}"></td>
</tr>
<tr>
	<td class="row1">{L_BACKGROUND_COLOR}:</td>
	<td class="row2" ><input class="post" type="text" size="6" maxlength="6" name="body_bgcolor" value="{BODY_BGCOLOR}"></td>
</tr>
<tr>
	<td class="row1">{L_TR_CLASS1}:</td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="25" name="tr_class1" value="{TR_CLASS1}"></td>
</tr>
<tr>
	<td class="row1">{L_TR_CLASS2}:</td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="25" name="tr_class2" value="{TR_CLASS2}"></td>
</tr>
<tr>
	<td class="row1">{L_TR_CLASS3}:</td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="25" name="tr_class3" value="{TR_CLASS3}"></td>
</tr>
<tr>
	<td class="row1">{L_TD_CLASS1}:</td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="25" name="td_class1" value="{TD_CLASS1}"></td>
</tr>
<tr>
	<td class="row1">{L_TD_CLASS2}:</td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="25" name="td_class2" value="{TD_CLASS2}"></td>
</tr>
<tr>
	<td class="row1">{L_TD_CLASS3}:</td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="25" name="td_class3" value="{TD_CLASS3}"></td>
</tr>
<tr><td class="cat" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SAVE_SETTINGS}" class="mainoption" /></td></tr>
</table>
</form>
<br clear="all">
