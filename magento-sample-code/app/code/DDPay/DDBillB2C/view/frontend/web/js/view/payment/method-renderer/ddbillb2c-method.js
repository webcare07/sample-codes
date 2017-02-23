/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Payment/js/view/payment/cc-form',
        'jquery',
        'Magento_Payment/js/model/credit-card-validation/validator',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'Magento_Customer/js/model/customer',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ( Component, $, creditCardValidators, quote, urlBuilder, customer, storage, errorProcessor, fullScreenLoader ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'DDPay_DDBillB2C/payment/ddbillb2c-form'
            },
			redirectAfterPlaceOrder: false,

            getCode: function() {
                return 'ddpay_ddbillb2c';
            },

            isActive: function() {
                return true;
            },

            validate: function() {
                var $form = $('#' + this.getCode() + '-form');
                return $form.validation() && $form.validation('isValid');
            },
			
			placeOrder: function (data, event) {
				var self = this;
				if (event) {
					event.preventDefault();
				}
			
				if( this.validate() ){
					this.isPlaceOrderActionAllowed(false);
					var newData = this.getData();
					var ddbillb2c_bank_code = $('#ddbillb2c_bank_code').val();
					var serviceUrl, payload, messageContainer;
		
					payload = {
						cartId: quote.getQuoteId(),
						billingAddress: quote.billingAddress(),
						paymentMethod: newData,
						ddbillb2c_bank_code: ddbillb2c_bank_code
					};
		
					if (customer.isLoggedIn()) {
						serviceUrl = urlBuilder.createUrl('/carts/mine/payment-information', {});
					} else {
						serviceUrl = urlBuilder.createUrl('/guest-carts/:quoteId/payment-information', {
							quoteId: quote.getQuoteId()
						});
						payload.email = quote.guestEmail;
					}

					if( window.authenticationPopup.baseUrl )
						serviceUrl = window.authenticationPopup.baseUrl + serviceUrl;
					else
						serviceUrl = '/' + serviceUrl;

					fullScreenLoader.startLoader();
					messageContainer = this.messageContainer;
					
					var rtn = storage.post(
						serviceUrl, JSON.stringify(payload)
					).done(
						function(response){
							
							if( $('#formDinpayFormB2C').length != 0 )
								$('#formDinpayFormB2C').html(response.html);
							else
								$('body').append('<div id="formDinpayFormB2C" style="display:none;">'+response.html+'</div>');
								
							if( $('form#dinpayFormB2C').length != 0 )
								$('form#dinpayFormB2C').submit();
							else
							{
								messageContainer.addErrorMessage("Payment Error: Invalid data.");
								fullScreenLoader.stopLoader();
								this.isPlaceOrderActionAllowed(true);
							}
						}
					).fail(
						function(response){
							messageContainer.addErrorMessage("Payment Error: Please contact site administrator");
							fullScreenLoader.stopLoader();
							this.isPlaceOrderActionAllowed(true);
						}
					);
					
					return false;
				}
			
				return false;
			}
        });
    }
);
