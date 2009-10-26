<!-- IF S_SLIDESHOW_SCRIPTS -->
<script type="text/javascript">
// <![CDATA[
// (C) 2000 www.CodeLifter.com - http://www.codelifter.com
// Free for all users, but leave in this  header

// Modified by Mighty Gorgon
// Version: 1.0.0
// Date: 2006/01/12

// Usage
// <img name="SlideShow" src="album_mod/upload/img_001.jpg" alt="Picture Des" />
// <a href="javascript:runSlideShow()">SLIDESHOW< /a>


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
// <a href="javascript:crossfade(document.getElementById('SlideShowPic'), 'album_mod/upload/img_001.jpg', '2', 'Picture Des')">NEXT< /a>
// <!-- define swapfade (image-object, 'new src', 'seconds'[, 'new alt text']) -->
// <a href="javascript:swapfade(document.getElementById('SlideShowPic'), 'album_mod/upload/img_001.jpg', '2', 'Picture Des')">NEXT< /a>
// <!-- define crosswipe (image-object, 'new src', 'seconds', 'direction'[, 'new alt text']) -->
// <a href="javascript:crosswipe(document.getElementById('SlideShowPic'), 'album_mod/upload/img_001.jpg', '2', 'Picture Des', 'lr')">NEXT< /a>

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
// ]]>
</script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/slideshow.js"></script>
<!-- ENDIF -->

<!-- IF S_SLIDESHOW -->
{S_SLIDESHOW_REFRESH}
<!-- ENDIF -->

