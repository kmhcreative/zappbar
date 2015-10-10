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
	//   return $rgb; // returns an array with the rgb values
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
	global $post;
	if (!$post) {	// on search results not found $post doesn't exist and who would share empty search results?
		$social = '';
	} else {
	extract(shortcode_atts(array(
		'type' => 'label',	// text, label (default), small, medium, large
		'include' => '',
		'exclude' => '',
		), $atts));

		if ($include != '' && $include != 'all') {
			$include = strtolower($include);
			$include = explode(",",$include);
		} else {
			$include = array('facebook','twitter','googleplus','reddit','stumbleupon','digg','linkedin','pinterest','delicious','rss','email');	
		}
		if ($exclude != null && $exclude != '') {
			$exclude = strtolower($exclude);
			$exclude = explode(",",$exclude);
		} else {
			$exclude = array();
		}
	$social = '<div class="zb-sharethis '.$type.'">';
	if ( in_array('facebook',$include) && !in_array('facebook',$exclude) ) {
	$social .=  '<a href="http://www.facebook.com/sharer.php?u='.urlencode(get_permalink($post->ID)).'&amp;t='.urlencode(get_the_title($post->ID)).'" title="Share on Facebook" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share facebook"><span>Facebook</span></a>';
	}
	if ( in_array('twitter',$include) && !in_array('twitter',$exclude) ) {
	$social .=  '<a href="http://twitter.com/share?text='.urlencode(get_the_title($post->ID)).'&url='.urlencode(wp_get_shortlink($post->ID)).'" title="Share on Twitter" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share twitter"><span>Twitter</span></a>';
	}
	if ( in_array('googleplus',$include) && !in_array('googleplus',$exclude) ) {	
	$social .=  '<a href="https://plus.google.com/share?url='.urlencode(get_permalink($post->ID)).'" title="Share on Google+" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share google-plus"><span>Google+</span></a>';
	}
	if ( in_array('reddit',$include) && !in_array('reddit',$exclude) ) {	
	$social .=  '<a href="http://www.reddit.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" title="Share on Reddit" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share reddit"><span>Reddit</span></a>';
	}
	if ( in_array('stumbleupon',$include) && !in_array('stumbleupon',$exclude) ) {	
	$social .=  '<a href="http://www.stumbleupon.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" title="Stumble It" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share stumbleupon"><span>Stumble It</span></a>';
	}
	if ( in_array('digg',$include) && !in_array('digg',$exclude) ) {	
	$social .=  '<a href="http://digg.com/submit?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" title="Digg this!" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share digg"><span>Digg this!</span></a>';
	}
	if ( in_array('linkedin',$include) && !in_array('linkedin',$exclude) ) {
	$social .=  '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;title='.urlencode(get_the_title($post->ID)).'&amp;url='.urlencode(wp_get_shortlink($post->ID)).'" title="Share on LinkedIn" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share linkedin"><span>LinkedIn</span></a>';
	}
	if ( in_array('pinterest',$include) && !in_array('pinterest',$exclude) ) {
	$social .=  '<a href="http://pinterest.com/pin/create/button/?url='.urlencode(get_permalink($post->ID)).'&media='.urlencode(wp_get_attachment_url( get_post_thumbnail_id($post->ID) )).'" title="Pin this!" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share pinterest"><span>Pinterest</span></a>';
	}
	if ( in_array('delicious',$include) && !in_array('delicious',$exclude) ) {
	$social .=  '<a href="http://del.icio.us/post?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode(get_the_title($post->ID)).'" title="Bookmark on del.icio.us" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share delicious"><span>Del.icio.us</span></a>';
	}
	if ( in_array('rss',$include) && !in_array('rss',$exclude) ) {
	$social .=  '<a href="'.get_site_url().'/?feed=rss" title="RSS Feed" rel="nofollow" target="_blank" onclick="event.preventDefault();window.open(this.href,\'_blank\',\'height=400,width=700\');" class="zb-share rss-feed"><span>RSS Feed</span></a>';
	}
	if ( in_array('email',$include) && !in_array('email',$exclude) ) {
	$social .=  '<a href="mailto:?subject=Sharing: '.get_the_title($post->ID).'&amp;body=%0AThought you might be interested in this:%0A%0A'.get_the_title($post->ID).'%0A%0A'.urlencode(get_permalink($post->ID)).'%0A%0A" title="Share by E-mail" rel="nofollow" target="_blank" class="zb-share zb-mail"><span>E-mail Link!</span></a>';

	}
	$social .= '</div>';
	}

	return $social;
}
add_shortcode('zb-share', 'zb_share_shortcode');

/*	
	MODE SWITCH (experimental)
	TO-DO: Make this persistent.  
	Drops in a toggle between Desktop and Mobile modes
	Note that this will also likely break scrolling and
	fixed positioning on Android 2.x devices.
*/
function zb_switch_modes( $atts, $content = null ) {
	$switch = '<a href="switch_mode" class="zb-switch" style="display:none;"><span class="sw_desktop">Switch to Desktop View</span></a>';
	return $switch;
}
add_shortcode('zb-switch', 'zb_switch_modes');
// Make shortcodes work in sidebar widgets too!
add_filter('widget_text', 'do_shortcode');
?>