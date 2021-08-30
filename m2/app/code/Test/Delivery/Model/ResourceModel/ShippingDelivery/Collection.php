<?php

namespace Test\Delivery\Model\ResourceModel\ShippingDelivery;

/**
 * Class Collection
 * @package Test\Delivery\Model\ResourceModel\ShippingDelivery
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'time_interval_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'test_shipping_delivery_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'delivery_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Test\Delivery\Model\ShippingDelivery', 'Test\Delivery\Model\ResourceModel\ShippingDelivery');
    }
}
