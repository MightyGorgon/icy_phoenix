/******************************************************************************
Name:    Highslide JS
Version: 4.0.12 (February 4 2009)
Config:  default +events +unobtrusive +imagemap +slideshow +positioning +transitions +inline +ajax +iframe +flash
Author:  Torstein Hønsi
Support: http://highslide.com/support

Licence:
Highslide JS is licensed under a Creative Commons Attribution-NonCommercial 2.5
License (http://creativecommons.org/licenses/by-nc/2.5/).

You are free:
	* to copy, distribute, display, and perform the work
	* to make derivative works

Under the following conditions:
	* Attribution. You must attribute the work in the manner  specified by  the
	  author or licensor.
	* Noncommercial. You may not use this work for commercial purposes.

* For  any  reuse  or  distribution, you  must make clear to others the license
  terms of this work.
* Any  of  these  conditions  can  be  waived  if  you  get permission from the
  copyright holder.

Your fair use and other rights are in no way affected by the above.
******************************************************************************/

var hs = {
// Language strings
lang : {
	cssDirection: 'ltr',
	loadingText : 'Loading...',
	loadingTitle : 'Click to cancel',
	focusTitle : 'Click to bring to front',
	fullExpandTitle : 'Expand to actual size (f)',
	creditsText : 'Powered by <i>Highslide JS</i>',
	creditsTitle : 'Go to the Highslide JS homepage',
	previousText : 'Previous',
	nextText : 'Next',
	moveText : 'Move',
	closeText : 'Close',
	closeTitle : 'Close (esc)',
	resizeTitle : 'Resize',
	playText : 'Play',
	playTitle : 'Play slideshow (spacebar)',
	pauseText : 'Pause',
	pauseTitle : 'Pause slideshow (spacebar)',
	previousTitle : 'Previous (arrow left)',
	nextTitle : 'Next (arrow right)',
	moveTitle : 'Move',
	fullExpandText : 'Full size',
	number: 'Image %1 of %2',
	restoreTitle : 'Click to close image, click and drag to move. Use arrow keys for next and previous.'
},
// See http://highslide.com/ref for examples of settings
graphicsDir : 'highslide/graphics/',
expandCursor : 'zoomin.cur', // null disables
restoreCursor : 'zoomout.cur', // null disables
expandDuration : 250, // milliseconds
restoreDuration : 250,
marginLeft : 15,
marginRight : 15,
marginTop : 15,
marginBottom : 15,
zIndexCounter : 1001, // adjust to other absolutely positioned elements
loadingOpacity : 0.75,
allowMultipleInstances: true,
numberOfImagesToPreload : 5,
outlineWhileAnimating : 2, // 0 = never, 1 = always, 2 = HTML only
outlineStartOffset : 3, // ends at 10
padToMinWidth : false, // pad the popup width to make room for wide caption
fullExpandPosition : 'bottom right',
fullExpandOpacity : 1,
showCredits : true, // you can set this to false if you want
creditsHref : 'http://highslide.com/',
enableKeyListener : true,
openerTagNames : ['a', 'area'], // Add more to allow slideshow indexing
transitions : [],
transitionDuration: 250,
dimmingOpacity: 0, // Lightbox style dimming background
dimmingDuration: 50, // 0 for instant dimming

allowWidthReduction : false,
allowHeightReduction : true,
preserveContent : true, // Preserve changes made to the content and position of HTML popups.
objectLoadTime : 'before', // Load iframes 'before' or 'after' expansion.
cacheAjax : true, // Cache ajax popups for instant display. Can be overridden for each popup.
anchor : 'auto', // where the image expands from
align : 'auto', // position in the client (overrides anchor)
targetX: null, // the id of a target element
targetY: null,
dragByHeading: true,
minWidth: 200,
minHeight: 200,
allowSizeReduction: true, // allow the image to reduce to fit client size. If false, this overrides minWidth and minHeight
outlineType : 'drop-shadow', // set null to disable outlines
wrapperClassName : 'highslide-wrapper', // for enhanced css-control
skin : {
	controls:
		'<div class="highslide-controls"><ul>'+
			'<li class="highslide-previous">'+
				'<a href="#" title="{hs.lang.previousTitle}">'+
				'<span>{hs.lang.previousText}</span></a>'+
			'</li>'+
			'<li class="highslide-play">'+
				'<a href="#" title="{hs.lang.playTitle}">'+
				'<span>{hs.lang.playText}</span></a>'+
			'</li>'+
			'<li class="highslide-pause">'+
				'<a href="#" title="{hs.lang.pauseTitle}">'+
				'<span>{hs.lang.pauseText}</span></a>'+
			'</li>'+
			'<li class="highslide-next">'+
				'<a href="#" title="{hs.lang.nextTitle}">'+
				'<span>{hs.lang.nextText}</span></a>'+
			'</li>'+
			'<li class="highslide-move">'+
				'<a href="#" title="{hs.lang.moveTitle}">'+
				'<span>{hs.lang.moveText}</span></a>'+
			'</li>'+
			'<li class="highslide-full-expand">'+
				'<a href="#" title="{hs.lang.fullExpandTitle}">'+
				'<span>{hs.lang.fullExpandText}</span></a>'+
			'</li>'+
			'<li class="highslide-close">'+
				'<a href="#" title="{hs.lang.closeTitle}" >'+
				'<span>{hs.lang.closeText}</span></a>'+
			'</li>'+
		'</ul></div>'
	,
	contentWrapper:
		'<div class="highslide-header"><ul>'+
			'<li class="highslide-previous">'+
				'<a href="#" title="{hs.lang.previousTitle}" onclick="return hs.previous(this)">'+
				'<span>{hs.lang.previousText}</span></a>'+
			'</li>'+
			'<li class="highslide-next">'+
				'<a href="#" title="{hs.lang.nextTitle}" onclick="return hs.next(this)">'+
				'<span>{hs.lang.nextText}</span></a>'+
			'</li>'+
			'<li class="highslide-move">'+
				'<a href="#" title="{hs.lang.moveTitle}" onclick="return false">'+
				'<span>{hs.lang.moveText}</span></a>'+
			'</li>'+
			'<li class="highslide-close">'+
				'<a href="#" title="{hs.lang.closeTitle}" onclick="return hs.close(this)">'+
				'<span>{hs.lang.closeText}</span></a>'+
			'</li>'+
		'</ul></div>'+
		'<div class="highslide-body"></div>'+
		'<div class="highslide-footer"><div>'+
			'<span class="highslide-resize" title="{hs.lang.resizeTitle}"><span></span></span>'+
		'</div></div>'
},
// END OF YOUR SETTINGS


// declare internal properties
preloadTheseImages : [],
continuePreloading: true,
expanders : [],
overrides : [
	'allowSizeReduction',
	'useBox',
	'anchor',
	'align',
	'targetX',
	'targetY',
	'outlineType',
	'outlineWhileAnimating',
	'captionId',
	'captionText',
	'captionEval',
	'captionOverlay',
	'headingId',
	'headingText',
	'headingEval',
	'headingOverlay',
	'dragByHeading',
	'autoplay',
	'numberPosition',
	'transitions',
	'dimmingOpacity',

	'width',
	'height',

	'contentId',
	'allowWidthReduction',
	'allowHeightReduction',
	'preserveContent',
	'maincontentId',
	'maincontentText',
	'maincontentEval',
	'objectType',
	'cacheAjax',
	'objectWidth',
	'objectHeight',
	'objectLoadTime',
	'swfOptions',
	'wrapperClassName',
	'minWidth',
	'minHeight',
	'maxWidth',
	'maxHeight',
	'slideshowGroup',
	'easing',
	'easingClose',
	'fadeInOut',
	'src'
],
overlays : [],
idCounter : 0,
oPos : {
	x: ['leftpanel', 'left', 'center', 'right', 'rightpanel'],
	y: ['above', 'top', 'middle', 'bottom', 'below']
},
mouse: {},
headingOverlay: {},
captionOverlay: {},
swfOptions: { flashvars: {}, params: {}, attributes: {} },
faders : [],

slideshows : [],

pendingOutlines : {},
sleeping : [],
preloadTheseAjax : [],
cacheBindings : [],
cachedGets : {},
clones : {},
ie : (document.all && !window.opera),
safari : /Safari/.test(navigator.userAgent),
geckoMac : /Macintosh.+rv:1\.[0-8].+Gecko/.test(navigator.userAgent),

$ : function (id) {
	return document.getElementById(id);
},

push : function (arr, val) {
	arr[arr.length] = val;
},

createElement : function (tag, attribs, styles, parent, nopad) {
	var el = document.createElement(tag);
	if (attribs) hs.setAttribs(el, attribs);
	if (nopad) hs.setStyles(el, {padding: 0, border: 'none', margin: 0});
	if (styles) hs.setStyles(el, styles);
	if (parent) parent.appendChild(el);
	return el;
},

setAttribs : function (el, attribs) {
	for (var x in attribs) el[x] = attribs[x];
},

setStyles : function (el, styles) {
	for (var x in styles) {
		if (hs.ie && x == 'opacity') {
			if (styles[x] > 0.99) el.style.removeAttribute('filter');
			else el.style.filter = 'alpha(opacity='+ (styles[x] * 100) +')';
		}
		else el.style[x] = styles[x];
	}
},

ieVersion : function () {
	var arr = navigator.appVersion.split("MSIE");
	return arr[1] ? parseFloat(arr[1]) : null;
},

getPageSize : function () {
	var d = document, w = window, iebody = d.compatMode && d.compatMode != 'BackCompat'
		? d.documentElement : d.body;


	var b = d.body;
	var xScroll = (w.innerWidth && w.scrollMaxX)
			? w.innerWidth + w.scrollMaxX : Math.max(b.scrollWidth, b.offsetWidth),
		yScroll = (w.innerHeight && window.scrollMaxY)
			? w.innerHeight + w.scrollMaxY : Math.max(b.scrollHeight, b.offsetHeight),
		pageWidth = hs.ie ? iebody.scrollWidth :
			(d.documentElement.clientWidth || self.innerWidth),
      	pageHeight = hs.ie ? Math.max(iebody.scrollHeight, iebody.clientHeight) :
			(d.documentElement.clientHeight || self.innerHeight);

	var width = hs.ie ? iebody.clientWidth :
			(d.documentElement.clientWidth || self.innerWidth),
		height = hs.ie ? iebody.clientHeight : self.innerHeight;

	return {
		pageWidth: Math.max(pageWidth, xScroll),
		pageHeight: Math.max(pageHeight, yScroll),
		width: width,
		height: height,
		scrollLeft: hs.ie ? iebody.scrollLeft : pageXOffset,
		scrollTop: hs.ie ? iebody.scrollTop : pageYOffset
	}
},

getPosition : function(el)	{
	if (/area/i.test(el.tagName)) {
		var imgs = document.getElementsByTagName('img');
		for (var i = 0; i < imgs.length; i++) {
			var u = imgs[i].useMap;
			if (u && u.replace(/^.*?#/, '') == el.parentNode.name) {
				el = imgs[i];
				break;
			}
		}
	}
	var p = { x: el.offsetLeft, y: el.offsetTop };
	while (el.offsetParent)	{
		el = el.offsetParent;
		p.x += el.offsetLeft;
		p.y += el.offsetTop;
		if (el != document.body && el != document.documentElement) {
			p.x -= el.scrollLeft;
			p.y -= el.scrollTop;
		}
	}
	return p;
},

expand : function(a, params, custom, type) {
	if (!a) a = hs.createElement('a', null, { display: 'none' }, hs.container);
	if (typeof a.getParams == 'function') return params;
	if (type == 'html') {
		for (var i = 0; i < hs.sleeping.length; i++) {
			if (hs.sleeping[i] && hs.sleeping[i].a == a) {
				hs.sleeping[i].awake();
				hs.sleeping[i] = null;
				return false;
			}
		}
		hs.hasHtmlExpanders = true;
	}
	try {
		new hs.Expander(a, params, custom, type);
		return false;
	} catch (e) { return true; }
},

htmlExpand : function(a, params, custom) {
	return hs.expand(a, params, custom, 'html');
},

getSelfRendered : function() {
	return hs.createElement('div', {
		className: 'highslide-html-content',
		innerHTML: hs.replaceLang(hs.skin.contentWrapper)
	});
},
getElementByClass : function (el, tagName, className) {
	var els = el.getElementsByTagName(tagName);
	for (var i = 0; i < els.length; i++) {
    	if ((new RegExp(className)).test(els[i].className)) {
			return els[i];
		}
	}
	return null;
},
replaceLang : function(s) {
	s = s.replace(/\s/g, ' ');
	var re = /{hs\.lang\.([^}]+)\}/g,
		matches = s.match(re),
		lang;
	if (matches) for (var i = 0; i < matches.length; i++) {
		lang = matches[i].replace(re, "$1");
		if (typeof hs.lang[lang] != 'undefined') s = s.replace(matches[i], hs.lang[lang]);
	}
	return s;
},


setClickEvents : function () {
	var els = document.getElementsByTagName('a');
	for (var i = 0; i < els.length; i++) {
		var type = hs.isUnobtrusiveAnchor(els[i]);
		if (type && !els[i].hsHasSetClick) {
			(function(){
				var t = type;
				if (hs.fireEvent(hs, 'onSetClickEvent', { element: els[i], type: t })) {
					els[i].onclick =(type == 'image') ?function() { return hs.expand(this) }:
						function() { return hs.htmlExpand(this, { objectType: t } );};
				}
			})();
			els[i].hsHasSetClick = true;
		}
	}
	if (!hs.pageLoaded) setTimeout( hs.setClickEvents, 50);
	else hs.getAnchors();
},
isUnobtrusiveAnchor: function(el) {
	if (el.rel == 'highslide') return 'image';
	else if (el.rel == 'highslide-ajax') return 'ajax';
	else if (el.rel == 'highslide-iframe') return 'iframe';
	else if (el.rel == 'highslide-swf') return 'swf';
},

getCacheBinding : function (a) {
	for (var i = 0; i < hs.cacheBindings.length; i++) {
		if (hs.cacheBindings[i][0] == a) {
			var c = hs.cacheBindings[i][1];
			hs.cacheBindings[i][1] = c.cloneNode(1);
			return c;
		}
	}
	return null;
},

preloadAjax : function (e) {
	var arr = hs.getAnchors();
	for (var i = 0; i < arr.htmls.length; i++) {
		var a = arr.htmls[i];
		if (hs.getParam(a, 'objectType') == 'ajax' && hs.getParam(a, 'cacheAjax'))
			hs.push(hs.preloadTheseAjax, a);
	}

	hs.preloadAjaxElement(0);
},

preloadAjaxElement : function (i) {
	if (!hs.preloadTheseAjax[i]) return;
	var a = hs.preloadTheseAjax[i];
	var cache = hs.getNode(hs.getParam(a, 'contentId'));
	if (!cache) cache = hs.getSelfRendered();
	var ajax = new hs.Ajax(a, cache, 1);
   	ajax.onError = function () { };
   	ajax.onLoad = function () {
   		hs.push(hs.cacheBindings, [a, cache]);
   		hs.preloadAjaxElement(i + 1);
   	};
   	ajax.run();
},

focusTopmost : function() {
	var topZ = 0, topmostKey = -1;
	for (var i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i]) {
			if (hs.expanders[i].wrapper.style.zIndex && hs.expanders[i].wrapper.style.zIndex > topZ) {
				topZ = hs.expanders[i].wrapper.style.zIndex;

				topmostKey = i;
			}
		}
	}
	if (topmostKey == -1) hs.focusKey = -1;
	else hs.expanders[topmostKey].focus();
},

