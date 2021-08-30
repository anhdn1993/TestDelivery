/**
 * Mixin file validate delivery date field
 */

define (['jquery', 'mage/translate'], function($, $t) {
    'use strict';

    var mixin = {
        validateShippingInformation : function () {
            var component = this._super();

            var deliveryEnabled = window.checkoutConfig.shipping.delivery_enable;
            var deliveryDateRequired = window.checkoutConfig.shipping.delivery_date.delivery_date_required;
            var deliveryTimeRequired = window.checkoutConfig.shipping.delivery_time.delivery_time_required;
            var deliveryCommentRequired = window.checkoutConfig.shipping.delivery_comment.delivery_comment_required;

            if (deliveryEnabled) {
                var statusDate = true, statusTime = true, statusComment = true;
                var arrayError = [];
                var messageError;

                var dateElement = $('[name="delivery-date"]');
                if (deliveryDateRequired && dateElement && dateElement.val().length <= 0) {
                    arrayError.push("Delivery Date");
                    statusDate = false;
                }

                var time = $('[name="delivery-time"]');
                if (deliveryTimeRequired && time && time.val() == null) {
                    arrayError.push("Delivery Time");
                    statusTime = false;
                }

                var comment = $('[name="delivery-comment"]');
                if (deliveryCommentRequired && comment && comment.val().length <= 0) {
                    arrayError.push("Delivery Comment");
                    statusComment = false;
                }

                if (!statusDate || !statusTime || !statusComment) {
                    messageError = arrayError.length > 1 ? arrayError.join(", ") + " are require fields." : arrayError[0] + " is require field.";
                    this.errorValidationMessage($t(messageError));
                    return false;
                }
            }

            return component;
        }
    };

    return function (target) {
        return target.extend(mixin);
    }
});
