<?php
$custom_page[0] = '';
$custom_page[1] = '';
$custom_header = '';
$custom_nav = '';
$custom_comment = '';
$custom_woo_comment = '';
$woo_adjust = '';
$woo_adjust_phone = '';
$custom_sidebars = '';
$always_hide = '';
if (isset($zb_site['altertheme'])) {
	$zb_site_alter = $zb_site['altertheme'];
	if (!isset($zb_site_alter['header'])) { $zb_site_alter['header'] = ''; };
	if (!isset($zb_site_alter['sitenav'])){ $zb_site_alter['sitenav']= ''; };
	if (!isset($zb_site_alter['commentform'])){$zb_site_alter['commentform']='';};
	if (!isset($zb_site_alter['push'])){$zb_site_alter['push']='';};
} else {
	$zb_site_alter = array('header' => '', 'sitenav' => '', 'commentform' => '', 'push' => '');
}
if ($zb_site['header_custom'] != '') { 	$custom_header = ', '.$zb_site['header_custom'];}
if ($zb_site['nav_custom'] != '') { 	$custom_nav = ', '.$zb_site['nav_custom'];}

    if ($zb_site_alter['header'] != '') {
    	if ($custom_header != '') {
    	$hide_header = '
    		'.$custom_header.' {
    			display: none !important;
    		}
    	';    	
    	} else {
    	$hide_header = '
    		#masthead, #header {
    			display: none !important;
    		}
    	';
    	}
    } else {
    	$hide_header = '';
    }
    if ($zb_site_alter['sitenav'] != '') {
    	if ($custom_nav != '') {
    	$hide_nav = '
    		'.$custom_nav.' {
    			display: none !important;
    		}
    	';    	
    	} else {
    	$hide_nav = '
    		#site-navigation, #menubar-wrapper'.$custom_nav.' {
    			display: none !important;
    		}
    	';
    	}
    } else {
    	$hide_nav = '';
    }
