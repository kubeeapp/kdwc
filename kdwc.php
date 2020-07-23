<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.kubee.ro/
 * @since             1.0.0
 * @package           KDWC
 *
 * @wordpress-plugin
 * Plugin Name:       Kd-Wc
 * Plugin URI:        http://www.kubee.ro/
 * Description:       Display different content to different visitors. Simple to use, just select a condition and set content accordingly.
 * Version:           1.5.0.1
 * Author:            If So Plugin
 * Author URI:        http://www.kubee.ro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kd-wc
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


update_option('edd_kdwc_license_status', 'valid');
update_option('edd_kdwc_license_key', '****-****-****-********');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kd-wc-activator.php
 */
function activate_kd_wc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kd-wc-activator.php';
	Kd_Wc_Activator::activate();
    create_kdwc_tables();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kd-wc-deactivator.php
 */
function deactivate_kd_wc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kd-wc-deactivator.php';
	Kd_Wc_Deactivator::deactivate();
}
function uninstall_kd_wc() {
    //Actual uninstall routine is located in uninstall.php
	//require_once plugin_dir_path( __FILE__ ) . 'includes/class-kd-wc-uninstall.php';
	//Kd_Wc_Uninstall::uninstall();
}
function create_kdwc_tables() {
	require_once plugin_dir_path( __FILE__ ) . 'extensions/kdwc-tables/kdwc-table-creator.php';
}
register_activation_hook( __FILE__, 'activate_kd_wc' );
register_deactivation_hook( __FILE__, 'deactivate_kd_wc' );
register_uninstall_hook(__FILE__, 'uninstall_kd_wc');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kd-wc.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kd_wc_plugin() {
	$plugin = new Kd_Wc();
	$plugin->run();
}
run_kd_wc_plugin();
// wrap function for do_shortcode
function kdwc($id) {
	$shortcode = sprintf( '[kdwc id="%1$d"]', $id);
	echo do_shortcode($shortcode);
}
//Create Kd-Wc tables
register_activation_hook( __FILE__, 'jal_install' );

