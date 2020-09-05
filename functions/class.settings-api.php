<?php

/**
 * ZB_Settings_API for ZappBars
 * derived from weDevs Settings API wrapper class
 * @author Tareq Hasan <tareq@weDevs.com>
 * @link http://tareq.weDevs.com Tareq's Planet
 *
 * Heavily modified and expanded by K.M. Hansen (software@kmhcreative.com)
 */

if ( !class_exists( 'WeDevs_Settings_API' ) ):
class ZB_Settings_API {

    /**
     * settings sections array
     *
     * @var array
     */
    private $settings_sections = array();

    /**
     * Settings fields array
     *
     * @var array
     */
    private $settings_fields = array();

    /**
     * Singleton instance
     *
     * @var object
     */
    private static $_instance;

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    /**
     * Enqueue scripts and styles
     */
    function admin_enqueue_scripts() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'thickbox' );

        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'thickbox' );
    }

    /**
     * Set settings sections
     *
     * @param array   $sections setting sections array
     */
    function set_sections( $sections ) {
        $this->settings_sections = $sections;
        return $this;
    }

    /**
     * Add a single section
     *
     * @param array   $section
     */
    function add_section( $section ) {
        $this->settings_sections[] = $section;

        return $this;
    }

    /**
     * Set settings fields
     *
     * @param array   $fields settings fields array
     */
    function set_fields( $fields ) {
        $this->settings_fields = $fields;

        return $this;
    }

    function add_field( $section, $field ) {
        $defaults = array(
            'name' => '',
            'label' => '',
            'desc' => '',
            'type' => 'text'
        );

        $arg = wp_parse_args( $field, $defaults );
        $this->settings_fields[$section][] = $arg;

        return $this;
    }

    /**
     * Initialize and registers the settings sections and fileds to WordPress
     *
     * Usually this should be called at `admin_init` hook.
     *
     * This function gets the initiated settings sections and fields. Then
     * registers them to WordPress and ready for use.
     */
    function admin_init() {
        //register settings sections
        foreach ( $this->settings_sections as $section ) {
            if ( false == get_option( $section['id'] ) ) {
                add_option( $section['id'] );
            }

            if ( isset($section['desc']) && !empty($section['desc']) ) {
                $section['desc'] = '<div class="inside">'.$section['desc'].'</div>';
                $callback = create_function('', 'echo "'.str_replace('"', '\"', $section['desc']).'";');
            } else {
                $callback = '__return_false';
            }

            add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
        }

        //register settings fields
        foreach ( $this->settings_fields as $section => $field ) {
            foreach ( $field as $option ) {

                $type = isset( $option['type'] ) ? $option['type'] : 'text';

                $args = array(
                    'id' => $option['name'],
                    'desc' => isset( $option['desc'] ) ? $option['desc'] : '',
                    'name' => $option['label'],
                    'section' => $section,
                    'class' => isset( $option['class'] ) ? $option['class'] : null,
                    'size' => isset( $option['size'] ) ? $option['size'] : null,
                    'button' => isset($option['button']) ? $option['button'] : null,
                    'options' => isset( $option['options'] ) ? $option['options'] : '',
                    'std' => isset( $option['default'] ) ? $option['default'] : '',
                    'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
                );
                add_settings_field( $section . '[' . $option['name'] . ']', $option['label'], array( $this, 'callback_' . $type ), $section, $section, $args );
            }
        }

        // creates our settings in the options table
        foreach ( $this->settings_sections as $section ) {
            register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
        }
    }
	/**
	 * Displays text, that is all
	 *
	 */
	 function callback_paragraph( $args ) {
	 	$text = $args['desc'];	 	
	 	echo $args['desc'];
	 }


    /**
     * Displays a text field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_text( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" size="%5$s"/>', $class, $args['section'], $args['id'], $value, $size );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }
    
    /**
     * Displays an HTML5 number input field for a settings field
     * If browser does not support type="number" it shows a text field
     *
     * @param array   $args settings field args
     */
    function callback_number( $args ) {
    
        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';
        // input type="number" does not have "size" param, so fake it with inline style + space allowance for up/down arrows
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? 'style="width:'.( (float)$args['size']+.5 ).'em;"' : '';
		$attr = '';	// assume none
		if ($args['options'] != ''){
			foreach ( $args['options'] as $key => $val ) {
				if ($key == 'min') {	$attr.='min="'.$val.'"';}
				if ($key == 'max') { 	$attr.='max="'.$val.'"';}
				if ($key == 'step'){	$attr.='step="'.$val.'"';}
			}
		}
        $html = sprintf( '<input type="number" class="%1$s-number" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" %6$s %5$s/>', $class, $args['section'], $args['id'], $value, $size, $attr );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }
 
     /**
     * Displays a icon picker button
     *
     * @param array   $args settings field args
     */   
    function callback_icon( $args ) {
    	$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
    	$class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';
 		if ($value != '') { $preview = explode('|',$value); } else { $preview = array('','');};

    	$html = sprintf( '<input class="%1$s-text" id="%2$s_%3$s" name="%2$s[%3$s]" type="hidden" value="%4$s"  />', $class, $args['section'], $args['id'], $value );
		$html .= sprintf( '<div id="preview_%2$s_%3$s" class="button icon-picker %5$s %6$s" data-target="#%2$s_%3$s"></div>',$class, $args['section'], $args['id'], $value, $preview[0], $preview[1] );

    	echo $html;
    }

    function callback_appbar( $args ) {
    	$class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'top';
    	$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$zb_layout = get_option('zappbar_layout');
		if (empty($zb_layout) || $zb_layout['button_layout'] == '') {
			$layout = 'zb-spread';
		} else {
			$layout = 'zb-'.$zb_layout['button_layout'];
		}
		if ($zb_layout['button_labels'] == '2') {
			$layout .= ' notext';
		} 	
 		if (empty($value)) {
 			if (preg_match('/bottom/',$class)) {
				$value = array(
					// icons
					array('dashicons|dashicons-admin-home','Home',get_home_url()),
					array('dashicons|dashicons-migrate','Sidebar','sidebar'),
					array('dashicons|dashicons-info','About',''),
					array('dashicons|dashicons-rss','Follow', '?feed=rss'),
					array('dashicons|dashicons-edit','Contact','mailto:'.get_bloginfo('admin_email'))
				);
 			} else {
				$value = array(
					// icons
					array('dashicons|dashicons-menu','Menu','appmenu'),
					array('dashicons|dashicons-blank','',''),
					array('dashicons|dashicons-wordpress','Blog','?post_type=post'),
					array('dashicons|dashicons-blank','', ''),
					array('dashicons|dashicons-search','Search','searchbox')
				);
 			}
		}
		
		$name = array(
			'button_a',
			'button_b',
			'button_c',
			'button_d',
			'button_e'
		);

		$html = '<div class="zappbar '.$layout.' '.$class.'">';
		$x = 0;
		foreach ($value as $val) {
			$html .= '<div class="zb '.$name[$x].'">';

			$preview = explode('|',$val[0]);
			$label = sprintf( '<input class="zb-label" id="label_%2$s_%3$s_%5$s_1" name="%2$s[%3$s][%5$s][1]" type="text" value="%4$s" size="%1$s" />', $class, $args['section'], $args['id'], $val[1], $x );;
			
    		$html .= sprintf( '<input class="" id="%2$s_%3$s_'.$x.'_0" name="%2$s[%3$s]['.$x.'][0]" type="hidden" value="%4$s"  />', $class, $args['section'], $args['id'], $val[0] );
			$html .= sprintf('<div class="button"><div id="preview_%2$s_%3$s_%4$s_0" class="icon-picker %5$s %6$s" data-target="#%2$s_%3$s_'.$x.'_0"></div><br/>'.$label.'</div>', $class, $args['section'], $args['id'], $x, $preview[0], $preview[1] );
			$html .= sprintf( '<input class="" id="%2$s_%3$s_'.$x.'_2" name="%2$s[%3$s][%4$s][2]" type="hidden" value="%5$s"  />', $class, $args['section'], $args['id'], $x, $val[2] );
			$html .= '<div class="helper">Button '.($x+1).'</div>';
			$html .= '</div>';
			$x++;
		}
		$html .= '</div><br/>';
		if ($args['desc'] != '') {
		$html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );
		} else {
		$html .= '<span class="description">Buttons with (blank) are hidden on the front-end of your site.</span>';
		}
		$html .= '<div><a href="javascript:void(0);" class="show_actions">Show Button Actions</a><a href="javascript:void(0);" class="hide_actions" style="display:none;">Hide Button Actions</a><div class="actions_box" style="display:none;">';
 			$targets = array(
 				'' => 'Select Target',
 				'appmenu_left' => 'App Menu (Left)',
 				'appmenu_right' => 'App Menu (Right)',
 				'search_box' => 'Search Box (Center)',
 				'search_left' => 'Search Box (Left)',
 				'search_right' => 'Search Box (Right)',
 				'commentform' => 'Open Comment Form',
 				'sidebar_left' => 'Open App Sidebar (Left)',
 				'sidebar_right'=> 'Open App Sidebar (Right)',
 				get_home_url() => 'Go to Home Page',
 				'blogposts' => 'All Blog Posts',
 				'?feed=rss' => 'RSS Feed',
 				'mailto:'.get_bloginfo('admin_email') => 'E-mail Admin',
 				'custom_email' => 'E-mail Contact',
 				'previous_post' => 'Previous Blog Post',
 				'next_post' => 'Next Blog Post',
 				'first_page' => 'First Page of Archive',
 				'prev_page' => 'Previous Page in Archive',
 				'next_page' => 'Next Page in Archive',
 				'last_page' => 'Last Page of Archive',
 				'callme'	=> 'Phone Number',
 				'share_this' => 'Open Share Panel',
 				'share_fb'	=> 'Share on Facebook',
 				'share_twitter' => 'Share on Twitter',
 				'share_gplus' => 'Share on Google+',
 				'share_reddit' => 'Share on Reddit',
 				'share_stumble'=> 'Share on Stumbleupon',
 				'share_digg' => 'Share on Digg',
 				'share_linkedin'=> 'Share on LinkedIn',
 				'share_pinterest'=>'Share on Pinterest',
 				'share_delicious'=>'Share on Del.icio.us'
 			);
 			if ( post_type_exists( 'ryuzine' ) ) {
 				$targets = array_merge($targets,array(
 					'ryuzine_rack' => 'Ryuzine Rack'
 					)
 				);
 			}
 			if ( class_exists( 'woocommerce' ) ) {
 				$woo_targets = array(
 					'woo_account' => 'Woo Account',
 					'woo_cart'	=> 'Woo Show Cart',
 					'woo_buy'	=> 'Woo Add to Cart',
 					'woo_desc' 	=> 'Woo Product Description',
 					'woo_addl'  => 'Woo Additional Info',
 					'woo_search'=> 'Woo Product Search',
 					'woo_search_left' => 'Woo Search (Left)',
 					'woo_search_right'=> 'Woo Search (Right)',
 					'woo_review'=> 'WooCommerce Product Reviews',
 					'woo_store' => 'WooCommerce Store Page'
 				);
 				$targets = array_merge($targets,$woo_targets);
 			}
 			
			if (function_exists('ceo_pluginfo') || function_exists('comicpress_themeinfo') || class_exists('Webcomic') || function_exists('webcomic') || post_type_exists('mangapress_comic') ) {
				if(function_exists('webcomic')){ 
					$collection = get_webcomic_collections();
					$comic_archive = get_webcomic_collection_url($collection[0]);
					$archive_label = "Comic Collection";
					$chapter_label = "Storyline";
				} else {
					$comic_archive = "comic_archive";
					$archive_label = "Comic Archive";
					$chapter_label = "Comic Chapter";
				}
				$ceo_targets = array(
					'prev_chapter'=> 'Previous '.$chapter_label,
					'first_comic' => 'First Comic',
					'prev_comic'  => 'Previous Comic',
					'next_comic'  => 'Next Comic',
					'last_comic'  => 'Last Comic',
					'next_chapter'=> 'Next '.$chapter_label,
				   $comic_archive => $archive_label
				);
				$targets = array_merge($targets,$ceo_targets);
			}
   			$pages = get_pages(); 
 			foreach ($pages as $page) {
 				$pagelist = array(get_page_link($page->ID) => $page->post_title);
 				$targets = array_merge($targets, $pagelist);
 			}
		$x = 0;
		foreach($value as $val) {
		$html .= '<div>Button '.($x+1).' action: ';
		$html .= '<select name="'.$args['section'].'['.$args['id'].']['.$x.'][2]">'; 
		foreach($targets as $key => $label) {
			if ($val[2] == $key) { $selected = 'selected';} else { $selected = ''; };
			$html .= '<option value="'.$key.'" '.$selected.'>'.$label.'</option>';
		}
		$html .= '</select></div>';
 		$x++;
 		};
		$html .= '</div></div>';
		$html .= '<script type="text/javascript">
        jQuery(document).ready(function(){	
        	jQuery(\'.show_actions\').click(function() {
        		jQuery(this).hide();
        		jQuery(this).siblings(\'.hide_actions\').show();
        		jQuery(this).siblings(\'.actions_box\').slideDown();
        	});
        	jQuery(\'.hide_actions\').click(function() {
        		jQuery(this).hide();
        		jQuery(this).siblings(\'.show_actions\').show();
        		jQuery(this).siblings(\'.actions_box\').slideUp();
        	});
        });	
		</script>';
		



    	echo $html;
    }

    /**
     * Displays a checkbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_checkbox( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

        $html = sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
        $html .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="on"%4$s />', $args['section'], $args['id'], $value, checked( $value, 'on', false ) );
        $html .= sprintf( '<label for="%1$s[%2$s]"> %3$s</label>', $args['section'], $args['id'], $args['desc'] );

        echo $html;
    }

    /**
     * Displays a multicheckbox a settings field
     *
     * @param array   $args settings field args
     */
    function callback_multicheck( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );

        $html = '';
        foreach ( $args['options'] as $key => $label ) {
            $checked = isset( $value[$key] ) ? $value[$key] : '0';
            $html .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s"%4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
            $html .= sprintf( '<label for="%1$s[%2$s][%4$s]"> %3$s</label><br>', $args['section'], $args['id'], $label, $key );
        }
        $html .= sprintf( '<span class="description"> %s</label>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a multicheckbox a settings field
     *
     * @param array   $args settings field args
     */
    function callback_radio( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );

        $html = '';
        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
            $html .= sprintf( '<label for="%1$s[%2$s][%4$s]"> %3$s</label><br>', $args['section'], $args['id'], $label, $key );
        }
        $html .= sprintf( '<span class="description"> %s</label>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a selectbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_select( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';

        $html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $class, $args['section'], $args['id'] );
        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
        }
        $html .= sprintf( '</select>' );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a dropdown for a settings field
     * (unlike select options these can be styled)
     * @param array   $args settings field args
     */
    function callback_dropdown( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'flex';
		
		$options = array();
		foreach($args['options'] as $key => $label) {
			array_push($options, $key.','.$label);
		}
		
		if ($value != '') {
			$value = explode(',',$value);
		} else {
			$value = explode(',',$options[0]);
		}
		
        $html = sprintf( '<div id="%2$s_%3$s" class="dropdown">
        <input value="'.$value[0].','.$value[1].'" type="hidden" name="%2$s[%3$s]" id="%2$s[%3$s]"/>
        <input value="'.$value[1].'" type="text" class="%1$s select" readonly="readonly" /><div class="dashicons dashicons-arrow-down"></div><ul class="%1$s">', $class, $args['section'], $args['id'] );
        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf( '<li data-option="%s" data-label="%s">%s</li>', $key, $label, $key, $label );
        }
        $html .= sprintf( '</ul></div>' );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );
        $html .= ' <script type="text/javascript">
        jQuery(document).ready(function($){
        	$(\'#'.$args['section'].'_'.$args['id'].' .select\').click(function(event) {
        		$(\'#'.$args['section'].'_'.$args['id'].'\').addClass(\'open\');
        	});
         	$(\'#'.$args['section'].'_'.$args['id'].' .dashicons\').click(function(event) {
        		$(\'#'.$args['section'].'_'.$args['id'].'\').addClass(\'open\');
        	});
          	$(\'#'.$args['section'].'_'.$args['id'].' li\').click(function(event) {
          		var option = $(this).data(\'option\');
          		var label = $(this).data(\'label\');
          		$(\'#'.$args['section'].'_'.$args['id'].' input[type=\"hidden\"]\').val(option+\',\'+label);
  				$(\'#'.$args['section'].'_'.$args['id'].' .select\').val(label);     		
        		$(\'#'.$args['section'].'_'.$args['id'].'\').removeClass().addClass(\'dropdown\');
        	});       	
        	$(document).on(\'touchstart click\', function(event) { 
    			if($(event.target).parents().index($(\'#'.$args['section'].'_'.$args['id'].'\')) == -1) {
        			if($(\'#'.$args['section'].'_'.$args['id'].'\').hasClass(\'open\')) {
            			$(\'#'.$args['section'].'_'.$args['id'].'\').removeClass().addClass(\'dropdown\');
        			}
    			}        
			})
		})</script>';

        echo $html;
    }

    /**
     * Displays a dropdown for a settings field
     * (unlike select options these can be styled)
     * @param array   $args settings field args
     */
    function callback_dropview( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'flex';
		
		$options = array();
		foreach($args['options'] as $key => $label) {
			array_push($options, $key.','.$label);
		}
		
		if ($value != '') {
			$value = explode(',',$value);
		} else {
			$value = explode(',',$options[0]);
		};
        $html = sprintf( '<div id="%2$s_%3$s" class="dropview">
        <input value="'.$value[0].','.$value[1].'" type="hidden" name="%2$s[%3$s]" id="%2$s[%3$s]"/>
        <div class="select %1$s"><span class="_'.$value[0].'"></span>'.$value[1].'</div><div class="dashicons dashicons-arrow-down"></div><ul class="%1$s">', $class, $args['section'], $args['id'] );
        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf( '<li data-option="%s" data-label="%s"><span class="_%s"></span>%s</li>', $key, $label, $key, $label );
        }
        $html .= sprintf( '</ul></div>' );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );
        $html .= ' <script type="text/javascript">
        jQuery(document).ready(function($){
        	$(\'#'.$args['section'].'_'.$args['id'].' .select\').click(function(event) {
        		$(\'#'.$args['section'].'_'.$args['id'].'\').addClass(\'open\');
        	});
         	$(\'#'.$args['section'].'_'.$args['id'].' .dashicons\').click(function(event) {
        		$(\'#'.$args['section'].'_'.$args['id'].'\').addClass(\'open\');
        	});
          	$(\'#'.$args['section'].'_'.$args['id'].' li\').click(function(event) {
          		var option = $(this).data(\'option\');
          		var label = $(this).data(\'label\');
          		var html = $(this).html();
          		$(\'#'.$args['section'].'_'.$args['id'].' input[type=\"hidden\"]\').val(option+\',\'+label);
  				$(\'#'.$args['section'].'_'.$args['id'].' .select\').html(html);     		
        		$(\'#'.$args['section'].'_'.$args['id'].'\').removeClass().addClass(\'dropview\');
        	});       	
        	$(document).on(\'touchstart click\', function(event) { 
    			if($(event.target).parents().index($(\'#'.$args['section'].'_'.$args['id'].'\')) == -1) {
        			if($(\'#'.$args['section'].'_'.$args['id'].'\').hasClass(\'open\')) {
            			$(\'#'.$args['section'].'_'.$args['id'].'\').removeClass().addClass(\'dropview\');
        			}
    			}        
			})
		})</script>';

        echo $html;
    }


    /**
     * Displays a textarea for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_textarea( $args ) {

        $value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';

        $html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $class, $args['section'], $args['id'], $value );
        $html .= sprintf( '<br><span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a textarea for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_html( $args ) {
        echo $args['desc'];
    }

    /**
     * Displays a rich text textarea for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_wysiwyg( $args ) {

        $value = wpautop( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : '500px';

        echo '<div style="width: ' . $class . ';">';

        wp_editor( $value, $args['section'] . '[' . $args['id'] . ']', array( 'teeny' => true, 'textarea_rows' => 10 ) );

        echo '</div>';

        echo sprintf( '<br><span class="description"> %s</span>', $args['desc'] );
    }

    /**
     * Displays a file upload field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_file( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';
        $id = $args['section']  . '[' . $args['id'] . ']';
        $js_id = $args['section']  . '\\\\[' . $args['id'] . '\\\\]';
        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $class, $args['section'], $args['id'], $value );
        $html .= '<input type="button" class="button wpsf-browse" id="'. $id .'_button" value="Browse" />
        <script type="text/javascript">
        jQuery(document).ready(function($){
            $("#'. $js_id .'_button").click(function() {
                tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
                window.original_send_to_editor = window.send_to_editor;
                window.send_to_editor = function(html) {
                    var url = $(html).attr(\'href\');
                    if ( !url ) {
                        url = $(html).attr(\'src\');
                    };
                    $("#'. $js_id .'").val(url);
                    tb_remove();
                    window.send_to_editor = window.original_send_to_editor;
                };
                return false;
            });
        });
        </script>';
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays the Media Library upload field
     * with the "thickbox" manager for WP < 3.5
     * and the new media manager for WP > 3.5
     *
     * @param array   $args settings field args
     */
    function callback_media( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std']) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';
		$id = $args['section'] . '_' . $args['id'];
		$button = isset($args['button']) && !is_null( $args['button']) ? esc_attr($args['button']) : 'Media Library';
        $html = sprintf( '<div class="uploader"><input type="text" class="%1$s-text" id="%2$s_%3$s" name="%2$s[%3$s]" value="%4$s"/>', $class, $args['section'], $args['id'], $value );
        $html .= '<a href="#" class="button" id="'.$id.'_button">'.$button.'</a></div><br/>';

        // Now enqueue the correct jQuery handler
        $wp_version = get_bloginfo('version');
		if ($wp_version < 3.5) {
        $html .= '<script type="text/javascript">
        jQuery(document).ready(function($){
            $("#'. $id .'_button").click(function() {
                tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
                window.original_send_to_editor = window.send_to_editor;
                window.send_to_editor = function(html) {
                    var url = $(html).attr(\'href\');
                    if ( !url ) {
                        url = $(html).attr(\'src\');
                    };
                    $("#'. $id .'").val(url);
                    tb_remove();
                    window.send_to_editor = window.original_send_to_editor;
                };
                return false;
            });
        });
        </script>';		
		} else {
        $html .= '<script type="text/javascript">
			jQuery(document).ready(function($){
				var _custom_media = false,
				_orig_send_attachment = wp.media.editor.send.attachment;
				id = \'\';
				wp.media.editor.send.attachment = function(props, attachment){
					$("#"+id).val(attachment.url);
				}
				$(\'.uploader .button\').click(function(e) {
					var send_attachment_bkp = wp.media.editor.send.attachment;
					var button = $(this);
					id = button.attr(\'id\').replace(\'_button\', \'\');
					wp.media.editor.open(button);
					return false;
				});
				$(\'.add_media\').on(\'click\', function(){
					_custom_media = false;
				});
			});
        </script>';
        };
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );
 /*		$img_options = get_option($args['section']);
 		if (!empty($img_options) && $img_options[$args['id']] != '') {
 		$html .= '<style>
			div.icon-picker.dashicons.dashicons-logo {
				height: 100%; width: 100%;
				background: url("'.$img_options[$args['id']].'") center center no-repeat;
				background-size: contain;
			}
		</style>';
 		}
*/
        echo $html;
    }


    /**
     * Displays a password field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_password( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';

        $html = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $class, $args['section'], $args['id'], $value );
        $html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

        echo $html;
    }

    /**
     * Displays a color picker field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_color( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $class = isset( $args['class'] ) && !is_null( $args['class'] ) ? $args['class'] : 'regular';

        $html = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $class, $args['section'], $args['id'], $value, $args['std'] );
        $html .= sprintf( '<span class="description" style="display:block;"> %s</span>', $args['desc'] );

        echo $html;
    }
    
    /**
     * Sanitize callback for Settings API
     */
    function sanitize_options( $options ) {
        foreach( $options as $option_slug => $option_value ) {
            $sanitize_callback = $this->get_sanitize_callback( $option_slug );

            // If callback is set, call it
            if ( $sanitize_callback ) {
                $options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
                continue;
            }

            // Treat everything that's not an array as a string
            if ( !is_array( $option_value ) ) {
                $options[ $option_slug ] = sanitize_text_field( $option_value );
                continue;
            }
        }
        return $options;
    }

    /**
     * Get sanitization callback for given option slug
     *
     * @param string $slug option slug
     *
     * @return mixed string or bool false
     */
    function get_sanitize_callback( $slug = '' ) {
        if ( empty( $slug ) )
            return false;
        // Iterate over registered fields and see if we can find proper callback
        foreach( $this->settings_fields as $section => $options ) {
            foreach ( $options as $option ) {
                if ( $option['name'] != $slug )
                    continue;
                // Return the callback name
                return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
            }
        }
        return false;
    }

    /**
     * Get the value of a settings field
     *
     * @param string  $option  settings field name
     * @param string  $section the section name this field belongs to
     * @param string  $default default text if it's not found
     * @return string
     */
    function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }

    /**
     * Show navigations as tab
     *
     * Shows all the settings section labels as tab
     */
    function show_navigation() {
        $html = '<h2 class="nav-tab-wrapper">';

        foreach ( $this->settings_sections as $tab ) {
            $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
        }

        $html .= '</h2>';

        echo $html;
    }

    /**
     * Show the section settings forms
     *
     * This function displays every sections in a different form
     */
     


     
    function show_forms() { ?>
        <div class="metabox-holder">
            <div class="postbox">
                <?php foreach ( $this->settings_sections as $form ) { 
                ?>
                    <div id="<?php echo $form['id']; ?>" class="group">
                        <form method="post" action="options.php">
                            <?php do_action( 'wsa_form_top_' . $form['id'], $form ); ?>
                            <?php settings_fields( $form['id'] ); ?>
                            <?php do_settings_sections( $form['id'] ); ?>
                            <?php do_action( 'wsa_form_bottom_' . $form['id'], $form ); ?>
                            <div style="padding-left: 10px">
                                <?php submit_button(); ?>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $this->script();
    }




    /**
     * Tabbable JavaScript codes & Initiate Color Picker
     *
     * This code uses localstorage for displaying active tabs
     */
    function script() {
        ?>
        <script>
            jQuery(document).ready(function($) {
                //Initiate Color Picker
                $('.wp-color-picker-field').wpColorPicker();              
                // Switches option sections
                $('.group').hide();
                var activetab = '';
                if (typeof(localStorage) != 'undefined' ) {
                    activetab = localStorage.getItem("activetab");
                }
                if (activetab != '' && $(activetab).length ) {
                    $(activetab).fadeIn();
                } else {
                    $('.group:first').fadeIn();
                }
                $('.group .collapsed').each(function(){
                    $(this).find('input:checked').parent().parent().parent().nextAll().each(
                    function(){
                        if ($(this).hasClass('last')) {
                            $(this).removeClass('hidden');
                            return false;
                        }
                        $(this).filter('.hidden').removeClass('hidden');
                    });
                });

                if (activetab != '' && $(activetab + '-tab').length ) {
                    $(activetab + '-tab').addClass('nav-tab-active');
                }
                else {
                    $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                }
                $('.nav-tab-wrapper a').click(function(evt) {
                    $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active').blur();
                    var clicked_group = $(this).attr('href');
                    if (typeof(localStorage) != 'undefined' ) {
                        localStorage.setItem("activetab", $(this).attr('href'));
                    }
                    $('.group').hide();
                    $(clicked_group).fadeIn();
                    evt.preventDefault();
                });
            });
        </script>

        <style type="text/css">
            /** WordPress 3.8 Fix **/
            .form-table th { padding: 20px 10px; }
            #wpbody-content .metabox-holder { padding-top: 5px; }
        </style>
        <?php
    }

}
endif;