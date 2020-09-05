<?php
/*	DYNAMIC STYLESHEET for ZappBar
	or it loads your custom stylesheet
*/
$zb_site = get_option('zappbar_site');
$bar_colors = get_option('zappbar_colors');    
if ($bar_colors['color_src'] == 'custom' && $bar_colors['custom_styles'] != '') {
    echo '<link rel="stylesheet" type="text/css" href="'.$bar_colors['custom_styles'].'" id="zb-customize" />';
    return;
} else {
    $panels = get_option('zappbar_panels');
    $zb_layout = get_option('zappbar_layout');
	// now build it //
    $zb_style = '<style type="text/css" id="zb-customize">';
	$custom_colors = '';

    function color_check($colors,$alpha) {
    	$opacity = array('1','1.0');
    	if ($colors != '') {
			if (!in_array($alpha,$opacity)) {
        		$color = 'rgba('.hex2rgb($colors).','.$alpha.')';
        	} else { 
        		$color = $colors; 
        	}			    	
    	} else { $color = ''; }
    	return $color;
    };
    function border_check($width,$style,$colors) {
    	$border = '';
		if ($width != '') {
        	$width = explode(',',$width);
        	if ($style != '') { $style = explode(',',$style); } else { $style = array('solid');}
        	if ($colors != '') { $color = $colors; } else { $color = "#000000";}
        	$border .= $width[0].' '.$style[0].' '.$color;
        }
   		return $border;    
    };
 
 	$bar_bg 		 = color_check($bar_colors['bar_bg'],$bar_colors['bar_bg_opacity']);
 	$button_bg 		 = color_check($bar_colors['button_bg'],$bar_colors['button_bg_opacity']);
 	$button_hover_bg = color_check($bar_colors['button_hover_bg'],$bar_colors['button_bg_hover_opacity']);
 	$font_color		 = $bar_colors['font_color'];
 	$font_hover_color= $bar_colors['font_hover_color'];
	$border			 = border_check($bar_colors['bar_border_width'],$bar_colors['bar_border_style'],$bar_colors['bar_border_color']);
	
	if ($panels['panel_styles']=='on') { // use Bar settings
	$panel_bg = $bar_bg;
	$panel_button_bg = $button_bg;
	$panel_button_hover_bg = $button_hover_bg;
	$panel_button_font_color = $font_color;
	$panel_button_font_hover_color = $font_hover_color;
	$panel_font_color = $font_color;
	$panel_font_hover_color = $font_hover_color;
	$panel_border = $border;
	} elseif ($panels['panel_styles']=='off') {	// inherit from theme styles
	$panel_bg = '';
	$panel_button_bg = '';
	$panel_button_hover_bg = '';
	$panel_button_font_color = '';
	$panel_button_font_hover_color = '';
	$panel_font_color = '';
	$panel_font_hover_color = '';
	$panel_border = '';
	} else {	// use custom panel settings
	$panel_bg	=	color_check($panels['panel_bg'],$panels['panel_bg_opacity']);
	$panel_button_bg = color_check($panels['panel_button_bg'],$panels['panel_button_bg_opacity']);
	$panel_button_hover_bg = color_check($panels['panel_button_hover_bg'],$panels['panel_button_bg_hover_opacity']);
	$panel_button_font_color = $panels['panel_button_font_color'];
	$panel_button_font_hover_color = $panels['panel_button_font_hover_color'];
	$panel_font_color = $panels['panel_font_color'];
	$panel_font_hover_color = $panels['panel_font_hover_color'];
	$panel_border =	border_check($panels['panel_border_width'],$panels['panel_border_style'],$panels['panel_border_color']);
	}      
        
	if ($bar_bg != '') {
		$custom_colors .= 'div.zappbar, div.zappbar a.searchbox span.search { 
			background-color: '.$bar_bg.';
		}
		#swipebox-action, #swipebox-caption {
			background-color: '.$bar_bg.' !important;
			background-image: none !important;
			color: '.$font_color.' !important;
			text-shadow: none;
		}
		';
	}
	if ($button_bg != '') {
		$custom_colors .= 'div.zappbar a.button { 
			background-color: '.$button_bg.';
		}
		div.zappbar a.searchbox span.search input[type="submit"] {
			background-color: '.$button_bg.';
			background-image: none;
			border: none;
			-webkit-box-shadow: none;
			-moz-box-shadow: none;
			-ms-box-shadow: none;
			box-shadow: none;
		}
		';
	}
	if ($font_color != '') {
		$custom_colors .= 'div.zappbar a.button { 
			color: '.$font_color.';
		}
		div.zappbar a.searchbox span.search input[type="submit"] {
			color: '.$font_color.';
			text-shadow: none;
		}
			#swipebox-close, #swipebox-prev, #swipebox-next {
				background-image: none !important;
				font-size: 32px;
				color: '.$font_color.';
				line-height: 45px;
				margin: 0 8px;
			}
			#swipebox-close::before {
				content: "X";
			}
			#swipebox-prev::after {
				content: "<";
				float: right;
			}
			#swipebox-next::after {
				content: ">";
				float: right;
			}
		';
	}
	if ($button_hover_bg != '') {
	$custom_colors .= '
		div.zappbar a.button:hover, 
		div.zappbar a.button:active,
		div.zappbar a.button:focus {
		background-color: '.$button_hover_bg.';
		color: '.$font_hover_color.';
		}
		div.zappbar a.searchbox span.search input[type="submit"]:hover,
		div.zappbar a.searchbox span.search input[type="submit"]:focus {
			background-color: '.$button_hover_bg.';
			color: '.$font_hover_color.';
		}
		';
	}
	if ($border != '') {
		$custom_colors .= 'div.zappbar.top { 
			border-bottom: '.$border.'; 
		}
		.sbtab { border: '.$border.';}
		div.zappbar.bottom { 
			border-top: '.$border.'; 
		}
		#swipebox-caption {
			border-bottom: '.$border.' !important;
		}
		#swipebox-action {
			border-top: '.$border.' !important;
		}
		';
	}
	// Now Panels //
	if ($panel_bg != '') {
		$custom_colors .='div.zb-panel {
			background-color: '.$panel_bg.' !important;
		}
		#zappbar_notice {
			background-color: '.$panel_bg.' !important;
		}
		';
			$custom_colors .= 'div.sbtab {
				background-color: '.$panel_bg.';
			}
			';
	}
	if ($panel_button_bg != '') {
		$custom_colors .= 'div.zb-panel .button {
			background-color: '.$panel_button_bg.';
			}
			';
		$custom_colors .= 'div.sbtab * {
			background-color: '.$panel_button_bg.';
		}
		#zappbar_notice .button, #zappbar_notice a {
			background-color: '.$panel_button_bg.';
		}
		';

	}
		if ($panel_button_hover_bg != '') {
			$zb_style .= '
			div.zb-panel .button:hover, 
			div.zb-panel .button:active, 
			div.zb-panel .button:focus  {
				background-color: '.$panel_button_hover_bg.';
				color: '.$panel_font_hover_color.' !important;
			}
			#zappbar_notice a:hover, #zappbar_notice a:active, #zappbar_notice a:focus,
			#zappbar_notice .button:hover, #zappbar_notice .button:active, #zappbar_notice .button:focus {
				background-color: '.$panel_button_hover_bg.';
				color: '.$panel_font_hover_color.' !important;
			}
			';
			$zb_style .= 'div.sbtab span:hover, div.sbtab span:active, div.sbtab span:focus {
				background-color: '.$panel_button_hover_bg.';
				color: '.$panel_font_hover_color.' !important;
			}
			';
		}
	if ($panel_button_font_color != '') {
		$custom_colors .= 'div.zb-panel .button, div.zb-panel button, 
		div.zb-panel input[type="submit"], div.zb-panel input[type="button"] {
			color: '.$panel_button_font_color.' !important;
		}
		#zappbar_notice, #zappbar_notice a, #zappbar_notice .button {
			color: '.$panel_button_font_color.' !important;
		}
		';
		$custom_colors .= 'div.sbtab {
			color: '.$panel_button_font_color.';
		}
		';
	}
		if ($panel_button_font_hover_color != '') {
			$custom_colors .= '
				div.zb-panel .button:hover, div.zb-panel .button:focus,
				div.zb-panel button:hover,  div.zb-panel button:focus,
				div.zb-panel input[type="submit"]:hover, div.zb-panel input[type="submit"]:focus,
				div.zb-panel input[type="button"]:hover, div.zb-panel input[type="button"]:focus {
					color: '.$panel_button_font_hover_color.';
				}
				';
		}	
	if ($panel_font_color != '') {
		$custom_colors .= 'div.zb-panel, div.zb-panel a, div.zb-panel p,
		div.zb-panel h1, div.zb-panel h2, div.zb-panel h3 {
			color: '.$panel_font_color.' !important;
		}
		';
	}
		if ($panel_font_hover_color != '') {
			$custom_colors .= '
				div.zb-panel a:hover, div.zb-panel a:active, div.zb-panel:focus {
					color: '.$panel_font_hover_color.';
				}
				';
		}		

	if ($panel_border != '') {
		$custom_colors .= 'div.zb-panel.left {
			border-right: '.$panel_border.';
		}
		div.zb-panel.right {
			border-left: '.$panel_border.';
		}
		#zappbar_notice {
			border: '.$panel_border.';
		}
		';
		$custom_colors .= 'div.sbtab {
			border: '.$panel_border.';}
		';
	}

	if ($zb_layout['logo'] != '') {
		$custom_colors .= '
			div.zappbar div.icon.fa.fa-logo,
			div.zappbar div.genericon.genericon-logo,
			div.zappbar div.icon.dashicons.dashicons-logo {
				height: 45px; width: 100%;
				background: url("'.$zb_layout['logo'].'") center center no-repeat;
				-webkit-background-size: 100% auto;
				-webkit-background-size: contain;
				-moz-background-size: contain;
				-ms-background-size: contain;
				background-size: contain;
			}
		';
	}

		
    // WooCommerce Cart Items //
    $zb_style .= '
    	span.zb-label span.contents {
			height:15px;
			min-width:15px;
			background: red;
			color:white;
			position:absolute;
			top:-34px;
			border-radius:100%;
			line-height:15px;
			font-size:8px;
			text-shadow:none;
			right: 9px;
			text-align:center;
			border:1px solid white;
			}
		a.button.zb-disabled {
			opacity: .5;
			cursor: not-allowed;
		}
    ';
    
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
$embiggen_woo = '';
if (isset($zb_site['altertheme'])) {
	$zb_site_alter = $zb_site['altertheme'];
	if (!isset($zb_site_alter['header'])) { $zb_site_alter['header'] = ''; };
	if (!isset($zb_site_alter['sitenav'])){ $zb_site_alter['sitenav']= ''; };
	if (!isset($zb_site_alter['commentlist'])){$zb_site_alter['commentlist']='';};
	if (!isset($zb_site_alter['commentform'])){$zb_site_alter['commentform']='';};
	if (!isset($zb_site_alter['push'])){$zb_site_alter['push']='';};
	if (!isset($zb_site_alter['blognav'])){$zb_site_alter['blognav']='';};
} else {
	$zb_site_alter = array('header' => '', 'sitenav' => '', 'commentlist' => '', 'commentform' => '', 'push' => '', 'blognav' => '');
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
    if ($zb_site_alter['blognav'] != '') {
    	$blog_nav = '
    		.blognav {
    			display: none !important;
    		}
    	';
    } else {
    	$blog_nav = '';
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
    			display: none !important;
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
    			display: none !important;
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
$commentform = '';
if ($zb_site['comment_custom'] != '') {
	$custom_comment = $zb_site['comment_custom'];
};
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
   				.zb-admin-bar '.$custom_comment.'.zb-panel {
   					padding: 90px 0 !important;
   				}
    	';   	
    	} else {
    		if ($zb_site_alter['commentlist'] != '') {
				$commentform = '
					#comments.zb-panel {
						margin-top: 0;
						padding: 60px 0 0 0 !important;
						overflow: auto;
						overflow-x: scroll;
						-webkit-overflow-scrolling: touch;
					}
						#comments.zb-panel form {
							margin-bottom: 100px;
						}
						#comments.zb-panel textarea,
						#comments.zb-panel input[type="text"] {
							max-width: 100%;
							resize: vertical;
						}
						.zb-admin-bar #comments.zb-panel {
							padding: 90px 0 !important;
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
						.zb-admin-bar #respond.zb-panel {
							padding: 90px 0 !important;
						}
				';
    		}
    	}
