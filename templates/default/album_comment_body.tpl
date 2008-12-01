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

// Startup variables
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
		&& (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
		&& (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

var baseHeight;

//var oldonload = window.onload;
//if(typeof(oldonload) == 'function')
//{
//	window.onload = function(){oldonload();initInsertions()};
//}
//else
//{
//	window.onload = function(){initInsertions()};
//}

//window.onload = initInsertions;

function initInsertions()
{
	//document.commentform.comment.focus();
	if (is_ie && typeof(baseHeight) != 'number') baseHeight = document.selection.createRange().duplicate().boundingHeight;
}

function emoticon(text)
{
	var txtarea = document.commentform.comment;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos)
	{
		if (baseHeight != txtarea.caretPos.boundingHeight)
		{
			txtarea.focus();
			storeCaret(txtarea);
		}
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		txtarea.focus();
	}
	else if ((txtarea.selectionEnd | txtarea.selectionEnd == 0) && (txtarea.selectionStart | txtarea.selectionStart == 0))
	{
		mozInsert(txtarea, text, "");
		return;
	}
	else
	{
		txtarea.value += text;
		txtarea.focus();
	}
}

// Insert at Caret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl)
{
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2)
	{
		selEnd = selLength;
	}

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	return;
}

function mozInsert(txtarea, openTag, closeTag)
{
	if (txtarea.selectionEnd > txtarea.value.length)
	{
		txtarea.selectionEnd = txtarea.value.length;
	}

	var startPos = txtarea.selectionStart;
	var endPos = txtarea.selectionEnd + openTag.length;

	txtarea.value=txtarea.value.slice(0,startPos) + openTag + txtarea.value.slice(startPos);
	txtarea.value=txtarea.value.slice(0,endPos) + closeTag + txtarea.value.slice(endPos);

	txtarea.selectionStart = startPos + openTag.length;
	txtarea.selectionEnd = endPos;
	txtarea.focus();
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

<form action="{S_ALBUM_ACTION}" method="post">
<div style="margin-top: 5px; text-align: right;">
{ALBUM_SEARCH_BOX}
</div>

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
		<textarea name="comment" class="post" cols="80" rows="9" wrap="virtual" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" style="vertical-align: top;">{S_MESSAGE}</textarea>
	</td>
	<td class="row1 row-center" valign="middle" width="25%">
		<table align="center" width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr>
			<td align="center">
			<!-- BEGIN smilies -->
				<img src="{switch_comment_post.smilies.URL}" style="padding: 2px;" border="0" onmouseover="this.style.cursor='hand';" onclick="emoticon('{switch_comment_post.smilies.CODE}');" alt="{switch_comment_post.smilies.DESC}" />
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