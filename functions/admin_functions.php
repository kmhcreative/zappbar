<?php
function register_zb_menu() {
	register_nav_menu( 'zb-menu-left', 'zAppBar Menu Left' );
	register_nav_menu( 'zb-menu-right', 'zAppBar Menu Right');
}
add_action( 'init','register_zb_menu');



?>