if (class_exists( 'woocommerce' ) && isset($zb_site['alter_woo_theme']) ) {
	$zb_site_alterwoo = $zb_site['alter_woo_theme'];
    if (isset($zb_site_alterwoo['woo_reviews'])) {
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
    if (isset($zb_site_alterwoo['woo_desc'])) {
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
    if (isset($zb_site_alterwoo['woo_addl'])) {
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
    if (isset($zb_site_alterwoo['woo_big'])) {
    	$embiggen_woo = '
    	    .woocommerce .shop_table_responsive {
    			font-size: 150%;
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
		$hideit = true;
		// if MangaPress Plugin is in use don't hide comic nav on latestcomic_page
		if (defined('MP_VERSION') ){
			if (MP_VERSION >= 4) {
				if (is_latest_comic_page()){ $hideit = false; };
			} else {
				$mp_options = get_option('mangapress_options');
				$mp_page = $mp_options['basic']['latestcomic_page'];
				$mp_page = get_page_by_path($mp_page);
				$mp_page = get_permalink($mp_page->ID);				
				if (get_page_link() == $mp_page) { $hideit = false; };
			}
		};
		if ($hideit) {
    		$comic_nav = 'div#comic-foot, #comic-navigation, body[class*="webcomic"] nav.post-navigation { display: none; }';
    	} else {
    		$comic_nav = '';
    	}
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
    	$screen1 = '1919';  $screen2 = '960';		$screen3 = '800';
    } else if ($zb_site['showon'] == 'tablets_hd') {
    	$screen1 = '1440';	$screen2 = '960';		$screen3 = '768';
    } else if ($zb_site['showon'] == 'tablets') {
    	$screen1 = '1280';	$screen2 = '800';		$screen3 = '736';
    } else {
    	$screen1 = '1024';	$screen2 = '768';		$screen3 = '736';
    };
    
    if ($zb_site['showon'] == 'desktops_hd' || $zb_site['applyto'] == 'force_mobile' || $zb_site['applyto'] == 'only_mobile_forced') {
    	$zb_style .= '
    	/* desktops_hd / force_mobile / only_mobile_forced */
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$blog_nav.'
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
    	@media screen and (min-width: '.($screen2+1).'px) and (max-width: '.$screen1.'px) {
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$blog_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}  	
    	}
    	@media screen and (min-width: '.($screen3+1).'px) and (max-width: '.$screen2.'px) {
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$blog_nav.'
    		'.$always_hide.'
    		.zappbar { 
    			display: block; 
    		}
    		'.$sidebars.'
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
    	}
    	@media screen and (max-width: 479px) {
    		.zappbar a.searchbox.left span.search form, 
    		.zappbar a.searchbox.right span.search form {
    			float: none;
    			margin: 0 auto;
    		}'.$embiggen_woo.'
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
    	@media screen and (min-width: '.($screen2+1).'px) and (max-width: '.$screen1.'px) {
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$blog_nav.'
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
    	@media screen and (min-width: '.($screen3+1).'px) and (max-width: '.$screen2.'px) {';
    	$zb_style .='
    		'.$splash.'
    		'.$custom_page[0].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$blog_nav.'
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
    	@media screen and (max-width: '.$screen3.'px) {
    		'.$splash.'
    		'.$custom_page[1].'
    		'.$hide_header.'
    		'.$hide_nav.'
    		'.$blog_nav.'
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
    		}'.$embiggen_woo.'
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
    		'.$blog_nav.'
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
    		}'.$embiggen_woo.'
    	}';
    } else { 
		$zb_style = '';    	// inject nothing
    }
	if ($zb_style != '') { $zb_style .= '</style>';}
    echo $zb_style;
};
?>