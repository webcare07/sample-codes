<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DDPay\DDBillB2C\Model;

use Exception;
use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\OrderFactory;

class DDBillB2C extends \Magento\Payment\Model\Method\Cc
{
    const CODE = 'ddpay_ddbillb2c';

    protected $_code = self::CODE;

    protected $_isGateway                   = true;
    protected $_canCapture                  = true;
    protected $_canCapturePartial           = false;
    protected $_canRefund                   = false;
    protected $_canRefundInvoicePartial     = false;

    protected $_countryFactory;
    protected $_urlBuilder;
    protected $_order;
    protected $_orderFactory;
    protected $orderSender;
    protected $creditmemoSender;

    protected $merchant_code = null;
    protected $sign_type = null;
    protected $public_key = null;
    protected $private_key = null;
    protected $order_status = null;
    protected $notify_url = null;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\RequestInterface $requestHttp,
        \Magento\Paypal\Model\CartFactory $cartFactory,
        OrderFactory $orderFactory,
        OrderSender $orderSender,
        CreditmemoSender $creditmemoSender,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = array()
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $moduleList,
            $localeDate,
            null,
            null,
            $data
        );

        $this->_countryFactory = $countryFactory;
        $this->_urlBuilder = $urlBuilder;
        $this->_orderFactory = $orderFactory;
        $this->orderSender = $orderSender;
        $this->creditmemoSender = $creditmemoSender;

        $this->order_status = "pending_payment";
        $this->notify_url = $this->_urlBuilder->getUrl('ddbillb2c/ipn/');
		
    }

    /**
     * Payment capturing
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Validator\Exception
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $payment->getOrder();

        /** @var \Magento\Sales\Model\Order\Address $billing */
        $billing = $order->getBillingAddress();
        $shipping = $order->getShippingAddress();
		$client_ip = $_SERVER['REMOTE_ADDR'];
		$json = @file_get_contents('php://input');
		$obj = @json_decode($json, true);
		$bank_code = isset($obj['ddbillb2c_bank_code']) ? $obj['ddbillb2c_bank_code'] : '';
		
		$amount = number_format($amount, 2, '.', '');
        $requestData = array(
            "order_amount" => $amount,
            "order_no" => $order->getIncrementId(),
            "order_time" => date('Y-m-d H:i:s', time()),
            "product_code" => "",
            "product_desc" => "Products of Order No: ".$order->getIncrementId(),
            "product_name" => "Products of Order No: ".$order->getIncrementId(),
            "product_num" => "",
            "return_url" => $this->_urlBuilder->getUrl('checkout/onepage/success/'),
            "show_url" => "",
            
			"customer_first_name" => $billing->getFirstname(),
            "customer_last_name" => $billing->getLastname(),
            "customer_email" => $order->getCustomerEmail(),
            "customer_phone" => $billing->getTelephone(),
            "customer_country" => $this->_countryFactory->create()->loadByCode($billing->getCountryId())->getName(),
            "customer_state" => $billing->getRegion(),
            "customer_city" => $billing->getCity(),
            "customer_street" => @implode(' ', $billing->getStreet()),
            "customer_zip" => $billing->getPostcode(),
			
            "ship_to_firstname" => $shipping->getFirstname(),
            "ship_to_lastname" => $shipping->getLastname(),
            "ship_to_email" => $order->getCustomerEmail(),
            "ship_to_phone" => $shipping->getTelephone(),
            "ship_to_country" => $this->_countryFactory->create()->loadByCode($shipping->getCountryId())->getName(),
            "ship_to_state" => $shipping->getRegion(),
            "ship_to_city" => $shipping->getCity(),
            "ship_to_street" => @implode(' ', $shipping->getStreet()),
            "ship_to_zip" => $shipping->getPostcode(),
            "extra_return_param" => "",
            "client_ip" => $client_ip,
			"order_button_text" => __("Pay with Dinpay B2C"),
        );

		$ajax = array();
		$ajax['status'] = 1;
		$ajax['html'] = $html;

        $order->setData('state', $this->order_status);
		$order->setStatus($this->order_status);
        $order->save();

		header("Content-type: application/json");		
		echo json_encode($ajax);
		exit();
    }

    /**
     * Payment refund
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Validator\Exception
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        return false;
    }

    /**
     * Determine method availability based on quote amount and config data
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if (!$this->getConfigData('merchant_code')) {
            return false;
        }

        if (!$this->getConfigData('public_key')) {
            return false;
        }

        if (!$this->getConfigData('private_key')) {
            return false;
        }

		if (!function_exists('curl_init')) {
			return false;
		}

		if (!function_exists('openssl_get_privatekey')) {
			return false;
		}

        return true;
    }

    /**
     * Validate payment method information object
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function validate()
    {
		//No validation required.
		
		return true;
	}

    /**
     * Availability for currency
     *
     * @param string $currencyCode
     * @return bool
     */
    public function canUseForCurrency($currencyCode)
    {
        return true;
    }


	public function processIpnRequest( $data ) {

			echo "SUCCESS";
			exit;
	}

}