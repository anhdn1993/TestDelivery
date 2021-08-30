<?php

namespace Test\Delivery\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class DeliveryCommentShipment
 * @package Test\Delivery\Block
 */
class DeliveryCommentShipment extends Template
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

    /**
     * @var ShipmentRepositoryInterface
     */
    protected $shipmentRepositoryInterface;

    protected $timeSlotRepository;

    protected $orderExtensionAttributes;

    /**
     * DeliveryCommentShipment constructor.
     * @param Registry $coreRegistry
     * @param OrderRepositoryInterface $orderRepository
     * @param ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param ShipmentRepositoryInterface $shipmentRepositoryInterface
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        OrderRepositoryInterface $orderRepository,
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        ShipmentRepositoryInterface $shipmentRepositoryInterface,
        Template\Context $context,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->orderRepository = $orderRepository;
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        $this->shipmentRepositoryInterface = $shipmentRepositoryInterface;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getDeliveryComment()
    {
        try {
            $invoiceId = $this->getRequest()->getParam('shipment_id');

            $invoice = $this->shipmentRepositoryInterface->get($invoiceId);

            $orderId= $invoice->getOrder()->getId();

            $delivery = $this->shippingDeliveryRepository->getByOrderId($orderId);

            return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryComment() : '';
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
}
