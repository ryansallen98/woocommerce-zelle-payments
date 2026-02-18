<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WC_Zelle_Blocks extends AbstractPaymentMethodType {

    protected $name = 'zelle';

    public function initialize() {
        $this->settings = get_option( 'woocommerce_zelle_settings', [] );
    }

    public function is_active() {
        return ! empty( $this->settings['enabled'] ) && $this->settings['enabled'] === 'yes';
    }

    public function get_payment_method_script_handles() {
        return [ 'wc-zelle-blocks' ];
    }

    public function get_payment_method_data() {
        return [
            'title'       => $this->settings['title'] ?? 'Zelle',
            'description' => $this->settings['description'] ?? '',
        ];
    }
}

add_action(
    'woocommerce_blocks_payment_method_type_registration',
    function( $payment_method_registry ) {
        $payment_method_registry->register( new WC_Zelle_Blocks() );
    }
);