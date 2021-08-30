<?php


namespace Test\Delivery\Block\Adminhtml;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Setup\Exception;

class DeliveryInfoInvoice extends Template
{

    protected $coreRegistry;

    protected $orderRepository;

    protected $timeSlotRepository;

    protected $shippingDeliveryRepository;

    protected $orderExtensionAttributes;

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
        $invoice = $this->coreRegistry->registry('current_invoice');
        return $invoice ? $invoice->getOrder()->getId() : false;
    }

    public function getDeliveryComment()
    {
        $delivery = $this->shippingDeliveryRepository->getByOrderId($this->getOrderId());
        return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryComment() : '';
    }

    public function getDeliveryDate()
    {
        $delivery = $this->shippingDeliveryRepository->getByOrderId($this->getOrderId());

        return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryDate() : '';
    }

    public function getDeliveryTime()
    {
        $delivery = $this->shippingDeliveryRepository->getByOrderId($this->getOrderId());
        return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryTime() : '';
    }
}
