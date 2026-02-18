( function( wp ) {
    const { registerPaymentMethod } = wc.wcBlocksRegistry;
    const { createElement } = wp.element;

    registerPaymentMethod( {
        name: 'zelle',
        label: 'Zelle',
        content: createElement(
            'div',
            null,
            'You will receive Zelle payment instructions after placing your order.'
        ),
        edit: createElement(
            'div',
            null,
            'You will receive Zelle payment instructions after placing your order.'
        ),
        canMakePayment: () => true,
        ariaLabel: 'Zelle payment method',
        supports: {
            features: [ 'products' ],
        },
    } );
} )( window.wp );