getParam : function (a, param) {
	a.getParams = a.onclick;
	var p = a.getParams ? a.getParams() : null;
	a.getParams = null;

	return (p && typeof p[param] != 'undefined') ? p[param] :
		(typeof hs[param] != 'undefined' ? hs[param] : null);
},

getSrc : function (a) {
	var src = hs.getParam(a, 'src');
	if (src) return src;
	return a.href;
},

getNode : function (id) {
	var node = hs.$(id), clone = hs.clones[id], a = {};
	if (!node && !clone) return null;
	if (!clone) {
		clone = node.cloneNode(true);
		clone.id = '';
		hs.clones[id] = clone;
		return node;
	} else {
		return clone.cloneNode(true);
	}
},

discardElement : function(d) {
	hs.garbageBin.appendChild(d);
	hs.garbageBin.innerHTML = '';
},
dim : function(exp) {
	if (!hs.dimmer) {
		hs.dimmer = hs.createElement ('div',
			{
				className: 'highslide-dimming',
				owner: '',
				onclick: function() {
					if (hs.fireEvent(hs, 'onDimmerClick'))

						hs.close();
				}
			},
			{ position: 'absolute', left: 0 }, hs.container, true);
		hs.addEventListener(window, 'resize', hs.setDimmerSize);
	}
	hs.dimmer.style.display = '';
	hs.setDimmerSize();
	hs.dimmer.owner += '|'+ exp.key;
	if (hs.geckoMac && hs.dimmingGeckoFix)
		hs.dimmer.style.background = 'url('+ hs.graphicsDir + 'geckodimmer.png)';
	else
		hs.fade(hs.dimmer, 0, exp.dimmingOpacity, hs.dimmingDuration);
},
undim : function(key) {
	if (!hs.dimmer) return;
	if (typeof key != 'undefined') hs.dimmer.owner = hs.dimmer.owner.replace('|'+ key, '');

	if (
		(typeof key != 'undefined' && hs.dimmer.owner != '')
		|| (hs.upcoming && hs.getParam(hs.upcoming, 'dimmingOpacity'))
	) return;
	if (hs.geckoMac && hs.dimmingGeckoFix)
		hs.setStyles(hs.dimmer, { background: 'none', width: 0, height: 0 });
	else hs.fade(hs.dimmer, hs.dimmingOpacity, 0, hs.dimmingDuration, function() {
		hs.setStyles(hs.dimmer, { display: 'none', width: 0, height: 0 });
	});
},
setDimmerSize : function(exp) {
	if (!hs.dimmer) return;
	var page = hs.getPageSize();
	var h = (hs.ie && exp && exp.wrapper) ?
		parseInt(exp.wrapper.style.top) + parseInt(exp.wrapper.style.height)+ (exp.outline ? exp.outline.offset : 0) : 0;
	hs.setStyles(hs.dimmer, {
		width: page.pageWidth +'px',
		height: Math.max(page.pageHeight, h) +'px'
	});
},
transit : function (adj, exp) {
	hs.last = exp = exp || hs.getExpander();
	try {
		hs.upcoming = adj;
		adj.onclick();
	} catch (e){
		hs.last = hs.upcoming = null;
	}
	try {
		if (!adj || exp.transitions[1] != 'crossfade')
		exp.close();
	} catch (e) {}
	return false;
},

previousOrNext : function (el, op) {
	var exp = hs.getExpander(el),
		adj = exp.getAdjacentAnchor(op);
	return hs.transit(adj, exp);
},

previous : function (el) {
	return hs.previousOrNext(el, -1);
},

next : function (el) {
	return hs.previousOrNext(el, 1);
},

keyHandler : function(e) {
	if (!e) e = window.event;
	if (!e.target) e.target = e.srcElement; // ie
	if (typeof e.target.form != 'undefined') return true; // form element has focus
	if (!hs.fireEvent(hs, 'onKeyDown', e)) return true;
	var exp = hs.getExpander();

	var op = null;
	switch (e.keyCode) {
		case 70: // f
			if (exp) exp.doFullExpand();
			return true;
		case 32: // Space
			op = 2;
			break;
		case 34: // Page Down
		case 39: // Arrow right
		case 40: // Arrow down
			op = 1;
			break;
		case 8:  // Backspace
		case 33: // Page Up
		case 37: // Arrow left
		case 38: // Arrow up
			op = -1;
			break;
		case 27: // Escape
		case 13: // Enter
			op = 0;
	}
	if (op !== null) {if (op != 2)hs.removeEventListener(document, window.opera ? 'keypress' : 'keydown', hs.keyHandler);
		if (!hs.enableKeyListener) return true;

		if (e.preventDefault) e.preventDefault();
    	else e.returnValue = false;

    	if (exp) {
			if (op == 0) {
				exp.close();
			} else if (op == 2) {
				if (exp.slideshow) exp.slideshow.hitSpace();
			} else {
				if (exp.slideshow) exp.slideshow.pause();
				hs.previousOrNext(exp.key, op);
			}
			return false;
		}
	}
	return true;
},


registerOverlay : function (overlay) {
	hs.push(hs.overlays, overlay);
},


addSlideshow : function (options) {
	var sg = options.slideshowGroup;
	if (typeof sg == 'object') {
		for (var i = 0; i < sg.length; i++) {
			var o = {};
			for (var x in options) o[x] = options[x];
			o.slideshowGroup = sg[i];
			hs.push(hs.slideshows, o);
		}
	} else {
		hs.push(hs.slideshows, options);
	}
},

getWrapperKey : function (element, expOnly) {
	var el, re = /^highslide-wrapper-([0-9]+)$/;
	// 1. look in open expanders
	el = element;
	while (el.parentNode)	{
		if (el.id && re.test(el.id)) return el.id.replace(re, "$1");
		el = el.parentNode;
	}
	// 2. look in thumbnail
	if (!expOnly) {
		el = element;
		while (el.parentNode)	{
			if (el.tagName && hs.isHsAnchor(el)) {
				for (var key = 0; key < hs.expanders.length; key++) {
					var exp = hs.expanders[key];
					if (exp && exp.a == el) return key;
				}
			}
			el = el.parentNode;
		}
	}
	return null;
},

getExpander : function (el, expOnly) {
	if (typeof el == 'undefined') return hs.expanders[hs.focusKey] || null;
	if (typeof el == 'number') return hs.expanders[el] || null;
	if (typeof el == 'string') el = hs.$(el);
	return hs.expanders[hs.getWrapperKey(el, expOnly)] || null;
},

isHsAnchor : function (a) {
	return (a.onclick && a.onclick.toString().replace(/\s/g, ' ').match(/hs.(htmlE|e)xpand/));
},

reOrder : function () {
	for (var i = 0; i < hs.expanders.length; i++)
		if (hs.expanders[i] && hs.expanders[i].isExpanded) hs.focusTopmost();
},
fireEvent : function (obj, evt, args) {
	return obj && obj[evt] ? (obj[evt](obj, args) !== false) : true;
},

mouseClickHandler : function(e)
{
	if (!e) e = window.event;
	if (e.button > 1) return true;
	if (!e.target) e.target = e.srcElement;

	var el = e.target;
	while (el.parentNode
		&& !(/highslide-(image|move|html|resize)/.test(el.className)))
	{
		el = el.parentNode;
	}
	var exp = hs.getExpander(el);
	if (exp && (exp.isClosing || !exp.isExpanded)) return true;

	if (exp && e.type == 'mousedown') {
		if (e.target.form) return true;
		var match = el.className.match(/highslide-(image|move|resize)/);
		if (match) {
			hs.dragArgs = { exp: exp , type: match[1], left: exp.x.pos, width: exp.x.size, top: exp.y.pos,
				height: exp.y.size, clickX: e.clientX, clickY: e.clientY };


			hs.addEventListener(document, 'mousemove', hs.dragHandler);
			if (e.preventDefault) e.preventDefault(); // FF

			if (/highslide-(image|html)-blur/.test(exp.content.className)) {
				exp.focus();
				hs.hasFocused = true;
			}
			return false;
		}
		else if (/highslide-html/.test(el.className) && hs.focusKey != exp.key) {
			exp.focus();
			exp.doShowHide('hidden');
		}
	} else if (e.type == 'mouseup') {

		hs.removeEventListener(document, 'mousemove', hs.dragHandler);

		if (hs.dragArgs) {
			if (hs.styleRestoreCursor && hs.dragArgs.type == 'image')
				hs.dragArgs.exp.content.style.cursor = hs.styleRestoreCursor;
			var hasDragged = hs.dragArgs.hasDragged;

			if (!hasDragged &&!hs.hasFocused && !/(move|resize)/.test(hs.dragArgs.type)) {
				if (hs.fireEvent(exp, 'onImageClick'))
				exp.close();
			}
			else if (hasDragged || (!hasDragged && hs.hasHtmlExpanders)) {
				hs.dragArgs.exp.doShowHide('hidden');
			}

			if (hs.dragArgs.exp.releaseMask)
				hs.dragArgs.exp.releaseMask.style.display = 'none';

			if (hasDragged) hs.fireEvent(hs.dragArgs.exp, 'onDrop', hs.dragArgs);
			if (hasDragged) hs.setDimmerSize(exp);

			hs.hasFocused = false;
			hs.dragArgs = null;

		} else if (/highslide-image-blur/.test(el.className)) {
			el.style.cursor = hs.styleRestoreCursor;
		}
	}
	return false;
},

dragHandler : function(e)
{
	if (!hs.dragArgs) return true;
	if (!e) e = window.event;
	var a = hs.dragArgs, exp = a.exp;
	if (exp.iframe) {
		if (!exp.releaseMask) exp.releaseMask = hs.createElement('div', null,
			{ position: 'absolute', width: exp.x.size+'px', height: exp.y.size+'px',
				left: exp.x.cb+'px', top: exp.y.cb+'px', zIndex: 4,	background: (hs.ie ? 'white' : 'none'),
				opacity: .01 },
			exp.wrapper, true);
		if (exp.releaseMask.style.display == 'none')
			exp.releaseMask.style.display = '';
	}

	a.dX = e.clientX - a.clickX;
	a.dY = e.clientY - a.clickY;

	var distance = Math.sqrt(Math.pow(a.dX, 2) + Math.pow(a.dY, 2));
	if (!a.hasDragged) a.hasDragged = (a.type != 'image' && distance > 0)
		|| (distance > (hs.dragSensitivity || 5));

	if (a.hasDragged && e.clientX > 5 && e.clientY > 5) {
		if (!hs.fireEvent(exp, 'onDrag', a)) return false;

		if (a.type == 'resize') exp.resize(a);
		else {
			exp.moveTo(a.left + a.dX, a.top + a.dY);
			if (a.type == 'image') exp.content.style.cursor = 'move';
		}
	}
	return false;
},

