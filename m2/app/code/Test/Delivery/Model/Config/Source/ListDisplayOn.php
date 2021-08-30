<?php

namespace Test\Delivery\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class ListDisplayOn implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'sales_order_view', 'label' => __('Order View Page (Backend)')],
            ['value' => 'sales_order_invoice_view', 'label' => __('Invoice View Page (Backend)')],
            ['value' => 'adminhtml_order_shipment_view', 'label' => __('Shipment View Page (Backend)')]
        ];
    }
}
