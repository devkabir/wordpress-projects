<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Dev_Kabir
 * @subpackage Dev_Kabir/public
 *
 * @link       https://devkabir.github.io/
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dev_Kabir
 * @subpackage Dev_Kabir/public
 *
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */
class Dev_Kabir_Public
{
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param Dev_Kabir_Loader $loader
     */
    public function __construct( \Dev_Kabir_Loader $loader )
    {

        $loader->add_action( 'wp_enqueue_scripts', [$this, 'enqueue_styles'] );
        $loader->add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Dev_Kabir_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Dev_Kabir_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( DEV_KABIR_NAME, plugin_dir_url( __FILE__ ) . 'js/dev-kabir-public.js', [ 'jquery' ], DEV_KABIR_VERSION, false );

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Dev_Kabir_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Dev_Kabir_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( DEV_KABIR_NAME, plugin_dir_url( __FILE__ ) . 'css/dev-kabir-public.css', [], DEV_KABIR_VERSION, 'all' );

    }
}