wrapperMouseHandler : function (e) {
	try {
		if (!e) e = window.event;
		var over = /mouseover/i.test(e.type);
		if (!e.target) e.target = e.srcElement; // ie
		if (hs.ie) e.relatedTarget =
			over ? e.fromElement : e.toElement; // ie
		var exp = hs.getExpander(e.target);
		if (!exp.isExpanded) return;
		if (!exp || !e.relatedTarget || hs.getExpander(e.relatedTarget, true) == exp
			|| hs.dragArgs) return;
		hs.fireEvent(exp, over ? 'onMouseOver' : 'onMouseOut', e);
		for (var i = 0; i < exp.overlays.length; i++) {
			var o = hs.$('hsId'+ exp.overlays[i]);
			if (o && o.hideOnMouseOut) {
				var from = over ? 0 : o.opacity,
					to = over ? o.opacity : 0;
				hs.fade(o, from, to);
			}
		}
	} catch (e) {}
},

addEventListener : function (el, event, func) {
	try {
		el.addEventListener(event, func, false);
	} catch (e) {
		try {
			el.detachEvent('on'+ event, func);
			el.attachEvent('on'+ event, func);
		} catch (e) {
			el['on'+ event] = func;
		}
	}
},

removeEventListener : function (el, event, func) {
	try {
		el.removeEventListener(event, func, false);
	} catch (e) {
		try {
			el.detachEvent('on'+ event, func);
		} catch (e) {
			el['on'+ event] = null;
		}
	}
},

preloadFullImage : function (i) {
	if (hs.continuePreloading && hs.preloadTheseImages[i] && hs.preloadTheseImages[i] != 'undefined') {
		var img = document.createElement('img');
		img.onload = function() {
			img = null;
			hs.preloadFullImage(i + 1);
		};
		img.src = hs.preloadTheseImages[i];
	}
},
preloadImages : function (number) {
	if (number && typeof number != 'object') hs.numberOfImagesToPreload = number;

	var arr = hs.getAnchors();
	for (var i = 0; i < arr.images.length && i < hs.numberOfImagesToPreload; i++) {
		hs.push(hs.preloadTheseImages, hs.getSrc(arr.images[i]));
	}

	// preload outlines
	if (hs.outlineType)	new hs.Outline(hs.outlineType, function () { hs.preloadFullImage(0)} );
	else

	hs.preloadFullImage(0);

	// preload cursor
	if (hs.restoreCursor) var cur = hs.createElement('img', { src: hs.graphicsDir + hs.restoreCursor });
},


init : function () {
	if (!hs.container) {
		hs.container = hs.createElement('div', {
				className: 'highslide-container'
			}, {
				position: 'absolute',
				left: 0,
				top: 0,
				width: '100%',
				zIndex: hs.zIndexCounter,
				direction: 'ltr'
			},
			document.body,
			true
		);
		hs.loading = hs.createElement('a', {
				className: 'highslide-loading',
				title: hs.lang.loadingTitle,
				innerHTML: hs.lang.loadingText,
				href: 'javascript:;'
			}, {
				position: 'absolute',
				top: '-9999px',
				opacity: hs.loadingOpacity,
				zIndex: 1
			}, hs.container
		);
		hs.garbageBin = hs.createElement('div', null, { display: 'none' }, hs.container);
		hs.clearing = hs.createElement('div', null,
			{ clear: 'both', paddingTop: '1px' }, null, true);

		// http://www.robertpenner.com/easing/
		Math.linearTween = function (t, b, c, d) {
			return c*t/d + b;
		};
		Math.easeInQuad = function (t, b, c, d) {
			return c*(t/=d)*t + b;
		};
		Math.easeInOutQuad = function (t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t + b;
			return -c/2 * ((--t)*(t-2) - 1) + b;
		};
		for (var x in hs.langDefaults) {
			if (typeof hs[x] != 'undefined') hs.lang[x] = hs[x];
			else if (typeof hs.lang[x] == 'undefined' && typeof hs.langDefaults[x] != 'undefined')
				hs.lang[x] = hs.langDefaults[x];
		}
		hs.ie6SSL = (hs.ie && hs.ieVersion() <= 6 && location.protocol == 'https:');

		hs.hideSelects = (hs.ie && hs.ieVersion() < 7);
		hs.hideIframes = ((window.opera && navigator.appVersion < 9) || navigator.vendor == 'KDE'
			|| (hs.ie && hs.ieVersion() < 5.5));
		hs.fireEvent(this, 'onActivate');
	}
},
domReady : function() {
	hs.isDomReady = true;
	if (hs.onDomReady) hs.onDomReady();
},

updateAnchors : function() {
	var el, els, all = [], images = [], htmls = [],groups = {}, re;

	for (var i = 0; i < hs.openerTagNames.length; i++) {
		els = document.getElementsByTagName(hs.openerTagNames[i]);
		for (var j = 0; j < els.length; j++) {
			el = els[j];
			re = hs.isHsAnchor(el);
			if (re) {
				hs.push(all, el);
				if (re[0] == 'hs.expand') hs.push(images, el);
				else if (re[0] == 'hs.htmlExpand') hs.push(htmls, el);
				var g = hs.getParam(el, 'slideshowGroup') || 'none';
				if (!groups[g]) groups[g] = [];
				hs.push(groups[g], el);
			}
		}
	}
	hs.anchors = { all: all, groups: groups, images: images, htmls: htmls };
	return hs.anchors;

},

getAnchors : function() {
	return hs.anchors || hs.updateAnchors();
},


fade : function (el, o, oFinal, dur, fn, i, dir) {
	if (typeof i == 'undefined') { // new fader
		if (typeof dur != 'number') dur = 250;
		if (dur < 25) { // instant
			hs.setStyles( el, { opacity: oFinal	});
			if (fn) fn();
			return;
		}
		i = hs.faders.length;
		dir = oFinal > o ? 1 : -1;
		var step = (25 / (dur - dur % 25)) * Math.abs(o - oFinal);
	}
	o = parseFloat(o);
	var skip = (el.fade === 0 || el.fade === false || (el.fade == 2 && hs.ie));
	el.style.visibility = ((skip ? oFinal : o) <= 0) ? 'hidden' : 'visible';
	if (skip || o < 0 || (dir == 1 && o > oFinal)) {
		if (fn) fn();
		return;
	}
	if (el.fading && el.fading.i != i) {
		clearTimeout(hs.faders[el.fading.i]);
		o = el.fading.o;
	}
	el.fading = {i: i, o: o, step: (step || el.fading.step)};
	el.style.visibility = (o <= 0) ? 'hidden' : 'visible';
	hs.setStyles(el, { opacity: o });
	hs.faders[i] = setTimeout(function() {
		hs.fade(el, o + el.fading.step * dir, oFinal, null, fn, i, dir);
	}, 25);
},

close : function(el) {
	var exp = hs.getExpander(el);
	if (exp) exp.close();
	return false;
}
}; // end hs object


hs.Outline =  function (outlineType, onLoad) {
	this.onLoad = onLoad;
	this.outlineType = outlineType;
	var v = hs.ieVersion(), tr;

	this.hasAlphaImageLoader = hs.ie && v >= 5.5 && v < 7;
	if (!outlineType) {
		if (onLoad) onLoad();
		return;
	}

	hs.init();
	this.table = hs.createElement(
		'table', {
			cellSpacing: 0
		}, {
			visibility: 'hidden',
			position: 'absolute',
			borderCollapse: 'collapse',
			width: 0
		},
		hs.container,
		true
	);
	var tbody = hs.createElement('tbody', null, null, this.table, 1);

	this.td = [];
	for (var i = 0; i <= 8; i++) {
		if (i % 3 == 0) tr = hs.createElement('tr', null, { height: 'auto' }, tbody, true);
		this.td[i] = hs.createElement('td', null, null, tr, true);
		var style = i != 4 ? { lineHeight: 0, fontSize: 0} : { position : 'relative' };
		hs.setStyles(this.td[i], style);
	}
	this.td[4].className = outlineType +' highslide-outline';

	this.preloadGraphic();
};

hs.Outline.prototype = {
preloadGraphic : function () {
	var src = hs.graphicsDir + (hs.outlinesDir || "outlines/")+ this.outlineType +".png";

	var appendTo = hs.safari ? hs.container : null;
	this.graphic = hs.createElement('img', null, { position: 'absolute',
		top: '-9999px' }, appendTo, true); // for onload trigger

	var pThis = this;
	this.graphic.onload = function() { pThis.onGraphicLoad(); };

	this.graphic.src = src;
},

onGraphicLoad : function () {
	var o = this.offset = this.graphic.width / 4,
		pos = [[0,0],[0,-4],[-2,0],[0,-8],0,[-2,-8],[0,-2],[0,-6],[-2,-2]],
		dim = { height: (2*o) +'px', width: (2*o) +'px' };
	for (var i = 0; i <= 8; i++) {
		if (pos[i]) {
			if (this.hasAlphaImageLoader) {
				var w = (i == 1 || i == 7) ? '100%' : this.graphic.width +'px';
				var div = hs.createElement('div', null, { width: '100%', height: '100%', position: 'relative', overflow: 'hidden'}, this.td[i], true);
				hs.createElement ('div', null, {
						filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale, src='"+ this.graphic.src + "')",
						position: 'absolute',
						width: w,
						height: this.graphic.height +'px',
						left: (pos[i][0]*o)+'px',
						top: (pos[i][1]*o)+'px'
					},
				div,
				true);
			} else {
				hs.setStyles(this.td[i], { background: 'url('+ this.graphic.src +') '+ (pos[i][0]*o)+'px '+(pos[i][1]*o)+'px'});
			}

			if (window.opera && (i == 3 || i ==5))
				hs.createElement('div', null, dim, this.td[i], true);

			hs.setStyles (this.td[i], dim);
		}
	}
	this.graphic = null;
	if (hs.pendingOutlines[this.outlineType]) hs.pendingOutlines[this.outlineType].destroy();
	hs.pendingOutlines[this.outlineType] = this;
	if (this.onLoad) this.onLoad();
},

setPosition : function (exp, pos, vis) {
	pos = pos || {
		x: exp.x.pos,
		y: exp.y.pos,
		w: exp.x.size + exp.x.p1 + exp.x.p2,
		h: exp.y.size + exp.y.p1 + exp.y.p2
	};
	if (vis) this.table.style.visibility = (pos.h >= 4 * this.offset)
		? 'visible' : 'hidden';
	hs.setStyles(this.table, {
		left: (pos.x - this.offset) +'px',
		top: (pos.y - this.offset) +'px',
		width: (pos.w + 2 * (exp.x.cb + this.offset)) +'px'
	});

	pos.w += 2 * (exp.x.cb - this.offset);
	pos.h += + 2 * (exp.y.cb - this.offset);
	hs.setStyles (this.td[4], {
		width: pos.w >= 0 ? pos.w +'px' : 0,
		height: pos.h >= 0 ? pos.h +'px' : 0
	});
	if (this.hasAlphaImageLoader) this.td[3].style.height
		= this.td[5].style.height = this.td[4].style.height;
},

destroy : function(hide) {
	if (hide) this.table.style.visibility = 'hidden';
	else hs.discardElement(this.table);
}
};

hs.Dimension = function(exp, dim) {
	this.exp = exp;
	this.dim = dim;
	this.ucwh = dim == 'x' ? 'Width' : 'Height';
	this.wh = this.ucwh.toLowerCase();
	this.uclt = dim == 'x' ? 'Left' : 'Top';
	this.lt = this.uclt.toLowerCase();
	this.ucrb = dim == 'x' ? 'Right' : 'Bottom';
	this.rb = this.ucrb.toLowerCase();
	this.p1 = this.p2 = 0;
};
hs.Dimension.prototype = {
get : function(key) {
	switch (key) {
		case 'loadingPos':
			return this.tpos + this.tb + (this.t - hs.loading['offset'+ this.ucwh]) / 2;
		case 'loadingPosXfade':
			return this.pos + this.cb+ this.p1 + (this.size - hs.loading['offset'+ this.ucwh]) / 2;
		case 'wsize':
			return this.size + 2 * this.cb + this.p1 + this.p2;
		case 'fitsize':
			return this.clientSize - this.marginMin - this.marginMax;
		case 'opos':
			return this.pos - (this.exp.outline ? this.exp.outline.offset : 0);
		case 'osize':
			return this.get('wsize') + (this.exp.outline ? 2*this.exp.outline.offset : 0);
		case 'imgPad':
			return this.imgSize ? Math.round((this.size - this.imgSize) / 2) : 0;

	}
},
calcBorders: function() {
	// correct for borders
	this.cb = (this.exp.content['offset'+ this.ucwh] - this.t) / 2;
	this.marginMax = hs['margin'+ this.ucrb] + 2 * this.cb;
},
calcThumb: function() {
	this.t = this.exp.el[this.wh] ? parseInt(this.exp.el[this.wh]) :
		this.exp.el['offset'+ this.ucwh];
	this.tpos = this.exp.tpos[this.dim];
	this.tb = (this.exp.el['offset'+ this.ucwh] - this.t) / 2;
	if (this.tpos == 0) {
		this.tpos = (hs.page[this.wh] / 2) + hs.page['scroll'+ this.uclt];
	};
},
calcExpanded: function() {
	var exp = this.exp;
	this.justify = 'auto';

	// get alignment
	if (exp.align == 'center') this.justify = 'center';
	else if (new RegExp(this.lt).test(exp.anchor)) this.justify = null;
	else if (new RegExp(this.rb).test(exp.anchor)) this.justify = 'max';


	// size and position
	this.pos = this.tpos - this.cb + this.tb;
	this.size = Math.min(this.full, exp['max'+ this.ucwh] || this.full);
	this.minSize = exp.allowSizeReduction ?
		Math.min(exp['min'+ this.ucwh], this.full) :this.full;
	if (exp.useBox)	{
		this.size = exp[this.wh];
		this.imgSize = this.full;
	}
	if (this.dim == 'x' && hs.padToMinWidth) this.minSize = exp.minWidth;
	this.target = exp['target'+ this.dim.toUpperCase()];
	this.marginMin = hs['margin'+ this.uclt];
	this.scroll = hs.page['scroll'+ this.uclt];
	this.clientSize = hs.page[this.wh];
},
setSize: function(i) {
	var exp = this.exp;
	if (exp.isImage && (exp.useBox || hs.padToMinWidth)) {
		this.imgSize = i;
		this.size = Math.max(this.size, this.imgSize);
		exp.content.style[this.lt] = this.get('imgPad')+'px';
	} else
	this.size = i;

	exp.content.style[this.wh] = i +'px';
	exp.wrapper.style[this.wh] = this.get('wsize') +'px';
	if (exp.outline) exp.outline.setPosition(exp);
	if (exp.releaseMask) exp.releaseMask.style[this.wh] = i +'px';
	if (exp.isHtml) {
		var d = exp.scrollerDiv;
		if (this.sizeDiff === undefined)
			this.sizeDiff = exp.innerContent['offset'+ this.ucwh] - d['offset'+ this.ucwh];
		d.style[this.wh] = (this.size - this.sizeDiff) +'px';

		if (this.dim == 'x') exp.mediumContent.style.width = 'auto';
		if (exp.body) exp.body.style[this.wh] = 'auto';
	}
	if (this.dim == 'x' && exp.overlayBox) exp.sizeOverlayBox(true);
	if (this.dim == 'x' && exp.slideshow && exp.isImage) {
		if (i == this.full) exp.slideshow.disable('full-expand');
		else exp.slideshow.enable('full-expand');
	}
},
setPos: function(i) {
	this.pos = i;
	this.exp.wrapper.style[this.lt] = i +'px';

	if (this.exp.outline) this.exp.outline.setPosition(this.exp);

}
};

