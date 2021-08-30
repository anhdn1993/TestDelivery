var config = {
    map:{
        '*':{

            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': 'Test_Delivery/js/model/shipping-save-processor/payload-extender'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Test_Delivery/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Test_Delivery/js/model/place-order-mixin': true
            }
        }
    }
};
