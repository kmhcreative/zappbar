/* 	Front-end Functions for zAppBar Plugin
	This uses variables from an in-page script
	that imports settings values from PHP which
	must be in the code before this script.
*/

jQuery(document).ready(function($){
// get/set main page wrapper element
var wrapper = wrapper || ["#page","#page-wide","#wrapper"];
if (page_custom != "") {
    wrapper.push(page_custom);
};
	var my_wrapper = "#page";	// default page wrapper
	for (var x=0;x<wrapper.length;x++) {
		if ($(''+wrapper[x]+'').length) {
			var my_wrapper = wrapper[x];	// find actual page wrapper
		}
	};
var retrofitTheme = function() {

	if (header_custom == '') { var my_header = "#header"; } else { var my_header = "#"+header_custom; };
	if (nav_custom == '') { var my_nav = "#menubar-wrapper"; } else { var my_nav = "#"+nav_custom; };
	var my_sidebars = "#sidebar-left, #sidebar-right, #primary, #secondary";
	if (sidebars_custom.length > 0) {
		for (var x=0; x < sidebars_custom.length; x++) {
			my_sidebars += ","+sidebars_custom[x]+" ";
		}
	}
	var barlist = my_sidebars.split(',');
	var cols0 = '';	  // #content-column with no columns left or right (layout-ncl)
	var cols2 = '';	  // #content-column with 1 column to left (layout-2cl) or right (layout-2cr)
	var cols3 = '';   // #content-column with 1 column to left AND 1 to right (layout-3c)
					  // #content-column with 2 columns to left  (layout-3cl)
					  // #content-column with 2 columns to right (layout-3cr)
					  // #content-column with 3 columns, main to left (layout-3clgn) or right (layout-3crgn)
	for (var s=0; s < barlist.length; s++) {
		var c = s<barlist.length-1 ? ', ' : '';
		cols2+='body.layout-2cl '+barlist[s].trim()+', body.layout-2cr '+barlist[s].trim()+c;
		cols3+='body.layout-3c '+barlist[s].trim()+',  body.layout-3cl '+barlist[s].trim()+', body.layout-3cr '+barlist[s].trim()+c+', '+
		'body.layout-3clgn '+barlist[s].trim()+', body.layout-3crgn '+barlist[s].trim()+c;
	}
	// get width of site wrapper element
	if (auto_width == 'on') {
		var site_width = (parseInt($(''+my_wrapper+'').css('width'))+parseInt($(''+my_wrapper+'').css('border-left-width'))-1 );
	} else {
		var site_width = theme_width;
	}
	var kickin = 767;
	var retrofit = '/* Retrofit Theme with Responsive Layout */\n'+
	'html { overflow-x: hidden; }\n'+
	'	'+my_wrapper+', '+my_wrapper+' '+my_header+', '+my_wrapper+' '+my_nav+', #main, #branding,\n'+
	'	'+my_wrapper+' #subcontent-wrapper, '+my_wrapper+' #footer, '+my_wrapper+' #colophon,\n'+
	'	'+my_wrapper+' #comic-header, '+my_wrapper+' #comic-foot {\n'+
	'		width: auto !important;\n'+
	'		max-width: '+site_width+'px;\n'+
	'	}\n'+
	'	#branding img {\n'+
	'		height: auto;\n'+
	'	}\n'+
	'		'+my_wrapper+' '+my_header+' h1 {\n'+
	'			max-width: 100%;\n'+
	'			-webkit-background-size: contain;\n'+
	'			-moz-background-size: contain;\n'+
	'			-ms-background-size: contain;\n'+
	'			background-size: contain;\n'+
	'		}\n'+
	'	'+my_nav+', #access, #menu, .menu, .menu-container, #comic-wrap, #subcontent-wrapper, #footer, #footer-sidebar-wrapper {\n'+
	'		max-width: 100%;\n'+
	'		height: auto;\n'+
	'		margin-left: auto; margin-right: auto;\n'+
	'	}\n'+
	'	img {\n'+
	'		max-width: 100%;\n'+
	'	}	\n'+
	'   	#footer {\n'+
	'  			display: block;\n'+
	' 		}\n'+
	'		.zappbar { \n'+
	'			display: none; \n'+
	'		}	\n'+	
	'		div.sbtab { display: none; }\n';
	// if site is > kickin @ 1px narrower make content and sidebars fluid
	if (site_width > kickin) {
	retrofit += '\n'+
	'@media screen and (max-width: '+(site_width-1)+'px) { /* switch columns to percentages */\n'+
	'	'+my_wrapper+', '+my_nav+', .menu-container, \n'+
	'	#comic-wrap, #subcontent-wrapper, #footer, #footer-sidebar-wrapper {\n'+
	'		/* borders and padding included in width */\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		-o-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'		/* box shadows introduce horizontal scrolling */\n'+
	'		-webkit-box-shadow: none !important;\n'+
	'		-moz-box-shadow: none !important;\n'+
	'		-ms-box-shadow: none !important;\n'+
	'		-o-box-shadow: none !important;\n'+
	'		box-shadow: none !important;\n'+
	'	}\n'+
	'/* No Columns (zero sidebars) */\n'+
	'   #content-column, .layout-ncl #content-column {\n'+
	'		width: 100% !important;\n'+
	'		max-width: 100% !important;\n'+
	'		min-width: 100% !important;\n'+
	'		margin: 0;\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'   }\n'+
	'/* 2 Columns (1 sidebar right or left) */\n'+      
	'	'+cols2+' {\n'+
	'		width: 25% !important;\n'+
	'		max-width: 25% !important;\n'+
	'		min-width: 25% !important;\n'+
	'		margin: 0;\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'	}\n'+
	'	.layout2cl #content-column, .layout2cr #content-column {\n'+
	'		width: 75% !important;\n'+
	'		max-width: 75% !important;\n'+
	'		min-width: 75% !important;\n'+
	'		margin: 0;\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'	}\n'+
	'/* 3 Columns (1 both sides or 2 right or left) */\n'+
	'	'+cols3+' {\n'+
	'		width: 25% !important;\n'+
	'		max-width: 25% !important;\n'+
	'		min-width: 25% !important;\n'+
	'		margin: 0;\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'	}\n'+
	'   .layout-3c #content-column, .layout-3cl #content-column, .layout-3cr #content-column,\n'+
	'   .layout-3clgn #content-column, .layout-3crgn #content-column {\n'+
	'		width: 50% !important;\n'+
	'		max-width: 50% !important;\n'+
	'		min-width: 50% !important;\n'+
	'		margin: 0;\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'	}\n'+
	'	.comic-table {\n'+
	'		display: block !important;\n'+
	'	}\n'+
	'	#comic, #comic {\n'+
	'		width: auto;\n'+
	'		max-width: 100%;\n'+
	'		display: block !important;\n'+
	'		margin: 0 auto;	/* center it */\n'+
	'	}\n'+
	'}\n';
	}
	// Below 800px wide everything should be fluid (<800px should be most tablets in portrait)
	if (site_width < kickin) { kickin = site_width-1; }
	retrofit += '\n'+
	'@media screen and (max-width: '+kickin+'px) {	/* below this width alter layout */\n'+
	'	'+my_wrapper+', #menubar-wrapper, .menu-container, \n'+
	'	#comic-wrap, #subcontent-wrapper, #footer, #footer-sidebar-wrapper {\n'+
	'		/* borders and padding included in width */\n'+
	'		-webkit-box-sizing: border-box;\n'+
	'		-moz-box-sizing: border-box;\n'+
	'		-ms-box-sizing: border-box;\n'+
	'		-o-box-sizing: border-box;\n'+
	'		box-sizing: border-box;\n'+
	'		/* box shadows introduce horizontal scrolling */\n'+
	'		-webkit-box-shadow: none !important;\n'+
	'		-moz-box-shadow: none !important;\n'+
	'		-ms-box-shadow: none !important;\n'+
	'		-o-box-shadow: none !important;\n'+
	'		box-shadow: none !important;\n'+
	'	}\n'+
	'   #subcontent-wrapper { display: block !important; }\n'+
	'\n'+
	'  	'+my_sidebars+' #sidebar-left-of-comic, #content {\n'+
	'		float: none !important;\n'+
	'		width: auto !important;\n'+
	'		max-width: 100% !important;\n'+
	'		min-width: 0px !important;\n'+
	'		min-height: 0px;\n'+
	'		margin-left: 0;\n'+
	'		margin-right: 0;\n'+
	'		clear: both;\n'+
	'		display: block !important;\n'+	
	'   }\n'+
	'   '+cols2+' {\n'+
	'		float: none !important;\n'+
	'		width: auto !important;\n'+
	'		max-width: 100% !important;\n'+
	'		min-width: 0px !important;\n'+
	'		min-height: 0px;\n'+
	'		margin-left: 0;\n'+
	'		margin-right: 0;\n'+
	'		clear: both;\n'+
	'		display: block !important;\n'+
	'	}\n'+
	'   '+cols3+' {\n'+
	'		float: none !important;\n'+
	'		width: auto !important;\n'+
	'		max-width: 100% !important;\n'+
	'		min-width: 0px !important;\n'+
	'		min-height: 0px;\n'+
	'		margin-left: 0;\n'+
	'		margin-right: 0;\n'+
	'		clear: both;\n'+
	'		display: block !important;\n'+
	'	}\n'+
	' 	#content-column, .layout-ncl #content-column, \n'+
	'   .layout-2cl #content-column, .layout-2cr #content-column,\n'+
	'   .layout-3c #content-column, .layout-3cl #content-column, .layout-3cr #content-column, \n'+
	'   .layout-3clgn #content-column, .layout-3crgn #content-column {\n'+
	'		float: none !important;\n'+
	'		width: auto !important;\n'+
	'		max-width: 100% !important;\n'+
	'		min-width: 0px !important;\n'+
	'		min-height: 0px;\n'+
	'		margin-left: 0;\n'+
	'		margin-right: 0;\n'+
	'		clear: both;\n'+
	'		display: block !important;\n'+
	'	}\n'+
	'	.comic-table {\n'+
	'		display: block !important;\n'+
	'	}\n'+
	'	#comic, #comic {\n'+
	'		width: auto;\n'+
	'		max-width: 100%;\n'+
	'		display: block !important;\n'+
	'		margin: 0 auto;	/* center it */\n'+
	'	}\n'+
	'}\n';
	var zb_retrofit = document.createElement('style');
		zb_retrofit.id = "zb-retrofit";
		zb_retrofit.type = "text/css";
		zb_retrofit.innerHTML = retrofit;
	$('head')[0].appendChild(zb_retrofit);	// <-- has to come last or causes ugly reflow
	retrofitTheme = null;
}