if ($zb_site['page_custom'] != '') {
	$custom_page[0] = '
	#'.$zb_site['page_custom'].' {
		position: relative;
		width: auto;
		max-width: 100%;
		margin: 0 auto;
		padding-bottom: 50px;
		-webkit-transition-duration: .5s;
		-moz-transition-duration: .5s;
		-ms-transition-duration: .5s;
		transition-duration: .5s;
	}
		#'.$zb_site['page_custom'].'.pushleft {
			left: 320px;
		}
		#'.$zb_site['page_custom'].'.pushright {
			left: -320px;
		}
		';
		if ($custom_header != '') { $add_header = ', #'.$zb_['page_custom'].' '.$custom_header;} else { $add_header = '';}
		if ($custom_nav != '') { $add_nav = ',#'.$zb_['page_custom'].' '.$custom_nav;} else { $add_nav = '';}
	$custom_page[0] .='
		#'.$zb_site['page_custom'].' #header, #'.$zb_site['page_custom'].' #menubar-wrapper,
		#'.$zb_site['page_custom'].' #subcontent-wrapper, #'.$zb_site['page_custom'].' #footer,
		#'.$zb_site['page_custom'].' #comic-header, #'.$zb_site['page_custom'].' #comic-foot'.$add_header.$add_nav.' {
			max-width: 100%;
		}
	';
	$custom_page[1] = '
	#'.$zb_site['page_custom'].'.pushleft,
	#'.$zb_site['page_custom'].'.pushright {
		left: 0px;
	}	
	';
}
if ($zb_site['sidebars_custom'] != '') {
	$csb = explode(',',$zb_site['sidebars_custom']);
	$custom_sidebars = '';
	$c = 0;
	foreach ($csb as $cs) {
		$custom_sidebars .= $cs;
		if ($c < (count($csb)-1) ) {
		$custom_sidebars .= ', ';
		}
		$c++;
	}
}	
if ($zb_site['responsive']!='0') {	// site is not responsive
    if ($zb_site['sidebars'] == '1' || $zb_site['sidebars'] == '2' ) {
    	if ($custom_sidebars != '') {
     	$sidebars = '
    		'.$custom_sidebars.' {
    			display: none;
    		}
    		#content, #content-column {
 				float: none;
    			width: auto;
    			max-width: 100%;
    			min-height: 0px;
    			clear: both;   			
    		}
    		.site-content { width: auto; }
    		';   	
    	} else {
    	$sidebars = '
    		#sidebar-left, #sidebar-right, #sidebar-left-of-comic {
    			display: none;
    		}
    		#content, #content-column {
 				float: none;
    			width: auto;
    			max-width: 100%;
    			min-height: 0px;  
    			clear: both; 			
    		}
    		.site-content { width: auto; }
    		';
    	}
    } else {
    	if ($custom_sidebars != '') {
    	$sidebars = '
    		#content, #content-column, '.$custom_sidebars.' {
				float: none;
    			width: auto;
    			max-width: 100%;
    			min-height: 0px;
    			clear: both;
    		}
    		.site-content { width: auto; }
    		';
    	} else {
    	$sidebars = '
    		#sidebar-left, #sidebar-right, #sidebar-left-of-comic,
    		#content, #content-column {
				float: none;
    			width: auto;
    			max-width: 100%;
    			min-height: 0px;
    			min-width: 0;
    			clear: both;
    			display: block;
    		}
    		.site-content { width: auto; }
    		';
    	}
    };
} else {
	$sidebars = '';
}
if ($zb_site['comment_custom'] != '') {
	$custom_comment = $zb_site['comment_custom'];
};
    if ($zb_site_alter['commentform'] != '') {
    	if ($custom_comment != '') {
   		$commentform = '
   			'.$custom_comment.'.zb-panel {
   				margin-top: 0;
   				padding: 60px 0 0 0 !important;
   				overflow: auto;
   				overflow-x: scroll;
   				-webkit-overflow-scrolling: touch;
   			}
   				'.$custom_comment.'.zb-panel form {
   					margin-bottom: 100px;
   				}
   				'.$custom_comment.'.zb-panel textarea,
   				'.$custom_comment.'.zb-panel input[type="text"] {
   					max-width: 100%;
   					resize: vertical;
   				}
    	';   	
    	} else {
   		$commentform = '
   			#respond.zb-panel {
   				margin-top: 0;
   				padding: 60px 0 0 0 !important;
   				overflow: auto;
   				overflow-x: scroll;
   				-webkit-overflow-scrolling: touch;
   			}
   				#respond.zb-panel form {
   					margin-bottom: 100px;
   				}
   				#respond.zb-panel textarea,
   				#respond.zb-panel input[type="text"] {
   					max-width: 100%;
   					resize: vertical;
   				}
    	';
    	}
    } else {
    	$commentform = '';
    }
