/*
CLEditor BBCode Plugin v1.0.0
http://premiumsoftware.net/cleditor
requires CLEditor v1.3.0 or later

Copyright 2010, Chris Landowski, Premium Software, LLC
Dual licensed under the MIT or GPL Version 2 licenses.
*/
(function(b){b.cleditor.defaultOptions.controls="bold italic underline strikethrough removeformat | bullets numbering | undo redo | image link unlink | cut copy paste pastetext | print source";var d=b.cleditor.defaultOptions.updateTextArea,e=b.cleditor.defaultOptions.updateFrame;b.cleditor.defaultOptions.updateTextArea=function(a){if(d)a=d(a);return b.cleditor.convertHTMLtoBBCode(a)};b.cleditor.defaultOptions.updateFrame=function(a){if(e)a=e(a);return b.cleditor.convertBBCodeToHTML(a)};b.cleditor.convertHTMLtoBBCode=
function(a){b.each([[/[\r|\n]/g,""],[/<br.*?>/gi,"\n"],[/<b>(.*?)<\/b>/gi,"[b]$1[/b]"],[/<strong>(.*?)<\/strong>/gi,"[b]$1[/b]"],[/<i>(.*?)<\/i>/gi,"[i]$1[/i]"],[/<em>(.*?)<\/em>/gi,"[i]$1[/i]"],[/<u>(.*?)<\/u>/gi,"[u]$1[/u]"],[/<ins>(.*?)<\/ins>/gi,"[u]$1[/u]"],[/<strike>(.*?)<\/strike>/gi,"[s]$1[/s]"],[/<del>(.*?)<\/del>/gi,"[s]$1[/s]"],[/<a.*?href="(.*?)".*?>(.*?)<\/a>/gi,"[url=$1]$2[/url]"],[/<img.*?src="(.*?)".*?>/gi,"[img]$1[/img]"],[/<ul>/gi,"[list]"],[/<\/ul>/gi,"[/list]"],[/<ol>/gi,"[list=1]"],
[/<\/ol>/gi,"[/list]"],[/<li>/gi,"[*]"],[/<\/li>/gi,"[/*]"],[/<.*?>(.*?)<\/.*?>/g,"$1"]],function(f,c){a=a.replace(c[0],c[1])});return a};b.cleditor.convertBBCodeToHTML=function(a){b.each([[/\r/g,""],[/\n/g,"<br />"],[/\[b\](.*?)\[\/b\]/gi,"<b>$1</b>"],[/\[i\](.*?)\[\/i\]/gi,"<i>$1</i>"],[/\[u\](.*?)\[\/u\]/gi,"<u>$1</u>"],[/\[s\](.*?)\[\/s\]/gi,"<strike>$1</strike>"],[/\[url=(.*?)\](.*?)\[\/url\]/gi,'<a href="$1">$2</a>'],[/\[img\](.*?)\[\/img\]/gi,'<img src="$1">'],[/\[list\](.*?)\[\/list\]/gi,
"<ul>$1</ul>"],[/\[list=1\](.*?)\[\/list\]/gi,"<ol>$1</ol>"],[/\[list\]/gi,"<ul>"],[/\[list=1\]/gi,"<ol>"],[/\[\*\](.*?)\[\/\*\]/g,"<li>$1</li>"],[/\[\*\]/g,"<li>"]],function(f,c){a=a.replace(c[0],c[1])});return a}})(jQuery);