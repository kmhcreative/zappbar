if (comment_custom != '') {
	var my_comment = comment_custom;
} else {
	if (altertheme_commentlist != '') {
		var my_comment = '#comments';
	} else {
		var my_comment = '#respond';
	}
}

// Hide Header?
if (altertheme_header == '1') {
	var hide_header = my_header+' {\n'+
	'	display: none !important;\n'+
	'}\n';
} else {
	var hide_header = '';
}
// Hide Site Nav?
if (altertheme_nav == '1') {
	var hide_nav = my_nav+' {\n'+
	'	display: none !important;\n'+
	'}\n';
} else {
	var hide_nav = '';
}
// Hide or Move Sidebars?
if (is_responsive != "0") {	// site is not responsive already 
	// set up pushover animation
	var retrofit = my_wrapper+' {\n'+
	'	position: relative;\n'+
	'	width: auto;\n'+
	'	margin: 0 auto;\n'+
	'	padding-bottom: 50px;\n'+
	'	-webkit-transition-duration: .5s;\n'+
	'	-moz-transition-duration: .5s;\n'+
	'	-ms-transition-duration: .5s;\n'+
	'	transition-duration: .5s;\n'+
	'}\n'+
	'	'+my_wrapper+'.pushleft { left: 320px; }\n'+
	'	'+my_wrapper+'.pushright { left: -320px;}\n'+
	'	'+my_wrapper+', '+my_wrapper+' '+my_header+', '+my_wrapper+' '+my_nav+', #main\n'+
	'	'+my_wrapper+' #subcontent-wrapper, #'+my_wrapper+' #footer,\n'+
	'	'+my_wrapper+' #comic-header, #'+my_wrapper+' #comic-foot {\n'+
	'		max-width: 100% !important; /* makes sure nothing is ever wider than its container */\n'+
	'	}\n'+
	'		'+my_wrapper+' '+my_header+' h1 {\n'+
	'			max-width: 100%;\n'+
	'			-webkit-background-size: contain;\n'+
	'			-moz-background-size: contain;\n'+
	'			-ms-background-size: contain;\n'+
	'			background-size: contain;\n'+
	'		}\n';
	if (altertheme_nav != '1') { // site nav is not being hidden, so fix wrapping
	retrofit +='	'+my_nav+', #menu, .menu-container, #comic-wrap, #subcontent-wrapper, #footer, #footer-sidebar-wrapper {\n'+
	'		max-width: 100%;\n'+
	'		height: auto;\n'+
	'		margin-left: auto; margin-right: auto;\n'+
	'	}\n';
	}
	if (altertheme_sidebars == '1' || altertheme_sidebars == '2') {	// hide the sidebars
		var fix_sidebars = my_sidebars+', #sidebar-left-of-comic {\n'+
		'	display: none;\n'+
		'}\n'+
		'#content, #content-column {\n'+
		'	float: none;\n'+
		'	width: auto;\n'+
		'	max-width: 100%\n'+
		'	min-height: 0px\n'+
		'	clear: both;\n'+
		'}\n'+
		'.site-content { width: auto; }\n';
	} else {	// move the sidebars
		var fix_sidebars = ''+
		my_sidebars+', #sidebar-left-of-comic,\n'+
		'#content, #content-column {\n'+
		'	float: none;\n'+
    	'	width: auto;\n'+
    	'	max-width: 100%;\n'+
    	'	min-height: 0px;\n'+
    	'	min-width: 0;\n'+
    	'	clear: both;\n'+
    	'	display: block;\n'+
    	'}\n'+
    	'.site-content { width: auto; }\n';
	}
} else {	// theme is already responsive, assume theme creator knows better than zappbar
	// set up for pushover animation
	var retrofit = 	var retrofit = my_wrapper+' {\n'+
	'	position: relative;\n'+
	'	width: auto;\n'+
	'	margin: 0 auto;\n'+
	'	padding-bottom: 50px;\n'+
	'	-webkit-transition-duration: .5s;\n'+
	'	-moz-transition-duration: .5s;\n'+
	'	-ms-transition-duration: .5s;\n'+
	'	transition-duration: .5s;\n'+
	'}\n'+
	'	'+my_wrapper+'.pushleft { left: 320px; }\n'+
	'	'+my_wrapper+'.pushright { left: -320px;}\n';
	var fix_sidebars = '';
}
// Move Comment Form?
if (altertheme_commentlist == '1' || altertheme_commentform == '1') {
	var commentform = ''+
	my_comment+'.zb-panel {\n'+
	'	margin-top: 0;\n'+
	'	padding: 60px 0 0 0 !important;\n'+
	'	overflow: auto;\n'+
	'	overflow-x: scroll;\n'+
	'	-webkit-overflow-scrolling: touch;\n'+
	'}\n'+
	'	'+my_comment+'.zb-panel form {\n'+
	'		margin-bottom: 100px;\n'+
	'	}\n'+
	'	'+my_comment+'.zb-panel textarea,\n'+
	'	'+my_comment+'.zb-panel input[type="text"] {\n'+
	'		max-width: 100%;\n'+
	'		resize: vertical;\n'+
	'	}\n
	'	.zb-admin-bar '+my_comment+'.zb-panel {\n'+
	'		padding: 90px 0 !important;\n'+
	'	}\n';
	if (altertheme_commentlist == '1') {
		commentform += ''+
	'	'+my_comment+'.zb-panel .comments-title {\n'+
	'		padding-top: 50px !important;\n'+
	'	}\n';
	} else {
		commentform += ''+
	'	'+my_comment+'.zb-panel .comment-reply-title {\n'+
	'		padding-top: 50px !important;\n'+
	'	}\n';
	}
} else {
	var commentform = '';
}
// If this is woocommerce is anything moved to panels?
if (woocommerce == '1') {
	if (alter_woo_theme_woo_reviews == "1") {
		var commentform = ''+
   		'	#tab-reviews.zb-panel {\n'+
   		'		margin-top: 0;\n'+
   		'		padding: 60px 0 0 0 !important;\n'+
   		'		overflow: auto;\n'+
   		'		overflow-x: scroll;\n'+
   		'		-webkit-overflow-scrolling: touch;\n'+
   		'	}\n'+
   		'		#tab-reviews.zb-panel form {\n'+
   		'			margin-bottom: 100px;\n'+
   		'		}\n';
	}
	if (alter_woo_theme_woo_desc == "1") {
		var commentform = ''+
  		'	#tab-description.zb-panel {\n'+
   		'		margin-top: 0;\n'+
   		'		padding: 60px 0 0 0 !important;\n'+
   		'		overflow: auto;\n'+
   		'		overflow-x: scroll;\n'+
   		'		-webkit-overflow-scrolling: touch;\n'+
   		'	}\n'+
   		'		#tab-description.zb-panel form {\n'+
   		'			margin-bottom: 100px;\n'+
   		'		}\n';
	}
	if (alter_woo_theme_woo_addl == "1") {
		var commentform = ''+
  		'	#tab-additional_information.zb-panel {\n'+
   		'		margin-top: 0;\n'+
   		'		padding: 60px 0 0 0 !important;\n'+
   		'		overflow: auto;\n'+
   		'		overflow-x: scroll;\n'+
   		'		-webkit-overflow-scrolling: touch;\n'+
   		'	}\n'+
   		'		#tab-additional_information.zb-panel form {\n'+
   		'			margin-bottom: 100px;\n'+
   		'		}	\n';
	}
}







if (search_button == "1") {
	var zb_style .= ''+
	'.zappbar .search input[type="submit"] {\n'+
	'	display: none;\n'+
	'}\n';
}
