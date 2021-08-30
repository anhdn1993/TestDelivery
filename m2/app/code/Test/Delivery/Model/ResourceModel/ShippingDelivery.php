<?php

namespace Test\Delivery\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class TimeInterval
 * @package Test\Delivery\Model\ResourceModel
 */
class ShippingDelivery extends AbstractDb
{
    /**
     * ShippingDelivery constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('test_shipping_delivery', 'delivery_id');
    }
}
