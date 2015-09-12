dashicons-picker
================

A jQuery plugin that makes it easy to select the perfect glyph from three popular Wordpress icon fonts 


[Dashicons](http://melchoyce.github.io/dashicons/) in WordPress a breeze

![dashicons-picker](https://raw.github.com/bradvin/dashicons-picker/master/dashicons-picker-jquery-plugin.jpg "dashicons-picker")

## THE FONTS ##

The icon fonts are not included, because you may already have them:

[Dashicons](https://github.com/melchoyce/dashicons) - comes with WordPress 3.8+ and is used for the new Admin UI.
[Genericons] (http://genericons.com/#menu) - came with the WordPress default theme "Twenty Thirteen"
[Font Awesome] (http://fortawesome.github.io/Font-Awesome/) - a popular icon font with WordPress theme designers.

## INSTALLATION ##

First, you need to make sure the icon fonts and scripts are are enqueued on the admin screen:

```
function admin_icon_picker_scripts() {
 /* On WP 3.8+ dashicons do not need to be enqueued in the admin area   
    $font1 = plugin_dir_url( __FILE__ ) . 'fonts/dashicons/css/dashicons.css';
 	wp_enqueue_script( 'dashicons', $font1, '', '');
 */    
    $font2 = plugin_dir_url( __FILE__ ) . 'fonts/font-awesome/css/font-awesome.css';
    wp_enqueue_style( 'font-awesome', $font2,'','');

	$font3 = plugin_dir_url( __FILE__ ) . 'fonts/genericons/genericons.css';
	wp_enqueue_style( 'genericons', $font3, '', '');
    
    $css = plugin_dir_url( __FILE__ ) . '/css/icon-picker.css';
    wp_enqueue_style( 'dashicons-picker', $css, array( 'dashicons' ), '1.0' );
    
    $js = plugin_dir_url( __FILE__ ) . '/js/icon-picker.js';
    wp_enqueue_script( 'dashicons-picker', $js, array( 'jquery' ), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'admin_icon_picker_scripts' );
```
In order to actually display the icons on the front-end of your blog you also need to enqueue them there (either in your plugin or theme functions)

```
function frontend_icon_picker_scripts() {
	// $font1 (assuming WP 3.8+ that already has dashicons //
	wp_enqueue_style( 'dashicons');
    $font2 = plugin_dir_url( __FILE__ ) . 'fonts/font-awesome/css/font-awesome.css';
    wp_enqueue_style( 'font-awesome', $font2,'','');
	$font3 = plugin_dir_url( __FILE__ ) . 'fonts/genericons/genericons.css';
	wp_enqueue_style( 'genericons', $font3, '', '');
}
add_action( 'wp_enqueue_scripts', 'frontend_icon_picker_scripts' );
```

First, include both the dashicons-picker.css and dashicons-picker.js files:

```
function dashicons_picker_scripts() {
	$css = plugin_dir_url( __FILE__ ) . 'css/dashicons-picker.css';
    wp_enqueue_style( 'dashicons-picker', $css, array( 'dashicons' ), '1.0' );

	$js = plugin_dir_url( __FILE__ ) . 'js/dashicons-picker.js';
	wp_enqueue_script( 'dashicons-picker', $js, array( 'jquery' ), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'dashicons_picker_scripts' );</pre>
```

## Options Page ##

In the HTML of your plugin Options/Settings page just include the class of "icon-picker" and include a data-target attribute which stores the selector.
You can show the stored value as a text input if you like, but the preferred display is to use the button itself to SHOW the selected icon:

```
	<input class="regular-text" type="hidden" id="icon_picker_example_icon1" name="icon_picker_settings[icon1]" value="<?php if( isset( $options['icon1'] ) ) { echo esc_attr( $options['icon1'] ); } ?>"/>
	<div id="preview_icon_picker_example_icon1" data-target="#icon_picker_example_icon1" class="button icon-picker <?php if( isset( $options['icon1'] ) ) { $v=explode(',',$options['icon1']); echo $v[0].' '.$v[1]; } ?>"></div>
```
If you prefer to use a class-based settings that utilizes the WP Settings API it would look like this (format from weDevs Settings API wrapper class by Tareq Hasan @ http://tareq.weDevs.com):

```
    function callback_icon( $args ) {
    	$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
    	$size = isset( $ars['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
 		if ($value != '') { $preview = explode(',',$value); } else { $preview = array('','');};
    	
    	$html = sprintf( '<input class="%1$s-text" id="%2$s_%3$s" name="%2$s[%3$s]" type="hidden" value="'.$value.'"  />', $size, $args['section'], $args['id'], $value );
		$html .= sprintf( '<div id="preview_%2$s_%3$s" class="button icon-picker '.$preview[0].' '.$preview[1].'" data-target="#%2$s_%3$s"></div>',$size, $args['section'], $args['id'], $value);

    	echo $html;
    }
```
Notice that the value is being exploded!  The selection is stored as a comma-delimited list such as "fa,fa-inbox" and needs tob exploded into an array:

```
	$value = array([0]=>'fa',[1]=>'fa-inbox');
	$font = $value[0];
	$icon = $value[1];
```

## Plugin Example ##
Download this [entire repo](https://github.com/bradvin/dashicons-picker/archive/master.zip) as a zip, and install it like you would any other plugin. 
A new settings page called "Icon Picker Example" will be available where you can play with.  This is just an example to show you how it works.  To include 
the Icon Picker in your own plugin just drop the entire "icon-picker" folder into YOUR plugin's directory and enqueue the scripts and stylesheets as shown 
above.  Again, note that this example does NOT include the actual fonts!  Download them at the links above, drop them into the "icon-picker/fonts/" directory 
and enqueue them from there on both your Admin area and the front-end.

## Thanks ##

Adapted from [Dashicons Picker](https://github.com/bradvin/dashicons-picker) by Brad Vincent @ http://themergency.com)