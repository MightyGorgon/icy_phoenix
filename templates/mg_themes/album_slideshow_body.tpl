<!-- BEGIN switch_slideshow -->
{U_SLIDESHOW_REFRESH}
<!-- END switch_slideshow -->

<!-- BEGIN switch_slideshow_scripts -->
<style type="text/css" media="screen, projection">
	img {
		filter: alpha(opacity=100);
		border: none;
	}

	/* duplicate image positioning */
	img.idupe {
		position: absolute;
		z-index: 30000;
		visibility: hidden;
	}
</style>

<script type="text/javascript">
// (C) 2000 www.CodeLifter.com - http://www.codelifter.com
// Free for all users, but leave in this  header

// Modified by Mighty Gorgon
// Version: 1.0.0
// Date: 2006/01/12

// Usage
// <img name="SlideShow" src="album_mod/upload/img_001.jpg" alt="Picture Des" />
// <a href="javascript:runSlideShow()">SLIDESHOW</a>


// *****************************************************
// SlideShow Transiction FX
//******************************************************
// *****************************************************
// DOM scripting by brothercake - http://www.brothercake.com/
//******************************************************
// *****************************************************
// Edited by Mighty Gorgon - http://www.mightygorgon.com/
//******************************************************

// Usage
// <img id="SlideShow" src="album_mod/upload/img_001.jpg" alt="Picture Des" />
// <!-- define crossfade (image-object, 'new src', 'seconds'[, 'new alt text']) -->
// <a href="javascript:crossfade(document.getElementById('SlideShowPic'), 'album_mod/upload/img_001.jpg', '2', 'Picture Des')">NEXT</a>
// <!-- define swapfade (image-object, 'new src', 'seconds'[, 'new alt text']) -->
// <a href="javascript:swapfade(document.getElementById('SlideShowPic'), 'album_mod/upload/img_001.jpg', '2', 'Picture Des')">NEXT</a>
// <!-- define crosswipe (image-object, 'new src', 'seconds', 'direction'[, 'new alt text']) -->
// <a href="javascript:crosswipe(document.getElementById('SlideShowPic'), 'album_mod/upload/img_001.jpg', '2', 'Picture Des', 'lr')">NEXT</a>

//global object
var ssfx = { 'clock' : null, 'fade' : true, 'count' : 1 }

// SlideShow Speed (seconds)
var slideShowSpeed = {SLIDESHOW_DELAY};

// Duration of crossfade (seconds)
var FXDuration = 3;

// Images Array
var Pic = new Array();
var Tit = new Array();
var Des = new Array();

{PIC_LIST}
{TIT_LIST}
{DES_LIST}

var t;
var rnd_nmb = 0;
var i = 0;
var j = 0;
var p = Pic.length;

ssfx.cache = [];

for (i = 0; i < p; i++)
{
	ssfx.cache[i] = new Image;
	ssfx.cache[i].src = Pic[i];
}

function runSlideShow()
{
	rnd_nmb = (Math.round((Math.random() + 0.5) * 4) - 1);
	//rnd_nmb = 0;
	switch(rnd_nmb)
	{
		case 1:
			crossfade(document.getElementById('SlideShowPic'), ssfx.cache[j].src, FXDuration, Tit[j], 'lr');
			break;

		case 2:
			swapfade(document.getElementById('SlideShowPic'), ssfx.cache[j].src, FXDuration, Tit[j], 'lr');
			break;

		case 3:
			crosswipe(document.getElementById('SlideShowPic'), ssfx.cache[j].src, FXDuration, Tit[j], 'lr');
			break;

		default:
			if (document.all)
			{
				document.getElementById('SlideShowPic').style.filter="blendTrans(duration=FXDuration)";
				document.getElementById('SlideShowPic').filters.blendTrans.Apply();
			}
			document.getElementById('SlideShowPic').src = ssfx.cache[j].src;
			if (document.all)
			{
				document.getElementById('SlideShowPic').filters.blendTrans.Play();
			}
	}

	document.getElementById('PicHeader').innerHTML = Tit[j];
	document.getElementById('PicTitle').innerHTML = Tit[j];
	document.getElementById('PicDes').innerHTML = Des[j];

	j = j + 1;
	if (j > (p - 1))
	{
		j = 0;
	}

	t = setTimeout('runSlideShow()', (slideShowSpeed * 1000));
}


