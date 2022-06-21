<?php

/**
 * Plugin Name
 *
 * @package           PluginPackage
 * @author            Your Name
 * @copyright         2019 Your Name or Company Name
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Name
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       plugin-slug
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */


/*
|--------------------------------------------------------------------------
| Step 1. Define Constants
|--------------------------------------------------------------------------
|
| Some text and value's will need in several places during  plugin 
| development. To avoid typo we can declare some constants.
|
*/
const PLUGIN_NAME = 'plugin-slug';
const PLUGIN_VERSION = '1.0.0';
const PLUGIN_VUE_JS = PLUGIN_NAME.'-vue';
define('PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_DIR_URL', plugin_dir_url(__FILE__));


/*
|--------------------------------------------------------------------------
| Step 2. Register Vue.JS and plugin script
|--------------------------------------------------------------------------
|
| Lets register vue.js and plugin's js file. This will be enqueued later 
| using the wp_enqueue_script() function.
|
*/
function dev_kabir_add_script() {
    wp_register_script( PLUGIN_VUE_JS, 'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js');
    wp_register_script(PLUGIN_NAME, PLUGIN_DIR_URL .'app.js', PLUGIN_VUE_JS,PLUGIN_VERSION, true );
}
add_action('wp_enqueue_scripts', 'dev_kabir_add_script');

/*
|--------------------------------------------------------------------------
| Step 3. Load and run script
|--------------------------------------------------------------------------
|
| Lets register vue.js and plugin's js file. This will be enqueued later 
| using the wp_enqueue_script() function.
|
*/
function dev_kabir_render_form(){
    //Add Vue.js
    wp_enqueue_script(PLUGIN_VUE_JS);
    //Add my code to it
    wp_enqueue_script(PLUGIN_NAME);
    // include view file
    ob_start();
    include PLUGIN_DIR_PATH. "/views/app.php";
    return ob_get_clean();
}
add_shortcode( 'wp-vue', 'dev_kabir_render_form');
