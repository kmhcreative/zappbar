<?php
/*
Plugin Name: ZappBar
Plugin URI:  https://github.com/kmhcreative/zappbar
Description: Adds mobile-friendly web app navigation and toolbars to any WordPress theme.
Version: 	 0.2.3
Author: 	 K.M. Hansen
Author URI:  http://www.kmhcreative.com
License: 	 GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
GitHub Plugin URI: https://github.com/kmhcreative/zappbar
GitHub Branch: master

Copyright 2012-2018  K.M. Hansen  (email : software@kmhcreative.com)

==== Beta Version Disclaimer =====

This plugin is still being tested is incomplete!  
Do not use it in production unless you can live 
without the things it is missing and are willing 
to accept the possibility that it could screw up 
your website.  Things left on To-Do list:

* auto-update from repository
* make it more awesome!

===================================
*/




/*
	ACTIVATION SETTINGS
*/

function zb_activate($reset = false) {
	// version check - if not using minimum WP version, bail!
	$wp_version = get_bloginfo('version');
	if ($wp_version < 3.5) {
		global $pagenow;
		if ( is_admin() && $pagenow=="plugins.php" ) {
		echo "<div class='error'><p><b>ERROR:</b> ZappBar is <em>activated</em> but requires <b>WordPress 3.5</b> or greater to work.  You are currently running <em>Wordpress <span style='color:red;'>".$wp_version."</span>,</em> please upgrade.</p></div>";
		}
		return;
	};
	// still here? Then lets set defaults!
	if ( $reset===true ) {
		delete_option('zappbar_site');
		delete_option('zappbar_colors');
		delete_option('zappbar_panels');
		delete_option('zappbar_layout');
	}
	$zb_site = get_option('zappbar_site');
	if (empty($zb_site) || $reset == 'zappbar_site' ) {
		$zb_site = array(
			'responsive'	=>	'0',
			'auto_width'	=>	'off',
			'theme_width'	=>	'940',
			'fix_admin'		=>	'no',
			'sidebars'		=>	'1',
			'adminbar'		=>	'off',
			'showon'		=>	'none',
			'applyto'		=>	'all',
			'altertheme'	=> 	array(
								'header'	 =>	'header',
								'sitenav'	 =>	'sitenav',
								'commentform'=>	'commentform',
								'push'		 => 'push',
								'blognav'    => 'blognav'),
			'app_icon'		 => '',
			'icon2favicon'   => '',
			'splash_screen'	 => '',
			'splash_size'	 => 'contain',
			'header_custom'	 =>	'',
			'nav_custom'	 =>	'',
			'comment_custom' =>	'',
			'sidebars_custom'=>	'',
			'comic_nav'		 =>	'',
			'alter_woo_theme'=> array(
								'woo_reviews' =>	'woo_reviews',
								'woo_desc'	  =>	'woo_desc',
								'woo_addl'	  =>	'woo_addl')
		);
		update_option('zappbar_site'	,	$zb_site);
	}
	$zb_social = get_option('zappbar_social');
	if (empty($zb_social) || $reset == 'zappbar_social' ) {
		$zb_social = array(
			'fb_default_img'	=>	'',
			'twitter_id'		=>	'',
			'phone_number'		=>	'',
			'email_address'		=>	'',
			'social_panel'		=>  array(
				'facebook'	=>	'facebook',
				'twitter'	=>	'twitter',
				'google'	=>	'google',
				'reddit'	=>	'reddit',
				'linkedin'	=>	'linkedin',
				'pinterest'	=>	'pinterest',
				'rss'		=>	'rss',
				'email'		=>	'email'
			)
		);
		update_option('zappbar_social' , $zb_social);
	}
	$zb_colors = get_option('zappbar_colors');
	if (empty($zb_colors) || $reset == 'zappbar_colors' ) {
		$zb_colors = array(
			'color_src'			=> 	'basic',
			'custom_styles'		=>	'',
			'bar_bg'			=>	'#ffffff',
			'bar_bg_opacity'	=>	'1.0',
			'button_bg'			=>	'#ffffff',
			'button_bg_opacity'	=>	'1.0',
			'button_hover_bg'	=>	'#cccccc',
			'button_bg_hover_opacity' => '1.0',
			'font_color'		=>	'#333333',
			'font_hover_color'	=>	'#000000',
			'bar_border_color'	=>	'#000000',
			'bar_border_style'	=>	'',
			'bar_border_width'	=>	'',
		);
		update_option('zappbar_colors'	,	$zb_colors);
	};
	$zb_panels = get_option('zappbar_panels');
	if (empty($zb_panels) || $reset == 'zappbar_panels' ) {
		$zb_panels = array(
			'panel_menu'	=>	'0',
			'panel_tabs'	=>	'yes',
			'panel_styles'	=>	'on',
			'panel_bg'		=>	'#ffffff',
			'panel_bg_opacity'	=>	'1.0',
			'panel_button_bg'	=>	'#ffffff',
			'panel_button_bg_opacity'	=>	'1.0',
			'panel_button_hover_bg'		=>	'#cccccc',
			'panel_button_hover_bg_opacity'	=>	'1.0',
			'panel_font_color'	=>	'#333333',
			'panel_font_hover_color'	=>	'#000000',
			'panel_border_color'	=>	'#000000',
			'panel_border_style'	=>	'',
			'panel_border_width'	=>	''
		);
		update_option('zappbar_panels'	,	$zb_panels);
	}
	$zb_layout = get_option('zappbar_layout');
	if (empty($zb_layout) || $reset == 'zappbar_layout' ) {
		$zb_layout = array(
			'button_layout'		=>	'spread',
			'search_button'		=>	'on',
			'logo'				=>	'',
			'default_top'		=>	array(
										array('dashicons|dashicons-menu','Menu','appmenu_left'),
										array('dashicons|dashicons-blank','',''),
										array('dashicons|dashicons-admin-home','Home',get_home_url()),
										array('dashicons|dashicons-blank','', ''),
										array('dashicons|dashicons-search','Search','search_right')
									),
			'default_bottom'	=>	array(
										array('dashicons|dashicons-wordpress','Blog','blogposts'),
										array('dashicons|dashicons-info','About',''),
										array('dashicons|dashicons-admin-comments','Comment','commentform'),
										array('dashicons|dashicons-edit','Contact','mailto:'.get_bloginfo('admin_email')),
										array('dashicons|dashicons-share','Share','share_this')
 										),
			'use_archive_top_bar'	=>	'yes',
			'archive_top_bar'	=>	array(
										array('dashicons|dashicons-menu','Menu','appmenu_left'),
										array('dashicons|dashicons-blank','',''),
										array('dashicons|dashicons-admin-home','Home',get_home_url()),
										array('dashicons|dashicons-blank','', ''),
										array('dashicons|dashicons-search','Search','search_right')
									),
			'use_archive_bottom_bar'=>	'yes',
			'archive_bottom_bar'=>	array(
										array('dashicons|dashicons-arrow-left-alt','First','first_page'),
										array('dashicons|dashicons-arrow-left-alt2','Previous','prev_page'),
										array('dashicons|dashicons-blank','',''),
										array('dashicons|dashicons-arrow-right-alt2','Next', 'next_page'),
										array('dashicons|dashicons-arrow-right-alt','Last','last_page')
									),
			'use_comic_top_bar'	=>	'yes',
			'comic_top_bar'		=>	array(
										array('dashicons|dashicons-menu','Menu','appmenu_left'),
										array('fa|fa-angle-double-left','Prev Chap','prev_comic'),
										array('dashicons|dashicons-admin-home','Home',get_home_url()),
										array('fa|fa-angle-double-right','Next chap', 'next_comic'),
										array('dashicons|dashicons-images-alt2','Archive','comic_archive')
                					),
			'use_comic_bottom_bar'	=>	'yes',
			'comic_bottom_bar'	=>	array(
										array('dashicons|dashicons-arrow-left-alt','First','first_comic'),
										array('dashicons|dashicons-arrow-left-alt2','Previous','prev_comic'),
										array('dashicons|dashicons-admin-comments','Comment','commentform'),
										array('dashicons|dashicons-arrow-right-alt2','Next', 'next_comic'),
										array('dashicons|dashicons-arrow-right-alt','Last','last_comic')
                					),
			'use_woo_top_bar'	=>	'yes',
			'woo_top_bar'		=>	array(
										array('dashicons|dashicons-menu','Menu','appmenu_left'),
										array('dashicons|dashicons-cart','Cart','woo_cart'),
										array('dashicons|dashicons-admin-home','Home',get_home_url()),
										array('dashicons|dashicons-admin-users','Account', 'woo_account'),
										array('dashicons|dashicons-search','Search','woo_search_right')
                					),
			'use_woo_bottom_bar'=>	'yes',
			'woo_bottom_bar'	=>	array(
										array('dashicons|dashicons-products','Store','woo_store'),
										array('dashicons|dashicons-tag','Info','woo_desc'),
										array('dashicons|dashicons-star-filled','Reviews','woo_review'),
										array('fa|fa-tags','More Info', 'woo_addl'),
										array('dashicons|dashicons-share','Share','share_this')
                					)
		);
		update_option('zappbar_layout'	,	$zb_layout);
	};
};
register_activation_hook(__FILE__, 'zb_activate');


