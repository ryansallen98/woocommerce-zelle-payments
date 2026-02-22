<?php
/**
 * Plugin Name: WooCommerce Zelle Payments
 * Plugin URI: https://github.com/ryansallen98/woocommerce-zelle-payments
 * Description: Adds Zelle as a manual payment method similar to bank transfer (BACS) to WooCommerce Checkout.
 * Version: 1.0.2
 * Author: Ryan Allen
 * Author URI: https://rallendev.com
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register block script handle early so Woo Blocks dependency graph is satisfied.
 */
add_action( 'init', function() {
    wp_register_script(
        'wc-zelle-blocks',
        plugins_url( 'zelle-blocks.js', __FILE__ ),
        array( 'wc-blocks-registry', 'wp-element', 'wp-i18n' ),
        '1.0.1',
        true
    );
} );

/**
 * Load gateway.
 */
add_action( 'plugins_loaded', function() {

    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
        return;
    }

    class WC_Gateway_Zelle extends WC_Payment_Gateway {

        public function __construct() {
            $this->id                 = 'zelle';
            $this->icon               = '';
            $this->has_fields         = false;
            $this->method_title       = 'Zelle';
            $this->method_description = 'Accept payments via Zelle (manual confirmation).';

            $this->supports = array( 'products' );

            $this->init_form_fields();
            $this->init_settings();

            $this->title         = $this->get_option( 'title' );
            $this->description   = $this->get_option( 'description' );
            $this->zelle_name    = $this->get_option( 'zelle_name' );
            $this->zelle_email   = $this->get_option( 'zelle_email' );
            $this->zelle_phone   = $this->get_option( 'zelle_phone' );
            $this->instructions  = $this->get_option( 'instructions' );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
            add_action( 'woocommerce_thankyou_' . $this->id, [ $this, 'thankyou_page' ] );
            add_action( 'woocommerce_email_instructions', [ $this, 'email_instructions' ], 10, 3 );
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'   => 'Enable/Disable',
                    'type'    => 'checkbox',
                    'label'   => 'Enable Zelle payments',
                    'default' => 'no',
                ),
                'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'default'     => 'Zelle',
                ),
                'description' => array(
                    'title'       => 'Description',
                    'type'        => 'textarea',
                    'default'     => 'Pay using Zelle. Your order will be processed after payment is received.',
                ),
                'zelle_name' => array(
                    'title' => 'Zelle Recipient Name',
                    'type'  => 'text',
                ),
                'zelle_email' => array(
                    'title' => 'Zelle Email',
                    'type'  => 'email',
                ),
                'zelle_phone' => array(
                    'title' => 'Zelle Phone',
                    'type'  => 'text',
                ),
                'instructions' => array(
                    'title'   => 'Payment Instructions',
                    'type'    => 'textarea',
                    'default' => 'Send payment via Zelle using your Order Number as the reference.',
                ),
            );
        }

        public function process_payment( $order_id ) {
            $order = wc_get_order( $order_id );
            $order->update_status( 'on-hold', 'Awaiting Zelle payment' );
            wc_reduce_stock_levels( $order_id );
            WC()->cart->empty_cart();

            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url( $order ),
            );
        }

        public function thankyou_page() {
            echo wpautop( wp_kses_post( $this->instructions ) );

            echo '<ul class="zelle-details">';
            if ( $this->zelle_name )  echo '<li><strong>Name:</strong> ' . esc_html( $this->zelle_name ) . '</li>';
            if ( $this->zelle_email ) echo '<li><strong>Email:</strong> ' . esc_html( $this->zelle_email ) . '</li>';
            if ( $this->zelle_phone ) echo '<li><strong>Phone:</strong> ' . esc_html( $this->zelle_phone ) . '</li>';
            echo '</ul>';
        }

        public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            if ( ! $sent_to_admin && $order->get_payment_method() === $this->id && $order->has_status( 'on-hold' ) ) {
                echo wpautop( wp_kses_post( $this->instructions ) );
            }
        }
    }
});

/**
 * Register gateway.
 */
add_filter( 'woocommerce_payment_gateways', function( $gateways ) {
    $gateways[] = 'WC_Gateway_Zelle';
    return $gateways;
} );

/**
 * Load Blocks integration.
 */
add_action( 'woocommerce_blocks_loaded', function() {
    if ( class_exists( '\Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
        require_once __DIR__ . '/zelle-blocks.php';
    }
} );