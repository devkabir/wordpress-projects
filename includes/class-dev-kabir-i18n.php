<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Dev_Kabir
 * @subpackage Dev_Kabir/includes
 *
 * @link       https://devkabir.github.io/
 * @since      1.0.0
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Dev_Kabir
 * @subpackage Dev_Kabir/includes
 *
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 *
 * @since      1.0.0
 */
class Dev_Kabir_i18n
{
    /**
     * Dev_Kabir_i18n constructor.
     * @param Dev_Kabir_Loader $loader
     */
    public function __construct( \Dev_Kabir_Loader $loader )
    {
        $loader->add_action( 'plugins_loaded', [$this, 'load_plugin_textdomain'] );
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            DEV_KABIR_NAME,
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }
}
