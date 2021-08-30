<?php

namespace Test\Delivery\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class FomatDate implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'yy-mm-dd', 'label' => __('yy-MM-dd')],
            ['value' => 'MM/dd/yy', 'label' => __('MM/dd/yy')],
            ['value' => 'dd/MM/yy', 'label' => __('dd/MM/yy')],
        ];
    }
}
