// mooShow 1.04
// (c)2006 Stuart Eaton - http://www.eatpixels.com
//
// Credit where credit is due: Inspiration from Lightbox (http://www.huddletogether.com/projects/lightbox2/)
// and Couloir (http://www.couloir.org/js_slideshow/) and of course moo.fx (http://moofx.mad4milk.net/
// moo.fx and prototype are covered by their own respective license terms.

// --------------------------------------------------------------------------------------------------------------

var loadingImage = "images/album/fap_loading.gif";		// loading image

// --------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------

var mooShows = null;


	// -----------------------------------------------------------------------------------

	function start()
	{
		// find any mooshows on the page
		find_mooshows();
	}

	// -----------------------------------------------------------------------------------

	function find_mooshows()
	{
		shows = document.getElementsByClassName("mooshow"); // find divs using the 'mooshow' class
		if(shows.length == 0)
		{
			shows = showsIE; // If can't find shows list get it from html page (hack for IE5.5)
		}
		mooShows = new Object();
		for ( var i = 0; i < shows.length; i++ )
		{
			showName = shows[i].id; // get shownames
			if(showName==null)
			{
				showName = shows[i]; // if we are getting show names from the html page (hack for IE5.5)
			}
			mooShows[ showName ] = new mooshow(showName); // create new mooshow objects
			create_mooshow(showName,i); // create the shows htmls
		}
	}

	// -----------------------------------------------------------------------------------

	function create_mooshow( showName,shownumber )
	{
		eval($(showName).innerHTML); //get slideshow settings
		photoArray = eval(showName); // set up image array based on id
		shownumber++; // add 1 to shownumber so it starts on 1 not 0

		if(this.dropShadow == 'yes')
		{
			this.outerContainerClass = 'mooshow_outerContainer dropShadowBorder';
		}
		else
		{
			this.outerContainerClass = 'mooshow_outerContainer';
		}

		var mooShow_html = '' +
		'<div id=\'' + showName + '_outerContainer\' class=\'' + this.outerContainerClass + '\' style=\'padding:' + this.border + 'px;\'> \n' +
		'	<div id=\'' + showName + '_topNav\' style=\'width:' + photoArray[0][1] + 'px;\' class=\'mooshow_topNav\'></div> \n' +
		'	<div id=\'' + showName + '_contentContainer\' class=\'mooshow_contentContainer\' > \n' +
		'		<img src=\'' + photoArray[0][0] + '\' class=\'mooshow_image\' width=\'' + photoArray[0][1] + '\' height=\'' + photoArray[0][2] + '\' id=\'' + showName + '_image\' /> \n' +
		'		<img src=\'' + loadingImage + '\' id=\'' + showName + '_loading\' class=\'mooshow_loading\' /> \n' +
		'		<div id=\'' + showName + '_copyright\' class=\'mooshow_copyright\'></div> \n' +
		'		<div id=\'' + showName + '_overlayNav\' class=\'mooshow_overlayNav\' style=\'height:' + photoArray[0][2] + 'px; width:' + photoArray[0][1] + 'px;\'> \n' +
		'			<a href=\'#' + shownumber + '\' id=\'' + showName + '_prevLink\' class=\'mooshow_prevLink\' onClick=\'mooShows[&#39;' + showName + '&#39;].prevImage();\' ></a> \n' +
		'			<a href=\'#' + shownumber + '\' id=\'' + showName + '_nextLink\' class=\'mooshow_nextLink\' onClick=\'mooShows[&#39;' + showName + '&#39;].nextImage();\' ></a> \n' +
		'		</div> \n' +
		'		<div id=\'' + showName + '_IPTC\' class=\'mooshow_IPTC\' style=\'width:' + photoArray[0][1] + 'px;\'></div> \n' +
		'		<a href=\'#' + shownumber + '\' ><img src=\'images/album/fap_info.gif\' id=\'' + showName + '_IPTCbutton\' class=\'mooshow_IPTCbutton\' onClick=\'mooShows[&#39;' + showName + '&#39;].updateIPTCinfoToggle();\' /></a> \n' +
		'	</div> \n' +
		'	<div id=\'' + showName + '_extras\' class=\'mooshow_extras\'> \n' +
		'		<div id=\'' + showName + '_captions\' class=\'mooshow_captions\' style=\'padding-top:' + this.border + 'px;padding-left:-' + this.border + 'px; width:' + photoArray[0][1] + 'px;\'>' + photoArray[0][7] + '&nbsp;</div> \n' +
		'		<div id=\'' + showName + '_bottomNav\' class=\'mooshow_bottomNav\'></div> \n' +
		'		<div id=\'' + showName + '_temp\' class=\'mooshow_temp\'></div> \n' +
		'	</div> \n' +
		'</div> \n' +
		'<script type="text/javascript"></script>';

		Element.setInnerHTML(showName, mooShow_html);

		// display or hide various options
		if(this.topNav == 'no') {Element.hide(showName+'_topNav');} else {mooShows[showName].updateTopNav(showName);}
		if(this.overlayNav == 'no') Element.hide(showName+'_overlayNav');
		if(this.captions == 'no') Element.hide(showName+'_captions');
		if(this.copyright == 'yes') Element.setInnerHTML(showName+'_copyright', photoArray[0][6]);
		if(this.IPTCinfo == 'no') Element.hide(showName+'_IPTCbutton');
		Element.show(showName);
	}