hs.Expander = function(a, params, custom, contentType) {
	if (document.readyState && hs.ie && !hs.isDomReady) {
		hs.onDomReady = function() {
			new hs.Expander(a, params, custom, contentType);
		};
		return;
	}
	this.a = a;
	this.custom = custom;
	this.contentType = contentType || 'image';
	this.isHtml = (contentType == 'html');
	this.isImage = !this.isHtml;

	hs.continuePreloading = false;
	this.overlays = [];
	this.last = hs.last;
	hs.last = null;
	hs.init();
	var key = this.key = hs.expanders.length;

	// override inline parameters
	for (var i = 0; i < hs.overrides.length; i++) {
		var name = hs.overrides[i];
		this[name] = params && typeof params[name] != 'undefined' ?
			params[name] : hs[name];
	}
	if (!this.src) this.src = a.href;

	// get thumb
	var el = (params && params.thumbnailId) ? hs.$(params.thumbnailId) : a;
	el = this.thumb = el.getElementsByTagName('img')[0] || el;
	this.thumbsUserSetId = el.id || a.id;
	if (!hs.fireEvent(this, 'onInit')) return true;

	// check if already open
	for (var i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i] && hs.expanders[i].a == a
			&& !(this.last && this.transitions[1] == 'crossfade')) {
			hs.expanders[i].focus();
			return false;
		}
	}

	// cancel other
	for (var i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i] && hs.expanders[i].thumb != el && !hs.expanders[i].onLoadStarted) {
			hs.expanders[i].cancelLoading();
		}
	}
	hs.expanders[this.key] = this;
	if (!hs.allowMultipleInstances && !hs.upcoming) {
		if (hs.expanders[key-1]) hs.expanders[key-1].close();
		if (typeof hs.focusKey != 'undefined' && hs.expanders[hs.focusKey])
			hs.expanders[hs.focusKey].close();
	}

	// initiate metrics
	this.el = el;
	this.tpos = hs.getPosition(el);
	hs.page = hs.getPageSize();
	var x = this.x = new hs.Dimension(this, 'x');
	x.calcThumb();
	var y = this.y = new hs.Dimension(this, 'y');
	y.calcThumb();
	if (/area/i.test(el.tagName)) this.getImageMapAreaCorrection(el);

	// instanciate the wrapper
	this.wrapper = hs.createElement(
		'div', {
			id: 'highslide-wrapper-'+ this.key,
			className: this.wrapperClassName
		}, {
			visibility: 'hidden',
			position: 'absolute',
			zIndex: hs.zIndexCounter++
		}, null, true );

	this.wrapper.onmouseover = this.wrapper.onmouseout = hs.wrapperMouseHandler;
	if (this.contentType == 'image' && this.outlineWhileAnimating == 2)
		this.outlineWhileAnimating = 0;

	// get the outline
	if (!this.outlineType
		|| (this.last && this.isImage && this.transitions[1] == 'crossfade')) {
		this[this.contentType +'Create']();

	} else if (hs.pendingOutlines[this.outlineType]) {
		this.connectOutline();
		this[this.contentType +'Create']();

	} else {
		this.showLoading();
		var exp = this;
		new hs.Outline(this.outlineType,
			function () {
				exp.connectOutline();
				exp[exp.contentType +'Create']();
			}
		);
	}
	return true;
};