/*	DEVICE & BROWSER DETECTION
	Pared down sniffer based on "Web-O-Detecto"
	full script @ https://gist.github.com/kmhcreative/cc73f6a5da2e0919432f
*/
var device = {};	// everything is a sub-object of this object
if (navigator.userAgent.match(/Edge/i)) {	// original Edge Spartan
	// Edge UA lies about what it is so check it first
	var ver = navigator.userAgent.match(/Edge\/../i);
	ver = ver[0].split("/");
	ver = parseFloat(ver[1]);
	device.v = ver;
	if (navigator.userAgent.match(/Android/i)){
		device.OS = "Android";	// is now actual Android instead of Windows 10 Mobile
		device.appName = "Edge Mobile";
	} else {
		device.OS = "Windows 10";	// because only Win10 can run Edge Spartan
	}
	device.Platform = "Edge";
} else if (navigator.userAgent.match(/Edg/i)) {	// Edge built on Chromium
	/* Edge-Chromium lies even worse about what it is, in fact
	   it can dynamically, automatically change its UA string!
	   So there are no guarantees it ever gets sniffed right.
	*/
	var ver = navigator.userAgent.match(/Edg\/../i);
	ver = ver[0].split("/");
	ver = parseFloat(ver[1]);
	device.v = ver;
	if (navigator.userAgent.match(/Android/i)) {
		device.OS = "Android";
		device.appName = "Edge Mobile";
	} else if (navigator.userAgent.match(/EdgiOS/i)) {
		// Note: MS bugtrack #21307338 says it may send incorrect UA string
		device.OS = "iOS";
		device.appName = "Edge Mobile";
	} else {
		// could be Windows or Mac, grab it below
	}
	device.Platform = "Chrome";
	device.appName  = "Edge";	
} else if (navigator.userAgent.match(/MSIE/i) || navigator.userAgent.match(/Trident/i)){
	// IE Mobile also lies about what it is
	if (navigator.userAgent.match(/IEMobile/i)) {
		if (navigator.userAgent.match(/Windows Phone 8/i)) {
			device.OS = "Windows Phone 8";
		} else {
			device.OS = "Windows Phone 7";
			iScroll = undefined; // iScroll does not work with Windows Phone 7
		}
		device.appName = "IE Mobile";
	} else {device.OS = "Windows"; }	// some version of it anyway
	device.Platform = "IE";
	// now get the browser version
	if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
		device.v = new Number(RegExp.$1); // capture x.x portion and store as a number
	} else if (/IEMobile\/(\d+\.\d+)/.test(navigator.userAgent)) {
		device.v = new Number(RegExp.$1);
	} else if (/rv:(\d+\.\d+)\)/.test(navigator.userAgent)) {
		device.v = new Number(RegExp.$1);
	} else {};
	if (device.v < 9 || device.v == 10) { 
		iScroll = undefined; // Legacy IE cannot use this anyway
		if (device.v >= 10) { // Try to figure out if it is IE in Win 8.x "Metro" mode
			device.metro = function() {
				var metro = 0;
				try { metro = !!new ActiveXObject("htmlfile"); // Might be desktop mode with plugins disabled
				} catch (e) {
					metro = 0;
				}
				if (metro != 0) {
					if(window.innerWidth == screen.width && window.innerHeight == screen.height) {
						metro = 1; // It is probably in Metro mode, but may still be desktop in fullscreen mode
					} else {
						metro = 0;
					}
				}
				return metro;
			}
		}
	}
} else if (navigator.userAgent.match(/Android/i)) {
	device.OS = "Android";
	if (navigator.userAgent.match(/Firefox/) || navigator.userAgent.match(/Fennec/)) {
		device.Platform = "Firefox";
		device.appName  = "Firefox Mobile";
		device.v = 4; 	// assume it's at least 4, FFM UA string doesn't include Android version!
	} else {
		if (navigator.userAgent.match(/Silk/)) {
			// check for Silk first because it will say it is Chrome too
			// device.OS = "FireOS";	// uncomment only if you need to sniff FireOS
			device.Platform = "Chrome";
			device.appName  = "Silk";
			var bv = navigator.userAgent.match(/Silk\/(\d+\.\d+)/i);
				bv = parseFloat(bv[0].split("/")[1]);
				device.bv = bv; // actual browser version	
		} else if ( navigator.userAgent.match(/OPR\//i) || navigator.userAgent.match(/Opera/i)) {
			// check for Opera first because new Opera will say it is Chrome
			// needs to check for OPR/ because Google Nexus 5X uses OPR too
				if (navigator.userAgent.match(/Opera/i)) { // old Opera version
					var bv = navigator.userAgent.match(/Opera\/(\d+\.\d+)/i);
					device.Platform = "Opera";	
					device.appName = "Opera Mini";
				} else {
					// this needs to be before Chrome because new Blink-based Opera lies and says it is Chrome.
					var bv = navigator.userAgent.match(/OPR\/(\d+\.\d+)/i);
					device.Platform = "Chrome";
					device.appName = "Opera Mobile";
				}
				bv = parseFloat(bv[0].split("/")[1]);
				device.bv = bv;			
		} else if (navigator.userAgent.match(/Chrome/)) {
			device.Platform = "Chrome";
			device.appName  = "Chrome Mobile";
			var bv = navigator.userAgent.match(/Chrome\/(\d+\.\d+)/i);
				bv = parseFloat(bv[0].split("/")[1]);
				device.bv = bv;	// actual browser version
		} else {
			device.Platform = "Android"; // Android 2.x UA string does not say Chrome
			device.appName  = "Browser";
			var bv = navigator.userAgent.match(/AppleWebKit\/(\d+\.\d+)/i);
				bv = parseFloat(bv[0].split("/")[1]);
				device.bv = bv;	// actual WebKit Build
		}
		// Android version may or may not have a point sub-version
		var ver = (navigator.userAgent.match(/Android (\d+\.\d+)/i)) ? navigator.userAgent.match(/Android (\d+\.\d+)/i) : navigator.userAgent.match(/Android (\d+)/i);
		ver = ver[0].split(" ");
		ver = parseFloat(ver[1]);
		device.v = ver;	// Android Version from UA String
		if (window.matchMedia('(display-mode: standalone)').matches) {	// bookmarked to homescreen
			device.app = 1;
		}
	}
} else if ( navigator.userAgent.match(/OPR\//i) || navigator.userAgent.match(/Opera/i) || window.opera ) {
		// Google Nexus 5X desktop view would have OPR so need to check for OPR/ (with trailing slash)
		device.Platform = "Opera";
			if (navigator.userAgent.match(/Opera/i)) { // old Opera version
        	var fullVersion = window.opera.version();
			device.v = parseInt(''+fullVersion,10);
			var subVersion = fullVersion.split('.');
			device.bv = subVersion[1];
		} else {
			// this needs to be before Chrome because new Blink-based Opera lies and says it is Chrome.
			var ver = navigator.userAgent.match(/OPR\/(\d+\.\d+)/i);
				ver = parseFloat(ver[0].split("/")[1]);
			device.v = ver;
		}
} else if ( navigator.userAgent.match(/Chrome/i) ) {
		device.Platform = "Chrome"; // Check first since UA string contains Safari
		var fullVersion  = ''+parseFloat(navigator.appVersion); 
		var majorVersion = parseFloat(navigator.appVersion,7);
		var verOffset = navigator.userAgent.indexOf("Chrome");
		fullVersion = navigator.userAgent.substring(verOffset+7);
		device.v = parseFloat(''+fullVersion,10);
} else if (navigator.userAgent.match(/wOSBrowser/i) || navigator.userAgent.match(/webOS/i)) {
	device.OS = "webOS"; // check first since UA string contains Safari
	device.Platform = "WebKit";
	device.appName  = "Browser";
} else if (navigator.userAgent.match(/RIM/i) || navigator.userAgent.match(/PlayBook/i) || navigator.userAgent.match(/BlackBerry/i)) {
	device.OS = "BlackBerryOS";	// check first since UA string contains Safari
	device.Platform = "WebKit";
	device.appName  = "Browser";
} else if (navigator.userAgent.match(/Safari/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i) ) {
	device.Platform = "Safari";
	if (navigator.userAgent.match(/iOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i) ) {
		device.OS = "iOS";
		device.appName  = "Mobile Safari";
		var fullVersion = navigator.appVersion;
		fullVersion = fullVersion.split("OS ");
		var majorVersion = parseInt(fullVersion[1]);
		if (majorVersion > 7) { // iOS 8.x userAgent string reports as v 10
			if (navigator.appVersion.match(/Version/gi)) {	// browser view
				fullVersion  = navigator.appVersion.split("Version/");
				majorVersion = parseFloat(fullVersion[1]);	// point value is not accurate anyway
			} else {	// appView so no version value!
				majorVersion = 8;	// we will just have to assume it is at least v 8.0	
			}
		}
		device.v = majorVersion; // This is OS major version, not browser version
		if (window.navigator.standalone || !navigator.userAgent.match(/Safari/)) { // bookmarked to home screen
			device.app = 1;
		} else { // Browser View, get actual browser version
			var fullVersion = navigator.appVersion;
			fullVersion = fullVersion.split("/");
			var majorVersion = parseFloat(fullVersion[2]);
			device.bv = majorVersion;
		}
	} else {
		var fullVersion  = ''+parseFloat(navigator.appVersion); 
		var majorVersion = parseInt(navigator.appVersion,7);
		var verOffset = navigator.userAgent.indexOf("Safari");
		fullVersion = navigator.userAgent.substring(verOffset-6);
		device.v = parseFloat(''+fullVersion,10);
	}
} else if (navigator.userAgent.match(/Firefox/i)) {
	if (navigator.userAgent.match(/\(Mobile/i) || navigator.userAgent.match(/\(Tablet/i)) {
		// only FF UA string with "Mobile"/"Tablet" right after parenthesis is Firefox OS	
		device.OS = "Firefox OS";
	}
	device.Platform = "Firefox" // Desktop Firefox
	var ver = navigator.userAgent.match(/Firefox\/../i);
	ver = ver[0].split("/");
	ver = parseFloat(ver[1]);
	device.v = ver;
} else {
	device.Platform = "Unknown";
	device.appName  = "Unknown";
}
// Rectify device OS if not already set
if (device.OS == undefined) {
	if (navigator.userAgent.match(/Windows/i)) { device.OS = "Windows";}
	else if (navigator.userAgent.match(/Macintosh/i)) { device.OS = "Mac";}
	else if (navigator.userAgent.match(/Linux/i)) { device.OS = "Linux";}
	else {};
}
// If Browser Version is not set use Device Version
if (device.bv == undefined) { device.bv = device.v; }
// If Browser AppName is not set use Platform Name
if (device.appName == undefined) { device.appName = device.Platform; }
// Now for a shorthand way to determine if we are on a mobile device or not
if (device.OS == 'Android' || device.OS == 'iOS' || device.OS == 'Windows 10 Mobile' || device.OS == 'Windows Phone 7' || device.OS == 'Windows Phone 8' || device.OS == 'BlackBerryOS' || device.OS == 'webOS' || device.OS == 'Firefox OS') {
	device.mobile = true;
} else {
	device.mobile = false;
}

	if ($('body').hasClass('admin-bar')) {
		$('html').addClass('zb-admin-bar');
	}

        	// woo img uses data-rel, many lightbox plugins use rel //
        	$(".zoom").each( function() {
        		if ( $(this).attr("data-rel") ) {
        			 var rel = $(this).attr("data-rel");
        			 if (rel.match(/prettyPhoto/gi)) {
        			 	$(this).attr("rel",""+rel+"");
        			 }
        		};
        	});
	
    var zbinit=comment_button=woo_desc_button=woo_review_button=woo_addl_button = false;

        	function zb_check() {
        		var w = $(window).width();
        		if (
					(	// apply based on screen size
						(
						   applyto == 'all' ||
						  (applyto == 'force_mobile' && device.mobile == false) ||
						  (applyto == 'only_mobile'  && device.mobile == true)
						) &&
						(
						  (	showon == "phones" 		&& w < 737	)||
						  (	showon == "idevices"	&& w < 1025 )||
						  (	showon == "tablets" 	&& w < 1281 )||
						  (	showon == "tablets_hd" 	&& w < 1441 )||
						  ( showon == "desktops" 	&& w < 1920 )||
						  ( showon == "desktops_hd")
						)
					) ||
					(applyto == 'force_mobile' && device.mobile == true)||	// over-ride regardless of screen size
					(applyto == 'only_mobile_forced' && device.mobile == true)
        		) { 
        			if (is_responsive == "2" && retrofitTheme != null) {
        				retrofitTheme();
        			}
					if (is_responsive!="0") {
        		 		$('#zb-site-tweaks-css').attr("href",""+zb_base+"css/site_tweaks.css");
        		 	}
        		 	if ( (applyto == 'force_mobile' || applyto == 'only_mobile_forced') && device.mobile == true) {
        		 		showon = "desktops_hd";
        		 		$("#zb-response-css").attr("href",""+zb_base+"css/zappbar_desktops_hd.css");
        		 	} else {
        		 		$("#zb-response-css").attr("href",""+zb_base+"css/zappbar_"+showon+".css");
        		 	}
        			// we only need to attach button actions on initial load
        			if (zbinit==false) { 
        				zb_init();
        			}
        			// see if any of these need to convert to panels
        			comment2panel();
        			review2panel();
        			addl2panel();
        			desc2panel();
        			$(".zb-switch").each( function() {
        				$(this).show();
        			});
        			zb_appify();
        		 } else {
        		 	$('#zb-site-tweaks-css').attr("href","");
        			un_appify(1);
					if (is_responsive == "2" && retrofitTheme != null) {
						retrofitTheme();
					}
        			$(".zb-switch").each( function() {
        				$(this).hide();
        			});
        		};
        	};
        	
        	// Add Panel Animations //
        	var zappPanel = function(panel,dir,plus) {
        		if(altertheme_push != "") {
        			var pushit = true;
        		} else {
        			var pushit = false;
        		};
    			if (pushit==true) {
    				if (dir==1 || dir=="right") {
    					var push = "pushright";
    					var pull = "pushleft";
    				} else { var push = "pushleft"; var pull = "pushright"; }
    			} else { var push = ""; var pull = "";};
        		$(".zb-panel").each( function() {
        			if ( $(this).attr("id") == ""+panel+"" ) {
						if ($(this).hasClass("hide")) {
							if (device.OS=="Android" && device.v < 3) {
								window.scrollTo(0,0);
							};
							$(this).removeClass("hide").addClass("show");
							$(''+my_wrapper+'').removeClass(""+pull+"").addClass(""+push+"");
							$(".sbtab").each(function() {
								if ( $(this).attr("id") == plus) {
									$(this).removeClass("hide").addClass("show");
								} else {
									$(this).removeClass("show").addClass("hide");
								}
							});
						} else {
							if (device.OS=="Android" && device.v < 3) {
								window.scrollTo(0,0);
							};
							$(this).removeClass("show").addClass("hide");
							$(''+my_wrapper+'').removeClass(""+push+"");
							$(".sbtab").each(function() {
								$(this).removeClass("show").addClass("hide");
							});
						};
					} else { // Park other panels
						$(this).removeClass("show").addClass("hide");

						$(".sbtab").each(function() {
							if ( $(this).attr("id") != plus) {
							$(this).removeClass("show").addClass("hide");
							}
						});
					}
        		});
        	};      	
	// Convert to Panel functions //
	function comment2panel() {
			if (comment_button==true && comments_open=="1" && is_home!="1" && is_archive!="1") {
				if (altertheme_commentlist != "" || altertheme_commentform != "") {
					$("#"+comment_custom+"").addClass("zb-panel left hide");
				};
				// else if commenting is closed, discussion thread remains below article
				// because if it were in a panel readers would have no way to see them
				// since the ZappBar comment button would be disabled.
			};
	}
	function addl2panel() {		
			if ( woo_addl_button==true && is_product=="1") {
				if ( alter_woo_theme_woo_addl != "" ) {
					$("#tab-additional_information").addClass("zb-panel left hide");
					$("#tab-additional_information").append("<p class=\"zb-spacer\" style=\"margin-bottom:100px;\"></p>");
					$(".additional_information_tab").hide();
				}
			}	
	}
	function desc2panel() {
			if ( woo_desc_button==true && is_product=="1") {
				if ( alter_woo_theme_woo_desc != "" ) {
					$("#tab-description").addClass("zb-panel left hide");
					$("#tab-description").append("<p class=\"zb-spacer\" style=\"margin-bottom:100px;\"></p>");
					$(".description_tab").hide();
				}
			}
	}
	function review2panel() {
			if ( woo_review_button==true && is_product=="1") {
				if ( alter_woo_theme_woo_reviews != "" ) {
					$("#tab-reviews").addClass("zb-panel left hide");
					$("#tab-reviews").append("<p class=\"zb-spacer\" style=\"margin-bottom:100px;\"></p>");
					$(".reviews_tab").hide();
				}
			}
	};
	
	var zb_styles = ['','','',''];
	
	// Undo for not applied //
		function un_appify(full) {
			if (full!=null && $(".zappbar").css("display") !='none') {
				// adjust for admin bar
				$('html').removeClass("zb-admin-bar");
				$("#app_meta").attr("content","no");	// turn off app-capable
				$("#view_meta").attr("content","");		// clear app viewport
				if (document.getElementById("zb-customize").tagName.toLowerCase()=="style") {
					if ((applyto == 'force_mobile' || applyto == 'only_mobile_forced') && device.mobile == true) {
						// ignore
					} else {
						// remove content
						document.getElementById("zb-customize").innerHTML="";
					}
				} else {
					if ((applyto == 'force_mobile' || applyto == 'only_mobile_forced') && device.mobile == true) {
						// ignore
					} else { 
						// empty href
						$("#zb-customize").attr("href","");
					}
				}
				// empty zb-response-css link href
				if ((applyto == 'force_mobile' || applyto == 'only_mobile_forced') && device.mobile == true) {
					// ignore
				} else {
					$("#zb-response-css").attr("href","");
				}
				if (is_responsive=="1") {
					// remove site-tweaks
					$("#zb-site-tweaks-css").attr("href","");
				}
				$(".zappbar").hide();
				$("#zappbar_menu_left").hide();
				$("#zappbar_menu_right").hide();
				$("#zappbar_sidebar_left").hide();
				$("#zappbar_sidebar_right").hide();
				$("#zappbar_share_this").hide();
				$("#zappbar_sbtab_left").hide();
				$("#zappbar_sbtab_right").hide();
				$("#zappbar_splash").hide();
				$("#zappbar_notice").hide();
			}
				$("#"+comment_custom+"").removeClass("zb-panel left");
				if (woocommerce==true) {
					$("#tab-additional_information").removeClass("zb-panel left");
					$(".additional_information_tab").show();
					$("#tab-description").removeClass("zb-panel left");
					$(".description_tab").show();
					$("#tab-reviews").removeClass("zb-panel left");
					$(".reviews_tab").show();
					$(".zb-spacer").remove();
				}
		}
		function zb_appify() {
        	// adjust for admin bar
			if ($('body').hasClass('admin-bar')) {
				$('html').addClass('zb-admin-bar');
			}
			if ($(".zappbar").css("display")=='none') {
				$("#app_meta").attr("content","yes");	// turn off app-capable
				zb_styles[0] = $("#view_meta").attr("content",""+zb_styles[0]+"");
				$(".zappbar").show();
				$("#zappbar_menu_left").attr("style","");
				$("#zappbar_menu_right").attr("style","");
				$("#zappbar_sidebar_left").attr("style","");
				$("#zappbar_sidebar_right").attr("style","");
				$("#zappbar_share_this").attr("style","");
				$("#zappbar_sbtab_left").attr("style","");
				$("#zappbar_sbtab_right").attr("style","");
				$("#zappbar_splash").attr("style","");
				$("#zappbar_notice").attr("style",""); 
				if (document.getElementById("zb-customize").tagName.toLowerCase()=="style") {
					// restore content
					if (zb_styles[1].length>0) {
						document.getElementById("zb-customize").innerHTML=zb_styles[1];
					}
				} else {
					// restore link
					if (zb_styles[1].length>0) {
						$("#zb-customize").attr("href",""+zb_styles[1]+"");
					}
				}
				// restore link
				$("#zb-response-css").attr("href",""+zb_styles[2]+"");
				if (is_responsive!="0") {
					// restore site-tweaks
					$("#zb-site-tweaks-css").attr("href",""+zb_styles[3]+"");
				}
			}
		}
		
	// Initialize button actions //
        function zb_init() {
        	// store vars for remove/restore
			zb_styles[0] = $("#view_meta").attr("content");
			if (document.getElementById("zb-customize").tagName.toLowerCase()=="style") {
				// store style content in var
				zb_styles[1] = document.getElementById("zb-customize").innerHTML;
			} else {
				// store link href in var
				zb_styles[1] = $("#zb-customize").attr("href");
			}
			// store zb-response-css link href
			zb_styles[2] = $("#zb-response-css").attr("href");
			if (is_responsive=="1") {
				// remove site-tweaks
				zb_styles[3] = $("#zb-site-tweaks-css").attr("href");
			}

        	if (splash != '') {
        		if (splash_timer == '' || splash_timer == null) { splash_timer = 5000; };
        		var zb_cookie = $.cookie("zb_splash");
        		if (zb_cookie == null) {
        			$("#zappbar_splash").show();
        			var hide_splash = setTimeout(function(){$("#zappbar_splash").fadeOut(1000);},splash_timer);
        			$.cookie("zb_splash","true");
					$("#zappbar_splash").on("click", function(event) {
						clearTimeout(hide_splash);
						$(this).fadeOut(1000);
						if (splash_link != '') {
							var popup=window.open(splash_link,"_blank");
						}
					});
        		} else {
        			$("#zappbar_splash").hide();
        		};
        	} else {
        		$("#zappbar_splash").hide();
        	}

        	// window opener for social media buttons
			$("a.zb-social").click( function(event){
				event.preventDefault();
				var popup=window.open($(this).attr("href"),"_blank","height=450,width=700");
				if (window.focus) {popup.focus()}
			});
        	// Hide empty buttons
        	$(".dashicons-blank").parent().hide();
        	// click outside search box hides it
        	$(document).on("touchend mouseup",function (e){
					if (!$(".search").is(e.target) && $(".search").has(e.target).length === 0) {
						$("div.zappbar").find(".search").removeClass("in").addClass("out");
					};
					if (!$("#zappbar_notice").is(e.target) && $("#zappbar_notice").has(e.target).length === 0) {
						$("#zappbar_notice").removeClass("in").addClass("out");
					};
				});
			// sidebar tabs
					$("#zappbar_sbtab_left").on("click", function(e) {
						zappPanel("zappbar_sidebar_left",0,"zappbar_sbtab_left");

					});
					$("#zappbar_sbtab_right").on("click", function(e) {
						zappPanel("zappbar_sidebar_right",1,"zappbar_sbtab_right");

					});
			// Mode Switch
			$(".zb-switch").each( function() {
       			if ( $(this).attr("href") == "switch_mode") {
        			 $(this).on("click", function(e) {
        			 	if ($("#zb-response-css").attr("href") == "") {
        			 		zb_appify()
        			 		$(".zb-switch").each( function() {
        			 			$(this).html('<span class="sw_desktop">Switch to Desktop View<span>');
        			 		});
        			 	} else {
        			 		un_appify(1);
        			 		$(".zb-switch").each( function() {
        			 			$(this).html('<span class="sw_mobile">Switch to Mobile View</span>');
        			 		});
        			 	}
        			 });
        			 $(this).attr("href","javascript:void(0);");
        		};
        	});
			// Attach button actions
        	$(".button").each( function() {
				if ( $(this).attr("href") == "#appmenu_left" ) {
					$(this).on("click", function(e) {
						zappPanel("zappbar_menu_left",0);
					});
					$(this).attr("href","javascript:void(0);");	
				};
				if ( $(this).attr("href") == "#appmenu_right") {
					$(this).on("click", function(e) {
						zappPanel("zappbar_menu_right",1);
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "#share_this") {
					$(this).on("click", function(e) {
						zappPanel("zappbar_share_this",1);
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "#search_box" || 
					 $(this).attr("href") == "#search_left" ||
					 $(this).attr("href") == "#search_right" ||
					 $(this).attr("href") == "#woo_search" ||
					 $(this).attr("href") == "#woo_search_left" ||
					 $(this).attr("href") == "#woo_search_right"
					 ) {
					$(this).on("click mouseover", function(e) {
						$(this).find(".search").removeClass("out").addClass("in");
						$(this).find("input[type=\'search\']").focus();
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "#callme") {
					 $(this).on("click", function(e) {
					 	telnum_text = unescape(telnum);
					 	// if you need some other calling pattern either change the regex below or change it to telnum_data = telnum_text to pass through //
					 	telnum_data = telnum_text.replace(/[^\dx\+]/g, "");
						$("#zappbar_notice").html('<p class="zb-phone">Call: <a href="tel:'+telnum_data+'" target="_blank">'+telnum_text+'</a></p>');
						if ($("#zappbar_notice").hasClass("out")) {
							$("#zappbar_notice").removeClass("out").addClass("in");
						}
					 });
					 $(this).attr("href","javascript:void(0);");
				}
				if ( $(this).attr("href") == "#sidebar_left") {
					$(this).on("click", function(e) {
						zappPanel("zappbar_sidebar_left",0,"zappbar_sbtab_left");
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "#sidebar_right" ) {
					$(this).on("click", function(e) {
						zappPanel("zappbar_sidebar_right",1,"zappbar_sbtab_right");
					});
					$(this).attr("href","javascript:void(0);");
				};
				// non-paginated archive with archive buttons
				if ( $(this).attr("href") == "#first_page" ||
					 $(this).attr("href") == "#prev_page" ||
					 $(this).attr("href") == "#next_page" ||
					 $(this).attr("href") == "#last_page") {
					 $(this).attr("href","javascript:void(0);");
					 $(this).addClass("zb-disabled");
					 $(this).attr("title","There are no other pages");	 
				};
				if ( $(this).attr("href") == "#commentform") {
					comment_button = true;
					if (comments_open=="1" && is_home!="1" && is_archive!="1" && $("#"+comment_custom+"").length ) {
						if (altertheme_commentlist != "" || altertheme_commentform != "") {
							$(this).on("click", function(e) {
								zappPanel(""+comment_custom+"",0);
							});
							$(this).attr("href","javascript:void(0);");
						} else {
							$(this).attr("href","#"+comment_custom+"");
						}
					} else {
						$(this).addClass("zb-disabled");
						$(this).attr("title","You cannot comment on this page.");
						$(this).attr("href","javascript:void(0);");
					};
				};
				if (woocommerce==true) {
					if ($(this).attr("href") == "#woo_review") {
						woo_review_button = true;
						if (comments_open=="1" && is_product=="1" && $("#tab-reviews").length ) {
							if (alter_woo_theme_woo_reviews != "") {
								$(this).on("click", function(e) {
									zappPanel("tab-reviews",0);
								});
							$(this).attr("href","javascript:void(0);");
						} else {
							$(this).attr("href","#tab-reviews");
						};
					} else {
						$(this).addClass("zb-disabled");
						$(this).attr("title","You cannot review products on this page.");
						$(this).attr("href","javascript:void(0);");
					};
				};
				if ($(this).attr("href") == "#woo_desc") {
					woo_desc_button = true;
					if ( is_product=="1" && $("#tab-description").length ) {
						if ( alter_woo_theme_woo_desc != "") {
							$(this).on("click", function(e) {
								zappPanel("tab-description",0);
							});
							$(this).attr("href","javascript:void(0);");
						} else {
							$(this).attr("href","#tab-description");
						};
					} else {
						$(this).addClass("zb-disabled");
						$(this).attr("title","Sorry, no Product Description on this page.");
						$(this).attr("href","javascript:void(0);");
					};
				};
				if ($(this).attr("href") == "#woo_addl") {
					woo_addl_button = true;
					if ( is_product=="1" && $("#tab-additional_information").length ) {
						if ( alter_woo_theme_woo_addl != "" ) {
							$(this).on("click", function(e) {
								zappPanel("tab-additional_information",0);
							});
							$(this).attr("href","javascript:void(0);");
						} else {
							$(this).attr("href","#tab-additional_information");
						};
					} else {
						$(this).addClass("zb-disabled");
						$(this).attr("title","Sorry, no Product Description on this page.");
						$(this).attr("href","javascript:void(0);");
					};
				};
			};				
		});
		// Allow Reply-To links to also open the Comment Panel
		if (comments_open=="1" && is_home!="1" && is_archive!="1" && $("#"+comment_custom+"").length ) {
			// if it's not the whole comments section but is the form alter links...
			if (altertheme_commentlist == "" && altertheme_commentform != "") {
				$('a.comment-reply-link').each( function() {
					$(this).on("click", function(e) {
						zappPanel(""+comment_custom+"",0);
					});					
				});
			}
		}
		zbinit = true;
	}; // end of init
	
	// now run init //
	zb_check();
    // attach listener for resize //
	$( window ).on("resize orientationchange", function(e) {
		zb_check();
	}); 
	
	/* 	If bookmarked to the home screen on iOS this prevents links from opening in Safari
		and keeps you in App View --> tested up to iOS 8.2 <--
		Comment out line below if you want to ALWAYS open in Safari on iOS devices
	*/
	(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")

/*  The App Panels need positioning changed because the "fixed" positioning 
	is partly broken in Android 2.x and earlier.  It also adjusts the main
	page element (if needed) to position woocommerce tabs converted to panels.
	None of this is necessary for newer versions of Android.
*/

if (device.OS=="Android" && device.v < 3) {
		$('html').css({
			'position':'absolute',
			'height':'100%',
			'width':'100%',
			'overflow':'auto',
			'overflow-x':'hidden',
			'overflow-y':'scroll'
		});
		$('body').css({
			'position':'relative',
			'width':'100%',
			'height':'auto'	/* prevents inheriting window->html height */
		});

		// fixed panels do not scroll, change them to absolute
		$('.zb-panel').addClass('android2x');
		
		if (woocommerce==true) {
			if (alter_woo_theme_woo_reviews=='woo_reviews' ||
				alter_woo_theme_woo_desc=='woo_desc' ||
				alter_woo_theme_woo_addl=='woo_addl') {
				/* 	if woocommerce product tabs are converted to panels
					we need to adjust the main page element or they will
					not be positioned correctly, so let us fix that...
				*/
				$(''+my_wrapper+'').addClass('android2x_page');			

			};
		};		
	};  
});	