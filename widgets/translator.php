<?php
/*
Widget Name: google translator
Widget URI: http://www.kmhcreative.com/labs
Description: 
Author: K.M. Hansen
Author URI: http://www.kmhcreative.com/labs
Version: 1.1

Adapted from the widget by Philip M. Hofer (Frumph)
for the ComicPress theme (http://frumph.net/)

*/

if (!class_exists('zb_google_translate_widget')) {

class zb_google_translate_widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			__CLASS__, // Base ID
			__( 'ZappBar Google Translator'), // Name
			array( 'classname' => __CLASS__, 'description' => __( 'Translate your site with Google.'), ) //Args
		);
	}	
	public function widget($args, $instance) {
		global $post;
		extract($args, EXTR_SKIP); 
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']); 
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }; ?>
		<center>
			<div id="google_translate_element"></div>
		</center>
		<?php
		echo $after_widget;
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	public function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<?php
	}


}

function zb_google_translate_widget_register() {
	register_widget('zb_google_translate_widget');
}

add_action( 'widgets_init', 'zb_google_translate_widget_register');
};
?>