hs.Expander.prototype = {

connectOutline : function() {
	var o = this.outline = hs.pendingOutlines[this.outlineType];
	o.table.style.zIndex = this.wrapper.style.zIndex;
	hs.pendingOutlines[this.outlineType] = null;
},

showLoading : function() {
	if (this.onLoadStarted || this.loading) return;

	this.loading = hs.loading;
	var exp = this;
	this.loading.onclick = function() {
		exp.cancelLoading();
	};


	if (!hs.fireEvent(this, 'onShowLoading')) return;
	var exp = this,
		l = this.x.get('loadingPos') +'px',
		t = this.y.get('loadingPos') +'px';
	if (!tgt && this.last && this.transitions[1] == 'crossfade')
		var tgt = this.last;
	if (tgt) {
		l = tgt.x.get('loadingPosXfade') +'px';
		t = tgt.y.get('loadingPosXfade') +'px';
		this.loading.style.zIndex = hs.zIndexCounter++;
	}
	setTimeout(function () {
		if (exp.loading) hs.setStyles(exp.loading, { left: l, top: t, zIndex: hs.zIndexCounter++ })}
	, 100);
},

imageCreate : function() {
	var exp = this;

	var img = document.createElement('img');
    this.content = img;
    img.onload = function () {
    	if (hs.expanders[exp.key]) exp.contentLoaded();
	};
    if (hs.blockRightClick) img.oncontextmenu = function() { return false; };
    img.className = 'highslide-image';
    hs.setStyles(img, {
    	visibility: 'hidden',
    	display: 'block',
    	position: 'absolute',
		maxWidth: '9999px',
		zIndex: 3
	});
    img.title = hs.lang.restoreTitle;
    if (hs.safari) hs.container.appendChild(img);
    if (hs.ie && hs.flushImgSize) img.src = null;
	img.src = this.src;

	this.showLoading();
},

htmlCreate : function () {
	if (!hs.fireEvent(this, 'onBeforeGetContent')) return;

	this.content = hs.getCacheBinding(this.a);
	if (!this.content)
		this.content = hs.getNode(this.contentId);
	if (!this.content)
		this.content = hs.getSelfRendered();
	this.getInline(['maincontent']);
	if (this.maincontent) {
		var body = hs.getElementByClass(this.content, 'div', 'highslide-body');
		if (body) body.appendChild(this.maincontent);
		this.maincontent.style.display = 'block';
	}
	hs.fireEvent(this, 'onAfterGetContent');

	this.innerContent = this.content;

	if (/(swf|iframe)/.test(this.objectType)) this.setObjContainerSize(this.innerContent);

	// the content tree
	hs.container.appendChild(this.wrapper);
	hs.setStyles( this.wrapper, {
		position: 'static',
		padding: '0 '+ hs.marginRight +'px 0 '+ hs.marginLeft +'px'
	});
	this.content = hs.createElement(
    	'div', {
    		className: 'highslide-html'
    	}, {
			position: 'relative',
			zIndex: 3,
			overflow: 'hidden'
		},
		this.wrapper
	);
	this.mediumContent = hs.createElement('div', null, null, this.content, 1);
	this.mediumContent.appendChild(this.innerContent);

	hs.setStyles (this.innerContent, {
		position: 'relative',
		display: 'block',
		direction: hs.lang.cssDirection || ''
	});
	if (this.width) this.innerContent.style.width = this.width+'px';
	if (this.height) this.innerContent.style.height = this.height+'px';
	if (this.innerContent.offsetWidth < this.minWidth)
		this.innerContent.style.width = this.minWidth +'px';


	if (this.objectType == 'ajax' && !hs.getCacheBinding(this.a)) {
		this.showLoading();
    	var ajax = new hs.Ajax(this.a, this.innerContent);
    	var exp = this;
    	ajax.onLoad = function () {	if (hs.expanders[exp.key]) exp.contentLoaded(); };
    	ajax.onError = function () { location.href = exp.src; };
    	ajax.run();
	}
    else

    if (this.objectType == 'iframe' && this.objectLoadTime == 'before') {
		this.writeExtendedContent();
	}
    else
    	this.contentLoaded();
},

contentLoaded : function() {
	try {
		if (!this.content) return;
		this.content.onload = null;
		if (this.onLoadStarted) return;
		else this.onLoadStarted = true;

		var x = this.x, y = this.y;

		if (this.loading) {
			hs.setStyles(this.loading, { top: '-9999px' });
			this.loading = null;
			hs.fireEvent(this, 'onHideLoading');
		}

		hs.setStyles (this.wrapper, {
			left: x.tpos +'px',
			top: y.tpos +'px'
		});
		if (this.isImage) {
			x.full = this.content.width;
			y.full = this.content.height;

			hs.setStyles(this.content, {
				width: this.x.t +'px',
				height: this.y.t +'px'
			});
			this.wrapper.appendChild(this.content);
			hs.container.appendChild(this.wrapper);
		} else if (this.htmlGetSize) this.htmlGetSize();

		x.calcBorders();
		y.calcBorders();


		this.initSlideshow();
		this.getOverlays();

		var ratio = x.full / y.full;

		x.calcExpanded();
		this.justify(x);

		y.calcExpanded();
		this.justify(y);
		if (this.isHtml) this.htmlSizeOperations();
		if (this.overlayBox) this.sizeOverlayBox(0, 1);

		if (this.allowSizeReduction) {
			if (this.isImage)
				this.correctRatio(ratio);
			else this.fitOverlayBox();
			var ss = this.slideshow;
			if (ss && this.last && ss.controls && ss.fixedControls) {
				var pos = ss.overlayOptions.position || '', p;
				for (var dim in hs.oPos) for (var i = 0; i < 5; i++) {
					p = this[dim];
					if (pos.match(hs.oPos[dim][i])) {
						p.pos = this.last[dim].pos
							+ (this.last[dim].p1 - p.p1)
							+ (this.last[dim].size - p.size) * [0, 0, .5, 1, 1][i];
						if (ss.fixedControls == 'fit') {
							if (p.pos + p.size + p.p1 + p.p2 > p.scroll + p.clientSize - p.marginMax)
								p.pos = p.scroll + p.clientSize - p.size - p.marginMin - p.marginMax - p.p1 - p.p2;
							if (p.pos < p.scroll + p.marginMin) p.pos = p.scroll + p.marginMin;
						}
					}
				}
			}
			if (this.isImage && this.x.full > (this.x.imgSize || this.x.size)) {
				this.createFullExpand();
				if (this.overlays.length == 1) this.sizeOverlayBox();
			}
		}
		this.show();

	} catch (e) {
		window.location.href = this.src;
	}
},


setObjContainerSize : function(parent, auto) {
	var c = hs.getElementByClass(parent, 'DIV', 'highslide-body');
	if (/(iframe|swf)/.test(this.objectType)) {
		if (this.objectWidth) c.style.width = this.objectWidth +'px';
		if (this.objectHeight) c.style.height = this.objectHeight +'px';
	}
},

writeExtendedContent : function () {
	if (this.hasExtendedContent) return;
	var exp = this;
	this.body = hs.getElementByClass(this.innerContent, 'DIV', 'highslide-body');
	if (this.objectType == 'iframe') {
		this.showLoading();
		var ruler = hs.clearing.cloneNode(1);
		this.body.appendChild(ruler);
		this.newWidth = this.innerContent.offsetWidth;
		if (!this.objectWidth) this.objectWidth = ruler.offsetWidth;
		var hDiff = this.innerContent.offsetHeight - this.body.offsetHeight,
			h = this.objectHeight || (hs.getPageSize()).height - hDiff - hs.marginTop - hs.marginBottom,
			onload = this.objectLoadTime == 'before' ?
				' onload="if (hs.expanders['+ this.key +']) hs.expanders['+ this.key +'].contentLoaded()" ' : '';

		this.body.innerHTML += '<iframe name="hs'+ (new Date()).getTime() +'" frameborder="0" key="'+ this.key +'" '
			+' allowtransparency="true" style="width:'+ this.objectWidth +'px; height:'+ h +'px" '
			+ onload +' src="'+ this.src +'"></iframe>';
		this.ruler = this.body.getElementsByTagName('div')[0];
		this.iframe = this.body.getElementsByTagName('iframe')[0];

		if (this.objectLoadTime == 'after') this.correctIframeSize();

	}
	if (this.objectType == 'swf') {
		this.body.id = this.body.id || 'hs-flash-id-' + this.key;
		var a = this.swfOptions;
		if (typeof a.params.wmode == 'undefined') a.params.wmode = 'transparent';
		if (swfobject) swfobject.embedSWF(this.src, this.body.id, this.objectWidth, this.objectHeight,
			a.version || '7', a.expressInstallSwfurl, a.flashvars, a.params, a.attributes);
	}
	this.hasExtendedContent = true;
},
htmlGetSize : function() {
	if (this.iframe && !this.objectHeight) { // loadtime before
		this.iframe.style.height = this.body.style.height = this.getIframePageHeight() +'px';
	}
	this.innerContent.appendChild(hs.clearing);
	if (!this.x.full) this.x.full = this.innerContent.offsetWidth;
    this.y.full = this.innerContent.offsetHeight;
    this.innerContent.removeChild(hs.clearing);
    if (hs.ie && this.newHeight > parseInt(this.innerContent.currentStyle.height)) { // ie css bug
		this.newHeight = parseInt(this.innerContent.currentStyle.height);
	}
	hs.setStyles( this.wrapper, { position: 'absolute',	padding: '0'});
	hs.setStyles( this.content, { width: this.x.t +'px',	height: this.y.t +'px'});
},

getIframePageHeight : function() {
	var h;
	try {
		var doc = this.iframe.contentDocument || this.iframe.contentWindow.document;
		var clearing = doc.createElement('div');
		clearing.style.clear = 'both';
		doc.body.appendChild(clearing);
		h = clearing.offsetTop;
		if (hs.ie) h += parseInt(doc.body.currentStyle.marginTop)
			+ parseInt(doc.body.currentStyle.marginBottom) - 1;
	} catch (e) { // other domain
		h = 300;
	}
	return h;
},
correctIframeSize : function () {
	var wDiff = this.innerContent.offsetWidth - this.ruler.offsetWidth;
	if (wDiff < 0) wDiff = 0;

	var hDiff = this.innerContent.offsetHeight - this.iframe.offsetHeight;
	hs.setStyles(this.iframe, { width: (this.x.size - wDiff) +'px',
		height: (this.y.size - hDiff) +'px' });
    hs.setStyles(this.body, { width: this.iframe.style.width,
    	height: this.iframe.style.height });

    this.scrollingContent = this.iframe;
    this.scrollerDiv = this.scrollingContent;
},
htmlSizeOperations : function () {

	this.setObjContainerSize(this.innerContent);


	if (this.objectType == 'swf' && this.objectLoadTime == 'before') this.writeExtendedContent();

    // handle minimum size
    if (this.x.size < this.x.full && !this.allowWidthReduction) this.x.size = this.x.full;
    if (this.y.size < this.y.full && !this.allowHeightReduction) this.y.size = this.y.full;
	this.scrollerDiv = this.innerContent;
    hs.setStyles(this.mediumContent, {
		width: this.x.size +'px',
		position: 'relative',
		left: (this.x.pos - this.x.tpos) +'px',
		top: (this.y.pos - this.y.tpos) +'px'
	});
    hs.setStyles(this.innerContent, {
    	border: 'none',
    	width: 'auto',
    	height: 'auto'
    });
	var node = hs.getElementByClass(this.innerContent, 'DIV', 'highslide-body');
    if (node && !/(iframe|swf)/.test(this.objectType)) {
    	var cNode = node; // wrap to get true size
    	node = hs.createElement(cNode.nodeName, null, {overflow: 'hidden'}, null, true);
    	cNode.parentNode.insertBefore(node, cNode);
    	node.appendChild(hs.clearing); // IE6
    	node.appendChild(cNode);

    	var wDiff = this.innerContent.offsetWidth - node.offsetWidth;
    	var hDiff = this.innerContent.offsetHeight - node.offsetHeight;
    	node.removeChild(hs.clearing);

    	var kdeBugCorr = hs.safari || navigator.vendor == 'KDE' ? 1 : 0; // KDE repainting bug
    	hs.setStyles(node, {
    			width: (this.x.size - wDiff - kdeBugCorr) +'px',
    			height: (this.y.size - hDiff) +'px',
    			overflow: 'auto',
    			position: 'relative'
    		}
    	);
		if (kdeBugCorr && cNode.offsetHeight > node.offsetHeight)	{
    		node.style.width = (parseInt(node.style.width) + kdeBugCorr) + 'px';
		}
    	this.scrollingContent = node;
    	this.scrollerDiv = this.scrollingContent;
	}
    if (this.iframe && this.objectLoadTime == 'before') this.correctIframeSize();
    if (!this.scrollingContent && this.y.size < this.mediumContent.offsetHeight) this.scrollerDiv = this.content;

	if (this.scrollerDiv == this.content && !this.allowWidthReduction && !/(iframe|swf)/.test(this.objectType)) {
		this.x.size += 17; // room for scrollbars
	}
	if (this.scrollerDiv && this.scrollerDiv.offsetHeight > this.scrollerDiv.parentNode.offsetHeight) {
		setTimeout("try { hs.expanders["+ this.key +"].scrollerDiv.style.overflow = 'auto'; } catch(e) {}",
			 hs.expandDuration);
	}
},

getImageMapAreaCorrection : function(area) {
	var c = area.coords.split(',');
	for (var i = 0; i < c.length; i++) c[i] = parseInt(c[i]);

	if (area.shape.toLowerCase() == 'circle') {
		this.x.tpos += c[0] - c[2];
		this.y.tpos += c[1] - c[2];
		this.x.t = this.y.t = 2 * c[2];
	} else {
		var maxX, maxY, minX = maxX = c[0], minY = maxY = c[1];
		for (var i = 0; i < c.length; i++) {
			if (i % 2 == 0) {
				minX = Math.min(minX, c[i]);
				maxX = Math.max(maxX, c[i]);
			} else {
				minY = Math.min(minY, c[i]);
				maxY = Math.max(maxY, c[i]);
			}
		}
		this.x.tpos += minX;
		this.x.t = maxX - minX;
		this.y.tpos += minY;
		this.y.t = maxY - minY;
	}
},
justify : function (p, moveOnly) {
	var tgtArr, tgt = p.target, dim = p == this.x ? 'x' : 'y';

	if (tgt && tgt.match(/ /)) {
		tgtArr = tgt.split(' ');
		tgt = tgtArr[0];
	}
	if (tgt && hs.$(tgt)) {
		p.pos = hs.getPosition(hs.$(tgt))[dim];
		if (tgtArr && tgtArr[1] && tgtArr[1].match(/^[-]?[0-9]+px$/))
			p.pos += parseInt(tgtArr[1]);

	} else if (p.justify == 'auto' || p.justify == 'center') {

		var hasMovedMin = false;

		var allowReduce = p.exp.allowSizeReduction;
		if (p.justify == 'center')
			p.pos = Math.round(p.scroll + (p.clientSize - p.get('wsize')) / 2);
		else
			p.pos = Math.round(p.pos - ((p.get('wsize') - p.t) / 2));
		if (p.pos < p.scroll + p.marginMin) {
			p.pos = p.scroll + p.marginMin;
			hasMovedMin = true;
		}
		if (!moveOnly && p.size < p.minSize) {
			p.size = p.minSize;
			allowReduce = false;
		}
		if (p.pos + p.get('wsize') > p.scroll + p.clientSize - p.marginMax) {
			if (!moveOnly && hasMovedMin && allowReduce) {
				p.size = p.get('fitsize')- 2 * p.cb - p.p1 - p.p2; // can't expand more
			} else if (p.get('wsize') < p.get('fitsize')) {
				p.pos = p.scroll + p.clientSize - p.marginMax - p.get('wsize');
			} else { // image larger than viewport
				p.pos = p.scroll + p.marginMin;
				if (!moveOnly && allowReduce) p.size = p.get('fitsize')- 2 * p.cb - p.p1 - p.p2;
			}
		}

		if (!moveOnly && p.size < p.minSize) {
			p.size = p.minSize;
			allowReduce = false;
		}


	} else if (p.justify == 'max') {
		p.pos = Math.floor(p.pos - p.size + p.t);
	}


	if (p.pos < p.marginMin) {
		var tmpMin = p.pos;
		p.pos = p.marginMin;

		if (allowReduce && !moveOnly) p.size = p.size - (p.pos - tmpMin);

	}
},

correctRatio : function(ratio) {
	var x = this.x,
		y = this.y,
		changed = false,
		xSize = Math.min(x.full, x.size),
		ySize = Math.min(y.full, y.size),
		useBox = (this.useBox || hs.padToMinWidth);

	if (xSize / ySize > ratio) { // width greater
		xSize = ySize * ratio;
		if (xSize < x.minSize) { // below minWidth
			xSize = x.minSize;
			ySize = xSize / ratio;
		}
		changed = true;

	} else if (xSize / ySize < ratio) { // height greater
		ySize = xSize / ratio;
		changed = true;
	}

	if (hs.padToMinWidth && x.full < x.minSize) {
		x.imgSize = x.full;
		y.size = y.imgSize = y.full;
	} else if (this.useBox) {
		x.imgSize = xSize;
		y.imgSize = ySize;
	} else {
		x.size = xSize;
		y.size = ySize;
	}
	this.fitOverlayBox(useBox ? null : ratio);
	if (useBox && y.size < y.imgSize) {
		y.imgSize = y.size;
		x.imgSize = y.size * ratio;
	}
	if (changed || useBox) {
		x.pos = x.tpos - x.cb + x.tb;
		x.minSize = x.size;
		this.justify(x, true);

		y.pos = y.tpos - y.cb + y.tb;
		y.minSize = y.size;
		this.justify(y, true);
		if (this.overlayBox) this.sizeOverlayBox();
	}
},
fitOverlayBox : function(ratio) {
	var x = this.x, y = this.y;
	if (this.overlayBox) {
		while (y.size > this.minHeight && x.size > this.minWidth
				&&  y.get('wsize') > y.get('fitsize')) {
			y.size -= 10;
			if (ratio) x.size = y.size * ratio;
			this.sizeOverlayBox(0, 1);
		}
	}
},

reflow : function () {
	var h = /iframe/i.test(this.scrollerDiv.tagName) ? this.getIframePageHeight() + 1 +'px' : 'auto';
	if (this.body) this.body.style.height = h;
	this.scrollerDiv.style.height = h;
	this.y.setSize(this.innerContent.offsetHeight);
},

show : function () {
	this.doShowHide('hidden');
	hs.fireEvent(this, 'onBeforeExpand');
	// Apply size change
	this.changeSize(
		1,
		{
			xpos: this.x.tpos + this.x.tb - this.x.cb,
			ypos: this.y.tpos + this.y.tb - this.y.cb,
			xsize: this.x.t,
			ysize: this.y.t,
			xp1: 0,
			xp2: 0,
			yp1: 0,
			yp2: 0,
			ximgSize: this.x.t,
			ximgPad: 0,
			yimgSize: this.y.t,
			yimgPad: 0,
			o: hs.outlineStartOffset
		},
		{
			xpos: this.x.pos,
			ypos: this.y.pos,
			xsize: this.x.size,
			ysize: this.y.size,
			xp1: this.x.p1,
			yp1: this.y.p1,
			xp2: this.x.p2,
			yp2: this.y.p2,
			ximgSize: this.x.imgSize,
			ximgPad: this.x.get('imgPad'),
			yimgSize: this.y.imgSize,
			yimgPad: this.y.get('imgPad'),
			o: this.outline ? this.outline.offset : 0
		},
		hs.expandDuration
	);
},

changeSize : function(up, from, to, dur) {
	// transition
	var trans = this.transitions,
	other = up ? (this.last ? this.last.a : null) : hs.upcoming,
	t = (trans[1] && other
			&& hs.getParam(other, 'transitions')[1] == trans[1]) ?
		trans[1] : trans[0];

	if (this[t] && t != 'expand') {
		this[t](up, from, to);
		return;
	}
	if (up) hs.setStyles(this.wrapper, { opacity: 1 });

	if (this.outline && !this.outlineWhileAnimating) {
		if (up) this.outline.setPosition(this);
		else this.outline.destroy(
				(this.isHtml && this.preserveContent));
	}


	if (!up && this.overlayBox) {
		if (this.slideshow) {
			var c = this.slideshow.controls;
			if (c && hs.getExpander(c) == this) c.parentNode.removeChild(c);
		}
		if (this.isHtml && this.preserveContent) {
			this.overlayBox.style.top = '-9999px';
			hs.container.appendChild(this.overlayBox);
		} else
		hs.discardElement(this.overlayBox);
	}
	if (this.fadeInOut) {
		from.op = up ? 0 : 1;
		to.op = up;
	}
	var t,
		exp = this,
		easing = Math[this.easing] || Math.easeInQuad,
		steps = (up ? hs.expandSteps : hs.restoreSteps) || parseInt(dur / 25) || 1;
	if (!up) easing = Math[this.easingClose] || easing;
	for (var i = 1; i <= steps ; i++) {
		t = Math.round(i * (dur / steps));

		(function(){
			var pI = i, size = {};

			for (var x in from) {
				size[x] = easing(t, from[x], to[x] - from[x], dur);
				if (isNaN(size[x])) size[x] = to[x];
				if (!/^op$/.test(x)) size[x] = Math.round(size[x]);
			}
			setTimeout ( function() {
				if (up && pI == 1) {
					exp.content.style.visibility = 'visible';
					exp.a.className += ' highslide-active-anchor';
				}
				exp.setSize(size);
			}, t);
		})();
	}

	if (up) {

		setTimeout(function() {
			if (exp.outline) exp.outline.table.style.visibility = "visible";
		}, t);
		setTimeout(function() {
			exp.afterExpand();
		}, t + 50);
	}
	else setTimeout(function() { exp.afterClose(); }, t);
},

setSize : function (to) {
	try {
		if (to.op) hs.setStyles(this.wrapper, { opacity: to.op });
		hs.setStyles ( this.wrapper, {
			width : (to.xsize +to.xp1 + to.xp2 +
				2 * this.x.cb) +'px',
			height : (to.ysize +to.yp1 + to.yp2 +
				2 * this.y.cb) +'px',
			left: to.xpos +'px',
			top: to.ypos +'px'
		});
		hs.setStyles(this.content, {
			left: (to.xp1 + to.ximgPad) +'px',
			top: (to.yp1 + to.yimgPad) +'px',
			width: (to.ximgSize ||to.xsize) +'px',
			height: (to.yimgSize ||to.ysize) +'px'
		});
		if (this.isHtml) {
			hs.setStyles(this.mediumContent, {
				left: (this.x.pos - to.xpos
					+ this.x.p1 - to.xp1) +'px',
				top: (this.y.pos - to.ypos
					+ this.y.p1 - to.yp1) +'px'
			});
			this.innerContent.style.visibility = 'visible';
		}

		if (this.outline && this.outlineWhileAnimating) {
			var o = this.outline.offset - to.o;
			this.outline.setPosition(this, {
				x: to.xpos + o,
				y: to.ypos + o,
				w: to.xsize + to.xp1 + to.xp2 + - 2 * o,
				h: to.ysize + to.yp1 + to.yp2 + - 2 * o
			}, 1);
		}

		this.wrapper.style.visibility = 'visible';

	} catch (e) {
		window.location.href = this.src;
	}
},

fade : function(up, from, to) {
	this.outlineWhileAnimating = false;
	var exp = this,	t = up ? 250 : 0;

	if (up) {
		hs.setStyles(this.wrapper, { opacity: 0 });
		this.setSize(to);
		this.content.style.visibility = 'visible';

		hs.fade (this.wrapper, 0, 1);
	}

	if (this.outline) {
		this.outline.table.style.zIndex = this.wrapper.style.zIndex;
		var dir = up || -1;
		for (var i = from.o; dir * i <= dir * to.o; i += dir, t += 25) {
			(function() {
				var o = up ? to.o - i : from.o - i;
				setTimeout(function() {
					exp.outline.setPosition(exp, {
						x: (exp.x.pos + o),
						y: (exp.y.pos + o),
						w: (exp.x.size - 2 * o + exp.x.p1 + exp.x.p2),
						h: (exp.y.size - 2 * o + exp.y.p1 + exp.y.p2)
					}, 1);
				}, t);
			})();
		}
	}


	if (up) setTimeout(function() { exp.afterExpand(); }, t+50);
	else {
		setTimeout( function() {
			if (exp.outline) exp.outline.destroy(exp.preserveContent);
			hs.fade (exp.wrapper, 1, 0);
			setTimeout( function() {
				exp.afterClose();
			}, 250);
		}, t);
	}
},

crossfade : function (up, from, to) {

	if (!up) return;
	var exp = this, steps = parseInt(hs.transitionDuration / 25) || 1, last = this.last;
	hs.removeEventListener(document, 'mousemove', hs.dragHandler);

	hs.setStyles(this.content, {
		width: (to.ximgSize ||to.xsize) +'px',
		height: (to.yimgSize ||to.ysize) +'px'
	});
	this.outline = this.last.outline;
	this.last.outline = null;
	this.fadeBox = hs.createElement('div', {
		className: 'highslide-image'
	}, {
		position: 'absolute',
		zIndex: 4,
		overflow: 'hidden',
		display: 'none'
	});
	if (this.isHtml) {
		hs.setStyles(this.mediumContent, {
			left: '0px',
			top: '0px'
		});
	}
	var names = { oldImg: last, newImg: this };
	for (var x in names) {
		this[x] = names[x].content.cloneNode(1);
		hs.setStyles(this[x], {
			position: 'absolute',
			border: 0,
			visibility: 'visible'
		});
		this.fadeBox.appendChild(this[x]);
	}
	this.wrapper.appendChild(this.fadeBox);
	from = {
		xpos: last.x.pos,
		xsize: last.x.size,
		xp1: last.x.p1,
		xp2: last.x.p2,
		ximgSize: last.x.imgSize || last.x.size,
		ximgPad: last.x.get('imgPad'),
		yimgSize: last.y.imgSize || last.y.size,
		yimgPad: last.y.get('imgPad'),
		ypos: last.y.pos,
		ysize: last.y.size,
		yp1: last.y.p1,
		yp2: last.y.p2,
		o: 1 / steps
	};
	to.ysize = this.y.size;
	to.o = 1;
	if (!to.ximgSize) to.ximgSize = to.xsize;
	if (!to.yimgSize) to.yimgSize = to.ysize;

	var t, easing = Math.easeInOutQuad;

	if (steps > 1) this.crossfadeStep(from);
	function prep() {
		if (exp.overlayBox) {
			exp.overlayBox.className = '';
			exp.overlayBox.style.overflow = 'visible';
			exp.wrapper.appendChild(exp.overlayBox);

			for (var i = 0; i < exp.last.overlays.length; i++) {
				var oDiv = hs.$('hsId'+ exp.last.overlays[i]);
				if (oDiv.reuse === exp.key) exp.overlayBox.appendChild(oDiv);
				else hs.fade(oDiv, oDiv.opacity, 0);
			}
		}
		exp.fadeBox.style.display = '';
		exp.last.content.style.display = 'none';
	};
	if (/rv:1\.[0-8].+Gecko/.test(navigator.userAgent)) setTimeout(prep, 0);
	else prep();
	if (hs.safari) {
		var match = navigator.userAgent.match(/Safari\/([0-9]{3})/);
		if (match && parseInt(match[1]) < 525) this.wrapper.style.visibility = 'visible';
	}

	for (var i = 1; i <= steps; i++) {
		t = Math.round(i * (hs.transitionDuration / steps));

		(function(){
			var size = {}, pI = i;
			for (var x in from)	{
				var val = easing(t, from[x], to[x] - from[x], hs.transitionDuration);
				if (isNaN(val)) val = to[x];
				size[x] = (x != 'o') ? Math.round(val) : val;
			}

			setTimeout ( function() {
				exp.crossfadeStep(size);
			}, t);
		})();
	}
	setTimeout ( function () {
		exp.crossfadeEnd();
	}, t + 100);

},

crossfadeStep : function (size) {
	try {
		if (this.outline) this.outline.setPosition(this, {
			x: size.xpos,
			y: size.ypos,
			w: size.xsize + size.xp1 + size.xp2,
			h: size.ysize + size.yp1 + size.yp2
		}, 1);
		this.last.wrapper.style.clip = 'rect('
			+ (size.ypos - this.last.y.pos)+'px, '
			+ (size.xsize + size.xp1 + size.xp2 + size.xpos + 2 * this.last.x.cb - this.last.x.pos) +'px, '
			+ (size.ysize + size.yp1 + size.yp2 + size.ypos + 2 * this.last.y.cb - this.last.y.pos) +'px, '
			+ (size.xpos - this.last.x.pos)+'px)';


		hs.setStyles(this.content, {
			top: (size.yp1 + this.y.get('imgPad')) +'px',
			left: (size.xp1 + this.x.get('imgPad')) +'px',
			marginTop: (this.y.pos - size.ypos) +'px',
			marginLeft: (this.x.pos - size.xpos) +'px'
		});

		hs.setStyles(this.wrapper, {
			top: size.ypos +'px',
			left: size.xpos +'px',
			width: (size.xp1 + size.xp2 + size.xsize + 2 * this.x.cb)+ 'px',
			height: (size.yp1 + size.yp2 + size.ysize + 2 * this.y.cb) + 'px'
		});
		hs.setStyles(this.fadeBox, {
			width: (size.ximgSize || size.xsize) + 'px',
			height: (size.yimgSize || size.ysize) +'px',
			left: (size.xp1 + size.ximgPad)  +'px',
			top: (size.yp1 + size.yimgPad) +'px',
			visibility: 'visible'
		});

		hs.setStyles(this.oldImg, {
			top: (this.last.y.pos - size.ypos + this.last.y.p1 - size.yp1 +
				this.last.y.get('imgPad') - size.yimgPad)+'px',
			left: (this.last.x.pos - size.xpos + this.last.x.p1 - size.xp1 +
				this.last.x.get('imgPad') - size.ximgPad)+'px'
		});

		hs.setStyles(this.newImg, {
			opacity: size.o,
			top: (this.y.pos - size.ypos + this.y.p1 - size.yp1 + this.y.get('imgPad') - size.yimgPad) +'px',
			left: (this.x.pos - size.xpos + this.x.p1 - size.xp1 + this.x.get('imgPad') - size.ximgPad) +'px'
		});
		hs.setStyles(this.overlayBox, {
			width: size.xsize + 'px',
			height: size.ysize +'px',
			left: (size.xp1 + this.x.cb)  +'px',
			top: (size.yp1 + this.y.cb) +'px'
		});
	} catch (e) {}
},
crossfadeEnd : function() {
	this.wrapper.style.background = this.wrapperBG || '';

	this.wrapper.style.visibility = this.content.style.visibility = 'visible';
	this.fadeBox.style.display = 'none';
	this.a.className += ' highslide-active-anchor';
	this.afterExpand();
	this.last.afterClose();
},
reuseOverlay : function(o, el) {
	if (!this.last) return false;
	for (var i = 0; i < this.last.overlays.length; i++) {
		var oDiv = hs.$('hsId'+ this.last.overlays[i]);
		if (oDiv && oDiv.hsId == o.hsId) {
			this.genOverlayBox();
			oDiv.reuse = this.key;
			hs.push(this.overlays, this.last.overlays[i]);
			return true;
		}
	}
	return false;
},


afterExpand : function() {
	this.isExpanded = true;
	this.focus();

	if (this.isHtml && this.objectLoadTime == 'after') this.writeExtendedContent();

	if (this.isHtml) {
		if (this.iframe) {
			try {
				var exp = this,
					doc = this.iframe.contentDocument || this.iframe.contentWindow.document;
				hs.addEventListener(doc, 'mousedown', function () {
					if (hs.focusKey != exp.key) exp.focus();
				});
			} catch(e) {}
			if (hs.ie && typeof this.isClosing != 'boolean') // first open
				this.iframe.style.width = (this.objectWidth - 1) +'px'; // hasLayout
		}
	}

	if (this.dimmingOpacity) hs.dim(this);
	if (hs.upcoming && hs.upcoming == this.a) hs.upcoming = null;
	this.prepareNextOutline();


	var p = hs.page, mX = hs.mouse.x + p.scrollLeft, mY = hs.mouse.y + p.scrollTop;
	this.mouseIsOver = this.x.pos < mX && mX < this.x.pos + this.x.get('wsize')
		&& this.y.pos < mY && mY < this.y.pos + this.y.get('wsize');

	if (this.overlayBox) this.showOverlays();
	hs.fireEvent(this, 'onAfterExpand');

},


prepareNextOutline : function() {
	var key = this.key;
	var outlineType = this.outlineType;
	new hs.Outline(outlineType,
		function () { try { hs.expanders[key].preloadNext(); } catch (e) {} });
},


preloadNext : function() {
	var next = this.getAdjacentAnchor(1);
	if (next && next.onclick.toString().match(/hs\.expand/))
		var img = hs.createElement('img', { src: hs.getSrc(next) });
},


getAdjacentAnchor : function(op) {
	var current = this.getAnchorIndex(), as = hs.anchors.groups[this.slideshowGroup || 'none'];

	/*< ? if ($cfg->slideshow) : ?>s*/
	if (!as[current + op] && this.slideshow && this.slideshow.repeat) {
		if (op == 1) return as[0];
		else if (op == -1) return as[as.length-1];
	}
	/*< ? endif ?>s*/
	return as[current + op] || null;
},

getAnchorIndex : function() {
	var arr = hs.anchors.groups[this.slideshowGroup || 'none'];
	for (var i = 0; i < arr.length; i++) {
		if (arr[i] == this.a) return i;
	}
	return null;
},


getNumber : function() {
	if (this[this.numberPosition]) {
		var arr = hs.anchors.groups[this.slideshowGroup || 'none'];
		var s = hs.lang.number.replace('%1', this.getAnchorIndex() + 1).replace('%2', arr.length);
		this[this.numberPosition].innerHTML =
			'<div class="highslide-number">'+ s +'</div>'+ this[this.numberPosition].innerHTML;
	}
},
initSlideshow : function() {
	if (!this.last) {
		for (var i = 0; i < hs.slideshows.length; i++) {
			var ss = hs.slideshows[i], sg = ss.slideshowGroup;
			if (typeof sg == 'undefined' || sg === null || sg === this.slideshowGroup)
				this.slideshow = new hs.Slideshow(ss);
		}
	} else {
		this.slideshow = this.last.slideshow;
	}
	var ss = this.slideshow;
	if (!ss) return;
	var exp = ss.exp = this;

	ss.checkFirstAndLast();
	ss.disable('full-expand');
	if (ss.controls) {
		var o = ss.overlayOptions || {};
		o.overlayId = ss.controls;
		o.hsId = 'controls';
		this.createOverlay(o);
	}
	if (!this.last && this.autoplay) ss.play(true);
	if (ss.autoplay) {
		ss.autoplay = setTimeout(function() {
			hs.next(exp.key);
		}, (ss.interval || 500));
	}
},

cancelLoading : function() {
	hs.expanders[this.key] = null;
	if (hs.upcoming == this.a) hs.upcoming = null;
	hs.undim(this.key);
	if (this.loading) hs.loading.style.left = '-9999px';
	hs.fireEvent(this, 'onHideLoading');
},

writeCredits : function () {
	if (this.credits) return;
	this.credits = hs.createElement('a', {
		href: hs.creditsHref,
		className: 'highslide-credits',
		innerHTML: hs.lang.creditsText,
		title: hs.lang.creditsTitle
	});
	this.createOverlay({
		overlayId: this.credits,
		position: 'top left',
		hsId: 'credits'
	});
},

getInline : function(types, addOverlay) {
	for (var i = 0; i < types.length; i++) {
		var type = types[i], s = null;
		if (type == 'caption' && !hs.fireEvent(this, 'onBeforeGetCaption')) return;
		else if (type == 'heading' && !hs.fireEvent(this, 'onBeforeGetHeading')) return;
		if (!this[type +'Id'] && this.thumbsUserSetId)
			this[type +'Id'] = type +'-for-'+ this.thumbsUserSetId;
		if (this[type +'Id']) this[type] = hs.getNode(this[type +'Id']);
		if (!this[type] && !this[type +'Text'] && this[type +'Eval']) try {
			s = eval(this[type +'Eval']);
		} catch (e) {}
		if (!this[type] && this[type +'Text']) {
			s = this[type +'Text'];
		}
		if (!this[type] && !s) {
			var next = this.a.nextSibling;
			while (next && !hs.isHsAnchor(next)) {
				if ((new RegExp('highslide-'+ type)).test(next.className || null)) {
					this[type] = next.cloneNode(1);
					break;
				}
				next = next.nextSibling;
			}
		}
		if (!this[type] && !s && this.numberPosition == type) s = '\n';

		if (!this[type] && s) this[type] = hs.createElement('div',
				{ className: 'highslide-'+ type, innerHTML: s } );

		if (addOverlay && this[type]) {
			var o = { position: (type == 'heading') ? 'above' : 'below' };
			for (var x in this[type+'Overlay']) o[x] = this[type+'Overlay'][x];
			o.overlayId = this[type];
			this.createOverlay(o);
		}
	}
},


// on end move and resize
doShowHide : function(visibility) {
	if (hs.hideSelects) this.showHideElements('SELECT', visibility);
	if (hs.hideIframes) this.showHideElements('IFRAME', visibility);
	if (hs.geckoMac) this.showHideElements('*', visibility);
},
showHideElements : function (tagName, visibility) {
	var els = document.getElementsByTagName(tagName);
	var prop = tagName == '*' ? 'overflow' : 'visibility';
	for (var i = 0; i < els.length; i++) {
		if (prop == 'visibility' || (document.defaultView.getComputedStyle(
				els[i], "").getPropertyValue('overflow') == 'auto'
				|| els[i].getAttribute('hidden-by') != null)) {
			var hiddenBy = els[i].getAttribute('hidden-by');
			if (visibility == 'visible' && hiddenBy) {
				hiddenBy = hiddenBy.replace('['+ this.key +']', '');
				els[i].setAttribute('hidden-by', hiddenBy);
				if (!hiddenBy) els[i].style[prop] = els[i].origProp;
			} else if (visibility == 'hidden') { // hide if behind
				var elPos = hs.getPosition(els[i]);
				elPos.w = els[i].offsetWidth;
				elPos.h = els[i].offsetHeight;
				if (!this.dimmingOpacity) { // hide all if dimming

					var clearsX = (elPos.x + elPos.w < this.x.get('opos')
						|| elPos.x > this.x.get('opos') + this.x.get('osize'));
					var clearsY = (elPos.y + elPos.h < this.y.get('opos')
						|| elPos.y > this.y.get('opos') + this.y.get('osize'));
				}
				var wrapperKey = hs.getWrapperKey(els[i]);
				if (!clearsX && !clearsY && wrapperKey != this.key) { // element falls behind image
					if (!hiddenBy) {
						els[i].setAttribute('hidden-by', '['+ this.key +']');
						els[i].origProp = els[i].style[prop];
						els[i].style[prop] = 'hidden';

					} else if (hiddenBy.indexOf('['+ this.key +']') == -1) {
						els[i].setAttribute('hidden-by', hiddenBy + '['+ this.key +']');
					}
				} else if ((hiddenBy == '['+ this.key +']' || hs.focusKey == wrapperKey)
						&& wrapperKey != this.key) { // on move
					els[i].setAttribute('hidden-by', '');
					els[i].style[prop] = els[i].origProp || '';
				} else if (hiddenBy && hiddenBy.indexOf('['+ this.key +']') > -1) {
					els[i].setAttribute('hidden-by', hiddenBy.replace('['+ this.key +']', ''));
				}

			}
		}
	}
},

focus : function() {
	this.wrapper.style.zIndex = hs.zIndexCounter++;
	// blur others
	for (var i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i] && i == hs.focusKey) {
			var blurExp = hs.expanders[i];
			blurExp.content.className += ' highslide-'+ blurExp.contentType +'-blur';
			if (blurExp.isImage) {
				blurExp.content.style.cursor = hs.ie ? 'hand' : 'pointer';
				blurExp.content.title = hs.lang.focusTitle;
			}
			hs.fireEvent(this, 'onBlur');
		}
	}

	// focus this
	if (this.outline) this.outline.table.style.zIndex
		= this.wrapper.style.zIndex;
	this.content.className = 'highslide-'+ this.contentType;
	if (this.isImage) {
		this.content.title = hs.lang.restoreTitle;

		if (hs.restoreCursor) {
			hs.styleRestoreCursor = window.opera ? 'pointer' : 'url('+ hs.graphicsDir + hs.restoreCursor +'), pointer';
			if (hs.ie && hs.ieVersion() < 6) hs.styleRestoreCursor = 'hand';
			this.content.style.cursor = hs.styleRestoreCursor;
		}
	}
	hs.focusKey = this.key;
	hs.addEventListener(document, window.opera ? 'keypress' : 'keydown', hs.keyHandler);
	hs.fireEvent(this, 'onFocus');
},
moveTo: function(x, y) {
	this.x.setPos(x);
	this.y.setPos(y);
},
resize : function (e) {
	var w, h, r = e.width / e.height;
	w = Math.max(e.width + e.dX, Math.min(this.minWidth, this.x.full));
	if (this.isImage && Math.abs(w - this.x.full) < 12) w = this.x.full;
	h = this.isHtml ? e.height + e.dY : w / r;
	if (h < Math.min(this.minHeight, this.y.full)) {
		h = Math.min(this.minHeight, this.y.full);
		if (this.isImage) w = h * r;
	}
	this.resizeTo(w, h);
},
resizeTo: function(w, h) {
	this.y.setSize(h);
	this.x.setSize(w);
},

