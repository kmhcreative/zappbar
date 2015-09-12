/* 	Front-end Functions for zAppBar Plugin
	This uses variables from an in-page script
	that imports settings values from PHP which
	must be in the code before this script.
*/

jQuery(document).ready(function($){	

	// Add Android 2.x detection //
	 var device = {};
	 if (navigator.userAgent.match(/Android/i)) {
		var ver = navigator.userAgent.match(/Android ./i);
		ver = ver[0].split(" ");
		ver = parseFloat(ver[1]);
		device.OS = "Android";
		device.Platform = "Android";
		device.v = ver;
	};
	
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
        		if ((showon == "tablets_hd" && w < 1441) ||
        			(showon == "tablets" && w < 1025) || 
        			(showon == "phones" && w < 737) || 
        			(showon == "desktops" && w < 1920) || (showon == "desktops_hd") 
        		) { 
        			// we only need to attach button actions on initial load
        			if (zbinit==false) { 
        				zb_init();
        			}
					if (is_responsive=="no") {
        		 			document.getElementById('zb-site-tweaks-css').href=""+zb_base+"css/site_tweaks.css";
        		 	}
        			// see if any of these need to convert to panels
        			comment2panel();
        			review2panel();
        			addl2panel();
        			desc2panel();
        			$(".zb-switch").each( function() {
        				$(this).show();
        			});
        		 } else {
        		 	if (is_responsive == "no") {
        		 	document.getElementById('zb-site-tweaks-css').href="";
        		 	}
        			un_appify();
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
							for (var x=0;x<wrapper.length;x++) {
								$("#"+wrapper[x]+"").removeClass(""+pull+"").addClass(""+push+"");
							};
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
							for (var x=0;x<wrapper.length;x++) {
								$("#"+wrapper[x]+"").removeClass(""+push+"");
							};
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
				if (altertheme_commentform != "") {
					$("#"+comment_custom+"").addClass("zb-panel left hide");
				};
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
			if (full!=null) {
				$("#app_meta").attr("content","no");	// turn off app-capable
				zb_styles[0] = $("#view_meta").attr("content");
				$("#view_meta").attr("content","");		// clear app viewport
				if (document.getElementById("zb-customize").tagName.toLowerCase()=="style") {
					// store style content in var
					zb_styles[1] = document.getElementById("zb-customize").innerHTML;
					// remove content
					document.getElementById("zb-customize").innerHTML="";
				} else {
					// store link href in var
					zb_styles[1] = $("#zb-customize").attr("href");
					// empty href
					$("#zb-customize").attr("href","");
				}
				// empty zb-response-css link href
				zb_styles[2] = $("#zb-response-css").attr("href");
				$("#zb-response-css").attr("href","");
				if (is_responsive=="no") {
					// remove site-tweaks
					zb_styles[3] = $("#zb-site-tweaks-css").attr("href");
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
				document.getElementById("zb-customize").innerHTML=zb_styles[1];
			} else {
				// restore link
				$("#zb-customize").attr("href",""+zb_styles[1]+"");
			}
			// restore link
			$("#zb-response-css").attr("href",""+zb_styles[2]+"");
			if (is_responsive=="no") {
				// restore site-tweaks
				$("#zb-site-tweaks-css").attr("href",""+zb_styles[3]+"");
			}
			zb_check();
		}
		
	// Initialize button actions //
        function zb_init() {
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
        	var wrapper = ["page","page-wide","wrapper"];
        	if (page_custom != "") {
        		wrapper.push(page_custom);
			};	        	

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
				if ( $(this).attr("href") == "appmenu_left" ) {
					$(this).on("click", function(e) {
						zappPanel("zappbar_menu_left",0);
					});
					$(this).attr("href","javascript:void(0);");	
				};
				if ( $(this).attr("href") == "appmenu_right") {
					$(this).on("click", function(e) {
						zappPanel("zappbar_menu_right",1);
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "share_this") {
					$(this).on("click", function(e) {
						zappPanel("zappbar_share_this",1);
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "search_box" || 
					 $(this).attr("href") == "search_left" ||
					 $(this).attr("href") == "search_right" ||
					 $(this).attr("href") == "woo_search" ||
					 $(this).attr("href") == "woo_search_left" ||
					 $(this).attr("href") == "woo_search_right"
					 ) {
					$(this).on("click mouseover", function(e) {
						$(this).find(".search").removeClass("out").addClass("in");
						$(this).find("input[type=\'search\']").focus();
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "callme") {
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
				if ( $(this).attr("href") == "sidebar_left") {
					$(this).on("click", function(e) {
						zappPanel("zappbar_sidebar_left",0,"zappbar_sbtab_left");
					});
					$(this).attr("href","javascript:void(0);");
				};
				if ( $(this).attr("href") == "sidebar_right" ) {
					$(this).on("click", function(e) {
						zappPanel("zappbar_sidebar_right",1,"zappbar_sbtab_right");
					});
					$(this).attr("href","javascript:void(0);");
				};
				// non-paginated archive with archive buttons
				if ( $(this).attr("href") == "first_page" ||
					 $(this).attr("href") == "prev_page" ||
					 $(this).attr("href") == "next_page" ||
					 $(this).attr("href") == "last_page") {
					 $(this).attr("href","javascript:void(0);");
					 $(this).addClass("zb-disabled");
					 $(this).attr("title","There are no other pages");	 
				};
				if ( $(this).attr("href") == "commentform") {
					comment_button = true;
					if (comments_open=="1" && is_home!="1" && is_archive!="1") {
						if (altertheme_commentform != "") {
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
					if ($(this).attr("href") == "woo_review") {
						woo_review_button = true;
						if (comments_open=="1" && is_product=="1") {
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
				if ($(this).attr("href") == "woo_desc") {
					woo_desc_button = true;
					if ( is_product=="1") {
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
				if ($(this).attr("href") == "woo_addl") {
					woo_addl_button = true;
					if ( is_product=="1") {
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
		zbinit = true;
	}; // end of init
	
	// now run init //
	zb_check();
    // attach listener for resize //
	$( window ).on("resize orientationchange", function(e) {
		zb_check();
	}); 
	
	/* 	If bookmarked to the home screen on iOS this prevents links from opening in Safari
		and keeps you in App View --> tested up to iOS 7.1 <--
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
				for (var x=0;x<wrapper.length;x++) {
					$("#"+wrapper[x]+"").addClass('android2x_page');
				};			

			};
		};		
	};

  
});	