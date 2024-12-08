<?php
/*
	UTILITY FUNCTIONS
*/
	// Convert HEX color codes to RGB //
	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return implode(",", $rgb); // returns the rgb values separated by commas
	}
// Flattens hierachical taxonomy terms while preserving term parameters
function zb_get_flat_terms( $taxonomy = 'category', $parent = 0, $hide_empty = 0 ){
	$args = array(
		'hierarchical' => 1,
		'show_option_none' => '',
		'hide_empty' => $hide_empty,
		'parent' => $parent,
		'taxonomy' => $taxonomy
	);
	$terms = get_terms($args);
	$list = [];
	foreach($terms as $term) {
		$list[] = $term;
		$subcats = zb_get_flat_terms($taxonomy,$term->term_id);
		foreach($subcats as $sub){
			$list[] = $sub;
		}
	};
	return $list;
};
// To get Previous/Next Category Term Links
function zb_get_term_links( $taxonomy ){
	$links = [];
	$terms = zb_get_flat_terms($taxonomy);
	foreach( $terms as $term ){
		$links[] = get_term_link( $term->slug, $term->taxonomy );
	}
	return $links;
}

// Pagination Links //
function zb_paginate() {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => true,
		'type' => 'array',
		'next_text' => '&raquo;',
		'prev_text' => '&laquo;'
		);
	
	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	
	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
	
	$pages = paginate_links( $pagination );
	$links = array();
		// first page $links[0]
		if (isset($pages)) {	// make sure there are links first!
		preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', $pages[1], $url);
		$links[] = $url[2][0];
		// previous page $links[1]
		preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', $pages[0], $url);
		if (count($url[2])==0) { $links[] = '';} else {
		$links[] = $url[2][0];		
		}
		// next page $links[2]
		preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', $pages[(count($pages)-1)], $url);
		if (count($url[2])==0) { $links[] = '';} else {
		$links[] = $url[2][0];		
		}
		// last page $links[3]
		preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', $pages[(count($pages)-2)], $url);
		$links[] = $url[2][0];
		}
		return $links;
};

function zb_share_shortcode( $atts, $content = null ) {
	$zb_layout = get_option('zappbar_layout');
	if ($zb_layout['logo'] != '') {
		$logo = $zb_layout['logo'];
	} else {
		$logo = '';
	}
	global $post, $wp;
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
		return;
	}
	extract(shortcode_atts(array(
		'type' => 'label',	// text, label (default), small, medium, large
		'include' => '',
		'exclude' => '',
		), $atts));

		if ($include != '' && $include != 'all') {
			$include = strtolower($include);
			$include = explode(",",$include);
		} else {
			$include = array('facebook','threads','bluesky','mastodon','tumblr','reddit','linkedin','pinterest','rss','email');	
		}
		if ($exclude != null && $exclude != '') {
			$exclude = strtolower($exclude);
			$exclude = explode(",",$exclude);
		} else {
			$exclude = array();
		}
	$social = '<div class="zb-sharethis '.$type.'">';
	if ( in_array('facebook',$include) && !in_array('facebook',$exclude) ) {
	$social .=  '<a href="http://www.facebook.com/sharer.php?u='.urlencode($permalink).'&amp;t='.urlencode($title).'" title="Share on Facebook" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share facebook"><span>Facebook</span></a>';
	}
	if ( in_array('threads',$include) && !in_array('threads',$exclude) ){
	$social .= '<a href="https://www.threads.net/intent/post?text='.urlencode($title).'%0A%0Z'.urlencode($permalink).'" title="Share on Threads" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share threads"><span>Threads</span></a>';
	}
	if ( in_array('bluesky',$include) && !in_array('bluesky',$exclude) ) {
	$social .=  '<a href="http://bsky.app/intent/compose?text='.urlencode($title).'%20$'.urlencode($shortlink).'" title="Share on Bluesky" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share bluesky"><span>Bluesky</span></a>';
	}
	if ( in_array('tumblr',$include) && !in_array('tumblr',$exclude) ){
	$social .= '<a href="http://tumblr.com/widgets/share/tool?canonicalUrl='.urlencode($permalink).'" title="Share on Tumblr" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share tumblr"><span>Tumblr</span></a>';
	}
	if ( in_array('mastodon',$include) && !in_array('mastodon',$exclude) ){
	$social .= '<a href="'.$permalink.'" title="Share on Mastodon" rel="nofollow" target="_blank" onclick="event.preventDefault();some.share(this.href);event.stopImmediatePropagation();" class="zb-share mastodon"><span>Mastodon</span></a>';
	}	
	if ( in_array('reddit',$include) && !in_array('reddit',$exclude) ) {	
	$social .=  '<a href="http://www.reddit.com/submit?url='.urlencode($permalink).'&amp;title='.urlencode($title).'" title="Share on Reddit" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share reddit"><span>Reddit</span></a>';
	}
	if ( in_array('linkedin',$include) && !in_array('linkedin',$exclude) ) {
	$social .=  '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;title='.urlencode($title).'&amp;url='.urlencode($shortlink).'" title="Share on LinkedIn" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share linkedin"><span>LinkedIn</span></a>';
	}
	if ( in_array('pinterest',$include) && !in_array('pinterest',$exclude) ) {
	$social .=  '<a href="http://pinterest.com/pin/create/button/?url='.urlencode($permalink).'&media='.urlencode($thumbnail).'" title="Pin this!" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share pinterest"><span>Pinterest</span></a>';
	}
	if ( in_array('rss',$include) && !in_array('rss',$exclude) ) {
	$social .=  '<a href="'.get_site_url().'/?feed=rss" title="RSS Feed" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share rss-feed"><span>RSS Feed</span></a>';
	}
	if ( in_array('email',$include) && !in_array('email',$exclude) ) {
	$social .=  '<a href="mailto:?subject=Sharing: '.$title.'&amp;body=%0AThought you might be interested in this:%0A%0A'.$title.'%0A%0A'.urlencode($permalink).'%0A%0A'.urlencode($thumbnail).'" title="Share by E-mail" rel="nofollow" target="_blank" class="zb-share zb-mail"><span>E-mail Link!</span></a>';

	}
	$social .= '</div>';

	return $social;
}
add_shortcode('zb-share', 'zb_share_shortcode');
/*	
	MODE SWITCH (experimental)
	TO-DO: Make this persistent.  
	Drops in a toggle between Desktop and Mobile modes
	Note that this will also likely break scrolling and
	fixed positioning on Android 2.x devices.
	
	Also, it is pointless if you have Zappbars set to display
	for All Devices because there wouldn't be a "desktop" mode.
*/
function zb_switch_modes( $atts, $content = null ) {
	$switch = '<a href="switch_mode" class="zb-switch" style="display:none;"><span class="sw_desktop">Switch to Desktop View</span></a>';
	return $switch;
}
add_shortcode('zb-switch', 'zb_switch_modes');
// Make shortcodes work in sidebar widgets too!
add_filter('widget_text', 'do_shortcode');
?>