// -----------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------



var mooshow = Class.create();
mooshow.prototype = {



	// -----------------------------------------------------------------------------------

	initialize: function(showName)
	{
		this.id = showName;
		this.busy = 0;
		this.counter = 0;
		this.photoArray = eval(this.id);
		this.numberOfImages = this.photoArray.length - 2;
		this.IPTCinfoStatus = 0;
		eval($(showName).innerHTML); //get slideshow settings (set inside the mooshow div)
	},

	// -----------------------------------------------------------------------------------

	nextImage: function()
	{
		if(this.busy<1)
		{
			this.busy=1;
			if(this.counter < this.numberOfImages)
			{
				this.counter ++;
			}
			else
			{
				this.counter = 0;
			}
			this.loadImage();
		}
	},

	// -----------------------------------------------------------------------------------

	prevImage: function()
	{
		if(this.busy < 1)
		{
			this.busy = 1;
			if(this.counter > 0)
			{
				this.counter --;
			}
			else
			{
				this.counter = this.numberOfImages;
			}
			this.loadImage();
		}
	},

	// -----------------------------------------------------------------------------------

	jumptoImage: function(counter)
	{
		if(this.busy < 1)
		{
			this.busy = 1;
			this.counter = counter-1;
			this.loadImage();
		}
	},

	// -----------------------------------------------------------------------------------

	switchContent: function(newArray)
	{
		if(this.busy < 1)
		{
			this.busy = 1;
			this.photoArray = eval(newArray);
			this.numberOfImages = this.photoArray.length -2;
			this.counter = 0;
			this.loadImage();
		}
	},

	// -----------------------------------------------------------------------------------

	updateIPTCinfo: function()
	{
		//get showname
		showName = this.id;
		//
		this.iptcHTML = '' +
		'<div class=\'mooshow_IPTC_left\'><br /><br />' +
		'	Image URL <br />' +
		'	Size <br />' +
		'	Dimensions <br />' +
		'	Title <br />' +
		'	Author <br />' +
		'	Copyright <br /><br />' +
		'	Description <br /><br />' +
		'</div>' +
		'<div class=\'mooshow_IPTC_right\'><br /><br />' +
		'	<a href=\'' + this.photoArray[this.counter][0] + '\'>' + this.photoArray[this.counter][0] + '</a><br />' +  //img src
		'	' + this.photoArray[this.counter][3] + '<br />' +  // file size
		'	' + this.photoArray[this.counter][1] + ' x ' + this.photoArray[this.counter][2] + ' pixels<br />' +  // width * height
		'	' + this.photoArray[this.counter][4] + '<br />' +  //title
		'	' + this.photoArray[this.counter][5] + '<br />' +  // author
		'	' + this.photoArray[this.counter][6] + '<br /><br />' +  //copyright
		'	' + this.photoArray[this.counter][7] + '<br /><br />' +  // description
		'</div>';
		Element.setInnerHTML(''+showName+'_IPTC', this.iptcHTML);
	},

	// -----------------------------------------------------------------------------------

	updateIPTCinfoToggle: function()
	{
		if(this.IPTCinfoStatus==1)
		{
			Element.hide(''+showName+'_IPTC');
			this.IPTCinfoStatus=0;
		}
		else
		{
			Element.show(''+showName+'_IPTC');
			this.IPTCinfoStatus=1;
			this.updateIPTCinfo();
		}
	},

	// -----------------------------------------------------------------------------------

	updateTopNav: function(showName)
	{
		//Element.setWidth(showName+'_topNav', this.photoArray[this.counter][1]);
		Element.setInnerHTML(showName+'_topNav', '');
		this.topNavContent = $(showName+'_topNav').innerHTML;

		this.topNavContent = (this.counter+1) + ' / ' + (this.photoArray.length-1) + ' <img src=\'images/album/fap_blank.gif\' width=\'10\' height=\'1\' />';

		for ( var i = 1; i < this.photoArray.length; i++ )
		{
			if(i==this.counter+1)
			{
				this.topNavContent = this.topNavContent + i;
			}
			else
			{
				this.topNavContent = this.topNavContent + ' <a href=\'#' + i + '\' onClick=\'mooShows[&#39;' + showName + '&#39;].jumptoImage(' + i + ');\'>' + i + '</a>';
			}
			if(i<this.photoArray.length-1)
			{
				this.topNavContent = this.topNavContent + ' | ';
			}
		}

		document.getElementById(showName+'_topNav').innerHTML = this.topNavContent;
	},

	// -----------------------------------------------------------------------------------

	loadImage: function()
	{
		//get showname
		showName = this.id;

		// update top navigation
		if(this.topNav=='yes')
		{
			this.updateTopNav(showName);
		}
		// show laoding animation
		Element.show(showName + '_loading');
		// hide IPTC info
		Element.hide(showName + '_IPTC');
		Element.hide(showName + '_IPTCbutton');
		// overlay navigation
		if(this.overlayNav == 'yes')
		{
			Element.hide(showName + '_overlayNav');
		}
		// preload in new image
		newImgPreloader = new Image();
		// if image is preloaded
		newImgPreloader.onload=function()
		{
			// when loaded
			// hide current photo
			Element.setSrc(showName + '_image','images/album/fap_blank.gif');
			Element.setOpacity(showName + '_image',0);
			// hide laoding animation
			Element.hide(showName + '_loading');
			// set captions to blank space
			Element.setInnerHTML(showName + '_captions', '&nbsp;');
			// set copyright to blank
			Element.setInnerHTML(showName + '_copyright', '');
			// get new sizes
			newHeight = newImgPreloader.height;
			newWidth = newImgPreloader.width;
			// resize containers to new size
			this.resizeTopNavWidth = new fx.Width(showName + '_topNav', {duration: mooShows[showName].speed});
			this.resizeCaptionWidth = new fx.Width(showName + '_captions', {duration: mooShows[showName].speed});
			this.resizeOuterContainerHeight = new fx.Height(showName + '_image', {duration: mooShows[showName].speed});
			this.resizeOuterContainerWidth = new fx.Width(showName + '_image', {duration: mooShows[showName].speed, onComplete: function()
				{
					// set up next image
					Element.setSrc(showName + '_image',newImgPreloader.src);
					// reposition overlay nav
					Element.setHeight(showName + '_overlayNav',(newImgPreloader.height));
					Element.setWidth(showName + '_overlayNav',(newImgPreloader.width));
					//show captions
					if(mooShows[showName].captions=='yes')
					{
						Element.setInnerHTML(showName + '_captions', mooShows[showName].photoArray[mooShows[showName].counter][7] + '&nbsp;');
					}
					// copyright
					if(mooShows[showName].copyright == 'yes')
					{
						Element.setInnerHTML(showName + '_copyright', mooShows[showName].photoArray[mooShows[showName].counter][6]);
					}
					// new moo.fx 'fader'
					this.fader = new fx.Opacity(showName + '_image', {duration: mooShows[showName].fadeSpeed, onComplete:function()
					{
						mooShows[showName].busy = 0;
						// overlay navigation
						if(mooShows[showName].overlayNav == 'yes')
						{
							Element.show(showName + '_overlayNav');
						}
						//IPTC panel height
						if(mooShows[showName].IPTCinfo == 'yes')
						{
							Element.show(showName + '_IPTCbutton');
						}
						Element.setWidth(showName + '_IPTC', newImgPreloader.width);
						if(mooShows[showName].IPTCinfoStatus==1)
						{
							mooShows[showName].IPTCinfoStatus=0;
							mooShows[showName].updateIPTCinfoToggle();
						}
						}});
					// call fader fx
					this.fader.hide();
					this.fader.toggle();
				}
			});

			// get current sizes
			oldHeight = Element.getHeight(showName + '_image');
			oldWidth = Element.getWidth(showName + '_image');

			// call moo.fx and when done switchImage()
			this.resizeTopNavWidth.custom(oldWidth,newWidth);
			this.resizeCaptionWidth.custom(oldWidth,newWidth);
			this.resizeOuterContainerHeight.custom(oldHeight,newHeight);
			this.resizeOuterContainerWidth.custom(oldWidth,newWidth);
		};

		newImgPreloader.src = this.photoArray[this.counter][0]; // preloader src
	}

	// -----------------------------------------------------------------------------------

}

