<?php

namespace Test\Delivery\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\Registry;

/**
 * Class DeliveryComment
 * @package Test\Delivery\Block
 */
class DeliveryComment extends Template
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var ShippingDeliveryRepositoryInterface
     */
    protected $shippingDeliveryRepository;

    protected $timeSlotRepository;

    protected $orderExtensionAttributes;

    /**
     * DeliveryComment constructor.
     * @param Registry $coreRegistry
     * @param OrderRepositoryInterface $orderRepository
     * @param ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        OrderRepositoryInterface $orderRepository,
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        Template\Context $context,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->orderRepository = $orderRepository;
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return int|false
     */
    public function getOrderId()
    {
        $order = $this->coreRegistry->registry('current_order');
        return $order?$order->getId():false;
    }

    /**
     * @return string
     */
    public function getDeliveryComment()
    {
        $delivery = $this->shippingDeliveryRepository->getByOrderId($this->getOrderId());
        return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryComment() : '';
    }
}
