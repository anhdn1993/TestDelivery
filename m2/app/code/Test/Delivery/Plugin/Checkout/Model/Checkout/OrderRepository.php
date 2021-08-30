<?php

namespace Test\Delivery\Plugin\Checkout\Model\Checkout;

use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Test\Delivery\Helper\Data;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepository
{
    /**
     * @var ShippingDeliveryRepositoryInterface
     */
    protected $shippingDeliveryRepository;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * OrderRepository constructor.
     * @param ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param Data $helper
     */
    public function __construct(
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        \Test\Delivery\Helper\Data $helper
    ) {
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        $this->helper = $helper;
    }

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterSave(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ) {

        //return if delivery date is not enabled
        if ($this->helper->isDeliveryShipmentEnabled() == false) {
            return $order;
        }

        $quoteId = $order->getQuoteId();

        $delivery = $this->shippingDeliveryRepository->getByQuoteId($quoteId);

        if (!$delivery) {
            return $order;
        }
        $delivery->setOrderId($order->getId());

        try {
            $this->shippingDeliveryRepository->save($delivery);
        } catch (\Exception $exception) {
            throwException($exception);
        }

        return $order;
    }
}
