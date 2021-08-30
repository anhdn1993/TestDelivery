define([
    'jquery',
    'ko',
    'uiComponent',
    'Test_Delivery/js/model/delivery-date',
    "Magento_Customer/js/customer-data",
    "mage/translate"
], function ($, ko, Component, deliveryModel, customerDataCheckOut, $t) {
    'use strict';

    var deliveryEnable = window.checkoutConfig.shipping.delivery_enable;
    var deliveryDisableDate = window.checkoutConfig.shipping.delivery_disable_date;
    var holidayDisableDate = window.checkoutConfig.shipping.holiday_disable_date;
    var maxDate = window.checkoutConfig.shipping.max_date;
    maxDate = maxDate -1;
    var dataTimeInterval = window.checkoutConfig.shipping.delivery_date.data_time_interval;
    var deliveryDateNote = window.checkoutConfig.shipping.delivery_date.delivery_date_note;
    var dateFormat = window.checkoutConfig.shipping.delivery_date.date_format;
    var deliveryDateRequired = window.checkoutConfig.shipping.delivery_date.delivery_date_required;
    var selectShowCustomer = window.checkoutConfig.shipping.delivery_date.select_show_customer;
    var dataCustomerGroup = window.checkoutConfig.shipping.delivery_date.data_customer_group;
    var arrDataCustomerGroup = [];
    if(dataCustomerGroup != null){
        var arrDataCustomerGroup = dataCustomerGroup.split(',');
    }
    var selectShippingMethod = window.checkoutConfig.shipping.delivery_date.select_shipping_method;
    var dataShippingMethod = window.checkoutConfig.shipping.delivery_date.data_shipping_method;
    var arrDataShippingMethod = [];
    if(dataShippingMethod != null){
        var arrDataShippingMethod = dataShippingMethod.split(',');
    }

    var deliveryTimeEnable = window.checkoutConfig.shipping.delivery_time.delivery_time_enable;
    var deliveryTimeNote = window.checkoutConfig.shipping.delivery_time.delivery_time_note;
    var deliveryTimeRequired = window.checkoutConfig.shipping.delivery_time.delivery_time_required;

    var deliveryCommentEnable = window.checkoutConfig.shipping.delivery_comment.delivery_comment_enable;
    var deliveryCommentNote = window.checkoutConfig.shipping.delivery_comment.delivery_comment_note;
    var deliveryCommentRequired = window.checkoutConfig.shipping.delivery_comment.delivery_comment_required;


    var deliveryData = window.checkoutConfig.shipping.delivery_date_data;

    var shippingMethod = window.checkoutConfig.selectedShippingMethod;

    var customerData =  window.checkoutConfig.customerData;
    var nameShippingMethod = '';
    if(shippingMethod != null ){
        nameShippingMethod = shippingMethod.carrier_code + '_' + shippingMethod.method_code;
    }

    var checkoutData = customerDataCheckOut.get('checkout-data')();

    // show for reload page
    if(checkoutData.selectedShippingRate){
            if(selectShippingMethod == true && arrDataShippingMethod.indexOf(checkoutData.selectedShippingRate) >= 0){
                $("#show-delivery-date").css('display' , 'block');
            }
        }

    // select shipping method
    $('body').on('click', '.table-checkout-shipping-method input[type="radio"]', function(){
        if(selectShippingMethod == true && dataShippingMethod != null){
            if(arrDataShippingMethod.indexOf($(this).val()) != -1){
                $("#show-delivery-date").css('display' , 'block');
            } else {
                $("#show-delivery-date").css('display' , 'none');
            }
        }
    });

    if(deliveryEnable === true){
        if(selectShowCustomer == true && customerData.group_id ==  null ||selectShowCustomer == true && dataCustomerGroup!= null && customerData.group_id !=  null && arrDataCustomerGroup.indexOf(customerData.group_id) == -1){
            return Component.extend({
                initialize: function () {
                    this._super();
                }
            });
        }

        return Component.extend({
            defaults: {
                template: 'Test_Delivery/delivery-input-date'
            },

            initialize: function () {
                this._super();
                var self = this;
                self.showData();

                return this;
            },

            showData : function(){
                if (typeof  deliveryData !== 'undefined' ) {
                    deliveryModel.setDeliveryDate(deliveryData.delivery_date_data_date);
                    deliveryModel.setDeliveryTime(deliveryData.delivery_date_data_time);
                    deliveryModel.setDeliveryComment(deliveryData.delivery_date_data_comment);
                }

                self.getDisabledTime = ko.observable(deliveryTimeEnable);
                self.getDisabledComment = ko.observable(deliveryCommentEnable);
                self.contentNoteDate = ko.pureComputed(function() {
                    if(deliveryDateNote){
                        return '(Note: ' + deliveryDateNote + ")";
                    }
                    return null;
                });

                self.showByShippingSelect = ko.pureComputed(function() {
                    if (selectShippingMethod === false) {
                        return  'block';
                    } else if (selectShippingMethod === true && dataShippingMethod == null ) {
                        return  'none';
                    } else if (selectShippingMethod === true && checkoutData.selectedShippingRate == null) {
                        return  'block';
                    }
                    if(checkoutData.selectedShippingRate){
                        if(selectShippingMethod === true && arrDataShippingMethod.indexOf(checkoutData.selectedShippingRate) === -1){
                            return  'none';
                        }
                    }
                    return 'block';
                });

                self.contenDatetRequired = ko.pureComputed(function() {
                    if(deliveryDateRequired){
                        return '*';
                    }

                    return null;
                });

                self.contentCommentRequired = ko.pureComputed(function() {
                    if(deliveryCommentRequired){
                        return '*';
                    }

                    return null;
                });

                self.contentTimeRequired = ko.pureComputed(function() {
                    if(deliveryTimeRequired){
                        return '*';
                    }

                    return null;
                });

                self.dateCheckRequired = ko.pureComputed(function() {
                    if(deliveryDateRequired){
                        return 'required';
                    }

                    return null;
                });

                self.contentNoteTime = ko.pureComputed(function() {
                    if(deliveryTimeNote){
                        return '(Note: ' + deliveryTimeNote + ")";
                    }
                    return null;
                });
                self.contentNoteComment = ko.pureComputed(function() {
                    if(deliveryCommentNote){
                        return '(Note: ' + deliveryCommentNote + ")";
                    }
                    return null;
                });

                // add array to select time interval
                self.availableCountries = ko.observableArray(dataTimeInterval);

                var defaults = {
                    dateFormat: 'mm\/dd\/yyyy',
                    showsTime: false,
                    timeFormat: null,
                    buttonImage: null,
                    buttonImageOnly: null,
                    buttonText: $t('Select Date')
                };
                //set datepicker
                ko.bindingHandlers.datepicker = {
                    init: function (el, valueAccessor) {
                        var config = valueAccessor(),
                            observable,
                            options = {};

                        function hiddenDate(date){
                            var dateHolidays = [];

                            var flag = true;
                            holidayDisableDate.forEach(function(holiday) {
                                var dateHoliday = new Date(holiday);
                                if(dateHoliday.getDate() === date.getDate() &&
                                    dateHoliday.getMonth() === date.getMonth() &&
                                    dateHoliday.getFullYear() === date.getFullYear()) {
                                    flag = false;
                                }
                            });
                            if(!flag) {
                                return [false];
                            }
                            var res = [true];
                            if(null != deliveryDisableDate){
                                var arr = deliveryDisableDate.split(',');

                                arr.forEach(function(dateHide) {
                                    if(date.getDay() == dateHide){
                                        res = [false]
                                    }
                                });
                            }

                            return res;
                        }
                        options = {
                            minDate: 0,
                            showOtherMonths: true,
                            selectOtherMonths: true,
                            dateFormat: dateFormat,
                            startDate:'0d',
                            maxDate:'+'+ maxDate + "d",
                            beforeShowDay: hiddenDate,
                        };
                        _.extend(options, defaults);

                        if (typeof config === 'object') {
                            observable = config.storage;
                            _.extend(options, config.options);
                        } else {
                            observable = config;
                        }

                        require(['mage/calendar'], function () {
                            $(el).calendar(options);

                            /*ko.utils.registerEventHandler(el, 'change', function () {
                                observable(this.value);
                            });*/
                        });
                    },
                    update: function (element, valueAccessor) {
                        var widget = $(element).data("DatePicker");
                        //when the view model is updated, update the widget
                        if (widget) {
                            var date = ko.utils.unwrapObservable(valueAccessor());
                            widget.date(date);
                        }
                    }

                };
            }

        });

    } else {
        return Component.extend({

            initialize: function () {
                this._super();
            }
        });
    }

});