// --------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------




// -----------------------------------------------------------------------------------
//	Additional methods for Element by SU, Couloir
//	- further additions by Lokesh Dhakar (huddletogether.com)
//	- and Stuart Eaton (eatpixels.com)

Object.extend(Element,
{
	getWidth: function(element)
	{
		element = $(element);
		return element.offsetWidth;
	},

	getHeight: function(element)
	{
		element = $(element);
		return element.offsetHeight;
	},


	setWidth: function(element,w)
	{
		element = $(element);
		element.style.width = w +"px";
	},

	setHeight: function(element,h)
	{
		element = $(element);
		element.style.height = h +"px";
	},

	setTop: function(element,t)
	{
		element = $(element);
		element.style.top = t +"px";
	},

	setSrc: function(element,src)
	{
		element = $(element);
		element.src = src;
	},

	setAlt: function(element,alt)
	{
		element = $(element);
		element.alt = alt;
	},

	setOpacity: function(element,opacity)
	{
		element = $(element);
		element.style.opacity = opacity;
	},

	setHref: function(element,href)
	{
		element = $(element);
		element.href = href;
	},

	setInnerHTML: function(element,content)
	{
		element = $(element);
		element.innerHTML = content;
	},

	hide: function(element)
	{
		element = $(element);
		element.style.display = 'none';
	},

	show: function(element)
	{
		element = $(element);
		element.style.display = 'inline';
	}
});

// ---------------------------------------------------
// addLoadEvent()
// Adds event to window.onload without overwriting currently assigned onload functions.
// Function found at Simon Willison's weblog - http://simon.incutio.com/
//
function addLoadEvent(func)
{
	var oldonload = window.onload;
	if (typeof window.onload != 'function')
	{
		window.onload = func;
	}
	else
	{
		window.onload = function()
		{
			oldonload();
			func();
		}
	}
}

// ---------------------------------------------------

function run()
{
	start();
}

addLoadEvent(run);	// run initMooshow onLoad

// --------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------