close : function() {
	if (this.isClosing || !this.isExpanded) return;
	if (this.transitions[1] == 'crossfade' && hs.upcoming) {
		hs.getExpander(hs.upcoming).cancelLoading();
		hs.upcoming = null;
	}
	if (!hs.fireEvent(this, 'onBeforeClose')) return;
	this.isClosing = true;
	if (this.slideshow && !hs.upcoming) this.slideshow.pause();

	hs.removeEventListener(document, window.opera ? 'keypress' : 'keydown', hs.keyHandler);

	try {
		if (this.isHtml) this.htmlPrepareClose();
		this.content.style.cursor = 'default';
		this.changeSize(
			0, {
				xpos: this.x.pos,
				ypos: this.y.pos,
				xsize: this.x.size,
				ysize: this.y.size,
				xp1: this.x.p1,
				yp1: this.y.p1,
				xp2: this.x.p2,
				yp2: this.y.p2,
				ximgSize: this.x.imgSize,
				ximgPad: this.x.get('imgPad'),
				yimgSize: this.y.imgSize,
				yimgPad: this.y.get('imgPad'),
				o: this.outline ? this.outline.offset : 0
			}, {
				xpos: this.x.tpos - this.x.cb + this.x.tb,
				ypos: this.y.tpos - this.y.cb + this.y.tb,
				xsize: this.x.t,
				ysize: this.y.t,
				xp1: 0,
				yp1: 0,
				xp2: 0,
				yp2: 0,
				ximgSize: this.x.imgSize ? this.x.t : null,
				ximgPad: 0,
				yimgSize: this.y.imgSize ? this.y.t : null,
				yimgPad: 0,
				o: hs.outlineStartOffset
			},
			hs.restoreDuration
		);

	} catch (e) { this.afterClose(); }
},

