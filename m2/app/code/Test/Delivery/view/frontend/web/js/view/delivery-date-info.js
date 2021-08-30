
define([
    'jquery',
    'ko',
    'uiComponent',
    'Test_Delivery/js/model/delivery-date'
], function ($, ko, Component, delivery) {
    'use strict';
    var self;
    return Component.extend({
        defaults: {
            template: 'Test_Delivery/delivery-date-info'
        },
        deliveryDate: ko.observable(''),
        deliveryTime: ko.observable(''),
        deliveryComment: ko.observable(''),

        initialize: function () {
            this._super();
            self = this;
            self.isShowedDeliveryDateForm = ko.observable(window.checkoutConfig.shipping.delivery_enable);

            this.deliveryDate(delivery.deliveryDate);
            this.deliveryTime(delivery.deliveryTime);
            this.deliveryComment(delivery.deliveryComment);
            return this;
        },
    });
});