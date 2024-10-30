<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/jt-express/
 * @since             1.0.0
 * @package           JT_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       J&T Express Singapore
 * Plugin URI:        https://wordpress.org/plugins/jt-express/
 * Description:       J&T Express is a One-Stop eCommerce Logistics Provider that allows all online shops to offer seamless shipping to customers world-wide with affordable shipping rates.
 * Version:           1.0.2
 * Author:            J&T Team
 * Author URI:        https://www.jtexpress.sg/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:      jt-express
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JT_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jtwc-activator.php
 */
function activate_jt_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jtwc-activator.php';
	JTWC_Activator::activate();
}

function jt_get_allowed_capability() {
    return 'manage_woocommerce';
}
function jt_get_option($key, $default = null) {
    $options = get_option('jt-woocommerce');
    if (!is_array($options)) {
        return $default;
    }
    if (!array_key_exists($key, $options)) {
        return $default;
    }
    return $options[$key];
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jtwc-deactivator.php
 */
function deactivate_jt_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jtwc-deactivator.php';
	JTWC_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jt_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_jt_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-jtwc.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-jtwc-order-list-table.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-jtwc-delivery-order-list-table.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-jtwc-return-order-list-table.php';


/**
 * @return object
 */
function jt_environment_variables() {
    global $wp_version;

    $o = get_option('jt-woocommerce', false);

    return (object) array(
        'repo' => 'master',
        'environment' => 'production', // staging or production
        'version' => '1.0.0',
        'php_version' => phpversion(),
        'wp_version' => (empty($wp_version) ? 'Unknown' : $wp_version),
        'wc_version' => function_exists('WC') ? WC()->version : null,
        'login_url' => 'https://jts.jtexpress.sg/jts-service-doorstep/api/gateway/v1/auth/login',
        'connote_url' => 'https://jts.jtexpress.sg/jts-service-doorstep/api/gateway/v1/deliveries/connote',
        'multiple_connote_url' => 'https://jts.jtexpress.sg/jts-service-doorstep/api/gateway/v1/deliveries/batchGetConnote',
    );
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jt_woocommerce() {

	$plugin = new JTWC();
	$plugin->run();

}
run_jt_woocommerce();
