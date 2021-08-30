define([
    'jquery',
    'mage/utils/wrapper',
    'Test_Delivery/js/model/delivery-date'
], function ($, wrapper, deliveryModel) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {

            var date = $('[name="delivery-date"]').val();
            var time = $('[name="delivery-time"]').val();
            var comment = $('[name="delivery-comment"]').val();

            deliveryModel.setDeliveryDate(date);
            deliveryModel.setDeliveryTime(time);
            deliveryModel.setDeliveryComment(comment);

            if (paymentData['extension_attributes'] === undefined) {
                paymentData['extension_attributes'] = {};
            }

            //paymentData['extension_attributes']['delivery'] = {'delivery_date':date, 'delivery_time':time, 'delivery_comment':comment};
            //var data = {};
            //data['data'] = {'delivery_date':date, 'delivery_time':time, 'delivery_comment':comment};
            paymentData['extension_attributes']['delivery_date'] = date;
            paymentData['extension_attributes']['delivery_time'] = time;
            paymentData['extension_attributes']['delivery_comment'] = comment;
            return originalAction(paymentData, messageContainer);
        });
    };
});
