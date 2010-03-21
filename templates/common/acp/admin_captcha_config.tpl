<h1>{L_CAPTCHA_CONFIGURATION}</h1>

<p>{L_CAPTCHA_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CAPTCHA_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_VC_ACTIVE}</th></tr>
<tr><td class="row1 row-center" colspan="2">{CAPTCHA_IMG}</td></tr>
<tr>
	<td class="row1"><b>{L_ENABLE_CONFIRM}</b><br /><span class="gensmall">{L_ENABLE_CONFIRM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_confirm" value="1" {S_ENABLE_CONFIRM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_confirm" value="0" {S_ENABLE_CONFIRM_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USE_CAPTCHA}</b><br /><span class="gensmall">{L_USE_CAPTCHA_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="use_captcha" value="1" {S_USE_CAPTCHA_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_captcha" value="0" {S_USE_CAPTCHA_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_WIDTH}</b></td>
	<td class="row2" width="50%"><input class="post" type="text" maxlength="3" size="4" name="captcha_width" value="{WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_HEIGHT}</b></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="4" name="captcha_height" value="{HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PRE_LETTERS}</b><br /><span class="gensmall">{L_PRE_LETTERS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="4" name="captcha_pre_letters" value="{PRE_LETTERS}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GREAT_PRE_LETTERS}</b><br /><span class="gensmall">{L_GREAT_PRE_LETTERS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="captcha_pre_letters_great" value="1" {S_GREAT_PRE_LETTERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_pre_letters_great" value="0" {S_GREAT_PRE_LETTERS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_RND_FONT_PER_LETTER}</b><br /><span class="gensmall">{L_RND_FONT_PER_LETTER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="captcha_font" value="1" {S_RND_FONT_PER_LETTER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_font" value="0" {S_RND_FONT_PER_LETTER_NO} /> {L_NO}</td>
</tr>
<tr><th colspan="2">{L_BACKGROUND_CONFIG}</th></tr>
<tr>
	<td class="row1"><b>{L_BACKGROUND_COLOR}</b><br /><span class="gensmall">{L_BACKGROUND_COLOR_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="7" size="8" name="captcha_background_color" value="{BACKGROUND_COLOR}" />&nbsp;<b style="background-color: {BACKGROUND_COLOR};">&nbsp;&nbsp;&nbsp;</b></td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_CHESS}</b><br /><span class="gensmall">{L_ALLOW_CHESS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="captcha_chess" value="1" {S_ALLOW_CHESS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_chess" value="0" {S_ALLOW_CHESS_NO} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="captcha_chess" value="2" {S_ALLOW_CHESS_RND} /> {L_RANDOM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_ELLIPSES}</b></td>
	<td class="row2"><input type="radio" name="captcha_ellipses" value="1" {S_ALLOW_ELLIPSES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_ellipses" value="0" {S_ALLOW_ELLIPSES_NO} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="captcha_ellipses" value="2" {S_ALLOW_ELLIPSES_RND} /> {L_RANDOM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_ARCS}</b></td>
	<td class="row2"><input type="radio" name="captcha_arcs" value="1" {S_ALLOW_ARCS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_arcs" value="0" {S_ALLOW_ARCS_NO} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="captcha_arcs" value="2" {S_ALLOW_ARCS_RND} /> {L_RANDOM}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_LINES}</b></td>
	<td class="row2"><input type="radio" name="captcha_lines" value="1" {S_ALLOW_LINES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_lines" value="0" {S_ALLOW_LINES_NO} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="captcha_lines" value="2" {S_ALLOW_LINES_RND} /> {L_RANDOM}</td>
</tr>
<!--
<tr>
	<td class="row1"><b>{L_ALLOW_IMAGE}</b><br /><span class="gensmall">{L_ALLOW_IMAGE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="captcha_image" value="1" {S_ALLOW_IMAGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="captcha_image" value="0" {S_ALLOW_IMAGE_NO} /> {L_NO}</td>
</tr>
-->
<tr><th colspan="2">{L_FOREGROUND_LATTICE}</th></tr>
<tr>
	<td class="row1"><b>{L_FOREGROUND_LATTICE}</b>&nbsp;<span class="gensmall">{L_FOREGROUND_LATTICE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="3" name="captcha_foreground_lattice_x" value="{LATTICE_X_LINES}" /> x <input class="post" type="text" maxlength="3" size="3" name="captcha_foreground_lattice_y" value="{LATTICE_Y_LINES}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_FOREGROUND_LATTICE_COLOR}</b><br /><span class="gensmall">{L_FOREGROUND_LATTICE_COLOR_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="7" size="8" name="captcha_lattice_color" value="{LATTICE_COLOR}" />&nbsp;<b style="background-color: {LATTICE_COLOR};">&nbsp;&nbsp;&nbsp;</b></td>
</tr>
<tr>
	<td class="row1"><b>{L_GAMMACORRECT}</b>&nbsp;<span class="gensmall">{L_GAMMACORRECT_EXPLAIN}</span></td>
	<td class="row2">&nbsp;1.0&nbsp;:&nbsp;<input class="post" type="text" maxlength="3" size="4" name="captcha_gammacorrect" value="{GAMMACORRECT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_GENERATE_JPEG}</b><br /><span class="gensmall">{L_GENERATE_JPEG_EXPAIN}</span></td>
	<td class="row2">&nbsp;<input type="radio" name="captcha_jpeg" value="1" {S_JPEG_IMAGE_YES} /> JPEG&nbsp;{L_JPEG_QUALITY}:&nbsp;<input class="post" type="text" maxlength="2" size="2" name="captcha_jpeg_quality" value="{JPEG_QUALITY}" />&nbsp;%<br />
	&nbsp;<input type="radio" name="captcha_jpeg" value="0" {S_JPEG_IMAGE_NO} /> PNG</td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>