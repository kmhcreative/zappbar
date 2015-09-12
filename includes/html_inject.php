<?php

function zappbar_inject() {
	$zb_layout = get_option('zappbar_layout');
	global $post;
	$zb_pages = zb_paginate();
	$share_panel = 0;	// assume no button is assigned
	$left_sidebar = 0;	// to activate any of these
	$right_sidebar= 0;	// panels.
	$left_appmenu = 0;
	$right_appmenu= 0;
	global $share_panel, $left_sidebar, $right_sidebar, $left_appmenu, $right_appmenu;

	function build_zappbars($value,$layout,$position,$paged) {
	global $post;
	$xtra = '';
		if ($position == null) { $position = 'top'; };
	$zb_name = array(
		'button_a',
		'button_b',
		'button_c',
		'button_d',
		'button_e'
	);	
		$html = '<div class="zappbar zb-'.$layout.' '.$position.'">';
		$x = 0;
		foreach ($value as $val) {
			$html .= '<div class="zb '.$zb_name[$x].' integrated-webcomic">';
			if ( array_filter($paged) ) {
				if ( function_exists('comicpress_display_comic') && comicpress_themeinfo('archive_display_order') == "asc" ) {
					$first_page = $paged[0]; $last_page = $paged[3];
				} else if ( function_exists('comicpress_display_comic') && comicpress_themeinfo('archive_display_order') == "desc" ) {
					$first_page = $paged[3]; $last_page = $paged[0];
				} else { $first_page = $paged[3]; $last_page = $paged[0]; }
				if ($val[2] == 'first_page') {
					$val[2] = $first_page;
				} else if ($val[2] == 'prev_page') {
					$val[2] = $paged[1];
				} else if ($val[2] == 'next_page') {
					$val[2] = $paged[2];
				} else if ($val[2] == 'last_page') {
					$val[2] = $last_page;
				} else {
				};
			} 
			if ( get_post_type() == 'comic' || function_exists('ceo_pluginfo') ) {
				if ($val[2] == 'prev_chapter') {
					$val[2] = ceo_get_previous_chapter();
				} else if ($val[2] == 'first_comic') {
					$val[2] = ceo_get_first_comic_permalink();
				} else if ($val[2] == 'prev_comic') {
					$val[2] = ceo_get_previous_comic_permalink();
				} else if ($val[2] == 'next_comic') {
					$val[2] = ceo_get_next_comic_permalink();
				} else if ($val[2] == 'last_comic') {
					$val[2] = ceo_get_last_comic_permalink();
				} else if ($val[2] == 'next_chapter') {
					$val[2] = ceo_get_next_chapter();
				} else if ($val[2] == 'comic_archive') {
					$val[2] = get_site_url().'/comic';
				} else {};
			} 
			if ( function_exists('comicpress_display_comic')  ) {	
				if ($val[2] == 'prev_chapter') {
					$val[2] = comicpress_get_previous_storyline_start_permalink();
				} else if ($val[2] == 'first_comic') {
					$val[2] = comicpress_get_first_comic_permalink();
				} else if ($val[2] == 'prev_comic') {
					$val[2] = comicpress_get_previous_comic_permalink();
				} else if ($val[2] == 'next_comic') {
					$val[2] = comicpress_get_next_comic_permalink();
				} else if ($val[2] == 'last_comic') {
					$val[2] = comicpress_get_last_comic_permalink();
				} else if ($val[2] == 'next_chapter') {
					$val[2] = comicpress_get_next_storyline_start_permalink();
				} else if ($val[2] == 'comic_archive') {
					$val[2] = get_site_url().'/?cat='.comicpress_themeinfo('comiccat').'/';
				} else {};
			} 
			if ( preg_match('/webcomic/',get_post_type()) ) {
				// Yes, this is a very convoluted way of getting the URLs
				if ($val[2] == 'prev_chapter') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[previous_webcomic_storyline_link]'), $matches);				
					$val[2] = $matches[2][0];				
				} else if ($val[2] == 'first_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[first_webcomic_link]'), $matches);				
					$val[2] = $matches[2][0];
				} else if ($val[2] == 'prev_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[previous_webcomic_link]'), $matches);
					$val[2] = $matches[2][0];
				} else if ($val[2] == 'next_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[next_webcomic_link]'), $matches);
					$val[2] = $matches[2][0];;
				} else if ($val[2] == 'last_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[last_webcomic_link]'), $matches);				
					$val[2] = $matches[2][0];
				} else if ($val[2] == 'next_chapter') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[next_webcomic_storyline_link]'), $matches);				
					$val[2] = $matches[2][0];
				} else if ($val[2] == 'comic_archive') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[the_webcomic_collections]'), $matches);				
					$val[2] = $matches[2][0];							
				} else {};
			} 
			if ( class_exists( 'woocommerce' ) && ( is_product() || is_cart() || is_checkout() || is_account_page() )  ) {
				global $woo_options, $woocommerce;
				if ($val[2] == 'woo_store') {
					$val[2] = get_permalink( woocommerce_get_page_id( 'shop' ) );
				}
				if ($val[2] == 'woo_cart' && ( is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
					$val[2] = $woocommerce->cart->get_cart_url();
					$cartcount = sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
					$label = '<span class="amount">'.$woocommerce->cart->get_cart_total().'</span>';
					if ($cartcount != '0') {
						$label .= '<span class="contents">'.$cartcount.'</span>';
					};
					$val[1] = $label;
				} else if ($val[2] == 'woo_review' && is_product() ) {
					global $product;
					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_rating_count() ) ) {
						$val[1] = $val[1].'<span class="contents">'.$count.'</span>';
					}
				} else if ($val[2] == 'woo_account' && ( is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
					$val[2] = get_permalink( get_option('woocommerce_myaccount_page_id') );
					if ( is_user_logged_in() ) {
						$who = wp_get_current_user();
						if (strlen($who->display_name)>10) {
						$val[1] = __('Account','woothemes');
						} else {
						$val[1] = $who->display_name;
						}
					} else {
						$val[1] = __('Login','woothemes');
					}
				} else if (in_array($val[2],array('woo_search','woo_search_left','woo_search_right'))) {
				if ($val[2] == 'woo_search_left') { 		$shift = ' left';
				} else if ($val[2] == 'woo_search_right') { $shift = ' right';
				} else { $shift = ' center';}
				$xtra = " searchbox".$shift;
				$val[1] = $val[1].'</span><span class="search out">
					<form role="search" method="get" action="'.esc_url(home_url( '/' )).'">
						<label class="screen-reader-text" for="s">'.__( 'Search Products:' , 'woothemes' ).'</label>
						<input type="search" results=5 autosave="'.esc_url(home_url( '/' )).'" class="input-text" placeholder="'.esc_attr__( 'Search Products', 'woothemes' ).'" value="'.get_search_query().'" name="s" />
						<input type="submit" class="button" value="'.esc_attr__( 'Search', 'woothemes' ).'" />
						<input type="hidden" name="post_type" value="product" />
					</form>
					</span>';
				} else {};
			};
			if ($val[2] == 'appmenu_left') {
				global $left_appmenu;
				$left_appmenu = 1;
			}
			if ($val[2] == 'appmenu_right') {
				global $right_appmenu;
				$right_appmenu = 1;
			}
			if ($val[2] == 'sidebar_left') {
				global $left_sidebar;
				$left_sidebar = 1;
			}
			if ($val[2] == 'sidebar_right') {
				global $right_sidebar;
				$right_sidebar = 1;
			}
			if ($val[2] == 'custom_email') {
				$zb_social = get_option('zappbar_social');
				$val[2] = 'mailto:'.$zb_social['email_address'];
			}
			if ($val[2] == 'blogposts') {
				$cats = get_categories();
				$pls = get_option('permalink_structure');
				/*	Page title is usually "Category Archives: First_Category_Name"
					but we want to trick it to only say "Category Archives:" so we
					inject cat_ID=1000 or category slug "all" which most likely do
					not exist, which leaves the name part of the title blank
				*/  
				if ($pls == '') {
					$category = '/?cat=1000,';
				} else if ( $pls == '/archives/%post_id%') {
					$category = '/archives/category/all,';
				} else { $category = '/category/all,';}
				$val[2] = esc_url(home_url()).$category;
				$c = 0;
				foreach ($cats as $cat) {
					if ($pls=='') {
					$val[2] .= $cat->cat_ID;
					} else {
					$val[2] .= $cat->slug;
					}
					if ($c<(count($cats)-1)){
						$val[2] .= ',';
					}
					$c++;
				}
				$val[2] .= '/';
			}
			if ($val[2] == 'ryuzine_rack') {
				$val[2] = esc_url(home_url()).'/ryuzine-rack/';
			}
			if (in_array($val[2],array('search_box','search_left','search_right'))) {
				if ($val[2] == 'search_left') { 		$shift = ' left"';
				} else if ($val[2] == 'search_right') { $shift = ' right"';
				} else { $shift = ' center';}
				$xtra = " searchbox".$shift;
				$val[1] = $val[1].'</span><span class="search out">
					<form role="search" method="get" class="search-form" action="'.home_url( '/' ).'">
						<label>
							<span class="screen-reader-text">Search for:</span>
							<input type="search" results=5 class="input-text" placeholder="Search Site" value="" name="s" title="Search for:" />
						</label>
						<input type="submit" class="search-submit" value="Search" />
					</form>
					</span>';
			
			}
			// now all the share options
			if ($val[2] == 'commentform') {
				if (get_comments_number() > 0) {
						 $count = '<span class="contents">'.get_comments_number().'</span>';
				} else { $count = '';};
				$val[1] = $val[1].$count;
			} else if ($val[2] == 'share_this') {
				global $share_panel;
				$share_panel = 1;
			} else if ($val[2] == 'share_fb') {
				$val[2] = 'http://www.facebook.com/sharer.php?u='.urlencode(get_permalink($post->ID)).'&amp;t='.urlencode(get_the_title($post->ID)).'';
				$xtra = ' zb-social';
			} else if ($val[2] == 'share_twitter') {
				$val[2] = 'http://twitter.com/share?text='.urlencode(get_the_title($post->ID)).'&url='.urlencode(wp_get_shortlink($post->ID)).'';
				$xtra = ' zb-social';
			} else if ($val[2] == 'share_gplus') {
				$val[2] = 'https://plus.google.com/share?url='.urlencode(get_permalink($post->ID));
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_reddit') {
				$val[2] = 'http://www.reddit.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'';
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_stumble') {
				$val[2] = 'http://www.stumbleupon.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'';
				$xtra = ' zb-social';						
			} else if ($val[2] == 'share_digg') {
				$val[2] = 'http://digg.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'';
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_linkedin') {
				$val[2] = 'http://www.linkedin.com/shareArticle?mini=true&amp;title='.urlencode(get_the_title($post->ID)).'&amp;url='.urlencode(wp_get_shortlink($post->ID)).'';
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_pinterest') {
				$val[2] = 'http://pinterest.com/pin/create/button/?url='.urlencode(get_permalink($post->ID)).'&media='.urlencode(wp_get_attachment_url( get_post_thumbnail_id($post->ID) )).'';
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_delicious') {
				$val[2] = 'http://del.icio.us/post?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'';
				$xtra = ' zb-social';			
			} else {};
			$icon = explode('|',$val[0]);
			$html .= '<a href="'.$val[2].'" class="button'.$xtra.'" target="_self"><div class="icon '.$icon[0].' '.$icon[1].'"></div><br/><span class="zb-label">'.$val[1].'</span></a>';

			$html .= '</div>';
			$x++;
		}
		$html .= '</div>';
		echo $html;
	}
	$zb_site = get_option('zappbar_site');
	if ($zb_site['showon'] != 'none') {
		if ( class_exists('woocommerce') && $zb_layout['use_woo_top_bar']=='yes' && (is_product() || is_cart() || is_checkout() || is_account_page()) ) {
		build_zappbars($zb_layout['woo_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
		} else if ( 	($post->post_type == 'comic' ||
				(function_exists('comicpress_display_comic') && comicpress_in_comic_category() && !is_home() ) ||
				preg_match('/webcomic/',get_post_type()) ||
				$post->post_type == 'mangapress_comic') && $zb_layout['use_comic_top_bar']=='yes' 
				&& !is_archive()
		) {
		build_zappbars($zb_layout['comic_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
		} else if ( $zb_layout['use_archive_top_bar']=='yes' && (is_archive() || is_search()) ) {
		build_zappbars($zb_layout['archive_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
		} else {
		build_zappbars($zb_layout['default_top'],$zb_layout['button_layout'],'top',$zb_pages);
		}
		if ( class_exists('woocommerce') && $zb_layout['use_woo_bottom_bar']=='yes' && is_product() ) {
		build_zappbars($zb_layout['woo_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
		} else if ( 	($post->post_type == 'comic' ||
				(function_exists('comicpress_display_comic') && comicpress_in_comic_category() && !is_home() ) ||
				preg_match('/webcomic/',get_post_type()) ||
				$post->post_type == 'mangapress_comic') && $zb_layout['use_comic_bottom_bar']=='yes' 
				&& !is_archive()
		) {
		build_zappbars($zb_layout['comic_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
		} else if ( $zb_layout['use_archive_bottom_bar']=='yes' && (is_archive() || is_search()) ) {
		build_zappbars($zb_layout['archive_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
		} else {
		build_zappbars($zb_layout['default_bottom'],$zb_layout['button_layout'],'bottom',$zb_pages);
		}
	}
	
    $panels = get_option('zappbar_panels');
    if ($left_appmenu == 1 || $right_appmenu == 1) {
	$menu_args = array(
		array(
		'theme_location' => 'zb-menu-left',
		'depth' => isset( $panels['panel_menu'] ) && !is_null( $panels['panel_menu'] ) ? $panels['panel_menu'] : '0'
		),
		array(
		'theme_location' => 'zb-menu-right',
		'depth' => isset( $panels['panel_menu'] ) && !is_null( $panels['panel_menu'] ) ? $panels['panel_menu'] : '0'		
		)
	);
	};
	if ($left_appmenu == 1) {
	?>
		<div id="zappbar_menu_left" class="zb-panel left hide"><div class="marginbox"><?php wp_nav_menu($menu_args[0]); ?></div></div>
	<?php };
		if ($right_appmenu == 1) { ?>
		<div id="zappbar_menu_right" class="zb-panel right hide"><div class="marginbox"><?php wp_nav_menu($menu_args[1]); ?></div></div>
	<?php }; 	
		if ( $panels['panel_tabs'] == 'yes' || $left_sidebar == 1) { ?>
		<div id="zappbar_sidebar_left" class="zb-panel <?php if($panels['panel_tabs']=='no'){echo 'notabs ';}; ?>left hide"><div class="marginbox"><?php if ( dynamic_sidebar( 'zb-panel-left' ) ) : else : endif; ?></div></div>
	<?php };
			if ( $panels['panel_tabs'] == 'yes' || $right_sidebar == 1) { ?>
		<div id="zappbar_sidebar_right" class="zb-panel <?php if($panels['panel_tabs']=='no'){echo 'notabs ';}; ?>right hide"><div class="marginbox"><?php if ( dynamic_sidebar( 'zb-panel-right' ) ) : else : endif; ?></div></div>
	<?php	}; 
		if ($share_panel == 1 ) { ?>
		<div id="zappbar_share_this" class="zb-panel right hide"><div class="marginbox">
			<h2>Share this On:</h2>
			<?php
				$zb_social = get_option('zappbar_social');
				$zb_social_panel = $zb_social['social_panel'];
				if ( $zb_social_panel['facebook'] != '') {	?>
			<a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;t=<?php echo urlencode(get_the_title($post->ID)); ?>" title="Share on Facebook" rel="nofollow" target="_blank" class="zb-social facebook">Facebook</a>
			<?php	};
				if ( $zb_social_panel['twitter'] != '') { ?>
			<a href="http://twitter.com/share?text=<?php echo urlencode(get_the_title($post->ID)); ?>&url=<?php echo urlencode(wp_get_shortlink($post->ID)); ?>" title="Share on Twitter" rel="nofollow" target="_blank" class="zb-social twitter">Twitter</a>
			<?php	};
				if ( $zb_social_panel['google'] != '') { ?>
			<a href="https://plus.google.com/share?url=<?php echo urlencode(get_permalink($post->ID)); ?>" title="Share on Google+" rel="nofollow" target="_blank" class="zb-social google-plus">Google+</a>
			<?php	};
				if ( $zb_social_panel['reddit'] != '') { ?>
			<a href="http://www.reddit.com/submit?url=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;title=<?php echo urlencode(get_the_title($post->ID)); ?>" title="Share on Reddit" rel="nofollow" target="_blank" class="zb-social reddit">Reddit</a>
			<?php	};
				if ( $zb_social_panel['stumble'] != '') { ?>
			<a href="http://www.stumbleupon.com/submit?url=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;title=<?php echo urlencode(get_the_title($post->ID)); ?>" title="Stumble It" rel="nofollow" target="_blank" class="zb-social stumbleupon">Stumble It</a>
			<?php 	};
				if ( $zb_social_panel['digg'] != '') { ?>
			<a href="http://digg.com/submit?url=<?php urlencode(get_permalink($post->ID)); ?>&amp;title=<?php urlencode(get_the_title($post->ID)); ?>" title="Digg this!" rel="nofollow" target="_blank" class="zb-social digg">Digg this!</a>
			<?php	};
				if ( $zb_social_panel['linkedin'] != '') { ?>
			<a href="http://www.linkedin.com/shareArticle?mini=true&amp;title=<?php echo urlencode(get_the_title($post->ID)); ?>&amp;url=<?php echo urlencode(wp_get_shortlink($post->ID)); ?>" title="Share on LinkedIn" rel="nofollow" target="_blank" class="zb-social linkedin">LinkedIn</a>
			<?php 	};
				if ( $zb_social_panel['pinterest'] != '') { ?>
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($post->ID)); ?>&media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id($post->ID) )); ?>" title="Pin this!" rel="nofollow" target="_blank" class="zb-social pinterest">Pinterest</a>
			<?php 	};
				if ( $zb_social_panel['delicious'] != '') { ?>
			<a href="http://del.icio.us/post?url=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;title=<?php echo urlencode(get_the_title($post->ID)); ?>" title="Bookmark on del.icio.us" rel="nofollow" target="_blank" class="zb-social delicious">Del.icio.us</a>
			<?php	};
				if ( $zb_social_panel['rss'] != '') { ?>
			<a href="<?php echo get_site_url(); ?>/?feed=rss" title="RSS Feed" rel="nofollow" target="_blank" class="zb-social rss-feed">RSS Feed</a>
			<?php	};
				if ( $zb_social_panel['email'] != '') { ?>
			<a href="mailto:?subject=Sharing: <?php echo get_the_title($post->ID); ?>&amp;body=%0AThought you might be interested in this:%0A%0A<?php echo get_the_title($post->ID); ?>%0A%0A<?php echo urlencode(get_permalink($post->ID)); ?>%0A%0A" title="Share by E-mail" rel="nofollow" target="_blank" class="zb-mail">E-mail Link!</a>
			<?php 	};	?>
		</div></div>
	<?php }; 
		if ( $panels['panel_tabs'] == 'yes' ) {
	?>
		<div id="zappbar_sbtab_left" class="sbtab hide"><span></span></div>
		<div id="zappbar_sbtab_right" class="sbtab hide"><span></span></div>
	<?php 	}; 
		if ( $zb_site['splash_screen'] != '' ) { ?>
		<div id="zappbar_splash"><span></span></div>
	<?php }; ?>
		<div id="zappbar_notice" class="out"></div>
	<?php
}
	
if (!is_admin()) {	// inject this at the end of the code
	// Load Social Button Styles even if ZappBars are disabled //
	function zb_load_social() {	
		$zb_site = get_option('zappbar_site');
		$plugin_dir_url = zappbar_pluginfo('plugin_url');
		$zb_css2 = $plugin_dir_url . 'css/social_buttons.css';
		wp_enqueue_style( 'zb-social', $zb_css2, '', '');
	}
	add_action('wp_enqueue_scripts', 'zb_load_social',99);
	$zb_site = get_option('zappbar_site');
	if ($zb_site['showon'] != 'none') {
		function zb_load_assets() {
			$zb_site = get_option('zappbar_site');
			$plugin_dir_url = zappbar_pluginfo('plugin_url');
			wp_enqueue_style( 'dashicons' );
			$font1 = $plugin_dir_url . 'fonts/genericons/genericons.css';
			wp_enqueue_style( 'genericons', $font1, '', '');

			$font2 = $plugin_dir_url . 'fonts/font-awesome/css/font-awesome.css';
			wp_enqueue_style( 'font-awesome', $font2,'','');
		 	if ($zb_site['responsive']=='no') {
				$css = $plugin_dir_url . 'css/site_tweaks.css';
				wp_enqueue_style( 'zb-site-tweaks', $css, '', '');
			}
			$zb_css1 = $plugin_dir_url . 'css/zappbar_'.$zb_site['showon'].'.css';
			wp_enqueue_style( 'zb-response', $zb_css1, '', '');
			
			$zb_js1 = $plugin_dir_url . 'js/zappbar.js';
			wp_enqueue_script( 'zb-functions', $zb_js1, array( 'jquery' ), '1.0', true );
			if ($zb_site['splash_screen']!='') {
				$zb_js2 = $plugin_dir_url . 'js/jquery.coo.kie.js';
				wp_enqueue_script( 'zb-cookiejar', $zb_js2, array( 'jquery' ), '1.0', true );
			}
			if (is_active_widget('comicpress_google_translate_widget', false, 'comicpress_google_translate_widget', true)) {
				wp_enqueue_script('google-translate', 'http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', null, null, true);
				wp_enqueue_script('google-translate-settings', $plugin_dir_url.'/js/googletranslate.js');
			}
		}
			
		function app_meta() {
			global $post;
			$zb_site = get_option('zappbar_site');
			$zb_social = get_option('zappbar_social');
    		$bar_colors = get_option('zappbar_colors');
    		$panels = get_option('zappbar_panels');
			$plugin_dir_url = zappbar_pluginfo('plugin_url');
			echo '<meta name="apple-mobile-web-app-capable" content="yes" id="app_meta">';
			/* 	If you want a zoomable interface in app mode (not recommended) uncomment the line below 
				but understand it WILL break scrolling and fixed positioning on Android 2.x devices, it
				will also likely confuse users who accidentally zoom in on other devices.
			*/
//			echo '<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=5,user-scalable=yes" id="view_meta">';
			echo '<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" id="view_meta">';
			echo '<meta name="apple-mobile-web-app-status-bar-style" content="black" />';
			echo '<meta name="apple-mobile-web-app-title" content="'.get_bloginfo('name').'">';		

$blank_splash = '<!-- iPad, retina, portrait -->
<link href="'.$plugin_dir_url.'includes/images/splash/1536x2008.png"
	  media="(device-width: 768px) and (device-height: 1024px)
		 and (orientation: portrait)
		 and (-webkit-device-pixel-ratio: 2)"
	  rel="apple-touch-startup-image">
<!-- iPad, retina, landscape -->
<link href="'.$plugin_dir_url.'includes/images/splash/1496x2048.png"
	  media="(device-width: 768px) and (device-height: 1024px)
		 and (orientation: landscape)
		 and (-webkit-device-pixel-ratio: 2)"
	  rel="apple-touch-startup-image">
<!-- iPad, portrait -->
<link href="'.$plugin_dir_url.'includes/images/splash/768x1004.png"
	  media="(device-width: 768px) and (device-height: 1024px)
		 and (orientation: portrait)
		 and (-webkit-device-pixel-ratio: 1)"
	  rel="apple-touch-startup-image">
<!-- iPad, landscape -->
<link href="'.$plugin_dir_url.'includes/images/splash/748x1024.png"
	  media="(device-width: 768px) and (device-height: 1024px)
		 and (orientation: landscape)
		 and (-webkit-device-pixel-ratio: 1)"
	  rel="apple-touch-startup-image">
<!-- iPhone 6 plus -->
<link href="'.$plugin_dir_url.'includes/images/splash/828x1418.png"
	  media="(device-width: 414px) and (device-height: 736px)
		 and (-webkit-device-pixel-ratio: 2)"
	  rel="apple-touch-startup-image">
<!-- iPhone 6 -->
<link href="'.$plugin_dir_url.'includes/images/splash/750x1284.png"
	  media="(device-width: 375px) and (device-height: 667px)
		 and (-webkit-device-pixel-ratio: 2)"
	  rel="apple-touch-startup-image">
<!-- iPhone 5 -->
<link href="'.$plugin_dir_url.'includes/images/splash/640x1096.png"
	  media="(device-width: 320px) and (device-height: 568px)
		 and (-webkit-device-pixel-ratio: 2)"
	  rel="apple-touch-startup-image">
<!-- iPhone, retina -->
<link href="'.$plugin_dir_url.'includes/images/splash/640x920.png"
	  media="(device-width: 320px) and (device-height: 480px)
		 and (-webkit-device-pixel-ratio: 2)"
	  rel="apple-touch-startup-image">
<!-- iPhone -->
<link href="'.$plugin_dir_url.'includes/images/splash/320x460.png"
	  media="(device-width: 320px) and (device-height: 480px)
		 and (-webkit-device-pixel-ratio: 1)"
	  rel="apple-touch-startup-image">
';
			echo $blank_splash;

			if ($zb_site['app_icon'] != '') {
				$icon = $zb_site['app_icon'];
				$favicon = aq_resize( $icon, 16, 16, true); 
				$tablet = aq_resize( $icon, 72, 72, true);
				$hi_res = aq_resize( $icon, 114, 114, true);
				$phones = aq_resize( $icon, 57, 57, true);
			} else {
				$favicon = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_16x16.png';
				$tablet = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_72x72.png';
				$hi_res = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_114x114.png';
				$phones = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_57x57.png';
			}
			echo '<link rel="icon" type="image/png" href="'.$favicon.'" />';
			echo '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="'.$hi_res.'" />';
			echo '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="'.$tablet.'" />';
			echo '<link rel="apple-touch-icon-precomposed" href="'.$phones.'" />';
			// Facebook Open Graph stuff //
			if ($zb_social['fb_default_img'] != '') {
				echo '<!--// Facebook OpenGraph Data by ZappBar //-->';
				echo '<meta property="og:locale" content="'.get_bloginfo('language').'" />';
				echo '<meta property="og:type" content="website" />';
				echo '<meta property="og:title" content="'.get_bloginfo('name').' -" />';
				echo '<meta property="og:url" content="'.get_bloginfo('url').'" />';
				echo '<meta property="og:site_name" content="'.get_bloginfo('name').'" />';
				if ( !is_singular()) { //if it is not a post or a page
					// we cannot get a thumbnail
				} else {
					if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
						$default_image="http://example.com/image.jpg"; //replace this with a default image on your server or an image in your media library
						echo '<meta property="og:image" content="' . $zb_social['fb_default_img'] . '"/>';
					}
					else{
						$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
						echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
					}
				};
				echo "
				";	
			};		
			// Twitter stuff //
			if ($zb_social['twitter_id'] != '') {
				echo '<!--// Twitter Meta //-->';
				echo '<meta name="twitter:card" content="summary"/>';
				echo '<meta name="twitter:site" content="'.$zb_social['twitter_id'].'"/>';
				echo '<meta name="twitter:domain" content="'.get_bloginfo('name').'"/>';
				echo '<meta name="twitter:creator" content="'.$zb_social['twitter_id'].'"/>';
			}


			require_once(zappbar_pluginfo('plugin_path').'includes/dynamic-css.php');
			$zb_site_alter = $zb_site['altertheme'];

?>
		<script type="text/javascript">
			var zb_base = "<?php echo $plugin_dir_url; ?>";
			var showon = "<?php echo $zb_site['showon']; ?>";
			var wrapper = ['page','page-wide','wrapper'<?php if ($zb_site['page_custom']!=''){ echo ',\''.$zb_site['page_custom'].'\'';}; ?>];
			var is_responsive = "<?php echo $zb_site['responsive']; ?>";
			var telnum = escape("<?php if($zb_social['phone_number']!='') {echo $zb_social['phone_number']; }; ?>");
			var splash = "<?php if($zb_site['splash_screen']!=''){echo $zb_site['splash_screen']; }; ?>";
			var splash_timer = <?php echo $zb_site['splash_timer']; ?>;
			var splash_link = "<?php echo $zb_site['splash_link']; ?>";
			var comments_open = "<?php echo comments_open(); ?>";
			var is_home = "<?php echo is_home(); ?>";
			var is_archive = "<?php echo is_archive(); ?>";
			var header_custom = "<?php echo $zb_site['header_custom']; ?>";
			var nav_custom 	  = "<?php echo $zb_site['nav_custom']; ?>";
			var altertheme_push = "<?php echo $zb_site_alter['push']; ?>";
			var altertheme_commentform = "<?php echo $zb_site_alter['commentform']; ?>";
			var altertheme_header 	= "<?php echo $zb_site_alter['header']; ?>";
			var altertheme_sitenav	= "<?php echo $zb_site_alter['sitenav']; ?>";
			var altertheme_sidebars = "<?php echo $zb_site['sidebars']; ?>";
			var page_custom = "<?php echo $zb_site['page_custom']; ?>";
			var sidebars_custom = "<?php echo $zb_site['sidebars_custom']; ?>";
			var comment_custom = "<?php if ($zb_site['comment_custom']!=''){echo $zb_site['comment_custom'];}else{echo 'respond';}; ?>";
		<?php
		if (class_exists( 'woocommerce' ) ) { 
			$zb_site_alterwoo = $zb_site['alter_woo_theme'];
		?>
			var woocommerce = true;
			var is_product = "<?php echo is_product(); ?>";
			var alter_woo_theme_woo_reviews = "<?php echo $zb_site_alterwoo['woo_reviews']; ?>";
			var alter_woo_theme_woo_desc = "<?php echo $zb_site_alterwoo['woo_desc']; ?>";
			var alter_woo_theme_woo_addl = "<?php echo $zb_site_alterwoo['woo_addl']; ?>";
		<?php 
		} else {
		?>
			var woocommerce = false;
			var is_product = "";
			var alter_woo_theme_woo_reviews = "";
			var alter_woo_theme_woo_desc = "";
			var alter_woo_theme_woo_addl = "";
		<?php
		}
		?>
		</script>
<?php
		}
		add_action('wp_head', 'app_meta', 99, 1);
		add_action('wp_enqueue_scripts', 'zb_load_assets',99);
		add_filter('wp_footer','zappbar_inject');
	}
}
?>