// Plugin Info Function
function zappbar_pluginfo($whichinfo = null) {
	global $zappbar_pluginfo;
	if (empty($zappbar_pluginfo) || $whichinfo == 'reset') {
		// Important to assign pluginfo as an array to begin with.
		$zappbar_pluginfo = array();
		$zappbar_addinfo = array(
				// plugin directory/url
				'plugin_file' => __FILE__,
				'plugin_url' => plugin_dir_url(__FILE__),
				'plugin_path' => plugin_dir_path(__FILE__),
				'plugin_basename' => plugin_basename(__FILE__),
				'version' => '0.2.2'
		);
		// Combine em.
		$zappbar_pluginfo = array_merge($zappbar_pluginfo, $zappbar_addinfo);
	}
	if ($whichinfo) {
		if (isset($zappbar_pluginfo[$whichinfo])) {
			return $zappbar_pluginfo[$whichinfo];
		} else return false;
	}
	return $zappbar_pluginfo;
}

if ( is_admin() ) {
	// We are on the back end
	@require('functions/admin_functions.php');
	@require('functions/utility_functions.php');
	@require('functions/class.settings-api.php');
	@require('functions/aq_resizer.php');
	@require('options/zappbar_options.php');
} else {
	// We are on the front end
	@require('functions/utility_functions.php');
	@require('functions/aq_resizer.php');
	@require('includes/html_inject.php');
}

// Load all the widgets
foreach (glob(plugin_dir_path(__FILE__)  . 'widgets/*.php') as $widgefile) {
	require_once($widgefile);
}
$zb_site = get_option('zappbar_site');

if ($zb_site['adminbar']=='on') { // "on" means do not show bar
	add_filter('show_admin_bar', '__return_false');
	
	function remove_admin_bar_space () {
    	remove_action( 'wp_head', '_admin_bar_bump_cb' );
	}
	add_action( 'admin_bar_init', 'remove_admin_bar_space' );
}

?>