<?php

namespace Test\Delivery\Block\Sales\Order\Info;

use Test\Delivery\Api\Data\ShippingDeliveryInterface;
use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Test\Delivery\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Delivery extends Template
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
     * @var Session
     */
    protected $checkoutSession;

    public function __construct(
        Context $context,
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        Data $helper,
        Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return bool|int
     */
    private function getCurrentOrderId()
    {
        if ($orderId = $this->getRequest()->getParam('order_id')) {
            return $orderId;
        }

        if ($lastRealOrder = $this->checkoutSession->getLastRealOrder()) {
            if ($orderId = $lastRealOrder->getId()) {
                return $orderId;
            }
        }

        return false;
    }

    /**
     * @return array|bool
     */
    public function getDeliveryDateFields()
    {
        if ($orderId = $this->getCurrentOrderId()) {
            $delivery = $this->shippingDeliveryRepository->getByOrderId($orderId);
        } else {
            return false;
        }

        if (!$delivery->getId()) {
            return false;
        }

        return $this->getDeliveryFields($delivery);
    }

    /**
     * @param $delivery ShippingDeliveryInterface
     * @return array
     */
    public function getDeliveryFields($delivery)
    {
        $date = $delivery->getDeliveryDate();
        $time = $delivery->getDeliveryTime();

        $fields = [];
        if (!empty($date)) {
            $fields[] = [
                'label' => __('Delivery Date'),
                'value' => $date
            ];
        }

        if ($time !== null && $time >= 0) {
            $fields[] = [
                'label' => __('Delivery Time'),
                'value' => $time,
            ];
        }

        if ($delivery->getDeliveryComment()) {
            $fields[] = [
                'label' => __('Delivery Comment'),
                'value' => $delivery->getDeliveryComment(),
            ];
        }

        return $fields;
    }
}
