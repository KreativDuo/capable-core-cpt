<?php
/**
 * @link              https://capable-themes.com
 * @since             1.0.0
 * @package           Capable_Core_Cpt
 *
 * @wordpress-plugin
 * Plugin Name:       CapCore Custom Post Types
 * Plugin URI:        https://capable-themes.com/capore-custom-post-types
 * Description:       Provides the core functionality for CapCore Themes by registering a 'Layouts' Custom Post Type. This allows you to create reusable headers, footers, and custom content sections with elementor. 
 * Version:           1.0.0
 * Author:            Capable Themes
 * Author URI:        https://capable-themes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       capable-core-cpt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
const CAPABLE_CORE_CPT_VERSION = '1.0.0';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-capable-core-cpt-activator.php
 */
function activate_capable_core_cpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-capable-core-cpt-activator.php';
	Capable_Core_Cpt_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-capable-core-cpt-deactivator.php
 */
function deactivate_capable_core_cpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-capable-core-cpt-deactivator.php';
	Capable_Core_Cpt_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_capable_core_cpt' );
register_deactivation_hook( __FILE__, 'deactivate_capable_core_cpt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-capable-core-cpt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_capable_core_cpt() {

	$plugin = new Capable_Core_Cpt();
	$plugin->run();

}

run_capable_core_cpt();