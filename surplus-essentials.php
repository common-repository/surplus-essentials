<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wordpress.org/plugins/surplus-essentials/
 * @since             1.0.0
 * @package           surplus_essentials
 *
 * @wordpress-plugin
 * Plugin Name:       Surplus Essentials
 * Plugin URI:        https://wordpress.org/plugins/surplus-essentials/
 * Description:       Surplus Essentials Provides necessary features to extend WordPress functionality and for better blogging experience.
 * Version:           1.0.3
 * Author:            surplusthemes
 * Author URI:        https://surplusthemes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       surplus-essentials
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'STEP_PLUGIN_VERSION', '1.0.0' );
define( 'STEP_BASE_PATH', dirname( __FILE__ ) );
define( 'STEP_FILE_PATH', __FILE__ );
define( 'STEP_FILE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
add_image_size( 'post-slider-thumb-size', 330, 190, true ); 

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-surplus-essentials-activator.php
 */
function activate_surplus_essentials() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-surplus-essentials-activator.php';
	Surplus_Essentials_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-surplus-essentials-deactivator.php
 */
function deactivate_surplus_essentials() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-surplus-essentials-deactivator.php';
	Surplus_Essentials_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_surplus_essentials' );
register_deactivation_hook( __FILE__, 'deactivate_surplus_essentials' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-surplus-essentials.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_surplus_essentials() {

	$plugin = new Surplus_Essentials();
	$plugin->run();

}
run_surplus_essentials();
