//&nbsp;<input type="text" id="newcolorpicker" size="10" maxlength="7" style="width:50px;font-size:10px;vertical-align:middle;" class="helpline" value="" />
(
function colorPickerNamespace()
{
	var cp = null;

	var imgBase = 'images/colorpicker/';

	// so we can convert names to hex codes
var colorNames = {
'aliceblue':'F0F8FF',
'antiquewhite':'FAEBD7',
'aqua':'00FFFF',
'aquamarine':'7FFFD4',
'azure':'F0FFFF',
'beige':'F5F5DC',
'bisque':'FFE4C4',
'black':'000000',
'blanchedalmond':'FFEBCD',
'blue':'0000FF',
'blueviolet':'8A2BE2',
'brown':'A52A2A',
'burlywood':'DEB887',
'cadetblue':'5F9EA0',
'chartreuse':'7FFF00',
'chocolate':'D2691E',
'coral':'FF7F50',
'cornflowerblue':'6495ED',
'cornsilk':'FFF8DC',
'crimson':'DC143C',
'cyan':'00FFFF',
'darkblue':'00008B',
'darkcyan':'008B8B',
'darkgoldenrod':'B8860B',
'darkgray':'A9A9A9',
'darkgreen':'006400',
'darkkhaki':'BDB76B',
'darkmagenta':'8B008B',
'darkolivegreen':'556B2F',
'darkorange':'FF8C00',
'darkorchid':'9932CC',
'darkred':'8B0000',
'darksalmon':'E9967A',
'darkseagreen':'8FBC8F',
'darkslateblue':'483D8B',
'darkslategray':'2F4F4F',
'darkturquoise':'00CED1',
'darkviolet':'9400D3',
'deeppink':'FF1493',
'deepskyblue':'00BFFF',
'dimgray':'696969',
'dodgerblue':'1E90FF',
'firebrick':'B22222',
'floralwhite':'FFFAF0',
'forestgreen':'228B22',
'fuchsia':'FF00FF',
'gainsboro':'DCDCDC',
'ghostwhite':'F8F8FF',
'gold':'FFD700',
'goldenrod':'DAA520',
'gray':'808080',
'green':'008000',
'greenyellow':'ADFF2F',
'honeydew':'F0FFF0',
'hotpink':'FF69B4',
'indianred':'CD5C5C',
'indigo':'4B0082',
'ivory':'FFFFF0',
'khaki':'F0E68C',
'lavender':'E6E6FA',
'lavenderblush':'FFF0F5',
'lawngreen':'7CFC00',
'lemonchiffon':'FFFACD',
'lightblue':'ADD8E6',
'lightcoral':'F08080',
'lightcyan':'E0FFFF',
'lightgoldenrodyellow':'FAFAD2',
'lightgreen':'90EE90',
'lightgrey':'D3D3D3',
'lightpink':'FFB6C1',
'lightsalmon':'FFA07A',
'lightseagreen':'20B2AA',
'lightskyblue':'87CEFA',
'lightslategray':'778899',
'lightsteelblue':'B0C4DE',
'lightyellow':'FFFFE0',
'lime':'00FF00',
'limegreen':'32CD32',
'linen':'FAF0E6',
'magenta':'FF00FF',
'maroon':'800000',
'mediumaquamarine':'66CDAA',
'mediumblue':'0000CD',
'mediumorchid':'BA55D3',
'mediumpurple':'9370DB',
'mediumseagreen':'3CB371',
'mediumslateblue':'7B68EE',
'mediumspringgreen':'00FA9A',
'mediumturquoise':'48D1CC',
'mediumvioletred':'C71585',
'midnightblue':'191970',
'mintcream':'F5FFFA',
'mistyrose':'FFE4E1',
'moccasin':'FFE4B5',
'navajowhite':'FFDEAD',
'navy':'000080',
'oldlace':'FDF5E6',
'olive':'808000',
'olivedrab':'6B8E23',
'orange':'FFA500',
'orangered':'FF4500',
'orchid':'DA70D6',
'palegoldenrod':'EEE8AA',
'palegreen':'98FB98',
'paleturquoise':'AFEEEE',
'palevioletred':'DB7093',
'papayawhip':'FFEFD5',
'peachpuff':'FFDAB9',
'peru':'CD853F',
'pink':'FFC0CB',
'plum':'DDA0DD',
'powderblue':'B0E0E6',
'purple':'800080',
'red':'FF0000',
'rosybrown':'BC8F8F',
'royalblue':'4169E1',
'saddlebrown':'8B4513',
'salmon':'FA8072',
'sandybrown':'F4A460',
'seagreen':'2E8B57',
'seashell':'FFF5EE',
'sienna':'A0522D',
'silver':'C0C0C0',
'skyblue':'87CEEB',
'slateblue':'6A5ACD',
'slategray':'708090',
'snow':'FFFAFA',
'springgreen':'00FF7F',
'steelblue':'4682B4',
'tan':'D2B48C',
'teal':'008080',
'thistle':'D8BFD8',
'tomato':'FD6347',
'turquoise':'40E0D0',
'violet':'EE82EE',
'wheat':'F5DEB3',
'white':'FFFFFF',
'whitesmoke':'F5F5F5',
'yellow':'FFFF00',
'yellowgreen':'9ACD32'
};

	function hex(c)
	{
		c=parseInt(c).toString(16);
		return c.length < 2 ? "0" + c : c;
	}

	function mouseCoordinates(ev)
	{
		ev = ev || window.event;
		if(ev.pageX || ev.pageY)
		{
			return {x:ev.pageX, y:ev.pageY};
		}
		return {x:ev.clientX + document.body.scrollLeft - document.body.clientLeft, y:ev.clientY + document.body.scrollTop  - document.body.clientTop};
	}

	function getPosition(obj)
	{
		var left = 0;
		var top  = 0;

		while (obj.offsetParent)
		{
			left += obj.offsetLeft;
			top  += obj.offsetTop;
			obj   = obj.offsetParent;
		}
		left += obj.offsetLeft;
		top  += obj.offsetTop;

		return {x:left, y:top};
	}

	function $DOM(A)
	{
		var aL = A.length, node, child, ref={}, bRef=false;
		if(aL>=1)
		{
			node = cE(A[0]);
			if(aL>=2)
			{
				for(var arg in A[1])
				{
					if(arg.indexOf('on')==0)
					{
						node[arg] = A[1][arg];
					}
					else if(arg=='ref')
					{
						ref[A[1][arg]] = node;
						ref['DOM']     = node;
						bRef           = true;
					}
					else
					{
						if(arg=='style')
						{
							node.style.cssText   = A[1][arg];
						}
						else if(arg.toLowerCase()=='classname')
						{
							node.style.className = A[1][arg];
						}
						else
						{
							node.setAttribute(arg, A[1][arg]);
						}
					}
				}
			}
			for(var i=2; i<aL; i++)
			{
				if(typeof(A[i])=='string')
				{
					node.appendChild(document.createTextNode(A[i]));
				}
				else
				{
					child = $DOM(A[i]);
					if(child.DOM)
					{
						bRef = true;
						for(n in child)
						{
							if(n=='DOM')
							{
								node.appendChild(child[n]);
							}
							else
							{
								ref[n] = child[n];
							}
						}
						ref['DOM'] = node;
					}
					else
					{
						node.appendChild(child);
					}
				}
			}
			return bRef ? ref : node;
		}
		return null;
	}

	function cE()
	{
		var A = arguments;

		if(!cE.cache[A[0]])
		{
			cE.cache[A[0]] = document.createElement(A[0]);
		}
		return cE.cache[A[0]].cloneNode(false);
	}
	cE.cache    = {};

	function createColorPicker()
	{
		if(cp)
		{
			return;
		}

		cp = $DOM(
			['DIV', {style:'position:absolute;font-family:tahoma,verdana,sans-serif;font-size:10px;', ref:'ColorPicker'},
				['DIV', {style:'background-color:#FFFFFF;line-height:2px;width:423px;_width:424px;height:21px;border:1px solid #000000;', ref:'hColorPicker'},
					['DIV', {style:'width:50px;height:21px;border-right:1px solid #FFFFFF;float:left;', ref:'hColorDiv'}],
					['DIV', {style:'float:left;'},
						['IMG', {class:'pngimg', style:'cursor:crosshair;', src:imgBase+'cp_horizontal_picker.png', width:350, height:21, onmousemove:hColorPickerMouseMove, onmousedown:hColorPickerMouseDown, ref:'hColorImg'}],
						['IMG', {class:'pngimg', style:'cursor:pointer;margin-left:1px;', src:imgBase+'cp_mini_icon.png', width:21, height:21, ref:'hColorIcon', onmousedown:showLgColorPicker}]
					]
				],
				['DIV', {style:'border:1px solid #000000;width:397px;height:305px;position:relative;background-color:#E0DFE3;', ref:'fColorPicker'},
					['DIV', {style:'position:absolute;top:3px;left:8px;'}, 'Select Color:'],
					['IMG', {class:'pngimg', style:'position:absolute;top:20px;left:8px;', src:imgBase+'cp_lg_background.png', width:260, height:260, galleryimg:'no'}],
					['IMG', {class:'pngimg', style:'cursor:crosshair;position:absolute;top:22px;left:10px;', src:imgBase+'cp_lg_overlay.png', width:256, height:256, galleryimg:'no', ref:'fColorImg', onmousedown:cpMouseDown, onmouseup:cpMouseUp, onclick:cpMouseClick}],
					['IMG', {class:'pngimg', style:'cursor:pointer;position:absolute;top:20px;left:280px;z-index:2;', src:imgBase+'cp_color_slider.png', width:23, height:260, ref:'colorSlider', onmousedown:cpSliderMouseDown, onmouseup:cpSliderMouseUp, onclick:cpSliderClick}],
					['IMG', {style:'cursor:pointer;position:absolute;top:18px;left:271px;z-index:1;', src:imgBase+'cp_arrows.gif', width:41, height:9, ref:'Arrows', onmousedown:cpSliderMouseDown, onmouseup:cpSliderMouseUp, onclick:cpSliderClick}],
					['IMG', {class:'pngimg', style:'position:absolute;top:20px;left:315px;', src:imgBase+'cp_cur_color_background.png', width:62, height:70}],
					['IMG', {style:'cursor:pointer;position:absolute;top:62px;left:380px;', src:imgBase+'cp_web_safe.gif', width:14, height:28, alt:'Click to Select Web Safe Color', ref:'websafeImg', onclick:selectWebSafeColor}],
					['DIV', {style:'z-index:2;position:absolute;top:22px;left:317px;width:58px;height:33px;', ref:'curColorDiv'}],
					['DIV', {style:'z-index:2;cursor:pointer;position:absolute;top:55px;left:317px;width:58px;height:33px;', ref:'OrigColorDiv', onclick:resetColor}],
					['DIV', {style:'position:absolute;top:103px;left:315px;'}, 'R:'],
					['DIV', {style:'position:absolute;top:128px;left:315px;'}, 'G:'],
					['DIV', {style:'position:absolute;top:153px;left:315px;'}, 'B:'],
					['INPUT', {style:'position:absolute;top:100px;left:330px;width:47px;', ref:'rInput', onchange:setCPColor}],
					['INPUT', {style:'position:absolute;top:125px;left:330px;width:47px;', ref:'gInput', onchange:setCPColor}],
					['INPUT', {style:'position:absolute;top:150px;left:330px;width:47px;', ref:'bInput', onchange:setCPColor}],
					['BUTTON', {style:'position:absolute;bottom:50px;left:315px;width:77px;', ref:'OK', onclick:hColorPickerMouseDown}, 'OK'],
					['BUTTON', {style:'position:absolute;bottom:25px;left:315px;width:77px;', ref:'Cancel', onclick:hideColorPicker}, 'Cancel'],
					['INPUT', {style:'position:absolute;top:280px;left:4px;', type:'checkbox', ref:'websafeCheckbox'}],
					['DIV', {style:'position:absolute;top:284px;left:25px;'}, 'Only Web Colors'],
					['IMG', {class:'pngimg', style:'cursor:pointer;position:absolute;bottom:0px;right:0px;border:1px solid #000000;border-width:1px 0px 0px 1px;', src:imgBase+'cp_mini_icon.png', height:21, width:21, ref:'fColorIcon', onmousedown:showSmColorPicker}]
				]
			]);

		document.onmousemove         = cpMouseMove;
		cp.baseColor                 = {r:0, g:0, b:0};

		document.body.appendChild(cp.ColorPicker);
		cp.ColorPicker.style.display = 'none';
	}

	function getHorizColor(i, width, height)
	{
		var sWidth = (width)/7;         // "section" width
		var C=i%width;                  // column
		var R=Math.floor(i/(sWidth*7)); // row
		var c=i%sWidth;                 // column in current group
		var r, g, b, h;

		var l=(255/sWidth)*c;           // color percentage

		if(C>=sWidth*6)
		{
			r=g=b=255-l;
		}
		else
		{
			h=255-l;

			r=C<sWidth?255:C<sWidth*2?h:C<sWidth*4?0:C<sWidth*5?l:255;
			g=C<sWidth?l:C<sWidth*3?255:C<sWidth*4?h:0;
			b=C<sWidth*2?0:C<sWidth*3?l:C<sWidth*5?255:h;

			if(R<(height/2))
			{
				var base = 255-(255*2/height)*R;

				r=base+(r*R*2/height);
				g=base+(g*R*2/height);
				b=base+(b*R*2/height);
			}
			else if(R>(height/2))
			{
				var base = (height-R)/(height/2);

				r=r*base;
				g=g*base;
				b=b*base;
			}
		}

		return hex(r)+hex(g)+hex(b);
	}

	function getVertColor(i, sZ)
	{
		var n=sZ/6, j=sZ/n, C=i, c=C%n;

		r=C<n?255:C<n*2?255-c*j:C<n*4?0:C<n*5?c*j:255;
		g=C<n*2?0:C<n*3?c*j:C<n*5?255:255-c*j;
		b=C<n?c*j:C<n*3?255:C<n*4?255-c*j:0;

		return {r:r, g:g, b:b};
	}

	function getGradientColor(x, y, Base)
	{
		x = x<0?0:x>255?255:x;
		y = y<0?0:y>255?255:y;

		var r = Math.round((1-(1-(Base.r/255))*(x/255))*(255-y));
		var g = Math.round((1-(1-(Base.g/255))*(x/255))*(255-y));
		var b = Math.round((1-(1-(Base.b/255))*(x/255))*(255-y));

		return {r:r, g:g, b:b};
	}

	function getWebSafeColor(color)
	{
		var rMod = color.r % 17;
		var gMod = color.g % 17;
		var bMod = color.b % 17;

		if((rMod==0) && (gMod==0) && (bMod==0))
		{
			return false;
		}

		var wsColor={};

		wsColor.r=(rMod<=25?Math.floor(color.r/17)*17:Math.ceil(color.r/17)*17);
		wsColor.g=(gMod<=25?Math.floor(color.g/17)*17:Math.ceil(color.g/17)*17);
		wsColor.b=(bMod<=25?Math.floor(color.b/17)*17:Math.ceil(color.b/17)*17);

		return wsColor;
	}

	function getWebSafeColor_old(color)
	{
		var rMod = color.r % 51;
		var gMod = color.g % 51;
		var bMod = color.b % 51;

		if((rMod==0) && (gMod==0) && (bMod==0)) return false;

		var wsColor={};

		wsColor.r=(rMod<=25?Math.floor(color.r/51)*51:Math.ceil(color.r/51)*51);
		wsColor.g=(gMod<=25?Math.floor(color.g/51)*51:Math.ceil(color.g/51)*51);
		wsColor.b=(bMod<=25?Math.floor(color.b/51)*51:Math.ceil(color.b/51)*51);

		return wsColor;
	}

	function hColorPickerMouseMove(ev)
	{
		ev            = ev || window.event;
		var hCPImg    = ev.target || ev.srcElement;

		var mousePos  = mouseCoordinates(ev);
		cp.colorPos   = getPosition(hCPImg);

		var x         = mousePos.x-cp.colorPos.x;
		var y         = mousePos.y-cp.colorPos.y;
		var width     = parseInt(hCPImg.offsetWidth);
		var height    = parseInt(hCPImg.offsetHeight);

		var color     = getHorizColor(y*width+x, width, height);

		cp.hColorDiv.style.backgroundColor = cp.cpColor = '#'+color;
	}

	function hColorPickerMouseDown()
	{
		if(cp.cpColor.r || (cp.cpColor.r===0))
		{
			cp.cpColor = '#'+hex(cp.cpColor.r)+hex(cp.cpColor.g)+hex(cp.cpColor.b);
		}
		cp.cpInput.value = cp.cpColor;
		hideColorPicker();
	}

	function attachColorPicker(input, noLg)
	{
		createColorPicker();
		if(noLg) input.setAttribute('noLg', '1');
		input.onfocus = showColorPicker;
		input.onblur  = tryHideColorPicker;
		input.onclick = showColorPicker;
	}

	function showSmColorPicker(ev)
	{
		cp.clicked = true;
		showColorPicker(ev, 's');
	}

	function showLgColorPicker(ev)
	{
		cp.clicked = true;
		showColorPicker(ev, 'l');
	}

	function showColorPicker(ev, size)
	{
		ev         = ev        || window.event;
		var input  = ev.target || ev.srcElement;
		size       = size      || (cp.fColorPicker.style.display == 'block' ? 'l' : 's');

		cp.ColorPicker.style.display  = 'block';
		if(input.nodeName == 'INPUT')
		{
			cp.cpInput = input;
		}

		cp.hColorIcon.style.display = cp.cpInput.getAttribute('noLg') == '1' ? 'none' : 'inline';

		var inpPos = getPosition(cp.cpInput);

		cp.ColorPicker.style.left = inpPos.x;
		cp.ColorPicker.style.top  = inpPos.y + parseInt(cp.cpInput.offsetHeight);

		cp.hColorPicker.style.display = cp.fColorPicker.style.display = 'none';
		(size == 's' ? cp.hColorPicker : cp.fColorPicker)['style'].display = 'block';

		if(size != 's')
		{
			cp.baseColor = parseColor(cp.cpInput.value);
			setCPColor(cp.fColorImg.style.backgroundColor = cp.origColor = cp.OrigColorDiv.style.backgroundColor = '#'+hex(cp.baseColor.r)+hex(cp.baseColor.g)+hex(cp.baseColor.b));
			cp.sliderPos = getPosition(cp.colorSlider);
		}
	}

	function tryHideColorPicker()
	{
		if(!cp.clicked)
		{
			hideColorPicker();
		}
	}

	function hideColorPicker()
	{
		cp.ColorPicker.style.display  = 'none';
	}

	function cpMouseDown(ev)
	{
		cp.cpPos       = getPosition(cp.fColorImg);
		cp.cpMouseDown = true;

		return false;
	}

	function cpMouseUp(ev)
	{
		cp.cpMouseDown = false;
	}

	function cpSliderMouseDown(ev)
	{
		cp.csPos           = getPosition(cp.colorSlider);
		cp.SliderMouseDown = true;

		return false;
	}

	function cpSliderMouseUp(ev)
	{
		cp.SliderMouseDown = false;
	}

	function cpSliderClick(ev)
	{
		ev           = ev || window.event;
		var mousePos = mouseCoordinates(ev);

		var y        = mousePos.y-cp.sliderPos.y-4;

		cpSliderSetColor(y);
	}

	function cpMouseClick(ev)
	{
		ev           = ev || window.event;
		var mousePos = mouseCoordinates(ev);

		var x        = mousePos.x-cp.cpPos.x-1;
		var y        = mousePos.y-cp.cpPos.y-1;

		setCPColor(getGradientColor(x, y, cp.baseColor));
	}

	function cpMouseMove(ev)
	{
		 // fired when mouse moves over the color picker
		if(cp.cpMouseDown)
		{
			cpMouseClick(ev);
		}

		// fired when mouse moves over the color slider
		if(cp.SliderMouseDown)
		{
			cpSliderClick(ev);
		}

		return false;
	}

	function cpSliderSetColor(y)
	{
		y = y<0 ? 0 : y>255 ? 255 : y;

		cp.Arrows.style.top      = (y+18)+'px';
		var color = cp.baseColor = getVertColor(y, 256);

		cp.fColorImg.style.backgroundColor = '#'+hex(color.r)+hex(color.g)+hex(color.b);
	}

	function selectWebSafeColor()
	{
		setCPColor(getWebSafeColor(cp.cpColor));
	}

	function resetColor()
	{
		setCPColor(cp.origColor);
	}

	function setCPColor(color)
	{
		if(color.srcElement || color.target)
		{
			color=null;
		}
		if(color && (!color.r && (color.r!=0)))
		{
			color = parseColor(color);
		}
		if(!color)
		{
			color = {
				r:parseInt(cp.rInput.value),
				g:parseInt(cp.gInput.value),
				b:parseInt(cp.bInput.value)
			}
		}
		var wsColor = getWebSafeColor(color)

		if(wsColor && !cp.websafeCheckbox.checked)
		{
			cp.websafeImg.style.display         = 'block';
			cp.websafeImg.style.backgroundColor = '#'+hex(wsColor.r)+hex(wsColor.g)+hex(wsColor.b);
		}
		else
		{
			if(wsColor && cp.websafeCheckbox.checked)
			{
				color = wsColor;
			}
			cp.websafeImg.style.display         = 'none';
		}

		cp.rInput.value = color.r;
		cp.gInput.value = color.g;
		cp.bInput.value = color.b;

		cp.cpColor      = color;
		cp.curColorDiv.style.backgroundColor = '#'+hex(color.r)+hex(color.g)+hex(color.b);
	}

	function parseColor(text)
	{
		if(colorNames[text.toLowerCase()])
		{
			text = colorNames[text.toLowerCase()];
		}
		if(/^\#?[0-9A-F]{6}$/i.test(text))
		{
			return {
				r: eval('0x'+text.substr(text.length==6?0:1, 2)),
				g: eval('0x'+text.substr(text.length==6?2:3, 2)),
				b: eval('0x'+text.substr(text.length==6?4:5, 2))
			}
		}
		return {r:255, g:0, b:0};
	}

	function documentMouseDown(ev)
	{
		ev         = ev            || window.event;
		var target = ev.srcElement || ev.target;

		while(target)
		{
			if(target==cp.ColorPicker)
			{
				return;
			}
			target = target.parentNode;
		}
		cp.ColorPicker.style.display = 'none';
	}

	function documentMouseUp(ev)
	{
		cpMouseUp(ev);
		cpSliderMouseUp(ev);
	}

	document.onmousedown     = documentMouseDown;
	document.onmouseup       = documentMouseUp;
	window.attachColorPicker = attachColorPicker;
}
)
();

var oldonload = window.onload;
if(typeof(oldonload) == 'function')
{
	window.onload = function(){oldonload();init_cp()};
}
else
{
	window.onload = function(){init_cp()};
}

function init_cp()
{
	var inpcp = document.getElementById('newcolorpicker');
	if(inpcp)
	{
		attachColorPicker(inpcp);
	}
}
