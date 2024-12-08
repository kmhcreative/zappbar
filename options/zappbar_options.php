<?php

function zappbar_icon_picker_scripts() {
	$plugin_dir_url = zappbar_pluginfo('plugin_url');
	$css1 = $plugin_dir_url . 'css/admin_styles.css';
	wp_enqueue_style( 'zb_admin_styles', $css1, '', '');

    $css2 = $plugin_dir_url . 'icon-picker/css/icon-picker.css';
    wp_enqueue_style( 'icon-picker', $css2, array( 'dashicons' ), '1.0' );

	$font1 = $plugin_dir_url . 'fonts/genericons/genericons.css';
	wp_enqueue_style( 'genericons', $font1, '', '');
    
    $font2 = $plugin_dir_url . 'fonts/font-awesome/css/font-awesome.css';
    wp_enqueue_style( 'font-awesome', $font2,'','');

    $js = $plugin_dir_url . 'icon-picker/js/icon-picker.js';
    wp_enqueue_script( 'icon-picker', $js, array( 'jquery' ), '1.0' );
    
    $wp_version = get_bloginfo('version');
	if ($wp_version < 3.5) {
    	// Pre WP 3.5 uploaders
    	wp_enqueue_script('media-upload');
    	wp_enqueue_script('thickbox');
	} else {
		// Media Uploader for WP 3.5+ //
        wp_enqueue_media();	
    }
    // MangaPress 3 messes up ZappBar Layout 
       wp_dequeue_style('mangapress-icons');   
}
// Make sure we only enqueue on our options page //
global $pagenow;
if ($pagenow=="options-general.php" && isset( $_GET['page'] ) && $_GET['page'] == "zappbar_settings"  ) {
	add_action( 'admin_enqueue_scripts', 'zappbar_icon_picker_scripts', 100 ); // high priority is to remove MangaPress 3 styles
}

$sitelayout = get_option('zappbar_site');
if ($sitelayout['fix_admin']=='yes') {
	function admin_tweaks(){
		echo '<link rel="stylesheet" type="text/css" media="all" href="'.zappbar_pluginfo('plugin_url').'css/admin_tweaks.css'.'"/>';
	}
	add_action( 'admin_print_scripts', 'admin_tweaks',99 );
}



if ( !class_exists('ZB_Settings_API_Test' ) ):
class ZB_Settings_API_Test {

    private $settings_api;

