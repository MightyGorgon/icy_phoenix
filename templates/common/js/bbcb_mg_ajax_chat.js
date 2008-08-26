// Startup variables
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!= -1) && (clientPC.indexOf('spoofer')== -1)
		&& (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')== -1)
		&& (clientPC.indexOf('webtv')== -1) && (clientPC.indexOf('hotjava')== -1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win") != -1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac") != -1);

// Other check in vars...
var uAgent = navigator.userAgent;
var ns4 = (document.layers) ? true : false;   //NS 4
var ie4 = (document.all) ? true : false;   //IE 4
var dom = (document.getElementById) ? true : false;   //DOM
var ope = uAgent.indexOf("Opera") > -1 && dom ? true : false; // + OP5
var ie5 = (dom && ie4 && !ope) ? true : false; // IE5
var ns6 = (dom && uAgent.indexOf("Netscape") > -1) ? true : false; // + NS 6
var khtml = uAgent.indexOf("khtml") > -1 ? true : false; // + Konqueror
//alert("UserAgent: "+uAgent+"\nns4 :"+ns4+"\nie4 :"+ie4+"\ndom :"+dom+"\nie5 :"+ie5+"\nns6 :"+ns6+"\nope :"+ope+"\nkhtml :"+khtml);

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
	var txtarea = document.chatForm.chatbarText;
	txtarea.focus();
	if (is_ie && (typeof(baseHeight) != 'number') )
	{
		baseHeight = document.selection.createRange().duplicate().boundingHeight;
	}
}

function checkForm()
{
	var txtarea = document.chatForm.chatbarText;
	formErrors = false;
	if (txtarea.value.length < 2)
	{
		alert('No Text');
		return false;
	}
	else
	{
		return true;
	}
}

function emoticon(text)
{
	var txtarea = document.chatForm.chatbarText;
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

function PostWrite(text)
{
	var txtarea = document.chatForm.chatbarText;
	txtarea.focus();
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

// From http://www.massless.org/mozedit/
function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if ( (selEnd == 1) || (selEnd == 2) )
	{
		selEnd = selLength;
	}

	var s1 = (txtarea.value).substring(0, selStart);
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

	txtarea.value=txtarea.value.slice(0, startPos) + openTag + txtarea.value.slice(startPos);
	txtarea.value=txtarea.value.slice(0, endPos) + closeTag + txtarea.value.slice(endPos);

	txtarea.selectionStart = startPos + openTag.length;
	txtarea.selectionEnd = endPos;
	txtarea.focus();
}

// Insert at Claret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl)
{
	if (textEl.createTextRange)
	{
		textEl.caretPos = document.selection.createRange().duplicate();
	}
}

// Mighty Gorgon - Highlight/Copy
function copymetasearch()
{
	document.post.message.select();
	document.post.message.focus();
	if ( (navigator.appName=="Microsoft Internet Explorer") && (parseInt(navigator.appVersion)>=4) )
	{
		textRange = document.post.message.createTextRange();
		textRange.execCommand("RemoveFormat");
		textRange.execCommand("Copy");
		// alert("Text copied to clipboard");
	}
}
// Mighty Gorgon - Highlight/Copy

// Div Expand js
function selectAll(elementId)
{
	var element = document.getElementById(elementId);
	if ( document.selection )
	{
		var range = document.body.createTextRange();
		range.moveToElementText(element);
		range.select();
	}
	if ( window.getSelection )
	{
		var range = document.createRange();
		range.selectNodeContents(element);
		var blockSelection = window.getSelection();
		blockSelection.removeAllRanges();
		blockSelection.addRange(range);
	}
}

function BBC_Tag_Add(tag)
{
	var txtarea = document.chatForm.chatbarText;

	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text;
		if (theSelection != '')
		{
			document.selection.createRange().text = "[" + tag + "]" + theSelection + "[/" + tag + "]";
			txtarea.focus();
			return;
		}
	}
	else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
	{
		//mozWrap(txtarea, "[" + tag + "]", "[/" + tag + "]");
		mozInsert(txtarea, "[" + tag + "]", "[/" + tag + "]");
		return;
	}
	ToAdd = "[" + tag + "][/" + tag + "]";
	PostWrite(ToAdd);
}
