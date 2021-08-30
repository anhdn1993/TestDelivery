<?php

namespace Test\Delivery\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class DeliveryDateShipment
 * @package Test\Delivery\Block
 */
class DeliveryDateShipment extends Template
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
     * DeliveryDateShipment constructor.
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
    public function getDeliveryDate()
    {
        try {
            $invoiceId = $this->getRequest()->getParam('shipment_id');

            $invoice = $this->shipmentRepositoryInterface->get($invoiceId);

            $orderId= $invoice->getOrder()->getId();

            $delivery = $this->shippingDeliveryRepository->getByOrderId($orderId);

            return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryDate() : '';
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
}
