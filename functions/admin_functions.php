<?php
function register_zb_menu() {
	register_nav_menu( 'zb-menu-left', 'ZappBar Menu Left' );
	register_nav_menu( 'zb-menu-right', 'ZappBar Menu Right');
}
add_action( 'init','register_zb_menu');



?>