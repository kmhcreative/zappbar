<?php
/*
	zAppBar Plugin
	This creates a customizable "sidebar" that
	slides in and out like an app panel.  It is
	ONLY visible if the zAppBars are displayed
	and you have wired a zAppBar Button to 
	activate it.
*/

function zb_sidebars() {
	if ( function_exists('register_sidebar') ){
		register_sidebar(array(
			'name' => 'ZappBar Left App Panel',
			'id' => 'zb-panel-left',
			'description' => 'A sidebar for ZappBar.  It is only visible if ZappBars are shown and you have wired a button to slide it in/out',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));
		register_sidebar(array(
			'name' => 'ZappBar Right App Panel',
			'id' => 'zb-panel-right',
			'description' => 'A sidebar for ZappBar.  It is only visible if ZappBars are shown and you have wired a button to slide it in/out',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));
	}
}
// Register Sidebar late to avoid re-ordering existing theme sidebars //
add_action( 'wp_loaded', 'zb_sidebars',99 );
?>