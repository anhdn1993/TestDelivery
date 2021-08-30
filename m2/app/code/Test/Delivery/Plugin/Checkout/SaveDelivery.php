<?php

namespace Test\Delivery\Plugin\Checkout;

use Test\Delivery\Helper\Data;
use Magento\Quote\Model\QuoteIdMaskFactory;

class SaveDelivery
{
    /**
     * @var \Test\Delivery\Model\ShippingDeliveryFactory
     */
    protected $deliveryModelFactory;

    /**
     * @var \Test\Delivery\Model\ShippingDeliveryRepository
     */
    protected $deliveryRepository;

    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * SaveDelivery constructor.
     * @param \Test\Delivery\Model\ShippingDeliveryFactory $deliveryFactory
     * @param \Test\Delivery\Model\ShippingDeliveryRepository $deliveryRepository
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(
        \Test\Delivery\Model\ShippingDeliveryFactory $deliveryFactory,
        \Test\Delivery\Model\ShippingDeliveryRepository $deliveryRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        \Psr\Log\LoggerInterface $logger,
        Data $helper
    ) {
        $this->deliveryModelFactory = $deliveryFactory;
        $this->deliveryRepository = $deliveryRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param $cartId
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     */
    public function process(
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        $isGuest
    ) {
        if ($this->helper->isDeliveryShipmentEnabled() != false) {
            $extensionAttributes = $paymentMethod->getExtensionAttributes();
            if ($extensionAttributes) {
                $deliveryDate = $extensionAttributes->getDeliveryDate();
                $deliveryTime = $extensionAttributes->getDeliveryTime();
                $deliveryComment = $extensionAttributes->getDeliveryComment();
                $quoteId = 0;
                if ($isGuest) {
                    $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
                    $quoteId = $quoteIdMask->getQuoteId();
                } else {
                    $quoteId = $cartId;
                }

                /**
                 * Check if Delivery Info with this quote id exist in delivery table
                 */
                $delivery = $this->deliveryRepository->getByQuoteId($quoteId);
                if (!$delivery) {
                    $delivery = $this->deliveryModelFactory->create();
                }
                $delivery->setQuoteId($quoteId);
                $delivery->setDeliveryDate($deliveryDate);
                $delivery->setDeliveryTime($deliveryTime);
                $delivery->setDeliveryComment($deliveryComment);
                try {
                    $this->deliveryRepository->save($delivery);
                } catch (\Exception $e) {
                    $this->logger->error('Cannot save delivery date.' . $e->getMessage());
                }
            }
        }
    }
}
