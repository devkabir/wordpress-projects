<?php

    /**
     * Plugin Name:     Woo-Commerce variation swiper
     * Plugin URI:      https://github.com/devkabir/wordpress-projects/tree/wc-variation-swiper
     * Description:     A php toolkit for swipe variation content on single product page
     * Author:          Dev Kabir
     * Author URI:      https://devkabir.github.io/
     * Text Domain:     wp-variation-swiper
     * Domain Path:     /languages
     * Version:         1.0.0
     *
     * @package         WC_Variation_Swiper
     */
    if ( !empty( $_GET ) ) {
        $parent_product_id = $_GET['parent'] ?? null;
        $can_sync          = $_GET['kk'] ?? false;

        if ( !empty( $parent_product_id ) && !empty( $can_sync ) ) {
            /*
             * Convert all product variation to single product
             *
             * @author Dev Kabir <dev.kabir01@gmail.com>
             *
             * @param  string  $parent_product_id selected variable product id
             * @param  string  $can_sync          is this page correct for sync
             * @return void
             */
            function kk_change_variation_to_single( $can_sync, $parent_product_id )
            {
                if ( $can_sync && $parent_product_id ) {
                    $args        = ['post_type' => 'product_variation', 'post_parent' => $parent_product_id];
                    $posts_array = get_posts( $args );

                    foreach ( $posts_array as $post ) {
                        $parent_product = wc_get_product( $parent_product_id );
                        // check product is exists or not
                        $name = str_replace( 'Models: ', '', $post->post_excerpt );
                        if ( kk_get_post_by_name( $name ) ) {
                            continue;
                        }

                        $post_id = wp_insert_post( [
                            'post_title'   => $name,
                            'post_author'  => 1,
                            'post_content' => $parent_product->get_description(),
                            'post_excerpt' => $parent_product->get_short_description(),
                            'post_status'  => 'publish',
                            'post_type'    => 'product',
                        ] );
                        wp_set_object_terms( $post_id, 'simple', 'product_type' );
                        update_post_meta( $post_id, '_visibility', $parent_product->get_catalog_visibility() );
                        update_post_meta( $post_id, '_stock_status', $parent_product->get_stock_status() );
                        update_post_meta( $post_id, '_regular_price', get_post_meta( $post->ID, '_regular_price', true ) ?? 0 );
                        update_post_meta( $post_id, '_price', get_post_meta( $post->ID, '_price', true ) ?? 0 );
                        update_post_meta( $post_id, '_sku', get_post_meta( $post->ID, 'attribute_pa_models', true ) ?? sanitize_title( $name ) );
                        update_post_meta( $post_id, '_manage_stock', get_post_meta( $post->ID, '_manage_stock', true ) ?? false );
                        update_post_meta( $post_id, '_backorders', get_post_meta( $post->ID, '_backorders', true ) ?? 'no' );
                        update_post_meta( $post_id, '_thumbnail_id', $parent_product->get_image_id() );
                        update_post_meta( $post_id, '_wp_page_template', 'default' );
                        update_post_meta( $post_id, '_parent_id', $parent_product_id );
                    }

                    wp_redirect( admin_url( 'edit.php?post_type=product' ) );
                }
            }

            add_action( 'woocommerce_after_register_post_type', 'kk_change_variation_to_single', 10, 2 );

        }
    }

    /**
     * Modify product table action row with  content
     *
     * @author Dev Kabir <dev.kabir01@gmail.com>
     *
     * @param  array    $actions
     * @param  WP_Post  $post
     * @return array
     */
    function kk_make_single( array $actions, WP_Post $post ): array
    {
        if ( $post->post_type === 'product' ) {
            $actions['make_variations_to_single_product'] = sprintf(
                '<a href="%1$s">%2$s</a>',
                admin_url( 'edit.php?post_type=product&kk=true&parent=' . $post->ID ),
                __( 'Variations to product', 'kk' )
            );
        }

        return $actions;
    }

    /**
     * Check product is exists or not by name
     *
     * @author Dev Kabir <dev.kabir01@gmail.com>
     *
     * @param  string  $name      name of the product
     * @param  string  $post_type type of product
     * @return void
     */
    function kk_get_post_by_name( string $name, string $post_type = 'product' )
    {
        $query = new WP_Query( [
            'post_type' => $post_type,
            'name'      => $name,
        ] );

        return $query->have_posts() ? reset( $query->posts ) : null;
    }

    /**
     * Load kk's script
     *
     * @author Dev Kabir <dev.kabir01@gmail.com>
     *
     * @return void
     */
    function kk_load_javascript()
    {
        wp_enqueue_style( 'kk-ajax', plugin_dir_url( __FILE__ ) . '/assets/style.css' );
        wp_enqueue_script( 'kk-ajax', plugin_dir_url( __FILE__ ) . '/assets/ajax.js', ['jquery'] );

        $variable_to_js = [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'dev-kabir-at-kk' ),
        ];
        wp_localize_script( 'kk-ajax', 'kk', $variable_to_js );
    }

    /**
     * Take ajax call and return product single page
     *
     * @author Dev Kabir <dev.kabir01@gmail.com>
     *
     * @return void
     */
    function kk_ajax_handler()
    {
        if ( !isset( $_GET['nonce'] ) || !wp_verify_nonce( $_GET['nonce'], 'dev-kabir-at-kk' ) ) {
            wp_die( -1 );
        }
        $product = $_GET['product'];
        echo do_shortcode( '[product_page id=' . $product . ']' );
        wp_die();
    }

    /**
     * Make a dropdown for products
     *
     * @author Dev Kabir <dev.kabir01@gmail.com>
     *
     * @param  array    $attrs
     */
    function kk_products_shortcode( array $attrs )
    {
        // Attributes
        $attrs = shortcode_atts(
            [
                'parent_id' => '1',
            ],
            $attrs,
            'kk_products'
        );
        $args = [
            'post_type'  => 'product',
            'meta_query' => [
                [
                    'key'   => '_parent_id',
                    'value' => $attrs['parent_id'],
                ],
            ],
        ];
        $query    = new WP_Query( $args );
        $products = wp_list_pluck( $query->posts, 'post_title', 'ID' );
    ?>
    <div class="kk-product-holder">
        <div class="kk-input-group kk-mb-3">
            <label class="kk-input-group-text" for="inputGroupSelect01">Products</label>
            <select class="kk-form-select" id="kk-products">
                <?php
                    if ( get_the_ID() == 46 ) {
                            echo '<option value="" selected hidden>' . __( 'Select one product', 'kk' ) . '</option>';
                        }
                        foreach ( $products as $id => $product ) {
                            $selected = in_array( get_the_ID(), wp_list_pluck( $query->posts, 'ID' ) ) && ( get_the_ID() == $id ) ? 'selected' : null;

                            echo "<option value=\"{$id}\" {$selected}>{$product}</option>";
                        }
                    ?>
            </select>
        </div>
    </div>
<?php

    }

add_filter( 'post_row_actions', 'kk_make_single_product', 11, 2 );
add_action( 'wp_enqueue_scripts', 'kk_enqueue_javascript' );
add_action( 'wp_ajax_kk_ajax', 'kk_get_products' );
add_action( 'wp_ajax_nopriv_kk_ajax', 'kk_ajax_handler' );
add_shortcode( 'kk_products', 'kk_products_shortcode' );