htmlPrepareClose : function() {
	if (hs.geckoMac) { // bad redraws
		if (!hs.mask) hs.mask = hs.createElement('div', null,
			{ position: 'absolute' }, hs.container);
		hs.setStyles(hs.mask, { width: this.x.size +'px', height: this.y.size +'px',
			left: this.x.pos +'px', top: this.y.pos +'px', display: 'block' });
	}
	if (this.objectType == 'swf') try { hs.$(this.body.id).StopPlay(); } catch (e) {}

	if (this.objectLoadTime == 'after' && !this.preserveContent) this.destroyObject();
	if (this.scrollerDiv && this.scrollerDiv != this.scrollingContent)
		this.scrollerDiv.style.overflow = 'hidden';
},

destroyObject : function () {
	if (hs.ie && this.iframe)
		try { this.iframe.contentWindow.document.body.innerHTML = ''; } catch (e) {}
	if (this.objectType == 'swf') swfobject.removeSWF(this.body.id);
	this.body.innerHTML = '';
},

sleep : function() {
	if (this.outline) this.outline.table.style.display = 'none';
	this.releaseMask = null;
	this.wrapper.style.display = 'none';
	hs.push(hs.sleeping, this);
},

awake : function() {try {

	hs.expanders[this.key] = this;

	if (!hs.allowMultipleInstances &&hs.focusKey != this.key) {
		try { hs.expanders[hs.focusKey].close(); } catch (e){}
	}

	var z = hs.zIndexCounter++, stl = { display: '', zIndex: z };
	hs.setStyles (this.wrapper, stl);
	this.isClosing = false;

	var o = this.outline || 0;
	if (o) {
		if (!this.outlineWhileAnimating) stl.visibility = 'hidden';
		hs.setStyles (o.table, stl);
	}
	if (this.slideshow) {
		this.initSlideshow();
	}

	this.show();
} catch (e) {}


},

createOverlay : function (o) {
	var el = o.overlayId;
	if (typeof el == 'string') el = hs.getNode(el);
	if (!el || typeof el == 'string') return;
	if (!hs.fireEvent(this, 'onCreateOverlay', { overlay: el })) return;
	el.style.display = 'block';
	o.hsId = o.hsId || o.overlayId;
	if (this.transitions[1] == 'crossfade' && this.reuseOverlay(o, el)) return;
	this.genOverlayBox();
	var width = o.width && /^[0-9]+(px|%)$/.test(o.width) ? o.width : 'auto';
	if (/^(left|right)panel$/.test(o.position) && !/^[0-9]+px$/.test(o.width)) width = '200px';

	var overlay = hs.createElement(
		'div', {
			id: 'hsId'+ hs.idCounter++, hsId: o.hsId
		}, {
			position: 'absolute',
			visibility: 'hidden',
			width: width,
			direction: hs.lang.cssDirection || ''
		},
		this.overlayBox,
		true
	);

	overlay.appendChild(el);
	hs.setAttribs(overlay, {
		hideOnMouseOut: o.hideOnMouseOut,
		opacity: o.opacity || 1,
		hsPos: o.position,
		fade: o.fade
	});

	if (this.gotOverlays) {
		this.positionOverlay(overlay);
		if (!overlay.hideOnMouseOut || this.mouseIsOver) hs.fade(overlay, 0, overlay.opacity);
	}
	hs.push(this.overlays, hs.idCounter - 1);
},
positionOverlay : function(overlay) {
	var p = overlay.hsPos || 'middle center';
	if (/left$/.test(p)) overlay.style.left = 0;
	if (/center$/.test(p))	hs.setStyles (overlay, {
		left: '50%',
		marginLeft: '-'+ Math.round(overlay.offsetWidth / 2) +'px'
	});
	if (/right$/.test(p))	overlay.style.right = 0;

	if (/^leftpanel$/.test(p)) {
		hs.setStyles(overlay, {
			right: '100%',
			marginRight: this.x.cb +'px',
			top: - this.y.cb +'px',
			bottom: - this.y.cb +'px',
			overflow: 'auto'
		});
		this.x.p1 = overlay.offsetWidth;

	} else if (/^rightpanel$/.test(p)) {
		hs.setStyles(overlay, {
			left: '100%',
			marginLeft: this.x.cb +'px',
			top: - this.y.cb +'px',
			bottom: - this.y.cb +'px',
			overflow: 'auto'
		});
		this.x.p2 = overlay.offsetWidth;
	}
	if (/^top/.test(p)) overlay.style.top = 0;
	if (/^middle/.test(p))	hs.setStyles (overlay, {
		top: '50%',
		marginTop: '-'+ Math.round(overlay.offsetHeight / 2) +'px'
	});
	if (/^bottom/.test(p)) overlay.style.bottom = 0;
	if (/^above$/.test(p)) {
		hs.setStyles(overlay, {
			left: (- this.x.p1 - this.x.cb) +'px',
			right: (- this.x.p2 - this.x.cb) +'px',
			bottom: '100%',
			marginBottom: this.y.cb +'px',
			width: 'auto'
		});
		this.y.p1 = overlay.offsetHeight;

	} else if (/^below$/.test(p)) {
		hs.setStyles(overlay, {
			position: 'relative',
			left: (- this.x.p1 - this.x.cb) +'px',
			right: (- this.x.p2 - this.x.cb) +'px',
			top: '100%',
			marginTop: this.y.cb +'px',
			width: 'auto'
		});
		this.y.p2 = overlay.offsetHeight;
		overlay.style.position = 'absolute';
	}
},

