"use strict";

/**
 **
 ** Revolver :: JavaScript library with modules architecture
 **
 ** ......................................... v.1.0.8( ES 7 )
 **
 ** Revolver JS is a fast, simple and powerfull solution for 
 ** front-end developers without any third party components.
 ** 
 ** Contnains parts of modules and helpers to use HTML DOM fast,
 ** native and have some special futures intended for Objective 
 ** humanity coding style.	
 **
 ** License: MIT
 ** developer: CyberX
 **
 */

/* RevolveR core modules */
const RR = {
 
	that: self.document,

	// set first index for locations api work correct
	startIndex: document.title,

	// browser detection helper
	browser: (mode) => {
		
		RR.appVer  = '1.0.8';
		RR.isM     = /(Android|BackBerry|phone|iPad|iPod|IEMobile|Nokia|Mobile)/.test(navigator.userAgent);
		RR.isTouch = !!('ontouchstart' in self);
 
		// screen width and size in fixed
		// available in RR.sizes[0,1]
		RR.sizes  = [self.screen.width, self.screen.height];

		// set avalible styles list from first important tag
		RR.styles = RR.get("body")[0].style;

		// fix body as canvas perimetr first to enable animatins
		RR.styles.position  = 'relative';

		// set screen sizes schema.css
		RR.dom('link',"new|after|head", {
			attr: {
				rel: "stylesheet",
				id: "schema"
			}
		});
 
		// autoupdate real browser window size on every resize
		// refreshed value available in RR.currentSizes[0,1];
		setInterval(function() {
			RR.currentSizes = [RR.that.documentElement.clientWidth, RR.that.documentElement.clientHeight];

			RR.that.body.addEventListener("touchmove", function(e){
				RR.curOffset = 	[self.scroollX, self.scrollY];	
				RR.sizes  = 	[self.screen.width, self.screen.height];
			}, false);

			RR.that.body.onmousemove = function(e) { 
				RR.curxy = 		[e.clientX, e.clientY, e];
				RR.curOffset =	[self.scrollX, self.scrollY];

			};
			
			RR.grid(true,true);
		
		}, 500);
	},

	// start evaluting code from external script connector with their context
	bootstrap: () => {
		
		// connect base style schema bootstrap + resets
		RR.dom('link',"new|before|head", {
			attr: {
				href: RR.bootCss,
				rel: "stylesheet",
				id: "bootstrap"
			}
		});

		// set history title correct
		self.onpopstate = function(e) {
			document.title = (self.history.state) ? self.history.state.title : RR.startIndex;
		}
	},

	screenPosition(current, maximum, mode) {

		function setIndicator() {

			let i = RR.get('#screen-position');

			if( RR.isU(i) ) {
				// inject reading position indicator
				RR.dom('progress', 'new|before|body', {
					attr: {
						id: 'screen-position',
						style: 'position: fixed; bottom: 0; height: 5px; width: 100%; z-index: 10000;',
					}
				});
			}

		}

		function setPosition(current, maximum, m) {

			setIndicator();

			var current = m ? current : self.scrollY;
			var maximum = m ? maximum : self.document.body.scrollHeight - self.innerHeight;
			var style   = m ? 'yellowProgress' : 'greenProgress';

			RR.attr('#screen-position', {
				'max': maximum,
				'value': current,
				'class': style
			});
		}

		if(!mode) {
			RR.event(document, 'scroll', function() {
				setPosition();
			});

			RR.event(self, 'resize', function() {
				setPosition();
			});

			setTimeout(function() {
				setPosition();
			}, 100);
		} 
		else {
			setPosition(current, maximum, true);
		}

	},

	event: function(e, evt, c) {
		var e = (e.length) ? this.htmlObj(e) : [e];

		if(e) {
			for (let i of e) {
				if(this.isC(c)) {
					switch(evt) {
						case 'click':
						case 'dblclick': 
						case 'mouseover':
						case 'mouseout':
						case 'mousemove':
						case 'mouseenter':
						case 'mouseleave':
						case 'mouseup':
						case 'mousedown':
						case 'select':
						case 'contextmenu':
						case 'scroll':
						case 'resize':
						case 'submit':
							var m = evt;
							break;
						default: return;
							break;
					}
					i.addEventListener(m, c, false);
				}
			}
		}
	},

	// simulate event on element
	fireEvent: function(e, type) {

		var e = (e.length) ? this.htmlObj(e) : [e];

		if( e ) {
			for( let i of e ) {

				if (i.fireEvent) {
					i.fireEvent('on' + type);
				} 
				else {
					let evObj = document.createEvent('Events');
					evObj.initEvent(type, true, false);
					i.dispatchEvent(evObj);
				}

			}
		}		
	},

	// fetch engine
	fetch: function(...args) {
		
		var u = args[0] || null;
		var m = args[1] || 'GET';
		var d = args[2] || 'text';

		var b = args[3] || null; 
		var f = args[4] || null;

		// define what index of argument shold be used for callback
		let callback = f ? f : b;
		let headers = new Headers();

		headers.append('X-Revolver', this.appVer);

		let reqInit = { 
				method: m,
				headers: headers,
				cache: 'default',
				credentials: 'same-origin',
				mode: 'same-origin',
				redirect: 'follow',
				referrer: 'client'
		};

		if( m === 'POST' || m === 'PUT' && args[4] ) {
			reqInit.body = b;
		}


		const request = new Request(u, reqInit);
	

		// real data loading
		fetch(request).then(function(r) {

			RR.screenPosition(0.4, 1, true);
			
			if(r.ok) {

				let result;

				switch(d) {
					default:
					case 'text': result = r.text();
						break;
					case 'json': result = r.json();
						break;
				}

				RR.screenPosition(0.7, 1, true);

				return result;
			}

		}).then(function(k) {

			/* deprecated with new RR.grepByTagHTML()
			if( d === 'html' ) {
				// revert from [string] to [object html]
				const pattern = /<body[^>]*>((.|[\n\r])*)<\/body>/im;
				k = RR.convertSTRToHTML(pattern.exec(k)[0]);
			}
			*/

			RR.screenPosition(1, 1, true);

			if( k ) callback.call( k );

		});
	},

	// Dinamic form submission module
	fetchSubmit: function(f, t, c) {

		var t = t || 'text';

		RR.event(f, 'submit', function(e) {
			
			e.preventDefault();

			let action = document.location.href + RR.attr(f, 'action');
			let method = RR.attr('form', 'method')[0].toUpperCase();

			let formInputs = RR.dom("form input[type='text'], form input[type='hidden'], form input[type='email'], form input[type='number'], form input[type='password'], form input[type='date'], form input[type='time'], form input[type='tel'], form input[type='url']"); 
			let formRadiosCheckboxes = RR.dom("form input[type='radio'], form input[type='checkbox']");
			let formTextareas = RR.dom("form textarea");
			let formSelect = RR.dom('form select');

			let data = new FormData(); 
		
			// text and other formats
			for(let j in formInputs) { 
				data.append( formInputs[j].name, formInputs[j].value ); 
			}

			// multi string long text
			for(let u in formTextareas) { 
				data.append( formInputs[u].name, formInputs[u].value ); 
			}

			// special flag's elements
			for(let l in formRadiosCheckboxes) { 
				if( formRadiosCheckboxes[l].checked ) {
					data.append( formRadiosCheckboxes[l].name, formRadiosCheckboxes[l].value );
				}
			} 
			// selects elements
			for(let s in formSelect) {
				let options = formSelect[s].querySelectorAll('*');
				let name = formSelect[s].name;

				for(let i in options) {

					let option = options[i];
					let select = option.selected;

					if( select ) {
						data.append( name, option.value ); 
					}
				}
			}

			// perform parameterized fetch request
			$.fetch(action, method, t, data, function() {
				c.call(this);
			});

		});

	},

	// location module. 
	// c - is a callback
	location: function(title, url, c) {
		var c = c || null;

		document.title = title;
		self.history.pushState({"title": title, 'url': url}, "", url);
		this.callback(c,[title,url]);
	},

	// this helper connects mobile schema
	grid: function(i,r,c) {
		var c = c || null;

		if(this.isM && this.isTouch) {

			this.schema = null;

			// apply mobile schema
			if(i) {
				if(RR.sizes[0] >= 320 && RR.sizes[0] <= 1920) {
					this.schema = "mobile";
					this.qsizes  = 'only screen and (max-width: 1920px) and (min-width: 320px)';
				}
			}

			this.externalCSS(this.schema, this.isM ? this.qsizes : false, c);

		}
	},

	// create a Gray Box modal windows
	modal: function(t,d,s,q,c) {

		// define title
		var t = t || 'Gray Box';
		var d = d || 'Gray Box Demo Contents';
		
		// new modal with overlay or without
		var q = (q) ? '#overlay' : 'body';

		// bind default sizes s[0:width,1:height]
		if(d && d.length > 10 && !this.lockModalBox) {

			this.lockModalBox = true;
			this.StopMoving = null;

			// center positions 
			var gBoxRealCenterTop  = ( this.currentSizes[1] - s[1] ) / 3;
			var gBoxRealCenterLeft = ( this.currentSizes[0] - s[0] ) / 2;

			// create elemnt with contents
			this.dom('div','new|in|body', {
				html: "<div style=\"width:"+ s[0] +"px; height:"+ s[1] +"px; opacity:0.1;\" id=\"mBox\"><header><i id=\"mBoxTitle\">"+ t +"</i><b id=\"mBoxClose\">X</b></header><div id=\"mBoxContent\">"+ d +"</div></div>",
				attr: {
					id: 'overlay',
				}
			});

			// animate opacity
			this.dom('#mBox', 'animate', ['opacity:1:1500']);

			var setPosition = function(e,xy) {
				RR.styleApply([e], ['left:'+ Math.round(xy[0]) +'px', 'top:'+ Math.round(xy[1]) +'px']);
			};

			var grayBox = this.htmlObj('#mBox')[0];
			
			// center the Gray Box
			setPosition(grayBox, [gBoxRealCenterLeft,gBoxRealCenterTop]);

			// use dragable wrapper
			this.event('#mBox header', 'mousedown', function() {
				let mFixRealPosL = RR.curxy[0] - RR.stripNum(RR.styleGet(grayBox,'left'));
				let mFixRealPosT = RR.curxy[1] - RR.stripNum(RR.styleGet(grayBox,'top'));
				RR.StopMoving = null;
				
				let x = this;

				RR.event('#overlay', 'mousemove', function() {
					if(!RR.StopMoving) {
						setPosition(x.parentElement,[ RR.curxy[0] - mFixRealPosL , RR.curxy[1] - mFixRealPosT ]);
					}
				});

				RR.event('#overlay', 'mouseup', function() {
					RR.StopMoving = true;
				});

			});

			// close button and cleans attached events
			RR.event('#mBoxClose', 'click', function() {
				
				RR.dom('#mBox','animate',['opacity:.1:800','height:0px:800']);
				
				setTimeout(function(){
					RR.dom('#overlay','del');
				}, 900);

				RR.lockModalBox = false;

			});
		}
	},

	// hint attributes
	hint: function(e,a) {
		var e = this.htmlObj(e);

		for(let i of e) {
			RR.event(i, 'mouseover', function(evt){ 
				evt.preventDefault(); 
				RR.dom('div',"new|before|body", { 
					attr: { id: "hint", style: "top:0; left:0; visibility:visible;" },
					html: RR.attr(i, a), 
				});
			});

			RR.event(i, 'mouseout', function(evt){ 
				RR.dom('#hint', 'del');
			});

			RR.event(i, 'mousemove', function(evt){ 
				RR.dom('#hint', 'style', [
					'left:'+ (evt.clientX - hint.clientWidth - 5) +'px',
					'top:'+ (evt.clientY - hint.clientHeight - 5) +'px'
				]);
			});
		}
	},

	// show or hide element
	toggle: function(e,c) {

		e = this.htmlObj(e);

		for(let i of e) {
			var el = this.treeHacks(i);

			if( el.style.overflow === 'hidden' ) {
				this.styleApply([el],['overflow:visible','width','height','display','line-height']);
			} 
			else {
				this.styleApply([el],['display:inline-block','overflow:hidden','width:0','height:0','line-height:0']);
			};
			this.callback(c,el)
		}
	},

	// target is an element(single selector)
	scroll: function(target, c) {
		
		var target = target || "body";
		var t = this.htmlObj(target)[0] || this.htmlObj("body")[0];
		var y = t.offsetTop - t.offsetHeight - 50;
		var c = c || null;
			
		// set minimal opacity value for animation
		RR.dom(target,'style', ['opacity:0.1']);

		// animate moving engine 
		// e - element, p - propertie, 
		// v[1] - from, v[2] - to 
		// t - time, r - easing, c - target element for restore opacity)
		this.animateMove(t, 'scroll', [this.curOffset[1], y], 1000, 'linear', target);
	},

	// toggle class ( [el] [class name])	
	toggleClass: function(e, cls) {
		var e = this.htmlObj(e);
		for(let i of e) {
			i.classList.toggle(cls);
		}
	},

	// this helper removes class
	removeClass: function(e, cls) {
		var e = this.htmlObj(e);
		for(let i of e) {
			i.classList.remove(cls);
		}
	},

	// this helper adds class
	addClass: function(e,cls) {
		var e = this.htmlObj(e);
		for(let i of e) {
			i.classList.add(cls);
		}
	},

	// this helper try to test for has class value
	hasClass: function(e,c) {
		var e = this.htmlObj(e);
		var c = c.split(' ');
		for(let i of c) {
			if(!this.treeHacks(e).classList.contains(i)) {
				return null;
			}
		}
		return true;
	},

	// apply styles to element  
	styleApply: function(e,s) {
		var e = this.htmlObj(e);

		for(let t of e) {
			for(let i of s) {

				var sets = this.arguments(i,":");
				var rule = this.normalizeStyleName(sets[0]);

				for(let g in t.style) {
					if( g === rule ) {
						if( !this.isU(sets[1]) ) {
							t.style[g] = sets[1];
						} 
						else {
							t.style[g] = 'inherit';
						}
					}
				}
			}
		}
	},

	// get some of CSS properties value
	styleGet: function(e,p) {
		var p = this.normalizeStyleName(p);
		var s = e.style[p] ? e.style[p] : getComputedStyle(e,null)[p];
		return s;
	},

	// show \ hide elements smooth
	show: function(e,t) {
		var e = this.htmlObj(e);
		for(let s in e) {
			var	sh = e[s].savedHeight ? e[s].savedHeight : null; // return saved height
			var sd = e[s].savedDisplay ? e[s].savedDisplay : 'inherit';

			this.styleApply([e[s]],['display:'+ sd]);
			
			if( sh ) {
				this.dom([e[s]], "animate", ['height:'+ sh +':'+ t]);
			}
			else {
				this.styleApply([e[s]],['height:auto']);
			}
		}
	},

	hide: function(e,t) {
		var e = this.htmlObj(e);
		for(let s of e) {
			s.savedHeight  = this.styleGet(s, 'height'); // save states for show module
			s.savedDisplay = this.styleGet(s, 'display');
		}

		this.dom(e, "animate", ['height:0px:'+ t], function() {
			RR.styleApply([this],['display:none']);
		});
	},

	// animations for base css properties
	animate: function(e,o,c) {

		var e = this.htmlObj(e);
		var c = c || null;

		for(let x of e) {
			for(let i of o) {

				var p  = this.arguments(i,':');
				var z = this.shortToFull(x, p);

				if(z[0][0] === 'transform') {
					p[1] = z[0][1] + "";
				}

				for(let l in z) {

					var prop = z[l][0];
					var dest = z[l][1];
					var unit = z[l][2];

					if( prop === 'width'  || 
						prop === 'height' ||
						prop === 'top'	  ||
						prop === 'left'   ||
						prop === 'bottom' ||
						prop === 'right'
					) {
						if( prop === 'left' || 
							prop === 'right'|| 
							prop === 'top'  ||
							prop === 'bottom'
						) {
							var pos = x.style.position;
							if(pos !== 'absolute' || position !== 'relative') {
								x.style.position = 'relative';
							}
						}

						var from = this.numberCSS(this.styleGet(x,p[0]), p[0])[0];

						// convert % to px
						if(unit === '%' && from !== 0) {
							dest *= from / 100;
							unit = 'px';
						}
					}
					else if( prop === 'backgroundColor' || 
							 prop === 'borderBottomColor'||
							 prop === 'borderLeftColor' ||
							 prop === 'borderRightColor' ||
							 prop === 'borderTopColor' ||
							 prop === 'color' ||
							 prop === 'outlineColor'
						) {

							this.fade(x, p[0], p[1], p[2], p[3]);
					} 
					else if ( prop === 'transform' ) {
						
						// get matrix and set a default value if not present
						var matrixStart = (self.getComputedStyle(x, null)[prop] === 'none') ? 'matrix(1, 0, 0, 1, 0, 0)' : self.getComputedStyle(x, null)[prop];
						
						var M2D = RR.arguments(this.getValFromPropsBrackets('matrix',matrixStart)[1], ',');

						// extract CSS transitions matrix from 2D to 3D
						if( M2D.length <= 6) {
							var matrix3D = 'matrix3d('+ M2D[0] +', '+ M2D[1] +', 0, 0, '+ M2D[2] +', '+ M2D[3] +', 0, 0, 0, 0, 1, 0,'+ M2D[4] +','+ M2D[5] +', 0, 1)'
						} 
						else {
							var matrix3D = self.getComputedStyle(x, null)['transform'];
						}
												
						// extract matrix values
						var M3D = RR.arguments(this.getValFromPropsBrackets('matrix3d',matrix3D)[1], ',');

						/*
						matrix3d pattern
						   1, 0, 0, 0,    1 scalex 6 scaley 11 scalez
						   0, 1, 0, 0,    5 skewX  2 skewY
						   0, 0, 1, 0,    10 rotateX 9 rotateY 1 rotateZ  
						   tx, ty, tz,    13 translateX 14 translateY 15 translateZ
						   				  12 - perspective
						*/
						
						// get current scale from matrix
						var scale3d = [M3D[0], M3D[5], M3D[10]]

						// get current rotate from matrix in degrees
						var pi = Math.PI;
						var sinB = parseFloat(M3D[8]);
						var b = Math.round(Math.asin(sinB) * 180 / pi);
						var cosB = Math.cos(b * pi / 180);
						var a = Math.round(Math.asin(-M3D[9] / cosB) * 180 / pi);
						var c = Math.round(Math.acos(M3D[0] / cosB) * 180 / pi);

						var angle3d = [a, b, isNaN(c) ? 0 : c ];

						// get translate
						var translate3d = [M3D[12] - 0, M3D[13] - 0, M3D[14] - 0];

						// get skew
						var skew3d = [ Math.floor(M3D[4] / 0.0174532925),  Math.floor(M3D[1] / 0.0174532925)];

						// get perspective TODO: calculate it
						var perspective3d = -1 / (M3D[11] - 0); 

						// compare transforms to animate
						var transforms = [];

						function compareTransformProp(prop, string){
							var props = [];
							var exe = RR.getValFromPropsBrackets(prop, string);
								
							if(exe) {

								let start;
								
								function axisIndex(a,p) {

									let i; 

									switch(a.replace(p,'')) {
										case 'X': i = 0;
											break;
										case 'Y': i = 1;
											break;
										case 'Z': i = 2;
											break;
									}

									return i;
								}

								function propAxis(p) {
									let tests = ['translate', 'skew', 'rotate', 'scale', 'perspective'];

									for(let i in tests) {
										if(p.includes(tests[i])) {

											let index = axisIndex(p, tests[i]);
											let s;
									
											switch( tests[i] ) {
												case 'translate':   s = translate3d[index];
													break;
												case 'skew': 	    s = skew3d[index];
													break;
												case 'rotate': 	    s = angle3d[index];
													break;
												case 'scale': 	    s = scale3d[index];
													break;
												case 'perspective': s = perspective3d;
													break;
											}
											return s;
										}
									}

								}

								props.push(prop, [propAxis(prop), RR.numberCSS(exe[1])[0], RR.numberCSS(exe[1])[1]]);
							}

							if(props[0]) {
								return props;
							} else {
								return null;
							}
							
						}

						function packTransform(p, d) {

							let axis = ['X', 'Y', 'Z'];
							let comp = [];

							for(let i in axis) {

								let prop;
								
								switch(p) {
									case 'scale':
									case 'translate': 
									case 'rotate': prop = p + axis[i];
										break;
									case 'skew': if( i <= 1 ) prop = p + axis[i];
										break;
									case 'perspective': if( i <= 0 ) prop = p;
										break;
								}

								if(prop) comp.push(prop);
							}

							for(let o in comp) {
								//fgd.push(compareTransformProp(comp[o]), d);
								transforms.push(compareTransformProp(comp[o], d));
							}
						}

						if( p[1].includes('rotate') ) packTransform('rotate', p[1]);
						if( p[1].includes('skew') ) packTransform('skew', p[1]);
						if( p[1].includes('translate') ) packTransform('translate', p[1]);
						if( p[1].includes('scale') ) packTransform('scale', p[1]);
						if( p[1].includes('perspective') ) packTransform('perspective', p[1]);
					
						this.animateMatrix(x,transforms,p[2],p[3]);
					}

					// other CSS values
					else {
						var from = this.numberCSS(this.styleGet(x,p[0]))[0];
						if(!from && from !== 0) {
							from = this.numberCSS(this.styleGet(x,z[l][3]))[0];
						}
					}

					// perform animation for element with propertie
					this.animateMove(x,prop,[from,dest,unit],p[2],p[3],c);
				}
			}
		}
	},

	// Micro module rotate
	// [element] :: ( #parents fixed container -> .slide selector )
	rotate: function(e,c,t) {
		var e = this.htmlObj(e);
		var t = t || 3000;
		let ind = 0;

		self.rotate = setInterval(() => { // place it in window to get timerId access on AJAX page loading
			if(e) {
				e[ind].style.zIndex = 0; ind++;
				ind = ind == e.length ? 0 : ind;
				e[ind].style.zIndex = 1;
			}
		}, t); 
	},

	// Micro tabs module
	// p - control selectors   ( like  [ul > li] )
	// e - switchable contents ( like [div] )
	tabs: function(p, e, c) {
		var pc = p.split(' ')[0]; // get parents selector to prevent other tabs to be switched
		var e = this.htmlObj(e);
		var p = this.htmlObj(p);

		this.event(p, 'click', function(evt) {
			
			evt.preventDefault();

			RR.attr(pc +' .tabactive,'+ pc +' .activetab', {'class': null});
			
			for (let i of e) {
				if (i.hasAttribute("data-content")) {
					if (RR.attr(i, 'data-content')[0] === RR.attr(this, 'data-link')[0]) {
						RR.attr(this, {'class': "activetab"});
						RR.attr(i, {'class': "tabactive"});
					}
				}
			}
		});
	},

	animateMove: function (e, p, v, t, r, c) {

		// v arg - [from,dest,unit);
		let s = performance.now();
		let m = (v[0] - v[1]) / t;

		requestAnimationFrame(
			function frame(d) {

				// g - time gone; s - start time; m - speed;  z - delta
				let g = d - s;
				let z = v[0] - (m * g);

				// time escape preventing
				if (g > t) { 
					g = t;
				}

				// apply FX's
				let f = RR.effects(r, g / t);

				// test for units are defined and set css value correct
				if(p === "scroll") {
					self.scrollTo(0, z * f);
				} 
				else {
					e.style[p] = (v[2]) ? Math.floor(z * f) + v[2] : z * f;
				}

				// animation time is over? if not next frame
				if (g < t) {
					requestAnimationFrame(frame);
				} 
				else {
					if(p === "scroll") {
						RR.dom(c,'animate',['opacity:1:700']); // restore opacity when animation success
					} 
					else {

						// hard fix css to prevent escaping ranges
						e.style[p] = v[1] + v[2];
						RR.callback(e, c);
					}
				}
			}
		);
	},

	// get values propertie from brackets
	getValFromPropsBrackets: function(p,v) {
		let pattern = new RegExp(p+"\\(([^)]+)\\)");
		return pattern.exec(v);
	},

	// replaces values in css matrix
	setMatrixCss: function(e, p, v) {
		let current = e.style['transform'];

		if(!current.includes(p)) {
			current += ' '+ p +'(0) ';
		}

		e.style['transform'] = current.replace(this.getValFromPropsBrackets(p,current)[0],'').trim() +' '+ p +'('+ v +')'; 
	},

	// animate transform matrix properties
	animateMatrix: function(e, tr, t, fx) {
		for(let i of tr) {

			if(i) {

				// prepare base variables for animation
				var s = performance.now(); // time now
				var m = (i[1][0] - i[1][1]) / t; // speed stepping
				var x = i[1][0]; // destination
				var h = i[1][1]; // duration
				var y = i[1][2]; // units
				var p = i[0];    // propertie

				(function animloop(s,p,m,x,y,h) {
					requestAnimationFrame(
						function frame(d) {

							// g - time gone; s - start time; m - speed;  z - delta
							let g = d - s;
							let z = x - (m * g);

							// time escape preventing
							if (g > t) { 
								g = t;
							}

							// apply FX's
							let f = RR.effects(fx, g / t);

							// test for units are defined and set css value correct
							RR.setMatrixCss(e, p, z * f + (y ? y : ''));

							// animation time is over? if not next frame
							if (g < t) {
								requestAnimationFrame(frame);
							} else {
								// hard fix css to prevent escaping ranges
								RR.setMatrixCss(e, p, h + (y ? y : ''));
							}
						}
					);
				})(s,p,m,x,y,h);
			}
		}	
	},
	effects: function(fx, f) {
		switch(fx) {
			case 'easeIn': f = Math.pow( f, 5 );
				break;
			case 'easeOut': f = 1 - Math.pow( 1 - f, 5 );
				break;
			case 'easeOutStrong': f = (f == 1) ? 1 : 1 - Math.pow(2, -10 * f);
				break;
			case 'easeInBack': f = (f) * f * ((1.70158 + 1) * f - 1.70158);
				break;
			case 'easeOutBack': f = (f = f - 1) * f * ((1.70158 + 1) * f + 1.70158) + 1;
				break;
			case 'easeOutQuad': f = f < 0.5 ? 2 * f * f : -1 + (4 - 2 * f) * f;
				break;
			case 'easeOutCubic': f = f * f * f;
				break;
			case 'easeInOutCubic': f = f < 0.5 ? 4 * f * f * f : (f - 1) * (2 * f - 2) * (2 * f - 2) + 1;
				break;
			case 'easeInQuart': f = f * f * f * f;
				break;
			case 'easeOutQuart': f = 1 - (--f) * f * f * f;
				break;
			case 'easeInOutQuart': f = f < 0.5 ? 8 * f * f * f * f : 1 - 8 * (--f) * f * f * f;
				break;
			case 'easeInQuint': f = f * f * f * f * f;
				break;
			case 'easeOutQuint': f = 1 + (--f) * f * f * f * f;
				break;
			case 'easeInOutQuint': f = f < 0.5 ? 16 * f * f * f * f * f : 1 + 16 * (--f) * f * f * f * f;
				break;
			case 'elastic': f = Math.pow(2, 10 * (f - 1)) * Math.cos(20 * Math.PI * 1.5 / 3 * f);
				break;
			case 'easeInElastic': f = (.04 - .04 / f) * Math.sin(25 * f) + 1;
				break;
			case 'easeOutElastic':  f = .04 * f / (--f) * Math.sin(25 * f);
				break;
			case 'easeInOutElastic': f = (f -= 0.5) < 0 ? (0.01 + 0.01 / f) * Math.sin(50 * f) : (0.02 - 0.01 / f) * Math.sin(50 * f) + 1;
				break;
			case 'easeInSin':  f = 1 + Math.sin(Math.PI / 2 * f - Math.PI / 2);
				break;
			case 'easeOutSin':  f = Math.sin(Math.PI / 2 * f);
				break;
			case 'easeInOutSin':  f = (1 + Math.sin(Math.PI * f - Math.PI / 2)) / 2;
				break;
			case 'easeInCirc':  f = -(Math.sqrt(1 - (f * f)) - 1);
				break;
			case 'easeOutCirc':  f = Math.sqrt(1 - Math.pow((f-1), 2));
				break;
			case 'easeInOutCirc':  f = ((f /= 0.5) < 1) ? -0.5 * (Math.sqrt(1 - f * f) - 1) : 0.5 * (Math.sqrt(1 - (f-=2) * f) + 1);
				break;
			case 'easeInQuad':  f = f * f;
				break;
			case 'easeInExpo':  f = (f === 0) ? 0 : Math.pow(2, 10 * (f - 1));
				break;
			case 'easeOutExpo':  f = (f === 1) ? 1 : -Math.pow(2, -10 * f) + 1;
				break;
			case 'easeInOutExpo':  f = ((f /= 0.5) < 1) ? 0.5 * Math.pow(2,10 * (f-1)) : 0.5 * (-Math.pow(2, -10 * --f) + 2);
				break;
			case 'easeOutBounce':  
				if ((f) < (1/2.75)) {
					f = (7.5625 * f * f);
				} else if (f < (2/2.75)) {
					f = (7.5625 * (f -= (1.5 / 2.75)) * f + 0.75);
				} else if (f < (2.5/2.75)) {
					f = (7.5625 * (f -= (2.25 / 2.75)) * f + 0.9375);
				} else {
					f = (7.5625 * (f -= (2.625 / 2.75)) * f + 0.984375);
				}
				break;
			case 'bouncePast': 
				if (f < (1/2.75)) {
					f = (7.5625 * f * f);
				} else if (f < (2 / 2.75)) {
					f = 2 - (7.5625 * ( f -= (1.5 / 2.75)) * f + 0.75);
				} else if (f < (2.5 / 2.75)) {
					f = 2 - (7.5625 * ( f -=(2.25 / 2.75)) * f + 0.9375);
				} else {
					f = 2 - (7.5625 * ( f -=(2.625 / 2.75)) * f + 0.984375);
				}
				break;
			case 'bounce': 
				var value;
				for (var a = 0, b = 1; 1; a += b, b /= 2){
					if (f >= (7 - 4 * a) / 11){
						value = b * b - Math.pow((11 - 6 * a - 11 * f) / 4, 2);
						break;
					}
				}
				f = value;
				break;
			case 'swingTo': f = (f -= 1) * f * ((1.70158 + 1) * f + 1.70158) + 1
				break;
			case 'swingFrom': f = f * f * ((1.70158 + 1) * f - 1.70158);
				break;
			case 'spring': f = 1 - (Math.cos(f * 4.5 * Math.PI) * Math.exp(-f * 6));
				break;
			case 'blink': f = Math.round(f*(5)) % 2;
				break;
			case 'pulse': f = (-Math.cos((f*((5)-.5)*2)*Math.PI)/2) + .5
				break;
			case 'wobble': f = (-Math.cos(f*Math.PI*(9*f))/2) + 0.5;
				break;
			case 'sinusoidal': f = (-Math.cos(f*Math.PI)/2) + 0.5;
				break;
			case 'flicker': 
				f = f + (Math.random()-0.5)/5; 
				f = this.effects('sinusoidal', f < 0 ? 0 : f > 1 ? 1 : f);
				break;
			case 'mirror':
				if (f < 0.5) {
					f = this.effects('sinusoidal', f * 2);
				} 
				else {
					f = this.effects('sinusoidal', 1 - (f - 0.5) * 2);				
				}
				break;
			case 'radical':  f = Math.sqrt(f);
				break;
			case 'harmony':  f = (1 + Math.sin((f - 0.5) * Math.PI)) / 2;
				break;  
			case 'back':  f = Math.pow(f, 2) * ((1.5 + 1) * f - 1.5);
				break;
			case 'expo': f = Math.pow(2, 8 * (f - 1));
				break;
			default: f = 1;
				break;
		}
		return f;
	},

	// return a color in rgb format
	getRGB: function(color) {

		let result;
		let colors = {
			aqua:[0,255,255,1], azure:[240,255,255,1], beige:[245,245,220,1], black:[0,0,0,1], blue:[0,0,255,1], brown:[165,42,42,1], cyan:[0,255,255,1], darkblue:[0,0,139,1], darkcyan:[0,139,139,1], darkgrey:[169,169,169,1], darkgreen:[0,100,0,1], darkkhaki:[189,183,107,1], darkmagenta:[139,0,139,1], darkolivegreen:[85,107,47,1], darkorange:[255,140,0,1], darkorchid:[153,50,204,1], darkred:[139,0,0,1],
			darksalmon:[233,150,122,1], darkviolet:[148,0,211,1], fuchsia:[255,0,255,1], gold:[255,215,0,1], green:[0,128,0,1], indigo:[75,0,130,1], khaki:[240,230,140,1], lightblue:[173,216,230,1],
			lightcyan:[224,255,255,1], lightgreen:[144,238,144,1], lightgrey:[211,211,211,1], lightpink:[255,182,193,1], lightyellow:[255,255,224,1], lime:[0,255,0,1],
			magenta:[255,0,255,1], maroon:[128,0,0,1], navy:[0,0,128,1], olive:[128,128,0,1], orange:[255,165,0,1], pink:[255,192,203,1], purple:[128,0,128,1],
			violet:[128,0,128,1], red:[255,0,0,1], silver:[192,192,192,1], white:[255,255,255,1], yellow:[255,255,0,1], transparent:[255,255,255,0]
		  };

		// Check if we're already dealing with an array of colors
		if(color && color.constructor == Array && color.length == 4) {
			return color;
		}

		let patterns = [
			/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/, 
			/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/, 
			/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
			/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
			/^hsla\((\d+),\s*([\d.]+)%,\s*([\d.]+)%,\s*(\d*(?:\.\d+)?)\)$/,
			/^hsl\((\d+),\s*([\d.]+)%,\s*([\d.]+)%\)$/,
			/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/,
			/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/
		];

		function hsla2rgb(h, s, l){
			
			function hue2rgb(p, q, t){
				
				if(t < 0) t += 1;
				if(t > 1) t -= 1;
				if(t < 1 / 6) return p + (q - p) * 6 * t;
				if(t < 1 / 2) return q;
				if(t < 2 / 3) return p + (q - p) * (2/3 - t) * 6;
				
				return p;
			}

			const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
			const p = 2 * l - q;

			return [parseInt(hue2rgb(p, q, h + 1 / 3) * 255), parseInt(hue2rgb(p, q, h) * 255), parseInt(hue2rgb(p, q, h - 1 / 3) * 255)];
		};

		if(result = patterns[0].exec(color)) {
			return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3]), 1];
		}
		
		if(result = patterns[1].exec(color)) {
			return [parseFloat(result[1]) * 2.55, parseFloat(result[2]) * 2.55, parseFloat(result[3]) * 2.55, 1];
		}

		if(result = patterns[2].exec(color)) {
			return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3]), parseFloat(result[4])];
		}
		
		if(result = patterns[3].exec(color)) {
			return [parseFloat(result[1]) * 2.55, parseFloat(result[2]) * 2.55, parseFloat(result[3]) * 2.55, parseFloat(result[4])];
		}

		if( result = patterns[4].exec(color)) {
			return hsla2rgb( parseInt(result[1]) / 360, parseInt(result[2]) / 100, parseInt(result[3]) / 100 ).concat(parseFloat(result[4]));
		}

		if( result = patterns[5].exec(color)) {
			return hsla2rgb( parseInt(result[1]) / 360, parseInt(result[2]) / 100, parseInt(result[3]) / 100).concat(1);
		}

		if(result = patterns[6].exec(color)) {
			return [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16), 1];
		}

		if(result = patterns[7].exec(color)) {
			return [parseInt(result[1] + result[1], 16), parseInt(result[2] + result[2], 16), parseInt(result[3] + result[3], 16), 1];
		}

		// Otherwise, we're most likely dealing with a named color
		return colors[color.trim().toLowerCase()];
	},

	// color animation engine
	fade: function(e, p, v, t) {

		// v arg - color destination;
		// e arg - elem
		// p arg - propertie
		// t arg - duration

		let s = performance.now();

		var v = this.getRGB(v);
		var c = this.getRGB(this.styleGet(e,p));

		// delta interpolation for colors animation
		function lerp(a, b, u) {
			return (1 - u) * a + u * b;
		}

		requestAnimationFrame(
			function frame(d) {

				// g - time gone; s - start time
				let g = d - s;

				function colors(h, m) {
					return parseInt(lerp( h, m, g / t ));
				}

				e.style.setProperty(p, 'rgba('+ colors(c[0], v[0]) +','+ colors(c[1], v[1]) +','+ colors(c[2], v[2]) +','+ parseFloat(lerp( v[3], c[3], g / t )) +')');

				// animation time is over? if not do next frame
				if (d < t) {
					requestAnimationFrame(frame);
				} 
				else {
					e.style.setProperty(p, 'rgba('+ v[0] +','+ v[1] +','+ v[2] +','+ v[3] +')');
				}
			}
		);
	},

	// engine for works with CSS link[href]
	externalCSS: function(schema, q, c) {
		if(q) {
			this.attr('#schema', {'href': './app/schema/'+ schema +'.css', 'media': q});
		} else {
			this.attr('#schema', {'href': './app/schema/'+ schema +'.css'});
		}
		this.callback(c, schema);
	},

	// engine for works with external JS script[src]
	externalJS: (src) => {
		RR.dom('script',"new|after|head", {
			attr: {
				type: "text/javascript",
				async: "async",
				src: src
			}
		});
	},

	// attributes engine
	attr: function(e,x) {
		var items = this.isO(e) ? [e] : this.get(e);
		let count = 0;
		for(let i of items) {

			if(this.isO(x)) {
				for(let b in x) {
					if(x[b] === null) {
						i.removeAttribute(b);
					} 
					else {
						i.setAttribute(b, x[b]);
					}
				}
			} 

			if(this.isS(x)) {
				var q = this.arguments(x,",");
				var p = [];
				for(let w in q) { 
					p[count++] = i.getAttribute(q[w]);
				}
			}
		}; 

		if(this.isS(x)) {
			return p;
		}
	},
 
	// selectors engine
	dom: function(e,m,p,c) {
	
		// self fix arguments call
		if(this.isF(arguments[1])) {
			let c = arguments[1];
			let m = null;
			let p = null;
		}

		if(this.isF(arguments[2])) {
			let p = null;
			let c = arguments[2];
		}

		let mod = [];
		var c = c || null;

		if( m && this.isS(m) ) {
			mod = this.arguments(m, "|");
		} 
		else {
			mod[0] = null;
		}

		switch(mod[0]) {
			case "new": this.create(e, mod[1], mod[2], p);
				break;
			case "del": this.del(e);
				break;
			case "style": 
				if( !this.isU(p) ) { 
					this.styleApply(this.get(e), p);
				}
				break;
			case "animate": 
				let ael = this.htmlObj(e);
				this.animate(ael, p, c);
				break;
			case "toggle": this.toggle(this.get(e),c);
				break;
			case "wrap": this.wrap(this.get(e), p);
				break;
			case "unwrap": this.unwrap(this.get(e));
				break;
			case "replace": this.replace(this.get(e), p);
			default: if(!m) {
				return this.get(e);
			}
		}
	},

	// replace some element to another element or with randon html
	replace: function(e, w) {
		var e = this.htmlObj(e);

		for(let i of e) {
			i.parentElement.replaceChild(!this.isO(w) ? this.convertSTRToHTML(w)[0] : w, i);
		}

	},
 
	// simply get link to html object
	get: function(sel) {
		// cleans selectors for dublicates
		let temp = document.querySelectorAll(sel);
		if(temp.length) { 
			return this.filterHTML(temp);
		}
	},

	// create and insert new elements in document
	// with attributes and load into it html data
	create: function(e, how, where, p) {
		var t = this.treeHacks(this.get(where));
		var n = this.that.createElement(e);
		
		if(p.attr) {
			this.attr(n,p.attr);
		}
		n.innerHTML = (p.html) ? p.html : "";
 
		switch(how) {
			case "before":
				t.insertBefore(n, t.firstChild);
			break;
			case "after":
				t.insertBefore(n, t.lastChild);
			break;
			default: this.insert(t,n,p.html);
				break;
		}
	},
 
	// insert in element html data
	// or create new element with contents
	insert: function(t,e,c) {
		var t = this.htmlObj(t);
		if(!c) {
			for(let i in t) {
				this.treeHacks(t[i]).innerHTML = e;
			}
		}
		if(c) {
			t.insertBefore(e,null);
			e.innerHTML = c;
		}
	},

	// delete elements from document
	del: function(e) {
		var t = this.htmlObj(e);
		for(let i in t) { 
			t[i].remove();
		}
	},

	// wrap elements
	wrap: function(e,w) {
		
		var e = this.htmlObj(e);
		var w = document.createElement(w);

		for(let i of e) {
			i.parentElement.insertBefore(w, i);
			w.appendChild(i);
		}

	},

	// unwrap elements
	unwrap: function(e) {
		var e = this.htmlObj(e);

		for(let i of e) {

			let parent = i.parentElement;
		
			while (i.firstChild) {
				parent.insertBefore(i.firstChild, i);
			}

			parent.removeChild(i);
		}
	},

	// local storage API wrapper
	storage: function(p,m) {
		
		let args = [];

		switch(m) {
			case 'set':
				
				if( this.isS(p) ) {
					args.push(this.arguments(p,'='));
				}
				
				if( this.isO(p) && p.length > 0) {
					for(let i in p) {
						args[i] = this.arguments(p[i],'=');
					}
				}
				for(let i in args) {
					localStorage.setItem(args[i][0].trim(), args[i][1].trim());
				};
			break;
			case 'get':
				if(this.isS(p)) {
					return localStorage.getItem(p.trim());
				}
			break;
			case 'del':
				if(this.isS(p)) {
					localStorage.removeItem(p.trim());
				}
				if(this.isO(p)) {
					for(let i in p) { 
						localStorage.removeItem(p[i].trim());
					}
				}
			break;
		}

	},
 
	// some HTML element objects like .class have a second level including
	// others like #id have not includings
	// 1) # -> dom()
	// 2) . -> [0] -> dom()
	treeHacks: (e) => (e[0]) ? e[0] : e,

	// test for HTML objects are equal
	equality: function(a,b) {
		return a.offsetLeft === b.offsetLeft && a.offsetTop === b.offsetTop && a.outerHTML === b.outerHTML ? true : false;
	},

	// filter for HTML objects only in Node Lists
	filterHTML: (aels) => {
		var filtered = [];
		var cnt = 0;
		for(let i in aels) {
			if( aels[i].nodeName !== '#text' && 
				aels[i].nodeName !== "#comment" && 
				!RR.isN(aels[i]) && 
				!RR.isU(aels[i]) && 
				!RR.isS(aels[i]) && 
				!RR.isF(aels[i]) &&
				!RR.isA(aels[i])) {
					if(aels[i] !== "") {
						filtered.push(aels[i]);	
					}
			}
		}; return filtered;
	},

	// convert string HTML to DOM
	convertSTRToHTML: function(html) {
		var sndbx = this.that.createElement('div');
		var temp = []; 
		sndbx.innerHTML = html;

		for(let i in sndbx.children) {
			if (sndbx.children[i].tagName !== 'SCRIPT') {
				temp.push(sndbx.children[i]);
			}
		}

		return this.filterHTML(temp);
	},

	// grep inner HTML inside some tag
	findHTMLByTag: function(tag, data) {
		return this.convertSTRToHTML(data)[0].querySelectorAll(tag);
	},

	// get letters by index
	// 0 - first letter
	letter: function(lt, ind) {
		return this.isN(ind) ? lt[ind] : false;
	},

	// help to parse modules API arguments
	arguments: function(a, d) {
		var args = a.split(d + "");
		for(let i in args) {
			args[i] = args[i].trim();
		}
		return args;
	},

	// this helper returns normalized 
	// for browser style name
	// example: border-left -> borderLeft
	normalizeStyleName: (s) => {
		var s = RR.arguments(s,"-");
		let r = '';
		for(let z in s) { 
			if(z >= 1) {
				s[z] = RR.letter(s[z],0).toUpperCase() + s[z].slice(1);
			}
			r += s[z];
		}; return r;
	},

	// get offset positions from style name
	normalizeStyleNameOffset: function(e,p) {
		var r = 'offset' + this.letter(p,0).toUpperCase() + p.slice(1);
		if(!this.isU(e[r])) {
			return e[r];
		}
	},

	// returns only number
	numberCSS: (v) => {
		let tests = ['px','ex','em','%','in','cm','mm','pt','pc','deg'];
		let count = 0;

		for (var i in tests) {
			if(v.includes(tests[i])) {
				return [v.replace(tests[i],'') - 0, tests[i]];
			} 
			else {
				if(count++ === 9) {
					return [v - 0, null];
				}
			}
		}
	},

	// css parameters shorthands helper
	shortToFull: function(e,p) {

		let isShort = null;
		let isTransformShort = null;

		let n = p[0] + "";
		let w = p[1];
		
		function replacer(str) {
			return str.replace(/\s+/g,'');
		};

		var p = this.arguments(p[1].replace(/\(.*?\)/g, replacer),' ');
		let j = p;
		let q = 0;
		let t = [];

		// collection of shorthands posible properties
		this.shorts   = ['border-radius','border-width','padding','margin', 'border-color'];
		this.shorts2  = ['skew', 'translate', 'scale', 'rotate'];
		this.fourval1 = ['TopLeft','TopRight','BottomLeft','BottomRight'];
		this.fourval2 = ['Top','Right','Bottom','Left'];
		this.fourval3 = ['X','Y','Z'];

		if(n === 'transform') {
			let resultstring = '';
			for(let g of p) {
				for(let k in this.shorts2) {
					var shorten = g.split('(')[0];
					if( this.shorts2[k] === shorten ) {
						for(let r in this.fourval3) {

							let valueUnits = this.arguments( this.getValFromPropsBrackets(shorten, g)[1], ',' );
							
							if( valueUnits[r] ) {
								resultstring += " "+ shorten + this.fourval3[r] +'('+ valueUnits[r] +')'+' '+ w.replace(g, ''); 
							}
						
						}
					}
				}

			}
			t.push(['transform', resultstring, 'NaN']);
		}
		
		for(let y of this.shorts) {
			if(y === n) {
				isShort = true;
			}
		}

		// repack standart properties
		if(isShort) {

			// autocomplite all values in shorthand notation
			// 1 * 4 repeated value
			// (1-2) * 2 repeated value
			// (1-2-3) + 2 to 4
			switch( p.length - 0 ) {
				case 1: p.push(p[0],p[0],p[0]);
					break;
				case 2: p.push(p[0],p[1]);
					break;
				case 3: p.push(p[1]);
					break;
			}

			// get computed values for all four longhand properties
			if(p.length === 4) {
				// expand full property definition from shorthand
				for(let y in this.shorts) {
					if(this.shorts[y] === n) {
						var xt = this.arguments(n,'-');

						if(n == 'border-radius' ) {
							var fg = this.fourval1;
							var df = null;
						}

						if(n == 'padding' || n == 'margin' || n == 'border-width') {
							var fg = this.fourval2;
							var df = 1;
						}

						if(n == 'border-color') {
							var df = null;
							var fg = this.fourval2;
						}

						for(let s in fg) {
							var style = (!df) ? this.normalizeStyleName(xt[0] +'-'+  fg[q] +'-'+ xt[1]) : this.normalizeStyleName(xt[0] +'-'+  fg[q]);
							// style, destination, units
							t.push([style,this.numberCSS(p[q])[0],this.numberCSS(p[q])[1]]);
							q++;
						}
					}
				}
			} 
		}
		// normal values
		if(!isShort) {
			t.push([this.normalizeStyleName(n), this.numberCSS(j[0])[0], this.numberCSS(j[0])[1]]);
		}
		return t;
	},

	// test for callback is present
	callback: function(e, c) { 

		if(this.isC(c)) {
			c.call(e);
		}
	},

	// strip string to number
	stripNum: (v) => +v.replace(/\D+/g,"") - 0,

	// is HTML Object defined and how
	htmlObj: (e) => RR.isO(e) ? e : RR.get(e),

	// is callback defined
	isC: (c) => c && RR.isF(c) ? true : null,

	// is object
	isO: (v) => typeof(v) === 'object' ? true : null,
 
	// is string
	isS: (v) => typeof(v) === 'string' ? true : null,
 
	// is array 
	isA: (v) => typeof(v) === 'array' ? true : null,
 
	// is function
	isF: (v) => typeof(v) === 'function' ? true : null,
 
	// is undefined
	isU: (v) => typeof(v) === 'undefined' ? true : null,
 
	// is number
	isN: (v) => typeof(v) === 'number' ? true : null,
 
	// very simple console
	log: (d) => {
		console.log(d);
	},
};


// Define class for new application
class Revolver {

	constructor( namespace ) {
		let loader = async function() {

			 	await eval('self.'+ namespace  +' = RR');

			 	console.log(document.location);

				// path to bootstrap css
				RR.bootCss = '/app/revolver.css';
  
				// [ get browser info & autostart some window features ]
				RR.browser();
				RR.screenPosition(null, null, null);

				// [ use self definition user programm and run it ]
				RR.bootstrap();
		};

		// READY TO EXEC
		document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll) ? loader() : document.addEventListener("DOMContentLoaded", loader);

	}


}

// Revolver evaluting
function RREval() {
	eval( document.getElementById('revolver').innerHTML );
}


// DOM ready execution
document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll) ? RREval() : document.addEventListener("DOMContentLoaded", RREval);	