//swapfade setup function
function swapfade()
{
	//if the timer is not already going
	if(ssfx.clock == null)
	{
		//copy the image object
		ssfx.obj = arguments[0];

		//copy the image src argument
		ssfx.src = arguments[1];

		//store the supported form of opacity
		if(typeof ssfx.obj.style.opacity != 'undefined')
		{
			ssfx.type = 'w3c';
		}
		else if(typeof ssfx.obj.style.MozOpacity != 'undefined')
		{
			ssfx.type = 'moz';
		}
		else if(typeof ssfx.obj.style.KhtmlOpacity != 'undefined')
		{
			ssfx.type = 'khtml';
		}
		else if(typeof ssfx.obj.filters == 'object')
		{
			//weed out win/ie5.0 by testing the length of the filters collection (where filters is an object with no data)
			//then weed out mac/ie5 by testing first the existence of the alpha object (to prevent errors in win/ie5.0)
			//then the returned value type, which should be a number, but in mac/ie5 is an empty string
			ssfx.type = (ssfx.obj.filters.length > 0 && typeof ssfx.obj.filters.alpha == 'object' && typeof ssfx.obj.filters.alpha.opacity == 'number') ? 'ie' : 'none';
		}
		else
		{
			ssfx.type = 'none';
		}

		//change the image alt text if defined
		if(typeof arguments[3] != 'undefined' && arguments[3] != '')
		{
			ssfx.obj.alt = arguments[3];
		}

		//if any kind of opacity is supported
		if(ssfx.type != 'none')
		{
			//copy and convert fade duration argument
			//the duration specifies the whole transition
			//but the swapfade is two distinct transitions
			ssfx.length = parseInt(arguments[2], 10) * 500;

			//create fade resolution argument as 20 steps per transition
			//again, split for the two distrinct transitions
			ssfx.resolution = parseInt(arguments[2], 10) * 10;

			//start the timer
			ssfx.clock = setInterval('ssfx.swapfade()', ssfx.length/ssfx.resolution);
		}

		//otherwise if opacity is not supported
		else
		{
			//just do the image swap
			ssfx.obj.src = ssfx.src;
		}

	}
};


//swapfade timer function
ssfx.swapfade = function()
{
	//increase or reduce the counter on an exponential scale
	ssfx.count = (ssfx.fade) ? ssfx.count * 0.9 : (ssfx.count * (1/0.9));

	//if the counter has reached the bottom
	if(ssfx.count < (1 / ssfx.resolution))
	{
		//clear the timer
		clearInterval(ssfx.clock);
		ssfx.clock = null;

		//do the image swap
		ssfx.obj.src = ssfx.src;

		//reverse the fade direction flag
		ssfx.fade = false;

		//restart the timer
		ssfx.clock = setInterval('ssfx.swapfade()', ssfx.length/ssfx.resolution);

	}

	//if the counter has reached the top
	if(ssfx.count > (1 - (1 / ssfx.resolution)))
	{
		//clear the timer
		clearInterval(ssfx.clock);
		ssfx.clock = null;

		//reset the fade direction flag
		ssfx.fade = true;

		//reset the counter
		ssfx.count = 1;
	}

	//set new opacity value on element
	//using whatever method is supported
	switch(ssfx.type)
	{
		case 'ie' :
			ssfx.obj.filters.alpha.opacity = ssfx.count * 100;
			break;

		case 'khtml' :
			ssfx.obj.style.KhtmlOpacity = ssfx.count;
			break;

		case 'moz' :
			//restrict max opacity to prevent a visual popping effect in firefox
			ssfx.obj.style.MozOpacity = (ssfx.count == 1 ? 0.9999999 : ssfx.count);
			break;

		default :
			//restrict max opacity to prevent a visual popping effect in firefox
			ssfx.obj.style.opacity = (ssfx.count == 1 ? 0.9999999 : ssfx.count);
	}
};



