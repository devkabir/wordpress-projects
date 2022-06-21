<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       Dev Kabir
 * Plugin URI:        https://devkabir.github.io/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Dev Kabir
 * Author URI:        https://devkabir.github.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dev-kabir
 * Domain Path:       /languages
 * @package           Dev_Kabir
 *
 * @link              https://devkabir.github.io/
 * @since             1.0.0
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    exit;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
const DEV_KABIR_VERSION = '1.0.0';
const DEV_KABIR_NAME    = 'dev-kabir';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dev-kabir-activator.php
 */
function activate_dev_kabir()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-dev-kabir-activator.php';
    Dev_Kabir_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dev-kabir-deactivator.php
 */
function deactivate_dev_kabir()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-dev-kabir-deactivator.php';
    Dev_Kabir_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dev_kabir' );
register_deactivation_hook( __FILE__, 'deactivate_dev_kabir' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dev-kabir.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dev_kabir()
{

    $plugin = new Dev_Kabir();
    $plugin->run();
}

run_dev_kabir();
