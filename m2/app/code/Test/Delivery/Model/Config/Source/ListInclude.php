<?php

namespace Test\Delivery\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class ListInclude implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'sales_order_print', 'label' => __('Order PDF')],
            ['value' => 'sales_order_printinvoice', 'label' => __('Invoice PDF')],
            ['value' => 'sales_order_printshipment', 'label' => __('Shipment PDF')],
            ['value' => 'sales_email_order_items', 'label' => __('Order Confirmation E-mail')],
            ['value' => 'sales_email_order_invoice_items', 'label' => __('Invoice E-mail')],
            ['value' => 'sales_email_order_shipment_items', 'label' => __('Shipment E-mail')],
        ];
    }
}
