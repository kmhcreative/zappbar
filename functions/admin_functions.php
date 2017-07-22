<?php
function register_zb_menu() {
	register_nav_menu( 'zb-menu-left', 'ZappBar Menu Left' );
	register_nav_menu( 'zb-menu-right', 'ZappBar Menu Right');
}
add_action( 'init','register_zb_menu');


function reset_zappbar_options(){
if (isset($_POST['reset'])) {
if ( ! wp_verify_nonce( $_POST['zb_reset_nonce'], basename(__FILE__) ) ) {
		zb_add_defaults( $reset = true );				
	} else {
		return false;
	}
}
}
add_action('init','reset_zappbar_options');
?>