    function __construct() {
        $this->settings_api = new ZB_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
    	if (current_user_can('manage_options')){
        add_options_page( 'ZappBar', 'ZappBar', 'manage_options', 'zappbar_settings', array($this, 'plugin_page') );
        }
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'zappbar_site',
                'title' => __( 'Site Layout', 'zbopts' )
            ),
            array(
            	'id' => 'zappbar_social',
            	'title' => __( 'Social Media', 'zbopts' )
            ),
            array(
                'id' => 'zappbar_colors',
                'title' => __( 'ZappBar Colors', 'zbopts' )
            ),
            array(
            	'id' => 'zappbar_panels',
            	'title' => __( 'ZappBar Panels', 'zbopts' )
            ),
            array(
                'id' => 'zappbar_layout',
                'title' => __( 'ZappBar Layout', 'wpuf' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
		$plugin_dir_url = zappbar_pluginfo('plugin_url');
		$cp_msg = get_theme_mod('comicpress-customize-range-site-width') ?
                array(
                	'name' => 'cp_auto_width',
                	'label' => 'Note:',
                	'type' => 'paragraph',
                	'desc' => __( 'Retrofit width of ComicPress 4.3 theme is automatically synchronized with <em>Appearance --&gt; Customize</em> setting.  Please change it there instead.','zbotps')
                ) : array(
                	'name' => 'cp_auto_width',
                	'label' => '',
                	'type' => 'paragraph'
                );
        $settings_fields = array(
            'zappbar_site' => array(
                array(
                    'name' => 'responsive',
                    'label' => __( 'Theme Layout', 'zbopts' ),
                    'type' => 'radio',
                    'default' => '0',
                    'options' => array(
                        '0' => 'Theme is already responsive',
                        '1' => 'Tweak theme to be responsive when ZappBars are displayed',
                        '2' => 'Retrofit to responsive theme even when ZappBars are not displayed'
                    )
                ),
                array(
                    'name' => 'auto_width',
                    'label' => __( 'Auto-detect Theme Width', 'zbopts' ),
                    'desc' => __( 'ZappBar can try to auto detect theme width before applying Retrofit (ignored if not retrofitting)', 'zbopts' ),
                    'default' => 'on',
                    'type' => 'checkbox'
                ),
                array(
                	'name' => 'theme_width',
                	'label' => __( 'Manual Theme Width', 'zbopts'),
                	'type' => 'number',
                	'default' => '940',
                	'size' => '5',
                	'desc' => __( 'If retrofitting and auto-detect is off, what is the theme width in pixels?', 'zbopts' )
                ),
                $cp_msg,
                array(
                    'name' => 'sidebars',
                    'label' => __( 'Theme Sidebars', 'zbopts' ),
                    'type' => 'radio',
                    'default' => '1',
                    'options' => array(
                        '0' => 'Try to MOVE sidebar(s) below main content on mobile',
                        '1' => 'Try to HIDE sidebar(s) but only from narrow mobile screens',
                        '2' => 'Try to HIDE sidebar(s) whenever ZappBars are displayed'
                    ),
                    'desc' => __( 'Ignored if setting above indicates theme is already responsive', 'zbopts')
                ),
                array(
                    'name' => 'fix_admin',
                    'label' => __( 'Admin Mobile Layout', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Tweak Admin Layout',
                        'no' => 'Use Admin Default'
                    ),
                    'desc' => __('Tries to make back-end Admin layout more phone-friendly (WordPress 3.8.1+ only)','zbopts'),
                ),
                array(
                    'name' => 'adminbar',
                    'label' => __( 'WP Admin Bar', 'zbopts' ),
                    'desc' => __( 'Disable Admin Toolbar on Front-End', 'zbopts' ),
                    'default' => 'off',
                    'type' => 'checkbox'
                ),
                array(
                    'name' => 'showon',
                    'label' => __( 'ZappBars On Screen Size', 'zbopts' ),
                    'desc' => __( 'Determined by rendered width in browser not device or device screen size.', 'zbopts' ),
                    'type' => 'radio',
					'default' => 'none',
                    'options' => array(
                    	'none' => 'None (to disable during set-up)',
                        'phones' => '< &nbsp;&nbsp;736px wide (Phones)',
                        'idevices' => '< 1024px wide (Tablets & Phones)',
                        'tablets' => '< 1280px wide (Tablets & Phones)',
                        'tablets_hd' => '< 1440px wide (HD Tablets & Phones)',
                        'desktops' => '< 1920px wide (Phones, Tablets, 720p HD Desktops)',
                        'desktops_hd' => 'All Screen Sizes'
                    )
                ),
                array(
                	'name' => 'applyto',
                	'label' => __( 'Apply ZappBars On', 'zbopts' ),
                	'desc' => __( '"Mobile Only" uses device detection, which can be spoofed. "Force" ignores screen size.', 'zbopts'),
                	'type' => 'radio',
                	'default' => 'all',
                	'options' => array(
                		'all' => 'ALL Devices by Screen Size',
                		'only_mobile' =>  'ONLY Mobile Devices by Screen Size',
                		'only_mobile_forced' => 'ONLY Mobile Devices & FORCE ZappBars',
                		'force_mobile' => 'Desktops by Screen Size & FORCE Mobile Devices to use ZappBars',
                	)
                ),
               array(
                    'name' => 'altertheme',
                    'label' => __( 'When ZappBars are Included', 'zbopts' ),
                    'desc' => __( 'If header section is hidden and site navigation is inside it, site navigation will be automatically hidden as well.<br/>If you hide blog navigation you should set ZappBar buttons for it.', 'zbopts' ),
                    'type' => 'multicheck',
           			'default' => array('header' => '', 'sitenav' => '', 'commentlist' => '', 'commentform' => '', 'push' => '', 'blognav' => ''),
                    'options' => array(
                        'header' => 'Hide Entire &lt;header&gt; Section',
                        'sitenav' => 'Hide regular site navigation',
                        'commentlist' => 'Convert Comment Section to App Panel (includes Comment Form)',
                        'commentform' => 'Convert Comment Form to App Panel (overridden if Comment Section is checked)',
                        'push' => 'Push #page element over when App Panel is open',
                        'blognav' => 'Hide Blog Navigation links'
                    )
                ),
                array(
                    'name' => 'app_icon',
                    'label' => __( 'App Icon Image', 'zbopts' ),
                    'desc' => __( 'Select an image to be used as an "App Icon" when your site is bookmarked to the home screen of a phone or tablet. 
                    The image will be automatically cropped/resized for each of the required app icon sizes.  If you leave this blank a generic WordPress icon 
                    will be used instead.', 'zbopts' ),
                    'type' => 'media',
                    'button' => __('Choose Image'),
                    'default' => ''
                ),
                array(
                    'name' => 'icon2favicon',
                    'label' => __( '', 'zbopts' ),
                    'desc' => __( 'Use App Icon as site Favicon (over-rides any set in Theme)' ),
                    'default' => 'off',
                    'type' => 'checkbox'
                ),
                array(
                    'name' => 'splash_screen',
                    'label' => __( 'Splash Image', 'zbopts' ),
                    'desc' => __( 'Select an image to use on the App Splash Screen.  This will show only at the start of a 
                    new user session, centered and scaled, against whatever color you have set for panel backgrounds.  
                    If you leave this blank the Splash Screen is disabled.', 'zbopts' ),
                    'type' => 'media',
                    'button' => __('Choose Image'),
                    'default' => ''
                ),
               array(
                    'name' => 'splash_size',
                    'label' => __( 'Splash Image Fit', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'auto',
                    'options' => array(
                    	'auto' => 'Do NOT Scale Splash Image, use original size',
                        'contain' => 'Scale Splash Image to FIT if image is larger than screen space',
                        'cover' => 'Scale Splash Image to FILL all available screen space'
                    ),
                    'desc' => __('No scaling may not fit on screen.  "Fit" may show some of the background color around it; "Fill" may crop off parts of the image 
                    (this setting is ignored if no Splash Image is set above)','zbopts')
                ),
                array(
                    'name' => 'splash_timer',
                    'label' => __( 'Splash Timer', 'zbopts' ),
                    'desc' => __( 'Number of seconds to show Splash Screen before clearing it automatically<br/>
                    (Users can always clear it sooner by clicking on it)', 'zbopts' ),
                    'type' => 'select',
                    'default' => '5000',
                    'options' => array(
                        '3000' => '3 seconds',
                        '5000' => '5 seconds',
                        '10000' => '10 seconds',
                        '15000' => '15 seconds'
                    )
                ),
                array(
                	'name' => 'splash_link',
                	'label' => __( 'Splash Link', 'zbopts'),
                	'desc' => __( '(optional)<br/>If using Splash Screen to show advertising, provide target link here.<br/>URL should include http:// and domain name.' , 'zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
           
                array(
                	'name' => 'custom_explain',
                	'label' => 'About Custom Settings',
                	'type' => 'paragraph',
                	'desc' => __( '
                		The settings above expect the theme to be using the most common element identifiers. 
                		However a theme designer might assign any ID or class name to a given element, in 
                		which case - if a selected layout option above is not working - you will need to look 
                		at the source code for the theme you are using and in the boxes below provide the correct IDs/class names 
                		so ZappBar can target them. <strong>NOTE: If "Theme Layout" above is set to "Theme is already repsonsive" the entries below are ignored!</strong><hr/>','zbotps')
                ),
                array(
                	'name' => 'header_custom',
                	'label' => __( 'Header target', 'zbopts'),
                	'desc' => __( '<br/>(optional) ID or class name of theme header element to hide (if set to hide).','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'nav_custom',
                	'label' => __( 'Navigation target', 'zbopts'),
                	'desc' => __( '<br/>(optional) ID or class name of theme nav element to hide (if set to hide).','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'comment_custom',
                	'label' => __( 'Comment target', 'zbopts'),
                	'desc' => __( '<br/>(optional) ID or class name of comment form element if you set it to convert to a panel and it is not doing so.','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'page_custom',
                	'label' => __( 'Page target', 'zbopts'),
                	'desc' => __( '<br/>(optional) ID of main page container (but NOT &gt;body&lt; tag) to push over - if set to push over on App Panel open.','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'sidebars_custom',
                	'label' => __( 'Sidebar targets', 'zbopts'),
                	'desc' => __( '<br/>(optional) Comma-separated list of element IDs/Classes to treat as sidebars (include "." if class or "#" if ID).','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'other_elements',
                	'label' => __( 'Always Hide', 'zbopts'),
                	'desc' => __( '<br/>(optional) Comma-separated list of element IDs/Classes to hide when ZappBars are displayed, <em>even if the theme is already responsive</em> (include "." if class or "#" if ID)','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                 array(
                	'name' => 'plugins_explain',
                	'label' => 'Theme & Plugin Settings',
                	'type' => 'paragraph',
                	'desc' => __( '
                		Any additional settings that appear below will allow you to customize settings or appearance of certain supported themes and other plugins while ZappBars are being displayed.<hr/>','zbotps')
                )
            ),
            'zappbar_social' => array(
                 array(
                	'name' => 'social_explain',
                	'label' => 'About Meta Tags',
                	'type' => 'paragraph',
                	'desc' => __( '
                		The following two items can inject &lt;meta&gt; tags for both Facebook and Twitter 
                		into the &lt;head&gt; of your site pages.  ZappBar injects content late so these 
                		may not work if your theme already has, or another plugin (such as Yoast SEO) injects 
                		&lt;meta&gt; for these earlier.','zbotps')
                ), 
                array(
                	'name' => 'zb_seo_meta',
                	'label' => __( 'Social Meta', 'zbopts' ),
                	'desc' => __( 'Include social media &lt;meta&gt; tags in site HEAD', 'zbopts'),
                	'default' => 'off',
                	'type' => 'checkbox'                
                ),
                array(
                    'name' => 'fb_default_img',
                    'label' => __( 'Default Facebook Image', 'zbopts' ),
                    'desc' => __( 'If the post has no "Featured Image" this image will be displayed as the thumbnail when somebody shares on Facebook.<br/>(If left blank ZappBar will not inject any Facebook &lt;meta&gt; tags).', 'zbopts' ),
                    'type' => 'media',
                    'button' => __('Choose Image'),
                    'default' => ''
                ),
                array(
                	'name' => 'mastodon_id',
                	'label' => __( 'Mastodon ID', 'zbopts'),
                	'desc' => __( '<br/>Enter the @username@instance ID associated with your Mastodon account and ZappBar will add self-verification code to your blog.  Then go to your Mastodon account profile and SAVE it.  Your Mastodon Profile should now have a green checkmark.  (Leave this blank and no verification code will be added).','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'phone_number',
                	'label' => __( 'Phone Number', 'zbopts'),
                	'desc' => __( '<br/>(optional) If you intend to link a ZappBar button to the "Phone" action this is the number that will be inserted into the on-screen notice box.','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
                array(
                	'name' => 'email_address',
                	'label' => __( 'E-Mail Address', 'zbopts'),
                	'desc' => __( '<br/>(optional) If you wire a ZappBar button to "E-Mail Contact" this is the e-mail address to which the message will be sent<br/>
                	If you want people to email the site administrator set your button action to "E-Mail Admin" and it will use the address 
                	on the <em>Settings > General</em> page.','zbopts'),
                	'type' => 'text',
                	'default' => ''
                ),
               array(
                    'name' => 'social_panel',
                    'label' => __( 'Social Media Panel', 'zbopts' ),
                    'desc' => __( 'If you have linked a button to the social sharing panel this is where you can set which options appear in the panel.  You cannot
                    deselect all of them, however if you are usin site-specific social media buttons on your ZappBars this list is ignored.  It is also ignored by the shortcode since 
                    each instance can display a different list of sites.', 'zbopts' ),
                    'type' => 'multicheck',
                    'default' => array(
                    		'email'		=>	'email',
                    		'facebook'	=>	'facebook',
                    		'linkedin'	=>	'linkedin',
                    		'mastodon'	=>	'mastodon',
                    		'pinterest'	=>	'pinterest',
                    		'reddit'	=>	'reddit',
                    		'rss'		=>	'rss',
                    		'tumblr'	=>	'tumblr',
                    		'bluesky'	=>	'bluesky',
                    		'threads'	=>	'threads'
                    	),
                    'options' => array(
                    	'email'		=>	'E-mail Share (email)',
						'facebook'	=>	'Facebook (facebook)',
						'linkedin'	=>	'LinkedIn (linkedin)',
						'mastodon'	=> 	'Mastodon (mastodon)',
						'pinterest'	=>	'Pinterest (pinterest)',
						'reddit'	=>	'Reddit (reddit)',
						'rss'		=>	'RSS Feed (rss)',
						'tumblr'	=>	'Tumblr (tumblr)',
						'bluesky'	=> 	'Bluesky (bluesky)',
						'threads'	=>	'Threads (threads)'
                    )
                ),
                 array(
                	'name' => 'social_shortcode',
                	'label' => 'Social Shortcode',
                	'type' => 'paragraph',
                	'desc' => __( '
                		There is also a <em><strong>[zb-share]</strong></em> shortcode available for dropping in social media links whereever you 
                		might need them.  This is really lightweight, does not cue any scripts or load remote IFRAME 
                		content.  The shortcode accepts the following (optional) parameters:<br/>
                		<ul style="padding: 0 40px;list-style: disc;">
                			<li><strong>type="text"</strong> (simple text-only links)</li>
                			<li><strong>type="label"</strong> (buttons with both a small icon and a text label.  This is the default if type is omitted)</li>
                			<li><strong>type="small"</strong>	(16x16 icons as buttons, spaced far enough apart to be clickable on mobile devices)</li>
                			<li><strong>type="medium"</strong> (24x24 icons as buttons)</li>
                			<li><strong>type="large"</strong>	(32x32 icons as buttons)</li>
                			<li><strong>include="threads,linkedin..."</strong> a comma-separated list limiting which social media sites are included</li>
                			<li><strong>exclude="facebook,pinterest..."</strong> a comma-separated list of which social media sites to exclude</li>
                		</ul>
						The social shortcode will work even if the ZappBars are set to "Display on: None" under the Site settings.  The names to enter 
						in include/exclude lists are in parenthesis above in the Social Media Panel section.
                		','zbotps')
                )          
            ),
            'zappbar_colors' => array(
                array(
                    'name' => 'color_src',
                    'label' => __( 'Styling Source', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'basic',
                    'options' => array(
                        'basic' => 'Use ZappBar settings on this page',
                        'custom' => 'Use a CUSTOM stylesheet'
                    ),
                    'desc' => __( 'If you set this to "custom" enter the full path to your stylesheet below','zbopts')
                ),
                array(
                	'name' => 'custom_styles',
                	'label' => __( 'Custom Stylesheet', 'zbopts'),
                	'desc' => __( '<br/>full URL path to stylesheet.' , 'zbopts'),
                	'type' => 'text',
                	'defalt' => ''
                ),
                array(
                    'name' => 'bar_bg',
                    'label' => __( 'Bar Background', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#ffffff'
                ),
					array(
						'name' => 'bar_bg_opacity',
						'label' => __( 'Bar BG Opacity', 'zbopts' ),
						'desc' => __( '(optional)', 'zbopts' ),
						'type' => 'number',
						'default' => '1.0',
						'class' => 'none',
						'size' => '3',
						'options' => array(
							'min' => '0',
							'max' => '1',
							'step'=> '0.1'
						),
						'sanitize_callback' => 'floatval'
					),
                 array(
                    'name' => 'button_bg',
                    'label' => __( 'Button Color', 'zbopts' ),
                    'desc' => __( 'Background color of buttons', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#ffffff'
                ), 
					array(
						'name' => 'button_bg_opacity',
						'label' => __( 'Button BG Opacity', 'zbopts' ),
						'desc' => __( '(optional)', 'zbopts' ),
						'type' => 'number',
						'default' => '1.0',
						'class' => 'none',
						'size' => '3',
						'options' => array(
							'min' => '0',
							'max' => '1',
							'step'=> '0.1'
						),
						'sanitize_callback' => 'floatval'
					),
                array(
                    'name' => 'button_hover_bg',
                    'label' => __( 'Button Hover Color', 'zbopts' ),
                    'desc' => __( 'When pointer hovers or touch device focuses on button.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#cccccc'
                ), 
					array(
						'name' => 'button_bg_hover_opacity',
						'label' => __( 'Button Hover Opacity', 'zbopts' ),
						'desc' => __( '(optional)', 'zbopts' ),
						'type' => 'number',
						'default' => '1.0',
						'class' => 'none',
						'size' => '3',
						'options' => array(
							'min' => '0',
							'max' => '1',
							'step'=> '0.1'
						),
						'sanitize_callback' => 'floatval'
					),               
                 array(
                    'name' => 'font_color',
                    'label' => __( 'Button Font Color', 'zbopts' ),
                    'desc' => __( '', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#333333'
                ),               
                array(
                    'name' => 'font_hover_color',
                    'label' => __( 'Button Font Hover color', 'zbopts' ),
                    'desc' => __( 'When pointer hovers or touch device focuses on button.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#000000'
                ), 
                array(
                    'name' => 'bar_border_color',
                    'label' => __( 'Bar Border color', 'zbopts' ),
                    'desc' => __( 'Color of the (optional) border under/over the top/bottom ZappBars.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#000000'
                ), 
                array(
                    'name' => 'bar_border_style',
                    'label' => __( 'Bar Border Style', 'zbopts' ),
                    'desc' => __( 'This border appears along the bottom of the top bar and top of the bottom bar.', 'zbopts' ),
                    'type' => 'dropview',
                    'options' => array(
                        'none' 	 => 'None',
                        'solid'  => 'Solid',
                        'double' => 'Double',
                        'dashed' => 'Dashed',
                        'dotted' => 'Dotted',
                        'groove' => 'Grooved',
                        'ridge'  => 'Ridged',
                        'inset'  => 'Inset',
                        'outset' => 'Outset'
                    )
                ),
                array(
                    'name' => 'bar_border_width',
                    'label' => __( 'Bar Border Width', 'zbopts' ),
                    'desc' => __( 'This border appears along the bottom of the top bar and top of the bottom bar.', 'zbopts' ),
                    'type' => 'dropview',
                    'options' => array(
                        '1px' 	=> '1px',
                        '2px'  	=> '2px',
                        '3px' 	=> '3px',
                        '4px' 	=> '4px',
                        '5px' 	=> '5px',
                        '6px' 	=> '6px',
                        '7px'  	=> '7px',
                        '8px'  	=> '8px',
                        '9px' 	=> '9px',
                        '10px' => '10px'
                    )
                )
            ),
            'zappbar_panels' => array(
                array(
                	'name' => 'panels_explain',
                	'label' => 'About Panel Settings',
                	'type' => 'paragraph',
                	'desc' => __( '
                		If you have set a custom stylesheet on the "ZappBar Colors" tab everything on this tab will 
                		be ignored in favor of your custom stylesheet.  If you select either the "Use Bar styles for Panels" or
                		"Inherit Panel Styles from Theme" option below all the color and styles settings after it will be ignored in favor of the 
                		settings on the "ZappBar Colors" tab or Theme stylesheet, respectively.','zbotps')
                ),
            	array(
            		'name' => 'panel_menu',
            		'label' => __( 'Menu Panel'),
            		'desc' => __( 'Layout of menu items in the Menu Panel.'),
            		'type' => 'radio',
            		'default' => '0',
            		'options' => array(
						'0' => 'Indent sub-items',
						'-1' => 'Flatten List (no indents)',
						'1' => 'Show top-level (parent) items only'            		
            		)
            	),
                array(
                    'name' => 'panel_tabs',
                    'label' => __( 'Sidebar Panels', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Overlay TABS to trigger left/right App Panel sidebars',
                        'no'  => 'Do NOT overlay sidebar panel tabs'
                    ),
                    'desc' => __('If enabled two tabs will overlay your site, left and right, which can be used to show the left/right sidebar App Panels.','zbopts'),
                ),
                array(
                	'name' => 'panel_styles',
                	'label' => __('Panel Style Source'),
                	'type' => 'radio',
                	'default' => 'on',
                	'options' => array(
                		'on'  => __('Use Bar styles for Panels <strong>(Ignores settings below!)</strong>'),
                		'off' => __('Inherit Panel styles from theme <strong>(Ignores settings below!)</strong>'),
                		'yes' => __('Use styles below for Panels')
                		)
                ),
                array(
                    'name' => 'panel_bg',
                    'label' => __( 'Panel Background', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#ffffff'
                ),
					array(
						'name' => 'panel_bg_opacity',
						'label' => __( 'Panel BG Opacity', 'zbopts' ),
						'desc' => __( '(optional)', 'zbopts' ),
						'type' => 'number',
						'default' => '1.0',
						'class' => 'none',
						'size' => '3',
						'options' => array(
							'min' => '0',
							'max' => '1',
							'step'=> '0.1'
						),
						'sanitize_callback' => 'floatval'	// breaks everything!
					),
                 array(
                    'name' => 'panel_button_bg',
                    'label' => __( 'Panel Button Color', 'zbopts' ),
                    'desc' => __( 'Background color of panel buttons', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#ffffff'
                ), 
					array(
						'name' => 'panel_button_bg_opacity',
						'label' => __( 'Panel Button BG Opacity', 'zbopts' ),
						'desc' => __( '(optional)', 'zbopts' ),
						'type' => 'number',
						'default' => '1.0',
						'class' => 'none',
						'size' => '3',
						'options' => array(
							'min' => '0',
							'max' => '1',
							'step'=> '0.1'
						),
						'sanitize_callback' => 'floatval'	// breaks everything!
					),
                array(
                    'name' => 'panel_button_hover_bg',
                    'label' => __( 'Panel Button Hover Color', 'zbopts' ),
                    'desc' => __( 'When pointer hovers or touch device focuses on button.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#cccccc'
                ), 
					array(
						'name' => 'panel_button_bg_hover_opacity',
						'label' => __( 'Panel Button Hover Opacity', 'zbopts' ),
						'desc' => __( '(optional)', 'zbopts' ),
						'type' => 'number',
						'default' => '1.0',
						'class' => 'none',
						'size' => '3',
						'options' => array(
							'min' => '0',
							'max' => '1',
							'step'=> '0.1'
						),
						'sanitize_callback' => 'floatval'
					),   
                array(
                    'name' => 'panel_button_font_color',
                    'label' => __( 'Panel Button Font Color', 'zbopts' ),
                    'desc' => __( '', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#333333'
                ),               
                array(
                    'name' => 'panel_button_font_hover_color',
                    'label' => __( 'Panel Button Font Hover color', 'zbopts' ),
                    'desc' => __( 'When pointer hovers or touch device focuses on button.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#000000'
                ),             
                 array(
                    'name' => 'panel_font_color',
                    'label' => __( 'Panel Font Color', 'zbopts' ),
                    'desc' => __( '', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#333333'
                ),               
                array(
                    'name' => 'panel_font_hover_color',
                    'label' => __( 'Panel Font Hover color', 'zbopts' ),
                    'desc' => __( 'When pointer hovers or touch device focuses on button.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#000000'
                ), 
                array(
                    'name' => 'panel_border_color',
                    'label' => __( 'Panel Bar Border color', 'zbopts' ),
                    'desc' => __( 'Color of the (optional) border under/over the top/bottom ZappBars.', 'zbopts' ),
                    'type' => 'color',
                    'default' => '#000000'
                ), 
                array(
                    'name' => 'panel_border_style',
                    'label' => __( 'Panel Bar Border Style', 'zbopts' ),
                    'desc' => __( 'This border appears along right side of panels on tablets and desktops', 'zbopts' ),
                    'type' => 'dropview',
                    'options' => array(
                        'none' 	 => 'None',
                        'solid'  => 'Solid',
                        'double' => 'Double',
                        'dashed' => 'Dashed',
                        'dotted' => 'Dotted',
                        'groove' => 'Grooved',
                        'ridge'  => 'Ridged',
                        'inset'  => 'Inset',
                        'outset' => 'Outset'
                    )
                ),
                array(
                    'name' => 'panel_border_width',
                    'label' => __( 'Panel Bar Border Width', 'zbopts' ),
                    'desc' => __( 'This border appears along right side of panels on tablets and desktops.', 'zbopts' ),
                    'type' => 'dropview',
                    'options' => array(
                        '1px' 	=> '1px',
                        '2px'  	=> '2px',
                        '3px' 	=> '3px',
                        '4px' 	=> '4px',
                        '5px' 	=> '5px',
                        '6px' 	=> '6px',
                        '7px'  	=> '7px',
                        '8px'  	=> '8px',
                        '9px' 	=> '9px',
                        '10px' => '10px'
                    )
                )
            ),
            'zappbar_layout' => array(
                array(
                	'name' => 'bar_explain',
                	'label' => 'Introduction',
                	'type' => 'paragraph',
                	'desc' => __( '
                		The bars below are editable.<br/>Click on the top part of a button to set the icon, 
                		and on the bottom to edit the button label text.  Then set the action for each 
                		button below the bar.','zbotps')
                ),
                array(
                	'name' => 'button_layout',
                	'label' => __( 'Button Layout', 'zbopts'),
                	'desc' => __( 'Determines how the buttons are positioned on the ZappBars', 'zbopts'),
                	'type' => 'radio',
                	'default' => 'spread',
                	'options' => array(
                		'spread' => '<img src="'.$plugin_dir_url.'options/images/button_layouts-01.png" width="150" height="30" alt="Spread Out"/><br/>',
                		'pushout' => '<img src="'.$plugin_dir_url.'options/images/button_layouts-02.png"  width="150" height="30" alt="Push Out"/><br/>',
                		'clusterleft1r' => '<img src="'.$plugin_dir_url.'options/images/button_layouts-03.png"  width="150" height="30" alt="Cluster Left, One Right"/><br/>',
                		'clusterleft' => '<img src="'.$plugin_dir_url.'options/images/button_layouts-04.png"  width="150" height="30" alt="Cluster Left"/><br/>',
                		'clusterright1l' => '<img src="'.$plugin_dir_url.'options/images/button_layouts-05.png"  width="150" height="30" alt="Cluster Right, One Left"/><br/>',
                		'clusterright' => '<img src="'.$plugin_dir_url.'options/images/button_layouts-06.png"  width="150" height="30" alt="Cluster Right"/>'
                	),
                ),
                array(
                    'name' => 'search_button',
                    'label' => __( 'ZappBar Search Box', 'zbopts' ),
                    'desc' => __( 'Hide "Search" button (form submits on Enter/Return)', 'zbopts' ),
                    'default' => 'on',
                    'type' => 'checkbox'
                ),
                array(
                    'name' => 'logo',
                    'label' => __( 'Logo Icon', 'zbopts' ),
                    'desc' => __( '(optional) Select an image to be your logo icon, then set one of the buttons below to "logo" to display it.  Also used by zb-share shortcode if there is no featured image for thumbnail.', 'zbopts' ),
                    'type' => 'media',
                    'button' => __('Choose Logo'),
                    'default' => ''
                ),
                array(
						'name' => 'button_labels',
						'label' => __( 'Button Label Text', 'zbopts' ),
						'desc' => __( 'Labels will still be shown below so you can set the text.', 'zbopts' ),
						'type' => 'radio',
						'default' => '0',
						'options' => array(
							'0' => 'Text on button, no Tooltip',
							'1' => 'Text on button is also Tooltip',
							'2' => 'NO Text on button, Tooltip ONLY'
						)
					),
                array(
                	'name' => 'default_top',
                	'label' => __( 'Default Top ZappBar', 'zbopts'),
                	'type' => 'appbar',
                	'default' => array(
						array('dashicons|dashicons-menu','Menu','appmenu_left'),
						array('dashicons|dashicons-blank','',''),
						array('dashicons|dashicons-admin-home','Home',get_home_url()),
						array('dashicons|dashicons-blank','', ''),
						array('dashicons|dashicons-search','Search','search_right')
					)
                ),
                array(
                	'name' => 'default_bottom',
                	'label' => __( 'Default Bottom ZappBar', 'zbopts'),
                	'type' => 'appbar',
                	'class' => 'bottom',
                	'default' => array(
						array('dashicons|dashicons-wordpress','Blog','blogposts'),
						array('dashicons|dashicons-info','About',''),
						array('dashicons|dashicons-admin-comments','Comment','commentform'),
						array('dashicons|dashicons-edit','Contact','mailto:'.get_bloginfo('admin_email')),
						array('dashicons|dashicons-share','Share','share_this')
 					)
                ),
				array(
						'name' => 'use_archive_top_bar',
						'label' => __( 'Top Archive ZappBar', 'zbopts' ),
						'type' => 'radio',
						'default' => 'yes',
						'options' => array(
							'yes' => 'Use Archive top bar on archive pages',
							'no' => 'Use Default top bar on archive pages',
							'none' => 'NO top bar on archive pages'
						)
					),
				array(
						'name' => 'archive_top_bar',
						'label' => __( 'Archive Top ZappBar', 'zbopts'),
						'desc' => __( 'Used on archive pages of any kind.','zbopts'),
						'type' => 'appbar',
						'class' => 'top',
						'default' => array(
							array('dashicons|dashicons-menu','Menu','appmenu_left'),
							array('dashicons|dashicons-blank','',''),
							array('dashicons|dashicons-admin-home','Home',get_home_url()),
							array('dashicons|dashicons-blank','', ''),
							array('dashicons|dashicons-search','Search','search_right')
						)
					),
				array(
						'name' => 'use_archive_bottom_bar',
						'label' => __( 'Bottom Archive ZappBar', 'zbopts' ),
						'type' => 'radio',
						'default' => 'yes',
						'options' => array(
							'yes' => 'Use Archive bottom bar on archive pages',
							'no' => 'Use Default bottom bar on archive pages',
							'none'=> 'NO bottom bar on archive pages'
						)
					),
				array(
						'name' => 'archive_bottom_bar',
						'label' => __( 'Archive Bottom ZappBar', 'zbopts'),
						'desc' => __( 'Used on archive pages of any kind.','zbopts'),
						'type' => 'appbar',
						'class' => 'bottom',
						'default' => array(
							array('dashicons|dashicons-arrow-left-alt','First','first_page'),
							array('dashicons|dashicons-arrow-left-alt2','Previous','prev_page'),
							array('dashicons|dashicons-blank','',''),
							array('dashicons|dashicons-arrow-right-alt2','Next', 'next_page'),
							array('dashicons|dashicons-arrow-right-alt','Last','last_page')
						)
					),
					
				array(
						'name' => 'use_blog_top_bar',
						'label' => __( 'Top Blog ZappBar', 'zbopts' ),
						'type' => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Use Blog top bar on blog posts',
							'no' => 'Use Default top bar on blog posts',
							'none' => 'NO top bar on blog posts'
						)
					),
				array(
						'name' => 'blog_top_bar',
						'label' => __( 'Blog Top ZappBar', 'zbopts'),
						'desc' => __( 'Used on blog posts of any kind.','zbopts'),
						'type' => 'appbar',
						'class' => 'top',
						'default' => array(
							array('dashicons|dashicons-menu','Menu','appmenu_left'),
							array('dashicons|dashicons-blank','',''),
							array('dashicons|dashicons-admin-home','Home',get_home_url()),
							array('dashicons|dashicons-blank','', ''),
							array('dashicons|dashicons-wordpress','Blog','blog_posts')
						)
					),
				array(
						'name' => 'use_blog_bottom_bar',
						'label' => __( 'Bottom Blog ZappBar', 'zbopts' ),
						'type' => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Use Blog bottom bar on blog posts',
							'no' => 'Use Default bottom bar on blog posts',
							'none'=> 'NO bottom bar on blog posts'
						)
					),
				array(
						'name' => 'blog_bottom_bar',
						'label' => __( 'Blog Bottom ZappBar', 'zbopts'),
						'desc' => __( 'Used on plog posts of any kind.','zbopts'),
						'type' => 'appbar',
						'class' => 'bottom',
						'default' => array(
							array('dashicons|dashicons-arrow-left-alt2','Previous','previous_post'),
							array('dashicons|dashicons-blank','',''),
							array('dashicons|dashicons-admin-comments','Comment','commentform'),
							array('dashicons|dashicons-blank','', ''),
							array('dashicons|dashicons-arrow-right-alt2','Next','next_post')
						)
					)
            )

        );
        
        if (function_exists('ceo_pluginfo') || function_exists('comicpress_themeinfo') || class_exists('Webcomic') || function_exists('webcomic') || post_type_exists('mangapress_comic') ||  function_exists('comicpost_pluginfo') ) {
			// Detect if any web comics plugins/themes are in use, if so add this bar option
			$settings_fields['zappbar_site'][] = array(
					'name' => 'comic_nav',
					'label' => __('Comic Navigation', 'zbopts'),
					'desc' => __('Hide Comic Naviation Footer when ZappBars are displayed.','zbopts'),
					'type' => 'checkbox',
					'default' => 'on'
			);
        	$settings_fields['zappbar_layout'][] = array(
                	'name' => 'comic_explain',
                	'label' => 'Web comics',
                	'type' => 'paragraph',
                	'desc' => __( '
                		If you are using the ComicPress theme, Comic Easel plugin, Webcomic plugin, or MangaPress plugin the bars 
                		below can be shown on pages displaying single comic posts.','zbotps')
                );
        	$settings_fields['zappbar_layout'][] = array(
                    'name' => 'use_comic_top_bar',
                    'label' => __( 'Top Comic ZappBar', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Use Comic top bar on comics pages',
                        'no' => 'Use Default top bar on comics pages',
                        'none'=> 'NO top bar on comics pages'
                    )
                );
        	$settings_fields['zappbar_layout'][] = array(
                	'name' => 'comic_top_bar',
                	'label' => __( 'Comic Top ZappBar', 'zbopts'),
                	'desc' => __( 'Used on single pages with comics posts.','zbopts'),
                	'type' => 'appbar',
                	'class' => 'top',
                	'default' => array(
						array('dashicons|dashicons-menu','Menu','appmenu_left'),
						array('fa|fa-angle-double-left','Prev Chap','prev_comic'),
						array('dashicons|dashicons-admin-home','Home',get_home_url()),
						array('fa|fa-angle-double-right','Next chap', 'next_comic'),
						array('dashicons|dashicons-images-alt2','Archive','comic_archive')
                	)
                );
        	$settings_fields['zappbar_layout'][] = array(
                    'name' => 'use_comic_bottom_bar',
                    'label' => __( 'Bottom Comic ZappBar', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Use Comic bottom bar on comics pages',
                        'no' => 'Use Default bottom bar on comics pages',
                        'none'=> 'NO bottom bar on comics pages'
                    )
                );
        	$settings_fields['zappbar_layout'][] = array(
                	'name' => 'comic_bottom_bar',
                	'label' => __( 'Comic Bottom ZappBar', 'zbopts'),
                	'desc' => __( 'Used on single pages with comics posts.','zbopts'),
                	'type' => 'appbar',
                	'class' => 'bottom',
                	'default' => array(
						array('dashicons|dashicons-arrow-left-alt','First','first_comic'),
						array('dashicons|dashicons-arrow-left-alt2','Previous','prev_comic'),
						array('dashicons|dashicons-admin-comments','Comment','commentform'),
						array('dashicons|dashicons-arrow-right-alt2','Next', 'next_comic'),
						array('dashicons|dashicons-arrow-right-alt','Last','last_comic')
                	)
                );
        }
        if ( class_exists( 'woocommerce' ) ) {
			// Detect if Woocommerce is activated and add custom bar options
        	$settings_fields['zappbar_layout'][] = array(
                	'name' => 'woo_explain',
                	'label' => 'WooCommerce',
                	'type' => 'paragraph',
                	'desc' => __( '
                		If you are using the WooCommerce plugin the bars below can be customized 
                		for use on e-commerce related pages of your site.','zbotps')
                );
        	$settings_fields['zappbar_layout'][] = array(
                    'name' => 'use_woo_top_bar',
                    'label' => __( 'Top Woocommerce ZappBar', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Use WooCommerce top bar on store pages',
                        'no' => 'Use Default top bar on store pages',
                        'none'=> 'NO top bar on store pages'
                    )
                );
        	$settings_fields['zappbar_layout'][] = array(
                	'name' => 'woo_top_bar',
                	'label' => __( 'WooCommerce Top ZappBar', 'zbopts'),
                	'desc' => __( 'Used on WooCommerce single product pages.','zbopts'),
                	'type' => 'appbar',
                	'class' => 'top',
                	'default' => array(
						array('dashicons|dashicons-menu','Menu','appmenu_left'),
						array('dashicons|dashicons-cart','Cart','woo_cart'),
						array('dashicons|dashicons-admin-home','Home',get_home_url()),
						array('dashicons|dashicons-admin-users','Account', 'woo_account'),
						array('dashicons|dashicons-search','Search','woo_search_right')
                	)
                );
        	$settings_fields['zappbar_layout'][] = array(
                    'name' => 'use_woo_bottom_bar',
                    'label' => __( 'Bottom WooCommerce ZappBar', 'zbopts' ),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Use WooCommerce bottom bar on store pages',
                        'no' => 'Use Default bottom bar on store pages',
                        'none'=> 'NO bottom bar on store pages'
                    )
                );
        	$settings_fields['zappbar_layout'][] = array(
                	'name' => 'woo_bottom_bar',
                	'label' => __( 'WooCommerce Bottom ZappBar', 'zbopts'),
                	'desc' => __( 'Used on WooCommerce single product pages.','zbopts'),
                	'type' => 'appbar',
                	'class' => 'bottom',
                	'default' => array(
						array('dashicons|dashicons-products','Store','woo_store'),
						array('dashicons|dashicons-tag','Info','woo_desc'),
						array('dashicons|dashicons-star-filled','Reviews','woo_review'),
						array('fa|fa-tags','Options', 'woo_addl'),
						array('dashicons|dashicons-share','Share','share_this')
                	)
                );
            $settings_fields['zappbar_site'][] = array(
                    'name' => 'alter_woo_theme',
                    'label' => __( 'WooCommerce Site', 'zbopts' ),
                    'desc' => __( 'Select which WooCommerce features to alter.', 'zbopts' ),
                    'type' => 'multicheck',
                    'default' => array('woo_reviews' => '', 'woo_desc' => '', 'woo_addl' => '', 'woo_big' => ''),
                    'options' => array(
                        'woo_reviews' => 'Convert Woo Reviews to App Panel',
                        'woo_desc' => 'Convert Woo Product Description to App Panel',
                        'woo_addl' => 'Convert Woo Additional Product Info to App Panel',
                        'woo_big'  => 'Increase text size of tables in Checkout on Phones'
                    )
                );
        }

        return $settings_fields;
    }

    function plugin_page() {
    ?>
        <div class="wrap zb_settings_page">
        <h1>ZappBar Settings</h1>
        <?php $this->settings_api->show_navigation(); ?>
        <?php $this->settings_api->show_forms(); ?>
        <form id="zb_reset" method="post" action="">
        <?php wp_nonce_field('zb_reset','zb_reset_nonce'); ?>
        <input type="hidden" name="zappbar_reset" value="1" />
        <input type="button" type="submit" name="resetbutton" class="reset button secondary-button" value="Reset to defaults" style="float:right;" />
        <div style="clear:both;"></div>
        </form>        
        <?php
        echo '</div>';
        echo '<style type="text/css">';
        $colors = get_option('zappbar_colors');
        $opacity = array('1','1.0');
        if (!empty($colors)) {
        	if ($colors['bar_bg'] != '') {
        		if (!in_array($colors['bar_bg_opacity'],$opacity)) {
        			$color = 'rgba('.hex2rgb($colors['bar_bg']).','.$colors['bar_bg_opacity'].');';
        		} else { 
        		$color = $colors['bar_bg']; 
        		}
        	echo 'div.zappbar { background-color: '.$color.';}';
        	}
        	if ($colors['button_bg'] != '') {
        		if (!in_array($colors['button_bg_opacity'],$opacity)) {
        			$color = 'rgba('.hex2rgb($colors['button_bg']).','.$colors['button_bg_opacity'].');';
        		} else { 
        		$color = $colors['button_bg']; 
        		}
        	echo 'div.zappbar div.button { background-color: '.$color.';}';
        	}
        	if ($colors['font_color'] != '') {
        		$fontcolor = 'color: '.$colors['font_color'].';';
        		echo 'div.zappbar div.button { '.$fontcolor.' }';
        	}
        	if ($colors['button_hover_bg'] != '') {
        		if (!in_array($colors['button_bg_hover_opacity'],$opacity)) {
        			$color = 'rgba('.hex2rgb($colors['button_hover_bg']).','.$colors['button_bg_hover_opacity'].');';
	       		} else { 
        		$color = $colors['button_hover_bg'];
        		}
        		if ($colors['font_hover_color'] != '') {
        			$fontcolor = 'color: '.$colors['font_hover_color'].';';
        		} else { $fontcolor = ''; }
        	echo 'div.zappbar div.button:hover, div.zappbar div.button:focus {';
        	echo 'background-color: '.$color.';'.$fontcolor.'}';
        	}
        	if ($colors['bar_border_width'] != '') {
        		$width = explode(',',$colors['bar_border_width']);
        		if ($colors['bar_border_style'] != '') { $style = explode(',',$colors['bar_border_style']); } else { $style = array('solid');}
        		if ($colors['bar_border_color'] != '') { $color = $colors['bar_border_color']; } else { $color = "#000000";}
        		echo 'div.zappbar.top { border-bottom: '.$width[0].' '.$style[0].' '.$color.'; }';
        		echo 'div.zappbar.bottom { border-top: '.$width[0].' '.$style[0].' '.$color.'; }';
        	}
        }
        $zb_layout = get_option('zappbar_layout');
        if ($zb_layout['logo'] != '') {
			echo	'div.icon-picker.dashicons.dashicons-logo,';
			echo	'div.icon-picker.fa.fa-logo,';
			echo	'div.icon-picker.genericon.genericon-logo {';
			echo	'	height: 100%; width: 100%;';
			echo	'	background: url("'.$zb_layout['logo'].'") center center no-repeat;';
			echo 	'	background-size: contain;';
			echo	'}';
        }
        echo '</style>';
        echo '<script type="text/javascript">' ?>
        	jQuery(document).ready(function($){	
				$('[name="zappbar_layout\\[button_layout\\]"]').on('change',function(e) {
					$('.zappbar').each( function() {
						var c = $(this).attr('class');
						c = c.split(' ');
						var newclass = $('[name="zappbar_layout\\[button_layout\\]"]:checked').val();
						$(this).removeClass().addClass('zappbar zb-'+newclass+' '+c[2]+'');
					});
				});
				var zb_theme_width = "<?php echo get_theme_mod('comicpress-customize-range-site-width') ? intval( get_theme_mod('comicpress-customize-range-site-width')) : ''; ?>";
				if (zb_theme_width != '') {
					$('[name="zappbar_site\\[auto_width\\]"]').parent().parent().css({ opacity : 0.5 });
					$('[name="zappbar_site\\[auto_width\\]"]').checked = true;
					$('[name="zappbar_site\\[theme_width\\]"]').parent().parent().css({ opacity : 0.5 });				
					$('[name="zappbar_site\\[theme_width\\]"]').val(zb_theme_width);
				}
				
				$( '#zb_reset input.reset' ).on( 'click', function() {
					if ( confirm( "ARE YOU SURE? Resetting ALL Zappbar settings to defaults CANNOT BE UNDONE!" ) ) {
						$('#zb_reset').submit();
					}
				} );
				
        	});
<?php  echo '</script>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new ZB_Settings_API_Test();
?>