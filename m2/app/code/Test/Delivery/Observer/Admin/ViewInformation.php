<?php

namespace Test\Delivery\Observer\Admin;

use Test\Delivery\Block\Adminhtml\Sales\Order\Delivery;
use Test\Delivery\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ViewInformation
 */
class ViewInformation implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $configProvider;

    public function __construct(
        Data $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        if (!$this->configProvider->isDeliveryShipmentEnabled()) {
            return;
        }

        $elementName = $observer->getElementName();
        $transport = $observer->getTransport();
        $html = $transport->getOutput();
        $block = $observer->getLayout()->getBlock($elementName);
        $blockName = null;
        $checkDeliveryEnable = false;
        $flagName = 'test_delivery_' . $elementName;

        switch ($elementName) {
            case 'order_info':
                $blockName = Delivery::class;
                break;
        }

        if (empty($blockName)
            || ($checkDeliveryEnable && !$this->configProvider->isDeliveryShipmentEnabled())
            || $block->hasData($flagName)
        ) {
            return;
        }

        $deliveryBlock = $observer->getLayout()->createBlock($blockName);
        $html .= $deliveryBlock->toHtml();
        $block->setData($flagName, true);
        $transport->setOutput($html);
    }
}