getOverlays : function() {
	this.getInline(['heading', 'caption'], true);
	this.getNumber();
	if (this.caption) hs.fireEvent(this, 'onAfterGetCaption');
	if (this.heading) hs.fireEvent(this, 'onAfterGetHeading');
	if (this.heading && this.dragByHeading) this.heading.className += ' highslide-move';
	if (hs.showCredits) this.writeCredits();
	for (var i = 0; i < hs.overlays.length; i++) {
		var o = hs.overlays[i], tId = o.thumbnailId, sg = o.slideshowGroup;
		if ((!tId && !sg) || (tId && tId == this.thumbsUserSetId)
				|| (sg && sg === this.slideshowGroup)) {
			if (this.isImage || (this.isHtml && o.useOnHtml))
			this.createOverlay(o);
		}
	}
	var os = [];
	for (var i = 0; i < this.overlays.length; i++) {
		var o = hs.$('hsId'+ this.overlays[i]);
		if (/panel$/.test(o.hsPos)) this.positionOverlay(o);
		else hs.push(os, o);
	}
	for (var i = 0; i < os.length; i++) this.positionOverlay(os[i]);
	this.gotOverlays = true;
},
genOverlayBox : function() {
	if (!this.overlayBox) this.overlayBox = hs.createElement (
		'div', {
			className: this.wrapperClassName
		}, {
			position : 'absolute',
			width: this.x.size ? this.x.size +'px' : this.x.full +'px',
			height: 0,
			visibility : 'hidden',
			overflow : 'hidden',
			zIndex : hs.ie ? 4 : null
		},
		hs.container,
		true
	);
},
sizeOverlayBox : function(doWrapper, doPanels) {
	hs.setStyles( this.overlayBox, {
		width: this.x.size +'px',
		height: this.y.size +'px'
	});
	if (doWrapper || doPanels) {
		for (var i = 0; i < this.overlays.length; i++) {
			var o = hs.$('hsId'+ this.overlays[i]);
			var ie6 = (hs.ie && (hs.ieVersion() <= 6 || document.compatMode == 'BackCompat'));
			if (o && /^(above|below)$/.test(o.hsPos)) {
				if (ie6) {
					o.style.width = (this.overlayBox.offsetWidth + 2 * this.x.cb
						+ this.x.p1 + this.x.p2) +'px';
				}
				this.y[o.hsPos == 'above' ? 'p1' : 'p2'] = o.offsetHeight;
			}
			if (o && ie6 && /^(left|right)panel$/.test(o.hsPos)) {
				o.style.height = (this.overlayBox.offsetHeight + 2 * this.y.cb
						+ this.y.p1 + this.y.p2) +'px';
			}
		}
	}
	if (doWrapper) {
		hs.setStyles(this.content, {
			top: this.y.p1 +'px'
		});
		hs.setStyles(this.overlayBox, {
			top: (this.y.p1 + this.y.cb) +'px'
		});
	}
},

showOverlays : function() {
	var b = this.overlayBox;
	b.className = '';
	hs.setStyles(b, {
		top: (this.y.p1 + this.y.cb) +'px',
		left: (this.x.p1 + this.x.cb) +'px',
		overflow : 'visible'
	});
	if (hs.safari) b.style.visibility = 'visible';
	this.wrapper.appendChild (b);
	for (var i = 0; i < this.overlays.length; i++) {
		var o = hs.$('hsId'+ this.overlays[i]);
		o.style.zIndex = o.hsId == 'controls' ? 5 : 4;
		if (!o.hideOnMouseOut || this.mouseIsOver) hs.fade(o, 0, o.opacity);
	}
},



createFullExpand : function () {
	if (this.slideshow && this.slideshow.controls) {
		this.slideshow.enable('full-expand');
		return;
	}
	this.fullExpandLabel = hs.createElement(
		'a', {
			href: 'javascript:hs.expanders['+ this.key +'].doFullExpand();',
			title: hs.lang.fullExpandTitle,
			className: 'highslide-full-expand'
		}
	);
	if (!hs.fireEvent(this, 'onCreateFullExpand')) return;

	this.createOverlay({
		overlayId: this.fullExpandLabel,
		position: hs.fullExpandPosition,
		hideOnMouseOut: true,
		opacity: hs.fullExpandOpacity
	});
},

doFullExpand : function () {
	try {
		if (!hs.fireEvent(this, 'onDoFullExpand')) return;
		if (this.fullExpandLabel) hs.discardElement(this.fullExpandLabel);

		this.focus();
		var xSize = this.x.size;
		this.resizeTo(this.x.full, this.y.full);

		var xpos = this.x.pos - (this.x.size - xSize) / 2;
		if (xpos < hs.marginLeft) xpos = hs.marginLeft;

		this.moveTo(xpos, this.y.pos);
		this.doShowHide('hidden');
		hs.setDimmerSize(this);

	} catch (e) {
		window.location.href = this.content.src;
	}
},


afterClose : function () {
	this.a.className = this.a.className.replace('highslide-active-anchor', '');

	this.doShowHide('visible');

	if (this.isHtml && this.preserveContent
			 && this.transitions[1] != 'crossfade') {
		this.sleep();
	} else {
		if (this.outline && this.outlineWhileAnimating) this.outline.destroy();

		hs.discardElement(this.wrapper);
	}
	if (hs.mask) hs.mask.style.display = 'none';
	if (this.dimmingOpacity) hs.undim(this.key);
	hs.fireEvent(this, 'onAfterClose');
	hs.expanders[this.key] = null;
	hs.reOrder();
}

};


// hs.Ajax object prototype
hs.Ajax = function (a, content, pre) {
	this.a = a;
	this.content = content;
	this.pre = pre;
};

hs.Ajax.prototype = {
run : function () {
	if (!this.src) this.src = hs.getSrc(this.a);
	if (this.src.match('#')) {
		var arr = this.src.split('#');
		this.src = arr[0];
		this.id = arr[1];
	}
	if (hs.cachedGets[this.src]) {
		this.cachedGet = hs.cachedGets[this.src];
		if (this.id) this.getElementContent();
		else this.loadHTML();
		return;
	}
	try { this.xmlHttp = new XMLHttpRequest(); }
	catch (e) {
		try { this.xmlHttp = new ActiveXObject("Msxml2.XMLHTTP"); }
		catch (e) {
			try { this.xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); }
			catch (e) { this.onError(); }
		}
	}
	var pThis = this;
	this.xmlHttp.onreadystatechange = function() {
		if(pThis.xmlHttp.readyState == 4) {
			if (pThis.id) pThis.getElementContent();
			else pThis.loadHTML();
		}
	};
	this.xmlHttp.open("GET", this.src, true);
	this.xmlHttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	this.xmlHttp.send(null);
},

getElementContent : function() {
	hs.init();
	var attribs = window.opera || hs.ie6SSL ? { src: 'about:blank' } : null;

	this.iframe = hs.createElement('iframe', attribs,
		{ position: 'absolute', top: '-9999px' }, hs.container);

	this.loadHTML();
},

loadHTML : function() {
	var s = this.cachedGet || this.xmlHttp.responseText;
	if (this.pre) hs.cachedGets[this.src] = s;
	if (!hs.ie || hs.ieVersion() >= 5.5) {
		s = s.replace(/\s/g, ' ').replace(
			new RegExp('<link[^>]*>', 'gi'), '').replace(
			new RegExp('<script[^>]*>.*?</script>', 'gi'), '');

		if (this.iframe) {
			var doc = this.iframe.contentDocument;
			if (!doc && this.iframe.contentWindow) doc = this.iframe.contentWindow.document;
			if (!doc) { // Opera
				var pThis = this;
				setTimeout(function() {	pThis.loadHTML(); }, 25);
				return;
			}
			doc.open();
			doc.write(s);
			doc.close();
			try { s = doc.getElementById(this.id).innerHTML; } catch (e) {
				try { s = this.iframe.document.getElementById(this.id).innerHTML; } catch (e) {} // opera
			}
		} else {
			s = s.replace(new RegExp('^.*?<body[^>]*>(.*?)</body>.*?$', 'i'), '$1');
		}
	}
	hs.getElementByClass(this.content, 'DIV', 'highslide-body').innerHTML = s;
	this.onLoad();
	for (var x in this) this[x] = null;
}
};


hs.Slideshow = function (options) {
	if (hs.dynamicallyUpdateAnchors !== false) hs.updateAnchors();
	for (var x in options) this[x] = options[x];
	if (this.useControls) this.getControls();
};
hs.Slideshow.prototype = {
getControls: function() {
	this.controls = hs.createElement('div', { innerHTML: hs.replaceLang(hs.skin.controls) },
		null, hs.container);

	var buttons = ['play', 'pause', 'previous', 'next', 'move', 'full-expand', 'close'];
	this.btn = {};
	var pThis = this;
	for (var i = 0; i < buttons.length; i++) {
		this.btn[buttons[i]] = hs.getElementByClass(this.controls, 'li', 'highslide-'+ buttons[i]);
		this.enable(buttons[i]);
	}
	this.btn.pause.style.display = 'none';
	//this.disable('full-expand');
},
checkFirstAndLast: function() {
	if (this.repeat || !this.controls) return;
	var cur = this.exp.getAnchorIndex(), re = /disabled$/;
	if (cur == 0)
		this.disable('previous');
	else if (re.test(this.btn.previous.getElementsByTagName('a')[0].className))
		this.enable('previous');
	if (cur + 1 == hs.anchors.groups[this.exp.slideshowGroup || 'none'].length) {
		this.disable('next');
		this.disable('play');
	} else if (re.test(this.btn.next.getElementsByTagName('a')[0].className)) {
		this.enable('next');
		this.enable('play');
	}
},
enable: function(btn) {
	if (!this.btn) return;
	var sls = this, a = this.btn[btn].getElementsByTagName('a')[0], re = /disabled$/;
	a.onclick = function() {
		sls[btn]();
		return false;
	};
	if (re.test(a.className)) a.className = a.className.replace(re, '');
},
disable: function(btn) {
	if (!this.btn) return;
	var a = this.btn[btn].getElementsByTagName('a')[0];
	a.onclick = function() { return false; };
	if (!/disabled$/.test(a.className)) a.className += ' disabled';
},
hitSpace: function() {
	if (this.autoplay) this.pause();
	else this.play();
},
play: function(wait) {
	if (this.btn) {
		this.btn.play.style.display = 'none';
		this.btn.pause.style.display = '';
	}

	this.autoplay = true;
	if (!wait) hs.next(this.exp.key);
},
pause: function() {
	if (this.btn) {
		this.btn.pause.style.display = 'none';
		this.btn.play.style.display = '';
	}

	clearTimeout(this.autoplay);
	this.autoplay = null;
},
previous: function() {
	this.pause();
	hs.previous(this.btn.previous);
},
next: function() {
	this.pause();
	hs.next(this.btn.next);
},
move: function() {},
'full-expand': function() {
	hs.getExpander().doFullExpand();
},
close: function() {
	hs.close(this.btn.close);
}

};
if (document.readyState && hs.ie) {
	(function () {
		try {
			document.documentElement.doScroll('left');
		} catch (e) {
			setTimeout(arguments.callee, 50);
			return;
		}
		hs.domReady();
	})();
}
hs.langDefaults = hs.lang;
// history
var HsExpander = hs.Expander;

// set handlers
hs.addEventListener(window, 'load', function() {
	if (hs.expandCursor) {
		var sel = '.highslide img',
			dec = 'cursor: url('+ hs.graphicsDir + hs.expandCursor +'), pointer !important;';

		var style = hs.createElement('style', { type: 'text/css' }, null,
			document.getElementsByTagName('HEAD')[0]);

		if (!hs.ie) {
			style.appendChild(document.createTextNode(sel + " {" + dec + "}"));
		} else {
			var last = document.styleSheets[document.styleSheets.length - 1];
			if (typeof(last.addRule) == "object") last.addRule(sel, dec);
		}
	}
});
hs.addEventListener(document, 'mousemove', function(e) {
	hs.mouse = { x: e.clientX, y: e.clientY	};
});
hs.addEventListener(document, 'mousedown', hs.mouseClickHandler);
hs.addEventListener(document, 'mouseup', hs.mouseClickHandler);
hs.addEventListener(window, 'load', hs.preloadImages);
hs.addEventListener(window, 'load', hs.preloadAjax);
hs.addEventListener(window, 'load', function() { hs.pageLoaded = true; });
hs.setClickEvents();