//crossfade setup function
function crossfade()
{
	//if the timer is not already going
	if(ssfx.clock == null)
	{
		//copy the image object
		ssfx.obj = arguments[0];

		//copy the image src argument
		ssfx.src = arguments[1];

		//store the supported form of opacity
		if(typeof ssfx.obj.style.opacity != 'undefined')
		{
			ssfx.type = 'w3c';
		}
		else if(typeof ssfx.obj.style.MozOpacity != 'undefined')
		{
			ssfx.type = 'moz';
		}
		else if(typeof ssfx.obj.style.KhtmlOpacity != 'undefined')
		{
			ssfx.type = 'khtml';
		}
		else if(typeof ssfx.obj.filters == 'object')
		{
			//weed out win/ie5.0 by testing the length of the filters collection (where filters is an object with no data)
			//then weed out mac/ie5 by testing first the existence of the alpha object (to prevent errors in win/ie5.0)
			//then the returned value type, which should be a number, but in mac/ie5 is an empty string
			ssfx.type = (ssfx.obj.filters.length > 0 && typeof ssfx.obj.filters.alpha == 'object' && typeof ssfx.obj.filters.alpha.opacity == 'number') ? 'ie' : 'none';
		}
		else
		{
			ssfx.type = 'none';
		}

		//change the image alt text if defined
		if(typeof arguments[3] != 'undefined' && arguments[3] != '')
		{
			ssfx.obj.alt = arguments[3];
		}

		//if any kind of opacity is supported
		if(ssfx.type != 'none')
		{
			//create a new image object and append it to body
			//detecting support for namespaced element creation, in case we're in the XML DOM
			ssfx.newimg = document.getElementsByTagName('body')[0].appendChild((typeof document.createElementNS != 'undefined') ? document.createElementNS('http://www.w3.org/1999/xhtml', 'img') : document.createElement('img'));

			//set positioning classname
			ssfx.newimg.className = 'idupe';

			//set src to new image src
			ssfx.newimg.src = ssfx.src

			//move it to superimpose original image
			ssfx.newimg.style.left = ssfx.getRealPosition(ssfx.obj, 'x') + 'px';
			ssfx.newimg.style.top = ssfx.getRealPosition(ssfx.obj, 'y') + 'px';

			//copy and convert fade duration argument
			ssfx.length = parseInt(arguments[2], 10) * 1000;

			//create fade resolution argument as 20 steps per transition
			ssfx.resolution = parseInt(arguments[2], 10) * 20;

			//start the timer
			ssfx.clock = setInterval('ssfx.crossfade()', ssfx.length/ssfx.resolution);
		}

		//otherwise if opacity is not supported
		else
		{
			//just do the image swap
			ssfx.obj.src = ssfx.src;
		}

	}
};


//crossfade timer function
ssfx.crossfade = function()
{
	//decrease the counter on a linear scale
	ssfx.count -= (1 / ssfx.resolution);

	//if the counter has reached the bottom
	if(ssfx.count < (1 / ssfx.resolution))
	{
		//clear the timer
		clearInterval(ssfx.clock);
		ssfx.clock = null;

		//reset the counter
		ssfx.count = 1;

		//set the original image to the src of the new image
		ssfx.obj.src = ssfx.src;
	}

	//set new opacity value on both elements
	//using whatever method is supported
	switch(ssfx.type)
	{
		case 'ie' :
			ssfx.obj.filters.alpha.opacity = ssfx.count * 100;
			ssfx.newimg.filters.alpha.opacity = (1 - ssfx.count) * 100;
			break;

		case 'khtml' :
			ssfx.obj.style.KhtmlOpacity = ssfx.count;
			ssfx.newimg.style.KhtmlOpacity = (1 - ssfx.count);
			break;

		case 'moz' :
			//restrict max opacity to prevent a visual popping effect in firefox
			ssfx.obj.style.MozOpacity = (ssfx.count == 1 ? 0.9999999 : ssfx.count);
			ssfx.newimg.style.MozOpacity = (1 - ssfx.count);
			break;

		default :
			//restrict max opacity to prevent a visual popping effect in firefox
			ssfx.obj.style.opacity = (ssfx.count == 1 ? 0.9999999 : ssfx.count);
			ssfx.newimg.style.opacity = (1 - ssfx.count);
	}

	//now that we've gone through one fade iteration
	//we can show the image that's fading in
	ssfx.newimg.style.visibility = 'visible';

	//keep new image in position with original image
	//in case text size changes mid transition or something
	ssfx.newimg.style.left = ssfx.getRealPosition(ssfx.obj, 'x') + 'px';
	ssfx.newimg.style.top = ssfx.getRealPosition(ssfx.obj, 'y') + 'px';

	//if the counter is at the top, which is just after the timer has finished
	if(ssfx.count == 1)
	{
		//remove the duplicate image
		ssfx.newimg.parentNode.removeChild(ssfx.newimg);
	}
};



