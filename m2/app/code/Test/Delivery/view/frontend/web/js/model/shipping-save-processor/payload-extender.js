define(['jquery', 'Test_Delivery/js/model/delivery-date',], function ($, deliveryModel) {
    'use strict';

    return function (payload) {
        payload.addressInformation['extension_attributes'] = {};

        //@todo add validate before append
        var date = $('[name="delivery-date"]').val();
        var time = $('[name="delivery-time"]').val();
        var comment = $('[name="delivery-comment"]').val();

        deliveryModel.setDeliveryDate(date);
        deliveryModel.setDeliveryTime(time);
        deliveryModel.setDeliveryComment(comment);

        payload.addressInformation['extension_attributes'] = {'delivery_date':date, 'delivery_time':time, 'delivery_comment':comment};
        return payload;
    };
});
