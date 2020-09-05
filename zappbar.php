<?php
/*
Plugin Name: ZappBar
Plugin URI:  https://github.com/kmhcreative/zappbar
Description: Adds mobile-friendly web app navigation and toolbars to any WordPress theme.
Version: 	 0.2.6
Author: 	 K.M. Hansen
Author URI:  http://www.kmhcreative.com
License: 	 GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Copyright 2012-2020  K.M. Hansen  (email : software@kmhcreative.com)

==== Beta Version Disclaimer =====

This plugin is still being tested.  
Do not use it in production unless you are willing 
to accept the possibility that it could screw up 
your website while activated.  The alterations are
non-destructive, so if it causes problems for you
deactivating this plugin should restore your site 
to the way it was.

===================================
*/

/* Minimum Version Checks */

	function zb_wp_version_check(){
		// if not using minimum WP and PHP versions, bail!
		$wp_version = get_bloginfo('version');
		global $pagenow;
		if ( is_admin() && $pagenow=="plugins.php" && ($wp_version < 3.5 || PHP_VERSION < 5.6) ) {
			echo "<div class='notice notice-error is-dismissible'><p><b>ERROR:</b> ZappBar is <em>activated</em> but requires <b>WordPress 3.5</b> and <b>PHP 5.6</b> or greater to work.  You are currently running <b>Wordpress <span style='color:red;'>".$wp_version."</span></b> and <b>PHP <span style='color:red;'>".PHP_VERSION."</span></b>. Please upgrade.</p></div>";
			return;
		}
	};
	add_action('admin_notices', 'zb_wp_version_check');


/*
	ACTIVATION SETTINGS
*/

function zb_activate($reset = false) {
	$options = array(
		'zappbar_site' => array(
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
								'commentlist'=> '',
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
			'page_custom'    => '',
			'sidebars_custom'=>	'',
			'other_elements' => '',
			'comic_nav'		 =>	'',
			'alter_woo_theme'=> array(
								'woo_reviews' =>	'woo_reviews',
								'woo_desc'	  =>	'woo_desc',
								'woo_addl'	  =>	'woo_addl',
								'woo_big'	  =>	'')
		),

		'zappbar_social' => array(
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
		),
		
		'zappbar_colors' => array(
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
		),
		
		'zappbar_panels' => array(
			'panel_menu'	=>	'0',
			'panel_tabs'	=>	'yes',
			'panel_styles'	=>	'on',
			'panel_bg'		=>	'#ffffff',
			'panel_bg_opacity'	=>	'1.0',
			'panel_button_bg'	=>	'#ffffff',
			'panel_button_bg_opacity'	=>	'1.0',
			'panel_button_hover_bg'		=>	'#cccccc',
			'panel_button_hover_bg_opacity'	=>	'1.0',
			'panel_button_font_color'	=>	'#333333',
			'panel_button_font_hover_color'	=>	'#000000',			
			'panel_font_color'	=>	'#333333',
			'panel_font_hover_color'	=>	'#000000',
			'panel_border_color'	=>	'#000000',
			'panel_border_style'	=>	'',
			'panel_border_width'	=>	''
		),
		
		'zappbar_layout' => array(
			'button_layout'		=>	'spread',
			'search_button'		=>	'on',
			'logo'				=>	'',
			'button_labels'		=>  '0',
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
			'use_blog_top_bar'  =>  'no',
			'blog_top_bar' 		=>  array(
										array('dashicons|dashicons-menu','Menu','appmenu_left'),
										array('dashicons|dashicons-blank','',''),
										array('dashicons|dashicons-admin-home','Home',get_home_url()),
										array('dashicons|dashicons-blank','', ''),
										array('dashicons|dashicons-wordpress','Blog','blog_posts')
									),
			'use_blog_bottom_bar'=> 'no',
			'blog_bottom_bar'	 => array(
										array('dashicons|dashicons-arrow-left-alt2','Previous','previous_post'),
										array('dashicons|dashicons-blank','',''),
										array('dashicons|dashicons-admin-comments','Comment','commentform'),
										array('dashicons|dashicons-blank','', ''),
										array('dashicons|dashicons-arrow-right-alt2','Next','next_post')
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
										array('fa|fa-tags','Options', 'woo_addl'),
										array('dashicons|dashicons-share','Share','share_this')
                					)
		)
	);
	if ( $reset===true ) {
		delete_option('zappbar_site');
		delete_option('zappbar_colors');
		delete_option('zappbar_panels');
		delete_option('zappbar_layout');
	}
	foreach ($options as $section => $settings) {
		$dbcheck = get_option($section);	// get the section from the database
		foreach ($settings as $key => &$value) { // & passes as reference
			if (isset($dbcheck[$key])) {	// if the option is set
				if ($dbcheck[$key] != $value){  // if value is no default
					$value = $dbcheck[$key];	// update value to custom setting
				}
			} else {
				// option is not set, use default
			}
		}
		update_option($section,$settings);
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
				'version' => '0.2.6'
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
	@require('plugin-update-checker/plugin-update-checker.php');
		$ZappBarUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/kmhcreative/zappbar',
			__FILE__,'zappbar'
		);
		$ZappBarUpdateChecker->getVcsApi()->enableReleaseAssets();
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

if (isset($zb_site['adminbar']) && $zb_site['adminbar']=='on') { // "on" means do not show bar
	add_filter('show_admin_bar', '__return_false');
	
	function remove_admin_bar_space () {
    	remove_action( 'wp_head', '_admin_bar_bump_cb' );
	}
	add_action( 'admin_bar_init', 'remove_admin_bar_space' );
}

?>