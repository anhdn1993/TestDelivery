<?php

namespace Test\Delivery\Model\ResourceModel\Order\Grid;

use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OriginalCollection;

class Collection extends OriginalCollection
{

    protected function _renderFiltersBefore()
    {
        $joinTable = $this->getTable('test_shipping_delivery');
        $this->getSelect()->joinLeft($joinTable.' as cpev', 'main_table.entity_id = cpev.order_id', array('delivery_date', 'delivery_time', 'delivery_comment'))->distinct(true);
        parent::_renderFiltersBefore();
    }
}
