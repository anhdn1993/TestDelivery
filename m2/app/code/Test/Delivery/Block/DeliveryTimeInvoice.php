<?php

namespace Test\Delivery\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class DeliveryTimeInvoice
 * @package Test\Delivery\Block
 */
class DeliveryTimeInvoice extends Template
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
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    protected $timeSlotRepository;
    protected $orderExtensionAttributes;

    /**
     * DeliveryTimeInvoice constructor.
     * @param Registry $coreRegistry
     * @param OrderRepositoryInterface $orderRepository
     * @param ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        OrderRepositoryInterface $orderRepository,
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        Template\Context $context,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->orderRepository = $orderRepository;
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        $this->invoiceRepository = $invoiceRepository;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getDeliveryTime()
    {
        try {
            $invoiceId = $this->getRequest()->getParam('invoice_id');

            $invoice = $this->invoiceRepository->get($invoiceId);

            $orderId = $invoice->getOrder()->getId();

            $delivery = $this->shippingDeliveryRepository->getByOrderId($orderId);

             return ($delivery->getDeliveryId() ) ? $delivery->getDeliveryTime() : '';
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
}
