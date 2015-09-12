<?
// Uninstall Script for ZappBar //

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();
 
	delete_option('zappbar_site');
	delete_option('zappbar_social');
	delete_option('ryuzine_colors');
	delete_option('zappbar_panels');
	delete_option('zappbar_layout');

function uninstallMsg()
{
echo '<div class="error">
       <p>ZappBar was unable to delete some database entries</p>
    </div>';
}  

add_action('admin_notices', 'uninstallMsg');

?>
