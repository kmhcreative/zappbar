<?php

function zappbar_inject() {
	wp_reset_query();	// need this or it may apply archive bars on non-archive pages!
	$zb_layout = get_option('zappbar_layout');
	global $post, $wp;
	$zb_pages = zb_paginate();
	$share_panel = 0;	// assume no button is assigned
	$left_sidebar = 0;	// to activate any of these
	$right_sidebar= 0;	// panels.
	$left_appmenu = 0;
	$right_appmenu= 0;
	global $share_panel, $left_sidebar, $right_sidebar, $left_appmenu, $right_appmenu, $mp_nav;
		// If MangaPress Plugin is in use we need to get the nav variables IF this is an mp comic page
		if (get_post_type() == 'mangapress_comic' || get_post_type() == 'mangapress_comicpage') {	
			$mp_options = get_option('mangapress_options');
			$group = boolval($mp_options['basic']['group_comics']);
			$by_parent = boolval($mp_options['basic']['group_by_parent']);
			// use mangapress function to get nav
			if (MP_VERSION >= 4) {
				$next_post = \MangaPress\Theme\Functions\get_adjacent_comic(false,$group,$by_parent,'mangapress_series');
				$prev_post = \MangaPress\Theme\Functions\get_adjacent_comic(true,$group,$by_parent,'mangapress_series');
				$last_post = \MangaPress\Theme\Functions\get_boundary_comic(false,$group,$by_parent,'mangapress_series');
				$first_post= \MangaPress\Theme\Functions\get_boundary_comic(true,$group,$by_parent,'mangapress_series');
				$current_page = $post->ID;
				$next_page = !isset($next_post->ID) ? $current_page : $next_post->ID;
				$prev_page = !isset($prev_post->ID) ? $current_page : $prev_post->ID;
					 $last = !isset($last_post->ID) ? $current_page : $last_post->ID;
					 $first= !isset($first_post->ID) ? $current_page : $first_post->ID;
				// get permalinks for buttons
				$first_url = get_permalink($first);
				$last_url  = get_permalink($last);
				$next_url  = get_permalink($next_page);
				$prev_url  = get_permalink($prev_page);
				// disable buttons with current url
				if ($next_url == get_permalink($current_page)) { $next_url = '';}
				if ($prev_url == get_permalink($current_page)) { $prev_url = '';}
				// get whatever page is used by MangaPress for the Archive
				$mp_archive = get_permalink($mp_options['basic']['comicarchive_page']);
			} else {
				$next_post  = mangapress_get_adjacent_comic($group, $by_parent, 'mangapress_series', false, false);
				$prev_post  = mangapress_get_adjacent_comic($group, $by_parent, 'mangapress_series', false, true);
				add_filter('pre_get_posts', '_mangapress_set_post_type_for_boundary');
				$last_post  = mangapress_get_boundary_comic($group, $by_parent, 'mangapress_series', false, false);
				$first_post = mangapress_get_boundary_comic($group, $by_parent, 'mangapress_series', false, true);
				remove_filter('pre_get_posts', '_mangapress_set_post_type_for_boundary');
				$current_page = $post->ID; // use post ID this time.

				$next_page = !isset($next_post->ID) ? $current_page : $next_post->ID;
				$prev_page = !isset($prev_post->ID) ? $current_page : $prev_post->ID;
				$last      = !isset($last_post[0]->ID) ? $current_page : $last_post[0]->ID;
				$first     = !isset($first_post[0]->ID) ? $current_page : $first_post[0]->ID;

				$first_url = get_permalink($first);
				$last_url  = get_permalink($last);
				$next_url  = get_permalink($next_page);
				$prev_url  = get_permalink($prev_page);
				// get whatever page is used by MangaPress for the Archive
				// Note: version 3 uses slug, we need to get ID for permalink
				$mp_archive = $mp_options['basic']['comicarchive_page'];
				$mp_archive = get_page_by_path($mp_archive);
				$mp_archive = get_permalink($mp_archive->ID);
			
			}
				if (!is_comic_archive_page()){
					//	MangaPress uses "Series" which are kind of like "Chapters" I guess
					$chapters = zb_get_term_links('mangapress_series');
					$post_terms = get_the_terms( $post->ID, 'mangapress_series');
					$post_links = [];
					if (!empty($post_terms)){
						foreach($post_terms as $post_term){
							$post_links[] = get_term_link( $post_term->slug, 'mangapress_series');
						}
					}
					$post_link = $post_links[count($post_links)-1]; // we're only interested in the lowest level term
					// now, see what index $post_link is in $chapters
					$link_index = array_search($post_link,$chapters,true);
					if ($link_index+1 < count($chapters)){
						$next_chapter = $chapters[$link_index+1];
					} else {
						$next_chapter = '';
					}
					if ($link_index-1 > -1){
						$prev_chapter = $chapters[$link_index-1];
					} else {
						$prev_chpater = '';
					}
				} else {
					$prev_chapter = '';
					$next_chapter = '';
				}				
			$mp_nav = Array(
				'first_url' => $first_url,
				'prev_url'  => $prev_url,
				'next_url'  => $next_url,
				'last_url'  => $last_url,
				'prev_chapter' => $prev_chapter,
				'next_chapter' => $next_chapter,
				'comic_archive'=> $mp_archive,
			);
		};
	
	function build_zappbars($value,$layout,$position,$paged) {
		global $post, $wp, $mp_nav;
		$xtra = '';
			if ($position == null) { $position = 'top'; };
		$zb_name = array(
			'button_a',
			'button_b',
			'button_c',
			'button_d',
			'button_e'
		);
		$zb_layout = get_option('zappbar_layout');
		if ($zb_layout['button_labels'] == '2') { 
			$notext = ' notext';
		} else { 
			$notext = ''; 
		}
		$html = '<div class="zappbar zb-'.$layout.' '.$position.$notext.'">';
		$x = 0;
		foreach ($value as $val) {
			$html .= '<div class="zb '.$zb_name[$x].' integrated-webcomic">';
			$xtra = ''; // reset extra styling for each loop through
		//	$val[1] = button label text string
		//  $val[2] = button link or action	
			$val[3] = ''; // extra text
			$val[4] = $val[1]; // tooltip string
			// Comic and Specific Archive Pages
			if ( array_filter($paged) ) {
				if ( function_exists('comicpress_display_comic') && comicpress_themeinfo('archive_display_order') == "asc" ) {
					$first_page = $paged[0]; $last_page = $paged[3];
				} else if ( function_exists('comicpress_display_comic') && comicpress_themeinfo('archive_display_order') == "desc" ) {
					$first_page = $paged[3]; $last_page = $paged[0];
				} else { $first_page = $paged[3]; $last_page = $paged[0]; }
				if ($val[2] == 'first_page') {
					$val[2] = $first_page;
					$xtra = '';
				} else if ($val[2] == 'prev_page') {
					$val[2] = $paged[1];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'next_page') {
					$val[2] = $paged[2];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'last_page') {
					$val[2] = $last_page;
					$xtra = '';
				} else {
				};
			}
			// Comic Easel Post 
			if ( get_post_type() == 'comic' || function_exists('ceo_pluginfo') ) {
				if (ceo_pluginfo('navigate_only_chapters')) {
					if ($val[2] == 'prev_chapter') {
						$val[2] = ceo_get_previous_chapter();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'first_comic') {
						$val[2] = ceo_get_first_comic_in_chapter_permalink();
						if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
					} else if ($val[2] == 'prev_comic') {
						$val[2] = ceo_get_previous_comic_in_chapter_permalink();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'next_comic') {
						$val[2] = ceo_get_next_comic_in_chapter_permalink();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'last_comic') {
						$val[2] = ceo_get_last_comic_in_chapter_permalink();
						if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
					} else if ($val[2] == 'next_chapter') {
						$val[2] = ceo_get_next_chapter();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'comic_archive') {
						$val[2] = get_site_url().'/comic';
						$xtra = '';
					} else {
					};
				} else {
					if ($val[2] == 'prev_chapter') {
						$val[2] = ceo_get_previous_chapter();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'first_comic') {
						$val[2] = ceo_get_first_comic_permalink();
						if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
					} else if ($val[2] == 'prev_comic') {
						$val[2] = ceo_get_previous_comic_permalink();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'next_comic') {
						$val[2] = ceo_get_next_comic_permalink();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'last_comic') {
						$val[2] = ceo_get_last_comic_permalink();
						if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
					} else if ($val[2] == 'next_chapter') {
						$val[2] = ceo_get_next_chapter();
						if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
					} else if ($val[2] == 'comic_archive') {
						$val[2] = get_site_url().'/comic';
						$xtra = '';
					} else {
					};
				}
			} 
			// ComicPress Post
			if ( function_exists('comicpress_display_comic')  ) {	
				if ($val[2] == 'prev_chapter') {
					$val[2] = comicpress_get_previous_storyline_start_permalink();
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'first_comic') {
					$val[2] = comicpress_get_first_comic_permalink();
					if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
				} else if ($val[2] == 'prev_comic') {
					$val[2] = comicpress_get_previous_comic_permalink();
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'next_comic') {
					$val[2] = comicpress_get_next_comic_permalink();
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'last_comic') {
					$val[2] = comicpress_get_last_comic_permalink();
					if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
				} else if ($val[2] == 'next_chapter') {
					$val[2] = comicpress_get_next_storyline_start_permalink();
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'comic_archive') {
					$val[2] = get_site_url().'/?cat='.comicpress_themeinfo('comiccat').'/';
					$xtra = '';
				} else {
				};
			} 
			/* 	MangaPress Post
				Note that logic for $vars is in the block on line 15 above
			*/
			if ( get_post_type() == 'mangapress_comic' || get_post_type() == 'mangapress_comicpage'  ) {					
				if ($val[2] == 'prev_chapter') {
					$val[2] = $mp_nav['prev_chapter'];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'first_comic') {
					$val[2] = $mp_nav['first_url'];
					if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
				} else if ($val[2] == 'prev_comic') {
					$val[2] = $mp_nav['prev_url'];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'next_comic') {
					$val[2] = $mp_nav['next_url'];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'last_comic') {
					$val[2] = $mp_nav['last_url'];
					if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
				} else if ($val[2] == 'next_chapter') {
					$val[2] = $mp_nav['next_chapter'];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'comic_archive') {
					$val[2] = $mp_nav['comic_archive'];
					if ($val[2] == ''){ $xtra = ' zb-disabled';} else { $xtra = '';}
				} else {
				};
			} 
			// Webcomic Post
			if ( preg_match('/webcomic/',get_post_type()) ) {
				// Yes, this is a very convoluted way of getting the URLs
				if ($val[2] == 'prev_chapter') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[previous_webcomic_storyline_link]'), $matches);				
					$val[2] = $matches[2][0];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }				
				} else if ($val[2] == 'first_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[first_webcomic_link]'), $matches);				
					$val[2] = $matches[2][0];
					if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
				} else if ($val[2] == 'prev_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[previous_webcomic_link]'), $matches);
					$val[2] = $matches[2][0];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'next_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[next_webcomic_link]'), $matches);
					$val[2] = $matches[2][0];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'last_comic') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[last_webcomic_link]'), $matches);				
					$val[2] = $matches[2][0];
					if($val[2]==get_permalink()){$xtra = ' zb-disabled';} else { $xtra = '';}
				} else if ($val[2] == 'next_chapter') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[next_webcomic_storyline_link]'), $matches);				
					$val[2] = $matches[2][0];
					if($val[2] == ''){$xtra = ' zb-disabled';} else { $xtra = ''; }
				} else if ($val[2] == 'comic_archive') {
					preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', do_shortcode('[the_webcomic_collections]'), $matches);				
					$val[2] = $matches[2][0];
					$xtra = '';							
				} else {
				};
			} 
			// WooCommerce Product Post
			if ( class_exists( 'woocommerce' ) && ( is_shop() || is_product() || is_cart() || is_checkout() || is_account_page() )  ) {
				global $woo_options, $woocommerce;
				if ($val[2] == 'woo_store') {
					$val[2] = get_permalink( woocommerce_get_page_id( 'shop' ) );
				}
				if ($val[2] == 'woo_cart' && ( is_shop() || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
					$val[2] = $woocommerce->cart->get_cart_url();
					$cartcount = sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
					$val[1] = '<span class="amount">'.$woocommerce->cart->get_cart_total().'</span>';
					if ($cartcount != '0') {
						$val[3] = '<span class="contents">'.$cartcount.'</span>';
					};
				} else if ($val[2] == 'woo_review' && is_product() ) {
					global $product;
					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_rating_count() ) ) {
						$val[3] = '<span class="contents">'.$count.'</span>';
					}
				} else if ($val[2] == 'woo_account' && ( is_shop() || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
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
				$val[3] = '</span><span class="search out">
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
			// Single Blog Post
			if ( get_post_type() == 'post'  ) {	
				if ($val[2] == 'previous_post') {
					$val[2] = get_permalink( get_adjacent_post(false,'',true) );
					if ($val[2]==get_permalink()) {$xtra = ' zb-disabled';} else { $xtra=''; }
				} else if ($val[2] == 'next_post') {
					$val[2] = get_permalink( get_adjacent_post(false,'',false) );
					if ($val[2]==get_permalink()) {$xtra = ' zb-disabled';} else { $xtra=''; }
				} else {};
			} 
			// ZappBar Panels
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
			// Contact
			if ($val[2] == 'custom_email') {
				$zb_social = get_option('zappbar_social');
				$val[2] = 'mailto:'.$zb_social['email_address'];
			}
			// All Blog Posts Archive
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
			// Ryuzine Rack
			if ($val[2] == 'ryuzine_rack') {
				$val[2] = esc_url(home_url()).'/ryuzine-rack/';
			}
			// Search Button
			if (in_array($val[2],array('search_box','search_left','search_right'))) {
				if ($val[2] == 'search_left') { 		$shift = ' left"';
				} else if ($val[2] == 'search_right') { $shift = ' right"';
				} else { $shift = ' center';}
				$xtra = " searchbox".$shift;
				$val[3] = '</span><span class="search out">
					<form role="search" method="get" class="search-form" action="'.home_url( '/' ).'">
						<label>
							<span class="screen-reader-text">Search for:</span>
							<input type="search" results=5 class="input-text" placeholder="Search Site" value="" name="s" title="Search for:" />
						</label>
						<input type="submit" class="search-submit" value="Search" />
					</form>
					</span>';
			
			}
			// Specific Social Media Buttons
				if ($zb_layout['logo'] != '') {
					$logo = $zb_layout['logo'];
				} else {
					$logo = '';
				}
				if (is_archive()) {
					$title = get_the_archive_title();
					$permalink = home_url( add_query_arg( array(), $wp->request ) );
					$shortlink = home_url( add_query_arg( array(), $wp->request ) );
					$thumbnail = $logo;
				} else if (is_search()) {
					$title = 'Search results for: '.get_search_query();
					$permalink = get_search_link();
					$shortlink = get_search_link();
					$thumbnail = $logo;
				} else if (is_single() || is_page() ) {
					$title = get_the_title($post->ID);
					$permalink = get_permalink($post->ID);
					$shortlink = wp_get_shortlink($post->ID);
					$thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				} else if (is_home()){
					$title = get_bloginfo( 'name' );
					$permalink = get_site_url();
					$shortlink = get_site_url();
					if ($post) {
					$thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					} else {
					$thumbnail = $logo;
					}
				} else {
					$title = get_bloginfo( 'name' );
					$permalink = get_site_url();
					$shortlink = get_site_url();
					$thumbanail = $logo;
				}
			if ($val[2] == 'commentform') {
				if (get_comments_number() > 0) {
					$val[3] = '<span class="contents">'.get_comments_number().'</span>';
				}
			} else if ($val[2] == 'share_this') {
				global $share_panel;
				$share_panel = 1;
			} else if ($val[2] == 'share_fb') {
				$val[2] = 'http://www.facebook.com/sharer.php?u='.urlencode($permalink).'&amp;t='.urlencode($title).'';
				$xtra = ' zb-social';
			} else if ($val[2] == 'share_twitter') {
				$val[2] = 'http://twitter.com/share?text='.urlencode($title).'&url='.urlencode($shortlink).'';
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_reddit') {
				$val[2] = 'http://www.reddit.com/submit?url='.urlencode($permalink).'&amp;title='.urlencode($title).'';
				$xtra = ' zb-social';											
			} else if ($val[2] == 'share_linkedin') {
				$val[2] = 'http://www.linkedin.com/shareArticle?mini=true&amp;title='.urlencode($title).'&amp;url='.urlencode($shortlink).'';
				$xtra = ' zb-social';			
			} else if ($val[2] == 'share_pinterest') {
				$val[2] = 'http://pinterest.com/pin/create/button/?url='.urlencode($permalink).'&media='.urlencode($thumbnail).'';
				$xtra = ' zb-social';			
			} else {};
			$icon = explode('|',$val[0]);
			// check if it is a JS action button rather than a URL
			if (in_array( $val[2], array('appmenu_left','appmenu_right','share_this','search_box',
										 'search_left','search_right','woo_search','woo_search_left',
										 'woo_search_right','callme','sidebar_left','sidebar_right',
										 'first_page','prev_page','next_page','last_page','commentform',
										 'woo_review','woo_desc','woo_addl'
										)
				) 
			) {
				// make it look like an anchor link for stupid bots that do not obey nofollow
				$val[2] = '#'.$val[2]; 
			}
			if ($zb_layout['button_labels'] == '2') {
				if (!$val[3]) {
					$button_label = '';
				} else {
					$button_label = '<br/><span class="zb-label">'.$val[3].'<span>';
				}
				$tooltip = ' title="'.$val[4].'" ';
			} elseif ($zb_layout['button_labels'] == '1') {
				$button_label = '<br/><span class="zb-label">'.$val[1].$val[3].'</span>';
				$tooltip = ' title="'.$val[4].'" ';			
			} else {
				$tooltip = '';
				$button_label = '<br/><span class="zb-label">'.$val[1].$val[3].'</span>';		
			}
			$html .= '<a href="'.$val[2].'" class="button'.$xtra.'" target="_self" rel="nofollow"'.$tooltip.'><div class="icon '.$icon[0].' '.$icon[1].'"></div>'.$button_label.'</a>';

			$html .= '</div>';
			$x++;
		}
		$html .= '</div>';
		echo $html;
	}
	$zb_site = get_option('zappbar_site');
	if ($zb_site['showon'] != 'none') {
		if ( class_exists('woocommerce') && $zb_layout['use_woo_top_bar']!='no' && (is_shop() || is_product() || is_cart() || is_checkout() || is_account_page()) ) {
			if ($zb_layout['use_woo_top_bar']=='yes'){
				build_zappbars($zb_layout['woo_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
			}
		} else if (
			( 
				(isset($post->post_type) && ($post->post_type == 'comic' || $post->post_type == 'mangapress_comic' || $post->post_type == 'webcomic')) ||
				(function_exists('comicpress_display_comic') && comicpress_in_comic_category() && !is_home() ) ||
				preg_match('/webcomic/',get_post_type())
			)
			&& $zb_layout['use_comic_top_bar']!='no' 
			&& !is_archive()
		) {
			if ($zb_layout['use_comic_top_bar']=='yes') {
				build_zappbars($zb_layout['comic_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
			}
		} else if ( $zb_layout['use_archive_top_bar']!='no' && (is_archive() || is_search()) ) {
			if ($zb_layout['use_archive_top_bar']=='yes'){
				build_zappbars($zb_layout['archive_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
			}
		} else if ( $zb_layout['use_blog_top_bar']!='no' && isset($post->post_type) && $post->post_type == 'post' && !is_archive() && !is_home() ) {
			if ($zb_layout['use_blog_top_bar']=='yes'){
				build_zappbars($zb_layout['blog_top_bar'],$zb_layout['button_layout'],'top',$zb_pages);
			}
		} else {
		build_zappbars($zb_layout['default_top'],$zb_layout['button_layout'],'top',$zb_pages);
		}
		
		if ( class_exists('woocommerce') && $zb_layout['use_woo_bottom_bar']!='no' && is_product() ) {
			if ($zb_layout['use_woo_bottom_bar']=='yes'){
				build_zappbars($zb_layout['woo_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
			}
		} else if (
			( 
				(isset($post->post_type) && ($post->post_type == 'comic' || $post->post_type == 'mangapress_comic')) ||
				(function_exists('comicpress_display_comic') && comicpress_in_comic_category() && !is_home() ) ||
				preg_match('/webcomic/',get_post_type())
			)
			&& $zb_layout['use_comic_bottom_bar']!='no' 
			&& !is_archive()
		) {
			if ($zb_layout['use_comic_bottom_bar']=='yes'){
				build_zappbars($zb_layout['comic_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
			}
		} else if ( $zb_layout['use_archive_bottom_bar']!='no' && (is_archive() || is_search()) ) {	
			if ($zb_layout['use_archive_bottom_bar']=='yes'){
				build_zappbars($zb_layout['archive_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
			}
		} else if ( $zb_layout['use_blog_bottom_bar']!='no' && isset($post->post_type) && $post->post_type == 'post' && !is_archive() && !is_home() ) {
			if ($zb_layout['use_blog_bottom_bar']=='yes'){
				build_zappbars($zb_layout['blog_bottom_bar'],$zb_layout['button_layout'],'bottom',$zb_pages);
			}
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
		if ($share_panel == 1 && !is_404() ) { ?>
		<div id="zappbar_share_this" class="zb-panel right hide"><div class="marginbox">
			<h2>Share this On:</h2>
			<?php
				if ($zb_layout['logo'] != '') {
					$logo = $zb_layout['logo'];
				} else {
					$logo = '';
				}
				if (is_archive()) {
					$title = get_the_archive_title();
					$permalink = home_url( add_query_arg( array(), $wp->request ) );
					$shortlink = home_url( add_query_arg( array(), $wp->request ) );
					$thumbnail = $logo;
				} else if (is_search()) {
					$title = 'Search results for: '.get_search_query();
					$permalink = get_search_link();
					$shortlink = get_search_link();
					$thumbnail = $logo;
				} else if (is_single() || is_page() ) {
					$title = get_the_title($post->ID);
					$permalink = get_permalink($post->ID);
					$shortlink = wp_get_shortlink($post->ID);
					$thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				} else if (is_home()){
					$title = get_bloginfo( 'name' );
					$permalink = get_site_url();
					$shortlink = get_site_url();
					if ($post) {
					$thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					} else {
					$thumbnail = $logo;
					}
				} else {
					$title = get_bloginfo( 'name' );
					$permalink = get_site_url();
					$shortlink = get_site_url();
					$thumbanail = $logo;
				}
				$zb_social = get_option('zappbar_social');
				$zb_social_panel = isset($zb_social['social_panel']) ? $zb_social['social_panel'] : [];
				if ( isset($zb_social_panel['facebook']) && $zb_social_panel['facebook'] != '') {	?>
			<a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode($permalink); ?>&amp;t=<?php echo urlencode($title); ?>" title="Share on Facebook" rel="nofollow" target="_blank" class="zb-social facebook">Facebook</a>
			<?php	};
				if ( isset($zb_social_panel['twitter']) && $zb_social_panel['twitter'] != '') { ?>
			<a href="http://twitter.com/share?text=<?php echo urlencode($title); ?>&url=<?php echo urlencode($shortlink); ?>" title="Share on Twitter" rel="nofollow" target="_blank" class="zb-social twitter">Twitter</a>
			<?php	};
				if ( isset($zb_social_panel['reddit']) && $zb_social_panel['reddit'] != '') { ?>
			<a href="http://www.reddit.com/submit?url=<?php echo urlencode($permalink); ?>&amp;title=<?php echo urlencode($title); ?>" title="Share on Reddit" rel="nofollow" target="_blank" class="zb-social reddit">Reddit</a>
			<?php	};
				if ( isset($zb_social_panel['linkedin']) && $zb_social_panel['linkedin'] != '') { ?>
			<a href="http://www.linkedin.com/shareArticle?mini=true&amp;title=<?php echo urlencode($title); ?>&amp;url=<?php echo urlencode($shortlink); ?>" title="Share on LinkedIn" rel="nofollow" target="_blank" class="zb-social linkedin">LinkedIn</a>
			<?php 	};
				if ( isset($zb_social_panel['pinterest']) && $zb_social_panel['pinterest'] != '') { ?>
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($permalink); ?>&media=<?php echo urlencode($thumbnail); ?>" title="Pin this!" rel="nofollow" target="_blank" class="zb-social pinterest">Pinterest</a>
			<?php 	};
				if ( isset($zb_social_panel['rss']) && $zb_social_panel['rss'] != '') { ?>
			<a href="<?php echo get_site_url(); ?>/?feed=rss" title="RSS Feed" rel="nofollow" target="_blank" class="zb-social rss-feed">RSS Feed</a>
			<?php	};
				if ( isset($zb_social_panel['email']) && $zb_social_panel['email'] != '') { ?>
			<a href="mailto:?subject=Sharing: <?php echo $title; ?>&amp;body=%0AThought you might be interested in this:%0A%0A<?php echo $title; ?>%0A%0A<?php echo urlencode($permalink); ?>%0A%0A<?php echo urlencode($thumbnail); ?>" title="Share by E-mail" rel="nofollow" target="_blank" class="zb-mail">E-mail Link!</a>
			<?php 	};
?>
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
	// needs to run at init action time to make sure that webcomic and comic easel plugins are loaded, they could load *after* zappbar so this ensures they are loaded
	add_action('init', 'zb_init_scripts_and_styles');
}

function zb_init_scripts_and_styles() {
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
		 	if ($zb_site['responsive']=='1') {
				$css = $plugin_dir_url . 'css/site_tweaks.css';
				wp_enqueue_style( 'zb-site-tweaks', $css, '', '');
			}
			if ($zb_site['responsive']=='2' && $zb_site['auto_width'] == 'on') {
				$zb_css1 = $plugin_dir_url . 'css/blank.css';	// <-- auto_width needs to get theme width BEFORE retrofit is applied
			} else {
				$zb_css1 = $plugin_dir_url . 'css/zappbar_'.$zb_site['showon'].'.css';
			}
			wp_enqueue_style( 'zb-response', $zb_css1, '', '');
			$zb_js1 = $plugin_dir_url . 'js/zappbar.js';
			wp_enqueue_script( 'zb-functions', $zb_js1, array( 'jquery' ), '1.0', true );
			if ($zb_site['splash_screen']!='') {
				$zb_js2 = $plugin_dir_url . 'js/jquery.coo.kie.js';
				wp_enqueue_script( 'zb-cookiejar', $zb_js2, array( 'jquery' ), '1.0', true );
			}
			if (is_active_widget('zb_google_translate_widget', false, 'zb_google_translate_widget', true)) {
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
				if ($zb_site['icon2favicon'] == 'on') {
					echo '<link rel="icon" type="image/png" href="'.$favicon.'" />';				
				}
			} else {
				$favicon = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_16x16.png';
				$tablet = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_72x72.png';
				$hi_res = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_114x114.png';
				$phones = $plugin_dir_url.'includes/images/app_icons/wordpress-logo_57x57.png';
			}
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
			// fallback values
			$zb_site_alter = array('header' => '', 'sitenav' => '', 'commentlist' => '', 'commentform' => '', 'push' => '', 'blognav' => '');
			if (isset($zb_site['altertheme'])) {	// one or more is set
				foreach( $zb_site_alter as $key => $value ) {
					if (isset($zb_site['altertheme'][$key])) {	// this one is set
						$zb_site_alter[$key] = $zb_site['altertheme'][$key];
					}
				}
			}
			// now move vars into js config:
?>
		<script type="text/javascript">
			var zb_base = "<?php echo $plugin_dir_url; ?>";
			var showon = "<?php echo $zb_site['showon']; ?>";
			var applyto = "<?php echo $zb_site['applyto']; ?>";
			var wrapper = ['page','page-wide','wrapper'<?php if ($zb_site['page_custom']!=''){ echo ',\''.$zb_site['page_custom'].'\'';}; ?>];
			var is_responsive = "<?php echo $zb_site['responsive']; ?>";
			var auto_width  = "<?php echo get_theme_mod('comicpress-customize-range-site-width') ? 'off' : $zb_site['auto_width']; ?>";
			var theme_width = "<?php echo get_theme_mod('comicpress-customize-range-site-width') ? intval( get_theme_mod('comicpress-customize-range-site-width')) : $zb_site['theme_width']; ?>";
			var telnum = escape("<?php if($zb_social['phone_number']!='') {echo $zb_social['phone_number']; }; ?>");
			var splash = "<?php if($zb_site['splash_screen']!=''){echo $zb_site['splash_screen']; }; ?>";
			var splash_timer = "<?php echo $zb_site['splash_timer']; ?>";
			var splash_link = "<?php echo $zb_site['splash_link']; ?>";
			var comments_open = "<?php if(is_singular()){echo comments_open();} ?>";
			var is_home = "<?php echo is_home(); ?>";
			var is_archive = "<?php echo is_archive(); ?>";
			var header_custom = "<?php echo $zb_site['header_custom']; ?>";
			var nav_custom 	  = "<?php echo $zb_site['nav_custom']; ?>";
			var altertheme_sidebars = "<?php echo $zb_site['sidebars']; ?>";
			var altertheme_header 	= "<?php echo $zb_site_alter['header']; ?>";
			var altertheme_sitenav	= "<?php echo $zb_site_alter['sitenav']; ?>";
			var altertheme_commentlist = "<?php echo $zb_site_alter['commentlist']; ?>";
			var altertheme_commentform = "<?php echo $zb_site_alter['commentform']; ?>";
			var altertheme_push = "<?php echo $zb_site_alter['push']; ?>";
			var altertheme_blognav = "<?php echo $zb_site_alter['blognav']; ?>";
			var page_custom = "<?php echo $zb_site['page_custom']; ?>";
			var sidebars_custom = "<?php echo $zb_site['sidebars_custom']; ?>";
			var comment_custom = "<?php if ($zb_site['comment_custom']!=''){echo $zb_site['comment_custom'];}else{if ($zb_site_alter['commentlist']!='') { echo 'comments'; } else { echo 'respond';}}; ?>";
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
