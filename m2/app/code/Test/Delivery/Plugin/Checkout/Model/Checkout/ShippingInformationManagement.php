<?php
namespace Test\Delivery\Plugin\Checkout\Model\Checkout;

class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Test\Delivery\Model\ShippingDeliveryFactory
     */
    protected $deliveryModelFactory;

    /**
     * @var \Test\Delivery\Model\ShippingDeliveryRepository
     */
    protected $deliveryRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Test\Delivery\Helper\Data
     */
    protected $helper;

    /**
     * ShippingInformationManagement constructor.
     * @param \Test\Delivery\Model\ShippingDeliveryFactory $deliveryFactory
     * @param \Test\Delivery\Model\ShippingDeliveryRepository $deliveryRepository
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Test\Delivery\Helper\Data $helper
     */
    public function __construct(
        \Test\Delivery\Model\ShippingDeliveryFactory $deliveryFactory,
        \Test\Delivery\Model\ShippingDeliveryRepository $deliveryRepository,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Psr\Log\LoggerInterface $logger,
        \Test\Delivery\Helper\Data $helper
    ) {
        $this->deliveryModelFactory = $deliveryFactory;
        $this->deliveryRepository = $deliveryRepository;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        //return if delivery date is not enabled
        if ($this->helper->isDeliveryShipmentEnabled() == false) {
            return [$cartId, $addressInformation];
        }

        $deliveryInfo = $addressInformation->getExtensionAttributes();
        $quoteId = $this->quoteRepository->getActive($cartId)->getEntityId();

        //return if get empty date
        if (empty($deliveryInfo->getDeliveryDate())) {
            return [$cartId, $addressInformation];
        }

        $delivery = $this->deliveryRepository->getByQuoteId($quoteId);
        if (!$delivery) {
            $delivery = $this->deliveryModelFactory->create();
        }
        $delivery->setQuoteId($quoteId);
        $delivery->setDeliveryDate($deliveryInfo->getDeliveryDate());
        $delivery->setDeliveryTime($deliveryInfo->getDeliveryTime());
        $delivery->setDeliveryComment($deliveryInfo->getDeliveryComment());
        try {
            $this->deliveryRepository->save($delivery);
        } catch (\Exception $e) {
            $this->logger->error('Cannot save delivery date.' . $e->getMessage());
        }
        return [$cartId, $addressInformation];
    }
}
