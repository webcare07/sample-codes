<?php
/**
 *
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DDPay\DDBillB2C\Controller\Ipn;

use Magento\Framework\Exception\RemoteServiceUnavailableException;

/**
 * Unified IPN controller for all supported Dinpay B2C methods
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \DDPay\DDBillB2C\Model\IpnFactory
     */
    protected $_ddbillb2c;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param \DDPay\DDBillB2C\Model\DDBillB2C $ddbillb2c
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \DDPay\DDBillB2C\Model\DDBillB2C $ddbillb2c,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_logger = $logger;
        $this->_ddbillb2c = $ddbillb2c;
        parent::__construct($context);
    }

    /**
     * Instantiate IPN model and pass IPN request to it
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
			return;
        }

        try {
            $data = $this->getRequest()->getPostValue();
			$this->_ddbillb2c->processIpnRequest($data);
        } catch (RemoteServiceUnavailableException $e) {
            $this->_logger->critical($e);
            $this->getResponse()->setStatusHeader(503, '1.1', 'Service Unavailable')->sendResponse();
            exit;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            $this->getResponse()->setHttpResponseCode(500);
            exit;
        }
    }
}
