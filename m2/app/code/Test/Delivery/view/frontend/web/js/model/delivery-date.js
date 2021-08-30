define(
    [
        'ko',
        'jquery',
        'uiComponent',
    ],
    function (ko) {
        'use strict';
        return {
            deliveryDate :  ko.observable(null),
            deliveryTime: ko.observable(null),
            deliveryComment: ko.observable(null),

            setDeliveryDate: function(date) {
                this.deliveryDate(date);
            },

            setDeliveryTime: function(time) {
                this.deliveryTime(time);
            },

            setDeliveryComment: function(comment) {
                this.deliveryComment(comment);
            }
        };


    }
);