//crosswipe setup function
function crosswipe()
{
	//if the timer is not already going
	if(ssfx.clock == null)
	{
		//copy the image object
		ssfx.obj = arguments[0];

		//get its dimensions
		ssfx.size = { 'w' : ssfx.obj.width, 'h' : ssfx.obj.height };

		//copy the image src argument
		ssfx.src = arguments[1];

		//change the image alt text if defined
		if(typeof arguments[3] != 'undefined' && arguments[4] != '')
		{
			ssfx.obj.alt = arguments[3];
		}

		//if dynamic element creation is supported
		if(typeof document.createElementNS != 'undefined' || typeof document.createElement != 'undefined')
		{
			//create a new image object and append it to body
			//detecting support for namespaced element creation, in case we're in the XML DOM
			ssfx.newimg = document.getElementsByTagName('body')[0].appendChild((typeof document.createElementNS != 'undefined') ? document.createElementNS('http://www.w3.org/1999/xhtml', 'img') : document.createElement('img'));

			//set positioning classname
			ssfx.newimg.className = 'idupe';

			//set src to new image src
			ssfx.newimg.src = ssfx.src

			//move it to superimpose original image
			ssfx.newimg.style.left = ssfx.getRealPosition(ssfx.obj, 'x') + 'px';
			ssfx.newimg.style.top = ssfx.getRealPosition(ssfx.obj, 'y') + 'px';

			//set it to be completely hidden with clip
			ssfx.newimg.style.clip = 'rect(0, 0, 0, 0)';

			//show the image
			ssfx.newimg.style.visibility = 'visible';

			//copy and convert fade duration argument
			ssfx.length = parseInt(arguments[2], 10) * 1000;

			//create fade resolution argument as 20 steps per transition
			ssfx.resolution = parseInt(arguments[2], 10) * 20;

			//copy slide direction argument
			ssfx.dir = arguments[4];

			//start the timer
			ssfx.clock = setInterval('ssfx.crosswipe()', ssfx.length/ssfx.resolution);
		}

		//otherwise if dynamic element creation is not supported
		else
		{
			//just do the image swap
			ssfx.obj.src = ssfx.src;
		}

	}
};


//crosswipe timer function
ssfx.crosswipe = function()
{
	//decrease the counter on a linear scale
	ssfx.count -= (1 / ssfx.resolution);

	//if the counter has reached the bottom
	if(ssfx.count < (1 / ssfx.resolution))
	{
		//clear the timer
		clearInterval(ssfx.clock);
		ssfx.clock = null;

		//reset the counter
		ssfx.count = 1;

		//set the original image to the src of the new image
		ssfx.obj.src = ssfx.src;
	}

	//animate the clip of the new image
	//using the width and height properties we saved earlier
	ssfx.newimg.style.clip = 'rect('
		+ ( (/bt|bltr|brtl/.test(ssfx.dir)) ? (ssfx.size.h * ssfx.count) : (/che|cc/.test(ssfx.dir)) ? ((ssfx.size.h * ssfx.count) / 2) : (0) )
		+ 'px, '
		+ ( (/lr|tlbr|bltr/.test(ssfx.dir)) ? (ssfx.size.w - (ssfx.size.w * ssfx.count)) : (/cve|cc/.test(ssfx.dir)) ? (ssfx.size.w - ((ssfx.size.w * ssfx.count) / 2)) : (ssfx.size.w) )
		+ 'px, '
		+ ( (/tb|tlbr|trbl/.test(ssfx.dir)) ? (ssfx.size.h - (ssfx.size.h * ssfx.count)) : (/che|cc/.test(ssfx.dir)) ? (ssfx.size.h - ((ssfx.size.h * ssfx.count) / 2)) : (ssfx.size.h) )
		+ 'px, '
		+ ( (/lr|tlbr|bltr/.test(ssfx.dir)) ? (0) : (/tb|bt|che/.test(ssfx.dir)) ? (0) : (/cve|cc/.test(ssfx.dir)) ? ((ssfx.size.w * ssfx.count) / 2) : (ssfx.size.w * ssfx.count) )
		+ 'px)';

	//keep new image in position with original image
	//in case text size changes mid transition or something
	ssfx.newimg.style.left = ssfx.getRealPosition(ssfx.obj, 'x') + 'px';
	ssfx.newimg.style.top = ssfx.getRealPosition(ssfx.obj, 'y') + 'px';

	//if the counter is at the top, which is just after the timer has finished
	if(ssfx.count == 1)
	{
		//remove the duplicate image
		ssfx.newimg.parentNode.removeChild(ssfx.newimg);
	}
};



