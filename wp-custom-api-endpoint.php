<?php

/**
 * Add custom api endpoint for shop subscriptions
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Custom api endpoint for shop subscriptions
 * Plugin URI:        https://github.com/devkabir/wordpress-projects/tree/wp-custom-api-endpoint
 * Description:       An php toolkit for adding custom api endpoint via wp rest api.
 * Version:           1.0.0
 * Author:            Dev Kabir
 * Author URI:        https://devkabir.github.io/
 * Text Domain:       wp-custom-api-endpoint
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/devkabir/wordpress-projects/tree/wp-custom-api-endpoint
 * GitHub Branch:     wp-custom-api-endpoint
 * @package   Wp_Custom_API_Endpoint
 *
 * @author Dev Kabir <dev.kabir01@gmail.com>
 * @copyright 2022 Dev Kabir
 * @license   GPL-2.0+
 *
 * @link      https://github.com/devkabir/wordpress-projects/tree/wp-custom-api-endpoint
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    exit;
}

/**
 * Get Data from database
 *
 * @author Dev Kabir <dev.kabir01@gmail.com>
 *
 * @return WP_REST_Response
 */
function fetch_data_from_db()
{
    $dp   = wc_get_price_decimals();
    $args = [
        'post_type'   => 'shop_subscription',
        'post_status' => ['wc-pending', 'wc-active', 'wc-on-hold', 'wc-cancelled', 'wc-switched', 'wc-expired', 'wc-pending-cancel'],

    ];

    $query  = new WP_Query( $args );
    $return = [];
    foreach ( $query->posts as $value ) {
        $order = wc_get_order( $value );
        $data  = [];
        // Add line items.
        foreach ( $order->get_items() as $item_id => $item ) {
            $product      = $item->get_product();
            $product_id   = 0;
            $variation_id = 0;
            $product_sku  = null;

            // Check if the product exists.
            if ( is_object( $product ) ) {
                $product_id   = $item->get_product_id();
                $variation_id = $item->get_variation_id();
                $product_sku  = $product->get_sku();
            }

            $line_item = [
                'id'           => $item_id,
                'name'         => $item['name'],
                'sku'          => $product_sku,
                'product_id'   => (int) $product_id,
                'variation_id' => (int) $variation_id,
                'quantity'     => wc_stock_amount( $item['qty'] ),
                'tax_class'    => !empty( $item['tax_class'] ) ? $item['tax_class'] : '',
                'price'        => wc_format_decimal( $order->get_item_total( $item, false, false ), $dp ),
                'subtotal'     => wc_format_decimal( $order->get_line_subtotal( $item, false, false ), $dp ),
                'subtotal_tax' => wc_format_decimal( $item['line_subtotal_tax'], $dp ),
                'total'        => wc_format_decimal( $order->get_line_total( $item, false, false ), $dp ),
                'total_tax'    => wc_format_decimal( $item['line_tax'], $dp ),
                'taxes'        => [],
            ];

            $item_line_taxes = maybe_unserialize( $item['line_tax_data'] );
            if ( isset( $item_line_taxes['total'] ) ) {
                $line_tax = [];

                foreach ( $item_line_taxes['total'] as $tax_rate_id => $tax ) {
                    $line_tax[$tax_rate_id] = [
                        'id'       => $tax_rate_id,
                        'total'    => $tax,
                        'subtotal' => '',
                    ];
                }

                foreach ( $item_line_taxes['subtotal'] as $tax_rate_id => $tax ) {
                    $line_tax[$tax_rate_id]['subtotal'] = $tax;
                }

                $line_item['taxes'] = array_values( $line_tax );
            }

            $data[] = $line_item;
        }
        $value->line_item    = $line_item;
        $value->custom_field = get_post_meta( $value->ID );
        $return[]            = $value;
    }
    $response = new WP_REST_Response( $return );
    $response->set_status( 200 );
    return $response;
}

/**
 * Delete data from database
 *
 * @author Dev Kabir <dev.kabir01@gmail.com>
 *
 * @param  array              $data
 * @return WP_REST_Response
 */
function delete_data_from_db( $data )
{
    try {
        wp_delete_post( $data['id'] );
        $return   = 'Subscription Deleted';
        $response = new WP_REST_Response( $return );
        $response->set_status( 200 );
    } catch ( \Throwable $th ) {
        $return   = $th->get_message();
        $response = new WP_REST_Response( $return );
        $response->set_status( $th->get_code() );
    }

    return $response;
}

/**
 * Register api endpoints
 *
 * @author Dev Kabir <dev.kabir01@gmail.com>
 *
 * @return void
 */
function add_endpoints()
{
    register_rest_route( 'dev-kabir/v1', '/shop-subscription', [
        'methods'  => 'GET',
        'callback' => 'fetch_data_from_db',
    ] );
    register_rest_route( 'dev-kabir/v1', '/shop-subscription/(?P<id>[\d]+)', [
        'methods'  => 'GET',
        'callback' => 'delete_data_from_db',
        'args'     => [
            'id' => [
                'validate_callback' => function ( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ] );
}

add_action( 'rest_api_init', 'add_endpoints' );
