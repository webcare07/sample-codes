/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'ddpay_ddbillb2c',
                component: 'DDPay_DDBillB2C/js/view/payment/method-renderer/ddbillb2c-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);