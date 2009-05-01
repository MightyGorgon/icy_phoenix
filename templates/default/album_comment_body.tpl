<script type="text/javascript">
// <![CDATA[
var form_name_original = form_name;
var text_name_original = text_name;
var form_name_thisform = 'commentform';
var text_name_thisform = 'comment';

{JAVASCRIPT_LANG_VARS}

s_help = "{L_BBCODE_S_HELP}";
s_s_help = "{L_BBCODE_S_HELP}";

var bbcb_mg_img_path = "{BBCB_MG_PATH_PREFIX}images/bbcb_mg/images/gif/";
var bbcb_mg_img_ext = ".gif";

function bbcb_vars_reassign_start()
{
	form_name = form_name_thisform;
	text_name = text_name_thisform;
}

function bbcb_vars_reassign_end()
{
	form_name = form_name_original;
	text_name = text_name_original;
}
// ]]>
</script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg.js"></script>

<script type="text/javascript">
<!--
function checkForm()
{
	formErrors = false;
	if ((document.commentform.comment.value.length < 2) && (document.commentform.rate.value == -1))
	{
		formErrors = "{L_COMMENT_NO_TEXT}";
	}
	else if (document.commentform.comment.value.length > {S_MAX_LENGTH})
	{
		formErrors = "{L_COMMENT_TOO_LONG}";
	}

	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}

function checkFormRate()
{
	formErrors = false;
	if (document.ratingform.rating.value == -1)
	{
		formErrors = "Per favore inserisci la tua valutazione";
	}

	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}

//pops up a window with all smilies
function openAllSmiles()
{
	smiles = window.open('album_showpage.php?mode=smilies','_phpbbsmilies','height=600,width=470,resizable=yes,scrollbars=yes');
	smiles.focus();
	return true;
}
// -->
</script>

<div style="margin-top: 5px; text-align: right;">
{ALBUM_SEARCH_BOX}
</div>

<form action="{S_ALBUM_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{PIC_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center"><img src="{U_PIC}" border="0" vspace="10" alt="{PIC_TITLE}" title="{PIC_TITLE}" /></td></tr>
<tr>
	<td class="row2">
		<table width="90%" align="center" border="0" cellpadding="3" cellspacing="2">
		<tr>
			<td width="25%" align="right"><span class="genmed">{L_POSTER}:</span></td>
			<td><span class="genmed"><b>{POSTER}</b></span></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_PIC_TITLE}:</span></td>
			<td valign="top"><b><span class="genmed">{PIC_TITLE}</span></b></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_PIC_ID}:</span></td>
			<td valign="top"><b><span class="genmed">{PIC_ID}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_POSTED}:</span></td>
			<td><b><span class="genmed">{PIC_TIME}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_VIEW}:</span></td>
			<td><b><span class="genmed">{PIC_VIEW}</span></b></td>
		</tr>
		<!-- BEGIN rate_switch -->
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_RATING}:</span></td>
			<td><b><span class="genmed">{PIC_RATING}</span></b></td>
		</tr>
		<!-- END rate_switch -->
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_PIC_DESC}:</span></td>
			<td valign="top"><b><span class="genmed">{PIC_DESC}</span></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- BEGIN switch_comment_post -->
<form name="commentform" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkForm();">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_POST_YOUR_COMMENT}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" valign="top" width="20%" nowrap="nowrap">
		<span class="gen">{L_MESSAGE}</span><br /><span class="genmed">
		{L_MAX_LENGTH}: <b>{S_MAX_LENGTH}</b></span>
	</td>
	<td class="row1" valign="top">
		<textarea name="comment" class="post" cols="80" rows="9" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" style="vertical-align: top;">{S_MESSAGE}</textarea>
	</td>
	<td class="row1 row-center" valign="middle" width="25%">
		<table align="center" width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr>
			<td align="center">
			<!-- BEGIN smilies -->
				<img src="{switch_comment_post.smilies.URL}" style="padding: 2px;" border="0" onmouseover="this.style.cursor='pointer';" onclick="javascript:bbcb_vars_reassign_start();emoticon('{switch_comment_post.smilies.CODE}');bbcb_vars_reassign_end();" alt="{switch_comment_post.smilies.DESC}" />
			<!-- END smilies -->
			</td>
		</tr>
		</table>
		<input type="button" class="post" name="smiles_button" value="Smileys" onclick="openAllSmiles();" />
	</td>
</tr>

<tr><td class="cat" align="center" colspan="3"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END switch_comment_post -->
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}