//get real position method
ssfx.getRealPosition = function()
{
	this.pos = (arguments[1] == 'x') ? arguments[0].offsetLeft : arguments[0].offsetTop;
	this.tmp = arguments[0].offsetParent;
	while(this.tmp != null)
	{
		this.pos += (arguments[1] == 'x') ? this.tmp.offsetLeft : this.tmp.offsetTop;
		this.tmp = this.tmp.offsetParent;
	}

	return this.pos;
};
</script>
<!-- END switch_slideshow_scripts -->

{IMG_THL}{IMG_THC}<span class="forumlink"><span id="PicHeader">{PIC_TITLE}</span></span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" align="center">
		<form name="slideshow" action="{U_SLIDESHOW}" method="post" onsubmit="return true;">
			<input type="submit" class="button" value="{L_SLIDESHOW_ONOFF}" style="width: 100px" /><br />
			<!-- BEGIN switch_slideshow_no_scripts -->
			<span class="genmed"><br /><b>{PIC_COUNT}</b></span><br />
			<!-- END switch_slideshow_no_scripts -->
			{U_PIC_L1}<div class="center-block"><img id="SlideShowPic" src="{U_PIC}" border="0" vspace="10" alt="{PIC_TITLE}" title="{PIC_TITLE}" /></div>{U_PIC_L2}
		</form>
		<span class="genmed">{U_PIC_CLICK}</span>
	</td>
</tr>
<tr>
	<td align="center" width="100%">
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="2" class="empty-table">
			<div align="center">
			<!-- BEGIN switch_slideshow_no_scripts -->
			<tr>
				<td width="50%" align="right"><span class="genmed">{L_POSTER}:</span></td>
				<td align="left"><span class="genmed"><b>{POSTER}</b></span></td>
			</tr>
			<!-- END switch_slideshow_no_scripts -->
			<tr>
				<td width="50%" valign="top" align="right"><span class="genmed">{L_PIC_TITLE}:</span></td>
				<td align="left" valign="top"><b><span id="PicTitle" class="genmed">{PIC_TITLE}</span></b></td>
			</tr>
			<!-- BEGIN switch_slideshow_no_scripts -->
			<tr>
				<td width="50%" valign="top" align="right"><span class="genmed">{L_PIC_DETAILS}:</span></td>
				<td valign="top" align="left"><b><span class="genmed">{L_PIC_ID}:&nbsp;{PIC_ID}&nbsp;-&nbsp;{L_PIC_TYPE}:&nbsp;{PIC_TYPE}&nbsp;-&nbsp;{L_PIC_SIZE}:&nbsp;{PIC_SIZE}</span></b></td>
			</tr>
			<tr>
				<td width="50%" align="right"><span class="genmed">{L_POSTED}:</span></td>
				<td align="left"><b><span class="genmed">{PIC_TIME}</span></b></td>
			</tr>
			<tr>
				<td width="50%" align="right"><span class="genmed">{L_VIEW}:</span></td>
				<td align="left"><b><span class="genmed">{PIC_VIEW}</span></b></td>
			</tr>
			<!-- END switch_slideshow_no_scripts -->
			<tr>
				<td width="50%" valign="top" align="right"><span class="genmed">{L_PIC_DESC}:</span></td>
				<td align="left" valign="top"><b><span id="PicDes" class="genmed">{PIC_DESC}</span></b></td>
			</tr>
			</div>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}