if (class_exists( 'woocommerce' ) ) {
	$zb_site_alterwoo = $zb_site['alter_woo_theme'];
    if ($zb_site_alterwoo['woo_reviews'] != '') {
   		$commentform .= '
   			#tab-reviews.zb-panel {
   				margin-top: 0;
   				padding: 60px 0 0 0 !important;
   				overflow: auto;
   				overflow-x: scroll;
   				-webkit-overflow-scrolling: touch;
   			}
   				#tab-reviews.zb-panel form {
   					margin-bottom: 100px;
   				}
    	';
    };
    if ($zb_site_alterwoo['woo_desc'] != '') {
   		$commentform .= '
   			#tab-description.zb-panel {
   				margin-top: 0;
   				padding: 60px 0 0 0 !important;
   				overflow: auto;
   				overflow-x: scroll;
   				-webkit-overflow-scrolling: touch;
   			}
   				#tab-description.zb-panel form {
   					margin-bottom: 100px;
   				}
    	';
    };
    if ($zb_site_alterwoo['woo_addl'] != '') {
   		$commentform .= '
   			#tab-additional_information.zb-panel {
   				margin-top: 0;
   				padding: 60px 0 0 0 !important;
   				overflow: auto;
   				overflow-x: scroll;
   				-webkit-overflow-scrolling: touch;
   			}
   				#tab-additional_information.zb-panel form {
   					margin-bottom: 100px;
   				}
    	';
    };
};        
	if ($zb_layout['search_button'] == 'on') {
		$zb_style .= '
			.zappbar .search input[type="submit"] {
				display: none;
			}	
		';
	}

    if ($panels['panel_tabs'] == 'yes' ) {
    	$panel_tabs = '
		.sbtab {
			background: #eee;
			color: #333;
			-webkit-transition-duration: .5s;
			-moz-transtion-duration: .5s;
			-ms-transition-duration: .5s;
			transition-duration: .5s;
		}
			.sbtab span {
				height: 100%;
				width: 100%;
				display: block;
			}
				.sbtab span:hover, .sbtab span:focus {
					background: #ccc;
				}
			.sbtab span:before {
				font-family: "Genericons";
				content: "\f436";
				font-size:28px;
				line-height:50px;
				margin:0;
				padding:0;
			} 
    	#zappbar_sbtab_left {
    		position: fixed;
    		top: 50%;
    		display: block;
    		margin-top:-15px;
			-webkit-border-radius: 0 5px 5px 0;
			-moz-border-radius: 0 5px 5px 0;
			-ms-border-radius: 0 5px 5px 0;
			border-radius: 0 5px 5px 0;
    	}	
    	#zappbar_sbtab_right {
    		position: fixed;
    		top: 50%;
    		display: block;
    		margin-top:-15px;
			-webkit-border-radius: 5px 0 0 5px;
			-moz-border-radius: 5px 0 0 5px;
			-ms-border-radius: 5px 0 0 5px;
			border-radius: 5px 0 0 5px;
    	}
    		#zappbar_sbtab_left.hide {
    			left: 0px;
    			margin-left:-5px;
    		}
    		#zappbar_sbtab_left.show {
    			left: 320px;
    			z-index: 10;
    		}
    		#zappbar_sbtab_right.hide {
    			right: 0px;
    			margin-right: -5px;
    		}
    		#zappbar_sbtab_right.show {
    			right: 320px;
    			z-index: 10;
    		}
    	';
    	$panel_tabs_phone = '
    		#zappbar_sbtab_left.hide {
    			left: 0%;
    			margin-left: -5px;
    		}
    		#zappbar_sbtab_left.show {
    			left: 100%;
    			z-index: 10;
    			margin-left: -35px;
    			margin-top: -75px;
    		}
    		#zappbar_sbtab_right.hide {
    			right: 0%;
    			margin-right: -5px;
    		}
    		#zappbar_sbtab_right.show {
    			right: 100%;
    			z-index: 10;
    			margin-top: -75px;
    			margin-right: -35px;
    		}
    		#zappbar_sidebar_left.show {
    			margin-left: -35px;
    		}
    			#zappbar_sidebar_left.show .marginbox {
    				margin-left: 45px;
    			}
    		#zappbar_sidebar_right.show {
    			margin-right: -35px;
    		}
    			#zappbar_sidebar_right.show .marginbox {
    				margin-right: 45px;
    			}   		
    	';
    } else {
    	$panel_tabs = 'div.sbtab { display: none; }
    	';
    	$panel_tabs_phone = 'div.sbtab { display: none; }
    	';
    }
	if ( isset($zb_site['comic_nav']) && $zb_site['comic_nav']=='on' ) {
    	$comic_nav = 'div#comic-foot { display: none; }';
    } else {
    	$comic_nav = '';
    }
	if (function_exists('ceo_pluginfo')) {	// adjust containers for ComicPress 4.2.x
		$comic_adj = 'div.comic-table, #subcontent-wrapper { display: block; }';
	} else {
		$comic_adj = '';
	}
    if ($zb_site['splash_screen'] != '') {
    	if ($panel_bg!='') { $bg_color = $panel_bg; } else { $bg_color = "#ffffff";}
    	if ($panel_font_color!='') {$fcolor = $panel_font_color; } else { $fcolor = "#333333";}
    	$splash = '
    		div#zappbar_splash {
    			position: fixed;
    			top: 0; left: 0;
    			height: 100%;
    			width: 100%;
    			z-index: 999999;
    			display: none;
    			background: '.$bg_color.' url(\''.$zb_site['splash_screen'].'\') center center no-repeat;
    			-webkit-background-size: '.$zb_site['splash_size'].';
    			-moz-background-size: '.$zb_site['splash_size'].';
    			-ms-background-size: '.$zb_site['splash_size'].';
    			background-size: '.$zb_site['splash_size'].';
    		}
    			div#zappbar_splash span::before {
    				position: absolute;
    				top: 0; right: 0;
    				font-size: 12px;
    				line-height: 24px;
    				text-align: right;
    				color: '.$fcolor.';
					margin-right: 10px;
    				content: "Skip to site >>";
    				text-decoration: underline;
    			}
    		';
    	if ($zb_site['splash_link']!='') {
    	$splash .= '
    		div#zappbar_splash span::after {
    			position: absolute;
    			bottom: 0; left: 0;
    			display: block;
    			width: 100%;
    			font-size: 12px;
    			line-height: 24px;
    			color: '.$fcolor.';
    			text-align: center;
    			content: "Please Click Above to Visit Our Sponsor";
    		}
    		';
    	};
    } else { $splash = ''; }
    if ($zb_site['other_elements'] != '') {
    	$always_hide = '
    		'.$zb_site['other_elements'].' {
    			display: none;
    		}
    		';
    }
    
    // 980 is a break-point because so many WP themes use it as the page width //
    // hd_desktops applies to all screen sizes so we don't need to define break-points //
    if ($zb_site['showon'] == 'desktops') {
    	$screen1 = '1919';  $screen2 = '980';		$screen3 = '1280';
    } else if ($zb_site['showon'] == 'tablets_hd') {
    	$screen1 = '1440';	$screen2 = '980';		$screen3 = '800';
    } else if ($zb_site['showon'] == 'tablets') {
    	$screen1 = '1280';	$screen2 = '980';		$screen3 = '736';
    } else {
    	$screen1 = '1024';	$screen2 = '980';		$screen3 = '736';
    };
    
    if ($zb_site['showon'] == 'desktops_hd' || $zb_site['applyto'] == 'force_mobile' || $zb_site['applyto'] == 'only_mobile_forced') {
    	$zb_style .= '
    	/* desktops_hd / force_mobile / only_mobile_forced */
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
    		'.$commentform.'
    		'.$panel_tabs.'
    		'.$custom_colors.'
    		'.$comic_nav.'
    		'.$comic_adj.'';
    	if ($zb_site['sidebars']==2) {
    	$zb_style .= ''.$sidebars.'
    		.archive div.comicthumbwrap {
				width: 33%;
			}
    	';
    		if (function_exists('ceo_pluginfo')) {
				$ceo_options = get_option('comiceasel-config');
				if ($ceo_options['thumbnail_size_for_archive'] == 'thumbnail' || $ceo_options['thumbnail_size_for_archive'] == 'medium') {
					$zb_style .= '.archive div.comic.uentry {
						float: left;
						width: 33%;
					}
					';
				} else if ( $ceo_options['thumbnail_size_for_archive'] == 'large')  {
					$zb_style .= '.archive div.comic.uentry {
						float: left;
						width: 45%;
					}
					';
				} else {};
			};
    	}
    	$zb_style .= '
    	@media screen and (min-width: '.$screen2.'px) and (max-width: '.$screen1.'px) {
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}  	
    	}
    	@media screen and (min-width: '.$screen3.'px) and (max-width: '.($screen2-1).'px) {
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
    		'.$sidebars.'
    	}
    	@media screen and (max-width: '.($screen3-1).'px) {
    		'.$splash.'
    		'.$custom_page[1].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
			'.$commentform.'
    		'.$sidebars.'
    		'.$panel_tabs.'
    		'.$panel_tabs_phone.'
    		'.$custom_colors.'
    	}
    	@media screen and (max-width: 479px) {
    		.zappbar a.searchbox.left span.search form, 
    		.zappbar a.searchbox.right span.search form {
    			float: none;
    			margin: 0 auto;
    		}
    	}';
    } elseif ( $zb_site['showon'] == 'desktops' || $zb_site['showon'] == 'tablets' || $zb_site['showon'] == 'idevices' || $zb_site['showon'] == 'tablets_hd' ) {
    	$zb_style .= '
    	.zappbar, .zb-panel, .sbtab { 
    		display: none;
    	}
    	@media screen and (min-width: '.($screen1+1).'px) {
    		/* desktops/tablets/idevices/tablets_hd */
 			#page.push, #page-wide.push { left: 0px; }
 			'.$custom_page[1].'
			.zb-panel.show { left: -320px; }
    		#respond {
    			position: relative;
    			height: auto;
    			width: auto;
    			overflow: visible;
    			border: none;
    			background: none;
    			padding: 0;
    			left: 0px;
    		}
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li, 
			.woocommerce div.product .woocommerce-tabs ul.tabs li, 
			.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li, 
			.woocommerce-page div.product .woocommerce-tabs ul.tabs li {
    			display: inline-block;
    		}
    		#tab-reviews,
    		#tab-description,
    		#tab-additional_information {
    			position: relative !important;
    			left: auto;
    			top: auto;
    			background-color: inherit !important;
    			border: inherit !important;
    			color: inherit !important;
    		}
    			#tab-description { display: block; }
    			#tab-reviews { display: none; }
    			#tab-additional_information { display:none;}

    	}
    	@media screen and (min-width: '.$screen2.'px) and (max-width: '.$screen1.'px) {
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
    		'.$commentform.'
    		'.$panel_tabs.'
    		'.$custom_colors.'
    		'.$comic_nav.'
    		'.$comic_adj.'';
    	if ($zb_site['sidebars']==2) {
    	$zb_style .= ''.$sidebars.'
    		.archive div.comicthumbwrap {
				width: 33%;
			}
    	';
    		if (function_exists('ceo_pluginfo')) {
				$ceo_options = get_option('comiceasel-config');
				if ($ceo_options['thumbnail_size_for_archive'] == 'thumbnail' || $ceo_options['thumbnail_size_for_archive'] == 'medium') {
					$zb_style .= '.archive div.comic.uentry {
						float: left;
						width: 33%;
					}
					';
				} else if ( $ceo_options['thumbnail_size_for_archive'] == 'large') {
					$zb_style .= '.archive div.comic.uentry {
						float: left;
						width: 45%;
					}
					';
				} else {};
			};
    	}
    	$zb_style .= '}    	
    	@media screen and (min-width: '.$screen3.'px) and (max-width: '.($screen2-1).'px) {';
    	$zb_style .='
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
    		'.$commentform.'
    		'.$sidebars.'
    		'.$panel_tabs.'
    		'.$custom_colors.'
    		'.$comic_nav.'
    		'.$comic_adj.'
    	}
    	@media screen and (max-width: '.($screen3-1).'px) {
    		'.$splash.'
    		'.$custom_page[1].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
			'.$commentform.'
    		'.$sidebars.'
    		'.$panel_tabs.'
    		'.$panel_tabs_phone.'
    		'.$custom_colors.'
    		'.$comic_nav.'
    		'.$comic_adj.'
    	}
    	@media screen and (max-width: 479px) {
    		.zappbar a.searchbox.left span.search form, 
    		.zappbar a.searchbox.right span.search form {
    			float: none;
    			margin: 0 auto;
    		}
    	}';
		if (function_exists('ceo_pluginfo')) {
			$zb_style .= 'div.comic-table, #subcontent-wrapper { display: block; }';
		}
    } elseif ( $zb_site['showon'] == 'phones' ) {
   	$zb_style .= '
    	.zappbar, .zb-panel, .sbtab { 
    		display: none;
    	}
    	@media screen and (min-width: '.($screen3+1).'px) {
    		/* phones */
 			#page.push, #page-wide.push { left: 0px; }
 			'.$custom_page[1].'
			.zb-panel.show { left: -320px; }
    		#respond {
    			position: relative;
    			height: auto;
    			width: auto;
    			overflow: visible;
    			border: none;
    			background: none;
    			padding: 0;
    			left: 0px;
    		}
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li, 
			.woocommerce div.product .woocommerce-tabs ul.tabs li, 
			.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li, 
			.woocommerce-page div.product .woocommerce-tabs ul.tabs li {
    			display: inline-block;
    		}
    		#tab-reviews,
    		#tab-description,
    		#tab-additional_information {
    			position: relative !important;
    			left: auto;
    			top: auto;
    			background-color: inherit !important;
    			border: inherit !important;
    			color: inherit !important;
    		}
    			#tab-description { display: block; }
    			#tab-reviews { display: none; }
    			#tab-additional_information { display:none;}
    	}
    	@media screen and (max-width: '.$screen3.'px) {
    		'.$splash.'
    		'.$custom_page[1].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
			'.$commentform.'
    		'.$sidebars.'
    		'.$panel_tabs.'
    		'.$panel_tabs_phone.'
    		'.$custom_colors.'
    		'.$comic_nav.'
    		'.$comic_adj.'
    	}
    	@media screen and (max-width: 479px) {
    		.zappbar a.searchbox.left span.search form, 
    		.zappbar a.searchbox.right span.search form {
    			float: none;
    			margin: 0 auto;
    		}
    	}';
?>